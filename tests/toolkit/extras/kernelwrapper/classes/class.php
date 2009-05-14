<?php

/**
 * eZ Publish content class kernel wrapper
 *
 * <code>
 * $class = new ezpClass();
 * $name = $class->add( 'Name', 'name', 'ezstring' );
 * $relations = $class->add( 'Relations', 'relations', 'ezobjectrelationlist' );
 * $email = $class->add( 'E-mail', 'email', 'ezemail' );
 *
 * $class->remove( $email );
 * $class->store();
 * </code>
 */

 /**
  * TODO: add possibility for translating content class
  * TODO: replace ezpClass params with struct
  * TODO: add check for type string (only registered datatypes should be allowed) in ezpClass::add()
  */
class ezpClass
{
    /**
     * Initialize ezpClass object
     * 
     * @param string $name
     * @param string $identifier
     * @param string $contentObjectName
     * @param int $creatorID
     * @param string $language
     * @param int $groupID
     * @param string $groupName
     */
    public function __construct( $name = 'Test class', $identifier = 'test_class', $contentObjectName = '<test_attribute>', $creatorID = 14, $language = 'eng-GB', $groupID = 1, $groupName = 'Content' )
    {
        if ( eZContentLanguage::fetchByLocale( $language ) === false )
        {
            $topPriorityLanguage = eZContentLanguage::topPriorityLanguage();
            
            if ( $topPriorityLanguage )
                $language = $topPriorityLanguage->attribute( 'locale' );
        }
        
        $this->language = $language;
            
        $this->class = eZContentClass::create( $creatorID, array(), $this->language );
        $this->class->setName( $name, $this->language );
        $this->class->setAttribute( 'contentobject_name', $contentObjectName );
        $this->class->setAttribute( 'identifier', $identifier );
        $this->class->store();
        
        $languageID = eZContentLanguage::idByLocale( $this->language );
        $this->class->setAlwaysAvailableLanguageID( $languageID );
        
        $this->classGroup = eZContentClassClassGroup::create( $this->id, $this->version, $groupID, $groupName );
        $this->classGroup->store();
    }
    
    /**
     * Adds new content class attribute to initialized class.
     * 
     * @param string $name
     * @param string $identifier
     * @param string $type
     * @return eZContentClassAttribute
     */
    public function add( $name = 'Test attribute', $identifer = 'test_attribute', $type = 'ezstring' )
    {
        $classAttribute = eZContentClassAttribute::create( $this->id, $type, array(), $this->language );
        $classAttribute->setName( $name, $this->language );
        
        $dataType = $classAttribute->dataType();
        $dataType->initializeClassAttribute( $classAttribute );
        
        $classAttribute->setAttribute( 'identifier', $identifer );
        $classAttribute->store();
        
        return $classAttribute;
    }
    
    /**
     * Remove given eZContentClassAttribute object from initialized class.
     * 
     * @param eZContentClassAttribute $classAttribute
     * @return void
     */
    public function remove( eZContentClassAttribute $classAttribute )
    {
        $this->class->removeAttributes( array( $classAttribute ) );
    }

    /**
     * Stores defined version of content class.
     * 
     * @return void
     */
    public function store()
    {
        $this->class->storeDefined( $this->class->fetchAttributes() );
    }
    
    /**
     * Returns the value of the property $name.
     *
     * @param string $name
     * @ignore
     */
    public function __get( $name )
    {
        return $this->class->attribute( $name );
    }

    /**
     * Sets the property $name to $value.
     *
     * @param string $name
     * @param mixed $value
     * @ignore
     */
    public function __set( $name, $value )
    {
        $this->$name = $value;
    }
}

?>
