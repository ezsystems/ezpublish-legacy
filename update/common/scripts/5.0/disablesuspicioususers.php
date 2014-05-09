#!/usr/bin/env php
<?php
/**
 * File containing a script responsible for disabling user accounts with suspicious user login (containing < and >).
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
        'description' => 'Script responsible for disabling user accounts with suspicious user login (containing < and >)',
        'use-session' => false,
        'use-modules' => false,
        'use-extensions' => true
    )
);
$options = $script->getOptions( '[disable]', '', array( '-q' => 'Quiet mode',
                                                        'disable' => 'Disabling user accounts with suspicious user login.' ) );

$cli = eZCLI::instance();

$script->initialize();
$script->startup();

$db = eZDB::instance();

$rows = $db->arrayQuery( "SELECT DISTINCT login FROM ezuser, ezuser_setting
                            WHERE ( ezuser.login LIKE '%<%' OR ezuser.login LIKE '%>%' )
                                AND ezuser.contentobject_id = ezuser_setting.user_id
                                    AND ezuser_setting.is_enabled = '1'" );

$cli->output( 'Script found ' . count( $rows ) . ' user accounts with suspicious login.' );
$cli->output( 'Login list' );

foreach( $rows as $index => $row )
{
    $user = eZUser::fetchByName( $row['login'] );
    $userSetting = eZUserSetting::fetch( $user->attribute( 'contentobject_id' ) );

    $cli->output( $index + 1 . '. '. $row['login'] );

    if ( $options['disable'] )
    {
        $userSetting->setAttribute( 'is_enabled', 0 );
        $userSetting->store();

        $cli->output( 'Disabled user account for login "' . $row['login'] . '" with ID "' . $user->attribute( 'contentobject_id' ) . "'" );
    }
}

$cli->output( 'Done.' );

$script->shutdown();

?>
