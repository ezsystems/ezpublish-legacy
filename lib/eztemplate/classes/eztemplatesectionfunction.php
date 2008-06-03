<?php
//
// Definition of eZTemplateSectionFunction class
//
// Created on: <01-Mar-2002 13:50:33 amos>
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
  \class eZTemplateSectionFunction eztemplatesectionfunction.php
  \ingroup eZTemplateFunctions
  \brief Advanced block handling in templates using function "section".

  This class can be used in several different ways. It's primary
  use is for display an array of elements using the loop parameter.
  The array is iterated and the text of all the children are appended
  for each element. The current key and item is set in the namespace
  provided by the parameter name. If the paremeter sequence is supplied
  and it is an array it will be iterated and each element will be set
  in the variable sequence, when the end of the sequence is reached it
  is restarted.

\code
// Example of template code
{* Loop 5 times *}
{section loop = 5}
{$item}
{/section}
\endcode


Add these:
{section name=adsfsdf sequence=array(234,234,23,4,234) loop=.. max=2 offset=0 exclude}
{section-exclude check=id using=array(1,2,5)}
{section-exclude check=array(class,id) using=array(1,2,5)}
{section-include check=class_id using=array(1,2,5)}

asdfasdf


{delimiter}asdfasdf{/delimiter}
{/section}

{$module.features.list(5,array(2,5))}


{section loop=... max=10 offset=2}
{/section}



*/


//include_once( 'lib/eztemplate/classes/eztemplatesectioniterator.php' );

class eZTemplateSectionFunction
{
    /*!
     Initializes the object with a name, the name is required for determining
     the name of the -else tag.
    */
    function eZTemplateSectionFunction( $name = "section" )
    {
        $this->Name = $name;
    }

    /*!
     Returns the attribute list which is delimiter and $name-else,
     where $name is the name of the function.
    */
    function attributeList()
    {
        return array( "delimiter" => true,
                      "section-exclude" => false,
                      "section-include" => false,
                      $this->Name . "-else" => false );
    }

    /*!
     Returns an array containing the name of the section function, default is "section".
     The name is specified in the constructor.
    */
    function functionList()
    {
        return array( $this->Name );
    }

    function functionTemplateHints()
    {
        return array( $this->Name => array( 'parameters' => true,
                                            'static' => false,
                                            'transform-children' => false,
                                            'tree-transformation' => true,
                                            'transform-parameters' => true ) );
    }

    function functionTemplateStatistics( $functionName, &$node, $tpl, $resourceData, $namespace, &$stats )
    {
        if ( $functionName != $this->Name )
            return false;
        $newNamespace = $namespace;
        $parameters = eZTemplateNodeTool::extractFunctionNodeParameters( $node );
        if ( isset( $parameters['name'] ) )
        {
            $nameData = $parameters['name'];
            $nameDataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                           $nameData, false,
                                                                           $resourceData );
            if ( $nameDataInspection['is-constant'] and
                 !$nameDataInspection['has-operators'] and
                 !$nameDataInspection['has-attributes'] )
            {
                $parameterNamespace = $nameDataInspection['new-data'][0][1];
                $newNamespace = $tpl->mergeNamespace( $namespace, $parameterNamespace );
            }
        }
        $parameterNames = array( 'loop', 'show', 'var', 'last-value', 'reverse', 'sequence', 'max', 'offset' );
        foreach ( $parameterNames as $parameterName )
        {
            if ( isset( $parameters[$parameterName] ) )
            {
                eZTemplateCompiler::calculateVariableNodeStatistics( $tpl, $parameters[$parameterName], false, $resourceData, $namespace, $stats );
            }
        }

        if ( !isset( $parameters['var'] ) )
        {
            if ( isset( $parameters['loop'] ) )
            {
                $newVariables = array( 'key', 'item', 'index', 'number' );
                foreach ( $newVariables as $newVariableName )
                {
                    eZTemplateCompiler::setVariableStatistics( $stats, $newNamespace, $newVariableName, array( 'is_created' => true,
                                                                                                               'is_removed' => true ) );
                }
            }
            if ( isset( $parameters['sequence'] ) )
            {
                $newVariables = array( 'sequence' );
                foreach ( $newVariables as $newVariableName )
                {
                    eZTemplateCompiler::setVariableStatistics( $stats, $newNamespace, $newVariableName, array( 'is_created' => true,
                                                                                                               'is_removed' => true ) );
                }
            }
        }
        else
        {
            if ( isset( $parameters['loop'] ) )
            {
                $varDataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                              $parameters['var'], false,
                                                                              $resourceData );
                if ( $varDataInspection['is-constant'] and
                     !$varDataInspection['has-operators'] and
                     !$varDataInspection['has-attributes'] )
                {
                    $varName = $varDataInspection['new-data'][0][1];
                    eZTemplateCompiler::setVariableStatistics( $stats, $newNamespace, $varName, array( 'is_created' => true,
                                                                                                       'is_removed' => true ) );
                }
            }
        }

        $functionChildren = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
        if ( is_array( $functionChildren ) )
        {
            eZTemplateCompiler::calculateVariableStatisticsChildren( $tpl, $functionChildren, $resourceData, $newNamespace, $stats );
        }
    }

    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        $useLastValue = false;
        if ( isset( $parameters['last-value'] ) and
             !eZTemplateNodeTool::isStaticElement( $parameters['last-value'] ) )
            return false;
        if ( isset( $parameters['name'] ) and
             !eZTemplateNodeTool::isStaticElement( $parameters['name'] ) )
            return false;
        if ( isset( $parameters['var'] ) and
             !eZTemplateNodeTool::isStaticElement( $parameters['var'] ) )
            return false;
        if ( isset( $parameters['reverse'] ) and
             !eZTemplateNodeTool::isStaticElement( $parameters['reverse'] ) )
            return false;

        $varName = false;
        if ( isset( $parameters['var'] ) )
            $varName = eZTemplateNodeTool::elementStaticValue( $parameters['var'] );
        if ( isset( $parameters['last-value'] ) )
            $useLastValue = (bool)eZTemplateNodeTool::elementStaticValue( $parameters['last-value'] );
        if ( !$varName )
            $useLastValue = false;
        $reverseLoop = false;
        if ( isset( $parameters['reverse'] ) )
            $reverseLoop = eZTemplateNodeTool::elementStaticValue( $parameters['reverse'] );

        $useLoop = isset( $parameters['loop'] );
        $allowLoop = true;
        $newNodes = array();

        $maxText = "false";
        $useMax = false;
        $maxPopText = false;
        if ( isset( $parameters['max'] ) )
        {
            if ( eZTemplateNodeTool::isStaticElement( $parameters['max'] ) )
            {
                $maxValue = eZTemplateNodeTool::elementStaticValue( $parameters['max'] );
                if ( $maxValue > 0 )
                {
                    $maxText = eZPHPCreator::variableText( $maxValue );
                    $useMax = true;
                }
                else
                    return array( eZTemplateNodeTool::createTextNode( '' ) );
            }
            else
            {
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['max'], eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                      array(), 'max' );
                $maxText = "\$max";
                $maxPopText = ", \$max";
                $useMax = true;
            }
        }

        // Controls whether the 'if' statement with brackets is added
        $useShow = false;
        // Controls whether main nodes are handled, also controls delimiter and filters
        $useMain = true;
        // Controls wether else nodes are handled
        $useElse = false;

        $spacing = 0;
        if ( isset( $parameters['show'] ) )
        {
            if ( eZTemplateNodeTool::isStaticElement( $parameters['show'] ) )
            {
                $showValue = eZTemplateNodeTool::elementStaticValue( $parameters['show'] );
                if ( $showValue )
                {
                    $useMain = true;
                    $useElse = false;
                    $useShow = false;
                }
                else
                {
                    $useMain = false;
                    $useElse = true;
                    $useShow = false;
                }
                $newNodes[] = eZTemplateNodeTool::createTextNode( '' );
            }
            else
            {
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['show'], eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                      array(), 'show' );
                $spacing = 4;
                $useElse = true;
                $useShow = true;
            }
        }

        $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
        if ( $useShow )
        {
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$show )\n{\n" );
            $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode( $spacing );
            $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( 'show' );
        }
        if ( isset( $parameters['name'] ) and !$useLoop )
            $newNodes[] = eZTemplateNodeTool::createNamespaceChangeNode( $parameters['name'] );
        $mainNodes = eZTemplateNodeTool::extractNodes( $children,
                                                       array( 'match' => array( 'type' => 'before',
                                                                                'matches' => array( array( 'match-keys' => array( 0 ),
                                                                                                           'match-with' => eZTemplate::NODE_FUNCTION ),
                                                                                                    array( 'match-keys' => array( 2 ),
                                                                                                           'match-with' => 'section-else' ) ),
                                                                                'filter' => array( array( array( 'match-keys' => array( 0 ),
                                                                                                                 'match-with' => eZTemplate::NODE_FUNCTION ),
                                                                                                          array( 'match-keys' => array( 2 ),
                                                                                                                 'match-with' => array( 'delimiter', 'section-exclude', 'section-include' ) ) ) ) ) ) );
        $delimiterNodes = eZTemplateNodeTool::extractNodes( $children,
                                                            array( 'match' => array( 'type' => 'equal',
                                                                                     'matches' => array( array( 'match-keys' => array( 0 ),
                                                                                                               'match-with' => eZTemplate::NODE_FUNCTION ),
                                                                                                         array( 'match-keys' => array( 2 ),
                                                                                                                'match-with' => 'delimiter' ) ) ) ) );
        $filterNodes = eZTemplateNodeTool::extractNodes( $children,
                                                         array( 'match' => array( 'type' => 'equal',
                                                                                  'matches' => array( array( 'match-keys' => array( 0 ),
                                                                                                             'match-with' => eZTemplate::NODE_FUNCTION ),
                                                                                                      array( 'match-keys' => array( 2 ),
                                                                                                             'match-with' => array( 'section-exclude', 'section-include' ) ) ) ) ) );

        $delimiterNode = false;
        if ( count( $delimiterNodes ) > 0 )
            $delimiterNode = $delimiterNodes[0];

        if ( $useMain )
        {
            // Avoid transformation if the nodes will not be used, saves time
            $mainNodes = eZTemplateCompiler::processNodeTransformationNodes( $tpl, $node, $mainNodes, $privateData );
        }

        if ( $useLoop and $useMain )
        {
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['loop'], eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                  array(), 'loopItem' );
            $hasSequence = false;
            if ( isset( $parameters['sequence'] ) )
            {
                $sequenceParameter = $parameters['sequence'];
                $hasSequence = true;
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $sequenceParameter, eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                      array(), 'sequence' );
            }

            if ( isset( $parameters['name'] ) )
                $newNodes[] = eZTemplateNodeTool::createNamespaceChangeNode( $parameters['name'] );

            $code = ( "if ( !isset( \$sectionStack ) )\n" .
                      "    \$sectionStack = array();\n" );

            $variableValuePushText = '';
            $variableValuePopText = '';
            if ( $varName )
            {
                $code .= ( "//include_once( 'lib/eztemplate/classes/eztemplatesectioniterator.php' );\n" .
                           "\$variableValue = new eZTemplateSectionIterator();\n" .
                           "\$lastVariableValue = false;\n" );
                $variableValuePushText = "&\$variableValue, ";
                $variableValuePopText = "\$variableValue, ";
            }
            $code .= ( "\$index = 0;\n" .
                       "\$currentIndex = 1;\n" );


            $arrayCode = '';
            $numericCode = '';
            $stringCode = '';
            $offsetText = '0';
            if ( isset( $parameters['offset'] ) )
            {
                $offsetParameter = $parameters['offset'];
                if ( eZTemplateNodeTool::isStaticElement( $offsetParameter ) )
                {
                    $iterationValue = (int)eZTemplateNodeTool::elementStaticValue( $offsetParameter );
                    if ( $iterationValue > 0 )
                    {
                        $arrayCode = "    \$loopKeys = array_splice( \$loopKeys, $iterationValue );\n";
                    }
                    $offsetText = $iterationValue;
                }
                else
                {
                    $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $offsetParameter, eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                          array(), 'offset' );
                    $arrayCode = ( "    if ( \$offset > 0 )\n" .
                                   "        \$loopKeys = array_splice( \$loopKeys, \$offset );\n" );
                    $offsetText = "\$offset";
                }
            }

            // Initialization for array
            $code .= ( "if ( is_array( \$loopItem ) )\n{\n" .
                       "    \$loopKeys = array_keys( \$loopItem );\n" );
            if ( $reverseLoop )
                $code .= "    \$loopKeys = array_reverse( \$loopKeys );\n";
            $code .= $arrayCode;
            $code .= "    \$loopCount = count( \$loopKeys );\n";
            $code .= "}\n";

            // Initialization for numeric
            $code .= ( "else if ( is_numeric( \$loopItem ) )\n{\n" .
                       "    \$loopKeys = false;\n" .
                       $numericCode .
                       "    if ( \$loopItem < 0 )\n" .
                       "        \$loopCountValue = -\$loopItem;\n" .
                       "    else\n" .
                       "        \$loopCountValue = \$loopItem;\n" .
                       "    \$loopCount = \$loopCountValue - $offsetText;\n" .
                       "}\n" );

            // Initialization for string
            $code .= ( "else if ( is_string( \$loopItem ) )\n{\n" .
                       "    \$loopKeys = false;\n" .
                       $stringCode .
                       "    \$loopCount = strlen( \$loopItem ) - $offsetText;\n" .
                       "}\n" );
            // Fallback for no item
            $code .= ( "else\n{\n" .
                       "    \$loopKeys = false;\n" .
                       "    \$loopCount = 0;\n" .
                       "}" );
            // Initialization end



            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );
            $code = ( "while ( \$index < \$loopCount )\n" .
                      "{\n"  );
            if ( $useMax )
                $code .= ( "    if ( \$currentIndex > $maxText )\n" .
                           "        break;\n" .
                           "    unset( \$item );\n" );


            // Iterator check for array
            $code .= ( "    if ( is_array( \$loopItem ) )\n" .
                       "    {\n" .
                       "        \$loopKey = \$loopKeys[\$index];\n" .
                       "        unset( \$item );\n" .
                       "        \$item = \$loopItem[\$loopKey];\n" .
                       "    }\n" );

            // Iterator check for numeric
            $code .= ( "    else if ( is_numeric( \$loopItem ) )\n" .
                       "    {\n" .
                       "        unset( \$item );\n" );
            if ( $reverseLoop )
                $code .= "        \$item = \$loopCountValue - \$index - $offsetText;\n";
            else
                $code .= "        \$item = \$index + $offsetText + 1;\n";
            $code .= ( "        if ( \$loopItem < 0 )\n" .
                       "            \$item = -\$item;\n" );
            if ( $reverseLoop )
                $code .= "        \$loopKey = \$loopCountValue - \$index - $offsetText - 1;\n";
            else
                $code .= "        \$loopKey = \$index + $offsetText;\n";
            $code .= "    }\n";

            // Iterator check for string
            $code .= ( "    else if ( is_string( \$loopItem ) )\n" .
                       "    {\n" .
                       "        unset( \$item );\n" );
            if ( $reverseLoop )
                $code .= "        \$loopKey = \$loopCount - \$index - $offsetText + 1;\n";
            else
                $code .= "        \$loopKey = \$index + $offsetText;\n";
            $code .= ( "        \$item = \$loopItem[\$loopKey];\n" .
                       "    }\n" );
            // Iterator check end

            $code .= ( "    unset( \$last );\n" .
                       "    \$last = false;\n" );
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );
            $code = '';
            if ( $useLastValue )
            {
                $code .= ( "    if ( \$currentIndex > 1 )\n" .
                           "    {\n" .
                           "        \$last = \$lastVariableValue;\n" .
                           "        \$variableValue = new eZTemplateSectionIterator();\n" .
                           "    }\n" );
            }
            if ( $varName )
            {
                $code .= "    \$variableValue->setIteratorValues( \$item, \$loopKey, \$currentIndex - 1, \$currentIndex, false, \$last );";
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'variableValue', eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                      array( 'spacing' => 4 ), array( '', eZTemplate::NAMESPACE_SCOPE_RELATIVE, $varName ), false, true, true );
            }
            else
            {
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'loopKey', eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                      array( 'spacing' => 4 ), array( '', eZTemplate::NAMESPACE_SCOPE_RELATIVE, 'key' ), false, true, true );
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'item', eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                      array( 'spacing' => 4 ), array( '', eZTemplate::NAMESPACE_SCOPE_RELATIVE, 'item' ), false, true, true );
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$currentIndexInc = \$currentIndex - 1;\n" );
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'currentIndexInc', eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                      array( 'spacing' => 4 ), array( '', eZTemplate::NAMESPACE_SCOPE_RELATIVE, 'index' ), false, true, true );
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'currentIndex', eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                      array( 'spacing' => 4 ), array( '', eZTemplate::NAMESPACE_SCOPE_RELATIVE, 'number' ), false, true, true );
            }

            $mainSpacing = 0;
            $hasFilter = false;
            if ( count( $filterNodes ) > 0 )
            {
                $newFilterNodes = array();
                $matchValue = true;
                $hasDynamicFilter = false;
                foreach ( $filterNodes as $filterNode )
                {
                    $filterParameters = eZTemplateNodeTool::extractFunctionNodeParameters( $filterNode );
                    if ( !isset( $filterParameters['match'] ) )
                        continue;
                    $hasFilter = true;
                    $filterParameterMatch = $filterParameters['match'];
                    $filterParameterMatch = eZTemplateCompiler::processElementTransformationList( $tpl, $filterNode, $filterParameterMatch, $privateData );
                    if ( eZTemplateNodeTool::isStaticElement( $filterParameterMatch ) )
                    {
                        $matchValue = eZTemplateNodeTool::elementStaticValue( $filterParameterMatch );
                        if ( eZTemplateNodeTool::extractFunctionNodeName( $filterNode ) == 'section-exclude' )
                        {
                            if ( $matchValue )
                                $matchValue = false;
                        }
                        else
                        {
                            if ( $matchValue )
                                $matchValue = true;
                        }
                        $newFilterNodes = array();
                        $hasDynamicFilter = false;
                    }
                    else
                    {
                        $newFilterNodes[] = eZTemplateNodeTool::createVariableNode( false, $filterParameterMatch, eZTemplateNodeTool::extractFunctionNodePlacement( $filterNode ),
                                                                                    array( 'spacing' => 4 ), 'tmpMatchValue' );
                        if ( eZTemplateNodeTool::extractFunctionNodeName( $filterNode ) == 'section-exclude' )
                        {
                            $newFilterNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$tmpMatchValue )\n    \$matchValue = false;",
                                                                                         array( 'spacing' => 4 ) );
                        }
                        else
                        {
                            $newFilterNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$tmpMatchValue )\n    \$matchValue = true;",
                                                                                         array( 'spacing' => 4 ) );
                        }
                        $hasDynamicFilter = true;
                    }
                }
                if ( $hasFilter )
                {
                    $mainSpacing += 4;
                    $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $matchValue, eZTemplateNodeTool::extractFunctionNodePlacement( $filterNode ),
                                                                          array( 'spacing' => 4 ), 'matchValue' );
                    if ( $hasDynamicFilter )
                    {
                        $newNodes = array_merge( $newNodes, $newFilterNodes );
                    }
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$matchValue )\n{\n", array( 'spacing' => 4 ) );
                }
            }

            $sequencePopText = '';
            if ( $hasSequence )
            {
                $sequencePopText = ", \$sequence";
                if ( $varName )
                {
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( is_array( \$sequence ) )\n" .
                                                                           "{\n" .
                                                                           "    \$sequenceValue = array_shift( \$sequence );\n" .
                                                                           "    \$variableValue->setSequence( \$sequenceValue );\n" .
                                                                           "    \$sequence[] = \$sequenceValue;\n" .
                                                                           "    unset( \$sequenceValue );\n" .
                                                                           "}", array( 'spacing' => $mainSpacing + 4 ) );
                    $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'variableValue', eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                          array( 'spacing' => 4 ), array( '', eZTemplate::NAMESPACE_SCOPE_RELATIVE, $varName ), false, true, true );
                }
                else
                {
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( is_array( \$sequence ) )\n" .
                                                                           "{\n" .
                                                                           "    \$sequenceValue = array_shift( \$sequence );\n", array( 'spacing' => $mainSpacing + 4 ) );
                    $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'sequenceValue', eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                          array( 'spacing' => $mainSpacing + 4 ), array( '', eZTemplate::NAMESPACE_SCOPE_RELATIVE, 'sequence' ), false, true, true );
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "    \$sequence[] = \$sequenceValue;\n" .
                                                                           "    unset( \$sequenceValue );\n" .
                                                                           "}", array( 'spacing' => $mainSpacing + 4 ) );
                }
            }
            $code = ( "\$sectionStack[] = array( " . $variableValuePushText . "&\$loopItem, \$loopKeys, \$loopCount, \$currentIndex, \$index" . $sequencePopText . $maxPopText . " );\n" .
                      "unset( \$loopItem, \$loopKeys );\n" );
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code, array( 'spacing' => $mainSpacing + 4 ) );

            $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode( $mainSpacing + 4 );

            if ( $delimiterNode )
            {
                $delimiterChildren = eZTemplateNodeTool::extractFunctionNodeChildren( $delimiterNode );
                $delimiterParameters = eZTemplateNodeTool::extractFunctionNodeParameters( $delimiterNode );
                $delimiterChildren = eZTemplateCompiler::processNodeTransformationNodes( $tpl, $node, $delimiterChildren, $privateData );
                $delimiterModulo = false;
                $matchCode = false;
                $useModulo = true;
                if ( isset( $delimiterParameters['match'] ) )
                {
                    $delimiterMatch = $delimiterParameters['match'];
                    $delimiterMatch = eZTemplateCompiler::processElementTransformationList( $tpl, $delimiterNode, $delimiterMatch, $privateData );
                    if ( eZTemplateNodeTool::isStaticElement( $delimiterMatch ) )
                    {
                        $moduloValue = eZTemplateNodeTool::elementStaticValue( $delimiterMatch );
                        $useModulo = false;
                    }
                    else
                    {
                        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $delimiterMatch, eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                              array( 'spacing' => 0 ), 'matchValue' );
                        $matchCode = " and \$matchValue";
                    }
                }
                else if ( isset( $delimiterParameters['modulo'] ) )
                {
                    $delimiterModulo = $delimiterParameters['modulo'];
                    $delimiterModulo = eZTemplateCompiler::processElementTransformationList( $tpl, $delimiterModulo, $delimiterModulo, $privateData );
                    if ( eZTemplateNodeTool::isStaticElement( $delimiterModulo ) )
                    {
                        $moduloValue = (int)eZTemplateNodeTool::elementStaticValue( $delimiterModulo );
                        $matchCode = " and ( ( \$currentIndex - 1 ) % $moduloValue ) == 0";
                    }
                    else
                    {
                        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $delimiterModulo, eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                              array( 'spacing' => 0 ), 'moduloValue' );
                        $matchCode = " and ( ( \$currentIndex - 1 ) % \$moduloValue ) == 0";
                    }
                }
                if ( $useModulo )
                {
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$currentIndex > 1$matchCode )\n{" );

                    $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode( 4 );
                    $newNodes = array_merge( $newNodes, $delimiterChildren );
                    $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode( 4 );

                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}\n" );
                }
            }
            $newNodes = array_merge( $newNodes, $mainNodes );
            $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode( $mainSpacing + 4 );

            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "list( " . $variableValuePopText . "\$loopItem, \$loopKeys, \$loopCount, \$currentIndex, \$index" . $sequencePopText. $maxPopText . " ) = array_pop( \$sectionStack );",
                                                                   array( 'spacing' => $mainSpacing + 4 ) );
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "++\$currentIndex;\n", array( 'spacing' => $mainSpacing + 4 ) );
            if ( $varName )
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$lastVariableValue = \$variableValue;", array( 'spacing' => $mainSpacing + 4 ) );
            if ( $hasFilter )
            {
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "    }" );
                $mainSpacing -= 4;
            }
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "++\$index;\n", array( 'spacing' => $mainSpacing + 4 ) );
            $code = ( "}\n" .
                      "unset( \$loopKeys, \$loopCount, \$index, \$last, \$loopIndex, \$loopItem" );
            if ( $hasSequence )
                $code .= ", \$sequence";
            $code .= " );";
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );
        }
        else if ( $useMain )
        {
            $newNodes = array_merge( $newNodes, $mainNodes );
        }

        if ( isset( $parameters['name'] ) )
            $newNodes[] = eZTemplateNodeTool::createNamespaceRestoreNode();

        if ( $useShow )
        {
            $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode( $spacing );
        }

        if ( $useElse )
        {
            $elseNodes = eZTemplateNodeTool::extractNodes( $children,
                                                           array( 'match' => array( 'type' => 'after',
                                                                                    'matches' => array( array( 'match-keys' => array( 0 ),
                                                                                                               'match-with' => eZTemplate::NODE_FUNCTION ),
                                                                                                        array( 'match-keys' => array( 2 ),
                                                                                                               'match-with' => 'section-else' ) )
                                                                                    ) ) );
            $elseNodes = eZTemplateCompiler::processNodeTransformationNodes( $tpl, $node, $elseNodes, $privateData );
            if ( count( $elseNodes ) > 0 )
            {
                if ( $useShow )
                {
                    // This is needed if a 'if ( $show )' was used earlier
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}\nelse\n{\n" );
                    $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode( $spacing );
                    $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( 'show' );
                }

                if ( isset( $parameters['name'] ) )
                    $newNodes[] = eZTemplateNodeTool::createNamespaceChangeNode( $parameters['name'] );
                $newNodes = array_merge( $newNodes, $elseNodes );
                if ( isset( $parameters['name'] ) )
                    $newNodes[] = eZTemplateNodeTool::createNamespaceRestoreNode();

                if ( $useShow )
                {
                    // This is needed if a 'if ( $show )' was used earlier
                    $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode( $spacing );
                }
            }

            if ( $useShow )
            {
                // This is needed if a 'if ( $show )' was used earlier
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}\n" );
            }
        }
        return $newNodes;
    }

    /*!
     Processes the function with all it's children.
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $children = $functionChildren;
        $parameters = $functionParameters;
        $name = null;
        if ( isset( $parameters["name"] ) )
             $name = $tpl->elementValue( $parameters["name"], $rootNamespace, $currentNamespace, $functionPlacement );
        if ( $name === null )
        {
            $name = $currentNamespace;
        }
        else
        {
            if ( $currentNamespace != "" )
                $name = "$currentNamespace:$name";
        }

        $loopItem = null;
        $hasLoopItemParameter = false;
        if ( isset( $parameters["loop"] ) )
        {
            $hasLoopItemParameter = true;
            $loopItem = $tpl->elementValue( $parameters["loop"], $rootNamespace, $currentNamespace, $functionPlacement );
        }

        $variableIterator = null;
        if ( isset( $parameters['var'] ) )
        {
            $variableIterator = $tpl->elementValue( $parameters['var'], $rootNamespace, $currentNamespace, $functionPlacement );
        }

        $noLastValue = true;
        if ( isset( $parameters['last-value'] ) )
        {
            $lastValue = $tpl->elementValue( $parameters['last-value'], $rootNamespace, $currentNamespace, $functionPlacement );
            $noLastValue = !$lastValue;
        }

        $reverseLoop = false;
        if ( isset( $parameters['reverse'] ) )
        {
            $reverseLoop = $tpl->elementValue( $parameters['reverse'], $rootNamespace, $currentNamespace, $functionPlacement );
        }
        if ( $hasLoopItemParameter and $loopItem === null )
            return;

        $showItem = null;
        $showSet = isset( $parameters["show"] );
        if ( $showSet )
            $showItem = $tpl->elementValue( $parameters["show"], $rootNamespace, $currentNamespace, $functionPlacement );

        $sequenceStructure = null;
        if ( isset( $parameters["sequence"] ) )
            $sequenceStructure = $tpl->elementValue( $parameters["sequence"], $rootNamespace, $currentNamespace, $functionPlacement );

        $iterationMaxCount = false;
        if ( isset( $parameters["max"] ) )
        {
            $iterationMaxCount = $tpl->elementValue( $parameters["max"], $rootNamespace, $currentNamespace, $functionPlacement );
            if ( is_array( $iterationMaxCount ) )
            {
                $iterationMaxCount = count( $iterationMaxCount );
            }
            else if ( !is_numeric( $iterationMaxCount ) )
            {
                $tpl->warning( $functionName, "Wrong parameter type '" . gettype( $iterationMaxCount ) . "' for 'max', use either numericals or arrays", $functionPlacement );
            }
            $iterationMaxCount = (int)$iterationMaxCount;
        }

        $iterationOffset = false;
        if ( isset( $parameters["offset"] ) )
        {
            $iterationOffset = $tpl->elementValue( $parameters["offset"], $rootNamespace, $currentNamespace, $functionPlacement );
            if ( is_array( $iterationOffset ) )
            {
                $iterationOffset = count( $iterationOffset );
            }
            else if ( !is_numeric( $iterationOffset ) )
            {
                $tpl->warning( $functionName, "Wrong parameter type '" . gettype( $iterationOffset ) . "' for 'offset', use either numericals or arrays", $functionPlacement );
            }
            $iterationOffset = (int)$iterationOffset;
            if ( $iterationOffset < 0 )
            {
                $tpl->warning( $functionName, "The 'offset' parameter can only be negative, $iterationOffset is not accepteed, the value will be reset to 0", $functionPlacement );
                $iterationOffset = 0;
            }
        }

        $elseName = $functionName . "-else";
        $delimiterStructure = null;
        $filterStructure = array();
        $else = null;
        $shown = 1;
        $items = array();
        $items[0] = array();
        $items[1] = array();
        if ( is_array( $children ) )
        {
            foreach ( array_keys( $children ) as $childKey )
            {
                $child =& $children[$childKey];
                $childType = $child[0];
                if ( $childType == eZTemplate::NODE_FUNCTION )
                {
                    switch ( $child[2] )
                    {
                        case "delimiter":
                        {
                            if ( $shown === 1 and $delimiterStructure === null )
                            {
                                $delimiterStructure =& $child;
                            }
                        } break;
                        case "section-exclude":
                        case "section-include":
                        {
                            if ( $shown === 1 )
                                $filterStructure[] =& $child;
                        } break;
                        case $elseName:
                        {
                            $else =& $child;
                            $shown = 0;
                        } break;
                        default:
                        {
                            $items[$shown][] =& $child;
                        } break;
                    }
                }
                else
                {
                    $items[$shown][] =& $child;
                }
            }
        }

        $canShowBlock = true;
        if( $showSet and ( ( is_array( $showItem ) and count( $showItem ) == 0 ) or
                           ( is_numeric( $showItem ) and $showItem == 0 ) or
                           ( is_string( $showItem ) > 0 and strlen( $showItem ) == 0 ) or
                           !$showItem ) )
            $canShowBlock = false;

        if ( ( !$showSet or ( $showSet and $canShowBlock ) ) and $loopItem === null )
        {
            $this->processChildrenOnce( $textElements, $items[1], $tpl, $rootNamespace, $name );
        }
        else
        {
            $iteratorData = array( 'iterator' => false );
            $showMainBody = true;
            if ( $showSet )
            {
                if( !$canShowBlock )
                    $showMainBody = false;
            }
            if ( $showMainBody )
            {
                $isFirstRun = true;
                $index = 0;
                if ( is_array( $loopItem ) )
                {
                    $array =& $loopItem;
                    $arrayKeys = array_keys( $array );
                    if ( $reverseLoop )
                        $arrayKeys = array_reverse( $arrayKeys );
                    if ( $iterationOffset !== false )
                        $arrayKeys = array_splice( $arrayKeys, $iterationOffset );
                    $currentCount = 0;
                    foreach ( $arrayKeys as $key )
                    {
                        unset( $item );
                        $item =& $array[$key];
                        $usedElement = $this->processChildren( $textElements, $items[1], $key, $item, $index, $isFirstRun,
                                                               $delimiterStructure, $sequenceStructure, $filterStructure,
                                                               $tpl, $rootNamespace, $name, $functionPlacement,
                                                               $variableIterator, $noLastValue,
                                                               $iteratorData );
                        if ( $usedElement )
                        {
                            if ( $iterationMaxCount !== false )
                            {
                                ++$currentCount;
                                if ( $currentCount >= $iterationMaxCount )
                                    break;
                            }
                        }
                    }
                }
                else if ( is_numeric( $loopItem ) )
                {
                    $value = $loopItem;
                    $count = $value;
                    if ( $value < 0 )
                        $count = -$count;
                    $loopStart = 0;
                    if ( $iterationOffset !== false )
                        $loopStart = $iterationOffset;
                    $currentCount = 0;
                    for ( $i = $loopStart; $i < $count; ++$i )
                    {
                        unset( $iterator );
                        if ( $reverseLoop )
                            $iterator = ( $count - $i ) - 1;
                        else
                            $iterator = $i;
                        unset( $key );
                        unset( $item );
                        if ( $value < 0 )
                        {
                            $key = $iterator;
                            $item = -$iterator - 1;
                        }
                        else
                        {
                            $key = $iterator;
                            $item = $iterator + 1;
                        }
                        $usedElement = $this->processChildren( $textElements, $items[1], $key, $item, $index, $isFirstRun,
                                                               $delimiterStructure, $sequenceStructure, $filterStructure,
                                                               $tpl, $rootNamespace, $name, $functionPlacement,
                                                               $variableIterator, $noLastValue,
                                                               $iteratorData );
                        if ( $usedElement )
                        {
                            if ( $iterationMaxCount !== false )
                            {
                                ++$currentCount;
                                if ( $currentCount >= $iterationMaxCount )
                                    break;
                            }
                        }
                    }
                }
                else if ( is_string( $loopItem ) )
                {
                    $text =& $loopItem;
                    $stringLength = strlen( $text );
                    $loopStart = 0;
                    if ( $iterationOffset !== false )
                        $loopStart = $iterationOffset;
                    $currentCount = 0;
                    for ( $i = $loopStart; $i < $stringLength; ++$i )
                    {
                        if ( $reverseLoop )
                            $iterator = ($stringLength - $i) - 1;
                        else
                            $iterator = $i;
                        unset( $key );
                        unset( $item );
                        $key = $iterator;
                        $item = $text[$iterator];
                        $usedElement = $this->processChildren( $textElements, $items[1], $key, $item, $index, $isFirstRun,
                                                               $delimiterStructure, $sequenceStructure, $filterStructure,
                                                               $tpl, $rootNamespace, $name, $functionPlacement,
                                                               $variableIterator, $noLastValue,
                                                               $iteratorData );
                        if ( $usedElement )
                        {
                            if ( $iterationMaxCount !== false )
                            {
                                ++$currentCount;
                                if ( $currentCount >= $iterationMaxCount )
                                    break;
                            }
                        }
                    }
                }
                if ( !$isFirstRun )
                {
                    if ( $variableIterator !== null )
                    {
                        $tpl->unsetVariable( $variableIterator, $name );
                    }
                    else
                    {
                        $tpl->unsetVariable( "key", $name );
                        $tpl->unsetVariable( "item", $name );
                        $tpl->unsetVariable( "index", $name );
                        $tpl->unsetVariable( "number", $name );
                        if ( $sequenceStructure !== null and is_array( $sequenceStructure ) )
                            $tpl->unsetVariable( "sequence", $name );
                    }
                }
            }
            else
            {
                $this->processChildrenOnce( $textElements, $items[0], $tpl, $rootNamespace, $name );
            }
        }
    }

    /*!
     \private
     Goes trough all children and process them into text.
     \return \c true.
    */
    function processChildrenOnce( &$textElements, &$children, $tpl, $rootNamespace, $name )
    {
        foreach ( array_keys( $children ) as $childKey )
        {
            $child =& $children[$childKey];
            $tpl->processNode( $child, $textElements, $rootNamespace, $name );
        }
        return true;
    }

    /*!
     \private
     Goes trough all children and process them into text. It will skip the processing
     if the current item is filtered away.
     \return \c true if the element was processed, \c false otherwise.
    */
    function processChildren( &$textElements,
                              &$children, $key, &$item, &$index, &$isFirstRun,
                              &$delimiterStructure, &$sequenceStructure, &$filterStructure,
                              $tpl, $rootNamespace, $name, $functionPlacement,
                              &$variableIterator, $noLastValue,
                              &$iteratorData )
    {
        if ( $variableIterator !== null )
        {
            unset( $last );
            if ( !$noLastValue and $iteratorData['iterator'] !== false )
            {
                $last =& $iteratorData['iterator'];
            }
            else
            {
                $last = false;
            }
            unset( $iteratorData['iterator'] );
            $iteratorData['iterator'] = new eZTemplateSectionIterator();
            $iteratorData['iterator']->setIteratorValues( $item, $key, $index, $index + 1, false, $last );
            unset( $last );

            $iteratorObject =& $iteratorData['iterator'];
            $tpl->setVariableRef( $variableIterator, $iteratorObject, $name );
        }
        else
        {
            $tpl->setVariable( "key", $key, $name );
            $tpl->setVariable( "item", $item, $name );
            $tpl->setVariable( "index", $index, $name );
            $tpl->setVariable( "number", $index + 1, $name );
        }
        if ( count( $filterStructure ) > 0 )
        {
            $filterCount = count( $filterStructure );
            $includeElement = true;
            for ( $i = 0; $i < $filterCount; ++$i )
            {
                $filterElement =& $filterStructure[$i];
                $filterParameters =& $filterElement[3];
                $filterName = $filterElement[2];
                $filterMatch = null;
                if ( isset( $filterParameters["match"] ) )
                {
                    $filterMatch = $tpl->elementValue( $filterParameters["match"], $rootNamespace, $name, $functionPlacement );
                    if ( $filterMatch )
                        $includeElement = $filterName == "section-exclude" ? false : true;
                }
                else
                    $tpl->missingParameter( "section:$filterName", "match" );
            }
            if ( !$includeElement )
                return false;
        }
        if ( $delimiterStructure !== null and !$isFirstRun )
        {
            $delimiterParameters = $delimiterStructure[3];
            $delimiterMatch = true;
            if ( isset( $delimiterParameters["modulo"] ) )
            {
                $delimiterModulo = $delimiterParameters["modulo"];
                $modulo = $tpl->elementValue( $delimiterModulo, $rootNamespace, $name, $functionPlacement );
                $modulo = trim( $modulo );
                if ( is_numeric( $modulo ) )
                    $delimiterMatch = ( $index % $modulo ) == 0;
            }
            if ( isset( $delimiterParameters["match"] ) )
            {
                $delimiterMatchParameter = $delimiterParameters["match"];
                $delimiterMatch = $tpl->elementValue( $delimiterMatchParameter, $rootNamespace, $name, $functionPlacement );
            }
            if ( $delimiterMatch )
            {
                $delimiterChildren =& $delimiterStructure[1];
                if ( is_array( $delimiterChildren ) )
                {
                    foreach ( array_keys( $delimiterChildren ) as $delimiterChildKey )
                    {
                        $delimiterChild =& $delimiterChildren[$delimiterChildKey];
                        $tpl->processNode( $delimiterChild, $textElements, $rootNamespace, $name );
                    }
                }
            }
        }
        $isFirstRun = false;
        if ( $sequenceStructure !== null and is_array( $sequenceStructure ) )
        {
            $sequenceValue = array_shift( $sequenceStructure );
            if ( $variableIterator !== null )
            {
                $iteratorData['iterator']->setSequence( $sequenceValue );
            }
            else
            {
                $tpl->setVariable( "sequence", $sequenceValue, $name );
            }
            $sequenceStructure[] = $sequenceValue;
        }
        foreach ( array_keys( $children ) as $childKey )
        {
            $child =& $children[$childKey];
            $tpl->processNode( $child, $textElements, $rootNamespace, $name );
        }
        ++$index;
        return true;
    }

    /*!
     Returns true.
    */
    function hasChildren()
    {
        return true;
    }

    /// \privatesection
    /// Name of the function
    public $Name;
}

?>
