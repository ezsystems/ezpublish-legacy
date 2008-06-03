<?php
//
// Definition of eZTemplateForFunction class
//
// Created on: <21-Feb-2005 12:38:26 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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
  \class eZTemplateForFunction eztemplateforfunction.php
  \ingroup eZTemplateFunctions
  \brief FOR loop

  Syntax:
\code
    {for <number> to <number> as $itemVar [sequence <array> as $seqVar]}
        [{delimiter}...{/delimiter}]
        [{break}]
        [{continue}]
        [{skip}]
    {/for}
\endcode

  Examples:
\code
    {for 1 to 5 as $i}
        i: {$i}<br/>
    {/for}

    {for 5 to 1 as $i}
        i: {$i}<br/>
    {/for}
\endcode
*/

class eZTemplateForFunction
{
    const FUNCTION_NAME = 'for';

    /*!
     * Returns an array of the function names, required for eZTemplate::registerFunctions.
     */
    function &functionList()
    {
        $functionList = array( eZTemplateForFunction::FUNCTION_NAME );
        return $functionList;
    }

    /*!
     * Returns the attribute list.
     * key:   parameter name
     * value: can have children
     */
    function attributeList()
    {
        return array( 'delimiter' => true,
                      'break'     => false,
                      'continue'  => false,
                      'skip'      => false );
    }


    /*!
     * Returns the array with hits for the template compiler.
     */
    function functionTemplateHints()
    {
        return array( eZTemplateForFunction::FUNCTION_NAME => array( 'parameters' => true,
                                                              'static' => false,
                                                              'transform-parameters' => true,
                                                              'tree-transformation' => true ) );
    }

    /*!
     * Compiles the function and its children into PHP code.
     */
    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        // {for <first_val> to <last_val> as $<loop_var> [sequence <sequence_array> as $<sequence_var>]}

        $newNodes = array();
        $tpl->ForCounter++;
        $nodePlacement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        $uniqid        =  md5( $nodePlacement[2] ) . "_" . $tpl->ForCounter;

        require_once( 'lib/eztemplate/classes/eztemplatecompiledloop.php' );
        $loop = new eZTemplateCompiledLoop( eZTemplateForFunction::FUNCTION_NAME,
                                            $newNodes, $parameters, $nodePlacement, $uniqid,
                                            $node, $tpl, $privateData );

        $variableStack   = "for_variable_stack_$uniqid";
        $namesArray = array( "for_firstval_$uniqid", "for_lastval_$uniqid", "for_i_$uniqid" );

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// for begins" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( !isset( \$$variableStack ) ) \$$variableStack = array();" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$" . $variableStack ."[] = compact( '" . implode( "', '", $namesArray ) . "' );" );

        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['first_val'], $nodePlacement, array( 'treat-value-as-non-object' => true ), "for_firstval_$uniqid" );
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['last_val'],  $nodePlacement, array( 'treat-value-as-non-object' => true ), "for_lastval_$uniqid"  );

        $loop->initVars();

        // loop header
        $modifyLoopCounterCode = "\$for_firstval_$uniqid < \$for_lastval_$uniqid ? \$for_i_${uniqid}++ : \$for_i_${uniqid}--"; // . ";\n";
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "for ( \$for_i_$uniqid = \$for_firstval_$uniqid ; ; $modifyLoopCounterCode )\n{" );
        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();
        // Check for index
        $indexArray = isset( $parameters['loop_var'][0][1] ) ? $parameters['loop_var'][0][1] : array( "", 2, "default_index_$uniqid" );
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, "for_i_$uniqid", $nodePlacement,
                                                              array( 'text-result' => true ), $indexArray, false, true, true );

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( !( \$for_firstval_$uniqid < \$for_lastval_$uniqid ? " .
                                                               "\$for_i_$uniqid <= \$for_lastval_$uniqid : " .
                                                               "\$for_i_$uniqid >= \$for_lastval_$uniqid ) )\n" .
                                                               "   break;\n" );

        $loop->processBody();

        // loop footer
        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "} // for" );

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( count( \$$variableStack ) ) extract( array_pop( \$$variableStack ) );\n" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "else\n{\n" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $indexArray );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( "for_firstval_$uniqid" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( "for_lastval_$uniqid" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( "for_i_$uniqid" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $variableStack );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}\n" );
        $loop->cleanup();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// for ends\n" );

        return $newNodes;
    }

    /*!
     * Actually executes the function and its children (in processed mode).
     */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        /*
         * Check function parameters
         */

        require_once( 'lib/eztemplate/classes/eztemplateloop.php' );
        $loop = new eZTemplateLoop( eZTemplateForFunction::FUNCTION_NAME,
                                    $functionParameters, $functionChildren, $functionPlacement,
                                    $tpl, $textElements, $rootNamespace, $currentNamespace );

        if ( !$loop->initialized() )
            return;

        $loop->parseScalarParamValue( 'first_val', $firstVal, $firstValIsProxy );
        $loop->parseScalarParamValue( 'last_val',  $lastVal,  $lastValIsProxy  );

        if ( $firstValIsProxy || $lastValIsProxy )
        {
            $tpl->error( eZTemplateForFunction::FUNCTION_NAME,
                         "Proxy objects ({section} loop iterators) cannot be used to specify the range \n" .
                         "(this will lead to indefinite loops in compiled mode).\n" .
                         "Please explicitly dereference the proxy object like this: \$current_node.item." );
            return;
        }

        $loop->parseParamVarName( 'loop_var' , $loopVarName );

        if ( is_null( $firstVal ) || is_null( $lastVal ) || !$loopVarName )
        {
            $tpl->error( eZTemplateForFunction::FUNCTION_NAME, "Wrong arguments passed." );
            return;
        }

        if ( !is_numeric( $firstVal ) || !is_numeric( $lastVal ) )
        {
            $tpl->error( eZTemplateForFunction::FUNCTION_NAME, "Both 'from' and 'to' values can only be numeric." );
            return;
        }

        $loop->initLoopVariable( $loopVarName );

        /*
         * Everything is ok, run the 'for' loop itself
         */
        for ( $i = $firstVal; $firstVal < $lastVal ? $i <= $lastVal : $i >= $lastVal; )
        {
            // set loop variable
            $tpl->setVariable( $loopVarName, $i, $rootNamespace );

            $loop->setSequenceVar(); // set sequence variable (if specified)
            $loop->processDelimiter();
            $loop->resetIteration();

            if ( $loop->processChildren() )
                break;

            // increment loop variable here for delimiter to be processed correctly
            $firstVal < $lastVal ? $i++ : $i--;

            $loop->incrementSequence();
        } // for

        $loop->cleanup();
    }

    /*!
     * Returns true, telling the template parser that the function can have children.
     */
    function hasChildren()
    {
        return true;
    }
}

?>
