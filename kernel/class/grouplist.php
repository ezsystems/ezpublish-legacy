<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "RemoveGroupButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteGroupIDArray', $deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'removegroup' ) . '/' );
        }
    }
}

if ( $http->hasPostVariable( "EditGroupButton" ) && $http->hasPostVariable( "EditGroupID" ) )
{
    $Module->redirectTo( $Module->functionURI( "groupedit" ) . "/" . $http->postVariable( "EditGroupID" ) );
    return;
}

if ( $http->hasPostVariable( "NewGroupButton" ) )
{
    $params = array();
    $Module->run( "groupedit", $params );
    return;
}

if ( $http->hasPostVariable( "NewClassButton" ) )
{
    if ( $http->hasPostVariable( "SelectedGroupID" ) )
    {
        $groupID = $http->postVariable( "SelectedGroupID" );
        $group = eZContentClassGroup::fetch( $groupID );
        $groupName = $group->attribute( 'name' );

        $params = array( null, $groupID, $groupName );
        return $Module->run( "edit", $params );
    }
}

if ( !isset( $TemplateData ) or !is_array( $TemplateData ) )
{
    $TemplateData = array( array( "name" => "groups",
                                  "http_base" => "ContentClass",
                                  "data" => array( "command" => "group_list",
                                                   "type" => "class" ) ) );
}

$Module->setTitle( ezpI18n::tr( 'kernel/class', 'Class group list' ) );
$tpl = eZTemplate::factory();

$user = eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];
    $data = $tpldata["data"];
    $asObject = isset( $data["as_object"] ) ? $data["as_object"] : true;
    $base = $tpldata["http_base"];
    unset( $list );
    $list = eZContentClassGroup::fetchList( false, $asObject );
    $tpl->setVariable( $tplname, $list );
}

$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:class/grouplist.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/class', 'Class groups' ) ) );

?>
