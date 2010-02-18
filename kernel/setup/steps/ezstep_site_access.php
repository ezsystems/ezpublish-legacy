<?php
//
// Definition of eZStepSiteAccess class
//
// Created on: <12-Aug-2003 17:35:47 kk>
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
  \class eZStepSiteAccess ezstep_site_access.php
  \brief The class eZStepSiteAccess does

*/

class eZStepSiteAccess extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteAccess( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_access', 'Site access' );
    }

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

        $siteType = $this->chosenSiteType();

        $siteType['access_type'] = $accessType;

        $this->setAccessValues( $siteType );

        $this->storeSiteType( $siteType );
        return true;
    }

    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $siteType = $this->chosenSiteType();

            $accessType = $data['Access'];
            if ( in_array( $accessType,
                           array( 'url', 'port', 'hostname' ) ) )
            $siteType['access_type'] = $accessType;

            $this->setAccessValues( $siteType );
            $this->storeSiteType( $siteType );
            return $this->kickstartContinueNextStep();
        }

        $siteType = $this->chosenSiteType();

        // If windows installer, install using url site access
        if ( eZSetupTestInstaller() == 'windows' )
        {
            $siteType['access_type'] = 'url';
            $siteType['access_type_value'] = $siteType['identifier'];
            $siteType['admin_access_type_value'] = $siteType['identifier'] . '_admin';

            $this->storeSiteType( $siteType );

            return true;
        }

        if ( !isset( $siteType['access_type'] ) )
            $siteType['access_type'] = 'url';

        $this->storeSiteType( $siteType );
        return false; // Always show site access
    }

    function display()
    {
        $siteType = $this->chosenSiteType();
        $this->Tpl->setVariable( 'site_type', $siteType );
//         $this->Tpl->setVariable( 'error', $this->Error );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_access.tpl' );
        $result['path'] = array( array( 'text' => ezpI18n::translate( 'design/standard/setup/init',
                                                          'Site access' ),
                                        'url' => false ) );
        return $result;
    }

    function setAccessValues( &$siteType )
    {
        $accessType = $siteType['access_type'];
        if ( $accessType == 'url' )
        {
            $siteType['access_type_value'] = $siteType['identifier'];
            $siteType['admin_access_type_value'] = $siteType['identifier'] . '_admin';
        }
        else if ( $accessType == 'port' )
        {
            $siteType['access_type_value'] = 8080;        // default port values
            $siteType['admin_access_type_value'] = 8081;
        }
        else if ( $accessType == 'hostname' )
        {
            $siteType['access_type_value'] = $siteType['identifier'] . '.' . eZSys::hostName();
            $siteType['admin_access_type_value'] = $siteType['identifier'] . '-admin.' . eZSys::hostName();
        }
        else
        {
            $siteType['access_type_value'] = $accessType;
            $siteType['admin_access_type_value'] = $accessType . '_admin';
        }
    }
}

?>
