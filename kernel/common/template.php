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
    include_once( "kernel/common/ezurloperator.php" );
    include_once( "kernel/common/ezi18noperator.php" );
    include_once( 'kernel/common/eztemplatedesignresource.php' );
    include_once( 'kernel/common/ezobjectforwarder.php' );
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
                                                                "class" => array( "contentclass_id" ),
                                                                "section" => array( "section_id" ) ),
                                     "attribute_access" => array(),
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
    $ttiINI =& eZINI::instance( 'texttoimage.ini' );
    $imgop->setFontDir( realpath( "." ) . "/" . $ttiINI->variable( "PathSettings", "FontDir" ) );
    $imgop->setCacheDir( realpath( "." ) . "/" . $ttiINI->variable( "PathSettings", "CacheDir" ) );
    $imgop->setHtmlDir( eZSys::wwwDir() . $ttiINI->variable( "PathSettings", "HtmlDir" ) );

    $tpl->registerOperators( $imgop );

    $tpl->registerOperators( new eZTemplateUnitOperator() );
    $tpl->registerOperators( new eZURLOperator() );
    $tpl->registerOperators( new eZi18nOperator() );
    $tpl->registerOperators( new eZTemplateLogicOperator() );

    return $tpl;
}

?>
