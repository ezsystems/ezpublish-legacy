<?php
/**
 * File containing the ezpRestPreRoutingFilterInterface interface
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

interface ezpRestPreRoutingFilterInterface
{
    public function __construct( ezcMvcRequest $request );
    public function filter();
}
