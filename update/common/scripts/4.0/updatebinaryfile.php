#!/usr/bin/env php
<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance( array( 'description' => ( "\nAdds the file extension suffix to the files stored by the binary file datatype\n" .
                                                        "where it is currently missing.\n" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( '', '', array() );
$script->initialize();

$limit = 20;
$offset = 0;

$db = eZDB::instance();

$script->setIterationData( '.', '~' );

while ( $binaryFiles = eZPersistentObject::fetchObjectList( eZBinaryFile::definition(), null, null, null, array( 'offset' => $offset, 'limit' => $limit ) ) )
{
    foreach ( $binaryFiles as $binaryFile )
    {
        $fileName = $binaryFile->attribute( 'filename' );

        if ( strpos( $fileName, '.' ) !== false )
        {
            $text = "skipping $fileName, it contains a suffix";
            $script->iterate( $cli, true, $text );
            continue;
        }

        $suffix = eZFile::suffix( $binaryFile->attribute( 'original_filename' ) );

        if ( $suffix )
        {
            $newFileName = $fileName . '.' . $suffix;

            $db->begin();

            $oldFilePath = $binaryFile->attribute( 'filepath' );

            $binaryFile->setAttribute( 'filename', $newFileName );
            $binaryFile->store();

            $newFilePath = $binaryFile->attribute( 'filepath' );

            $file = eZClusterFileHandler::instance( $oldFilePath );
            if ( $file->exists() )
            {
                $text = "renamed $fileName to $newFileName";
                $file->move( $newFilePath );
            }
            else
            {
                $text = "file not found: $oldFilePath";
                $script->iterate( $cli, false, $text );
                $db->rollback();
                continue;
            }

            $db->commit();
        }
        else
        {
            $text = "skipping $fileName, original file name does not contain a suffix";
        }

        $script->iterate( $cli, true, $text );
    }

    $offset += $limit;
}

$script->shutdown();

?>
