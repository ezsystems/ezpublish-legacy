<?php
//
// Definition of eZDefaultBasketInfoHandler class
//
// Created on: <09-Nov-2006 11:38:45 bjorn>
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


class eZDefaultBasketInfoHandler
{
    /*!
     Constructor
    */
    function eZDefaultBasketInfoHandler()
    {
    }

    /*!
     Calculate additional information about vat and prices for items in the basket.
    */
    function updatePriceInfo( $productCollectionID, &$basketInfo )
    {
        $shippingInfo = eZShippingManager::getShippingInfo( $productCollectionID );
        $additionalShippingValues = eZShippingManager::vatPriceInfo( $shippingInfo );
        $returnValue = false;
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

                $basketInfo['total_price_info']['total_price_ex_vat'] = $shippingExVAT;
                $basketInfo['total_price_info']['total_price_inc_vat'] = $shippingIncVAT;
                $basketInfo['total_price_info']['total_price_vat'] = $shippingVat;
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

        if ( count( $additionalShippingValues['shipping_vat_list'] ) > 0 )
        {
            $returnValue = true;
        }

        return $returnValue;
    }
}

?>
