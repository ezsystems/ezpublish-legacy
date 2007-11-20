<?php
//
// Definition of eZTemplateBlockFunction class
//
// Created on: <01-Mar-2002 13:50:33 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
  \class eZTemplateBlockFunction eztemplateblockfunction.php
  \ingroup eZTemplateFunctions
  \brief Advanced block handling

  set-block
  Renders all it's children as text and sets it as a template variable.
  This is useful for allowing one template to return multiple text portions,
  for instance an email template could set subject as a block and return
  the rest as body.

\code
{set-block name=Space scope=global variable=text}
{$item} - {$item2}
{/set-block}
\endcode

  append-block
  Similar to set-block but will make the variable an array where each append-block
  adds an item.

\code
{append-block scope=global variable=extra_header_data}
<script language=jscript src={"/extension/xmleditor/dhtml/ezeditor.js"|ezroot}></script>
<link rel="stylesheet" type="text/css" href={"/extension/xmleditor/dhtml/toolbar.css"|ezroot}>
{/append-block}
\endcode

  run-once
  Makes sure that the block is run only once.

\code
{run-once}
<p>This appears only one time</p>
{/run-once}
\endcode
*/

class eZTemplateBlockFunction
{
    const SCOPE_RELATIVE = 1;
    const SCOPE_ROOT = 2;
    const SCOPE_GLOBAL = 3;

    /*!
     Initializes the object with names.
    */
    function eZTemplateBlockFunction( $blockName = 'set-block',
                                      $appendBlockName = 'append-block',
                                      $onceName = 'run-once' )
    {
        $this->BlockName = $blockName;
        $this->AppendBlockName = $appendBlockName;
        $this->OnceName = $onceName;
    }

    /*!
     Returns an array containing the name of the block function, default is "block".
     The name is specified in the constructor.
    */
    function functionList()
    {
        return array( $this->BlockName, $this->AppendBlockName, $this->OnceName );
    }

    function functionTemplateHints()
    {
        return array( $this->BlockName => array( 'parameters' => true,
                                                 'static' => false,
                                                 'transform-children' => true,
                                                 'tree-transformation' => true,
                                                 'transform-parameters' => true ),
                      $this->AppendBlockName => array( 'parameters' => true,
                                                       'static' => false,
                                                       'transform-children' => true,
                                                       'tree-transformation' => true,
                                                       'transform-parameters' => true ),
                      $this->OnceName => array( 'parameters' => false,
                                                'static' => false,
                                                'transform-children' => true,
                                                'tree-transformation' => true ) );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        if ( $functionName == $this->BlockName or
             $functionName == $this->AppendBlockName )
        {
            if ( !isset( $parameters['variable'] ) )
                return false;

            $scope = eZTemplate::NAMESPACE_SCOPE_RELATIVE;
            if ( isset( $parameters['scope'] ) )
            {
                if ( !eZTemplateNodeTool::isStaticElement( $parameters['scope'] ) )
                    return false;
                $scopeText = eZTemplateNodeTool::elementStaticValue( $parameters['scope'] );
                if ( $scopeText == 'relative' )
                    $scope = eZTemplate::NAMESPACE_SCOPE_RELATIVE;
                else if ( $scopeText == 'root' )
                    $scope = eZTemplate::NAMESPACE_SCOPE_LOCAL;
                else if ( $scopeText == 'global' )
                    $scope = eZTemplate::NAMESPACE_SCOPE_GLOBAL;
            }

            $name = '';
            if ( isset( $parameters['name'] ) )
            {
                if ( !eZTemplateNodeTool::isStaticElement( $parameters['name'] ) )
                    return false;
                $name = eZTemplateNodeTool::elementStaticValue( $parameters['name'] );
            }
            $variableName = eZTemplateNodeTool::elementStaticValue( $parameters['variable'] );

            $newNodes = array();

            $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );

            $newNodes[] = eZTemplateNodeTool::createOutputVariableIncreaseNode();
            $newNodes = array_merge( $newNodes, $children );
            $newNodes[] = eZTemplateNodeTool::createAssignFromOutputVariableNode( 'blockText' );
            if ( $functionName == $this->AppendBlockName )
            {
                $data = array( eZTemplateNodeTool::createVariableElement( $variableName, $name, $scope ) );
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $data, false, array(),
                                                                      'blockData' );

                // This block checks whether the append-block variable is an array or not.
                // TODO: This is a temporary solution and should also check whether the template variable exists.
                // This new solution requires probably writing the createVariableElement and createVariableNode your self.
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( is_null ( \$blockData ) ) \$blockData = array();" );
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( is_array ( \$blockData ) ) \$blockData[] = \$blockText;" );
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "else eZDebug::writeError( \"Variable '$variableName' is already in use.\" );" );
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'blockData', false, array(),
                                                                      array( $name, $scope, $variableName ), false, true, true );
                $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( 'blockData' );
            }
            else
            {
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, 'blockText', false, array(),
                                                                      array( $name, $scope, $variableName ), false, true, true );
            }
            $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( 'blockText' );
            $newNodes[] = eZTemplateNodeTool::createOutputVariableDecreaseNode();

            return $newNodes;
        }
        else if ( $functionName == $this->OnceName )
        {
            $functionPlacement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
            $key = $this->placementKey( $functionPlacement );
            $newNodes = array();
            if ( $key !== false )
            {
                $keyText = eZPHPCreator::variableText( $key, 0, 0, false );
                $placementText = eZPHPCreator::variableText( $functionPlacement, 0, 0, false );
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( !isset( \$GLOBALS['eZTemplateRunOnceKeys'][$keyText] ) )\n" .
                                                                       "{\n" .
                                                                       "    \$GLOBALS['eZTemplateRunOnceKeys'][$keyText] = $placementText;" );
                $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
                $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode( 4 );
                $newNodes = array_merge( $newNodes, $children );
                $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode( 4 );
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}" );
            }
            return $newNodes;
        }
        return false;
    }

    /*!
     Processes the function with all it's children.
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        switch ( $functionName )
        {
            case $this->BlockName:
            case $this->AppendBlockName:
            {
                $children = $functionChildren;
                $parameters = $functionParameters;

                $scope = eZTemplateBlockFunction::SCOPE_RELATIVE;
                if ( isset( $parameters["scope"] ) )
                {
                    $scopeText = $tpl->elementValue( $parameters["scope"], $rootNamespace, $currentNamespace, $functionPlacement );
                    if ( $scopeText == 'relative' )
                        $scope = eZTemplateBlockFunction::SCOPE_RELATIVE;
                    else if ( $scopeText == 'root' )
                        $scope = eZTemplateBlockFunction::SCOPE_ROOT;
                    else if ( $scopeText == 'global' )
                        $scope = eZTemplateBlockFunction::SCOPE_GLOBAL;
                    else
                        $tpl->warning( $functionName, "Scope value '$scopeText' is not valid, use either 'relative', 'root' or 'global'", $functionPlacement );
                }

                $name = null;
                if ( isset( $parameters["name"] ) )
                    $name = $tpl->elementValue( $parameters["name"], $rootNamespace, $currentNamespace, $functionPlacement );
                if ( $name === null )
                {
                    if ( $scope == eZTemplateBlockFunction::SCOPE_RELATIVE )
                        $name = $currentNamespace;
                    else if ( $scope == eZTemplateBlockFunction::SCOPE_ROOT )
                        $name = $rootNamespace;
                    else
                        $name = '';
                }
                else
                {
                    if ( $scope == eZTemplateBlockFunction::SCOPE_RELATIVE and
                         $currentNamespace != '' )
                        $name = "$currentNamespace:$name";
                    else if ( $scope == eZTemplateBlockFunction::SCOPE_ROOT and
                              $rootNamespace != '' )
                        $name = "$rootNamespace:$name";
                }
                $variableItem = null;
                if ( isset( $parameters["variable"] ) )
                {
                    $hasLoopItemParameter = true;
                    $variableItem = $tpl->elementValue( $parameters["variable"], $rootNamespace, $currentNamespace, $functionPlacement );
                }
                else
                {
                    $tpl->missingParameter( $functionName, 'variable' );
                    return;
                }

                $childTextElements = array();
                if ( is_array( $children ) )
                {
                    foreach ( array_keys( $children ) as $childKey )
                    {
                        $child =& $children[$childKey];
                        $tpl->processNode( $child, $childTextElements, $rootNamespace, $name );
                    }
                }
                $text = implode( '', $childTextElements );
                if ( $functionName == $this->AppendBlockName )
                {
                    $textArray = array();
                    if ( $tpl->hasVariable( $variableItem, $name ) )
                    {
                        $textArray = $tpl->variable( $variableItem, $name );
                        if ( !is_array( $textArray ) )
                        {
                           $tpl->warning( $functionName, "Variable '$variableItem' is already in use.", $functionPlacement );
                           return;
                        }
                    }
                    $textArray[] = $text;
                    $tpl->setVariable( $variableItem, $textArray, $name );
                }
                else
                    $tpl->setVariable( $variableItem, $text, $name );
            } break;

            case $this->OnceName:
            {
                $key = $this->placementKey( $functionPlacement );
                if ( $key !== false and !$this->hasPlacementKey( $key ) )
                {
                    $this->registerPlacementKey( $key, $functionPlacement );

                    if ( is_array( $functionChildren ) )
                    {
                        foreach ( array_keys( $functionChildren ) as $childKey )
                        {
                            $child =& $functionChildren[$childKey];
                            $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                        }
                    }
                }
            } break;
        }
    }

    function resetFunction( $functionName )
    {
        if ( $functionName == $this->OnceName )
        {
            unset( $GLOBALS['eZTemplateRunOnceKeys'] );
        }
    }

    /*!
     Generates an md5 key from the start, stop and file of the template function and returns it.
     \return false if the key could not be made.
    */
    function placementKey( $placement )
    {
        if ( isset( $placement[0] ) and
             isset( $placement[1] ) and
             isset( $placement[2] ) )
        {
            $input = $placement[0][0] . ',' . $placement[0][1] . "\n";
            $input .= $placement[1][0] . ',' . $placement[1][1] . "\n";
            $input .= $placement[2];
            return md5( $input );
        }
        return false;
    }

    /*!
     \return true if the placement key is registered which means that the block has already been run.
    */
    function hasPlacementKey( $key )
    {
        return isset( $GLOBALS['eZTemplateRunOnceKeys'][$key] );
    }

    /*!
     Registers the placement key \a $key with the data \a $placement.
    */
    function registerPlacementKey( $key, $placement )
    {
        return $GLOBALS['eZTemplateRunOnceKeys'][$key] = $placement;
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
    public $BlockName;
    public $AppendBlockName;
    public $OnceName;
}

?>
