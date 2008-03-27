<?php
//
// Created on: <01-Jun-2007 15:00:00 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezsiteinstaller.php
*/

/*!
  \class eZSiteInstaller ezsiteinstaller.php
  \ingroup eZUtils
  \brief A set of functions to do web-site installation from command-line.

  Helps simplify installation process by providing a set of steps
  which will be executed sequentially. This class contains a common functions
  which can be reused in particular installtions.

*/

//include_once( 'kernel/classes/ezcontentobjectattribute.php' );
//include_once( 'kernel/classes/ezcontentclassattribute.php' );
//include_once( 'kernel/classes/ezrole.php' );

class eZSiteInstaller
{
    const ERR_OK = 0;
    const ERR_ABORT = 1;
    const ERR_CONTINUE = 2;

    function eZSiteInstaller( $parameters = false )
    {
        $this->initSettings( $parameters );
        $this->initSteps();

        $this->LastErrorCode = eZSiteInstaller::ERR_OK;
    }

    function &instance( $params )
    {
        eZDebug::writeWarning( "Your installer doesn't implement 'instance' function", "eZSiteInstaller::instance" );
        return false;
    }

    /*!
     Initialize $Settings member with custom values which can be used
     in install steps.
    */
    function initSettings( $parameters )
    {
        eZDebug::writeWarning( "Your installer doesn't implement 'initSettings' function", "eZSiteInstaller::initSettings" );
    }

    /*!
     Define install steps
     Step definition:
         array( '_function' => <function_name>,
                '_params' => array( <param_list> ) )
         where '_function' defines function name(which should be implemented within installer class) which will be called,
               '_params' is an array of parameters which will be passed to function <function_name>. Note that items of <param_list>
               can be defined in the form as <step definition> and this rule can be applied recursively.

     Each function used in step definition should be defined as:
     function someFunction( $params )
     {
        ...
        // you code goes here
        ...

        // optionally you can set $LastErrorCode to 'eZSiteInstaller::ERR_ABORT' if you want to break installation if some step is failed.
        // $this->setLastErrorCode( eZSiteInstaller::ERR_ABORT );
     }

     Example:
         array( '_function' => 'step_1',
                '_params' => array( 'param_a' => 'foo',
                                    'param_b' => array( '_function' => 'helper_function_for_b',
                                                        '_params' => array( 'param_b_a' => 'goo',
                                                                            'param_b_b' => array( '_function' => 'helper_functin_for_b_b',
                                                                                                  '_params' => array( 'param_b_b_a' => 'b_b_a' ) ) ) ) ) );
         Execution flow:
         - $b_b = helper_function_b_b( array( 'param_b_b_a' => 'b_b_a' ) );
         - $b = helper_function_for_b( array( 'param_b_a' => 'goo',
                                              'param_b_b' => $b_b ) );
         - $result = step_1( array( 'param_a' => 'foo',
                                    'param_b' => $b ) );
    */
    function initSteps()
    {
        eZDebug::writeWarning( "Your installer doesn't implement 'initSteps' function", "eZSiteInstaller::initSteps" );
    }

    /*!
     Add setting with $name and $value.
    */
    function addSetting( $name, $value )
    {
        $this->Settings[$name] = $value;
    }

    /*!
     Return value for setting $name.
    */
    function setting( $name )
    {
        $value = false;
        if( $this->hasSetting( $name ) )
        {
            $value = $this->Settings[$name];
        }
        else
        {
            eZDebug::writeWarning( "Setting '$name' doesn't exist", "eZSiteInstaller::setting" );
        }

        return $value;
    }

    /*!
     Return TRUE if setting exist, otherwise FALSE.
    */
    function hasSetting( $name )
    {
        $hasSetting = false;
        if( isset( $this->Settings[$name] ) )
            $hasSetting = true;

        return $hasSetting;
    }

    /*!
     Return 'post_install' steps
    */
    function postInstallSteps()
    {
        return $this->Steps['post_install'];
    }

    /*!
     Execute 'post_install' steps
    */
    function postInstall()
    {
        $steps = $this->postInstallSteps();
        $this->executeSteps( $steps );
    }

    /*!
     Execute $steps
    */
    function executeSteps( $steps )
    {
        $stepNum = 1;
        foreach( $steps as $step )
        {
            $this->execFunction( $step );

            if( $this->lastErrorCode() !== eZSiteInstaller::ERR_OK )
            {
                $res = $this->handleError();
                if( $res === false )
                    $res = $this->defaultErrorHandler();

                if( $res === eZSiteInstaller::ERR_ABORT )
                {
                    $this->reportError( "Aborting execution on step number $stepNum: '". $step['_function'] ."'", 'eZSiteInstaller::postInstall' );
                    break;
                }
            }

            ++$stepNum;
        }
    }

    /*!
     Execute $function defined in step
    */
    function execFunction( $function )
    {
        $functionName = $function['_function'];

        $this->buildFunctionParams( $function['_params'] );

        $result = $this->$functionName( $function['_params'] );

        return $result;
    }

    /*!
     Prepare parameters for function executed in step definition.
    */
    function buildFunctionParams( &$params )
    {
        foreach( array_keys( $params ) as $paramKey )
        {
            if( $this->isFunctionParam( $params[$paramKey] ) )
            {
                $params[$paramKey] = $this->execFunction( $params[$paramKey] );
            }
            else if( is_array( $params[$paramKey] ) )
            {
                $this->buildFunctionParams( $params[$paramKey] );
            }
        }
    }

    /*!
     Return TRUE is custom function should be called to prepare params, otherwise FALSE( $param is constant).
    */
    function isFunctionParam( $param )
    {
        $isFunction = false;
        if( is_array( $param) && isset( $param['_function'] ) )
            $isFunction = true;

        return $isFunction;
    }

    /*!
     Set last error code.
    */
    function setLastErrorCode( $errCode )
    {
        $this->LastErrorCode = $errCode;
    }

    /*!
     Get last error code.
    */
    function lastErrorCode()
    {
        return $this->LastErrorCode;
    }

    /*!
     Set last error code and write error message to debug output
     Params:
        $text    - error message text;
        $caption - error message caption;
        $errCode - error code to set;
    */
    function reportError( $text, $caption, $errCode = eZSiteInstaller::ERR_ABORT )
    {
        eZDebug::writeError( $text, $caption );

        $this->setLastErrorCode( $errCode );
    }


    //
    // Function groups for common use in install steps.
    //

    ///////////////////////////////////////////////////////////////////////////
    // DB:
    //     dbBegin
    //     dbCommit
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Start transaction.
    Params:
        no params
    */
    function dbBegin( $params )
    {
        $db = eZDB::instance();
        $db->begin();
    }

    /*!
     Commit transaction.
     Params:
        no params
    */
    function dbCommit( $params )
    {
        $db = eZDB::instance();
        $db->commit();
    }


    ///////////////////////////////////////////////////////////////////////////
    // Content class:
    //      classIDbyIdentifier
    //      classByIdentifier
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Return ID of the content class.
     Params:
        'identifier'  - identifier of the class.
    */
    function classIDbyIdentifier( $params )
    {
        $classID = false;

        $contentClass = $this->classByIdentifier( $params );

        if( is_object( $contentClass ) )
        {
            $classID = $contentClass->attribute( 'id' );
        }

        return $classID;
    }

    /*!
     Return content class.
     Params:
        'identifier'  - identifier of the class.
    */
    function classByIdentifier( $params )
    {
        //include_once( 'kernel/classes/ezcontentclass.php' );

        $classIdentifier = $params['identifier'];

        $contentClass = eZContentClass::fetchByIdentifier( $classIdentifier );
        if( !is_object( $contentClass ) )
        {
            eZDebug::writeWarning( "Content class with identifier '$classIdentifier' doesn't exist.", 'eZSiteInstaller::classByIdentifier' );
        }

        return $contentClass;
    }


    ///////////////////////////////////////////////////////////////////////////
    // Content class attributes:
    //      removeClassAttribute
    //      addClassAttributes
    //      updateClassAttributes
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Remove attribute from the content class
     Params:
        'class_id'              - ID of content class to remove attribute from;
        'attribute_identifier'  - attibute identifier to remove;
    */
    function removeClassAttribute( $params )
    {
        //include_once( 'kernel/classes/ezcontentclassattribute.php' );

        $contentClassID = $params['class_id'];
        $classAttributeIdentifier = $params['attribute_identifier'];

        // get attributes of 'temporary' version as well
        $classAttributeList = eZContentClassAttribute::fetchFilteredList( array( 'contentclass_id' => $contentClassID,
                                                                                  'identifier' => $classAttributeIdentifier ),
                                                                           true );

        $validation = array();
        foreach( $classAttributeList as $classAttribute )
        {
            $dataType = $classAttribute->dataType();
            if( $dataType->isClassAttributeRemovable( $classAttribute ) )
            {
                $objectAttributes = eZContentObjectAttribute::fetchSameClassAttributeIDList( $classAttribute->attribute( 'id' ) );
                foreach( $objectAttributes as $objectAttribute )
                {
                    $objectAttributeID = $objectAttribute->attribute( 'id' );
                    $objectAttribute->removeThis( $objectAttributeID );
                }

                $classAttribute->removeThis();
            }
            else
            {
                $removeInfo = $dataType->classAttributeRemovableInformation( $classAttribute );
                if( $removeInfo === false )
                    $removeInfo = "Unknow reason";

                $validation[] = array( 'id' => $classAttribute->attribute( 'id' ),
                                       'identifier' => $classAttribute->attribute( 'identifier' ),
                                       'reason' => $removeInfo );
            }
        }

        if( count( $validation ) > 0 )
        {
            $this->reportError( $validation, 'eZSiteInstaller::removeClassAttribute: Unable to remove eZClassAttribute(s)' );
        }

    }

    /*!
     Add attribute to the content class
     Params:
        array( 'class' => array( 'identifier' => optional, string: identifire of content class; see 'id';
                                 'id' => optional, ID of content class. if set it's used instead of 'class_identifier' to speed up things.
                                         you must supply either 'class_identifier' or 'class_id'; ),
               'attributes' => array( array( 'identifier' => attibute identifier,
                                             'name' => attribute name,
                                             'data_type_string' => attribute datatype,
                                             'default_value' => optional, attribute default value, default is 'false'
                                             'can_translate' => optional, default '1'
                                             'is_required' => optional, default '0'
                                             'is_searchable' => optional, default '0'
                                             'content' => optional, attribute content ),
                                      ...
                                    );
    */
    function addClassAttributes( $params )
    {
        //include_once( 'kernel/classes/ezcontentclassattribute.php' );

        $classInfo = $params['class'];
        $attributesInfo = $params['attributes'];

        $classID = isset( $classInfo['id'] ) ? $classInfo['id'] : false;
        if( $classID )
        {
            $class = eZContentClass::fetch( $classID );
        }
        else
        {
            if( isset( $classInfo['identifier'] ) )
            {
                $class = eZSiteInstaller::classByIdentifier( $classInfo );
            }
            else
            {
                $this->reportError( "neighter 'id' nor 'identifier' is set for content class" ,
                                    'eZSiteInstaller::addClassAttribute' );
            }
        }

        if( !is_object( $class ) )
        {
            $this->reportError( "Can't fetch content class" ,
                                'eZSiteInstaller::addClassAttribute' );
            return;
        }

        $classID = $class->attribute( 'id' );

        foreach( $attributesInfo as $attributeInfo )
        {
            $classAttributeIdentifier = $attributeInfo['identifier'];
            $classAttributeName = $attributeInfo['name'];
            $datatype = $attributeInfo['data_type_string'];
            $defaultValue = isset( $attributeInfo['default_value'] ) ? $attributeInfo['default_value'] : false;
            $canTranslate = isset( $attributeInfo['can_translate'] ) ? $attributeInfo['can_translate'] : 1;
            $isRequired   = isset( $attributeInfo['is_required']   ) ? $attributeInfo['is_required'] : 0;
            $isSearchable = isset( $attributeInfo['is_searchable'] ) ? $attributeInfo['is_searchable'] : 0;
            $attrContent  = isset( $attributeInfo['content'] ) ? $attributeInfo['content'] : false;

            $attrCreateInfo = array( 'identifier' => $classAttributeIdentifier,
                                     'name' => $classAttributeName,
                                     'can_translate' => $canTranslate,
                                     'is_required' => $isRequired,
                                     'is_searchable' => $isSearchable );
            $newAttribute = eZContentClassAttribute::create( $classID, $datatype, $attrCreateInfo  );

            $dataType = $newAttribute->dataType();
            $dataType->initializeClassAttribute( $newAttribute );

            // not all datatype can have 'default_value'. do check here.
            if( $defaultValue !== false  )
            {
                switch( $datatype )
                {
                    case 'ezboolean':
                    {
                        $newAttribute->setAttribute( 'data_int3', $defaultValue );
                    }
                    break;

                    default:
                        break;
                }
            }

            if( $attrContent )
                $newAttribute->setContent( $attrContent );

            // store attribute, update placement, etc...
            $attributes = $class->fetchAttributes();
            $attributes[] = $newAttribute;

            // remove temporary version
            if ( $newAttribute->attribute( 'id' ) !== null )
            {
                $newAttribute->remove();
            }

            $newAttribute->setAttribute( 'version', eZContentClass::VERSION_STATUS_DEFINED );
            $newAttribute->setAttribute( 'placement', count( $attributes ) );

            $class->adjustAttributePlacements( $attributes );
            foreach( $attributes as $attribute )
            {
                $attribute->storeDefined();
            }

            // update objects
            $classAttributeID = $newAttribute->attribute( 'id' );
            $objects = eZContentObject::fetchSameClassList( $classID );
            foreach( $objects as $object )
            {
                $contentobjectID = $object->attribute( 'id' );
                $objectVersions = $object->versions();
                foreach( $objectVersions as $objectVersion )
                {
                    $translations = $objectVersion->translations( false );
                    $version = $objectVersion->attribute( 'version' );
                    foreach( $translations as $translation )
                    {
                        $objectAttribute = eZContentObjectAttribute::create( $classAttributeID, $contentobjectID, $version );
                        $objectAttribute->setAttribute( 'language_code', $translation );
                        $objectAttribute->initialize();
                        $objectAttribute->store();
                        $objectAttribute->postInitialize();
                    }
                }
            }
        }
    }

    /*!
     Update class attribute data. Currently only attribute 'name' is supported.
     Params:
        'class'      - array( 'identifier' - identifire of content class );
        'attributes  - array( array( 'identifier'  - attibute identifier to update;
                                     'new_name'    - new attribute name ),
                              ...
                            );
    */
    function updateClassAttributes( $params )
    {
        $classInfo = $params['class'];
        $attributesInfo = $params['attributes'];

        $contentClassID = eZSiteInstaller::classIDbyIdentifier( $classInfo );
        if( $contentClassID )
        {
            foreach( $attributesInfo as $attributeInfo )
            {
                $attributeIdentifier = $attributeInfo['identifier'];
                $name = isset( $attributeInfo['new_name'] ) ? $attributeInfo['new_name'] : false;

                $classAttributeList = eZContentClassAttribute::fetchFilteredList( array( 'contentclass_id' => $contentClassID,
                                                                                         'identifier' => $attributeIdentifier ),
                                                                                  true );
                foreach( $classAttributeList as $attribute )
                {
                    if( $name !== false )
                    {
                        $attribute->setName( $name );
                    }

                    $attribute->store();
                }
            }
        }
    }


    ///////////////////////////////////////////////////////////////////////////
    // Content object:
    //      contentObjectByUrl
    //      contentObjectByName
    //      createContentObject
    //      removeContentObject
    //      renameContentObject
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Return content object by path identification string of its node.
     Params:
        'location'  - path_identification_string of the node.
    */
    function contentObjectByUrl( $params )
    {
        $object = false;

        $node = $this->nodeByUrl( $params );
        if( is_object( $node ) )
        {
            $object = $node->object();
        }

        return $object;
    }

    /*!
     Create content object.
     Params:
        class_identifier    - identifier of class;
        location            - path identification string of parent node;
        attributes          - array with object attributes;
    */
    function createContentObject( $params )
    {
        //include_once( 'kernel/classes/ezcontentfunctions.php' );

        $objectID = false;

        $classIdentifier = $params['class_identifier'];
        $location = $params['location'];
        $attributesData = $params['attributes'];

        $parentNode = $this->nodeByUrl( $params );
        if( is_object( $parentNode ) )
        {
            $parentNodeID = $parentNode->attribute( 'node_id' );
            $object = eZContentFunctions::createAndPublishObject( array( 'parent_node_id' => $parentNodeID,
                                                                         'class_identifier' => $classIdentifier,
                                                                         'attributes' => $attributesData ) );

            if( is_object( $object ) )
            {
                $objectID = $object->attribute( 'id' );
            }
        }

        return $objectID;
    }

    /*!
     Change name of content object.
     Params:
        contentobject_id        - ID of object;
        name                    - new object name;
    */
    function renameContentObject( $params )
    {
        //include_once( 'kernel/classes/ezcontentobject.php' );
        $contentObjectID = $params['contentobject_id'];
        $newName = $params['name'];
        $object = eZContentObject::fetch( $contentObjectID );
        if( !is_object( $object ) )
            return false;
        $object->rename( $newName );
    }

    /*!
     Return content object. The first object is returned in case if several objects have the same name.
     Params:
        'name'      - object's name to look up;
        'class_id'  - optional, ID of content class;
    */
    function contentObjectByName( $params )
    {
        $objectName = $params['name'];
        $classID = isset( $params['class_id'] ) ? $params['class_id'] : false;

        //build up the conditions
        $conditions = array( 'name' => $objectName );

        if( $classID )
            array_merge( $conditions, array( 'contentclass_id' => $classID ) );

        $objectList = eZContentObject::fetchList( true, $conditions, 0, 1 );

        $object = false;
        if( count( $objectList ) > 0 )
            $object = $objectList[0];

        return $object;
    }

    /*!
     Remove content object.
        Params: see 'contentObjectByName'
    */
    function removeContentObject( $params )
    {
        $object = $this->contentObjectByName( $params );
        if( is_object( $object ) )
        {
            $object->purge();
        }
        else
        {
            eZDebug::writeWarning( "Object with name '" . $params['name'] . "' doesn't exist", "eZSiteInstaller::removeContentObject" );
        }
    }



    ///////////////////////////////////////////////////////////////////////////
    // Content object attributes:
    //      updateObjectAttributeFromString
    //      updateContentObjectAttributes
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Update contentObjectAttribute with value specified in string.
     The object can be specified either by ID or by path identification string
     of its node.
     Params:
        object_id                   - optional, ID of object to update;
        location                    - optional, path to node with object;
        class_attribute_identifier  - an identifier of attribute to update;
        string                      - new attribute value;
    */
    function updateObjectAttributeFromString( $params )
    {
        $objectID = isset( $params['object_id'] ) ? $params['object_id'] : false;
        $location = isset( $params['location'] ) ? $params['location'] : false;
        $classAttrIdentifier = $params['class_attribute_identifier'];
        $stringParam = $params['string'];

        $contentObject = false;
        if( $objectID )
        {
            $contentObject = eZContentObject::fetch( $objectID );
            if( !is_object( $contentObject ) )
            {
                $this->reportError( "Content object with id '$objectID' doesn't exist." , 'eZSiteInstaller::updateObjectAttributeFromString' );
            }
        }
        else if( $location )
        {
            $contentObject = $this->contentObjectByUrl( array( 'location' => $location ) );
        }

        if( is_object( $contentObject ) )
        {
            $attributes = $contentObject->contentObjectAttributes();
            if( count( $attributes ) > 0 )
            {
                $objectAttribute = false;
                foreach( $attributes as $attribute )
                {
                    if( $attribute->attribute( 'contentclass_attribute_identifier' ) == $classAttrIdentifier )
                    {
                        $objectAttribute = $attribute;
                        break;
                    }
                }

                if( is_object( $objectAttribute ) )
                {
                    $objectAttribute->fromString( $stringParam );
                    $objectAttribute->store();
                }
                else
                {
                    $this->reportError( "Content object with id '$objectID' doesn't have attribute with contentClassAttribute identifier '$classAttrIdentifier'." , 'eZSiteInstaller::updateObjectAttributeFromString' );
                }
            }
            else
            {
                $this->reportError( "Content object with id '$objectID' doesn't have attributes." , 'eZSiteInstaller::updateObjectAttributeFromString' );
            }
        }
    }


    function updateContentObjectAttributes( $params )
    {
        $objectID = $params['object_id'];
        $attributesData = $params['attributes_data'];

        $contentObject = eZContentObject::fetch( $objectID );
        if( !is_object( $contentObject ) )
        {
            $this->reportError( "Content object with id '$objectID' doesn't exist." , 'eZSiteInstaller::updateContentObjectAttributes' );
            return;
        }

        $dataMap = $contentObject->dataMap();
        foreach( $attributesData as $attrIdentifier => $attrData )
        {
            $attribute = $dataMap[$attrIdentifier];
            if( !is_object( $attribute ) )
            {
                $this->reportError( "Warning: could not acquire attribute with identifier '$attrIdentifier'.",
                                    'eZSiteInstaller::updateContentObjectAttributes',
                                    eZSiteInstaller::ERR_CONTINUE );
                continue;
            }

            //get datatype name
            $datatypeString = $attribute->attribute( 'data_type_string' );

            switch( $datatypeString )
            {
                case 'ezstring':
                {
                    $attribute->setAttribute( "data_text", $attrData['DataText']);
                } break;

                case 'ezxmltext':
                {
                    $xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".
                           '<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"'."\n".
                           '         xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"'."\n".
                           '         xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">'."\n".
                           '  <section>'."\n";
                    {
                        $xml .= '    <paragraph>';
                        $numSentences = mt_rand( ( int ) $attributeParameters['min_sentences'], ( int ) $attributeParameters['max_sentences'] );
                        for( $sentence = 0; $sentence < $numSentences; $sentence++ )
                        {
                            if( $sentence != 0 )
                            {
                                $xml .= ' ';
                            }
                        }
                        $xml .= "</paragraph>\n";
                    }
                    $xml .= "  </section>\n</section>\n";

                    $attribute->setAttribute( 'data_text', $xml );
                } break;

                case 'eztext':
                {
                    $attribute->setAttribute( 'data_text', $attrData['DataText'] );
                } break;

                case 'ezmatrix':
                {
                    $columnsCount = count( $attrData["MatrixDefinition"]->attribute( 'columns' ) );
                    if( $columnsCount > 0 )
                    {
                        $rowsCount = count( $attrData["MatrixCells"] ) / $columnsCount;

                        $tempMatrix = new eZMatrix( $attrData["MatrixTitle"], $rowsCount, $attrData["MatrixDefinition"] );
                        $tempMatrix->Cells = $attrData["MatrixCells"];

                        $attribute->setAttribute( 'data_text', $tempMatrix->xmlString() );
                        $tempMatrix->decodeXML( $attribute->attribute( 'data_text' ) );

                        $attribute->setContent( $tempMatrix );
                    }
                    else
                    {
                        $this->reportError( "Number of columns in 'ezmatrix' should be greater then zero",
                                            'eZSiteInstaller::updateContentObjectAttributes',
                                            eZSiteInstaller::ERR_CONTINUE );
                    }

                } break;

                case 'ezboolean':
                {
                    $attribute->setAttribute( 'data_int', $attrData['DataInt'] );
                } break;

                case 'ezinteger':
                {
                    $attribute->setAttribute( 'data_int', $attrData['DataInt'] );
                } break;

                case 'ezfloat':
                {
                    $power = 100;
                    $float = mt_rand( $power * ( int ) $attrData['Min'], $power * ( int ) $attrData['Max'] );
                    $float = $float / $power;
                    $attribute->setAttribute( 'data_float', $float );
                } break;

                case 'ezprice':
                {
                    $power = 10;
                    $price = mt_rand( $power * ( int ) $attrData['Min'], $power * ( int ) $attrData['Max'] );
                    $price = $price / $power;
                    $attribute->setAttribute( 'data_float', $price );
                } break;

                case 'ezurl':
                {
                    $attribute->setContent( $attrData['Content'] );
                    $attribute->setAttribute( "data_text", $attrData['DataText']);
                } break;

                case 'ezuser':
                {
                    $user = $attribute->content();
                    if( $user === null )
                    {
                        $user = eZUser::create( $objectID );
                    }

                    $user->setInformation( $objectID,
                                           md5( time() . '-' . mt_rand() ),
                                           md5( time() . '-' . mt_rand() ) . '@ez.no',
                                           'publish',
                                           'publish' );
                    $user->store();
                } break;
            }
            $attribute->store();
        }

        $contentObject->store();
    }


    ///////////////////////////////////////////////////////////////////////////
    // Content object tree node:
    //      nodeByUrl
    //      nodeByName
    //      nodeIdByName
    //      nodePathStringByURL
    //      moveTreeNode
    //      swapNodes
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Return 'path_string' attribute of the node.
     Params:
        'location'  - path_identification_string of the node.
    */
    function nodePathStringByURL( $params )
    {
        //include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $pathString = '';

        $node = $this->nodeByUrl( $params );

        if( is_object( $node ) )
        {
            $pathString = $node->attribute( 'path_string' );
        }

        return $pathString;
    }

    /*!
     Return node.
     Params:
        'location'  - path_identification_string of the node.
    */
    function nodeByUrl( $params )
    {
        //include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $path_identification_string = $params['location'];

        $node = eZContentObjectTreeNode::fetchByURLPath( $path_identification_string );

        if( !is_object( $node ) )
        {
            $this->reportError( "The node '$path_identification_string' doesn't exist", 'eZSiteInstaller::nodeByUrl' );
        }

        return $node;
    }

    /*
     Return 'main' node ID of object which has specifued name. For more info see 'contentObjectByName'
     Params:
        See 'contentObjectByName'
    */
    function nodeIdByName( $params )
    {
        $node = $this->nodeByName( $params );

        $id = false;
        if( is_object( $node ) )
            $id = $node->attribute( 'node_id' );

        return $id;
    }

    /*
     Return 'main' node of object which has specifued name. For more info see 'contentObjectByName'
     Params:
        See 'contentObjectByName'
    */
    function nodeByName( $params )
    {
        $node = false;

        $contentObject = $this->contentObjectByName( $params );
        if( is_object( $contentObject ) )
            $node = $contentObject->attribute( 'main_node' );

        return $node;
    }

    /*!
     Move node.
        Params:
            'node'          -   array( 'name'       => object's name to move,
                                       'class_id'   => optional, object's content class ID );

            'parent_node'   -   array( 'name'       => parent object's name to move;
                                       'class_id'   => optional, parent object's content class ID );
    */
    function moveTreeNode( $params )
    {
        $nodeID = $this->nodeIdByName( $params['node'] );
        $parentNodeID = $this->nodeIdByName( $params['parent_node'] );

        $result = eZContentObjectTreeNodeOperations::move( $nodeID, $parentNodeID );

        return $result;
    }

    /*!
     Swap two nodes.
     It's a copy/paste from kernel/content/action.php
    */
    function swapNodes( $params )
    {
        $nodeInfo1 = $params['src_node'];
        $nodeInfo2 = $params['dst_node'];

        //init vars
        $node1 = $this->nodeIdByName( $nodeInfo1 );
        $node2 = $this->nodeIdByName( $nodeInfo2 );

        $nodeID = $node1;
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        if( !is_object( $node ) )
        {
            $this->reportError( "Can't fetch node '$nodeID'", 'eZSiteInstaller::swapNodes' );
            return false;
        }

        if( !$node->canSwap() )
        {
            $this->reportError( "Cannot swap node '$nodeID' (no edit permission)", 'eZSiteInstaller::swapNodes' );
            return false;
        }

        $nodeParentNodeID = $node->attribute( 'parent_node_id' );

        $object = $node->object();
        if( !is_object( $object ) )
        {
            $this->reportError( "Cannot fetch object for node '$nodeID'", 'eZSiteInstaller::swapNodes' );
            return false;
        }

        $objectID = $object->attribute( 'id' );
        $objectVersion = $object->attribute( 'current_version' );
        $class = $object->contentClass();
        $classID = $class->attribute( 'id' );

        $selectedNodeID = $node2;

        $selectedNode = eZContentObjectTreeNode::fetch( $selectedNodeID );

        if( !is_object( $selectedNode ) )
        {
            $this->reportError( "Cannot fetch node '$selectedNodeID'", 'eZSiteInstaller::swapNodes' );
            return false;
        }

        if( !$selectedNode->canSwap() )
        {
            $this->reportError( "Cannot use node $selectedNodeID as the exchanging node for $nodeID, the current user does not have edit permission for it",
                                'eZSiteInstaller::swapNodes' );
            return false;
        }

        // clear cache.
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        $selectedObject = $selectedNode->object();
        $selectedObjectID = $selectedObject->attribute( 'id' );
        $selectedObjectVersion = $selectedObject->attribute( 'current_version' );
        $selectedNodeParentNodeID = $selectedNode->attribute( 'parent_node_id' );


        /* In order to swap node1 and node2 a user should have the following permissions:
         * 1. move_from: move node1
         * 2. move_from: move node2
         * 3. move_to: move an object of the same class as node2 under parent of node1
         * 4. move_to: move an object of the same class as node1 under parent of node2
         *
         * The First two has already been checked. Let's check the rest.
         */
        $nodeParent            = $node->attribute( 'parent' );
        $selectedNodeParent    = $selectedNode->attribute( 'parent' );
        $objectClassID         = $object->attribute( 'contentclass_id' );
        $selectedObjectClassID = $selectedObject->attribute( 'contentclass_id' );

        if( !$nodeParent || !$selectedNodeParent )
        {
            $this->reportError( "No $nodeParent or no !$selectedNodeParent received.",
                                'eZSiteInstaller::swapNodes' );
            return false;
        }

        if( !$nodeParent->canMoveTo( $selectedObjectClassID ) )
        {
            $this->reportError( "Cannot move an object of class $selectedObjectClassID to node $nodeParentNodeID (no create permission)",
                                'eZSiteInstaller::swapNodes' );
            return false;
        }

        if( !$selectedNodeParent->canMoveTo( $objectClassID ) )
        {
            $this->reportError( "Cannot move an object of class $objectClassID to node $selectedNodeParentNodeID (no create permission)",
                                'eZSiteInstaller::swapNodes' );
            return false;
        }

        // exchange contentobject ids and versions.
        $node->setAttribute( 'contentobject_id', $selectedObjectID );
        $node->setAttribute( 'contentobject_version', $selectedObjectVersion );

        $db = eZDB::instance();
        $db->begin();
        $node->store();
        $selectedNode->setAttribute( 'contentobject_id', $objectID );
        $selectedNode->setAttribute( 'contentobject_version', $objectVersion );
        $selectedNode->store();

        // modify path string
        $changedOriginalNode = eZContentObjectTreeNode::fetch( $nodeID );
        $changedOriginalNode->updateSubTreePath();
        $changedTargetNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
        $changedTargetNode->updateSubTreePath();

        // modify section
        if( $changedOriginalNode->attribute( 'main_node_id' ) == $changedOriginalNode->attribute( 'node_id' ) )
        {
            $changedOriginalObject = $changedOriginalNode->object();
            $parentObject = $nodeParent->object();
            if( $changedOriginalObject->attribute( 'section_id' ) != $parentObject->attribute( 'section_id' ) )
            {

                eZContentObjectTreeNode::assignSectionToSubTree( $changedOriginalNode->attribute( 'main_node_id' ),
                                                                 $parentObject->attribute( 'section_id' ),
                                                                 $changedOriginalObject->attribute( 'section_id' ) );
            }
        }
        if( $changedTargetNode->attribute( 'main_node_id' ) == $changedTargetNode->attribute( 'node_id' ) )
        {
            $changedTargetObject = $changedTargetNode->object();
            $selectedParentObject = $selectedNodeParent->object();
            if( $changedTargetObject->attribute( 'section_id' ) != $selectedParentObject->attribute( 'section_id' ) )
            {

                eZContentObjectTreeNode::assignSectionToSubTree( $changedTargetNode->attribute( 'main_node_id' ),
                                                                 $selectedParentObject->attribute( 'section_id' ),
                                                                 $changedTargetObject->attribute( 'section_id' ) );
            }
        }

        $db->commit();

        // clear cache for new placement.
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        return true;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Roles and policies:
    //      assignUserToRole
    //      addPoliciesForRole
    //      removePoliciesForRole
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Assign user to role.
     Params:
        location    - path to user node where user object is located;
        role_name   - name of role to assign;
    */
    function assignUserToRole( $params )
    {
        //include_once( 'kernel/classes/ezrole.php' );

        $location = $params['location'];
        $roleName = $params['role_name'];

        $node = $this->nodeByUrl( $params );
        if( is_object( $node ) )
        {
            $role = eZRole::fetchByName( $roleName );
            if( is_object( $role ) )
                $role->assignToUser( $node->attribute( 'contentobject_id' ) );
        }
    }

    /*!
     Add policies from role
     Params:
        role_name       - name of role;
        policies        - array of policies to remove. Each item is an
                          array of policy definition: array( 'module' =>
                                                             'function' =>
                                                             ['limitation'] => );
       create_role      - boolean, optional. If TRUE - will create new role if it doesn't exist.
                          Default is TRUE.
    */
    function addPoliciesForRole( $params )
    {
        //include_once( 'kernel/classes/ezrole.php' );

        $roleName = $params['role_name'];
        $policiesDefinition = $params['policies'];
        $createRoleIfNotExists = isset( $params['create_role'] ) ? $params['create_role'] : true;

        $role = eZRole::fetchByName( $roleName );
        if( is_object( $role ) || $createRoleIfNotExists )
        {
            if( !is_object( $role ) )
            {
                $role = eZRole::create( $roleName );
                $role->store();
            }

            $roleID = $role->attribute( 'id' );
            if( count( $policiesDefinition ) > 0 )
            {
                foreach( $policiesDefinition as $policyDefinition )
                {
                    if( isset( $policyDefinition['limitation'] ) )
                    {
                        $role->appendPolicy( $policyDefinition['module'], $policyDefinition['function'], $policyDefinition['limitation'] );
                    }
                    else
                    {
                        $role->appendPolicy( $policyDefinition['module'], $policyDefinition['function'] );
                    }
                }
            }
        }
        else
        {
            $this->reportError( "Role '$roleName' doesn't exist." , 'eZSiteInstaller::addPoliciesToRole' );
        }
    }

    /*!
     Remove policies from role
     Params:
        role_name       - name of role;
        policies        - array of policies to remove. Each item is an
                          array of policy definition: array( 'module' => ,
                                                             'function' => );
       remove_role      - boolean, optional. If TRUE - empty(without policies) role will be removed.
                          Default is TRUE.
    */
    function removePoliciesForRole( $params )
    {
        //include_once( 'kernel/classes/ezrole.php' );

        $roleName = $params['role_name'];
        $policiesDefinition = $params['policies'];
        $removeRoleIfEmpty = isset( $params['remove_role'] ) ? $params['remove_role'] : true;

        $role = eZRole::fetchByName( $roleName );
        if( is_object( $role ) )
        {
            foreach( $policiesDefinition as $policyDefinition )
            {
                $role->removePolicy( $policyDefinition['module'], $policyDefinition['function'] );
            }

            if( $removeRoleIfEmpty && count( $role->policyList() ) == 0 )
            {
                $role->removeThis();
            }
        }
        else
        {
            $this->reportError( "Role '$roleName' doesn't exist." , 'eZSiteInstaller::removePoliciesForRole' );
        }
    }


    ///////////////////////////////////////////////////////////////////////////
    // Section:
    //      sectionIDbyName
    //      createContentSection
    //      setSection
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Return ID of the section.
     Params:
        'section_name'  - name of the section
    */
    function sectionIDbyName( $params )
    {
        //include_once( 'kernel/classes/ezsection.php' );

        $sectionID = false;
        $sectionName = $params['section_name'];

        $sectionList = eZSection::fetchFilteredList( array( 'name' => $sectionName ), false, false, true );

        if( is_array( $sectionList ) && count( $sectionList ) > 0 )
        {
            $section = $sectionList[0];
            if( is_object( $section ) )
            {
                $sectionID = $section->attribute( 'id' );
            }
        }

        return $sectionID;
    }

    /*!
     Create new content section.
     Params:
        'name'                          - string: section name;
        'navigation_part_identifier'    - string: identifier of navigation part;
    */
    function createContentSection( $params )
    {
        //include_once( 'kernel/classes/ezsection.php' );

        $section = false;

        $sectionName = $params['name'];
        $navigationPart = $params['navigation_part_identifier'];

        $section = new eZSection( array() );
        $section->setAttribute( 'name', $sectionName );
        $section->setAttribute( 'navigation_part_identifier', $navigationPart );
        $section->store();

        return $section;
    }

    /*!
     Assign section to subtree.
     Params:
        'section_name'    - string: section name;
        'location'        - string: path_identification_string of the node(root node of subtree);
    */
    function setSection( $params )
    {
        $location = $params['location'];
        $sectionName = $params['section_name'];

        $sectionID = $this->sectionIDbyName( $params );
        if( $sectionID )
        {
            $rootNode = $this->nodeByUrl( $params );
            if( is_object( $rootNode ) )
            {
                eZContentObjectTreeNode::assignSectionToSubTree( $rootNode->attribute( 'node_id' ), $sectionID );
            }
        }

    }


    ///////////////////////////////////////////////////////////////////////////
    // RSS:
    //      setRSSExport
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Create rss export.
    */
    function setRSSExport( $params )
    {
        //include_once( 'kernel/classes/ezrssexport.php' );
        //include_once( 'kernel/classes/ezrssexportitem.php' );
        require_once( 'kernel/common/i18n.php' );

        // Create default rssExport object to use
        $rssExport = eZRSSExport::create( $params['creator'] );
        $rssExport->setAttribute( 'access_url', $params['access_url'] );
        $rssExport->setAttribute( 'main_node_only', $params['main_node_only'] );
        $rssExport->setAttribute( 'number_of_objects', $params['number_of_objects'] );
        $rssExport->setAttribute( 'rss_version', $params['rss_version'] );
        $rssExport->setAttribute( 'status', $params['status'] );
        $rssExport->setAttribute( 'title', $params['title'] );
        $rssExport->store();

        $rssExportID = $rssExport->attribute( 'id' );

        foreach( $params['rss_export_itmes'] as $item )
        {
            // Create One empty export item
            $rssExportItem = eZRSSExportItem::create( $rssExportID );
            $rssExportItem->setAttribute( 'class_id', $item['class_id'] );
            $rssExportItem->setAttribute( 'description', $item['description'] );
            $rssExportItem->setAttribute( 'source_node_id', $item['source_node_id'] );
            $rssExportItem->setAttribute( 'status', $item['status'] );
            $rssExportItem->setAttribute( 'title', $item['title'] );
            $rssExportItem->store();
        }
    }


    ///////////////////////////////////////////////////////////////////////////
    // Package:
    //      packageFileItemPath
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Return path for package item.
    */
    function packageFileItemPath( $params )
    {
        $collection = $params['collection'];
        $fileItem = $params['file_item'];

        $filePath = $fileItem['name'];

        $package = $this->setting( 'package_object' );
        if( is_object( $package ) )
        {
            $filePath = $package->fileItemPath( $fileItem, $collection );
        }
        else
        {
            eZDebug::writeWarning( "'Package' object is not set", 'eZSiteInstaller::packageFileItemPath' );
        }

        return $filePath;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Languages and Locales:
    //      languageNameListFromLocaleList
    //      languageNameFromLocale
    ///////////////////////////////////////////////////////////////////////////

    /*!
     Build array of language names from locale list.
     Example: array( 'eng-GB', 'rus-RU' ) => array( 'eng', 'rus' )
    */
    function languageNameListFromLocaleList( $localeList )
    {
        $languageList = array();
        foreach( $localeList as $locale )
            $languageList[] = $this->languageNameFromLocale( $locale );

        return $languageList;
    }

    /*!
     Return language name from locale string.
     Example: 'rus' from 'rus-RU'
    */
    function languageNameFromLocale( $locale )
    {
         $pos = strpos( $locale , "-");
         $languageName = substr( $locale , 0, $pos );
         return $languageName;
    }

    /*!
     Create siteaccess urls for additional user siteacceses using info about access type(host, post, uri)
     Params:
        'siteaccess_list'         - list of siteaccess names to build urls;
        'access_type'             - access type: port, hostname, url;
        'port'                    - optional, port number to start with. used if 'access_type' is 'port';
        'exclude_port_list'       - optional, ports to skip. used if 'access_type' is 'port';
        'host'                    - host name
        'host_prepend_siteaccess' - optional, boolean which instructs to prepend the site access name or not to the value of 'host', by default true
    */
    function createSiteaccessUrls( $params )
    {
        $sys = eZSys::instance();

        $urlList = array();

        $siteaccessList = $params['siteaccess_list'];
        $accessType = $params['access_type'];

        $excludePortList = isset( $params['exclude_port_list'] ) ? $params['exclude_port_list'] : array();

        $port = isset( $params['port'] ) ? $params['port'] : 8085;
        $host = isset( $params['host'] ) && $params['host'] ? $params['host'] : $sys->hostname();

        $indexFile = eZSys::wwwDir() . eZSys::indexFileName();

        switch( $accessType )
        {
            case 'port':
                {
                    // grep server url stripping port number
                    $pos = strpos( $host, ':' );
                    if( $pos !== false )
                        $host = substr( $host, 0, $pos );

                    // build urls
                    foreach( $siteaccessList as $siteaccess )
                    {
                        // skip ports which are already in use
                        while( in_array( $port, $excludePortList ) )
                            ++$port;

                        $urlList[$siteaccess]['url'] = "$host:$port" . $indexFile;
                        $urlList[$siteaccess]['port'] = $port;
                        ++$port;
                    }
                }
                break;
            case 'host':
            case 'hostname':
                {
                    $prependSiteAccess = isset( $params['host_prepend_siteaccess'] ) && is_bool( $params['host_prepend_siteaccess'] ) ? $params['host_prepend_siteaccess'] : true;

                    // grep domain
                    if( preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $host, $matches ) )
                        $host = $matches[1];

                    foreach( $siteaccessList as $siteaccess )
                    {
                        if ( $prependSiteAccess )
                        {
                            // replace undescores with dashes( '_' -> '-' );
                            $hostPrefix = preg_replace( '/(_)/', '-', $siteaccess);

                            // create url and host
                            $urlList[$siteaccess]['url'] = $hostPrefix . '.' . $host . $indexFile;
                            $urlList[$siteaccess]['host'] = $hostPrefix . '.' . $host;
                        }
                        else
                        {
                            // create url and host
                            $urlList[$siteaccess]['url'] = $host . $indexFile;
                            $urlList[$siteaccess]['host'] = $host;
                        }
                    }
                }
                break;
            case 'url':
            case 'uri':
                {
                    foreach( $siteaccessList as $siteaccess )
                    {
                        $urlList[$siteaccess]['url'] = $host . $indexFile . '/' . $siteaccess;
                    }
                }
                break;

            default:
                break;
        }

        return $urlList;
    }

    /*!
     Create localized siteaccess: copy general setting form 'source' siteaccess, apply new custom settings(locale, others...).
    */
    function createSiteAccess( $params )
    {
        $srcSiteaccess = $params['src']['siteaccess'];
        $dstSiteaccess = $params['dst']['siteaccess'];
        $dstSettings   = isset( $params['dst']['settings'] ) ? $params['dst']['settings'] : array();

        // Create the siteaccess directory
        $srcSiteaccessDir = "settings/siteaccess/" . $srcSiteaccess;
        $dstSiteaccessDir = "settings/siteaccess/" . $dstSiteaccess;
        eZDir::mkdir( $dstSiteaccessDir, false, true );
        eZDir::copy( $srcSiteaccessDir, $dstSiteaccessDir, false, true );

        // Update settings
        foreach ( $dstSettings as $iniFile => $settingGroups )
        {
            $ini = eZINI::instance( $iniFile . ".append.php", $dstSiteaccessDir, null, false, null, true );

            foreach ( $settingGroups as $settingGroup => $settings )
            {
                foreach ( $settings as $name => $value )
                {
                    $ini->setVariable( $settingGroup, $name, $value );
                }
            }

            $ini->save(  false, false, false, false, true, true );
            unset( $ini );
        }

        // Create roles
        $role = eZRole::fetchByName( "Anonymous" );
        $role->appendPolicy( "user", "login", array( "SiteAccess" => array( eZSys::ezcrc32( $dstSiteaccess ) ) ) );
        $role->store();
    }

    function solutionVersion()
    {
        eZDebug::writeWarning( "Your installer doesn't implement 'solutionVersion' function", "eZSiteInstaller::initSettings" );
    }

    function solutionName()
    {
        eZDebug::writeWarning( "Your installer doesn't implement 'solutionName' function", "eZSiteInstaller::initSettings" );
    }

    /*!
     Set solution name and version into db.
     Params:
        not used
    */
    function setVersion( $params = false )
    {
        $db = eZDB::instance();

        $name = strtolower( $this->solutionName() );
        $version = $this->solutionVersion();

        $result = $db->query( "INSERT INTO ezsite_data VALUES( '$name', '$version' )" );

        return $result;
    }

    function updateINIFiles( $params )
    {
        foreach( $params['groups'] as $settingsData )
        {
            $iniFilename = $settingsData['name'] . '.append.php';
            $ini = eZINI::instance( $iniFilename, $params['settings_dir'] );
            if( isset( $settingsData['discard_old_values'] ) && $settingsData['discard_old_values'] )
                $ini->reset();

            // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
            $ini->setReadOnlySettingsCheck( false );
            $ini->setVariables( $settingsData['settings'] );
            $ini->save();
        }
    }

    function updateRoles( $params )
    {
        foreach( $params['roles'] as $roleData )
        {
            $roleName = $roleData['name'];
            $role = eZRole::fetchByName( $roleName );
            if( !is_object( $role ) )
            {
                $role = eZRole::create( $roleName );
                $role->store();
            }

            $roleID = $role->attribute( 'id' );
            if( isset( $roleData['policies'] ) )
            {
                $policies = $roleData['policies'];
                foreach( $policies as $policy )
                {
                    $role->appendPolicy( $policy['module'], $policy['function'], isset( $policy['limitation'] ) ? $policy['limitation'] : array() );
                }
            }

            if( isset( $roleData['assignments'] ) )
            {
                $roleAssignments = $roleData['assignments'];
                foreach( $roleAssignments as $roleAssignment )
                {
                    $assignmentIdentifier = false;
                    $assignmentValue = false;
                    if( isset( $roleAssignment['limitation'] ) )
                    {
                        $assignmentIdentifier = $roleAssignment['limitation']['identifier'];
                        $assignmentValue = $roleAssignment['limitation']['value'];
                    }
                    $role->assignToUser( $roleAssignment['user_id'], $assignmentIdentifier, $assignmentValue );
                }
            }
        }
    }

    function updatePreferences( $params )
    {
        foreach( $params['prefs'] as $prefEntry )
        {
            $prefUserID = $prefEntry['user_id'];
            foreach( $prefEntry['preferences'] as $pref )
            {
                $prefName = $pref['name'];
                $prefValue = $pref['value'];
                eZPreferences::setValue( $prefName, $prefValue, $prefUserID );
            }
        }
    }

    /*!
     \static
     Return a value from $params hash.
     Return $defaultValue is value is not set.
     $name - a key in $params hash. $name can point to 2-dimensional array.
     example: $name = 'foo' will return $params['foo'];
              $name = 'foo/boo' will return  $params['foo']['boo']

    */
    function getParam( $params, $name, $defaultValue = false )
    {
        $value = $defaultValue;

        $pos = strpos( $name, '/' );
        if( $pos !== false )
        {
            $dim1 = substr( $name, 0, $pos );
            $dim2 = substr( $name, $pos + 1 );
            if( isset( $params[$dim1][$dim2] ) )
            {
                $value = $params[$dim1][$dim2];
            }
        }
        else if( isset( $params[$name] ) )
        {
            $value = $params[$name];
        }

        return $value;
    }

    /*!
     Default error handler
    */
    function defaultErrorHandler()
    {
        return $this->lastErrorCode();
    }

    /*!
     Virtual function to re-implement in derived classes to handle error.
     Default error handler will be called if returns FALSE.
    */
    function handleError()
    {
        // call default error handler
        return false;
    }

    // store data to use in your steps.
    var $Settings;
    // define an order of functions to execute.
    var $Steps;
    // hold an error code of last executed step.
    var $LastErrorCode;
}

?>
