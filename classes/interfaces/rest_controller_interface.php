<?php
/**
 * File containing the ezpRestControllerInterface interface.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

/**
 * The ezpRestControllerInterface which controller has to implement
 *
 */
interface ezpRestControllerInterface
{
    /**
     * Creates a view object associated with controller
     *
     * @abstract
     * @param ezcMvcResult $result
     * @return ezcMvcView
     */
    public function loadView( ezcMvcResult $result );
}

?>
