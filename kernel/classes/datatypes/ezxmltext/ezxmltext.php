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
                    include_once( 'kernel/classes/datatypes/ezxmltext/ezsimpifiedxmlinput.php' );
                    $this->XMLInputHandler = new eZSimplifiedXMLInput( $this->XMLData );
//                    include_once( 'extension/xmleditor/dhtml/ezdhtmlinput.php' );
//                    $this->XMLInputHandler = new eZDHTMLInput( $this->XMLData );
                }
                return $this->XMLInputHandler;
            }break;

            case 'output' :
            {
                if ( $this->XMLOutputHandler === null )
                {
                    include_once( 'kernel/classes/datatypes/ezxmltext/ezxhtmloutput.php' );
                    $this->XMLOutputHandler = new eZXHTMLOutput( $this->XMLData );
                }
                return $this->XMLOutputHandler;
            }break;

            case 'xml_data' :
            {
                return $this->XMLData;
            }break;
        }
    }

    /// Contains the XML data
    var $XMLData;
    var $XMLInputHandler;
    var $XMLOutputHandler;
}

?>
