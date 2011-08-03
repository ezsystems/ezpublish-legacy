<?php
/**
 * File containing ezpRestAuthenticationStyle class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
abstract class ezpRestAuthenticationStyle
{
    /**
     * Authenticated user
     * @var eZuser
     */
    protected $user;

    /**
     * Current prefix for REST requests, to be used in case of internal redirects
     * @var string
     */
    protected $prefix;

    public function __construct()
    {
        $this->prefix = eZINI::instance( 'rest.ini' )->variable( 'System', 'ApiPrefix' );
    }

    /**
     * @see ezpRestAuthenticationStyleInterface::setUser()
     */
    public function setUser( eZUser $user )
    {
        $this->user = $user;
    }

    /**
     * @see ezpRestAuthenticationStyleInterface::getUser()
     */
    public function getUser()
    {
        return $this->user;
    }
}
?>