<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$FunctionList = array();
$FunctionList['list_count'] = array( 'name' => 'list_count',
                                     'operation_types' => array( 'read' ),
                                     'call_method' => array( 'class' => 'eZSearchFunctionCollection',
                                                             'method' => 'fetchSearchListCount' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array() );
$FunctionList['list'] = array( 'name' => 'list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZSearchFunctionCollection',
                                                       'method' => 'fetchSearchList' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'offset',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'limit',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => false ) ) );
?>
