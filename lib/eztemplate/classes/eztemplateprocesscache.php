<?php
//
// Definition of eZTemplateProcessCache class
//
// Created on: <06-Dec-2002 14:17:10 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file eztemplateprocesscache.php
*/

/*!
  \class eZTemplateProcessCache eztemplateprocesscache.php
  \brief The class eZTemplateProcessCache does

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/eztemplate/classes/eztemplatenodetool.php' );

define( 'EZ_TEMPLATE_PROCESS_CACHE_CODE_DATE', 1041857934 );

class eZTemplateProcessCache
{
    /*!
     Constructor
    */
    function eZTemplateProcessCache()
    {
    }

    /*!
     \static
     \return true if template process caching is enabled.
     \note To change this setting edit settings/site.ini and locate the group TemplateSettings and the entry ProcessCaching.
    */
    function isCacheEnabled()
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
        $cacheEnabled = $ini->variable( 'TemplateSettings', 'ProcessCaching' ) == 'enabled';
        return $cacheEnabled;
    }

    /*!
     \static
     \return the cache directory for process cache files.
    */
    function cacheDirectory()
    {
        $cacheDirectory =& $GLOBALS['eZTemplateProcessCacheDirectory'];
        if ( !isset( $cacheDirectory ) )
        {
            include_once( 'lib/ezutils/classes/ezdir.php' );
            include_once( 'lib/ezutils/classes/ezsys.php' );
            $cacheDirectory = eZDir::path( array( eZSys::cacheDirectory(), 'template/process' ) );
        }
        return $cacheDirectory;
    }

    /*!
     \static
     \return true if the cache with the key \a $key exists.
             A cache file is found restorable when it exists and has a timestamp
             higher or equal to \a $timestamp.
    */
    function hasProcessCache( $key, $timestamp )
    {
        if ( !eZTemplateProcessCache::isCacheEnabled() )
            return false;
//         return false;

        $internalCharset = eZTextCodec::internalCharset();
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTemplateProcessCache::cacheDirectory(), $cacheFileName );
        $canRestore = $php->canRestore( $timestamp );
        $uri = false;
        if ( $canRestore )
            eZDebugSetting::writeDebug( 'eztemplate-process-cache', "Cache hit for uri '$uri' with key '$key'", 'eZTemplateProcessCache::hasProcessCache' );
        else
            eZDebugSetting::writeDebug( 'eztemplate-process-cache', "Cache miss for uri '$uri' with key '$key'", 'eZTemplateProcessCache::hasProcessCache' );
        return $canRestore;
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
            $fd = fopen( $file, 'r' );
            fseek( $fd, $startPosition );
            $text = fread( $fd, $length );
            fclose( $fd );
            return $text;
        }
        return null;
    }

    function executeCache( &$tpl, &$textElements, $key, &$resourceData,
                           $rootNamespace, $currentNamespace )
    {
        if ( !eZTemplateProcessCache::isCacheEnabled() )
            return false;
//         return false;
        $internalCharset = eZTextCodec::internalCharset();
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $directory = eZTemplateProcessCache::cacheDirectory();
        $phpScript = eZDir::path( array( $directory, $cacheFileName ) );
        if ( file_exists( $phpScript ) )
        {
            $text = false;
            $helperStatus = eZTemplateProcessCache::executeCacheHelper( $phpScript, $text,
                                                                        $tpl, $key, $resourceData,
                                                                        $rootNamespace, $currentNamespace );
            if ( $helperStatus )
            {
                $textElements[] = $text;
                return true;
            }
            else
                eZDebug::writeError( "Failed executing process cache file '$phpScript'" );
        }
        else
            eZDebug::writeError( "Unknown process cache file '$phpScript'" );
        return false;
    }

    function executeCacheHelper( $phpScript, &$text,
                                 &$tpl, $key, &$resourceData,
                                 $rootNamespace, $currentNamespace )
    {
//         print( "root=$rootNamespace, current=$currentNamespace<br/>" );
        include( $phpScript );
        if ( isset( $text ) )
        {
//             print( "Text:<pre>$text</pre>Text end:" );
            return true;
        }
        return false;
    }

    /*!
     \static
     Generates the cache will be used for handling optimized processinging using the key \a $key.
     \return false if the cache does not exist.
    */
    function generateCache( &$tpl, $key, &$resourceData )
    {
        if ( !eZTemplateProcessCache::isCacheEnabled() )
            return false;
        $internalCharset = eZTextCodec::internalCharset();
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $rootNode =& $resourceData['root-node'];
        if ( !$rootNode )
            return false;

        $php = new eZPHPCreator( eZTemplateProcessCache::cacheDirectory(), $cacheFileName );
        $php->addComment( 'URI:       ' . $resourceData['uri'] );
        $php->addComment( 'Filename:  ' . $resourceData['template-filename'] );
        $php->addComment( 'Timestamp: ' . $resourceData['time-stamp'] . ' (' . date( 'D M j G:i:s T Y', $resourceData['time-stamp'] ) . ')' );
        $php->addComment( "Original code:\n" . $resourceData['text'] );
        $php->addVariable( 'eZTemplateProcessCacheCodeDate', EZ_TEMPLATE_PROCESS_CACHE_CODE_DATE );
        $php->addSpace();

        $php->addCodePiece( "eZDebug::accumulatorStart( 'template_process_cache', 'template_total', 'Template process cache' );" );

        $php->addCodePiece( "\$vars =& \$tpl->Variables;\n" );
        $lbracket = '{';
        $rbracket = '}';
        $initText = "if ( !function_exists( 'processfetchvariable' ) )
$lbracket
    function &processFetchVariable( &\$vars, \$namespace, \$name )
    $lbracket
        \$exists = ( array_key_exists( \$namespace, \$vars ) and
                    array_key_exists( \$name, \$vars[\$namespace] ) );
        if ( \$exists )
        $lbracket
            \$var =& \$vars[\$namespace][\$name];
        $rbracket
        else
            \$var = null;
        return \$var;
    $rbracket
$rbracket
if ( !function_exists( 'processfetchtext' ) )
$lbracket
    function processFetchText( &\$tpl, \$rootNamespace, \$currentNamespace, \$namespace, &\$var )
    $lbracket
        \$text = '';
        \$tpl->appendElement( \$text, \$var, \$rootNamespace, \$currentNamespace );
        return \$text;
    $rbracket
$rbracket
if ( !function_exists( 'processfetchattribute' ) )
$lbracket
    function processFetchAttribute( &\$value, \$attributeValue )
    $lbracket
        if ( !is_null( \$attributeValue ) )
        $lbracket
            if ( !is_numeric( \$attributeValue ) and
                 !is_string( \$attributeValue ) )
                return null;
            if ( is_array( \$value ) )
            $lbracket
                if ( isset( \$value[\$attributeValue] ) )
                $lbracket
                    unset( \$tempValue );
                    \$tempValue =& \$value[\$attributeValue];
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
                        \$tempValue =& \$value->attribute( \$attributeValue );
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
        $php->addSpace();

        $php->addVariable( 'text', '' );
        $php->addSpace();

        $transformedTree = array();
        eZTemplateProcessCache::processNodeTransformation( $php, $tpl, $rootNode, $resourceData, $transformedTree );

        $staticTree = array();
        eZTemplateProcessCache::processStaticOptimizations( $php, $tpl, $transformedTree, $resourceData, $staticTree );

        $combinedTree = array();
        eZTemplateProcessCache::processNodeCombining( $php, $tpl, $staticTree, $resourceData, $combinedTree );

        eZTemplateProcessCache::generatePHPCode( $php, $tpl, $combinedTree, $resourceData );

//         $php->addVariable( 'combinedTree', $combinedTree, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
//         $php->addVariable( 'staticTree', $staticTree, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
//         $php->addVariable( 'transformedTree', $transformedTree, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
//         $php->addVariable( 'originalTree', $rootNode, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );

        $php->addCodePiece( "eZDebug::accumulatorStop( 'template_process_cache' );" );

        $php->store();

        return true;
    }

    /*

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

    function generatePHPCode( &$php, &$tpl, &$node, &$resourceData )
    {
        $parameters = array();
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            $children = $node[1];
            if ( $children )
            {
                eZTemplateProcessCache::generatePHPCodeChildren( $php, $tpl, $children, $resourceData, $parameters );
            }
        }
        else
            $tpl->error( 'generatePHPCode', "Unknown root type $nodeType, should be " . EZ_TEMPLATE_NODE_ROOT );
        $php->addSpace();
    }

    function generateTextAppendCode( &$php, &$tpl, $text )
    {
        $php->addVariable( 'text', $text, EZ_PHPCREATOR_VARIABLE_APPEND_TEXT );
    }

    function generateVariableCode( &$php, &$tpl, $node, $dataInspection,
                                   $parameters )
    {
        $variableData = $node[2];
        $persistence = array();
        eZTemplateProcessCache::generateVariableDataCode( $php, $tpl, $variableData, $dataInspection, $persistence, $parameters );
    }

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
                eZTemplateProcessCache::generateMergeNamespaceCode( $php, $tpl, $namespace, $namespaceScope );
                $variableNameText = $php->variableText( $variableName, 0 );
                $php->addCodePiece( "\$$variableAssignmentName =& processFetchVariable( \$vars, \$namespace, $variableNameText );\n" );
            }
            else if ( $variableDataType == EZ_TEMPLATE_TYPE_ATTRIBUTE )
            {
                $newParameters = $parameters;
                $newParameters['counter'] += 1;
                eZTemplateProcessCache::generateVariableDataCode( $php, $tpl, $variableDataItem[1], $dataInspection,
                                                                  $persistence, $newParameters );
                $newVariableAssignmentName = $newParameters['variable'];
                $newVariableAssignmentCounter = $newParameters['counter'];
                if ( $newVariableAssignmentCounter > 0 )
                    $newVariableAssignmentName .= $newVariableAssignmentCounter;
                $php->addCodePiece( "\$$variableAssignmentName = processFetchAttribute( \$$variableAssignmentName, \$$newVariableAssignmentName );\n" );
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

    function generatePHPCodeChildren( &$php, &$tpl, &$nodeChildren, &$resourceData, $parameters )
    {
        foreach ( $nodeChildren as $node )
        {
            $newNode = false;
            $nodeType = $node[0];
            if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
            {
                $children = $node[1];
                if ( $children )
                {
                    eZTemplateProcessCache::generatePHPCodeChildren( $php, $tpl, $children, $resourceData, $parameters );
                }
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_TEXT )
            {
                $text = $node[2];
                $variablePlacement = $node[3];
                $originalText = eZTemplateProcessCache::fetchTemplatePiece( $variablePlacement );
                $php->addComment( "Text start:" );
                $php->addComment( $originalText );
                $php->addComment( "Text end:" );
                eZTemplateProcessCache::generateTextAppendCode( $php, $tpl, $text );
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_VARIABLE )
            {
                $variableData = $node[2];
                $variablePlacement = $node[3];
                $dataInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
                                                                               $variableData, $variablePlacement,
                                                                               $resourceData );
                $newNode = array( $nodeType,
                                  false,
                                  $variableData,
                                  $variablePlacement );
                $php->addComment( "Variable data: " .
                                  "Is constant: " . ( $dataInspection['is-constant'] ? 'Yes' : 'No' ) .
                                  " Is variable: " . ( $dataInspection['is-variable'] ? 'Yes' : 'No' ) .
                                  " Has attributes: " . ( $dataInspection['has-attributes'] ? 'Yes' : 'No' ) .
                                  " Has operators: " . ( $dataInspection['has-operators'] ? 'Yes' : 'No' )
                                  );
                $originalText = eZTemplateProcessCache::fetchTemplatePiece( $variablePlacement );
                $php->addComment( '{' . $originalText . '}' );
                eZTemplateProcessCache::generateVariableCode( $php, $tpl, $node, $dataInspection,
                                                              array( 'variable' => 'var',
                                                                     'counter' => 0 ) );
                $php->addCodePiece( "if ( is_object( \$var ) )
    \$text .= processFetchText( \$tpl, \$rootNamespace, \$currentNamespace, \$namespace, \$var );
else
    \$text .= \$var;
unset( \$var );\n" );
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
                $php->addComment( "Function: $functionName, $parameterText" );
                $originalText = eZTemplateProcessCache::fetchTemplatePiece( $functionPlacement );
                $php->addComment( '{' . $originalText . '}' );
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
                                $nameInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
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
                                    eZTemplateProcessCache::generateVariableCode( $php, $tpl, $nameParameter, $nameInspection,
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

    function processNodeCombining( &$php, &$tpl, &$node, &$resourceData, &$newNode )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            $children = $node[1];
            $newNode[0] = $nodeType;
            $newNode[1] = false;
            if ( $children )
            {
                eZTemplateProcessCache::processNodeCombiningChildren( $php, $tpl, $children, $resourceData, $newNode );
            }
        }
        else
            $tpl->error( 'processNodeCombining', "Unknown root type $nodeType, should be " . EZ_TEMPLATE_NODE_ROOT );
    }

    function combineStaticNodes( &$tpl, &$resourceData, &$lastNode, &$newNode )
    {
//         eZDebug::writeDebug( $lastNode, 'lastNode' );
//         eZDebug::writeDebug( $newNode, 'newNode' );
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
            $lastDataInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
                                                                               $lastNode[2], $lastNode[3],
                                                                               $resourceData );
            if ( !$lastDataInspection['is-constant'] or
                 $lastDataInspection['is-variable'] or
                 $lastDataInspection['has-attributes'] or
                 $lastDataInspection['has-operators'] )
                return false;
        }
        if ( $newNodeType == EZ_TEMPLATE_NODE_VARIABLE )
        {
            $newDataInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
                                                                              $newNode[2], $newNode[3],
                                                                              $resourceData );
            if ( !$newDataInspection['is-constant'] or
                 $newDataInspection['is-variable'] or
                 $newDataInspection['has-attributes'] or
                 $newDataInspection['has-operators'] )
                return false;
        }
        $textElements = array();
        $lastNodeData = eZTemplateProcessCache::staticNodeData( $lastNode );
        $newNodeData = eZTemplateProcessCache::staticNodeData( $newNode );
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

    function processNodeCombiningChildren( &$php, &$tpl, &$nodeChildren, &$resourceData, &$parentNode )
    {
        $newNodeChildren = array();
        $lastNode = false;
        foreach ( $nodeChildren as $node )
        {
            $newNode = false;
            $nodeType = $node[0];
            if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
            {
                $children = $node[1];
                $newNode = array( $nodeType,
                                  false );
                if ( $children )
                {
                    eZTemplateProcessCache::processNodeCombiningChildren( $php, $tpl, $children, $resourceData, $newNode );
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
                eZTemplateProcessCache::combineStaticNodes( $tpl, $resourceData, $lastNode, $newNode );
            }
            else if ( $nodeType == EZ_TEMPLATE_NODE_VARIABLE )
            {
                $variableData = $node[2];
                $variablePlacement = $node[3];
                $dataInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
                                                                               $variableData, $variablePlacement,
                                                                               $resourceData );
                $newNode = array( $nodeType,
                                  false,
                                  $variableData,
                                  $variablePlacement );
                unset( $dataInspection );
                eZTemplateProcessCache::combineStaticNodes( $tpl, $resourceData, $lastNode, $newNode );
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
                    eZTemplateProcessCache::processNodeCombiningChildren( $php, $tpl,
                                                                          $functionChildren, $resourceData, $newNode );
                }

            }
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

    function processStaticOptimizations( &$php, &$tpl, &$node, &$resourceData, &$newNode )
    {
        if ( !isset( $node[0] ) )
            eZDebug::writeDebug( $node, 'node' );
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
                    eZTemplateProcessCache::processStaticOptimizations( $php, $tpl, $child, $resourceData, $newChild );
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
            $dataInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
                                                                           $variableData, $variablePlacement,
                                                                           $resourceData );
//             $php->addVariable( 'dataInspection', $dataInspection, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
            if ( isset( $dataInspection['new-data'] ) )
            {
                $variableData = $dataInspection['new-data'];
            }
            $newNode[0] = $nodeType;
            $newNode[1] = false;
            $newNode[2] = $variableData;
            $newNode[3] = $variablePlacement;
            unset( $dataInspection );
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_FUNCTION )
        {
            $functionChildren = $node[1];
            $functionName = $node[2];
            $functionParameters = $node[3];
            $functionPlacement = $node[4];

            $newFunctionChildren = array();
//             eZDebug::writeDebug( $functionChildren, "functionChildren for $functionName" );
            if ( is_array( $functionChildren ) )
            {
                foreach ( $functionChildren as $functionChild )
                {
                    $newChild = array();
                    eZTemplateProcessCache::processStaticOptimizations( $php, $tpl,
                                                                        $functionChild, $resourceData, $newChild );
                    $newFunctionChildren[] = $newChild;
                }
                $functionChildren = $newFunctionChildren;
            }
//             eZDebug::writeDebug( $functionChildren, "new functionChildren for $functionName" );

            $newFunctionParameters = array();
            if ( $functionParameters )
            {
                foreach ( $functionParameters as $functionParameterName => $functionParameterData )
                {
                    $dataInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
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
    }

    function inspectVariableData( &$tpl, $variableData, $variablePlacement, &$resourceData )
    {
        $dataInspection = array( 'is-constant' => false,
                                 'is-variable' => false,
                                 'has-operators' => false,
                                 'has-attributes' => false );
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
                $newDataInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
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
                $operatorHint = eZTemplateProcessCache::operatorHint( $tpl, $operatorName );
                $newVariableItem = $variableItem;
//                 eZDebug::writeDebug( $operatorHint, $operatorName );
                if ( $operatorHint )
                {
                    if ( !$operatorHint['input'] and
                         $operatorHint['output'] )
                        $newVariableData = array();
                    if ( !$operatorHint['parameters'] )
                        $newVariableItem[1] = array( $operatorName );
                    if ( $operatorHint['static'] )
                    {
                        $operatorStaticData = eZTemplateProcessCache::operatorStaticData( $tpl, $operatorName );
                        $newVariableItem = eZTemplateProcessCache::createStaticVariableData( $tpl, $operatorStaticData, $variableItemPlacement );
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
//                         eZDebug::writeDebug( $operatorParameter );
                        $newDataInspection = eZTemplateProcessCache::inspectVariableData( $tpl,
                                                                                          $operatorParameter, false,
                                                                                          $resourceData );
                        if ( isset( $newDataInspection['new-data'] ) )
                        {
                            $operatorParameter = $newDataInspection['new-data'];
//                             eZDebug::writeDebug( $operatorParameter, "#2" );
                        }
                        $newVariableItem[1][] = $operatorParameter;
                    }
                }
                $newVariableData[] = $newVariableItem;
            }
            else if ( $variableItemType == EZ_TEMPLATE_TYPE_VOID )
            {
                $tpl->warning( 'processCache', "Void datatype should not be used, ignoring it" );
            }
            else
            {
                $tpl->warning( 'processCache', "Unknown data type $variableItemType, ignoring it" );
            }
        }
        $dataInspection['new-data'] = $newVariableData;
        return $dataInspection;
    }

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
            if ( method_exists( $operatorObject, 'processCacheHints' ) )
            {
                $operatorHintArray = $operatorObject->processCacheHints();
                if ( isset( $operatorHintArray[$operatorName] ) )
                {
                    $operatorHint = $operatorHintArray[$operatorName];
                }
            }
        }
        return $operatorHint;
    }

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
            if ( method_exists( $operatorObject, 'processCacheStaticData' ) )
            {
                $operatorData = $operatorObject->processCacheStaticData( $operatorName );
            }
        }
        return $operatorData;
    }

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

    function processNodeTransformation( &$php, &$tpl, &$node, &$resourceData, &$newNode )
    {
        $newNode = eZTemplateProcessCache::processNodeTransformationRoot( $php, $tpl, $node, $resourceData );
    }

    function processNodeTransformationRoot( &$php, &$tpl, &$node, &$resourceData )
    {
        $nodeType = $node[0];
        eZDebug::writeDebug( 'there' );
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
                    $newChildNode = eZTemplateProcessCache::processNodeTransformationChild( $php, $tpl, $childNode, $resourceData );
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

    function processNodeTransformationChild( &$php, &$tpl, &$node, &$resourceData )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_FUNCTION )
        {
            $functionChildren = $node[1];
            $functionName = $node[2];
            $functionParameters = $node[3];
            $functionPlacement = $node[4];

            if ( is_array( $tpl->Functions[$functionName] ) )
            {
                $tpl->loadAndRegisterOperators( $tpl->Functions[$functionName] );
            }
            $functionObject =& $tpl->Functions[$functionName];
            eZDebug::writeDebug( 'here' );
            if ( is_object( $functionObject ) )
            {
                $hasTransformationSupport = false;
                if ( method_exists( $functionObject, 'processCacheHints' ) )
                {
                    $hints = $functionObject->processCacheHints();
                    eZDebug::writeDebug( $hints, "Hints for $functionName" );
                    if ( isset( $hints[$functionName] ) and
                         isset( $hints[$functionName]['tree-transformation'] ) and
                         $hints[$functionName]['tree-transformation'] )
                        $hasTransformationSupport = true;
                }
                if ( $hasTransformationSupport and
                     method_exists( $functionObject, 'templateNodeTransformation' ) )
                {
                    eZDebug::writeDebug( "Transformation for $functionName" );

                    if ( $functionChildren )
                    {
                        $newChildren = array();
                        foreach ( $functionChildren as $childNode )
                        {
                            $newChildNode = eZTemplateProcessCache::processNodeTransformationChild( $php, $tpl, $childNode, $resourceData );
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
                        return $node;
                    return $newNodes;
                }
            }
            return false;
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            return eZTemplateProcessCache::processNodeTransformationRoot( $php, $tpl, $node, $resourceData );
        }
        else
            return false;
    }

}

?>
