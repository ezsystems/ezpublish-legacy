<?php
//
// Definition of eZDataType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*!
  \class eZDataType ezdatatype.php
  \ingroup eZKernel
  \brief Base class for content datatypes

  Defines both the interface for datatypes as well as functions
  for registering, quering and fetching datatypes.

  Each new datatype will inherit this class and define some functions
  as well as three templates. A datatype has three roles, it handles
  definition of content class attributes, editing of a content object
  attribute and viewing of a content object attribute. The content class
  attribute definition part is optional while object attribute edit and
  view must be implemented for the datatype to be usable.

  If the datatype handles class attribute definition it must define one
  or more of these functions: storeClassAttribute, validateClassAttributeHTTPInput,
  fixupClassAttributeHTTPInput, fetchClassAttributeHTTPInput. See each function
  for more details. The class attribute definition usually defines the
  default data and/or the validation rules for an object attribute.

  Object attribute editing must define these functions: storeObjectAttribute,
  validateObjectAttributeHTTPInput, fixupObjectAttributeHTTPInput,
  fetchObjectAttributeHTTPInput, initializeObjectAttribute. If the attribute
  wants to have a custom action it must implement the customObjectAttributeHTTPAction
  function. See each function for more details.

  Each datatype initializes itself with a datatype string id (ezstring, ezinteger)
  and a descriptive name. The datatype string id must be unique for the whole
  system and should have a prefix, for instance we in eZ systems use ez as our prefix.
*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "lib/ezutils/classes/ezinputvalidator.php" );

class eZDataType
{
    /*!
     Initializes the datatype with the string id \a $dataTypeString and
     the name \a $name.
    */
    function eZDataType( $dataTypeString, $name, $properties = array() )
    {
        $this->DataTypeString = $dataTypeString;
        $this->Name = $name;

        $translationAllowed = true;
        $serializeSupported = false;
        $objectSerializeMap = false;
        if ( isset( $properties['translation_allowed'] ) )
            $translationAllowed = $properties['translation_allowed'];
        if ( isset( $properties['serialize_supported'] ) )
            $serializeSupported = $properties['serialize_supported'];
        if ( isset( $properties['object_serialize_map'] ) )
            $objectSerializeMap = $properties['object_serialize_map'];

        $this->Attributes = array();
        $this->Attributes["is_indexable"] = $this->isIndexable();
        $this->Attributes["is_information_collector"] = $this->isInformationCollector();

        $this->Attributes["information"] = array( "string" => $this->DataTypeString,
                                                  "name" => $this->Name );
        $this->Attributes["properties"] = array( "translation_allowed" => $translationAllowed,
                                                 'serialize_supported' => $serializeSupported,
                                                 'object_serialize_map' => $objectSerializeMap );
    }

    /*!
     \return the template name to use for viewing the attribute.
     \note Default is to return the datatype string which is OK
           for most datatypes, if you want dynamic templates
           reimplement this function and return a template name.
     \note The returned template name does not include the .tpl extension.
     \sa editTemplate, informationTemplate
    */
    function &viewTemplate( &$contentobjectAttribute )
    {
        return $this->DataTypeString;
    }

    /*!
     \return the template name to use for editing the attribute.
     \note Default is to return the datatype string which is OK
           for most datatypes, if you want dynamic templates
           reimplement this function and return a template name.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, informationTemplate
    */
    function &editTemplate( &$contentobjectAttribute )
    {
        return $this->DataTypeString;
    }

    /*!
     \return the template name to use for information collection for the attribute.
     \note Default is to return the datatype string which is OK
           for most datatypes, if you want dynamic templates
           reimplement this function and return a template name.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, editTemplate
    */
    function &informationTemplate( &$contentobjectAttribute )
    {
        return $this->DataTypeString;
    }

    /*!
     \return the template name to use for result view of an information collection attribute.
     \note Default is to return the datatype string which is OK
           for most datatypes, if you want dynamic templates
           reimplement this function and return a template name.
     \note The returned template name does not include the .tpl extension.
     \note \a $collectionAttribute can in some cases be a eZContentObjectAttribute, so any
           datatype that overrides this must be able to handle both types.
    */
    function &resultTemplate( &$collectionAttribute )
    {
        return $this->DataTypeString;
    }

    /*!
     \static
     Crates a datatype instance of the datatype string id \a $dataTypeString.
     \note It only creates one instance for each datatype.
    */
    function &create( $dataTypeString )
    {
        $types =& $GLOBALS["eZDataTypes"];
        $def = null;
        if ( !isset( $types[$dataTypeString] ) )
        {
            eZDataType::loadAndRegisterType( $dataTypeString );
        }

        if ( isset( $types[$dataTypeString] ) )
        {
            $className = $types[$dataTypeString];
            $def =& $GLOBALS["eZDataTypeObjects"][$dataTypeString];

            if ( get_class( $def ) != $className )
            {
                $def = new $className();
            }
        }
        return $def;
    }

    /*!
     \static
     \return a list of datatypes which has been registered.
     \note This will instantiate all datatypes.
    */
    function &registeredDataTypes()
    {
        $types =& $GLOBALS["eZDataTypes"];
        $type_objects =& $GLOBALS["eZDataTypeObjects"];
        if ( isset( $types ) )
        {
            foreach ( $types as $dataTypeString => $className )
            {
                $def =& $type_objects[$dataTypeString];
                if ( get_class( $def ) != $className )
                    $def = new $className();
            }
        }
        return $type_objects;
    }

    /*!
     \static
     Registers the datatype with string id \a $dataTypeString and
     class name \a $className. The class name is used for instantiating
     the class and should be in lowercase letters.
    */
    function register( $dataTypeString, $className )
    {
        $types =& $GLOBALS["eZDataTypes"];
        if ( !is_array( $types ) )
            $types = array();
        $types[$dataTypeString] = $className;
    }

    /*!
     \return the data type identification string.
    */
    function isA()
    {
        return $this->Attributes["information"]["string"];
    }

    /*!
     \return the attributes for this datatype.
    */
    function &attributes()
    {
        return array_keys( $this->Attributes );
    }

    /*!
     \return true if the attribute \a $attr exists in this object.
    */
    function hasAttribute( $attr )
    {
        return isset( $this->Attributes[$attr] );
    }

    /*!
     \return the data for the attribute \a $attr or null if it does not exist.
    */
    function &attribute( $attr )
    {
        if ( isset( $this->Attributes[$attr] ) )
        {
            $attributeData =& $this->Attributes[$attr];
            return $attributeData;
        }
        else
            return null;
    }

    /*!
     \return \c true if the datatype support insertion of HTTP files or \c false (default) otherwise.

     \sa insertHTTPFile()
    */
    function isHTTPFileInsertionSupported()
    {
        return false;
    }

    /*!
     \return \c true if the datatype support insertion of files or \c false (default) otherwise.

     \sa insertRegularFile()
    */
    function isRegularFileInsertionSupported()
    {
        return false;
    }

    /*!
     \return \c true if the datatype support insertion of simple strings or \c false (default) otherwise.

     \sa insertSimpleString()
    */
    function isSimpleStringInsertionSupported()
    {
        return false;
    }

    /*!
     \virtual
     Inserts the HTTP file \a $httpFile to the content object attribute \a $objectAttribute.

     \param $object The contentobject in which the attribute is contained
     \param $objectVersion The current version of the object it is being worked on
     \param $objectLanguage The current language being worked on
     \param $objectAttribute The attribute which will get the file
     \param $httpFile Object of type eZHTTPFile which contains information on the uploaded file
     \param $mimeData MIME-Type information on the file, can be used to figure out a storage name
     \param[out] $result Array which will be filled with information on the process, it will contain:
                 - errors - Array with error elements, each element is an array with \c 'description' containing the text
                 - require_storage - \c true if the attribute must be stored after this call, or \c false if not required at all

     \return \c true if the file was stored correctly in the attribute or \c false if something failed.
     \note The datatype will return \c null (the default) if does not support HTTP files.
     \note \a $result will not be defined if the return value is \c null
     \note The \a $httpFile must not be stored prior to calling this, the datatype will handle this internally

     \sa isHTTPFileInsertionSupported()
    */
    function insertHTTPFile( &$object, $objectVersion, $objectLanguage,
                             &$objectAttribute, &$httpFile, $mimeData,
                             &$result )
    {
        eZDebug::writeWarning( "The datatype " . get_class( $this ) . " for attribute ID " . $objectAttribute->attribute( 'id' ) . " does not support insertion of HTTP files",
                               'eZDataType::insertHTTPFile' );
        return null;
    }

    /*!
     \virtual
     Inserts the file named \a $filePath to the content object attribute \a $objectAttribute.

     \param $object The contentobject in which the attribute is contained
     \param $objectVersion The current version of the object it is being worked on
     \param $objectLanguage The current language being worked on
     \param $objectAttribute The attribute which will get the file
     \param $filePath Full path including the filename
     \param[out] $result Array which will be filled with information on the process, it will contain:
                 - errors - Array with error elements, each element is an array with \c 'description' containing the text
                 - require_storage - \c true if the attribute must be stored after this call, or \c false if not required at all

     \return \c true if the file was stored correctly in the attribute or \c false if something failed.
     \note The datatype will return \c null (the default) if does not support HTTP files.
     \note \a $result will not be defined if the return value is \c null

     \sa isRegularFileInsertionSupported()
    */
    function insertRegularFile( &$object, $objectVersion, $objectLanguage,
                                &$objectAttribute, $filePath,
                                &$result )
    {
        eZDebug::writeWarning( "The datatype " . get_class( $this ) . " for attribute ID " . $objectAttribute->attribute( 'id' ) . " does not support insertion of regular files",
                               'eZDataType::insertRegularFile' );
        return null;
    }

    /*!
     \virtual
     Inserts the string \a $string to the content object attribute \a $objectAttribute.

     \param $object The contentobject in which the attribute is contained
     \param $objectVersion The current version of the object it is being worked on
     \param $objectLanguage The current language being worked on
     \param $objectAttribute The attribute which will get the file
     \param $filePath Full path including the filename
     \param[out] $result Array which will be filled with information on the process, it will contain:
                 - errors - Array with error elements, each element is an array with \c 'description' containing the text
                 - require_storage - \c true if the attribute must be stored after this call, or \c false if not required at all

     \return \c true if the file was stored correctly in the attribute or \c false if something failed.
     \note The datatype will return \c null (the default) if does not support HTTP files.
     \note \a $result will not be defined if the return value is \c null

     \sa isSimpleStringInsertionSupported()
    */
    function insertSimpleString( &$object, $objectVersion, $objectLanguage,
                                 &$objectAttribute, $string,
                                 &$result )
    {
        eZDebug::writeWarning( "The datatype " . get_class( $this ) . " for attribute ID " . $objectAttribute->attribute( 'id' ) . " does not support insertion of simple strings",
                               'eZDataType::insertSimplestring' );
        return null;
    }

    /*!
     Fetches the product option information for option with ID \a $optionID and returns this information.
     This will be called from the basket when a new product with an option is added, it is then up to the
     specific datatype to return proper data. It will also be used to recalcuate prices.

     \param $objectAttribute The attribute that the datatype controls.
     \param $optionID The ID of the option which information should be returned from.
     \param $productItem The product item object which contains the option, is available for reading only.
     \return An array structure which contains:
             - id - The unique ID of the selected option, this must be unique in the attribute and will later on
                    be used to recalculate prices.
             - name - The name of the option list
             - value - The display string of the selected option
             - additional_price - A value which is added to the total product price, set to 0 or \c false if no price is used.
             If the option could not be found it should return \c false, if not supported it should return \c null.
     \sa handleProductOption
    */
    function productOptionInformation( &$objectAttribute, $optionID, &$productItem )
    {
        eZDebug::writeWarning( "The datatype " . get_class( $this ) . " for attribute ID " . $objectAttribute->attribute( 'id' ) . " does not support product options",
                               'eZDataType::productOptionInformation' );
        return null;
    }

    /*!
     Returns the content data for the given content object attribute.
    */
    function &objectAttributeContent( &$objectAttribute )
    {
        return "";
    }

    /*!
     \return \c true if the datatype finds any content in the attribute \a $contentObjectAttribute.
    */
    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return false;
    }

    /*!
     Returns the content data for the given content class attribute.
    */
    function &classAttributeContent( &$classAttribute )
    {
        return "";
    }

    /*!
     Stores the datatype data to the database which is related to the
     object attribute.
     \return True if the value was stored correctly.
     \note The method is entirely up to the datatype, for instance
           it could reuse the available types in the the attribute or
           store in a separate object.
     \sa fetchObjectAttributeHTTPInput
    */
    function storeObjectAttribute( &$objectAttribute )
    {
    }

    /*!
     Perfoms necessary actions with attribute data after object is published,
     it means that you have access to published nodes.
     \return True if the value was stored correctly.
     \note The method is entirely up to the datatype, for instance
           it could reuse the available types in the the attribute or
           store in a separate object.
    */
    function onPublish( &$contentObjectAttribute, &$contentObject, &$publishedNodes )
    {
    }

    /*!
     Similar to the storeClassAttribute but is called before the
     attribute itself is stored and can be used to set values in the
     class attribute.
     \return True if the value was stored correctly.
     \sa fetchClassAttributeHTTPInput
    */
    function preStoreClassAttribute( &$classAttribute, $version )
    {
    }

    /*!
     Stores the datatype data to the database which is related to the
     class attribute. The \a $version parameter determines which version
     is currently being stored, 0 is the real version while 1 is the
     temporary version.
     \return True if the value was stored correctly.
     \note The method is entirely up to the datatype, for instance
           it could reuse the available types in the the attribute or
           store in a separate object.
     \note This function is called after the attribute data has been stored.
           If you need to alter attribute data use preStoreClassAttribute instead.
     \sa fetchClassAttributeHTTPInput
    */
    function storeClassAttribute( &$classAttribute, $version )
    {
    }


    function storeDefinedClassAttribute( &$classAttribute )
    {
    }

    function preStoreDefinedClassAttribute( &$classAttribute )
    {
        $this->preStoreClassAttribute( $classAttribute, $classAttribute->attribute( 'version' ) );
    }

    /*!
     Validates the input for a class attribute and returns a validation
     state as defined in eZInputValidator.
     \note Default implementation does nothing and returns accepted.
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Tries to do a fixup on the input text so that it's acceptable as
     class attribute input.
     \note Default implementation does nothing and returns accepted.
    */
    function fixupClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the HTTP input for the content class attribute.
     \note Default implementation does nothing.
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
    }

    /*!
     Executes a custom action for a class attribute which was defined on the web page.
     \note Default implementation does nothing.
    */
    function customClassAttributeHTTPAction( &$http, $action, &$classAttribute )
    {
    }

    /*!
     Matches the action against the action name \a $actionName
     and extracts the value from the action puts it into \a $value.
     \return \c true if the action matched and a value was found,
             \c false otherwise.
     \node If no match is made or no value found the \a $value parameter is not modified.
    */
    function fetchActionValue( $action, $actionName, &$value )
    {
        if ( preg_match( "#^" . $actionName . "_(.+)$#", $action, $matches ) )
        {
            $value = $matches[1];
            return true;
        }
        return false;
    }

    /*!
     Validates the input for an object attribute and returns a validation
     state as defined in eZInputValidator.
     \note Default implementation does nothing and returns accepted.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$objectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Tries to do a fixup on the input text so that it's acceptable as
     object attribute input.
     \note Default implementation does nothing.
    */
    function fixupObjectAttributeHTTPInput( &$http, $base, &$objectAttribute )
    {
    }

    /*!
     Fetches the HTTP input for the content object attribute.
     \note Default implementation does nothing.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$objectAttribute )
    {
    }

    /*!
     Validates the input for an object attribute and returns a validation
     state as defined in eZInputValidator.
     \note Default implementation does nothing and returns accepted.
    */
    function validateCollectionAttributeHTTPInput( &$http, $base, &$objectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Tries to do a fixup on the input text so that it's acceptable as
     object attribute input.
     \note Default implementation does nothing.
    */
    function fixupCollectionAttributeHTTPInput( &$http, $base, &$objectAttribute )
    {
    }

    /*!
     Fetches the HTTP collected information for the content object attribute.
     \note Default implementation does nothing.

     \return true if variable was successfully fetched.
    */
    function fetchCollectionAttributeHTTPInput( &$collection, &$collectionAttribute, &$http, $base, &$objectAttribute )
    {
    }

    /*!
     Executes a custom action for an object attribute which was defined on the web page.
     \note Default implementation does nothing.
    */
    function customObjectAttributeHTTPAction( &$http, $action, &$objectAttribute )
    {
    }

    /*!
     Takes care of custom action handling, this means checking if a custom action request
     must be sent to a contentobject attribute. This function is only useful for
     datatypes that must do custom action handling for sub objects and attributes.
     \note Default implementation does nothing.
    */
    function handleCustomObjectHTTPActions( &$http, $attributeDataBaseName,
                                            $customActionAttributeArray, $customActionParameters )
    {
    }

    /*!
     Initializes the class attribute with some data.
     \note Default implementation does nothing.
    */
    function initializeClassAttribute( &$classAttribute )
    {
    }

    /*!
     Clones the date from the old class attribute to the new one.
     \note Default implementation does nothing which is good enough for datatypes which does not use external tables.
    */
    function cloneClassAttribute( &$oldClassAttribute, &$newClassAttribute )
    {
    }

    /*!
     Initializes the object attribute with some data.
     \note Default implementation does nothing.
    */
    function initializeObjectAttribute( &$objectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
    }

    /*!
     Tries to do a repair on the content object attribute \a $contentObjectAttribute and returns \c true if it succeeds.
     \return \c false if it fails or \c null if it is not supported to do a repair.
    */
    function repairContentObjectAttribute( &$contentObjectAttribute )
    {
        return null;
    }

    /*!
     Initializes the object attribute with some data after object attribute is already stored. It means that for initial version you allready have an attribute_id and you can store data somewhere using this id.
     \note Default implementation does nothing.
    */
    function postInitializeObjectAttribute( &$objectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
    }

    /*!
     Clean up stored object attribute
     \note Default implementation does nothing.
    */
    function deleteStoredObjectAttribute( &$objectAttribute, $version = null )
    {
    }

    /*!
     Clean up stored class attribute
     \note Default implementation does nothing.
    */
    function deleteStoredClassAttribute( &$classAttribute, $version = null )
    {
    }

    /*!
     \return the content action(s) which can be performed on object containing
     the current datatype.
    */
    function contentActionList( &$classAttribute )
    {
        $actionList = array();
        if ( is_object( $classAttribute ) )
        {
            if ( $classAttribute->attribute( 'is_information_collector' ) == true )
            {
                $actionList[] = array( 'name' => ezi18n( 'kernel/classes/datatypes', 'Send', 'Datatype information collector action' ),
                                       'action' => 'ActionCollectInformation' );
            }
        }
        else
        {
            eZDebug::writeError( '$classAttribute isn\'t an object.', 'eZDataType::contentActionList' );
        }
        return $actionList;
    }

    /*!
     \return true if the data type can do information collection
    */
    function hasInformationCollection()
    {
        return false;
    }

    /*!
     Returns the title of the current type, this is to form
     the title of the object.
    */
    function title( &$objectAttribute, $name = null )
    {
        return "";
    }

    /*!
     \return true if the datatype can be indexed
    */
    function isIndexable()
    {
        return false;
    }

    /*!
     \return true if the datatype can be used as an information collector
    */
    function isInformationCollector()
    {
        return false;
    }

    /*!
     \return the sort key for the datatype. This is used for sorting on attribute level.
    */
    function sortKey( &$objectAttribute )
    {
        return "";
    }

    /*!
     \returns the type of the sort key int|string
      False is returned if sorting is not supported
    */
    function sortKeyType()
    {
        return false;
    }

    /*!
     \return the text which should be indexed in the search engine. An associative array can
      be returned to enable searching in specific parts of the data. E.g. array( 'first_column' => "foo",
     'second_column' => "bar" );
    */
    function &metaData()
    {
        return "";
    }

    /*!
     Can be called to figure out if a datatype has certain special templates that it relies on.
     This can for instance be used to figure out which override templates to include in a package.
     \return An array with template files that this datatype relies on.
             Each element can be one of the following types:
             - string - The filepath of the template
             - array - Advanced matching criteria, element 0 determines the type:
               - 'regexp' - A regular expression, element 1 is the regexp string (PREG)
             If \c false is returned it means there are no relations to any templates.
     \note All matching is done relative from templates directory in the given design.
     \note The templates that are found in content/datatype/* should not be included.
    */
    function templateList()
    {
        return false;
    }

    /*!
     Adds the necessary dom structure to the attribute parameters.
     \note The default is to add unsupported='true' to the attribute node,
           meaning that the datatype does not support serializing.
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        if ( !$this->Attributes['properties']['serialize_supported'] )
            $attributeNode->appendAttribute( eZDOMDocument::createAttributeNode( 'unsupported', 'true' ) );
    }

    /*!
     Extracts values from the attribute parameters and sets it in the class attribute.
     \note This function is called after the attribute has been stored and a second store is
           called after this function is done.
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
    }

    /*!
     \param package
     \param content attribute

     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        $node = new eZDOMNode();

        $node->setPrefix( 'ezobject' );
        $node->setName( 'attribute' );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $objectAttribute->attribute( 'id' ), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'identifier', $objectAttribute->contentClassAttributeIdentifier(), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', $this->isA() ) );

        if ( $this->Attributes["properties"]['object_serialize_map'] )
        {
            $map = $this->Attributes["properties"]['object_serialize_map'];
            foreach ( $map as $attributeName => $xmlName )
            {
                if ( $objectAttribute->hasAttribute( $attributeName ) )
                {
                    $value = $objectAttribute->attribute( $attributeName );
                    $node->appendChild( eZDOMDocument::createElementTextNode( $xmlName, (string)$value ) );
                }
                else
                {
                    eZDebug::writeError( "The attribute '$attributeName' does not exists for contentobject attribute " . $objectAttribute->attribute( 'id' ),
                                         'eZDataType::serializeContentObjectAttribute' );
                }
            }
        }
        else
        {
            $node->appendChild( eZDOMDocument::createElementTextNode( 'data-int', (string)$objectAttribute->attribute( 'data_int' ) ) );
            $node->appendChild( eZDOMDocument::createElementTextNode( 'data-float', (string)$objectAttribute->attribute( 'data_float' ) ) );
            $node->appendChild( eZDOMDocument::createElementTextNode( 'data-text', $objectAttribute->attribute( 'data_text' ) ) );
        }
        return $node;
    }

    /*!
     Unserailize contentobject attribute

     \param package
     \param contentobject attribute object
     \param ezdomnode object
    */
    function unserializeContentObjectAttribute( &$package, &$objectAttribute, $attributeNode )
    {
        if ( $this->Attributes["properties"]['object_serialize_map'] )
        {
            $map = $this->Attributes["properties"]['object_serialize_map'];
            foreach ( $map as $attributeName => $xmlName )
            {
                if ( $objectAttribute->hasAttribute( $attributeName ) )
                {
                    $value = $attributeNode->elementTextContentByName( $xmlName );
                    if ( $value !== false )
                    {
                        $objectAttribute->setAttribute( $attributeName, $value );
                    }
                    else
                    {
                        eZDebug::writeError( "The xml element '$xmlName' does not exist for contentobject attribute " . $objectAttribute->attribute( 'id' ),
                                             'eZDataType::unserializeContentObjectAttribute' );
                    }
                }
                else
                {
                    eZDebug::writeError( "The attribute '$attributeName' does not exist for contentobject attribute " . $objectAttribute->attribute( 'id' ),
                                         'eZDataType::unserializeContentObjectAttribute' );
                }
            }
        }
        else
        {
            $objectAttribute->setAttribute( 'data_int', (int)$attributeNode->elementTextContentByName( 'data-int' ) );
            $objectAttribute->setAttribute( 'data_float', (float)$attributeNode->elementTextContentByName( 'data-float' ) );
            $objectAttribute->setAttribute( 'data_text', $attributeNode->elementTextContentByName( 'data-text' ) );
        }
    }

    function allowedTypes()
    {
        $allowedTypes =& $GLOBALS["eZDataTypeAllowedTypes"];
        if ( !is_array( $allowedTypes ) )
        {
            $contentINI =& eZINI::instance( 'content.ini' );
            $dataTypes = $contentINI->variable( 'DataTypeSettings', 'AvailableDataTypes' );
            $allowedTypes = array_unique( $dataTypes );
        }
        return $allowedTypes;
    }

    function loadAndRegisterAllTypes()
    {
        $allowedTypes =& eZDataType::allowedTypes();
        foreach( $allowedTypes as $type )
        {
            eZDataType::loadAndRegisterType( $type );
        }
    }

    function loadAndRegisterType( $type )
    {
        $types =& $GLOBALS["eZDataTypes"];
        if ( isset( $types[$type] ) )
        {
            return false;
        }

        include_once( 'lib/ezutils/classes/ezextension.php' );
        $baseDirectory = eZExtension::baseDirectory();
        $contentINI =& eZINI::instance( 'content.ini' );

        $extensionDirectories = $contentINI->variable( 'DataTypeSettings', 'ExtensionDirectories' );
        $extensionDirectories = array_unique( $extensionDirectories );
        $repositoryDirectories = $contentINI->variable( 'DataTypeSettings', 'RepositoryDirectories' );

        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/datatypes';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }
        $foundEventType = false;
        $repositoryDirectories = array_unique( $repositoryDirectories );
        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/$type/" . $type . "type.php";
            if ( file_exists( $includeFile ) )
            {
                $foundEventType = true;
                break;
            }
        }
        if ( !$foundEventType )
        {
            eZDebug::writeError( "Datatype not found: $type, searched in these directories: " . implode( ', ', $repositoryDirectories ), "eZDataType::loadAndRegisterType" );
            return false;
        }
        include_once( $includeFile );
        return true;
    }

    /// \privatesection
    /// The datatype string ID, used for uniquely identifying a datatype
    var $DataTypeString;
    /// The descriptive name of the datatype, usually used for displaying to the user
    var $Name;
}

?>
