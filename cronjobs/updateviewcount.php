<?php
//
// Definition of  class
//
// Created on: <07-Jul-2003 10:06:19 wy>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

            foreach ( $prefixes as $prefix )
            {
                $url = preg_replace( "/^\/$prefix/", "", $url );
            }

            if ( preg_match( "/\/content\/view\/full\//", $url ) )
            {
                $url = str_replace( "/content/view/full/", "", $url );
                $url = str_replace( "/", "", $url );
                $url = preg_replace( "/\?(.*)/", "", $url );
                $nodeIDArray[] = $url;
            }
            else
            {
                $urlArray = split( "/", $url );
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
                    if ( $firstElement != "" )
                    {
                        $pathIdentificationString = $firstElement;
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
    $nodeObject =& eZContentObjectTreeNode::fetch( $nodeID );
    if ( $nodeObject != null )
    {
        $counter =& eZViewCounter::fetch( $nodeID );
        if ( $counter == null )
            $counter =& eZViewCounter::create( $nodeID );
        $counter->increase();
    }
}

foreach ( $pathArray as $path )
{
    $pathIdentification = $path;
    $pathIdentification = substr ($pathIdentification,1);
    $nodeIDList = $db->arrayQuery( "SELECT node_id FROM ezcontentobject_tree
	                                WHERE path_identification_string='$pathIdentification'" );

    if ( $nodeIDList != null )
    {
        $nodeID = $nodeIDList[0]['node_id'];
        $counter =& eZViewCounter::fetch( $nodeID );
        if ( $counter == null )
            $counter =& eZViewCounter::create( $nodeID );
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
