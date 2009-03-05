<?php
//
// Definition of eZTemplateDebugFunction class
//
// Created on: <01-Mar-2002 13:50:33 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

  debug-log
  Does exactly the same as eZDebug::writeDebug() method.
  Has two parameters:
  - var: variable to dump
  - msg: text message

  \code
  {debug-log var=$object msg='object contents'}
  {debug-log msg='hello world'}
  {debug-log var=array(1,2,3)}
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
                                      $log = 'debug-log',
                                      $trace = 'debug-trace' )
    {
        $this->TimingPointName = $timingPoint;
        $this->AccumulatorName = $accumulator;
        $this->LogName = $log;
        $this->TraceName = $trace;
    }

    /*!
     Return the list of available functions.
    */
    function functionList()
    {
        return array( $this->TimingPointName, $this->AccumulatorName, $this->LogName, $this->TraceName );
    }

    /*!
     * Returns the attribute list.
     * key:   parameter name
     * value: can have children
     */
    function attributeList()
    {
        return array(
            $this->TimingPointName => true,
            $this->AccumulatorName => true,
            $this->LogName => false,
            $this->TraceName => true
        );
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
                      $this->LogName => array( 'parameters' => true,
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
                                         $tpl, $parameters, $privateData )
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
            // Assign a name (as $functionName) which will be used in the debug output.
            $name = ( $name === false and $id === false ) ?  $functionName : $name;
            // To uniquely identify this accumulator.
            $id = $id === false ? uniqID( $functionName . '_' ): $id;
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
        else if ( $functionName == $this->LogName )
        {
            $nodePlacement  = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
            $newNodes = array();

            $varIsSet = $msgIsSet = false;
            if ( isset( $parameters['var'] ) )
            {
                $varIsSet = true;
                $var = $parameters['var'];
            }
            if ( isset( $parameters['msg'] ) )
            {
                $msgIsSet = true;
                $msg = $parameters['msg'];
            }

            $newNodes[]= eZTemplateNodeTool::createCodePieceNode( "// debug-log starts\n" );

            if ( $varIsSet )
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $var, $nodePlacement, array( 'treat-value-as-non-object' => true ), 'debug_log_var' );
            if ( $msgIsSet )
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $msg, $nodePlacement, array( 'treat-value-as-non-object' => true ), 'debug_log_msg' );

            if ( $varIsSet && $msgIsSet )
                 $newNodes[]= eZTemplateNodeTool::createCodePieceNode( "eZDebug::writeDebug( \$debug_log_var, \$debug_log_msg );\n" );
            elseif ( $msgIsSet )
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "eZDebug::writeDebug( \$debug_log_msg );\n" );
            elseif ( $varIsSet )
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "eZDebug::writeDebug( \$debug_log_var );\n" );

            if ( $varIsSet )
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "unset( \$debug_log_var );" );
            if ( $msgIsSet )
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "unset( \$debug_log_msg );" );

            $newNodes[]= eZTemplateNodeTool::createCodePieceNode( "// debug-log ends\n" );

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
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
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

                if ( is_array( $children ) )
                {
                    foreach ( array_keys( $children ) as $childKey )
                    {
                        $child =& $children[$childKey];
                        $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                    }
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

                // Assign a name (as $functionName) which will be used in the debug output.
                $name = ( $name === false and $id === false ) ?  $functionName : $name;
                // To uniquely identify this accumulator.
                $id = $id === false ? uniqID( $functionName . '_' ): $id;

                eZDebug::accumulatorStart( $id, 'Debug-Accumulator', $name );

                if ( is_array( $children ) )
                {
                    foreach ( array_keys( $children ) as $childKey )
                    {
                        $child =& $children[$childKey];
                        $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                    }
                }

                eZDebug::accumulatorStop( $id, 'Debug-Accumulator', $name );

            } break;

            case $this->LogName:
            {
                $parameters = $functionParameters;

                if ( isset( $parameters['var'] ) )
                    $var = $tpl->elementValue( $parameters['var'], $rootNamespace, $currentNamespace, $functionPlacement );
                if ( isset( $parameters['msg'] ) )
                    $msg = $tpl->elementValue( $parameters['msg'], $rootNamespace, $currentNamespace, $functionPlacement );

                if ( isset( $var ) && isset( $msg ) )
                    eZDebug::writeDebug( $var, $msg );
                elseif ( isset( $msg ) )
                    eZDebug::writeDebug( $msg );
                elseif ( isset( $var ) )
                    eZDebug::writeDebug( $var );
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

                    if ( is_array( $children ) )
                    {
                        foreach ( array_keys( $children ) as $childKey )
                        {
                            $child =& $children[$childKey];
                            $tpl->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                        }
                    }

                    xdebug_stop_trace();
                }
                elseif ( is_array( $children ) )
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
        return $this->attributeList();
    }

    /// \privatesection
    /// Name of the function
    public $DebugName;
    public $AppendDebugName;
    public $OnceName;
}

?>
