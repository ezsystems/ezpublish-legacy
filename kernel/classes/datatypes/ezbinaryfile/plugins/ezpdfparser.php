<?php
//
// Definition of eZPDFParser class
//
// Created on: <16-Jun-2003 16:38:22 bf>
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
  \class eZPDFParser ezpdfparser.php
  \ingroup eZKernel
  \brief The class eZPDFParser handles parsing of PDF files and returns the metadata

*/

class eZPDFParser
{
    function parseFile( $fileName )
    {
        $binaryINI = eZINI::instance( 'binaryfile.ini' );

        $textExtractionTool = $binaryINI->variable( 'PDFHandlerSettings', 'TextExtractionTool' );

        // save the buffer contents
        $buffer = ob_get_contents();
        ob_end_clean();

        // fetch the module printout
        ob_start();
        passthru( "$textExtractionTool $fileName" );
        $metaData = ob_get_contents();
        ob_end_clean();

        // fill the buffer with the old values
        ob_start();
        print( $buffer );

        return $metaData;
    }
}

?>
