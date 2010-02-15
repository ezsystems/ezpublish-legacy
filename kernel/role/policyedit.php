<?php
//
// Definition of Policyedit class
//
// Created on: <25-Apr-2003 11:31:32 wy>
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

/*! \file
*/


require_once( "kernel/common/template.php" );
$Module = $Params['Module'];
$policyID = $Params["PolicyID"];

$policy = eZPolicy::fetch( $policyID );
if( $policy === null )
{
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$currentModule = $policy->attribute( 'module_name' );
$currentFunction = $policy->attribute( 'function_name' );
$roleID = $policy->attribute( 'role_id' );
$role = eZRole::fetch( $roleID );
$roleName = $role->attribute( 'name' );
$limitationValueList = $policy->limitationList();
$nodeList = array();
$subtreeList = array();

if ( $currentModule == "*" )
{
    $functions = array();
}
else
{
    $mod = eZModule::exists( $currentModule );
    $functions = $mod->attribute( 'available_functions' );
}
$currentFunctionLimitations = array();
if ( isset( $functions[$currentFunction] ) && $functions[$currentFunction] )
{
    foreach ( $functions[$currentFunction] as $key => $limitation )
    {
        if ( ( count( $limitation['values'] ) == 0 ) && array_key_exists( 'class', $limitation ) )
        {
            $basePath = 'kernel/'; //set default basepath for limitationValueClasses
            if( array_key_exists( 'extension', $limitation ) && $limitation['extension'] )
            {
                $basePath = 'extension/' . $limitation['extension'] . '/';
            }
            include_once( $basePath . $limitation['path'] . $limitation['file']  );
            $obj = new $limitation['class']( array() );
            $limitationValueList = call_user_func_array( array( $obj, $limitation['function'] ), $limitation['parameter'] );
            $limitationValueArray = array();
            foreach ( $limitationValueList as $limitationValue )
            {
                $limitationValuePair = array();
                $limitationValuePair['Name'] = $limitationValue['name'];
                $limitationValuePair['value'] = $limitationValue['id'];
                $limitationValueArray[] = $limitationValuePair;
            }
            $limitation['values'] = $limitationValueArray;
        }
        $currentFunctionLimitations[ $key ] = $limitation;
    }
}

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "DeleteNodeButton" ) )
{
    if ( $http->hasPostVariable( "DeleteNodeIDArray" ) )
    {
        $deletedIDList = $http->postVariable( "DeleteNodeIDArray" );

        $db = eZDB::instance();
        $db->begin();
        foreach ( $deletedIDList as $deletedID )
        {
            eZPolicyLimitationValue::removeByValue( $deletedID, $policyID );
        }
        $db->commit();
    }

    /* Clean up policy cache */
    eZUser::cleanupCache();
}

if ( $http->hasPostVariable( "DeleteSubtreeButton" ) )
{
    if ( $http->hasPostVariable( "DeleteSubtreeIDArray" ) )
    {
        $deletedIDList = $http->postVariable( "DeleteSubtreeIDArray" );

        $db = eZDB::instance();
        $db->begin();
        foreach ( $deletedIDList as $deletedID )
        {
            $subtree = eZContentObjectTreeNode::fetch( $deletedID, false, false );
            $path = $subtree['path_string'];
            eZPolicyLimitationValue::removeByValue( $path, $policyID );
        }
        $db->commit();
    }

    /* Clean up policy cache */
    eZUser::cleanupCache();
}

// Fetch node limitations
$nodeIDList = array();
$nodeLimitation = eZPolicyLimitation::fetchByIdentifier( $policyID, "Node" );
if ( $nodeLimitation != null )
{
    $nodeLimitationID = $nodeLimitation->attribute( 'id' );
    $nodeLimitationValues = eZPolicyLimitationValue::fetchList( $nodeLimitationID );
    foreach ( $nodeLimitationValues as $nodeLimitationValue )
    {
        $nodeID = $nodeLimitationValue->attribute( 'value' );
        $nodeIDList[] = $nodeID;
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $nodeList[] = $node;
    }
}

// Fetch subtree limitations
$subtreeLimitation = eZPolicyLimitation::fetchByIdentifier( $policyID, "Subtree" );
if ( $subtreeLimitation != null )
{
    $subtreeLimitationID = $subtreeLimitation->attribute('id');
    $subtreeLimitationValues = eZPolicyLimitationValue::fetchList( $subtreeLimitationID );
    foreach ( $subtreeLimitationValues as $subtreeLimitationValue )
    {
        $subtreePath = $subtreeLimitationValue->attribute( 'value' );
        $subtreeObject = eZContentObjectTreeNode::fetchByPath( $subtreePath );
        if ( $subtreeObject )
        {
            $subtreeID = $subtreeObject->attribute( 'node_id' );
            if ( !isset( $subtreeIDList ) )
                $subtreeIDList = array();
            $subtreeIDList[] = $subtreeID;
            $subtree = eZContentObjectTreeNode::fetch( $subtreeID );
            $subtreeList[] = $subtree;
        }
    }
}

$http->setSessionVariable( 'DisableRoleCache', 1 );

if ( $http->hasPostVariable( "DiscardChange" ) )
{
    $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $roleID . '/');
}
if ( $http->hasPostVariable( "UpdatePolicy" ) )
{
    // Set flag for audit role-change. If true audit will be processed after applying
    $http->setSessionVariable( 'RoleWasChanged', true );

    $hasNodeLimitation = false;
    $hasLimitation = false;
    $db = eZDB::instance();
    $db->begin();
    $limitationList = eZPolicyLimitation::fetchByPolicyID( $policyID );
    foreach ( $limitationList as $limitation )
    {
        $limitationID = $limitation->attribute( 'id' );
        $limitationIdentifier = $limitation->attribute( 'identifier' );
        if ( $limitationIdentifier != "Node" and $limitationIdentifier != "Subtree" )
            eZPolicyLimitation::removeByID( $limitationID );
        if ( $limitationIdentifier == "Node" )
        {
            $nodeLimitationValues = eZPolicyLimitationValue::fetchList( $limitationID );
            if ( $nodeLimitationValues != null )
                $hasNodeLimitation = true;
            else
                eZPolicyLimitation::removeByID( $limitationID );
        }

        if ( $limitationIdentifier == "Subtree" )
        {
            $nodeLimitationValues = eZPolicyLimitationValue::fetchList( $limitationID );
            if ( $nodeLimitationValues != null )
                $hasLimitation = true;
            else
                eZPolicyLimitation::removeByID( $limitationID );

        }
    }

    foreach ( $currentFunctionLimitations as $functionLimitation )
    {
        if ( $http->hasPostVariable( $functionLimitation['name'] ) and
             $functionLimitation['name'] != "Node" and
             $functionLimitation['name'] != "Subtree" )
        {
            $limitationValueList = $http->postVariable( $functionLimitation['name'] );
            eZDebug::writeNotice( $limitationValueList, 'limitationValues');

            if ( !in_array('-1', $limitationValueList ) )
            {
                $hasLimitation = true;
                $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'),
                                                                   $functionLimitation['name'],
                                                                   $currentModule,
                                                                   $currentFunction );
                foreach ( $limitationValueList as $limitationValue )
                {
                    eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                }
            }
        }
    }

    $policy->store();
    $db->commit();

    /* Clean up policy cache */
    eZUser::cleanupCache();

    $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $roleID . '/');
}

if ( $http->hasPostVariable( "BrowseLimitationNodeButton" ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'FindLimitationNode',
                                    'content' => array( 'policy_id' => $policyID ),
                                    'from_page' => '/role/policyedit/' . $policyID ),
                             $Module );
    return;
}

if ( $http->hasPostVariable( "BrowseLimitationSubtreeButton" ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'FindLimitationSubtree',
                                    'content' => array( 'policy_id' => $policyID ),
                                    'from_page' => '/role/policyedit/' . $policyID ),
                             $Module );
    return;
}

if ( $http->hasPostVariable( "SelectedNodeIDArray" ) and
     $http->postVariable( "BrowseActionName" ) == "FindLimitationNode" and
     !$http->hasPostVariable( 'BrowseCancelButton' ) )
{
    $db = eZDB::instance();
    $db->begin();
    $limitationList = eZPolicyLimitation::fetchByPolicyID( $policyID );

    // Remove other limitations. When the policy is applied to node, no other constraints needed.
    // Removes limitations only from a DropList if it is specified in the module.
    if ( isset( $currentFunctionLimitations['Node']['DropList'] ) )
    {
        $dropList = $currentFunctionLimitations['Node']['DropList'];
        foreach ( $limitationList as $limitation )
        {
            $limitationID = $limitation->attribute( 'id' );
            $limitationIdentifier = $limitation->attribute( 'identifier' );
            if ( in_array( $limitationIdentifier, $dropList ) )
            {
                eZPolicyLimitation::removeByID( $limitationID );
            }
        }
    }
    else
    {
        foreach ( $limitationList as $limitation )
        {
            $limitationID = $limitation->attribute( 'id' );
            $limitationIdentifier = $limitation->attribute( 'identifier' );
            if ( $limitationIdentifier != 'Node' and $limitationIdentifier != 'Subtree' )
                eZPolicyLimitation::removeByID( $limitationID );
        }
    }

    $db->commit();

    $selectedNodeIDList = $http->postVariable( "SelectedNodeIDArray" );

    if ( $nodeLimitation == null )
        $nodeLimitation = eZPolicyLimitation::createNew( $policyID, "Node", $currentModule, $currentFunction);
    foreach ( $selectedNodeIDList as $nodeID )
    {
        if ( !in_array( $nodeID, $nodeIDList ) )
        {
            $nodeLimitationValue = eZPolicyLimitationValue::createNew( $nodeLimitation->attribute( 'id' ),  $nodeID );
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            $nodeList[] = $node;
        }
    }
}

if ( $http->hasPostVariable( "SelectedNodeIDArray" ) and
     $http->postVariable( "BrowseActionName" ) == "FindLimitationSubtree" and
     !$http->hasPostVariable( 'BrowseCancelButton' ) )
{
    $selectedSubtreeIDList = $http->postVariable( "SelectedNodeIDArray" );

    $db = eZDB::instance();
    $db->begin();
    if ( $subtreeLimitation == null )
        $subtreeLimitation = eZPolicyLimitation::createNew( $policyID, "Subtree", $currentModule, $currentFunction);

    foreach ( $selectedSubtreeIDList as $nodeID )
    {
        if ( !isset( $subtreeIDList ) or !in_array( $nodeID, $subtreeIDList ) )
        {
            $subtree = eZContentObjectTreeNode::fetch( $nodeID );
            $pathString = $subtree->attribute( 'path_string' );
            $policyLimitationValue = eZPolicyLimitationValue::createNew( $subtreeLimitation->attribute( 'id' ),  $pathString );
            $subtreeList[] = $subtree;
        }
    }
    $db->commit();
}

$currentLimitationList = array();
foreach ( $currentFunctionLimitations as $currentFunctionLimitation )
{
    $currentLimitationList[$currentFunctionLimitation['name']] = "-1";
}

$limitationList = eZPolicyLimitation::fetchByPolicyID( $policyID );
foreach ( $limitationList as $limitation )
{
    $limitationID = $limitation->attribute( 'id' );
    $limitationIdentifier = $limitation->attribute( 'identifier' );
    $limitationValueList = eZPolicyLimitationValue::fetchList( $limitationID );
    $valueList = array();
    foreach ( $limitationValueList as $limitationValue )
    {
        $valueList[] = $limitationValue->attribute( 'value' );
    }
    $currentLimitationList[$limitationIdentifier] = $valueList;
}

$Module->setTitle( "Edit policy" );
$tpl = templateInit();
$tpl->setVariable( "Module", $Module );
$tpl->setVariable( "current_function", $currentFunction );
$tpl->setVariable( "role_id", $roleID );
$tpl->setVariable( "role_name", $roleName );
$tpl->setVariable( "current_module", $currentModule );
$tpl->setVariable( "function_limitations", $currentFunctionLimitations );
$tpl->setVariable( "policy_id", $policyID );
$tpl->setVariable( "policy_limitation_list", $limitationValueList );
$tpl->setVariable( "node_list", $nodeList );
$tpl->setVariable( "subtree_list", $subtreeList );
$tpl->setVariable( "current_limitation_list", $currentLimitationList );

$Result = array();

$Result['path'] = array( array( 'url' => false,
                                'text' => eZi18n::translate( 'kernel/role', 'Editing policy' ) ) );
$Result['content'] = $tpl->fetch( 'design:role/policyedit.tpl' );
?>
