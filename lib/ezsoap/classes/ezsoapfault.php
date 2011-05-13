<?php
/**
 * File containing the eZSOAPFault class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZSOAPFault ezsoapfault.php
  \ingroup eZSOAP
  \brief eZSOAPFault handles SOAP fault messages

*/

class eZSOAPFault
{
    /*!
     Constructs a new eZSOAPFault object
    */
    function eZSOAPFault( $faultCode = "", $faultString = "" )
    {
        $this->FaultCode = $faultCode;
        $this->FaultString = $faultString;
    }

    /*!
     Returns the fauls code.
    */
    function faultCode()
    {
        return $this->FaultCode;
    }

    /*!
     Returns the fauls string.
    */
    function faultString()
    {
        return $this->FaultString;
    }

    /// Contains the fault code
    public $FaultCode;

    /// Contains the fault string
    public $FaultString;
}

?>
