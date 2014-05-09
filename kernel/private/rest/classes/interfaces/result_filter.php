<?php
/**
 * File containing the ezpRestResultFilterInterface interface
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

interface ezpRestResultFilterInterface
{
    public function __construct( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result );
    public function filter();
}
