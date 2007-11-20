<?php
//
// Created on: <14-May-2003 16:37:37 sp>
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
$FunctionList['handler_list'] = array( 'name' => 'handler_list',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                               'method' => 'handlerList' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( ) );

$FunctionList['digest_handlers'] = array( 'name' => 'digest_handlers',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                                  'method' => 'digestHandlerList' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array( array( 'name' => 'date',
                                                                        'type' => 'integer',
                                                                        'required' => true ),
                                                                 array( 'name' => 'address',
                                                                        'type' => 'string',
                                                                        'required' => true ) ) );


$FunctionList['digest_items'] = array( 'name' => 'digest_items',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                               'method' => 'digestItems' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'date',
                                                                     'type' => 'integer',
                                                                     'required' => true ),
                                                              array( 'name' => 'address',
                                                                     'type' => 'string',
                                                                     'required' => true ),
                                                              array( 'name' => 'handler',
                                                                     'type' => 'string',
                                                                     'required' => true ) ) );


$FunctionList['event_content'] = array( 'name' => 'event_content',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                               'method' => 'eventContent' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'event_id',
                                                                     'type' => 'integer',
                                                                     'required' => true ) ) );

$FunctionList['subscribed_nodes'] = array( 'name' => 'subscribed_nodes',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                                   'method' => 'subscribedNodes' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'offset',
                                                                         'type' => 'integer',
                                                                         'default' => false,
                                                                         'required' => false ),
                                                                  array( 'name' => 'limit',
                                                                         'type' => 'integer',
                                                                         'default' => false,
                                                                         'required' => false ) ) );

$FunctionList['subscribed_nodes_count'] = array( 'name' => 'subscribed_nodes_count',
                                                 'operation_types' => array( 'read' ),
                                                 'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                                         'method' => 'subscribedNodesCount' ),
                                                 'parameter_type' => 'standard',
                                                 'parameters' => array() );

?>
