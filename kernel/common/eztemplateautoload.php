<?php
//
// Created on: <05-Oct-2002 21:27:11 amos>
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

/*! \file eztemplateautoload.php
*/

// Operator autoloading

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezurloperator.php',
                                    'class' => 'eZURLOperator',
                                    'operator_names' => array( 'ezurl', 'ezroot', 'ezdesign', 'ezimage', 'exturl' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'kernel/common/ezi18noperator.php',
                                    'class' => 'eZI18NOperator',
                                    'operator_names' => array( 'i18n' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatephpoperator.php',
                                    'class' => 'eZTemplatePHPOperator',
                                    'class_parameter' => array( 'upcase' => 'strtoupper',
                                                                'reverse' => 'strrev',
                                                                'nl2br' => 'nl2br' ),
                                    'operator_names' => array( 'upcase', 'reverse', 'nl2br' ) );

// Function autoloading

$eZTemplateFunctionArray = array();
$eZTemplateFunctionArray[] = array( 'function' => 'eZObjectForwardInit',
                                    'function_names' => array( 'attribute_edit_gui',
                                                               'attribute_view_gui',
                                                               'node_view_gui',
                                                               'content_view_gui',
                                                               'event_edit_gui',
                                                               'class_attribute_edit_gui' ) );

if ( !function_exists( 'eZObjectForwardInit' ) )
{
    function &eZObjectForwardInit()
        {
            include_once( 'kernel/common/ezobjectforwarder.php' );
            $forward_rules = array(
                'attribute_edit_gui' => array( 'template_root' => 'content/datatype/edit',
                                               'input_name' => 'attribute',
                                               'output_name' => 'attribute',
                                               'namespace' => '',
                                               'attribute_access' => array( array( 'contentclass_attribute',
                                                                                   'data_type',
                                                                                   'information',
                                                                                   'string' ) ),
                                               'use_views' => false ),
                'attribute_view_gui' => array( 'template_root' => 'content/datatype/view',
                                               'input_name' => 'attribute',
                                               'output_name' => 'attribute',
                                               'namespace' => '',
                                               'attribute_access' => array( array( 'contentclass_attribute',
                                                                                   'data_type',
                                                                                   'information',
                                                                                   'string' ) ),
                                               'use_views' => false ),
                'content_view_gui' => array( 'template_root' => 'content/view',
                                             'input_name' => 'content_object',
                                             'output_name' => 'object',
                                             'namespace' => 'ContentView',
                                             'attribute_keys' => array( 'object' => array( 'id' ),
                                                                        'class' => array( 'contentclass_id' ),
                                                                        'section' => array( 'section_id' ) ),
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
                                                                     'section' => array( 'object', 'section_id' ) ),
                                          'attribute_access' => array(),
                                          'use_views' => 'view' ),
                'event_edit_gui' => array( 'template_root' => 'workflow/eventtype/edit',
                                           'input_name' => 'event',
                                           'output_name' => 'event',
                                           'namespace' => 'WorkflowEvent',
                                           'attribute_access' => array( array( 'workflow_type_string' ) ),
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
