









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





INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (3,14,44,1061223634,'News');





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





INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (2,0,'Article','article','<title>',14,14,1024392098,1048494722);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (3,0,'User group','user_group','<name>',14,14,1024392098,1048494743);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1048494759);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (5,0,'Image','image','<name>',8,14,1031484992,1048494784);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (6,0,'Forum','forum','<name>',14,14,1052384723,1052384870);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (7,0,'Forum message','forum_message','<topic>',14,14,1052384877,1052384943);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (8,0,'Product','product','<title>',14,14,1052384951,1052385067);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (9,0,'Product review','product_review','<title>',14,14,1052385080,1052385252);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (11,0,'Link','link','<title>',14,14,1052385361,1052385453);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (12,0,'File','file','<name>',14,14,1052385472,1052385669);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (13,0,'Comment','comment','<subject>',14,14,1052385685,1052385756);





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





INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',NULL,0,1);





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





INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (40,14,2,4,'test test',1,0,1053613020,1053613020,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (41,14,3,1,'Media',1,0,1060695457,1060695457,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (42,14,1,1,'News',1,0,1061223553,1061223553,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (43,14,1,1,'Files',1,0,1061223565,1061223565,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (44,14,1,1,'Documents',1,0,1061223583,1061223583,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (45,14,1,2,'Intranet news',1,0,1061223634,1061223634,1,'');





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
  PRIMARY KEY  (id,version),
  KEY ezcontentobject_attribute_contentobject_id (contentobject_id),
  KEY ezcontentobject_attribute_language_code (language_code),
  KEY sort_key_int (sort_key_int),
  KEY sort_key_string (sort_key_string),
  KEY ezcontentobject_attribute_co_id_ver_lang_code (contentobject_id,version,language_code)
) TYPE=MyISAM;





INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (1,'eng-GB',1,1,4,'Root folder',NULL,NULL,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (21,'eng-GB',1,10,12,'',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (20,'eng-GB',1,10,9,'User',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (23,'eng-GB',1,11,7,'',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (25,'eng-GB',1,12,7,'',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (26,'eng-GB',1,13,6,'Editors',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (27,'eng-GB',1,13,7,'',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (29,'eng-GB',1,14,9,'User',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (30,'eng-GB',1,14,12,'',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (95,'eng-GB',1,40,8,'test',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (96,'eng-GB',1,40,9,'test',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (97,'eng-GB',1,40,12,'',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (98,'eng-GB',1,41,4,'Media',0,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (100,'eng-GB',1,42,4,'News',0,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Latest news on our intranet.</paragraph>\n</section>',1045487555,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (102,'eng-GB',1,43,4,'Files',0,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (103,'eng-GB',1,43,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (104,'eng-GB',1,44,4,'Documents',0,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (105,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (106,'eng-GB',1,45,1,'Intranet news',0,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (107,'eng-GB',1,45,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas augue leo, hendrerit vitae, dapibus ut, interdum at, ligula. Sed ut ipsum vitae sem rhoncus pulvinar. Quisque fringilla nibh at odio convallis mollis. </paragraph>\n</section>',1045487555,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (108,'eng-GB',1,45,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n  <section>\n    <header>Nam sodales</header>\n    <paragraph>\n      <line> Nam sodales vestibulum erat. Nam non mauris quis dui porttitor iaculis. Nullam quam. Suspendisse at dolor. Aliquam erat volutpat. Morbi ac justo ut enim gravida gravida. Donec tempor, nunc id elementum nonummy, lorem ligula consectetuer felis, nec rutrum wisi nisl nec augue. Nam feugiat. Etiam iaculis elementum massa. Mauris vitae ante vel lacus eleifend vehicula. Nulla facilisi. Maecenas tincidunt consequat wisi. Curabitur in libero. Nunc interdum pretium urna.</line>\n      <line> Vivamus vel tortor. Cras nonummy facilisis ligula. Aliquam urna dolor, congue eu, fermentum a, faucibus ac, tortor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed varius risus sagittis elit egestas egestas. Aenean elit. Nulla magna. Mauris rutrum. Suspendisse dignissim eros in wisi pretium dignissim. Maecenas ante. Pellentesque mattis augue. Cras a ante. Nam non arcu. Donec cursus lacinia elit.</line>\n      <line> Duis eu nunc. Cras condimentum posuere lectus. Maecenas diam. Vestibulum vitae urna vitae lorem porttitor commodo. Integer vehicula tincidunt odio. Aliquam sit amet elit a nunc accumsan sagittis. Vivamus sollicitudin tempor magna. Suspendisse convallis nibh id ligula. Phasellus at velit sit amet sapien viverra ultricies. Etiam molestie euismod risus. Pellentesque non purus at erat dapibus aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Nullam ornare ullamcorper risus. Nulla nonummy sapien vitae enim. Nam velit mauris, interdum vel, ultricies ut, feugiat at, ligula. Praesent vehicula, arcu sed nonummy tempus, orci mauris fermentum neque, nec sagittis libero lacus et augue. Sed fermentum eleifend sapien. Fusce sem magna, pharetra sed, facilisis eu, interdum ut, neque. Suspendisse facilisis augue eget est. Fusce mollis vehicula felis. </line>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (109,'eng-GB',1,45,122,'',0,0,0,0,'0');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) VALUES (110,'eng-GB',1,45,123,'',0,0,0,0,'0');





CREATE TABLE ezcontentobject_link (
  id int(11) NOT NULL auto_increment,
  from_contentobject_id int(11) NOT NULL default '0',
  from_contentobject_version int(11) NOT NULL default '0',
  to_contentobject_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;










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
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (40,'test test',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (41,'Media',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (42,'News',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (43,'Files',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (44,'Documents',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'Intranet news',1,'eng-GB','eng-GB');





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
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (2,1,1,1,1,1,'/1/2/',1,1,0,'',2);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (5,1,4,1,0,1,'/1/5/',1,1,0,'users',5);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (42,12,40,1,1,3,'/1/5/12/42/',9,1,0,'users/guest_accounts/test_test',42);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (43,1,41,1,1,1,'/1/43/',9,1,0,'media',43);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (44,2,42,1,1,2,'/1/2/44/',9,1,0,'news',44);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (45,2,43,1,1,2,'/1/2/45/',9,1,0,'files',45);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (46,45,44,1,1,3,'/1/2/45/46/',9,1,0,'files/documents',46);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (47,44,45,1,1,3,'/1/2/44/47/',9,1,0,'news/intranet_news',47);





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





INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (1,1,14,1,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (4,4,14,1,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (438,10,14,1,1033920649,1033920665,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (439,11,14,1,1033920737,1033920746,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (440,12,14,1,1033920760,1033920775,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (441,13,14,1,1033920786,1033920794,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (442,14,14,1,1033920808,1033920830,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (471,40,14,1,1053613007,1053613020,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (472,41,14,1,1060695450,1060695457,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (473,42,14,1,1061223534,1061223553,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (474,43,14,1,1061223559,1061223565,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (475,44,14,1,1061223573,1061223583,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (476,45,14,1,1061223601,1061223634,1,0,0);





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





INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (2,136,0,'Ok','3',2);
INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (1,136,0,'Poor','2',1);
INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (3,136,0,'Good','5',3);





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










CREATE TABLE ezinfocollection (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;










CREATE TABLE ezinfocollection_attribute (
  id int(11) NOT NULL auto_increment,
  informationcollection_id int(11) NOT NULL default '0',
  data_text text,
  data_int int(11) default NULL,
  data_float float default NULL,
  contentclass_attribute_id int(11) NOT NULL default '0',
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





INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (2,1,1,1,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (4,8,2,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (144,4,1,1,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (147,210,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (146,209,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (145,1,2,1,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (148,9,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (149,10,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (150,11,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (151,12,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (152,13,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (153,14,1,13,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (181,40,1,12,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (182,41,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (183,42,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (184,43,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (185,44,1,45,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (186,45,1,44,9,1,1,0,0);





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





INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (1,0,'ezpublish',41,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (2,0,'ezpublish',42,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (3,0,'ezpublish',43,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (4,0,'ezpublish',44,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (5,0,'ezpublish',45,1,0,0,'','','','');





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










CREATE TABLE ezpolicy (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (317,3,'*','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (308,2,'*','*','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (326,1,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (325,1,'login','user','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (319,3,'login','user','*');





CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  policy_id int(11) default NULL,
  identifier varchar(255) NOT NULL default '',
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (249,326,'Class',0,'read','content');





CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (435,249,'1');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (436,249,'10');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (437,249,'10');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (438,249,'11');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (439,249,'11');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (440,249,'12');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (441,249,'12');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (442,249,'13');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (443,249,'13');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (444,249,'2');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (445,249,'2');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (446,249,'5');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (447,249,'5');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (448,249,'6');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (449,249,'6');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (450,249,'7');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (451,249,'7');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (452,249,'8');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (453,249,'8');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (454,249,'9');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (455,249,'9');





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
INSERT INTO ezrole (id, version, name, value) VALUES (3,0,'Editor','');





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





INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (27,40,5,0,1,5,0,4,1053613020,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (28,41,6,0,0,0,0,1,1060695457,3,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (29,42,7,0,0,0,8,1,1061223553,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (30,42,8,0,1,7,7,1,1061223553,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (31,42,7,0,2,8,9,1,1061223553,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (32,42,9,0,3,7,10,1,1061223553,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (33,42,10,0,4,9,11,1,1061223553,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (34,42,11,0,5,10,0,1,1061223553,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (35,43,12,0,0,0,0,1,1061223565,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (36,44,13,0,0,0,0,1,1061223583,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (37,45,11,0,0,0,7,2,1061223634,1,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (38,45,7,0,1,11,14,2,1061223634,1,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (39,45,14,0,2,7,15,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (40,45,15,0,3,14,16,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (41,45,16,0,4,15,17,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (42,45,17,0,5,16,18,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (43,45,18,0,6,17,19,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (44,45,19,0,7,18,20,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (45,45,20,0,8,19,21,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (46,45,21,0,9,20,22,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (47,45,22,0,10,21,23,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (48,45,23,0,11,22,24,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (49,45,24,0,12,23,25,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (50,45,25,0,13,24,26,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (51,45,26,0,14,25,27,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (52,45,27,0,15,26,28,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (53,45,28,0,16,27,29,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (54,45,29,0,17,28,30,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (55,45,30,0,18,29,31,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (56,45,31,0,19,30,32,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (57,45,32,0,20,31,28,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (58,45,28,0,21,32,15,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (59,45,15,0,22,28,26,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (60,45,26,0,23,15,33,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (61,45,33,0,24,26,34,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (62,45,34,0,25,33,35,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (63,45,35,0,26,34,36,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (64,45,36,0,27,35,37,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (65,45,37,0,28,36,38,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (66,45,38,0,29,37,30,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (67,45,30,0,30,38,39,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (68,45,39,0,31,30,40,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (69,45,40,0,32,39,41,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (70,45,41,0,33,40,42,2,1061223634,1,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (71,45,42,0,34,41,19,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (72,45,19,0,35,42,43,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (73,45,43,0,36,19,42,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (74,45,42,0,37,43,19,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (75,45,19,0,38,42,43,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (76,45,43,0,39,19,44,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (77,45,44,0,40,43,45,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (78,45,45,0,41,44,46,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (79,45,46,0,42,45,47,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (80,45,47,0,43,46,21,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (81,45,21,0,44,47,48,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (82,45,48,0,45,21,49,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (83,45,49,0,46,48,50,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (84,45,50,0,47,49,51,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (85,45,51,0,48,50,52,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (86,45,52,0,49,51,53,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (87,45,53,0,50,52,22,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (88,45,22,0,51,53,54,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (89,45,54,0,52,22,55,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (90,45,55,0,53,54,56,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (91,45,56,0,54,55,56,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (92,45,56,0,55,56,41,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (93,45,41,0,56,56,57,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (94,45,57,0,57,41,35,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (95,45,35,0,58,57,58,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (96,45,58,0,59,35,59,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (97,45,59,0,60,58,60,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (98,45,60,0,61,59,61,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (99,45,61,0,62,60,15,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (100,45,15,0,63,61,62,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (101,45,62,0,64,15,57,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (102,45,57,0,65,62,63,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (103,45,63,0,66,57,64,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (104,45,64,0,67,63,65,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (105,45,65,0,68,64,66,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (106,45,66,0,69,65,67,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (107,45,67,0,70,66,68,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (108,45,68,0,71,67,69,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (109,45,69,0,72,68,70,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (110,45,70,0,73,69,71,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (111,45,71,0,74,70,72,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (112,45,72,0,75,71,60,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (113,45,60,0,76,72,73,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (114,45,73,0,77,60,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (115,45,32,0,78,73,34,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (116,45,34,0,79,32,74,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (117,45,74,0,80,34,75,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (118,45,75,0,81,74,76,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (119,45,76,0,82,75,44,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (120,45,44,0,83,76,76,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (121,45,76,0,84,44,77,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (122,45,77,0,85,76,78,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (123,45,78,0,86,77,79,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (124,45,79,0,87,78,80,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (125,45,80,0,88,79,81,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (126,45,81,0,89,80,28,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (127,45,28,0,90,81,60,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (128,45,60,0,91,28,82,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (129,45,82,0,92,60,83,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (130,45,83,0,93,82,49,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (131,45,49,0,94,83,78,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (132,45,78,0,95,49,53,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (133,45,53,0,96,78,84,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (134,45,84,0,97,53,85,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (135,45,85,0,98,84,86,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (136,45,86,0,99,85,87,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (137,45,87,0,100,86,88,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (138,45,88,0,101,87,89,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (139,45,89,0,102,88,90,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (140,45,90,0,103,89,91,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (141,45,91,0,104,90,83,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (142,45,83,0,105,91,28,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (143,45,28,0,106,83,72,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (144,45,72,0,107,28,92,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (145,45,92,0,108,72,93,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (146,45,93,0,109,92,28,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (147,45,28,0,110,93,53,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (148,45,53,0,111,28,23,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (149,45,23,0,112,53,94,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (150,45,94,0,113,23,26,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (151,45,26,0,114,94,95,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (152,45,95,0,115,26,96,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (153,45,96,0,116,95,97,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (154,45,97,0,117,96,26,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (155,45,26,0,118,97,98,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (156,45,98,0,119,26,99,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (157,45,99,0,120,98,100,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (158,45,100,0,121,99,28,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (159,45,28,0,122,100,17,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (160,45,17,0,123,28,18,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (161,45,18,0,124,17,101,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (162,45,101,0,125,18,102,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (163,45,102,0,126,101,103,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (164,45,103,0,127,102,50,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (165,45,50,0,128,103,104,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (166,45,104,0,129,50,87,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (167,45,87,0,130,104,91,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (168,45,91,0,131,87,105,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (169,45,105,0,132,91,106,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (170,45,106,0,133,105,35,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (171,45,35,0,134,106,107,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (172,45,107,0,135,35,76,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (173,45,76,0,136,107,71,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (174,45,71,0,137,76,84,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (175,45,84,0,138,71,108,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (176,45,108,0,139,84,91,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (177,45,91,0,140,108,109,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (178,45,109,0,141,91,63,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (179,45,63,0,142,109,110,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (180,45,110,0,143,63,96,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (181,45,96,0,144,110,17,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (182,45,17,0,145,96,18,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (183,45,18,0,146,17,59,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (184,45,59,0,147,18,106,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (185,45,106,0,148,59,111,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (186,45,111,0,149,106,24,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (187,45,24,0,150,111,112,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (188,45,112,0,151,24,113,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (189,45,113,0,152,112,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (190,45,32,0,153,113,112,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (191,45,112,0,154,32,54,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (192,45,54,0,155,112,114,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (193,45,114,0,156,54,115,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (194,45,115,0,157,114,116,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (195,45,116,0,158,115,88,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (196,45,88,0,159,116,61,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (197,45,61,0,160,88,117,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (198,45,117,0,161,61,60,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (199,45,60,0,162,117,81,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (200,45,81,0,163,60,66,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (201,45,66,0,164,81,118,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (202,45,118,0,165,66,119,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (203,45,119,0,166,118,72,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (204,45,72,0,167,119,72,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (205,45,72,0,168,72,78,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (206,45,78,0,169,72,61,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (207,45,61,0,170,78,30,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (208,45,30,0,171,61,27,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (209,45,27,0,172,30,15,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (210,45,15,0,173,27,76,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (211,45,76,0,174,15,26,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (212,45,26,0,175,76,108,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (213,45,108,0,176,26,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (214,45,32,0,177,108,120,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (215,45,120,0,178,32,121,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (216,45,121,0,179,120,15,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (217,45,15,0,180,121,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (218,45,32,0,181,15,122,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (219,45,122,0,182,32,72,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (220,45,72,0,183,122,57,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (221,45,57,0,184,72,50,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (222,45,50,0,185,57,22,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (223,45,22,0,186,50,66,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (224,45,66,0,187,22,64,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (225,45,64,0,188,66,90,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (226,45,90,0,189,64,104,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (227,45,104,0,190,90,123,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (228,45,123,0,191,104,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (229,45,32,0,192,123,101,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (230,45,101,0,193,32,81,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (231,45,81,0,194,101,78,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (232,45,78,0,195,81,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (233,45,32,0,196,78,60,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (234,45,60,0,197,32,91,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (235,45,91,0,198,60,52,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (236,45,52,0,199,91,58,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (237,45,58,0,200,52,49,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (238,45,49,0,201,58,42,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (239,45,42,0,202,49,80,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (240,45,80,0,203,42,124,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (241,45,124,0,204,80,39,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (242,45,39,0,205,124,71,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (243,45,71,0,206,39,52,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (244,45,52,0,207,71,80,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (245,45,80,0,208,52,117,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (246,45,117,0,209,80,125,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (247,45,125,0,210,117,117,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (248,45,117,0,211,125,125,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (249,45,125,0,212,117,60,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (250,45,60,0,213,125,126,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (251,45,126,0,214,60,117,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (252,45,117,0,215,126,91,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (253,45,91,0,216,117,114,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (254,45,114,0,217,91,75,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (255,45,75,0,218,114,101,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (256,45,101,0,219,75,95,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (257,45,95,0,220,101,127,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (258,45,127,0,221,95,128,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (259,45,128,0,222,127,89,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (260,45,89,0,223,128,74,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (261,45,74,0,224,89,30,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (262,45,30,0,225,74,16,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (263,45,16,0,226,30,115,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (264,45,115,0,227,16,126,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (265,45,126,0,228,115,47,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (266,45,47,0,229,126,90,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (267,45,90,0,230,47,88,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (268,45,88,0,231,90,73,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (269,45,73,0,232,88,28,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (270,45,28,0,233,73,116,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (271,45,116,0,234,28,129,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (272,45,129,0,235,116,129,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (273,45,129,0,236,129,130,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (274,45,130,0,237,129,131,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (275,45,131,0,238,130,98,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (276,45,98,0,239,131,48,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (277,45,48,0,240,98,132,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (278,45,132,0,241,48,133,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (279,45,133,0,242,132,14,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (280,45,14,0,243,133,31,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (281,45,31,0,244,14,19,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (282,45,19,0,245,31,108,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (283,45,108,0,246,19,44,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (284,45,44,0,247,108,82,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (285,45,82,0,248,44,134,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (286,45,134,0,249,82,135,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (287,45,135,0,250,134,44,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (288,45,44,0,251,135,23,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (289,45,23,0,252,44,117,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (290,45,117,0,253,23,100,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (291,45,100,0,254,117,102,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (292,45,102,0,255,100,127,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (293,45,127,0,256,102,132,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (294,45,132,0,257,127,123,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (295,45,123,0,258,132,114,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (296,45,114,0,259,123,26,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (297,45,26,0,260,114,61,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (298,45,61,0,261,26,112,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (299,45,112,0,262,61,43,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (300,45,43,0,263,112,92,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (301,45,92,0,264,43,136,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (302,45,136,0,265,92,50,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (303,45,50,0,266,136,137,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (304,45,137,0,267,50,22,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (305,45,22,0,268,137,138,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (306,45,138,0,269,22,93,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (307,45,93,0,270,138,134,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (308,45,134,0,271,93,99,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (309,45,99,0,272,134,57,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (310,45,57,0,273,99,113,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (311,45,113,0,274,57,98,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (312,45,98,0,275,113,29,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (313,45,29,0,276,98,77,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (314,45,77,0,277,29,105,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (315,45,105,0,278,77,46,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (316,45,46,0,279,105,112,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (317,45,112,0,280,46,139,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (318,45,139,0,281,112,106,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (319,45,106,0,282,139,133,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (320,45,133,0,283,106,122,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (321,45,122,0,284,133,31,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (322,45,31,0,285,122,115,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (323,45,115,0,286,31,105,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (324,45,105,0,287,115,16,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (325,45,16,0,288,105,140,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (326,45,140,0,289,16,84,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (327,45,84,0,290,140,103,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (328,45,103,0,291,84,58,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (329,45,58,0,292,103,63,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (330,45,63,0,293,58,88,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (331,45,88,0,294,63,139,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (332,45,139,0,295,88,141,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (333,45,141,0,296,139,142,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (334,45,142,0,297,141,143,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (335,45,143,0,298,142,144,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (336,45,144,0,299,143,66,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (337,45,66,0,300,144,145,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (338,45,145,0,301,66,146,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (339,45,146,0,302,145,147,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (340,45,147,0,303,146,148,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (341,45,148,0,304,147,149,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (342,45,149,0,305,148,150,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (343,45,150,0,306,149,151,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (344,45,151,0,307,150,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (345,45,32,0,308,151,51,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (346,45,51,0,309,32,83,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (347,45,83,0,310,51,152,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (348,45,152,0,311,83,21,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (349,45,21,0,312,152,97,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (350,45,97,0,313,21,97,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (351,45,97,0,314,97,153,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (352,45,153,0,315,97,21,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (353,45,21,0,316,153,50,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (354,45,50,0,317,21,87,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (355,45,87,0,318,50,114,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (356,45,114,0,319,87,82,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (357,45,82,0,320,114,74,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (358,45,74,0,321,82,80,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (359,45,80,0,322,74,154,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (360,45,154,0,323,80,57,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (361,45,57,0,324,154,134,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (362,45,134,0,325,57,77,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (363,45,77,0,326,134,80,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (364,45,80,0,327,77,22,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (365,45,22,0,328,80,61,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (366,45,61,0,329,22,96,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (367,45,96,0,330,61,56,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (368,45,56,0,331,96,23,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (369,45,23,0,332,56,106,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (370,45,106,0,333,23,58,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (371,45,58,0,334,106,61,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (372,45,61,0,335,58,117,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (373,45,117,0,336,61,91,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (374,45,91,0,337,117,72,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (375,45,72,0,338,91,130,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (376,45,130,0,339,72,155,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (377,45,155,0,340,130,156,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (378,45,156,0,341,155,21,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (379,45,21,0,342,156,157,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (380,45,157,0,343,21,84,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (381,45,84,0,344,157,98,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (382,45,98,0,345,84,106,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (383,45,106,0,346,98,158,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (384,45,158,0,347,106,68,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (385,45,68,0,348,158,159,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (386,45,159,0,349,68,22,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (387,45,22,0,350,159,109,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (388,45,109,0,351,22,60,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (389,45,60,0,352,109,26,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (390,45,26,0,353,60,105,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (391,45,105,0,354,26,26,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (392,45,26,0,355,105,14,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (393,45,14,0,356,26,95,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (394,45,95,0,357,14,160,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (395,45,160,0,358,95,161,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (396,45,161,0,359,160,136,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (397,45,136,0,360,161,138,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (398,45,138,0,361,136,39,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (399,45,39,0,362,138,115,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (400,45,115,0,363,39,17,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (401,45,17,0,364,115,18,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (402,45,18,0,365,17,21,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (403,45,21,0,366,18,58,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (404,45,58,0,367,21,98,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (405,45,98,0,368,58,162,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (406,45,162,0,369,98,152,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (407,45,152,0,370,162,46,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (408,45,46,0,371,152,163,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (409,45,163,0,372,46,131,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (410,45,131,0,373,163,87,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (411,45,87,0,374,131,74,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (412,45,74,0,375,87,40,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (413,45,40,0,376,74,38,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (414,45,38,0,377,40,48,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (415,45,48,0,378,38,31,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (416,45,31,0,379,48,71,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (417,45,71,0,380,31,30,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (418,45,30,0,381,71,86,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (419,45,86,0,382,30,17,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (420,45,17,0,383,86,18,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (421,45,18,0,384,17,54,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (422,45,54,0,385,18,110,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (423,45,110,0,386,54,164,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (424,45,164,0,387,110,102,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (425,45,102,0,388,164,165,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (426,45,165,0,389,102,111,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (427,45,111,0,390,165,83,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (428,45,83,0,391,111,96,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (429,45,96,0,392,83,91,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (430,45,91,0,393,96,49,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (431,45,49,0,394,91,30,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (432,45,30,0,395,49,126,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (433,45,126,0,396,30,27,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (434,45,27,0,397,126,115,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (435,45,115,0,398,27,166,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (436,45,166,0,399,115,167,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (437,45,167,0,400,166,168,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (438,45,168,0,401,167,169,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (439,45,169,0,402,168,170,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (440,45,170,0,403,169,171,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (441,45,171,0,404,170,172,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (442,45,172,0,405,171,173,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (443,45,173,0,406,172,174,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (444,45,174,0,407,173,175,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (445,45,175,0,408,174,173,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (446,45,173,0,409,175,176,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (447,45,176,0,410,173,177,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (448,45,177,0,411,176,128,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (449,45,128,0,412,177,104,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (450,45,104,0,413,128,178,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (451,45,178,0,414,104,83,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (452,45,83,0,415,178,50,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (453,45,50,0,416,83,133,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (454,45,133,0,417,50,54,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (455,45,54,0,418,133,26,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (456,45,26,0,419,54,116,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (457,45,116,0,420,26,117,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (458,45,117,0,421,116,86,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (459,45,86,0,422,117,114,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (460,45,114,0,423,86,29,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (461,45,29,0,424,114,112,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (462,45,112,0,425,29,164,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (463,45,164,0,426,112,28,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (464,45,28,0,427,164,100,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (465,45,100,0,428,28,30,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (466,45,30,0,429,100,31,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (467,45,31,0,430,30,179,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (468,45,179,0,431,31,136,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (469,45,136,0,432,179,72,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (470,45,72,0,433,136,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (471,45,32,0,434,72,133,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (472,45,133,0,435,32,121,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (473,45,121,0,436,133,64,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (474,45,64,0,437,121,114,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (475,45,114,0,438,64,103,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (476,45,103,0,439,114,45,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (477,45,45,0,440,103,44,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (478,45,44,0,441,45,152,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (479,45,152,0,442,44,113,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (480,45,113,0,443,152,43,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (481,45,43,0,444,113,66,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (482,45,66,0,445,43,23,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (483,45,23,0,446,66,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (484,45,32,0,447,23,103,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (485,45,103,0,448,32,92,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (486,45,92,0,449,103,54,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (487,45,54,0,450,92,79,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (488,45,79,0,451,54,33,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (489,45,33,0,452,79,87,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (490,45,87,0,453,33,180,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (491,45,180,0,454,87,32,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (492,45,32,0,455,180,122,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (493,45,122,0,456,32,84,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (494,45,84,0,457,122,29,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (495,45,29,0,458,84,28,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (496,45,28,0,459,29,45,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (497,45,45,0,460,28,74,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (498,45,74,0,461,45,122,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (499,45,122,0,462,74,23,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (500,45,23,0,463,122,181,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (501,45,181,0,464,23,53,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (502,45,53,0,465,181,79,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (503,45,79,0,466,53,41,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (504,45,41,0,467,79,136,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (505,45,136,0,468,41,108,2,1061223634,1,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (506,45,108,0,469,136,0,2,1061223634,1,121,'',0);





CREATE TABLE ezsearch_return_count (
  id int(11) NOT NULL auto_increment,
  phrase_id int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;





INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (1,1,1061223659,2);





CREATE TABLE ezsearch_search_phrase (
  id int(11) NOT NULL auto_increment,
  phrase varchar(250) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (1,'news');





CREATE TABLE ezsearch_word (
  id int(11) NOT NULL auto_increment,
  word varchar(150) default NULL,
  object_count int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_word (word)
) TYPE=MyISAM;





INSERT INTO ezsearch_word (id, word, object_count) VALUES (5,'test',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (6,'media',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (7,'news',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (8,'latest',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (9,'on',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (10,'our',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (11,'intranet',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (12,'files',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (13,'documents',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (14,'lorem',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (15,'ipsum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (16,'dolor',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (17,'sit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (18,'amet',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (19,'consectetuer',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (20,'adipiscing',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (21,'elit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (22,'maecenas',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (23,'augue',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (24,'leo',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (25,'hendrerit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (26,'vitae',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (27,'dapibus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (28,'ut',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (29,'interdum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (30,'at',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (31,'ligula',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (32,'sed',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (33,'sem',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (34,'rhoncus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (35,'pulvinar',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (36,'quisque',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (37,'fringilla',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (38,'nibh',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (39,'odio',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (40,'convallis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (41,'mollis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (42,'proin',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (43,'lacus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (44,'nec',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (45,'neque',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (46,'vivamus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (47,'volutpat',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (48,'id',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (49,'purus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (50,'nulla',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (51,'varius',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (52,'dictum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (53,'est',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (54,'sapien',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (55,'pede',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (56,'mattis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (57,'in',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (58,'a',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (59,'mi',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (60,'vestibulum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (61,'ante',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (62,'primis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (63,'faucibus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (64,'orci',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (65,'luctus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (66,'et',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (67,'ultrices',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (68,'posuere',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (69,'cubilia',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (70,'curae',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (71,'phasellus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (72,'arcu',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (73,'justo',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (74,'suspendisse',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (75,'quis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (76,'turpis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (77,'pretium',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (78,'scelerisque',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (79,'fusce',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (80,'dignissim',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (81,'metus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (82,'rutrum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (83,'risus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (84,'eu',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (85,'venenatis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (86,'velit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (87,'magna',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (88,'ac',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (89,'quam',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (90,'morbi',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (91,'non',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (92,'eleifend',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (93,'consequat',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (94,'malesuada',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (95,'porttitor',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (96,'pellentesque',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (97,'egestas',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (98,'nunc',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (99,'curabitur',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (100,'feugiat',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (101,'dui',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (102,'etiam',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (103,'fermentum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (104,'ornare',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (105,'urna',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (106,'cras',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (107,'imperdiet',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (108,'felis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (109,'diam',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (110,'viverra',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (111,'euismod',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (112,'vel',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (113,'libero',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (114,'mauris',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (115,'aliquam',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (116,'enim',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (117,'nam',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (118,'blandit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (119,'vulputate',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (120,'aliquet',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (121,'tempus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (122,'facilisis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (123,'massa',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (124,'semper',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (125,'sodales',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (126,'erat',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (127,'iaculis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (128,'nullam',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (129,'gravida',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (130,'donec',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (131,'tempor',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (132,'elementum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (133,'nonummy',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (134,'wisi',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (135,'nisl',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (136,'vehicula',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (137,'facilisi',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (138,'tincidunt',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (139,'tortor',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (140,'congue',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (141,'cum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (142,'sociis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (143,'natoque',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (144,'penatibus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (145,'magnis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (146,'dis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (147,'parturient',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (148,'montes',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (149,'nascetur',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (150,'ridiculus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (151,'mus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (152,'sagittis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (153,'aenean',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (154,'eros',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (155,'cursus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (156,'lacinia',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (157,'duis',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (158,'condimentum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (159,'lectus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (160,'commodo',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (161,'integer',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (162,'accumsan',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (163,'sollicitudin',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (164,'ultricies',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (165,'molestie',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (166,'class',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (167,'aptent',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (168,'taciti',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (169,'sociosqu',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (170,'ad',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (171,'litora',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (172,'torquent',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (173,'per',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (174,'conubia',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (175,'nostra',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (176,'inceptos',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (177,'hymenaeos',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (178,'ullamcorper',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (179,'praesent',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (180,'pharetra',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (181,'eget',1);





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





INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-version','3.2');
INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-release','6');





CREATE TABLE ezsubtree_notification_rule (
  id int(11) NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  use_digest int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
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










CREATE TABLE ezurl_object_link (
  url_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  PRIMARY KEY  (url_id,contentobject_attribute_id,contentobject_attribute_version)
) TYPE=MyISAM;










CREATE TABLE ezurlalias (
  id int(11) NOT NULL auto_increment,
  source_url text NOT NULL,
  source_md5 varchar(32) default NULL,
  destination_url text NOT NULL,
  is_internal int(11) NOT NULL default '1',
  forward_to_id int(11) NOT NULL default '0',
  is_wildcard int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezurlalias_source_md5 (source_md5),
  KEY ezurlalias_source_url (source_url(255)),
  KEY ezurlalias_desturl (destination_url(200))
) TYPE=MyISAM;





INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (21,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/44',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (22,'files','45b963397aa40d4a0063e0d85e4fe7a1','content/view/full/45',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (23,'files/documents','2d30f25cef1a92db784bc537e8bf128d','content/view/full/46',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (24,'news/intranet_news','86e1d5cd2c5eb047bdef7588e99889a7','content/view/full/47',1,0,0);





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
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (40,'test','test@test.com',2,'be778b473235e210cc577056226536a4');





CREATE TABLE ezuser_accountkey (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  hash_key varchar(32) NOT NULL default '',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;










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
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (30,3,13);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (28,1,11);





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





CREATE TABLE ezvattype (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  percentage float default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





INSERT INTO ezvattype (id, name, percentage) VALUES (1,'Std',0);





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






