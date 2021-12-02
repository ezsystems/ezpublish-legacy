<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'class' => 'eZURLOperator',
                                    'operator_names' => array( 'ezurl', 'ezroot', 'ezdesign', 'ezimage', 'exturl',
                                                               'ezsys', 'ezhttp', 'ezhttp_hasvariable', 'ezini', 'ezini_hasvariable' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZi18nOperator',
                                    'operator_names' => array( 'i18n', 'x18n', 'd18n' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZAlphabetOperator',
                                    'operator_names' => array( 'alphabet' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZDateOperatorCollection',
                                    'operator_names' => array( 'month_overview' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZAutoLinkOperator',
                                    'operator_names' => array( 'autolink' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZSimpleTagsOperator',
                                    'operator_names' => array( 'simpletags' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZTreeMenuOperator',
                                    'operator_names' => array( 'treemenu' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZContentStructureTreeOperator',
                                    'operator_names' => array( 'content_structure_tree' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZWordToImageOperator',
                                    'operator_names' => array( 'wordtoimage',
                                                               'mimetype_icon', 'class_icon', 'classgroup_icon', 'action_icon', 'icon',
                                                               'flag_icon', 'icon_info' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZKernelOperator',
                                    'operator_names' => array( 'ezpreference' ) );


$eZTemplateOperatorArray[] = array( 'function' => 'eZPHPOperatorInit',
                                    'operator_names_function' => 'eZPHPOperatorNameInit' );

$eZTemplateOperatorArray[] = array( 'class' => 'eZModuleParamsOperator',
                                    'operator_names' => array( 'module_params' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZTopMenuOperator',
                                    'operator_names' => array( 'topmenu' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZPackageOperator',
                                    'operator_names' => array( 'ezpackage' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZTOCOperator',
                                    'operator_names' => array( 'eztoc' ) );

$eZTemplateOperatorArray[] = array( 'class' => 'eZModuleOperator',
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
                                                                          'class' => array( 'object', 'contentclass_id' ) ),
                                               'attribute_access' => array( array( 'view_template' ) ),
                                               'use_views' => false ),

                'attribute_result_gui' => array( 'template_root' => 'content/datatype/result',
                                                 'render_mode' => false,
                                                 'input_name' => 'attribute',
                                                 'output_name' => 'attribute',
                                                 'namespace' => 'CollectionAttribute',
                                                 'attribute_keys' => array( 'attribute_identifier' => array( 'contentclass_attribute', 'identifier' ),
                                                                            'attribute' => array( 'contentclass_attribute_id' ),
                                                                            'class_identifier' => array( 'contentobject', 'class_identifier' ),
                                                                            'class' => array( 'contentobject', 'contentclass_id' ) ),
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
                                                                        'remote_id' => array( 'remote_id' ),
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
                                                                        'class_identifier' => array( 'class_identifier' ),
                                                                        'remote_id' => array( 'remote_id' ),
                                                                        'state' => array( 'state_id_array' ),
                                                                        'state_identifier' => array( 'state_identifier_array' ) ),
                                             'attribute_access' => array(),
                                             'use_views' => 'view' ),


                'content_pdf_gui' => array( 'template_root' => 'content/pdf',
                                             'input_name' => 'content_object',
                                             'output_name' => 'object',
                                             'namespace' => 'ContentView',
                                             'attribute_keys' => array( 'object' => array( 'id' ),
                                                                        'class' => array( 'contentclass_id' ),
                                                                        'section' => array( 'section_id' ),
                                                                        'remote_id' => array( 'remote_id' ),
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
                                                                     'section_identifier' => array( 'object', 'section_identifier' ),
                                                                     'class_identifier' => array( 'object', 'class_identifier' ),
                                                                     'class_group' => array( 'object', 'match_ingroup_id_list' ),
                                                                     'state' => array( 'object', 'state_id_array' ),
                                                                     'state_identifier' => array( 'object', 'state_identifier_array' ),
                                                                     'parent_node' => array( 'parent_node_id' ),
                                                                     'depth' => array( 'depth' ),
                                                                     'url_alias' => array( 'url_alias' ),
                                                                     'remote_id' => array( 'object', 'remote_id' ),
                                                                     'node_remote_id' => array( 'remote_id' ),
                                                                     'parent_class_identifier' => array( 'parent', 'class_identifier' ),
                                                                     'parent_node_remote_id' => array( 'parent', 'remote_id'),
                                                                     'parent_object_remote_id' => array( 'parent', 'object', 'remote_id') ),
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
