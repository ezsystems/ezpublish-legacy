<?php
/**
 * Definition of hashing benchmark
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

$MarkDefinition = array( 'name' => 'Hashing',
                         'marks' => array() );

$MarkDefinition['marks'][] = array( 'name' => 'MD5-CRC32',
                                    'file' => 'ezmarkhashing.php',
                                    'class' => 'eZMarkHashing' );

?>
