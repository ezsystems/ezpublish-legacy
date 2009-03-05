<?php
//
// Definition of eZXMLInputHandler class
//
// Created on: <06-Nov-2002 15:10:02 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZXMLInputHandler ezxmlinputhandler.php
  \ingroup eZDatatype
  \brief The class eZXMLInputHandler does

*/

class eZXMLInputHandler
{
    /*!
     Constructor
    */
    function eZXMLInputHandler( $xmlData, $aliasedType, $contentObjectAttribute )
    {
        $this->XMLData = $xmlData;
        $this->ContentObjectAttribute = $contentObjectAttribute;
        $this->AliasedType = $aliasedType;
        $this->AliasedHandler = null;
    }

    /*!
     \return an array with attribute names.
    */
    function attributes()
    {
        return array( 'input_xml',
                      'aliased_type',
                      'aliased_handler',
                      'edit_template_name',
                      'information_template_name' );
    }

    /*!
     \return true if the attribute \a $name exists.
    */
    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    /*!
     \return the value of the attribute \a $name if it exists, if not returns \c null.
    */
    function attribute( $name )
    {
        switch ( $name )
        {
            case 'input_xml':
            {
                return $this->inputXML();
            } break;
            case 'edit_template_name':
            {
                return $this->editTemplateName();
            }break;
            case 'information_template_name':
            {
                return $this->informationTemplateName();
            }break;
            case 'aliased_type':
            {
                return $this->AliasedType;
            }break;
            case 'aliased_handler':
            {
                if ( $this->AliasedType !== false and
                     $this->AliasedHandler === null )
                {
                    $this->AliasedHandler = eZXMLText::inputHandler( $this->XMLData,
                                                                     $this->AliasedType,
                                                                     false,
                                                                     $this->ContentObjectAttribute );
                }
                return $this->AliasedHandler;
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZXMLInputHandler::attribute' );
                return null;
            }break;
        }
    }

    /*!
     \return the template name for this input handler, includes the edit suffix if any.
    */
    function editTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->editTemplateSuffix( $this->ContentObjectAttribute );
        if ( $suffix !== false )
        {
            $name .= '_' . $suffix;
        }
        return $name;
    }

    /*!
     \return the template name for this input handler, includes the information suffix if any.
    */
    function informationTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->informationTemplateSuffix( $this->ContentObjectAttribute );
        if ( $suffix !== false )
        {
            $name .= '_' . $suffix;
        }
        return $name;
    }

    /*!
     \pure
     Handles custom actions for input handler.
     \note Default does nothing, reimplement to check actions.
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute )
    {
    }

    /*!
     \virtual
     \return true if the input handler is considered valid, if not the handler will not be used.
     \note Default returns true
    */
    function isValid()
    {
        return true;
    }

    /*!
     \pure
     \return the suffix for the attribute template, if false it is ignored.
    */
    function editTemplateSuffix( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \pure
     \return the suffix for the attribute template, if false it is ignored.
    */
    function informationTemplateSuffix( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \return the xml data as text.
    */
    function xmlData()
    {
        return $this->XMLData;
    }

    /*!
     \pure
     Validates user input and returns whether it can be used or not.
    */
    function validateInput( $http, $base, $contentObjectAttribute )
    {
        return eZInputValidator::STATE_INVALID;
    }

    /*!
     \pure
     Converts text input \a $text into an XML structure and returns it.
     \return an array where index 0 is the xml structure and index 1 is a message.
    */
    function convertInput( $text )
    {
        return null;
    }

    /*!
     \pure
     Returns the text representation of the XML structure, implement this to turn
     XML back into user input.
    */
    function inputXML()
    {
        return null;
    }

    /// \privatesection
    /// Contains the XML data as text
    public $XMLData;
    public $AliasedType;
    public $AliasedHandler;
    public $ContentObjectAttribute;
}

?>
