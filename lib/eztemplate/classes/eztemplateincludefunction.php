<?php
//
// Definition of eZTemplateIncludeFunction class
//
// Created on: <05-Mar-2002 13:55:25 amos>
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
  \class eZTemplateIncludeFunction eztemplateincludefunction.php
  \ingroup eZTemplateFunctions
  \brief Includes external template code using function "include"

  Allows the template designer to include another template file
  dynamically. This allows for reuse of commonly used template code.
  The new template file will loaded into the current namespace or a
  namspace specified by the template designer, any extra parameters
  to this function is set as template variables for the template file
  using the newly aquired namespace.

\code
// Example template code
{include uri=file:myfile.tpl}

{include name=new_namespace uri=/etc/test.tpl}

\endcode
*/

class eZTemplateIncludeFunction
{
    /*!
     Initializes the function with the function name $inc_name.
    */
    function eZTemplateIncludeFunction( $inc_name = "include" )
    {
        $this->IncludeName = $inc_name;
    }

    /*!
     Returns an array of the function names, required for eZTemplate::registerFunctions.
    */
    function functionList()
    {
        return array( $this->IncludeName );
    }

    function functionTemplateHints()
    {
        return array( $this->IncludeName => array( 'parameters' => true,
                                                   'static' => false,
                                                   'transform-children' => true,
                                                   'tree-transformation' => true,
                                                   'transform-parameters' => true ) );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         &$tpl, $parameters, $privateData )
    {
        if ( $functionName != $this->IncludeName )
            return false;
        $parameters = eZTemplateNodeTool::extractFunctionNodeParameters( $node );
        if ( !isset( $parameters['uri'] ) )
            return false;

        $uriData = $parameters['uri'];
        if ( !eZTemplateNodeTool::isStaticElement( $uriData ) )
            return false;

        $namespaceValue = false;
        $namespaceName = '$currentNamespace';
        if ( isset( $parameters['name'] ) )
        {
            $nameData = $parameters['name'];
            if ( !eZTemplateNodeTool::isStaticElement( $nameData ) )
                return false;
            $namespaceValue = eZTemplateNodeTool::elementStaticValue( $nameData );
            $namespaceName = '$namespace';
        }

        $uriString = eZTemplateNodeTool::elementStaticValue( $uriData );

        $resourceName = "";
        $templateName = "";
        $resource =& $tpl->resourceFor( $uriString, $resourceName, $templateName );
        $resourceData = $tpl->resourceData( $resource, $uriString, $resourceName, $templateName );
        $resourceData['use-comments'] = eZTemplateCompiler::isCommentsEnabled();

        $includeNodes = $resource->templateNodeTransformation( $functionName, $node, $tpl, $resourceData, $parameters, $namespaceValue );
        if ( $includeNodes === false )
            return false;

        $newNodes = array();

        $variableList = array();
        $uniqID = md5( uniqid('inc') );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$oldRestoreIncludeArray" . "_$uniqID = isset( \$restoreIncludeArray ) ? \$restoreIncludeArray : array();\n".
                                                               "\$restoreIncludeArray = array();\n");
        foreach ( array_keys( $parameters ) as $parameterName )
        {
            if ( $parameterName == 'uri' or
                 $parameterName == 'name' )
                continue;
            $parameterData =& $parameters[$parameterName];
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameterData, false, array(),
                                                                  array( $namespaceValue, EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE, $parameterName ) );
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$restoreIncludeArray[] = array( $namespaceName, '$parameterName', \$vars[$namespaceName]['$parameterName'] );\n" );
            $variableList[] = $parameterName;
        }

        $newNodes = array_merge( $newNodes, $includeNodes );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "foreach ( \$restoreIncludeArray as \$element )\n".
                                                               "{\n".
                                                               "    \$vars[\$element[0]][\$element[1]] = \$element[2];\n".
                                                               "}\n".
                                                               "\$restoreIncludeArray = \$oldRestoreIncludeArray" . "_$uniqID;\n" );

        return $newNodes;
    }

    /*!
     Loads the file specified in the parameter "uri" with namespace "name".
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $params = $functionParameters;
        if ( !isset( $params["uri"] ) )
        {
            $tpl->missingParameter( $this->IncludeName, "uri" );
            return false;
        }
        $uri = $tpl->elementValue( $params["uri"], $rootNamespace, $currentNamespace, $functionPlacement );
        $name = "";
        if ( isset( $params["name"] ) )
            $name = $tpl->elementValue( $params["name"], $rootNamespace, $currentNamespace, $functionPlacement );
        if ( $currentNamespace != "" )
        {
            if ( $name != "" )
                $name = "$currentNamespace:$name";
            else
                $name = $currentNamespace;
        }
        reset( $params );
        while ( ( $key = key( $params ) ) !== null )
        {
            $item =& $params[$key];
            switch ( $key )
            {
                case "name":
                case "uri":
                    break;

                default:
                {
                    $item_value = $tpl->elementValue( $item, $rootNamespace, $currentNamespace, $functionPlacement );
                    $tpl->setVariable( $key, $item_value, $name );
                } break;
            }
            next( $params );
        }
        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, $rootNamespace, $name );
    }

    /*!
     \static
     Takes care of loading the template file and set it in the \a $text parameter.
    */
    function handleInclude( &$textElements, &$uri, &$tpl, $rootNamespace, $name )
    {
        $tpl->processURI( $uri, true, $extraParameters, $textElements, $name, $name );
    }

    /*!
     Returns false, telling the template parser that this is a single tag.
    */
    function hasChildren()
    {
        return false;
    }

    /// \privatesection
    /// The name of the include function
    var $IncludeName;
}

?>
