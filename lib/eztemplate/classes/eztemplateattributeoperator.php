<?php
//
// Definition of eZTemplateAttributeOperator class
//
// Created on: <01-Mar-2002 13:50:09 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*!
  \class eZTemplateAttributeOperator eztemplateattributeoperator.php
  \ingroup eZTemplateOperators
  \brief Display of variable attributes using operator "attribute"

  This class allows for displaying template variable attributes. The display
  is recursive and the number of levels can be maximized.

  The operator can take three parameters. The first is the maximum number of
  levels to recurse, if blank or omitted the maxium level is infinity.
  The second is the type of display, if set to "text" the output is as pure text
  otherwise as html.
  The third is whether to show variable values or not, default is to not show.

\code
// Example template code

// Display attributes of $myvar
{$myvar|attribute}
// Display 2 levels of $tree
{$tree|attribute(2)}
// Display attributes and values of $item
{$item|attribute(,,show)}
\endcode

*/

class eZTemplateAttributeOperator
{
    /*!
     Initializes the object with the name $name, default is "attribute".
    */
    function eZTemplateAttributeOperator( $name = "attribute" )
    {
        $this->AttributeName = $name;
        $this->Operators = array( $name );
    }

    /*!
     Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function operatorTemplateHints()
    {
        return array( $this->AttributeName => array( 'input' => true,
                                                     'output' => true,
                                                     'parameters' => 3 ) );
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( "show_values" => array( "type" => "string",
                                              "required" => false,
                                              "default" => "" ),
                      "max_val" => array( "type" => "numerical",
                                          "required" => false,
                                          "default" => 2 ),
                      "as_html" => array( "type" => "boolean",
                                          "required" => false,
                                          "default" => true ) );
    }

    /*!
     Display the variable.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $max = $namedParameters["max_val"];
        $as_html = $namedParameters["as_html"];
        $show_values = $namedParameters["show_values"] == "show";
        $txt = "";
        $this->displayVariable( $operatorValue, $as_html, $show_values, $max, 0, $txt );
        if ( $as_html )
        {
            $headers = "<th align=\"left\">Attribute</th>\n<th align=\"left\">Type</th>\n";
            if ( $show_values )
                $headers .= "<th align=\"left\">Value</th>\n";
            $operatorValue = "<table><tr>$headers</tr>\n$txt</table>\n";
        }
        else
            $operatorValue = $txt;
    }

    /*!
     \private
     Helper function for recursive display of attributes.
     $value is the current variable, $as_html is true if display as html,
     $max is the maximum number of levels, $cur_level the current level
     and $txt is the output text which the function adds to.
    */
    function displayVariable( &$value, $as_html, $show_values, $max, $cur_level, &$txt )
    {
        if ( $max !== false and $cur_level >= $max )
            return;
        if ( is_array( $value ) )
        {
            foreach( $value as $key => $item )
            {
                $type = gettype( $item );
                if ( is_object( $item ) )
                    $type .= "[" . get_class( $item ) . "]";

                if ( is_bool( $item ) )
                    $itemValue = $item ? "true" : "false";
                else if ( is_array( $item ) )
                    $itemValue = 'Array(' . count( $item ) . ')';
                else if ( is_string( $item ) )
                    $itemValue = "'" . $item . "'";
                else if ( is_object( $item ) )
                    $itemValue = method_exists( $item, '__toString' ) ? (string)$item : 'Object';
                else
                    $itemValue = $item;
                if ( $as_html )
                {
                    $spacing = str_repeat( ">", $cur_level );
                    if ( $show_values )
                        $txt .= "<tr><td>$spacing$key</td>\n<td>$type</td>\n<td>$itemValue</td>\n</tr>\n";
                    else
                        $txt .= "<tr><td>$spacing$key</td>\n<td>$type</td>\n</tr>\n";
                }
                else
                {
                    $spacing = str_repeat( " ", $cur_level*4 );
                    if ( $show_values )
                        $txt .= "$spacing$key ($type=$item)\n";
                    else
                        $txt .= "$spacing$key ($type)\n";
                }
                $this->displayVariable( $item, $as_html, $show_values, $max, $cur_level + 1, $txt );
            }
        }
        else if ( is_object( $value ) )
        {
            if ( !method_exists( $value, "attributes" ) or
                 !method_exists( $value, "attribute" ) )
                return;
            $attrs = $value->attributes();
            foreach ( $attrs as $key )
            {
                $item = $value->attribute( $key );
                $type = gettype( $item );
                if ( is_object( $item ) )
                    $type .= "[" . get_class( $item ) . "]";

                if ( is_bool( $item ) )
                    $itemValue = $item ? "true" : "false";
                else if ( is_array( $item ) )
                    $itemValue = 'Array(' . count( $item ) . ')';
                else if ( is_numeric( $item ) )
                    $itemValue = $item;
                else if ( is_string( $item ) )
                    $itemValue = "'" . $item . "'";
                else if ( is_object( $item ) )
                    $itemValue = method_exists( $item, '__toString' ) ? (string)$item : 'Object';
                else
                    $itemValue = $item;
                if ( $as_html )
                {
                    $spacing = str_repeat( ">", $cur_level );
                    if ( $show_values )
                        $txt .= "<tr><td>$spacing$key</td>\n<td>$type</td>\n<td>$itemValue</td>\n</tr>\n";
                    else
                        $txt .= "<tr><td>$spacing$key</td>\n<td>$type</td>\n</tr>\n";
                }
                else
                {
                    $spacing = str_repeat( " ", $cur_level*4 );
                    if ( $show_values )
                        $txt .= "$spacing$key ($type=$item)\n";
                    else
                        $txt .= "$spacing$key ($type)\n";
                }
                $this->displayVariable( $item, $as_html, $show_values, $max, $cur_level + 1, $txt );
            }
        }
    }

    /// The array of operators, used for registering operators
    public $Operators;
}

?>
