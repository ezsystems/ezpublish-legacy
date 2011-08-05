<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$roleID = $Params['RoleID'];

$role = eZRole::fetch( $roleID );

if ( !$role )
{
    $Module->redirectTo( '/role/list/' );
    return;
}

// Redirect to role edit
if ( $http->hasPostVariable( 'EditRoleButton' ) )
{
    $Module->redirectTo( '/role/edit/' . $roleID );
    return;
}

// Redirect to content node browse in the user tree
if ( $http->hasPostVariable( 'AssignRoleButton' ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'AssignRole',
                                    'from_page' => '/role/assign/' . $roleID,
                                    'cancel_page' => '/role/view/'. $roleID ),
                             $Module );

    return;
}
else if ( $http->hasPostVariable( 'AssignRoleLimitedButton' ) )
{
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $http->postVariable( 'AssignRoleType' ) );
    return;
}

// Assign the role for a user or group
if ( $Module->isCurrentAction( 'AssignRole' ) )
{
    $selectedObjectIDArray = eZContentBrowse::result( 'AssignRole' );

    $assignedUserIDArray = $role->fetchUserID();

    $db = eZDB::instance();
    $db->begin();
    foreach ( $selectedObjectIDArray as $objectID )
    {
        if ( !in_array(  $objectID, $assignedUserIDArray ) )
        {
            $role->assignToUser( $objectID );
        }
    }
    /* Clean up policy cache */
    eZUser::cleanupCache();

    // Clear role caches.
    eZRole::expireCache();

    // Clear all content cache.
    eZContentCacheManager::clearAllContentCache();

    $db->commit();
}

// Remove the role assignment
if ( $http->hasPostVariable( 'RemoveRoleAssignmentButton' ) )
{
    $idArray = $http->postVariable( "IDArray" );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $idArray as $id )
    {
        $role->removeUserAssignmentByID( $id );
    }
    /* Clean up policy cache */
    eZUser::cleanupCache();

    // Clear role caches.
    eZRole::expireCache();

    // Clear all content cache.
    eZContentCacheManager::clearAllContentCache();

    $db->commit();
}

$tpl = eZTemplate::factory();

$userArray = $role->fetchUserByRole();

$policies = $role->attribute( 'policies' );
$tpl->setVariable( 'policies', $policies );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'role', $role );

$tpl->setVariable( 'user_array', $userArray );

$Module->setTitle( 'View role - ' . $role->attribute( 'name' ) );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:role/view.tpl' );
$Result['path'] = array( array( 'text' => 'Role',
                                'url' => 'role/list' ),
                         array( 'text' => $role->attribute( 'name' ),
                                'url' => false ) );

?>
