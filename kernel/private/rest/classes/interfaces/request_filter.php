<?php
/**
 * File containing the ezpRestRequestFilterInterface interface
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

interface ezpRestRequestFilterInterface
{
    public function __construct( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request );
    public function filter();
}
