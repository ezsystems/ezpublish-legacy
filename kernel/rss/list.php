<?php
//
// eZSetup - init part initialization
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];


$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'NewExportButton' ) )
{
    return $Module->run( 'edit_export', array() );
}
else if ( $http->hasPostVariable( 'RemoveExportButton' ) && $http->hasPostVariable( 'DeleteIDArray' ) )
{
    $deleteArray = $http->postVariable( 'DeleteIDArray' );
    foreach ( $deleteArray as $deleteID )
    {
        $rssExport = eZRSSExport::fetch( $deleteID, true, eZRSSExport::STATUS_DRAFT );
        if ( $rssExport )
        {
            $rssExport->remove();
        }
        $rssExport = eZRSSExport::fetch( $deleteID, true, eZRSSExport::STATUS_VALID );
        if ( $rssExport )
        {
            $rssExport->remove();
        }
    }
}
else if ( $http->hasPostVariable( 'NewImportButton' ) )
{
    return $Module->run( 'edit_import', array() );
}
else if ( $http->hasPostVariable( 'RemoveImportButton' ) && $http->hasPostVariable( 'DeleteIDArrayImport' ) )
{
    $deleteArray = $http->postVariable( 'DeleteIDArrayImport' );
    foreach ( $deleteArray as $deleteID )
    {
        $rssImport = eZRSSImport::fetch( $deleteID, true, eZRSSImport::STATUS_DRAFT );
        if ( $rssImport )
        {
            $rssImport->remove();
        }
        $rssImport = eZRSSImport::fetch( $deleteID, true, eZRSSImport::STATUS_VALID );
        if ( $rssImport )
        {
            $rssImport->remove();
        }
    }
}


// Get all RSS Exports
$exportArray = eZRSSExport::fetchList();
$exportList = array();
foreach( $exportArray as $export )
{
    $exportList[$export->attribute( 'id' )] = $export;
}

// Get all RSS imports
$importArray = eZRSSImport::fetchList();
$importList = array();
foreach( $importArray as $import )
{
    $importList[$import->attribute( 'id' )] = $import;
}

$tpl = eZTemplate::factory();

$tpl->setVariable( 'rssexport_list', $exportList );
$tpl->setVariable( 'rssimport_list', $importList );

$Result = array();
$Result['content'] = $tpl->fetch( "design:rss/list.tpl" );
$Result['path'] = array( array( 'url' => 'rss/list',
                                'text' => ezpI18n::tr( 'kernel/rss', 'Really Simple Syndication' ) ) );


?>
