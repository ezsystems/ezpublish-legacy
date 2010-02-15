<?php
//
// Definition of eZStepDatabaseChoice class
//
// Created on: <11-Aug-2003 16:45:50 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file
*/


/*!
  \class eZStepDatabaseChoice ezstep_database_choice.php
  \brief The class eZStepDatabaseChoice does

*/

class eZStepDatabaseChoice extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepDatabaseChoice( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'database_choice', 'Database choice' );
    }

    function processPostData()
    {
        $databaseMap = eZSetupDatabaseMap();
        $this->PersistenceList['database_info'] = $databaseMap[$this->Http->postVariable( 'eZSetupDatabaseType' )];
        return true;
    }

    function init()
    {
        $databaseMap = eZSetupDatabaseMap();

        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();
            $extension = $data['Type'];
            $map = array( 'postgresql' => 'pgsql' );
            if ( isset( $map[$extension] ) )
                $extension = $map[$extension];

            if ( isset( $databaseMap[$extension] ) )
            {
                $this->PersistenceList['database_info'] = $databaseMap[$extension];
                return $this->kickstartContinueNextStep();
            }
        }

        if ( eZSetupTestInstaller() == 'windows' )
        {
            $this->PersistenceList['database_info'] = $databaseMap['mysql'];
            return true;
        }

        $databaseMap = eZSetupDatabaseMap();
        $database = null;
        $databaseCount = 0;
        if ( isset( $this->PersistenceList['database_extensions']['found'] ) )
        {
            $databaseExtensions = $this->PersistenceList['database_extensions']['found'];
            foreach ( $databaseExtensions as $extension )
            {
                if ( !isset( $databaseMap[$extension] ) )
                    continue;
                $database = $databaseMap[$extension];
                $database['name'] = null;
                $databaseCount++;
            }
        }

        if ( $databaseCount != 1 )
        {
            return false;
        }

        $this->PersistenceList['database_info'] = $database;

        return true;
    }

    function display()
    {
        $databaseMap = eZSetupDatabaseMap();
        $availableDatabases = array();
        $databaseList = array();
        if ( isset( $this->PersistenceList['database_extensions']['found'] ) )
        {
            $databaseExtensions = $this->PersistenceList['database_extensions']['found'];
            foreach ( $databaseExtensions as $extension )
            {
                if ( !isset( $databaseMap[$extension] ) )
                    continue;
                $databaseList[] = $databaseMap[$extension];
                if ( $databaseMap[$extension]['type'] == 'mysql' or $databaseMap[$extension]['type'] == 'mysqli' )
                {
                    $availableDatabases['mysql'] = true;
                }
                elseif ( $databaseMap[$extension]['type'] == 'postgresql' )
                {
                    $availableDatabases['postgresql'] = true;
                }
            }
        }

        $databaseInfo = $databaseList[0];
        if ( isset( $this->PersistenceList['database_info'] ) )
            $databaseInfo = $this->PersistenceList['database_info'];

        $this->Tpl->setVariable( 'database_list', $databaseList );
        $this->Tpl->setVariable( 'database_info', $databaseInfo );
        $this->Tpl->setVariable( 'available_databases', $availableDatabases );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/database_choice.tpl" );
        $result['path'] = array( array( 'text' => eZi18n::translate( 'design/standard/setup/init',
                                                          'Database choice' ),
                                        'url' => false ) );
        return $result;
    }

}

?>