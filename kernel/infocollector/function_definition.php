<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$FunctionList = array();


$FunctionList['collected_info_count'] = array( 'name' => 'collected_info_count',
                                               'operation_types' => array( 'read' ),
                                               'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                       'method' => 'fetchCollectedInfoCount' ),
                                               'parameter_type' => 'standard',
                                               'parameters' => array( array( 'name' => 'object_attribute_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'object_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'value',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'creator_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'user_identifier',
                                                                             'type' => 'string',
                                                                             'required' => false,
                                                                             'default' => false ) ) );

$FunctionList['collected_info_count_list'] = array( 'name' => 'collected_info_count_list',
                                               'operation_types' => array( 'read' ),
                                               'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                       'method' => 'fetchCollectedInfoCountList' ),
                                               'parameter_type' => 'standard',
                                               'parameters' => array( array( 'name' => 'object_attribute_id',
                                                                             'type' => 'integer',
                                                                             'required' => true,
                                                                             'default' => false ) ) );


$FunctionList['collected_info_collection'] = array( 'name' => 'collected_info_collection',
                                                    'operation_types' => array( 'read' ),
                                                    'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                            'method' => 'fetchCollectedInfoCollection' ),
                                                    'parameter_type' => 'standard',
                                                    'parameters' => array( array( 'name' => 'collection_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'contentobject_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ) ) );

$FunctionList['collected_info_list'] = array( 'name' => 'collected_info_list',
                                                    'operation_types' => array( 'read' ),
                                                    'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                            'method' => 'fetchCollectionsList' ),
                                                    'parameter_type' => 'standard',
                                                    'parameters' => array( array( 'name' => 'object_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'creator_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'user_identifier',
                                                                                  'type' => 'string',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'limit',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'offset',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'sort_by',
                                                                                  'type' => 'array',
                                                                                  'required' => false,
                                                                                  'default' => array() ) ) );


?>
