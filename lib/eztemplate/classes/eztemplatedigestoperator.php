<?php
//
// Definition of eZTemplateDigestOperator class
//
// Created on: <18-Jul-2003 13:00:18 bh>
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

/*!
  \class eZTemplateDigestOperator eztemplatedigestoperator.php
  \ingroup eZTemplateOperators
\code

\endcode

*/

class eZTemplateDigestOperator
{
    /*!
     Constructor.
    */
    function eZTemplateDigestOperator( $crc32Name = 'crc32',
                                       $md5Name   = 'md5',
                                       $rot13Name = 'rot13')
    {
        $this->Operators = array( $crc32Name, $md5Name, $rot13Name );
        $this->Crc32Name = $crc32Name;
        $this->Md5Name   = $md5Name;
        $this->Rot13Name = $rot13Name;
    }

    /*!
     Returns the template operators.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }
    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return false;
    }
    /*!
     Display the variable.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            // Calculate and return crc32 polynomial.
            case $this->Crc32Name:
            {
                $operatorValue = crc32( $operatorValue );
            }break;

            // Calculate the MD5 hash.
            case $this->Md5Name:
            {
                $operatorValue = md5( $operatorValue );
            }break;

            // Preform rot13 transform on the string.
            case $this->Rot13Name:
            {
                $operatorValue = str_rot13( $operatorValue );
            }break;

            // Default case: something went wrong - unknown things...
            default:
            {
                $tpl->warning( $operatorName, "Unknown input type '$type'" );
            } break;
        }
    }

    /// The array of operators, used for registering operators
    var $Operators;
}

?>
