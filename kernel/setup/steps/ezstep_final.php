<?php
//
// Definition of eZStepFinal class
//
// Created on: <13-Aug-2003 14:09:47 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
