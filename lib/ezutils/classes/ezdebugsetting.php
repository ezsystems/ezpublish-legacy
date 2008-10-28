<?php
//
// Definition of eZDebugSetting class
//
// Created on: <16-Jan-2003 16:23:58 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

require_once( 'lib/ezutils/classes/ezdebug.php' );
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
    static function isConditionTrue( $conditionName, $messageType )
    {
        global $eZDebugSettingINIObject;

        $ini = $eZDebugSettingINIObject;

        if ( isset( $eZDebugSettingINIObject ) and $ini instanceof eZINI )
        {
            if ( $ini->variable( 'DebugSettings', 'ConditionDebug' ) != 'enabled' )
                return false;
            $generalSetting = 'GeneralCondition';
            $debug = eZDebug::instance();
            $debugName = $debug->messageName( $messageType );
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
    static function changeLabel( $conditionName, $label )
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
    static function writeNotice( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_NOTICE ) )
            return false;
        eZDebug::writeNotice( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
      \static
      Writes a debug warning if the condition \a $conditionName is enabled.
    */
    static function writeWarning( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_WARNING ) )
            return false;
        eZDebug::writeWarning( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
      \static
      Writes a debug error if the condition \a $conditionName is enabled.
    */
    static function writeError( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_ERROR ) )
            return false;
        eZDebug::writeError( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
      \static
      Writes a debug message if the condition \a $conditionName is enabled.
    */
    static function writeDebug( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_DEBUG ) )
            return false;
        eZDebug::writeDebug( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
      \static
      Adds the timing point if the condition \a $conditionName is enabled.
    */
    static function addTimingPoint( $conditionName, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_TIMING_POINT ) )
            return false;
        eZDebug::addTimingPoint( eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /*!
     \static
     Sets the INI object
    */
    static function setDebugINI( $ini )
    {
        global $eZDebugSettingINIObject;
        $eZDebugSettingINIObject = $ini;
    }

}

?>
