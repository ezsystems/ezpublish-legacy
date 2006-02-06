<?php
//
// Definition of eZStepSiteTypes class
//
// Created on: <16-Apr-2004 09:56:02 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.6.x
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

include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepSiteTypes ezstep_site_types.php
  \brief The class eZStepSiteTypes does

*/

class eZStepSiteTypes extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteTypes( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_types', 'Site types' );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetup_site_type' ) )
        {
            $siteTypes = array( $this->Http->postVariable( 'eZSetup_site_type' ) );
            if ( !$this->selectSiteTypes( $siteTypes ) )
            {
                $this->ErrorMsg = ezi18n( 'design/standard/setup/init',
                                          'No site choosen.' );
                return false;
            }
        }
        else
        {
            $this->ErrorMsg = ezi18n( 'design/standard/setup/init',
                                      'No site choosen.' );
            return false;
        }
        return true;
    }

    /*!
     \reimp
     */
    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $chosenSiteTypes = $data['Sites'];
            if ( $this->selectSiteTypes( $chosenSiteTypes ) )
            {
                return $this->kickstartContinueNextStep();
            }
        }

        $this->ErrorMsg = false;
        return false; // Always show site template selection
    }

    /*!
     \reimp
    */
    function &display()
    {
        $siteTypes = $this->availableSiteTypes();
        $chosenSiteTypes = $this->chosenSiteTypes();

        $this->Tpl->setVariable( 'site_types', $siteTypes );
        $this->Tpl->setVariable( 'chosen_types', $chosenSiteTypes );
        $this->Tpl->setVariable( 'error', $this->ErrorMsg );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_types.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Site selection' ),
                                        'url' => false ) );
        return $result;

    }

    var $Error = 0;
}

?>
