<?php
//
// Definition of eZObjectforwarder class
//
// Created on: <14-Sep-2002 15:38:26 amos>
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

/*! \file ezobjectforwarder.php
*/

/*!
  \class eZObjectforwarder ezobjectforwarder.php
  \brief The class eZObjectforwarder does

*/

class eZObjectForwarder
{
    function eZObjectForwarder( $rules )
    {
        $this->Rules =& $rules;
    }

    function functionList()
    {
        return array_keys( $this->Rules );
    }

    function functionTemplateHints()
    {
        $hints = array();
        foreach ( $this->Rules as $name => $data )
        {
            $hints[$name] = array( 'parameters' => true,
                                   'static' => false,
                                   'transform-children' => true,
                                   'tree-transformation' => true,
                                   'transform-parameters' => true );
        }
        return $hints;
    }

    function templateNodeTransformation( $functionName, &$node,
                                         &$tpl, $parameters, $privateData )
    {
        if ( !isset( $this->Rules[$functionName] ) )
            return false;
        $rule =& $this->Rules[$functionName];

        $parameters = eZTemplateNodeTool::extractFunctionNodeParameters( $node );
        $inputName = $rule['input_name'];
        if ( !isset( $parameters[$inputName] ) )
            return false;
        $inputData = $parameters[$inputName];
        $outputName = $rule['output_name'];

        $newNodes = array();

        $viewDir = '';
        $renderMode = false;
        if ( isset( $rule["render_mode"] ) )
        {
            $renderMode = $rule["render_mode"];
        }
        if ( isset( $parameters['render-mode'] ) )
        {
            $renderData = $parameters['render-mode'];
            if ( !eZTemplateNodeTool::isStaticElement( $renderData ) )
                return false;
            $renderMode = eZTemplateNodeTool::elementStaticValue( $renderData );
        }
        if ( $renderMode )
            $view_dir .= "/render-$renderMode";

        $viewValue = false;
        $viewName = false;
        if ( $rule['use_views'] )
        {
            $viewName = $rule['use_views'];
            if ( !isset( $parameters[$viewName] ) )
                return false;

            $viewData = $parameters[$viewName];
            if ( !eZTemplateNodeTool::isStaticElement( $viewData ) )
                return false;
            $viewValue = eZTemplateNodeTool::elementStaticValue( $viewData );
            $viewDir .= '/' . $viewValue;
        }

        $namespaceValue = false;
        if ( isset( $rule['namespace'] ) )
        {
            $namespaceValue = $rule['namespace'];
        }

        $variableList = array();

        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $inputData, false, array(),
                                                              array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $outputName ) );
        $variableList[] = $outputName;

        foreach ( array_keys( $parameters ) as $parameterName )
        {
            if ( $parameterName == $inputName or
                 $parameterName == $outputName or
                 $parameterName == $viewName )
                continue;
            $parameterData =& $parameters[$parameterName];
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameterData, false, array(),
                                                                  array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $parameterName ) );
            $variableList[] = $parameterName;
        }

        $templateRoot = $rule["template_root"];
        $matchFileArray =& eZTemplateDesignResource::overrideArray();

        if ( is_string( $templateRoot ) )
        {
            $resourceNodes = $this->resourceAcquisitionTransformation( $functionName, $node, $rule, $inputData,
                                                                       $templateRoot, $viewDir, $viewValue,
                                                                       $matchFileArray, 0 );
            $newNodes = array_merge( $newNodes, $resourceNodes );
        }
        else
        {
            if ( isset( $templateRoot['type'] ) and
                 $templateRoot['type'] == 'multi_match' and
                 isset( $templateRoot['attributes'] ) and
                 isset( $templateRoot['matches'] ) )
            {
                $rootAttributes = $templateRoot['attributes'];
                $attributeAccessData = array();
                foreach ( $rootAttributes as $rootAttributeName )
                {
                    $attributeAccessData[] = eZTemplateNodeTool::createAttributeLookupElement( $rootAttributeName );
                }
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $attributeAccessData, false,
                                                                      array( 'spacing' => 0 ), 'templateRootMatch' );
                $rootMatches = $templateRoot['matches'];
                $rootMatchCounter = 0;
                foreach ( $rootMatches as $rootMatch )
                {
                    $rootMatchValue = $rootMatch[0];
                    $templateRoot = $rootMatch[1];
                    $rootMatchValueText = eZPHPCreator::variableText( $rootMatchValue, 0, 0, false );
                    $code = '';
                    if ( $rootMatchCounter > 0 )
                        $code .= "else ";
                    $code .= "if ( \$templateRootMatch == $rootMatchValueText )\n{";
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );

                    $resourceNodes = $this->resourceAcquisitionTransformation( $functionName, $node, $rule, $inputData,
                                                                               $templateRoot, $viewDir, $viewValue,
                                                                               $matchFileArray, 4 );
                    $newNodes = array_merge( $newNodes, $resourceNodes );
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}" );
                    ++$rootMatchCounter;
                }
                $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( 'templateRootMatch' );
            }
        }

        foreach ( $variableList as $variableName )
        {
            $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $variableName ) );
        }

        return $newNodes;
    }

    function resourceAcquisitionTransformation( $functionName, &$node, $rule, $inputData,
                                                $templateRoot, $viewDir, $viewValue,
                                                $matchFileArray, $acquisitionSpacing )
    {
        $startRoot = '/' . $templateRoot . $viewDir;
        $viewFileMatchName = '/' . $templateRoot . '/' . $viewValue . '.tpl';
        $startRootLength = strlen( $startRoot );
        $matchList = array();
        $viewFileMatch = null;
        foreach ( $matchFileArray as $matchFile )
        {
            $path = $matchFile['template'];
            $subPath = substr( $path, 0, $startRootLength );
            if ( $subPath == $startRoot and
                 $path[$startRootLength] == '/' )
            {
                $matchFile['match_part'] = substr( $path, $startRootLength + 1 );
                $matchList[] = $matchFile;
            }
            if ( $path == $viewFileMatchName )
                $viewFileMatch = $matchFile;
        }
        $designKeysName = 'dKeys';
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( !isset( \$$designKeysName ) )\n{\n    \$resH =& \$tpl->resourceHandler( 'design' );\n    \$$designKeysName =& \$resH->Keys;\n}", array( 'spacing' => $acquisitionSpacing ) );

        $attributeAccess =& $rule["attribute_access"];

        $hasAttributeAccess = false;
        if ( is_array( $attributeAccess ) )
        {
            $hasAttributeAccess = count( $attributeAccess ) > 0;
            $attributeAccessCount = 0;
            foreach ( $attributeAccess as $attributeAccessEntries )
            {
                $attributeAccessData = $inputData;
                $spacing = $acquisitionSpacing;
                if ( $attributeAccessCount > 1 )
                {
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "else if ( !$resourceFound )\n{\n", array( 'spacing' => $acquisitionSpacing ) );
                    $spacing += 4;
                }
                else if ( $attributeAccessCount > 0 )
                {
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( !\$resourceFound )\n{\n", array( 'spacing' => $acquisitionSpacing ) );
                    $spacing += 4;
                }
                foreach ( $attributeAccessEntries as $attributeAccessName )
                {
                    $attributeAccessData[] = eZTemplateNodeTool::createAttributeLookupElement( $attributeAccessName );
                }
                $accessNodes = array();
                $accessNodes[] = eZTemplateNodeTool::createVariableNode( false, $attributeAccessData, false,
                                                                         array( 'spacing' => $spacing ), 'attributeAccess' );

                $acquisitionNodes = array();
                $templateCounter = 0;
                $hasAcquisitionNodes = false;
                foreach ( $matchList as $matchItem )
                {
                    $matchPart = $matchItem['match_part'];
                    if ( preg_match( "/^(.+)\.tpl$/", $matchPart, $matches ) )
                        $matchPart = $matches[1];
                    $code = "if ( \$attributeAccess == '$matchPart' )\n{\n";
                    if ( $templateCounter > 0 )
                        $code = "else " . $code;
                    $acquisitionNodes[] = eZTemplateNodeTool::createCodePieceNode( $code, array( 'spacing' => $spacing ) );

                    $defaultMatchSpacing = $spacing;
                    if ( isset( $matchItem['custom_match'] ) )
                    {
                        $customSpacing = $spacing + 4;
                        $defaultMatchSpacing = $spacing + 4;
                        $customMatchList = $matchItem['custom_match'];
                        $matchCount = 0;
                        foreach ( $customMatchList as $customMatch )
                        {
                            if ( $matchCount > 0 )
                            {
                                $code = "else if ( ";
                            }
                            else
                            {
                                $code = "if ( ";
                            }
                            $ifLength = strlen( $code );
                            $conditionCount = 0;
                            foreach ( $customMatch['conditions'] as $conditionName => $conditionValue )
                            {
                                if ( $conditionCount > 0 )
                                    $code .= " and\n" . str_repeat( ' ', $ifLength );
                                $conditionNameText = eZPHPCreator::variableText( $conditionName, 0 );
                                $conditionValueText = eZPHPCreator::variableText( $conditionValue, 0 );
                                $code .= "isset( \$" . $designKeysName . "[$conditionNameText] ) and \$" . $designKeysName . "[$conditionNameText] == $conditionValueText";
                                ++$conditionCount;
                            }
                            $code .= " )\n{";
                            $matchFile = $customMatch['match_file'];
                            $acquisitionNodes[] = eZTemplateNodeTool::createCodePieceNode( $code, array( 'spacing' => $customSpacing ) );
                            $hasAcquisitionNodes = true;
                            $acquisitionNodes[] = eZTemplateNodeTool::createResourceAcquisitionNode( '',
                                                                                                     $matchFile, $matchFile,
                                                                                                     EZ_RESOURCE_FETCH, false,
                                                                                                     $node[4], array( 'spacing' => $customSpacing + 4 ),
                                                                                                     $rule['namespace'] );
                            $acquisitionNodes[] = eZTemplateNodeTool::createCodePieceNode( "}", array( 'spacing' => $customSpacing ) );
                            ++$matchCount;
                        }
                        $acquisitionNodes[] = eZTemplateNodeTool::createCodePieceNode( "else\n{", array( 'spacing' => $customSpacing ) );
                    }

                    $matchFile = $matchItem['base_dir'] . $matchItem['template'];
                    $acquisitionNodes[] = eZTemplateNodeTool::createResourceAcquisitionNode( '',
                                                                                             $matchFile, $matchFile,
                                                                                             EZ_RESOURCE_FETCH, false,
                                                                                             $node[4], array( 'spacing' => $defaultMatchSpacing + 4 ),
                                                                                             $rule['namespace'] );
                    $hasAcquisitionNodes = true;
                    if ( isset( $matchItem['custom_match'] ) )
                        $acquisitionNodes[] = eZTemplateNodeTool::createCodePieceNode( "}", array( 'spacing' => $customSpacing ) );

                    $acquisitionNodes[] = eZTemplateNodeTool::createCodePieceNode( "}", array( 'spacing' => $spacing ) );
                    ++$templateCounter;
                }

                if ( $hasAcquisitionNodes )
                {
                    $newNodes = array_merge( $newNodes, $accessNodes, $acquisitionNodes );

                    if ( $attributeAccessCount > 0 )
                    {
                        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}", array( 'spacing' => $acquisitionSpacing ) );
                    }
                    ++$attributeAccessCount;
                }
                else
                {
                    $newNodes[] = eZTemplateNodeTool::createErrorNode( "Failed to load template",
                                                                       $functionName,
                                                                       eZTemplateNodeTool::extractFunctionNodePlacement( $node ),
                                                                       array( 'spacing' => $acquisitionSpacing ) );
                }
            }
        }
        if ( $viewFileMatch !== null )
        {
            $mainSpacing = 0;
            if ( $hasAttributeAccess )
            {
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "else\n{\n", array( 'spacing' => $acquisitionSpacing ) );
                $mainSpacing = 4;
            }
            $templateCounter = 0;

            $basedir = $viewFileMatch['base_dir'];
            $template = $viewFileMatch['template'];
            $file = $basedir . $template;

            if ( isset( $viewFileMatch['custom_match'] ) )
            {
                $spacing = $mainSpacing + 4;
                $customMatchList = $viewFileMatch['custom_match'];
                $matchCount = 0;
                foreach ( $customMatchList as $customMatch )
                {
                    if ( $matchCount > 0 )
                    {
                        $code = "else if ( ";
                    }
                    else
                    {
                        $code = "if ( ";
                    }
                    $ifLength = strlen( $code );
                    $conditionCount = 0;
                    foreach ( $customMatch['conditions'] as $conditionName => $conditionValue )
                    {
                        if ( $conditionCount > 0 )
                            $code .= " and\n" . str_repeat( ' ', $ifLength );
                        $conditionNameText = eZPHPCreator::variableText( $conditionName, 0 );
                        $conditionValueText = eZPHPCreator::variableText( $conditionValue, 0 );
                        $code .= "isset( \$" . $designKeysName . "[$conditionNameText] ) and \$" . $designKeysName . "[$conditionNameText] == $conditionValueText";
                        ++$conditionCount;
                    }
                    $code .= " )\n{";
                    $matchFile = $customMatch['match_file'];
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code, array( 'spacing' => $acquisitionSpacing ) );
                    $newNodes[] = eZTemplateNodeTool::createResourceAcquisitionNode( '',
                                                                                     $matchFile, $matchFile,
                                                                                     EZ_RESOURCE_FETCH, false,
                                                                                     $node[4], array( 'spacing' => $spacing ),
                                                                                     $rule['namespace'] );
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}", array( 'spacing' => $acquisitionSpacing ) );
                    ++$matchCount;
                }
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "else\n{", array( 'spacing' => $acquisitionSpacing ) );
            }
            $newNodes[] = eZTemplateNodeTool::createResourceAcquisitionNode( '',
                                                                             $file, $file,
                                                                             EZ_RESOURCE_FETCH, false,
                                                                             $node[4], array( 'spacing' => $mainSpacing ),
                                                                             $rule['namespace'] );
            if ( isset( $viewFileMatch['custom_match'] ) )
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}", array( 'spacing' => $acquisitionSpacing ) );

            if ( $hasAttributeAccess )
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}\n", array( 'spacing' => $acquisitionSpacing ) );
        }
        return $newNodes;
    }

    function &process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        if ( !isset( $this->Rules[$functionName] ) )
        {
            $tpl->undefinedFunction( $functionName );
            return;
        }
        $rule =& $this->Rules[$functionName];
        $template_dir = $rule["template_root"];
        $input_name =& $rule["input_name"];
        $outCurrentNamespace = $currentNamespace;
        if ( isset( $rule['namespace'] ) )
        {
            $ruleNamespace = $rule['namespace'];
            if ( $ruleNamespace != '' )
            {
                if ( $outCurrentNamespace != '' )
                    $outCurrentNamespace .= ':' . $ruleNamespace;
                else
                    $outCurrentNamespace = $ruleNamespace;
            }
        }

        $params = $functionParameters;
        if ( !isset( $params[$input_name] ) )
        {
            $tpl->missingParameter( $functionName, $input_name );
            return;
        }

        $old_nspace = $rootNamespace;

        $input_var =& $tpl->elementValue( $params[$input_name], $rootNamespace, $currentNamespace, $functionPlacement );
        if ( !is_object( $input_var ) )
        {
            $tpl->warning( $functionName, "Parameter $input_name is not an object" );
            return;
        }

        $txt = "";
        $attribute_access =& $rule["attribute_access"];
        $view_mode = "";
        $view_dir = "";
        $view_var = null;
        $renderMode = false;
        if ( isset( $rule["render_mode"] ) )
        {
            $renderMode = $rule["render_mode"];
        }
        if ( isset( $params['render-mode'] ) )
        {
            $renderMode =& $tpl->elementValue( $params['render-mode'], $rootNamespace, $currentNamespace, $functionPlacement );
        }
        if ( $renderMode )
            $view_dir .= "/render-$renderMode";
        if ( $rule["use_views"] )
        {
            $view_var =& $rule["use_views"];
            if ( !isset( $params[$view_var] ) )
            {
                if ( !isset( $rule['optional_views'] ) or
                     !$rule['optional_views'] )
                    $tpl->warning( $functionName, "No view specified, skipping views" );
            }
            else
            {
                $view_mode =& $tpl->elementValue( $params[$view_var], $rootNamespace, $currentNamespace, $functionPlacement );
                $view_dir .= "/" . $view_mode;
            }
        }

        $resourceKeys = false;
        if ( isset( $rule['attribute_keys'] ) )
        {
            $resourceKeys = array();
            $attributeKeys =& $rule['attribute_keys'];
            foreach( $attributeKeys as $attributeKey => $attributeSelection )
            {
                $keyValue =& $tpl->variableAttribute( $input_var, $attributeSelection );
                $resourceKeys[] = array( $attributeKey, $keyValue );
            }
        }

        $output_var =& $input_var;
        $res = null;
        $tried_files = array();
        $extraParameters = array();
        if ( $resourceKeys !== false )
            $extraParameters['ezdesign:keys'] = $resourceKeys;
        if ( is_array( $template_dir ) )
        {
            $templateRoot = $template_dir;
            $template_dir = '';
            if ( !isset( $templateRoot['type'] ) )
                $tpl->error( $functionName,
                             'No template root type defined' );
            else if ( $templateRoot['type'] == 'multi_match' )
            {
                if ( !isset( $templateRoot['attributes'] ) )
                    $tpl->error( $functionName,
                                 'No template root attributes defined' );
                else if ( !isset( $templateRoot['attributes'] ) )
                    $tpl->error( $functionName,
                                 'No template root matches defined' );
                else
                {
                    $templateRootValue =& $tpl->variableAttribute( $input_var, $templateRoot['attributes'] );
                    $templateRootMatches =& $templateRoot['matches'];
                    foreach ( $templateRootMatches as $templateRootMatch )
                    {
                        if ( $templateRootMatch[0] == $templateRootValue )
                        {
                            $template_dir = $templateRootMatch[1];
                            if ( is_array( $template_dir ) )
                            {
                                $templateDirAttributesList = $template_dir[1];
                                $template_dir = $template_dir[0];
                                $attributeValues = array();
                                foreach ( $templateDirAttributesList as $templateDirAttributes )
                                {
                                    $attributeValues[] =& $tpl->variableAttribute( $input_var, $templateDirAttributes );
                                }
                                $template_dir .= implode( '/', $attributeValues );
                            }
                            break;
                        }
                    }
                }
            }
            else
                $tpl->error( $functionName,
                             'Unknown template root type: ' . $templateRoot['type'] );
        }

        $resourceData = null;
        $root = null;
        $canCache = false;
        if ( is_array( $attribute_access ) )
        {
            for ( $i = 0; $i < count( $attribute_access ) && !$res; ++$i )
            {
                $attribute_access_array =& $attribute_access[$i];
                $output_var =& $tpl->variableAttribute( $input_var, $attribute_access_array );
                $incfile =& $output_var;
                $uri = "design:$template_dir$view_dir/$incfile.tpl";
                $resourceData =& $tpl->loadURIRoot( $uri, false, $extraParameters );
                if ( $resourceData === null )
                    $tried_files[] = $uri;
                else
                    break;
            }
            if ( $resourceData === null )
            {
                $uri = "design:$template_dir/$view_mode.tpl";
                $resourceData =& $tpl->loadURIRoot( $uri, false, $extraParameters );
                if ( $resourceData === null )
                    $tried_files[] = $uri;
            }
        }

        if ( $resourceData !== null )
        {
            $designUsedKeys = array();
            $designMatchedKeys = array();
            if ( isset( $extraParameters['ezdesign:used_keys'] ) )
                $designUsedKeys = $extraParameters['ezdesign:used_keys'];
            if ( isset( $extraParameters['ezdesign:matched_keys'] ) )
                $designMatchedKeys = $extraParameters['ezdesign:matched_keys'];
            if ( $outCurrentNamespace != '' )
                $designKeyNamespace = $outCurrentNamespace . ':DesignKeys';
            else
                $designKeyNamespace = 'DesignKeys';

            $sub_text = "";
            $output_name =& $rule["output_name"];
            $setVariableArray = array();
            $tpl->setVariableRef( $output_name, $input_var, $outCurrentNamespace );
            $setVariableArray[] = $output_name;
            // Set design keys
            $tpl->setVariable( 'used', $designUsedKeys, $designKeyNamespace );
            $tpl->setVariable( 'matched', $designMatchedKeys, $designKeyNamespace );
            // Set function parameters
            foreach ( array_keys( $params ) as $paramName )
            {
                if ( $paramName == $input_name or
                     $paramName == $view_var )
                    continue;
                $paramValue =& $tpl->elementValue( $params[$paramName], $old_nspace, $currentNamespace, $functionPlacement );
                $tpl->setVariableRef( $paramName, $paramValue, $outCurrentNamespace );
                $setVariableArray[] = $paramName;
            }
            // Set constant variables
            if ( isset( $rule['constant_template_variables'] ) )
            {
                foreach ( $rule['constant_template_variables'] as $constantTemplateVariableKey => $constantTemplateVariableValue )
                {
                    if ( $constantTemplateVariableKey == $input_name or
                         $constantTemplateVariableKey == $view_var or
                         $tpl->hasVariable( $constantTemplateVariableKey, $currentNamespace ) )
                        continue;
                    $tpl->setVariableRef( $constantTemplateVariableKey, $constantTemplateVariableValue, $outCurrentNamespace );
                    $setVariableArray[] = $constantTemplateVariableKey;
                }
            }

            $templateCompilationUsed = false;
            if ( $resourceData['compiled-template'] )
            {
                if ( $tpl->executeCompiledTemplate( $resourceData, $textElements, $outCurrentNamespace, $outCurrentNamespace, $extraParameters ) )
                    $templateCompilationUsed = true;
            }
            if ( !$templateCompilationUsed and
                 $resourceData['root-node'] )
            {
                $root =& $resourceData['root-node'];
                $tpl->process( $root, $sub_text, $outCurrentNamespace, $outCurrentNamespace );
                $tpl->setIncludeOutput( $uri, $sub_text );

                $textElements[] = $sub_text;
            }
            foreach ( $setVariableArray as $setVariableName )
            {
                $tpl->unsetVariable( $setVariableName, $outCurrentNamespace );
            }
        }
        else
        {
            $tpl->warning( $functionName,
                           "None of the templates " . implode( ", ", $tried_files ) .
                           " could be found" );
        }
    }

    function hasChildren()
    {
        return false;
    }

    var $Rules;
};

?>
