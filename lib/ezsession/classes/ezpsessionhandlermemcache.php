<?php
/**
 * File containing PHP session handler
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/** Memcache session handler class
 *
 * @package lib
 * @subpackage ezsession
 */
class ezpSessionHandlerMemcache extends ezpSessionHandlerPHP
{
    const EXPIRE_LIMIT = 2592000;

    /**
     * reimp (Does nothing, lets php handle sessions)
     * Does set gc_maxlifetime to SessionTimeout to make sure timeout works like DB handler
     */
    public function setSaveHandler()
    {
        $ini = eZINI::instance();
        if ( $ini->hasVariable( 'Session', 'SessionTimeout' ) && $ini->variable( 'Session', 'SessionTimeout' ) )
        {
            $sessionTimeout = $ini->variable( 'Session', 'SessionTimeout' );
            if ( $sessionTimeout > self::EXPIRE_LIMIT )
            {
                $sessionTimeout += time();
            }

            ini_set( "session.gc_maxlifetime", $sessionTimeout );
        }
        // make sure eZUser does not update lastVisit on every request and only on login
        $GLOBALS['eZSessionIdleTime'] = 0;
        return true;
    }
}
