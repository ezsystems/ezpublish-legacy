<?php
//
// Created on: <16-Apr-2002 12:37:51 amos>
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

include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/eztemplate/classes/eztemplatefileresource.php" );

class eZTemplateDesignResource extends eZTemplateFileResource
{
    /*!
     Initializes with a default resource name "design".
    */
    function eZTemplateDesignResource( $name = "design" )
    {
        $this->eZTemplateFileResource( $name );
        $this->Keys = array();
    }

    /*!
     Loads the template file if it exists, also sets the modification timestamp.
     Returns true if the file exists.
    */
    function handleResource( &$tpl, &$text, &$tstamp, &$path, $method, &$extraParameters )
    {
        $ini =& eZINI::instance();
        $std_base = $ini->variable( "DesignSettings", "StandardDesign" );
        $site_base = $ini->variable( "DesignSettings", "SiteDesign" );

        $matches = array();
        $matches[] = array( "file" => "design/$site_base/override/templates/$path",
                            "type" => "override" );
        $matches[] = array( "file" => "design/$std_base/override/templates/$path",
                            "type" => "override" );
        $matches[] = array( "file" => "design/$site_base/templates/$path",
                            "type" => "normal" );
        $matches[] = array( "file" => "design/$std_base/templates/$path",
                            "type" => "normal" );

        $match_keys = $this->Keys;

        if ( is_array( $extraParameters ) and
             isset( $extraParameters['ezdesign:keys'] ) )
        {
            $match_keys = $extraParameters['ezdesign:keys'];
        }

        $match = null;
        foreach ( $matches as $tpl_match )
        {
            $tpl_path = $tpl_match["file"];
            $tpl_type = $tpl_match["type"];
            if ( $tpl_type == "normal" )
            {
                if ( file_exists( $tpl_path ) )
                {
                    $match = $tpl_match;
                    break;
                }
            }
            else if ( $tpl_type == "override" )
            {
                if ( count( $match_keys ) == 0 )
                    continue;
                if ( file_exists( $tpl_path ) and
                     is_dir( $tpl_path ) ) // Do advanced match with multiple keys
                {
                    $hd = opendir( $tpl_path );
                    $key_regex = "([0-9]*)";
                    if ( count( $match_keys ) > 1 )
                        $key_regex .= str_repeat( ",([0-9]*)", count( $match_keys ) - 1 );
                    while( ( $file = readdir( $hd ) ) !== false )
                    {
                        if ( $file == "." or
                             $file == ".." )
                            continue;
                        if ( !preg_match( "#^$key_regex\.tpl$#", $file, $regs ) )
                            continue;
//                     eZDebug::writeNotice( "Matching file $file"  );
                        $key_index = 0;
                        $found = true;
                        for ( $i = 1; $i < count( $regs ) and $key_index < count( $match_keys ); ++$i )
                        {
                            $key = $match_keys[$key_index];
                            $key_val = $key[1];
//                         eZDebug::writeNotice( "Matching key $key_val" . '=' . $regs[$i] );
                            if ( is_numeric( $key_val )and
                                 is_numeric( $regs[$i] ) )
                            {
                                if ( $regs[$i] != $key_val )
                                {
                                    $found = false;
                                    break;
                                }
                            }
                            else if ( $regs[$i] != "" )
                            {
                                $found = false;
                                break;
                            }
                            ++$key_index;
                        }
                        if ( !$found )
                            continue;
                        $match = $tpl_match;
                        $match["file"] = "$tpl_path/$file";
//                         eZDebug::writeNotice( "Multi match found, using override " . $match["file"]  );
                        break;
                    }
                    closedir( $hd );
                }
                else // Check for dir/filebase_keyname_keyid.tpl, eg. content/view_section_1.tpl
                {
                    preg_match( "#^(.+)/(.+)(\.tpl)$#", $tpl_path, $regs );
                    foreach ( $match_keys as $match_key )
                    {
                        $match_key_name = $match_key[0];
                        $match_key_val = $match_key[1];
                        $file = $regs[1] . "/" . $regs[2] . "_$match_key_name" . "_$match_key_val" . $regs[3];
                        if ( file_exists( $file ) )
                        {
                            $match = $tpl_match;
                            $match["file"] = $file;
//                             eZDebug::writeNotice( "Match found, using override " . $match["file"]  );
                            break;
                        }
                    }
                }
                if ( $match !== null )
                    break;
            }
        }
        if ( $match === null )
            return false;

        $file = $match["file"];

        return eZTemplateFileResource::handleResource( $tpl, $text, $tstamp, $file, $method, $extraParameters );
    }

    function setKeys( $keys )
    {
        $this->Keys = $keys;
    }

    function &instance()
    {
        $instance =& $GLOBALS["eZTemplateDesignResourceInstance"];
        if ( get_class( $instance ) != "eztemplatedesignresource" )
        {
            $instance = new eZTemplateDesignResource();
        }
        return $instance;
    }

    var $Keys;
}

//         "attribute_edit_gui" => array( "template_root" => "content/datatype/edit",
//                                        "input_name" => "attribute",
//                                        "output_name" => "attribute",
//                                        "namespace" => "",
//                                        "attribute_access" => array( "contentclass_attribute", "data_type" ),
//                                        "use_views" => true ),
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
            $tpl->setVariableRef( $output_name, $input_var, $current_nspace );
            $root->process( $tpl, $sub_text, $current_nspace, $current_nspace );
            $tpl->setIncludeOutput( $uri, $sub_text );

            $txt =& $sub_text;
            $tpl->unsetVariable( $output_name, $current_nspace );
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

function &templateInit()
{
    $tpl =& $GLOBALS["eZPublishTemplate"];
    if ( get_class( $tpl ) == "eztemplate" )
        return $tpl;
    include_once( "lib/eztemplate/classes/eztemplate.php" );
    include_once( "lib/eztemplate/classes/eztemplatephpoperator.php" );
    include_once( "lib/eztemplate/classes/eztemplateattributeoperator.php" );
    include_once( "lib/eztemplate/classes/eztemplatelocaleoperator.php" );
    include_once( "lib/eztemplate/classes/eztemplateimageoperator.php" );
    include_once( "lib/eztemplate/classes/eztemplateunitoperator.php" );
    include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
    include_once( "lib/eztemplate/classes/eztemplatetextoperator.php" );
    include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
    include_once( "lib/eztemplate/classes/eztemplatedelimitfunction.php" );
    include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );
    include_once( "lib/eztemplate/classes/eztemplateswitchfunction.php" );
    include_once( "lib/eztemplate/classes/eztemplatesequencefunction.php" );
    include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
    include_once( "kernel/classes/ezurloperator.php" );
    include_once( "kernel/classes/ezi18noperator.php" );
    include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

    include_once( "lib/ezlocale/classes/ezlocale.php" );

//     $lang =& eZLanguage::instance();
    $locale =& eZLocale::instance();
//     $locale->setLanguage( $lang );

// Init template
    $tpl = eZTemplate::instance();

    $tpl->registerResource( eZTemplateDesignResource::instance() );

    $forward_rules = array(
        "attribute_edit_gui" => array( "template_root" => "content/datatype/edit", //"class/datatype",
                                       "input_name" => "attribute",
                                       "output_name" => "attribute",
                                       "namespace" => "",
                                       "attribute_access" => array( array( "contentclass_attribute",
                                                                           "data_type",
                                                                           "information",
                                                                           "string" ) ),
                                       "use_views" => false ),
        "attribute_view_gui" => array( "template_root" => "content/datatype/view", //"content/datatype",
                                       "input_name" => "attribute",
                                       "output_name" => "attribute",
                                       "namespace" => "",
                                       "attribute_access" => array( array( "contentclass_attribute",
                                                                           "data_type",
                                                                           "information",
                                                                           "string" ) ),
                                       "use_views" => false ),
        "content_view_gui" => array( "template_root" => "content/view",
                                     "input_name" => "content_object",
                                     "output_name" => "object",
                                     "namespace" => "ContentView",
                                     "attribute_keys" => array( "object" => array( "id" ),
                                                                "class" => array( "contentclass_id" ) ),
                                     "attribute_access" => array( array( "contentclass_id" ),
                                                                  array( "class_name" ) ),
                                     "use_views" => "view" ),
        "event_edit_gui" => array( "template_root" => "workflow/eventtype/edit",
                                   "input_name" => "event",
                                   "output_name" => "event",
                                   "namespace" => "WorkflowEvent",
                                   "attribute_access" => array( array( "workflow_type_string" ) ),
                                   "use_views" => false ),
        "class_attribute_edit_gui" => array( "template_root" => "class/datatype/edit",
                                             "input_name" => "class_attribute",
                                             "output_name" => "class_attribute",
                                             "namespace" => "ClassAttribute",
                                             "attribute_access" => array( array( "data_type",
                                                                                 "information",
                                                                                 "string" ) ),
                                             "use_views" => false ) );
    $tpl->registerFunctions( new eZObjectForwarder( $forward_rules ) );

    $tpl->registerOperators( new eZTemplatePHPOperator( array( "upcase" => "strtoupper",
                                                               "reverse" => "strrev",
                                                               "nl2br" => "nl2br" ) ) );
    $tpl->registerOperators( new eZTemplateAttributeOperator() );
    $tpl->registerOperators( new eZTemplateLocaleOperator() );
    $tpl->registerOperators( new eZTemplateArrayOperator() );
    $tpl->registerOperators( new eZTemplateTextOperator() );
    $tpl->registerFunctions( new eZTemplateSectionFunction() );
    $tpl->registerFunctions( new eZTemplateDelimitFunction() );
    $tpl->registerFunctions( new eZTemplateIncludeFunction() );
    $tpl->registerFunctions( new eZTemplateSwitchFunction() );
    $tpl->registerFunctions( new eZTemplateSequenceFunction() );
    $tpl->registerFunctions( new eZTemplateSetFunction() );

    $tpl_ini =& $tpl->ini();

    include_once( "lib/ezutils/classes/ezsys.php" );

    $imgop = new eZTemplateImageOperator();
    $imgop->setFontDir( realpath( "." ) . "/" . $tpl_ini->variable( "TextToImageSettings", "FontDir" ) );
    $imgop->setCacheDir( realpath( "." ) . "/" . $tpl_ini->variable( "TextToImageSettings", "CacheDir" ) );
    $imgop->setHtmlDir( eZSys::wwwDir() . $tpl_ini->variable( "TextToImageSettings", "HtmlDir" ) );
    $imgop->setFamily( $tpl_ini->variable( "TextToImageSettings", "Family" ) );
    $imgop->setPointSize( $tpl_ini->variable( "TextToImageSettings", "PointSize" ) );
    $imgop->setAngle( $tpl_ini->variable( "TextToImageSettings", "Angle" ) );
    $imgop->setXAdjustment( $tpl_ini->variable( "TextToImageSettings", "XAdjustment" ) );
    $imgop->setYAdjustment( $tpl_ini->variable( "TextToImageSettings", "YAdjustment" ) );
    $imgop->setColor( "bgcolor", $tpl_ini->variable( "TextToImageSettings", "BackgroundColor" ) );
    $imgop->setColor( "textcolor", $tpl_ini->variable( "TextToImageSettings", "TextColor" ) );

    $tpl->registerOperators( $imgop );

    $tpl->registerOperators( new eZTemplateUnitOperator() );
    $tpl->registerOperators( new eZURLOperator() );
    $tpl->registerOperators( new eZi18nOperator() );
    $tpl->registerOperators( new eZTemplateLogicOperator() );

    return $tpl;
}

?>
