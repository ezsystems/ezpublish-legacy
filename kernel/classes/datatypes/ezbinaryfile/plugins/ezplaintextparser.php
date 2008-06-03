<?php
//
// Definition of eZPlainTextParser class
//
// Created on: <16-Jun-2003 15:49:43 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZPlainTextParser ezplaintextparser.php
  \ingroup eZKernel
  \brief The class eZPlainTextParser handles parsing of text files and returns the metadata

*/

class eZPlainTextParser
{
    function parseFile( $fileName )
    {
        $metaData = "";
        if ( file_exists( $fileName ) )
        {
            $fp = fopen( $fileName, "r" );
            $metaData = fread( $fp, filesize( $fileName ) );
            fclose( $fp );
        }

        return $metaData;
    }
}

?>
