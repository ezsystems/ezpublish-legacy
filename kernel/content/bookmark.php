<?php
//
// Definition of Bookmark class
//
// Created on: <30-Apr-2003 13:46:01 sp>
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

/*! \file bookmark.php
*/
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentbrowse.php' );
include_once( 'kernel/classes/ezcontentbrowsebookmark.php' );

$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$user =& eZUser::currentUser();
$userID = $user->id();

if ( $Module->isCurrentAction( 'Remove' )  )
{
    if ( $Module->hasActionParameter( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $Module->actionParameter( 'DeleteIDArray' );
        foreach ( $deleteIDArray as $deleteID )
        {
            $bookmark =& eZContentBrowseBookmark::fetch( $deleteID );
            if ( $bookmark === null )
                continue;
            $bookmark->remove();
        }
    }
}
else if ( $Module->isCurrentAction( 'Add' )  )
{
    return eZContentBrowse::browse( array( 'action_name' => 'AddBookmark',
                                           'description_template' => 'design:content/browse_bookmark.tpl',
                                           'from_page' => "/content/bookmark" ),
                                    $Module );
}
else if ( $Module->isCurrentAction( 'AddBookmark' )  )
{
    $nodeList = eZContentBrowse::result( 'AddBookmark' );
    if ( $nodeList )
    {
        foreach ( $nodeList as $nodeID )
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
            if ( $node )
            {
                $nodeName = $node->attribute( 'name' );
                eZContentBrowseBookmark::createNew( $userID, $nodeID, $nodeName );
            }
        }
    }
}

$tpl =& templateInit();
$tpl->setVariable('view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/bookmark.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'My bookmarks' ),
                                'url' => false ) );


?>
