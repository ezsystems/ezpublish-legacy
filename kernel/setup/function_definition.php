<?php
//
// Created on: <02-Nov-2004 13:23:10 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

$FunctionList['version'] = array( 'name' => 'version',
                                  'operation_types' => array( 'read' ),
                                  'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                          'method' => 'fetchFullVersionString' ),
                                  'parameter_type' => 'standard',
                                  'parameters' => array( ) );
$FunctionList['major_version'] = array( 'name' => 'major_version',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                'method' => 'fetchMajorVersion' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( ) );
$FunctionList['minor_version'] = array( 'name' => 'minor_version',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                'method' => 'fetchMinorVersion' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( ) );
$FunctionList['release'] = array( 'name' => 'release',
                                  'operation_types' => array( 'read' ),
                                  'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                          'method' => 'fetchRelease' ),
                                  'parameter_type' => 'standard',
                                  'parameters' => array( ) );
$FunctionList['state'] = array( 'name' => 'state',
                                'operation_types' => array( 'read' ),
                                'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                        'method' => 'fetchState' ),
                                'parameter_type' => 'standard',
                                'parameters' => array( ) );
$FunctionList['is_development'] = array( 'name' => 'is_development',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                 'method' => 'fetchIsDevelopment' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( ) );
$FunctionList['revision'] = array( 'name' => 'revision',
                                   'operation_types' => array( 'read' ),
                                   'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                           'method' => 'fetchRevision' ),
                                   'parameter_type' => 'standard',
                                   'parameters' => array( ) );
$FunctionList['database_version'] = array( 'name' => 'database_version',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                   'method' => 'fetchDatabaseVersion' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'with_release',
                                                                         'type' => 'bool',
                                                                         'required' => false,
                                                                         'default' => true ) ) );
$FunctionList['database_release'] = array( 'name' => 'database_release',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                   'method' => 'fetchDatabaseRelease' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );
?>
