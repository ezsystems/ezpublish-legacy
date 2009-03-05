<?php
//
// Created on: <03-Oct-2006 13:01:25 sp>
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


$FunctionList['collected_info_count'] = array( 'name' => 'collected_info_count',
                                               'operation_types' => array( 'read' ),
                                               'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                       'method' => 'fetchCollectedInfoCount' ),
                                               'parameter_type' => 'standard',
                                               'parameters' => array( array( 'name' => 'object_attribute_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'object_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'value',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'creator_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'user_identifier',
                                                                             'type' => 'string',
                                                                             'required' => false,
                                                                             'default' => false ) ) );

$FunctionList['collected_info_count_list'] = array( 'name' => 'collected_info_count_list',
                                               'operation_types' => array( 'read' ),
                                               'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                       'method' => 'fetchCollectedInfoCountList' ),
                                               'parameter_type' => 'standard',
                                               'parameters' => array( array( 'name' => 'object_attribute_id',
                                                                             'type' => 'integer',
                                                                             'required' => true,
                                                                             'default' => false ) ) );


$FunctionList['collected_info_collection'] = array( 'name' => 'collected_info_collection',
                                                    'operation_types' => array( 'read' ),
                                                    'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                            'method' => 'fetchCollectedInfoCollection' ),
                                                    'parameter_type' => 'standard',
                                                    'parameters' => array( array( 'name' => 'collection_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'contentobject_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ) ) );

$FunctionList['collected_info_list'] = array( 'name' => 'collected_info_list',
                                                    'operation_types' => array( 'read' ),
                                                    'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                            'method' => 'fetchCollectionsList' ),
                                                    'parameter_type' => 'standard',
                                                    'parameters' => array( array( 'name' => 'object_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'creator_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'user_identifier',
                                                                                  'type' => 'string',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'limit',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'offset',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'sort_by',
                                                                                  'type' => 'array',
                                                                                  'required' => false,
                                                                                  'default' => array() ) ) );


?>
