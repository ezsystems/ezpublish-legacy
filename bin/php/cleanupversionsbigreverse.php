#!/usr/bin/env php
<?php
/**
 * File containing the script to cleanup versions according content.ini/[VersionManagement]/DefaultVersionHistoryLimit
 * and VersionHistoryClass settings
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package
 */

require_once 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "This script will progressively cleanup history of big contentobjects.",
        'use-session' => false,
        'use-modules' => true,
        'use-extensions' => true
    )
);
$script->startup();
$script->initialize();

$cli->output("Collection 10 big content objects... pls hoad on...");


$db = eZDB::instance();
$rows = $db->arrayQuery( 'select contentobject_id, count(*) amount from `ezcontentobject_attribute` group by contentobject_id order by amount desc limit 10' );

foreach ( $rows as $row )
{
    $cli->output($row['contentobject_id']." has ".$row['amount']." ... ");
    $object = eZContentObject::fetch( $row['contentobject_id'] );
    $versionCount = $object->getVersionCount();
    $versionLimit = eZContentClass::versionHistoryLimit( $object->attribute( 'content_class' ) );
    if ( $versionCount <= $versionLimit )
    {
        return true;
    }
    $versionToRemove = $versionCount - $versionLimit;
    $versions = $object->versions( true, array(
        'conditions' => array( 'status' => \eZContentObjectVersion::STATUS_ARCHIVED ),
        'sort' => array( 'modified' => 'asc' ),
        'limit' => array( 'limit' => $versionToRemove, 'offset' => 0 ),
    ) );
    $db->begin();
    foreach( $versions as $version )
    {
        $version->removeThis();
    }
    $db->commit();
}


$script->shutdown();