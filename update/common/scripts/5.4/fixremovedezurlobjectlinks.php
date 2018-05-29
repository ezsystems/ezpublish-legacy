#!/usr/bin/env php
<?php
/**
 * File containing the upgrade script to fix older occurrences of link items
 * remaining in the ezurl_object_table despite being removed from versions/translations.
 * Based on ../4.1/fixezurlobjectlinks.php which fixes the opposite case.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 */

require 'autoload.php';

set_time_limit( 0 );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "Fix occurrences of link items that have been removed from XML-blocks, but\n" .
                                                        "not from the URL object link table. This script will remove these orphaned\n" .
                                                        "entries.\n" .
                                                        "\n" .
                                                        "fixremovedezurlobjectlinks.php" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true,
                                    ) );


$config = "[fix][fetch-limit:]";
$argConfig = "";
$optionHelp = array(
                    "fix" => "Fix orphaned ezurl-object-link references.",
                    "fetch-limit" => "The number of attributes to fetch in one chunk. Default value is 200,\nthe limit must be higher than 1."
                    );
$arguments = false;
$useStandardOptions = true;

$script->startup();
$options = $script->getOptions( $config, $argConfig, $optionHelp, $arguments, $useStandardOptions );
$script->initialize();
$script->setIterationData( '.', '+' );

$linkRemove = new ezpUrlObjectLinkRemove( $cli, $script, $options );

$cli->output( $cli->stylize( 'red', "Found ") . $cli->stylize( 'green', $linkRemove->xmlTextContentObjectAttributeCount() ) . $cli->stylize( 'red', " occurrences of 'ezxmltext'." ) );

$cli->output();
$cli->output( "Starting to process content object attributes." );
$cli->output( "Fetch limit: " . $cli->stylize( 'green', $linkRemove->fetchLimit ) );
$cli->output();
$linkRemove->processData();

$cli->output();
$cli->output();
$cli->output( $cli->stylize( 'red', "Detailed script summary:" ) );
$cli->output();
$linkRemove->showSummary();

$cli->output();
$script->shutdown();

/**
 * Utility class to help remove occurrences of external http-links that are
 * removed from versions/translations of an object
 */
class ezpUrlObjectLinkRemove
{
    public $verboseLevel;
    public $xmlClassAttributeIdArray = null;
    public $xmlAttrCount = null;

    public $offset;
    public $fetchLimit;
    public $processedCount;

    public $outputEntryNumber;
    public $finalOutputMessageArray = array();

    public $cli;
    public $script;

    public $doFix;

    /**
     * Create a new instance of the ezpUrlObjectLink object.
     *
     * @param eZCLI $cli
     * @param eZScript $script
     * @param array $options
     */
    public function __construct( $cli, $script, $options )
    {
        $this->cli = $cli;
        $this->script = $script;

        $this->offset = 0;
        $this->fetchLimit = 200;
        $this->processedCount = 0;

        $this->verboseLevel = $this->script->verboseOutputLevel();

        $this->script->resetIteration( $this->xmlTextContentObjectAttributeCount() );

        $this->doFix = false;
        if ( $options['fix'] !== null and $options['fix'] )
        {
            $this->doFix = true;
        }

        if ($options['fetch-limit'] !== null and $options['fetch-limit'] > 1 )
        {
            $this->fetchLimit = $options['fetch-limit'];
        }
    }

    /**
     * Get an array of all defined class attributes with ezxmltext datatype.
     *
     * @return array
     */
    public function xmlClassAttributeIds()
    {
        if ( $this->xmlClassAttributeIdArray === null )
        {
            // First we want to find all class attributes which are defined. We won't be touching
            // attributes which are in a transient state.
            $xmlTextAttributes = eZContentClassAttribute::fetchList( true, array( 'data_type' => 'ezxmltext',
                                                                                  'version' => 0 ) );
            $this->xmlClassAttributeIdArray = array();

            foreach ( $xmlTextAttributes as $classAttr )
            {
                $this->xmlClassAttributeIdArray[] = $classAttr->attribute( 'id' );
            }
            unset( $xmlTextAttributes );
        }
        return $this->xmlClassAttributeIdArray;
    }

    /**
     * Retrieve the number of valid ezxmltext occurences
     *
     * @return int
     */
    public function xmlTextContentObjectAttributeCount()
    {
        if ( $this->xmlAttrCount === null )
        {
            $this->xmlAttrCount = eZContentObjectAttribute::fetchListByClassID( $this->xmlClassAttributeIds(), false, null, false, true );
        }
        return $this->xmlAttrCount;
    }

    /**
     * Add a message to the message buffer, to be displayed after processData has completed.
     *
     * @param string $message
     * @param string $label
     * @param bool $groupedEntry
     * @return void
     */
    public function outputString( $message, $label = null, $groupedEntry = false )
    {
        if ( $groupedEntry )
        {
            $this->finalOutputMessageArray[$this->outputEntryNumber]["messages"][] = $message;
        }
        else
        {
            $this->outputEntryNumber++;
            $this->finalOutputMessageArray[$this->outputEntryNumber] = array( "messages" => array( $message ),
                                                                              "label" => $label );
        }
    }

    /**
     * Search through valid ezxmltext occurrences, and fix missing url object links if
     * specified.
     *
     * @return void
     */
    public function processData()
    {
        while ( $this->processedCount < $this->xmlTextContentObjectAttributeCount() )
        {
            $limit = array( 'offset' => $this->offset,
                            'length' => $this->fetchLimit );

            $xmlAttributeChunk = eZContentObjectAttribute::fetchListByClassID( $this->xmlClassAttributeIds(), false, $limit, true, false );

            foreach ( $xmlAttributeChunk as $xmlAttr )
            {
                $result = true;
                // If the current entry has been logged, we don't increment the running number
                // so that the entries can be displayed together on output.
                $currentEntryLogged = false;

                $currentId = $xmlAttr->attribute( 'id' );
                $objectId = $xmlAttr->attribute( 'contentobject_id' );
                $version = $xmlAttr->attribute( 'version' );
                $languageCode = $xmlAttr->attribute( 'language_code' );

                $label = "Attribute [id:{$currentId}] - [Object-id:{$objectId}] - [Version:{$version}] - [Language:{$languageCode}]";

                $xmlText = eZXMLTextType::rawXMLText( $xmlAttr );
                if ( empty( $xmlText ) )
                {
                    if ( $this->verboseLevel > 0 )
                    {
                        $this->outputString( "Empty XML-data", $label, $currentEntryLogged );
                        $currentEntryLogged = true;
                    }
                    $result = false;
                    continue;
                }

                $dom = new DOMDocument( '1.0', 'utf-8' );
                $success = $dom->loadXML( $xmlText );
                if ( !$success )
                {
                    if ( $this->verboseLevel > 0 )
                    {
                        $this->outputString( "XML not loaded correctly for attribute", $label, $currentEntryLogged );
                        $currentEntryLogged = true;
                    }
                    $result = false;
                    continue;
                }

                $linkNodes = $dom->getElementsByTagName( 'link' );
                $urlIdArray = array();

                foreach ( $linkNodes as $link )
                {
                    // We are looking for external 'http://'-style links, not the internal
                    // object or node links.
                    if ( $link->hasAttribute( 'url_id' ) )
                    {
                        $urlIdArray[] = $link->getAttribute( 'url_id' );
                    }
                }
                $urlIdArray = array_unique( $urlIdArray );

                if ( count( $urlIdArray ) > 0 )
                {
                    if ( $this->verboseLevel > 0 )
                    {
                        $this->outputString( "Found http-link elements in xml-block", $label, $currentEntryLogged );
                        $currentEntryLogged = true;
                    }

                    if ( $this->doFix)
                    {
                        $db = eZDB::instance();
                        $db->begin();
                        eZSimplifiedXMLInput::updateUrlObjectLinks( $xmlAttr, $urlIdArray );
                        $db->commit();
                    }
                }

                $this->script->iterate( $this->cli, $result );
                $label = null;
            }

            $this->processedCount += count( $xmlAttributeChunk );
            $this->offset += $this->fetchLimit;
            unset( $xmlAttributeChunk );
        }
    }

    /**
     * Print a summary of all the messages created during processData.
     *
     * @return void
     */
    public function showSummary()
    {
        foreach ( $this->finalOutputMessageArray as $messageEntry )
        {
            if ( $messageEntry['label'] !== null )
            {
                $this->cli->output( $this->cli->stylize( 'bold', $messageEntry['label'] ) );
            }

            foreach ( $messageEntry['messages'] as $msg )
            {
                $this->cli->output( $msg);
            }
            $this->cli->output();
        }
    }
}
?>
