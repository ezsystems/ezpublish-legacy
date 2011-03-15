<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/*! \file
*/

$FunctionList = array();
$FunctionList['list'] = array( 'name' => 'list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZURLFunctionCollection',
                                                       'method' => 'fetchList' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'is_valid',
                                                                    'required' => false,
                                                                    'default' => null ),
                                                             array( 'name' => 'offset',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'limit',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'only_published',
                                                                    'required' => false,
                                                                    'default' => false ) ) );
$FunctionList['list_count'] = array( 'name' => 'list_count',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZURLFunctionCollection',
                                                       'method' => 'fetchListCount' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'is_valid',
                                                                    'required' => false,
                                                                    'default' => null ),
                                                             array( 'name' => 'only_published',
                                                                    'required' => false,
                                                                    'default' => false ) ) );

?>
