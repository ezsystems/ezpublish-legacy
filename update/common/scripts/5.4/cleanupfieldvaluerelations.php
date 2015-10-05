<?php
/**
 * File containing the cleanupfieldvaluerelations.php script
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

require_once 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "Removes relation data towards non-existing Content from field values.\n" .
            "Only Relation and RelationList fieldtypes field values will be processed.\n" .
            "For more details see https://jira.ez.no/browse/EZP-22408.",
        'use-session' => false,
        'use-modules' => false,
        'use-extensions' => true
    )
);
$script->startup();

$options = $script->getOptions(
    "[n][v][iteration-sleep:][iteration-limit:]",
    "",
    array(
        'iteration-sleep' => 'Sleep duration between batches, in seconds (default: 1)',
        'iteration-limit' => 'Batch size (default: 100)',
        'n' => 'Do not wait 30 seconds before starting',
    )
);
$optIterationSleep = (int)$options['iteration-sleep'] ?: 1;
$optIterationLimit = (int)$options['iteration-limit'] ?: 100;
$verboseLevel = $script->verboseOutputLevel();

$script->initialize();

$db = eZDB::instance();

if ( !$db->isConnected() )
{
    $cli->error( "Can't initialize database connection.\n" );
    $script->shutdown( 1 );
}

$relationCount = $result = $db->arrayQuery(
    "SELECT ( COUNT( * ) ) AS relation_count
     FROM ezcontentobject_attribute
     LEFT JOIN ezcontentobject ON ezcontentobject_attribute.data_int = ezcontentobject.id
     WHERE ezcontentobject_attribute.data_type_string = 'ezobjectrelation' AND
           ezcontentobject_attribute.data_int IS NOT NULL AND
           ezcontentobject.id IS NULL"
);
$relationCount = $relationCount[0]["relation_count"];

$relationListCount = $result = $db->arrayQuery(
    "SELECT ( COUNT( * ) ) AS relationlist_count
     FROM ezcontentobject_attribute
     WHERE ezcontentobject_attribute.data_type_string = 'ezobjectrelationlist'"
);
$relationListCount = $relationListCount[0]["relationlist_count"];

$cli->warning( "This script removes relation data towards non-existing Content from field values." );
$cli->warning( "Only Relation and RelationList fieldtypes field values will be processed." );
$cli->warning( "For more details about this cleanup, see https://jira.ez.no/browse/EZP-22408." );
$cli->output();
$cli->output( "Found total of {$relationCount} Relation and {$relationListCount} RelationList field values to process." );

if ( $relationCount == 0 && $relationListCount == 0 )
{
    $cli->output( "Nothing to process, exiting." );
    $script->shutdown( 0 );
}

$cli->output();
$cli->output( "Relation field values will be updated in a single update" );
$cli->output( "RelationList field values will be processed in batches of {$optIterationLimit}, with {$optIterationSleep} seconds pause between the batches." );
$cli->warning( "Depending on the number of RelationList field values found, this script can potentially run for a very long time." );

if ( !$options['n'] )
{
    $cli->output();
    $cli->warning( "You have 30 seconds to break the script before actual processing starts (press Ctrl-C)." );
    $cli->warning( "Execute the script with '-n' switch to skip this delay." );
    sleep( 30 );
}

function processRelationRows( $relationCount )
{
    $cli = eZCLI::instance();
    $db = eZDB::instance();

    if ( $db->databaseName() == 'mysql' )
    {
        $result = $db->query(
            "UPDATE ezcontentobject_attribute
             LEFT JOIN ezcontentobject ON ezcontentobject_attribute.data_int = ezcontentobject.id
             SET data_int = NULL, sort_key_int = 0
             WHERE ezcontentobject_attribute.data_type_string = 'ezobjectrelation' AND
                   ezcontentobject_attribute.data_int IS NOT NULL AND
                   ezcontentobject.id IS NULL"
        );
    }
    else if ( $db->databaseName() == 'postgresql' )
    {
        $result = $db->query(
            "UPDATE ezcontentobject_attribute
             SET data_int=NULL, sort_key_int=0
             FROM ezcontentobject_attribute t1
             LEFT JOIN ezcontentobject ON t1.data_int=ezcontentobject.id
             WHERE ezcontentobject_attribute.data_type_string='ezobjectrelation' AND
                   ezcontentobject_attribute.data_int IS NOT NULL AND
                   ezcontentobject.id IS NULL"
        );
    }
    $cli->output( "Updated total of {$relationCount} Relation field values." );
}

$cli->output();

if ( $relationCount > 0 )
{
    $cli->output( "Processing Relation field values..." );
    processRelationRows( $relationCount );
}
else
{
    $cli->output( "There are no Relation field values to process." );
}

function processRelationListRow( array $row )
{
    $db = eZDB::instance();

    $document = new DOMDocument( "1.0", "utf-8" );
    $document->loadXML( $row["data_text"] );

    $xpath = new DOMXPath( $document );
    $xpathExpression = "//related-objects/relation-list/relation-item";

    $removedRelations = array();
    $relationItems = $xpath->query( $xpathExpression );
    /** @var \DOMElement $relationItem */
    foreach ( $relationItems as $relationItem )
    {
        $contentId = $relationItem->getAttribute( "contentobject-id" );

        if ( !eZContentObject::exists( $contentId ) )
        {
            $relationItem->parentNode->removeChild( $relationItem );
            $removedRelations[] = $contentId;
        }
    }

    if ( count( $removedRelations ) > 0 )
    {
        $db->query(
            "UPDATE ezcontentobject_attribute
             SET data_text = '" . $db->escapeString( $document->saveXML() ) . "'
             WHERE ezcontentobject_attribute.id = " . $row["id"] . " AND
                   ezcontentobject_attribute.version = " . $row["version"]
        );
    }

    return $removedRelations;
}

function loadRelationListRows( $pass, $optIterationLimit )
{
    $script = eZScript::instance();
    $cli = eZCLI::instance();
    $db = eZDB::instance();

    $relationListQuery = "SELECT id, version, contentobject_id, data_text
                      FROM ezcontentobject_attribute
                      WHERE data_type_string = 'ezobjectrelationlist'";

    $relationListArray = $db->arrayQuery(
        $relationListQuery,
        array(
            "limit" => $optIterationLimit,
            "offset" => $pass * $optIterationLimit
        )
    );
    if ( !is_array( $relationListArray ) )
    {
        $cli->error( "SQL query error: $relationListQuery" );
        $script->shutdown( 1 );
    }

    return $relationListArray;
}

function processRelationListRows( $optIterationLimit, $optIterationSleep, $verboseLevel )
{
    $cli = eZCLI::instance();

    $pass = 0;
    $relationListUpdatedCount = 0;

    $relationListArray = loadRelationListRows( $pass, $optIterationLimit );

    while( count( $relationListArray ) )
    {
        $fromNumber = $pass * $optIterationLimit;
        $toNumber = $fromNumber + count( $relationListArray );
        $cli->output( "Processing records #{$fromNumber}-{$toNumber}" );

        foreach ( $relationListArray as $row )
        {
            $removedRelationIds = processRelationListRow( $row );

            if ( count( $removedRelationIds ) )
            {
                $relationListUpdatedCount++;
                if ( $verboseLevel > 0 )
                {
                    $cli->output(
                        " - updated field value with id={$row['id']}, version={$row['version']}, " .
                        "removed relations towards: " . implode( ",", $removedRelationIds )
                    );
                }
            }
        }

        sleep( $optIterationSleep );
        $pass++;
        $relationListArray = loadRelationListRows( $pass, $optIterationLimit );
    }

    $cli->output( "Updated total of {$relationListUpdatedCount} RelationList field values." );
}

$cli->output();

if ( $relationListCount > 0 )
{
    $cli->output( "Processing RelationList field values..." );
    processRelationListRows( $optIterationLimit, $optIterationSleep, $verboseLevel );
}
else
{
    $cli->output( "There are no RelationList field values to process." );
}

$cli->output();
$cli->output( "Done." );

$script->shutdown();
