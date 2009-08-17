<?php
//
// Created on: <27-Apr-2009 14:36:10 rp/ar>
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

$OperationList = array();

$OperationList['setsettings'] = array( 'name' => 'setsettings',
                                        'default_call_method' => array( 'include_file' => 'kernel/user/ezuseroperationcollection.php',
                                                                        'class' => 'eZUserOperationCollection' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'user_id',
                                                                      'type' => 'integer',
                                                                      'required' => true ),
                                                               array( 'name' => 'is_enabled',
                                                                      'type' => 'integer',
                                                                      'required' => true ),
                                                               array( 'name' => 'max_login',
                                                                      'type' => 'integer',
                                                                      'required' => false ), ),
                                        'keys' => array( 'user_id', 'is_enabled', 'max_login' ),

                                        'body' => array( array( 'type' => 'trigger',
                                                                'name' => 'pre_setsettings',
                                                                'keys' => array( 'user_id',
                                                                                 'is_enabled',
                                                                                 'max_login')
                                                                ),
                                                         array( 'type' => 'method',
                                                                'name' => 'setsettings',
                                                                'frequency' => 'once',
                                                                'method' => 'setSettings',
                                                                ),
                                                         array( 'type' => 'trigger',
                                                                'name' => 'post_setsettings',
                                                                'keys' => array( 'user_id',
                                                                                 'is_enabled',
                                                                                 'max_login'
                                                                                 ) ) )
                                        );

$OperationList['activation'] = array( 'name' => 'activation',
                                        'default_call_method' => array( 'include_file' => 'kernel/user/ezuseroperationcollection.php',
                                                                        'class' => 'eZUserOperationCollection' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'user_id',
                                                                      'type' => 'integer',
                                                                      'required' => true ),
                                                               array( 'name' => 'user_hash',
                                                                      'type' => 'string',
                                                                      'required' => true ),
                                                               array( 'name' => 'is_enabled',
                                                                      'type' => 'bool',
                                                                      'required' => false ), ),
                                        'keys' => array( 'user_id', 'user_hash', 'is_enabled' ),

                                        'body' => array( array( 'type' => 'trigger',
                                                                'name' => 'pre_activation',
                                                                'keys' => array( 'user_id',
                                                                                 'user_hash',
                                                                                 'is_enabled')
                                                                ),
                                                         array( 'type' => 'method',
                                                                'name' => 'activation',
                                                                'frequency' => 'once',
                                                                'method' => 'activation',
                                                                ),
                                                         array( 'type' => 'trigger',
                                                                'name' => 'post_activation',
                                                                'keys' => array( 'user_id',
                                                                                 'user_hash',
                                                                                 'is_enabled'
                                                                                 ) ) )
                                        );

$OperationList['password'] = array( 'name' => 'password',
                                        'default_call_method' => array( 'include_file' => 'kernel/user/ezuseroperationcollection.php',
                                                                        'class' => 'eZUserOperationCollection' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'user_id',
                                                                      'type' => 'integer',
                                                                      'required' => true ),
                                                               array( 'name' => 'new_password',
                                                                      'type' => 'string',
                                                                      'required' => true ), ),
                                        'keys' => array( 'user_id', 'new_password' ),

                                        'body' => array( array( 'type' => 'trigger',
                                                                'name' => 'pre_password',
                                                                'keys' => array( 'user_id',
                                                                                 'new_password')
                                                                ),
                                                         array( 'type' => 'method',
                                                                'name' => 'password',
                                                                'frequency' => 'once',
                                                                'method' => 'password',
                                                                ),
                                                         array( 'type' => 'trigger',
                                                                'name' => 'post_password',
                                                                'keys' => array( 'user_id',
                                                                                 'new_password'
                                                         ) ) )
                                        );

$OperationList['forgotpassword'] = array( 'name' => 'forgotpassword',
                                        'default_call_method' => array( 'include_file' => 'kernel/user/ezuseroperationcollection.php',
                                                                        'class' => 'eZUserOperationCollection' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'user_id',
                                                                      'type' => 'integer',
                                                                      'required' => true ),
                                                               array( 'name' => 'password_hash',
                                                                      'type' => 'string',
                                                                      'required' => true ),
                                                               array( 'name' => 'time',
                                                                      'type' => 'integer',
                                                                      'required' => true )),
                                        'keys' => array( 'user_id', 'password_hash', 'time' ),

                                        'body' => array( array( 'type' => 'trigger',
                                                                'name' => 'pre_forgotpassword',
                                                                'keys' => array( 'user_id',
                                                                                 'password_hash',
                                                                                 'time')
                                                                ),
                                                         array( 'type' => 'method',
                                                                'name' => 'forgotpassword',
                                                                'frequency' => 'once',
                                                                'method' => 'forgotpassword',
                                                                ),
                                                         array( 'type' => 'trigger',
                                                                'name' => 'post_forgotpassword',
                                                                'keys' => array( 'user_id',
                                                                                 'password_hash',
                                                                                 'time'
                                                         ) ) )
                                        );

$OperationList['preferences'] = array( 'name' => 'preferences',
                                        'default_call_method' => array( 'include_file' => 'kernel/user/ezuseroperationcollection.php',
                                                                        'class' => 'eZUserOperationCollection' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'key',
                                                                      'type' => 'string',
                                                                      'required' => true ),
                                                               array( 'name' => 'value',
                                                                      'type' => 'string',
                                                                      'required' => true )),
                                        'keys' => array( 'key', 'value' ),

                                        'body' => array( array( 'type' => 'trigger',
                                                                'name' => 'pre_preferences',
                                                                'keys' => array( 'key',
                                                                                 'value')
                                                                ),
                                                         array( 'type' => 'method',
                                                                'name' => 'preferences',
                                                                'frequency' => 'once',
                                                                'method' => 'preferences',
                                                                ),
                                                         array( 'type' => 'trigger',
                                                                'name' => 'post_preferences',
                                                                'keys' => array( 'key',
                                                                                 'value'
                                                         ) ) )
                                        );

?>
