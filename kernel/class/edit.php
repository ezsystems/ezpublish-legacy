<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentclassclassgroup.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );



$Module =& $Params['Module'];
$ClassID = null;
if ( isset( $Params['ClassID'] ) )
    $ClassID = $Params['ClassID'];
$GroupID = null;
if ( isset( $Params['GroupID'] ) )
    $GroupID = $Params['GroupID'];
$GroupName = null;
if ( isset( $Params['GroupName'] ) )
    $GroupName = $Params['GroupName'];
$ClassVersion = null;

switch ( $Params['FunctionName'] )
{
    case 'edit':
    {
    } break;
    default:
    {
        eZDebug::writeError( 'Undefined function: ' . $params['Function'] );
        $Module->setExitStatus( EZ_MODULE_STATUS_FAILED );
        return;
    };
}
if ( is_numeric( $ClassID ) )
{
    $class =& eZContentClass::fetch( $ClassID, true, EZ_CLASS_VERSION_STATUS_TEMPORARY );

    // If temporary version does not exist fetch the current and add temperory class to corresponding group
    if ( $class->attribute( 'id' ) == null )
    {
        $class =& eZContentClass::fetch( $ClassID, true, EZ_CLASS_VERSION_STATUS_DEFINED );
        $classGroups=& eZContentClassClassGroup::fetchGroupList( $ClassID, EZ_CLASS_VERSION_STATUS_DEFINED );
        foreach ( $classGroups as $classGroup )
        {
            $groupID = $classGroup->attribute( 'group_id' );
            $groupName = $classGroup->attribute( 'group_name' );
            $ingroup =& eZContentClassClassGroup::create( $ClassID, EZ_CLASS_VERSION_STATUS_TEMPORARY, $groupID, $groupName );
            $ingroup->store();
        }
    }
    else
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $user =& eZUser::currentUser();
        $contentIni =& eZIni::instance( 'content.ini' );
        $timeOut =& $contentIni->variable( 'ClassSettings', 'DraftTimeout' );

        if ( $class->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
             $class->attribute( 'modified' ) + $timeOut > time() )
        {
            include_once( 'kernel/common/template.php' );
            $tpl =& templateInit();

            $res =& eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'class', $class->attribute( 'id' ) ) ) ); // Class ID
            $tpl->setVariable( 'class', $class );
            $tpl->setVariable( 'lock_timeout', $timeOut );

            $Result = array();
            $Result['content'] =& $tpl->fetch( 'design:class/edit_denied.tpl' );
            $Result['path'] = array( array( 'url' => '/class/grouplist/',
                                            'text' => ezi18n( 'kernel/class', 'Class list' ) ) );
            return $Result;
        }
    }
}
else
{
    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( 'contentobject_id' );
    $class =& eZContentClass::create( $user_id );
    $class->setAttribute( 'name', 'New Class' );
    $class->store();
    $ClassID = $class->attribute( 'id' );
    $ClassVersion = $class->attribute( 'version' );
    $ingroup =& eZContentClassClassGroup::create( $ClassID, $ClassVersion, $GroupID, $GroupName );
    $ingroup->store();
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID );
    return;
}

$http =& eZHttpTool::instance();
$contentClassHasInput = true;
if ( $http->hasPostVariable( 'ContentClassHasInput' ) )
    $contentClassHasInput = $http->postVariable( 'ContentClassHasInput' );

// Find out the group where class is created or edited from.
if ( $http->hasSessionVariable( 'FromGroupID' ) )
{
    $fromGroupID = $http->sessionVariable( 'FromGroupID' );
}
$ClassID = $class->attribute( 'id' );
$ClassVersion = $class->attribute( 'version' );


if ( $http->hasPostVariable( 'DiscardButton' ) )
{
    eZSessionDestroy( $http->sessionVariable( 'CanStoreTicket' ) );
    $http->removeSessionVariable( 'CanStoreTicket' );
    $class->setVersion( EZ_CLASS_VERSION_STATUS_TEMPORARY );
    $class->remove( true, $ClassVersion );
    eZContentClassClassGroup::removeClassMembers( $ClassID, $ClassVersion );
    if ( $fromGroupID === false )
    {
        $Module->redirectToView( 'grouplist' );
    }
    else
    {
        $Module->redirectTo( $Module->functionURI( 'classlist' ) . '/' . $fromGroupID . '/' );
    }
    return;
}
if ( $http->hasPostVariable( 'AddGroupButton' ) )
{
    if ( $http->hasPostVariable( 'ContentClass_group') )
    {
        $selectedGroup = $http->postVariable( 'ContentClass_group' );
        list ( $groupID, $groupName ) = split( '/', $selectedGroup );
        $ingroup =& eZContentClassClassGroup::create( $ClassID, $ClassVersion, $groupID, $groupName );
        $ingroup->store();
    }
}

if ( $http->hasPostVariable( 'RemoveGroupButton' ) )
{
    if ( $http->hasPostVariable( 'group_id_checked') )
    {
        $selectedGroup = $http->postVariable( 'group_id_checked' );
        foreach(  $selectedGroup as $group_id )
        {
            eZContentClassClassGroup::remove( $ClassID, $ClassVersion, $group_id );
        }
    }
}
// Fetch attributes and definitions
$attributes =& $class->fetchAttributes();

include_once( 'kernel/classes/ezdatatype.php' );
eZDataType::loadAndRegisterAllTypes();
$datatypes =& eZDataType::registeredDataTypes();

$customAction = false;
$customActionAttributeID = null;
// Check for custom actions
if ( $http->hasPostVariable( 'CustomActionButton' ) )
{
    $customActionArray = $http->postVariable( 'CustomActionButton' );
    $customActionString = key( $customActionArray );

    $customActionAttributeID = preg_match( "#^([0-9]+)_(.*)$#", $customActionString, $matchArray );

    $customActionAttributeID = $matchArray[1];
    $customAction = $matchArray[2];
}
// Validate input
$validation = array( 'processed' => false,
                     'attributes' => array() );
$unvalidatedAttributes = array();

$storeActions = array( 'MoveUp',
                       'MoveDown',
                       'StoreButton',
                       'ApplyButton',
                       'NewButton',
                       'CustomActionButton');
$validationRequired = false;
foreach( $storeActions as $storeAction )
{
    if ( $http->hasPostVariable( $storeAction ) )
    {
        $validationRequired = true;
        break;
    }
}
include_once( 'lib/ezutils/classes/ezinputvalidator.php' );
$canStore = true;
$requireFixup = false;
if ( $contentClassHasInput )
{
    if ( $validationRequired )
    {
        foreach ( array_keys( $attributes ) as $key )
        {
            $attribute =& $attributes[$key];
            $dataType =& $attribute->dataType();
            $status = $dataType->validateClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
            if ( $status == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
                $requireFixup = true;
            else if ( $status == EZ_INPUT_VALIDATOR_STATE_INVALID )
            {
                $canStore = false;
                $attributeName = $dataType->attribute( 'information' );
                $attributeName = $attributeName['name'];
                $unvalidatedAttributes[] = array( 'id' => $attribute->attribute( 'id' ),
                                                  'identifier' => $attribute->attribute( 'identifier' ),
                                                  'name' => $attributeName );
            }
        }
        $validation['processed'] = true;
        $validation['attributes'] = $unvalidatedAttributes;
        $requireVariable = 'ContentAttribute_is_required_checked';
        $searchableVariable = 'ContentAttribute_is_searchable_checked';
        $informationCollectorVariable = 'ContentAttribute_is_information_collector_checked';
        $canTranslateVariable = 'ContentAttribute_can_translate_checked';
        $requireCheckedArray = array();
        $searchableCheckedArray = array();
        $informationCollectorCheckedArray = array();
        $canTranslateCheckedArray = array();
        if ( $http->hasPostVariable( $requireVariable ) )
            $requireCheckedArray = $http->postVariable( $requireVariable );
        if ( $http->hasPostVariable( $searchableVariable ) )
            $searchableCheckedArray = $http->postVariable( $searchableVariable );
        if ( $http->hasPostVariable( $informationCollectorVariable ) )
            $informationCollectorCheckedArray = $http->postVariable( $informationCollectorVariable );
        if ( $http->hasPostVariable( $canTranslateVariable ) )
            $canTranslateCheckedArray = $http->postVariable( $canTranslateVariable );

        foreach ( array_keys( $attributes ) as $key )
        {
            $attribute =& $attributes[$key];
            $attributeID = $attribute->attribute( 'id' );
            $attribute->setAttribute( 'is_required', in_array( $attributeID, $requireCheckedArray ) );
            $attribute->setAttribute( 'is_searchable', in_array( $attributeID, $searchableCheckedArray ) );
            $attribute->setAttribute( 'is_information_collector', in_array( $attributeID, $informationCollectorCheckedArray ) );
            // Set can_translate to 0 if user has clicked Disable translation in GUI
            $attribute->setAttribute( 'can_translate', !in_array( $attributeID, $canTranslateCheckedArray ) );
        }
    }
}
// Fixup input
if ( $requireFixup )
{
    foreach( array_keys( $attributes ) as $key )
    {
        $attribute =& $attributes[$key];
        $dataType =& $attribute->dataType();
        $status = $dataType->fixupClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
    }
}

$cur_datatype = 0;
// Apply HTTP POST variables
if ( $contentClassHasInput )
{
    eZHTTPPersistence::fetch( 'ContentAttribute', eZContentClassAttribute::definition(),
                              $attributes, $http, true );
    eZHttpPersistence::fetch( 'ContentClass', eZContentClass::definition(),
                              $class, $http, false );
    if ( $http->hasVariable( 'ContentClass_is_container_exists' ) )
    {
    	if ( $http->hasVariable( 'ContentClass_is_container_checked' ) )
        {
            $class->setAttribute( "is_container", 1 );
        }
        else
        {
            $class->setAttribute( "is_container", 0 );
        }
    }
    if ( $http->hasPostVariable( 'DataTypeString' ) )
        $cur_datatype = $http->postVariable( 'DataTypeString' );
}

$class->setAttribute( 'version', EZ_CLASS_VERSION_STATUS_TEMPORARY );

// Fixed identifiers to only contain a-z0-9_
foreach( array_keys( $attributes ) as $key )
{
    $attribute =& $attributes[$key];
    $attribute->setAttribute( 'version', EZ_CLASS_VERSION_STATUS_TEMPORARY );
    $identifier = $attribute->attribute( 'identifier' );
    if ( $identifier == '' )
        $identifier = $attribute->attribute( 'name' );
    $identifier = strtolower( $identifier );
    $identifier = preg_replace( array( "/[^a-z0-9_ ]/" ,
                                       "/ /",
                                       "/__+/" ),
                                array( "",
                                       "_",
                                       "_" ),
                                $identifier );
    $attribute->setAttribute( 'identifier', $identifier );
    $dataType =& $attribute->dataType();
    $dataType->initializeClassAttribute( $attribute );
}

// Fixed class identifier to only contain a-z0-9_
$identifier = $class->attribute( 'identifier' );
if ( $identifier == '' )
    $identifier = $class->attribute( 'name' );
$identifier = strtolower( $identifier );
$identifier = preg_replace( array( "/[^a-z0-9_ ]/" ,
                                   "/ /",
                                   "/__+/" ),
                            array( "",
                                   "_",
                                   "_" ),
                            $identifier );
$class->setAttribute( 'identifier', $identifier );

// Run custom actions if any
if ( $customAction )
{
    foreach( array_keys( $attributes ) as $key )
    {
        $attribute =& $attributes[$key];
        if ( $customActionAttributeID == $attribute->attribute( 'id' ) )
        {
            $attribute->customHTTPAction( $Module, $http, $customAction );
        }
    }
}
// Set new modification date
$date_time = time();
$class->setAttribute( 'modified', $date_time );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
$user =& eZUser::currentUser();
$user_id = $user->attribute( 'contentobject_id' );
$class->setAttribute( 'modifier_id', $user_id );

// Remove attributes which are to be deleted
if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    if ( eZHttpPersistence::splitSelected( 'ContentAttribute', $attributes,
                                           $http, 'id',
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
if ( $contentClassHasInput )
{
    foreach( array_keys( $attributes ) as $key )
    {
        $attribute =& $attributes[$key];
        $dataType =& $attribute->dataType();
        $dataType->fetchClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
    }
}

// Store version 0 and discard version 1
if ( $http->hasPostVariable( 'StoreButton' ) && $canStore )
{
    $firstStoreAttempt =& eZSessionRead( $http->sessionVariable( 'CanStoreTicket' ) );
    if ( !$firstStoreAttempt )
    {
        return $Module->redirectToView( 'view', array( $ClassID ) );
    }
    eZSessionDestroy( $http->sessionVariable( 'CanStoreTicket' ) );

    // Class cleanup, update existing class objects according to new changes
    include_once( 'kernel/classes/ezcontentobject.php' );
    $id = $class->attribute( 'id' );
    $oldClassAttributes = $class->fetchAttributes( $id, true, EZ_CLASS_VERSION_STATUS_DEFINED );
    $newClassAttributes = $class->fetchAttributes( );
    $objects = null;
    $objectCount =& eZContentObject::fetchSameClassListCount( $ClassID );
    if ( $objectCount > 0 )
    {
        // Delete object attributes which have been removed.
        foreach ( $oldClassAttributes as $oldClassAttribute )
        {
            $attributeExist = false;
            $oldClassAttributeID = $oldClassAttribute->attribute( 'id' );
            foreach ( $newClassAttributes as $newClassAttribute )
            {
                $newClassAttributeID = $newClassAttribute->attribute( 'id' );
                if ( $oldClassAttributeID == $newClassAttributeID )
                    $attributeExist = true;
            }
            if ( !$attributeExist )
            {
                $objectAttributes =& eZContentObjectAttribute::fetchSameClassAttributeIDList( $oldClassAttributeID );
                foreach ( $objectAttributes as $objectAttribute )
                {
                    $objectAttributeID = $objectAttribute->attribute( 'id' );
                    $objectAttribute->remove( $objectAttributeID );
                }
            }
        }
        $class->storeDefined( $attributes );

        // Add object attributes which have been added.
        foreach ( $newClassAttributes as $newClassAttribute )
        {
            $attributeExist = false;
            $newClassAttributeID = $newClassAttribute->attribute( 'id' );
            foreach ( $oldClassAttributes as $oldClassAttribute )
            {
                $oldClassAttributeID = $oldClassAttribute->attribute( 'id' );
                if ( $oldClassAttributeID == $newClassAttributeID )
                    $attributeExist = true;
            }
            if ( !$attributeExist )
            {
                if ( $objects == null )
                {
                    $objects =& eZContentObject::fetchSameClassList( $ClassID );
                }
                foreach ( $objects as $object )
                {
                    $contentobjectID = $object->attribute( 'id' );
                    $objectVersions =& $object->versions();
                    foreach ( $objectVersions as $objectVersion )
                    {
                        $translations = $objectVersion->translations( false );
                        $version = $objectVersion->attribute( 'version' );
                        foreach ( $translations as $translation )
                        {
                            $objectAttribute =& eZContentObjectAttribute::create( $newClassAttributeID, $contentobjectID, $version );
                            $objectAttribute->setAttribute( 'language_code', $translation );
                            $objectAttribute->initialize();
                            $objectAttribute->store();
                        }
                    }
                }
            }
        }
    }
    else
    {
        $class->storeDefined( $attributes );
    }

    // Set the object name to the first attribute, if not set
    $classAttributes = $class->fetchAttributes();

    // Fetch the first attribute
    if ( count( $classAttributes ) > 0 )
    {
        $identifier = $classAttributes[0]->attribute( 'identifier' );
        $identifier = '<' . $identifier . '>';
        if ( trim( $class->attribute( 'contentobject_name' ) ) == '' )
        {
            $class->setAttribute( 'contentobject_name', $identifier );
            $class->store();
        }
    }

    // Remove old version 0 first
    eZContentClassClassGroup::removeClassMembers( $ClassID, EZ_CLASS_VERSION_STATUS_DEFINED );

    $classgroups =& eZContentClassClassGroup::fetchGroupList( $ClassID, EZ_CLASS_VERSION_STATUS_TEMPORARY );
	for ( $i=0;$i<count(  $classgroups );$i++ )
    {
        $classgroup =& $classgroups[$i];
        $classgroup->setAttribute('contentclass_version', EZ_CLASS_VERSION_STATUS_DEFINED );
        $classgroup->store();
    }
//     eZContentClass::removeAttributes( false, $ClassID, EZ_CLASS_VERSION_STATUS_DEFINED );
//     $class->remove( true );
//     $class->setVersion( EZ_CLASS_VERSION_STATUS_DEFINED, $attributes );
//     include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
//     $user =& eZUser::currentUser();
//     $user_id = $user->attribute( 'contentobject_id' );
//     $class->setAttribute( 'modifier_id', $user_id );
//     $class->setAttribute( 'modified', time() );
//     $class->adjustAttributePlacements( $attributes );
//     $class->store( $attributes );

    // Remove version 1
    eZContentClassClassGroup::removeClassMembers( $ClassID, EZ_CLASS_VERSION_STATUS_TEMPORARY );

    $http->removeSessionVariable( 'CanStoreTicket' );
    return $Module->redirectToView( 'view', array( $ClassID ) );
}

// Store changes
if ( $canStore )
    $class->store( $attributes );

if ( $http->hasPostVariable( 'NewButton' ) )
{
    $new_attribute =& eZContentClassAttribute::create( $ClassID, $cur_datatype );
    $attrcnt = count( $attributes ) + 1;
    $new_attribute->setAttribute( 'name', 'new attribute'. $attrcnt );
    $dataType = $new_attribute->dataType();
    $dataType->initializeClassAttribute( $new_attribute );
    $new_attribute->store();
    $attributes[] =& $new_attribute;
}
else if ( $http->hasPostVariable( 'MoveUp' ) )
{
    $attribute =& eZContentClassAttribute::fetch( $http->postVariable( 'MoveUp' ), true, EZ_CLASS_VERSION_STATUS_TEMPORARY,
                                                  array( 'contentclass_id', 'version', 'placement' ) );
    $attribute->move( false );
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID );
    return;
}
else if ( $http->hasPostVariable( 'MoveDown' ) )
{
    $attribute =& eZContentClassAttribute::fetch( $http->postVariable( 'MoveDown' ), true, EZ_CLASS_VERSION_STATUS_TEMPORARY,
                                                  array( 'contentclass_id', 'version', 'placement' ) );
    $attribute->move( true );
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID );
    return;
}

$Module->setTitle( 'Edit class ' . $class->attribute( 'name' ) );
if ( !$http->hasSessionVariable( 'CanStoreTicket' ) )
{
    $http->setSessionVariable( 'CanStoreTicket', md5( (string)rand() ) );
    eZSessionWrite( $http->sessionVariable( 'CanStoreTicket' ), 1 );
}
// Template handling
include_once( 'kernel/common/template.php' );
$tpl =& templateInit();
$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'class', $class->attribute( 'id' ) ) ) ); // Class ID
$tpl->setVariable( 'http', $http );
$tpl->setVariable( 'validation', $validation );
$tpl->setVariable( 'can_store', $canStore );
$tpl->setVariable( 'require_fixup', $requireFixup );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'class', $class );
$tpl->setVariable( 'attributes', $attributes );
$tpl->setVariable( 'datatypes', $datatypes );
$tpl->setVariable( 'datatype', $cur_datatype );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:class/edit.tpl' );
$Result['path'] = array( array( 'url' => '/class/edit/',
                                'text' => ezi18n( 'kernel/class', 'Class edit' ) ) );

?>
