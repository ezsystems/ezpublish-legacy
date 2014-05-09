<?php
/**
 * File containing ezpAttributeOperatorFormatter base class definition
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpAttributeOperatorFormatter
{
    /**
     * Returns type for given item
     *
     * @param mixed $item
     * @return string
     */
    protected function getType( $item )
    {
        $type = gettype( $item );
        if ( is_object( $item ) )
            $type .= "[" . get_class( $item ) . "]";

        return $type;
    }

    /**
     * Returns value for given item
     *
     * @param mixed $item
     * @return string
     */
    protected function getValue( $item )
    {
        if ( is_bool( $item ) )
            $value = $item ? "true" : "false";
        else if ( is_array( $item ) )
            $value = 'Array(' . count( $item ) . ')';
        else if ( is_numeric( $item ) )
            $value = $item;
        else if ( is_string( $item ) )
            $value = "'" . $item . "'";
        else if ( is_object( $item ) )
            $value = method_exists( $item, '__toString' ) ? (string)$item : 'Object';
        else
            $value = $item;

        return $value;
    }

    /**
     * @see ezpAttributeOperatorFormatterInterface::exportScalar()
     */
    public function exportScalar( $value )
    {
        return var_export( $value, true );
    }
}
