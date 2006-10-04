<?php
//
// Created on: <03-Oct-2006 13:01:25 sp>
//

// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
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


$FunctionList['collected_info_count'] = array( 'name' => 'collected_info_count',
                                               'operation_types' => array( 'read' ),
                                               'call_method' => array( 'include_file' => 'kernel/infocollector/ezinfocollectorfunctioncollection.php',
                                                                       'class' => 'eZInfocollectorFunctionCollection',
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
                                               'call_method' => array( 'include_file' => 'kernel/infocollector/ezinfocollectorfunctioncollection.php',
                                                                       'class' => 'eZInfocollectorFunctionCollection',
                                                                       'method' => 'fetchCollectedInfoCountList' ),
                                               'parameter_type' => 'standard',
                                               'parameters' => array( array( 'name' => 'object_attribute_id',
                                                                             'type' => 'integer',
                                                                             'required' => true,
                                                                             'default' => false ) ) );


$FunctionList['collected_info_collection'] = array( 'name' => 'collected_info_collection',
                                                    'operation_types' => array( 'read' ),
                                                    'call_method' => array( 'include_file' => 'kernel/infocollector/ezinfocollectorfunctioncollection.php',
                                                                            'class' => 'eZInfocollectorFunctionCollection',
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
                                                    'call_method' => array( 'include_file' => 'kernel/infocollector/ezinfocollectorfunctioncollection.php',
                                                                            'class' => 'eZInfocollectorFunctionCollection',
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
