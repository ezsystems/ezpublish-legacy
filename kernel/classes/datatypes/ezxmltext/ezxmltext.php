<?php
//
// Definition of eZXMLText class
//
// Created on: <28-Jan-2003 12:56:49 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZXMLText ezxmltext.php
  \ingroup eZDatatype
  \brief The class eZXMLText handles XML text data type instances

*/

class eZXMLText
{
    function eZXMLText( $xmlData, $contentObjectAttribute )
    {
        $this->XMLData = $xmlData;
        $this->ContentObjectAttribute = $contentObjectAttribute;
        $this->XMLInputHandler = null;
        $this->XMLOutputHandler = null;
    }

    function attributes()
    {
        return array( 'input',
                      'output',
                      'pdf_output',
                      'xml_data',
                      'is_empty' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
    {
        switch ( $name )
        {
            case 'input' :
            {
                if ( $this->XMLInputHandler === null )
                {
                    $this->XMLInputHandler = $this->inputHandler( $this->XMLData, false, true, $this->ContentObjectAttribute );
                }
                return $this->XMLInputHandler;
            }break;

            case 'output' :
            {
                if ( $this->XMLOutputHandler === null )
                {
                    $this->XMLOutputHandler = $this->outputHandler( $this->XMLData, false, true, $this->ContentObjectAttribute );
                }
                return $this->XMLOutputHandler;
            }break;

            case 'pdf_output' :
            {
                if ( $this->XMLOutputHandler === null )
                {
                    $this->XMLOutputHandler = $this->outputHandler( $this->XMLData, 'ezpdf', true, $this->ContentObjectAttribute );
                }
                return $this->XMLOutputHandler;
            }break;

            case 'xml_data' :
            {
                return $this->XMLData;
            }break;

            case 'is_empty' :
            {
                $isEmpty = true;
                $dom = new DOMDocument( '1.0', 'utf-8' );
                if ( !$this->XMLData )
                {
                    return $isEmpty;
                }
                $success = $dom->loadXML( $this->XMLData );
                if ( $success )
                {
                    $sectionNode = $dom->documentElement;

                    if ( $sectionNode->childNodes->length > 0 )
                    {
                        $isEmpty = false;
                    }
                }
                return $isEmpty;
            }break;

            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZXMLText::attribute' );
                $retValue = null;
                return $retValue;
            }break;
        }
    }

    /// \static
    static function inputHandler( &$xmlData, $type = false, $useAlias = true, $contentObjectAttribute = false )
    {
        $optionArray = array( 'iniFile'       => 'ezxml.ini',
                              'iniSection'    => 'InputSettings',
                              'iniVariable'   => 'HandlerClass',
                              'callMethod'    => 'isValid',
                              'handlerParams' => array( $xmlData,
                                                        false,
                                                        $contentObjectAttribute ),
                              'aliasVariable' => ( $useAlias ? 'AliasClasses' : null ) );

        $options = new ezpExtensionOptions( $optionArray );

        $inputHandler = eZExtension::getHandlerClass( $options );

        if ( $inputHandler === null || $inputHandler === false )
        {
            $inputHandler = new eZSimplifiedXMLInput( $xmlData, false, $contentObjectAttribute );
        }
        return $inputHandler;
    }

    /// \static
    static function outputHandler( &$xmlData, $type = false, $useAlias = true, $contentObjectAttribute = false )
    {
        $optionArray = array( 'iniFile'       => 'ezxml.ini',
                              'iniSection'    => 'OutputSettings',
                              'iniVariable'   => 'HandlerClass',
                              'callMethod'    => 'isValid',
                              'handlerParams' => array( $xmlData,
                                                        false,
                                                        $contentObjectAttribute ),
                              'aliasVariable' => ( $useAlias ? 'AliasClasses' : null )  );

        $options = new ezpExtensionOptions( $optionArray );

        $outputHandler = eZExtension::getHandlerClass( $options );

        if ( $outputHandler === null || $outputHandler === false )
        {
            $outputHandler = new eZXHTMLXMLOutput( $xmlData, false, $contentObjectAttribute );
        }
        return $outputHandler;
    }

    /// Contains the XML data
    public $XMLData;

    public $XMLInputHandler;
    public $XMLOutputHandler;
    public $XMLAttributeID;
    public $ContentObjectAttribute;
}

?>
