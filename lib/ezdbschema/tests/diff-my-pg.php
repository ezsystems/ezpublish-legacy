<?php 
$diff = 
array (
  'table_changes' => 
  array (
    'ezapprove_items' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezapprove_items12_key' => true,
      ),
    ),
    'ezbasket' => 
    array (
      'changed_fields' => 
      array (
        'session_id' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezbasket24_key' => true,
      ),
    ),
    'ezbinaryfile' => 
    array (
      'changed_fields' => 
      array (
        'filename' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'original_filename' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'mime_type' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'contentobject_attribute_id',
            1 => 'version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezbinaryfile36_key' => true,
      ),
    ),
    'ezcollab_group' => 
    array (
      'changed_fields' => 
      array (
        'path_string' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'title' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezcollab_group_path' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'path_string',
          ),
        ),
        'ezcollab_group_depth' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'depth',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcollab_group50_key' => true,
        'ezcollab_group_depth63' => true,
        'ezcollab_group_path62' => true,
      ),
    ),
    'ezcollab_item' => 
    array (
      'changed_fields' => 
      array (
        'type_identifier' => 
        array (
          'length' => '40',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'data_text1' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_text2' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_text3' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_float1' => 
        array (
          'type' => 'float',
          'not_null' => '1',
        ),
        'data_float2' => 
        array (
          'type' => 'float',
          'not_null' => '1',
        ),
        'data_float3' => 
        array (
          'type' => 'float',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcollab_item71_key' => true,
      ),
    ),
    'ezcollab_item_group_link' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
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
      'removed_indexes' => 
      array (
        'ezcollab_item_group_link95_key' => true,
      ),
    ),
    'ezcollab_item_message_link' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcollab_item_message_link112_key' => true,
      ),
    ),
    'ezcollab_item_participant_link' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'collaboration_id',
            1 => 'participant_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcollab_item_participant_link128_key' => true,
      ),
    ),
    'ezcollab_item_status' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'collaboration_id',
            1 => 'user_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcollab_item_status146_key' => true,
      ),
    ),
    'ezcollab_notification_rule' => 
    array (
      'changed_fields' => 
      array (
        'user_id' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'collab_identifier' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcollab_notification_rule160_key' => true,
      ),
    ),
    'ezcollab_profile' => 
    array (
      'changed_fields' => 
      array (
        'data_text1' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcollab_profile172_key' => true,
      ),
    ),
    'ezcollab_simple_message' => 
    array (
      'changed_fields' => 
      array (
        'message_type' => 
        array (
          'length' => '40',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'data_text1' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_text2' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_text3' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_float1' => 
        array (
          'type' => 'float',
          'not_null' => '1',
        ),
        'data_float2' => 
        array (
          'type' => 'float',
          'not_null' => '1',
        ),
        'data_float3' => 
        array (
          'type' => 'float',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcollab_simple_message187_key' => true,
      ),
    ),
    'ezcontent_translation' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'locale' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontent_translation210_key' => true,
      ),
    ),
    'ezcontentbrowsebookmark' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezcontentbrowsebookmark_user' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'user_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentbrowsebookmark222_key' => true,
        'ezcontentbrowsebookmark_user228' => true,
      ),
    ),
    'ezcontentbrowserecent' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezcontentbrowserecent_user' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'user_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentbrowserecent236_key' => true,
        'ezcontentbrowserecent_user243' => true,
      ),
    ),
    'ezcontentclass' => 
    array (
      'changed_fields' => 
      array (
        'identifier' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
            1 => 'version',
          ),
        ),
        'ezcontentclass_version' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentclass251_key' => true,
        'ezcontentclass_version262' => true,
      ),
    ),
    'ezcontentclass_attribute' => 
    array (
      'changed_fields' => 
      array (
        'identifier' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'data_type_string' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'is_searchable' => 
        array (
          'length' => '1',
          'type' => 'int',
          'not_null' => '1',
        ),
        'is_required' => 
        array (
          'length' => '1',
          'type' => 'int',
          'not_null' => '1',
        ),
        'data_float1' => 
        array (
          'type' => 'float',
        ),
        'data_float2' => 
        array (
          'type' => 'float',
        ),
        'data_float3' => 
        array (
          'type' => 'float',
        ),
        'data_float4' => 
        array (
          'type' => 'float',
        ),
        'data_text5' => 
        array (
          'type' => 'text',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
            1 => 'version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentclass_attribute270_key' => true,
      ),
    ),
    'ezcontentclass_classgroup' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
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
      'removed_indexes' => 
      array (
        'ezcontentclass_classgroup303_key' => true,
      ),
    ),
    'ezcontentclassgroup' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentclassgroup316_key' => true,
      ),
    ),
    'ezcontentobject' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentobject331_key' => true,
      ),
    ),
    'ezcontentobject_attribute' => 
    array (
      'changed_fields' => 
      array (
        'language_code' => 
        array (
          'length' => '20',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'data_text' => 
        array (
          'type' => 'text',
        ),
        'data_float' => 
        array (
          'type' => 'float',
        ),
        'sort_key_string' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'data_type_string' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
            1 => 'version',
          ),
        ),
        'ezcontentobject_attribute_contentobject_id' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'contentobject_id',
          ),
        ),
        'ezcontentobject_attribute_language_code' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'language_code',
          ),
        ),
        'sort_key_int' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'sort_key_int',
          ),
        ),
        'sort_key_string' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'sort_key_string',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentobject_attribute_contentobject_id364' => true,
        'ezcontentobject_attribute_language_code365' => true,
        'sort_key_int366' => true,
        'sort_key_string367' => true,
        'ezcontentobject_attribute351_key' => true,
      ),
    ),
    'ezcontentobject_link' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentobject_link375_key' => true,
      ),
    ),
    'ezcontentobject_name' => 
    array (
      'changed_fields' => 
      array (
        'content_translation' => 
        array (
          'length' => '20',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
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
      'removed_indexes' => 
      array (
        'ezcontentobject_name388_key' => true,
      ),
    ),
    'ezcontentobject_tree' => 
    array (
      'changed_fields' => 
      array (
        'path_string' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'sort_order' => 
        array (
          'length' => '1',
          'type' => 'int',
          'default' => '1',
        ),
        'path_identification_string' => 
        array (
          'type' => 'text',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'node_id',
          ),
        ),
        'ezcontentobject_tree_path' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'path_string',
          ),
        ),
        'ezcontentobject_tree_p_node_id' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'parent_node_id',
          ),
        ),
        'ezcontentobject_tree_co_id' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'contentobject_id',
          ),
        ),
        'ezcontentobject_tree_depth' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'depth',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentobject_tree_path416' => true,
        'ezcontentobject_tree_p_node_id417' => true,
        'ezcontentobject_tree_co_id418' => true,
        'ezcontentobject_tree_depth419' => true,
        'ezcontentobject_tree402_key' => true,
      ),
    ),
    'ezcontentobject_version' => 
    array (
      'changed_fields' => 
      array (
        'workflow_event_pos' => 
        array (
          'length' => '11',
          'type' => 'int',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezcontentobject_version427_key' => true,
      ),
    ),
    'ezdiscountrule' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezdiscountrule445_key' => true,
      ),
    ),
    'ezdiscountsubrule' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'discount_percent' => 
        array (
          'type' => 'float',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezdiscountsubrule456_key' => true,
      ),
    ),
    'ezdiscountsubrule_value' => 
    array (
      'changed_fields' => 
      array (
        'issection' => 
        array (
          'length' => '1',
          'type' => 'int',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
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
      'removed_indexes' => 
      array (
        'ezdiscountsubrule_value470_key' => true,
      ),
    ),
    'ezenumobjectvalue' => 
    array (
      'changed_fields' => 
      array (
        'enumelement' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'enumvalue' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'contentobject_attribute_id',
            1 => 'contentobject_attribute_version',
            2 => 'enumid',
          ),
        ),
        'ezenumobjectvalue_co_attr_id_co_attr_ver' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'contentobject_attribute_id',
            1 => 'contentobject_attribute_version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezenumobjectvalue482_key' => true,
        'ezenumobjectvalue_co_attr_id_co_attr_ver489' => true,
      ),
    ),
    'ezenumvalue' => 
    array (
      'changed_fields' => 
      array (
        'enumelement' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'enumvalue' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
            1 => 'contentclass_attribute_id',
            2 => 'contentclass_attribute_version',
          ),
        ),
        'ezenumvalue_co_cl_attr_id_co_class_att_ver' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'contentclass_attribute_id',
            1 => 'contentclass_attribute_version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezenumvalue497_key' => true,
        'ezenumvalue_co_cl_attr_id_co_class_att_ver505' => true,
      ),
    ),
    'ezforgot_password' => 
    array (
      'changed_fields' => 
      array (
        'hash_key' => 
        array (
          'length' => '32',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezforgot_password513_key' => true,
      ),
    ),
    'ezgeneral_digest_user_settings' => 
    array (
      'changed_fields' => 
      array (
        'address' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'day' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'time' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezgeneral_digest_user_settings526_key' => true,
      ),
    ),
    'ezimage' => 
    array (
      'changed_fields' => 
      array (
        'filename' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'original_filename' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'mime_type' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'alternative_text' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'contentobject_attribute_id',
            1 => 'version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezimage541_key' => true,
      ),
    ),
    'ezimagefile' => 
    array (
      'changed_fields' => 
      array (
        'filepath' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezimagefile_pkey' => true,
      ),
    ),
    'ezimagevariation' => 
    array (
      'changed_fields' => 
      array (
        'filename' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
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
      'removed_indexes' => 
      array (
        'ezimagevariation556_key' => true,
      ),
    ),
    'ezinfocollection' => 
    array (
      'changed_fields' => 
      array (
        'modified' => 
        array (
          'length' => '11',
          'type' => 'int',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezinfocollection573_key' => true,
      ),
    ),
    'ezinfocollection_attribute' => 
    array (
      'changed_fields' => 
      array (
        'data_text' => 
        array (
          'type' => 'text',
        ),
        'data_float' => 
        array (
          'type' => 'float',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezinfocollection_attribute585_key' => true,
      ),
    ),
    'ezkeyword' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezkeyword600_key' => true,
      ),
    ),
    'ezkeyword_attribute_link' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezkeyword_attribute_link612_key' => true,
      ),
    ),
    'ezmedia' => 
    array (
      'changed_fields' => 
      array (
        'filename' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'original_filename' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'mime_type' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'has_controller' => 
        array (
          'length' => '1',
          'type' => 'int',
        ),
        'is_autoplay' => 
        array (
          'length' => '1',
          'type' => 'int',
        ),
        'is_loop' => 
        array (
          'length' => '1',
          'type' => 'int',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'contentobject_attribute_id',
            1 => 'version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezmedia624_key' => true,
      ),
    ),
    'ezmessage' => 
    array (
      'changed_fields' => 
      array (
        'send_method' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'send_weekday' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'send_time' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'destination_address' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'title' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'body' => 
        array (
          'type' => 'text',
        ),
        'is_sent' => 
        array (
          'length' => '1',
          'type' => 'int',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezmessage646_key' => true,
      ),
    ),
    'ezmodule_run' => 
    array (
      'changed_fields' => 
      array (
        'module_data' => 
        array (
          'type' => 'text',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezmodule_run_workflow_process_id_s' => 
        array (
          'type' => 'unique',
          'fields' => 
          array (
            0 => 'workflow_process_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezmodule_run663_key' => true,
        'ezmodule_run_workflow_process_id_s670' => true,
      ),
    ),
    'eznode_assignment' => 
    array (
      'changed_fields' => 
      array (
        'sort_order' => 
        array (
          'length' => '1',
          'type' => 'int',
          'default' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'eznode_assignment678_key' => true,
      ),
    ),
    'eznotificationcollection' => 
    array (
      'changed_fields' => 
      array (
        'handler' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'transport' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'data_subject' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_text' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'eznotificationcollection696_key' => true,
      ),
    ),
    'eznotificationcollection_item' => 
    array (
      'changed_fields' => 
      array (
        'address' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'eznotificationcollection_item711_key' => true,
      ),
    ),
    'eznotificationevent' => 
    array (
      'changed_fields' => 
      array (
        'event_type_string' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'data_text1' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_text2' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_text3' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'data_text4' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'eznotificationevent725_key' => true,
      ),
    ),
    'ezoperation_memento' => 
    array (
      'changed_fields' => 
      array (
        'memento_key' => 
        array (
          'length' => '32',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'memento_data' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'main_key' => 
        array (
          'length' => '32',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
            1 => 'memento_key',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezoperation_memento745_key' => true,
      ),
    ),
    'ezorder' => 
    array (
      'changed_fields' => 
      array (
        'data_text_2' => 
        array (
          'type' => 'text',
        ),
        'data_text_1' => 
        array (
          'type' => 'text',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezorder759_key' => true,
      ),
    ),
    'ezorder_item' => 
    array (
      'changed_fields' => 
      array (
        'price' => 
        array (
          'type' => 'float',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezorder_item778_key' => true,
      ),
    ),
    'ezpdf_export' => 
    array (
      'changed_fields' => 
      array (
        'intro_text' => 
        array (
          'type' => 'text',
        ),
        'sub_text' => 
        array (
          'type' => 'text',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezpdf_export_pkey' => true,
      ),
    ),
    'ezpolicy' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezpolicy792_key' => true,
      ),
    ),
    'ezpolicy_limitation' => 
    array (
      'changed_fields' => 
      array (
        'identifier' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezpolicy_limitation806_key' => true,
      ),
    ),
    'ezpolicy_limitation_value' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezpolicy_limitation_value821_key' => true,
      ),
    ),
    'ezpreferences' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezpreferences_name' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'name',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezpreferences833_key' => true,
        'ezpreferences_name839' => true,
      ),
    ),
    'ezproductcollection' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezproductcollection847_key' => true,
      ),
    ),
    'ezproductcollection_item' => 
    array (
      'changed_fields' => 
      array (
        'vat_value' => 
        array (
          'type' => 'float',
        ),
        'discount' => 
        array (
          'type' => 'float',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezproductcollection_item858_key' => true,
      ),
    ),
    'ezproductcollection_item_opt' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'value' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'price' => 
        array (
          'type' => 'float',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezproductcollection_item_opt875_key' => true,
      ),
    ),
    'ezrole' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezrole891_key' => true,
      ),
    ),
    'ezrss_export' => 
    array (
      'changed_fields' => 
      array (
        'description' => 
        array (
          'type' => 'text',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezrss_export_pkey' => true,
      ),
    ),
    'ezrss_export_item' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezrss_export_item_pkey' => true,
      ),
    ),
    'ezrss_import' => 
    array (
      'changed_fields' => 
      array (
        'url' => 
        array (
          'type' => 'text',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezrss_import_pkey' => true,
      ),
    ),
    'ezsearch_object_word_link' => 
    array (
      'changed_fields' => 
      array (
        'frequency' => 
        array (
          'type' => 'float',
          'not_null' => '1',
        ),
        'identifier' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezsearch_object_word_link_object' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'contentobject_id',
          ),
        ),
        'ezsearch_object_word_link_word' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'word_id',
          ),
        ),
        'ezsearch_object_word_link_frequency' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'frequency',
          ),
        ),
        'ezsearch_object_word_link_identifier' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'identifier',
          ),
        ),
        'ezsearch_object_word_link_integer_value' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'integer_value',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezsearch_object_word_link_object919' => true,
        'ezsearch_object_word_link_word920' => true,
        'ezsearch_object_word_link_frequency921' => true,
        'ezsearch_object_word_link_identifier922' => true,
        'ezsearch_object_word_link_integer_value923' => true,
        'ezsearch_object_word_link904_key' => true,
      ),
    ),
    'ezsearch_return_count' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezsearch_return_count931_key' => true,
      ),
    ),
    'ezsearch_search_phrase' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezsearch_search_phrase944_key' => true,
      ),
    ),
    'ezsearch_word' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezsearch_word' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'word',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezsearch_word955_key' => true,
        'ezsearch_word960' => true,
      ),
    ),
    'ezsection' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezsection968_key' => true,
      ),
    ),
    'ezsession' => 
    array (
      'changed_fields' => 
      array (
        'session_key' => 
        array (
          'length' => '32',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'expiration_time' => 
        array (
          'length' => '11',
          'type' => 'int',
          'not_null' => '1',
        ),
        'data' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'session_key',
          ),
        ),
        'expiration_time' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'expiration_time',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezsession981_key' => true,
        'expiration_time986' => true,
      ),
    ),
    'ezsite_data' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '60',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'value' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'name',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezsite_data_pkey' => true,
      ),
    ),
    'ezsubtree_notification_rule' => 
    array (
      'changed_fields' => 
      array (
        'address' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezsubtree_notification_rule994_key' => true,
      ),
    ),
    'eztipafriend_counter' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'node_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'eztipafriend_counter_pkey' => true,
      ),
    ),
    'eztrigger' => 
    array (
      'changed_fields' => 
      array (
        'module_name' => 
        array (
          'length' => '200',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'function_name' => 
        array (
          'length' => '200',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'connect_type' => 
        array (
          'length' => '1',
          'type' => 'char',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'eztrigger_def_id' => 
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
      'removed_indexes' => 
      array (
        'eztrigger1007_key' => true,
        'eztrigger_def_id1015' => true,
      ),
    ),
    'ezurl' => 
    array (
      'changed_fields' => 
      array (
        'original_url_md5' => 
        array (
          'length' => '32',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezurl1023_key' => true,
      ),
    ),
    'ezurl_object_link' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
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
      'removed_indexes' => 
      array (
        'ezurl_object_link1039_key' => true,
      ),
    ),
    'ezurlalias' => 
    array (
      'changed_fields' => 
      array (
        'source_url' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
        'destination_url' => 
        array (
          'type' => 'text',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezurlalias_source_md5' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'source_md5',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezurlalias_source_md51059' => true,
        'ezurlalias1051_key' => true,
      ),
    ),
    'ezuser' => 
    array (
      'changed_fields' => 
      array (
        'login' => 
        array (
          'length' => '150',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'email' => 
        array (
          'length' => '150',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'contentobject_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezuser1067_key' => true,
      ),
    ),
    'ezuser_accountkey' => 
    array (
      'changed_fields' => 
      array (
        'hash_key' => 
        array (
          'length' => '32',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezuser_accountkey1081_key' => true,
      ),
    ),
    'ezuser_discountrule' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezuser_discountrule1094_key' => true,
      ),
    ),
    'ezuser_role' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
        'ezuser_role_contentobject_id' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'contentobject_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezuser_role1107_key' => true,
        'ezuser_role_contentobject_id1112' => true,
      ),
    ),
    'ezuser_setting' => 
    array (
      'changed_fields' => 
      array (
        'is_enabled' => 
        array (
          'length' => '1',
          'type' => 'int',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'user_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezuser_setting1120_key' => true,
      ),
    ),
    'ezvattype' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'percentage' => 
        array (
          'type' => 'float',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezvattype1132_key' => true,
      ),
    ),
    'ezview_counter' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'node_id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezview_counter_pkey' => true,
      ),
    ),
    'ezwaituntildatevalue' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
            1 => 'workflow_event_id',
            2 => 'workflow_event_version',
          ),
        ),
        'ezwaituntildateevalue_wf_ev_id_wf_ver' => 
        array (
          'type' => 'non-unique',
          'fields' => 
          array (
            0 => 'workflow_event_id',
            1 => 'workflow_event_version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezwaituntildatevalue1144_key' => true,
        'ezwaituntildateevalue_wf_ev_id_wf_ver1151' => true,
      ),
    ),
    'ezwishlist' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezwishlist1159_key' => true,
      ),
    ),
    'ezworkflow' => 
    array (
      'changed_fields' => 
      array (
        'is_enabled' => 
        array (
          'length' => '1',
          'type' => 'int',
          'not_null' => '1',
        ),
        'workflow_type_string' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
            1 => 'version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezworkflow1171_key' => true,
      ),
    ),
    'ezworkflow_assign' => 
    array (
      'changed_fields' => 
      array (
        'as_tree' => 
        array (
          'length' => '1',
          'type' => 'int',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezworkflow_assign1189_key' => true,
      ),
    ),
    'ezworkflow_event' => 
    array (
      'changed_fields' => 
      array (
        'workflow_type_string' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'description' => 
        array (
          'length' => '50',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
            1 => 'version',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezworkflow_event1203_key' => true,
      ),
    ),
    'ezworkflow_group' => 
    array (
      'changed_fields' => 
      array (
        'name' => 
        array (
          'length' => '255',
          'type' => 'varchar',
          'not_null' => '1',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezworkflow_group1226_key' => true,
      ),
    ),
    'ezworkflow_group_link' => 
    array (
      'added_indexes' => 
      array (
        'PRIMARY' => 
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
      'removed_indexes' => 
      array (
        'ezworkflow_group_link1241_key' => true,
      ),
    ),
    'ezworkflow_process' => 
    array (
      'changed_fields' => 
      array (
        'process_key' => 
        array (
          'length' => '32',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'session_key' => 
        array (
          'length' => '32',
          'type' => 'varchar',
          'not_null' => '1',
        ),
        'parameters' => 
        array (
          'type' => 'text',
        ),
      ),
      'added_indexes' => 
      array (
        'PRIMARY' => 
        array (
          'type' => 'primary',
          'fields' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'removed_indexes' => 
      array (
        'ezworkflow_process1254_key' => true,
      ),
    ),
  ),
);
return $diff;
?>
