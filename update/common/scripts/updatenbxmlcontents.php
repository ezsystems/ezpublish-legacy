<?php
/**
 * File containing the updatenbxmlcontents script.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

require_once 'autoload.php';
$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "Updates non-break space encoding in ezxml contents. See issue EZP-18220\n",
        'use-session' => true,
        'use-modules' => false,
        'use-extensions' => true
    )
);
$script->startup();

$options = $script->getOptions(
    "[dry-run][n][v][iteration-sleep:][iteration-limit:]",
    "",
    array(
        'dry-run' => 'Dry run',
        'iteration-sleep' => 'Sleep duration between batches, in seconds (default: 1)',
        'iteration-limit' => 'Batch size (default: 100)',
        'n' => 'Do not wait 30 seconds before starting',
    )
);
$optDryRun = (bool)$options['dry-run'];
$optIterationSleep = $options['iteration-sleep'] ? (int)$options['iteration-sleep'] : 1;
$optIterationLimit = $options['iteration-limit'] ? (int)$options['iteration-limit'] : 100;
$verboseLevel = $script->verboseOutputLevel();

$limit = array(
    "offset" => 0,
    "limit" => $optIterationLimit,
);

$script->initialize();
$db = eZDB::instance();

if ( $optDryRun )
{
    $cli->warning( "dry-run mode" );
}

/**
 * Updates non-breaking spaces from existing "&amp;nbsp;" to proper "\xC2\xA0"
 *
 * @param array $attribute
 */
function updateEzxmlNonbreakSpaces( $attribute, $optDryRun, $verbose )
{
    $id = $attribute['id'];
    $contentId = $attribute['contentobject_id'];
    $version = $attribute['version'];
    $xmlData = $attribute['data_text'];

    $matchTags = implode('|', array( 'paragraph', 'header') );
    $pattern = '/(<(?<tag>' . $matchTags . ')[^>]*\>)(.*)&amp;nbsp;(.*)(<\/(?P=tag)>)/';
    $replace = "\\1\\3\xC2\xA0\\4\\5";

    do {
        $xmlData = preg_replace( $pattern, $replace, $xmlData, -1, $countReplaced );
    } while ($countReplaced > 0);

    if ( $verbose ) {
        eZCLI::instance()->output( "Updating data for content #$contentId (ver. $version) ..." );
    }
    if ( !$optDryRun ) {
        $db = eZDB::instance();
        $xmlData = $db->escapeString( $xmlData );
        $db->query( "UPDATE ezcontentobject_attribute SET data_text='$xmlData' WHERE id='$id' AND version='$version'" );
    }
}

if ( !$options['n'] )
{
    $cli->output();
    $cli->warning( "You have 30 seconds to break the script before actual processing starts (press Ctrl-C)." );
    $cli->warning( "Execute the script with '-n' switch to skip this delay." );
    sleep( 30 );
}

$attributeCount = $db->arrayQuery(
    "SELECT count(id) as count " .
    "FROM ezcontentobject_attribute attr " .
    "WHERE data_type_string='ezxmltext' AND data_text LIKE '%&amp;nbsp;%' "
);
$attributeCount = $attributeCount[0]['count'];

$cli->output( "Number of xml attributes to update: " . $attributeCount );

// main loop
do {
    $rows = $db->arrayQuery(
        "SELECT id, contentobject_id, version, data_text " .
        "FROM ezcontentobject_attribute attr " .
        "WHERE data_type_string='ezxmltext' AND data_text LIKE '%&amp;nbsp;%' ",
        $limit
    );

    $db->begin();
    foreach ( $rows as $attribute )
    {
        updateEzxmlNonbreakSpaces( $attribute, $optDryRun, $verboseLevel );
    }
    $db->commit();

    $cli->output(".");

    $limit["offset"] += $optIterationLimit;
    sleep( $optIterationSleep );
} while ( count($rows) == $optIterationLimit );

$cli->output( "Update has been completed." );

$script->shutdown();
