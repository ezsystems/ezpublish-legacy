#!/usr/bin/env php
<?php
/**
 * File containing the script to cleanup from database policies defined on module which do not exist in a modules folder
 * according to settings from module.ini/[ModuleSettings]/ExtensionRepositories
 *
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
        'description' => "Remove from database policies defined on module which do not exist in a modules folder"
            . "according to settings from module.ini/[ModuleSettings]/ExtensionRepositories",
        'use-session' => false,
        'use-modules' => true,
        'use-extensions' => true
    )
);
$script->startup();
$options = $script->getOptions(
    "[dry-run][n]",
    '',
    array(
        'dry-run' => "Test mode, output the list of affected policies without removing them",
        'n' => "Do not wait"
    )
);
$script->initialize();

$optDryRun = (bool) $options['dry-run'];

if ( !$optDryRun && !isset( $options['n'] ) )
{
    $cli->warning( "This cleanup script is going to remove policies defined on module that do not exist." );
    $cli->warning();
    $cli->warning( "You have 10 seconds to break the script (press Ctrl-C)" );
    sleep( 10 );
    $cli->output();
}

$rows =  eZPersistentObject::fetchObjectList( eZPolicy::definition(),
    array(),
    null,
    false,
    null,
    false, false,
    array( array( 'operation' => 'count( * )',
                  'name' => 'count' ) ) );
$total = $rows[0]['count'];
if ( !$optDryRun )
{
    $cli->output( "{$total} policies to check... (In the progess bar, 'R' means that the policy was removed)" );
}
else
{
    $cli->output( "{$total} policies to check..." );
}

if ( !$optDryRun )
{
    $script->setIterationData( 'R', '.' );
    $script->resetIteration( $total );
}

$limitation = array( 'offset' => 0, 'limit' => 100 );

$db = eZDB::instance();

$modules = eZModule::globalPathList();

$removedPolicies = 0;
while ( true )
{
    $policies = eZPersistentObject::fetchObjectList(
        eZPolicy::definition(),
        null,
        null,
        null,
        $limitation,
        true
    );
    if ( empty( $policies ) )
    {
        break;
    }
    foreach ( $policies as $policy )
    {
        if ( $policy->attribute('module_name') === '*' )
        {
            continue;
        }

        $moduleExists = false;
        foreach ( $modules as $module )
        {
            if ( file_exists( $module . '/' . $policy->attribute('module_name') ) )
            {
                $moduleExists = true;
                break;
            }
        }
        if ( !$moduleExists )
        {
            if ( !$optDryRun )
            {
                $policy->removeThis();
            }
            $removedPolicies++;
        }
        if ( $optDryRun )
        {
            if ( !$moduleExists )
            {
                $cli->output( "Policy defined on module '" . $policy->attribute('module_name') . "' can be removed" );
            }
        }
        else
        {
            $script->iterate( $cli, !$moduleExists );
        }
    }
    $limitation['offset'] += $limitation['limit'] - $removedPolicies;
}

$cli->output();

if ( $optDryRun && $removedPolicies > 0 )
{
    $cli->output( "{$removedPolicies} policies can be removed" );
}
else if ( $removedPolicies > 0 )
{
    $cli->output( "Removed {$removedPolicies} policies" );
}
else
{
    $cli->output( "No policies found to remove" );
}

$script->shutdown();
