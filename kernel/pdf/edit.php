<?php
//
// eZSetup - init part initialization
//
// Created on: <29-Oct-2003 14:49:54 kk>
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

define( 'EZ_PDF_EXPORT_GENERATE_STRING', 'generate' );

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezpdfexport.php' );
include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );

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

if ( isset( $Params['PDFGenerate'] ) && $Params['PDFGenerate'] == EZ_PDF_EXPORT_GENERATE_STRING )
{
    generatePDF( $pdfExport );

    if ( $pdfExport->attribute( 'status' ) == 2 ) // only generate OnTheFly if status set correctly
    {
        include_once( 'lib/ezutils/classes/ezexecution.php' );
    }

    eZExecution::cleanExit();

}

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) ) // Get Source node ID from browse
{
    $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
    $pdfExport->setAttribute( 'source_node_id', $selectedNodeIDArray[0] );
    $pdfExport->store( );
}

if ( $Module->isCurrentAction( 'BrowseSource' ) || // Store PDF export objects
     $Module->isCurrentAction( 'Export' ) )
{
    $pdfExport->setAttribute( 'title', $Module->actionParameter( 'Title' ) );
    $pdfExport->setAttribute( 'show_frontpage', $Module->hasActionParameter( 'DisplayFrontpage' ) ? 1 : 0 );
    $pdfExport->setAttribute( 'intro_text', $Module->actionParameter( 'IntroText' ) );
    $pdfExport->setAttribute( 'sub_text', $Module->actionParameter( 'SubText' ) );
    $pdfExport->setAttribute( 'export_structure', $Module->actionParameter( 'ExportType' ) );
    $pdfExport->setAttribute( 'export_classes', implode( ':', $Module->actionParameter( 'ClassList' ) ) );
    $pdfExport->setAttribute( 'pdf_filename', basename( $Module->actionParameter( 'DestinationFile' ) ) );

    if ( $Module->isCurrentAction( 'Export' ) )
    {
        $pdfExport->setAttribute( 'source_node_id', $Module->actionParameter( 'SourceNode' ) );
    }

    $pdfExport->store( );
}

$setWarning = false; // used to set missing options during export

if ( $Module->isCurrentAction( 'BrowseSource' ) )
{
    include_once( 'kernel/classes/ezcontentbrowse.php' );
    eZContentBrowse::browse( array( 'action_name' => 'ExportSourceBrowse',
                                    'description_template' => 'design:content/browse_export.tpl',
                                    'from_page' => '/pdf/edit/'. $pdfExport->attribute( 'id' ) ),
                             $Module );
}
else if ( $Module->isCurrentAction( 'Export' ) )
{
    if ( $Module->actionParameter( 'DestinationType' ) != 'download' )
    {
        generatePDF( $pdfExport, $pdfExport->attribute( 'filepath' ) );
        $pdfExport->store( 1 );

        return $Module->redirect( 'pdf', 'list' );
    }
    else
    {
        $pdfExport->store( 2 );
        return $Module->redirect( 'pdf', 'list' );
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
$tpl->setVariable( 'export_type' , $pdfExport->attribute( 'status' ) );
$tpl->setVariable( 'export_site_access', $siteAccess );
$tpl->setVariable( 'export_class_array', $classArray );
$tpl->setVariable( 'pdfexport_list', eZPDFExport::fetchList() );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:pdf/edit.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'pdf/edit', 'PDF Export' ) ) );

/*!
 \generate and output PDF data, either to file or stream

 \param PDF export object
 \param toFile, false if generate to stream, $
                filename if generate to file
*/
function generatePDF( &$pdfExport, $toFile = false )
{
    if ( $pdfExport == null )
        return;

    $node = $pdfExport->attribute( 'source_node' );
    if ( $node )
    {
        $object =& $node->attribute( 'object' );

        $tpl =& templateInit();

        $tpl->setVariable( 'node', $node );
        $tpl->setVariable( 'generate_toc', 1 );

        $tpl->setVariable( 'tree_traverse',
                           $pdfExport->attribute( 'export_structure' ) == 'tree' ? 1 : 0 );
        $tpl->setVariable( 'class_array', explode( ':', $pdfExport->attribute( 'export_classes' ) ) );
        $tpl->setVariable( 'show_frontpage', $pdfExport->attribute( 'show_frontpage' ) );
        if ( $pdfExport->attribute( 'show_frontpage' ) == 1 )
        {
            $tpl->setVariable( 'intro_text', $pdfExport->attribute( 'intro_text' ) );
            $tpl->setVariable( 'sub_intro_text', $pdfExport->attribute( 'sub_text' ) );
        }

        if ( $toFile === false )
        {
            $tpl->setVariable( 'generate_stream', 1 );
        }
        else
        {
            $tpl->setVariable( 'generate_file', 1 );
            $tpl->setVariable( 'filename', $toFile );
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
        $tpl->setVariable( 'pdf_root_template', 1 );
        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, '', '' );
        $pdf_definition = implode( '', $textElements );

        $pdf_definition = str_replace( array( ' ',
                                              "\r\n",
                                              "\t",
                                              "\n" ),
                                       '',
                                       $pdf_definition );

        $tpl->setVariable( 'pdf_definition', $pdf_definition );

        $uri = 'design:node/view/execute_pdf.tpl';
        $textElements = '';
        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, '', '' );
    }
}

?>
