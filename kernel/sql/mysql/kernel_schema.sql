CREATE TABLE ezapprove_items (
  collaboration_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezbasket (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  session_id varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezbasket_session_id (session_id)
) TYPE=MyISAM;





CREATE TABLE ezbinaryfile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  download_count int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





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
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezcollab_item_message_link (
  collaboration_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  message_id int(11) NOT NULL default '0',
  message_type int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  participant_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezcollab_item_status (
  collaboration_id int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  is_read int(11) NOT NULL default '0',
  last_read int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (collaboration_id,user_id)
) TYPE=MyISAM;





CREATE TABLE ezcollab_notification_rule (
  collab_identifier varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  user_id varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezcollab_profile (
  created int(11) NOT NULL default '0',
  data_text1 longtext NOT NULL,
  id int(11) NOT NULL auto_increment,
  main_group int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezcontent_translation (
  id int(11) NOT NULL auto_increment,
  locale varchar(255) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezcontentbrowsebookmark (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  node_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezcontentbrowsebookmark_user (user_id)
) TYPE=MyISAM;





CREATE TABLE ezcontentbrowserecent (
  created int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  node_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezcontentbrowserecent_user (user_id)
) TYPE=MyISAM;





CREATE TABLE ezcontentclass (
  contentobject_name varchar(255) default NULL,
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  identifier varchar(50) NOT NULL default '',
  is_container int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  remote_id varchar(100) NOT NULL default '',
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version),
  KEY ezcontentclass_version (version)
) TYPE=MyISAM;





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
  name varchar(255) NOT NULL default '',
  placement int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;





CREATE TABLE ezcontentclass_classgroup (
  contentclass_id int(11) NOT NULL default '0',
  contentclass_version int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (contentclass_id,contentclass_version,group_id)
) TYPE=MyISAM;





CREATE TABLE ezcontentclassgroup (
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  modified int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezcontentobject (
  contentclass_id int(11) NOT NULL default '0',
  current_version int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  is_published int(11) default NULL,
  modified int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  owner_id int(11) NOT NULL default '0',
  published int(11) NOT NULL default '0',
  remote_id varchar(100) default NULL,
  section_id int(11) NOT NULL default '0',
  status int(11) default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





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
  sort_key_int int(11) NOT NULL default '0',
  sort_key_string varchar(255) NOT NULL default '',
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version),
  KEY ezcontentobject_attribute_co_id_ver_lang_code (contentobject_id,version,language_code),
  KEY ezcontentobject_attribute_contentobject_id (contentobject_id),
  KEY ezcontentobject_attribute_language_code (language_code),
  KEY sort_key_int (sort_key_int),
  KEY sort_key_string (sort_key_string)
) TYPE=MyISAM;





CREATE TABLE ezcontentobject_link (
  from_contentobject_id int(11) NOT NULL default '0',
  from_contentobject_version int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  to_contentobject_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezcontentobject_name (
  content_translation varchar(20) NOT NULL default '',
  content_version int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  real_translation varchar(20) default NULL,
  PRIMARY KEY  (contentobject_id,content_version,content_translation)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezcontentobject_version (
  contentobject_id int(11) default NULL,
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  modified int(11) NOT NULL default '0',
  status int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  workflow_event_pos int(11) default '0',
  PRIMARY KEY  (id),
  KEY idx_object_version_objver (contentobject_id,version)
) TYPE=MyISAM;





CREATE TABLE ezdiscountrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezdiscountsubrule (
  discount_percent float default NULL,
  discountrule_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  limitation char(1) default NULL,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezdiscountsubrule_value (
  discountsubrule_id int(11) NOT NULL default '0',
  issection int(11) NOT NULL default '0',
  value int(11) NOT NULL default '0',
  PRIMARY KEY  (discountsubrule_id,value,issection)
) TYPE=MyISAM;





CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumid int(11) NOT NULL default '0',
  enumvalue varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid),
  KEY ezenumobjectvalue_co_attr_id_co_attr_ver (contentobject_attribute_id,contentobject_attribute_version)
) TYPE=MyISAM;





CREATE TABLE ezenumvalue (
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,contentclass_attribute_id,contentclass_attribute_version),
  KEY ezenumvalue_co_cl_attr_id_co_class_att_ver (contentclass_attribute_id,contentclass_attribute_version)
) TYPE=MyISAM;





CREATE TABLE ezforgot_password (
  hash_key varchar(32) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  time int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezgeneral_digest_user_settings (
  address varchar(255) NOT NULL default '',
  day varchar(255) NOT NULL default '',
  digest_type int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  receive_digest int(11) NOT NULL default '0',
  time varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezimage (
  alternative_text varchar(255) NOT NULL default '',
  contentobject_attribute_id int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  version int(11) NOT NULL default '0',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;





CREATE TABLE ezimagefile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  filepath longtext NOT NULL,
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id),
  KEY ezimagefile_coid (contentobject_attribute_id),
  KEY ezimagefile_file (filepath(200))
) TYPE=MyISAM;





CREATE TABLE ezimagevariation (
  additional_path varchar(255) default NULL,
  contentobject_attribute_id int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  height int(11) NOT NULL default '0',
  requested_height int(11) NOT NULL default '0',
  requested_width int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  width int(11) NOT NULL default '0',
  PRIMARY KEY  (contentobject_attribute_id,version,requested_width,requested_height)
) TYPE=MyISAM;





CREATE TABLE ezinfocollection (
  contentobject_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  modified int(11) default '0',
  user_identifier varchar(34) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezinfocollection_attribute (
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  data_float float default NULL,
  data_int int(11) default NULL,
  data_text longtext,
  id int(11) NOT NULL auto_increment,
  informationcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezkeyword (
  class_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  keyword varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezkeyword_attribute_link (
  id int(11) NOT NULL auto_increment,
  keyword_id int(11) NOT NULL default '0',
  objectattribute_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezmodule_run (
  function_name varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  module_data longtext,
  module_name varchar(255) default NULL,
  workflow_process_id int(11) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY ezmodule_run_workflow_process_id_s (workflow_process_id)
) TYPE=MyISAM;





CREATE TABLE eznode_assignment (
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  from_node_id int(11) default '0',
  id int(11) NOT NULL auto_increment,
  is_main int(11) NOT NULL default '0',
  parent_node int(11) default NULL,
  parent_remote_id varchar(100) NOT NULL default '',
  remote_id int(11) NOT NULL default '0',
  sort_field int(11) default '1',
  sort_order int(11) default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE eznotificationcollection (
  data_subject longtext NOT NULL,
  data_text longtext NOT NULL,
  event_id int(11) NOT NULL default '0',
  handler varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  transport varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE eznotificationcollection_item (
  address varchar(255) NOT NULL default '',
  collection_id int(11) NOT NULL default '0',
  event_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  send_date int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezoperation_memento (
  id int(11) NOT NULL auto_increment,
  main int(11) NOT NULL default '0',
  main_key varchar(32) NOT NULL default '',
  memento_data longtext NOT NULL,
  memento_key varchar(32) NOT NULL default '',
  PRIMARY KEY  (id,memento_key),
  KEY ezoperation_memento_memento_key_main (memento_key,main)
) TYPE=MyISAM;





CREATE TABLE ezorder (
  account_identifier varchar(100) NOT NULL default 'default',
  created int(11) NOT NULL default '0',
  data_text_1 longtext,
  data_text_2 longtext,
  email varchar(150) default '',
  id int(11) NOT NULL auto_increment,
  ignore_vat int(11) NOT NULL default '0',
  is_temporary int(11) NOT NULL default '1',
  order_nr int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezorder_item (
  description varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  price float default NULL,
  vat_value int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezorder_item_order_id (order_id)
) TYPE=MyISAM;





CREATE TABLE ezpaymentobject (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  payment_string varchar(255) NOT NULL default '',
  status int(11) NOT NULL default '0',
  workflowprocess_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





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
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezpending_actions (
  action varchar(64) NOT NULL default '',
  param longtext,
  KEY ezpending_actions_action (action)
) TYPE=MyISAM;





CREATE TABLE ezpolicy (
  function_name varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  module_name varchar(255) default NULL,
  role_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  identifier varchar(255) NOT NULL default '',
  policy_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezpreferences (
  id int(11) NOT NULL auto_increment,
  name varchar(100) default NULL,
  user_id int(11) NOT NULL default '0',
  value varchar(100) default NULL,
  PRIMARY KEY  (id),
  KEY ezpreferences_name (name),
  KEY ezpreferences_user_id_idx (user_id,name)
) TYPE=MyISAM;





CREATE TABLE ezproductcollection (
  created int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezproductcollection_item (
  contentobject_id int(11) NOT NULL default '0',
  discount float default NULL,
  id int(11) NOT NULL auto_increment,
  is_vat_inc int(11) default NULL,
  item_count int(11) NOT NULL default '0',
  price float default '0',
  productcollection_id int(11) NOT NULL default '0',
  vat_value float default NULL,
  PRIMARY KEY  (id),
  KEY ezproductcollection_item_contentobject_id (contentobject_id),
  KEY ezproductcollection_item_productcollection_id (productcollection_id)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezrole (
  id int(11) NOT NULL auto_increment,
  is_new int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  value char(1) default NULL,
  version int(11) default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezrss_export (
  access_url varchar(255) default NULL,
  active int(11) default NULL,
  created int(11) default NULL,
  creator_id int(11) default NULL,
  description longtext,
  id int(11) NOT NULL auto_increment,
  image_id int(11) default NULL,
  modified int(11) default NULL,
  modifier_id int(11) default NULL,
  rss_version varchar(255) default NULL,
  site_access varchar(255) default NULL,
  status int(11) default NULL,
  title varchar(255) default NULL,
  url varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezrss_export_item (
  class_id int(11) default NULL,
  description varchar(255) default NULL,
  id int(11) NOT NULL auto_increment,
  rssexport_id int(11) default NULL,
  source_node_id int(11) default NULL,
  title varchar(255) default NULL,
  PRIMARY KEY  (id),
  KEY ezrss_export_rsseid (rssexport_id)
) TYPE=MyISAM;





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
  modified int(11) default NULL,
  modifier_id int(11) default NULL,
  name varchar(255) default NULL,
  object_owner_id int(11) default NULL,
  status int(11) default NULL,
  url longtext,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezsearch_return_count (
  count int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  phrase_id int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezsearch_search_phrase (
  id int(11) NOT NULL auto_increment,
  phrase varchar(250) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezsearch_word (
  id int(11) NOT NULL auto_increment,
  object_count int(11) NOT NULL default '0',
  word varchar(150) default NULL,
  PRIMARY KEY  (id),
  KEY ezsearch_word_word_i (word)
) TYPE=MyISAM;





CREATE TABLE ezsection (
  id int(11) NOT NULL auto_increment,
  locale varchar(255) default NULL,
  name varchar(255) default NULL,
  navigation_part_identifier varchar(100) default 'ezcontentnavigationpart',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezsession (
  data longtext NOT NULL,
  expiration_time int(11) NOT NULL default '0',
  session_key varchar(32) NOT NULL default '',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (session_key),
  KEY expiration_time (expiration_time),
  KEY ezsession_user_id (user_id)
) TYPE=MyISAM;





CREATE TABLE ezsite_data (
  name varchar(60) NOT NULL default '',
  value longtext NOT NULL,
  PRIMARY KEY  (name)
) TYPE=MyISAM;





CREATE TABLE ezsubtree_expiry (
  cache_file varchar(255) NOT NULL default '',
  subtree int(11) default NULL,
  KEY ezsubtree_expiry_subtree (subtree)
) TYPE=MyISAM DELAY_KEY_WRITE=1;





CREATE TABLE ezsubtree_notification_rule (
  id int(11) NOT NULL auto_increment,
  node_id int(11) NOT NULL default '0',
  use_digest int(11) default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsubtree_notification_rule_user_id (user_id)
) TYPE=MyISAM;





CREATE TABLE eztipafriend_counter (
  count int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;





CREATE TABLE eztrigger (
  connect_type char(1) NOT NULL default '',
  function_name varchar(200) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  module_name varchar(200) NOT NULL default '',
  name varchar(255) default NULL,
  workflow_id int(11) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY eztrigger_def_id (module_name,function_name,connect_type),
  KEY eztrigger_fetch (name(25),module_name(50),function_name(50))
) TYPE=MyISAM;





CREATE TABLE ezurl (
  created int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  is_valid int(11) NOT NULL default '1',
  last_checked int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  original_url_md5 varchar(32) NOT NULL default '',
  url varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezurl_object_link (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  url_id int(11) NOT NULL default '0',
  KEY ezurl_ol_coa_id (contentobject_attribute_id),
  KEY ezurl_ol_coa_version (contentobject_attribute_version),
  KEY ezurl_ol_url_id (url_id)
) TYPE=MyISAM;





CREATE TABLE ezurlalias (
  destination_url longtext NOT NULL,
  forward_to_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  is_internal int(11) NOT NULL default '1',
  is_wildcard int(11) NOT NULL default '0',
  source_md5 varchar(32) default NULL,
  source_url longtext NOT NULL,
  PRIMARY KEY  (id),
  KEY ezurlalias_desturl (destination_url(200)),
  KEY ezurlalias_source_md5 (source_md5),
  KEY ezurlalias_source_url (source_url(255))
) TYPE=MyISAM;





CREATE TABLE ezuser (
  contentobject_id int(11) NOT NULL default '0',
  email varchar(150) NOT NULL default '',
  login varchar(150) NOT NULL default '',
  password_hash varchar(50) default NULL,
  password_hash_type int(11) NOT NULL default '1',
  PRIMARY KEY  (contentobject_id)
) TYPE=MyISAM;





CREATE TABLE ezuser_accountkey (
  hash_key varchar(32) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  time int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezuser_discountrule (
  contentobject_id int(11) default NULL,
  discountrule_id int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezuser_role (
  contentobject_id int(11) default NULL,
  id int(11) NOT NULL auto_increment,
  limit_identifier varchar(255) default '',
  limit_value varchar(255) default '',
  role_id int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ezuser_role_contentobject_id (contentobject_id),
  KEY ezuser_role_role_id (role_id)
) TYPE=MyISAM;





CREATE TABLE ezuser_setting (
  is_enabled int(11) NOT NULL default '0',
  max_login int(11) default NULL,
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;





CREATE TABLE ezuservisit (
  current_visit_timestamp int(11) NOT NULL default '0',
  last_visit_timestamp int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;





CREATE TABLE ezvattype (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  percentage float default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezview_counter (
  count int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;





CREATE TABLE ezwaituntildatevalue (
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  workflow_event_id int(11) NOT NULL default '0',
  workflow_event_version int(11) NOT NULL default '0',
  PRIMARY KEY  (id,workflow_event_id,workflow_event_version),
  KEY ezwaituntildateevalue_wf_ev_id_wf_ver (workflow_event_id,workflow_event_version)
) TYPE=MyISAM;





CREATE TABLE ezwishlist (
  id int(11) NOT NULL auto_increment,
  productcollection_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





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
) TYPE=MyISAM;





CREATE TABLE ezworkflow_assign (
  access_type int(11) NOT NULL default '0',
  as_tree int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  node_id int(11) NOT NULL default '0',
  workflow_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezworkflow_event (
  data_int1 int(11) default NULL,
  data_int2 int(11) default NULL,
  data_int3 int(11) default NULL,
  data_int4 int(11) default NULL,
  data_text1 varchar(50) default NULL,
  data_text2 varchar(50) default NULL,
  data_text3 varchar(50) default NULL,
  data_text4 varchar(50) default NULL,
  description varchar(50) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  placement int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  workflow_id int(11) NOT NULL default '0',
  workflow_type_string varchar(50) NOT NULL default '',
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;





CREATE TABLE ezworkflow_group (
  created int(11) NOT NULL default '0',
  creator_id int(11) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  modified int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





CREATE TABLE ezworkflow_group_link (
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  workflow_id int(11) NOT NULL default '0',
  workflow_version int(11) NOT NULL default '0',
  PRIMARY KEY  (workflow_id,group_id,workflow_version)
) TYPE=MyISAM;





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
) TYPE=MyISAM;



