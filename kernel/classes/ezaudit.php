<?php
//
// Definition of eZAudit class
//
// Created on: <01-aug-2006 11:00:54 vd>
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

class eZAudit
{
    const DEFAULT_LOG_DIR = 'var/log/audit';

    /*!
      Creates a new audit object.
    */
    function eZAudit( )
    {
    }

    /*
     \static
     Returns an associative array of all names of audit and the log files used by this class,
     Will be fetched from ini settings.
    */
    static function fetchAuditNameSettings()
    {
        $ini = eZINI::instance( 'audit.ini' );

        $auditNames = $ini->hasVariable( 'AuditSettings', 'AuditFileNames' )
                      ? $ini->variable( 'AuditSettings', 'AuditFileNames' )
                      : array();
        $logDir = $ini->hasVariable( 'AuditSettings', 'LogDir' ) ? $ini->variable( 'AuditSettings', 'LogDir' ): self::DEFAULT_LOG_DIR;

        $resultArray = array();
        foreach ( array_keys( $auditNames ) as $auditNameKey )
        {
            $auditNameValue = $auditNames[$auditNameKey];
            $resultArray[$auditNameKey] = array( 'dir' => $logDir,
                                                 'file_name' => $auditNameValue );
        }
        return $resultArray;
    }

    /*!
     \static
     Writes $auditName with $auditAttributes as content
     to file name that will be fetched from ini settings by auditNameSettings() for logging.
    */
    static function writeAudit( $auditName, $auditAttributes = array() )
    {
        $enabled = eZAudit::isAuditEnabled();
        if ( !$enabled )
            return false;

        $auditNameSettings = eZAudit::auditNameSettings();

        if ( !isset( $auditNameSettings[$auditName] ) )
            return false;

        $ip = eZSys::serverVariable( 'REMOTE_ADDR', true );
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

    /*!
     \static
     \return true if audit should be enabled.
    */
    static function isAuditEnabled()
    {
        if ( isset( $GLOBALS['eZAuditEnabled'] ) )
        {
            return $GLOBALS['eZAuditEnabled'];
        }
        $enabled = eZAudit::fetchAuditEnabled();
        $GLOBALS['eZAuditEnabled'] = $enabled;
        return $enabled;
    }

    /*!
     \static
     \return true if audit should be enabled.
     \note Will fetch from ini setting.
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

    /*!
     \static
     Returns an associative array of all names of audit and the log files used by this class
    */
    static function auditNameSettings()
    {
        if ( isset( $GLOBALS['eZAuditNameSettings'] ) )
        {
            return $GLOBALS['eZAuditNameSettings'];
        }
        $nameSettings = eZAudit::fetchAuditNameSettings();
        $GLOBALS['eZAuditNameSettings'] = $nameSettings;
        return $nameSettings;
    }
}
?>
