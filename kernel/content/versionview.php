<?php
//
// Created on: <03-May-2002 15:17:01 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

$tpl =& templateInit();
$ObjectID = $Params['ObjectID'];
$Module =& $Params['Module'];
$LanguageCode = $Params['LanguageCode'];
$EditVersion = $Params['EditVersion'];

$contentObject =& eZContentObject::fetch( $ObjectID );
$versionObject =& $contentObject->version( $EditVersion );
$versionAttributes = $versionObject->contentObjectAttributes();

if ( $contentObject === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_FOUND, 'kernel' );

if ( $LanguageCode != '' )
{
    $contentObject->setCurrentLanguage( $LanguageCode );
}
else
{
    $LanguageCode = $contentObject->defaultLanguage();
}


$relatedObjectArray =& $contentObject->relatedContentObjectArray( $contentObject->attribute( 'current_version' ) );

$classID = $contentObject->attribute( 'contentclass_id' );

$class =& eZContentClass::fetch( $classID );

$classes =& eZContentClass::fetchList( $version = 0, $asObject = true, $user_id = false,
                                       array( 'name' => 'name' ), $fields = null );

$Module->setTitle( 'View ' . $class->attribute( 'name' ) . ' - ' . $contentObject->attribute( 'name' ) );

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'object', $contentObject->attribute( 'id' ) ), // Object ID
                      array( 'class', $class->attribute( 'id' ) ), // Class ID
                      array( 'viewmode', 'full' ) ) ); // Section ID

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $contentObject->attribute( 'section_id' ) );

$tpl->setVariable( 'object', $contentObject );
$tpl->setVariable( 'version_attributes', $versionAttributes );
$tpl->setVariable( 'class', $class );
$tpl->setVariable( 'object_version', $EditVersion );
$tpl->setVariable( 'object_languagecode', $LanguageCode );

$tpl->setVariable( 'related_contentobject_array', $relatedObjectArray );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/view/versionview.tpl' );
$Result['path'] = array( array( 'text' => $contentObject->attribute( 'name' ),
                                'url' => false ) );

?>
