<?php
//
// Definition of eZObjectforwarder class
//
// Created on: <14-Sep-2002 15:38:26 amos>
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

    function &process( &$tpl, &$func_name, &$func_obj, $nspace, $current_nspace )
    {
        if ( !isset( $this->Rules[$func_name] ) )
        {
            $tpl->undefinedFunction( $func_name );
            return null;
        }
        $rule =& $this->Rules[$func_name];
        $template_dir =& $rule["template_root"];
        $input_name =& $rule["input_name"];

        $params =& $func_obj->parameters();
        if ( !isset( $params[$input_name] ) )
        {
            $tpl->missingParameter( $func_name, $input_name );
            return null;
        }

        $old_nspace = $nspace;

        $input_var =& $tpl->elementValue( $params[$input_name], $nspace );
        if ( !is_object( $input_var ) )
        {
            $tpl->warning( $func_name, "Parameter $input_name is not an object" );
            return null;
        }

        $txt = "";
        $attribute_access =& $rule["attribute_access"];
        $view_mode = "";
        $view_dir = "";
        if ( $rule["use_views"] )
        {
            $view_var =& $rule["use_views"];
            if ( !isset( $params[$view_var] ) )
            {
                $tpl->warning( $func_name, "No view specified, skipping views" );
            }
            else
            {
                $view_mode =& $tpl->elementValue( $params[$view_var], $nspace );
                $view_dir = "/" . $view_mode;
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

//         ( array( array( "object", $object->attribute( "id" ) ), // Object ID
//                  array( "class", $class->attribute( "id" ) ), // Class ID
//                  array( "section", 0 ) ) ); // Section ID, 0 so far

        $output_var =& $input_var;
        $res = null;
        $tried_files = array();
        $extraParameters = array();
        if ( $resourceKeys !== false )
            $extraParameters['ezdesign:keys'] = $resourceKeys;
        if ( is_array( $attribute_access ) )
        {
            for ( $i = 0; $i < count( $attribute_access ) && !$res; ++$i )
            {
                $attribute_access_array =& $attribute_access[$i];
                $output_var =& $tpl->variableAttribute( $input_var, $attribute_access_array );
                $incfile =& $output_var;
                $uri = "design:$template_dir$view_dir/$incfile.tpl";
                $res =& $tpl->loadURI( $uri, false, $extraParameters );
                $tried_files[] = $uri;
            }
            if ( !is_array( $res ) and $rule["use_views"] )
            {
                $uri = "design:$template_dir/$view_mode.tpl";
                $res =& $tpl->loadURI( $uri, false, $extraParameters );
                $tried_files[] = $uri;
            }
        }

        if ( is_array( $res ) )
        {
            $root = new eZTemplateRoot();
            $tpl_text =& $res["text"];
            $nspace = $rule["namespace"];
            $tpl->setIncludeText( $uri, $tpl_text );
            $tpl->parse( $tpl_text, $root, "", $res["resource"], $res["template_name"] );

            $sub_text = "";
            $output_name =& $rule["output_name"];
            $setVariableArray = array();
            $tpl->setVariableRef( $output_name, $input_var, $current_nspace );
            $setVariableArray[] = $output_name;
            foreach ( array_keys( $params ) as $paramName )
            {
                if ( $paramName == $input_name or
                     $paramName == $view_var )
                    continue;
                $paramValue =& $tpl->elementValue( $params[$paramName], $old_nspace );
                $tpl->setVariableRef( $paramName, $paramValue, $current_nspace );
                $setVariableArray[] = $paramName;
            }

            $root->process( $tpl, $sub_text, $current_nspace, $current_nspace );
            $tpl->setIncludeOutput( $uri, $sub_text );

            $txt =& $sub_text;
            foreach ( $setVariableArray as $setVariableName )
            {
                $tpl->unsetVariable( $setVariableName, $current_nspace );
            }
        }
        else
        {
            $tpl->warning( $func_name,
                           "None of the templates " . implode( ", ", $tried_files ) .
                           " could be found" );
        }
        return $txt;
    }

    function hasChildren()
    {
        return false;
    }

    var $Rules;
};

?>
