<?php
//
// Definition of eZXMLInputHandler class
//
// Created on: <06-Nov-2002 15:10:02 wy>
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

/*! \file ezxmlinputhandler.php
*/

/*!
  \class eZXMLInputHandler
  \brief The class eZXMLInputHandler does

*/

include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "lib/ezxml/classes/ezdomnode.php" );
include_once( "lib/ezxml/classes/ezdomdocument.php" );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );

class eZXMLInputHandler
{
    /*!
     Constructor
    */
    function eZXMLInputHandler(  &$xmlData, $aliasedType, $contentObjectAttribute )
    {
        $this->XMLData =& $xmlData;
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
        if ( $name == 'input_xml' or
             $name == 'aliased_type' or
             $name == 'aliased_handler' or
             $name == 'edit_template_name' or
             $name == 'information_template_name' )
            return true;
        return false;
    }

    /*!
     \return the value of the attribute \a $name if it exists, if not returns \c null.
    */
    function &attribute( $name )
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
            }
            case 'information_template_name':
            {
                return $this->informationTemplateName();
            }
            case 'aliased_type':
            {
                return $this->AliasedType;
            }
            case 'aliased_handler':
            {
                if ( $this->AliasedType !== false and
                     $this->AliasedHandler === null )
                {
                    $this->AliasedHandler =& eZXMLText::inputHandler( $this->XMLData,
                                                                      $this->AliasedType,
                                                                      false );
                }
                return $this->AliasedHandler;
            }
        }
        eZDebug::writeError( "Attribute '$name' does not exist", 'eZXMLInputHandler::attribute' );
        return null;
    }

    /*!
     \return the template name for this input handler, includes the edit suffix if any.
    */
    function editTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->editTemplateSuffix();
        if ( $suffix !== false )
            $name .= '_' . $suffix;
        return $name;
    }

    /*!
     \return the template name for this input handler, includes the information suffix if any.
    */
    function informationTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->informationTemplateSuffix();
        if ( $suffix !== false )
            $name .= '_' . $suffix;
        return $name;
    }

    /*!
     \pure
     Handles custom actions for input handler.
     \note Default does nothing, reimplement to check actions.
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
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
    function &editTemplateSuffix( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \pure
     \return the suffix for the attribute template, if false it is ignored.
    */
    function &informationTemplateSuffix( &$contentobjectAttribute )
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
    function &validateInput( &$http, $base, &$contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     \pure
     Converts text input \a $text into an XML structure and returns it.
     \return an array where index 0 is the xml structure and index 1 is a message.
    */
    function &convertInput( &$text )
    {
        return null;
    }

    /*!
     \pure
     Returns the text representation of the XML structure, implement this to turn
     XML back into user input.
    */
    function &inputXML()
    {
        return null;
    }

    /// \privatesection
    /// Contains the XML data as text
    var $XMLData;
    var $AliasedType;
    var $AliasedHandler;
    var $ContentObjectAttribute;
}

?>
