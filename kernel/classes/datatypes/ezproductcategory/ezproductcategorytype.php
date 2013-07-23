<?php
/**
 * File containing the eZProductCategoryType class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Stores an eZProductCategory object
 *
 * @package kernel
 */
class eZProductCategoryType extends eZDataType
{
    const DATA_TYPE_STRING = "ezproductcategory";

    /**
     * Initializes the datatype
     */
    function eZProductCategoryType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Product category", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_int' => 'value' ) ) );
    }

    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataInt = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $dataInt );
        }
        else
        {
            $default = 0;
            $contentObjectAttribute->setAttribute( "data_int", $default );
        }
    }

    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( !$contentObjectAttribute->validateIsRequired() )
            return eZInputValidator::STATE_ACCEPTED;

        if ( $http->hasPostVariable( $base . "_category_id_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_category_id_" . $contentObjectAttribute->attribute( "id" ) );

            if ( is_numeric( $data ) )
                return eZInputValidator::STATE_ACCEPTED;
        }

        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                             'Input required.' ) );
        return eZInputValidator::STATE_INVALID;
    }

    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_category_id_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $data = $http->postVariable( $base . "_category_id_" . $contentObjectAttribute->attribute( "id" ) );
            if ( !is_numeric( $data ) )
                $data = 0;
        }
        else
        {
            $data = 0;
        }

        $contentObjectAttribute->setAttribute( "data_int", $data );
        return true;
    }

    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_category_id_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $data = $http->postVariable( $base . "_category_id_" . $contentObjectAttribute->attribute( "id" ) );
            if ( !is_numeric( $data ) )
                $data = 0;
        }
        else
        {
            $data = 0;
        }

        $collectionAttribute->setAttribute( 'data_int', $data );
        return true;
    }

    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    function isIndexable()
    {
        return true;
    }

    function isInformationCollector()
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    function sortKeyType()
    {
        return 'int';
    }

    /**
     * @inheritdoc
     * @return eZProductCategory
     */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $category = eZProductCategory::fetch( $contentObjectAttribute->attribute( 'data_int' ) );
        return $category;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $productCategory = $this->objectAttributeContent( $contentObjectAttribute );
        return is_object( $productCategory );
    }

    function toString( $contentObjectAttribute )
    {
        $category =  $contentObjectAttribute->attribute( 'content' );
        if ( $category )
        {
            return implode( '|', array( $category->attribute( 'name' ), $category->attribute( 'id' ) ) );
        }
        return '';
    }

    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;
        $categoryData = explode( '|', $string );

        if ( isset ( $categoryData[1]  ) )
        {
            $category = eZProductCategory::fetch( $categoryData[1] );
            if ( $category )
            {
                $contentObjectAttribute->setAttribute( 'data_int', $category->attribute( 'id' ) );
                return  true;
            }
        }

        if ( isset ( $categoryData[1]  ) )
        {
            $category = eZProductCategory::fetchByName( $categoryData[0] );
            if ( $category )
            {
                $contentObjectAttribute->setAttribute( 'data_int', $category->attribute( 'id' ) );
                return  true;
            }
        }
        return false;
    }

    function title( $contentObjectAttribute, $name = null )
    {
        $categoryID = $contentObjectAttribute->attribute( "data_int" );
        $category = $categoryID > 0 ? eZProductCategory::fetch( $categoryID ) : false;
        return is_object( $category ) ? $category->attribute( 'name' ) : '';
    }

    function diff( $old, $new, $options = null )
    {
        return null;
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        $default = 0;
        return array( 'data_int' => $default, 'sort_key_int' => $default );
    }
}

?>
