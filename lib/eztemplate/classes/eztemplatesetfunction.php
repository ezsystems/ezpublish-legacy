<?php
//
// Definition of eZTemplateSetFunction class
//
// Created on: <05-Mar-2002 13:55:25 amos>
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

/*!
  \class eZTemplateSetFunction eztemplatesetfunction.php
  \ingroup eZTemplateFunctions
  \brief Sets template variables code using function 'set'

  Allows for setting template variables from templates using
  a template function. This is mainly used for optimizations.

  The let function will define new variables and initialize them with
  a value while set only sets values to existing variables.
  The let function is also scoped with children which means that the
  variables are unset when the children are processed.

\code
// Example template code
{let object=$item1 some_text='abc' integer=1}
  {set object=$item2 some_text='def'}

{/let}

{set name=NewNamespace place='/etc/test.tpl'}

\endcode
*/

class eZTemplateSetFunction
{
    /*!
     Initializes the function with the function names $setName and $letName.
    */
    function eZTemplateSetFunction( $setName = 'set', $letName = 'let', $defaultName = 'default' )
    {
        $this->SetName = $setName;
        $this->LetName = $letName;
        $this->DefaultName = $defaultName;
    }

    /*!
     Returns an array of the function names, required for eZTemplate::registerFunctions.
    */
    function &functionList()
    {
        return array( $this->SetName, $this->LetName, $this->DefaultName );
    }

    function functionTemplateHints()
    {
        return array( $this->LetName => array( 'parameters' => true,
                                               'static' => false,
                                               'tree-transformation' => true ),
                      $this->DefaultName => array( 'parameters' => true,
                                                   'static' => false,
                                                   'tree-transformation' => true ) );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         &$tpl, &$resourceData )
    {
        if ( $functionName != $this->LetName and
             $functionName != $this->DefaultName )
            return false;
        $parameterNames = eZTemplateNodeTool::extractFunctionNodeParameterNames( $node );
        if ( $functionName == $this->LetName )
            $functionHookName = 'defineVariables';
        else
            $functionHookName = 'createDefaultVariables';
        $newNodes = eZTemplateNodeTool::deflateFunctionNode( $node,
                                                             array( 'name' => 'pre',
                                                                    'function' => array( 'name' => $functionHookName,
                                                                                         'static' => true,
                                                                                         'class-name' => 'eZTemplateSetFunction',
                                                                                         'php-file' => 'lib/eztemplate/classes/eztemplatesetfunction.php',
                                                                                         'add-function-placement' => true,
                                                                                         'add-calculated-namespace' => true,
                                                                                         'return-value' => true ) ),
                                                             array( 'name' => 'post',
                                                                    'use-parameters' => true,
                                                                    'function' => array( 'name' => 'cleanupVariables',
                                                                                         'static' => true,
                                                                                         'class-name' => 'eZTemplateSetFunction',
                                                                                         'php-file' => 'lib/eztemplate/classes/eztemplatesetfunction.php',
                                                                                         'add-function-parameters' => false,
                                                                                         'add-calculated-namespace' => false,
                                                                                         'add-input' => true ),
                                                                    'parameters' => array( 'names' => $parameterNames ) ) );
        return $newNodes;
    }

    function templateHookProcess( $functionName, $functionHookName, $functionHook,
                                  &$tpl, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
    }

    function defineVariables( &$tpl, $functionParameters, $functionPlacement, $name, $rootNamespace, &$currentNamespace )
    {
        $oldCurrentNamespace = $currentNamespace;
        $definedVariables = array();
        foreach ( array_keys( $functionParameters ) as $key )
        {
            $item =& $functionParameters[$key];
            switch ( $key )
            {
                case 'name':
                    break;

                default:
                {
                    if ( !$tpl->hasVariable( $key, $name ) )
                    {
                        $itemValue =& $tpl->elementValue( $item, $rootNamespace, $currentNamespace, $functionPlacement );
                        $tpl->setVariableRef( $key, $itemValue, $name );
                        $definedVariables[] = $key;
                    }
                    else
                    {
                        $varname = $key;
                        if ( $name != '' )
                            $varname = "$name:$varname";
                        $tpl->warning( $this->SetName, "Variable '$varname' already exists, cannot define" );
                    }
                } break;
            }
        }
        $currentNamespace = $name;
        return array( $definedVariables,
                      $oldCurrentNamespace );
    }

    function createDefaultVariables( &$tpl, $functionParameters, $functionPlacement, $name, $rootNamespace, &$currentNamespace )
    {
        $oldCurrentNamespace = $currentNamespace;
        $definedVariables = array();
        foreach ( array_keys( $functionParameters ) as $key )
        {
            $item =& $functionParameters[$key];
            switch ( $key )
            {
                case 'name':
                    break;

                default:
                {
                    if ( !$tpl->hasVariable( $key, $name ) )
                    {
                        $itemValue =& $tpl->elementValue( $item, $rootNamespace, $currentNamespace, $functionPlacement );
                        $tpl->setVariableRef( $key, $itemValue, $name );
                        $definedVariables[] = $key;
                    }
                } break;
            }
        }
        $currentNamespace = $name;
        return array( $definedVariables,
                      $oldCurrentNamespace );
    }

    function cleanupVariables( &$tpl, $rootNamespace, &$currentNamespace, &$setData )
    {
        $definedVariables =& $setData[0];
        foreach ( $definedVariables as $variable )
        {
            $tpl->unsetVariable( $variable, $currentNamespace );
        }
        $currentNamespace = $setData[1];
    }

    /*!
     Loads the file specified in the parameter 'uri' with namespace 'name'.
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        if ( $functionName != $this->SetName and
             $functionName != $this->LetName and
             $functionName != $this->DefaultName )
            return null;
        $name = '';
        if ( isset( $functionParameters['name'] ) )
            $name = $tpl->elementValue( $functionParameters['name'], $rootNamespace, $currentNamespace, $functionPlacement );
        if ( $currentNamespace != '' )
        {
            if ( $name != '' )
                $name = "$currentNamespace:$name";
            else
                $name = $currentNamespace;
        }
        $definedVariables = array();
        if ( $functionName == $this->SetName )
        {
            foreach ( array_keys( $functionParameters ) as $key )
            {
                $item =& $functionParameters[$key];
                switch ( $key )
                {
                    case 'name':
                        break;

                    default:
                    {
                        if ( $tpl->hasVariable( $key, $name ) )
                        {
                            $itemValue = $tpl->elementValue( $item, $rootNamespace, $currentNamespace, $functionPlacement );
                            $tpl->setVariableRef( $key, $itemValue, $name );
                        }
                        else
                        {
                            $varname = $key;
                            if ( $name != '' )
                                $varname = "$name:$varname";
                            $tpl->warning( $functionName, "Variable '$varname' doesn't exist, cannot set" );
                        }
                    } break;
                }
            }
        }
        else if ( $functionName == $this->DefaultName )
        {
            $definedVariables = eZTemplateSetFunction::createDefaultVariables( $tpl, $functionParameters, $functionPlacement, $name, $rootNamespace, $currentNamespace );
        }
        else
        {
            $definedVariables = eZTemplateSetFunction::defineVariables( $tpl, $functionParameters, $functionPlacement, $name, $rootNamespace, $currentNamespace );
        }
        if ( $functionName == $this->LetName or
             $functionName == $this->DefaultName )
        {
            foreach ( array_keys( $functionChildren ) as $childKey )
            {
                $child =& $functionChildren[$childKey];
                $tpl->processNode( $child, $textElements, $rootNamespace, $name );
            }
            eZTemplateSetFunction::cleanupVariables( $tpl, $rootNamespace, $currentNamespace, $definedVariables );
        }
    }

    /*!
     Returns false, telling the template parser that this is a single tag.
    */
    function hasChildren()
    {
        return array( $this->SetName => false,
                      $this->LetName => true,
                      $this->DefaultName => true );
    }

    function generateTemplateCodeCache( &$php, &$tpl, &$tplProcessCache, &$resourceData, $prefix,
                                        $functionName, $functionChildren, $functionParameters, $functionPlacement )
    {
        if ( $functionName != $this->SetName and
             $functionName != $this->LetName and
             $functionName != $this->DefaultName )
            return null;

        $nameVar = '$' . $prefix . 'Name';
        $placementVar = '$' . $prefix . 'FunctionPlacement';
        $definedVar = '$' . $prefix . 'DefinedVariables';
        $childrenVar = '$' . $prefix . 'FunctionChildren';
        $parameterVar = '$' . $prefix . 'FunctionParameters';
        $php->addVariable( $prefix . 'FunctionPlacement', $functionPlacement );
        $php->addVariable( $prefix . 'FunctionChildren', $functionChildren );
        $php->addVariable( $prefix . 'FunctionParameters', $functionParameters );
        if ( isset( $functionParameters['name'] ) )
        {
//             $code1 .= $nameVar . ' = $this->elementValue( ' . $parameterVar . '[\'name\'], $rootNamespace, $currentNamespace, ' . $placementVar . ' );' . "\n";
            eZTemplateProcessCache::processVariable( $php, $tpl, $resourceData, $functionParameters['name'], $functionPlacement,
                                                     0, $prefix . 'Name', EZ_PHPCREATOR_VARIABLE_ASSIGNMENT );
        }
        else
            $php->addCodePiece( $nameVar . ' = \'\';' . "\n" );
        $code1 = 'if ( $currentNamespace != \'\' )
{
    if ( ' . $nameVar . ' != \'\' )
        ' . $nameVar . ' = "$currentNamespace:' . $nameVar . '";
    else
        ' . $nameVar . ' = $currentNamespace;
}
' . $definedVar . ' = array();
';


        if ( $functionName == $this->SetName )
        {
            $code1 .= 'foreach ( array_keys( ' . $parameterVar . ' ) as $key )
{
    $item =& ' . $parameterVar . '[$key];
    switch ( $key )
    {
        case \'name\':
            break;

        default:
        {
            if ( $this->hasVariable( $key, '. $nameVar . ' ) )
            {
                $itemValue = $this->elementValue( $item, $rootNamespace, $currentNamespace, ' . $placementVar . ' );
                $this->setVariableRef( $key, $itemValue, ' . $nameVar . ' );
            }
            else
            {
                $varname = $key;
                if ( ' . $nameVar . ' != \'\' )
                    $varname = "' . $nameVar . ':$varname";
                $this->warning( "' . $functionName . '", "Variable \'$varname\' doesn\'t exist, cannot set" );
            }
        } break;
    }
}
';
        }
        else if ( $functionName == $this->DefaultName )
        {
            $code1 .= 'foreach ( array_keys( ' . $parameterVar . ' ) as $key )
{
    $item =& ' . $parameterVar . '[$key];
    switch ( $key )
    {
        case \'name\':
            break;

        default:
        {
            if ( !$this->hasVariable( $key, ' . $nameVar . ' ) )
            {
                $itemValue =& $this->elementValue( $item, $rootNamespace, $currentNamespace, ' . $placementVar . ' );
                $this->setVariableRef( $key, $itemValue, ' . $nameVar . ' );
                ' . $definedVar . '[] = $key;
            }
        } break;
    }
}
';
        }
        else
        {
//             foreach ( array_keys( $functionParameters ) as $key )
//             {
//                 $item =& $functionParameters[$key];
//                 switch ( $key )
//                 {
//                     case 'name':
//                         break;

//                     default:
//                     {
//                         if ( !$tpl->hasVariable( $key, $name ) )
//                         {
//                             $itemValue =& $tpl->elementValue( $item, $rootNamespace, $currentNamespace, $functionPlacement );
//                             $tpl->setVariableRef( $key, $itemValue, $name );
//                             $definedVariables[] = $key;
//                         }
//                         else
//                         {
//                             $varname = $key;
//                             if ( $name != '' )
//                                 $varname = "$name:$varname";
//                             $tpl->warning( $functionName, "Variable '$varname' already exists, cannot define" );
//                         }
//                     } break;
//                 }
//             }
        }

        if ( $functionName == $this->LetName or
             $functionName == $this->DefaultName )
        {
            $code1 .= 'foreach ( array_keys( ' . $childrenVar . ' ) as $childKey )
{
    $child =& ' . $childrenVar . '[$childKey];
    $this->processNode( $child, $textElements, $rootNamespace, ' . $nameVar . ' );
}
foreach ( ' . $definedVar . ' as $variable )
{
    $this->unsetVariable( $variable, $name );
}
';
        }

        $php->addCodePiece( $code1 );
    }

    /// The name of the set function
    var $SetName;
    var $LetName;
    var $DefaultName;
}

?>
