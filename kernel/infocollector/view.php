<?php
//
// Created on: <13-Feb-2005 03:13:00 bh>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'kernel/common/template.php' );

$http =& eZHTTPTool::instance();
$Module =& $Params['Module'];
$collectionID = $Params['CollectionID'];

$collection = false;
$object = false;

if( is_numeric( $collectionID ) )
{
    $collection = eZInformationCollection::fetch( $collectionID );
}

if( !$collection )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$object = eZContentObject::fetch( $collection->attribute( 'contentobject_id' ) );

if( !$object )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$objectID   = $collection->attribute( 'contentobject_id' );
$objectName = $object->attribute( 'name' );

$tpl =& templateInit();
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'collection', $collection );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:infocollector/view.tpl' );
$Result['path'] = array( array( 'url' => '/infocollector/overview',
                                'text' => ezi18n( 'kernel/infocollector', 'Collected information' ) ),
                         array( 'url' => '/infocollector/collectionlist/' . $objectID,
                                'text' => $objectName ),
                         array( 'url' => false,
                                'text' => $collectionID ) );

?>
