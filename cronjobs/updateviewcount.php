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
$startTime = $dt->day() . "/" . date( 'M', time() ) . "/" . $dt->year() . ":" . $dt->hour() . ":" . $dt->minute() . ":" . $dt->second();

$cli->output( "Started at " . $dt->toString() . "\n"  );

eZDB::instance()->setIsSQLOutputEnabled( false );

$startLine = "";
$hasStartLine = false;

$updateViewLogPath = eZSys::instance()->varDirectory() . "/" . eZINI::instance()->variable( 'FileSettings', 'LogDir' ) . "/updateview.log";
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
$logFileIni = eZINI::instance( 'logfile.ini' );
$logFilePath = $logFileIni->variable( 'AccessLogFileSettings', 'StorageDir' ) . '/' . $logFileIni->variable( 'AccessLogFileSettings', 'LogFileName' );
$prefixes = $logFileIni->variable( 'AccessLogFileSettings', 'SitePrefix' );
$pathPrefixes = $logFileIni->variable( 'AccessLogFileSettings', 'PathPrefix' );
$pathPrefixesCount = count( $pathPrefixes );

$nodeIDHashCounter = array();
$pathHashCounter = array();
$contentHash = array();
$nonContentHash = array();

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
            if ( empty( $line ) )
            {
                continue;
            }

            if ( $line != "" )
                $lastLine = $line;

            if ( $startParse or !$hasStartLine )
            {
                $logPartArray = preg_split( "/[\"]+/", $line );
                list( $ip, $timePart ) = explode( '[', $logPartArray[0] );
                list( $time, $rest ) = explode( ' ', $timePart );

                if ( $time == $startTime )
                    $stopParse = true;

                list( $requireMethod, $url ) = explode( ' ', $logPartArray[1] );
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
                    if ( !isset( $nodeIDHashCounter[$url] ) )
                    {
                        $nodeIDHashCounter[$url] = 1;
                    }
                    else
                    {
                        ++$nodeIDHashCounter[$url];
                    }
                }
                else
                {
                    $urlArray = explode( '/', $url );
                    $firstElement = $urlArray[1];
                    if ( isset( $contentHash[$firstElement] ) )
                    {
                        if ( !isset( $pathHashCounter[$url] ) )
                        {
                            $pathHashCounter[$url] = 1;
                        }
                        else
                        {
                            ++$pathHashCounter[$url];
                        }
                    }
                    else if ( !isset( $nonContentHash[$firstElement] ) && ( $firstElement != "" || $url === '/' ) )
                    {
                        //check in database, if found, add to contentHash, else add to nonContentHash.
                        $result = eZURLAliasML::fetchNodeIDByPath( $firstElement );

                        // Fix for sites using PathPrefix
                        $pathPrefixIndex = 0;
                        while ( !$result )
                        {
                            if ( $pathPrefixIndex >= $pathPrefixesCount )
                                break;

                            // Try prepending each of the existing pathPrefixes, to see if one of them matches an existing node
                            $result = eZURLAliasML::fetchNodeIDByPath( $pathPrefixes[$pathPrefixIndex] . '/' . $firstElement );
                            $pathPrefixIndex++;
                        }

                        if ( $result )
                        {
                            $contentHash[$firstElement] = 1;
                            if ( !isset( $pathHashCounter[$url] ) )
                            {
                                $pathHashCounter[$url] = 1;
                            }
                            else
                            {
                                ++$pathHashCounter[$url];
                            }
                        }
                        else if ( $firstElement != "content" )
                        {
                            $nonContentHash[$firstElement] = 1;
                        }
                    }
                }
            }
            if ( $line == $startLine )
            {
                $startParse = true;
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

// Process the content of $pathHashCounter to transform it into $nodeIDHashCounter
foreach ( $pathHashCounter as $path => $count )
{
    $nodeID = eZURLAliasML::fetchNodeIDByPath( $path );

    // Support for PathPrefix
    for ( $pathPrefixIndex = 0; !$nodeID && $pathPrefixIndex < $pathPrefixesCount; ++$pathPrefixIndex )
    {
        // Try prepending each of the existing pathPrefixes, to see if one of them matches an existing node
        $nodeID = eZURLAliasML::fetchNodeIDByPath( $pathPrefixes[$pathPrefixIndex] . $path );
    }

    if ( $nodeID )
    {
        if ( !isset( $nodeIDHashCounter[$nodeID] ) )
        {
            $nodeIDHashCounter[$nodeID] = 1;
        }
        else
        {
            ++$nodeIDHashCounter[$nodeID];
        }
    }
}

foreach ( $nodeIDHashCounter as $nodeID => $count )
{
    if ( eZContentObjectTreeNode::fetch( $nodeID ) != null )
    {
        $counter = eZViewCounter::fetch( $nodeID );
        if ( $counter == null )
        {
            $counter = eZViewCounter::create( $nodeID );
            $counter->setAttribute( 'count', $count );
            $counter->store();
        }
        else
        {
            $counter->increase( $count );
        }
    }
}

$dt = new eZDateTime();

$fh = fopen( $updateViewLogPath, "w" );
if ( $fh )
{
    fwrite(
        $fh,
        "# Finished at " . $dt->toString() . "\n" .
        "# Last updated entry:" . "\n" .
        $lastLine . "\n"
    );
    fclose( $fh );
}

$cli->output( "Finished at " . $dt->toString() . "\n"  );
$cli->output( "View count have been updated!\n" );

?>
