<?php
//
// Definition of eZTemplateBlockFunction class
//
// Created on: <01-Mar-2002 13:50:33 amos>
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

/*!
  \class eZTemplateBlockFunction eztemplateblockfunction.php
  \ingroup eZTemplateFunctions
  \brief Advanced block handling in templates using function "set-block".

  Renders all it's children as text and sets it as a template variable.
  This is useful for allowing one template to return multiple text portions,
  for instance an email template could set subject as a block and return
  the rest as body.

\code
// Example of template code
{set-block name=Space scope=global variable=text}
{$item} - {$item2}
{/set-block}
\endcode

*/

define( 'EZ_TEMPLATE_BLOCK_SCOPE_RELATIVE', 1 );
define( 'EZ_TEMPLATE_BLOCK_SCOPE_ROOT', 2 );
define( 'EZ_TEMPLATE_BLOCK_SCOPE_GLOBAL', 3 );


class eZTemplateBlockFunction
{
    /*!
     Initializes the object with a name, the name is required for determining
     the name of the -else tag.
    */
    function eZTemplateBlockFunction( $name = "set-block" )
    {
        $this->Name = $name;
    }

    /*!
     Returns an array containing the name of the block function, default is "block".
     The name is specified in the constructor.
    */
    function functionList()
    {
        return array( $this->Name );
    }
    /*!
     Processes the function with all it's children.
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $children = $functionChildren;
        $parameters = $functionParameters;

        $scope = EZ_TEMPLATE_BLOCK_SCOPE_RELATIVE;
        if ( isset( $parameters["scope"] ) )
        {
             $scopeText = $tpl->elementValue( $parameters["scope"], $rootNamespace, $currentNamespace, $functionPlacement );
             if ( $scopeText == 'relative' )
                 $scope = EZ_TEMPLATE_BLOCK_SCOPE_RELATIVE;
             else if ( $scopeText == 'root' )
                 $scope = EZ_TEMPLATE_BLOCK_SCOPE_ROOT;
             else if ( $scopeText == 'global' )
                 $scope = EZ_TEMPLATE_BLOCK_SCOPE_GLOBAL;
             else
                 $tpl->warning( $functionName, "Scope value '$scopeText' is not valid, use either 'relative', 'root' or 'global'" );
        }

        $name = null;
        if ( isset( $parameters["name"] ) )
             $name = $tpl->elementValue( $parameters["name"], $rootNamespace, $currentNamespace, $functionPlacement );
        if ( $name === null )
        {
            if ( $scope == EZ_TEMPLATE_BLOCK_SCOPE_RELATIVE )
                $name = $currentNamespace;
            else if ( $scope == EZ_TEMPLATE_BLOCK_SCOPE_ROOT )
                $name = $rootNamespace;
            else
                $name = '';
        }
        else
        {
            if ( $scope == EZ_TEMPLATE_BLOCK_SCOPE_RELATIVE and
                 $currentNamespace != '' )
                $name = "$currentNamespace:$name";
            else if ( $scope == EZ_TEMPLATE_BLOCK_SCOPE_ROOT and
                      $rootNamespace != '' )
                $name = "$rootNamespace:$name";
        }
        $variableItem = null;
        if ( isset( $parameters["variable"] ) )
        {
            $hasLoopItemParameter = true;
            $variableItem =& $tpl->elementValue( $parameters["variable"], $rootNamespace, $currentNamespace, $functionPlacement );
        }
        else
        {
            $tpl->missingParameter( $functionName, 'variable' );
            return;
        }

        $childTextElements = array();
        foreach ( array_keys( $children ) as $childKey )
        {
            $child =& $children[$childKey];
            $tpl->processNode( $child, $childTextElements, $rootNamespace, $name );
        }
        $text = implode( '', $childTextElements );
        $tpl->setVariable( $variableItem, $text, $name );

    }

    /*!
     Returns true.
    */
    function hasChildren()
    {
        return true;
    }

    /// \privatesection
    /// Name of the function
    var $Name;
}

?>
