<?php
/**
 * File containing the eZTemplateAttributeOperator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateAttributeOperator eztemplateattributeoperator.php
  \ingroup eZTemplateOperators
  \brief Display of variable attributes using operator "attribute" or dumps a variable using the operator "dump"

  This class allows for displaying template variable attributes. The display
  is recursive and the number of levels can be maximized.

  The "attribute" operator can take three parameters. The first is whether to show
  variable values or not, default is to not show. The second is the maximum number
  of levels to recurse, if blank or omitted the maxium level is 2.
  The third is the type of display, if set to "text" the output is as pure text
  otherwise as html. The default output is configured in template.ini.

  The "dump" operator does exactly what the "attribute" operator does with following
  exceptions:
  - The default maximum number of levels to recurse is 1
  - By default, it shows the values of arrays and object properties
  - it can handle primitive variables and NULL values

\code
// Example template code for operator 'attribute'

// Display attributes of $myvar
{$myvar|attribute}
// Display 2 levels of $tree
{$tree|attribute(show,2)}
// Display attributes and values of $item
{$item|attribute(show)}


// Example template code for operator 'dump'

// Dumps out $myvar - can handle primitive variables, arrays and objects.
// By default it shows array values or object properties.
{$myvar|dump()}

// Show 2 levels of $tree (default is 1)
{$tree|dump(show, 2)}

\endcode

*/

class eZTemplateAttributeOperator
{
    /*!
     Initializes the object with the name $attributeName, default is "attribute" and $dumpName, default is 'dump'
    */
    function eZTemplateAttributeOperator( $attributeName = 'attribute',
                                          $dumpName = 'dump' )
    {
        $this->AttributeName = $attributeName;
        $this->DumpName = $dumpName;
        $this->Operators = array( $attributeName, $dumpName );
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
                                                     'parameters' => 3 ),
                      $this->DumpName => array( 'input' => true,
                                                'outpout' => true,
                                                'parameters' => 3 ) );
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( $this->AttributeName => array( "show_values" => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => "" ),
                                                     "max_val"     => array( "type" => "numerical",
                                                                             "required" => false,
                                                                             "default" => 2 ),
                                                     "format"      => array( "type" => "boolean",
                                                                             "required" => false,
                                                                             "default" => eZINI::instance( 'template.ini' )->variable( 'AttributeOperator', 'DefaultFormatter' ) ) ),
                      $this->DumpName =>      array( "show_values" => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => "show" ),
                                                     "max_val"     => array( "type" => "numerical",
                                                                             "required" => false,
                                                                             "default" => 1 ),
                                                     "format"      => array( "type" => "boolean",
                                                                             "required" => false,
                                                                             "default" => eZINI::instance( 'template.ini' )->variable( 'AttributeOperator', 'DefaultFormatter' ) ) ) );
    }

   /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     Display the variable.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
    {
        $showValues = $namedParameters[ 'show_values' ] == 'show';
        $max        = $namedParameters[ 'max_val' ];
        $format     = $namedParameters[ 'format' ];

        $formatter = ezpAttributeOperatorManager::getOutputFormatter( $format );

        // check for an object or an array that is not empty
        if ( is_object( $operatorValue ) || ( is_array( $operatorValue ) && !empty( $operatorValue ) ) )
        {
            $outputString = "";
            $this->displayVariable( $operatorValue, $formatter, $showValues, $max, 0, $outputString );

            if ( $formatter instanceof ezpAttributeOperatorFormatterInterface )
                $operatorValue = $formatter->header( $outputString, $showValues );
        }
        else if ( $formatter instanceof ezpAttributeOperatorFormatterInterface )
        {
            $operatorValue = $formatter->exportScalar( $operatorValue );
        }
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
            foreach ( $value as $key => $item )
            {
                $outputString .= $formatter->line( $key, $item, $showValues, $level );

                $this->displayVariable( $item, $formatter, $showValues, $max, $level + 1, $outputString );
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
