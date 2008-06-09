<?php
//
// Definition of eZTemplateCompiledLoop class
//
// Created on: <17-Mar-2005 11:26:59 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

        $this->NewNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$skipDelimiter = false;" );
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

        $childrenNodes = array();
        $delimiter = null;

        if ( is_array( $transformedChildren ) )
        {
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
        }

        if ( $delimiter ) // if delimiter is specified
        {
            $delimiterNodes = eZTemplateNodeTool::extractNodes( $children,
                                                    array( 'match' => array( 'type' => 'equal',
                                                                             'matches' => array( array( 'match-keys' => array( 0 ),
                                                                                                       'match-with' => EZ_TEMPLATE_NODE_FUNCTION ),
                                                                                                 array( 'match-keys' => array( 2 ),
                                                                                                        'match-with' => 'delimiter' ) ) ) ) );
            $delimiterNode = false;
            if ( count( $delimiterNodes ) > 0 )
                $delimiterNode = $delimiterNodes[0];

            $delimiterChildren = eZTemplateNodeTool::extractFunctionNodeChildren( $delimiterNode );
            $delimiterParameters = eZTemplateNodeTool::extractFunctionNodeParameters( $delimiterNode );

            $checkModulo = array();
            $checkModuloEnd = array();
            $delemiterModuloValue = array();
            if ( isset( $delimiterParameters['modulo'] ) )
            {
                switch ( $this->Name )
                {
                    case 'foreach':
                    {
                        $delimiterModulo = $delimiterParameters['modulo'];
                        $delimiterModulo = eZTemplateCompiler::processElementTransformationList( $this->Tpl, $delimiterModulo, $delimiterModulo, $this->PrivateData );
                        // Get unique index
                        $currentIndex = "\$fe_i_$this->UniqID";

                        if ( eZTemplateNodeTool::isStaticElement( $delimiterModulo ) )
                        {
                            $moduloValue = (int)eZTemplateNodeTool::elementStaticValue( $delimiterModulo );
                            $matchCode = "( ( $currentIndex ) % $moduloValue ) == 0";
                        }
                        else
                        {
                            $delemiterModuloValue[] = eZTemplateNodeTool::createVariableNode( false, $delimiterModulo, eZTemplateNodeTool::extractFunctionNodePlacement( $this->Node ),
                                                                                        array( 'spacing' => 0 ), 'moduloValue' );
                            $matchCode = "( ( $currentIndex ) % \$moduloValue ) == 0";
                        }
                        $checkModulo[] = eZTemplateNodeTool::createCodePieceNode( "if ( $matchCode ) // Check modulo\n{" );
                        $checkModulo[] = eZTemplateNodeTool::createSpacingIncreaseNode( 4 );

                        $checkModuloEnd[] = eZTemplateNodeTool::createSpacingDecreaseNode( 4 );
                        $checkModuloEnd[] = eZTemplateNodeTool::createCodePieceNode( "}\n" );
                    }
                }
            }
            $delimiterNodes = array();
            $delimiterNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$skipDelimiter )\n" .
                                                                         "    \$skipDelimiter = false;\n" .
                                                                         "else\n" .
                                                                         "{ // delimiter begins" );
            $delimiterNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();
            if ( is_array( $delimiter[1] ) ) // if delimiter has children
            {
                // If modulo is specified
                $delimiterNodes = array_merge( $delimiterNodes, $checkModulo );

                foreach ( $delimiter[1] as $delimiterChild )
                    $delimiterNodes[] = $delimiterChild;

                // Set end of checking for modulo
                $delimiterNodes = array_merge( $delimiterNodes, $checkModuloEnd );
            }

            $delimiterNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
            $delimiterNodes[] = eZTemplateNodeTool::createCodePieceNode( "} // delimiter ends\n" );

            // we place its code right before other loop children,
            // if delemiter and modulo are specified and value of modulo is not static
            // $delemiterModuloValue is initialization of variable
            // we should place initialization of moduloValue before checking for delimiter
            $childrenNodes = array_merge( $delemiterModuloValue, $delimiterNodes, $childrenNodes );
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
