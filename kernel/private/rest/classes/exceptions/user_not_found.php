<?php
/**
 * File containing ezpUserNotFoundException class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
class ezpUserNotFoundException extends ezpRestException
{
    public function __construct( $userID )
    {
        eZLog::write( __METHOD__ . " : Provided user #$userID was not found and could not be logged in", 'error.log' );
        parent::__construct( 'Provided user was not found' );
    }
}
?>