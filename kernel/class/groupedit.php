<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];

if ( is_numeric( $GroupID ) )
{
    $classgroup = eZContentClassGroup::fetch( $GroupID );
}
else
{
    $user = eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $classgroup = eZContentClassGroup::create( $user_id );
    $classgroup->setAttribute( "name", ezpI18n::tr( 'kernel/class/groupedit', "New Group" ) );
    $classgroup->store();
    $GroupID = $classgroup->attribute( "id" );
    $Module->redirectTo( $Module->functionURI( "groupedit" ) . "/" . $GroupID );
    return;
}

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $Module->redirectTo( $Module->functionURI( "grouplist" ) );
    return;
}

if ( $http->hasPostVariable( "StoreButton" ) )
{
    if ( $http->hasPostVariable( "Group_name" ) )
    {
        $name = $http->postVariable( "Group_name" );
    }
    $classgroup->setAttribute( "name", $name );
    // Set new modification date
    $date_time = time();
    $classgroup->setAttribute( "modified", $date_time );
    $user = eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $classgroup->setAttribute( "modifier_id", $user_id );
    $classgroup->store();

    eZContentClassClassGroup::update( null, $GroupID, $name );

    $Module->redirectToView( 'classlist', array( $classgroup->attribute( 'id' ) ) );
    return;
}

$Module->setTitle( "Edit class group " . $classgroup->attribute( "name" ) );

// Template handling
$tpl = eZTemplate::factory();

$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( "classgroup", $classgroup->attribute( "id" ) ) ) );

$tpl->setVariable( "http", $http );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "classgroup", $classgroup );

$Result = array();
$Result['content'] = $tpl->fetch( "design:class/groupedit.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Class groups' ) ),
                         array( 'url' => false,
                                'text' => $classgroup->attribute( 'name' ) ) );

?>
