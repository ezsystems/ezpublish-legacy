<?php
//
// Created on: <19-Aug-2002 16:38:41 sp>
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

/*! \file edit.php
*/

include_once( 'kernel/classes/ezmodulemanager.php' );
include_once( 'kernel/classes/ezrole.php' );
include_once( 'kernel/classes/ezpolicy.php' );

include_once( 'kernel/classes/ezsearch.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );
include_once( 'lib/ezutils/classes/ezmodule.php' );

include_once( 'kernel/common/template.php' );

$tpl =& templateInit();
$Module =& $Params['Module'];
$roleID =& $Params['RoleID'];

$modules = eZModuleManager::availableModules();
sort( $modules );

$role = eZRole::fetch( 0, $roleID );
if ( is_null( $role ) )
{
    $role = eZRole::fetch( $roleID );
    if ( $role->attribute( 'version' ) == '0' )
    {
        $temporaryRole = $role->createTemporaryVersion();
        unset( $role );
        $role = $temporaryRole;
    }
}

$http =& eZHTTPTool::instance();

$tpl->setVariable( 'module', $Module );

$role->turnOffCaching();

$tpl->setVariable( 'role', $role );
$Module->setTitle( 'Edit ' . $role->attribute( 'name' ) );

if ($http->hasPostVariable( 'NewName' ) && $role->attribute( 'name' ) != $http->postVariable( 'NewName' )  )
{
    $role->setAttribute( 'name' , $http->postVariable( 'NewName' ) );
    $role->store();
}

$showModules = true;
$showFunctions = false;
$showLimitations = false;
$noFunctions = false;
$noLimitations = false;

if ( $http->hasPostVariable( 'Apply' )  )
{
    $originalRole = eZRole::fetch( $role->attribute( 'version' ) );
    $originalRole->revertFromTemporaryVersion();
    include_once( 'kernel/classes/ezcontentobject.php' );
    eZContentObject::expireAllCache();
    $Module->redirectTo( $Module->functionURI( 'view' ) . '/' . $originalRole->attribute( 'id' ) . '/');
}

if ( $http->hasPostVariable( 'Discard' )  )
{
    $originalRole = eZRole::fetch( $roleID ) ;
    $originalRole->remove();
    $Module->redirectTo( $Module->functionURI( 'list' ) . '/' );

}

if ( $http->hasPostVariable( 'ChangeRoleName' )  )
{
    $role->setAttribute( 'name', $http->postVariable( 'NewName' ) );
}
if ( $http->hasPostVariable( 'AddModule' )  )
{
    $currentModule = $http->postVariable( 'Modules' );
    $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                    'FunctionName' => '*' ) );
}
if ( $http->hasPostVariable( 'AddFunction' ) )
{
    $currentModule = $http->postVariable( 'CurrentModule' );
    $currentFunction = $http->postVariable( 'ModuleFunction' );
    eZDebug::writeNotice( $currentModule, 'currentModule');
    $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                    'FunctionName' => $currentFunction ) );

}

if ( $http->hasPostVariable( 'AddLimitation' ) )
{
    $policy = false;

    if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
    {
        $hasNodeLimitation = false;
        $policy =& eZPolicy::fetch( $http->sessionVariable( 'BrowsePolicyID' ) );
        if ( $policy )
        {
            $limitationList =& eZPolicyLimitation::fetchByPolicyID( $policy->attribute( 'id' ) );
            foreach ( $limitationList as $limitation )
            {
                $limitationID = $limitation->attribute( 'id' );
                $limitationIdentifier = $limitation->attribute( 'identifier' );
                if ( $limitationIdentifier != 'Node' and $limitationIdentifier != 'Subtree' )
                    eZPolicyLimitation::remove( $limitationID );
                if ( $limitationIdentifier == 'Node' )
                {
                    $nodeLimitationValues =& eZPolicyLimitationValue::fetchList( $limitationID );
                    if ( $nodeLimitationValues != null )
                        $hasNodeLimitation = true;
                    else
                        eZPolicyLimitation::remove( $limitationID );
                }

                if ( $limitationIdentifier == 'Subtree' )
                {
                    $nodeLimitationValues =& eZPolicyLimitationValue::fetchList( $limitationID );
                    if ( $nodeLimitationValues == null )
                        eZPolicyLimitation::remove( $limitationID );
                }
            }

//             if ( !$hasNodeLimitation )
            {
                $currentModule = $http->postVariable( 'CurrentModule' );
                $currentFunction = $http->postVariable( 'CurrentFunction' );

                $mod = & eZModule::exists( $currentModule );
                $functions =& $mod->attribute( 'available_functions' );
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
        $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                        'FunctionName' => $currentFunction,
                                                        'Limitation' => '') );

        $mod = & eZModule::exists( $currentModule );
        $functions =& $mod->attribute( 'available_functions' );
        $currentFunctionLimitations = $functions[ $currentFunction ];
        eZDebug::writeNotice($currentFunctionLimitations, 'currentFunctionLimitations');
        foreach ( $currentFunctionLimitations as $functionLimitation )
        {
            if ( $http->hasPostVariable( $functionLimitation['name'] ))
            {
                $limitationValues = $http->postVariable( $functionLimitation['name'] );
                eZDebug::writeNotice( $limitationValues, 'limitationValues');

                if ( !in_array('-1', $limitationValues ) )
                {
                    $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'], $currentModule, $currentFunction);
                    foreach ( $limitationValues as $limitationValue )
                    {
                        eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                    }
                }
            }
        }
    }
}

if ( $http->hasPostVariable( 'RemovePolicy' ) )
{
    $policyID = $http->postVariable( 'RolePolicy' ) ;
    eZDebug::writeNotice( $policyID, 'trying to remove policy' );
    eZPolicy::remove( $policyID );

}
if ( $http->hasPostVariable( 'RemovePolicies' )  )
{
    foreach( $http->postVariable( 'DeleteIDArray' ) as $deleteID)
    {
        eZDebug::writeNotice( $deleteID, 'trying to remove policy' );
        eZPolicy::remove( $deleteID );
    }
}


if ( $http->hasPostVariable( 'CustomFunction' ) )
{
    $currentModule = $http->postVariable( 'Modules' );
    if ( $currentModule != '*' )
    {
        $mod = & eZModule::exists( $currentModule );
//    var_dump( $currentModule );
        flush();
        $functions =& $mod->attribute( 'available_functions' );
        $functionNames = array_keys( $functions );
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

//    eZDebug::writeNotice( $functions, 'Functions' );
    $tpl->setVariable( 'current_module', $currentModule );
    $tpl->setVariable( 'functions', $functionNames );
    $tpl->setVariable( 'no_functions', $noFunctions );

    $Module->setTitle( 'Edit ' . $role->attribute( 'name' ) );
    $Result = array();

    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezi18n( 'kernel/role',
                                                      'Create policy - step 2 - Specify function' ) ) );

    $Result['content'] =& $tpl->fetch( 'design:role/createpolicystep2.tpl' );
    return;


}

if ( $http->hasPostVariable( 'DiscardFunction' ) )
{
    $showModules = true;
    $showFunctions = false;

}

if ( $http->hasPostVariable( 'Limitation' ) or
     $http->hasPostVariable( 'SelectedNodeIDArray' ) or
     $http->hasPostVariable( 'BrowseLimitationNodeButton' ) or
     $http->hasPostVariable( 'DeleteNodeButton' ) or
     $http->hasPostVariable( 'BrowseLimitationSubtreeButton' ) or
     $http->hasPostVariable( 'DeleteSubtreeButton' ) )
{
    if ( $http->hasPostVariable( 'DeleteNodeButton' ) )
    {
        if ( $http->hasPostVariable( 'DeleteNodeIDArray' ) )
        {
            $deletedIDList = $http->postVariable( 'DeleteNodeIDArray' );

            foreach ( $deletedIDList as $deletedID )
            {
                eZPolicyLimitationValue::removeByValue( $deletedID );
            }
        }
    }

    if ( $http->hasPostVariable( 'DeleteSubtreeButton' ) )
    {
        if ( $http->hasPostVariable( 'DeleteSubtreeIDArray' ) )
        {
            $deletedIDList = $http->postVariable( 'DeleteSubtreeIDArray' );

            foreach ( $deletedIDList as $deletedID )
            {
                $subtree =& eZContentObjectTreeNode::fetch( $deletedID );
                $path = $subtree->attribute( 'path_string' );
                eZPolicyLimitationValue::removeByValue( $path );
            }
        }
    }

    if (  $http->hasPostVariable( 'Limitation' ) and $http->hasSessionVariable( 'BrowsePolicyID' ) )
        $http->removeSessionVariable( 'BrowsePolicyID' );

    if ( $http->hasSessionVariable( 'BrowseCurrentModule' ) )
        $currentModule = $http->sessionVariable( 'BrowseCurrentModule' );

    if ( $http->hasPostVariable( 'CurrentModule' ) )
        $currentModule = $http->postVariable( 'CurrentModule' );

    $mod = & eZModule::exists( $currentModule );
    $functions =& $mod->attribute( 'available_functions' );
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
        $nodeLimitation =& eZPolicyLimitation::fetchByIdentifier( $policyID, 'Node' );
        if ( $nodeLimitation != null )
        {
            $nodeLimitationID = $nodeLimitation->attribute('id');
            $nodeLimitationValues =& eZPolicyLimitationValue::fetchList( $nodeLimitationID );
            foreach ( $nodeLimitationValues as $nodeLimitationValue )
            {
                $nodeID = $nodeLimitationValue->attribute( 'value' );
                $nodeIDList[] = $nodeID;
                $node =& eZContentObjectTreeNode::fetch( $nodeID );
                $nodeList[] = $node;
            }
        }

        // Fetch subtree limitations
        $subtreeLimitation =& eZPolicyLimitation::fetchByIdentifier( $policyID, 'Subtree' );
        if ( $subtreeLimitation != null )
        {
            $subtreeLimitationID = $subtreeLimitation->attribute('id');
            $subtreeLimitationValues =& eZPolicyLimitationValue::fetchList( $subtreeLimitationID );

            foreach ( $subtreeLimitationValues as $subtreeLimitationValue )
            {
                $subtreePath = $subtreeLimitationValue->attribute( 'value' );
                $subtreeObject =& eZContentObjectTreeNode::fetchByPath( $subtreePath );
                if ( $subtreeObject )
                {
                    $subtreeID = $subtreeObject->attribute( 'node_id' );
                    $subtreeIDList[] = $subtreeID;
                    $subtree =& eZContentObjectTreeNode::fetch( $subtreeID );
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
    foreach( $functions[ $currentFunction ] as $limitation )
    {
        if( count( $limitation[ 'values' ] == 0 ) && array_key_exists( 'class', $limitation ) )
        {
            include_once( 'kernel/' . $limitation['path'] . $limitation['file']  );
            $obj = new $limitation['class']( array() );
            $limitationValueList = call_user_func_array ( array( &$obj , $limitation['function']) , $limitation['parameter'] );
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
        $currentFunctionLimitations[] = $limitation;
    }

    if ( count( $currentFunctionLimitations ) < 1 )
    {
        $showModules = false;
        $showFunctions = true;
        $showLimitations = false;
        $noLimitations = true;
    }

    $currentLimitationList = array();
    foreach ( $currentFunctionLimitations as $currentFunctionLimitation )
    {
        $limitationName = $currentFunctionLimitation['name'];
        $currentLimitationList[$limitationName] = '-1';
    }

    $limitationList =& eZPolicyLimitation::fetchByPolicyID( $policyID );
    foreach ( $limitationList as $limitation )
    {
        $limitationID = $limitation->attribute( 'id' );
        $limitationIdentifier = $limitation->attribute( 'identifier' );
        $limitationValues =& eZPolicyLimitationValue::fetchList( $limitationID );
        $valueList = array();
        foreach ( $limitationValues as $limitationValue )
        {
            $value = $limitationValue->attribute( 'value' );
            $valueList[] = $value;
        }
        $currentLimitationList[$limitationIdentifier] = $valueList;
    }

    if ( $http->hasPostVariable( 'BrowseLimitationNodeButton' ) )
    {
/*        $http->setSessionVariable( 'BrowseFromPage', '/role/edit/' . $roleID );
        $http->setSessionVariable( 'BrowseActionName', 'FindLimitationNode' );
        $http->setSessionVariable( 'BrowseReturnType', 'NodeID' );
        $http->setSessionVariable( 'BrowseSelectionType', 'Multiple' );
        $nodeID = 2;
        $Module->redirectTo( '/content/browse/' . $nodeID );
*/
        $http->setSessionVariable( 'BrowseCurrentModule', $currentModule );
        $http->setSessionVariable( 'BrowseCurrentFunction', $currentFunction );

        include_once( 'kernel/classes/ezcontentbrowse.php' );
        eZContentBrowse::browse( array( 'action_name' => 'FindLimitationNode',
                                        'from_page' => '/role/edit/' . $roleID . '/' ),
                                 $Module );

        return;
    }

    if ( $http->hasPostVariable( 'BrowseLimitationSubtreeButton' ) )
    {
        // Store other limitations
        if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
        {
            $policy =& eZPolicy::fetch( $http->sessionVariable( 'BrowsePolicyID' ) );
            $limitationList =& eZPolicyLimitation::fetchByPolicyID( $policy->attribute( 'id' ) );
            foreach ( $limitationList as $limitation )
            {
                $limitationID = $limitation->attribute( 'id' );
                $limitationIdentifier = $limitation->attribute( 'identifier' );
                if ( $limitationIdentifier != 'Node' and $limitationIdentifier != 'Subtree' )
                    eZPolicyLimitation::remove( $limitationID );
            }

            foreach ( $currentFunctionLimitations as $functionLimitation )
            {
                if ( $http->hasPostVariable( $functionLimitation['name'] ) and
                     $functionLimitation['name'] != 'Node' and
                     $functionLimitation['name'] != 'Subtree' )
                {
                    $limitationValues = $http->postVariable( $functionLimitation['name'] );
                    eZDebug::writeNotice( $limitationValues, 'limitationValues');

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
            $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                            'FunctionName' => $currentFunction,
                                                            'Limitation' => '') );

            $http->setSessionVariable( 'BrowsePolicyID', $policy->attribute('id') );
            foreach ( $currentFunctionLimitations as $functionLimitation )
            {
                if ( $http->hasPostVariable( $functionLimitation['name'] ))
                {
                    $limitationValues = $http->postVariable( $functionLimitation['name'] );
                    eZDebug::writeNotice( $limitationValues, 'limitationValues');

                    if ( !in_array( '-1', $limitationValues ) )
                    {
                        $policyLimitation = eZPolicyLimitation::createNew( $policy->attribute('id'), $functionLimitation['name'], $currentModule, $currentFunction);
                        foreach ( $limitationValues as $limitationValue )
                        {
                            eZPolicyLimitationValue::createNew( $policyLimitation->attribute( 'id' ), $limitationValue );
                        }
                    }
                }
            }
        }
/*        $http->setSessionVariable( 'BrowseFromPage', '/role/edit/' . $roleID );
        $http->setSessionVariable( 'BrowseActionName', 'FindLimitationSubtree' );
        $http->setSessionVariable( 'BrowseReturnType', 'NodeID' );
        $http->setSessionVariable( 'BrowseSelectionType', 'Multiple' );
        $nodeID = 2;
        $Module->redirectTo( '/content/browse/' . $nodeID );
*/
        $http->setSessionVariable( 'BrowseCurrentModule', $currentModule );
        $http->setSessionVariable( 'BrowseCurrentFunction', $currentFunction );

        include_once( 'kernel/classes/ezcontentbrowse.php' );
        eZContentBrowse::browse( array( 'action_name' => 'FindLimitationSubtree',
                                        'from_page' => '/role/edit/' . $roleID . '/' ),
                                 $Module );
        return;
    }

    if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) and $http->postVariable( 'BrowseActionName' ) == 'FindLimitationNode' )
    {
        $selectedNodeIDList = $http->postVariable( 'SelectedNodeIDArray' );

        if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
        {
            $policy =& eZPolicy::fetch( $http->sessionVariable( 'BrowsePolicyID' ) );
            $limitationList =& eZPolicyLimitation::fetchByPolicyID( $policy->attribute( 'id' ) );
            // Remove other limitations. When the policy is applied to node, no other constraints needed.
            foreach ( $limitationList as $limitation )
            {
                $limitationID = $limitation->attribute( 'id' );
                $limitationIdentifier = $limitation->attribute( 'identifier' );
                if ( $limitationIdentifier != 'Node' and $limitationIdentifier != 'Subtree' )
                    eZPolicyLimitation::remove( $limitationID );
            }
        }
        else
        {
            $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
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
                $nodeLimitationValue =& eZPolicyLimitationValue::createNew( $nodeLimitation->attribute( 'id' ),  $nodeID );
                $node =& eZContentObjectTreeNode::fetch( $nodeID );
                $nodeList[] = $node;
            }
        }
    }

    if ( $http->hasPostVariable( 'SelectedNodeIDArray' ) and $http->postVariable( 'BrowseActionName' ) == 'FindLimitationSubtree' )
    {
        $selectedSubtreeIDList = $http->postVariable( 'SelectedNodeIDArray' );
        if ( $http->hasSessionVariable( 'BrowsePolicyID' ) )
        {
            $policy =& eZPolicy::fetch( $http->sessionVariable( 'BrowsePolicyID' ) );
        }
        else
        {
            $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
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
                $subtree =& eZContentObjectTreeNode::fetch( $nodeID );
                $pathString = $subtree->attribute( 'path_string' );
                $policyLimitationValue =& eZPolicyLimitationValue::createNew( $subtreeLimitation->attribute( 'id' ),  $pathString );
                $subtreeList[] = $subtree;
            }
        }
    }

    if ( $http->hasPostVariable( 'Limitation' ) && count( $currentFunctionLimitations ) == 0 )
    {
        $currentModule = $http->postVariable( 'CurrentModule' );
        $currentFunction = $http->postVariable( 'ModuleFunction' );
        eZDebug::writeNotice( $currentModule, 'currentModule');
        $policy =& eZPolicy::createNew( $roleID, array( 'ModuleName'=> $currentModule,
                                                        'FunctionName' => $currentFunction ) );
    }
    else
    {
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
                                        'text' => ezi18n( 'kernel/role',
                                                          'Create policy - step 3 - Specify limitations' ) ) );

        $Result['content'] =& $tpl->fetch( 'design:role/createpolicystep3.tpl' );
        return;
    }
}

if ( $http->hasPostVariable( 'DiscardLimitation' )  || $http->hasPostVariable( 'Step2')  )
{
    $currentModule = $http->postVariable( 'CurrentModule' );
    $mod = & eZModule::exists( $currentModule );
    $functions =& $mod->attribute( 'available_functions' );
    $functionNames = array_keys( $functions );

    $showModules = false;
    $showFunctions = true;
//    eZDebug::writeNotice( $functions, 'Functions' );
    $tpl->setVariable( 'current_module', $currentModule );
    $tpl->setVariable( 'functions', $functionNames );
    $tpl->setVariable( 'no_functions', false );

    $Result = array();
    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezi18n( 'kernel/role',
                                                      'Create policy - step 2 - Specify function' ) ) );

    $Result['content'] =& $tpl->fetch( 'design:role/createpolicystep2.tpl' );
    return;

}

if ( $http->hasPostVariable( 'CreatePolicy' ) || $http->hasPostVariable( 'Step1') )
{
    $Module->setTitle( 'Edit ' . $role->attribute( 'name' ) );
    $tpl->setVariable( 'modules', $modules );
    $tpl->setVariable( 'role', $role );
    $tpl->setVariable( 'module', $Module );

    $Result = array();
    $Result['path'] = array( array( 'url' => false ,
                                    'text' => ezi18n( 'kernel/role',
                                                      'Create policy - step 1 - Specify module' ) ) );

    $Result['content'] =& $tpl->fetch( 'design:role/createpolicystep1.tpl' );
    return;

}


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

$Result['content'] =& $tpl->fetch( 'design:role/edit.tpl' );

?>
