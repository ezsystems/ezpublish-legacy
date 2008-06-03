<?php
//
// Definition of eZDefaultVATHandler class
//
// Created on: <15-Dec-2005 15:42:29 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*! \file ezdefaultvathandler.php
*/

/*!
  \class eZDefaultVATHandler ezdefaultvathandler.php
  \brief Default VAT handler.

  Provides basic VAT charging rules.
  Resulting VAT percentage may depend on product category
  and country the user is from.
*/

class eZDefaultVATHandler
{
    /**
     * \public
     * \static
     */
    function getVatPercent( $object, $country )
    {
        $productCategory = eZDefaultVATHandler::getProductCategory( $object );

        // If product category is not specified
        if ( $productCategory === null )
        {
            require_once( 'kernel/classes/ezproductcategory.php' );

            // Default to a fake product category (*) that will produce
            // weak match on category for any VAT rule.
            $productCategory = new eZProductCategory( array( 'id' => -1,
                                                             'name' => '*' ) );
        }

        // If country is not specified
        if ( !$country )
        {
            // Default to a fake country that will produce
            // weak match on country for any VAT rule.
            $country = '*';
        }

        $vatType = eZDefaultVATHandler::chooseVatType( $productCategory, $country );

        return $vatType->attribute( 'percentage' );
    }

    /**
     * Determine object's product category.
     *
     * \private
     * \static
     */
    function getProductCategory( $object )
    {
        $ini = eZINI::instance( 'shop.ini' );
        if ( !$ini->hasVariable( 'VATSettings', 'ProductCategoryAttribute' ) )
        {
            eZDebug::writeError( "Cannot find product category: please specify its attribute identifier " .
                                 "in the following setting: shop.ini.[VATSettings].ProductCategoryAttribute" );
            return null;
        }

        $categoryAttributeName = $ini->variable( 'VATSettings', 'ProductCategoryAttribute' );

        if ( !$categoryAttributeName )
        {
            eZDebug::writeError( "Cannot find product category: empty attribute name specified " .
                                 "in the following setting: shop.ini.[VATSettings].ProductCategoryAttribute" );

            return null;
        }

        $productDataMap = $object->attribute( 'data_map' );

        if ( !isset( $productDataMap[$categoryAttributeName] ) )
        {
            eZDebug::writeError( "Cannot find product category: there is no attribute '$categoryAttributeName' in object '" .
                                  $object->attribute( 'name' ) .
                                  "' of class '" .
                                  $object->attribute( 'class_name' ) . "'." );
            return null;
        }

        $categoryAttribute = $productDataMap[$categoryAttributeName];
        $productCategory = $categoryAttribute->attribute( 'content' );

        if ( $productCategory === null )
        {
            eZDebug::writeNotice( "Product category is not specified in object '" .
                                   $object->attribute( 'name' ) .
                                   "' of class '" .
                                   $object->attribute( 'class_name' ) . "'." );
            return null;
        }

        return $productCategory;
    }

    /**
     * Choose the best matching VAT type for given product category and country.
     *
     * We calculate priority for each VAT type and then choose
     * the VAT type having the highest priority
     * (or first of those having the highest priority).
     *
     * VAT type priority is calculated from county match and category match as following:
     *
     * CountryMatch  = 0
     * CategoryMatch = 1
     *
     * if ( <there is exact match on country> )
     *     CountryMatch = 2
     * elseif ( <there is weak match on country> )
     *     CountryMatch = 1
     *
     * if ( <there is exact match on product category> )
     *     CategoryMatch = 2
     * elseif ( <there is weak match on product category> )
     *     CategoryMatch = 1
     *
     * if ( <there is match on both country and category )
     *     VatTypePriority = CountryMatch * 2 + CategoryMatch - 2
     * else
     *     VatTypePriority = 0
     *
     * \private
     * \static
     */
    function chooseVatType( $productCategory, $country )
    {
        require_once( 'kernel/classes/ezvatrule.php' );

        $vatRules = eZVatRule::fetchList();

        $catID = $productCategory->attribute( 'id' );

        $vatPriorities = array();
        foreach ( $vatRules as $rule )
        {
            $ruleCountry = $rule->attribute( 'country_code' );
            $ruleCatIDs  = $rule->attribute( 'product_categories_ids' );
            $ruleVatID   = $rule->attribute( 'vat_type' );

            $categoryMatch = 0;
            $countryMatch  = 0;

            if ( $ruleCountry == '*' )
                $countryMatch = 1;
            elseif ( $ruleCountry == $country )
                $countryMatch = 2;

            if ( !$ruleCatIDs )
                $categoryMatch = 1;
            elseif ( in_array( $catID, $ruleCatIDs ) )
                $categoryMatch = 2;

            if ( $countryMatch && $categoryMatch )
                $vatPriority = $countryMatch * 2 + $categoryMatch - 2;
            else
                $vatPriority = 0;

            if ( !isset( $vatPriorities[$vatPriority] ) )
                $vatPriorities[$vatPriority] = $ruleVatID;
        }

        krsort( $vatPriorities, SORT_NUMERIC );


        $bestPriority = 0;
        if ( $vatPriorities )
            $bestPriority = array_shift( array_keys( $vatPriorities ) );

        if ( $bestPriority == 0 )
        {
            eZDebug::writeError( "Cannot find a suitable VAT type " .
                                 "for country '" . $country . "'" .
                                 " and category '" . $productCategory->attribute( 'name' ). "'." );

            return new eZVatType( array( "id" => 0,
                                         "name" => ezi18n( 'kernel/shop', 'None' ),
                                         "percentage" => 0.0 ) );
        }

        $bestVatTypeID = array_shift( $vatPriorities );
        $bestVatType = eZVatType::fetch( $bestVatTypeID );

        eZDebug::writeDebug(
            sprintf( "Best matching VAT for '%s'/'%s' is '%s' (%d%%)",
                     $country,
                     $productCategory->attribute( 'name' ),
                     $bestVatType->attribute( 'name' ),
                     $bestVatType->attribute( 'percentage' ) ) );

        return $bestVatType;
    }
}

?>
