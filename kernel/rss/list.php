<?php
//
// eZSetup - init part initialization
//
// Created on: <17-Sep-2003 11:00:54 kk>
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
                                'text' => ezpI18n::translate( 'kernel/rss', 'Really Simple Syndication' ) ) );


?>
