<?php
/**
 * File containing the ezpResourceError class.
 *
 * @package
 * @version //autogen//
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 *
 * <code>
 * </code>
 *
 * @package
 * @version //autogen//
 */

class ezpResourceError extends Exception
{
    /**
     * An array containing the files which were tried
     *
     * @var array(string=>string)
     */
    public $triedFiles = array();

    /**
     * The name of the resource that was requested
     *
     * @var string
     */
    public $name;

    /**
     */
    public function __construct( $msg, $name, $triedFiles )
    {
        Exception::__construct( $msg );
        $this->name = $name;
        $this->triedFiles = $triedFiles;
    }
}

?>
