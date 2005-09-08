<?php
//
// Definition of eZTemplateDoFunction class
//
// Created on: <25-Feb-2005 13:04:30 vs>
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
  \class eZTemplateDoFunction eztemplatedofunction.php
  \ingroup eZTemplateFunctions
  \brief DO..WHILE loop

  Syntax:
\code
    {do}
        [{delimiter}...{/delimiter}]
        [{break}]
        [{continue}]
        [{skip}]
    {/do while <condition> [sequence <array> as $seqVar]}
\endcode

  Example:
\code
    {do}
        One more beer, please.
    {/do while eq( $drunk, false() )}
\endcode
*/

define ( 'EZ_TEMPLATE_DO_FUNCTION_NAME', 'do' );
class eZTemplateDoFunction
{
    /*!
     * Returns an array of the function names, required for eZTemplate::registerFunctions().
     */
    function &functionList()
    {
        $functionList = array( EZ_TEMPLATE_DO_FUNCTION_NAME );
        return $functionList;
    }

    /*!
     * Returns the attribute list
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
        return array( EZ_TEMPLATE_DO_FUNCTION_NAME => array( 'parameters' => true,
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
        // {/do while <condition> [sequence <sequence_array> as $<sequence_var>]}

        $tpl->DoCounter++;
        $newNodes      = array();
        $nodePlacement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        $uniqid        =  md5( $nodePlacement[2] ) . "_" . $tpl->DoCounter;

        // initialize loop
        require_once( 'lib/eztemplate/classes/eztemplatecompiledloop.php' );
        $loop = new eZTemplateCompiledLoop( EZ_TEMPLATE_DO_FUNCTION_NAME,
                                            $newNodes, $parameters, $nodePlacement, $uniqid,
                                            $node, $tpl, $privateData );

        $loop->initVars();

        // loop header
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "while ( 1 ) // do..while\n{" );
        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();

        $loop->processBody();

        // loop footer
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['condition'], $nodePlacement, array( 'treat-value-as-non-object' => true ), 'do_cond' );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( ! \$do_cond ) break;" );
        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "} // do..while" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( 'do_cond' );

        $loop->cleanup();

        return $newNodes;
    }

    /*!
     * Actually executes the function and its children (in processed mode).
     */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        require_once( 'lib/eztemplate/classes/eztemplateloop.php' );
        $loop = new eZTemplateLoop( EZ_TEMPLATE_DO_FUNCTION_NAME,
                                    $functionParameters, $functionChildren, $functionPlacement,
                                    $tpl, $textElements, $rootNamespace, $currentNamespace );

        if ( !$loop->initialized() )
            return;

        if ( !isset( $functionParameters['condition'] ) )
        {
            eZDebug::writeError( "Not enough arguments passed to 'do' function"  );
            return;
        }

        do
        {
            $loop->resetIteration();
            $loop->setSequenceVar();

            if ( $loop->processChildren() )
                break;

            // evaluate the loop condition again
            $loopCond = $tpl->elementValue( $functionParameters['condition'], $rootNamespace, $currentNamespace, $functionPlacement );
            if ( $loop->processDelimiter( $loopCond ) )
                break;

            $loop->incrementSequence();
        } while ( $tpl->elementValue( $functionParameters['condition'], $rootNamespace, $currentNamespace, $functionPlacement ) );

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
