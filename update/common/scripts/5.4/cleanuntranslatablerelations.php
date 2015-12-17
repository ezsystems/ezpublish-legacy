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
        'description' => "Fixes stale object relations for non-translatable objecrelation attributes. See issue EZP-25065\n",
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
$optIterationLimit = (int)$options['iteration-limit'] ?: 10;

$limit = array(
    "offset" => 0,
    "limit" => $optIterationLimit,
);

$script->initialize();
$db = eZDB::instance();

if ( $optDryRun ) {
    $cli->warning( "dry-run mode" );
}


// main loop
do {
    // get all relations for all attribute versions from non-translatable ezobjectrelation class attributes
    $rows = $db->arrayQuery(
        "SELECT contentobject_id, attr.version, classattr.id as attr_id, data_int FROM ezcontentobject_attribute attr ".
        "INNER JOIN ezcontentclass_attribute classattr ON attr.contentclassattribute_id=classattr.id " .
        "WHERE classattr.data_type_string='ezobjectrelation' AND can_translate=0 AND NOT data_int IS null " .
        "GROUP BY contentobject_id, version, data_int"
    );

    $count = count($rows);
    $cli->notice( "Processing $count attribute versions..." );

    $db->begin();
    foreach ( $rows as $attributeInfo )
    {
        $contentObjectId = $attributeInfo['contentobject_id'];
        $version = $attributeInfo['version'];
        $relationId = $attributeInfo['data_int'];
        $attrId = $attributeInfo['attr_id'];

        $relationType = eZContentObject::RELATION_ATTRIBUTE;

        if ( !$optDryRun )
        {
            $db->query(
                "DELETE FROM ezcontentobject_link " .
                "WHERE relation_type=$relationType " .
                "AND contentclassattribute_id=$attrId AND from_contentobject_id=$contentObjectId AND from_contentobject_version=$version " .
                "AND to_contentobject_id != $relationId"
            );
        }
    }
    $db->commit();

    $limit["offset"] += $optIterationLimit;
    sleep( $optIterationSleep );
} while ( count($rows) == $optIterationLimit );

$cli->output( "Update has been completed, please clear caches if necessary." );

$script->shutdown();
