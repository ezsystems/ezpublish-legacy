<?php 
$schema = 
array (
  'ezapprove_items' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'workflow_process_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'collaboration_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezapprove_items12_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezbasket' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'session_id' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'productcollection_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezbasket24_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezbasket_session_id' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'session_id',
        ),
      ),
    ),
  ),
  'ezbinaryfile' => 
  array (
    'fields' => 
    array (
      'contentobject_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'filename' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'original_filename' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'mime_type' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezbinaryfile36_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'contentobject_attribute_id',
          1 => 'version',
        ),
      ),
    ),
  ),
  'ezcollab_group' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'parent_group_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'depth' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'path_string' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'is_open' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'title' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'priority' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_group50_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezcollab_group_depth63' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'depth',
        ),
      ),
      'ezcollab_group_path62' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'path_string',
        ),
      ),
    ),
  ),
  'ezcollab_item' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'type_identifier' => 
      array (
        'length' => '40',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'status' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'data_text1' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_text2' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_text3' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_int1' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_int2' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_int3' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_float1' => 
      array (
        'type' => 'float',
        'not_null' => '1',
        'default' => '0',
      ),
      'data_float2' => 
      array (
        'type' => 'float',
        'not_null' => '1',
        'default' => '0',
      ),
      'data_float3' => 
      array (
        'type' => 'float',
        'not_null' => '1',
        'default' => '0',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_item71_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcollab_item_group_link' => 
  array (
    'fields' => 
    array (
      'collaboration_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'group_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_read' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_active' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'last_read' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_item_group_link95_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'collaboration_id',
          1 => 'group_id',
          2 => 'user_id',
        ),
      ),
    ),
  ),
  'ezcollab_item_message_link' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'collaboration_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'participant_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'message_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'message_type' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_item_message_link112_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcollab_item_participant_link' => 
  array (
    'fields' => 
    array (
      'collaboration_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'participant_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'participant_type' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'participant_role' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'is_read' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_active' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'last_read' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_item_participant_link128_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'collaboration_id',
          1 => 'participant_id',
        ),
      ),
    ),
  ),
  'ezcollab_item_status' => 
  array (
    'fields' => 
    array (
      'collaboration_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_read' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_active' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'last_read' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_item_status146_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'collaboration_id',
          1 => 'user_id',
        ),
      ),
    ),
  ),
  'ezcollab_notification_rule' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'collab_identifier' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_notification_rule160_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcollab_profile' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'main_group' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_text1' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_profile172_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcollab_simple_message' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'message_type' => 
      array (
        'length' => '40',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_text1' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_text2' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_text3' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_int1' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_int2' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_int3' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_float1' => 
      array (
        'type' => 'float',
        'not_null' => '1',
        'default' => '0',
      ),
      'data_float2' => 
      array (
        'type' => 'float',
        'not_null' => '1',
        'default' => '0',
      ),
      'data_float3' => 
      array (
        'type' => 'float',
        'not_null' => '1',
        'default' => '0',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcollab_simple_message187_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcontent_translation' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'locale' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezcontent_translation210_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcontentbrowsebookmark' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentbrowsebookmark222_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezcontentbrowsebookmark_user228' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'user_id',
        ),
      ),
    ),
  ),
  'ezcontentbrowserecent' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentbrowserecent236_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezcontentbrowserecent_user243' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'user_id',
        ),
      ),
    ),
  ),
  'ezcontentclass' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'identifier' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'contentobject_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modifier_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentclass251_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
          1 => 'version',
        ),
      ),
      'ezcontentclass_version262' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'version',
        ),
      ),
    ),
  ),
  'ezcontentclass_attribute' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclass_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'identifier' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'data_type_string' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'is_searchable' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_required' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'placement' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_int1' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_int2' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_int3' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_int4' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_float1' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'data_float2' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'data_float3' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'data_float4' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'data_text1' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'data_text2' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'data_text3' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'data_text4' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'data_text5' => 
      array (
        'type' => 'longtext',
      ),
      'is_information_collector' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'can_translate' => 
      array (
        'length' => 11,
        'type' => 'int',
        'default' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentclass_attribute270_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
          1 => 'version',
        ),
      ),
    ),
  ),
  'ezcontentclass_classgroup' => 
  array (
    'fields' => 
    array (
      'contentclass_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclass_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'group_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'group_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentclass_classgroup303_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'contentclass_id',
          1 => 'contentclass_version',
          2 => 'group_id',
        ),
      ),
    ),
  ),
  'ezcontentclassgroup' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modifier_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentclassgroup316_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcontentobject' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'owner_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'section_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclass_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'current_version' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'is_published' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'published' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'status' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'remote_id' => 
      array (
        'length' => '100',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentobject331_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcontentobject_attribute' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'language_code' => 
      array (
        'length' => '20',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclassattribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_text' => 
      array (
        'type' => 'longtext',
      ),
      'data_int' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_float' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'attribute_original_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'sort_key_int' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'sort_key_string' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'default' => '',
      ),
      'data_type_string' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentobject_attribute_contentobject_id364' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'contentobject_id',
        ),
      ),
      'ezcontentobject_attribute_language_code365' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'language_code',
        ),
      ),
      'sort_key_int366' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'sort_key_int',
        ),
      ),
      'sort_key_string367' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'sort_key_string',
        ),
      ),
      'ezcontentobject_attribute_co_id_ver_lang_code' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'contentobject_id',
          1 => 'version',
          2 => 'language_code',
        ),
      ),
      'ezcontentobject_attribute351_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
          1 => 'version',
        ),
      ),
    ),
  ),
  'ezcontentobject_link' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'from_contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'from_contentobject_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'to_contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentobject_link375_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezcontentobject_name' => 
  array (
    'fields' => 
    array (
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'content_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'content_translation' => 
      array (
        'length' => '20',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'real_translation' => 
      array (
        'length' => '20',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentobject_name388_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'contentobject_id',
          1 => 'content_version',
          2 => 'content_translation',
        ),
      ),
    ),
  ),
  'ezcontentobject_tree' => 
  array (
    'fields' => 
    array (
      'node_id' => 
      array (
        'type' => 'auto_increment',
      ),
      'parent_node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'contentobject_version' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'contentobject_is_published' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'depth' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'path_string' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'sort_field' => 
      array (
        'length' => 11,
        'type' => 'int',
        'default' => '1',
      ),
      'sort_order' => 
      array (
        'length' => 11,
        'type' => 'int',
        'default' => '1',
      ),
      'priority' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'path_identification_string' => 
      array (
        'type' => 'longtext',
      ),
      'main_node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentobject_tree_path416' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'path_string',
        ),
      ),
      'ezcontentobject_tree_p_node_id417' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'parent_node_id',
        ),
      ),
      'ezcontentobject_tree_co_id418' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'contentobject_id',
        ),
      ),
      'ezcontentobject_tree_depth419' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'depth',
        ),
      ),
      'ezcontentobject_tree402_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'node_id',
        ),
      ),
    ),
  ),
  'ezcontentobject_version' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'status' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'workflow_event_pos' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezcontentobject_version427_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezdiscountrule' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezdiscountrule445_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezdiscountsubrule' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'discountrule_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'discount_percent' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'limitation' => 
      array (
        'length' => '1',
        'type' => 'char',
      ),
    ),
    'indexes' => 
    array (
      'ezdiscountsubrule456_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezdiscountsubrule_value' => 
  array (
    'fields' => 
    array (
      'discountsubrule_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'value' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'issection' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezdiscountsubrule_value470_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'discountsubrule_id',
          1 => 'value',
          2 => 'issection',
        ),
      ),
    ),
  ),
  'ezenumobjectvalue' => 
  array (
    'fields' => 
    array (
      'contentobject_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentobject_attribute_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'enumid' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'enumelement' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'enumvalue' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezenumobjectvalue482_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'contentobject_attribute_id',
          1 => 'contentobject_attribute_version',
          2 => 'enumid',
        ),
      ),
      'ezenumobjectvalue_co_attr_id_co_attr_ver489' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'contentobject_attribute_id',
          1 => 'contentobject_attribute_version',
        ),
      ),
    ),
  ),
  'ezenumvalue' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'contentclass_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclass_attribute_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'enumelement' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'enumvalue' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'placement' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezenumvalue497_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
          1 => 'contentclass_attribute_id',
          2 => 'contentclass_attribute_version',
        ),
      ),
      'ezenumvalue_co_cl_attr_id_co_class_att_ver505' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'contentclass_attribute_id',
          1 => 'contentclass_attribute_version',
        ),
      ),
    ),
  ),
  'ezforgot_password' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'hash_key' => 
      array (
        'length' => '32',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'time' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezforgot_password513_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezgeneral_digest_user_settings' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'address' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'receive_digest' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'digest_type' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'day' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'time' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezgeneral_digest_user_settings526_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezimage' => 
  array (
    'fields' => 
    array (
      'contentobject_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'filename' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'original_filename' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'mime_type' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'alternative_text' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezimage541_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'contentobject_attribute_id',
          1 => 'version',
        ),
      ),
    ),
  ),
  'ezimagefile' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'contentobject_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'filepath' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezimagefile_pkey' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezimagefile_file' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'filepath',
        ),
      ),
      'ezimagefile_coid' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'contentobject_attribute_id',
        ),
      ),
    ),
  ),
  'ezimagevariation' => 
  array (
    'fields' => 
    array (
      'contentobject_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'filename' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'additional_path' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'requested_width' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'requested_height' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'width' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'height' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezimagevariation556_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'contentobject_attribute_id',
          1 => 'version',
          2 => 'requested_width',
          3 => 'requested_height',
        ),
      ),
    ),
  ),
  'ezinfocollection' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'user_identifier' => 
      array (
        'length' => '34',
        'type' => 'varchar',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezinfocollection573_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezinfocollection_attribute' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'informationcollection_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_text' => 
      array (
        'type' => 'longtext',
      ),
      'data_int' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_float' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'contentclass_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentobject_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezinfocollection_attribute585_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezkeyword' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'keyword' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'class_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezkeyword600_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezkeyword_attribute_link' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'keyword_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'objectattribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezkeyword_attribute_link612_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezmedia' => 
  array (
    'fields' => 
    array (
      'contentobject_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'filename' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'original_filename' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'mime_type' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'width' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'height' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'has_controller' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'is_autoplay' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'pluginspage' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'quality' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'controls' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'is_loop' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezmedia624_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'contentobject_attribute_id',
          1 => 'version',
        ),
      ),
    ),
  ),
  'ezmessage' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'send_method' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'send_weekday' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'send_time' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'destination_address' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'title' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'body' => 
      array (
        'type' => 'longtext',
      ),
      'is_sent' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezmessage646_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezmodule_run' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'workflow_process_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'module_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'function_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'module_data' => 
      array (
        'type' => 'longtext',
      ),
    ),
    'indexes' => 
    array (
      'ezmodule_run663_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezmodule_run_workflow_process_id_s670' => 
      array (
        'type' => 'unique',
        'fields' => 
        array (
          0 => 'workflow_process_id',
        ),
      ),
    ),
  ),
  'eznode_assignment' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'contentobject_version' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'parent_node' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'sort_field' => 
      array (
        'length' => 11,
        'type' => 'int',
        'default' => '1',
      ),
      'sort_order' => 
      array (
        'length' => 11,
        'type' => 'int',
        'default' => '1',
      ),
      'is_main' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'from_node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'remote_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'eznode_assignment678_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'eznotificationcollection' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'event_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'handler' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'transport' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'data_subject' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_text' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'eznotificationcollection696_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'eznotificationcollection_item' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'collection_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'event_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'address' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'send_date' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'eznotificationcollection_item711_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'eznotificationevent' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'status' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'event_type_string' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'data_int1' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_int2' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_int3' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_int4' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_text1' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_text2' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_text3' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'data_text4' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'eznotificationevent725_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezoperation_memento' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'memento_key' => 
      array (
        'length' => '32',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'memento_data' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'main' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'main_key' => 
      array (
        'length' => '32',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezoperation_memento745_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
          1 => 'memento_key',
        ),
      ),
      'ezoperation_memento_memento_key_main' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'memento_key',
          1 => 'main',
        ),
      ),
    ),
  ),
  'ezorder' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'productcollection_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_temporary' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'order_nr' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'data_text_2' => 
      array (
        'type' => 'longtext',
      ),
      'data_text_1' => 
      array (
        'type' => 'longtext',
      ),
      'account_identifier' => 
      array (
        'length' => '100',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => 'default',
      ),
      'ignore_vat' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezorder759_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezorder_item' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'order_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'description' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'price' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'vat_value' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezorder_item778_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezorder_item_order_id' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'order_id',
        ),
      ),
    ),
  ),
  'ezpdf_export' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'title' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'show_frontpage' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'intro_text' => 
      array (
        'type' => 'longtext',
      ),
      'sub_text' => 
      array (
        'type' => 'longtext',
      ),
      'source_node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'export_structure' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'export_classes' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'site_access' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'pdf_filename' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'modifier_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'status' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezpdf_export_pkey' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezpolicy' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'role_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'function_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'module_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'limitation' => 
      array (
        'length' => '1',
        'type' => 'char',
      ),
    ),
    'indexes' => 
    array (
      'ezpolicy792_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezpolicy_limitation' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'policy_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'identifier' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'role_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'function_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'module_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezpolicy_limitation806_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezpolicy_limitation_value' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'limitation_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'value' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezpolicy_limitation_value821_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezpreferences' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'name' => 
      array (
        'length' => '100',
        'type' => 'varchar',
      ),
      'value' => 
      array (
        'length' => '100',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezpreferences833_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezpreferences_name839' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'name',
        ),
      ),
    ),
  ),
  'ezproductcollection' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezproductcollection847_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezproductcollection_item' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'productcollection_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'item_count' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'price' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'is_vat_inc' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'vat_value' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
      'discount' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
    ),
    'indexes' => 
    array (
      'ezproductcollection_item858_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezproductcollection_item_contentobject_id' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'productcollection_id',
        ),
      ),
      'ezproductcollection_item_productcollection_id' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'productcollection_id',
        ),
      ),
    ),
  ),
  'ezproductcollection_item_opt' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'item_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'option_item_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'value' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'price' => 
      array (
        'type' => 'float',
        'not_null' => '1',
        'default' => '0',
      ),
      'object_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezproductcollection_item_opt875_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezproductcollection_item_opt_item_id' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'item_id',
        ),
      ),
    ),
  ),
  'ezrole' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'value' => 
      array (
        'length' => '1',
        'type' => 'char',
      ),
    ),
    'indexes' => 
    array (
      'ezrole891_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezrss_export' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'title' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'modifier_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'url' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'description' => 
      array (
        'type' => 'longtext',
      ),
      'image_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'active' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'access_url' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'status' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'rss_version' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'site_access' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezrss_export_pkey' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezrss_export_item' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'rssexport_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'source_node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'class_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'title' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'description' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezrss_export_item_pkey' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezrss_export_rsseid' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'rssexport_id',
        ),
      ),
    ),
  ),
  'ezrss_import' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'url' => 
      array (
        'type' => 'longtext',
      ),
      'destination_node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'class_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'class_title' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'class_url' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'class_description' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'active' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'modifier_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'status' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'object_owner_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezrss_import_pkey' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezsearch_object_word_link' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'word_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'frequency' => 
      array (
        'type' => 'float',
        'not_null' => '1',
        'default' => '0',
      ),
      'placement' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'prev_word_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'next_word_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclass_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'published' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'section_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclass_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'identifier' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'integer_value' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezsearch_object_word_link_object919' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'contentobject_id',
        ),
      ),
      'ezsearch_object_word_link_word920' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'word_id',
        ),
      ),
      'ezsearch_object_word_link_frequency921' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'frequency',
        ),
      ),
      'ezsearch_object_word_link_identifier922' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'identifier',
        ),
      ),
      'ezsearch_object_word_link_integer_value923' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'integer_value',
        ),
      ),
      'ezsearch_object_word_link904_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezsearch_return_count' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'phrase_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'time' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'count' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezsearch_return_count931_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezsearch_search_phrase' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'phrase' => 
      array (
        'length' => '250',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezsearch_search_phrase944_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezsearch_word' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'word' => 
      array (
        'length' => '150',
        'type' => 'varchar',
      ),
      'object_count' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezsearch_word955_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezsearch_word960' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'word',
        ),
      ),
    ),
  ),
  'ezsection' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'locale' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'navigation_part_identifier' => 
      array (
        'length' => '100',
        'type' => 'varchar',
        'default' => 'ezcontentnavigationpart',
      ),
    ),
    'indexes' => 
    array (
      'ezsection968_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezsession' => 
  array (
    'fields' => 
    array (
      'session_key' => 
      array (
        'length' => '32',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'expiration_time' => 
      array (
        'type' => 'int',
        'not_null' => '1',
        'default' => '0::bigint',
      ),
      'data' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezsession981_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'session_key',
        ),
      ),
      'expiration_time986' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'expiration_time',
        ),
      ),
    ),
  ),
  'ezsite_data' => 
  array (
    'fields' => 
    array (
      'name' => 
      array (
        'length' => '60',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'value' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
        'default' => '\'\'::text',
      ),
    ),
    'indexes' => 
    array (
      'ezsite_data_pkey' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'name',
        ),
      ),
    ),
  ),
  'ezsubtree_notification_rule' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'address' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'use_digest' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezsubtree_notification_rule994_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'eztipafriend_counter' => 
  array (
    'fields' => 
    array (
      'node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'count' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'eztipafriend_counter_pkey' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'node_id',
        ),
      ),
    ),
  ),
  'eztrigger' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'module_name' => 
      array (
        'length' => '200',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'function_name' => 
      array (
        'length' => '200',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'connect_type' => 
      array (
        'length' => '1',
        'type' => 'char',
        'not_null' => '1',
        'default' => '\'\'::bpchar',
      ),
      'workflow_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'eztrigger1007_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'eztrigger_fetch' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'name',
          1 => 'module_name',
          2 => 'function_name',
        ),
      ),
      'eztrigger_def_id1015' => 
      array (
        'type' => 'unique',
        'fields' => 
        array (
          0 => 'module_name',
          1 => 'function_name',
          2 => 'connect_type',
        ),
      ),
    ),
  ),
  'ezurl' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'url' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_valid' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'last_checked' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'original_url_md5' => 
      array (
        'length' => '32',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezurl1023_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezurl_object_link' => 
  array (
    'fields' => 
    array (
      'url_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentobject_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentobject_attribute_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezurl_object_link1039_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'url_id',
          1 => 'contentobject_attribute_id',
          2 => 'contentobject_attribute_version',
        ),
      ),
    ),
  ),
  'ezurlalias' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'source_url' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'source_md5' => 
      array (
        'length' => '32',
        'type' => 'varchar',
      ),
      'destination_url' => 
      array (
        'type' => 'longtext',
        'not_null' => '1',
      ),
      'is_internal' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'forward_to_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_wildcard' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezurlalias_source_md51059' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'source_md5',
        ),
      ),
      'ezurlalias_source_url' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'source_url',
        ),
      ),
      'ezurlalias_desturl' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'destination_url',
        ),
      ),
      'ezurlalias1051_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezuser' => 
  array (
    'fields' => 
    array (
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'login' => 
      array (
        'length' => '150',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'email' => 
      array (
        'length' => '150',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'password_hash_type' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
        'default' => '1',
      ),
      'password_hash' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezuser1067_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'contentobject_id',
        ),
      ),
    ),
  ),
  'ezuser_accountkey' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'hash_key' => 
      array (
        'length' => '32',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'time' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezuser_accountkey1081_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezuser_discountrule' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'discountrule_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
    ),
    'indexes' => 
    array (
      'ezuser_discountrule1094_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezuser_role' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'role_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'contentobject_id' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezuser_role1107_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezuser_role_contentobject_id1112' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'contentobject_id',
        ),
      ),
    ),
  ),
  'ezuser_setting' => 
  array (
    'fields' => 
    array (
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_enabled' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'max_login' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
    ),
    'indexes' => 
    array (
      'ezuser_setting1120_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'user_id',
        ),
      ),
    ),
  ),
  'ezvattype' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'percentage' => 
      array (
        'type' => 'float',
        'default' => '0',
      ),
    ),
    'indexes' => 
    array (
      'ezvattype1132_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezview_counter' => 
  array (
    'fields' => 
    array (
      'node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'count' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezview_counter_pkey' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'node_id',
        ),
      ),
    ),
  ),
  'ezwaituntildatevalue' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'workflow_event_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'workflow_event_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclass_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'contentclass_attribute_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezwaituntildatevalue1144_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
          1 => 'workflow_event_id',
          2 => 'workflow_event_version',
        ),
      ),
      'ezwaituntildateevalue_wf_ev_id_wf_ver1151' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'workflow_event_id',
          1 => 'workflow_event_version',
        ),
      ),
    ),
  ),
  'ezwishlist' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'productcollection_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezwishlist1159_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezworkflow' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'is_enabled' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'workflow_type_string' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modifier_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezworkflow1171_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
          1 => 'version',
        ),
      ),
    ),
  ),
  'ezworkflow_assign' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'workflow_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'access_type' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'as_tree' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezworkflow_assign1189_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezworkflow_event' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'workflow_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'workflow_type_string' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'description' => 
      array (
        'length' => '50',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'data_int1' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_int2' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_int3' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_int4' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'data_text1' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'data_text2' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'data_text3' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'data_text4' => 
      array (
        'length' => '50',
        'type' => 'varchar',
      ),
      'placement' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezworkflow_event1203_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
          1 => 'version',
        ),
      ),
    ),
  ),
  'ezworkflow_group' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'creator_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modifier_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
    ),
    'indexes' => 
    array (
      'ezworkflow_group1226_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
    ),
  ),
  'ezworkflow_group_link' => 
  array (
    'fields' => 
    array (
      'workflow_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'group_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'workflow_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'group_name' => 
      array (
        'length' => '255',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezworkflow_group_link1241_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'workflow_id',
          1 => 'group_id',
          2 => 'workflow_version',
        ),
      ),
    ),
  ),
  'ezworkflow_process' => 
  array (
    'fields' => 
    array (
      'id' => 
      array (
        'type' => 'auto_increment',
      ),
      'process_key' => 
      array (
        'length' => '32',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '',
      ),
      'workflow_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'user_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'content_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'content_version' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'node_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'session_key' => 
      array (
        'length' => '32',
        'type' => 'varchar',
        'not_null' => '1',
        'default' => '0',
      ),
      'event_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'event_position' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'last_event_id' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'last_event_position' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'last_event_status' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'event_status' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'created' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'modified' => 
      array (
        'length' => 11,
        'type' => 'int',
        'not_null' => '1',
      ),
      'activation_date' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'event_state' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'status' => 
      array (
        'length' => 11,
        'type' => 'int',
      ),
      'parameters' => 
      array (
        'type' => 'longtext',
      ),
      'memento_key' => 
      array (
        'length' => '32',
        'type' => 'varchar',
      ),
    ),
    'indexes' => 
    array (
      'ezworkflow_process1254_key' => 
      array (
        'type' => 'primary',
        'fields' => 
        array (
          0 => 'id',
        ),
      ),
      'ezworkflow_process_process_key' => 
      array (
        'type' => 'non-unique',
        'fields' => 
        array (
          0 => 'process_key',
        ),
      ),
    ),
  ),
);
return $schema;
?>
