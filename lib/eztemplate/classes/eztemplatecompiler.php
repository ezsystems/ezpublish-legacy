<?php
//
// Definition of eZTemplateCompiler class
//
// Created on: <06-Dec-2002 14:17:10 amos>
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

require_once( 'lib/ezutils/classes/ezdebug.php' );

class eZTemplateCompiler
{
    const CODE_DATE = 1074699607;

    /*!
     \static
     Returns the prefix for file names
    */
    static function TemplatePrefix()
    {
        $templatePrefix = '';
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        if ( $ini->variable( 'TemplateSettings', 'TemplateCompression' ) == 'enabled' )
        {
            $templatePrefix = 'compress.zlib://';
        }
        return $templatePrefix;
    }
    /*!
     \static
     Sets/unsets various compiler settings. To set a setting add a key in the \a $settingsMap
     with the wanted value, to unset it use \c null as the value.

     The following values can be set.
     - compile - boolean, whether to compile templates or not
     - comments - boolean, whether to include comments in templates
     - accumulators - boolean, whether to include debug accumulators in templates
     - timingpoints - boolean, whether to include debug timingpoints in templates
     - fallbackresource - boolean, whether to include the fallback resource code
     - nodeplacement - boolean, whether to include information on placement of all nodes
     - execution - boolean, whether to execute the compiled templates or not
     - generate - boolean, whether to always generate the compiled files, or only when template is changed
     - compilation-directory - string, where to place compiled files, the path will be relative from the
                               eZ Publish directory and not the var/cache directory.
    */
    static function setSettings( $settingsMap )
    {
        $existingMap = array();
        if ( isset( $GLOBALS['eZTemplateCompilerSettings'] ) )
        {
            $existingMap = $GLOBALS['eZTemplateCompilerSettings'];
        }
        $GLOBALS['eZTemplateCompilerSettings'] = array_merge( $existingMap, $settingsMap );
    }

    /*!
     \static
     \return true if template compiling is enabled.
     \note To change this setting edit settings/site.ini and locate the group TemplateSettings and the entry TemplateCompile.
    */
    static function isCompilationEnabled()
    {
        if ( isset( $GLOBALS['eZSiteBasics'] ) )
        {
            $siteBasics = $GLOBALS['eZSiteBasics'];
            if ( isset( $siteBasics['no-cache-adviced'] ) and
                 $siteBasics['no-cache-adviced'] )
            {
                return false;
            }
        }

        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['compile'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['compile'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['compile'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $compilationEnabled = $ini->variable( 'TemplateSettings', 'TemplateCompile' ) == 'enabled';
        return $compilationEnabled;
    }

    /*!
     \static
     \return true if template compilation should include comments.
    */
    static function isCommentsEnabled()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['comments'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['comments'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['comments'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $commentsEnabled = $ini->variable( 'TemplateSettings', 'CompileComments' ) == 'enabled';
        return $commentsEnabled;
    }

    /*!
     \static
     \return true if template compilation should run in development mode.

     When in development mode the system will perform additional checks, e.g. for
     modification time of compiled file vs original source file.
     This mode is quite useful for development since it requires less
     clear-cache calls but has additional file checks and should be turned off
     for live sites.
    */
    static function isDevelopmentModeEnabled()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['development_mode'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['development_mode'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['development_mode'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $developmentModeEnabled = $ini->variable( 'TemplateSettings', 'DevelopmentMode' ) == 'enabled';
        return $developmentModeEnabled;
    }

    /*!
     \static
     \return true if template compilation should include debug accumulators.
    */
    static function isAccumulatorsEnabled()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['accumulators'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['accumulators'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['accumulators'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $enabled = $ini->variable( 'TemplateSettings', 'CompileAccumulators' ) == 'enabled';
        return $enabled;
    }

    /*!
     \static
     \return true if template compilation should include debug timing points.
    */
    static function isTimingPointsEnabled()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['timingpoints'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['timingpoints'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['timingpoints'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $enabled = $ini->variable( 'TemplateSettings', 'CompileTimingPoints' ) == 'enabled';
        return $enabled;
    }

    /*!
     \static
     \return true if resource fallback code should be included.
    */
    static function isFallbackResourceCodeEnabled()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['fallbackresource'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['fallbackresource'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['fallbackresource'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $enabled = $ini->variable( 'TemplateSettings', 'CompileResourceFallback' ) == 'enabled';
        return $enabled;
    }

    /*!
     \static
     \return true if template compilation should include comments.
    */
    static function isNodePlacementEnabled()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['nodeplacement'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['nodeplacement'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['nodeplacement'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $nodePlacementEnabled = $ini->variable( 'TemplateSettings', 'CompileNodePlacements' ) == 'enabled';
        return $nodePlacementEnabled;
    }

    /*!
     \static
     \return true if the compiled template execution is enabled.
    */
    static function isExecutionEnabled()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['execution'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['execution'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['execution'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $execution = $ini->variable( 'TemplateSettings', 'CompileExecution' ) == 'enabled';
        return $execution;
    }

    /*!
     \static
     \return true if template compilation should always be run even if a sufficient compilation already exists.
    */
    static function alwaysGenerate()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['generate'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['generate'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['generate'];
        }

        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $alwaysGenerate = $ini->variable( 'TemplateSettings', 'CompileAlwaysGenerate' ) == 'enabled';
        return $alwaysGenerate;
    }

    /*!
     \static
     \return true if template node tree named \a $treeName should be included the compiled template.
    */
    static function isTreeEnabled( $treeName )
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $treeList = $ini->variable( 'TemplateSettings', 'CompileIncludeNodeTree' );
        return in_array( $treeName, $treeList );
    }

    /*!
     \static
     \return the directory for compiled templates.
    */
    static function compilationDirectory()
    {
        if ( isset( $GLOBALS['eZTemplateCompilerSettings']['compilation-directory'] ) and
             $GLOBALS['eZTemplateCompilerSettings']['compilation-directory'] !== null )
        {
            return $GLOBALS['eZTemplateCompilerSettings']['compilation-directory'];
        }

        $compilationDirectory =& $GLOBALS['eZTemplateCompilerDirectory'];
        if ( !isset( $compilationDirectory ) )
        {
            //include_once( 'lib/ezfile/classes/ezdir.php' );
            //include_once( 'lib/ezutils/classes/ezsys.php' );
            $compilationDirectory = eZDir::path( array( eZSys::cacheDirectory(), 'template/compiled' ) );
        }
        return $compilationDirectory;
    }

    /*!
     Creates the name for the compiled template and returns it.
     The name conists of original filename with the md5 of the key and charset appended.
    */
    static function compilationFilename( $key, $resourceData )
    {
        $internalCharset = eZTextCodec::internalCharset();
        $templateFilepath = $resourceData['template-filename'];
        $extraName = '';
        if ( preg_match( "#^.+/(.*)\.tpl$#", $templateFilepath, $matches ) )
            $extraName = $matches[1] . '-';
        else if ( preg_match( "#^(.*)\.tpl$#", $templateFilepath, $matches ) )
            $extraName = $matches[1] . '-';
        $accessText = false;
        if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
            $accessText = '-' . $GLOBALS['eZCurrentAccess']['name'];
        //include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $locale = eZLocale::instance();
        $language = $locale->translationCode();
        //include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $http = eZHTTPTool::instance();
        $useFullUrlText = $http->UseFullUrl ? 'full' : 'relative';

        $pageLayoutVariable = "";
        if ( isset( $GLOBALS['eZCustomPageLayout'] ) )
            $pageLayoutVariable = $GLOBALS['eZCustomPageLayout'];
        $cacheFileKey = $key . '-' . $internalCharset . '-' . $language . '-' . $useFullUrlText . $accessText . "-" . $pageLayoutVariable . '-' . eZSys::indexFile();
        $cacheFileName = $extraName . md5( $cacheFileKey ) . '.php';
        return $cacheFileName;
    }

    /*!
     \static
     \return true if the compiled template with the key \a $key exists.
             A compiled template is found usable when it exists and has a timestamp
             higher or equal to \a $timestamp.
    */
    static function hasCompiledTemplate( $key, $timestamp, &$resourceData )
    {
        if ( !eZTemplateCompiler::isCompilationEnabled() )
            return false;
        if ( eZTemplateCompiler::alwaysGenerate() )
            return false;

        $cacheFileName = eZTemplateCompiler::compilationFilename( $key, $resourceData );

        //include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTemplateCompiler::compilationDirectory(), $cacheFileName, eZTemplateCompiler::TemplatePrefix() );
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
    static function executeCompilation( $tpl, &$textElements, $key, &$resourceData,
                                 $rootNamespace, $currentNamespace )
     {
        if ( !eZTemplateCompiler::isCompilationEnabled() )
            return false;
        if ( !eZTemplateCompiler::isExecutionEnabled() )
            return false;
        $cacheFileName = eZTemplateCompiler::compilationFilename( $key, $resourceData );
        $resourceData['use-comments'] = eZTemplateCompiler::isCommentsEnabled();

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
    static function executeCompilationHelper( $phpScript, &$text,
                                       $tpl, $key, &$resourceData,
                                       $rootNamespace, $currentNamespace )
    {
        $vars =& $tpl->Variables;

        /* We use $setArray to detect if execution failed, and not $text,
         * because an empty template does not return any $text and this is not
         * an error. */
        $setArray = null;
        $namespaceStack = array();

        $tpl->createLocalVariablesList();
        include( eZTemplateCompiler::TemplatePrefix() . $phpScript );
        $tpl->unsetLocalVariables();
        $tpl->destroyLocalVariablesList();

        if ( $setArray !== null )
        {
            return true;
        }
        return false;
    }

    /*!
     \static
     Generates the cache which will be used for handling optimized processing using the key \a $key.
     \note Each call to this will set the PHP time limit to 30
     \return false if the cache does not exist.
    */
    static function compileTemplate( $tpl, $key, &$resourceData )
    {
        if ( !eZTemplateCompiler::isCompilationEnabled() )
            return false;

        $resourceData['use-comments'] = eZTemplateCompiler::isCommentsEnabled();

        $cacheFileName = eZTemplateCompiler::compilationFilename( $key, $resourceData );
        $resourceData['uniqid'] = md5( $resourceData['template-filename']. uniqid( "ezp". getmypid(), true ) );

        // Time limit #1:
        // We reset the time limit to 30 seconds to ensure that templates
        // have enough time to compile
        // However if time limit is unlimited (0) we leave it be
        // Time limit will also be reset after subtemplates are compiled
        $maxExecutionTime = ini_get( 'max_execution_time' );
        if ( $maxExecutionTime != 0 && $maxExecutionTime < 30 )
        {
            @set_time_limit( 30 );
        }

        $rootNode =& $resourceData['root-node'];
        if ( !$rootNode )
            return false;

        //include_once( 'lib/eztemplate/classes/eztemplatenodetool.php' );
        //include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $GLOBALS['eZTemplateCompilerResourceCache'][$resourceData['template-filename']] =& $resourceData;

        $useComments = eZTemplateCompiler::isCommentsEnabled();

        if ( !$resourceData['test-compile'] )
        {
            eZTemplateCompiler::createCommonCompileTemplate();
        }

        /* Check if we need to disable the generation of spacing for the compiled templates */
        $ini = eZINI::instance();
        $spacing = 'disabled';
        if ( $ini->variable( 'TemplateSettings', 'UseFormatting' ) == 'enabled' )
        {
            $spacing = 'enabled';
        }

        $php = new eZPHPCreator( eZTemplateCompiler::compilationDirectory(), $cacheFileName,
                                 eZTemplateCompiler::TemplatePrefix(), array( 'spacing' => $spacing ) );
        $php->addComment( 'URI:       ' . $resourceData['uri'] );
        $php->addComment( 'Filename:  ' . $resourceData['template-filename'] );
        $php->addComment( 'Timestamp: ' . $resourceData['time-stamp'] . ' (' . date( 'D M j G:i:s T Y', $resourceData['time-stamp'] ) . ')' );

        $php->addCodePiece("\$oldSetArray_{$resourceData['uniqid']} = isset( \$setArray ) ? \$setArray : array();\n".
                           "\$setArray = array();\n");
// Code to decrement include level of the templates
        $php->addCodePiece( "\$tpl->Level++;\n" );
        $php->addCodePiece( "if ( \$tpl->Level > $tpl->MaxLevel )\n".
                            "{\n".
                            "\$text = \$tpl->MaxLevelWarning;".
                            "\$tpl->Level--;\n".
                            "return;\n".
                            "}\n" );
        if ( $resourceData['locales'] && count( $resourceData['locales'] ) )
        {
            $php->addComment( 'Locales:   ' . join( ', ', $resourceData['locales'] ) );

            $php->addCodePiece(
                '$locales = array( "'. join( '", "', $resourceData['locales'] ) . "\" );\n".
                '$oldLocale_'. $resourceData['uniqid']. ' = setlocale( LC_CTYPE, null );'. "\n".
                '$currentLocale_'. $resourceData['uniqid']. ' = setlocale( LC_CTYPE, $locales );'. "\n"
            );
        }
//         $php->addCodePiece( "print( \"" . $resourceData['template-filename'] . " ($cacheFileName)<br/>\n\" );" );
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
        $php->addVariable( 'eZTemplateCompilerCodeDate', eZTemplateCompiler::CODE_DATE );
        $php->addCodePiece( "if ( !defined( 'EZ_TEMPLATE_COMPILER_COMMON_CODE' ) )\n" );
        $php->addInclude( eZTemplateCompiler::compilationDirectory() . '/common.php', eZPHPCreator::INCLUDE_ONCE_STATEMENT, array( 'spacing' => 4 ) );
        $php->addSpace();

        if ( eZTemplateCompiler::isAccumulatorsEnabled() )
        {
            $php->addCodePiece( "eZDebug::accumulatorStart( 'template_compiled_execution', 'template_total', 'Template compiled execution', true );\n" );
        }
        if ( eZTemplateCompiler::isTimingPointsEnabled() )
        {
            $php->addCodePiece( "eZDebug::addTimingPoint( 'Script start $cacheFileName' );\n" );
        }

//         $php->addCodePiece( "if ( !isset( \$vars ) )\n    \$vars =& \$tpl->Variables;\n" );
//         $php->addSpace();

        $parameters = array();
        $textName = eZTemplateCompiler::currentTextName( $parameters );

//         $php->addCodePiece( "if ( !isset( \$$textName ) )\n    \$$textName = '';\n" );
//         $php->addSpace();

//         $variableStats = array();
//         eZTemplateCompiler::prepareVariableStatistics( $tpl, $resourceData, $variableStats );
//         eZTemplateCompiler::calculateVariableStatistics( $tpl, $rootNode, $resourceData, $variableStats );
//         print_r( $variableStats );

        $transformedTree = array();
        eZTemplateCompiler::processNodeTransformation( $useComments, $php, $tpl, $rootNode, $resourceData, $transformedTree );

        if ( $ini->variable( 'TemplateSettings', 'TemplateOptimization' ) == 'enabled' )
        {
            require_once ('lib/eztemplate/classes/eztemplateoptimizer.php');
            /* Retrieve class information for the attribute lookup table */
            if ( isset( $resourceData['handler']->Keys ) and isset( $resourceData['handler']->Keys['class'] ) ) {
                $resourceData['class-info'] = eZTemplateOptimizer::fetchClassDeclaration( $resourceData['handler']->Keys['class'] );
            }
            /* Run the optimizations */
            eZTemplateOptimizer::optimize( $useComments, $php, $tpl, $transformedTree, $resourceData );
        }

        $staticTree = array();
        eZTemplateCompiler::processStaticOptimizations( $useComments, $php, $tpl, $transformedTree, $resourceData, $staticTree );

        $combinedTree = array();
        eZTemplateCompiler::processNodeCombining( $useComments, $php, $tpl, $staticTree, $resourceData, $combinedTree );

        $finalTree = $combinedTree;
        if ( !eZTemplateCompiler::isNodePlacementEnabled() )
            eZTemplateCompiler::processRemoveNodePlacement( $finalTree );

        eZTemplateCompiler::generatePHPCode( $useComments, $php, $tpl, $finalTree, $resourceData );

        if ( eZTemplateCompiler::isTreeEnabled( 'final' ) )
            $php->addVariable( 'finalTree', $finalTree, eZPHPCreator::VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
        if ( eZTemplateCompiler::isTreeEnabled( 'combined' ) )
            $php->addVariable( 'combinedTree', $combinedTree, eZPHPCreator::VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
        if ( eZTemplateCompiler::isTreeEnabled( 'static' ) )
            $php->addVariable( 'staticTree', $staticTree, eZPHPCreator::VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
        if ( eZTemplateCompiler::isTreeEnabled( 'transformed' ) )
            $php->addVariable( 'transformedTree', $transformedTree, eZPHPCreator::VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );
        if ( eZTemplateCompiler::isTreeEnabled( 'original' ) )
            $php->addVariable( 'originalTree', $rootNode, eZPHPCreator::VARIABLE_ASSIGNMENT, array( 'full-tree' => true ) );

        if ( eZTemplateCompiler::isTimingPointsEnabled() )
            $php->addCodePiece( "eZDebug::addTimingPoint( 'Script end $cacheFileName' );\n" );
        if ( eZTemplateCompiler::isAccumulatorsEnabled() )
            $php->addCodePiece( "eZDebug::accumulatorStop( 'template_compiled_execution', true );\n" );

        if ( $resourceData['locales'] && count( $resourceData['locales'] ) )
        {
            $php->addCodePiece(
                'setlocale( LC_CTYPE, $oldLocale_'. $resourceData['uniqid']. ' );'. "\n"
            );
        }
        $php->addCodePiece('$setArray = $oldSetArray_'. $resourceData['uniqid']. ";\n");

// Code to decrement include level of the templates
        $php->addCodePiece("\$tpl->Level--;\n" );

        /*
        // dump names of all defined PHP variables
        $php->addCodePiece( "echo \"defined vars in $resourceData[uri]:<br/><pre>\\n\";\n" );
        $php->addCodePiece( 'foreach ( array_keys( get_defined_vars() ) as $var_name  ) echo "- $var_name\n";' );
        // dump tpl vars
        $php->addCodePiece( 'echo "\n-----------------------------------------------------------\nvars: ";' );
        $php->addCodePiece( 'var_dump( $vars );' );
        $php->addCodePiece( 'echo "</pre><hr/>\n";' );
        */

        if ( !$resourceData['test-compile'] )
        {
            $php->store( true );
        }

        return true;
    }

    static function prepareVariableStatistics( $tpl, &$resourceData, &$stats )
    {
//         $path = $resourceData['template-filename'];
//         $info =& $GLOBALS['eZTemplateCompileVariableInfo'][$path];
        if ( isset( $resourceData['variable-info'] ) )
        {
        }
    }

    /*!
    */
    static function calculateVariableStatistics( $tpl, &$node, &$resourceData, &$stats )
    {
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_ROOT )
        {
            $children = $node[1];
            $namespace = '';
            if ( $children )
            {
                eZTemplateCompiler::calculateVariableStatisticsChildren( $tpl, $children, $resourceData, $namespace, $stats );
            }
        }
        else
            $tpl->error( 'calculateVariableStatistics', "Unknown root type $nodeType, should be " . eZTemplate::NODE_ROOT );
    }

    static function calculateVariableStatisticsChildren( $tpl, &$nodeChildren, &$resourceData, $namespace, &$stats )
    {
        foreach ( $nodeChildren as $node )
        {
            if ( !isset( $node[0] ) )
                continue;
            $nodeType = $node[0];
            if ( $nodeType == eZTemplate::NODE_ROOT )
            {
                $children = $node[1];
                if ( $children )
                {
                    eZTemplateCompiler::calculateVariableStatisticsChildren( $tpl, $children, $resourceData, $namespace, $stats );
                }
            }
            else if ( $nodeType == eZTemplate::NODE_TEXT )
            {
                $text = $node[2];
                $placement = $node[3];
            }
            else if ( $nodeType == eZTemplate::NODE_VARIABLE )
            {
                $variableData = $node[2];
                $variablePlacement = $node[3];
                $variableParameters = false;
                eZTemplateCompiler::calculateVariableNodeStatistics( $tpl, $variableData, $variablePlacement, $resourceData, $namespace, $stats );
            }
            else if ( $nodeType == eZTemplate::NODE_FUNCTION )
            {
                $functionChildren = $node[1];
                $functionName = $node[2];
                $functionParameters = $node[3];
                $functionPlacement = $node[4];

                if ( !isset( $tpl->Functions[$functionName] ) )
                    continue;

                if ( is_array( $tpl->Functions[$functionName] ) )
                {
                    $tpl->loadAndRegisterOperators( $tpl->Functions[$functionName] );
                }
                $functionObject =& $tpl->Functions[$functionName];
                if ( is_object( $functionObject ) )
                {
                    $hasTransformationSupport = false;
                    $transformChildren = true;
                    if ( method_exists( $functionObject, 'functionTemplateStatistics' ) )
                    {
                        $functionObject->functionTemplateStatistics( $functionName, $node, $tpl, $resourceData, $namespace, $stats );
                    }
                }
                else if ( $resourceData['test-compile'] )
                {
                    $tpl->warning( '', "Operator '$operatorName' is not registered.", $functionPlacement );
                }
            }
        }
    }

    static function calculateVariableNodeStatistics( $tpl, $variableData, $variablePlacement, &$resourceData, $namespace, &$stats )
    {
        if ( !is_array( $variableData ) )
            return false;
        foreach ( $variableData as $variableItem )
        {
            $variableItemType = $variableItem[0];
            $variableItemData = $variableItem[1];
            $variableItemPlacement = $variableItem[2];
            if ( $variableItemType == eZTemplate::TYPE_STRING or
                 $variableItemType == eZTemplate::TYPE_IDENTIFIER )
            {
            }
            else if ( $variableItemType == eZTemplate::TYPE_NUMERIC )
            {
            }
            else if ( $variableItemType == eZTemplate::TYPE_ARRAY )
            {
            }
            else if ( $variableItemType == eZTemplate::TYPE_BOOLEAN )
            {
            }
            else if ( $variableItemType == eZTemplate::TYPE_VARIABLE )
            {
                $variableNamespace = $variableItemData[0];
                $variableNamespaceScope = $variableItemData[1];
                $variableName = $variableItemData[2];
                if ( $variableNamespaceScope == eZTemplate::NAMESPACE_SCOPE_GLOBAL )
                    $newNamespace = $variableNamespace;
                else if ( $variableNamespaceScope == eZTemplate::NAMESPACE_SCOPE_LOCAL )
                    $newNamespace = $variableNamespace;
                else if ( $variableNamespaceScope == eZTemplate::NAMESPACE_SCOPE_RELATIVE )
                    $newNamespace = $tpl->mergeNamespace( $namespace, $variableNamespace );
                else
                    $newNamespace = false;
                eZTemplateCompiler::setVariableStatistics( $stats, $newNamespace, $variableName, array( 'is_accessed' => true ) );
            }
            else if ( $variableItemType == eZTemplate::TYPE_ATTRIBUTE )
            {
                eZTemplateCompiler::calculateVariableNodeStatistics( $tpl, $variableItemData, $variableItemPlacement, $resourceData, $namespace, $stats );
            }
            else if ( $variableItemType == eZTemplate::TYPE_OPERATOR )
            {
                $operatorName = $variableItemData[0];

                if ( !isset( $tpl->Operators[$operatorName] ) )
                    continue;

                if ( is_array( $tpl->Operators[$operatorName] ) )
                {
                    $tpl->loadAndRegisterOperators( $tpl->Operators[$operatorName] );
                }
                $operator =& $tpl->Operators[$operatorName];

                if ( is_object( $operator ) )
                {
                    $hasStats = false;
                    if ( method_exists( $operator, 'operatorTemplateHints' ) )
                    {
                        $hints = $operator->operatorTemplateHints();
                        if ( isset( $hints[$operatorName] ) )
                        {
                            $operatorHints = $hints[$operatorName];
                            $hasParameters = false;
                            if ( isset( $operatorHints['parameters'] ) )
                                $hasParameters = $operatorHints['parameters'];
                            if ( $hasParameters === true )
                            {
                                $parameters = $variableItemData;
                                $count = count( $parameters ) - 1;
                                for ( $i = 0; $i < $count; ++$i )
                                {
                                    $parameter =& $parameters[$i + 1];
                                    $parameterData = $parameter[1];
                                    $parameterPlacement = $parameter[2];
                                    eZTemplateCompiler::calculateVariableNodeStatistics( $tpl, $parameter, $parameterPlacement,
                                                                                         $resourceData, $namespace, $stats );
                                }
                            }
                            else if ( is_integer( $hasParameters ) )
                            {
                                $parameters = $variableItemData;
                                $count = min( count( $parameters ) - 1, $hasParameters );
                                for ( $i = 0; $i < $count; ++$i )
                                {
                                    $parameter =& $parameters[$i + 1];
                                    $parameterData = $parameter[1];
                                    $parameterPlacement = $parameter[2];
                                    eZTemplateCompiler::calculateVariableNodeStatistics( $tpl, $parameter, $parameterPlacement,
                                                                                         $resourceData, $namespace, $stats );
                                }
                            }
                            $hasStats = true;
                        }
                    }
                    if ( !$hasStats and method_exists( $operator, 'operatorTemplateStatistics' ) )
                    {
                        $hasStats = $operator->operatorTemplateStatistics( $operatorName, $variableItem, $variablePlacement, $tpl, $resourceData, $namespace, $stats );
                    }
                    if ( !$hasStats and method_exists( $operator, 'namedParameterList' ) )
                    {
                        $namedParameterList = $operator->namedParameterList();
                        if ( method_exists( $operator, 'namedParameterPerOperator' ) and
                             $operator->namedParameterPerOperator() )
                        {
                            $namedParameterList = $namedParameterList[$operatorName];
                        }
                        $operatorParameters = array_slice( $variableItemData, 1 );
                        $count = 0;
                        foreach ( $namedParameterList as $parameterName => $parameterDefinition )
                        {
                            $operatorParameter = $operatorParameters[$count];
                            eZTemplateCompiler::calculateVariableNodeStatistics( $tpl, $operatorParameter, $variablePlacement, $resourceData, $namespace, $stats );
                            ++$count;
                        }
                        $hasStats = true;
                    }
                }
                else if ( $resourceData['test-compile'] )
                {
                    $tpl->warning( '', "Operator '$operatorName' is not registered." );
                }
            }
            else if ( $variableItemType == eZTemplate::TYPE_VOID )
            {
                $tpl->warning( 'TemplateCompiler::calculateOperatorStatistics', "Void datatype should not be used, ignoring it" );
            }
            else
            {
                $tpl->warning( 'TemplateCompiler::calculateOperatorStatistics', "Unknown data type $variableItemType, ignoring it" );
            }
        }
        return true;
    }

    static function setVariableStatistics( &$stats, $namespace, $variableName, $changes )
    {
        if ( isset( $stats['variables'][$namespace][$variableName] ) )
        {
            $variableStats =& $stats['variables'][$namespace][$variableName];
        }
        else
        {
            $variableStats = array( 'is_accessed' => false,
                                    'is_created' => false,
                                    'is_modified' => false,
                                    'is_removed' => false,
                                    'is_local' => false,
                                    'is_input' => false,
                                    'namespace' => $namespace,
                                    'namespace_scope' => false,
                                    'type' => false );
            $stats['variables'][$namespace][$variableName] =& $variableStats;
        }
        if ( isset( $changes['invalid_access'] ) and $changes['invalid_access'] !== false )
            $variableStats['invalid_access'] = $changes['invalid_access'];
        if ( isset( $changes['is_accessed'] ) and $changes['is_accessed'] !== false )
            $variableStats['is_accessed'] = $changes['is_accessed'];
        if ( isset( $changes['is_created'] ) and $changes['is_created'] !== false )
            $variableStats['is_created'] = $changes['is_created'];
        if ( isset( $changes['is_modified'] ) and $changes['is_modified'] !== false )
            $variableStats['is_modified'] = $changes['is_modified'];
        if ( isset( $changes['is_removed'] ) and $changes['is_removed'] !== false )
            $variableStats['is_removed'] = $changes['is_removed'];
        if ( isset( $changes['is_local'] ) and $changes['is_local'] !== false )
            $variableStats['is_local'] = $changes['is_local'];
        if ( isset( $changes['is_input'] ) and $changes['is_input'] !== false )
            $variableStats['is_input'] = $changes['is_input'];
        if ( isset( $changes['namespace'] ) )
            $variableStats['namespace'] = $changes['namespace'];
        if ( isset( $changes['namespace_scope'] ) )
            $variableStats['namespace_scope'] = $changes['namespace_scope'];
        if ( isset( $changes['type'] ) )
            $variableStats['type'] = $changes['type'];
    }

    /*!
     Iterates over the template node tree and tries to combine multiple static siblings
     into one element. The original tree is specified in \a $node and the new
     combined tree will be present in \a $newNode.
     \sa processNodeCombiningChildren
    */
    static function processNodeCombining( $useComments, $php, $tpl, &$node, &$resourceData, &$newNode )
    {
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_ROOT )
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
            $tpl->error( 'processNodeCombining', "Unknown root type $nodeType, should be " . eZTemplate::NODE_ROOT );
    }

    /*!
     Does node combining on the children \a $nodeChildren.
     \sa processNodeCombining
    */
    static function processNodeCombiningChildren( $useComments, $php, $tpl, &$nodeChildren, &$resourceData, &$parentNode )
    {
        $newNodeChildren = array();
        $lastNode = false;
        foreach ( $nodeChildren as $node )
        {
            $newNode = false;
            if ( !isset( $node[0] ) )
                continue;
            $nodeType = $node[0];
            if ( $nodeType == eZTemplate::NODE_ROOT )
            {
                $children = $node[1];
                $newNode = array( $nodeType,
                                  false );
                if ( $children )
                {
                    eZTemplateCompiler::processNodeCombiningChildren( $useComments, $php, $tpl, $children, $resourceData, $newNode );
                }
            }
            else if ( $nodeType == eZTemplate::NODE_TEXT )
            {
                $text = $node[2];
                $placement = $node[3];

                $newNode = array( $nodeType,
                                  false,
                                  $text,
                                  $placement );
                eZTemplateCompiler::combineStaticNodes( $tpl, $resourceData, $lastNode, $newNode );
            }
            else if ( $nodeType == eZTemplate::NODE_VARIABLE )
            {
                $variableCustom = $node[1];
                $variableData = $node[2];
                $variablePlacement = $node[3];
                $variableParameters = false;
                $dataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                           $variableData, $variablePlacement,
                                                                           $resourceData );
                $newNode = $node;
                $newNode[1] = $variableCustom;
                unset( $dataInspection );
                eZTemplateCompiler::combineStaticNodes( $tpl, $resourceData, $lastNode, $newNode );
            }
            else if ( $nodeType == eZTemplate::NODE_FUNCTION )
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
    static function combineStaticNodes( $tpl, &$resourceData, &$lastNode, &$newNode )
    {
        if ( $lastNode == false or
             $newNode == false )
            return false;
        $lastNodeType = $lastNode[0];
        $newNodeType = $newNode[0];
        if ( !in_array( $lastNodeType, array( eZTemplate::NODE_TEXT,
                                              eZTemplate::NODE_VARIABLE ) ) or
             !in_array( $newNodeType, array( eZTemplate::NODE_TEXT,
                                             eZTemplate::NODE_VARIABLE ) ) )
            return false;
        if ( $lastNodeType == eZTemplate::NODE_VARIABLE )
        {
            if ( is_array( $lastNode[1] ) )
                return false;
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
        if ( $newNodeType == eZTemplate::NODE_VARIABLE )
        {
            if ( is_array( $newNode[1] ) )
                return false;
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
            if ( isset( $newNode[1] ) and
                 $newNode[1] !== false )
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
        $newNode = array( eZTemplate::NODE_TEXT,
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
    static function staticNodeData( $node )
    {
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_TEXT )
        {
            return $node[2];
        }
        else if ( $nodeType == eZTemplate::NODE_VARIABLE )
        {
            $data = $node[2];
            if ( is_array( $data ) and
                 count( $data ) > 0 )
            {
                $dataType = $data[0][0];
                if ( $dataType == eZTemplate::TYPE_STRING or
                     $dataType == eZTemplate::TYPE_NUMERIC or
                     $dataType == eZTemplate::TYPE_IDENTIFIER or
                     $dataType == eZTemplate::TYPE_ARRAY or
                     $dataType == eZTemplate::TYPE_BOOLEAN )
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
    static function processStaticOptimizations( $useComments, $php, $tpl, &$node, &$resourceData, &$newNode )
    {
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_ROOT )
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
        else if ( $nodeType == eZTemplate::NODE_TEXT )
        {
            $text = $node[2];
            $placement = $node[3];

            $newNode[0] = $nodeType;
            $newNode[1] = false;
            $newNode[2] = $text;
            $newNode[3] = $placement;
        }
        else if ( $nodeType == eZTemplate::NODE_VARIABLE )
        {
            $variableCustom = $node[1];
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
            $newNode[1] = $variableCustom;
            $newNode[2] = $variableData;
            unset( $dataInspection );
        }
        else if ( $nodeType == eZTemplate::NODE_FUNCTION )
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
    static function processNodeTransformation( $useComments, $php, $tpl, &$node, &$resourceData, &$newNode )
    {
        $newNode = eZTemplateCompiler::processNodeTransformationRoot( $useComments, $php, $tpl, $node, $resourceData );
    }

    /*!
     Iterates over the nodes \a $nodes and does transformation on them.
     \sa processNodeTransformationChildren
     \note This method can be called from operator and functions as long as they have the \a $privateData parameter.
    */
    static function processNodeTransformationNodes( $tpl, &$node, &$nodes, &$privateData )
    {
        $useComments = $privateData['use-comments'];
        $php =& $privateData['php-creator'];
        $resourceData =& $privateData['resource-data'];
        return eZTemplateCompiler::processNodeTransformationChildren( $useComments, $php, $tpl, $node, $nodes, $resourceData );
    }

    /*!
     Iterates over the children \a $children and does transformation on them.
     \sa processNodeTransformation, processNodeTransformationChild
    */
    static function processNodeTransformationChildren( $useComments, $php, $tpl, &$node, &$children, &$resourceData )
    {
        if ( $children )
        {
            $newChildren = array();
            foreach ( $children as $childNode )
            {
                $newChildNode = eZTemplateCompiler::processNodeTransformationChild( $useComments, $php, $tpl, $childNode, $resourceData );
                if ( !$newChildNode )
                    $newChildren[] = $childNode;
                else
                    $newChildren = array_merge( $newChildren, $newChildNode );
            }
            if ( count( $newChildren ) > 0 )
                return $newChildren;
        }
        return $children;
    }

    /*!
     Iterates over the children of the root node \a $node and does transformation on them.
     \sa processNodeTransformation, processNodeTransformationChild
    */
    static function processNodeTransformationRoot( $useComments, $php, $tpl, &$node, &$resourceData )
    {
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_ROOT )
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
                    else
                        $newChildren = array_merge( $newChildren, $newChildNode );
                }
                if ( count( $newChildren ) > 0 )
                    $newNode[1] = $newChildren;
            }
            return $newNode;
        }
        else
            $tpl->error( 'processNodeTransformation', "Unknown root type $nodeType, should be " . eZTemplate::NODE_ROOT );
        return false;
    }

    /*!
     Iterates over the children of the function node \a $node and transforms the tree.
     If the node is not a function it will return \c false.
     \sa processNodeTransformationRoot, processNodeTransformationChild
    */
    static function processNodeTransformationChild( $useComments, $php, $tpl, &$node, &$resourceData )
    {
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_FUNCTION )
        {
            $nodeCopy = $node;
            $functionChildren = $node[1];
            $functionName = $node[2];
            $functionParameters = $node[3];
            $functionPlacement = $node[4];
            if ( !isset( $tpl->Functions[$functionName] ) )
                return false;

            if ( is_array( $tpl->Functions[$functionName] ) )
            {
                $tpl->loadAndRegisterFunctions( $tpl->Functions[$functionName] );
            }
            $functionObject =& $tpl->Functions[$functionName];
            if ( is_object( $functionObject ) )
            {
                $hasTransformationSupport = false;
                $transformChildren = true;
                $transformParameters = false;
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
                    if ( isset( $hints[$functionName] ) and
                         isset( $hints[$functionName]['transform-parameters'] ) )
                        $transformParameters = $hints[$functionName]['transform-parameters'];
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

                    if ( $transformParameters and
                         $functionParameters )
                    {
                        $newParameters = array();
                        foreach ( $functionParameters as $parameterName => $parameterElementList )
                        {
                            $elementTree = $parameterElementList;
                            $elementList = $elementTree;
                            $newParamNode = eZTemplateCompiler::processElementTransformationChild( $useComments, $php, $tpl, $node,
                                                                                                   $elementTree, $elementList, $resourceData );
                            if ( !$newParamNode || !is_array( $newParamNode ) )
                                $newParameters[$parameterName] = $parameterElementList;
                            else
                                $newParameters[$parameterName] = $newParamNode;
                        }
                        if ( count( $newParameters ) > 0 )
                        {
                            $node[3] = $newParameters;
                            $functionParameters = $newParameters;
                        }
                    }

                    $privateData = array( 'use-comments' => $useComments,
                                          'php-creator' => $php,
                                          'resource-data' => &$resourceData );
                    $newNodes = $functionObject->templateNodeTransformation( $functionName, $node,
                                                                             $tpl, $functionParameters, $privateData );
                    unset( $privateData );
                    if ( !$newNodes )
                    {
                        $node = $nodeCopy;
                        $node[1] = $functionChildren;
                        return false;
                        return $node;
                    }
                    return $newNodes;
                }
            }
            else if ( $resourceData['test-compile'] )
            {
                $tpl->warning( '', "Function '$functionName' is not registered.", $functionPlacement );
            }

            return false;
        }
        else if ( $nodeType == eZTemplate::NODE_VARIABLE )
        {
            $elementTree = $node[2];
            $elementList = $elementTree;

            $newParameterElements = eZTemplateCompiler::processElementTransformationChild( $useComments, $php, $tpl, $node,
                                                                                           $elementTree, $elementList, $resourceData );
            if ( $newParameterElements )
            {
                $newNode = $node;
                $newNode[2] = $newParameterElements;
                $newNodes = array( $newNode );
                return $newNodes;
            }
        }
        else if ( $nodeType == eZTemplate::NODE_ROOT )
        {
            return eZTemplateCompiler::processNodeTransformationRoot( $useComments, $php, $tpl, $node, $resourceData );
        }
        else
            return false;
    }

    /*!
     Iterates over the element list \a $elements and transforms them.
     \sa processElementTransformationChild
    */
    static function processElementTransformationList( $tpl, &$node, $elements, &$privateData )
    {
        $useComments = $privateData['use-comments'];
        $php =& $privateData['php-creator'];
        $resourceData =& $privateData['resource-data'];
        $elementTree = $elements;
        $newElements = eZTemplateCompiler::processElementTransformationChild( $useComments, $php, $tpl, $node,
                                                                              $elementTree, $elements, $resourceData );
        if ( $newElements )
            return $newElements;
        return $elements;
    }

    /*!
     Iterates over the children of the function node \a $node and transforms the tree.
     If the node is not a function it will return \c false.
     \sa processNodeTransformationRoot, processNodeTransformationChild
    */
    static function processElementTransformationChild( $useComments, $php, $tpl, &$node,
                                                $elementTree, $elementList, &$resourceData )
    {
        $count = count( $elementList );
        $lastElement = null;
        $newElementList = array();
        for ( $i = 0; $i < $count; ++$i )
        {
            $element =& $elementList[$i];
            $elementType = $element[0];
            if ( $elementType == eZTemplate::TYPE_OPERATOR )
            {
                $operatorName = $element[1][0];
                $operatorParameters = array_slice( $element[1], 1 );
                if ( !isset( $tpl->Operators[$operatorName] ) )
                    return false;

                if ( is_array( $tpl->Operators[$operatorName] ) )
                {
                    $tpl->loadAndRegisterOperators( $tpl->Operators[$operatorName] );
                }
                $operatorObject =& $tpl->Operators[$operatorName];
                if ( is_object( $operatorObject ) )
                {
                    $hasTransformationSupport = false;
                    $transformParameters = false;
                    $inputAsParameter = false;
                    $knownType = 'static';
                    if ( method_exists( $operatorObject, 'operatorTemplateHints' ) )
                    {
                        $hints = $operatorObject->operatorTemplateHints();
                        if ( isset( $hints[$operatorName] ) and
                             isset( $hints[$operatorName]['element-transformation'] ) and
                             $hints[$operatorName]['element-transformation'] )
                        {
                            $hasTransformationSupport = true;
                        }

                        if ( $hasTransformationSupport  and
                             isset( $hints[$operatorName]['element-transformation-func'] ) )
                        {
                            $transformationMethod = $hints[$operatorName]['element-transformation-func'];
                        }
                        else
                        {
                            $transformationMethod = 'templateElementTransformation';
                        }

                        if ( isset( $hints[$operatorName] ) and
                             isset( $hints[$operatorName]['transform-parameters'] ) )
                        {
                            $transformParameters = $hints[$operatorName]['transform-parameters'];
                        }

                        if ( isset( $hints[$operatorName] ) and
                             isset( $hints[$operatorName]['input-as-parameter'] ) )
                        {
                            $inputAsParameter = $hints[$operatorName]['input-as-parameter'];
                        }

                        if ( isset( $hints[$operatorName]['output'] ) and !$hints[$operatorName]['output'] )
                        {
                            $knownType = 'null';
                        }
                        else if ( isset( $hints[$operatorName]['output-type'] ) )
                        {
                            $knownType = $hints[$operatorName]['output-type'];
                        }
                    }
                    if ( $hasTransformationSupport and
                         method_exists( $operatorObject, $transformationMethod ) )
                    {
                        $resetNewElementList = false;
                        if ( $transformParameters )
                        {
                            $newParameters = array();
                            if ( $inputAsParameter )
                            {
                                $newParameterElements = eZTemplateCompiler::processElementTransformationChild( $useComments, $php, $tpl, $node,
                                                                                                               $elementTree, $newElementList, $resourceData );
                                if ( count( $newParameterElements ) > 0 or
                                     $inputAsParameter === 'always' )
                                {
                                    $newParameters[] = $newParameterElements;
                                    $resetNewElementList = true;
                                }
                            }

                            foreach ( $operatorParameters as $operatorParameter )
                            {
                                $newParameterElements = eZTemplateCompiler::processElementTransformationChild( $useComments, $php, $tpl, $node,
                                                                                                               $elementTree, $operatorParameter, $resourceData );
                                if ( !$newParameterElements )
                                    $newParameters[] = $operatorParameter;
                                else
                                    $newParameters[] = $newParameterElements;
                            }
                            $operatorParameters = $newParameters;
                        }

                        $newElements = $operatorObject->$transformationMethod( $operatorName, $node, $tpl, $resourceData,
                                                                               $element, $lastElement, $elementList, $elementTree,
                                                                               $operatorParameters );
                        if ( is_array( $newElements ) )
                        {
                            if ( $resetNewElementList )
                            {
                                $newElementList = $newElements;
                            }
                            else
                            {
                                $newElementList = array_merge( $newElementList, $newElements );
                            }
                        }
                        else
                        {
                            $newElementList[] = $element;
                        }
                    }
                    else
                    {
                        $newElementList[] = $element;
                    }
                }
                else if ( $resourceData['test-compile'] )
                {
                    $tpl->warning( '', "Operator '$operatorName' is not registered." );
                }
            }
            else
            {
                $newElementList[] = $element;
            }
            $lastElement = $element;
        }
        return $newElementList;
    }

    /*!
     Iterates over the node tree and removes all placement information.
    */
    static function processRemoveNodePlacement( &$node )
    {
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_ROOT )
        {
            $nodeChildren =& $node[1];
            for ( $i = 0; $i < count( $nodeChildren ); ++$i )
            {
                $nodeChild =& $nodeChildren[$i];
                eZTemplateCompiler::processRemoveNodePlacement( $nodeChild );
            }
        }
        else if ( $nodeType == eZTemplate::NODE_TEXT )
        {
            $node[3] = false;
        }
        else if ( $nodeType == eZTemplate::NODE_VARIABLE )
        {
            $node[3] = false;
        }
        else if ( $nodeType == eZTemplate::NODE_FUNCTION )
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
        else if ( $nodeType == eZTemplate::NODE_OPERATOR )
        {
        }
    }

    /*!
     Looks over the variable data \a $variableData and returns an array with
     information on the structure.
     The following entries are generated.
     - is-constant - true if the variable data contains constant data like text and numerics
     - is-variable - true if the variable data is a variable lookup
     - has-operators - true if operators are present
     - has-attributes - true if attributes are used
    */
    static function inspectVariableData( $tpl, $variableData, $variablePlacement, &$resourceData )
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
            if ( $variableItemType == eZTemplate::TYPE_STRING or
                 $variableItemType == eZTemplate::TYPE_IDENTIFIER )
            {
                $dataInspection['is-constant'] = true;
                $dataInspection['is-variable'] = false;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == eZTemplate::TYPE_NUMERIC )
            {
                $dataInspection['is-constant'] = true;
                $dataInspection['is-variable'] = false;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == eZTemplate::TYPE_BOOLEAN )
            {
                $dataInspection['is-constant'] = true;
                $dataInspection['is-variable'] = false;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == eZTemplate::TYPE_DYNAMIC_ARRAY )
            {
                $dataInspection['is-constant'] = false;
                $dataInspection['is-variable'] = true;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == eZTemplate::TYPE_ARRAY )
            {
                $dataInspection['is-constant'] = true;
                $dataInspection['is-variable'] = false;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == eZTemplate::TYPE_VARIABLE )
            {
                $dataInspection['is-constant'] = false;
                $dataInspection['is-variable'] = true;
                $newVariableData[] = $variableItem;
            }
            else if ( $variableItemType == eZTemplate::TYPE_ATTRIBUTE )
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
            else if ( $variableItemType == eZTemplate::TYPE_OPERATOR )
            {
                $dataInspection['has-operators'] = true;
                $operatorName = $variableItemData[0];
                $operatorHint = eZTemplateCompiler::operatorHint( $tpl, $operatorName );
                $newVariableItem = $variableItem;
                if ( $operatorHint and
                     isset( $operatorHint['input'] ) and
                     isset( $operatorHint['output'] ) and
                     isset( $operatorHint['parameters'] ) )
                {
                    if ( !$operatorHint['input'] and
                         $operatorHint['output'] )
                        $newVariableData = array();
                    if ( !isset( $operatorHint) or !$operatorHint['parameters'] )
                        $newVariableItem[1] = array( $operatorName );
                    if ( isset ( $operatorHint['static'] ) and
                         $operatorHint['static'] )
                    {
                        $operatorStaticData = eZTemplateCompiler::operatorStaticData( $tpl, $operatorName );
                        $newVariableItem = eZTemplateCompiler::createStaticVariableData( $tpl, $operatorStaticData, $variableItemPlacement );
                        $dataInspection['is-constant'] = true;
                        $dataInspection['is-variable'] = false;
                        $dataInspection['has-operators'] = false;
                    }
                }
                if ( $newVariableItem[0] == eZTemplate::TYPE_OPERATOR )
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
            else if ( $variableItemType == eZTemplate::TYPE_VOID )
            {
                $tpl->warning( 'TemplateCompiler', "Void datatype should not be used, ignoring it" );
            }
            else if ( $variableItemType > eZTemplate::TYPE_INTERNAL and
                      $variableItemType < eZTemplate::TYPE_INTERNAL_STOP )
            {
                $newVariableData[] = $variableItem;
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
    static function operatorHint( $tpl, $operatorName )
    {
        if ( isset( $tpl->Operators[$operatorName] ) and
             is_array( $tpl->Operators[$operatorName] ) )
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
        else if ( $tpl->testCompile() )
        {
            $tpl->warning( '', "Operator '$operatorName' is not registered." );
        }

        return $operatorHint;
    }

    /*!
     \return static data from operators which support returning static data,
             or \c null if no static data could be extracted.
             The operator is specified in \a $operatorName.

    */
    static function operatorStaticData( $tpl, $operatorName )
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
        else if ( $tpl->testCompile() )
        {
            $tpl->warning( '', "Operator '$operatorName' is not registered." );
        }

        return $operatorData;
    }

    /*!
     Creates a variable data element for the data \a $staticData and returns it.
     The type of element depends on the type of the data, strings and booleans
     are returned as EZ_TEMPLATE_TYPE_TEXT and eZTemplate::TYPE_NUMERIC while other
     types are turned into text and returned as EZ_TEMPLATE_TYPE_TEXT.
    */
    static function createStaticVariableData( $tpl, $staticData, $variableItemPlacement )
    {
        if ( is_string( $staticData ) )
            return array( EZ_TEMPLATE_TYPE_TEXT,
                          $staticData,
                          $variableItemPlacement );
        else if ( is_bool( $staticData ) )
            return array( eZTemplate::TYPE_BOOLEAN,
                          $staticData,
                          $variableItemPlacement );
        else if ( is_bool( $staticData ) or is_numeric( $staticData ) )
            return array( eZTemplate::TYPE_NUMERIC,
                          $staticData,
                          $variableItemPlacement );
        else if ( is_array( $staticData ) )
            return array( eZTemplate::TYPE_ARRAY,
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
    static function fetchTemplatePiece( $placementData )
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
            if ( $length > 0 )
            {
                $fd = fopen( $file, 'rb' );
                fseek( $fd, $startPosition );
                $text = fread( $fd, $length );
                fclose( $fd );
                return $text;
            }
            else
            {
                return '';
            }
        }
        return null;
    }

    /*!
     Creates the common.php file which has common functions for compiled templates.
     If the file already exists if will not create it.
    */
    static function createCommonCompileTemplate()
    {
        $php = new eZPHPCreator( eZTemplateCompiler::compilationDirectory(), 'common.php' );
        if ( $php->exists() )
            return;

        $php->addComment( "This file contains functions which are common to all compiled templates.\n\n" .
                          'NOTE: This file is autogenerated and should not be modified, any changes will be lost!' );
        $php->addSpace();
        $php->addDefine( 'EZ_TEMPLATE_COMPILER_COMMON_CODE', true );
        $php->addSpace();

        $namespaceStack = array();
        $php->addCodePiece( "if ( !isset( \$namespaceStack ) )\n" );
        $php->addVariable( 'namespaceStack', $namespaceStack, eZPHPCreator::VARIABLE_ASSIGNMENT, array( 'spacing' => 4 ) );
        $php->addSpace();

        $lbracket = '{';
        $rbracket = '}';
        $initText = "if ( !function_exists( 'compiledfetchvariable' ) )
$lbracket
    function compiledFetchVariable( \$vars, \$namespace, \$name )
    $lbracket
        \$exists = ( array_key_exists( \$namespace, \$vars ) and
                    array_key_exists( \$name, \$vars[\$namespace] ) );
        if ( \$exists )
        $lbracket
            return \$vars[\$namespace][\$name];
        $rbracket
        return null;
    $rbracket
$rbracket
if ( !function_exists( 'compiledfetchtext' ) )
$lbracket
    function compiledFetchText( \$tpl, \$rootNamespace, \$currentNamespace, \$namespace, \$var )
    $lbracket
        \$text = '';
        \$tpl->appendElement( \$text, \$var, \$rootNamespace, \$currentNamespace );
        return \$text;
    $rbracket
$rbracket
if ( !function_exists( 'compiledAcquireResource' ) )
$lbracket
    function compiledAcquireResource( \$phpScript, \$key, &\$originalText,
                                      \$tpl, \$rootNamespace, \$currentNamespace )
    {
        include( '" . eZTemplateCompiler::TemplatePrefix() . "' . \$phpScript );
        if ( isset( \$text ) )
        {
            \$originalText .= \$text;
            return true;
        }
        return false;
    }
$rbracket
if ( !function_exists( 'compiledfetchattribute' ) )
$lbracket
    function compiledFetchAttribute( \$value, \$attributeValue )
    $lbracket
        if ( is_object( \$value ) )
        $lbracket
            if ( method_exists( \$value, \"attribute\" ) and
                 method_exists( \$value, \"hasattribute\" ) )
            $lbracket
                if ( \$value->hasAttribute( \$attributeValue ) )
                $lbracket
                    return \$value->attribute( \$attributeValue );
                $rbracket
            $rbracket
        $rbracket
        else if ( is_array( \$value ) )
        $lbracket
            if ( isset( \$value[\$attributeValue] ) )
            $lbracket
                return \$value[\$attributeValue];
            $rbracket
        $rbracket
        return null;
    $rbracket
$rbracket
";
        $php->addCodePiece( $initText );
        $php->store( true );
    }

    /*!
     Figures out the current text name to use in compiled template code and return it.
     The names will be text, text1, text2 etc.
    */
    static function currentTextName( $parameters )
    {
        $textData = array( 'variable' => 'text',
                           'counter' => 0 );
        if ( isset( $parameters['text-data'] ) )
            $textData = $parameters['text-data'];
        $name = $textData['variable'];
        if ( $textData['counter'] > 0 )
            $name .= $textData['counter'];
        return $name;
    }

    /*!
     Increases the counter for the current text name, this ensure a uniqe name for it.
    */
    static function increaseCurrentTextName( &$parameters )
    {
        $textData = array( 'variable' => 'text',
                           'counter' => 0 );
        if ( !isset( $parameters['text-data'] ) )
            $parameters['text-data'] = $textData;

        $parameters['text-data']['counter']++;
    }

    /*!
     Decreases a previosuly increased counter for the current text name.
    */
    static function decreaseCurrentTextName( &$parameters )
    {
        $textData = array( 'variable' => 'text',
                           'counter' => 0 );
        if ( !isset( $parameters['text-data'] ) )
        {
            $parameters['text-data'] = $textData;
            return;
        }

        $parameters['text-data']['counter']--;
    }

    static function boundVariableName( $variableID, $parameters )
    {
        $bindMap =& $parameters['variable-bind']['map'][$variableID];
        if ( isset( $bindMap ) )
            $bindMap = array();
    }

    /*!
     Generates the PHP code defined in the template node tree \a $node.
     The code is generated using the php creator specified in \a $php.
    */

    static function generatePHPCode( $useComments, $php, $tpl, &$node, &$resourceData )
    {
        $parameters = array();
        $currentParameters = array( 'spacing' => 0 );
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_ROOT )
        {
            $children = $node[1];
            if ( $children )
            {
                eZTemplateCompiler::generatePHPCodeChildren( $useComments, $php, $tpl, $children, $resourceData, $parameters, $currentParameters );
            }
        }
        else
            $tpl->error( 'generatePHPCode', "Unknown root type $nodeType, should be " . eZTemplate::NODE_ROOT );
        $php->addSpace();
    }

    /*!
     Generates the PHP code for all node children specified in \a $nodeChildren.
     \sa generatePHPCode
    */
    static function generatePHPCodeChildren( $useComments, $php, $tpl, &$nodeChildren, &$resourceData, &$parameters, $currentParameters )
    {
        foreach ( $nodeChildren as $node )
        {
            $newNode = false;
            $nodeType = $node[0];
            if ( $nodeType > eZTemplate::NODE_USER_CUSTOM )
            {
                // Do custom nodes
            }
            else if ( $nodeType > eZTemplate::NODE_INTERNAL )
            {
                // Do custom internal nodes
                if ( $nodeType == eZTemplate::NODE_INTERNAL_CODE_PIECE )
                {
                    $codePiece = $node[1];
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[2]['spacing'] ) )
                        $spacing += $node[2]['spacing'];
                    $php->addCodePiece( $codePiece, array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_WARNING )
                {
                    $warningText = $php->thisVariableText( $node[1], 23, 0, false );
                    $warningLabel = false;
                    $warningLabelText = '';
                    if ( isset( $node[2] ) )
                        $warningLabelText = $php->thisVariableText( $node[2], 0, 0, false );
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[3]['spacing'] ) )
                        $spacing += $node[3]['spacing'];
                    $placementText = 'false';
                    if ( isset( $node[4] ) )
                        $placementText = $php->thisVariableText( $node[4], 0, 0, false );
                    $php->addCodePiece( "\$tpl->warning( " . $warningLabelText . ", " . $warningText . ", " . $placementText . " );", array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_ERROR )
                {
                    $errorText = $php->thisVariableText( $node[1], 21, 0, false );
                    $errorLabel = false;
                    $errorLabelText = '';
                    if ( isset( $node[2] ) )
                        $errorLabelText = $php->thisVariableText( $node[2], 0, 0, false );
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[3]['spacing'] ) )
                        $spacing += $node[3]['spacing'];
                    $placementText = 'false';
                    if ( isset( $node[4] ) )
                        $placementText = $php->thisVariableText( $node[4], 0, 0, false );
                    $php->addCodePiece( "\$tpl->error( " . $errorLabelText . ", " . $errorText . ", " . $placementText . " );", array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_OUTPUT_READ )
                {
                    $variableName = $node[1];
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[2]['spacing'] ) )
                        $spacing += $node[2]['spacing'];
                    $textName = eZTemplateCompiler::currentTextName( $parameters );
                    $assignmentType = $node[3];
                    $assignmentText = $php->variableNameText( $variableName, $assignmentType, $node[2] );
                    $php->addCodePiece( "$assignmentText\$$textName;", array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_OUTPUT_ASSIGN )
                {
                    $variableName = $node[1];
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[2]['spacing'] ) )
                        $spacing += $node[2]['spacing'];
                    $textName = eZTemplateCompiler::currentTextName( $parameters );
                    $assignmentType = $node[3];
                    $assignmentText = $php->variableNameText( $textName, $assignmentType, $node[2] );
                    $php->addCodePiece( "$assignmentText\$$variableName;", array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_OUTPUT_INCREASE )
                {
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[1]['spacing'] ) )
                        $spacing += $node[1]['spacing'];
                    $textName = eZTemplateCompiler::currentTextName( $parameters );
                    $php->addCodePiece( "if " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( !isset( \$textStack ) )\n" .
                                        "    \$textStack = array();\n" .
                                        "\$textStack[] = \$$textName;\n" .
                                        "\$$textName = '';", array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_OUTPUT_DECREASE )
                {
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[1]['spacing'] ) )
                        $spacing += $node[1]['spacing'];
                    $textName = eZTemplateCompiler::currentTextName( $parameters );
                    $php->addCodePiece( "\$$textName = array_pop( \$textStack );", array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_OUTPUT_SPACING_INCREASE )
                {
                    $spacing = $node[1];
                    $currentParameters['spacing'] += $spacing;
                    continue;
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_SPACING_DECREASE )
                {
                    $spacing = $node[1];
                    $currentParameters['spacing'] -= $spacing;
                    continue;
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_VARIABLE_SET )
                {
                    $variableName = $node[1];
                    $variableValue = $node[2];
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[3]['spacing'] ) )
                        $spacing += $node[3]['spacing'];
                    $php->addVariable( $variableName, $variableValue, eZPHPCreator::VARIABLE_ASSIGNMENT, array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_VARIABLE_UNSET )
                {
                    $variableName = $node[1];
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[2]['spacing'] ) )
                        $spacing += $node[2]['spacing'];

                    if ( is_array( $variableName ) )
                    {
                        $namespace = $variableName[0];
                        $namespaceScope = $variableName[1];
                        $variableName = $variableName[2];
                        $namespaceText = eZTemplateCompiler::generateMergeNamespaceCode( $php, $tpl, $namespace, $namespaceScope, array( 'spacing' => $spacing ), true );
                        if ( !is_string( $namespaceText ) )
                            $namespaceText = "\$namespace";
                        $variableNameText = $php->thisVariableText( $variableName, 0, 0, false );
                        if ( isset( $node[2]['remember_set'] ) and $node[2]['remember_set'] )
                        {
                            $php->addCodePiece( "if " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( isset( \$setArray[$namespaceText][$variableNameText] ) )\n".
                                                "{\n" );
                            $spacing += 4;
                        }
                        if ( isset( $node[2]['local-variable'] ) )
                        {
                            $php->addCodePiece( "\$tpl->unsetLocalVariable( $variableNameText, $namespaceText );\n",
                                                array( 'spacing' => $spacing ) );
                        }
                        else
                        {
                            $php->addCodePiece( "unset( \$vars[$namespaceText][$variableNameText] );",
                                                array( 'spacing' => $spacing ) );
                        }

                        if ( isset( $node[2]['remember_set'] ) and $node[2]['remember_set'] )
                        {
                            $php->addCodePiece( "\n}\n" );
                            $spacing -= 4;
                        }
                    }
                    else
                    {
                        $php->addVariableUnset( $variableName, array( 'spacing' => $spacing ) );
                    }
                }
                else if ( ( $nodeType == eZTemplate::NODE_INTERNAL_RESOURCE_ACQUISITION ) ||
                          ( $nodeType == eZTemplate::NODE_OPTIMIZED_RESOURCE_ACQUISITION ) )
                {
                    $resource = $node[1];
                    $resourceObject = $tpl->resourceHandler( $resource );
                    if ( !$resourceObject )
                        continue;

                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[7]['spacing'] ) )
                        $spacing += $node[7]['spacing'];
                    $newRootNamespace = $node[8];
                    $resourceVariableName = $node[9];
                    $resourceFilename = isset( $node[10] ) ? $node[10] : false;

                    /* We can only use fallback code if we know upfront which
                     * template is included; it does not work if we are using
                     * something from the ezobjectforwarder which makes the
                     * uriMap an array */
                    $useFallbackCode = true;
                    $uriMap = $node[2];
                    if ( is_string( $uriMap ) )
                    {
                        $uriMap = array( $uriMap );
                    }
                    else
                    {
                        $useFallbackCode = false;
                    }

                    $resourceMap = array();
                    $hasCompiledCode = false;
                    foreach ( $uriMap as $uriKey => $originalURI )
                    {
                        $uri = $originalURI;
                        if ( $resource )
                            $uri = $resource . ':' . $uri;
                        unset( $tmpResourceData );
                        $tmpResourceData = $tpl->resourceData( $resourceObject, $uri, $node[1], $originalURI );
                        $uriText = $php->thisVariableText( $uri, 0, 0, false );

                        $resourceCanCache = true;
                        if ( !$resourceObject->servesStaticData() )
                            $resourceCanCache = false;
                        if ( !$tpl->isCachingAllowed() )
                            $resourceCanCache = false;

                        $tmpResourceData['text'] = null;
                        $tmpResourceData['root-node'] = null;
                        $tmpResourceData['compiled-template'] = false;
                        $tmpResourceData['time-stamp'] = null;
                        $tmpResourceData['key-data'] = null;
                        $tmpResourceData['use-comments'] = eZTemplateCompiler::isCommentsEnabled();
                        $subSpacing = 0;
                        $hasResourceData = false;

                        $savedLocale = setlocale( LC_CTYPE, null );
                        if ( isset( $GLOBALS['eZTemplateCompilerResourceCache'][$tmpResourceData['template-filename']] ) )
                        {
                            $tmpFileName = $tmpResourceData['template-filename'];
                            unset( $tmpResourceData );
                            $tmpResourceData = $GLOBALS['eZTemplateCompilerResourceCache'][$tmpFileName];
                            $tmpResourceData['compiled-template'] = true;
                            $tmpResourceData['use-comments'] = eZTemplateCompiler::isCommentsEnabled();
                            $hasResourceData = true;
                            $hasCompiledCode = true;
                        }
                        else if ( $useFallbackCode )
                        {
                            // If we can use fallback code we don't need to compile the templates in advance
                            // Simply fake that it has been compiled by setting some variables
                            // Note: Yes this is a hack, but rewriting this code is not an easy task
                            if ( $resourceObject->handleResource( $tpl, $tmpResourceData, $node[4], $node[5] ) )
                            {
                                $tmpResourceData['compiled-template'] = true;
                                $hasResourceData = true;
                                $hasCompiledCode = true;
                            }
                        }
                        else
                        {
                            if ( $resourceObject->handleResource( $tpl, $tmpResourceData, $node[4], $node[5] ) )
                            {
                                if ( !$tmpResourceData['compiled-template'] and
                                     $tmpResourceData['root-node'] === null )
                                {
                                    $root =& $tmpResourceData['root-node'];
                                    $root = array( eZTemplate::NODE_ROOT, false );
                                    $templateText =& $tmpResourceData["text"];
                                    $keyData = $tmpResourceData['key-data'];
                                    $rootNamespace = '';
                                    $tpl->parse( $templateText, $root, $rootNamespace, $tmpResourceData );
                                    $hasResourceData = false;
                                }

                                /* We always DO need to execute this part if we
                                 * don't have any fallback code. If we can
                                 * generate the fallback code we make the
                                 * included template compile on demand */
                                if ( !$tmpResourceData['compiled-template'] and
                                     $resourceCanCache and
                                     $tpl->canCompileTemplate( $tmpResourceData, $node[5] ) and
                                     !$useFallbackCode )
                                {
                                    $generateStatus = $tpl->compileTemplate( $tmpResourceData, $node[5] );

                                    // Time limit #2:
                                    /* We reset the time limit to 60 seconds to
                                     * ensure that remaining template has
                                     * enough time to compile. However if time
                                     * limit is unlimited (0) we leave it be */
                                    $maxExecutionTime = ini_get( 'max_execution_time' );
                                    if ( $maxExecutionTime != 0 && $maxExecutionTime < 60 )
                                    {
                                        @set_time_limit( 60 );
                                    }

                                    if ( $generateStatus )
                                        $tmpResourceData['compiled-template'] = true;
                                }
                            }
                            $GLOBALS['eZTemplateCompilerResourceCache'][$tmpResourceData['template-filename']] =& $tmpResourceData;
                        }
                        setlocale( LC_CTYPE, $savedLocale );
                        $textName = eZTemplateCompiler::currentTextName( $parameters );
                        if ( $tmpResourceData['compiled-template'] )
                        {
                            $hasCompiledCode = true;
//                            if ( !eZTemplateCompiler::isFallbackResourceCodeEnabled() )
//                                $useFallbackCode = false;
                            $keyData = $tmpResourceData['key-data'];
                            $templatePath = $tmpResourceData['template-name'];
                            $key = $resourceObject->cacheKey( $keyData, $tmpResourceData, $templatePath, $node[5] );
                            $cacheFileName = eZTemplateCompiler::compilationFilename( $key, $tmpResourceData );

                            $directory = eZTemplateCompiler::compilationDirectory();
                            $phpScript = eZDir::path( array( $directory, $cacheFileName ) );
                            $phpScriptText = $php->thisVariableText( $phpScript, 0, 0, false );
                            $resourceMap[$uriKey] = array( 'key' => $uriKey,
                                                           'uri' => $uri,
                                                           'phpscript' => $phpScript );
                        }
                    }

                    if ( $useComments )
                    {
                        $variablePlacement = $node[6];
                        if ( $variablePlacement )
                        {
                            $originalText = eZTemplateCompiler::fetchTemplatePiece( $variablePlacement );
                            $php->addComment( "Resource Acquisition:", true, true, array( 'spacing' => $spacing ) );
                            $php->addComment( $originalText, true, true, array( 'spacing' => $spacing ) );
                        }
                    }

                    if ( $hasCompiledCode )
                    {
                        if ( $resourceVariableName )
                        {
                            $phpScriptText = '$phpScript';
                            $phpScriptArray = array();

                            foreach ( $resourceMap as $resourceMapItem )
                            {
                                $phpScriptArray[$resourceMapItem['key']] = $resourceMapItem['phpscript'];
                            }

                            if ( !$resourceFilename ) /* Not optimized version */
                            {
                                $php->addVariable( "phpScriptArray", $phpScriptArray, eZPHPCreator::VARIABLE_ASSIGNMENT, array( 'spacing' => $spacing ) );
                                $resourceVariableNameText = "\$$resourceVariableName";
                                $php->addCodePiece( "\$phpScript = isset( \$phpScriptArray[$resourceVariableNameText] ) ? \$phpScriptArray[$resourceVariableNameText] : false;\n", array( 'spacing' => $spacing ) );
                            }
                            else /* Optimised version */
                            {
                                $php->addVariable( "phpScript", $phpScriptArray[$node[10]], eZPHPCreator::VARIABLE_ASSIGNMENT, array('spacing' => $spacing ) );
                            }

                            // The default is to only check if it exists
                            $modificationCheckText = "file_exists( $phpScriptText )";
                            if  ( eZTemplateCompiler::isDevelopmentModeEnabled() )
                            {
                                $modificationCheckText = "@filemtime( $phpScriptText ) > filemtime( $uriText )";
                            }
                            $php->addCodePiece( "\$resourceFound = false;\nif " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( $phpScriptText !== false and $modificationCheckText )\n{\n", array( 'spacing' => $spacing ) );
                        }
                        else
                        {
                            $php->addCodePiece( "\$resourceFound = false;\n", array( 'spacing' => $spacing ) );
                            $phpScript = $resourceMap[0]['phpscript'];
                            $phpScriptText = $php->thisVariableText( $phpScript, 0, 0, false );
                            // Not sure where this should come from
//                         if ( $resourceIndex > 0 )
//                             $php->addCodePiece( "else " );
                            // The default is to only check if it exists
                            $modificationCheckText = "file_exists( $phpScriptText )";
                            if  ( eZTemplateCompiler::isDevelopmentModeEnabled() )
                            {
                                $modificationCheckText = "@filemtime( $phpScriptText ) > filemtime( $uriText )";
                            }
                            $php->addCodePiece( "if " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( $modificationCheckText )\n{\n", array( 'spacing' => $spacing ) );

                        }

                        /* Generate code to do a namespace switch and includes the template */
                        $code = "\$resourceFound = true;\n\$namespaceStack[] = array( \$rootNamespace, \$currentNamespace );\n";
                        if ( $newRootNamespace )
                        {
                            $newRootNamespaceText = $php->thisVariableText( $newRootNamespace, 0, 0, false );
                            $code .= "\$currentNamespace = \$rootNamespace = !\$currentNamespace ? $newRootNamespaceText : ( \$currentNamespace . ':' . $newRootNamespaceText );\n";
                        }
                        else
                        {
                            $code .= "\$rootNamespace = \$currentNamespace;\n";
                        }

                        $code .=
                            "\$tpl->createLocalVariablesList();\n" .
                            "\$tpl->appendTemplateFetch( $uriText );\n" . // Make sure the template file is recorded, like in loadURIRoot
                            "include( '" . eZTemplateCompiler::TemplatePrefix() . "' . $phpScriptText );\n" .
                            "\$tpl->unsetLocalVariables();\n" .
                            "\$tpl->destroyLocalVariablesList();\n" .
                            "list( \$rootNamespace, \$currentNamespace ) = array_pop( \$namespaceStack );\n";

                        $php->addCodePiece( $code, array( 'spacing' => $spacing + 4 ) );
                        if ( $useFallbackCode )
                            $php->addCodePiece( "}\nelse\n{\n    \$resourceFound = true;\n", array( 'spacing' => $spacing ) );
                        else
                            $php->addCodePiece( "}\n", array( 'spacing' => $spacing ) );
                        $subSpacing = 4;
                    }
                    else
                    {
                        /* Yes, this is a hack, but it is required because
                         * sometimes the generated nodes after this one emit an
                         * else statement while there is no accompanied if */
                        $php->addCodePiece( "\nif " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "(false)\n{\n}\n" );
                    }

                    /* The fallback code will be added if we need to process an
                     * URI, this will also compile a template then. We need to
                     * do the namespace switch manually here otherwise the
                     * processed template will be run on the node from which
                     * the template was included from. */
                    if ( $useFallbackCode )
                    {
                        $code = "\$resourceFound = true;\n\$namespaceStack[] = array( \$rootNamespace, \$currentNamespace );\n";
                        if ( $newRootNamespace )
                        {
                            $newRootNamespaceText = $php->thisVariableText( $newRootNamespace, 0, 0, false );
                            $code .= "\$currentNamespace = \$rootNamespace = !\$currentNamespace ? $newRootNamespaceText : ( \$currentNamespace . ':' . $newRootNamespaceText );\n";
                        }
                        else
                        {
                            $code .= "\$rootNamespace = \$currentNamespace;\n";
                        }
                        $php->addCodePiece( $code );

                        $php->addCodePiece( "\$textElements = array();\n\$extraParameters = array();\n\$tpl->processURI( $uriText, true, \$extraParameters, \$textElements, \$rootNamespace, \$currentNamespace );\n\$$textName .= implode( '', \$textElements );\n", array( 'spacing' => $spacing + $subSpacing ) );
                        $php->addCodePiece( "list( \$rootNamespace, \$currentNamespace ) = array_pop( \$namespaceStack );\n" );
                    }

                    if ( $hasCompiledCode and $useFallbackCode )
                    {
                        $php->addCodePiece( "}\n", array( 'spacing' => $spacing ) );
                    }
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_NAMESPACE_CHANGE )
                {
                    $variableData = $node[1];
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[2]['spacing'] ) )
                        $spacing += $node[2]['spacing'];
                    $php->addCodePiece( "\$namespaceStack[] = \$currentNamespace;\n", array( 'spacing' => $spacing ) );
                    $php->addCodePiece( '$currentNamespace .= ( $currentNamespace ? ":" : "" ) . \''. $variableData[0][1] . '\';' . "\n", array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_INTERNAL_NAMESPACE_RESTORE )
                {
                    $spacing = $currentParameters['spacing'];
                    if ( isset( $node[1]['spacing'] ) )
                        $spacing += $node[1]['spacing'];
                    $php->addCodePiece( "\$currentNamespace = array_pop( \$namespaceStack );\n", array( 'spacing' => $spacing ) );
                }
                else if ( $nodeType == eZTemplate::NODE_OPTIMIZED_INIT )
                {
                    $code = <<<END
\$node = ( array_key_exists( \$rootNamespace, \$vars ) and array_key_exists( "node", \$vars[\$rootNamespace] ) ) ? \$vars[\$rootNamespace]["node"] : null;
if ( is_object( \$node ) )
\$object = \$node->attribute( 'object' );
if ( isset( \$object ) && is_object( \$object ) )
\$nod_{$resourceData['uniqid']} = \$object->attribute( 'data_map' );
else
\$nod_{$resourceData['uniqid']} = false;
unset( \$node, \$object );

END;
                    $php->addCodePiece($code);
                    // Tell the rest of the system that we have create the nod_* variable
                    $resourceData['node-object-cached'] = true;
                }
                else
                    eZDebug::writeWarning( "Unknown internal template node type $nodeType, ignoring node for code generation",
                                           'eZTemplateCompiler:generatePHPCodeChildren' );
            }
            else if ( $nodeType == eZTemplate::NODE_ROOT )
            {
                $children = $node[1];
                if ( $children )
                {
                    $newCurrentParameters = $currentParameters;
                    $newCurrentParameters['spacing'] += 4;
                    eZTemplateCompiler::generatePHPCodeChildren( $useComments, $php, $tpl, $children, $resourceData, $parameters, $newCurrentParameters );
                }
                continue;
            }
            else if ( $nodeType == eZTemplate::NODE_TEXT )
            {
                $text = $node[2];
                if ( $text != '' )
                {
                    $variablePlacement = $node[3];
                    $originalText = eZTemplateCompiler::fetchTemplatePiece( $variablePlacement );
                    if ( $useComments )
                    {
                        $php->addComment( "Text start:", true, true, array( 'spacing' => $currentParameters['spacing'] ) );
                        $php->addComment( $originalText, true, true, array( 'spacing' => $currentParameters['spacing'] ) );
                        $php->addComment( "Text end:", true, true, array( 'spacing' => $currentParameters['spacing'] ) );
                    }
                    $php->addVariable( eZTemplateCompiler::currentTextName( $parameters ),
                                       $text, eZPHPCreator::VARIABLE_APPEND_TEXT,
                                       array( 'spacing' => $currentParameters['spacing'] ) );
                }
                continue;
            }
            else if ( $nodeType == eZTemplate::NODE_VARIABLE )
            {
                $variableAssignmentName = $node[1];
                $variableData = $node[2];
                $variablePlacement = $node[3];

                $variableParameters = array();
                if ( isset( $node[4] ) and
                     $node[4] )
                    $variableParameters = $node[4];
                $variableOnlyExisting = isset( $node[5] ) ? $node[5] : false;
                $variableOverWrite = isset( $node[6] ) ? $node[6] : false;
                $rememberSet = isset( $node[7] ) ? $node[7] : false;

                $spacing = $currentParameters['spacing'];
                if ( isset( $variableParameters['spacing'] ) )
                    $spacing += $variableParameters['spacing'];
                $variableParameters = array_merge( array( 'variable-name' => 'var',
                                                          'text-result' => true ),
                                                   $variableParameters );
                $dataInspection = eZTemplateCompiler::inspectVariableData( $tpl,
                                                                           $variableData, $variablePlacement,
                                                                           $resourceData );
                $newNode = $node;
                $newNode[1] = false;

                $treatVariableDataAsNonObject = isset( $variableParameters['treat-value-as-non-object'] ) && $variableParameters['treat-value-as-non-object'];

                if ( $useComments )
                {
                    $php->addComment( "Variable data: " .
                                      "Is constant: " . ( $dataInspection['is-constant'] ? 'Yes' : 'No' ) .
                                      " Is variable: " . ( $dataInspection['is-variable'] ? 'Yes' : 'No' ) .
                                      " Has attributes: " . ( $dataInspection['has-attributes'] ? 'Yes' : 'No' ) .
                                      " Has operators: " . ( $dataInspection['has-operators'] ? 'Yes' : 'No' ),
                                      true, true, array( 'spacing' => $spacing )
                                      );
                    $originalText = eZTemplateCompiler::fetchTemplatePiece( $variablePlacement );
                    $php->addComment( '{' . $originalText . '}', true, true, array( 'spacing' => $spacing ) );
                }
                $generatedVariableName = $variableParameters['variable-name'];
                $assignVariable = false;
                if ( $variableAssignmentName !== false )
                {
                    if ( is_array( $variableAssignmentName ) )
                    {
                        $variableParameters['text-result'] = false;
                        $assignVariable = true;
                    }
                    else
                    {
                        $generatedVariableName = $variableAssignmentName;
                        $variableParameters['text-result'] = false;
                    }
                }

                $isStaticElement = false;
                $nodeElements = $node[2];
                $knownTypes = array();
                if ( eZTemplateNodeTool::isStaticElement( $nodeElements ) and
                     !$variableParameters['text-result'] )
                {
                    $variableText = $php->thisVariableText( eZTemplateNodeTool::elementStaticValue( $nodeElements ), 0, 0, false );
                    $isStaticElement = true;
                }
                else if ( eZTemplateNodeTool::isPHPVariableElement( $nodeElements ) and
                          !$variableParameters['text-result'] )
                {
                    $variableText = '$' . eZTemplateNodeTool::elementStaticValue( $nodeElements );
                    $isStaticElement = true;
                }
                else
                {
                    $variableText = "\$$generatedVariableName";
                    eZTemplateCompiler::generateVariableCode( $php, $tpl, $node, $knownTypes, $dataInspection,
                                                              array( 'spacing' => $spacing,
                                                                     'variable' => $generatedVariableName,
                                                                     'treat-value-as-non-object' => $treatVariableDataAsNonObject,
                                                                     'counter' => 0 ),
                                                              $resourceData );
                }

                if ( $variableParameters['text-result'] )
                {
                    $textName = eZTemplateCompiler::currentTextName( $parameters );
                    if ( count( $knownTypes ) == 0 or in_array( 'objectproxy', $knownTypes ) )
                    {
                        $php->addCodePiece( "\$$textName .= ( is_object( \$$generatedVariableName ) ? compiledFetchText( \$tpl, \$rootNamespace, \$currentNamespace, false, \$$generatedVariableName ) : \$$generatedVariableName );" .  ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "\n" .
                                            "unset( \$$generatedVariableName );\n", array( 'spacing' => $spacing ) );
                    }
                    else
                    {
                        $php->addCodePiece( "\$$textName .= \$$generatedVariableName;\n" .
                                            "unset( \$$generatedVariableName );\n", array( 'spacing' => $spacing ) );
                    }
                }
                else if ( $assignVariable )
                {
                    $namespace = $variableAssignmentName[0];
                    $namespaceScope = $variableAssignmentName[1];
                    $variableName = $variableAssignmentName[2];
                    $namespaceText = eZTemplateCompiler::generateMergeNamespaceCode( $php, $tpl, $namespace, $namespaceScope, array( 'spacing' => $spacing ), true );
                    if ( !is_string( $namespaceText ) )
                        $namespaceText = "\$namespace";
                    $variableNameText = $php->thisVariableText( $variableName, 0, 0, false );
                    $unsetVariableText = false;
                    if ( $variableOnlyExisting )
                    {
                        if ( !$isStaticElement )
                            $unsetVariableText = "\n    unset( $variableText );";
                        $php->addCodePiece( "if " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( array_key_exists( $namespaceText, \$vars ) && array_key_exists( $variableNameText, \$vars[$namespaceText] ) )\n".
                                            "{\n".
                                            "    \$vars[$namespaceText][$variableNameText] = $variableText;$unsetVariableText\n".
                                            "}",
                                            array( 'spacing' => $spacing ) );
                    }
                    else if ( $variableOverWrite )
                    {
                        if ( !$isStaticElement )
                            $unsetVariableText = "\nunset( $variableText );";
                        if ( isset( $variableParameters['local-variable'] ) )
                        {
                            $php->addCodePiece( "if ( \$tpl->hasVariable( $variableNameText, $namespaceText ) )\n{\n" ); // if the variable already exists
                            $php->addCodePiece( "    \$tpl->warning( '" . eZTemplateDefFunction::DEF_FUNCTION_NAME . "', \"Variable $variableNameText is already defined.\" );\n" );
                            $php->addCodePiece( "    \$tpl->setVariable( $variableNameText, $variableText, $namespaceText );\n}\nelse\n{\n" );
                            $php->addCodePiece( "    \$tpl->setLocalVariable( $variableNameText, $variableText, $namespaceText );\n}\n" ,
                                                array( 'spacing' => $spacing ) );
                        }
                        else
                        {
                            $php->addCodePiece( "\$vars[$namespaceText][$variableNameText] = $variableText;$unsetVariableText",
                                                array( 'spacing' => $spacing ) );
                        }

                    }
                    else if ( $rememberSet )
                    {
                        if ( !$isStaticElement )
                            $unsetVariableText = "\n    unset( $variableText );";
                        $php->addCodePiece( "if " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( !isset( \$vars[$namespaceText][$variableNameText] ) )\n".
                                            "{\n".
                                            "    \$vars[$namespaceText][$variableNameText] = $variableText;$unsetVariableText\n".
                                            "    \$setArray[$namespaceText][$variableNameText] = true;\n".
                                            "}\n",
                                            array( 'spacing' => $spacing ) );
                    }
                    else
                    {
                        if ( !$isStaticElement )
                            $unsetVariableText = "\n    unset( $variableText );";
                        $php->addCodePiece( "if " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( !isset( \$vars[$namespaceText][$variableNameText] ) )\n{\n    \$vars[$namespaceText][$variableNameText] = $variableText;$unsetVariableText\n}",
                                            array( 'spacing' => $spacing ) );
                    }
                }
                else if ( $variableAssignmentName !== false and $isStaticElement )
                {
                    $php->addCodePiece( "\$$generatedVariableName = $variableText;", array( 'spacing' => $spacing ) );
                }
                else if ( $variableAssignmentName !== false and !$isStaticElement and !$treatVariableDataAsNonObject )
                {
                    // Normal assignment from an expression, no need to anything extra
                }
                unset( $dataInspection );
            }
            else if ( $nodeType == eZTemplate::NODE_FUNCTION )
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
                    $php->addComment( "Function: $functionName, $parameterText", true, true, array( 'spacing' => $currentParameters['spacing'] ) );
                    $originalText = eZTemplateCompiler::fetchTemplatePiece( $functionPlacement );
                    $php->addComment( '{' . $originalText . '}', true, true, array( 'spacing' => $currentParameters['spacing'] ) );
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

                        $functionNameText = $php->thisVariableText( $functionName, 0, 0, false );
                        $functionChildrenText = $php->thisVariableText( $functionChildren, $codeTextLength, 0, false );

                        $inputFunctionParameters = $functionParameters;
                        if ( $functionHookCustomFunction['add-calculated-namespace'] )
                            unset( $inputFunctionParameters['name'] );
                        $functionParametersText = $php->thisVariableText( $inputFunctionParameters, $codeTextLength, 0, false );

                        $functionPlacementText = $php->thisVariableText( $functionPlacement, $codeTextLength, 0, false );
                        $functionHookText = $php->thisVariableText( $functionHook, $codeTextLength, 0, false );

                        $functionHookName = $functionHook['name'];
                        $functionHookNameText = $php->thisVariableText( $functionHookName, 0, 0, false );

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
                                    $nameText = $php->thisVariableText( $nameData, 0, 0, false );
                                    $php->addCodePiece( "if ( \$currentNamespace != '' )
    \$name = \$currentNamespace . ':' . $nameText;
else
    \$name = $nameText;\n", array( 'spacing' => $currentParameters['spacing'] ) );
                                    $codeParameters[] = "\$name";
                                }
                                else
                                {
                                    $persistence = array();
                                    $knownTypes = array();
                                    eZTemplateCompiler::generateVariableCode( $php, $tpl, $nameParameter, $knownTypes, $nameInspection,
                                                                              $persistence,
                                                                              array( 'variable' => 'name',
                                                                                     'counter' => 0 ),
                                                                              $resourceData );
                                    $php->addCodePiece( "if " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( \$currentNamespace != '' )
{
    if ( \$name != '' )
        \$name = \"\$currentNamespace:\$name\";
    else
        \$name = \$currentNamespace;
}\n", array( 'spacing' => $currentParameters['spacing'] ) );
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
                            $hookFileText = $php->thisVariableText( $hookFile, 0, 0, false );
                            $php->addCodePiece( "include_once( $hookFileText );\n", array( 'spacing' => $currentParameters['spacing'] ) );
                        }
                        else
                            $php->addCodePiece( "\$functionObject = \$tpl->fetchFunctionObject( $functionNameText );\n", array( 'spacing' => $currentParameters['spacing'] ) );
                        $php->addCodePiece( $codeText, array( 'spacing' => $currentParameters['spacing'] ) );
                    }
                    else
                    {
                        $functionNameText = $php->thisVariableText( $functionName, 0, 0, false );
                        $functionChildrenText = $php->thisVariableText( $functionChildren, 52, 0, false );
                        $functionParametersText = $php->thisVariableText( $functionParameters, 52, 0, false );
                        $functionPlacementText = $php->thisVariableText( $functionPlacement, 52, 0, false );

                        $functionHookText = $php->thisVariableText( $functionHook, 52, 0, false );
                        $functionHookName = $functionHook['name'];
                        $functionHookNameText = $php->thisVariableText( $functionHookName, 0, 0, false );
                        $functionHookParameters = $functionHook['parameters'];
                        $php->addCodePiece( "\$functionObject = \$tpl->fetchFunctionObject( $functionNameText );
\$hookResult = \$functionObject->templateHookProcess( $functionNameText, $functionHookNameText,
                                                    $functionHookText,
                                                    \$tpl,
                                                    $functionParametersText,
                                                    $functionPlacementText,
                                                    \$rootNamespace, \$currentNamespace );
", array( 'spacing' => $currentParameters['spacing'] ) );
                    }
                }
                else
                {
                    $textName = eZTemplateCompiler::currentTextName( $parameters );
                    $functionNameText = $php->thisVariableText( $functionName, 0, 0, false );
                    $functionChildrenText = $php->thisVariableText( $functionChildren, 22, 0, false );
                    $functionParametersText = $php->thisVariableText( $functionParameters, 22, 0, false );
                    $functionPlacementText = $php->thisVariableText( $functionPlacement, 22, 0, false );
                    $php->addCodePiece( "\$textElements = array();
\$tpl->processFunction( $functionNameText, \$textElements,
                       $functionChildrenText,
                       $functionParametersText,
                       $functionPlacementText,
                       \$rootNamespace, \$currentNamespace );
\$$textName .= implode( '', \$textElements );\n", array( 'spacing' => $currentParameters['spacing'] ) );
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
    static function generateMergeNamespaceCode( $php, $tpl, $namespace, $namespaceScope, $parameters = array(), $skipSimpleAssignment = false )
    {
        if ( $namespace != '' )
        {
            if ( $namespaceScope == eZTemplate::NAMESPACE_SCOPE_GLOBAL )
            {
                $php->addVariable( 'namespace', $namespace, eZPHPCreator::VARIABLE_ASSIGNMENT, $parameters );
            }
            else if ( $namespaceScope == eZTemplate::NAMESPACE_SCOPE_LOCAL )
            {
                $php->addCodePiece( "\$namespace = \$rootNamespace;
if ( \$namespace == '' )
    \$namespace = \"$namespace\";
else
    \$namespace .= ':$namespace';
", $parameters );
            }
            else if ( $namespaceScope == eZTemplate::NAMESPACE_SCOPE_RELATIVE )
            {
                $php->addCodePiece( "\$namespace = \$currentNamespace;
if ( \$namespace == '' )
    \$namespace = \"$namespace\";
else
    \$namespace .= ':$namespace';
", $parameters );
            }
        }
        else
        {
            if ( $namespaceScope == eZTemplate::NAMESPACE_SCOPE_GLOBAL )
            {
                if ( $skipSimpleAssignment )
                    return "''";
                $php->addVariable( 'namespace', '', eZPHPCreator::VARIABLE_ASSIGNMENT, $parameters );
            }
            else if ( $namespaceScope == eZTemplate::NAMESPACE_SCOPE_LOCAL )
            {
                if ( $skipSimpleAssignment )
                    return "\$rootNamespace";
                $php->addCodePiece( "\$namespace = \$rootNamespace;\n", $parameters );
            }
            else if ( $namespaceScope == eZTemplate::NAMESPACE_SCOPE_RELATIVE )
            {
                if ( $skipSimpleAssignment )
                    return "\$currentNamespace";
                $php->addCodePiece( "\$namespace = \$currentNamespace;\n", $parameters );
            }
        }
        return true;
    }

    /*!
     Generates PHP code for the variable node \a $node.
     Use generateVariableDataCode if you want to create code for arbitrary variable data structures.
    */
    static function generateVariableCode( $php, $tpl, $node, &$knownTypes, $dataInspection, $parameters, &$resourceData )
    {
        $variableData = $node[2];
        $persistence = array();
        eZTemplateCompiler::generateVariableDataCode( $php, $tpl, $variableData, $knownTypes, $dataInspection, $persistence, $parameters, $resourceData );
    }

    /*!
     Generates PHP code for the variable tree structure in \a $variableData.
     The code will contain string, numeric and identifier assignment,
     variable lookup, attribute lookup and operator execution.
     Use generateVariableCode if you want to create code for a variable tree node.
    */
    static function generateVariableDataCode( $php, $tpl, $variableData, &$knownTypes, $dataInspection, &$persistence, $parameters, &$resourceData )
    {
        $staticTypeMap = array( eZTemplate::TYPE_STRING => 'string',
                                eZTemplate::TYPE_NUMERIC => 'numeric',
                                eZTemplate::TYPE_IDENTIFIER => 'string',
                                eZTemplate::TYPE_ARRAY => 'array',
                                eZTemplate::TYPE_BOOLEAN => 'boolean' );

        $variableAssignmentName = $parameters['variable'];
        $variableAssignmentCounter = $parameters['counter'];
        $spacing = 0;
        $optimizeNode = false;
        if ( isset( $parameters['spacing'] ) )
            $spacing = $parameters['spacing'];
        if ( $variableAssignmentCounter > 0 )
            $variableAssignmentName .= $variableAssignmentCounter;

        // We need to unset the assignment variable before any elements are processed
        // This ensures that we don't work on existing variables
        $php->addCodePiece( "unset( \$$variableAssignmentName );\n", array( 'spacing' => $spacing ) );

        if ( is_array( $variableData ) )
        {
        foreach ( $variableData as $index => $variableDataItem )
        {
            $variableDataType = $variableDataItem[0];
            if ( $variableDataType == eZTemplate::TYPE_STRING or
                 $variableDataType == eZTemplate::TYPE_NUMERIC or
                 $variableDataType == eZTemplate::TYPE_IDENTIFIER or
                 $variableDataType == eZTemplate::TYPE_ARRAY or
                 $variableDataType == eZTemplate::TYPE_BOOLEAN )
            {
                $knownTypes = array_unique( array_merge( $knownTypes, array( $staticTypeMap[$variableDataType] ) ) );
                $dataValue = $variableDataItem[1];
                $dataText = $php->thisVariableText( $dataValue, 0, 0, false );
                $php->addCodePiece( "\$$variableAssignmentName = $dataText;\n", array( 'spacing' => $spacing ) );
            }
            else if ( $variableDataType == eZTemplate::TYPE_OPTIMIZED_NODE )
            {
                $optimizeNode = true;
                if ( !isset( $resourceData['node-object-cached'] ) )
                    $tpl->error( "eZTemplateCompiler" . ( $resourceData['use-comments'] ? ( ":" . __LINE__ ) : "" ), "Attribute node-object-cached of variable \$resourceData was not found but variable node eZTemplate::TYPE_OPTIMIZED_NODE is still present. This should not happen" );
                $php->addCodePiece("\$$variableAssignmentName = \$nod_{$resourceData['uniqid']};\n");

                // If optimized node is not set, use unoptimized code.
                $php->addCodePiece( "if ( !\$$variableAssignmentName )\n{\n" );
            }
            else if ( $variableDataType == eZTemplate::TYPE_OPTIMIZED_ARRAY_LOOKUP )
            {
                $code = ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/\n" ) : "" );

                // This code is used a lot so we create a variable for it
                $phpVar = "\$$variableAssignmentName";
                $indexName = "'{$variableDataItem[1][0][1]}'";

                // Add sanity checking
                $code .= ( "if ( !isset( {$phpVar}[{$indexName}] ) )\n" .
                           "{\n" .
                           "    \$tpl->error( 'eZTemplateCompiler" . ( $resourceData['use-comments'] ? ( ":" . __LINE__ ) : "" ) . "', \"PHP variable \\$phpVar"."[{$indexName}] does not exist, cannot fetch the value.\" );\n" .
                           "    $phpVar = null;\n" .
                           "}\n" .
                           "else\n    " );

                // Add the actual code
                $code .= "$phpVar = {$phpVar}[{$indexName}];\n";

                $php->addCodePiece( $code );
            }
            else if ( $variableDataType == eZTemplate::TYPE_OPTIMIZED_ATTRIBUTE_LOOKUP )
            {
                $code = ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/\n" ) : "" );
                $code .= <<<END
if ( !is_object( \${$variableAssignmentName} ) )
{
    \${$variableAssignmentName} = null;
}
else if ( \${$variableAssignmentName}->hasAttribute( "{$variableDataItem[1][0][1]}" ) )
{
    \${$variableAssignmentName} = \${$variableAssignmentName}->attribute( "{$variableDataItem[1][0][1]}" );
}

END;
                $php->addCodePiece($code);
            }
            else if ( $variableDataType == eZTemplate::TYPE_OPTIMIZED_CONTENT_CALL )
            {
                // Line number comment
                $code = ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/\n" ) : "" );

                // This code is used a lot so we create a variable for it
                $phpVar = "\$$variableAssignmentName";

                // Add sanity checking
                $code .= ( "if ( !is_object( $phpVar ) )\n" .
                           "{\n" .
                           "    \$tpl->error( 'eZTemplateCompiler" . ( $resourceData['use-comments'] ? ( ":" . __LINE__ ) : "" ) . "', \"PHP variable \\$phpVar is not an object, cannot fetch content()\" );\n" .
                           "    $phpVar = null;\n" .
                           "}\n" .
                           "else\n" .
                           "{\n" );

                // Add the actual code
                $code .= "     {$phpVar}Tmp = {$phpVar}->content();\n" .
                         "     unset( $phpVar );\n" .
                         "     $phpVar = {$phpVar}Tmp;\n" .
                         "     unset( {$phpVar}Tmp );\n}\n";

                $php->addCodePiece( $code );
            }
            else if ( $variableDataType == eZTemplate::TYPE_PHP_VARIABLE )
            {
                $knownTypes = array();
                $phpVariableName = $variableDataItem[1];
                $php->addCodePiece( "\$$variableAssignmentName = \$$phpVariableName;\n", array( 'spacing' => $spacing ) );
            }
            else if ( $variableDataType == eZTemplate::TYPE_VARIABLE )
            {
                $knownTypes = array();
                $namespace = $variableDataItem[1][0];
                $namespaceScope = $variableDataItem[1][1];
                $variableName = $variableDataItem[1][2];
                $namespaceText = eZTemplateCompiler::generateMergeNamespaceCode( $php, $tpl, $namespace, $namespaceScope, array( 'spacing' => $spacing ), true );
                if ( !is_string( $namespaceText ) )
                    $namespaceText = "\$namespace";
                $variableNameText = $php->thisVariableText( $variableName, 0, 0, false );
                $code = "unset( \$$variableAssignmentName );\n";
                $code .= "\$$variableAssignmentName = ( array_key_exists( $namespaceText, \$vars ) and array_key_exists( $variableNameText, \$vars[$namespaceText] ) ) ? \$vars[$namespaceText][$variableNameText] : null;\n";
                $php->addCodePiece( $code,
                                    array( 'spacing' => $spacing ) );
            }
            else if ( $variableDataType == eZTemplate::TYPE_ATTRIBUTE )
            {
                $knownTypes = array();
                $newParameters = $parameters;
                $newParameters['counter'] += 1;
                $tmpVariableAssignmentName = $newParameters['variable'];
                $tmpVariableAssignmentCounter = $newParameters['counter'];
                if ( $tmpVariableAssignmentCounter > 0 )
                    $tmpVariableAssignmentName .= $tmpVariableAssignmentCounter;
                if ( eZTemplateNodeTool::isStaticElement( $variableDataItem[1] ) )
                {
                    $attributeStaticValue = eZTemplateNodeTool::elementStaticValue( $variableDataItem[1] );
                    $attributeText = $php->thisVariableText( $attributeStaticValue, 0, 0, false );
                }
                else
                {
                    $newParameters['counter'] += 1;
                    $tmpKnownTypes = array();
                    eZTemplateCompiler::generateVariableDataCode( $php, $tpl, $variableDataItem[1], $tmpKnownTypes, $dataInspection,
                                                                  $persistence, $newParameters, $resourceData );
                    $newVariableAssignmentName = $newParameters['variable'];
                    $newVariableAssignmentCounter = $newParameters['counter'];
                    if ( $newVariableAssignmentCounter > 0 )
                        $newVariableAssignmentName .= $newVariableAssignmentCounter;
                    $attributeText = "\$$newVariableAssignmentName";
                }
                $php->addCodePiece( "\$$tmpVariableAssignmentName = compiledFetchAttribute( \$$variableAssignmentName, $attributeText );\n" .
                                    "unset( \$$variableAssignmentName );\n" .
                                    "\$$variableAssignmentName = \$$tmpVariableAssignmentName;\n",
                                    array( 'spacing' => $spacing ) );

                // End if optimized node object is null/false. See also eZTemplateOptimizer::optimizeVariable()
                if ( $optimizeNode &&
                     $index == 3 )
                {
                    $php->addCodePiece( "}\n" );
                }
            }
            else if ( $variableDataType == eZTemplate::TYPE_OPERATOR )
            {
                $knownTypes = array();
                $operatorParameters = $variableDataItem[1];
                $operatorName = $operatorParameters[0];
                $operatorParameters = array_splice( $operatorParameters, 1 );
                $operatorNameText = $php->thisVariableText( $operatorName, 0, 0, false );
                $operatorParametersText = $php->thisVariableText( $operatorParameters, 23, 0, false );

                $operatorHint = eZTemplateCompiler::operatorHint( $tpl, $operatorName );
                if ( isset( $operatorHint['output'] ) and $operatorHint['output'] )
                {
                    if ( isset( $operatorHint['output-type'] ) )
                    {
                        $knownType = $operatorHint['output-type'];
                        if ( is_array( $knownType ) )
                            $knownTypes = array_merge( $knownTypes, $knownType );
                        else
                            $knownTypes[] = $knownType;
                        $knownTypes = array_unique( $knownTypes );
                    }
                    else
                        $knownTypes[] = 'static';
                }

                $php->addCodePiece( "if (! isset( \$$variableAssignmentName ) ) \$$variableAssignmentName = NULL;\n", array ( 'spacing' => $spacing ) );
                $php->addCodePiece( "while " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( is_object( \$$variableAssignmentName ) and method_exists( \$$variableAssignmentName, 'templateValue' ) )\n" .
                                                  "    \$$variableAssignmentName = \$$variableAssignmentName" . "->templateValue();\n" );
                $php->addCodePiece( "\$" . $variableAssignmentName . "Data = array( 'value' => \$$variableAssignmentName );
\$tpl->processOperator( $operatorNameText,
                       $operatorParametersText,
                       \$rootNamespace, \$currentNamespace, \$" . $variableAssignmentName . "Data, false, false );
\$$variableAssignmentName = \$" . $variableAssignmentName . "Data['value'];
unset( \$" . $variableAssignmentName . "Data );\n",
                                    array( 'spacing' => $spacing ) );
            }
            else if ( $variableDataType == eZTemplate::TYPE_VOID )
            {
            }
            else if ( $variableDataType == eZTemplate::TYPE_DYNAMIC_ARRAY )
            {
                $knownTypes = array_unique( array_merge( $knownTypes, array( 'array' ) ) );
                $code = '%output% = array( ';

                $matchMap = array( '%input%', '%output%' );
                $replaceMap = array( '$' . $variableAssignmentName, '$' . $variableAssignmentName );
                $unsetList = array();
                $counter = 1;
                $paramCount = 0;

                $values = $variableDataItem[2];
                $newParameters = $parameters;
                foreach ( $values as $key => $value )
                {
                    if ( $paramCount != 0 )
                    {
                        $code .= ', ';
                    }
                    ++$paramCount;
                    $code .= '\'' . $key . '\' => ';
                    if( eZTemplateNodeTool::isStaticElement( $value ) )
                    {
                        $code .= eZPHPCreator::variableText( eZTemplateNodeTool::elementStaticValue( $value ), 0, 0, false );
                        continue;
                    }
                    $code .= '%' . $counter . '%';
                    $newParameters['counter'] += 1;
                    $newVariableAssignmentName = $newParameters['variable'];
                    $newVariableAssignmentCounter = $newParameters['counter'];
                    if ( $newVariableAssignmentCounter > 0 )
                        $newVariableAssignmentName .= $newVariableAssignmentCounter;
                    $matchMap[] = '%' . $counter . '%';
                    $replaceMap[] = '$' . $newVariableAssignmentName;
                    $unsetList[] = $newVariableAssignmentName;
                    $tmpKnownTypes = array();
                    eZTemplateCompiler::generateVariableDataCode( $php, $tpl, $value, $tmpKnownTypes, $dataInspection,
                                                                  $persistence, $newParameters, $resourceData );
                    ++$counter;
                }

                $code .= ' );';
                $code = str_replace( $matchMap, $replaceMap, $code );
                $php->addCodePiece( $code, array( 'spacing' => $spacing ) );
                $php->addVariableUnsetList( $unsetList, array( 'spacing' => $spacing ) );
            }
            else if ( $variableDataType == eZTemplate::TYPE_INTERNAL_CODE_PIECE )
            {
                $code = $variableDataItem[1];
                $values = false;
                $matchMap = array( '%input%', '%output%' );
                $replaceMap = array( '$' . $variableAssignmentName, '$' . $variableAssignmentName );
                $unsetList = array();
                $counter = 1;
                if ( isset( $variableDataItem[3] ) && is_array( $variableDataItem[3] ) )
                {
                    $newParameters = $parameters;
                    $values = $variableDataItem[3];
                    foreach ( $values as $value )
                    {
                        $newParameters['counter'] += 1;
                        $newVariableAssignmentName = $newParameters['variable'];
                        $newVariableAssignmentCounter = $newParameters['counter'];
                        if ( $newVariableAssignmentCounter > 0 )
                            $newVariableAssignmentName .= $newVariableAssignmentCounter;
                        if ( eZTemplateNodeTool::isStaticElement( $value ) )
                        {
                            $staticValue = eZTemplateNodeTool::elementStaticValue( $value );
                            $staticValueText = $php->thisVariableText( $staticValue, 0, 0, false );
                            if ( preg_match( "/%code$counter%/", $code ) )
                            {
                                $matchMap[] = '%code' . $counter . '%';
                                $replaceMap[] = '';
                            }
                            $matchMap[] = '%' . $counter . '%';
                            $replaceMap[] = $staticValueText;
                        }
                        else
                        {
                            $matchMap[] = '%' . $counter . '%';
                            $replaceMap[] = '$' . $newVariableAssignmentName;
                            $unsetList[] = $newVariableAssignmentName;
                            if ( preg_match( "/%code$counter%/", $code ) )
                            {
                                $tmpPHP = new eZPHPCreator( '', '', eZTemplateCompiler::TemplatePrefix() );
                                $tmpKnownTypes = array();
                                eZTemplateCompiler::generateVariableDataCode( $tmpPHP, $tpl, $value, $tmpKnownTypes, $dataInspection,
                                                                              $persistence, $newParameters, $resourceData );
                                $newCode = $tmpPHP->fetch( false );
                                if ( count( $tmpKnownTypes ) == 0 or in_array( 'objectproxy', $tmpKnownTypes ) )
                                {
                                    $newCode .= ( "while " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( is_object( \$$newVariableAssignmentName ) and method_exists( \$$newVariableAssignmentName, 'templateValue' ) )\n" .
                                                  "    \$$newVariableAssignmentName = \$$newVariableAssignmentName" . "->templateValue();\n" );
                                }
                                $matchMap[] = '%code' . $counter . '%';
                                $replaceMap[] = $newCode;
                            }
                            else
                            {
                                $tmpKnownTypes = array();
                                eZTemplateCompiler::generateVariableDataCode( $php, $tpl, $value, $tmpKnownTypes, $dataInspection,
                                                                              $persistence, $newParameters, $resourceData );
                                if ( !$parameters['treat-value-as-non-object'] and ( count( $tmpKnownTypes ) == 0 or in_array( 'objectproxy', $tmpKnownTypes ) ) )
                                {
                                    $php->addCodePiece( "while " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( is_object( \$$newVariableAssignmentName ) and method_exists( \$$newVariableAssignmentName, 'templateValue' ) )\n" .
                                                        "    \$$newVariableAssignmentName = \$$newVariableAssignmentName" . "->templateValue();\n",
                                                        array( 'spacing' => $spacing ) );
                                }
                            }
                        }
                        ++$counter;
                    }
                }
                if ( isset( $variableDataItem[4] ) && ( $variableDataItem[4] !== false ) )
                {
                    $values = $variableDataItem[4];

                    for ( $i = 0; $i < $values; $i++ )
                    {
                        $newParameters['counter'] += 1;
                        $newVariableAssignmentName = $newParameters['variable'];
                        $newVariableAssignmentCounter = $newParameters['counter'];
                        if ( $newVariableAssignmentCounter > 0 )
                            $newVariableAssignmentName .= $newVariableAssignmentCounter;
                        $matchMap[] = '%tmp' . ( $i + 1 ) . '%';
                        $replaceMap[] = '$' . $newVariableAssignmentName;
                        $unsetList[] = $newVariableAssignmentName;
                    }
                }
                if ( isset( $variableDataItem[5] ) and $variableDataItem[5] )
                {
                    if ( is_array( $variableDataItem[5] ) )
                        $knownTypes = array_unique( array_merge( $knownTypes, $variableDataItem[5] ) );
                    else if ( is_string( $variableDataItem[5] ) )
                        $knownTypes = array_unique( array_merge( $knownTypes, array( $variableDataItem[5] ) ) );
                    else
                        $knownTypes = array_unique( array_merge( $knownTypes, array( 'static' ) ) );
                }
                $code = str_replace( $matchMap, $replaceMap, $code );
                $php->addCodePiece( $code, array( 'spacing' => $spacing ) );
                $php->addVariableUnsetList( $unsetList, array( 'spacing' => $spacing ) );
            }
        }
        }
        // After the entire expression line is done we try to extract the actual value if proxies are used
        $php->addCodePiece( "if (! isset( \$$variableAssignmentName ) ) \$$variableAssignmentName = NULL;\n" );
        $php->addCodePiece( "while " . ( $resourceData['use-comments'] ? ( "/*TC:" . __LINE__ . "*/" ) : "" ) . "( is_object( \$$variableAssignmentName ) and method_exists( \$$variableAssignmentName, 'templateValue' ) )\n" .
                            "    \$$variableAssignmentName = \$$variableAssignmentName" . "->templateValue();\n" );
    }
}

?>
