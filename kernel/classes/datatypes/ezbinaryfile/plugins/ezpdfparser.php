<?php
//
// Definition of eZPDFParser class
//
// Created on: <16-Jun-2003 16:38:22 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

/*!
  \class eZPDFParser ezpdfparser.php
  \ingroup eZKernel
  \brief The class eZPDFParser handles parsing of PDF files and returns the metadata

*/

class eZPDFParser
{
    function &parseFile( $fileName )
    {
        $binaryINI =& eZINI::instance( 'binaryfile.ini' );

        $textExtractionTool = $binaryINI->variable( 'PDFHandlerSettings', 'TextExtractionTool' );

        // save the buffer contents
        $buffer =& ob_get_contents();
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
