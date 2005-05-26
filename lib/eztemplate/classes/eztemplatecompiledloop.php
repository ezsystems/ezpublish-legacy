<?php
//
// Definition of eZTemplateCompiledLoop class
//
// Created on: <17-Mar-2005 11:26:59 vs>
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
  \class eZTemplateCompiledLoop eztemplatecompiledloop.php
  \ingroup eZTemplateFunctions
  \brief Common code for compiling the loop functions
*/
class eZTemplateCompiledLoop
{
    function eZTemplateCompiledLoop( $name, &$newNodes, &$parameters, &$nodePlacement, $uniqid,
                                     &$node, &$tpl, &$privateData )
    {
        $this->Name          =& $name;
        $this->Parameters    =& $parameters;
        $this->NodePlacement =& $nodePlacement;
        $this->UniqID        =& $uniqid;
        $this->NewNodes      =& $newNodes;
        $this->Node          =& $node;
        $this->Tpl           =& $tpl;
        $this->PrivateData   =& $privateData;
    }

    /*!
     * Returns true if sequence has been specified for the loop in its parameters.
     */
    function hasSequence()
    {
        return isset( $this->Parameters['sequence_var'] );
    }

    /*!
     * Destroys PHP and template variables defined by the loop.
     */
    function cleanup()
    {
        if ( $this->hasSequence() )
            $this->destroySequenceVars();
    }

    /*!
    \private
    */
    function destroySequenceVars()
    {
        $fName      = $this->Name;
        $uniqid     = $this->UniqID;
        $this->NewNodes[] = eZTemplateNodeTool::createVariableUnsetNode( "${fName}_sequence_array_$uniqid" );
        $this->NewNodes[] = eZTemplateNodeTool::createVariableUnsetNode( "${fName}_sequence_var_$uniqid" );
        $this->NewNodes[] = eZTemplateNodeTool::createVariableUnsetNode( $this->Parameters['sequence_var'][0][1] );
    }


    /*!
     * Create PHP and template variables representing sequence specified for the loop.
     */
    function createSequenceVars()
    {
        if ( !$this->hasSequence() )
            return;

        $fName      = $this->Name;
        $uniqid     = $this->UniqID;
        $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "// creating sequence variables for \{$fName} loop" );
        $this->NewNodes[] = eZTemplateNodeTool::createVariableNode( false,
                                                                    $this->Parameters['sequence_array'],
                                                                    $this->NodePlacement,
                                                                    array( 'treat-value-as-non-object' => true, 'text-result' => false ),
                                                                    "${fName}_sequence_array_$uniqid" );
        $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$${fName}_sequence_var_$uniqid = current( \$${fName}_sequence_array_$uniqid );\n" );
    }

    /*!
     * Export current sequence value to the template variable specified in loop parameters.
     */
    function setCurrentSequenceValue()
    {
        if ( !$this->hasSequence() )
            return;

        $fName    = $this->Name;
        $uniqid   = $this->UniqID;
        $seqVar   = "${fName}_sequence_var_$uniqid";
        $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "// setting current sequence value" );
        $this->NewNodes[] = eZTemplateNodeTool::createVariableNode( false, $seqVar, $this->NodePlacement, array(),
                                                                    $this->Parameters['sequence_var'][0][1],
                                                                    false, true, true );
    }

    /*!
     * Increments loop sequence.
     */
    function iterateSequence()
    {
        if ( !$this->hasSequence() )
            return;

        $fName    = $this->Name;
        $uniqid   = $this->UniqID;
        $seqArray = "${fName}_sequence_array_$uniqid";
        $seqVar   = "${fName}_sequence_var_$uniqid";
        $alterSeqValCode =
            "if ( ( \$$seqVar = next( \$$seqArray ) ) === false )\n" .
            "{\n" .
            "   reset( \$$seqArray );\n" .
            "   \$$seqVar = current( \$$seqArray );\n" .
            "}\n";
        $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "// sequence iteration" );
        $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( $alterSeqValCode );
    }


    /*
     * Compiles loop children (=code residing between start and end tags of the loop).
     * Besides, does special handling of {break}, {continue}, {skip} and {delimiter} functions.
     * \return true if the caller loop should break, false otherwise
     */
    function processChildren()
    {
        // process the loop body
        $children            = eZTemplateNodeTool::extractFunctionNodeChildren( $this->Node );
        $transformedChildren = eZTemplateCompiler::processNodeTransformationNodes( $this->Tpl, $this->Node, $children, $this->PrivateData );
        unset( $children );

        $childrenNodes = array();
        $delimiter = null;

        foreach ( array_keys( $transformedChildren ) as $childKey )
        {
            $child =& $transformedChildren[$childKey];

            if ( $child[0] == EZ_TEMPLATE_NODE_FUNCTION ) // check child type
            {
                $childFunctionName = $child[2];
                if ( $childFunctionName == 'delimiter' )
                {
                    // save delimiter for it to be processed below
                    $delimiter =& $child;
                    continue;
                }
                elseif ( $childFunctionName == 'break' )
                {
                    $childrenNodes[] = eZTemplateNodeTool::createCodePieceNode( "break;\n" );
                    continue;
                }
                elseif ( $childFunctionName == 'continue' )
                {
                    $childrenNodes[] = eZTemplateNodeTool::createCodePieceNode( "continue;\n" );
                    continue;
                }
                elseif ( $childFunctionName == 'skip' )
                {
                    $childrenNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$skipDelimiter = true;\ncontinue;\n" );
                    continue;
                }
            }

            $childrenNodes[] = $child;
        }

        if ( $delimiter ) // if delimiter is specified
        {
            $delimiterNodes = array();
            $delimiterNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$skipDelimiter )\n" .
                                                                         "    \$skipDelimiter = false;\n" .
                                                                         "else\n" .
                                                                         "{ // delimiter begins" );
            $delimiterNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();
            foreach ( $delimiter[1] as $delimiterChild )
                $delimiterNodes[] = $delimiterChild;
            $delimiterNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
            $delimiterNodes[] = eZTemplateNodeTool::createCodePieceNode( "} // delimiter ends\n" );

            // we place its code right before other loop children
            $childrenNodes = array_merge( $delimiterNodes, $childrenNodes );
        }

        $this->NewNodes = array_merge( $this->NewNodes, $childrenNodes );
    }

    /*!
     * Generates loop body.
     */
    function processBody()
    {
        // export current sequence value to the specified template variable <$sequence_var>
        $this->setCurrentSequenceValue();

        // process the loop body
        $this->processChildren();

        $this->iterateSequence();
    }

    /*!
     * create PHP and template variables needed for the loop.
     */
    function initVars()
    {
        // initialize delimiter processing
        $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$skipDelimiter = true;" );

        // initialize sequence
        $this->createSequenceVars();
    }

    ///
    /// \privatesection
    ///
    var $Name;
    var $Parameters;
    var $NodePlacement;
    var $UniqID;
    var $NewNodes;
    var $Node;
    var $Tpl;
    var $PrivateData;

}

?>
