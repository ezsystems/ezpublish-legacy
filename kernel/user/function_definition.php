<?php
//
// Created on: <06-Oct-2002 16:01:10 amos>
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

/*! \file function_definition.php
*/

$FunctionList = array();
$FunctionList['current_user'] = array( 'name' => 'current_user',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                               'method' => 'fetchCurrentUser' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array() );

$FunctionList['is_logged_in'] = array( 'name' => 'is_logged_in',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                               'method' => 'fetchIsLoggedIn' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'user_id',
                                                                     'type' => 'integer',
                                                                     'required' => true ) ) );

$FunctionList['logged_in_count'] = array( 'name' => 'logged_in_count',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                  'method' => 'fetchLoggedInCount' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array() );

$FunctionList['anonymous_count'] = array( 'name' => 'anonymous_count',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                  'method' => 'fetchAnonymousCount' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array() );

$FunctionList['logged_in_list'] = array( 'name' => 'logged_in_list',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                 'method' => 'fetchLoggedInList' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( array( 'name' => 'sort_by',
                                                                       'type' => 'mixed',
                                                                       'required' => false ),
                                                                array( 'name' => 'offset',
                                                                       'type' => 'integer',
                                                                       'required' => false ),
                                                                array( 'name' => 'limit',
                                                                       'type' => 'integer',
                                                                       'required' => false ) ) );

$FunctionList['logged_in_users'] = array( 'name' => 'logged_in_users',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                  'method' => 'fetchLoggedInUsers' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array( array( 'name' => 'sort_by',
                                                                        'type' => 'mixed',
                                                                        'required' => false ),
                                                                 array( 'name' => 'offset',
                                                                        'type' => 'integer',
                                                                        'required' => false ),
                                                                 array( 'name' => 'limit',
                                                                        'type' => 'integer',
                                                                        'required' => false ) ) );
$FunctionList['user_role'] = array( 'name' => 'user_role',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                            'method' => 'fetchUserRole' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'user_id',
                                                                  'type' => 'integer',
                                                                  'required' => true ) ) );


$FunctionList['member_of'] = array( 'name' => 'member_of',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                            'method' => 'fetchMemberOf' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'id',
                                                                  'type' => 'integer',
                                                                  'required' => true ) ) );

$FunctionList['has_access_to'] = array( 'name' => 'has_access_to',
                                        'operation_types' => array(),
                                        'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                'method' => 'hasAccessTo' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'module',
                                                                      'type' => 'string',
                                                                      'required' => true ),
                                                               array( 'name' => 'function',
                                                                      'type' => 'string',
                                                                      'required' => true ),
                                                               array( 'name' => 'user_id',
                                                                      'type' => 'integer',
                                                                      'required' => false ) ) );

?>
