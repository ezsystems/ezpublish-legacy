<?php
//
// eZSetup - init part initialization
//
// Created on: <17-Sep-2003 11:00:54 kk>
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

$Module =& $Params["Module"];

include_once( "kernel/common/template.php" );
include_once( 'kernel/classes/ezrssexport.php' );
include_once( 'kernel/classes/ezrssimport.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( 'NewExportButton' ) )
{
    return $Module->run( 'edit_export', array() );
}
else if ( $http->hasPostVariable( 'RemoveExportButton' ) )
{
    $deleteArray =& $http->postVariable( 'DeleteIDArray' );
    foreach ( $deleteArray as $deleteID )
    {
        $rssExport =& eZRSSExport::fetch( $deleteID );
        $rssExport->remove();
    }
}
else if ( $http->hasPostVariable( 'NewImportButton' ) )
{
    return $Module->run( 'edit_import', array() );
}
else if ( $http->hasPostVariable( 'RemoveImportButton' ) )
{
    $deleteArray =& $http->postVariable( 'DeleteIDArrayImport' );
    foreach ( $deleteArray as $deleteID )
    {
        $rssImport =& eZRSSImport::fetch( $deleteID );
        $rssImport->remove();
    }
}


// Get all RSS Exports
$exportArray =& eZRSSExport::fetchList();
$exportList = array();
foreach( array_keys( $exportArray ) as $exportID )
{
    $export =& $exportArray[$exportID];
    $exportList[$export->attribute( 'id' )] =& $export;
}

// Get all RSS imports
$importArray =& eZRSSImport::fetchList();
$importList = array();
foreach( array_keys( $importArray ) as $importID )
{
    $import =& $importArray[$importID];
    $importList[$import->attribute( 'id' )] =& $import;
}

$tpl =& templateInit();

$tpl->setVariable( 'rssexport_list', $exportList );
$tpl->setVariable( 'rssimport_list', $importList );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:rss/list.tpl" );
$Result['path'] = array( array( 'url' => 'kernel/rss',
                                'text' => ezi18n( 'kernel/rss', 'Really Simple Syndication' ) ) );


?>
