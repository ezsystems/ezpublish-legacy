-- MySQL dump 8.22
--
-- Host: localhost    Database: wy3
---------------------------------------------------------
-- Server version	3.23.54

--
-- Table structure for table 'ezapprove_items'
--

CREATE TABLE ezapprove_items (
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) NOT NULL default '0',
  collaboration_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezapprove_items'
--



--
-- Table structure for table 'ezbasket'
--

CREATE TABLE ezbasket (
  id int(11) NOT NULL auto_increment,
  session_id varchar(255) NOT NULL default '',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezbasket_session_id (session_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezbasket'
--



--
-- Table structure for table 'ezbinaryfile'
--

CREATE TABLE ezbinaryfile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezbinaryfile'
--



--
-- Table structure for table 'ezcollab_group'
--

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

--
-- Dumping data for table 'ezcollab_group'
--



--
-- Table structure for table 'ezcollab_item'
--

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

--
-- Dumping data for table 'ezcollab_item'
--



--
-- Table structure for table 'ezcollab_item_group_link'
--

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

--
-- Dumping data for table 'ezcollab_item_group_link'
--



--
-- Table structure for table 'ezcollab_item_message_link'
--

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

--
-- Dumping data for table 'ezcollab_item_message_link'
--



--
-- Table structure for table 'ezcollab_item_participant_link'
--

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

--
-- Dumping data for table 'ezcollab_item_participant_link'
--



--
-- Table structure for table 'ezcollab_item_status'
--

CREATE TABLE ezcollab_item_status (
  collaboration_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  is_read int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  last_read int(11) NOT NULL default '0',
  PRIMARY KEY  (collaboration_id,user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcollab_item_status'
--



--
-- Table structure for table 'ezcollab_notification_rule'
--

CREATE TABLE ezcollab_notification_rule (
  id int(11) NOT NULL auto_increment,
  user_id varchar(255) NOT NULL default '',
  collab_identifier varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcollab_notification_rule'
--



--
-- Table structure for table 'ezcollab_profile'
--

CREATE TABLE ezcollab_profile (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  main_group int(11) NOT NULL default '0',
  data_text1 text NOT NULL,
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcollab_profile'
--



--
-- Table structure for table 'ezcollab_simple_message'
--

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

--
-- Dumping data for table 'ezcollab_simple_message'
--



--
-- Table structure for table 'ezcontent_translation'
--

CREATE TABLE ezcontent_translation (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  locale varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontent_translation'
--


INSERT INTO ezcontent_translation VALUES (1,'English (United Kingdom)','eng-GB');

--
-- Table structure for table 'ezcontentbrowsebookmark'
--

CREATE TABLE ezcontentbrowsebookmark (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezcontentbrowsebookmark_user (user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentbrowsebookmark'
--



--
-- Table structure for table 'ezcontentbrowserecent'
--

CREATE TABLE ezcontentbrowserecent (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezcontentbrowserecent_user (user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentbrowserecent'
--


INSERT INTO ezcontentbrowserecent VALUES (1,14,44,1076581401,'Setup');
INSERT INTO ezcontentbrowserecent VALUES (2,14,193,1076581463,'Common ini settings');
INSERT INTO ezcontentbrowserecent VALUES (3,14,46,1076577902,'Setup links');

--
-- Table structure for table 'ezcontentclass'
--

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

--
-- Dumping data for table 'ezcontentclass'
--


INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694);
INSERT INTO ezcontentclass VALUES (2,0,'Article','article','<title>',14,14,1024392098,1066907423);
INSERT INTO ezcontentclass VALUES (3,0,'User group','user_group','<name>',14,14,1024392098,1048494743);
INSERT INTO ezcontentclass VALUES (4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1069678307);
INSERT INTO ezcontentclass VALUES (5,0,'Image','image','<name>',8,14,1031484992,1048494784);
INSERT INTO ezcontentclass VALUES (10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353);
INSERT INTO ezcontentclass VALUES (12,0,'File','file','<name>',14,14,1052385472,1052385669);
INSERT INTO ezcontentclass VALUES (14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885);
INSERT INTO ezcontentclass VALUES (15,0,'Template look','template_look','<title>',14,14,1066390045,1069416268);
INSERT INTO ezcontentclass VALUES (23,0,'Product','product','<name>',14,14,1068472452,1068557806);
INSERT INTO ezcontentclass VALUES (24,0,'Feedback form','feedback_form','<name>',14,14,1068554718,1068555117);
INSERT INTO ezcontentclass VALUES (25,0,'Review','review','<topic>',14,14,1068565707,1068648892);
INSERT INTO ezcontentclass VALUES (26,0,'Common ini settings','common_ini_settings','<name>',14,14,1076581204,1076581337);

--
-- Table structure for table 'ezcontentclass_attribute'
--

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

--
-- Dumping data for table 'ezcontentclass_attribute'
--


INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (218,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute VALUES (180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute VALUES (160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (215,0,25,'topic','Topic','ezstring',0,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (216,0,25,'description','Description','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (217,0,25,'rating','Rating','ezinteger',1,1,3,0,5,0,3,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (12,0,4,'user_account','User account','ezuser',1,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (201,0,23,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (202,0,23,'product_number','Product number','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (203,0,23,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (204,0,23,'image','Image','ezimage',0,0,4,1,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (205,0,23,'price','Price','ezprice',0,0,5,1,0,0,0,1,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute VALUES (157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute VALUES (210,0,24,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute VALUES (209,0,24,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute VALUES (208,0,24,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute VALUES (207,0,24,'description','Description','ezxmltext',1,0,2,15,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (206,0,24,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (231,0,26,'imagelarge','Image Large Size','ezinisetting',0,0,13,6,0,0,0,0,0,0,0,'image.ini','large','Filters','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (230,0,26,'imagemedium','Image Medium Size','ezinisetting',0,0,12,6,0,0,0,0,0,0,0,'image.ini','medium','Filters','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (229,0,26,'imagesmall','Image Small Size','ezinisetting',0,0,11,6,0,0,0,0,0,0,0,'image.ini','small','Filters','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (228,0,26,'templatecompile','Template Compile','ezinisetting',0,0,10,2,0,0,0,0,0,0,0,'site.ini','TemplateSettings','TemplateCompile','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (227,0,26,'templatecache','Template Cache','ezinisetting',0,0,9,2,0,0,0,0,0,0,0,'site.ini','TemplateSettings','TemplateCache','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (226,0,26,'viewcaching','View Caching','ezinisetting',0,0,8,2,0,0,0,0,0,0,0,'site.ini','ContentSettings','ViewCaching','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (225,0,26,'debugredirection','Debug Redirection','ezinisetting',0,0,7,2,0,0,0,0,0,0,0,'site.ini','DebugSettings','DebugRedirection','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (224,0,26,'debugiplist','Debug IP List','ezinisetting',0,0,6,6,0,0,0,0,0,0,0,'site.ini','DebugSettings','DebugIPList','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (223,0,26,'debugbyip','Debug By IP','ezinisetting',0,0,5,2,0,0,0,0,0,0,0,'site.ini','DebugSettings','DebugByIP','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (222,0,26,'debugoutput','Debug Output','ezinisetting',0,0,4,2,0,0,0,0,0,0,0,'site.ini','DebugSettings','DebugOutput','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (221,0,26,'defaultpage','Default Page','ezinisetting',0,0,3,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','DefaultPage','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (220,0,26,'indexpage','Index Page','ezinisetting',0,0,2,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','IndexPage','14;15','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (219,0,26,'name','Name','ezstring',1,0,1,0,0,0,0,0,0,0,0,'','','','','',0,1);

--
-- Table structure for table 'ezcontentclass_classgroup'
--

CREATE TABLE ezcontentclass_classgroup (
  contentclass_id int(11) NOT NULL default '0',
  contentclass_version int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (contentclass_id,contentclass_version,group_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentclass_classgroup'
--


INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (2,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (4,0,2,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (5,0,3,'Media');
INSERT INTO ezcontentclass_classgroup VALUES (3,0,2,'');
INSERT INTO ezcontentclass_classgroup VALUES (6,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (7,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (9,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (10,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (11,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (12,0,3,'Media');
INSERT INTO ezcontentclass_classgroup VALUES (13,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (14,0,4,'Setup');
INSERT INTO ezcontentclass_classgroup VALUES (15,0,4,'Setup');
INSERT INTO ezcontentclass_classgroup VALUES (12,1,3,'Media');
INSERT INTO ezcontentclass_classgroup VALUES (16,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (17,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (21,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (20,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (21,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (23,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (24,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (25,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (26,0,4,'Setup');

--
-- Table structure for table 'ezcontentclassgroup'
--

CREATE TABLE ezcontentclassgroup (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentclassgroup'
--


INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO ezcontentclassgroup VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO ezcontentclassgroup VALUES (3,'Media',8,14,1032009743,1033922120);
INSERT INTO ezcontentclassgroup VALUES (4,'Setup',14,14,1066383702,1066383712);

--
-- Table structure for table 'ezcontentobject'
--

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

--
-- Dumping data for table 'ezcontentobject'
--


INSERT INTO ezcontentobject VALUES (1,14,1,1,'Shop',8,0,1033917596,1069686123,1,'');
INSERT INTO ezcontentobject VALUES (4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject VALUES (10,14,2,4,'Anonymous User',2,0,1033920665,1072181255,1,'');
INSERT INTO ezcontentobject VALUES (11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject VALUES (12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject VALUES (13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject VALUES (14,14,2,4,'Administrator User',7,0,1033920830,1068556425,1,'');
INSERT INTO ezcontentobject VALUES (41,14,3,1,'Media',1,0,1060695457,1060695457,1,'');
INSERT INTO ezcontentobject VALUES (42,14,11,1,'Setup',1,0,1066383068,1066383068,1,'');
INSERT INTO ezcontentobject VALUES (43,14,11,14,'Classes',11,0,1066384365,1068640429,1,'');
INSERT INTO ezcontentobject VALUES (44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,'');
INSERT INTO ezcontentobject VALUES (45,14,11,14,'Look and feel',11,0,1066388816,1068640502,1,'');
INSERT INTO ezcontentobject VALUES (46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,'');
INSERT INTO ezcontentobject VALUES (47,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (122,14,1,5,'New Image',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (49,14,1,1,'News',1,0,1066398020,1066398020,1,'');
INSERT INTO ezcontentobject VALUES (51,14,1,14,'New Setup link',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (53,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (56,14,11,15,'Shop',59,0,1066643397,1069839572,1,'');
INSERT INTO ezcontentobject VALUES (160,14,1,2,'News bulletin October',2,0,1068047455,1069686818,1,'');
INSERT INTO ezcontentobject VALUES (161,14,1,10,'Shipping and returns',4,0,1068047603,1069688507,1,'');
INSERT INTO ezcontentobject VALUES (219,14,1,10,'Privacy notice',2,0,1068542692,1069688136,1,'');
INSERT INTO ezcontentobject VALUES (83,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (84,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (85,14,5,1,'New Folder',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (88,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (91,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (96,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (103,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (104,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (105,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (106,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (213,14,1,1,'Products',2,0,1068473231,1068556203,1,'');
INSERT INTO ezcontentobject VALUES (115,14,11,14,'Cache',5,0,1066991725,1068640475,1,'');
INSERT INTO ezcontentobject VALUES (116,14,11,14,'URL translator',4,0,1066992054,1068640525,1,'');
INSERT INTO ezcontentobject VALUES (117,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (263,14,1,23,'eZ publish basics',1,0,1069752520,1069752520,1,'');
INSERT INTO ezcontentobject VALUES (260,14,1,23,'Troll',1,0,1069752252,1069752252,1,'');
INSERT INTO ezcontentobject VALUES (257,14,1,1,'Books',1,0,1069751025,1069751025,1,'');
INSERT INTO ezcontentobject VALUES (222,14,1,24,'Contact us',2,0,1068554919,1069688573,1,'');
INSERT INTO ezcontentobject VALUES (258,14,1,1,'Cars',2,0,1069751059,1069751108,1,'');
INSERT INTO ezcontentobject VALUES (259,14,1,1,'DVD',1,0,1069751462,1069751462,1,'');
INSERT INTO ezcontentobject VALUES (220,14,1,10,'Conditions of use',2,0,1068542738,1069688214,1,'');
INSERT INTO ezcontentobject VALUES (265,14,1,23,'Action DVD',1,0,1069752921,1069752921,1,'');
INSERT INTO ezcontentobject VALUES (264,14,1,23,'Music DVD',1,0,1069752759,1069752759,1,'');
INSERT INTO ezcontentobject VALUES (250,14,1,2,'News bulletin November',1,0,1069687269,1069687269,1,'');
INSERT INTO ezcontentobject VALUES (251,14,1,1,'Cords',1,0,1069687877,1069687877,1,'');
INSERT INTO ezcontentobject VALUES (252,14,1,23,'1 meter cord',1,0,1069687927,1069687927,1,'');
INSERT INTO ezcontentobject VALUES (253,14,1,23,'5 meter cord',1,0,1069687961,1069687961,1,'');
INSERT INTO ezcontentobject VALUES (254,14,1,2,'A new cord',1,0,1069688677,1069688677,1,'');
INSERT INTO ezcontentobject VALUES (262,14,1,23,'Summer book',1,0,1069752445,1069752445,1,'');
INSERT INTO ezcontentobject VALUES (261,14,1,23,'Ferrari',1,0,1069752332,1069752332,1,'');
INSERT INTO ezcontentobject VALUES (266,14,2,3,'Anonymous Users',1,0,1072181235,1072181235,1,'');
INSERT INTO ezcontentobject VALUES (267,14,11,1,'Common ini settings',1,0,1076581401,1076581401,1,'');
INSERT INTO ezcontentobject VALUES (268,14,11,26,'Common ini settings',2,0,1076581463,1076577930,1,'');
INSERT INTO ezcontentobject VALUES (269,14,11,14,'Common ini settings',1,0,1076577902,1076577902,1,'');

--
-- Table structure for table 'ezcontentobject_attribute'
--

CREATE TABLE ezcontentobject_attribute (
  id int(11) NOT NULL auto_increment,
  language_code varchar(20) NOT NULL default '',
  version int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  contentclassattribute_id int(11) NOT NULL default '0',
  data_text mediumtext,
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

--
-- Dumping data for table 'ezcontentobject_attribute'
--


INSERT INTO ezcontentobject_attribute VALUES (7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (103,'eng-GB',11,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (102,'eng-GB',11,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (109,'eng-GB',11,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel.png\"\n         original_filename=\"look_and_feel.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (108,'eng-GB',11,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage');
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage');
INSERT INTO ezcontentobject_attribute VALUES (152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage');
INSERT INTO ezcontentobject_attribute VALUES (154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (940,'eng-GB',1,250,1,'News bulletin November',0,0,0,0,'news bulletin november','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (519,'eng-GB',2,160,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin_october.\"\n         suffix=\"\"\n         basename=\"news_bulletin_october\"\n         dirpath=\"var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB\"\n         url=\"var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB/news_bulletin_october.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"519\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (520,'eng-GB',2,160,123,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute VALUES (521,'eng-GB',2,160,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute VALUES (785,'eng-GB',2,220,140,'Conditions of use',0,0,0,0,'conditions of use','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (786,'eng-GB',2,220,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The conditions of use is where you state how people shall act and behave in your webshop. </paragraph>\n  <paragraph>It also states the policy you have towards the customer.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (951,'eng-GB',1,252,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"1_meter_cord.\"\n         suffix=\"\"\n         basename=\"1_meter_cord\"\n         dirpath=\"var/shop/storage/images/products/cords/1_meter_cord/951-1-eng-GB\"\n         url=\"var/shop/storage/images/products/cords/1_meter_cord/951-1-eng-GB/1_meter_cord.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069687893\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (524,'eng-GB',4,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"shipping_and_returns.\"\n         suffix=\"\"\n         basename=\"shipping_and_returns\"\n         dirpath=\"var/shop/storage/images/shipping_and_returns/524-4-eng-GB\"\n         url=\"var/shop/storage/images/shipping_and_returns/524-4-eng-GB/shipping_and_returns.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (522,'eng-GB',4,161,140,'Shipping and returns',0,0,0,0,'shipping and returns','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (523,'eng-GB',4,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Shipping and returns are always one of the most important pages in your webshop. Even if people are not returning their products to you they want to know is this is possible. It is kind of a guarantee on their bahalf. It is also a way for you to show that you are professional.</paragraph>\n  <paragraph>Normally a page like this contains information about:</paragraph>\n  <paragraph>\n    <line>Delivery Time</line>\n    <line>Cooling-off Period/Return Rights</line>\n    <line>Faulty or Defective Goods</line>\n    <line>Order Cancellation by the Customer</line>\n    <line>Order Cancellation by Us</line>\n    <line>Replacement Goods</line>\n    <line>Exceptions</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (437,'eng-GB',59,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (152,'eng-GB',59,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"shop.gif\"\n         suffix=\"gif\"\n         basename=\"shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop.gif\"\n         original_filename=\"webshop.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069839028\">\n  <original attribute_id=\"152\"\n            attribute_version=\"58\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069839029\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069839029\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069843091\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (516,'eng-GB',2,160,1,'News bulletin October',0,0,0,0,'news bulletin october','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',8,1,4,'Shop',0,0,0,0,'shop','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (2,'eng-GB',8,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (1006,'eng-GB',1,265,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"action_dvd.\"\n         suffix=\"\"\n         basename=\"action_dvd\"\n         dirpath=\"var/shop/storage/images/products/dvd/action_dvd/1006-1-eng-GB\"\n         url=\"var/shop/storage/images/products/dvd/action_dvd/1006-1-eng-GB/action_dvd.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752769\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (1007,'eng-GB',1,265,205,'',0,12,0,0,'','ezprice');
INSERT INTO ezcontentobject_attribute VALUES (972,'eng-GB',1,257,4,'Books',0,0,0,0,'books','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (977,'eng-GB',1,259,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (976,'eng-GB',1,259,4,'DVD',0,0,0,0,'dvd','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (974,'eng-GB',2,258,4,'Cars',0,0,0,0,'cars','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (975,'eng-GB',2,258,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (973,'eng-GB',1,257,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (518,'eng-GB',2,160,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We release a new website. As you all can see it is a great step forward from the old site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (963,'eng-GB',1,254,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute VALUES (517,'eng-GB',2,160,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here are the latest news from this webshop. We will publish these news as soon as we have new products, new releases and important information to tell. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (787,'eng-GB',2,220,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"conditions_of_use.\"\n         suffix=\"\"\n         basename=\"conditions_of_use\"\n         dirpath=\"var/shop/storage/images/conditions_of_use/787-2-eng-GB\"\n         url=\"var/shop/storage/images/conditions_of_use/787-2-eng-GB/conditions_of_use.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"787\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (784,'eng-GB',2,219,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"privacy_notice.\"\n         suffix=\"\"\n         basename=\"privacy_notice\"\n         dirpath=\"var/shop/storage/images/privacy_notice/784-2-eng-GB\"\n         url=\"var/shop/storage/images/privacy_notice/784-2-eng-GB/privacy_notice.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"784\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (782,'eng-GB',2,219,140,'Privacy notice',0,0,0,0,'privacy notice','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (783,'eng-GB',2,219,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>In the privacy notice you should write about how secure you handle information you collect from your customers. What do you do with it and what do you not use it for?</paragraph>\n  <paragraph>Normally people are very interested in knowing about this and it is therefore very important that you state this as clear as possible. It can be the make or breake of your webshop.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (792,'eng-GB',2,222,207,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A contact page is where you let your readers, customers, partners etc find information on how to get in touch with you. </paragraph>\n  <paragraph>Normal info to have here is: telephone numbers, fax numbers, e-mail addresses, visitors address and snail mail address. </paragraph>\n  <paragraph>This site is also often used for people that wants to tip the site on news, updates etc. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (793,'eng-GB',2,222,208,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (794,'eng-GB',2,222,209,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (795,'eng-GB',2,222,210,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (958,'eng-GB',1,254,1,'A new cord',0,0,0,0,'a new cord','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (959,'eng-GB',1,254,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The have finally received some 5 meter cords from our supplier</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (960,'eng-GB',1,254,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>They are available from our shop for as low as £13. Get i while you can.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (961,'eng-GB',1,254,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"a_new_cord.\"\n         suffix=\"\"\n         basename=\"a_new_cord\"\n         dirpath=\"var/shop/storage/images/news/a_new_cord/961-1-eng-GB\"\n         url=\"var/shop/storage/images/news/a_new_cord/961-1-eng-GB/a_new_cord.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069688601\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (962,'eng-GB',1,254,123,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute VALUES (1005,'eng-GB',1,265,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Clips from the best action movies from the leading actors from Hollywood. 3 hours of non-stop action from back to back.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (1004,'eng-GB',1,265,202,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1003,'eng-GB',1,265,201,'Action DVD',0,0,0,0,'action dvd','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1002,'eng-GB',1,264,205,'',0,6,0,0,'','ezprice');
INSERT INTO ezcontentobject_attribute VALUES (1001,'eng-GB',1,264,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"music_dvd.\"\n         suffix=\"\"\n         basename=\"music_dvd\"\n         dirpath=\"var/shop/storage/images/products/dvd/music_dvd/1001-1-eng-GB\"\n         url=\"var/shop/storage/images/products/dvd/music_dvd/1001-1-eng-GB/music_dvd.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752535\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (1000,'eng-GB',1,264,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A collection of music from the year 2003. The best of the best. All top of the charts from Top 100.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (998,'eng-GB',1,264,201,'Music DVD',0,0,0,0,'music dvd','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (999,'eng-GB',1,264,202,'60897',0,0,0,0,'60897','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (988,'eng-GB',1,262,201,'Summer book',0,0,0,0,'summer book','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (989,'eng-GB',1,262,202,'1324',0,0,0,0,'1324','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (990,'eng-GB',1,262,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The book is about all the colors and smells of summer. The book is packed with picures of the beautiful landscape in Norway.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (991,'eng-GB',1,262,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"summer_book.\"\n         suffix=\"\"\n         basename=\"summer_book\"\n         dirpath=\"var/shop/storage/images/products/books/summer_book/991-1-eng-GB\"\n         url=\"var/shop/storage/images/products/books/summer_book/991-1-eng-GB/summer_book.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752350\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (992,'eng-GB',1,262,205,'',0,79,0,0,'','ezprice');
INSERT INTO ezcontentobject_attribute VALUES (993,'eng-GB',1,263,201,'eZ publish basics',0,0,0,0,'ez publish basics','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (994,'eng-GB',1,263,202,'123414',0,0,0,0,'123414','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (995,'eng-GB',1,263,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Everything you need to know about eZ publish. All steps from download to the finished site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (996,'eng-GB',1,263,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_publish_basics.\"\n         suffix=\"\"\n         basename=\"ez_publish_basics\"\n         dirpath=\"var/shop/storage/images/products/books/ez_publish_basics/996-1-eng-GB\"\n         url=\"var/shop/storage/images/products/books/ez_publish_basics/996-1-eng-GB/ez_publish_basics.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752456\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (767,'eng-GB',2,213,4,'Products',0,0,0,0,'products','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (768,'eng-GB',2,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Our products</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (952,'eng-GB',1,252,205,'',0,9,0,0,'','ezprice');
INSERT INTO ezcontentobject_attribute VALUES (953,'eng-GB',1,253,201,'5 meter cord',0,0,0,0,'5 meter cord','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (954,'eng-GB',1,253,202,'34555',0,0,0,0,'34555','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (955,'eng-GB',1,253,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This cord is five meters long and works for all machines</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (956,'eng-GB',1,253,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"5_meter_cord.\"\n         suffix=\"\"\n         basename=\"5_meter_cord\"\n         dirpath=\"var/shop/storage/images/products/cords/5_meter_cord/956-1-eng-GB\"\n         url=\"var/shop/storage/images/products/cords/5_meter_cord/956-1-eng-GB/5_meter_cord.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069687936\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (957,'eng-GB',1,253,205,'',0,13,0,0,'','ezprice');
INSERT INTO ezcontentobject_attribute VALUES (28,'eng-GB',7,14,8,'Administrator',0,0,0,0,'administrator','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (29,'eng-GB',7,14,9,'User',0,0,0,0,'user','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (30,'eng-GB',7,14,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute VALUES (978,'eng-GB',1,260,201,'Troll',0,0,0,0,'troll','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (979,'eng-GB',1,260,202,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (980,'eng-GB',1,260,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Troll was the first - and so far the only - car made in Norway. Only five cars left the factory in total. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (981,'eng-GB',1,260,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"troll.\"\n         suffix=\"\"\n         basename=\"troll\"\n         dirpath=\"var/shop/storage/images/products/cars/troll/981-1-eng-GB\"\n         url=\"var/shop/storage/images/products/cars/troll/981-1-eng-GB/troll.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752061\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (982,'eng-GB',1,260,205,'',0,980,0,0,'','ezprice');
INSERT INTO ezcontentobject_attribute VALUES (997,'eng-GB',1,263,205,'',0,9,0,0,'','ezprice');
INSERT INTO ezcontentobject_attribute VALUES (983,'eng-GB',1,261,201,'Ferrari',0,0,0,0,'ferrari','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (984,'eng-GB',1,261,202,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (985,'eng-GB',1,261,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Enjoy the feeling. It&apos;s nothing more to say. If you have ever tried one you never want to leave and you</line>\n    <line>re a fan forever.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (986,'eng-GB',1,261,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ferrari.\"\n         suffix=\"\"\n         basename=\"ferrari\"\n         dirpath=\"var/shop/storage/images/products/cars/ferrari/986-1-eng-GB\"\n         url=\"var/shop/storage/images/products/cars/ferrari/986-1-eng-GB/ferrari.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752264\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (987,'eng-GB',1,261,205,'',0,200000,0,0,'','ezprice');
INSERT INTO ezcontentobject_attribute VALUES (669,'eng-GB',59,56,196,'ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (911,'eng-GB',59,56,218,'Copyright &copy; eZ systems as 1999-2004',0,0,0,0,'copyright &copy; ez systems as 1999-2004','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',59,56,160,'shop_red',0,0,0,0,'shop_red','ezpackage');
INSERT INTO ezcontentobject_attribute VALUES (154,'eng-GB',59,56,161,'shop_package',0,0,0,0,'shop_package','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (151,'eng-GB',59,56,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (104,'eng-GB',11,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (105,'eng-GB',11,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (323,'eng-GB',5,115,152,'Cache',0,0,0,0,'cache','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (324,'eng-GB',5,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache.png\"\n         original_filename=\"cache.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (327,'eng-GB',4,116,152,'URL translator',0,0,0,0,'url translator','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (328,'eng-GB',4,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator.png\"\n         original_filename=\"url_translator.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (325,'eng-GB',5,115,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (326,'eng-GB',5,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (110,'eng-GB',11,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (111,'eng-GB',11,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (329,'eng-GB',4,116,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (330,'eng-GB',4,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (941,'eng-GB',1,250,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This month started off with the release of two new products. Product A and Product B.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (942,'eng-GB',1,250,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>They are both part of a new product portfolio that will be the basis of this shop. There will be examples on products like this in many different categories. </paragraph>\n  <paragraph>In these categories you can add as many products you like, set prices and write product texts. You should also always add pictures of the product so that the users can see the product they are reading about.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (943,'eng-GB',1,250,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin_november.\"\n         suffix=\"\"\n         basename=\"news_bulletin_november\"\n         dirpath=\"var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB\"\n         url=\"var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB/news_bulletin_november.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069686831\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (944,'eng-GB',1,250,123,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute VALUES (945,'eng-GB',1,250,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute VALUES (946,'eng-GB',1,251,4,'Cords',0,0,0,0,'cords','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (947,'eng-GB',1,251,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (948,'eng-GB',1,252,201,'1 meter cord',0,0,0,0,'1 meter cord','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (949,'eng-GB',1,252,202,'13444',0,0,0,0,'13444','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (950,'eng-GB',1,252,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This cord is one meter long and works for all machines</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (791,'eng-GB',2,222,206,'Contact us',0,0,0,0,'contact us','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (150,'eng-GB',59,56,157,'Shop',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1008,'eng-GB',1,266,6,'Anonymous Users',0,0,0,0,'anonymous users','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1009,'eng-GB',1,266,7,'User group for the anonymous user',0,0,0,0,'user group for the anonymous user','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (19,'eng-GB',2,10,8,'Anonymous',0,0,0,0,'anonymous','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (20,'eng-GB',2,10,9,'User',0,0,0,0,'user','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (21,'eng-GB',2,10,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute VALUES (1010,'eng-GB',1,267,4,'Common ini settings',0,0,0,0,'common ini settings','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1011,'eng-GB',1,267,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (1012,'eng-GB',1,268,219,'Common ini settings',0,0,0,0,'common ini settings','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1013,'eng-GB',1,268,220,'/content/view/full/2/',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1014,'eng-GB',1,268,221,'/content/view/full/2/',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1015,'eng-GB',1,268,222,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1016,'eng-GB',1,268,223,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1017,'eng-GB',1,268,224,'=',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1018,'eng-GB',1,268,225,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1019,'eng-GB',1,268,226,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1020,'eng-GB',1,268,227,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1021,'eng-GB',1,268,228,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1022,'eng-GB',1,268,229,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1023,'eng-GB',1,268,230,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1024,'eng-GB',1,268,231,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1025,'eng-GB',1,269,152,'Common ini settings',0,0,0,0,'common ini settings','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1026,'eng-GB',1,269,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"common_ini_settings1.png\"\n         suffix=\"png\"\n         basename=\"common_ini_settings1\"\n         dirpath=\"var/shop/storage/images-versioned/1026/1-eng-GB\"\n         url=\"var/shop/storage/images-versioned/1026/1-eng-GB/common_ini_settings1.png\"\n         original_filename=\"exec.png\"\n         mime_type=\"image/png\"\n         width=\"32\"\n         height=\"32\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1076577901\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"common_ini_settings1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images-versioned/1026/1-eng-GB\"\n         url=\"var/shop/storage/images-versioned/1026/1-eng-GB/common_ini_settings1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"32\"\n         height=\"32\"\n         alias_key=\"183954394\"\n         timestamp=\"1076577903\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"common_ini_settings1_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images-versioned/1026/1-eng-GB\"\n         url=\"var/shop/storage/images-versioned/1026/1-eng-GB/common_ini_settings1_medium.png\"\n         mime_type=\"image/png\"\n         width=\"32\"\n         height=\"32\"\n         alias_key=\"472385770\"\n         timestamp=\"1076577903\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"common_ini_settings1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images-versioned/1026/1-eng-GB\"\n         url=\"var/shop/storage/images-versioned/1026/1-eng-GB/common_ini_settings1_large.png\"\n         mime_type=\"image/png\"\n         width=\"32\"\n         height=\"32\"\n         alias_key=\"-958410206\"\n         timestamp=\"1076577912\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (1027,'eng-GB',1,269,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (1028,'eng-GB',1,269,155,'content/edit/268',0,0,0,0,'content/edit/268','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1012,'eng-GB',2,268,219,'Common ini settings',0,0,0,0,'common ini settings','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (1013,'eng-GB',2,268,220,'/content/view/full/2/',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1014,'eng-GB',2,268,221,'/content/view/full/2/',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1015,'eng-GB',2,268,222,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1016,'eng-GB',2,268,223,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1017,'eng-GB',2,268,224,'=',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1018,'eng-GB',2,268,225,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1019,'eng-GB',2,268,226,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1020,'eng-GB',2,268,227,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1021,'eng-GB',2,268,228,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1022,'eng-GB',2,268,229,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1023,'eng-GB',2,268,230,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (1024,'eng-GB',2,268,231,'',0,0,0,0,'','ezinisetting');

--
-- Table structure for table 'ezcontentobject_link'
--

CREATE TABLE ezcontentobject_link (
  id int(11) NOT NULL auto_increment,
  from_contentobject_id int(11) NOT NULL default '0',
  from_contentobject_version int(11) NOT NULL default '0',
  to_contentobject_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_link'
--


INSERT INTO ezcontentobject_link VALUES (10,1,8,49);

--
-- Table structure for table 'ezcontentobject_name'
--

CREATE TABLE ezcontentobject_name (
  contentobject_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  content_version int(11) NOT NULL default '0',
  content_translation varchar(20) NOT NULL default '',
  real_translation varchar(20) default NULL,
  PRIMARY KEY  (contentobject_id,content_version,content_translation)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_name'
--


INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (4,'Users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (10,'Anonymous User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (11,'Guest accounts',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (12,'Administrator users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (13,'Editors',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (41,'Media',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (42,'Setup',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (44,'Setup links',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Setup Objects',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (46,'Fonts and colors',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (46,'Look and feel',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (47,'New Template look',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (116,'URL translator',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (49,'News',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',37,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Look and feel',7,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (51,'New Setup link',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Look and feel',8,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (53,'New Template look',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',8,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',36,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (161,'About this forum',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranetyy',30,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',25,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',24,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',22,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',23,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',35,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (122,'New Image',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Look and feel',9,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',7,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',8,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',9,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',38,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',10,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (83,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (84,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',11,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (85,'New Folder',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (88,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',33,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranetyy',31,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',32,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',12,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',13,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (91,'New Template look',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',18,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Shop',57,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (161,'Shipping and returns',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',39,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (96,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (213,'Hardware',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (222,'Contact us',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',34,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',20,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (160,'News bulletin',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (103,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (104,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (260,'Troll',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (105,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (106,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Corporate',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Setup Objects',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',7,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Setup Objects',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (115,'Cache',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Setup Objects',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (116,'URL translator',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (117,'New Article',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Look and feel',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Look and feel',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',19,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (115,'Cache',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',21,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (115,'Cache',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',26,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranetyy',27,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranetyy',28,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranetyy',29,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',41,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',42,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',40,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Forum',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Forum',45,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (263,'eZ publish basics',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Forum',44,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (264,'Music DVD',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Forum',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (160,'News bulletin October',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (220,'Conditions of use',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Shop',8,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Forum',46,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (213,'Products',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (219,'Privacy notice',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (219,'Shipping and returns',0,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (261,'Ferrari',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Shop',7,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',7,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',55,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Forum',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Forum',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (262,'Summer book',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',43,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',47,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',48,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',49,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',50,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',51,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (115,'Cache',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',9,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Look and feel',10,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (116,'URL translator',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (115,'Cache',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',10,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (43,'Classes',11,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (45,'Look and feel',11,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (116,'URL translator',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',52,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Shop',59,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Shop',58,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (265,'Action DVD',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',53,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',8,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'My shop',54,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (250,'News bulletin November',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Shop',56,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (259,'DVD',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (251,'Cords',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (252,'1 meter cord',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (253,'5 meter cord',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (219,'Privacy notice',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (220,'Conditions of use',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (161,'Shipping and returns',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (161,'Shipping and returns',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (222,'Contact us',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (254,'A new cord',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (257,'Books',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (258,'Flowers',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (258,'Cars',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (266,'Anonymous Users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (10,'Anonymous User',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (267,'Common ini settings',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (268,'Common ini settings',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (269,'Common ini settings',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (268,'Common ini settings',2,'eng-GB','eng-GB');

--
-- Table structure for table 'ezcontentobject_tree'
--

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
  modified_subnode int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id),
  KEY ezcontentobject_tree_path (path_string),
  KEY ezcontentobject_tree_p_node_id (parent_node_id),
  KEY ezcontentobject_tree_co_id (contentobject_id),
  KEY ezcontentobject_tree_depth (depth),
  KEY ezcontentobject_tree_mo_su (modified_subnode)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_tree'
--


INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1,1076577930);
INSERT INTO ezcontentobject_tree VALUES (2,1,1,8,1,1,'/1/2/',8,1,0,'',2,1069752921);
INSERT INTO ezcontentobject_tree VALUES (5,1,4,1,0,1,'/1/5/',1,1,0,'users',5,1072181255);
INSERT INTO ezcontentobject_tree VALUES (11,192,10,2,1,3,'/1/5/192/11/',9,1,0,'users/anonymous_users/anonymous_user',11,1072181255);
INSERT INTO ezcontentobject_tree VALUES (12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12,1033920746);
INSERT INTO ezcontentobject_tree VALUES (13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13,1068556425);
INSERT INTO ezcontentobject_tree VALUES (14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14,1033920794);
INSERT INTO ezcontentobject_tree VALUES (15,13,14,7,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15,1068556425);
INSERT INTO ezcontentobject_tree VALUES (43,1,41,1,1,1,'/1/43/',9,1,0,'media',43,1060695457);
INSERT INTO ezcontentobject_tree VALUES (44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44,1076577930);
INSERT INTO ezcontentobject_tree VALUES (45,46,43,11,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45,1068640429);
INSERT INTO ezcontentobject_tree VALUES (46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46,1076577902);
INSERT INTO ezcontentobject_tree VALUES (47,46,45,11,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47,1068640502);
INSERT INTO ezcontentobject_tree VALUES (48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48,1069839572);
INSERT INTO ezcontentobject_tree VALUES (50,2,49,1,1,2,'/1/2/50/',9,1,1,'news',50,1069688677);
INSERT INTO ezcontentobject_tree VALUES (54,48,56,59,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/shop',54,1069839572);
INSERT INTO ezcontentobject_tree VALUES (95,46,115,5,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95,1068640475);
INSERT INTO ezcontentobject_tree VALUES (96,46,116,4,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96,1068640525);
INSERT INTO ezcontentobject_tree VALUES (126,50,160,2,1,3,'/1/2/50/126/',9,1,0,'news/news_bulletin_october',126,1069686818);
INSERT INTO ezcontentobject_tree VALUES (127,2,161,4,1,2,'/1/2/127/',9,1,5,'shipping_and_returns',127,1069688507);
INSERT INTO ezcontentobject_tree VALUES (154,2,213,2,1,2,'/1/2/154/',9,1,2,'products',154,1069752921);
INSERT INTO ezcontentobject_tree VALUES (160,2,219,2,1,2,'/1/2/160/',9,1,3,'privacy_notice',160,1069688136);
INSERT INTO ezcontentobject_tree VALUES (161,2,220,2,1,2,'/1/2/161/',9,1,4,'conditions_of_use',161,1069688214);
INSERT INTO ezcontentobject_tree VALUES (163,2,222,2,1,2,'/1/2/163/',9,1,6,'contact_us',163,1069688573);
INSERT INTO ezcontentobject_tree VALUES (176,50,250,1,1,3,'/1/2/50/176/',9,1,0,'news/news_bulletin_november',176,1069687269);
INSERT INTO ezcontentobject_tree VALUES (177,154,251,1,1,3,'/1/2/154/177/',9,1,0,'products/cords',177,1069687961);
INSERT INTO ezcontentobject_tree VALUES (178,177,252,1,1,4,'/1/2/154/177/178/',9,1,0,'products/cords/1_meter_cord',178,1069687927);
INSERT INTO ezcontentobject_tree VALUES (179,177,253,1,1,4,'/1/2/154/177/179/',9,1,0,'products/cords/5_meter_cord',179,1069687961);
INSERT INTO ezcontentobject_tree VALUES (180,50,254,1,1,3,'/1/2/50/180/',9,1,0,'news/a_new_cord',180,1069688677);
INSERT INTO ezcontentobject_tree VALUES (183,154,257,1,1,3,'/1/2/154/183/',9,1,0,'products/books',183,1069752520);
INSERT INTO ezcontentobject_tree VALUES (184,154,258,2,1,3,'/1/2/154/184/',9,1,0,'products/cars',184,1069752332);
INSERT INTO ezcontentobject_tree VALUES (185,154,259,1,1,3,'/1/2/154/185/',9,1,0,'products/dvd',185,1069752921);
INSERT INTO ezcontentobject_tree VALUES (186,184,260,1,1,4,'/1/2/154/184/186/',9,1,0,'products/cars/troll',186,1069752252);
INSERT INTO ezcontentobject_tree VALUES (187,184,261,1,1,4,'/1/2/154/184/187/',9,1,0,'products/cars/ferrari',187,1069752332);
INSERT INTO ezcontentobject_tree VALUES (188,183,262,1,1,4,'/1/2/154/183/188/',9,1,0,'products/books/summer_book',188,1069752445);
INSERT INTO ezcontentobject_tree VALUES (189,183,263,1,1,4,'/1/2/154/183/189/',9,1,0,'products/books/ez_publish_basics',189,1069752520);
INSERT INTO ezcontentobject_tree VALUES (190,185,264,1,1,4,'/1/2/154/185/190/',9,1,0,'products/dvd/music_dvd',190,1069752759);
INSERT INTO ezcontentobject_tree VALUES (191,185,265,1,1,4,'/1/2/154/185/191/',9,1,0,'products/dvd/action_dvd',191,1069752921);
INSERT INTO ezcontentobject_tree VALUES (192,5,266,1,1,2,'/1/5/192/',9,1,0,'users/anonymous_users',192,1072181255);
INSERT INTO ezcontentobject_tree VALUES (193,44,267,1,1,2,'/1/44/193/',9,1,0,'setup/common_ini_settings',193,1076577930);
INSERT INTO ezcontentobject_tree VALUES (194,193,268,2,1,3,'/1/44/193/194/',9,1,0,'setup/common_ini_settings/common_ini_settings',194,1076577930);
INSERT INTO ezcontentobject_tree VALUES (195,46,269,1,1,3,'/1/44/46/195/',9,1,0,'setup/setup_links/common_ini_settings',195,1076577902);

--
-- Table structure for table 'ezcontentobject_version'
--

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

--
-- Dumping data for table 'ezcontentobject_version'
--


INSERT INTO ezcontentobject_version VALUES (4,4,14,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version VALUES (439,11,14,1,1033920737,1033920746,1,0,0);
INSERT INTO ezcontentobject_version VALUES (440,12,14,1,1033920760,1033920775,1,0,0);
INSERT INTO ezcontentobject_version VALUES (441,13,14,1,1033920786,1033920794,1,0,0);
INSERT INTO ezcontentobject_version VALUES (880,219,14,2,1069688002,1069688136,1,0,0);
INSERT INTO ezcontentobject_version VALUES (894,259,14,1,1069751455,1069751461,1,0,0);
INSERT INTO ezcontentobject_version VALUES (472,41,14,1,1060695450,1060695457,1,0,0);
INSERT INTO ezcontentobject_version VALUES (473,42,14,1,1066383039,1066383068,1,0,0);
INSERT INTO ezcontentobject_version VALUES (847,43,14,11,1068640411,1068640429,1,0,0);
INSERT INTO ezcontentobject_version VALUES (475,44,14,1,1066384403,1066384457,1,0,0);
INSERT INTO ezcontentobject_version VALUES (849,45,14,11,1068640482,1068640502,1,0,0);
INSERT INTO ezcontentobject_version VALUES (482,46,14,2,1066389882,1066389902,1,0,0);
INSERT INTO ezcontentobject_version VALUES (490,49,14,1,1066398007,1066398020,1,0,0);
INSERT INTO ezcontentobject_version VALUES (902,56,14,59,1069839554,1069839571,1,0,0);
INSERT INTO ezcontentobject_version VALUES (871,1,14,8,1069686109,1069686122,1,1,0);
INSERT INTO ezcontentobject_version VALUES (899,264,14,1,1069752534,1069752759,1,0,0);
INSERT INTO ezcontentobject_version VALUES (879,253,14,1,1069687934,1069687961,1,0,0);
INSERT INTO ezcontentobject_version VALUES (891,257,14,1,1069751013,1069751025,1,0,0);
INSERT INTO ezcontentobject_version VALUES (878,252,14,1,1069687890,1069687927,1,0,0);
INSERT INTO ezcontentobject_version VALUES (877,251,14,1,1069687868,1069687877,1,0,0);
INSERT INTO ezcontentobject_version VALUES (816,14,14,7,1068546819,1068556425,1,0,0);
INSERT INTO ezcontentobject_version VALUES (896,261,14,1,1069752262,1069752332,1,0,0);
INSERT INTO ezcontentobject_version VALUES (895,260,14,1,1069752059,1069752252,1,0,0);
INSERT INTO ezcontentobject_version VALUES (818,213,14,2,1068556190,1068556203,1,0,0);
INSERT INTO ezcontentobject_version VALUES (893,258,14,2,1069751102,1069751108,1,0,0);
INSERT INTO ezcontentobject_version VALUES (874,250,14,1,1069686828,1069687269,1,0,0);
INSERT INTO ezcontentobject_version VALUES (872,160,14,2,1069686675,1069686817,1,0,0);
INSERT INTO ezcontentobject_version VALUES (898,263,14,1,1069752453,1069752520,1,0,0);
INSERT INTO ezcontentobject_version VALUES (848,115,14,5,1068640455,1068640475,1,0,0);
INSERT INTO ezcontentobject_version VALUES (850,116,14,4,1068640509,1068640525,1,0,0);
INSERT INTO ezcontentobject_version VALUES (881,220,14,2,1069688144,1069688213,1,0,0);
INSERT INTO ezcontentobject_version VALUES (883,161,14,4,1069688307,1069688507,1,0,0);
INSERT INTO ezcontentobject_version VALUES (884,222,14,2,1069688558,1069688572,1,0,0);
INSERT INTO ezcontentobject_version VALUES (885,254,14,1,1069688599,1069688677,1,0,0);
INSERT INTO ezcontentobject_version VALUES (897,262,14,1,1069752348,1069752444,1,0,0);
INSERT INTO ezcontentobject_version VALUES (900,265,14,1,1069752767,1069752921,1,0,0);
INSERT INTO ezcontentobject_version VALUES (903,266,14,1,1072181226,1072181235,1,0,0);
INSERT INTO ezcontentobject_version VALUES (904,10,14,2,1072181242,1072181254,1,0,0);
INSERT INTO ezcontentobject_version VALUES (905,267,14,1,1076581391,1076581401,1,0,0);
INSERT INTO ezcontentobject_version VALUES (906,268,14,1,1076581428,1076581463,3,0,0);
INSERT INTO ezcontentobject_version VALUES (907,269,14,1,1076581499,1076577901,1,0,0);
INSERT INTO ezcontentobject_version VALUES (908,268,14,2,1076577917,1076577930,1,0,0);

--
-- Table structure for table 'ezdiscountrule'
--

CREATE TABLE ezdiscountrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezdiscountrule'
--



--
-- Table structure for table 'ezdiscountsubrule'
--

CREATE TABLE ezdiscountsubrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  discountrule_id int(11) NOT NULL default '0',
  discount_percent float default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezdiscountsubrule'
--



--
-- Table structure for table 'ezdiscountsubrule_value'
--

CREATE TABLE ezdiscountsubrule_value (
  discountsubrule_id int(11) NOT NULL default '0',
  value int(11) NOT NULL default '0',
  issection int(1) NOT NULL default '0',
  PRIMARY KEY  (discountsubrule_id,value,issection)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezdiscountsubrule_value'
--



--
-- Table structure for table 'ezenumobjectvalue'
--

CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumid int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid),
  KEY ezenumobjectvalue_co_attr_id_co_attr_ver (contentobject_attribute_id,contentobject_attribute_version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezenumobjectvalue'
--



--
-- Table structure for table 'ezenumvalue'
--

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

--
-- Dumping data for table 'ezenumvalue'
--



--
-- Table structure for table 'ezforgot_password'
--

CREATE TABLE ezforgot_password (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  hash_key varchar(32) NOT NULL default '',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezforgot_password'
--



--
-- Table structure for table 'ezgeneral_digest_user_settings'
--

CREATE TABLE ezgeneral_digest_user_settings (
  id int(11) NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  receive_digest int(11) NOT NULL default '0',
  digest_type int(11) NOT NULL default '0',
  day varchar(255) NOT NULL default '',
  time varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezgeneral_digest_user_settings'
--



--
-- Table structure for table 'ezimage'
--

CREATE TABLE ezimage (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  alternative_text varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezimage'
--


INSERT INTO ezimage VALUES (103,4,'phpWJgae7.png','kaddressbook.png','image/png','');
INSERT INTO ezimage VALUES (103,5,'php7ZhvcB.png','chardevice.png','image/png','');
INSERT INTO ezimage VALUES (109,1,'phpvzmRGW.png','folder_txt.png','image/png','');
INSERT INTO ezimage VALUES (120,11,'phpG6qloJ.gif','ezpublish_logo_blue.gif','image/gif','');
INSERT INTO ezimage VALUES (152,15,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (120,13,'phpG6qloJ.gif','ezpublish_logo_blue.gif','image/gif','');
INSERT INTO ezimage VALUES (152,12,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,13,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,11,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,16,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,7,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,18,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,9,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,10,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,14,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (152,17,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif','');
INSERT INTO ezimage VALUES (268,1,'php8lV61b.png','phphWMyJs.png','image/png','');
INSERT INTO ezimage VALUES (268,2,'php8lV61b.png','phphWMyJs.png','image/png','');
INSERT INTO ezimage VALUES (287,1,'phpjqUhJn.jpg','017_8_1small.jpg','image/jpeg','');
INSERT INTO ezimage VALUES (292,2,'phpCKfj8I.png','phpCG9Rrg_600x600_97870.png','image/png','');
INSERT INTO ezimage VALUES (293,2,'php2e1GsG.png','bj.png','image/png','');
INSERT INTO ezimage VALUES (293,3,'php2e1GsG.png','bj.png','image/png','');
INSERT INTO ezimage VALUES (103,6,'phpXz5esv.jpg','TN_a5.JPG','image/jpeg','');
INSERT INTO ezimage VALUES (109,2,'phppIJtoa.jpg','maidinmanhattantop.jpg','image/jpeg','');
INSERT INTO ezimage VALUES (103,7,'phpG0YSsD.png','gnome-settings.png','image/png','');
INSERT INTO ezimage VALUES (109,3,'phpAhcEu9.png','gnome-favorites.png','image/png','');
INSERT INTO ezimage VALUES (324,1,'php4sHmOe.png','gnome-ccperiph.png','image/png','');
INSERT INTO ezimage VALUES (109,4,'phpbVfzkm.png','gnome-devel.png','image/png','');
INSERT INTO ezimage VALUES (328,1,'php7a7vQE.png','gnome-globe.png','image/png','');
INSERT INTO ezimage VALUES (109,5,'phpvs7kFg.png','gnome-color-browser.png','image/png','');
INSERT INTO ezimage VALUES (400,2,'phprwazbD.jpg','vbanner.jpg','image/jpeg','');

--
-- Table structure for table 'ezimagefile'
--

CREATE TABLE ezimagefile (
  id int(11) NOT NULL auto_increment,
  contentobject_attribute_id int(11) NOT NULL default '0',
  filepath text NOT NULL,
  PRIMARY KEY  (id),
  KEY ezimagefile_coid (contentobject_attribute_id),
  KEY ezimagefile_file (filepath(200))
) TYPE=MyISAM;

--
-- Dumping data for table 'ezimagefile'
--


INSERT INTO ezimagefile VALUES (1,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_logo.gif');
INSERT INTO ezimagefile VALUES (2,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop.gif');
INSERT INTO ezimagefile VALUES (3,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_reference.gif');
INSERT INTO ezimagefile VALUES (4,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_medium.gif');
INSERT INTO ezimagefile VALUES (5,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_logo.gif');
INSERT INTO ezimagefile VALUES (10,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_logo.gif');
INSERT INTO ezimagefile VALUES (11,519,'var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB/news_bulletin_october.');
INSERT INTO ezimagefile VALUES (12,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop.gif');
INSERT INTO ezimagefile VALUES (13,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_reference.gif');
INSERT INTO ezimagefile VALUES (14,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_medium.gif');
INSERT INTO ezimagefile VALUES (15,943,'var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB/news_bulletin_november.');
INSERT INTO ezimagefile VALUES (31,152,'var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_logo.gif');
INSERT INTO ezimagefile VALUES (18,951,'var/shop/storage/images/products/cords/1_meter_cord/951-1-eng-GB/1_meter_cord.');
INSERT INTO ezimagefile VALUES (19,956,'var/shop/storage/images/products/cords/5_meter_cord/956-1-eng-GB/5_meter_cord.');
INSERT INTO ezimagefile VALUES (20,784,'var/shop/storage/images/privacy_notice/784-2-eng-GB/privacy_notice.');
INSERT INTO ezimagefile VALUES (21,787,'var/shop/storage/images/conditions_of_use/787-2-eng-GB/conditions_of_use.');
INSERT INTO ezimagefile VALUES (22,524,'var/shop/storage/images/shipping_and_returns/524-3-eng-GB/shipping_and_returns.');
INSERT INTO ezimagefile VALUES (23,524,'var/shop/storage/images/shipping_and_returns/524-4-eng-GB/shipping_and_returns.');
INSERT INTO ezimagefile VALUES (24,961,'var/shop/storage/images/news/a_new_cord/961-1-eng-GB/a_new_cord.');
INSERT INTO ezimagefile VALUES (28,152,'var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop.gif');
INSERT INTO ezimagefile VALUES (29,152,'var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_reference.gif');
INSERT INTO ezimagefile VALUES (30,152,'var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_medium.gif');
INSERT INTO ezimagefile VALUES (32,981,'var/shop/storage/images/products/cars/troll/981-1-eng-GB/troll.');
INSERT INTO ezimagefile VALUES (33,986,'var/shop/storage/images/products/cars/ferrari/986-1-eng-GB/ferrari.');
INSERT INTO ezimagefile VALUES (34,991,'var/shop/storage/images/products/books/summer_book/991-1-eng-GB/summer_book.');
INSERT INTO ezimagefile VALUES (35,996,'var/shop/storage/images/products/books/ez_publish_basics/996-1-eng-GB/ez_publish_basics.');
INSERT INTO ezimagefile VALUES (36,1001,'var/shop/storage/images/products/dvd/music_dvd/1001-1-eng-GB/music_dvd.');
INSERT INTO ezimagefile VALUES (37,1006,'var/shop/storage/images/products/dvd/action_dvd/1006-1-eng-GB/action_dvd.');
INSERT INTO ezimagefile VALUES (42,152,'var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop_logo.gif');
INSERT INTO ezimagefile VALUES (43,152,'var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop.gif');
INSERT INTO ezimagefile VALUES (44,152,'var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_reference.gif');
INSERT INTO ezimagefile VALUES (45,152,'var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_medium.gif');
INSERT INTO ezimagefile VALUES (46,152,'var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_logo.gif');
INSERT INTO ezimagefile VALUES (47,103,'var/storage/original/image/phpWJgae7.png');
INSERT INTO ezimagefile VALUES (48,109,'var/storage/original/image/phpbVfzkm.png');
INSERT INTO ezimagefile VALUES (49,103,'var/storage/original/image/php7ZhvcB.png');
INSERT INTO ezimagefile VALUES (50,103,'var/storage/original/image/phpXz5esv.jpg');
INSERT INTO ezimagefile VALUES (51,109,'var/storage/original/image/phppIJtoa.jpg');
INSERT INTO ezimagefile VALUES (52,109,'var/storage/original/image/phpAhcEu9.png');
INSERT INTO ezimagefile VALUES (53,1026,'var/shop/storage/images-versioned/1026/1-eng-GB/common_ini_settings1.png');
INSERT INTO ezimagefile VALUES (54,1026,'var/shop/storage/images/setup/setup_links/common_ini_settings/1026-1-eng-GB/common_ini_settings.png');
INSERT INTO ezimagefile VALUES (55,1026,'var/shop/storage/images-versioned/1026/1-eng-GB/common_ini_settings1_reference.png');
INSERT INTO ezimagefile VALUES (56,1026,'var/shop/storage/images-versioned/1026/1-eng-GB/common_ini_settings1_medium.png');
INSERT INTO ezimagefile VALUES (57,1026,'var/shop/storage/images-versioned/1026/1-eng-GB/common_ini_settings1_large.png');

--
-- Table structure for table 'ezimagevariation'
--

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

--
-- Dumping data for table 'ezimagevariation'
--


INSERT INTO ezimagevariation VALUES (103,4,'phpWJgae7_100x100_103.png','p/h/p',100,100,48,48);
INSERT INTO ezimagevariation VALUES (103,4,'phpWJgae7_600x600_103.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation VALUES (103,5,'php7ZhvcB_100x100_103.png','p/h/p',100,100,48,48);
INSERT INTO ezimagevariation VALUES (109,1,'phpvzmRGW_100x100_109.png','p/h/p',100,100,48,48);
INSERT INTO ezimagevariation VALUES (103,5,'php7ZhvcB_600x600_103.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation VALUES (109,1,'phpvzmRGW_600x600_109.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation VALUES (293,2,'php2e1GsG_600x600_293.png','p/h/p',600,600,186,93);
INSERT INTO ezimagevariation VALUES (120,11,'phpG6qloJ_100x100_120.gif.png','p/h/p',100,100,100,16);
INSERT INTO ezimagevariation VALUES (292,2,'phpCKfj8I_600x600_292.png','p/h/p',600,600,186,93);
INSERT INTO ezimagevariation VALUES (152,13,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation VALUES (293,2,'php2e1GsG_100x100_293.png','p/h/p',100,100,100,50);
INSERT INTO ezimagevariation VALUES (120,11,'phpG6qloJ_600x600_120.gif.png','p/h/p',600,600,129,21);
INSERT INTO ezimagevariation VALUES (152,12,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation VALUES (152,11,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation VALUES (292,2,'phpCKfj8I_100x100_292.png','p/h/p',100,100,100,50);
INSERT INTO ezimagevariation VALUES (287,1,'phpjqUhJn_100x100_287.jpg','p/h/p',100,100,73,100);
INSERT INTO ezimagevariation VALUES (268,2,'php8lV61b_100x100_268.png','p/h/p',100,100,100,93);
INSERT INTO ezimagevariation VALUES (268,1,'php8lV61b_150x150_268.png','p/h/p',150,150,144,134);
INSERT INTO ezimagevariation VALUES (152,16,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation VALUES (152,7,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation VALUES (268,1,'php8lV61b_100x100_268.png','p/h/p',100,100,100,93);
INSERT INTO ezimagevariation VALUES (152,9,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation VALUES (152,10,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35);
INSERT INTO ezimagevariation VALUES (293,2,'php2e1GsG_150x150_293.png','p/h/p',150,150,150,75);
INSERT INTO ezimagevariation VALUES (292,2,'phpCKfj8I_150x150_292.png','p/h/p',150,150,150,75);
INSERT INTO ezimagevariation VALUES (293,3,'php2e1GsG_100x100_293.png','p/h/p',100,100,100,50);
INSERT INTO ezimagevariation VALUES (103,6,'phpXz5esv_600x600_103.jpg','p/h/p',600,600,377,600);
INSERT INTO ezimagevariation VALUES (109,2,'phppIJtoa_600x600_109.jpg','p/h/p',600,600,116,61);
INSERT INTO ezimagevariation VALUES (103,7,'phpG0YSsD_600x600_103.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation VALUES (109,3,'phpAhcEu9_600x600_109.png','p/h/p',600,600,48,52);
INSERT INTO ezimagevariation VALUES (324,1,'php4sHmOe_600x600_324.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation VALUES (109,4,'phpbVfzkm_600x600_109.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation VALUES (328,1,'php7a7vQE_600x600_328.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation VALUES (109,5,'phpvs7kFg_600x600_109.png','p/h/p',600,600,48,48);
INSERT INTO ezimagevariation VALUES (268,2,'php8lV61b_150x150_268.png','p/h/p',150,150,144,134);
INSERT INTO ezimagevariation VALUES (103,7,'phpG0YSsD_150x150_103.png','p/h/p',150,150,48,48);
INSERT INTO ezimagevariation VALUES (109,5,'phpvs7kFg_150x150_109.png','p/h/p',150,150,48,48);
INSERT INTO ezimagevariation VALUES (324,1,'php4sHmOe_150x150_324.png','p/h/p',150,150,48,48);
INSERT INTO ezimagevariation VALUES (328,1,'php7a7vQE_150x150_328.png','p/h/p',150,150,48,48);
INSERT INTO ezimagevariation VALUES (400,2,'phprwazbD_100x100_400.jpg','p/h/p',100,100,100,33);
INSERT INTO ezimagevariation VALUES (400,2,'phprwazbD_600x600_400.jpg','p/h/p',600,600,450,150);

--
-- Table structure for table 'ezinfocollection'
--

CREATE TABLE ezinfocollection (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  user_identifier varchar(34) default NULL,
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezinfocollection'
--



--
-- Table structure for table 'ezinfocollection_attribute'
--

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

--
-- Dumping data for table 'ezinfocollection_attribute'
--



--
-- Table structure for table 'ezkeyword'
--

CREATE TABLE ezkeyword (
  id int(11) NOT NULL auto_increment,
  keyword varchar(255) default NULL,
  class_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezkeyword'
--



--
-- Table structure for table 'ezkeyword_attribute_link'
--

CREATE TABLE ezkeyword_attribute_link (
  id int(11) NOT NULL auto_increment,
  keyword_id int(11) NOT NULL default '0',
  objectattribute_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezkeyword_attribute_link'
--



--
-- Table structure for table 'ezmedia'
--

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

--
-- Dumping data for table 'ezmedia'
--



--
-- Table structure for table 'ezmessage'
--

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

--
-- Dumping data for table 'ezmessage'
--



--
-- Table structure for table 'ezmodule_run'
--

CREATE TABLE ezmodule_run (
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) default NULL,
  module_name varchar(255) default NULL,
  function_name varchar(255) default NULL,
  module_data text,
  PRIMARY KEY  (id),
  UNIQUE KEY ezmodule_run_workflow_process_id_s (workflow_process_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezmodule_run'
--



--
-- Table structure for table 'eznode_assignment'
--

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

--
-- Dumping data for table 'eznode_assignment'
--


INSERT INTO eznode_assignment VALUES (4,8,2,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (144,4,1,1,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (602,262,1,183,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (601,261,1,184,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (148,9,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (150,11,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (151,12,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (152,13,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (583,219,2,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (182,41,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (183,42,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (550,43,11,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (185,44,1,44,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (552,45,11,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (193,46,2,44,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (201,49,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (607,56,59,48,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (574,1,8,1,8,1,1,0,0);
INSERT INTO eznode_assignment VALUES (604,264,1,185,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (551,115,5,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (581,252,1,177,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (553,116,4,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (596,257,1,154,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (580,251,1,154,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (598,258,2,154,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (605,265,1,185,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (524,213,2,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (522,14,7,13,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (577,250,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (575,160,2,50,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (582,253,1,177,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (600,260,1,184,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (603,263,1,183,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (584,220,2,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (586,161,4,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (587,222,2,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (588,254,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (599,259,1,154,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (608,266,1,5,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (610,10,2,192,9,1,1,5,0);
INSERT INTO eznode_assignment VALUES (611,267,1,44,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (612,268,1,193,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (613,269,1,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (614,268,2,193,9,1,1,0,0);

--
-- Table structure for table 'eznotificationcollection'
--

CREATE TABLE eznotificationcollection (
  id int(11) NOT NULL auto_increment,
  event_id int(11) NOT NULL default '0',
  handler varchar(255) NOT NULL default '',
  transport varchar(255) NOT NULL default '',
  data_subject text NOT NULL,
  data_text text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'eznotificationcollection'
--



--
-- Table structure for table 'eznotificationcollection_item'
--

CREATE TABLE eznotificationcollection_item (
  id int(11) NOT NULL auto_increment,
  collection_id int(11) NOT NULL default '0',
  event_id int(11) NOT NULL default '0',
  address varchar(255) NOT NULL default '',
  send_date int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'eznotificationcollection_item'
--



--
-- Table structure for table 'eznotificationevent'
--

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

--
-- Dumping data for table 'eznotificationevent'
--


INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',267,1,0,0,'','','','');
INSERT INTO eznotificationevent VALUES (2,0,'ezpublish',268,1,0,0,'','','','');
INSERT INTO eznotificationevent VALUES (3,0,'ezpublish',269,1,0,0,'','','','');
INSERT INTO eznotificationevent VALUES (4,0,'ezpublish',268,2,0,0,'','','','');

--
-- Table structure for table 'ezoperation_memento'
--

CREATE TABLE ezoperation_memento (
  id int(11) NOT NULL auto_increment,
  memento_key varchar(32) NOT NULL default '',
  memento_data text NOT NULL,
  main int(11) NOT NULL default '0',
  main_key varchar(32) NOT NULL default '',
  PRIMARY KEY  (id,memento_key),
  KEY ezoperation_memento_memento_key_main (memento_key,main)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezoperation_memento'
--



--
-- Table structure for table 'ezorder'
--

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

--
-- Dumping data for table 'ezorder'
--



--
-- Table structure for table 'ezorder_item'
--

CREATE TABLE ezorder_item (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  description varchar(255) default NULL,
  price float default NULL,
  vat_value int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezorder_item_order_id (order_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezorder_item'
--



--
-- Table structure for table 'ezpdf_export'
--

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

--
-- Dumping data for table 'ezpdf_export'
--



--
-- Table structure for table 'ezpolicy'
--

CREATE TABLE ezpolicy (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy'
--


INSERT INTO ezpolicy VALUES (308,2,'*','*','*');
INSERT INTO ezpolicy VALUES (341,8,'read','content','*');
INSERT INTO ezpolicy VALUES (381,1,'login','user','*');
INSERT INTO ezpolicy VALUES (382,1,'read','content','');
INSERT INTO ezpolicy VALUES (383,1,'buy','shop','*');

--
-- Table structure for table 'ezpolicy_limitation'
--

CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  policy_id int(11) default NULL,
  identifier varchar(255) NOT NULL default '',
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy_limitation'
--


INSERT INTO ezpolicy_limitation VALUES (301,382,'Class',0,'read','content');

--
-- Table structure for table 'ezpolicy_limitation_value'
--

CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy_limitation_value'
--


INSERT INTO ezpolicy_limitation_value VALUES (608,301,'5');
INSERT INTO ezpolicy_limitation_value VALUES (607,301,'25');
INSERT INTO ezpolicy_limitation_value VALUES (606,301,'24');
INSERT INTO ezpolicy_limitation_value VALUES (605,301,'23');
INSERT INTO ezpolicy_limitation_value VALUES (604,301,'2');
INSERT INTO ezpolicy_limitation_value VALUES (603,301,'12');
INSERT INTO ezpolicy_limitation_value VALUES (602,301,'10');
INSERT INTO ezpolicy_limitation_value VALUES (601,301,'1');

--
-- Table structure for table 'ezpreferences'
--

CREATE TABLE ezpreferences (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  name varchar(100) default NULL,
  value varchar(100) default NULL,
  PRIMARY KEY  (id),
  KEY ezpreferences_name (name)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpreferences'
--


INSERT INTO ezpreferences VALUES (1,14,'advanced_menu','on');

--
-- Table structure for table 'ezproductcollection'
--

CREATE TABLE ezproductcollection (
  id int(11) NOT NULL auto_increment,
  created int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezproductcollection'
--



--
-- Table structure for table 'ezproductcollection_item'
--

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

--
-- Dumping data for table 'ezproductcollection_item'
--



--
-- Table structure for table 'ezproductcollection_item_opt'
--

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

--
-- Dumping data for table 'ezproductcollection_item_opt'
--



--
-- Table structure for table 'ezrole'
--

CREATE TABLE ezrole (
  id int(11) NOT NULL auto_increment,
  version int(11) default '0',
  name varchar(255) NOT NULL default '',
  value char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezrole'
--


INSERT INTO ezrole VALUES (1,0,'Anonymous','');
INSERT INTO ezrole VALUES (2,0,'Administrator','*');
INSERT INTO ezrole VALUES (8,0,'Guest',NULL);

--
-- Table structure for table 'ezrss_export'
--

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

--
-- Dumping data for table 'ezrss_export'
--



--
-- Table structure for table 'ezrss_export_item'
--

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

--
-- Dumping data for table 'ezrss_export_item'
--



--
-- Table structure for table 'ezrss_import'
--

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

--
-- Dumping data for table 'ezrss_import'
--



--
-- Table structure for table 'ezsearch_object_word_link'
--

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

--
-- Dumping data for table 'ezsearch_object_word_link'
--


INSERT INTO ezsearch_object_word_link VALUES (5145,161,1140,0,0,0,1141,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5146,161,1141,0,1,1140,1142,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5147,161,1142,0,2,1141,1140,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5148,161,1140,0,3,1142,1141,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5149,161,1141,0,4,1140,1142,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5150,161,1142,0,5,1141,1140,10,1068047603,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5151,161,1140,0,6,1142,1141,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5152,161,1141,0,7,1140,1142,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5153,161,1142,0,8,1141,1143,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5154,161,1143,0,9,1142,1144,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5155,161,1144,0,10,1143,1145,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5156,161,1145,0,11,1144,1146,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5157,161,1146,0,12,1145,1147,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5158,161,1147,0,13,1146,1148,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5159,161,1148,0,14,1147,1149,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5160,161,1149,0,15,1148,1150,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5161,161,1150,0,16,1149,1151,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5162,161,1151,0,17,1150,1152,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5163,161,1152,0,18,1151,1153,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5164,161,1153,0,19,1152,1154,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5165,161,1154,0,20,1153,1155,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5166,161,1155,0,21,1154,1156,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5167,161,1156,0,22,1155,1143,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5168,161,1143,0,23,1156,1157,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5169,161,1157,0,24,1143,1158,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5170,161,1158,0,25,1157,1159,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5171,161,1159,0,26,1158,1160,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5172,161,1160,0,27,1159,1161,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5173,161,1161,0,28,1160,1162,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5174,161,1162,0,29,1161,1163,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5175,161,1163,0,30,1162,1164,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5176,161,1164,0,31,1163,1161,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5177,161,1161,0,32,1164,1165,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5178,161,1165,0,33,1161,1166,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5179,161,1166,0,34,1165,1167,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5180,161,1167,0,35,1166,1166,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5181,161,1166,0,36,1167,1168,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5182,161,1168,0,37,1166,1169,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5183,161,1169,0,38,1168,1166,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5184,161,1166,0,39,1169,1170,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5185,161,1170,0,40,1166,1146,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5186,161,1146,0,41,1170,1171,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5187,161,1171,0,42,1146,1172,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5188,161,1172,0,43,1171,1173,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5189,161,1173,0,44,1172,1159,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5190,161,1159,0,45,1173,1174,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5191,161,1174,0,46,1159,1169,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5192,161,1169,0,47,1174,1166,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5193,161,1166,0,48,1169,1175,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5194,161,1175,0,49,1166,1171,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5195,161,1171,0,50,1175,1176,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5196,161,1176,0,51,1171,1177,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5197,161,1177,0,52,1176,1162,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5198,161,1162,0,53,1177,1161,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5199,161,1161,0,54,1162,1178,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5200,161,1178,0,55,1161,1179,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5201,161,1179,0,56,1178,1162,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5202,161,1162,0,57,1179,1143,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5203,161,1143,0,58,1162,1180,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5204,161,1180,0,59,1143,1181,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5205,161,1181,0,60,1180,1171,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5206,161,1171,0,61,1181,1182,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5207,161,1182,0,62,1171,1183,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5208,161,1183,0,63,1182,1167,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5209,161,1167,0,64,1183,1184,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5210,161,1184,0,65,1167,1185,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5211,161,1185,0,66,1184,1186,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5212,161,1186,0,67,1185,1187,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5213,161,1187,0,68,1186,1188,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5214,161,1188,0,69,1187,1189,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5215,161,1189,0,70,1188,1190,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5216,161,1190,0,71,1189,1191,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5217,161,1191,0,72,1190,1192,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5218,161,1192,0,73,1191,1193,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5219,161,1193,0,74,1192,1194,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5220,161,1194,0,75,1193,1195,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5221,161,1195,0,76,1194,1196,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5222,161,1196,0,77,1195,1197,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5223,161,1197,0,78,1196,1198,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5224,161,1198,0,79,1197,1199,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5225,161,1199,0,80,1198,1200,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5226,161,1200,0,81,1199,1147,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5227,161,1147,0,82,1200,1201,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5228,161,1201,0,83,1147,1198,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5229,161,1198,0,84,1201,1199,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5230,161,1199,0,85,1198,1200,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5231,161,1200,0,86,1199,1202,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5232,161,1202,0,87,1200,1203,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5233,161,1203,0,88,1202,1197,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5234,161,1197,0,89,1203,1204,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5235,161,1204,0,90,1197,1140,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5236,161,1140,0,91,1204,1141,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5237,161,1141,0,92,1140,1142,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5238,161,1142,0,93,1141,1143,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5239,161,1143,0,94,1142,1144,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5240,161,1144,0,95,1143,1145,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5241,161,1145,0,96,1144,1146,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5242,161,1146,0,97,1145,1147,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5243,161,1147,0,98,1146,1148,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5244,161,1148,0,99,1147,1149,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5245,161,1149,0,100,1148,1150,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5246,161,1150,0,101,1149,1151,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5247,161,1151,0,102,1150,1152,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5248,161,1152,0,103,1151,1153,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5249,161,1153,0,104,1152,1154,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5250,161,1154,0,105,1153,1155,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5251,161,1155,0,106,1154,1156,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5252,161,1156,0,107,1155,1143,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5253,161,1143,0,108,1156,1157,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5254,161,1157,0,109,1143,1158,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5255,161,1158,0,110,1157,1159,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5256,161,1159,0,111,1158,1160,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5257,161,1160,0,112,1159,1161,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5258,161,1161,0,113,1160,1162,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5259,161,1162,0,114,1161,1163,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5260,161,1163,0,115,1162,1164,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5261,161,1164,0,116,1163,1161,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5262,161,1161,0,117,1164,1165,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5263,161,1165,0,118,1161,1166,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5264,161,1166,0,119,1165,1167,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5265,161,1167,0,120,1166,1166,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5266,161,1166,0,121,1167,1168,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5267,161,1168,0,122,1166,1169,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5268,161,1169,0,123,1168,1166,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5269,161,1166,0,124,1169,1170,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5270,161,1170,0,125,1166,1146,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5271,161,1146,0,126,1170,1171,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5272,161,1171,0,127,1146,1172,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5273,161,1172,0,128,1171,1173,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5274,161,1173,0,129,1172,1159,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5275,161,1159,0,130,1173,1174,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5276,161,1174,0,131,1159,1169,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5277,161,1169,0,132,1174,1166,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5278,161,1166,0,133,1169,1175,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5279,161,1175,0,134,1166,1171,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5280,161,1171,0,135,1175,1176,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5281,161,1176,0,136,1171,1177,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5282,161,1177,0,137,1176,1162,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5283,161,1162,0,138,1177,1161,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5284,161,1161,0,139,1162,1178,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5285,161,1178,0,140,1161,1179,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5286,161,1179,0,141,1178,1162,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5287,161,1162,0,142,1179,1143,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5288,161,1143,0,143,1162,1180,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5289,161,1180,0,144,1143,1181,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5290,161,1181,0,145,1180,1171,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5291,161,1171,0,146,1181,1182,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5292,161,1182,0,147,1171,1183,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5293,161,1183,0,148,1182,1167,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5294,161,1167,0,149,1183,1184,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5295,161,1184,0,150,1167,1185,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5296,161,1185,0,151,1184,1186,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5297,161,1186,0,152,1185,1187,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5298,161,1187,0,153,1186,1188,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5299,161,1188,0,154,1187,1189,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5300,161,1189,0,155,1188,1190,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5301,161,1190,0,156,1189,1191,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5302,161,1191,0,157,1190,1192,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5303,161,1192,0,158,1191,1193,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5304,161,1193,0,159,1192,1194,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5305,161,1194,0,160,1193,1195,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5306,161,1195,0,161,1194,1196,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5307,161,1196,0,162,1195,1197,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5308,161,1197,0,163,1196,1198,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5309,161,1198,0,164,1197,1199,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5310,161,1199,0,165,1198,1200,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5311,161,1200,0,166,1199,1147,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5312,161,1147,0,167,1200,1201,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5313,161,1201,0,168,1147,1198,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5314,161,1198,0,169,1201,1199,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5315,161,1199,0,170,1198,1200,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5316,161,1200,0,171,1199,1202,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5317,161,1202,0,172,1200,1203,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5318,161,1203,0,173,1202,1197,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5319,161,1197,0,174,1203,1204,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5320,161,1204,0,175,1197,0,10,1068047603,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5321,213,1160,0,0,0,1160,1,1068473231,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5322,213,1160,0,1,1160,1205,1,1068473231,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5323,213,1205,0,2,1160,1160,1,1068473231,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5324,213,1160,0,3,1205,1205,1,1068473231,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5325,213,1205,0,4,1160,1160,1,1068473231,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5326,213,1160,0,5,1205,0,1,1068473231,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5327,251,1206,0,0,0,1206,1,1069687877,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5328,251,1206,0,1,1206,0,1,1069687877,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5329,252,1207,0,0,0,1208,23,1069687927,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5330,252,1208,0,1,1207,1209,23,1069687927,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5331,252,1209,0,2,1208,1207,23,1069687927,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5332,252,1207,0,3,1209,1208,23,1069687927,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5333,252,1208,0,4,1207,1209,23,1069687927,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5334,252,1209,0,5,1208,1210,23,1069687927,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5335,252,1210,0,6,1209,1210,23,1069687927,1,202,'',13444);
INSERT INTO ezsearch_object_word_link VALUES (5336,252,1210,0,7,1210,1167,23,1069687927,1,202,'',13444);
INSERT INTO ezsearch_object_word_link VALUES (5337,252,1167,0,8,1210,1209,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5338,252,1209,0,9,1167,1166,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5339,252,1166,0,10,1209,1145,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5340,252,1145,0,11,1166,1208,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5341,252,1208,0,12,1145,1211,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5342,252,1211,0,13,1208,1141,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5343,252,1141,0,14,1211,1212,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5344,252,1212,0,15,1141,1177,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5345,252,1177,0,16,1212,1213,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5346,252,1213,0,17,1177,1214,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5347,252,1214,0,18,1213,1167,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5348,252,1167,0,19,1214,1209,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5349,252,1209,0,20,1167,1166,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5350,252,1166,0,21,1209,1145,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5351,252,1145,0,22,1166,1208,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5352,252,1208,0,23,1145,1211,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5353,252,1211,0,24,1208,1141,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5354,252,1141,0,25,1211,1212,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5355,252,1212,0,26,1141,1177,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5356,252,1177,0,27,1212,1213,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5357,252,1213,0,28,1177,1214,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5358,252,1214,0,29,1213,0,23,1069687927,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5359,253,1215,0,0,0,1208,23,1069687961,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5360,253,1208,0,1,1215,1209,23,1069687961,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5361,253,1209,0,2,1208,1215,23,1069687961,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5362,253,1215,0,3,1209,1208,23,1069687961,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5363,253,1208,0,4,1215,1209,23,1069687961,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5364,253,1209,0,5,1208,1216,23,1069687961,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5365,253,1216,0,6,1209,1216,23,1069687961,1,202,'',34555);
INSERT INTO ezsearch_object_word_link VALUES (5366,253,1216,0,7,1216,1167,23,1069687961,1,202,'',34555);
INSERT INTO ezsearch_object_word_link VALUES (5367,253,1167,0,8,1216,1209,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5368,253,1209,0,9,1167,1166,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5369,253,1166,0,10,1209,1217,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5370,253,1217,0,11,1166,1218,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5371,253,1218,0,12,1217,1211,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5372,253,1211,0,13,1218,1141,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5373,253,1141,0,14,1211,1212,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5374,253,1212,0,15,1141,1177,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5375,253,1177,0,16,1212,1213,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5376,253,1213,0,17,1177,1214,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5377,253,1214,0,18,1213,1167,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5378,253,1167,0,19,1214,1209,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5379,253,1209,0,20,1167,1166,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5380,253,1166,0,21,1209,1217,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5381,253,1217,0,22,1166,1218,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5382,253,1218,0,23,1217,1211,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5383,253,1211,0,24,1218,1141,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5384,253,1141,0,25,1211,1212,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5385,253,1212,0,26,1141,1177,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5386,253,1177,0,27,1212,1213,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5387,253,1213,0,28,1177,1214,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5388,253,1214,0,29,1213,0,23,1069687961,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5389,257,1219,0,0,0,1219,1,1069751025,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5390,257,1219,0,1,1219,0,1,1069751025,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5391,262,1220,0,0,0,1221,23,1069752445,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5392,262,1221,0,1,1220,1220,23,1069752445,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5393,262,1220,0,2,1221,1221,23,1069752445,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5394,262,1221,0,3,1220,1222,23,1069752445,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5395,262,1222,0,4,1221,1222,23,1069752445,1,202,'',1324);
INSERT INTO ezsearch_object_word_link VALUES (5396,262,1222,0,5,1222,1147,23,1069752445,1,202,'',1324);
INSERT INTO ezsearch_object_word_link VALUES (5397,262,1147,0,6,1222,1221,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5398,262,1221,0,7,1147,1166,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5399,262,1166,0,8,1221,1186,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5400,262,1186,0,9,1166,1213,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5401,262,1213,0,10,1186,1147,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5402,262,1147,0,11,1213,1223,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5403,262,1223,0,12,1147,1141,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5404,262,1141,0,13,1223,1224,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5405,262,1224,0,14,1141,1146,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5406,262,1146,0,15,1224,1220,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5407,262,1220,0,16,1146,1147,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5408,262,1147,0,17,1220,1221,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5409,262,1221,0,18,1147,1166,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5410,262,1166,0,19,1221,1225,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5411,262,1225,0,20,1166,1226,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5412,262,1226,0,21,1225,1227,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5413,262,1227,0,22,1226,1146,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5414,262,1146,0,23,1227,1147,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5415,262,1147,0,24,1146,1228,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5416,262,1228,0,25,1147,1229,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5417,262,1229,0,26,1228,1151,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5418,262,1151,0,27,1229,1230,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5419,262,1230,0,28,1151,1147,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5420,262,1147,0,29,1230,1221,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5421,262,1221,0,30,1147,1166,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5422,262,1166,0,31,1221,1186,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5423,262,1186,0,32,1166,1213,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5424,262,1213,0,33,1186,1147,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5425,262,1147,0,34,1213,1223,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5426,262,1223,0,35,1147,1141,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5427,262,1141,0,36,1223,1224,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5428,262,1224,0,37,1141,1146,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5429,262,1146,0,38,1224,1220,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5430,262,1220,0,39,1146,1147,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5431,262,1147,0,40,1220,1221,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5432,262,1221,0,41,1147,1166,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5433,262,1166,0,42,1221,1225,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5434,262,1225,0,43,1166,1226,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5435,262,1226,0,44,1225,1227,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5436,262,1227,0,45,1226,1146,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5437,262,1146,0,46,1227,1147,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5438,262,1147,0,47,1146,1228,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5439,262,1228,0,48,1147,1229,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5440,262,1229,0,49,1228,1151,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5441,262,1151,0,50,1229,1230,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5442,262,1230,0,51,1151,0,23,1069752445,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5443,263,1231,0,0,0,1232,23,1069752520,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5444,263,1232,0,1,1231,1233,23,1069752520,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5445,263,1233,0,2,1232,1231,23,1069752520,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5446,263,1231,0,3,1233,1232,23,1069752520,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5447,263,1232,0,4,1231,1233,23,1069752520,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5448,263,1233,0,5,1232,1234,23,1069752520,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5449,263,1234,0,6,1233,1234,23,1069752520,1,202,'',123414);
INSERT INTO ezsearch_object_word_link VALUES (5450,263,1234,0,7,1234,1235,23,1069752520,1,202,'',123414);
INSERT INTO ezsearch_object_word_link VALUES (5451,263,1235,0,8,1234,1162,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5452,263,1162,0,9,1235,1236,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5453,263,1236,0,10,1162,1161,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5454,263,1161,0,11,1236,1165,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5455,263,1165,0,12,1161,1186,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5456,263,1186,0,13,1165,1231,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5457,263,1231,0,14,1186,1232,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5458,263,1232,0,15,1231,1213,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5459,263,1213,0,16,1232,1237,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5460,263,1237,0,17,1213,1238,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5461,263,1238,0,18,1237,1239,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5462,263,1239,0,19,1238,1161,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5463,263,1161,0,20,1239,1147,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5464,263,1147,0,21,1161,1240,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5465,263,1240,0,22,1147,1241,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5466,263,1241,0,23,1240,1235,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5467,263,1235,0,24,1241,1162,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5468,263,1162,0,25,1235,1236,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5469,263,1236,0,26,1162,1161,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5470,263,1161,0,27,1236,1165,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5471,263,1165,0,28,1161,1186,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5472,263,1186,0,29,1165,1231,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5473,263,1231,0,30,1186,1232,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5474,263,1232,0,31,1231,1213,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5475,263,1213,0,32,1232,1237,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5476,263,1237,0,33,1213,1238,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5477,263,1238,0,34,1237,1239,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5478,263,1239,0,35,1238,1161,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5479,263,1161,0,36,1239,1147,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5480,263,1147,0,37,1161,1240,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5481,263,1240,0,38,1147,1241,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5482,263,1241,0,39,1240,0,23,1069752520,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5483,258,1242,0,0,0,1242,1,1069751059,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5484,258,1242,0,1,1242,0,1,1069751059,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5485,260,1243,0,0,0,1243,23,1069752252,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5486,260,1243,0,1,1243,1243,23,1069752252,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5487,260,1243,0,2,1243,1244,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5488,260,1244,0,3,1243,1147,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5489,260,1147,0,4,1244,1245,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5490,260,1245,0,5,1147,1141,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5491,260,1141,0,6,1245,1246,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5492,260,1246,0,7,1141,1247,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5493,260,1247,0,8,1246,1147,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5494,260,1147,0,9,1247,1248,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5495,260,1248,0,10,1147,1249,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5496,260,1249,0,11,1248,1250,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5497,260,1250,0,12,1249,1151,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5498,260,1151,0,13,1250,1230,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5499,260,1230,0,14,1151,1248,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5500,260,1248,0,15,1230,1217,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5501,260,1217,0,16,1248,1242,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5502,260,1242,0,17,1217,1251,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5503,260,1251,0,18,1242,1147,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5504,260,1147,0,19,1251,1252,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5505,260,1252,0,20,1147,1151,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5506,260,1151,0,21,1252,1253,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5507,260,1253,0,22,1151,1243,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5508,260,1243,0,23,1253,1244,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5509,260,1244,0,24,1243,1147,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5510,260,1147,0,25,1244,1245,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5511,260,1245,0,26,1147,1141,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5512,260,1141,0,27,1245,1246,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5513,260,1246,0,28,1141,1247,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5514,260,1247,0,29,1246,1147,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5515,260,1147,0,30,1247,1248,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5516,260,1248,0,31,1147,1249,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5517,260,1249,0,32,1248,1250,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5518,260,1250,0,33,1249,1151,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5519,260,1151,0,34,1250,1230,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5520,260,1230,0,35,1151,1248,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5521,260,1248,0,36,1230,1217,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5522,260,1217,0,37,1248,1242,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5523,260,1242,0,38,1217,1251,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5524,260,1251,0,39,1242,1147,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5525,260,1147,0,40,1251,1252,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5526,260,1252,0,41,1147,1151,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5527,260,1151,0,42,1252,1253,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5528,260,1253,0,43,1151,0,23,1069752252,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5529,261,1254,0,0,0,1254,23,1069752332,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5530,261,1254,0,1,1254,1255,23,1069752332,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5531,261,1255,0,2,1254,1147,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5532,261,1147,0,3,1255,1256,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5533,261,1256,0,4,1147,1169,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5534,261,1169,0,5,1256,1257,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5535,261,1257,0,6,1169,1258,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5536,261,1258,0,7,1257,1259,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5537,261,1259,0,8,1258,1161,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5538,261,1161,0,9,1259,1260,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5539,261,1260,0,10,1161,1155,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5540,261,1155,0,11,1260,1162,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5541,261,1162,0,12,1155,1261,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5542,261,1261,0,13,1162,1262,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5543,261,1262,0,14,1261,1263,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5544,261,1263,0,15,1262,1145,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5545,261,1145,0,16,1263,1162,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5546,261,1162,0,17,1145,1264,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5547,261,1264,0,18,1162,1164,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5548,261,1164,0,19,1264,1161,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5549,261,1161,0,20,1164,1265,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5550,261,1265,0,21,1161,1141,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5551,261,1141,0,22,1265,1162,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5552,261,1162,0,23,1141,1266,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5553,261,1266,0,24,1162,1171,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5554,261,1171,0,25,1266,1267,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5555,261,1267,0,26,1171,1268,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5556,261,1268,0,27,1267,1255,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5557,261,1255,0,28,1268,1147,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5558,261,1147,0,29,1255,1256,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5559,261,1256,0,30,1147,1169,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5560,261,1169,0,31,1256,1257,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5561,261,1257,0,32,1169,1258,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5562,261,1258,0,33,1257,1259,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5563,261,1259,0,34,1258,1161,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5564,261,1161,0,35,1259,1260,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5565,261,1260,0,36,1161,1155,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5566,261,1155,0,37,1260,1162,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5567,261,1162,0,38,1155,1261,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5568,261,1261,0,39,1162,1262,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5569,261,1262,0,40,1261,1263,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5570,261,1263,0,41,1262,1145,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5571,261,1145,0,42,1263,1162,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5572,261,1162,0,43,1145,1264,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5573,261,1264,0,44,1162,1164,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5574,261,1164,0,45,1264,1161,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5575,261,1161,0,46,1164,1265,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5576,261,1265,0,47,1161,1141,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5577,261,1141,0,48,1265,1162,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5578,261,1162,0,49,1141,1266,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5579,261,1266,0,50,1162,1171,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5580,261,1171,0,51,1266,1267,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5581,261,1267,0,52,1171,1268,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5582,261,1268,0,53,1267,0,23,1069752332,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5583,259,1269,0,0,0,1269,1,1069751462,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5584,259,1269,0,1,1269,0,1,1069751462,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5585,264,1270,0,0,0,1269,23,1069752759,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5586,264,1269,0,1,1270,1270,23,1069752759,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5587,264,1270,0,2,1269,1269,23,1069752759,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5588,264,1269,0,3,1270,1271,23,1069752759,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5589,264,1271,0,4,1269,1271,23,1069752759,1,202,'',60897);
INSERT INTO ezsearch_object_word_link VALUES (5590,264,1271,0,5,1271,1171,23,1069752759,1,202,'',60897);
INSERT INTO ezsearch_object_word_link VALUES (5591,264,1171,0,6,1271,1272,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5592,264,1272,0,7,1171,1146,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5593,264,1146,0,8,1272,1270,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5594,264,1270,0,9,1146,1238,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5595,264,1238,0,10,1270,1147,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5596,264,1147,0,11,1238,1273,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5597,264,1273,0,12,1147,1274,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5598,264,1274,0,13,1273,1147,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5599,264,1147,0,14,1274,1275,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5600,264,1275,0,15,1147,1146,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5601,264,1146,0,16,1275,1147,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5602,264,1147,0,17,1146,1275,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5603,264,1275,0,18,1147,1213,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5604,264,1213,0,19,1275,1276,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5605,264,1276,0,20,1213,1146,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5606,264,1146,0,21,1276,1147,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5607,264,1147,0,22,1146,1277,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5608,264,1277,0,23,1147,1238,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5609,264,1238,0,24,1277,1276,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5610,264,1276,0,25,1238,1278,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5611,264,1278,0,26,1276,1171,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5612,264,1171,0,27,1278,1272,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5613,264,1272,0,28,1171,1146,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5614,264,1146,0,29,1272,1270,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5615,264,1270,0,30,1146,1238,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5616,264,1238,0,31,1270,1147,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5617,264,1147,0,32,1238,1273,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5618,264,1273,0,33,1147,1274,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5619,264,1274,0,34,1273,1147,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5620,264,1147,0,35,1274,1275,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5621,264,1275,0,36,1147,1146,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5622,264,1146,0,37,1275,1147,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5623,264,1147,0,38,1146,1275,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5624,264,1275,0,39,1147,1213,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5625,264,1213,0,40,1275,1276,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5626,264,1276,0,41,1213,1146,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5627,264,1146,0,42,1276,1147,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5628,264,1147,0,43,1146,1277,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5629,264,1277,0,44,1147,1238,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5630,264,1238,0,45,1277,1276,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5631,264,1276,0,46,1238,1278,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5632,264,1278,0,47,1276,0,23,1069752759,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5633,265,1279,0,0,0,1269,23,1069752921,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5634,265,1269,0,1,1279,1279,23,1069752921,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5635,265,1279,0,2,1269,1269,23,1069752921,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5636,265,1269,0,3,1279,1280,23,1069752921,1,201,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5637,265,1280,0,4,1269,1238,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5638,265,1238,0,5,1280,1147,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5639,265,1147,0,6,1238,1275,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5640,265,1275,0,7,1147,1279,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5641,265,1279,0,8,1275,1281,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5642,265,1281,0,9,1279,1238,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5643,265,1238,0,10,1281,1147,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5644,265,1147,0,11,1238,1282,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5645,265,1282,0,12,1147,1283,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5646,265,1283,0,13,1282,1238,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5647,265,1238,0,14,1283,1284,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5648,265,1284,0,15,1238,1285,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5649,265,1285,0,16,1284,1286,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5650,265,1286,0,17,1285,1146,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5651,265,1146,0,18,1286,1287,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5652,265,1287,0,19,1146,1288,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5653,265,1288,0,20,1287,1279,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5654,265,1279,0,21,1288,1238,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5655,265,1238,0,22,1279,1289,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5656,265,1289,0,23,1238,1161,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5657,265,1161,0,24,1289,1289,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5658,265,1289,0,25,1161,1280,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5659,265,1280,0,26,1289,1238,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5660,265,1238,0,27,1280,1147,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5661,265,1147,0,28,1238,1275,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5662,265,1275,0,29,1147,1279,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5663,265,1279,0,30,1275,1281,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5664,265,1281,0,31,1279,1238,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5665,265,1238,0,32,1281,1147,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5666,265,1147,0,33,1238,1282,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5667,265,1282,0,34,1147,1283,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5668,265,1283,0,35,1282,1238,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5669,265,1238,0,36,1283,1284,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5670,265,1284,0,37,1238,1285,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5671,265,1285,0,38,1284,1286,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5672,265,1286,0,39,1285,1146,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5673,265,1146,0,40,1286,1287,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5674,265,1287,0,41,1146,1288,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5675,265,1288,0,42,1287,1279,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5676,265,1279,0,43,1288,1238,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5677,265,1238,0,44,1279,1289,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5678,265,1289,0,45,1238,1161,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5679,265,1161,0,46,1289,1289,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5680,265,1289,0,47,1161,0,23,1069752921,1,203,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5681,219,1290,0,0,0,1291,10,1068542692,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5682,219,1291,0,1,1290,1290,10,1068542692,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5683,219,1290,0,2,1291,1291,10,1068542692,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5684,219,1291,0,3,1290,1151,10,1068542692,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5685,219,1151,0,4,1291,1147,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5686,219,1147,0,5,1151,1290,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5687,219,1290,0,6,1147,1291,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5688,219,1291,0,7,1290,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5689,219,1162,0,8,1291,1292,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5690,219,1292,0,9,1162,1293,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5691,219,1293,0,10,1292,1186,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5692,219,1186,0,11,1293,1294,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5693,219,1294,0,12,1186,1295,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5694,219,1295,0,13,1294,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5695,219,1162,0,14,1295,1296,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5696,219,1296,0,15,1162,1185,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5697,219,1185,0,16,1296,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5698,219,1162,0,17,1185,1297,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5699,219,1297,0,18,1162,1238,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5700,219,1238,0,19,1297,1152,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5701,219,1152,0,20,1238,1298,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5702,219,1298,0,21,1152,1299,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5703,219,1299,0,22,1298,1300,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5704,219,1300,0,23,1299,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5705,219,1162,0,24,1300,1300,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5706,219,1300,0,25,1162,1226,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5707,219,1226,0,26,1300,1169,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5708,219,1169,0,27,1226,1141,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5709,219,1141,0,28,1169,1299,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5710,219,1299,0,29,1141,1300,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5711,219,1300,0,30,1299,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5712,219,1162,0,31,1300,1157,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5713,219,1157,0,32,1162,1301,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5714,219,1301,0,33,1157,1169,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5715,219,1169,0,34,1301,1177,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5716,219,1177,0,35,1169,1181,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5717,219,1181,0,36,1177,1156,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5718,219,1156,0,37,1181,1143,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5719,219,1143,0,38,1156,1302,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5720,219,1302,0,39,1143,1303,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5721,219,1303,0,40,1302,1151,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5722,219,1151,0,41,1303,1304,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5723,219,1304,0,42,1151,1186,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5724,219,1186,0,43,1304,1167,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5725,219,1167,0,44,1186,1141,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5726,219,1141,0,45,1167,1169,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5727,219,1169,0,46,1141,1166,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5728,219,1166,0,47,1169,1305,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5729,219,1305,0,48,1166,1302,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5730,219,1302,0,49,1305,1149,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5731,219,1149,0,50,1302,1179,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5732,219,1179,0,51,1149,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5733,219,1162,0,52,1179,1306,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5734,219,1306,0,53,1162,1167,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5735,219,1167,0,54,1306,1307,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5736,219,1307,0,55,1167,1308,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5737,219,1308,0,56,1307,1307,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5738,219,1307,0,57,1308,1168,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5739,219,1168,0,58,1307,1169,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5740,219,1169,0,59,1168,1309,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5741,219,1309,0,60,1169,1310,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5742,219,1310,0,61,1309,1147,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5743,219,1147,0,62,1310,1311,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5744,219,1311,0,63,1147,1195,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5745,219,1195,0,64,1311,1312,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5746,219,1312,0,65,1195,1146,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5747,219,1146,0,66,1312,1152,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5748,219,1152,0,67,1146,1153,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5749,219,1153,0,68,1152,1151,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5750,219,1151,0,69,1153,1147,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5751,219,1147,0,70,1151,1290,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5752,219,1290,0,71,1147,1291,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5753,219,1291,0,72,1290,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5754,219,1162,0,73,1291,1292,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5755,219,1292,0,74,1162,1293,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5756,219,1293,0,75,1292,1186,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5757,219,1186,0,76,1293,1294,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5758,219,1294,0,77,1186,1295,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5759,219,1295,0,78,1294,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5760,219,1162,0,79,1295,1296,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5761,219,1296,0,80,1162,1185,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5762,219,1185,0,81,1296,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5763,219,1162,0,82,1185,1297,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5764,219,1297,0,83,1162,1238,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5765,219,1238,0,84,1297,1152,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5766,219,1152,0,85,1238,1298,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5767,219,1298,0,86,1152,1299,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5768,219,1299,0,87,1298,1300,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5769,219,1300,0,88,1299,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5770,219,1162,0,89,1300,1300,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5771,219,1300,0,90,1162,1226,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5772,219,1226,0,91,1300,1169,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5773,219,1169,0,92,1226,1141,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5774,219,1141,0,93,1169,1299,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5775,219,1299,0,94,1141,1300,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5776,219,1300,0,95,1299,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5777,219,1162,0,96,1300,1157,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5778,219,1157,0,97,1162,1301,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5779,219,1301,0,98,1157,1169,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5780,219,1169,0,99,1301,1177,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5781,219,1177,0,100,1169,1181,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5782,219,1181,0,101,1177,1156,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5783,219,1156,0,102,1181,1143,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5784,219,1143,0,103,1156,1302,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5785,219,1302,0,104,1143,1303,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5786,219,1303,0,105,1302,1151,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5787,219,1151,0,106,1303,1304,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5788,219,1304,0,107,1151,1186,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5789,219,1186,0,108,1304,1167,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5790,219,1167,0,109,1186,1141,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5791,219,1141,0,110,1167,1169,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5792,219,1169,0,111,1141,1166,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5793,219,1166,0,112,1169,1305,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5794,219,1305,0,113,1166,1302,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5795,219,1302,0,114,1305,1149,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5796,219,1149,0,115,1302,1179,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5797,219,1179,0,116,1149,1162,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5798,219,1162,0,117,1179,1306,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5799,219,1306,0,118,1162,1167,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5800,219,1167,0,119,1306,1307,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5801,219,1307,0,120,1167,1308,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5802,219,1308,0,121,1307,1307,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5803,219,1307,0,122,1308,1168,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5804,219,1168,0,123,1307,1169,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5805,219,1169,0,124,1168,1309,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5806,219,1309,0,125,1169,1310,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5807,219,1310,0,126,1309,1147,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5808,219,1147,0,127,1310,1311,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5809,219,1311,0,128,1147,1195,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5810,219,1195,0,129,1311,1312,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5811,219,1312,0,130,1195,1146,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5812,219,1146,0,131,1312,1152,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5813,219,1152,0,132,1146,1153,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5814,219,1153,0,133,1152,0,10,1068542692,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5815,220,1313,0,0,0,1146,10,1068542738,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5816,220,1146,0,1,1313,1301,10,1068542738,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5817,220,1301,0,2,1146,1313,10,1068542738,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5818,220,1313,0,3,1301,1146,10,1068542738,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5819,220,1146,0,4,1313,1301,10,1068542738,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5820,220,1301,0,5,1146,1147,10,1068542738,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5821,220,1147,0,6,1301,1313,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5822,220,1313,0,7,1147,1146,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5823,220,1146,0,8,1313,1301,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5824,220,1301,0,9,1146,1166,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5825,220,1166,0,10,1301,1314,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5826,220,1314,0,11,1166,1162,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5827,220,1162,0,12,1314,1306,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5828,220,1306,0,13,1162,1294,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5829,220,1294,0,14,1306,1156,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5830,220,1156,0,15,1294,1315,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5831,220,1315,0,16,1156,1316,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5832,220,1316,0,17,1315,1141,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5833,220,1141,0,18,1316,1317,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5834,220,1317,0,19,1141,1151,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5835,220,1151,0,20,1317,1152,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5836,220,1152,0,21,1151,1153,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5837,220,1153,0,22,1152,1169,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5838,220,1169,0,23,1153,1175,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5839,220,1175,0,24,1169,1318,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5840,220,1318,0,25,1175,1147,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5841,220,1147,0,26,1318,1319,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5842,220,1319,0,27,1147,1162,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5843,220,1162,0,28,1319,1261,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5844,220,1261,0,29,1162,1320,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5845,220,1320,0,30,1261,1147,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5846,220,1147,0,31,1320,1201,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5847,220,1201,0,32,1147,1147,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5848,220,1147,0,33,1201,1313,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5849,220,1313,0,34,1147,1146,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5850,220,1146,0,35,1313,1301,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5851,220,1301,0,36,1146,1166,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5852,220,1166,0,37,1301,1314,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5853,220,1314,0,38,1166,1162,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5854,220,1162,0,39,1314,1306,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5855,220,1306,0,40,1162,1294,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5856,220,1294,0,41,1306,1156,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5857,220,1156,0,42,1294,1315,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5858,220,1315,0,43,1156,1316,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5859,220,1316,0,44,1315,1141,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5860,220,1141,0,45,1316,1317,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5861,220,1317,0,46,1141,1151,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5862,220,1151,0,47,1317,1152,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5863,220,1152,0,48,1151,1153,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5864,220,1153,0,49,1152,1169,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5865,220,1169,0,50,1153,1175,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5866,220,1175,0,51,1169,1318,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5867,220,1318,0,52,1175,1147,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5868,220,1147,0,53,1318,1319,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5869,220,1319,0,54,1147,1162,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5870,220,1162,0,55,1319,1261,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5871,220,1261,0,56,1162,1320,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5872,220,1320,0,57,1261,1147,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5873,220,1147,0,58,1320,1201,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5874,220,1201,0,59,1147,0,10,1068542738,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5875,222,1321,0,0,0,1202,24,1068554919,1,206,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5876,222,1202,0,1,1321,1321,24,1068554919,1,206,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5877,222,1321,0,2,1202,1202,24,1068554919,1,206,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5878,222,1202,0,3,1321,1171,24,1068554919,1,206,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5879,222,1171,0,4,1202,1321,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5880,222,1321,0,5,1171,1182,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5881,222,1182,0,6,1321,1166,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5882,222,1166,0,7,1182,1314,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5883,222,1314,0,8,1166,1162,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5884,222,1162,0,9,1314,1322,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5885,222,1322,0,10,1162,1152,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5886,222,1152,0,11,1322,1323,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5887,222,1323,0,12,1152,1298,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5888,222,1298,0,13,1323,1324,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5889,222,1324,0,14,1298,1325,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5890,222,1325,0,15,1324,1326,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5891,222,1326,0,16,1325,1185,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5892,222,1185,0,17,1326,1173,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5893,222,1173,0,18,1185,1294,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5894,222,1294,0,19,1173,1161,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5895,222,1161,0,20,1294,1327,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5896,222,1327,0,21,1161,1151,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5897,222,1151,0,22,1327,1328,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5898,222,1328,0,23,1151,1226,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5899,222,1226,0,24,1328,1162,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5900,222,1162,0,25,1226,1329,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5901,222,1329,0,26,1162,1330,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5902,222,1330,0,27,1329,1161,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5903,222,1161,0,28,1330,1261,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5904,222,1261,0,29,1161,1331,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5905,222,1331,0,30,1261,1166,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5906,222,1166,0,31,1331,1332,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5907,222,1332,0,32,1166,1333,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5908,222,1333,0,33,1332,1334,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5909,222,1334,0,34,1333,1333,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5910,222,1333,0,35,1334,1335,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5911,222,1335,0,36,1333,1336,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5912,222,1336,0,37,1335,1337,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5913,222,1337,0,38,1336,1338,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5914,222,1338,0,39,1337,1339,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5915,222,1339,0,40,1338,1141,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5916,222,1141,0,41,1339,1340,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5917,222,1340,0,42,1141,1336,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5918,222,1336,0,43,1340,1339,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5919,222,1339,0,44,1336,1167,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5920,222,1167,0,45,1339,1241,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5921,222,1241,0,46,1167,1166,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5922,222,1166,0,47,1241,1175,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5923,222,1175,0,48,1166,1341,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5924,222,1341,0,49,1175,1342,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5925,222,1342,0,50,1341,1177,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5926,222,1177,0,51,1342,1156,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5927,222,1156,0,52,1177,1179,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5928,222,1179,0,53,1156,1343,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5929,222,1343,0,54,1179,1161,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5930,222,1161,0,55,1343,1344,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5931,222,1344,0,56,1161,1147,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5932,222,1147,0,57,1344,1241,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5933,222,1241,0,58,1147,1173,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5934,222,1173,0,59,1241,1345,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5935,222,1345,0,60,1173,1346,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5936,222,1346,0,61,1345,1325,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5937,222,1325,0,62,1346,1171,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5938,222,1171,0,63,1325,1321,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5939,222,1321,0,64,1171,1182,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5940,222,1182,0,65,1321,1166,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5941,222,1166,0,66,1182,1314,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5942,222,1314,0,67,1166,1162,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5943,222,1162,0,68,1314,1322,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5944,222,1322,0,69,1162,1152,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5945,222,1152,0,70,1322,1323,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5946,222,1323,0,71,1152,1298,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5947,222,1298,0,72,1323,1324,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5948,222,1324,0,73,1298,1325,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5949,222,1325,0,74,1324,1326,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5950,222,1326,0,75,1325,1185,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5951,222,1185,0,76,1326,1173,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5952,222,1173,0,77,1185,1294,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5953,222,1294,0,78,1173,1161,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5954,222,1161,0,79,1294,1327,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5955,222,1327,0,80,1161,1151,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5956,222,1151,0,81,1327,1328,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5957,222,1328,0,82,1151,1226,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5958,222,1226,0,83,1328,1162,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5959,222,1162,0,84,1226,1329,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5960,222,1329,0,85,1162,1330,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5961,222,1330,0,86,1329,1161,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5962,222,1161,0,87,1330,1261,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5963,222,1261,0,88,1161,1331,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5964,222,1331,0,89,1261,1166,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5965,222,1166,0,90,1331,1332,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5966,222,1332,0,91,1166,1333,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5967,222,1333,0,92,1332,1334,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5968,222,1334,0,93,1333,1333,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5969,222,1333,0,94,1334,1335,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5970,222,1335,0,95,1333,1336,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5971,222,1336,0,96,1335,1337,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5972,222,1337,0,97,1336,1338,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5973,222,1338,0,98,1337,1339,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5974,222,1339,0,99,1338,1141,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5975,222,1141,0,100,1339,1340,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5976,222,1340,0,101,1141,1336,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5977,222,1336,0,102,1340,1339,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5978,222,1339,0,103,1336,1167,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5979,222,1167,0,104,1339,1241,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5980,222,1241,0,105,1167,1166,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5981,222,1166,0,106,1241,1175,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5982,222,1175,0,107,1166,1341,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5983,222,1341,0,108,1175,1342,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5984,222,1342,0,109,1341,1177,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5985,222,1177,0,110,1342,1156,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5986,222,1156,0,111,1177,1179,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5987,222,1179,0,112,1156,1343,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5988,222,1343,0,113,1179,1161,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5989,222,1161,0,114,1343,1344,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5990,222,1344,0,115,1161,1147,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5991,222,1147,0,116,1344,1241,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5992,222,1241,0,117,1147,1173,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5993,222,1173,0,118,1241,1345,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5994,222,1345,0,119,1173,1346,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5995,222,1346,0,120,1345,1325,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5996,222,1325,0,121,1346,0,24,1068554919,1,207,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5997,49,1345,0,0,0,1345,1,1066398020,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5998,49,1345,0,1,1345,0,1,1066398020,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (5999,160,1345,0,0,0,1347,2,1068047455,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6000,160,1347,0,1,1345,1348,2,1068047455,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6001,160,1348,0,2,1347,1345,2,1068047455,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6002,160,1345,0,3,1348,1347,2,1068047455,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6003,160,1347,0,4,1345,1348,2,1068047455,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6004,160,1348,0,5,1347,1331,2,1068047455,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6005,160,1331,0,6,1348,1143,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6006,160,1143,0,7,1331,1147,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6007,160,1147,0,8,1143,1349,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6008,160,1349,0,9,1147,1345,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6009,160,1345,0,10,1349,1238,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6010,160,1238,0,11,1345,1167,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6011,160,1167,0,12,1238,1153,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6012,160,1153,0,13,1167,1350,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6013,160,1350,0,14,1153,1351,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6014,160,1351,0,15,1350,1232,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6015,160,1232,0,16,1351,1352,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6016,160,1352,0,17,1232,1345,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6017,160,1345,0,18,1352,1307,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6018,160,1307,0,19,1345,1353,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6019,160,1353,0,20,1307,1307,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6020,160,1307,0,21,1353,1350,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6021,160,1350,0,22,1307,1261,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6022,160,1261,0,23,1350,1354,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6023,160,1354,0,24,1261,1160,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6024,160,1160,0,25,1354,1354,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6025,160,1354,0,26,1160,1355,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6026,160,1355,0,27,1354,1141,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6027,160,1141,0,28,1355,1149,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6028,160,1149,0,29,1141,1185,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6029,160,1185,0,30,1149,1161,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6030,160,1161,0,31,1185,1356,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6031,160,1356,0,32,1161,1331,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6032,160,1331,0,33,1356,1143,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6033,160,1143,0,34,1331,1147,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6034,160,1147,0,35,1143,1349,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6035,160,1349,0,36,1147,1345,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6036,160,1345,0,37,1349,1238,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6037,160,1238,0,38,1345,1167,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6038,160,1167,0,39,1238,1153,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6039,160,1153,0,40,1167,1350,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6040,160,1350,0,41,1153,1351,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6041,160,1351,0,42,1350,1232,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6042,160,1232,0,43,1351,1352,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6043,160,1352,0,44,1232,1345,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6044,160,1345,0,45,1352,1307,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6045,160,1307,0,46,1345,1353,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6046,160,1353,0,47,1307,1307,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6047,160,1307,0,48,1353,1350,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6048,160,1350,0,49,1307,1261,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6049,160,1261,0,50,1350,1354,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6050,160,1354,0,51,1261,1160,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6051,160,1160,0,52,1354,1354,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6052,160,1354,0,53,1160,1355,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6053,160,1355,0,54,1354,1141,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6054,160,1141,0,55,1355,1149,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6055,160,1149,0,56,1141,1185,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6056,160,1185,0,57,1149,1161,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6057,160,1161,0,58,1185,1356,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6058,160,1356,0,59,1161,1350,2,1068047455,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6059,160,1350,0,60,1356,1357,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6060,160,1357,0,61,1350,1171,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6061,160,1171,0,62,1357,1354,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6062,160,1354,0,63,1171,1358,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6063,160,1358,0,64,1354,1307,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6064,160,1307,0,65,1358,1162,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6065,160,1162,0,66,1307,1213,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6066,160,1213,0,67,1162,1309,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6067,160,1309,0,68,1213,1359,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6068,160,1359,0,69,1309,1169,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6069,160,1169,0,70,1359,1166,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6070,160,1166,0,71,1169,1171,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6071,160,1171,0,72,1166,1360,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6072,160,1360,0,73,1171,1361,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6073,160,1361,0,74,1360,1362,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6074,160,1362,0,75,1361,1238,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6075,160,1238,0,76,1362,1147,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6076,160,1147,0,77,1238,1363,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6077,160,1363,0,78,1147,1241,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6078,160,1241,0,79,1363,1350,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6079,160,1350,0,80,1241,1357,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6080,160,1357,0,81,1350,1171,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6081,160,1171,0,82,1357,1354,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6082,160,1354,0,83,1171,1358,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6083,160,1358,0,84,1354,1307,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6084,160,1307,0,85,1358,1162,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6085,160,1162,0,86,1307,1213,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6086,160,1213,0,87,1162,1309,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6087,160,1309,0,88,1213,1359,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6088,160,1359,0,89,1309,1169,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6089,160,1169,0,90,1359,1166,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6090,160,1166,0,91,1169,1171,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6091,160,1171,0,92,1166,1360,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6092,160,1360,0,93,1171,1361,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6093,160,1361,0,94,1360,1362,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6094,160,1362,0,95,1361,1238,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6095,160,1238,0,96,1362,1147,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6096,160,1147,0,97,1238,1363,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6097,160,1363,0,98,1147,1241,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6098,160,1241,0,99,1363,0,2,1068047455,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6099,250,1345,0,0,0,1347,2,1069687269,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6100,250,1347,0,1,1345,1364,2,1069687269,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6101,250,1364,0,2,1347,1345,2,1069687269,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6102,250,1345,0,3,1364,1347,2,1069687269,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6103,250,1347,0,4,1345,1364,2,1069687269,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6104,250,1364,0,5,1347,1167,2,1069687269,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6105,250,1167,0,6,1364,1365,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6106,250,1365,0,7,1167,1366,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6107,250,1366,0,8,1365,1190,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6108,250,1190,0,9,1366,1226,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6109,250,1226,0,10,1190,1147,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6110,250,1147,0,11,1226,1357,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6111,250,1357,0,12,1147,1146,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6112,250,1146,0,13,1357,1367,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6113,250,1367,0,14,1146,1354,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6114,250,1354,0,15,1367,1160,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6115,250,1160,0,16,1354,1368,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6116,250,1368,0,17,1160,1171,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6117,250,1171,0,18,1368,1141,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6118,250,1141,0,19,1171,1368,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6119,250,1368,0,20,1141,1369,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6120,250,1369,0,21,1368,1167,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6121,250,1167,0,22,1369,1365,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6122,250,1365,0,23,1167,1366,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6123,250,1366,0,24,1365,1190,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6124,250,1190,0,25,1366,1226,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6125,250,1226,0,26,1190,1147,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6126,250,1147,0,27,1226,1357,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6127,250,1357,0,28,1147,1146,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6128,250,1146,0,29,1357,1367,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6129,250,1367,0,30,1146,1354,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6130,250,1354,0,31,1367,1160,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6131,250,1160,0,32,1354,1368,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6132,250,1368,0,33,1160,1171,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6133,250,1171,0,34,1368,1141,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6134,250,1141,0,35,1171,1368,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6135,250,1368,0,36,1141,1369,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6136,250,1369,0,37,1368,1163,2,1069687269,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6137,250,1163,0,38,1369,1143,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6138,250,1143,0,39,1163,1370,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6139,250,1370,0,40,1143,1371,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6140,250,1371,0,41,1370,1146,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6141,250,1146,0,42,1371,1171,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6142,250,1171,0,43,1146,1354,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6143,250,1354,0,44,1171,1368,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6144,250,1368,0,45,1354,1372,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6145,250,1372,0,46,1368,1179,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6146,250,1179,0,47,1372,1351,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6147,250,1351,0,48,1179,1310,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6148,250,1310,0,49,1351,1147,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6149,250,1147,0,50,1310,1373,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6150,250,1373,0,51,1147,1146,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6151,250,1146,0,52,1373,1167,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6152,250,1167,0,53,1146,1374,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6153,250,1374,0,54,1167,1375,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6154,250,1375,0,55,1374,1351,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6155,250,1351,0,56,1375,1310,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6156,250,1310,0,57,1351,1376,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6157,250,1376,0,58,1310,1173,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6158,250,1173,0,59,1376,1160,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6159,250,1160,0,60,1173,1183,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6160,250,1183,0,61,1160,1167,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6161,250,1167,0,62,1183,1151,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6162,250,1151,0,63,1167,1377,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6163,250,1377,0,64,1151,1378,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6164,250,1378,0,65,1377,1379,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6165,250,1379,0,66,1378,1151,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6166,250,1151,0,67,1379,1352,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6167,250,1352,0,68,1151,1379,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6168,250,1379,0,69,1352,1162,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6169,250,1162,0,70,1379,1309,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6170,250,1309,0,71,1162,1380,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6171,250,1380,0,72,1309,1307,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6172,250,1307,0,73,1380,1377,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6173,250,1377,0,74,1307,1160,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6174,250,1160,0,75,1377,1162,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6175,250,1162,0,76,1160,1183,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6176,250,1183,0,77,1162,1381,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6177,250,1381,0,78,1183,1382,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6178,250,1382,0,79,1381,1141,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6179,250,1141,0,80,1382,1293,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6180,250,1293,0,81,1141,1368,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6181,250,1368,0,82,1293,1383,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6182,250,1383,0,83,1368,1162,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6183,250,1162,0,84,1383,1292,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6184,250,1292,0,85,1162,1175,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6185,250,1175,0,86,1292,1144,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6186,250,1144,0,87,1175,1380,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6187,250,1380,0,88,1144,1384,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6188,250,1384,0,89,1380,1146,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6189,250,1146,0,90,1384,1147,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6190,250,1147,0,91,1146,1368,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6191,250,1368,0,92,1147,1246,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6192,250,1246,0,93,1368,1179,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6193,250,1179,0,94,1246,1147,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6194,250,1147,0,95,1179,1385,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6195,250,1385,0,96,1147,1309,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6196,250,1309,0,97,1385,1359,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6197,250,1359,0,98,1309,1147,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6198,250,1147,0,99,1359,1368,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6199,250,1368,0,100,1147,1163,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6200,250,1163,0,101,1368,1143,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6201,250,1143,0,102,1163,1386,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6202,250,1386,0,103,1143,1186,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6203,250,1186,0,104,1386,1163,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6204,250,1163,0,105,1186,1143,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6205,250,1143,0,106,1163,1370,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6206,250,1370,0,107,1143,1371,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6207,250,1371,0,108,1370,1146,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6208,250,1146,0,109,1371,1171,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6209,250,1171,0,110,1146,1354,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6210,250,1354,0,111,1171,1368,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6211,250,1368,0,112,1354,1372,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6212,250,1372,0,113,1368,1179,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6213,250,1179,0,114,1372,1351,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6214,250,1351,0,115,1179,1310,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6215,250,1310,0,116,1351,1147,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6216,250,1147,0,117,1310,1373,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6217,250,1373,0,118,1147,1146,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6218,250,1146,0,119,1373,1167,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6219,250,1167,0,120,1146,1374,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6220,250,1374,0,121,1167,1375,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6221,250,1375,0,122,1374,1351,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6222,250,1351,0,123,1375,1310,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6223,250,1310,0,124,1351,1376,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6224,250,1376,0,125,1310,1173,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6225,250,1173,0,126,1376,1160,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6226,250,1160,0,127,1173,1183,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6227,250,1183,0,128,1160,1167,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6228,250,1167,0,129,1183,1151,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6229,250,1151,0,130,1167,1377,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6230,250,1377,0,131,1151,1378,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6231,250,1378,0,132,1377,1379,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6232,250,1379,0,133,1378,1151,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6233,250,1151,0,134,1379,1352,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6234,250,1352,0,135,1151,1379,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6235,250,1379,0,136,1352,1162,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6236,250,1162,0,137,1379,1309,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6237,250,1309,0,138,1162,1380,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6238,250,1380,0,139,1309,1307,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6239,250,1307,0,140,1380,1377,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6240,250,1377,0,141,1307,1160,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6241,250,1160,0,142,1377,1162,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6242,250,1162,0,143,1160,1183,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6243,250,1183,0,144,1162,1381,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6244,250,1381,0,145,1183,1382,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6245,250,1382,0,146,1381,1141,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6246,250,1141,0,147,1382,1293,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6247,250,1293,0,148,1141,1368,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6248,250,1368,0,149,1293,1383,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6249,250,1383,0,150,1368,1162,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6250,250,1162,0,151,1383,1292,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6251,250,1292,0,152,1162,1175,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6252,250,1175,0,153,1292,1144,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6253,250,1144,0,154,1175,1380,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6254,250,1380,0,155,1144,1384,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6255,250,1384,0,156,1380,1146,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6256,250,1146,0,157,1384,1147,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6257,250,1147,0,158,1146,1368,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6258,250,1368,0,159,1147,1246,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6259,250,1246,0,160,1368,1179,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6260,250,1179,0,161,1246,1147,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6261,250,1147,0,162,1179,1385,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6262,250,1385,0,163,1147,1309,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6263,250,1309,0,164,1385,1359,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6264,250,1359,0,165,1309,1147,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6265,250,1147,0,166,1359,1368,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6266,250,1368,0,167,1147,1163,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6267,250,1163,0,168,1368,1143,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6268,250,1143,0,169,1163,1386,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6269,250,1386,0,170,1143,1186,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6270,250,1186,0,171,1386,0,2,1069687269,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6271,254,1171,0,0,0,1354,2,1069688677,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6272,254,1354,0,1,1171,1209,2,1069688677,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6273,254,1209,0,2,1354,1171,2,1069688677,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6274,254,1171,0,3,1209,1354,2,1069688677,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6275,254,1354,0,4,1171,1209,2,1069688677,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6276,254,1209,0,5,1354,1147,2,1069688677,1,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6277,254,1147,0,6,1209,1261,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6278,254,1261,0,7,1147,1387,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6279,254,1387,0,8,1261,1388,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6280,254,1388,0,9,1387,1389,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6281,254,1389,0,10,1388,1215,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6282,254,1215,0,11,1389,1208,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6283,254,1208,0,12,1215,1206,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6284,254,1206,0,13,1208,1238,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6285,254,1238,0,14,1206,1205,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6286,254,1205,0,15,1238,1390,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6287,254,1390,0,16,1205,1147,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6288,254,1147,0,17,1390,1261,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6289,254,1261,0,18,1147,1387,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6290,254,1387,0,19,1261,1388,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6291,254,1388,0,20,1387,1389,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6292,254,1389,0,21,1388,1215,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6293,254,1215,0,22,1389,1208,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6294,254,1208,0,23,1215,1206,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6295,254,1206,0,24,1208,1238,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6296,254,1238,0,25,1206,1205,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6297,254,1205,0,26,1238,1390,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6298,254,1390,0,27,1205,1163,2,1069688677,1,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6299,254,1163,0,28,1390,1143,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6300,254,1143,0,29,1163,1391,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6301,254,1391,0,30,1143,1238,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6302,254,1238,0,31,1391,1205,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6303,254,1205,0,32,1238,1374,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6304,254,1374,0,33,1205,1177,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6305,254,1177,0,34,1374,1307,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6306,254,1307,0,35,1177,1392,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6307,254,1392,0,36,1307,1307,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6308,254,1307,0,37,1392,1393,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6309,254,1393,0,38,1307,1327,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6310,254,1327,0,39,1393,1394,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6311,254,1394,0,40,1327,1395,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6312,254,1395,0,41,1394,1162,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6313,254,1162,0,42,1395,1309,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6314,254,1309,0,43,1162,1163,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6315,254,1163,0,44,1309,1143,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6316,254,1143,0,45,1163,1391,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6317,254,1391,0,46,1143,1238,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6318,254,1238,0,47,1391,1205,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6319,254,1205,0,48,1238,1374,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6320,254,1374,0,49,1205,1177,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6321,254,1177,0,50,1374,1307,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6322,254,1307,0,51,1177,1392,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6323,254,1392,0,52,1307,1307,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6324,254,1307,0,53,1392,1393,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6325,254,1393,0,54,1307,1327,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6326,254,1327,0,55,1393,1394,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6327,254,1394,0,56,1327,1395,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6328,254,1395,0,57,1394,1162,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6329,254,1162,0,58,1395,1309,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6330,254,1309,0,59,1162,0,2,1069688677,1,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6331,11,1396,0,0,0,1397,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6332,11,1397,0,1,1396,1396,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6333,11,1396,0,2,1397,1397,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6334,11,1397,0,3,1396,0,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6335,12,1398,0,0,0,1385,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6336,12,1385,0,1,1398,1398,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6337,12,1398,0,2,1385,1385,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6338,12,1385,0,3,1398,0,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6339,14,1398,0,0,0,1398,4,1033920830,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6340,14,1398,0,1,1398,1399,4,1033920830,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6341,14,1399,0,2,1398,1399,4,1033920830,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6342,14,1399,0,3,1399,1400,4,1033920830,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6343,14,1400,0,4,1399,1401,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6344,14,1401,0,5,1400,1400,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6345,14,1400,0,6,1401,1401,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6346,14,1401,0,7,1400,0,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6347,13,1402,0,0,0,1402,3,1033920794,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6348,13,1402,0,1,1402,0,3,1033920794,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6349,266,1403,0,0,0,1385,3,1072181235,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6350,266,1385,0,1,1403,1403,3,1072181235,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6351,266,1403,0,2,1385,1385,3,1072181235,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6352,266,1385,0,3,1403,1399,3,1072181235,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6353,266,1399,0,4,1385,1404,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6354,266,1404,0,5,1399,1177,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6355,266,1177,0,6,1404,1147,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6356,266,1147,0,7,1177,1403,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6357,266,1403,0,8,1147,1399,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6358,266,1399,0,9,1403,1399,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6359,266,1399,0,10,1399,1404,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6360,266,1404,0,11,1399,1177,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6361,266,1177,0,12,1404,1147,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6362,266,1147,0,13,1177,1403,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6363,266,1403,0,14,1147,1399,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6364,266,1399,0,15,1403,0,3,1072181235,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6365,10,1403,0,0,0,1403,4,1033920665,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6366,10,1403,0,1,1403,1399,4,1033920665,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6367,10,1399,0,2,1403,1399,4,1033920665,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6368,10,1399,0,3,1399,1403,4,1033920665,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6369,10,1403,0,4,1399,1401,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6370,10,1401,0,5,1403,1403,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6371,10,1403,0,6,1401,1401,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6372,10,1401,0,7,1403,0,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6373,44,1405,0,0,0,1406,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6374,44,1406,0,1,1405,1405,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6375,44,1405,0,2,1406,1406,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6376,44,1406,0,3,1405,0,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6377,43,1407,0,0,0,1407,14,1066384365,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6378,43,1407,0,1,1407,1408,14,1066384365,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6379,43,1408,0,2,1407,1409,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6380,43,1409,0,3,1408,1408,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6381,43,1408,0,4,1409,1409,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6382,43,1409,0,5,1408,0,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6383,45,1410,0,0,0,1141,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6384,45,1141,0,1,1410,1411,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6385,45,1411,0,2,1141,1410,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6386,45,1410,0,3,1411,1141,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6387,45,1141,0,4,1410,1411,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6388,45,1411,0,5,1141,1412,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6389,45,1412,0,6,1411,1413,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6390,45,1413,0,7,1412,1414,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6391,45,1414,0,8,1413,1412,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6392,45,1412,0,9,1414,1413,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6393,45,1413,0,10,1412,1414,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6394,45,1414,0,11,1413,0,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6395,115,1415,0,0,0,1415,14,1066991725,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6396,115,1415,0,1,1415,1405,14,1066991725,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6397,115,1405,0,2,1415,1415,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6398,115,1415,0,3,1405,1405,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6399,115,1405,0,4,1415,1415,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6400,115,1415,0,5,1405,0,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6401,116,1416,0,0,0,1417,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6402,116,1417,0,1,1416,1416,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6403,116,1416,0,2,1417,1417,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6404,116,1417,0,3,1416,1412,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6405,116,1412,0,4,1417,1418,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6406,116,1418,0,5,1412,1412,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6407,116,1412,0,6,1418,1418,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6408,116,1418,0,7,1412,0,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6409,46,1410,0,0,0,1141,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6410,46,1141,0,1,1410,1411,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6411,46,1411,0,2,1141,1410,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6412,46,1410,0,3,1411,1141,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6413,46,1141,0,4,1410,1411,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6414,46,1411,0,5,1141,0,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6415,56,1419,0,0,0,1419,15,1066643397,11,161,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6416,56,1419,0,1,1419,1420,15,1066643397,11,161,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6417,56,1420,0,2,1419,1421,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6418,56,1421,0,3,1420,1231,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6419,56,1231,0,4,1421,1422,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6420,56,1422,0,5,1231,1307,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6421,56,1307,0,6,1422,1423,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6422,56,1423,0,7,1307,1424,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6423,56,1424,0,8,1423,1420,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6424,56,1420,0,9,1424,1421,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6425,56,1421,0,10,1420,1231,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6426,56,1231,0,11,1421,1422,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6427,56,1422,0,12,1231,1307,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6428,56,1307,0,13,1422,1423,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6429,56,1423,0,14,1307,1424,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6430,56,1424,0,15,1423,0,15,1066643397,11,218,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6431,267,1425,0,0,0,1426,1,1076581401,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6432,267,1426,0,1,1425,1427,1,1076581401,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6433,267,1427,0,2,1426,1425,1,1076581401,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6434,267,1425,0,3,1427,1426,1,1076581401,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6435,267,1426,0,4,1425,1427,1,1076581401,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6436,267,1427,0,5,1426,0,1,1076581401,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6460,268,1427,0,5,1426,0,26,1076581463,11,219,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6459,268,1426,0,4,1425,1427,26,1076581463,11,219,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6458,268,1425,0,3,1427,1426,26,1076581463,11,219,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6457,268,1427,0,2,1426,1425,26,1076581463,11,219,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6456,268,1426,0,1,1425,1427,26,1076581463,11,219,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6455,268,1425,0,0,0,1426,26,1076581463,11,219,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6443,269,1425,0,0,0,1426,14,1076577902,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6444,269,1426,0,1,1425,1427,14,1076577902,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6445,269,1427,0,2,1426,1425,14,1076577902,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6446,269,1425,0,3,1427,1426,14,1076577902,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6447,269,1426,0,4,1425,1427,14,1076577902,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6448,269,1427,0,5,1426,1412,14,1076577902,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6449,269,1412,0,6,1427,1413,14,1076577902,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6450,269,1413,0,7,1412,1428,14,1076577902,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6451,269,1428,0,8,1413,1412,14,1076577902,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6452,269,1412,0,9,1428,1413,14,1076577902,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6453,269,1413,0,10,1412,1428,14,1076577902,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (6454,269,1428,0,11,1413,0,14,1076577902,11,155,'',0);

--
-- Table structure for table 'ezsearch_return_count'
--

CREATE TABLE ezsearch_return_count (
  id int(11) NOT NULL auto_increment,
  phrase_id int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_return_count'
--



--
-- Table structure for table 'ezsearch_search_phrase'
--

CREATE TABLE ezsearch_search_phrase (
  id int(11) NOT NULL auto_increment,
  phrase varchar(250) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_search_phrase'
--



--
-- Table structure for table 'ezsearch_word'
--

CREATE TABLE ezsearch_word (
  id int(11) NOT NULL auto_increment,
  word varchar(150) default NULL,
  object_count int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_word (word)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_word'
--


INSERT INTO ezsearch_word VALUES (1140,'shipping',1);
INSERT INTO ezsearch_word VALUES (1141,'and',13);
INSERT INTO ezsearch_word VALUES (1142,'returns',1);
INSERT INTO ezsearch_word VALUES (1143,'are',5);
INSERT INTO ezsearch_word VALUES (1144,'always',2);
INSERT INTO ezsearch_word VALUES (1145,'one',3);
INSERT INTO ezsearch_word VALUES (1146,'of',7);
INSERT INTO ezsearch_word VALUES (1147,'the',14);
INSERT INTO ezsearch_word VALUES (1148,'most',1);
INSERT INTO ezsearch_word VALUES (1149,'important',3);
INSERT INTO ezsearch_word VALUES (1150,'pages',1);
INSERT INTO ezsearch_word VALUES (1151,'in',7);
INSERT INTO ezsearch_word VALUES (1152,'your',4);
INSERT INTO ezsearch_word VALUES (1153,'webshop',4);
INSERT INTO ezsearch_word VALUES (1154,'even',1);
INSERT INTO ezsearch_word VALUES (1155,'if',2);
INSERT INTO ezsearch_word VALUES (1156,'people',4);
INSERT INTO ezsearch_word VALUES (1157,'not',2);
INSERT INTO ezsearch_word VALUES (1158,'returning',1);
INSERT INTO ezsearch_word VALUES (1159,'their',1);
INSERT INTO ezsearch_word VALUES (1160,'products',4);
INSERT INTO ezsearch_word VALUES (1161,'to',6);
INSERT INTO ezsearch_word VALUES (1162,'you',9);
INSERT INTO ezsearch_word VALUES (1163,'they',3);
INSERT INTO ezsearch_word VALUES (1164,'want',2);
INSERT INTO ezsearch_word VALUES (1165,'know',2);
INSERT INTO ezsearch_word VALUES (1166,'is',8);
INSERT INTO ezsearch_word VALUES (1167,'this',7);
INSERT INTO ezsearch_word VALUES (1168,'possible',2);
INSERT INTO ezsearch_word VALUES (1169,'it',5);
INSERT INTO ezsearch_word VALUES (1170,'kind',1);
INSERT INTO ezsearch_word VALUES (1171,'a',7);
INSERT INTO ezsearch_word VALUES (1172,'guarantee',1);
INSERT INTO ezsearch_word VALUES (1173,'on',3);
INSERT INTO ezsearch_word VALUES (1174,'bahalf',1);
INSERT INTO ezsearch_word VALUES (1175,'also',4);
INSERT INTO ezsearch_word VALUES (1176,'way',1);
INSERT INTO ezsearch_word VALUES (1177,'for',7);
INSERT INTO ezsearch_word VALUES (1178,'show',1);
INSERT INTO ezsearch_word VALUES (1179,'that',4);
INSERT INTO ezsearch_word VALUES (1180,'professional',1);
INSERT INTO ezsearch_word VALUES (1181,'normally',2);
INSERT INTO ezsearch_word VALUES (1182,'page',2);
INSERT INTO ezsearch_word VALUES (1183,'like',2);
INSERT INTO ezsearch_word VALUES (1184,'contains',1);
INSERT INTO ezsearch_word VALUES (1185,'information',4);
INSERT INTO ezsearch_word VALUES (1186,'about',5);
INSERT INTO ezsearch_word VALUES (1187,'delivery',1);
INSERT INTO ezsearch_word VALUES (1188,'time',1);
INSERT INTO ezsearch_word VALUES (1189,'cooling',1);
INSERT INTO ezsearch_word VALUES (1190,'off',2);
INSERT INTO ezsearch_word VALUES (1191,'period',1);
INSERT INTO ezsearch_word VALUES (1192,'return',1);
INSERT INTO ezsearch_word VALUES (1193,'rights',1);
INSERT INTO ezsearch_word VALUES (1194,'faulty',1);
INSERT INTO ezsearch_word VALUES (1195,'or',2);
INSERT INTO ezsearch_word VALUES (1196,'defective',1);
INSERT INTO ezsearch_word VALUES (1197,'goods',1);
INSERT INTO ezsearch_word VALUES (1198,'order',1);
INSERT INTO ezsearch_word VALUES (1199,'cancellation',1);
INSERT INTO ezsearch_word VALUES (1200,'by',1);
INSERT INTO ezsearch_word VALUES (1201,'customer',2);
INSERT INTO ezsearch_word VALUES (1202,'us',2);
INSERT INTO ezsearch_word VALUES (1203,'replacement',1);
INSERT INTO ezsearch_word VALUES (1204,'exceptions',1);
INSERT INTO ezsearch_word VALUES (1205,'our',2);
INSERT INTO ezsearch_word VALUES (1206,'cords',2);
INSERT INTO ezsearch_word VALUES (1207,'1',1);
INSERT INTO ezsearch_word VALUES (1208,'meter',3);
INSERT INTO ezsearch_word VALUES (1209,'cord',3);
INSERT INTO ezsearch_word VALUES (1210,'13444',1);
INSERT INTO ezsearch_word VALUES (1211,'long',2);
INSERT INTO ezsearch_word VALUES (1212,'works',2);
INSERT INTO ezsearch_word VALUES (1213,'all',6);
INSERT INTO ezsearch_word VALUES (1214,'machines',2);
INSERT INTO ezsearch_word VALUES (1215,'5',2);
INSERT INTO ezsearch_word VALUES (1216,'34555',1);
INSERT INTO ezsearch_word VALUES (1217,'five',2);
INSERT INTO ezsearch_word VALUES (1218,'meters',1);
INSERT INTO ezsearch_word VALUES (1219,'books',1);
INSERT INTO ezsearch_word VALUES (1220,'summer',1);
INSERT INTO ezsearch_word VALUES (1221,'book',1);
INSERT INTO ezsearch_word VALUES (1222,'1324',1);
INSERT INTO ezsearch_word VALUES (1223,'colors',1);
INSERT INTO ezsearch_word VALUES (1224,'smells',1);
INSERT INTO ezsearch_word VALUES (1225,'packed',1);
INSERT INTO ezsearch_word VALUES (1226,'with',4);
INSERT INTO ezsearch_word VALUES (1227,'picures',1);
INSERT INTO ezsearch_word VALUES (1228,'beautiful',1);
INSERT INTO ezsearch_word VALUES (1229,'landscape',1);
INSERT INTO ezsearch_word VALUES (1230,'norway',2);
INSERT INTO ezsearch_word VALUES (1231,'ez',2);
INSERT INTO ezsearch_word VALUES (1232,'publish',2);
INSERT INTO ezsearch_word VALUES (1233,'basics',1);
INSERT INTO ezsearch_word VALUES (1234,'123414',1);
INSERT INTO ezsearch_word VALUES (1235,'everything',1);
INSERT INTO ezsearch_word VALUES (1236,'need',1);
INSERT INTO ezsearch_word VALUES (1237,'steps',1);
INSERT INTO ezsearch_word VALUES (1238,'from',6);
INSERT INTO ezsearch_word VALUES (1239,'download',1);
INSERT INTO ezsearch_word VALUES (1240,'finished',1);
INSERT INTO ezsearch_word VALUES (1241,'site',3);
INSERT INTO ezsearch_word VALUES (1242,'cars',2);
INSERT INTO ezsearch_word VALUES (1243,'troll',1);
INSERT INTO ezsearch_word VALUES (1244,'was',1);
INSERT INTO ezsearch_word VALUES (1245,'first',1);
INSERT INTO ezsearch_word VALUES (1246,'so',2);
INSERT INTO ezsearch_word VALUES (1247,'far',1);
INSERT INTO ezsearch_word VALUES (1248,'only',1);
INSERT INTO ezsearch_word VALUES (1249,'car',1);
INSERT INTO ezsearch_word VALUES (1250,'made',1);
INSERT INTO ezsearch_word VALUES (1251,'left',1);
INSERT INTO ezsearch_word VALUES (1252,'factory',1);
INSERT INTO ezsearch_word VALUES (1253,'total',1);
INSERT INTO ezsearch_word VALUES (1254,'ferrari',1);
INSERT INTO ezsearch_word VALUES (1255,'enjoy',1);
INSERT INTO ezsearch_word VALUES (1256,'feeling',1);
INSERT INTO ezsearch_word VALUES (1257,'s',1);
INSERT INTO ezsearch_word VALUES (1258,'nothing',1);
INSERT INTO ezsearch_word VALUES (1259,'more',1);
INSERT INTO ezsearch_word VALUES (1260,'say',1);
INSERT INTO ezsearch_word VALUES (1261,'have',5);
INSERT INTO ezsearch_word VALUES (1262,'ever',1);
INSERT INTO ezsearch_word VALUES (1263,'tried',1);
INSERT INTO ezsearch_word VALUES (1264,'never',1);
INSERT INTO ezsearch_word VALUES (1265,'leave',1);
INSERT INTO ezsearch_word VALUES (1266,'re',1);
INSERT INTO ezsearch_word VALUES (1267,'fan',1);
INSERT INTO ezsearch_word VALUES (1268,'forever',1);
INSERT INTO ezsearch_word VALUES (1269,'dvd',3);
INSERT INTO ezsearch_word VALUES (1270,'music',1);
INSERT INTO ezsearch_word VALUES (1271,'60897',1);
INSERT INTO ezsearch_word VALUES (1272,'collection',1);
INSERT INTO ezsearch_word VALUES (1273,'year',1);
INSERT INTO ezsearch_word VALUES (1274,'2003',1);
INSERT INTO ezsearch_word VALUES (1275,'best',2);
INSERT INTO ezsearch_word VALUES (1276,'top',1);
INSERT INTO ezsearch_word VALUES (1277,'charts',1);
INSERT INTO ezsearch_word VALUES (1278,'100',1);
INSERT INTO ezsearch_word VALUES (1279,'action',1);
INSERT INTO ezsearch_word VALUES (1280,'clips',1);
INSERT INTO ezsearch_word VALUES (1281,'movies',1);
INSERT INTO ezsearch_word VALUES (1282,'leading',1);
INSERT INTO ezsearch_word VALUES (1283,'actors',1);
INSERT INTO ezsearch_word VALUES (1284,'hollywood',1);
INSERT INTO ezsearch_word VALUES (1285,'3',1);
INSERT INTO ezsearch_word VALUES (1286,'hours',1);
INSERT INTO ezsearch_word VALUES (1287,'non',1);
INSERT INTO ezsearch_word VALUES (1288,'stop',1);
INSERT INTO ezsearch_word VALUES (1289,'back',1);
INSERT INTO ezsearch_word VALUES (1290,'privacy',1);
INSERT INTO ezsearch_word VALUES (1291,'notice',1);
INSERT INTO ezsearch_word VALUES (1292,'should',2);
INSERT INTO ezsearch_word VALUES (1293,'write',2);
INSERT INTO ezsearch_word VALUES (1294,'how',3);
INSERT INTO ezsearch_word VALUES (1295,'secure',1);
INSERT INTO ezsearch_word VALUES (1296,'handle',1);
INSERT INTO ezsearch_word VALUES (1297,'collect',1);
INSERT INTO ezsearch_word VALUES (1298,'customers',2);
INSERT INTO ezsearch_word VALUES (1299,'what',1);
INSERT INTO ezsearch_word VALUES (1300,'do',1);
INSERT INTO ezsearch_word VALUES (1301,'use',2);
INSERT INTO ezsearch_word VALUES (1302,'very',1);
INSERT INTO ezsearch_word VALUES (1303,'interested',1);
INSERT INTO ezsearch_word VALUES (1304,'knowing',1);
INSERT INTO ezsearch_word VALUES (1305,'therefore',1);
INSERT INTO ezsearch_word VALUES (1306,'state',2);
INSERT INTO ezsearch_word VALUES (1307,'as',5);
INSERT INTO ezsearch_word VALUES (1308,'clear',1);
INSERT INTO ezsearch_word VALUES (1309,'can',4);
INSERT INTO ezsearch_word VALUES (1310,'be',2);
INSERT INTO ezsearch_word VALUES (1311,'make',1);
INSERT INTO ezsearch_word VALUES (1312,'breake',1);
INSERT INTO ezsearch_word VALUES (1313,'conditions',1);
INSERT INTO ezsearch_word VALUES (1314,'where',2);
INSERT INTO ezsearch_word VALUES (1315,'shall',1);
INSERT INTO ezsearch_word VALUES (1316,'act',1);
INSERT INTO ezsearch_word VALUES (1317,'behave',1);
INSERT INTO ezsearch_word VALUES (1318,'states',1);
INSERT INTO ezsearch_word VALUES (1319,'policy',1);
INSERT INTO ezsearch_word VALUES (1320,'towards',1);
INSERT INTO ezsearch_word VALUES (1321,'contact',1);
INSERT INTO ezsearch_word VALUES (1322,'let',1);
INSERT INTO ezsearch_word VALUES (1323,'readers',1);
INSERT INTO ezsearch_word VALUES (1324,'partners',1);
INSERT INTO ezsearch_word VALUES (1325,'etc',1);
INSERT INTO ezsearch_word VALUES (1326,'find',1);
INSERT INTO ezsearch_word VALUES (1327,'get',2);
INSERT INTO ezsearch_word VALUES (1328,'touch',1);
INSERT INTO ezsearch_word VALUES (1329,'normal',1);
INSERT INTO ezsearch_word VALUES (1330,'info',1);
INSERT INTO ezsearch_word VALUES (1331,'here',2);
INSERT INTO ezsearch_word VALUES (1332,'telephone',1);
INSERT INTO ezsearch_word VALUES (1333,'numbers',1);
INSERT INTO ezsearch_word VALUES (1334,'fax',1);
INSERT INTO ezsearch_word VALUES (1335,'e',1);
INSERT INTO ezsearch_word VALUES (1336,'mail',1);
INSERT INTO ezsearch_word VALUES (1337,'addresses',1);
INSERT INTO ezsearch_word VALUES (1338,'visitors',1);
INSERT INTO ezsearch_word VALUES (1339,'address',1);
INSERT INTO ezsearch_word VALUES (1340,'snail',1);
INSERT INTO ezsearch_word VALUES (1341,'often',1);
INSERT INTO ezsearch_word VALUES (1342,'used',1);
INSERT INTO ezsearch_word VALUES (1343,'wants',1);
INSERT INTO ezsearch_word VALUES (1344,'tip',1);
INSERT INTO ezsearch_word VALUES (1345,'news',4);
INSERT INTO ezsearch_word VALUES (1346,'updates',1);
INSERT INTO ezsearch_word VALUES (1347,'bulletin',2);
INSERT INTO ezsearch_word VALUES (1348,'october',1);
INSERT INTO ezsearch_word VALUES (1349,'latest',1);
INSERT INTO ezsearch_word VALUES (1350,'we',1);
INSERT INTO ezsearch_word VALUES (1351,'will',2);
INSERT INTO ezsearch_word VALUES (1352,'these',2);
INSERT INTO ezsearch_word VALUES (1353,'soon',1);
INSERT INTO ezsearch_word VALUES (1354,'new',3);
INSERT INTO ezsearch_word VALUES (1355,'releases',1);
INSERT INTO ezsearch_word VALUES (1356,'tell',1);
INSERT INTO ezsearch_word VALUES (1357,'release',2);
INSERT INTO ezsearch_word VALUES (1358,'website',1);
INSERT INTO ezsearch_word VALUES (1359,'see',2);
INSERT INTO ezsearch_word VALUES (1360,'great',1);
INSERT INTO ezsearch_word VALUES (1361,'step',1);
INSERT INTO ezsearch_word VALUES (1362,'forward',1);
INSERT INTO ezsearch_word VALUES (1363,'old',1);
INSERT INTO ezsearch_word VALUES (1364,'november',1);
INSERT INTO ezsearch_word VALUES (1365,'month',1);
INSERT INTO ezsearch_word VALUES (1366,'started',1);
INSERT INTO ezsearch_word VALUES (1367,'two',1);
INSERT INTO ezsearch_word VALUES (1368,'product',1);
INSERT INTO ezsearch_word VALUES (1369,'b',1);
INSERT INTO ezsearch_word VALUES (1370,'both',1);
INSERT INTO ezsearch_word VALUES (1371,'part',1);
INSERT INTO ezsearch_word VALUES (1372,'portfolio',1);
INSERT INTO ezsearch_word VALUES (1373,'basis',1);
INSERT INTO ezsearch_word VALUES (1374,'shop',2);
INSERT INTO ezsearch_word VALUES (1375,'there',1);
INSERT INTO ezsearch_word VALUES (1376,'examples',1);
INSERT INTO ezsearch_word VALUES (1377,'many',1);
INSERT INTO ezsearch_word VALUES (1378,'different',1);
INSERT INTO ezsearch_word VALUES (1379,'categories',1);
INSERT INTO ezsearch_word VALUES (1380,'add',1);
INSERT INTO ezsearch_word VALUES (1381,'set',1);
INSERT INTO ezsearch_word VALUES (1382,'prices',1);
INSERT INTO ezsearch_word VALUES (1383,'texts',1);
INSERT INTO ezsearch_word VALUES (1384,'pictures',1);
INSERT INTO ezsearch_word VALUES (1385,'users',3);
INSERT INTO ezsearch_word VALUES (1386,'reading',1);
INSERT INTO ezsearch_word VALUES (1387,'finally',1);
INSERT INTO ezsearch_word VALUES (1388,'received',1);
INSERT INTO ezsearch_word VALUES (1389,'some',1);
INSERT INTO ezsearch_word VALUES (1390,'supplier',1);
INSERT INTO ezsearch_word VALUES (1391,'available',1);
INSERT INTO ezsearch_word VALUES (1392,'low',1);
INSERT INTO ezsearch_word VALUES (1393,'£13',1);
INSERT INTO ezsearch_word VALUES (1394,'i',1);
INSERT INTO ezsearch_word VALUES (1395,'while',1);
INSERT INTO ezsearch_word VALUES (1396,'guest',1);
INSERT INTO ezsearch_word VALUES (1397,'accounts',1);
INSERT INTO ezsearch_word VALUES (1398,'administrator',2);
INSERT INTO ezsearch_word VALUES (1399,'user',3);
INSERT INTO ezsearch_word VALUES (1400,'admin',1);
INSERT INTO ezsearch_word VALUES (1401,'nospam@ez.no',2);
INSERT INTO ezsearch_word VALUES (1402,'editors',1);
INSERT INTO ezsearch_word VALUES (1403,'anonymous',2);
INSERT INTO ezsearch_word VALUES (1404,'group',1);
INSERT INTO ezsearch_word VALUES (1405,'setup',2);
INSERT INTO ezsearch_word VALUES (1406,'links',1);
INSERT INTO ezsearch_word VALUES (1407,'classes',1);
INSERT INTO ezsearch_word VALUES (1408,'class',1);
INSERT INTO ezsearch_word VALUES (1409,'grouplist',1);
INSERT INTO ezsearch_word VALUES (1410,'look',2);
INSERT INTO ezsearch_word VALUES (1411,'feel',2);
INSERT INTO ezsearch_word VALUES (1412,'content',3);
INSERT INTO ezsearch_word VALUES (1413,'edit',2);
INSERT INTO ezsearch_word VALUES (1414,'56',1);
INSERT INTO ezsearch_word VALUES (1415,'cache',1);
INSERT INTO ezsearch_word VALUES (1416,'url',1);
INSERT INTO ezsearch_word VALUES (1417,'translator',1);
INSERT INTO ezsearch_word VALUES (1418,'urltranslator',1);
INSERT INTO ezsearch_word VALUES (1419,'shop_package',1);
INSERT INTO ezsearch_word VALUES (1420,'copyright',1);
INSERT INTO ezsearch_word VALUES (1421,'&copy',1);
INSERT INTO ezsearch_word VALUES (1422,'systems',1);
INSERT INTO ezsearch_word VALUES (1423,'1999',1);
INSERT INTO ezsearch_word VALUES (1424,'2004',1);
INSERT INTO ezsearch_word VALUES (1425,'common',3);
INSERT INTO ezsearch_word VALUES (1426,'ini',3);
INSERT INTO ezsearch_word VALUES (1427,'settings',3);
INSERT INTO ezsearch_word VALUES (1428,'268',1);

--
-- Table structure for table 'ezsection'
--

CREATE TABLE ezsection (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  locale varchar(255) default NULL,
  navigation_part_identifier varchar(100) default 'ezcontentnavigationpart',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsection'
--


INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart');
INSERT INTO ezsection VALUES (2,'Users','','ezusernavigationpart');
INSERT INTO ezsection VALUES (3,'Media','','ezmedianavigationpart');
INSERT INTO ezsection VALUES (11,'Set up object','','ezsetupnavigationpart');

--
-- Table structure for table 'ezsession'
--

CREATE TABLE ezsession (
  session_key varchar(32) NOT NULL default '',
  expiration_time int(11) unsigned NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (session_key),
  KEY expiration_time (expiration_time)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsession'
--


INSERT INTO ezsession VALUES ('7ed88deca149f968b77c23205ea7ebf9',1076837133,'eZUserInfoCache_Timestamp|i:1076581113;eZUserGroupsCache_Timestamp|i:1076581113;eZUserLoggedInID|s:2:\"14\";eZUserInfoCache|a:1:{i:14;a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}}eZUserGroupsCache|a:1:{i:14;a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1076581113;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}CanInstantiateClassesCachedForUser|s:2:\"14\";ClassesCachedTimestamp|i:1076581339;CanInstantiateClasses|i:1;CanInstantiateClassList|a:13:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"23\";s:4:\"name\";s:7:\"Product\";}i:10;a:2:{s:2:\"id\";s:2:\"24\";s:4:\"name\";s:13:\"Feedback form\";}i:11;a:2:{s:2:\"id\";s:2:\"25\";s:4:\"name\";s:6:\"Review\";}i:12;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:19:\"Common ini settings\";}}eZPreferences|a:3:{s:13:\"bookmark_menu\";b:0;s:12:\"history_menu\";b:0;s:13:\"advanced_menu\";s:2:\"on\";}FromGroupID|b:0;ClassesCachedForUser|s:2:\"14\";LastAccessesURI|s:22:\"/content/view/full/193\";eZUserDiscountRulesTimestamp|i:1076581352;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}');

--
-- Table structure for table 'ezsite_data'
--

CREATE TABLE ezsite_data (
  name varchar(60) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY  (name)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsite_data'
--


INSERT INTO ezsite_data VALUES ('ezpublish-version','3.3');
INSERT INTO ezsite_data VALUES ('ezpublish-release','3');

--
-- Table structure for table 'ezsubtree_notification_rule'
--

CREATE TABLE ezsubtree_notification_rule (
  id int(11) NOT NULL auto_increment,
  use_digest int(11) default '0',
  node_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsubtree_notification_rule_id (id),
  KEY ezsubtree_notification_rule_user_id (user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsubtree_notification_rule'
--



--
-- Table structure for table 'eztipafriend_counter'
--

CREATE TABLE eztipafriend_counter (
  node_id int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'eztipafriend_counter'
--



--
-- Table structure for table 'eztrigger'
--

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

--
-- Dumping data for table 'eztrigger'
--



--
-- Table structure for table 'ezurl'
--

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

--
-- Dumping data for table 'ezurl'
--



--
-- Table structure for table 'ezurl_object_link'
--

CREATE TABLE ezurl_object_link (
  url_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  PRIMARY KEY  (url_id,contentobject_attribute_id,contentobject_attribute_version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezurl_object_link'
--



--
-- Table structure for table 'ezurlalias'
--

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

--
-- Dumping data for table 'ezurlalias'
--


INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0);
INSERT INTO ezurlalias VALUES (13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0);
INSERT INTO ezurlalias VALUES (14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,205,0);
INSERT INTO ezurlalias VALUES (15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0);
INSERT INTO ezurlalias VALUES (16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0);
INSERT INTO ezurlalias VALUES (17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0);
INSERT INTO ezurlalias VALUES (18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0);
INSERT INTO ezurlalias VALUES (19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,0);
INSERT INTO ezurlalias VALUES (20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0);
INSERT INTO ezurlalias VALUES (21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0);
INSERT INTO ezurlalias VALUES (22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0);
INSERT INTO ezurlalias VALUES (23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0);
INSERT INTO ezurlalias VALUES (24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0);
INSERT INTO ezurlalias VALUES (25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0);
INSERT INTO ezurlalias VALUES (26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0);
INSERT INTO ezurlalias VALUES (27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0);
INSERT INTO ezurlalias VALUES (83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0);
INSERT INTO ezurlalias VALUES (29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0);
INSERT INTO ezurlalias VALUES (125,'discussions/music_discussion/latest_msg_not_sticky','1980b453976fed108ef2874bac0f8477','content/view/full/130',1,0,0);
INSERT INTO ezurlalias VALUES (126,'discussions/music_discussion/not_sticky_2','06916ca78017a7482957aa4997f66664','content/view/full/131',1,0,0);
INSERT INTO ezurlalias VALUES (34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,179,0);
INSERT INTO ezurlalias VALUES (124,'discussions/music_discussion/new_topic_sticky/reply','ae271e634c8d9cb077913b222e4b9d17','content/view/full/129',1,0,0);
INSERT INTO ezurlalias VALUES (121,'news/news_bulletin','9365952d8950c12f923a3a48e5e27fa3','content/view/full/126',1,178,0);
INSERT INTO ezurlalias VALUES (122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,157,0);
INSERT INTO ezurlalias VALUES (123,'discussions/music_discussion/new_topic_sticky','493ae5ad7ceb46af67edfdaf244d047a','content/view/full/128',1,0,0);
INSERT INTO ezurlalias VALUES (99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,179,0);
INSERT INTO ezurlalias VALUES (90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0);
INSERT INTO ezurlalias VALUES (93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,179,0);
INSERT INTO ezurlalias VALUES (87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0);
INSERT INTO ezurlalias VALUES (88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0);
INSERT INTO ezurlalias VALUES (89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0);
INSERT INTO ezurlalias VALUES (59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0);
INSERT INTO ezurlalias VALUES (60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0);
INSERT INTO ezurlalias VALUES (61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0);
INSERT INTO ezurlalias VALUES (127,'discussions/music_discussion/important_sticky','5b25f18de9f5bafe8050dafdaa759fca','content/view/full/132',1,0,0);
INSERT INTO ezurlalias VALUES (65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0);
INSERT INTO ezurlalias VALUES (66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0);
INSERT INTO ezurlalias VALUES (84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0);
INSERT INTO ezurlalias VALUES (69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0);
INSERT INTO ezurlalias VALUES (71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0);
INSERT INTO ezurlalias VALUES (72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0);
INSERT INTO ezurlalias VALUES (73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0);
INSERT INTO ezurlalias VALUES (102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0);
INSERT INTO ezurlalias VALUES (78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0);
INSERT INTO ezurlalias VALUES (79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0);
INSERT INTO ezurlalias VALUES (80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0);
INSERT INTO ezurlalias VALUES (82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1);
INSERT INTO ezurlalias VALUES (86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0);
INSERT INTO ezurlalias VALUES (202,'products/dvd/music_dvd','d0cc19fd0f214acc42b83c7a8aefc0ca','content/view/full/190',1,0,0);
INSERT INTO ezurlalias VALUES (105,'discussions/music_discussion/this_is_a_new_topic','2344619129cdcf0b057b66b259d43a86','content/view/full/112',1,0,0);
INSERT INTO ezurlalias VALUES (106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1);
INSERT INTO ezurlalias VALUES (117,'discussions/music_discussion/this_is_a_new_topic/foo_bar','8ccf76d178398a5021594b8dcc111ef3','content/view/full/122',1,0,0);
INSERT INTO ezurlalias VALUES (178,'news/news_bulletin_october','4bb330d0024e02fb3954cbd69fca08c8','content/view/full/126',1,0,0);
INSERT INTO ezurlalias VALUES (111,'discussions/sports_discussion/football','687ae615eecb9131ce8600e02f087921','content/view/full/119',1,0,0);
INSERT INTO ezurlalias VALUES (149,'hardware','3ca14c518d1bf901acc339e7c9cd6d7f','content/view/full/154',1,162,0);
INSERT INTO ezurlalias VALUES (113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1);
INSERT INTO ezurlalias VALUES (115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,179,0);
INSERT INTO ezurlalias VALUES (114,'discussions/music_discussion/this_is_a_new_topic/my_reply','295c0cf1dfb0786654b87ae7879269ce','content/view/full/120',1,0,0);
INSERT INTO ezurlalias VALUES (118,'discussions/music_discussion/what_about_pop','29e6fdc68db2a2820a4198ccf9606316','content/view/full/123',1,0,0);
INSERT INTO ezurlalias VALUES (119,'discussions/music_discussion/reply_wanted_for_this_topic','659797091633ef0b16807a67d6594e12','content/view/full/124',1,0,0);
INSERT INTO ezurlalias VALUES (120,'discussions/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','cd75b5016b43b7dec4c22e911c98b00f','content/view/full/125',1,0,0);
INSERT INTO ezurlalias VALUES (128,'discussions/sports_discussion/football/reply_2','b99ca3fa56d5010fd9e2edb25c6c723c','content/view/full/133',1,0,0);
INSERT INTO ezurlalias VALUES (130,'discussions/music_discussion/lkj_ssssstick','515b0805b631e2e60f5a01a62078aafd','content/view/full/135',1,0,0);
INSERT INTO ezurlalias VALUES (131,'discussions/music_discussion/foo','c30b12e11f43e38e5007e437eb28f7fc','content/view/full/136',1,0,0);
INSERT INTO ezurlalias VALUES (132,'discussions/music_discussion/lkj_ssssstick/reply','b81320d415f41d95b962b73d36e2c248','content/view/full/137',1,0,0);
INSERT INTO ezurlalias VALUES (135,'discussions/music_discussion/lkj_ssssstick/uyuiyui','c560e70f61e30defc917cf5fd1824831','content/view/full/140',1,0,0);
INSERT INTO ezurlalias VALUES (136,'discussions/music_discussion/test2','79a4b87fad6297c89e32fcda0fdeadef','content/view/full/141',1,0,0);
INSERT INTO ezurlalias VALUES (137,'discussions/music_discussion/t4','a411ba84550a8808aa017d46d7f61899','content/view/full/142',1,0,0);
INSERT INTO ezurlalias VALUES (138,'discussions/music_discussion/lkj_ssssstick/klj_jkl_klj','ad8b440b5c57fce9f5ae28d271b2b629','content/view/full/143',1,0,0);
INSERT INTO ezurlalias VALUES (139,'discussions/music_discussion/test2/retest2','8e0e854c6f944f7b1fd9676c37258634','content/view/full/144',1,0,0);
INSERT INTO ezurlalias VALUES (141,'discussions/music_discussion/lkj_ssssstick/my_reply','d0d8e13f8fc3f4d24ff7223c02bcd26d','content/view/full/146',1,0,0);
INSERT INTO ezurlalias VALUES (142,'discussions/music_discussion/lkj_ssssstick/retest','b9924edb42d7cb24b5b7ff0b3ae8d1f4','content/view/full/147',1,0,0);
INSERT INTO ezurlalias VALUES (194,'products/books','9958a314b65536ff546916425fa22a11','content/view/full/183',1,0,0);
INSERT INTO ezurlalias VALUES (144,'discussions/music_discussion/hjg_dghsdjgf','c9b3ef4c7c4cca6eacfc0d2e0a88747d','content/view/full/149',1,0,0);
INSERT INTO ezurlalias VALUES (146,'discussions/music_discussion/hjg_dghsdjgf/dfghd_fghklj','3353f2cdd52889bdf18d0071c9b3c85b','content/view/full/151',1,0,0);
INSERT INTO ezurlalias VALUES (195,'products/flowers','cc38cceec70ad9af57cced5d4c58fa5b','content/view/full/184',1,196,0);
INSERT INTO ezurlalias VALUES (196,'products/cars','fc536e4f1775ed51adf2031587023b4d','content/view/full/184',1,0,0);
INSERT INTO ezurlalias VALUES (197,'products/dvd','a16e9023bb6d9e8aa8c7d11f62e253a6','content/view/full/185',1,0,0);
INSERT INTO ezurlalias VALUES (192,'products/pc/monitor/lcd_15','b16aa924d05760f61f84112e426b38b2','content/view/full/181',1,0,0);
INSERT INTO ezurlalias VALUES (157,'shipping_and_returns','b6e6c30236fd41d3623ad5cb6ac2bf7d','content/view/full/127',1,0,0);
INSERT INTO ezurlalias VALUES (158,'privacy_notice','8c8c68c20b331d0f4781cc125b98e700','content/view/full/160',1,0,0);
INSERT INTO ezurlalias VALUES (159,'conditions_of_use','53214a466568707294398ecd56b4f788','content/view/full/161',1,0,0);
INSERT INTO ezurlalias VALUES (162,'products','86024cad1e83101d97359d7351051156','content/view/full/154',1,0,0);
INSERT INTO ezurlalias VALUES (161,'contact_us__1','54f33014a45dc127271f59d0ff3e01f7','content/view/full/163',1,187,0);
INSERT INTO ezurlalias VALUES (163,'hardware/*','24c7f0cd68f9143e5c13f759ea1b90bd','products/{1}',1,0,1);
INSERT INTO ezurlalias VALUES (199,'products/cars/ferrari','c6c1a725181a4780250d7fa0985544b3','content/view/full/187',1,0,0);
INSERT INTO ezurlalias VALUES (200,'products/books/summer_book','def473e58410352709df09917f5a1e63','content/view/full/188',1,0,0);
INSERT INTO ezurlalias VALUES (201,'products/books/ez_publish_basics','446ea3e82e8051d9f20746649e5362cd','content/view/full/189',1,0,0);
INSERT INTO ezurlalias VALUES (193,'products/mac/g101_power/jkhjkhjk','1465cf924b4a2dd75c5c3776b06c76a7','content/view/full/182',1,0,0);
INSERT INTO ezurlalias VALUES (168,'setup/look_and_feel/my_shop','dcc2fb6a7ef4778e4058de4a202ab95b','content/view/full/54',1,179,0);
INSERT INTO ezurlalias VALUES (169,'products/good','82f4dd56a317e99f2eda1145cb607304','content/view/full/168',1,170,0);
INSERT INTO ezurlalias VALUES (170,'products/mac/g101_power/good','1e4239d6d550a1c88cd4dc0fae5b7166','content/view/full/168',1,0,0);
INSERT INTO ezurlalias VALUES (171,'products/mac/g101_power/the_best_expansion_pack','552f8240650ade100313e3aa98a2b59c','content/view/full/169',1,0,0);
INSERT INTO ezurlalias VALUES (172,'products/mac/g101_power/whimper','be70879cfc363dc87db87dc9566c94a2','content/view/full/170',1,0,0);
INSERT INTO ezurlalias VALUES (173,'products/mac/g101_power/an_utter_disappointment','43fb8f8283cb725684b087662dccc6ce','content/view/full/171',1,0,0);
INSERT INTO ezurlalias VALUES (174,'products/mac/g101_power/asdfasdf','fe58e7ded6f19d069ca9abb1328aae66','content/view/full/172',1,0,0);
INSERT INTO ezurlalias VALUES (179,'setup/look_and_feel/shop','10a4300fdb27b6b751340b62b885d81c','content/view/full/54',1,0,0);
INSERT INTO ezurlalias VALUES (180,'news/news_bulletin_november','ffa2ee44cb666a55101adbeaeeb6ad9f','content/view/full/176',1,0,0);
INSERT INTO ezurlalias VALUES (203,'products/dvd/action_dvd','5d10b5e5c295c9fdeef3b8e19249fe2a','content/view/full/191',1,0,0);
INSERT INTO ezurlalias VALUES (182,'products/nokia_g101/*','fa18fd0bc938fd540fb4fe1d4c61fe3e','products/g101_power/{1}',1,0,1);
INSERT INTO ezurlalias VALUES (198,'products/cars/troll','f3cfa9f08cc14cd729e5ebfc72266f3e','content/view/full/186',1,0,0);
INSERT INTO ezurlalias VALUES (184,'products/cords','fc6cde66dac794b6b8cb58a48d2b77e4','content/view/full/177',1,0,0);
INSERT INTO ezurlalias VALUES (185,'products/cords/1_meter_cord','4c1c1e669e3bbadd5512da3955bcd24c','content/view/full/178',1,0,0);
INSERT INTO ezurlalias VALUES (186,'products/cords/5_meter_cord','3a3c9627da21e9894a91e36776cd268a','content/view/full/179',1,0,0);
INSERT INTO ezurlalias VALUES (187,'contact_us','53a2c328fefc1efd85d75137a9d833ab','content/view/full/163',1,0,0);
INSERT INTO ezurlalias VALUES (188,'news/a_new_cord','d18e23d7b8ca3336b29913b22e8eb076','content/view/full/180',1,0,0);
INSERT INTO ezurlalias VALUES (191,'products/g101_power/*','4f3ac3a16a32af7773e43e879f7562f0','products/mac/g101_power/{1}',1,0,1);
INSERT INTO ezurlalias VALUES (204,'users/anonymous_users','3ae1aac958e1c82013689d917d34967a','content/view/full/192',1,0,0);
INSERT INTO ezurlalias VALUES (205,'users/anonymous_users/anonymous_user','aad93975f09371695ba08292fd9698db','content/view/full/11',1,0,0);
INSERT INTO ezurlalias VALUES (206,'setup/common_ini_settings','e501fe6c81ed14a5af2b322d248102d8','content/view/full/193',1,0,0);
INSERT INTO ezurlalias VALUES (207,'setup/common_ini_settings/common_ini_settings','51580fac4a0d382aede57bc43af6e63a','content/view/full/194',1,0,0);
INSERT INTO ezurlalias VALUES (208,'setup/setup_links/common_ini_settings','e85ca643d417d1d3b7459bc4eef7a1f0','content/view/full/195',1,0,0);

--
-- Table structure for table 'ezuser'
--

CREATE TABLE ezuser (
  contentobject_id int(11) NOT NULL default '0',
  login varchar(150) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  password_hash_type int(11) NOT NULL default '1',
  password_hash varchar(50) default NULL,
  PRIMARY KEY  (contentobject_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser'
--


INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');
INSERT INTO ezuser VALUES (108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3');
INSERT INTO ezuser VALUES (109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c');
INSERT INTO ezuser VALUES (130,'','',2,'4ccb7125baf19de015388c99893fbb4d');
INSERT INTO ezuser VALUES (246,'','',1,'');
INSERT INTO ezuser VALUES (187,'','',1,'');
INSERT INTO ezuser VALUES (189,'','',1,'');
INSERT INTO ezuser VALUES (243,'','',1,'');
INSERT INTO ezuser VALUES (244,'','',1,'');
INSERT INTO ezuser VALUES (245,'','',1,'');
INSERT INTO ezuser VALUES (247,'','',1,'');
INSERT INTO ezuser VALUES (248,'','',1,'');
INSERT INTO ezuser VALUES (249,'','',1,'');

--
-- Table structure for table 'ezuser_accountkey'
--

CREATE TABLE ezuser_accountkey (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  hash_key varchar(32) NOT NULL default '',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser_accountkey'
--


INSERT INTO ezuser_accountkey VALUES (1,154,'837e17025d6b3a340cfb305769caa30d',1068042835);
INSERT INTO ezuser_accountkey VALUES (2,188,'281ca20cd4d47e3f3be239f6e587df70',1068110661);
INSERT INTO ezuser_accountkey VALUES (3,197,'6a92e8886841440681b58a699e69d4dc',1068112344);

--
-- Table structure for table 'ezuser_discountrule'
--

CREATE TABLE ezuser_discountrule (
  id int(11) NOT NULL auto_increment,
  discountrule_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser_discountrule'
--



--
-- Table structure for table 'ezuser_role'
--

CREATE TABLE ezuser_role (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ezuser_role_contentobject_id (contentobject_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser_role'
--


INSERT INTO ezuser_role VALUES (29,1,10);
INSERT INTO ezuser_role VALUES (25,2,12);
INSERT INTO ezuser_role VALUES (28,1,11);
INSERT INTO ezuser_role VALUES (34,1,13);

--
-- Table structure for table 'ezuser_setting'
--

CREATE TABLE ezuser_setting (
  user_id int(11) NOT NULL default '0',
  is_enabled int(1) NOT NULL default '0',
  max_login int(11) default NULL,
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser_setting'
--


INSERT INTO ezuser_setting VALUES (10,1,1000);
INSERT INTO ezuser_setting VALUES (14,1,10);
INSERT INTO ezuser_setting VALUES (23,1,0);
INSERT INTO ezuser_setting VALUES (40,1,0);
INSERT INTO ezuser_setting VALUES (107,1,0);
INSERT INTO ezuser_setting VALUES (108,1,0);
INSERT INTO ezuser_setting VALUES (109,1,0);
INSERT INTO ezuser_setting VALUES (111,1,0);
INSERT INTO ezuser_setting VALUES (130,1,0);
INSERT INTO ezuser_setting VALUES (149,1,0);
INSERT INTO ezuser_setting VALUES (154,0,0);
INSERT INTO ezuser_setting VALUES (187,1,0);
INSERT INTO ezuser_setting VALUES (188,0,0);
INSERT INTO ezuser_setting VALUES (189,1,0);
INSERT INTO ezuser_setting VALUES (197,0,0);
INSERT INTO ezuser_setting VALUES (198,1,0);
INSERT INTO ezuser_setting VALUES (206,1,0);
INSERT INTO ezuser_setting VALUES (239,1,0);
INSERT INTO ezuser_setting VALUES (243,1,0);
INSERT INTO ezuser_setting VALUES (244,1,0);
INSERT INTO ezuser_setting VALUES (245,1,0);
INSERT INTO ezuser_setting VALUES (246,1,0);
INSERT INTO ezuser_setting VALUES (247,1,0);
INSERT INTO ezuser_setting VALUES (248,1,0);
INSERT INTO ezuser_setting VALUES (249,1,0);

--
-- Table structure for table 'ezvattype'
--

CREATE TABLE ezvattype (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  percentage float default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezvattype'
--


INSERT INTO ezvattype VALUES (1,'Std',0);

--
-- Table structure for table 'ezview_counter'
--

CREATE TABLE ezview_counter (
  node_id int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezview_counter'
--



--
-- Table structure for table 'ezwaituntildatevalue'
--

CREATE TABLE ezwaituntildatevalue (
  id int(11) NOT NULL auto_increment,
  workflow_event_id int(11) NOT NULL default '0',
  workflow_event_version int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  contentclass_attribute_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id,workflow_event_id,workflow_event_version),
  KEY ezwaituntildateevalue_wf_ev_id_wf_ver (workflow_event_id,workflow_event_version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezwaituntildatevalue'
--



--
-- Table structure for table 'ezwishlist'
--

CREATE TABLE ezwishlist (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezwishlist'
--



--
-- Table structure for table 'ezworkflow'
--

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

--
-- Dumping data for table 'ezworkflow'
--



--
-- Table structure for table 'ezworkflow_assign'
--

CREATE TABLE ezworkflow_assign (
  id int(11) NOT NULL auto_increment,
  workflow_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  access_type int(11) NOT NULL default '0',
  as_tree int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_assign'
--



--
-- Table structure for table 'ezworkflow_event'
--

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

--
-- Dumping data for table 'ezworkflow_event'
--



--
-- Table structure for table 'ezworkflow_group'
--

CREATE TABLE ezworkflow_group (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_group'
--


INSERT INTO ezworkflow_group VALUES (1,'Standard',14,14,1024392098,1024392098);

--
-- Table structure for table 'ezworkflow_group_link'
--

CREATE TABLE ezworkflow_group_link (
  workflow_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  workflow_version int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (workflow_id,group_id,workflow_version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_group_link'
--


INSERT INTO ezworkflow_group_link VALUES (1,1,0,'Standard');

--
-- Table structure for table 'ezworkflow_process'
--

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

--
-- Dumping data for table 'ezworkflow_process'
--



