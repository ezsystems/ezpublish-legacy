<?php
//
// Created on: <18-Dec-2003 13:20:08 kk>
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


$FunctionList = array();
$FunctionList['create'] = array();
$FunctionList['edit'] = array();

?>
