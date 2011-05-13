<?php
/**
 * File containing the eZMarkHashing class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
        eZSys::ezcrc32( $this->Text );
    }

    var $Text;
}

?>
