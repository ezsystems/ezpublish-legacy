<?php
//
// Definition of eZStepFinal class
//
// Created on: <13-Aug-2003 14:09:47 kk>
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

/*! \file ezstep_final.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/setup/ezsetuptests.php" );
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepFinal ezstep_final.php
  \brief The class eZStepFinal does

*/

class eZStepFinal extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepFinal( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'final', 'Final' );
    }

    /*!
     \reimp
    */
    function processPostData()
    {
        return true; // Last step, but always proceede
    }

    /*!
     \reimp
     */
    function init()
    {
        return false; // Always show
    }

    /*!
     \reimp
    */
    function &display()
    {
        $siteTypes = $this->chosenSiteTypes();
//         for ( $counter = 0; $counter < $this->PersistenceList['site_templates']['count']; $counter++ )
        $counter = 0;
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
//             $templates[$counter] = $this->PersistenceList['site_templates_'.$counter];
//             eZDebug::writeDebug( $templates[$counter], '$templates[$counter]' );
//             $url = $templates[$counter]['url'];
            $url = $siteType['url'];
            if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url ) )
            {
                $url = 'http://' . $url;
            }
            $currentURL = $url;
            $adminURL = $url;
//             if ( $templates[$counter]['access_type'] == 'url' )
            if ( $siteType['access_type'] == 'url' )
            {
//                 $url .= '/' . $templates[$counter]['access_type_value'];
//                 $adminURL .= '/' . $templates[$counter]['admin_access_type_value'];
                $url .= '/' . $siteType['access_type_value'];
                $adminURL .= '/' . $siteType['admin_access_type_value'];
            }
//             else if ( $templates[$counter]['access_type'] == 'hostname' )
            else if ( $siteType['access_type'] == 'hostname' )
            {
//                 $url = $templates[$counter]['access_type_value'];
//                 $adminURL = $templates[$counter]['admin_access_type_value'];
                $url = $siteType['access_type_value'];
                $adminURL = $siteType['admin_access_type_value'];
                if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url ) )
                    $url = 'http://' . $url;
                if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $adminURL ) )
                    $adminURL = 'http://' . $adminURL;
                $url .= eZSys::indexDir( false );
                $adminURL .= eZSys::indexDir( false );
            }
//             else if ( $templates[$counter]['access_type'] == 'port' )
            else if ( $siteType['access_type'] == 'port' )
            {
//                 $url = eZHTTPTool::createRedirectURL( $currentURL, array( 'override_port' => $templates[$counter]['access_type_value'] ) );
//                 $adminURL = eZHTTPTool::createRedirectURL( $currentURL, array( 'override_port' => $templates[$counter]['admin_access_type_value'] ) );
                $url = eZHTTPTool::createRedirectURL( $currentURL, array( 'override_port' => $siteType['access_type_value'] ) );
                $adminURL = eZHTTPTool::createRedirectURL( $currentURL, array( 'override_port' => $siteType['admin_access_type_value'] ) );
            }
//             $templates[$counter]['url'] = $url;
//             $templates[$counter]['admin_url'] = $adminURL;
            $siteType['url'] = $url;
            $siteType['admin_url'] = $adminURL;
            ++$counter;
        }

        $this->Tpl->setVariable( 'site_templates', $siteTypes );

        $this->Tpl->setVariable( 'setup_previous_step', 'Final' );
        $this->Tpl->setVariable( 'setup_next_step', 'Final' );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/final.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Finished' ),
                                        'url' => false ) );
        return $result;

    }


}

?>
