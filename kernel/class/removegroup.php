<?php
//
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
            $deletedClassName = ezpI18n::translate( 'kernel/class', '(no classes)' );
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
$Module->setTitle( ezpI18n::translate( 'kernel/class', 'Remove class groups' ) . ' ' . $GroupName );
require_once( "kernel/common/template.php" );
$tpl = templateInit();

$tpl->setVariable( "DeleteResult", $deleteResult );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "groups_info", $groupsInfo );
$Result = array();
$Result['content'] = $tpl->fetch( "design:class/removegroup.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::translate( 'kernel/class', 'Class groups' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::translate( 'kernel/class', 'Remove class groups' ) ) );
?>
