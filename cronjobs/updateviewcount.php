<?php
//
// Definition of  class
//
// Created on: <07-Jul-2003 10:06:19 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file updateviewcount.php
*/
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( "lib/ezutils/classes/ezsys.php" );
include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "kernel/classes/ezviewcounter.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( 'lib/ezlocale/classes/ezdatetime.php' );

set_time_limit( 0 );
eZModule::setGlobalPathList( array( "kernel" ) );

if ( !$isQuiet )
    print( "Update content view count ...\n"  );


$dt = new eZDateTime();
$year = $dt->year();
$month = date( 'M', mktime() );
$day = $dt->day();
$hour = $dt->hour();
$minute = $dt->minute();
$second = $dt->second();
$startTime = $day . "/" . $month . "/" . $year . ":" . $hour . ":" . $minute . ":" . $second;
//$startTime = "29/Aug/2003:11:31:42";
print( "Started at " . $dt->toString() . "\n\n"  );
$nodeIDArray = array();

$pathArray = array();

$contentArray = array();

$nonContentArray = array();

$ini =& eZINI::instance();
$logFileIni =& eZINI::instance( 'logfile.ini' );
$fileDir = $logFileIni->variable( 'AccessLogFileSettings', 'StorageDir' );
$fileName = $logFileIni->variable( 'AccessLogFileSettings', 'LogFileName' );

$prefixes = $logFileIni->variable( 'AccessLogFileSettings', 'SitePrefix' );

$ini =& eZINI::instance();
$logDir = $ini->variable( 'FileSettings', 'LogDir' );

$db =& eZDB::instance();
$db->setIsSQLOutputEnabled( false );

$sys =& eZSys::instance();
$varDir = $sys->varDirectory();
$updateViewLog = "updateview.log";

$startLine = "";
$hasStartLine = false;
$fh = fopen( $varDir . "/" . $logDir . "/" . $updateViewLog, "r" );
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

print( "Start line:\n" .$startLine . "\n" );
$lastLine = "";
$handle = fopen( $fileDir . $fileName, "r" );
if ( $handle )
{
    $count = 0;
    $startParse = false;
    $stopParse = false;
    while ( !feof ($handle) and !$stopParse )
    {
        $line = fgets($handle, 1024);
        if ( $line != "" )
            $lastLine = $line;

        if ( $startParse or !$hasStartLine )
        {
            $logPartArray = preg_split( "/[\"]+/", $line );

            $timeIPPart = $logPartArray[0];
            list( $ip, $timePart ) = split( "\[", $timeIPPart );
            list( $time, $rest ) = split( " ", $timePart );

            if ( $time == $startTime )
                $stopParse = true;
            $requirePart = $logPartArray[1];

            list( $requireMethod, $url ) = split( " ", $requirePart );
            $url = preg_replace( "/\?.*/", "", $url);
            foreach ( $prefixes as $prefix )
            {
                $url = preg_replace( "/^\/$prefix/", "", $url );
            }

            if ( preg_match( "/content\/view\/full\//", $url ) )
            {
                $url = str_replace( "content/view/full/", "", $url );
                $url = str_replace( "/", "", $url );
                $url = preg_replace( "/\?(.*)/", "", $url );
                $nodeIDArray[] = $url;
            }
            else
            {
                $urlArray = split( "/", $url );
                $firstElement = $urlArray[0];
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
                    if ( $firstElement != "" )
                    {
                        $pathIdentificationString = $db->escapeString( $firstElement );

                        //check in database, if fount, add to contentArray, else add to nonContentArray.
                        $query = "SELECT node_id FROM ezcontentobject_tree
	                          WHERE path_identification_string='$pathIdentificationString'";
                        $result = $db->arrayQuery( $query );
                        if ( count($result) != 0 )
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
        /*$count++;
        if ( $count == 7 )
            break;*/
    }
    fclose($handle);
}

/*print_r($nonContentArray);
print_r($contentArray);
print_r($nodeIDArray );
print_r($pathArray );*/

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
    $pathIdentification = $db->escapeString( $path );
    $nodeIDList = $db->arrayQuery( "SELECT node_id FROM ezcontentobject_tree
	                                WHERE path_identification_string='$pathIdentification'" );

    if ( $nodeIDList != null )
    {
        $nodeID = $nodeIDList[0]['node_id'];
        $counter = eZViewCounter::fetch( $nodeID );
        if ( $counter == null )
            $counter = eZViewCounter::create( $nodeID );
        $counter->increase();
    }
}

$dt = new eZDateTime();

$fh = fopen( $varDir . "/" . $logDir . "/" . $updateViewLog, "w" );
if ( $fh )
{
    fwrite( $fh, "# Finished at " . $dt->toString() . "\n" );
    fwrite( $fh, "# Last updated entry:" . "\n" );
    fwrite( $fh, $lastLine . "\n" );
    fclose( $fh );
}

print( "Finished at " . $dt->toString() . "\n\n"  );
if ( !$isQuiet )
    print( "View count have been updated!\n" );

?>
