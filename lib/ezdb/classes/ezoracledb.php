<?php
//
// Definition of eZOracleDB class
//
// Created on: <25-Feb-2002 14:50:11 ce>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

//!
/*!
  \class eZOracleDB ezoracledb.php
  \ingroup eZDB
  \brief This eZOracleDB class provides Oracle database functions.

  eZOracleDB implements OracleDB spesific database code.
*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezdb/classes/ezdbinterface.php" );

class eZOracleDB extends eZDBInterface
{
    /*!
      Creates a new eZOracleDB object and connects to the database.
    */
    function eZOracleDB( $parameters )
    {
        $this->eZDBInterface( $parameters );

        $server = $this->Server;
        $user = $this->User;
        $password = $this->Password;
        $db = $this->DB;

        putenv( "ORACLE_SID=oracl" );
        putenv( "ORACLE_HOME=/usr/oracle" );
        $ini =& eZINI::instance();

//        $this->DBConnection = ora_logon( $user, $password );

//             $this->DBConnection = OCILogon( $user, $password, $db );

        if ( function_exists( "OCILogon" ) )
        {

            $this->DBConnection = OCILogon( $user, $password);//, $db );
//            OCIInternalDebug(1);

            if ( $this->DBConnection === false )
                $this->IsConnected = false;
            else
                $this->IsConnected = true;

            if ( $this->DBConnection === false )
            {
                $error =& OCIError();
                eZDebug::writeError( "Connection error(" . $error["code"] . "):\n". $error["message"] .  " ", "eZOracleDB" );
            }
        }
        else
        {
            eZDebug::writeError( "Oracle support not compiled in PHP", "eZOracleDB" );
            $this->IsConnected = false;
        }
    }

    /*!
      \reimp
    */
    function databaseName()
    {
        return 'oracle';
    }

    /*!
      \reimp
    */
    function &query( $sql )
    {
        if ( $this->isConnected() )
        {
            if ( $this->OutputSQL )
                $this->startTimer();

            $statement = OCIParse( $this->DBConnection, $sql );
            if ( $statement )
            {
               OCIExecute( $statement, OCI_DEFAULT );
               OCICommit ( $this->DBConnection );

               if ( $this->OutputSQL )
               {
                   $this->endTimer();
                   $this->reportQuery( 'eZOracleDB', $sql, false, $this->timeTaken() );
               }
            }
            else
            {
                eZDebug::writeError( "Error: " . OCIError( $statement ), "eZOracleDB" );
            }
        }
        return $result;
    }

    /*!
      \reimp
    */
    function arrayQuery( $sql, $params = array() )
    {
        $resultArray = array();

        if ( $this->isConnected() )
        {

            $limit = -1;
            $offset = 0;
            // check for array parameters
            if ( is_array( $params ) )
            {
//                $params = $min;


                $column = false;
                if ( isset( $params["limit"] ) and is_numeric( $params["limit"] ) )
                {
                    $limit = $params["limit"];
                }

                if ( isset( $params["offset"] ) and is_numeric( $params["offset"] ) )
                {
                    $offset = $params["offset"];
                }
                if ( isset( $params["column"] ) and is_numeric( $params["column"] ) )
                    $column = $params["column"];
            }

            if ( $limit != -1 )
            {
                 $sql = ' select * from ( ' . $sql . ' ) where ROWNUM BETWEEN ' . $offset .' AND ' . ( $limit + $offset ) ;
            }

            if ( $this->OutputSQL )
                $this->startTimer();

            $statement =& OCIParse( $this->DBConnection, $sql );
//            print( "<br/> $sql <br> eZOracle::query()" );
            flush();
            OCIExecute( $statement );

            if ( $this->OutputSQL )
            {
                $this->endTimer();
                $this->reportQuery( 'eZOracleDB', $sql, false, $this->timeTaken() );
            }

            $numCols = OCINumcols( $statement );
            $resultArray = array();
            $row = array();

            while ( OCIFetchInto( $statement, $row, OCI_ASSOC|OCI_RETURN_LOBS|OCI_RETURN_NULLS ) )
            {
                reset( $row );
                while ( ( $key = key( $row ) ) !== null )
                {
                    $row[strtolower( $key )] =& $row[$key];
                    next( $row );
                }

                $resultArray[] = $row;
            }
        }
        unset($statement);
        unset($row);

        return $resultArray;
    }

    /*!
     \private
    */
    function subString( $string, $from, $len )
    {
        return " substr( $string, $from, $len ) ";
    }

    /*!
      \reimp
    */
    function begin()
    {
    }

    /*!
      \reimp
    */
    function commit()
    {
        if ( $this->isConnected() )
        {
            OCICommit( $this->DBConnection );
        }
    }

    /*!
      \reimp
    */
    function rollback()
    {
        if ( $this->isConnected() )
        {
            OCIRollback( $this->DBConnection );
        }
    }

    /*!
      \reimp
    */
    function &lastSerialID( $table, $column )
    {
        $id = null;
        if ( $this->isConnected() )
        {
            $sql = "SELECT " . $table . "_s.currval from DUAL";
            $res =& $this->arrayQuery( $sql );
            $id = $res[0]["currval"];
        }

        return $id;
    }

    /*!
      \reimp
    */
    function &escapeString( $str )
    {
        $str = str_replace ("'", "\'", $str );
        $str = str_replace ("\"", "\\\"", $str );
        return $str;
    }

    /*!
      \reimp
    */
    function close()
    {
        OCILogOff( $this->DBConnection );
    }

    /// Database connection
    var $DBConnection;

}

?>
