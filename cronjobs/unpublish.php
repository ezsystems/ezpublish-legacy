<?php
//
// Created on: <16-Сен-2003 16:09:52 sp>
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

/*! \file unpublish.php
*/

include_once( "kernel/classes/ezcontentobjecttreenode.php" );



// Check for extension
include_once( 'lib/ezutils/classes/ezextension.php' );
include_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions();
// Extension check end

include_once( "lib/ezutils/classes/ezini.php" );
$ini =& eZINI::instance( 'content.ini' );
$unpublishClasses = $ini->variable( 'UnpublishSettings','ClassList' );

$rootNodeIDList = $ini->variable( 'UnpublishSettings','RootNodeList' );

//print( 'classes:' );
//var_dump( $unpublishClasses );

include_once( 'lib/ezlocale/classes/ezdatetime.php' );
$currrentDate = eZDateTime::currentTimeStamp();

foreach( $rootNodeIDList as $nodeID )
{
    $rootNode =& eZContentObjectTreeNode::fetch( $nodeID );

    $articleNodeArray =& $rootNode->subTree( array( 'ClassFilterType' => 'include',
                                                    'ClassFilterArray' => $unpublishClasses ) );

    //var_dump( $articleNodeArray );

    foreach ( array_keys( $articleNodeArray ) as $key )
    {
        $article =& $articleNodeArray[$key]->attribute( 'object' );
        $dataMap =& $article->attribute( 'data_map' );

        $dateAttribute =& $dataMap['unpublish_date'];

        if ( is_null( $dateAttribute ) )
            continue;

        $date = $dateAttribute->content();
        $articleRetractDate = $date->attribute( 'timestamp' );
        //var_dump( $articleRetractDate );
        if ( $articleRetractDate > 0 && $articleRetractDate < $currrentDate )
        {
            $article->remove( $article->attribute( 'id' ), $articleNodeArray[$key]->attribute( 'node_id' ) );
        }
    }
}


?>
