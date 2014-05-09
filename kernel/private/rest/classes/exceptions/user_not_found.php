<?php
/**
 * File containing ezpUserNotFoundException class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
