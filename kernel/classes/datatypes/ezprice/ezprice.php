<?php
//
// Definition of eZPrice class
//
// Created on: <26-Nov-2002 12:26:52 wy>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
    function eZPrice( &$classAttribute, &$contentObjectAttribute, $storedPrice = null )
    {
        $this->ContentObjectAttribute =& $contentObjectAttribute;
        $this->VatTypeArray =& eZVatType::fetchList();
        $this->CurrentUser =& eZUser::currentUser();
        $this->Price = $storedPrice;
        $VATID = $classAttribute->attribute( EZ_DATATYPESTRING_VAT_ID_FIELD );
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD ) == 1 )
            $this->IsVATIncluded = true;
        else
            $this->IsVATIncluded = false;
        $this->VATType =& eZVatType::fetch( $VATID );
        $this->Discount = $this->discount();
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "vat_type" or
             $attr == "current_user" or
             $attr == "is_vat_included" or
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
                if ( $this->VATType != null )
                    return $this->VATType->attribute( 'percentage' );
                else
                    return 0;
            }break;
            case "is_vat_included":
            {
                return $this->IsVATIncluded;
            }break;
            case "inc_vat_price" :
            {
                if ( $this->IsVATIncluded )
                {
                    return $this->Price;
                }
                else
                {
                    if ( $this->VATType != null )
                        $vatPercent = $this->VATType->attribute( 'percentage' );
                    else
                        $vatPercent = 0;
                    $incVATPrice = $this->Price * ( $vatPercent + 100 ) / 100;
                    return $incVATPrice;
                }
            }break;
            case "ex_vat_price" :
            {
                if ( $this->IsVATIncluded )
                {
                    if ( $this->VATType != null )
                        $vatPercent = $this->VATType->attribute( 'percentage' );
                    else
                        $vatPercent = 0;
                    $exVATPrice = $this->Price / ( $vatPercent + 100 ) * 100;
                    return $exVATPrice;
                }
                else
                    return $this->Price;
            }break;
            case "discount_percent" :
            {
                return $this->Discount;
            }break;
            case "discount_price_inc_vat" :
            {
                $discountPercent = $this->Discount;

                if ( $this->IsVATIncluded )
                    $incVATPrice = $this->Price;
                else
                {
                    if ( $this->VATType != null )
                        $vatPercent = $this->VATType->attribute( 'percentage' );
                    else
                        $vatPercent = 0;
                    $incVATPrice = $this->Price * ( $vatPercent + 100 ) / 100;
                }
                $discountPrice = $incVATPrice * ( 100 - $discountPercent ) / 100;
                return $discountPrice;
            }break;
            case "discount_price_ex_vat" :
            {
                $discountPercent = $this->Discount;
                if ( $this->IsVATIncluded )
                {
                    if ( $this->VATType != null )
                        $vatPercent = $this->VATType->attribute( 'percentage' );
                    else
                        $vatPercent = 0;
                    $exVATPrice = $this->Price / ( $vatPercent + 100 ) * 100;
                }
                else
                    $exVATPrice = $this->Price;
                $discountPrice = $exVATPrice * ( 100 - $discountPercent ) / 100;
                return $discountPrice;
            }break;
            case "has_discount" :
            {
                $discountPercent = $this->Discount;

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

    /*!
     \returns the discountrules for the currrent user
    */
    function discount()
    {
        $bestMatch = 0.0;

        if ( get_class( $this->ContentObjectAttribute ) == 'ezcontentobjectattribute' )
        {
            $db =& eZDB::instance();
            $user =& $this->CurrentUser;
            $groups =& $user->groups();
            $idArray =& array_merge( $groups, $user->attribute( 'contentobject_id' ) );

            // Fetch discount rules for the current user
            $rules =& eZUserDiscountRule::fetchByUserIDArray( $idArray );

            if ( count( $rules ) > 0 )
            {
                $i = 1;
                $subRuleStr = "";
                foreach ( $rules as $rule )
                {
                    $subRuleStr .= $rule->attribute( 'id' );
                    if ( $i < count( $rules ) )
                        $subRuleStr .= ", ";
                    $i++;
                }

                // Fetch the discount sub rules
                $subRules =& $db->arrayQuery( "SELECT * FROM
                                       ezdiscountsubrule
                                       WHERE discountrule_id IN ( $subRuleStr )
                                       ORDER BY discount_percent DESC" );

                // cache object if we need it
                $object = false;
                // Find the best matching discount rule
                foreach ( $subRules as $subRule )
                {
                    if ( $subRule['discount_percent'] > $bestMatch )
                    {
                        // Rule has better discount, see if it matches
                        if ( $subRule['limitation'] == '*' )
                            $bestMatch = $subRule['discount_percent'];
                        else
                        {
                            // Do limitation check
                            $limitationArray =& $db->arrayQuery( "SELECT * FROM
                                       ezdiscountsubrule_value
                                       WHERE discountsubrule_id='" . $subRule['id']. "'" );

                            if ( $object == false )
                                $object =& $this->ContentObjectAttribute->object();

                            $hasSectionLimitation = false;
                            $hasClassLimitation = false;
                            $hasObjectLimitation = false;
                            $objectMatch = false;
                            $sectionMatch = false;
                            $classMatch = false;
                            foreach ( $limitationArray as $limitation )
                            {
                                if ( $limitation['issection'] == '1' )
                                {
                                    $hasSectionLimitation = true;

                                    if ( $object->attribute( 'section_id' ) == $limitation['value'] )
                                        $sectionMatch = true;
                                }
                                elseif ( $limitation['issection'] == '2' )
                                {
                                    $hasObjectLimitation = true;

                                    if ( $object->attribute( 'id' ) == $limitation['value'] )
                                        $objectMatch = true;
                                }
                                else
                                {
                                    $hasClassLimitation = true;
                                    if ( $object->attribute( 'contentclass_id' ) == $limitation['value'] )
                                        $classMatch = true;
                                }
                            }

                            $match = true;
                            if ( ( $hasClassLimitation == true ) and ( $classMatch == false ) )
                                $match = false;

                            if ( ( $hasSectionLimitation == true ) and ( $sectionMatch == false ) )
                                $match = false;

                            if ( ( $hasObjectLimitation == true ) and ( $objectMatch == false ) )
                                $match = false;

                            if ( $match == true  )
                                $bestMatch = $subRule['discount_percent'];
                        }
                    }
                }
            }
        }
        return $bestMatch;
    }


    var $VatTypeArray;
    var $Price;
    var $CurrentUser;
    var $VATType;
    var $IsVATIncluded;
    var $ContentObjectAttribute;
    var $Discount;
}

?>
