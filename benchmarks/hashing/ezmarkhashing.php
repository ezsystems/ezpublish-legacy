<?php
//
// Definition of eZMarkHashing class
//
// Created on: <18-Feb-2004 11:54:17 >
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

/*! \file ezmarkhashing.php
*/

/*!
  \class eZMarkHashing ezmarkhashing.php
  \brief The class eZMarkHashing does

*/

class eZMarkHashing extends eZBenchmarkCase
{
    /*!
     Constructor
    */
    function eZMarkHashing( $name )
    {
        $this->eZBenchmarkCase( $name );
        $this->addMark( 'markMD5', 'MD5 hash', array( 'repeat_count' => 1000 ) );
        $this->addMark( 'markCRC32', 'CRC32 hash', array( 'repeat_count' => 1000 ) );
    }

    function prime( &$tr )
    {
        $this->Text = implode( '_', array( '240', 'test', 'some_key', 'more' ) );
    }

    function markMD5( &$tr )
    {
        md5( $this->Text );
    }

    function markCRC32( &$tr )
    {
        crc32( $this->Text );
    }

    var $Text;
}

?>
