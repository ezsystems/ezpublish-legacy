<?php
//
// Definition of eZTemplateIncludeFunction class
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
    function &functionList()
    {
        return array( $this->IncludeName );
    }

    /*!
     Loads the file specified in the parameter "uri" with namespace "name".
    */
    function &process( &$tpl, &$func_name, &$func_obj, $nspace, $current_nspace )
    {
        $text = "";
        $params =& $func_obj->parameters();
        if ( !isset( $params["uri"] ) )
        {
            $tpl->missingParameter( $this->IncludeName, "uri" );
            return false;
        }
        $uri = $tpl->elementValue( $params["uri"], $nspace );
        $name = "";
        if ( isset( $params["name"] ) )
            $name = $tpl->elementValue( $params["name"], $nspace );
        if ( $current_nspace != "" )
        {
            if ( $name != "" )
                $name = "$current_nspace:$name";
            else
                $name = $current_nspace;
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
                    $item_value = $tpl->elementValue( $item, $nspace );
                    $tpl->setVariable( $key, $item_value, $name );
                } break;
            }
            next( $params );
        }
        eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, $nspace, $name );
        return $text;
    }

    /*!
     \static
     Takes care of loading the template file and set it in the \a $text parameter.
    */
    function handleInclude( &$text, &$uri, &$tpl, $nspace, $name )
    {
        $canCache = true;
        $resource =& $tpl->resourceFor( $uri, $resourceName, $templateName );
        if ( !$resource->servesStaticData() )
            $canCache = false;
        $root = null;
        if ( $canCache )
            $root = $tpl->cachedTemplateTree( $uri );
//         $root = null;
        if ( $root === null )
        {
            $extraParameters = false;
            $res =& $tpl->loadURI( $uri, true, $extraParameters );
            if ( $res )
            {
                $root = new eZTemplateRoot();
                $tpl_text =& $res["text"];
                $tpl->setIncludeText( $uri, $tpl_text );
                $tpl->parse( $tpl_text, $root, "", $res["resource"], $res["template_name"] );
                if ( $canCache )
                    $tpl->setCachedTemplateTree( $uri, $root );
            }
        }
        if ( $root )
        {
            $sub_text = "";
            $root->process( $tpl, $sub_text, $name, $name );
            $tpl->setIncludeOutput( $uri, $sub_text );
            $text = $sub_text;
        }
    }

    /*!
     Returns false, telling the template parser that this is a single tag.
    */
    function hasChildren()
    {
        return false;
    }

    /// The name of the include function
    var $IncludeName;
}

?>
