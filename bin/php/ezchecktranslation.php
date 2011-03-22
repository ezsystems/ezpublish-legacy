#!/usr/bin/env php
<?php
/**
 * File containing the ezchecktranslation.php script.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

require_once 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Translation Checker\n\n" .
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


$cli->output( " parsing", false );

$tree = new DOMDOcument();
if ( !$tree->loadXML( $transXML ) )
    $script->shutdown( 1, "XML text for file $translationFile did not parse as valid XML" );

$cli->output( " validating" );
if ( !eZTSTranslator::validateDOMTree( $tree ) )
    $script->shutdown( 1, "XML text for file $translationFile did not validate" );


function handleContextNode( $context, $cli, $data )
{
    $contextName = null;
    $messages = array();
    $context_children = $context->childNodes;
    foreach ( $context_children as $context_child )
    {
        if ( $context_child->nodeType == XML_ELEMENT_NODE )
        {
            if ( $context_child->localName == "name" )
            {
                $data['context_count']++;
                $contextName = $context_child->textContent;
            }
            else if ( $context_child->localName == "message" )
            {
                $messages[] = $context_child;
            }
            else
            {
                $cli->warning( "Unknown element name: " . $context_child->localName );
            }
        }
    }
    if ( $contextName === null )
    {
        $cli->warning( "No context name found, skipping context" );
    }
    else if ( !in_array( $contextName, $data['ignored_context_list'] ) )
    {
        $data['context'] = array();
        foreach( $messages as $message )
        {
            $data['element_count']++;
            $data = handleMessageNode( $contextName, $message, $cli, $data, true );
        }
        unset( $data['context'] );
    }

    return $data;
}

function handleMessageNode( $contextName, $message, $cli, $data, $requireTranslation )
{
    $source = null;
    $translation = null;
    $comment = null;
    $type = '';
    $message_children = $message->childNodes;
    foreach( $message_children as $message_child )
    {
        if ( $message_child->nodeType == XML_ELEMENT_NODE )
        {
            if ( $message_child->localName == "source" )
            {
                $source = $message_child->textContent;
            }
            else if ( $message_child->localName == "translation" )
            {
                $translation_el = $message_child->childNodes;
                $type = $message_child->getAttribute( 'type' );
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
                if ( $translation_el->length > 0 )
                {
                    $translation_el = $translation_el->item( 0 );
                    $translation = $translation_el->textContent;
                }
            }
            else if ( $message_child->localName == "comment" )
            {
                $comment = $message_child->textContent;
            }
            else if ( $message_child->localName == "location" )
            {
                // Ignore it.
            }
            else if ( $message_child->localName == "translatorcomment" )
            {
                // Ignore it.
            }
            /// @todo add other tags that are not used in eZP but valid in qt spec
            else
            {
                $cli->warning( "Unknown element name: " . $message_child->localName . " in context $contextName" );
            }
        }
    }
    // give warnings for empty sources, not only for missing source element
    if ( $source == null )
    {
        $cli->warning( "No source name found, skipping message in context $contextName" );
    }
    else
    {
        if ( $type == '' )
        {
            // valid translations have to be not empty
            if ( $translation == '' )
            {
                $cli->warning( "Empty translation found for source '$source' in context $contextName" );
            }
            else
            {
                // check for double translations (nb: comment is part of the key)
                // nb: corner case: this works correctly in all cases ONLY if sources never end in :: or comments never start in ::
                if ( in_array( $source . '::' . $comment, $data[ 'context'] ) )
                {
                    $cli->warning( "Two translations for source '$source' in context $contextName" );
                }
                else
                {
                    $data[ 'context'][] = $source . '::' . $comment;
                }

                // a token in source: check that it is not translated too (ie. it is verbatim present in translation)
                /// @todo verify if eZP code uses the same regexp as we do to identify tokens
                if ( preg_match_all( '/(%[0-9]+|%[a-z_]+)/', $source, $matches ) )
                {
                    foreach( $matches[1] as $match )
                    {
                        if ( strpos( $translation, $match ) === false )
                        {
                            $cli->warning( "No translation found for token '$match' of source '$source' in context $contextName" );
                        }
                    }
                }
            }
        }
    }

    return $data;
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

$treeRoot = $tree->documentElement;
$children = $treeRoot->childNodes;
foreach( $children as $child )
{
    if ( $child->nodeType == XML_ELEMENT_NODE )
    {
        if ( $child->localName == "context" )
        {
            $data = handleContextNode( $child, $cli, $data );
        }
        else
        {
            $cli->warning( "Unknown element name: " . $child->localName );
        }
    }
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
