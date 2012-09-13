#!/usr/bin/env php
<?php
/**
 * File containing a script responsible for disabling user accounts with suspicious user login (containing < and >).
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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

$rows = $db->arrayQuery( "SELECT DISTINCT login FROM ezuser" );

$cli->output( 'Script found ' . count( $rows ) . ' user accounts' );
$cli->output( 'Login list' );

foreach ( $rows as $index => $row )
{
    $fixedLogin = str_replace( '&amp;', '&', htmlspecialchars( $row['login'], ENT_QUOTES, 'UTF-8' ) );
    if( $fixedLogin === $row['login'] ) {
        continue;
    }

    $i = 0;
    $newLogin = $row['login'];
    // There may be already user with the same login
    if ( eZUser::fetchByName( $newLogin ) instanceof eZUser !== false )
    {
        do
        {
            $newLogin = $fixedLogin . '_' . ++$i;
        }
        while( eZUser::fetchByName( $newLogin ) instanceof eZUser !== false && $i < 100 );
    }

    if ( eZUser::fetchByName( $newLogin ) instanceof eZUser === false )
    {
        $user = eZUser::fetchByName( $row['login'] );
        $user->setAttribute( 'login', $newLogin );
        $user->store();
        // Maybe email should be send to the user about his login change?

        $cli->output( 'Login was changed from "' . $row['login'] . '" to "' . $newLogin . "'" );
    }
}

$cli->output( 'Done.' );

$script->shutdown();

?>
