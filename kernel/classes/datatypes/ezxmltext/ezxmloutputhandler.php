<?php
//
// Definition of eZXMLOutputHandler class
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

/*! \file ezxmloutputhandler.php
*/

/*!
  \class eZXMLOutputHandler
  \brief The class eZXMLOutputHandler does

*/

include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "lib/ezxml/classes/ezdomnode.php" );
include_once( "lib/ezxml/classes/ezdomdocument.php" );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );

class eZXMLOutputHandler
{
    /*!
     Constructor
    */
    function eZXMLOutputHandler( &$xmlData, $aliasedType )
    {
        $this->XMLData =& $xmlData;
        $this->AliasedType = $aliasedType;
        $this->AliasedHandler = null;
    }

    /*!
     \return an array with attribute names.
    */
    function attributes()
    {
        return array( 'output_text',
                      'aliased_type',
                      'aliased_handler',
                      'view_template_name' );
    }

    /*!
     \return true if the attribute \a $name exists.
    */
    function hasAttribute( $name )
    {
        if ( $name == 'output_text' or
             $name == 'aliased_type' or
             $name == 'aliased_handler' or
             $name == 'view_template_name' )
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
            case 'output_text':
            {
                return $this->outputText();
            } break;
            case 'aliased_type':
            {
                return $this->AliasedType;
            }
            case 'view_template_name':
            {
                return $this->viewTemplateName();
            }
            case 'aliased_handler':
            {
                if ( $this->AliasedType !== false and
                     $this->AliasHandler === null )
                {
                    $this->AliasedHandler =& eZXMLText::inputHandler( $this->XMLData,
                                                                      $this->AliasedType,
                                                                      false );
                }
                return $this->AliasedHandler;
            }
        }
        eZDebug::writeError( "Attribute '$name' does not exist", 'eZXMLOutputHandler::attribute' );
        return null;
    }

    /*!
     \return the template name for this input handler, includes the edit suffix if any.
    */
    function &viewTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->viewTemplateSuffix();
        if ( $suffix !== false )
            $name .= '_' . $suffix;
        return $name;
    }

    /*!
     \virtual
     \return true if the output handler is considered valid, if not the handler will not be used.
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
    function &viewTemplateSuffix( &$contentobjectAttribute )
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
     Returns the output text representation of the XML structure, implement this to turn
     XML into an output format.
    */
    function &outputText()
    {
        return null;
    }

    /// \privatesection
    /// Contains the XML data as text
    var $XMLData;
    var $AliasedType;
    var $AliasedHandler;
}

?>
