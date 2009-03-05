<?php
//
// Created on: <23-May-2003 16:45:07 amos>
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

$FunctionList['object'] = array( 'name' => 'object',
                                 'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                         'method' => 'fetchSectionObject' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array( array( 'name' => 'section_id',
                                                               'type' => 'integer',
                                                               'required' => true ) ) );

$FunctionList['list'] = array( 'name' => 'list',
                               'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                       'method' => 'fetchSectionList' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( ) );

$FunctionList['object_list'] = array( 'name' => 'object_list',
                                      'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                              'method' => 'fetchObjectList' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'section_id',
                                                                    'type' => 'integer',
                                                                    'required' => true ),
                                                             array( 'name' => 'offset',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'limit',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'sort_order',
                                                                    'type' => 'variant',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'status',
                                                                    'type' => 'string',
                                                                    'required' => false,
                                                                    'default' => false ) ) );

$FunctionList['object_list_count'] = array( 'name' => 'object_list_count',
                                            'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                                    'method' => 'fetchObjectListCount' ),
                                            'parameter_type' => 'standard',
                                            'parameters' => array( array( 'name' => 'section_id',
                                                                          'type' => 'integer',
                                                                          'required' => true ),
                                                                   array( 'name' => 'status',
                                                                          'type' => 'string',
                                                                          'required' => false,
                                                                          'default' => false ) ) );

$FunctionList['roles'] = array( 'name' => 'roles',
                                'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                        'method' => 'fetchRoles' ),
                                'parameter_type' => 'standard',
                                'parameters' => array( array( 'name' => 'section_id',
                                                              'type' => 'integer',
                                                              'required' => true ) ) );

$FunctionList['user_roles'] = array( 'name' => 'user_roles',
                                     'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                             'method' => 'fetchUserRoles' ),
                                     'parameter_type' => 'standard',
                                     'parameters' => array( array( 'name' => 'section_id',
                                                                   'type' => 'integer',
                                                                   'required' => true ) ) );

?>
