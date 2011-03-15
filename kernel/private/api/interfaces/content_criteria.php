<?php
/**
 * File containing the ezpContentCriteriaInterface interface.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API
 */

/**
 * This interface is the base one when it comes to implementing content query criteria
 * @package API
 */
interface ezpContentCriteriaInterface
{
    /**
     * Return the criteria as a value usable by eZContentObjectTreeNode
     * Temporary method that needs to be refactored
     *
     * @return array
     */
    public function translate();
}
?>