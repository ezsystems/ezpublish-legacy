









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





INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (2,0,'Article','article','<title>',14,14,1024392098,1066907423);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (3,0,'User group','user_group','<name>',14,14,1024392098,1048494743);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1066916721);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (5,0,'Image','image','<name>',8,14,1031484992,1048494784);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (12,0,'File','file','<name>',14,14,1052385472,1052385669);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (15,0,'Template look','template_look','<title>',14,14,1066390045,1069412609);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (19,0,'Feedback form','feedback_form','<name>',14,14,1068027045,1068027439);





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





INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1);
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
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (187,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (188,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (181,0,19,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (182,0,19,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (183,0,19,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (185,0,19,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (184,0,19,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (12,0,4,'user_account','User account','ezuser',1,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1);





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
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (19,1,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (19,0,1,'Content');





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





INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (1,14,1,1,'Corporate',4,0,1033917596,1069762950,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (10,14,2,4,'Anonymous User',2,0,1033920665,1072180818,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (41,14,3,1,'Media',1,0,1060695457,1060695457,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (42,14,11,1,'Setup',1,0,1066383068,1066383068,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (43,14,11,14,'Classes',8,0,1066384365,1067950307,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (45,14,11,14,'Look and feel',9,0,1066388816,1067950326,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (47,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (122,14,1,5,'New Image',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (49,14,4,1,'News',1,0,1066398020,1066398020,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (51,14,1,14,'New Setup link',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (53,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (56,14,11,15,'Corporate',62,0,1066643397,1069840811,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (58,14,4,1,'Business news',1,0,1066729196,1066729196,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (59,14,4,1,'Off topic',1,0,1066729211,1066729211,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (60,14,4,1,'Reports',2,0,1066729226,1066729241,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (61,14,4,1,'Staff news',1,0,1066729258,1066729258,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (135,14,1,1,'General info',2,0,1067936571,1069757266,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (136,14,1,10,'About',5,0,1067937053,1069757111,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (137,14,1,19,'Contact us',4,0,1068027382,1069761690,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (138,14,4,2,'New website',1,0,1069755162,1069755162,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (129,14,1,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (144,14,1,10,'Support',1,0,1069757581,1069757581,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (127,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (142,14,1,10,'Career',1,0,1069757199,1069757199,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (143,14,1,10,'Shop info',1,0,1069757424,1069757424,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (83,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (84,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (85,14,5,1,'New Folder',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (88,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (140,14,1,10,'PublishABC',1,0,1069756410,1069756410,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (139,14,1,10,'Top 100 set',1,0,1069756326,1069756326,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (91,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (92,14,4,2,'Live from Top fair 2003',6,0,1066828821,1069755437,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (94,14,4,2,'Mr Smith joined us',3,0,1066829047,1069755309,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (96,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (126,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (145,14,1,10,'Development',1,0,1069757729,1069757729,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (103,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (104,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (105,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (106,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (133,14,1,1,'Products',1,0,1067872500,1067872500,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (134,14,1,1,'Services',1,0,1067872529,1067872529,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (115,14,11,14,'Cache',3,0,1066991725,1067950265,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (116,14,11,14,'URL translator',2,0,1066992054,1067950343,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (117,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (146,14,2,3,'Anonymous Users',1,0,1072180743,1072180743,1,'');





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





INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (157,'eng-GB',1,58,4,'Business news',0,0,0,0,'business news','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (158,'eng-GB',1,58,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (159,'eng-GB',1,59,4,'Off topic',0,0,0,0,'off topic','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (160,'eng-GB',1,59,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (161,'eng-GB',2,60,4,'Reports',0,0,0,0,'reports','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (162,'eng-GB',2,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (163,'eng-GB',1,61,4,'Staff news',0,0,0,0,'staff news','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (164,'eng-GB',1,61,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (251,'eng-GB',6,92,1,'Live from Top fair 2003',0,0,0,0,'live from top fair 2003','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (476,'eng-GB',1,139,140,'Top 100 set',0,0,0,0,'top 100 set','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (477,'eng-GB',1,139,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A collection of music from the year 2003. The best of the best. All top of the charts from Top 100. </paragraph>\n  <paragraph>Mona will be smarting from the lacklustre chart position of her new album &apos;Up and Go&apos;. It&apos;s come in at No. 234 when surely it should have snagged the top spot. Fellow babe July will be smarting too, with her new CD manages a poor No. 343. But for Tim Tim, whose new album &apos;InOn&apos; doesn&apos;t even manage to scrape into the top 20. The once mighty seem fragile. Meanwhile someone who&apos;s reputation has been seeming really fragile is Joap Jackson, and he romps to No. 1.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (478,'eng-GB',1,139,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"top_100_set.\"\n         suffix=\"\"\n         basename=\"top_100_set\"\n         dirpath=\"var/corporate/storage/images/products/top_100_set/478-1-eng-GB\"\n         url=\"var/corporate/storage/images/products/top_100_set/478-1-eng-GB/top_100_set.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069756112\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (255,'eng-GB',6,92,123,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (276,'eng-GB',6,92,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (254,'eng-GB',6,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"live_from_top_fair_2003.\"\n         suffix=\"\"\n         basename=\"live_from_top_fair_2003\"\n         dirpath=\"var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB\"\n         url=\"var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB/live_from_top_fair_2003.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"254\"\n            attribute_version=\"5\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (422,'eng-GB',1,133,4,'Products',0,0,0,0,'products','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (423,'eng-GB',1,133,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find information about our products.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (424,'eng-GB',1,134,4,'Services',0,0,0,0,'services','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (425,'eng-GB',1,134,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about our services.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (481,'eng-GB',1,140,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"publishabc.\"\n         suffix=\"\"\n         basename=\"publishabc\"\n         dirpath=\"var/corporate/storage/images/products/publishabc/481-1-eng-GB\"\n         url=\"var/corporate/storage/images/products/publishabc/481-1-eng-GB/publishabc.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069756339\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (479,'eng-GB',1,140,140,'PublishABC',0,0,0,0,'publishabc','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (480,'eng-GB',1,140,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>PublishABC is an open source content management system (CMS) and development framework.</paragraph>\n  <paragraph>With advanced functionality for e-commerce sites, news-sites, forums, picture galleries, intranets and much more, you can build your dynamic websites fast and reliable. PublishABC is a very flexible system for everyone that wants to share their information on the web.</paragraph>\n  <paragraph>With PublishABC you can easily create, edit and publish all sorts of content and the day-to-day work can easily be done by non-technical persons.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (253,'eng-GB',6,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Four crew members are on-site from the 20th to the 24th reporting live from the hall. The following text contains a live report from the fair.</paragraph>\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</paragraph>\n  <paragraph>Speaking of community, we&apos;re happy that the community show up. It is always interesting and inspiring to meet face to face and to discuss various topics. We certainly hope that more community people will show up during the rest of the week.</paragraph>\n  <paragraph>Anyway, we were talking about the benefits of open and free software. As mentioned, some people still don&apos;t get it; however, when explained, we&apos;re met by replies such as:</paragraph>\n  <paragraph>&quot;Amazing!&quot; - big smile...</paragraph>\n  <paragraph>&quot;I would have to pay a lot of money for this feature from company...&quot;</paragraph>\n  <paragraph>- from a guy who came to us from one of the neighboring stands (right after watching a presentation there).</paragraph>\n  <paragraph>Some companies are just interested in talking to potential customers who are willing to spend millions on rigid solutions. This is not our policy. We&apos;re very flexible and eager to talk to a wide range of people. If you have the chance visit the fair, feel free to stop by. Our stand is open and prepared for everyone. Anybody can sit down together with our representatives and receive an on-site, hands-on demonstration of the system.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (252,'eng-GB',6,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This week, some members of the crew are reporting live from Hall A, attending &quot;Top fair 2003&quot;. Top fair 2003 is an international trade fair for Information Technology and Telecommunications. The trade fair is held for the 5th time.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (150,'eng-GB',62,56,157,'Corporate',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (262,'eng-GB',3,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Mr Smith started today at our firm. He will be in charge of the computer matrix. We hired him from his previous workplace at Nemos place.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (261,'eng-GB',3,94,1,'Mr Smith joined us',0,0,0,0,'mr smith joined us','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (265,'eng-GB',3,94,123,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (278,'eng-GB',3,94,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (264,'eng-GB',3,94,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"mr_smith_joined_us.\"\n         suffix=\"\"\n         basename=\"mr_smith_joined_us\"\n         dirpath=\"var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB\"\n         url=\"var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB/mr_smith_joined_us.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (263,'eng-GB',3,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>His name is Mr Smith and I hope you all welcome him into our ranks.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (152,'eng-GB',62,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate.gif\"\n         original_filename=\"corporate.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069839369\">\n  <original attribute_id=\"152\"\n            attribute_version=\"61\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069839370\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069839370\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069844204\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (474,'eng-GB',1,138,123,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (470,'eng-GB',1,138,1,'New website',0,0,0,0,'new website','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (471,'eng-GB',1,138,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We have now released our new website. I hope that it is easier to find iformation about the company and what we offer from now on.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (472,'eng-GB',1,138,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The feedback we have gotten so far indicates this. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (473,'eng-GB',1,138,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_website.\"\n         suffix=\"\"\n         basename=\"new_website\"\n         dirpath=\"var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB\"\n         url=\"var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB/new_website.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069755091\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (153,'eng-GB',62,56,160,'corporate_blue',0,0,0,0,'corporate_blue','ezpackage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (154,'eng-GB',62,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (475,'eng-GB',1,138,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (151,'eng-GB',62,56,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (428,'eng-GB',5,136,140,'About',0,0,0,0,'about','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (429,'eng-GB',5,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n  <paragraph>My company is located in Skien, Norway with 223 employees. My company was founded in May 1973, in Skien, Norway,</paragraph>\n  <paragraph>Corporate Vision</paragraph>\n  <paragraph>&quot;We shall be an open minded, dedicated team helping people and businesses around the world to share information and knowledge&quot;.</paragraph>\n  <paragraph>\n    <line>Corporate Values</line>\n    <line>Open - We shall always meet the world with an open mind and an open heart, always welcoming other people, ideas and knowledge.</line>\n  </paragraph>\n  <paragraph>Sharing - We shall share our information, ideas and knowledge and pull together as a team, both internally and together with the community. Together we will accomplish great things.</paragraph>\n  <paragraph>Innovative - We shall be innovative people creating innovative solutions.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (430,'eng-GB',5,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/corporate/storage/images/information/about/430-5-eng-GB\"\n         url=\"var/corporate/storage/images/information/about/430-5-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"430\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (484,'eng-GB',1,142,140,'Career',0,0,0,0,'career','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (485,'eng-GB',1,142,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We are now hiring the following</paragraph>\n  <paragraph>System developers </paragraph>\n  <paragraph>You will be part of the eZ development crew, developing our products. You will also be part of customer projects either as a project leader and/or developer. Very good programming skills are required. You must know object oriented programming and design using C++ and PHP. You should be familiar with UML, SQL, XML, XHTML, SOAP/XML-RPC and Linux/Unix. Experience with the Qt Toolkit is a plus. Experience from open source projects is a plus. Fresh graduates may also apply, if you have very good programming knowledge. </paragraph>\n  <paragraph>\n    <line>Applications will be accepted continually.</line>\n    <line>Place of work: Skien, Norway.</line>\n    <line>Conditions: Depending on qualifications.</line>\n    <line>Very good English skills are required. Other languages is a plus.</line>\n  </paragraph>\n  <paragraph>\n    <line>Questions and applications with CV&apos;s are to be sent by e-mail to:</line>\n    <line>The Boss</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (486,'eng-GB',1,142,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"career.\"\n         suffix=\"\"\n         basename=\"career\"\n         dirpath=\"var/corporate/storage/images/information/career/486-1-eng-GB\"\n         url=\"var/corporate/storage/images/information/career/486-1-eng-GB/career.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757141\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (426,'eng-GB',2,135,4,'General info',0,0,0,0,'general info','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (427,'eng-GB',2,135,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find information about this company.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (1,'eng-GB',4,1,4,'Corporate',0,0,0,0,'corporate','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to the website of MyCompany. Here you can read about our company, our products and services. Take a tour through our digitised archive, and find out more about the comapny and what we offer. </paragraph>\n  <paragraph>Our mission is to keep our customers in touch with the latest updates, releases and products.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (487,'eng-GB',1,143,140,'Shop info',0,0,0,0,'shop info','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (488,'eng-GB',1,143,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We are committed to your satisfaction. We will do everything we can to make a present or potential customer a satisfied customer. </paragraph>\n  <paragraph>On these pages we will outline our terms &amp; conditions and your rights and privacy as a customer.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (489,'eng-GB',1,143,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"shop_info.\"\n         suffix=\"\"\n         basename=\"shop_info\"\n         dirpath=\"var/corporate/storage/images/general_info/shop_info/489-1-eng-GB\"\n         url=\"var/corporate/storage/images/general_info/shop_info/489-1-eng-GB/shop_info.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757397\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (493,'eng-GB',1,145,140,'Development',0,0,0,0,'development','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (494,'eng-GB',1,145,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Use the crew for developments and enhancements </paragraph>\n  <paragraph>Hire a guy to help you with your solution. We and our friends have highly skilled developers ready to advice you. Consulting ranges from feature requests, installation help, upgrade help, migration, integration and solutions. </paragraph>\n  <paragraph>Often we help with installation, migration, integration, programming etc. We can also deliver a turn key web solution based on PublishABC. Let us know what we can do for you. Our standard hourly rate is $ 129 and our minimum asking price for projects is $ 2344. </paragraph>\n  <paragraph>Contact us if there is something we can do for you.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (495,'eng-GB',1,145,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"development.\"\n         suffix=\"\"\n         basename=\"development\"\n         dirpath=\"var/corporate/storage/images/services/development/495-1-eng-GB\"\n         url=\"var/corporate/storage/images/services/development/495-1-eng-GB/development.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757596\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (492,'eng-GB',1,144,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"support.\"\n         suffix=\"\"\n         basename=\"support\"\n         dirpath=\"var/corporate/storage/images/services/support/492-1-eng-GB\"\n         url=\"var/corporate/storage/images/services/support/492-1-eng-GB/support.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757493\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (490,'eng-GB',1,144,140,'Support',0,0,0,0,'support','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (491,'eng-GB',1,144,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>&quot; Use the crew for support - first hand information&quot; </paragraph>\n  <paragraph>To guarantee our customers the best possible result we offer a support program. The professionals are ready to help you with your problem. </paragraph>\n  <paragraph>\n    <line>What you will get with support</line>\n    <line>The support will cover answers by email and phone. If you need help to configure or develop features, we can help you doing that directly on your server, or as a new feature to a distribution on PublishABC. We will also be able to give advise on how to solve problems with development in PublishABC, if you want to do most of the work yourself.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (441,'eng-GB',4,137,181,'Contact us',0,0,0,0,'contact us','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (442,'eng-GB',4,137,182,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Fill in the form below if you have any feedback. Please remember to fill in your e-mail address.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (443,'eng-GB',4,137,183,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (445,'eng-GB',4,137,185,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (444,'eng-GB',4,137,184,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (437,'eng-GB',62,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (461,'eng-GB',62,56,187,'ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (468,'eng-GB',62,56,188,'Copyright &copy; eZ systems as 1999-2004',0,0,0,0,'copyright &copy; ez systems as 1999-2004','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (496,'eng-GB',1,146,6,'Anonymous Users',0,0,0,0,'anonymous users','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (497,'eng-GB',1,146,7,'User group for the anonymous user',0,0,0,0,'user group for the anonymous user','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (19,'eng-GB',2,10,8,'Anonymous',0,0,0,0,'anonymous','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (20,'eng-GB',2,10,9,'User',0,0,0,0,'user','ezstring');
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string, data_type_string) VALUES (21,'eng-GB',2,10,12,'',0,0,0,0,'','ezuser');





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
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (58,'Business news',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (59,'Off topic',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (60,'Reports',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (60,'Reports',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (61,'Staff news',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (137,'Contact us',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',36,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (135,'Information',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (136,'About',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranetyy',30,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (136,'About',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (134,'Services',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (88,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',33,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranetyy',31,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',32,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',12,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',13,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (91,'New Template look',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',18,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (92,'eZ systems - reporting live from Munich',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (94,'Mr xxx joined us',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',39,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (92,'eZ systems - reporting live from Munich',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (96,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (133,'Products',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (92,'eZ systems - reporting live from Munich',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (92,'eZ systems - reporting live from Munich',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',34,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Intranet',20,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (94,'Mr xxx joined us',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (103,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (104,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (137,'Contact us',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',43,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',44,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (92,'eZ systems - reporting live from Munich',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',45,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',46,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',48,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',47,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',51,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',53,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',54,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',55,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',56,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',58,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',59,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',60,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',57,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (136,'About',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (138,'New website',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (94,'Mr Smith joined us',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (92,'Live from Top fair 2003',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (139,'Top 100 set',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (140,'PublishABC',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Corporate',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (137,'Contact us',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Corporate',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (136,'About',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (136,'About',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (142,'Career',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (135,'General info',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',62,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (143,'Shop info',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'Corporate',61,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (144,'Support',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (145,'Development',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (137,'Contact us',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (146,'Anonymous Users',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (2,1,1,4,1,1,'/1/2/',8,1,0,'',2);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (5,1,4,1,0,1,'/1/5/',1,1,0,'users',5);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (43,1,41,1,1,1,'/1/43/',9,1,0,'media',43);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (45,46,43,8,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (47,46,45,9,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (50,2,49,1,1,2,'/1/2/50/',9,1,1,'news',50);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (56,50,58,1,1,3,'/1/2/50/56/',9,1,0,'news/business_news',56);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (54,48,56,62,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/corporate',54);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (57,50,59,1,1,3,'/1/2/50/57/',9,1,0,'news/off_topic',57);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (58,50,60,2,1,3,'/1/2/50/58/',9,1,0,'news/reports',58);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (59,50,61,1,1,3,'/1/2/50/59/',9,1,0,'news/staff_news',59);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (108,2,135,2,1,2,'/1/2/108/',9,1,4,'general_info',108);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (109,108,136,5,1,3,'/1/2/108/109/',9,1,0,'general_info/about',109);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (110,2,137,4,1,2,'/1/2/110/',9,1,5,'contact_us',110);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (111,57,138,1,1,4,'/1/2/50/57/111/',9,1,0,'news/off_topic/new_website',111);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (112,58,92,6,1,4,'/1/2/50/58/112/',9,1,0,'news/reports/live_from_top_fair_2003',81);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (114,106,140,1,1,3,'/1/2/106/114/',9,1,0,'products/publishabc',114);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (116,108,142,1,1,3,'/1/2/108/116/',9,1,0,'general_info/career',116);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (117,108,143,1,1,3,'/1/2/108/117/',9,1,0,'general_info/shop_info',117);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (81,56,92,6,1,4,'/1/2/50/56/81/',9,1,0,'news/business_news/live_from_top_fair_2003',81);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (113,106,139,1,1,3,'/1/2/106/113/',9,1,0,'products/top_100_set',113);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (83,59,94,3,1,4,'/1/2/50/59/83/',9,1,0,'news/staff_news/mr_smith_joined_us',83);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (118,107,144,1,1,3,'/1/2/107/118/',9,1,0,'services/support',118);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (119,107,145,1,1,3,'/1/2/107/119/',9,1,0,'services/development',119);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (106,2,133,1,1,2,'/1/2/106/',9,1,2,'products',106);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (107,2,134,1,1,2,'/1/2/107/',9,1,3,'services',107);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (95,46,115,3,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (96,46,116,2,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (120,5,146,1,1,2,'/1/5/120/',9,1,0,'users/anonymous_users',120);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) VALUES (121,120,10,2,1,3,'/1/5/120/121/',9,1,0,'users/anonymous_users/anonymous_user',121);





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





INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (4,4,14,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (439,11,14,1,1033920737,1033920746,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (440,12,14,1,1033920760,1033920775,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (441,13,14,1,1033920786,1033920794,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (442,14,14,1,1033920808,1033920830,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (472,41,14,1,1060695450,1060695457,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (473,42,14,1,1066383039,1066383068,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (475,44,14,1,1066384403,1066384457,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (482,46,14,2,1066389882,1066389902,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (718,139,14,1,1069756109,1069756326,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (735,56,14,62,1069839663,1069840810,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (490,49,14,1,1066398007,1066398020,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (717,92,14,6,1069755358,1069755437,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (520,58,14,1,1066729186,1066729195,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (521,59,14,1,1066729202,1066729210,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (523,60,14,2,1066729234,1066729241,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (524,61,14,1,1066729249,1066729258,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (730,144,14,1,1069757491,1069757581,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (719,140,14,1,1069756337,1069756410,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (683,45,14,9,1067950316,1067950326,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (682,43,14,8,1067950294,1067950307,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (681,115,14,3,1067950253,1067950265,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (716,94,14,3,1069755194,1069755309,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (725,142,14,1,1069757139,1069757199,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (724,136,14,5,1069757094,1069757111,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (728,143,14,1,1069757395,1069757424,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (726,135,14,2,1069757215,1069757265,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (684,116,14,2,1067950335,1067950343,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (674,134,14,1,1067872510,1067872528,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (733,1,14,4,1069762220,1069762950,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (732,137,14,4,1069761671,1069761690,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (731,145,14,1,1069757594,1069757729,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (673,133,14,1,1067872484,1067872500,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (715,138,14,1,1069755089,1069755162,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (736,146,14,1,1072180699,1072180743,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (737,10,14,2,1072180749,1072180818,1,0,0);





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





INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (1,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (2,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (3,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (4,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (5,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (6,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (7,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (8,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (9,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (10,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (11,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (12,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (13,430,'var/corporate/storage/images/information/about/430-3-eng-GB/about.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (14,473,'var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB/new_website.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (15,264,'var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB/mr_smith_joined_us.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (16,254,'var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB/live_from_top_fair_2003.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (17,478,'var/corporate/storage/images/products/top_100_set/478-1-eng-GB/top_100_set.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (18,481,'var/corporate/storage/images/products/publishabc/481-1-eng-GB/publishabc.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (19,430,'var/corporate/storage/images/about/430-4-eng-GB/about.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (20,430,'var/corporate/storage/images/information/about/430-5-eng-GB/about.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (21,486,'var/corporate/storage/images/information/career/486-1-eng-GB/career.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (22,489,'var/corporate/storage/images/general_info/shop_info/489-1-eng-GB/shop_info.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (23,492,'var/corporate/storage/images/services/support/492-1-eng-GB/support.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (24,495,'var/corporate/storage/images/services/development/495-1-eng-GB/development.');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (26,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (27,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (28,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (29,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (30,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (31,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (32,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (33,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (34,109,'var/storage/original/image/phpvzmRGW.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (35,109,'var/storage/original/image/phppIJtoa.jpg');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (36,109,'var/storage/original/image/phpAhcEu9.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (37,103,'var/storage/original/image/phpWJgae7.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (38,109,'var/storage/original/image/phpbVfzkm.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (39,103,'var/storage/original/image/php7ZhvcB.png');
INSERT INTO ezimagefile (id, contentobject_attribute_id, filepath) VALUES (40,103,'var/storage/original/image/phpXz5esv.jpg');





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





INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (4,8,2,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (144,4,1,1,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (147,210,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (146,209,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (148,9,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (150,11,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (151,12,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (152,13,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (153,14,1,13,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (182,41,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (183,42,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (185,44,1,44,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (193,46,2,44,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (443,56,62,48,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (201,49,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (230,58,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (420,92,6,56,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (441,1,4,1,8,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (231,59,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (233,60,2,50,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (234,61,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (437,144,1,107,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (423,140,1,106,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (386,45,9,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (385,43,8,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (384,115,3,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (377,134,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (432,142,1,108,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (419,94,3,59,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (431,136,5,108,9,1,1,2,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (433,135,2,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (421,92,6,58,9,1,0,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (435,143,1,108,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (387,116,2,46,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (422,139,1,106,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (440,137,4,2,9,1,1,115,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (438,145,1,107,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (376,133,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (418,138,1,57,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (444,146,1,5,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (446,10,2,120,9,1,1,-1,0);





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
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (370,24,'create','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (371,24,'create','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (372,24,'create','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (341,8,'read','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (369,24,'read','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (373,24,'create','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (374,24,'edit','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (380,1,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (379,1,'login','user','*');





CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  policy_id int(11) default NULL,
  identifier varchar(255) NOT NULL default '',
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (289,371,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (290,371,'Section',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (288,370,'Section',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (287,370,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (291,372,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (292,372,'Section',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (293,373,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (294,373,'Section',0,'create','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (295,374,'Class',0,'edit','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (300,380,'Class',0,'read','content');





CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;





INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (555,291,'12');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (554,291,'1');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (551,289,'16');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (550,288,'4');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (548,287,'13');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (549,287,'2');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (553,290,'5');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (552,289,'17');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (547,287,'1');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (556,292,'6');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (557,293,'6');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (558,293,'7');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (559,294,'7');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (560,295,'1');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (561,295,'2');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (562,295,'6');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (563,295,'7');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (564,295,'12');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (565,295,'13');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (566,295,'16');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (567,295,'17');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (568,295,'18');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (591,300,'12');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (590,300,'10');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (589,300,'5');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (588,300,'2');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (587,300,'1');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (592,300,'19');





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
INSERT INTO ezrole (id, version, name, value) VALUES (24,0,'Intranet',NULL);





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





INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6625,133,1348,0,0,0,1348,1,1067872500,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6626,133,1348,0,1,1348,1349,1,1067872500,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6627,133,1349,0,2,1348,1350,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6628,133,1350,0,3,1349,1351,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6629,133,1351,0,4,1350,1352,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6630,133,1352,0,5,1351,1353,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6631,133,1353,0,6,1352,1354,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6632,133,1354,0,7,1353,1355,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6633,133,1355,0,8,1354,1348,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6634,133,1348,0,9,1355,1349,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6635,133,1349,0,10,1348,1350,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6636,133,1350,0,11,1349,1351,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6637,133,1351,0,12,1350,1352,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6638,133,1352,0,13,1351,1353,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6639,133,1353,0,14,1352,1354,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6640,133,1354,0,15,1353,1355,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6641,133,1355,0,16,1354,1348,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6642,133,1348,0,17,1355,0,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6643,139,1356,0,0,0,1357,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6644,139,1357,0,1,1356,1358,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6645,139,1358,0,2,1357,1356,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6646,139,1356,0,3,1358,1357,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6647,139,1357,0,4,1356,1358,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6648,139,1358,0,5,1357,1359,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6649,139,1359,0,6,1358,1360,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6650,139,1360,0,7,1359,1361,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6651,139,1361,0,8,1360,1362,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6652,139,1362,0,9,1361,1363,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6653,139,1363,0,10,1362,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6654,139,1364,0,11,1363,1365,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6655,139,1365,0,12,1364,1366,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6656,139,1366,0,13,1365,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6657,139,1364,0,14,1366,1367,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6658,139,1367,0,15,1364,1361,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6659,139,1361,0,16,1367,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6660,139,1364,0,17,1361,1367,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6661,139,1367,0,18,1364,1368,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6662,139,1368,0,19,1367,1356,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6663,139,1356,0,20,1368,1361,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6664,139,1361,0,21,1356,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6665,139,1364,0,22,1361,1369,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6666,139,1369,0,23,1364,1363,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6667,139,1363,0,24,1369,1356,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6668,139,1356,0,25,1363,1357,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6669,139,1357,0,26,1356,1370,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6670,139,1370,0,27,1357,1351,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6671,139,1351,0,28,1370,1371,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6672,139,1371,0,29,1351,1372,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6673,139,1372,0,30,1371,1363,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6674,139,1363,0,31,1372,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6675,139,1364,0,32,1363,1373,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6676,139,1373,0,33,1364,1374,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6677,139,1374,0,34,1373,1375,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6678,139,1375,0,35,1374,1361,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6679,139,1361,0,36,1375,1376,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6680,139,1376,0,37,1361,1377,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6681,139,1377,0,38,1376,1378,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6682,139,1378,0,39,1377,1379,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6683,139,1379,0,40,1378,1380,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6684,139,1380,0,41,1379,1381,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6685,139,1381,0,42,1380,1382,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6686,139,1382,0,43,1381,1383,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6687,139,1383,0,44,1382,1384,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6688,139,1384,0,45,1383,1385,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6689,139,1385,0,46,1384,1386,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6690,139,1386,0,47,1385,1387,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6691,139,1387,0,48,1386,1388,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6692,139,1388,0,49,1387,1389,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6693,139,1389,0,50,1388,1390,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6694,139,1390,0,51,1389,1382,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6695,139,1382,0,52,1390,1391,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6696,139,1391,0,53,1382,1392,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6697,139,1392,0,54,1391,1393,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6698,139,1393,0,55,1392,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6699,139,1364,0,56,1393,1356,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6700,139,1356,0,57,1364,1394,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6701,139,1394,0,58,1356,1395,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6702,139,1395,0,59,1394,1396,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6703,139,1396,0,60,1395,1397,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6704,139,1397,0,61,1396,1351,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6705,139,1351,0,62,1397,1371,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6706,139,1371,0,63,1351,1372,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6707,139,1372,0,64,1371,1398,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6708,139,1398,0,65,1372,1399,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6709,139,1399,0,66,1398,1376,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6710,139,1376,0,67,1399,1377,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6711,139,1377,0,68,1376,1400,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6712,139,1400,0,69,1377,1401,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6713,139,1401,0,70,1400,1359,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6714,139,1359,0,71,1401,1402,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6715,139,1402,0,72,1359,1387,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6716,139,1387,0,73,1402,1403,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6717,139,1403,0,74,1387,1404,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6718,139,1404,0,75,1403,1405,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6719,139,1405,0,76,1404,1406,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6720,139,1406,0,77,1405,1406,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6721,139,1406,0,78,1406,1407,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6722,139,1407,0,79,1406,1377,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6723,139,1377,0,80,1407,1378,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6724,139,1378,0,81,1377,1408,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6725,139,1408,0,82,1378,1409,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6726,139,1409,0,83,1408,1410,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6727,139,1410,0,84,1409,1411,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6728,139,1411,0,85,1410,1412,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6729,139,1412,0,86,1411,1413,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6730,139,1413,0,87,1412,1414,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6731,139,1414,0,88,1413,1415,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6732,139,1415,0,89,1414,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6733,139,1364,0,90,1415,1356,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6734,139,1356,0,91,1364,1416,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6735,139,1416,0,92,1356,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6736,139,1364,0,93,1416,1417,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6737,139,1417,0,94,1364,1418,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6738,139,1418,0,95,1417,1419,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6739,139,1419,0,96,1418,1420,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6740,139,1420,0,97,1419,1421,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6741,139,1421,0,98,1420,1422,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6742,139,1422,0,99,1421,1423,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6743,139,1423,0,100,1422,1383,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6744,139,1383,0,101,1423,1424,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6745,139,1424,0,102,1383,1425,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6746,139,1425,0,103,1424,1426,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6747,139,1426,0,104,1425,1427,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6748,139,1427,0,105,1426,1428,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6749,139,1428,0,106,1427,1420,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6750,139,1420,0,107,1428,1429,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6751,139,1429,0,108,1420,1430,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6752,139,1430,0,109,1429,1431,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6753,139,1431,0,110,1430,1380,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6754,139,1380,0,111,1431,1432,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6755,139,1432,0,112,1380,1433,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6756,139,1433,0,113,1432,1413,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6757,139,1413,0,114,1433,1387,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6758,139,1387,0,115,1413,1434,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6759,139,1434,0,116,1387,1359,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6760,139,1359,0,117,1434,1360,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6761,139,1360,0,118,1359,1361,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6762,139,1361,0,119,1360,1362,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6763,139,1362,0,120,1361,1363,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6764,139,1363,0,121,1362,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6765,139,1364,0,122,1363,1365,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6766,139,1365,0,123,1364,1366,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6767,139,1366,0,124,1365,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6768,139,1364,0,125,1366,1367,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6769,139,1367,0,126,1364,1361,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6770,139,1361,0,127,1367,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6771,139,1364,0,128,1361,1367,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6772,139,1367,0,129,1364,1368,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6773,139,1368,0,130,1367,1356,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6774,139,1356,0,131,1368,1361,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6775,139,1361,0,132,1356,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6776,139,1364,0,133,1361,1369,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6777,139,1369,0,134,1364,1363,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6778,139,1363,0,135,1369,1356,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6779,139,1356,0,136,1363,1357,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6780,139,1357,0,137,1356,1370,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6781,139,1370,0,138,1357,1351,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6782,139,1351,0,139,1370,1371,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6783,139,1371,0,140,1351,1372,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6784,139,1372,0,141,1371,1363,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6785,139,1363,0,142,1372,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6786,139,1364,0,143,1363,1373,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6787,139,1373,0,144,1364,1374,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6788,139,1374,0,145,1373,1375,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6789,139,1375,0,146,1374,1361,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6790,139,1361,0,147,1375,1376,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6791,139,1376,0,148,1361,1377,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6792,139,1377,0,149,1376,1378,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6793,139,1378,0,150,1377,1379,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6794,139,1379,0,151,1378,1380,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6795,139,1380,0,152,1379,1381,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6796,139,1381,0,153,1380,1382,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6797,139,1382,0,154,1381,1383,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6798,139,1383,0,155,1382,1384,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6799,139,1384,0,156,1383,1385,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6800,139,1385,0,157,1384,1386,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6801,139,1386,0,158,1385,1387,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6802,139,1387,0,159,1386,1388,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6803,139,1388,0,160,1387,1389,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6804,139,1389,0,161,1388,1390,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6805,139,1390,0,162,1389,1382,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6806,139,1382,0,163,1390,1391,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6807,139,1391,0,164,1382,1392,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6808,139,1392,0,165,1391,1393,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6809,139,1393,0,166,1392,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6810,139,1364,0,167,1393,1356,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6811,139,1356,0,168,1364,1394,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6812,139,1394,0,169,1356,1395,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6813,139,1395,0,170,1394,1396,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6814,139,1396,0,171,1395,1397,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6815,139,1397,0,172,1396,1351,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6816,139,1351,0,173,1397,1371,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6817,139,1371,0,174,1351,1372,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6818,139,1372,0,175,1371,1398,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6819,139,1398,0,176,1372,1399,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6820,139,1399,0,177,1398,1376,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6821,139,1376,0,178,1399,1377,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6822,139,1377,0,179,1376,1400,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6823,139,1400,0,180,1377,1401,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6824,139,1401,0,181,1400,1359,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6825,139,1359,0,182,1401,1402,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6826,139,1402,0,183,1359,1387,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6827,139,1387,0,184,1402,1403,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6828,139,1403,0,185,1387,1404,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6829,139,1404,0,186,1403,1405,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6830,139,1405,0,187,1404,1406,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6831,139,1406,0,188,1405,1406,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6832,139,1406,0,189,1406,1407,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6833,139,1407,0,190,1406,1377,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6834,139,1377,0,191,1407,1378,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6835,139,1378,0,192,1377,1408,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6836,139,1408,0,193,1378,1409,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6837,139,1409,0,194,1408,1410,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6838,139,1410,0,195,1409,1411,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6839,139,1411,0,196,1410,1412,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6840,139,1412,0,197,1411,1413,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6841,139,1413,0,198,1412,1414,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6842,139,1414,0,199,1413,1415,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6843,139,1415,0,200,1414,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6844,139,1364,0,201,1415,1356,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6845,139,1356,0,202,1364,1416,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6846,139,1416,0,203,1356,1364,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6847,139,1364,0,204,1416,1417,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6848,139,1417,0,205,1364,1418,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6849,139,1418,0,206,1417,1419,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6850,139,1419,0,207,1418,1420,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6851,139,1420,0,208,1419,1421,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6852,139,1421,0,209,1420,1422,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6853,139,1422,0,210,1421,1423,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6854,139,1423,0,211,1422,1383,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6855,139,1383,0,212,1423,1424,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6856,139,1424,0,213,1383,1425,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6857,139,1425,0,214,1424,1426,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6858,139,1426,0,215,1425,1427,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6859,139,1427,0,216,1426,1428,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6860,139,1428,0,217,1427,1420,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6861,139,1420,0,218,1428,1429,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6862,139,1429,0,219,1420,1430,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6863,139,1430,0,220,1429,1431,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6864,139,1431,0,221,1430,1380,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6865,139,1380,0,222,1431,1432,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6866,139,1432,0,223,1380,1433,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6867,139,1433,0,224,1432,1413,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6868,139,1413,0,225,1433,1387,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6869,139,1387,0,226,1413,1434,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6870,139,1434,0,227,1387,0,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6871,140,1435,0,0,0,1435,10,1069756410,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6872,140,1435,0,1,1435,1435,10,1069756410,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6873,140,1435,0,2,1435,1429,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6874,140,1429,0,3,1435,1436,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6875,140,1436,0,4,1429,1437,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6876,140,1437,0,5,1436,1438,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6877,140,1438,0,6,1437,1439,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6878,140,1439,0,7,1438,1440,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6879,140,1440,0,8,1439,1441,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6880,140,1441,0,9,1440,1442,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6881,140,1442,0,10,1441,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6882,140,1380,0,11,1442,1443,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6883,140,1443,0,12,1380,1444,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6884,140,1444,0,13,1443,1399,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6885,140,1399,0,14,1444,1445,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6886,140,1445,0,15,1399,1446,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6887,140,1446,0,16,1445,1405,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6888,140,1405,0,17,1446,1447,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6889,140,1447,0,18,1405,1448,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6890,140,1448,0,19,1447,1449,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6891,140,1449,0,20,1448,1450,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6892,140,1450,0,21,1449,1449,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6893,140,1449,0,22,1450,1451,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6894,140,1451,0,23,1449,1452,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6895,140,1452,0,24,1451,1453,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6896,140,1453,0,25,1452,1454,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6897,140,1454,0,26,1453,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6898,140,1380,0,27,1454,1455,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6899,140,1455,0,28,1380,1456,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6900,140,1456,0,29,1455,1350,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6901,140,1350,0,30,1456,1457,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6902,140,1457,0,31,1350,1458,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6903,140,1458,0,32,1457,1459,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6904,140,1459,0,33,1458,1460,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6905,140,1460,0,34,1459,1461,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6906,140,1461,0,35,1460,1462,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6907,140,1462,0,36,1461,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6908,140,1380,0,37,1462,1463,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6909,140,1463,0,38,1380,1435,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6910,140,1435,0,39,1463,1429,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6911,140,1429,0,40,1435,1359,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6912,140,1359,0,41,1429,1464,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6913,140,1464,0,42,1359,1465,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6914,140,1465,0,43,1464,1441,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6915,140,1441,0,44,1465,1405,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6916,140,1405,0,45,1441,1466,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6917,140,1466,0,46,1405,1467,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6918,140,1467,0,47,1466,1468,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6919,140,1468,0,48,1467,1413,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6920,140,1413,0,49,1468,1469,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6921,140,1469,0,50,1413,1470,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6922,140,1470,0,51,1469,1353,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6923,140,1353,0,52,1470,1471,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6924,140,1471,0,53,1353,1364,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6925,140,1364,0,54,1471,1472,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6926,140,1472,0,55,1364,1399,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6927,140,1399,0,56,1472,1435,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6928,140,1435,0,57,1399,1350,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6929,140,1350,0,58,1435,1457,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6930,140,1457,0,59,1350,1473,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6931,140,1473,0,60,1457,1474,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6932,140,1474,0,61,1473,1475,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6933,140,1475,0,62,1474,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6934,140,1380,0,63,1475,1476,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6935,140,1476,0,64,1380,1368,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6936,140,1368,0,65,1476,1477,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6937,140,1477,0,66,1368,1361,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6938,140,1361,0,67,1477,1439,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6939,140,1439,0,68,1361,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6940,140,1380,0,69,1439,1364,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6941,140,1364,0,70,1380,1478,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6942,140,1478,0,71,1364,1413,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6943,140,1413,0,72,1478,1478,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6944,140,1478,0,73,1413,1479,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6945,140,1479,0,74,1478,1457,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6946,140,1457,0,75,1479,1473,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6947,140,1473,0,76,1457,1371,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6948,140,1371,0,77,1473,1480,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6949,140,1480,0,78,1371,1481,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6950,140,1481,0,79,1480,1482,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6951,140,1482,0,80,1481,1483,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6952,140,1483,0,81,1482,1484,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6953,140,1484,0,82,1483,1435,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6954,140,1435,0,83,1484,1429,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6955,140,1429,0,84,1435,1436,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6956,140,1436,0,85,1429,1437,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6957,140,1437,0,86,1436,1438,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6958,140,1438,0,87,1437,1439,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6959,140,1439,0,88,1438,1440,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6960,140,1440,0,89,1439,1441,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6961,140,1441,0,90,1440,1442,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6962,140,1442,0,91,1441,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6963,140,1380,0,92,1442,1443,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6964,140,1443,0,93,1380,1444,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6965,140,1444,0,94,1443,1399,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6966,140,1399,0,95,1444,1445,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6967,140,1445,0,96,1399,1446,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6968,140,1446,0,97,1445,1405,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6969,140,1405,0,98,1446,1447,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6970,140,1447,0,99,1405,1448,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6971,140,1448,0,100,1447,1449,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6972,140,1449,0,101,1448,1450,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6973,140,1450,0,102,1449,1449,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6974,140,1449,0,103,1450,1451,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6975,140,1451,0,104,1449,1452,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6976,140,1452,0,105,1451,1453,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6977,140,1453,0,106,1452,1454,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6978,140,1454,0,107,1453,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6979,140,1380,0,108,1454,1455,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6980,140,1455,0,109,1380,1456,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6981,140,1456,0,110,1455,1350,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6982,140,1350,0,111,1456,1457,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6983,140,1457,0,112,1350,1458,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6984,140,1458,0,113,1457,1459,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6985,140,1459,0,114,1458,1460,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6986,140,1460,0,115,1459,1461,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6987,140,1461,0,116,1460,1462,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6988,140,1462,0,117,1461,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6989,140,1380,0,118,1462,1463,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6990,140,1463,0,119,1380,1435,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6991,140,1435,0,120,1463,1429,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6992,140,1429,0,121,1435,1359,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6993,140,1359,0,122,1429,1464,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6994,140,1464,0,123,1359,1465,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6995,140,1465,0,124,1464,1441,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6996,140,1441,0,125,1465,1405,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6997,140,1405,0,126,1441,1466,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6998,140,1466,0,127,1405,1467,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (6999,140,1467,0,128,1466,1468,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7000,140,1468,0,129,1467,1413,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7001,140,1413,0,130,1468,1469,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7002,140,1469,0,131,1413,1470,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7003,140,1470,0,132,1469,1353,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7004,140,1353,0,133,1470,1471,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7005,140,1471,0,134,1353,1364,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7006,140,1364,0,135,1471,1472,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7007,140,1472,0,136,1364,1399,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7008,140,1399,0,137,1472,1435,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7009,140,1435,0,138,1399,1350,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7010,140,1350,0,139,1435,1457,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7011,140,1457,0,140,1350,1473,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7012,140,1473,0,141,1457,1474,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7013,140,1474,0,142,1473,1475,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7014,140,1475,0,143,1474,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7015,140,1380,0,144,1475,1476,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7016,140,1476,0,145,1380,1368,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7017,140,1368,0,146,1476,1477,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7018,140,1477,0,147,1368,1361,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7019,140,1361,0,148,1477,1439,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7020,140,1439,0,149,1361,1380,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7021,140,1380,0,150,1439,1364,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7022,140,1364,0,151,1380,1478,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7023,140,1478,0,152,1364,1413,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7024,140,1413,0,153,1478,1478,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7025,140,1478,0,154,1413,1479,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7026,140,1479,0,155,1478,1457,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7027,140,1457,0,156,1479,1473,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7028,140,1473,0,157,1457,1371,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7029,140,1371,0,158,1473,1480,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7030,140,1480,0,159,1371,1481,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7031,140,1481,0,160,1480,1482,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7032,140,1482,0,161,1481,1483,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7033,140,1483,0,162,1482,1484,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7034,140,1484,0,163,1483,0,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7035,134,1485,0,0,0,1485,1,1067872529,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7036,134,1485,0,1,1485,1353,1,1067872529,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7037,134,1353,0,2,1485,1354,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7038,134,1354,0,3,1353,1355,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7039,134,1355,0,4,1354,1485,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7040,134,1485,0,5,1355,1353,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7041,134,1353,0,6,1485,1354,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7042,134,1354,0,7,1353,1355,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7043,134,1355,0,8,1354,1485,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7044,134,1485,0,9,1355,0,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7045,144,1486,0,0,0,1486,10,1069757581,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7046,144,1486,0,1,1486,1487,10,1069757581,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7047,144,1487,0,2,1486,1488,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7048,144,1488,0,3,1487,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7049,144,1364,0,4,1488,1489,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7050,144,1489,0,5,1364,1405,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7051,144,1405,0,6,1489,1486,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7052,144,1486,0,7,1405,1490,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7053,144,1490,0,8,1486,1491,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7054,144,1491,0,9,1490,1492,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7055,144,1492,0,10,1491,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7056,144,1413,0,11,1492,1493,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7057,144,1493,0,12,1413,1355,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7058,144,1355,0,13,1493,1494,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7059,144,1494,0,14,1355,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7060,144,1364,0,15,1494,1367,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7061,144,1367,0,16,1364,1495,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7062,144,1495,0,17,1367,1496,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7063,144,1496,0,18,1495,1497,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7064,144,1497,0,19,1496,1498,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7065,144,1498,0,20,1497,1359,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7066,144,1359,0,21,1498,1486,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7067,144,1486,0,22,1359,1499,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7068,144,1499,0,23,1486,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7069,144,1364,0,24,1499,1500,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7070,144,1500,0,25,1364,1501,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7071,144,1501,0,26,1500,1502,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7072,144,1502,0,27,1501,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7073,144,1413,0,28,1502,1503,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7074,144,1503,0,29,1413,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7075,144,1350,0,30,1503,1399,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7076,144,1399,0,31,1350,1459,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7077,144,1459,0,32,1399,1504,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7078,144,1504,0,33,1459,1505,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7079,144,1505,0,34,1504,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7080,144,1350,0,35,1505,1351,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7081,144,1351,0,36,1350,1506,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7082,144,1506,0,37,1351,1399,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7083,144,1399,0,38,1506,1486,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7084,144,1486,0,39,1399,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7085,144,1364,0,40,1486,1486,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7086,144,1486,0,41,1364,1351,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7087,144,1351,0,42,1486,1507,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7088,144,1507,0,43,1351,1508,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7089,144,1508,0,44,1507,1481,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7090,144,1481,0,45,1508,1509,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7091,144,1509,0,46,1481,1380,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7092,144,1380,0,47,1509,1510,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7093,144,1510,0,48,1380,1511,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7094,144,1511,0,49,1510,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7095,144,1350,0,50,1511,1512,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7096,144,1512,0,51,1350,1503,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7097,144,1503,0,52,1512,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7098,144,1413,0,53,1503,1513,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7099,144,1513,0,54,1413,1514,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7100,144,1514,0,55,1513,1515,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7101,144,1515,0,56,1514,1516,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7102,144,1516,0,57,1515,1497,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7103,144,1497,0,58,1516,1457,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7104,144,1457,0,59,1497,1503,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7105,144,1503,0,60,1457,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7106,144,1350,0,61,1503,1517,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7107,144,1517,0,62,1350,1467,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7108,144,1467,0,63,1517,1518,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7109,144,1518,0,64,1467,1471,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7110,144,1471,0,65,1518,1459,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7111,144,1459,0,66,1471,1519,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7112,144,1519,0,67,1459,1514,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7113,144,1514,0,68,1519,1520,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7114,144,1520,0,69,1514,1359,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7115,144,1359,0,70,1520,1377,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7116,144,1377,0,71,1359,1521,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7117,144,1521,0,72,1377,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7118,144,1413,0,73,1521,1359,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7119,144,1359,0,74,1413,1522,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7120,144,1522,0,75,1359,1471,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7121,144,1471,0,76,1522,1435,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7122,144,1435,0,77,1471,1497,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7123,144,1497,0,78,1435,1351,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7124,144,1351,0,79,1497,1523,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7125,144,1523,0,80,1351,1371,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7126,144,1371,0,81,1523,1524,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7127,144,1524,0,82,1371,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7128,144,1413,0,83,1524,1525,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7129,144,1525,0,84,1413,1526,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7130,144,1526,0,85,1525,1471,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7131,144,1471,0,86,1526,1527,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7132,144,1527,0,87,1471,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7133,144,1413,0,88,1527,1528,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7134,144,1528,0,89,1413,1529,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7135,144,1529,0,90,1528,1399,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7136,144,1399,0,91,1529,1443,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7137,144,1443,0,92,1399,1385,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7138,144,1385,0,93,1443,1435,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7139,144,1435,0,94,1385,1511,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7140,144,1511,0,95,1435,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7141,144,1350,0,96,1511,1530,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7142,144,1530,0,97,1350,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7143,144,1413,0,98,1530,1531,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7144,144,1531,0,99,1413,1532,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7145,144,1532,0,100,1531,1361,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7146,144,1361,0,101,1532,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7147,144,1364,0,102,1361,1479,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7148,144,1479,0,103,1364,1533,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7149,144,1533,0,104,1479,1487,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7150,144,1487,0,105,1533,1488,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7151,144,1488,0,106,1487,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7152,144,1364,0,107,1488,1489,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7153,144,1489,0,108,1364,1405,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7154,144,1405,0,109,1489,1486,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7155,144,1486,0,110,1405,1490,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7156,144,1490,0,111,1486,1491,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7157,144,1491,0,112,1490,1492,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7158,144,1492,0,113,1491,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7159,144,1413,0,114,1492,1493,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7160,144,1493,0,115,1413,1355,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7161,144,1355,0,116,1493,1494,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7162,144,1494,0,117,1355,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7163,144,1364,0,118,1494,1367,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7164,144,1367,0,119,1364,1495,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7165,144,1495,0,120,1367,1496,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7166,144,1496,0,121,1495,1497,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7167,144,1497,0,122,1496,1498,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7168,144,1498,0,123,1497,1359,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7169,144,1359,0,124,1498,1486,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7170,144,1486,0,125,1359,1499,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7171,144,1499,0,126,1486,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7172,144,1364,0,127,1499,1500,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7173,144,1500,0,128,1364,1501,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7174,144,1501,0,129,1500,1502,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7175,144,1502,0,130,1501,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7176,144,1413,0,131,1502,1503,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7177,144,1503,0,132,1413,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7178,144,1350,0,133,1503,1399,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7179,144,1399,0,134,1350,1459,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7180,144,1459,0,135,1399,1504,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7181,144,1504,0,136,1459,1505,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7182,144,1505,0,137,1504,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7183,144,1350,0,138,1505,1351,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7184,144,1351,0,139,1350,1506,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7185,144,1506,0,140,1351,1399,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7186,144,1399,0,141,1506,1486,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7187,144,1486,0,142,1399,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7188,144,1364,0,143,1486,1486,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7189,144,1486,0,144,1364,1351,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7190,144,1351,0,145,1486,1507,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7191,144,1507,0,146,1351,1508,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7192,144,1508,0,147,1507,1481,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7193,144,1481,0,148,1508,1509,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7194,144,1509,0,149,1481,1380,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7195,144,1380,0,150,1509,1510,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7196,144,1510,0,151,1380,1511,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7197,144,1511,0,152,1510,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7198,144,1350,0,153,1511,1512,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7199,144,1512,0,154,1350,1503,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7200,144,1503,0,155,1512,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7201,144,1413,0,156,1503,1513,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7202,144,1513,0,157,1413,1514,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7203,144,1514,0,158,1513,1515,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7204,144,1515,0,159,1514,1516,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7205,144,1516,0,160,1515,1497,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7206,144,1497,0,161,1516,1457,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7207,144,1457,0,162,1497,1503,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7208,144,1503,0,163,1457,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7209,144,1350,0,164,1503,1517,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7210,144,1517,0,165,1350,1467,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7211,144,1467,0,166,1517,1518,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7212,144,1518,0,167,1467,1471,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7213,144,1471,0,168,1518,1459,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7214,144,1459,0,169,1471,1519,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7215,144,1519,0,170,1459,1514,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7216,144,1514,0,171,1519,1520,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7217,144,1520,0,172,1514,1359,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7218,144,1359,0,173,1520,1377,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7219,144,1377,0,174,1359,1521,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7220,144,1521,0,175,1377,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7221,144,1413,0,176,1521,1359,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7222,144,1359,0,177,1413,1522,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7223,144,1522,0,178,1359,1471,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7224,144,1471,0,179,1522,1435,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7225,144,1435,0,180,1471,1497,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7226,144,1497,0,181,1435,1351,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7227,144,1351,0,182,1497,1523,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7228,144,1523,0,183,1351,1371,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7229,144,1371,0,184,1523,1524,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7230,144,1524,0,185,1371,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7231,144,1413,0,186,1524,1525,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7232,144,1525,0,187,1413,1526,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7233,144,1526,0,188,1525,1471,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7234,144,1471,0,189,1526,1527,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7235,144,1527,0,190,1471,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7236,144,1413,0,191,1527,1528,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7237,144,1528,0,192,1413,1529,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7238,144,1529,0,193,1528,1399,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7239,144,1399,0,194,1529,1443,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7240,144,1443,0,195,1399,1385,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7241,144,1385,0,196,1443,1435,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7242,144,1435,0,197,1385,1511,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7243,144,1511,0,198,1435,1350,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7244,144,1350,0,199,1511,1530,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7245,144,1530,0,200,1350,1413,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7246,144,1413,0,201,1530,1531,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7247,144,1531,0,202,1413,1532,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7248,144,1532,0,203,1531,1361,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7249,144,1361,0,204,1532,1364,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7250,144,1364,0,205,1361,1479,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7251,144,1479,0,206,1364,1533,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7252,144,1533,0,207,1479,0,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7253,145,1443,0,0,0,1443,10,1069757729,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7254,145,1443,0,1,1443,1488,10,1069757729,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7255,145,1488,0,2,1443,1364,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7256,145,1364,0,3,1488,1489,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7257,145,1489,0,4,1364,1405,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7258,145,1405,0,5,1489,1534,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7259,145,1534,0,6,1405,1380,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7260,145,1380,0,7,1534,1535,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7261,145,1535,0,8,1380,1536,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7262,145,1536,0,9,1535,1359,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7263,145,1359,0,10,1536,1537,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7264,145,1537,0,11,1359,1413,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7265,145,1413,0,12,1537,1503,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7266,145,1503,0,13,1413,1350,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7267,145,1350,0,14,1503,1399,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7268,145,1399,0,15,1350,1459,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7269,145,1459,0,16,1399,1538,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7270,145,1538,0,17,1459,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7271,145,1497,0,18,1538,1380,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7272,145,1380,0,19,1497,1355,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7273,145,1355,0,20,1380,1539,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7274,145,1539,0,21,1355,1392,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7275,145,1392,0,22,1539,1540,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7276,145,1540,0,23,1392,1541,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7277,145,1541,0,24,1540,1542,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7278,145,1542,0,25,1541,1502,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7279,145,1502,0,26,1542,1413,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7280,145,1413,0,27,1502,1543,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7281,145,1543,0,28,1413,1350,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7282,145,1350,0,29,1543,1544,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7283,145,1544,0,30,1350,1545,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7284,145,1545,0,31,1544,1363,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7285,145,1363,0,32,1545,1521,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7286,145,1521,0,33,1363,1546,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7287,145,1546,0,34,1521,1547,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7288,145,1547,0,35,1546,1503,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7289,145,1503,0,36,1547,1548,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7290,145,1548,0,37,1503,1503,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7291,145,1503,0,38,1548,1549,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7292,145,1549,0,39,1503,1550,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7293,145,1550,0,40,1549,1380,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7294,145,1380,0,41,1550,1551,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7295,145,1551,0,42,1380,1552,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7296,145,1552,0,43,1551,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7297,145,1497,0,44,1552,1503,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7298,145,1503,0,45,1497,1399,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7299,145,1399,0,46,1503,1547,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7300,145,1547,0,47,1399,1549,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7301,145,1549,0,48,1547,1550,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7302,145,1550,0,49,1549,1553,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7303,145,1553,0,50,1550,1554,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7304,145,1554,0,51,1553,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7305,145,1497,0,52,1554,1457,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7306,145,1457,0,53,1497,1523,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7307,145,1523,0,54,1457,1555,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7308,145,1555,0,55,1523,1359,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7309,145,1359,0,56,1555,1556,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7310,145,1556,0,57,1359,1557,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7311,145,1557,0,58,1556,1472,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7312,145,1472,0,59,1557,1538,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7313,145,1538,0,60,1472,1558,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7314,145,1558,0,61,1538,1471,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7315,145,1471,0,62,1558,1435,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7316,145,1435,0,63,1471,1559,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7317,145,1559,0,64,1435,1560,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7318,145,1560,0,65,1559,1561,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7319,145,1561,0,66,1560,1505,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7320,145,1505,0,67,1561,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7321,145,1497,0,68,1505,1457,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7322,145,1457,0,69,1497,1531,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7323,145,1531,0,70,1457,1405,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7324,145,1405,0,71,1531,1350,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7325,145,1350,0,72,1405,1355,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7326,145,1355,0,73,1350,1562,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7327,145,1562,0,74,1355,1563,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7328,145,1563,0,75,1562,1564,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7329,145,1564,0,76,1563,1429,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7330,145,1429,0,77,1564,1565,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7331,145,1565,0,78,1429,1380,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7332,145,1380,0,79,1565,1355,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7333,145,1355,0,80,1380,1566,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7334,145,1566,0,81,1355,1567,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7335,145,1567,0,82,1566,1568,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7336,145,1568,0,83,1567,1405,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7337,145,1405,0,84,1568,1569,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7338,145,1569,0,85,1405,1429,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7339,145,1429,0,86,1569,1570,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7340,145,1570,0,87,1429,1571,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7341,145,1571,0,88,1570,1560,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7342,145,1560,0,89,1571,1511,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7343,145,1511,0,90,1560,1572,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7344,145,1572,0,91,1511,1429,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7345,145,1429,0,92,1572,1573,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7346,145,1573,0,93,1429,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7347,145,1497,0,94,1573,1457,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7348,145,1457,0,95,1497,1531,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7349,145,1531,0,96,1457,1405,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7350,145,1405,0,97,1531,1350,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7351,145,1350,0,98,1405,1488,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7352,145,1488,0,99,1350,1364,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7353,145,1364,0,100,1488,1489,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7354,145,1489,0,101,1364,1405,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7355,145,1405,0,102,1489,1534,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7356,145,1534,0,103,1405,1380,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7357,145,1380,0,104,1534,1535,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7358,145,1535,0,105,1380,1536,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7359,145,1536,0,106,1535,1359,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7360,145,1359,0,107,1536,1537,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7361,145,1537,0,108,1359,1413,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7362,145,1413,0,109,1537,1503,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7363,145,1503,0,110,1413,1350,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7364,145,1350,0,111,1503,1399,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7365,145,1399,0,112,1350,1459,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7366,145,1459,0,113,1399,1538,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7367,145,1538,0,114,1459,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7368,145,1497,0,115,1538,1380,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7369,145,1380,0,116,1497,1355,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7370,145,1355,0,117,1380,1539,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7371,145,1539,0,118,1355,1392,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7372,145,1392,0,119,1539,1540,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7373,145,1540,0,120,1392,1541,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7374,145,1541,0,121,1540,1542,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7375,145,1542,0,122,1541,1502,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7376,145,1502,0,123,1542,1413,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7377,145,1413,0,124,1502,1543,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7378,145,1543,0,125,1413,1350,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7379,145,1350,0,126,1543,1544,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7380,145,1544,0,127,1350,1545,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7381,145,1545,0,128,1544,1363,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7382,145,1363,0,129,1545,1521,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7383,145,1521,0,130,1363,1546,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7384,145,1546,0,131,1521,1547,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7385,145,1547,0,132,1546,1503,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7386,145,1503,0,133,1547,1548,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7387,145,1548,0,134,1503,1503,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7388,145,1503,0,135,1548,1549,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7389,145,1549,0,136,1503,1550,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7390,145,1550,0,137,1549,1380,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7391,145,1380,0,138,1550,1551,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7392,145,1551,0,139,1380,1552,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7393,145,1552,0,140,1551,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7394,145,1497,0,141,1552,1503,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7395,145,1503,0,142,1497,1399,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7396,145,1399,0,143,1503,1547,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7397,145,1547,0,144,1399,1549,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7398,145,1549,0,145,1547,1550,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7399,145,1550,0,146,1549,1553,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7400,145,1553,0,147,1550,1554,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7401,145,1554,0,148,1553,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7402,145,1497,0,149,1554,1457,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7403,145,1457,0,150,1497,1523,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7404,145,1523,0,151,1457,1555,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7405,145,1555,0,152,1523,1359,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7406,145,1359,0,153,1555,1556,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7407,145,1556,0,154,1359,1557,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7408,145,1557,0,155,1556,1472,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7409,145,1472,0,156,1557,1538,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7410,145,1538,0,157,1472,1558,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7411,145,1558,0,158,1538,1471,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7412,145,1471,0,159,1558,1435,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7413,145,1435,0,160,1471,1559,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7414,145,1559,0,161,1435,1560,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7415,145,1560,0,162,1559,1561,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7416,145,1561,0,163,1560,1505,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7417,145,1505,0,164,1561,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7418,145,1497,0,165,1505,1457,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7419,145,1457,0,166,1497,1531,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7420,145,1531,0,167,1457,1405,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7421,145,1405,0,168,1531,1350,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7422,145,1350,0,169,1405,1355,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7423,145,1355,0,170,1350,1562,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7424,145,1562,0,171,1355,1563,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7425,145,1563,0,172,1562,1564,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7426,145,1564,0,173,1563,1429,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7427,145,1429,0,174,1564,1565,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7428,145,1565,0,175,1429,1380,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7429,145,1380,0,176,1565,1355,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7430,145,1355,0,177,1380,1566,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7431,145,1566,0,178,1355,1567,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7432,145,1567,0,179,1566,1568,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7433,145,1568,0,180,1567,1405,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7434,145,1405,0,181,1568,1569,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7435,145,1569,0,182,1405,1429,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7436,145,1429,0,183,1569,1570,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7437,145,1570,0,184,1429,1571,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7438,145,1571,0,185,1570,1560,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7439,145,1560,0,186,1571,1511,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7440,145,1511,0,187,1560,1572,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7441,145,1572,0,188,1511,1429,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7442,145,1429,0,189,1572,1573,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7443,145,1573,0,190,1429,1497,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7444,145,1497,0,191,1573,1457,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7445,145,1457,0,192,1497,1531,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7446,145,1531,0,193,1457,1405,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7447,145,1405,0,194,1531,1350,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7448,145,1350,0,195,1405,0,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7449,135,1574,0,0,0,1575,1,1067936571,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7450,135,1575,0,1,1574,1574,1,1067936571,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7451,135,1574,0,2,1575,1575,1,1067936571,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7452,135,1575,0,3,1574,1349,1,1067936571,1,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7453,135,1349,0,4,1575,1350,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7454,135,1350,0,5,1349,1351,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7455,135,1351,0,6,1350,1352,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7456,135,1352,0,7,1351,1353,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7457,135,1353,0,8,1352,1354,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7458,135,1354,0,9,1353,1576,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7459,135,1576,0,10,1354,1577,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7460,135,1577,0,11,1576,1349,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7461,135,1349,0,12,1577,1350,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7462,135,1350,0,13,1349,1351,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7463,135,1351,0,14,1350,1352,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7464,135,1352,0,15,1351,1353,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7465,135,1353,0,16,1352,1354,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7466,135,1354,0,17,1353,1576,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7467,135,1576,0,18,1354,1577,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7468,135,1577,0,19,1576,0,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7469,136,1354,0,0,0,1354,10,1067937053,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7470,136,1354,0,1,1354,1353,10,1067937053,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7471,136,1353,0,2,1354,1354,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7472,136,1354,0,3,1353,1459,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7473,136,1459,0,4,1354,1577,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7474,136,1577,0,5,1459,1578,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7475,136,1578,0,6,1577,1577,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7476,136,1577,0,7,1578,1429,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7477,136,1429,0,8,1577,1579,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7478,136,1579,0,9,1429,1385,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7479,136,1385,0,10,1579,1580,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7480,136,1580,0,11,1385,1581,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7481,136,1581,0,12,1580,1399,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7482,136,1399,0,13,1581,1582,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7483,136,1582,0,14,1399,1583,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7484,136,1583,0,15,1582,1578,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7485,136,1578,0,16,1583,1577,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7486,136,1577,0,17,1578,1584,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7487,136,1584,0,18,1577,1585,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7488,136,1585,0,19,1584,1385,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7489,136,1385,0,20,1585,1586,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7490,136,1586,0,21,1385,1587,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7491,136,1587,0,22,1586,1385,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7492,136,1385,0,23,1587,1580,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7493,136,1580,0,24,1385,1581,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7494,136,1581,0,25,1580,1588,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7495,136,1588,0,26,1581,1589,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7496,136,1589,0,27,1588,1590,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7497,136,1590,0,28,1589,1591,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7498,136,1591,0,29,1590,1371,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7499,136,1371,0,30,1591,1436,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7500,136,1436,0,31,1371,1437,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7501,136,1437,0,32,1436,1592,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7502,136,1592,0,33,1437,1593,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7503,136,1593,0,34,1592,1594,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7504,136,1594,0,35,1593,1595,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7505,136,1595,0,36,1594,1596,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7506,136,1596,0,37,1595,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7507,136,1380,0,38,1596,1597,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7508,136,1597,0,39,1380,1598,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7509,136,1598,0,40,1597,1364,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7510,136,1364,0,41,1598,1599,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7511,136,1599,0,42,1364,1413,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7512,136,1413,0,43,1599,1469,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7513,136,1469,0,44,1413,1353,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7514,136,1353,0,45,1469,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7515,136,1380,0,46,1353,1600,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7516,136,1600,0,47,1380,1588,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7517,136,1588,0,48,1600,1601,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7518,136,1601,0,49,1588,1437,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7519,136,1437,0,50,1601,1497,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7520,136,1497,0,51,1437,1591,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7521,136,1591,0,52,1497,1602,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7522,136,1602,0,53,1591,1603,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7523,136,1603,0,54,1602,1364,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7524,136,1364,0,55,1603,1599,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7525,136,1599,0,56,1364,1399,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7526,136,1399,0,57,1599,1436,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7527,136,1436,0,58,1399,1437,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7528,136,1437,0,59,1436,1604,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7529,136,1604,0,60,1437,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7530,136,1380,0,61,1604,1436,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7531,136,1436,0,62,1380,1437,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7532,136,1437,0,63,1436,1605,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7533,136,1605,0,64,1437,1602,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7534,136,1602,0,65,1605,1606,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7535,136,1606,0,66,1602,1607,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7536,136,1607,0,67,1606,1596,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7537,136,1596,0,68,1607,1608,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7538,136,1608,0,69,1596,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7539,136,1380,0,70,1608,1609,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7540,136,1609,0,71,1380,1610,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7541,136,1610,0,72,1609,1497,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7542,136,1497,0,73,1610,1591,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7543,136,1591,0,74,1497,1469,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7544,136,1469,0,75,1591,1355,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7545,136,1355,0,76,1469,1353,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7546,136,1353,0,77,1355,1608,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7547,136,1608,0,78,1353,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7548,136,1380,0,79,1608,1609,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7549,136,1609,0,80,1380,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7550,136,1380,0,81,1609,1611,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7551,136,1611,0,82,1380,1612,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7552,136,1612,0,83,1611,1520,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7553,136,1520,0,84,1612,1359,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7554,136,1359,0,85,1520,1594,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7555,136,1594,0,86,1359,1613,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7556,136,1613,0,87,1594,1614,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7557,136,1614,0,88,1613,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7558,136,1380,0,89,1614,1612,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7559,136,1612,0,90,1380,1399,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7560,136,1399,0,91,1612,1364,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7561,136,1364,0,92,1399,1615,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7562,136,1615,0,93,1364,1612,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7563,136,1612,0,94,1615,1497,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7564,136,1497,0,95,1612,1351,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7565,136,1351,0,96,1497,1616,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7566,136,1616,0,97,1351,1617,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7567,136,1617,0,98,1616,1618,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7568,136,1618,0,99,1617,1619,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7569,136,1619,0,100,1618,1497,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7570,136,1497,0,101,1619,1591,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7571,136,1591,0,102,1497,1371,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7572,136,1371,0,103,1591,1619,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7573,136,1619,0,104,1371,1596,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7574,136,1596,0,105,1619,1620,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7575,136,1620,0,106,1596,1619,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7576,136,1619,0,107,1620,1551,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7577,136,1551,0,108,1619,1353,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7578,136,1353,0,109,1551,1354,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7579,136,1354,0,110,1353,1459,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7580,136,1459,0,111,1354,1577,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7581,136,1577,0,112,1459,1578,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7582,136,1578,0,113,1577,1577,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7583,136,1577,0,114,1578,1429,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7584,136,1429,0,115,1577,1579,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7585,136,1579,0,116,1429,1385,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7586,136,1385,0,117,1579,1580,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7587,136,1580,0,118,1385,1581,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7588,136,1581,0,119,1580,1399,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7589,136,1399,0,120,1581,1582,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7590,136,1582,0,121,1399,1583,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7591,136,1583,0,122,1582,1578,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7592,136,1578,0,123,1583,1577,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7593,136,1577,0,124,1578,1584,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7594,136,1584,0,125,1577,1585,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7595,136,1585,0,126,1584,1385,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7596,136,1385,0,127,1585,1586,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7597,136,1586,0,128,1385,1587,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7598,136,1587,0,129,1586,1385,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7599,136,1385,0,130,1587,1580,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7600,136,1580,0,131,1385,1581,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7601,136,1581,0,132,1580,1588,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7602,136,1588,0,133,1581,1589,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7603,136,1589,0,134,1588,1590,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7604,136,1590,0,135,1589,1591,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7605,136,1591,0,136,1590,1371,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7606,136,1371,0,137,1591,1436,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7607,136,1436,0,138,1371,1437,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7608,136,1437,0,139,1436,1592,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7609,136,1592,0,140,1437,1593,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7610,136,1593,0,141,1592,1594,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7611,136,1594,0,142,1593,1595,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7612,136,1595,0,143,1594,1596,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7613,136,1596,0,144,1595,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7614,136,1380,0,145,1596,1597,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7615,136,1597,0,146,1380,1598,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7616,136,1598,0,147,1597,1364,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7617,136,1364,0,148,1598,1599,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7618,136,1599,0,149,1364,1413,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7619,136,1413,0,150,1599,1469,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7620,136,1469,0,151,1413,1353,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7621,136,1353,0,152,1469,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7622,136,1380,0,153,1353,1600,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7623,136,1600,0,154,1380,1588,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7624,136,1588,0,155,1600,1601,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7625,136,1601,0,156,1588,1437,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7626,136,1437,0,157,1601,1497,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7627,136,1497,0,158,1437,1591,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7628,136,1591,0,159,1497,1602,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7629,136,1602,0,160,1591,1603,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7630,136,1603,0,161,1602,1364,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7631,136,1364,0,162,1603,1599,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7632,136,1599,0,163,1364,1399,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7633,136,1399,0,164,1599,1436,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7634,136,1436,0,165,1399,1437,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7635,136,1437,0,166,1436,1604,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7636,136,1604,0,167,1437,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7637,136,1380,0,168,1604,1436,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7638,136,1436,0,169,1380,1437,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7639,136,1437,0,170,1436,1605,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7640,136,1605,0,171,1437,1602,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7641,136,1602,0,172,1605,1606,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7642,136,1606,0,173,1602,1607,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7643,136,1607,0,174,1606,1596,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7644,136,1596,0,175,1607,1608,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7645,136,1608,0,176,1596,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7646,136,1380,0,177,1608,1609,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7647,136,1609,0,178,1380,1610,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7648,136,1610,0,179,1609,1497,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7649,136,1497,0,180,1610,1591,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7650,136,1591,0,181,1497,1469,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7651,136,1469,0,182,1591,1355,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7652,136,1355,0,183,1469,1353,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7653,136,1353,0,184,1355,1608,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7654,136,1608,0,185,1353,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7655,136,1380,0,186,1608,1609,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7656,136,1609,0,187,1380,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7657,136,1380,0,188,1609,1611,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7658,136,1611,0,189,1380,1612,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7659,136,1612,0,190,1611,1520,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7660,136,1520,0,191,1612,1359,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7661,136,1359,0,192,1520,1594,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7662,136,1594,0,193,1359,1613,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7663,136,1613,0,194,1594,1614,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7664,136,1614,0,195,1613,1380,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7665,136,1380,0,196,1614,1612,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7666,136,1612,0,197,1380,1399,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7667,136,1399,0,198,1612,1364,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7668,136,1364,0,199,1399,1615,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7669,136,1615,0,200,1364,1612,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7670,136,1612,0,201,1615,1497,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7671,136,1497,0,202,1612,1351,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7672,136,1351,0,203,1497,1616,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7673,136,1616,0,204,1351,1617,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7674,136,1617,0,205,1616,1618,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7675,136,1618,0,206,1617,1619,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7676,136,1619,0,207,1618,1497,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7677,136,1497,0,208,1619,1591,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7678,136,1591,0,209,1497,1371,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7679,136,1371,0,210,1591,1619,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7680,136,1619,0,211,1371,1596,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7681,136,1596,0,212,1619,1620,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7682,136,1620,0,213,1596,1619,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7683,136,1619,0,214,1620,1551,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7684,136,1551,0,215,1619,0,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7685,142,1621,0,0,0,1621,10,1069757199,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7686,142,1621,0,1,1621,1497,10,1069757199,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7687,142,1497,0,2,1621,1501,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7688,142,1501,0,3,1497,1622,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7689,142,1622,0,4,1501,1623,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7690,142,1623,0,5,1622,1364,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7691,142,1364,0,6,1623,1624,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7692,142,1624,0,7,1364,1441,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7693,142,1441,0,8,1624,1542,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7694,142,1542,0,9,1441,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7695,142,1350,0,10,1542,1351,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7696,142,1351,0,11,1350,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7697,142,1371,0,12,1351,1625,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7698,142,1625,0,13,1371,1361,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7699,142,1361,0,14,1625,1364,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7700,142,1364,0,15,1361,1626,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7701,142,1626,0,16,1364,1443,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7702,142,1443,0,17,1626,1489,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7703,142,1489,0,18,1443,1627,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7704,142,1627,0,19,1489,1355,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7705,142,1355,0,20,1627,1348,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7706,142,1348,0,21,1355,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7707,142,1350,0,22,1348,1351,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7708,142,1351,0,23,1350,1523,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7709,142,1523,0,24,1351,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7710,142,1371,0,25,1523,1625,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7711,142,1625,0,26,1371,1361,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7712,142,1361,0,27,1625,1628,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7713,142,1628,0,28,1361,1569,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7714,142,1569,0,29,1628,1629,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7715,142,1629,0,30,1569,1520,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7716,142,1520,0,31,1629,1359,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7717,142,1359,0,32,1520,1630,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7718,142,1630,0,33,1359,1631,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7719,142,1631,0,34,1630,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7720,142,1380,0,35,1631,1514,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7721,142,1514,0,36,1380,1632,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7722,142,1632,0,37,1514,1464,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7723,142,1464,0,38,1632,1633,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7724,142,1633,0,39,1464,1553,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7725,142,1553,0,40,1633,1634,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7726,142,1634,0,41,1553,1501,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7727,142,1501,0,42,1634,1635,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7728,142,1635,0,43,1501,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7729,142,1350,0,44,1635,1636,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7730,142,1636,0,45,1350,1561,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7731,142,1561,0,46,1636,1637,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7732,142,1637,0,47,1561,1638,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7733,142,1638,0,48,1637,1553,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7734,142,1553,0,49,1638,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7735,142,1380,0,50,1553,1639,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7736,142,1639,0,51,1380,1640,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7737,142,1640,0,52,1639,1641,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7738,142,1641,0,53,1640,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7739,142,1380,0,54,1641,1642,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7740,142,1642,0,55,1380,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7741,142,1350,0,56,1642,1391,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7742,142,1391,0,57,1350,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7743,142,1371,0,58,1391,1643,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7744,142,1643,0,59,1371,1399,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7745,142,1399,0,60,1643,1644,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7746,142,1644,0,61,1399,1645,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7747,142,1645,0,62,1644,1646,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7748,142,1646,0,63,1645,1647,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7749,142,1647,0,64,1646,1648,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7750,142,1648,0,65,1647,1646,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7751,142,1646,0,66,1648,1649,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7752,142,1649,0,67,1646,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7753,142,1380,0,68,1649,1650,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7754,142,1650,0,69,1380,1651,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7755,142,1651,0,70,1650,1652,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7756,142,1652,0,71,1651,1399,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7757,142,1399,0,72,1652,1364,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7758,142,1364,0,73,1399,1653,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7759,142,1653,0,74,1364,1654,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7760,142,1654,0,75,1653,1429,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7761,142,1429,0,76,1654,1359,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7762,142,1359,0,77,1429,1655,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7763,142,1655,0,78,1359,1652,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7764,142,1652,0,79,1655,1363,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7765,142,1363,0,80,1652,1437,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7766,142,1437,0,81,1363,1438,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7767,142,1438,0,82,1437,1569,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7768,142,1569,0,83,1438,1429,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7769,142,1429,0,84,1569,1359,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7770,142,1359,0,85,1429,1655,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7771,142,1655,0,86,1359,1656,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7772,142,1656,0,87,1655,1657,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7773,142,1657,0,88,1656,1586,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7774,142,1586,0,89,1657,1523,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7775,142,1523,0,90,1586,1658,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7776,142,1658,0,91,1523,1511,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7777,142,1511,0,92,1658,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7778,142,1350,0,93,1511,1392,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7779,142,1392,0,94,1350,1464,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7780,142,1464,0,95,1392,1633,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7781,142,1633,0,96,1464,1553,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7782,142,1553,0,97,1633,1609,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7783,142,1609,0,98,1553,1659,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7784,142,1659,0,99,1609,1351,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7785,142,1351,0,100,1659,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7786,142,1371,0,101,1351,1660,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7787,142,1660,0,102,1371,1661,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7788,142,1661,0,103,1660,1662,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7789,142,1662,0,104,1661,1361,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7790,142,1361,0,105,1662,1479,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7791,142,1479,0,106,1361,1580,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7792,142,1580,0,107,1479,1581,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7793,142,1581,0,108,1580,1663,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7794,142,1663,0,109,1581,1664,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7795,142,1664,0,110,1663,1471,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7796,142,1471,0,111,1664,1665,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7797,142,1665,0,112,1471,1464,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7798,142,1464,0,113,1665,1633,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7799,142,1633,0,114,1464,1666,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7800,142,1666,0,115,1633,1634,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7801,142,1634,0,116,1666,1501,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7802,142,1501,0,117,1634,1635,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7803,142,1635,0,118,1501,1607,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7804,142,1607,0,119,1635,1667,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7805,142,1667,0,120,1607,1429,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7806,142,1429,0,121,1667,1359,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7807,142,1359,0,122,1429,1655,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7808,142,1655,0,123,1359,1668,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7809,142,1668,0,124,1655,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7810,142,1380,0,125,1668,1659,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7811,142,1659,0,126,1380,1399,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7812,142,1399,0,127,1659,1669,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7813,142,1669,0,128,1399,1383,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7814,142,1383,0,129,1669,1501,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7815,142,1501,0,130,1383,1413,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7816,142,1413,0,131,1501,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7817,142,1371,0,132,1413,1670,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7818,142,1670,0,133,1371,1481,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7819,142,1481,0,134,1670,1447,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7820,142,1447,0,135,1481,1671,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7821,142,1671,0,136,1447,1413,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7822,142,1413,0,137,1671,1364,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7823,142,1364,0,138,1413,1672,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7824,142,1672,0,139,1364,1497,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7825,142,1497,0,140,1672,1501,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7826,142,1501,0,141,1497,1622,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7827,142,1622,0,142,1501,1623,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7828,142,1623,0,143,1622,1364,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7829,142,1364,0,144,1623,1624,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7830,142,1624,0,145,1364,1441,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7831,142,1441,0,146,1624,1542,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7832,142,1542,0,147,1441,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7833,142,1350,0,148,1542,1351,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7834,142,1351,0,149,1350,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7835,142,1371,0,150,1351,1625,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7836,142,1625,0,151,1371,1361,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7837,142,1361,0,152,1625,1364,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7838,142,1364,0,153,1361,1626,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7839,142,1626,0,154,1364,1443,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7840,142,1443,0,155,1626,1489,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7841,142,1489,0,156,1443,1627,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7842,142,1627,0,157,1489,1355,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7843,142,1355,0,158,1627,1348,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7844,142,1348,0,159,1355,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7845,142,1350,0,160,1348,1351,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7846,142,1351,0,161,1350,1523,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7847,142,1523,0,162,1351,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7848,142,1371,0,163,1523,1625,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7849,142,1625,0,164,1371,1361,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7850,142,1361,0,165,1625,1628,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7851,142,1628,0,166,1361,1569,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7852,142,1569,0,167,1628,1629,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7853,142,1629,0,168,1569,1520,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7854,142,1520,0,169,1629,1359,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7855,142,1359,0,170,1520,1630,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7856,142,1630,0,171,1359,1631,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7857,142,1631,0,172,1630,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7858,142,1380,0,173,1631,1514,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7859,142,1514,0,174,1380,1632,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7860,142,1632,0,175,1514,1464,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7861,142,1464,0,176,1632,1633,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7862,142,1633,0,177,1464,1553,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7863,142,1553,0,178,1633,1634,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7864,142,1634,0,179,1553,1501,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7865,142,1501,0,180,1634,1635,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7866,142,1635,0,181,1501,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7867,142,1350,0,182,1635,1636,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7868,142,1636,0,183,1350,1561,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7869,142,1561,0,184,1636,1637,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7870,142,1637,0,185,1561,1638,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7871,142,1638,0,186,1637,1553,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7872,142,1553,0,187,1638,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7873,142,1380,0,188,1553,1639,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7874,142,1639,0,189,1380,1640,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7875,142,1640,0,190,1639,1641,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7876,142,1641,0,191,1640,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7877,142,1380,0,192,1641,1642,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7878,142,1642,0,193,1380,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7879,142,1350,0,194,1642,1391,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7880,142,1391,0,195,1350,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7881,142,1371,0,196,1391,1643,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7882,142,1643,0,197,1371,1399,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7883,142,1399,0,198,1643,1644,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7884,142,1644,0,199,1399,1645,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7885,142,1645,0,200,1644,1646,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7886,142,1646,0,201,1645,1647,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7887,142,1647,0,202,1646,1648,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7888,142,1648,0,203,1647,1646,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7889,142,1646,0,204,1648,1649,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7890,142,1649,0,205,1646,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7891,142,1380,0,206,1649,1650,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7892,142,1650,0,207,1380,1651,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7893,142,1651,0,208,1650,1652,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7894,142,1652,0,209,1651,1399,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7895,142,1399,0,210,1652,1364,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7896,142,1364,0,211,1399,1653,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7897,142,1653,0,212,1364,1654,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7898,142,1654,0,213,1653,1429,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7899,142,1429,0,214,1654,1359,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7900,142,1359,0,215,1429,1655,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7901,142,1655,0,216,1359,1652,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7902,142,1652,0,217,1655,1363,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7903,142,1363,0,218,1652,1437,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7904,142,1437,0,219,1363,1438,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7905,142,1438,0,220,1437,1569,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7906,142,1569,0,221,1438,1429,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7907,142,1429,0,222,1569,1359,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7908,142,1359,0,223,1429,1655,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7909,142,1655,0,224,1359,1656,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7910,142,1656,0,225,1655,1657,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7911,142,1657,0,226,1656,1586,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7912,142,1586,0,227,1657,1523,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7913,142,1523,0,228,1586,1658,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7914,142,1658,0,229,1523,1511,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7915,142,1511,0,230,1658,1350,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7916,142,1350,0,231,1511,1392,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7917,142,1392,0,232,1350,1464,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7918,142,1464,0,233,1392,1633,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7919,142,1633,0,234,1464,1553,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7920,142,1553,0,235,1633,1609,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7921,142,1609,0,236,1553,1659,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7922,142,1659,0,237,1609,1351,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7923,142,1351,0,238,1659,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7924,142,1371,0,239,1351,1660,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7925,142,1660,0,240,1371,1661,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7926,142,1661,0,241,1660,1662,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7927,142,1662,0,242,1661,1361,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7928,142,1361,0,243,1662,1479,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7929,142,1479,0,244,1361,1580,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7930,142,1580,0,245,1479,1581,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7931,142,1581,0,246,1580,1663,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7932,142,1663,0,247,1581,1664,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7933,142,1664,0,248,1663,1471,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7934,142,1471,0,249,1664,1665,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7935,142,1665,0,250,1471,1464,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7936,142,1464,0,251,1665,1633,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7937,142,1633,0,252,1464,1666,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7938,142,1666,0,253,1633,1634,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7939,142,1634,0,254,1666,1501,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7940,142,1501,0,255,1634,1635,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7941,142,1635,0,256,1501,1607,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7942,142,1607,0,257,1635,1667,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7943,142,1667,0,258,1607,1429,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7944,142,1429,0,259,1667,1359,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7945,142,1359,0,260,1429,1655,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7946,142,1655,0,261,1359,1668,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7947,142,1668,0,262,1655,1380,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7948,142,1380,0,263,1668,1659,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7949,142,1659,0,264,1380,1399,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7950,142,1399,0,265,1659,1669,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7951,142,1669,0,266,1399,1383,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7952,142,1383,0,267,1669,1501,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7953,142,1501,0,268,1383,1413,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7954,142,1413,0,269,1501,1371,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7955,142,1371,0,270,1413,1670,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7956,142,1670,0,271,1371,1481,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7957,142,1481,0,272,1670,1447,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7958,142,1447,0,273,1481,1671,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7959,142,1671,0,274,1447,1413,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7960,142,1413,0,275,1671,1364,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7961,142,1364,0,276,1413,1672,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7962,142,1672,0,277,1364,0,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7963,143,1673,0,0,0,1575,10,1069757424,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7964,143,1575,0,1,1673,1673,10,1069757424,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7965,143,1673,0,2,1575,1575,10,1069757424,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7966,143,1575,0,3,1673,1497,10,1069757424,1,140,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7967,143,1497,0,4,1575,1501,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7968,143,1501,0,5,1497,1674,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7969,143,1674,0,6,1501,1413,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7970,143,1413,0,7,1674,1459,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7971,143,1459,0,8,1413,1675,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7972,143,1675,0,9,1459,1497,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7973,143,1497,0,10,1675,1351,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7974,143,1351,0,11,1497,1531,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7975,143,1531,0,12,1351,1676,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7976,143,1676,0,13,1531,1497,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7977,143,1497,0,14,1676,1457,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7978,143,1457,0,15,1497,1413,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7979,143,1413,0,16,1457,1677,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7980,143,1677,0,17,1413,1359,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7981,143,1359,0,18,1677,1678,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7982,143,1678,0,19,1359,1514,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7983,143,1514,0,20,1678,1679,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7984,143,1679,0,21,1514,1628,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7985,143,1628,0,22,1679,1359,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7986,143,1359,0,23,1628,1680,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7987,143,1680,0,24,1359,1628,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7988,143,1628,0,25,1680,1471,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7989,143,1471,0,26,1628,1681,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7990,143,1681,0,27,1471,1682,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7991,143,1682,0,28,1681,1497,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7992,143,1497,0,29,1682,1351,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7993,143,1351,0,30,1497,1683,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7994,143,1683,0,31,1351,1355,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7995,143,1355,0,32,1683,1684,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7996,143,1684,0,33,1355,1685,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7997,143,1685,0,34,1684,1663,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7998,143,1663,0,35,1685,1380,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (7999,143,1380,0,36,1663,1459,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8000,143,1459,0,37,1380,1686,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8001,143,1686,0,38,1459,1380,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8002,143,1380,0,39,1686,1687,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8003,143,1687,0,40,1380,1520,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8004,143,1520,0,41,1687,1359,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8005,143,1359,0,42,1520,1628,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8006,143,1628,0,43,1359,1497,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8007,143,1497,0,44,1628,1501,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8008,143,1501,0,45,1497,1674,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8009,143,1674,0,46,1501,1413,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8010,143,1413,0,47,1674,1459,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8011,143,1459,0,48,1413,1675,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8012,143,1675,0,49,1459,1497,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8013,143,1497,0,50,1675,1351,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8014,143,1351,0,51,1497,1531,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8015,143,1531,0,52,1351,1676,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8016,143,1676,0,53,1531,1497,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8017,143,1497,0,54,1676,1457,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8018,143,1457,0,55,1497,1413,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8019,143,1413,0,56,1457,1677,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8020,143,1677,0,57,1413,1359,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8021,143,1359,0,58,1677,1678,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8022,143,1678,0,59,1359,1514,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8023,143,1514,0,60,1678,1679,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8024,143,1679,0,61,1514,1628,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8025,143,1628,0,62,1679,1359,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8026,143,1359,0,63,1628,1680,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8027,143,1680,0,64,1359,1628,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8028,143,1628,0,65,1680,1471,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8029,143,1471,0,66,1628,1681,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8030,143,1681,0,67,1471,1682,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8031,143,1682,0,68,1681,1497,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8032,143,1497,0,69,1682,1351,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8033,143,1351,0,70,1497,1683,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8034,143,1683,0,71,1351,1355,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8035,143,1355,0,72,1683,1684,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8036,143,1684,0,73,1355,1685,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8037,143,1685,0,74,1684,1663,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8038,143,1663,0,75,1685,1380,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8039,143,1380,0,76,1663,1459,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8040,143,1459,0,77,1380,1686,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8041,143,1686,0,78,1459,1380,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8042,143,1380,0,79,1686,1687,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8043,143,1687,0,80,1380,1520,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8044,143,1520,0,81,1687,1359,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8045,143,1359,0,82,1520,1628,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8046,143,1628,0,83,1359,0,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8047,137,1571,0,0,0,1560,19,1068027382,1,181,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8048,137,1560,0,1,1571,1571,19,1068027382,1,181,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8049,137,1571,0,2,1560,1560,19,1068027382,1,181,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8050,137,1560,0,3,1571,1688,19,1068027382,1,181,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8051,137,1688,0,4,1560,1385,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8052,137,1385,0,5,1688,1364,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8053,137,1364,0,6,1385,1689,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8054,137,1689,0,7,1364,1690,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8055,137,1690,0,8,1689,1511,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8056,137,1511,0,9,1690,1350,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8057,137,1350,0,10,1511,1392,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8058,137,1392,0,11,1350,1691,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8059,137,1691,0,12,1392,1692,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8060,137,1692,0,13,1691,1693,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8061,137,1693,0,14,1692,1694,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8062,137,1694,0,15,1693,1413,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8063,137,1413,0,16,1694,1688,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8064,137,1688,0,17,1413,1385,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8065,137,1385,0,18,1688,1459,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8066,137,1459,0,19,1385,1447,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8067,137,1447,0,20,1459,1671,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8068,137,1671,0,21,1447,1695,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8069,137,1695,0,22,1671,1688,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8070,137,1688,0,23,1695,1385,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8071,137,1385,0,24,1688,1364,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8072,137,1364,0,25,1385,1689,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8073,137,1689,0,26,1364,1690,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8074,137,1690,0,27,1689,1511,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8075,137,1511,0,28,1690,1350,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8076,137,1350,0,29,1511,1392,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8077,137,1392,0,30,1350,1691,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8078,137,1691,0,31,1392,1692,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8079,137,1692,0,32,1691,1693,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8080,137,1693,0,33,1692,1694,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8081,137,1694,0,34,1693,1413,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8082,137,1413,0,35,1694,1688,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8083,137,1688,0,36,1413,1385,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8084,137,1385,0,37,1688,1459,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8085,137,1459,0,38,1385,1447,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8086,137,1447,0,39,1459,1671,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8087,137,1671,0,40,1447,1695,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8088,137,1695,0,41,1671,0,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8089,49,1450,0,0,0,1450,1,1066398020,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8090,49,1450,0,1,1450,0,1,1066398020,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8091,58,1696,0,0,0,1450,1,1066729196,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8092,58,1450,0,1,1696,1696,1,1066729196,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8093,58,1696,0,2,1450,1450,1,1066729196,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8094,58,1450,0,3,1696,0,1,1066729196,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9742,92,1441,0,783,1364,0,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9741,92,1364,0,782,1361,1441,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9740,92,1361,0,781,1975,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9739,92,1975,0,780,1471,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9738,92,1471,0,779,1974,1975,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9737,92,1974,0,778,1861,1471,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9736,92,1861,0,777,1471,1974,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9735,92,1471,0,776,1436,1861,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9734,92,1436,0,775,1973,1471,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9733,92,1973,0,774,1380,1436,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9732,92,1380,0,773,1972,1973,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9731,92,1972,0,772,1355,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9730,92,1355,0,771,1399,1972,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9729,92,1399,0,770,1612,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9728,92,1612,0,769,1971,1399,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9727,92,1971,0,768,1970,1612,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9726,92,1970,0,767,1457,1971,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9725,92,1457,0,766,1969,1970,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9724,92,1969,0,765,1466,1457,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9723,92,1466,0,764,1405,1969,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9722,92,1405,0,763,1968,1466,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9721,92,1968,0,762,1380,1405,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9720,92,1380,0,761,1437,1968,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9719,92,1437,0,760,1429,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9718,92,1429,0,759,1967,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9717,92,1967,0,758,1355,1429,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9716,92,1355,0,757,1481,1967,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9715,92,1481,0,756,1966,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9714,92,1966,0,755,1413,1481,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9713,92,1413,0,754,1931,1966,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9712,92,1931,0,753,1965,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9711,92,1965,0,752,1844,1931,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9710,92,1844,0,751,1364,1965,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9709,92,1364,0,750,1964,1844,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9708,92,1964,0,749,1963,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9707,92,1963,0,748,1364,1964,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9706,92,1364,0,747,1392,1963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9705,92,1392,0,746,1350,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9704,92,1350,0,745,1511,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9703,92,1511,0,744,1596,1350,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9702,92,1596,0,743,1361,1511,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9701,92,1361,0,742,1962,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9700,92,1962,0,741,1961,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9699,92,1961,0,740,1359,1962,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9698,92,1359,0,739,1413,1961,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9697,92,1413,0,738,1960,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9696,92,1960,0,737,1413,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9695,92,1413,0,736,1959,1960,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9694,92,1959,0,735,1380,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9693,92,1380,0,734,1465,1959,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9692,92,1465,0,733,1464,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9691,92,1464,0,732,1917,1465,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9690,92,1917,0,731,1497,1464,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9689,92,1497,0,730,1958,1917,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9688,92,1958,0,729,1355,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9687,92,1355,0,728,1907,1958,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9686,92,1907,0,727,1429,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9685,92,1429,0,726,1576,1907,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9684,92,1576,0,725,1551,1429,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9683,92,1551,0,724,1957,1576,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9682,92,1957,0,723,1471,1551,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9681,92,1471,0,722,1956,1957,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9680,92,1956,0,721,1955,1471,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9679,92,1955,0,720,1413,1956,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9678,92,1413,0,719,1954,1955,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9677,92,1954,0,718,1501,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9676,92,1501,0,717,1423,1954,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9675,92,1423,0,716,1494,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9674,92,1494,0,715,1679,1423,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9673,92,1679,0,714,1413,1494,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9672,92,1413,0,713,1930,1679,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9671,92,1930,0,712,1385,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9670,92,1385,0,711,1953,1930,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9669,92,1953,0,710,1905,1385,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9668,92,1905,0,709,1501,1953,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9667,92,1501,0,708,1888,1905,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9666,92,1888,0,707,1846,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9665,92,1846,0,706,1572,1888,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9664,92,1572,0,705,1952,1846,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9663,92,1952,0,704,1359,1572,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9662,92,1359,0,703,1951,1952,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9661,92,1951,0,702,1950,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9660,92,1950,0,701,1949,1951,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9659,92,1949,0,700,1948,1950,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9658,92,1948,0,699,1947,1949,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9657,92,1947,0,698,1364,1948,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9656,92,1364,0,697,1361,1947,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9655,92,1361,0,696,1946,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9654,92,1946,0,695,1363,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9653,92,1363,0,694,1560,1946,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9652,92,1560,0,693,1413,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9651,92,1413,0,692,1945,1560,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9650,92,1945,0,691,1423,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9649,92,1423,0,690,1537,1945,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9648,92,1537,0,689,1359,1423,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9647,92,1359,0,688,1363,1537,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9646,92,1363,0,687,1487,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9645,92,1487,0,686,1577,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9644,92,1577,0,685,1363,1487,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9643,92,1363,0,684,1521,1577,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9642,92,1521,0,683,1576,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9641,92,1576,0,682,1405,1521,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9640,92,1405,0,681,1944,1576,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9639,92,1944,0,680,1361,1405,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9638,92,1361,0,679,1872,1944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9637,92,1872,0,678,1359,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9636,92,1359,0,677,1943,1872,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9635,92,1943,0,676,1413,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9634,92,1413,0,675,1392,1943,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9633,92,1392,0,674,1942,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9632,92,1942,0,673,1941,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9631,92,1941,0,672,1940,1942,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9630,92,1940,0,671,1939,1941,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9629,92,1939,0,670,1487,1940,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9628,92,1487,0,669,1938,1939,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9627,92,1938,0,668,1520,1487,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9626,92,1520,0,667,1937,1938,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9625,92,1937,0,666,1936,1520,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9624,92,1936,0,665,1481,1937,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9623,92,1481,0,664,1935,1936,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9622,92,1935,0,663,1917,1481,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9621,92,1917,0,662,1497,1935,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9620,92,1497,0,661,1934,1917,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9619,92,1934,0,660,1389,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9618,92,1389,0,659,1900,1934,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9617,92,1900,0,658,1382,1389,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9616,92,1382,0,657,1506,1900,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9615,92,1506,0,656,1410,1382,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9614,92,1410,0,655,1933,1506,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9613,92,1933,0,654,1895,1410,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9612,92,1895,0,653,1596,1933,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9611,92,1596,0,652,1846,1895,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9610,92,1846,0,651,1932,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9609,92,1932,0,650,1520,1846,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9608,92,1520,0,649,1897,1932,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9607,92,1897,0,648,1931,1520,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9606,92,1931,0,647,1380,1897,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9605,92,1380,0,646,1437,1931,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9604,92,1437,0,645,1361,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9603,92,1361,0,644,1909,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9602,92,1909,0,643,1364,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9601,92,1364,0,642,1354,1909,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9600,92,1354,0,641,1930,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9599,92,1930,0,640,1929,1354,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9598,92,1929,0,639,1497,1930,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9597,92,1497,0,638,1928,1929,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9596,92,1928,0,637,1845,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9595,92,1845,0,636,1364,1928,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9594,92,1364,0,635,1361,1845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9593,92,1361,0,634,1927,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9592,92,1927,0,633,1364,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9591,92,1364,0,632,1926,1927,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9590,92,1926,0,631,1379,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9589,92,1379,0,630,1919,1926,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9588,92,1919,0,629,1351,1379,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9587,92,1351,0,628,1596,1919,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9586,92,1596,0,627,1615,1351,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9585,92,1615,0,626,1456,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9584,92,1456,0,625,1467,1615,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9583,92,1467,0,624,1780,1456,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9582,92,1780,0,623,1925,1467,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9581,92,1925,0,622,1497,1780,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9580,92,1497,0,621,1924,1925,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9579,92,1924,0,620,1886,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9578,92,1886,0,619,1923,1924,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9577,92,1923,0,618,1413,1886,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9576,92,1413,0,617,1380,1923,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9575,92,1380,0,616,1922,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9574,92,1922,0,615,1413,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9573,92,1413,0,614,1922,1922,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9572,92,1922,0,613,1603,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9571,92,1603,0,612,1413,1922,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9570,92,1413,0,611,1921,1603,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9569,92,1921,0,610,1380,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9568,92,1380,0,609,1920,1921,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9567,92,1920,0,608,1602,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9566,92,1602,0,607,1429,1920,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9565,92,1429,0,606,1382,1602,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9564,92,1382,0,605,1379,1429,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9563,92,1379,0,604,1919,1382,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9562,92,1919,0,603,1615,1379,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9561,92,1615,0,602,1364,1919,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9560,92,1364,0,601,1467,1615,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9559,92,1467,0,600,1918,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9558,92,1918,0,599,1917,1467,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9557,92,1917,0,598,1497,1918,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9556,92,1497,0,597,1615,1917,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9555,92,1615,0,596,1361,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9554,92,1361,0,595,1916,1615,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9553,92,1916,0,594,1618,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9552,92,1618,0,593,1617,1916,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9551,92,1617,0,592,1915,1618,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9550,92,1915,0,591,1413,1617,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9549,92,1413,0,590,1612,1915,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9548,92,1612,0,589,1914,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9547,92,1914,0,588,1501,1612,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9546,92,1501,0,587,1423,1914,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9545,92,1423,0,586,1913,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9544,92,1913,0,585,1912,1423,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9543,92,1912,0,584,1399,1913,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9542,92,1399,0,583,1615,1912,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9541,92,1615,0,582,1437,1399,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9540,92,1437,0,581,1380,1615,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9539,92,1380,0,580,1911,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9538,92,1911,0,579,1359,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9537,92,1359,0,578,1910,1911,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9536,92,1910,0,577,1361,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9535,92,1361,0,576,1909,1910,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9534,92,1909,0,575,1364,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9533,92,1364,0,574,1908,1909,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9532,92,1908,0,573,1413,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9531,92,1413,0,572,1907,1908,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9530,92,1907,0,571,1371,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9529,92,1371,0,570,1457,1907,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9528,92,1457,0,569,1878,1371,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9527,92,1878,0,568,1438,1457,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9526,92,1438,0,567,1437,1878,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9525,92,1437,0,566,1436,1438,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9524,92,1436,0,565,1906,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9523,92,1906,0,564,1527,1436,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9522,92,1527,0,563,1905,1906,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9521,92,1905,0,562,1904,1527,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9520,92,1904,0,561,1901,1905,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9519,92,1901,0,560,1389,1904,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9518,92,1389,0,559,1903,1901,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9517,92,1903,0,558,1419,1389,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9516,92,1419,0,557,1902,1903,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9515,92,1902,0,556,1901,1419,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9514,92,1901,0,555,1900,1902,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9513,92,1900,0,554,1899,1901,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9512,92,1899,0,553,1898,1900,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9511,92,1898,0,552,1380,1899,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9510,92,1380,0,551,1897,1898,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9509,92,1897,0,550,1438,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9508,92,1438,0,549,1437,1897,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9507,92,1437,0,548,1399,1438,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9506,92,1399,0,547,1896,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9505,92,1896,0,546,1895,1399,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9504,92,1895,0,545,1501,1896,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9503,92,1501,0,544,1596,1895,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9502,92,1596,0,543,1846,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9501,92,1846,0,542,1650,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9500,92,1650,0,541,1894,1846,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9499,92,1894,0,540,1361,1650,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9498,92,1361,0,539,1893,1894,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9497,92,1893,0,538,1892,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9496,92,1892,0,537,1364,1893,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9495,92,1364,0,536,1891,1892,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9494,92,1891,0,535,1890,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9493,92,1890,0,534,1380,1891,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9492,92,1380,0,533,1889,1890,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9491,92,1889,0,532,1888,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9490,92,1888,0,531,1380,1889,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9489,92,1380,0,530,1887,1888,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9488,92,1887,0,529,1886,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9487,92,1886,0,528,1885,1887,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9486,92,1885,0,527,1501,1886,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9485,92,1501,0,526,1884,1885,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9484,92,1884,0,525,1361,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9483,92,1361,0,524,1876,1884,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9482,92,1876,0,523,1883,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9481,92,1883,0,522,1380,1876,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9480,92,1380,0,521,1882,1883,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9479,92,1882,0,520,1363,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9478,92,1363,0,519,1881,1882,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9477,92,1881,0,518,1501,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9476,92,1501,0,517,1880,1881,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9475,92,1880,0,516,1364,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9474,92,1364,0,515,1444,1880,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9473,92,1444,0,514,1443,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9472,92,1443,0,513,1380,1444,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9471,92,1380,0,512,1441,1443,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9470,92,1441,0,511,1440,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9469,92,1440,0,510,1439,1441,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9468,92,1439,0,509,1438,1440,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9467,92,1438,0,508,1437,1439,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9466,92,1437,0,507,1436,1438,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9465,92,1436,0,506,1429,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9464,92,1429,0,505,1879,1436,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9463,92,1879,0,504,1878,1429,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9462,92,1878,0,503,1877,1879,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9461,92,1877,0,502,1355,1878,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9460,92,1355,0,501,1361,1877,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9459,92,1361,0,500,1609,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9458,92,1609,0,499,1846,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9457,92,1846,0,498,1392,1609,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9456,92,1392,0,497,1874,1846,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9455,92,1874,0,496,1423,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9454,92,1423,0,495,1596,1874,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9453,92,1596,0,494,1876,1423,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9452,92,1876,0,493,1501,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9451,92,1501,0,492,1572,1876,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9450,92,1572,0,491,1467,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9449,92,1467,0,490,1875,1572,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9448,92,1875,0,489,1382,1467,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9447,92,1382,0,488,1874,1875,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9446,92,1874,0,487,1560,1382,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9445,92,1560,0,486,1873,1874,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9444,92,1873,0,485,1392,1560,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9443,92,1392,0,484,1596,1873,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9442,92,1596,0,483,1361,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9441,92,1361,0,482,1872,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9440,92,1872,0,481,1359,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9439,92,1359,0,480,1380,1872,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9438,92,1380,0,479,1478,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9437,92,1478,0,478,1359,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9436,92,1359,0,477,1349,1478,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9435,92,1349,0,476,1426,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9434,92,1426,0,475,1871,1349,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9433,92,1871,0,474,1392,1426,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9432,92,1392,0,473,1497,1871,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9431,92,1497,0,472,1870,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9430,92,1870,0,471,1368,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9429,92,1368,0,470,1869,1870,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9428,92,1869,0,469,1868,1368,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9427,92,1868,0,468,1464,1869,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9426,92,1464,0,467,1501,1868,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9425,92,1501,0,466,1867,1464,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9424,92,1867,0,465,1490,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9423,92,1490,0,464,1355,1867,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9422,92,1355,0,463,1844,1490,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9421,92,1844,0,462,1364,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9420,92,1364,0,461,1363,1844,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9419,92,1363,0,460,1866,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9418,92,1866,0,459,1843,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9417,92,1843,0,458,1359,1866,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9416,92,1359,0,457,1865,1843,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9415,92,1865,0,456,1864,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9414,92,1864,0,455,1624,1865,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9413,92,1624,0,454,1364,1864,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9412,92,1364,0,453,1849,1624,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9411,92,1849,0,452,1364,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9410,92,1364,0,451,1363,1849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9409,92,1363,0,450,1843,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9408,92,1843,0,449,1848,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9407,92,1848,0,448,1863,1843,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9406,92,1863,0,447,1364,1848,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9405,92,1364,0,446,1413,1863,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9404,92,1413,0,445,1862,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9403,92,1862,0,444,1364,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9402,92,1364,0,443,1363,1862,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9401,92,1363,0,442,1861,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9400,92,1861,0,441,1471,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9399,92,1471,0,440,1501,1861,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9398,92,1501,0,439,1847,1471,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9397,92,1847,0,438,1489,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9396,92,1489,0,437,1860,1847,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9395,92,1860,0,436,1441,1489,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9394,92,1441,0,435,1364,1860,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9393,92,1364,0,434,1361,1441,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9392,92,1361,0,433,1975,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9391,92,1975,0,432,1471,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9390,92,1471,0,431,1974,1975,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9389,92,1974,0,430,1861,1471,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9388,92,1861,0,429,1471,1974,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9387,92,1471,0,428,1436,1861,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9386,92,1436,0,427,1973,1471,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9385,92,1973,0,426,1380,1436,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9384,92,1380,0,425,1972,1973,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9383,92,1972,0,424,1355,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9382,92,1355,0,423,1399,1972,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9381,92,1399,0,422,1612,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9380,92,1612,0,421,1971,1399,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9379,92,1971,0,420,1970,1612,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9378,92,1970,0,419,1457,1971,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9377,92,1457,0,418,1969,1970,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9376,92,1969,0,417,1466,1457,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9375,92,1466,0,416,1405,1969,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9374,92,1405,0,415,1968,1466,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9373,92,1968,0,414,1380,1405,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9372,92,1380,0,413,1437,1968,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9371,92,1437,0,412,1429,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9370,92,1429,0,411,1967,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9369,92,1967,0,410,1355,1429,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9368,92,1355,0,409,1481,1967,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9367,92,1481,0,408,1966,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9366,92,1966,0,407,1413,1481,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9365,92,1413,0,406,1931,1966,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9364,92,1931,0,405,1965,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9363,92,1965,0,404,1844,1931,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9362,92,1844,0,403,1364,1965,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9361,92,1364,0,402,1964,1844,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9360,92,1964,0,401,1963,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9359,92,1963,0,400,1364,1964,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9358,92,1364,0,399,1392,1963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9357,92,1392,0,398,1350,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9356,92,1350,0,397,1511,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9355,92,1511,0,396,1596,1350,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9354,92,1596,0,395,1361,1511,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9353,92,1361,0,394,1962,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9352,92,1962,0,393,1961,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9351,92,1961,0,392,1359,1962,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9350,92,1359,0,391,1413,1961,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9349,92,1413,0,390,1960,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9348,92,1960,0,389,1413,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9347,92,1413,0,388,1959,1960,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9346,92,1959,0,387,1380,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9345,92,1380,0,386,1465,1959,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9344,92,1465,0,385,1464,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9343,92,1464,0,384,1917,1465,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9342,92,1917,0,383,1497,1464,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9341,92,1497,0,382,1958,1917,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9340,92,1958,0,381,1355,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9339,92,1355,0,380,1907,1958,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9338,92,1907,0,379,1429,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9337,92,1429,0,378,1576,1907,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9336,92,1576,0,377,1551,1429,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9335,92,1551,0,376,1957,1576,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9334,92,1957,0,375,1471,1551,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9333,92,1471,0,374,1956,1957,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9332,92,1956,0,373,1955,1471,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9331,92,1955,0,372,1413,1956,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9330,92,1413,0,371,1954,1955,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9329,92,1954,0,370,1501,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9328,92,1501,0,369,1423,1954,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9327,92,1423,0,368,1494,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9326,92,1494,0,367,1679,1423,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9325,92,1679,0,366,1413,1494,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9324,92,1413,0,365,1930,1679,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9323,92,1930,0,364,1385,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9322,92,1385,0,363,1953,1930,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9321,92,1953,0,362,1905,1385,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9320,92,1905,0,361,1501,1953,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9319,92,1501,0,360,1888,1905,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9318,92,1888,0,359,1846,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9317,92,1846,0,358,1572,1888,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9316,92,1572,0,357,1952,1846,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9315,92,1952,0,356,1359,1572,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9314,92,1359,0,355,1951,1952,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9313,92,1951,0,354,1950,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9312,92,1950,0,353,1949,1951,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9311,92,1949,0,352,1948,1950,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9310,92,1948,0,351,1947,1949,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9309,92,1947,0,350,1364,1948,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9308,92,1364,0,349,1361,1947,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9307,92,1361,0,348,1946,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9306,92,1946,0,347,1363,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9305,92,1363,0,346,1560,1946,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9304,92,1560,0,345,1413,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9303,92,1413,0,344,1945,1560,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9302,92,1945,0,343,1423,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9301,92,1423,0,342,1537,1945,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9300,92,1537,0,341,1359,1423,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9299,92,1359,0,340,1363,1537,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9298,92,1363,0,339,1487,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9297,92,1487,0,338,1577,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9296,92,1577,0,337,1363,1487,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9295,92,1363,0,336,1521,1577,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9294,92,1521,0,335,1576,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9293,92,1576,0,334,1405,1521,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9292,92,1405,0,333,1944,1576,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9291,92,1944,0,332,1361,1405,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9290,92,1361,0,331,1872,1944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9289,92,1872,0,330,1359,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9288,92,1359,0,329,1943,1872,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9287,92,1943,0,328,1413,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9286,92,1413,0,327,1392,1943,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9285,92,1392,0,326,1942,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9284,92,1942,0,325,1941,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9283,92,1941,0,324,1940,1942,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9282,92,1940,0,323,1939,1941,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9281,92,1939,0,322,1487,1940,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9280,92,1487,0,321,1938,1939,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9279,92,1938,0,320,1520,1487,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9278,92,1520,0,319,1937,1938,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9277,92,1937,0,318,1936,1520,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9276,92,1936,0,317,1481,1937,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9275,92,1481,0,316,1935,1936,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9274,92,1935,0,315,1917,1481,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9273,92,1917,0,314,1497,1935,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9272,92,1497,0,313,1934,1917,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9271,92,1934,0,312,1389,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9270,92,1389,0,311,1900,1934,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9269,92,1900,0,310,1382,1389,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9268,92,1382,0,309,1506,1900,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9267,92,1506,0,308,1410,1382,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9266,92,1410,0,307,1933,1506,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9265,92,1933,0,306,1895,1410,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9264,92,1895,0,305,1596,1933,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9263,92,1596,0,304,1846,1895,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9262,92,1846,0,303,1932,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9261,92,1932,0,302,1520,1846,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9260,92,1520,0,301,1897,1932,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9259,92,1897,0,300,1931,1520,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9258,92,1931,0,299,1380,1897,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9257,92,1380,0,298,1437,1931,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9256,92,1437,0,297,1361,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9255,92,1361,0,296,1909,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9254,92,1909,0,295,1364,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9253,92,1364,0,294,1354,1909,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9252,92,1354,0,293,1930,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9251,92,1930,0,292,1929,1354,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9250,92,1929,0,291,1497,1930,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9249,92,1497,0,290,1928,1929,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9248,92,1928,0,289,1845,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9247,92,1845,0,288,1364,1928,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9246,92,1364,0,287,1361,1845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9245,92,1361,0,286,1927,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9244,92,1927,0,285,1364,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9243,92,1364,0,284,1926,1927,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9242,92,1926,0,283,1379,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9241,92,1379,0,282,1919,1926,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9240,92,1919,0,281,1351,1379,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9239,92,1351,0,280,1596,1919,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9238,92,1596,0,279,1615,1351,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9237,92,1615,0,278,1456,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9236,92,1456,0,277,1467,1615,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9235,92,1467,0,276,1780,1456,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9234,92,1780,0,275,1925,1467,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9233,92,1925,0,274,1497,1780,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9232,92,1497,0,273,1924,1925,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9231,92,1924,0,272,1886,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9230,92,1886,0,271,1923,1924,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9229,92,1923,0,270,1413,1886,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9228,92,1413,0,269,1380,1923,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9227,92,1380,0,268,1922,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9226,92,1922,0,267,1413,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9225,92,1413,0,266,1922,1922,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9224,92,1922,0,265,1603,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9223,92,1603,0,264,1413,1922,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9222,92,1413,0,263,1921,1603,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9221,92,1921,0,262,1380,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9220,92,1380,0,261,1920,1921,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9219,92,1920,0,260,1602,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9218,92,1602,0,259,1429,1920,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9217,92,1429,0,258,1382,1602,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9216,92,1382,0,257,1379,1429,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9215,92,1379,0,256,1919,1382,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9214,92,1919,0,255,1615,1379,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9213,92,1615,0,254,1364,1919,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9212,92,1364,0,253,1467,1615,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9211,92,1467,0,252,1918,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9210,92,1918,0,251,1917,1467,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9209,92,1917,0,250,1497,1918,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9208,92,1497,0,249,1615,1917,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9207,92,1615,0,248,1361,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9206,92,1361,0,247,1916,1615,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9205,92,1916,0,246,1618,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9204,92,1618,0,245,1617,1916,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9203,92,1617,0,244,1915,1618,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9202,92,1915,0,243,1413,1617,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9201,92,1413,0,242,1612,1915,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9200,92,1612,0,241,1914,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9199,92,1914,0,240,1501,1612,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9198,92,1501,0,239,1423,1914,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9197,92,1423,0,238,1913,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9196,92,1913,0,237,1912,1423,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9195,92,1912,0,236,1399,1913,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9194,92,1399,0,235,1615,1912,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9193,92,1615,0,234,1437,1399,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9192,92,1437,0,233,1380,1615,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9191,92,1380,0,232,1911,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9190,92,1911,0,231,1359,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9189,92,1359,0,230,1910,1911,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9188,92,1910,0,229,1361,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9187,92,1361,0,228,1909,1910,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9186,92,1909,0,227,1364,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9185,92,1364,0,226,1908,1909,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9184,92,1908,0,225,1413,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9183,92,1413,0,224,1907,1908,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9182,92,1907,0,223,1371,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9181,92,1371,0,222,1457,1907,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9180,92,1457,0,221,1878,1371,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9179,92,1878,0,220,1438,1457,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9178,92,1438,0,219,1437,1878,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9177,92,1437,0,218,1436,1438,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9176,92,1436,0,217,1906,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9175,92,1906,0,216,1527,1436,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9174,92,1527,0,215,1905,1906,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9173,92,1905,0,214,1904,1527,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9172,92,1904,0,213,1901,1905,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9171,92,1901,0,212,1389,1904,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9170,92,1389,0,211,1903,1901,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9169,92,1903,0,210,1419,1389,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9168,92,1419,0,209,1902,1903,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9167,92,1902,0,208,1901,1419,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9166,92,1901,0,207,1900,1902,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9165,92,1900,0,206,1899,1901,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9164,92,1899,0,205,1898,1900,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9163,92,1898,0,204,1380,1899,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9162,92,1380,0,203,1897,1898,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9161,92,1897,0,202,1438,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9160,92,1438,0,201,1437,1897,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9159,92,1437,0,200,1399,1438,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9158,92,1399,0,199,1896,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9157,92,1896,0,198,1895,1399,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9156,92,1895,0,197,1501,1896,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9155,92,1501,0,196,1596,1895,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9154,92,1596,0,195,1846,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9153,92,1846,0,194,1650,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9152,92,1650,0,193,1894,1846,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9151,92,1894,0,192,1361,1650,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9150,92,1361,0,191,1893,1894,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9149,92,1893,0,190,1892,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9148,92,1892,0,189,1364,1893,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9147,92,1364,0,188,1891,1892,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9146,92,1891,0,187,1890,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9145,92,1890,0,186,1380,1891,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9144,92,1380,0,185,1889,1890,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9143,92,1889,0,184,1888,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9142,92,1888,0,183,1380,1889,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9141,92,1380,0,182,1887,1888,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9140,92,1887,0,181,1886,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9139,92,1886,0,180,1885,1887,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9138,92,1885,0,179,1501,1886,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9137,92,1501,0,178,1884,1885,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9136,92,1884,0,177,1361,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9135,92,1361,0,176,1876,1884,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9134,92,1876,0,175,1883,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9133,92,1883,0,174,1380,1876,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9132,92,1380,0,173,1882,1883,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9131,92,1882,0,172,1363,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9130,92,1363,0,171,1881,1882,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9129,92,1881,0,170,1501,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9128,92,1501,0,169,1880,1881,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9127,92,1880,0,168,1364,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9126,92,1364,0,167,1444,1880,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9125,92,1444,0,166,1443,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9124,92,1443,0,165,1380,1444,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9123,92,1380,0,164,1441,1443,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9122,92,1441,0,163,1440,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9121,92,1440,0,162,1439,1441,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9120,92,1439,0,161,1438,1440,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9119,92,1438,0,160,1437,1439,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9118,92,1437,0,159,1436,1438,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9117,92,1436,0,158,1429,1437,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9116,92,1429,0,157,1879,1436,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9115,92,1879,0,156,1878,1429,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9114,92,1878,0,155,1877,1879,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9113,92,1877,0,154,1355,1878,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9112,92,1355,0,153,1361,1877,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9111,92,1361,0,152,1609,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9110,92,1609,0,151,1846,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9109,92,1846,0,150,1392,1609,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9108,92,1392,0,149,1874,1846,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9107,92,1874,0,148,1423,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9106,92,1423,0,147,1596,1874,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9105,92,1596,0,146,1876,1423,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9104,92,1876,0,145,1501,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9103,92,1501,0,144,1572,1876,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9102,92,1572,0,143,1467,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9101,92,1467,0,142,1875,1572,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9100,92,1875,0,141,1382,1467,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9099,92,1382,0,140,1874,1875,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9098,92,1874,0,139,1560,1382,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9097,92,1560,0,138,1873,1874,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9096,92,1873,0,137,1392,1560,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9095,92,1392,0,136,1596,1873,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9094,92,1596,0,135,1361,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9093,92,1361,0,134,1872,1596,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9092,92,1872,0,133,1359,1361,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9091,92,1359,0,132,1380,1872,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9090,92,1380,0,131,1478,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9089,92,1478,0,130,1359,1380,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9088,92,1359,0,129,1349,1478,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9087,92,1349,0,128,1426,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9086,92,1426,0,127,1871,1349,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9085,92,1871,0,126,1392,1426,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9084,92,1392,0,125,1497,1871,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9083,92,1497,0,124,1870,1392,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9082,92,1870,0,123,1368,1497,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9081,92,1368,0,122,1869,1870,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9080,92,1869,0,121,1868,1368,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9079,92,1868,0,120,1464,1869,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9078,92,1464,0,119,1501,1868,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9077,92,1501,0,118,1867,1464,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9076,92,1867,0,117,1490,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9075,92,1490,0,116,1355,1867,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9074,92,1355,0,115,1844,1490,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9073,92,1844,0,114,1364,1355,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9072,92,1364,0,113,1363,1844,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9071,92,1363,0,112,1866,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9070,92,1866,0,111,1843,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9069,92,1843,0,110,1359,1866,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9068,92,1359,0,109,1865,1843,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9067,92,1865,0,108,1864,1359,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9066,92,1864,0,107,1624,1865,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9065,92,1624,0,106,1364,1864,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9064,92,1364,0,105,1849,1624,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9063,92,1849,0,104,1364,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9062,92,1364,0,103,1363,1849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9061,92,1363,0,102,1843,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9060,92,1843,0,101,1848,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9059,92,1848,0,100,1863,1843,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9058,92,1863,0,99,1364,1848,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9057,92,1364,0,98,1413,1863,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9056,92,1413,0,97,1862,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9055,92,1862,0,96,1364,1413,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9054,92,1364,0,95,1363,1862,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9053,92,1363,0,94,1861,1364,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9052,92,1861,0,93,1471,1363,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9051,92,1471,0,92,1501,1861,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9050,92,1501,0,91,1847,1471,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9049,92,1847,0,90,1489,1501,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9048,92,1489,0,89,1860,1847,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9047,92,1860,0,88,1859,1489,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9046,92,1859,0,87,1858,1860,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9045,92,1858,0,86,1364,1859,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9044,92,1364,0,85,1405,1858,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9043,92,1405,0,84,1857,1364,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9042,92,1857,0,83,1429,1405,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9041,92,1429,0,82,1844,1857,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9040,92,1844,0,81,1854,1429,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9039,92,1854,0,80,1364,1844,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9038,92,1364,0,79,1856,1854,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9037,92,1856,0,78,1380,1364,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9036,92,1380,0,77,1855,1856,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9035,92,1855,0,76,1353,1380,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9034,92,1353,0,75,1405,1855,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9033,92,1405,0,74,1844,1353,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9032,92,1844,0,73,1854,1405,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9031,92,1854,0,72,1853,1844,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9030,92,1853,0,71,1436,1854,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9029,92,1436,0,70,1429,1853,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9028,92,1429,0,69,1366,1436,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9027,92,1366,0,68,1844,1429,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9026,92,1844,0,67,1356,1366,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9025,92,1356,0,66,1852,1844,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9024,92,1852,0,65,1844,1356,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9023,92,1844,0,64,1851,1852,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9022,92,1851,0,63,1850,1844,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9021,92,1850,0,62,1359,1851,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9020,92,1359,0,61,1849,1850,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9019,92,1849,0,60,1363,1359,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9018,92,1363,0,59,1843,1849,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9017,92,1843,0,58,1848,1363,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9016,92,1848,0,57,1501,1843,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9015,92,1501,0,56,1489,1848,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9014,92,1489,0,55,1364,1501,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9013,92,1364,0,54,1361,1489,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9012,92,1361,0,53,1847,1364,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9011,92,1847,0,52,1846,1361,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9010,92,1846,0,51,1845,1847,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9009,92,1845,0,50,1576,1846,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9008,92,1576,0,49,1859,1845,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9007,92,1859,0,48,1858,1576,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9006,92,1858,0,47,1364,1859,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9005,92,1364,0,46,1405,1858,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9004,92,1405,0,45,1857,1364,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9003,92,1857,0,44,1429,1405,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9002,92,1429,0,43,1844,1857,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9001,92,1844,0,42,1854,1429,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9000,92,1854,0,41,1364,1844,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8999,92,1364,0,40,1856,1854,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8998,92,1856,0,39,1380,1364,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8997,92,1380,0,38,1855,1856,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8996,92,1855,0,37,1353,1380,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8995,92,1353,0,36,1405,1855,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8994,92,1405,0,35,1844,1353,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8993,92,1844,0,34,1854,1405,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8992,92,1854,0,33,1853,1844,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8991,92,1853,0,32,1436,1854,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8990,92,1436,0,31,1429,1853,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8989,92,1429,0,30,1366,1436,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8988,92,1366,0,29,1844,1429,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8987,92,1844,0,28,1356,1366,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8986,92,1356,0,27,1852,1844,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8985,92,1852,0,26,1844,1356,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8984,92,1844,0,25,1851,1852,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8983,92,1851,0,24,1850,1844,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8982,92,1850,0,23,1359,1851,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8981,92,1359,0,22,1849,1850,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8980,92,1849,0,21,1363,1359,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8979,92,1363,0,20,1843,1849,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8978,92,1843,0,19,1848,1363,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8977,92,1848,0,18,1501,1843,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8976,92,1501,0,17,1489,1848,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8975,92,1489,0,16,1364,1501,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8974,92,1364,0,15,1361,1489,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8973,92,1361,0,14,1847,1364,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8972,92,1847,0,13,1846,1361,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8971,92,1846,0,12,1845,1847,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8970,92,1845,0,11,1576,1846,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8969,92,1576,0,10,1366,1845,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8968,92,1366,0,9,1844,1576,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8967,92,1844,0,8,1356,1366,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8966,92,1356,0,7,1363,1844,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8965,92,1363,0,6,1843,1356,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8964,92,1843,0,5,1366,1363,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8963,92,1366,0,4,1844,1843,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8962,92,1844,0,3,1356,1366,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8961,92,1356,0,2,1363,1844,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8960,92,1363,0,1,1843,1356,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8959,92,1843,0,0,0,1363,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8879,59,1831,0,0,0,1832,1,1066729211,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8880,59,1832,0,1,1831,1831,1,1066729211,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8881,59,1831,0,2,1832,1832,1,1066729211,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8882,59,1832,0,3,1831,0,1,1066729211,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8883,138,1377,0,0,0,1833,2,1069755162,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8884,138,1833,0,1,1377,1377,2,1069755162,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8885,138,1377,0,2,1833,1833,2,1069755162,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8886,138,1833,0,3,1377,1497,2,1069755162,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8887,138,1497,0,4,1833,1392,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8888,138,1392,0,5,1497,1622,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8889,138,1622,0,6,1392,1834,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8890,138,1834,0,7,1622,1355,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8891,138,1355,0,8,1834,1377,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8892,138,1377,0,9,1355,1833,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8893,138,1833,0,10,1377,1835,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8894,138,1835,0,11,1833,1780,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8895,138,1780,0,12,1835,1467,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8896,138,1467,0,13,1780,1382,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8897,138,1382,0,14,1467,1429,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8898,138,1429,0,15,1382,1836,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8899,138,1836,0,16,1429,1413,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8900,138,1413,0,17,1836,1352,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8901,138,1352,0,18,1413,1837,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8902,138,1837,0,19,1352,1354,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8903,138,1354,0,20,1837,1364,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8904,138,1364,0,21,1354,1577,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8905,138,1577,0,22,1364,1380,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8906,138,1380,0,23,1577,1505,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8907,138,1505,0,24,1380,1497,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8908,138,1497,0,25,1505,1498,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8909,138,1498,0,26,1497,1363,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8910,138,1363,0,27,1498,1622,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8911,138,1622,0,28,1363,1471,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8912,138,1471,0,29,1622,1497,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8913,138,1497,0,30,1471,1392,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8914,138,1392,0,31,1497,1622,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8915,138,1622,0,32,1392,1834,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8916,138,1834,0,33,1622,1355,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8917,138,1355,0,34,1834,1377,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8918,138,1377,0,35,1355,1833,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8919,138,1833,0,36,1377,1835,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8920,138,1835,0,37,1833,1780,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8921,138,1780,0,38,1835,1467,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8922,138,1467,0,39,1780,1382,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8923,138,1382,0,40,1467,1429,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8924,138,1429,0,41,1382,1836,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8925,138,1836,0,42,1429,1413,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8926,138,1413,0,43,1836,1352,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8927,138,1352,0,44,1413,1837,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8928,138,1837,0,45,1352,1354,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8929,138,1354,0,46,1837,1364,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8930,138,1364,0,47,1354,1577,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8931,138,1577,0,48,1364,1380,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8932,138,1380,0,49,1577,1505,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8933,138,1505,0,50,1380,1497,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8934,138,1497,0,51,1505,1498,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8935,138,1498,0,52,1497,1363,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8936,138,1363,0,53,1498,1622,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8937,138,1622,0,54,1363,1471,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8938,138,1471,0,55,1622,1364,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8939,138,1364,0,56,1471,1692,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8940,138,1692,0,57,1364,1497,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8941,138,1497,0,58,1692,1392,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8942,138,1392,0,59,1497,1838,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8943,138,1838,0,60,1392,1839,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8944,138,1839,0,61,1838,1840,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8945,138,1840,0,62,1839,1841,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8946,138,1841,0,63,1840,1576,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8947,138,1576,0,64,1841,1364,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8948,138,1364,0,65,1576,1692,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8949,138,1692,0,66,1364,1497,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8950,138,1497,0,67,1692,1392,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8951,138,1392,0,68,1497,1838,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8952,138,1838,0,69,1392,1839,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8953,138,1839,0,70,1838,1840,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8954,138,1840,0,71,1839,1841,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8955,138,1841,0,72,1840,1576,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8956,138,1576,0,73,1841,0,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8957,60,1842,0,0,0,1842,1,1066729226,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (8958,60,1842,0,1,1842,0,1,1066729226,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9743,61,1976,0,0,0,1450,1,1066729258,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9744,61,1450,0,1,1976,1976,1,1066729258,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9745,61,1976,0,2,1450,1450,1,1066729258,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9746,61,1450,0,3,1976,0,1,1066729258,4,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9747,94,1977,0,0,0,1978,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9748,94,1978,0,1,1977,1979,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9749,94,1979,0,2,1978,1560,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9750,94,1560,0,3,1979,1977,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9751,94,1977,0,4,1560,1978,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9752,94,1978,0,5,1977,1979,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9753,94,1979,0,6,1978,1560,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9754,94,1560,0,7,1979,1977,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9755,94,1977,0,8,1560,1978,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9756,94,1978,0,9,1977,1980,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9757,94,1980,0,10,1978,1981,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9758,94,1981,0,11,1980,1386,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9759,94,1386,0,12,1981,1355,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9760,94,1355,0,13,1386,1982,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9761,94,1982,0,14,1355,1432,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9762,94,1432,0,15,1982,1351,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9763,94,1351,0,16,1432,1371,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9764,94,1371,0,17,1351,1385,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9765,94,1385,0,18,1371,1983,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9766,94,1983,0,19,1385,1361,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9767,94,1361,0,20,1983,1364,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9768,94,1364,0,21,1361,1984,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9769,94,1984,0,22,1364,1985,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9770,94,1985,0,23,1984,1497,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9771,94,1497,0,24,1985,1986,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9772,94,1986,0,25,1497,1987,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9773,94,1987,0,26,1986,1363,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9774,94,1363,0,27,1987,1988,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9775,94,1988,0,28,1363,1989,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9776,94,1989,0,29,1988,1990,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9777,94,1990,0,30,1989,1386,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9778,94,1386,0,31,1990,1991,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9779,94,1991,0,32,1386,1662,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9780,94,1662,0,33,1991,1977,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9781,94,1977,0,34,1662,1978,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9782,94,1978,0,35,1977,1980,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9783,94,1980,0,36,1978,1981,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9784,94,1981,0,37,1980,1386,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9785,94,1386,0,38,1981,1355,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9786,94,1355,0,39,1386,1982,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9787,94,1982,0,40,1355,1432,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9788,94,1432,0,41,1982,1351,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9789,94,1351,0,42,1432,1371,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9790,94,1371,0,43,1351,1385,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9791,94,1385,0,44,1371,1983,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9792,94,1983,0,45,1385,1361,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9793,94,1361,0,46,1983,1364,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9794,94,1364,0,47,1361,1984,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9795,94,1984,0,48,1364,1985,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9796,94,1985,0,49,1984,1497,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9797,94,1497,0,50,1985,1986,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9798,94,1986,0,51,1497,1987,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9799,94,1987,0,52,1986,1363,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9800,94,1363,0,53,1987,1988,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9801,94,1988,0,54,1363,1989,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9802,94,1989,0,55,1988,1990,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9803,94,1990,0,56,1989,1386,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9804,94,1386,0,57,1990,1991,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9805,94,1991,0,58,1386,1662,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9806,94,1662,0,59,1991,1988,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9807,94,1988,0,60,1662,1992,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9808,94,1992,0,61,1988,1429,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9809,94,1429,0,62,1992,1977,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9810,94,1977,0,63,1429,1978,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9811,94,1978,0,64,1977,1380,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9812,94,1380,0,65,1978,1835,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9813,94,1835,0,66,1380,1780,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9814,94,1780,0,67,1835,1350,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9815,94,1350,0,68,1780,1368,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9816,94,1368,0,69,1350,1993,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9817,94,1993,0,70,1368,1987,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9818,94,1987,0,71,1993,1415,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9819,94,1415,0,72,1987,1355,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9820,94,1355,0,73,1415,1994,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9821,94,1994,0,74,1355,1988,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9822,94,1988,0,75,1994,1992,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9823,94,1992,0,76,1988,1429,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9824,94,1429,0,77,1992,1977,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9825,94,1977,0,78,1429,1978,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9826,94,1978,0,79,1977,1380,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9827,94,1380,0,80,1978,1835,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9828,94,1835,0,81,1380,1780,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9829,94,1780,0,82,1835,1350,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9830,94,1350,0,83,1780,1368,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9831,94,1368,0,84,1350,1993,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9832,94,1993,0,85,1368,1987,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9833,94,1987,0,86,1993,1415,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9834,94,1415,0,87,1987,1355,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9835,94,1355,0,88,1415,1994,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9836,94,1994,0,89,1355,0,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9837,11,1995,0,0,0,1996,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9838,11,1996,0,1,1995,1995,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9839,11,1995,0,2,1996,1996,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9840,11,1996,0,3,1995,0,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9841,146,1997,0,0,0,1998,3,1072180743,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9842,146,1998,0,1,1997,1997,3,1072180743,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9843,146,1997,0,2,1998,1998,3,1072180743,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9844,146,1998,0,3,1997,1999,3,1072180743,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9845,146,1999,0,4,1998,2000,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9846,146,2000,0,5,1999,1405,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9847,146,1405,0,6,2000,1364,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9848,146,1364,0,7,1405,1997,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9849,146,1997,0,8,1364,1999,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9850,146,1999,0,9,1997,1999,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9851,146,1999,0,10,1999,2000,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9852,146,2000,0,11,1999,1405,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9853,146,1405,0,12,2000,1364,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9854,146,1364,0,13,1405,1997,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9855,146,1997,0,14,1364,1999,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9856,146,1999,0,15,1997,0,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9857,10,1997,0,0,0,1997,4,1033920665,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9858,10,1997,0,1,1997,1999,4,1033920665,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9859,10,1999,0,2,1997,1999,4,1033920665,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9860,10,1999,0,3,1999,1997,4,1033920665,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9861,10,1997,0,4,1999,2001,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9862,10,2001,0,5,1997,1997,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9863,10,1997,0,6,2001,2001,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9864,10,2001,0,7,1997,0,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9865,12,2002,0,0,0,1998,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9866,12,1998,0,1,2002,2002,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9867,12,2002,0,2,1998,1998,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9868,12,1998,0,3,2002,0,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9869,14,2002,0,0,0,2002,4,1033920830,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9870,14,2002,0,1,2002,1999,4,1033920830,2,8,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9871,14,1999,0,2,2002,1999,4,1033920830,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9872,14,1999,0,3,1999,2003,4,1033920830,2,9,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9873,14,2003,0,4,1999,2001,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9874,14,2001,0,5,2003,2003,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9875,14,2003,0,6,2001,2001,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9876,14,2001,0,7,2003,0,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9877,13,2004,0,0,0,2004,3,1033920794,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9878,13,2004,0,1,2004,0,3,1033920794,2,6,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9879,44,2005,0,0,0,2006,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9880,44,2006,0,1,2005,2005,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9881,44,2005,0,2,2006,2006,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9882,44,2006,0,3,2005,0,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9883,43,2007,0,0,0,2007,14,1066384365,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9884,43,2007,0,1,2007,2008,14,1066384365,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9885,43,2008,0,2,2007,2009,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9886,43,2009,0,3,2008,2008,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9887,43,2008,0,4,2009,2009,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9888,43,2009,0,5,2008,0,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9889,45,2010,0,0,0,1380,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9890,45,1380,0,1,2010,1965,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9891,45,1965,0,2,1380,2010,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9892,45,2010,0,3,1965,1380,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9893,45,1380,0,4,2010,1965,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9894,45,1965,0,5,1380,1439,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9895,45,1439,0,6,1965,1475,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9896,45,1475,0,7,1439,2011,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9897,45,2011,0,8,1475,1439,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9898,45,1439,0,9,2011,1475,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9899,45,1475,0,10,1439,2011,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9900,45,2011,0,11,1475,0,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9901,115,2012,0,0,0,2012,14,1066991725,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9902,115,2012,0,1,2012,2005,14,1066991725,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9903,115,2005,0,2,2012,2012,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9904,115,2012,0,3,2005,2005,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9905,115,2005,0,4,2012,2012,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9906,115,2012,0,5,2005,0,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9907,116,2013,0,0,0,2014,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9908,116,2014,0,1,2013,2013,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9909,116,2013,0,2,2014,2014,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9910,116,2014,0,3,2013,1439,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9911,116,1439,0,4,2014,2015,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9912,116,2015,0,5,1439,1439,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9913,116,1439,0,6,2015,2015,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9914,116,2015,0,7,1439,0,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9915,46,2010,0,0,0,1380,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9916,46,1380,0,1,2010,1965,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9917,46,1965,0,2,1380,2010,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9918,46,2010,0,3,1965,1380,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9919,46,1380,0,4,2010,1965,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9920,46,1965,0,5,1380,0,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9921,56,2016,0,0,0,2016,15,1066643397,11,161,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9922,56,2016,0,1,2016,2017,15,1066643397,11,161,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9923,56,2017,0,2,2016,2018,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9924,56,2018,0,3,2017,1626,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9925,56,1626,0,4,2018,2019,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9926,56,2019,0,5,1626,1520,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9927,56,1520,0,6,2019,2020,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9928,56,2020,0,7,1520,2021,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9929,56,2021,0,8,2020,2017,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9930,56,2017,0,9,2021,2018,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9931,56,2018,0,10,2017,1626,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9932,56,1626,0,11,2018,2019,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9933,56,2019,0,12,1626,1520,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9934,56,1520,0,13,2019,2020,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9935,56,2020,0,14,1520,2021,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) VALUES (9936,56,2021,0,15,2020,0,15,1066643397,11,188,'',0);





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





INSERT INTO ezsearch_word (id, word, object_count) VALUES (1348,'products',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1349,'here',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1350,'you',9);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1351,'will',9);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1352,'find',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1353,'information',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1354,'about',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1355,'our',10);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1356,'top',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1357,'100',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1358,'set',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1359,'a',8);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1360,'collection',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1361,'of',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1362,'music',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1363,'from',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1364,'the',11);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1365,'year',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1366,'2003',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1367,'best',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1368,'all',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1369,'charts',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1370,'mona',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1371,'be',7);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1372,'smarting',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1373,'lacklustre',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1374,'chart',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1375,'position',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1376,'her',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1377,'new',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1378,'album',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1379,'up',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1380,'and',12);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1381,'go',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1382,'it',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1383,'s',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1384,'come',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1385,'in',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1386,'at',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1387,'no',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1388,'234',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1389,'when',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1390,'surely',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1391,'should',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1392,'have',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1393,'snagged',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1394,'spot',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1395,'fellow',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1396,'babe',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1397,'july',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1398,'too',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1399,'with',7);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1400,'cd',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1401,'manages',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1402,'poor',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1403,'343',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1404,'but',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1405,'for',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1406,'tim',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1407,'whose',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1408,'inon',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1409,'doesn',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1410,'t',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1411,'even',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1412,'manage',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1413,'to',10);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1414,'scrape',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1415,'into',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1416,'20',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1417,'once',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1418,'mighty',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1419,'seem',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1420,'fragile',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1421,'meanwhile',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1422,'someone',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1423,'who',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1424,'reputation',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1425,'has',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1426,'been',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1427,'seeming',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1428,'really',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1429,'is',8);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1430,'joap',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1431,'jackson',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1432,'he',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1433,'romps',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1434,'1',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1435,'publishabc',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1436,'an',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1437,'open',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1438,'source',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1439,'content',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1440,'management',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1441,'system',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1442,'cms',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1443,'development',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1444,'framework',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1445,'advanced',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1446,'functionality',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1447,'e',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1448,'commerce',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1449,'sites',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1450,'news',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1451,'forums',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1452,'picture',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1453,'galleries',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1454,'intranets',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1455,'much',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1456,'more',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1457,'can',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1458,'build',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1459,'your',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1460,'dynamic',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1461,'websites',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1462,'fast',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1463,'reliable',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1464,'very',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1465,'flexible',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1466,'everyone',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1467,'that',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1468,'wants',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1469,'share',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1470,'their',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1471,'on',7);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1472,'web',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1473,'easily',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1474,'create',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1475,'edit',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1476,'publish',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1477,'sorts',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1478,'day',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1479,'work',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1480,'done',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1481,'by',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1482,'non',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1483,'technical',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1484,'persons',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1485,'services',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1486,'support',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1487,'\"',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1488,'use',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1489,'crew',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1490,'first',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1491,'hand',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1492,'information\"',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1493,'guarantee',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1494,'customers',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1495,'possible',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1496,'result',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1497,'we',8);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1498,'offer',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1499,'program',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1500,'professionals',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1501,'are',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1502,'ready',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1503,'help',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1504,'problem',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1505,'what',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1506,'get',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1507,'cover',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1508,'answers',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1509,'email',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1510,'phone',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1511,'if',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1512,'need',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1513,'configure',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1514,'or',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1515,'develop',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1516,'features',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1517,'doing',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1518,'directly',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1519,'server',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1520,'as',6);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1521,'feature',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1522,'distribution',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1523,'also',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1524,'able',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1525,'give',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1526,'advise',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1527,'how',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1528,'solve',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1529,'problems',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1530,'want',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1531,'do',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1532,'most',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1533,'yourself',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1534,'developments',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1535,'enhancements',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1536,'hire',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1537,'guy',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1538,'solution',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1539,'friends',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1540,'highly',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1541,'skilled',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1542,'developers',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1543,'advice',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1544,'consulting',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1545,'ranges',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1546,'requests',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1547,'installation',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1548,'upgrade',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1549,'migration',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1550,'integration',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1551,'solutions',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1552,'often',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1553,'programming',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1554,'etc',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1555,'deliver',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1556,'turn',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1557,'key',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1558,'based',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1559,'let',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1560,'us',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1561,'know',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1562,'standard',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1563,'hourly',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1564,'rate',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1565,'129',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1566,'minimum',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1567,'asking',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1568,'price',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1569,'projects',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1570,'2344',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1571,'contact',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1572,'there',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1573,'something',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1574,'general',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1575,'info',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1576,'this',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1577,'company',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1578,'my',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1579,'located',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1580,'skien',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1581,'norway',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1582,'223',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1583,'employees',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1584,'was',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1585,'founded',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1586,'may',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1587,'1973',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1588,'corporate',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1589,'vision',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1590,'\"we',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1591,'shall',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1592,'minded',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1593,'dedicated',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1594,'team',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1595,'helping',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1596,'people',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1597,'businesses',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1598,'around',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1599,'world',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1600,'knowledge\"',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1601,'values',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1602,'always',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1603,'meet',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1604,'mind',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1605,'heart',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1606,'welcoming',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1607,'other',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1608,'ideas',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1609,'knowledge',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1610,'sharing',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1611,'pull',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1612,'together',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1613,'both',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1614,'internally',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1615,'community',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1616,'accomplish',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1617,'great',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1618,'things',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1619,'innovative',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1620,'creating',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1621,'career',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1622,'now',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1623,'hiring',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1624,'following',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1625,'part',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1626,'ez',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1627,'developing',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1628,'customer',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1629,'either',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1630,'project',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1631,'leader',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1632,'developer',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1633,'good',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1634,'skills',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1635,'required',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1636,'must',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1637,'object',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1638,'oriented',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1639,'design',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1640,'using',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1641,'c',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1642,'php',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1643,'familiar',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1644,'uml',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1645,'sql',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1646,'xml',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1647,'xhtml',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1648,'soap',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1649,'rpc',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1650,'linux',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1651,'unix',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1652,'experience',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1653,'qt',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1654,'toolkit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1655,'plus',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1656,'fresh',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1657,'graduates',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1658,'apply',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1659,'applications',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1660,'accepted',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1661,'continually',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1662,'place',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1663,'conditions',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1664,'depending',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1665,'qualifications',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1666,'english',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1667,'languages',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1668,'questions',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1669,'cv',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1670,'sent',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1671,'mail',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1672,'boss',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1673,'shop',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1674,'committed',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1675,'satisfaction',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1676,'everything',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1677,'make',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1678,'present',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1679,'potential',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1680,'satisfied',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1681,'these',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1682,'pages',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1683,'outline',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1684,'terms',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1685,'&',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1686,'rights',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1687,'privacy',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1688,'fill',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1689,'form',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1690,'below',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1691,'any',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1692,'feedback',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1693,'please',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1694,'remember',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1695,'address',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1696,'business',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1943,'pay',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1942,'would',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1941,'\"i',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1940,'smile',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1939,'big',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1938,'\"amazing',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1937,'such',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1936,'replies',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1935,'met',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1934,'explained',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1933,'don',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1932,'mentioned',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1931,'free',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1930,'talking',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1929,'were',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1928,'anyway',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1927,'rest',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1926,'during',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1925,'certainly',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1924,'topics',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1923,'discuss',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1922,'face',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1921,'inspiring',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1920,'interesting',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1919,'show',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1918,'happy',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1917,'re',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1916,'speaking',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1915,'achieve',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1914,'working',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1913,'minds',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1912,'creative',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1911,'huge',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1910,'having',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1909,'benefits',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1908,'mention',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1907,'not',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1906,'powerful',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1905,'just',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1904,'realize',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1903,'impressed',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1902,'sure',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1901,'they',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1900,'however',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1899,'licenses',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1898,'public',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1897,'software',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1896,'unfamiliar',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1895,'still',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1894,'gnu',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1893,'success',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1892,'enormous',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1891,'despite',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1890,'small',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1889,'large',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1888,'companies',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1887,'organizations',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1886,'various',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1885,'representing',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1884,'them',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1883,'austria',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1882,'germany',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1881,'mostly',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1880,'visitors',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1780,'hope',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1879,'which',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1878,'product',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1877,'main',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1876,'many',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1875,'seems',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1874,'already',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1873,'visited',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1872,'lot',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1871,'barely',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1870,'expectations',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1869,'exceeding',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1868,'positive',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1867,'impressions',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1866,'report',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1865,'contains',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1864,'text',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1863,'24th',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1862,'20th',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1861,'site',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1860,'four',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1859,'time',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1858,'5th',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1857,'held',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1856,'telecommunications',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1855,'technology',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1854,'trade',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1853,'international',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1852,'2003\"',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1851,'\"top',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1850,'attending',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1849,'hall',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1848,'reporting',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1847,'members',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1846,'some',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1845,'week',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1844,'fair',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1843,'live',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1831,'off',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1832,'topic',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1833,'website',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1834,'released',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1835,'i',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1836,'easier',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1837,'iformation',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1838,'gotten',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1839,'so',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1840,'far',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1841,'indicates',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1842,'reports',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1944,'money',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1945,'came',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1946,'one',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1947,'neighboring',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1948,'stands',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1949,'right',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1950,'after',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1951,'watching',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1952,'presentation',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1953,'interested',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1954,'willing',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1955,'spend',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1956,'millions',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1957,'rigid',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1958,'policy',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1959,'eager',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1960,'talk',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1961,'wide',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1962,'range',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1963,'chance',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1964,'visit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1965,'feel',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1966,'stop',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1967,'stand',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1968,'prepared',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1969,'anybody',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1970,'sit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1971,'down',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1972,'representatives',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1973,'receive',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1974,'hands',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1975,'demonstration',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1976,'staff',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1977,'mr',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1978,'smith',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1979,'joined',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1980,'started',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1981,'today',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1982,'firm',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1983,'charge',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1984,'computer',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1985,'matrix',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1986,'hired',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1987,'him',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1988,'his',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1989,'previous',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1990,'workplace',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1991,'nemos',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1992,'name',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1993,'welcome',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1994,'ranks',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1995,'guest',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1996,'accounts',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1997,'anonymous',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1998,'users',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (1999,'user',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2000,'group',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2001,'nospam@ez.no',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2002,'administrator',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2003,'admin',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2004,'editors',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2005,'setup',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2006,'links',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2007,'classes',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2008,'class',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2009,'grouplist',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2010,'look',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2011,'56',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2012,'cache',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2013,'url',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2014,'translator',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2015,'urltranslator',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2016,'corporate_package',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2017,'copyright',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2018,'&copy',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2019,'systems',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2020,'1999',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2021,'2004',1);





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
INSERT INTO ezsite_data (name, value) VALUES ('ezpublish-release','2');





CREATE TABLE ezsubtree_notification_rule (
  id int(11) NOT NULL auto_increment,
  use_digest int(11) default '0',
  node_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsubtree_notification_rule_id (id),
  KEY ezsubtree_notification_rule_user_id (user_id)
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
  is_wildcard int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ezurlalias_source_md5 (source_md5),
  KEY ezurlalias_source_url (source_url(255)),
  KEY ezurlalias_desturl (destination_url(200))
) TYPE=MyISAM;





INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (37,'news/off_topic','c77d3081eac3bee15b0213bcc89b369b','content/view/full/57',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (36,'news/business_news','bde42888705c25806fbe02b8570d055d','content/view/full/56',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,99,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (38,'news/reports_','ac624940baa3e037e0467bf2db2743cb','content/view/full/58',1,39,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (39,'news/reports','f3cbeafbd5dbf7477a9a803d47d4dcbb','content/view/full/58',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (40,'news/staff_news','c50e4a6eb10a499c098857026282ceb4','content/view/full/59',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (97,'information','bb3ccd5881d651448ded1dac904054ac','content/view/full/108',1,112,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (111,'general_info/career','ea1c177fd7c868dc277cf107f26f668c','content/view/full/116',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,99,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (62,'news/business_news/ez_systems_reporting_live_from_munich','ddb9dceff37417877c5a030d5ca3e5b5','content/view/full/81',1,103,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (105,'products/top_100_set','ef50df42a7d2fe7cff26830b3de58283','content/view/full/113',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (64,'news/staff_news/mr_xxx_joined_us','6755615af39b3f3a145fd2a57a37809d','content/view/full/83',1,102,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (95,'products','86024cad1e83101d97359d7351051156','content/view/full/106',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (96,'services','10cd395cf71c18328c863c08e78f3fd0','content/view/full/107',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (101,'news/off_topic/new_website','0c0589f38af62cd21f20d37e906bb5de','content/view/full/111',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (102,'news/staff_news/mr_smith_joined_us','5f9ddd15b000a10b585cb57647e9f387','content/view/full/83',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (103,'news/business_news/live_from_top_fair_2003','50fb0286625a02fd09c01b984cd985a9','content/view/full/81',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (104,'news/reports/live_from_top_fair_2003','4577e798f398f1d9437338be5c9a83d5','content/view/full/112',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (106,'products/publishabc','68eaddddc60054eef37a76b0fb429952','content/view/full/114',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (108,'contact/contact_us','9f8a82d9487a7189ffee59fabbaceb89','content/view/full/110',1,117,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (109,'about','46b3931b9959c927df4fc65fdee94b07','content/view/full/109',1,110,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (110,'general_info/about','136f5f5c96ca444bbf07042b0597864d','content/view/full/109',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (112,'general_info','fb56bf0921bfd932e96f9e2167884487','content/view/full/108',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (113,'information/*','109c37699c2b15b48493419b460eb7c6','general_info/{1}',1,0,1);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (114,'general_info/shop_info','2b3337b223f81931c8addf43fff88f69','content/view/full/117',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (115,'services/support','5fb758997f086bac4a01a3058174bd1c','content/view/full/118',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (116,'services/development','119490e5cab36746526adbd2432cfe75','content/view/full/119',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (117,'contact_us','53a2c328fefc1efd85d75137a9d833ab','content/view/full/110',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (118,'users/anonymous_users','3ae1aac958e1c82013689d917d34967a','content/view/full/120',1,0,0);
INSERT INTO ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) VALUES (119,'users/anonymous_users/anonymous_user','aad93975f09371695ba08292fd9698db','content/view/full/121',1,0,0);





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
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (32,24,13);
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






