<?php

class ezpObject
{
    /**
     * Holds the properties of this class.
     *
     * @var array( string=>mixed )
     */
    private $properties = array();


    public function __construct( $classIdentifier, $parentNodeID = false, $creatorID = 14, $section = 1 )
    {
        $this->class = eZContentClass::fetchByIdentifier( $classIdentifier );
        $this->object = $this->class->instantiate( $creatorID, $section );

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
                if ( isset( $this->object ) )
                    return $this->object->dataMap();
                else
                    return array();
                break;
            default:
                if ( isset( $this->dataMap[$name] ) )
                {
                    return $this->dataMap[$name]->content();
                }

                return $this->object->attribute( $name );
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
        self::publishContentObject( $this->object );

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

        $result = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $objectID,
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
        $this->object->removeTranslation( $language->attribute( 'id' ) );

        // $this->publish();
    }

    public function refresh()
    {
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
