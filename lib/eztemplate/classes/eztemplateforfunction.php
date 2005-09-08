<?php
//
// Definition of eZTemplateForFunction class
//
// Created on: <21-Feb-2005 12:38:26 vs>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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

define ( 'EZ_TEMPLATE_FOR_FUNCTION_NAME', 'for' );
class eZTemplateForFunction
{
    /*!
     * Returns an array of the function names, required for eZTemplate::registerFunctions.
     */
    function &functionList()
    {
        $functionList = array( EZ_TEMPLATE_FOR_FUNCTION_NAME );
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
        return array( EZ_TEMPLATE_FOR_FUNCTION_NAME => array( 'parameters' => true,
                                                              'static' => false,
                                                              'transform-parameters' => true,
                                                              'tree-transformation' => true ) );
    }

    /*!
     * Compiles the function and its children into PHP code.
     */
    function templateNodeTransformation( $functionName, &$node,
                                         &$tpl, $parameters, $privateData )
    {
        // {for <first_val> to <last_val> as $<loop_var> [sequence <sequence_array> as $<sequence_var>]}

        $newNodes = array();
        $tpl->ForCounter++;
        $nodePlacement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        $uniqid        =  md5( $nodePlacement[2] ) . "_" . $tpl->ForCounter;

        require_once( 'lib/eztemplate/classes/eztemplatecompiledloop.php' );
        $loop = new eZTemplateCompiledLoop( EZ_TEMPLATE_FOR_FUNCTION_NAME,
                                            $newNodes, $parameters, $nodePlacement, $uniqid,
                                            $node, $tpl, $privateData );

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// for begins" );
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['first_val'], $nodePlacement, array( 'treat-value-as-non-object' => true ), "for_firstval_$uniqid" );
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['last_val'],  $nodePlacement, array( 'treat-value-as-non-object' => true ), "for_lastval_$uniqid"  );

        $loop->initVars();

        // loop header
        $modifyLoopCounterCode = "\$for_firstval_$uniqid < \$for_lastval_$uniqid ? \$for_i_${uniqid}++ : \$for_i_${uniqid}--"; // . ";\n";
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "for ( \$for_i_$uniqid = \$for_firstval_$uniqid ; ; $modifyLoopCounterCode )\n{" );
        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, "for_i_$uniqid", $nodePlacement,
                                                              array( 'text-result' => true ), $parameters['loop_var'][0][1], false, true, true );

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( !( \$for_firstval_$uniqid < \$for_lastval_$uniqid ? " .
                                                               "\$for_i_$uniqid <= \$for_lastval_$uniqid : " .
                                                               "\$for_i_$uniqid >= \$for_lastval_$uniqid ) )\n" .
                                                               "   break;\n" );

        $loop->processBody();

        // loop footer
        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "} // for" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $parameters['loop_var'][0][1] );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( "for_firstval_$uniqid" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( "for_lastval_$uniqid" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( "for_i_$uniqid" );
        $loop->cleanup();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// for ends\n" );

        return $newNodes;
    }

    /*!
     * Actually executes the function and its children (in processed mode).
     */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        /*
         * Check function parameters
         */

        require_once( 'lib/eztemplate/classes/eztemplateloop.php' );
        $loop = new eZTemplateLoop( EZ_TEMPLATE_FOR_FUNCTION_NAME,
                                    $functionParameters, $functionChildren, $functionPlacement,
                                    $tpl, $textElements, $rootNamespace, $currentNamespace );

        if ( !$loop->initialized() )
            return;

        $loop->parseScalarParamValue( 'first_val', $firstVal, $firstValIsProxy );
        $loop->parseScalarParamValue( 'last_val',  $lastVal,  $lastValIsProxy  );

        if ( $firstValIsProxy || $lastValIsProxy )
        {
            $tpl->error( EZ_TEMPLATE_FOR_FUNCTION_NAME,
                         "Proxy objects ({section} loop iterators) cannot be used to specify the range \n" .
                         "(this will lead to indefinite loops in compiled mode).\n" .
                         "Please explicitly dereference the proxy object like this: \$current_node.item." );
            return;
        }

        $loop->parseParamVarName( 'loop_var' , $loopVarName );

        if ( is_null( $firstVal ) || is_null( $lastVal ) || !$loopVarName )
        {
            $tpl->error( EZ_TEMPLATE_FOR_FUNCTION_NAME, "Wrong arguments passed." );
            return;
        }

        if ( !is_integer( $firstVal ) || !is_integer( $lastVal ) )
        {
            $tpl->error( EZ_TEMPLATE_FOR_FUNCTION_NAME, "Both 'from' and 'to' values can only be integers." );
            return;
        }

        $loop->initLoopVariable( $loopVarName );

        /*
         * Everything is ok, run the 'for' loop itself
         */
        for ( $i = $firstVal; $firstVal < $lastVal ? $i <= $lastVal : $i >= $lastVal; )
        {
            $loop->resetIteration();
            $loop->setSequenceVar();

            // set loop variable
            $tpl->setVariable( $loopVarName, $i, $rootNamespace );

            if ( $loop->processChildren() )
                break;

            // increment loop variable here for delimiter to be processed correctly
            $firstVal < $lastVal ? $i++ : $i--;

            // evaluate the loop condition again
            $loopCond = ( $firstVal < $lastVal ? $i <= $lastVal : $i >= $lastVal );
            if ( $loop->processDelimiter( $loopCond ) )
                break;

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
