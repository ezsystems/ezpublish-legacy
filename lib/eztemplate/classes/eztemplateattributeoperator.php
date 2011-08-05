<?php
/**
 * File containing the eZTemplateAttributeOperator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

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
                      "format" => array( "type" => "string",
                                         "required" => false,
                                         "default" => eZINI::instance( 'template.ini' )->variable( 'AttributeOperator', 'DefaultFormatter' ) ) );
    }

    /*!
     Display the variable.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $max = $namedParameters["max_val"];
        $format = $namedParameters["format"];
        $showValues = $namedParameters["show_values"] == "show";

        $formatter = ezpAttributeOperatorManager::getOutputFormatter( $format );

        $outputString = "";
        $this->displayVariable( $operatorValue, $formatter, $showValues, $max, 0, $outputString );

        if ( $formatter instanceof ezpAttributeOperatorFormatterInterface )
                $operatorValue = $formatter->header( $outputString, $showValues );
    }

    /*!
     \private
     Helper function for recursive display of attributes.
     $value is the current variable, $as_html is true if display as html,
     $max is the maximum number of levels, $cur_level the current level
     and $outputString is the output text which the function adds to.
    */
    function displayVariable( &$value, ezpAttributeOperatorFormatterInterface $formatter, $showValues, $max, $level, &$outputString )
    {
        if ( $max !== false and $level >= $max )
            return;

        if ( is_array( $value ) )
        {
            foreach( $value as $key => $item )
            {
                $outputString .= $formatter->line( $key, $item, $showValues, $level );

                $this->displayVariable( $item, $formatter, $showValues, $max, $level + 1, $txt );
            }
        }
        else if ( is_object( $value ) )
        {
            if ( !method_exists( $value, "attributes" ) or
                 !method_exists( $value, "attribute" ) )
                return;

            foreach ( $value->attributes() as $key )
            {
                $item = $value->attribute( $key );

                $outputString .= $formatter->line( $key, $item, $showValues, $level );

                $this->displayVariable( $item, $formatter, $showValues, $max, $level + 1, $outputString );
            }
        }
    }

    /// The array of operators, used for registering operators
    public $Operators;
}

?>
