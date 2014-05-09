<?php
/**
 * File containing the ezpRestProviderInterface interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

interface ezpRestProviderInterface
{
    /**
     * Returns registered versioned routes for provider
     *
     * @abstract
     * @return array
     */
    public function getRoutes();

    /**
     * Returns associated with provider view controller
     *
     * @abstract
     * @return ezpRestViewController
     */
    public function getViewController();
}
