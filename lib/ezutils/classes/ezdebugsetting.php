<?php
//
// Definition of eZDebugSetting class
//
// Created on: <16-Jan-2003 16:23:58 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezdebugsetting.php
*/

/*!
  \class eZDebugSetting ezdebugsetting.php
  \brief Conditional debug output

  This works as a wrapper for the eZDebug class by checking some
  conditions defined in site.ini before writing the message.
  The condition must be true for the message to be written.

  It will check the debug.ini file and first see if conditions are
  enabled globally by reading DebugSettings/ConditionDebug.
  If true if will then see if the condition exists in the GeneralCondition
  group, if so it will use it for condition check.
  If it doesn't exists generally it will check it specifically according
  to the message type for instance ErrorCondition, DebugCondition etc.

Example of debug.ini:
\code
[DebugSettings]
ConditionDebug=enabled

[GeneralCondition]
my-flag=enabled
other-flag=disabled

[ErrorCondition]
bad-name-flag=disabled

\endcode

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/ezutils/classes/ezini.php' );

class eZDebugSetting
{
    /*!
     Constructor
    */
    function eZDebugSetting()
    {
    }

    /*!
      \static
      \return true if the condition \a $conditionName is considered enabled.
    */
    function isConditionTrue( $conditionName, $messageType )
    {
        global $eZDebugSettingINIObject;

        $ini =& $eZDebugSettingINIObject;

        if ( isset( $eZDebugSettingINIObject ) and  get_class( $ini ) == 'ezini' )
        {
            if ( $ini->variable( 'DebugSettings', 'ConditionDebug' ) != 'enabled' )
                return false;
            $generalSetting = 'GeneralCondition';
            $debugName = eZDebug::messageName( $messageType );
            $specificSetting = $debugName . 'Condition';
            if ( $ini->hasVariable( $generalSetting, $conditionName ) )
                return $ini->variable( $generalSetting, $conditionName ) == 'enabled';
            if ( $ini->hasVariable( $specificSetting, $conditionName ) )
                return $ini->variable( $specificSetting, $conditionName ) == 'enabled';
        }
        return false;
    }

    /*!
     \static
     Creates a new debug label from the original and the condition and returns it.
    */
    function changeLabel( $conditionName, $label )
    {
        if ( $label == "" )
            return '<' . $conditionName . '>';
        else
            return $label . ' <' . $conditionName . '>';
    }

    /*!
      \static
      Writes a debug notice if the condition \a $conditionName is enabled.
    */
    function writeNotice( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, EZ_LEVEL_NOTICE ) )
            return false;
        eZDebug::writeNotice( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
      \static
      Writes a debug warning if the condition \a $conditionName is enabled.
    */
    function writeWarning( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, EZ_LEVEL_WARNING ) )
            return false;
        eZDebug::writeWarning( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
      \static
      Writes a debug error if the condition \a $conditionName is enabled.
    */
    function writeError( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, EZ_LEVEL_ERROR ) )
            return false;
        eZDebug::writeError( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
      \static
      Writes a debug message if the condition \a $conditionName is enabled.
    */
    function writeDebug( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, EZ_LEVEL_DEBUG ) )
            return false;
        eZDebug::writeDebug( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
      \static
      Adds the timing point if the condition \a $conditionName is enabled.
    */
    function addTimingPoint( $conditionName, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, EZ_LEVEL_TIMING_POINT ) )
            return false;
        eZDebug::addTimingPoint( eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
     \static
     Sets the INI object
    */
    function setDebugINI( $ini )
    {
        global $eZDebugSettingINIObject;
        $eZDebugSettingINIObject = $ini;
    }

}

?>
