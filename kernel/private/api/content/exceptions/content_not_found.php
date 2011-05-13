<?php
/**
 * File containing the ezpContentNotFoundException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpContentNotFoundException extends ezpContentException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}

?>