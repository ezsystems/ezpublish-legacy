<?php
//
// Definition of eZTemplateSetFunction class
//
// Created on: <05-Mar-2002 13:55:25 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

    /*!
     Loads the file specified in the parameter 'uri' with namespace 'name'.
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        if ( $functionName != $this->SetName and
             $functionName != $this->LetName and
             $functionName != $this->DefaultName )
            return null;
        $parameters = $functionParameters;
        $name = '';
        if ( isset( $parameters['name'] ) )
            $name = $tpl->elementValue( $parameters['name'], $rootNamespace, $currentNamespace, $functionPlacement );
        if ( $currentNamespace != '' )
        {
            if ( $name != '' )
                $name = "$currentNamespace:$name";
            else
                $name = $currentNamespace;
        }
        $definedVariables = array();
        foreach ( array_keys( $parameters ) as $key )
        {
            $item =& $parameters[$key];
            switch ( $key )
            {
                case 'name':
                    break;

                default:
                {
//                         eZDebug::writeError( "setting key '$key' to '$itemValue' in namespace '$name'" );
                    if ( $functionName == $this->SetName )
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
                    }
                    else if ( $functionName == $this->DefaultName )
                    {
                        if ( !$tpl->hasVariable( $key, $name ) )
                        {
                            $itemValue =& $tpl->elementValue( $item, $rootNamespace, $currentNamespace, $functionPlacement );
//                             eZDebug::writeError( "setting key '$key' to '$itemValue' in namespace '$name'" );
                            $tpl->setVariableRef( $key, $itemValue, $name );
                            $definedVariables[] = $key;
                        }
                    }
                    else
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
                            $tpl->warning( $functionName, "Variable '$varname' already exists, cannot define" );
                        }
                    }
                } break;
            }
        }
        if ( $functionName == $this->LetName or
             $functionName == $this->DefaultName )
        {
            $children = $functionChildren;
            foreach ( array_keys( $children ) as $childKey )
            {
                $child =& $children[$childKey];
                $tpl->processNode( $child, $textElements, $rootNamespace, $name );
            }
            foreach ( $definedVariables as $variable )
            {
                $tpl->unsetVariable( $variable, $name );
            }
        }
        return;
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

    /// The name of the set function
    var $SetName;
    var $LetName;
    var $DefaultName;
}

?>
