<?php
/**
 * File containing the ezpMultivariateTestInterface interface.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

interface ezpMultivariateTestHandlerInterface
{
    /**
     * Checks whether multivariate testing is enabled or not
     *
     * @abstract
     * @return bool
     */
    public function isEnabled();

    /**
     * Executes multivariate test scenarios
     *
     * @abstract
     * @param int $nodeID
     * @return int
     */
    public function execute( $nodeID );
}
