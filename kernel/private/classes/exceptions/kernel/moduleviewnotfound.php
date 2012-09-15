<?php
/**
 * File containing the ezpModuleViewNotFound exception.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
