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
include_once( "lib/ezdb/classes/ezdbinterface.php" );

class eZPostgreSQLDB extends eZDBInterface
{
    /*!
      Creates a new eZPostgreSQLDB object and connects to the database.
    */
    function eZPostgreSQLDB( $parameters )
    {
        $this->eZDBInterface( $parameters );

        $ini =& eZINI::instance();

        $server = $this->Server;
        $db = $this->DB;
        $user = $this->User;
        $password = $this->Password;

        if ( $ini->variable( "DatabaseSettings", "UsePersistentConnection" ) == "enabled" &&  function_exists( "pg_pconnect" ))
        {
            eZDebug::writeNotice( $ini->variable( "DatabaseSettings", "UsePersistentConnection" ), "using persistent connection" );
            $this->DBConnection = pg_pconnect( "host=$server dbname=$db user=$user password=$password" );
            $this->IsConnected = true;
            // add error checking
//          eZDebug::writeError( "Error: could not connect to database." . pg_errormessage( $this->DBConnection ), "eZPostgreSQLDB" );
        }
        else if ( function_exists( "pg_connect" ) )
        {
            eZDebug::writeNotice( "using real connection",  "using real connection" );
            $this->DBConnection = pg_connect( "host=$server dbname=$db user=$user password=$password" );
            $this->IsConnected = true;
        }
        else
        {
            $this->IsConnected = false;
            eZDebug::writeError( "PostgreSQL support not compiled into PHP, contact your system administrator", "eZPostgreSQLDB" );

        }
    }

    /*!
     \reimp
    */
    function databaseName()
    {
        return 'postgresql';
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

            $result = @pg_exec( $this->DBConnection, $sql );

            if ( $this->OutputSQL )
            {
                $this->endTimer();
                $this->reportQuery( 'eZPostgreSQLDB', $sql, false, $this->timeTaken() );
            }

            if ( !$result )
            {
                eZDebug::writeError( "Error: error executing query: $sql " . pg_errormessage ( $this->DBConnection ), "eZPostgreSQLDB" );
            }
        }
        return $result;
    }


    /*!
     \reimp
    */
    function &arrayQuery( $sql, $params = array() )
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

    /*!
     \private
    */
    function subString( $string, $from, $len )
    {
        return " substring( $string from $from for $len ) ";
    }

    function cancatString( $strings = array() )
    {
        $str = implode( " || " , $strings );
        return "  $str   ";
    }

    /*!
     \reimp
    */
    function lock( $table )
    {
        if ( $this->isConnected() )
        {
             $this->query( "LOCK TABLE $table" );
        }
    }

    /*!
     \reimp
    */
    function unlock()
    {
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
            $this->query( "COMMIT WORK" );
        }
    }

    /*!
     \reimp
    */
    function rollback()
    {
        if ( $this->isConnected() )
        {
            $this->query( "ROLLBACK WORK" );
        }
    }

    /*!
     \reimp
    */
    function lastSerialID( $table, $column = 'id' )
    {
        if ( $this->isConnected() )
        {
            $sql = "SELECT currval( '" . $table . "_s')";
            $result = @pg_exec( $this->DBConnection, $sql );
            if ( !$result )
            {
                eZDebug::writeError( "Error: error executing query: $sql " . pg_errormessage( $this->DBConnection ), "eZPostgreSQLDB" );
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
        @pg_close();
    }

    /// \privatesection
    /// database connection
    var $DBConnection;

}

?>
