<?php
/**
 * File containing the ezpModuleDisabled exception.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Exception occuring when a module is disabled.
 *
 * @package kernel
 */
class ezpModuleDisabled extends Exception
{
    /**
     * @var string
     */
    public $moduleName;

    /**
     * Constructor
     *
     * @param string $moduleName
     */
    public function __construct( $moduleName )
    {
        $this->moduleName = $moduleName;
    }
}
