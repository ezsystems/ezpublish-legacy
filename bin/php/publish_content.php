<?php
/**
 * File containing the publish_content.php bin script
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 * @subpackage content
 */

/**
 * This script, given a queued contentobject_id + version, will resume the publishing operation on it
 * @package kernel
 * @subpackage content
 */
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => 'Asynchronous publishing handler, not meant to be used directly',
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );
$script->startup();

$argumentConfig = '[OBJECT_ID] [VERSION_ID]';
$optionsConfig = '';
$options = $script->getOptions( $optionsConfig, $argumentConfig );

$script->initialize();

if ( count( $options['arguments'] ) != 2 )
    $script->shutdown( 1, 'wrong argument count' );

$objectId = $options['arguments'][0];
$version = $options['arguments'][1];

// eZModule::setGlobalPathList( eZModule::activeModuleRepositories() );
$operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $objectId, 'version' => $version  ) );

if ( isset( $operationResult['status'] ) && $operationResult['status'] == eZModuleOperationInfo::STATUS_CONTINUE )
    $script->shutdown( 0 );
else
{
    $script->shutdown( 2, 'Publishing did not complete' );
    eZLog::write( "Obj: $objectId, ver: $version: " . serialize( $operationResult ) );
}
?>