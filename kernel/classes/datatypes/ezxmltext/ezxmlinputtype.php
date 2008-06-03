<?php
//
// Definition of eZXMLInputType class
//
// Created on: <06-Nov-2002 14:23:08 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezxmlinputtype.php
*/

/*!
  \class eZXMLInputType ezxmlinputtype.php
  \ingroup eZDatatype
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

    static function instance()
    {
        $ini = eZINI::instance();
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
        $http = eZHTTPTool::instance();
        if ( $http->hasSessionVariable( 'DisableEditorExtension' ) )
        {
            $disableExtension = true;
        }

        $impl = null;
        if ( $inputHandler == "standard" )
        {
            if ( file_exists( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" ) )
            {
                //include_once( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" );
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
                    //include_once( "kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php" );
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
        $ini = eZINI::instance();
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

        $http = eZHTTPTool::instance();
        if ( $http->hasSessionVariable( 'DisableEditorExtension' ) )
        {
            $disableExtension = true;
        }

        $editorName = null;
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
