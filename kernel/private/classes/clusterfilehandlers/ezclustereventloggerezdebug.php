<?php
/**
 * File containing the eZClusterEventLoggerEzdebug class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
