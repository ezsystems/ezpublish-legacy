<?php
//
// Definition of eZStepSiteTemplates class
//
// Created on: <12-Aug-2003 15:14:42 kk>
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
  \class eZStepSiteTemplates ezstep_site_templates.php
  \brief The class eZStepSiteTemplates does

*/

class eZStepSiteTemplates extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteTemplates( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_templates', 'Site templates' );
    }

    function processPostData()
    {
        // set template and template thumbnail
        $config = eZINI::instance( 'setup.ini' );
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
                $this->ErrorMsg = ezpI18n::translate( 'design/standard/setup/init',
                                          'No templates chosen.' );
                return false;
            }
            $this->PersistenceList['site_templates']['count'] = $siteTemplatesCount;
        }
        else
        {
            $this->ErrorMsg = ezpI18n::translate( 'design/standard/setup/init',
                                      'No templates chosen.' );
            return false;
        }
        return true;
    }

    function init()
    {
        return false; // Always show site template selection
    }

    function display()
    {
        // Get site templates from setup.ini
        $config = eZINI::instance( 'setup.ini' );
        $thumbnailBase = $config->variable( 'SiteTemplates', 'ThumbnailBase' );
        $thumbnailExtension = $config->variable( 'SiteTemplates', 'ThumbnailExtension' );

        $site_templates = array();

        $packages = eZPackage::fetchPackages( array( 'path' => 'kernel/setup/packages' ) );
        foreach( $packages as $key => $packages )
        {
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
        $result['path'] = array( array( 'text' => ezpI18n::translate( 'design/standard/setup/init',
                                                          'Site template selection' ),
                                        'url' => false ) );
        return $result;

    }

    public $Error = 0;
}

?>
