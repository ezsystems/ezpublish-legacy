<?php
//
// Created on: <04-Feb-2003 11:02:34 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file updateurllink.php
 Takes care of fixing the links in the XML format to use the url ID
 instead of a HREF.
 Will also fix all /content/download links to use the new format if any
 is found.
*/

set_time_limit( 0 );

$showDebug = false;
$fixErrors = false;

$fixAttribute = true;
$fixURL = false;

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );

// eZDebug::setHandleType( EZ_HANDLE_TO_PHP );
eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );
eZDebug::setLogFileEnabled( false );
eZINI::setIsCacheEnabled( false );

include_once( 'lib/ezutils/classes/ezexecution.php' );

function eZDBCleanup()
{
    if ( class_exists( 'ezdb' )
         and eZDB::hasInstance() )
    {
        $db =& eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
//     session_write_close();
}

function eZFatalError()
{
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    print( "Fatal error: eZ publish did not finish it's request\n" );
    print( "The execution of eZ publish was abruptly ended." );
}

eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );

include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezbinaryfilehandler.php' );
include_once( 'kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php' );

include_once( 'lib/ezdb/classes/ezdb.php' );

$db =& eZDB::instance();
$db->setIsSQLOutputEnabled( false );

$xmlTypeAttributeList =& eZContentClassAttribute::fetchList( true, array( 'data_type' => 'ezxmltext',
                                                                          'version' => 0 ) );
$classAttributeIDList = array();
for ( $i = 0; $i < count( $xmlTypeAttributeList ); ++$i )
{
    $xmlTypeAttribute =& $xmlTypeAttributeList[$i];
    $classAttributeIDList[] = $xmlTypeAttribute->attribute( 'id' );
}
unset( $xmlTypeAttributeList );

$urlCount = eZURL::fetchListCount();

if ( $showDebug )
    print( "URL count = '$urlCount'\n" );

$attributeCount = eZContentObjectAttribute::fetchListByClassID( $classAttributeIDList, false, array( 'offset' => 0,
                                                                                                     'length' => 3 ),
                                                                false, true );
if ( $showDebug )
    print( "Attribute count = '$attributeCount'\n" );

$urlList =& eZURL::fetchList();

$urlRefMap = array();
$urlIDMap = array();
for ( $i = 0; $i < count( $urlList ); ++$i )
{
    $url =& $urlList[$i];
    $urlRefMap[$url->attribute( 'url' )] =& $url;
    $urlIDMap[$url->attribute( 'id' )] =& $url;
}

function findAndReplaceLinks( &$doc, &$node )
{
    global $urlRefMap;
    global $urlIDMap;
    global $showDebug;
    global $fixErrors;
    $foundLinks = false;
    unset( $children );
    if ( !$node )
        return $foundLinks;
    $children =& $node->children();
    for ( $i = 0; $i < count( $children ); ++$i )
    {
        $child =& $children[$i];
        if ( $child->name() == 'link' )
        {
            unset( $linkAttributes );
            $linkAttributes =& $child->attributes();
            unset( $hrefAttribute );
            unset( $idAttribute );
            $hrefAttribute = null;
            $idAttribute = null;
            for ( $j = 0; $j < count( $linkAttributes ); ++$j )
            {
                $linkAttribute =& $linkAttributes[$j];
                if ( $linkAttribute->name() == 'href' )
                    $hrefAttribute =& $linkAttributes[$j];
                else if ( $linkAttribute->name() == 'id' )
                    $idAttribute =& $linkAttributes[$j];
            }
            if ( $idAttribute === null and
                 $hrefAttribute !== null )
            {
                $href = $hrefAttribute->content();
                if ( array_key_exists( $href, $urlRefMap ) )
                {
                    if ( $showDebug )
                        print( "Found '$href'\n" );
                    $url =& $urlRefMap[$href];
                    $idAttribute = $doc->createAttributeNode( 'id', $url->attribute( 'id' ) );
                    $child->appendAttribute( $idAttribute );
                    $child->removeNamedAttribute( 'href' );
                    $foundLinks = true;
                }
            }
        }
        if ( findAndReplaceLinks( $doc, $child ) )
            $foundLinks = true;
        unset( $child );
    }
    unset( $children );
    return $foundLinks;
}

$wrongURLCount = 0;
$fixedURLCount = 0;
$wrongLinkCount = 0;

$attributeOffset = 0;
$attributeLimit = 50;

$dotCount = 0;
$dotTotalCount = 0;
$dotMax = 70;

include_once( 'lib/ezxml/classes/ezxml.php' );
$xml = new eZXML();

if ( $fixAttribute )
{
    print( "Fixing bad xml text links\n" );
    $badXMLArray = array();
    while ( $attributeOffset < $attributeCount )
    {
        $percent = ( $dotTotalCount * 100.0 ) / ( $attributeCount - 1 );
//         if ( $percent > 27.76 )
//             print( "cd " . $attributeOffset . ", " . $attributeLimit );
        unset( $objectAttributeList );
        $objectAttributeList =& eZContentObjectAttribute::fetchListByClassID( $classAttributeIDList, false, array( 'offset' => $attributeOffset,
                                                                                                                   'length' => $attributeLimit ),
                                                                              true, false );
//         if ( $percent > 27.76 )
//             print( "ef" );
        $lastID = false;
        for ( $i = 0; $i < count( $objectAttributeList ); ++$i )
        {
            $percent = ( $dotTotalCount * 100.0 ) / ( $attributeCount - 1 );
            $objectAttribute =& $objectAttributeList[$i];
//             if ( $percent > 27.76 )
//             {
//                 print( $objectAttribute->attribute( 'id' ) . " (" . $objectAttribute->attribute( 'version' ) . ")\n" );
//             }
//             if ( $lastID !== false and
//                  $objectAttribute->attribute( 'id' ) == $lastID)
//                 print( "Found duplicate " . $objectAttribute->attribute( 'id' ) . "\n" );
            $lastID = $objectAttribute->attribute( 'id' );
            $dataType =& $objectAttribute->dataType();
            if ( !$dataType or get_class( $dataType ) != 'ezxmltexttype' )
                continue;
            unset( $content );
            $content =& $objectAttribute->content();
            unset( $xmlData );
            $xmlData = $content->attribute( 'xml_data' );
            unset( $doc );
            $doc =& $xml->domTree( $xmlData );
            if ( $doc )
            {
                if ( findAndReplaceLinks( $doc, $doc->root() ) )
                {
                    if ( $showDebug )
                        print( "Links found and replaced\n" );
//                 print( $doc->toString() . "\n" );
                    $objectAttribute->setAttribute( 'data_text', $doc->toString() );
                    ++$wrongLinkCount;
                    print( '*' );
                }
                else
                    print( '.' );
            }
            else
            {
                if ( $showDebug )
                    print( "Invalid XML data: $xmlData\n" );
                if ( trim( $xmlData ) == '' )
                {
                    unset( $doc );
                    $doc = new eZDOMDocument();
                    $doc->setRoot( $doc->createElementNode( 'section' ) );
                    $objectAttribute->setAttribute( 'data_text', $doc->toString() );
                    ++$wrongLinkCount;
                    print( '0' );
                }
                else
                {
                    print( '-' );
                    $badXMLArray[] = array( 'id' => $objectAttribute->attribute( 'id' ),
                                            'version' => $objectAttribute->attribute( 'version' ) );
                }
            }
            if ( $fixErrors )
                $objectAttribute->sync();
            ++$dotCount;
            ++$dotTotalCount;
            if ( $dotCount >= $dotMax or $dotTotalCount >= $attributeCount - 1 )
            {
                $percent = number_format( ( $dotTotalCount * 100.0 ) / ( $attributeCount - 1 ), 2 );
                $dotSpace = str_repeat( ' ', $dotMax - $dotCount );
                print( $dotSpace . " " . $percent . "%\n" );
                $dotCount = 0;
            }
//             if ( $percent > 27.76 )
//                 print( "ab" );
        }

        $attributeOffset += $attributeLimit;
    }
    print( "\n" );
    print( ". Ignored\n" );
    print( "* Fixed url usage\n" );
    print( "- Invalid XML data\n" );
    print( "0 Empty XML data\n" );
    if ( count( $badXMLArray ) > 0 )
    {
        print( "The following attributes had bad XML\n" );
        $len = 0;
        $i = 0;
        foreach ( $badXMLArray as $badXML )
        {
            $text = '';
            if ( $len > 70 )
            {
                $len = 0;
                if ( $i > 0 )
                    print( ',' );
                print( "\n" );
            }
            else if ( $i > 0 )
                $text = ', ';
            $text .= sprintf( "%5.d(%d)",
                             $badXML['id'],
                             $badXML['version'] );
            print( $text );
            $len += strlen( $text );
            ++$i;
        }
    }
    print( "\n" );
}

$dotCount = 0;
$dotTotalCount = 0;

if ( $fixURL )
{
    $failedURLArray = array();
    print( "Fixing bad download urls\n" );
    for ( $i = 0; $i < count( $urlList ); ++$i )
    {
        $url =& $urlList[$i];
        $urlText = $url->attribute( 'url' );
        if ( preg_match( "#/?content/download/[0-9]+/[0-9]+/[a-z_-]+/.+$#", $urlText, $matches ) )
        {
            if ( $showDebug )
                print( "matched correct download url: '$urlText'\n" );
            print( '+' );
        }
        else if ( preg_match( "#/?content/download/([0-9]+)(/([0-9]*))?#", $urlText, $matches ) )
        {
            ++$wrongURLCount;
            $attributeID = $matches[1];
            $version = $matches[3];
            if ( $version == false )
                $version = 1;
            if ( $showDebug )
                print( "matched faulty download url: '$urlText' $attributeID @ $version\n" );
            $contentObjectAttribute =& eZContentObjectAttribute::fetch( $attributeID, $version );
            if ( $contentObjectAttribute )
            {
                $contentObjectID = $contentObjectAttribute->attribute( 'contentobject_id' );
                $contentObject =& eZContentObject::fetch( $contentObjectID );
                $downloadURL = eZBinaryFileHandler::downloadURL( $contentObject, $contentObjectAttribute );
                $url->setAttribute( 'url', $downloadURL );
                if ( $fixErrors )
                {
                    $url->store();
                    ++$fixedURLCount;
                }
                if ( $showDebug )
                    print( "correct download url: '$downloadURL/'\n" );
                print( '*' );
            }
            else
            {
                print( '-' );
                $failedURLArray[] = $url;
            }
        }
        else
            print( '.' );
        ++$dotCount;
        ++$dotTotalCount;
        if ( $dotCount >= $dotMax or $dotTotalCount >= $urlCount )
        {
            $percent = number_format( ( $dotTotalCount * 100.0 ) / ( $urlCount ), 2 );
            $dotSpace = '';
            if ( $dotTotalCount > $dotMax )
                $dotSpace = str_repeat( ' ', $dotMax - $dotCount );
            print( $dotSpace . " " . $percent . "%\n" );
            $dotCount = 0;
        }
    }
    print( "\n" );
    print( "+ Correct download url\n" );
    print( "* Fixed download url\n" );
    print( "- Failed to fix\n" );
    print( ". Ignored\n" );
    if ( count( $failedURLArray ) > 0 )
    {
        print( "The following urls could not be fixed\n" );
        foreach ( $failedURLArray as $failedURL )
        {
            print( sprintf( "%5.d: %s\n",
                            $failedURL->attribute( 'id' ),
                            $failedURL->attribute( 'url' ) ) );
        }
    }
    print( "\n" );
}

print( "Number of attributes      : $attributeCount\n" );
print( "Number of fixed attributes: $wrongLinkCount\n\n" );

print( "Number of urls      : $urlCount\n" );
print( "Number of bad urls  : $wrongURLCount\n" );
print( "Number of fixed urls: $fixedURLCount\n" );

if ( $showDebug )
    eZDebug::printReport( false, false );

eZExecution::cleanup();
eZExecution::setCleanExit();

?>
