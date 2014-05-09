<?php
/**
 * File containing the ezpModuleViewNotFound exception.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Exception occuring when a module/view is not found.
 *
 * @package kernel
 */
class ezpModuleViewNotFound extends Exception
{
    /**
     * @var string
     */
    public $moduleName;

    /**
     * @var string
     */
    public $viewName;

    /**
     * Constructor
     *
     * @param string $moduleName
     */
    public function __construct( $moduleName, $viewName )
    {
        $this->moduleName = $moduleName;
        $this->viewName = $viewName;
    }
}
