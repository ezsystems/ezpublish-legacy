<?php
//
// eZSetup - init part initialization
//
// Created on: <29-Oct-2003 14:49:54 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

require_once( 'kernel/common/template.php' );
$Module = $Params['Module'];
$http = eZHTTPTool::instance();

if ( isset( $Params['PDFGenerate'] ) && $Params['PDFGenerate'] == 'generate' )
{
    $pdfExport = eZPDFExport::fetch( $Params['PDFExportID'] );
    if ( $pdfExport && $pdfExport->attribute( 'status' ) == eZPDFExport::CREATE_ONFLY ) // only generate OnTheFly if status set correctly
    {
        generatePDF( $pdfExport );
        eZExecution::cleanExit();
    }
    return;
}

if ( isset( $Params['PDFExportID'] ) )
{
    $pdfExport = eZPDFExport::fetch( $Params['PDFExportID'], true, eZPDFExport::VERSION_DRAFT );

    if ( $pdfExport )
    {
        $user = eZUser::currentUser();
        $contentIni = eZINI::instance( 'content.ini' );
        $timeOut = $contentIni->variable( 'PDFExportSettings', 'DraftTimeout' );
        if ( $pdfExport->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
             $pdfExport->attribute( 'modified' ) + $timeOut > time() )
        {
            // TODO: In 3.6
            // // locked editing
            // $tpl = templateInit();
            // $tpl->setVariable ...
            // $Result = array();
            // $Result['content'] = $tpl->fetch( 'design:pdf/edit_denied.tpl' );
            // $Result['path'] = ...
            // return $Result;
        }
        else if ( $timeOut > 0 && $pdfExport->attribute( 'modified' ) + $timeOut < time() )
        {
            $pdfExport->remove();
            $pdfExport = false;
        }
    }
    if ( !$pdfExport )
    {
        $pdfExport = eZPDFExport::fetch( $Params['PDFExportID'] );
        if( !$pdfExport ) // user requested a non existent export
        {
            return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }
        $pdfExport->setAttribute( 'version', eZPDFExport::VERSION_DRAFT );
        $pdfExport->store();
    }
}
else
{
    $user = eZUser::currentUser();

    $pdfExport = eZPDFExport::create( $user->attribute( 'contentobject_id' ) );
    $pdfExport->store();
}

if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) && !$http->hasPostVariable( 'BrowseCancelButton' ) ) // Get Source node ID from browse
{
    $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
    $pdfExport->setAttribute( 'source_node_id', $selectedNodeIDArray[0] );
    $pdfExport->store();
}

$validation = array();
$inputValidated = true;

if ( $Module->isCurrentAction( 'BrowseSource' ) || // Store PDF export objects
     $Module->isCurrentAction( 'Export' ) )
{
    $pdfExport->setAttribute( 'title', $Module->actionParameter( 'Title' ) );
    $pdfExport->setAttribute( 'show_frontpage', $Module->hasActionParameter( 'DisplayFrontpage' ) ? 1 : 0 );
    $pdfExport->setAttribute( 'intro_text', $Module->actionParameter( 'IntroText' ) );
    $pdfExport->setAttribute( 'sub_text', $Module->actionParameter( 'SubText' ) );
    $pdfExport->setAttribute( 'export_structure', $Module->actionParameter( 'ExportType' ) );
    if ( $Module->actionParameter( 'ExportType' ) == 'tree' && $Module->hasActionParameter( 'ClassList' ) )
        $pdfExport->setAttribute( 'export_classes', implode( ':', $Module->actionParameter( 'ClassList' ) ) );
    $pdfExport->setAttribute( 'pdf_filename', basename( $Module->actionParameter( 'DestinationFile' ) ) );
    $pdfExport->setAttribute( 'status', ( basename( $Module->actionParameter( 'DestinationType' ) ) != 'download' ) ?
                              eZPDFExport::CREATE_ONCE : eZPDFExport::CREATE_ONFLY );

    if ( $Module->isCurrentAction( 'Export' ) )
    {
        $pdfExport->setAttribute( 'source_node_id', $Module->actionParameter( 'SourceNode' ) );

        if ( $pdfExport->attribute( 'status' ) == eZPDFExport::CREATE_ONCE
             && $pdfExport->countGeneratingOnceExports() > 0 )
        {
            $validation[ 'placement' ][] = array( 'text' => ezi18n( 'kernel/pdf', 'An export with such filename already exists.' ) );
            $validation[ 'processed' ] = true;
            $inputValidated = false;
        }
    }

    if ( $inputValidated )
    {
        $pdfExport->store();
    }
}

$setWarning = false; // used to set missing options during export

if ( $Module->isCurrentAction( 'BrowseSource' ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'ExportSourceBrowse',
                                    'description_template' => 'design:content/browse_export.tpl',
                                    'from_page' => '/pdf/edit/'. $pdfExport->attribute( 'id' ) ),
                             $Module );
}
else if ( $Module->isCurrentAction( 'Export' ) && $inputValidated )
{
    // remove the old file ( user may changed the filename )
    $originalPdfExport = eZPDFExport::fetch( $Params['PDFExportID'] );
    if ( $originalPdfExport && $originalPdfExport->attribute( 'status' ) == eZPDFExport::CREATE_ONCE )
    {
        $filename = $originalPdfExport->attribute( 'filepath' );
        if ( file_exists( $filename ) )
        {
            unlink( $filename );
        }
    }

    if ( $pdfExport->attribute( 'status' ) == eZPDFExport::CREATE_ONCE )
    {
        generatePDF( $pdfExport, $pdfExport->attribute( 'filepath' ) );
        $pdfExport->store( true );
        return $Module->redirect( 'pdf', 'list' );
    }
    else
    {
        $pdfExport->store( true );
        return $Module->redirect( 'pdf', 'list' );
    }
}
else if ( $Module->isCurrentAction( 'Discard' ) )
{
    $pdfExport->remove();
    return $Module->redirect( 'pdf', 'list' );
}

$tpl = templateInit();

$tpl->setVariable( 'set_warning', $setWarning );

// Get Classes and class attributes
$classArray = eZContentClass::fetchList();

$tpl->setVariable( 'pdf_export', $pdfExport );
$tpl->setVariable( 'export_type' , $pdfExport->attribute( 'status' ) );
$tpl->setVariable( 'export_class_array', $classArray );
$tpl->setVariable( 'pdfexport_list', eZPDFExport::fetchList() );

if ( !$inputValidated )
{
    $tpl->setVariable( 'validation', $validation );
}

$Result = array();
$Result['content'] = $tpl->fetch( 'design:pdf/edit.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'pdf/edit', 'PDF Export' ) ) );

/*!
 \generate and output PDF data, either to file or stream

 \param PDF export object
 \param toFile, false if generate to stream, $
                filename if generate to file
*/
function generatePDF( $pdfExport, $toFile = false )
{
    if ( $pdfExport == null )
        return;

    $node = $pdfExport->attribute( 'source_node' );
    if ( $node )
    {
        $object = $node->attribute( 'object' );

        $tpl = templateInit();

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

        $res = eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                              array( 'node', $node->attribute( 'node_id' ) ),
                              array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                              array( 'class', $object->attribute( 'contentclass_id' ) ),
                              array( 'class_identifier', $object->attribute( 'class_identifier' ) ),
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
