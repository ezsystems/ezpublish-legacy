<?php
/**
 * File containing the eZDefaultBasketInfoHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
