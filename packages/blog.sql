









CREATE TABLE ezapprove_items (
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) NOT NULL default '0',
  collaboration_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezbasket (
  id int(11) NOT NULL auto_increment,
  session_id varchar(255) NOT NULL default '',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezbasket_session_id (session_id)
) TYPE=MyISAM;











CREATE TABLE ezbinaryfile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;











CREATE TABLE ezcollab_group (
  id int(11) NOT NULL auto_increment,
  parent_group_id int(11) NOT NULL default '0',
  depth int(11) NOT NULL default '0',
  path_string varchar(255) NOT NULL default '',
  is_open int(11) NOT NULL default '1',
  user_id int(11) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  priority int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezcollab_group_path (path_string),
  KEY ezcollab_group_depth (depth)
) TYPE=MyISAM;











CREATE TABLE ezcollab_item (
  id int(11) NOT NULL auto_increment,
  type_identifier varchar(40) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  status int(11) NOT NULL default '1',
  data_text1 text NOT NULL,
  data_text2 text NOT NULL,
  data_text3 text NOT NULL,
  data_int1 int(11) NOT NULL default '0',
  data_int2 int(11) NOT NULL default '0',
  data_int3 int(11) NOT NULL default '0',
  data_float1 float NOT NULL default '0',
  data_float2 float NOT NULL default '0',
  data_float3 float NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezcollab_item_group_link (
  collaboration_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  is_read int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  last_read int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (collaboration_id,group_id,user_id)
) TYPE=MyISAM;











CREATE TABLE ezcollab_item_message_link (
  id int(11) NOT NULL auto_increment,
  collaboration_id int(11) NOT NULL default '0',
  participant_id int(11) NOT NULL default '0',
  message_id int(11) NOT NULL default '0',
  message_type int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezcollab_item_participant_link (
  collaboration_id int(11) NOT NULL default '0',
  participant_id int(11) NOT NULL default '0',
  participant_type int(11) NOT NULL default '1',
  participant_role int(11) NOT NULL default '1',
  is_read int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  last_read int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (collaboration_id,participant_id)
) TYPE=MyISAM;











CREATE TABLE ezcollab_item_status (
  collaboration_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  is_read int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  last_read int(11) NOT NULL default '0',
  PRIMARY KEY  (collaboration_id,user_id)
) TYPE=MyISAM;











CREATE TABLE ezcollab_notification_rule (
  id int(11) NOT NULL auto_increment,
  user_id varchar(255) NOT NULL default '',
  collab_identifier varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezcollab_profile (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  main_group int(11) NOT NULL default '0',
  data_text1 text NOT NULL,
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezcollab_simple_message (
  id int(11) NOT NULL auto_increment,
  message_type varchar(40) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  data_text1 text NOT NULL,
  data_text2 text NOT NULL,
  data_text3 text NOT NULL,
  data_int1 int(11) NOT NULL default '0',
  data_int2 int(11) NOT NULL default '0',
  data_int3 int(11) NOT NULL default '0',
  data_float1 float NOT NULL default '0',
  data_float2 float NOT NULL default '0',
  data_float3 float NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezcontent_translation (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  locale varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezcontent_translation (id, name, locale) VALUES (1,'English (United Kingdom)','eng-GB');





CREATE TABLE ezcontentbrowsebookmark (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezcontentbrowsebookmark_user (user_id)
) TYPE=MyISAM;











CREATE TABLE ezcontentbrowserecent (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezcontentbrowserecent_user (user_id)
) TYPE=MyISAM;











CREATE TABLE ezcontentclass (
  id int(11) NOT NULL auto_increment,
  version int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  identifier varchar(50) NOT NULL default '',
  contentobject_name varchar(255) default NULL,
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version),
  KEY ezcontentclass_version (version)
) TYPE=MyISAM;






INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1068804191);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (2,0,'Article','article','<title>',14,14,1024392098,1066907423);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (3,0,'User group','user_group','<name>',14,14,1024392098,1048494743);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1068123024);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (5,0,'Image','image','<name>',8,14,1031484992,1048494784);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (12,0,'File','file','<name>',14,14,1052385472,1052385669);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (15,0,'Template look','template_look','<title>',14,14,1066390045,1069677671);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (25,0,'Poll','poll','<name>',14,14,1068716373,1068717758);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (24,0,'Link','link','<name>',14,14,1068716182,1068716360);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (23,0,'Log','log','<title>',14,14,1068710873,1068718967);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (26,0,'Comment','comment','<name>',14,14,1068716787,1068716858);





CREATE TABLE ezcontentclass_attribute (
  id int(11) NOT NULL auto_increment,
  version int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  identifier varchar(50) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  data_type_string varchar(50) NOT NULL default '',
  is_searchable int(1) NOT NULL default '0',
  is_required int(1) NOT NULL default '0',
  placement int(11) NOT NULL default '0',
  data_int1 int(11) default NULL,
  data_int2 int(11) default NULL,
  data_int3 int(11) default NULL,
  data_int4 int(11) default NULL,
  data_float1 float default NULL,
  data_float2 float default NULL,
  data_float3 float default NULL,
  data_float4 float default NULL,
  data_text1 varchar(50) default NULL,
  data_text2 varchar(50) default NULL,
  data_text3 varchar(50) default NULL,
  data_text4 varchar(255) default NULL,
  data_text5 text,
  is_information_collector int(11) NOT NULL default '0',
  can_translate int(11) default '1',
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;






INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (214,0,1,'list_title','List title','ezstring',0,1,4,0,0,0,0,0,0,0,0,'Recent items','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (198,0,4,'location','Location','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (199,0,4,'signature','Signature','eztext',1,0,6,2,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (203,0,23,'log','Log','ezxmltext',1,0,2,15,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (207,0,25,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (208,0,25,'option','Option','ezoption',0,1,2,0,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (205,0,24,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (206,0,24,'url','URL','ezurl',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (204,0,24,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (202,0,23,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (212,0,26,'comment','Comment','eztext',1,0,4,10,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (200,0,4,'user_image','User image','ezimage',0,0,7,1,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (197,0,4,'title','Title','ezstring',1,0,4,25,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (12,0,4,'user_account','User account','ezuser',1,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (213,0,23,'enable_comments','Enable comments','ezboolean',1,0,3,0,0,1,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (211,0,26,'url','URL','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (210,0,26,'email','E-mail','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (209,0,26,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (215,0,1,'category_list_title','Category list title','ezstring',0,1,5,0,0,0,0,0,0,0,0,'All Categories','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (216,0,1,'archive_title','Archive Title','ezstring',0,1,3,0,0,0,0,0,0,0,0,'Item Archive','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1);





CREATE TABLE ezcontentclass_classgroup (
  contentclass_id int(11) NOT NULL default '0',
  contentclass_version int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (contentclass_id,contentclass_version,group_id)
) TYPE=MyISAM;






INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (1,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (2,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (4,0,2,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (5,0,3,'Media');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (3,0,2,'');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (6,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (7,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (8,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (9,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (10,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (11,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (12,0,3,'Media');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (13,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (14,0,4,'Setup');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (15,0,4,'Setup');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (12,1,3,'Media');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (16,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (17,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (21,1,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (20,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (21,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (25,1,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (23,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (26,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (24,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (25,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (14,1,4,'Setup');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (26,1,1,'Content');





CREATE TABLE ezcontentclassgroup (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (3,'Media',8,14,1032009743,1033922120);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (4,'Setup',14,14,1066383702,1066383712);





CREATE TABLE ezcontentobject (
  id int(11) NOT NULL auto_increment,
  owner_id int(11) NOT NULL default '0',
  section_id int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  current_version int(11) default NULL,
  is_published int(11) default NULL,
  published int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  status int(11) default '0',
  remote_id varchar(100) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (1,14,1,1,'Blog',6,0,1033917596,1068710485,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (10,14,2,4,'Anonymous User',2,0,1033920665,1072180618,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (14,14,2,4,'Administrator User',5,0,1033920830,1068468219,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (41,14,3,1,'Media',1,0,1060695457,1060695457,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (42,14,11,1,'Setup',1,0,1066383068,1066383068,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (43,14,11,14,'Classes',9,0,1066384365,1068729357,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (45,14,11,14,'Look and feel',10,0,1066388816,1068729376,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (47,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (122,14,1,5,'New Image',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (49,14,13,1,'Blogs',5,0,1066398020,1068804689,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (51,14,1,14,'New Setup link',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (53,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (56,14,11,15,'Blog',70,0,1066643397,1069839484,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (214,14,13,23,'Today I got my new car!',2,0,1068711140,1069770036,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (215,14,13,23,'Special things happened today',1,0,1068713677,1068713677,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (212,14,13,1,'Personal',2,0,1068711069,1068717667,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (161,14,1,10,'About me',3,0,1068047603,1069770484,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (129,14,1,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (127,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (83,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (84,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (85,14,5,1,'New Folder',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (87,14,5,16,'New Company',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (88,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (91,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (213,14,13,1,'Computers',2,0,1068711091,1068717696,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (96,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (126,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (103,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (104,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (105,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (106,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (220,14,13,26,'båbåb',1,0,1068716967,1068716967,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (115,14,11,14,'Cache',4,0,1066991725,1068729308,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (116,14,11,14,'URL translator',3,0,1066992054,1068729395,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (117,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (218,14,1,25,'New Poll',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (219,14,13,26,'Bård Farstad',1,0,1068716920,1068716920,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (143,14,1,14,'New Setup link',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (144,14,1,14,'New Setup link',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (145,14,1,14,'New Setup link',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (216,14,1,25,'New Poll',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (217,14,1,25,'New Poll',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (176,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (177,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (178,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (179,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (180,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (181,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (182,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (183,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (184,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (185,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (186,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (187,14,1,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (191,149,1,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (189,14,1,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (192,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (193,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (194,149,0,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (200,149,1,21,'New Forum topic',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (201,149,1,22,'New Forum reply',1,0,0,0,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (221,14,1,25,'New Poll',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (222,14,1,25,'New Poll',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (224,14,1,25,'New Poll',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (225,14,1,25,'New Poll',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (226,14,13,23,'For Posterity\'s Sake',1,0,1068717935,1068717935,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (227,14,1,25,'Which color do you like?',2,0,1068718128,1068719696,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (228,14,12,1,'Links',4,0,1068718629,1068804675,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (229,14,12,1,'Software',1,0,1068718672,1068718672,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (230,14,12,1,'Movie',1,0,1068718712,1068718712,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (231,14,12,1,'Downloads',2,0,1068718746,1069770638,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (232,14,12,24,'VG',1,0,1068718861,1068718861,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (233,14,13,26,'bård',1,0,1068718705,1068718705,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (234,14,12,24,'Sina',1,0,1068718957,1068718957,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (235,14,13,26,'kjh',1,0,1068718760,1068718760,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (236,14,12,24,'Soft house',1,0,1068719251,1068719251,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (237,14,13,23,'New big discovery today',1,0,1068719051,1068719051,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (238,14,13,23,'No comments on this one',1,0,1068719129,1068719129,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (239,14,13,26,'Bård',1,0,1068719374,1068719374,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (240,14,1,1,'Polls',3,0,1068719643,1069172879,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (241,14,1,25,'Which one is the best of matrix movies?',1,0,1068720802,1068720802,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (242,14,13,26,'ghghj',1,0,1068720915,1068720915,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (243,14,13,1,'Entertainment',1,0,1068727871,1068727871,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (244,14,13,23,'A Pirate\'s Life',1,0,1068727918,1068727918,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (245,14,13,26,'kjlh',1,0,1068730476,1068730476,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (246,14,13,26,'kjhkjh',1,0,1068737197,1068737197,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (247,14,13,23,'I overslept today',1,0,1068796296,1068796296,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (248,10,13,26,'ete',1,0,1069409151,1069409151,2,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (252,14,13,23,'I overslept again',1,0,1069770140,1069770140,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (253,14,13,23,'Tonight I was at the movies',1,0,1069770254,1069770254,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (254,14,13,23,'Finally I got it',1,0,1069770356,1069770356,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (255,14,12,24,'eZ systems',1,0,1069770691,1069770691,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (256,14,12,24,'eZ publish at Freshmeat',1,0,1069770809,1069770809,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (257,14,12,1,'Movies',1,0,1069770849,1069770849,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (258,14,12,24,'The Matrix',1,0,1069770910,1069770910,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (259,14,12,24,'Lord of the Rings',1,0,1069770984,1069770984,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (260,14,12,1,'Sports',1,0,1069771040,1069771040,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (261,14,12,24,'Liverpool FC',1,0,1069771089,1069771089,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (262,14,1,25,'Which do you like the best: Matrix or Lord of the Rings?',1,0,1069771243,1069771243,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (263,14,13,23,'Party!',1,0,1069771496,1069771496,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (264,14,12,1,'Fun',1,0,1069773539,1069773539,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (265,14,12,24,'Pondus',1,0,1069773629,1069773629,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (266,10,13,26,'Tim',1,0,1069773783,1069773783,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (267,10,13,26,'Helene',1,0,1069773826,1069773826,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (268,14,2,3,'Anonymous Users',1,0,1072180551,1072180551,1,'');





CREATE TABLE ezcontentobject_attribute (
  id int(11) NOT NULL auto_increment,
  language_code varchar(20) NOT NULL default '',
  version int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  contentclassattribute_id int(11) NOT NULL default '0',
  data_text text,
  data_int int(11) default NULL,
  data_float float default NULL,
  attribute_original_id int(11) default '0',
  sort_key_int int(11) NOT NULL default '0',
  sort_key_string varchar(50) NOT NULL default '',
  data_type_string varchar(50) NOT NULL default '',
  PRIMARY KEY  (id,version),
  KEY ezcontentobject_attribute_contentobject_id (contentobject_id),
  KEY ezcontentobject_attribute_language_code (language_code),
  KEY sort_key_int (sort_key_int),
  KEY sort_key_string (sort_key_string),
  KEY ezcontentobject_attribute_co_id_ver_lang_code (contentobject_id,version,language_code)
) TYPE=MyISAM;






INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1,'eng-GB',6,1,4,'Blog',0,0,0,0,'blog','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (810,'eng-GB',1,235,211,'kljh',0,0,0,0,'kljh','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (811,'eng-GB',1,235,212,'< >\n\n:)\n\nhttp://ez.no',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (809,'eng-GB',1,235,210,'kjlh',0,0,0,0,'kjlh','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (676,'eng-GB',1,200,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (677,'eng-GB',1,200,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (678,'eng-GB',1,201,191,'Re:test',0,0,0,0,'re:test','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (679,'eng-GB',1,201,193,'fdsf',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (817,'eng-GB',1,226,213,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (816,'eng-GB',1,215,213,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (814,'eng-GB',1,236,206,'hzinfo',4,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (813,'eng-GB',1,236,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Download software here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (812,'eng-GB',1,236,204,'Soft house',0,0,0,0,'soft house','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (767,'eng-GB',1,215,202,'Special things happened today',0,0,0,0,'special things happened today','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (768,'eng-GB',1,215,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <link id=\"1\">sd oifgu sdoiguosdiu gfosdfg d</link>dfg sdfg sdfg sdfg sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n  <paragraph>sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n  <paragraph>\n    <link id=\"1\">sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg</link> sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (763,'eng-GB',1,213,4,'Computers',0,0,0,0,'computers','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (764,'eng-GB',1,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',61,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"60\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (639,'eng-GB',1,192,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (640,'eng-GB',1,192,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (634,'eng-GB',1,191,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (635,'eng-GB',1,191,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (636,'eng-GB',1,191,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (637,'eng-GB',1,191,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (638,'eng-GB',1,192,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (609,'eng-GB',1,184,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (610,'eng-GB',1,184,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (611,'eng-GB',1,184,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (612,'eng-GB',1,184,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (613,'eng-GB',1,185,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (614,'eng-GB',1,185,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (615,'eng-GB',1,185,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (616,'eng-GB',1,185,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (617,'eng-GB',1,186,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (618,'eng-GB',1,186,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (619,'eng-GB',1,186,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (620,'eng-GB',1,186,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (603,'eng-GB',1,182,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (604,'eng-GB',1,182,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (605,'eng-GB',1,183,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (606,'eng-GB',1,183,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (607,'eng-GB',1,183,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (608,'eng-GB',1,183,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (602,'eng-GB',1,182,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (923,'eng-GB',2,231,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (675,'eng-GB',1,200,189,'sefsefsf\nsf\nsf',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (983,'eng-GB',1,253,202,'Tonight I was at the movies',0,0,0,0,'tonight i was at the movies','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (984,'eng-GB',1,253,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>My first date with Mia! We went to see the romantic Matrix:-) </paragraph>\n  <paragraph>It must have been a success since she let me follow here home. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (985,'eng-GB',1,253,213,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (601,'eng-GB',1,182,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (820,'eng-GB',1,237,213,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (819,'eng-GB',1,237,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>I discovered the Internet, it&apos;s big - about 20meters. dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </line>\n    <line>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </line>\n  </paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg v</paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (818,'eng-GB',1,237,202,'New big discovery today',0,0,0,0,'new big discovery today','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (808,'eng-GB',1,235,209,'kjh',0,0,0,0,'kjh','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (986,'eng-GB',1,254,202,'Finally I got it',0,0,0,0,'finally i got it','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (987,'eng-GB',1,254,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>After such a long time with pulling my hair I finally got the latest edition of my software working. Perhaps the way to fortune in not that long anymore?</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (980,'eng-GB',1,252,202,'I overslept again',0,0,0,0,'i overslept again','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (981,'eng-GB',1,252,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Somehow I must have turned of the alarm in my sleep. I woke up three hours to late and missed a meeting with what will hopefully be my girlfriend. She was not very happy about this.  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (982,'eng-GB',1,252,213,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (815,'eng-GB',2,214,213,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (772,'eng-GB',1,219,209,'Bård Farstad',0,0,0,0,'bård farstad','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (773,'eng-GB',1,219,210,'nospam@ez.no',0,0,0,0,'nospam@ez.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (774,'eng-GB',1,219,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (775,'eng-GB',1,219,212,'I\'ve seen more speacial things.. dsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gf\n\ndsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gf',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (150,'eng-GB',61,56,157,'Blog',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (151,'eng-GB',62,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (992,'eng-GB',1,256,204,'eZ publish at Freshmeat',0,0,0,0,'ez publish at freshmeat','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (993,'eng-GB',1,256,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Visit eZ publish at Freshmeat</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',62,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',62,56,161,'blog_package',0,0,0,0,'blog_package','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (437,'eng-GB',62,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (671,'eng-GB',62,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (600,'eng-GB',1,181,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (591,'eng-GB',1,179,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (592,'eng-GB',1,179,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (593,'eng-GB',1,180,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (594,'eng-GB',1,180,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (595,'eng-GB',1,180,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (596,'eng-GB',1,180,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (597,'eng-GB',1,181,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (598,'eng-GB',1,181,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (599,'eng-GB',1,181,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (577,'eng-GB',1,176,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (578,'eng-GB',1,176,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (579,'eng-GB',1,176,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (580,'eng-GB',1,176,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (581,'eng-GB',1,177,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (582,'eng-GB',1,177,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (583,'eng-GB',1,177,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (584,'eng-GB',1,177,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (585,'eng-GB',1,178,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (586,'eng-GB',1,178,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (587,'eng-GB',1,178,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (588,'eng-GB',1,178,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (589,'eng-GB',1,179,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (590,'eng-GB',1,179,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (150,'eng-GB',63,56,157,'Blog test',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (151,'eng-GB',63,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',63,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"62\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',63,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',63,56,161,'blog_package',0,0,0,0,'blog_package','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (437,'eng-GB',63,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (671,'eng-GB',63,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (995,'eng-GB',1,257,4,'Movies',0,0,0,0,'movies','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (996,'eng-GB',1,257,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (997,'eng-GB',1,257,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (998,'eng-GB',1,257,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (999,'eng-GB',1,257,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1000,'eng-GB',1,258,204,'The Matrix',0,0,0,0,'the matrix','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1001,'eng-GB',1,258,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Best movies ever. This is the homepage for the movie</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1002,'eng-GB',1,258,206,'',7,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (824,'eng-GB',1,239,209,'Bård',0,0,0,0,'bård','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (823,'eng-GB',1,238,213,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (822,'eng-GB',1,238,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (821,'eng-GB',1,238,202,'No comments on this one',0,0,0,0,'no comments on this one','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (437,'eng-GB',61,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (797,'eng-GB',2,231,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links for news site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (674,'eng-GB',1,200,188,'test',0,0,0,0,'test','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (641,'eng-GB',1,192,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (642,'eng-GB',1,193,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (643,'eng-GB',1,193,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (644,'eng-GB',1,193,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (645,'eng-GB',1,193,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (646,'eng-GB',1,194,188,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (647,'eng-GB',1,194,189,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (648,'eng-GB',1,194,190,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (649,'eng-GB',1,194,194,'',0,0,0,0,'','ezsubtreesubscription');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',70,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog.gif\"\n         original_filename=\"weblog.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069838835\">\n  <original attribute_id=\"152\"\n            attribute_version=\"69\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069838837\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069838837\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069843716\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',61,56,161,'blog_package',0,0,0,0,'blog_package','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',70,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',70,56,161,'blog_package',0,0,0,0,'blog_package','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (524,'eng-GB',3,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_me.\"\n         suffix=\"\"\n         basename=\"about_me\"\n         dirpath=\"var/blog/storage/images/about_me/524-3-eng-GB\"\n         url=\"var/blog/storage/images/about_me/524-3-eng-GB/about_me.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (766,'eng-GB',2,214,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>It is an old Volkswagen Beetle from 1982. It has a lot more charm that it cost me money. </paragraph>\n  <paragraph>I bought it from a friend for £30 and even got the old original wheels. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (765,'eng-GB',2,214,202,'Today I got my new car!',0,0,0,0,'today i got my new car!','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (151,'eng-GB',61,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (850,'eng-GB',1,247,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sdgj sdlgjsdkfjlgh sdfg sdfg</line>\n    <line>sdfg</line>\n    <line>sdg</line>\n    <line>sdf</line>\n    <line>gds</line>\n  </paragraph>\n  <paragraph>\n    <line>sdfgsdfgsd</line>\n    <line>fg</line>\n    <line>sdfg</line>\n    <line>sdf</line>\n    <line>gsd</line>\n    <line>fg</line>\n    <line>sdg</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (851,'eng-GB',1,247,213,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',65,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',65,56,161,'blog_package',0,0,0,0,'blog_package','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (437,'eng-GB',65,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (671,'eng-GB',65,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (825,'eng-GB',1,239,210,'nospam@ez.no',0,0,0,0,'nospam@ez.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (802,'eng-GB',1,233,210,'nospam@ez.no',0,0,0,0,'nospam@ez.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (803,'eng-GB',1,233,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (804,'eng-GB',1,233,212,'dfgl sdflg sdiofg usdoigfu osdigu iosdgf sdgfsd\nfg\nsdfg\nsdfg\nsdg',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (150,'eng-GB',65,56,157,'Blog test',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (151,'eng-GB',65,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (150,'eng-GB',62,56,157,'Blog',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',65,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"64\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_test_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069677595\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (437,'eng-GB',70,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (871,'eng-GB',2,231,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (896,'eng-GB',2,231,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (989,'eng-GB',1,255,204,'eZ systems',0,0,0,0,'ez systems','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (990,'eng-GB',1,255,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Creators of eZ publish content management system</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (991,'eng-GB',1,255,206,'',1,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (695,'eng-GB',4,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (707,'eng-GB',4,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (719,'eng-GB',4,14,199,'developer... ;)',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (731,'eng-GB',4,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (807,'eng-GB',1,234,206,'sina',3,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (806,'eng-GB',1,234,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A famous chinese news site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (805,'eng-GB',1,234,204,'Sina ',0,0,0,0,'sina','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (695,'eng-GB',5,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (707,'eng-GB',5,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (719,'eng-GB',5,14,199,'developer... ;)',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (731,'eng-GB',5,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"731\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"administrator_user_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (790,'eng-GB',1,228,4,'Links',0,0,0,0,'links','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (791,'eng-GB',1,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (792,'eng-GB',1,229,4,'Software',0,0,0,0,'software','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (793,'eng-GB',1,229,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links about software..</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (794,'eng-GB',1,230,4,'Movie',0,0,0,0,'movie','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (795,'eng-GB',1,230,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links to movie sites.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (798,'eng-GB',1,232,204,'VG',0,0,0,0,'vg','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (799,'eng-GB',1,232,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Know norwegian news from here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (800,'eng-GB',1,232,206,'VG',2,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (801,'eng-GB',1,233,209,'bård',0,0,0,0,'bård','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (788,'eng-GB',1,227,207,'Which color do you like?',0,0,0,0,'which color do you like?','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (789,'eng-GB',1,227,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name>Colors</name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Blue</option>\n    <option id=\"1\"\n            additional_price=\"\">Yellow</option>\n    <option id=\"2\"\n            additional_price=\"\">Red</option>\n    <option id=\"3\"\n            additional_price=\"\">Orange</option>\n    <option id=\"4\"\n            additional_price=\"\">Green</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (838,'eng-GB',1,244,202,'A Pirate\'s Life',0,0,0,0,'a pirate\'s life','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (839,'eng-GB',1,244,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Pirates of the Caribbean wasn&apos;t on my list of movies to see until I read what a few others were saying about it. Now I can&apos;t recommend it enough. This movie is an excellent 2.5 hours of pure escapism. Great sets, intriguing cinematography, fun characters, and a downright entertaining story. A good blend of action, humor, and a small dose of Disney-esque romance. As [Capt&apos;n!] Jack Sparrow, Johnny Depp gives us another brilliant performance, holding the audience with his charm and swagger throughout the film&apos;s entirety. He continues to impress me with his wide range of ability and the consistently strong characters of his recent career. And need I say what nice visuals Keira Knightley adds to this film?</paragraph>\n  <paragraph>Without giving anything away, my favorite parts of this movie were the over-obvious tie-ins with Disney&apos;s original ride. Anyone who remembers even a portion of the scenes which float by are bound to have a few chuckles over the way they&apos;re cleverly integrated into the movie. The pirates&apos; movements in and out of moonlight make for nicely added effects which I don&apos;t remember the ride revealing. (Note: The film is rated PG-13 for a reason: the pirates&apos; true identity and their violence may give small children nightmares, so think twice if ye have little ones.)</paragraph>\n  <paragraph>Aside from a few lengthy sword fights, I can&apos;t think of any reason not to say: you should run, not walk, to your nearest theater and see this movie as soon as possible. If you&apos;re not rambling out loud when you walk out of the theater, you&apos;ll at least be mumbling to yourself like a pirate, and hopefully you&apos;ll have had as good a time as I did.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (840,'eng-GB',1,244,213,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (834,'eng-GB',1,242,211,'fghvmbnmbvnm',0,0,0,0,'fghvmbnmbvnm','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (835,'eng-GB',1,242,212,'fgn fdgh fdgh fdgh\nkløæ\nølæ\nløæ\nløæ\nløæ\nhjlh\nhj',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (836,'eng-GB',1,243,4,'Entertainment',0,0,0,0,'entertainment','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (837,'eng-GB',1,243,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Music, books, film, television, shopping, travel, and many other fine escapes and vices. If it&apos;s downright fun, it probably belongs here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (826,'eng-GB',1,239,211,'sdfgsd',0,0,0,0,'sdfgsd','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (827,'eng-GB',1,239,212,'sdfgsdgsd\nsdg\nsdf',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (788,'eng-GB',2,227,207,'Which color do you like?',0,0,0,0,'which color do you like?','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (789,'eng-GB',2,227,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Blue</option>\n    <option id=\"1\"\n            additional_price=\"\">Yellow</option>\n    <option id=\"2\"\n            additional_price=\"\">Red</option>\n    <option id=\"3\"\n            additional_price=\"\">Orange</option>\n    <option id=\"4\"\n            additional_price=\"\">Green</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (830,'eng-GB',1,241,207,'Which one is the best of matrix movies?',0,0,0,0,'which one is the best of matrix movies?','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (831,'eng-GB',1,241,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Matrix</option>\n    <option id=\"1\"\n            additional_price=\"\">Matrix reloaded</option>\n    <option id=\"2\"\n            additional_price=\"\">Matrix revoluaton</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (832,'eng-GB',1,242,209,'ghghj',0,0,0,0,'ghghj','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (833,'eng-GB',1,242,210,'nospam@ez.no',0,0,0,0,'nospam@ez.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (776,'eng-GB',1,220,209,'båbåb',0,0,0,0,'båbåb','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (777,'eng-GB',1,220,210,'nospam@ez.no',0,0,0,0,'nospam@ez.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (778,'eng-GB',1,220,211,'http://piranha.no',0,0,0,0,'http://piranha.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (779,'eng-GB',1,220,212,'sdfgsd fgsdgsd\ngf\nsdfg\nsdfgdsg\nsdgf\n',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (786,'eng-GB',1,226,202,'For Posterity\'s Sake',0,0,0,0,'for posterity\'s sake','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (787,'eng-GB',1,226,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Seven years ago, yesterday, I packed up everything I owned, left many friends behind in San Diego, and moved to San Francisco, (where I knew absolutely no one) to start my job at HotWired on August 12, 1996. The Creative Director hired me as a junior designer, since I knew very little about design for the Web, despite the fact that I had more print design experience than almost every other designer there at the time.</paragraph>\n  <paragraph>Three months after starting the low-paying job, I was depressed, out of money, missed my friends in San Diego, and was convinced I had made the wrong move. I gave notice, told my landlord I was moving out, and arranged to go back to my old job at a design agency in San Diego. Two days before I was to leave HotWired, Jonathan Louie (Design Director at the time) convinced me otherwise, offering me a new position and my first promotion of many more to come. I stayed, and lost a girlfriend in San Diego as a result.</paragraph>\n  <paragraph>Fast-forward to the present?</paragraph>\n  <paragraph>In two days, it will have been 9 months since I left Wired to go out on my own. Friends who know the journey and my initial intimidation of taking the job at HotWired say to me, &quot;?and look where you are now.&quot;</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (761,'eng-GB',2,212,4,'Personal',0,0,0,0,'personal','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (762,'eng-GB',2,212,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A glimpse into the life of me.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (763,'eng-GB',2,213,4,'Computers',0,0,0,0,'computers','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (764,'eng-GB',2,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Computers, handhelds, electronic gadgets, and the software which connects them all. I&apos;m an early adopter, which means I often end up paying the price, in more than one way.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (988,'eng-GB',1,254,213,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429664\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429664\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (841,'eng-GB',1,245,209,'kjlh',0,0,0,0,'kjlh','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (842,'eng-GB',1,245,210,'kjh',0,0,0,0,'kjh','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (843,'eng-GB',1,245,211,'kjh',0,0,0,0,'kjh','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (844,'eng-GB',1,245,212,'kjlhkhkjhklhj',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (845,'eng-GB',1,246,209,'kjhkjh',0,0,0,0,'kjhkjh','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (846,'eng-GB',1,246,210,'nospam@ez.no',0,0,0,0,'nospam@ez.no','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (847,'eng-GB',1,246,211,'sdfgsdfg',0,0,0,0,'sdfgsdfg','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (848,'eng-GB',1,246,212,'sdfgsdfgsdfgds\nfgsd\nfg\nsdfg',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (849,'eng-GB',1,247,202,'I overslept today',0,0,0,0,'i overslept today','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (854,'eng-GB',4,1,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (855,'eng-GB',5,1,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (856,'eng-GB',6,1,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (857,'eng-GB',1,41,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (858,'eng-GB',1,42,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (859,'eng-GB',1,44,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (861,'eng-GB',2,46,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (865,'eng-GB',2,212,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (866,'eng-GB',1,213,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (867,'eng-GB',2,213,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (868,'eng-GB',1,228,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (869,'eng-GB',1,229,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (870,'eng-GB',1,230,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (874,'eng-GB',1,243,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (877,'eng-GB',4,1,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (878,'eng-GB',5,1,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (879,'eng-GB',6,1,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (880,'eng-GB',1,41,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (881,'eng-GB',1,42,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (882,'eng-GB',1,44,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (884,'eng-GB',2,46,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (889,'eng-GB',2,212,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (890,'eng-GB',1,213,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (891,'eng-GB',2,213,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (892,'eng-GB',1,228,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (894,'eng-GB',1,229,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (895,'eng-GB',1,230,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (899,'eng-GB',1,243,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (125,'eng-GB',4,49,4,'Blogs',0,0,0,0,'blogs','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (126,'eng-GB',4,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Parenthetical thoughts, concepts, and brainstorms</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (863,'eng-GB',4,49,214,'Excerpts from Recent Entries',0,0,0,0,'excerpts from recent entries','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (887,'eng-GB',4,49,215,'Entry Categories',0,0,0,0,'entry categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (902,'eng-GB',4,1,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (903,'eng-GB',5,1,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (904,'eng-GB',6,1,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (905,'eng-GB',1,41,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (906,'eng-GB',1,42,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (907,'eng-GB',1,44,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (909,'eng-GB',2,46,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (913,'eng-GB',4,49,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (915,'eng-GB',2,212,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (916,'eng-GB',1,213,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (917,'eng-GB',2,213,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (918,'eng-GB',1,228,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (921,'eng-GB',1,229,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (922,'eng-GB',1,230,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (926,'eng-GB',1,243,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (790,'eng-GB',4,228,4,'Links',0,0,0,0,'links','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (791,'eng-GB',4,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Worthwhile websites, weblogs, journals, news pubs, and the like</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (920,'eng-GB',4,228,216,'Link Archive',0,0,0,0,'link archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (868,'eng-GB',4,228,214,'Recent Links',0,0,0,0,'recent links','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (893,'eng-GB',4,228,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (125,'eng-GB',5,49,4,'Blogs',0,0,0,0,'blogs','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (126,'eng-GB',5,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Parenthetical thoughts, concepts, and brainstorms</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (913,'eng-GB',5,49,216,'Log Archive',0,0,0,0,'log archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (863,'eng-GB',5,49,214,'Excerpts from Recent Entries',0,0,0,0,'excerpts from recent entries','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (887,'eng-GB',5,49,215,'Entry Categories',0,0,0,0,'entry categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',61,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (828,'eng-GB',3,240,4,'Polls',0,0,0,0,'polls','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (829,'eng-GB',3,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (925,'eng-GB',3,240,216,'Poll Archive',0,0,0,0,'poll archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (873,'eng-GB',3,240,214,'Recent polls',0,0,0,0,'recent polls','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (898,'eng-GB',3,240,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',62,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"61\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069429748\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (796,'eng-GB',2,231,4,'Downloads',0,0,0,0,'downloads','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (151,'eng-GB',70,56,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (150,'eng-GB',70,56,157,'Blog',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (956,'eng-GB',1,248,209,'ete',0,0,0,0,'ete','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (957,'eng-GB',1,248,210,'ete',0,0,0,0,'ete','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (958,'eng-GB',1,248,211,'ete',0,0,0,0,'ete','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (959,'eng-GB',1,248,212,'te',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (150,'eng-GB',64,56,157,'Blog test',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (151,'eng-GB',64,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',64,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"63\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',64,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',64,56,161,'blog_package',0,0,0,0,'blog_package','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (437,'eng-GB',64,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (671,'eng-GB',64,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (522,'eng-GB',3,161,140,'About me',0,0,0,0,'about me','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (523,'eng-GB',3,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Anyway: this is me:</paragraph>\n  <paragraph>\n    <line>To short</line>\n    <line>To young</line>\n    <line>To smart</line>\n    <line>Not smart enough</line>\n    <line>Pimples</line>\n    <line>Boy</line>\n    <line>Totally girl addicted</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (671,'eng-GB',61,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (994,'eng-GB',1,256,206,'',6,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1003,'eng-GB',1,259,204,'Lord of the Rings',0,0,0,0,'lord of the rings','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1004,'eng-GB',1,259,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Can&apos;t wait for number three even if I know what will happen</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1005,'eng-GB',1,259,206,'',8,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1006,'eng-GB',1,260,4,'Sports',0,0,0,0,'sports','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1007,'eng-GB',1,260,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1008,'eng-GB',1,260,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1009,'eng-GB',1,260,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1010,'eng-GB',1,260,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1011,'eng-GB',1,261,204,'Liverpool FC',0,0,0,0,'liverpool fc','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1012,'eng-GB',1,261,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The best team ever</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1013,'eng-GB',1,261,206,'',9,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1014,'eng-GB',1,262,207,'Which do you like the best: Matrix or Lord of the Rings?',0,0,0,0,'which do you like the best: matrix or lord of the','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1015,'eng-GB',1,262,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Matrix</option>\n    <option id=\"1\"\n            additional_price=\"\">Lord of the Rings </option>\n    <option id=\"2\"\n            additional_price=\"\">None</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1016,'eng-GB',1,263,202,'Party!',0,0,0,0,'party!','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1017,'eng-GB',1,263,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>I was invited to a party at a friends house this weekend. And I was told that the girl of my dreams will also be there. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1018,'eng-GB',1,263,213,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1019,'eng-GB',1,264,4,'Fun',0,0,0,0,'fun','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1020,'eng-GB',1,264,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1021,'eng-GB',1,264,216,'Item Archive',0,0,0,0,'item archive','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1022,'eng-GB',1,264,214,'Recent items',0,0,0,0,'recent items','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1023,'eng-GB',1,264,215,'All Categories',0,0,0,0,'all categories','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1024,'eng-GB',1,265,204,'Pondus',0,0,0,0,'pondus','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1025,'eng-GB',1,265,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Have you ever discovered the football fanatic? He is available in several languages but this site is in Norwegian only.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1026,'eng-GB',1,265,206,'',10,0,0,0,'','ezurl');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1027,'eng-GB',1,266,209,'Tim',0,0,0,0,'tim','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1028,'eng-GB',1,266,210,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1029,'eng-GB',1,266,211,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1030,'eng-GB',1,266,212,'When will you share it with the rest of us?',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1031,'eng-GB',1,267,209,'Helene',0,0,0,0,'helene','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1032,'eng-GB',1,267,210,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1033,'eng-GB',1,267,211,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1034,'eng-GB',1,267,212,'Can I come? What\'s the address?',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (671,'eng-GB',70,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1035,'eng-GB',1,268,6,'Anonymous Users',0,0,0,0,'anonymous users','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1036,'eng-GB',1,268,7,'User group for the anonymous user',0,0,0,0,'user group for the anonymous user','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (19,'eng-GB',2,10,8,'Anonymous',0,0,0,0,'anonymous','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (20,'eng-GB',2,10,9,'User',0,0,0,0,'user','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (21,'eng-GB',2,10,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (692,'eng-GB',2,10,197,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (704,'eng-GB',2,10,198,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (716,'eng-GB',2,10,199,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (728,'eng-GB',2,10,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"anonymous_user.\"\n         suffix=\"\"\n         basename=\"anonymous_user\"\n         dirpath=\"var/storage/images/users/anonymous_users/anonymous_user/728-2-eng-GB\"\n         url=\"var/storage/images/users/anonymous_users/anonymous_user/728-2-eng-GB/anonymous_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1072180557\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');





CREATE TABLE ezcontentobject_link (
  id int(11) NOT NULL auto_increment,
  from_contentobject_id int(11) NOT NULL default '0',
  from_contentobject_version int(11) NOT NULL default '0',
  to_contentobject_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezcontentobject_link (id, from_contentobject_id, from_contentobject_version, to_contentobject_id) VALUES (1,1,4,49);
INSERT INTO ezcontentobject_link (id, from_contentobject_id, from_contentobject_version, to_contentobject_id) VALUES (4,1,5,49);





CREATE TABLE ezcontentobject_name (
  contentobject_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  content_version int(11) NOT NULL default '0',
  content_translation varchar(20) NOT NULL default '',
  real_translation varchar(20) default NULL,
  PRIMARY KEY  (contentobject_id,content_version,content_translation)
) TYPE=MyISAM;






INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Root folder',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (4,'Users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (10,'Anonymous User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (11,'Guest accounts',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (12,'Administrator users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (13,'Editors',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14,'Administrator User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (233,'bård',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (41,'Media',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (42,'Setup',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (44,'Setup links',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Setup Objects',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (46,'Fonts and colors',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (46,'Look and feel',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (47,'New Template look',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (116,'URL translator',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (126,'New Article',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (49,'News',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',37,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (235,'kjh',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Look and feel',7,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (51,'New Setup link',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Look and feel',8,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (53,'New Template look',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',8,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (236,'Soft house',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (237,'New big discovery today',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',36,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (161,'About this forum',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranetyy',30,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',25,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',24,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (127,'New Article',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',22,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',23,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',35,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (122,'New Image',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Look and feel',9,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',7,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',8,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',9,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',38,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',10,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (83,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (84,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',11,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (85,'New Folder',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (87,'New Company',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (88,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',33,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranetyy',31,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',32,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',12,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',13,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (91,'New Template look',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',18,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (214,'Today I got my new car!',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (215,'Special things happened today',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',39,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (96,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (213,'Computers',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (234,'Sina',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',34,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',20,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (212,'Personal',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (103,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (104,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (240,'Polls',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (105,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (106,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Corporate',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Setup Objects',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',7,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Setup Objects',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (115,'Cache',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Setup Objects',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (116,'URL translator',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (117,'New Article',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Look and feel',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Look and feel',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',19,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (115,'Cache',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',21,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (115,'Cache',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',26,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranetyy',27,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranetyy',28,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (129,'New Article',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranetyy',29,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',41,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',42,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',40,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Forum',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Forum',45,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (240,'Polls',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (227,'Which color do you like?',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (221,'New Poll',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (143,'New Setup link',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (144,'New Setup link',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (145,'New Setup link',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (239,'Bård',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Forum',44,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (216,'New Poll',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (49,'Blogs',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14,'Administrator User',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (238,'No comments on this one',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (176,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (177,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (178,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (179,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (180,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (181,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (182,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (183,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (184,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (185,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (186,'New Forum topic',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (187,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (189,'test2 test2',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (191,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (192,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (193,'',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (194,'New Forum topic',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (228,'Links',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Forum',46,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (231,'News',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (200,'test',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (201,'Re:test',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (232,'VG',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (227,'Which color do you like?',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (226,'For Posterity\'s Sake',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (212,'Personal',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (213,'Computers',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14,'Administrator User',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14,'Administrator User',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (230,'Movie',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (229,'Software',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Forum',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Forum',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (224,'New Poll',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14,'Administrator User',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (222,'New Poll',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (225,'New Poll',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (218,'New Poll',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (219,'Bård Farstad',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (220,'båbåb',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (217,'New Poll',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Blog',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (161,'About me',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (241,'Which one is the best of matrix movies?',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (242,'ghghj',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (243,'Entertainment',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (244,'A Pirate\'s Life',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',43,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',47,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (115,'Cache',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Classes',9,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Look and feel',10,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (116,'URL translator',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (245,'kjlh',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',48,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (246,'kjhkjh',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',49,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (247,'I overslept today',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (228,'Links',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (49,'Blogs',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (228,'Links',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (49,'Blogs',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (228,'Links',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (49,'Blogs',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',50,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',51,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (240,'Polls',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',52,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',55,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',56,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',57,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',58,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',59,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',60,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (248,'ete',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog test',63,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (161,'About me',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',61,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',62,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog test',64,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog test',65,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',66,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',67,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',68,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (214,'Today I got my new car!',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (252,'I overslept again',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (253,'Tonight I was at the movies',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (254,'Finally I got it',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (231,'Downloads',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (255,'eZ systems',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (256,'eZ publish at Freshmeat',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (257,'Movies',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (258,'The Matrix',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (259,'Lord of the Rings',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (260,'Sports',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (261,'Liverpool FC',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (241,'Which one is the best of matrix movies?',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (262,'Which do you like the best: Matrix or Lord of the Rings?',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (263,'Party!',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (264,'Fun',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (265,'Pondus',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (266,'Tim',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (267,'Helene',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',69,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Blog',70,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (268,'Anonymous Users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (10,'Anonymous User',2,'eng-GB','eng-GB');





CREATE TABLE ezcontentobject_tree (
  node_id int(11) NOT NULL auto_increment,
  parent_node_id int(11) NOT NULL default '0',
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  contentobject_is_published int(11) default NULL,
  depth int(11) NOT NULL default '0',
  path_string varchar(255) NOT NULL default '',
  sort_field int(11) default '1',
  sort_order int(1) default '1',
  priority int(11) NOT NULL default '0',
  path_identification_string text,
  main_node_id int(11) default NULL,
  PRIMARY KEY  (node_id),
  KEY ezcontentobject_tree_path (path_string),
  KEY ezcontentobject_tree_p_node_id (parent_node_id),
  KEY ezcontentobject_tree_co_id (contentobject_id),
  KEY ezcontentobject_tree_depth (depth)
) TYPE=MyISAM;






INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (2,1,1,6,1,1,'/1/2/',9,1,0,'',2);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (5,1,4,1,0,1,'/1/5/',1,1,0,'users',5);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (11,199,10,2,1,3,'/1/5/199/11/',9,1,0,'users/anonymous_users/anonymous_user',11);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (15,13,14,5,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (43,1,41,1,1,1,'/1/43/',9,1,0,'media',43);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (45,46,43,9,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (47,46,45,10,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (50,2,49,5,1,2,'/1/2/50/',9,1,0,'blogs',50);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (54,48,56,70,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/blog',54);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (153,50,212,2,1,3,'/1/2/50/153/',9,1,0,'blogs/personal',153);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (127,2,161,3,1,2,'/1/2/127/',9,1,0,'about_me',127);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (154,50,213,2,1,3,'/1/2/50/154/',9,1,0,'blogs/computers',154);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (95,46,115,4,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (96,46,116,3,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (184,176,253,1,1,4,'/1/2/50/176/184/',9,1,0,'blogs/entertainment/tonight_i_was_at_the_movies',184);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (176,50,243,1,1,3,'/1/2/50/176/',9,1,0,'blogs/entertainment',176);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (185,154,254,1,1,4,'/1/2/50/154/185/',9,1,0,'blogs/computers/finally_i_got_it',185);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (198,194,267,1,1,5,'/1/2/50/153/194/198/',1,1,0,'blogs/personal/party/helene',198);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (174,173,241,1,1,3,'/1/2/173/174/',9,1,1,'polls/which_one_is_the_best_of_matrix_movies',174);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (173,2,240,3,1,2,'/1/2/173/',8,1,0,'polls',173);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (190,188,259,1,1,4,'/1/2/161/188/190/',9,1,0,'links/movies/lord_of_the_rings',190);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (191,161,260,1,1,3,'/1/2/161/191/',9,1,0,'links/sports',191);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (192,191,261,1,1,4,'/1/2/161/191/192/',9,1,0,'links/sports/liverpool_fc',192);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (193,173,262,1,1,3,'/1/2/173/193/',9,1,0,'polls/which_do_you_like_the_best_matrix_or_lord_of_the_rings',193);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (194,153,263,1,1,4,'/1/2/50/153/194/',9,1,0,'blogs/personal/party',194);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (186,164,255,1,1,4,'/1/2/161/164/186/',9,1,0,'links/downloads/ez_systems',186);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (187,164,256,1,1,4,'/1/2/161/164/187/',9,1,0,'links/downloads/ez_publish_at_freshmeat',187);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (164,161,231,2,1,3,'/1/2/161/164/',9,1,0,'links/downloads',164);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (188,161,257,1,1,3,'/1/2/161/188/',9,1,0,'links/movies',188);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (189,188,258,1,1,4,'/1/2/161/188/189/',9,1,0,'links/movies/the_matrix',189);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (195,161,264,1,1,3,'/1/2/161/195/',9,1,0,'links/fun',195);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (161,2,228,4,1,2,'/1/2/161/',9,1,0,'links',161);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (183,153,252,1,1,4,'/1/2/50/153/183/',9,1,0,'blogs/personal/i_overslept_again',183);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (196,195,265,1,1,4,'/1/2/161/195/196/',9,1,0,'links/fun/pondus',196);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (197,185,266,1,1,5,'/1/2/50/154/185/197/',1,1,0,'blogs/computers/finally_i_got_it/tim',197);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (155,153,214,2,1,4,'/1/2/50/153/155/',9,1,0,'blogs/personal/today_i_got_my_new_car',155);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (199,5,268,1,1,2,'/1/5/199/',9,1,0,'users/anonymous_users',199);





CREATE TABLE ezcontentobject_version (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) default NULL,
  creator_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  status int(11) NOT NULL default '0',
  workflow_event_pos int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (804,1,14,6,1068710443,1068710484,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (4,4,14,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (439,11,14,1,1033920737,1033920746,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (440,12,14,1,1033920760,1033920775,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (441,13,14,1,1033920786,1033920794,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (831,235,14,1,1068718746,1068718760,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (472,41,14,1,1060695450,1060695457,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (473,42,14,1,1066383039,1066383068,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (475,44,14,1,1066384403,1066384457,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (478,43,14,4,1066388115,1066388183,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (479,43,14,5,1066388613,1066388707,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (482,46,14,2,1066389882,1066389902,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (896,260,14,1,1069771033,1069771040,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (631,45,14,7,1067002652,1067002675,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (895,259,14,1,1069770938,1069770983,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (832,236,14,1,1068718999,1068719250,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (833,237,14,1,1068718996,1068719051,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (683,45,14,9,1067950316,1067950326,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (682,43,14,8,1067950294,1067950307,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (885,214,14,2,1069769855,1069770036,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (881,56,14,65,1069677489,1069677521,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (880,56,14,64,1069676476,1069676517,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (894,258,14,1,1069770876,1069770910,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (619,45,14,6,1066995597,1066996371,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (877,56,14,61,1069414825,1069414843,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (842,244,14,1,1068727900,1068727917,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (845,43,14,9,1068729346,1068729356,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (809,215,14,1,1068713628,1068713677,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (906,56,14,70,1069839457,1069839484,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (878,56,14,62,1069429714,1069429727,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (632,45,14,8,1067002781,1067002791,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (835,239,14,1,1068719292,1068719374,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (834,238,14,1,1068719106,1068719128,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (609,43,14,6,1066989725,1066989762,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (611,43,14,7,1066989980,1066990055,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (614,45,14,4,1066991894,1066991945,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (616,45,14,5,1066992186,1066992656,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (848,245,14,1,1068730464,1068730475,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (844,115,14,4,1068729296,1068729308,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (813,219,14,1,1068716892,1068716920,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (893,257,14,1,1069770837,1069770849,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (892,256,14,1,1069770742,1069770809,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (891,255,14,1,1069770648,1069770690,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (840,242,14,1,1068720892,1068720915,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (841,243,14,1,1068727844,1068727871,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (890,231,14,2,1069770628,1069770638,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (807,213,14,1,1068711079,1068711091,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (839,241,14,1,1068720708,1068720802,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (837,227,14,2,1068719654,1068719695,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (742,176,149,1,1068108624,1068108805,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (743,177,149,1,1068108805,1068108834,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (744,178,149,1,1068108834,1068108898,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (745,179,149,1,1068108898,1068109016,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (746,180,149,1,1068109016,1068109220,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (747,181,149,1,1068109220,1068109255,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (748,182,149,1,1068109255,1068109498,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (749,183,149,1,1068109498,1068109663,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (750,184,149,1,1068109663,1068109781,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (751,185,149,1,1068109781,1068109829,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (752,186,149,1,1068109829,1068109829,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (889,161,14,3,1069770402,1069770484,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (888,254,14,1,1069770267,1069770356,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (758,191,149,1,1068111317,1068111376,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (759,192,149,1,1068111376,1068111870,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (760,193,149,1,1068111870,1068111917,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (761,194,149,1,1068111917,1068111917,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (825,229,14,1,1068718643,1068718672,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (830,234,14,1,1068718886,1068718957,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (829,233,14,1,1068718686,1068718705,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (769,200,149,1,1068120480,1068120496,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (770,201,149,1,1068120737,1068120756,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (824,228,14,1,1068718602,1068718628,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (887,253,14,1,1069770176,1069770254,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (819,213,14,2,1068717682,1068717696,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (818,212,14,2,1068717602,1068717667,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (828,232,14,1,1068718770,1068718860,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (886,252,14,1,1069770048,1069770140,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (879,56,14,63,1069676374,1069676432,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (823,227,14,1,1068717981,1068718128,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (822,226,14,1,1068717922,1068717934,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (826,230,14,1,1068718683,1068718712,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (792,1,14,4,1068212220,1068212328,3,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (793,1,14,5,1068212545,1068212663,3,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (794,14,14,4,1068213048,1068213064,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (796,14,14,5,1068468183,1068468218,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (814,220,14,1,1068716938,1068716967,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (847,116,14,3,1068729385,1068729395,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (852,247,14,1,1068796274,1068796296,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (850,246,14,1,1068737185,1068737197,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (846,45,14,10,1068729368,1068729376,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (872,248,10,1,1069408745,1069408767,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (856,49,14,4,1068804018,1068804028,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (857,228,14,4,1068804664,1068804675,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (858,49,14,5,1068804682,1068804689,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (861,240,14,3,1069172829,1069172878,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (897,261,14,1,1069771057,1069771089,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (899,262,14,1,1069771197,1069771243,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (900,263,14,1,1069771432,1069771496,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (901,264,14,1,1069773530,1069773539,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (902,265,14,1,1069773548,1069773629,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (903,266,10,1,1069773747,1069773783,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (904,267,10,1,1069773805,1069773826,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (907,268,14,1,1072180537,1072180551,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (908,10,14,2,1072180557,1072180618,1,0,0);





CREATE TABLE ezdiscountrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezdiscountsubrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  discountrule_id int(11) NOT NULL default '0',
  discount_percent float default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezdiscountsubrule_value (
  discountsubrule_id int(11) NOT NULL default '0',
  value int(11) NOT NULL default '0',
  issection int(1) NOT NULL default '0',
  PRIMARY KEY  (discountsubrule_id,value,issection)
) TYPE=MyISAM;











CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumid int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid),
  KEY ezenumobjectvalue_co_attr_id_co_attr_ver (contentobject_attribute_id,contentobject_attribute_version)
) TYPE=MyISAM;











CREATE TABLE ezenumvalue (
  id int(11) NOT NULL auto_increment,
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,contentclass_attribute_id,contentclass_attribute_version),
  KEY ezenumvalue_co_cl_attr_id_co_class_att_ver (contentclass_attribute_id,contentclass_attribute_version)
) TYPE=MyISAM;











CREATE TABLE ezforgot_password (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  hash_key varchar(32) NOT NULL default '',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezgeneral_digest_user_settings (
  id int(11) NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  receive_digest int(11) NOT NULL default '0',
  digest_type int(11) NOT NULL default '0',
  day varchar(255) NOT NULL default '',
  time varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezimage (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  alternative_text varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;






INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (103,4,'phpWJgae7.png','kaddressbook.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (103,5,'php7ZhvcB.png','chardevice.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (109,1,'phpvzmRGW.png','folder_txt.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (120,11,'phpG6qloJ.gif','ezpublish_logo_blue.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,15,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (120,13,'phpG6qloJ.gif','ezpublish_logo_blue.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,12,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,13,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,11,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,16,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,7,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,18,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,9,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,10,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,14,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (152,17,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (268,1,'php8lV61b.png','phphWMyJs.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (268,2,'php8lV61b.png','phphWMyJs.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (287,1,'phpjqUhJn.jpg','017_8_1small.jpg','image/jpeg','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (292,2,'phpCKfj8I.png','phpCG9Rrg_600x600_97870.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (293,2,'php2e1GsG.png','bj.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (293,3,'php2e1GsG.png','bj.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (103,6,'phpXz5esv.jpg','TN_a5.JPG','image/jpeg','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (109,2,'phppIJtoa.jpg','maidinmanhattantop.jpg','image/jpeg','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (103,7,'phpG0YSsD.png','gnome-settings.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (109,3,'phpAhcEu9.png','gnome-favorites.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (324,1,'php4sHmOe.png','gnome-ccperiph.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (109,4,'phpbVfzkm.png','gnome-devel.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (328,1,'php7a7vQE.png','gnome-globe.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (109,5,'phpvs7kFg.png','gnome-color-browser.png','image/png','');
INSERT INTO ezimage (contentobject_attribute_id, version, filename, original_filename, mime_type, alternative_text) VALUES (400,2,'phprwazbD.jpg','vbanner.jpg','image/jpeg','');





CREATE TABLE ezimagefile (
  id int(11) NOT NULL auto_increment,
  contentobject_attribute_id int(11) NOT NULL default '0',
  filepath text NOT NULL,
  PRIMARY KEY  (id),
  KEY ezimagefile_coid (contentobject_attribute_id),
  KEY ezimagefile_file (filepath(200))
) TYPE=MyISAM;






INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (43,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (42,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (41,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (5,152,'var/blog/storage/images/setup/look_and_feel/blog/152-52-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (6,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (7,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (8,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (9,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (10,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (11,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (12,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (13,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (14,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (15,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (16,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (17,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (18,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (19,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (20,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (21,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (22,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (23,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (24,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (25,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (26,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (27,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (28,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (29,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (30,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (31,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (32,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (33,103,'var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (34,103,'var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (35,109,'var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (36,109,'var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (37,324,'var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (38,324,'var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (39,328,'var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (40,328,'var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (44,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (45,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (46,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (47,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (48,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (49,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (50,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (51,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (52,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (53,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (54,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (55,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (56,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (57,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (58,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (59,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (60,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (61,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (62,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (63,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (64,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (65,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (66,524,'var/blog/storage/images/about_me/524-3-eng-GB/about_me.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (68,152,'var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (69,152,'var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (70,152,'var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (71,152,'var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (72,152,'var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (73,152,'var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (74,152,'var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (75,152,'var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (76,728,'var/storage/images/users/anonymous_users/anonymous_user/728-2-eng-GB/anonymous_user.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (77,109,'var/storage/original/image/phpvzmRGW.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (78,109,'var/storage/original/image/phppIJtoa.jpg');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (79,109,'var/storage/original/image/phpAhcEu9.png');





CREATE TABLE ezimagevariation (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  additional_path varchar(255) default NULL,
  requested_width int(11) NOT NULL default '0',
  requested_height int(11) NOT NULL default '0',
  width int(11) NOT NULL default '0',
  height int(11) NOT NULL default '0',
  PRIMARY KEY  (contentobject_attribute_id,version,requested_width,requested_height)
) TYPE=MyISAM;






INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (103,4,'phpWJgae7_100x100_103.png','p/h/p',100,100,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (103,4,'phpWJgae7_600x600_103.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (103,5,'php7ZhvcB_100x100_103.png','p/h/p',100,100,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (109,1,'phpvzmRGW_100x100_109.png','p/h/p',100,100,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (103,5,'php7ZhvcB_600x600_103.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (109,1,'phpvzmRGW_600x600_109.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (293,2,'php2e1GsG_600x600_293.png','p/h/p',600,600,186,93);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (120,11,'phpG6qloJ_100x100_120.gif.png','p/h/p',100,100,100,16);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (292,2,'phpCKfj8I_600x600_292.png','p/h/p',600,600,186,93);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (152,13,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (293,2,'php2e1GsG_100x100_293.png','p/h/p',100,100,100,50);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (120,11,'phpG6qloJ_600x600_120.gif.png','p/h/p',600,600,129,21);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (152,12,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (152,11,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (292,2,'phpCKfj8I_100x100_292.png','p/h/p',100,100,100,50);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (287,1,'phpjqUhJn_100x100_287.jpg','p/h/p',100,100,73,100);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (268,2,'php8lV61b_100x100_268.png','p/h/p',100,100,100,93);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (268,1,'php8lV61b_150x150_268.png','p/h/p',150,150,144,134);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (152,16,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (152,7,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (268,1,'php8lV61b_100x100_268.png','p/h/p',100,100,100,93);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (152,9,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (152,10,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (293,2,'php2e1GsG_150x150_293.png','p/h/p',150,150,150,75);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (292,2,'phpCKfj8I_150x150_292.png','p/h/p',150,150,150,75);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (293,3,'php2e1GsG_100x100_293.png','p/h/p',100,100,100,50);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (103,6,'phpXz5esv_600x600_103.jpg','p/h/p',600,600,377,600);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (109,2,'phppIJtoa_600x600_109.jpg','p/h/p',600,600,116,61);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (103,7,'phpG0YSsD_600x600_103.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (109,3,'phpAhcEu9_600x600_109.png','p/h/p',600,600,48,52);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (324,1,'php4sHmOe_600x600_324.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (109,4,'phpbVfzkm_600x600_109.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (328,1,'php7a7vQE_600x600_328.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (109,5,'phpvs7kFg_600x600_109.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (268,2,'php8lV61b_150x150_268.png','p/h/p',150,150,144,134);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (103,7,'phpG0YSsD_150x150_103.png','p/h/p',150,150,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (109,5,'phpvs7kFg_150x150_109.png','p/h/p',150,150,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (324,1,'php4sHmOe_150x150_324.png','p/h/p',150,150,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (328,1,'php7a7vQE_150x150_328.png','p/h/p',150,150,48,48);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (400,2,'phprwazbD_100x100_400.jpg','p/h/p',100,100,100,33);
INSERT INTO ezimagevariation (contentobject_attribute_id, version, filename, additional_path, requested_width, requested_height, width, height) VALUES (400,2,'phprwazbD_600x600_400.jpg','p/h/p',600,600,450,150);





CREATE TABLE ezinfocollection (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  user_identifier varchar(34) default NULL,
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezinfocollection_attribute (
  id int(11) NOT NULL auto_increment,
  informationcollection_id int(11) NOT NULL default '0',
  data_text text,
  data_int int(11) default NULL,
  data_float float default NULL,
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezkeyword (
  id int(11) NOT NULL auto_increment,
  keyword varchar(255) default NULL,
  class_id int(11) NOT NULL default '0',
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
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  width int(11) default NULL,
  height int(11) default NULL,
  has_controller int(1) default NULL,
  is_autoplay int(1) default NULL,
  pluginspage varchar(255) default NULL,
  quality varchar(50) default NULL,
  controls varchar(50) default NULL,
  is_loop int(1) default NULL,
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;











CREATE TABLE ezmessage (
  id int(11) NOT NULL auto_increment,
  send_method varchar(50) NOT NULL default '',
  send_weekday varchar(50) NOT NULL default '',
  send_time varchar(50) NOT NULL default '',
  destination_address varchar(50) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  body text,
  is_sent int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezmodule_run (
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) default NULL,
  module_name varchar(255) default NULL,
  function_name varchar(255) default NULL,
  module_data text,
  PRIMARY KEY  (id),
  UNIQUE KEY ezmodule_run_workflow_process_id_s (workflow_process_id)
) TYPE=MyISAM;











CREATE TABLE eznode_assignment (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  parent_node int(11) default NULL,
  sort_field int(11) default '1',
  sort_order int(1) default '1',
  is_main int(11) NOT NULL default '0',
  from_node_id int(11) default '0',
  remote_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (510,1,6,1,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (4,8,2,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (144,4,1,1,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (522,227,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (593,257,1,161,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (148,9,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (150,11,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (151,12,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (152,13,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (603,266,1,185,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (182,41,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (183,42,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (185,44,1,44,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (189,43,4,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (190,43,5,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (193,46,2,44,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (334,45,7,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (581,56,65,48,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (541,243,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (586,252,1,153,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (386,45,9,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (385,43,8,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (513,213,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (580,56,64,48,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (321,45,6,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (577,56,61,48,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (556,49,4,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (544,115,4,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (589,161,3,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (606,56,70,48,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (545,43,9,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (335,45,8,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (578,56,62,48,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (546,45,10,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (588,254,1,154,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (311,43,6,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (313,43,7,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (316,45,4,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (318,45,5,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (561,240,3,2,8,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (519,212,2,50,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (579,56,63,48,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (600,263,1,153,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (587,253,1,176,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (547,116,3,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (446,176,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (447,177,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (448,178,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (449,179,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (450,180,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (451,181,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (452,182,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (453,183,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (454,184,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (455,185,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (456,186,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (462,191,1,115,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (463,192,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (464,193,1,2,1,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (465,194,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (601,264,1,161,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (539,241,1,173,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (595,259,1,188,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (473,200,1,114,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (474,201,1,135,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (602,265,1,195,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (591,255,1,164,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (597,261,1,191,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (590,231,2,161,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (592,256,1,164,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (594,258,1,188,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (596,260,1,161,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (604,267,1,194,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (496,1,4,1,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (497,1,5,1,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (498,14,4,13,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (523,228,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (500,14,5,13,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (520,213,2,50,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (558,49,5,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (585,214,2,153,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (557,228,4,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (599,262,1,173,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (607,268,1,5,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (609,10,2,199,9,1,1,5,0);





CREATE TABLE eznotificationcollection (
  id int(11) NOT NULL auto_increment,
  event_id int(11) NOT NULL default '0',
  handler varchar(255) NOT NULL default '',
  transport varchar(255) NOT NULL default '',
  data_subject text NOT NULL,
  data_text text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE eznotificationcollection_item (
  id int(11) NOT NULL auto_increment,
  collection_id int(11) NOT NULL default '0',
  event_id int(11) NOT NULL default '0',
  address varchar(255) NOT NULL default '',
  send_date int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE eznotificationevent (
  id int(11) NOT NULL auto_increment,
  status int(11) NOT NULL default '0',
  event_type_string varchar(255) NOT NULL default '',
  data_int1 int(11) NOT NULL default '0',
  data_int2 int(11) NOT NULL default '0',
  data_int3 int(11) NOT NULL default '0',
  data_int4 int(11) NOT NULL default '0',
  data_text1 text NOT NULL,
  data_text2 text NOT NULL,
  data_text3 text NOT NULL,
  data_text4 text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezoperation_memento (
  id int(11) NOT NULL auto_increment,
  memento_key varchar(32) NOT NULL default '',
  memento_data text NOT NULL,
  main int(11) NOT NULL default '0',
  main_key varchar(32) NOT NULL default '',
  PRIMARY KEY  (id,memento_key),
  KEY ezoperation_memento_memento_key_main (memento_key,main)
) TYPE=MyISAM;











CREATE TABLE ezorder (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  is_temporary int(11) NOT NULL default '1',
  order_nr int(11) NOT NULL default '0',
  data_text_2 text,
  data_text_1 text,
  account_identifier varchar(100) NOT NULL default 'default',
  ignore_vat int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezorder_item (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  description varchar(255) default NULL,
  price float default NULL,
  vat_value int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezorder_item_order_id (order_id)
) TYPE=MyISAM;











CREATE TABLE ezpdf_export (
  id int(11) NOT NULL auto_increment,
  title varchar(255) default NULL,
  show_frontpage int(11) default NULL,
  intro_text text,
  sub_text text,
  source_node_id int(11) default NULL,
  export_structure varchar(255) default NULL,
  export_classes varchar(255) default NULL,
  site_access varchar(255) default NULL,
  pdf_filename varchar(255) default NULL,
  modifier_id int(11) default NULL,
  modified int(11) default NULL,
  created int(11) default NULL,
  creator_id int(11) default NULL,
  status int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezpolicy (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (308,2,'*','*','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (341,8,'read','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (388,1,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (387,1,'login','user','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (389,1,'create','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (390,1,'edit','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (391,1,'versionread','content','');





CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  policy_id int(11) default NULL,
  identifier varchar(255) NOT NULL default '',
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (306,388,'Class',0,'read','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (307,389,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (308,390,'Class',0,'edit','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (309,391,'Class',0,'versionread','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (310,391,'Owner',0,'versionread','content');





CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (633,306,'5');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (632,306,'26');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (631,306,'25');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (630,306,'24');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (629,306,'23');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (628,306,'2');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (627,306,'12');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (626,306,'10');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (625,306,'1');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (634,307,'26');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (635,308,'26');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (636,309,'26');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (637,310,'1');





CREATE TABLE ezpreferences (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  name varchar(100) default NULL,
  value varchar(100) default NULL,
  PRIMARY KEY  (id),
  KEY ezpreferences_name (name)
) TYPE=MyISAM;











CREATE TABLE ezproductcollection (
  id int(11) NOT NULL auto_increment,
  created int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezproductcollection_item (
  id int(11) NOT NULL auto_increment,
  productcollection_id int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  item_count int(11) NOT NULL default '0',
  price double default NULL,
  is_vat_inc int(11) default NULL,
  vat_value float default NULL,
  discount float default NULL,
  PRIMARY KEY  (id),
  KEY ezproductcollection_item_productcollection_id (productcollection_id),
  KEY ezproductcollection_item_contentobject_id (productcollection_id)
) TYPE=MyISAM;











CREATE TABLE ezproductcollection_item_opt (
  id int(11) NOT NULL auto_increment,
  item_id int(11) NOT NULL default '0',
  option_item_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  value varchar(255) NOT NULL default '',
  price float NOT NULL default '0',
  object_attribute_id int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ezproductcollection_item_opt_item_id (item_id)
) TYPE=MyISAM;











CREATE TABLE ezrole (
  id int(11) NOT NULL auto_increment,
  version int(11) default '0',
  name varchar(255) NOT NULL default '',
  value char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezrole (id, version, name, value) VALUES (1,0,'Anonymous','');
INSERT INTO ezrole (id, version, name, value) VALUES (2,0,'Administrator','*');
INSERT INTO ezrole (id, version, name, value) VALUES (8,0,'Guest',NULL);





CREATE TABLE ezrss_export (
  id int(11) NOT NULL auto_increment,
  title varchar(255) default NULL,
  modifier_id int(11) default NULL,
  modified int(11) default NULL,
  url varchar(255) default NULL,
  description text,
  image_id int(11) default NULL,
  active int(11) default NULL,
  access_url varchar(255) default NULL,
  created int(11) default NULL,
  creator_id int(11) default NULL,
  status int(11) default NULL,
  site_access varchar(255) default NULL,
  rss_version varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezrss_export_item (
  id int(11) NOT NULL auto_increment,
  rssexport_id int(11) default NULL,
  source_node_id int(11) default NULL,
  class_id int(11) default NULL,
  title varchar(255) default NULL,
  description varchar(255) default NULL,
  PRIMARY KEY  (id),
  KEY ezrss_export_rsseid (rssexport_id)
) TYPE=MyISAM;











CREATE TABLE ezrss_import (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  url text,
  destination_node_id int(11) default NULL,
  class_id int(11) default NULL,
  class_title varchar(255) default NULL,
  class_url varchar(255) default NULL,
  class_description varchar(255) default NULL,
  active int(11) default NULL,
  creator_id int(11) default NULL,
  created int(11) default NULL,
  modifier_id int(11) default NULL,
  modified int(11) default NULL,
  status int(11) default NULL,
  object_owner_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezsearch_object_word_link (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) NOT NULL default '0',
  word_id int(11) NOT NULL default '0',
  frequency float NOT NULL default '0',
  placement int(11) NOT NULL default '0',
  prev_word_id int(11) NOT NULL default '0',
  next_word_id int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  published int(11) NOT NULL default '0',
  section_id int(11) NOT NULL default '0',
  contentclass_attribute_id int(11) NOT NULL default '0',
  identifier varchar(255) NOT NULL default '',
  integer_value int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_object_word_link_object (contentobject_id),
  KEY ezsearch_object_word_link_word (word_id),
  KEY ezsearch_object_word_link_frequency (frequency),
  KEY ezsearch_object_word_link_identifier (identifier),
  KEY ezsearch_object_word_link_integer_value (integer_value)
) TYPE=MyISAM;






INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (1,161,1,0,0,0,2,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (2,161,2,0,1,1,1,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (3,161,1,0,2,2,2,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (4,161,2,0,3,1,3,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (5,161,3,0,4,2,4,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6,161,4,0,5,3,5,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7,161,5,0,6,4,2,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8,161,2,0,7,5,6,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9,161,6,0,8,2,7,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (10,161,7,0,9,6,6,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (11,161,6,0,10,7,8,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (12,161,8,0,11,6,6,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (13,161,6,0,12,8,9,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (14,161,9,0,13,6,10,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (15,161,10,0,14,9,9,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (16,161,9,0,15,10,11,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (17,161,11,0,16,9,12,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (18,161,12,0,17,11,13,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (19,161,13,0,18,12,14,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (20,161,14,0,19,13,15,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (21,161,15,0,20,14,16,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (22,161,16,0,21,15,3,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (23,161,3,0,22,16,4,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (24,161,4,0,23,3,5,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (25,161,5,0,24,4,2,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (26,161,2,0,25,5,6,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (27,161,6,0,26,2,7,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (28,161,7,0,27,6,6,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (29,161,6,0,28,7,8,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (30,161,8,0,29,6,6,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (31,161,6,0,30,8,9,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (32,161,9,0,31,6,10,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (33,161,10,0,32,9,9,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (34,161,9,0,33,10,11,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (35,161,11,0,34,9,12,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (36,161,12,0,35,11,13,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (37,161,13,0,36,12,14,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (38,161,14,0,37,13,15,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (39,161,15,0,38,14,16,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (40,161,16,0,39,15,0,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (41,228,17,0,0,0,17,1,1068718629,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (42,228,17,0,1,17,18,1,1068718629,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (43,228,18,0,2,17,19,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (44,228,19,0,3,18,20,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (45,228,20,0,4,19,21,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (46,228,21,0,5,20,22,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (47,228,22,0,6,21,23,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (48,228,23,0,7,22,24,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (49,228,24,0,8,23,25,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (50,228,25,0,9,24,26,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (51,228,26,0,10,25,18,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (52,228,18,0,11,26,19,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (53,228,19,0,12,18,20,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (54,228,20,0,13,19,21,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (55,228,21,0,14,20,22,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (56,228,22,0,15,21,23,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (57,228,23,0,16,22,24,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (58,228,24,0,17,23,25,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (59,228,25,0,18,24,26,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (60,228,26,0,19,25,0,1,1068718629,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (61,231,27,0,0,0,27,1,1068718746,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (62,231,27,0,1,27,17,1,1068718746,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (63,231,17,0,2,27,28,1,1068718746,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (64,231,28,0,3,17,22,1,1068718746,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (65,231,22,0,4,28,29,1,1068718746,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (66,231,29,0,5,22,17,1,1068718746,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (67,231,17,0,6,29,28,1,1068718746,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (68,231,28,0,7,17,22,1,1068718746,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (69,231,22,0,8,28,29,1,1068718746,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (70,231,29,0,9,22,0,1,1068718746,12,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (71,255,30,0,0,0,31,24,1069770691,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (72,255,31,0,1,30,30,24,1069770691,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (73,255,30,0,2,31,31,24,1069770691,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (74,255,31,0,3,30,32,24,1069770691,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (75,255,32,0,4,31,33,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (76,255,33,0,5,32,30,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (77,255,30,0,6,33,34,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (78,255,34,0,7,30,35,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (79,255,35,0,8,34,36,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (80,255,36,0,9,35,37,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (81,255,37,0,10,36,32,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (82,255,32,0,11,37,33,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (83,255,33,0,12,32,30,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (84,255,30,0,13,33,34,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (85,255,34,0,14,30,35,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (86,255,35,0,15,34,36,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (87,255,36,0,16,35,37,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (88,255,37,0,17,36,0,24,1069770691,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (89,256,30,0,0,0,34,24,1069770809,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (90,256,34,0,1,30,38,24,1069770809,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (91,256,38,0,2,34,39,24,1069770809,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (92,256,39,0,3,38,30,24,1069770809,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (93,256,30,0,4,39,34,24,1069770809,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (94,256,34,0,5,30,38,24,1069770809,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (95,256,38,0,6,34,39,24,1069770809,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (96,256,39,0,7,38,40,24,1069770809,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (97,256,40,0,8,39,30,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (98,256,30,0,9,40,34,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (99,256,34,0,10,30,38,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (100,256,38,0,11,34,39,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (101,256,39,0,12,38,40,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (102,256,40,0,13,39,30,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (103,256,30,0,14,40,34,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (104,256,34,0,15,30,38,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (105,256,38,0,16,34,39,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (106,256,39,0,17,38,0,24,1069770809,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (107,257,41,0,0,0,41,1,1069770849,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (108,257,41,0,1,41,0,1,1069770849,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (109,258,25,0,0,0,42,24,1069770910,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (110,258,42,0,1,25,25,24,1069770910,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (111,258,25,0,2,42,42,24,1069770910,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (112,258,42,0,3,25,43,24,1069770910,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (113,258,43,0,4,42,41,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (114,258,41,0,5,43,44,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (115,258,44,0,6,41,4,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (116,258,4,0,7,44,5,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (117,258,5,0,8,4,25,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (118,258,25,0,9,5,45,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (119,258,45,0,10,25,28,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (120,258,28,0,11,45,25,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (121,258,25,0,12,28,46,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (122,258,46,0,13,25,43,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (123,258,43,0,14,46,41,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (124,258,41,0,15,43,44,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (125,258,44,0,16,41,4,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (126,258,4,0,17,44,5,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (127,258,5,0,18,4,25,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (128,258,25,0,19,5,45,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (129,258,45,0,20,25,28,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (130,258,28,0,21,45,25,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (131,258,25,0,22,28,46,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (132,258,46,0,23,25,0,24,1069770910,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (133,259,47,0,0,0,33,24,1069770984,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (134,259,33,0,1,47,25,24,1069770984,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (135,259,25,0,2,33,48,24,1069770984,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (136,259,48,0,3,25,47,24,1069770984,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (137,259,47,0,4,48,33,24,1069770984,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (138,259,33,0,5,47,25,24,1069770984,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (139,259,25,0,6,33,48,24,1069770984,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (140,259,48,0,7,25,49,24,1069770984,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (141,259,49,0,8,48,50,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (142,259,50,0,9,49,51,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (143,259,51,0,10,50,28,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (144,259,28,0,11,51,52,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (145,259,52,0,12,28,53,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (146,259,53,0,13,52,54,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (147,259,54,0,14,53,55,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (148,259,55,0,15,54,56,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (149,259,56,0,16,55,57,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (150,259,57,0,17,56,58,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (151,259,58,0,18,57,59,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (152,259,59,0,19,58,60,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (153,259,60,0,20,59,49,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (154,259,49,0,21,60,50,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (155,259,50,0,22,49,51,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (156,259,51,0,23,50,28,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (157,259,28,0,24,51,52,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (158,259,52,0,25,28,53,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (159,259,53,0,26,52,54,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (160,259,54,0,27,53,55,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (161,259,55,0,28,54,56,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (162,259,56,0,29,55,57,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (163,259,57,0,30,56,58,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (164,259,58,0,31,57,59,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (165,259,59,0,32,58,60,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (166,259,60,0,33,59,0,24,1069770984,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (167,260,61,0,0,0,61,1,1069771040,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (168,260,61,0,1,61,0,1,1069771040,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (169,261,62,0,0,0,63,24,1069771089,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (170,261,63,0,1,62,62,24,1069771089,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (171,261,62,0,2,63,63,24,1069771089,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (172,261,63,0,3,62,25,24,1069771089,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (173,261,25,0,4,63,43,24,1069771089,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (174,261,43,0,5,25,64,24,1069771089,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (175,261,64,0,6,43,44,24,1069771089,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (176,261,44,0,7,64,25,24,1069771089,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (177,261,25,0,8,44,43,24,1069771089,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (178,261,43,0,9,25,64,24,1069771089,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (179,261,64,0,10,43,44,24,1069771089,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (180,261,44,0,11,64,0,24,1069771089,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (181,264,65,0,0,0,65,1,1069773539,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (182,264,65,0,1,65,0,1,1069773539,12,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (183,265,66,0,0,0,66,24,1069773629,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (184,265,66,0,1,66,67,24,1069773629,12,204,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (185,265,67,0,2,66,68,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (186,265,68,0,3,67,44,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (187,265,44,0,4,68,69,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (188,265,69,0,5,44,25,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (189,265,25,0,6,69,70,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (190,265,70,0,7,25,71,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (191,265,71,0,8,70,72,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (192,265,72,0,9,71,5,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (193,265,5,0,10,72,73,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (194,265,73,0,11,5,74,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (195,265,74,0,12,73,75,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (196,265,75,0,13,74,76,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (197,265,76,0,14,75,77,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (198,265,77,0,15,76,4,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (199,265,4,0,16,77,29,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (200,265,29,0,17,4,5,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (201,265,5,0,18,29,74,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (202,265,74,0,19,5,78,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (203,265,78,0,20,74,79,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (204,265,79,0,21,78,67,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (205,265,67,0,22,79,68,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (206,265,68,0,23,67,44,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (207,265,44,0,24,68,69,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (208,265,69,0,25,44,25,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (209,265,25,0,26,69,70,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (210,265,70,0,27,25,71,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (211,265,71,0,28,70,72,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (212,265,72,0,29,71,5,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (213,265,5,0,30,72,73,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (214,265,73,0,31,5,74,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (215,265,74,0,32,73,75,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (216,265,75,0,33,74,76,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (217,265,76,0,34,75,77,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (218,265,77,0,35,76,4,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (219,265,4,0,36,77,29,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (220,265,29,0,37,4,5,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (221,265,5,0,38,29,74,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (222,265,74,0,39,5,78,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (223,265,78,0,40,74,79,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (224,265,79,0,41,78,0,24,1069773629,12,205,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (225,240,80,0,0,0,80,1,1068719643,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (226,240,80,0,1,80,0,1,1068719643,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (227,241,81,0,0,0,82,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (228,241,82,0,1,81,5,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (229,241,5,0,2,82,25,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (230,241,25,0,3,5,43,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (231,241,43,0,4,25,33,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (232,241,33,0,5,43,42,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (233,241,42,0,6,33,41,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (234,241,41,0,7,42,81,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (235,241,81,0,8,41,82,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (236,241,82,0,9,81,5,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (237,241,5,0,10,82,25,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (238,241,25,0,11,5,43,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (239,241,43,0,12,25,33,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (240,241,33,0,13,43,42,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (241,241,42,0,14,33,41,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (242,241,41,0,15,42,0,25,1068720802,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (243,262,81,0,0,0,83,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (244,262,83,0,1,81,68,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (245,262,68,0,2,83,26,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (246,262,26,0,3,68,25,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (247,262,25,0,4,26,43,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (248,262,43,0,5,25,42,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (249,262,42,0,6,43,84,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (250,262,84,0,7,42,47,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (251,262,47,0,8,84,33,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (252,262,33,0,9,47,25,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (253,262,25,0,10,33,48,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (254,262,48,0,11,25,81,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (255,262,81,0,12,48,83,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (256,262,83,0,13,81,68,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (257,262,68,0,14,83,26,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (258,262,26,0,15,68,25,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (259,262,25,0,16,26,43,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (260,262,43,0,17,25,42,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (261,262,42,0,18,43,84,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (262,262,84,0,19,42,47,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (263,262,47,0,20,84,33,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (264,262,33,0,21,47,25,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (265,262,25,0,22,33,48,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (266,262,48,0,23,25,0,25,1069771243,1,207,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (267,49,85,0,0,0,85,1,1066398020,13,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (268,49,85,0,1,85,86,1,1066398020,13,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (269,49,86,0,2,85,87,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (270,49,87,0,3,86,88,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (271,49,88,0,4,87,24,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (272,49,24,0,5,88,89,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (273,49,89,0,6,24,86,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (274,49,86,0,7,89,87,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (275,49,87,0,8,86,88,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (276,49,88,0,9,87,24,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (277,49,24,0,10,88,89,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (278,49,89,0,11,24,0,1,1066398020,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (279,212,90,0,0,0,90,1,1068711069,13,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (280,212,90,0,1,90,91,1,1068711069,13,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (281,212,91,0,2,90,92,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (282,212,92,0,3,91,93,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (283,212,93,0,4,92,25,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (284,212,25,0,5,93,94,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (285,212,94,0,6,25,33,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (286,212,33,0,7,94,2,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (287,212,2,0,8,33,91,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (288,212,91,0,9,2,92,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (289,212,92,0,10,91,93,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (290,212,93,0,11,92,25,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (291,212,25,0,12,93,94,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (292,212,94,0,13,25,33,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (293,212,33,0,14,94,2,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (294,212,2,0,15,33,0,1,1068711069,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (295,214,95,0,0,0,56,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (296,214,56,0,1,95,96,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (297,214,96,0,2,56,97,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (298,214,97,0,3,96,98,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (299,214,98,0,4,97,99,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (300,214,99,0,5,98,95,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (301,214,95,0,6,99,56,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (302,214,56,0,7,95,96,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (303,214,96,0,8,56,97,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (304,214,97,0,9,96,98,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (305,214,98,0,10,97,99,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (306,214,99,0,11,98,100,23,1068711140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (307,214,100,0,12,99,5,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (308,214,5,0,13,100,101,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (309,214,101,0,14,5,102,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (310,214,102,0,15,101,103,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (311,214,103,0,16,102,104,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (312,214,104,0,17,103,105,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (313,214,105,0,18,104,106,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (314,214,106,0,19,105,100,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (315,214,100,0,20,106,107,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (316,214,107,0,21,100,91,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (317,214,91,0,22,107,108,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (318,214,108,0,23,91,109,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (319,214,109,0,24,108,110,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (320,214,110,0,25,109,111,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (321,214,111,0,26,110,100,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (322,214,100,0,27,111,112,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (323,214,112,0,28,100,2,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (324,214,2,0,29,112,113,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (325,214,113,0,30,2,56,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (326,214,56,0,31,113,114,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (327,214,114,0,32,56,100,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (328,214,100,0,33,114,105,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (329,214,105,0,34,100,91,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (330,214,91,0,35,105,115,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (331,214,115,0,36,91,28,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (332,214,28,0,37,115,116,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (333,214,116,0,38,28,24,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (334,214,24,0,39,116,54,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (335,214,54,0,40,24,96,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (336,214,96,0,41,54,25,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (337,214,25,0,42,96,102,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (338,214,102,0,43,25,117,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (339,214,117,0,44,102,118,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (340,214,118,0,45,117,100,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (341,214,100,0,46,118,5,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (342,214,5,0,47,100,101,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (343,214,101,0,48,5,102,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (344,214,102,0,49,101,103,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (345,214,103,0,50,102,104,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (346,214,104,0,51,103,105,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (347,214,105,0,52,104,106,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (348,214,106,0,53,105,100,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (349,214,100,0,54,106,107,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (350,214,107,0,55,100,91,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (351,214,91,0,56,107,108,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (352,214,108,0,57,91,109,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (353,214,109,0,58,108,110,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (354,214,110,0,59,109,111,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (355,214,111,0,60,110,100,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (356,214,100,0,61,111,112,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (357,214,112,0,62,100,2,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (358,214,2,0,63,112,113,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (359,214,113,0,64,2,56,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (360,214,56,0,65,113,114,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (361,214,114,0,66,56,100,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (362,214,100,0,67,114,105,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (363,214,105,0,68,100,91,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (364,214,91,0,69,105,115,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (365,214,115,0,70,91,28,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (366,214,28,0,71,115,116,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (367,214,116,0,72,28,24,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (368,214,24,0,73,116,54,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (369,214,54,0,74,24,96,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (370,214,96,0,75,54,25,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (371,214,25,0,76,96,102,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (372,214,102,0,77,25,117,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (373,214,117,0,78,102,118,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (374,214,118,0,79,117,119,23,1068711140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (375,214,119,0,80,118,119,23,1068711140,13,213,'',1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (376,214,119,0,81,119,0,23,1068711140,13,213,'',1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (377,252,56,0,0,0,120,23,1069770140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (378,252,120,0,1,56,121,23,1069770140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (379,252,121,0,2,120,56,23,1069770140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (380,252,56,0,3,121,120,23,1069770140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (381,252,120,0,4,56,121,23,1069770140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (382,252,121,0,5,120,122,23,1069770140,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (383,252,122,0,6,121,56,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (384,252,56,0,7,122,123,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (385,252,123,0,8,56,67,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (386,252,67,0,9,123,124,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (387,252,124,0,10,67,33,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (388,252,33,0,11,124,25,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (389,252,25,0,12,33,125,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (390,252,125,0,13,25,74,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (391,252,74,0,14,125,97,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (392,252,97,0,15,74,126,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (393,252,126,0,16,97,56,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (394,252,56,0,17,126,127,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (395,252,127,0,18,56,128,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (396,252,128,0,19,127,53,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (397,252,53,0,20,128,129,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (398,252,129,0,21,53,6,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (399,252,6,0,22,129,130,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (400,252,130,0,23,6,24,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (401,252,24,0,24,130,131,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (402,252,131,0,25,24,91,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (403,252,91,0,26,131,132,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (404,252,132,0,27,91,133,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (405,252,133,0,28,132,58,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (406,252,58,0,29,133,59,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (407,252,59,0,30,58,134,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (408,252,134,0,31,59,135,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (409,252,135,0,32,134,97,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (410,252,97,0,33,135,136,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (411,252,136,0,34,97,137,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (412,252,137,0,35,136,138,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (413,252,138,0,36,137,10,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (414,252,10,0,37,138,139,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (415,252,139,0,38,10,140,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (416,252,140,0,39,139,1,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (417,252,1,0,40,140,4,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (418,252,4,0,41,1,122,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (419,252,122,0,42,4,56,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (420,252,56,0,43,122,123,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (421,252,123,0,44,56,67,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (422,252,67,0,45,123,124,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (423,252,124,0,46,67,33,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (424,252,33,0,47,124,25,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (425,252,25,0,48,33,125,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (426,252,125,0,49,25,74,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (427,252,74,0,50,125,97,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (428,252,97,0,51,74,126,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (429,252,126,0,52,97,56,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (430,252,56,0,53,126,127,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (431,252,127,0,54,56,128,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (432,252,128,0,55,127,53,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (433,252,53,0,56,128,129,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (434,252,129,0,57,53,6,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (435,252,6,0,58,129,130,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (436,252,130,0,59,6,24,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (437,252,24,0,60,130,131,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (438,252,131,0,61,24,91,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (439,252,91,0,62,131,132,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (440,252,132,0,63,91,133,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (441,252,133,0,64,132,58,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (442,252,58,0,65,133,59,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (443,252,59,0,66,58,134,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (444,252,134,0,67,59,135,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (445,252,135,0,68,134,97,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (446,252,97,0,69,135,136,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (447,252,136,0,70,97,137,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (448,252,137,0,71,136,138,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (449,252,138,0,72,137,10,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (450,252,10,0,73,138,139,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (451,252,139,0,74,10,140,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (452,252,140,0,75,139,1,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (453,252,1,0,76,140,4,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (454,252,4,0,77,1,141,23,1069770140,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (455,252,141,0,78,4,141,23,1069770140,13,213,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (456,252,141,0,79,141,0,23,1069770140,13,213,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (457,263,142,0,0,0,142,23,1069771496,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (458,263,142,0,1,142,56,23,1069771496,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (459,263,56,0,2,142,138,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (460,263,138,0,3,56,143,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (461,263,143,0,4,138,6,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (462,263,6,0,5,143,91,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (463,263,91,0,6,6,142,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (464,263,142,0,7,91,38,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (465,263,38,0,8,142,91,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (466,263,91,0,9,38,144,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (467,263,144,0,10,91,145,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (468,263,145,0,11,144,4,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (469,263,4,0,12,145,146,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (470,263,146,0,13,4,24,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (471,263,24,0,14,146,56,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (472,263,56,0,15,24,138,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (473,263,138,0,16,56,147,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (474,263,147,0,17,138,111,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (475,263,111,0,18,147,25,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (476,263,25,0,19,111,15,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (477,263,15,0,20,25,33,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (478,263,33,0,21,15,97,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (479,263,97,0,22,33,148,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (480,263,148,0,23,97,59,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (481,263,59,0,24,148,149,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (482,263,149,0,25,59,135,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (483,263,135,0,26,149,150,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (484,263,150,0,27,135,56,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (485,263,56,0,28,150,138,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (486,263,138,0,29,56,143,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (487,263,143,0,30,138,6,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (488,263,6,0,31,143,91,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (489,263,91,0,32,6,142,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (490,263,142,0,33,91,38,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (491,263,38,0,34,142,91,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (492,263,91,0,35,38,144,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (493,263,144,0,36,91,145,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (494,263,145,0,37,144,4,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (495,263,4,0,38,145,146,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (496,263,146,0,39,4,24,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (497,263,24,0,40,146,56,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (498,263,56,0,41,24,138,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (499,263,138,0,42,56,147,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (500,263,147,0,43,138,111,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (501,263,111,0,44,147,25,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (502,263,25,0,45,111,15,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (503,263,15,0,46,25,33,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (504,263,33,0,47,15,97,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (505,263,97,0,48,33,148,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (506,263,148,0,49,97,59,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (507,263,59,0,50,148,149,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (508,263,149,0,51,59,135,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (509,263,135,0,52,149,150,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (510,263,150,0,53,135,119,23,1069771496,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (511,263,119,0,54,150,119,23,1069771496,13,213,'',1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (512,263,119,0,55,119,0,23,1069771496,13,213,'',1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (513,267,151,0,0,0,151,26,1069773826,13,209,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (514,267,151,0,1,151,49,26,1069773826,13,209,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (515,267,49,0,2,151,56,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (516,267,56,0,3,49,152,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (517,267,152,0,4,56,58,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (518,267,58,0,5,152,153,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (519,267,153,0,6,58,25,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (520,267,25,0,7,153,154,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (521,267,154,0,8,25,49,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (522,267,49,0,9,154,56,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (523,267,56,0,10,49,152,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (524,267,152,0,11,56,58,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (525,267,58,0,12,152,153,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (526,267,153,0,13,58,25,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (527,267,25,0,14,153,154,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (528,267,154,0,15,25,0,26,1069773826,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (529,213,155,0,0,0,155,1,1068711091,13,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (530,213,155,0,1,155,155,1,1068711091,13,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (531,213,155,0,2,155,156,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (532,213,156,0,3,155,157,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (533,213,157,0,4,156,158,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (534,213,158,0,5,157,24,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (535,213,24,0,6,158,25,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (536,213,25,0,7,24,159,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (537,213,159,0,8,25,81,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (538,213,81,0,9,159,160,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (539,213,160,0,10,81,161,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (540,213,161,0,11,160,162,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (541,213,162,0,12,161,56,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (542,213,56,0,13,162,163,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (543,213,163,0,14,56,101,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (544,213,101,0,15,163,164,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (545,213,164,0,16,101,165,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (546,213,165,0,17,164,81,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (547,213,81,0,18,165,166,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (548,213,166,0,19,81,56,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (549,213,56,0,20,166,167,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (550,213,167,0,21,56,168,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (551,213,168,0,22,167,128,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (552,213,128,0,23,168,169,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (553,213,169,0,24,128,25,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (554,213,25,0,25,169,170,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (555,213,170,0,26,25,74,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (556,213,74,0,27,170,109,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (557,213,109,0,28,74,171,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (558,213,171,0,29,109,82,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (559,213,82,0,30,171,172,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (560,213,172,0,31,82,155,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (561,213,155,0,32,172,156,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (562,213,156,0,33,155,157,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (563,213,157,0,34,156,158,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (564,213,158,0,35,157,24,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (565,213,24,0,36,158,25,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (566,213,25,0,37,24,159,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (567,213,159,0,38,25,81,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (568,213,81,0,39,159,160,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (569,213,160,0,40,81,161,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (570,213,161,0,41,160,162,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (571,213,162,0,42,161,56,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (572,213,56,0,43,162,163,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (573,213,163,0,44,56,101,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (574,213,101,0,45,163,164,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (575,213,164,0,46,101,165,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (576,213,165,0,47,164,81,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (577,213,81,0,48,165,166,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (578,213,166,0,49,81,56,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (579,213,56,0,50,166,167,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (580,213,167,0,51,56,168,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (581,213,168,0,52,167,128,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (582,213,128,0,53,168,169,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (583,213,169,0,54,128,25,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (584,213,25,0,55,169,170,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (585,213,170,0,56,25,74,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (586,213,74,0,57,170,109,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (587,213,109,0,58,74,171,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (588,213,171,0,59,109,82,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (589,213,82,0,60,171,172,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (590,213,172,0,61,82,0,1,1068711091,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (591,254,173,0,0,0,56,23,1069770356,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (592,254,56,0,1,173,96,23,1069770356,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (593,254,96,0,2,56,100,23,1069770356,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (594,254,100,0,3,96,173,23,1069770356,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (595,254,173,0,4,100,56,23,1069770356,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (596,254,56,0,5,173,96,23,1069770356,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (597,254,96,0,6,56,100,23,1069770356,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (598,254,100,0,7,96,174,23,1069770356,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (599,254,174,0,8,100,175,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (600,254,175,0,9,174,91,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (601,254,91,0,10,175,176,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (602,254,176,0,11,91,177,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (603,254,177,0,12,176,133,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (604,254,133,0,13,177,178,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (605,254,178,0,14,133,97,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (606,254,97,0,15,178,179,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (607,254,179,0,16,97,56,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (608,254,56,0,17,179,173,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (609,254,173,0,18,56,96,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (610,254,96,0,19,173,25,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (611,254,25,0,20,96,180,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (612,254,180,0,21,25,181,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (613,254,181,0,22,180,33,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (614,254,33,0,23,181,97,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (615,254,97,0,24,33,159,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (616,254,159,0,25,97,182,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (617,254,182,0,26,159,183,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (618,254,183,0,27,182,25,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (619,254,25,0,28,183,172,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (620,254,172,0,29,25,6,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (621,254,6,0,30,172,184,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (622,254,184,0,31,6,74,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (623,254,74,0,32,184,10,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (624,254,10,0,33,74,111,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (625,254,111,0,34,10,176,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (626,254,176,0,35,111,185,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (627,254,185,0,36,176,174,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (628,254,174,0,37,185,175,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (629,254,175,0,38,174,91,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (630,254,91,0,39,175,176,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (631,254,176,0,40,91,177,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (632,254,177,0,41,176,133,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (633,254,133,0,42,177,178,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (634,254,178,0,43,133,97,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (635,254,97,0,44,178,179,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (636,254,179,0,45,97,56,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (637,254,56,0,46,179,173,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (638,254,173,0,47,56,96,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (639,254,96,0,48,173,25,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (640,254,25,0,49,96,180,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (641,254,180,0,50,25,181,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (642,254,181,0,51,180,33,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (643,254,33,0,52,181,97,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (644,254,97,0,53,33,159,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (645,254,159,0,54,97,182,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (646,254,182,0,55,159,183,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (647,254,183,0,56,182,25,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (648,254,25,0,57,183,172,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (649,254,172,0,58,25,6,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (650,254,6,0,59,172,184,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (651,254,184,0,60,6,74,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (652,254,74,0,61,184,10,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (653,254,10,0,62,74,111,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (654,254,111,0,63,10,176,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (655,254,176,0,64,111,185,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (656,254,185,0,65,176,119,23,1069770356,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (657,254,119,0,66,185,119,23,1069770356,13,213,'',1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (658,254,119,0,67,119,0,23,1069770356,13,213,'',1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (659,266,186,0,0,0,186,26,1069773783,13,209,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (660,266,186,0,1,186,187,26,1069773783,13,209,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (661,266,187,0,2,186,59,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (662,266,59,0,3,187,68,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (663,266,68,0,4,59,188,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (664,266,188,0,5,68,100,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (665,266,100,0,6,188,133,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (666,266,133,0,7,100,25,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (667,266,25,0,8,133,189,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (668,266,189,0,9,25,33,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (669,266,33,0,10,189,190,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (670,266,190,0,11,33,187,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (671,266,187,0,12,190,59,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (672,266,59,0,13,187,68,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (673,266,68,0,14,59,188,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (674,266,188,0,15,68,100,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (675,266,100,0,16,188,133,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (676,266,133,0,17,100,25,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (677,266,25,0,18,133,189,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (678,266,189,0,19,25,33,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (679,266,33,0,20,189,190,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (680,266,190,0,21,33,0,26,1069773783,13,212,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (681,243,191,0,0,0,191,1,1068727871,13,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (682,243,191,0,1,191,192,1,1068727871,13,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (683,243,192,0,2,191,193,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (684,243,193,0,3,192,194,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (685,243,194,0,4,193,195,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (686,243,195,0,5,194,196,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (687,243,196,0,6,195,197,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (688,243,197,0,7,196,24,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (689,243,24,0,8,197,198,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (690,243,198,0,9,24,199,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (691,243,199,0,10,198,200,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (692,243,200,0,11,199,201,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (693,243,201,0,12,200,24,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (694,243,24,0,13,201,202,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (695,243,202,0,14,24,55,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (696,243,55,0,15,202,100,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (697,243,100,0,16,55,153,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (698,243,153,0,17,100,203,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (699,243,203,0,18,153,65,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (700,243,65,0,19,203,100,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (701,243,100,0,20,65,204,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (702,243,204,0,21,100,205,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (703,243,205,0,22,204,206,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (704,243,206,0,23,205,192,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (705,243,192,0,24,206,193,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (706,243,193,0,25,192,194,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (707,243,194,0,26,193,195,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (708,243,195,0,27,194,196,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (709,243,196,0,28,195,197,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (710,243,197,0,29,196,24,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (711,243,24,0,30,197,198,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (712,243,198,0,31,24,199,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (713,243,199,0,32,198,200,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (714,243,200,0,33,199,201,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (715,243,201,0,34,200,24,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (716,243,24,0,35,201,202,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (717,243,202,0,36,24,55,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (718,243,55,0,37,202,100,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (719,243,100,0,38,55,153,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (720,243,153,0,39,100,203,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (721,243,203,0,40,153,65,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (722,243,65,0,41,203,100,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (723,243,100,0,42,65,204,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (724,243,204,0,43,100,205,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (725,243,205,0,44,204,206,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (726,243,206,0,45,205,0,1,1068727871,13,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (727,253,207,0,0,0,56,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (728,253,56,0,1,207,138,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (729,253,138,0,2,56,38,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (730,253,38,0,3,138,25,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (731,253,25,0,4,38,41,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (732,253,41,0,5,25,207,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (733,253,207,0,6,41,56,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (734,253,56,0,7,207,138,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (735,253,138,0,8,56,38,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (736,253,38,0,9,138,25,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (737,253,25,0,10,38,41,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (738,253,41,0,11,25,97,23,1069770254,13,202,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (739,253,97,0,12,41,208,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (740,253,208,0,13,97,209,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (741,253,209,0,14,208,133,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (742,253,133,0,15,209,210,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (743,253,210,0,16,133,211,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (744,253,211,0,17,210,212,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (745,253,212,0,18,211,6,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (746,253,6,0,19,212,213,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (747,253,213,0,20,6,25,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (748,253,25,0,21,213,214,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (749,253,214,0,22,25,42,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (750,253,42,0,23,214,100,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (751,253,100,0,24,42,123,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (752,253,123,0,25,100,67,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (753,253,67,0,26,123,215,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (754,253,215,0,27,67,91,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (755,253,91,0,28,215,216,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (756,253,216,0,29,91,217,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (757,253,217,0,30,216,137,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (758,253,137,0,31,217,218,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (759,253,218,0,32,137,2,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (760,253,2,0,33,218,219,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (761,253,219,0,34,2,206,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (762,253,206,0,35,219,220,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (763,253,220,0,36,206,97,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (764,253,97,0,37,220,208,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (765,253,208,0,38,97,209,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (766,253,209,0,39,208,133,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (767,253,133,0,40,209,210,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (768,253,210,0,41,133,211,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (769,253,211,0,42,210,212,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (770,253,212,0,43,211,6,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (771,253,6,0,44,212,213,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (772,253,213,0,45,6,25,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (773,253,25,0,46,213,214,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (774,253,214,0,47,25,42,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (775,253,42,0,48,214,100,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (776,253,100,0,49,42,123,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (777,253,123,0,50,100,67,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (778,253,67,0,51,123,215,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (779,253,215,0,52,67,91,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (780,253,91,0,53,215,216,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (781,253,216,0,54,91,217,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (782,253,217,0,55,216,137,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (783,253,137,0,56,217,218,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (784,253,218,0,57,137,2,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (785,253,2,0,58,218,219,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (786,253,219,0,59,2,206,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (787,253,206,0,60,219,220,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (788,253,220,0,61,206,141,23,1069770254,13,203,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (789,253,141,0,62,220,141,23,1069770254,13,213,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (790,253,141,0,63,141,0,23,1069770254,13,213,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (791,11,221,0,0,0,222,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (792,11,222,0,1,221,221,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (793,11,221,0,2,222,222,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (794,11,222,0,3,221,0,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (795,12,223,0,0,0,224,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (796,12,224,0,1,223,223,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (797,12,223,0,2,224,224,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (798,12,224,0,3,223,0,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (799,14,223,0,0,0,223,4,1033920830,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (800,14,223,0,1,223,225,4,1033920830,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (801,14,225,0,2,223,225,4,1033920830,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (802,14,225,0,3,225,226,4,1033920830,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (803,14,226,0,4,225,227,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (804,14,227,0,5,226,226,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (805,14,226,0,6,227,227,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (806,14,227,0,7,226,228,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (807,14,228,0,8,227,228,4,1033920830,2,197,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (808,14,228,0,9,228,229,4,1033920830,2,197,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (809,14,229,0,10,228,230,4,1033920830,2,198,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (810,14,230,0,11,229,229,4,1033920830,2,198,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (811,14,229,0,12,230,230,4,1033920830,2,198,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (812,14,230,0,13,229,231,4,1033920830,2,198,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (813,14,231,0,14,230,231,4,1033920830,2,199,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (814,14,231,0,15,231,0,4,1033920830,2,199,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (815,13,232,0,0,0,232,3,1033920794,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (816,13,232,0,1,232,0,3,1033920794,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (817,268,233,0,0,0,224,3,1072180551,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (818,268,224,0,1,233,233,3,1072180551,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (819,268,233,0,2,224,224,3,1072180551,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (820,268,224,0,3,233,225,3,1072180551,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (821,268,225,0,4,224,234,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (822,268,234,0,5,225,28,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (823,268,28,0,6,234,25,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (824,268,25,0,7,28,233,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (825,268,233,0,8,25,225,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (826,268,225,0,9,233,225,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (827,268,225,0,10,225,234,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (828,268,234,0,11,225,28,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (829,268,28,0,12,234,25,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (830,268,25,0,13,28,233,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (831,268,233,0,14,25,225,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (832,268,225,0,15,233,0,3,1072180551,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (833,10,233,0,0,0,233,4,1033920665,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (834,10,233,0,1,233,225,4,1033920665,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (835,10,225,0,2,233,225,4,1033920665,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (836,10,225,0,3,225,233,4,1033920665,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (837,10,233,0,4,225,227,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (838,10,227,0,5,233,233,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (839,10,233,0,6,227,227,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (840,10,227,0,7,233,0,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (841,44,235,0,0,0,17,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (842,44,17,0,1,235,235,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (843,44,235,0,2,17,17,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (844,44,17,0,3,235,0,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (845,43,236,0,0,0,236,14,1066384365,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (846,43,236,0,1,236,237,14,1066384365,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (847,43,237,0,2,236,238,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (848,43,238,0,3,237,237,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (849,43,237,0,4,238,238,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (850,43,238,0,5,237,0,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (851,45,239,0,0,0,24,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (852,45,24,0,1,239,240,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (853,45,240,0,2,24,239,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (854,45,239,0,3,240,24,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (855,45,24,0,4,239,240,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (856,45,240,0,5,24,35,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (857,45,35,0,6,240,241,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (858,45,241,0,7,35,242,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (859,45,242,0,8,241,35,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (860,45,35,0,9,242,241,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (861,45,241,0,10,35,242,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (862,45,242,0,11,241,0,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (863,115,243,0,0,0,243,14,1066991725,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (864,115,243,0,1,243,235,14,1066991725,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (865,115,235,0,2,243,243,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (866,115,243,0,3,235,235,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (867,115,235,0,4,243,243,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (868,115,243,0,5,235,0,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (869,116,244,0,0,0,245,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (870,116,245,0,1,244,244,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (871,116,244,0,2,245,245,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (872,116,245,0,3,244,35,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (873,116,35,0,4,245,246,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (874,116,246,0,5,35,35,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (875,116,35,0,6,246,246,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (876,116,246,0,7,35,0,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (877,46,239,0,0,0,24,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (878,46,24,0,1,239,240,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (879,46,240,0,2,24,239,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (880,46,239,0,3,240,24,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (881,46,24,0,4,239,240,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (882,46,240,0,5,24,0,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (883,56,247,0,0,0,247,15,1066643397,11,161,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (884,56,247,0,1,247,0,15,1066643397,11,161,'',0);





CREATE TABLE ezsearch_return_count (
  id int(11) NOT NULL auto_increment,
  phrase_id int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezsearch_search_phrase (
  id int(11) NOT NULL auto_increment,
  phrase varchar(250) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezsearch_word (
  id int(11) NOT NULL auto_increment,
  word varchar(150) default NULL,
  object_count int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_word (word)
) TYPE=MyISAM;






INSERT INTO ezsearch_word (id, word, object_count) VALUES (1,'about',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2,'me',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (3,'anyway',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (4,'this',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (5,'is',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (6,'to',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (7,'short',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (8,'young',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (9,'smart',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (10,'not',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (11,'enough',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (12,'pimples',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (13,'boy',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (14,'totally',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (15,'girl',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (16,'addicted',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (17,'links',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (18,'worthwhile',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (19,'websites',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (20,'weblogs',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (21,'journals',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (22,'news',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (23,'pubs',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (24,'and',9);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (25,'the',17);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (26,'like',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (27,'downloads',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (28,'for',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (29,'site',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (30,'ez',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (31,'systems',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (32,'creators',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (33,'of',9);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (34,'publish',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (35,'content',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (36,'management',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (37,'system',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (38,'at',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (39,'freshmeat',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (40,'visit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (41,'movies',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (42,'matrix',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (43,'best',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (44,'ever',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (45,'homepage',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (46,'movie',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (47,'lord',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (48,'rings',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (49,'can',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (50,'t',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (51,'wait',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (52,'number',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (53,'three',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (54,'even',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (55,'if',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (56,'i',8);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (57,'know',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (58,'what',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (59,'will',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (60,'happen',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (61,'sports',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (62,'liverpool',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (63,'fc',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (64,'team',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (65,'fun',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (66,'pondus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (67,'have',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (68,'you',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (69,'discovered',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (70,'football',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (71,'fanatic',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (72,'he',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (73,'available',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (74,'in',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (75,'several',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (76,'languages',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (77,'but',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (78,'norwegian',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (79,'only',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (80,'polls',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (81,'which',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (82,'one',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (83,'do',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (84,'or',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (85,'blogs',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (86,'parenthetical',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (87,'thoughts',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (88,'concepts',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (89,'brainstorms',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (90,'personal',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (91,'a',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (92,'glimpse',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (93,'into',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (94,'life',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (95,'today',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (96,'got',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (97,'my',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (98,'new',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (99,'car',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (100,'it',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (101,'an',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (102,'old',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (103,'volkswagen',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (104,'beetle',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (105,'from',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (106,'1982',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (107,'has',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (108,'lot',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (109,'more',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (110,'charm',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (111,'that',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (112,'cost',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (113,'money',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (114,'bought',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (115,'friend',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (116,'£30',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (117,'original',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (118,'wheels',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (119,'1',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (120,'overslept',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (121,'again',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (122,'somehow',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (123,'must',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (124,'turned',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (125,'alarm',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (126,'sleep',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (127,'woke',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (128,'up',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (129,'hours',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (130,'late',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (131,'missed',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (132,'meeting',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (133,'with',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (134,'hopefully',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (135,'be',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (136,'girlfriend',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (137,'she',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (138,'was',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (139,'very',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (140,'happy',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (141,'0',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (142,'party',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (143,'invited',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (144,'friends',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (145,'house',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (146,'weekend',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (147,'told',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (148,'dreams',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (149,'also',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (150,'there',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (151,'helene',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (152,'come',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (153,'s',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (154,'address',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (155,'computers',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (156,'handhelds',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (157,'electronic',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (158,'gadgets',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (159,'software',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (160,'connects',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (161,'them',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (162,'all',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (163,'m',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (164,'early',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (165,'adopter',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (166,'means',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (167,'often',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (168,'end',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (169,'paying',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (170,'price',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (171,'than',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (172,'way',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (173,'finally',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (174,'after',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (175,'such',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (176,'long',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (177,'time',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (178,'pulling',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (179,'hair',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (180,'latest',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (181,'edition',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (182,'working',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (183,'perhaps',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (184,'fortune',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (185,'anymore',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (186,'tim',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (187,'when',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (188,'share',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (189,'rest',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (190,'us',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (191,'entertainment',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (192,'music',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (193,'books',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (194,'film',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (195,'television',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (196,'shopping',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (197,'travel',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (198,'many',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (199,'other',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (200,'fine',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (201,'escapes',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (202,'vices',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (203,'downright',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (204,'probably',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (205,'belongs',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (206,'here',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (207,'tonight',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (208,'first',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (209,'date',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (210,'mia',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (211,'we',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (212,'went',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (213,'see',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (214,'romantic',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (215,'been',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (216,'success',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (217,'since',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (218,'let',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (219,'follow',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (220,'home',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (221,'guest',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (222,'accounts',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (223,'administrator',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (224,'users',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (225,'user',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (226,'admin',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (227,'nospam@ez.no',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (228,'uberguru',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (229,'skien',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (230,'norway',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (231,'developer',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (232,'editors',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (233,'anonymous',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (234,'group',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (235,'setup',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (236,'classes',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (237,'class',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (238,'grouplist',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (239,'look',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (240,'feel',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (241,'edit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (242,'56',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (243,'cache',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (244,'url',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (245,'translator',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (246,'urltranslator',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (247,'blog_package',1);





CREATE TABLE ezsection (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  locale varchar(255) default NULL,
  navigation_part_identifier varchar(100) default 'ezcontentnavigationpart',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (2,'Users','','ezusernavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (3,'Media','','ezmedianavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (4,'News','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (5,'Contact','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (6,'Files','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (11,'Set up object','','ezsetupnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (12,'Links','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (13,'Blogs','','ezcontentnavigationpart');





CREATE TABLE ezsession (
  session_key varchar(32) NOT NULL default '',
  expiration_time int(11) unsigned NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (session_key),
  KEY expiration_time (expiration_time)
) TYPE=MyISAM;











CREATE TABLE ezsite_data (
  name varchar(60) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY  (name)
) TYPE=MyISAM;






INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-version','3.3');
INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-release','1');





CREATE TABLE ezsubtree_notification_rule (
  id int(11) NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  use_digest int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE eztipafriend_counter (
  node_id int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;











CREATE TABLE eztrigger (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  module_name varchar(200) NOT NULL default '',
  function_name varchar(200) NOT NULL default '',
  connect_type char(1) NOT NULL default '',
  workflow_id int(11) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY eztrigger_def_id (module_name,function_name,connect_type),
  KEY eztrigger_fetch (name(25),module_name(50),function_name(50))
) TYPE=MyISAM;











CREATE TABLE ezurl (
  id int(11) NOT NULL auto_increment,
  url varchar(255) default NULL,
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  is_valid int(11) NOT NULL default '1',
  last_checked int(11) NOT NULL default '0',
  original_url_md5 varchar(32) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (1,'http://ez.no',1068713677,1068713677,1,0,'dfcdb471b240d964dc3f57b998eb0533');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (2,'http://www.vg.no',1068718860,1068718860,1,0,'26f1033e463720ae68742157890bc752');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (3,'http://www.sina.com.cn',1068718957,1068718957,1,0,'4f12a25ee6cc3d6123be77df850e343e');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (4,'http://download.hzinfo.com',1068719250,1068719250,1,0,'4c9c884a40d63b7d9555ffb77fe75466');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (5,'http://vg.no',1069407573,1069407573,1,0,'5d85f44aadfc8b160572da41691d0162');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (6,'http://freshmeat.net/projects/ezpublish/?topic_id=92%2C243%2C234%2C87%2C96',1069770809,1069770809,1,0,'d85ef15ed11d5ac7c04896ad03afb12d');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (7,'http://whatisthematrix.warnerbros.com/',1069770910,1069770910,1,0,'12a254b2a3a7390dbc7fbc4db47b62ac');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (8,'http://www.lordoftherings.net/',1069770983,1069770983,1,0,'3132ce6558475654ae838771140f480c');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (9,'http://liverpoolfc.tv/',1069771089,1069771089,1,0,'967039f689cc4043aadbd2525f6d4e17');
INSERT INTO ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) VALUES (10,'http://www.start.no/pondus/',1069773629,1069773629,1,0,'1403d356d0dd0fd838927ddb32cc06e1');





CREATE TABLE ezurl_object_link (
  url_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  PRIMARY KEY  (url_id,contentobject_attribute_id,contentobject_attribute_version)
) TYPE=MyISAM;






INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (1,768,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (1,991,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (2,800,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (3,807,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (4,814,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (5,945,57);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (6,994,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (7,1002,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (8,1005,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (9,1013,1);
INSERT INTO ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) VALUES (10,1026,1);





CREATE TABLE ezurlalias (
  id int(11) NOT NULL auto_increment,
  source_url text NOT NULL,
  source_md5 varchar(32) default NULL,
  destination_url text NOT NULL,
  is_internal int(11) NOT NULL default '1',
  forward_to_id int(11) NOT NULL default '0',
  is_wildcard int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ezurlalias_source_md5 (source_md5),
  KEY ezurlalias_source_url (source_url(255)),
  KEY ezurlalias_desturl (destination_url(200))
) TYPE=MyISAM;






INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,208,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,154,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (125,'discussions/forum_main_group/music_discussion/latest_msg_not_sticky','70cf693961dcdd67766bf941c3ed2202','content/view/full/130',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (126,'discussions/forum_main_group/music_discussion/not_sticky_2','969f470c93e2131a0884648b91691d0b','content/view/full/131',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,189,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (124,'discussions/forum_main_group/music_discussion/new_topic_sticky/reply','f3dd8b6512a0b04b426ef7d7307b7229','content/view/full/129',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (157,'blogs/computers','2f8fda683b5e2473a80187cbce012bb8','content/view/full/154',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,153,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (123,'discussions/forum_main_group/music_discussion/new_topic_sticky','bf37b4a370ddb3935d0625a5b348dd20','content/view/full/128',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,189,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,189,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (127,'discussions/forum_main_group/music_discussion/important_sticky','2f16cf3039c97025a43f23182b4b6d60','content/view/full/132',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (155,'news/*','5319b79408bf223063ba67c14ad03ee0','blogs/{1}',1,0,1);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (104,'discussions/music_discussion','09533dfccc8477debe545d31bccf391f','content/view/full/114',1,149,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (105,'discussions/forum_main_group/music_discussion/this_is_a_new_topic','cec6b1593bf03079990a89a3fdc60c56','content/view/full/112',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (107,'discussions/sports_discussion','c551943f4df3c58a693f8ba55e9b6aeb','content/view/full/115',1,151,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (117,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/foo_bar','741cdf9f1ee1fa974ea7ec755f538271','content/view/full/122',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (111,'discussions/forum_main_group/sports_discussion/football','6e9c09d390322aa44bb5108b93f5f17c','content/view/full/119',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (154,'blogs','51704a6cacf71c8d5211445d9e80515f','content/view/full/50',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,189,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (114,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/my_reply','1e03a7609698aa8a98dccf1178df0e6f','content/view/full/120',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (118,'discussions/forum_main_group/music_discussion/what_about_pop','c4ebc99b2ed9792d1aee0e5fe210b852','content/view/full/123',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (119,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic','6c20d2df5a828dcdb6a4fcb4897bb643','content/view/full/124',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (120,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','de98a1bb645ea84919a5e34688ff84e2','content/view/full/125',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (128,'discussions/forum_main_group/sports_discussion/football/reply_2','13a443b7e046bb36831640f1d19e33d9','content/view/full/133',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (130,'discussions/forum_main_group/music_discussion/lkj_ssssstick','75ee87c770e4e8be9d44200cdb71d071','content/view/full/135',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (131,'discussions/forum_main_group/music_discussion/foo','12c58f35c1114deeb172aba728c50ca8','content/view/full/136',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (132,'discussions/forum_main_group/music_discussion/lkj_ssssstick/reply','6040856b4ec5bcc1c699d95020005be5','content/view/full/137',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (135,'discussions/forum_main_group/music_discussion/lkj_ssssstick/uyuiyui','4c48104ea6e5ec2a78067374d9561fcb','content/view/full/140',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (136,'discussions/forum_main_group/music_discussion/test2','53f71d4ff69ffb3bf8c8ccfb525eabd3','content/view/full/141',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (137,'discussions/forum_main_group/music_discussion/t4','5da27cda0fbcd5290338b7d22cfd730c','content/view/full/142',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (138,'discussions/forum_main_group/music_discussion/lkj_ssssstick/klj_jkl_klj','9ae60fa076882d6807506c2232143d27','content/view/full/143',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (139,'discussions/forum_main_group/music_discussion/test2/retest2','a17d07fbbd2d1b6d0fbbf8ca1509cd01','content/view/full/144',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (141,'discussions/forum_main_group/music_discussion/lkj_ssssstick/my_reply','1f95000d1f993ffa16a0cf83b78515bf','content/view/full/146',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (142,'discussions/forum_main_group/music_discussion/lkj_ssssstick/retest','0686f14064a420e6ee95aabf89c4a4f2','content/view/full/147',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (144,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf','21f0ee2122dd5264192adc15c1e69c03','content/view/full/149',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (156,'blogs/personal','10a5d8f539ef0a468722f8327f7950ab','content/view/full/153',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (146,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/dfghd_fghklj','460d30ba47855079ac8605e1c8085993','content/view/full/151',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (158,'blogs/personal/today_i_got_my_new_car','ce9118c9b6c16328082445f6d8098a0d','content/view/full/155',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (149,'discussions/forum_main_group/music_discussion','a1a79985f113d5b05b22c9686b46b175','content/view/full/114',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (150,'discussions/music_discussion/*','2ec2a3bfcf01ad3f1323390ab26dfeac','discussions/forum_main_group/music_discussion/{1}',1,0,1);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (151,'discussions/forum_main_group/sports_discussion','b68c5a82b8b2035eeee5788cb223bb7e','content/view/full/115',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (152,'discussions/sports_discussion/*','7acbf48218ca6e1d80c267911860d34f','discussions/forum_main_group/sports_discussion/{1}',1,0,1);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (153,'about_me','50793f253d2dc015e93a2f75163b0894','content/view/full/127',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (190,'blogs/personal/i_overslept_again','61ab4dd49514d2031be3362827465fe4','content/view/full/183',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (203,'links/fun','05aaf2bb0d72213e94a358284f184b79','content/view/full/195',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (164,'links','807765384d9d5527da8848df14a4f02f','content/view/full/161',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (196,'links/movies','bea720c2faeb616182aa095cb8a0e492','content/view/full/188',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (197,'links/movies/the_matrix','14290cfd46a612082d7c56dffa1b1fff','content/view/full/189',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (167,'links/news','61e2cd3056056408f1b41435a3f953c3','content/view/full/164',1,193,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (194,'links/downloads/ez_systems','8fb0de95d1934b0fa54410c6fa9bb04f','content/view/full/186',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (193,'links/downloads','1d3503542a171d9dfab9c7d3caec48e0','content/view/full/164',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (195,'links/downloads/ez_publish_at_freshmeat','c26b6a315b8894db61eb9faff400400f','content/view/full/187',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (201,'polls/which_do_you_like_the_best_matrix_or_lord_of_the_rings','25f0f61fbc5d84e469f858722040aa4c','content/view/full/193',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (198,'links/movies/lord_of_the_rings','10e58e9e8a34b59f0a3feeaa77ab2fea','content/view/full/190',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (199,'links/sports','414e65e1c520e395d334856f3dc2ea3e','content/view/full/191',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (200,'links/sports/liverpool_fc','c7c457cae15412fdcc8326da47c4bb15','content/view/full/192',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (176,'polls','952e8cf2c863b8ddc656bac6ad0b729b','content/view/full/173',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (202,'blogs/personal/party','69c642bf0ff392505a114bb6288b0a04','content/view/full/194',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (178,'polls/which_one_is_the_best_of_matrix_movies','bb0ff8ca87eb02ff2219a32c5c73b7f4','content/view/full/174',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (180,'blogs/entertainment','9dd2cf029c6cfcddc067d936dc750b3d','content/view/full/176',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (191,'blogs/entertainment/tonight_i_was_at_the_movies','12f1086f208d19b6868a93df108d8af6','content/view/full/184',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (192,'blogs/computers/finally_i_got_it','5626ad47e7065dee9ad763069e24d225','content/view/full/185',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (204,'links/fun/pondus','54e0c99d111d8d109261fef3d61b23eb','content/view/full/196',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (205,'blogs/computers/finally_i_got_it/tim','341022e34eb81e5cd2d6ca4a626834bf','content/view/full/197',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (206,'blogs/personal/party/helene','4f7f9b1b19f98bf98f649253c6bb2127','content/view/full/198',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (188,'setup/look_and_feel/blog_test','6d9593beb391a932e45ca5823e3c6359','content/view/full/54',1,189,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (189,'setup/look_and_feel/blog','a0aa455a1c24b5d1d0448546c83836cf','content/view/full/54',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (207,'users/anonymous_users','3ae1aac958e1c82013689d917d34967a','content/view/full/199',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (208,'users/anonymous_users/anonymous_user','aad93975f09371695ba08292fd9698db','content/view/full/11',1,0,0);





CREATE TABLE ezuser (
  contentobject_id int(11) NOT NULL default '0',
  login varchar(150) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  password_hash_type int(11) NOT NULL default '1',
  password_hash varchar(50) default NULL,
  PRIMARY KEY  (contentobject_id)
) TYPE=MyISAM;






INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (130,'','',2,'4ccb7125baf19de015388c99893fbb4d');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (187,'','',1,'');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (189,'','',1,'');





CREATE TABLE ezuser_accountkey (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  hash_key varchar(32) NOT NULL default '',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezuser_accountkey (id, user_id, hash_key, time) VALUES (1,154,'837e17025d6b3a340cfb305769caa30d',1068042835);
INSERT INTO ezuser_accountkey (id, user_id, hash_key, time) VALUES (2,188,'281ca20cd4d47e3f3be239f6e587df70',1068110661);
INSERT INTO ezuser_accountkey (id, user_id, hash_key, time) VALUES (3,197,'6a92e8886841440681b58a699e69d4dc',1068112344);





CREATE TABLE ezuser_discountrule (
  id int(11) NOT NULL auto_increment,
  discountrule_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezuser_role (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ezuser_role_contentobject_id (contentobject_id)
) TYPE=MyISAM;






INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (29,1,10);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (25,2,12);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (28,1,11);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (34,1,13);





CREATE TABLE ezuser_setting (
  user_id int(11) NOT NULL default '0',
  is_enabled int(1) NOT NULL default '0',
  max_login int(11) default NULL,
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;






INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (10,1,1000);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (14,1,10);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (23,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (40,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (107,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (108,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (109,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (111,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (130,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (149,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (154,0,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (187,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (188,0,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (189,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (197,0,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (198,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (206,1,0);





CREATE TABLE ezvattype (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  percentage float default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezvattype (id, name, percentage) VALUES (1,'Std',0);





CREATE TABLE ezview_counter (
  node_id int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;











CREATE TABLE ezwaituntildatevalue (
  id int(11) NOT NULL auto_increment,
  workflow_event_id int(11) NOT NULL default '0',
  workflow_event_version int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  contentclass_attribute_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id,workflow_event_id,workflow_event_version),
  KEY ezwaituntildateevalue_wf_ev_id_wf_ver (workflow_event_id,workflow_event_version)
) TYPE=MyISAM;











CREATE TABLE ezwishlist (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezworkflow (
  id int(11) NOT NULL auto_increment,
  version int(11) NOT NULL default '0',
  is_enabled int(1) NOT NULL default '0',
  workflow_type_string varchar(50) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;











CREATE TABLE ezworkflow_assign (
  id int(11) NOT NULL auto_increment,
  workflow_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  access_type int(11) NOT NULL default '0',
  as_tree int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;











CREATE TABLE ezworkflow_event (
  id int(11) NOT NULL auto_increment,
  version int(11) NOT NULL default '0',
  workflow_id int(11) NOT NULL default '0',
  workflow_type_string varchar(50) NOT NULL default '',
  description varchar(50) NOT NULL default '',
  data_int1 int(11) default NULL,
  data_int2 int(11) default NULL,
  data_int3 int(11) default NULL,
  data_int4 int(11) default NULL,
  data_text1 varchar(50) default NULL,
  data_text2 varchar(50) default NULL,
  data_text3 varchar(50) default NULL,
  data_text4 varchar(50) default NULL,
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;











CREATE TABLE ezworkflow_group (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;






INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Standard',14,14,1024392098,1024392098);





CREATE TABLE ezworkflow_group_link (
  workflow_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  workflow_version int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (workflow_id,group_id,workflow_version)
) TYPE=MyISAM;






INSERT INTO ezworkflow_group_link (workflow_id, group_id, workflow_version, group_name) VALUES (1,1,0,'Standard');





CREATE TABLE ezworkflow_process (
  id int(11) NOT NULL auto_increment,
  process_key varchar(32) NOT NULL default '',
  workflow_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  content_id int(11) NOT NULL default '0',
  content_version int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  session_key varchar(32) NOT NULL default '0',
  event_id int(11) NOT NULL default '0',
  event_position int(11) NOT NULL default '0',
  last_event_id int(11) NOT NULL default '0',
  last_event_position int(11) NOT NULL default '0',
  last_event_status int(11) NOT NULL default '0',
  event_status int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  activation_date int(11) default NULL,
  event_state int(11) default NULL,
  status int(11) default NULL,
  parameters text,
  memento_key varchar(32) default NULL,
  PRIMARY KEY  (id),
  KEY ezworkflow_process_process_key (process_key)
) TYPE=MyISAM;







