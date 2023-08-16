<?php
/**
 * File containing the eZStepSiteTemplates class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZStepSiteTemplates ezstep_site_templates.php
  \brief The class eZStepSiteTemplates does

*/

class eZStepSiteTemplates extends eZStepInstaller
{
    /**
     * @var string
     */
    public $ErrorMsg;
    /**
     * Constructor
     *
     * @param eZTemplate $tpl
     * @param eZHTTPTool $http
     * @param eZINI $ini
     * @param array $persistenceList
     */
    public function __construct( $tpl, $http, $ini, &$persistenceList )
    {
        parent::__construct( $tpl, $http, $ini, $persistenceList, 'site_templates', 'Site templates' );
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
                $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                          'No templates chosen.' );
                return false;
            }
            $this->PersistenceList['site_templates']['count'] = $siteTemplatesCount;
        }
        else
        {
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
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
        foreach( $packages as $key => $package )
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
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Site template selection' ),
                                        'url' => false ) );
        return $result;

    }

    public $Error = 0;
}

?>
