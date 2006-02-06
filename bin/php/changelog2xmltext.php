#!/usr/bin/env php
<?php
//
// Created on: <22-Mar-2004 13:00:25 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

        $elements = preg_split( "/bug #([0-9]+)/im",
                                $changeText,
                                false,
                                PREG_SPLIT_DELIM_CAPTURE );
        $newElements = array();
        $i = 0;
        foreach ( $elements as $element )
        {
            if ( ( $i % 2 ) == 1 )
            {
                $element = "<link href=\"/bugs/view/$element\">bug #$element</link>";
            }
            $newElements[] = $element;
            ++$i;
        }
        $changeText = implode( '', $newElements );

        $changeText = preg_replace( "# *\( *?(:?manually +)?merged +from +[a-z0-9.-]+(?:/[a-z0-9.-]+)*[,/]? +(\([0-9](?:[.-][0-9a-z]+)*\))? *(?:rev|erv)(?:ision|\.)? *[0-9]+ *\)#i", '', $changeText );

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
