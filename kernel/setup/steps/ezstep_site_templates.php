<?php
//
// Definition of eZStepSiteTemplates class
//
// Created on: <12-Aug-2003 15:14:42 kk>
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

/*! \file ezstep_site_templates.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepSiteTemplates ezstep_site_templates.php
  \brief The class eZStepSiteTemplates does

*/

class eZStepSiteTemplates extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteTemplates( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_templates', 'Site templates' );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        // set template and template thumbnail
        $config =& eZINI::instance( 'setup.ini' );
        $thumbnailBase = $config->variable( 'SiteTemplates', 'ThumbnailBase' );
        $thumbnailExtension = $config->variable( 'SiteTemplates', 'ThumbnailExtension' );

        if ( $this->Http->hasPostVariable( 'eZSetup_site_templates' ) )
        {
            $siteTemplates = $this->Http->postVariable( 'eZSetup_site_templates' );
            $this->PersistenceList['site_templates']['count'] = count( $siteTemplates );

            $siteTemplatesCount = 0;
            foreach ( $siteTemplates as $key => $template )
            {
                if ( !isset( $template['checked'] ) or
                     $template['checked'] != $template['identifier'] )
                    continue;
                $this->PersistenceList['site_templates_' . $siteTemplatesCount]['identifier'] = $template['identifier'];
                $this->PersistenceList['site_templates_' . $siteTemplatesCount]['name'] = $template['name'];
                $this->PersistenceList['site_templates_' . $siteTemplatesCount]['image_file_name'] = $template['image'];
                ++$siteTemplatesCount;
            }
            if ( $siteTemplatesCount == 0)
            {
                $this->ErrorMsg = ezi18n( 'design/standard/setup/init',
                                          'No templates choosen.' );
                return false;
            }
            $this->PersistenceList['site_templates']['count'] = $siteTemplatesCount;
        }
        else
        {
            $this->ErrorMsg = ezi18n( 'design/standard/setup/init',
                                      'No templates choosen.' );
            return false;
        }
        return true;
    }

    /*!
     \reimp
     */
    function init()
    {
        return false; // Always show site template selection
    }

    /*!
     \reimp
    */
    function &display()
    {
        // Get site templates from setup.ini
        $config =& eZINI::instance( 'setup.ini' );
        $thumbnailBase = $config->variable( 'SiteTemplates', 'ThumbnailBase' );
        $thumbnailExtension = $config->variable( 'SiteTemplates', 'ThumbnailExtension' );

        $site_templates = array();

        include_once( 'kernel/classes/ezpackage.php' );

        $packages =& eZPackage::fetchPackages( array( 'path' => 'kernel/setup/packages' ) );
        for ( $key = 0; $key < count( $packages ); ++$key )
        {
            $package =& $packages[$key];
            $site_templates[$key]['name'] = $package->attribute( 'summary' );
            $site_templates[$key]['identifier'] = $package->attribute( 'name' );
            $thumbnails = $package->thumbnailList( 'default' );
            if ( count( $thumbnails ) > 0 )
                $site_templates[$key]['image_file_name'] = $package->fileItemPath( $thumbnails[0], 'default', 'kernel/setup/packages' );
            else
                $site_templates[$key]['image_file_name'] = false;
        }

        $this->Tpl->setVariable( 'site_templates', $site_templates );
        $this->Tpl->setVariable( 'error', $this->Error );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_templates.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Site template selection' ),
                                        'url' => false ) );
        return $result;

    }

    var $Error = 0;
}

?>
