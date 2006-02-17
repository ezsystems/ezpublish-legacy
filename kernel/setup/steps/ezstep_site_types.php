<?php
//
// Definition of eZStepSiteTypes class
//
// Created on: <16-Apr-2004 09:56:02 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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
            $sitePackage = $this->Http->postVariable( 'eZSetup_site_type' );

            // TODO: Download site package and it's related packages
            //       in case of remote package has been choosen.


            if ( !$this->selectSiteType( $sitePackage ) )
            {
                $this->ErrorMsg = ezi18n( 'design/standard/setup/init',
                                          'No site package choosen.' );
                return false;
            }
        }
        else
        {
            $this->ErrorMsg = ezi18n( 'design/standard/setup/init',
                                      'No site package choosen.' );
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

            $chosenSitePackage = $data['Sites'][0];

            // TODO: Download site package and it's related packages
            //       in case of remote package has been choosen.


            if ( $this->selectSiteType( $chosenSitePackage ) )
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
        $remotePackagesList = $this->remotePackagesList();
        $sitePackages = $this->availableSitePackages();

        // TODO: Exclude already downloaded packages from remote site packages list

        $chosenSitePackage = $this->chosenSitePackage();

        $this->Tpl->setVariable( 'remote_packages_list', $remotePackagesList);
        $this->Tpl->setVariable( 'site_packages', $sitePackages );
        $this->Tpl->setVariable( 'chosen_package', $chosenSitePackage );
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

    function availableSitePackages()
    {
        include_once( 'kernel/classes/ezpackage.php' );
        $packageList = eZPackage::fetchPackages( array(), array( 'type' => 'site' ) );
    
        return $packageList;
    }

    /* Fetches remote site packages list (with URLs) in XML format from some URL (given in ini)
       Possilbe format:
       
       <packages vendor='eZ systems'
                 vendor-dir='ezsystems'>
         <package name='news_site'
                  version='1.0'
                  summary='News site'
                  description='blah-blah-blah...'
                  url='http://ez.no/packages380/sites/news_site.ezpkg' />
         <package name='forum_site'
                  version='1.0'
                  summary='News site'
                  description='blah-blah-blah...'
                  url='http://ez.no/packages380/sites/forum_site.ezpkg' />
       </packages>

       Returns this list in array
       Possible example:    0 => array( 'vendor' => 'eZ systems',
                                        'vendor-dir' => 'ezsystems',
                                        'packages' => array( 0 => array( "name" =>... , "version" =>... , "summary" => ... "url" =>... ),
                                                             1 => array( "name" =>... , "version" =>... , "summary" => ... "url" =>... ), ) ),
                            1 => ...
    */
    function remotePackagesList()
    {
        // TODO
    }

    var $Error = 0;
    var $ErrorMsg = false;
}

?>
