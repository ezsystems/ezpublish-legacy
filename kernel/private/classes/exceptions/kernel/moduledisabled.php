<?php
/**
 * File containing the ezpModuleDisabled exception.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
