<?php
//
// Created on: <18-Nov-2003 10:00:08 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
$LanguageCode = $Params['Language'];
$http = eZHTTPTool::instance();
$ClassID = null;
$validation = array( 'processed' => false,
                     'groups' => array() );

if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];
$ClassVersion = null;

if ( !is_numeric( $ClassID ) )
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );

$class = eZContentClass::fetch( $ClassID, true, eZContentClass::VERSION_STATUS_DEFINED );

if ( !$class )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$LanguageCode)
    $LanguageCode = $class->attribute( 'top_priority_language_locale' );

if ( $http->hasPostVariable( 'AddGroupButton' ) && $http->hasPostVariable( 'ContentClass_group' ) )
{
    eZClassFunctions::addGroup( $ClassID, $ClassVersion, $http->postVariable( 'ContentClass_group' ) );
}

if ( $http->hasPostVariable( 'RemoveGroupButton' ) && $http->hasPostVariable( 'group_id_checked' ) )
{
    if ( !eZClassFunctions::removeGroup( $ClassID, $ClassVersion, $http->postVariable( 'group_id_checked' ) ) )
    {
        $validation['groups'][] = array( 'text' => ezi18n( 'kernel/class', 'You have to have at least one group that the class belongs to!' ) );
        $validation['processed'] = true;
    }
}

$attributes = $class->fetchAttributes();
$datatypes = eZDataType::registeredDataTypes();

$mainGroupID = false;
$mainGroupName = false;
$groupList = $class->fetchGroupList();
if ( count( $groupList ) > 0 )
{
    $mainGroupID = $groupList[0]->attribute( 'group_id' );
    $mainGroupName = $groupList[0]->attribute( 'group_name' );
}

$Module->setTitle( "Edit class " . $class->attribute( "name" ) );

require_once( "kernel/common/template.php" );
$tpl = templateInit();

$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'class', $class->attribute( "id" ) ),
                      array( 'class_identifier', $class->attribute( 'identifier' ) ) ) );

$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'language_code', $LanguageCode );
$tpl->setVariable( 'class', $class );
$tpl->setVariable( 'attributes', $attributes );
$tpl->setVariable( 'datatypes', $datatypes );
$tpl->setVariable( 'validation', $validation );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:class/view.tpl' );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezi18n( 'kernel/class', 'Classes' ) ) );
if ( $mainGroupID !== false )
{
    $Result['path'][] = array( 'url' => '/class/classlist/' . $mainGroupID,
                               'text' => $mainGroupName );
}
$Result['path'][] = array( 'url' => false,
                           'text' => $class->attribute( 'name' ) );

?>
