<?php
//
//
// <creation-tag>
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

require_once( 'kernel/common/template.php' );

$Module = $Params['Module'];
$objectID = $Params['ObjectID'];

$Offset = $Params['Offset'];

$viewParameters = array( 'offset' => $Offset );

$contentObject = eZContentObject::fetch( $objectID );

if ( !$contentObject )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

$classID = $contentObject->attribute( 'contentclass_id' );
$class = eZContentClass::fetch( $classID );

if ( !$contentObject->attribute( 'can_read' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

if ( !$contentObject->attribute( 'can_diff' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$http = eZHTTPTool::instance();
$tpl = templateInit();

$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'object', $contentObject->attribute( 'id' ) ),
                    array( 'remote_id', $contentObject->attribute( 'remote_id' ) ),
                    array( 'class', $class->attribute( 'id' ) ),
                    array( 'class_identifier', $class->attribute( 'identifier' ) ) ) );

$tpl->setVariable( 'object', $contentObject );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'module', $Module );

//Set default values
$previousVersion = 1;
$newestVersion = 1;

//By default, set preselect the previous and most recent version for diffing
if ( count( $contentObject->versions() ) > 1 )
{
    $versionArray = $contentObject->versions( false );
    $selectableVersions = array();
    foreach( $versionArray as $versionItem )
    {
        //Only return version numbers of archived or published items
        if ( in_array( $versionItem['status'], array( 0, 1, 3 ) ) )
        {
            $selectableVersions[] = $versionItem['version'];
        }
    }
    $newestVersion = array_pop( $selectableVersions );
    $previousVersion = array_pop( $selectableVersions );
}

$tpl->setVariable( 'selectOldVersion', $previousVersion );
$tpl->setVariable( 'selectNewVersion', $newestVersion );

$diff = array();

if ( $http->hasPostVariable( 'FromVersion' ) && $http->hasPostVariable( 'ToVersion' ) )
{
    $lang = false;
    if ( $http->hasPostVariable( 'Language' ) )
    {
        $lang = $http->postVariable( 'Language' );
    }
    $oldVersion = $http->postVariable( 'FromVersion' );
    $newVersion = $http->postVariable( 'ToVersion' );

    if ( is_numeric( $oldVersion ) && is_numeric( $newVersion ) )
    {
        $oldObject = $contentObject->version( $oldVersion );
        $newObject = $contentObject->version( $newVersion );

        if ( $lang )
        {
            $initLang = $contentObject->initialLanguageCode();
            $oldAttributes = $contentObject->fetchDataMap( $oldVersion, $lang );
            if ( !$oldAttributes )
            {
                $oldAttributes = $contentObject->fetchDataMap( $oldVersion, $initLang );
            }
            $newAttributes = $contentObject->fetchDataMap( $newVersion, $lang );
            if ( !$newAttributes )
            {
                $newAttributes = $contentObject->fetchDataMap( $newVersion, $initLang );
            }

        }
        else
        {
            $oldAttributes = $oldObject->dataMap();
            $newAttributes = $newObject->dataMap();
        }

        $extraOptions = false;
        if ( $http->hasPostVariable( 'ExtraOptions' ) )
        {
            $extraOptions = $http->postVariable( 'ExtraOptions' );
        }

        foreach ( $oldAttributes as $attribute )
        {
            $newAttr = $newAttributes[$attribute->attribute( 'contentclass_attribute_identifier' )];
            $contentClassAttr = $newAttr->attribute( 'contentclass_attribute' );
            $diff[$contentClassAttr->attribute( 'id' )] = $contentClassAttr->diff( $attribute, $newAttr, $extraOptions );
        }

        $tpl->setVariable( 'object', $contentObject );
        $tpl->setVariable( 'oldVersion', $oldVersion );
        $tpl->setVariable( 'oldVersionObject', $contentObject->version( $oldVersion ) );

        $tpl->setVariable( 'newVersion', $newVersion );
        $tpl->setVariable( 'newVersionObject', $contentObject->version( $newVersion ) );
        $tpl->setVariable( 'diff', $diff );
    }
}

eZDebug::writeNotice( 'The diff view has been deprecated, please use the /content/history/ view instead' );

$Result = array();

$section = eZSection::fetch( $contentObject->attribute( 'section_id' ) );
if ( $section )
{
    $Result['section_id'] = $section->attribute( 'id' );
    $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
    if ( $navigationPartIdentifier )
    {
        $Result['navigation_part'] = $navigationPartIdentifier;
    }
}

$Result['content'] = $tpl->fetch( "design:content/diff.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => eZi18n::translate( 'kernel/content', 'Differences' ) ) );

?>
