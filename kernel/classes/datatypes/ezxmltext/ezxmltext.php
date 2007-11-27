<?php
//
// Definition of eZXMLText class
//
// Created on: <28-Jan-2003 12:56:49 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezxmltext.php
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
        $inputDefinition = array( 'ini-name' => 'ezxml.ini',
                                  'repository-group' => 'HandlerSettings',
                                  'repository-variable' => 'Repositories',
                                  'extension-group' => 'HandlerSettings',
                                  'extension-variable' => 'ExtensionRepositories',
                                  'type-group' => 'InputSettings',
                                  'type-variable' => 'Handler',
                                  'subdir' => 'input',
                                  'type-directory' => false,
                                  'extension-subdir' => 'ezxmltext/handlers/input',
                                  'suffix-name' => 'xmlinput.php' );
        if ( $type !== false )
            $inputDefinition['type'] = $type;
        if ( $useAlias )
        {
            $inputDefinition['alias-group'] = 'InputSettings';
            $inputDefinition['alias-variable'] = 'Alias';
        }
        $inputHandler = eZXMLText::fetchHandler( $inputDefinition,
                                                  'XMLInput',
                                                  $xmlData,
                                                  $contentObjectAttribute );
        if ( $inputHandler === null )
        {
            //include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmlinput.php' );
            $inputHandler = new eZSimplifiedXMLInput( $xmlData, false, $contentObjectAttribute );
        }
        return $inputHandler;
    }

    /// \static
    static function outputHandler( &$xmlData, $type = false, $useAlias = true, $contentObjectAttribute = false )
    {
        $outputDefinition = array( 'ini-name' => 'ezxml.ini',
                                   'repository-group' => 'HandlerSettings',
                                   'repository-variable' => 'Repositories',
                                   'extension-group' => 'HandlerSettings',
                                   'extension-variable' => 'ExtensionRepositories',
                                   'type-group' => 'OutputSettings',
                                   'type-variable' => 'Handler',
                                   'subdir' => 'output',
                                   'type-directory' => false,
                                   'extension-subdir' => 'ezxmltext/handlers/output',
                                   'suffix-name' => 'xmloutput.php' );
        if ( $type !== false )
            $outputDefinition['type'] = $type;
        if ( $useAlias )
        {
            $outputDefinition['alias-group'] = 'OutputSettings';
            $outputDefinition['alias-variable'] = 'Alias';
        }
        $outputHandler = eZXMLText::fetchHandler( $outputDefinition,
                                                  'XMLOutput',
                                                  $xmlData,
                                                  $contentObjectAttribute );
        if ( $outputHandler === null )
        {
            //include_once( 'kernel/classes/datatypes/ezxmltext/handlers/output/ezxhtmlxmloutput.php' );
            $outputHandler = new eZXHTMLXMLOutput( $xmlData, false, $contentObjectAttribute );
        }
        return $outputHandler;
    }

    /// \static
    static function fetchHandler( $definition, $classSuffix, &$xmlData, $contentObjectAttribute )
    {
        $handler = null;
        if ( eZExtension::findExtensionType( $definition,
                                             $out ) )
        {
            $filePath = $out['found-file-path'];
            include_once( $filePath );
            $class = $out['type'] . $classSuffix;
            $handlerValid = false;
            $aliasedType = false;
            if ( $out['original-type'] != $out['type'] )
                $aliasedType = $out['original-type'];
            if( class_exists( $class ) )
            {
                $handler = new $class( $xmlData, $aliasedType, $contentObjectAttribute );
                if ( $handler->isValid() )
                    $handlerValid = true;
            }
            else
            {
                eZDebug::writeError( "Could not instantiate class '$class', it is not defined",
                                     'eZXMLText::fetchHandler' );
            }

            if ( !$handlerValid and
                 $out['type'] != $out['original-type'] and
                 isset( $definition['alias-group'] ) and
                 isset( $definition['alias-variable'] ) )
            {
                unset( $definition['alias-group'] );
                unset( $definition['alias-variable'] );
                if ( eZExtension::findExtensionType( $definition,
                                                     $out ) )
                {
                    $filePath = $out['found-file-path'];
                    include_once( $filePath );
                    $class = $out['type'] . $classSuffix;
                    $handlerValid = false;
                    if( class_exists( $class ) )
                    {
                        $handler = new $class( $xmlData, false, $contentObjectAttribute );
                        if ( $handler->isValid() )
                            $handlerValid = true;
                    }
                    else
                    {
                        eZDebug::writeError( "Could not instantiate class '$class', it is not defined",
                                             'eZXMLText::fetchHandler' );
                    }

                    if ( !$handlerValid )
                    {
                        $handler = null;
                    }
                }
            }
        }
        return $handler;
    }

    /// Contains the XML data
    public $XMLData;

    public $XMLInputHandler;
    public $XMLOutputHandler;
    public $XMLAttributeID;
    public $ContentObjectAttribute;
}

?>
