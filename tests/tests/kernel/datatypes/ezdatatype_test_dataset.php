<?php
/**
 * File containing the  class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package tests
 */
/**
 * Struct used to represent a datatype for testing
 */
class ezpDatatypeTestDataSet extends ezcBaseStruct
{
    /**
     * The fromString value
     *
     * @var string
     */
    public $fromString = '';

    /**
     * Integer data
     *
     * @var int
     */
    public $dataInt = null;

    /**
     * Text data
     *
     * @var string
     */
    public $dataText = '';

    /**
     * Float data
     *
     * @var float
     */
    public $dataFloat = '0';

    /**
     * Content the datatype should return
     * @var mixed
     */
    public $content;

    /**
     * Constructs a new ezpDatatypeTestDataSet with initial values.
     *
     * @param string $fromString
     * @param int $dataInt
     * @param string $dataText
     * @param float $dataFloat
     * @param
     */
    public function __construct( $fromString = '', $dataInt = null, $dataText = '', $dataFloat = '0' )
    {
        $this->fromString = $fromString;
        $this->dataFloat = $dataFloat;
        $this->dataInt = $dataInt;
        $this->dataText = $dataText;
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
     * @return ezpDatatypeTestDataSet
     */
    static public function __set_state( array $array )
    {
        return new self( $array['fromString'], $array['data_int'], $array['data_string'], $array['data_float'] );
    }
}
?>