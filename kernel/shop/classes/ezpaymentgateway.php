<?php
/**
 * File containing the eZPaymentGateway class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZPaymentGateway ezpaymentgateway.php
  \brief Abstract class for all payment gateways.
*/

class eZPaymentGateway
{
    /*!
    Constructor.
    */
    function eZPaymentGateway()
    {
        $this->logger = eZPaymentLogger::CreateForAdd( "var/log/eZPaymentGateway.log" );
    }

    function execute( $process, $event )
    {
        $this->logger->writeTimedString( 'You must override this function.', 'execute' );
        return eZWorkflowType::STATUS_REJECTED;
    }

    function needCleanup()
    {
        return false;
    }

    function cleanup( $process, $event )
    {
    }

    /*!
    Creates short description of order. Usually this string is
    passed to payment site as describtion of payment.
    */
    function createShortDescription( $order, $maxDescLen )
    {
        //__DEBUG__
        $this->logger->writeTimedString("createShortDescription");
        //___end____

        $descText       = '';
        $productItems   = $order->productItems();

        foreach( $productItems as $item )
        {
            $descText .= $item['object_name'] . ',';
        }
        $descText   = rtrim( $descText, "," );

        $descLen    = strlen( $descText );
        if( ($maxDescLen > 0) && ($descLen > $maxDescLen) )
        {
            $descText = substr($descText, 0, $maxDescLen - 3) ;
            $descText .= '...';
        }

        //__DEBUG__
        $this->logger->writeTimedString("descText=$descText");
        //___end____

        return $descText;
    }

    public $logger;
}
?>
