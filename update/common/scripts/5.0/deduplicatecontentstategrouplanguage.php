#!/usr/bin/env php
<?php
/**
 * File containing the deduplicate content object state group language script.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package update
 */

require 'autoload.php';

set_time_limit( 0 );

$cli = eZCLI::instance();
$script = eZScript::instance(
    array(
        'description' => 'Deduplicates content object state group language ' .
            'records. See issue http://issues.ez.no/19169.',
        'use-session' => false,
        'use-modules' => false,
        'use-extensions' => true
    )
);
$options = $script->getOptions( '', '', array( '-q' => 'Quiet mode' ) );

$cli = eZCLI::instance();

$script->initialize();
$script->startup();

$db = eZDB::instance();

$limit = 50;
$offset = 0;

while ( true )
{
    $groups = eZContentObjectStateGroup::fetchByOffset( $limit, $offset );
    if ( empty( $groups ) )
    {
        break;
    }
    foreach ( $groups as $group )
    {
        $cli->output( 'Fixing translations for group ' . $group->attribute( 'identifier' ), false );
        $groupId = $group->attribute( 'id' );
        $defaultLangId = $group->attribute( 'default_language_id' );

        // for the default language id, the correct record is the one with
        // the always available bit, so we delete records without it
        // ie language_id = real_language_id = $defaultLangId
        $queryDefaultLang = "DELETE FROM ezcobj_state_group_language
            WHERE contentobject_state_group_id={$groupId}
            AND language_id={$defaultLangId} AND real_language_id={$defaultLangId}";

        // for others languages, the correct records are the ones where
        // language_id and real_language_id are the same (the always available
        // bit should not be added)
        $queryOthersLang = "DELETE FROM ezcobj_state_group_language
            WHERE contentobject_state_group_id={$groupId}
            AND real_language_id != {$defaultLangId}
            AND language_id != real_language_id";

        $db->query( $queryDefaultLang );
        $db->query( $queryOthersLang );
        $cli->output( ' Done' );
    }

    $offset += $limit;
}

// the records are now correct, we can change the primary key
$cli->output( 'Changing the primary key in table ezcobj_state_group_language', false );
if ( $db->databaseName() == 'mysql' )
{
    $db->query( 'ALTER TABLE ezcobj_state_group_language DROP PRIMARY KEY' );
    $db->query(
        'ALTER TABLE ezcobj_state_group_language ADD PRIMARY KEY (contentobject_state_group_id, real_language_id)'
    );
}
else if ( $db->databaseName() == 'postgresql' )
{
    $db->query( 'ALTER TABLE ezcobj_state_group_language DROP CONSTRAINT ezcobj_state_group_language_pkey' );
    $db->query(
        'ALTER TABLE ONLY ezcobj_state_group_language
        ADD CONSTRAINT ezcobj_state_group_language_pkey PRIMARY KEY (contentobject_state_group_id, real_language_id)'
    );
}
else if ( $db->databaseName() == 'oracle' )
{
    $db->query( 'ALTER TABLE ezcobj_state_group_language DROP PRIMARY KEY' );
    $db->query(
        'ALTER TABLE ezcobj_state_group_language ADD PRIMARY KEY (contentobject_state_group_id, real_language_id)'
    );
}
$cli->output( ' Done' );


$script->shutdown();
?>
