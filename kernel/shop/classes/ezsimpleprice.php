<?php
//
// Definition of eZSimplePrice class
//
// Created on: <28-Nov-2005 12:26:52 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezsimpleprice.php
*/

/*!
  \class eZSimplePrice ezsimpleprice.php
  \brief Handles prices with VAT and discounts.


*/
/*!
  \class eZSimplePrice ezsimpleprice.php

The available attributes are:
  - vat_type
  - current_user
  - is_vat_included
  - selected_vat_type
  - vat_percent
  - inc_vat_price
  - ex_vat_price
  - discount_percent
  - discount_price_inc_vat
  - discount_price_ex_vat
  - has_discount
  - price
*/

include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezvattype.php' );
include_once( 'kernel/classes/ezdiscount.php' );


class eZSimplePrice
{
    function eZSimplePrice( &$classAttribute, &$contentObjectAttribute, $storedPrice = null )
    {
        $this->setVATIncluded( false );

        $price = 0.0;
        if ( isset( $storedPrice ) )
        {
            $price = $storedPrice;
        }
        $this->setPrice( $price );

        $discountPercent = 0.0;
        if ( strtolower( get_class( $contentObjectAttribute ) ) == 'ezcontentobjectattribute' )
        {
            $object =& $contentObjectAttribute->object();
            $this->ContentObject = $object;
            $discountPercent = eZDiscount::discountPercent( eZUser::currentUser(),
                                                            array( 'contentclass_id' => $object->attribute( 'contentclass_id'),
                                                                   'contentobject_id' => $object->attribute( 'id' ),
                                                                   'section_id' => $object->attribute( 'section_id') ) );
        }
        $this->setDiscountPercent( $discountPercent );
    }

    function attributes()
    {
        return array( 'price',
                      'currency',
                      'selected_vat_type',
                      'vat_type',
                      'vat_percent',
                      'is_vat_included',
                      'inc_vat_price',
                      'ex_vat_price',
                      'discount_percent',
                      'discount_price_inc_vat',
                      'discount_price_ex_vat',
                      'has_discount',
                      'current_user'            // for backward compatibility
                    );
    }

    /*!
     \return \c true if the attribute named \a $attr exists.
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function setAttribute( $attr, $value )
    {
        switch ( $attr )
        {
            case 'selected_vat_type':
            {
                $this->setVATType( $value );
            } break;

            case 'is_vat_included':
            {
                $this->setVATIncluded( $value == '1' );
            } break;

            default:
            {
                eZDebug::writeError( "Unspecified attribute: " . $attr, 'eZSimplePrice::setAttribute' );
            } break;
        }
    }

    function &attribute( $attr )
    {
        switch ( $attr )
        {
            case 'price' :
            {
                return $this->price();
            } break;

            case 'currency' :
            {
                return $this->currency();
            } break;

            case 'selected_vat_type':
            {
                return $this->VATType();
            } break;

            case 'vat_type' :
            {
                $VATType =& $this->VATType();
                return $VATType->VATTypeList();

            } break;

            case 'vat_percent' :
            {
                $vatPercent = $this->VATPercent();
                return $vatPercent;
            } break;

            case 'is_vat_included':
            {
                return $this->VATIncluded();
            } break;

            case 'inc_vat_price' :
            {
                return $this->incVATPrice();
            } break;

            case 'ex_vat_price' :
            {
               return $this->exVATPrice();
            } break;

            case 'discount_percent' :
            {
                return $this->discountPercent();
            } break;

            case 'discount_price_inc_vat' :
            {
                return $this->discountIncVATPrice();
            } break;

            case 'discount_price_ex_vat' :
            {
                return $this->discountExVATPrice();
            } break;

            case 'has_discount' :
            {
                return $this->hasDiscount();
            } break;

            case 'current_user':
            {
                return eZUser::currentUser();
            }

            default :
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZSimplePrice::attribute' );
                $retValue = null;
                return $retValue;
            } break;
        }
    }

    function &VATType()
    {
        if ( !$this->VATType )
            $this->VATType = eZVatType::create();

        return $this->VATType;
    }

    function setVATType( $VATID )
    {
        $this->VATType = eZVatType::fetch( $VATID );
        if ( !$this->VATType )
        {
            eZDebug::writeDebug( "VAT type with id '$VATID' is unavailable", 'eZSimplePrice::setVATType');
            $this->VATType = eZVatType::create();
        }
    }

    /**
     * Can return dynamic percentage depending on product and country the user is from.
     */
    function VATPercent( $object = false, $country = false )
    {
        $VATType =& $this->VATType();

        if ( $object === false )
        {
            if ( $this->ContentObject === null )
                return $VATType->attribute( 'percentage' );

            $object = $this->ContentObject;
        }

        return $VATType->getPercentage( $object, $country );
    }

    function &VATIncluded()
    {
        return $this->IsVATIncluded;
    }

    function setVATIncluded( $VATIncluded )
    {
        $this->IsVATIncluded = $VATIncluded ;
    }

    function &price()
    {
        return $this->Price;
    }

    function setPrice( $value )
    {
        $this->Price = $value;
    }

    function &incVATPrice()
    {
        return $this->calcIncVATPrice( $this->price() );
    }

    function &exVATPrice()
    {
        return $this->calcExVATPrice( $this->price() );
    }

    function &discountPercent()
    {
        return $this->DiscountPercent;
    }

    function setDiscountPercent( $percent )
    {
        $this->DiscountPercent = $percent;
    }

    function &hasDiscount()
    {
        $hasDiscount = false;
        $discountPercent = $this->discountPercent();
        if ( $discountPercent != 0 )
            $hasDiscount = true;

        return $hasDiscount;
    }

    function &discountIncVATPrice()
    {
        return $this->calcDiscountIncVATPrice( $this->price() );
    }

    function &discountExVATPrice()
    {
        return $this->calcDiscountExVATPrice( $this->price() );
    }

    /*!
     \returns discount percentage. Backward compatibility.
    */
    function discount()
    {
        return $this->discountPercent();
    }

    function &calcDiscountIncVATPrice( $priceValue )
    {
        $discountPercent =& $this->discountPercent();
        $incVATPrice =& $this->calcIncVATPrice( $priceValue );
        $discountPrice = $incVATPrice * ( 100 - $discountPercent ) / 100;
        return $discountPrice;
    }

    function &calcDiscountExVATPrice( $priceValue )
    {
        $discountPercent =& $this->discountPercent();
        $exVATPrice =& $this->calcExVATPrice( $priceValue );
        $discountPrice = $exVATPrice * ( 100 - $discountPercent ) / 100;
        return $discountPrice;
    }

    function &calcIncVATPrice( $priceValue )
    {
        $incVATPrice = $priceValue;
        if ( !$this->VATIncluded() )
        {
            $VATPercent = $this->VATPercent();
            // If VAT is unknown yet then we use zero VAT percentage for price calculation.
            if ( $VATPercent == -1 )
                $VATPercent = 0;
            $incVATPrice = $priceValue * ( $VATPercent + 100 ) / 100;
        }

        return $incVATPrice;
    }

    function &calcExVATPrice( $priceValue )
    {
        $exVATPrice = $priceValue;
        if ( $this->VATIncluded() )
        {
            $VATPercent = $this->VATPercent();
            // If VAT is unknown yet then we use zero VAT percentage for price calculation.
            if ( $VATPercent == -1 )
                $VATPercent = 0;
            $exVATPrice = $priceValue / ( $VATPercent + 100 ) * 100;
        }

        return $exVATPrice;
    }

    function &currency()
    {
        $currencyCode = '';
        return $currencyCode;
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $price =& $classAttribute->content();
        if ( $price )
        {
            $vatIncluded = $price->attribute( 'is_vat_included' );
            $vatTypes = $price->attribute( 'vat_type' );
            $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'vat-included',
                                                                                     array( 'is-set' => $vatIncluded ? 'true' : 'false' ) ) );
            $vatTypeNode = eZDOMDocument::createElementNode( 'vat-type' );
            $chosenVatType = $classAttribute->attribute( 'data_float1' );
            $gotVat = false;
            foreach ( $vatTypes as $vatType )
            {
                $id = $vatType->attribute( 'id' );
                if ( $id == $chosenVatType )
                {
                    $vatTypeNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $vatType->attribute( 'name' ) ) );
                    $vatTypeNode->appendAttribute( eZDOMDocument::createAttributeNode( 'percentage', $vatType->attribute( 'percentage' ) ) );
                    $gotVat = true;
                    break;
                }
            }
            if ( $gotVat )
                $attributeParametersNode->appendChild( $vatTypeNode );
        }
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $vatNode =& $attributeParametersNode->elementByName( 'vat-included' );
        $vatIncluded = strtolower( $vatNode->attributeValue( 'is-set' ) ) == 'true';
        $classAttribute->setAttribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD, $vatIncluded );
        $vatTypeNode =& $attributeParametersNode->elementByName( 'vat-type' );
        $vatName = $vatTypeNode->attributeValue( 'name' );
        $vatPercentage = $vatTypeNode->attributeValue( 'percentage' );
        $vatID = false;
        $vatTypes = eZVATType::fetchList();
        foreach ( array_keys( $vatTypes ) as $vatTypeKey )
        {
            $vatType =& $vatTypes[$vatTypeKey];
            if ( $vatType->attribute( 'name' ) == $vatName and
                 $vatType->attribute( 'percentage' ) == $vatPercentage )
            {
                $vatID = $vatType->attribute( 'id' );
                break;
            }
        }
        if ( !$vatID )
        {
            $vatType = eZVATType::create();
            $vatType->setAttribute( 'name', $vatName );
            $vatType->setAttribute( 'percentage', $vatPercentage );
            $vatType->store();
            $vatID = $vatType->attribute( 'id' );
        }
        $classAttribute->setAttribute( EZ_DATATYPESTRING_VAT_ID_FIELD, $vatID );
    }

    /// \privatesection
    public $Price;
    public $VATType;
    public $IsVATIncluded;
    public $DiscountPercent;
    public $ContentObject;
}


?>
