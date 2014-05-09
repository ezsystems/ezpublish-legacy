<?php
/**
 * File containing the updatenodeassignmentparentremoteids.php script
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

require_once 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "Updates 'parent_remote_id' column in 'eznode_assignment' table.\n" .
            "Can be used on a live site to update node assignments for Locations of unpublished Content, see https://jira.ez.no/browse/EZP-22260.",
        'use-session' => false,
        'use-modules' => false,
        'use-extensions' => true
    )
);
$script->startup();

$options = $script->getOptions(
    "[n][iteration-sleep:][iteration-limit:]",
    "",
    array(
        'iteration-sleep' => 'Sleep duration between batches, in seconds (default: 1)',
        'iteration-limit' => 'Batch size (default: 100)',
        'n' => 'Do not wait 30 seconds before starting'
    )
);
$optIterationSleep = (int)$options['iteration-sleep'] ?: 1;
$optIterationLimit = (int)$options['iteration-limit'] ?: 100;

$script->initialize();

$condition = array(
    "op_code" => eZNodeAssignment::OP_CODE_CREATE,
    "parent_remote_id" => "",
);
$limit = array(
    "offset" => 0,
    "limit" => $optIterationLimit,
);
$count = 0;
$totalCount = eZPersistentObject::count( eZNodeAssignment::definition(), $condition );

$cli->warning( "This script will update empty 'parent_remote_id' of all node assignments with OP_CODE_CREATE." );
$cli->warning( "If node assignment's 'remote_id' is not empty its value will be copied to 'parent_remote_id' and set to 0." );
$cli->warning( "Otherwise new hash for 'parent_remote_id' will be calculated." );
$cli->output();
$cli->warning( "For more details about this cleanup, see https://jira.ez.no/browse/EZP-22260" );
$cli->output();
$cli->output( "Found total of {$totalCount} node assignments to be updated" );
$cli->output();

if ( $totalCount == 0 )
{
    $cli->output( "Nothing to process, exiting" );
    $script->shutdown( 0 );
}

if ( !isset( $options['n'] ) )
{
    $cli->warning( "You have 30 seconds to break the script before actual processing starts (press Ctrl-C)" );
    $cli->warning( "(execute the script with '-n' switch to skip this delay)" );
    sleep( 30 );
    $cli->output();
}

$db = eZDB::instance();

while (
    $nodeAssignments = eZPersistentObject::fetchObjectList(
        eZNodeAssignment::definition(),
        null,
        $condition,
        null,
        $limit
    )
)
{
    $db->begin();

    /** @var $nodeAssignments \ezNodeAssignment[] */
    foreach ( $nodeAssignments as $nodeAssignment )
    {
        $currentParentRemoteId = $nodeAssignment->attribute( "parent_remote_id" );
        if ( !empty( $currentParentRemoteId ) )
        {
            $cli->output( "Skipped: node assignment #{$nodeAssignment->ID}" );
            continue;
        }

        $currentRemoteId = $nodeAssignment->attribute( "remote_id" );
        if ( !empty( $currentRemoteId ) )
        {
            $nodeAssignment->setAttribute( "remote_id", 0 );
            $nodeAssignment->setAttribute( "parent_remote_id", $currentRemoteId );
            $cli->output( "Updated node assignment #{$nodeAssignment->ID}: copied 'parent_remote_id' from 'remote_id'" );
        }
        else
        {
            $nodeAssignment->setAttribute(
                "parent_remote_id",
                eZRemoteIdUtility::generate( "eznode_assignment" )
            );
            $cli->output( "Updated node assignment #{$nodeAssignment->ID}: calculated new 'parent_remote_id'" );
        }

        $nodeAssignment->store();
        $count += 1;
    }

    $db->commit();
    $limit["offset"] += $optIterationLimit;
    sleep( $optIterationSleep );
}

$cli->output();
$cli->output( "Updated total of {$count} node assignments" );
$cli->output();
$cli->output( "Done" );

$script->shutdown();
