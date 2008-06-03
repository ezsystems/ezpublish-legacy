<?php
//
// Definition of eZTemplateSequenceFunction class
//
// Created on: <05-Mar-2002 13:55:25 amos>
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
  \class eZTemplateSequenceFunction eztemplatesectionfunction.php
  \ingroup eZTemplateFunctions
  \brief Wrapped array looping in templates using function "sequence"

  This class allows for creating arrays which are looped independently
  of a section. This is useful if you want to create multiple sequences.

\code
// Example of template code
{* Init the sequence *}
{sequence name=seq loop=array(2,5,7)}

{* Use it *}
{$seq:item}

{* Iterate it *}
{sequence name=seq}

\endcode
*/

class eZTemplateSequenceFunction
{
    /*!
     Initializes the function with the function name $inc_name.
    */
    function eZTemplateSequenceFunction()
    {
        $this->SequenceName = 'sequence';
    }

    /*!
     Returns an array of the function names, required for eZTemplate::registerFunctions.
    */
    function &functionList()
    {
        $functionList = array( $this->SequenceName );
        return $functionList;
    }

    /*!
     * Returns the array with hits for the template compiler.
     */
    function functionTemplateHints()
    {
        return array( $this->SequenceName => array( 'parameters' => true,
                                                    'static' => false,
                                                    'transform-parameters' => true,
                                                    'tree-transformation' => true ) );
    }

    function templateNodeSequenceCreate( &$node, $tpl, $parameters, $nameValue, $loopValue )
    {
        $newNodes = array();

        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['loop'],
                                                              eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                              array( 'text-result' => false ), '_array' );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$GLOBALS['eZTemplateSequence-$nameValue'] = array( 'iteration' => 0, 'index' => 0, 'loop' => \$_array );\n" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$tpl->setVariable( 'item', \$GLOBALS['eZTemplateSequence-$nameValue']['loop'][0],  \$namespace );\n" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$tpl->setVariable( 'iteration', 0, \$namespace );\n" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( '_array' );

        return $newNodes;
    }

    function templateNodeSequenceIterate( &$node, $tpl, $parameters, $nameValue )
    {
        $newNodes = array();

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$_seq_var = &\$GLOBALS['eZTemplateSequence-$nameValue'];\n" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "++\$_seq_var['index'];\n++\$_seq_var['iteration'];" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( \$_seq_var['index'] >= count( \$_seq_var['loop'] ) )\n\t\$_seq_var['index'] = 0;\n" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$tpl->setVariable( 'item', \$_seq_var['loop'][\$_seq_var['index']], \$namespace );\n" );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$tpl->setVariable( 'iteration', \$_seq_var['iteration'], \$namespace );\n" );

        return $newNodes;
    }

    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        $newNodes = array();
        $namespaceValue = false;
        $varName = 'match';

        if ( !isset( $parameters['name'] ) )
            return false;
        if ( !eZTemplateNodeTool::isStaticElement( $parameters['name'] ) )
            return false;

        $nameData = $parameters['name'];
        $nameValue = eZTemplateNodeTool::elementStaticValue( $nameData );

        $nameSpaceNode = eZTemplateNodeTool::createCodePieceNode( "\$namespace = \$rootNamespace;
if ( \$namespace == '' )
    \$namespace = \"$nameValue\";
else
    \$namespace .= ':$nameValue';
" );
        if ( isset( $parameters['loop'] ) )
        {
            $loopData = $parameters['loop'];
            if ( !eZTemplateNodeTool::isStaticElement( $loopData ) )
                return false;
            $loopValue = eZTemplateNodeTool::elementStaticValue( $loopData );

            $newNodes = $this->templateNodeSequenceCreate( $node, $tpl, $parameters, $nameValue, $loopValue );
        }
        else
        {
            $newNodes = $this->templateNodeSequenceIterate( $node, $tpl, $parameters, $nameValue );
        }
        $retNodes = array_merge( array( $nameSpaceNode ),  $newNodes );
        return $retNodes;
    }

    /*!
     Either initializes the sequence or iterates it.
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $params = $functionParameters;
        $loop = null;
        if ( isset( $params["loop"] ) )
        {
            $loop = $tpl->elementValue( $params["loop"], $rootNamespace, $currentNamespace, $functionPlacement );
        }
        if ( $loop !== null and !is_array( $loop ) )
        {
            $tpl->error( $functionName, "Can only loop arrays", $functionPlacement );
            return;
        }

        $name = "";
        if ( !isset( $params["name"] ) )
        {
            $tpl->missingParameter( $functionName, "name" );
            return;
        }
        $name = $tpl->elementValue( $params["name"], $rootNamespace, $currentNamespace, $functionPlacement );
        $seq_var =& $GLOBALS["eZTemplateSequence-$name"];
        if ( !is_array( $seq_var ) )
            $seq_var = array();
        if ( $loop !== null )
        {
            $seq_var["loop"] = $loop;
            $seq_var["iteration"] = 0;
            $seq_var["index"] = 0;
        }
        else
        {
            $index =& $seq_var["index"];
            $iteration =& $seq_var["iteration"];
            ++$iteration;
            ++$index;
            if ( $index >= count( $seq_var["loop"] ) )
                $index = 0 ;
        }
        $tpl->setVariable( "item", $seq_var["loop"][$seq_var["index"]], $tpl->mergeNamespace( $rootNamespace, $name ) );
        $tpl->setVariable( "iteration", $seq_var["iteration"], $tpl->mergeNamespace( $rootNamespace, $name ) );
    }

    /*!
     Returns false, telling the template parser that this is a single tag.
    */
    function hasChildren()
    {
        return false;
    }

    /// Name of sequence function
    public $SequenceName;
}

?>
