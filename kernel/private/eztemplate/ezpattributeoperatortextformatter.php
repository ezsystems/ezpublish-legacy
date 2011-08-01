<?php
/**
 * File containing ezpAttributeOperatorTextFormatter class definition
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
 
class ezpAttributeOperatorTextFormatter extends ezpAttributeOperatorFormatter implements ezpAttributeOperatorFormatterInterface
{

    /**
     * Formats header for the 'attribute' template operator output
     *
     * @param string $value
     * @param bool $showValues
     * @return string
     */
    public function header( $value, $showValues )
    {
        return $value;
    }

    /**
     * Formats single line for the 'attribute' template operator output
     *
     * @param string $key
     * @param mixed $item
     * @param bool $showValues
     * @param int $level
     * @return string
     */
    public function line( $key, $item, $showValues, $level )
    {
        $type = $this->getType( $item );
        $value = $this->getValue( $item );
        $spacing = str_repeat( " ", $level * 4 );

        if ( $showValues )
            $output = "{$spacing}{$key} ({$type}={$value})\n";
        else
            $output = "{$spacing}{$key} ({$type})\n";

        return $output;
    }
}
