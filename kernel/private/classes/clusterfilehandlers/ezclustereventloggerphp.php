<?php
/**
 * File containing the eZClusterEventLoggerPhp class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

/**
 * Logger using PHP log
 */
class eZClusterEventLoggerPhp implements eZClusterEventLogger
{
    /**
     * Logs $errMsg in PHP error log
     *
     * @param string $errMsg Error message to be logged
     * @param string $context Context where the error occurred
     * @return void
     */
    public function logError( $errMsg, $context = null )
    {
        $errMsg = $context != null ? $errMsg . ' - ' . $context : $errMsg;
        error_log( $errMsg );
    }
}
