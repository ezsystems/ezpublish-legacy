<?php
//
// Definition of eZRangeOption class
//
// Created on: <17-æÅ×-2003 16:17:18 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezrangeoption.php
*/

/*!
  \class eZRangeOption ezrangeoption.php
  \brief The class eZRangeOption does

*/

class eZRangeOption
{
    /*!
     Constructor
    */
    function eZRangeOption( $name )
    {
        $this->Name = $name;
        $Options = array();
        $this->OptionCount = 0;
    }

    /*!
     Sets the name of the option
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     Returns the name of the option set.
    */
    function &name()
    {
        return $this->Name;
    }

    function hasAttribute( $name )
    {
        if ( $name == "name" || $name == "start_value" || $name == "stop_value" || $name == "step_value" )
            return true;
        else
            if ( $name == "option_list" )
                return true;
            else
                return false;
    }

    function &attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;
            case "start_value" :
            {
                return $this->StartValue;
            }break;
            case "stop_value" :
            {
                return $this->StopValue;
            }break;
            case "step_value" :
            {
                return $this->StepValue;
            }break;
            case "option_list" :
            {
                return $this->Options;
            }break;
        }
    }

    function addOption( $valueArray )
    {
        $this->Options[] = array( "id" => $this->OptionCount,
                                  "value" => $valueArray['value'],
                                  'additional_price' => 0,
                                  "is_default" => false );

        $this->OptionCount += 1;
    }

    function decodeXML( $xmlString )
    {
        $xml = new eZXML();


        $dom =& $xml->domTree( $xmlString );

        if ( $xmlString != "" )
        {
            // set the name of the node
            $rangeOptionElement =& $dom->root( );
//            $rangeOptionElement =& $rangeoptionElements[0];
            $startValue = $rangeOptionElement->attributeValue( 'start_value' );
            $stopValue = $rangeOptionElement->attributeValue( 'stop_value' );
            $stepValue = $rangeOptionElement->attributeValue( 'step_value' );
            if ( $stepValue == 0 )
                $stepValue = 1;
            $this->StartValue = $startValue;
            $this->StopValue = $stopValue;
            $this->StepValue = $stepValue;


            $nameArray =& $dom->elementsByName( "name" );
            $this->setName( $nameArray[0]->textContent() );

            for ( $i=$startValue;$i<=$stopValue;$i+=$stepValue )
            {
                $this->addOption( array( 'value' => $i,
                                         'additional_price' => 0 ) );
            }
        }
        else
        {
            $this->StartValue = 0;
            $this->StopValue = 0;
            $this->StepValue = 0;
        }
    }

    /*!
     Will return the XML string for this option set.
    */
    function &xmlString( )
    {
        $doc = new eZDOMDocument( "Option" );

        $root =& $doc->createElementNode( "ezrangeoption" );
        $root->appendAttribute( $doc->createAttributeNode( "start_value", $this->StartValue ) );
        $root->appendAttribute( $doc->createAttributeNode( "stop_value", $this->StopValue ) );
        $root->appendAttribute( $doc->createAttributeNode( "step_value", $this->StepValue ) );
        $doc->setRoot( $root );

        $name =& $doc->createElementNode( "name" );
        $nameValue =& $doc->createTextNode( $this->Name );
        $name->appendChild( $nameValue );

        $name->setContent( $this->Name() );

        $root->appendChild( $name );

        $xml =& $doc->toString();

        return $xml;
    }

    function setStartValue( $value )
    {
        $this->StartValue = $value;
    }

    function setStopValue( $value )
    {
        $this->StopValue = $value;
    }

    function setStepValue( $value )
    {
        $this->StepValue = $value;
    }


        /// Contains the Option name
    var $Name;

    /// Contains the Options
    var $Options;

    /// Contains the option counter value
    var $OptionCount;
    var $StartValue;
    var $StopValue;
    var $StepValue;
}

?>
