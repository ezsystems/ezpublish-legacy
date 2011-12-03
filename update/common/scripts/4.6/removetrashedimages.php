#!/usr/bin/env php
<?php
/**
 * File containing the script to cleanup duplicated trashed images.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package update
 * @see Issue 17781
 */

/**
 * Traverses $directory to recursively find "trashed" directories and remove them.
 *
 * @param string $directory
 */
function filesystemCleanup( $directory )
{
    foreach (
        new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator( $directory ),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $entry )
    {
        if ( $entry->getFilename() !== "trashed" )
        {
            continue;
        }

        foreach (
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator( $entry ),
                RecursiveIteratorIterator::CHILD_FIRST
            ) as $trashedDirectoryEntry )
        {
            if ( $trashedDirectoryEntry->isDir() )
            {
                rmdir( $trashedDirectoryEntry );
            }
            else
            {
                unlink( $trashedDirectoryEntry );
            }
        }
        rmdir( $entry );
    }
}

/**
 * Remove content from "trashed" directories under $directory from ezdbfile and
 * ezdbfile_data tables.
 *
 * @param string $directory
 */
function databaseCleanup( $directory )
{
    list( $host, $user, $pass, $db, $port, $socket ) = eZINI::instance( "file.ini" )
        ->variableMulti(
            "ClusteringSettings",
            array(
                "DBHost",
                "DBUser",
                "DBPassword",
                "DBName",
                "DBPort",
                "DBSocket",
            )
        );

    $db = new mysqli( $host, $user, $pass, $db, $port, $socket );

    // Creating a temporary table to hold name_hash to delete from
    // both ezdbfile and ezdbfile_data tables.
    $db->query( "
        CREATE TEMPORARY TABLE ezdbfile_cleanup (
            name_hash VARCHAR(34) NOT NULL,
            PRIMARY KEY (name_hash)
        )"
    );

    // Replacing \ and / by a regex equivalent matching both.
    $imagesDirectory = str_replace(
        array( "\\", "/" ),
        "[\\\\/]",
        // Removing trailing \ and /
        rtrim(
            $directory,
            "\\/"
        )
    );

    // Filling the temporary table with trashed objects to remove
    $db->query( "
        INSERT INTO ezdbfile_cleanup
        SELECT name_hash
        FROM ezdbfile
        WHERE datatype LIKE 'image/%' AND
        name REGEXP '^{$imagesDirectory}[\\\\/].*[\\\\/].*[\\\\/]trashed[\\\\/]'"
    );

    // Removing from both tables at once.
    $db->query( "
        DELETE FROM ezdbfile, ezdbfile_data
        USING ezdbfile
        JOIN ezdbfile_data ON ezdbfile.name_hash = ezdbfile_data.name_hash
        JOIN ezdbfile_cleanup ON ezdbfile.name_hash = ezdbfile_cleanup.name_hash"
    );

    $db->close();
}

/**
 * Removes content from "trashed" directories under $directory from ezdfsfile table
 * and below the mount point.
 *
 * @param string $directory
 */
function dfsCleanup( $directory )
{
    list( $host, $user, $pass, $db, $port, $socket, $mountPoint ) = eZINI::instance( "file.ini" )
        ->variableMulti(
            "eZDFSClusteringSettings",
            array(
                "DBHost",
                "DBUser",
                "DBPassword",
                "DBName",
                "DBPort",
                "DBSocket",
                "MountPointPath",
            )
        );

    $db = new mysqli( $host, $user, $pass, $db, $port, $socket );

    // Replacing \ and / by a regex equivalent matching both.
    $imagesDirectory = str_replace(
        array( "\\", "/" ),
        "[\\\\/]",
        // Removing trailing \ and /
        rtrim(
            $directory,
            "\\/"
        )
    );

    $db->query( "
        DELETE FROM ezdfsfile
        WHERE scope = 'image' AND
        name REGEXP '^{$imagesDirectory}[\\\\/].*[\\\\/].*[\\\\/]trashed[\\\\/]'"
    );

    $db->close();

    array_map( "filesystemCleanup", glob( "$mountPoint/$directory/*" ) );
}

require "autoload.php";

$script = eZScript::instance(
    array(
        "description" => "eZ Publish trashed images sanitizer script (#017781).",
        "use-session" => false,
        "use-modules" => false,
        "use-extensions" => true,
    )
);

$script->startup();
$options = $script->getOptions(
    "[n]",
    "",
    array(
        "-q" => "Quiet mode",
        "n" => "Do not wait"
    )
);

$script->initialize();

$cli = eZCLI::instance();

if ( !isset( $options['n'] ) )
{
    $cli->warning( "This cleanup script will remove any images from trashed objects." );
    $cli->warning( "For more details about this cleanup, take a look at: http://issues.ez.no/17781." );
    $cli->warning();
    $cli->warning( "IT IS YOUR RESPONSABILITY TO TAKE CARE THAT NO ITEMS REMAINS IN TRASH BEFORE RUNNING THIS SCRIPT." );
    $cli->warning();
    $cli->warning( "You have 30 seconds to break the script (press Ctrl-C)." );
    sleep( 30 );
}

$fileHandler = eZINI::instance( "file.ini" )->variable( "ClusteringSettings", "FileHandler" );

$directory = eZSys::varDirectory() . "/storage/images";

switch ( strtolower( $fileHandler ) )
{
    case "ezfsfilehandler":
    case "ezfs2filehandler":
        array_map( "filesystemCleanup", glob( "$directory/*" ) );
        break;

    case "ezdbfilehandler":
        databaseCleanup( $directory );
        break;

    case "ezdfsfilehandler":
        dfsCleanup( $directory );
        break;

    default:
        $cli->error( "Unsupported '$fileHandler' FileHandler." );
}

$script->shutdown();
?>
