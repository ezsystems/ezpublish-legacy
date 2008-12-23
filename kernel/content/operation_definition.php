<?php
//
// Created on: <01-Nov-2002 13:39:10 amos>
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
                                                                                         'constant' => 1 ) ) ), // eZContentObjectVersion::STATUS_PENDING
                                                    array( 'type' => 'method',
                                                           'name' => 'update-section-id',
                                                           'frequency' => 'once',
                                                           'method' => 'updateSectionID'
                                                           ),
                                                    array( 'type' => 'trigger',
                                                           'name' => 'pre_publish',
                                                           'keys' => array( 'object_id',
                                                                            'version' )
                                                           ),
                                                    array( 'type' => 'method',
                                                           'name' => 'copy-translations',
                                                           'frequency' => 'once',
                                                           'method' => 'copyTranslations' ),
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
                                                                                         'constant' => 2 ) ) ), // eZContentObjectVersion::STATUS_ARCHIVED
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
                                                                                         'constant' => 3 ) ) ), // eZContentObjectVersion::STATUS_PUBLISHED
                                                    array( 'type' => 'method',
                                                           'name' => 'set-object-published',
                                                           'frequency' => 'once',
                                                           'method' => 'setObjectStatusPublished',
                                                           'parameters' => array( array( 'name' => 'object_id',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  array( 'name' => 'version',
                                                                                         'type' => 'integer',
                                                                                         'required' => true )
                                                                                  ) ),
                                                    array( 'type' => 'method',
                                                           'name' => 'publish-object-extension-handler',
                                                           'frequency' => 'once',
                                                           'method' => 'publishObjectExtensionHandler',
                                                           'parameters' => array( array( 'name' => 'object_id',
                                                                                         'type' => 'integer',
                                                                                         'required' => true ),
                                                                                  array( 'name' => 'version',
                                                                                         'type' => 'integer',
                                                                                         'required' => true )
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
                                                           'name' => 'update-nontranslatable-attributes',
                                                           'frequency' => 'once',
                                                           'method' => 'updateNontranslatableAttributes' ),
                                                    array( 'type' => 'method',
                                                           'name' => 'reset-nodeassignment-opcodes',
                                                           'frequency' => 'once',
                                                           'method' => 'resetNodeassignmentOpcodes',
                                                           ),

                                                    array( 'type' => 'method',
                                                           'name' => 'clear-object-view-cache',
                                                           'frequency' => 'once',
                                                           'method' => 'clearObjectViewCache',
                                                           'parameters' => array(  array( 'name' => 'object_id',
                                                                                          'type' => 'integer',
                                                                                          'required' => true ),
                                                                                   array( 'name' => 'version',
                                                                                          'type' => 'integer',
                                                                                          'required' => true ) ) ),
                                                    // PreGeneration: This generates view cache for a given set of users if enabled
                                                    array( 'type' => 'method',
                                                           'name' => 'generate-object-view-cache',
                                                           'frequency' => 'once',
                                                           'method' => 'generateObjectViewCache',
                                                           'parameters' => array(  array( 'name' => 'object_id',
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

                                                    array( 'type' => 'method',
                                                           'name' => 'remove-temporary-drafts',
                                                           'frequency' => 'once',
                                                           'method' => 'removeTemporaryDrafts'
                                                           ),

                                                    array( 'type' => 'trigger',
                                                           'name' => 'post_publish',
                                                           'keys' => array( 'object_id',
                                                                            'version' ) ),
                                                    ) );
?>
