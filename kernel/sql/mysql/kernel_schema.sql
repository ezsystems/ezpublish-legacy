CREATE TABLE ezapprove_items (
  collaboration_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezbasket (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  session_id varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezbasket_session_id (session_id)
) ENGINE=InnoDB;





CREATE TABLE ezbinaryfile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  download_count int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  mime_type varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (contentobject_attribute_id,version)
) ENGINE=InnoDB;





CREATE TABLE ezcobj_state (
  default_language_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  identifier varchar(45) NOT NULL default '',
  language_mask int(11) NOT NULL default '0',
  priority int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY ezcobj_state_identifier (group_id,identifier),
  KEY ezcobj_state_lmask (language_mask),
  KEY ezcobj_state_priority (priority)
) ENGINE=InnoDB;





CREATE TABLE ezcobj_state_group (
  default_language_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  identifier varchar(45) NOT NULL default '',
  language_mask int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY ezcobj_state_group_identifier (identifier),
  KEY ezcobj_state_group_lmask (language_mask)
) ENGINE=InnoDB;





CREATE TABLE ezcobj_state_group_language (
  contentobject_state_group_id int(11) NOT NULL default '0',
  description longtext NOT NULL,
  language_id int(11) NOT NULL default '0',
  name varchar(45) NOT NULL default '',
  PRIMARY KEY  (contentobject_state_group_id,language_id)
) ENGINE=InnoDB;





CREATE TABLE ezcobj_state_language (
  contentobject_state_id int(11) NOT NULL default '0',
  description longtext NOT NULL,
  language_id int(11) NOT NULL default '0',
  name varchar(45) NOT NULL default '',
  PRIMARY KEY  (contentobject_state_id,language_id)
) ENGINE=InnoDB;





CREATE TABLE ezcobj_state_link (
  contentobject_id int(11) NOT NULL default '0',
  contentobject_state_id int(11) NOT NULL default '0',
  PRIMARY KEY  (contentobject_id,contentobject_state_id)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_group (
  created int(11) NOT NULL default '0',
  depth int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  is_open int(11) NOT NULL default '1',
  modified int(11) NOT NULL default '0',
  parent_group_id int(11) NOT NULL default '0',
  path_string varchar(255) NOT NULL default '',
  priority int(11) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezcollab_group_depth (depth),
  KEY ezcollab_group_path (path_string)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_item (
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  data_float1 float NOT NULL default '0',
  data_float2 float NOT NULL default '0',
  data_float3 float NOT NULL default '0',
  data_int1 int(11) NOT NULL default '0',
  data_int2 int(11) NOT NULL default '0',
  data_int3 int(11) NOT NULL default '0',
  data_text1 longtext NOT NULL,
  data_text2 longtext NOT NULL,
  data_text3 longtext NOT NULL,
  id int(11) NOT NULL auto_increment,
  modified int(11) NOT NULL default '0',
  status int(11) NOT NULL default '1',
  type_identifier varchar(40) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_item_group_link (
  collaboration_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  is_read int(11) NOT NULL default '0',
  last_read int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (collaboration_id,group_id,user_id)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_item_message_link (
  collaboration_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  message_id int(11) NOT NULL default '0',
  message_type int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  participant_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_item_participant_link (
  collaboration_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  is_read int(11) NOT NULL default '0',
  last_read int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  participant_id int(11) NOT NULL default '0',
  participant_role int(11) NOT NULL default '1',
  participant_type int(11) NOT NULL default '1',
  PRIMARY KEY  (collaboration_id,participant_id)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_item_status (
  collaboration_id int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  is_read int(11) NOT NULL default '0',
  last_read int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (collaboration_id,user_id)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_notification_rule (
  collab_identifier varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  user_id varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_profile (
  created int(11) NOT NULL default '0',
  data_text1 longtext NOT NULL,
  id int(11) NOT NULL auto_increment,
  main_group int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezcollab_simple_message (
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  data_float1 float NOT NULL default '0',
  data_float2 float NOT NULL default '0',
  data_float3 float NOT NULL default '0',
  data_int1 int(11) NOT NULL default '0',
  data_int2 int(11) NOT NULL default '0',
  data_int3 int(11) NOT NULL default '0',
  data_text1 longtext NOT NULL,
  data_text2 longtext NOT NULL,
  data_text3 longtext NOT NULL,
  id int(11) NOT NULL auto_increment,
  message_type varchar(40) NOT NULL default '',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezcontent_language (
  disabled int(11) NOT NULL default '0',
  id int(11) NOT NULL default '0',
  locale varchar(20) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezcontent_language_name (name)
) ENGINE=InnoDB;





CREATE TABLE ezcontentbrowsebookmark (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  node_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezcontentbrowsebookmark_user (user_id)
) ENGINE=InnoDB;





CREATE TABLE ezcontentbrowserecent (
  created int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  node_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezcontentbrowserecent_user (user_id)
) ENGINE=InnoDB;





CREATE TABLE ezcontentclass (
  always_available int(11) NOT NULL default '0',
  contentobject_name varchar(255) default NULL,
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  identifier varchar(50) NOT NULL default '',
  initial_language_id int(11) NOT NULL default '0',
  is_container int(11) NOT NULL default '0',
  language_mask int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  remote_id varchar(100) NOT NULL default '',
  serialized_name_list longtext,
  sort_field int(11) NOT NULL default '1',
  sort_order int(11) NOT NULL default '1',
  url_alias_name varchar(255) default NULL,
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version),
  KEY ezcontentclass_version (version)
) ENGINE=InnoDB;





CREATE TABLE ezcontentclass_attribute (
  can_translate int(11) default '1',
  contentclass_id int(11) NOT NULL default '0',
  data_float1 float default NULL,
  data_float2 float default NULL,
  data_float3 float default NULL,
  data_float4 float default NULL,
  data_int1 int(11) default NULL,
  data_int2 int(11) default NULL,
  data_int3 int(11) default NULL,
  data_int4 int(11) default NULL,
  data_text1 varchar(50) default NULL,
  data_text2 varchar(50) default NULL,
  data_text3 varchar(50) default NULL,
  data_text4 varchar(255) default NULL,
  data_text5 longtext,
  data_type_string varchar(50) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  identifier varchar(50) NOT NULL default '',
  is_information_collector int(11) NOT NULL default '0',
  is_required int(11) NOT NULL default '0',
  is_searchable int(11) NOT NULL default '0',
  placement int(11) NOT NULL default '0',
  serialized_name_list longtext NOT NULL,
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version),
  KEY ezcontentclass_attr_ccid (contentclass_id)
) ENGINE=InnoDB;





CREATE TABLE ezcontentclass_classgroup (
  contentclass_id int(11) NOT NULL default '0',
  contentclass_version int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (contentclass_id,contentclass_version,group_id)
) ENGINE=InnoDB;





CREATE TABLE ezcontentclass_name (
  contentclass_id int(11) NOT NULL default '0',
  contentclass_version int(11) NOT NULL default '0',
  language_id int(11) NOT NULL default '0',
  language_locale varchar(20) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentclass_id,contentclass_version,language_id)
) ENGINE=InnoDB;





CREATE TABLE ezcontentclassgroup (
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  modified int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezcontentobject (
  contentclass_id int(11) NOT NULL default '0',
  current_version int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  initial_language_id int(11) NOT NULL default '0',
  is_published int(11) default NULL,
  language_mask int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  owner_id int(11) NOT NULL default '0',
  published int(11) NOT NULL default '0',
  remote_id varchar(100) default NULL,
  section_id int(11) NOT NULL default '0',
  status int(11) default '0',
  PRIMARY KEY  (id),
  KEY ezcontentobject_classid (contentclass_id),
  KEY ezcontentobject_currentversion (current_version),
  KEY ezcontentobject_lmask (language_mask),
  KEY ezcontentobject_owner (owner_id),
  KEY ezcontentobject_pub (published),
  UNIQUE KEY ezcontentobject_remote_id (remote_id),
  KEY ezcontentobject_status (status)
) ENGINE=InnoDB;





CREATE TABLE ezcontentobject_attribute (
  attribute_original_id int(11) default '0',
  contentclassattribute_id int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  data_float float default NULL,
  data_int int(11) default NULL,
  data_text longtext,
  data_type_string varchar(50) default '',
  id int(11) NOT NULL auto_increment,
  language_code varchar(20) NOT NULL default '',
  language_id int(11) NOT NULL default '0',
  sort_key_int int(11) NOT NULL default '0',
  sort_key_string varchar(255) NOT NULL default '',
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version),
  KEY ezcontentobject_attr_id (id),
  KEY ezcontentobject_attribute_co_id_ver_lang_code (contentobject_id,version,language_code),
  KEY ezcontentobject_attribute_contentobject_id (contentobject_id),
  KEY ezcontentobject_attribute_language_code (language_code),
  KEY sort_key_int (sort_key_int),
  KEY sort_key_string (sort_key_string)
) ENGINE=InnoDB;





CREATE TABLE ezcontentobject_link (
  contentclassattribute_id int(11) NOT NULL default '0',
  from_contentobject_id int(11) NOT NULL default '0',
  from_contentobject_version int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  op_code int(11) NOT NULL default '0',
  relation_type int(11) NOT NULL default '1',
  to_contentobject_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezco_link_from (from_contentobject_id,from_contentobject_version,contentclassattribute_id),
  KEY ezco_link_to_co_id (to_contentobject_id)
) ENGINE=InnoDB;





CREATE TABLE ezcontentobject_name (
  content_translation varchar(20) NOT NULL default '',
  content_version int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  language_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  real_translation varchar(20) default NULL,
  PRIMARY KEY  (contentobject_id,content_version,content_translation),
  KEY ezcontentobject_name_co_id (contentobject_id),
  KEY ezcontentobject_name_cov_id (content_version),
  KEY ezcontentobject_name_lang_id (language_id),
  KEY ezcontentobject_name_name (name)
) ENGINE=InnoDB;





CREATE TABLE ezcontentobject_trash (
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  depth int(11) NOT NULL default '0',
  is_hidden int(11) NOT NULL default '0',
  is_invisible int(11) NOT NULL default '0',
  main_node_id int(11) default NULL,
  modified_subnode int(11) default '0',
  node_id int(11) NOT NULL default '0',
  parent_node_id int(11) NOT NULL default '0',
  path_identification_string longtext,
  path_string varchar(255) NOT NULL default '',
  priority int(11) NOT NULL default '0',
  remote_id varchar(100) NOT NULL default '',
  sort_field int(11) default '1',
  sort_order int(11) default '1',
  PRIMARY KEY  (node_id),
  KEY ezcobj_trash_co_id (contentobject_id),
  KEY ezcobj_trash_depth (depth),
  KEY ezcobj_trash_modified_subnode (modified_subnode),
  KEY ezcobj_trash_p_node_id (parent_node_id),
  KEY ezcobj_trash_path (path_string),
  KEY ezcobj_trash_path_ident (path_identification_string(50))
) ENGINE=InnoDB;





CREATE TABLE ezcontentobject_tree (
  contentobject_id int(11) default NULL,
  contentobject_is_published int(11) default NULL,
  contentobject_version int(11) default NULL,
  depth int(11) NOT NULL default '0',
  is_hidden int(11) NOT NULL default '0',
  is_invisible int(11) NOT NULL default '0',
  main_node_id int(11) default NULL,
  modified_subnode int(11) default '0',
  node_id int(11) NOT NULL auto_increment,
  parent_node_id int(11) NOT NULL default '0',
  path_identification_string longtext,
  path_string varchar(255) NOT NULL default '',
  priority int(11) NOT NULL default '0',
  remote_id varchar(100) NOT NULL default '',
  sort_field int(11) default '1',
  sort_order int(11) default '1',
  PRIMARY KEY  (node_id),
  KEY ezcontentobject_tree_co_id (contentobject_id),
  KEY ezcontentobject_tree_depth (depth),
  KEY ezcontentobject_tree_p_node_id (parent_node_id),
  KEY ezcontentobject_tree_path (path_string),
  KEY ezcontentobject_tree_path_ident (path_identification_string(50)),
  KEY modified_subnode (modified_subnode)
) ENGINE=InnoDB;





CREATE TABLE ezcontentobject_version (
  contentobject_id int(11) default NULL,
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  initial_language_id int(11) NOT NULL default '0',
  language_mask int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  status int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  workflow_event_pos int(11) default '0',
  PRIMARY KEY  (id),
  KEY ezcobj_version_creator_id (creator_id),
  KEY ezcobj_version_status (status),
  KEY idx_object_version_objver (contentobject_id,version)
) ENGINE=InnoDB;





CREATE TABLE ezcurrencydata (
  auto_rate_value decimal(10,5) NOT NULL default '0.00000',
  code varchar(4) NOT NULL default '',
  custom_rate_value decimal(10,5) NOT NULL default '0.00000',
  id int(11) NOT NULL auto_increment,
  locale varchar(255) NOT NULL default '',
  rate_factor decimal(10,5) NOT NULL default '1.00000',
  status int(11) NOT NULL default '1',
  symbol varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezcurrencydata_code (code)
) ENGINE=InnoDB;





CREATE TABLE ezdiscountrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezdiscountsubrule (
  discount_percent float default NULL,
  discountrule_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  limitation char(1) default NULL,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezdiscountsubrule_value (
  discountsubrule_id int(11) NOT NULL default '0',
  issection int(11) NOT NULL default '0',
  value int(11) NOT NULL default '0',
  PRIMARY KEY  (discountsubrule_id,value,issection)
) ENGINE=InnoDB;





CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumid int(11) NOT NULL default '0',
  enumvalue varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid),
  KEY ezenumobjectvalue_co_attr_id_co_attr_ver (contentobject_attribute_id,contentobject_attribute_version)
) ENGINE=InnoDB;





CREATE TABLE ezenumvalue (
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,contentclass_attribute_id,contentclass_attribute_version),
  KEY ezenumvalue_co_cl_attr_id_co_class_att_ver (contentclass_attribute_id,contentclass_attribute_version)
) ENGINE=InnoDB;





CREATE TABLE ezforgot_password (
  hash_key varchar(32) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  time int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezforgot_password_user (user_id)
) ENGINE=InnoDB;





CREATE TABLE ezgeneral_digest_user_settings (
  address varchar(255) NOT NULL default '',
  day varchar(255) NOT NULL default '',
  digest_type int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  receive_digest int(11) NOT NULL default '0',
  time varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY ezgeneral_digest_user_settings_address (address)
) ENGINE=InnoDB;





CREATE TABLE ezimagefile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  filepath longtext NOT NULL,
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id),
  KEY ezimagefile_coid (contentobject_attribute_id),
  KEY ezimagefile_file (filepath(200))
) ENGINE=InnoDB;





CREATE TABLE ezinfocollection (
  contentobject_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  modified int(11) default '0',
  user_identifier varchar(34) default NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezinfocollection_attribute (
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  data_float float default NULL,
  data_int int(11) default NULL,
  data_text longtext,
  id int(11) NOT NULL auto_increment,
  informationcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezinfocollection_attr_co_id (contentobject_id)
) ENGINE=InnoDB;





CREATE TABLE ezisbn_group (
  description varchar(255) NOT NULL default '',
  group_number int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezisbn_group_range (
  from_number int(11) NOT NULL default '0',
  group_from varchar(32) NOT NULL default '',
  group_length int(11) NOT NULL default '0',
  group_to varchar(32) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  to_number int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezisbn_registrant_range (
  from_number int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  isbn_group_id int(11) NOT NULL default '0',
  registrant_from varchar(32) NOT NULL default '',
  registrant_length int(11) NOT NULL default '0',
  registrant_to varchar(32) NOT NULL default '',
  to_number int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezkeyword (
  class_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  keyword varchar(255) default NULL,
  PRIMARY KEY  (id),
  KEY ezkeyword_keyword (keyword),
  KEY ezkeyword_keyword_id (keyword,id)
) ENGINE=InnoDB;





CREATE TABLE ezkeyword_attribute_link (
  id int(11) NOT NULL auto_increment,
  keyword_id int(11) NOT NULL default '0',
  objectattribute_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezkeyword_attr_link_keyword_id (keyword_id),
  KEY ezkeyword_attr_link_kid_oaid (keyword_id,objectattribute_id)
) ENGINE=InnoDB;





CREATE TABLE ezmedia (
  contentobject_attribute_id int(11) NOT NULL default '0',
  controls varchar(50) default NULL,
  filename varchar(255) NOT NULL default '',
  has_controller int(11) default '0',
  height int(11) default NULL,
  is_autoplay int(11) default '0',
  is_loop int(11) default '0',
  mime_type varchar(50) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  pluginspage varchar(255) default NULL,
  quality varchar(50) default NULL,
  version int(11) NOT NULL default '0',
  width int(11) default NULL,
  PRIMARY KEY  (contentobject_attribute_id,version)
) ENGINE=InnoDB;





CREATE TABLE ezmessage (
  body longtext,
  destination_address varchar(50) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  is_sent int(11) NOT NULL default '0',
  send_method varchar(50) NOT NULL default '',
  send_time varchar(50) NOT NULL default '',
  send_weekday varchar(50) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezmodule_run (
  function_name varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  module_data longtext,
  module_name varchar(255) default NULL,
  workflow_process_id int(11) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY ezmodule_run_workflow_process_id_s (workflow_process_id)
) ENGINE=InnoDB;





CREATE TABLE ezmultipricedata (
  contentobject_attr_id int(11) NOT NULL default '0',
  contentobject_attr_version int(11) NOT NULL default '0',
  currency_code varchar(4) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  type int(11) NOT NULL default '0',
  value decimal(15,2) NOT NULL default '0.00',
  PRIMARY KEY  (id),
  KEY ezmultipricedata_coa_id (contentobject_attr_id),
  KEY ezmultipricedata_coa_version (contentobject_attr_version),
  KEY ezmultipricedata_currency_code (currency_code)
) ENGINE=InnoDB;





CREATE TABLE eznode_assignment (
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  from_node_id int(11) default '0',
  id int(11) NOT NULL auto_increment,
  is_main int(11) NOT NULL default '0',
  op_code int(11) NOT NULL default '0',
  parent_node int(11) default NULL,
  parent_remote_id varchar(100) NOT NULL default '',
  remote_id int(11) NOT NULL default '0',
  sort_field int(11) default '1',
  sort_order int(11) default '1',
  PRIMARY KEY  (id),
  KEY eznode_assignment_co_id (contentobject_id),
  KEY eznode_assignment_co_version (contentobject_version),
  KEY eznode_assignment_coid_cov (contentobject_id,contentobject_version),
  KEY eznode_assignment_is_main (is_main),
  KEY eznode_assignment_parent_node (parent_node)
) ENGINE=InnoDB;





CREATE TABLE eznotificationcollection (
  data_subject longtext NOT NULL,
  data_text longtext NOT NULL,
  event_id int(11) NOT NULL default '0',
  handler varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  transport varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE eznotificationcollection_item (
  address varchar(255) NOT NULL default '',
  collection_id int(11) NOT NULL default '0',
  event_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  send_date int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE eznotificationevent (
  data_int1 int(11) NOT NULL default '0',
  data_int2 int(11) NOT NULL default '0',
  data_int3 int(11) NOT NULL default '0',
  data_int4 int(11) NOT NULL default '0',
  data_text1 longtext NOT NULL,
  data_text2 longtext NOT NULL,
  data_text3 longtext NOT NULL,
  data_text4 longtext NOT NULL,
  event_type_string varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  status int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezoperation_memento (
  id int(11) NOT NULL auto_increment,
  main int(11) NOT NULL default '0',
  main_key varchar(32) NOT NULL default '',
  memento_data longtext NOT NULL,
  memento_key varchar(32) NOT NULL default '',
  PRIMARY KEY  (id,memento_key),
  KEY ezoperation_memento_memento_key_main (memento_key,main)
) ENGINE=InnoDB;





CREATE TABLE ezorder (
  account_identifier varchar(100) NOT NULL default 'default',
  created int(11) NOT NULL default '0',
  data_text_1 longtext,
  data_text_2 longtext,
  email varchar(150) default '',
  id int(11) NOT NULL auto_increment,
  ignore_vat int(11) NOT NULL default '0',
  is_archived int(11) NOT NULL default '0',
  is_temporary int(11) NOT NULL default '1',
  order_nr int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  status_id int(11) default '0',
  status_modified int(11) default '0',
  status_modifier_id int(11) default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezorder_is_archived (is_archived),
  KEY ezorder_is_tmp (is_temporary)
) ENGINE=InnoDB;





CREATE TABLE ezorder_item (
  description varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  is_vat_inc int(11) NOT NULL default '0',
  order_id int(11) NOT NULL default '0',
  price float default NULL,
  type varchar(30) default NULL,
  vat_value float NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezorder_item_order_id (order_id),
  KEY ezorder_item_type (type)
) ENGINE=InnoDB;





CREATE TABLE ezorder_status (
  id int(11) NOT NULL auto_increment,
  is_active int(11) NOT NULL default '1',
  name varchar(255) NOT NULL default '',
  status_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezorder_status_active (is_active),
  KEY ezorder_status_name (name),
  KEY ezorder_status_sid (status_id)
) ENGINE=InnoDB;





CREATE TABLE ezorder_status_history (
  id int(11) NOT NULL auto_increment,
  modified int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  order_id int(11) NOT NULL default '0',
  status_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezorder_status_history_mod (modified),
  KEY ezorder_status_history_oid (order_id),
  KEY ezorder_status_history_sid (status_id)
) ENGINE=InnoDB;





CREATE TABLE ezpackage (
  id int(11) NOT NULL auto_increment,
  install_date int(11) NOT NULL default '0',
  name varchar(100) NOT NULL default '',
  version varchar(30) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezpaymentobject (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  payment_string varchar(255) NOT NULL default '',
  status int(11) NOT NULL default '0',
  workflowprocess_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezpdf_export (
  created int(11) default NULL,
  creator_id int(11) default NULL,
  export_classes varchar(255) default NULL,
  export_structure varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  intro_text longtext,
  modified int(11) default NULL,
  modifier_id int(11) default NULL,
  pdf_filename varchar(255) default NULL,
  show_frontpage int(11) default NULL,
  site_access varchar(255) default NULL,
  source_node_id int(11) default NULL,
  status int(11) default NULL,
  sub_text longtext,
  title varchar(255) default NULL,
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version)
) ENGINE=InnoDB;





CREATE TABLE ezpending_actions (
  action varchar(64) NOT NULL default '',
  created int(11) default NULL,
  param longtext,
  KEY ezpending_actions_action (action),
  KEY ezpending_actions_created (created)
) ENGINE=InnoDB;





CREATE TABLE ezpolicy (
  function_name varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  module_name varchar(255) default NULL,
  role_id int(11) default NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  identifier varchar(255) NOT NULL default '',
  policy_id int(11) default NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value varchar(255) default NULL,
  PRIMARY KEY  (id),
  KEY ezpolicy_limitation_value_val (value)
) ENGINE=InnoDB;





CREATE TABLE ezpreferences (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  user_id int(11) NOT NULL default '0',
  value varchar(100) default NULL,
  PRIMARY KEY  (id),
  KEY ezpreferences_name (name),
  KEY ezpreferences_user_id_idx (user_id,name)
) ENGINE=InnoDB;





CREATE TABLE ezproductcategory (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezproductcollection (
  created int(11) default NULL,
  currency_code varchar(4) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezproductcollection_item (
  contentobject_id int(11) NOT NULL default '0',
  discount float default NULL,
  id int(11) NOT NULL auto_increment,
  is_vat_inc int(11) default NULL,
  item_count int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  price float default '0',
  productcollection_id int(11) NOT NULL default '0',
  vat_value float default NULL,
  PRIMARY KEY  (id),
  KEY ezproductcollection_item_contentobject_id (contentobject_id),
  KEY ezproductcollection_item_productcollection_id (productcollection_id)
) ENGINE=InnoDB;





CREATE TABLE ezproductcollection_item_opt (
  id int(11) NOT NULL auto_increment,
  item_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  object_attribute_id int(11) default NULL,
  option_item_id int(11) NOT NULL default '0',
  price float NOT NULL default '0',
  value varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezproductcollection_item_opt_item_id (item_id)
) ENGINE=InnoDB;





CREATE TABLE ezrole (
  id int(11) NOT NULL auto_increment,
  is_new int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  value char(1) default NULL,
  version int(11) default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezrss_export (
  access_url varchar(255) default NULL,
  active int(11) default NULL,
  created int(11) default NULL,
  creator_id int(11) default NULL,
  description longtext,
  id int(11) NOT NULL auto_increment,
  image_id int(11) default NULL,
  main_node_only int(11) NOT NULL default '1',
  modified int(11) default NULL,
  modifier_id int(11) default NULL,
  node_id int(11) default NULL,
  number_of_objects int(11) NOT NULL default '0',
  rss_version varchar(255) default NULL,
  site_access varchar(255) default NULL,
  status int(11) NOT NULL default '0',
  title varchar(255) default NULL,
  url varchar(255) default NULL,
  PRIMARY KEY  (id,status)
) ENGINE=InnoDB;





CREATE TABLE ezrss_export_item (
  category varchar(255) default NULL,
  class_id int(11) default NULL,
  description varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  rssexport_id int(11) default NULL,
  source_node_id int(11) default NULL,
  status int(11) NOT NULL default '0',
  subnodes int(11) NOT NULL default '0',
  title varchar(255) default NULL,
  PRIMARY KEY  (id,status),
  KEY ezrss_export_rsseid (rssexport_id)
) ENGINE=InnoDB;





CREATE TABLE ezrss_import (
  active int(11) default NULL,
  class_description varchar(255) default NULL,
  class_id int(11) default NULL,
  class_title varchar(255) default NULL,
  class_url varchar(255) default NULL,
  created int(11) default NULL,
  creator_id int(11) default NULL,
  destination_node_id int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  import_description longtext NOT NULL,
  modified int(11) default NULL,
  modifier_id int(11) default NULL,
  name varchar(255) default NULL,
  object_owner_id int(11) default NULL,
  status int(11) NOT NULL default '0',
  url longtext,
  PRIMARY KEY  (id,status)
) ENGINE=InnoDB;





CREATE TABLE ezsearch_object_word_link (
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  frequency float NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  identifier varchar(255) NOT NULL default '',
  integer_value int(11) NOT NULL default '0',
  next_word_id int(11) NOT NULL default '0',
  placement int(11) NOT NULL default '0',
  prev_word_id int(11) NOT NULL default '0',
  published int(11) NOT NULL default '0',
  section_id int(11) NOT NULL default '0',
  word_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_object_word_link_frequency (frequency),
  KEY ezsearch_object_word_link_identifier (identifier),
  KEY ezsearch_object_word_link_integer_value (integer_value),
  KEY ezsearch_object_word_link_object (contentobject_id),
  KEY ezsearch_object_word_link_word (word_id)
) ENGINE=InnoDB;





CREATE TABLE ezsearch_return_count (
  count int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  phrase_id int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_return_cnt_ph_id_cnt (phrase_id,count)
) ENGINE=InnoDB;





CREATE TABLE ezsearch_search_phrase (
  id int(11) NOT NULL auto_increment,
  phrase varchar(250) default NULL,
  phrase_count int(11) default '0',
  result_count int(11) default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_search_phrase_count (phrase_count),
  UNIQUE KEY ezsearch_search_phrase_phrase (phrase)
) ENGINE=InnoDB;





CREATE TABLE ezsearch_word (
  id int(11) NOT NULL auto_increment,
  object_count int(11) NOT NULL default '0',
  word varchar(150) default NULL,
  PRIMARY KEY  (id),
  KEY ezsearch_word_obj_count (object_count),
  KEY ezsearch_word_word_i (word)
) ENGINE=InnoDB;





CREATE TABLE ezsection (
  id int(11) NOT NULL auto_increment,
  locale varchar(255) default NULL,
  name varchar(255) default NULL,
  navigation_part_identifier varchar(100) default 'ezcontentnavigationpart',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezsession (
  data longtext NOT NULL,
  expiration_time int(11) NOT NULL default '0',
  session_key varchar(32) NOT NULL default '',
  user_id int(11) NOT NULL default '0',
  user_hash varchar(32) NOT NULL default '',
  PRIMARY KEY  (session_key),
  KEY expiration_time (expiration_time),
  KEY ezsession_user_id (user_id)
) ENGINE=InnoDB;





CREATE TABLE ezsite_data (
  name varchar(60) NOT NULL default '',
  value longtext NOT NULL,
  PRIMARY KEY  (name)
) ENGINE=InnoDB;





CREATE TABLE ezsubtree_notification_rule (
  id int(11) NOT NULL auto_increment,
  node_id int(11) NOT NULL default '0',
  use_digest int(11) default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsubtree_notification_rule_user_id (user_id)
) ENGINE=InnoDB;





CREATE TABLE eztipafriend_counter (
  count int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  requested int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id,requested)
) ENGINE=InnoDB;





CREATE TABLE eztipafriend_request (
  created int(11) NOT NULL default '0',
  email_receiver varchar(100) NOT NULL default '',
  KEY eztipafriend_request_created (created),
  KEY eztipafriend_request_email_rec (email_receiver)
) ENGINE=InnoDB;





CREATE TABLE eztrigger (
  connect_type char(1) NOT NULL default '',
  function_name varchar(200) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  module_name varchar(200) NOT NULL default '',
  name varchar(255) default NULL,
  workflow_id int(11) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY eztrigger_def_id (module_name(50),function_name(50),connect_type),
  KEY eztrigger_fetch (name(25),module_name(50),function_name(50))
) ENGINE=InnoDB;





CREATE TABLE ezurl (
  created int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  is_valid int(11) NOT NULL default '1',
  last_checked int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  original_url_md5 varchar(32) NOT NULL default '',
  url longtext,
  PRIMARY KEY  (id),
  KEY ezurl_url (url(255))
) ENGINE=InnoDB;





CREATE TABLE ezurl_object_link (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  url_id int(11) NOT NULL default '0',
  KEY ezurl_ol_coa_id (contentobject_attribute_id),
  KEY ezurl_ol_coa_version (contentobject_attribute_version),
  KEY ezurl_ol_url_id (url_id)
) ENGINE=InnoDB;





CREATE TABLE ezurlalias (
  destination_url longtext NOT NULL,
  forward_to_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  is_imported int(11) NOT NULL default '0',
  is_internal int(11) NOT NULL default '1',
  is_wildcard int(11) NOT NULL default '0',
  source_md5 varchar(32) default NULL,
  source_url longtext NOT NULL,
  PRIMARY KEY  (id),
  KEY ezurlalias_desturl (destination_url(200)),
  KEY ezurlalias_forward_to_id (forward_to_id),
  KEY ezurlalias_imp_wcard_fwd (is_imported,is_wildcard,forward_to_id),
  KEY ezurlalias_source_md5 (source_md5),
  KEY ezurlalias_source_url (source_url(255)),
  KEY ezurlalias_wcard_fwd (is_wildcard,forward_to_id)
) ENGINE=InnoDB;





CREATE TABLE ezurlalias_ml (
  action longtext NOT NULL,
  action_type varchar(32) NOT NULL default '',
  alias_redirects int(11) NOT NULL default '1',
  id int(11) NOT NULL default '0',
  is_alias int(11) NOT NULL default '0',
  is_original int(11) NOT NULL default '0',
  lang_mask int(11) NOT NULL default '0',
  link int(11) NOT NULL default '0',
  parent int(11) NOT NULL default '0',
  text longtext NOT NULL,
  text_md5 varchar(32) NOT NULL default '',
  PRIMARY KEY  (parent,text_md5),
  KEY ezurlalias_ml_act_org (action(32),is_original),
  KEY ezurlalias_ml_action (action(32),id,link),
  KEY ezurlalias_ml_actt (action_type),
  KEY ezurlalias_ml_actt_org_al (action_type,is_original,is_alias),
  KEY ezurlalias_ml_id (id),
  KEY ezurlalias_ml_par_act_id_lnk (parent,action(32),id,link),
  KEY ezurlalias_ml_par_lnk_txt (parent,link,text(32)),
  KEY ezurlalias_ml_par_txt (parent,text(32)),
  KEY ezurlalias_ml_text (text(32),id,link),
  KEY ezurlalias_ml_text_lang (text(32),lang_mask,parent)
) ENGINE=InnoDB;





CREATE TABLE ezurlalias_ml_incr (
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezurlwildcard (
  destination_url longtext NOT NULL,
  id int(11) NOT NULL auto_increment,
  source_url longtext NOT NULL,
  type int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezuser (
  contentobject_id int(11) NOT NULL default '0',
  email varchar(150) NOT NULL default '',
  login varchar(150) NOT NULL default '',
  password_hash varchar(50) default NULL,
  password_hash_type int(11) NOT NULL default '1',
  PRIMARY KEY  (contentobject_id)
) ENGINE=InnoDB;





CREATE TABLE ezuser_accountkey (
  hash_key varchar(32) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  time int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezuser_discountrule (
  contentobject_id int(11) default NULL,
  discountrule_id int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezuser_role (
  contentobject_id int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  limit_identifier varchar(255) default '',
  limit_value varchar(255) default '',
  role_id int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ezuser_role_contentobject_id (contentobject_id),
  KEY ezuser_role_role_id (role_id)
) ENGINE=InnoDB;





CREATE TABLE ezuser_setting (
  is_enabled int(11) NOT NULL default '0',
  max_login int(11) default NULL,
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (user_id)
) ENGINE=InnoDB;





CREATE TABLE ezuservisit (
  current_visit_timestamp int(11) NOT NULL default '0',
  failed_login_attempts int(11) NOT NULL default '0',
  last_visit_timestamp int(11) NOT NULL default '0',
  login_count int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (user_id),
  KEY ezuservisit_co_visit_count (current_visit_timestamp,login_count)
) ENGINE=InnoDB;





CREATE TABLE ezvatrule (
  country_code varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  vat_type int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezvatrule_product_category (
  product_category_id int(11) NOT NULL default '0',
  vatrule_id int(11) NOT NULL default '0',
  PRIMARY KEY  (vatrule_id,product_category_id)
) ENGINE=InnoDB;





CREATE TABLE ezvattype (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  percentage float default NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezview_counter (
  count int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) ENGINE=InnoDB;





CREATE TABLE ezwaituntildatevalue (
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  workflow_event_id int(11) NOT NULL default '0',
  workflow_event_version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,workflow_event_id,workflow_event_version),
  KEY ezwaituntildateevalue_wf_ev_id_wf_ver (workflow_event_id,workflow_event_version)
) ENGINE=InnoDB;





CREATE TABLE ezwishlist (
  id int(11) NOT NULL auto_increment,
  productcollection_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezworkflow (
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  is_enabled int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  version int(11) NOT NULL default '0',
  workflow_type_string varchar(50) NOT NULL default '',
  PRIMARY KEY  (id,version)
) ENGINE=InnoDB;





CREATE TABLE ezworkflow_assign (
  access_type int(11) NOT NULL default '0',
  as_tree int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  node_id int(11) NOT NULL default '0',
  workflow_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezworkflow_event (
  data_int1 int(11) default NULL,
  data_int2 int(11) default NULL,
  data_int3 int(11) default NULL,
  data_int4 int(11) default NULL,
  data_text1 varchar(255) default NULL,
  data_text2 varchar(255) default NULL,
  data_text3 varchar(255) default NULL,
  data_text4 varchar(255) default NULL,
  data_text5 longtext,
  description varchar(50) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  placement int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  workflow_id int(11) NOT NULL default '0',
  workflow_type_string varchar(50) NOT NULL default '',
  PRIMARY KEY  (id,version)
) ENGINE=InnoDB;





CREATE TABLE ezworkflow_group (
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  modified int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;





CREATE TABLE ezworkflow_group_link (
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  workflow_id int(11) NOT NULL default '0',
  workflow_version int(11) NOT NULL default '0',
  PRIMARY KEY  (workflow_id,group_id,workflow_version)
) ENGINE=InnoDB;





CREATE TABLE ezworkflow_process (
  activation_date int(11) default NULL,
  content_id int(11) NOT NULL default '0',
  content_version int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  event_id int(11) NOT NULL default '0',
  event_position int(11) NOT NULL default '0',
  event_state int(11) default NULL,
  event_status int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  last_event_id int(11) NOT NULL default '0',
  last_event_position int(11) NOT NULL default '0',
  last_event_status int(11) NOT NULL default '0',
  memento_key varchar(32) default NULL,
  modified int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  parameters longtext,
  process_key varchar(32) NOT NULL default '',
  session_key varchar(32) NOT NULL default '0',
  status int(11) default NULL,
  user_id int(11) NOT NULL default '0',
  workflow_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezworkflow_process_process_key (process_key)
) ENGINE=InnoDB;


