<?php
//
// Created on: <06-Oct-2002 16:01:10 amos>
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

/*! \file function_definition.php
*/

$FunctionList = array();
$FunctionList['current_user'] = array( 'name' => 'current_user',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'include_file' => 'kernel/user/ezuserfunctioncollection.php',
                                                               'class' => 'eZUserFunctionCollection',
                                                               'method' => 'fetchCurrentUser' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array() );

$FunctionList['is_logged_in'] = array( 'name' => 'is_logged_in',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'include_file' => 'kernel/user/ezuserfunctioncollection.php',
                                                               'class' => 'eZUserFunctionCollection',
                                                               'method' => 'fetchIsLoggedIn' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'user_id',
                                                                     'type' => 'integer',
                                                                     'required' => true ) ) );

$FunctionList['logged_in_count'] = array( 'name' => 'logged_in_count',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'include_file' => 'kernel/user/ezuserfunctioncollection.php',
                                                                  'class' => 'eZUserFunctionCollection',
                                                                  'method' => 'fetchLoggedInCount' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array() );

$FunctionList['anonymous_count'] = array( 'name' => 'anonymous_count',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'include_file' => 'kernel/user/ezuserfunctioncollection.php',
                                                                  'class' => 'eZUserFunctionCollection',
                                                                  'method' => 'fetchAnonymousCount' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array() );

$FunctionList['logged_in_list'] = array( 'name' => 'logged_in_list',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'include_file' => 'kernel/user/ezuserfunctioncollection.php',
                                                                 'class' => 'eZUserFunctionCollection',
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
                                          'call_method' => array( 'include_file' => 'kernel/user/ezuserfunctioncollection.php',
                                                                  'class' => 'eZUserFunctionCollection',
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

$FunctionList['member_of'] = array( 'name' => 'member_of',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'include_file' => 'kernel/user/ezuserfunctioncollection.php',
                                                            'class' => 'eZUserFunctionCollection',
                                                            'method' => 'fetchMemberOf' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'id',
                                                                  'type' => 'integer',
                                                                  'required' => true ) ) );

?>
