<?php
//
//
// Created on: <16-ïËÔ-2002 10:45:47 sp>
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

include_once( 'kernel/classes/ezrole.php' );
include_once( 'kernel/classes/ezcontentbrowse.php' );

$http =& eZHTTPTool::instance();

$Module =& $Params['Module'];
$roleID =& $Params['RoleID'];
$limitIdent =& $Params['LimitIdent'];
$limitValue =& $Params['LimitValue'];

if ( $http->hasPostVariable( 'AssignSectionID' ) &&
     $http->hasPostVariable( 'SectionID' ) )
{
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $http->postVariable( 'SectionID' ) );
}
else if ( $http->hasPostVariable( 'BrowseActionName' ) and
          $http->postVariable( 'BrowseActionName' ) == 'SelectObjectRelationNode' )
{
    $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
    if ( count( $selectedNodeIDArray ) == 1 )
    {
        $limitValue = $selectedNodeIDArray[0];
    }
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $limitValue );
}
else if ( $http->hasPostVariable( 'BrowseActionName' ) and
          $http->postVariable( 'BrowseActionName' ) == 'AssignRole' )
{
    $selectedObjectIDArray = $http->postVariable( 'SelectedObjectIDArray' );
    $role =& eZRole::fetch( $roleID );

    foreach ( $selectedObjectIDArray as $objectID )
    {
        $role->assignToUser( $objectID, $limitIdent, $limitValue );
    }
    if ( count( $selectedObjectIDArray ) > 0 )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        eZContentObject::expireAllCache();
    }
    $Module->redirectTo( '/role/view/' . $roleID );
}
else if ( is_string( $limitIdent ) && !isset( $limitValue ) )
{
    switch( $limitIdent )
    {
        case 'subtree':
        {
            eZContentBrowse::browse( array( 'action_name' => 'SelectObjectRelationNode',
                                            'from_page' => '/role/assign/' . $roleID . '/' . $limitIdent ),
                                     $Module );
            return;
        } break;

        case 'section':
        {
            include_once( 'kernel/common/template.php' );
            include_once( 'kernel/classes/ezsection.php' );
            $sectionArray =& eZSection::fetchList( );
            $tpl =& templateInit();
            $tpl->setVariable( 'section_array', $sectionArray );
            $tpl->setVariable( 'role_id', $roleID );
            $tpl->setVariable( 'limit_ident', $limitIdent );

            $Result = array();
            $Result['content'] =& $tpl->fetch( 'design:role/assign_limited_section.tpl' );
            $Result['path'] = array( array( 'url' => false,
                                            'text' => ezi18n( 'kernel/role', 'Limit on section' ) ) );
            return;
        } break;

        default:
        {
            eZDebug::writeWarning( 'Unsupported assign limitation: ' . $limitIdent );
            $Module->redirectTo( '/role/view/' . $roleID );
        } break;
    }
}
else if ( is_numeric( $roleID ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'AssignRole',
                                    'from_page' => '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $limitValue ),
                             $Module );

    return;
}



?>
