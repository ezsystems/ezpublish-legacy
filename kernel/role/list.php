<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$http = eZHTTPTool::instance();


$Module = $Params['Module'];

$offset = $Params['Offset'];

if( eZPreferences::value( 'admin_role_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_role_list_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
   if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        $db = eZDB::instance();
        $db->begin();
        foreach ( $deleteIDArray as $deleteID )
        {
            eZRole::removeRole( $deleteID );
        }
        // Clear role caches.
        eZRole::expireCache();

        // Clear all content cache.
        eZContentCacheManager::clearAllContentCache();

        $db->commit();
    }
}
// Redirect to content node browse in the user tree
// Assign the role for a user or group
if ( $Module->isCurrentAction( 'AssignRole' ) )
{
    $selectedObjectIDArray = eZContentBrowse::result( 'AssignRole' );

    foreach ( $selectedObjectIDArray as $objectID )
    {
        $role->assignToUser( $objectID );
    }
    // Clear role caches.
    eZRole::expireCache();

    // Clear all content cache.
    eZContentCacheManager::clearAllContentCache();
}

if ( $http->hasPostVariable( 'NewButton' )  )
{
    $role = eZRole::createNew( );
    return $Module->redirectToView( 'edit', array( $role->attribute( 'id' ) ) );
}

$viewParameters = array( 'offset' => $offset );
$tpl = eZTemplate::factory();

$roles = eZRole::fetchByOffset( $offset, $limit, $asObject = true, $ignoreTemp = true );
$roleCount = eZRole::roleCount();
$tempRoles = eZRole::fetchList( $temporaryVersions = true );
$tpl->setVariable( 'roles', $roles );
$tpl->setVariable( 'role_count', $roleCount );
$tpl->setVariable( 'temp_roles', $tempRoles );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'limit', $limit );


$Result = array();
$Result['content'] = $tpl->fetch( 'design:role/list.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/role', 'Role list' ) ) );
?>
