<?php
/**
 * File containing the eZTemplateExecuteOperator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateExecuteOperator eztemplateexecuteoperator.php
  \brief The class eZTemplateExecuteOperator does

*/

class eZTemplateExecuteOperator
{
    /**
     * Constructor
     *
     * @param string $fetchName
     * @param string $fetchAliasName
     */
    public function __construct( $fetchName = 'fetch', $fetchAliasName = 'fetch_alias' )
    {
        $this->Operators = array( $fetchName, $fetchAliasName );
        $this->Fetch = $fetchName;
        $this->FetchAlias = $fetchAliasName;
    }

    /*!
     Returns the operators in this class.
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

    function operatorTemplateHints()
    {
        return array( $this->Fetch => array( 'input' => false,
                                             'output' => true,
                                             'parameters' => true,
                                             'element-transformation' => true,
                                             'transform-parameters' => true,
                                             'element-transformation-func' => 'fetchTransform' ),
                      $this->FetchAlias => array( 'input' => false,
                                                  'output' => true,
                                                  'parameters' => true,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'element-transformation-func' => 'fetchTransform' )
                      );
    }

    function fetchTransform( $operatorName, &$node, $tpl, &$resourceData,
                             $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $parameterTranslation = false;
        $constParameters = array();

        if ( $operatorName == $this->Fetch )
        {
            if ( !eZTemplateNodeTool::isConstantElement( $parameters[0] ) ||
                 !eZTemplateNodeTool::isConstantElement( $parameters[1] ) )
            {
                return false;
            }

            $moduleName = eZTemplateNodeTool::elementConstantValue( $parameters[0] );
            $functionName = eZTemplateNodeTool::elementConstantValue( $parameters[1] );

            $moduleFunctionInfo = eZFunctionHandler::moduleFunctionInfo( $moduleName );
            if ( !$moduleFunctionInfo->isValid() )
            {
                eZDebug::writeError( "Cannot execute  module '$moduleName', no module found", __METHOD__ );
                return array();
            }
            $fetchParameters = array();
            if ( isset( $parameters[2] ) )
                $fetchParameters =  $parameters[2];
        }
        else if ( $operatorName == $this->FetchAlias )
        {
            if ( !eZTemplateNodeTool::isConstantElement( $parameters[0] ) )
            {
                return false;
            }

            $aliasFunctionName = eZTemplateNodeTool::elementConstantValue( $parameters[0] );

            $aliasSettings = eZINI::instance( 'fetchalias.ini' );
            if ( $aliasSettings->hasSection( $aliasFunctionName ) )
            {
                $moduleFunctionInfo = eZFunctionHandler::moduleFunctionInfo( $aliasSettings->variable( $aliasFunctionName, 'Module' ) );
                if ( !$moduleFunctionInfo->isValid() )
                {
                    eZDebug::writeError( "Cannot execute function '$aliasFunctionName' in module '$moduleName', no valid data", __METHOD__ );
                    return array();
                }

                $functionName = $aliasSettings->variable( $aliasFunctionName, 'FunctionName' );

                $functionArray = array();
                if ( $aliasSettings->hasVariable( $aliasFunctionName, 'Parameter' ) )
                {
                    $parameterTranslation = $aliasSettings->variable( $aliasFunctionName, 'Parameter' );
                }

                if ( $aliasSettings->hasVariable( $aliasFunctionName, 'Constant' ) )
                {
                    $constantParameterArray = $aliasSettings->variable( $aliasFunctionName, 'Constant' );
                    if ( is_array( $constantParameterArray ) )
                    {
                        foreach ( array_keys( $constantParameterArray ) as $constKey )
                        {
                            if ( $moduleFunctionInfo->isParameterArray( $functionName, $constKey ) )
                                $constParameters[$constKey] = explode( ';', $constantParameterArray[$constKey] );
                            else
                                $constParameters[$constKey] = $constantParameterArray[$constKey];
                        }
                    }
                }
            }
            else
            {
                $placement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
                $tpl->warning( 'fetch_alias', "Fetch alias '$aliasFunctionName' is not defined in fetchalias.ini", $placement );
                return array();
            }

            $fetchParameters = array();
            if ( isset( $parameters[1] ) )
                $fetchParameters = $parameters[1];
        }
        else
        {
            return false;
        }

        $functionDefinition = $moduleFunctionInfo->preExecute( $functionName );
        if ( $functionDefinition === false )
        {
            return false;
        }

        $isDynamic = false;
        $isVariable = false;
        if ( eZTemplateNodeTool::isConstantElement( $fetchParameters ) )
        {
            $staticParameters = eZTemplateNodeTool::elementConstantValue( $fetchParameters );
            $functionKeys = array_keys( $staticParameters );
        }
        else if ( eZTemplateNodeTool::isDynamicArrayElement( $fetchParameters ) )
        {
            $isDynamic = true;
            $dynamicParameters = eZTemplateNodeTool::elementDynamicArray( $fetchParameters );
            $functionKeys = eZTemplateNodeTool::elementDynamicArrayKeys( $fetchParameters );
        }
        else if ( eZTemplateNodeTool::isVariableElement( $fetchParameters ) or
                  eZTemplateNodeTool::isInternalCodePiece( $fetchParameters ) )
        {
            $isVariable = true;
        }
        else
        {
            $functionKeys = array();
        }

        $paramCount = 0;
        $values = array();
        if ( $isVariable )
        {
            $values[] = $fetchParameters;

            $parametersCode = 'array( ';
            foreach( $functionDefinition['parameters'] as $parameterDefinition )
            {
                if ( $paramCount != 0 )
                {
                    $parametersCode .= ',' . "\n";
                }
                ++$paramCount;

                $parameterName = $parameterDefinition['name'];

                if ( $parameterTranslation )
                {
                    if ( in_array( $parameterName, array_keys( $parameterTranslation ) ) )
                    {
                        $parameterName = $parameterTranslation[$parameterName];
                    }
                }

                $defaultValue = 'null';
                if ( !$parameterDefinition['required'] )
                    $defaultValue = eZPHPCreator::variableText( $parameterDefinition['default'], 0, 0, false );

                $parametersSelection = '%1%[\'' . $parameterName . '\']';
                $parametersCode .= '( isset( ' . $parametersSelection . ' ) ? ' . $parametersSelection . " : $defaultValue )";
            }

            $parametersCode .= ' )';

        }
        else
        {
            $parametersCode = 'array( ';
            if ( count( $functionDefinition['parameters'] ) )
            {
                foreach( $functionDefinition['parameters'] as $parameterDefinition )
                {
                    if ( $paramCount != 0 )
                    {
                        $parametersCode .= ',' . "\n";
                    }
                    ++$paramCount;

                    $parameterName = $parameterDefinition['name'];

                    if ( $parameterTranslation )
                    {
                        if ( in_array( $parameterName, array_keys( $parameterTranslation ) ) )
                        {
                            $parameterName = $parameterTranslation[$parameterName];
                        }
                    }

                    $parametersCode .= '    \'' . $parameterName . '\' => ';

                    if ( in_array( $parameterName, $functionKeys ) )
                    {
                        if ( $isDynamic )
                        {
                            if ( eZTemplateNodeTool::isConstantElement( $dynamicParameters[$parameterName] ) )
                            {
                                $parametersCode .= eZPHPCreator::variableText( eZTemplateNodeTool::elementConstantValue( $dynamicParameters[$parameterName] ), 0, 0, false );
                            }
                            else
                            {
                                $values[] = $dynamicParameters[$parameterName];
                                $parametersCode .= '%' . count( $values ) . '%';
                            }
                        }
                        else
                        {
                            $parametersCode .= eZPHPCreator::variableText( $staticParameters[$parameterName], 0, 0, false );
                        }
                    }
                    else if( $constParameters &&
                             isset( $constParameters[$parameterName] ) )
                    {
                        $parametersCode .= eZPHPCreator::variableText( $constParameters[$parameterName], 0, 0, false );
                    }
                    else
                    {
                        if ( $parameterDefinition['required'] )
                        {
                            return false;
                        }
                        else if ( isset( $parameterDefinition['default'] ) )
                        {
                            $parametersCode .= eZPHPCreator::variableText( $parameterDefinition['default'], 0, 0, false );
                        }
                        else
                        {
                            $parametersCode .= 'null';
                        }
                    }
                }
            }

            $parametersCode .= ' )';
        }

        $code = '%output% = call_user_func_array( array( new ' . $functionDefinition['call_method']['class'] . '(), \'' . $functionDefinition['call_method']['method'] . '\' ),' . "\n" .
                 '  array_values( ' . $parametersCode . ' ) );';
        $code .= "\n";

        $code .= '%output% = isset( %output%[\'result\'] ) ? %output%[\'result\'] : null;' . "\n";

        return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'fetch' => array( 'module_name' => array( 'type' => 'string',
                                                                'required' => true,
                                                                'default' => false ),
                                        'function_name' => array( 'type' => 'string',
                                                                  'required' => true,
                                                                  'default' => false ),
                                        'function_parameters' => array( 'type' => 'array',
                                                                        'required' => false,
                                                                        'default' => array() ) ),
                      'fetch_alias' => array( 'function_name' => array( 'type' => 'string',
                                                                  'required' => true,
                                                                  'default' => false ),
                                              'function_parameters' => array( 'type' => 'array',
                                                                              'required' => false,
                                                                              'default' => array() ) ) );
    }

    /*!
     Calls a specified module function and returns the result.
    */
    function modify( $tpl, $operatorName, $operatorParameters,
                     $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
    {
        $functionName = $namedParameters['function_name'];
        $functionParameters = $namedParameters['function_parameters'];

        if ( $operatorName == $this->Fetch )
        {
            $moduleName = $namedParameters['module_name'];
            $result = eZFunctionHandler::execute( $moduleName, $functionName, $functionParameters );
            $operatorValue = $result;
        }
        else if ( $operatorName == $this->FetchAlias )
        {
            $result = eZFunctionHandler::executeAlias( $functionName, $functionParameters );
            $operatorValue = $result;
        }
    }

    /// \privatesection
    public $Operators;

    public $Fetch;
    public $FetchAlias;
}

?>
