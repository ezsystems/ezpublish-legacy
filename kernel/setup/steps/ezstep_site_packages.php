<?php
//
// Definition of eZStepSitePackages class
//
// Created on: <16-Apr-2004 10:53:35 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepSitePackages ezstep_site_packages.php
  \brief The class eZStepSitePackages does

*/

class eZStepSitePackages extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSitePackages( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetup_site_packages' ) )
        {
            $sitePackages = $this->Http->postVariable( 'eZSetup_site_packages' );
            $this->PersistenceList['site_packages'] = $sitePackages;

            // need to merge in required manually because disabled checkboxes won't post value.
            $packageINI = eZINI::instance( 'setup.ini' );
            $this->PersistenceList['additional_packages'] = array_merge( $this->Http->postVariable( 'AdditionalPackages' ), $packageINI->variable( 'PackageList', 'Required' ) );
        }
        else
        {
            $this->ErrorMsg = ezi18n( 'design/standard/setup/init',
                                      'No packages choosen.' );
            return false;
        }
        return true;
    }

    /*!
     \reimp
     */
    function init()
    {
        $this->ErrorMsg = false;
        return false; // Always show site package selection
    }

    /*!
     \reimp
    */
    function &display()
    {
        $packageINI = eZINI::instance( 'setup.ini' );
        $requiredPackages = $packageINI->variable( 'PackageList', 'Required' );

        include_once( 'kernel/classes/ezpackage.php' );
        $packageArray = eZPackage::fetchPackages( array( 'path' => 'packages' ) );

        $packageInfoArray = array();
        foreach ( $packageArray as $package )
        {
            $packageInfoArray[] = array( 'required' => in_array( $package->attribute( 'name' ), $requiredPackages ) ? 1 : 0,
                                         'name' => $package->attribute( 'name' ),
                                         'description' => $package->attribute( 'description' ) );
        }


        $siteTypes = $this->chosenSiteTypes();

        $this->Tpl->setVariable( 'site_types', $siteTypes );
        $this->Tpl->setVariable( 'error', $this->ErrorMsg );
        $this->Tpl->setVariable( 'package_array', $packageInfoArray );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_packages.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Site element selection' ),
                                        'url' => false ) );
        return $result;

    }

    var $Error = 0;
}

?>
