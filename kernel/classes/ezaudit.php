<?php
/**
 * File containing the eZAudit class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class eZAudit
{
    const DEFAULT_LOG_DIR = 'log/audit';

    /**
     * Returns an associative array of all names of audit and the log files used by this class,
     * Will be fetched from ini settings.
     *
     * @return array
     */
    static function fetchAuditNameSettings()
    {
        $ini = eZINI::instance( 'audit.ini' );

        $auditNames = $ini->hasVariable( 'AuditSettings', 'AuditFileNames' )
                      ? $ini->variable( 'AuditSettings', 'AuditFileNames' )
                      : array();
        $varDir = eZINI::instance()->variable( 'FileSettings', 'VarDir' );
        // concat varDir setting with LogDir setting
        $logDir = $varDir . '/';
        $logDir .= $ini->hasVariable( 'AuditSettings', 'LogDir' ) ? $ini->variable( 'AuditSettings', 'LogDir' ): self::DEFAULT_LOG_DIR;

        $resultArray = array();
        foreach ( array_keys( $auditNames ) as $auditNameKey )
        {
            $auditNameValue = $auditNames[$auditNameKey];
            $resultArray[$auditNameKey] = array( 'dir' => $logDir,
                                                 'file_name' => $auditNameValue );
        }
        return $resultArray;
    }

    /**
     * Writes $auditName with $auditAttributes as content
     * to file or database, based on INI configuration
     *
     * @param string $auditName
     * @param array $auditAttributes
     * @return bool
     */
    static function writeAudit( $auditName, $auditAttributes = array() )
    {
        $auditDestination = self::fetchAuditDestination();
        if ( $auditDestination === 'database' )
        {
            return self::writeAuditToDatabase( $auditName, $auditAttributes );
        }

        return self::writeAuditToLog( $auditName, $auditAttributes );
    }

    /**
     * Writes $auditName with $auditAttributes as content to database table
     *
     * @param string $auditName
     * @param array $auditAttributes
     * @return bool
     */
    static function writeAuditToDatabase( $auditName, $auditAttributes = array() )
    {
        $enabled = self::isAuditEnabled();
        if ( !$enabled )
            return false;

        $enabledAudits = self::fetchEnabledAudits();
        if ( !in_array( $auditName, $enabledAudits ) )
            return false;

        return eZDBAudit::writeAudit( $auditName, $auditAttributes );
    }

    /**
     * Writes $auditName with $auditAttributes as content
     * to file name that will be fetched from ini settings by auditNameSettings() for logging.
     *
     * @param string $auditName
     * @param array $auditAttributes
     * @return bool
     */
    static function writeAuditToLog( $auditName, $auditAttributes = array() )
    {
        $enabled = self::isAuditEnabled();
        if ( !$enabled )
            return false;

        $enabledAudits = self::fetchEnabledAudits();
        if ( !in_array( $auditName, $enabledAudits ) )
            return false;

        $auditNameSettings = self::auditNameSettings();
        if ( !isset( $auditNameSettings[$auditName] ) )
            return false;

        $ip = eZSys::clientIP();
        if ( !$ip )
            $ip = eZSys::serverVariable( 'HOSTNAME', true );

        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $userLogin = $user->attribute( 'login' );

        $message = "[$ip] [$userLogin:$userID]\n";

        foreach ( array_keys( $auditAttributes ) as $attributeKey )
        {
            $attributeValue = $auditAttributes[$attributeKey];
            $message .= "$attributeKey: $attributeValue\n";
        }

        $logName = $auditNameSettings[$auditName]['file_name'];
        $dir = $auditNameSettings[$auditName]['dir'];
        eZLog::write( $message, $logName, $dir );

        return true;
    }

    /**
     * Returns true if audit should be enabled.
     *
     * @return boolean
     */
    static function isAuditEnabled()
    {
        if ( isset( $GLOBALS['eZAuditEnabled'] ) )
        {
            return $GLOBALS['eZAuditEnabled'];
        }
        $enabled = self::fetchAuditEnabled();
        $GLOBALS['eZAuditEnabled'] = $enabled;
        return $enabled;
    }

    /**
     * Returns true if audit should be enabled.
     * Will fetch from ini setting.
     *
     * @return bool
     */
    static function fetchAuditEnabled()
    {
        $ini = eZINI::instance( 'audit.ini' );
        $auditEnabled = $ini->hasVariable( 'AuditSettings', 'Audit' )
                      ? $ini->variable( 'AuditSettings', 'Audit' )
                      : 'disabled';
        $enabled = $auditEnabled == 'enabled';
        return $enabled;
    }

    /**
     * Returns an associative array of all names of audit and the log files used by this class
     *
     * @return array
     */
    static function auditNameSettings()
    {
        if ( isset( $GLOBALS['eZAuditNameSettings'] ) )
        {
            return $GLOBALS['eZAuditNameSettings'];
        }
        $nameSettings = self::fetchAuditNameSettings();
        $GLOBALS['eZAuditNameSettings'] = $nameSettings;
        return $nameSettings;
    }

    /**
     * Returns audit destination
     *
     * @return string
     */
    static function fetchAuditDestination()
    {
        if ( isset( $GLOBALS['eZAuditDestination'] ) )
        {
            return $GLOBALS['eZAuditDestination'];
        }

        $ini = eZINI::instance( 'audit.ini' );
        $destination = $ini->hasVariable( 'AuditSettings', 'AuditDestination' )
                     ? $ini->variable( 'AuditSettings', 'AuditDestination' )
                     : 'log';

        $GLOBALS['eZAuditDestination'] = $destination;
        return $destination;
    }

    /**
     * Returns the list of enabled audits
     *
     * @return array
     */
    static function fetchEnabledAudits()
    {
        if ( isset( $GLOBALS['eZAuditEnabledAudits'] ) )
        {
            return $GLOBALS['eZAuditEnabledAudits'];
        }

        $ini = eZINI::instance( 'audit.ini' );

        $enabledAudits = array();
        if ( $ini->hasVariable( 'AuditSettings', 'EnabledAudits' ) )
        {
            $enabledAudits = $ini->variable( 'AuditSettings', 'EnabledAudits' );
        }

        $GLOBALS['eZAuditEnabledAudits'] = $enabledAudits;
        return $enabledAudits;
    }
}
?>
