<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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

include_once( "kernel/classes/ezcontentclass.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( "kernel/classes/ezcontentclassclassgroup.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$Module =& $Params["Module"];
$ClassID = null;
if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];
$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];
$GroupName = null;
if ( isset( $Params["GroupName"] ) )
    $GroupName = $Params["GroupName"];
$ClassVersion = null;
switch ( $Params["FunctionName"] )
{
    case "up":
    {
        $attribute =& eZContentClassAttribute::fetch( $Params["AttributeID"], true, 1,
                                                      array( "contentclass_id", "version", "placement" ) );
        $attribute->move( $Params["FunctionName"] == "up" ? false : true );
        $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $ClassID );
    }
    case "down":
    {
        $attribute =& eZContentClassAttribute::fetch( $Params["AttributeID"], true, 1,
                                                      array( "contentclass_id", "version", "placement" ) );
        $attribute->move( $Params["FunctionName"] == "down" ? true : false );
        $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $ClassID );
        return;
    } break;
    case "edit":
    {
    } break;
    default:
    {
        eZDebug::writeError( "Undefined function: " . $params["Function"] );
        $Module->setExitStatus( EZ_MODULE_STATUS_FAILED );
        return;
    };
}

if ( is_numeric( $ClassID ) )
{
    $class =& eZContentClass::fetch( $ClassID, true, 1 );

    // If temporary version does not exist fetch the current and add temperory class to corresponding group
    if ( $class->attribute("id") == null )
    {
        $class =& eZContentClass::fetch( $ClassID, true, 0 );
        $classGroups=& eZContentClassClassGroup::fetchGroupList( $ClassID, 0);
        foreach ( $classGroups as $classGroup )
        {
            $groupID = $classGroup->attribute( "group_id" );
            $groupName = $classGroup->attribute( "group_name" );
            $ingroup =& eZContentClassClassGroup::create( $ClassID, 1, $groupID, $groupName );
            $ingroup->store();
        }
    }
}
else
{
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $class =& eZContentClass::create( $user_id );
    $class->setAttribute( "name", "New Class" );
    $class->store();
    $ClassID = $class->attribute( "id" );
    $ClassVersion = $class->attribute( "version" );
    $ingroup =& eZContentClassClassGroup::create( $ClassID, $ClassVersion, $GroupID, $GroupName );
    $ingroup->store();
    $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $ClassID );
    return;
}
$http =& eZHttpTool::instance();
$ClassID = $class->attribute( "id" );
$ClassVersion = $class->attribute( "version" );
if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $class->setVersion( 1 );
    $class->remove( true );
    eZContentClassClassGroup::removeClassMembers( $ClassID, $ClassVersion );
    $Module->redirectTo( $Module->functionURI( "grouplist" ) );
    return;
}

if ( $http->hasPostVariable( "AddGroupButton" ) )
{
    if ( $http->hasPostVariable( "ContentClass_group") )
    {
        $selectedGroup = $http->postVariable( "ContentClass_group" );
        list ( $groupID, $groupName ) = split( "/", $selectedGroup );
        $ingroup =& eZContentClassClassGroup::create( $ClassID, $ClassVersion, $groupID, $groupName );
        $ingroup->store();
    }
}

if ( $http->hasPostVariable( "DeleteGroupButton" ) )
{
    if ( $http->hasPostVariable( "group_id_checked") )
    {
        $selectedGroup = $http->postVariable( "group_id_checked" );
        foreach(  $selectedGroup as $group_id )
        {
            eZContentClassClassGroup::remove( $ClassID, $ClassVersion, $group_id );
        }
    }
}

// Fetch attributes and definitions
$attributes =& $class->fetchAttributes();

include_once( "kernel/classes/ezdatatype.php" );
$datatypes =& eZDataType::registeredDataTypes();

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

// Validate input
$validation = array( "processed" => false,
                     "attributes" => array() );
$unvalidatedAttributes = array();

$storeActions = array( "StoreButton",
                       "ApplyButton" );
$validationRequired = false;
foreach( $storeActions as $storeAction )
{
    if ( $http->hasPostVariable( $storeAction ) )
    {
        $validationRequired = true;
        break;
    }
}

include_once( "lib/ezutils/classes/ezinputvalidator.php" );
$canStore = true;
$requireFixup = false;
if ( $validationRequired )
{
    reset( $attributes );
    while( ( $key = key( $attributes ) ) !== null )
    {
        $attribute =& $attributes[$key];
        $dataType =& $attribute->dataType();
        $status = $dataType->validateClassAttributeHTTPInput( $http, "ContentClass", $attribute );
        if ( $status == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
            $requireFixup = true;
        else if ( $status == EZ_INPUT_VALIDATOR_STATE_INVALID )
        {
            $canStore = false;
            $attributeName = $dataType->attribute( "information" );
            $attributeName = $attributeName["name"];
            $unvalidatedAttributes[] = array( "id" => $attribute->attribute( "id" ),
                                              "identifier" => $attribute->attribute( "identifier" ),
                                              "name" => $attributeName );
        }
        next( $attributes );
    }
    $validation["processed"] = true;
    $validation["attributes"] = $unvalidatedAttributes;
}

// Fixup input
if ( $requireFixup )
{
    reset( $attributes );
    while( ( $key = key( $attributes ) ) !== null )
    {
        $attribute =& $attributes[$key];
        $dataType =& $attribute->dataType();
        $status = $dataType->fixupClassAttributeHTTPInput( $http, "ContentClass", $attribute );
        next( $attributes );
    }
}

$cur_datatype = 0;

// Apply HTTP POST variables
eZHttpPersistence::fetch( "ContentAttribute", eZContentClassAttribute::definition(),
                          $attributes, $http, true );
eZHttpPersistence::handleChecked( "ContentAttribute", eZContentClassAttribute::definition(),
                                  $attributes, $http, true );
eZHttpPersistence::fetch( "ContentClass", eZContentClass::definition(),
                          $class, $http, false );
if ( $http->hasPostVariable( "DataTypeString" ) )
    $cur_datatype = $http->postVariable( "DataTypeString" );
$class->setAttribute( "version", 1 );

// Fixed identifiers to only contain a-z0-9_
for ( $i = 0; $i < count( $attributes ); ++$i )
{
    $attribute =& $attributes[$i];
    $attribute->setAttribute( "version", 1 );
    $identifier =& $attribute->attribute( "identifier" );
    if ( $identifier == "" )
        $identifier = $attribute->attribute( "name" );
    $identifier = strtolower( $identifier );
    $identifier = preg_replace( array( "/[^a-z0-9_ ]/" ,
                                       "/ /",
                                       "/__+/" ),
                                array( "",
                                       "_",
                                       "_" ),
                                $identifier );
    $attribute->setAttribute( "identifier", $identifier );

    $dataType =& $attribute->dataType();
    $dataType->initializeClassAttribute( $attribute );
}

// Run custom actions if any
if ( $customAction )
{
    reset( $attributes );
    while( ( $key = key( $attributes ) ) !== null )
    {
        $attribute =& $attributes[$key];
        if ( $customActionAttributeID == $attribute->attribute( "id" ) )
        {
            $attribute->customHTTPAction( $http, $customAction );
        }
        next( $attributes );
    }
}

// Set new modification date
include_once( "lib/ezlocale/classes/ezdatetime.php" );
$date_time = eZDateTime::currentTimeStamp();
$class->setAttribute( "modified", $date_time );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
$user =& eZUser::currentUser();
$user_id = $user->attribute( "contentobject_id" );
$class->setAttribute( "modifier_id", $user_id );

// Remove events which are to be deleted
if ( $http->hasPostVariable( "DeleteButton" ) )
{
    if ( eZHttpPersistence::splitSelected( "ContentAttribute", $attributes,
                                           $http, "id",
                                           $keepers, $rejects ) )
    {
        $attributes = $keepers;
        foreach ( $rejects as $reject )
        {
            $reject->remove();
        }
    }
}

// Fetch HTTP input
reset( $attributes );
while( ( $key = key( $attributes ) ) !== null )
{
    $attribute =& $attributes[$key];
    $dataType =& $attribute->dataType();
    $dataType->fetchClassAttributeHTTPInput( $http, "ContentClass", $attribute );
    next( $attributes );
}

// Store version 0 and discard version 1
if ( $http->hasPostVariable( "StoreButton" ) and $canStore )
{
    // Class cleanup, update existing class objects according to new changes
    include_once( "kernel/classes/ezcontentobject.php" );
    $id = $class->attribute( "id" );
    $oldClassAttributes = $class->fetchAttributes( $id, true, 0 );
    $newClassAttributes = $class->fetchAttributes( );
    $objects =& eZContentObject::fetchSameClassList( $ClassID );
    if ( $objects[0] !== null )
    {
        // Delete object attributes which have been removed.
        foreach ( $oldClassAttributes as $oldClassAttribute )
        {
            $attributeExist = false;
            $oldClassAttributeID = $oldClassAttribute->attribute( "id" );
            foreach ( $newClassAttributes as $newClassAttribute )
            {
                $newClassAttributeID = $newClassAttribute->attribute( "id" );
                if ( $oldClassAttributeID == $newClassAttributeID )
                    $attributeExist = true;
            }
            if ( !$attributeExist )
            {
                $objectAttributes =& eZContentObjectAttribute::fetchSameClassAttributeIDList( $oldClassAttributeID );
                foreach ( $objectAttributes as $objectAttribute )
                {
                    $objectAttributeID = $objectAttribute->attribute( "id" );
                    $objectAttribute->remove( $objectAttributeID );
                }
            }
        }

        $class->storeDefined( $attributes );

        // Add object attributes which have been added.
        foreach ( $newClassAttributes as $newClassAttribute )
        {
            $attributeExist = false;
            $newClassAttributeID = $newClassAttribute->attribute( "id" );
            foreach ( $oldClassAttributes as $oldClassAttribute )
            {
                $oldClassAttributeID = $oldClassAttribute->attribute( "id" );
                if ( $oldClassAttributeID == $newClassAttributeID )
                    $attributeExist = true;
            }
            if ( !$attributeExist )
            {
                foreach ( $objects as $object )
                {
                    $contentobjectID = $object->attribute( "id" );
                    $objectVersions =& $object->versions();
                    foreach ( $objectVersions as $objectVersion )
                    {
                        //$version = $objectVersion->attribute( "version" );
                        //$objectAttribute =& eZContentObjectAttribute::create( $newClassAttributeID, $contentobjectID, $version );
                        //$objectAttribute->storeNewRow();
                        $version = $objectVersion->attribute( "version" );
                        $objectAttribute =& eZContentObjectAttribute::create( $newClassAttributeID, $contentobjectID, $version );
                        $objectAttribute->initialize();
                        $objectAttribute->store();
                    }
                }
            }
        }
    }
    else
    {
        $class->storeDefined( $attributes );
    }

    // Remove old version 0 first
    eZContentClassClassGroup::removeClassMembers( $ClassID, 0 );

    $classgroups =& eZContentClassClassGroup::fetchGroupList( $ClassID, 1 );
	for ( $i=0;$i<count(  $classgroups );$i++ )
    {
        $classgroup =& $classgroups[$i];
        $classgroup->setAttribute("contentclass_version", 0 );
        $classgroup->store();
    }
//     eZContentClass::removeAttributes( false, $ClassID, 0 );
//     $class->remove( true );
//     $class->setVersion( 0, $attributes );
//     include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
//     $user =& eZUser::currentUser();
//     $user_id = $user->attribute( "contentobject_id" );
//     $class->setAttribute( "modifier_id", $user_id );
//     $class->setAttribute( "modified", eZDateTime::currentTimeStamp() );
//     $class->adjustAttributePlacements( $attributes );
//     $class->store( $attributes );

    // Remove version 1
    eZContentClassClassGroup::removeClassMembers( $ClassID, 1 );
    $Module->redirectTo( $Module->functionURI( 'grouplist' ) );
    return;
}

// Store changes
if ( $canStore )
    $class->store( $attributes );

if ( $http->hasPostVariable( "NewButton" ) )
{
    $new_attribute =& eZContentClassAttribute::create( $ClassID, $cur_datatype );
    $attrcnt = count( $attributes ) + 1;
    $new_attribute->setAttribute( "name", "new attribute$attrcnt" );
    $new_attribute->store();
    $attributes[] =& $new_attribute;
}

$Module->setTitle( "Edit class " . $class->attribute( "name" ) );

// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "class", $class->attribute( "id" ) ) ) ); // Class ID
$tpl->setVariable( "http", $http );
$tpl->setVariable( "validation", $validation );
$tpl->setVariable( "can_store", $canStore );
$tpl->setVariable( "require_fixup", $requireFixup );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "class", $class );
$tpl->setVariable( "attributes", $attributes );
$tpl->setVariable( "datatypes", $datatypes );
$tpl->setVariable( "datatype", $cur_datatype );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/edit.tpl" );
$Result['path'] = array( array( 'url' => '/class/edit/',
                                'text' => 'Class edit' ) );

?>
