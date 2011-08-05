<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$GroupID = false;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];

$http = eZHTTPTool::instance();
$http->setSessionVariable( 'FromGroupID', $GroupID );
if ( $http->hasPostVariable( "RemoveButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteClassIDArray', $deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'removeclass' ) . '/'  . $GroupID . '/' );
        }
    }
}

if ( $http->hasPostVariable( "NewButton" ) )
{
    if ( $http->hasPostVariable( "CurrentGroupID" ) )
        $GroupID = $http->postVariable( "CurrentGroupID" );
    if ( $http->hasPostVariable( "CurrentGroupName" ) )
        $GroupName = $http->postVariable( "CurrentGroupName" );
    if ( $http->hasPostVariable( "ClassLanguageCode" ) )
        $LanguageCode = $http->postVariable( "ClassLanguageCode" );
    $params = array( null, $GroupID, $GroupName, $LanguageCode );
    $unorderedParams = array( 'Language' => $LanguageCode );
    $Module->run( 'edit', $params, $unorderedParams );
    return;
}

if ( !isset( $TemplateData ) or !is_array( $TemplateData ) )
{
    $TemplateData = array( array( "name" => "groupclasses",
                                  "http_base" => "ContentClass",
                                  "data" => array( "command" => "groupclass_list",
                                                   "type" => "class" ) ) );
}

$Module->setTitle( ezpI18n::tr( 'kernel/class', 'Class list of group' ) . ' ' . $GroupID );
$tpl = eZTemplate::factory();

$user = eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];

    $groupInfo =  eZContentClassGroup::fetch( $GroupID );

    if( !$groupInfo )
    {
       return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $list = eZContentClassClassGroup::fetchClassList( 0, $GroupID, $asObject = true );
    $groupModifier = eZContentObject::fetch( $groupInfo->attribute( 'modifier_id') );
    $tpl->setVariable( $tplname, $list );
    $tpl->setVariable( "class_count", count( $list ) );
    $tpl->setVariable( "GroupID", $GroupID );
    $tpl->setVariable( "group", $groupInfo );
    $tpl->setVariable( "group_modifier", $groupModifier );
}

$group = eZContentClassGroup::fetch( $GroupID );
$groupName = $group->attribute( 'name' );


$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:class/classlist.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Class groups' ) ),
                         array( 'url' => false,
                                'text' => $groupName ) );
?>
