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


INSERT INTO ezcontentbrowserecent VALUES (1,14,44,1076578917,'Setup');
INSERT INTO ezcontentbrowserecent VALUES (2,14,122,1076578960,'Common ini settings');
INSERT INTO ezcontentbrowserecent VALUES (3,14,46,1076579082,'Setup links');

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
INSERT INTO ezcontentclass VALUES (4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1066916721);
INSERT INTO ezcontentclass VALUES (5,0,'Image','image','<name>',8,14,1031484992,1048494784);
INSERT INTO ezcontentclass VALUES (10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353);
INSERT INTO ezcontentclass VALUES (12,0,'File','file','<name>',14,14,1052385472,1052385669);
INSERT INTO ezcontentclass VALUES (14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885);
INSERT INTO ezcontentclass VALUES (15,0,'Template look','template_look','<title>',14,14,1066390045,1069412609);
INSERT INTO ezcontentclass VALUES (19,0,'Feedback form','feedback_form','<name>',14,14,1068027045,1068027439);
INSERT INTO ezcontentclass VALUES (20,0,'Common ini settings','common_ini_settings','<name>',14,14,1076578779,1076578869);

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
INSERT INTO ezcontentclass_attribute VALUES (160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute VALUES (187,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute VALUES (188,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (181,0,19,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (182,0,19,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (183,0,19,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute VALUES (185,0,19,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute VALUES (184,0,19,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1);
INSERT INTO ezcontentclass_attribute VALUES (159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1);
INSERT INTO ezcontentclass_attribute VALUES (12,0,4,'user_account','User account','ezuser',1,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (201,0,20,'imagelarge','Image Large Size','ezinisetting',0,0,13,6,0,0,0,0,0,0,0,'image.ini','large','Filters','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (200,0,20,'imagemedium','Image Medium Size','ezinisetting',0,0,12,6,0,0,0,0,0,0,0,'image.ini','medium','Filters','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (199,0,20,'imagesmall','Image Small Size','ezinisetting',0,0,11,6,0,0,0,0,0,0,0,'image.ini','small','Filters','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (198,0,20,'templatecompile','Template Compile','ezinisetting',0,0,10,2,0,0,0,0,0,0,0,'site.ini','TemplateSettings','TemplateCompile','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (197,0,20,'templatecache','Template Cache','ezinisetting',0,0,9,2,0,0,0,0,0,0,0,'site.ini','TemplateSettings','TemplateCache','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (196,0,20,'viewcaching','View Caching','ezinisetting',0,0,8,2,0,0,0,0,0,0,0,'site.ini','ContentSettings','ViewCaching','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (195,0,20,'debugredirection','Debug Redirection','ezinisetting',0,0,7,2,0,0,0,0,0,0,0,'site.ini','DebugSettings','DebugRedirection','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (194,0,20,'debugiplist','Debug IP List','ezinisetting',0,0,6,6,0,0,0,0,0,0,0,'site.ini','DebugSettings','DebugIPList','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (193,0,20,'debugbyip','Debug By IP','ezinisetting',0,0,5,2,0,0,0,0,0,0,0,'site.ini','DebugSettings','DebugByIP','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (192,0,20,'debugoutput','Debug Output','ezinisetting',0,0,4,2,0,0,0,0,0,0,0,'site.ini','DebugSettings','DebugOutput','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (191,0,20,'defaultpage','Default Page','ezinisetting',0,0,3,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','DefaultPage','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);
INSERT INTO ezcontentclass_attribute VALUES (189,0,20,'name','Name','ezstring',1,0,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
INSERT INTO ezcontentclass_attribute VALUES (190,0,20,'indexpage','Index Page','ezinisetting',0,0,2,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','IndexPage','8;9','override;admin;blog_admin;blog_user;forum_user;forum_admin;news_user;news_admin;corporate_user;corporate_admin;gallery_user;gallery_admin;intranet_user;intranet_admin;shop_user;shop_admin;plain',0,1);

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
INSERT INTO ezcontentclass_classgroup VALUES (19,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (19,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (20,0,4,'Setup');

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


INSERT INTO ezcontentobject VALUES (1,14,1,1,'Corporate',4,0,1033917596,1069762950,1,'');
INSERT INTO ezcontentobject VALUES (4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject VALUES (10,14,2,4,'Anonymous User',2,0,1033920665,1072180818,1,'');
INSERT INTO ezcontentobject VALUES (11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject VALUES (12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject VALUES (13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject VALUES (14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL);
INSERT INTO ezcontentobject VALUES (41,14,3,1,'Media',1,0,1060695457,1060695457,1,'');
INSERT INTO ezcontentobject VALUES (42,14,11,1,'Setup',1,0,1066383068,1066383068,1,'');
INSERT INTO ezcontentobject VALUES (43,14,11,14,'Classes',8,0,1066384365,1067950307,1,'');
INSERT INTO ezcontentobject VALUES (44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,'');
INSERT INTO ezcontentobject VALUES (45,14,11,14,'Look and feel',9,0,1066388816,1067950326,1,'');
INSERT INTO ezcontentobject VALUES (46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,'');
INSERT INTO ezcontentobject VALUES (47,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (122,14,1,5,'New Image',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (49,14,4,1,'News',1,0,1066398020,1066398020,1,'');
INSERT INTO ezcontentobject VALUES (51,14,1,14,'New Setup link',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (53,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (56,14,11,15,'Corporate',62,0,1066643397,1069840811,1,'');
INSERT INTO ezcontentobject VALUES (58,14,4,1,'Business news',1,0,1066729196,1066729196,1,'');
INSERT INTO ezcontentobject VALUES (59,14,4,1,'Off topic',1,0,1066729211,1066729211,1,'');
INSERT INTO ezcontentobject VALUES (60,14,4,1,'Reports',2,0,1066729226,1066729241,1,'');
INSERT INTO ezcontentobject VALUES (61,14,4,1,'Staff news',1,0,1066729258,1066729258,1,'');
INSERT INTO ezcontentobject VALUES (135,14,1,1,'General info',2,0,1067936571,1069757266,1,'');
INSERT INTO ezcontentobject VALUES (136,14,1,10,'About',5,0,1067937053,1069757111,1,'');
INSERT INTO ezcontentobject VALUES (137,14,1,19,'Contact us',4,0,1068027382,1069761690,1,'');
INSERT INTO ezcontentobject VALUES (138,14,4,2,'New website',1,0,1069755162,1069755162,1,'');
INSERT INTO ezcontentobject VALUES (129,14,1,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (144,14,1,10,'Support',1,0,1069757581,1069757581,1,'');
INSERT INTO ezcontentobject VALUES (127,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (142,14,1,10,'Career',1,0,1069757199,1069757199,1,'');
INSERT INTO ezcontentobject VALUES (143,14,1,10,'Shop info',1,0,1069757424,1069757424,1,'');
INSERT INTO ezcontentobject VALUES (83,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (84,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (85,14,5,1,'New Folder',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (88,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (140,14,1,10,'PublishABC',1,0,1069756410,1069756410,1,'');
INSERT INTO ezcontentobject VALUES (139,14,1,10,'Top 100 set',1,0,1069756326,1069756326,1,'');
INSERT INTO ezcontentobject VALUES (91,14,1,15,'New Template look',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (92,14,4,2,'Live from Top fair 2003',6,0,1066828821,1069755437,1,'');
INSERT INTO ezcontentobject VALUES (94,14,4,2,'Mr Smith joined us',3,0,1066829047,1069755309,1,'');
INSERT INTO ezcontentobject VALUES (96,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (126,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (145,14,1,10,'Development',1,0,1069757729,1069757729,1,'');
INSERT INTO ezcontentobject VALUES (103,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (104,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (105,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (106,14,2,4,'New User',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (133,14,1,1,'Products',1,0,1067872500,1067872500,1,'');
INSERT INTO ezcontentobject VALUES (134,14,1,1,'Services',1,0,1067872529,1067872529,1,'');
INSERT INTO ezcontentobject VALUES (115,14,11,14,'Cache',3,0,1066991725,1067950265,1,'');
INSERT INTO ezcontentobject VALUES (116,14,11,14,'URL translator',2,0,1066992054,1067950343,1,'');
INSERT INTO ezcontentobject VALUES (117,14,4,2,'New Article',1,0,0,0,0,'');
INSERT INTO ezcontentobject VALUES (146,14,2,3,'Anonymous Users',1,0,1072180743,1072180743,1,'');
INSERT INTO ezcontentobject VALUES (147,14,11,1,'Common ini settings',1,0,1076578917,1076578917,1,'');
INSERT INTO ezcontentobject VALUES (148,14,11,20,'Common ini settings',2,0,1076578960,1076579114,1,'');
INSERT INTO ezcontentobject VALUES (149,14,11,14,'Common ini settings',1,0,1076579082,1076579082,1,'');

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
INSERT INTO ezcontentobject_attribute VALUES (28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute VALUES (98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage');
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage');
INSERT INTO ezcontentobject_attribute VALUES (157,'eng-GB',1,58,4,'Business news',0,0,0,0,'business news','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (158,'eng-GB',1,58,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (159,'eng-GB',1,59,4,'Off topic',0,0,0,0,'off topic','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (160,'eng-GB',1,59,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (161,'eng-GB',2,60,4,'Reports',0,0,0,0,'reports','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (162,'eng-GB',2,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (163,'eng-GB',1,61,4,'Staff news',0,0,0,0,'staff news','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (164,'eng-GB',1,61,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (251,'eng-GB',6,92,1,'Live from Top fair 2003',0,0,0,0,'live from top fair 2003','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage');
INSERT INTO ezcontentobject_attribute VALUES (154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (476,'eng-GB',1,139,140,'Top 100 set',0,0,0,0,'top 100 set','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (477,'eng-GB',1,139,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A collection of music from the year 2003. The best of the best. All top of the charts from Top 100. </paragraph>\n  <paragraph>Mona will be smarting from the lacklustre chart position of her new album &apos;Up and Go&apos;. It&apos;s come in at No. 234 when surely it should have snagged the top spot. Fellow babe July will be smarting too, with her new CD manages a poor No. 343. But for Tim Tim, whose new album &apos;InOn&apos; doesn&apos;t even manage to scrape into the top 20. The once mighty seem fragile. Meanwhile someone who&apos;s reputation has been seeming really fragile is Joap Jackson, and he romps to No. 1.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (478,'eng-GB',1,139,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"top_100_set.\"\n         suffix=\"\"\n         basename=\"top_100_set\"\n         dirpath=\"var/corporate/storage/images/products/top_100_set/478-1-eng-GB\"\n         url=\"var/corporate/storage/images/products/top_100_set/478-1-eng-GB/top_100_set.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069756112\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (255,'eng-GB',6,92,123,'',0,0,0,0,'','ezboolean');
INSERT INTO ezcontentobject_attribute VALUES (276,'eng-GB',6,92,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute VALUES (254,'eng-GB',6,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"live_from_top_fair_2003.\"\n         suffix=\"\"\n         basename=\"live_from_top_fair_2003\"\n         dirpath=\"var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB\"\n         url=\"var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB/live_from_top_fair_2003.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"254\"\n            attribute_version=\"5\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (422,'eng-GB',1,133,4,'Products',0,0,0,0,'products','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (423,'eng-GB',1,133,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find information about our products.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (424,'eng-GB',1,134,4,'Services',0,0,0,0,'services','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (425,'eng-GB',1,134,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about our services.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (481,'eng-GB',1,140,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"publishabc.\"\n         suffix=\"\"\n         basename=\"publishabc\"\n         dirpath=\"var/corporate/storage/images/products/publishabc/481-1-eng-GB\"\n         url=\"var/corporate/storage/images/products/publishabc/481-1-eng-GB/publishabc.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069756339\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (479,'eng-GB',1,140,140,'PublishABC',0,0,0,0,'publishabc','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (480,'eng-GB',1,140,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>PublishABC is an open source content management system (CMS) and development framework.</paragraph>\n  <paragraph>With advanced functionality for e-commerce sites, news-sites, forums, picture galleries, intranets and much more, you can build your dynamic websites fast and reliable. PublishABC is a very flexible system for everyone that wants to share their information on the web.</paragraph>\n  <paragraph>With PublishABC you can easily create, edit and publish all sorts of content and the day-to-day work can easily be done by non-technical persons.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (253,'eng-GB',6,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Four crew members are on-site from the 20th to the 24th reporting live from the hall. The following text contains a live report from the fair.</paragraph>\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</paragraph>\n  <paragraph>Speaking of community, we&apos;re happy that the community show up. It is always interesting and inspiring to meet face to face and to discuss various topics. We certainly hope that more community people will show up during the rest of the week.</paragraph>\n  <paragraph>Anyway, we were talking about the benefits of open and free software. As mentioned, some people still don&apos;t get it; however, when explained, we&apos;re met by replies such as:</paragraph>\n  <paragraph>&quot;Amazing!&quot; - big smile...</paragraph>\n  <paragraph>&quot;I would have to pay a lot of money for this feature from company...&quot;</paragraph>\n  <paragraph>- from a guy who came to us from one of the neighboring stands (right after watching a presentation there).</paragraph>\n  <paragraph>Some companies are just interested in talking to potential customers who are willing to spend millions on rigid solutions. This is not our policy. We&apos;re very flexible and eager to talk to a wide range of people. If you have the chance visit the fair, feel free to stop by. Our stand is open and prepared for everyone. Anybody can sit down together with our representatives and receive an on-site, hands-on demonstration of the system.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (252,'eng-GB',6,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This week, some members of the crew are reporting live from Hall A, attending &quot;Top fair 2003&quot;. Top fair 2003 is an international trade fair for Information Technology and Telecommunications. The trade fair is held for the 5th time.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (150,'eng-GB',62,56,157,'Corporate',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (262,'eng-GB',3,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Mr Smith started today at our firm. He will be in charge of the computer matrix. We hired him from his previous workplace at Nemos place.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (261,'eng-GB',3,94,1,'Mr Smith joined us',0,0,0,0,'mr smith joined us','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (265,'eng-GB',3,94,123,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute VALUES (278,'eng-GB',3,94,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute VALUES (264,'eng-GB',3,94,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"mr_smith_joined_us.\"\n         suffix=\"\"\n         basename=\"mr_smith_joined_us\"\n         dirpath=\"var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB\"\n         url=\"var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB/mr_smith_joined_us.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (263,'eng-GB',3,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>His name is Mr Smith and I hope you all welcome him into our ranks.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (152,'eng-GB',62,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate.gif\"\n         original_filename=\"corporate.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069839369\">\n  <original attribute_id=\"152\"\n            attribute_version=\"61\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069839370\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069839370\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069844204\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (474,'eng-GB',1,138,123,'',1,0,0,1,'','ezboolean');
INSERT INTO ezcontentobject_attribute VALUES (470,'eng-GB',1,138,1,'New website',0,0,0,0,'new website','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (471,'eng-GB',1,138,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We have now released our new website. I hope that it is easier to find iformation about the company and what we offer from now on.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (472,'eng-GB',1,138,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The feedback we have gotten so far indicates this. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (473,'eng-GB',1,138,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_website.\"\n         suffix=\"\"\n         basename=\"new_website\"\n         dirpath=\"var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB\"\n         url=\"var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB/new_website.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069755091\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',62,56,160,'corporate_blue',0,0,0,0,'corporate_blue','ezpackage');
INSERT INTO ezcontentobject_attribute VALUES (154,'eng-GB',62,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (475,'eng-GB',1,138,177,'',0,0,0,0,'','ezinteger');
INSERT INTO ezcontentobject_attribute VALUES (151,'eng-GB',62,56,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (428,'eng-GB',5,136,140,'About',0,0,0,0,'about','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (429,'eng-GB',5,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n  <paragraph>My company is located in Skien, Norway with 223 employees. My company was founded in May 1973, in Skien, Norway,</paragraph>\n  <paragraph>Corporate Vision</paragraph>\n  <paragraph>&quot;We shall be an open minded, dedicated team helping people and businesses around the world to share information and knowledge&quot;.</paragraph>\n  <paragraph>\n    <line>Corporate Values</line>\n    <line>Open - We shall always meet the world with an open mind and an open heart, always welcoming other people, ideas and knowledge.</line>\n  </paragraph>\n  <paragraph>Sharing - We shall share our information, ideas and knowledge and pull together as a team, both internally and together with the community. Together we will accomplish great things.</paragraph>\n  <paragraph>Innovative - We shall be innovative people creating innovative solutions.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (430,'eng-GB',5,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/corporate/storage/images/information/about/430-5-eng-GB\"\n         url=\"var/corporate/storage/images/information/about/430-5-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"430\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (484,'eng-GB',1,142,140,'Career',0,0,0,0,'career','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (485,'eng-GB',1,142,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We are now hiring the following</paragraph>\n  <paragraph>System developers </paragraph>\n  <paragraph>You will be part of the eZ development crew, developing our products. You will also be part of customer projects either as a project leader and/or developer. Very good programming skills are required. You must know object oriented programming and design using C++ and PHP. You should be familiar with UML, SQL, XML, XHTML, SOAP/XML-RPC and Linux/Unix. Experience with the Qt Toolkit is a plus. Experience from open source projects is a plus. Fresh graduates may also apply, if you have very good programming knowledge. </paragraph>\n  <paragraph>\n    <line>Applications will be accepted continually.</line>\n    <line>Place of work: Skien, Norway.</line>\n    <line>Conditions: Depending on qualifications.</line>\n    <line>Very good English skills are required. Other languages is a plus.</line>\n  </paragraph>\n  <paragraph>\n    <line>Questions and applications with CV&apos;s are to be sent by e-mail to:</line>\n    <line>The Boss</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (486,'eng-GB',1,142,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"career.\"\n         suffix=\"\"\n         basename=\"career\"\n         dirpath=\"var/corporate/storage/images/information/career/486-1-eng-GB\"\n         url=\"var/corporate/storage/images/information/career/486-1-eng-GB/career.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757141\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (426,'eng-GB',2,135,4,'General info',0,0,0,0,'general info','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (427,'eng-GB',2,135,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find information about this company.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',4,1,4,'Corporate',0,0,0,0,'corporate','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to the website of MyCompany. Here you can read about our company, our products and services. Take a tour through our digitised archive, and find out more about the comapny and what we offer. </paragraph>\n  <paragraph>Our mission is to keep our customers in touch with the latest updates, releases and products.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (487,'eng-GB',1,143,140,'Shop info',0,0,0,0,'shop info','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (488,'eng-GB',1,143,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We are committed to your satisfaction. We will do everything we can to make a present or potential customer a satisfied customer. </paragraph>\n  <paragraph>On these pages we will outline our terms &amp; conditions and your rights and privacy as a customer.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (489,'eng-GB',1,143,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"shop_info.\"\n         suffix=\"\"\n         basename=\"shop_info\"\n         dirpath=\"var/corporate/storage/images/general_info/shop_info/489-1-eng-GB\"\n         url=\"var/corporate/storage/images/general_info/shop_info/489-1-eng-GB/shop_info.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757397\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (493,'eng-GB',1,145,140,'Development',0,0,0,0,'development','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (494,'eng-GB',1,145,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Use the crew for developments and enhancements </paragraph>\n  <paragraph>Hire a guy to help you with your solution. We and our friends have highly skilled developers ready to advice you. Consulting ranges from feature requests, installation help, upgrade help, migration, integration and solutions. </paragraph>\n  <paragraph>Often we help with installation, migration, integration, programming etc. We can also deliver a turn key web solution based on PublishABC. Let us know what we can do for you. Our standard hourly rate is $ 129 and our minimum asking price for projects is $ 2344. </paragraph>\n  <paragraph>Contact us if there is something we can do for you.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (495,'eng-GB',1,145,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"development.\"\n         suffix=\"\"\n         basename=\"development\"\n         dirpath=\"var/corporate/storage/images/services/development/495-1-eng-GB\"\n         url=\"var/corporate/storage/images/services/development/495-1-eng-GB/development.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757596\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (492,'eng-GB',1,144,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"support.\"\n         suffix=\"\"\n         basename=\"support\"\n         dirpath=\"var/corporate/storage/images/services/support/492-1-eng-GB\"\n         url=\"var/corporate/storage/images/services/support/492-1-eng-GB/support.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757493\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (490,'eng-GB',1,144,140,'Support',0,0,0,0,'support','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (491,'eng-GB',1,144,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>&quot; Use the crew for support - first hand information&quot; </paragraph>\n  <paragraph>To guarantee our customers the best possible result we offer a support program. The professionals are ready to help you with your problem. </paragraph>\n  <paragraph>\n    <line>What you will get with support</line>\n    <line>The support will cover answers by email and phone. If you need help to configure or develop features, we can help you doing that directly on your server, or as a new feature to a distribution on PublishABC. We will also be able to give advise on how to solve problems with development in PublishABC, if you want to do most of the work yourself.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (441,'eng-GB',4,137,181,'Contact us',0,0,0,0,'contact us','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (442,'eng-GB',4,137,182,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Fill in the form below if you have any feedback. Please remember to fill in your e-mail address.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (443,'eng-GB',4,137,183,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (445,'eng-GB',4,137,185,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (444,'eng-GB',4,137,184,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (437,'eng-GB',62,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (461,'eng-GB',62,56,187,'ez.no',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (468,'eng-GB',62,56,188,'Copyright &copy; eZ systems as 1999-2004',0,0,0,0,'copyright &copy; ez systems as 1999-2004','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (496,'eng-GB',1,146,6,'Anonymous Users',0,0,0,0,'anonymous users','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (497,'eng-GB',1,146,7,'User group for the anonymous user',0,0,0,0,'user group for the anonymous user','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (19,'eng-GB',2,10,8,'Anonymous',0,0,0,0,'anonymous','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (20,'eng-GB',2,10,9,'User',0,0,0,0,'user','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (21,'eng-GB',2,10,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute VALUES (498,'eng-GB',1,147,4,'Common ini settings',0,0,0,0,'common ini settings','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (499,'eng-GB',1,147,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (500,'eng-GB',1,148,189,'Common ini settings',0,0,0,0,'common ini settings','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (501,'eng-GB',1,148,190,'/content/view/full/2/',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (502,'eng-GB',1,148,191,'/content/view/full/2/',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (503,'eng-GB',1,148,192,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (504,'eng-GB',1,148,193,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (505,'eng-GB',1,148,194,'=',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (506,'eng-GB',1,148,195,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (507,'eng-GB',1,148,196,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (508,'eng-GB',1,148,197,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (509,'eng-GB',1,148,198,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (510,'eng-GB',1,148,199,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (511,'eng-GB',1,148,200,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (512,'eng-GB',1,148,201,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (513,'eng-GB',1,149,152,'Common ini settings',0,0,0,0,'common ini settings','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (514,'eng-GB',1,149,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"common_ini_settings1.png\"\n         suffix=\"png\"\n         basename=\"common_ini_settings1\"\n         dirpath=\"var/corporate/storage/images-versioned/514/1-eng-GB\"\n         url=\"var/corporate/storage/images-versioned/514/1-eng-GB/common_ini_settings1.png\"\n         original_filename=\"exec.png\"\n         mime_type=\"image/png\"\n         width=\"32\"\n         height=\"32\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1076579082\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"common_ini_settings1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images-versioned/514/1-eng-GB\"\n         url=\"var/corporate/storage/images-versioned/514/1-eng-GB/common_ini_settings1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"32\"\n         height=\"32\"\n         alias_key=\"183954394\"\n         timestamp=\"1076579083\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"common_ini_settings1_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images-versioned/514/1-eng-GB\"\n         url=\"var/corporate/storage/images-versioned/514/1-eng-GB/common_ini_settings1_medium.png\"\n         mime_type=\"image/png\"\n         width=\"32\"\n         height=\"32\"\n         alias_key=\"472385770\"\n         timestamp=\"1076579083\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"common_ini_settings1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images-versioned/514/1-eng-GB\"\n         url=\"var/corporate/storage/images-versioned/514/1-eng-GB/common_ini_settings1_large.png\"\n         mime_type=\"image/png\"\n         width=\"32\"\n         height=\"32\"\n         alias_key=\"-958410206\"\n         timestamp=\"1076579095\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
INSERT INTO ezcontentobject_attribute VALUES (515,'eng-GB',1,149,154,'',0,0,0,0,'','eztext');
INSERT INTO ezcontentobject_attribute VALUES (516,'eng-GB',1,149,155,'content/edit/148',0,0,0,0,'content/edit/148','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (500,'eng-GB',2,148,189,'Common ini settings',0,0,0,0,'common ini settings','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (501,'eng-GB',2,148,190,'/content/view/full/2/',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (502,'eng-GB',2,148,191,'/content/view/full/2/',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (503,'eng-GB',2,148,192,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (504,'eng-GB',2,148,193,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (505,'eng-GB',2,148,194,'=',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (506,'eng-GB',2,148,195,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (507,'eng-GB',2,148,196,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (508,'eng-GB',2,148,197,'enabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (509,'eng-GB',2,148,198,'disabled',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (510,'eng-GB',2,148,199,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (511,'eng-GB',2,148,200,'',0,0,0,0,'','ezinisetting');
INSERT INTO ezcontentobject_attribute VALUES (512,'eng-GB',2,148,201,'',0,0,0,0,'','ezinisetting');

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
INSERT INTO ezcontentobject_name VALUES (126,'New Article',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_name VALUES (58,'Business news',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (59,'Off topic',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (60,'Reports',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (60,'Reports',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (61,'Staff news',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (137,'Contact us',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',36,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (135,'Information',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (136,'About',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranetyy',30,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (136,'About',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (134,'Services',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',25,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',24,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (127,'New Article',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_name VALUES (92,'eZ systems - reporting live from Munich',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (94,'Mr xxx joined us',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',39,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (92,'eZ systems - reporting live from Munich',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (96,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (133,'Products',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (92,'eZ systems - reporting live from Munich',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (92,'eZ systems - reporting live from Munich',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',34,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranet',20,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (94,'Mr xxx joined us',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (103,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (104,'New User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (137,'Contact us',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_name VALUES (129,'New Article',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Intranetyy',29,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',41,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',42,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',40,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',43,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',44,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (92,'eZ systems - reporting live from Munich',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',45,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',46,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',48,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',47,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',51,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',53,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',54,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',55,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',56,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',58,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',59,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',60,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',57,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (136,'About',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (138,'New website',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (94,'Mr Smith joined us',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (92,'Live from Top fair 2003',6,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (139,'Top 100 set',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (140,'PublishABC',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Corporate',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (137,'Contact us',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (1,'Corporate',3,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (136,'About',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (136,'About',5,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (142,'Career',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (135,'General info',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',62,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (143,'Shop info',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (56,'Corporate',61,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (144,'Support',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (145,'Development',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (137,'Contact us',4,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (146,'Anonymous Users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (10,'Anonymous User',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (147,'Common ini settings',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (148,'Common ini settings',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (149,'Common ini settings',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (148,'Common ini settings',2,'eng-GB','eng-GB');

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


INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1,1076579114);
INSERT INTO ezcontentobject_tree VALUES (2,1,1,4,1,1,'/1/2/',8,1,0,'',2,1069762950);
INSERT INTO ezcontentobject_tree VALUES (5,1,4,1,0,1,'/1/5/',1,1,0,'users',5,1072180818);
INSERT INTO ezcontentobject_tree VALUES (12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12,1033920746);
INSERT INTO ezcontentobject_tree VALUES (13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13,1033920830);
INSERT INTO ezcontentobject_tree VALUES (14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14,1033920794);
INSERT INTO ezcontentobject_tree VALUES (15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15,1033920830);
INSERT INTO ezcontentobject_tree VALUES (43,1,41,1,1,1,'/1/43/',9,1,0,'media',43,1060695457);
INSERT INTO ezcontentobject_tree VALUES (44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44,1076579114);
INSERT INTO ezcontentobject_tree VALUES (45,46,43,8,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45,1067950307);
INSERT INTO ezcontentobject_tree VALUES (46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46,1076579082);
INSERT INTO ezcontentobject_tree VALUES (47,46,45,9,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47,1067950326);
INSERT INTO ezcontentobject_tree VALUES (48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48,1069840811);
INSERT INTO ezcontentobject_tree VALUES (50,2,49,1,1,2,'/1/2/50/',9,1,1,'news',50,1069755437);
INSERT INTO ezcontentobject_tree VALUES (54,48,56,62,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/corporate',54,1069840811);
INSERT INTO ezcontentobject_tree VALUES (56,50,58,1,1,3,'/1/2/50/56/',9,1,0,'news/business_news',56,1069755437);
INSERT INTO ezcontentobject_tree VALUES (57,50,59,1,1,3,'/1/2/50/57/',9,1,0,'news/off_topic',57,1069755162);
INSERT INTO ezcontentobject_tree VALUES (58,50,60,2,1,3,'/1/2/50/58/',9,1,0,'news/reports',58,1069755437);
INSERT INTO ezcontentobject_tree VALUES (59,50,61,1,1,3,'/1/2/50/59/',9,1,0,'news/staff_news',59,1069755309);
INSERT INTO ezcontentobject_tree VALUES (81,56,92,6,1,4,'/1/2/50/56/81/',9,1,0,'news/business_news/live_from_top_fair_2003',81,1069755437);
INSERT INTO ezcontentobject_tree VALUES (83,59,94,3,1,4,'/1/2/50/59/83/',9,1,0,'news/staff_news/mr_smith_joined_us',83,1069755309);
INSERT INTO ezcontentobject_tree VALUES (95,46,115,3,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95,1067950265);
INSERT INTO ezcontentobject_tree VALUES (96,46,116,2,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96,1067950343);
INSERT INTO ezcontentobject_tree VALUES (106,2,133,1,1,2,'/1/2/106/',9,1,2,'products',106,1069756410);
INSERT INTO ezcontentobject_tree VALUES (107,2,134,1,1,2,'/1/2/107/',9,1,3,'services',107,1069757729);
INSERT INTO ezcontentobject_tree VALUES (108,2,135,2,1,2,'/1/2/108/',9,1,4,'general_info',108,1069757424);
INSERT INTO ezcontentobject_tree VALUES (109,108,136,5,1,3,'/1/2/108/109/',9,1,0,'general_info/about',109,1069757111);
INSERT INTO ezcontentobject_tree VALUES (110,2,137,4,1,2,'/1/2/110/',9,1,5,'contact_us',110,1069761690);
INSERT INTO ezcontentobject_tree VALUES (111,57,138,1,1,4,'/1/2/50/57/111/',9,1,0,'news/off_topic/new_website',111,1069755162);
INSERT INTO ezcontentobject_tree VALUES (112,58,92,6,1,4,'/1/2/50/58/112/',9,1,0,'news/reports/live_from_top_fair_2003',81,1069755437);
INSERT INTO ezcontentobject_tree VALUES (113,106,139,1,1,3,'/1/2/106/113/',9,1,0,'products/top_100_set',113,1069756326);
INSERT INTO ezcontentobject_tree VALUES (114,106,140,1,1,3,'/1/2/106/114/',9,1,0,'products/publishabc',114,1069756410);
INSERT INTO ezcontentobject_tree VALUES (116,108,142,1,1,3,'/1/2/108/116/',9,1,0,'general_info/career',116,1069757199);
INSERT INTO ezcontentobject_tree VALUES (117,108,143,1,1,3,'/1/2/108/117/',9,1,0,'general_info/shop_info',117,1069757424);
INSERT INTO ezcontentobject_tree VALUES (118,107,144,1,1,3,'/1/2/107/118/',9,1,0,'services/support',118,1069757581);
INSERT INTO ezcontentobject_tree VALUES (119,107,145,1,1,3,'/1/2/107/119/',9,1,0,'services/development',119,1069757729);
INSERT INTO ezcontentobject_tree VALUES (120,5,146,1,1,2,'/1/5/120/',9,1,0,'users/anonymous_users',120,1072180818);
INSERT INTO ezcontentobject_tree VALUES (121,120,10,2,1,3,'/1/5/120/121/',9,1,0,'users/anonymous_users/anonymous_user',121,1072180818);
INSERT INTO ezcontentobject_tree VALUES (122,44,147,1,1,2,'/1/44/122/',9,1,0,'setup/common_ini_settings',122,1076579114);
INSERT INTO ezcontentobject_tree VALUES (123,122,148,2,1,3,'/1/44/122/123/',9,1,0,'setup/common_ini_settings/common_ini_settings',123,1076579114);
INSERT INTO ezcontentobject_tree VALUES (124,46,149,1,1,3,'/1/44/46/124/',9,1,0,'setup/setup_links/common_ini_settings',124,1076579082);

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
INSERT INTO ezcontentobject_version VALUES (442,14,14,1,1033920808,1033920830,1,0,0);
INSERT INTO ezcontentobject_version VALUES (472,41,14,1,1060695450,1060695457,1,0,0);
INSERT INTO ezcontentobject_version VALUES (473,42,14,1,1066383039,1066383068,1,0,0);
INSERT INTO ezcontentobject_version VALUES (475,44,14,1,1066384403,1066384457,1,0,0);
INSERT INTO ezcontentobject_version VALUES (482,46,14,2,1066389882,1066389902,1,0,0);
INSERT INTO ezcontentobject_version VALUES (718,139,14,1,1069756109,1069756326,1,0,0);
INSERT INTO ezcontentobject_version VALUES (735,56,14,62,1069839663,1069840810,1,0,0);
INSERT INTO ezcontentobject_version VALUES (490,49,14,1,1066398007,1066398020,1,0,0);
INSERT INTO ezcontentobject_version VALUES (717,92,14,6,1069755358,1069755437,1,0,0);
INSERT INTO ezcontentobject_version VALUES (520,58,14,1,1066729186,1066729195,1,0,0);
INSERT INTO ezcontentobject_version VALUES (521,59,14,1,1066729202,1066729210,1,0,0);
INSERT INTO ezcontentobject_version VALUES (523,60,14,2,1066729234,1066729241,1,0,0);
INSERT INTO ezcontentobject_version VALUES (524,61,14,1,1066729249,1066729258,1,0,0);
INSERT INTO ezcontentobject_version VALUES (730,144,14,1,1069757491,1069757581,1,0,0);
INSERT INTO ezcontentobject_version VALUES (719,140,14,1,1069756337,1069756410,1,0,0);
INSERT INTO ezcontentobject_version VALUES (683,45,14,9,1067950316,1067950326,1,0,0);
INSERT INTO ezcontentobject_version VALUES (682,43,14,8,1067950294,1067950307,1,0,0);
INSERT INTO ezcontentobject_version VALUES (681,115,14,3,1067950253,1067950265,1,0,0);
INSERT INTO ezcontentobject_version VALUES (716,94,14,3,1069755194,1069755309,1,0,0);
INSERT INTO ezcontentobject_version VALUES (725,142,14,1,1069757139,1069757199,1,0,0);
INSERT INTO ezcontentobject_version VALUES (724,136,14,5,1069757094,1069757111,1,0,0);
INSERT INTO ezcontentobject_version VALUES (728,143,14,1,1069757395,1069757424,1,0,0);
INSERT INTO ezcontentobject_version VALUES (726,135,14,2,1069757215,1069757265,1,0,0);
INSERT INTO ezcontentobject_version VALUES (684,116,14,2,1067950335,1067950343,1,0,0);
INSERT INTO ezcontentobject_version VALUES (674,134,14,1,1067872510,1067872528,1,0,0);
INSERT INTO ezcontentobject_version VALUES (733,1,14,4,1069762220,1069762950,1,1,0);
INSERT INTO ezcontentobject_version VALUES (732,137,14,4,1069761671,1069761690,1,0,0);
INSERT INTO ezcontentobject_version VALUES (731,145,14,1,1069757594,1069757729,1,0,0);
INSERT INTO ezcontentobject_version VALUES (673,133,14,1,1067872484,1067872500,1,0,0);
INSERT INTO ezcontentobject_version VALUES (715,138,14,1,1069755089,1069755162,1,0,0);
INSERT INTO ezcontentobject_version VALUES (736,146,14,1,1072180699,1072180743,1,0,0);
INSERT INTO ezcontentobject_version VALUES (737,10,14,2,1072180749,1072180818,1,0,0);
INSERT INTO ezcontentobject_version VALUES (738,147,14,1,1076578899,1076578917,1,0,0);
INSERT INTO ezcontentobject_version VALUES (739,148,14,1,1076578937,1076578959,3,0,0);
INSERT INTO ezcontentobject_version VALUES (740,149,14,1,1076578994,1076579081,1,0,0);
INSERT INTO ezcontentobject_version VALUES (741,148,14,2,1076579101,1076579114,1,0,0);

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


INSERT INTO ezimagefile VALUES (1,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate.gif');
INSERT INTO ezimagefile VALUES (2,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile VALUES (3,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile VALUES (4,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile VALUES (5,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate.gif');
INSERT INTO ezimagefile VALUES (6,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile VALUES (7,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile VALUES (8,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile VALUES (9,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate.gif');
INSERT INTO ezimagefile VALUES (10,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile VALUES (11,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile VALUES (12,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile VALUES (13,430,'var/corporate/storage/images/information/about/430-3-eng-GB/about.');
INSERT INTO ezimagefile VALUES (14,473,'var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB/new_website.');
INSERT INTO ezimagefile VALUES (15,264,'var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB/mr_smith_joined_us.');
INSERT INTO ezimagefile VALUES (16,254,'var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB/live_from_top_fair_2003.');
INSERT INTO ezimagefile VALUES (17,478,'var/corporate/storage/images/products/top_100_set/478-1-eng-GB/top_100_set.');
INSERT INTO ezimagefile VALUES (18,481,'var/corporate/storage/images/products/publishabc/481-1-eng-GB/publishabc.');
INSERT INTO ezimagefile VALUES (19,430,'var/corporate/storage/images/about/430-4-eng-GB/about.');
INSERT INTO ezimagefile VALUES (20,430,'var/corporate/storage/images/information/about/430-5-eng-GB/about.');
INSERT INTO ezimagefile VALUES (21,486,'var/corporate/storage/images/information/career/486-1-eng-GB/career.');
INSERT INTO ezimagefile VALUES (22,489,'var/corporate/storage/images/general_info/shop_info/489-1-eng-GB/shop_info.');
INSERT INTO ezimagefile VALUES (23,492,'var/corporate/storage/images/services/support/492-1-eng-GB/support.');
INSERT INTO ezimagefile VALUES (24,495,'var/corporate/storage/images/services/development/495-1-eng-GB/development.');
INSERT INTO ezimagefile VALUES (26,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate.gif');
INSERT INTO ezimagefile VALUES (27,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile VALUES (28,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile VALUES (29,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile VALUES (30,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate.gif');
INSERT INTO ezimagefile VALUES (31,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_reference.gif');
INSERT INTO ezimagefile VALUES (32,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_medium.gif');
INSERT INTO ezimagefile VALUES (33,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_logo.gif');
INSERT INTO ezimagefile VALUES (34,109,'var/storage/original/image/phpvzmRGW.png');
INSERT INTO ezimagefile VALUES (35,109,'var/storage/original/image/phppIJtoa.jpg');
INSERT INTO ezimagefile VALUES (36,109,'var/storage/original/image/phpAhcEu9.png');
INSERT INTO ezimagefile VALUES (37,103,'var/storage/original/image/phpWJgae7.png');
INSERT INTO ezimagefile VALUES (38,109,'var/storage/original/image/phpbVfzkm.png');
INSERT INTO ezimagefile VALUES (39,103,'var/storage/original/image/php7ZhvcB.png');
INSERT INTO ezimagefile VALUES (40,103,'var/storage/original/image/phpXz5esv.jpg');
INSERT INTO ezimagefile VALUES (41,514,'var/corporate/storage/images-versioned/514/1-eng-GB/common_ini_settings1.png');
INSERT INTO ezimagefile VALUES (42,514,'var/corporate/storage/images/setup/setup_links/common_ini_settings/514-1-eng-GB/common_ini_settings.png');
INSERT INTO ezimagefile VALUES (43,514,'var/corporate/storage/images-versioned/514/1-eng-GB/common_ini_settings1_reference.png');
INSERT INTO ezimagefile VALUES (44,514,'var/corporate/storage/images-versioned/514/1-eng-GB/common_ini_settings1_medium.png');
INSERT INTO ezimagefile VALUES (45,514,'var/corporate/storage/images-versioned/514/1-eng-GB/common_ini_settings1_large.png');

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
INSERT INTO eznode_assignment VALUES (147,210,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (146,209,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (148,9,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (150,11,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (151,12,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (152,13,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (153,14,1,13,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (182,41,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (183,42,1,1,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (185,44,1,44,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (193,46,2,44,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (443,56,62,48,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (201,49,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (230,58,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (420,92,6,56,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (441,1,4,1,8,1,1,0,0);
INSERT INTO eznode_assignment VALUES (231,59,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (233,60,2,50,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (234,61,1,50,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (437,144,1,107,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (423,140,1,106,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (386,45,9,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (385,43,8,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (384,115,3,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (377,134,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (432,142,1,108,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (419,94,3,59,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (431,136,5,108,9,1,1,2,0);
INSERT INTO eznode_assignment VALUES (433,135,2,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (421,92,6,58,9,1,0,0,0);
INSERT INTO eznode_assignment VALUES (435,143,1,108,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (387,116,2,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (422,139,1,106,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (440,137,4,2,9,1,1,115,0);
INSERT INTO eznode_assignment VALUES (438,145,1,107,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (376,133,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (418,138,1,57,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (444,146,1,5,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (446,10,2,120,9,1,1,-1,0);
INSERT INTO eznode_assignment VALUES (447,147,1,44,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (448,148,1,122,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (449,149,1,46,9,1,1,0,0);
INSERT INTO eznode_assignment VALUES (450,148,2,122,9,1,1,0,0);

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


INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',147,1,0,0,'','','','');
INSERT INTO eznotificationevent VALUES (2,0,'ezpublish',148,1,0,0,'','','','');
INSERT INTO eznotificationevent VALUES (3,0,'ezpublish',149,1,0,0,'','','','');
INSERT INTO eznotificationevent VALUES (4,0,'ezpublish',148,2,0,0,'','','','');

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
INSERT INTO ezpolicy VALUES (370,24,'create','content','');
INSERT INTO ezpolicy VALUES (371,24,'create','content','');
INSERT INTO ezpolicy VALUES (372,24,'create','content','');
INSERT INTO ezpolicy VALUES (341,8,'read','content','*');
INSERT INTO ezpolicy VALUES (369,24,'read','content','*');
INSERT INTO ezpolicy VALUES (373,24,'create','content','');
INSERT INTO ezpolicy VALUES (374,24,'edit','content','');
INSERT INTO ezpolicy VALUES (380,1,'read','content','');
INSERT INTO ezpolicy VALUES (379,1,'login','user','*');

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


INSERT INTO ezpolicy_limitation VALUES (289,371,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation VALUES (290,371,'Section',0,'create','content');
INSERT INTO ezpolicy_limitation VALUES (288,370,'Section',0,'create','content');
INSERT INTO ezpolicy_limitation VALUES (287,370,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation VALUES (291,372,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation VALUES (292,372,'Section',0,'create','content');
INSERT INTO ezpolicy_limitation VALUES (293,373,'Class',0,'create','content');
INSERT INTO ezpolicy_limitation VALUES (294,373,'Section',0,'create','content');
INSERT INTO ezpolicy_limitation VALUES (295,374,'Class',0,'edit','content');
INSERT INTO ezpolicy_limitation VALUES (300,380,'Class',0,'read','content');

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


INSERT INTO ezpolicy_limitation_value VALUES (555,291,'12');
INSERT INTO ezpolicy_limitation_value VALUES (554,291,'1');
INSERT INTO ezpolicy_limitation_value VALUES (551,289,'16');
INSERT INTO ezpolicy_limitation_value VALUES (550,288,'4');
INSERT INTO ezpolicy_limitation_value VALUES (548,287,'13');
INSERT INTO ezpolicy_limitation_value VALUES (549,287,'2');
INSERT INTO ezpolicy_limitation_value VALUES (553,290,'5');
INSERT INTO ezpolicy_limitation_value VALUES (552,289,'17');
INSERT INTO ezpolicy_limitation_value VALUES (547,287,'1');
INSERT INTO ezpolicy_limitation_value VALUES (556,292,'6');
INSERT INTO ezpolicy_limitation_value VALUES (557,293,'6');
INSERT INTO ezpolicy_limitation_value VALUES (558,293,'7');
INSERT INTO ezpolicy_limitation_value VALUES (559,294,'7');
INSERT INTO ezpolicy_limitation_value VALUES (560,295,'1');
INSERT INTO ezpolicy_limitation_value VALUES (561,295,'2');
INSERT INTO ezpolicy_limitation_value VALUES (562,295,'6');
INSERT INTO ezpolicy_limitation_value VALUES (563,295,'7');
INSERT INTO ezpolicy_limitation_value VALUES (564,295,'12');
INSERT INTO ezpolicy_limitation_value VALUES (565,295,'13');
INSERT INTO ezpolicy_limitation_value VALUES (566,295,'16');
INSERT INTO ezpolicy_limitation_value VALUES (567,295,'17');
INSERT INTO ezpolicy_limitation_value VALUES (568,295,'18');
INSERT INTO ezpolicy_limitation_value VALUES (591,300,'12');
INSERT INTO ezpolicy_limitation_value VALUES (590,300,'10');
INSERT INTO ezpolicy_limitation_value VALUES (589,300,'5');
INSERT INTO ezpolicy_limitation_value VALUES (588,300,'2');
INSERT INTO ezpolicy_limitation_value VALUES (587,300,'1');
INSERT INTO ezpolicy_limitation_value VALUES (592,300,'19');

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
INSERT INTO ezrole VALUES (24,0,'Intranet',NULL);

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


INSERT INTO ezsearch_object_word_link VALUES (13249,133,2696,0,0,0,2696,1,1067872500,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13250,133,2696,0,1,2696,2697,1,1067872500,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13251,133,2697,0,2,2696,2698,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13252,133,2698,0,3,2697,2699,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13253,133,2699,0,4,2698,2700,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13254,133,2700,0,5,2699,2701,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13255,133,2701,0,6,2700,2702,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13256,133,2702,0,7,2701,2703,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13257,133,2703,0,8,2702,2696,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13258,133,2696,0,9,2703,2697,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13259,133,2697,0,10,2696,2698,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13260,133,2698,0,11,2697,2699,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13261,133,2699,0,12,2698,2700,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13262,133,2700,0,13,2699,2701,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13263,133,2701,0,14,2700,2702,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13264,133,2702,0,15,2701,2703,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13265,133,2703,0,16,2702,2696,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13266,133,2696,0,17,2703,0,1,1067872500,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13267,139,2704,0,0,0,2705,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13268,139,2705,0,1,2704,2706,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13269,139,2706,0,2,2705,2704,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13270,139,2704,0,3,2706,2705,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13271,139,2705,0,4,2704,2706,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13272,139,2706,0,5,2705,2707,10,1069756326,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13273,139,2707,0,6,2706,2708,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13274,139,2708,0,7,2707,2709,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13275,139,2709,0,8,2708,2710,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13276,139,2710,0,9,2709,2711,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13277,139,2711,0,10,2710,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13278,139,2712,0,11,2711,2713,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13279,139,2713,0,12,2712,2714,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13280,139,2714,0,13,2713,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13281,139,2712,0,14,2714,2715,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13282,139,2715,0,15,2712,2709,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13283,139,2709,0,16,2715,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13284,139,2712,0,17,2709,2715,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13285,139,2715,0,18,2712,2716,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13286,139,2716,0,19,2715,2704,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13287,139,2704,0,20,2716,2709,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13288,139,2709,0,21,2704,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13289,139,2712,0,22,2709,2717,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13290,139,2717,0,23,2712,2711,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13291,139,2711,0,24,2717,2704,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13292,139,2704,0,25,2711,2705,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13293,139,2705,0,26,2704,2718,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13294,139,2718,0,27,2705,2699,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13295,139,2699,0,28,2718,2719,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13296,139,2719,0,29,2699,2720,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13297,139,2720,0,30,2719,2711,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13298,139,2711,0,31,2720,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13299,139,2712,0,32,2711,2721,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13300,139,2721,0,33,2712,2722,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13301,139,2722,0,34,2721,2723,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13302,139,2723,0,35,2722,2709,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13303,139,2709,0,36,2723,2724,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13304,139,2724,0,37,2709,2725,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13305,139,2725,0,38,2724,2726,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13306,139,2726,0,39,2725,2727,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13307,139,2727,0,40,2726,2728,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13308,139,2728,0,41,2727,2729,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13309,139,2729,0,42,2728,2730,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13310,139,2730,0,43,2729,2731,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13311,139,2731,0,44,2730,2732,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13312,139,2732,0,45,2731,2733,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13313,139,2733,0,46,2732,2734,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13314,139,2734,0,47,2733,2735,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13315,139,2735,0,48,2734,2736,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13316,139,2736,0,49,2735,2737,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13317,139,2737,0,50,2736,2738,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13318,139,2738,0,51,2737,2730,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13319,139,2730,0,52,2738,2739,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13320,139,2739,0,53,2730,2740,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13321,139,2740,0,54,2739,2741,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13322,139,2741,0,55,2740,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13323,139,2712,0,56,2741,2704,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13324,139,2704,0,57,2712,2742,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13325,139,2742,0,58,2704,2743,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13326,139,2743,0,59,2742,2744,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13327,139,2744,0,60,2743,2745,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13328,139,2745,0,61,2744,2699,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13329,139,2699,0,62,2745,2719,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13330,139,2719,0,63,2699,2720,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13331,139,2720,0,64,2719,2746,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13332,139,2746,0,65,2720,2747,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13333,139,2747,0,66,2746,2724,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13334,139,2724,0,67,2747,2725,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13335,139,2725,0,68,2724,2748,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13336,139,2748,0,69,2725,2749,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13337,139,2749,0,70,2748,2707,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13338,139,2707,0,71,2749,2750,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13339,139,2750,0,72,2707,2735,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13340,139,2735,0,73,2750,2751,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13341,139,2751,0,74,2735,2752,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13342,139,2752,0,75,2751,2753,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13343,139,2753,0,76,2752,2754,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13344,139,2754,0,77,2753,2754,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13345,139,2754,0,78,2754,2755,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13346,139,2755,0,79,2754,2725,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13347,139,2725,0,80,2755,2726,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13348,139,2726,0,81,2725,2756,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13349,139,2756,0,82,2726,2757,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13350,139,2757,0,83,2756,2758,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13351,139,2758,0,84,2757,2759,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13352,139,2759,0,85,2758,2760,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13353,139,2760,0,86,2759,2761,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13354,139,2761,0,87,2760,2762,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13355,139,2762,0,88,2761,2763,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13356,139,2763,0,89,2762,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13357,139,2712,0,90,2763,2704,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13358,139,2704,0,91,2712,2764,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13359,139,2764,0,92,2704,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13360,139,2712,0,93,2764,2765,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13361,139,2765,0,94,2712,2766,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13362,139,2766,0,95,2765,2767,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13363,139,2767,0,96,2766,2768,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13364,139,2768,0,97,2767,2769,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13365,139,2769,0,98,2768,2770,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13366,139,2770,0,99,2769,2771,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13367,139,2771,0,100,2770,2731,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13368,139,2731,0,101,2771,2772,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13369,139,2772,0,102,2731,2773,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13370,139,2773,0,103,2772,2774,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13371,139,2774,0,104,2773,2775,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13372,139,2775,0,105,2774,2776,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13373,139,2776,0,106,2775,2768,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13374,139,2768,0,107,2776,2777,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13375,139,2777,0,108,2768,2778,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13376,139,2778,0,109,2777,2779,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13377,139,2779,0,110,2778,2728,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13378,139,2728,0,111,2779,2780,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13379,139,2780,0,112,2728,2781,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13380,139,2781,0,113,2780,2761,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13381,139,2761,0,114,2781,2735,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13382,139,2735,0,115,2761,2782,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13383,139,2782,0,116,2735,2707,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13384,139,2707,0,117,2782,2708,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13385,139,2708,0,118,2707,2709,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13386,139,2709,0,119,2708,2710,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13387,139,2710,0,120,2709,2711,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13388,139,2711,0,121,2710,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13389,139,2712,0,122,2711,2713,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13390,139,2713,0,123,2712,2714,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13391,139,2714,0,124,2713,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13392,139,2712,0,125,2714,2715,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13393,139,2715,0,126,2712,2709,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13394,139,2709,0,127,2715,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13395,139,2712,0,128,2709,2715,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13396,139,2715,0,129,2712,2716,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13397,139,2716,0,130,2715,2704,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13398,139,2704,0,131,2716,2709,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13399,139,2709,0,132,2704,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13400,139,2712,0,133,2709,2717,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13401,139,2717,0,134,2712,2711,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13402,139,2711,0,135,2717,2704,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13403,139,2704,0,136,2711,2705,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13404,139,2705,0,137,2704,2718,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13405,139,2718,0,138,2705,2699,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13406,139,2699,0,139,2718,2719,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13407,139,2719,0,140,2699,2720,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13408,139,2720,0,141,2719,2711,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13409,139,2711,0,142,2720,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13410,139,2712,0,143,2711,2721,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13411,139,2721,0,144,2712,2722,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13412,139,2722,0,145,2721,2723,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13413,139,2723,0,146,2722,2709,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13414,139,2709,0,147,2723,2724,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13415,139,2724,0,148,2709,2725,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13416,139,2725,0,149,2724,2726,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13417,139,2726,0,150,2725,2727,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13418,139,2727,0,151,2726,2728,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13419,139,2728,0,152,2727,2729,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13420,139,2729,0,153,2728,2730,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13421,139,2730,0,154,2729,2731,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13422,139,2731,0,155,2730,2732,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13423,139,2732,0,156,2731,2733,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13424,139,2733,0,157,2732,2734,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13425,139,2734,0,158,2733,2735,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13426,139,2735,0,159,2734,2736,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13427,139,2736,0,160,2735,2737,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13428,139,2737,0,161,2736,2738,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13429,139,2738,0,162,2737,2730,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13430,139,2730,0,163,2738,2739,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13431,139,2739,0,164,2730,2740,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13432,139,2740,0,165,2739,2741,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13433,139,2741,0,166,2740,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13434,139,2712,0,167,2741,2704,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13435,139,2704,0,168,2712,2742,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13436,139,2742,0,169,2704,2743,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13437,139,2743,0,170,2742,2744,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13438,139,2744,0,171,2743,2745,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13439,139,2745,0,172,2744,2699,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13440,139,2699,0,173,2745,2719,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13441,139,2719,0,174,2699,2720,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13442,139,2720,0,175,2719,2746,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13443,139,2746,0,176,2720,2747,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13444,139,2747,0,177,2746,2724,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13445,139,2724,0,178,2747,2725,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13446,139,2725,0,179,2724,2748,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13447,139,2748,0,180,2725,2749,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13448,139,2749,0,181,2748,2707,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13449,139,2707,0,182,2749,2750,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13450,139,2750,0,183,2707,2735,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13451,139,2735,0,184,2750,2751,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13452,139,2751,0,185,2735,2752,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13453,139,2752,0,186,2751,2753,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13454,139,2753,0,187,2752,2754,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13455,139,2754,0,188,2753,2754,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13456,139,2754,0,189,2754,2755,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13457,139,2755,0,190,2754,2725,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13458,139,2725,0,191,2755,2726,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13459,139,2726,0,192,2725,2756,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13460,139,2756,0,193,2726,2757,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13461,139,2757,0,194,2756,2758,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13462,139,2758,0,195,2757,2759,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13463,139,2759,0,196,2758,2760,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13464,139,2760,0,197,2759,2761,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13465,139,2761,0,198,2760,2762,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13466,139,2762,0,199,2761,2763,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13467,139,2763,0,200,2762,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13468,139,2712,0,201,2763,2704,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13469,139,2704,0,202,2712,2764,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13470,139,2764,0,203,2704,2712,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13471,139,2712,0,204,2764,2765,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13472,139,2765,0,205,2712,2766,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13473,139,2766,0,206,2765,2767,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13474,139,2767,0,207,2766,2768,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13475,139,2768,0,208,2767,2769,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13476,139,2769,0,209,2768,2770,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13477,139,2770,0,210,2769,2771,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13478,139,2771,0,211,2770,2731,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13479,139,2731,0,212,2771,2772,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13480,139,2772,0,213,2731,2773,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13481,139,2773,0,214,2772,2774,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13482,139,2774,0,215,2773,2775,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13483,139,2775,0,216,2774,2776,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13484,139,2776,0,217,2775,2768,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13485,139,2768,0,218,2776,2777,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13486,139,2777,0,219,2768,2778,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13487,139,2778,0,220,2777,2779,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13488,139,2779,0,221,2778,2728,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13489,139,2728,0,222,2779,2780,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13490,139,2780,0,223,2728,2781,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13491,139,2781,0,224,2780,2761,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13492,139,2761,0,225,2781,2735,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13493,139,2735,0,226,2761,2782,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13494,139,2782,0,227,2735,0,10,1069756326,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13495,140,2783,0,0,0,2783,10,1069756410,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13496,140,2783,0,1,2783,2783,10,1069756410,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13497,140,2783,0,2,2783,2777,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13498,140,2777,0,3,2783,2784,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13499,140,2784,0,4,2777,2785,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13500,140,2785,0,5,2784,2786,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13501,140,2786,0,6,2785,2787,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13502,140,2787,0,7,2786,2788,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13503,140,2788,0,8,2787,2789,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13504,140,2789,0,9,2788,2790,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13505,140,2790,0,10,2789,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13506,140,2728,0,11,2790,2791,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13507,140,2791,0,12,2728,2792,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13508,140,2792,0,13,2791,2747,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13509,140,2747,0,14,2792,2793,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13510,140,2793,0,15,2747,2794,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13511,140,2794,0,16,2793,2753,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13512,140,2753,0,17,2794,2795,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13513,140,2795,0,18,2753,2796,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13514,140,2796,0,19,2795,2797,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13515,140,2797,0,20,2796,2798,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13516,140,2798,0,21,2797,2797,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13517,140,2797,0,22,2798,2799,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13518,140,2799,0,23,2797,2800,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13519,140,2800,0,24,2799,2801,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13520,140,2801,0,25,2800,2802,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13521,140,2802,0,26,2801,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13522,140,2728,0,27,2802,2803,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13523,140,2803,0,28,2728,2804,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13524,140,2804,0,29,2803,2698,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13525,140,2698,0,30,2804,2805,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13526,140,2805,0,31,2698,2806,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13527,140,2806,0,32,2805,2807,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13528,140,2807,0,33,2806,2808,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13529,140,2808,0,34,2807,2809,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13530,140,2809,0,35,2808,2810,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13531,140,2810,0,36,2809,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13532,140,2728,0,37,2810,2811,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13533,140,2811,0,38,2728,2783,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13534,140,2783,0,39,2811,2777,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13535,140,2777,0,40,2783,2707,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13536,140,2707,0,41,2777,2812,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13537,140,2812,0,42,2707,2813,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13538,140,2813,0,43,2812,2789,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13539,140,2789,0,44,2813,2753,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13540,140,2753,0,45,2789,2814,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13541,140,2814,0,46,2753,2815,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13542,140,2815,0,47,2814,2816,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13543,140,2816,0,48,2815,2761,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13544,140,2761,0,49,2816,2817,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13545,140,2817,0,50,2761,2818,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13546,140,2818,0,51,2817,2701,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13547,140,2701,0,52,2818,2819,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13548,140,2819,0,53,2701,2712,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13549,140,2712,0,54,2819,2820,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13550,140,2820,0,55,2712,2747,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13551,140,2747,0,56,2820,2783,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13552,140,2783,0,57,2747,2698,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13553,140,2698,0,58,2783,2805,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13554,140,2805,0,59,2698,2821,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13555,140,2821,0,60,2805,2822,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13556,140,2822,0,61,2821,2823,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13557,140,2823,0,62,2822,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13558,140,2728,0,63,2823,2824,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13559,140,2824,0,64,2728,2716,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13560,140,2716,0,65,2824,2825,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13561,140,2825,0,66,2716,2709,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13562,140,2709,0,67,2825,2787,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13563,140,2787,0,68,2709,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13564,140,2728,0,69,2787,2712,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13565,140,2712,0,70,2728,2826,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13566,140,2826,0,71,2712,2761,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13567,140,2761,0,72,2826,2826,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13568,140,2826,0,73,2761,2827,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13569,140,2827,0,74,2826,2805,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13570,140,2805,0,75,2827,2821,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13571,140,2821,0,76,2805,2719,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13572,140,2719,0,77,2821,2828,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13573,140,2828,0,78,2719,2829,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13574,140,2829,0,79,2828,2830,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13575,140,2830,0,80,2829,2831,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13576,140,2831,0,81,2830,2832,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13577,140,2832,0,82,2831,2783,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13578,140,2783,0,83,2832,2777,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13579,140,2777,0,84,2783,2784,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13580,140,2784,0,85,2777,2785,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13581,140,2785,0,86,2784,2786,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13582,140,2786,0,87,2785,2787,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13583,140,2787,0,88,2786,2788,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13584,140,2788,0,89,2787,2789,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13585,140,2789,0,90,2788,2790,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13586,140,2790,0,91,2789,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13587,140,2728,0,92,2790,2791,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13588,140,2791,0,93,2728,2792,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13589,140,2792,0,94,2791,2747,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13590,140,2747,0,95,2792,2793,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13591,140,2793,0,96,2747,2794,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13592,140,2794,0,97,2793,2753,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13593,140,2753,0,98,2794,2795,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13594,140,2795,0,99,2753,2796,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13595,140,2796,0,100,2795,2797,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13596,140,2797,0,101,2796,2798,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13597,140,2798,0,102,2797,2797,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13598,140,2797,0,103,2798,2799,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13599,140,2799,0,104,2797,2800,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13600,140,2800,0,105,2799,2801,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13601,140,2801,0,106,2800,2802,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13602,140,2802,0,107,2801,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13603,140,2728,0,108,2802,2803,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13604,140,2803,0,109,2728,2804,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13605,140,2804,0,110,2803,2698,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13606,140,2698,0,111,2804,2805,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13607,140,2805,0,112,2698,2806,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13608,140,2806,0,113,2805,2807,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13609,140,2807,0,114,2806,2808,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13610,140,2808,0,115,2807,2809,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13611,140,2809,0,116,2808,2810,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13612,140,2810,0,117,2809,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13613,140,2728,0,118,2810,2811,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13614,140,2811,0,119,2728,2783,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13615,140,2783,0,120,2811,2777,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13616,140,2777,0,121,2783,2707,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13617,140,2707,0,122,2777,2812,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13618,140,2812,0,123,2707,2813,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13619,140,2813,0,124,2812,2789,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13620,140,2789,0,125,2813,2753,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13621,140,2753,0,126,2789,2814,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13622,140,2814,0,127,2753,2815,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13623,140,2815,0,128,2814,2816,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13624,140,2816,0,129,2815,2761,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13625,140,2761,0,130,2816,2817,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13626,140,2817,0,131,2761,2818,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13627,140,2818,0,132,2817,2701,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13628,140,2701,0,133,2818,2819,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13629,140,2819,0,134,2701,2712,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13630,140,2712,0,135,2819,2820,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13631,140,2820,0,136,2712,2747,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13632,140,2747,0,137,2820,2783,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13633,140,2783,0,138,2747,2698,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13634,140,2698,0,139,2783,2805,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13635,140,2805,0,140,2698,2821,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13636,140,2821,0,141,2805,2822,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13637,140,2822,0,142,2821,2823,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13638,140,2823,0,143,2822,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13639,140,2728,0,144,2823,2824,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13640,140,2824,0,145,2728,2716,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13641,140,2716,0,146,2824,2825,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13642,140,2825,0,147,2716,2709,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13643,140,2709,0,148,2825,2787,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13644,140,2787,0,149,2709,2728,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13645,140,2728,0,150,2787,2712,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13646,140,2712,0,151,2728,2826,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13647,140,2826,0,152,2712,2761,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13648,140,2761,0,153,2826,2826,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13649,140,2826,0,154,2761,2827,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13650,140,2827,0,155,2826,2805,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13651,140,2805,0,156,2827,2821,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13652,140,2821,0,157,2805,2719,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13653,140,2719,0,158,2821,2828,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13654,140,2828,0,159,2719,2829,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13655,140,2829,0,160,2828,2830,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13656,140,2830,0,161,2829,2831,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13657,140,2831,0,162,2830,2832,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13658,140,2832,0,163,2831,0,10,1069756410,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13659,134,2833,0,0,0,2833,1,1067872529,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13660,134,2833,0,1,2833,2701,1,1067872529,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13661,134,2701,0,2,2833,2702,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13662,134,2702,0,3,2701,2703,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13663,134,2703,0,4,2702,2833,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13664,134,2833,0,5,2703,2701,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13665,134,2701,0,6,2833,2702,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13666,134,2702,0,7,2701,2703,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13667,134,2703,0,8,2702,2833,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13668,134,2833,0,9,2703,0,1,1067872529,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13669,144,2834,0,0,0,2834,10,1069757581,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13670,144,2834,0,1,2834,2835,10,1069757581,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13671,144,2835,0,2,2834,2836,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13672,144,2836,0,3,2835,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13673,144,2712,0,4,2836,2837,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13674,144,2837,0,5,2712,2753,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13675,144,2753,0,6,2837,2834,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13676,144,2834,0,7,2753,2838,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13677,144,2838,0,8,2834,2839,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13678,144,2839,0,9,2838,2840,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13679,144,2840,0,10,2839,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13680,144,2761,0,11,2840,2841,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13681,144,2841,0,12,2761,2703,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13682,144,2703,0,13,2841,2842,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13683,144,2842,0,14,2703,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13684,144,2712,0,15,2842,2715,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13685,144,2715,0,16,2712,2843,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13686,144,2843,0,17,2715,2844,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13687,144,2844,0,18,2843,2845,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13688,144,2845,0,19,2844,2846,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13689,144,2846,0,20,2845,2707,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13690,144,2707,0,21,2846,2834,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13691,144,2834,0,22,2707,2847,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13692,144,2847,0,23,2834,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13693,144,2712,0,24,2847,2848,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13694,144,2848,0,25,2712,2849,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13695,144,2849,0,26,2848,2850,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13696,144,2850,0,27,2849,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13697,144,2761,0,28,2850,2851,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13698,144,2851,0,29,2761,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13699,144,2698,0,30,2851,2747,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13700,144,2747,0,31,2698,2807,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13701,144,2807,0,32,2747,2852,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13702,144,2852,0,33,2807,2853,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13703,144,2853,0,34,2852,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13704,144,2698,0,35,2853,2699,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13705,144,2699,0,36,2698,2854,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13706,144,2854,0,37,2699,2747,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13707,144,2747,0,38,2854,2834,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13708,144,2834,0,39,2747,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13709,144,2712,0,40,2834,2834,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13710,144,2834,0,41,2712,2699,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13711,144,2699,0,42,2834,2855,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13712,144,2855,0,43,2699,2856,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13713,144,2856,0,44,2855,2829,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13714,144,2829,0,45,2856,2857,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13715,144,2857,0,46,2829,2728,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13716,144,2728,0,47,2857,2858,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13717,144,2858,0,48,2728,2859,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13718,144,2859,0,49,2858,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13719,144,2698,0,50,2859,2860,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13720,144,2860,0,51,2698,2851,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13721,144,2851,0,52,2860,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13722,144,2761,0,53,2851,2861,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13723,144,2861,0,54,2761,2862,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13724,144,2862,0,55,2861,2863,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13725,144,2863,0,56,2862,2864,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13726,144,2864,0,57,2863,2845,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13727,144,2845,0,58,2864,2805,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13728,144,2805,0,59,2845,2851,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13729,144,2851,0,60,2805,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13730,144,2698,0,61,2851,2865,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13731,144,2865,0,62,2698,2815,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13732,144,2815,0,63,2865,2866,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13733,144,2866,0,64,2815,2819,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13734,144,2819,0,65,2866,2807,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13735,144,2807,0,66,2819,2867,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13736,144,2867,0,67,2807,2862,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13737,144,2862,0,68,2867,2868,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13738,144,2868,0,69,2862,2707,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13739,144,2707,0,70,2868,2725,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13740,144,2725,0,71,2707,2869,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13741,144,2869,0,72,2725,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13742,144,2761,0,73,2869,2707,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13743,144,2707,0,74,2761,2870,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13744,144,2870,0,75,2707,2819,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13745,144,2819,0,76,2870,2783,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13746,144,2783,0,77,2819,2845,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13747,144,2845,0,78,2783,2699,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13748,144,2699,0,79,2845,2871,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13749,144,2871,0,80,2699,2719,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13750,144,2719,0,81,2871,2872,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13751,144,2872,0,82,2719,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13752,144,2761,0,83,2872,2873,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13753,144,2873,0,84,2761,2874,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13754,144,2874,0,85,2873,2819,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13755,144,2819,0,86,2874,2875,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13756,144,2875,0,87,2819,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13757,144,2761,0,88,2875,2876,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13758,144,2876,0,89,2761,2877,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13759,144,2877,0,90,2876,2747,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13760,144,2747,0,91,2877,2791,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13761,144,2791,0,92,2747,2733,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13762,144,2733,0,93,2791,2783,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13763,144,2783,0,94,2733,2859,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13764,144,2859,0,95,2783,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13765,144,2698,0,96,2859,2878,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13766,144,2878,0,97,2698,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13767,144,2761,0,98,2878,2879,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13768,144,2879,0,99,2761,2880,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13769,144,2880,0,100,2879,2709,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13770,144,2709,0,101,2880,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13771,144,2712,0,102,2709,2827,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13772,144,2827,0,103,2712,2881,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13773,144,2881,0,104,2827,2835,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13774,144,2835,0,105,2881,2836,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13775,144,2836,0,106,2835,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13776,144,2712,0,107,2836,2837,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13777,144,2837,0,108,2712,2753,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13778,144,2753,0,109,2837,2834,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13779,144,2834,0,110,2753,2838,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13780,144,2838,0,111,2834,2839,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13781,144,2839,0,112,2838,2840,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13782,144,2840,0,113,2839,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13783,144,2761,0,114,2840,2841,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13784,144,2841,0,115,2761,2703,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13785,144,2703,0,116,2841,2842,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13786,144,2842,0,117,2703,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13787,144,2712,0,118,2842,2715,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13788,144,2715,0,119,2712,2843,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13789,144,2843,0,120,2715,2844,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13790,144,2844,0,121,2843,2845,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13791,144,2845,0,122,2844,2846,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13792,144,2846,0,123,2845,2707,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13793,144,2707,0,124,2846,2834,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13794,144,2834,0,125,2707,2847,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13795,144,2847,0,126,2834,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13796,144,2712,0,127,2847,2848,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13797,144,2848,0,128,2712,2849,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13798,144,2849,0,129,2848,2850,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13799,144,2850,0,130,2849,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13800,144,2761,0,131,2850,2851,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13801,144,2851,0,132,2761,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13802,144,2698,0,133,2851,2747,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13803,144,2747,0,134,2698,2807,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13804,144,2807,0,135,2747,2852,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13805,144,2852,0,136,2807,2853,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13806,144,2853,0,137,2852,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13807,144,2698,0,138,2853,2699,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13808,144,2699,0,139,2698,2854,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13809,144,2854,0,140,2699,2747,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13810,144,2747,0,141,2854,2834,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13811,144,2834,0,142,2747,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13812,144,2712,0,143,2834,2834,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13813,144,2834,0,144,2712,2699,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13814,144,2699,0,145,2834,2855,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13815,144,2855,0,146,2699,2856,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13816,144,2856,0,147,2855,2829,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13817,144,2829,0,148,2856,2857,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13818,144,2857,0,149,2829,2728,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13819,144,2728,0,150,2857,2858,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13820,144,2858,0,151,2728,2859,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13821,144,2859,0,152,2858,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13822,144,2698,0,153,2859,2860,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13823,144,2860,0,154,2698,2851,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13824,144,2851,0,155,2860,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13825,144,2761,0,156,2851,2861,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13826,144,2861,0,157,2761,2862,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13827,144,2862,0,158,2861,2863,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13828,144,2863,0,159,2862,2864,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13829,144,2864,0,160,2863,2845,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13830,144,2845,0,161,2864,2805,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13831,144,2805,0,162,2845,2851,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13832,144,2851,0,163,2805,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13833,144,2698,0,164,2851,2865,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13834,144,2865,0,165,2698,2815,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13835,144,2815,0,166,2865,2866,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13836,144,2866,0,167,2815,2819,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13837,144,2819,0,168,2866,2807,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13838,144,2807,0,169,2819,2867,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13839,144,2867,0,170,2807,2862,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13840,144,2862,0,171,2867,2868,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13841,144,2868,0,172,2862,2707,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13842,144,2707,0,173,2868,2725,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13843,144,2725,0,174,2707,2869,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13844,144,2869,0,175,2725,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13845,144,2761,0,176,2869,2707,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13846,144,2707,0,177,2761,2870,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13847,144,2870,0,178,2707,2819,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13848,144,2819,0,179,2870,2783,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13849,144,2783,0,180,2819,2845,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13850,144,2845,0,181,2783,2699,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13851,144,2699,0,182,2845,2871,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13852,144,2871,0,183,2699,2719,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13853,144,2719,0,184,2871,2872,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13854,144,2872,0,185,2719,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13855,144,2761,0,186,2872,2873,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13856,144,2873,0,187,2761,2874,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13857,144,2874,0,188,2873,2819,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13858,144,2819,0,189,2874,2875,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13859,144,2875,0,190,2819,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13860,144,2761,0,191,2875,2876,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13861,144,2876,0,192,2761,2877,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13862,144,2877,0,193,2876,2747,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13863,144,2747,0,194,2877,2791,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13864,144,2791,0,195,2747,2733,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13865,144,2733,0,196,2791,2783,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13866,144,2783,0,197,2733,2859,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13867,144,2859,0,198,2783,2698,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13868,144,2698,0,199,2859,2878,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13869,144,2878,0,200,2698,2761,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13870,144,2761,0,201,2878,2879,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13871,144,2879,0,202,2761,2880,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13872,144,2880,0,203,2879,2709,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13873,144,2709,0,204,2880,2712,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13874,144,2712,0,205,2709,2827,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13875,144,2827,0,206,2712,2881,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13876,144,2881,0,207,2827,0,10,1069757581,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13877,145,2791,0,0,0,2791,10,1069757729,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13878,145,2791,0,1,2791,2836,10,1069757729,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13879,145,2836,0,2,2791,2712,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13880,145,2712,0,3,2836,2837,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13881,145,2837,0,4,2712,2753,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13882,145,2753,0,5,2837,2882,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13883,145,2882,0,6,2753,2728,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13884,145,2728,0,7,2882,2883,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13885,145,2883,0,8,2728,2884,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13886,145,2884,0,9,2883,2707,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13887,145,2707,0,10,2884,2885,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13888,145,2885,0,11,2707,2761,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13889,145,2761,0,12,2885,2851,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13890,145,2851,0,13,2761,2698,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13891,145,2698,0,14,2851,2747,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13892,145,2747,0,15,2698,2807,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13893,145,2807,0,16,2747,2886,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13894,145,2886,0,17,2807,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13895,145,2845,0,18,2886,2728,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13896,145,2728,0,19,2845,2703,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13897,145,2703,0,20,2728,2887,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13898,145,2887,0,21,2703,2740,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13899,145,2740,0,22,2887,2888,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13900,145,2888,0,23,2740,2889,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13901,145,2889,0,24,2888,2890,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13902,145,2890,0,25,2889,2850,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13903,145,2850,0,26,2890,2761,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13904,145,2761,0,27,2850,2891,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13905,145,2891,0,28,2761,2698,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13906,145,2698,0,29,2891,2892,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13907,145,2892,0,30,2698,2893,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13908,145,2893,0,31,2892,2711,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13909,145,2711,0,32,2893,2869,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13910,145,2869,0,33,2711,2894,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13911,145,2894,0,34,2869,2895,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13912,145,2895,0,35,2894,2851,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13913,145,2851,0,36,2895,2896,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13914,145,2896,0,37,2851,2851,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13915,145,2851,0,38,2896,2897,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13916,145,2897,0,39,2851,2898,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13917,145,2898,0,40,2897,2728,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13918,145,2728,0,41,2898,2899,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13919,145,2899,0,42,2728,2900,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13920,145,2900,0,43,2899,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13921,145,2845,0,44,2900,2851,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13922,145,2851,0,45,2845,2747,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13923,145,2747,0,46,2851,2895,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13924,145,2895,0,47,2747,2897,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13925,145,2897,0,48,2895,2898,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13926,145,2898,0,49,2897,2901,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13927,145,2901,0,50,2898,2902,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13928,145,2902,0,51,2901,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13929,145,2845,0,52,2902,2805,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13930,145,2805,0,53,2845,2871,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13931,145,2871,0,54,2805,2903,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13932,145,2903,0,55,2871,2707,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13933,145,2707,0,56,2903,2904,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13934,145,2904,0,57,2707,2905,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13935,145,2905,0,58,2904,2820,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13936,145,2820,0,59,2905,2886,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13937,145,2886,0,60,2820,2906,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13938,145,2906,0,61,2886,2819,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13939,145,2819,0,62,2906,2783,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13940,145,2783,0,63,2819,2907,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13941,145,2907,0,64,2783,2908,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13942,145,2908,0,65,2907,2909,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13943,145,2909,0,66,2908,2853,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13944,145,2853,0,67,2909,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13945,145,2845,0,68,2853,2805,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13946,145,2805,0,69,2845,2879,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13947,145,2879,0,70,2805,2753,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13948,145,2753,0,71,2879,2698,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13949,145,2698,0,72,2753,2703,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13950,145,2703,0,73,2698,2910,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13951,145,2910,0,74,2703,2911,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13952,145,2911,0,75,2910,2912,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13953,145,2912,0,76,2911,2777,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13954,145,2777,0,77,2912,2913,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13955,145,2913,0,78,2777,2728,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13956,145,2728,0,79,2913,2703,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13957,145,2703,0,80,2728,2914,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13958,145,2914,0,81,2703,2915,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13959,145,2915,0,82,2914,2916,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13960,145,2916,0,83,2915,2753,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13961,145,2753,0,84,2916,2917,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13962,145,2917,0,85,2753,2777,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13963,145,2777,0,86,2917,2918,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13964,145,2918,0,87,2777,2919,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13965,145,2919,0,88,2918,2908,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13966,145,2908,0,89,2919,2859,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13967,145,2859,0,90,2908,2920,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13968,145,2920,0,91,2859,2777,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13969,145,2777,0,92,2920,2921,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13970,145,2921,0,93,2777,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13971,145,2845,0,94,2921,2805,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13972,145,2805,0,95,2845,2879,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13973,145,2879,0,96,2805,2753,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13974,145,2753,0,97,2879,2698,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13975,145,2698,0,98,2753,2836,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13976,145,2836,0,99,2698,2712,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13977,145,2712,0,100,2836,2837,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13978,145,2837,0,101,2712,2753,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13979,145,2753,0,102,2837,2882,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13980,145,2882,0,103,2753,2728,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13981,145,2728,0,104,2882,2883,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13982,145,2883,0,105,2728,2884,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13983,145,2884,0,106,2883,2707,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13984,145,2707,0,107,2884,2885,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13985,145,2885,0,108,2707,2761,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13986,145,2761,0,109,2885,2851,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13987,145,2851,0,110,2761,2698,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13988,145,2698,0,111,2851,2747,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13989,145,2747,0,112,2698,2807,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13990,145,2807,0,113,2747,2886,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13991,145,2886,0,114,2807,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13992,145,2845,0,115,2886,2728,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13993,145,2728,0,116,2845,2703,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13994,145,2703,0,117,2728,2887,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13995,145,2887,0,118,2703,2740,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13996,145,2740,0,119,2887,2888,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13997,145,2888,0,120,2740,2889,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13998,145,2889,0,121,2888,2890,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (13999,145,2890,0,122,2889,2850,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14000,145,2850,0,123,2890,2761,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14001,145,2761,0,124,2850,2891,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14002,145,2891,0,125,2761,2698,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14003,145,2698,0,126,2891,2892,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14004,145,2892,0,127,2698,2893,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14005,145,2893,0,128,2892,2711,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14006,145,2711,0,129,2893,2869,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14007,145,2869,0,130,2711,2894,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14008,145,2894,0,131,2869,2895,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14009,145,2895,0,132,2894,2851,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14010,145,2851,0,133,2895,2896,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14011,145,2896,0,134,2851,2851,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14012,145,2851,0,135,2896,2897,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14013,145,2897,0,136,2851,2898,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14014,145,2898,0,137,2897,2728,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14015,145,2728,0,138,2898,2899,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14016,145,2899,0,139,2728,2900,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14017,145,2900,0,140,2899,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14018,145,2845,0,141,2900,2851,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14019,145,2851,0,142,2845,2747,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14020,145,2747,0,143,2851,2895,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14021,145,2895,0,144,2747,2897,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14022,145,2897,0,145,2895,2898,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14023,145,2898,0,146,2897,2901,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14024,145,2901,0,147,2898,2902,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14025,145,2902,0,148,2901,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14026,145,2845,0,149,2902,2805,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14027,145,2805,0,150,2845,2871,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14028,145,2871,0,151,2805,2903,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14029,145,2903,0,152,2871,2707,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14030,145,2707,0,153,2903,2904,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14031,145,2904,0,154,2707,2905,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14032,145,2905,0,155,2904,2820,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14033,145,2820,0,156,2905,2886,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14034,145,2886,0,157,2820,2906,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14035,145,2906,0,158,2886,2819,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14036,145,2819,0,159,2906,2783,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14037,145,2783,0,160,2819,2907,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14038,145,2907,0,161,2783,2908,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14039,145,2908,0,162,2907,2909,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14040,145,2909,0,163,2908,2853,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14041,145,2853,0,164,2909,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14042,145,2845,0,165,2853,2805,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14043,145,2805,0,166,2845,2879,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14044,145,2879,0,167,2805,2753,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14045,145,2753,0,168,2879,2698,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14046,145,2698,0,169,2753,2703,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14047,145,2703,0,170,2698,2910,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14048,145,2910,0,171,2703,2911,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14049,145,2911,0,172,2910,2912,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14050,145,2912,0,173,2911,2777,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14051,145,2777,0,174,2912,2913,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14052,145,2913,0,175,2777,2728,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14053,145,2728,0,176,2913,2703,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14054,145,2703,0,177,2728,2914,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14055,145,2914,0,178,2703,2915,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14056,145,2915,0,179,2914,2916,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14057,145,2916,0,180,2915,2753,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14058,145,2753,0,181,2916,2917,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14059,145,2917,0,182,2753,2777,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14060,145,2777,0,183,2917,2918,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14061,145,2918,0,184,2777,2919,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14062,145,2919,0,185,2918,2908,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14063,145,2908,0,186,2919,2859,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14064,145,2859,0,187,2908,2920,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14065,145,2920,0,188,2859,2777,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14066,145,2777,0,189,2920,2921,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14067,145,2921,0,190,2777,2845,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14068,145,2845,0,191,2921,2805,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14069,145,2805,0,192,2845,2879,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14070,145,2879,0,193,2805,2753,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14071,145,2753,0,194,2879,2698,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14072,145,2698,0,195,2753,0,10,1069757729,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14073,135,2922,0,0,0,2923,1,1067936571,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14074,135,2923,0,1,2922,2922,1,1067936571,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14075,135,2922,0,2,2923,2923,1,1067936571,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14076,135,2923,0,3,2922,2697,1,1067936571,1,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14077,135,2697,0,4,2923,2698,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14078,135,2698,0,5,2697,2699,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14079,135,2699,0,6,2698,2700,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14080,135,2700,0,7,2699,2701,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14081,135,2701,0,8,2700,2702,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14082,135,2702,0,9,2701,2924,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14083,135,2924,0,10,2702,2925,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14084,135,2925,0,11,2924,2697,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14085,135,2697,0,12,2925,2698,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14086,135,2698,0,13,2697,2699,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14087,135,2699,0,14,2698,2700,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14088,135,2700,0,15,2699,2701,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14089,135,2701,0,16,2700,2702,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14090,135,2702,0,17,2701,2924,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14091,135,2924,0,18,2702,2925,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14092,135,2925,0,19,2924,0,1,1067936571,1,119,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14093,136,2702,0,0,0,2702,10,1067937053,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14094,136,2702,0,1,2702,2701,10,1067937053,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14095,136,2701,0,2,2702,2702,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14096,136,2702,0,3,2701,2807,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14097,136,2807,0,4,2702,2925,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14098,136,2925,0,5,2807,2926,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14099,136,2926,0,6,2925,2925,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14100,136,2925,0,7,2926,2777,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14101,136,2777,0,8,2925,2927,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14102,136,2927,0,9,2777,2733,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14103,136,2733,0,10,2927,2928,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14104,136,2928,0,11,2733,2929,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14105,136,2929,0,12,2928,2747,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14106,136,2747,0,13,2929,2930,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14107,136,2930,0,14,2747,2931,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14108,136,2931,0,15,2930,2926,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14109,136,2926,0,16,2931,2925,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14110,136,2925,0,17,2926,2932,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14111,136,2932,0,18,2925,2933,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14112,136,2933,0,19,2932,2733,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14113,136,2733,0,20,2933,2934,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14114,136,2934,0,21,2733,2935,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14115,136,2935,0,22,2934,2733,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14116,136,2733,0,23,2935,2928,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14117,136,2928,0,24,2733,2929,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14118,136,2929,0,25,2928,2936,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14119,136,2936,0,26,2929,2937,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14120,136,2937,0,27,2936,2938,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14121,136,2938,0,28,2937,2939,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14122,136,2939,0,29,2938,2719,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14123,136,2719,0,30,2939,2784,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14124,136,2784,0,31,2719,2785,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14125,136,2785,0,32,2784,2940,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14126,136,2940,0,33,2785,2941,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14127,136,2941,0,34,2940,2942,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14128,136,2942,0,35,2941,2943,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14129,136,2943,0,36,2942,2944,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14130,136,2944,0,37,2943,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14131,136,2728,0,38,2944,2945,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14132,136,2945,0,39,2728,2946,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14133,136,2946,0,40,2945,2712,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14134,136,2712,0,41,2946,2947,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14135,136,2947,0,42,2712,2761,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14136,136,2761,0,43,2947,2817,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14137,136,2817,0,44,2761,2701,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14138,136,2701,0,45,2817,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14139,136,2728,0,46,2701,2948,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14140,136,2948,0,47,2728,2936,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14141,136,2936,0,48,2948,2949,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14142,136,2949,0,49,2936,2785,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14143,136,2785,0,50,2949,2845,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14144,136,2845,0,51,2785,2939,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14145,136,2939,0,52,2845,2950,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14146,136,2950,0,53,2939,2951,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14147,136,2951,0,54,2950,2712,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14148,136,2712,0,55,2951,2947,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14149,136,2947,0,56,2712,2747,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14150,136,2747,0,57,2947,2784,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14151,136,2784,0,58,2747,2785,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14152,136,2785,0,59,2784,2952,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14153,136,2952,0,60,2785,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14154,136,2728,0,61,2952,2784,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14155,136,2784,0,62,2728,2785,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14156,136,2785,0,63,2784,2953,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14157,136,2953,0,64,2785,2950,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14158,136,2950,0,65,2953,2954,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14159,136,2954,0,66,2950,2955,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14160,136,2955,0,67,2954,2944,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14161,136,2944,0,68,2955,2956,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14162,136,2956,0,69,2944,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14163,136,2728,0,70,2956,2957,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14164,136,2957,0,71,2728,2958,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14165,136,2958,0,72,2957,2845,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14166,136,2845,0,73,2958,2939,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14167,136,2939,0,74,2845,2817,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14168,136,2817,0,75,2939,2703,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14169,136,2703,0,76,2817,2701,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14170,136,2701,0,77,2703,2956,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14171,136,2956,0,78,2701,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14172,136,2728,0,79,2956,2957,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14173,136,2957,0,80,2728,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14174,136,2728,0,81,2957,2959,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14175,136,2959,0,82,2728,2960,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14176,136,2960,0,83,2959,2868,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14177,136,2868,0,84,2960,2707,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14178,136,2707,0,85,2868,2942,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14179,136,2942,0,86,2707,2961,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14180,136,2961,0,87,2942,2962,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14181,136,2962,0,88,2961,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14182,136,2728,0,89,2962,2960,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14183,136,2960,0,90,2728,2747,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14184,136,2747,0,91,2960,2712,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14185,136,2712,0,92,2747,2963,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14186,136,2963,0,93,2712,2960,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14187,136,2960,0,94,2963,2845,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14188,136,2845,0,95,2960,2699,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14189,136,2699,0,96,2845,2964,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14190,136,2964,0,97,2699,2965,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14191,136,2965,0,98,2964,2966,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14192,136,2966,0,99,2965,2967,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14193,136,2967,0,100,2966,2845,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14194,136,2845,0,101,2967,2939,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14195,136,2939,0,102,2845,2719,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14196,136,2719,0,103,2939,2967,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14197,136,2967,0,104,2719,2944,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14198,136,2944,0,105,2967,2968,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14199,136,2968,0,106,2944,2967,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14200,136,2967,0,107,2968,2899,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14201,136,2899,0,108,2967,2701,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14202,136,2701,0,109,2899,2702,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14203,136,2702,0,110,2701,2807,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14204,136,2807,0,111,2702,2925,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14205,136,2925,0,112,2807,2926,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14206,136,2926,0,113,2925,2925,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14207,136,2925,0,114,2926,2777,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14208,136,2777,0,115,2925,2927,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14209,136,2927,0,116,2777,2733,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14210,136,2733,0,117,2927,2928,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14211,136,2928,0,118,2733,2929,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14212,136,2929,0,119,2928,2747,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14213,136,2747,0,120,2929,2930,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14214,136,2930,0,121,2747,2931,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14215,136,2931,0,122,2930,2926,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14216,136,2926,0,123,2931,2925,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14217,136,2925,0,124,2926,2932,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14218,136,2932,0,125,2925,2933,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14219,136,2933,0,126,2932,2733,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14220,136,2733,0,127,2933,2934,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14221,136,2934,0,128,2733,2935,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14222,136,2935,0,129,2934,2733,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14223,136,2733,0,130,2935,2928,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14224,136,2928,0,131,2733,2929,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14225,136,2929,0,132,2928,2936,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14226,136,2936,0,133,2929,2937,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14227,136,2937,0,134,2936,2938,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14228,136,2938,0,135,2937,2939,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14229,136,2939,0,136,2938,2719,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14230,136,2719,0,137,2939,2784,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14231,136,2784,0,138,2719,2785,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14232,136,2785,0,139,2784,2940,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14233,136,2940,0,140,2785,2941,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14234,136,2941,0,141,2940,2942,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14235,136,2942,0,142,2941,2943,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14236,136,2943,0,143,2942,2944,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14237,136,2944,0,144,2943,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14238,136,2728,0,145,2944,2945,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14239,136,2945,0,146,2728,2946,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14240,136,2946,0,147,2945,2712,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14241,136,2712,0,148,2946,2947,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14242,136,2947,0,149,2712,2761,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14243,136,2761,0,150,2947,2817,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14244,136,2817,0,151,2761,2701,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14245,136,2701,0,152,2817,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14246,136,2728,0,153,2701,2948,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14247,136,2948,0,154,2728,2936,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14248,136,2936,0,155,2948,2949,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14249,136,2949,0,156,2936,2785,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14250,136,2785,0,157,2949,2845,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14251,136,2845,0,158,2785,2939,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14252,136,2939,0,159,2845,2950,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14253,136,2950,0,160,2939,2951,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14254,136,2951,0,161,2950,2712,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14255,136,2712,0,162,2951,2947,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14256,136,2947,0,163,2712,2747,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14257,136,2747,0,164,2947,2784,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14258,136,2784,0,165,2747,2785,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14259,136,2785,0,166,2784,2952,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14260,136,2952,0,167,2785,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14261,136,2728,0,168,2952,2784,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14262,136,2784,0,169,2728,2785,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14263,136,2785,0,170,2784,2953,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14264,136,2953,0,171,2785,2950,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14265,136,2950,0,172,2953,2954,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14266,136,2954,0,173,2950,2955,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14267,136,2955,0,174,2954,2944,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14268,136,2944,0,175,2955,2956,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14269,136,2956,0,176,2944,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14270,136,2728,0,177,2956,2957,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14271,136,2957,0,178,2728,2958,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14272,136,2958,0,179,2957,2845,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14273,136,2845,0,180,2958,2939,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14274,136,2939,0,181,2845,2817,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14275,136,2817,0,182,2939,2703,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14276,136,2703,0,183,2817,2701,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14277,136,2701,0,184,2703,2956,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14278,136,2956,0,185,2701,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14279,136,2728,0,186,2956,2957,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14280,136,2957,0,187,2728,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14281,136,2728,0,188,2957,2959,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14282,136,2959,0,189,2728,2960,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14283,136,2960,0,190,2959,2868,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14284,136,2868,0,191,2960,2707,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14285,136,2707,0,192,2868,2942,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14286,136,2942,0,193,2707,2961,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14287,136,2961,0,194,2942,2962,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14288,136,2962,0,195,2961,2728,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14289,136,2728,0,196,2962,2960,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14290,136,2960,0,197,2728,2747,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14291,136,2747,0,198,2960,2712,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14292,136,2712,0,199,2747,2963,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14293,136,2963,0,200,2712,2960,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14294,136,2960,0,201,2963,2845,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14295,136,2845,0,202,2960,2699,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14296,136,2699,0,203,2845,2964,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14297,136,2964,0,204,2699,2965,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14298,136,2965,0,205,2964,2966,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14299,136,2966,0,206,2965,2967,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14300,136,2967,0,207,2966,2845,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14301,136,2845,0,208,2967,2939,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14302,136,2939,0,209,2845,2719,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14303,136,2719,0,210,2939,2967,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14304,136,2967,0,211,2719,2944,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14305,136,2944,0,212,2967,2968,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14306,136,2968,0,213,2944,2967,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14307,136,2967,0,214,2968,2899,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14308,136,2899,0,215,2967,0,10,1067937053,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14309,142,2969,0,0,0,2969,10,1069757199,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14310,142,2969,0,1,2969,2845,10,1069757199,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14311,142,2845,0,2,2969,2849,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14312,142,2849,0,3,2845,2970,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14313,142,2970,0,4,2849,2971,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14314,142,2971,0,5,2970,2712,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14315,142,2712,0,6,2971,2972,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14316,142,2972,0,7,2712,2789,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14317,142,2789,0,8,2972,2890,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14318,142,2890,0,9,2789,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14319,142,2698,0,10,2890,2699,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14320,142,2699,0,11,2698,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14321,142,2719,0,12,2699,2973,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14322,142,2973,0,13,2719,2709,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14323,142,2709,0,14,2973,2712,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14324,142,2712,0,15,2709,2974,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14325,142,2974,0,16,2712,2791,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14326,142,2791,0,17,2974,2837,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14327,142,2837,0,18,2791,2975,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14328,142,2975,0,19,2837,2703,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14329,142,2703,0,20,2975,2696,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14330,142,2696,0,21,2703,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14331,142,2698,0,22,2696,2699,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14332,142,2699,0,23,2698,2871,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14333,142,2871,0,24,2699,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14334,142,2719,0,25,2871,2973,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14335,142,2973,0,26,2719,2709,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14336,142,2709,0,27,2973,2976,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14337,142,2976,0,28,2709,2917,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14338,142,2917,0,29,2976,2977,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14339,142,2977,0,30,2917,2868,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14340,142,2868,0,31,2977,2707,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14341,142,2707,0,32,2868,2978,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14342,142,2978,0,33,2707,2979,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14343,142,2979,0,34,2978,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14344,142,2728,0,35,2979,2862,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14345,142,2862,0,36,2728,2980,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14346,142,2980,0,37,2862,2812,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14347,142,2812,0,38,2980,2981,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14348,142,2981,0,39,2812,2901,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14349,142,2901,0,40,2981,2982,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14350,142,2982,0,41,2901,2849,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14351,142,2849,0,42,2982,2983,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14352,142,2983,0,43,2849,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14353,142,2698,0,44,2983,2984,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14354,142,2984,0,45,2698,2909,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14355,142,2909,0,46,2984,2985,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14356,142,2985,0,47,2909,2986,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14357,142,2986,0,48,2985,2901,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14358,142,2901,0,49,2986,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14359,142,2728,0,50,2901,2987,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14360,142,2987,0,51,2728,2988,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14361,142,2988,0,52,2987,2989,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14362,142,2989,0,53,2988,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14363,142,2728,0,54,2989,2990,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14364,142,2990,0,55,2728,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14365,142,2698,0,56,2990,2739,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14366,142,2739,0,57,2698,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14367,142,2719,0,58,2739,2991,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14368,142,2991,0,59,2719,2747,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14369,142,2747,0,60,2991,2992,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14370,142,2992,0,61,2747,2993,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14371,142,2993,0,62,2992,2994,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14372,142,2994,0,63,2993,2995,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14373,142,2995,0,64,2994,2996,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14374,142,2996,0,65,2995,2994,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14375,142,2994,0,66,2996,2997,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14376,142,2997,0,67,2994,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14377,142,2728,0,68,2997,2998,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14378,142,2998,0,69,2728,2999,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14379,142,2999,0,70,2998,3000,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14380,142,3000,0,71,2999,2747,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14381,142,2747,0,72,3000,2712,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14382,142,2712,0,73,2747,3001,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14383,142,3001,0,74,2712,3002,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14384,142,3002,0,75,3001,2777,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14385,142,2777,0,76,3002,2707,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14386,142,2707,0,77,2777,3003,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14387,142,3003,0,78,2707,3000,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14388,142,3000,0,79,3003,2711,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14389,142,2711,0,80,3000,2785,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14390,142,2785,0,81,2711,2786,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14391,142,2786,0,82,2785,2917,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14392,142,2917,0,83,2786,2777,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14393,142,2777,0,84,2917,2707,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14394,142,2707,0,85,2777,3003,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14395,142,3003,0,86,2707,3004,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14396,142,3004,0,87,3003,3005,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14397,142,3005,0,88,3004,2934,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14398,142,2934,0,89,3005,2871,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14399,142,2871,0,90,2934,3006,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14400,142,3006,0,91,2871,2859,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14401,142,2859,0,92,3006,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14402,142,2698,0,93,2859,2740,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14403,142,2740,0,94,2698,2812,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14404,142,2812,0,95,2740,2981,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14405,142,2981,0,96,2812,2901,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14406,142,2901,0,97,2981,2957,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14407,142,2957,0,98,2901,3007,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14408,142,3007,0,99,2957,2699,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14409,142,2699,0,100,3007,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14410,142,2719,0,101,2699,3008,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14411,142,3008,0,102,2719,3009,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14412,142,3009,0,103,3008,3010,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14413,142,3010,0,104,3009,2709,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14414,142,2709,0,105,3010,2827,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14415,142,2827,0,106,2709,2928,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14416,142,2928,0,107,2827,2929,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14417,142,2929,0,108,2928,3011,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14418,142,3011,0,109,2929,3012,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14419,142,3012,0,110,3011,2819,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14420,142,2819,0,111,3012,3013,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14421,142,3013,0,112,2819,2812,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14422,142,2812,0,113,3013,2981,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14423,142,2981,0,114,2812,3014,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14424,142,3014,0,115,2981,2982,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14425,142,2982,0,116,3014,2849,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14426,142,2849,0,117,2982,2983,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14427,142,2983,0,118,2849,2955,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14428,142,2955,0,119,2983,3015,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14429,142,3015,0,120,2955,2777,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14430,142,2777,0,121,3015,2707,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14431,142,2707,0,122,2777,3003,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14432,142,3003,0,123,2707,3016,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14433,142,3016,0,124,3003,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14434,142,2728,0,125,3016,3007,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14435,142,3007,0,126,2728,2747,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14436,142,2747,0,127,3007,3017,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14437,142,3017,0,128,2747,2731,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14438,142,2731,0,129,3017,2849,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14439,142,2849,0,130,2731,2761,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14440,142,2761,0,131,2849,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14441,142,2719,0,132,2761,3018,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14442,142,3018,0,133,2719,2829,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14443,142,2829,0,134,3018,2795,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14444,142,2795,0,135,2829,3019,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14445,142,3019,0,136,2795,2761,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14446,142,2761,0,137,3019,2712,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14447,142,2712,0,138,2761,3020,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14448,142,3020,0,139,2712,2845,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14449,142,2845,0,140,3020,2849,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14450,142,2849,0,141,2845,2970,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14451,142,2970,0,142,2849,2971,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14452,142,2971,0,143,2970,2712,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14453,142,2712,0,144,2971,2972,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14454,142,2972,0,145,2712,2789,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14455,142,2789,0,146,2972,2890,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14456,142,2890,0,147,2789,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14457,142,2698,0,148,2890,2699,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14458,142,2699,0,149,2698,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14459,142,2719,0,150,2699,2973,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14460,142,2973,0,151,2719,2709,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14461,142,2709,0,152,2973,2712,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14462,142,2712,0,153,2709,2974,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14463,142,2974,0,154,2712,2791,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14464,142,2791,0,155,2974,2837,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14465,142,2837,0,156,2791,2975,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14466,142,2975,0,157,2837,2703,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14467,142,2703,0,158,2975,2696,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14468,142,2696,0,159,2703,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14469,142,2698,0,160,2696,2699,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14470,142,2699,0,161,2698,2871,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14471,142,2871,0,162,2699,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14472,142,2719,0,163,2871,2973,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14473,142,2973,0,164,2719,2709,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14474,142,2709,0,165,2973,2976,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14475,142,2976,0,166,2709,2917,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14476,142,2917,0,167,2976,2977,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14477,142,2977,0,168,2917,2868,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14478,142,2868,0,169,2977,2707,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14479,142,2707,0,170,2868,2978,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14480,142,2978,0,171,2707,2979,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14481,142,2979,0,172,2978,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14482,142,2728,0,173,2979,2862,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14483,142,2862,0,174,2728,2980,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14484,142,2980,0,175,2862,2812,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14485,142,2812,0,176,2980,2981,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14486,142,2981,0,177,2812,2901,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14487,142,2901,0,178,2981,2982,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14488,142,2982,0,179,2901,2849,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14489,142,2849,0,180,2982,2983,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14490,142,2983,0,181,2849,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14491,142,2698,0,182,2983,2984,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14492,142,2984,0,183,2698,2909,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14493,142,2909,0,184,2984,2985,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14494,142,2985,0,185,2909,2986,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14495,142,2986,0,186,2985,2901,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14496,142,2901,0,187,2986,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14497,142,2728,0,188,2901,2987,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14498,142,2987,0,189,2728,2988,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14499,142,2988,0,190,2987,2989,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14500,142,2989,0,191,2988,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14501,142,2728,0,192,2989,2990,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14502,142,2990,0,193,2728,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14503,142,2698,0,194,2990,2739,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14504,142,2739,0,195,2698,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14505,142,2719,0,196,2739,2991,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14506,142,2991,0,197,2719,2747,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14507,142,2747,0,198,2991,2992,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14508,142,2992,0,199,2747,2993,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14509,142,2993,0,200,2992,2994,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14510,142,2994,0,201,2993,2995,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14511,142,2995,0,202,2994,2996,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14512,142,2996,0,203,2995,2994,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14513,142,2994,0,204,2996,2997,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14514,142,2997,0,205,2994,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14515,142,2728,0,206,2997,2998,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14516,142,2998,0,207,2728,2999,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14517,142,2999,0,208,2998,3000,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14518,142,3000,0,209,2999,2747,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14519,142,2747,0,210,3000,2712,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14520,142,2712,0,211,2747,3001,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14521,142,3001,0,212,2712,3002,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14522,142,3002,0,213,3001,2777,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14523,142,2777,0,214,3002,2707,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14524,142,2707,0,215,2777,3003,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14525,142,3003,0,216,2707,3000,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14526,142,3000,0,217,3003,2711,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14527,142,2711,0,218,3000,2785,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14528,142,2785,0,219,2711,2786,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14529,142,2786,0,220,2785,2917,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14530,142,2917,0,221,2786,2777,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14531,142,2777,0,222,2917,2707,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14532,142,2707,0,223,2777,3003,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14533,142,3003,0,224,2707,3004,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14534,142,3004,0,225,3003,3005,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14535,142,3005,0,226,3004,2934,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14536,142,2934,0,227,3005,2871,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14537,142,2871,0,228,2934,3006,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14538,142,3006,0,229,2871,2859,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14539,142,2859,0,230,3006,2698,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14540,142,2698,0,231,2859,2740,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14541,142,2740,0,232,2698,2812,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14542,142,2812,0,233,2740,2981,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14543,142,2981,0,234,2812,2901,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14544,142,2901,0,235,2981,2957,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14545,142,2957,0,236,2901,3007,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14546,142,3007,0,237,2957,2699,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14547,142,2699,0,238,3007,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14548,142,2719,0,239,2699,3008,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14549,142,3008,0,240,2719,3009,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14550,142,3009,0,241,3008,3010,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14551,142,3010,0,242,3009,2709,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14552,142,2709,0,243,3010,2827,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14553,142,2827,0,244,2709,2928,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14554,142,2928,0,245,2827,2929,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14555,142,2929,0,246,2928,3011,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14556,142,3011,0,247,2929,3012,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14557,142,3012,0,248,3011,2819,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14558,142,2819,0,249,3012,3013,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14559,142,3013,0,250,2819,2812,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14560,142,2812,0,251,3013,2981,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14561,142,2981,0,252,2812,3014,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14562,142,3014,0,253,2981,2982,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14563,142,2982,0,254,3014,2849,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14564,142,2849,0,255,2982,2983,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14565,142,2983,0,256,2849,2955,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14566,142,2955,0,257,2983,3015,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14567,142,3015,0,258,2955,2777,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14568,142,2777,0,259,3015,2707,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14569,142,2707,0,260,2777,3003,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14570,142,3003,0,261,2707,3016,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14571,142,3016,0,262,3003,2728,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14572,142,2728,0,263,3016,3007,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14573,142,3007,0,264,2728,2747,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14574,142,2747,0,265,3007,3017,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14575,142,3017,0,266,2747,2731,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14576,142,2731,0,267,3017,2849,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14577,142,2849,0,268,2731,2761,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14578,142,2761,0,269,2849,2719,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14579,142,2719,0,270,2761,3018,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14580,142,3018,0,271,2719,2829,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14581,142,2829,0,272,3018,2795,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14582,142,2795,0,273,2829,3019,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14583,142,3019,0,274,2795,2761,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14584,142,2761,0,275,3019,2712,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14585,142,2712,0,276,2761,3020,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14586,142,3020,0,277,2712,0,10,1069757199,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14587,143,3021,0,0,0,2923,10,1069757424,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14588,143,2923,0,1,3021,3021,10,1069757424,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14589,143,3021,0,2,2923,2923,10,1069757424,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14590,143,2923,0,3,3021,2845,10,1069757424,1,140,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14591,143,2845,0,4,2923,2849,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14592,143,2849,0,5,2845,3022,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14593,143,3022,0,6,2849,2761,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14594,143,2761,0,7,3022,2807,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14595,143,2807,0,8,2761,3023,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14596,143,3023,0,9,2807,2845,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14597,143,2845,0,10,3023,2699,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14598,143,2699,0,11,2845,2879,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14599,143,2879,0,12,2699,3024,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14600,143,3024,0,13,2879,2845,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14601,143,2845,0,14,3024,2805,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14602,143,2805,0,15,2845,2761,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14603,143,2761,0,16,2805,3025,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14604,143,3025,0,17,2761,2707,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14605,143,2707,0,18,3025,3026,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14606,143,3026,0,19,2707,2862,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14607,143,2862,0,20,3026,3027,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14608,143,3027,0,21,2862,2976,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14609,143,2976,0,22,3027,2707,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14610,143,2707,0,23,2976,3028,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14611,143,3028,0,24,2707,2976,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14612,143,2976,0,25,3028,2819,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14613,143,2819,0,26,2976,3029,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14614,143,3029,0,27,2819,3030,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14615,143,3030,0,28,3029,2845,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14616,143,2845,0,29,3030,2699,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14617,143,2699,0,30,2845,3031,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14618,143,3031,0,31,2699,2703,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14619,143,2703,0,32,3031,3032,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14620,143,3032,0,33,2703,3033,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14621,143,3033,0,34,3032,3011,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14622,143,3011,0,35,3033,2728,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14623,143,2728,0,36,3011,2807,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14624,143,2807,0,37,2728,3034,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14625,143,3034,0,38,2807,2728,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14626,143,2728,0,39,3034,3035,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14627,143,3035,0,40,2728,2868,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14628,143,2868,0,41,3035,2707,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14629,143,2707,0,42,2868,2976,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14630,143,2976,0,43,2707,2845,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14631,143,2845,0,44,2976,2849,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14632,143,2849,0,45,2845,3022,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14633,143,3022,0,46,2849,2761,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14634,143,2761,0,47,3022,2807,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14635,143,2807,0,48,2761,3023,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14636,143,3023,0,49,2807,2845,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14637,143,2845,0,50,3023,2699,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14638,143,2699,0,51,2845,2879,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14639,143,2879,0,52,2699,3024,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14640,143,3024,0,53,2879,2845,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14641,143,2845,0,54,3024,2805,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14642,143,2805,0,55,2845,2761,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14643,143,2761,0,56,2805,3025,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14644,143,3025,0,57,2761,2707,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14645,143,2707,0,58,3025,3026,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14646,143,3026,0,59,2707,2862,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14647,143,2862,0,60,3026,3027,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14648,143,3027,0,61,2862,2976,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14649,143,2976,0,62,3027,2707,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14650,143,2707,0,63,2976,3028,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14651,143,3028,0,64,2707,2976,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14652,143,2976,0,65,3028,2819,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14653,143,2819,0,66,2976,3029,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14654,143,3029,0,67,2819,3030,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14655,143,3030,0,68,3029,2845,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14656,143,2845,0,69,3030,2699,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14657,143,2699,0,70,2845,3031,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14658,143,3031,0,71,2699,2703,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14659,143,2703,0,72,3031,3032,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14660,143,3032,0,73,2703,3033,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14661,143,3033,0,74,3032,3011,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14662,143,3011,0,75,3033,2728,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14663,143,2728,0,76,3011,2807,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14664,143,2807,0,77,2728,3034,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14665,143,3034,0,78,2807,2728,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14666,143,2728,0,79,3034,3035,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14667,143,3035,0,80,2728,2868,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14668,143,2868,0,81,3035,2707,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14669,143,2707,0,82,2868,2976,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14670,143,2976,0,83,2707,0,10,1069757424,1,141,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14671,137,2919,0,0,0,2908,19,1068027382,1,181,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14672,137,2908,0,1,2919,2919,19,1068027382,1,181,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14673,137,2919,0,2,2908,2908,19,1068027382,1,181,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14674,137,2908,0,3,2919,3036,19,1068027382,1,181,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14675,137,3036,0,4,2908,2733,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14676,137,2733,0,5,3036,2712,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14677,137,2712,0,6,2733,3037,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14678,137,3037,0,7,2712,3038,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14679,137,3038,0,8,3037,2859,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14680,137,2859,0,9,3038,2698,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14681,137,2698,0,10,2859,2740,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14682,137,2740,0,11,2698,3039,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14683,137,3039,0,12,2740,3040,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14684,137,3040,0,13,3039,3041,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14685,137,3041,0,14,3040,3042,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14686,137,3042,0,15,3041,2761,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14687,137,2761,0,16,3042,3036,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14688,137,3036,0,17,2761,2733,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14689,137,2733,0,18,3036,2807,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14690,137,2807,0,19,2733,2795,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14691,137,2795,0,20,2807,3019,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14692,137,3019,0,21,2795,3043,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14693,137,3043,0,22,3019,3036,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14694,137,3036,0,23,3043,2733,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14695,137,2733,0,24,3036,2712,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14696,137,2712,0,25,2733,3037,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14697,137,3037,0,26,2712,3038,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14698,137,3038,0,27,3037,2859,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14699,137,2859,0,28,3038,2698,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14700,137,2698,0,29,2859,2740,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14701,137,2740,0,30,2698,3039,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14702,137,3039,0,31,2740,3040,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14703,137,3040,0,32,3039,3041,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14704,137,3041,0,33,3040,3042,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14705,137,3042,0,34,3041,2761,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14706,137,2761,0,35,3042,3036,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14707,137,3036,0,36,2761,2733,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14708,137,2733,0,37,3036,2807,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14709,137,2807,0,38,2733,2795,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14710,137,2795,0,39,2807,3019,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14711,137,3019,0,40,2795,3043,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14712,137,3043,0,41,3019,0,19,1068027382,1,182,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14713,49,2798,0,0,0,2798,1,1066398020,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14714,49,2798,0,1,2798,0,1,1066398020,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14715,58,3044,0,0,0,2798,1,1066729196,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14716,58,2798,0,1,3044,3044,1,1066729196,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14717,58,3044,0,2,2798,2798,1,1066729196,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (14718,58,2798,0,3,3044,0,1,1066729196,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16366,92,2789,0,783,2712,0,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16365,92,2712,0,782,2709,2789,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16364,92,2709,0,781,3323,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16363,92,3323,0,780,2819,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16362,92,2819,0,779,3322,3323,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16361,92,3322,0,778,3209,2819,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16360,92,3209,0,777,2819,3322,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16359,92,2819,0,776,2784,3209,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16358,92,2784,0,775,3321,2819,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16357,92,3321,0,774,2728,2784,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16356,92,2728,0,773,3320,3321,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16355,92,3320,0,772,2703,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16354,92,2703,0,771,2747,3320,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16353,92,2747,0,770,2960,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16352,92,2960,0,769,3319,2747,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16351,92,3319,0,768,3318,2960,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16350,92,3318,0,767,2805,3319,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16349,92,2805,0,766,3317,3318,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16348,92,3317,0,765,2814,2805,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16347,92,2814,0,764,2753,3317,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16346,92,2753,0,763,3316,2814,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16345,92,3316,0,762,2728,2753,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16344,92,2728,0,761,2785,3316,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16343,92,2785,0,760,2777,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16342,92,2777,0,759,3315,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16341,92,3315,0,758,2703,2777,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16340,92,2703,0,757,2829,3315,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16339,92,2829,0,756,3314,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16338,92,3314,0,755,2761,2829,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16337,92,2761,0,754,3279,3314,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16336,92,3279,0,753,3313,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16335,92,3313,0,752,3192,3279,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16334,92,3192,0,751,2712,3313,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16333,92,2712,0,750,3312,3192,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16332,92,3312,0,749,3311,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16331,92,3311,0,748,2712,3312,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16330,92,2712,0,747,2740,3311,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16329,92,2740,0,746,2698,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16328,92,2698,0,745,2859,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16327,92,2859,0,744,2944,2698,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16326,92,2944,0,743,2709,2859,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16325,92,2709,0,742,3310,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16324,92,3310,0,741,3309,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16323,92,3309,0,740,2707,3310,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16322,92,2707,0,739,2761,3309,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16321,92,2761,0,738,3308,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16320,92,3308,0,737,2761,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16319,92,2761,0,736,3307,3308,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16318,92,3307,0,735,2728,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16317,92,2728,0,734,2813,3307,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16316,92,2813,0,733,2812,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16315,92,2812,0,732,3265,2813,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16314,92,3265,0,731,2845,2812,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16313,92,2845,0,730,3306,3265,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16312,92,3306,0,729,2703,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16311,92,2703,0,728,3255,3306,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16310,92,3255,0,727,2777,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16309,92,2777,0,726,2924,3255,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16308,92,2924,0,725,2899,2777,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16307,92,2899,0,724,3305,2924,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16306,92,3305,0,723,2819,2899,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16305,92,2819,0,722,3304,3305,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16304,92,3304,0,721,3303,2819,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16303,92,3303,0,720,2761,3304,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16302,92,2761,0,719,3302,3303,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16301,92,3302,0,718,2849,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16300,92,2849,0,717,2771,3302,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16299,92,2771,0,716,2842,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16298,92,2842,0,715,3027,2771,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16297,92,3027,0,714,2761,2842,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16296,92,2761,0,713,3278,3027,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16295,92,3278,0,712,2733,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16294,92,2733,0,711,3301,3278,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16293,92,3301,0,710,3253,2733,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16292,92,3253,0,709,2849,3301,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16291,92,2849,0,708,3236,3253,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16290,92,3236,0,707,3194,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16289,92,3194,0,706,2920,3236,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16288,92,2920,0,705,3300,3194,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16287,92,3300,0,704,2707,2920,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16286,92,2707,0,703,3299,3300,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16285,92,3299,0,702,3298,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16284,92,3298,0,701,3297,3299,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16283,92,3297,0,700,3296,3298,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16282,92,3296,0,699,3295,3297,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16281,92,3295,0,698,2712,3296,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16280,92,2712,0,697,2709,3295,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16279,92,2709,0,696,3294,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16278,92,3294,0,695,2711,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16277,92,2711,0,694,2908,3294,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16276,92,2908,0,693,2761,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16275,92,2761,0,692,3293,2908,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16274,92,3293,0,691,2771,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16273,92,2771,0,690,2885,3293,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16272,92,2885,0,689,2707,2771,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16271,92,2707,0,688,2711,2885,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16270,92,2711,0,687,2835,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16269,92,2835,0,686,2925,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16268,92,2925,0,685,2711,2835,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16267,92,2711,0,684,2869,2925,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16266,92,2869,0,683,2924,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16265,92,2924,0,682,2753,2869,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16264,92,2753,0,681,3292,2924,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16263,92,3292,0,680,2709,2753,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16262,92,2709,0,679,3220,3292,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16261,92,3220,0,678,2707,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16260,92,2707,0,677,3291,3220,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16259,92,3291,0,676,2761,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16258,92,2761,0,675,2740,3291,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16257,92,2740,0,674,3290,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16256,92,3290,0,673,3289,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16255,92,3289,0,672,3288,3290,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16254,92,3288,0,671,3287,3289,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16253,92,3287,0,670,2835,3288,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16252,92,2835,0,669,3286,3287,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16251,92,3286,0,668,2868,2835,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16250,92,2868,0,667,3285,3286,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16249,92,3285,0,666,3284,2868,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16248,92,3284,0,665,2829,3285,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16247,92,2829,0,664,3283,3284,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16246,92,3283,0,663,3265,2829,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16245,92,3265,0,662,2845,3283,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16244,92,2845,0,661,3282,3265,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16243,92,3282,0,660,2737,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16242,92,2737,0,659,3248,3282,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16241,92,3248,0,658,2730,2737,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16240,92,2730,0,657,2854,3248,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16239,92,2854,0,656,2758,2730,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16238,92,2758,0,655,3281,2854,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16237,92,3281,0,654,3243,2758,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16236,92,3243,0,653,2944,3281,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16235,92,2944,0,652,3194,3243,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16234,92,3194,0,651,3280,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16233,92,3280,0,650,2868,3194,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16232,92,2868,0,649,3245,3280,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16231,92,3245,0,648,3279,2868,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16230,92,3279,0,647,2728,3245,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16229,92,2728,0,646,2785,3279,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16228,92,2785,0,645,2709,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16227,92,2709,0,644,3257,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16226,92,3257,0,643,2712,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16225,92,2712,0,642,2702,3257,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16224,92,2702,0,641,3278,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16223,92,3278,0,640,3277,2702,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16222,92,3277,0,639,2845,3278,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16221,92,2845,0,638,3276,3277,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16220,92,3276,0,637,3193,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16219,92,3193,0,636,2712,3276,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16218,92,2712,0,635,2709,3193,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16217,92,2709,0,634,3275,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16216,92,3275,0,633,2712,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16215,92,2712,0,632,3274,3275,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16214,92,3274,0,631,2727,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16213,92,2727,0,630,3267,3274,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16212,92,3267,0,629,2699,2727,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16211,92,2699,0,628,2944,3267,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16210,92,2944,0,627,2963,2699,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16209,92,2963,0,626,2804,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16208,92,2804,0,625,2815,2963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16207,92,2815,0,624,3128,2804,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16206,92,3128,0,623,3273,2815,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16205,92,3273,0,622,2845,3128,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16204,92,2845,0,621,3272,3273,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16203,92,3272,0,620,3234,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16202,92,3234,0,619,3271,3272,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16201,92,3271,0,618,2761,3234,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16200,92,2761,0,617,2728,3271,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16199,92,2728,0,616,3270,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16198,92,3270,0,615,2761,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16197,92,2761,0,614,3270,3270,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16196,92,3270,0,613,2951,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16195,92,2951,0,612,2761,3270,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16194,92,2761,0,611,3269,2951,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16193,92,3269,0,610,2728,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16192,92,2728,0,609,3268,3269,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16191,92,3268,0,608,2950,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16190,92,2950,0,607,2777,3268,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16189,92,2777,0,606,2730,2950,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16188,92,2730,0,605,2727,2777,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16187,92,2727,0,604,3267,2730,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16186,92,3267,0,603,2963,2727,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16185,92,2963,0,602,2712,3267,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16184,92,2712,0,601,2815,2963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16183,92,2815,0,600,3266,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16182,92,3266,0,599,3265,2815,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16181,92,3265,0,598,2845,3266,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16180,92,2845,0,597,2963,3265,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16179,92,2963,0,596,2709,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16178,92,2709,0,595,3264,2963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16177,92,3264,0,594,2966,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16176,92,2966,0,593,2965,3264,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16175,92,2965,0,592,3263,2966,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16174,92,3263,0,591,2761,2965,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16173,92,2761,0,590,2960,3263,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16172,92,2960,0,589,3262,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16171,92,3262,0,588,2849,2960,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16170,92,2849,0,587,2771,3262,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16169,92,2771,0,586,3261,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16168,92,3261,0,585,3260,2771,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16167,92,3260,0,584,2747,3261,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16166,92,2747,0,583,2963,3260,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16165,92,2963,0,582,2785,2747,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16164,92,2785,0,581,2728,2963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16163,92,2728,0,580,3259,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16162,92,3259,0,579,2707,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16161,92,2707,0,578,3258,3259,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16160,92,3258,0,577,2709,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16159,92,2709,0,576,3257,3258,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16158,92,3257,0,575,2712,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16157,92,2712,0,574,3256,3257,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16156,92,3256,0,573,2761,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16155,92,2761,0,572,3255,3256,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16154,92,3255,0,571,2719,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16153,92,2719,0,570,2805,3255,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16152,92,2805,0,569,3226,2719,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16151,92,3226,0,568,2786,2805,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16150,92,2786,0,567,2785,3226,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16149,92,2785,0,566,2784,2786,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16148,92,2784,0,565,3254,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16147,92,3254,0,564,2875,2784,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16146,92,2875,0,563,3253,3254,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16145,92,3253,0,562,3252,2875,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16144,92,3252,0,561,3249,3253,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16143,92,3249,0,560,2737,3252,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16142,92,2737,0,559,3251,3249,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16141,92,3251,0,558,2767,2737,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16140,92,2767,0,557,3250,3251,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16139,92,3250,0,556,3249,2767,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16138,92,3249,0,555,3248,3250,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16137,92,3248,0,554,3247,3249,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16136,92,3247,0,553,3246,3248,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16135,92,3246,0,552,2728,3247,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16134,92,2728,0,551,3245,3246,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16133,92,3245,0,550,2786,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16132,92,2786,0,549,2785,3245,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16131,92,2785,0,548,2747,2786,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16130,92,2747,0,547,3244,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16129,92,3244,0,546,3243,2747,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16128,92,3243,0,545,2849,3244,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16127,92,2849,0,544,2944,3243,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16126,92,2944,0,543,3194,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16125,92,3194,0,542,2998,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16124,92,2998,0,541,3242,3194,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16123,92,3242,0,540,2709,2998,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16122,92,2709,0,539,3241,3242,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16121,92,3241,0,538,3240,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16120,92,3240,0,537,2712,3241,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16119,92,2712,0,536,3239,3240,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16118,92,3239,0,535,3238,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16117,92,3238,0,534,2728,3239,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16116,92,2728,0,533,3237,3238,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16115,92,3237,0,532,3236,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16114,92,3236,0,531,2728,3237,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16113,92,2728,0,530,3235,3236,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16112,92,3235,0,529,3234,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16111,92,3234,0,528,3233,3235,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16110,92,3233,0,527,2849,3234,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16109,92,2849,0,526,3232,3233,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16108,92,3232,0,525,2709,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16107,92,2709,0,524,3224,3232,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16106,92,3224,0,523,3231,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16105,92,3231,0,522,2728,3224,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16104,92,2728,0,521,3230,3231,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16103,92,3230,0,520,2711,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16102,92,2711,0,519,3229,3230,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16101,92,3229,0,518,2849,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16100,92,2849,0,517,3228,3229,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16099,92,3228,0,516,2712,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16098,92,2712,0,515,2792,3228,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16097,92,2792,0,514,2791,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16096,92,2791,0,513,2728,2792,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16095,92,2728,0,512,2789,2791,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16094,92,2789,0,511,2788,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16093,92,2788,0,510,2787,2789,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16092,92,2787,0,509,2786,2788,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16091,92,2786,0,508,2785,2787,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16090,92,2785,0,507,2784,2786,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16089,92,2784,0,506,2777,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16088,92,2777,0,505,3227,2784,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16087,92,3227,0,504,3226,2777,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16086,92,3226,0,503,3225,3227,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16085,92,3225,0,502,2703,3226,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16084,92,2703,0,501,2709,3225,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16083,92,2709,0,500,2957,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16082,92,2957,0,499,3194,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16081,92,3194,0,498,2740,2957,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16080,92,2740,0,497,3222,3194,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16079,92,3222,0,496,2771,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16078,92,2771,0,495,2944,3222,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16077,92,2944,0,494,3224,2771,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16076,92,3224,0,493,2849,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16075,92,2849,0,492,2920,3224,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16074,92,2920,0,491,2815,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16073,92,2815,0,490,3223,2920,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16072,92,3223,0,489,2730,2815,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16071,92,2730,0,488,3222,3223,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16070,92,3222,0,487,2908,2730,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16069,92,2908,0,486,3221,3222,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16068,92,3221,0,485,2740,2908,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16067,92,2740,0,484,2944,3221,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16066,92,2944,0,483,2709,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16065,92,2709,0,482,3220,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16064,92,3220,0,481,2707,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16063,92,2707,0,480,2728,3220,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16062,92,2728,0,479,2826,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16061,92,2826,0,478,2707,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16060,92,2707,0,477,2697,2826,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16059,92,2697,0,476,2774,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16058,92,2774,0,475,3219,2697,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16057,92,3219,0,474,2740,2774,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16056,92,2740,0,473,2845,3219,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16055,92,2845,0,472,3218,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16054,92,3218,0,471,2716,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16053,92,2716,0,470,3217,3218,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16052,92,3217,0,469,3216,2716,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16051,92,3216,0,468,2812,3217,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16050,92,2812,0,467,2849,3216,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16049,92,2849,0,466,3215,2812,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16048,92,3215,0,465,2838,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16047,92,2838,0,464,2703,3215,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16046,92,2703,0,463,3192,2838,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16045,92,3192,0,462,2712,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16044,92,2712,0,461,2711,3192,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16043,92,2711,0,460,3214,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16042,92,3214,0,459,3191,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16041,92,3191,0,458,2707,3214,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16040,92,2707,0,457,3213,3191,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16039,92,3213,0,456,3212,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16038,92,3212,0,455,2972,3213,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16037,92,2972,0,454,2712,3212,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16036,92,2712,0,453,3197,2972,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16035,92,3197,0,452,2712,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16034,92,2712,0,451,2711,3197,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16033,92,2711,0,450,3191,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16032,92,3191,0,449,3196,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16031,92,3196,0,448,3211,3191,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16030,92,3211,0,447,2712,3196,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16029,92,2712,0,446,2761,3211,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16028,92,2761,0,445,3210,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16027,92,3210,0,444,2712,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16026,92,2712,0,443,2711,3210,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16025,92,2711,0,442,3209,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16024,92,3209,0,441,2819,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16023,92,2819,0,440,2849,3209,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16022,92,2849,0,439,3195,2819,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16021,92,3195,0,438,2837,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16020,92,2837,0,437,3208,3195,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16019,92,3208,0,436,2789,2837,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16018,92,2789,0,435,2712,3208,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16017,92,2712,0,434,2709,2789,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16016,92,2709,0,433,3323,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16015,92,3323,0,432,2819,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16014,92,2819,0,431,3322,3323,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16013,92,3322,0,430,3209,2819,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16012,92,3209,0,429,2819,3322,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16011,92,2819,0,428,2784,3209,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16010,92,2784,0,427,3321,2819,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16009,92,3321,0,426,2728,2784,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16008,92,2728,0,425,3320,3321,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16007,92,3320,0,424,2703,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16006,92,2703,0,423,2747,3320,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16005,92,2747,0,422,2960,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16004,92,2960,0,421,3319,2747,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16003,92,3319,0,420,3318,2960,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16002,92,3318,0,419,2805,3319,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16001,92,2805,0,418,3317,3318,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16000,92,3317,0,417,2814,2805,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15999,92,2814,0,416,2753,3317,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15998,92,2753,0,415,3316,2814,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15997,92,3316,0,414,2728,2753,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15996,92,2728,0,413,2785,3316,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15995,92,2785,0,412,2777,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15994,92,2777,0,411,3315,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15993,92,3315,0,410,2703,2777,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15992,92,2703,0,409,2829,3315,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15991,92,2829,0,408,3314,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15990,92,3314,0,407,2761,2829,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15989,92,2761,0,406,3279,3314,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15988,92,3279,0,405,3313,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15987,92,3313,0,404,3192,3279,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15986,92,3192,0,403,2712,3313,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15985,92,2712,0,402,3312,3192,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15984,92,3312,0,401,3311,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15983,92,3311,0,400,2712,3312,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15982,92,2712,0,399,2740,3311,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15981,92,2740,0,398,2698,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15980,92,2698,0,397,2859,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15979,92,2859,0,396,2944,2698,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15978,92,2944,0,395,2709,2859,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15977,92,2709,0,394,3310,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15976,92,3310,0,393,3309,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15975,92,3309,0,392,2707,3310,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15974,92,2707,0,391,2761,3309,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15973,92,2761,0,390,3308,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15972,92,3308,0,389,2761,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15971,92,2761,0,388,3307,3308,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15970,92,3307,0,387,2728,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15969,92,2728,0,386,2813,3307,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15968,92,2813,0,385,2812,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15967,92,2812,0,384,3265,2813,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15966,92,3265,0,383,2845,2812,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15965,92,2845,0,382,3306,3265,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15964,92,3306,0,381,2703,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15963,92,2703,0,380,3255,3306,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15962,92,3255,0,379,2777,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15961,92,2777,0,378,2924,3255,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15960,92,2924,0,377,2899,2777,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15959,92,2899,0,376,3305,2924,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15958,92,3305,0,375,2819,2899,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15957,92,2819,0,374,3304,3305,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15956,92,3304,0,373,3303,2819,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15955,92,3303,0,372,2761,3304,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15954,92,2761,0,371,3302,3303,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15953,92,3302,0,370,2849,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15952,92,2849,0,369,2771,3302,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15951,92,2771,0,368,2842,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15950,92,2842,0,367,3027,2771,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15949,92,3027,0,366,2761,2842,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15948,92,2761,0,365,3278,3027,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15947,92,3278,0,364,2733,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15946,92,2733,0,363,3301,3278,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15945,92,3301,0,362,3253,2733,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15944,92,3253,0,361,2849,3301,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15943,92,2849,0,360,3236,3253,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15942,92,3236,0,359,3194,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15941,92,3194,0,358,2920,3236,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15940,92,2920,0,357,3300,3194,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15939,92,3300,0,356,2707,2920,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15938,92,2707,0,355,3299,3300,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15937,92,3299,0,354,3298,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15936,92,3298,0,353,3297,3299,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15935,92,3297,0,352,3296,3298,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15934,92,3296,0,351,3295,3297,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15933,92,3295,0,350,2712,3296,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15932,92,2712,0,349,2709,3295,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15931,92,2709,0,348,3294,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15930,92,3294,0,347,2711,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15929,92,2711,0,346,2908,3294,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15928,92,2908,0,345,2761,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15927,92,2761,0,344,3293,2908,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15926,92,3293,0,343,2771,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15925,92,2771,0,342,2885,3293,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15924,92,2885,0,341,2707,2771,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15923,92,2707,0,340,2711,2885,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15922,92,2711,0,339,2835,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15921,92,2835,0,338,2925,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15920,92,2925,0,337,2711,2835,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15919,92,2711,0,336,2869,2925,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15918,92,2869,0,335,2924,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15917,92,2924,0,334,2753,2869,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15916,92,2753,0,333,3292,2924,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15915,92,3292,0,332,2709,2753,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15914,92,2709,0,331,3220,3292,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15913,92,3220,0,330,2707,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15912,92,2707,0,329,3291,3220,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15911,92,3291,0,328,2761,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15910,92,2761,0,327,2740,3291,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15909,92,2740,0,326,3290,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15908,92,3290,0,325,3289,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15907,92,3289,0,324,3288,3290,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15906,92,3288,0,323,3287,3289,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15905,92,3287,0,322,2835,3288,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15904,92,2835,0,321,3286,3287,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15903,92,3286,0,320,2868,2835,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15902,92,2868,0,319,3285,3286,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15901,92,3285,0,318,3284,2868,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15900,92,3284,0,317,2829,3285,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15899,92,2829,0,316,3283,3284,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15898,92,3283,0,315,3265,2829,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15897,92,3265,0,314,2845,3283,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15896,92,2845,0,313,3282,3265,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15895,92,3282,0,312,2737,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15894,92,2737,0,311,3248,3282,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15893,92,3248,0,310,2730,2737,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15892,92,2730,0,309,2854,3248,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15891,92,2854,0,308,2758,2730,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15890,92,2758,0,307,3281,2854,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15889,92,3281,0,306,3243,2758,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15888,92,3243,0,305,2944,3281,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15887,92,2944,0,304,3194,3243,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15886,92,3194,0,303,3280,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15885,92,3280,0,302,2868,3194,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15884,92,2868,0,301,3245,3280,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15883,92,3245,0,300,3279,2868,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15882,92,3279,0,299,2728,3245,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15881,92,2728,0,298,2785,3279,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15880,92,2785,0,297,2709,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15879,92,2709,0,296,3257,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15878,92,3257,0,295,2712,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15877,92,2712,0,294,2702,3257,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15876,92,2702,0,293,3278,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15875,92,3278,0,292,3277,2702,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15874,92,3277,0,291,2845,3278,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15873,92,2845,0,290,3276,3277,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15872,92,3276,0,289,3193,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15871,92,3193,0,288,2712,3276,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15870,92,2712,0,287,2709,3193,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15869,92,2709,0,286,3275,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15868,92,3275,0,285,2712,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15867,92,2712,0,284,3274,3275,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15866,92,3274,0,283,2727,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15865,92,2727,0,282,3267,3274,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15864,92,3267,0,281,2699,2727,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15863,92,2699,0,280,2944,3267,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15862,92,2944,0,279,2963,2699,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15861,92,2963,0,278,2804,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15860,92,2804,0,277,2815,2963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15859,92,2815,0,276,3128,2804,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15858,92,3128,0,275,3273,2815,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15857,92,3273,0,274,2845,3128,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15856,92,2845,0,273,3272,3273,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15855,92,3272,0,272,3234,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15854,92,3234,0,271,3271,3272,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15853,92,3271,0,270,2761,3234,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15852,92,2761,0,269,2728,3271,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15851,92,2728,0,268,3270,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15850,92,3270,0,267,2761,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15849,92,2761,0,266,3270,3270,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15848,92,3270,0,265,2951,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15847,92,2951,0,264,2761,3270,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15846,92,2761,0,263,3269,2951,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15845,92,3269,0,262,2728,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15844,92,2728,0,261,3268,3269,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15843,92,3268,0,260,2950,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15842,92,2950,0,259,2777,3268,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15841,92,2777,0,258,2730,2950,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15840,92,2730,0,257,2727,2777,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15839,92,2727,0,256,3267,2730,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15838,92,3267,0,255,2963,2727,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15837,92,2963,0,254,2712,3267,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15836,92,2712,0,253,2815,2963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15835,92,2815,0,252,3266,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15834,92,3266,0,251,3265,2815,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15833,92,3265,0,250,2845,3266,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15832,92,2845,0,249,2963,3265,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15831,92,2963,0,248,2709,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15830,92,2709,0,247,3264,2963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15829,92,3264,0,246,2966,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15828,92,2966,0,245,2965,3264,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15827,92,2965,0,244,3263,2966,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15826,92,3263,0,243,2761,2965,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15825,92,2761,0,242,2960,3263,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15824,92,2960,0,241,3262,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15823,92,3262,0,240,2849,2960,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15822,92,2849,0,239,2771,3262,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15821,92,2771,0,238,3261,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15820,92,3261,0,237,3260,2771,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15819,92,3260,0,236,2747,3261,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15818,92,2747,0,235,2963,3260,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15817,92,2963,0,234,2785,2747,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15816,92,2785,0,233,2728,2963,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15815,92,2728,0,232,3259,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15814,92,3259,0,231,2707,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15813,92,2707,0,230,3258,3259,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15812,92,3258,0,229,2709,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15811,92,2709,0,228,3257,3258,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15810,92,3257,0,227,2712,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15809,92,2712,0,226,3256,3257,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15808,92,3256,0,225,2761,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15807,92,2761,0,224,3255,3256,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15806,92,3255,0,223,2719,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15805,92,2719,0,222,2805,3255,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15804,92,2805,0,221,3226,2719,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15803,92,3226,0,220,2786,2805,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15802,92,2786,0,219,2785,3226,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15801,92,2785,0,218,2784,2786,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15800,92,2784,0,217,3254,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15799,92,3254,0,216,2875,2784,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15798,92,2875,0,215,3253,3254,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15797,92,3253,0,214,3252,2875,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15796,92,3252,0,213,3249,3253,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15795,92,3249,0,212,2737,3252,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15794,92,2737,0,211,3251,3249,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15793,92,3251,0,210,2767,2737,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15792,92,2767,0,209,3250,3251,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15791,92,3250,0,208,3249,2767,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15790,92,3249,0,207,3248,3250,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15789,92,3248,0,206,3247,3249,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15788,92,3247,0,205,3246,3248,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15787,92,3246,0,204,2728,3247,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15786,92,2728,0,203,3245,3246,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15785,92,3245,0,202,2786,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15784,92,2786,0,201,2785,3245,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15783,92,2785,0,200,2747,2786,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15782,92,2747,0,199,3244,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15781,92,3244,0,198,3243,2747,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15780,92,3243,0,197,2849,3244,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15779,92,2849,0,196,2944,3243,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15778,92,2944,0,195,3194,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15777,92,3194,0,194,2998,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15776,92,2998,0,193,3242,3194,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15775,92,3242,0,192,2709,2998,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15774,92,2709,0,191,3241,3242,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15773,92,3241,0,190,3240,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15772,92,3240,0,189,2712,3241,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15771,92,2712,0,188,3239,3240,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15770,92,3239,0,187,3238,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15769,92,3238,0,186,2728,3239,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15768,92,2728,0,185,3237,3238,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15767,92,3237,0,184,3236,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15766,92,3236,0,183,2728,3237,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15765,92,2728,0,182,3235,3236,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15764,92,3235,0,181,3234,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15763,92,3234,0,180,3233,3235,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15762,92,3233,0,179,2849,3234,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15761,92,2849,0,178,3232,3233,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15760,92,3232,0,177,2709,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15759,92,2709,0,176,3224,3232,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15758,92,3224,0,175,3231,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15757,92,3231,0,174,2728,3224,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15756,92,2728,0,173,3230,3231,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15755,92,3230,0,172,2711,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15754,92,2711,0,171,3229,3230,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15753,92,3229,0,170,2849,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15752,92,2849,0,169,3228,3229,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15751,92,3228,0,168,2712,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15750,92,2712,0,167,2792,3228,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15749,92,2792,0,166,2791,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15748,92,2791,0,165,2728,2792,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15747,92,2728,0,164,2789,2791,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15746,92,2789,0,163,2788,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15745,92,2788,0,162,2787,2789,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15744,92,2787,0,161,2786,2788,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15743,92,2786,0,160,2785,2787,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15742,92,2785,0,159,2784,2786,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15741,92,2784,0,158,2777,2785,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15740,92,2777,0,157,3227,2784,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15739,92,3227,0,156,3226,2777,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15738,92,3226,0,155,3225,3227,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15737,92,3225,0,154,2703,3226,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15736,92,2703,0,153,2709,3225,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15735,92,2709,0,152,2957,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15734,92,2957,0,151,3194,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15733,92,3194,0,150,2740,2957,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15732,92,2740,0,149,3222,3194,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15731,92,3222,0,148,2771,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15730,92,2771,0,147,2944,3222,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15729,92,2944,0,146,3224,2771,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15728,92,3224,0,145,2849,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15727,92,2849,0,144,2920,3224,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15726,92,2920,0,143,2815,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15725,92,2815,0,142,3223,2920,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15724,92,3223,0,141,2730,2815,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15723,92,2730,0,140,3222,3223,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15722,92,3222,0,139,2908,2730,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15721,92,2908,0,138,3221,3222,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15720,92,3221,0,137,2740,2908,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15719,92,2740,0,136,2944,3221,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15718,92,2944,0,135,2709,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15717,92,2709,0,134,3220,2944,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15716,92,3220,0,133,2707,2709,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15715,92,2707,0,132,2728,3220,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15714,92,2728,0,131,2826,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15713,92,2826,0,130,2707,2728,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15712,92,2707,0,129,2697,2826,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15711,92,2697,0,128,2774,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15710,92,2774,0,127,3219,2697,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15709,92,3219,0,126,2740,2774,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15708,92,2740,0,125,2845,3219,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15707,92,2845,0,124,3218,2740,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15706,92,3218,0,123,2716,2845,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15705,92,2716,0,122,3217,3218,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15704,92,3217,0,121,3216,2716,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15703,92,3216,0,120,2812,3217,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15702,92,2812,0,119,2849,3216,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15701,92,2849,0,118,3215,2812,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15700,92,3215,0,117,2838,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15699,92,2838,0,116,2703,3215,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15698,92,2703,0,115,3192,2838,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15697,92,3192,0,114,2712,2703,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15696,92,2712,0,113,2711,3192,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15695,92,2711,0,112,3214,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15694,92,3214,0,111,3191,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15693,92,3191,0,110,2707,3214,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15692,92,2707,0,109,3213,3191,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15691,92,3213,0,108,3212,2707,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15690,92,3212,0,107,2972,3213,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15689,92,2972,0,106,2712,3212,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15688,92,2712,0,105,3197,2972,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15687,92,3197,0,104,2712,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15686,92,2712,0,103,2711,3197,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15685,92,2711,0,102,3191,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15684,92,3191,0,101,3196,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15683,92,3196,0,100,3211,3191,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15682,92,3211,0,99,2712,3196,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15681,92,2712,0,98,2761,3211,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15680,92,2761,0,97,3210,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15679,92,3210,0,96,2712,2761,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15678,92,2712,0,95,2711,3210,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15677,92,2711,0,94,3209,2712,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15676,92,3209,0,93,2819,2711,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15675,92,2819,0,92,2849,3209,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15674,92,2849,0,91,3195,2819,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15673,92,3195,0,90,2837,2849,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15672,92,2837,0,89,3208,3195,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15671,92,3208,0,88,3207,2837,2,1066828821,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15670,92,3207,0,87,3206,3208,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15669,92,3206,0,86,2712,3207,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15668,92,2712,0,85,2753,3206,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15667,92,2753,0,84,3205,2712,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15666,92,3205,0,83,2777,2753,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15665,92,2777,0,82,3192,3205,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15664,92,3192,0,81,3202,2777,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15663,92,3202,0,80,2712,3192,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15662,92,2712,0,79,3204,3202,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15661,92,3204,0,78,2728,2712,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15660,92,2728,0,77,3203,3204,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15659,92,3203,0,76,2701,2728,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15658,92,2701,0,75,2753,3203,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15657,92,2753,0,74,3192,2701,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15656,92,3192,0,73,3202,2753,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15655,92,3202,0,72,3201,3192,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15654,92,3201,0,71,2784,3202,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15653,92,2784,0,70,2777,3201,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15652,92,2777,0,69,2714,2784,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15651,92,2714,0,68,3192,2777,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15650,92,3192,0,67,2704,2714,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15649,92,2704,0,66,3200,3192,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15648,92,3200,0,65,3192,2704,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15647,92,3192,0,64,3199,3200,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15646,92,3199,0,63,3198,3192,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15645,92,3198,0,62,2707,3199,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15644,92,2707,0,61,3197,3198,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15643,92,3197,0,60,2711,2707,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15642,92,2711,0,59,3191,3197,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15641,92,3191,0,58,3196,2711,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15640,92,3196,0,57,2849,3191,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15639,92,2849,0,56,2837,3196,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15638,92,2837,0,55,2712,2849,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15637,92,2712,0,54,2709,2837,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15636,92,2709,0,53,3195,2712,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15635,92,3195,0,52,3194,2709,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15634,92,3194,0,51,3193,3195,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15633,92,3193,0,50,2924,3194,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15632,92,2924,0,49,3207,3193,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15631,92,3207,0,48,3206,2924,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15630,92,3206,0,47,2712,3207,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15629,92,2712,0,46,2753,3206,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15628,92,2753,0,45,3205,2712,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15627,92,3205,0,44,2777,2753,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15626,92,2777,0,43,3192,3205,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15625,92,3192,0,42,3202,2777,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15624,92,3202,0,41,2712,3192,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15623,92,2712,0,40,3204,3202,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15622,92,3204,0,39,2728,2712,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15621,92,2728,0,38,3203,3204,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15620,92,3203,0,37,2701,2728,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15619,92,2701,0,36,2753,3203,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15618,92,2753,0,35,3192,2701,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15617,92,3192,0,34,3202,2753,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15616,92,3202,0,33,3201,3192,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15615,92,3201,0,32,2784,3202,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15614,92,2784,0,31,2777,3201,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15613,92,2777,0,30,2714,2784,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15612,92,2714,0,29,3192,2777,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15611,92,3192,0,28,2704,2714,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15610,92,2704,0,27,3200,3192,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15609,92,3200,0,26,3192,2704,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15608,92,3192,0,25,3199,3200,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15607,92,3199,0,24,3198,3192,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15606,92,3198,0,23,2707,3199,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15605,92,2707,0,22,3197,3198,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15604,92,3197,0,21,2711,2707,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15603,92,2711,0,20,3191,3197,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15602,92,3191,0,19,3196,2711,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15601,92,3196,0,18,2849,3191,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15600,92,2849,0,17,2837,3196,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15599,92,2837,0,16,2712,2849,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15598,92,2712,0,15,2709,2837,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15597,92,2709,0,14,3195,2712,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15596,92,3195,0,13,3194,2709,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15595,92,3194,0,12,3193,3195,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15594,92,3193,0,11,2924,3194,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15593,92,2924,0,10,2714,3193,2,1066828821,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15592,92,2714,0,9,3192,2924,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15591,92,3192,0,8,2704,2714,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15590,92,2704,0,7,2711,3192,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15589,92,2711,0,6,3191,2704,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15588,92,3191,0,5,2714,2711,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15587,92,2714,0,4,3192,3191,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15586,92,3192,0,3,2704,2714,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15585,92,2704,0,2,2711,3192,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15584,92,2711,0,1,3191,2704,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15583,92,3191,0,0,0,2711,2,1066828821,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15503,59,3179,0,0,0,3180,1,1066729211,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15504,59,3180,0,1,3179,3179,1,1066729211,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15505,59,3179,0,2,3180,3180,1,1066729211,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15506,59,3180,0,3,3179,0,1,1066729211,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15507,138,2725,0,0,0,3181,2,1069755162,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15508,138,3181,0,1,2725,2725,2,1069755162,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15509,138,2725,0,2,3181,3181,2,1069755162,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15510,138,3181,0,3,2725,2845,2,1069755162,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15511,138,2845,0,4,3181,2740,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15512,138,2740,0,5,2845,2970,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15513,138,2970,0,6,2740,3182,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15514,138,3182,0,7,2970,2703,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15515,138,2703,0,8,3182,2725,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15516,138,2725,0,9,2703,3181,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15517,138,3181,0,10,2725,3183,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15518,138,3183,0,11,3181,3128,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15519,138,3128,0,12,3183,2815,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15520,138,2815,0,13,3128,2730,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15521,138,2730,0,14,2815,2777,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15522,138,2777,0,15,2730,3184,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15523,138,3184,0,16,2777,2761,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15524,138,2761,0,17,3184,2700,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15525,138,2700,0,18,2761,3185,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15526,138,3185,0,19,2700,2702,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15527,138,2702,0,20,3185,2712,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15528,138,2712,0,21,2702,2925,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15529,138,2925,0,22,2712,2728,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15530,138,2728,0,23,2925,2853,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15531,138,2853,0,24,2728,2845,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15532,138,2845,0,25,2853,2846,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15533,138,2846,0,26,2845,2711,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15534,138,2711,0,27,2846,2970,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15535,138,2970,0,28,2711,2819,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15536,138,2819,0,29,2970,2845,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15537,138,2845,0,30,2819,2740,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15538,138,2740,0,31,2845,2970,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15539,138,2970,0,32,2740,3182,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15540,138,3182,0,33,2970,2703,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15541,138,2703,0,34,3182,2725,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15542,138,2725,0,35,2703,3181,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15543,138,3181,0,36,2725,3183,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15544,138,3183,0,37,3181,3128,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15545,138,3128,0,38,3183,2815,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15546,138,2815,0,39,3128,2730,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15547,138,2730,0,40,2815,2777,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15548,138,2777,0,41,2730,3184,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15549,138,3184,0,42,2777,2761,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15550,138,2761,0,43,3184,2700,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15551,138,2700,0,44,2761,3185,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15552,138,3185,0,45,2700,2702,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15553,138,2702,0,46,3185,2712,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15554,138,2712,0,47,2702,2925,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15555,138,2925,0,48,2712,2728,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15556,138,2728,0,49,2925,2853,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15557,138,2853,0,50,2728,2845,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15558,138,2845,0,51,2853,2846,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15559,138,2846,0,52,2845,2711,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15560,138,2711,0,53,2846,2970,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15561,138,2970,0,54,2711,2819,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15562,138,2819,0,55,2970,2712,2,1069755162,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15563,138,2712,0,56,2819,3040,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15564,138,3040,0,57,2712,2845,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15565,138,2845,0,58,3040,2740,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15566,138,2740,0,59,2845,3186,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15567,138,3186,0,60,2740,3187,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15568,138,3187,0,61,3186,3188,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15569,138,3188,0,62,3187,3189,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15570,138,3189,0,63,3188,2924,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15571,138,2924,0,64,3189,2712,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15572,138,2712,0,65,2924,3040,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15573,138,3040,0,66,2712,2845,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15574,138,2845,0,67,3040,2740,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15575,138,2740,0,68,2845,3186,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15576,138,3186,0,69,2740,3187,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15577,138,3187,0,70,3186,3188,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15578,138,3188,0,71,3187,3189,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15579,138,3189,0,72,3188,2924,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15580,138,2924,0,73,3189,0,2,1069755162,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15581,60,3190,0,0,0,3190,1,1066729226,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (15582,60,3190,0,1,3190,0,1,1066729226,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16367,61,3324,0,0,0,2798,1,1066729258,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16368,61,2798,0,1,3324,3324,1,1066729258,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16369,61,3324,0,2,2798,2798,1,1066729258,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16370,61,2798,0,3,3324,0,1,1066729258,4,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16371,94,3325,0,0,0,3326,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16372,94,3326,0,1,3325,3327,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16373,94,3327,0,2,3326,2908,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16374,94,2908,0,3,3327,3325,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16375,94,3325,0,4,2908,3326,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16376,94,3326,0,5,3325,3327,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16377,94,3327,0,6,3326,2908,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16378,94,2908,0,7,3327,3325,2,1066829047,4,1,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16379,94,3325,0,8,2908,3326,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16380,94,3326,0,9,3325,3328,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16381,94,3328,0,10,3326,3329,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16382,94,3329,0,11,3328,2734,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16383,94,2734,0,12,3329,2703,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16384,94,2703,0,13,2734,3330,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16385,94,3330,0,14,2703,2780,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16386,94,2780,0,15,3330,2699,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16387,94,2699,0,16,2780,2719,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16388,94,2719,0,17,2699,2733,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16389,94,2733,0,18,2719,3331,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16390,94,3331,0,19,2733,2709,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16391,94,2709,0,20,3331,2712,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16392,94,2712,0,21,2709,3332,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16393,94,3332,0,22,2712,3333,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16394,94,3333,0,23,3332,2845,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16395,94,2845,0,24,3333,3334,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16396,94,3334,0,25,2845,3335,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16397,94,3335,0,26,3334,2711,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16398,94,2711,0,27,3335,3336,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16399,94,3336,0,28,2711,3337,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16400,94,3337,0,29,3336,3338,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16401,94,3338,0,30,3337,2734,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16402,94,2734,0,31,3338,3339,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16403,94,3339,0,32,2734,3010,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16404,94,3010,0,33,3339,3325,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16405,94,3325,0,34,3010,3326,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16406,94,3326,0,35,3325,3328,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16407,94,3328,0,36,3326,3329,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16408,94,3329,0,37,3328,2734,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16409,94,2734,0,38,3329,2703,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16410,94,2703,0,39,2734,3330,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16411,94,3330,0,40,2703,2780,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16412,94,2780,0,41,3330,2699,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16413,94,2699,0,42,2780,2719,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16414,94,2719,0,43,2699,2733,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16415,94,2733,0,44,2719,3331,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16416,94,3331,0,45,2733,2709,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16417,94,2709,0,46,3331,2712,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16418,94,2712,0,47,2709,3332,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16419,94,3332,0,48,2712,3333,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16420,94,3333,0,49,3332,2845,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16421,94,2845,0,50,3333,3334,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16422,94,3334,0,51,2845,3335,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16423,94,3335,0,52,3334,2711,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16424,94,2711,0,53,3335,3336,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16425,94,3336,0,54,2711,3337,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16426,94,3337,0,55,3336,3338,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16427,94,3338,0,56,3337,2734,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16428,94,2734,0,57,3338,3339,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16429,94,3339,0,58,2734,3010,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16430,94,3010,0,59,3339,3336,2,1066829047,4,120,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16431,94,3336,0,60,3010,3340,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16432,94,3340,0,61,3336,2777,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16433,94,2777,0,62,3340,3325,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16434,94,3325,0,63,2777,3326,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16435,94,3326,0,64,3325,2728,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16436,94,2728,0,65,3326,3183,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16437,94,3183,0,66,2728,3128,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16438,94,3128,0,67,3183,2698,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16439,94,2698,0,68,3128,2716,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16440,94,2716,0,69,2698,3341,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16441,94,3341,0,70,2716,3335,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16442,94,3335,0,71,3341,2763,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16443,94,2763,0,72,3335,2703,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16444,94,2703,0,73,2763,3342,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16445,94,3342,0,74,2703,3336,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16446,94,3336,0,75,3342,3340,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16447,94,3340,0,76,3336,2777,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16448,94,2777,0,77,3340,3325,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16449,94,3325,0,78,2777,3326,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16450,94,3326,0,79,3325,2728,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16451,94,2728,0,80,3326,3183,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16452,94,3183,0,81,2728,3128,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16453,94,3128,0,82,3183,2698,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16454,94,2698,0,83,3128,2716,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16455,94,2716,0,84,2698,3341,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16456,94,3341,0,85,2716,3335,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16457,94,3335,0,86,3341,2763,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16458,94,2763,0,87,3335,2703,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16459,94,2703,0,88,2763,3342,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16460,94,3342,0,89,2703,0,2,1066829047,4,121,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16461,11,3343,0,0,0,3344,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16462,11,3344,0,1,3343,3343,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16463,11,3343,0,2,3344,3344,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16464,11,3344,0,3,3343,0,3,1033920746,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16465,146,3345,0,0,0,3346,3,1072180743,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16466,146,3346,0,1,3345,3345,3,1072180743,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16467,146,3345,0,2,3346,3346,3,1072180743,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16468,146,3346,0,3,3345,3347,3,1072180743,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16469,146,3347,0,4,3346,3348,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16470,146,3348,0,5,3347,2753,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16471,146,2753,0,6,3348,2712,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16472,146,2712,0,7,2753,3345,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16473,146,3345,0,8,2712,3347,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16474,146,3347,0,9,3345,3347,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16475,146,3347,0,10,3347,3348,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16476,146,3348,0,11,3347,2753,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16477,146,2753,0,12,3348,2712,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16478,146,2712,0,13,2753,3345,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16479,146,3345,0,14,2712,3347,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16480,146,3347,0,15,3345,0,3,1072180743,2,7,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16481,10,3345,0,0,0,3345,4,1033920665,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16482,10,3345,0,1,3345,3347,4,1033920665,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16483,10,3347,0,2,3345,3347,4,1033920665,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16484,10,3347,0,3,3347,3345,4,1033920665,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16485,10,3345,0,4,3347,3349,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16486,10,3349,0,5,3345,3345,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16487,10,3345,0,6,3349,3349,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16488,10,3349,0,7,3345,0,4,1033920665,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16489,12,3350,0,0,0,3346,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16490,12,3346,0,1,3350,3350,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16491,12,3350,0,2,3346,3346,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16492,12,3346,0,3,3350,0,3,1033920775,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16493,14,3350,0,0,0,3350,4,1033920830,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16494,14,3350,0,1,3350,3347,4,1033920830,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16495,14,3347,0,2,3350,3347,4,1033920830,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16496,14,3347,0,3,3347,3351,4,1033920830,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16497,14,3351,0,4,3347,3349,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16498,14,3349,0,5,3351,3351,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16499,14,3351,0,6,3349,3349,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16500,14,3349,0,7,3351,0,4,1033920830,2,12,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16501,13,3352,0,0,0,3352,3,1033920794,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16502,13,3352,0,1,3352,0,3,1033920794,2,6,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16503,44,3353,0,0,0,3354,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16504,44,3354,0,1,3353,3353,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16505,44,3353,0,2,3354,3354,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16506,44,3354,0,3,3353,0,1,1066384457,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16507,43,3355,0,0,0,3355,14,1066384365,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16508,43,3355,0,1,3355,3356,14,1066384365,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16509,43,3356,0,2,3355,3357,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16510,43,3357,0,3,3356,3356,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16511,43,3356,0,4,3357,3357,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16512,43,3357,0,5,3356,0,14,1066384365,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16513,45,3358,0,0,0,2728,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16514,45,2728,0,1,3358,3313,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16515,45,3313,0,2,2728,3358,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16516,45,3358,0,3,3313,2728,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16517,45,2728,0,4,3358,3313,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16518,45,3313,0,5,2728,2787,14,1066388816,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16519,45,2787,0,6,3313,2823,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16520,45,2823,0,7,2787,3359,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16521,45,3359,0,8,2823,2787,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16522,45,2787,0,9,3359,2823,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16523,45,2823,0,10,2787,3359,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16524,45,3359,0,11,2823,0,14,1066388816,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16525,115,3360,0,0,0,3360,14,1066991725,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16526,115,3360,0,1,3360,3353,14,1066991725,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16527,115,3353,0,2,3360,3360,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16528,115,3360,0,3,3353,3353,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16529,115,3353,0,4,3360,3360,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16530,115,3360,0,5,3353,0,14,1066991725,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16531,116,3361,0,0,0,3362,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16532,116,3362,0,1,3361,3361,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16533,116,3361,0,2,3362,3362,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16534,116,3362,0,3,3361,2787,14,1066992054,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16535,116,2787,0,4,3362,3363,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16536,116,3363,0,5,2787,2787,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16537,116,2787,0,6,3363,3363,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16538,116,3363,0,7,2787,0,14,1066992054,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16539,46,3358,0,0,0,2728,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16540,46,2728,0,1,3358,3313,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16541,46,3313,0,2,2728,3358,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16542,46,3358,0,3,3313,2728,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16543,46,2728,0,4,3358,3313,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16544,46,3313,0,5,2728,0,1,1066389805,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16545,56,3364,0,0,0,3364,15,1066643397,11,161,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16546,56,3364,0,1,3364,3365,15,1066643397,11,161,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16547,56,3365,0,2,3364,3366,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16548,56,3366,0,3,3365,2974,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16549,56,2974,0,4,3366,3367,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16550,56,3367,0,5,2974,2868,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16551,56,2868,0,6,3367,3368,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16552,56,3368,0,7,2868,3369,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16553,56,3369,0,8,3368,3365,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16554,56,3365,0,9,3369,3366,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16555,56,3366,0,10,3365,2974,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16556,56,2974,0,11,3366,3367,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16557,56,3367,0,12,2974,2868,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16558,56,2868,0,13,3367,3368,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16559,56,3368,0,14,2868,3369,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16560,56,3369,0,15,3368,0,15,1066643397,11,188,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16561,147,3370,0,0,0,3371,1,1076578917,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16562,147,3371,0,1,3370,3372,1,1076578917,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16563,147,3372,0,2,3371,3370,1,1076578917,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16564,147,3370,0,3,3372,3371,1,1076578917,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16565,147,3371,0,4,3370,3372,1,1076578917,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16566,147,3372,0,5,3371,0,1,1076578917,11,4,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16590,148,3372,0,5,3371,0,20,1076578960,11,189,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16589,148,3371,0,4,3370,3372,20,1076578960,11,189,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16588,148,3370,0,3,3372,3371,20,1076578960,11,189,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16587,148,3372,0,2,3371,3370,20,1076578960,11,189,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16586,148,3371,0,1,3370,3372,20,1076578960,11,189,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16585,148,3370,0,0,0,3371,20,1076578960,11,189,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16573,149,3370,0,0,0,3371,14,1076579082,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16574,149,3371,0,1,3370,3372,14,1076579082,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16575,149,3372,0,2,3371,3370,14,1076579082,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16576,149,3370,0,3,3372,3371,14,1076579082,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16577,149,3371,0,4,3370,3372,14,1076579082,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16578,149,3372,0,5,3371,2787,14,1076579082,11,152,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16579,149,2787,0,6,3372,2823,14,1076579082,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16580,149,2823,0,7,2787,3373,14,1076579082,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16581,149,3373,0,8,2823,2787,14,1076579082,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16582,149,2787,0,9,3373,2823,14,1076579082,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16583,149,2823,0,10,2787,3373,14,1076579082,11,155,'',0);
INSERT INTO ezsearch_object_word_link VALUES (16584,149,3373,0,11,2823,0,14,1076579082,11,155,'',0);

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


INSERT INTO ezsearch_word VALUES (2696,'products',2);
INSERT INTO ezsearch_word VALUES (2697,'here',3);
INSERT INTO ezsearch_word VALUES (2698,'you',9);
INSERT INTO ezsearch_word VALUES (2699,'will',9);
INSERT INTO ezsearch_word VALUES (2700,'find',3);
INSERT INTO ezsearch_word VALUES (2701,'information',6);
INSERT INTO ezsearch_word VALUES (2702,'about',6);
INSERT INTO ezsearch_word VALUES (2703,'our',10);
INSERT INTO ezsearch_word VALUES (2704,'top',2);
INSERT INTO ezsearch_word VALUES (2705,'100',1);
INSERT INTO ezsearch_word VALUES (2706,'set',1);
INSERT INTO ezsearch_word VALUES (2707,'a',8);
INSERT INTO ezsearch_word VALUES (2708,'collection',1);
INSERT INTO ezsearch_word VALUES (2709,'of',6);
INSERT INTO ezsearch_word VALUES (2710,'music',1);
INSERT INTO ezsearch_word VALUES (2711,'from',6);
INSERT INTO ezsearch_word VALUES (2712,'the',11);
INSERT INTO ezsearch_word VALUES (2713,'year',1);
INSERT INTO ezsearch_word VALUES (2714,'2003',2);
INSERT INTO ezsearch_word VALUES (2715,'best',2);
INSERT INTO ezsearch_word VALUES (2716,'all',4);
INSERT INTO ezsearch_word VALUES (2717,'charts',1);
INSERT INTO ezsearch_word VALUES (2718,'mona',1);
INSERT INTO ezsearch_word VALUES (2719,'be',7);
INSERT INTO ezsearch_word VALUES (2720,'smarting',1);
INSERT INTO ezsearch_word VALUES (2721,'lacklustre',1);
INSERT INTO ezsearch_word VALUES (2722,'chart',1);
INSERT INTO ezsearch_word VALUES (2723,'position',1);
INSERT INTO ezsearch_word VALUES (2724,'her',1);
INSERT INTO ezsearch_word VALUES (2725,'new',3);
INSERT INTO ezsearch_word VALUES (2726,'album',1);
INSERT INTO ezsearch_word VALUES (2727,'up',2);
INSERT INTO ezsearch_word VALUES (2728,'and',12);
INSERT INTO ezsearch_word VALUES (2729,'go',1);
INSERT INTO ezsearch_word VALUES (2730,'it',3);
INSERT INTO ezsearch_word VALUES (2731,'s',2);
INSERT INTO ezsearch_word VALUES (2732,'come',1);
INSERT INTO ezsearch_word VALUES (2733,'in',6);
INSERT INTO ezsearch_word VALUES (2734,'at',2);
INSERT INTO ezsearch_word VALUES (2735,'no',1);
INSERT INTO ezsearch_word VALUES (2736,'234',1);
INSERT INTO ezsearch_word VALUES (2737,'when',2);
INSERT INTO ezsearch_word VALUES (2738,'surely',1);
INSERT INTO ezsearch_word VALUES (2739,'should',2);
INSERT INTO ezsearch_word VALUES (2740,'have',6);
INSERT INTO ezsearch_word VALUES (2741,'snagged',1);
INSERT INTO ezsearch_word VALUES (2742,'spot',1);
INSERT INTO ezsearch_word VALUES (2743,'fellow',1);
INSERT INTO ezsearch_word VALUES (2744,'babe',1);
INSERT INTO ezsearch_word VALUES (2745,'july',1);
INSERT INTO ezsearch_word VALUES (2746,'too',1);
INSERT INTO ezsearch_word VALUES (2747,'with',7);
INSERT INTO ezsearch_word VALUES (2748,'cd',1);
INSERT INTO ezsearch_word VALUES (2749,'manages',1);
INSERT INTO ezsearch_word VALUES (2750,'poor',1);
INSERT INTO ezsearch_word VALUES (2751,'343',1);
INSERT INTO ezsearch_word VALUES (2752,'but',1);
INSERT INTO ezsearch_word VALUES (2753,'for',6);
INSERT INTO ezsearch_word VALUES (2754,'tim',1);
INSERT INTO ezsearch_word VALUES (2755,'whose',1);
INSERT INTO ezsearch_word VALUES (2756,'inon',1);
INSERT INTO ezsearch_word VALUES (2757,'doesn',1);
INSERT INTO ezsearch_word VALUES (2758,'t',2);
INSERT INTO ezsearch_word VALUES (2759,'even',1);
INSERT INTO ezsearch_word VALUES (2760,'manage',1);
INSERT INTO ezsearch_word VALUES (2761,'to',10);
INSERT INTO ezsearch_word VALUES (2762,'scrape',1);
INSERT INTO ezsearch_word VALUES (2763,'into',2);
INSERT INTO ezsearch_word VALUES (2764,'20',1);
INSERT INTO ezsearch_word VALUES (2765,'once',1);
INSERT INTO ezsearch_word VALUES (2766,'mighty',1);
INSERT INTO ezsearch_word VALUES (2767,'seem',2);
INSERT INTO ezsearch_word VALUES (2768,'fragile',1);
INSERT INTO ezsearch_word VALUES (2769,'meanwhile',1);
INSERT INTO ezsearch_word VALUES (2770,'someone',1);
INSERT INTO ezsearch_word VALUES (2771,'who',2);
INSERT INTO ezsearch_word VALUES (2772,'reputation',1);
INSERT INTO ezsearch_word VALUES (2773,'has',1);
INSERT INTO ezsearch_word VALUES (2774,'been',2);
INSERT INTO ezsearch_word VALUES (2775,'seeming',1);
INSERT INTO ezsearch_word VALUES (2776,'really',1);
INSERT INTO ezsearch_word VALUES (2777,'is',8);
INSERT INTO ezsearch_word VALUES (2778,'joap',1);
INSERT INTO ezsearch_word VALUES (2779,'jackson',1);
INSERT INTO ezsearch_word VALUES (2780,'he',2);
INSERT INTO ezsearch_word VALUES (2781,'romps',1);
INSERT INTO ezsearch_word VALUES (2782,'1',1);
INSERT INTO ezsearch_word VALUES (2783,'publishabc',3);
INSERT INTO ezsearch_word VALUES (2784,'an',3);
INSERT INTO ezsearch_word VALUES (2785,'open',4);
INSERT INTO ezsearch_word VALUES (2786,'source',3);
INSERT INTO ezsearch_word VALUES (2787,'content',5);
INSERT INTO ezsearch_word VALUES (2788,'management',2);
INSERT INTO ezsearch_word VALUES (2789,'system',3);
INSERT INTO ezsearch_word VALUES (2790,'cms',1);
INSERT INTO ezsearch_word VALUES (2791,'development',5);
INSERT INTO ezsearch_word VALUES (2792,'framework',2);
INSERT INTO ezsearch_word VALUES (2793,'advanced',1);
INSERT INTO ezsearch_word VALUES (2794,'functionality',1);
INSERT INTO ezsearch_word VALUES (2795,'e',3);
INSERT INTO ezsearch_word VALUES (2796,'commerce',1);
INSERT INTO ezsearch_word VALUES (2797,'sites',1);
INSERT INTO ezsearch_word VALUES (2798,'news',4);
INSERT INTO ezsearch_word VALUES (2799,'forums',1);
INSERT INTO ezsearch_word VALUES (2800,'picture',1);
INSERT INTO ezsearch_word VALUES (2801,'galleries',1);
INSERT INTO ezsearch_word VALUES (2802,'intranets',1);
INSERT INTO ezsearch_word VALUES (2803,'much',1);
INSERT INTO ezsearch_word VALUES (2804,'more',2);
INSERT INTO ezsearch_word VALUES (2805,'can',5);
INSERT INTO ezsearch_word VALUES (2806,'build',1);
INSERT INTO ezsearch_word VALUES (2807,'your',6);
INSERT INTO ezsearch_word VALUES (2808,'dynamic',1);
INSERT INTO ezsearch_word VALUES (2809,'websites',1);
INSERT INTO ezsearch_word VALUES (2810,'fast',1);
INSERT INTO ezsearch_word VALUES (2811,'reliable',1);
INSERT INTO ezsearch_word VALUES (2812,'very',3);
INSERT INTO ezsearch_word VALUES (2813,'flexible',2);
INSERT INTO ezsearch_word VALUES (2814,'everyone',2);
INSERT INTO ezsearch_word VALUES (2815,'that',4);
INSERT INTO ezsearch_word VALUES (2816,'wants',1);
INSERT INTO ezsearch_word VALUES (2817,'share',2);
INSERT INTO ezsearch_word VALUES (2818,'their',1);
INSERT INTO ezsearch_word VALUES (2819,'on',7);
INSERT INTO ezsearch_word VALUES (2820,'web',2);
INSERT INTO ezsearch_word VALUES (2821,'easily',1);
INSERT INTO ezsearch_word VALUES (2822,'create',1);
INSERT INTO ezsearch_word VALUES (2823,'edit',3);
INSERT INTO ezsearch_word VALUES (2824,'publish',1);
INSERT INTO ezsearch_word VALUES (2825,'sorts',1);
INSERT INTO ezsearch_word VALUES (2826,'day',2);
INSERT INTO ezsearch_word VALUES (2827,'work',3);
INSERT INTO ezsearch_word VALUES (2828,'done',1);
INSERT INTO ezsearch_word VALUES (2829,'by',4);
INSERT INTO ezsearch_word VALUES (2830,'non',1);
INSERT INTO ezsearch_word VALUES (2831,'technical',1);
INSERT INTO ezsearch_word VALUES (2832,'persons',1);
INSERT INTO ezsearch_word VALUES (2833,'services',1);
INSERT INTO ezsearch_word VALUES (2834,'support',1);
INSERT INTO ezsearch_word VALUES (2835,'\"',2);
INSERT INTO ezsearch_word VALUES (2836,'use',2);
INSERT INTO ezsearch_word VALUES (2837,'crew',4);
INSERT INTO ezsearch_word VALUES (2838,'first',2);
INSERT INTO ezsearch_word VALUES (2839,'hand',1);
INSERT INTO ezsearch_word VALUES (2840,'information\"',1);
INSERT INTO ezsearch_word VALUES (2841,'guarantee',1);
INSERT INTO ezsearch_word VALUES (2842,'customers',2);
INSERT INTO ezsearch_word VALUES (2843,'possible',1);
INSERT INTO ezsearch_word VALUES (2844,'result',1);
INSERT INTO ezsearch_word VALUES (2845,'we',8);
INSERT INTO ezsearch_word VALUES (2846,'offer',2);
INSERT INTO ezsearch_word VALUES (2847,'program',1);
INSERT INTO ezsearch_word VALUES (2848,'professionals',1);
INSERT INTO ezsearch_word VALUES (2849,'are',4);
INSERT INTO ezsearch_word VALUES (2850,'ready',2);
INSERT INTO ezsearch_word VALUES (2851,'help',2);
INSERT INTO ezsearch_word VALUES (2852,'problem',1);
INSERT INTO ezsearch_word VALUES (2853,'what',3);
INSERT INTO ezsearch_word VALUES (2854,'get',2);
INSERT INTO ezsearch_word VALUES (2855,'cover',1);
INSERT INTO ezsearch_word VALUES (2856,'answers',1);
INSERT INTO ezsearch_word VALUES (2857,'email',1);
INSERT INTO ezsearch_word VALUES (2858,'phone',1);
INSERT INTO ezsearch_word VALUES (2859,'if',5);
INSERT INTO ezsearch_word VALUES (2860,'need',1);
INSERT INTO ezsearch_word VALUES (2861,'configure',1);
INSERT INTO ezsearch_word VALUES (2862,'or',3);
INSERT INTO ezsearch_word VALUES (2863,'develop',1);
INSERT INTO ezsearch_word VALUES (2864,'features',1);
INSERT INTO ezsearch_word VALUES (2865,'doing',1);
INSERT INTO ezsearch_word VALUES (2866,'directly',1);
INSERT INTO ezsearch_word VALUES (2867,'server',1);
INSERT INTO ezsearch_word VALUES (2868,'as',6);
INSERT INTO ezsearch_word VALUES (2869,'feature',3);
INSERT INTO ezsearch_word VALUES (2870,'distribution',1);
INSERT INTO ezsearch_word VALUES (2871,'also',3);
INSERT INTO ezsearch_word VALUES (2872,'able',1);
INSERT INTO ezsearch_word VALUES (2873,'give',1);
INSERT INTO ezsearch_word VALUES (2874,'advise',1);
INSERT INTO ezsearch_word VALUES (2875,'how',2);
INSERT INTO ezsearch_word VALUES (2876,'solve',1);
INSERT INTO ezsearch_word VALUES (2877,'problems',1);
INSERT INTO ezsearch_word VALUES (2878,'want',1);
INSERT INTO ezsearch_word VALUES (2879,'do',3);
INSERT INTO ezsearch_word VALUES (2880,'most',1);
INSERT INTO ezsearch_word VALUES (2881,'yourself',1);
INSERT INTO ezsearch_word VALUES (2882,'developments',1);
INSERT INTO ezsearch_word VALUES (2883,'enhancements',1);
INSERT INTO ezsearch_word VALUES (2884,'hire',1);
INSERT INTO ezsearch_word VALUES (2885,'guy',2);
INSERT INTO ezsearch_word VALUES (2886,'solution',1);
INSERT INTO ezsearch_word VALUES (2887,'friends',1);
INSERT INTO ezsearch_word VALUES (2888,'highly',1);
INSERT INTO ezsearch_word VALUES (2889,'skilled',1);
INSERT INTO ezsearch_word VALUES (2890,'developers',2);
INSERT INTO ezsearch_word VALUES (2891,'advice',1);
INSERT INTO ezsearch_word VALUES (2892,'consulting',1);
INSERT INTO ezsearch_word VALUES (2893,'ranges',1);
INSERT INTO ezsearch_word VALUES (2894,'requests',1);
INSERT INTO ezsearch_word VALUES (2895,'installation',1);
INSERT INTO ezsearch_word VALUES (2896,'upgrade',1);
INSERT INTO ezsearch_word VALUES (2897,'migration',1);
INSERT INTO ezsearch_word VALUES (2898,'integration',1);
INSERT INTO ezsearch_word VALUES (2899,'solutions',3);
INSERT INTO ezsearch_word VALUES (2900,'often',1);
INSERT INTO ezsearch_word VALUES (2901,'programming',2);
INSERT INTO ezsearch_word VALUES (2902,'etc',1);
INSERT INTO ezsearch_word VALUES (2903,'deliver',1);
INSERT INTO ezsearch_word VALUES (2904,'turn',1);
INSERT INTO ezsearch_word VALUES (2905,'key',1);
INSERT INTO ezsearch_word VALUES (2906,'based',1);
INSERT INTO ezsearch_word VALUES (2907,'let',1);
INSERT INTO ezsearch_word VALUES (2908,'us',4);
INSERT INTO ezsearch_word VALUES (2909,'know',2);
INSERT INTO ezsearch_word VALUES (2910,'standard',1);
INSERT INTO ezsearch_word VALUES (2911,'hourly',1);
INSERT INTO ezsearch_word VALUES (2912,'rate',1);
INSERT INTO ezsearch_word VALUES (2913,'129',1);
INSERT INTO ezsearch_word VALUES (2914,'minimum',1);
INSERT INTO ezsearch_word VALUES (2915,'asking',1);
INSERT INTO ezsearch_word VALUES (2916,'price',1);
INSERT INTO ezsearch_word VALUES (2917,'projects',2);
INSERT INTO ezsearch_word VALUES (2918,'2344',1);
INSERT INTO ezsearch_word VALUES (2919,'contact',2);
INSERT INTO ezsearch_word VALUES (2920,'there',2);
INSERT INTO ezsearch_word VALUES (2921,'something',1);
INSERT INTO ezsearch_word VALUES (2922,'general',1);
INSERT INTO ezsearch_word VALUES (2923,'info',2);
INSERT INTO ezsearch_word VALUES (2924,'this',3);
INSERT INTO ezsearch_word VALUES (2925,'company',4);
INSERT INTO ezsearch_word VALUES (2926,'my',1);
INSERT INTO ezsearch_word VALUES (2927,'located',1);
INSERT INTO ezsearch_word VALUES (2928,'skien',2);
INSERT INTO ezsearch_word VALUES (2929,'norway',2);
INSERT INTO ezsearch_word VALUES (2930,'223',1);
INSERT INTO ezsearch_word VALUES (2931,'employees',1);
INSERT INTO ezsearch_word VALUES (2932,'was',1);
INSERT INTO ezsearch_word VALUES (2933,'founded',1);
INSERT INTO ezsearch_word VALUES (2934,'may',2);
INSERT INTO ezsearch_word VALUES (2935,'1973',1);
INSERT INTO ezsearch_word VALUES (2936,'corporate',1);
INSERT INTO ezsearch_word VALUES (2937,'vision',1);
INSERT INTO ezsearch_word VALUES (2938,'\"we',1);
INSERT INTO ezsearch_word VALUES (2939,'shall',1);
INSERT INTO ezsearch_word VALUES (2940,'minded',1);
INSERT INTO ezsearch_word VALUES (2941,'dedicated',1);
INSERT INTO ezsearch_word VALUES (2942,'team',1);
INSERT INTO ezsearch_word VALUES (2943,'helping',1);
INSERT INTO ezsearch_word VALUES (2944,'people',2);
INSERT INTO ezsearch_word VALUES (2945,'businesses',1);
INSERT INTO ezsearch_word VALUES (2946,'around',1);
INSERT INTO ezsearch_word VALUES (2947,'world',1);
INSERT INTO ezsearch_word VALUES (2948,'knowledge\"',1);
INSERT INTO ezsearch_word VALUES (2949,'values',1);
INSERT INTO ezsearch_word VALUES (2950,'always',2);
INSERT INTO ezsearch_word VALUES (2951,'meet',2);
INSERT INTO ezsearch_word VALUES (2952,'mind',1);
INSERT INTO ezsearch_word VALUES (2953,'heart',1);
INSERT INTO ezsearch_word VALUES (2954,'welcoming',1);
INSERT INTO ezsearch_word VALUES (2955,'other',2);
INSERT INTO ezsearch_word VALUES (2956,'ideas',1);
INSERT INTO ezsearch_word VALUES (2957,'knowledge',3);
INSERT INTO ezsearch_word VALUES (2958,'sharing',1);
INSERT INTO ezsearch_word VALUES (2959,'pull',1);
INSERT INTO ezsearch_word VALUES (2960,'together',2);
INSERT INTO ezsearch_word VALUES (2961,'both',1);
INSERT INTO ezsearch_word VALUES (2962,'internally',1);
INSERT INTO ezsearch_word VALUES (2963,'community',2);
INSERT INTO ezsearch_word VALUES (2964,'accomplish',1);
INSERT INTO ezsearch_word VALUES (2965,'great',2);
INSERT INTO ezsearch_word VALUES (2966,'things',2);
INSERT INTO ezsearch_word VALUES (2967,'innovative',1);
INSERT INTO ezsearch_word VALUES (2968,'creating',1);
INSERT INTO ezsearch_word VALUES (2969,'career',1);
INSERT INTO ezsearch_word VALUES (2970,'now',2);
INSERT INTO ezsearch_word VALUES (2971,'hiring',1);
INSERT INTO ezsearch_word VALUES (2972,'following',2);
INSERT INTO ezsearch_word VALUES (2973,'part',1);
INSERT INTO ezsearch_word VALUES (2974,'ez',2);
INSERT INTO ezsearch_word VALUES (2975,'developing',1);
INSERT INTO ezsearch_word VALUES (2976,'customer',2);
INSERT INTO ezsearch_word VALUES (2977,'either',1);
INSERT INTO ezsearch_word VALUES (2978,'project',1);
INSERT INTO ezsearch_word VALUES (2979,'leader',1);
INSERT INTO ezsearch_word VALUES (2980,'developer',1);
INSERT INTO ezsearch_word VALUES (2981,'good',1);
INSERT INTO ezsearch_word VALUES (2982,'skills',1);
INSERT INTO ezsearch_word VALUES (2983,'required',1);
INSERT INTO ezsearch_word VALUES (2984,'must',1);
INSERT INTO ezsearch_word VALUES (2985,'object',1);
INSERT INTO ezsearch_word VALUES (2986,'oriented',1);
INSERT INTO ezsearch_word VALUES (2987,'design',1);
INSERT INTO ezsearch_word VALUES (2988,'using',1);
INSERT INTO ezsearch_word VALUES (2989,'c',1);
INSERT INTO ezsearch_word VALUES (2990,'php',1);
INSERT INTO ezsearch_word VALUES (2991,'familiar',1);
INSERT INTO ezsearch_word VALUES (2992,'uml',1);
INSERT INTO ezsearch_word VALUES (2993,'sql',1);
INSERT INTO ezsearch_word VALUES (2994,'xml',1);
INSERT INTO ezsearch_word VALUES (2995,'xhtml',1);
INSERT INTO ezsearch_word VALUES (2996,'soap',1);
INSERT INTO ezsearch_word VALUES (2997,'rpc',1);
INSERT INTO ezsearch_word VALUES (2998,'linux',2);
INSERT INTO ezsearch_word VALUES (2999,'unix',1);
INSERT INTO ezsearch_word VALUES (3000,'experience',1);
INSERT INTO ezsearch_word VALUES (3001,'qt',1);
INSERT INTO ezsearch_word VALUES (3002,'toolkit',1);
INSERT INTO ezsearch_word VALUES (3003,'plus',1);
INSERT INTO ezsearch_word VALUES (3004,'fresh',1);
INSERT INTO ezsearch_word VALUES (3005,'graduates',1);
INSERT INTO ezsearch_word VALUES (3006,'apply',1);
INSERT INTO ezsearch_word VALUES (3007,'applications',1);
INSERT INTO ezsearch_word VALUES (3008,'accepted',1);
INSERT INTO ezsearch_word VALUES (3009,'continually',1);
INSERT INTO ezsearch_word VALUES (3010,'place',2);
INSERT INTO ezsearch_word VALUES (3011,'conditions',2);
INSERT INTO ezsearch_word VALUES (3012,'depending',1);
INSERT INTO ezsearch_word VALUES (3013,'qualifications',1);
INSERT INTO ezsearch_word VALUES (3014,'english',1);
INSERT INTO ezsearch_word VALUES (3015,'languages',1);
INSERT INTO ezsearch_word VALUES (3016,'questions',1);
INSERT INTO ezsearch_word VALUES (3017,'cv',1);
INSERT INTO ezsearch_word VALUES (3018,'sent',1);
INSERT INTO ezsearch_word VALUES (3019,'mail',2);
INSERT INTO ezsearch_word VALUES (3020,'boss',1);
INSERT INTO ezsearch_word VALUES (3021,'shop',1);
INSERT INTO ezsearch_word VALUES (3022,'committed',1);
INSERT INTO ezsearch_word VALUES (3023,'satisfaction',1);
INSERT INTO ezsearch_word VALUES (3024,'everything',1);
INSERT INTO ezsearch_word VALUES (3025,'make',1);
INSERT INTO ezsearch_word VALUES (3026,'present',1);
INSERT INTO ezsearch_word VALUES (3027,'potential',2);
INSERT INTO ezsearch_word VALUES (3028,'satisfied',1);
INSERT INTO ezsearch_word VALUES (3029,'these',1);
INSERT INTO ezsearch_word VALUES (3030,'pages',1);
INSERT INTO ezsearch_word VALUES (3031,'outline',1);
INSERT INTO ezsearch_word VALUES (3032,'terms',1);
INSERT INTO ezsearch_word VALUES (3033,'&',1);
INSERT INTO ezsearch_word VALUES (3034,'rights',1);
INSERT INTO ezsearch_word VALUES (3035,'privacy',1);
INSERT INTO ezsearch_word VALUES (3036,'fill',1);
INSERT INTO ezsearch_word VALUES (3037,'form',1);
INSERT INTO ezsearch_word VALUES (3038,'below',1);
INSERT INTO ezsearch_word VALUES (3039,'any',1);
INSERT INTO ezsearch_word VALUES (3040,'feedback',2);
INSERT INTO ezsearch_word VALUES (3041,'please',1);
INSERT INTO ezsearch_word VALUES (3042,'remember',1);
INSERT INTO ezsearch_word VALUES (3043,'address',1);
INSERT INTO ezsearch_word VALUES (3044,'business',1);
INSERT INTO ezsearch_word VALUES (3291,'pay',1);
INSERT INTO ezsearch_word VALUES (3290,'would',1);
INSERT INTO ezsearch_word VALUES (3289,'\"i',1);
INSERT INTO ezsearch_word VALUES (3288,'smile',1);
INSERT INTO ezsearch_word VALUES (3287,'big',1);
INSERT INTO ezsearch_word VALUES (3286,'\"amazing',1);
INSERT INTO ezsearch_word VALUES (3285,'such',1);
INSERT INTO ezsearch_word VALUES (3284,'replies',1);
INSERT INTO ezsearch_word VALUES (3283,'met',1);
INSERT INTO ezsearch_word VALUES (3282,'explained',1);
INSERT INTO ezsearch_word VALUES (3281,'don',1);
INSERT INTO ezsearch_word VALUES (3280,'mentioned',1);
INSERT INTO ezsearch_word VALUES (3279,'free',1);
INSERT INTO ezsearch_word VALUES (3278,'talking',1);
INSERT INTO ezsearch_word VALUES (3277,'were',1);
INSERT INTO ezsearch_word VALUES (3276,'anyway',1);
INSERT INTO ezsearch_word VALUES (3275,'rest',1);
INSERT INTO ezsearch_word VALUES (3274,'during',1);
INSERT INTO ezsearch_word VALUES (3273,'certainly',1);
INSERT INTO ezsearch_word VALUES (3272,'topics',1);
INSERT INTO ezsearch_word VALUES (3271,'discuss',1);
INSERT INTO ezsearch_word VALUES (3270,'face',1);
INSERT INTO ezsearch_word VALUES (3269,'inspiring',1);
INSERT INTO ezsearch_word VALUES (3268,'interesting',1);
INSERT INTO ezsearch_word VALUES (3267,'show',1);
INSERT INTO ezsearch_word VALUES (3266,'happy',1);
INSERT INTO ezsearch_word VALUES (3265,'re',1);
INSERT INTO ezsearch_word VALUES (3264,'speaking',1);
INSERT INTO ezsearch_word VALUES (3263,'achieve',1);
INSERT INTO ezsearch_word VALUES (3262,'working',1);
INSERT INTO ezsearch_word VALUES (3261,'minds',1);
INSERT INTO ezsearch_word VALUES (3260,'creative',1);
INSERT INTO ezsearch_word VALUES (3259,'huge',1);
INSERT INTO ezsearch_word VALUES (3258,'having',1);
INSERT INTO ezsearch_word VALUES (3257,'benefits',1);
INSERT INTO ezsearch_word VALUES (3256,'mention',1);
INSERT INTO ezsearch_word VALUES (3255,'not',1);
INSERT INTO ezsearch_word VALUES (3254,'powerful',1);
INSERT INTO ezsearch_word VALUES (3253,'just',1);
INSERT INTO ezsearch_word VALUES (3252,'realize',1);
INSERT INTO ezsearch_word VALUES (3251,'impressed',1);
INSERT INTO ezsearch_word VALUES (3250,'sure',1);
INSERT INTO ezsearch_word VALUES (3249,'they',1);
INSERT INTO ezsearch_word VALUES (3248,'however',1);
INSERT INTO ezsearch_word VALUES (3247,'licenses',1);
INSERT INTO ezsearch_word VALUES (3246,'public',1);
INSERT INTO ezsearch_word VALUES (3245,'software',1);
INSERT INTO ezsearch_word VALUES (3244,'unfamiliar',1);
INSERT INTO ezsearch_word VALUES (3243,'still',1);
INSERT INTO ezsearch_word VALUES (3242,'gnu',1);
INSERT INTO ezsearch_word VALUES (3241,'success',1);
INSERT INTO ezsearch_word VALUES (3240,'enormous',1);
INSERT INTO ezsearch_word VALUES (3239,'despite',1);
INSERT INTO ezsearch_word VALUES (3238,'small',1);
INSERT INTO ezsearch_word VALUES (3237,'large',1);
INSERT INTO ezsearch_word VALUES (3236,'companies',1);
INSERT INTO ezsearch_word VALUES (3235,'organizations',1);
INSERT INTO ezsearch_word VALUES (3234,'various',1);
INSERT INTO ezsearch_word VALUES (3233,'representing',1);
INSERT INTO ezsearch_word VALUES (3232,'them',1);
INSERT INTO ezsearch_word VALUES (3231,'austria',1);
INSERT INTO ezsearch_word VALUES (3230,'germany',1);
INSERT INTO ezsearch_word VALUES (3229,'mostly',1);
INSERT INTO ezsearch_word VALUES (3228,'visitors',1);
INSERT INTO ezsearch_word VALUES (3128,'hope',3);
INSERT INTO ezsearch_word VALUES (3227,'which',1);
INSERT INTO ezsearch_word VALUES (3226,'product',1);
INSERT INTO ezsearch_word VALUES (3225,'main',1);
INSERT INTO ezsearch_word VALUES (3224,'many',1);
INSERT INTO ezsearch_word VALUES (3223,'seems',1);
INSERT INTO ezsearch_word VALUES (3222,'already',1);
INSERT INTO ezsearch_word VALUES (3221,'visited',1);
INSERT INTO ezsearch_word VALUES (3220,'lot',1);
INSERT INTO ezsearch_word VALUES (3219,'barely',1);
INSERT INTO ezsearch_word VALUES (3218,'expectations',1);
INSERT INTO ezsearch_word VALUES (3217,'exceeding',1);
INSERT INTO ezsearch_word VALUES (3216,'positive',1);
INSERT INTO ezsearch_word VALUES (3215,'impressions',1);
INSERT INTO ezsearch_word VALUES (3214,'report',1);
INSERT INTO ezsearch_word VALUES (3213,'contains',1);
INSERT INTO ezsearch_word VALUES (3212,'text',1);
INSERT INTO ezsearch_word VALUES (3211,'24th',1);
INSERT INTO ezsearch_word VALUES (3210,'20th',1);
INSERT INTO ezsearch_word VALUES (3209,'site',1);
INSERT INTO ezsearch_word VALUES (3208,'four',1);
INSERT INTO ezsearch_word VALUES (3207,'time',1);
INSERT INTO ezsearch_word VALUES (3206,'5th',1);
INSERT INTO ezsearch_word VALUES (3205,'held',1);
INSERT INTO ezsearch_word VALUES (3204,'telecommunications',1);
INSERT INTO ezsearch_word VALUES (3203,'technology',1);
INSERT INTO ezsearch_word VALUES (3202,'trade',1);
INSERT INTO ezsearch_word VALUES (3201,'international',1);
INSERT INTO ezsearch_word VALUES (3200,'2003\"',1);
INSERT INTO ezsearch_word VALUES (3199,'\"top',1);
INSERT INTO ezsearch_word VALUES (3198,'attending',1);
INSERT INTO ezsearch_word VALUES (3197,'hall',1);
INSERT INTO ezsearch_word VALUES (3196,'reporting',1);
INSERT INTO ezsearch_word VALUES (3195,'members',1);
INSERT INTO ezsearch_word VALUES (3194,'some',1);
INSERT INTO ezsearch_word VALUES (3193,'week',1);
INSERT INTO ezsearch_word VALUES (3192,'fair',1);
INSERT INTO ezsearch_word VALUES (3191,'live',1);
INSERT INTO ezsearch_word VALUES (3179,'off',1);
INSERT INTO ezsearch_word VALUES (3180,'topic',1);
INSERT INTO ezsearch_word VALUES (3181,'website',1);
INSERT INTO ezsearch_word VALUES (3182,'released',1);
INSERT INTO ezsearch_word VALUES (3183,'i',2);
INSERT INTO ezsearch_word VALUES (3184,'easier',1);
INSERT INTO ezsearch_word VALUES (3185,'iformation',1);
INSERT INTO ezsearch_word VALUES (3186,'gotten',1);
INSERT INTO ezsearch_word VALUES (3187,'so',1);
INSERT INTO ezsearch_word VALUES (3188,'far',1);
INSERT INTO ezsearch_word VALUES (3189,'indicates',1);
INSERT INTO ezsearch_word VALUES (3190,'reports',1);
INSERT INTO ezsearch_word VALUES (3292,'money',1);
INSERT INTO ezsearch_word VALUES (3293,'came',1);
INSERT INTO ezsearch_word VALUES (3294,'one',1);
INSERT INTO ezsearch_word VALUES (3295,'neighboring',1);
INSERT INTO ezsearch_word VALUES (3296,'stands',1);
INSERT INTO ezsearch_word VALUES (3297,'right',1);
INSERT INTO ezsearch_word VALUES (3298,'after',1);
INSERT INTO ezsearch_word VALUES (3299,'watching',1);
INSERT INTO ezsearch_word VALUES (3300,'presentation',1);
INSERT INTO ezsearch_word VALUES (3301,'interested',1);
INSERT INTO ezsearch_word VALUES (3302,'willing',1);
INSERT INTO ezsearch_word VALUES (3303,'spend',1);
INSERT INTO ezsearch_word VALUES (3304,'millions',1);
INSERT INTO ezsearch_word VALUES (3305,'rigid',1);
INSERT INTO ezsearch_word VALUES (3306,'policy',1);
INSERT INTO ezsearch_word VALUES (3307,'eager',1);
INSERT INTO ezsearch_word VALUES (3308,'talk',1);
INSERT INTO ezsearch_word VALUES (3309,'wide',1);
INSERT INTO ezsearch_word VALUES (3310,'range',1);
INSERT INTO ezsearch_word VALUES (3311,'chance',1);
INSERT INTO ezsearch_word VALUES (3312,'visit',1);
INSERT INTO ezsearch_word VALUES (3313,'feel',3);
INSERT INTO ezsearch_word VALUES (3314,'stop',1);
INSERT INTO ezsearch_word VALUES (3315,'stand',1);
INSERT INTO ezsearch_word VALUES (3316,'prepared',1);
INSERT INTO ezsearch_word VALUES (3317,'anybody',1);
INSERT INTO ezsearch_word VALUES (3318,'sit',1);
INSERT INTO ezsearch_word VALUES (3319,'down',1);
INSERT INTO ezsearch_word VALUES (3320,'representatives',1);
INSERT INTO ezsearch_word VALUES (3321,'receive',1);
INSERT INTO ezsearch_word VALUES (3322,'hands',1);
INSERT INTO ezsearch_word VALUES (3323,'demonstration',1);
INSERT INTO ezsearch_word VALUES (3324,'staff',1);
INSERT INTO ezsearch_word VALUES (3325,'mr',1);
INSERT INTO ezsearch_word VALUES (3326,'smith',1);
INSERT INTO ezsearch_word VALUES (3327,'joined',1);
INSERT INTO ezsearch_word VALUES (3328,'started',1);
INSERT INTO ezsearch_word VALUES (3329,'today',1);
INSERT INTO ezsearch_word VALUES (3330,'firm',1);
INSERT INTO ezsearch_word VALUES (3331,'charge',1);
INSERT INTO ezsearch_word VALUES (3332,'computer',1);
INSERT INTO ezsearch_word VALUES (3333,'matrix',1);
INSERT INTO ezsearch_word VALUES (3334,'hired',1);
INSERT INTO ezsearch_word VALUES (3335,'him',1);
INSERT INTO ezsearch_word VALUES (3336,'his',1);
INSERT INTO ezsearch_word VALUES (3337,'previous',1);
INSERT INTO ezsearch_word VALUES (3338,'workplace',1);
INSERT INTO ezsearch_word VALUES (3339,'nemos',1);
INSERT INTO ezsearch_word VALUES (3340,'name',1);
INSERT INTO ezsearch_word VALUES (3341,'welcome',1);
INSERT INTO ezsearch_word VALUES (3342,'ranks',1);
INSERT INTO ezsearch_word VALUES (3343,'guest',1);
INSERT INTO ezsearch_word VALUES (3344,'accounts',1);
INSERT INTO ezsearch_word VALUES (3345,'anonymous',2);
INSERT INTO ezsearch_word VALUES (3346,'users',2);
INSERT INTO ezsearch_word VALUES (3347,'user',3);
INSERT INTO ezsearch_word VALUES (3348,'group',1);
INSERT INTO ezsearch_word VALUES (3349,'nospam@ez.no',2);
INSERT INTO ezsearch_word VALUES (3350,'administrator',2);
INSERT INTO ezsearch_word VALUES (3351,'admin',1);
INSERT INTO ezsearch_word VALUES (3352,'editors',1);
INSERT INTO ezsearch_word VALUES (3353,'setup',2);
INSERT INTO ezsearch_word VALUES (3354,'links',1);
INSERT INTO ezsearch_word VALUES (3355,'classes',1);
INSERT INTO ezsearch_word VALUES (3356,'class',1);
INSERT INTO ezsearch_word VALUES (3357,'grouplist',1);
INSERT INTO ezsearch_word VALUES (3358,'look',2);
INSERT INTO ezsearch_word VALUES (3359,'56',1);
INSERT INTO ezsearch_word VALUES (3360,'cache',1);
INSERT INTO ezsearch_word VALUES (3361,'url',1);
INSERT INTO ezsearch_word VALUES (3362,'translator',1);
INSERT INTO ezsearch_word VALUES (3363,'urltranslator',1);
INSERT INTO ezsearch_word VALUES (3364,'corporate_package',1);
INSERT INTO ezsearch_word VALUES (3365,'copyright',1);
INSERT INTO ezsearch_word VALUES (3366,'&copy',1);
INSERT INTO ezsearch_word VALUES (3367,'systems',1);
INSERT INTO ezsearch_word VALUES (3368,'1999',1);
INSERT INTO ezsearch_word VALUES (3369,'2004',1);
INSERT INTO ezsearch_word VALUES (3370,'common',3);
INSERT INTO ezsearch_word VALUES (3371,'ini',3);
INSERT INTO ezsearch_word VALUES (3372,'settings',3);
INSERT INTO ezsearch_word VALUES (3373,'148',1);

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
INSERT INTO ezsection VALUES (4,'News','','ezcontentnavigationpart');
INSERT INTO ezsection VALUES (5,'Contact','','ezcontentnavigationpart');
INSERT INTO ezsection VALUES (6,'Files','','ezcontentnavigationpart');
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


INSERT INTO ezsession VALUES ('9aa48014039bbe5b29ee3ef05abffebb',1076838321,'LastAccessesURI|s:22:\"/content/view/full/122\";eZUserInfoCache_Timestamp|i:1076578721;eZUserGroupsCache_Timestamp|i:1076578721;eZUserLoggedInID|s:2:\"14\";eZUserInfoCache|a:1:{i:14;a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}}eZUserGroupsCache|a:1:{i:14;a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1076578721;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}eZUserDiscountRulesTimestamp|i:1076578721;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}CanInstantiateClassesCachedForUser|s:2:\"14\";ClassesCachedTimestamp|i:1076578874;CanInstantiateClasses|i:1;CanInstantiateClassList|a:11:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"19\";s:4:\"name\";s:13:\"Feedback form\";}i:10;a:2:{s:2:\"id\";s:2:\"20\";s:4:\"name\";s:19:\"Common ini settings\";}}eZPreferences|a:3:{s:13:\"bookmark_menu\";b:0;s:12:\"history_menu\";b:0;s:13:\"advanced_menu\";s:2:\"on\";}FromGroupID|b:0;ClassesCachedForUser|s:2:\"14\";');

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
INSERT INTO ezurlalias VALUES (15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0);
INSERT INTO ezurlalias VALUES (16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0);
INSERT INTO ezurlalias VALUES (17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0);
INSERT INTO ezurlalias VALUES (18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0);
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
INSERT INTO ezurlalias VALUES (37,'news/off_topic','c77d3081eac3bee15b0213bcc89b369b','content/view/full/57',1,0,0);
INSERT INTO ezurlalias VALUES (36,'news/business_news','bde42888705c25806fbe02b8570d055d','content/view/full/56',1,0,0);
INSERT INTO ezurlalias VALUES (34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,99,0);
INSERT INTO ezurlalias VALUES (38,'news/reports_','ac624940baa3e037e0467bf2db2743cb','content/view/full/58',1,39,0);
INSERT INTO ezurlalias VALUES (39,'news/reports','f3cbeafbd5dbf7477a9a803d47d4dcbb','content/view/full/58',1,0,0);
INSERT INTO ezurlalias VALUES (40,'news/staff_news','c50e4a6eb10a499c098857026282ceb4','content/view/full/59',1,0,0);
INSERT INTO ezurlalias VALUES (97,'information','bb3ccd5881d651448ded1dac904054ac','content/view/full/108',1,112,0);
INSERT INTO ezurlalias VALUES (111,'general_info/career','ea1c177fd7c868dc277cf107f26f668c','content/view/full/116',1,0,0);
INSERT INTO ezurlalias VALUES (99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,0,0);
INSERT INTO ezurlalias VALUES (90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0);
INSERT INTO ezurlalias VALUES (93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,99,0);
INSERT INTO ezurlalias VALUES (87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0);
INSERT INTO ezurlalias VALUES (88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0);
INSERT INTO ezurlalias VALUES (89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0);
INSERT INTO ezurlalias VALUES (59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0);
INSERT INTO ezurlalias VALUES (60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0);
INSERT INTO ezurlalias VALUES (61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0);
INSERT INTO ezurlalias VALUES (62,'news/business_news/ez_systems_reporting_live_from_munich','ddb9dceff37417877c5a030d5ca3e5b5','content/view/full/81',1,103,0);
INSERT INTO ezurlalias VALUES (105,'products/top_100_set','ef50df42a7d2fe7cff26830b3de58283','content/view/full/113',1,0,0);
INSERT INTO ezurlalias VALUES (64,'news/staff_news/mr_xxx_joined_us','6755615af39b3f3a145fd2a57a37809d','content/view/full/83',1,102,0);
INSERT INTO ezurlalias VALUES (65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0);
INSERT INTO ezurlalias VALUES (66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0);
INSERT INTO ezurlalias VALUES (84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0);
INSERT INTO ezurlalias VALUES (69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0);
INSERT INTO ezurlalias VALUES (71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0);
INSERT INTO ezurlalias VALUES (72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0);
INSERT INTO ezurlalias VALUES (73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0);
INSERT INTO ezurlalias VALUES (95,'products','86024cad1e83101d97359d7351051156','content/view/full/106',1,0,0);
INSERT INTO ezurlalias VALUES (96,'services','10cd395cf71c18328c863c08e78f3fd0','content/view/full/107',1,0,0);
INSERT INTO ezurlalias VALUES (78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0);
INSERT INTO ezurlalias VALUES (79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0);
INSERT INTO ezurlalias VALUES (80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0);
INSERT INTO ezurlalias VALUES (82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1);
INSERT INTO ezurlalias VALUES (86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0);
INSERT INTO ezurlalias VALUES (101,'news/off_topic/new_website','0c0589f38af62cd21f20d37e906bb5de','content/view/full/111',1,0,0);
INSERT INTO ezurlalias VALUES (102,'news/staff_news/mr_smith_joined_us','5f9ddd15b000a10b585cb57647e9f387','content/view/full/83',1,0,0);
INSERT INTO ezurlalias VALUES (103,'news/business_news/live_from_top_fair_2003','50fb0286625a02fd09c01b984cd985a9','content/view/full/81',1,0,0);
INSERT INTO ezurlalias VALUES (104,'news/reports/live_from_top_fair_2003','4577e798f398f1d9437338be5c9a83d5','content/view/full/112',1,0,0);
INSERT INTO ezurlalias VALUES (106,'products/publishabc','68eaddddc60054eef37a76b0fb429952','content/view/full/114',1,0,0);
INSERT INTO ezurlalias VALUES (108,'contact/contact_us','9f8a82d9487a7189ffee59fabbaceb89','content/view/full/110',1,117,0);
INSERT INTO ezurlalias VALUES (109,'about','46b3931b9959c927df4fc65fdee94b07','content/view/full/109',1,110,0);
INSERT INTO ezurlalias VALUES (110,'general_info/about','136f5f5c96ca444bbf07042b0597864d','content/view/full/109',1,0,0);
INSERT INTO ezurlalias VALUES (112,'general_info','fb56bf0921bfd932e96f9e2167884487','content/view/full/108',1,0,0);
INSERT INTO ezurlalias VALUES (113,'information/*','109c37699c2b15b48493419b460eb7c6','general_info/{1}',1,0,1);
INSERT INTO ezurlalias VALUES (114,'general_info/shop_info','2b3337b223f81931c8addf43fff88f69','content/view/full/117',1,0,0);
INSERT INTO ezurlalias VALUES (115,'services/support','5fb758997f086bac4a01a3058174bd1c','content/view/full/118',1,0,0);
INSERT INTO ezurlalias VALUES (116,'services/development','119490e5cab36746526adbd2432cfe75','content/view/full/119',1,0,0);
INSERT INTO ezurlalias VALUES (117,'contact_us','53a2c328fefc1efd85d75137a9d833ab','content/view/full/110',1,0,0);
INSERT INTO ezurlalias VALUES (118,'users/anonymous_users','3ae1aac958e1c82013689d917d34967a','content/view/full/120',1,0,0);
INSERT INTO ezurlalias VALUES (119,'users/anonymous_users/anonymous_user','aad93975f09371695ba08292fd9698db','content/view/full/121',1,0,0);
INSERT INTO ezurlalias VALUES (120,'setup/common_ini_settings','e501fe6c81ed14a5af2b322d248102d8','content/view/full/122',1,0,0);
INSERT INTO ezurlalias VALUES (121,'setup/common_ini_settings/common_ini_settings','51580fac4a0d382aede57bc43af6e63a','content/view/full/123',1,0,0);
INSERT INTO ezurlalias VALUES (122,'setup/setup_links/common_ini_settings','e85ca643d417d1d3b7459bc4eef7a1f0','content/view/full/124',1,0,0);

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
INSERT INTO ezuser_role VALUES (32,24,13);
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



