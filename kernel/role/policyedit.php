<?php
//
// Definition of Policyedit class
//
// Created on: <25-Apr-2003 11:31:32 wy>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file policyedit.php
*/


include_once( "kernel/classes/ezrole.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezpolicylimitation.php" );
include_once( "kernel/classes/ezpolicylimitationvalue.php" );
include_once( "kernel/classes/ezpolicy.php" );
include_once( "kernel/classes/ezcontentbrowse.php" );

$Module =& $Params["Module"];
$policyID =& $Params["PolicyID"];

if ( is_numeric( $policyID ) )
{
    $policy =& eZPolicy::fetch( $policyID );
}
else
{
    return;
}

$currentModule = $policy->attribute( 'module_name' );
$currentFunction = $policy->attribute( 'function_name' );
$roleID = $policy->attribute( 'role_id' );
$limitationValueList =& $policy->limitationList();
$nodeList = array();
$subtreeList = array();

if ( $currentModule == "*" )
{
    $functions = array();
}
else
{
    $mod = & eZModule::exists( $currentModule );
    $functions =& $mod->attribute( 'available_functions' );
}
$currentFunctionLimitations = array();
if ( $functions[$currentFunction] )
{
    foreach ( array_keys( $functions[$currentFunction] ) as $key )
    {
        $limitation =& $functions[$currentFunction][$key];
        if ( count( $limitation['values'] == 0 ) && array_key_exists( 'class', $limitation ) )
        {
            include_once( 'kernel/' . $limitation['path'] . $limitation['file'] );
            $obj = new $limitation['class']( array() );
            $limitationValueList = call_user_func_array( array( &$obj, $limitation['function'] ), $limitation['parameter'] );
            $limitationValueArray =  array();
            foreach ( array_keys( $limitationValueList ) as $key )
            {
                $limitationValue =& $limitationValueList[$key];
                $limitationValuePair = array();
                $limitationValuePair['Name'] = $limitationValue['name'];
                $limitationValuePair['value'] = $limitationValue['id'];
                $limitationValueArray[] = $limitationValuePair;
            }
            $limitation['values'] = $limitationValueArray;
        }
        $currentFunctionLimitations[] = $limitation;
    }
}
$currentLimitationList = array();
foreach ( array_keys( $currentFunctionLimitations ) as $key )
{
    $currentFunctionLimitation =& $currentFunctionLimitations[$key];
    $limitationName = $currentFunctionLimitation['name'];
    $currentLimitationList[$limitationName] = "-1";
}

$limitationList =& eZPolicyLimitation::fetchByPolicyID( $policyID );
foreach ( array_keys( $limitationList ) as $key )
{
    $limitation =& $limitationList[$key];
    $limitationID = $limitation->attribute( 'id' );
    $limitationIdentifier = $limitation->attribute( 'identifier' );
    $limitationValues =& eZPolicyLimitationValue::fetchList( $limitationID );
    $valueList = array();
    foreach ( array_keys( $limitationValues ) as $key )
    {
        $limitationValue = $limitationValues[$key];
        $value = $limitationValue->attribute( 'value' );
        $valueList[] = $value;
    }
    $currentLimitationList[$limitationIdentifier] = $valueList;
}

$http =& eZHttpTool::instance();
if ( $http->hasPostVariable( "DeleteNodeButton" ) )
{
    if ( $http->hasPostVariable( "DeleteNodeIDArray" ) )
    {
        $deletedIDList = $http->postVariable( "DeleteNodeIDArray" );

        foreach ( $deletedIDList as $deletedID )
        {
            eZPolicyLimitationValue::removeByValue( $deletedID );
        }
    }
}

if ( $http->hasPostVariable( "DeleteSubtreeButton" ) )
{
    if ( $http->hasPostVariable( "DeleteSubtreeIDArray" ) )
    {
        $deletedIDList = $http->postVariable( "DeleteSubtreeIDArray" );

        foreach ( $deletedIDList as $deletedID )
        {
            $subtree =& eZContentObjectTreeNode::fetch( $deletedID );
            $path = $subtree->attribute( 'path_string' );
            eZPolicyLimitationValue::removeByValue( $path );
        }
    }
}

// Fetch node limitations
$nodeLimitation =& eZPolicyLimitation::fetchByIdentifier( $policyID, "Node" );
if ( $nodeLimitation != null )
{
    $nodeLimitationID = $nodeLimitation->attribute( 'id' );
    $nodeLimitationValues =& eZPolicyLimitationValue::fetchList( $nodeLimitationID );
    foreach ( array_keys( $nodeLimitationValues ) as $key )
    {
        $nodeLimitationValue =& $nodeLimitationValues[$key];
        $nodeID = $nodeLimitationValue->attribute( 'value' );
        $nodeIDList[] = $nodeID;
        $node =& eZContentObjectTreeNode::fetch( $nodeID );
        $nodeList[] = $node;
    }
}

// Fetch subtree limitations
$subtreeLimitation =& eZPolicyLimitation::fetchByIdentifier( $policyID, "Subtree" );
if ( $subtreeLimitation != null )
{
    $subtreeLimitationID = $subtreeLimitation->attribute('id');
    $subtreeLimitationValues =& eZPolicyLimitationValue::fetchList( $subtreeLimitationID );
    foreach ( array_keys( $subtreeLimitationValues ) as $key )
    {
        $subtreeLimitationValue =& $subtreeLimitationValues[$key];
        $subtreePath = $subtreeLimitationValue->attribute( 'value' );
        $subtreeObject =& eZContentObjectTreeNode::fetchByPath( $subtreePath );
        if ( $subtreeObject )
        {
            $subtreeID = $subtreeObject->attribute( 'node_id' );
            if ( !isset( $subtreeIDList ) )
                $subtreeIDList = array();
            $subtreeIDList[] = $subtreeID;
            $subtree =& eZContentObjectTreeNode::fetch( $subtreeID );
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
    $hasNodeLimitation = false;
    $hasLimitation = false;
    foreach ( array_keys( $limitationList ) as $key )
    {
        $limitation =& $limitationList[$key];
        $limitationID = $limitation->attribute( 'id' );
        $limitationIdentifier = $limitation->attribute( 'identifier' );
        if ( $limitationIdentifier != "Node" and $limitationIdentifier != "Subtree" )
            eZPolicyLimitation::remove( $limitationID );
        if ( $limitationIdentifier == "Node" )
        {
            $nodeLimitationValues =& eZPolicyLimitationValue::fetchList( $limitationID );
            if ( $nodeLimitationValues != null )
                $hasNodeLimitation = true;
            else
                eZPolicyLimitation::remove( $limitationID );
        }

        if ( $limitationIdentifier == "Subtree" )
        {
            $nodeLimitationValues =& eZPolicyLimitationValue::fetchList( $limitationID );
            if ( $nodeLimitationValues != null )
                $hasLimitation = true;
            else
                eZPolicyLimitation::remove( $limitationID );

        }
    }

//    if ( !$hasNodeLimitation )
    {
        foreach ( array_keys( $currentFunctionLimitations ) as $key )
        {
            $functionLimitation =& $currentFunctionLimitations[$key];
            if ( $http->hasPostVariable( $functionLimitation['name'] ) and
                 $functionLimitation['name'] != "Node" and
                 $functionLimitation['name'] != "Subtree" )
            {
                $limitationValues = $http->postVariable( $functionLimitation['name'] );
                eZDebug::writeNotice( $limitationValues, 'limitationValues');

                if ( !in_array('-1', $limitationValues ) )
                {
                    $hasLimitation = true;
                    $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'], $currentModule, $currentFunction );
                    foreach ( array_keys( $limitationValues ) as $key )
                    {
                        $limitationValue =& $limitationValues[$key];
                        eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                    }
                }
            }
        }
    }

    $policy->store();

    $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $roleID . '/');
}
if ( $http->hasPostVariable( "DeleteSubtreeButton" ) )
{
    if ( $http->hasPostVariable( "DeleteSubtreeIDArray" ) )
    {
        $deletedIDList = $http->postVariable( "DeleteSubtreeIDArray" );

        foreach ( $deletedIDList as $deletedID )
        {
            $subtree =& eZContentObjectTreeNode::fetch( $deletedID );
            $path = $subtree->attribute( 'path_string' );
            eZPolicyLimitationValue::removeByValue( $path );
        }
    }
}

if ( $http->hasPostVariable( "BrowseLimitationNodeButton" ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'FindLimitationNode',
                                    'content' => array( 'policy_id' => $policyID ),
                                    'from_page' => '/role/policyedit/' . $policyID ),
                             $Module );
//     $http->setSessionVariable( "BrowseFromPage", "/role/policyedit/" . $policyID );
//     $http->setSessionVariable( "BrowseActionName", "FindLimitationNode" );
//     $http->setSessionVariable( "BrowseReturnType", "NodeID" );
//     $http->setSessionVariable( 'BrowseSelectionType', 'Multiple' );
//     $nodeID = 2;
//     $Module->redirectTo( "/content/browse/" . $nodeID );
    return;
}

if ( $http->hasPostVariable( "BrowseLimitationSubtreeButton" ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'FindLimitationSubtree',
                                    'content' => array( 'policy_id' => $policyID ),
                                    'from_page' => '/role/policyedit/' . $policyID ),
                             $Module );
//     $http->setSessionVariable( "BrowseFromPage", "/role/policyedit/" . $policyID );
//     $http->setSessionVariable( "BrowseActionName", "FindLimitationSubtree" );
//     $http->setSessionVariable( "BrowseReturnType", "NodeID" );
//     $http->setSessionVariable( 'BrowseSelectionType', 'Multiple' );
//     $nodeID = 2;
//     $Module->redirectTo( "/content/browse/" . $nodeID );
    return;
}

if ( $http->hasPostVariable( "SelectedNodeIDArray" ) and
     $http->postVariable( "BrowseActionName" ) == "FindLimitationNode" )
{
    // Remove other limitations. When the policy is applied to node, no other constraints needed.
    foreach ( $limitationList as $limitation )
    {
        $limitationID = $limitation->attribute( 'id' );
        $limitationIdentifier = $limitation->attribute( 'identifier' );
        if ( $limitationIdentifier != "Node" and $limitationIdentifier != "Subtree" )
            eZPolicyLimitation::remove( $limitationID );
    }

    $selectedNodeIDList = $http->postVariable( "SelectedNodeIDArray" );

    if ( $nodeLimitation == null )
        $nodeLimitation = eZPolicyLimitation::createNew( $policyID, "Node", $currentModule, $currentFunction);
    foreach ( $selectedNodeIDList as $nodeID )
    {
        if ( !in_array( $nodeID, $nodeIDList ) )
        {
            $nodeLimitationValue =& eZPolicyLimitationValue::createNew( $nodeLimitation->attribute( 'id' ),  $nodeID );
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
            $nodeList[] = $node;
        }
    }
}

if ( $http->hasPostVariable( "SelectedNodeIDArray" ) and $http->postVariable( "BrowseActionName" ) == "FindLimitationSubtree" )
{
    $selectedSubtreeIDList = $http->postVariable( "SelectedNodeIDArray" );

    if ( $subtreeLimitation == null )
        $subtreeLimitation = eZPolicyLimitation::createNew( $policyID, "Subtree", $currentModule, $currentFunction);

    foreach ( $selectedSubtreeIDList as $nodeID )
    {
        if ( !in_array( $nodeID, $subtreeIDList ) )
        {
            $subtree =& eZContentObjectTreeNode::fetch( $nodeID );
            $pathString = $subtree->attribute( 'path_string' );
            $policyLimitationValue =& eZPolicyLimitationValue::createNew( $subtreeLimitation->attribute( 'id' ),  $pathString );
            $subtreeList[] = $subtree;
        }
    }
}

$Module->setTitle( "Edit policy" );
$tpl =& templateInit();
$tpl->setVariable( "Module", $Module );
$tpl->setVariable( "current_function", $currentFunction );
$tpl->setVariable( "role_id", $roleID );
$tpl->setVariable( "current_module", $currentModule );
$tpl->setVariable( "function_limitations", $currentFunctionLimitations );
$tpl->setVariable( "policy_id", $policyID );
$tpl->setVariable( "policy_limitation_list", $limitationValueList );
$tpl->setVariable( "node_list", $nodeList );
$tpl->setVariable( "subtree_list", $subtreeList );
$tpl->setVariable( "current_limitation_list", $currentLimitationList );

$Result = array();

$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/role', 'Editing policy' ) ) );
$Result['content'] =& $tpl->fetch( 'design:role/policyedit.tpl' );
?>
