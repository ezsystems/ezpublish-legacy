<?php
/**
 * File containing the eZClusterEventLoggerEzdebug class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

/**
 * Logger using eZDebugSetting.
 * Condition is 'kernel-clustering'
 */
class eZClusterEventLoggerEzdebug implements eZClusterEventLogger
{
    /**
     * Logs $errMsg.
     *
     * @param string $errMsg Error message to be logged
     * @param string $context Context where the error occurred
     * @return void
     */
    public function logError( $errMsg, $context = null )
    {
        eZDebugSetting::writeError( 'kernel-clustering', $errMsg, $context );
    }
}
