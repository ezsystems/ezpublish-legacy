<?php
//
// $Id$
//
// Definition of eZPostgreSQLLDB class
//
// Created on: <25-Feb-2002 14:08:32 bf>
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
  \class eZPostgreSQLDB ezpostgresqldb.php
  \ingroup eZDB
  \brief  The eZPostgreSQLDB class provides PostgreSQL database functions.

  eZPostgreSQLDB implementes PostgreSQLDB specific database code.

  \sa eZDB
*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

class eZPostgreSQLDB
{
    /*!
      Creates a new eZPostgreSQLDB object and connects to the database.
    */
    function eZPostgreSQLDB( $server,  $user, $password, $db )
    {
        $ini =& eZINI::instance();
        $this->OutputSQL = $ini->variable( "DatabaseSettings", "SQLOutput" ) == "enabled";

        if ( $ini->variable( "DatabaseSettings", "UsePersistentConnection" ) == "enabled" &&  function_exists( "pg_pconnect" ))
        {
            eZDebug::writeNotice( $ini->variable( "DatabaseSettings", "UsePersistentConnection" ), "using persistent connection" );
            $this->Database = pg_pconnect( "host=$server dbname=$db user=$user password=$password" );
            $this->IsConnected = true;
            // add error checking
//          eZDebug::writeError( "Error: could not connect to database." . pg_errormessage( $this->Database ), "eZPostgreSQLDB" );
        }elseif ( function_exists( "pg_connect" ) )
        {
            eZDebug::writeNotice( "using real connection",  "using real connection" );
            $this->Database = pg_connect( "host=$server dbname=$db user=$user password=$password" );
            $this->IsConnected = true;
        }
        else
        {
            $this->IsConnected = false;
            eZDebug::writeError( "PostgreSQL support not compiled into PHP, contact your system administrator", "eZPostgreSQLDB" );

        }
    }

    /*!
      Returns the driver type.
    */
    function isA()
    {
        return "postgresql";
    }

    /*!
      Runs a query to the PostgreSQL database.
    */
    function &query( $sql )
    {
        if ( $this->isConnected() )
        {
            if ( $this->OutputSQL )
                eZDebug::writeNotice( "$sql", "eZPostgreSQL::query() query number per page:" . $this->NumQueries++ );

            $result = @pg_exec( $this->Database, $sql );
            if ( !$result )
            {
                eZDebug::writeError( "Error: error executing query: $sql " . pg_errormessage ( $this->Database ), "eZPostgreSQLDB" );
            }
        }
        return $result;
    }


    /*!
      Runs a query to the PostgreSQL database and returns the result as an
      associative array.
    */
    function &arrayQuery( $sql, $params=array() )
    {
        $retArray = array();
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
                $sql .= " LIMIT $limit, $offset";
            }
            $result =& $this->query( $sql );

            if ( $result == false )
            {
                return false;
            }

            $offset = count( $retArray );

            if ( pg_numrows( $result ) > 0 )
            {
                if ( !is_string( $column ) )
                {
                    for($i = 0; $i < pg_numrows($result); $i++)
                    {
                        $retArray[$i + $offset] =& pg_fetch_array ( $result, $i );
                    }
                }
                else
                {
                    for ($i = 0; $i < pg_numrows( $result ); $i++ )
                    {
                        $tmp_row =& pg_fetch_array ( $result, $i );
                        $retArray[$i + $offset] =& $tmp_row[$column];
                    }
                }
            }
            pg_freeresult( $result );
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
             $this->query( "LOCK TABLE $table" );
        }
    }

    /*!
      Releases table locks. Not needed for PostgreSQL.
    */
    function unlock()
    {
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
            $this->query( "COMMIT WORK" );
        }
    }

    /*!
      Cancels the transaction.
    */
    function rollback()
    {
        if ( $this->isConnected() )
        {
            $this->query( "ROLLBACK WORK" );
        }
    }

    /*!
      Returns the next value which can be used as a unique index value.
    */
    function lastSerialID( $table, $column='id' )
    {
        if ( $this->isConnected() )
        {
            $sql = "SELECT currval( '" . $table . "_s')";
            $result = @pg_exec( $this->Database, $sql );
            if ( !$result )
            {
                eZDebug::writeError( "Error: error executing query: $sql " . pg_errormessage( $this->Database ), "eZPostgreSQLDB" );
            }

            if ( $result )
            {
                $array = pg_fetch_row( $result, 0 );
                $id = $array[0];
            }
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
        @pg_close();
    }

    /*!
      \private
      Returns true if we're connected to the database backed.
    */
    function isConnected()
    {
        return $this->IsConnected;
    }

    /// database connection
    var $Database;

    /// Contains true if we're connected to the database backend
    var $IsConnected = false;

    /// Variable which stores the status of debug output
    var $OutputSQL;
    /// Contains number of queries sended to DB
    var $NumQueries = 0;

}

?>
