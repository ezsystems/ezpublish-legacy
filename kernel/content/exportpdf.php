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
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezpdfexport.php' );

$Module =& $Params['Module'];

if ( isset( $Params['PDFExportID'] ) )
{
    $pdfExport =& eZPDFExport::fetch( $Params['PDFExportID'] );
}
else
{
    include_once( 'kernel/classes/ezpdfexport.php' );
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();

    $pdfExport =& eZPDFExport::create( $user->attribute( 'contentobject_id' ) );
    $pdfExport->store();
}

$http =& eZHTTPTool::instance();
if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) )
{
    $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
    $pdfExport->setAttribute( 'source_node_id', $selectedNodeIDArray[0] );
    $pdfExport->store( );
}

if ( isset( $pdfExport ) &&
     $Module->hasActionParameter( 'SourceNode' ) &&
     $Module->hasActionParameter( 'DestinationType' ) &&
     $Module->hasActionParameter( 'ClassList' ) &&
     $Module->hasActionParameter( 'SiteAccess' ) &&
     $Module->hasActionParameter( 'Title' ) )
{
    $pdfExport->setAttribute( 'title', $Module->actionParameter( 'Title' ) );
    $pdfExport->setAttribute( 'show_frontpage', $Module->actionParameter( 'DisplayFrontpage' ) );
    $pdfExport->setAttribute( 'intro_text', $Module->actionParameter( 'IntroText' ) );
    $pdfExport->setAttribute( 'sub_text', $Module->actionParameter( 'SubText' ) );
    $pdfExport->setAttribute( 'source_node_id', $Module->actionParameter( 'SourceNode' ) );
    $pdfExport->setAttribute( 'site_access', $Module->actionParameter( 'SiteAccess' ) );
    $pdfExport->setAttribute( 'export_structure', $Module->actionParameter( 'ExportType' ) );
    $pdfExport->setAttribute( 'export_classes', implode( ':', $Module->actionParameter( 'ClassList' ) ) );
    $pdfExport->setAttribute( 'pdf_filename', $Module->actionParameter( 'DestinationFile' ) );
    $pdfExport->store( );
}

$setWarning = false; // used to set missing options during export

if ( $Module->isCurrentAction( 'BrowseSource' ) )
{;
    include_once( 'kernel/classes/ezcontentbrowse.php' );
    eZContentBrowse::browse( array( 'action_name' => 'ExportSourceBrowse',
                                    'description_template' => 'design:content/browse_export.tpl',
                                    'from_page' => '/content/exportpdf/'. $pdfExport->attribute( 'id' ) ),
                             $Module );
}
else if ( $Module->isCurrentAction( 'Export' ) )
{
    $node = $pdfExport->attribute( 'source_node' );
    if ( $node )
    {
        $object =& $node->attribute( 'object' );

        $tpl =& templateInit();

        $tpl->setVariable( 'node', $node );
        $tpl->setVariable( 'generate_toc', 1 );

        if ( $Module->actionParameter( 'DestinationType' ) == 'download' )
        {
            $tpl->setVariable( 'generate_stream', 1 );
        }
        else
        {
            $tpl->setVariable( 'generate_file', 1 );
        }

        $res =& eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                              array( 'node', $node->attribute( 'node_id' ) ),
                              array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                              array( 'class', $object->attribute( 'contentclass_id' ) ),
                              array( 'depth', $node->attribute( 'depth' ) ),
                              array( 'url_alias', $node->attribute( 'url_alias' ) )
                              ) );

        $textElements = array();
        $uri = 'design:node/view/pdf.tpl';
        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, '', '' );
        $pdf_definition = implode( '', $textElements );

        $pdf_definition = str_replace( array( ' ',
                                              "\r\n",
                                              "\t",
                                              "\n" ),
                                       '',
                                       $pdf_definition );

        $tpl->setVariable( 'pdf_definition', $pdf_definition );


        $tpl->setVariable( 'filename', $pdfExport->attribute( 'filepath' ) );
        $tpl->fetch( 'design:node/view/execute_pdf.tpl' );

        if ( $Module->actionParameter( 'DestinationType' ) != 'download' )
        {
            $pdfExport->store( 1 );
            return $Module->redirect( 'content', 'listpdf' );
        }
    }
    else
    {
        $setWarning = true;
    }
}

$tpl =& templateInit();

$tpl->setVariable( 'set_warning', $setWarning );

// Populate site access list
$config =& eZINI::instance( 'site.ini' );
$siteAccess =& $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

// Get Classes and class attributes
$classArray =& eZContentClass::fetchList();

$tpl->setVariable( 'pdf_export', $pdfExport );
$tpl->setVariable( 'export_site_access', $siteAccess );
$tpl->setVariable( 'export_class_array', $classArray );
$tpl->setVariable( 'pdfexport_list', eZPDFExport::fetchList() );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/export_pdf.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'content/pdf', 'PDF Export' ) ) );

?>
