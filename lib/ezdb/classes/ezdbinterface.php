<?php
//
// $Id$
//
// Definition of eZDBInterface class
//
// Created on: <12-Feb-2002 15:54:17 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2003 eZ Systems.  All rights reserved.
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
  \class eZDBInterface ezdbinterface.php
  \ingroup eZDB
  \brief The eZDBInterface defines the interface for all database implementations

  \sa eZDB
*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

define( 'EZ_DB_RELATION_TABLE', 0 );
define( 'EZ_DB_RELATION_SEQUENCE', 1 );
define( 'EZ_DB_RELATION_TRIGGER', 2 );
define( 'EZ_DB_RELATION_VIEW', 3 );
define( 'EZ_DB_RELATION_INDEX', 4 );

define( 'EZ_DB_RELATION_TABLE_BIT', (1 << EZ_DB_RELATION_TABLE) );
define( 'EZ_DB_RELATION_SEQUENCE_BIT', (1 << EZ_DB_RELATION_SEQUENCE) );
define( 'EZ_DB_RELATION_TRIGGER_BIT', (1 << EZ_DB_RELATION_TRIGGER) );
define( 'EZ_DB_RELATION_VIEW_BIT', (1 << EZ_DB_RELATION_VIEW) );
define( 'EZ_DB_RELATION_INDEX_BIT', (1 << EZ_DB_RELATION_INDEX) );

define( 'EZ_DB_RELATION_NONE', 0 );
define( 'EZ_DB_RELATION_MASK', ( EZ_DB_RELATION_TABLE_BIT |
                                 EZ_DB_RELATION_SEQUENCE_BIT |
                                 EZ_DB_RELATION_TRIGGER_BIT |
                                 EZ_DB_RELATION_VIEW_BIT |
                                 EZ_DB_RELATION_INDEX_BIT ) );


class eZDBInterface
{
    /*!
      Create a new eZDBInterface object and connects to the database backend.
    */
    function eZDBInterface( $parameters )
    {
        $server = $parameters['server'];
        $user = $parameters['user'];
        $password = $parameters['password'];
        $db = $parameters['database'];
        $charset = $parameters['charset'];
        $builtinEncoding = $parameters['builtin_encoding'];
        $connectRetries = $parameters['connect_retries'];

        $this->DB = $db;
        $this->Server = $server;
        $this->User = $user;
        $this->Password = $password;
        $this->Charset = $charset;
        $this->UseBuiltinEncoding = $builtinEncoding;
        $this->ConnectRetries = $connectRetries;

        if ( $this->UseBuiltinEncoding )
        {
            include_once( "lib/ezi18n/classes/eztextcodec.php" );
            $this->OutputTextCodec =& eZTextCodec::instance( $charset );
            $this->InputTextCodec =& eZTextCodec::instance( eZTextCodec::internalCharset(), $charset );
        }

        $ini =& eZINI::instance();
        $this->OutputSQL = $ini->variable( "DatabaseSettings", "SQLOutput" ) == "enabled";

        $this->IsConnected = false;
        $this->NumQueries = 0;
        $this->StartTime = false;
        $this->EndTime = false;
        $this->TimeTaken = false;
    }

    /*!
     \private
     Writes a debug notice with query information.
    */
    function reportQuery( $class, $sql, $numRows, $timeTaken )
    {
        $rowText = '';
        if ( $numRows !== false )
            $rowText = "$numRows rows, ";
        eZDebug::writeNotice( "$sql", "$class::query($rowText" . number_format( $timeTaken, 3 ) . " ms) query number per page:" . $this->NumQueries++ );
    }

    /*!
     Enabled or disables sql output.
    */
    function setIsSQLOutputEnabled( $enabled )
    {
        $this->OutputSQL = $enabled;
    }

    /*!
     \private
     Records the current micro time. End the timer with endTimer() and
     fetch the result with timeTaken();
    */
    function startTimer()
    {
        $this->StartTime = microtime();
    }

    /*!
     \private
     Stops the current timer and calculates the time taken.
     \sa startTimer, timeTaken
    */
    function endTimer()
    {
        $this->EndTime = microtime();
        // Calculate time taken in ms
        list($usec, $sec) = explode( " ", $this->StartTime );
        $start_val = ((float)$usec + (float)$sec);
        list($usec, $sec) = explode( " ", $this->EndTime );
        $end_val = ((float)$usec + (float)$sec);
        $this->TimeTaken = $end_val - $start_val;
        $this->TimeTaken *= 1000.0;
    }

    /*!
     \private
     \return the micro time when the timer was start or false if no timer.
    */
    function startTime()
    {
        return $this->StartTime;
    }

    /*!
     \private
     \return the micro time when the timer was ended or false if no timer.
    */
    function endTime()
    {
        return $this->EndTime;
    }

    /*!
     \private
     \return the number of milliseconds the last operation took or false if no value.
    */
    function timeTaken()
    {
        return $this->TimeTaken;
    }

    /*!
     \pure
     Returns the name of driver, this is used to determine the name of the database type.
     For instance multiple implementations of the MySQL database will all return \c 'mysql'.
    */
    function databaseName()
    {
        return '';
    }

    /*!
     \return the number of times the db handler should try to reconnect if it fails.
    */
    function connectRetryCount()
    {
        return $this->ConnectRetries;
    }

    /*!
     \return the number of seconds the db handler should wait before rereconnecting.
     \note Currently returns 3 seconds.
    */
    function connectRetryWaitTime()
    {
        return 3;
    }

    /*!
     \pure
     \return a mask of the relation type it supports.
    */
    function supportedRelationTypeMask()
    {
        return EZ_DB_RELATION_NONE;
    }

    /*!
     \pure
     \return an array of the relation types.
    */
    function supportedRelationTypes()
    {
        return array();
    }

    /*!
     \pure
    */
    function databaseServerVersion()
    {
    }

    /*!
     \pure
    */
    function databaseClientVersion()
    {
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
      \pure
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function &query( $sql )
    {
    }

    /*!
      \pure
      Executes an SQL query and returns the result as an array of accociative arrays.
    */
    function &arrayQuery( $sql, $params = array() )
    {
    }

    /*!
      \pure
      Locks a table
    */
    function lock( $table )
    {
    }

    /*!
      \pure
      Releases table locks.
    */
    function unlock()
    {
    }

    /*!
      \pure
      Starts a new transaction.
    */
    function begin()
    {
    }

    /*!
      \pure
      Commits the transaction.
    */
    function commit()
    {
    }

    /*!
      \pure
      Cancels the transaction.
    */
    function rollback()
    {
    }

    /*!
      \pure
      \return the relation count for all relation types in the mask \a $relationMask.
    */
    function relationCounts( $relationMask )
    {
    }

    /*!
      \pure
      \return the number of relation objects in the database for the relation type \a $relationType.
    */
    function relationCount( $relationType = EZ_DB_RELATION_TABLE )
    {
    }

    /*!
      \pure
      \return the relation names in the database as an array for the relation type \a $relationType.
    */
    function relationList( $relationType = EZ_DB_RELATION_TABLE )
    {
    }

    /*!
      \pure
      Tries to remove the relation type \a $relationType named \a $relationName
      \return \c true if successful
    */
    function removeRelation( $relationName, $relationType )
    {
        return false;
    }

    /*!
     \protected
     \return the name of the relation type which is usable in SQL or false if unknown type.
     \note This function can be used by som database handlers which can operate on relation types using SQL.
    */
    function relationName( $relationType )
    {
        $names = array( EZ_DB_RELATION_TABLE => 'TABLE',
                        EZ_DB_RELATION_SEQUENCE => 'SEQUENCE',
                        EZ_DB_RELATION_TRIGGER => 'TRIGGER',
                        EZ_DB_RELATION_VIEW => 'VIEW',
                        EZ_DB_RELATION_INDEX => 'INDEX' );
        if ( !isset( $names[$relationType] ) )
            return false;
        return $names[$relationType];
    }

    /*!
      \pure
      Returns the last serial ID generated with an auto increment field.
    */
    function lastSerialID( $table, $column )
    {
    }

    /*!
      \pure
      Will escape a string so it's ready to be inserted in the database.
    */
    function &escapeString( $str )
    {
    }

    /*!
      \pure
      Will close the database connection.
    */
    function close()
    {
    }

    /*!
      Returns true if we're connected to the database backend.
    */
    function isConnected()
    {
        return $this->IsConnected;
    }

    /*!
      \pure
      Create a new database
    */
    function createDatabase()
    {
    }

    /*!
      \pure
      Sets the error message and error message number
    */
    function setError()
    {
    }

    /*!
      Returns the error message
    */
    function errorMessage()
    {
        return $this->ErrorMessage;
    }

    /*!
      Returns the error number
    */
    function errorNumber()
    {
        return $this->ErrorNumber;
    }

    /// \protectedsection
    /// Contains the current server
    var $Server;
    /// The current database name
    var $DB;
    /// Stores the database connection user
    var $User;
    /// Stores the database connection password
    var $Password;
    /// The charset used for the current database
    var $Charset;
    /// The number of times to retry a connection if it fails
    var $ConnectRetries;
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
    /// The start time of the timer
    var $StartTime;
    /// The end time of the tiemr
    var $EndTime;
    /// The total number of milliseconds the timer took
    var $TimeTaken;
    /// The database error message of the last executed function
    var $ErrorMessage;
    /// The database error message number of the last executed function
    var $ErrorNumber;
    /// If true then ErrorMessage and ErrorNumber get filled
    var $RecordError = true;
}

?>
