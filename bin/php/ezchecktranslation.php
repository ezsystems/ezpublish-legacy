#!/usr/bin/env php
<?php
//
// Created on: <31-Mar-2004 11:12:27 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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
$script =& eZScript::instance( array( 'description' => ( "eZ publish Translation Checker\n\n" .
                                                         "Will display some statistics on a given translation" .
                                                         "\n" .
                                                         "ezchecktranslation.php ita-IT" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[ignore-tr-setup]",
                                "[translation]",
                                array( 'ignore-tr-setup' => 'Tells the analyzer to skip all translations regarding the setup' ) );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
    $script->shutdown( 1, "No translation specified" );

$translationName = false;
$translationFile = $options['arguments'][0];
if ( !file_exists( $translationFile ) )
{
    $translationName = $translationFile;
    $translationFile = 'share/translations/' . $translationName . '/translation.ts';
}

if ( !file_exists( $translationFile ) )
    $script->shutdown( 1, "Translation file $translationFile does not exist" );

$cli->output( $cli->stylize( 'file', $translationFile ) . ":", false );
$cli->output( " loading", false );
$fd = fopen( $translationFile, "rb" );
$transXML = fread( $fd, filesize( $translationFile ) );
fclose( $fd );

include_once( "lib/ezxml/classes/ezxml.php" );
$xml = new eZXML();

$cli->output( " parsing", false );
$tree =& $xml->domTree( $transXML );

$cli->output( " validating", false );
include_once( 'lib/ezi18n/classes/eztstranslator.php' );
if ( !eZTSTranslator::validateDOMTree( $tree ) )
    $script->shutdown( 1, "XML text for file $translationFile did not validate" );


function handleContextNode( $context, &$cli, &$data )
{
    $contextName = null;
    $messages = array();
    $context_children = $context->children();
    foreach( $context_children as $context_child )
    {
        if ( $context_child->type() == 1 )
        {
            if ( $context_child->name() == "name" )
            {
                $data['context_count']++;
                $name_el = $context_child->children();
                if ( count( $name_el ) > 0 )
                {
                    $name_el = $name_el[0];
                    $contextName = $name_el->content();
                }
            }
            else if ( $context_child->name() == "message" )
            {
                $messages[] = $context_child;
            }
            else
                $cli->warning( "Unknown element name: " . $context_child->name() );
        }
        else
            $cli->warning( "Unknown DOMnode type: " . $context_child->type() );
    }
    if ( $contextName === null )
    {
        $cli->warning( "No context name found, skipping context" );
        return false;
    }

    if ( !in_array( $contextName, $data['ignored_context_list'] ) )
    {
        foreach( $messages as $message )
        {
            $data['element_count']++;
            handleMessageNode( $contextName, $message, $cli, $data, true );
        }
    }
    return true;
}

function handleMessageNode( $contextName, &$message, &$cli, &$data, $requireTranslation )
{
    $source = null;
    $translation = null;
    $comment = null;
    $message_children =& $message->children();
    foreach( $message_children as $message_child )
    {
        if ( $message_child->type() == 1 )
        {
            if ( $message_child->name() == "source" )
            {
                $source_el = $message_child->children();
                $source_el = $source_el[0];
                $source = $source_el->content();
            }
            else if ( $message_child->name() == "translation" )
            {
                $translation_el = $message_child->children();
                $type = $message_child->attributeValue( 'type' );
                if ( $type == 'unfinished' )
                {
                    $data['untranslated_element_count']++;
                }
                else if ( $type == 'obsolete' )
                {
                    $data['obsolete_element_count']++;
                }
                else
                {
                    $data['translated_element_count']++;
                }
                if ( count( $translation_el ) > 0 )
                {
                    $translation_el = $translation_el[0];
                    $translation = $translation_el->content();
                }
            }
            else if ( $message_child->name() == "comment" )
            {
                $comment_el = $message_child->children();
                $comment_el = $comment_el[0];
                $comment = $comment_el->content();
            }
            else
                $cli->warning( "Unknown element name: " . $message_child->name() );
        }
        else
            $cli->warning( "Unknown DOMnode type: " . $message_child->type() );
    }
    if ( $source === null )
    {
        $cli->warning( "No source name found, skipping message" );
        return false;
    }
    if ( $translation === null )
    {
        return false;
    }
    return true;
}

$data = array( 'element_count' => 0,
               'context_count' => 0,
               'translated_element_count' => 0,
               'untranslated_element_count' => 0,
               'obsolete_element_count' => 0 );
$data['ignored_context_list'] = array();

if ( $options['ignore-tr-setup'] )
{
    $data['ignored_context_list'] = array_merge( $data['ignored_context_list'],
                                                 array( 'design/standard/setup',
                                                        'design/standard/setup/datatypecode',
                                                        'design/standard/setup',
                                                        'design/standard/setup/db',
                                                        'design/standard/setup/init',
                                                        'design/standard/setup/operatorcode',
                                                        'design/standard/setup/tests' ) );
}

$treeRoot =& $tree->get_root();
$children = $treeRoot->children();
foreach( $children as $child )
{
    if ( $child->type() == 1 )
    {
        if ( $child->name() == "context" )
        {
            handleContextNode( $child, $cli, $data );
        }
        else
            $cli->warning( "Unknown element name: " . $child->name() );
    }
    else
        $cli->warning( "Unknown DOMnode type: " . $child->type() );
}

$cli->output();

$cli->output( $cli->stylize( 'header', "Name" ) . "          " . $cli->stylize( "header", "Count" ) );
$cli->output( "context:      " . $data['context_count'] );
$cli->output( "element:      " . $data['element_count'] );
$cli->output( "translated:   " . $data['translated_element_count'] );
$cli->output( "untranslated: " . $data['untranslated_element_count'] );
$cli->output( "obsolete:     " . $data['obsolete_element_count'] );
$cli->output();
$totalCount = $data['translated_element_count'] + $data['untranslated_element_count'];
if ( $totalCount == 0 )
{
    $percentText = "no elements";
}
else
{
    $percent = ( $data['translated_element_count'] * 100 ) / $totalCount;
    $percentText = number_format( $percent, 2 ) . "%";
}

$cli->output( "Percent finished: " . $percentText );

$script->shutdown();

?>
