<?php
//
// Definition of eZXMLText class
//
// Created on: <28-Jan-2003 12:56:49 bf>
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

/*! \file ezxmltext.php
*/

/*!
  \class eZXMLText
  \brief The class eZXMLText handles XML text data type instances

*/

class eZXMLText
{
    function eZXMLText( &$xmlData )
    {
        $this->XMLData =& $xmlData;
        $this->XMLInputHandler = null;
        $this->XMLOutputHandler = null;
    }

    function hasAttribute( $name )
    {
        if ( $name == 'input' or
             $name == 'output' or
             $name == 'xml_data' )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function &attribute( $name )
    {
        switch ( $name )
        {
            case 'input' :
            {
                if ( $this->XMLInputHandler === null )
                {
                    $this->XMLInputHandler =& $this->inputHandler();
                }
                return $this->XMLInputHandler;
            }break;

            case 'output' :
            {
                if ( $this->XMLOutputHandler === null )
                {
                    $this->XMLOutputHandler =& $this->outputHandler();
                }
                return $this->XMLOutputHandler;
            }break;

            case 'xml_data' :
            {
                return $this->XMLData;
            }break;
        }
    }

    function &inputHandler()
    {
        $inputHandler = null;
        if ( eZExtension::findExtensionType( array( 'ini-name' => 'ezxml.ini',
                                                    'repository-group' => 'HandlerSettings',
                                                    'repository-variable' => 'Repositories',
                                                    'extension-group' => 'HandlerSettings',
                                                    'extension-variable' => 'ExtensionRepositories',
                                                    'type-group' => 'InputSettings',
                                                    'type-variable' => 'Handler',
                                                    'alias-group' => 'InputSettings',
                                                    'alias-variable' => 'Alias',
                                                    'subdir' => 'input',
                                                    'type-directory' => false,
                                                    'extension-subdir' => 'ezxmltext/handlers/input',
                                                    'suffix-name' => 'xmlinput.php' ),
                                             $out ) )
        {
            $filePath = $out['found-file-path'];
            include_once( $filePath );
            $class = $out['type'] . 'XMLInput';
            $inputHandler = new $class( $this->XMLData );
        }
        else
        {
            include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimpifiedxmlinput.php' );
            $inputHandler = new eZSimplifiedXMLInput( $this->XMLData );
        }
        return $inputHandler;
    }

    function &outputHandler()
    {
        $outputHandler = null;
        if ( eZExtension::findExtensionType( array( 'ini-name' => 'ezxml.ini',
                                                    'repository-group' => 'HandlerSettings',
                                                    'repository-variable' => 'Repositories',
                                                    'extension-group' => 'HandlerSettings',
                                                    'extension-variable' => 'ExtensionRepositories',
                                                    'type-group' => 'OutputSettings',
                                                    'type-variable' => 'Handler',
                                                    'alias-group' => 'OutputSettings',
                                                    'alias-variable' => 'Alias',
                                                    'subdir' => 'output',
                                                    'type-directory' => false,
                                                    'extension-subdir' => 'ezxmltext/handlers/output',
                                                    'suffix-name' => 'xmloutput.php' ),
                                             $out ) )
        {
            $filePath = $out['found-file-path'];
            include_once( $filePath );
            $class = $out['type'] . 'XMLOutput';
            $outputHandler = new $class( $this->XMLData );
        }
        else
        {
            include_once( 'kernel/classes/datatypes/ezxmltext/handlers/output/ezxhtmlxmloutput.php' );
            $outputHandler = new eZXHTMLXMLOutput( $this->XMLData );
        }
        return $outputHandler;
    }

    /// Contains the XML data
    var $XMLData;
    var $XMLInputHandler;
    var $XMLOutputHandler;
}

?>
