<?php
/**
 * File containing the eZTemplatePHPOperator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplatePHPOperator eztemplatephpoperator.php
  \ingroup eZTemplateOperators
  \brief Makes it easy to add php functions as operators

  This class makes it easy to add existing PHP functions as template operators.
  It maps a template operator to a PHP function, the function must take one
  parameter and return the result.
  The redirection is done by supplying an associative array to the class,
  each key is the operatorname and the value is the PHP function name.

  Example:
\code
$tpl->registerOperators( new eZTemplatePHPOperator( array( "upcase" => "strtoupper",
                                                           "reverse" => "strrev" ) ) );
\endcode
*/

class eZTemplatePHPOperator
{
    /*!
     Initializes the object with the redirection array.
    */
    function eZTemplatePHPOperator( $php_names )
    {
        if ( !is_array( $php_names ) )
            $php_names = array( $php_names );
        $this->PHPNames = $php_names;
        reset( $php_names );
        while ( list( $key, $val ) = each( $php_names ) )
        {
            $this->Operators[] = $key;
        }
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
        $hints = array();
        foreach ( array_keys( $this->PHPNames ) as $name )
        {
            $hints[$name] = array( 'input' => true,
                                   'output' => true,
                                   'parameters' => false,
                                   'element-transformation' => true,
                                   'transform-parameters' => true,
                                   'input-as-parameter' => true,
                                   'element-transformation-func' => 'phpOperatorTransformation');
        }
        return $hints;
    }

    function phpOperatorTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                        $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( !isset( $parameters[0] ) )
        {
            return false;
        }
        $newElements = array();
        $phpname = $this->PHPNames[$operatorName];

        $values[] = $parameters[0];
        $code = "%output% = $phpname( %1% );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     Executes the PHP function for the operator $op_name.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters, $placement )
    {
        $phpname = $this->PHPNames[$operatorName];
        if ( $value !== null )
            $operand = $value;
        else
            $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
        $value = $phpname( $operand );
    }

    /// The array of operators, used for registering operators
    public $Operators;
    /// The associative array of operator/php function redirection
    public $PHPNames;
}

?>
