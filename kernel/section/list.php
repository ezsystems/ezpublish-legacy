<?php
//
// Created on: <27-Aug-2002 15:42:43 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/ezsection.php' );
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezpreferences.php' );

$http =& eZHTTPTool::instance();
$Module =& $Params["Module"];
$tpl =& templateInit();
$tpl->setVariable( 'module', $Module );

$offset = $Params['Offset'];

if( eZPreferences::value( 'admin_section_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_section_list_limit' ) )
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

if ( $http->hasPostVariable( 'CreateSectionButton' ) )
{
    $Module->redirectTo( $Module->functionURI( "edit" ) . '/0/' );
    return;
}

if ( $http->hasPostVariable( 'RemoveSectionButton' ) )
{
    $sectionIDArray =& $http->postVariable( 'SectionIDArray' );
    $http->setSessionVariable( 'SectionIDArray', $sectionIDArray );
    $sections = array();
    foreach ( $sectionIDArray as $sectionID )
    {
        $section =& eZSection::fetch( $sectionID );
        $sections[] =& $section;
    }
    $tpl->setVariable( 'delete_result', $sections );
    $Result = array();
    $Result['content'] =& $tpl->fetch( "design:section/confirmremove.tpl" );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'kernel/section', 'Sections' ) ) );
    return;

}

if ( $http->hasPostVariable( 'ConfirmRemoveSectionButton' ) )
{
    $sectionIDArray =& $http->sessionVariable( 'SectionIDArray' );
    foreach ( $sectionIDArray as $sectionID )
    {
        $section =& eZSection::fetch( $sectionID );
        $section->remove( );
    }
}

$viewParameters = array( 'offset' => $offset );
$sectionArray =& eZSection::fetchByOffset( $offset, $limit );
$sectionCount = eZSection::sectionCount();

$tpl->setVariable( "limit", $limit );
$tpl->setVariable( 'section_array', $sectionArray );
$tpl->setVariable( 'section_count', $sectionCount );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:section/list.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/section', 'Sections' ) ) );

?>
