<?php
/**
 * File containing the eZDebugSetting class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package lib
 */

/**
 * Provides conditional debug output
 *
 * This works as a wrapper for the eZDebug class by checking some
 * conditions defined in site.ini before writing the message.
 * The condition must be true for the message to be written.
 *
 * It will check the debug.ini file and first see if conditions are
 * enabled globally by reading DebugSettings/ConditionDebug.
 * If true if will then see if the condition exists in the GeneralCondition
 * group, if so it will use it for condition check.
 * If it doesn't exists generally it will check it specifically according
 * to the message type for instance ErrorCondition, DebugCondition etc.
 *
 * Example of debug.ini:
 * <code>
 * [DebugSettings]
 * ConditionDebug=enabled
 *
 * [GeneralCondition]
 * my-flag=enabled
 * other-flag=disabled
 *
 * [ErrorCondition]
 * bad-name-flag=disabled
 * </code>
 */
class eZDebugSetting
{
    /**
     * Returns true if the condition $conditionName is considered enabled.
     *
     * @param string $conditionName Name of the condition
     *
     * @return bool
     */
    static function isConditionTrue( $conditionName, $messageType )
    {
        $ini = eZINI::instance( 'debug.ini' );

        if ( $ini->variable( 'DebugSettings', 'ConditionDebug' ) != 'enabled' )
            return false;

        $generalSetting = 'GeneralCondition';
        if ( $ini->hasVariable( $generalSetting, $conditionName ) )
            return $ini->variable( $generalSetting, $conditionName ) == 'enabled';

        $specificSetting = eZDebug::instance()->messageName( $messageType ) . 'Condition';
        if ( $ini->hasVariable( $specificSetting, $conditionName ) )
            return $ini->variable( $specificSetting, $conditionName ) == 'enabled';
    }

    /**
     * Creates a new debug label from the original and the condition and returns it.
     *
     * @param string $conditionName Name of the condition
     * @param string $label Optional label
     * @return string $label . '<' . $conditionName . '>'
     */
    static function changeLabel( $conditionName, $label = '' )
    {
        if ( $label == '' )
            return '<' . $conditionName . '>';
        else
            return $label . ' <' . $conditionName . '>';
    }

    /**
     * Writes a debug notice if the condition $conditionName is enabled.
     *
     * @param string $conditionName Name of the condition
     * @param string $string Text to write
     * @param string $label Optional label
     */
    static function writeNotice( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_NOTICE ) )
            return false;
        eZDebug::writeNotice( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /**
     * Writes a debug warning if the condition $conditionName is enabled.
     *
     * @param string $conditionName Name of the condition
     * @param string $string Text to write
     * @param string $label Optional label
     */
    static function writeWarning( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_WARNING ) )
            return false;
        eZDebug::writeWarning( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /**
     * Writes a debug error if the condition $conditionName is enabled.
     *
     * @param string $conditionName Name of the condition
     * @param string $string Text to write
     * @param string $label Optional label
     */
    static function writeError( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_ERROR ) )
            return false;
        eZDebug::writeError( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /**
     * Writes a debug message if the condition $conditionName is enabled.
     *
     * @param string $conditionName Name of the condition
     * @param string $string Text to write
     * @param string $label Optional label
     */
    static function writeDebug( $conditionName, $string, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_DEBUG ) )
            return false;
        eZDebug::writeDebug( $string, eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /**
     * Adds the timing point if the condition $conditionName is enabled.
     *
     * @param string $conditionName Name of the condition
     * @param string $label Optional label
     */
    static function addTimingPoint( $conditionName, $label = "" )
    {
        if ( !eZDebugSetting::isConditionTrue( $conditionName, eZDebug::LEVEL_TIMING_POINT ) )
            return false;
        eZDebug::addTimingPoint( eZDebugSetting::changeLabel( $conditionName, $label ) );
    }

    /**
     * Sets the INI object
     *
     * @param eZINI $ini The eZINI object to set.
     *
     * @deprecated Since 4.5
     */
    static function setDebugINI( $ini )
    {
        eZDebug::writeStrict( __METHOD__ . ' is deprecated as of 4.5.', __METHOD__ );
    }

}

?>
