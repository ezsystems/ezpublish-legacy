<?php
//
// Definition of eZWordParser class
//
// Created on: <17-Jun-2003 09:33:14 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
  \class eZWordParser ezwordparser.php
  \ingroup eZKernel
  \brief The class eZWordParser handles parsing of Word files and returns the metadata

*/

class eZWordParser
{
    function parseFile( $fileName )
    {
        $binaryINI = eZINI::instance( 'binaryfile.ini' );

        $textExtractionTool = $binaryINI->variable( 'WordHandlerSettings', 'TextExtractionTool' );

        $tmpName = "var/cache/" . md5( time() ) . '.txt';
        $handle = fopen( $tmpName, "w" );
        fclose( $handle );
        chmod( $tmpName, 0777 );

        exec( "$textExtractionTool $fileName > $tmpName", $ret );

        $metaData = "";
        if ( file_exists( $tmpName ) )
        {
            $fp = fopen( $tmpName, "r" );
            $metaData = fread( $fp, filesize( $tmpName ) );
            $metaData = strip_tags( $metaData );
            fclose( $fp );
            unlink( $tmpName );
        }

        return $metaData;
    }
}

?>
