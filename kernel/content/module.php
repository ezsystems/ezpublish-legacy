<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
                                    'DiscardButton' => 'Discard',
                                    'BrowseNodeButton' => 'BrowseForNodes',
//                                    'DeleteNodeButton' => 'DeleteNode',
//                                    'MoveNodeButton' => 'MoveNode',
                                    'EditLanguageButton' => 'EditLanguage',
                                    'BrowseObjectButton' => 'BrowseForObjects',
                                    'NewButton' => 'NewObject',
                                    'DeleteRelationButton' => 'DeleteRelation',
                                    'StoreButton' => 'Store',
                                    'CustomActionButton' => 'CustomAction',
                                    'MoveNodeID' => 'MoveNode',
                                    'RemoveNodeID' => 'DeleteNode',
                                    'ConfirmButton' => 'ConfirmAssignmentDelete'
                                    ),
    'post_action_parameters' => array( 'EditLanguage' => array( 'SelectedLanguage' => 'EditSelectedLanguage' ) ),
    'post_actions' => array( 'BrowseActionName' ),
    "script" => "edit.php",
    "params" => array( "ObjectID", "EditVersion", 'EditLanguage' ) );

$ViewList["removenode"] = array(
    "functions" => array( 'edit' ),
    'single_post_actions' => array( 'ConfirmButton' => 'ConfirmAssignmentRemove',
                                    'CancelButton' => 'CancelAssignmentRemove' ),
    "script" => "removenode.php",
    "params" => array( "ObjectID", "EditVersion", 'EditLanguage', "NodeID" ) );


$ViewList["view"] = array(
    "functions" => array( 'read' ),
    "script" => "view.php",
    "params" => array( "ViewMode", "NodeID", "LanguageCode" ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" )
    );

$ViewList["copy"] = array(
    "functions" => array( 'edit' ),
    "script" => "copy.php",
    'single_post_actions' => array( 'CopyButton' => 'Copy',
                                    'CancelButton' => 'Cancel' ),
    'post_action_parameters' => array( 'Copy' => array( 'VersionChoice' => 'VersionChoice' ) ),
    "params" => array( "ObjectID" ) );


$ViewList["versionview"] = array(
    "functions" => array( 'versionread' ),
    "script" => "versionview.php",
    'single_post_actions' => array( 'ChangeSettingsButton' => 'ChangeSettings',
                                    'EditButton' => 'Edit',
                                    'VersionsButton' => 'Versions',
                                    'PreviewPublishButton' => 'Publish' ),
    'post_action_parameters' => array( 'ChangeSettings' => array( 'Language' => 'SelectedLanguage',
                                                                  'PlacementID' => 'SelectedPlacement',
                                                                  'Sitedesign' => 'SelectedSitedesign' ) ),
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
    "params" => array( "NodeID", "ObjectID", "EditVersion" ),
    "unordered_params" => array( "offset" => "Offset" ) );

$ViewList["removeobject"] = array(
    "functions" => array( 'read' ),
    "script" => "removeobject.php",
    "params" => array( ) );

$ViewList["removeeditversion"] = array(
    "functions" => array( 'read' ),
    "script" => "removeeditversion.php",
    "params" => array( ) );

$ViewList["download"] = array(
    "functions" => array( 'read' ),
    "script" => "download.php",
    "params" => array( "ContentObjectAttributeID", "version" ) );

$ViewList["action"] = array(
    "functions" => array( 'read' ),
    "script" => "action.php",
    "params" => array(  ),
    'post_actions' => array( 'BrowseActionName' ) );

$ViewList["collectinformation"] = array(
    "functions" => array( 'read' ),
    "script" => "collectinformation.php",
    'single_post_actions' => array( 'ActionCollectInformation' => 'CollectInformation' ),
    'post_action_parameters' => array( 'CollectInformation' => array( 'ContentObjectID' => 'ContentObjectID' ) ),
    "params" => array(  ) );

$ViewList["versions"] = array(
    "functions" => array( 'read', 'edit' ),
    "script" => "versions.php",
    'single_post_actions' => array( 'CopyVersionButton' => 'CopyVersion',
                                    'EditButton' => 'Edit' ),
    'post_action_parameters' => array( 'CopyVersion' => array( 'VersionID' => 'RevertToVersionID',
                                                               'EditLanguage' => 'EditLanguage' ),
                                       'Edit' => array( 'VersionID' => 'RevertToVersionID',
                                                        'EditLanguage' => 'EditLanguage' ) ),
    "params" => array( "ObjectID" ,'EditVersion', 'EditLanguage' ) );

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
    'single_post_actions' => array( 'EditObjectButton' => 'EditObject',
                                    'PreviewButton' => 'Preview',
                                    'StoreButton' => 'Store',
                                    'AddLanguageButton' => 'AddLanguage',
                                    'RemoveLanguageButton' => 'RemoveLanguage',
                                    'RemoveLanguageConfirmationButton' => 'RemoveLanguageConfirmation',
                                    'RemoveLanguageCancelButton' => 'RemoveLanguageCancel',
                                    'EditLanguageButton' => 'EditLanguage' ),
    'post_action_parameters' => array( 'AddLanguage' => array( 'SelectedLanguage' => 'SelectedLanguage' ) ,
                                       'RemoveLanguage' => array( 'SelectedLanguageList' => 'RemoveLanguageArray' ),
                                       'RemoveLanguageConfirmation' => array( 'SelectedLanguageList' => 'RemoveLanguageArray' ),
                                       'EditLanguage' => array( 'SelectedLanguage' => 'EditSelectedLanguage' ) ),
    'action_parameters' => array( 'CancelTask' => array( 'SelectedLanguage' ) ),
    "params" => array( "ObjectID", "EditVersion" ) );

$ViewList["draft"] = array(
    "script" => "draft.php",
    "params" => array( ) );

$ViewList["archive"] = array(
    "script" => "archive.php",
    "params" => array( ) );


$ViewList["translations"] = array(
    "functions" => array( 'edit' ),
    "script" => "translations.php",
    'single_post_actions' => array( 'RemoveButton' => 'Remove',
                                    'StoreButton' => 'StoreNew',
/*                                    'EditButton' => 'Edit',
                                    'ChangeButton' => 'Change',*/
                                    'NewButton' => 'New',
                                    'ConfirmButton' => 'Confirm' ),
    'post_action_parameters' => array( 'StoreNew' => array( 'LocaleID' => 'LocaleID',
                                                            'TranslationName' => 'TranslationName',
                                                            'TranslationLocale' => 'TranslationLocale' ),
                                       'Remove' => array( 'SelectedTranslationList' => 'DeleteIDArray' ),
                                       'Confirm' => array( 'ConfirmList' => 'ConfirmTranlationID' ) ),
    "params" => array( ) );





$ClassID = array(
    'name'=> 'Class',
    'values'=> array(),
    "path" => "classes/",
    "file" => "ezcontentclass.php",
    "class" => 'eZContentClass',
    "function" => "fetchList",
    "parameter" => array( 0, false )
    );

$ParentClassID = array(
    'name'=> 'ParentClass',
    'values'=> array(),
    "path" => "classes/",
    "file" => "ezcontentclass.php",
    "class" => 'eZContentClass',
    "function" => "fetchList",
    "parameter" => array( 0, false )
    );

$SectionID = array(
    'name'=> 'Section',
    'values'=> array(),
    "path" => "classes/",
    "file" => "ezsection.php",
    "class" => 'eZSection',
    "function" => "fetchList",
    "parameter" => array(  false )
    );
$Status = array(
    'name'=> 'Status',
    'values'=> array(),
    "path" => "classes/",
    "file" => "ezcontentobjectversion.php",
    "class" => 'eZContentObjectVersion',
    "function" => "statusList",
    "parameter" => array(  false )
    );
$Assigned = array(
    'name'=> 'Owner',
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


$FunctionList['read'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'Owner' => $Assigned );
$FunctionList['create'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'ParentClass' => $ParentClassID
                                );
$FunctionList['edit'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'Owner' => $Assigned );
$FunctionList['remove'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'Owner' => $Assigned );

$FunctionList['versionread'] = array( 'Class' => $ClassID,
                                      'Section' => $SectionID,
                                      'Owner' => $Assigned,
                                      'Status' => $Status);

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
