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
// http://ez.no/home/licences/professional/. For pricing of this licence
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

        $internalCharset = eZTextCodec::internalCharset();
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTemplateProcessCache::cacheDirectory(), $cacheFileName );
        return $php->canRestore( $timestamp );
    }

    /*!
     \static
     Generates the cache will be used for handling optimized processinging using the key \a $key.
     \return false if the cache does not exist.
    */
    function generateCache( $key, &$resourceData )
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
        $php->addVariable( 'eZTemplateProcessCacheCodeDate', EZ_TEMPLATE_PROCESS_CACHE_CODE_DATE );
        $php->addSpace();

        eZTemplateProcessCache::processNode( $php, $rootNode, $resourceData );

        $php->store();

        return true;
    }

    function processNode( &$php, &$node, &$resourceData )
    {
        $nodeType = $node[0];
        if ( $nodeType == EZ_TEMPLATE_NODE_ROOT )
        {
            $children = $node[1];
            if ( $children )
            {
                foreach ( $children as $child )
                {
                    eZTemplateProcessCache::processNode( $php, $child, $resourceData );
                }
            }
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_TEXT )
        {
            $text = $node[2];
            $php->addVariable( 'textElements', $text, EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT );
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_VARIABLE )
        {
            $variableData = $node[2];
            $variablePlacement = $node[3];

            eZTemplateProcessCache::processVariable( $php, $resourceData, $variableData, $variablePlacement );

//         $value = $this->elementValue( $variableData, $rootNamespace, $currentNamespace, $variablePlacement );
//         $this->appendElementText( $textElements, $value, $rootNamespace, $currentNamespace );
//             $this->processVariable( $textElements, $variableData, $variablePlacement, $rootNamespace, $currentNamespace );
//             if ( !is_array( $textElements ) )
//                 eZDebug::writeError( "Textelements is no longer array: '$textElements'",
//                                      'eztemplate::processNode::variable' );
        }
        else if ( $nodeType == EZ_TEMPLATE_NODE_FUNCTION )
        {
            $functionChildren = $node[1];
            $functionName = $node[2];
            $functionParameters = $node[3];
            $functionPlacement = $node[4];
            $php->addSpace();
            $php->addMethodCall( 'this', 'processFunction',
                                 array( array( $functionName ),
                                        array( 'textElements',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ),
                                        array( $functionChildren ),
                                        array( $functionParameters ),
                                        array( $functionPlacement ),
                                        array( 'rootNamespace',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ),
                                        array( 'currentNamespace',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ) ) );
            $php->addSpace();

//             $php->addCodePiece( '$func =& $this->Functions[$functionName];
// if ( is_array( $func ) )
// {
//     $this->loadAndRegisterFunctions( $this->Functions["' . $functionName . '"] );
//     $func =& $this->Functions["' . $functionName . '"];
// }
// if ( isset( $func ) and
//      is_object( $func ) )
// {
//     return $func->process( $this, &$textElements, "' . $functionName . '", ' . $php->variableText( $functionChildren, 0 ) . ', ' . $functionParameters . ', ' . $functionPlacement . ', $rootNamespace, $currentNamespace );
// }
// else
// {
//     $this->warning( "", "Function ' . $functionName . ' is not registered" );
// }
// ' . 'unset( $func );
// ' );

//             $this->processFunction( $functionName, $textElements, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace );
//             if ( !is_array( $textElements ) )
//                 eZDebug::writeError( "Textelements is no longer array: '$textElements'",
//                                      "eztemplate::processNode::function( '$functionName' )" );
        }
    }

    function processVariable( &$php, &$resourceData, $variableData, $variablePlacement )
    {
        $simpleTypes = array( EZ_TEMPLATE_TYPE_VOID, EZ_TEMPLATE_TYPE_STRING, EZ_TEMPLATE_TYPE_NUMERIC, EZ_TEMPLATE_TYPE_IDENTIFIER );
        $attributeTypes = array( EZ_TEMPLATE_TYPE_ATTRIBUTE, EZ_TEMPLATE_TYPE_OPERATOR );
        $isSimpleType = true;
        $startsWithSimpleType = false;
        $hasAttributes = false;
        $hasOperators = false;

        if ( count( $variableData ) > 0 and
             in_array( $variableData[0][0], $simpleTypes ) )
            $startsWithSimpleType = true;
        foreach ( $variableData as $variableDataElement )
        {
            $elementType = $variableDataElement[0];
            if ( !in_array( $elementType, $simpleTypes ) )
                $isSimpleType = false;
            if ( in_array( $elementType, $attributeTypes ) )
                $hasAttributes = true;
            if ( $elementType == EZ_TEMPLATE_TYPE_OPERATOR )
                $hasOperators = true;
        }
//         eZDebug::writeDebug( $isSimpleType, 'isSimpleType' );
//         eZDebug::writeDebug( $startsWithSimpleType, 'startsWithSimpleType' );
//         eZDebug::writeDebug( $hasAttributes, 'hasAttributes' );
        if ( $isSimpleType )
        {
            if ( count( $variableData ) > 0 and
                 $variableData[0][0] == EZ_TEMPLATE_TYPE_VOID )
            {
                $php->addMethodCall( 'this', 'warning',
                                     array( array( 'elementValue' ),
                                            array( 'Found void datatype, should not be used' ) ) );
            }
            else
            {
                $php->addVariable( 'textElements', $variableData[0][1],
                                   EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT );
            }
        }
        else if ( !$hasAttributes and
                  count( $variableData ) > 0 and
                  $variableData[0][0] == EZ_TEMPLATE_TYPE_VARIABLE )
        {
            $variableInfo = $variableData[0][1];
            $variableType = $variableData[0][0];
            $assignmentName = 'textElements';
            $assignmentType = EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT;
            eZTemplateProcessCache::processSingleElement( $php, $resourceData, $variableType, $variableInfo, $variablePlacement,
                                                          $assignmentName, $assignmentType );
        }
        else if ( $hasAttributes or
                  $hasOperators )
        {
            $variableInfo = $variableData[0][1];
            $variableType = $variableData[0][0];
            $assignmentName = 'value';
            $assignmentType = EZ_PHPCREATOR_VARIABLE_ASSIGNMENT;
            $php->addVariable( $assignmentName, null,
                               EZ_PHPCREATOR_VARIABLE_ASSIGNMENT );
            eZTemplateProcessCache::processSingleElement( $php, $resourceData, $variableType, $variableInfo, $variablePlacement,
                                                          $assignmentName, $assignmentType );
            for ( $i = 1; $i < count( $variableData ); ++$i )
            {
                $variableElement = $variableData[$i];
            }
        }
        else
        {
            $php->addSpace();
            $php->addMethodCall( 'this', 'elementValue',
                                 array( array( $variableData ),
                                        array( 'rootNamespace',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ),
                                        array( 'currentNamespace',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ),
                                        array( $variablePlacement ) ),
                                 array( 'value' ) );
            $php->addMethodCall( 'this', 'appendElementText',
                                 array( array( 'textElements',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ),
                                        array( 'value',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ),
                                        array( 'rootNamespace',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ),
                                        array( 'currentNamespace',
                                               EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE ) ) );
            $php->addCodePiece( 'unset( $value );' . "\n" );
            $php->addSpace();
        }
    }

    function processSingleElement( &$php, &$resourceData, $variableType, $variableInfo, $variablePlacement,
                                   $assignmentName, $assignmentType )
    {
        if ( $variableType == EZ_TEMPLATE_TYPE_VARIABLE )
        {
            $variableNamespace = $variableInfo[0];
            $variableNamespaceScope = $variableInfo[1];
            $variableName = $variableInfo[2];
            $namespaceText = '$namespace = ';
            $namespaceNameText = '$namespace';
            if ( $variableNamespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_GLOBAL )
            {
                if ( $variableNamespace != "" )
                    $namespaceText .= "'$variableNamespace'";
                else
                {
                    $namespaceNameText = 'false';
                    $namespaceText = false;
                }
            }
            else if ( $variableNamespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL )
            {
                if ( $variableNamespace != "" )
                    $namespaceText .= 'eZTemplate::mergeNamespace( $rootNamespace, "' . $variableNamespace . '" )';
                else
                {
                    $namespaceNameText = '$rootNamespace';
                    $namespaceText = false;
                }
            }
            else if ( $variableNamespaceScope == EZ_TEMPLATE_NAMESPACE_SCOPE_RELATIVE )
            {
                if ( $variableNamespace != "" )
                    $namespaceText .= 'eZTemplate::mergeNamespace( $currentNamespace, "' . $variableNamespace . '" )';
                else
                {
                    $namespaceNameText = '$currentNamespace';
                    $namespaceText = false;
                }
            }
            else
                $namespaceText .= '"false"';
            if ( $namespaceText )
                $namespaceText .= ";\n";
            $variableNameText = "'$variableName'";
            $placementText = eZTemplate::placementText( $variablePlacement );
            $assignmentText = '$' . $assignmentName;
            if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_ASSIGNMENT )
                $assignmentText .= " = ";
            else if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_APPEND_TEXT )
                $assignmentText .= " .= ";
            if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT )
                $assignmentText .= "[] = ";

            $php->addSpace();
            $php->addCodePiece(
                $namespaceText .
                'if ( $this->hasVariable( ' . $variableNameText . ', ' . $namespaceNameText . ' ) )
    ' . $assignmentText . '$this->variable( ' . $variableNameText . ', ' . $namespaceNameText . ' );
else
    $this->error( ' . "''" . ', "Unknown template variable ' . "'$variableName'" . ' in namespace ' . $namespaceNameText . '", "' . $placementText . '" );
' );
            $php->addSpace();
        }
        else
        {
            $php->addVariable( $assignmentName, $variableData[0][1],
                               $assignmentType );
        }
    }
}

?>
