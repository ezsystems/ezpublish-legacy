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

    function hasSequence()
    {
        return isset( $this->Parameters['sequence_var'] );
    }

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
    }


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
    \return true if the caller loop should break, false otherwise
    */
    function processChildren()
    {
        // process the loop body
        $children            = eZTemplateNodeTool::extractFunctionNodeChildren( $this->Node );
        $transformedChildren = eZTemplateCompiler::processNodeTransformationNodes( $this->Tpl, $this->Node, $children, $this->PrivateData );
        unset( $children );

        foreach ( array_keys( $transformedChildren ) as $childKey )
        {
            $child =& $transformedChildren[$childKey];

            if ( $child[0] == EZ_TEMPLATE_NODE_FUNCTION ) // check child type
            {
                $childFunctionName = $child[2];
                if ( $childFunctionName == 'delimiter' )
                {
                    $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$skipDelimiter )\n" .
                                                                                 "    \$skipDelimiter = false;\n" .
                                                                                 "else\n" .
                                                                                 "{ // delimiter begins" );
                    foreach ( $child[1] as $delimiterChild )
                        $this->NewNodes[] = $delimiterChild;
                    $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "} // delimiter ends\n" );

                    continue;
                }
                elseif ( $childFunctionName == 'break' )
                    $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "break;\n" );
                elseif ( $childFunctionName == 'continue' )
                    $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "continue;\n" );
                elseif ( $childFunctionName == 'skip' )
                    $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$skipDelimiter = true;\ncontinue;\n" );
            }

            $this->NewNodes[] = $child;
        }
    }

    function processBody()
    {
        // export current sequence value to the specified template variable <$sequence_var>
        $this->setCurrentSequenceValue();

        // process the loop body
        $this->processChildren();

        $this->iterateSequence();
    }

    function initVars()
    {
        // initialize delimiter processing
        $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$skipDelimiter = true;\n" );

        // initialize sequence
        $this->createSequenceVars();
    }

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
