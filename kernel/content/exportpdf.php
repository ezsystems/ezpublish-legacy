<?php
//
// eZSetup - init part initialization
//
// Created on: <29-Oct-2003 14:49:54 kk>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// 'GNU General Public License' version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid 'eZ publish professional licences' may use this
// file in accordance with the 'eZ publish professional licence' Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The 'eZ publish professional licence' is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The 'GNU General Public License' (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/common/template.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$Module =& $Params['Module'];

$http =& eZHTTPTool::instance();

if ( $Module->isCurrentAction( 'BrowseSource' ) )
{
    include_once( 'kernel/classes/ezcontentbrowse.php' );
    eZContentBrowse::browse( array( 'action_name' => 'ExportSourceBrowse',
                                    'description_template' => 'design:content/browse_export.tpl',
                                    'from_page' => '/content/exportpdf' ),
                             $Module );
}
else if ( $Module->isCurrentAction( 'Export' ) &&
          $http->hasPostVariable( 'export_pdf_node_id' ) &&
          $http->hasPostVariable( 'export_pdf_type' ) &&
          $http->hasPostVariable( 'export_pdf_class_list' ) &&
          $http->hasPostVariable( 'export_pdf_site_access' ) &&
          $http->hasPostVariable( 'export_pdf_destination' ) )
{
    $tpl =& templateInit();

    $tpl->setVariable( 'node', eZContentObjectTreeNode::fetch( $http->postVariable( 'export_pdf_node_id' ) ) );
    $tpl->setVariable( 'class_array', $http->postVariable( 'export_pdf_class_list' ) );
    $tpl->setVariable( 'tree_traverse', ($http->postVariable( 'export_pdf_type' ) != 'node') ? 1 : -1 ); //set to 1 if recursive export
    $tpl->setVariable( 'generate_toc', 1 );

    if ( $http->postVariable( 'export_pdf_destination' ) == 'download' )
    {
        $tpl->setVariable( 'generate_stream', 1 );
    }
    else
    {
        $tpl->setVariable( 'generate_file', 1 );
    }
    $textElements = array();
    $uri = 'design:node/view/pdf.tpl';
    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, '', '' );
    $pdf_definition = implode( '', $textElements );

    $pdf_definition = str_replace( array( ' ',
                                          "\n" ),
                                   '',
                                   $pdf_definition );

    $tpl->setVariable( 'pdf_definition', $pdf_definition );

    $tpl->fetch( 'design:node/view/execute_pdf.tpl' );
}

$tpl =& templateInit();

if ( $tpl->hasVariable( 'node' ) )
    $tpl->setVariable( 'export_node_id', $http->postVariable( 'export_pdf_node_id' ) );

if ( $Module->isCurrentAction( 'NodeID' ) && $http->hasPostVariable( 'SelectedNodeIDArray' ) )
{
    $nodeIDArray =& $http->postVariable( 'SelectedNodeIDArray' );
    $tpl->setVariable( 'export_node_id', $nodeIDArray[0] );
}

// Populate site access list
$config =& eZINI::instance( 'site.ini' );
$siteAccess =& $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

// Get Classes and class attributes
$classArray =& eZContentClass::fetchList();

$tpl->setVariable( 'export_site_access', $siteAccess );
$tpl->setVariable( 'export_class_array', $classArray );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/export_pdf.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'content/pdf', 'PDF Export' ) ) );

?>
