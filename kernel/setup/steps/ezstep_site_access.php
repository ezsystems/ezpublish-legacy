<?php
//
// Definition of eZStepSiteAccess class
//
// Created on: <12-Aug-2003 17:35:47 kk>
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

/*! \file ezstep_site_access.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepSiteAccess ezstep_site_access.php
  \brief The class eZStepSiteAccess does

*/

class eZStepSiteAccess extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteAccess( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     \reimp
    */
    function processPostData()
    {
        $accessType = null;
        if( $this->Http->hasPostVariable( 'eZSetup_site_access' ) )
        {
            $accessType = $this->Http->postVariable( 'eZSetup_site_access' );
        }
        else
        {
            return false; // unknown error
        }

//         include_once( 'kernel/setup/ezsetuptypes.php' );
//         $siteTypes = eZSetupTypes();
//         $chosenType = $this->PersistenceList['site_type']['identifier'];
//         $siteType = $siteTypes[$chosenType];

//         $templateCount = $this->PersistenceList['site_templates']['count'];

        $portCounter = 8080;
//         $siteList = array( $siteType );
//         for ( $counter = 0; $counter < $templateCount; $counter++ )
//         for ( $counter = 0; $counter < count( $siteList ); $counter++ )
        $siteTypes = $this->chosenSiteTypes();
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];

            $siteType['access_type'] = $accessType;
//             $this->PersistenceList['site_templates_' . $counter]['access_type'] = $accessType;
//             if ( !isset( $this->PersistenceList['site_templates_' . $counter]['identifier'] ) )
//                 $this->PersistenceList['site_templates_' . $counter]['identifier'] = $siteList[$counter]['identifier'];
            if ( $accessType == 'url' )
            {
//                 $this->PersistenceList['site_templates_'.$counter]['access_type_value'] = $this->PersistenceList['site_templates_'.$counter]['identifier'];
//                 $this->PersistenceList['site_templates_'.$counter]['admin_access_type_value'] = $this->PersistenceList['site_templates_'.$counter]['identifier'] . '_admin';
                $siteType['access_type_value'] = $siteType['identifier'];
                $siteType['admin_access_type_value'] = $siteType['identifier'] . '_admin';
            }
            else if ( $accessType == 'port' )
            {
//                 $this->PersistenceList['site_templates_'.$counter]['access_type_value'] = $portCounter++;
//                 $this->PersistenceList['site_templates_'.$counter]['admin_access_type_value'] = $portCounter++;
                $siteType['access_type_value'] = $portCounter++;
                $siteType['admin_access_type_value'] = $portCounter++;
            }
            else if ( $accessType == 'hostname' )
            {
//                 $this->PersistenceList['site_templates_'.$counter]['access_type_value'] = $this->PersistenceList['site_templates_'.$counter]['identifier'] . '.' . eZSys::hostName();
//                 $this->PersistenceList['site_templates_'.$counter]['admin_access_type_value'] = $this->PersistenceList['site_templates_'.$counter]['identifier'] . '-admin.' . eZSys::hostName();
                $siteType['access_type_value'] = $siteType['identifier'] . '.' . eZSys::hostName();
                $siteType['admin_access_type_value'] = $siteType['identifier'] . '-admin.' . eZSys::hostName();
            }
            else
            {
//                 $this->PersistenceList['site_templates_'.$counter]['access_type_value'] = $accessType;
//                 $this->PersistenceList['site_templates_'.$counter]['admin_access_type_value'] = $accessType . '_admin';
                $siteType['access_type_value'] = $accessType;
                $siteType['admin_access_type_value'] = $accessType . '_admin';
            }
        }
        $this->storeSiteTypes( $siteTypes );
        return true;
    }

    /*!
     \reimp
     */
    function init()
    {
        // If windows installer, install using url site access
        include_once( "kernel/setup/ezsetuptests.php" );
        if ( eZSetupTestInstaller() == 'windows' )
        {
//             $templateCount = $this->PersistenceList['site_templates']['count'];
            $siteTypes = $this->chosenSiteTypes();

//             for ( $counter = 0; $counter < $templateCount; $counter++ )
            foreach ( array_keys( $siteTypes ) as $siteTypeKey )
            {
                $siteType =& $siteTypes[$siteTypeKey];
                $siteType['access_type'] = 'url';
                $siteType['access_type_value'] = $siteType['identifier'];
                $siteType['admin_access_type_value'] = $siteType['identifier'] . '_admin';

//                 $this->PersistenceList['site_templates_'.$counter]['access_type'] = 'url';
//                 $this->PersistenceList['site_templates_'.$counter]['access_type_value'] = $this->PersistenceList['site_templates_'.$counter]['identifier'];
//                 $this->PersistenceList['site_templates_'.$counter]['admin_access_type_value'] = $this->PersistenceList['site_templates_'.$counter]['identifier'] . '_admin';
            }
            $this->storeSiteTypes( $siteTypes );

            return true;
        }
        return false; // Always show site access
    }

    /*!
     \reimp
    */
    function &display()
    {
//         $siteTypes = $this->chosenSiteTypes();
//         $this->Tpl->setVariable( 'site_types', $siteTypes );
//         $this->Tpl->setVariable( 'error', $this->Error );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_access.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Site access' ),
                                        'url' => false ) );
        return $result;
    }

}

?>
