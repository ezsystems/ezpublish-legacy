<?php
//
// Definition of eZPrice class
//
// Created on: <26-Nov-2002 12:26:52 wy>
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

/*! \file ezprice.php
*/

/*!
  \class eZPrice ezprice.php
  \brief The class eZPrice does

*/
include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezvattype.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/classes/ezuserdiscountrule.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );

class eZPrice
{
    /*!
     Constructor
    */
    function eZPrice( &$classAttribute, $storedPrice = null )
    {
        $this->VatTypeArray =& eZVatType::fetchList();
        $this->CurrentUser =& eZUser::currentUser();
        $this->Price = $storedPrice;
        $VATID = $classAttribute->attribute( EZ_DATATYPESTRING_VAT_ID_FIELD );
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD ) == 1 )
            $this->IsVATIncluded = true;
        else
            $this->IsVATIncluded = false;
        $this->VATType =& eZVatType::fetch( $VATID );
        $this->DiscountRules = $this->discountRules();
    }
    function hasAttribute( $attr )
    {
        if ( $attr == "vat_type" or
             $attr == "current_user" or
             $attr == "vat_percent" or
             $attr == "inc_vat_price" or
             $attr == "ex_vat_price" or
             $attr == "discount_percent" or
             $attr == "discount_price_inc_vat" or
             $attr == "discount_price_ex_vat" or
             $attr == "has_discount" or
             $attr == "price" )
            return true;
        else
            return false;
    }

    function &attribute( $attr )
    {
        switch ( $attr )
        {
            case "vat_type" :
            {
                return $this->VatTypeArray;
            }break;
            case "current_user" :
            {
                return $this->CurrentUser;
            }break;
            case "vat_percent" :
            {
                return $this->VATType->attribute( 'percentage' );
            }break;
            case "inc_vat_price" :
            {
                if ( $this->IsVATIncluded )
                {
                    return $this->Price;
                }
                else
                {
                    $vatPercent = $this->VATType->attribute( 'percentage' );
                    $incVATPrice = $this->Price * ( $vatPercent + 100 ) / 100;
                    return $incVATPrice;
                }
            }break;
            case "ex_vat_price" :
            {
                if ( $this->IsVATIncluded )
                {
                    $vatPercent = $this->VATType->attribute( 'percentage' );
                    $exVATPrice = $this->Price / ( $vatPercent + 100 ) * 100;
                    return $exVATPrice;
                }
                else
                    return $this->Price;
            }break;
            case "discount_percent" :
            {
                $discountPercent = 0;
                $rules = $this->DiscountRules;
                foreach ( $rules as $rule )
                {
                    $percent = $rule->attribute( 'discount_percent' );
                    if ( $discountPercent < $percent )
                        $discountPercent = $percent;
                }
                return $discountPercent;
            }break;
            case "discount_price_inc_vat" :
            {
                $discountPercent = 0;
                $rules = $this->DiscountRules;
                foreach ( $rules as $rule )
                {
                    $percent = $rule->attribute( 'discount_percent' );
                    if ( $discountPercent < $percent )
                        $discountPercent = $percent;
                }

                if ( $this->IsVATIncluded )
                    $incVATPrice = $this->Price;
                else
                {
                    $vatPercent = $this->VATType->attribute( 'percentage' );
                    $incVATPrice = $this->Price * ( $vatPercent + 100 ) / 100;
                }
                $discountPrice = $incVATPrice * ( 100 - $discountPercent ) / 100;
                return $discountPrice;
            }break;
            case "discount_price_ex_vat" :
            {
                $discountPercent = 0;
                $rules = $this->DiscountRules;
                foreach ( $rules as $rule )
                {
                    $percent = $rule->attribute( 'discount_percent' );
                    if ( $discountPercent < $percent )
                        $discountPercent = $percent;
                }
                if ( $this->IsVATIncluded )
                {
                    $vatPercent = $this->VATType->attribute( 'percentage' );
                    $exVATPrice = $this->Price / ( $vatPercent + 100 ) * 100;
                }
                else
                    $exVATPrice = $this->Price;
                $discountPrice = $exVATPrice * ( 100 - $discountPercent ) / 100;
                return $discountPrice;
            }break;
            case "has_discount" :
            {
                $discountPercent = 0;
                $rules = $this->DiscountRules;
                foreach ( $rules as $rule )
                {
                    $percent = $rule->attribute( 'discount_percent' );
                    if ( $discountPercent < $percent )
                        $discountPercent = $percent;
                }
                if ( $discountPercent != 0)
                    $hasDiscount = true;
                else
                    $hasDiscount = false;
                return $hasDiscount;
            }break;
            case "price" :
            {
                return $this->Price;
            }break;
            default :
            {
                eZDebug::writeError( "Unknown attribute: " . $attr );
            }break;
        }
    }

    function discountRules()
    {
        $user = $this->CurrentUser;
        $userID = $user->attribute( 'contentobject_id' );
        $nodes =& eZContentObjectTreeNode::fetchByContentObjectID( $userID );
        $idArray = array();
        $idArray[] = $userID;
        foreach ( $nodes as $node )
        {
            $parentNodeID = $node->attribute( 'parent_node_id' );
            $idArray[] = $parentNodeID;
        }
        $rules =& eZUserDiscountRule::fetchByUserIDArray( $idArray );
        return $rules;
    }
    var $VatTypeArray;
    var $Price;
    var $CurrentUser;
    var $VATType;
    var $IsVATIncluded;
    var $DiscountRules;
}

?>
