<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$Module = array( "name" => "eZContentObject",
                 "variable_params" => true );

$ViewList = array();
$ViewList["edit"] = array(
    "functions" => array( 'edit' ),
    'single_post_actions' => array( 'PreviewButton' => 'Preview',
                                    'TranslateButton' => 'Translate',
                                    'VersionsButton' => 'VersionEdit',
                                    'PublishButton' => 'Publish',
                                    'BrowseNodeButton' => 'BrowseForNodes',
                                    'DeleteNodeButton' => 'DeleteNode',
                                    'BrowseObjectButton' => 'BrowseForObjects',
                                    'StoreButton' => 'Store' ),
    'post_actions' => array( 'BrowseActionName' ),
    "script" => "edit.php",
    "params" => array( "ObjectID", "EditVersion" ) );

$ViewList["view"] = array(
    "functions" => array( 'read' ),
    "script" => "view.php",
    "params" => array( "ViewMode", "NodeID", "LanguageCode" ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" )
    );


$ViewList["versionview"] = array(
    "functions" => array( 'read' ),
    "script" => "versionview.php",
    "params" => array( "ObjectID", "EditVersion", "LanguageCode" ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );

$ViewList["search"] = array(
    "functions" => array( 'read' ),
    "script" => "search.php",
    "params" => array( ) );

$ViewList["advancedsearch"] = array(
    "functions" => array( 'read' ),
    "script" => "advancedsearch.php",
    "params" => array( ) );

$ViewList["browse"] = array(
    "functions" => array( 'read' ),
    "script" => "browse.php",
    "params" => array( "NodeID", "ObjectID", "EditVersion" ) );

$ViewList["download"] = array(
    "functions" => array( 'read' ),
    "script" => "download.php",
    "params" => array( "ContentObjectAttributeID", "version" ) );

$ViewList["action"] = array(
    "functions" => array( 'read' ),
    "script" => "action.php",
    "params" => array(  ) );

$ViewList["versions"] = array(
    "functions" => array( 'read', 'edit' ),
    "script" => "versions.php",
    "params" => array( "ObjectID" ) );
$ViewList["sitemap"] = array(
    "functions" => array( 'read' ),
    "script" => "sitemap.php",
    "params" => array( "TopObjectID" ) ,
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" )
    );
$ViewList["error"] = array(
    "functions" => array( 'read' ),
    "script" => "error.php",
    "params" => array( "ObjectID" ) ,
    );
$ViewList["permission"] = array(
    "functions" => array( 'edit' ),
    "script" => "permission.php",
    "params" => array( "ObjectID" ) );
$ViewList["translate"] = array(
    "functions" => array( 'edit' ),
    "script" => "translate.php",
    "params" => array( "ObjectID", "EditVersion" ) );







$ClassID = array(
    'name'=> 'ClassID',
    'values'=> array(),
    "path" => "classes/",
    "file" => "ezcontentclass.php",
    "class" => 'eZContentClass',
    "function" => "fetchList",
    "parameter" => array( 0, false )
    );

$ParentClassID = array(
    'name'=> 'ParentClassID',
    'values'=> array(),
    "path" => "classes/",
    "file" => "ezcontentclass.php",
    "class" => 'eZContentClass',
    "function" => "fetchList",
    "parameter" => array( 0, false )
    );

$SectionID = array(
    'name'=> 'SectionID',
    'values'=> array(),
    "path" => "classes/",
    "file" => "ezsection.php",
    "class" => 'eZSection',
    "function" => "fetchList",
    "parameter" => array(  false )
    );
$Assigned = array(
    'name'=> 'Assigned',
    'values'=> array(
        array(
            'Name' => 'Self',
            'value' => '1')
        )
    );
/*
          array(
            'Name' => 'Frontpage',
            'value' => '1'),
        array(
            'Name' => 'Sports',
            'value' => '2'),
        array(
            'Name' => 'Music',
            'value' => '3')
 */


$FunctionList['read'] = array( 'ClassID' => $ClassID,
                                 'SectionID' => $SectionID,
                                 'Assigned' => $Assigned );
$FunctionList['create'] = array( 'ClassID' => $ClassID,
                                 'SectionID' => $SectionID,
                                 'ParentClassID' => $ParentClassID
                                );
$FunctionList['edit'] = array( 'ClassID' => $ClassID,
                                 'SectionID' => $SectionID,
                                 'Assigned' => $Assigned );
$FunctionList['remove'] = array( 'ClassID' => $ClassID,
                                 'SectionID' => $SectionID,
                                 'Assigned' => $Assigned );


/*
$ViewArray["view"] = array(
    "functions" => array( "read", ""
    "script" => "view.php",
    "params" => array( "ViewMode", "NodeID", "LanguageCode" ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" )
    );

*/
// Module definition





?>
