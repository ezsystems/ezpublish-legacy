<?php
//
// Definition of eZDataType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*! \defgroup eZDataType Content datatypes */

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
  system and should have a prefix, for instance we in eZ Systems use ez as our prefix.
*/

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
    function viewTemplate( $contentobjectAttribute )
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
    function editTemplate( $contentobjectAttribute )
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
    function informationTemplate( $contentobjectAttribute )
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
    function resultTemplate( &$collectionAttribute )
    {
        return $this->DataTypeString;
    }

    /*!
     \static
     Crates a datatype instance of the datatype string id \a $dataTypeString.
     \note It only creates one instance for each datatype.
    */
    static function create( $dataTypeString )
    {
        $def = null;
        if ( !isset( $GLOBALS["eZDataTypes"][$dataTypeString] ) )
        {
            eZDataType::loadAndRegisterType( $dataTypeString );
        }

        if ( isset( $GLOBALS['eZDataTypes'][$dataTypeString] ) )
        {
            $className = $GLOBALS['eZDataTypes'][$dataTypeString];

            if ( !isset( $GLOBALS["eZDataTypeObjects"][$dataTypeString] ) ||
                 get_class( $GLOBALS["eZDataTypeObjects"][$dataTypeString] ) != $className )
            {
                $GLOBALS["eZDataTypeObjects"][$dataTypeString] = new $className();
            }
            return $GLOBALS["eZDataTypeObjects"][$dataTypeString];
        }

        return null;
    }

    /*!
     \static
     \return a list of datatypes which has been registered.
     \note This will instantiate all datatypes.
    */
    static function registeredDataTypes()
    {
        $types = isset( $GLOBALS["eZDataTypes"] ) ? $GLOBALS["eZDataTypes"] : null;
        if ( isset( $types ) )
        {
            foreach ( $types as $dataTypeString => $className )
            {
                if ( !isset( $GLOBALS["eZDataTypeObjects"][$dataTypeString] ) )
                {
                    $GLOBALS["eZDataTypeObjects"][$dataTypeString] = new $className();
                }
            }
            uasort( $GLOBALS["eZDataTypeObjects"],
                    create_function( '$a, $b',
                                     'return strcmp( $a->Name, $b->Name);' ) );
        return $GLOBALS["eZDataTypeObjects"];
        }
        return null;
    }

    /*!
     \static
     Registers the datatype with string id \a $dataTypeString and
     class name \a $className. The class name is used for instantiating
     the class and should be in lowercase letters.
    */
    static function register( $dataTypeString, $className )
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
    function attributes()
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
    function attribute( $attr )
    {
        if ( isset( $this->Attributes[$attr] ) )
        {
            return $this->Attributes[$attr];
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", 'eZDataType::attribute' );
        $attributeData = null;
        return $attributeData;
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
    function insertHTTPFile( $object, $objectVersion, $objectLanguage,
                             $objectAttribute, $httpFile, $mimeData,
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
    function insertRegularFile( $object, $objectVersion, $objectLanguage,
                                $objectAttribute, $filePath,
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
    function insertSimpleString( $object, $objectVersion, $objectLanguage,
                                 $objectAttribute, $string,
                                 &$result )
    {
        eZDebug::writeWarning( "The datatype " . get_class( $this ) . " for attribute ID " . $objectAttribute->attribute( 'id' ) . " does not support insertion of simple strings",
                               'eZDataType::insertSimplestring' );
        return null;
    }

    /*!
     \virtual
     Checks if the datatype supports returning file information.

     \param $object The contentobject in which the attribute is contained
     \param $objectVersion The current version of the object it is being worked on
     \param $objectLanguage The current language being worked on
     \param $objectAttribute The attribute which stored the file

     \return \c true if file information is supported or \c false if it doesn't.
    */
    function hasStoredFileInformation( $object, $objectVersion, $objectLanguage,
                                       $objectAttribute )
    {
        return false;
    }

    /*!
     \virtual
     This function is called when someone tries to download the file.

     \param $object The contentobject in which the attribute is contained
     \param $objectVersion The current version of the object it is being worked on
     \param $objectLanguage The current language being worked on
     \param $objectAttribute The attribute which stored the file

     \return \c true if any action has been don or \c false if hasn't.
    */
    function handleDownload( $object, $objectVersion, $objectLanguage,
                             $objectAttribute )
    {
        return false;
    }

    /*!
     \virtual
     Returns file information for the filed stored by the attribute.

     \param $object The contentobject in which the attribute is contained
     \param $objectVersion The current version of the object it is being worked on
     \param $objectLanguage The current language being worked on
     \param $objectAttribute The attribute which stored the file

     \return An array structure with information or \c false (default) if no
             information could be found.
             The structure must contain:
             - filepath - The full path to the file

             The structure can contain:
             - filename - The name of the file, if not supplied it will
                           be figured out from the filepath
             - filesize - The size of the file, if not supplied it will
                           be figured out from the filepath
             - mime_type - The MIME type for the file, if not supplied it will
                           be figured out from the filepath
    */
    function storedFileInformation( $object, $objectVersion, $objectLanguage,
                                    $objectAttribute )
    {
        return false;
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
    function productOptionInformation( $objectAttribute, $optionID, $productItem )
    {
        eZDebug::writeWarning( "The datatype " . get_class( $this ) . " for attribute ID " . $objectAttribute->attribute( 'id' ) . " does not support product options",
                               'eZDataType::productOptionInformation' );
        return null;
    }

    /*!
      \virtual
      Will return information on how the datatype should be represented in
      the various display modes when used by an object.

      If this method is reimplemented the implementor must call this method
      with the new info array as second parameter.

      \param $objectAttribute The content object attribute to return info for.
      \param $mergeInfo A structure that must match the returned array, or \c false to ignore.
                        Any entries here will override the default.
      \return An array structure which contains:
              - \c edit
                - \c grouped_input - If \c true then the datatype has lots of input elements
                                     that should be grouped. (e.g. in a fieldset)
                                     EditSettings/GroupedInput in datatype.ini is used to
                                     automatically determine this field
                .
              - \c view
              - \c collection
                - \c grouped_input - If \c true then the datatype has lots of input elements
                                     that should be grouped. (e.g. in a fieldset)
                                     CollectionSettings/GroupedInput in datatype.ini is used to
                                     automatically determine this field and will override
                                     the default and datatype setting if used.
                .
              - \c result
    */
    function objectDisplayInformation( $objectAttribute, $mergeInfo = false )
    {
        $datatype = $objectAttribute->attribute( 'data_type_string' );
        $ini = eZINI::instance( 'datatype.ini' );
        $editGrouped = in_array( $datatype, $ini->variable( 'EditSettings', 'GroupedInput' ) );
        $viewGrouped = in_array( $datatype, $ini->variable( 'ViewSettings', 'GroupedInput' ) );
        $resultGrouped = in_array( $datatype, $ini->variable( 'ResultSettings', 'GroupedInput' ) );
        $collectionGrouped = in_array( $datatype, $ini->variable( 'CollectionSettings', 'GroupedInput' ) );

        $info = array( 'edit' => array( 'grouped_input' => false ),
                       'view' => array( 'grouped_input' => false),
                       'collection' => array( 'grouped_input' => false ),
                       'result' => array( 'grouped_input' => false ) );
        $override = array();
        if ( $editGrouped )
            $override['edit']['grouped_input'] = true;
        if ( $collectionGrouped )
            $override['collection']['grouped_input'] = true;
        if ( $viewGrouped )
            $override['view']['grouped_input'] = true;
        if ( $resultGrouped )
            $override['result']['grouped_input'] = true;

        if ( $mergeInfo )
        {
            // All entries in $mergeInfo will override the defaults
            foreach ( array( 'edit', 'view', 'collection', 'result' ) as $view )
            {
                if ( isset( $mergeInfo[$view] ) )
                    $info[$view] = array_merge( $info[$view], $mergeInfo[$view] );
                if ( isset( $override[$view] ) )
                    $info[$view] = array_merge( $info[$view], $override[$view] );
            }
        }
        else
        {
            // All entries in $override will override the defaults
            foreach ( array( 'edit', 'view', 'collection', 'result' ) as $view )
            {
                if ( isset( $override[$view] ) )
                    $info[$view] = array_merge( $info[$view], $override[$view] );
            }
        }
        return $info;
    }

    /*!
      \virtual
      Will return information on how the datatype should be represented in
      the various display modes when used by a class.

      If this method is reimplemented the implementor must call this method
      with the new info array as second parameter.

      \param $classAttribute The content class attribute to return info for.
      \param $mergeInfo A structure that must match the returned array, or \c false to ignore.
                        Any entries here will override the default.
      \return An array structure which contains:
              - \c edit
                - \c grouped_input - If \c true then the datatype has lots of input elements
                                     that should be grouped. (e.g. in a fieldset)
                                     ClassEditSettings/GroupedInput in datatype.ini is used to
                                     automatically determine this field and will override
                                     the default and datatype setting if used.
                .
              - \c view
    */
    function classDisplayInformation( $classAttribute, $mergeInfo = false )
    {
        $datatype = $classAttribute->attribute( 'data_type_string' );
        $ini = eZINI::instance( 'datatype.ini' );
        $editGrouped = in_array( $datatype, $ini->variable( 'ClassEditSettings', 'GroupedInput' ) );

        $info = array( 'edit' => array( 'grouped_input' => false ),
                       'view' => array() );
        $override = array();
        if ( $editGrouped )
            $override['edit']['grouped_input'] = true;

        if ( $mergeInfo )
        {
            // All entries in $mergeInfo will override the defaults
            foreach ( array( 'edit', 'view' ) as $view )
            {
                if ( isset( $mergeInfo[$view] ) )
                    $info[$view] = array_merge( $info[$view], $mergeInfo[$view] );
                if ( isset( $override[$view] ) )
                    $info[$view] = array_merge( $info[$view], $override[$view] );
            }
        }
        else
        {
            // All entries in $override will override the defaults
            foreach ( array( 'edit', 'view' ) as $view )
            {
                if ( isset( $override[$view] ) )
                    $info[$view] = array_merge( $info[$view], $override[$view] );
            }
        }
        return $info;
    }

    /*!
     Returns the content data for the given content object attribute.
    */
    function objectAttributeContent( $objectAttribute )
    {
        $retValue = '';
        return $retValue;
    }

    /*!
     \return \c true if the datatype finds any content in the attribute \a $contentObjectAttribute.
    */
    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return false;
    }

    /*!
     Returns the content data for the given content class attribute.
    */
    function classAttributeContent( $classAttribute )
    {
        return '';
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
    function storeObjectAttribute( $objectAttribute )
    {
    }

    /*!
     Performs necessary actions with attribute data after object is published,
     it means that you have access to published nodes.
     \return True if the value was stored correctly.
     \note The method is entirely up to the datatype, for instance
           it could reuse the available types in the the attribute or
           store in a separate object.

     \note Might be transaction unsafe.
    */
    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
    }

    /*!
     Similar to the storeClassAttribute but is called before the
     attribute itself is stored and can be used to set values in the
     class attribute.
     \return True if the value was stored correctly.
     \sa fetchClassAttributeHTTPInput
    */
    function preStoreClassAttribute( $classAttribute, $version )
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
    function storeClassAttribute( $classAttribute, $version )
    {
    }


    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function storeDefinedClassAttribute( $classAttribute )
    {
    }

    function preStoreDefinedClassAttribute( $classAttribute )
    {
        $this->preStoreClassAttribute( $classAttribute, $classAttribute->attribute( 'version' ) );
    }

    /*!
     Validates the input for a class attribute and returns a validation
     state as defined in eZInputValidator.
     \note Default implementation does nothing and returns accepted.
    */
    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Tries to do a fixup on the input text so that it's acceptable as
     class attribute input.
     \note Default implementation does nothing and returns accepted.
    */
    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches the HTTP input for the content class attribute.
     \note Default implementation does nothing.
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
    }

    /*!
     Executes a custom action for a class attribute which was defined on the web page.
     \note Default implementation does nothing.
    */
    function customClassAttributeHTTPAction( $http, $action, $classAttribute )
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
    function validateObjectAttributeHTTPInput( $http, $base, $objectAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Tries to do a fixup on the input text so that it's acceptable as
     object attribute input.
     \note Default implementation does nothing.
    */
    function fixupObjectAttributeHTTPInput( $http, $base, $objectAttribute )
    {
    }

    /*!
     Fetches the HTTP input for the content object attribute.
     \note Default implementation does nothing.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $objectAttribute )
    {
    }

    /*!
     Validates the input for an object attribute and returns a validation
     state as defined in eZInputValidator.
     \note Default implementation does nothing and returns accepted.
    */
    function validateCollectionAttributeHTTPInput( $http, $base, $objectAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Tries to do a fixup on the input text so that it's acceptable as
     object attribute input.
     \note Default implementation does nothing.
    */
    function fixupCollectionAttributeHTTPInput( $http, $base, $objectAttribute )
    {
    }

    /*!
     Fetches the HTTP collected information for the content object attribute.
     \note Default implementation does nothing.

     \return true if variable was successfully fetched.
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $objectAttribute )
    {
    }

    /*!
     Executes a custom action for an object attribute which was defined on the web page.
     \note Default implementation does nothing.
    */
    function customObjectAttributeHTTPAction( $http, $action, $objectAttribute, $parameters )
    {
    }

    /*!
     Takes care of custom action handling, this means checking if a custom action request
     must be sent to a contentobject attribute. This function is only useful for
     datatypes that must do custom action handling for sub objects and attributes.
     \note Default implementation does nothing.
    */
    function handleCustomObjectHTTPActions( $http, $attributeDataBaseName,
                                            $customActionAttributeArray, $customActionParameters )
    {
    }

    /*!
     Initializes the class attribute with some data.
     \note Default implementation does nothing.
    */
    function initializeClassAttribute( $classAttribute )
    {
    }

    /*!
     Clones the date from the old class attribute to the new one.
     \note Default implementation does nothing which is good enough for datatypes which does not use external tables.
    */
    function cloneClassAttribute( $oldClassAttribute, $newClassAttribute )
    {
    }

    /*!
     Initializes the object attribute with some data.
     \note Default implementation does nothing.
    */
    function initializeObjectAttribute( $objectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
    }

    /*!
     Tries to do a repair on the content object attribute \a $contentObjectAttribute and returns \c true if it succeeds.
     \return \c false if it fails or \c null if it is not supported to do a repair.
    */
    function repairContentObjectAttribute( $contentObjectAttribute )
    {
        return null;
    }

    /*!
     Initializes the object attribute with some data after object attribute is already stored. It means that for initial version you allready have an attribute_id and you can store data somewhere using this id.
     \note Default implementation does nothing.
    */
    function postInitializeObjectAttribute( $objectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
    }

    /*
     Makes some post-store operations. Called by framework after store of eZContentObjectAttribute object.
    */
    function postStore( $objectAttribute )
    {
    }

    /*!
     Clean up stored object attribute
     \note Default implementation does nothing.
    */
    function deleteStoredObjectAttribute( $objectAttribute, $version = null )
    {
    }

    /*!
     Clean up stored class attribute
     \note Default implementation does nothing.
    */
    function deleteStoredClassAttribute( $classAttribute, $version = null )
    {
    }

    /*!
     \return the content action(s) which can be performed on object containing
     the current datatype.
    */
    function contentActionList( $classAttribute )
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
    function title( $objectAttribute, $name = null )
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
     \return true if the datatype requires validation during add to basket procedure
    */
    function isAddToBasketValidationRequired()
    {
        return false;
    }
    /*!
     Validates the input for an object attribute during add to basket process
     and returns a validation state as defined in eZInputValidator.
     \note Default implementation does nothing and returns accepted.
    */
    function validateAddToBasket( $objectAttribute, $data, &$errors )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Queries the datatype if the attribute containing this datatype can be
     removed from the class. This can be used by datatypes to ensure
     that very important datatypes that could cause system malfunction is
     not removed.
     The datatype will only need to reimplemented this if it wants to
     do some checking, the default returns \c true.

     \return \c true if the class attribute can be removed or \c false.
     \sa classAttributeRemovableInformation()
     \note The default code will call classAttributeRemovableInformation with
          $includeAll set to \c false, if it returns false or an empty \c 'list'
          it will return \c true.
    */
    function isClassAttributeRemovable( $classAttribute )
    {
        $info = $this->classAttributeRemovableInformation( $classAttribute, false );
        return ( $info === false or count( $info['list'] ) == 0 );
    }

    /*!
     If the call to isClassAttributeRemovable() returns \c false then this
     can be called to figure out why it cannot be removed, e.g to give
     information to the user.
     \return An array structure with information, or \c false if no info is available
             - text - Plain text explaining why it can't be removed
             - list - A list of reasons with details on why it can be removed
                      - identifier - The identifier of the reason (optional)
                      - text - Plain text explaning the reason
     \param $includeAll Controls whether the returned information will contain all
                        sources for not being to remove or just the first that it finds.
    */
    function classAttributeRemovableInformation( $classAttribute, $includeAll = true )
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
    function sortKey( $objectAttribute )
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

    function customSorting()
    {
        return false;
    }

    function customSortingSQL( $params )
    {
        return false;
    }

    /*!
     \return the text which should be indexed in the search engine. An associative array can
      be returned to enable searching in specific parts of the data. E.g. array( 'first_column' => "foo",
     'second_column' => "bar" );
    */
    function metaData( $contentObjectAttribute )
    {
        return '';
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export
     */
    function toString( $objectAttribute )
    {
        return '';
    }
    function fromString( $objectAttribute, $string )
    {
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
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        if ( !$this->Attributes['properties']['serialize_supported'] )
            $attributeNode->setAttribute( 'unsupported', 'true' );
    }

    /*!
     Extracts values from the attribute parameters and sets it in the class attribute.
     \note This function is called after the attribute has been stored and a second store is
           called after this function is done.
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
    }

    /*!
     \param package
     \param objectAttribute content attribute

     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );

        $node = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:attribute' );

        $node->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:id', $objectAttribute->attribute( 'id' ) );
        $node->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:identifier', $objectAttribute->contentClassAttributeIdentifier() );
        $node->setAttribute( 'name', $objectAttribute->contentClassAttributeName() );
        $node->setAttribute( 'type', $this->isA() );

        if ( $this->Attributes["properties"]['object_serialize_map'] )
        {
            $map = $this->Attributes["properties"]['object_serialize_map'];
            foreach ( $map as $attributeName => $xmlName )
            {
                if ( $objectAttribute->hasAttribute( $attributeName ) )
                {
                    $value = $objectAttribute->attribute( $attributeName );
                    unset( $attributeNode );
                    $attributeNode = $dom->createElement( $xmlName );
                    $attributeNode->appendChild( $dom->createTextNode( (string)$value ) );
                    $node->appendChild( $attributeNode );
                }
                else
                {
                    eZDebug::writeError( "The attribute '$attributeName' does not exist for contentobject attribute " . $objectAttribute->attribute( 'id' ),
                                         'eZDataType::serializeContentObjectAttribute' );
                }
            }
        }
        else
        {
            $dataIntNode = $dom->createElement( 'data-int' );
            $dataIntNode->appendChild( $dom->createTextNode( (string)$objectAttribute->attribute( 'data_int' ) ) );
            $node->appendChild( $dataIntNode );
            $dataFloatNode = $dom->createElement( 'data-float' );
            $dataFloatNode->appendChild( $dom->createTextNode( (string)$objectAttribute->attribute( 'data_float' ) ) );
            $node->appendChild( $dataFloatNode );
            $dataTextNode = $dom->createElement( 'data-text' );
            $dataTextNode->appendChild( $dom->createTextNode( $objectAttribute->attribute( 'data_text' ) ) );
            $node->appendChild( $dataTextNode );
        }
        return $node;
    }

    /*!
     Unserialize contentobject attribute

     \param package
     \param objectAttribute contentobject attribute object
     \param attributeNode ezdomnode object
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        if ( $this->Attributes["properties"]['object_serialize_map'] )
        {
            $map = $this->Attributes["properties"]['object_serialize_map'];
            foreach ( $map as $attributeName => $xmlName )
            {
                if ( $objectAttribute->hasAttribute( $attributeName ) )
                {
                    $elements = $attributeNode->getElementsByTagName( $xmlName );
                    if ( $elements->length !== 0 )
                    {
                        $value = $elements->item( 0 )->textContent;
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
            $objectAttribute->setAttribute( 'data_int', (int)$attributeNode->getElementsByTagName( 'data-int' )->item( 0 )->textContent );
            $objectAttribute->setAttribute( 'data_float', (float)$attributeNode->getElementsByTagName( 'data-float' )->item( 0 )->textContent );
            $objectAttribute->setAttribute( 'data_text', $attributeNode->getElementsByTagName( 'data-text' )->item( 0 )->textContent );
        }
    }

    /*
        Post unserialize. Called after all related objects are created.
        \return true means that attribute has been modified and should be stored
    */
    function postUnserializeContentObjectAttribute( $package, $objectAttribute )
    {
        return false;
    }

    static function allowedTypes()
    {
        $allowedTypes =& $GLOBALS["eZDataTypeAllowedTypes"];
        if ( !is_array( $allowedTypes ) )
        {
            $contentINI = eZINI::instance( 'content.ini' );
            $dataTypes = $contentINI->variable( 'DataTypeSettings', 'AvailableDataTypes' );
            $allowedTypes = array_unique( $dataTypes );
        }
        return $allowedTypes;
    }

    static function loadAndRegisterAllTypes()
    {
        $allowedTypes = eZDataType::allowedTypes();
        foreach( $allowedTypes as $type )
        {
            eZDataType::loadAndRegisterType( $type );
        }
    }

    static function loadAndRegisterType( $type )
    {
        $types =& $GLOBALS["eZDataTypes"];
        if ( isset( $types[$type] ) )
        {
            return false;
        }

        $baseDirectory = eZExtension::baseDirectory();
        $contentINI = eZINI::instance( 'content.ini' );

        $extensionDirectories = $contentINI->variable( 'DataTypeSettings', 'ExtensionDirectories' );
        $extensionDirectories = array_unique( $extensionDirectories );
        $repositoryDirectories = $contentINI->variable( 'DataTypeSettings', 'RepositoryDirectories' );
        $triedDirectories = $repositoryDirectories;

        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/datatypes';
            $triedDirectories[] = $extensionPath;
            if ( file_exists( $extensionPath ) )
            {
                $repositoryDirectories[] = $extensionPath;
            }
            else
            {
                eZDebug::writeWarning( "Extension '$extensionDirectory' does not have the subdirectory 'datatypes'\n" .
                                       "Looked for directory '" . $extensionPath . "'\n" .
                                       "Cannot look for datatype '$type' in this extension." );
            }
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
            eZDebug::writeError( "Datatype not found: '$type', searched in these directories: " . implode( ', ', $triedDirectories ), "eZDataType::loadAndRegisterType" );
            return false;
        }
        include_once( $includeFile );
        return true;
    }

    /*!
     Removes objects with given ID from the relations list
     \note Default implementation does nothing.
    */
    function removeRelatedObjectItem( $contentObjectAttribute, $objectID )
    {
    }

    /*!
    Fixes objects with given ID in the relations list according to what is done with object
     \note Default implementation does nothing.
    */
    function fixRelatedObjectItem( $contentObjectAttribute, $objectID, $mode )
    {
    }
    /**
     * Create empty content object attribute DOM node.
     *
     * The result is intended to be used in a datatype's
     * serializeContentObjectAttribute() method.
     *
     * \return "Empty" DOM node
     */
    function createContentObjectAttributeDOMNode( $objectAttribute )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );

        $node = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:attribute' );

        $node->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:id', $objectAttribute->attribute( 'id' ) );
        $node->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:identifier', $objectAttribute->contentClassAttributeIdentifier() );
        $node->setAttribute( 'name', $objectAttribute->contentClassAttributeName() );
        $node->setAttribute( 'type', $this->isA() );

        return $node;
    }

    /*!
      Method used by content diff system to retrieve changes in attributes.
      This method implements the default behaviour, which is to show old and
      new version values of the object.
    */
    function diff( $old, $new, $options = false )
    {
        $diff = new eZDiff();
        $diff->setDiffEngineType( $diff->engineType( 'container' ) );
        $diff->initDiffEngine();
        $diffObject = $diff->diff( $old, $new );
        return $diffObject;
    }

    /*!
      Returns dba-data file name of the specific datatype.
      This one is the default dba-data file name for all datatypes
    */
    function getDBAFileName()
    {
        return 'share/db_data.dba';
    }

    /*!
      Returns dba-data file path (relative to the system root folder) for the specific datatype.
    */
    function getDBAFilePath( $checkExtensions = true )
    {
         $fileName = 'kernel/classes/datatypes/' . $this->DataTypeString . '/' . $this->getDBAFileName();
         if ( $checkExtensions === true && !file_exists( $fileName ) )
         {
             $fileName = $this->getDBAExtensionFilePath();
         }
         return $fileName;
    }

    /*!
      Returns dba-data file extension path (relative to the system root folder) for the specific datatype.
      \return the first path that is found for the datatype. If not found, it will return false.
    */
    function getDBAExtensionFilePath()
    {
        $activeExtensions = eZExtension::activeExtensions();
        $dataTypeString = $this->DataTypeString;
        $dbaFileName = $this->getDBAFileName();
        $fileName = false;
        foreach ( $activeExtensions as $activeExtension )
        {
            $extesionFileName = eZExtension::baseDirectory() . '/' . $activeExtension .
                                '/datatypes/' . $dataTypeString . '/' . $dbaFileName;
            if ( file_exists( $extesionFileName ) )
            {
                $fileName = $extesionFileName;
                break;
            }
        }
        return $fileName;
    }

    /*!
      Used by setup-wizard to update database data using per datatype dba file
      which is usually placed in share subfolder of the datatype and (share/db_data.dba)
      Any reimplementation of this method must return true if import is succesfully done,
      otherwise false.
    */
    function importDBDataFromDBAFile( $dbaFilePath = false )
    {
        // If no file path is passed then get the common dba-data file name for the datatype
        if ( $dbaFilePath === false )
            $dbaFilePath = $this->getDBAFilePath();

        $fileExist = true;
        if ( !file_exists( $dbaFilePath ) )
        {
            $fileExist = false;
        }
        $result = true;
        if ( $fileExist === true )
        {
            $dataArray = eZDbSchema::read( $dbaFilePath, true );
            if ( is_array( $dataArray ) and count( $dataArray ) > 0 )
            {
                $db = eZDB::instance();
                $dataArray['type'] = strtolower( $db->databaseName() );
                $dataArray['instance'] =& $db;
                $dbSchema = eZDbSchema::instance( $dataArray );

                $result = false;
                if ( $dbSchema )
                {
                    // Before adding the schema, make sure that the tables are empty.
                    if ( $this->cleanDBDataBeforeImport() )
                    {
                        // This will insert the data and
                        // run any sequence value correction SQL if required
                        $result = $dbSchema->insertSchema( array( 'schema' => false,
                                                                  'data' => true ) );
                    }
                }
            }
        }
        else
        {
            $result = false;
        }
        return $result;
    }

    /*!
      \private
      Used by updateDBDataByDBAFile() method
      Must return true if successfull, or false otherwise.
    */
    function cleanDBDataBeforeImport()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        return array();
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return false;
    }

    /// \privatesection
    /// The datatype string ID, used for uniquely identifying a datatype
    public $DataTypeString;
    /// The descriptive name of the datatype, usually used for displaying to the user
    public $Name;
}

?>
