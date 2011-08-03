<?php
/**
 * File containing the eZKernelOperator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZKerneloperator ezkerneloperator.php
  \brief The class eZKernelOperator does handles eZ Publish preferences

*/
class eZKernelOperator
{
    /*!
     Initializes the object with the name $name
    */
    function eZKernelOperator( $name = "ezpreference" )
    {
        $this->Operators = array( $name );
    }

    /*!
      Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'ezpreference' => array( 'name' => array( 'type' => 'string',
                                                                'required' => true,
                                                                'default' => false ) ) );
    }

    function operatorTemplateHints()
    {
        return array( 'ezpreference' => array( 'input' => false,
                                               'output' => true,
                                               'parameters' => 1,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => false,
                                               'element-transformation-func' => 'preferencesTransformation') );
    }

    function preferencesTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                        $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        if ( count( $parameters[0] ) == 0 )
            return false;
        $values = array();
        if ( eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
        {
            $name = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
            $nameText = eZPHPCreator::variableText( $name, 0, 0, false );
        }
        else
        {
            $nameText = '%1%';
            $values[] = $parameters[0];
        }
        return array( eZTemplateNodeTool::createCodePieceElement( "%output% = eZPreferences::value( $nameText );\n",
                                                                  $values ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'ezpreference':
            {
                $name = $namedParameters['name'];
                $value = eZPreferences::value( $name );
                $operatorValue = $value;
            }break;

            default:
            {
                eZDebug::writeError( "Unknown kernel operator: $operatorName" );
            }break;
        }
    }
    public $Operators;
}
?>
