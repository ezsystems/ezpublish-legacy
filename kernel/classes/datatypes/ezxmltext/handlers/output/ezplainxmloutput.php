<?php
/**
 * File containing the eZPlainXMLOutput class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class eZPlainXMLOutput extends eZXMLOutputHandler
{
    function eZPlainXMLOutput( &$xmlData, $aliasedType )
    {
        $this->eZXMLOutputHandler( $xmlData, $aliasedType );
    }

    function &outputText()
    {
        $retText = "<pre>" . htmlspecialchars( $this->xmlData() ) . "</pre>";
        return $retText;
    }
}

?>
