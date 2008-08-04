#!/usr/bin/env php
<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*!
 \file updatebinaryfile.php
*/

include_once( 'kernel/classes/ezscript.php' );
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php' );
include_once( 'lib/ezfile/classes/ezfile.php' );
include_once( 'kernel/classes/ezclusterfilehandler.php' );

$cli =& eZCLI::instance();

$script =& eZScript::instance( array( 'description' => ( "\nAdds the file extension suffix to the files stored by the binary file datatype\n" .
                                                        "where it is currently missing.\n" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => false ) );

$script->startup();

$options = $script->getOptions( '', '', array() );
$script->initialize();

$limit = 20;
$offset = 0;

$db =& eZDB::instance();

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

            $fh =& eZClusterFileHandler::instance();

            if ( $fh->fileExists( $oldFilePath ) )
            {
                $text = "renamed $fileName to $newFileName";
                $fh->fileMove( $oldFilePath, $newFilePath );
                $db->commit();
            }
            else if ( $fh->fileExists( $newFilePath ) )
            {
                $text = "$fileName was renamed before, changed path in db to $newFileName";
                $db->commit();
            }
            else
            {
                $text = "ERROR, file not found: $oldFilePath";
                $script->iterate( $cli, false, $text );
                $db->rollback();
                continue;
            }
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
