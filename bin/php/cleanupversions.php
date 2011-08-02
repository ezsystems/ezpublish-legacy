#!/usr/bin/env php
<?php
/**
 * File containing the script to cleanup versions according content.ini/[VersionManagement]/DefaultVersionHistoryLimit
 * and VersionHistoryClass settings
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package
 */

require 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "Remove archived content object versions according to "
            . "[VersionManagement/DefaultVersionHistoryLimit and "
            . "[VersionManagement]/VersionHistoryClass settings",
        'use-session' => false,
        'use-modules' => true,
        'use-extensions' => true
    )
);
$script->startup();
$options = $script->getOptions( "[n]", "", array( "n" => "Do not wait" ) );
$script->initialize();

if ( !isset( $options['n'] ) )
{
    $cli->warning( "This cleanup script is going to remove archived versions according to the settings" );
    $cli->warning( "content.ini/[VersionManagement]/DefaultVersionHistoryLimit and content.ini/[VersionManagement]/VersionHistoryClass" );
    $cli->warning();
    $cli->warning( "You have 10 seconds to break the script (press Ctrl-C)" );
    sleep( 10 );
    $cli->output();
}

$subTreeParams = array(
    'Limitation' => array(),
    'MainNodeOnly' => true,
    'LoadDataMap' => false,
    'IgnoreVisibility' => true,
);
$total = eZContentObjectTreeNode::subTreeCountByNodeID( $subTreeParams, 1 );
$cli->output( "{$total} objects to check... (In the progess bar, 'R' means that at least a version was removed)" );

$script->setIterationData( 'R', '.' );
$script->resetIteration( $total );

$subTreeParams['Offset'] = 0;
$subTreeParams['Limit'] = 100;
$db = eZDB::instance();

while ( true )
{
    $nodes = eZContentObjectTreeNode::subTreeByNodeID( $subTreeParams, 1 );
    if ( empty( $nodes ) )
    {
        break;
    }
    foreach( $nodes as $node )
    {
        $object = $node->attribute( 'object' );
        $versionCount = $object->getVersionCount();
        $versionLimit = eZContentClass::versionHistoryLimit( $object->attribute( 'content_class' ) );
        if ( $versionCount <= $versionLimit )
        {
            $script->iterate( $cli, false, "Nothing to do on object #{$object->attribute( 'id' )}" );
            continue;
        }

        $versionToRemove = $versionCount - $versionLimit;
        $versions = $object->versions( true, array(
            'conditions' => array( 'status' => eZContentObjectVersion::STATUS_ARCHIVED ),
            'sort' => array( 'modified' => 'asc' ),
            'limit' => array( 'limit' => $versionToRemove, 'offset' => 0 ),
        ) );

        $removedVersion = 0;
        $db->begin();
        foreach( $versions as $version )
        {
            $version->removeThis();
            $removedVersion++;
        }
        $db->commit();

        if ( $removedVersion > 0 )
        {
            $script->iterate( $cli, true, "Removed {$removedVersion} archived versions of object #{$object->attribute( 'id' )}" );
        }
        else
        {
            $script->iterate( $cli, false, "No archived version of object #{$object->attribute( 'id' )} found" );
        }
    }
    $subTreeParams['Offset'] += $subTreeParams['Limit'];
    eZContentObject::clearCache();
}

$script->shutdown();

?>
