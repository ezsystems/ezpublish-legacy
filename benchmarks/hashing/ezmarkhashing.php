<?php
//
// Definition of eZMarkHashing class
//
// Created on: <18-Feb-2004 11:54:17 >
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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
        //include_once( 'lib/ezutils/classes/ezsys.php' );
        eZSys::ezcrc32( $this->Text );
    }

    var $Text;
}

?>
