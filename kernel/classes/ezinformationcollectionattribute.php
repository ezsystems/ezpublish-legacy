<?php
/**
 * File containing the eZInformationCollectionAttribute class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZInformationCollectionAttribute ezinformationcollectionattribute.php
  \ingroup eZKernel
  \brief The class eZInformationCollectionAttribute handles collected attribute information

*/

class eZInformationCollectionAttribute extends eZPersistentObject
{
    public $ContentClassAttributeID;
    public function __construct( $row )
    {
        $this->Content = null;
        parent::__construct( $row );
    }

    /*!
     \return the persistent object definition for the eZInformationCollectionAttribute class.
    */
    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'informationcollection_id' => array( 'name' => 'InformationCollectionID',
                                                                              'datatype' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true,
                                                                              'foreign_class' => 'eZInformationCollection',
                                                                              'foreign_attribute' => 'id',
                                                                              'multiplicity' => '1..*' ),
                                         'contentclass_attribute_id' => array( 'name' => 'ContentClassAttributeID',
                                                                               'datatype' => 'integer',
                                                                               'default' => 0,
                                                                               'required' => true,
                                                                               'foreign_class' => 'eZContentClassAttribute',
                                                                               'foreign_attribute' => 'id',
                                                                               'multiplicity' => '1..*' ),
                                         'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
                                         'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZContentObject',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         'data_text' => array( 'name' => 'DataText',
                                                               'datatype' => 'text',
                                                               'default' => '',
                                                               'required' => true ),
                                         'data_int' => array( 'name' => 'DataInt',
                                                              'datatype' => 'integer',
                                                              'default' => null,
                                                              'required' => true ),
                                         'data_float' => array( 'name' => 'DataFloat',
                                                                'datatype' => 'float',
                                                                'default' => 0,
                                                                'required' => true ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'contentclass_attribute_name' => 'contentClassAttributeName',
                                                      'contentclass_attribute' => 'contentClassAttribute',
                                                      'contentobject_attribute' => 'contentObjectAttribute',
                                                      'contentobject' => 'contentObject',
                                                      'result_template' => 'resultTemplateName',
                                                      'has_content' => 'hasContent',
                                                      'content' => 'content',
                                                      'class_content' => 'classContent' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZInformationCollectionAttribute',
                      'name' => 'ezinfocollection_attribute' );
    }

    /*!
     \return the content for the contentclass attribute which defines this information collection attribute.
    */
    function classContent()
    {
        $classAttribute = $this->contentClassAttribute();
        if ( is_object( $classAttribute ) )
        {
            return $classAttribute->content();
        }
        return null;
    }

    /*!
     \return the content for this attribute.
    */
    function content()
    {
        if ( $this->Content === null )
        {
            $dataType = $this->dataType();
            if ( is_object( $dataType ) )
            {
                $this->Content = $dataType->objectAttributeContent( $this );
            }
        }
        return $this->Content;
    }

    /*!
     \return \c true if the attribute is considered to have any content at all (ie. non-empty).

     It will call the hasObjectAttributeContent() for the current datatype to figure this out.
    */
    function hasContent()
    {
        $dataType = $this->dataType();
        if ( is_object( $dataType ) )
        {
            return $dataType->hasObjectAttributeContent( $this );
        }
        return false;
    }

    /*!
     \return the template name to use for viewing the attribute
     \note The returned template name does not include the .tpl extension.
     \sa informationTemplate
    */
    function resultTemplateName()
    {
        $dataType = $this->dataType();
        if ( $dataType )
        {
            return $dataType->resultTemplate( $this );
        }
        return null;
    }

    function contentObject()
    {
        return eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
    }

    function contentObjectAttribute()
    {
        $contentObject = $this->contentObject();
        return eZContentObjectAttribute::fetch( $this->attribute( 'contentobject_attribute_id' ), $contentObject->attribute( 'current_version' ) );
    }

    function contentClassAttribute()
    {
        return eZContentClassAttribute::fetch( $this->attribute( 'contentclass_attribute_id' ) );
    }

    function dataType()
    {
        $contentClassAttribute = $this->contentClassAttribute();
        if ( $contentClassAttribute )
            return  $contentClassAttribute->dataType();
        return null;
    }

    function contentClassAttributeName()
    {
        $db = eZDB::instance();
        $nameArray = $db->arrayQuery( "SELECT serialized_name_list FROM ezcontentclass_attribute WHERE id='$this->ContentClassAttributeID'" );

        return eZSerializedObjectNameList::nameFromSerializedString( $nameArray[0]['serialized_name_list'] );
    }

    /*!
     Creates a new eZInformationCollectionAttribute instance.
    */
    static function create( $informationCollectionID )
    {
        $row = array( 'informationcollection_id' => $informationCollectionID );
        return new eZInformationCollectionAttribute( $row );
    }

    /*!
     \static
      Fetches the information collection by object attribute ID.
    */
    static function fetchByObjectAttributeID( $id, $contentobjectAttributeID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZInformationCollectionAttribute::definition(),
                                                null,
                                                array( 'informationcollection_id' => $id,
                                                       'contentobject_attribute_id' => $contentobjectAttributeID ),
                                                $asObject );
    }

    /*!
     \static
     Removes all attributes for collected information.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezinfocollection_attribute" );
    }

    // Contains the content for this attribute
    public $Content;
}

?>
