<?php
//
// Definition of eZPriceType class
//
// Created on: <26-Apr-2002 16:54:35 bf>
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

//!! eZKernel
//! The class eZPriceType
/*!

*/

include_once( "kernel/classes/ezdatatype.php" );

define( "EZ_DATATYPESTRING_PRICE", "ezprice" );

class eZPriceType extends eZDataType
{
    function eZPriceType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_PRICE, "Price" );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) );
            $data = str_replace(" ", "", $data );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            if( ( $classAttribute->attribute( "is_required" ) == false ) &&  ( $data == "" ) )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            // TODO: Make better matching
            if ( preg_match( "#[0-9]+(.[0-9]+)?#", $data ) )
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    function storeObjectAttribute( &$attribute )
    {
    }

    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $data = $http->postVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) );
        $contentObjectAttribute->setAttribute( "data_float", $data );
    }
    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    function contentActionList( )
    {
        return array( array( 'name' => 'Add to basket',
                             'action' => 'ActionAddToBasket'
                             ),
                      array( 'name' => 'Add to wish list',
                             'action' => 'ActionAddToWishList'
                             ) );
    }

    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }
}

eZDataType::register( EZ_DATATYPESTRING_PRICE, "ezpricetype" );

?>
