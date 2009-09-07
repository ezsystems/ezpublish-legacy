<?php
/**
 * File containing the ezpObject class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class ezpObject
{
    /**
     * Holds the properties of this class.
     *
     * @var array( string=>mixed )
     */
    private $properties = array();

    /**
     * Contains the result of the last publish operation
     * @var mixed
     **/
    public $operationResult;

    /**
     * @var eZContentObject
     **/
    public $object;

    /**
     * @var eZContentClass
     **/
    public $class;

    /**
     * @var ezpNode
     **/
    public $mainNode;

    /**
     * @var array(eZContentObjectTreeNode)
     **/
    public $nodes;

    public function __construct( $classIdentifier, $parentNodeID = false, $creatorID = 14, $section = 1, $languageCode = false )
    {
        $this->class = eZContentClass::fetchByIdentifier( $classIdentifier );
        if ( !$this->class instanceof eZContentClass )
            throw new ezcBaseValueException( 'class',
                                             ( isset( $this->class ) ? get_class( $this->class ) : null ),
                                             'eZContentClass ($classIdentifier was: ' . $classIdentifier . ' ) ',
                                             'member' );

        $this->object = $this->class->instantiate( $creatorID, $section, false, $languageCode );

        // Create main node
        if ( is_numeric( $parentNodeID ) )
        {
            $this->mainNode = new ezpNode( $this->object, $parentNodeID, true );
        }

        $this->nodes = array( $this->mainNode );
    }

    /**
     * Returns the value of the property $name.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @ignore
     */
    public function __get( $name )
    {
        switch ( $name )
        {
            case 'dataMap':
            {
                if ( isset( $this->object ) )
                    return $this->object->dataMap();
                else
                    return array();
            } break;
            default:
            {
                if ( isset( $this->dataMap[$name] ) )
                {
                    return $this->dataMap[$name]->content();
                }

                if ( !$this->object instanceof eZContentObject )
                    throw new ezcBaseInvalidParentClassException( 'eZContentObject', $this->object );

                if ( $this->object->hasAttribute( $name ) )
                    return $this->object->attribute( $name );

                throw new ezcBasePropertyNotFoundException( '->object->attribute( ' . $name . ' )' );
            }
        }
    }

    /**
     * Sets the property $name to $value.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @param mixed $value
     * @ignore
     */
    public function __set( $name, $value )
    {
        switch( $name )
        {
            default:
                if ( isset( $this->dataMap[$name] ) )
                {
                    $attribute = $this->dataMap[$name];
                    switch( $attribute->attribute( 'data_type_string' ) )
                    {
                        case 'ezxmltext':
                            $value = $this->processXmlTextData( $value, $attribute );
                            $attribute->setAttribute( 'data_text', $value );
                            break;

                        case 'ezurl':
                            $attribute->setAttribute( 'data_text', $value[0] );
                            $attribute->setContent( $value[1] );
                            break;

                        case 'ezbinaryfile':
                            $attribute->fromString( $value );
                            break;

                        case 'ezimage':
                            $attribute->fromString( $value );
                            break;

                        // Relation: either an eZContentObject or an object ID
                        case 'ezobjectrelation':
                            if ( $value instanceof eZContentObject )
                                $attribute->setAttribute( 'data_int', $value->attribute( 'contentobject_id' ) );
                            elseif ( is_numeric( $value ) )
                                $attribute->setAttribute( 'data_int', $value );
                            break;

                        case 'ezkeyword':
                            $attribute->fromString( $value );
                            break;

                        // Relation list: either an array of ID, or a dash separated string
                        case 'ezobjectrelationlist':
                            if ( is_array( $value ) )
                                $value = implode( '-', $value );
                            $attribute->fromString( $value );
                            break;

                        default:
                            $attribute->setAttribute( 'data_text', $value );
                            break;
                    }

                    $this->dataMap[$name]->store();
                }
                else
                {
                    // eZPersistentObject sets a class properties to store
                    // attribute information
                    $this->$name = $value;
                }
        }
    }

    public function __call( $name, $arguments )
    {
        return call_user_func_array( array( $this->object, $name ), $arguments );
        // return $this->object->$name( $arguments );
    }

    public function addNode( $parentNodeID )
    {
        $newNode = $this->object->addLocation( $parentNodeID, true );

        // Workaround: addLocation() does not do everything necessary to
        // create new nodes. If this changes in the future remove the
        // below lines.
        $newNode->updateSubTreePath();
        $newNode->setAttribute( 'contentobject_is_published', 1 );
        $newNode->sync();

        $this->nodes[] = $newNode;

        return $newNode;
    }

    public function publish()
    {
        $this->operationResult = self::publishContentObject( $this->object );

        return $this->object->attribute( 'id' );
    }

    public function remove()
    {
       $this->object->removeThis();
       $this->object->purge();
    }

    public function refreshAttributes()
    {
        unset( $this->dataMap );
    }

    static function publishContentObject( $object, $version = false )
    {
        $objectID = $object->attribute( 'id' );
        $object->clearCache(); // Added to make sure caches in global vars gets cleared.

        if ( $version and is_numeric( $version ) )
        {
            $versionNumber = $version;
        }
        elseif ( $version instanceof eZContentObjectVersion )
        {
            $versionNumber = $version->attribute( 'version' );
        }
        else
        {
            $versionNumber = $object->attribute( 'current_version' );
        }

        return eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $objectID,
                                                                         'version' => $versionNumber ) );
    }

    /**
     * Adds a translation in language $newLanguageCode for object
     *
     * @param string $newLanguageCode
     * @param mixed $translationData array( attribute identifier => attribute value )
     * @return void
     */
    public function addTranslation( $newLanguageCode, $translationData )
    {
        // Make sure to refresh the objects data.
        $this->refresh();

        $this->object->cleanupInternalDrafts();
        $version = $this->object->createNewVersionIn( $newLanguageCode );
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
        $version->store();

        $newVersion = $this->object->version( $version->attribute( 'version' ) );
        $newVersionAttributes = $newVersion->contentObjectAttributes( $newLanguageCode );

        $versionDataMap = self::createDataMap( $newVersionAttributes );

        // Start updating new version
        $version->setAttribute( 'modified', time() );
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );

        $db = eZDB::instance();
        $db->begin();

        $version->store();

        // @TODO: Add generic datatype support here

        foreach ( $translationData as $attr => $value )
        {
            if ( $versionDataMap[$attr]->attribute( 'data_type_string') == "ezxmltext" )
            {
                $value = $this->processXmlTextData( $value, $versionDataMap[$attr] );
            }

            $versionDataMap[$attr]->setAttribute( 'data_text', $value );
            $versionDataMap[$attr]->store();
        }

        $db->commit();

        //Update the content object name
        $db->begin();
        $this->object->setName( $this->class->contentObjectName( $this->object,
                                                                 $version->attribute( 'version' ),
                                                                 $newLanguageCode ),
                                $version->attribute( 'version' ), $newLanguageCode );
        $db->commit();


        // Finally publish object
        self::publishContentObject( $this->object, $version );
    }

    /**
     * Removes a translation with language $languageCode
     *
     * @param string $languageCode (nor-NO, eng-GB)
     * @return void
     */
    public function removeTranslation( $languageCode )
    {
        // Log in as admin first because removeTranslation() checks for permissions.
        $adminUser = eZUser::fetch( 14 );
        $adminUser->loginCurrent();

        $language = eZContentLanguage::fetchByLocale( $languageCode, false );
        $success = $this->object->removeTranslation( $language->attribute( 'id' ) );
        if ( !$success )
        {
            throw new Exception( "Unable to remove translation $languageCode" );
        }

        // $this->publish();
    }

    public function refresh()
    {
        $this->object->clearCache();
        $this->object = eZContentObject::fetch( $this->id );
        $this->refreshAttributes();
    }

    private static function createDataMap( $attributeArray )
    {
        $ret = array();
        foreach( $attributeArray as $attribute )
        {
            $ret[$attribute->contentClassAttributeIdentifier()] = $attribute;
        }
        return $ret;
    }

    private function processXmlTextData( $xml, $attribute )
    {
        $parser = new eZSimplifiedXMLInputParser( $this->object->attribute( 'id' ) );
        $parser->ParseLineBreaks = true;

        $xml = $parser->process( $xml );
        $xml = eZXMLTextType::domString( $xml );

        $urlIdArray = $parser->getUrlIDArray();
        if ( count( $urlIdArray ) > 0 )
            eZSimplifiedXMLInput::updateUrlObjectLinks( $attribute, $urlIdArray );

        return $xml;
    }
}

?>