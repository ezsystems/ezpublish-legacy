<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
