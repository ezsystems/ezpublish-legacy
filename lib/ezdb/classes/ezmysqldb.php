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

class eZMySQLDB
{
    /*!
      Create a new eZMySQLDB object and connects to the database backend.
    */
    function eZMySQLDB( $server, $user, $password, $db, $charset, $builtinEncoding )
    {
        $this->DB = $db;
        $this->Server = $server;
        $this->User = $user;
        $this->Password = $password;
        $this->Charset = $charset;
        $this->UseBuiltinEncoding = $builtinEncoding;

        if ( $this->UseBuiltinEncoding )
        {
            include_once( "lib/ezi18n/classes/eztextcodec.php" );
            $this->OutputTextCodec =& eZTextCodec::instance( $charset );
            $this->InputTextCodec =& eZTextCodec::instance( eZTextCodec::internalCharset(), $charset );
        }

        $ini =& eZINI::instance();
        $socketPath =& $ini->variable( "DatabaseSettings", "Socket" );
        $this->OutputSQL = $ini->variable( "DatabaseSettings", "SQLOutput" ) == "enabled";

        if ( trim( $socketPath != "" ) && $socketPath != "disabled" )
        {
            ini_set( "mysql.default_socket", $socketPath );
        }

        $this->Database = @mysql_pconnect( $server, $user, $password );
        $numAttempts = 1;
        while ( $this->Database == false && $numAttempts < 5 )
        {
            usleep( 50 );
            $this->Database = @mysql_pconnect( $server, $user, $password );
            $numAttempts++;
        }

        $this->IsConnected = true;

        if ( $this->Database == false )
        {
            eZDebug::writeError( "Connection error: Couldn't connect to database. Please try again later or inform the system administrator.", "eZMySQLDB" );
            $this->IsConnected = false;
        }

        $ret = @mysql_select_db( $db, $this->Database );

        if ( !$ret )
        {
            eZDebug::writeError( "Connection error: " . @mysql_errno( $this->Database ) . ": " . @mysql_error( $this->Database ), "eZMySQLDB" );

            $this->IsConnected = false;
        }
    }

    /*!
      Returns the driver type.
    */
    function isA()
    {
        return "mysql";
    }

    /*!
     Returns the charset which the database is encoded in.
     \sa usesBuiltinEncoding
    */
    function charset()
    {
        return $this->Charset;
    }

    /*!
     Returns true if the database handles encoding itself, if not
     all queries and returned data must be decoded yourselves.
     \note This functionality might be removed in the future
    */
    function usesBuiltinEncoding()
    {
        return $this->UseBuiltinEncoding;
    }

    /*!
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function &query( $sql )
    {
        if ( $this->isConnected() )
        {
            $orig_sql = $sql;
            // The converted sql should not be output
            if ( $this->UseBuiltinEncoding )
                $sql = $this->InputTextCodec->convertString( $sql );
            if ( $this->OutputSQL )
                $start_time = microtime();
            $result =& mysql_query( $sql, $this->Database );
            if ( $this->OutputSQL )
            {
                $end_time = microtime();
                // Calculate time taken in ms
                list($usec, $sec) = explode( " ", $start_time );
                $start_val = ((float)$usec + (float)$sec);
                list($usec, $sec) = explode( " ", $end_time );
                $end_val = ((float)$usec + (float)$sec);
                $diff_val = $end_val - $start_val;
                $diff_val *= 1000.0;

                $num_rows = mysql_affected_rows( $this->Database );
                eZDebug::writeNotice( "$sql", "eZMySQLDB::query($num_rows rows, " . number_format( $diff_val, 3 ) . " ms) query number per page:" . $this->NumQueries++ );
            }

            $errorMsg = mysql_error( $this->Database );
            $errorNum = mysql_errno( $this->Database );

            if ( $result )
            {
                return $result;
            }
            else
            {
                eZDebug::writeError( "Query error: " . mysql_error( $this->Database ) . ". Query: ". $sql, "eZMySQLDB"  );
                $this->unlock();

                return false;
            }
            mysql_free_result( $result );
        }
    }

    /*!
      Executes an SQL query and returns the result as an array of accociative arrays.
    */
    function &arrayQuery( $sql, $params=array() )
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
                if ( isset( $params["Limit"] ) and is_numeric( $params["Limit"] ) )
                    $limit = $params["Limit"];

                if ( isset( $params["Offset"] ) and is_numeric( $params["Offset"] ) )
                    $offset = $params["Offset"];

                if ( isset( $params["Column"] ) and is_numeric( $params["Column"] ) )
                    $column = $params["Column"];
            }

            if ( $limit !== false and is_numeric( $limit ) )
            {
                $sql .= " LIMIT $offset, $limit ";
            }
            $result =& $this->query( $sql );

            if ( $result == false )
            {
                eZDebug::writeError( $this->Error, "eZMySQLDB" );
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
                                $conv_row[$key] =& $this->OutputTextCodec->convertString( $tmp_row[$key] );
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
                            $retArray[$i + $offset] =& $this->OutputTextCodec->convertString( $tmp_row[$column] );
                        else
                            $retArray[$i + $offset] =& $tmp_row[$column];
                    }
                }
            }
        }
        return $retArray;
    }

    function subString( $string, $from, $len )
    {
        return " substring( $string from $from for $len ) ";
    }
    /*!
      Locks a table
    */
    function lock( $table )
    {
        if ( $this->isConnected() )
        {
            $this->query( "LOCK TABLES $table WRITE" );
        }
    }

    /*!
      Releases table locks.
    */
    function unlock()
    {
        if ( $this->isConnected() )
        {
            $this->query( "UNLOCK TABLES" );
        }
    }

    /*!
      Starts a new transaction.
    */
    function begin()
    {
        if ( $this->isConnected() )
        {
            $this->query( "BEGIN WORK" );
        }
    }

    /*!
      Commits the transaction.
    */
    function commit()
    {
        if ( $this->isConnected() )
        {
            $this->query( "COMMIT" );
        }
    }

    /*!
      Cancels the transaction.
    */
    function rollback()
    {
        if ( $this->isConnected() )
        {
            $this->query( "ROLLBACK" );
        }
    }

    /*!
      Returns the last serial ID generated with an auto increment field.
    */
    function lastSerialID( $table, $column )
    {
        if ( $this->isConnected() )
        {
            return mysql_insert_id( $this->Database );
        }
        else
            return false;
    }

    /*!
      Will escape a string so it's ready to be inserted in the database.
    */
    function &escapeString( $str )
    {
        return mysql_escape_string( $str );
    }

    /*!
      Will close the database connection.
    */
    function close()
    {
        if ( $this->isConnected() )
        {
            @mysql_close( $this->Database );
        }
    }

    /*!
      \private
      Returns true if we're connected to the database backed.
    */
    function isConnected()
    {
        return $this->IsConnected;
    }


    /// Contains the current server
    var $Server;
    /// The current database name
    var $DB;
    /// Stores the database connection user
    var $User;
    /// Stores the database connection password
    var $Password;
    /// Contains the current database connection
    var $Database;
    /// The charset used for the current database
    var $Charset;
    /// Instance of a textcodec which handles text conversion, may not be set if no builtin encoding is used
    var $OutputTextCodec;
    /// True if a builtin encoder is to be used, this means that all input/output text is converted
    var $UseBuiltinEncoding;
    /// Setting if SQL queries should be sent to debug output
    var $OutputSQL;
    /// Contains true if we're connected to the database backend
    var $IsConnected = false;
    /// Contains number of queries sended to DB
    var $NumQueries = 0;

}

?>
