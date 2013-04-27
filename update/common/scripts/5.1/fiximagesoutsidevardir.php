<?php
/**
 * File containing the fiximagesoutsidevardir.php script
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

/**
 * This update script will fix references to images that aren't part of the current vardir.
 *
 * This may for instance occur when the VarDir setting is changed. This script will rename the files, and update the
 * database references to the new path
 */

require 'autoload.php';

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
    $cli->output( "No files not starting with '{$varDir}' was found in the database" );
    $script->shutdown();
    eZExecution::cleanExit();
}

foreach ( $rows as $row )
{
    $filePath = $row['filepath'];
    $imageAttributeId = $row['contentobject_attribute_id'];
    $cli->output( "- $filePath" );

    // Detect the part before '/images/', e.g. the varDir the file was created with
    $relativePath = substr(
        $filePath,
        strpos( $filePath, 'storage/images/' )
    );

    if ( !$clusterHandler->fileExists( $filePath ) )
    {
        $cli->output( "  File doesn't exist, skipping" );
        continue;
    }

    $newPath = $varDir . $relativePath;
    $cli->output( "  Moving file to $newPath" );
    if ( !$optDryRun )
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

foreach( $renamedFiles as $attributeId => $files )
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

/**
 * Updates the image node $node that references $oldPath to reference $newPath (url & dirpath attributes)
 */
function updateDomImage( DOMNode $node, $oldPath, $newPath )
{
    if ( $node->getAttribute( 'url' ) == $oldPath )
    {
        $node->setAttribute( 'url', $newPath );
        $node->setAttribute( 'dirpath', dirname( $newPath ) );
        return true;
    }
    return false;
}
