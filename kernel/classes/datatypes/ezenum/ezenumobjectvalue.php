<?php
/**
 * File containing the eZEnum class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZEnumObjectValue ezenumobjectvalue.php
  \brief The class eZEnumObjectValue stores chosen enum values of an object attribute

*/

class eZEnumObjectValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZEnumObjectValue( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "contentobject_attribute_id" => array( 'name' => "ContentObjectAttributeID",
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
                                         "contentobject_attribute_version" => array( 'name' => "ContentObjectAttributeVersion",
                                                                                     'datatype' => 'integer',
                                                                                     'default' => 0,
                                                                                     'required' => true,
                                                                                     'short_name' => 'contentobject_attr_version' ),
                                         "enumid" => array( 'name' => "EnumID",
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "enumelement" => array( 'name' => "EnumElement",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "enumvalue" => array( 'name' => "EnumValue",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ) ),
                      "keys" => array( "contentobject_attribute_id", "contentobject_attribute_version", "enumid" ),
                      "sort" => array( "contentobject_attribute_id" => "asc" ),
                      "class_name" => "eZEnumObjectValue",
                      "name" => "ezenumobjectvalue" );
    }

    static function create( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue )
    {
        $row = array( "contentobject_attribute_id" => $contentObjectAttributeID,
                      "contentobject_attribute_version" => $contentObjectAttributeVersion,
                      "enumid" => $enumID,
                      "enumelement" =>  $enumElement,
                      "enumvalue" => $enumValue );
        return new eZEnumObjectValue( $row );
    }

    static function removeAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
        if( $contentObjectAttributeVersion == null )
        {
            eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                              array( "contentobject_attribute_id" => $contentObjectAttributeID ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                              array( "contentobject_attribute_id" => $contentObjectAttributeID,
                                                     "contentobject_attribute_version" => $contentObjectAttributeVersion ) );
        }
    }

    function removeByOAID( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumid )
    {
        eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                          array( "enumid" => $enumid,
                                                 "contentobject_attribute_id" => $contentObjectAttributeID,
                                                 "contentobject_attribute_version" => $contentObjectAttributeVersion ) );
    }

    static function fetch( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumid, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZEnumObjectValue::definition(),
                                                null,
                                                array(  "contentobject_attribute_id" => $contentObjectAttributeID,
                                                        "contentobject_attribute_version" => $contentObjectAttributeVersion,
                                                        "enumid" => $enumid ),
                                                $asObject );
    }

    static function fetchAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZEnumObjectValue::definition(),
                                                    null,
                                                    array( "contentobject_attribute_id" => $contentObjectAttributeID,
                                                           "contentobject_attribute_version" => $contentObjectAttributeVersion ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    public $ContentObjectAttributeID;
    public $ContentObjectAttributeVersion;
    public $EnumID;
    public $EnumElement;
    public $EnumValue;
}

?>
