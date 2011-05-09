#!/usr/bin/env php
<?php
/**
 * File containing the section identifier upgrade script.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package update
 */

require 'autoload.php';

$script = eZScript::instance( array( 'description' => 'eZ Publish section identifier update script. ' .
                                                      'This script will update existing sections with missing identifiers.',
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );
$script->startup();
$options = $script->getOptions( '', '', array( '-q' => 'Quiet mode' ) );

$script->initialize();

$cli = eZCLI::instance();
$trans = eZCharTransform::instance();

// Fetch 50 items per iteration
$limit = 50;
$offset = 0;

do
{
    // Fetch items with empty identifier
    $rows = eZSection::fetchFilteredList( null, $offset, $limit );

    if ( !$rows )
        break;

    foreach ( $rows as $row )
    {
        if ( $row->attribute( 'identifier' ) == '' )
        {
            // Create a new section identifier with NAME_ID pattern
            $name = $row->attribute( 'name' );
            $identifier = $trans->transformByGroup( $name, 'identifier' ) . '_' . $row->attribute( 'id' );

            // Set new section identifier and store it
            $row->setAttribute( 'identifier', $identifier );
            $row->store();

            $cli->output( "Setting identifier '{$identifier}' for section '{$name}'" );
        }
    }
    $offset += $limit;

} while ( true );

$cli->output( "Update has been completed." );

$script->shutdown();

?>
