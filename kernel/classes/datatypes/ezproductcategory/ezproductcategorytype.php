<?php
//
// Definition of eZProductCategoryType class
//
// Created on: <19-Feb-2006 14:08:26 vs>
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

/*!
  \class eZProductCategoryType ezproductcategorytype.php
  \ingroup eZDatatype
  \brief Stores product category.

*/

class eZProductCategoryType extends eZDataType
{
    const DATA_TYPE_STRING = "ezproductcategory";

    function eZProductCategoryType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Product category", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_int' => 'value' ) ) );
    }

   /*!
     Sets the default value.
    */
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

    /*!
      Validates the http post var.
    */
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

        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                             'Input required.' ) );
        return eZInputValidator::STATE_INVALID;
    }

    /*!
     Fetches the http post var and stores it in the data instance.
    */
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

   /*!
    \reimp
    Fetches the http post variable for collected information
   */
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

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return true;
    }

    /*!
     \reimp
    */
    function sortKey( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'int';
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $category = eZProductCategory::fetch( $contentObjectAttribute->attribute( 'data_int' ) );
        return $category;
    }

    /*!
     \reimp
     */
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

    /*!
     Returns the integer value.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        $categoryID = $contentObjectAttribute->attribute( "data_int" );
        $category = $categoryID > 0 ? eZProductCategory::fetch( $categoryID ) : false;
        return is_object( $category ) ? $category->attribute( 'name' ) : '';
    }

    /*!
      \reimp
    */
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

eZDataType::register( eZProductCategoryType::DATA_TYPE_STRING, "eZProductCategoryType" );

?>
