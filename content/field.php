<?php
/**
 * File containing the ezpContentField class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API
 */

/**
 * This class handles content fields.
 * A content field currently wraps around an eZContentObjectAttribute
 * @package API
 */
class ezpContentField
{
    public function __construct()
    {

    }

    public static function fromContentObjectAttribute( eZContentObjectAttribute $attribute )
    {
        $field = new self;
        $field->attribute = $attribute;
        return $field;
    }

    /**
     * String representation of the attribute.
     * Uses {eZContentObjectAttribute::toString()}
     */
    public function __toString()
    {
        if ( $this->attribute instanceof eZContentObjectAttribute )
            return $this->attribute->toString();
        else
            return '';
    }

    /**
     * @var eZContentObjectAttribute
     */
    protected $attribute;
}
?>