<?php
//
// Definition of eZTemplate class
//
// Created on: <01-Mar-2002 13:49:57 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file eztemplate.php
 Template system manager.
*/

/*! \defgroup eZTemplate Template system */

/*!
  \class eZTemplate eztemplate.php
  \ingroup eZTemplate
  \brief The main manager for templates

  The template systems allows for separation of code and
  layout by moving the layout part into template files. These
  template files are parsed and processed with template variables set
  by the PHP code.

  The template system in itself is does not do much, it parses template files
  according to a rule set sets up a tree hierarchy and process the data
  using functions and operators. The standard template system comes with only
  a few functions and no operators, it is meant for these functions and operators
  to be specified by the users of the template system. But for simplicity a few
  help classes is available which can be easily enabled.

  The classes are:
  - eZTemplateDelimitFunction - Inserts the left and right delimiter which are normally parsed.
  - eZTemplateSectionFunction - Allows for conditional blocks and loops.
  - eZTemplateIncludeFunction - Includes external templates
  - eZTemplateSequenceFunction - Creates sequences arrays
  - eZTemplateSwitchFunction - Conditional output of template

  - eZTemplatePHPOperator - Allows for easy redirection of operator names to PHP functions.
  - eZTemplateLocaleOperator - Allows for locale conversions.
  - eZTemplateArrayOperator - Creates arrays
  - eZTemplateAttributeOperator - Displays contents of template variables, useful for debugging
  - eZTemplateImageOperator - Converts text to image
  - eZTemplateLogicOperator - Various logical operators for boolean handling
  - eZTemplateUnitOperator - Unit conversion and display

  To enable these functions and operator use registerFunction and registerOperator.

  In keeping with the spirit of being simple the template system does not know how
  to get the template files itself. Instead it relies on resource handlers, these
  handlers fetches the template files using different kind of transport mechanism.
  For simplicity a default resource class is available, eZTemplateFileResource fetches
  templates from the filesystem.

  The parser process consists of three passes, each pass adds a new level of complexity.
  The first pass strips text from template blocks which starts with a left delimiter and
  ends with a right delimiter (default is { and } ), and places them in an array.
  The second pass iterates the text and block elements and removes newlines from
  text before function blocks and text after function blocks.
  The third pass builds the tree according the function rules.

  Processing is done by iterating over the root of the tree, if a text block is found
  the text is appended to the result text. If a variable or contant is it's data is extracted
  and any operators found are run on it before fetching the result and appending it to
  the result text. If a function is found the function is called with the parameters
  and it's up to the function handle children if any.

  Constants and template variables will usually be called variables since there's little
  difference. A template variable expression will start with a $ and consists of a
  namespace (optional) a name and attribues(optional). The variable expression
  \verbatim $root:var.attr1 \endverbatim exists in the "root" namespace, has the name "var" and uses the
  attribute "attr1". Some functions will create variables on demand, to avoid name conflicts
  namespaces were introduced, each function will place the new variables in a namespace
  specified in the template file. Attribues are used for fetching parts of the variable,
  for instance an element in an array or data in an object. Since the syntax is the
  same for arrays and objects the PHP code can use simple arrays when speed is required,
  the template code will not care.
  A different syntax is also available when you want to access an attribute using a variable.
  For instance \verbatim $root:var[$attr_var] \endverbatim, if the variable $attr_var contains "attr1" it would
  access the same attribute as in the first example.

  The syntax for operators is a | and a name, optionally parameters can be specified with
  ( and ) delimited with ,. Valid operators are \verbatim |upcase, |l10n(date) \endverbatim.

  Functions look a lot like HTML/XML tags. The function consists of a name and parameters
  which are assigned using the param=value syntax. Some parameters may be required while
  others may be optionally, the exact behaviour is specified by each function.
  Valid functions are \verbatim "section name=abc loop=4" \endverbatim

  Example of usage:
\code
// Init template
$tpl = eZTemplate::instance();

$tpl->registerOperators( new eZTemplatePHPOperator( array( "upcase" => "strtoupper",
                                                           "reverse" => "strrev" ) ) );
$tpl->registerOperators( new eZTemplateLocaleOperator() );
$tpl->registerFunction( "section", new eZTemplateSectionFunction( "section" ) );
$tpl->registerFunctions( new eZTemplateDelimitFunction() );

$tpl->setVariable( "my_var", "{this value set by variable}", "test" );
$tpl->setVariable( "my_arr", array( "1st", "2nd", "third", "fjerde" ) );
$tpl->setVariable( "multidim", array( array( "a", "b" ),
                                      array( "c", "d" ),
                                      array( "e", "f" ),
                                      array( "g", "h" ) ) );

class mytest
{
    function mytest( $n, $s )
    {
        $this->n = $n;
        $this->s = $s;
    }

    function hasAttribute( $attr )
    {
        return ( $attr == "name" || $attr == "size" );
    }

    function attribute( $attr )
    {
        switch ( $attr )
        {
            case "name";
                return $this->n;
            case "size";
                return $this->s;
            default:
                $retAttr = null;
                return $retAttr;
        }
    }

};

$tpl->setVariable( "multidim_obj", array( new mytest( "jan", 200 ),
                                          new mytest( "feb", 200 ),
                                          new mytest( "john", 200 ),
                                          new mytest( "doe", 50 ) ) );
$tpl->setVariable( "curdate", time() );

$tpl->display( "lib/eztemplate/example/test.tpl" );

// test.tpl

{section name=outer loop=4}
123
{delimit}::{/delimit}
{/section}

{literal test=1} This is some {blah arg1="" arg2="abc" /} {/literal}

<title>This is a test</title>
<table border="1">
<tr><th>{$test:my_var}
{"some text!!!"|upcase|reverse}</th></tr>
{section name=abc loop=$my_arr}
<tr><td>{$abc:item}</td></tr>
{/section}
</table>

<table border="1">
{section name=outer loop=$multidim}
<tr>
{section name=inner loop=$outer:item}
<td>{$inner:item}</td>
{/section}
</tr>
{/section}
</table>

<table border="1">
{section name=outer loop=$multidim_obj}
<tr>
<td>{$outer:item.name}</td>
<td>{$outer:item.size}</td>
</tr>
{/section}
</table>

{section name=outer loop=$nonexistingvar}
<b><i>Dette skal ikke vises</b></i>
{section-else}
<b><i>This is shown when the {ldelim}$loop{rdelim} variable is non-existant</b></i>
{/section}


Denne koster {1.4|l10n(currency)}<br>
{-123456789|l10n(number)}<br>
{$curdate|l10n(date)}<br>
{$curdate|l10n(shortdate)}<br>
{$curdate|l10n(time)}<br>
{$curdate|l10n(shorttime)}<br>
{include file="test2.tpl"/}

\endcode
*/

class eZTemplate
{
    const RESOURCE_FETCH = 1;
    const RESOURCE_QUERY = 2;

    const ELEMENT_TEXT = 1;
    const ELEMENT_SINGLE_TAG = 2;
    const ELEMENT_NORMAL_TAG = 3;
    const ELEMENT_END_TAG = 4;
    const ELEMENT_VARIABLE = 5;
    const ELEMENT_COMMENT = 6;

    const NODE_ROOT = 1;
    const NODE_TEXT = 2;
    const NODE_VARIABLE = 3;
    const NODE_FUNCTION = 4;
    const NODE_OPERATOR = 5;


    const NODE_INTERNAL = 100;
    const NODE_INTERNAL_CODE_PIECE = 101;

    const NODE_INTERNAL_VARIABLE_SET = 105;
    const NODE_INTERNAL_VARIABLE_UNSET = 102;

    const NODE_INTERNAL_NAMESPACE_CHANGE = 103;
    const NODE_INTERNAL_NAMESPACE_RESTORE = 104;

    const NODE_INTERNAL_WARNING = 120;
    const NODE_INTERNAL_ERROR = 121;

    const NODE_INTERNAL_RESOURCE_ACQUISITION = 140;
    const NODE_OPTIMIZED_RESOURCE_ACQUISITION = 141;

    const NODE_INTERNAL_OUTPUT_ASSIGN = 150;
    const NODE_INTERNAL_OUTPUT_READ = 151;
    const NODE_INTERNAL_OUTPUT_INCREASE = 152;
    const NODE_INTERNAL_OUTPUT_DECREASE = 153;

    const NODE_INTERNAL_OUTPUT_SPACING_INCREASE = 160;
    const NODE_INTERNAL_SPACING_DECREASE = 161;

    const NODE_OPTIMIZED_INIT = 201;


    const NODE_USER_CUSTOM = 1000;


    const TYPE_VOID = 0;
    const TYPE_STRING = 1;
    const TYPE_NUMERIC = 2;
    const TYPE_IDENTIFIER = 3;
    const TYPE_VARIABLE = 4;
    const TYPE_ATTRIBUTE = 5;
    const TYPE_OPERATOR = 6;
    const TYPE_BOOLEAN = 7;
    const TYPE_ARRAY = 8;
    const TYPE_DYNAMIC_ARRAY = 9;

    const TYPE_INTERNAL = 100;
    const TYPE_INTERNAL_CODE_PIECE = 101;
    const TYPE_PHP_VARIABLE = 102;

    const TYPE_OPTIMIZED_NODE = 201;
    const TYPE_OPTIMIZED_ARRAY_LOOKUP = 202;
    const TYPE_OPTIMIZED_CONTENT_CALL = 203;
    const TYPE_OPTIMIZED_ATTRIBUTE_LOOKUP = 204;

    const TYPE_INTERNAL_STOP = 999;


    const TYPE_STRING_BIT = 1;
    const TYPE_NUMERIC_BIT = 2;
    const TYPE_IDENTIFIER_BIT = 4;
    const TYPE_VARIABLE_BIT = 8;
    const TYPE_ATTRIBUTE_BIT = 16;
    const TYPE_OPERATOR_BIT = 32;

    const TYPE_NONE = 0;

    const TYPE_ALL = 63;

    const TYPE_BASIC = 47;

    const TYPE_MODIFIER_MASK = 48;

    const NAMESPACE_SCOPE_GLOBAL = 1;
    const NAMESPACE_SCOPE_LOCAL = 2;
    const NAMESPACE_SCOPE_RELATIVE = 3;

    const DEBUG_INTERNALS = false;

    const FILE_ERRORS = 1;

    /*!
     Intializes the template with left and right delimiters being { and },
     and a file resource. The literal tag "literal" is also registered.
    */
    function eZTemplate()
    {
        $this->Tree = array( eZTemplate::NODE_ROOT, false );
        $this->LDelim = "{";
        $this->RDelim = "}";

        $this->IncludeText = array();
        $this->IncludeOutput = array();

        $this->registerLiteral( "literal" );

        $res = new eZTemplateFileResource();
        $this->DefaultResource = $res;
        $this->registerResource( $res );

        $this->Resources = array();
        $this->Text = null;

        $this->IsCachingAllowed = true;

        $this->resetErrorLog();

        $this->AutoloadPathList = array( 'lib/eztemplate/classes/' );
        $this->Variables = array();
        $this->LocalVariablesNamesStack = array();
        $this->CurrentLocalVariablesNames = null;
        $this->Functions = array();
        $this->FunctionAttributes = array();

        $this->TestCompile = false;

        $ini = eZINI::instance( 'template.ini' );
        if ( $ini->hasVariable( 'ControlSettings', 'MaxLevel' ) )
             $this->MaxLevel = $ini->variable( 'ControlSettings', 'MaxLevel' );
        require_once('kernel/common/i18n.php');
        $this->MaxLevelWarning = ezi18n( 'lib/template',
                                         'The maximum nesting level of %max has been reached. The execution is stopped to avoid infinite recursion.',
                                         '',
                                         array( '%max' => $this->MaxLevel ) );
        eZDebug::createAccumulatorGroup( 'template_total', 'Template Total' );

        $this->TemplatesUsageStatistics = array();
        // Array of templates which are used in a single fetch()
        $this->TemplateFetchList = array();

        $this->ForeachCounter = 0;
        $this->ForCounter     = 0;
        $this->WhileCounter   = 0;
        $this->DoCounter      = 0;
        $this->ElseifCounter  = 0;
    }

    /*!
     Returns the left delimiter being used.
    */
    function leftDelimiter()
    {
        return $this->LDelim;
    }

    /*!
     Returns the right delimiter being used.
    */
    function rightDelimiter()
    {
        return $this->RDelim;
    }

    /*!
     Sets the left delimiter.
    */
    function setLeftDelimiter( $delim )
    {
        $this->LDelim = $delim;
    }

    /*!
     Sets the right delimiter.
    */
    function setRightDelimiter( $delim )
    {
        $this->RDelim = $delim;
    }

    /*!
     Fetches the result of the template file and displays it.
     If $template is supplied it will load this template file first.
    */
    function display( $template = false, $extraParameters = false )
    {
        $output = $this->fetch( $template, $extraParameters );
        if ( $this->ShowDetails )
        {
            echo '<h1>Result:</h1>' . "\n";
            echo '<hr/>' . "\n";
        }
        echo "$output";
        if ( $this->ShowDetails )
        {
            echo '<hr/>' . "\n";
        }
        if ( $this->ShowDetails )
        {
            echo "<h1>Template data:</h1>";
            echo "<p class=\"filename\">" . $template . "</p>";
            echo "<pre class=\"example\">" . htmlspecialchars( $this->Text ) . "</pre>";
            reset( $this->IncludeText );
            while ( ( $key = key( $this->IncludeText ) ) !== null )
            {
                $item = $this->IncludeText[$key];
                echo "<p class=\"filename\">" . $key . "</p>";
                echo "<pre class=\"example\">" . htmlspecialchars( $item ) . "</pre>";
                next( $this->IncludeText );
            }
            echo "<h1>Result text:</h1>";
            echo "<p class=\"filename\">" . $template . "</p>";
            echo "<pre class=\"example\">" . htmlspecialchars( $output ) . "</pre>";
            reset( $this->IncludeOutput );
            while ( ( $key = key( $this->IncludeOutput ) ) !== null )
            {
                $item = $this->IncludeOutput[$key];
                echo "<p class=\"filename\">" . $key . "</p>";
                echo "<pre class=\"example\">" . htmlspecialchars( $item ) . "</pre>";
                next( $this->IncludeOutput );
            }
        }
    }

    /*!
     * Initialize list of local variables for the current template.
     * The list contains only names of variables.
     */
    function createLocalVariablesList()
    {
        $this->LocalVariablesNamesStack[] = array();
        $this->CurrentLocalVariablesNames =& $this->LocalVariablesNamesStack[ count( $this->LocalVariablesNamesStack ) - 1];
    }

    /*!
     * Check if the given local variable exists.
     */
    function hasLocalVariable( $varName, $rootNamespace )
    {
        return ( array_key_exists( $rootNamespace, $this->CurrentLocalVariablesNames ) &&
                 array_key_exists( $varName, $this->CurrentLocalVariablesNames[$rootNamespace] ) );
    }

    /*!
     * Create a local variable.
     */
    function setLocalVariable( $varName, $varValue, $rootNamespace )
    {
        $this->CurrentLocalVariablesNames[$rootNamespace][$varName] = 1;
        $this->setVariable( $varName, $varValue, $rootNamespace );
    }

    /*!
     * Destroy a local variable.
     */
    function unsetLocalVariable( $varName, $rootNamespace )
    {
        if ( !$this->hasLocalVariable( $varName, $rootNamespace ) )
            return;

        $this->unsetVariable( $varName, $rootNamespace );
        unset( $this->CurrentLocalVariablesNames[$rootNamespace][$varName] );
    }

    /*!
     * Destroy all local variables defined in the current template.
     */
    function unsetLocalVariables()
    {
        foreach ( $this->CurrentLocalVariablesNames as $ns => $vars )
        {
            foreach ( $vars as $var => $val )
                $this->unsetLocalVariable( $var, $ns );
        }
    }

    /*!
     * Destroy list of local variables defined in the current (innermost) template.
     */
    function destroyLocalVariablesList()
    {
        array_pop( $this->LocalVariablesNamesStack );

        if ( $this->LocalVariablesNamesStack )
            $this->CurrentLocalVariablesNames =& $this->LocalVariablesNamesStack[ count( $this->LocalVariablesNamesStack ) - 1];
        else
            unset( $this->CurrentLocalVariablesNames );
    }

    /*!
     Tries to fetch the result of the template file and returns it.
     If $template is supplied it will load this template file first.
    */
    function fetch( $template = false, $extraParameters = false, $returnResourceData = false )
    {
        $this->resetErrorLog();
        // Reset fetch list when a new fetch is started
        $this->TemplateFetchList = array();

        eZDebug::accumulatorStart( 'template_total' );
        eZDebug::accumulatorStart( 'template_load', 'template_total', 'Template load' );
        $root = null;
        if ( is_string( $template ) )
        {
            $resourceData = $this->loadURIRoot( $template, true, $extraParameters );
            if ( $resourceData and
                 $resourceData['root-node'] !== null )
                $root =& $resourceData['root-node'];
        }
        eZDebug::accumulatorStop( 'template_load' );
        if ( $resourceData['locales'] && count( $resourceData['locales'] ) )
        {
            $savedLocale = setlocale( LC_CTYPE, null );
            setlocale( LC_CTYPE, $resourceData['locales'] );
        }

        $text = "";

        if ( $root !== null or
             $resourceData['compiled-template'] )
        {
            if ( $this->ShowDetails )
                eZDebug::addTimingPoint( "Process" );
            eZDebug::accumulatorStart( 'template_processing', 'template_total', 'Template processing' );

            $templateCompilationUsed = false;
            if ( $resourceData['compiled-template'] )
            {
                $textElements = array();
                if ( $this->executeCompiledTemplate( $resourceData, $textElements, "", "", $extraParameters ) )
                {
                    $text = implode( '', $textElements );
                    $templateCompilationUsed = true;
                }
            }
            if ( !$templateCompilationUsed )
            {
                if ( eZTemplate::isDebugEnabled() )
                {
                    $fname = $resourceData['template-filename'];
                    eZDebug::writeDebug( "FETCH START URI: $template, $fname" );
                }
                $this->process( $root, $text, "", "" );
                if ( eZTemplate::isDebugEnabled() )
                    eZDebug::writeDebug( "FETCH END URI: $template, $fname" );
            }

            eZDebug::accumulatorStop( 'template_processing' );
            if ( $this->ShowDetails )
                eZDebug::addTimingPoint( "Process done" );
        }

        eZDebug::accumulatorStop( 'template_total' );

        if ( $resourceData['locales'] && count( $resourceData['locales'] ) )
        {
            setlocale( LC_CTYPE, $savedLocale );
        }

        if ( $returnResourceData )
        {
            $resourceData['result_text'] = $text;
            return $resourceData;
        }
        return $text;
    }

    function process( $root, &$text, $rootNamespace, $currentNamespace )
    {
        $this->createLocalVariablesList();

        $textElements = array();
        $this->processNode( $root, $textElements, $rootNamespace, $currentNamespace );
        if ( is_array( $textElements ) )
            $text = implode( '', $textElements );
        else
            $text = $textElements;

        $this->unsetLocalVariables();
        $this->destroyLocalVariablesList();
    }

    function processNode( $node, &$textElements, $rootNamespace, $currentNamespace )
    {
        $rslt = null;
        $nodeType = $node[0];
        if ( $nodeType == eZTemplate::NODE_ROOT )
        {
            $children = $node[1];
            if ( $children )
            {
                foreach ( $children as $child )
                {
                    $this->processNode( $child, $textElements, $rootNamespace, $currentNamespace );
                    if ( !is_array( $textElements ) )
                        eZDebug::writeError( "Textelements is no longer array: '$textElements'",
                                             'eztemplate::processNode::root' );
                }
            }
        }
        else if ( $nodeType == eZTemplate::NODE_TEXT )
        {
            $textElements[] = $node[2];
        }
        else if ( $nodeType == eZTemplate::NODE_VARIABLE )
        {
            $variableData = $node[2];
            $variablePlacement = $node[3];
            $rslt = $this->processVariable( $textElements, $variableData, $variablePlacement, $rootNamespace, $currentNamespace );
            if ( !is_array( $textElements ) )
                eZDebug::writeError( "Textelements is no longer array: '$textElements'",
                                     'eztemplate::processNode::variable' );
        }
        else if ( $nodeType == eZTemplate::NODE_FUNCTION )
        {
            $functionChildren = $node[1];
            $functionName = $node[2];
            $functionParameters = $node[3];
            $functionPlacement = $node[4];
            $rslt = $this->processFunction( $functionName, $textElements, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace );
            if ( !is_array( $textElements ) )
                eZDebug::writeError( "Textelements is no longer array: '$textElements'",
                                     "eztemplate::processNode::function( '$functionName' )" );
        }

        return $rslt;
    }

    function processVariable( &$textElements, $variableData, $variablePlacement, $rootNamespace, $currentNamespace )
    {
        $value = $this->elementValue( $variableData, $rootNamespace, $currentNamespace, $variablePlacement );
        $this->appendElementText( $textElements, $value, $rootNamespace, $currentNamespace );
    }

    function processFunction( $functionName, &$textElements, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        // Note: This code piece is replicated in the eZTemplateCompiler,
        //       if this code is changed the replicated code must be updated as well.
        $func = $this->Functions[$functionName];
        if ( is_array( $func ) )
        {
            $this->loadAndRegisterFunctions( $this->Functions[$functionName] );
            $func = $this->Functions[$functionName];
        }
        if ( isset( $func ) and
             is_object( $func ) )
        {
            if ( eZTemplate::isMethodDebugEnabled() )
                eZDebug::writeDebug( "START FUNCTION: $functionName" );
            $value = $func->process( $this, $textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace );
            if ( eZTemplate::isMethodDebugEnabled() )
                eZDebug::writeDebug( "END FUNCTION: $functionName" );
            return $value;
        }
        else
        {
            $this->warning( "", "Function \"$functionName\" is not registered" );
        }
    }

    function fetchFunctionObject( $functionName )
    {
        $func = $this->Functions[$functionName];
        if ( is_array( $func ) )
        {
            $this->loadAndRegisterFunctions( $this->Functions[$functionName] );
            $func = $this->Functions[$functionName];
        }
        return $func;
    }

    /*!
     Loads the template using the URI $uri and parses it.
     \return The root node of the tree if \a $returnResourceData is false,
             if \c true the entire resource data structure.
    */
    function load( $uri, $extraParameters = false, $returnResourceData = false )
    {
        $resourceData = $this->loadURIRoot( $uri, true, $extraParameters );
        if ( !$resourceData or
             $resourceData['root-node'] === null )
        {
            $retValue = null;
            return $retValue;
        }
        else
            return $resourceData['root-node'];
    }

    function parse( $sourceText, &$rootElement, $rootNamespace, &$resourceData )
    {
        $parser = eZTemplateMultiPassParser::instance();
        $parser->parse( $this, $sourceText, $rootElement, $rootNamespace, $resourceData );
    }

    function loadURIData( $resourceObject, $uri, $resourceName, $template, &$extraParameters, $displayErrors = true )
    {
        $resourceData = $this->resourceData( $resourceObject, $uri, $resourceName, $template );

        $resourceData['text'] = null;
        $resourceData['root-node'] = null;
        $resourceData['compiled-template'] = false;
        $resourceData['time-stamp'] = null;
        $resourceData['key-data'] = null;
        $resourceData['locales'] = null;

        if ( !$resourceObject->handleResource( $this, $resourceData, eZTemplate::RESOURCE_FETCH, $extraParameters ) )
        {
            $resourceData = null;
            if ( $displayErrors )
                $this->warning( "", "No template could be loaded for \"$template\" using resource \"$resourceName\"" );
        }
        return $resourceData;
    }

    /*!
     \static
     Creates a resource data structure of the parameters and returns it.
     This structure is passed to various parts of the template system.

     \note If you only have the URI you should call resourceFor() first to
           figure out the resource handler.
    */
    function resourceData( $resourceObject, $uri, $resourceName, $templateName )
    {
        $resourceData = array();
        $resourceData['uri'] = $uri;
        $resourceData['resource'] = $resourceName;
        $resourceData['template-name'] = $templateName;
        $resourceData['template-filename'] = $templateName;
        $resourceData['handler'] = $resourceObject;
        $resourceData['test-compile'] = $this->TestCompile;
        return $resourceData;
    }

    /*!
     Loads the template using the URI $uri and returns a structure with the text and timestamp,
     false otherwise.
     The structure keys are:
     - "text", the text.
     - "time-stamp", the timestamp.
    */
    function loadURIRoot( $uri, $displayErrors = true, &$extraParameters )
    {
        $res = "";
        $template = "";
        $resobj = $this->resourceFor( $uri, $res, $template );

        if ( !is_object( $resobj ) )
        {
            if ( $displayErrors )
                $this->warning( "", "No resource handler for \"$res\" and no default resource handler, aborting." );
            return null;
        }

        $canCache = true;
        if ( !$resobj->servesStaticData() )
            $canCache = false;
        if ( !$this->isCachingAllowed() )
            $canCache = false;

        $resourceData = $this->loadURIData( $resobj, $uri, $res, $template, $extraParameters, $displayErrors );

        if ( $resourceData )
        {
            $root = null;
            eZTemplate::appendTemplateToStatisticsIfNeeded( $resourceData['template-name'], $resourceData['template-filename'] );
            $this->appendTemplateFetch( $resourceData['template-filename'] );

            if ( !$resourceData['compiled-template'] and
                 $resourceData['root-node'] === null )
            {
                $resourceData['root-node'] = array( eZTemplate::NODE_ROOT, false );
                $templateText = $resourceData["text"];
                $keyData = $resourceData['key-data'];
                $this->setIncludeText( $uri, $templateText );
                $rootNamespace = '';
                $this->parse( $templateText, $resourceData['root-node'], $rootNamespace, $resourceData );

                if ( eZTemplate::isDebugEnabled() )
                {
                    $this->appendDebugNodes( $resourceData['root-node'], $resourceData );
                }

                if ( $canCache )
                    $resobj->setCachedTemplateTree( $keyData, $uri, $res, $template, $extraParameters, $resourceData['root-node'] );
            }
            if ( !$resourceData['compiled-template'] and
                 $canCache and
                 $this->canCompileTemplate( $resourceData, $extraParameters ) )
            {
                $generateStatus = $this->compileTemplate( $resourceData, $extraParameters );
                if ( $generateStatus )
                    $resourceData['compiled-template'] = true;
            }
        }

        return $resourceData;
    }

    function processURI( $uri, $displayErrors = true, &$extraParameters,
                         &$textElements, $rootNamespace, $currentNamespace )
    {
        $this->Level++;
        if ( $this->Level > $this->MaxLevel )
        {
            eZDebug::writeError( $this->MaxLevelWarning,  "eZTemplate:processURI Level: $this->Level @ $uri" );
            $textElements[] = $this->MaxLevelWarning;
            $this->Level--;
            return;
        }
        $resourceData = $this->loadURIRoot( $uri, $displayErrors, $extraParameters );
        if ( !$resourceData or
             ( !$resourceData['compiled-template'] and
               $resourceData['root-node'] === null ) )
        {
            $this->Level--;
            return;
        }

        $templateCompilationUsed = false;

        if ( $resourceData['locales'] && count( $resourceData['locales'] ) )
        {
            $savedLocale = setlocale( LC_CTYPE, null );
            setlocale( LC_CTYPE, $resourceData['locales'] );
        }

        if ( $resourceData['compiled-template'] )
        {
            if ( $this->executeCompiledTemplate( $resourceData, $textElements, $rootNamespace, $currentNamespace, $extraParameters ) )
                $templateCompilationUsed = true;
        }
        if ( !$templateCompilationUsed )
        {
            $text = null;
            if ( eZTemplate::isDebugEnabled() )
            {
                $fname = $resourceData['template-filename'];
                eZDebug::writeDebug( "START URI: $uri, $fname" );
            }
            $this->process( $resourceData['root-node'], $text, $rootNamespace, $currentNamespace );
            if ( eZTemplate::isDebugEnabled() )
                eZDebug::writeDebug( "END URI: $uri, $fname" );
            $this->setIncludeOutput( $uri, $text );
            $textElements[] = $text;
        }

        if ( $resourceData['locales'] && count( $resourceData['locales'] ) )
        {
            setlocale( LC_CTYPE, $savedLocale );
        }
        $this->Level--;

    }

    function canCompileTemplate( $resourceData, &$extraParameters )
    {
        $resourceObject = $resourceData['handler'];
        if ( !$resourceObject )
            return false;
        $canGenerate = $resourceObject->canCompileTemplate( $this, $resourceData, $extraParameters );
        return $canGenerate;
    }

    /*!
     Validates the template file \a $file and returns \c true if the file has correct syntax.
     \param $returnResourceData If \c true then the returned value will be the resourcedata structure
     \sa compileTemplateFile(), fetch()
    */
    function validateTemplateFile( $file, $returnResourceData = false )
    {
        $this->resetErrorLog();

        if ( !file_exists( $file ) )
            return false;
        $resourceHandler = $this->resourceFor( $file, $resourceName, $templateName );
        if ( !$resourceHandler )
            return false;
        $resourceData = $this->resourceData( $resourceHandler, $file, $resourceName, $templateName );
        $resourceData['key-data'] = "file:" . $file;
        $key = md5( $resourceData['key-data'] );
        $extraParameters = array();

        // Disable caching/compiling while fetchin the resource
        // It will be restored afterwards
        $isCachingAllowed = $this->IsCachingAllowed;
        $this->IsCachingAllowed = false;

        $resourceHandler->handleResource( $this, $resourceData, eZTemplate::RESOURCE_FETCH, $extraParameters );

        // Restore previous caching flag
        $this->IsCachingAllowed = $isCachingAllowed;

        $root =& $resourceData['root-node'];
        $root = array( eZTemplate::NODE_ROOT, false );
        $templateText = $resourceData["text"];
        $rootNamespace = '';
        $this->parse( $templateText, $root, $rootNamespace, $resourceData );
        if ( eZTemplate::isDebugEnabled() )
        {
            $this->appendDebugNodes( $root, $resourceData );
        }

        $result = !$this->hasErrors() and !$this->hasWarnings();
        if ( $returnResourceData )
        {
            $resourceData['result'] = $result;
            return $resourceData;
        }
        return $result;
    }

    /*!
     Compiles the template file \a $file and returns \c true if the compilation was OK.
     \param $returnResourceData If \c true then the returned value will be the resourcedata structure
     \sa validateTemplateFile(), fetch()
    */
    function compileTemplateFile( $file, $returnResourceData = false )
    {
        $this->resetErrorLog();

        if ( !file_exists( $file ) )
            return false;
        $resourceHandler = $this->resourceFor( $file, $resourceName, $templateName );
        if ( !$resourceHandler )
            return false;
        $resourceData = $this->resourceData( $resourceHandler, $file, $resourceName, $templateName );
        $resourceData['key-data'] = "file:" . $file;
        $key = md5( $resourceData['key-data'] );
        $extraParameters = array();
        $resourceHandler->handleResource( $this, $resourceData, eZTemplate::RESOURCE_FETCH, $extraParameters );

        $isCompiled = false;
        if ( isset( $resourceData['compiled-template'] ) )
            $isCompiled = $resourceData['compiled-template'];

        if ( !$isCompiled )
        {
            $root =& $resourceData['root-node'];
            $root = array( eZTemplate::NODE_ROOT, false );
            $templateText = $resourceData["text"];
            $rootNamespace = '';
            $this->parse( $templateText, $root, $rootNamespace, $resourceData );
            if ( eZTemplate::isDebugEnabled() )
            {
                $this->appendDebugNodes( $root, $resourceData );
            }

            $result = eZTemplateCompiler::compileTemplate( $this, $key, $resourceData );
        }
        else
        {
            $result = true;
        }

        if ( $returnResourceData )
        {
            $resourceData['result'] = $result;
            return $resourceData;
        }
        return $result;
    }

    function compileTemplate( &$resourceData, &$extraParameters )
    {
        $resourceObject = $resourceData['handler'];
        if ( !$resourceObject )
            return false;
        $keyData = $resourceData['key-data'];
        $uri = $resourceData['uri'];
        $resourceName = $resourceData['resource'];
        $templatePath = $resourceData['template-name'];
        return $resourceObject->compileTemplate( $this, $keyData, $uri, $resourceName, $templatePath, $extraParameters, $resourceData );
    }

    function executeCompiledTemplate( &$resourceData, &$textElements, $rootNamespace, $currentNamespace, &$extraParameters )
    {
        $resourceObject = $resourceData['handler'];
        if ( !$resourceObject )
            return false;
        $keyData = $resourceData['key-data'];
        $uri = $resourceData['uri'];
        $resourceName = $resourceData['resource'];
        $templatePath = $resourceData['template-name'];
        $timestamp = $resourceData['time-stamp'];
        return $resourceObject->executeCompiledTemplate( $this, $textElements,
                                                         $keyData, $uri, $resourceData, $templatePath,
                                                         $extraParameters, $timestamp,
                                                         $rootNamespace, $currentNamespace );
    }

    /*!
     Returns the resource object for URI $uri. If a resource type is specified
     in the URI it is extracted and set in $res. The template name is set in $template
     without any resource specifier. To specify a resource the name and a ":" is
     prepended to the URI, for instance file:my.tpl.
     If no resource type is found the URI the default resource handler is used.
    */
    function resourceFor( $uri, &$res, &$template )
    {
        $args = explode( ":", $uri );
        if ( count( $args ) > 1 )
        {
            $res = $args[0];
            $template = $args[1];
        }
        else
            $template = $uri;
        if ( eZTemplate::isDebugEnabled() )
        {
            eZDebug::writeNotice( "eZTemplate: Loading template \"$template\" with resource \"$res\"" );
        }
        if ( isset( $this->Resources[$res] ) and is_object( $this->Resources[$res] ) )
        {
            return $this->Resources[$res];
        }
        return $this->DefaultResource;
    }

    /*!
     \return The resource handler object for resource name \a $resourceName.
     \sa resourceFor
    */
    function resourceHandler( $resourceName )
    {
        if ( isset( $this->Resources[$resourceName] ) &&
             is_object( $this->Resources[$resourceName] ) )
        {
            return $this->Resources[$resourceName];
        }
        return $this->DefaultResource;
    }

    function hasChildren( &$function, $functionName )
    {
        $hasChildren = $function->hasChildren();
        if ( is_array( $hasChildren ) )
            return $hasChildren[$functionName];
        else
            return $hasChildren;
     }

    /*!
     Returns the empty variable type.
    */
    function emptyVariable()
    {
        return array( "type" => "null" );
    }

    /*!
     \static
    */
    function mergeNamespace( $rootNamespace, $additionalNamespace )
    {
        $namespace = $rootNamespace;
        if ( $namespace == '' )
            $namespace = $additionalNamespace;
        else if ( $additionalNamespace != '' )
            $namespace = "$namespace:$additionalNamespace";
        return $namespace;
    }

    /*!
     Returns the actual value of a template type or null if an unknown type.
    */
    function elementValue( &$dataElements, $rootNamespace, $currentNamespace, $placement = false,
                           $checkExistance = false, $checkForProxy = false )
    {
        /*
         * We use a small dirty hack in this function...
         * To help the caller to determine if the value was a proxy object,
         * we store boolean true to $dataElements['proxy-object-found'] in this case.
         * (it's up to caller to remove this garbage from $dataElements...)
         * This behaviour is enabled by $checkForProxy parameter.
         */

        $value = null;
        if ( !is_array( $dataElements ) )
        {
            $this->error( "elementValue",
                          "Missing array data structure, got " . gettype( $dataElements ) );
            return null;
        }
        foreach ( $dataElements as $dataElement )
        {
            if ( is_null( $dataElement ) )
            {
                return null;
            }
            $dataType = $dataElement[0];
            switch ( $dataType )
            {
                case eZTemplate::TYPE_VOID:
                {
                    if ( !$checkExistance )
                        $this->warning( 'elementValue',
                                        'Found void datatype, should not be used' );
                    else
                    {
                        return null;
                    }
                } break;
                case eZTemplate::TYPE_STRING:
                case eZTemplate::TYPE_NUMERIC:
                case eZTemplate::TYPE_IDENTIFIER:
                case eZTemplate::TYPE_BOOLEAN:
                case eZTemplate::TYPE_ARRAY:
                {
                    $value = $dataElement[1];
                } break;
                case eZTemplate::TYPE_VARIABLE:
                {
                    $variableData = $dataElement[1];
                    $variableNamespace = $variableData[0];
                    $variableNamespaceScope = $variableData[1];
                    $variableName = $variableData[2];
                    if ( $variableNamespaceScope == eZTemplate::NAMESPACE_SCOPE_GLOBAL )
                        $namespace = $variableNamespace;
                    else if ( $variableNamespaceScope == eZTemplate::NAMESPACE_SCOPE_LOCAL )
                        $namespace = $this->mergeNamespace( $rootNamespace, $variableNamespace );
                    else if ( $variableNamespaceScope == eZTemplate::NAMESPACE_SCOPE_RELATIVE )
                        $namespace = $this->mergeNamespace( $currentNamespace, $variableNamespace );
                    else
                        $namespace = false;
                    if ( $this->hasVariable( $variableName, $namespace ) )
                    {
                        $value = $this->variable( $variableName, $namespace );
                    }
                    else
                    {
                        if ( !$checkExistance )
                            $this->error( '', "Unknown template variable '$variableName' in namespace '$namespace'", $placement );
                        {
                            return null;
                        }
                    }
                } break;
                case eZTemplate::TYPE_ATTRIBUTE:
                {
                    $attributeData = $dataElement[1];
                    $attributeValue = $this->elementValue( $attributeData, $rootNamespace, $currentNamespace, false, $checkExistance );

                    if ( !is_null( $attributeValue ) )
                    {
                        if ( !is_numeric( $attributeValue ) and
                             !is_string( $attributeValue ) and
                             !is_bool( $attributeValue ) )
                        {
                            if ( !$checkExistance )
                                $this->error( "",
                                              "Cannot use type " . gettype( $attributeValue ) . " for attribute lookup", $placement );
                            {
                                return null;
                            }
                        }
                        if ( is_array( $value ) )
                        {
                            if ( array_key_exists( $attributeValue, $value ) )
                            {
                                $value = $value[$attributeValue];
                            }
                            else
                            {
                                if ( !$checkExistance )
                                {
                                    $arrayAttributeList = array_keys( $value );
                                    $arrayCount = count( $arrayAttributeList );
                                    $errorMessage = "No such attribute for array($arrayCount): $attributeValue";
                                    $chooseText = "Choose one of following: ";
                                    $errorMessage .= "\n$chooseText";
                                    $errorMessage .= $this->expandAttributes( $arrayAttributeList, $chooseText, 25 );
                                    $this->error( "",
                                                  $errorMessage, $placement );
                                }
                                return null;
                            }
                        }
                        else if ( is_object( $value ) )
                        {
                            if ( method_exists( $value, "attribute" ) and
                                 method_exists( $value, "hasattribute" ) )
                            {
                                if ( $value->hasAttribute( $attributeValue ) )
                                {
                                    $value = $value->attribute( $attributeValue );
                                }
                                else
                                {
                                    if ( !$checkExistance )
                                    {
                                        $objectAttributeList = array();
                                        if ( method_exists( $value, 'attributes' ) )
                                            $objectAttributeList = $value->attributes();
                                        $objectClass= get_class( $value );
                                        $errorMessage = "No such attribute for object($objectClass): $attributeValue";
                                        $chooseText = "Choose one of following: ";
                                        $errorMessage .= "\n$chooseText";
                                        $errorMessage .= $this->expandAttributes( $objectAttributeList, $chooseText, 25 );
                                        $this->error( "",
                                                      $errorMessage, $placement );
                                    }
                                    return null;
                                }
                            }
                            else
                            {
                                if ( !$checkExistance )
                                    $this->error( "",
                                                  "Cannot retrieve attribute of object(" . get_class( $value ) .
                                                  "), no attribute functions available",
                                                  $placement );
                                return null;
                            }
                        }
                        else
                        {
                            if ( !$checkExistance )
                                $this->error( "",
                                              "Cannot retrieve attribute of a " . gettype( $value ),
                                              $placement );
                            return null;
                        }
                    }
                    else
                    {
                        if ( !$checkExistance )
                            $this->error( '',
                                          'Attribute value was null, cannot get attribute',
                                          $placement );
                        return null;
                    }
                } break;
                case eZTemplate::TYPE_OPERATOR:
                {
                    $operatorParameters = $dataElement[1];
                    $operatorName = $operatorParameters[0];
                    $operatorParameters = array_splice( $operatorParameters, 1 );
                    if ( is_object( $value ) and
                         method_exists( $value, 'templateValue' ) )
                    {
                        if ( $checkForProxy )
                            $dataElements['proxy-object-found'] = true;
                        $value = $value->templateValue();
                    }
                    $valueData = array( 'value' => $value );
                    $this->processOperator( $operatorName, $operatorParameters, $rootNamespace, $currentNamespace,
                                            $valueData, $placement, $checkExistance );
                    $value = $valueData['value'];
                } break;
                default:
                {
                    if ( !$checkExistance )
                        $this->error( "elementValue",
                                      "Unknown data type: '$dataType'" );
                    return null;
                }
            }
        }
        if ( is_object( $value ) and
             method_exists( $value, 'templateValue' ) )
        {
            if ( $checkForProxy )
                $dataElements['proxy-object-found'] = true;
            return $value->templateValue();
        }
        return $value;
    }

    function expandAttributes( $attributeList, $chooseText, $maxThreshold, $minThreshold = 1 )
    {
        $errorMessage = '';
        $attributeCount = count( $attributeList );
        if ( $attributeCount < $minThreshold )
            return $errorMessage;
        if ( $attributeCount < $maxThreshold )
        {
            $chooseLength = strlen( $chooseText );
            $attributeText = '';
            $i = 0;
            foreach ( $attributeList as $attributeName )
            {
                if ( $i > 0 )
                    $attributeText .= ",";
                if ( strlen( $attributeText ) > 40 )
                {
                    $attributeText .= "\n";
                    $errorMessage .= $attributeText;
                    $errorMessage .= str_repeat( ' ', $chooseLength );
                    $attributeText = '';
                }
                else if ( $i > 0 )
                    $attributeText .= " ";
                $attributeText .= $attributeName;
                ++$i;
            }
            $errorMessage .= $attributeText;
        }
        return $errorMessage;
    }

    function processOperator( $operatorName, $operatorParameters, $rootNamespace, $currentNamespace,
                              &$valueData, $placement = false, $checkExistance = false )
    {
        $namedParameters = array();
        $operatorParameterDefinition = $this->operatorParameterList( $operatorName );
        $i = 0;
        foreach ( $operatorParameterDefinition as $parameterName => $parameterType )
        {
            if ( !isset( $operatorParameters[$i] ) or
                 !isset( $operatorParameters[$i][0] ) or
                 $operatorParameters[$i][0] == eZTemplate::TYPE_VOID )
            {
                if ( $parameterType["required"] )
                {
                    if ( !$checkExistance )
                        $this->warning( "eZTemplateOperatorElement", "Parameter '$parameterName' ($i) missing",
                                        $placement );
                    $namedParameters[$parameterName] = $parameterType["default"];
                }
                else
                {
                    $namedParameters[$parameterName] = $parameterType["default"];
                }
            }
            else
            {
                $parameterData = $operatorParameters[$i];
                $namedParameters[$parameterName] = $this->elementValue( $parameterData, $rootNamespace, $currentNamespace, false, $checkExistance );
            }
            ++$i;
        }

        if ( isset( $this->Operators[$operatorName] ) )
        {
            if ( is_array( $this->Operators[$operatorName] ) )
            {
                $this->loadAndRegisterOperators( $this->Operators[$operatorName] );
            }

            $op = $this->Operators[$operatorName];

            if ( is_object( $op ) and method_exists( $op, 'modify' ) )
            {
                $value = $valueData['value'];
                if ( eZTemplate::isMethodDebugEnabled() )
                    eZDebug::writeDebug( "START OPERATOR: $operatorName" );
                $op->modify( $this, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, $value, $namedParameters,
                             $placement );
                if ( eZTemplate::isMethodDebugEnabled() )
                    eZDebug::writeDebug( "END OPERATOR: $operatorName" );
                $valueData['value'] = $value;
            }
            else
                $this->error( '', "Object problem with operator '$operatorName' ",
                              $placement );
        }
        else if ( !$checkExistance )
            $this->warning( "", "Operator '$operatorName' is not registered",
                            $placement );
    }

    /*!
     Return the identifier used for attribute lookup.
    */
    function attributeValue( &$data, $nspace )
    {
        switch ( $data["type"] )
        {
            case "map":
            {
                return $data["content"];
            } break;
            case "index":
            {
                return $data["content"];
            } break;
            case "variable":
            {
                return $this->elementValue( $data["content"], $nspace );
            } break;
            default:
            {
                $this->error( "attributeValue()", "Unknown attribute type: " . $data["type"] );
                return null;
            }
        }
    }

    /*!
     Helper function for creating a displayable text for a variable.
    */
    function variableText( $var, $namespace = "", $attrs = array() )
    {
        $txt = "$";
        if ( $namespace != "" )
            $txt .= "$namespace:";
        $txt .= $var;
        if ( count( $attrs ) > 0 )
            $txt .= "." . implode( ".", $attrs );
        return $txt;
    }

    /*!
     Returns the named parameter list for the operator $name.
    */
    function operatorParameterList( $name )
    {
        $param_list = array();
        if ( !isset( $this->Operators[$name] ) )
        {
            return $param_list;
        }

        if ( is_array( $this->Operators[$name] ) )
        {
            $this->loadAndRegisterOperators( $this->Operators[$name] );
        }

        $op = $this->Operators[$name];
        if ( isset( $op ) and
             method_exists( $op, "namedparameterlist" ) )
        {
            $param_list = $op->namedParameterList();
            if ( method_exists( $op, "namedparameterperoperator" ) and
                 $op->namedParameterPerOperator() )
            {
                if ( !isset( $param_list[$name] ) )
                    return array();
                $param_list = $param_list[$name];
            }
        }
        return $param_list;
    }

    /*!
     Tries to run the operator $operatorName with parameters $operatorParameters
     on the value $value.
    */
    function doOperator( $element, &$namespace, &$current_nspace, &$value, $operatorName, $operatorParameters, &$named_params )
    {
        if ( is_array( $this->Operators[$operatorName] ) )
        {
            $this->loadAndRegisterOperators( $this->Operators[$operatorName] );
        }
        $op = $this->Operators[$operatorName];
        if ( isset( $op ) )
        {
            $op->modify( $element, $this, $operatorName, $operatorParameters, $namespace, $current_nspace, $value, $named_params );
        }
        else
            $this->warning( "", "Operator \"$operatorName\" is not registered" );
    }

    /*!
     Tries to run the function object $func_obj
    */
    function doFunction( $name, $func_obj, $nspace, $current_nspace )
    {
        $func = $this->Functions[$name];
        if ( is_array( $func ) )
        {
            $this->loadAndRegisterFunctions( $this->Functions[$name] );
            $func = $this->Functions[$name];
        }
        if ( isset( $func ) and
             is_object( $func ) )
        {
            return $func->process( $this, $name, $func_obj, $nspace, $current_nspace );
        }
        else
        {
            $this->warning( "", "Function \"$name\" is not registered" );
            return false;
        }
    }

    /*!
     Sets the template variable $var to the value $val.
     \sa setVariableRef
    */
    function setVariable( $var, $val, $namespace = "" )
    {
        if ( array_key_exists( $namespace, $this->Variables ) and
             array_key_exists( $var, $this->Variables[$namespace] ) )
            unset( $this->Variables[$namespace][$var] );
        $this->Variables[$namespace][$var] = $val;
    }

    /*!
     Sets the template variable $var to the value $val.
     \note This sets the variable using reference
     \sa setVariable
    */
    function setVariableRef( $var, $val, $namespace = "" )
    {
        if ( array_key_exists( $namespace, $this->Variables ) and
             array_key_exists( $var, $this->Variables[$namespace] ) )
            unset( $this->Variables[$namespace][$var] );
        $this->Variables[$namespace][$var] = $val;
    }

    /*!
     Removes the template variable $var. If the variable does not exists an error is output.
    */
    function unsetVariable( $var, $namespace = "" )
    {
        if ( array_key_exists( $namespace, $this->Variables ) and
             array_key_exists( $var, $this->Variables[$namespace] ) )
            unset( $this->Variables[$namespace][$var] );
        else
            $this->warning( "unsetVariable()", "Undefined Variable: \$$namespace:$var, cannot unset" );
    }

    /*!
     Returns true if the variable $var is set in namespace $namespace,
     if $attrs is supplied alle attributes must exist for the function to return true.
    */
    function hasVariable( $var, $namespace = "", $attrs = array() )
    {
        $exists = ( array_key_exists( $namespace, $this->Variables ) and
                    array_key_exists( $var, $this->Variables[$namespace] ) );
        if ( $exists and count( $attrs ) > 0 )
        {
            $ptr =& $this->Variables[$namespace][$var];
            foreach( $attrs as $attr )
            {
                unset( $tmp );
                if ( is_object( $ptr ) )
                {
                    if ( $ptr->hasAttribute( $attr ) )
                        $tmp = $ptr->attribute( $attr );
                    else
                        return false;
                }
                else if ( is_array( $ptr ) )
                {
                    if ( array_key_exists( $attr, $ptr ) )
                        $tmp =& $ptr[$attr];
                    else
                        return false;
                }
                else
                {
                    return false;
                }
                unset( $ptr );
                $ptr =& $tmp;
            }
        }
        return $exists;
    }

    /*!
     Returns the content of the variable $var using namespace $namespace,
     if $attrs is supplied the result of the attributes is returned.
    */
    function variable( $var, $namespace = "", $attrs = array() )
    {
        $val = null;
        $exists = ( array_key_exists( $namespace, $this->Variables ) and
                    array_key_exists( $var, $this->Variables[$namespace] ) );
        if ( $exists )
        {
            if ( count( $attrs ) > 0 )
            {
                $element = $this->Variables[$namespace][$var];
                foreach( $attrs as $attr )
                {
                    if ( is_object( $element ) )
                    {
                        if ( $element->hasAttribute( $attr ) )
                        {
                            $element = $element->attribute( $attr );
                        }
                        else
                        {
                            return $val;
                        }
                    }
                    else if ( is_array( $element ) )
                    {
                        if ( array_key_exists( $attr, $element ) )
                        {
                            $val = $element[$attr];
                        }
                        else
                        {
                            return $val;
                        }
                    }
                    else
                    {
                        return $val;
                    }
                    $val = $element;
                }
            }
            else
            {
                $val = $this->Variables[$namespace][$var];
            }
        }
        return $val;
    }

    /*!
     Returns the attribute(s) of the template variable $var,
     $attrs is an array of attribute names to use iteratively for each new variable returned.
    */
    function variableAttribute( $var, $attrs )
    {
        foreach( $attrs as $attr )
        {
            if ( is_object( $var ) )
            {
                if ( $var->hasAttribute( $attr ) )
                {
                    $var = $var->attribute( $attr );
                }
                else
                {
                    return null;
                }
            }
            else if ( is_array( $var ) )
            {
                if ( isset( $var[$attr] ) )
                {
                    $var = $var[$attr];
                }
                else
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
        }
        if ( isset( $var ) )
        {
            return $var;
        }

        return null;
    }

    function appendElement( &$text, $item, $nspace, $name )
    {
        $this->appendElementText( $textElements, $item, $nspace, $name );
        $text .= implode( '', $textElements );
    }

    function appendElementText( &$textElements, $item, $nspace, $name )
    {
        if ( !is_array( $textElements ) )
            $textElements = array();
        if ( is_object( $item ) and
             method_exists( $item, 'templateValue' ) )
        {
            $item = $item->templateValue();
            $textElements[] = "$item";
        }
        else if ( is_object( $item ) )
        {
            $hasTemplateData = false;
            if ( method_exists( $item, 'templateData' ) )
            {
                $templateData = $item->templateData();
                if ( is_array( $templateData ) and
                     isset( $templateData['type'] ) )
                {
                    if ( $templateData['type'] == 'template' and
                         isset( $templateData['uri'] ) and
                         isset( $templateData['template_variable_name'] ) )
                    {
                        $templateURI =& $templateData['uri'];
                        $templateVariableName =& $templateData['template_variable_name'];
                        $templateText = '';
                        $this->setVariableRef( $templateVariableName, $item, $name );
                        eZTemplateIncludeFunction::handleInclude( $textElements, $templateURI, $this, $nspace, $name );
                        $hasTemplateData = true;
                    }
                }
            }
            if ( !$hasTemplateData )
                $textElements[] = method_exists( $item, '__toString' ) ? (string)$item : 'Object(' . get_class( $item ) . ')';
        }
        else
            $textElements[] = "$item";
        return $textElements;
    }

    /*!
     Creates some text nodes before and after the children of \a $root.
     It will extract the current filename and uri and create some XHTML
     comments and inline text.
     \sa isXHTMLCodeIncluded
    */
    function appendDebugNodes( &$root, &$resourceData )
    {
        $path = $resourceData['template-filename'];
        $uri = $resourceData['uri'];
        $preText = "\n<!-- START: including template: $path ($uri) -->\n";
        if ( eZTemplate::isXHTMLCodeIncluded() )
            $preText .= "<p class=\"small\">$path</p><br/>\n";
        $postText = "\n<!-- STOP: including template: $path ($uri) -->\n";
        $root[1] = array_merge( array( eZTemplateNodeTool::createTextNode( $preText ) ), $root[1] );
        $root[1][] = eZTemplateNodeTool::createTextNode( $postText );
    }

    /*!
     Registers the functions supplied by the object $functionObject.
     The object must have a function called functionList()
     which returns an array of functions this object handles.
     If the object has a function called attributeList()
     it is used for registering function attributes.
     The function returns an associative array with each key being
     the name of the function and the value being a boolean.
     If the boolean is true the function will have children.
    */
    function registerFunctions( &$functionObject )
    {
        $this->registerFunctionsInternal( $functionObject );
    }

    function registerAutoloadFunctions( $functionDefinition )
    {
        if ( ( ( isset( $functionDefinition['function'] ) or
                 ( isset( $functionDefinition['script'] ) and
                   isset( $functionDefinition['class'] ) ) ) and
               ( isset( $functionDefinition['function_names_function'] ) or
                 isset( $functionDefinition['function_names'] ) ) ) )
        {
            if ( isset( $functionDefinition['function_names_function'] ) )
            {
                $functionNamesFunction = $functionDefinition['function_names_function'];
                if ( !function_exists( $functionNamesFunction ) )
                {
                    $this->error( 'registerFunctions', "Cannot register function definition, missing function names function '$functionNamesFunction'" );
                    return;
                }
                $functionNames = $functionNamesFunction();
            }
            else
                $functionNames = $functionDefinition['function_names'];
            foreach ( $functionNames as $functionName )
            {
                $this->Functions[$functionName] = $functionDefinition;
            }
            if ( isset( $functionDefinition['function_attributes'] ) )
            {
                foreach ( $functionDefinition['function_attributes'] as $functionAttributeName )
                {
                    $this->FunctionAttributes[$functionAttributeName] = $functionDefinition;
                }
            }
        }
        else
            $this->error( 'registerFunctions', 'Cannot register function definition, missing data' );
    }

    function loadAndRegisterFunctions( $functionDefinition )
    {
        eZDebug::accumulatorStart( 'template_register_function', 'template_total', 'Template load and register function' );
        $functionObject = null;
        if ( isset( $functionDefinition['function'] ) )
        {
            $function = $functionDefinition['function'];
//             print( "loadAndRegisterFunction: $function<br/>" );
            if ( function_exists( $function ) )
                $functionObject = $function();
        }
        else if ( isset( $functionDefinition['script'] ) )
        {
            $script = $functionDefinition['script'];
            $class = $functionDefinition['class'];
//             print( "loadAndRegisterFunction: $script<br/>" );
            include_once( $script );
            if ( class_exists( $class ) )
                $functionObject = new $class();
        }
        eZDebug::accumulatorStop( 'template_register_function' );
        if ( is_object( $functionObject ) )
        {
            $this->registerFunctionsInternal( $functionObject, true );
            return true;
        }
        return false;
    }

    /*!
     \private
    */
    function registerFunctionsInternal( $functionObject, $debug = false )
    {
        if ( !is_object( $functionObject ) or
             !method_exists( $functionObject, 'functionList' ) )
            return false;
        foreach ( $functionObject->functionList() as $functionName )
        {
            $this->Functions[$functionName] = $functionObject;
        }
        if ( method_exists( $functionObject, "attributeList" ) )
        {
            $functionAttributes = $functionObject->attributeList();
            foreach ( $functionAttributes as $attributeName => $hasChildren )
            {
                $this->FunctionAttributes[$attributeName] = $hasChildren;
            }
        }
        return true;
    }

    /*!
     Registers the function $func_name to be bound to object $func_obj.
     If the object has a function called attributeList()
     it is used for registering function attributes.
     The function returns an associative array with each key being
     the name of the function and the value being a boolean.
     If the boolean is true the function will have children.
    */
    function registerFunction( $func_name, $func_obj )
    {
        $this->Functions[$func_name] = $func_obj;
        if ( method_exists( $func_obj, "attributeList" ) )
        {
            $attrs = $func_obj->attributeList();
            while ( list( $attr_name, $has_children ) = each( $attrs ) )
            {
                $this->FunctionAttributes[$attr_name] = $has_children;
            }
        }
    }

    /*!
     Registers a new literal tag in which the tag will be transformed into
     a text element.
    */
    function registerLiteral( $func_name )
    {
        $this->Literals[$func_name] = true;
    }

    /*!
     Removes the literal tag $func_name.
    */
    function unregisterLiteral( $func_name )
    {
        unset( $this->Literals[$func_name] );
    }

    function registerAutoloadOperators( $operatorDefinition )
    {
        if ( ( ( isset( $operatorDefinition['function'] ) or
                 ( isset( $operatorDefinition['script'] ) and
                   isset( $operatorDefinition['class'] ) ) ) and
               ( isset( $operatorDefinition['operator_names_function'] ) or
                 isset( $operatorDefinition['operator_names'] ) ) ) )
        {
            if ( isset( $operatorDefinition['operator_names_function'] ) )
            {
                $operatorNamesFunction = $operatorDefinition['operator_names_function'];
                if ( !function_exists( $operatorNamesFunction ) )
                {
                    $this->error( 'registerOperators', "Cannot register operator definition, missing operator names function '$operatorNamesFunction'" );
                    return;
                }
                $operatorNames = $operatorNamesFunction();
            }
            else
                $operatorNames = $operatorDefinition['operator_names'];
            foreach ( $operatorNames as $operatorName )
            {
                $this->Operators[$operatorName] = $operatorDefinition;
            }
        }
        else
            $this->error( 'registerOperators', 'Cannot register operator definition, missing data' );
    }

    function loadAndRegisterOperators( $operatorDefinition )
    {
        $operatorObject = null;
        if ( isset( $operatorDefinition['function'] ) )
        {
            $function = $operatorDefinition['function'];
//             print( "loadAndRegisterOperator: $function<br/>" );
            if ( function_exists( $function ) )
                $operatorObject = $function();
        }
        else if ( isset( $operatorDefinition['script'] ) )
        {
            $script = $operatorDefinition['script'];
            $class = $operatorDefinition['class'];
//             print( "loadAndRegisterOperator: $script<br/>" );
            include_once( $script );
            if ( class_exists( $class ) )
            {
                if ( isset( $operatorDefinition['class_parameter'] ) )
                    $operatorObject = new $class( $operatorDefinition['class_parameter'] );
                else
                    $operatorObject = new $class();
            }
        }
        if ( is_object( $operatorObject ) )
        {
            $this->registerOperatorsInternal( $operatorObject, true );
            return true;
        }
        return false;
    }

    /*!
     Registers the operators supplied by the object $operatorObject.
     The function operatorList() must return an array of operator names.
    */
    function registerOperators( &$operatorObject )
    {
        $this->registerOperatorsInternal( $operatorObject );
    }

    function registerOperatorsInternal( $operatorObject, $debug = false )
    {
        if ( !is_object( $operatorObject ) or
             !method_exists( $operatorObject, 'operatorList' ) )
            return false;
        foreach( $operatorObject->operatorList() as $operatorName )
        {
            $this->Operators[$operatorName] = $operatorObject;
        }
    }

    /*!
     Registers the operator $op_name to use the object $op_obj.
    */
    function registerOperator( $op_name, $op_obj )
    {
        $this->Operators[$op_name] = $op_obj;
    }

    /*!
     Unregisters the operator $op_name.
    */
    function unregisterOperator( $op_name )
    {
        if ( is_array( $op_name ) )
        {
            foreach ( $op_name as $op )
            {
                $this->unregisterOperator( $op_name );
            }
        }
        else if ( isset( $this->Operators ) )
            unset( $this->Operators[$op_name] );
        else
            $this->warning( "unregisterOpearator()", "Operator $op_name is not registered, cannot unregister" );
    }

    /*!
     Not implemented yet.
    */
    function registerFilter()
    {
    }

    /*!
     Registers a new resource object $res.
     The resource object take care of fetching templates using an URI.
    */
    function registerResource( $res )
    {
        if ( is_object( $res ) )
            $this->Resources[$res->resourceName()] =& $res;
        else
            $this->warning( "registerResource()", "Supplied argument is not a resource object" );
    }

    /*!
     Unregisters the resource $res_name.
    */
    function unregisterResource( $res_name )
    {
        if ( is_array( $res_name ) )
        {
            foreach ( $res_name as $res )
            {
                $this->unregisterResource( $res );
            }
        }
        else if ( isset( $this->Resources[$res_name] ) )
            unset( $this->Resources[$res_name] );
        else
            $this->warning( "unregisterResource()", "Resource $res_name is not registered, cannot unregister" );
    }

    /*!
     Sets whether detail output is used or not.
     Detail output is useful for debug output where you want to examine the template
     and the output text.
    */
    function setShowDetails( $show )
    {
        $this->ShowDetails = $show;
    }

    /*!
     Outputs a warning about the parameter $param missing for function/operator $name.
    */
    function missingParameter( $name, $param )
    {
        $this->warning( $name, "Missing parameter $param" );
    }

    /*!
     Outputs a warning about the parameter count being to high for function/operator $name.
    */
    function extraParameters( $name, $count, $maxCount )
    {
        $this->warning( $name, "Passed $count parameters but correct count is $maxCount" );
    }

    /*!
     Outputs a warning about the variable $var being undefined.
    */
    function undefinedVariable( $name, $var )
    {
        $this->warning( $name, "Undefined variable: $var" );
    }

    /*!
     Outputs an error about the template function $func_name being undefined.
    */
    function undefinedFunction( $func_name )
    {
        $this->error( "", "Undefined function: $func_name" );
    }

    /*!
     Creates a string for the placement information and returns it.
     \note The placement information can either be in indexed or associative
    */
    function placementText( $placement = false )
    {
        $placementText = false;
        if ( $placement !== false )
        {
            if ( isset( $placement['start'] ) and
                 isset( $placement['stop'] ) and
                 isset( $placement['templatefile'] ) )
            {
                $line = $placement['start']['line'];
                $column = $placement['start']['column'];
                $templateFile = $placement['templatefile'];
            }
            else
            {
                $line = $placement[0][0];
                $column = $placement[0][1];
                $templateFile = $placement[2];
            }

            $placementText = " @ $templateFile:$line" . "[$column]";
        }
        return $placementText;
    }

    /*!
     Displays a warning for the function/operator $name and text $txt.
    */
    function warning( $name, $txt, $placement = false )
    {
        $this->WarningLog[] = array( 'name' => $name,
                                     'text' => $txt,
                                     'placement' => $placement );

        if ( !is_string( $placement ) )
            $placementText = $this->placementText( $placement );
        else
            $placementText = $placement;
        $placementText = $this->placementText( $placement );
        if ( $name != "" )
            eZDebug::writeWarning( $txt, "eZTemplate:$name" . $placementText );
        else
            eZDebug::writeWarning( $txt, "eZTemplate" . $placementText );
    }

    /*!
     Displays an error for the function/operator $name and text $txt.
    */
    function error( $name, $txt, $placement = false )
    {
        $this->ErrorLog[] = array( 'name' => $name,
                                   'text' => $txt,
                                   'placement' => $placement );

        if ( !is_string( $placement ) )
            $placementText = $this->placementText( $placement );
        else
            $placementText = $placement;
        if ( $name != "" )
            $nameText = "eZTemplate:$name";
        else
            $nameText = "eZTemplate";
        eZDebug::writeError( $txt, $nameText . $placementText );
        $hasAppendWarning =& $GLOBALS['eZTemplateHasAppendWarning'];
        $ini = $this->ini();
        if ( $ini->variable( 'ControlSettings', 'DisplayWarnings' ) == 'enabled' )
        {
            if ( !isset( $hasAppendWarning ) or
                 !$hasAppendWarning )
            {
                if ( function_exists( 'eZAppendWarningItem' ) )
                {
                    eZAppendWarningItem( array( 'error' => array( 'type' => 'template',
                                                                  'number' => eZTemplate::FILE_ERRORS ),
                                                'text' => ezi18n( 'lib/eztemplate', 'Some template errors occurred, see debug for more information.' ) ) );
                    $hasAppendWarning = true;
                }
            }
        }
    }


    function operatorInputSupported( $operatorName )
    {
    }

    /*!
     Sets the original text for uri $uri to $text.
    */
    function setIncludeText( $uri, $text )
    {
        $this->IncludeText[$uri] = $text;
    }

    /*!
     Sets the output for uri $uri to $output.
    */
    function setIncludeOutput( $uri, $output )
    {
        $this->IncludeOutput[$uri] = $output;
    }

    /*!
     \return the path list which is used for autoloading functions and operators.
    */
    function autoloadPathList()
    {
        return $this->AutoloadPathList;
    }

    /*!
     Sets the path list for autoloading.
    */
    function setAutoloadPathList( $pathList )
    {
        $this->AutoloadPathList = $pathList;
    }

    /*!
     Looks trough the pathes specified in autoloadPathList() and fetches autoload
     definition files used for autoloading functions and operators.
    */
    function autoload()
    {
        $pathList = $this->autoloadPathList();
        foreach ( $pathList as $path )
        {
            $autoloadFile = $path . '/eztemplateautoload.php';
            if ( file_exists( $autoloadFile ) )
            {
                unset( $eZTemplateOperatorArray );
                unset( $eZTemplateFunctionArray );
                include( $autoloadFile );
                if ( isset( $eZTemplateOperatorArray ) and
                     is_array( $eZTemplateOperatorArray ) )
                {
                    foreach ( $eZTemplateOperatorArray as $operatorDefinition )
                    {
                        $this->registerAutoloadOperators( $operatorDefinition );
                    }
                }
                if ( isset( $eZTemplateFunctionArray ) and
                     is_array( $eZTemplateFunctionArray ) )
                {
                    foreach ( $eZTemplateFunctionArray as $functionDefinition )
                    {
                        $this->registerAutoloadFunctions( $functionDefinition );
                    }
                }
            }
        }
    }

    /*!
     Resets all template variables.
    */
    function resetVariables()
    {
        $this->Variables = array();
    }

    /*!
     Resets all template functions and operators by calling the resetFunction and resetOperator
     on all elements that supports it.
    */
    function resetElements()
    {
        foreach ( $this->Functions as $functionName => $functionObject )
        {
            if ( is_object( $functionObject ) and
                 method_exists( $functionObject, 'resetFunction' ) )
            {
                $functionObject->resetFunction( $functionName );
            }
        }

        foreach ( $this->Operators as  $operatorName => $operatorObject )
        {
            if ( is_object( $operatorObject ) and
                 method_exists( $operatorObject, 'resetOperator' ) )
            {
                $operatorObject->resetOperator( $operatorName );
            }
        }
    }

    /*!
     Resets all template variables, functions, operators and error counts.
    */
    function reset()
    {
        $this->resetVariables();
        $this->resetElements();
        $this->IsCachingAllowed = true;

        $this->resetErrorLog();

        $this->TemplatesUsageStatistics = array();
        $this->TemplateFetchList = array();
    }

    /*!
      \return The number of errors that occured with the last fetch
      \sa hasErrors()
    */
    function errorCount()
    {
        return count( $this->ErrorLog );
    }

    /*!
      \return \ true if errors occured with the last fetch.
      \sa errorCount()
    */
    function hasErrors()
    {
        return $this->errorCount() > 0;
    }

    /*!
     \return error log.
     \sa errorCount()
    */
    function errorLog()
    {
        return $this->ErrorLog;
    }

    /*!
      \return The number of warnings that occured with the last fetch
      \sa hasWarnings()
    */
    function warningCount()
    {
        return count( $this->WarningLog );
    }

    /*!
      \return \ true if warnings occured with the last fetch.
      \sa warningCount()
    */
    function hasWarnings()
    {
        return $this->warningCount() > 0;
    }

    /*!
     \return waring log.
     \sa warningCount()
    */
    function warningLog()
    {
        return $this->WarningLog;
    }

    /*!
     Returns the globale template instance, creating it if it does not exist.
    */
    static function instance()
    {
        if ( !isset( $GLOBALS['eZTemplateInstance'] ) )
        {
            $GLOBALS['eZTemplateInstance'] = new eZTemplate();
        }

        return $GLOBALS['eZTemplateInstance'];
    }

    /*!
     Returns the INI object for the template.ini file.
    */
    function ini()
    {
        return eZINI::instance( "template.ini" );
    }

    /*!
     \static
     \return true if special XHTML code should be included before the included template file.
             This code will display the template filename in the browser but will eventually
             break the design.
    */
    static function isXHTMLCodeIncluded()
    {
        if ( !isset( $GLOBALS['eZTemplateDebugXHTMLCodeEnabled'] ) )
        {
            $ini = eZINI::instance();
            $GLOBALS['eZTemplateDebugXHTMLCodeEnabled'] = $ini->variable( 'TemplateSettings', 'ShowXHTMLCode' ) == 'enabled';
        }
        return $GLOBALS['eZTemplateDebugXHTMLCodeEnabled'];
    }

    /*!
     \static
     \return \c true if debug output of template functions and operators should be enabled.
    */
    static function isMethodDebugEnabled()
    {
        if ( !isset( $GLOBALS['eZTemplateDebugMethodEnabled'] ) )
        {
            $ini = eZINI::instance();
            $GLOBALS['eZTemplateDebugMethodEnabled'] = $ini->variable( 'TemplateSettings', 'ShowMethodDebug' ) == 'enabled';
        }
        return $GLOBALS['eZTemplateDebugMethodEnabled'];
    }

    /*!
     \static
     \return true if debugging of internals is enabled, this will display
     which files are loaded and when cache files are created.
      Set the option with setIsDebugEnabled().
    */
    static function isDebugEnabled()
    {
        if ( !isset( $GLOBALS['eZTemplateDebugInternalsEnabled'] ) )
             $GLOBALS['eZTemplateDebugInternalsEnabled'] = eZTemplate::DEBUG_INTERNALS;
        return $GLOBALS['eZTemplateDebugInternalsEnabled'];
    }

    /*!
     \static
     Sets whether internal debugging is enabled or not.
    */
    static function setIsDebugEnabled( $debug )
    {
        $GLOBALS['eZTemplateDebugInternalsEnabled'] = $debug;
    }

    /*!
      \return \c true if caching is allowed (default) or \c false otherwise.
              This also affects template compiling.
      \sa setIsCachingAllowed
    */
    function isCachingAllowed()
    {
        return $this->IsCachingAllowed;
    }

    /*!
      Sets whether caching/compiling is allowed or not. This is useful
      if you need to make sure templates are parsed and processed
      without any caching mechanisms.
      \note The default is to allow caching.
      \sa isCachingAllowed
    */
    function setIsCachingAllowed( $allowed )
    {
        $this->IsCachingAllowed = $allowed;
    }

    /*!
     \static
     \return \c true if templates usage statistics should be enabled.
    */
    static function isTemplatesUsageStatisticsEnabled()
    {
        if ( !isset( $GLOBALS['eZTemplateDebugTemplatesUsageStatisticsEnabled'] ) )
        {
            $ini = eZINI::instance();
            $GLOBALS['eZTemplateDebugTemplatesUsageStatisticsEnabled'] = $ini->variable( 'TemplateSettings', 'ShowUsedTemplates' ) == 'enabled';
        }
        return ( $GLOBALS['eZTemplateDebugTemplatesUsageStatisticsEnabled'] );
    }

    /*!
     \static
     Sets whether templates usage statistics enabled or not.
     \return \c true if templates usage statistics was enabled, otherwise \c false.
    */
    function setIsTemplatesUsageStatisticsEnabled( $enabled )
    {
        $wasEnabled = false;
        if( isset( $GLOBALS['eZTemplateDebugTemplatesUsageStatisticsEnabled'] ) )
            $wasEnabled = $GLOBALS['eZTemplateDebugTemplatesUsageStatisticsEnabled'];

        $GLOBALS['eZTemplateDebugTemplatesUsageStatisticsEnabled'] = $enabled;
        return $wasEnabled;
    }

    /*!
     \static
     Checks settings and if 'ShowUsedTemplates' is enabled appends template info to stats.
    */
    function appendTemplateToStatisticsIfNeeded( &$templateName, &$templateFileName )
    {
        if ( eZTemplate::isTemplatesUsageStatisticsEnabled() )
            eZTemplate::appendTemplateToStatistics( $templateName, $templateFileName );
    }

    /*!
     \static
     Appends template info to stats.
    */
    function appendTemplateToStatistics( $templateName, $templateFileName )
    {
        $actualTemplateName = preg_replace( "#^[\w/]+templates/#", '', $templateFileName );
        $requestedTemplateName = preg_replace( "#^[\w/]+templates/#", '', $templateName );

        $tpl = eZTemplate::instance();
        $needToAppend = true;

        // don't add template info if it is a duplicate of previous.
        $statsSize = count( $tpl->TemplatesUsageStatistics );
        if ( $statsSize > 0 )
        {
            $lastTemplateInfo = $tpl->TemplatesUsageStatistics[$statsSize-1];
            if ( $lastTemplateInfo['actual-template-name'] === $actualTemplateName &&
                 $lastTemplateInfo['requested-template-name'] === $requestedTemplateName &&
                 $lastTemplateInfo['template-filename'] === $templateFileName )
            {
                $needToAppend = false;
            }
        }

        if ( $needToAppend )
        {
            $templateInfo = array( 'actual-template-name' => $actualTemplateName,
                                   'requested-template-name' => $requestedTemplateName,
                                   'template-filename' => $templateFileName );

            $tpl->TemplatesUsageStatistics[] = $templateInfo;
        }
    }

    /*!
     Appends template info for current fetch.
    */
    function appendTemplateFetch( $actualTemplateName )
    {
        $this->TemplateFetchList[] = $actualTemplateName;
        $this->TemplateFetchList = array_unique( $this->TemplateFetchList );
    }

    /*!
     Reset error and warning logs
    */
    function resetErrorLog()
    {
        $this->ErrorLog = array();
        $this->WarningLog = array();
    }

    /*!
     \static
     Returns template usage statistics
    */
    static function templatesUsageStatistics()
    {
        $tpl = eZTemplate::instance();
        return $tpl->TemplatesUsageStatistics;
    }

    /*!
     Returns template list for the last fetch.
    */
    function templateFetchList()
    {
        return $this->TemplateFetchList;
    }

    /*!
     Set template compilation test mode.

     \param true, will set template compilation in test mode ( no disc writes ).
            false, will compile templates to disc
    */
    function setCompileTest( $val )
    {
        $this->TestCompile = $val;
    }

    /*!
     Get if template session is test compile
    */
    function testCompile()
    {
        return $this->TestCompile;
    }

    /// \privatesection
    /// Associative array of resource objects
    public $Resources;
    /// Reference to the default resource object
    public $DefaultResource;
    /// The original template text
    public $Text;
    /// Included texts, usually performed by custom functions
    public $IncludeText;
    /// Included outputs, usually performed by custom functions
    public $IncludeOutput;
    /// The timestamp of the template when it was last modified
    public $TimeStamp;
    /// The left delimiter used for parsing
    public $LDelim;
    /// The right delimiter used for parsing
    public $RDelim;

    /// The resulting object tree of the template
    public $Tree;
    /// An associative array of template variables
    public $Variables;
    /*!
     Last element of this stack contains names of
     all variables created in the innermost template, for them
     to be destroyed after the template execution finishes.
     */
    public $LocalVariablesNamesStack;
    // Reference to the last element of $LocalVariablesNamesStack.
    public $CurrentLocalVariablesNames;
    /// An associative array of operators
    public $Operators;
    /// An associative array of functions
    public $Functions;
    /// An associative array of function attributes
    public $FunctionAttributes;
    /// An associative array of literal tags
    public $Literals;
    /// True if output details is to be shown
    public $ShowDetails = false;
    /// \c true if caching is allowed
    public $IsCachingAllowed;

    /// Array containing all errors occured during a fetch
    public $ErrorLog;
    /// Array containing all warnings occured during a fetch
    public $WarningLog;

    public $AutoloadPathList;
    /// include level
    public $Level = 0;
    public $MaxLevel = 40;

    /// A list of templates used by a rendered page
    public $TemplatesUsageStatistics;

    // counter to make unique names for {foreach} loop variables in com
    public $ForeachCounter;
    public $ForCounter;
    public $WhileCounter;
    public $DoCounter;
    public $ElseifCounter;

    // Flag for setting compilation in test mode
    public $TestCompile;

//     public $CurrentRelatedResource;
//     public $CurrentRelatedTemplateName;
}

?>
