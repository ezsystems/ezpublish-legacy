<?php
//
// Definition of eZStepDatabaseChoice class
//
// Created on: <11-Aug-2003 16:45:50 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezstep_database_choice.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php' );
include_once( 'kernel/setup/ezsetupcommon.php' );
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepDatabaseChoice ezstep_database_choice.php
  \brief The class eZStepDatabaseChoice does

*/

class eZStepDatabaseChoice extends eZStepInstaller
{
    /*!
     Constructor
    \reimp
    */
    function eZStepDatabaseChoice( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        $databaseMap = eZSetupDatabaseMap();
        $this->PersistenceList['database_info'] = $databaseMap[$this->Http->postVariable( 'eZSetupDatabaseType' )];
        return true;
    }

    /*!
     \reimp
     */
    function init()
    {
        $databaseMap = eZSetupDatabaseMap();

        include_once( "kernel/setup/ezsetuptests.php" );
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

        if( $databaseCount != 1 )
        {
            return false;
        }

        $this->PersistenceList['database_info'] = $database;

        return true;
    }

    /*!
     \reimp
     */
    function &display()
    {
        $databaseMap = eZSetupDatabaseMap();
        $databaseList = array();
        if ( isset( $this->PersistenceList['database_extensions']['found'] ) )
        {
            $databaseExtensions = $this->PersistenceList['database_extensions']['found'];
            foreach ( $databaseExtensions as $extension )
            {
                if ( !isset( $databaseMap[$extension] ) )
                    continue;
                $databaseList[] = $databaseMap[$extension];
            }
        }

        $this->Tpl->setVariable( 'database_list', $databaseList );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/database_choice.tpl" );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Database choice' ),
                                        'url' => false ) );
        return $result;
    }

}

?>
