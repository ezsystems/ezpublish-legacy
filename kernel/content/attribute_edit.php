<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
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

/*!
  \file attribute_edit.php
  This file is a shared code file which is used by different parts of the system
  to edit objects. This file only implements editing of attributes and uses
  hooks to allow external code to add functionality.
  \param $Module must be set by the code which includes this file
*/

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

if ( isset( $Module ) )
    $Module =& $Params['Module'];
$ObjectID =& $Params['ObjectID'];
if ( !isset( $EditVersion ) )
    $EditVersion =& $Params['EditVersion'];

if ( $Module->runHooks( 'pre_fetch', array( $ObjectID, $EditVersion ) ) )
    return;

$object =& eZContentObject::fetch( $ObjectID );
if ( $object === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_edit' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );


$version =& $object->version( $EditVersion );
eZDebug::writeNotice( $version , "object version" );
$classID = $object->attribute( 'contentclass_id' );

$class =& eZContentClass::fetch( $classID );
$contentObjectAttributes =& $version->contentObjectAttributes();

eZDebug::writeNotice($contentObjectAttributes,"obj attributes" );


$http =& eZHTTPTool::instance();

if ( $Module->runHooks( 'post_fetch', array( &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion ) ) )
    return;
$validation = array( 'processed' => false,
                     'attributes' => array() );

/********** Custom Action Code Start ***************/
$customAction = false;
$customActionAttributeID = null;
// Check for custom actions
if ( $http->hasPostVariable( "CustomActionButton" ) )
{
    $customActionArray = $http->postVariable( "CustomActionButton" );
    $customActionString = key( $customActionArray );

    $customActionAttributeID = preg_match( "#^([0-9]+)_(.*)$#", $customActionString, $matchArray );
    $customActionAttributeID = $matchArray[1];
    $customAction = $matchArray[2];
}
/********** Custom Action Code End ***************/

$storeActions = array( 'Preview',
                       'Translate',
                       'VersionEdit',
                       'Apply',
                       'Publish',
                       'Store',
                       'CustomAction' );
$storingAllowed = in_array( $Module->currentAction(), $storeActions );


// These variables will be modified according to validation
$inputValidated = true;
$requireFixup = false;

if ( $storingAllowed )
{
    // Validate input
    include_once( 'lib/ezutils/classes/ezinputvalidator.php' );
    $unvalidatedAttributes = array();
    foreach( array_keys( $contentObjectAttributes ) as $key )
    {
        $contentObjectAttribute =& $contentObjectAttributes[$key];
        $status = $contentObjectAttribute->validateInput( $http, 'ContentObjectAttribute' );
        if ( $status == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
            $requireFixup = true;
        else if ( $status == EZ_INPUT_VALIDATOR_STATE_INVALID )
        {
            $inputValidated = false;
            $dataType =& $contentObjectAttribute->dataType();
            $attributeName = $dataType->attribute( 'information' );
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $attributeName = $attributeName['name'];
            $unvalidatedAttributes[] = array( 'id' => $contentObjectAttribute->attribute( 'id' ),
                                              'identifier' => $contentClassAttribute->attribute( 'identifier' ),
                                              'name' => $contentObjectAttribute->attribute( 'validation_error' ) );
        }
    }
    // Fixup input
    if ( $requireFixup )
    {
        foreach ( array_keys( $contentObjectAttributes ) as $key )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            $contentObjectAttribute->fixupInput( $http, 'ContentObjectAttribute' );
        }
    }
    $requreStoreAction= false;
    foreach( array_keys( $contentObjectAttributes ) as $key )
    {
        $contentObjectAttribute =& $contentObjectAttributes[$key];
        if( $contentObjectAttribute->fetchInput( $http, "ContentObjectAttribute" ) )
        {
            $requreStoreAction= true;
        }
/********** Custom Action Code Start ***************/
        if ( $customActionAttributeID == $contentObjectAttribute->attribute( "id" ) )
        {
            $contentObjectAttribute->customHTTPAction( $http, $customAction );
        }
/********** Custom Action Code End ***************/

    }

    if ( $inputValidated == true && $requreStoreAction )
    {
        if ( $Module->runHooks( 'pre_commit', array( &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion ) ) )
            return;

        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
        $version->store();

        // Tell attributes to store themselves if necessary
        foreach( array_keys( $contentObjectAttributes ) as $key )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            $contentObjectAttribute->store();
        }

        $objectName = $class->contentObjectName( $object );
        $object->setAttribute( 'name', $objectName );
        $object->store();
    }

    $validation['processed'] = true;
    $validation['attributes'] = $unvalidatedAttributes;
}

// After the object has been validated we can check for other actions
if ( $inputValidated == true )
{
    if ( $Module->runHooks( 'action_check', array( &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion ) ) )
        return;
}

if ( isset( $Params['TemplateObject'] ) )
    $tpl =& $Params['TemplateObject'];

if ( !isset( $tpl ) || get_class( $tpl ) != 'eztemplate' )
    $tpl =& templateInit();

$tpl->setVariable( 'validation', $validation );

$Module->setTitle( 'Edit ' . $class->attribute( 'name' ) . ' - ' . $object->attribute( 'name' ) );
$res =& eZTemplateDesignResource::instance();

$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ), // Object ID
                      array( 'class', $class->attribute( 'id' ) ) // Class ID
                      ) ); // Section ID

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $object->attribute( 'section_id' ) );

$tpl->setVariable( 'edit_version', $EditVersion );
$tpl->setVariable( 'http', $http );
$tpl->setVariable( 'content_attributes', $contentObjectAttributes );
$tpl->setVariable( 'class', $class );
$tpl->setVariable( 'object', $object );
if ( $Module->runHooks( 'pre_template', array( &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion, &$tpl ) ) )
    return;
$templateName = 'design:content/edit.tpl';

if ( isset( $Params['TemplateName'] ) )
    $templateName = $Params['TemplateName'];
$Result = array();
$Result['content'] =& $tpl->fetch( $templateName );

$Result['path'] = array( array( 'text' => $object->attribute( 'name' ),
                                'url' => false ) );
?>
