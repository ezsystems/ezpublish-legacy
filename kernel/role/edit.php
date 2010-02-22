<?php
//
// Created on: <19-Aug-2002 16:38:41 sp>
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



$tpl = eZTemplate::factory();
$Module = $Params['Module'];
$roleID = $Params['RoleID'];

$ini = eZINI::instance( 'module.ini' );
$modules = $ini->variable( 'ModuleSettings', 'ModuleList' );
sort( $modules );

$role = eZRole::fetch( 0, $roleID );
if ( $role === null )
{
    $role = eZRole::fetch( $roleID );
    if ( $role )
    {
        if ( $role->attribute( 'version' ) == '0' )
        {
            $temporaryRole = $role->createTemporaryVersion();
            unset( $role );
            $role = $temporaryRole;
        }
    }
    else
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}

$http = eZHTTPTool::instance();

$tpl->setVariable( 'module', $Module );

$role->turnOffCaching();

$tpl->setVariable( 'role', $role );
$Module->setTitle( 'Edit ' . $role->attribute( 'name' ) );

if ( $http->hasPostVariable( 'NewName' ) && $role->attribute( 'name' ) != $http->postVariable( 'NewName' ) )
{
    $role->setAttribute( 'name' , $http->postVariable( 'NewName' ) );
    $role->store();
    // Set flag for audit. If true audit will be processed
    $http->setSessionVariable( 'RoleWasChanged', true );
}

$showModules = true;
$showFunctions = false;
$showLimitations = false;
$noFunctions = false;
$noLimitations = false;

if ( $http->hasPostVariable( 'Apply' ) )
{
    $originalRole = eZRole::fetch( $role->attribute( 'version' ) );
    $originalRoleName = $originalRole->attribute( 'name' );
    $originalRoleID = $originalRole->attribute( 'id' );

    // Who changes which role(s) should be logged.
    if ( $http->hasSessionVariable( 'RoleWasChanged' ) and
         $http->sessionVariable( 'RoleWasChanged' ) === true )
    {
        eZAudit::writeAudit( 'role-change', array( 'Role ID' => $originalRoleID, 'Role name' => $originalRoleName,
                                                   'Comment' => 'Changed the current role: kernel/role/edit.php' ) );
        $http->removeSessionVariable( 'RoleWasChanged' );
    }

    $originalRole->revertFromTemporaryVersion();
    eZContentCacheManager::clearAllContentCache();

    $Module->redirectTo( $Module->functionURI( 'view' ) . '/' . $originalRoleID . '/');

    /* Clean up policy cache */
    eZUser::cleanupCache();
}

if ( $http->hasPostVariable( 'Discard' ) )
{
    $http->removeSessionVariable( 'RoleWasChanged' );

    $role = eZRole::fetch( $roleID ) ;
    $originalRole = eZRole::fetch( $role->attribute( 'version') );
    $role->removeThis();
    if ( $originalRole != null && $originalRole->attribute( 'is_new' ) == 1 )
    {
        $originalRole->remove();
    }
    $Module->redirectTo( $Module->functionURI( 'list' ) . '/' );
}

if ( $http->hasPostVariable( 'ChangeRoleName' ) )
{
    $role->setAttribute( 'name', $http->postVariable( 'NewName' ) );
    // Set flag for audit. If true audit will be processed
    $http->setSessionVariable( 'RoleWasChanged', true );
}
if ( $http->hasPostVariable( 'AddModule' ) )
{
    if ( $http->hasPostVariable( 'Modules' ) )
        $currentModule = $http->postVariable( 'Modules' );
    else if ( $http->hasPostVariable( 'CurrentModule' ) )
        $currentModule = $http->postVariable( 'CurrentModule' );
    $policy = eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                   'FunctionName' => '*' ) );
}
if ( $http->hasPostVariable( 'AddFunction' ) )
{
    $currentModule = $http->postVariable( 'CurrentModule' );
    $currentFunction = $http->postVariable( 'ModuleFunction' );
    eZDebugSetting::writeDebug( 'kernel-role-edit', $currentModule, 'currentModule');
    $policy = eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                   'FunctionName' => $currentFunction ) );
}

if ( $http->hasPostVariable( 'AddLimitation' ) )
{
    $policy = false;

    if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
    {
        $hasNodeLimitation = false;
        $policy = eZPolicy::fetch( $http->sessionVariable( 'BrowsePolicyID' ) );
        if ( $policy )
        {
            $limitationList = eZPolicyLimitation::fetchByPolicyID( $policy->attribute( 'id' ) );
            foreach ( $limitationList as $limitation )
            {
                $limitationID = $limitation->attribute( 'id' );
                $limitationIdentifier = $limitation->attribute( 'identifier' );
                if ( $limitationIdentifier != 'Node' and $limitationIdentifier != 'Subtree' )
                    eZPolicyLimitation::removeByID( $limitationID );
                if ( $limitationIdentifier == 'Node' )
                {
                    $nodeLimitationValues = eZPolicyLimitationValue::fetchList( $limitationID );
                    if ( $nodeLimitationValues != null )
                        $hasNodeLimitation = true;
                    else
                        eZPolicyLimitation::removeByID( $limitationID );
                }

                if ( $limitationIdentifier == 'Subtree' )
                {
                    $nodeLimitationValues = eZPolicyLimitationValue::fetchList( $limitationID );
                    if ( $nodeLimitationValues == null )
                        eZPolicyLimitation::removeByID( $limitationID );
                }
            }

//             if ( !$hasNodeLimitation )
            {
                $currentModule = $http->postVariable( 'CurrentModule' );
                $currentFunction = $http->postVariable( 'CurrentFunction' );

                $mod = eZModule::exists( $currentModule );
                $functions = $mod->attribute( 'available_functions' );
                $currentFunctionLimitations = $functions[ $currentFunction ];
                foreach ( $currentFunctionLimitations as $functionLimitation )
                {
                    if ( $http->hasPostVariable( $functionLimitation['name'] ) and
                         $functionLimitation['name'] != 'Node' and
                         $functionLimitation['name'] != 'Subtree' )
                    {
                        $limitationValues = $http->postVariable( $functionLimitation['name'] );

                        if ( !in_array( '-1', $limitationValues ) )
                        {
                            $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'], $currentModule, $currentFunction );
                            foreach ( $limitationValues as $limitationValue )
                            {
                                eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                            }
                        }
                    }
                }
            }
        }
    }

    if ( !$policy )
    {
        $currentModule = $http->postVariable( 'CurrentModule' );
        $currentFunction = $http->postVariable( 'CurrentFunction' );
        $policy = eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                        'FunctionName' => $currentFunction,
                                                        'Limitation' => '' ) );

        $mod = eZModule::exists( $currentModule );
        $functions = $mod->attribute( 'available_functions' );
        $currentFunctionLimitations = $functions[ $currentFunction ];
        eZDebugSetting::writeDebug( 'kernel-role-edit', $currentFunctionLimitations, 'currentFunctionLimitations' );

        $db = eZDB::instance();
        $db->begin();
        foreach ( $currentFunctionLimitations as $functionLimitation )
        {
            if ( $http->hasPostVariable( $functionLimitation['name'] ) )
            {
                $limitationValues = $http->postVariable( $functionLimitation['name'] );
                eZDebugSetting::writeDebug( 'kernel-role-edit', $limitationValues, 'limitationValues' );

                if ( !in_array('-1', $limitationValues ) )
                {
                    $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'], $currentModule, $currentFunction );
                    foreach ( $limitationValues as $limitationValue )
                    {
                        eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                    }
                }
            }
        }
        $db->commit();
    }
}

if ( $http->hasPostVariable( 'RemovePolicy' ) )
{
    $policyID = $http->postVariable( 'RolePolicy' ) ;
    eZDebugSetting::writeDebug( 'kernel-role-edit', $policyID, 'trying to remove policy' );
    eZPolicy::removeByID( $policyID );
    // Set flag for audit. If true audit will be processed
    $http->setSessionVariable( 'RoleWasChanged', true );
}
if ( $http->hasPostVariable( 'RemovePolicies' ) and
     $http->hasPostVariable( 'DeleteIDArray' ) )
{
    $db = eZDB::instance();
    $db->begin();
    foreach( $http->postVariable( 'DeleteIDArray' ) as $deleteID)
    {
        eZDebugSetting::writeDebug( 'kernel-role-edit', $deleteID, 'trying to remove policy' );
        eZPolicy::removeByID( $deleteID );
    }
    $db->commit();
    // Set flag for audit. If true audit will be processed
    $http->setSessionVariable( 'RoleWasChanged', true );
}


if ( $http->hasPostVariable( 'CustomFunction' ) )
{
    if ( $http->hasPostVariable( 'Modules' ) )
        $currentModule = $http->postVariable( 'Modules' );
    else if ( $http->hasPostVariable( 'CurrentModule' ) )
        $currentModule = $http->postVariable( 'CurrentModule' );
    if ( $currentModule != '*' )
    {
        $mod = eZModule::exists( $currentModule );
        $functions = $mod->attribute( 'available_functions' );
        $functionNames = array_keys( $functions );
    }
    else
    {
        $functionNames = array();
    }

    $showModules = false;
    $showFunctions = true;

    if ( count( $functionNames ) < 1 )
    {
        $showModules = true;
        $showFunctions = false;
        $showLimitations = false;
        $noFunctions = true;
    }

    $tpl->setVariable( 'current_module', $currentModule );
    $tpl->setVariable( 'functions', $functionNames );
    $tpl->setVariable( 'no_functions', $noFunctions );

    $Module->setTitle( 'Edit ' . $role->attribute( 'name' ) );
    $Result = array();

    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezpI18n::translate( 'kernel/role',
                                                      'Create new policy, step 2: select function' ) ) );

    $Result['content'] = $tpl->fetch( 'design:role/createpolicystep2.tpl' );
    return;
}

if ( $http->hasPostVariable( 'DiscardFunction' ) )
{
    $showModules = true;
    $showFunctions = false;
}

if ( $http->hasPostVariable( 'SelectButton' ) or
     $http->hasPostVariable( 'BrowseCancelButton' ) or
     $http->hasPostVariable( 'Limitation' ) or
     $http->hasPostVariable( 'SelectedNodeIDArray' ) or
     $http->hasPostVariable( 'BrowseLimitationNodeButton' ) or
     $http->hasPostVariable( 'DeleteNodeButton' ) or
     $http->hasPostVariable( 'BrowseLimitationSubtreeButton' ) or
     $http->hasPostVariable( 'DeleteSubtreeButton' ) )
{
    $db = eZDB::instance();
    $db->begin();
    if ( $http->hasPostVariable( 'DeleteNodeButton' ) and $http->hasSessionVariable( 'BrowsePolicyID' ) )
    {
        if ( $http->hasPostVariable( 'DeleteNodeIDArray' ) )
        {
            $deletedIDList = $http->postVariable( 'DeleteNodeIDArray' );

            foreach ( $deletedIDList as $deletedID )
            {
                eZPolicyLimitationValue::removeByValue( $deletedID, $http->sessionVariable( 'BrowsePolicyID' ) );
            }
        }
    }

    if ( $http->hasPostVariable( 'DeleteSubtreeButton' ) and $http->hasSessionVariable( 'BrowsePolicyID' ) )
    {
        if ( $http->hasPostVariable( 'DeleteSubtreeIDArray' ) )
        {
            $deletedIDList = $http->postVariable( 'DeleteSubtreeIDArray' );

            foreach ( $deletedIDList as $deletedID )
            {
                $subtree = eZContentObjectTreeNode::fetch( $deletedID , false, false);
                $path = $subtree['path_string'];
                eZPolicyLimitationValue::removeByValue( $path, $http->sessionVariable( 'BrowsePolicyID' ) );
            }
        }
    }

    if ( $http->hasPostVariable( 'Limitation' ) and $http->hasSessionVariable( 'BrowsePolicyID' ) )
        $http->removeSessionVariable( 'BrowsePolicyID' );

    if ( $http->hasSessionVariable( 'BrowseCurrentModule' ) )
        $currentModule = $http->sessionVariable( 'BrowseCurrentModule' );

    if ( $http->hasPostVariable( 'CurrentModule' ) )
        $currentModule = $http->postVariable( 'CurrentModule' );

    $mod = eZModule::exists( $currentModule );
    $functions = $mod->attribute( 'available_functions' );
    $functionNames = array_keys( $functions );

    $showModules = false;
    $showFunctions = false;
    $showLimitations = true;
    $nodeList = array();
    $nodeIDList = array();
    $subtreeList = array();
    $subtreeIDList = array();

    // Check for temporary node and subtree policy limitation
    if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
    {
        $policyID = $http->sessionVariable( 'BrowsePolicyID' );
        // Fetch node limitations
        $nodeLimitation = eZPolicyLimitation::fetchByIdentifier( $policyID, 'Node' );
        if ( $nodeLimitation != null )
        {
            $nodeLimitationID = $nodeLimitation->attribute('id');
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
        $subtreeLimitation = eZPolicyLimitation::fetchByIdentifier( $policyID, 'Subtree' );
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
                    $subtreeIDList[] = $subtreeID;
                    $subtree = eZContentObjectTreeNode::fetch( $subtreeID );
                    $subtreeList[] = $subtree;
                }
            }
        }
    }

    if ( $http->hasSessionVariable( 'BrowseCurrentFunction' ) )
        $currentFunction = $http->sessionVariable( 'BrowseCurrentFunction' );

    if ( $http->hasPostVariable( 'CurrentFunction' ) )
        $currentFunction = $http->postVariable( 'CurrentFunction' );

    if ( $http->hasPostVariable( 'ModuleFunction' ) )
        $currentFunction = $http->postVariable( 'ModuleFunction' );

    $currentFunctionLimitations = array();
    foreach( $functions[ $currentFunction ] as $key => $limitation )
    {
        if( count( $limitation[ 'values' ] == 0 ) && array_key_exists( 'class', $limitation ) )
        {
            $basePath = 'kernel/'; //set default basepath for limitationValueClasses
            if( array_key_exists( 'extension', $limitation ) && $limitation['extension'] )
            {
                $basePath = 'extension/' . $limitation['extension'] . '/';
            }
            include_once( $basePath . $limitation['path'] . $limitation['file']  );
            $obj = new $limitation['class']( array() );
            $limitationValueList = call_user_func_array ( array( $obj , $limitation['function']) , $limitation['parameter'] );
            $limitationValueArray =  array();
            foreach( $limitationValueList as $limitationValue )
            {
                $limitationValuePair = array();
                $limitationValuePair['Name'] = $limitationValue[ 'name' ];
                $limitationValuePair['value'] = $limitationValue[ 'id' ];
                $limitationValueArray[] = $limitationValuePair;
            }
            $limitation[ 'values' ] = $limitationValueArray;
        }
        $currentFunctionLimitations[ $key ] = $limitation;
    }

    if ( count( $currentFunctionLimitations ) < 1 )
    {
        $showModules = false;
        $showFunctions = true;
        $showLimitations = false;
        $noLimitations = true;
    }


    if ( $http->hasPostVariable( 'BrowseLimitationSubtreeButton' ) ||
         $http->hasPostVariable( 'BrowseLimitationNodeButton' ) )
    {
        // Store other limitations
        if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
        {
            $policy = eZPolicy::fetch( $http->sessionVariable( 'BrowsePolicyID' ) );
            $limitationList = eZPolicyLimitation::fetchByPolicyID( $policy->attribute( 'id' ) );
            foreach ( $limitationList as $limitation )
            {
                $limitationID = $limitation->attribute( 'id' );
                $limitationIdentifier = $limitation->attribute( 'identifier' );
                if ( $limitationIdentifier != 'Node' and $limitationIdentifier != 'Subtree' )
                    eZPolicyLimitation::removeByID( $limitationID );
            }

            foreach ( $currentFunctionLimitations as $functionLimitation )
            {
                if ( $http->hasPostVariable( $functionLimitation['name'] ) and
                     $functionLimitation['name'] != 'Node' and
                     $functionLimitation['name'] != 'Subtree' )
                {
                    $limitationValues = $http->postVariable( $functionLimitation['name'] );
                    eZDebugSetting::writeDebug( 'kernel-role-edit', $limitationValues, 'limitationValues');

                    if ( !in_array('-1', $limitationValues ) )
                    {
                        $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'], $currentModule, $currentFunction );
                        foreach ( $limitationValues as $limitationValue )
                        {
                            eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                        }
                    }
                }
            }
        }
        else
        {
            $policy = eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                            'FunctionName' => $currentFunction,
                                                            'Limitation' => '') );

            $http->setSessionVariable( 'BrowsePolicyID', $policy->attribute('id') );
            foreach ( $currentFunctionLimitations as $functionLimitation )
            {
                if ( $http->hasPostVariable( $functionLimitation['name'] ))
                {
                    $limitationValues = $http->postVariable( $functionLimitation['name'] );
                    eZDebugSetting::writeDebug( 'kernel-role-edit', $limitationValues, 'limitationValues');

                    if ( !in_array( '-1', $limitationValues ) )
                    {
                        $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'], $currentModule, $currentFunction);
                        eZDebugSetting::writeDebug( 'kernel-role-edit', $policyLimitation, 'policyLimitationCreated' );
                        foreach ( $limitationValues as $limitationValue )
                        {
                            eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                        }
                    }
                }
            }
        }
        $db->commit();

        $http->setSessionVariable( 'BrowseCurrentModule', $currentModule );
        $http->setSessionVariable( 'BrowseCurrentFunction', $currentFunction );
        if ( $http->hasPostVariable( 'BrowseLimitationSubtreeButton' ) )
        {

            eZContentBrowse::browse( array( 'action_name' => 'FindLimitationSubtree',
                                            'from_page' => '/role/edit/' . $roleID . '/' ),
                                     $Module );
        }
        elseif ( $http->hasPostVariable( 'BrowseLimitationNodeButton' ) )
        {
            eZContentBrowse::browse( array( 'action_name' => 'FindLimitationNode',
                                            'from_page' => '/role/edit/' . $roleID . '/' ),
                                     $Module );

        }
        return;
    }

    if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) and
         $http->postVariable( 'BrowseActionName' ) == 'FindLimitationNode' and
         !$http->hasPostVariable( 'BrowseCancelButton' ) )
    {
        $selectedNodeIDList = $http->postVariable( 'SelectedNodeIDArray' );

        if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
        {
            $policy = eZPolicy::fetch( $http->sessionVariable( 'BrowsePolicyID' ) );
            $limitationList = eZPolicyLimitation::fetchByPolicyID( $policy->attribute( 'id' ) );

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
        }
        else
        {
            $policy = eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                           'FunctionName' => $currentFunction,
                                                           'Limitation' => '') );
            $http->setSessionVariable( 'BrowsePolicyID', $policy->attribute('id') );
        }

        $nodeLimitation = eZPolicyLimitation::fetchByIdentifier( $policy->attribute('id'), 'Node' );
        if ( $nodeLimitation == null )
            $nodeLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), 'Node', $currentModule, $currentFunction);

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

    if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) and
         $http->postVariable( 'BrowseActionName' ) == 'FindLimitationSubtree' and
         !$http->hasPostVariable( 'BrowseCancelButton' ) )
    {
        $selectedSubtreeIDList = $http->postVariable( 'SelectedNodeIDArray' );
        if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
        {
            $policy = eZPolicy::fetch( $http->sessionVariable( 'BrowsePolicyID' ) );
        }
        else
        {
            $policy = eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                            'FunctionName' => $currentFunction,
                                                            'Limitation' => '') );
            $http->setSessionVariable( 'BrowsePolicyID', $policy->attribute('id') );
        }

        $subtreeLimitation = eZPolicyLimitation::fetchByIdentifier( $policy->attribute('id'), 'Subtree' );
        if ( $subtreeLimitation == null )
            $subtreeLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), 'Subtree', $currentModule, $currentFunction);

        foreach ( $selectedSubtreeIDList as $nodeID )
        {
            if ( !in_array( $nodeID, $subtreeIDList ) )
            {
                $subtree = eZContentObjectTreeNode::fetch( $nodeID );
                $pathString = $subtree->attribute( 'path_string' );
                $policyLimitationValue = eZPolicyLimitationValue::createNew( $subtreeLimitation->attribute( 'id' ),  $pathString );
                $subtreeList[] = $subtree;
            }
        }
    }

    if ( $http->hasPostVariable( 'Limitation' ) && count( $currentFunctionLimitations ) == 0 )
    {
        $currentModule = $http->postVariable( 'CurrentModule' );
        $currentFunction = $http->postVariable( 'ModuleFunction' );
        eZDebugSetting::writeDebug( 'kernel-role-edit', $currentModule, 'currentModule' );
        $policy = eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                       'FunctionName' => $currentFunction ) );
    }
    else
    {
        $db->commit();

        $currentLimitationList = array();
        foreach ( $currentFunctionLimitations as $currentFunctionLimitation )
        {
            $limitationName = $currentFunctionLimitation['name'];
            $currentLimitationList[$limitationName] = '-1';
        }
        if ( isset( $policyID ) )
        {
            $limitationList = eZPolicyLimitation::fetchByPolicyID( $policyID );
            foreach ( $limitationList as $limitation )
            {
                $limitationID = $limitation->attribute( 'id' );
                $limitationIdentifier = $limitation->attribute( 'identifier' );
                $limitationValues = eZPolicyLimitationValue::fetchList( $limitationID );
                $valueList = array();
                foreach ( $limitationValues as $limitationValue )
                {
                    $value = $limitationValue->attribute( 'value' );
                    $valueList[] = $value;
                }
                $currentLimitationList[$limitationIdentifier] = $valueList;
            }
        }


        $tpl->setVariable( 'current_function', $currentFunction );
        $tpl->setVariable( 'function_limitations', $currentFunctionLimitations );
        $tpl->setVariable( 'no_limitations', $noLimitations );

        $tpl->setVariable( 'current_module', $currentModule );
        $tpl->setVariable( 'functions', $functionNames );
        $tpl->setVariable( 'node_list', $nodeList );
        $tpl->setVariable( 'subtree_list', $subtreeList );
        $tpl->setVariable( 'current_limitation_list', $currentLimitationList );

        $Result = array();
        $Result['path'] = array( array( 'url' => false ,
                                        'text' => ezpI18n::translate( 'kernel/role',
                                                          'Create new policy, step three: set function limitations' ) ) );

        $Result['content'] = $tpl->fetch( 'design:role/createpolicystep3.tpl' );
        return;
    }
    $db->commit();
}

if ( $http->hasPostVariable( 'DiscardLimitation' )  || $http->hasPostVariable( 'Step2')  )
{
    $currentModule = $http->postVariable( 'CurrentModule' );
    $mod = eZModule::exists( $currentModule );
    $functions = $mod->attribute( 'available_functions' );
    $functionNames = array_keys( $functions );

    $showModules = false;
    $showFunctions = true;
    $tpl->setVariable( 'current_module', $currentModule );
    $tpl->setVariable( 'functions', $functionNames );
    $tpl->setVariable( 'no_functions', false );

    $Result = array();
    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezpI18n::translate( 'kernel/role',
                                                      'Create new policy, step two: select function' ) ) );

    $Result['content'] = $tpl->fetch( 'design:role/createpolicystep2.tpl' );
    return;
}

if ( $http->hasPostVariable( 'CreatePolicy' ) || $http->hasPostVariable( 'Step1' ) )
{
    // Set flag for audit. If true audit will be processed
    $http->setSessionVariable( 'RoleWasChanged', true );
    $Module->setTitle( 'Edit ' . $role->attribute( 'name' ) );
    $tpl->setVariable( 'modules', $modules );

    $moduleList = array();
    foreach( $modules as $module )
    {
        $moduleList[] = eZModule::exists( $module );
    }
    $tpl->setVariable( 'module_list', $moduleList );
    $tpl->setVariable( 'role', $role );
    $tpl->setVariable( 'module', $Module );

    $Result = array();
    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezpI18n::translate( 'kernel/role',
                                                      'Create new policy, step one: select module' ) ) );

    $Result['content'] = $tpl->fetch( 'design:role/createpolicystep1.tpl' );
    return;
}

// Set flag for audit. If true audit will be processed
// Cancel button was pressed
if ( $http->hasPostVariable( 'CancelPolicyButton' ) )
    $http->setSessionVariable( 'RoleWasChanged', false );

$policies = $role->attribute( 'policies' );
$tpl->setVariable( 'no_functions', $noFunctions );
$tpl->setVariable( 'no_limitations', $noLimitations );

$tpl->setVariable( 'show_modules', $showModules );
$tpl->setVariable( 'show_limitations', $showLimitations );
$tpl->setVariable( 'show_functions', $showFunctions );

$tpl->setVariable( 'policies', $policies );
$tpl->setVariable( 'modules', $modules );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'role', $role );

$tpl->setVariable( 'step', 0 );

$Module->setTitle( 'Edit ' . $role->attribute( 'name' ) );

$Result = array();
$Result['path'] = array( array( 'text' => 'Role',
                                'url' => 'role/list' ),
                         array( 'text' => $role->attribute( 'name' ),
                                'url' => false ) );

$Result['content'] = $tpl->fetch( 'design:role/edit.tpl' );

?>
