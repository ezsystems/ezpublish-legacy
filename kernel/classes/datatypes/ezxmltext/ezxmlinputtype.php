<?php
//
// Definition of eZXMLInputType class
//
// Created on: <06-Nov-2002 14:23:08 wy>
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

/*! \file ezxmlinputtype.php
*/

/*!
  \class eZXMLInputType
  \brief The class eZXMLInputType does

*/

class eZXMLInputType
{
    /*!
     Constructor
    */
    function eZXMLInputType()
    {
    }

    function &instance()
    {
        $ini =& eZINI::instance();
        $inputHandler = $ini->variable( "ExtensionSettings", "XMLEditor" );

        $isMSIE = false;
        $userAgent = eZSys::serverVariable( 'HTTP_USER_AGENT' );
        if ( eregi('MSIE[ \/]([0-9\.]+)', $userAgent, $browserInfo ) )
        {
            $version = $browserInfo[1];
            if ( $version >= 5.5 )
            {
                $isMSIE = true;
            }
        }

        $disableExtension = false;
        $http =& eZHTTPTool::instance();
        if ( $http->hasSessionVariable( 'DisableEditorExtension' ) )
        {
            $disableExtension = true;
        }

        if ( $inputHandler == "standard" )
        {
            if ( file_exists( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" ) )
            {
                include_once( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" );
                $impl = new eZXMLInputHandler();
            }
        }
        elseif ( $inputHandler == "dhtml" )
        {
            if ( $isMSIE
                 and ( file_exists( "extension/xmleditor/" . $inputHandler . "/ezdhtmlinputhandler.php" ) )
                 and ( $disableExtension == false ) )
            {
                include_once( "extension/xmleditor/" . $inputHandler . "/ezdhtmlinputhandler.php" );
                $impl = new eZDHTMLInputHandler();
            }
            else
            {
                if ( file_exists( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" ) )
                {
                    include_once( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" );
                    $impl = new eZXMLInputHandler();
                }
            }
        }
        else
        {
            eZDebug::writeError( "No XML editor available." );
        }
        return $impl;
    }

    function &editorName()
    {
        $ini =& eZINI::instance();
        $inputHandler = $ini->variable( "ExtensionSettings", "XMLEditor" );

        $disableExtension = false;

        $isMSIE = false;
        $userAgent = eZSys::serverVariable( 'HTTP_USER_AGENT' );
        if ( eregi('MSIE[ \/]([0-9\.]+)', $userAgent, $browserInfo ) )
        {
            $version = $browserInfo[1];
            if ( $version >= 5.5 )
            {
                $isMSIE = true;
            }
        }

        $http =& eZHTTPTool::instance();
        if ( $http->hasSessionVariable( 'DisableEditorExtension' ) )
        {
            $disableExtension = true;
        }

        if ( $inputHandler == "standard" )
        {
            if ( file_exists( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" ) )
            {
                $editorName = "standard";
            }
        }
        elseif ( $inputHandler == "dhtml" )
        {
            if ( $isMSIE
                 and ( file_exists( "extension/xmleditor/" . $inputHandler . "/ezdhtmlinputhandler.php" ) )
                 and ( $disableExtension == false ) )
            {
                $editorName = "dhtml";
            }
            else
            {
                if ( file_exists( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" ) )
                {
                    $editorName = "standard";
                }
            }
        }
        else
        {
            eZDebug::writeError( "No XML editor available." );
        }
        return $editorName;
    }

}

?>
