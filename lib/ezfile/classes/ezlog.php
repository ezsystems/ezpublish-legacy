<?php
/**
 * File containing the eZLog class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/**
* This class handles writing to log files and log rotating
*
* @package lib
* @subpackage ezutils
*/
class eZLog
{
    const MAX_LOGROTATE_FILES = 3;
    const MAX_LOGFILE_SIZE = 204800; // 200*1024

    /**
     * Creates a new log object.
     */
    public function __construct()
    {

    }

    /**
     * Writes a message $message to a given file name $logName and directory $dir for logging
     *
     * @static
     * @param string $message
     * @param string $logName
     * @param string $dir
     * @return void
     */
    public static function write( $message, $logName = 'common.log', $dir = 'var/log' )
    {
        $fileName = $dir . '/' . $logName;
        $oldumask = @umask( 0 );

        $fileExisted = file_exists( $fileName );
        if ( $fileExisted and
             filesize( $fileName ) > self::maxLogSize() )
        {
            if ( eZLog::rotateLog( $fileName ) )
                $fileExisted = false;
        }
        else if ( !$fileExisted and !file_exists( $dir ) )
        {
            eZDir::mkdir( $dir, false, true );
        }

        $logFile = @fopen( $fileName, "a" );
        if ( $logFile )
        {
            $time = strftime( "%b %d %Y %H:%M:%S", strtotime( "now" ) );
            $logMessage = "[ " . $time . " ] $message\n";
            @fwrite( $logFile, $logMessage );
            @fclose( $logFile );
            if ( !$fileExisted )
            {
                $ini = eZINI::instance();
                $permissions = octdec( $ini->variable( 'FileSettings', 'LogFilePermissions' ) );
                @chmod( $fileName, $permissions );
            }
            @umask( $oldumask );
        }
        else
        {
            eZDebug::writeError( 'Couldn\'t create the log file "' . $fileName . '"', __METHOD__ );
        }
    }

    /**
     * Writes file name $name and storage directory $dir to storage log
     *
     * @static
     * @param string $name
     * @param bool $dir
     * @return void
     */
    public static function writeStorageLog( $name, $dir = false )
    {
        $ini = eZINI::instance();
        $varDir = $ini->variable( 'FileSettings', 'VarDir' );
        $logDir = $ini->variable( 'FileSettings', 'LogDir' );
        $storageLogDir = $varDir . '/' . $logDir;

        if ( $dir !== false )
        {
            $dir = preg_replace( "#/$#", "", $dir );
            $dir .= "/";
        }
        else
        {
            $dir = "";
        }

        $message = "[" . $dir . $name . "]";
        self::write( $message, 'storage.log', $storageLogDir );
    }

    /**
     * Returns the maxium size for a log file in bytes.
     *
     * @static
     * @return int
     */
    public static function maxLogSize()
    {
        $maxLogSize =& $GLOBALS['eZMaxLogSize'];
        if ( isset( $maxLogSize ) )
            return $maxLogSize;
        return self::MAX_LOGFILE_SIZE;
    }

    /**
     * Sets the maxium size for a log file to $size.
     *
     * @static
     * @param  $size
     * @return void
     */
    public static function setMaxLogSize( $size )
    {
        $GLOBALS['eZMaxLogSize'] = $size;
    }

    /**
     * Returns the maxium number of logrotate files to keep.
     *
     * @static
     * @return int
     */
    public static function maxLogrotateFiles()
    {
        $maxLogrotateFiles =& $GLOBALS['eZMaxLogrotateFiles'];
        if ( isset( $maxLogrotateFiles ) )
            return $maxLogrotateFiles;
        return self::MAX_LOGROTATE_FILES;
    }

    /**
     * Rotates the logfile $fileName
     * 
     * Rotates logfiles so the current logfile is backed up,
     * old rotate logfiles are rotated once more and those that
     * exceed self::maxLogrotateFiles() will be removed.
     * Rotated files will get the extension .1, .2 etc.
     *
     * @static
     * @param string $fileName
     * @return bool
     */
    public static function rotateLog( $fileName )
    {
        $maxLogrotateFiles = self::maxLogrotateFiles();
        for ( $i = $maxLogrotateFiles; $i > 0; --$i )
        {
            $logRotateName = $fileName . '.' . $i;
            if ( file_exists( $logRotateName ) )
            {
                if ( $i == $maxLogrotateFiles )
                {
                    @unlink( $logRotateName );
                }
                else
                {
                    $newLogRotateName = $fileName . '.' . ($i + 1);
                    eZFile::rename( $logRotateName, $newLogRotateName );
                }
            }
        }
        if ( file_exists( $fileName ) )
        {
            $newLogRotateName = $fileName . '.' . 1;
            eZFile::rename( $fileName, $newLogRotateName );
            return true;
        }
        return false;
    }
    
    /**
     * Sets the number of files to be rotated
     *
     * @static
     * @param  $files
     * @return void
     */
    static function setLogrotateFiles( $files )
    {
        $GLOBALS['eZMaxLogrotateFiles'] = $files;
    }
}

?>
