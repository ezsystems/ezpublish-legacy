<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$deleteIDArray = $http->hasSessionVariable( 'DeleteGroupIDArray' ) ? $http->sessionVariable( 'DeleteGroupIDArray' ) : array();
$groupsInfo = array();
$deleteResult = array();
$deleteClassIDList = array();
foreach ( $deleteIDArray as $deleteID )
{
    $deletedClassName = '';
    $group = eZContentClassGroup::fetch( $deleteID );
    if ( $group != null )
    {
        $GroupName = $group->attribute( 'name' );
        $classList = eZContentClassClassGroup::fetchClassList( null, $deleteID );
        $groupClassesInfo = array();
        foreach ( $classList as $class )
        {
            $classID = $class->attribute( "id" );
            $classGroups = eZContentClassClassGroup::fetchGroupList( $classID, 0);
            if ( count( $classGroups ) == 1 )
            {
                $classObject = eZContentclass::fetch( $classID );
                $className = $classObject->attribute( "name" );
                $deletedClassName .= " '" . $className . "'" ;
                $deleteClassIDList[] = $classID;
                $groupClassesInfo[] = array( 'class_name'   => $className,
                                             'object_count' => $classObject->objectCount() );
            }
        }
        if ( $deletedClassName == '' )
            $deletedClassName = ezpI18n::tr( 'kernel/class', '(no classes)' );
        $deleteResult[] = array( 'groupName'        => $GroupName,
                                 'deletedClassName' => $deletedClassName );
        $groupsInfo[] = array( 'group_name' => $GroupName,
                               'class_list' => $groupClassesInfo );
    }
}
if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    foreach ( $deleteIDArray as $deleteID )
    {
        eZContentClassGroup::removeSelected( $deleteID );
        eZContentClassClassGroup::removeGroupMembers( $deleteID );
        foreach ( $deleteClassIDList as $deleteClassID )
        {
            $deleteClass = eZContentClass::fetch( $deleteClassID );
            if ( $deleteClass )
                $deleteClass->remove( true );
            $deleteClass = eZContentClass::fetch( $deleteClassID, true, eZContentClass::VERSION_STATUS_TEMPORARY );
            if ( $deleteClass )
                $deleteClass->remove( true );
        }
    }
    $Module->redirectTo( '/class/grouplist/' );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/class/grouplist/' );
}
$Module->setTitle( ezpI18n::tr( 'kernel/class', 'Remove class groups' ) . ' ' . $GroupName );
$tpl = eZTemplate::factory();

$tpl->setVariable( "DeleteResult", $deleteResult );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "groups_info", $groupsInfo );
$Result = array();
$Result['content'] = $tpl->fetch( "design:class/removegroup.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Class groups' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/class', 'Remove class groups' ) ) );
?>
