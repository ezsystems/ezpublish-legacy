#!/usr/bin/env php
<?php
//
// Created on: <22-Mar-2004 13:00:25 amos>
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Changelog converter\n\n" .
                                                         "Converts a Changelog into XML text format usable in eZ publish\n" .
                                                         "The result is printed to the standard output" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "[changelog]",
                                false, false,
                                array( 'log' => false,
                                       'siteaccess' => false ) );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $cli->error( "Missing changelog file" );
    $script->shutdown( 1 );
}

$changelogFilename = $options['arguments'][0];

$fd = fopen( $changelogFilename, 'r' ) or $script->shutdown( 1, "Couldn't not open file $changelogFilename" );
$text = fread( $fd, filesize( $changelogFilename ) );
fclose( $fd );

$lines = explode( "\n", $text );

function isChangeEntry( $line, &$changeText )
{
    if ( preg_match( "/^- (.+)$/", $line, $matches ) )
    {
        $changeText = $matches[1];
        return true;
    }
    return false;
}

function addListItem( &$listLines, $changeText )
{
    if ( $changeText !== false )
    {
        $methodText = 'http|https|ftp|sftp';
        $elements = preg_split( "#((?:(?:$methodText)://)(?:(?:[a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+(?:(?:[\/a-zA-Z0-9_+$-]+\\.)*(?:[\/a-zA-Z0-9_+$-])*))(?:\#[a-zA-Z0-9-]+)?\?*(?:[a-zA-Z0-9_-]+=[a-zA-Z0-9_-]+&*)*)#m",
                                $changeText,
                                false,
                                PREG_SPLIT_DELIM_CAPTURE );
        $newElements = array();
        $i = 0;
        foreach ( $elements as $element )
        {
            if ( ( $i % 2 ) == 1 )
            {
                $element = "<link href=\"$element\">$element</link>";
            }
            $newElements[] = $element;
            ++$i;
        }
        $changeText = implode( '', $newElements );

        $listLines[] = '<li>' . $changeText . '</li>';
    }
}

function createList( &$newLines, &$listLines )
{
    if ( count( $listLines ) > 0 )
    {
        $newLines[] = '<ul>';
        $newLines = array_merge( $newLines, $listLines );
        $newLines[] = '</ul>';
        $newLines[] = '';
        $listLines = array();
    }
}

function isEmptyLine( $line )
{
    return strlen( trim( $line ) ) == 0;
}

$newLines = array();
$lineNumber = 0;
$listCounter = 0;
$listLines = array();
$currentListEntry = false;
foreach ( $lines as $line )
{
    ++$lineNumber;
    if ( $lineNumber == 1 )
    {
        $newLines[] = $line;
        $newLines[] = '';
        continue;
    }
    if ( $listCounter > 0 )
    {
        if ( isChangeEntry( $line, $changeText ) )
        {
            addListItem( $listLines, $currentListEntry );
            $currentListEntry = $changeText;
        }
        else if ( isEmptyLine( $line ) )
        {
            addListItem( $listLines, $currentListEntry );
            $currentListEntry = false;
            $listCounter = 0;
        }
        else
        {
            $currentListEntry .= ' ' . trim( $line );
        }
    }
    else if ( preg_match( "/^(\*?)(.+):/", $line, $matches ) )
    {
        addListItem( $listLines, $currentListEntry );
        $currentListEntry = false;
        createList( $newLines, $listLines );

        $header = $matches[2];
        $headerLevel = 1;
        if ( !$matches[1] )
            $headerLevel += 1;
        $newLines[] = "<header level=$headerLevel>$header</header>";
        $listCounter = 1;
    }
}
addListItem( $listLines, $currentListEntry );
$currentListEntry = false;
createList( $newLines, $listLines );

$newText = implode( "\n", $newLines );

$cli->output( $newText, false );

$script->shutdown();

?>
