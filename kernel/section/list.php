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
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "kernel/classes/ezsection.php" );
include_once( "kernel/common/template.php" );

$http =& eZHTTPTool::instance();
$Module =& $Params["Module"];
$tpl =& templateInit();
$tpl->setVariable( 'module', $Module );

$offset = $Params['Offset'];
$limit = 10;

if ( $http->hasPostVariable( 'CreateSectionButton' ) )
{
    $section = new eZSection( array( 'name' => 'New section' ) );
    $section->store();
    $Module->redirectTo( $Module->functionURI( "edit" ) . '/' . $section->attribute( 'id' ) . '/' );
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
        if( $section === null )
            continue;
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
