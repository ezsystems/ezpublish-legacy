<?php
//
// Definition of eZTemplateIfFunction class
//
// Created on: <07-Feb-2005 16:31:03 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZTemplateIfFunction eztemplateiffunction.php
  \ingroup eZTemplateFunctions
  \brief Conditional execution in templates

  This class allows to execute on of two or more code pieces depending
  on a condition.

  Syntax:
\code
  {if <condition>}
  [{elseif <condition>}]
  [{else}]
  {/if}
\endcode

  Example:
\code
  {if eq( $var, true() )}
    Hello world
  {else}
    No world here, move along.
  {/if}
\endcode
*/

class eZTemplateIfFunction
{
    const FUNCTION_NAME = 'if';

    /*!
     * Returns an array of the function names, required for eZTemplate::registerFunctions.
     */
    function &functionList()
    {
        //eZDebug::writeDebug( "if::functionList()" );
        $functionList = array( eZTemplateIfFunction::FUNCTION_NAME );
        return $functionList;
    }

    /*!
     * Returns the attribute list which is 'delimiter', 'elseif' and 'else'.
     * key:   parameter name
     * value: can have children
     */
    function attributeList()
    {
        return array( 'elseif'    => false,
                      'else'      => false );
    }


    /*!
     * Returns the array with hits for the template compiler.
     */
    function functionTemplateHints()
    {
        return array( eZTemplateIfFunction::FUNCTION_NAME => array( 'parameters' => true,
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
        $tpl->ElseifCounter++;
        $newNodes       = array();
        $nodesToPrepend = array();
        $nodesToAppend  = array();
        $nodePlacement  = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        $uniqid        =  md5( $nodePlacement[2] ) . "_" . $tpl->ElseifCounter;
        $children       = eZTemplateNodeTool::extractFunctionNodeChildren( $node );


        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// if begins" );
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['condition'], $nodePlacement, array( 'treat-value-as-non-object' => true ), 'if_cond' );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$if_cond )\n{" );
        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();

        if ( is_array( $children ) )
        {
            foreach ( array_keys( $children ) as $childKey )
            {
                $child =& $children[$childKey];

                if ( $child[0] == eZTemplate::NODE_FUNCTION )
                {
                    $childFunctionName =& $child[2];
                    $childChildren     = eZTemplateNodeTool::extractFunctionNodeChildren( $child );
                    $childFunctionArgs =& $child[3];

                    if ( $childFunctionName == 'elseif' )
                    {
                        $compiledElseifCondition = eZTemplateCompiler::processElementTransformationList( $tpl, $child, $childFunctionArgs['condition'], $privateData );
                        $nodesToPrepend[] = eZTemplateNodeTool::createVariableNode( false, $compiledElseifCondition,
                                                                                    $nodePlacement,
                                                                                    array( 'text-result' => true ),
                                                                                    "elseif_cond_$uniqid" );
                        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
                        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}\nelseif ( \$elseif_cond_$uniqid )\n{" );
                        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();
                        $nodesToAppend[] = eZTemplateNodeTool::createVariableUnsetNode( "elseif_cond_$uniqid" );
                        // increment unique elseif counter
                        $uniqid        =  md5( $nodePlacement[2] ) . "_" . ++$tpl->ElseifCounter;
                    }
                    elseif ( $childFunctionName == 'else' )
                    {
                        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
                        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}\nelse\n{" );
                        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();
                    }
                    elseif ( $childFunctionName == 'break' )
                        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "break;\n" );
                    elseif ( $childFunctionName == 'continue' )
                        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "continue;\n" );
                    elseif ( $childFunctionName == 'skip' )
                        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$skipDelimiter = true;\ncontinue;\n" );

                    // let other functions (ones not listed in the conditions above) be transformed
                    if ( in_array( $childFunctionName, array( 'elseif', 'else', 'break', 'continue', 'skip' ) ) )
                         continue;
                }
                $newNodes[] = $child;
            }
        }

        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "unset( \$if_cond );" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// if ends\n" );

        $newNodes = array_merge( $nodesToPrepend, $newNodes, $nodesToAppend );

        return $newNodes;
    }

    /*!
     * Actually executes the function and its children (in processed mode).
     */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        if ( count( $functionParameters ) == 0 || !count( $functionParameters['condition'] ) )
        {
            eZDebug::writeError( "Not enough arguments passed to 'if' function." );
            return;
        }

        $ifValue = $tpl->elementValue( $functionParameters['condition'], $rootNamespace, $currentNamespace, $functionPlacement );

        $show        = $ifValue; // whether to show the child being currently processing
        $showWasTrue = $show;    // true if $show has been assigned 'true' value at least once in the current {if}...{/if} block.
        $foundElse   = false;    // true if 'else' has been met once in the current {if}...{/if} block.

        if ( !is_array( $functionChildren ) )
        {
            return;
        }
        foreach ( $functionChildren as $key => $child )
        {
            $childType = $child[0];

            // parse 'elseif', 'else' functions
            if ( $childType == eZTemplate::NODE_FUNCTION )
            {
                $childFunctionName      =& $child[2];
                $childFunctionArgs      =& $child[3];
                $childFunctionPlacement =& $child[4];

                if ( $childFunctionName == 'else' )
                {
                    if ( $foundElse  )
                    {
                        eZDebug::writeError( "Duplicated 'else'" );
                        $show = false;
                        continue;
                    }

                    $show = !$showWasTrue;
                    $foundElse = true;
                    continue;
                }
                elseif ( $childFunctionName == 'elseif' )
                {
                    if ( $foundElse  )
                    {
                        eZDebug::writeError( "There should be no more 'elseif' after 'else'" );
                        $show = false;
                        continue;
                    }

                    if ( !isset( $childFunctionArgs['condition'] ) || !count( $childFunctionArgs['condition'] ) ) // no arguments passes to 'elseif' (should not happen)
                        $elseifValue = false;
                    else
                        $elseifValue = $tpl->elementValue( $childFunctionArgs['condition'], $rootNamespace, $currentNamespace, $functionPlacement );

                    if ( !$showWasTrue && $elseifValue )
                        $show = $showWasTrue = true;
                    else
                        $show = false;
                    continue;
                }
                elseif ( $childFunctionName == 'break' )
                {
                    if ( !$show )
                            continue;
                    return array( 'breakFunctionFound' => 1 );
                }
                elseif ( $childFunctionName == 'continue' )
                {
                    if ( !$show )
                            continue;
                    return array( 'continueFunctionFound' => 1 );
                }
                elseif ( $childFunctionName == 'skip' )
                {
                    if ( !$show )
                            continue;
                    return array( 'skipFunctionFound' => 1 );
                }
            }

            if ( $show )
            {
                $rslt = $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );

                // handle 'break', 'continue' and 'skip'
                if ( is_array( $rslt ) && ( array_key_exists( 'breakFunctionFound', $rslt ) ||
                                            array_key_exists( 'continueFunctionFound', $rslt )  ||
                                            array_key_exists( 'skipFunctionFound', $rslt ) ) )
                {
                    return $rslt;
                }
            }
        }
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
