<?php
//
// Definition of eZStepDatabaseCreate class
//
// Created on: <14-Aug-2003 11:34:00 kk>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezstep_database_create.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( 'lib/ezdb/classes/ezdbtool.php' );
include_once( 'kernel/common/i18n.php' );

/*!
  \class eZStepDatabaseCreate ezstep_database_create.php
  \brief The class eZStepDatabaseCreate does

*/

class eZStepDatabaseCreate extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepDatabaseCreate( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        return true; // Always asume everything worked
    }

    /*!
     \reimp
     */
    function init()
    {
        $databaseMap = eZSetupDatabaseMap();

        // Get password
        if ( isset( $this->PersistenceList['database_info']['password'] ) )
        {
            $password = $this->PersistenceList['database_info']['password'];
        }

        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];

        $dbStatus = array();
        $dbDriver = $databaseInfo['info']['driver'];
        $dbServer = $databaseInfo['server'];
        $dbName = $databaseInfo['name'];
        $dbUser = $databaseInfo['user'];
        $dbSocket = $databaseInfo['socket'];
        if ( trim( $dbSocket ) == '' )
            $dbSocket = false;
        $dbPwd = $password;
        $dbCharset = 'iso-8859-1';
        $dbParameters = array( 'server' => $dbServer,
                               'user' => $dbUser,
                               'password' => $dbPwd,
                               'socket' => $dbSocket,
                               'database' => $dbName,
                               'charset' => $dbCharset );
        $db =& eZDB::instance( $dbDriver, $dbParameters, true );
        $dbStatus['connected'] = $db->isConnected();

        $dbError = false;
        $demoDataResult = true;
        if ( $dbStatus['connected'] )
        {
            if ( $this->PersistenceList['database_info']['existing_database'] == 2 )
            {
                set_time_limit( 0 );
                $db->OutputSQL = false;
                if ( !eZDBTool::cleanup( $db ) )
                    $dbError = true;
            }

            if ( $this->PersistenceList['database_info']['existing_database'] != 3 )
            {
                $setupINI =& eZINI::instance( 'setup.ini' );
                $sqlSchemaFile = $setupINI->variable( 'DatabaseSettings', 'SQLSchema' );
                $sqlFile = $setupINI->variable( 'DatabaseSettings', 'CleanSQLData' );

                $result = $this->doQuery( $db, 'kernel/sql/', $sqlSchemaFile );
                $result = $result && $this->doQuery( $db, 'kernel/sql/', $sqlFile );
            }
        }

        return true; // create database and move forward
    }

    /*!
     \reimp
     */
    function &display()
    {
    }

    /*!
	 Prepare the sql file so we can create the database.
    */
    function prepareSqlQuery( &$file, &$buffer )
    {

        $sqlQueryArray = array();
        while( count( $sqlQueryArray ) == 0 && !feof( $file ) )
        {
            $buffer  .= fread( $file, 4096 );
            if ( $buffer )
            {
                // Fix SQL file by deleting all comments and newlines
//            eZDebug::writeDebug( $buffer, "read data" );
                $sqlQuery = preg_replace( array( "/^#.*\n" . "/m", "/^--.*\n" . "/m" ), array( "", "" ),  $buffer );
//            eZDebug::writeDebug( $sqlQuery, "read data" );

                // Split the query into an array
                $sqlQueryArray = preg_split( '/;\n/m', $sqlQuery );

                if ( preg_match( '/;\n/m', $sqlQueryArray[ count( $sqlQueryArray ) -1 ] ) )
                {
                    $buffer = '';
                }
                else
                {
                    $buffer = $sqlQueryArray[ count( $sqlQueryArray ) -1 ];
                    array_splice( $sqlQueryArray, count( $sqlQueryArray ) -1 , 1 );
                }
            }
            else
            {
                return $sqlQueryArray;

            }
        }
        return $sqlQueryArray;

    }

    function doQuery( &$dbObject, $path, $sqlFile )
    {
        $type = $dbObject->databaseName();

        include_once( 'lib/ezutils/classes/ezdir.php' );
        $sqlFileName = eZDir::path( array( $path, $type, $sqlFile ) );
        $sqlFileHandler = fopen( $sqlFileName, 'r' );
        $buffer = '';
        $done = false;
        while(  count( ( $sqlArray = $this->prepareSqlQuery( $sqlFileHandler, $buffer ) ) ) > 0 )
        {
            // Turn unneccessary SQL debug output off
            $dbObject->OutputSQL = false;
            if ( $sqlArray && is_array( $sqlArray ) )
            {
                $done = true;
                foreach( $sqlArray as $singleQuery )
                {
                    $singleQuery = preg_replace( "/\n|\r\n|\r/", " ", $singleQuery );
                    if ( trim( $singleQuery ) != "" )
                    {
//                    eZDebug::writeDebug( $singleQuery );
                        $dbObject->query( $singleQuery );
                        if ( $dbObject->errorNumber() != 0 )
                            return false;
                    }
                }

            }
        }
        return $done;

    }

    var $Error = 0;
}

?>
