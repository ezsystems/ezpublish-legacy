<?php
/**
 * File containing the ${NAME} class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

require_once 'autoload.php';
$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "Fixes invalid references to image files in trashed content. See issue EZP-25077\n",
        'use-session' => true,
        'use-modules' => false,
        'use-extensions' => true
    )
);
$script->startup();

$options = $script->getOptions(
    "[dry-run][iteration-sleep:][iteration-limit:]",
    "",
    array(
        'dry-run' => 'Dry run'
    )
);
$optDryRun = (bool)$options['dry-run'];
$optIterationSleep = (int)$options['iteration-sleep'] ?: 1;
$optIterationLimit = (int)$options['iteration-limit'] ?: 100;

$limit = array(
    "offset" => 0,
    "limit" => $optIterationLimit,
);

$script->initialize();
$db = eZDB::instance();

if ( $optDryRun ) {
    $cli->warning( "dry-run mode" );
}

/**
 * Fix xml attributes for empty images that have been trashed.
 *
 * @param $imageAttribute
 * @param $optDryRun bool   if in dry-run mode (do not update DB row)
 */
function fixupTrashedImageXml( $imageAttribute, $optDryRun )
{
    if ( stripos( $imageAttribute['data_text'], ' dirpath="/trashed" ' ) === false ||
        stripos( $imageAttribute['data_text'], ' is_valid="" ' ) === false )
    {
        return;
    }

    $id = $imageAttribute['id'];
    $contentId = $imageAttribute['contentobject_id'];
    $version = $imageAttribute['version'];

    eZCLI::instance()->notice( "Processing image $contentId ($version) ..." );

    if ( ( $doc = simplexml_load_string( $imageAttribute['data_text'] ) ) === false )
        return;

    $doc['filename'] = '';
    $doc['basename'] = '';
    $doc['dirpath'] = '';
    $doc['url'] = '';

    $imageXml = $doc->asXml();

    if ( !$optDryRun ) {
        eZDB::instance()->query( "UPDATE ezcontentobject_attribute SET data_text='$imageXml' WHERE id='$id' and version='$version'" );
    }
}

// main loop
do {
    $rows = $db->arrayQuery(
        "SELECT id,contentobject_id, version, data_text " .
        "FROM ezcontentobject_attribute NATURAL JOIN ezcontentobject_trash t " .
        "WHERE data_type_string='ezimage'",
        $limit
    );

    $db->begin();
    foreach ( $rows as $imageAttribute )
    {
        fixupTrashedImageXml( $imageAttribute, $optDryRun );
    }
    $db->commit();

    $limit["offset"] += $optIterationLimit;
    sleep( $optIterationSleep );
} while ( count($rows) == $optIterationLimit );

$cli->output( "Update has been completed." );

$script->shutdown();
