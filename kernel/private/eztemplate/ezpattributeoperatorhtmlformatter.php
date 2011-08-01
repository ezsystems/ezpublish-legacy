<?php
/**
 * File containing ezpAttributeOperatorHTMLFormatter class definition
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
 
class ezpAttributeOperatorHTMLFormatter extends ezpAttributeOperatorFormatter implements ezpAttributeOperatorFormatterInterface
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
        $headers = "<th align=\"left\">Attribute</th>\n<th align=\"left\">Type</th>\n";
        if ( $showValues )
            $headers .= "<th align=\"left\">Value</th>\n";

        return "<table><tr>{$headers}</tr>\n{$value}</table>\n";
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
        $spacing = str_repeat( "&gt;", $level );

        if ( $showValues )
            $output = "<tr><td>{$spacing}{$key}</td>\n<td>{$type}</td>\n<td>{$value}</td>\n</tr>\n";
        else
            $output = "<tr><td>{$spacing}{$key}</td>\n<td>{$type}</td>\n</tr>\n";

        return $output;
    }
}
