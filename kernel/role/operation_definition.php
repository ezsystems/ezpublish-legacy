<?php
//
// Created on: <27-Apr-2009 14:36:10 rp/ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$OperationList['assign'] = array( 'name' => 'assign',
                                        'default_call_method' => array( 'include_file' => 'kernel/role/ezroleoperationcollection.php',
                                                                        'class' => 'eZRoleOperationCollection' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'roleID',
                                                                      'type' => 'integer',
                                                                      'required' => true ),
                                                               array( 'name' => 'limitIdent',
                                                                      'type' => 'string',
                                                                      'required' => false ),
                                                               array( 'name' => 'limitValue',
                                                                      'type' => 'string',
                                                                      'required' => false ),),
                                        'keys' => array( 'roleID', 'limitIdent', 'limitValue' ),

                                        'body' => array( array( 'type' => 'trigger',
                                                                'name' => 'pre_assign',
                                                                'keys' => array( 'roleID',
                                                                                 'limitIdent',
                                                                                 'limitValue')
                                                                ),
                                                         array( 'type' => 'method',
                                                                'name' => 'assign',
                                                                'frequency' => 'once',
                                                                'method' => 'assignRole',
                                                                ),
                                                         array( 'type' => 'trigger',
                                                                'name' => 'post_assign',
                                                                'keys' => array( 'roleID',
                                                                                 'limitIdent',
                                                                                 'limitValue') ) )
                                        );


?>

