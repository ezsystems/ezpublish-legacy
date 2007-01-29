<?php
//
// Created on: <27-Aug-2002 17:06:06 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezsection.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/ezcontentbrowse.php" );
include_once( "kernel/common/template.php" );

$http =& eZHTTPTool::instance();
$SectionID =& $Params["SectionID"];
$Module =& $Params["Module"];

if ( $http->hasPostVariable( 'BrowseCancelButton' ) )
{
    if ( $http->hasPostVariable( 'BrowseCancelURI' ) )
    {
        return $Module->redirectTo( $http->postVariable( 'BrowseCancelURI' ) );
    }
}

$section = eZSection::fetch( $SectionID );

// Redirect to content node browse

// Assign section to subtree of node
if ( $Module->isCurrentAction( 'AssignSection' ) )
{
    $selectedNodeIDArray = eZContentBrowse::result( 'AssignSection' );

    $db =& eZDB::instance();
    $db->begin();
    foreach ( $selectedNodeIDArray as $nodeID )
    {
        eZContentObjectTreeNode::assignSectionToSubTree( $nodeID, $section->attribute( 'id' ) );
    }
    $db->commit();
    if ( count( $selectedNodeIDArray ) > 0 )
    {
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();
    }
    $Module->redirectTo( "/section/list/" );
}
else
{
    eZContentBrowse::browse( array( 'action_name' => 'AssignSection',
                                    'keys' => array(),
                                    'description_template' => 'design:section/browse_assign.tpl',
                                    'content' => array( 'section_id' => $section->attribute( 'id' ) ),
                                    'from_page' => '/section/assign/' . $section->attribute( 'id' ) . "/",
                                    'cancel_page' => '/section/list' ),
                             $Module );
    return;
}

?>
