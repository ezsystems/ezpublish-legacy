<?php
/**
 * File containing ezpAutoloadFileFindContext class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Struct which defines the information collected by the file walker for locating files.
 *
 * @package kernel
 */
class ezpAutoloadFileFindContext extends ezcBaseStruct
{
    /**
     * The list of files
     *
     * @var array(string)
     */
    public $elements;

    /**
     * The number of files
     *
     * @var int
     */
    public $count;

    /**
     * The autoload generator
     *
     * @var eZAutoloadGenerator
     */
    public $generator;

    /**
     * Constructs a new ezpAutoloadFileFindContext with initial values.
     *
     * @param array(string) $elements
     * @param int $count
     * @param int $size
     */
    public function __construct( $elements = array(), $count = 0, $gen = null )
    {
        $this->elements = $elements;
        $this->count = $count;
        $this->generator = $gen;
    }

    /**
     * Returns a new instance of this class with the data specified by $array.
     *
     * $array contains all the data members of this class in the form:
     * array('member_name'=>value).
     *
     * __set_state makes this class exportable with var_export.
     * var_export() generates code, that calls this method when it
     * is parsed with PHP.
     *
     * @param array(string=>mixed) $array
     * @return ezpAutoloadFileFindContext
     */
    static public function __set_state( array $array )
    {
        return new ezpAutoloadFileFindContext( $array['elements'], $array['count'], $array['generator'] );
    }
}
?>
