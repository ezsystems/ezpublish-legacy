<?php
//
// Created on: <27-Aug-2002 17:06:06 bf>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/ezsection.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/ezcontentbrowse.php" );
include_once( "kernel/common/template.php" );

$http =& eZHTTPTool::instance();
$SectionID =& $Params["SectionID"];
$Module =& $Params["Module"];

$section =& eZSection::fetch( $SectionID );

// Redirect to content node browse

// Assign section to subtree of node
if ( $Module->isCurrentAction( 'AssignSection' ) )
{
    $selectedNodeIDArray = eZContentBrowse::result( 'AssignSection' );

    foreach ( $selectedNodeIDArray as $nodeID )
    {
        eZContentObjectTreeNode::assignSectionToSubTree( $nodeID, $section->attribute( 'id' ) );
    }
    if ( count( $selectedNodeIDArray ) > 0 )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        eZContentObject::expireAllCache();
    }
    $Module->redirectTo( "/section/list/" );
}
else
{
    eZContentBrowse::browse( array( 'action_name' => 'AssignSection',
                                    'description_template' => 'design:section/browse_assign.tpl',
                                    'content' => array( 'section_id' => $section->attribute( 'id' ) ),
                                    'from_page' => '/section/assign/' . $section->attribute( 'id' ) . "/",
                                    'cancel_page' => '/section/list' ),
                             $Module );
    return;
}

$tpl =& templateInit();

$tpl->setVariable( "section", $section );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:section/assign.tpl" );

?>
