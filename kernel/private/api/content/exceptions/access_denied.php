<?php
/**
 * File containing the ezpContentAccessDeniedException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpContentAccessDeniedException extends ezpContentException
{
    /**
     * @param int $objectID ContentObjectID
     */
    public function __construct( $objectID )
    {
        $userID = eZUser::currentUserID();
        $message = ezpI18n::tr( 'design/standard/error/kernel', 'Access denied' ) . '. ' .
                   ezpI18n::tr( 'design/standard/error/kernel', 'You do not have permission to access this area.');

        eZLog::write( "Access denied to content object #$objectID for user #$userID", 'error.log' );
        parent::__construct( $message );
    }
}
?>