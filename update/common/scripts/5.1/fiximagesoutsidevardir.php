<?php
/**
 * File containing the fiximagesoutsidevardir.php script
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

/**
 * This update script will fix references to images that aren't part of the current vardir.
 *
 * This may for instance occur when the VarDir setting is changed. This script will rename the files, and update the
 * database references to the new path
 */

require_once 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "Fixes references to images located outside VarDir.\n\n" .
            "This may for instance occur when the VarDir setting is changed after a project has been started\n" .
            "\n" .
            "The legacy kernel will accept this, but the new Public API will not.",
        'use-session' => true,
        'use-modules' => false,
        'use-extensions' => true
    )
);
$script->startup();

$options = $script->getOptions( "[dry-run]", "", array( 'n' => 'Dry run' ) );
$optDryRun = (bool)$options['dry-run'];

$script->initialize();

$db = eZDB::instance();

$clusterHandler = eZClusterFileHandler::instance();

$varDir = eZINI::instance( 'site.ini' )->variable( 'FileSettings', 'VarDir' );
if ( substr( $varDir, 0, -1 ) !== DIRECTORY_SEPARATOR )
{
    $varDir .= DIRECTORY_SEPARATOR;
}
$cli->output( "Searching database " . $db->DB . " for images that don't start with $varDir" );
if ( $optDryRun )
{
    $cli->output( "*DRY RUN MODE*" );
}
$cli->output();
$rows = $db->arrayQuery( "SELECT * FROM ezimagefile WHERE filepath NOT LIKE '{$varDir}%'" );

if ( !count( $rows ) )
{
    $cli->output( "No files outside '{$varDir}' found in the database, terminating" );
    $script->shutdown();
    eZExecution::cleanExit();
}

foreach ( $rows as $row )
{
    $moveFile = true;

    $filePath = $row['filepath'];
    $imageAttributeId = $row['contentobject_attribute_id'];
    $cli->output( "- $filePath" );

    // Detect the part before '/images/', e.g. the varDir the file was created with
    $relativePath = substr(
        $filePath,
        strpos( $filePath, 'storage/images/' )
    );

    $newPath = $varDir . $relativePath;

    if ( !$clusterHandler->fileExists( $filePath ) )
    {
        if ( $clusterHandler->fileExists( $newPath ) )
        {
            $moveFile = false;
            $cli->output( "  File is already in the correct directory, updating references" );
        }
        else
        {
            $cli->output( "  File doesn't exist, skipping" );
            continue;
        }
    }
    else
    {
        $cli->output( "  Moving file to $newPath" );
    }

    if ( !$optDryRun && $moveFile )
    {
        $clusterHandler->fileMove( $filePath, $newPath );
        $db->query( "UPDATE ezimagefile SET filepath = '$newPath' WHERE contentobject_attribute_id = $imageAttributeId" );
    }

    if ( !isset( $renamedFiles[$imageAttributeId] ) )
    {
        $renamedFiles[$imageAttributeId] = array();
    }
    $renamedFiles[$imageAttributeId][$filePath] = $newPath;
}

foreach ( $renamedFiles as $attributeId => $files )
{
    $attributeObjects = eZContentObjectAttribute::fetchObjectList(
        eZContentObjectAttribute::definition(),
        null,
        array( 'id' => $attributeId )
    );

    /** @var eZContentObjectAttribute $attributeObject */
    foreach ( $attributeObjects as $attributeObject )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        if ( !$dom->loadXML( $attributeObject->attribute( 'data_text' ) ) )
            continue;

        foreach ( $dom->getElementsByTagName( 'ezimage' ) as $ezimageNode )
        {
            // Update main image
            $oldPath = $ezimageNode->getAttribute( 'url' );
            if ( isset( $files[$oldPath] ) )
            {
                $ezimageNode->setAttribute( 'url', $files[$oldPath] );
                $ezimageNode->setAttribute( 'dirpath', dirname( $files[$oldPath] ) );
            }

            // Update aliases
            foreach ( $ezimageNode->getElementsByTagName( 'alias' ) as $ezimageAlias )
            {
                $oldPath = $ezimageAlias->getAttribute( 'url' );
                if ( isset( $files[$oldPath] ) )
                {
                    $ezimageAlias->setAttribute( 'url', $files[$oldPath] );
                    $ezimageAlias->setAttribute( 'dirpath', dirname( $files[$oldPath] ) );
                }
            }
        }
        $attributeObject->setAttribute( 'data_text', $dom->saveXML() );
        if ( !$optDryRun )
        {
            $attributeObject->store();
        }
    }
}

eZContentCacheManager::clearAllContentCache();
$script->shutdown();
