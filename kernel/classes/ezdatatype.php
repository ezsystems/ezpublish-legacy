<?php
//
// Definition of eZDataType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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
        if ( isset( $properties['translation_allowed'] ) )
            $translationAllowed = $properties['translation_allowed'];

        $this->Attributes = array();
        $this->Attributes["is_indexable"] = $this->isIndexable();
        $this->Attributes["is_information_collector"] = $this->isInformationCollector();

        $this->Attributes["information"] = array( "string" => $this->DataTypeString,
                                                  "name" => $this->Name );
        $this->Attributes["properties"] = array( "translation_allowed" => $translationAllowed );
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
        foreach ( $types as $dataTypeString => $className )
        {
            $def =& $type_objects[$dataTypeString];
            if ( get_class( $def ) != $className )
                $def = new $className();
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
            return $this->Attributes[$attr];
        else
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
     Stores the datatype data to the database which is related to the
     class attribute. The \a $version parameter determines which version
     is currently being stored, 0 is the real version while 1 is the
     temporary version.
     \return True if the value was stored correctly.
     \note The method is entirely up to the datatype, for instance
           it could reuse the available types in the the attribute or
           store in a separate object.
     \sa fetchClassAttributeHTTPInput
    */
    function storeClassAttribute( &$classAttribute, $version )
    {
    }

    function storeDefinedClassAttribute( &$classAttribute )
    {
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
     Executes a custom action for an object attribute which was defined on the web page.
     \note Default implementation does nothing.
    */
    function customObjectAttributeHTTPAction( &$http, $action, &$objectAttribute )
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
     Initializes the object attribute with some data.
     \note Default implementation does nothing.
    */
    function initializeObjectAttribute( &$objectAttribute, $currentVersion )
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
        return array();
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
    function title(  &$objectAttribute, $name = null )
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
     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( &$objectAttribute )
    {
        $node =& $this->contentObjectAttributeDOMNode( $objectAttribute );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'unsupported', 'true' ) );
        return $node;
    }

    /*!
     \return a DOM representation of the content object attribute
    */
    function &contentObjectAttributeDOMNode( &$objectAttribute )
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );

        $node = new eZDOMNode();

        $node->setPrefix( 'ezobject' );
        $node->setName( 'attribute' );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $objectAttribute->attribute( 'id' ), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'identifier', $objectAttribute->contentClassAttributeIdentifier(), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', $this->isA() ) );
        return $node;
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
            eZDebug::writeError( "Datatype already registered: $type", "eZDataType::loadAndRegisterType" );
            return false;
        }

        include_once( 'kernel/classes/ezextension.php' );
        $baseDirectory = eZExtension::baseDirectory();
        $contentINI =& eZINI::instance( 'content.ini' );
        $repositoryDirectories = $contentINI->variable( 'DataTypeSettings', 'RepositoryDirectories' );
        $extensionDirectories = $contentINI->variable( 'DataTypeSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/datatypes';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }
        $foundEventType = false;
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

// include_once( "kernel/classes/datatypes/ezstring/ezstringtype.php" );
// include_once( "kernel/classes/datatypes/eztext/eztexttype.php" );
// include_once( "kernel/classes/datatypes/ezinteger/ezintegertype.php" );

// include defined datatypes

eZDataType::loadAndRegisterAllTypes();

// $contentINI =& eZINI::instance( 'content.ini' );
// $availableTypes =& $contentINI->variable( "DataTypeSettings", "AvailableDataTypes" );

// foreach ( $availableTypes as $type )
// {
//     $includeFile = "kernel/classes/datatypes/" . $type . "/" . $type ."type.php";
//     if ( file_exists( $includeFile ) )
//     {
//         include_once( $includeFile );
//     }
//     else
//     {
//         eZDebug::writeError( "Class type: $includeFile not found " );
//     }

// }

?>
