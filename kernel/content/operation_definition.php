<?php
//
// Created on: <01-Nov-2002 13:39:10 amos>
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

/*! \file operation_definition.php
*/

$OperationList = array();
$OperationList['read'] = array( 'name' => 'read',
                                'default_call_method' => array( 'include_file' => 'kernel/content/ezcontentoperationcollection.php',
                                                                'class' => 'eZContentOperationCollection' ),
                                'parameter_type' => 'standard',
                                'parameters' => array( array( 'name' => 'node_id',
                                                              'type' => 'integer',
                                                              'required' => true ),
                                                       array( 'name' => 'user_id',
                                                              'type' => 'integer',
                                                              'required' => true ),
                                                       array( 'name' => 'language_code',
                                                              'type' => 'string',
                                                              'default' => '',
                                                              'required' => false ) ),
                                'keys' => array( 'node_id' ),

                                'body' => array( array( 'type' => 'trigger',
                                                        'name' => 'pre_read',
                                                        'keys' => array( 'node_id',
                                                                         'user_id'
                                                                         ) ),



                                                 array( 'type' => 'method',
                                                        'name' => 'fetch-object',
                                                        'frequency' => 'once',
                                                        'method' => 'readObject',
                                                        ) ) );

$OperationList['publish'] = array( 'name' => 'publish',
                                   'default_call_method' => array( 'include_file' => 'kernel/content/ezcontentoperationcollection.php',
                                                                   'class' => 'eZContentOperationCollection' ),
                                   'parameters' => array( array( 'name' => 'object_id',
                                                                 'type' => 'integer',
                                                                 'required' => true ),
                                                          array( 'name' => 'version',
                                                                 'type' => 'integer',
                                                                 'required' => true ) ),
                                   'body' => array( array( 'type' => 'method',
                                                           'name' => 'set-version-pending',
                                                           'frequency' => 'once',
                                                           'method' => 'setVersionStatus',
                                                           'parameters' => array( array( 'name' => 'object_id',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  array( 'name' => 'version',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  array( 'name' => 'status',
                                                                                         'type' => 'integer',
                                                                                         'constant' => 1 ) ) ), // EZ_VERSION_STATUS_PENDING
                                                    array( 'type' => 'trigger',
                                                           'name' => 'pre_publish',
                                                           'keys' => array( 'object_id',
                                                                            'version'
                                                                             ) ),
                                                    array( 'type' => 'method',
                                                           'name' => 'set-version-archived',
                                                           'frequency' => 'once',
                                                           'method' => 'setVersionStatus',
                                                           'parameters' => array( array( 'name' => 'object_id',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  array( 'name' => 'version',
                                                                                         'type' => 'integer',
                                                                                         'constant' => false ), // false means current version
                                                                                  array( 'name' => 'status',
                                                                                         'type' => 'integer',
                                                                                         'constant' => 2 ) ) ), // EZ_VERSION_STATUS_ARCHIVED
                                                    array( 'type' => 'method',
                                                           'name' => 'update-section-id',
                                                           'frequency' => 'once',
                                                           'method' => 'updateSectionID',
                                                           ),
                                                    array( 'type' => 'loop',
                                                           'name' => 'loop-nodes',
                                                           'method' => 'loopNodeAssignment',
                                                           'continue_operation' => 'all',   // 'one', 'none'
                                                           'child_parameters' => array( array( 'name' => 'parent_node_id',
                                                                                               'type' => 'integer',
                                                                                               'required' => true ),
                                                                                        array( 'name' => 'object_id',
                                                                                               'type' => 'integer',
                                                                                               'required' => true ),
                                                                                        array( 'name' => 'version',
                                                                                               'type' => 'integer',
                                                                                               'required' => true ),
                                                                                        array( 'name' => 'main_node_id',
                                                                                               'type' => 'integer',
                                                                                               'required' => true ) ),
                                                           'children' => array( array( 'type' => 'method',
                                                                                       'name' => 'publish-node',
                                                                                       'frequency' => 'always',
                                                                                       'method' => 'publishNode' )
                                                                                ) ),
                                                    array( 'type' => 'method',
                                                           'name' => 'set-version-published',
                                                           'frequency' => 'once',
                                                           'method' => 'setVersionStatus',
                                                           'parameters' => array( array( 'name' => 'object_id',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  array( 'name' => 'version',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  array( 'name' => 'status',
                                                                                         'type' => 'integer',
                                                                                         'constant' => 3 ) ) ), // EZ_VERSION_STATUS_PUBLISHED
                                                    array( 'type' => 'method',
                                                           'name' => 'set-object-published',
                                                           'frequency' => 'once',
                                                           'method' => 'setObjectStatusPublished',
                                                           'parameters' => array( array( 'name' => 'object_id',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  ) ),
                                                    array( 'type' => 'method',
                                                           'name' => 'remove-old-nodes',
                                                           'frequency' => 'once',
                                                           'method' => 'removeOldNodes',
                                                           ),
                                                    array( 'type' => 'method',
                                                           'name' => 'attribute-publish-action',
                                                           'frequency' => 'once',
                                                           'method' => 'attributePublishAction',
                                                           'parameters' => array( array( 'name' => 'object_id',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  array( 'name' => 'version',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ) ) ),
                                                    array( 'type' => 'method',
                                                           'name' => 'register-search-object',
                                                           'frequency' => 'once',
                                                           'method' => 'registerSearchObject',
                                                           ),
                                                    array( 'type' => 'method',
                                                           'name' => 'create-notification',
                                                           'frequency' => 'once',
                                                           'method' => 'createNotificationEvent',
                                                           ),
                                                    array( 'type' => 'trigger',
                                                           'name' => 'post_publish',
                                                           'keys' => array( 'object_id',
                                                                            'version' ) ),
                                                    ) );
?>
