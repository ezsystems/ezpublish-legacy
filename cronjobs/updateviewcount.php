<?php
/**
 * File containing the updateviewcount.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
set_time_limit( 0 );

$cli->output( "Update content view count..."  );

$dt = new eZDateTime();
$year = $dt->year();
$month = date( 'M', time() );
$day = $dt->day();
$hour = $dt->hour();
$minute = $dt->minute();
$second = $dt->second();
$startTime = $day . "/" . $month . "/" . $year . ":" . $hour . ":" . $minute . ":" . $second;

$cli->output( "Started at " . $dt->toString() . "\n"  );
$nodeIDArray = array();

$pathArray = array();

$contentArray = array();

$nonContentArray = array();

$ini = eZINI::instance();
$logFileIni = eZINI::instance( 'logfile.ini' );
$fileDir = $logFileIni->variable( 'AccessLogFileSettings', 'StorageDir' );
$fileName = $logFileIni->variable( 'AccessLogFileSettings', 'LogFileName' );

$prefixes = $logFileIni->variable( 'AccessLogFileSettings', 'SitePrefix' );
$pathPrefixes = $logFileIni->variable( 'AccessLogFileSettings', 'PathPrefix' );
$pathPrefixesCount = count( $pathPrefixes );

$ini = eZINI::instance();
$logDir = $ini->variable( 'FileSettings', 'LogDir' );

$db = eZDB::instance();
$db->setIsSQLOutputEnabled( false );

$sys = eZSys::instance();
$varDir = $sys->varDirectory();
$updateViewLog = "updateview.log";

$startLine = "";
$hasStartLine = false;

$updateViewLogPath = $varDir . "/" . $logDir . "/" . $updateViewLog;
if ( is_file( $updateViewLogPath ) )
{
    $fh = fopen( $updateViewLogPath, "r" );
    if ( $fh )
    {
        while ( !feof ( $fh ) )
        {
            $line = fgets( $fh, 1024 );
            if ( preg_match( "/\[/", $line ) )
            {
                $startLine = $line;
                $hasStartLine = true;
            }
        }
        fclose( $fh );
    }
}

$cli->output( "Start line:\n" . $startLine );
$lastLine = "";
$logFilePath = $fileDir . '/' . $fileName;

if ( is_file( $logFilePath ) )
{
    $handle = fopen( $logFilePath, "r" );
    if ( $handle )
    {
        $startParse = false;
        $stopParse = false;
        while ( !feof ($handle) and !$stopParse )
        {
            $line = fgets($handle, 1024);
            if ( !empty( $line ) )
            {
                if ( $line != "" )
                    $lastLine = $line;

                if ( $startParse or !$hasStartLine )
                {
                    $logPartArray = preg_split( "/[\"]+/", $line );
                    $timeIPPart = $logPartArray[0];
                    list( $ip, $timePart ) = explode( '[', $timeIPPart );
                    list( $time, $rest ) = explode( ' ', $timePart );

                    if ( $time == $startTime )
                        $stopParse = true;
                    $requirePart = $logPartArray[1];

                    list( $requireMethod, $url ) = explode( ' ', $requirePart );
                    $url = preg_replace( "/\?.*/", "", $url);
                    foreach ( $prefixes as $prefix )
                    {
                        $urlChanged = preg_replace( '/^\/' . preg_quote( $prefix, '/' ) . '(\/|$)/', '/', $url );
                        if ( $urlChanged != $url )
                        {
                            $url = $urlChanged;
                            break;
                        }
                    }

                    if ( strpos( $url, 'content/view/full/' ) !== false )
                    {
                        $url = str_replace( "content/view/full/", "", $url );
                        $url = str_replace( "/", "", $url );
                        $url = preg_replace( "/\?(.*)/", "", $url );
                        $nodeIDArray[] = $url;
                    }
                    else
                    {
                        $urlArray = explode( '/', $url );
                        $firstElement = $urlArray[1];
                        if ( in_array( $firstElement, $contentArray ) )
                        {
                            $pathArray[] = $url;
                        }
                        else if ( in_array( $firstElement, $nonContentArray ) )
                        {
                            // do nothing
                        }
                        else
                        {
                            if ( $firstElement != "" || $url === '/' )
                            {
                                $pathIdentificationString = $db->escapeString( $firstElement );

                                //check in database, if found, add to contentArray, else add to nonContentArray.
                                $result = eZURLAliasML::fetchNodeIDByPath( $pathIdentificationString );

                                // Fix for sites using PathPrefix
                                $pathPrefixIndex = 0;
                                while ( !$result )
                                {
                                    if ( $pathPrefixIndex < $pathPrefixesCount )
                                    {
                                        // Try prepending each of the existing pathPrefixes, to see if one of them matches an existing node
                                        $pathIdentificationString = $db->escapeString( $pathPrefixes[$pathPrefixIndex] . '/' . $firstElement );
                                        $result = eZURLAliasML::fetchNodeIDByPath( $pathIdentificationString );
                                    }
                                    else
                                        break;
                                    $pathPrefixIndex++;
                                }

                                if ( $result )
                                {
                                    $contentArray[] = $firstElement;
                                    $pathArray[] = $url;
                                }
                                else
                                {
                                    if ( $firstElement != "content" )
                                        $nonContentArray[] = $firstElement;
                                }
                            }
                        }
                    }
                }
                if ( $line == $startLine )
                {
                    $startParse = true;
                }
            }
        }
        fclose( $handle );
    }
    else
    {
        $cli->output( "Warning: Cannot open apache log-file '$logFilePath' for reading, please check permissions and try again." );
    }
}
else
{
    $cli->output( "Warning: apache log-file '$logFilePath' doesn't exist, please check your ini-settings and try again." );
}

foreach ( $nodeIDArray as $nodeID )
{
    $nodeObject = eZContentObjectTreeNode::fetch( $nodeID );
    if ( $nodeObject != null )
    {
        $counter = eZViewCounter::fetch( $nodeID );
        if ( $counter == null )
            $counter = eZViewCounter::create( $nodeID );
        $counter->increase();
    }
}

foreach ( $pathArray as $path )
{
    $nodeID = eZURLAliasML::fetchNodeIDByPath( $path );

    // Support for PathPrefix
    for ( $pathPrefixIndex = 0; !$nodeID && $pathPrefixIndex < $pathPrefixesCount; ++$pathPrefixIndex )
    {
        // Try prepending each of the existing pathPrefixes, to see if one of them matches an existing node
        $nodeID = eZURLAliasML::fetchNodeIDByPath( $db->escapeString( $pathPrefixes[$pathPrefixIndex] . $path ) );
    }

    if ( $nodeID )
    {
        $counter = eZViewCounter::fetch( $nodeID );
        if ( $counter == null )
            $counter = eZViewCounter::create( $nodeID );
        $counter->increase();
    }
}

$dt = new eZDateTime();

$fh = fopen( $updateViewLogPath, "w" );
if ( $fh )
{
    fwrite( $fh, "# Finished at " . $dt->toString() . "\n" );
    fwrite( $fh, "# Last updated entry:" . "\n" );
    fwrite( $fh, $lastLine . "\n" );
    fclose( $fh );
}

$cli->output( "Finished at " . $dt->toString() . "\n"  );
$cli->output( "View count have been updated!\n" );

?>
