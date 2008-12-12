<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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


$Module = $Params['Module'];
$ClassID = $Params['ClassID'];
$GroupID = $Params['GroupID'];
$GroupName = $Params['GroupName'];
$EditLanguage = $Params['Language'];
$FromLanguage = false;
$ClassVersion = null;


switch ( $Params['FunctionName'] )
{
    case 'edit':
    {
    } break;
    default:
    {
        eZDebug::writeError( 'Undefined function: ' . $params['Function'] );
        $Module->setExitStatus( eZModule::STATUS_FAILED );
        return;
    };
}

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( 'CancelConflictButton' ) )
{
    $Module->redirectToView( 'grouplist' );
}

if ( $http->hasPostVariable( 'EditLanguage' ) )
{
    $EditLanguage = $http->postVariable( 'EditLanguage' );
}

if ( is_numeric( $ClassID ) )
{
    $class = eZContentClass::fetch( $ClassID, true, eZContentClass::VERSION_STATUS_TEMPORARY );

    // If temporary version does not exist fetch the current and add temperory class to corresponding group
    if ( !is_object( $class ) or $class->attribute( 'id' ) == null )
    {
        $class = eZContentClass::fetch( $ClassID, true, eZContentClass::VERSION_STATUS_DEFINED );
        if( is_null( $class ) ) // Class does not exist
        {
            return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }
        $classGroups= eZContentClassClassGroup::fetchGroupList( $ClassID, eZContentClass::VERSION_STATUS_DEFINED );
        foreach ( $classGroups as $classGroup )
        {
            $groupID = $classGroup->attribute( 'group_id' );
            $groupName = $classGroup->attribute( 'group_name' );
            $ingroup = eZContentClassClassGroup::create( $ClassID, eZContentClass::VERSION_STATUS_TEMPORARY, $groupID, $groupName );
            $ingroup->store();
        }
    }
    else
    {
        $user = eZUser::currentUser();
        $contentIni = eZINI::instance( 'content.ini' );
        $timeOut = $contentIni->variable( 'ClassSettings', 'DraftTimeout' );

        if ( $class->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
             $class->attribute( 'modified' ) + $timeOut > time() )
        {
            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();

            $res = eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'class', $class->attribute( 'id' ) ) ) ); // Class ID
            $tpl->setVariable( 'class', $class );
            $tpl->setVariable( 'lock_timeout', $timeOut );

            $Result = array();
            $Result['content'] = $tpl->fetch( 'design:class/edit_denied.tpl' );
            $Result['path'] = array( array( 'url' => '/class/grouplist/',
                                            'text' => ezi18n( 'kernel/class', 'Class list' ) ) );
            return $Result;
        }
    }
}
else
{
    if ( !$EditLanguage )
    {
        $language = eZContentLanguage::topPriorityLanguage();
        if ( $language )
        {
            $EditLanguage = $language->attribute( 'locale' );
        }
        else
        {
            eZDebug::writeError( 'Undefined default language', 'class/edit.php' );
            $Module->setExitStatus( eZModule::STATUS_FAILED );
            return;
        }
    }

    if ( is_numeric( $GroupID ) and is_string( $GroupName ) and $GroupName != '' )
    {
        $user = eZUser::currentUser();
        $user_id = $user->attribute( 'contentobject_id' );
        $class = eZContentClass::create( $user_id, array(), $EditLanguage );
        $class->setName( ezi18n( 'kernel/class/edit', 'New Class' ), $EditLanguage );
        $class->store();
        $editLanguageID = eZContentLanguage::idByLocale( $EditLanguage );
        $class->setAlwaysAvailableLanguageID( $editLanguageID );
        $ClassID = $class->attribute( 'id' );
        $ClassVersion = $class->attribute( 'version' );
        $ingroup = eZContentClassClassGroup::create( $ClassID, $ClassVersion, $GroupID, $GroupName );
        $ingroup->store();
        $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID . '/(language)/' . $EditLanguage );
        return;
    }
    else
    {
        $errorResponseGroupName = ( $GroupName == '' ) ? '<Empty name>' : $GroupName;
        $errorResponseGroupID = ( !is_numeric( $GroupID ) ) ? '<Empty ID>' : $GroupID;
        eZDebug::writeError( "Unknown class group: {$errorResponseGroupName} (ID: {$errorResponseGroupID})", 'Kernel - Class - Edit' );
        $Module->setExitStatus( eZModule::STATUS_FAILED );
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}


$contentClassHasInput = true;
if ( $http->hasPostVariable( 'ContentClassHasInput' ) )
    $contentClassHasInput = $http->postVariable( 'ContentClassHasInput' );

// Find out the group where class is created or edited from.
if ( $http->hasSessionVariable( 'FromGroupID' ) )
{
    $fromGroupID = $http->sessionVariable( 'FromGroupID' );
}
else
{
    $fromGroupID = false;
}
$ClassID = $class->attribute( 'id' );
$ClassVersion = $class->attribute( 'version' );

$validation = array( 'processed' => false,
                     'groups' => array(),
                     'attributes' => array(),
                     'class_errors' => array() );
$unvalidatedAttributes = array();

if ( $http->hasPostVariable( 'DiscardButton' ) )
{
    eZSessionDestroy( $http->sessionVariable( 'CanStoreTicket' ) );
    $http->removeSessionVariable( 'CanStoreTicket' );
    $class->setVersion( eZContentClass::VERSION_STATUS_TEMPORARY );
    $class->remove( true, eZContentClass::VERSION_STATUS_TEMPORARY );
    eZContentClassClassGroup::removeClassMembers( $ClassID, eZContentClass::VERSION_STATUS_TEMPORARY );
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
if ( $http->hasPostVariable( 'AddGroupButton' ) && $http->hasPostVariable( 'ContentClass_group' ) )
{
    eZClassFunctions::addGroup( $ClassID, $ClassVersion, $http->postVariable( 'ContentClass_group' ) );
}
if ( $http->hasPostVariable( 'RemoveGroupButton' ) && $http->hasPostVariable( 'group_id_checked' ) )
{
    if ( !eZClassFunctions::removeGroup( $ClassID, $ClassVersion, $http->postVariable( 'group_id_checked' ) ) )
    {
        $validation['groups'][] = array( 'text' => ezi18n( 'kernel/class', 'You have to have at least one group that the class belongs to!' ) );
        $validation['processed'] = true;
    }
}

// Fetch attributes and definitions
$attributes = $class->fetchAttributes();

if ( $http->hasPostVariable( 'SelectLanguageButton' ) && $http->hasPostVariable( 'EditLanguage' ) )
{
    $EditLanguage = $http->postVariable( 'EditLanguage' );

    $FromLanguage = 'None';
    if ( $http->hasPostVariable( 'FromLanguage' ) )
        $FromLanguage = $http->postVariable( 'FromLanguage' );

    foreach ( array_keys( $attributes ) as $key )
    {
        $name = '';
        if ( $FromLanguage != 'None' )
        {
            $name = $attributes[$key]->name( $FromLanguage );
        }
        $attributes[$key]->setName( $name, $EditLanguage );
    }

    $name = '';
    if ( $FromLanguage != 'None' )
    {
        $name = $class->name( $FromLanguage );
    }

    $class->setName( $name, $EditLanguage );
}

// No language was specified in the URL, we need to figure out
// the language to use.
if ( !$EditLanguage )
{
    // Check number of languages
    $languages = eZContentLanguage::fetchList();
    // If there is only one language we choose it for the user.
    if ( count( $languages ) == 1 )
    {
        $language = array_shift( $languages );
        $EditLanguage = $language->attribute( 'locale' );
    }
    else
    {
        $canCreateLanguages = $class->attribute( 'can_create_languages' );
        if ( count( $canCreateLanguages ) == 0)
        {
            $EditLanguage = $class->attribute( 'top_priority_language_locale' );
        }
        else
        {
            require_once( 'kernel/common/template.php' );

            $tpl = templateInit();

            $res = eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'class', $class->attribute( 'id' ) ) ) ); // Class ID

            $tpl->setVariable( 'module', $Module );
            $tpl->setVariable( 'class', $class );

            $Result = array();
            $Result['content'] = $tpl->fetch( 'design:class/select_language.tpl' );
            $Result['path'] = array( array( 'url' => '/class/edit/',
                                            'text' => ezi18n( 'kernel/class', 'Class edit' ) ) );
            return $Result;
        }
    }
}

eZDataType::loadAndRegisterAllTypes();
$datatypes = eZDataType::registeredDataTypes();

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

$canStore = true;
$requireFixup = false;
if ( $contentClassHasInput )
{
    if ( $validationRequired )
    {
        foreach ( $attributes as $attribute )
        {
            $dataType = $attribute->dataType();
            $status = $dataType->validateClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
            if ( $status == eZInputValidator::STATE_INTERMEDIATE )
                $requireFixup = true;
            else if ( $status == eZInputValidator::STATE_INVALID )
            {
                $canStore = false;
                $attributeName = $dataType->attribute( 'information' );
                $attributeName = $attributeName['name'];
                $unvalidatedAttributes[] = array( 'id' => $attribute->attribute( 'id' ),
                                                  'identifier' => $attribute->attribute( 'identifier' ) ? $attribute->attribute( 'identifier' ) : $attribute->attribute( 'name' ),
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

        if ( $http->hasPostVariable( 'ContentAttribute_priority' ) )
            $placementArray = $http->postVariable( 'ContentAttribute_priority' );

        foreach ( $attributes as $key => $attribute )
        {
            $attributeID = $attribute->attribute( 'id' );
            $attribute->setAttribute( 'is_required', in_array( $attributeID, $requireCheckedArray ) );
            $attribute->setAttribute( 'is_searchable', in_array( $attributeID, $searchableCheckedArray ) );
            $attribute->setAttribute( 'is_information_collector', in_array( $attributeID, $informationCollectorCheckedArray ) );
            // Set can_translate to 0 if user has clicked Disable translation in GUI
            $attribute->setAttribute( 'can_translate', !in_array( $attributeID, $canTranslateCheckedArray ) );

            $placement = (int) $placementArray[$key];
            if ( $attribute->attribute( 'placement' ) != $placement )
                $attribute->setAttribute( 'placement', $placement );
        }
    }
}

// Fixup input
if ( $requireFixup )
{
    foreach( $attributes as $attribute )
    {
        $dataType = $attribute->dataType();
        $status = $dataType->fixupClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
    }
}

$cur_datatype = 'ezstring';
// Apply HTTP POST variables
if ( $contentClassHasInput )
{
    eZHTTPPersistence::fetch( 'ContentAttribute', eZContentClassAttribute::definition(), $attributes, $http, true );
    if ( $http->hasPostVariable( 'ContentAttribute_name' ) )
    {
        $attributeNames = $http->postVariable( 'ContentAttribute_name' );
        foreach( array_keys( $attributes ) as $key )
        {
            if ( isset( $attributeNames[$key] ) )
            {
                $attributes[$key]->setName( $attributeNames[$key], $EditLanguage );
            }
        }
    }

    eZHTTPPersistence::fetch( 'ContentClass', eZContentClass::definition(), $class, $http, false );
    if ( $http->hasPostVariable( 'ContentClass_name' ) )
    {
        $class->setName( $http->postVariable( 'ContentClass_name' ), $EditLanguage );
    }

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

    if ( $http->hasVariable( 'ContentClass_always_available_exists' ) )
    {
        if ( $http->hasVariable( 'ContentClass_always_available' ) )
        {
            $class->setAttribute( 'always_available', 1 );
        }
        else
        {
            $class->setAttribute( 'always_available', 0 );
        }
    }

    if ( $http->hasVariable( 'ContentClass_default_sorting_exists' ) )
    {
        if ( $http->hasVariable( 'ContentClass_default_sorting_field' ) )
        {
            $sortingField = $http->variable( 'ContentClass_default_sorting_field' );
            $class->setAttribute( 'sort_field', $sortingField );
        }
        if ( $http->hasVariable( 'ContentClass_default_sorting_order' ) )
        {
            $sortingOrder = $http->variable( 'ContentClass_default_sorting_order' );
            $class->setAttribute( 'sort_order', $sortingOrder );
        }
    }

    if ( $http->hasPostVariable( 'DataTypeString' ) )
        $cur_datatype = $http->postVariable( 'DataTypeString' );
}

$class->setAttribute( 'version', eZContentClass::VERSION_STATUS_TEMPORARY );
$class->NameList->setHasDirtyData();

$trans = eZCharTransform::instance();

// Fixed identifiers to only contain a-z0-9_
foreach( $attributes as $attribute )
{
    $attribute->setAttribute( 'version', eZContentClass::VERSION_STATUS_TEMPORARY );
    $identifier = $attribute->attribute( 'identifier' );
    if ( $identifier == '' )
        $identifier = $attribute->attribute( 'name' );

    $identifier = $trans->transformByGroup( $identifier, 'identifier' );
    $attribute->setAttribute( 'identifier', $identifier );
    if ( $dataType = $attribute->dataType() )
    {
        $dataType->initializeClassAttribute( $attribute );
    }
}

// Fixed class identifier to only contain a-z0-9_
$identifier = $class->attribute( 'identifier' );
if ( $identifier == '' )
    $identifier = $class->attribute( 'name' );
$identifier = $trans->transformByGroup( $identifier, 'identifier' );
$class->setAttribute( 'identifier', $identifier );

// Run custom actions if any
if ( $customAction )
{
    foreach( $attributes as $attribute )
    {
        if ( $customActionAttributeID == $attribute->attribute( 'id' ) )
        {
            $attribute->customHTTPAction( $Module, $http, $customAction );
        }
    }
}
// Set new modification date
$date_time = time();
$class->setAttribute( 'modified', $date_time );
$user = eZUser::currentUser();
$user_id = $user->attribute( 'contentobject_id' );
$class->setAttribute( 'modifier_id', $user_id );

// Remove attributes which are to be deleted
if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    $validation['processed'] = true;
    if ( eZHTTPPersistence::splitSelected( 'ContentAttribute', $attributes,
                                           $http, 'id',
                                           $keepers, $rejects ) )
    {
        $attributes = $keepers;
        foreach ( $rejects as $reject )
        {
            if ( !$reject->removeThis( true ) )
            {
                $dataType = $reject->dataType();
                $removeInfo = $dataType->classAttributeRemovableInformation( $reject );
                if ( $removeInfo !== false )
                {
                    $validation['attributes'] = array( array( 'id' => $reject->attribute( 'id' ),
                                                              'identifier' => $reject->attribute( 'identifier' ),
                                                              'reason' => $removeInfo ) );
                }
            }
        }
    }
}

// Fetch HTTP input
$datatypeValidation = array();
if ( $contentClassHasInput )
{
    foreach( $attributes as $attribute )
    {
        if ( $dataType = $attribute->dataType() )
        {
            $dataType->fetchClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
        }
        else
        {
            $datatypeValidation['processed'] = 1;
            $datatypeValidation['attributes'][] =
                array( 'reason' => array( 'text' => ezi18n( 'kernel/class', 'Could not load datatype: ' ).
                                           $attribute->attribute( 'data_type_string' )."\n".
                                           ezi18n( 'kernel/class', 'Editing this content class may cause data corruption in your system.' ).'<br>'.
                                           ezi18n( 'kernel/class', 'Press "Cancel" to safely exit this operation.').'<br>'.
                                           ezi18n( 'kernel/class', 'Please contact your eZ Publish administrator to solve this problem.').'<br>' ),
                       'item' => $attribute->attribute( 'data_type_string' ),
                       'identifier' => $attribute->attribute( 'data_type_string' ),
                       'id' => $key );
        }
    }
}

if ( $validationRequired )
{
    // check for duplicate attribute identifiers in the input
    if ( count( $attributes ) > 1 )
    {
        for( $attrIndex = 0; $attrIndex < count( $attributes ) - 1; $attrIndex++ )
        {
            $classAttribute = $attributes[$attrIndex];
            $identifier = $classAttribute->attribute( 'identifier' );
            $placement = $classAttribute->attribute( 'placement' );
            for ( $attrIndex2 = $attrIndex + 1; $attrIndex2 < count( $attributes ); $attrIndex2++ )
            {
                $classAttribute2 = $attributes[$attrIndex2];
                $identifier2 = $classAttribute2->attribute( 'identifier' );
                $placement2 = $classAttribute2->attribute( 'placement' );
                if (  $placement ==  $placement2 )
                {
                    $validation['attributes'][] = array( 'identifier' => $identifier2,
                                                         'name' => $classAttribute2->attribute( 'name' ),
                                                         'id' => $classAttribute2->attribute( 'id' ),
                                                         'reason' => array ( 'text' => ezi18n( 'kernel/class', 'duplicate attribute placement' ) ) );
                    $canStore = false;
                    break;
                }

                if ( $identifier == $identifier2 )
                {
                    $validation['attributes'][] = array( 'identifier' => $identifier,
                                                         'name' => $classAttribute->attribute( 'name' ),
                                                         'id' => $classAttribute->attribute( 'id' ),
                                                         'reason' => array ( 'text' => ezi18n( 'kernel/class', 'duplicate attribute identifier' ) ) );
                    $canStore = false;
                    break;
                }
            }
        }
    }
}

// Store version 0 and discard version 1
if ( $http->hasPostVariable( 'StoreButton' ) && $canStore )
{

    $id = $class->attribute( 'id' );
    $oldClassAttributes = $class->fetchAttributes( $id, true, eZContentClass::VERSION_STATUS_DEFINED );
    $newClassAttributes = $class->fetchAttributes( );

    // validate class name and identifier; check presence of class attributes
    // FIXME: object pattern name is never validated

    $basicClassPropertiesValid = true;
    $className       = $class->attribute( 'name' );
    $classIdentifier = $class->attribute( 'identifier' );
    $classID         = $class->attribute( 'id' );

    // validate class name
    if( trim( $className ) == '' )
    {
        $validation['class_errors'][] = array( 'text' => ezi18n( 'kernel/class', 'The class should have nonempty \'Name\' attribute.' ) );
        $basicClassPropertiesValid = false;
    }

    // check presence of attributes
    if ( count( $newClassAttributes ) == 0 )
    {
        $validation['class_errors'][] = array( 'text' => ezi18n( 'kernel/class', 'The class should have at least one attribute.' ) );
        $basicClassPropertiesValid = false;
    }

    // validate class identifier

    $db = eZDB::instance();
    $classCount = $db->arrayQuery( "SELECT COUNT(*) AS count FROM ezcontentclass WHERE  identifier='$classIdentifier' AND version=" . eZContentClass::VERSION_STATUS_DEFINED . " AND id <> $classID" );
    if ( $classCount[0]['count'] > 0 )
    {
        $validation['class_errors'][] = array( 'text' => ezi18n( 'kernel/class', 'There is a class already having the same identifier.' ) );
        $basicClassPropertiesValid = false;
    }
    unset( $classList );
    unset( $db );

    if ( !$basicClassPropertiesValid )
    {
        $canStore = false;
        $validation['processed'] = false;
    }
    else
    {
        $firstStoreAttempt = eZSessionRead( $http->sessionVariable( 'CanStoreTicket' ) );
        if ( !$firstStoreAttempt )
        {
            return $Module->redirectToView( 'view', array( $ClassID ), array( 'Language' => $EditLanguage ) );
        }
        eZSessionDestroy( $http->sessionVariable( 'CanStoreTicket' ) );

        // Class cleanup, update existing class objects according to new changes
        $db = eZDB::instance();
        $db->begin();

        $objects = null;
        $objectCount = eZContentObject::fetchSameClassListCount( $ClassID );
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
                    $objectAttributes = eZContentObjectAttribute::fetchSameClassAttributeIDList( $oldClassAttributeID );
                    foreach ( $objectAttributes as $objectAttribute )
                    {
                        $objectAttributeID = $objectAttribute->attribute( 'id' );
                        $objectAttribute->removeThis( $objectAttributeID );
                    }
                }
            }
            $class->storeDefined( $attributes );

            // Add object attributes which have been added.
            foreach ( $attributes as $newClassAttribute )
            {
                $attributeExist = false;
                $newClassAttributeID = $newClassAttribute->attribute( 'id' );
                foreach ( $oldClassAttributes as $oldClassAttribute )
                {
                    $oldClassAttributeID = $oldClassAttribute->attribute( 'id' );
                    if ( $oldClassAttributeID == $newClassAttributeID )
                    {
                        $attributeExist = true;
                        break;
                    }
                }
                if ( !$attributeExist )
                {
                    $newClassAttribute->initializeObjectAttributes( $objects );
                }
            }
        }
        else
        {
            $class->storeDefined( $attributes );
        }

        $db->commit();

        $http->removeSessionVariable( 'CanStoreTicket' );
        return $Module->redirectToView( 'view', array( $ClassID ), array( 'Language' => $EditLanguage ) );
    }
}

// Store changes
if ( $canStore )
    $class->store( $attributes );

if ( $http->hasPostVariable( 'NewButton' ) )
{
    $new_attribute = eZContentClassAttribute::create( $ClassID, $cur_datatype, array(), $EditLanguage );
    $attrcnt = count( $attributes ) + 1;
    $new_attribute->setName( ezi18n( 'kernel/class/edit', 'new attribute' ) . $attrcnt, $EditLanguage );
    $dataType = $new_attribute->dataType();
    $dataType->initializeClassAttribute( $new_attribute );
    $new_attribute->store();
    $attributes[] = $new_attribute;
}
else if ( $http->hasPostVariable( 'MoveUp' ) )
{
    $attribute = eZContentClassAttribute::fetch( $http->postVariable( 'MoveUp' ), true, eZContentClass::VERSION_STATUS_TEMPORARY,
                                                  array( 'contentclass_id', 'version', 'placement' ) );
    $attribute->move( false );
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID . '/(language)/' . $EditLanguage );
    return;
}
else if ( $http->hasPostVariable( 'MoveDown' ) )
{
    $attribute = eZContentClassAttribute::fetch( $http->postVariable( 'MoveDown' ), true, eZContentClass::VERSION_STATUS_TEMPORARY,
                                                  array( 'contentclass_id', 'version', 'placement' ) );
    $attribute->move( true );
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID . '/(language)/' . $EditLanguage );
    return;
}

$Module->setTitle( 'Edit class ' . $class->attribute( 'name' ) );
if ( !$http->hasSessionVariable( 'CanStoreTicket' ) )
{
    $http->setSessionVariable( 'CanStoreTicket', md5( (string)rand() ) );
    eZSessionWrite( $http->sessionVariable( 'CanStoreTicket' ), 1 );
}

// Fetch updated attributes
$attributes = $class->fetchAttributes();
$validation = array_merge( $validation, $datatypeValidation );

// Template handling
require_once( 'kernel/common/template.php' );
$tpl = templateInit();
$res = eZTemplateDesignResource::instance();
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
$tpl->setVariable( 'language_code', $EditLanguage );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:class/edit.tpl' );
$Result['path'] = array( array( 'url' => '/class/edit/',
                                'text' => ezi18n( 'kernel/class', 'Class edit' ) ) );

?>
