<?php
//
// Created on: <05-Oct-2002 21:27:11 amos>
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

/*! \file eztemplateautoload.php
*/

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezurloperator.php',
                                    'class' => 'eZURLOperator',
                                    'operator_names' => array( 'ezurl', 'ezroot', 'ezdesign', 'ezimage', 'exturl',
                                                               'ezsys', 'ezhttp', 'ezini' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezi18noperator.php',
                                    'class' => 'eZI18NOperator',
                                    'operator_names' => array( 'i18n', 'x18n' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezdateoperatorcollection.php',
                                    'class' => 'eZDateOperatorCollection',
                                    'operator_names' => array( 'month_overview' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezautolinkoperator.php',
                                    'class' => 'eZAutoLinkOperator',
                                    'operator_names' => array( 'autolink' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezsimpletagsoperator.php',
                                    'class' => 'eZSimpleTagsOperator',
                                    'operator_names' => array( 'simpletags' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/eztreemenuoperator.php',
                                    'class' => 'eZTreeMenuOperator',
                                    'operator_names' => array( 'treemenu' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezwordtoimageoperator.php',
                                    'class' => 'eZWordToImageOperator',
                                    'operator_names' => array( 'wordtoimage', 'mimetype_icon' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezkerneloperator.php',
                                    'class' => 'eZKernelOperator',
                                    'operator_names' => array( 'ezpreference' ) );


$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatephpoperator.php',
                                    'function' => 'eZPHPOperatorInit',
                                    'operator_names_function' => 'eZPHPOperatorNameInit' );
$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezmoduleparamsoperator.php',
                                    'class' => 'eZModuleParamsOperator',
                                    'operator_names' => array( 'module_params' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezpackageoperator.php',
                                    'class' => 'eZPackageOperator',
                                    'operator_names' => array( 'ezpackage' ) );

// Function autoloading

$eZTemplateFunctionArray = array();
$eZTemplateFunctionArray[] = array( 'function' => 'eZObjectForwardInit',
                                    'function_names' => array( 'my_gui',
                                                               'my_gui_view',
                                                               'my_gui2',
                                                               'my_gui3',
                                                               'attribute_edit_gui',
                                                               'attribute_view_gui',
                                                               'attribute_result_gui',
                                                               'attribute_pdf_gui',
                                                               'node_view_gui',
                                                               'content_view_gui',
                                                               'content_pdf_gui',
                                                               'shop_account_view_gui',
                                                               'content_version_view_gui',
                                                               'collaboration_view_gui',
                                                               'collaboration_icon',
                                                               'collaboration_simple_message_view',
                                                               'collaboration_participation_view',
                                                               'event_edit_gui',
                                                               'class_attribute_view_gui',
                                                               'class_attribute_edit_gui' ) );

if ( !function_exists( 'eZPHPOperatorInit' ) )
{
    function &eZPHPOperatorInit()
        {
            include_once( 'lib/eztemplate/classes/eztemplatephpoperator.php' );
            $ini =& eZINI::instance( 'template.ini' );
            $operatorList = $ini->variable( 'PHP', 'PHPOperatorList' );
            return new eZTemplatePHPOperator( $operatorList );
        }
}

if ( !function_exists( 'eZPHPOperatorNameInit' ) )
{
    function eZPHPOperatorNameInit()
        {
            $ini =& eZINI::instance( 'template.ini' );
            $operatorList = $ini->variable( 'PHP', 'PHPOperatorList' );
            return array_keys( $operatorList );
        }
}

if ( !function_exists( 'eZObjectForwardInit' ) )
{
    function &eZObjectForwardInit()
        {
            include_once( 'kernel/common/ezobjectforwarder.php' );
            $forward_rules = array(
                'attribute_edit_gui' => array( 'template_root' => 'content/datatype/edit',
                                               'input_name' => 'attribute',
                                               'output_name' => 'attribute',
                                               'namespace' => 'ContentAttribute',
                                               'attribute_access' => array( array( 'edit_template' ) ),
                                               'use_views' => false ),

                'attribute_pdf_gui' => array( 'template_root' => 'content/datatype/pdf',
                                              'input_name' => 'attribute',
                                              'output_name' => 'attribute',
                                              'namespace' => 'ContentAttribute',
                                              'attribute_access' => array( array( 'view_template' ) ),
                                              'use_views' => false ),

                'attribute_view_gui' => array( 'template_root' => array( 'type' => 'multi_match',
                                                                         'attributes' => array( 'contentclass_attribute',
                                                                                                'is_information_collector' ),
                                                                         'matches' => array( array( false,
                                                                                                    'content/datatype/view' ),
                                                                                             array( true,
                                                                                                    'content/datatype/collect' ) ) ),
                                               'render_mode' => false,
                                               'input_name' => 'attribute',
                                               'output_name' => 'attribute',
                                               'namespace' => 'ContentAttribute',
                                               'attribute_access' => array( array( 'view_template' ) ),
                                               'optional_views' => true,
                                               'use_views' => 'view' ),

                'attribute_result_gui' => array( 'template_root' => 'content/datatype/result',
                                                 'render_mode' => false,
                                                 'input_name' => 'attribute',
                                                 'output_name' => 'attribute',
                                                 'namespace' => 'CollectionAttribute',
                                                 'attribute_access' => array( array( 'result_template' ) ),
                                                 'optional_views' => true,
                                                 'use_views' => 'view' ),

                'shop_account_view_gui' => array( 'template_root' => "shop/accounthandlers",
                                                  'input_name' => 'order',
                                                  'output_name' => 'order',
                                                  'namespace' => 'ShopAccount',
                                                  'attribute_access' => array( array( 'account_view_template' ) ),
                                                  'use_views' => 'view' ),

                'content_view_gui' => array( 'template_root' => 'content/view',
                                             'input_name' => 'content_object',
                                             'output_name' => 'object',
                                             'namespace' => 'ContentView',
                                             'attribute_keys' => array( 'object' => array( 'id' ),
                                                                        'class' => array( 'contentclass_id' ),
                                                                        'section' => array( 'section_id' ) ),
                                             'attribute_access' => array(),
                                             'use_views' => 'view' ),


                'content_pdf_gui' => array( 'template_root' => 'content/pdf',
                                             'input_name' => 'content_object',
                                             'output_name' => 'object',
                                             'namespace' => 'ContentView',
                                             'attribute_keys' => array( 'object' => array( 'id' ),
                                                                        'class' => array( 'contentclass_id' ),
                                                                        'section' => array( 'section_id' ) ),
                                             'attribute_access' => array(),
                                             'use_views' => 'view' ),

                'content_version_view_gui' => array( 'template_root' => 'content/version/view',
                                                     'input_name' => 'content_version',
                                                     'output_name' => 'version',
                                                     'namespace' => 'VersionView',
                                                     'attribute_keys' => array( 'object' => array( 'contentobject_id' ) ),
                                                     'attribute_access' => array(),
                                                     'use_views' => 'view' ),

                'node_view_gui' => array( 'template_root' => 'node/view',
                                          'input_name' => 'content_node',
                                          'output_name' => 'node',
                                          'namespace' => 'NodeView',
                                          'constant_template_variables' => array( 'view_parameters' => array( 'offset' => 0 ) ),
                                          'attribute_keys' => array( 'node' => array( 'node_id' ),
                                                                     'object' => array( 'contentobject_id' ),
                                                                     'class' => array( 'object', 'contentclass_id' ),
                                                                     'section' => array( 'object', 'section_id' ),
                                                                     'class_identifier' => array( 'object', 'class_identifier' ) ),
                                          'attribute_access' => array(),
                                          'use_views' => 'view' ),

                'collaboration_view_gui' => array( 'template_root' => 'collaboration/handlers/view',
                                                   'input_name' => 'collaboration_item',
                                                   'output_name' => 'item',
                                                   'namespace' => 'Collaboration',
                                                   'attribute_keys' => array(),
                                                   'attribute_access' => array( array( 'type_identifier' ) ),
                                                   'use_views' => 'view' ),

                'collaboration_icon' => array( 'template_root' => 'collaboration/handlers/icon',
                                               'input_name' => 'collaboration_item',
                                               'output_name' => 'item',
                                               'namespace' => 'Collaboration',
                                               'attribute_keys' => array(),
                                               'attribute_access' => array( array( 'type_identifier' ) ),
                                               'use_views' => 'view' ),

                'collaboration_simple_message_view' => array( 'template_root' => 'collaboration/message/view',
                                                              'input_name' => 'collaboration_message',
                                                              'output_name' => 'item',
                                                              'namespace' => 'CollaborationMessage',
                                                              'attribute_keys' => array(),
                                                              'attribute_access' => array( array( 'message_type' ) ),
                                                              'use_views' => 'view' ),

                'collaboration_participation_view' => array( 'template_root' => array( 'type' => 'multi_match',
                                                                                       'attributes' => array( 'is_builtin_type' ),
                                                                                       'matches' => array( array( true,
                                                                                                                  'collaboration/participation/view' ),
                                                                                                           array( false,
                                                                                                                  array( 'collaboration/participation/view/custom',
                                                                                                                         array( array( 'participant_type_string' ) ) ) ) ) ),
                                                             'input_name' => 'collaboration_participant',
                                                             'output_name' => 'item',
                                                             'namespace' => 'CollaborationParticipant',
                                                             'attribute_keys' => array(),
                                                             'attribute_access' => array( array( 'participant_type_string' ) ),
                                                             'use_views' => 'view' ),

                'event_edit_gui' => array( 'template_root' => 'workflow/eventtype/edit',
                                           'input_name' => 'event',
                                           'output_name' => 'event',
                                           'namespace' => 'WorkflowEvent',
                                           'attribute_access' => array( array( 'workflow_type_string' ) ),
                                           'use_views' => false ),

                'class_attribute_view_gui' => array( 'template_root' => 'class/datatype/view',
                                                     'input_name' => 'class_attribute',
                                                     'output_name' => 'class_attribute',
                                                     'namespace' => 'ClassAttribute',
                                                     'attribute_access' => array( array( 'data_type',
                                                                                         'information',
                                                                                         'string' ) ),
                                                     'use_views' => false ),

                'class_attribute_edit_gui' => array( 'template_root' => 'class/datatype/edit',
                                                     'input_name' => 'class_attribute',
                                                     'output_name' => 'class_attribute',
                                                     'namespace' => 'ClassAttribute',
                                                     'attribute_access' => array( array( 'data_type',
                                                                                         'information',
                                                                                         'string' ) ),
                                                     'use_views' => false ) );
            return new eZObjectForwarder( $forward_rules );
        }
}

?>
