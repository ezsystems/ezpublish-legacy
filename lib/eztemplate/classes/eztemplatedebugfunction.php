<?php
//
// Definition of eZTemplateDebugFunction class
//
// Created on: <01-Mar-2002 13:50:33 amos>
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
  \class eZTemplateDebugFunction eztemplatedebugfunction.php
  \ingroup eZTemplateFunctions
  \brief Advanced debug handling

  debug-timing-point
  Starts a timing point, executes body and ends the timing point.
  This is useful if you want to figure out how fast a piece of
  template code goes or to see all debug entries that occur
  between these two points.

  \code
  {debug-timing-point id=""}
  {$item} - {$item2}
  {/debug-timing-point}
  \endcode

  debug-accumulator
  Executes the body and performs statistics.
  The number of calls, total time and average time will be shown in debug.

  \code
  {debug-accumulator}
  {section var=error loop=$errors}{$error}{/section}
  {/debug-accumulator}
  \endcode

  debug-trace
  Executes the body while tracing the result using XDebug.
  The result will a trace file made by XDebug which can be analyzed.
  Note: This will not do anything when XDebug is not available

  \code
  {debug-trace id="loop"}
  {section var=error loop=$errors}{$error}{/section}
  {/debug-trace}
  \endcode
*/

class eZTemplateDebugFunction
{
    /*!
     Initializes the object with names.
    */
    function eZTemplateDebugFunction( $timingPoint = 'debug-timing-point',
                                      $accumulator = 'debug-accumulator',
                                      $trace = 'debug-trace' )
    {
        $this->TimingPointName = $timingPoint;
        $this->AccumulatorName = $accumulator;
        $this->TraceName = $trace;
    }

    /*!
     Return the list of available functions.
    */
    function functionList()
    {
        return array( $this->TimingPointName, $this->AccumulatorName, $this->TraceName );
    }

    function functionTemplateHints()
    {
        return array( $this->TimingPointName => array( 'parameters' => true,
                                                       'static' => false,
                                                       'transform-children' => true,
                                                       'tree-transformation' => true,
                                                       'transform-parameters' => true ),
                      $this->AccumulatorName => array( 'parameters' => true,
                                                       'static' => false,
                                                       'transform-children' => true,
                                                       'tree-transformation' => true,
                                                       'transform-parameters' => true ),
                      $this->TraceName => array( 'parameters' => true,
                                                 'static' => false,
                                                 'transform-children' => true,
                                                 'tree-transformation' => true,
                                                 'transform-parameters' => true ) );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         &$tpl, $parameters, $privateData )
    {
        if ( $functionName == $this->TimingPointName )
        {
            $id = false;
            if ( isset( $parameters['id'] ) )
            {
                if ( !eZTemplateNodeTool::isConstantElement( $parameters['id'] ) )
                    return false;
                $id = eZTemplateNodeTool::elementConstantValue( $parameters['id'] );
            }

            $newNodes = array();

            $startDescription = "debug-timing-point START: $id";
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "eZDebug::addTimingPoint( " . var_export( $startDescription, true ) . " );" );

            $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
            $newNodes = array_merge( $newNodes, $children );

            $endDescription = "debug-timing-point END: $id";
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "eZDebug::addTimingPoint( " . var_export( $endDescription, true ) . " );" );

            return $newNodes;
        }
        else if ( $functionName == $this->AccumulatorName )
        {
            $id = false;
            if ( isset( $parameters['id'] ) )
            {
                if ( !eZTemplateNodeTool::isConstantElement( $parameters['id'] ) )
                    return false;
                $id = eZTemplateNodeTool::elementConstantValue( $parameters['id'] );
            }

            $name = false;
            if ( isset( $parameters['name'] ) )
            {
                if ( !eZTemplateNodeTool::isConstantElement( $parameters['name'] ) )
                    return false;
                $name = eZTemplateNodeTool::elementConstantValue( $parameters['name'] );
            }

            $newNodes = array();

            if ( $name )
            {
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "eZDebug::accumulatorStart( " . var_export( $id, true ) . ", 'Debug-Accumulator', " . var_export( $name, true ) . " );" );
            }
            else
            {
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "eZDebug::accumulatorStart( " . var_export( $id, true ) . ", 'Debug-Accumulator' );" );
            }

            $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
            $newNodes = array_merge( $newNodes, $children );

            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "eZDebug::accumulatorStop( " . var_export( $id, true ) . " );" );

            return $newNodes;
        }
        else if ( $functionName == $this->TraceName )
        {
            $id = false;
            if ( isset( $parameters['id'] ) )
            {
                if ( !eZTemplateNodeTool::isConstantElement( $parameters['id'] ) )
                    return false;
                $id = eZTemplateNodeTool::elementConstantValue( $parameters['id'] );
            }

            if ( !$id )
                $id = 'template-debug';

            $newNodes = array();

            $code = ( "if ( extension_loaded( 'xdebug' ) )\n" .
                      "{\n" .
                      "if ( file_exists( " . var_export( $id . '.xt', true ) . " ) )\n" .
                      "{\n" .
                      "\$fd = fopen( " . var_export( $id . '.xt', true ) . ", 'w' ); fclose( \$fd ); unset( \$fd );\n" .
                      "}\n" .
                      "xdebug_start_trace( " . var_export( $id, true ) . " );\n" .
                      "}\n" );
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );

            $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
            $newNodes = array_merge( $newNodes, $children );

            $code = ( "if ( extension_loaded( 'xdebug' ) )\n" .
                      "xdebug_stop_trace();\n" );
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );

            return $newNodes;
        }
        return false;
    }

    /*!
     Processes the function with all it's children.
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        switch ( $functionName )
        {
            case $this->TimingPointName:
            {
                $children = $functionChildren;
                $parameters = $functionParameters;

                $id = false;
                if ( isset( $parameters["id"] ) )
                {
                    $id = $tpl->elementValue( $parameters["id"], $rootNamespace, $currentNamespace, $functionPlacement );
                }


                $startDescription = "debug-timing-point START: $id";
                eZDebug::addTimingPoint( $startDescription );

                foreach ( array_keys( $children ) as $childKey )
                {
                    $child =& $children[$childKey];
                    $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                }

                $endDescription = "debug-timing-point END: $id";
                eZDebug::addTimingPoint( $endDescription );

            } break;

            case $this->AccumulatorName:
            {
                $children = $functionChildren;
                $parameters = $functionParameters;

                $id = false;
                if ( isset( $parameters["id"] ) )
                {
                    $id = $tpl->elementValue( $parameters["id"], $rootNamespace, $currentNamespace, $functionPlacement );
                }

                $name = false;
                if ( isset( $parameters["name"] ) )
                {
                    $name = $tpl->elementValue( $parameters["name"], $rootNamespace, $currentNamespace, $functionPlacement );
                }

                eZDebug::accumulatorStart( $id, 'Debug-Accumulator', $name );

                foreach ( array_keys( $children ) as $childKey )
                {
                    $child =& $children[$childKey];
                    $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                }

                eZDebug::accumulatorStop( $id, 'Debug-Accumulator', $name );

            } break;

            case $this->TraceName:
            {
                $children = $functionChildren;

                $id = false;
                // If we have XDebug we start the trace, execute children and stop it
                // if not we just execute the children as normal
                if ( extension_loaded( 'xdebug' ) )
                {
                    $parameters = $functionParameters;
                    if ( isset( $parameters["id"] ) )
                    {
                        $id = $tpl->elementValue( $parameters["id"], $rootNamespace, $currentNamespace, $functionPlacement );
                    }

                    if ( !$id )
                        $id = 'template-debug';

                    // If we already have a file, make sure it is truncated
                    if ( file_exists( $id . '.xt' ) )
                    {
                        $fd = fopen( $id, '.xt', 'w' ); fclose( $fd );
                    }
                    xdebug_start_trace( $id );

                    foreach ( array_keys( $children ) as $childKey )
                    {
                        $child =& $children[$childKey];
                        $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                    }

                    xdebug_stop_trace();
                }
                else
                {
                    foreach ( array_keys( $children ) as $childKey )
                    {
                        $child =& $children[$childKey];
                        $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                    }
                }

            } break;
        }
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
    var $DebugName;
    var $AppendDebugName;
    var $OnceName;
}

?>
