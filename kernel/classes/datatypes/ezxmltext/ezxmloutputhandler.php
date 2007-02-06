<?php
//
// Definition of eZXMLOutputHandler class
//
// Created on: <06-Nov-2002 15:10:02 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezxmloutputhandler.php
*/

/*!
  \class eZXMLOutputHandler ezxmloutputhandler
  \ingroup eZDatatype
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
        return in_array( $name, $this->attributes() );
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
                $retValue =& $this->outputText();
            } break;
            case 'aliased_type':
            {
                return $this->AliasedType;
            } break;
            case 'view_template_name':
            {
                $retValue =& $this->viewTemplateName();
            } break;
            case 'aliased_handler':
            {
                if ( $this->AliasedType !== false and
                     $this->AliasHandler === null )
                {
                    $this->AliasedHandler =& eZXMLText::inputHandler( $this->XMLData,
                                                                      $this->AliasedType,
                                                                      false,
                                                                      $this->ContentObjectAttribute );
                }
                return $this->AliasedHandler;
            } break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZXMLOutputHandler::attribute' );
                $retValue = null;
            } break;
        }
        return $retValue;
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
        $suffix = false;
        return $suffix;
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
        $retVal = null;
        return $retVal;
    }

    /// \privatesection
    /// Contains the XML data as text
    var $XMLData;
    var $AliasedType;
    var $AliasedHandler;
}

?>
