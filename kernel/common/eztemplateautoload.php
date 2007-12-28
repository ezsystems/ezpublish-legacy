<?php
//
// Created on: <05-Oct-2002 21:27:11 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file eztemplateautoload.php
*/

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezurloperator.php',
                                    'class' => 'eZURLOperator',
                                    'operator_names' => array( 'ezurl', 'ezroot', 'ezdesign', 'ezimage', 'exturl',
                                                               'ezsys', 'ezhttp', 'ezhttp_hasvariable', 'ezini', 'ezini_hasvariable' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezi18noperator.php',
                                    'class' => 'eZI18nOperator',
                                    'operator_names' => array( 'i18n', 'x18n' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezalphabetoperator.php',
                                    'class' => 'eZAlphabetOperator',
                                    'operator_names' => array( 'alphabet' ) );

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

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezcontentstructuretreeoperator.php',
                                    'class' => 'eZContentStructureTreeOperator',
                                    'operator_names' => array( 'content_structure_tree' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezwordtoimageoperator.php',
                                    'class' => 'eZWordToImageOperator',
                                    'operator_names' => array( 'wordtoimage',
                                                               'mimetype_icon', 'class_icon', 'classgroup_icon', 'action_icon', 'icon',
                                                               'flag_icon', 'icon_info' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezkerneloperator.php',
                                    'class' => 'eZKernelOperator',
                                    'operator_names' => array( 'ezpreference' ) );


$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatephpoperator.php',
                                    'function' => 'eZPHPOperatorInit',
                                    'operator_names_function' => 'eZPHPOperatorNameInit' );
$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezmoduleparamsoperator.php',
                                    'class' => 'eZModuleParamsOperator',
                                    'operator_names' => array( 'module_params' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/eztopmenuoperator.php',
                                    'class' => 'eZTopMenuOperator',
                                    'operator_names' => array( 'topmenu' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezpackageoperator.php',
                                    'class' => 'eZPackageOperator',
                                    'operator_names' => array( 'ezpackage' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/eztocoperator.php',
                                    'class' => 'eZTOCOperator',
                                    'operator_names' => array( 'eztoc' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezmoduleoperator.php',
                                    'class' => 'eZModuleOperator',
                                    'operator_names' => array( 'ezmodule' ) );

// Function autoloading

$eZTemplateFunctionArray = array();
$eZTemplateFunctionArray[] = array( 'function' => 'eZObjectForwardInit',
                                    'function_names' => array( 'attribute_edit_gui',
                                                               'attribute_view_gui',
                                                               'attribute_result_gui',
                                                               'attribute_pdf_gui',
                                                               'attribute_diff_gui',
                                                               'related_view_gui',
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
                                                               'event_view_gui',
                                                               'class_attribute_view_gui',
                                                               'class_attribute_edit_gui' ) );

if ( !function_exists( 'eZPHPOperatorInit' ) )
{
    function eZPHPOperatorInit()
        {
            //include_once( 'lib/eztemplate/classes/eztemplatephpoperator.php' );
            $ini = eZINI::instance( 'template.ini' );
            $operatorList = $ini->variable( 'PHP', 'PHPOperatorList' );
            return new eZTemplatePHPOperator( $operatorList );
        }
}

if ( !function_exists( 'eZPHPOperatorNameInit' ) )
{
    function eZPHPOperatorNameInit()
        {
            $ini = eZINI::instance( 'template.ini' );
            $operatorList = $ini->variable( 'PHP', 'PHPOperatorList' );
            return array_keys( $operatorList );
        }
}

if ( !function_exists( 'eZObjectForwardInit' ) )
{
    function eZObjectForwardInit()
        {
            //include_once( 'kernel/common/ezobjectforwarder.php' );
            $forward_rules = array(
                'attribute_edit_gui' => array( 'template_root' => 'content/datatype/edit',
                                               'input_name' => 'attribute',
                                               'output_name' => 'attribute',
                                               'namespace' => 'ContentAttribute',
                                               'attribute_keys' => array( 'attribute_identifier' => array( 'contentclass_attribute_identifier' ),
                                                                          'attribute' => array( 'contentclassattribute_id' ),
                                                                          'class_identifier' => array( 'object', 'class_identifier' ),
                                                                          'class' => array( 'object', 'contentclass_id' ) ),
                                               'attribute_access' => array( array( 'edit_template' ) ),
                                               'use_views' => false ),

                'attribute_pdf_gui' => array( 'template_root' => 'content/datatype/pdf',
                                              'input_name' => 'attribute',
                                              'output_name' => 'attribute',
                                              'namespace' => 'ContentAttribute',
                                              'attribute_keys' => array( 'attribute_identifier' => array( 'contentclass_attribute_identifier' ),
                                                                         'attribute' => array( 'contentclassattribute_id' ),
                                                                         'class_identifier' => array( 'object', 'class_identifier' ),
                                                                         'class' => array( 'object', 'contentclass_id' ) ),
                                              'attribute_access' => array( array( 'view_template' ) ),
                                              'use_views' => false ),

                'attribute_view_gui' => array( 'template_root' => array( 'type' => 'multi_match',
                                                                         'attributes' => array( 'is_information_collector' ),
                                                                         'matches' => array( array( false,
                                                                                                    'content/datatype/view' ),
                                                                                             array( true,
                                                                                                    'content/datatype/collect' ) ) ),
                                               'render_mode' => false,
                                               'input_name' => 'attribute',
                                               'output_name' => 'attribute',
                                               'namespace' => 'ContentAttribute',
                                               'attribute_keys' => array( 'attribute_identifier' => array( 'contentclass_attribute_identifier' ),
                                                                          'attribute' => array( 'contentclassattribute_id' ),
                                                                          'class_identifier' => array( 'object', 'class_identifier' ),
                                                                          'class' => array( 'object', 'contentclass_id' ) ),
                                               'attribute_access' => array( array( 'view_template' ) ),
                                               'optional_views' => true,
                                               'use_views' => 'view' ),

                'attribute_diff_gui' => array( 'template_root' => 'content/datatype/diff',
                                               'input_name' => 'attribute',
                                               'output_name' => 'attribute',
                                               'namespace' => 'ContentAttribute',
                                               'attribute_keys' => array( 'attribute_identifier' => array( 'contentclass_attribute_identifier' ),
                                                                          'attribute' => array( 'contentclassattribute_id' ),
                                                                          'class_identifier' => array( 'object', 'class_identifier' ),
                                                                          'class' => array( 'object', 'contentclass_id' ) ),                                               'attribute_access' => array( array( 'view_template' ) ),
                                               'attribute_access' => array( array( 'view_template' ) ),
                                               'use_views' => false ),

                'attribute_result_gui' => array( 'template_root' => 'content/datatype/result',
                                                 'render_mode' => false,
                                                 'input_name' => 'attribute',
                                                 'output_name' => 'attribute',
                                                 'namespace' => 'CollectionAttribute',
                                                 'attribute_keys' => array( 'attribute_identifier' => array( 'contentclass_attribute_identifier' ),
                                                                            'attribute' => array( 'contentclassattribute_id' ),
                                                                            'class_identifier' => array( 'object', 'class_identifier' ),
                                                                            'class' => array( 'object', 'contentclass_id' ) ),
                                                 'attribute_access' => array( array( 'result_template' ) ),
                                                 'optional_views' => true,
                                                 'use_views' => 'view' ),

                'related_view_gui' => array( 'template_root' => 'content/related',
                                             'input_name' => 'related_object',
                                             'output_name' => 'related_object',
                                             'namespace' => 'RelatedView',
                                             'attribute_keys' => array( 'object' => array( 'id' ),
                                                                        'class' => array( 'class_id' ),
                                                                        'section' => array( 'section_id' ),
                                                                        'class_identifier' => array( 'class_identifier' ) ),
                                             'attribute_access' => array(),
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
                                                                        'class_group' => array( 'match_ingroup_id_list' ),
                                                                        'class' => array( 'contentclass_id' ),
                                                                        'section' => array( 'section_id' ),
                                                                        'class_identifier' => array( 'class_identifier' ) ),
                                             'attribute_access' => array(),
                                             'use_views' => 'view' ),


                'content_pdf_gui' => array( 'template_root' => 'content/pdf',
                                             'input_name' => 'content_object',
                                             'output_name' => 'object',
                                             'namespace' => 'ContentView',
                                             'attribute_keys' => array( 'object' => array( 'id' ),
                                                                        'class' => array( 'contentclass_id' ),
                                                                        'section' => array( 'section_id' ),
                                                                        'class_identifier' => array( 'class_identifier' ) ),
                                             'attribute_access' => array(),
                                             'use_views' => 'view' ),

                'content_version_view_gui' => array( 'template_root' => 'content/version/view',
                                                     'input_name' => 'content_version',
                                                     'output_name' => 'version',
                                                     'namespace' => 'VersionView',
                                                     'attribute_keys' => array( 'object' => array( 'contentobject_id' ),
                                                                                'class' => array( 'contentobject', 'contentclass_id' ),
                                                                                'section' => array( 'contentobject', 'section_id' ),
                                                                                'class_identifier' => array( 'contentobject', 'class_identifier' ) ),
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
                                                                     'class_identifier' => array( 'object', 'class_identifier' ),
                                                                     'class_group' => array( 'object','match_ingroup_id_list' ),
                                                                     'parent_node' => array( 'parent_node_id' ),
                                                                     'depth' => array( 'depth' ),
                                                                     'url_alias' => array( 'url_alias' ) ),
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

                'event_view_gui' => array( 'template_root' => 'workflow/eventtype/view',
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
