<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

$ViewList = array();
$ViewList['edit'] = array(
    'functions' => array( 'edit or create' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'ui_context' => 'edit',
    'single_post_actions' => array( 'PreviewButton' => 'Preview',
                                    'TranslateButton' => 'Translate',
                                    'VersionsButton' => 'VersionEdit',
                                    'PublishButton' => 'Publish',
                                    'DiscardButton' => 'Discard',
                                    'BrowseNodeButton' => 'BrowseForNodes',
                                    'RemoveAssignmentButton' => 'RemoveAssignments',
                                    'EditLanguageButton' => 'EditLanguage',
                                    'FromLanguageButton' => 'FromLanguage',
                                    'TranslateLanguageButton' => 'TranslateLanguage',
                                    'BrowseObjectButton' => 'BrowseForObjects',
                                    'UploadFileRelationButton' => 'UploadFileRelation',
                                    'NewButton' => 'NewObject',
                                    'DeleteRelationButton' => 'DeleteRelation',
                                    'StoreButton' => 'Store',
                                    'StoreExitButton' => 'StoreExit',
                                    'MoveNodeID' => 'MoveNode',
                                    'RemoveNodeID' => 'DeleteNode',
                                    'ConfirmButton' => 'ConfirmAssignmentDelete',
                                    'SectionEditButton' => 'SectionEdit'
                                    ),
    'post_action_parameters' => array( 'EditLanguage' => array( 'SelectedLanguage' => 'EditSelectedLanguage' ),
                                       'FromLanguage' => array( 'FromLanguage' => 'FromLanguage' ),
                                       'TranslateLanguage' => array( 'SelectedLanguage' => 'EditSelectedLanguage' ),
                                       'UploadFileRelation' => array( 'UploadRelationLocation' => 'UploadRelationLocationChoice' ) ),
    'post_actions' => array( 'BrowseActionName' ),
    'script' => 'edit.php',
    'params' => array( 'ObjectID', 'EditVersion', 'EditLanguage', 'FromLanguage' ) );

$ViewList['removenode'] = array(
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'ui_context' => 'edit',
    'single_post_actions' => array( 'ConfirmButton' => 'ConfirmAssignmentRemove',
                                    'CancelButton' => 'CancelAssignmentRemove' ),
    'script' => 'removenode.php',
    'params' => array( 'ObjectID', 'EditVersion', 'EditLanguage', 'NodeID' ) );

$ViewList['removeassignment'] = array(
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'ui_context' => 'edit',
    'ui_component' => 'content',
    'single_post_actions' => array( 'ConfirmRemovalButton' => 'ConfirmRemoval',
                                    'CancelRemovalButton' => 'CancelRemoval' ),
    'script' => 'removeassignment.php',
    'params' => array() );

$ViewList['pdf'] = array(
    'functions' => array( 'pdf' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'pdf.php',
    'params' => array( 'NodeID' ),
    'unordered_params' => array( 'language' => 'Language',
                                 'offset' => 'Offset',
                                 'year' => 'Year',
                                 'month' => 'Month',
                                 'day' => 'Day' )
    );

$ViewList['view'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'view.php',
    'params' => array( 'ViewMode', 'NodeID' ),
    'unordered_params' => array( 'language' => 'Language',
                                 'offset' => 'Offset',
                                 'year' => 'Year',
                                 'month' => 'Month',
                                 'day' => 'Day' )
    );

$ViewList['copy'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'copy.php',
    'ui_context' => 'edit',
    'single_post_actions' => array( 'CopyButton' => 'Copy',
                                    'CancelButton' => 'Cancel' ),
    'post_action_parameters' => array( 'Copy' => array( 'VersionChoice' => 'VersionChoice' ) ),
    'post_actions' => array( 'BrowseActionName' ),
    'params' => array( 'ObjectID' ) );

$ViewList['copysubtree'] = array(
    'functions' => array( 'create' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'copysubtree.php',
    'ui_context' => 'administration',
    'single_post_actions' => array( 'CopyButton' => 'Copy',
                                    'CancelButton' => 'Cancel' ),
    'post_action_parameters' => array( 'Copy' => array( 'VersionChoice' => 'VersionChoice',
                                                        'CreatorChoice' => 'CreatorChoice',
                                                        'TimeChoice' => 'TimeChoice' ) ),
    'post_actions' => array( 'BrowseActionName' ),
    'params' => array( 'NodeID' )
    );

$ViewList['versionview'] = array(
    'functions' => array( 'versionread' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'ui_context' => 'edit',
    'script' => 'versionview.php',
    'single_post_actions' => array( 'ChangeSettingsButton' => 'ChangeSettings',
                                    'EditButton' => 'Edit',
                                    'VersionsButton' => 'Versions',
                                    'PreviewPublishButton' => 'Publish' ),
    'post_action_parameters' => array( 'ChangeSettings' => array( 'Language' => 'SelectedLanguage',
                                                                  'PlacementID' => 'SelectedPlacement',
                                                                  'SiteAccess' => 'SelectedSiteAccess' ) ),
    'params' => array( 'ObjectID', 'EditVersion', 'LanguageCode', 'FromLanguage' ),
    'unordered_params' => array( 'language' => 'Language',
                                 'offset' => 'Offset',
                                 'site_access' => 'SiteAccess' ) );

$ViewList['restore'] = array(
    'functions' => array( 'restore' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'ui_context' => 'administration',
    'script' => 'restore.php',
    'single_post_actions' => array( 'ConfirmButton' => 'Confirm',
                                    'CancelButton' => 'Cancel',
                                    'AddLocationAction' => 'AddLocation' ),
    'post_action_parameters' => array( 'Confirm' => array( 'RestoreType' => 'RestoreType' ) ),
    'params' => array( 'ObjectID' ) );

$ViewList['search'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'search.php',
    'params' => array( ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['urlalias'] = array(
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'urlalias.php',
    'ui_context' => 'administration',
    'single_post_actions' => array( 'NewAliasButton' => 'NewAlias',
                                    'RemoveAllAliasesButton' => 'RemoveAllAliases',
                                    'RemoveAliasButton' => 'RemoveAlias' ),
    'post_action_parameters' => array( 'RemoveAlias' => array( 'ElementList' => 'ElementList' ),
                                       'NewAlias' => array( 'LanguageCode' => 'LanguageCode',
                                                            'AliasText' => 'AliasText' ) ),
    'params' => array( 'NodeID' ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['urltranslator'] = array(
    'functions' => array( 'urltranslator' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'urlalias_global.php',
    'ui_context' => 'administration',
    'single_post_actions' => array( 'NewAliasButton' => 'NewAlias',
                                    'RemoveAllAliasesButton' => 'RemoveAllAliases',
                                    'RemoveAliasButton' => 'RemoveAlias' ),
    'post_action_parameters' => array( 'RemoveAlias' => array( 'ElementList' => 'ElementList' ),
                                       'NewAlias' => array( 'LanguageCode' => 'LanguageCode',
                                                            'AliasSourceText' => 'AliasSourceText',
                                                            'AliasDestinationText' => 'AliasDestinationText' ) ),
/*    'single_post_actions' => array( 'NewURLAliasButton' => 'NewURLAlias',
                                    'NewForwardURLAliasButton' => 'NewForwardURLAlias',
                                    'NewWildcardURLAliasButton' => 'NewWildcardURLAlias',
                                    'RemoveURLAliasButton' => 'RemoveURLAlias',
                                    'StoreURLAliasButton' => 'StoreURLAlias' ),*/
    'params' => array( ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['urlwildcards'] = array(
    'functions' => array( 'urltranslator' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'urlalias_wildcard.php',
    'ui_context' => 'administration',
    'single_post_actions' => array( 'NewWildcardButton' => 'NewWildcard',
                                    'RemoveAllWildcardsButton' => 'RemoveAllWildcards',
                                    'RemoveWildcardButton' => 'RemoveWildcard' ),
    'post_action_parameters' => array( 'RemoveWildcard' => array( 'WildcardIDList' => 'WildcardIDList',
                                                                  'Offset' => 'Offset' ),
                                       'NewWildcard' => array( 'WildcardType' => 'WildcardType',
                                                               'WildcardSourceText' => 'WildcardSourceText',
                                                               'WildcardDestinationText' => 'WildcardDestinationText',
                                                               'Offset' => 'Offset' ) ),
    'params' => array( ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['advancedsearch'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'advancedsearch.php',
    'params' => array( 'ViewMode' ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['browse'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'ui_context' => 'browse',
    'script' => 'browse.php',
    'params' => array( 'NodeID', 'ObjectID', 'EditVersion' ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['upload'] = array(
    'functions' => array( 'create' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'upload.php',
    'single_post_actions' => array( 'UploadFileButton' => 'UploadFile',
                                    'CancelUploadButton' => 'CancelUpload' ),
    'post_action_parameters' => array( 'UploadFile' => array( 'UploadLocation' => 'UploadLocationChoice',
                                                              'ObjectName' => 'ObjectName' ) ),
    'params' => array() );

$ViewList['removeobject'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'removeobject.php',
    'params' => array(  ) );

$ViewList['removeuserobject'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezusernavigationpart',
    'script' => 'removeobject.php',
    'params' => array( ) );

$ViewList['removemediaobject'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezmedianavigationpart',
    'script' => 'removeobject.php',
    'params' => array( ) );

$ViewList['removeeditversion'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'removeeditversion.php',
    'ui_context' => 'edit',
    'params' => array( ) );

$ViewList['download'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'download.php',
    'params' => array( 'ContentObjectID', 'ContentObjectAttributeID' ),
    'unordered_params' => array( 'version' => 'Version' ) );

$ViewList['action'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'action.php',
    'ui_context' => 'edit',
    'params' => array(  ),
    'single_post_actions' => array( 'RemoveAssignmentButton' => 'RemoveAssignment',
                                    'AddAssignmentButton' => 'SelectAssignmentLocation',
                                    'AddAssignmentAction' => 'AddAssignment',
                                    'UpdateMainAssignmentButton' => 'UpdateMainAssignment',
                                    'ClearViewCacheButton' => 'ClearViewCache',
                                    'ClearViewCacheSubtreeButton' => 'ClearViewCacheSubtree',
                                    'MoveNodeButton' => 'MoveNodeRequest',
                                    'MoveNodeAction' => 'MoveNode',
                                    'SwapNodeButton' => 'SwapNodeRequest',
                                    'SwapNodeAction' => 'SwapNode',
                                    'UploadFileAction' => 'UploadFile' ),
    'post_action_parameters' => array( 'SelectAssignmentLocation' => array( 'AssignmentIDSelection' => 'AssignmentIDSelection',
                                                                            'NodeID' => 'ContentNodeID',
                                                                            'ObjectID' => 'ContentObjectID',
                                                                            'ViewMode' => 'ViewMode',
                                                                            'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'AddAssignment' => array( 'AssignmentIDSelection' => 'AssignmentIDSelection',
                                                                 'NodeID' => 'ContentNodeID',
                                                                 'ObjectID' => 'ContentObjectID',
                                                                 'ViewMode' => 'ViewMode',
                                                                 'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'RemoveAssignment' => array( 'AssignmentIDSelection' => 'AssignmentIDSelection', // Note: AssignmentIDSelection is deprecated, use LocationIDSelection
                                                                    'LocationIDSelection' => 'LocationIDSelection',
                                                                    'NodeID' => 'ContentNodeID',
                                                                    'ObjectID' => 'ContentObjectID',
                                                                    'ViewMode' => 'ViewMode',
                                                                    'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'UpdateMainAssignment' => array( 'MainAssignmentID' => 'MainAssignmentCheck',
                                                                        'HasMainAssignment' => 'HasMainAssignment',
                                                                        'NodeID' => 'ContentNodeID',
                                                                        'ObjectID' => 'ContentObjectID',
                                                                        'ViewMode' => 'ViewMode',
                                                                        'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'ClearViewCache' => array( 'NodeID' => 'NodeID',
                                                                  'ObjectID' => 'ObjectID',
                                                                  'ViewMode' => 'ViewMode',
                                                                  'LanguageCode' => 'ContentObjectLanguageCode',
                                                                  'CurrentURL' => 'CurrentURL' ),
                                       'ClearViewCacheSubtree' => array( 'NodeID' => 'NodeID',
                                                                         'ObjectID' => 'ObjectID',
                                                                         'ViewMode' => 'ViewMode',
                                                                         'LanguageCode' => 'ContentObjectLanguageCode',
                                                                         'CurrentURL' => 'CurrentURL' ),
                                       'MoveNodeRequest' => array( 'NodeID' => 'ContentNodeID',
                                                                   'ViewMode' => 'ViewMode',
                                                                   'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'MoveNode' => array( 'NodeID' => 'ContentNodeID',
                                                            'ViewMode' => 'ViewMode',
                                                            'NewParentNode' => 'NewParentNode',
                                                            'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'SwapNodeRequest' => array( 'NodeID' => 'ContentNodeID',
                                                                   'ViewMode' => 'ViewMode',
                                                                   'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'SwapNode' => array( 'NodeID' => 'ContentNodeID',
                                                            'ViewMode' => 'ViewMode',
                                                            'NewNode' => 'NewNode',
                                                            'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'UploadFile' => array( 'UploadActionName' => 'UploadActionName',
                                                              'UploadParentNodes' => 'UploadParentNodes',
                                                              'UploadRedirectBack' => 'UploadRedirectBack' ) ),
    'post_actions' => array( 'BrowseActionName' ) );

$ViewList['collectinformation'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'collectinformation.php',
    'single_post_actions' => array( 'ActionCollectInformation' => 'CollectInformation' ),
    'post_action_parameters' => array( 'CollectInformation' => array( 'ContentObjectID' => 'ContentObjectID',
                                                                      'ContentNodeID' => 'ContentNodeID',
                                                                      'ViewMode' => 'ViewMode' ) ),
    'params' => array(  ) );

$ViewList['versions'] = array(
    'functions' => array( 'read', 'edit' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'ui_context' => 'edit',
    'script' => 'versions.php',
    'single_post_actions' => array( 'CopyVersionButton' => 'CopyVersion',
                                    'EditButton' => 'Edit' ),
    'post_action_parameters' => array( 'CopyVersion' => array( 'VersionID' => 'RevertToVersionID',
                                                               'VersionKeyArray' => 'CopyVersionButton',
                                                               'LanguageArray' => 'CopyVersionLanguage' ),
                                       'Edit' => array( 'VersionID' => 'RevertToVersionID',
                                                        'VersionKeyArray' => 'EditButton' ) ),
    'params' => array( 'ObjectID' ,'EditVersion' ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['draft'] = array(
    'functions' => array( 'edit' ),
    'script' => 'draft.php',
    'default_navigation_part' => 'ezmynavigationpart',
    'params' => array( ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['diff'] = array(
    'functions' => array( 'diff' ),
    'script' => 'diff.php',
    'default_navigation_part' => 'ezcontentnavigationpart',
    'params' => array( 'ObjectID' ),
    'unordered_params' => array( 'offset' => 'Offset'  ) );

$ViewList['history'] = array(
    'functions' => array( 'read', 'edit' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'ui_context' => 'edit',
    'script' => 'history.php',
    'single_post_actions' => array( 'HistoryCopyVersionButton' => 'CopyVersion',
                                    'HistoryEditButton' => 'Edit' ),
    'post_action_parameters' => array( 'CopyVersion' => array( 'VersionID' => 'RevertToVersionID',
                                                               'VersionKeyArray' => 'HistoryCopyVersionButton',
                                                               'LanguageArray' => 'CopyVersionLanguage' ),
                                       'Edit' => array( 'VersionID' => 'RevertToVersionID',
                                                        'VersionKeyArray' => 'HistoryEditButton' ) ),
    'params' => array( 'ObjectID' ,'EditVersion' ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['trash'] = array(
    'functions' => array( 'restore' ),
    'script' => 'trash.php',
    'default_navigation_part' => 'ezcontentnavigationpart',
    'params' => array( ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['translations'] = array(
    'functions' => array( 'translations' ),
    'ui_context' => 'administration',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'translations.php',
    'single_post_actions' => array( 'RemoveButton' => 'Remove',
                                    'StoreButton' => 'StoreNew',
                                    'NewButton' => 'New',
                                    'ConfirmButton' => 'Confirm' ),
    'post_action_parameters' => array( 'StoreNew' => array( 'LocaleID' => 'LocaleID',
                                                            'TranslationName' => 'TranslationName',
                                                            'TranslationLocale' => 'TranslationLocale' ),
                                       'Remove' => array( 'SelectedTranslationList' => 'DeleteIDArray' ),
                                       'Confirm' => array( 'ConfirmList' => 'ConfirmTranlationID' ) ),
    'params' => array( 'TranslationID' ) );

$ViewList['tipafriend'] = array(
    'functions' => array( 'tipafriend', 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'tipafriend.php',
    'params' => array( 'NodeID' ) );

$ViewList['keyword'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'keyword.php',
    'params' => array( 'alphabet'=>'Alphabet' ),
    'unordered_params' => array( 'offset' => 'Offset', 'classid' => 'ClassID' ) );

$ViewList['collectedinfo'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'collectedinfo.php',
    'params' => array( 'NodeID' ) );

$ViewList['bookmark'] = array(
    'functions' => array( 'bookmark' ),
    'default_navigation_part' => 'ezmynavigationpart',
    'script' => 'bookmark.php',
    'params' => array(),
    'single_post_actions' => array( 'AddButton' => 'Add',
                                    'RemoveButton' => 'Remove' ),
    'post_actions' => array( 'BrowseActionName' ),
    'post_action_parameters' => array( 'Remove' => array( 'DeleteIDArray' => 'DeleteIDArray' ) ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['pendinglist'] = array(
    'functions' => array( 'pendinglist' ),
    'default_navigation_part' => 'ezmynavigationpart',
    'script' => 'pendinglist.php',
    'params' => array(),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['new'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'newcontent.php',
    'params' => array() );

$ViewList['hide'] = array(
    'functions' => array( 'hide' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'hide.php',
    'params' => array( 'NodeID' ) );

$ViewList['move'] = array(
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'move.php',
    'params' => array( 'NodeID' ) );

$ViewList['reverserelatedlist'] = array(
    'functions' => array( 'reverserelatedlist' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'reverserelatedlist.php',
    'params' => array( 'NodeID' ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['translation'] = array(
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezcontentnavigationpart',
    'script' => 'translation.php',
    'params' => array(  ),
    'single_post_actions' => array( 'CancelButton' => 'Cancel',
                                    'UpdateInitialLanguageButton' => 'UpdateInitialLanguage',
                                    'UpdateAlwaysAvailableButton' => 'UpdateAlwaysAvailable',
                                    'RemoveTranslationButton' => 'RemoveTranslation' ),
    'post_action_parameters' => array( 'Cancel' => array( 'NodeID' => 'ContentNodeID',
                                                          'ViewMode' => 'ViewMode',
                                                          'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'UpdateInitialLanguage' => array( 'InitialLanguageID' => 'InitialLanguageID',
                                                                         'NodeID' => 'ContentNodeID',
                                                                         'ObjectID' => 'ContentObjectID',
                                                                         'ViewMode' => 'ViewMode',
                                                                         'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'UpdateAlwaysAvailable' => array( 'AlwaysAvailable' => 'AlwaysAvailable',
                                                                         'NodeID' => 'ContentNodeID',
                                                                         'ObjectID' => 'ContentObjectID',
                                                                         'ViewMode' => 'ViewMode',
                                                                         'LanguageCode' => 'ContentObjectLanguageCode' ),
                                       'RemoveTranslation' => array( 'LanguageID' => 'LanguageID',
                                                                     'ConfirmRemoval' => 'ConfirmRemoval',
                                                                     'NodeID' => 'ContentNodeID',
                                                                     'ObjectID' => 'ContentObjectID',
                                                                     'ViewMode' => 'ViewMode',
                                                                     'LanguageCode' => 'ContentObjectLanguageCode' ) ) );

$ViewList['treemenu'] = array(
    'functions' => array( 'read' ),
    'script' => 'treemenu.php',
    'default_navigation_part' => 'ezmynavigationpart',
    'params' => array() );

$ClassID = array(
    'name'=> 'Class',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezcontentclass.php',
    'class' => 'eZContentClass',
    'function' => 'fetchList',
    'parameter' => array( 0, false, false, array( 'name' => 'asc' ) )
    );

$ParentClassID = array(
    'name'=> 'ParentClass',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezcontentclass.php',
    'class' => 'eZContentClass',
    'function' => 'fetchList',
    'parameter' => array( 0, false, false, array( 'name' => 'asc' ) )
    );

$SectionID = array(
    'name'=> 'Section',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezsection.php',
    'class' => 'eZSection',
    'function' => 'fetchList',
    'parameter' => array( false )
    );

$VersionStatusRead = array(
    'name'=> 'Status',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezcontentobjectversion.php',
    'class' => 'eZContentObjectVersion',
    'function' => 'statusList',
    'parameter' => array( 'read' )
    );

$VersionStatusRemove = array(
    'name'=> 'Status',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezcontentobjectversion.php',
    'class' => 'eZContentObjectVersion',
    'function' => 'statusList',
    'parameter' => array( 'remove' )
    );

$Language = array(
    'name'=> 'Language',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezcontentlanguage.php',
    'class' => 'eZContentLanguage',
    'function' => 'fetchLimitationList',
    'parameter' => array( false )
    );

$Assigned = array(
    'name'=> 'Owner',
    'values'=> array(
        array(
            'Name' => 'Self',
            'value' => '1')
        )
    );

$AssignedEdit = array(
    'name'=> 'Owner',
    'single_select' => true,
    'values'=> array(
        array( 'Name' => 'Self',
               'value' => '1'),
        array( 'Name' => 'Self or anonymous users per HTTP session',
               'value' => '2' ) ) );

$AssignedGroup = array(
    'name'=> 'Group',
    'single_select' => true,
    'values'=> array(
        array( 'Name' => 'Self',
               'value' => '1') ) );

$ParentDepth = array(
    'name' => 'ParentDepth',
    'values' => array(),
    'path' => 'classes/',
    'file' => 'ezcontentobjecttreenode.php',
    'class' => 'eZContentObjectTreeNode',
    'function' => 'parentDepthLimitationList',
    'parameter' => array( false )
    );

$Node = array(
    'name'=> 'Node',
    'values'=> array()
    );

$Subtree = array(
    'name'=> 'Subtree',
    'values'=> array()
    );


$FunctionList = array();
$FunctionList['bookmark'] = array();

$FunctionList['move'] = array();

$FunctionList['read'] = array( 'Class' => $ClassID,
                               'Section' => $SectionID,
                               'Owner' => $Assigned,
                               'Group' => $AssignedGroup,
                               'Node' => $Node,
                               'Subtree' => $Subtree);
$FunctionList['diff'] = array( 'Class' => $ClassID,
                               'Section' => $SectionID,
                               'Owner' => $Assigned,
                               'Node' => $Node,
                               'Subtree' => $Subtree);
$FunctionList['view_embed'] = array( 'Class' => $ClassID,
                                     'Section' => $SectionID,
                                     'Owner' => $Assigned,
                                     'Node' => $Node,
                                     'Subtree' => $Subtree);
$FunctionList['create'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'ParentClass' => $ParentClassID,
                                 'ParentDepth' => $ParentDepth,
                                 'Node' => array_merge(  $Node, array( 'DropList' => array( 'ParentClass', 'Section' ) ) ),
                                 'Subtree' => $Subtree,
                                 'Language' => $Language
                                 );
$FunctionList['edit'] = array( 'Class' => $ClassID,
                               'Section' => $SectionID,
                               'Owner' => $AssignedEdit,
                               'Group' => $AssignedGroup,
                               'Node' => $Node,
                               'Subtree' => $Subtree,
                               'Language' => $Language);

$FunctionList['manage_locations'] = array( 'Class' => $ClassID,
                                           'Section' => $SectionID,
                                           'Owner' => $Assigned,
                                           'Subtree' => $Subtree );

$FunctionList['hide'] = array( 'Subtree' => $Subtree );

$FunctionList['reverserelatedlist'] = array();

$FunctionList['translate'] = array( 'Class' => $ClassID,
                                    'Section' => $SectionID,
                                    'Owner' => $Assigned,
                                    'Node' => $Node,
                                    'Subtree' => $Subtree,
                                    'Language' => $Language);
$FunctionList['remove'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'Owner' => $Assigned,
                                 'Node' => $Node,
                                 'Subtree' => $Subtree
                                 );

$FunctionList['versionread'] = array( 'Class' => $ClassID,
                                      'Section' => $SectionID,
                                      'Owner' => $Assigned,
                                      'Status' => $VersionStatusRead,
                                      'Node' => $Node,
                                      'Subtree' => $Subtree);

$FunctionList['versionremove'] = array( 'Class' => $ClassID,
                                        'Section' => $SectionID,
                                        'Owner' => $Assigned,
                                        'Status' => $VersionStatusRemove,
                                        'Node' => $Node,
                                        'Subtree' => $Subtree);

$FunctionList['pdf'] = array( 'Class' => $ClassID,
                              'Section' => $SectionID,
                              'Owner' => $Assigned,
                              'Node' => $Node,
                              'Subtree' => $Subtree );

$FunctionList['translations'] = array();
$FunctionList['urltranslator'] = array();
$FunctionList['pendinglist'] = array();
$FunctionList['restore'] = array();
$FunctionList['cleantrash'] = array();
$FunctionList['tipafriend'] = array();

?>
