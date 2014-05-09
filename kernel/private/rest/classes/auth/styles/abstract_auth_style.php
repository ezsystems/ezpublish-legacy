<?php
/**
 * File containing ezpRestAuthenticationStyle class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
