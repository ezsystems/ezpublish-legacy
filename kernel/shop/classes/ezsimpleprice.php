<?php
//
// Definition of eZSimplePrice class
//
// Created on: <28-Nov-2005 12:26:52 dl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
        if ( get_class( $contentObjectAttribute ) == 'ezcontentobjectattribute' )
        {
            $object =& $contentObjectAttribute->object();
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
                return $this->VATPercent();
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

    function &VATPercent()
    {
        $VATType =& $this->VATType();
        return $VATType->attribute( 'percentage' );
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
        return $this->discountPercent;
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
            $VATPercent =& $this->VATPercent();
            $incVATPrice = $priceValue * ( $VATPercent + 100 ) / 100;
        }

        return $incVATPrice;
    }

    function &calcExVATPrice( $priceValue )
    {
        $exVATPrice = $priceValue;
        if ( $this->VATIncluded() )
        {
            $VATPercent =& $this->VATPercent();
            $exVATPrice = $priceValue / ( $VATPercent + 100 ) * 100;
        }

        return $exVATPrice;
    }


    /// \privatesection
    var $Price;
    var $VATType;
    var $IsVATIncluded;
    var $DiscountPercent;
}


?>