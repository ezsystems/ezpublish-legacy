#!/usr/bin/env php
<?php
/**
 * File containing the ezapplytemplate.php script.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Code Template Generator\n\n" .
                                                        "This will apply any template blocks it finds in files\n" .
                                                        "and writes back the new file\n" .
                                                        "\n" .
                                                        "The return code is set to 0 if no changes occured, 1 if a file is changed\n" .
                                                        "or 2 if an error occurs" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[a|all][check-only]",
                                "[file]",
                                array( 'all' => 'Go trough all files defined in codetemplate.ini',
                                       'check-only' => 'Will only check if the files will be changed or have errors' ) );
$script->initialize();

if ( !$options['all'] and count( $options['arguments'] ) < 1 )
{
    $cli->error( "Need at least one file" );
    $script->shutdown( 1 );
}

$hasErrors = false;
$hasModified = false;

$tpl = new eZCodeTemplate();

if ( $options['all'] )
{
    $files = $tpl->allCodeFiles();
}
else
{
    $files = $options['arguments'];
}

foreach ( $files as $file )
{
    $status = $tpl->apply( $file, $options['check-only'] );
    if ( $status == eZCodeTemplate::STATUS_OK )
    {
        $cli->output( "Updated " . $cli->stylize( 'file', $file ) );
        $hasModified = true;
    }
    else if ( $status == eZCodeTemplate::STATUS_NO_CHANGE )
    {
        $cli->output( "No change in " . $cli->stylize( 'file', $file ) );
    }
    else if ( $status == eZCodeTemplate::STATUS_FAILED )
    {
        $cli->output( "Template errors for " . $cli->stylize( 'file', $file ) );
        $hasErrors = true;
    }
}

if ( $hasErrors )
    $script->shutdown( 2 );
else if ( $hasModified )
    $script->shutdown( 1 );

$script->shutdown();

?>
