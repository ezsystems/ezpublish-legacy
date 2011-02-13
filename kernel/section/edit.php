<?php
//
// Created on: <27-Aug-2002 16:31:33 bf>
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



$http = eZHTTPTool::instance();
$SectionID = $Params["SectionID"];
$Module = $Params['Module'];
$tpl = eZTemplate::factory();

if ( $SectionID == 0 )
{
    $section = array( 'id' => 0,
                      'name' => ezpI18n::tr( 'kernel/section', 'New section' ),
                      'navigation_part_identifier' => 'ezcontentnavigationpart' );
}
else
{
    $section = eZSection::fetch( $SectionID );
    if( $section === null )
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}

if ( $http->hasPostVariable( "StoreButton" ) )
{
    if ( $SectionID == 0 )
    {
        $section = new eZSection( array() );
    }
    $section->setAttribute( 'name', $http->postVariable( 'Name' ) );
    $sectionIdentifier = trim( $http->postVariable( 'SectionIdentifier' ) );
    $errorMessage = '';
    if( $sectionIdentifier === '' )
    {
        $errorMessage = ezpI18n::tr( 'design/admin/section/edit', 'Identifier can not be empty' );

    }
    else if( preg_match( '/(^[^A-Za-z])|\W/', $sectionIdentifier ) )
    {
        $errorMessage = ezpI18n::tr( 'design/admin/section/edit', 'Identifier should consist of letters, numbers or \'_\' with letter prefix.' );
    }
    else
    {
        $conditions = array( 'identifier' => $sectionIdentifier,
                             'id' => array( '!=', $SectionID ) );
        $existingSection = eZSection::fetchFilteredList( $conditions );
        if( count( $existingSection ) > 0 )
        {
            $errorMessage = ezpI18n::tr( 'design/admin/section/edit', 'The identifier has been used in another section.' );
        }
    }
    $section->setAttribute( 'identifier', $sectionIdentifier );
    $section->setAttribute( 'navigation_part_identifier', $http->postVariable( 'NavigationPartIdentifier' ) );
    if ( $http->hasPostVariable( 'Locale' ) )
        $section->setAttribute( 'locale', $http->postVariable( 'Locale' ) );
    if( $errorMessage === '' )
    {
        $section->store();
        eZContentCacheManager::clearContentCacheIfNeededBySectionID( $section->attribute( 'id' ) );
        $Module->redirectTo( $Module->functionURI( 'list' ) );
        return;
    }
    else
    {
        $tpl->setVariable( 'error_message', $errorMessage );
    }
}

if ( $http->hasPostVariable( 'CancelButton' )  )
{
    $Module->redirectTo( $Module->functionURI( 'list' ) );
}

$tpl->setVariable( "section", $section );

$Result = array();
$Result['content'] = $tpl->fetch( "design:section/edit.tpl" );
$Result['path'] = array( array( 'url' => 'section/list',
                                'text' => ezpI18n::tr( 'kernel/section', 'Sections' ) ),
                         array( 'url' => false,
                                'text' => $section instanceof eZSection ? $section->attribute('name') : $section['name'] ) );

?>
