<?php
//
// $Id$
//
// Definition of eZMySQLDB class
//
// Created on: <12-Feb-2002 15:54:17 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

/*!
  \class eZMySQLDB ezmysqldb.php
  \ingroup eZDB
  \brief The eZMySQLDB class provides MySQL implementation of the database interface.

  eZMySQLDB is the MySQL implementation of eZDB.
  \sa eZDB
*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezdb/classes/ezdbinterface.php" );

class eZMySQLDB extends eZDBInterface
{
    /*!
      Create a new eZMySQLDB object and connects to the database backend.
    */
    function eZMySQLDB( $parameters )
    {
        $this->eZDBInterface( $parameters );

        $ini =& eZINI::instance();
        $socketPath =& $ini->variable( "DatabaseSettings", "Socket" );

        if ( trim( $socketPath != "" ) && $socketPath != "disabled" )
        {
            ini_set( "mysql.default_socket", $socketPath );
        }

        $this->DBConnection = @mysql_pconnect( $this->Server, $this->User, $this->Password );
        $numAttempts = 1;
        while ( $this->DBConnection == false && $numAttempts < 5 )
        {
            usleep( 50 );
            $this->DBConnection = @mysql_pconnect( $this->Server, $this->User, $this->Password );
            $numAttempts++;
        }
        $this->setError();

        $this->IsConnected = true;

        if ( $this->DBConnection == false )
        {
            eZDebug::writeError( "Connection error: Couldn't connect to database. Please try again later or inform the system administrator.", "eZMySQLDB" );
            $this->IsConnected = false;
        }

        if ( $this->isConnected() )
        {
            $ret = @mysql_select_db( $this->DB, $this->DBConnection );
            $this->setError();

            if ( !$ret )
            {
                eZDebug::writeError( "Connection error: " . @mysql_errno( $this->DBConnection ) . ": " . @mysql_error( $this->DBConnection ), "eZMySQLDB" );
                $this->IsConnected = false;
            }
        }
        eZDebug::createAccumulatorGroup( 'mysql_total', 'Mysql Total' );
    }

    /*!
     \reimp
    */
    function databaseName()
    {
        return 'mysql';
    }

    /*!
     \reimp
    */
    function &query( $sql )
    {
        if ( $this->isConnected() )
        {
            $orig_sql = $sql;
            // The converted sql should not be output
            if ( $this->UseBuiltinEncoding )
            {
                eZDebug::accumulatorStart( 'mysql_conversion', 'mysql_total', 'String conversion in mysql' );
                $sql = $this->InputTextCodec->convertString( $sql );
                eZDebug::accumulatorStop( 'mysql_conversion' );
            }

            if ( $this->OutputSQL )
            {
                $this->startTimer();
                eZDebug::accumulatorStart( 'mysql_query', 'mysql_total', 'Mysql_queries' );
            }
            $result =& mysql_query( $sql, $this->DBConnection );
            if ( $this->RecordError )
                $this->setError();

            if ( $this->OutputSQL )
            {
                $this->endTimer();
                eZDebug::accumulatorStop( 'mysql_query' );

                $num_rows = mysql_affected_rows( $this->DBConnection );
                $this->reportQuery( 'eZMySQLDB', $sql, $num_rows, $this->timeTaken() );
            }

            if ( $result )
            {
                return $result;
            }
            else
            {
                eZDebug::writeError( "Query error: " . mysql_error( $this->DBConnection ) . ". Query: ". $sql, "eZMySQLDB"  );
                $this->RecordError = false;
                $this->unlock();
                $this->RecordError = true;

                return false;
            }
            mysql_free_result( $result );
        }
        else
        {
            eZDebug::writeError( "Trying to do a query without being connected to a database!", "eZMySQLDB"  );
        }
    }

    /*!
     \reimp
    */
    function &arrayQuery( $sql, $params = array() )
    {
        $retArray = array();
        if ( $this->isConnected() )
        {
            $limit = false;
            $offset = 0;
            $column = false;
            // check for array parameters
            if ( is_array( $params ) )
            {
                if ( isset( $params["limit"] ) and is_numeric( $params["limit"] ) )
                    $limit = $params["limit"];

                if ( isset( $params["offset"] ) and is_numeric( $params["offset"] ) )
                    $offset = $params["offset"];

                if ( isset( $params["column"] ) and is_numeric( $params["column"] ) )
                    $column = $params["column"];
            }

            if ( $limit !== false and is_numeric( $limit ) )
            {
                $sql .= " LIMIT $offset, $limit ";
            }
            $result =& $this->query( $sql );

            if ( $result == false )
            {
                $this->reportQuery( 'eZMySQLDB', $sql, false, false );
                return false;
            }

            if ( mysql_num_rows( $result ) > 0 )
            {
                if ( !is_string( $column ) )
                {
                    for ( $i=0; $i < mysql_num_rows($result); $i++ )
                    {
                        if ( $this->UseBuiltinEncoding )
                        {
                            $tmp_row =& mysql_fetch_array( $result );
                            unset( $conv_row );
                            $conv_row = array();
                            reset( $tmp_row );
                            while( ( $key = key( $tmp_row ) ) !== null )
                            {
                                eZDebug::accumulatorStart( 'mysql_conversion', 'mysql_total', 'String conversion in mysql' );
                                $conv_row[$key] =& $this->OutputTextCodec->convertString( $tmp_row[$key] );
                                eZDebug::accumulatorStop( 'mysql_conversion' );
                                next( $tmp_row );
                            }
                            $retArray[$i + $offset] =& $conv_row;
                        }
                        else
                            $retArray[$i + $offset] =& mysql_fetch_array( $result );
                    }
                }
                else
                {
                    for ( $i=0; $i < mysql_num_rows($result); $i++ )
                    {
                        $tmp_row =& mysql_fetch_array( $result );
                        if ( $this->UseBuiltinEncoding )
                        {
                            eZDebug::accumulatorStart( 'mysql_conversion', 'mysql_total', 'String conversion in mysql' );
                            $retArray[$i + $offset] =& $this->OutputTextCodec->convertString( $tmp_row[$column] );
                            eZDebug::accumulatorStop( 'mysql_conversion' );
                        }
                        else
                            $retArray[$i + $offset] =& $tmp_row[$column];
                    }
                }
            }
        }
        return $retArray;
    }

    /*!
     \private
    */
    function subString( $string, $from, $len = null )
    {
        if ( $len == null )
        {
            return " substring( $string from $from ) ";
        }else
        {
            return " substring( $string from $from for $len ) ";
        }
    }

    function cancatString( $strings = array() )
    {
        $str = implode( "," , $strings );
        return " concat( $str  ) ";
    }
    /*!
     \reimp
    */
    function lock( $table )
    {
        if ( $this->isConnected() )
        {
            $this->query( "LOCK TABLES $table WRITE" );
        }
    }

    /*!
     \reimp
    */
    function unlock()
    {
        if ( $this->isConnected() )
        {
            $this->query( "UNLOCK TABLES" );
        }
    }

    /*!
     \reimp
    */
    function begin()
    {
        if ( $this->isConnected() )
        {
            $this->query( "BEGIN WORK" );
        }
    }

    /*!
     \reimp
    */
    function commit()
    {
        if ( $this->isConnected() )
        {
            $this->query( "COMMIT" );
        }
    }

    /*!
     \reimp
    */
    function rollback()
    {
        if ( $this->isConnected() )
        {
            $this->query( "ROLLBACK" );
        }
    }

    /*!
     \reimp
    */
    function lastSerialID( $table, $column )
    {
        if ( $this->isConnected() )
        {
            return mysql_insert_id( $this->DBConnection );
        }
        else
            return false;
    }

    /*!
     \reimp
    */
    function &escapeString( $str )
    {
        return mysql_escape_string( $str );
    }

    /*!
     \reimp
    */
    function close()
    {
        if ( $this->isConnected() )
        {
            @mysql_close( $this->DBConnection );
        }
    }

    /*!
     \reimp
    */
    function createDatabase( $dbName )
    {
        if ( $this->DBConnection != false )
        {
            mysql_create_db( $dbName, $this->DBConnection );
            $this->setError();
        }
    }

    /*!
     \reimp
    */
    function setError()
    {
        if ( $this->DBConnection )
        {
            $this->ErrorMessage = mysql_error( $this->DBConnection );
            $this->ErrorNumber = mysql_errno( $this->DBConnection );
        }
        else
        {
            $this->ErrorMessage = mysql_error();
            $this->ErrorNumber = mysql_errno();
        }
    }


    /// \privatesection
    /// Contains the current database connection
    var $DBConnection;
}

?>
