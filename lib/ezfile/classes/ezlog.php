<?php
//
// Definition of eZLog class
//
// Created on: <17-Mar-2003 11:00:54 wy>
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

/*! \defgroup eZUtils Utility classes */

/*!
  \class eZLog ezlog.php
  \ingroup eZUtils
*/


include_once( "lib/ezutils/classes/ezsys.php" );
define( "EZ_MAX_LOGROTATE_FILES", 3 );
define( "EZ_MAX_LOGFILE_SIZE", 200*1024 );
class eZLog
{
    /*!
      Creates a new log object.
    */
    function eZLog( )
    {
    }

    /*!
     \public
     Writes a message $message to a given file name $name and directory $dir for logging
    */
    function write( $message, $logName = 'common.log', $dir = 'var/log' )
    {
        $fileName = $dir . '/' . $logName;
        if ( !file_exists( $dir ) )
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::mkdir( $dir, 0775, true );
        }
        $oldumask = @umask( 0 );

        $fileExisted = @file_exists( $fileName );
        if ( $fileExisted and
             filesize( $fileName ) > eZLog::maxLogSize() )
        {
            if ( eZLog::rotateLog( $fileName ) )
                $fileExisted = false;
        }

        $logFile = @fopen( $fileName, "a" );
        if ( $logFile )
        {
            $time = strftime( "%b %d %Y %H:%M:%S", strtotime( "now" ) );
            $logMessage = "[ " . $time . " ] $message\n";
            @fwrite( $logFile, $logMessage );
            @fclose( $logFile );
            if ( !$fileExisted )
                @chmod( $fileName, 0666 );
            @umask( $oldumask );
        }
    }

    /*!
     \private
     Writes file name $name and storage directory $dir to storage log
    */
    function writeStorageLog( $name, $dir = false )
    {
//        $ini =& eZINI::instance();
//        $varDir = $ini->variable( 'FileSettings', 'VarDir' );
//        $logDir = $ini->variable( 'FileSettings', 'LogDir' );
        $varDir = 'var';
        $logDir = 'log';
        $logName = 'storage.log';
        $fileName = $varDir . '/' . $logDir . '/' . $logName;
        if ( !file_exists( $varDir . '/' . $logDir ) )
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::mkdir( $varDir . '/' . $logDir, 0775, true );
        }
        $oldumask = @umask( 0 );

        $fileExisted = @file_exists( $fileName );
        if ( $fileExisted and
             filesize( $fileName ) > eZLog::maxLogSize() )
        {
            if ( eZLog::rotateLog( $fileName ) )
                $fileExisted = false;
        }

        if ( $dir !== false )
        {
            $dir = preg_replace( "#/$#", "", $dir );
            $dir .= "/";
        }
        else
        {
            $dir = "";
        }

        $logFile = @fopen( $fileName, "a" );
        if ( $logFile )
        {
            $time = strftime( "%b %d %Y %H:%M:%S", strtotime( "now" ) );
            $logMessage = "[ " . $time . " ] [" . $dir . $name . "]\n";
            @fwrite( $logFile, $logMessage );
            @fclose( $logFile );
            if ( !$fileExisted )
                @chmod( $fileName, 0666 );
            @umask( $oldumask );
        }
    }

    /*!
     \static
     \return the maxium size for a log file in bytes.
    */
    function maxLogSize()
    {
        $maxLogSize =& $GLOBALS['eZMaxLogSize'];
        if ( isset( $maxLogSize ) )
            return $maxLogSize;
        return EZ_MAX_LOGFILE_SIZE;
    }

    /*!
     \static
     Sets the maxium size for a log file to \a $size.
    */
    function setMaxLogSize( $size )
    {
        $GLOBALS['eZMaxLogSize'] = $size;
    }

    /*!
     \static
     \return the maxium number of logrotate files to keep.
    */
    function maxLogrotateFiles()
    {
        $maxLogrotateFiles =& $GLOBALS['eZMaxLogrotateFiles'];
        if ( isset( $maxLogrotateFiles ) )
            return $maxLogrotateFiles;
        return EZ_MAX_LOGROTATE_FILES;
    }

    /*!
     \static
     Rotates logfiles so the current logfile is backed up,
     old rotate logfiles are rotated once more and those that
     exceed maxLogrotateFiles() will be removed.
     Rotated files will get the extension .1, .2 etc.
    */
    function rotateLog( $fileName )
    {
        $maxLogrotateFiles = eZLog::maxLogrotateFiles();
        for ( $i = $maxLogrotateFiles; $i > 0; --$i )
        {
            $logRotateName = $fileName . '.' . $i;
            if ( @file_exists( $logRotateName ) )
            {
                if ( $i == $maxLogrotateFiles )
                {
                    @unlink( $logRotateName );
                }
                else
                {
                    $newLogRotateName = $fileName . '.' . ($i + 1);
                    @rename( $logRotateName, $newLogRotateName );
                }
            }
        }
        if ( @file_exists( $fileName ) )
        {
            $newLogRotateName = $fileName . '.' . 1;
            @rename( $fileName, $newLogRotateName );
            return true;
        }
        return false;
    }

    /*!
     \static
     Sets the maxium number of logrotate files to keep to \a $files.
    */
    function setLogrotateFiles( $files )
    {
        $GLOBALS['eZMaxLogrotateFiles'] = $filse;
    }

}

?>
