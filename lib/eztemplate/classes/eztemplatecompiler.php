<?php
//
// Definition of eZTemplateCompiler class
//
// Created on: <06-Dec-2002 14:17:10 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztemplatecompiler.php
*/

/*!
  \class eZTemplateCompiler eztemplatecompiler.php
  \brief Creates compiled PHP code from templates to speed up template usage.

   Various optimizations that can be done are:

    Data:
    - Is constant, generate static data
    - Is variable, generate direct variable extraction
    - Has operators
    - Has attributes

    Attributes:
    - Is constant, generate static data

    Operators:
    - Supports input
    - Supports output
    - Supports parameters
    - Generates static data (true, false)
    - Custom PHP code
    - Modifies template variables, if possible name which ones. Allows
      for caching of variables in the script.

    Functions:
    - Supports parameters
    - Supports children (set? no, section? yes)
    - Generates static data (ldelim,rdelim)
    - Children usage, no result(set-block) | copy(let,default) | dynamic(conditional, repeated etc.)
    - Children tree, requires original tree | allows custom processing
    - Custom PHP code
    - Deflate/transform tree, create new non-nested tree (let, default)
    - Modifies template variables, if possible name which ones. Allows
      for caching of variables in the script.
*/

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/eztemplate/classes/eztemplatenodetool.php' );

define( 'EZ_TEMPLATE_COMPILE_CODE_DATE', 1074699607 );

class eZTemplateCompiler
{
    /*!
     Constructor
    */
    function eZTemplateCompiler()
    {
    }

    /*!
     \static
     \return true if template compiling is enabled.
     \note To change this setting edit settings/site.ini and locate the group TemplateSettings and the entry TemplateCompile.
    */
    function isCompilationEnabled()
    {
        if ( isset( $GLOBALS['eZSiteBasics'] ) )
        {
            $siteBasics = $GLOBALS['eZSiteBasics'];
            if ( $siteBasics['no-cache-adviced'] )
            {
                return false;
            }
        }

        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $compilationEnabled = $ini->variable( 'TemplateSettings', 'TemplateCompile' ) == 'enabled';
        return $compilationEnabled;
    }

    /*!
     \static
     \return true if template compilation should include comments.
    */
    function isCommentsEnabled()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $commentsEnabled = $ini->variable( 'TemplateSettings', 'CompileComments' ) == 'enabled';
        return $commentsEnabled;
    }

    /*!
     \static
     \return true if template compilation should include debug accumulators.
    */
    function isAccumulatorsEnabled()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $enabled = $ini->variable( 'TemplateSettings', 'CompileAccumulators' ) == 'enabled';
        return $enabled;
    }

    /*!
     \static
     \return true if template compilation should include debug timing points.
    */
    function isTimingPointsEnabled()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $enabled = $ini->variable( 'TemplateSettings', 'CompileTimingPoints' ) == 'enabled';
        return $enabled;
    }

    /*!
     \static
     \return true if template compilation should include comments.
    */
    function isNodePlacementEnabled()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $nodePlacementEnabled = $ini->variable( 'TemplateSettings', 'CompileNodePlacements' ) == 'enabled';
        return $nodePlacementEnabled;
    }

    /*!
     \static
     \return true if the compiled template execution is enabled.
    */
    function isExecutionEnabled()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $execution = $ini->variable( 'TemplateSettings', 'CompileExecution' ) == 'enabled';
        return $execution;
    }

    /*!
     \static
     \return true if template compilation should always be run even if a sufficient compilation already exists.
    */
    function alwaysGenerate()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $alwaysGenerate = $ini->variable( 'TemplateSettings', 'CompileAlwaysGenerate' ) == 'enabled';
        return $alwaysGenerate;
    }

    /*!
     \static
     \return true if template node tree named \a $treeName should be included the compiled template.
    */
    function isTreeEnabled( $treeName )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $treeList = $ini->variable( 'TemplateSettings', 'CompileIncludeNodeTree' );
        return in_array( $treeName, $treeList );
    }

    /*!
     \static
     \return the directory for compiled templates.
    */
    function compilationDirectory()
    {
        $compilationDirectory =& $GLOBALS['eZTemplateCompilerDirectory'];
        if ( !isset( $compilationDirectory ) )
        {
            include_once( 'lib/ezutils/classes/ezdir.php' );
            include_once( 'lib/ezutils/classes/ezsys.php' );
            $compilationDirectory = eZDir::path( array( eZSys::cacheDirectory(), 'template/compiled' ) );
        }
        return $compilationDirectory;
    }

    /*!
     Creates the name for the compiled template and returns it.
     The name conists of the md5 of the key and charset with the original filename appended.
    */
    function compilationFilename( $key, $resourceData )
    {
        $internalCharset = eZTextCodec::internalCharset();
        $templateFilepath = $resourceData['template-filename'];
        $extraName = '';
        if ( preg_match( "#^.+/(.*)\.tpl$#", $templateFilepath, $matches ) )
            $extraName = '-' . $matches[1];
        else if ( preg_match( "#^(.*)\.tpl$#", $templateFilepath, $matches ) )
            $extraName = '-' . $matches[1];
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . $extraName . '.php';
        return $cacheFileName;
    }

    /*!
     \static
     \return true if the compiled template with the key \a $key exists.
             A compiled template is found usable when it exists and has a timestamp
             higher or equal to \a $timestamp.
    */
    function hasCompiledTemplate( $key, $timestamp, &$resourceData )
    {
        if ( !eZTemplateCompiler::isCompilationEnabled() )
            return false;
        if ( eZTemplateCompiler::alwaysGenerate() )
            return false;

        $cacheFileName = eZTemplateCompiler::compilationFilename( $key, $resourceData );

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTemplateCompiler::compilationDirectory(), $cacheFileName );
        $canRestore = $php->canRestore( $timestamp );
        $uri = false;
        if ( $canRestore )
            eZDebugSetting::writeDebug( 'eztemplate-compile', "Cache hit for uri '$uri' with key '$key'", 'eZTemplateCompiler::hasCompiledTemplate' );
        else
            eZDebugSetting::writeDebug( 'eztemplate-compile', "Cache miss for uri '$uri' with key '$key'", 'eZTemplateCompiler::hasCompiledTemplate' );
        return $canRestore;
    }

    /*!
     Tries to execute the compiled template and returns \c true if succsesful.
     Returns \c false if caching is disabled or the compiled template could not be executed.
    */
    function executeCompilation( &$tpl, &$textElements, $key, &$resourceData,
                                 $rootNamespace, $currentNamespace )
    {
        if ( !eZTemplateCompiler::isCompilationEnabled() )
            return false;
        if ( !eZTemplateCompiler::isExecutionEnabled() )
            return false;
        $cacheFileName = eZTemplateCompiler::compilationFilename( $key, $resourceData );

        $directory = eZTemplateCompiler::compilationDirectory();
        $phpScript = eZDir::path( array( $directory, $cacheFileName ) );
        if ( file_exists( $phpScript ) )
        {
            $text = false;
            $helperStatus = eZTemplateCompiler::executeCompilationHelper( $phpScript, $text,
                                                                          $tpl, $key, $resourceData,
                                                                          $rootNamespace, $currentNamespace );
            if ( $helperStatus )
            {
                $textElements[] = $text;
                return true;
            }
            else
                eZDebug::writeError( "Failed executing compiled template '$phpScript'", 'eZTemplateCompiler::executeCompilation' );
        }
        else
            eZDebug::writeError( "Unknown compiled template '$phpScript'", 'eZTemplateCompiler::executeCompilation' );
        return false;
    }

    /*!
     Helper function for executeCompilation. Will execute the script \a $phpScript and
     set the result text in \a $text.
     The parameters \a $tpl, \a $resourceData, \a $rootNamespace and \a $currentNamespace
     are passed to the executed template compilation script.
     \return true if a text result was created.
    */
    function executeCompilationHelper( $phpScript, &$text,
                                       &$tpl, $key, &$resourceData,
                                       $rootNamespace, $currentNamespace )
    {
        include( $phpScript );
        if ( isset( $text ) )
        {
            return true;
        }
        return false;
    }

    /*!
     \static
     Generates the cache which will be used for handling optimized processing using the key \a $key.
     \return false if the cache does not exist.
    */
    function compileTemplate( &$tpl, $key, &$resourceData )
    {
        if ( !eZTemplateCompiler::isCompilationEnabled() )
            return false;
        $cacheFileName = eZTemplateCompiler::compilationFilename( $key, $resourceData );

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $rootNode =& $resourceData['root-node'];
        if ( !$rootNode )
            return false;

        $useComments = eZTemplateCompiler::isCommentsEnabled();

        eZTemplateCompiler::createCommonCompileTemplate();

        $php = new eZPHPCreator( eZTemplateCompiler::compilationDirectory(), $cacheFileName );
        $php->addComment( 'URI:       ' . $resourceData['uri'] );
        $php->addComment( 'Filename:  ' . $resourceData['template-filename'] );
        $php->addComment( 'Timestamp: ' . $resourceData['time-stamp'] . ' (' . date( 'D M j G:i:s T Y', $resourceData['time-stamp'] ) . ')' );
        if ( $useComments )
        {
            $templateFilename = $resourceData['template-filename'];
            if ( file_exists( $templateFilename ) )
            {
                $fd = fopen( $templateFilename, 'rb' );
                if ( $fd )
                {
                    $templateText = fread( $fd, filesize( $templateFilename ) );
                    $php->addComment( "Original code:\n" . $templateText );
                    fclose( $fd );
                }
            }
        }
        $php->addVariable( 'eZTemplateCompilerCodeDate', EZ_TEMPLATE_COMPILE_CODE_DATE );
        $php->addInclude( eZTemplateCompiler::compilationDirectory() . '/common.php' );
        $php->addSpace();

        if ( eZTemplateCompiler::isAccumulatorsEnabled() )
            $php->addCodePiece( "eZDebug::accumulatorStart( 'template_compiled_execution', 'template_total', 'Template compiled execution', true );\n" );
        if ( eZTemplateCompiler::isTimingPointsEnabled() )
            $php->addCodePiece( "eZDebug::addTimingPoint( 'Script start $cacheFileName' );\n" );

        $php->addCodePiece( "\$vars =& \$tpl->Variables;\n" );
        $php->addSpace();

        $php->addVariable( 'text', '' );
        $php->addSpace();

        $transformedTree = array();
        eZTemplateCompiler::processNodeTransformation( $useComments, $php, $tpl, $rootNode, $resourceData, $transformedTree );

        $staticTree = array();
        eZTemplateCompiler::processStaticOptimizations( $useComments, $php, $tpl, $transformedTree, $resourceData, $staticTree );

        $combinedTree = array();
        eZTemplateCompiler::processNodeCombining( $useComments, $php, $tpl, $staticTree, $resourceData, $combinedTree );

        $finalTree = $combinedTree;
        if ( !eZTemplateCompiler::isNodePlacementEnabled() )
            eZTemplateCompiler::processRemoveNodePlacement( $finalTree );

        eZTemplateCompiler::generatePHPCode( $useComments, $php, $tpl, $finalTree, $resourceData );

        if ( eZTemplateCompiler::isTreeEnabled( 'final' ) )
            $php->addVariable( 'finalTree', $finalTree, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
        if ( eZTemplateCompiler::isTreeEnabled( 'combined' ) )
            $php->addVariable( 'combinedTree', $combinedTree, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
        if ( eZTemplateCompiler::isTreeEnabled( 'static' ) )
            $php->addVariable( 'staticTree', $staticTree, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
        if ( eZTemplateCompiler::isTreeEnabled( 'transformed' ) )
            $php->addVariable( 'transformedTree', $transformedTree, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
        if ( eZTemplateCompiler::isTreeEnabled( 'original' ) )
            $php->addVariable( 'originalTree', $rootNode, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );

        if ( eZTemplateCompiler::isTimingPointsEnabled() )
            $php->addCodePiece( "eZDebug::addTimingPoint( 'Script end $cacheFileName' );\n" );
        if ( eZTemplateCompiler::isAccumulatorsEnabled() )
            $php->addCodePiece( "eZDebug::accumulatorStop( 'template_compiled_execution', true );\n" );

        $php->store();

        return true;
    }

    /*!
     Creates the common.php file which has common functions for compiled templates.
     If the file already exists if will not create it.
    */
    function createCommonCompileTemplate()
    {
        $php = new eZPHPCreator( eZTemplateCompiler::compilationDirectory(), 'common.php' );
        if ( $php->exists() )
            return;

        $php->addComment( "This file contains functions which are common to all compiled templates.\n\n" .
                          'NOTE: This file is autogenerated and should not be modified, any changes will be lost!' );
        $php->addSpace();
        $lbracket = '{';
        $rbracket = '}';
        $initText = "if ( !function_exists( 'compiledfetchvariable' ) )
$lbracket
    function compiledFetchVariable( &\$vars, \$namespace, \$name )
    $lbracket
        \$exists = ( array_key_exists( \$namespace, \$vars ) and
                    array_key_exists( \$name, \$vars[\$namespace] ) );
        if ( \$exists )
        $lbracket
            \$var = \$vars[\$namespace][\$name];
        $rbracket
        else
            \$var = null;
        return \$var;
    $rbracket
$rbracket
if ( !function_exists( 'compiledfetchtext' ) )
$lbracket
    function compiledFetchText( &\$tpl, \$rootNamespace, \$currentNamespace, \$namespace, &\$var )
    $lbracket
        \$text = '';
        \$tpl->appendElement( \$text, \$var, \$rootNamespace, \$currentNamespace );
        return \$text;
    $rbracket
$rbracket
if ( !function_exists( 'compiledfetchattribute' ) )
$lbracket
    function compiledFetchAttribute( &\$value, \$attributeValue )
    $lbracket
        if ( !is_null( \$attributeValue ) )
        $lbracket
            if ( !is_numeric( \$attributeValue ) and
                 !is_string( \$attributeValue ) and
                 !is_bool( \$attributeValue ) )
                return null;
            if ( is_array( \$value ) )
            $lbracket
                if ( isset( \$value[\$attributeValue] ) )
                $lbracket
                    unset( \$tempValue );
                    \$tempValue = \$value[\$attributeValue];
                    return \$tempValue;
                $rbracket
            $rbracket
            else if ( is_object( \$value ) )
            $lbracket
                if ( method_exists( \$value, \"attribute\" ) and
                     method_exists( \$value, \"hasattribute\" ) )
                $lbracket
                    if ( \$value->hasAttribute( \$attributeValue ) )
                    $lbracket
                        unset( \$tempValue );
                        \$tempValue = \$value->attribute( \$attributeValue );
                        return \$tempValue;
                    $rbracket
                $rbracket
            $rbracket
        $rbracket
        return null;
    $rbracket
$rbracket
";
        $php->addCodePiece( $initText );
        $php->store();
    }

    /*!
     Generates the PHP code defined in the template node tree \a $node.
     The code is generated using the php creator specified in \a $php.
    */

    function generatePHPCode( $useComments, &$php, &$tpl, &$node, &$resourceData )
    {
        $parameters = array();
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            $children = $node[1];
            if ( $children )
            {
                eZTemplateCompiler::generatePHPCodeChildren( $useComments, $php, $tpl, $children, $resourceData, $parameters );
            }
        }
        else
            $tpl->error( 'generatePHPCode', "Unknown root type $nodeType, should be " . EZ_TEMPLATE_NODE_ROOT );
        $php->addSpace();
    }

    /*!
     Generates the PHP code for all node children specified in \a $nodeChildren.
     \sa generatePHPCode
    */
    function generatePHPCodeChildren( $useComments, &$php, &$tpl, &$nodeChildren, &$resourceData, &$parameters )
    {
        foreach ( $nodeChildren as $node )
        {
            $newNode = false;
            $nodeType = $node[0];
            if ( $nodeType > EZ_TEMPLATE_NODE_USER_CUSTOM )
            {
                // Do custom nodes
            }
            else if ( $nodeType > EZ_TEMPLATE_NODE_INTERNAL )
            {
                // Do custom internal nodes
                if ( $nodeType == EZ_TEMPLATE_NODE_INTERNAL_CODE_PIECE )
                {
                    $codePiece = $node[1];
                    $php->addCodePiece( $codePiece );
                }
                else if ( $nodeType == EZ_TEMPLATE_NODE_INTERNAL_VARIABLE_UNSET )
                {
                    $variableName = $node[1];
                    $php->addVariableUnset( $variableName );
                }
                else if ( $nodeType == EZ_TEMPLATE_NODE_INTERNAL_NAMESPACE_CHANGE )
                {
                    $variableData = $node[1];
                    $namespaceData = array( 'variable' => 'oldNamespace',
                                            'counter' => 0 );
                    if ( isset( $parameters['namespace-data'] ) )
                    {
                        $parameters['namespace-data']['counter']++;
                        $namespaceData = $parameters['namespace-data'];
                    }
                    else
                        $parameters['namespace-data'] = $namespaceData;
                    $namespaceVariable = $namespaceData['variable'];
                    $namespaceVariable .= $namespaceData['counter'];
                    $php->addCodePiece( "\$$namespaceVariable = \$currentNamespace;\n" );
                    $tmpNodes = array( array( EZ_TEMPLATE_NODE_VARIABLE,
                                              false,
                                              $variableData,
                                              false,
                                              array( 'variable-name' => 'currentNamespace',
                                                     'text-result' => false ) ) );
                    eZTemplateCompiler::generatePHPCodeChildren( $useComments, $php, $tpl, $tmpNodes, $resourceData, $parameters );
                }
                else if ( $nodeType == EZ_TEMPLATE_NODE_INTERNAL_NAMESPACE_RESTORE )
                {
                    if ( isset( $parameters['namespace-data'] ) )
                    {
                        $namespaceData = $parameters['namespace-data'];
                        $namespaceVariable = $namespaceData['variable'];
                        $namespaceVariable .= $namespaceData['counter'];
                        $php->addCodePiece( "\$currentNamespace = \$$namespaceVariable;\n" );
                        $parameters['namespace-data']['counter']--;
                    }
                    else
                        eZDebug::writeError( "Expected previous 'namespace-data' but found none",
                                             'eZTemplateCompiler::generatePHPCodeChildren' );
                }
                else
                    eZDebug::writeWarning( "Unknown internal template node type $nodeType, ignoring node for code generation",
                                           'eZTemplateCompiler:generatePHPCodeChildren' );
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
            {
                $children = $node[1];
                if ( $children )
                {
                    eZTemplateCompiler::generatePHPCodeChildren( $useComments, $php, $tpl, $children, $resourceData, $parameters );
                }
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_TEXT )
            {
                $text = $node[2];
                $variablePlacement = $node[3];
                $originalText = eZTemplateCompiler::fetchTemplatePiece( $variablePlacement );
                if ( $useComments )
                {
                    $php->addComment( "Text start:" );
                    $php->addComment( $originalText );
                    $php->addComment( "Text end:" );
                }
                $php->addVariable( 'text', $text, EZ_PHPCREATOR_VARIABLE_APPEND_TEXT );
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_VARIABLE )
            {
                $variableData = $node[2];
                $variablePlacement = $node[3];
                $variableParameters = array();
                if ( isset( $node[4] ) and
                     $node[4] )
                    $variableParameters = $node[4];
                $variableParameters = array_merge( array( 'variable-name' => 'var',
                                                          'text-result' => true ),
                                                   $variableParameters );
                $dataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                           $variableData, $variablePlacement,
                                                                           $resourceData );
                $newNode = $node;
                $newNode[1] = false;
                if ( $useComments )
                {
                    $php->addComment( "Variable data: " .
                                      "Is constant: " . ( $dataInspection['is-constant'] ? 'Yes' : 'No' ) .
                                      " Is variable: " . ( $dataInspection['is-variable'] ? 'Yes' : 'No' ) .
                                      " Has attributes: " . ( $dataInspection['has-attributes'] ? 'Yes' : 'No' ) .
                                      " Has operators: " . ( $dataInspection['has-operators'] ? 'Yes' : 'No' )
                                      );
                    $originalText = eZTemplateCompiler::fetchTemplatePiece( $variablePlacement );
                    $php->addComment( '{' . $originalText . '}' );
                }
                $generatedVariableName = $variableParameters['variable-name'];
                eZTemplateCompiler::generateVariableCode( $php, $tpl, $node, $dataInspection,
                                                              array( 'variable' => $generatedVariableName,
                                                                     'counter' => 0 ) );
                if ( $variableParameters['text-result'] )
                {
                    $php->addCodePiece( "while ( is_object( \$$generatedVariableName ) and method_exists( 'templateValue' ) )
{
    \$$generatedVariableName =& \$$generatedVariableName" . "->templateValue();
}
if ( is_object( \$$generatedVariableName ) )
    \$text .= compiledFetchText( \$tpl, \$rootNamespace, \$currentNamespace, \$namespace, \$$generatedVariableName );
else
    \$text .= \$$generatedVariableName;
unset( \$$generatedVariableName );\n" );
                }
                unset( $dataInspection );
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_FUNCTION )
            {
                $functionChildren = $node[1];
                $functionName = $node[2];
                $functionParameters = $node[3];
                $functionPlacement = $node[4];

                $newNode = array( $nodeType,
                                  false,
                                  $functionName,
                                  $functionParameters,
                                  $functionPlacement );

                $parameterText = 'No parameters';
                if ( $functionParameters )
                {
                    $parameterText = "Parameters: ". implode( ', ', array_keys( $functionParameters ) );
                }
                if ( $useComments )
                {
                    $php->addComment( "Function: $functionName, $parameterText" );
                    $originalText = eZTemplateCompiler::fetchTemplatePiece( $functionPlacement );
                    $php->addComment( '{' . $originalText . '}' );
                }
                if ( isset( $node[5] ) )
                {
                    $functionHook = $node[5];
                    $functionHookCustomFunction = $functionHook['function'];
                    if ( $functionHookCustomFunction )
                    {
                        $functionHookCustomFunction = array_merge( array( 'add-function-name' => false,
                                                                          'add-hook-name' => false,
                                                                          'add-template-handler' => true,
                                                                          'add-function-hook-data' => false,
                                                                          'add-function-parameters' => true,
                                                                          'add-function-placement' => false,
                                                                          'add-calculated-namespace' => false,
                                                                          'add-namespace' => true,
                                                                          'add-input' => false,
                                                                          'return-value' => false ),
                                                                   $functionHookCustomFunction );
                        if ( !isset( $parameters['hook-result-variable-counter'][$functionName] ) )
                            $parameters['hook-result-variable-counter'][$functionName] = 0;
                        if ( $functionHookCustomFunction['return-value'] )
                            $parameters['hook-result-variable-counter'][$functionName]++;
                        $hookResultName = $functionName . 'Result' . $parameters['hook-result-variable-counter'][$functionName];
                        if ( $functionHookCustomFunction['add-input'] )
                            $parameters['hook-result-variable-counter'][$functionName]--;
                        $functionHookCustomFunctionName = $functionHookCustomFunction['name'];
                        $codeText = '';
                        if ( $functionHookCustomFunction['return-value'] )
                            $codeText = "\$$hookResultName = ";
                        if ( $functionHookCustomFunction['static'] )
                        {
                            $hookClassName = $functionHookCustomFunction['class-name'];
                            $codeText .= "$hookClassName::$functionHookCustomFunctionName( ";
                        }
                        else
                            $codeText .= "\$functionObject->$functionHookCustomFunctionName( ";
                        $codeTextLength = strlen( $codeText );

                        $functionNameText = $php->variableText( $functionName, 0 );
                        $functionChildrenText = $php->variableText( $functionChildren, $codeTextLength, 0, false );

                        $inputFunctionParameters = $functionParameters;
                        if ( $functionHookCustomFunction['add-calculated-namespace'] )
                            unset( $inputFunctionParameters['name'] );
                        $functionParametersText = $php->variableText( $inputFunctionParameters, $codeTextLength, 0, false );

                        $functionPlacementText = $php->variableText( $functionPlacement, $codeTextLength, 0, false );
                        $functionHookText = $php->variableText( $functionHook, $codeTextLength, 0, false );

                        $functionHookName = $functionHook['name'];
                        $functionHookNameText = $php->variableText( $functionHookName, 0 );

                        $codeParameters = array();
                        if ( $functionHookCustomFunction['add-function-name'] )
                            $codeParameters[] = $functionNameText;
                        if ( $functionHookCustomFunction['add-hook-name'] )
                            $codeParameters[] = $functionHookNameText;
                        if ( $functionHookCustomFunction['add-function-hook-data'] )
                            $codeParameters[] = $functionHookText;
                        if ( $functionHookCustomFunction['add-template-handler'] )
                            $codeParameters[] = "\$tpl";
                        if ( $functionHookCustomFunction['add-function-parameters'] )
                            $codeParameters[] = $functionParametersText;
                        if ( $functionHookCustomFunction['add-function-placement'] )
                            $codeParameters[] = $functionPlacementText;
                        if ( $functionHookCustomFunction['add-calculated-namespace'] )
                        {
                            $name = '';
                            if ( isset( $functionParameters['name'] ) )
                            {
                                $nameParameter = $functionParameters['name'];
                                $nameInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                                           $nameParameter, $functionPlacement,
                                                                                           $resourceData );
                                if ( $nameInspection['is-constant'] and
                                     !$nameInspection['is-variable'] and
                                     !$nameInspection['has-attributes'] and
                                     !$nameInspection['has-operators'] )
                                {
                                    $nameData = $nameParameter[0][1];
                                    $nameText = $php->variableText( $nameData, 0, 0, false );
                                    $php->addCodePiece( "if ( \$currentNamespace != '' )
    \$name = \$currentNamespace . ':' . $nameText;
else
    \$name = $nameText;\n" );
                                    $codeParameters[] = "\$name";
                                }
                                else
                                {
                                    $persistence = array();
                                    eZTemplateCompiler::generateVariableCode( $php, $tpl, $nameParameter, $nameInspection,
                                                                                  $persistence,
                                                                                  array( 'variable' => 'name',
                                                                                         'counter' => 0 ) );
                                    $php->addCodePiece( "if ( \$currentNamespace != '' )
{
    if ( \$name != '' )
        \$name = \"\$currentNamespace:\$name\";
    else
        \$name = \$currentNamespace;
}\n" );
                                    $codeParameters[] = "\$name";
                                }
                            }
                            else
                            {
                                $codeParameters[] = "\$currentNamespace";
                            }
                        }
                        if ( $functionHookCustomFunction['add-namespace'] )
                            $codeParameters[] = "\$rootNamespace, \$currentNamespace";
                        if ( $functionHookCustomFunction['add-input'] )
                            $codeParameters[] = "\$$hookResultName";
                        $codeText .= implode( ",\n" . str_repeat( ' ', $codeTextLength ),
                                              $codeParameters );
                        $codeText .= " );\n";
                        if ( $functionHookCustomFunction['static'] )
                        {
                            $hookFile = $functionHookCustomFunction['php-file'];
                            $hookFileText = $php->variableTexT( $hookFile, 0 );
                            $php->addCodePiece( "include_once( $hookFileText );\n" );
                        }
                        else
                            $php->addCodePiece( "\$functionObject =& \$tpl->fetchFunctionObject( $functionNameText );\n" );
                        $php->addCodePiece( $codeText );
                    }
                    else
                    {
                        $functionNameText = $php->variableText( $functionName, 0 );
                        $functionChildrenText = $php->variableText( $functionChildren, 52, 0, false );
                        $functionParametersText = $php->variableText( $functionParameters, 52, 0, false );
                        $functionPlacementText = $php->variableText( $functionPlacement, 52, 0, false );

                        $functionHookText = $php->variableText( $functionHook, 52, 0, false );
                        $functionHookName = $functionHook['name'];
                        $functionHookNameText = $php->variableText( $functionHookName, 0 );
                        $functionHookParameters = $functionHook['parameters'];
                        $php->addCodePiece( "\$functionObject =& \$tpl->fetchFunctionObject( $functionNameText );
\$hookResult = \$functionObject->templateHookProcess( $functionNameText, $functionHookNameText,
                                                    $functionHookText,
                                                    \$tpl,
                                                    $functionParametersText,
                                                    $functionPlacementText,
                                                    \$rootNamespace, \$currentNamespace );
" );
                    }
                }
                else
                {
                    $functionNameText = $php->variableText( $functionName, 0 );
                    $functionChildrenText = $php->variableText( $functionChildren, 22, 0, false );
                    $functionParametersText = $php->variableText( $functionParameters, 22, 0, false );
                    $functionPlacementText = $php->variableText( $functionPlacement, 22, 0, false );
                    $php->addCodePiece( "\$textElements = array();
\$tpl->processFunction( $functionNameText, \$textElements,
                       $functionChildrenText,
                       $functionParametersText,
                       $functionPlacementText,
                       \$rootNamespace, \$currentNamespace );
\$text .= implode( '', \$textElements );\n" );
                }
            }
            $php->addSpace();
        }
    }

    /*!
     Generates PHP code which will do namespace merging.
     The namespace to merge with is specified in \a $namespace and
     the scope of the merging is defined by \a $namespaceScope.
    */
    function generateMergeNamespaceCode( &$php, &$tpl, $namespace, $namespaceScope )
    {
        if ( $namespace != '' )
        {
            if ( $namespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_GLOBAL )
            {
                $php->addVariable( 'namespace', $namespace );
            }
            else if ( $namespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL )
            {
                $php->addCodePiece( "\$namespace = \$rootNamespace;
if ( \$namespace == '' )
    \$namespace = \"$namespace\";
else
    \$namespace .= ':$namespace';
" );
            }
            else if ( $namespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE )
            {
                $php->addCodePiece( "\$namespace = \$currentNamespace;
if ( \$namespace == '' )
    \$namespace = \"$namespace\";
else
    \$namespace .= ':$namespace';
" );
            }
        }
        else
        {
            if ( $namespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_GLOBAL )
            {
                $php->addVariable( 'namespace', '' );
            }
            else if ( $namespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL )
            {
                $php->addCodePiece( "\$namespace = \$rootNamespace;\n" );
            }
            else if ( $namespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE )
            {
                $php->addCodePiece( "\$namespace = \$currentNamespace;\n" );
            }
        }
    }

    /*!
     Generates PHP code for the variable node \a $node.
     Use generateVariableDataCode if you want to create code for arbitrary variable data structures.
    */
    function generateVariableCode( &$php, &$tpl, $node, $dataInspection,
                                   $parameters )
    {
        $variableData = $node[2];
        $persistence = array();
        eZTemplateCompiler::generateVariableDataCode( $php, $tpl, $variableData, $dataInspection, $persistence, $parameters );
    }

    /*!
     Generates PHP code for the variable tree structure in \a $variableData.
     The code will contain string, numeric and identifier assignment,
     variable lookup, attribute lookup and operator execution.
     Use generateVariableCode if you want to create code for a variable tree node.
    */
    function generateVariableDataCode( &$php, &$tpl, $variableData, $dataInspection, &$persistence, $parameters )
    {
        $variableAssignmentName = $parameters['variable'];
        $variableAssignmentCounter = $parameters['counter'];
        if ( $variableAssignmentCounter > 0 )
            $variableAssignmentName .= $variableAssignmentCounter;
        foreach ( $variableData as $variableDataItem )
        {
            $variableDataType = $variableDataItem[0];
            if ( $variableDataType == EZ_TEMPLATE_TYPE_STRING or
                 $variableDataType == EZ_TEMPLATE_TYPE_NUMERIC or
                 $variableDataType == EZ_TEMPLATE_TYPE_IDENTIFIER )
            {
                $dataValue = $variableDataItem[1];
                $dataText = $php->variableText( $dataValue, 0 );
                $php->addCodePiece( "\$$variableAssignmentName = $dataText;\n" );
            }
            else if ( $variableDataType == EZ_TEMPLATE_TYPE_VARIABLE )
            {
                $namespace = $variableDataItem[1][0];
                $namespaceScope = $variableDataItem[1][1];
                $variableName = $variableDataItem[1][2];
                eZTemplateCompiler::generateMergeNamespaceCode( $php, $tpl, $namespace, $namespaceScope );
                $variableNameText = $php->variableText( $variableName, 0 );
                $php->addCodePiece( "\$$variableAssignmentName = compiledFetchVariable( \$vars, \$namespace, $variableNameText );\n" );
            }
            else if ( $variableDataType == EZ_TEMPLATE_TYPE_ATTRIBUTE )
            {
                $newParameters = $parameters;
                $newParameters['counter'] += 1;
                eZTemplateCompiler::generateVariableDataCode( $php, $tpl, $variableDataItem[1], $dataInspection,
                                                                  $persistence, $newParameters );
                $newVariableAssignmentName = $newParameters['variable'];
                $newVariableAssignmentCounter = $newParameters['counter'];
                if ( $newVariableAssignmentCounter > 0 )
                    $newVariableAssignmentName .= $newVariableAssignmentCounter;
                $php->addCodePiece( "unset( \$tmp$variableAssignmentName );\n\$tmp$variableAssignmentName = compiledFetchAttribute( \$$variableAssignmentName, \$$newVariableAssignmentName );\nunset( \$$variableAssignmentName );\n\$$variableAssignmentName = \$tmp$variableAssignmentName;\nunset( \$tmp$variableAssignmentName );\n" );
// $php->addVariable( 'attr', $variableDataItem[1] );
            }
            else if ( $variableDataType == EZ_TEMPLATE_TYPE_OPERATOR )
            {
                $operatorParameters = $variableDataItem[1];
                $operatorName = $operatorParameters[0];
                $operatorParameters = array_splice( $operatorParameters, 1 );
                $operatorNameText = $php->variableText( $operatorName, 0 );
                $operatorParametersText = $php->variableText( $operatorParameters, 23, 0, false );
                $php->addCodePiece( "\$tpl->processOperator( $operatorNameText,
                       $operatorParametersText,
                       \$rootNamespace, \$currentNamespace, \$$variableAssignmentName, false, false );\n" );
            }
            else if ( $variableDataType == EZ_TEMPLATE_TYPE_VOID )
            {
            }
        }
    }

    /*!
     Iterates over the template node tree and tries to combine multiple static siblings
     into one element. The original tree is specified in \a $node and the new
     combined tree will be present in \a $newNode.
     \sa processNodeCombiningChildren
    */
    function processNodeCombining( $useComments, &$php, &$tpl, &$node, &$resourceData, &$newNode )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            $children = $node[1];
            $newNode[0] = $nodeType;
            $newNode[1] = false;
            if ( $children )
            {
                eZTemplateCompiler::processNodeCombiningChildren( $useComments, $php, $tpl, $children, $resourceData, $newNode );
            }
        }
        else
            $tpl->error( 'processNodeCombining', "Unknown root type $nodeType, should be " . EZ_TEMPLATE_NODE_ROOT );
    }

    /*!
     Does node combining on the children \a $nodeChildren.
     \sa processNodeCombining
    */
    function processNodeCombiningChildren( $useComments, &$php, &$tpl, &$nodeChildren, &$resourceData, &$parentNode )
    {
        $newNodeChildren = array();
        $lastNode = false;
        foreach ( $nodeChildren as $node )
        {
            $newNode = false;
            if ( !isset( $node[0] ) )
                continue;
            $nodeType = $node[0];
            if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
            {
                $children = $node[1];
                $newNode = array( $nodeType,
                                  false );
                if ( $children )
                {
                    eZTemplateCompiler::processNodeCombiningChildren( $useComments, $php, $tpl, $children, $resourceData, $newNode );
                }
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_TEXT )
            {
                $text = $node[2];
                $placement = $node[3];

                $newNode = array( $nodeType,
                                  false,
                                  $text,
                                  $placement );
                eZTemplateCompiler::combineStaticNodes( $tpl, $resourceData, $lastNode, $newNode );
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_VARIABLE )
            {
                $variableData = $node[2];
                $variablePlacement = $node[3];
                $variableParameters = false;
                $dataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                           $variableData, $variablePlacement,
                                                                           $resourceData );
                $newNode = $node;
                $newNode[1] = false;
                unset( $dataInspection );
                eZTemplateCompiler::combineStaticNodes( $tpl, $resourceData, $lastNode, $newNode );
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_FUNCTION )
            {
                $functionChildren = $node[1];
                $functionName = $node[2];
                $functionParameters = $node[3];
                $functionPlacement = $node[4];

                $newNode = array( $nodeType,
                                  false,
                                  $functionName,
                                  $functionParameters,
                                  $functionPlacement );
                if ( isset( $node[5] ) )
                    $newNode[5] = $node[5];

                if ( is_array( $functionChildren ) )
                {
                    eZTemplateCompiler::processNodeCombiningChildren( $useComments, $php, $tpl,
                                                                          $functionChildren, $resourceData, $newNode );
                }

            }
            else
                $newNode = $node;
            if ( $lastNode != false )
            {
                $newNodeChildren[] = $lastNode;
                $lastNode = false;
            }
            if ( $newNode != false )
                $lastNode = $newNode;
        }
        if ( $lastNode != false )
        {
            $newNodeChildren[] = $lastNode;
            $lastNode = false;
        }
        $parentNode[1] = $newNodeChildren;
    }

    /*!
     Tries to combine the node \a $lastNode and the node \a $newNode
     into one new text node. If possible the new node is created in \a $newNode
     and \a $lastNode will be set to \c false.
     Combining nodes only works for text nodes and variable nodes without
     variable lookup, attributes and operators.
    */
    function combineStaticNodes( &$tpl, &$resourceData, &$lastNode, &$newNode )
    {
        if ( $lastNode == false or
             $newNode == false )
            return false;
        $lastNodeType = $lastNode[0];
        $newNodeType = $newNode[0];
        if ( !in_array( $lastNodeType, array( EZ_TEMPLATE_NODE_TEXT,
                                              EZ_TEMPLATE_NODE_VARIABLE ) ) or
             !in_array( $newNodeType, array( EZ_TEMPLATE_NODE_TEXT,
                                              EZ_TEMPLATE_NODE_VARIABLE ) ) )
            return false;
        if ( $lastNodeType == EZ_TEMPLATE_NODE_VARIABLE )
        {
            $lastDataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                           $lastNode[2], $lastNode[3],
                                                                           $resourceData );
            if ( !$lastDataInspection['is-constant'] or
                 $lastDataInspection['is-variable'] or
                 $lastDataInspection['has-attributes'] or
                 $lastDataInspection['has-operators'] )
                return false;
            if ( isset( $lastNode[4] ) and
                 isset( $lastNode[4]['text-result'] ) and
                 !$lastNode[4]['text-result'] )
                return false;
        }
        if ( $newNodeType == EZ_TEMPLATE_NODE_VARIABLE )
        {
            $newDataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                          $newNode[2], $newNode[3],
                                                                          $resourceData );
            if ( !$newDataInspection['is-constant'] or
                 $newDataInspection['is-variable'] or
                 $newDataInspection['has-attributes'] or
                 $newDataInspection['has-operators'] )
                return false;
            if ( isset( $newNode[4] ) and
                 isset( $newNode[4]['text-result'] ) and
                 !$newNode[4]['text-result'] )
                return false;
        }
        $textElements = array();
        $lastNodeData = eZTemplateCompiler::staticNodeData( $lastNode );
        $newNodeData = eZTemplateCompiler::staticNodeData( $newNode );
        $tpl->appendElementText( $textElements, $lastNodeData, false, false );
        $tpl->appendElementText( $textElements, $newNodeData, false, false );
        $newData = implode( '', $textElements );
        $newPlacement = $lastNode[3];
        if ( !is_array( $newPlacement ) )
        {
            $newPlacement = $newNode[3];
        }
        else
        {
            $newPlacement[1][0] = $newNode[3][1][0]; // Line end
            $newPlacement[1][1] = $newNode[3][1][1]; // Column end
            $newPlacement[1][2] = $newNode[3][1][2]; // Position end
        }
        $lastNode = false;
        $newNode = array( EZ_TEMPLATE_NODE_TEXT,
                          false,
                          $newData,
                          $newPlacement );
    }

    /*!
     \return the static data for the node \a $node or \c false if
             no data could be fetched.
             Will only return data from text nodes and variables nodes
             without variable lookup, attribute lookup or operators.
    */
    function staticNodeData( $node )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_TEXT )
        {
            return $node[2];
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_VARIABLE )
        {
            $data = $node[2];
            if ( is_array( $data ) and
                 count( $data ) > 0 )
            {
                $dataType = $data[0][0];
                if ( $dataType == EZ_TEMPLATE_TYPE_STRING or
                     $dataType == EZ_TEMPLATE_TYPE_NUMERIC or
                     $dataType == EZ_TEMPLATE_TYPE_IDENTIFIER )
                {
                    return $data[0][1];
                }
            }
        }
        return null;
    }

    /*!
     Iterates over the items in the tree \a $node and tries to extract static data
     from operators which supports it.
    */
    function processStaticOptimizations( $useComments, &$php, &$tpl, &$node, &$resourceData, &$newNode )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            $children = $node[1];
            $newNode[0] = $nodeType;
            $newNode[1] = false;
            if ( $children )
            {
                $newNode[1] = array();
                foreach ( $children as $child )
                {
                    $newChild = array();
                    eZTemplateCompiler::processStaticOptimizations( $useComments, $php, $tpl, $child, $resourceData, $newChild );
                    $newNode[1][] = $newChild;
                }
            }
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_TEXT )
        {
            $text = $node[2];
            $placement = $node[3];

            $newNode[0] = $nodeType;
            $newNode[1] = false;
            $newNode[2] = $text;
            $newNode[3] = $placement;
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_VARIABLE )
        {
            $variableData = $node[2];
            $variablePlacement = $node[3];
            $dataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                       $variableData, $variablePlacement,
                                                                       $resourceData );
            if ( isset( $dataInspection['new-data'] ) )
            {
                $variableData = $dataInspection['new-data'];
            }
            $newNode = $node;
            $newNode[1] = false;
            $newNode[2] = $variableData;
            unset( $dataInspection );
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_FUNCTION )
        {
            $functionChildren = $node[1];
            $functionName = $node[2];
            $functionParameters = $node[3];
            $functionPlacement = $node[4];

            $newFunctionChildren = array();
            if ( is_array( $functionChildren ) )
            {
                foreach ( $functionChildren as $functionChild )
                {
                    $newChild = array();
                    eZTemplateCompiler::processStaticOptimizations( $useComments, $php, $tpl,
                                                                        $functionChild, $resourceData, $newChild );
                    $newFunctionChildren[] = $newChild;
                }
                $functionChildren = $newFunctionChildren;
            }

            $newFunctionParameters = array();
            if ( $functionParameters )
            {
                foreach ( $functionParameters as $functionParameterName => $functionParameterData )
                {
                    $dataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                                   $functionParameterData, false,
                                                                                   $resourceData );
                    if ( isset( $dataInspection['new-data'] ) )
                    {
                        $functionParameterData = $dataInspection['new-data'];
                    }
                    $newFunctionParameters[$functionParameterName] = $functionParameterData;
                }
                $functionParameters = $newFunctionParameters;
            }

            $newNode[0] = $nodeType;
            $newNode[1] = $functionChildren;
            $newNode[2] = $functionName;
            $newNode[3] = $functionParameters;
            $newNode[4] = $functionPlacement;
            if ( isset( $node[5] ) )
                $newNode[5] = $node[5];
        }
        else
            $newNode = $node;
    }

    /*!
     Iterates over the template node tree \a $node and returns a new transformed
     tree in \a $newNode.
     \sa processNodeTransformationRoot, processNodeTransformationChild
    */
    function processNodeTransformation( $useComments, &$php, &$tpl, &$node, &$resourceData, &$newNode )
    {
        $newNode = eZTemplateCompiler::processNodeTransformationRoot( $useComments, $php, $tpl, $node, $resourceData );
    }

    /*!
     Iterates over the children of the root node \a $node and does transformation on them.
     \sa processNodeTransformation, processNodeTransformationChild
    */
    function processNodeTransformationRoot( $useComments, &$php, &$tpl, &$node, &$resourceData )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            $children = $node[1];
            $newNode = array( $nodeType,
                              false );
            if ( $children )
            {
                $newChildren = array();
                foreach ( $children as $childNode )
                {
                    $newChildNode = eZTemplateCompiler::processNodeTransformationChild( $useComments, $php, $tpl, $childNode, $resourceData );
                    if ( !$newChildNode )
                        $newChildren[] = $childNode;
                    else if ( !is_array( $newChildNode ) )
                        $newChildren[] = $newChildNode;
                    else
                        $newChildren = array_merge( $newChildren, $newChildNode );
                }
                if ( count( $newChildren ) > 0 )
                    $newNode[1] = $newChildren;
            }
            return $newNode;
        }
        else
            $tpl->error( 'processNodeTransformation', "Unknown root type $nodeType, should be " . EZ_TEMPLATE_NODE_ROOT );
        return false;
    }

    /*!
     Iterates over the children of the function node \a $node and transforms the tree.
     If the node is not a function it will return \c false.
     \sa processNodeTransformationRoot, processNodeTransformationChild
    */
    function processNodeTransformationChild( $useComments, &$php, &$tpl, &$node, &$resourceData )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_FUNCTION )
        {
            $functionChildren = $node[1];
            $functionName = $node[2];
            $functionParameters = $node[3];
            $functionPlacement = $node[4];
            if ( !isset( $tpl->Functions[$functionName] ) )
                return false;

            if ( is_array( $tpl->Functions[$functionName] ) )
            {
                $tpl->loadAndRegisterOperators( $tpl->Functions[$functionName] );
            }
            $functionObject =& $tpl->Functions[$functionName];
            if ( is_object( $functionObject ) )
            {
                $hasTransformationSupport = false;
                $transformChildren = true;
                if ( method_exists( $functionObject, 'functionTemplateHints' ) )
                {
                    $hints = $functionObject->functionTemplateHints();
                    if ( isset( $hints[$functionName] ) and
                         isset( $hints[$functionName]['tree-transformation'] ) and
                         $hints[$functionName]['tree-transformation'] )
                        $hasTransformationSupport = true;
                    if ( isset( $hints[$functionName] ) and
                         isset( $hints[$functionName]['transform-children'] ) )
                        $transformChildren = $hints[$functionName]['transform-children'];
                }
                if ( $hasTransformationSupport and
                     method_exists( $functionObject, 'templateNodeTransformation' ) )
                {
                    if ( $transformChildren and
                         $functionChildren )
                    {
                        $newChildren = array();
                        foreach ( $functionChildren as $childNode )
                        {
                            $newChildNode = eZTemplateCompiler::processNodeTransformationChild( $useComments, $php, $tpl, $childNode, $resourceData );
                            if ( !$newChildNode )
                                $newChildren[] = $childNode;
                            else if ( !is_array( $newChildNode ) )
                                $newChildren[] = $newChildNode;
                            else
                                $newChildren = array_merge( $newChildren, $newChildNode );
                        }
                        if ( count( $newChildren ) > 0 )
                            $node[1] = $newChildren;
                    }

                    $newNodes = $functionObject->templateNodeTransformation( $functionName, $node,
                                                                             $tpl, $resourceData );
                    if ( !$newNodes )
                    {
                        $node[1] = $functionChildren;
                        return false;
                        return $node;
                    }
                    return $newNodes;
                }
            }
            return false;
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            return eZTemplateCompiler::processNodeTransformationRoot( $useComments, $php, $tpl, $node, $resourceData );
        }
        else
            return false;
    }

    /*!
     Iterates over the node tree and removes all placement information.
    */
    function processRemoveNodePlacement( &$node )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            $nodeChildren =& $node[1];
            for ( $i = 0; $i < count( $nodeChildren ); ++$i )
            {
                $nodeChild =& $nodeChildren[$i];
                eZTemplateCompiler::processRemoveNodePlacement( $nodeChild );
            }
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_TEXT )
        {
            $node[3] = false;
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_VARIABLE )
        {
            $node[3] = false;
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_FUNCTION )
        {
            $node[4] = false;
            $nodeChildren =& $node[1];
            if ( $nodeChildren )
            {
                for ( $i = 0; $i < count( $nodeChildren ); ++$i )
                {
                    $nodeChild =& $nodeChildren[$i];
                    eZTemplateCompiler::processRemoveNodePlacement( $nodeChild );
                }
            }
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_OPERATOR )
        {
        }
    }

    /*!
     Looks over the variable data \a $variableData and returns an array with
     information on the structure.
     The followin entries are generated.
     - is-constant - true if the variable data contains constant data like text and numerics
     - is-variable - true if the variable data is a variable lookup
     - has-operators - true if operators are present
     - has-attributes - true if attributes are used
    */
    function inspectVariableData( &$tpl, $variableData, $variablePlacement, &$resourceData )
    {
        $dataInspection = array( 'is-constant' => false,
                                 'is-variable' => false,
                                 'has-operators' => false,
                                 'has-attributes' => false );
        if ( !is_array( $variableData ) )
            return $dataInspection;
        $newVariableData = array();
        // Static optimizations, the following items are done:
        // - Recognize static data
        // - Extract static data, if possible, from operators
        // - Remove parameters and input which not be used.
        foreach ( $variableData as $variableItem )
        {
            $variableItemType = $variableItem[0];
            $variableItemData = $variableItem[1];
            $variableItemPlacement = $variableItem[2];
            if ( $variableItemType == EZ_TEMPLATE_TYPE_STRING or
                 $variableItemType == EZ_TEMPLATE_TYPE_IDENTIFIER )
            {
                $dataInspection['is-constant'] = true;
                $dataInspection['is-variable'] = false;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == EZ_TEMPLATE_TYPE_NUMERIC )
            {
                $dataInspection['is-constant'] = true;
                $dataInspection['is-variable'] = false;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == EZ_TEMPLATE_TYPE_VARIABLE )
            {
                $dataInspection['is-constant'] = false;
                $dataInspection['is-variable'] = true;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == EZ_TEMPLATE_TYPE_ATTRIBUTE )
            {
                $dataInspection['has-attributes'] = true;
                $newDataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                                  $variableItemData, $variableItemPlacement,
                                                                                  $resourceData );
                if ( isset( $newDataInspection['new-data'] ) )
                {
                    $variableItemData = $newDataInspection['new-data'];
                }
                $variableItem[1] = $variableItemData;
                unset( $newDataInspection );
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == EZ_TEMPLATE_TYPE_OPERATOR )
            {
                $dataInspection['has-operators'] = true;
                $operatorName = $variableItemData[0];
                $operatorHint = eZTemplateCompiler::operatorHint( $tpl, $operatorName );
                $newVariableItem = $variableItem;
                if ( $operatorHint )
                {
                    if ( !$operatorHint['input'] and
                         $operatorHint['output'] )
                        $newVariableData = array();
                    if ( !$operatorHint['parameters'] )
                        $newVariableItem[1] = array( $operatorName );
                    if ( $operatorHint['static'] )
                    {
                        $operatorStaticData = eZTemplateCompiler::operatorStaticData( $tpl, $operatorName );
                        $newVariableItem = eZTemplateCompiler::createStaticVariableData( $tpl, $operatorStaticData, $variableItemPlacement );
                        $dataInspection['is-constant'] = true;
                        $dataInspection['is-variable'] = false;
                        $dataInspection['has-operators'] = false;
                    }
                }
                if ( $newVariableItem[0] == EZ_TEMPLATE_TYPE_OPERATOR )
                {
                    $tmpVariableItem = $newVariableItem[1];
                    $newVariableItem[1] = array( $operatorName );
                    for ( $i = 1; $i < count( $tmpVariableItem ); ++$i )
                    {
                        $operatorParameter = $tmpVariableItem[$i];
                        $newDataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                                          $operatorParameter, false,
                                                                                          $resourceData );
                        if ( isset( $newDataInspection['new-data'] ) )
                        {
                            $operatorParameter = $newDataInspection['new-data'];
                        }
                        $newVariableItem[1][] = $operatorParameter;
                    }
                }
                $newVariableData[] = $newVariableItem;
            }
            else if ( $variableItemType == EZ_TEMPLATE_TYPE_VOID )
            {
                $tpl->warning( 'TemplateCompiler', "Void datatype should not be used, ignoring it" );
            }
            else
            {
                $tpl->warning( 'TemplateCompiler', "Unknown data type $variableItemType, ignoring it" );
            }
        }
        $dataInspection['new-data'] = $newVariableData;
        return $dataInspection;
    }

    /*!
     \return the operator hint for the operator \a $operatorName, or \c false if
             the operator does not exist or has no hints.
    */
    function operatorHint( &$tpl, $operatorName )
    {
        if ( is_array( $tpl->Operators[$operatorName] ) )
        {
            $tpl->loadAndRegisterOperators( $tpl->Operators[$operatorName] );
        }
        $operatorObject =& $tpl->Operators[$operatorName];
        $operatorHint = false;
        if ( is_object( $operatorObject ) )
        {
            if ( method_exists( $operatorObject, 'operatorTemplateHints' ) )
            {
                $operatorHintArray = $operatorObject->operatorTemplateHints();
                if ( isset( $operatorHintArray[$operatorName] ) )
                {
                    $operatorHint = $operatorHintArray[$operatorName];
                }
            }
        }
        return $operatorHint;
    }

    /*!
     \return static data from operators which support returning static data,
             or \c null if no static data could be extracted.
             The operator is specified in \a $operatorName.

    */
    function operatorStaticData( &$tpl, $operatorName )
    {
        if ( is_array( $tpl->Operators[$operatorName] ) )
        {
            $tpl->loadAndRegisterOperators( $tpl->Operators[$operatorName] );
        }
        $operatorObject =& $tpl->Operators[$operatorName];
        $operatorData = null;
        if ( is_object( $operatorObject ) )
        {
            if ( method_exists( $operatorObject, 'operatorCompiledStaticData' ) )
            {
                $operatorData = $operatorObject->operatorCompiledStaticData( $operatorName );
            }
        }
        return $operatorData;
    }

    /*!
     Creates a variable data element for the data \a $staticData and returns it.
     The type of element depends on the type of the data, strings and booleans
     are returned as EZ_TEMPLATE_TYPE_TEXT and EZ_TEMPLATE_TYPE_NUMERIC while other
     types are turned into text and returned as EZ_TEMPLATE_TYPE_TEXT.
    */
    function createStaticVariableData( &$tpl, $staticData, $variableItemPlacement )
    {
        if ( is_string( $staticData ) )
            return array( EZ_TEMPLATE_TYPE_TEXT,
                          $staticData,
                          $variableItemPlacement );
        else if ( is_bool( $staticData ) or is_numeric( $staticData ) )
            return array( EZ_TEMPLATE_TYPE_NUMERIC,
                          $staticData,
                          $variableItemPlacement );
        else
            return array( EZ_TEMPLATE_TYPE_TEXT,
                          "$staticData",
                          $variableItemPlacement );
    }

    /*!
     Opens the template files specified in \a $placementData
     and fetches the text portion defined by the
     start and end position. The text is returned or \c null if the
     text could not be fetched.
    */
    function fetchTemplatePiece( $placementData )
    {
        if ( !isset( $placementData[0] ) or
             !isset( $placementData[1] ) or
             !isset( $placementData[2] ) )
            return null;
        $file = $placementData[2];
        $startPosition = $placementData[0][2];
        $endPosition = $placementData[1][2];
        $length = $endPosition - $startPosition;
        if ( file_exists( $file ) )
        {
            $fd = fopen( $file, 'rb' );
            fseek( $fd, $startPosition );
            $text = fread( $fd, $length );
            fclose( $fd );
            return $text;
        }
        return null;
    }

}

?>
