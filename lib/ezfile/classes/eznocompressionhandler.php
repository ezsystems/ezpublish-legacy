<?php
/**
 * File containing the eZNoCompressionHandler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZNoCompressionHandler eznocompressionhandler.php
  \brief Does no compression at all

*/

class eZNoCompressionHandler extends eZCompressionHandler
{
    /*!
     See eZCompressionHandler::eZCompressionHandler
    */
    function eZNoCompressionHandler()
    {
        $this->eZCompressionHandler( 'No compression', 'no' );
    }
}

?>
