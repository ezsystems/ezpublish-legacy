<?php
/**
 * File containing the ezpDatabaseBasedClusterFileHandler interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 * @subpackage clustering
 */

/**
 * This interface describes a database based cluster file handler
 *
 * @package kernel
 *
 * @subpackage clustering
 */
interface ezpDatabaseBasedClusterFileHandler
{
    /**
     * Disconnects the cluster file handler from the database it is connected to
     * @since 4.6
     */
    public function disconnect();
}
?>
