<?php
/**
 * File containing the ezpMultivariateTestInterface interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
