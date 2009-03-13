<?php
//
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

$Module = array( 'name' => 'eZContentObjectState',
                 'variable_params' => false );

$ViewList = array();

$ViewList['assign'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'assign.php',
    'params' => array( 'ObjectID', 'SelectedStateID' ),
    'functions' => array( 'assign' ),
    'single_post_actions' => array( 'AssignButton' => 'Assign' ),
    'post_action_parameters' => array( 'Assign' => array( 'ObjectID'            => 'ObjectID',
                                                          'SelectedStateIDList' => 'SelectedStateIDList',
                                                          'RedirectRelativeURI' => 'RedirectRelativeURI' ) )
);

$ViewList['groups'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'groups.php',
    'params' => array(),
    'functions' => array( 'administrate' ),
    'unordered_params' => array( 'offset' => 'Offset' ),
    'single_post_actions' => array( 'CreateButton' => 'Create',
                                    'RemoveButton' => 'Remove' ),
    'post_action_parameters' => array( 'Remove' => array( 'RemoveIDList' => 'RemoveIDList' ) )
);

$ViewList['group'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'group.php',
    'params' => array( 'GroupIdentifier', 'Language' ),
    'functions' => array( 'administrate' ),
    'single_post_actions' => array( 'CreateButton' => 'Create',
                                    'UpdateOrderButton' => 'UpdateOrder',
                                    'EditButton' => 'Edit',
                                    'RemoveButton' => 'Remove' ),
    'post_action_parameters' => array( 'UpdateOrder' => array( 'Order' => 'Order' ),
                                       'Remove' => array( 'RemoveIDList' => 'RemoveIDList' ) )
);

$ViewList['group_edit'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'group_edit.php',
    'ui_context' => 'edit',
    'params' => array( 'GroupIdentifier' ),
    'functions' => array( 'administrate' ),
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'CancelButton' => 'Cancel' )
);

$ViewList['view'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'view.php',
    'params' => array( 'GroupIdentifier', 'StateIdentifier', 'Language' ),
    'functions' => array( 'administrate' ),
    'single_post_actions' => array( 'EditButton' => 'Edit' )
);

$ViewList['edit'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'script' => 'edit.php',
    'ui_context' => 'edit',
    'params' => array( 'GroupIdentifier', 'StateIdentifier' ),
    'functions' => array( 'administrate' ),
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'CancelButton' => 'Cancel' )
);

$ClassID = array(
    'name'=> 'Class',
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

$Assigned = array(
    'name'=> 'Owner',
    'values'=> array(
        array(
            'Name' => 'Self',
            'value' => '1')
        )
    );

$AssignedGroup = array(
    'name'=> 'Group',
    'single_select' => true,
    'values'=> array(
        array( 'Name' => 'Self',
               'value' => '1') ) );

$Node = array(
    'name'=> 'Node',
    'values'=> array()
    );

$Subtree = array(
    'name'=> 'Subtree',
    'values'=> array()
    );

$stateLimitations = eZContentObjectStateGroup::limitations();

$NewState = array(
    'name' => 'NewState',
    'values' => array(),
    'path' => 'private/classes/',
    'file' => 'ezcontentobjectstate.php',
    'class' => 'eZContentObjectState',
    'function' => 'limitationList',
    'parameter' => array()
);

$FunctionList = array();

$FunctionList['administrate'] = array();

$FunctionList['assign'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'Owner' => $Assigned,
                                 'Group' => $AssignedGroup,
                                 'Node' => $Node,
                                 'Subtree' => $Subtree );

$FunctionList['assign'] = array_merge( $FunctionList['assign'], $stateLimitations, array( 'NewState' => $NewState ) );

?>