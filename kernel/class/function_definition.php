<?php
//
// Created on: <06-Oct-2002 16:01:10 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

$FunctionList = array();
$FunctionList['list'] = array( 'name' => 'list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZClassFunctionCollection',
                                                       'method' => 'fetchClassList' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'class_filter',
                                                             'type' => 'array',
                                                             'required' => false,
                                                             'default' => false ),
                                                      array( 'name' => 'sort_by',
                                                             'type' => 'array',
                                                             'required' => false,
                                                             'default' => array() ) ) );

$FunctionList['list_by_groups'] = array( 'name' => 'list_by_groups',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'class' => 'eZClassFunctionCollection',
                                                                'method' => 'fetchClassListByGroups' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'group_filter',
                                                                      'type' => 'array',
                                                                      'required' => true,
                                                                      'default' => false ),
                                                               array( 'name' => 'group_filter_type',
                                                                      'type' => 'string',
                                                                      'required' => false,
                                                                      'default' => 'include' ) ) );

$FunctionList['latest_list'] = array( 'operation_types' => array( 'read' ),
                                      'call_method' => array( 'class' => 'eZClassFunctionCollection',
                                                              'method' => 'fetchLatestClassList' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'offset',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'limit',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => false ) ) );

$FunctionList['attribute_list'] = array( 'name' => 'attribute_list',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'class' => 'eZClassFunctionCollection',
                                                                 'method' => 'fetchClassAttributeList' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( array( 'name' => 'class_id',
                                                                       'type' => 'integer',
                                                                       'required' => true ) ) );



$FunctionList['override_template_list'] = array( 'name' => 'override_template_list',
                                                           'operation_types' => array( 'read' ),
                                                           'call_method' => array( 'class' => 'eZClassFunctionCollection',
                                                                                   'method' => 'fetchOverrideTemplateList' ),
                                                 'parameter_type' => 'standard',
                                                 'parameters' => array( array( 'name' => 'class_id',
                                                                               'type' => 'integer',
                                                                               'required' => true ) ) );

?>
