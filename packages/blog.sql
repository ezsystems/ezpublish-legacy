-- MySQL dump 10.2
--
-- Host: localhost    Database: blog
---------------------------------------------------------
-- Server version	4.1.0-alpha

--
-- Table structure for table 'ezapprove_items'
--

DROP TABLE IF EXISTS ezapprove_items;
CREATE TABLE ezapprove_items (
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) NOT NULL default '0',
  collaboration_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezapprove_items'
--

/*!40000 ALTER TABLE ezapprove_items DISABLE KEYS */;
LOCK TABLES ezapprove_items WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezapprove_items ENABLE KEYS */;

--
-- Table structure for table 'ezbasket'
--

DROP TABLE IF EXISTS ezbasket;
CREATE TABLE ezbasket (
  id int(11) NOT NULL auto_increment,
  session_id varchar(255) NOT NULL default '',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezbasket_session_id (session_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezbasket'
--

/*!40000 ALTER TABLE ezbasket DISABLE KEYS */;
LOCK TABLES ezbasket WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezbasket ENABLE KEYS */;

--
-- Table structure for table 'ezbinaryfile'
--

DROP TABLE IF EXISTS ezbinaryfile;
CREATE TABLE ezbinaryfile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezbinaryfile'
--

/*!40000 ALTER TABLE ezbinaryfile DISABLE KEYS */;
LOCK TABLES ezbinaryfile WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezbinaryfile ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_group'
--

DROP TABLE IF EXISTS ezcollab_group;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_group'
--

/*!40000 ALTER TABLE ezcollab_group DISABLE KEYS */;
LOCK TABLES ezcollab_group WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_group ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_item'
--

DROP TABLE IF EXISTS ezcollab_item;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_item'
--

/*!40000 ALTER TABLE ezcollab_item DISABLE KEYS */;
LOCK TABLES ezcollab_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_item_group_link'
--

DROP TABLE IF EXISTS ezcollab_item_group_link;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_item_group_link'
--

/*!40000 ALTER TABLE ezcollab_item_group_link DISABLE KEYS */;
LOCK TABLES ezcollab_item_group_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item_group_link ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_item_message_link'
--

DROP TABLE IF EXISTS ezcollab_item_message_link;
CREATE TABLE ezcollab_item_message_link (
  id int(11) NOT NULL auto_increment,
  collaboration_id int(11) NOT NULL default '0',
  participant_id int(11) NOT NULL default '0',
  message_id int(11) NOT NULL default '0',
  message_type int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_item_message_link'
--

/*!40000 ALTER TABLE ezcollab_item_message_link DISABLE KEYS */;
LOCK TABLES ezcollab_item_message_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item_message_link ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_item_participant_link'
--

DROP TABLE IF EXISTS ezcollab_item_participant_link;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_item_participant_link'
--

/*!40000 ALTER TABLE ezcollab_item_participant_link DISABLE KEYS */;
LOCK TABLES ezcollab_item_participant_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item_participant_link ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_item_status'
--

DROP TABLE IF EXISTS ezcollab_item_status;
CREATE TABLE ezcollab_item_status (
  collaboration_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  is_read int(11) NOT NULL default '0',
  is_active int(11) NOT NULL default '1',
  last_read int(11) NOT NULL default '0',
  PRIMARY KEY  (collaboration_id,user_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_item_status'
--

/*!40000 ALTER TABLE ezcollab_item_status DISABLE KEYS */;
LOCK TABLES ezcollab_item_status WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item_status ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_notification_rule'
--

DROP TABLE IF EXISTS ezcollab_notification_rule;
CREATE TABLE ezcollab_notification_rule (
  id int(11) NOT NULL auto_increment,
  user_id varchar(255) NOT NULL default '',
  collab_identifier varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_notification_rule'
--

/*!40000 ALTER TABLE ezcollab_notification_rule DISABLE KEYS */;
LOCK TABLES ezcollab_notification_rule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_notification_rule ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_profile'
--

DROP TABLE IF EXISTS ezcollab_profile;
CREATE TABLE ezcollab_profile (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  main_group int(11) NOT NULL default '0',
  data_text1 text NOT NULL,
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_profile'
--

/*!40000 ALTER TABLE ezcollab_profile DISABLE KEYS */;
LOCK TABLES ezcollab_profile WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_profile ENABLE KEYS */;

--
-- Table structure for table 'ezcollab_simple_message'
--

DROP TABLE IF EXISTS ezcollab_simple_message;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcollab_simple_message'
--

/*!40000 ALTER TABLE ezcollab_simple_message DISABLE KEYS */;
LOCK TABLES ezcollab_simple_message WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_simple_message ENABLE KEYS */;

--
-- Table structure for table 'ezcontent_translation'
--

DROP TABLE IF EXISTS ezcontent_translation;
CREATE TABLE ezcontent_translation (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  locale varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontent_translation'
--

/*!40000 ALTER TABLE ezcontent_translation DISABLE KEYS */;
LOCK TABLES ezcontent_translation WRITE;
INSERT INTO ezcontent_translation VALUES (1,'English (United Kingdom)','eng-GB');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontent_translation ENABLE KEYS */;

--
-- Table structure for table 'ezcontentbrowsebookmark'
--

DROP TABLE IF EXISTS ezcontentbrowsebookmark;
CREATE TABLE ezcontentbrowsebookmark (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezcontentbrowsebookmark_user (user_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentbrowsebookmark'
--

/*!40000 ALTER TABLE ezcontentbrowsebookmark DISABLE KEYS */;
LOCK TABLES ezcontentbrowsebookmark WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentbrowsebookmark ENABLE KEYS */;

--
-- Table structure for table 'ezcontentbrowserecent'
--

DROP TABLE IF EXISTS ezcontentbrowserecent;
CREATE TABLE ezcontentbrowserecent (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY ezcontentbrowserecent_user (user_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentbrowserecent'
--

/*!40000 ALTER TABLE ezcontentbrowserecent DISABLE KEYS */;
LOCK TABLES ezcontentbrowserecent WRITE;
INSERT INTO ezcontentbrowserecent VALUES (35,111,99,1067006746,'foo bar corp'),(65,149,135,1068126974,'lkj ssssstick'),(106,14,195,1069773629,'Fun'),(64,206,135,1068123651,'lkj ssssstick'),(108,10,194,1069773826,'Party!');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentbrowserecent ENABLE KEYS */;

--
-- Table structure for table 'ezcontentclass'
--

DROP TABLE IF EXISTS ezcontentclass;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentclass'
--

/*!40000 ALTER TABLE ezcontentclass DISABLE KEYS */;
LOCK TABLES ezcontentclass WRITE;
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1068804191),(2,0,'Article','article','<title>',14,14,1024392098,1066907423),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1068123024),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069677671),(12,1,'File','file','<name>',14,14,1052385472,1067353799),(25,0,'Poll','poll','<name>',14,14,1068716373,1068717758),(24,0,'Link','link','<name>',14,14,1068716182,1068716360),(23,0,'Log','log','<title>',14,14,1068710873,1068718967),(26,0,'Comment','comment','<name>',14,14,1068716787,1068716858),(14,1,'Setup link','setup_link','<title>',14,14,1066383719,1069334255),(25,1,'Poll','poll','<name>',14,14,1068716373,1069411484),(26,1,'Comment','comment','<name>',14,14,1068716787,1069411500);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentclass ENABLE KEYS */;

--
-- Table structure for table 'ezcontentclass_attribute'
--

DROP TABLE IF EXISTS ezcontentclass_attribute;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentclass_attribute'
--

/*!40000 ALTER TABLE ezcontentclass_attribute DISABLE KEYS */;
LOCK TABLES ezcontentclass_attribute WRITE;
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(214,0,1,'list_title','List title','ezstring',0,1,4,0,0,0,0,0,0,0,0,'Recent items','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(198,0,4,'location','Location','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(199,0,4,'signature','Signature','eztext',1,0,6,2,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(203,0,23,'log','Log','ezxmltext',1,0,2,15,0,0,0,0,0,0,0,'','','','','',0,1),(207,0,25,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(208,0,25,'option','Option','ezoption',0,1,2,0,0,0,0,0,0,0,0,'','','','','',1,1),(205,0,24,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1),(206,0,24,'url','URL','ezurl',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(204,0,24,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(202,0,23,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(212,0,26,'comment','Comment','eztext',1,0,4,10,0,0,0,0,0,0,0,'','','','','',0,1),(200,0,4,'user_image','User image','ezimage',0,0,7,1,0,0,0,0,0,0,0,'','','','','',0,1),(197,0,4,'title','Title','ezstring',1,0,4,25,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(213,0,23,'enable_comments','Enable comments','ezboolean',1,0,3,0,0,1,0,0,0,0,0,'','','','','',0,1),(211,0,26,'url','URL','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(210,0,26,'email','E-mail','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(209,0,26,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(215,0,1,'category_list_title','Category list title','ezstring',0,1,5,0,0,0,0,0,0,0,0,'All Categories','','','','',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1),(216,0,1,'archive_title','Archive Title','ezstring',0,1,3,0,0,0,0,0,0,0,0,'Item Archive','','','','',0,1),(119,0,1,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','','',0,1),(153,1,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,1,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(154,1,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(152,1,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(207,1,25,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(208,1,25,'option','Option','ezoption',0,1,2,0,0,0,0,0,0,0,0,'','','','','',1,1),(212,1,26,'comment','Comment','eztext',1,0,4,10,0,0,0,0,0,0,0,'','','','','',0,1),(209,1,26,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(210,1,26,'email','E-mail','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(211,1,26,'url','URL','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentclass_attribute ENABLE KEYS */;

--
-- Table structure for table 'ezcontentclass_classgroup'
--

DROP TABLE IF EXISTS ezcontentclass_classgroup;
CREATE TABLE ezcontentclass_classgroup (
  contentclass_id int(11) NOT NULL default '0',
  contentclass_version int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (contentclass_id,contentclass_version,group_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentclass_classgroup'
--

/*!40000 ALTER TABLE ezcontentclass_classgroup DISABLE KEYS */;
LOCK TABLES ezcontentclass_classgroup WRITE;
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(21,1,1,'Content'),(20,0,1,'Content'),(21,0,1,'Content'),(25,1,1,'Content'),(23,0,1,'Content'),(26,0,1,'Content'),(24,0,1,'Content'),(25,0,1,'Content'),(14,1,4,'Setup'),(26,1,1,'Content');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentclass_classgroup ENABLE KEYS */;

--
-- Table structure for table 'ezcontentclassgroup'
--

DROP TABLE IF EXISTS ezcontentclassgroup;
CREATE TABLE ezcontentclassgroup (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentclassgroup'
--

/*!40000 ALTER TABLE ezcontentclassgroup DISABLE KEYS */;
LOCK TABLES ezcontentclassgroup WRITE;
INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,14,1031216928,1033922106),(2,'Users',1,14,1031216941,1033922113),(3,'Media',8,14,1032009743,1033922120),(4,'Setup',14,14,1066383702,1066383712);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentclassgroup ENABLE KEYS */;

--
-- Table structure for table 'ezcontentobject'
--

DROP TABLE IF EXISTS ezcontentobject;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentobject'
--

/*!40000 ALTER TABLE ezcontentobject DISABLE KEYS */;
LOCK TABLES ezcontentobject WRITE;
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Blog',6,0,1033917596,1068710485,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',5,0,1033920830,1068468219,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',9,0,1066384365,1068729357,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',10,0,1066388816,1068729376,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,13,1,'Blogs',5,0,1066398020,1068804689,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'Blog',70,0,1066643397,1069839484,1,''),(214,14,13,23,'Today I got my new car!',2,0,1068711140,1069770036,1,''),(215,14,13,23,'Special things happened today',1,0,1068713677,1068713677,2,''),(212,14,13,1,'Personal',2,0,1068711069,1068717667,1,''),(161,14,1,10,'About me',3,0,1068047603,1069770484,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(87,14,5,16,'New Company',1,0,0,0,2,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(213,14,13,1,'Computers',2,0,1068711091,1068717696,1,''),(165,149,1,21,'New Forum topic',1,0,0,0,2,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(220,14,13,26,'båbåb',1,0,1068716967,1068716967,2,''),(115,14,11,14,'Cache',4,0,1066991725,1068729308,1,''),(116,14,11,14,'URL translator',3,0,1066992054,1068729395,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(218,14,1,25,'New Poll',1,0,0,0,0,''),(219,14,13,26,'Bård Farstad',1,0,1068716920,1068716920,2,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(216,14,1,25,'New Poll',1,0,0,0,0,''),(217,14,1,25,'New Poll',1,0,0,0,0,''),(168,149,0,21,'New Forum topic',1,0,0,0,2,''),(169,149,0,21,'New Forum topic',1,0,0,0,2,''),(171,149,1,21,'New Forum topic',1,0,0,0,2,''),(172,149,0,21,'New Forum topic',1,0,0,0,2,''),(173,149,0,21,'New Forum topic',1,0,0,0,2,''),(174,149,0,21,'New Forum topic',1,0,0,0,2,''),(175,149,0,21,'New Forum topic',1,0,0,0,2,''),(176,149,0,21,'New Forum topic',1,0,0,0,2,''),(177,149,0,21,'New Forum topic',1,0,0,0,2,''),(178,149,0,21,'New Forum topic',1,0,0,0,2,''),(179,149,0,21,'New Forum topic',1,0,0,0,2,''),(180,149,0,21,'New Forum topic',1,0,0,0,2,''),(181,149,0,21,'New Forum topic',1,0,0,0,2,''),(182,149,0,21,'New Forum topic',1,0,0,0,2,''),(183,149,0,21,'New Forum topic',1,0,0,0,2,''),(184,149,0,21,'New Forum topic',1,0,0,0,2,''),(185,149,0,21,'New Forum topic',1,0,0,0,2,''),(186,149,0,21,'New Forum topic',1,0,0,0,2,''),(187,14,1,4,'New User',1,0,0,0,0,''),(191,149,1,21,'New Forum topic',1,0,0,0,2,''),(189,14,1,4,'New User',1,0,0,0,0,''),(192,149,0,21,'New Forum topic',1,0,0,0,2,''),(193,149,0,21,'New Forum topic',1,0,0,0,2,''),(194,149,0,21,'New Forum topic',1,0,0,0,2,''),(200,149,1,21,'New Forum topic',1,0,0,0,2,''),(201,149,1,22,'New Forum reply',1,0,0,0,2,''),(221,14,1,25,'New Poll',1,0,0,0,0,''),(222,14,1,25,'New Poll',1,0,0,0,0,''),(224,14,1,25,'New Poll',1,0,0,0,0,''),(225,14,1,25,'New Poll',1,0,0,0,0,''),(226,14,13,23,'For Posterity\'s Sake',1,0,1068717935,1068717935,2,''),(227,14,1,25,'Which color do you like?',2,0,1068718128,1068719696,2,''),(228,14,12,1,'Links',4,0,1068718629,1068804675,1,''),(229,14,12,1,'Software',1,0,1068718672,1068718672,2,''),(230,14,12,1,'Movie',1,0,1068718712,1068718712,2,''),(231,14,12,1,'Downloads',2,0,1068718746,1069770638,1,''),(232,14,12,24,'VG',1,0,1068718861,1068718861,2,''),(233,14,13,26,'bård',1,0,1068718705,1068718705,2,''),(234,14,12,24,'Sina',1,0,1068718957,1068718957,2,''),(235,14,13,26,'kjh',1,0,1068718760,1068718760,2,''),(236,14,12,24,'Soft house',1,0,1068719251,1068719251,2,''),(237,14,13,23,'New big discovery today',1,0,1068719051,1068719051,2,''),(238,14,13,23,'No comments on this one',1,0,1068719129,1068719129,2,''),(239,14,13,26,'Bård',1,0,1068719374,1068719374,2,''),(240,14,1,1,'Polls',3,0,1068719643,1069172879,1,''),(241,14,1,25,'Which one is the best of matrix movies?',1,0,1068720802,1068720802,1,''),(242,14,13,26,'ghghj',1,0,1068720915,1068720915,2,''),(243,14,13,1,'Entertainment',1,0,1068727871,1068727871,1,''),(244,14,13,23,'A Pirate\'s Life',1,0,1068727918,1068727918,2,''),(245,14,13,26,'kjlh',1,0,1068730476,1068730476,2,''),(246,14,13,26,'kjhkjh',1,0,1068737197,1068737197,2,''),(247,14,13,23,'I overslept today',1,0,1068796296,1068796296,2,''),(248,10,13,26,'ete',1,0,1069409151,1069409151,2,''),(249,10,13,26,'New Comment',1,0,0,0,0,''),(250,10,0,26,'New Comment',1,0,0,0,0,''),(252,14,13,23,'I overslept again',1,0,1069770140,1069770140,1,''),(253,14,13,23,'Tonight I was at the movies',1,0,1069770254,1069770254,1,''),(254,14,13,23,'Finally I got it',1,0,1069770356,1069770356,1,''),(255,14,12,24,'eZ systems',1,0,1069770691,1069770691,1,''),(256,14,12,24,'eZ publish at Freshmeat',1,0,1069770809,1069770809,1,''),(257,14,12,1,'Movies',1,0,1069770849,1069770849,1,''),(258,14,12,24,'The Matrix',1,0,1069770910,1069770910,1,''),(259,14,12,24,'Lord of the Rings',1,0,1069770984,1069770984,1,''),(260,14,12,1,'Sports',1,0,1069771040,1069771040,1,''),(261,14,12,24,'Liverpool FC',1,0,1069771089,1069771089,1,''),(262,14,1,25,'Which do you like the best: Matrix or Lord of the Rings?',1,0,1069771243,1069771243,1,''),(263,14,13,23,'Party!',1,0,1069771496,1069771496,1,''),(264,14,12,1,'Fun',1,0,1069773539,1069773539,1,''),(265,14,12,24,'Pondus',1,0,1069773629,1069773629,1,''),(266,10,13,26,'Tim',1,0,1069773783,1069773783,1,''),(267,10,13,26,'Helene',1,0,1069773826,1069773826,1,'');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject ENABLE KEYS */;

--
-- Table structure for table 'ezcontentobject_attribute'
--

DROP TABLE IF EXISTS ezcontentobject_attribute;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentobject_attribute'
--

/*!40000 ALTER TABLE ezcontentobject_attribute DISABLE KEYS */;
LOCK TABLES ezcontentobject_attribute WRITE;
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Blog',0,0,0,0,'blog','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',1,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',1,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',1,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',1,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(810,'eng-GB',1,235,211,'kljh',0,0,0,0,'kljh','ezstring'),(811,'eng-GB',1,235,212,'< >\n\n:)\n\nhttp://ez.no',0,0,0,0,'','eztext'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(809,'eng-GB',1,235,210,'kjlh',0,0,0,0,'kjlh','ezstring'),(676,'eng-GB',1,200,190,'',0,0,0,0,'','ezboolean'),(677,'eng-GB',1,200,194,'',0,0,0,0,'','ezsubtreesubscription'),(678,'eng-GB',1,201,191,'Re:test',0,0,0,0,'re:test','ezstring'),(679,'eng-GB',1,201,193,'fdsf',0,0,0,0,'','eztext'),(817,'eng-GB',1,226,213,'',1,0,0,1,'','ezboolean'),(816,'eng-GB',1,215,213,'',1,0,0,1,'','ezboolean'),(815,'eng-GB',1,214,213,'',1,0,0,1,'','ezboolean'),(814,'eng-GB',1,236,206,'hzinfo',4,0,0,0,'','ezurl'),(813,'eng-GB',1,236,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Download software here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(812,'eng-GB',1,236,204,'Soft house',0,0,0,0,'soft house','ezstring'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(558,'eng-GB',1,171,189,'',0,0,0,0,'','eztext'),(553,'eng-GB',1,169,190,'',0,0,0,0,'','ezboolean'),(554,'eng-GB',1,169,194,'',0,0,0,0,'','ezsubtreesubscription'),(557,'eng-GB',1,171,188,'',0,0,0,0,'','ezstring'),(552,'eng-GB',1,169,189,'sfsvggs\nsfsf',0,0,0,0,'','eztext'),(547,'eng-GB',1,168,188,'',0,0,0,0,'','ezstring'),(548,'eng-GB',1,168,189,'',0,0,0,0,'','eztext'),(549,'eng-GB',1,168,190,'',0,0,0,0,'','ezboolean'),(550,'eng-GB',1,168,194,'',0,0,0,0,'','ezsubtreesubscription'),(551,'eng-GB',1,169,188,'test',0,0,0,0,'test','ezstring'),(767,'eng-GB',1,215,202,'Special things happened today',0,0,0,0,'special things happened today','ezstring'),(768,'eng-GB',1,215,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <link id=\"1\">sd oifgu sdoiguosdiu gfosdfg d</link>dfg sdfg sdfg sdfg sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n  <paragraph>sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n  <paragraph>\n    <link id=\"1\">sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg</link> sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(765,'eng-GB',1,214,202,'Today I got my new car!',0,0,0,0,'today i got my new car!','ezstring'),(766,'eng-GB',1,214,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf </paragraph>\n  <paragraph>\n    <strong>sdgfklj sdfklgj sdlkfg </strong>lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf </paragraph>\n  <paragraph>\n    <strong>sdgfklj sdfklgj sdlkfg lsdgf sdgf </strong>sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(762,'eng-GB',1,212,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(763,'eng-GB',1,213,4,'Computers',0,0,0,0,'computers','ezstring'),(764,'eng-GB',1,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(761,'eng-GB',1,212,4,'Personal',0,0,0,0,'personal','ezstring'),(671,'eng-GB',66,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(535,'eng-GB',1,165,188,'',0,0,0,0,'','ezstring'),(536,'eng-GB',1,165,189,'',0,0,0,0,'','eztext'),(537,'eng-GB',1,165,190,'',0,0,0,0,'','ezboolean'),(538,'eng-GB',1,165,194,'',0,0,0,0,'','ezsubtreesubscription'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(152,'eng-GB',61,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"60\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(639,'eng-GB',1,192,189,'',0,0,0,0,'','eztext'),(640,'eng-GB',1,192,190,'',0,0,0,0,'','ezboolean'),(634,'eng-GB',1,191,188,'',0,0,0,0,'','ezstring'),(635,'eng-GB',1,191,189,'',0,0,0,0,'','eztext'),(636,'eng-GB',1,191,190,'',0,0,0,0,'','ezboolean'),(637,'eng-GB',1,191,194,'',0,0,0,0,'','ezsubtreesubscription'),(638,'eng-GB',1,192,188,'',0,0,0,0,'','ezstring'),(609,'eng-GB',1,184,188,'',0,0,0,0,'','ezstring'),(610,'eng-GB',1,184,189,'',0,0,0,0,'','eztext'),(611,'eng-GB',1,184,190,'',0,0,0,0,'','ezboolean'),(612,'eng-GB',1,184,194,'',0,0,0,0,'','ezsubtreesubscription'),(613,'eng-GB',1,185,188,'',0,0,0,0,'','ezstring'),(614,'eng-GB',1,185,189,'',0,0,0,0,'','eztext'),(615,'eng-GB',1,185,190,'',0,0,0,0,'','ezboolean'),(616,'eng-GB',1,185,194,'',0,0,0,0,'','ezsubtreesubscription'),(617,'eng-GB',1,186,188,'',0,0,0,0,'','ezstring'),(618,'eng-GB',1,186,189,'',0,0,0,0,'','eztext'),(619,'eng-GB',1,186,190,'',0,0,0,0,'','ezboolean'),(620,'eng-GB',1,186,194,'',0,0,0,0,'','ezsubtreesubscription'),(603,'eng-GB',1,182,190,'',0,0,0,0,'','ezboolean'),(604,'eng-GB',1,182,194,'',0,0,0,0,'','ezsubtreesubscription'),(605,'eng-GB',1,183,188,'',0,0,0,0,'','ezstring'),(606,'eng-GB',1,183,189,'',0,0,0,0,'','eztext'),(607,'eng-GB',1,183,190,'',0,0,0,0,'','ezboolean'),(608,'eng-GB',1,183,194,'',0,0,0,0,'','ezsubtreesubscription'),(602,'eng-GB',1,182,189,'',0,0,0,0,'','eztext'),(730,'eng-GB',2,14,200,'',0,0,0,0,'','ezimage'),(731,'eng-GB',3,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"administrator_user.\"\n         suffix=\"\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB/administrator_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(923,'eng-GB',2,231,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(729,'eng-GB',1,14,200,'',0,0,0,0,'','ezimage'),(728,'eng-GB',1,10,200,'',0,0,0,0,'','ezimage'),(675,'eng-GB',1,200,189,'sefsefsf\nsf\nsf',0,0,0,0,'','eztext'),(150,'eng-GB',69,56,157,'Blog',0,0,0,0,'','ezinisetting'),(983,'eng-GB',1,253,202,'Tonight I was at the movies',0,0,0,0,'tonight i was at the movies','ezstring'),(984,'eng-GB',1,253,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>My first date with Mia! We went to see the romantic Matrix:-) </paragraph>\n  <paragraph>It must have been a success since she let me follow here home. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(985,'eng-GB',1,253,213,'',0,0,0,0,'','ezboolean'),(601,'eng-GB',1,182,188,'',0,0,0,0,'','ezstring'),(716,'eng-GB',1,10,199,'',0,0,0,0,'','eztext'),(717,'eng-GB',1,14,199,'',0,0,0,0,'','eztext'),(718,'eng-GB',2,14,199,'',0,0,0,0,'','eztext'),(719,'eng-GB',3,14,199,'developer... ;)',0,0,0,0,'','eztext'),(692,'eng-GB',1,10,197,'',0,0,0,0,'','ezstring'),(693,'eng-GB',1,14,197,'',0,0,0,0,'','ezstring'),(694,'eng-GB',2,14,197,'',0,0,0,0,'','ezstring'),(695,'eng-GB',3,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(704,'eng-GB',1,10,198,'',0,0,0,0,'','ezstring'),(705,'eng-GB',1,14,198,'',0,0,0,0,'','ezstring'),(706,'eng-GB',2,14,198,'',0,0,0,0,'','ezstring'),(707,'eng-GB',3,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(820,'eng-GB',1,237,213,'',1,0,0,1,'','ezboolean'),(819,'eng-GB',1,237,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>I discovered the Internet, it&apos;s big - about 20meters. dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </line>\n    <line>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </line>\n  </paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg v</paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(818,'eng-GB',1,237,202,'New big discovery today',0,0,0,0,'new big discovery today','ezstring'),(808,'eng-GB',1,235,209,'kjh',0,0,0,0,'kjh','ezstring'),(1,'eng-GB',2,1,4,'Corporate',0,0,0,0,'corporate','ezstring'),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(986,'eng-GB',1,254,202,'Finally I got it',0,0,0,0,'finally i got it','ezstring'),(987,'eng-GB',1,254,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>After such a long time with pulling my hair I finally got the latest edition of my software working. Perhaps the way to fortune in not that long anymore?</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(154,'eng-GB',67,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',67,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',67,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(980,'eng-GB',1,252,202,'I overslept again',0,0,0,0,'i overslept again','ezstring'),(981,'eng-GB',1,252,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Somehow I must have turned of the alarm in my sleep. I woke up three hours to late and missed a meeting with what will hopefully be my girlfriend. She was not very happy about this.  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(982,'eng-GB',1,252,213,'',0,0,0,0,'','ezboolean'),(815,'eng-GB',2,214,213,'',1,0,0,1,'','ezboolean'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(772,'eng-GB',1,219,209,'Bård Farstad',0,0,0,0,'bård farstad','ezstring'),(773,'eng-GB',1,219,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(774,'eng-GB',1,219,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring'),(775,'eng-GB',1,219,212,'I\'ve seen more speacial things.. dsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gf\n\ndsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gf',0,0,0,0,'','eztext'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',61,56,157,'Blog',0,0,0,0,'','ezinisetting'),(151,'eng-GB',62,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',67,56,157,'Blog',0,0,0,0,'','ezinisetting'),(151,'eng-GB',67,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',67,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"66\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069686266\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(125,'eng-GB',2,49,4,'Blogs',0,0,0,0,'blogs','ezstring'),(126,'eng-GB',2,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(992,'eng-GB',1,256,204,'eZ publish at Freshmeat',0,0,0,0,'ez publish at freshmeat','ezstring'),(993,'eng-GB',1,256,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Visit eZ publish at Freshmeat</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',62,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',62,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',62,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',62,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(600,'eng-GB',1,181,194,'',0,0,0,0,'','ezsubtreesubscription'),(591,'eng-GB',1,179,190,'',0,0,0,0,'','ezboolean'),(592,'eng-GB',1,179,194,'',0,0,0,0,'','ezsubtreesubscription'),(593,'eng-GB',1,180,188,'',0,0,0,0,'','ezstring'),(594,'eng-GB',1,180,189,'',0,0,0,0,'','eztext'),(595,'eng-GB',1,180,190,'',0,0,0,0,'','ezboolean'),(596,'eng-GB',1,180,194,'',0,0,0,0,'','ezsubtreesubscription'),(597,'eng-GB',1,181,188,'',0,0,0,0,'','ezstring'),(598,'eng-GB',1,181,189,'',0,0,0,0,'','eztext'),(599,'eng-GB',1,181,190,'',0,0,0,0,'','ezboolean'),(573,'eng-GB',1,175,188,'',0,0,0,0,'','ezstring'),(574,'eng-GB',1,175,189,'',0,0,0,0,'','eztext'),(575,'eng-GB',1,175,190,'',0,0,0,0,'','ezboolean'),(576,'eng-GB',1,175,194,'',0,0,0,0,'','ezsubtreesubscription'),(577,'eng-GB',1,176,188,'',0,0,0,0,'','ezstring'),(578,'eng-GB',1,176,189,'',0,0,0,0,'','eztext'),(579,'eng-GB',1,176,190,'',0,0,0,0,'','ezboolean'),(580,'eng-GB',1,176,194,'',0,0,0,0,'','ezsubtreesubscription'),(581,'eng-GB',1,177,188,'',0,0,0,0,'','ezstring'),(582,'eng-GB',1,177,189,'',0,0,0,0,'','eztext'),(583,'eng-GB',1,177,190,'',0,0,0,0,'','ezboolean'),(584,'eng-GB',1,177,194,'',0,0,0,0,'','ezsubtreesubscription'),(585,'eng-GB',1,178,188,'',0,0,0,0,'','ezstring'),(586,'eng-GB',1,178,189,'',0,0,0,0,'','eztext'),(587,'eng-GB',1,178,190,'',0,0,0,0,'','ezboolean'),(588,'eng-GB',1,178,194,'',0,0,0,0,'','ezsubtreesubscription'),(589,'eng-GB',1,179,188,'',0,0,0,0,'','ezstring'),(590,'eng-GB',1,179,189,'',0,0,0,0,'','eztext'),(561,'eng-GB',1,172,188,'',0,0,0,0,'','ezstring'),(562,'eng-GB',1,172,189,'',0,0,0,0,'','eztext'),(563,'eng-GB',1,172,190,'',0,0,0,0,'','ezboolean'),(564,'eng-GB',1,172,194,'',0,0,0,0,'','ezsubtreesubscription'),(565,'eng-GB',1,173,188,'',0,0,0,0,'','ezstring'),(566,'eng-GB',1,173,189,'',0,0,0,0,'','eztext'),(567,'eng-GB',1,173,190,'',0,0,0,0,'','ezboolean'),(568,'eng-GB',1,173,194,'',0,0,0,0,'','ezsubtreesubscription'),(569,'eng-GB',1,174,188,'',0,0,0,0,'','ezstring'),(570,'eng-GB',1,174,189,'',0,0,0,0,'','eztext'),(571,'eng-GB',1,174,190,'',0,0,0,0,'','ezboolean'),(572,'eng-GB',1,174,194,'',0,0,0,0,'','ezsubtreesubscription'),(560,'eng-GB',1,171,194,'',0,0,0,0,'','ezsubtreesubscription'),(559,'eng-GB',1,171,190,'',0,0,0,0,'','ezboolean'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(153,'eng-GB',67,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(150,'eng-GB',68,56,157,'Blog',0,0,0,0,'','ezinisetting'),(150,'eng-GB',63,56,157,'Blog test',0,0,0,0,'','ezinisetting'),(151,'eng-GB',63,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(151,'eng-GB',69,56,158,'author=eZ systems package team\ncopyright=Copyright &copy; 1999-2003 eZ systems as\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',63,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"62\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',66,56,157,'Blog',0,0,0,0,'','ezinisetting'),(153,'eng-GB',63,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',63,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',63,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',63,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(995,'eng-GB',1,257,4,'Movies',0,0,0,0,'movies','ezstring'),(996,'eng-GB',1,257,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(997,'eng-GB',1,257,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(998,'eng-GB',1,257,214,'Recent items',0,0,0,0,'recent items','ezstring'),(999,'eng-GB',1,257,215,'All Categories',0,0,0,0,'all categories','ezstring'),(1000,'eng-GB',1,258,204,'The Matrix',0,0,0,0,'the matrix','ezstring'),(1001,'eng-GB',1,258,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Best movies ever. This is the homepage for the movie</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1002,'eng-GB',1,258,206,'',7,0,0,0,'','ezurl'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(824,'eng-GB',1,239,209,'Bård',0,0,0,0,'bård','ezstring'),(823,'eng-GB',1,238,213,'',0,0,0,0,'','ezboolean'),(822,'eng-GB',1,238,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(821,'eng-GB',1,238,202,'No comments on this one',0,0,0,0,'no comments on this one','ezstring'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(671,'eng-GB',69,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(151,'eng-GB',66,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(151,'eng-GB',68,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(437,'eng-GB',61,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(797,'eng-GB',2,231,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links for news site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(674,'eng-GB',1,200,188,'test',0,0,0,0,'test','ezstring'),(641,'eng-GB',1,192,194,'',0,0,0,0,'','ezsubtreesubscription'),(642,'eng-GB',1,193,188,'',0,0,0,0,'','ezstring'),(643,'eng-GB',1,193,189,'',0,0,0,0,'','eztext'),(644,'eng-GB',1,193,190,'',0,0,0,0,'','ezboolean'),(645,'eng-GB',1,193,194,'',0,0,0,0,'','ezsubtreesubscription'),(646,'eng-GB',1,194,188,'',0,0,0,0,'','ezstring'),(647,'eng-GB',1,194,189,'',0,0,0,0,'','eztext'),(648,'eng-GB',1,194,190,'',0,0,0,0,'','ezboolean'),(649,'eng-GB',1,194,194,'',0,0,0,0,'','ezsubtreesubscription'),(152,'eng-GB',70,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog.gif\"\n         original_filename=\"weblog.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069838835\">\n  <original attribute_id=\"152\"\n            attribute_version=\"69\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069838837\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069838837\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069843716\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(154,'eng-GB',61,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(153,'eng-GB',70,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',70,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(524,'eng-GB',3,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_me.\"\n         suffix=\"\"\n         basename=\"about_me\"\n         dirpath=\"var/blog/storage/images/about_me/524-3-eng-GB\"\n         url=\"var/blog/storage/images/about_me/524-3-eng-GB/about_me.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(766,'eng-GB',2,214,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>It is an old Volkswagen Beetle from 1982. It has a lot more charm that it cost me money. </paragraph>\n  <paragraph>I bought it from a friend for £30 and even got the old original wheels. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',68,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"67\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(765,'eng-GB',2,214,202,'Today I got my new car!',0,0,0,0,'today i got my new car!','ezstring'),(153,'eng-GB',68,56,160,'blog_red',0,0,0,0,'blog_red','ezpackage'),(154,'eng-GB',68,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',68,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(151,'eng-GB',61,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(153,'eng-GB',66,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',66,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',66,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(152,'eng-GB',66,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"65\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069678621\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(850,'eng-GB',1,247,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sdgj sdlgjsdkfjlgh sdfg sdfg</line>\n    <line>sdfg</line>\n    <line>sdg</line>\n    <line>sdf</line>\n    <line>gds</line>\n  </paragraph>\n  <paragraph>\n    <line>sdfgsdfgsd</line>\n    <line>fg</line>\n    <line>sdfg</line>\n    <line>sdf</line>\n    <line>gsd</line>\n    <line>fg</line>\n    <line>sdg</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(851,'eng-GB',1,247,213,'',1,0,0,1,'','ezboolean'),(153,'eng-GB',65,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',65,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',65,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',65,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(437,'eng-GB',69,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',69,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog.gif\"\n         original_filename=\"weblog.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069838835\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069838837\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069838837\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069838862\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(1,'eng-GB',3,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',3,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(671,'eng-GB',68,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(825,'eng-GB',1,239,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(802,'eng-GB',1,233,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(803,'eng-GB',1,233,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring'),(804,'eng-GB',1,233,212,'dfgl sdflg sdiofg usdoigfu osdigu iosdgf sdgfsd\nfg\nsdfg\nsdfg\nsdg',0,0,0,0,'','eztext'),(150,'eng-GB',65,56,157,'Blog test',0,0,0,0,'','ezinisetting'),(151,'eng-GB',65,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',62,56,157,'Blog',0,0,0,0,'','ezinisetting'),(152,'eng-GB',65,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"64\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_test_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069677595\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(437,'eng-GB',70,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(871,'eng-GB',2,231,214,'Recent items',0,0,0,0,'recent items','ezstring'),(896,'eng-GB',2,231,215,'All Categories',0,0,0,0,'all categories','ezstring'),(989,'eng-GB',1,255,204,'eZ systems',0,0,0,0,'ez systems','ezstring'),(990,'eng-GB',1,255,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Creators of eZ publish content management system</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(991,'eng-GB',1,255,206,'',1,0,0,0,'','ezurl'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',4,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',4,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',4,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',4,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(807,'eng-GB',1,234,206,'sina',3,0,0,0,'','ezurl'),(806,'eng-GB',1,234,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A famous chinese news site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(805,'eng-GB',1,234,204,'Sina ',0,0,0,0,'sina','ezstring'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',5,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',5,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',5,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',5,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"731\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"administrator_user_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(790,'eng-GB',1,228,4,'Links',0,0,0,0,'links','ezstring'),(791,'eng-GB',1,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(792,'eng-GB',1,229,4,'Software',0,0,0,0,'software','ezstring'),(793,'eng-GB',1,229,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links about software..</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(794,'eng-GB',1,230,4,'Movie',0,0,0,0,'movie','ezstring'),(795,'eng-GB',1,230,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links to movie sites.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(796,'eng-GB',1,231,4,'News',0,0,0,0,'news','ezstring'),(797,'eng-GB',1,231,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links for news site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(798,'eng-GB',1,232,204,'VG',0,0,0,0,'vg','ezstring'),(799,'eng-GB',1,232,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Know norwegian news from here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(800,'eng-GB',1,232,206,'VG',2,0,0,0,'','ezurl'),(801,'eng-GB',1,233,209,'bård',0,0,0,0,'bård','ezstring'),(788,'eng-GB',1,227,207,'Which color do you like?',0,0,0,0,'which color do you like?','ezstring'),(789,'eng-GB',1,227,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name>Colors</name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Blue</option>\n    <option id=\"1\"\n            additional_price=\"\">Yellow</option>\n    <option id=\"2\"\n            additional_price=\"\">Red</option>\n    <option id=\"3\"\n            additional_price=\"\">Orange</option>\n    <option id=\"4\"\n            additional_price=\"\">Green</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(838,'eng-GB',1,244,202,'A Pirate\'s Life',0,0,0,0,'a pirate\'s life','ezstring'),(839,'eng-GB',1,244,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Pirates of the Caribbean wasn&apos;t on my list of movies to see until I read what a few others were saying about it. Now I can&apos;t recommend it enough. This movie is an excellent 2.5 hours of pure escapism. Great sets, intriguing cinematography, fun characters, and a downright entertaining story. A good blend of action, humor, and a small dose of Disney-esque romance. As [Capt&apos;n!] Jack Sparrow, Johnny Depp gives us another brilliant performance, holding the audience with his charm and swagger throughout the film&apos;s entirety. He continues to impress me with his wide range of ability and the consistently strong characters of his recent career. And need I say what nice visuals Keira Knightley adds to this film?</paragraph>\n  <paragraph>Without giving anything away, my favorite parts of this movie were the over-obvious tie-ins with Disney&apos;s original ride. Anyone who remembers even a portion of the scenes which float by are bound to have a few chuckles over the way they&apos;re cleverly integrated into the movie. The pirates&apos; movements in and out of moonlight make for nicely added effects which I don&apos;t remember the ride revealing. (Note: The film is rated PG-13 for a reason: the pirates&apos; true identity and their violence may give small children nightmares, so think twice if ye have little ones.)</paragraph>\n  <paragraph>Aside from a few lengthy sword fights, I can&apos;t think of any reason not to say: you should run, not walk, to your nearest theater and see this movie as soon as possible. If you&apos;re not rambling out loud when you walk out of the theater, you&apos;ll at least be mumbling to yourself like a pirate, and hopefully you&apos;ll have had as good a time as I did.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(840,'eng-GB',1,244,213,'',1,0,0,1,'','ezboolean'),(834,'eng-GB',1,242,211,'fghvmbnmbvnm',0,0,0,0,'fghvmbnmbvnm','ezstring'),(835,'eng-GB',1,242,212,'fgn fdgh fdgh fdgh\nkløæ\nølæ\nløæ\nløæ\nløæ\nhjlh\nhj',0,0,0,0,'','eztext'),(836,'eng-GB',1,243,4,'Entertainment',0,0,0,0,'entertainment','ezstring'),(837,'eng-GB',1,243,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Music, books, film, television, shopping, travel, and many other fine escapes and vices. If it&apos;s downright fun, it probably belongs here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(826,'eng-GB',1,239,211,'sdfgsd',0,0,0,0,'sdfgsd','ezstring'),(827,'eng-GB',1,239,212,'sdfgsdgsd\nsdg\nsdf',0,0,0,0,'','eztext'),(828,'eng-GB',1,240,4,'Polls',0,0,0,0,'polls','ezstring'),(829,'eng-GB',1,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(788,'eng-GB',2,227,207,'Which color do you like?',0,0,0,0,'which color do you like?','ezstring'),(789,'eng-GB',2,227,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Blue</option>\n    <option id=\"1\"\n            additional_price=\"\">Yellow</option>\n    <option id=\"2\"\n            additional_price=\"\">Red</option>\n    <option id=\"3\"\n            additional_price=\"\">Orange</option>\n    <option id=\"4\"\n            additional_price=\"\">Green</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(828,'eng-GB',2,240,4,'Polls',0,0,0,0,'polls','ezstring'),(829,'eng-GB',2,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(830,'eng-GB',1,241,207,'Which one is the best of matrix movies?',0,0,0,0,'which one is the best of matrix movies?','ezstring'),(831,'eng-GB',1,241,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Matrix</option>\n    <option id=\"1\"\n            additional_price=\"\">Matrix reloaded</option>\n    <option id=\"2\"\n            additional_price=\"\">Matrix revoluaton</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(832,'eng-GB',1,242,209,'ghghj',0,0,0,0,'ghghj','ezstring'),(833,'eng-GB',1,242,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(776,'eng-GB',1,220,209,'båbåb',0,0,0,0,'båbåb','ezstring'),(777,'eng-GB',1,220,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(778,'eng-GB',1,220,211,'http://piranha.no',0,0,0,0,'http://piranha.no','ezstring'),(779,'eng-GB',1,220,212,'sdfgsd fgsdgsd\ngf\nsdfg\nsdfgdsg\nsdgf\n',0,0,0,0,'','eztext'),(786,'eng-GB',1,226,202,'For Posterity\'s Sake',0,0,0,0,'for posterity\'s sake','ezstring'),(787,'eng-GB',1,226,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Seven years ago, yesterday, I packed up everything I owned, left many friends behind in San Diego, and moved to San Francisco, (where I knew absolutely no one) to start my job at HotWired on August 12, 1996. The Creative Director hired me as a junior designer, since I knew very little about design for the Web, despite the fact that I had more print design experience than almost every other designer there at the time.</paragraph>\n  <paragraph>Three months after starting the low-paying job, I was depressed, out of money, missed my friends in San Diego, and was convinced I had made the wrong move. I gave notice, told my landlord I was moving out, and arranged to go back to my old job at a design agency in San Diego. Two days before I was to leave HotWired, Jonathan Louie (Design Director at the time) convinced me otherwise, offering me a new position and my first promotion of many more to come. I stayed, and lost a girlfriend in San Diego as a result.</paragraph>\n  <paragraph>Fast-forward to the present?</paragraph>\n  <paragraph>In two days, it will have been 9 months since I left Wired to go out on my own. Friends who know the journey and my initial intimidation of taking the job at HotWired say to me, &quot;?and look where you are now.&quot;</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(761,'eng-GB',2,212,4,'Personal',0,0,0,0,'personal','ezstring'),(762,'eng-GB',2,212,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A glimpse into the life of me.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(763,'eng-GB',2,213,4,'Computers',0,0,0,0,'computers','ezstring'),(764,'eng-GB',2,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Computers, handhelds, electronic gadgets, and the software which connects them all. I&apos;m an early adopter, which means I often end up paying the price, in more than one way.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(522,'eng-GB',2,161,140,'About me',0,0,0,0,'about me','ezstring'),(523,'eng-GB',2,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',2,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_me.\"\n         suffix=\"\"\n         basename=\"about_me\"\n         dirpath=\"var/blog/storage/images/about_me/524-2-eng-GB\"\n         url=\"var/blog/storage/images/about_me/524-2-eng-GB/about_me.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(988,'eng-GB',1,254,213,'',1,0,0,1,'','ezboolean'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429664\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429664\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(841,'eng-GB',1,245,209,'kjlh',0,0,0,0,'kjlh','ezstring'),(842,'eng-GB',1,245,210,'kjh',0,0,0,0,'kjh','ezstring'),(843,'eng-GB',1,245,211,'kjh',0,0,0,0,'kjh','ezstring'),(844,'eng-GB',1,245,212,'kjlhkhkjhklhj',0,0,0,0,'','eztext'),(845,'eng-GB',1,246,209,'kjhkjh',0,0,0,0,'kjhkjh','ezstring'),(846,'eng-GB',1,246,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(847,'eng-GB',1,246,211,'sdfgsdfg',0,0,0,0,'sdfgsdfg','ezstring'),(848,'eng-GB',1,246,212,'sdfgsdfgsdfgds\nfgsd\nfg\nsdfg',0,0,0,0,'','eztext'),(849,'eng-GB',1,247,202,'I overslept today',0,0,0,0,'i overslept today','ezstring'),(153,'eng-GB',69,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',69,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(852,'eng-GB',2,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(853,'eng-GB',3,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(854,'eng-GB',4,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(855,'eng-GB',5,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(856,'eng-GB',6,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(857,'eng-GB',1,41,214,'Recent items',0,0,0,0,'recent items','ezstring'),(858,'eng-GB',1,42,214,'Recent items',0,0,0,0,'recent items','ezstring'),(859,'eng-GB',1,44,214,'Recent items',0,0,0,0,'recent items','ezstring'),(860,'eng-GB',1,46,214,'Recent items',0,0,0,0,'recent items','ezstring'),(861,'eng-GB',2,46,214,'Recent items',0,0,0,0,'recent items','ezstring'),(862,'eng-GB',1,49,214,'Recent items',0,0,0,0,'recent items','ezstring'),(863,'eng-GB',2,49,214,'Recent items',0,0,0,0,'recent items','ezstring'),(864,'eng-GB',1,212,214,'Recent items',0,0,0,0,'recent items','ezstring'),(865,'eng-GB',2,212,214,'Recent items',0,0,0,0,'recent items','ezstring'),(866,'eng-GB',1,213,214,'Recent items',0,0,0,0,'recent items','ezstring'),(867,'eng-GB',2,213,214,'Recent items',0,0,0,0,'recent items','ezstring'),(868,'eng-GB',1,228,214,'Recent items',0,0,0,0,'recent items','ezstring'),(869,'eng-GB',1,229,214,'Recent items',0,0,0,0,'recent items','ezstring'),(870,'eng-GB',1,230,214,'Recent items',0,0,0,0,'recent items','ezstring'),(871,'eng-GB',1,231,214,'Recent items',0,0,0,0,'recent items','ezstring'),(872,'eng-GB',1,240,214,'Recent items',0,0,0,0,'recent items','ezstring'),(873,'eng-GB',2,240,214,'Recent items',0,0,0,0,'recent items','ezstring'),(874,'eng-GB',1,243,214,'Recent items',0,0,0,0,'recent items','ezstring'),(790,'eng-GB',2,228,4,'Links',0,0,0,0,'links','ezstring'),(791,'eng-GB',2,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Worthwhile websites, weblogs, journals, news pubs, and the like</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(868,'eng-GB',2,228,214,'Recent Links',0,0,0,0,'recent links','ezstring'),(125,'eng-GB',3,49,4,'Blogs',0,0,0,0,'blogs','ezstring'),(126,'eng-GB',3,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Parenthetical thoughts, concepts, and brainstorms</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(863,'eng-GB',3,49,214,'Excerpts from Recent Entries',0,0,0,0,'excerpts from recent entries','ezstring'),(875,'eng-GB',2,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(876,'eng-GB',3,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(877,'eng-GB',4,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(878,'eng-GB',5,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(879,'eng-GB',6,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(880,'eng-GB',1,41,215,'All Categories',0,0,0,0,'all categories','ezstring'),(881,'eng-GB',1,42,215,'All Categories',0,0,0,0,'all categories','ezstring'),(882,'eng-GB',1,44,215,'All Categories',0,0,0,0,'all categories','ezstring'),(883,'eng-GB',1,46,215,'All Categories',0,0,0,0,'all categories','ezstring'),(884,'eng-GB',2,46,215,'All Categories',0,0,0,0,'all categories','ezstring'),(885,'eng-GB',1,49,215,'All Categories',0,0,0,0,'all categories','ezstring'),(886,'eng-GB',2,49,215,'All Categories',0,0,0,0,'all categories','ezstring'),(887,'eng-GB',3,49,215,'All Categories',0,0,0,0,'all categories','ezstring'),(888,'eng-GB',1,212,215,'All Categories',0,0,0,0,'all categories','ezstring'),(889,'eng-GB',2,212,215,'All Categories',0,0,0,0,'all categories','ezstring'),(890,'eng-GB',1,213,215,'All Categories',0,0,0,0,'all categories','ezstring'),(891,'eng-GB',2,213,215,'All Categories',0,0,0,0,'all categories','ezstring'),(892,'eng-GB',1,228,215,'All Categories',0,0,0,0,'all categories','ezstring'),(893,'eng-GB',2,228,215,'All Categories',0,0,0,0,'all categories','ezstring'),(894,'eng-GB',1,229,215,'All Categories',0,0,0,0,'all categories','ezstring'),(895,'eng-GB',1,230,215,'All Categories',0,0,0,0,'all categories','ezstring'),(896,'eng-GB',1,231,215,'All Categories',0,0,0,0,'all categories','ezstring'),(897,'eng-GB',1,240,215,'All Categories',0,0,0,0,'all categories','ezstring'),(898,'eng-GB',2,240,215,'All Categories',0,0,0,0,'all categories','ezstring'),(899,'eng-GB',1,243,215,'All Categories',0,0,0,0,'all categories','ezstring'),(790,'eng-GB',3,228,4,'Links',0,0,0,0,'links','ezstring'),(791,'eng-GB',3,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Worthwhile websites, weblogs, journals, news pubs, and the like</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(868,'eng-GB',3,228,214,'Recent Links',0,0,0,0,'recent links','ezstring'),(893,'eng-GB',3,228,215,'All Categories',0,0,0,0,'all categories','ezstring'),(125,'eng-GB',4,49,4,'Blogs',0,0,0,0,'blogs','ezstring'),(126,'eng-GB',4,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Parenthetical thoughts, concepts, and brainstorms</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(863,'eng-GB',4,49,214,'Excerpts from Recent Entries',0,0,0,0,'excerpts from recent entries','ezstring'),(887,'eng-GB',4,49,215,'Entry Categories',0,0,0,0,'entry categories','ezstring'),(900,'eng-GB',2,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(901,'eng-GB',3,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(902,'eng-GB',4,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(903,'eng-GB',5,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(904,'eng-GB',6,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(905,'eng-GB',1,41,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(906,'eng-GB',1,42,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(907,'eng-GB',1,44,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(908,'eng-GB',1,46,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(909,'eng-GB',2,46,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(910,'eng-GB',1,49,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(911,'eng-GB',2,49,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(912,'eng-GB',3,49,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(913,'eng-GB',4,49,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(914,'eng-GB',1,212,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(915,'eng-GB',2,212,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(916,'eng-GB',1,213,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(917,'eng-GB',2,213,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(918,'eng-GB',1,228,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(919,'eng-GB',2,228,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(920,'eng-GB',3,228,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(921,'eng-GB',1,229,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(922,'eng-GB',1,230,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(923,'eng-GB',1,231,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(924,'eng-GB',1,240,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(925,'eng-GB',2,240,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(926,'eng-GB',1,243,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(790,'eng-GB',4,228,4,'Links',0,0,0,0,'links','ezstring'),(791,'eng-GB',4,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Worthwhile websites, weblogs, journals, news pubs, and the like</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(920,'eng-GB',4,228,216,'Link Archive',0,0,0,0,'link archive','ezstring'),(868,'eng-GB',4,228,214,'Recent Links',0,0,0,0,'recent links','ezstring'),(893,'eng-GB',4,228,215,'All Categories',0,0,0,0,'all categories','ezstring'),(125,'eng-GB',5,49,4,'Blogs',0,0,0,0,'blogs','ezstring'),(126,'eng-GB',5,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Parenthetical thoughts, concepts, and brainstorms</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(913,'eng-GB',5,49,216,'Log Archive',0,0,0,0,'log archive','ezstring'),(863,'eng-GB',5,49,214,'Excerpts from Recent Entries',0,0,0,0,'excerpts from recent entries','ezstring'),(887,'eng-GB',5,49,215,'Entry Categories',0,0,0,0,'entry categories','ezstring'),(153,'eng-GB',61,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(828,'eng-GB',3,240,4,'Polls',0,0,0,0,'polls','ezstring'),(829,'eng-GB',3,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(925,'eng-GB',3,240,216,'Poll Archive',0,0,0,0,'poll archive','ezstring'),(873,'eng-GB',3,240,214,'Recent polls',0,0,0,0,'recent polls','ezstring'),(898,'eng-GB',3,240,215,'All Categories',0,0,0,0,'all categories','ezstring'),(152,'eng-GB',62,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"61\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069429748\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(796,'eng-GB',2,231,4,'Downloads',0,0,0,0,'downloads','ezstring'),(151,'eng-GB',70,56,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',70,56,157,'Blog',0,0,0,0,'','ezinisetting'),(956,'eng-GB',1,248,209,'ete',0,0,0,0,'ete','ezstring'),(957,'eng-GB',1,248,210,'ete',0,0,0,0,'ete','ezstring'),(958,'eng-GB',1,248,211,'ete',0,0,0,0,'ete','ezstring'),(959,'eng-GB',1,248,212,'te',0,0,0,0,'','eztext'),(960,'eng-GB',1,249,209,'',0,0,0,0,'','ezstring'),(961,'eng-GB',1,249,210,'',0,0,0,0,'','ezstring'),(962,'eng-GB',1,249,211,'',0,0,0,0,'','ezstring'),(963,'eng-GB',1,249,212,'',0,0,0,0,'','eztext'),(964,'eng-GB',1,250,209,'what is the problem?',0,0,0,0,'what is the problem?','ezstring'),(965,'eng-GB',1,250,210,'ade@yahoo.com',0,0,0,0,'ade@yahoo.com','ezstring'),(966,'eng-GB',1,250,211,'http://mysite.com',0,0,0,0,'http://mysite.com','ezstring'),(967,'eng-GB',1,250,212,'sdfdsseff\nsef',0,0,0,0,'','eztext'),(150,'eng-GB',64,56,157,'Blog test',0,0,0,0,'','ezinisetting'),(151,'eng-GB',64,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',64,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"63\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',64,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',64,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',64,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',64,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(522,'eng-GB',3,161,140,'About me',0,0,0,0,'about me','ezstring'),(523,'eng-GB',3,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Anyway: this is me:</paragraph>\n  <paragraph>\n    <line>To short</line>\n    <line>To young</line>\n    <line>To smart</line>\n    <line>Not smart enough</line>\n    <line>Pimples</line>\n    <line>Boy</line>\n    <line>Totally girl addicted</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(671,'eng-GB',61,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(994,'eng-GB',1,256,206,'',6,0,0,0,'','ezurl'),(1003,'eng-GB',1,259,204,'Lord of the Rings',0,0,0,0,'lord of the rings','ezstring'),(1004,'eng-GB',1,259,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Can&apos;t wait for number three even if I know what will happen</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1005,'eng-GB',1,259,206,'',8,0,0,0,'','ezurl'),(1006,'eng-GB',1,260,4,'Sports',0,0,0,0,'sports','ezstring'),(1007,'eng-GB',1,260,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(1008,'eng-GB',1,260,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(1009,'eng-GB',1,260,214,'Recent items',0,0,0,0,'recent items','ezstring'),(1010,'eng-GB',1,260,215,'All Categories',0,0,0,0,'all categories','ezstring'),(1011,'eng-GB',1,261,204,'Liverpool FC',0,0,0,0,'liverpool fc','ezstring'),(1012,'eng-GB',1,261,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The best team ever</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1013,'eng-GB',1,261,206,'',9,0,0,0,'','ezurl'),(1014,'eng-GB',1,262,207,'Which do you like the best: Matrix or Lord of the Rings?',0,0,0,0,'which do you like the best: matrix or lord of the','ezstring'),(1015,'eng-GB',1,262,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Matrix</option>\n    <option id=\"1\"\n            additional_price=\"\">Lord of the Rings </option>\n    <option id=\"2\"\n            additional_price=\"\">None</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(1016,'eng-GB',1,263,202,'Party!',0,0,0,0,'party!','ezstring'),(1017,'eng-GB',1,263,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>I was invited to a party at a friends house this weekend. And I was told that the girl of my dreams will also be there. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1018,'eng-GB',1,263,213,'',1,0,0,1,'','ezboolean'),(1019,'eng-GB',1,264,4,'Fun',0,0,0,0,'fun','ezstring'),(1020,'eng-GB',1,264,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(1021,'eng-GB',1,264,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(1022,'eng-GB',1,264,214,'Recent items',0,0,0,0,'recent items','ezstring'),(1023,'eng-GB',1,264,215,'All Categories',0,0,0,0,'all categories','ezstring'),(1024,'eng-GB',1,265,204,'Pondus',0,0,0,0,'pondus','ezstring'),(1025,'eng-GB',1,265,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Have you ever discovered the football fanatic? He is available in several languages but this site is in Norwegian only.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1026,'eng-GB',1,265,206,'',10,0,0,0,'','ezurl'),(1027,'eng-GB',1,266,209,'Tim',0,0,0,0,'tim','ezstring'),(1028,'eng-GB',1,266,210,'',0,0,0,0,'','ezstring'),(1029,'eng-GB',1,266,211,'',0,0,0,0,'','ezstring'),(1030,'eng-GB',1,266,212,'When will you share it with the rest of us?',0,0,0,0,'','eztext'),(1031,'eng-GB',1,267,209,'Helene',0,0,0,0,'helene','ezstring'),(1032,'eng-GB',1,267,210,'',0,0,0,0,'','ezstring'),(1033,'eng-GB',1,267,211,'',0,0,0,0,'','ezstring'),(1034,'eng-GB',1,267,212,'Can I come? What\'s the address?',0,0,0,0,'','eztext'),(671,'eng-GB',70,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_attribute ENABLE KEYS */;

--
-- Table structure for table 'ezcontentobject_link'
--

DROP TABLE IF EXISTS ezcontentobject_link;
CREATE TABLE ezcontentobject_link (
  id int(11) NOT NULL auto_increment,
  from_contentobject_id int(11) NOT NULL default '0',
  from_contentobject_version int(11) NOT NULL default '0',
  to_contentobject_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentobject_link'
--

/*!40000 ALTER TABLE ezcontentobject_link DISABLE KEYS */;
LOCK TABLES ezcontentobject_link WRITE;
INSERT INTO ezcontentobject_link VALUES (1,1,4,49),(4,1,5,49);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_link ENABLE KEYS */;

--
-- Table structure for table 'ezcontentobject_name'
--

DROP TABLE IF EXISTS ezcontentobject_name;
CREATE TABLE ezcontentobject_name (
  contentobject_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  content_version int(11) NOT NULL default '0',
  content_translation varchar(20) NOT NULL default '',
  real_translation varchar(20) default NULL,
  PRIMARY KEY  (contentobject_id,content_version,content_translation)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentobject_name'
--

/*!40000 ALTER TABLE ezcontentobject_name DISABLE KEYS */;
LOCK TABLES ezcontentobject_name WRITE;
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(233,'bård',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(235,'kjh',1,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(165,'',1,'eng-GB','eng-GB'),(236,'Soft house',1,'eng-GB','eng-GB'),(237,'New big discovery today',1,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(87,'New Company',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(214,'Today I got my new car!',1,'eng-GB','eng-GB'),(215,'Special things happened today',1,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(169,'test',1,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(213,'Computers',1,'eng-GB','eng-GB'),(168,'',1,'eng-GB','eng-GB'),(234,'Sina',1,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(212,'Personal',1,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(240,'Polls',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(240,'Polls',2,'eng-GB','eng-GB'),(227,'Which color do you like?',2,'eng-GB','eng-GB'),(221,'New Poll',1,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(239,'Bård',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(216,'New Poll',1,'eng-GB','eng-GB'),(49,'Blogs',2,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(238,'No comments on this one',1,'eng-GB','eng-GB'),(171,'',1,'eng-GB','eng-GB'),(172,'',1,'eng-GB','eng-GB'),(173,'',1,'eng-GB','eng-GB'),(174,'',1,'eng-GB','eng-GB'),(175,'',1,'eng-GB','eng-GB'),(176,'',1,'eng-GB','eng-GB'),(177,'',1,'eng-GB','eng-GB'),(178,'',1,'eng-GB','eng-GB'),(179,'',1,'eng-GB','eng-GB'),(180,'',1,'eng-GB','eng-GB'),(181,'',1,'eng-GB','eng-GB'),(182,'',1,'eng-GB','eng-GB'),(183,'',1,'eng-GB','eng-GB'),(184,'',1,'eng-GB','eng-GB'),(185,'',1,'eng-GB','eng-GB'),(186,'New Forum topic',1,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(191,'',1,'eng-GB','eng-GB'),(192,'',1,'eng-GB','eng-GB'),(193,'',1,'eng-GB','eng-GB'),(194,'New Forum topic',1,'eng-GB','eng-GB'),(228,'Links',1,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(231,'News',1,'eng-GB','eng-GB'),(200,'test',1,'eng-GB','eng-GB'),(201,'Re:test',1,'eng-GB','eng-GB'),(232,'VG',1,'eng-GB','eng-GB'),(227,'Which color do you like?',1,'eng-GB','eng-GB'),(226,'For Posterity\'s Sake',1,'eng-GB','eng-GB'),(212,'Personal',2,'eng-GB','eng-GB'),(213,'Computers',2,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(230,'Movie',1,'eng-GB','eng-GB'),(229,'Software',1,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(224,'New Poll',1,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(222,'New Poll',1,'eng-GB','eng-GB'),(225,'New Poll',1,'eng-GB','eng-GB'),(218,'New Poll',1,'eng-GB','eng-GB'),(219,'Bård Farstad',1,'eng-GB','eng-GB'),(220,'båbåb',1,'eng-GB','eng-GB'),(217,'New Poll',1,'eng-GB','eng-GB'),(1,'Blog',6,'eng-GB','eng-GB'),(161,'About me',2,'eng-GB','eng-GB'),(241,'Which one is the best of matrix movies?',1,'eng-GB','eng-GB'),(242,'ghghj',1,'eng-GB','eng-GB'),(243,'Entertainment',1,'eng-GB','eng-GB'),(244,'A Pirate\'s Life',1,'eng-GB','eng-GB'),(56,'Blog',43,'eng-GB','eng-GB'),(56,'Blog',47,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(245,'kjlh',1,'eng-GB','eng-GB'),(56,'Blog',48,'eng-GB','eng-GB'),(246,'kjhkjh',1,'eng-GB','eng-GB'),(56,'Blog',49,'eng-GB','eng-GB'),(247,'I overslept today',1,'eng-GB','eng-GB'),(228,'Links',2,'eng-GB','eng-GB'),(49,'Blogs',3,'eng-GB','eng-GB'),(228,'Links',3,'eng-GB','eng-GB'),(49,'Blogs',4,'eng-GB','eng-GB'),(228,'Links',4,'eng-GB','eng-GB'),(49,'Blogs',5,'eng-GB','eng-GB'),(56,'Blog',50,'eng-GB','eng-GB'),(56,'Blog',51,'eng-GB','eng-GB'),(240,'Polls',3,'eng-GB','eng-GB'),(56,'Blog',52,'eng-GB','eng-GB'),(56,'Blog',55,'eng-GB','eng-GB'),(56,'Blog',56,'eng-GB','eng-GB'),(56,'Blog',57,'eng-GB','eng-GB'),(56,'Blog',58,'eng-GB','eng-GB'),(56,'Blog',59,'eng-GB','eng-GB'),(56,'Blog',60,'eng-GB','eng-GB'),(248,'ete',1,'eng-GB','eng-GB'),(249,'',1,'eng-GB','eng-GB'),(250,'what is the problem?',1,'eng-GB','eng-GB'),(56,'Blog test',63,'eng-GB','eng-GB'),(161,'About me',3,'eng-GB','eng-GB'),(56,'Blog',61,'eng-GB','eng-GB'),(56,'Blog',62,'eng-GB','eng-GB'),(56,'Blog test',64,'eng-GB','eng-GB'),(56,'Blog test',65,'eng-GB','eng-GB'),(56,'Blog',66,'eng-GB','eng-GB'),(56,'Blog',67,'eng-GB','eng-GB'),(56,'Blog',68,'eng-GB','eng-GB'),(214,'Today I got my new car!',2,'eng-GB','eng-GB'),(252,'I overslept again',1,'eng-GB','eng-GB'),(253,'Tonight I was at the movies',1,'eng-GB','eng-GB'),(254,'Finally I got it',1,'eng-GB','eng-GB'),(231,'Downloads',2,'eng-GB','eng-GB'),(255,'eZ systems',1,'eng-GB','eng-GB'),(256,'eZ publish at Freshmeat',1,'eng-GB','eng-GB'),(257,'Movies',1,'eng-GB','eng-GB'),(258,'The Matrix',1,'eng-GB','eng-GB'),(259,'Lord of the Rings',1,'eng-GB','eng-GB'),(260,'Sports',1,'eng-GB','eng-GB'),(261,'Liverpool FC',1,'eng-GB','eng-GB'),(241,'Which one is the best of matrix movies?',2,'eng-GB','eng-GB'),(262,'Which do you like the best: Matrix or Lord of the Rings?',1,'eng-GB','eng-GB'),(263,'Party!',1,'eng-GB','eng-GB'),(264,'Fun',1,'eng-GB','eng-GB'),(265,'Pondus',1,'eng-GB','eng-GB'),(266,'Tim',1,'eng-GB','eng-GB'),(267,'Helene',1,'eng-GB','eng-GB'),(56,'Blog',69,'eng-GB','eng-GB'),(56,'Blog',70,'eng-GB','eng-GB');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_name ENABLE KEYS */;

--
-- Table structure for table 'ezcontentobject_tree'
--

DROP TABLE IF EXISTS ezcontentobject_tree;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentobject_tree'
--

/*!40000 ALTER TABLE ezcontentobject_tree DISABLE KEYS */;
LOCK TABLES ezcontentobject_tree WRITE;
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,6,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,5,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,9,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,10,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,5,1,2,'/1/2/50/',9,1,0,'blogs',50),(54,48,56,70,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/blog',54),(153,50,212,2,1,3,'/1/2/50/153/',9,1,0,'blogs/personal',153),(127,2,161,3,1,2,'/1/2/127/',9,1,0,'about_me',127),(154,50,213,2,1,3,'/1/2/50/154/',9,1,0,'blogs/computers',154),(95,46,115,4,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,3,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(184,176,253,1,1,4,'/1/2/50/176/184/',9,1,0,'blogs/entertainment/tonight_i_was_at_the_movies',184),(176,50,243,1,1,3,'/1/2/50/176/',9,1,0,'blogs/entertainment',176),(185,154,254,1,1,4,'/1/2/50/154/185/',9,1,0,'blogs/computers/finally_i_got_it',185),(198,194,267,1,1,5,'/1/2/50/153/194/198/',1,1,0,'blogs/personal/party/helene',198),(174,173,241,1,1,3,'/1/2/173/174/',9,1,1,'polls/which_one_is_the_best_of_matrix_movies',174),(173,2,240,3,1,2,'/1/2/173/',8,1,0,'polls',173),(190,188,259,1,1,4,'/1/2/161/188/190/',9,1,0,'links/movies/lord_of_the_rings',190),(191,161,260,1,1,3,'/1/2/161/191/',9,1,0,'links/sports',191),(192,191,261,1,1,4,'/1/2/161/191/192/',9,1,0,'links/sports/liverpool_fc',192),(193,173,262,1,1,3,'/1/2/173/193/',9,1,0,'polls/which_do_you_like_the_best_matrix_or_lord_of_the_rings',193),(194,153,263,1,1,4,'/1/2/50/153/194/',9,1,0,'blogs/personal/party',194),(186,164,255,1,1,4,'/1/2/161/164/186/',9,1,0,'links/downloads/ez_systems',186),(187,164,256,1,1,4,'/1/2/161/164/187/',9,1,0,'links/downloads/ez_publish_at_freshmeat',187),(164,161,231,2,1,3,'/1/2/161/164/',9,1,0,'links/downloads',164),(188,161,257,1,1,3,'/1/2/161/188/',9,1,0,'links/movies',188),(189,188,258,1,1,4,'/1/2/161/188/189/',9,1,0,'links/movies/the_matrix',189),(195,161,264,1,1,3,'/1/2/161/195/',9,1,0,'links/fun',195),(161,2,228,4,1,2,'/1/2/161/',9,1,0,'links',161),(183,153,252,1,1,4,'/1/2/50/153/183/',9,1,0,'blogs/personal/i_overslept_again',183),(196,195,265,1,1,4,'/1/2/161/195/196/',9,1,0,'links/fun/pondus',196),(197,185,266,1,1,5,'/1/2/50/154/185/197/',1,1,0,'blogs/computers/finally_i_got_it/tim',197),(155,153,214,2,1,4,'/1/2/50/153/155/',9,1,0,'blogs/personal/today_i_got_my_new_car',155);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_tree ENABLE KEYS */;

--
-- Table structure for table 'ezcontentobject_version'
--

DROP TABLE IF EXISTS ezcontentobject_version;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezcontentobject_version'
--

/*!40000 ALTER TABLE ezcontentobject_version DISABLE KEYS */;
LOCK TABLES ezcontentobject_version WRITE;
INSERT INTO ezcontentobject_version VALUES (804,1,14,6,1068710443,1068710484,1,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,3,0,0),(831,235,14,1,1068718746,1068718760,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(480,45,14,1,1066388718,1066388815,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(896,260,14,1,1069771033,1069771040,1,0,0),(884,56,14,68,1069686815,1069686910,3,0,0),(490,49,14,1,1066398007,1066398020,3,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(741,175,149,1,1068108534,1068108624,0,0,0),(895,259,14,1,1069770938,1069770983,1,0,0),(882,56,14,66,1069677684,1069677719,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(734,168,149,1,1068048359,1068048594,0,0,0),(832,236,14,1,1068718999,1068719250,1,0,0),(833,237,14,1,1068718996,1068719051,1,0,0),(731,165,149,1,1068048190,1068048359,0,0,0),(806,212,14,1,1068711059,1068711069,3,0,0),(836,240,14,1,1068719631,1068719643,3,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(885,214,14,2,1069769855,1069770036,1,0,0),(725,161,14,1,1068047518,1068047603,3,0,0),(881,56,14,65,1069677489,1069677521,3,0,0),(905,56,14,69,1069838667,1069838835,3,0,0),(740,174,149,1,1068050123,1068108534,0,0,0),(880,56,14,64,1069676476,1069676517,3,0,0),(894,258,14,1,1069770876,1069770910,1,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(877,56,14,61,1069414825,1069414843,3,0,0),(842,244,14,1,1068727900,1068727917,1,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(845,43,14,9,1068729346,1068729356,1,0,0),(739,173,149,1,1068050088,1068050123,0,0,0),(809,215,14,1,1068713628,1068713677,1,0,0),(838,240,14,2,1068720650,1068720665,3,0,0),(738,172,149,1,1068049706,1068050088,0,0,0),(735,169,149,1,1068048594,1068048622,0,0,0),(906,56,14,70,1069839457,1069839484,1,0,0),(808,214,14,1,1068711110,1068711140,3,0,0),(737,171,149,1,1068049618,1068049706,0,0,0),(878,56,14,62,1069429714,1069429727,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(835,239,14,1,1068719292,1068719374,1,0,0),(834,238,14,1,1068719106,1068719128,1,0,0),(672,1,14,2,1067871685,1067871717,3,1,0),(883,56,14,67,1069685397,1069685414,3,0,0),(668,49,14,2,1067357193,1068711012,3,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(695,1,14,3,1068035768,1068035779,3,1,0),(848,245,14,1,1068730464,1068730475,1,0,0),(844,115,14,4,1068729296,1068729308,1,0,0),(813,219,14,1,1068716892,1068716920,1,0,0),(893,257,14,1,1069770837,1069770849,1,0,0),(892,256,14,1,1069770742,1069770809,1,0,0),(891,255,14,1,1069770648,1069770690,1,0,0),(840,242,14,1,1068720892,1068720915,1,0,0),(841,243,14,1,1068727844,1068727871,1,0,0),(890,231,14,2,1069770628,1069770638,1,0,0),(807,213,14,1,1068711079,1068711091,3,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(839,241,14,1,1068720708,1068720802,1,0,0),(837,227,14,2,1068719654,1068719695,1,0,0),(742,176,149,1,1068108624,1068108805,0,0,0),(743,177,149,1,1068108805,1068108834,0,0,0),(744,178,149,1,1068108834,1068108898,0,0,0),(745,179,149,1,1068108898,1068109016,0,0,0),(746,180,149,1,1068109016,1068109220,0,0,0),(747,181,149,1,1068109220,1068109255,0,0,0),(748,182,149,1,1068109255,1068109498,0,0,0),(749,183,149,1,1068109498,1068109663,0,0,0),(750,184,149,1,1068109663,1068109781,0,0,0),(751,185,149,1,1068109781,1068109829,0,0,0),(752,186,149,1,1068109829,1068109829,0,0,0),(889,161,14,3,1069770402,1069770484,1,0,0),(888,254,14,1,1069770267,1069770356,1,0,0),(758,191,149,1,1068111317,1068111376,0,0,0),(759,192,149,1,1068111376,1068111870,0,0,0),(760,193,149,1,1068111870,1068111917,0,0,0),(761,194,149,1,1068111917,1068111917,0,0,0),(825,229,14,1,1068718643,1068718672,1,0,0),(830,234,14,1,1068718886,1068718957,1,0,0),(829,233,14,1,1068718686,1068718705,1,0,0),(769,200,149,1,1068120480,1068120496,0,0,0),(770,201,149,1,1068120737,1068120756,0,0,0),(824,228,14,1,1068718602,1068718628,3,0,0),(887,253,14,1,1069770176,1069770254,1,0,0),(819,213,14,2,1068717682,1068717696,1,0,0),(818,212,14,2,1068717602,1068717667,1,0,0),(828,232,14,1,1068718770,1068718860,1,0,0),(886,252,14,1,1069770048,1069770140,1,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(879,56,14,63,1069676374,1069676432,3,0,0),(823,227,14,1,1068717981,1068718128,3,0,0),(822,226,14,1,1068717922,1068717934,1,0,0),(827,231,14,1,1068718724,1068718745,3,0,0),(826,230,14,1,1068718683,1068718712,1,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(874,250,10,1,1069409353,1069411652,0,0,0),(796,14,14,5,1068468183,1068468218,1,0,0),(814,220,14,1,1068716938,1068716967,1,0,0),(847,116,14,3,1068729385,1068729395,1,0,0),(873,249,10,1,1069409163,1069409163,0,0,0),(852,247,14,1,1068796274,1068796296,1,0,0),(850,246,14,1,1068737185,1068737197,1,0,0),(846,45,14,10,1068729368,1068729376,1,0,0),(872,248,10,1,1069408745,1069408767,1,0,0),(805,161,14,2,1068710499,1068710511,3,0,0),(853,228,14,2,1068803436,1068803511,3,0,0),(854,49,14,3,1068803526,1068803558,3,0,0),(855,228,14,3,1068803993,1068804002,3,0,0),(856,49,14,4,1068804018,1068804028,3,0,0),(857,228,14,4,1068804664,1068804675,1,0,0),(858,49,14,5,1068804682,1068804689,1,0,0),(861,240,14,3,1069172829,1069172878,1,0,0),(897,261,14,1,1069771057,1069771089,1,0,0),(899,262,14,1,1069771197,1069771243,1,0,0),(900,263,14,1,1069771432,1069771496,1,0,0),(901,264,14,1,1069773530,1069773539,1,0,0),(902,265,14,1,1069773548,1069773629,1,0,0),(903,266,10,1,1069773747,1069773783,1,0,0),(904,267,10,1,1069773805,1069773826,1,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_version ENABLE KEYS */;

--
-- Table structure for table 'ezdiscountrule'
--

DROP TABLE IF EXISTS ezdiscountrule;
CREATE TABLE ezdiscountrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezdiscountrule'
--

/*!40000 ALTER TABLE ezdiscountrule DISABLE KEYS */;
LOCK TABLES ezdiscountrule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezdiscountrule ENABLE KEYS */;

--
-- Table structure for table 'ezdiscountsubrule'
--

DROP TABLE IF EXISTS ezdiscountsubrule;
CREATE TABLE ezdiscountsubrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  discountrule_id int(11) NOT NULL default '0',
  discount_percent float default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezdiscountsubrule'
--

/*!40000 ALTER TABLE ezdiscountsubrule DISABLE KEYS */;
LOCK TABLES ezdiscountsubrule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezdiscountsubrule ENABLE KEYS */;

--
-- Table structure for table 'ezdiscountsubrule_value'
--

DROP TABLE IF EXISTS ezdiscountsubrule_value;
CREATE TABLE ezdiscountsubrule_value (
  discountsubrule_id int(11) NOT NULL default '0',
  value int(11) NOT NULL default '0',
  issection int(1) NOT NULL default '0',
  PRIMARY KEY  (discountsubrule_id,value,issection)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezdiscountsubrule_value'
--

/*!40000 ALTER TABLE ezdiscountsubrule_value DISABLE KEYS */;
LOCK TABLES ezdiscountsubrule_value WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezdiscountsubrule_value ENABLE KEYS */;

--
-- Table structure for table 'ezenumobjectvalue'
--

DROP TABLE IF EXISTS ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumid int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid),
  KEY ezenumobjectvalue_co_attr_id_co_attr_ver (contentobject_attribute_id,contentobject_attribute_version)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezenumobjectvalue'
--

/*!40000 ALTER TABLE ezenumobjectvalue DISABLE KEYS */;
LOCK TABLES ezenumobjectvalue WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezenumobjectvalue ENABLE KEYS */;

--
-- Table structure for table 'ezenumvalue'
--

DROP TABLE IF EXISTS ezenumvalue;
CREATE TABLE ezenumvalue (
  id int(11) NOT NULL auto_increment,
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,contentclass_attribute_id,contentclass_attribute_version),
  KEY ezenumvalue_co_cl_attr_id_co_class_att_ver (contentclass_attribute_id,contentclass_attribute_version)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezenumvalue'
--

/*!40000 ALTER TABLE ezenumvalue DISABLE KEYS */;
LOCK TABLES ezenumvalue WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezenumvalue ENABLE KEYS */;

--
-- Table structure for table 'ezforgot_password'
--

DROP TABLE IF EXISTS ezforgot_password;
CREATE TABLE ezforgot_password (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  hash_key varchar(32) NOT NULL default '',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezforgot_password'
--

/*!40000 ALTER TABLE ezforgot_password DISABLE KEYS */;
LOCK TABLES ezforgot_password WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezforgot_password ENABLE KEYS */;

--
-- Table structure for table 'ezgeneral_digest_user_settings'
--

DROP TABLE IF EXISTS ezgeneral_digest_user_settings;
CREATE TABLE ezgeneral_digest_user_settings (
  id int(11) NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  receive_digest int(11) NOT NULL default '0',
  digest_type int(11) NOT NULL default '0',
  day varchar(255) NOT NULL default '',
  time varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezgeneral_digest_user_settings'
--

/*!40000 ALTER TABLE ezgeneral_digest_user_settings DISABLE KEYS */;
LOCK TABLES ezgeneral_digest_user_settings WRITE;
INSERT INTO ezgeneral_digest_user_settings VALUES (1,'bf@ez.no',0,0,'',''),(2,'wy@ez.no',0,0,'','');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezgeneral_digest_user_settings ENABLE KEYS */;

--
-- Table structure for table 'ezimage'
--

DROP TABLE IF EXISTS ezimage;
CREATE TABLE ezimage (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  alternative_text varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezimage'
--

/*!40000 ALTER TABLE ezimage DISABLE KEYS */;
LOCK TABLES ezimage WRITE;
INSERT INTO ezimage VALUES (103,4,'phpWJgae7.png','kaddressbook.png','image/png',''),(103,5,'php7ZhvcB.png','chardevice.png','image/png',''),(109,1,'phpvzmRGW.png','folder_txt.png','image/png',''),(120,11,'phpG6qloJ.gif','ezpublish_logo_blue.gif','image/gif',''),(152,15,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(120,13,'phpG6qloJ.gif','ezpublish_logo_blue.gif','image/gif',''),(152,12,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,13,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,11,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,16,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,7,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,18,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,9,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,10,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,14,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(152,17,'phpZWf2sh.gif','phpCfM6Z4_600x600_68578.gif','image/gif',''),(268,1,'php8lV61b.png','phphWMyJs.png','image/png',''),(268,2,'php8lV61b.png','phphWMyJs.png','image/png',''),(287,1,'phpjqUhJn.jpg','017_8_1small.jpg','image/jpeg',''),(292,2,'phpCKfj8I.png','phpCG9Rrg_600x600_97870.png','image/png',''),(293,2,'php2e1GsG.png','bj.png','image/png',''),(293,3,'php2e1GsG.png','bj.png','image/png',''),(103,6,'phpXz5esv.jpg','TN_a5.JPG','image/jpeg',''),(109,2,'phppIJtoa.jpg','maidinmanhattantop.jpg','image/jpeg',''),(103,7,'phpG0YSsD.png','gnome-settings.png','image/png',''),(109,3,'phpAhcEu9.png','gnome-favorites.png','image/png',''),(324,1,'php4sHmOe.png','gnome-ccperiph.png','image/png',''),(109,4,'phpbVfzkm.png','gnome-devel.png','image/png',''),(328,1,'php7a7vQE.png','gnome-globe.png','image/png',''),(109,5,'phpvs7kFg.png','gnome-color-browser.png','image/png',''),(400,2,'phprwazbD.jpg','vbanner.jpg','image/jpeg','');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezimage ENABLE KEYS */;

--
-- Table structure for table 'ezimagefile'
--

DROP TABLE IF EXISTS ezimagefile;
CREATE TABLE ezimagefile (
  id int(11) NOT NULL auto_increment,
  contentobject_attribute_id int(11) NOT NULL default '0',
  filepath text NOT NULL,
  PRIMARY KEY  (id),
  KEY ezimagefile_coid (contentobject_attribute_id),
  KEY ezimagefile_file (filepath(200))
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezimagefile'
--

/*!40000 ALTER TABLE ezimagefile DISABLE KEYS */;
LOCK TABLES ezimagefile WRITE;
INSERT INTO ezimagefile VALUES (43,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_medium.gif'),(42,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_reference.gif'),(41,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog.gif'),(5,152,'var/blog/storage/images/setup/look_and_feel/blog/152-52-eng-GB/blog_logo.gif'),(6,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog.gif'),(7,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_reference.gif'),(8,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_medium.gif'),(9,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_logo.gif'),(10,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog.gif'),(11,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_reference.gif'),(12,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_medium.gif'),(13,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_logo.gif'),(14,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog.gif'),(15,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_reference.gif'),(16,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_medium.gif'),(17,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_logo.gif'),(18,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog.gif'),(19,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_reference.gif'),(20,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_medium.gif'),(21,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_logo.gif'),(22,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog.gif'),(23,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_reference.gif'),(24,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_medium.gif'),(25,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_logo.gif'),(26,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog.gif'),(27,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_reference.gif'),(28,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_medium.gif'),(29,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_logo.gif'),(30,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog.gif'),(31,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_reference.gif'),(32,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_medium.gif'),(33,103,'var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png'),(34,103,'var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png'),(35,109,'var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png'),(36,109,'var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png'),(37,324,'var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png'),(38,324,'var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png'),(39,328,'var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png'),(40,328,'var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png'),(44,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_logo.gif'),(45,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test.gif'),(46,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_reference.gif'),(47,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_medium.gif'),(48,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test.gif'),(49,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_reference.gif'),(50,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_medium.gif'),(51,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test.gif'),(52,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_reference.gif'),(53,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_medium.gif'),(54,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_logo.gif'),(55,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog.gif'),(56,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_reference.gif'),(57,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_medium.gif'),(58,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_logo.gif'),(59,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog.gif'),(60,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_reference.gif'),(61,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_medium.gif'),(62,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_logo.gif'),(63,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog.gif'),(64,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_reference.gif'),(65,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_medium.gif'),(66,524,'var/blog/storage/images/about_me/524-3-eng-GB/about_me.'),(68,152,'var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog.gif'),(69,152,'var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_reference.gif'),(70,152,'var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_medium.gif'),(71,152,'var/blog/storage/images/setup/look_and_feel/blog/152-69-eng-GB/blog_logo.gif'),(72,152,'var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog.gif'),(73,152,'var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_reference.gif'),(74,152,'var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_medium.gif'),(75,152,'var/blog/storage/images/setup/look_and_feel/blog/152-70-eng-GB/blog_logo.gif');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezimagefile ENABLE KEYS */;

--
-- Table structure for table 'ezimagevariation'
--

DROP TABLE IF EXISTS ezimagevariation;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezimagevariation'
--

/*!40000 ALTER TABLE ezimagevariation DISABLE KEYS */;
LOCK TABLES ezimagevariation WRITE;
INSERT INTO ezimagevariation VALUES (103,4,'phpWJgae7_100x100_103.png','p/h/p',100,100,48,48),(103,4,'phpWJgae7_600x600_103.png','p/h/p',600,600,48,48),(103,5,'php7ZhvcB_100x100_103.png','p/h/p',100,100,48,48),(109,1,'phpvzmRGW_100x100_109.png','p/h/p',100,100,48,48),(103,5,'php7ZhvcB_600x600_103.png','p/h/p',600,600,48,48),(109,1,'phpvzmRGW_600x600_109.png','p/h/p',600,600,48,48),(293,2,'php2e1GsG_600x600_293.png','p/h/p',600,600,186,93),(120,11,'phpG6qloJ_100x100_120.gif.png','p/h/p',100,100,100,16),(292,2,'phpCKfj8I_600x600_292.png','p/h/p',600,600,186,93),(152,13,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35),(293,2,'php2e1GsG_100x100_293.png','p/h/p',100,100,100,50),(120,11,'phpG6qloJ_600x600_120.gif.png','p/h/p',600,600,129,21),(152,12,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35),(152,11,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35),(292,2,'phpCKfj8I_100x100_292.png','p/h/p',100,100,100,50),(287,1,'phpjqUhJn_100x100_287.jpg','p/h/p',100,100,73,100),(268,2,'php8lV61b_100x100_268.png','p/h/p',100,100,100,93),(268,1,'php8lV61b_150x150_268.png','p/h/p',150,150,144,134),(152,16,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35),(152,7,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35),(268,1,'php8lV61b_100x100_268.png','p/h/p',100,100,100,93),(152,9,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35),(152,10,'phpZWf2sh_100x100_152.gif.png','p/h/p',100,100,100,35),(293,2,'php2e1GsG_150x150_293.png','p/h/p',150,150,150,75),(292,2,'phpCKfj8I_150x150_292.png','p/h/p',150,150,150,75),(293,3,'php2e1GsG_100x100_293.png','p/h/p',100,100,100,50),(103,6,'phpXz5esv_600x600_103.jpg','p/h/p',600,600,377,600),(109,2,'phppIJtoa_600x600_109.jpg','p/h/p',600,600,116,61),(103,7,'phpG0YSsD_600x600_103.png','p/h/p',600,600,48,48),(109,3,'phpAhcEu9_600x600_109.png','p/h/p',600,600,48,52),(324,1,'php4sHmOe_600x600_324.png','p/h/p',600,600,48,48),(109,4,'phpbVfzkm_600x600_109.png','p/h/p',600,600,48,48),(328,1,'php7a7vQE_600x600_328.png','p/h/p',600,600,48,48),(109,5,'phpvs7kFg_600x600_109.png','p/h/p',600,600,48,48),(268,2,'php8lV61b_150x150_268.png','p/h/p',150,150,144,134),(103,7,'phpG0YSsD_150x150_103.png','p/h/p',150,150,48,48),(109,5,'phpvs7kFg_150x150_109.png','p/h/p',150,150,48,48),(324,1,'php4sHmOe_150x150_324.png','p/h/p',150,150,48,48),(328,1,'php7a7vQE_150x150_328.png','p/h/p',150,150,48,48),(400,2,'phprwazbD_100x100_400.jpg','p/h/p',100,100,100,33),(400,2,'phprwazbD_600x600_400.jpg','p/h/p',600,600,450,150);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezimagevariation ENABLE KEYS */;

--
-- Table structure for table 'ezinfocollection'
--

DROP TABLE IF EXISTS ezinfocollection;
CREATE TABLE ezinfocollection (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  user_identifier varchar(34) default NULL,
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezinfocollection'
--

/*!40000 ALTER TABLE ezinfocollection DISABLE KEYS */;
LOCK TABLES ezinfocollection WRITE;
INSERT INTO ezinfocollection VALUES (1,137,1068027503,'c6194244e6057c2ed46e92ac8c59be21',1068027503),(2,137,1068028058,'c6194244e6057c2ed46e92ac8c59be21',1068028058),(3,227,1068718291,'c6194244e6057c2ed46e92ac8c59be21',1068718291),(4,227,1068718359,'c6194244e6057c2ed46e92ac8c59be21',1068718359),(5,227,1068721732,'c6194244e6057c2ed46e92ac8c59be21',1068721732),(6,227,1068723204,'c6194244e6057c2ed46e92ac8c59be21',1068723204),(7,227,1068723216,'c6194244e6057c2ed46e92ac8c59be21',1068723216),(8,227,1068723236,'c6194244e6057c2ed46e92ac8c59be21',1068723236),(9,227,1068723826,'c6194244e6057c2ed46e92ac8c59be21',1068723826),(10,227,1068723856,'c6194244e6057c2ed46e92ac8c59be21',1068723856),(11,227,1068724005,'c6194244e6057c2ed46e92ac8c59be21',1068724005),(12,227,1068724227,'c6194244e6057c2ed46e92ac8c59be21',1068724227),(13,227,1068726335,'c6194244e6057c2ed46e92ac8c59be21',1068726335),(14,227,1068726772,'c6194244e6057c2ed46e92ac8c59be21',1068726772),(15,227,1068727910,'c6194244e6057c2ed46e92ac8c59be21',1068727910),(16,227,1068729189,'9d6d05ca28ed8f65e38e0e7f01741744',1068729189),(17,227,1068729968,'cf64399b65e473dd59293d990f30bfbf',1068729968),(18,227,1068731428,'c6194244e6057c2ed46e92ac8c59be21',1068731428),(19,227,1068731436,'c6194244e6057c2ed46e92ac8c59be21',1068731436),(20,227,1068731442,'c6194244e6057c2ed46e92ac8c59be21',1068731442),(21,227,1068732540,'c6194244e6057c2ed46e92ac8c59be21',1068732540),(22,227,1068736388,'c6194244e6057c2ed46e92ac8c59be21',1068736388),(23,227,1068736850,'c6194244e6057c2ed46e92ac8c59be21',1068736850),(24,227,1068737071,'c6194244e6057c2ed46e92ac8c59be21',1068737071),(25,227,1068796372,'c6194244e6057c2ed46e92ac8c59be21',1068796372),(26,227,1068823513,'246f16e4f01d95e37296d2c33eff57d7',1068823513),(27,227,1068823514,'246f16e4f01d95e37296d2c33eff57d7',1068823514),(28,241,1069173187,'246f16e4f01d95e37296d2c33eff57d7',1069173187),(29,227,1069173197,'246f16e4f01d95e37296d2c33eff57d7',1069173197),(30,227,1069344809,'246f16e4f01d95e37296d2c33eff57d7',1069344809),(31,262,1069773683,'5b5270557d219bf1e9501f082d09e959',1069773683);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezinfocollection ENABLE KEYS */;

--
-- Table structure for table 'ezinfocollection_attribute'
--

DROP TABLE IF EXISTS ezinfocollection_attribute;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezinfocollection_attribute'
--

/*!40000 ALTER TABLE ezinfocollection_attribute DISABLE KEYS */;
LOCK TABLES ezinfocollection_attribute WRITE;
INSERT INTO ezinfocollection_attribute VALUES (1,1,'',0,0,183,443,137),(2,1,'',0,0,185,445,137),(3,1,'',0,0,184,444,137),(4,2,'FOo bar ',0,0,183,443,137),(5,2,'bf@ez.no',0,0,185,445,137),(6,2,'This is my feedback.',0,0,184,444,137),(7,3,'',0,0,208,789,227),(8,4,'',2,0,208,789,227),(9,5,'',2,0,208,789,227),(10,6,'',3,0,208,789,227),(11,7,'',4,0,208,789,227),(12,8,'',1,0,208,789,227),(13,9,'',1,0,208,789,227),(14,10,'',1,0,208,789,227),(15,11,'',3,0,208,789,227),(16,12,'',3,0,208,789,227),(17,13,'',3,0,208,789,227),(18,14,'',0,0,208,789,227),(19,15,'',1,0,208,789,227),(20,16,'',2,0,208,789,227),(21,17,'',2,0,208,789,227),(22,18,'',0,0,208,789,227),(23,19,'',0,0,208,789,227),(24,20,'',0,0,208,789,227),(25,21,'',0,0,208,789,227),(26,22,'',0,0,208,789,227),(27,23,'',1,0,208,789,227),(28,24,'',1,0,208,789,227),(29,25,'',2,0,208,789,227),(30,26,'',0,0,208,789,227),(31,27,'',0,0,208,789,227),(32,28,'',1,0,208,831,241),(33,29,'',3,0,208,789,227),(34,30,'',4,0,208,789,227),(35,31,'',0,0,208,1015,262);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezinfocollection_attribute ENABLE KEYS */;

--
-- Table structure for table 'ezkeyword'
--

DROP TABLE IF EXISTS ezkeyword;
CREATE TABLE ezkeyword (
  id int(11) NOT NULL auto_increment,
  keyword varchar(255) default NULL,
  class_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezkeyword'
--

/*!40000 ALTER TABLE ezkeyword DISABLE KEYS */;
LOCK TABLES ezkeyword WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezkeyword ENABLE KEYS */;

--
-- Table structure for table 'ezkeyword_attribute_link'
--

DROP TABLE IF EXISTS ezkeyword_attribute_link;
CREATE TABLE ezkeyword_attribute_link (
  id int(11) NOT NULL auto_increment,
  keyword_id int(11) NOT NULL default '0',
  objectattribute_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezkeyword_attribute_link'
--

/*!40000 ALTER TABLE ezkeyword_attribute_link DISABLE KEYS */;
LOCK TABLES ezkeyword_attribute_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezkeyword_attribute_link ENABLE KEYS */;

--
-- Table structure for table 'ezmedia'
--

DROP TABLE IF EXISTS ezmedia;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezmedia'
--

/*!40000 ALTER TABLE ezmedia DISABLE KEYS */;
LOCK TABLES ezmedia WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezmedia ENABLE KEYS */;

--
-- Table structure for table 'ezmessage'
--

DROP TABLE IF EXISTS ezmessage;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezmessage'
--

/*!40000 ALTER TABLE ezmessage DISABLE KEYS */;
LOCK TABLES ezmessage WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezmessage ENABLE KEYS */;

--
-- Table structure for table 'ezmodule_run'
--

DROP TABLE IF EXISTS ezmodule_run;
CREATE TABLE ezmodule_run (
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) default NULL,
  module_name varchar(255) default NULL,
  function_name varchar(255) default NULL,
  module_data text,
  PRIMARY KEY  (id),
  UNIQUE KEY ezmodule_run_workflow_process_id_s (workflow_process_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezmodule_run'
--

/*!40000 ALTER TABLE ezmodule_run DISABLE KEYS */;
LOCK TABLES ezmodule_run WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezmodule_run ENABLE KEYS */;

--
-- Table structure for table 'eznode_assignment'
--

DROP TABLE IF EXISTS eznode_assignment;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'eznode_assignment'
--

/*!40000 ALTER TABLE eznode_assignment DISABLE KEYS */;
LOCK TABLES eznode_assignment WRITE;
INSERT INTO eznode_assignment VALUES (510,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(522,227,1,2,9,1,1,0,0),(593,257,1,161,9,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(603,266,1,185,1,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(191,45,1,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(584,56,68,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(582,56,66,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(445,175,1,2,1,1,0,0,0),(438,168,1,2,1,1,0,0,0),(581,56,65,48,9,1,1,0,0),(541,243,1,50,9,1,1,0,0),(435,165,1,115,1,1,0,0,0),(586,252,1,153,9,1,1,0,0),(512,212,1,50,9,1,1,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(513,213,1,50,9,1,1,0,0),(605,56,69,48,9,1,1,0,0),(444,174,1,2,1,1,0,0,0),(580,56,64,48,9,1,1,0,0),(321,45,6,46,9,1,1,0,0),(577,56,61,48,9,1,1,0,0),(554,49,3,2,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(556,49,4,2,9,1,1,0,0),(443,173,1,2,1,1,0,0,0),(439,169,1,2,1,1,1,0,0),(544,115,4,46,9,1,1,0,0),(442,172,1,2,1,1,0,0,0),(589,161,3,2,9,1,1,0,0),(606,56,70,48,9,1,1,0,0),(545,43,9,46,9,1,1,0,0),(441,171,1,115,1,1,0,0,0),(335,45,8,46,9,1,1,0,0),(578,56,62,48,9,1,1,0,0),(546,45,10,46,9,1,1,0,0),(588,254,1,154,9,1,1,0,0),(375,1,2,1,9,1,1,0,0),(583,56,67,48,9,1,1,0,0),(371,49,2,2,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(398,1,3,1,9,1,1,0,0),(561,240,3,2,8,1,1,0,0),(519,212,2,50,9,1,1,0,0),(555,228,3,2,9,1,1,0,0),(579,56,63,48,9,1,1,0,0),(574,250,1,2,1,1,1,0,0),(553,228,2,2,9,1,1,0,0),(600,263,1,153,9,1,1,0,0),(514,214,1,153,9,1,1,0,0),(424,14,2,13,9,1,1,0,0),(587,253,1,176,9,1,1,0,0),(547,116,3,46,9,1,1,0,0),(446,176,1,2,1,1,0,0,0),(447,177,1,2,1,1,0,0,0),(448,178,1,2,1,1,0,0,0),(449,179,1,2,1,1,0,0,0),(450,180,1,2,1,1,0,0,0),(451,181,1,2,1,1,0,0,0),(452,182,1,2,1,1,0,0,0),(453,183,1,2,1,1,0,0,0),(454,184,1,2,1,1,0,0,0),(455,185,1,2,1,1,0,0,0),(456,186,1,2,1,1,1,0,0),(573,249,1,180,1,1,1,0,0),(462,191,1,115,1,1,0,0,0),(463,192,1,2,1,1,0,0,0),(464,193,1,2,1,1,0,0,0),(465,194,1,2,1,1,1,0,0),(601,264,1,161,9,1,1,0,0),(539,241,1,173,9,1,1,0,0),(595,259,1,188,9,1,1,0,0),(473,200,1,114,1,1,1,0,0),(474,201,1,135,1,1,1,0,0),(602,265,1,195,9,1,1,0,0),(591,255,1,164,9,1,1,0,0),(597,261,1,191,9,1,1,0,0),(590,231,2,161,9,1,1,0,0),(538,240,2,2,8,1,1,0,0),(526,231,1,161,9,1,1,0,0),(481,14,3,13,9,1,1,0,0),(592,256,1,164,9,1,1,0,0),(594,258,1,188,9,1,1,0,0),(596,260,1,161,9,1,1,0,0),(535,240,1,2,9,1,1,0,0),(604,267,1,194,1,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(523,228,1,2,9,1,1,0,0),(500,14,5,13,9,1,1,0,0),(520,213,2,50,9,1,1,0,0),(558,49,5,2,9,1,1,0,0),(585,214,2,153,9,1,1,0,0),(557,228,4,2,9,1,1,0,0),(599,262,1,173,9,1,1,0,0),(511,161,2,2,9,1,1,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE eznode_assignment ENABLE KEYS */;

--
-- Table structure for table 'eznotificationcollection'
--

DROP TABLE IF EXISTS eznotificationcollection;
CREATE TABLE eznotificationcollection (
  id int(11) NOT NULL auto_increment,
  event_id int(11) NOT NULL default '0',
  handler varchar(255) NOT NULL default '',
  transport varchar(255) NOT NULL default '',
  data_subject text NOT NULL,
  data_text text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'eznotificationcollection'
--

/*!40000 ALTER TABLE eznotificationcollection DISABLE KEYS */;
LOCK TABLES eznotificationcollection WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE eznotificationcollection ENABLE KEYS */;

--
-- Table structure for table 'eznotificationcollection_item'
--

DROP TABLE IF EXISTS eznotificationcollection_item;
CREATE TABLE eznotificationcollection_item (
  id int(11) NOT NULL auto_increment,
  collection_id int(11) NOT NULL default '0',
  event_id int(11) NOT NULL default '0',
  address varchar(255) NOT NULL default '',
  send_date int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'eznotificationcollection_item'
--

/*!40000 ALTER TABLE eznotificationcollection_item DISABLE KEYS */;
LOCK TABLES eznotificationcollection_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE eznotificationcollection_item ENABLE KEYS */;

--
-- Table structure for table 'eznotificationevent'
--

DROP TABLE IF EXISTS eznotificationevent;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'eznotificationevent'
--

/*!40000 ALTER TABLE eznotificationevent DISABLE KEYS */;
LOCK TABLES eznotificationevent WRITE;
INSERT INTO eznotificationevent VALUES (227,0,'ezpublish',142,3,0,0,'','','',''),(226,0,'ezpublish',141,3,0,0,'','','',''),(225,0,'ezpublish',211,2,0,0,'','','',''),(224,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',142,2,0,0,'','','',''),(222,0,'ezpublish',141,2,0,0,'','','',''),(221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','',''),(228,0,'ezpublish',1,6,0,0,'','','',''),(229,0,'ezpublish',161,2,0,0,'','','',''),(230,0,'ezpublish',49,2,0,0,'','','',''),(231,0,'ezpublish',212,1,0,0,'','','',''),(232,0,'ezpublish',213,1,0,0,'','','',''),(233,0,'ezpublish',214,1,0,0,'','','',''),(234,0,'ezpublish',215,1,0,0,'','','',''),(235,0,'ezpublish',219,1,0,0,'','','',''),(236,0,'ezpublish',220,1,0,0,'','','',''),(237,0,'ezpublish',212,2,0,0,'','','',''),(238,0,'ezpublish',213,2,0,0,'','','',''),(239,0,'ezpublish',226,1,0,0,'','','',''),(240,0,'ezpublish',227,1,0,0,'','','',''),(241,0,'ezpublish',228,1,0,0,'','','',''),(242,0,'ezpublish',229,1,0,0,'','','',''),(243,0,'ezpublish',230,1,0,0,'','','',''),(244,0,'ezpublish',231,1,0,0,'','','',''),(245,0,'ezpublish',233,1,0,0,'','','',''),(246,0,'ezpublish',232,1,0,0,'','','',''),(247,0,'ezpublish',235,1,0,0,'','','',''),(248,0,'ezpublish',234,1,0,0,'','','',''),(249,0,'ezpublish',237,1,0,0,'','','',''),(250,0,'ezpublish',236,1,0,0,'','','',''),(251,0,'ezpublish',238,1,0,0,'','','',''),(252,0,'ezpublish',239,1,0,0,'','','',''),(253,0,'ezpublish',240,1,0,0,'','','',''),(254,0,'ezpublish',227,2,0,0,'','','',''),(255,0,'ezpublish',240,2,0,0,'','','',''),(256,0,'ezpublish',241,1,0,0,'','','',''),(257,0,'ezpublish',242,1,0,0,'','','',''),(258,0,'ezpublish',243,1,0,0,'','','',''),(259,0,'ezpublish',244,1,0,0,'','','',''),(260,0,'ezpublish',56,47,0,0,'','','',''),(261,0,'ezpublish',115,4,0,0,'','','',''),(262,0,'ezpublish',43,9,0,0,'','','',''),(263,0,'ezpublish',45,10,0,0,'','','',''),(264,0,'ezpublish',116,3,0,0,'','','',''),(265,0,'ezpublish',245,1,0,0,'','','',''),(266,0,'ezpublish',56,48,0,0,'','','',''),(267,0,'ezpublish',246,1,0,0,'','','',''),(268,0,'ezpublish',56,49,0,0,'','','',''),(269,0,'ezpublish',247,1,0,0,'','','',''),(270,0,'ezpublish',228,2,0,0,'','','',''),(271,0,'ezpublish',49,3,0,0,'','','',''),(272,0,'ezpublish',228,3,0,0,'','','',''),(273,0,'ezpublish',49,4,0,0,'','','',''),(274,0,'ezpublish',228,4,0,0,'','','',''),(275,0,'ezpublish',49,5,0,0,'','','',''),(276,0,'ezpublish',56,50,0,0,'','','',''),(277,0,'ezpublish',56,51,0,0,'','','',''),(278,0,'ezpublish',240,3,0,0,'','','',''),(279,0,'ezpublish',56,52,0,0,'','','',''),(280,0,'ezpublish',56,55,0,0,'','','',''),(281,0,'ezpublish',56,56,0,0,'','','',''),(282,0,'ezpublish',56,57,0,0,'','','',''),(283,0,'ezpublish',56,58,0,0,'','','',''),(284,0,'ezpublish',56,59,0,0,'','','',''),(285,0,'ezpublish',56,60,0,0,'','','',''),(286,0,'ezpublish',248,1,0,0,'','','',''),(287,0,'ezpublish',251,1,0,0,'','','',''),(288,0,'ezpublish',56,61,0,0,'','','',''),(289,0,'ezpublish',56,62,0,0,'','','',''),(290,0,'ezpublish',56,63,0,0,'','','',''),(291,0,'ezpublish',56,64,0,0,'','','',''),(292,0,'ezpublish',56,65,0,0,'','','',''),(293,0,'ezpublish',56,66,0,0,'','','',''),(294,0,'ezpublish',56,67,0,0,'','','',''),(295,0,'ezpublish',56,68,0,0,'','','',''),(296,0,'ezpublish',214,2,0,0,'','','',''),(297,0,'ezpublish',252,1,0,0,'','','',''),(298,0,'ezpublish',253,1,0,0,'','','',''),(299,0,'ezpublish',254,1,0,0,'','','',''),(300,0,'ezpublish',161,3,0,0,'','','',''),(301,0,'ezpublish',231,2,0,0,'','','',''),(302,0,'ezpublish',255,1,0,0,'','','',''),(303,0,'ezpublish',256,1,0,0,'','','',''),(304,0,'ezpublish',257,1,0,0,'','','',''),(305,0,'ezpublish',258,1,0,0,'','','',''),(306,0,'ezpublish',259,1,0,0,'','','',''),(307,0,'ezpublish',260,1,0,0,'','','',''),(308,0,'ezpublish',261,1,0,0,'','','',''),(309,0,'ezpublish',262,1,0,0,'','','',''),(310,0,'ezpublish',263,1,0,0,'','','',''),(311,0,'ezpublish',264,1,0,0,'','','',''),(312,0,'ezpublish',265,1,0,0,'','','',''),(313,0,'ezpublish',266,1,0,0,'','','',''),(314,0,'ezpublish',267,1,0,0,'','','',''),(315,0,'ezpublish',56,69,0,0,'','','',''),(316,0,'ezpublish',56,70,0,0,'','','','');
UNLOCK TABLES;
/*!40000 ALTER TABLE eznotificationevent ENABLE KEYS */;

--
-- Table structure for table 'ezoperation_memento'
--

DROP TABLE IF EXISTS ezoperation_memento;
CREATE TABLE ezoperation_memento (
  id int(11) NOT NULL auto_increment,
  memento_key varchar(32) NOT NULL default '',
  memento_data text NOT NULL,
  main int(11) NOT NULL default '0',
  main_key varchar(32) NOT NULL default '',
  PRIMARY KEY  (id,memento_key),
  KEY ezoperation_memento_memento_key_main (memento_key,main)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezoperation_memento'
--

/*!40000 ALTER TABLE ezoperation_memento DISABLE KEYS */;
LOCK TABLES ezoperation_memento WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezoperation_memento ENABLE KEYS */;

--
-- Table structure for table 'ezorder'
--

DROP TABLE IF EXISTS ezorder;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezorder'
--

/*!40000 ALTER TABLE ezorder DISABLE KEYS */;
LOCK TABLES ezorder WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezorder ENABLE KEYS */;

--
-- Table structure for table 'ezorder_item'
--

DROP TABLE IF EXISTS ezorder_item;
CREATE TABLE ezorder_item (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  description varchar(255) default NULL,
  price float default NULL,
  vat_value int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezorder_item_order_id (order_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezorder_item'
--

/*!40000 ALTER TABLE ezorder_item DISABLE KEYS */;
LOCK TABLES ezorder_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezorder_item ENABLE KEYS */;

--
-- Table structure for table 'ezpdf_export'
--

DROP TABLE IF EXISTS ezpdf_export;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezpdf_export'
--

/*!40000 ALTER TABLE ezpdf_export DISABLE KEYS */;
LOCK TABLES ezpdf_export WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpdf_export ENABLE KEYS */;

--
-- Table structure for table 'ezpolicy'
--

DROP TABLE IF EXISTS ezpolicy;
CREATE TABLE ezpolicy (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezpolicy'
--

/*!40000 ALTER TABLE ezpolicy DISABLE KEYS */;
LOCK TABLES ezpolicy WRITE;
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(341,8,'read','content','*'),(388,1,'read','content',''),(387,1,'login','user','*'),(389,1,'create','content',''),(390,1,'edit','content',''),(391,1,'versionread','content','');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpolicy ENABLE KEYS */;

--
-- Table structure for table 'ezpolicy_limitation'
--

DROP TABLE IF EXISTS ezpolicy_limitation;
CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  policy_id int(11) default NULL,
  identifier varchar(255) NOT NULL default '',
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezpolicy_limitation'
--

/*!40000 ALTER TABLE ezpolicy_limitation DISABLE KEYS */;
LOCK TABLES ezpolicy_limitation WRITE;
INSERT INTO ezpolicy_limitation VALUES (306,388,'Class',0,'read','content'),(307,389,'Class',0,'create','content'),(308,390,'Class',0,'edit','content'),(309,391,'Class',0,'versionread','content'),(310,391,'Owner',0,'versionread','content');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpolicy_limitation ENABLE KEYS */;

--
-- Table structure for table 'ezpolicy_limitation_value'
--

DROP TABLE IF EXISTS ezpolicy_limitation_value;
CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezpolicy_limitation_value'
--

/*!40000 ALTER TABLE ezpolicy_limitation_value DISABLE KEYS */;
LOCK TABLES ezpolicy_limitation_value WRITE;
INSERT INTO ezpolicy_limitation_value VALUES (633,306,'5'),(632,306,'26'),(631,306,'25'),(630,306,'24'),(629,306,'23'),(628,306,'2'),(627,306,'12'),(626,306,'10'),(625,306,'1'),(634,307,'26'),(635,308,'26'),(636,309,'26'),(637,310,'1');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpolicy_limitation_value ENABLE KEYS */;

--
-- Table structure for table 'ezpreferences'
--

DROP TABLE IF EXISTS ezpreferences;
CREATE TABLE ezpreferences (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  name varchar(100) default NULL,
  value varchar(100) default NULL,
  PRIMARY KEY  (id),
  KEY ezpreferences_name (name)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezpreferences'
--

/*!40000 ALTER TABLE ezpreferences DISABLE KEYS */;
LOCK TABLES ezpreferences WRITE;
INSERT INTO ezpreferences VALUES (1,14,'advanced_menu','on');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpreferences ENABLE KEYS */;

--
-- Table structure for table 'ezproductcollection'
--

DROP TABLE IF EXISTS ezproductcollection;
CREATE TABLE ezproductcollection (
  id int(11) NOT NULL auto_increment,
  created int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezproductcollection'
--

/*!40000 ALTER TABLE ezproductcollection DISABLE KEYS */;
LOCK TABLES ezproductcollection WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezproductcollection ENABLE KEYS */;

--
-- Table structure for table 'ezproductcollection_item'
--

DROP TABLE IF EXISTS ezproductcollection_item;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezproductcollection_item'
--

/*!40000 ALTER TABLE ezproductcollection_item DISABLE KEYS */;
LOCK TABLES ezproductcollection_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezproductcollection_item ENABLE KEYS */;

--
-- Table structure for table 'ezproductcollection_item_opt'
--

DROP TABLE IF EXISTS ezproductcollection_item_opt;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezproductcollection_item_opt'
--

/*!40000 ALTER TABLE ezproductcollection_item_opt DISABLE KEYS */;
LOCK TABLES ezproductcollection_item_opt WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezproductcollection_item_opt ENABLE KEYS */;

--
-- Table structure for table 'ezrole'
--

DROP TABLE IF EXISTS ezrole;
CREATE TABLE ezrole (
  id int(11) NOT NULL auto_increment,
  version int(11) default '0',
  name varchar(255) NOT NULL default '',
  value char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezrole'
--

/*!40000 ALTER TABLE ezrole DISABLE KEYS */;
LOCK TABLES ezrole WRITE;
INSERT INTO ezrole VALUES (1,0,'Anonymous',''),(2,0,'Administrator','*'),(8,0,'Guest',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezrole ENABLE KEYS */;

--
-- Table structure for table 'ezrss_export'
--

DROP TABLE IF EXISTS ezrss_export;
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
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezrss_export'
--

/*!40000 ALTER TABLE ezrss_export DISABLE KEYS */;
LOCK TABLES ezrss_export WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezrss_export ENABLE KEYS */;

--
-- Table structure for table 'ezrss_export_item'
--

DROP TABLE IF EXISTS ezrss_export_item;
CREATE TABLE ezrss_export_item (
  id int(11) NOT NULL auto_increment,
  rssexport_id int(11) default NULL,
  source_node_id int(11) default NULL,
  class_id int(11) default NULL,
  title varchar(255) default NULL,
  description varchar(255) default NULL,
  PRIMARY KEY  (id),
  KEY ezrss_export_rsseid (rssexport_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezrss_export_item'
--

/*!40000 ALTER TABLE ezrss_export_item DISABLE KEYS */;
LOCK TABLES ezrss_export_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezrss_export_item ENABLE KEYS */;

--
-- Table structure for table 'ezrss_import'
--

DROP TABLE IF EXISTS ezrss_import;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezrss_import'
--

/*!40000 ALTER TABLE ezrss_import DISABLE KEYS */;
LOCK TABLES ezrss_import WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezrss_import ENABLE KEYS */;

--
-- Table structure for table 'ezsearch_object_word_link'
--

DROP TABLE IF EXISTS ezsearch_object_word_link;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsearch_object_word_link'
--

/*!40000 ALTER TABLE ezsearch_object_word_link DISABLE KEYS */;
LOCK TABLES ezsearch_object_word_link WRITE;
INSERT INTO ezsearch_object_word_link VALUES (28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(4796,43,1906,0,2,1905,0,14,1066384365,11,155,'',0),(4795,43,1905,0,1,1904,1906,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(4794,43,1904,0,0,0,1905,14,1066384365,11,152,'',0),(4802,45,1908,0,5,1907,0,14,1066388816,11,155,'',0),(4801,45,1907,0,4,25,1908,14,1066388816,11,155,'',0),(4800,45,25,0,3,34,1907,14,1066388816,11,155,'',0),(4799,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(4886,49,1954,0,5,33,0,1,1066398020,13,119,'',0),(5061,253,2044,0,19,1655,2045,23,1069770254,13,203,'',0),(5060,253,1655,0,18,1727,2044,23,1069770254,13,203,'',0),(5059,253,1727,0,17,2075,1655,23,1069770254,13,203,'',0),(5058,253,2075,0,16,1535,1727,23,1069770254,13,203,'',0),(5057,253,1535,0,15,2074,2075,23,1069770254,13,203,'',0),(5056,253,2074,0,14,1578,1535,23,1069770254,13,203,'',0),(5055,253,1578,0,13,2073,2074,23,1069770254,13,203,'',0),(5054,253,2073,0,12,2072,1578,23,1069770254,13,203,'',0),(5053,253,2072,0,11,2071,2073,23,1069770254,13,203,'',0),(5052,253,2071,0,10,2055,2072,23,1069770254,13,203,'',0),(5051,253,2055,0,9,2070,2071,23,1069770254,13,203,'',0),(5050,253,2070,0,8,2069,2055,23,1069770254,13,203,'',0),(5049,253,2069,0,7,2021,2070,23,1069770254,13,203,'',0),(5048,253,2021,0,6,1728,2069,23,1069770254,13,203,'',0),(5047,253,1728,0,5,1535,2021,23,1069770254,13,202,'',0),(5046,253,1535,0,4,1411,1728,23,1069770254,13,202,'',0),(5045,253,1411,0,3,2062,1535,23,1069770254,13,202,'',0),(5044,253,2062,0,2,1490,1411,23,1069770254,13,202,'',0),(5043,253,1490,0,1,2068,2062,23,1069770254,13,202,'',0),(5042,253,2068,0,0,0,1490,23,1069770254,13,202,'',0),(5041,252,2067,0,39,2066,0,23,1069770140,13,213,'',0),(5040,252,2066,0,38,934,2067,23,1069770140,13,203,'',0),(5039,252,934,0,37,2065,2066,23,1069770140,13,203,'',0),(5038,252,2065,0,36,2064,934,23,1069770140,13,203,'',0),(5037,252,2064,0,35,2063,2065,23,1069770140,13,203,'',0),(5036,252,2063,0,34,2062,2064,23,1069770140,13,203,'',0),(5035,252,2062,0,33,2061,2063,23,1069770140,13,203,'',0),(5034,252,2061,0,32,2060,2062,23,1069770140,13,203,'',0),(5033,252,2060,0,31,2021,2061,23,1069770140,13,203,'',0),(5032,252,2021,0,30,2059,2060,23,1069770140,13,203,'',0),(5031,252,2059,0,29,2058,2021,23,1069770140,13,203,'',0),(5030,252,2058,0,28,2057,2059,23,1069770140,13,203,'',0),(5029,252,2057,0,27,2056,2058,23,1069770140,13,203,'',0),(5028,252,2056,0,26,2055,2057,23,1069770140,13,203,'',0),(5027,252,2055,0,25,2054,2056,23,1069770140,13,203,'',0),(5026,252,2054,0,24,89,2055,23,1069770140,13,203,'',0),(5025,252,89,0,23,2053,2054,23,1069770140,13,203,'',0),(5024,252,2053,0,22,33,89,23,1069770140,13,203,'',0),(5023,252,33,0,21,2052,2053,23,1069770140,13,203,'',0),(5022,252,2052,0,20,1578,33,23,1069770140,13,203,'',0),(5021,252,1578,0,19,2051,2052,23,1069770140,13,203,'',0),(5020,252,2051,0,18,2050,1578,23,1069770140,13,203,'',0),(5019,252,2050,0,17,1554,2051,23,1069770140,13,203,'',0),(5018,252,1554,0,16,2049,2050,23,1069770140,13,203,'',0),(5017,252,2049,0,15,1490,1554,23,1069770140,13,203,'',0),(5016,252,1490,0,14,2048,2049,23,1069770140,13,203,'',0),(5015,252,2048,0,13,2021,1490,23,1069770140,13,203,'',0),(5014,252,2021,0,12,1388,2048,23,1069770140,13,203,'',0),(5013,252,1388,0,11,2047,2021,23,1069770140,13,203,'',0),(5012,252,2047,0,10,1535,1388,23,1069770140,13,203,'',0),(5011,252,1535,0,9,1537,2047,23,1069770140,13,203,'',0),(5010,252,1537,0,8,2046,1535,23,1069770140,13,203,'',0),(5009,252,2046,0,7,2045,1537,23,1069770140,13,203,'',0),(5008,252,2045,0,6,2044,2046,23,1069770140,13,203,'',0),(5007,252,2044,0,5,1490,2045,23,1069770140,13,203,'',0),(5006,252,1490,0,4,2043,2044,23,1069770140,13,203,'',0),(5005,252,2043,0,3,2042,1490,23,1069770140,13,203,'',0),(5004,252,2042,0,2,2041,2043,23,1069770140,13,202,'',0),(5003,252,2041,0,1,1490,2042,23,1069770140,13,202,'',0),(5002,252,1490,0,0,0,2041,23,1069770140,13,202,'',0),(5001,214,2040,0,40,2039,0,23,1068711140,13,213,'',1),(5000,214,2039,0,39,2038,2040,23,1068711140,13,203,'',0),(4999,214,2038,0,38,2024,2039,23,1068711140,13,203,'',0),(4998,214,2024,0,37,1535,2038,23,1068711140,13,203,'',0),(4997,214,1535,0,36,2020,2024,23,1068711140,13,203,'',0),(4996,214,2020,0,35,2037,1535,23,1068711140,13,203,'',0),(4995,214,2037,0,34,33,2020,23,1068711140,13,203,'',0),(4994,214,33,0,33,2036,2037,23,1068711140,13,203,'',0),(4993,214,2036,0,32,1560,33,23,1068711140,13,203,'',0),(4992,214,1560,0,31,2035,2036,23,1068711140,13,203,'',0),(4991,214,2035,0,30,89,1560,23,1068711140,13,203,'',0),(4990,214,89,0,29,1691,2035,23,1068711140,13,203,'',0),(4989,214,1691,0,28,1655,89,23,1068711140,13,203,'',0),(4988,214,1655,0,27,2034,1691,23,1068711140,13,203,'',0),(4987,214,2034,0,26,1490,1655,23,1068711140,13,203,'',0),(4986,214,1490,0,25,2033,2034,23,1068711140,13,203,'',0),(4985,214,2033,0,24,1381,1490,23,1068711140,13,203,'',0),(4984,214,1381,0,23,2032,2033,23,1068711140,13,203,'',0),(4983,214,2032,0,22,1655,1381,23,1068711140,13,203,'',0),(4982,214,1655,0,21,2031,2032,23,1068711140,13,203,'',0),(4981,214,2031,0,20,2030,1655,23,1068711140,13,203,'',0),(4980,214,2030,0,19,1519,2031,23,1068711140,13,203,'',0),(4979,214,1519,0,18,2029,2030,23,1068711140,13,203,'',0),(4978,214,2029,0,17,89,1519,23,1068711140,13,203,'',0),(4977,214,89,0,16,2028,2029,23,1068711140,13,203,'',0),(4976,214,2028,0,15,1655,89,23,1068711140,13,203,'',0),(4975,214,1655,0,14,2027,2028,23,1068711140,13,203,'',0),(4974,214,2027,0,13,1691,1655,23,1068711140,13,203,'',0),(4973,214,1691,0,12,2026,2027,23,1068711140,13,203,'',0),(4972,214,2026,0,11,2025,1691,23,1068711140,13,203,'',0),(4971,214,2025,0,10,2024,2026,23,1068711140,13,203,'',0),(4970,214,2024,0,9,1548,2025,23,1068711140,13,203,'',0),(4969,214,1548,0,8,1725,2024,23,1068711140,13,203,'',0),(4968,214,1725,0,7,1655,1548,23,1068711140,13,203,'',0),(4967,214,1655,0,6,2023,1725,23,1068711140,13,203,'',0),(4966,214,2023,0,5,2022,1655,23,1068711140,13,202,'',0),(4965,214,2022,0,4,2021,2023,23,1068711140,13,202,'',0),(4964,214,2021,0,3,2020,2022,23,1068711140,13,202,'',0),(4963,214,2020,0,2,1490,2021,23,1068711140,13,202,'',0),(4962,214,1490,0,1,2019,2020,23,1068711140,13,202,'',0),(4961,214,2019,0,0,0,1490,23,1068711140,13,202,'',0),(3787,213,1538,0,0,0,1538,1,1068711091,13,4,'',0),(3779,212,1532,0,0,0,89,1,1068711069,13,4,'',0),(5268,267,2160,0,7,1535,0,26,1069773826,13,212,'',0),(5267,267,1535,0,6,1562,2160,26,1069773826,13,212,'',0),(5266,267,1562,0,5,2056,1535,26,1069773826,13,212,'',0),(5265,267,2056,0,4,2159,1562,26,1069773826,13,212,'',0),(5264,267,2159,0,3,1490,2056,26,1069773826,13,212,'',0),(5263,267,1490,0,2,2120,2159,26,1069773826,13,212,'',0),(5262,267,2120,0,1,2158,1490,26,1069773826,13,212,'',0),(5261,267,2158,0,0,0,2120,26,1069773826,13,209,'',0),(5256,266,2055,0,6,1655,1535,26,1069773783,13,212,'',0),(5257,266,1535,0,7,2055,2156,26,1069773783,13,212,'',0),(5258,266,2156,0,8,1535,1537,26,1069773783,13,212,'',0),(5259,266,1537,0,9,2156,2157,26,1069773783,13,212,'',0),(5260,266,2157,0,10,1537,0,26,1069773783,13,212,'',0),(4798,45,33,0,1,32,34,14,1066388816,11,152,'',0),(4793,115,1903,0,2,7,0,14,1066991725,11,155,'',0),(4792,115,7,0,1,1903,1903,14,1066991725,11,155,'',0),(4791,115,1903,0,0,0,7,14,1066991725,11,152,'',0),(4806,116,1911,0,3,25,0,14,1066992054,11,155,'',0),(4805,116,25,0,2,1910,1911,14,1066992054,11,155,'',0),(4804,116,1910,0,1,1909,25,14,1066992054,11,152,'',0),(4803,116,1909,0,0,0,1910,14,1066992054,11,152,'',0),(4797,45,32,0,0,0,33,14,1066388816,11,152,'',0),(3784,212,1536,0,5,1535,1537,1,1068711069,13,119,'',0),(3783,212,1535,0,4,1534,1536,1,1068711069,13,119,'',0),(3782,212,1534,0,3,1533,1535,1,1068711069,13,119,'',0),(3781,212,1533,0,2,89,1534,1,1068711069,13,119,'',0),(3068,14,1362,0,5,1316,0,4,1033920830,2,199,'',0),(3067,14,1316,0,4,1361,1362,4,1033920830,2,198,'',0),(5255,266,1655,0,5,2155,2055,26,1069773783,13,212,'',0),(5254,266,2155,0,4,2131,1655,26,1069773783,13,212,'',0),(5253,266,2131,0,3,2057,2155,26,1069773783,13,212,'',0),(5252,266,2057,0,2,2154,2131,26,1069773783,13,212,'',0),(5251,266,2154,0,1,2153,2057,26,1069773783,13,212,'',0),(5250,266,2153,0,0,0,2154,26,1069773783,13,209,'',0),(5249,265,2152,0,20,2151,0,24,1069773629,12,205,'',0),(5248,265,2151,0,19,1388,2152,24,1069773629,12,205,'',0),(5247,265,1388,0,18,1725,2151,24,1069773629,12,205,'',0),(5246,265,1725,0,17,2106,1388,24,1069773629,12,205,'',0),(5245,265,2106,0,16,2066,1725,24,1069773629,12,205,'',0),(5244,265,2066,0,15,2150,2106,24,1069773629,12,205,'',0),(5243,265,2150,0,14,2149,2066,24,1069773629,12,205,'',0),(5242,265,2149,0,13,2148,2150,24,1069773629,12,205,'',0),(5241,265,2148,0,12,1388,2149,24,1069773629,12,205,'',0),(5240,265,1388,0,11,2147,2148,24,1069773629,12,205,'',0),(5239,265,2147,0,10,1725,1388,24,1069773629,12,205,'',0),(5238,265,1725,0,9,2146,2147,24,1069773629,12,205,'',0),(5237,265,2146,0,8,2145,1725,24,1069773629,12,205,'',0),(5236,265,2145,0,7,2144,2146,24,1069773629,12,205,'',0),(5235,265,2144,0,6,1535,2145,24,1069773629,12,205,'',0),(5234,265,1535,0,5,2143,2144,24,1069773629,12,205,'',0),(5233,265,2143,0,4,2115,1535,24,1069773629,12,205,'',0),(5232,265,2115,0,3,2131,2143,24,1069773629,12,205,'',0),(5231,265,2131,0,2,2045,2115,24,1069773629,12,205,'',0),(5230,265,2045,0,1,2142,2131,24,1069773629,12,205,'',0),(5229,265,2142,0,0,0,2045,24,1069773629,12,204,'',0),(5228,264,1749,0,0,0,0,1,1069773539,12,4,'',0),(5227,263,2040,0,27,2141,0,23,1069771496,13,213,'',1),(5226,263,2141,0,26,2059,2040,23,1069771496,13,203,'',0),(5225,263,2059,0,25,2140,2141,23,1069771496,13,203,'',0),(5224,263,2140,0,24,2057,2059,23,1069771496,13,203,'',0),(5223,263,2057,0,23,2139,2140,23,1069771496,13,203,'',0),(5222,263,2139,0,22,2021,2057,23,1069771496,13,203,'',0),(5221,263,2021,0,21,1537,2139,23,1069771496,13,203,'',0),(5220,263,1537,0,20,2103,2021,23,1069771496,13,203,'',0),(5219,263,2103,0,19,1535,1537,23,1069771496,13,203,'',0),(5218,263,1535,0,18,2031,2103,23,1069771496,13,203,'',0),(5217,263,2031,0,17,2138,1535,23,1069771496,13,203,'',0),(5216,263,2138,0,16,2062,2031,23,1069771496,13,203,'',0),(5215,263,2062,0,15,1490,2138,23,1069771496,13,203,'',0),(5214,263,1490,0,14,33,2062,23,1069771496,13,203,'',0),(5213,263,33,0,13,2137,1490,23,1069771496,13,203,'',0),(5212,263,2137,0,12,2066,33,23,1069771496,13,203,'',0),(5211,263,2066,0,11,2136,2137,23,1069771496,13,203,'',0),(5210,263,2136,0,10,2135,2066,23,1069771496,13,203,'',0),(5209,263,2135,0,9,89,2136,23,1069771496,13,203,'',0),(5208,263,89,0,8,1411,2135,23,1069771496,13,203,'',0),(5207,263,1411,0,7,2133,89,23,1069771496,13,203,'',0),(5206,263,2133,0,6,89,1411,23,1069771496,13,203,'',0),(5205,263,89,0,5,1578,2133,23,1069771496,13,203,'',0),(5204,263,1578,0,4,2134,89,23,1069771496,13,203,'',0),(5203,263,2134,0,3,2062,1578,23,1069771496,13,203,'',0),(5202,263,2062,0,2,1490,2134,23,1069771496,13,203,'',0),(5201,263,1490,0,1,2133,2062,23,1069771496,13,203,'',0),(5200,263,2133,0,0,0,1490,23,1069771496,13,202,'',0),(5199,262,2119,0,11,1535,0,25,1069771243,1,207,'',0),(5198,262,1535,0,10,1537,2119,25,1069771243,1,207,'',0),(5197,262,1537,0,9,2118,1535,25,1069771243,1,207,'',0),(5196,262,2118,0,8,2132,1537,25,1069771243,1,207,'',0),(5195,262,2132,0,7,1727,2118,25,1069771243,1,207,'',0),(5194,262,1727,0,6,1726,2132,25,1069771243,1,207,'',0),(5193,262,1726,0,5,1535,1727,25,1069771243,1,207,'',0),(5192,262,1535,0,4,1723,1726,25,1069771243,1,207,'',0),(5191,262,1723,0,3,2131,1535,25,1069771243,1,207,'',0),(5190,262,2131,0,2,2130,1723,25,1069771243,1,207,'',0),(5189,262,2130,0,1,1543,2131,25,1069771243,1,207,'',0),(5188,262,1543,0,0,0,2130,25,1069771243,1,207,'',0),(5187,261,2115,0,5,2129,0,24,1069771089,12,205,'',0),(5186,261,2129,0,4,1726,2115,24,1069771089,12,205,'',0),(5185,261,1726,0,3,1535,2129,24,1069771089,12,205,'',0),(5184,261,1535,0,2,2128,1726,24,1069771089,12,205,'',0),(5183,261,2128,0,1,2127,1535,24,1069771089,12,204,'',0),(5182,261,2127,0,0,0,2128,24,1069771089,12,204,'',0),(5181,260,2126,0,0,0,0,1,1069771040,12,4,'',0),(5180,259,2125,0,16,2057,0,24,1069770984,12,205,'',0),(5179,259,2057,0,15,2056,2125,24,1069770984,12,205,'',0),(5178,259,2056,0,14,2124,2057,24,1069770984,12,205,'',0),(5177,259,2124,0,13,1490,2056,24,1069770984,12,205,'',0),(5176,259,1490,0,12,1747,2124,24,1069770984,12,205,'',0),(5175,259,1747,0,11,2037,1490,24,1069770984,12,205,'',0),(5174,259,2037,0,10,2050,1747,24,1069770984,12,205,'',0),(5173,259,2050,0,9,2123,2037,24,1069770984,12,205,'',0),(5172,259,2123,0,8,1560,2050,24,1069770984,12,205,'',0),(5171,259,1560,0,7,2122,2123,24,1069770984,12,205,'',0),(5170,259,2122,0,6,2121,1560,24,1069770984,12,205,'',0),(5169,259,2121,0,5,2120,2122,24,1069770984,12,205,'',0),(5168,259,2120,0,4,2119,2121,24,1069770984,12,205,'',0),(5167,259,2119,0,3,1535,2120,24,1069770984,12,204,'',0),(5166,259,1535,0,2,1537,2119,24,1069770984,12,204,'',0),(5165,259,1537,0,1,2118,1535,24,1069770984,12,204,'',0),(5164,259,2118,0,0,0,1537,24,1069770984,12,204,'',0),(5127,161,2104,0,19,2103,0,10,1068047603,1,141,'',0),(5126,161,2103,0,18,2102,2104,10,1068047603,1,141,'',0),(5125,161,2102,0,17,2101,2103,10,1068047603,1,141,'',0),(5124,161,2101,0,16,2100,2102,10,1068047603,1,141,'',0),(5123,161,2100,0,15,2099,2101,10,1068047603,1,141,'',0),(5122,161,2099,0,14,2098,2100,10,1068047603,1,141,'',0),(5121,161,2098,0,13,2063,2099,10,1068047603,1,141,'',0),(5120,161,2063,0,12,2098,2098,10,1068047603,1,141,'',0),(5119,161,2098,0,11,1578,2063,10,1068047603,1,141,'',0),(5118,161,1578,0,10,2097,2098,10,1068047603,1,141,'',0),(5117,161,2097,0,9,1578,1578,10,1068047603,1,141,'',0),(5116,161,1578,0,8,2096,2097,10,1068047603,1,141,'',0),(5115,161,2096,0,7,1578,1578,10,1068047603,1,141,'',0),(5114,161,1578,0,6,1381,2096,10,1068047603,1,141,'',0),(5113,161,1381,0,5,1725,1578,10,1068047603,1,141,'',0),(5112,161,1725,0,4,2066,1381,10,1068047603,1,141,'',0),(5111,161,2066,0,3,2095,1725,10,1068047603,1,141,'',0),(5110,161,2095,0,2,1381,2066,10,1068047603,1,141,'',0),(5108,161,934,0,0,0,1381,10,1068047603,1,140,'',0),(5109,161,1381,0,1,934,2095,10,1068047603,1,140,'',0),(3804,213,1543,0,17,1550,1551,1,1068711091,13,119,'',0),(3803,213,1550,0,16,1549,1543,1,1068711091,13,119,'',0),(3802,213,1549,0,15,1548,1550,1,1068711091,13,119,'',0),(3801,213,1548,0,14,1547,1549,1,1068711091,13,119,'',0),(3800,213,1547,0,13,1490,1548,1,1068711091,13,119,'',0),(3799,213,1490,0,12,1546,1547,1,1068711091,13,119,'',0),(3798,213,1546,0,11,1545,1490,1,1068711091,13,119,'',0),(3817,213,1559,0,30,1558,0,1,1068711091,13,119,'',0),(3816,213,1558,0,29,1557,1559,1,1068711091,13,119,'',0),(3815,213,1557,0,28,1519,1558,1,1068711091,13,119,'',0),(3814,213,1519,0,27,1388,1557,1,1068711091,13,119,'',0),(3813,213,1388,0,26,1556,1519,1,1068711091,13,119,'',0),(3796,213,1544,0,9,1543,1545,1,1068711091,13,119,'',0),(3797,213,1545,0,10,1544,1546,1,1068711091,13,119,'',0),(3066,14,1361,0,3,1360,1316,4,1033920830,2,198,'',0),(3065,14,1360,0,2,1359,1361,4,1033920830,2,197,'',0),(3064,14,1359,0,1,1358,1360,4,1033920830,2,9,'',0),(3063,14,1358,0,0,0,1359,4,1033920830,2,8,'',0),(3812,213,1556,0,25,1535,1388,1,1068711091,13,119,'',0),(3811,213,1535,0,24,1555,1556,1,1068711091,13,119,'',0),(3810,213,1555,0,23,1554,1535,1,1068711091,13,119,'',0),(3809,213,1554,0,22,1553,1555,1,1068711091,13,119,'',0),(3808,213,1553,0,21,1552,1554,1,1068711091,13,119,'',0),(3807,213,1552,0,20,1490,1553,1,1068711091,13,119,'',0),(3806,213,1490,0,19,1551,1552,1,1068711091,13,119,'',0),(3805,213,1551,0,18,1543,1490,1,1068711091,13,119,'',0),(3111,1,1380,0,0,0,0,1,1033917596,1,4,'',0),(3795,213,1543,0,8,1542,1544,1,1068711091,13,119,'',0),(3794,213,1542,0,7,1535,1543,1,1068711091,13,119,'',0),(3793,213,1535,0,6,33,1542,1,1068711091,13,119,'',0),(3792,213,33,0,5,1541,1535,1,1068711091,13,119,'',0),(3791,213,1541,0,4,1540,33,1,1068711091,13,119,'',0),(3790,213,1540,0,3,1539,1541,1,1068711091,13,119,'',0),(3789,213,1539,0,2,1538,1540,1,1068711091,13,119,'',0),(3788,213,1538,0,1,1538,1539,1,1068711091,13,119,'',0),(3786,212,1381,0,7,1537,0,1,1068711069,13,119,'',0),(3785,212,1537,0,6,1536,1381,1,1068711069,13,119,'',0),(3780,212,89,0,1,1532,1533,1,1068711069,13,119,'',0),(5107,254,2040,0,33,2094,0,23,1069770356,13,213,'',1),(5106,254,2094,0,32,2085,2040,23,1069770356,13,203,'',0),(5105,254,2085,0,31,2031,2094,23,1069770356,13,203,'',0),(5104,254,2031,0,30,2063,2085,23,1069770356,13,203,'',0),(5103,254,2063,0,29,1388,2031,23,1069770356,13,203,'',0),(5102,254,1388,0,28,2093,2063,23,1069770356,13,203,'',0),(5101,254,2093,0,27,1578,1388,23,1069770356,13,203,'',0),(5100,254,1578,0,26,1559,2093,23,1069770356,13,203,'',0),(5099,254,1559,0,25,1535,1578,23,1069770356,13,203,'',0),(5098,254,1535,0,24,2092,1559,23,1069770356,13,203,'',0),(5097,254,2092,0,23,2091,1535,23,1069770356,13,203,'',0),(5096,254,2091,0,22,1542,2092,23,1069770356,13,203,'',0),(5095,254,1542,0,21,2021,2091,23,1069770356,13,203,'',0),(5094,254,2021,0,20,1537,1542,23,1069770356,13,203,'',0),(5093,254,1537,0,19,2090,2021,23,1069770356,13,203,'',0),(5092,254,2090,0,18,2089,1537,23,1069770356,13,203,'',0),(5091,254,2089,0,17,1535,2090,23,1069770356,13,203,'',0),(5090,254,1535,0,16,2020,2089,23,1069770356,13,203,'',0),(5089,254,2020,0,15,2082,1535,23,1069770356,13,203,'',0),(5088,254,2082,0,14,1490,2020,23,1069770356,13,203,'',0),(5087,254,1490,0,13,2088,2082,23,1069770356,13,203,'',0),(5086,254,2088,0,12,2021,1490,23,1069770356,13,203,'',0),(5085,254,2021,0,11,2087,2088,23,1069770356,13,203,'',0),(5084,254,2087,0,10,2055,2021,23,1069770356,13,203,'',0),(5083,254,2055,0,9,2086,2087,23,1069770356,13,203,'',0),(5082,254,2086,0,8,2085,2055,23,1069770356,13,203,'',0),(5081,254,2085,0,7,89,2086,23,1069770356,13,203,'',0),(5080,254,89,0,6,2084,2085,23,1069770356,13,203,'',0),(5079,254,2084,0,5,2083,89,23,1069770356,13,203,'',0),(5078,254,2083,0,4,1655,2084,23,1069770356,13,203,'',0),(5077,254,1655,0,3,2020,2083,23,1069770356,13,202,'',0),(5076,254,2020,0,2,1490,1655,23,1069770356,13,202,'',0),(5075,254,1490,0,1,2082,2020,23,1069770356,13,202,'',0),(5074,254,2082,0,0,0,1490,23,1069770356,13,202,'',0),(5073,253,2067,0,31,2081,0,23,1069770254,13,213,'',0),(5072,253,2081,0,30,1692,2067,23,1069770254,13,203,'',0),(5071,253,1692,0,29,2080,2081,23,1069770254,13,203,'',0),(5070,253,2080,0,28,1381,1692,23,1069770254,13,203,'',0),(5069,253,1381,0,27,2079,2080,23,1069770254,13,203,'',0),(5068,253,2079,0,26,2061,1381,23,1069770254,13,203,'',0),(5067,253,2061,0,25,2078,2079,23,1069770254,13,203,'',0),(5066,253,2078,0,24,2077,2061,23,1069770254,13,203,'',0),(5065,253,2077,0,23,89,2078,23,1069770254,13,203,'',0),(5064,253,89,0,22,2076,2077,23,1069770254,13,203,'',0),(5063,253,2076,0,21,2045,89,23,1069770254,13,203,'',0),(5062,253,2045,0,20,2044,2076,23,1069770254,13,203,'',0),(5162,258,1535,0,10,1560,2117,24,1069770910,12,205,'',0),(5161,258,1560,0,9,2116,1535,24,1069770910,12,205,'',0),(4880,228,1723,0,9,1535,0,1,1068718629,12,119,'',0),(5154,258,1726,0,2,1727,1728,24,1069770910,12,205,'',0),(5153,258,1727,0,1,1535,1726,24,1069770910,12,204,'',0),(5152,258,1535,0,0,0,1727,24,1069770910,12,204,'',0),(5151,257,1728,0,0,0,0,1,1069770849,12,4,'',0),(5150,256,2113,0,8,1411,0,24,1069770809,12,205,'',0),(5149,256,1411,0,7,2110,2113,24,1069770809,12,205,'',0),(5148,256,2110,0,6,2107,1411,24,1069770809,12,205,'',0),(5147,256,2107,0,5,2114,2110,24,1069770809,12,205,'',0),(5133,255,2107,0,0,0,2108,24,1069770691,12,204,'',0),(5132,231,2106,0,4,1678,0,1,1068718746,12,119,'',0),(5131,231,1678,0,3,1560,2106,1,1068718746,12,119,'',0),(5130,231,1560,0,2,11,1678,1,1068718746,12,119,'',0),(5128,231,2105,0,0,0,11,1,1068718746,12,4,'',0),(5129,231,11,0,1,2105,1560,1,1068718746,12,119,'',0),(5139,255,25,0,6,2110,2111,24,1069770691,12,205,'',0),(5138,255,2110,0,5,2107,25,24,1069770691,12,205,'',0),(5137,255,2107,0,4,1537,2110,24,1069770691,12,205,'',0),(5136,255,1537,0,3,2109,2107,24,1069770691,12,205,'',0),(5135,255,2109,0,2,2108,1537,24,1069770691,12,205,'',0),(5134,255,2108,0,1,2107,2109,24,1069770691,12,204,'',0),(5146,256,2114,0,4,2113,2107,24,1069770809,12,205,'',0),(5145,256,2113,0,3,1411,2114,24,1069770809,12,204,'',0),(5144,256,1411,0,2,2110,2113,24,1069770809,12,204,'',0),(5143,256,2110,0,1,2107,1411,24,1069770809,12,204,'',0),(5142,256,2107,0,0,0,2110,24,1069770809,12,204,'',0),(5140,255,2111,0,7,25,2112,24,1069770691,12,205,'',0),(5141,255,2112,0,8,2111,0,24,1069770691,12,205,'',0),(5160,258,2116,0,8,1535,1560,24,1069770910,12,205,'',0),(5159,258,1535,0,7,1725,2116,24,1069770910,12,205,'',0),(5158,258,1725,0,6,2066,1535,24,1069770910,12,205,'',0),(5157,258,2066,0,5,2115,1725,24,1069770910,12,205,'',0),(5155,258,1728,0,3,1726,2115,24,1069770910,12,205,'',0),(5156,258,2115,0,4,1728,2066,24,1069770910,12,205,'',0),(4889,240,1957,0,0,0,0,1,1068719643,1,4,'',0),(5163,258,2117,0,11,1535,0,24,1069770910,12,205,'',0),(4443,241,1543,0,0,0,1558,25,1068720802,1,207,'',0),(4444,241,1558,0,1,1543,1725,25,1068720802,1,207,'',0),(4445,241,1725,0,2,1558,1535,25,1068720802,1,207,'',0),(4446,241,1535,0,3,1725,1726,25,1068720802,1,207,'',0),(4447,241,1726,0,4,1535,1537,25,1068720802,1,207,'',0),(4448,241,1537,0,5,1726,1727,25,1068720802,1,207,'',0),(4449,241,1727,0,6,1537,1728,25,1068720802,1,207,'',0),(4450,241,1728,0,7,1727,0,25,1068720802,1,207,'',0),(4465,243,1738,0,0,0,1094,1,1068727871,13,4,'',0),(4466,243,1094,0,1,1738,1739,1,1068727871,13,119,'',0),(4467,243,1739,0,2,1094,1740,1,1068727871,13,119,'',0),(4468,243,1740,0,3,1739,1741,1,1068727871,13,119,'',0),(4469,243,1741,0,4,1740,1742,1,1068727871,13,119,'',0),(4470,243,1742,0,5,1741,1743,1,1068727871,13,119,'',0),(4471,243,1743,0,6,1742,33,1,1068727871,13,119,'',0),(4472,243,33,0,7,1743,1572,1,1068727871,13,119,'',0),(4473,243,1572,0,8,33,1609,1,1068727871,13,119,'',0),(4474,243,1609,0,9,1572,1744,1,1068727871,13,119,'',0),(4475,243,1744,0,10,1609,1745,1,1068727871,13,119,'',0),(4476,243,1745,0,11,1744,33,1,1068727871,13,119,'',0),(4477,243,33,0,12,1745,1746,1,1068727871,13,119,'',0),(4478,243,1746,0,13,33,1747,1,1068727871,13,119,'',0),(4479,243,1747,0,14,1746,1655,1,1068727871,13,119,'',0),(4480,243,1655,0,15,1747,1562,1,1068727871,13,119,'',0),(4481,243,1562,0,16,1655,1748,1,1068727871,13,119,'',0),(4482,243,1748,0,17,1562,1749,1,1068727871,13,119,'',0),(4483,243,1749,0,18,1748,1655,1,1068727871,13,119,'',0),(4484,243,1655,0,19,1749,1750,1,1068727871,13,119,'',0),(4485,243,1750,0,20,1655,1751,1,1068727871,13,119,'',0),(4486,243,1751,0,21,1750,1692,1,1068727871,13,119,'',0),(4487,243,1692,0,22,1751,0,1,1068727871,13,119,'',0),(4879,228,1535,0,8,33,1723,1,1068718629,12,119,'',0),(4878,228,33,0,7,1949,1535,1,1068718629,12,119,'',0),(4877,228,1949,0,6,1678,33,1,1068718629,12,119,'',0),(4876,228,1678,0,5,1948,1949,1,1068718629,12,119,'',0),(4875,228,1948,0,4,1947,1678,1,1068718629,12,119,'',0),(4874,228,1947,0,3,1946,1948,1,1068718629,12,119,'',0),(4873,228,1946,0,2,1945,1947,1,1068718629,12,119,'',0),(4872,228,1945,0,1,11,1946,1,1068718629,12,119,'',0),(4871,228,11,0,0,0,1945,1,1068718629,12,4,'',0),(4885,49,33,0,4,1953,1954,1,1066398020,13,119,'',0),(4884,49,1953,0,3,1952,33,1,1066398020,13,119,'',0),(4883,49,1952,0,2,1951,1953,1,1066398020,13,119,'',0),(4882,49,1951,0,1,1950,1952,1,1066398020,13,119,'',0),(4881,49,1950,0,0,0,1951,1,1066398020,13,4,'',0),(5270,56,2162,0,0,0,0,15,1066643397,11,161,'',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsearch_object_word_link ENABLE KEYS */;

--
-- Table structure for table 'ezsearch_return_count'
--

DROP TABLE IF EXISTS ezsearch_return_count;
CREATE TABLE ezsearch_return_count (
  id int(11) NOT NULL auto_increment,
  phrase_id int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsearch_return_count'
--

/*!40000 ALTER TABLE ezsearch_return_count DISABLE KEYS */;
LOCK TABLES ezsearch_return_count WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsearch_return_count ENABLE KEYS */;

--
-- Table structure for table 'ezsearch_search_phrase'
--

DROP TABLE IF EXISTS ezsearch_search_phrase;
CREATE TABLE ezsearch_search_phrase (
  id int(11) NOT NULL auto_increment,
  phrase varchar(250) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsearch_search_phrase'
--

/*!40000 ALTER TABLE ezsearch_search_phrase DISABLE KEYS */;
LOCK TABLES ezsearch_search_phrase WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsearch_search_phrase ENABLE KEYS */;

--
-- Table structure for table 'ezsearch_word'
--

DROP TABLE IF EXISTS ezsearch_word;
CREATE TABLE ezsearch_word (
  id int(11) NOT NULL auto_increment,
  word varchar(150) default NULL,
  object_count int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_word (word)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsearch_word'
--

/*!40000 ALTER TABLE ezsearch_word DISABLE KEYS */;
LOCK TABLES ezsearch_word WRITE;
INSERT INTO ezsearch_word VALUES (6,'media',1),(7,'setup',3),(1906,'grouplist',1),(1905,'class',1),(1904,'classes',1),(11,'links',3),(25,'content',3),(34,'feel',2),(33,'and',9),(32,'look',2),(2030,'charm',1),(2029,'lot',1),(1519,'more',2),(934,'about',2),(1534,'into',1),(2028,'has',1),(2026,'beetle',1),(2027,'1982',1),(89,'a',6),(1535,'the',16),(1536,'life',1),(1094,'music',1),(1552,'often',1),(1544,'connects',1),(1559,'way',2),(1558,'one',2),(1557,'than',1),(1556,'price',1),(1555,'paying',1),(1911,'urltranslator',1),(1540,'electronic',1),(1539,'handhelds',1),(1541,'gadgets',1),(1543,'which',3),(1903,'cache',1),(1542,'software',2),(1537,'of',9),(1490,'i',8),(2025,'volkswagen',1),(1538,'computers',1),(1532,'personal',1),(1380,'blog',1),(1362,'developer',1),(1316,'norway',1),(1361,'skien',1),(1360,'uberguru',1),(1359,'user',1),(1533,'glimpse',1),(1554,'up',2),(2160,'address',1),(2159,'come',1),(2158,'helene',1),(2157,'us',1),(2156,'rest',1),(2155,'share',1),(2154,'when',1),(2153,'tim',1),(2152,'only',1),(2151,'norwegian',1),(2150,'but',1),(2149,'languages',1),(2148,'several',1),(1411,'at',3),(2147,'available',1),(2146,'he',1),(2145,'fanatic',1),(2144,'football',1),(2143,'discovered',1),(2142,'pondus',1),(2141,'there',1),(2140,'also',1),(2139,'dreams',1),(2138,'told',1),(2137,'weekend',1),(2136,'house',1),(2135,'friends',1),(2134,'invited',1),(2133,'party',1),(2132,'or',1),(2131,'you',3),(2130,'do',1),(2129,'team',1),(1388,'in',4),(2128,'fc',1),(2127,'liverpool',1),(2126,'sports',1),(2125,'happen',1),(2124,'know',1),(2123,'number',1),(2122,'wait',1),(2121,'t',1),(1381,'me',4),(2024,'old',1),(2023,'car',1),(1908,'56',1),(1907,'edit',1),(1910,'translator',1),(1909,'url',1),(1553,'end',1),(2022,'new',1),(2021,'my',5),(2019,'today',1),(2020,'got',2),(1551,'means',1),(1547,'m',1),(1954,'brainstorms',1),(1550,'adopter',1),(1549,'early',1),(1548,'an',2),(1358,'administrator',1),(1546,'all',1),(1545,'them',1),(2120,'can',2),(2119,'rings',2),(2118,'lord',2),(2117,'movie',1),(2114,'visit',1),(2113,'freshmeat',1),(2104,'addicted',1),(2103,'girl',2),(2102,'totally',1),(2101,'boy',1),(2100,'pimples',1),(2099,'enough',1),(2098,'smart',1),(2097,'young',1),(2096,'short',1),(2095,'anyway',1),(1560,'for',4),(1562,'s',2),(1572,'many',1),(1578,'to',5),(2094,'anymore',1),(2093,'fortune',1),(2092,'perhaps',1),(2091,'working',1),(2090,'edition',1),(2089,'latest',1),(2088,'hair',1),(2087,'pulling',1),(2086,'time',1),(1609,'other',1),(2085,'long',1),(2084,'such',1),(2083,'after',1),(2082,'finally',1),(2081,'home',1),(2080,'follow',1),(2079,'let',1),(2078,'since',1),(2077,'success',1),(2076,'been',1),(2075,'romantic',1),(2074,'see',1),(2073,'went',1),(2072,'we',1),(2071,'mia',1),(2070,'date',1),(2069,'first',1),(2068,'tonight',1),(2067,'0',2),(2066,'this',5),(2065,'happy',1),(2064,'very',1),(2063,'not',3),(2062,'was',3),(2061,'she',2),(2060,'girlfriend',1),(2059,'be',2),(2058,'hopefully',1),(2057,'will',4),(2056,'what',3),(2055,'with',4),(2054,'meeting',1),(2053,'missed',1),(2052,'late',1),(2051,'hours',1),(2050,'three',2),(2049,'woke',1),(2048,'sleep',1),(2047,'alarm',1),(2046,'turned',1),(2045,'have',3),(2044,'must',2),(1655,'it',5),(2043,'somehow',1),(2042,'again',1),(2041,'overslept',1),(2040,'1',3),(2039,'wheels',1),(2108,'systems',1),(2038,'original',1),(2037,'even',2),(2036,'£30',1),(2035,'friend',1),(2034,'bought',1),(2033,'money',1),(2112,'system',1),(2032,'cost',1),(2031,'that',3),(1723,'like',2),(2111,'management',1),(2110,'publish',2),(1678,'news',2),(2105,'downloads',1),(2107,'ez',2),(2106,'site',2),(1691,'from',1),(1692,'here',2),(2109,'creators',1),(2116,'homepage',1),(2115,'ever',3),(1957,'polls',1),(1725,'is',5),(1726,'best',4),(1727,'matrix',4),(1728,'movies',4),(1738,'entertainment',1),(1739,'books',1),(1740,'film',1),(1741,'television',1),(1742,'shopping',1),(1743,'travel',1),(1744,'fine',1),(1745,'escapes',1),(1746,'vices',1),(1747,'if',2),(1748,'downright',1),(1749,'fun',2),(1750,'probably',1),(1751,'belongs',1),(1948,'journals',1),(1947,'weblogs',1),(1946,'websites',1),(1953,'concepts',1),(1952,'thoughts',1),(1951,'parenthetical',1),(1945,'worthwhile',1),(1950,'blogs',1),(1949,'pubs',1),(2162,'blog_package',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsearch_word ENABLE KEYS */;

--
-- Table structure for table 'ezsection'
--

DROP TABLE IF EXISTS ezsection;
CREATE TABLE ezsection (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  locale varchar(255) default NULL,
  navigation_part_identifier varchar(100) default 'ezcontentnavigationpart',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsection'
--

/*!40000 ALTER TABLE ezsection DISABLE KEYS */;
LOCK TABLES ezsection WRITE;
INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart'),(2,'Users','','ezusernavigationpart'),(3,'Media','','ezmedianavigationpart'),(4,'News','','ezcontentnavigationpart'),(5,'Contact','','ezcontentnavigationpart'),(6,'Files','','ezcontentnavigationpart'),(11,'Set up object','','ezsetupnavigationpart'),(12,'Links','','ezcontentnavigationpart'),(13,'Blogs','','ezcontentnavigationpart');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsection ENABLE KEYS */;

--
-- Table structure for table 'ezsession'
--

DROP TABLE IF EXISTS ezsession;
CREATE TABLE ezsession (
  session_key varchar(32) NOT NULL default '',
  expiration_time int(11) unsigned NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (session_key),
  KEY expiration_time (expiration_time)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsession'
--

/*!40000 ALTER TABLE ezsession DISABLE KEYS */;
LOCK TABLES ezsession WRITE;
INSERT INTO ezsession VALUES ('4838c02be7388f035b70ffed64eab28f',1070103039,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069843822;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069843822;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069843822;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:5:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}i:4;a:5:{s:2:\"id\";s:3:\"391\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:11:\"versionread\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069843822;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"306\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:306;a:9:{i:0;a:3:{s:2:\"id\";s:3:\"625\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"626\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"627\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"628\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"629\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"630\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"631\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"632\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"26\";}i:8;a:3:{s:2:\"id\";s:3:\"633\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"5\";}}}');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsession ENABLE KEYS */;

--
-- Table structure for table 'ezsite_data'
--

DROP TABLE IF EXISTS ezsite_data;
CREATE TABLE ezsite_data (
  name varchar(60) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY  (name)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsite_data'
--

/*!40000 ALTER TABLE ezsite_data DISABLE KEYS */;
LOCK TABLES ezsite_data WRITE;
INSERT INTO ezsite_data VALUES ('ezpublish-version','3.3.0'),('ezpublish-release','1');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsite_data ENABLE KEYS */;

--
-- Table structure for table 'ezsubtree_notification_rule'
--

DROP TABLE IF EXISTS ezsubtree_notification_rule;
CREATE TABLE ezsubtree_notification_rule (
  id int(11) NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  use_digest int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsubtree_notification_rule'
--

/*!40000 ALTER TABLE ezsubtree_notification_rule DISABLE KEYS */;
LOCK TABLES ezsubtree_notification_rule WRITE;
INSERT INTO ezsubtree_notification_rule VALUES (1,'nospam@ez.no',0,112),(2,'wy@ez.no',0,112),(3,'nospam@ez.no',0,123),(4,'bf@ez.no',0,124),(5,'bf@ez.no',0,135),(6,'wy@ez.no',0,114);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsubtree_notification_rule ENABLE KEYS */;

--
-- Table structure for table 'eztipafriend_counter'
--

DROP TABLE IF EXISTS eztipafriend_counter;
CREATE TABLE eztipafriend_counter (
  node_id int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'eztipafriend_counter'
--

/*!40000 ALTER TABLE eztipafriend_counter DISABLE KEYS */;
LOCK TABLES eztipafriend_counter WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE eztipafriend_counter ENABLE KEYS */;

--
-- Table structure for table 'eztrigger'
--

DROP TABLE IF EXISTS eztrigger;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'eztrigger'
--

/*!40000 ALTER TABLE eztrigger DISABLE KEYS */;
LOCK TABLES eztrigger WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE eztrigger ENABLE KEYS */;

--
-- Table structure for table 'ezurl'
--

DROP TABLE IF EXISTS ezurl;
CREATE TABLE ezurl (
  id int(11) NOT NULL auto_increment,
  url varchar(255) default NULL,
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  is_valid int(11) NOT NULL default '1',
  last_checked int(11) NOT NULL default '0',
  original_url_md5 varchar(32) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezurl'
--

/*!40000 ALTER TABLE ezurl DISABLE KEYS */;
LOCK TABLES ezurl WRITE;
INSERT INTO ezurl VALUES (1,'http://ez.no',1068713677,1068713677,1,0,'dfcdb471b240d964dc3f57b998eb0533'),(2,'http://www.vg.no',1068718860,1068718860,1,0,'26f1033e463720ae68742157890bc752'),(3,'http://www.sina.com.cn',1068718957,1068718957,1,0,'4f12a25ee6cc3d6123be77df850e343e'),(4,'http://download.hzinfo.com',1068719250,1068719250,1,0,'4c9c884a40d63b7d9555ffb77fe75466'),(5,'http://vg.no',1069407573,1069407573,1,0,'5d85f44aadfc8b160572da41691d0162'),(6,'http://freshmeat.net/projects/ezpublish/?topic_id=92%2C243%2C234%2C87%2C96',1069770809,1069770809,1,0,'d85ef15ed11d5ac7c04896ad03afb12d'),(7,'http://whatisthematrix.warnerbros.com/',1069770910,1069770910,1,0,'12a254b2a3a7390dbc7fbc4db47b62ac'),(8,'http://www.lordoftherings.net/',1069770983,1069770983,1,0,'3132ce6558475654ae838771140f480c'),(9,'http://liverpoolfc.tv/',1069771089,1069771089,1,0,'967039f689cc4043aadbd2525f6d4e17'),(10,'http://www.start.no/pondus/',1069773629,1069773629,1,0,'1403d356d0dd0fd838927ddb32cc06e1');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezurl ENABLE KEYS */;

--
-- Table structure for table 'ezurl_object_link'
--

DROP TABLE IF EXISTS ezurl_object_link;
CREATE TABLE ezurl_object_link (
  url_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  PRIMARY KEY  (url_id,contentobject_attribute_id,contentobject_attribute_version)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezurl_object_link'
--

/*!40000 ALTER TABLE ezurl_object_link DISABLE KEYS */;
LOCK TABLES ezurl_object_link WRITE;
INSERT INTO ezurl_object_link VALUES (1,768,1),(1,991,1),(2,800,1),(3,807,1),(4,814,1),(5,945,57),(6,994,1),(7,1002,1),(8,1005,1),(9,1013,1),(10,1026,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezurl_object_link ENABLE KEYS */;

--
-- Table structure for table 'ezurlalias'
--

DROP TABLE IF EXISTS ezurlalias;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezurlalias'
--

/*!40000 ALTER TABLE ezurlalias DISABLE KEYS */;
LOCK TABLES ezurlalias WRITE;
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,154,0),(125,'discussions/forum_main_group/music_discussion/latest_msg_not_sticky','70cf693961dcdd67766bf941c3ed2202','content/view/full/130',1,0,0),(126,'discussions/forum_main_group/music_discussion/not_sticky_2','969f470c93e2131a0884648b91691d0b','content/view/full/131',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,189,0),(124,'discussions/forum_main_group/music_discussion/new_topic_sticky/reply','f3dd8b6512a0b04b426ef7d7307b7229','content/view/full/129',1,0,0),(157,'blogs/computers','2f8fda683b5e2473a80187cbce012bb8','content/view/full/154',1,0,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,153,0),(123,'discussions/forum_main_group/music_discussion/new_topic_sticky','bf37b4a370ddb3935d0625a5b348dd20','content/view/full/128',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,189,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,189,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(127,'discussions/forum_main_group/music_discussion/important_sticky','2f16cf3039c97025a43f23182b4b6d60','content/view/full/132',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0),(155,'news/*','5319b79408bf223063ba67c14ad03ee0','blogs/{1}',1,0,1),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(104,'discussions/music_discussion','09533dfccc8477debe545d31bccf391f','content/view/full/114',1,149,0),(105,'discussions/forum_main_group/music_discussion/this_is_a_new_topic','cec6b1593bf03079990a89a3fdc60c56','content/view/full/112',1,0,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(107,'discussions/sports_discussion','c551943f4df3c58a693f8ba55e9b6aeb','content/view/full/115',1,151,0),(117,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/foo_bar','741cdf9f1ee1fa974ea7ec755f538271','content/view/full/122',1,0,0),(111,'discussions/forum_main_group/sports_discussion/football','6e9c09d390322aa44bb5108b93f5f17c','content/view/full/119',1,0,0),(154,'blogs','51704a6cacf71c8d5211445d9e80515f','content/view/full/50',1,0,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,189,0),(114,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/my_reply','1e03a7609698aa8a98dccf1178df0e6f','content/view/full/120',1,0,0),(118,'discussions/forum_main_group/music_discussion/what_about_pop','c4ebc99b2ed9792d1aee0e5fe210b852','content/view/full/123',1,0,0),(119,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic','6c20d2df5a828dcdb6a4fcb4897bb643','content/view/full/124',1,0,0),(120,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','de98a1bb645ea84919a5e34688ff84e2','content/view/full/125',1,0,0),(128,'discussions/forum_main_group/sports_discussion/football/reply_2','13a443b7e046bb36831640f1d19e33d9','content/view/full/133',1,0,0),(130,'discussions/forum_main_group/music_discussion/lkj_ssssstick','75ee87c770e4e8be9d44200cdb71d071','content/view/full/135',1,0,0),(131,'discussions/forum_main_group/music_discussion/foo','12c58f35c1114deeb172aba728c50ca8','content/view/full/136',1,0,0),(132,'discussions/forum_main_group/music_discussion/lkj_ssssstick/reply','6040856b4ec5bcc1c699d95020005be5','content/view/full/137',1,0,0),(135,'discussions/forum_main_group/music_discussion/lkj_ssssstick/uyuiyui','4c48104ea6e5ec2a78067374d9561fcb','content/view/full/140',1,0,0),(136,'discussions/forum_main_group/music_discussion/test2','53f71d4ff69ffb3bf8c8ccfb525eabd3','content/view/full/141',1,0,0),(137,'discussions/forum_main_group/music_discussion/t4','5da27cda0fbcd5290338b7d22cfd730c','content/view/full/142',1,0,0),(138,'discussions/forum_main_group/music_discussion/lkj_ssssstick/klj_jkl_klj','9ae60fa076882d6807506c2232143d27','content/view/full/143',1,0,0),(139,'discussions/forum_main_group/music_discussion/test2/retest2','a17d07fbbd2d1b6d0fbbf8ca1509cd01','content/view/full/144',1,0,0),(141,'discussions/forum_main_group/music_discussion/lkj_ssssstick/my_reply','1f95000d1f993ffa16a0cf83b78515bf','content/view/full/146',1,0,0),(142,'discussions/forum_main_group/music_discussion/lkj_ssssstick/retest','0686f14064a420e6ee95aabf89c4a4f2','content/view/full/147',1,0,0),(144,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf','21f0ee2122dd5264192adc15c1e69c03','content/view/full/149',1,0,0),(156,'blogs/personal','10a5d8f539ef0a468722f8327f7950ab','content/view/full/153',1,0,0),(146,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/dfghd_fghklj','460d30ba47855079ac8605e1c8085993','content/view/full/151',1,0,0),(158,'blogs/personal/today_i_got_my_new_car','ce9118c9b6c16328082445f6d8098a0d','content/view/full/155',1,0,0),(149,'discussions/forum_main_group/music_discussion','a1a79985f113d5b05b22c9686b46b175','content/view/full/114',1,0,0),(150,'discussions/music_discussion/*','2ec2a3bfcf01ad3f1323390ab26dfeac','discussions/forum_main_group/music_discussion/{1}',1,0,1),(151,'discussions/forum_main_group/sports_discussion','b68c5a82b8b2035eeee5788cb223bb7e','content/view/full/115',1,0,0),(152,'discussions/sports_discussion/*','7acbf48218ca6e1d80c267911860d34f','discussions/forum_main_group/sports_discussion/{1}',1,0,1),(153,'about_me','50793f253d2dc015e93a2f75163b0894','content/view/full/127',1,0,0),(190,'blogs/personal/i_overslept_again','61ab4dd49514d2031be3362827465fe4','content/view/full/183',1,0,0),(203,'links/fun','05aaf2bb0d72213e94a358284f184b79','content/view/full/195',1,0,0),(164,'links','807765384d9d5527da8848df14a4f02f','content/view/full/161',1,0,0),(196,'links/movies','bea720c2faeb616182aa095cb8a0e492','content/view/full/188',1,0,0),(197,'links/movies/the_matrix','14290cfd46a612082d7c56dffa1b1fff','content/view/full/189',1,0,0),(167,'links/news','61e2cd3056056408f1b41435a3f953c3','content/view/full/164',1,193,0),(194,'links/downloads/ez_systems','8fb0de95d1934b0fa54410c6fa9bb04f','content/view/full/186',1,0,0),(193,'links/downloads','1d3503542a171d9dfab9c7d3caec48e0','content/view/full/164',1,0,0),(195,'links/downloads/ez_publish_at_freshmeat','c26b6a315b8894db61eb9faff400400f','content/view/full/187',1,0,0),(201,'polls/which_do_you_like_the_best_matrix_or_lord_of_the_rings','25f0f61fbc5d84e469f858722040aa4c','content/view/full/193',1,0,0),(198,'links/movies/lord_of_the_rings','10e58e9e8a34b59f0a3feeaa77ab2fea','content/view/full/190',1,0,0),(199,'links/sports','414e65e1c520e395d334856f3dc2ea3e','content/view/full/191',1,0,0),(200,'links/sports/liverpool_fc','c7c457cae15412fdcc8326da47c4bb15','content/view/full/192',1,0,0),(176,'polls','952e8cf2c863b8ddc656bac6ad0b729b','content/view/full/173',1,0,0),(202,'blogs/personal/party','69c642bf0ff392505a114bb6288b0a04','content/view/full/194',1,0,0),(178,'polls/which_one_is_the_best_of_matrix_movies','bb0ff8ca87eb02ff2219a32c5c73b7f4','content/view/full/174',1,0,0),(180,'blogs/entertainment','9dd2cf029c6cfcddc067d936dc750b3d','content/view/full/176',1,0,0),(191,'blogs/entertainment/tonight_i_was_at_the_movies','12f1086f208d19b6868a93df108d8af6','content/view/full/184',1,0,0),(192,'blogs/computers/finally_i_got_it','5626ad47e7065dee9ad763069e24d225','content/view/full/185',1,0,0),(204,'links/fun/pondus','54e0c99d111d8d109261fef3d61b23eb','content/view/full/196',1,0,0),(205,'blogs/computers/finally_i_got_it/tim','341022e34eb81e5cd2d6ca4a626834bf','content/view/full/197',1,0,0),(206,'blogs/personal/party/helene','4f7f9b1b19f98bf98f649253c6bb2127','content/view/full/198',1,0,0),(188,'setup/look_and_feel/blog_test','6d9593beb391a932e45ca5823e3c6359','content/view/full/54',1,189,0),(189,'setup/look_and_feel/blog','a0aa455a1c24b5d1d0448546c83836cf','content/view/full/54',1,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezurlalias ENABLE KEYS */;

--
-- Table structure for table 'ezuser'
--

DROP TABLE IF EXISTS ezuser;
CREATE TABLE ezuser (
  contentobject_id int(11) NOT NULL default '0',
  login varchar(150) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  password_hash_type int(11) NOT NULL default '1',
  password_hash varchar(50) default NULL,
  PRIMARY KEY  (contentobject_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezuser'
--

/*!40000 ALTER TABLE ezuser DISABLE KEYS */;
LOCK TABLES ezuser WRITE;
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','bf@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d'),(187,'','',1,''),(189,'','',1,'');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser ENABLE KEYS */;

--
-- Table structure for table 'ezuser_accountkey'
--

DROP TABLE IF EXISTS ezuser_accountkey;
CREATE TABLE ezuser_accountkey (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  hash_key varchar(32) NOT NULL default '',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezuser_accountkey'
--

/*!40000 ALTER TABLE ezuser_accountkey DISABLE KEYS */;
LOCK TABLES ezuser_accountkey WRITE;
INSERT INTO ezuser_accountkey VALUES (1,154,'837e17025d6b3a340cfb305769caa30d',1068042835),(2,188,'281ca20cd4d47e3f3be239f6e587df70',1068110661),(3,197,'6a92e8886841440681b58a699e69d4dc',1068112344);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser_accountkey ENABLE KEYS */;

--
-- Table structure for table 'ezuser_discountrule'
--

DROP TABLE IF EXISTS ezuser_discountrule;
CREATE TABLE ezuser_discountrule (
  id int(11) NOT NULL auto_increment,
  discountrule_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezuser_discountrule'
--

/*!40000 ALTER TABLE ezuser_discountrule DISABLE KEYS */;
LOCK TABLES ezuser_discountrule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser_discountrule ENABLE KEYS */;

--
-- Table structure for table 'ezuser_role'
--

DROP TABLE IF EXISTS ezuser_role;
CREATE TABLE ezuser_role (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  PRIMARY KEY  (id),
  KEY ezuser_role_contentobject_id (contentobject_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezuser_role'
--

/*!40000 ALTER TABLE ezuser_role DISABLE KEYS */;
LOCK TABLES ezuser_role WRITE;
INSERT INTO ezuser_role VALUES (29,1,10),(25,2,12),(28,1,11),(34,1,13);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser_role ENABLE KEYS */;

--
-- Table structure for table 'ezuser_setting'
--

DROP TABLE IF EXISTS ezuser_setting;
CREATE TABLE ezuser_setting (
  user_id int(11) NOT NULL default '0',
  is_enabled int(1) NOT NULL default '0',
  max_login int(11) default NULL,
  PRIMARY KEY  (user_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezuser_setting'
--

/*!40000 ALTER TABLE ezuser_setting DISABLE KEYS */;
LOCK TABLES ezuser_setting WRITE;
INSERT INTO ezuser_setting VALUES (10,1,1000),(14,1,10),(23,1,0),(40,1,0),(107,1,0),(108,1,0),(109,1,0),(111,1,0),(130,1,0),(149,1,0),(154,0,0),(187,1,0),(188,0,0),(189,1,0),(197,0,0),(198,1,0),(206,1,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser_setting ENABLE KEYS */;

--
-- Table structure for table 'ezvattype'
--

DROP TABLE IF EXISTS ezvattype;
CREATE TABLE ezvattype (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  percentage float default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezvattype'
--

/*!40000 ALTER TABLE ezvattype DISABLE KEYS */;
LOCK TABLES ezvattype WRITE;
INSERT INTO ezvattype VALUES (1,'Std',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezvattype ENABLE KEYS */;

--
-- Table structure for table 'ezview_counter'
--

DROP TABLE IF EXISTS ezview_counter;
CREATE TABLE ezview_counter (
  node_id int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezview_counter'
--

/*!40000 ALTER TABLE ezview_counter DISABLE KEYS */;
LOCK TABLES ezview_counter WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezview_counter ENABLE KEYS */;

--
-- Table structure for table 'ezwaituntildatevalue'
--

DROP TABLE IF EXISTS ezwaituntildatevalue;
CREATE TABLE ezwaituntildatevalue (
  id int(11) NOT NULL auto_increment,
  workflow_event_id int(11) NOT NULL default '0',
  workflow_event_version int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  contentclass_attribute_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id,workflow_event_id,workflow_event_version),
  KEY ezwaituntildateevalue_wf_ev_id_wf_ver (workflow_event_id,workflow_event_version)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezwaituntildatevalue'
--

/*!40000 ALTER TABLE ezwaituntildatevalue DISABLE KEYS */;
LOCK TABLES ezwaituntildatevalue WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezwaituntildatevalue ENABLE KEYS */;

--
-- Table structure for table 'ezwishlist'
--

DROP TABLE IF EXISTS ezwishlist;
CREATE TABLE ezwishlist (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezwishlist'
--

/*!40000 ALTER TABLE ezwishlist DISABLE KEYS */;
LOCK TABLES ezwishlist WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezwishlist ENABLE KEYS */;

--
-- Table structure for table 'ezworkflow'
--

DROP TABLE IF EXISTS ezworkflow;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezworkflow'
--

/*!40000 ALTER TABLE ezworkflow DISABLE KEYS */;
LOCK TABLES ezworkflow WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow ENABLE KEYS */;

--
-- Table structure for table 'ezworkflow_assign'
--

DROP TABLE IF EXISTS ezworkflow_assign;
CREATE TABLE ezworkflow_assign (
  id int(11) NOT NULL auto_increment,
  workflow_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  access_type int(11) NOT NULL default '0',
  as_tree int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezworkflow_assign'
--

/*!40000 ALTER TABLE ezworkflow_assign DISABLE KEYS */;
LOCK TABLES ezworkflow_assign WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_assign ENABLE KEYS */;

--
-- Table structure for table 'ezworkflow_event'
--

DROP TABLE IF EXISTS ezworkflow_event;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezworkflow_event'
--

/*!40000 ALTER TABLE ezworkflow_event DISABLE KEYS */;
LOCK TABLES ezworkflow_event WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_event ENABLE KEYS */;

--
-- Table structure for table 'ezworkflow_group'
--

DROP TABLE IF EXISTS ezworkflow_group;
CREATE TABLE ezworkflow_group (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezworkflow_group'
--

/*!40000 ALTER TABLE ezworkflow_group DISABLE KEYS */;
LOCK TABLES ezworkflow_group WRITE;
INSERT INTO ezworkflow_group VALUES (1,'Standard',14,14,1024392098,1024392098);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_group ENABLE KEYS */;

--
-- Table structure for table 'ezworkflow_group_link'
--

DROP TABLE IF EXISTS ezworkflow_group_link;
CREATE TABLE ezworkflow_group_link (
  workflow_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  workflow_version int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (workflow_id,group_id,workflow_version)
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezworkflow_group_link'
--

/*!40000 ALTER TABLE ezworkflow_group_link DISABLE KEYS */;
LOCK TABLES ezworkflow_group_link WRITE;
INSERT INTO ezworkflow_group_link VALUES (1,1,0,'Standard');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_group_link ENABLE KEYS */;

--
-- Table structure for table 'ezworkflow_process'
--

DROP TABLE IF EXISTS ezworkflow_process;
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezworkflow_process'
--

/*!40000 ALTER TABLE ezworkflow_process DISABLE KEYS */;
LOCK TABLES ezworkflow_process WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_process ENABLE KEYS */;

alter table ezrss_export add rss_version varchar(255) default null;