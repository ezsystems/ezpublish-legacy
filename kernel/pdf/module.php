<?php
//
// Created on: <18-Dec-2003 13:20:08 kk>
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

$Module = array( 'name' => 'eZContentObject',
                 'variable_params' => true );

$ViewList['edit'] = array(
    'script' => 'edit.php',
    'functions' => array( 'edit' ),
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ExportPDFBrowse' => 'BrowseSource',
                                    'ExportPDFButton' => 'Export',
                                    'DiscardButton'   => 'Discard',
                                    'CreateExport' => 'CreateExport' ),
    'post_action_parameters' => array( 'Export' => array( 'Title' => 'Title',
                                                          'DisplayFrontpage' => 'DisplayFrontpage',
                                                          'IntroText' => 'IntroText',
                                                          'SubText' => 'SubText',
                                                          'SourceNode' => 'SourceNode',
                                                          'ExportType' => 'ExportType',
                                                          'ClassList' => 'ClassList',
                                                          'SiteAccess' => 'SiteAccess',
                                                          'DestinationType' => 'DestinationType',
                                                          'DestinationFile' => 'DestinationFile' ),
                                       'BrowseSource' => array( 'Title' => 'Title',
                                                                'DisplayFrontpage' => 'DisplayFrontpage',
                                                                'IntroText' => 'IntroText',
                                                                'SubText' => 'SubText',
                                                                'ExportType' => 'ExportType',
                                                                'ClassList' => 'ClassList',
                                                                'SiteAccess' => 'SiteAccess',
                                                                'DestinationType' => 'DestinationType',
                                                                'DestinationFile' => 'DestinationFile' ) ),
    'unordered_params' => array( 'language' => 'Language' ),
    'params' => array( 'PDFExportID', 'PDFGenerate' ) );

$ViewList['list'] = array(
    'script' => 'list.php',
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'NewPDFExport' => 'NewExport',
                                    'RemoveExportButton' => 'RemoveExport' ),
    'post_action_parameters' => array( 'RemoveExport' => array( 'DeleteIDArray' => 'DeleteIDArray' ) ),
    'unordered_params' => array( 'language' => 'Language' ) );

$FunctionList['create'] = array( );

$FunctionList['edit'] = array( );

?>
