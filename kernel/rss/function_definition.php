<?php

$FunctionList = array();

$FunctionList['has_export_by_node'] = array( 'name' => 'has_node_map',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZRSSFunctionCollection',
                                                       'method' => 'hasExportByNode' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'node_id',
                                                             'type' => 'integer',
                                                             'required' => true ) ) );
$FunctionList['export_by_node'] = array( 'name' => 'node_map',
                                   'operation_types' => array( 'read' ),
                                   'call_method' => array( 'class' => 'eZRSSFunctionCollection',
                                                           'method' => 'exportByNode' ),
                                   'parameter_type' => 'standard',
                                   'parameters' => array( array( 'name' => 'node_id',
                                                                 'type' => 'integer',
                                                                 'required' => true ) ) );
?>