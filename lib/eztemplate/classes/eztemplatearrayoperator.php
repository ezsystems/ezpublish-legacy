<?php
//
// Definition of eZTemplateArrayOperator class
//
// Created on: <05-Mar-2002 12:52:10 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
  \class eZTemplateArrayOperator eztemplatearrayoperator.php
  \ingroup eZTemplateOperators
  \brief Dynamic creation of arrays using operator "array"

  Creates an operator which can create arrays dynamically by
  adding all operator parameters as array elements.

\code
// Example template code
{array(1,"test")}
{array(array(1,2),3)}
\endcode

*/

include_once( "lib/eztemplate/classes/eztemplate.php" );

class eZTemplateArrayOperator
{
    /*!
     Initializes the array operator with the operator name $name.
    */
    function eZTemplateArrayOperator( $arrayName = "array", $hashName = 'hash' )
    {
        $this->ArrayName = $arrayName;
        $this->HashName = $hashName;
        $this->Operators = array( $arrayName, $hashName );
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     Creates an array of all it's input parameters and sets $operatorValue.
    */
    function modify( &$element,
                     &$tpl, &$operatorName, /*! Contains all array elements */ &$operatorParameters,
                     &$namespace, &$current_nspace, &$operatorValue, &$named_params )
    {
        if ( $operatorName == $this->HashName )
        {
            $operatorValue = array();
            $hashCount = (int)( count( $operatorParameters ) / 2 );
            for ( $i = 0; $i < $hashCount; ++$i )
            {
                $hashName = $tpl->elementValue( $operatorParameters[$i*2], $namespace );
                if ( is_string( $hashName ) or
                     is_numerical( $hashName ) )
                    $operatorValue[$hashName] =& $tpl->elementValue( $operatorParameters[($i*2)+1], $namespace );
                else
                    $tpl->error( $operatorName,
                                 "Unknown hash key type '" . gettype( $hashName ) . "', skipping" );
            }
        }
        else
        {
            $operatorValue = array();
            for ( $i = 0; $i < count( $operatorParameters ); ++$i )
            {
                $operatorValue[] =& $tpl->elementValue( $operatorParameters[$i], $namespace );
            }
        }
    }

    /// \privatesection
    var $Operators;
    var $ArrayName;
    var $HashName;
}

?>
