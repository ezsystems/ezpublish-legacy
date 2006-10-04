<?php
//
// Definition of eZShippingManager class
//
// Created on: <12-Dec-2005 12:00:06 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

/*! \file ezshippingmanager.php
*/

/*!
  \class eZShippingManager ezshippingmanager.php
  \brief The class eZShippingManager does
*/

class eZShippingManager
{
    /*!
     Constructor
    */
    function eZShippingManager()
    {
    }

    /*!
     \public
     \static

     The function are fetching the shipping info and need to be reimplemented in a new shippinghandler.
     It's also possible to return additional parameters to use in the templates.

     \return an array with shipping info.
     An example for an array that should be returned.

     \code
     array( 'shipping_items' => array( array( 'description'     => 'Shipping vat: 12%',
                                              'cost'            => 50.25,
                                              'vat_value'       => 12,
                                              'is_vat_inc'      => 0,
                                              'management_link' => '/myshippingmodule/options/12' ),
                                       array( 'description'     => 'Shipping vat: 25%',
                                              'cost'            => 100.75,
                                              'vat_value'       => 25,
                                              'is_vat_inc'      => 1,
                                              'management_link' => '/myshippingmodule/options/25' ) ),
            'description'     => 'Total Shipping',
            'cost'            => 10.25,
            'vat_value'       => false,
            'is_vat_inc'      => 1,
            'management_link' => '/myshippingmodule/options' );
     \endcode

     An example for the shippingvalues with only one shippingitem.
     \code
     array( 'description'     => 'Total Shipping vat: 16%',
            'cost'            => 10.25,
            'vat_value'       => 16,
            'is_vat_inc'      => 1,
            'management_link' => '/myshippingmodule/options/1234' );
     \endcode

     The returned array for each shipping item should consist of these keys:
     - order_id - The order id for the current order.
     - description - An own description of the shipping item.
     - cost - A float value of the cost for the shipping. The value should be a float value.
     - vat_value - The vat value that should be added to the shipping item. The value should be an integer or
                   false if the cost is combined by several VAT prices.
     - is_vat_inc - Integer, either 0, 1. 0: The cost is excluded VAT.
                                          1: the cost is included VAT.

     - management_link - Example of an additional parameter that can be used
       in a template. Ex: basket.tpl
     */
    function getShippingInfo( $productCollectionID )
    {
        if ( !is_object( $handler = eZShippingManager::loadShippingHandler() ) )
            return null;

        return $handler->getShippingInfo( $productCollectionID );
    }

    /*!
     \public
     \static

     The function to update any additional shippinginfo and need to be reimplemented
     in a new shippinghandler.

     \return true if all updates are ok.
             false if the update went wrong.
     */
    function updateShippingInfo( $productCollectionID )
    {
        if ( is_object( $handler = eZShippingManager::loadShippingHandler() ) )
            return $handler->updateShippingInfo( $productCollectionID );
    }

    /*!
     Purge shipping information for a stale product collection that is about to be removed.
     \public
     \static

     Should be used when a basket is removed. All shipping information related to the
     productCollectionID should be removed.


     \return true if everything went ok.
             false if an error occurred.
     */
    function purgeShippingInfo( $productCollectionID )
    {
        if ( is_object( $handler = eZShippingManager::loadShippingHandler() ) )
            return $handler->purgeShippingInfo( $productCollectionID );
    }

    /*!
     Load shipping handler (if specified).

     \private
     \static
     \return true if no handler specified,
             false if a handler specified but could not be loaded,
             handler object if handler specified and found.
     */
    function loadShippingHandler()
    {
        $shopINI =& eZINI::instance( 'shop.ini' );

        if ( !$shopINI->hasVariable( 'ShippingSettings', 'Handler' ) )
            return true;

        $handlerName = $shopINI->variable( 'ShippingSettings', 'Handler' );
        $repositoryDirectories = $shopINI->variable( 'ShippingSettings', 'RepositoryDirectories' );
        $extensionDirectories = $shopINI->variable( 'ShippingSettings', 'ExtensionDirectories' );

        $baseDirectory = eZExtension::baseDirectory();
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/shippinghandlers';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }

        $foundHandler = false;
        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/{$handlerName}shippinghandler.php";

            if ( file_exists( $includeFile ) )
            {
                $foundHandler = true;
                break;
            }
        }

        if ( !$foundHandler )
        {
            eZDebug::writeError( "Shipping handler '$handlerName' not found, " .
                                 "searched in these directories: " .
                                 implode( ', ', $repositoryDirectories ),
                                 'eZShippingManager::loadShippingHandler' );
            return false;
        }

        require_once( $includeFile );
        $className = $handlerName . 'ShippingHandler';
        if ( !class_exists ( $className ) )
        {
            eZDebug::writeError( "Cannot instantiate non-existent class: '$className'",
                                 'eZShippingManager::loadShippingHandler' );
            return null;
        }

        return new $className;
    }

    /*!
     Calculate the vat prices returned by the shippinghandler.
     \public
     \static

     Need to receive the following values (In the arrays will also consist of
     additional parameters, see the function getShippingInfo() for more information):

     Option 1: An array with shipping items with atleast these values:
     \code
     array( 'shipping_items' => array( array( 'cost'       => 50.25,
                                              'vat_value'  => 12,
                                              'is_vat_inc' => 0 ),
                                       array( 'cost'       => 100.75,
                                              'vat_value'  => 25,
                                              'is_vat_inc' => 1 ) ),
            'cost'       => 182.22,
            'vat_value'  => false,
            'is_vat_inc' => 1 );
     \endcode

     Option 2, backwards compatible:
     \code
     array( 'cost'       => 50.25,
            'vat_value'  => 12,
            'is_vat_inc' => 0 );
     \endcode

     \return array with calculated vat prices.
             Example of return value:
     \code
     array( 'vat_shipping_list_ex_vat'  => array(  5 => 345.25,
                                                  25 => 112.50 ),
            'vat_shipping_list_inc_vat' => array(  5 => 125,
                                                  25 => 150.50 ),
            'total_shipping_ex_vat'  => 1234,
            'total_shipping_inc_vat' => 2345 );
     \endcode

     - vat_shipping_list_ex_vat  - contains an array with prices (float) ex vat
                                   where you have the vat (integer) value as the array key.
     - vat_shipping_list_inc_vat - contains an array with prices (float) inc vat
                                   where you have the vat value (integer) as the array key.
     - total_shipping_ex_vat     - contains the sum of all prices (float) ex vat.
     - total_shipping_inc_vat    - contains the sum of all prices (float) inc vat.
     */
    function vatPriceInfo( $shippingInfo )
    {
        $totalShippingExVat = 0;
        $totalShippingIncVat = 0;
        $totalShippingVat = 0;
        $subShippingExVat = array();
        $subShippingIncVat = array();
        $shippingVatList = array();
        if ( isset( $shippingInfo['shipping_items'] ) )
        {
            foreach ( $shippingInfo['shipping_items'] as $shippingItem )
            {
                $vatValue = $shippingItem['vat_value'];
                $shippingCost = $shippingItem['cost'];
                $isVatInc = $shippingItem['is_vat_inc'];

                if ( $isVatInc == 1 )
                {
                    $divideValue = ( $vatValue / 100 ) + 1;
                    if ( !isset( $subShippingExVat[$vatValue] ) )
                    {
                        $subShippingExVat[$vatValue] = ( $shippingCost / $divideValue );
                        $subShippingIncVat[$vatValue] = $shippingCost;
                        $subShippingVat[$vatValue] = ( $shippingCost - ( $shippingCost / $divideValue ) );
                    }
                    else
                    {
                        $subShippingExVat[$vatValue] += ( $shippingCost / $divideValue );
                        $subShippingIncVat[$vatValue] += $shippingCost;
                        $subShippingVat[$vatValue] += ( $shippingCost - ( $shippingCost / $divideValue ) );
                    }
                }
                else
                {
                    $multiplier = ( $vatValue / 100 ) + 1;
                    if ( !isset( $subShippingExVat[$vatValue] ) )
                    {
                        $subShippingExVat[$vatValue] = $shippingCost;
                        $subShippingIncVat[$vatValue] = ( $shippingCost * $multiplier );
                        $subShippingVat[$vatValue] = ( ( $shippingCost * $multiplier ) - $shippingCost );
                    }
                    else
                    {
                        $subShippingExVat[$vatValue] += $shippingCost;
                        $subShippingIncVat[$vatValue] += ( $shippingCost * $multiplier );
                        $subShippingVat[$vatValue] += ( ( $shippingCost * $multiplier ) - $shippingCost );
                    }
                }

                if ( !isset( $shippingVatList[$vatValue]['shipping_ex_vat'] ) )
                {
                    $shippingVatList[$vatValue]['shipping_ex_vat'] = $subShippingExVat[$vatValue];
                    $shippingVatList[$vatValue]['shipping_inc_vat'] = $subShippingIncVat[$vatValue];
                    $shippingVatList[$vatValue]['shipping_vat'] = $subShippingVat[$vatValue];
                }
                else
                {
                    $shippingVatList[$vatValue]['shipping_ex_vat'] += $subShippingExVat[$vatValue];
                    $shippingVatList[$vatValue]['shipping_inc_vat'] += $subShippingIncVat[$vatValue];
                    $shippingVatList[$vatValue]['shipping_vat'] += $subShippingVat[$vatValue];
                }

                $totalShippingExVat += $subShippingExVat[$vatValue];
                $totalShippingIncVat += $subShippingIncVat[$vatValue];
                $totalShippingVat += $subShippingVat[$vatValue];
            }
        }
        else // made for backwards compability.
        {
            $vatValue = $shippingInfo['vat_value'];
            $shippingCost = $shippingInfo['cost'];
            $isVatInc = $shippingInfo['is_vat_inc'];
            if ( $isVatInc == 1 )
            {
                $divideValue = ( $vatValue / 100 ) + 1;
                $subShippingExVat[$vatValue] = $shippingCost;
                $subShippingIncVat[$vatValue] = ( $shippingCost / $divideValue );
                $subShippingVat[$vatValue] = ( $subShippingIncVat[$vatValue] - $subShippingExVat[$vatValue] );
            }
            else
            {
                $multiplier = ( $vatValue / 100 ) + 1;
                $subShippingExVat[$vatValue] = $shippingCost;
                $subShippingIncVat[$vatValue] = ( $shippingCost * $multiplier );
                $subShippingVat[$vatValue] = ( $subShippingIncVat[$vatValue] - $subShippingExVat[$vatValue] );
            }

            $shippingVatList[$vatValue]['shipping_ex_vat'] = $subShippingExVat[$vatValue];
            $shippingVatList[$vatValue]['shipping_inc_vat'] = $subShippingIncVat[$vatValue];
            $shippingVatList[$vatValue]['shipping_vat'] = $subShippingVat[$vatValue];

            $totalShippingExVat = $subShippingExVat[$vatValue];
            $totalShippingIncVat = $subShippingIncVat[$vatValue];
            $totalShippingVat = $subShippingVat[$vatValue];
        }
        $returnArray = array( 'vat_shipping_list_ex_vat' => $subShippingExVat,
                              'vat_shipping_list_inc_vat' => $subShippingIncVat,
                              'vat_shipping_list_vat' => $subShippingVat,
                              'shipping_vat_list' => $shippingVatList,
                              'total_shipping_ex_vat' => $totalShippingExVat,
                              'total_shipping_inc_vat' => $totalShippingIncVat,
                              'total_shipping_vat' => $totalShippingVat );
        return $returnArray;
    }


    function updatePriceInfo( $productCollectionID, &$basketInfo )
    {
        $shippingInfo = eZShippingManager::getShippingInfo( $productCollectionID );
        $additionalShippingValues = eZShippingManager::vatPriceInfo( $shippingInfo );

        foreach ( $additionalShippingValues['shipping_vat_list'] as $vatValue => $additionalShippingValueArray )
        {
            $shippingExVAT = $additionalShippingValueArray['shipping_ex_vat'];
            $shippingIncVAT = $additionalShippingValueArray['shipping_inc_vat'];
            $shippingVat = $additionalShippingValueArray['shipping_vat'];

            if ( !isset( $basketInfo['price_info'][$vatValue]['total_price_ex_vat'] ) )
            {
                $basketInfo['price_info'][$vatValue]['total_price_ex_vat'] = $shippingExVAT;
                $basketInfo['price_info'][$vatValue]['total_price_inc_vat'] = $shippingIncVAT;
                $basketInfo['price_info'][$vatValue]['total_price_vat'] = $shippingVat;

                $basketInfo['total_price_info']['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['total_price_info']['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['total_price_info']['total_price_vat'] += $shippingVat;
            }
            else
            {
                $basketInfo['price_info'][$vatValue]['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['price_info'][$vatValue]['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['price_info'][$vatValue]['total_price_vat'] += $shippingVat;

                $basketInfo['total_price_info']['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['total_price_info']['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['total_price_info']['total_price_vat'] += $shippingVat;
            }

            if ( !isset( $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_ex_vat'] ) )
            {
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_ex_vat'] = $shippingExVAT;
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_inc_vat'] = $shippingIncVAT;
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_vat'] = ( $shippingIncVAT - $shippingExVAT );
            }
            else
            {
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_vat'] += ( $shippingIncVAT - $shippingExVAT );
            }

            if ( !isset( $basketInfo['additional_info']['shipping_total']['total_price_ex_vat'] ) )
            {
                $basketInfo['additional_info']['shipping_total']['total_price_ex_vat'] = $shippingExVAT;
                $basketInfo['additional_info']['shipping_total']['total_price_inc_vat'] = $shippingIncVAT;
                $basketInfo['additional_info']['shipping_total']['total_price_vat'] = ( $shippingIncVAT - $shippingExVAT );
            }
            else
            {
                $basketInfo['additional_info']['shipping_total']['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['additional_info']['shipping_total']['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['additional_info']['shipping_total']['total_price_vat'] += ( $shippingIncVAT - $shippingExVAT );
            }
        }
    }
}
?>
