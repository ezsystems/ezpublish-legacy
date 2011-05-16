<?php
/**
 * File containing the eZTemplateIncludeFunction class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

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
                                         $tpl, $parameters, $privateData )
    {
        if ( $functionName != $this->IncludeName )
            return false;
        $parameters = eZTemplateNodeTool::extractFunctionNodeParameters( $node );
        if ( !isset( $parameters['uri'] ) )
            return false;

        $uriData = $parameters['uri'];
        if ( !eZTemplateNodeTool::isConstantElement( $uriData ) )
            return false;

        $namespaceValue = false;
        $namespaceName = '$currentNamespace';
        if ( isset( $parameters['name'] ) )
        {
            $nameData = $parameters['name'];
            if ( !eZTemplateNodeTool::isConstantElement( $nameData ) )
                return false;
            $namespaceValue = eZTemplateNodeTool::elementConstantValue( $nameData );
            $namespaceName = '$namespace';
        }

        $uriString = eZTemplateNodeTool::elementConstantValue( $uriData );

        $resourceName = "";
        $templateName = "";
        $resource = $tpl->resourceFor( $uriString, $resourceName, $templateName );
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
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( isset( $namespaceName ) and isset( \$vars[$namespaceName]['$parameterName'] ) )\n".
                                                                   "    \$restoreIncludeArray[] = array( $namespaceName, '$parameterName', \$vars[$namespaceName]['$parameterName'] );\n".
                                                                   "elseif ( !isset( \$vars[( isset( $namespaceName ) ? $namespaceName : '' )]['$parameterName'] ) ) \n".
                                                                   "    \$restoreIncludeArray[] = array( ( isset( $namespaceName ) ? $namespaceName : '' ), '$parameterName', 'unset' );\n" );

            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameterData, false, array(),
                                                                  array( $namespaceValue, eZTemplate::NAMESPACE_SCOPE_RELATIVE, $parameterName ) );
            $variableList[] = $parameterName;
        }

        $newNodes = array_merge( $newNodes, $includeNodes );
        // Restore previous variables, before including
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "foreach ( \$restoreIncludeArray as \$element )\n".
                                                               "{\n".
                                                               "    if ( \$element[2] === 'unset' )\n".
                                                               "    {\n".
                                                               "        unset( \$vars[\$element[0]][\$element[1]] );\n".
                                                               "        continue;\n".
                                                               "    }\n".
                                                               "    \$vars[\$element[0]][\$element[1]] = \$element[2];\n".
                                                               "}\n".
                                                               "\$restoreIncludeArray = \$oldRestoreIncludeArray" . "_$uniqID;\n" );

        return $newNodes;
    }

    /*!
     Loads the file specified in the parameter "uri" with namespace "name".
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
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
        $whatParamsShouldBeUnset = array();
        $whatParamsShouldBeReplaced = array();
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
                    if ( !$tpl->hasVariable( $key, $name ) )
                    {
                        $whatParamsShouldBeUnset[] = $key; // Tpl vars should be removed after including
                    }
                    else
                    {
                        $whatParamsShouldBeReplaced[$key] = $tpl->variable( $key, $name ); // Tpl vars should be replaced after including
                    }

                    $item_value = $tpl->elementValue( $item, $rootNamespace, $currentNamespace, $functionPlacement );
                    $tpl->setVariable( $key, $item_value, $name );
                } break;
            }
            next( $params );
        }
        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, $rootNamespace, $name );
        // unset var
        foreach ( $whatParamsShouldBeUnset as $key )
        {
            $tpl->unsetVariable( $key, $name );
        }
        // replace var
        foreach ( $whatParamsShouldBeReplaced as $key => $item_value )
        {
            $tpl->setVariable( $key, $item_value, $name );
        }
    }

    /*!
     \static
     Takes care of loading the template file and set it in the \a $text parameter.
    */
    static function handleInclude( &$textElements, &$uri, $tpl, $rootNamespace, $name )
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
    public $IncludeName;
}

?>
