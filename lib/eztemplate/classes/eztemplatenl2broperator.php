<?php
/**
 * File containing the eZTemplateNl2BrOperator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateNl2BrOperator eztemplatenl2broperator.php
  \ingroup eZTemplateOperators
\code

\endcode

*/

class eZTemplateNl2BrOperator
{
    public $Nl2brName;
    /**
     * Initializes the object with the name $name, default is "nl2br".
     */
    public function __construct()
    {
        $this->Operators = array( 'nl2br' );
        $this->Nl2brName = 'nl2br';
    }

    /*!
     Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( );
    }

    function operatorTemplateHints()
    {
        return array( $this->Nl2brName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => true,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => true,
                                                 'element-transformation-func' => 'nl2brTransformation') );
    }

    function nl2brTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                  $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( ( count( $parameters ) != 1) )
        {
            return false;
        }
        $newElements = array();

        $values[] = $parameters[0];
        $code = "%output% = nl2br( %1% );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     Display the variable.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
    {
        $operatorValue = str_replace( "\n",
                                      "<br />",
                                      $operatorValue );
    }

    /// The array of operators, used for registering operators
    public $Operators;
}

?>
