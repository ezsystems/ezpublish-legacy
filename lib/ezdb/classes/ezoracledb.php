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

class eZOracleDB
{
    /*!
      Creates a new eZOracleDB object and connects to the database.
    */
    function eZOracleDB( $server, $user, $password, $db )
    {
        putenv( "ORACLE_SID=oracl" );
        putenv( "ORACLE_HOME=/usr/oracle" );
        $ini =& eZINI::instance();
        $this->OutputSQL = $ini->variable( "DatabaseSettings", "SQLOutput" ) == "enabled";

//        $this->Database = ora_logon( $user, $password );

//             $this->Database = OCILogon( $user, $password, $db );
        
        if ( function_exists( "OCILogon" ) )
        {

            $this->Database = OCILogon( $user, $password);//, $db );
//            OCIInternalDebug(1); 

//            print( $user . ' ' . $password . '<br/>' ) ;
            if ( $this->Database === false )
                $this->IsConnected = false;
            else
                $this->IsConnected = true;

            if ( $this->Database === false )
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
      Returns the driver type.
    */
    function isA()
    {
        return "oracle";
    }

    /*!
      Runs a query to the Oracle database.
    */
    function &query( $sql )
    {
        if ( $this->isConnected() )
        {
            if ( $this->OutputSQL )
                eZDebug::writeNotice( "$sql", "eZOracle::query()" );

            $statement = OCIParse( $this->Database, $sql );
            if ( $statement )
            {
               OCIExecute( $statement, OCI_DEFAULT );
               OCICommit ( $this->Database );

            }
            else
            {
                eZDebug::writeError( "Error: " . OCIError( $statement ), "eZOracleDB" );
            }
        }
        return $result;
    }

    /*!
      Returns the result from the SQL query as a PHP array.
    */
    function arrayQuery( $sql, $params=array() )
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
                if ( isset( $params["Limit"] ) and is_numeric( $params["Limit"] ) )
                {
                    $limit = $params["Limit"];
                }

                if ( isset( $params["Offset"] ) and is_numeric( $params["Offset"] ) )
                {
                    $offset = $params["Offset"];
                }
                if ( isset( $params["Column"] ) and is_numeric( $params["Column"] ) )
                    $column = $params["Column"];
            }



            if ( $limit != -1 )
            {
                 $sql = ' select * from ( ' . $sql . ' ) where ROWNUM BETWEEN ' . $offset .' AND ' . ( $limit + $offset ) ;
            }
            $statement =& OCIParse( $this->Database, $sql );
            if ( $this->OutputSQL )
                eZDebug::writeNotice( "$sql", "eZOracle::query()" );
//            print( "<br/> $sql <br> eZOracle::query()" );
            flush(); 
            OCIExecute( $statement );

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

    function subString( $string, $from, $len )
    {
        return " substr( $string, $from, $len ) "; 
    }

    /*!
      Starts a new transaction. Dummy function, not needed in oracle.
    */
    function begin()
    {

    }

    /*!
      Commits the transaction.
    */
    function commit()
    {
        if ( $this->isConnected() )
        {
            OCICommit( $this->Database );
        }
    }

    /*!
      Does a transaction rollback.
    */
    function rollback()
    {
        if ( $this->isConnected() )
        {
            OCIRollback( $this->Database );
        }
    }

    /*!
      Returns the last serial id, null is returned it does not
      exist.
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
      Will escape a string so it's ready to be inserted in the database.
    */
    function &escapeString( $str )
    {
        $str = str_replace ("'", "\'", $str );
        $str = str_replace ("\"", "\\\"", $str );
        return $str;
    }

    /*!
      Closes the database connection.
    */
    function close()
    {
        OCILogOff( $this->Database );
    }

    /*!
      \private
      Returns true if we're connected to the database backed.
    */
    function isConnected()
    {
        return $this->IsConnected;
    }

    /// Database connection
    var $Database;

    /// Contains true if we're connected to the database backend
    var $IsConnected = false;

}
?>
