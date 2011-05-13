<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$ClassID = null;
if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];

$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];

$class = eZContentClass::fetch( $ClassID );
$ClassName = $class->attribute( 'name' );
$classObjects = eZContentObject::fetchSameClassList( $ClassID );
$ClassObjectsCount = count( $classObjects );
if ( $ClassObjectsCount == 0 )
    $ClassObjectsCount .= " object";
else
    $ClassObjectsCount .= " objects";
$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    $class->remove( true );
    eZContentClassClassGroup::removeClassMembers( $ClassID, 0 );
    $Module->redirectTo( '/class/classlist/' . $GroupID );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/class/classlist/' . $GroupID );
}
$Module->setTitle( "Deletion of class " .$ClassID );
$tpl = eZTemplate::factory();


$tpl->setVariable( "module", $Module );
$tpl->setVariable( "GroupID", $GroupID );
$tpl->setVariable( "ClassID", $ClassID );
$tpl->setVariable( "ClassName", $ClassName );
$tpl->setVariable( "ClassObjectsCount", $ClassObjectsCount );
$Result = array();
$Result['content'] = $tpl->fetch( "design:class/delete.tpl" );
$Result['path'] = array( array( 'url' => '/class/delete/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Remove class' ) ) );
?>
