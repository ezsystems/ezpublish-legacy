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


/**
 * Parse xml_text from ezobjectrelationlist attribute and return array of related content ids
 *
 * @param string $relationListXml relationlist xml (data_text)
 *
 * @return int[] array of content ids, or empty
 */
function parseRelationListIds( $relationListXml )
{
    $relationListXml = trim( $relationListXml );
    if ( empty( $relationListXml ) )
        return array();

    $xml = simplexml_load_string( $relationListXml );
    $list = $xml->{'relation-list'};

    if ( !( $list instanceof SimpleXMLElement ) ) {
        return array();
    }

    $contentIds = array();
    foreach ( $list->children() as $item ) {
        $contentIds[] = (int)$item['contentobject-id'];
    }

    return array_unique( $contentIds );
}



// main loop
do {
    // get all relations for all attribute versions from non-translatable ezobjectrelation class attributes
    $rows = $db->arrayQuery(
        "SELECT contentobject_id, attr.version, classattr.id as attr_id, classattr.data_type_string, attr.data_int, attr.data_text " .
        "FROM ezcontentobject_attribute attr ".
        "INNER JOIN ezcontentclass_attribute classattr ON attr.contentclassattribute_id=classattr.id " .
        "WHERE classattr.data_type_string IN ( 'ezobjectrelation', 'ezobjectrelationlist' ) AND can_translate=0 " .
        "GROUP BY contentobject_id, version, data_int, attr_id, classattr.data_type_string, attr.data_text"
    );

    $count = count($rows);
    $cli->notice( "Processing $count attribute versions..." );

    $db->begin();
    foreach ( $rows as $attributeInfo )
    {
        $contentObjectId = $attributeInfo['contentobject_id'];
        $version = $attributeInfo['version'];
        $attrId = $attributeInfo['attr_id'];

        $relationType = eZContentObject::RELATION_ATTRIBUTE;

        if ( $attributeInfo['data_type_string'] == 'ezobjectrelation' ) {
            $relationId = $attributeInfo['data_int'];

            if ( empty($relationId) )
                continue;

            if ( !$optDryRun ) {
                $db->query(
                    "DELETE FROM ezcontentobject_link " .
                    "WHERE relation_type=$relationType " .
                    "AND contentclassattribute_id=$attrId AND from_contentobject_id=$contentObjectId AND from_contentobject_version=$version " .
                    "AND to_contentobject_id != $relationId"
                );
            }

        } else {
            // process objectrelationlist
            $relationIds = parseRelationListIds( $attributeInfo['data_text'] );
            if ( empty($relationIds) )
                continue;

            $relationIdsSql = implode( ', ', $relationIds );
            if ( !$optDryRun ) {
                $rows = $db->query(
                    "DELETE FROM ezcontentobject_link " .
                    "WHERE relation_type=$relationType " .
                    "AND contentclassattribute_id=$attrId AND from_contentobject_id=$contentObjectId AND from_contentobject_version=$version " .
                    "AND to_contentobject_id NOT IN ( $relationIdsSql )"
                );
            }
        }

    }
    $db->commit();

    $limit["offset"] += $optIterationLimit;
    sleep( $optIterationSleep );
} while ( count($rows) == $optIterationLimit );

$cli->output( "Update has been completed, please clear caches if necessary." );

$script->shutdown();
