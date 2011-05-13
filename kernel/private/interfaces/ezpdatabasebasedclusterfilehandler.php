<?php
/**
 * File containing the ezpDatabaseBasedClusterFileHandler interface.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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