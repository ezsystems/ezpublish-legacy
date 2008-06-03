<?php
//
// Definition of eZTemplateForeachFunction class
//
// Created on: <24-Feb-2005 15:47:35 vs>
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
  \class eZTemplateForeachFunction eztemplateforeachfunction.php
  \ingroup eZTemplateFunctions
  \brief FOREACH loop

  Syntax:
\code
    {foreach <array> as [$keyVar =>] $itemVar
             [sequence <array> as $sequenceVar]
             [offset <offset>]
            [max <max>]
            [reverse]}

        [{delimiter}...{/delimiter}]
        [{break}]
        [{continue}]
        [{skip}]
    {/foreach}
\endcode

  Example:
\code
    {foreach $objects as $object}
        <tr>
        {foreach $object.nodes as $node sequence array(dark,light) as $class}
            <td class=$class>
            {$node.name|wash}
            </td>
        {/foreach}
        </tr>
    {/foreach}
\endcode
*/

class eZTemplateForeachFunction
{
    const FUNCTION_NAME = 'foreach';

    /*!
     * Returns an array of the function names, required for eZTemplate::registerFunctions().
     */
    function &functionList()
    {
        $functionList = array( eZTemplateForeachFunction::FUNCTION_NAME );
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
        return array( eZTemplateForeachFunction::FUNCTION_NAME => array( 'parameters' => true,
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
        /*
        {foreach <array> as [$keyVar =>] $itemVar
                 [sequence <array> as $sequenceVar]
                 [offset <offset>]
                 [max <max>]
                 [reverse]
        }
        */

        $tpl->ForeachCounter++;
        $newNodes            = array();
        $nodePlacement       = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        $uniqid              =  md5( $nodePlacement[2] ) . "_" . $tpl->ForeachCounter;

        require_once( 'lib/eztemplate/classes/eztemplatecompiledloop.php' );
        $loop = new eZTemplateCompiledLoop( eZTemplateForeachFunction::FUNCTION_NAME,
                                            $newNodes, $parameters, $nodePlacement, $uniqid,
                                            $node, $tpl, $privateData );



        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// foreach begins" );

        $loop->initVars();

        $array           = "fe_array_$uniqid";
        $arrayKeys       = "fe_array_keys_$uniqid";
        $nItems          = "fe_n_items_$uniqid";
        $nItemsProcessed = "fe_n_items_processed_$uniqid";
        $i               = "fe_i_$uniqid";
        $key             = "fe_key_$uniqid";
        $val             = "fe_val_$uniqid";
        $offset          = "fe_offset_$uniqid";
        $max             = "fe_max_$uniqid";
        $reverse         = "fe_reverse_$uniqid";
        $firstVal        = "fe_first_val_$uniqid";
        $lastVal         = "fe_last_val_$uniqid";

        $variableStack   = "fe_variable_stack_$uniqid";
        $namesArray = array( $array, $arrayKeys, $nItems, $nItemsProcessed, $i, $key, $val, $offset, $max, $reverse, $firstVal, $lastVal );

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( !isset( \$$variableStack ) ) \$$variableStack = array();" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$" . $variableStack ."[] = compact( '" . implode( "', '", $namesArray ) . "' );" );

        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['array'], $nodePlacement, array( 'text-result' => false ), $array );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$arrayKeys = is_array( \$$array ) ? array_keys( \$$array ) : array();" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$nItems = count( \$$arrayKeys );" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$nItemsProcessed = 0;" );


        // process offset, max and reverse parameters
        if ( isset( $parameters['offset'] ) )
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['offset'], $nodePlacement, array( 'text-result' => false ), $offset );
        else
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$offset = 0;" );

        if ( isset( $parameters['max'] ) )
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['max'], $nodePlacement, array( 'text-result' => false ), $max );
        else
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$max = \$$nItems - \$$offset;" );

        if ( isset( $parameters['reverse'] ) )
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['reverse'], $nodePlacement, array( 'text-result' => false ), $reverse );
        else
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$reverse = false;" );


        // fix definitely incorrect offset
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$$offset < 0 || \$$offset >= \$$nItems )\n{\n".
                                                               "    \$$offset = ( \$$offset < 0 ) ? 0 : \$$nItems;\n".
                                                               "    if ( \$$nItems || \$$offset < 0 )\n {\n".
                                                               "        eZDebug::writeWarning(\"Invalid 'offset' parameter specified.\");   \n}\n}" );
        // fix definitely incorrect max
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$$max < 0 || \$$offset + \$$max > \$$nItems )\n{\n".
                                                               "    if ( \$$max < 0 )\n eZDebug::writeWarning(\"Invalid 'max' parameter specified.\");\n".
                                                               "    \$$max = \$$nItems - \$$offset;\n}" );

        // initialize first and last indexes to iterate between them
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$$reverse )\n" .
                                                               "{\n" .
                                                               "    \$$firstVal = \$$nItems - 1 - \$$offset;\n" .
                                                               "    \$$lastVal  = 0;\n" .
                                                               "}\n" .
                                                               "else\n" .
                                                               "{\n" .
                                                               "    \$$firstVal = \$$offset;\n" .
                                                               "    \$$lastVal  = \$$nItems - 1;\n" .
                                                               "}" );

        // generate loop header
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// foreach" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "for ( \$$i = \$$firstVal; " .
                                                               "\$$nItemsProcessed < \$$max && ( \$$reverse ? \$$i >= \$$lastVal : \$$i <= \$$lastVal ); " .
                                                               "\$$reverse ? \$$i-- : \$$i++ )\n" .
                                                               "{" );
        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$key = \$${arrayKeys}[\$$i];" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$val = \$${array}[\$$key];" );

        // export $itemVar
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, "$val", $nodePlacement, array(),
                                                              $parameters['item_var'][0][1],
                                                              false, true, true );

        // export $keyVar (if specified)
        if ( isset( $parameters['key_var'] ) )
        {
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, "$key", $nodePlacement, array(),
                                                                  $parameters['key_var'][0][1],
                                                                  false, true, true );
        }

        $loop->processBody();

        // generate loop footer
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$$nItemsProcessed++;" );
        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "} // foreach" );

        $loop->cleanup();

        // unset the loop variables
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( count( \$$variableStack ) ) extract( array_pop( \$$variableStack ) );\n" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "else\n{\n" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $array );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $arrayKeys );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $nItems );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $nItemsProcessed );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $i );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $key );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $val );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $offset );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $max );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $reverse );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $firstVal );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $lastVal );

        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $variableStack );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}\n" );

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// foreach ends" );

        return $newNodes;
    }

    /*!
     * Actually executes the function and its children (in processed mode).
     */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        /*
        {foreach <array> as [$keyVar =>] $itemVar
                 [sequence <array> as $sequenceVar]
                 [offset <offset>]
                 [max <max>]
                 [reverse]
        }
        */

        //eZDebug::writeDebug( $functionParameters, '$functionParameters' );

        require_once( 'lib/eztemplate/classes/eztemplateloop.php' );
        $loop = new eZTemplateLoop( eZTemplateForeachFunction::FUNCTION_NAME,
                                    $functionParameters, $functionChildren, $functionPlacement,
                                    $tpl, $textElements, $rootNamespace, $currentNamespace );

        if ( !$loop->initialized() )
            return;

        $loop->parseParamValue( 'array', $array );
        if ( !is_array( $array ) )
        {
            $tpl->error( eZTemplateForeachFunction::FUNCTION_NAME, "Missing/malformed array to iterate through.", $functionPlacement );
            return;
        }

        $loop->parseParamVarName( 'item_var', $itemVarName );
        if ( !$itemVarName )
        {
            $tpl->error( eZTemplateForeachFunction::FUNCTION_NAME, "Missing/malformed item variable name.", $functionPlacement );
            return;
        }

        $loop->parseParamVarName( 'key_var', $keyVarName );
        $loop->parseParamValue( 'max',      $max     );
        $loop->parseParamValue( 'offset',   $offset  );
        $loop->parseParamValue( 'reverse',  $reverse );

        /*
         * run the loop itself
         */

        /*
            $offset and $max parameters must meet the following requirements:
             -  $offset + $max <= $nItems
             -  $offset >= 0
             -  $max    >= 0
            Otherwise they are not considered.
        */

        $arrayKeys       = array_keys( $array );
        $nItems          = count( $arrayKeys );
        $nItemsProcessed = 0;

        // do nothing in case of empty array
        if ( !$nItems )
            return;

        // fix definitely incorrect offset
        if ( is_null( $offset ) )
            $offset = 0;
        elseif ( $offset < 0 || $offset >= $nItems )
        {
            if ( $nItems || $offset < 0 )
            {
                $tpl->warning( eZTemplateForeachFunction::FUNCTION_NAME, "Invalid 'offset' parameter specified.", $functionPlacement );
            }
            $offset = ( $offset < 0 ) ? 0 : $nItems;
        }

        // fix definitely incorrect max
        if ( is_null( $max ) )
            $max = $nItems - $offset;
        elseif ( $max < 0 || $offset+$max > $nItems )
        {
            if ( $max <0 )
                $tpl->warning( eZTemplateForeachFunction::FUNCTION_NAME, "Invalid 'max' parameter specified.", $functionPlacement );
            $max = $nItems - $offset;
        }

        // process 'reverse' parameter
        if ( is_null( $reverse ) )
            $reverse = false;
        if ( $reverse )
        {
            $firstVal = $nItems - 1 - $offset;
            $lastVal  = 0;
        }
        else
        {
            $firstVal = $offset;
            $lastVal  = $nItems - 1;
        }

        if ( $firstVal < $lastVal )
        {
            if ( $keyVarName && !$tpl->hasVariable( $keyVarName, $rootNamespace ) )
            {
                $loop->initLoopVariable( $keyVarName );
            }
            if ( !$tpl->hasVariable( $itemVarName, $rootNamespace ) )
            {
                $loop->initLoopVariable( $itemVarName );
            }
        }

        for ( $i = $firstVal; $nItemsProcessed < $max && ( $reverse ? $i >= $lastVal : $i <= $lastVal ); )
        {
            $key =& $arrayKeys[$i];
            $val =& $array[$key];

            if ( $keyVarName )
                $tpl->setVariable( $keyVarName, $key, $rootNamespace );
            $tpl->setVariable( $itemVarName, $val, $rootNamespace );

            $loop->setSequenceVar(); // set sequence variable (if specified)
            $loop->processDelimiter( $i );
            $loop->resetIteration();

            // process loop body
            if ( $loop->processChildren() )
                break;

            // increment loop counter here for delimiter to be processed correctly
            $reverse ? $i-- : $i++;

            $loop->incrementSequence();
            $nItemsProcessed++;
        }

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
