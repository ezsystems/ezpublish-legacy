-- MySQL dump 10.2
--
-- Host: localhost    Database: gallery
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
INSERT INTO ezcontentbrowserecent VALUES (35,111,99,1067006746,'foo bar corp'),(65,149,135,1068126974,'lkj ssssstick'),(49,10,12,1068112852,'Guest accounts'),(64,206,135,1068123651,'lkj ssssstick'),(141,14,259,1069318590,'Misc');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1066907423),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1068123024),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069414675),(12,1,'File','file','<name>',14,14,1052385472,1067353799),(27,0,'Gallery','gallery','<name>',14,14,1068803512,1069086251),(28,0,'Album','album','<name>',14,14,1068803560,1069150091),(26,0,'Comment','comment','<subject>',14,14,1068716787,1069155431),(1,1,'Folder','folder','<name>',14,14,1024392098,1068803282);
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(198,0,4,'location','Location','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(199,0,4,'signature','Signature','eztext',1,0,6,2,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(212,0,26,'comment','Comment','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',0,1),(211,0,26,'url','Homepage URL','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(221,0,28,'column','Column','ezinteger',1,1,3,0,0,4,0,0,0,0,0,'','','','','',0,1),(222,0,28,'image','Image','ezimage',0,0,4,5,0,0,0,0,0,0,0,'','','','','',0,1),(4,1,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','','',0,1),(119,1,1,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1),(210,0,26,'email','Your E-mail','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(200,0,4,'user_image','User image','ezimage',0,0,7,1,0,0,0,0,0,0,0,'','','','','',0,1),(197,0,4,'title','Title','ezstring',1,0,4,25,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(217,0,28,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(220,0,26,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(209,0,26,'name','Your name','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(214,1,1,'','new attribute3','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(218,0,28,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(223,0,27,'column','Album columns','ezinteger',0,1,3,0,0,2,0,0,0,0,0,'','','','','',0,1),(216,0,27,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(215,0,27,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(224,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'css','CSS','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'css','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1);
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
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(21,1,1,'Content'),(20,0,1,'Content'),(21,0,1,'Content'),(23,0,1,'Content'),(26,0,1,'Content'),(24,0,1,'Content'),(1,1,1,'Content'),(27,0,1,'Content'),(28,0,1,'Content');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Gallery',7,0,1033917596,1068803301,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',5,0,1033920830,1068468219,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',11,0,1066384365,1069254603,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',12,0,1066388816,1069254903,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'My gallery',58,0,1066643397,1069415051,1,''),(268,14,1,2,'Latest sdfgsdgf',1,0,1068814752,1068814752,1,''),(161,14,1,10,'About me',2,0,1068047603,1068710511,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(87,14,5,16,'New Company',1,0,0,0,2,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(165,149,1,21,'New Forum topic',1,0,0,0,2,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(107,14,2,4,'John Doe',2,0,1066916865,1066916941,1,''),(267,14,1,1,'News',1,0,1068814364,1068814364,1,''),(111,14,2,4,'vid la',1,0,1066917523,1066917523,1,''),(115,14,11,14,'Cache',6,0,1066991725,1069254540,1,''),(116,14,11,14,'URL translator',5,0,1066992054,1069254931,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(218,14,1,25,'New Poll',1,0,0,0,2,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(216,14,1,25,'New Poll',1,0,0,0,2,''),(149,14,2,4,'wenyue yu',8,0,1068041016,1068130543,1,''),(217,14,1,25,'New Poll',1,0,0,0,2,''),(168,149,0,21,'New Forum topic',1,0,0,0,2,''),(169,149,0,21,'New Forum topic',1,0,0,0,2,''),(171,149,1,21,'New Forum topic',1,0,0,0,2,''),(172,149,0,21,'New Forum topic',1,0,0,0,2,''),(173,149,0,21,'New Forum topic',1,0,0,0,2,''),(174,149,0,21,'New Forum topic',1,0,0,0,2,''),(175,149,0,21,'New Forum topic',1,0,0,0,2,''),(176,149,0,21,'New Forum topic',1,0,0,0,2,''),(177,149,0,21,'New Forum topic',1,0,0,0,2,''),(178,149,0,21,'New Forum topic',1,0,0,0,2,''),(179,149,0,21,'New Forum topic',1,0,0,0,2,''),(180,149,0,21,'New Forum topic',1,0,0,0,2,''),(181,149,0,21,'New Forum topic',1,0,0,0,2,''),(182,149,0,21,'New Forum topic',1,0,0,0,2,''),(183,149,0,21,'New Forum topic',1,0,0,0,2,''),(184,149,0,21,'New Forum topic',1,0,0,0,2,''),(185,149,0,21,'New Forum topic',1,0,0,0,2,''),(186,149,0,21,'New Forum topic',1,0,0,0,2,''),(187,14,1,4,'New User',1,0,0,0,0,''),(191,149,1,21,'New Forum topic',1,0,0,0,2,''),(189,14,1,4,'New User',1,0,0,0,0,''),(192,149,0,21,'New Forum topic',1,0,0,0,2,''),(193,149,0,21,'New Forum topic',1,0,0,0,2,''),(194,149,0,21,'New Forum topic',1,0,0,0,2,''),(200,149,1,21,'New Forum topic',1,0,0,0,2,''),(201,149,1,22,'New Forum reply',1,0,0,0,2,''),(206,14,2,4,'Bård Farstad',1,0,1068123599,1068123599,1,''),(221,14,1,25,'New Poll',1,0,0,0,2,''),(222,14,1,25,'New Poll',1,0,0,0,2,''),(224,14,1,25,'New Poll',1,0,0,0,2,''),(225,14,1,25,'New Poll',1,0,0,0,2,''),(320,14,1,28,'Flowers',3,0,1069317685,1069321701,1,''),(321,14,1,5,'Blue flower',1,0,1069317728,1069317728,1,''),(322,14,1,5,'Purple haze',1,0,1069317767,1069317767,1,''),(323,14,1,5,'Yellow flower',1,0,1069317797,1069317797,1,''),(324,14,1,28,'Landscape',2,0,1069317869,1069321720,1,''),(325,14,1,5,'Pond reflection',1,0,1069317907,1069317907,1,''),(326,14,1,5,'Ormevika skyline',1,0,1069317947,1069317947,1,''),(327,14,1,5,'Foggy trees',1,0,1069317978,1069317978,1,''),(328,14,1,5,'Water reflection',1,0,1069318020,1069318020,1,''),(329,14,1,27,'Abstract',1,0,1069318331,1069318331,1,''),(330,14,1,28,'Misc',2,0,1069318374,1069321636,1,''),(331,14,1,5,'CVS branching?',1,0,1069318446,1069318446,1,''),(332,14,1,5,'Gear wheel',1,0,1069318482,1069318482,1,''),(333,14,1,5,'Green clover',1,0,1069318517,1069318517,1,''),(334,14,1,5,'Mjaurits',1,0,1069318560,1069318560,1,''),(335,14,1,5,'Speeding',1,0,1069318590,1069318590,1,''),(299,14,1,5,'New Image',1,0,0,0,0,''),(300,14,1,5,'New Image',1,0,0,0,0,''),(319,14,1,27,'Nature',1,0,1069317649,1069317649,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Blog',0,0,0,0,'blog','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(103,'eng-GB',11,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069254602\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414615\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069687923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',11,43,152,'Classes',0,0,0,0,'classes','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',11,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"109\"\n            attribute_version=\"10\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',11,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(437,'eng-GB',54,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(676,'eng-GB',1,200,190,'',0,0,0,0,'','ezboolean'),(677,'eng-GB',1,200,194,'',0,0,0,0,'','ezsubtreesubscription'),(678,'eng-GB',1,201,191,'Re:test',0,0,0,0,'re:test','ezstring'),(679,'eng-GB',1,201,193,'fdsf',0,0,0,0,'','eztext'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(558,'eng-GB',1,171,189,'',0,0,0,0,'','eztext'),(553,'eng-GB',1,169,190,'',0,0,0,0,'','ezboolean'),(554,'eng-GB',1,169,194,'',0,0,0,0,'','ezsubtreesubscription'),(557,'eng-GB',1,171,188,'',0,0,0,0,'','ezstring'),(552,'eng-GB',1,169,189,'sfsvggs\nsfsf',0,0,0,0,'','eztext'),(547,'eng-GB',1,168,188,'',0,0,0,0,'','ezstring'),(548,'eng-GB',1,168,189,'',0,0,0,0,'','eztext'),(549,'eng-GB',1,168,190,'',0,0,0,0,'','ezboolean'),(550,'eng-GB',1,168,194,'',0,0,0,0,'','ezsubtreesubscription'),(551,'eng-GB',1,169,188,'test',0,0,0,0,'test','ezstring'),(535,'eng-GB',1,165,188,'',0,0,0,0,'','ezstring'),(536,'eng-GB',1,165,189,'',0,0,0,0,'','eztext'),(537,'eng-GB',1,165,190,'',0,0,0,0,'','ezboolean'),(538,'eng-GB',1,165,194,'',0,0,0,0,'','ezsubtreesubscription'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(152,'eng-GB',51,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.jpg\"\n         suffix=\"jpg\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-51-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-51-eng-GB/my_gallery.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"50\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-51-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-51-eng-GB/my_gallery_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-51-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-51-eng-GB/my_gallery_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-51-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-51-eng-GB/my_gallery_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069154491\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',51,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(740,'eng-GB',1,206,8,'Bård',0,0,0,0,'bård','ezstring'),(741,'eng-GB',1,206,9,'Farstad',0,0,0,0,'farstad','ezstring'),(742,'eng-GB',1,206,12,'',0,0,0,0,'','ezuser'),(743,'eng-GB',1,206,197,'music guru',0,0,0,0,'music guru','ezstring'),(744,'eng-GB',1,206,198,'Oslo/Norway',0,0,0,0,'oslo/norway','ezstring'),(745,'eng-GB',1,206,199,'sig..',0,0,0,0,'','eztext'),(746,'eng-GB',1,206,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"brd_farstad.jpg\"\n         suffix=\"jpg\"\n         basename=\"brd_farstad\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad.jpg\"\n         original_filename=\"dscn9284.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"2cv\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"brd_farstad_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"brd_farstad_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"225\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"brd_farstad_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(639,'eng-GB',1,192,189,'',0,0,0,0,'','eztext'),(640,'eng-GB',1,192,190,'',0,0,0,0,'','ezboolean'),(483,'eng-GB',2,149,12,'',0,0,0,0,'','ezuser'),(634,'eng-GB',1,191,188,'',0,0,0,0,'','ezstring'),(635,'eng-GB',1,191,189,'',0,0,0,0,'','eztext'),(636,'eng-GB',1,191,190,'',0,0,0,0,'','ezboolean'),(637,'eng-GB',1,191,194,'',0,0,0,0,'','ezsubtreesubscription'),(638,'eng-GB',1,192,188,'',0,0,0,0,'','ezstring'),(609,'eng-GB',1,184,188,'',0,0,0,0,'','ezstring'),(610,'eng-GB',1,184,189,'',0,0,0,0,'','eztext'),(611,'eng-GB',1,184,190,'',0,0,0,0,'','ezboolean'),(612,'eng-GB',1,184,194,'',0,0,0,0,'','ezsubtreesubscription'),(613,'eng-GB',1,185,188,'',0,0,0,0,'','ezstring'),(614,'eng-GB',1,185,189,'',0,0,0,0,'','eztext'),(615,'eng-GB',1,185,190,'',0,0,0,0,'','ezboolean'),(616,'eng-GB',1,185,194,'',0,0,0,0,'','ezsubtreesubscription'),(617,'eng-GB',1,186,188,'',0,0,0,0,'','ezstring'),(618,'eng-GB',1,186,189,'',0,0,0,0,'','eztext'),(619,'eng-GB',1,186,190,'',0,0,0,0,'','ezboolean'),(620,'eng-GB',1,186,194,'',0,0,0,0,'','ezsubtreesubscription'),(482,'eng-GB',2,149,9,'yu',0,0,0,0,'yu','ezstring'),(481,'eng-GB',2,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(603,'eng-GB',1,182,190,'',0,0,0,0,'','ezboolean'),(604,'eng-GB',1,182,194,'',0,0,0,0,'','ezsubtreesubscription'),(605,'eng-GB',1,183,188,'',0,0,0,0,'','ezstring'),(606,'eng-GB',1,183,189,'',0,0,0,0,'','eztext'),(607,'eng-GB',1,183,190,'',0,0,0,0,'','ezboolean'),(608,'eng-GB',1,183,194,'',0,0,0,0,'','ezsubtreesubscription'),(602,'eng-GB',1,182,189,'',0,0,0,0,'','eztext'),(730,'eng-GB',2,14,200,'',0,0,0,0,'','ezimage'),(731,'eng-GB',3,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"administrator_user.\"\n         suffix=\"\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB/administrator_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(732,'eng-GB',1,107,200,'',0,0,0,0,'','ezimage'),(733,'eng-GB',2,107,200,'',0,0,0,0,'','ezimage'),(734,'eng-GB',1,111,200,'',0,0,0,0,'','ezimage'),(735,'eng-GB',1,149,200,'',0,0,0,0,'','ezimage'),(736,'eng-GB',2,149,200,'',0,0,0,0,'','ezimage'),(737,'eng-GB',3,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(729,'eng-GB',1,14,200,'',0,0,0,0,'','ezimage'),(728,'eng-GB',1,10,200,'',0,0,0,0,'','ezimage'),(675,'eng-GB',1,200,189,'sefsefsf\nsf\nsf',0,0,0,0,'','eztext'),(151,'eng-GB',53,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(601,'eng-GB',1,182,188,'',0,0,0,0,'','ezstring'),(716,'eng-GB',1,10,199,'',0,0,0,0,'','eztext'),(717,'eng-GB',1,14,199,'',0,0,0,0,'','eztext'),(718,'eng-GB',2,14,199,'',0,0,0,0,'','eztext'),(719,'eng-GB',3,14,199,'developer... ;)',0,0,0,0,'','eztext'),(720,'eng-GB',1,107,199,'',0,0,0,0,'','eztext'),(721,'eng-GB',2,107,199,'',0,0,0,0,'','eztext'),(722,'eng-GB',1,111,199,'',0,0,0,0,'','eztext'),(723,'eng-GB',1,149,199,'',0,0,0,0,'','eztext'),(724,'eng-GB',2,149,199,'',0,0,0,0,'','eztext'),(725,'eng-GB',3,149,199,'',0,0,0,0,'','eztext'),(692,'eng-GB',1,10,197,'',0,0,0,0,'','ezstring'),(693,'eng-GB',1,14,197,'',0,0,0,0,'','ezstring'),(694,'eng-GB',2,14,197,'',0,0,0,0,'','ezstring'),(695,'eng-GB',3,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(696,'eng-GB',1,107,197,'',0,0,0,0,'','ezstring'),(697,'eng-GB',2,107,197,'',0,0,0,0,'','ezstring'),(698,'eng-GB',1,111,197,'',0,0,0,0,'','ezstring'),(699,'eng-GB',1,149,197,'',0,0,0,0,'','ezstring'),(700,'eng-GB',2,149,197,'',0,0,0,0,'','ezstring'),(701,'eng-GB',3,149,197,'',0,0,0,0,'','ezstring'),(704,'eng-GB',1,10,198,'',0,0,0,0,'','ezstring'),(705,'eng-GB',1,14,198,'',0,0,0,0,'','ezstring'),(706,'eng-GB',2,14,198,'',0,0,0,0,'','ezstring'),(707,'eng-GB',3,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(708,'eng-GB',1,107,198,'',0,0,0,0,'','ezstring'),(709,'eng-GB',2,107,198,'',0,0,0,0,'','ezstring'),(710,'eng-GB',1,111,198,'',0,0,0,0,'','ezstring'),(711,'eng-GB',1,149,198,'',0,0,0,0,'','ezstring'),(712,'eng-GB',2,149,198,'',0,0,0,0,'','ezstring'),(713,'eng-GB',3,149,198,'',0,0,0,0,'','ezstring'),(150,'eng-GB',58,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(1154,'eng-GB',49,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1155,'eng-GB',50,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1156,'eng-GB',51,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1157,'eng-GB',52,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1158,'eng-GB',53,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1159,'eng-GB',54,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1160,'eng-GB',55,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1161,'eng-GB',56,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(302,'eng-GB',1,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',1,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',1,107,12,'',0,0,0,0,'','ezuser'),(302,'eng-GB',2,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',2,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',2,107,12,'',0,0,0,0,'','ezuser'),(315,'eng-GB',1,111,12,'',0,0,0,0,'','ezuser'),(313,'eng-GB',1,111,8,'vid',0,0,0,0,'vid','ezstring'),(314,'eng-GB',1,111,9,'la',0,0,0,0,'la','ezstring'),(1,'eng-GB',7,1,4,'Gallery',0,0,0,0,'gallery','ezstring'),(2,'eng-GB',7,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',57,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069328524\">\n  <original attribute_id=\"152\"\n            attribute_version=\"56\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414734\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069414734\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069414911\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',54,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',54,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',53,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.png\"\n         suffix=\"png\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB/my_gallery.png\"\n         original_filename=\"gallery.png\"\n         mime_type=\"original\"\n         width=\"208\"\n         height=\"59\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069257589\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB/my_gallery_reference.png\"\n         mime_type=\"image/png\"\n         width=\"208\"\n         height=\"59\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069257590\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB/my_gallery_medium.png\"\n         mime_type=\"image/png\"\n         width=\"200\"\n         height=\"56\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069257590\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB/my_gallery_logo.png\"\n         mime_type=\"image/png\"\n         width=\"204\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069257607\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',12,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel.png\"\n         original_filename=\"look_and_feel.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069254902\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414615\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069687923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',12,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(151,'eng-GB',52,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',52,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.png\"\n         suffix=\"png\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB/my_gallery.png\"\n         original_filename=\"gallery.png\"\n         mime_type=\"original\"\n         width=\"208\"\n         height=\"59\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069252381\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB/my_gallery_reference.png\"\n         mime_type=\"image/png\"\n         width=\"208\"\n         height=\"59\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069252383\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB/my_gallery_medium.png\"\n         mime_type=\"image/png\"\n         width=\"200\"\n         height=\"56\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069252383\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB/my_gallery_logo.png\"\n         mime_type=\"image/png\"\n         width=\"204\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069252397\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(600,'eng-GB',1,181,194,'',0,0,0,0,'','ezsubtreesubscription'),(591,'eng-GB',1,179,190,'',0,0,0,0,'','ezboolean'),(592,'eng-GB',1,179,194,'',0,0,0,0,'','ezsubtreesubscription'),(593,'eng-GB',1,180,188,'',0,0,0,0,'','ezstring'),(594,'eng-GB',1,180,189,'',0,0,0,0,'','eztext'),(595,'eng-GB',1,180,190,'',0,0,0,0,'','ezboolean'),(596,'eng-GB',1,180,194,'',0,0,0,0,'','ezsubtreesubscription'),(597,'eng-GB',1,181,188,'',0,0,0,0,'','ezstring'),(598,'eng-GB',1,181,189,'',0,0,0,0,'','eztext'),(599,'eng-GB',1,181,190,'',0,0,0,0,'','ezboolean'),(573,'eng-GB',1,175,188,'',0,0,0,0,'','ezstring'),(574,'eng-GB',1,175,189,'',0,0,0,0,'','eztext'),(575,'eng-GB',1,175,190,'',0,0,0,0,'','ezboolean'),(576,'eng-GB',1,175,194,'',0,0,0,0,'','ezsubtreesubscription'),(577,'eng-GB',1,176,188,'',0,0,0,0,'','ezstring'),(578,'eng-GB',1,176,189,'',0,0,0,0,'','eztext'),(579,'eng-GB',1,176,190,'',0,0,0,0,'','ezboolean'),(580,'eng-GB',1,176,194,'',0,0,0,0,'','ezsubtreesubscription'),(581,'eng-GB',1,177,188,'',0,0,0,0,'','ezstring'),(582,'eng-GB',1,177,189,'',0,0,0,0,'','eztext'),(583,'eng-GB',1,177,190,'',0,0,0,0,'','ezboolean'),(584,'eng-GB',1,177,194,'',0,0,0,0,'','ezsubtreesubscription'),(585,'eng-GB',1,178,188,'',0,0,0,0,'','ezstring'),(586,'eng-GB',1,178,189,'',0,0,0,0,'','eztext'),(587,'eng-GB',1,178,190,'',0,0,0,0,'','ezboolean'),(588,'eng-GB',1,178,194,'',0,0,0,0,'','ezsubtreesubscription'),(589,'eng-GB',1,179,188,'',0,0,0,0,'','ezstring'),(590,'eng-GB',1,179,189,'',0,0,0,0,'','eztext'),(561,'eng-GB',1,172,188,'',0,0,0,0,'','ezstring'),(562,'eng-GB',1,172,189,'',0,0,0,0,'','eztext'),(563,'eng-GB',1,172,190,'',0,0,0,0,'','ezboolean'),(564,'eng-GB',1,172,194,'',0,0,0,0,'','ezsubtreesubscription'),(565,'eng-GB',1,173,188,'',0,0,0,0,'','ezstring'),(566,'eng-GB',1,173,189,'',0,0,0,0,'','eztext'),(567,'eng-GB',1,173,190,'',0,0,0,0,'','ezboolean'),(568,'eng-GB',1,173,194,'',0,0,0,0,'','ezsubtreesubscription'),(569,'eng-GB',1,174,188,'',0,0,0,0,'','ezstring'),(570,'eng-GB',1,174,189,'',0,0,0,0,'','eztext'),(571,'eng-GB',1,174,190,'',0,0,0,0,'','ezboolean'),(572,'eng-GB',1,174,194,'',0,0,0,0,'','ezsubtreesubscription'),(560,'eng-GB',1,171,194,'',0,0,0,0,'','ezsubtreesubscription'),(559,'eng-GB',1,171,190,'',0,0,0,0,'','ezboolean'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(152,'eng-GB',54,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.png\"\n         suffix=\"png\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery.png\"\n         original_filename=\"gallery.png\"\n         mime_type=\"original\"\n         width=\"208\"\n         height=\"59\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069257589\">\n  <original attribute_id=\"152\"\n            attribute_version=\"53\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery_reference.png\"\n         mime_type=\"image/png\"\n         width=\"208\"\n         height=\"59\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069257590\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery_medium.png\"\n         mime_type=\"image/png\"\n         width=\"200\"\n         height=\"56\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069257590\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery_logo.png\"\n         mime_type=\"image/png\"\n         width=\"204\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069320005\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',54,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(150,'eng-GB',57,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(151,'eng-GB',57,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(153,'eng-GB',53,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',53,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',53,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',53,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(437,'eng-GB',55,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',55,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(482,'eng-GB',3,149,9,'yu',0,0,0,0,'yu','ezstring'),(481,'eng-GB',3,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(483,'eng-GB',3,149,12,'',0,0,0,0,'','ezuser'),(150,'eng-GB',49,56,157,'Blog',0,0,0,0,'','ezinisetting'),(150,'eng-GB',50,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',51,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',52,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',53,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',54,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',55,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(674,'eng-GB',1,200,188,'test',0,0,0,0,'test','ezstring'),(154,'eng-GB',50,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',50,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(641,'eng-GB',1,192,194,'',0,0,0,0,'','ezsubtreesubscription'),(642,'eng-GB',1,193,188,'',0,0,0,0,'','ezstring'),(643,'eng-GB',1,193,189,'',0,0,0,0,'','eztext'),(644,'eng-GB',1,193,190,'',0,0,0,0,'','ezboolean'),(645,'eng-GB',1,193,194,'',0,0,0,0,'','ezsubtreesubscription'),(646,'eng-GB',1,194,188,'',0,0,0,0,'','ezstring'),(647,'eng-GB',1,194,189,'',0,0,0,0,'','eztext'),(648,'eng-GB',1,194,190,'',0,0,0,0,'','ezboolean'),(649,'eng-GB',1,194,194,'',0,0,0,0,'','ezsubtreesubscription'),(151,'eng-GB',55,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',50,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.jpg\"\n         suffix=\"jpg\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-50-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-50-eng-GB/my_gallery.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"49\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-50-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-50-eng-GB/my_gallery_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-50-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-50-eng-GB/my_gallery_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-50-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-50-eng-GB/my_gallery_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069073730\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',50,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(151,'eng-GB',50,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',58,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069328524\">\n  <original attribute_id=\"152\"\n            attribute_version=\"57\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414734\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069414734\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069415078\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',58,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(671,'eng-GB',50,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',56,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(151,'eng-GB',49,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069328524\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414692\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069414692\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069328580\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(671,'eng-GB',54,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',55,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"160\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069325654\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069325655\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069325655\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069325675\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',55,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',55,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',49,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.jpg\"\n         suffix=\"jpg\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-49-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-49-eng-GB/blog.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"48\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-49-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-49-eng-GB/blog_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-49-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-49-eng-GB/blog_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(1,'eng-GB',3,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',3,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',57,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',57,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',57,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',57,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1161,'eng-GB',57,56,224,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(153,'eng-GB',58,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',58,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',58,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',58,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1161,'eng-GB',58,56,224,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(483,'eng-GB',4,149,12,'',0,0,0,0,'','ezuser'),(481,'eng-GB',4,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',4,149,9,'yu',0,0,0,0,'yu','ezstring'),(481,'eng-GB',1,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',1,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',1,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',4,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',4,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',4,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',4,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-4-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',5,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',5,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',5,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',5,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',5,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',5,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',5,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-5-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',6,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',6,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',6,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',6,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',6,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',6,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',6,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-6-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-6-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"5\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',7,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',7,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',7,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',7,149,197,'Derector',0,0,0,0,'derector','ezstring'),(713,'eng-GB',7,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',7,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',7,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',8,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',8,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',8,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',8,149,197,'Director',0,0,0,0,'director','ezstring'),(713,'eng-GB',8,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',8,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',8,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"wenyue_yu_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"139\"\n         height=\"200\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',9,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',9,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',9,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',9,149,197,'Director',0,0,0,0,'director','ezstring'),(713,'eng-GB',9,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',9,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',9,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"wenyue_yu_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"139\"\n         height=\"200\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',4,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',4,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',4,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',4,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',5,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',5,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',5,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',5,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"731\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"administrator_user_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(784,'eng-GB',1,224,207,'',0,0,0,0,'','ezstring'),(785,'eng-GB',1,225,207,'',0,0,0,0,'','ezstring'),(522,'eng-GB',2,161,140,'About me',0,0,0,0,'about me','ezstring'),(523,'eng-GB',2,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',2,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_me.\"\n         suffix=\"\"\n         basename=\"about_me\"\n         dirpath=\"var/blog/storage/images/about_me/524-2-eng-GB\"\n         url=\"var/blog/storage/images/about_me/524-2-eng-GB/about_me.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(153,'eng-GB',49,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',49,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',49,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',49,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(921,'eng-GB',1,267,4,'News',0,0,0,0,'news','ezstring'),(922,'eng-GB',1,267,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Latest.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(923,'eng-GB',1,268,1,'Latest sdfgsdgf',0,0,0,0,'latest sdfgsdgf','ezstring'),(924,'eng-GB',1,268,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfsdfg sdfgsdf</line>\n    <line>gsdf</line>\n    <line>gd</line>\n    <line>sgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(925,'eng-GB',1,268,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfg</line>\n    <line>df</line>\n    <line>ghdf</line>\n    <line>gh</line>\n    <line>fd</line>\n  </paragraph>\n  <paragraph>\n    <line>dfgh</line>\n    <line>dfgh</line>\n    <line>dfgh</line>\n    <line>df</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(926,'eng-GB',1,268,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"latest_sdfgsdgf.jpg\"\n         suffix=\"jpg\"\n         basename=\"latest_sdfgsdgf\"\n         dirpath=\"var/galler/storage/images/news/latest_sdfgsdgf/926-1-eng-GB\"\n         url=\"var/galler/storage/images/news/latest_sdfgsdgf/926-1-eng-GB/latest_sdfgsdgf.jpg\"\n         original_filename=\"dscn1631.jpg\"\n         mime_type=\"original\"\n         width=\"1024\"\n         height=\"768\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(927,'eng-GB',1,268,123,'',0,0,0,0,'','ezboolean'),(928,'eng-GB',1,268,177,'',0,0,0,0,'','ezinteger'),(1112,'eng-GB',1,323,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Yellow flower</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1113,'eng-GB',1,323,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"yellow_flower.jpg\"\n         suffix=\"jpg\"\n         basename=\"yellow_flower\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower.jpg\"\n         original_filename=\"yellow_flower.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"A yellow flower\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317797\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"yellow_flower_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069317809\"\n         is_valid=\"1\" />\n  <alias name=\"small_v\"\n         filename=\"yellow_flower_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069317825\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"yellow_flower_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318079\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"yellow_flower_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069318084\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1114,'eng-GB',1,324,217,'Landscape',0,0,0,0,'landscape','ezstring'),(1115,'eng-GB',1,324,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Landscape photography.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1116,'eng-GB',1,324,221,'',4,0,0,4,'','ezinteger'),(1117,'eng-GB',1,324,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"landscape.\"\n         suffix=\"\"\n         basename=\"landscape\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/1117-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/1117-1-eng-GB/landscape.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317845\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1118,'eng-GB',1,325,116,'Pond reflection',0,0,0,0,'pond reflection','ezstring'),(1119,'eng-GB',1,325,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Reflection in a small pond.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1120,'eng-GB',1,325,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"pond_reflection.jpg\"\n         suffix=\"jpg\"\n         basename=\"pond_reflection\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection.jpg\"\n         original_filename=\"pond_reflection.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Pond reflection\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317907\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"pond_reflection_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069318078\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"pond_reflection_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069321901\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"pond_reflection_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069321912\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1121,'eng-GB',1,326,116,'Ormevika skyline',0,0,0,0,'ormevika skyline','ezstring'),(1122,'eng-GB',1,326,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Ormevika by night</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1123,'eng-GB',1,326,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"ormevika_skyline.jpg\"\n         suffix=\"jpg\"\n         basename=\"ormevika_skyline\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline.jpg\"\n         original_filename=\"skyline.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Nice nightshot from ormevika\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317947\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"ormevika_skyline_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069318074\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"ormevika_skyline_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069318078\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"ormevika_skyline_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069321895\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"ormevika_skyline_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069321901\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1124,'eng-GB',1,327,116,'Foggy trees',0,0,0,0,'foggy trees','ezstring'),(1125,'eng-GB',1,327,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Foggy trees</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1126,'eng-GB',1,327,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"foggy_trees.jpg\"\n         suffix=\"jpg\"\n         basename=\"foggy_trees\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees.jpg\"\n         original_filename=\"trees.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Foggy trees\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317978\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"foggy_trees_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069318074\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"foggy_trees_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069318078\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"foggy_trees_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318286\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"foggy_trees_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069321894\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1127,'eng-GB',1,328,116,'Water reflection',0,0,0,0,'water reflection','ezstring'),(1128,'eng-GB',1,328,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Reflection from a lake in Kongsberg</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1129,'eng-GB',1,328,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"water_reflection.jpg\"\n         suffix=\"jpg\"\n         basename=\"water_reflection\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection.jpg\"\n         original_filename=\"water.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Water reflection\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318019\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"water_reflection_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069318073\"\n         is_valid=\"1\" />\n  <alias name=\"small_v\"\n         filename=\"water_reflection_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069318074\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"water_reflection_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318085\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"water_reflection_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069318285\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',51,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',51,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',51,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',51,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',5,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',5,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"324\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',5,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',5,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',10,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',10,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"103\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',10,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',10,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(110,'eng-GB',11,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',11,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',4,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',4,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"328\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',4,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',4,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(153,'eng-GB',56,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',56,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',56,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',56,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1130,'eng-GB',1,329,215,'Abstract',0,0,0,0,'abstract','ezstring'),(1131,'eng-GB',1,329,216,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Abstract photography</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1132,'eng-GB',1,329,223,'',2,0,0,2,'','ezinteger'),(1133,'eng-GB',1,330,217,'Misc',0,0,0,0,'misc','ezstring'),(1134,'eng-GB',1,330,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(1135,'eng-GB',1,330,221,'',4,0,0,4,'','ezinteger'),(1136,'eng-GB',1,330,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"misc.\"\n         suffix=\"\"\n         basename=\"misc\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/1136-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/1136-1-eng-GB/misc.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318368\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1137,'eng-GB',1,331,116,'CVS branching?',0,0,0,0,'cvs branching?','ezstring'),(1138,'eng-GB',1,331,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Visual representation of a CVS branch.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1139,'eng-GB',1,331,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cvs_branching.jpg\"\n         suffix=\"jpg\"\n         basename=\"cvs_branching\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching.jpg\"\n         original_filename=\"branch.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"CVS branch\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318446\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"cvs_branching_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069426469\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"cvs_branching_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069322030\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cvs_branching_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069322302\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1140,'eng-GB',1,332,116,'Gear wheel',0,0,0,0,'gear wheel','ezstring'),(1141,'eng-GB',1,332,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Gear wheel statue from Skien</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1142,'eng-GB',1,332,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"gear_wheel.jpg\"\n         suffix=\"jpg\"\n         basename=\"gear_wheel\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel.jpg\"\n         original_filename=\"gear_wheel.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Gear wheel\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318481\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"gear_wheel_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069426469\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"gear_wheel_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318980\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"gear_wheel_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069322029\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1143,'eng-GB',1,333,116,'Green clover',0,0,0,0,'green clover','ezstring'),(1144,'eng-GB',1,333,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Actually it&apos;s called gaukesyre</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1145,'eng-GB',1,333,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"green_clover.jpg\"\n         suffix=\"jpg\"\n         basename=\"green_clover\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover.jpg\"\n         original_filename=\"green_clover.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Gren clover\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318517\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"green_clover_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069426472\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"green_clover_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069427696\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"green_clover_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318965\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"green_clover_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069318979\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1146,'eng-GB',1,334,116,'Mjaurits',0,0,0,0,'mjaurits','ezstring'),(1147,'eng-GB',1,334,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Mjaurits the cat.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1148,'eng-GB',1,334,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"mjaurits.jpg\"\n         suffix=\"jpg\"\n         basename=\"mjaurits\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits.jpg\"\n         original_filename=\"cat.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"A closeup of the cat Mjaurits\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318560\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"mjaurits_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069426471\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"mjaurits_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069427696\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"mjaurits_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069427992\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"mjaurits_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069318965\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1149,'eng-GB',1,335,116,'Speeding',0,0,0,0,'speeding','ezstring'),(1150,'eng-GB',1,335,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>All withing legal limits, of course.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1151,'eng-GB',1,335,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"speeding.jpg\"\n         suffix=\"jpg\"\n         basename=\"speeding\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding.jpg\"\n         original_filename=\"speed.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Speed\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318589\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"speeding_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069426471\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"speeding_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069427695\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"speeding_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069427991\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"speeding_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318965\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1133,'eng-GB',2,330,217,'Misc',0,0,0,0,'misc','ezstring'),(1134,'eng-GB',2,330,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(1135,'eng-GB',2,330,221,'',3,0,0,3,'','ezinteger'),(1136,'eng-GB',2,330,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"misc.\"\n         suffix=\"\"\n         basename=\"misc\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/1136-2-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/1136-2-eng-GB/misc.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318368\">\n  <original attribute_id=\"1136\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1101,'eng-GB',2,320,217,'Flowers',0,0,0,0,'flowers','ezstring'),(1102,'eng-GB',2,320,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Pictures of various flowers.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1103,'eng-GB',2,320,221,'',3,0,0,3,'','ezinteger'),(1104,'eng-GB',2,320,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"flowers.\"\n         suffix=\"\"\n         basename=\"flowers\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/1104-2-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/1104-2-eng-GB/flowers.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317669\">\n  <original attribute_id=\"1104\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1101,'eng-GB',3,320,217,'Flowers',0,0,0,0,'flowers','ezstring'),(1102,'eng-GB',3,320,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Pictures of various flowers.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1103,'eng-GB',3,320,221,'',3,0,0,3,'','ezinteger'),(1104,'eng-GB',3,320,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"flowers.\"\n         suffix=\"\"\n         basename=\"flowers\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/1104-3-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/1104-3-eng-GB/flowers.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317669\">\n  <original attribute_id=\"1104\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1114,'eng-GB',2,324,217,'Landscape',0,0,0,0,'landscape','ezstring'),(1115,'eng-GB',2,324,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Landscape photography.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1116,'eng-GB',2,324,221,'',3,0,0,3,'','ezinteger'),(1117,'eng-GB',2,324,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"landscape.\"\n         suffix=\"\"\n         basename=\"landscape\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/1117-2-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/1117-2-eng-GB/landscape.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317845\">\n  <original attribute_id=\"1117\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1109,'eng-GB',1,322,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A purple one, actually two.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1108,'eng-GB',1,322,116,'Purple haze',0,0,0,0,'purple haze','ezstring'),(1098,'eng-GB',1,319,215,'Nature',0,0,0,0,'nature','ezstring'),(1099,'eng-GB',1,319,216,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Nature images</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1100,'eng-GB',1,319,223,'',2,0,0,2,'','ezinteger'),(1101,'eng-GB',1,320,217,'Flowers',0,0,0,0,'flowers','ezstring'),(1102,'eng-GB',1,320,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Pictures of various flowers.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1103,'eng-GB',1,320,221,'',4,0,0,4,'','ezinteger'),(1104,'eng-GB',1,320,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"flowers.\"\n         suffix=\"\"\n         basename=\"flowers\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/1104-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/1104-1-eng-GB/flowers.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317669\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1105,'eng-GB',1,321,116,'Blue flower',0,0,0,0,'blue flower','ezstring'),(1106,'eng-GB',1,321,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A small nice blue flower.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1107,'eng-GB',1,321,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blue_flower.jpg\"\n         suffix=\"jpg\"\n         basename=\"blue_flower\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower.jpg\"\n         original_filename=\"blue_flower.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Blue flower\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317728\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"blue_flower_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069317808\"\n         is_valid=\"1\" />\n  <alias name=\"small_v\"\n         filename=\"blue_flower_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069317826\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"blue_flower_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069320924\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"blue_flower_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069326352\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',5,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',5,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(327,'eng-GB',5,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',5,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator.png\"\n         original_filename=\"url_translator.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069254930\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414616\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069687924\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',12,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',12,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(104,'eng-GB',11,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',11,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(325,'eng-GB',6,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',6,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(323,'eng-GB',6,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',6,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache.png\"\n         original_filename=\"cache.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069254539\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414616\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069687923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',52,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',52,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',52,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',52,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1111,'eng-GB',1,323,116,'Yellow flower',0,0,0,0,'yellow flower','ezstring'),(1110,'eng-GB',1,322,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"purple_haze.jpg\"\n         suffix=\"jpg\"\n         basename=\"purple_haze\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze.jpg\"\n         original_filename=\"purple_haze.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Purple haze\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317767\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"purple_haze_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069317809\"\n         is_valid=\"1\" />\n  <alias name=\"small_v\"\n         filename=\"purple_haze_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069317826\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"purple_haze_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318084\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"purple_haze_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069320923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage');
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(268,'Latest sdfgsdgf',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(267,'News',1,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(165,'',1,'eng-GB','eng-GB'),(56,'My gallery',55,'eng-GB','eng-GB'),(320,'Flowers',3,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(87,'New Company',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(56,'My gallery',58,'eng-GB','eng-GB'),(56,'My gallery',57,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(169,'test',1,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(320,'Flowers',2,'eng-GB','eng-GB'),(168,'',1,'eng-GB','eng-GB'),(56,'My gallery',54,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(107,'John Doe',1,'eng-GB','eng-GB'),(107,'John Doe',2,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(111,'vid la',1,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(221,'New Poll',1,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(149,'wenyue yu',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(216,'New Poll',1,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(171,'',1,'eng-GB','eng-GB'),(172,'',1,'eng-GB','eng-GB'),(173,'',1,'eng-GB','eng-GB'),(174,'',1,'eng-GB','eng-GB'),(175,'',1,'eng-GB','eng-GB'),(176,'',1,'eng-GB','eng-GB'),(177,'',1,'eng-GB','eng-GB'),(178,'',1,'eng-GB','eng-GB'),(179,'',1,'eng-GB','eng-GB'),(180,'',1,'eng-GB','eng-GB'),(181,'',1,'eng-GB','eng-GB'),(182,'',1,'eng-GB','eng-GB'),(183,'',1,'eng-GB','eng-GB'),(184,'',1,'eng-GB','eng-GB'),(185,'',1,'eng-GB','eng-GB'),(186,'New Forum topic',1,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(149,'wenyue yu',2,'eng-GB','eng-GB'),(191,'',1,'eng-GB','eng-GB'),(192,'',1,'eng-GB','eng-GB'),(193,'',1,'eng-GB','eng-GB'),(194,'New Forum topic',1,'eng-GB','eng-GB'),(149,'wenyue yu',3,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(200,'test',1,'eng-GB','eng-GB'),(201,'Re:test',1,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(206,'Bård Farstad',1,'eng-GB','eng-GB'),(328,'Water reflection',1,'eng-GB','eng-GB'),(149,'wenyue yu',4,'eng-GB','eng-GB'),(149,'wenyue yu',5,'eng-GB','eng-GB'),(149,'wenyue yu',6,'eng-GB','eng-GB'),(149,'wenyue yu',7,'eng-GB','eng-GB'),(149,'wenyue yu',8,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(224,'New Poll',1,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(222,'New Poll',1,'eng-GB','eng-GB'),(225,'New Poll',1,'eng-GB','eng-GB'),(218,'New Poll',1,'eng-GB','eng-GB'),(217,'New Poll',1,'eng-GB','eng-GB'),(1,'Blog',6,'eng-GB','eng-GB'),(161,'About me',2,'eng-GB','eng-GB'),(115,'Cache',6,'eng-GB','eng-GB'),(43,'Classes',11,'eng-GB','eng-GB'),(45,'Look and feel',12,'eng-GB','eng-GB'),(56,'Blog',43,'eng-GB','eng-GB'),(56,'Blog',47,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(56,'Blog',48,'eng-GB','eng-GB'),(56,'Blog',49,'eng-GB','eng-GB'),(1,'Gallery',7,'eng-GB','eng-GB'),(56,'My gallery',53,'eng-GB','eng-GB'),(334,'Mjaurits',1,'eng-GB','eng-GB'),(335,'Speeding',1,'eng-GB','eng-GB'),(330,'Misc',2,'eng-GB','eng-GB'),(324,'Landscape',2,'eng-GB','eng-GB'),(56,'My gallery',50,'eng-GB','eng-GB'),(56,'My gallery',51,'eng-GB','eng-GB'),(115,'Cache',5,'eng-GB','eng-GB'),(43,'Classes',10,'eng-GB','eng-GB'),(45,'Look and feel',11,'eng-GB','eng-GB'),(116,'URL translator',4,'eng-GB','eng-GB'),(329,'Abstract',1,'eng-GB','eng-GB'),(330,'Misc',1,'eng-GB','eng-GB'),(331,'CVS branching?',1,'eng-GB','eng-GB'),(332,'Gear wheel',1,'eng-GB','eng-GB'),(333,'Green clover',1,'eng-GB','eng-GB'),(319,'Nature',1,'eng-GB','eng-GB'),(320,'Flowers',1,'eng-GB','eng-GB'),(321,'Blue flower',1,'eng-GB','eng-GB'),(322,'Purple haze',1,'eng-GB','eng-GB'),(323,'Yellow flower',1,'eng-GB','eng-GB'),(324,'Landscape',1,'eng-GB','eng-GB'),(325,'Pond reflection',1,'eng-GB','eng-GB'),(326,'Ormevika skyline',1,'eng-GB','eng-GB'),(116,'URL translator',5,'eng-GB','eng-GB'),(56,'My gallery',56,'eng-GB','eng-GB'),(299,'afunction_1280',1,'eng-GB','eng-GB'),(300,'nin',1,'eng-GB','eng-GB'),(56,'My gallery',52,'eng-GB','eng-GB'),(327,'Foggy trees',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,7,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,5,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,11,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,12,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(54,48,56,58,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/my_gallery',54),(127,2,161,2,1,2,'/1/2/127/',9,1,0,'about_me',127),(91,14,107,2,1,3,'/1/5/14/91/',9,1,0,'users/editors/john_doe',91),(92,14,111,1,1,3,'/1/5/14/92/',9,1,0,'users/editors/vid_la',92),(95,46,115,6,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,5,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(117,13,149,8,1,3,'/1/5/13/117/',9,1,0,'users/administrator_users/wenyue_yu',117),(200,199,268,1,1,3,'/1/2/199/200/',9,1,0,'news/latest_sdfgsdgf',200),(145,13,206,1,1,3,'/1/5/13/145/',9,1,0,'users/administrator_users/brd_farstad',145),(199,2,267,1,1,2,'/1/2/199/',9,1,0,'news',199),(250,249,321,1,1,4,'/1/2/248/249/250/',9,1,0,'nature/flowers/blue_flower',250),(251,249,322,1,1,4,'/1/2/248/249/251/',9,1,0,'nature/flowers/purple_haze',251),(252,249,323,1,1,4,'/1/2/248/249/252/',9,1,0,'nature/flowers/yellow_flower',252),(253,248,324,2,1,3,'/1/2/248/253/',9,1,0,'nature/landscape',253),(254,253,325,1,1,4,'/1/2/248/253/254/',9,1,0,'nature/landscape/pond_reflection',254),(255,253,326,1,1,4,'/1/2/248/253/255/',9,1,0,'nature/landscape/ormevika_skyline',255),(256,253,327,1,1,4,'/1/2/248/253/256/',9,1,0,'nature/landscape/foggy_trees',256),(257,253,328,1,1,4,'/1/2/248/253/257/',9,1,0,'nature/landscape/water_reflection',257),(258,2,329,1,1,2,'/1/2/258/',9,1,0,'abstract',258),(259,258,330,2,1,3,'/1/2/258/259/',9,1,0,'abstract/misc',259),(260,259,331,1,1,4,'/1/2/258/259/260/',9,1,0,'abstract/misc/cvs_branching',260),(261,259,332,1,1,4,'/1/2/258/259/261/',9,1,0,'abstract/misc/gear_wheel',261),(262,259,333,1,1,4,'/1/2/258/259/262/',9,1,0,'abstract/misc/green_clover',262),(263,259,334,1,1,4,'/1/2/258/259/263/',9,1,0,'abstract/misc/mjaurits',263),(264,259,335,1,1,4,'/1/2/258/259/264/',9,1,0,'abstract/misc/speeding',264),(248,2,319,1,1,2,'/1/2/248/',9,1,0,'nature',248),(249,248,320,3,1,3,'/1/2/248/249/',9,1,0,'nature/flowers',249);
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
INSERT INTO ezcontentobject_version VALUES (804,1,14,6,1068710443,1068710484,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,3,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(950,43,14,11,1069254550,1069254602,1,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(897,45,14,11,1069074388,1069074395,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(1000,56,14,58,1069415035,1069415051,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(741,175,149,1,1068108534,1068108624,0,0,0),(998,56,14,56,1069328499,1069328524,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(734,168,149,1,1068048359,1068048594,0,0,0),(982,324,14,1,1069317843,1069317869,3,0,0),(731,165,149,1,1068048190,1068048359,0,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(974,56,14,53,1069257560,1069257588,3,0,0),(725,161,14,1,1068047518,1068047603,3,0,0),(976,56,14,55,1069259417,1069325654,3,0,0),(851,56,14,49,1068739710,1068739721,3,0,0),(740,174,149,1,1068050123,1068108534,0,0,0),(975,56,14,54,1069259362,1069259378,3,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(892,56,14,51,1069074111,1069074215,3,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(845,43,14,9,1068729346,1068729356,3,0,0),(739,173,149,1,1068050088,1068050123,0,0,0),(984,326,14,1,1069317915,1069317947,1,0,0),(738,172,149,1,1068049706,1068050088,0,0,0),(735,169,149,1,1068048594,1068048622,0,0,0),(891,56,14,50,1069073666,1069073704,3,0,0),(990,332,14,1,1069318454,1069318481,1,0,0),(737,171,149,1,1068049618,1068049706,0,0,0),(928,56,14,52,1069252360,1069252381,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(876,268,14,1,1068814400,1068814751,1,0,0),(598,107,14,1,1066916843,1066916865,3,0,0),(599,107,14,2,1066916931,1066916941,1,0,0),(853,1,14,7,1068803287,1068803301,1,1,0),(604,111,14,1,1066917488,1066917523,1,0,0),(999,56,14,57,1069414689,1069414733,3,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(951,45,14,12,1069254878,1069254902,1,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(695,1,14,3,1068035768,1068035779,3,1,0),(844,115,14,4,1068729296,1068729308,3,0,0),(875,267,14,1,1068814351,1068814364,1,0,0),(709,149,14,1,1068040987,1068041016,3,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(949,115,14,6,1069254471,1069254539,1,0,0),(742,176,149,1,1068108624,1068108805,0,0,0),(743,177,149,1,1068108805,1068108834,0,0,0),(744,178,149,1,1068108834,1068108898,0,0,0),(745,179,149,1,1068108898,1068109016,0,0,0),(746,180,149,1,1068109016,1068109220,0,0,0),(747,181,149,1,1068109220,1068109255,0,0,0),(748,182,149,1,1068109255,1068109498,0,0,0),(749,183,149,1,1068109498,1068109663,0,0,0),(750,184,149,1,1068109663,1068109781,0,0,0),(751,185,149,1,1068109781,1068109829,0,0,0),(752,186,149,1,1068109829,1068109829,0,0,0),(757,149,14,2,1068111093,1068111116,3,0,0),(758,191,149,1,1068111317,1068111376,0,0,0),(759,192,149,1,1068111376,1068111870,0,0,0),(760,193,149,1,1068111870,1068111917,0,0,0),(761,194,149,1,1068111917,1068111917,0,0,0),(993,335,14,1,1069318568,1069318589,1,0,0),(766,149,14,3,1068112999,1068113012,3,0,0),(769,200,149,1,1068120480,1068120496,0,0,0),(770,201,149,1,1068120737,1068120756,0,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(782,206,14,1,1068123519,1068123599,1,0,0),(986,328,14,1,1069317987,1069318019,1,0,0),(785,149,149,4,1068129024,1068129067,3,0,0),(786,149,149,5,1068129453,1068129479,3,0,0),(787,149,149,6,1068129554,1068129569,3,0,0),(789,149,149,7,1068130370,1068130443,3,0,0),(790,149,149,8,1068130529,1068130543,1,0,0),(791,149,149,9,1068132647,1068132647,0,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(796,14,14,5,1068468183,1068468218,1,0,0),(997,324,14,2,1069321711,1069321719,1,0,0),(847,116,14,3,1068729385,1068729395,3,0,0),(846,45,14,10,1068729368,1068729376,3,0,0),(805,161,14,2,1068710499,1068710511,1,0,0),(996,320,14,3,1069321690,1069321700,1,0,0),(894,115,14,5,1069074351,1069074361,3,0,0),(896,43,14,10,1069074370,1069074377,3,0,0),(898,116,14,4,1069074407,1069074415,3,0,0),(980,322,14,1,1069317740,1069317767,1,0,0),(981,323,14,1,1069317776,1069317797,1,0,0),(983,325,14,1,1069317882,1069317906,1,0,0),(985,327,14,1,1069317955,1069317978,1,0,0),(987,329,14,1,1069318314,1069318331,1,0,0),(989,331,14,1,1069318390,1069318446,1,0,0),(995,320,14,2,1069321651,1069321658,3,0,0),(978,320,14,1,1069317667,1069317685,3,0,0),(992,334,14,1,1069318526,1069318560,1,0,0),(988,330,14,1,1069318366,1069318374,3,0,0),(991,333,14,1,1069318491,1069318517,1,0,0),(952,116,14,5,1069254913,1069254930,1,0,0),(994,330,14,2,1069321615,1069321635,1,0,0),(979,321,14,1,1069317697,1069317728,1,0,0),(977,319,14,1,1069317638,1069317649,1,0,0);
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
INSERT INTO ezimagefile VALUES (1,1104,'var/gallery/storage/images/nature/flowers/1104-1-eng-GB/flowers.'),(3,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower.jpg'),(5,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze.jpg'),(7,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower.jpg'),(8,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_h.jpg'),(9,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_small_h.jpg'),(10,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_small_h.jpg'),(11,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_small_v.jpg'),(12,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_small_v.jpg'),(13,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_v.jpg'),(14,1117,'var/gallery/storage/images/nature/landscape/1117-1-eng-GB/landscape.'),(16,1120,'var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection.jpg'),(18,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline.jpg'),(20,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees.jpg'),(22,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection.jpg'),(23,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_small_h.jpg'),(24,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_small_v.jpg'),(25,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_small_v.jpg'),(26,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_small_v.jpg'),(27,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_small_h.jpg'),(28,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_small_h.jpg'),(29,1120,'var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_small_h.jpg'),(30,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_navigator.jpg'),(31,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_large.jpg'),(32,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_navigator.jpg'),(33,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_navigator.jpg'),(34,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_large.jpg'),(35,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_navigator.jpg'),(36,1136,'var/gallery/storage/images/abstract/misc/1136-1-eng-GB/misc.'),(38,1139,'var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching.jpg'),(40,1142,'var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel.jpg'),(42,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover.jpg'),(44,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits.jpg'),(46,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding.jpg'),(47,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_v.jpg'),(48,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_v.jpg'),(49,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_v.jpg'),(50,1139,'var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_small_h.jpg'),(51,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_h.jpg'),(52,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_h.jpg'),(53,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_h.jpg'),(54,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_large.jpg'),(55,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_navigator.jpg'),(56,1142,'var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_small_h.jpg'),(57,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_large.jpg'),(58,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_navigator.jpg'),(59,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_navigator.jpg'),(60,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_large.jpg'),(61,1142,'var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_navigator.jpg'),(62,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery_logo.png'),(63,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_large.jpg'),(64,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_navigator.jpg'),(65,1136,'var/gallery/storage/images/abstract/misc/1136-2-eng-GB/misc.'),(66,1104,'var/gallery/storage/images/nature/flowers/1104-2-eng-GB/flowers.'),(67,1104,'var/gallery/storage/images/nature/flowers/1104-3-eng-GB/flowers.'),(68,1117,'var/gallery/storage/images/nature/landscape/1117-2-eng-GB/landscape.'),(69,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_large.jpg'),(70,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_navigator.jpg'),(71,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_large.jpg'),(72,1120,'var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_navigator.jpg'),(73,1120,'var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_large.jpg'),(74,1142,'var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_large.jpg'),(75,1139,'var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_navigator.jpg'),(76,1139,'var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_large.jpg'),(78,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery.gif'),(79,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_reference.gif'),(80,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_medium.gif'),(81,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_logo.gif'),(82,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_large.jpg'),(84,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery.gif'),(85,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_reference.gif'),(86,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_medium.gif'),(87,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_logo.gif'),(88,103,'var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_reference.png'),(89,103,'var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_large.png'),(90,109,'var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel_reference.png'),(91,109,'var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel_large.png'),(92,324,'var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache_reference.png'),(93,324,'var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache_large.png'),(94,328,'var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator_reference.png'),(95,328,'var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator_large.png'),(96,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery.gif'),(97,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_reference.gif'),(98,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_medium.gif'),(99,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_logo.gif'),(100,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery.gif'),(101,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_reference.gif'),(102,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_medium.gif'),(103,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_logo.gif');
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
INSERT INTO ezinfocollection VALUES (1,137,1068027503,'c6194244e6057c2ed46e92ac8c59be21',1068027503),(2,137,1068028058,'c6194244e6057c2ed46e92ac8c59be21',1068028058),(3,227,1068718291,'c6194244e6057c2ed46e92ac8c59be21',1068718291),(4,227,1068718359,'c6194244e6057c2ed46e92ac8c59be21',1068718359),(5,227,1068721732,'c6194244e6057c2ed46e92ac8c59be21',1068721732),(6,227,1068723204,'c6194244e6057c2ed46e92ac8c59be21',1068723204),(7,227,1068723216,'c6194244e6057c2ed46e92ac8c59be21',1068723216),(8,227,1068723236,'c6194244e6057c2ed46e92ac8c59be21',1068723236),(9,227,1068723826,'c6194244e6057c2ed46e92ac8c59be21',1068723826),(10,227,1068723856,'c6194244e6057c2ed46e92ac8c59be21',1068723856),(11,227,1068724005,'c6194244e6057c2ed46e92ac8c59be21',1068724005),(12,227,1068724227,'c6194244e6057c2ed46e92ac8c59be21',1068724227),(13,227,1068726335,'c6194244e6057c2ed46e92ac8c59be21',1068726335),(14,227,1068726772,'c6194244e6057c2ed46e92ac8c59be21',1068726772),(15,227,1068727910,'c6194244e6057c2ed46e92ac8c59be21',1068727910),(16,227,1068729189,'9d6d05ca28ed8f65e38e0e7f01741744',1068729189),(17,227,1068729968,'cf64399b65e473dd59293d990f30bfbf',1068729968),(18,227,1068731428,'c6194244e6057c2ed46e92ac8c59be21',1068731428),(19,227,1068731436,'c6194244e6057c2ed46e92ac8c59be21',1068731436),(20,227,1068731442,'c6194244e6057c2ed46e92ac8c59be21',1068731442),(21,227,1068732540,'c6194244e6057c2ed46e92ac8c59be21',1068732540),(22,227,1068736388,'c6194244e6057c2ed46e92ac8c59be21',1068736388),(23,227,1068736850,'c6194244e6057c2ed46e92ac8c59be21',1068736850),(24,227,1068737071,'c6194244e6057c2ed46e92ac8c59be21',1068737071),(25,227,1068796372,'c6194244e6057c2ed46e92ac8c59be21',1068796372);
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
INSERT INTO ezinfocollection_attribute VALUES (1,1,'',0,0,183,443,137),(2,1,'',0,0,185,445,137),(3,1,'',0,0,184,444,137),(4,2,'FOo bar ',0,0,183,443,137),(5,2,'bf@ez.no',0,0,185,445,137),(6,2,'This is my feedback.',0,0,184,444,137),(7,3,'',0,0,208,789,227),(8,4,'',2,0,208,789,227),(9,5,'',2,0,208,789,227),(10,6,'',3,0,208,789,227),(11,7,'',4,0,208,789,227),(12,8,'',1,0,208,789,227),(13,9,'',1,0,208,789,227),(14,10,'',1,0,208,789,227),(15,11,'',3,0,208,789,227),(16,12,'',3,0,208,789,227),(17,13,'',3,0,208,789,227),(18,14,'',0,0,208,789,227),(19,15,'',1,0,208,789,227),(20,16,'',2,0,208,789,227),(21,17,'',2,0,208,789,227),(22,18,'',0,0,208,789,227),(23,19,'',0,0,208,789,227),(24,20,'',0,0,208,789,227),(25,21,'',0,0,208,789,227),(26,22,'',0,0,208,789,227),(27,23,'',1,0,208,789,227),(28,24,'',1,0,208,789,227),(29,25,'',2,0,208,789,227);
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
INSERT INTO eznode_assignment VALUES (510,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(691,332,1,259,9,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(575,267,1,2,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(651,43,11,46,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(597,45,11,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(701,56,58,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(699,56,56,48,9,1,1,0,0),(445,175,1,2,1,1,0,0,0),(438,168,1,2,1,1,0,0,0),(677,56,55,48,9,1,1,0,0),(435,165,1,115,1,1,0,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(675,56,53,48,9,1,1,0,0),(551,56,49,48,9,1,1,0,0),(444,174,1,2,1,1,0,0,0),(676,56,54,48,9,1,1,0,0),(321,45,6,46,9,1,1,0,0),(592,56,51,48,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(443,173,1,2,1,1,0,0,0),(439,169,1,2,1,1,1,0,0),(544,115,4,46,9,1,1,0,0),(442,172,1,2,1,1,0,0,0),(681,322,1,249,9,1,1,0,0),(591,56,50,48,9,1,1,0,0),(545,43,9,46,9,1,1,0,0),(441,171,1,115,1,1,0,0,0),(335,45,8,46,9,1,1,0,0),(629,56,52,48,9,1,1,0,0),(546,45,10,46,9,1,1,0,0),(300,107,1,14,9,1,1,0,0),(301,107,2,14,9,1,1,0,0),(553,1,7,1,9,1,1,0,0),(306,111,1,14,9,1,1,0,0),(700,56,57,48,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(652,45,12,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(398,1,3,1,9,1,1,0,0),(413,149,1,13,9,1,1,0,0),(694,335,1,259,9,1,1,0,0),(424,14,2,13,9,1,1,0,0),(547,116,3,46,9,1,1,0,0),(446,176,1,2,1,1,0,0,0),(447,177,1,2,1,1,0,0,0),(448,178,1,2,1,1,0,0,0),(449,179,1,2,1,1,0,0,0),(450,180,1,2,1,1,0,0,0),(451,181,1,2,1,1,0,0,0),(452,182,1,2,1,1,0,0,0),(453,183,1,2,1,1,0,0,0),(454,184,1,2,1,1,0,0,0),(455,185,1,2,1,1,0,0,0),(456,186,1,2,1,1,1,0,0),(461,149,2,13,9,1,1,0,0),(462,191,1,115,1,1,0,0,0),(463,192,1,2,1,1,0,0,0),(464,193,1,2,1,1,0,0,0),(465,194,1,2,1,1,1,0,0),(650,115,6,46,9,1,1,0,0),(470,149,3,13,9,1,1,0,0),(473,200,1,114,1,1,1,0,0),(474,201,1,135,1,1,1,0,0),(481,14,3,13,9,1,1,0,0),(685,326,1,253,9,1,1,0,0),(687,328,1,253,9,1,1,0,0),(486,206,1,13,9,1,1,0,0),(576,268,1,199,9,1,1,0,0),(489,149,4,13,9,1,1,0,0),(490,149,5,13,9,1,1,0,0),(491,149,6,13,9,1,1,0,0),(493,149,7,13,9,1,1,0,0),(494,149,8,13,9,1,1,0,0),(495,149,9,13,9,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(500,14,5,13,9,1,1,0,0),(680,321,1,249,9,1,1,0,0),(698,324,2,248,9,1,1,0,0),(511,161,2,2,9,1,1,0,0),(697,320,3,248,9,1,1,0,0),(594,115,5,46,9,1,1,0,0),(596,43,10,46,9,1,1,0,0),(598,116,4,46,9,1,1,0,0),(683,324,1,248,9,1,1,0,0),(684,325,1,253,9,1,1,0,0),(686,327,1,253,9,1,1,0,0),(688,329,1,2,9,1,1,0,0),(693,334,1,259,9,1,1,0,0),(696,320,2,248,9,1,1,0,0),(679,320,1,248,9,1,1,0,0),(690,331,1,259,9,1,1,0,0),(689,330,1,258,9,1,1,0,0),(692,333,1,259,9,1,1,0,0),(653,116,5,46,9,1,1,0,0),(695,330,2,258,9,1,1,0,0),(682,323,1,249,9,1,1,0,0),(678,319,1,2,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (227,0,'ezpublish',142,3,0,0,'','','',''),(226,0,'ezpublish',141,3,0,0,'','','',''),(225,0,'ezpublish',211,2,0,0,'','','',''),(224,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',142,2,0,0,'','','',''),(222,0,'ezpublish',141,2,0,0,'','','',''),(221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','',''),(228,0,'ezpublish',1,6,0,0,'','','',''),(229,0,'ezpublish',161,2,0,0,'','','',''),(230,0,'ezpublish',49,2,0,0,'','','',''),(231,0,'ezpublish',212,1,0,0,'','','',''),(232,0,'ezpublish',213,1,0,0,'','','',''),(233,0,'ezpublish',214,1,0,0,'','','',''),(234,0,'ezpublish',215,1,0,0,'','','',''),(235,0,'ezpublish',219,1,0,0,'','','',''),(236,0,'ezpublish',220,1,0,0,'','','',''),(237,0,'ezpublish',212,2,0,0,'','','',''),(238,0,'ezpublish',213,2,0,0,'','','',''),(239,0,'ezpublish',226,1,0,0,'','','',''),(240,0,'ezpublish',227,1,0,0,'','','',''),(241,0,'ezpublish',228,1,0,0,'','','',''),(242,0,'ezpublish',229,1,0,0,'','','',''),(243,0,'ezpublish',230,1,0,0,'','','',''),(244,0,'ezpublish',231,1,0,0,'','','',''),(245,0,'ezpublish',233,1,0,0,'','','',''),(246,0,'ezpublish',232,1,0,0,'','','',''),(247,0,'ezpublish',235,1,0,0,'','','',''),(248,0,'ezpublish',234,1,0,0,'','','',''),(249,0,'ezpublish',237,1,0,0,'','','',''),(250,0,'ezpublish',236,1,0,0,'','','',''),(251,0,'ezpublish',238,1,0,0,'','','',''),(252,0,'ezpublish',239,1,0,0,'','','',''),(253,0,'ezpublish',240,1,0,0,'','','',''),(254,0,'ezpublish',227,2,0,0,'','','',''),(255,0,'ezpublish',240,2,0,0,'','','',''),(256,0,'ezpublish',241,1,0,0,'','','',''),(257,0,'ezpublish',242,1,0,0,'','','',''),(258,0,'ezpublish',243,1,0,0,'','','',''),(259,0,'ezpublish',244,1,0,0,'','','',''),(260,0,'ezpublish',56,47,0,0,'','','',''),(261,0,'ezpublish',115,4,0,0,'','','',''),(262,0,'ezpublish',43,9,0,0,'','','',''),(263,0,'ezpublish',45,10,0,0,'','','',''),(264,0,'ezpublish',116,3,0,0,'','','',''),(265,0,'ezpublish',245,1,0,0,'','','',''),(266,0,'ezpublish',56,48,0,0,'','','',''),(267,0,'ezpublish',246,1,0,0,'','','',''),(268,0,'ezpublish',56,49,0,0,'','','',''),(269,0,'ezpublish',247,1,0,0,'','','',''),(270,0,'ezpublish',1,7,0,0,'','','',''),(271,0,'ezpublish',248,1,0,0,'','','',''),(272,0,'ezpublish',249,1,0,0,'','','',''),(273,0,'ezpublish',250,1,0,0,'','','',''),(274,0,'ezpublish',251,1,0,0,'','','',''),(275,0,'ezpublish',252,1,0,0,'','','',''),(276,0,'ezpublish',254,1,0,0,'','','',''),(277,0,'ezpublish',254,2,0,0,'','','',''),(278,0,'ezpublish',255,1,0,0,'','','',''),(279,0,'ezpublish',256,1,0,0,'','','',''),(280,0,'ezpublish',257,1,0,0,'','','',''),(281,0,'ezpublish',258,1,0,0,'','','',''),(282,0,'ezpublish',259,1,0,0,'','','',''),(283,0,'ezpublish',260,1,0,0,'','','',''),(284,0,'ezpublish',261,1,0,0,'','','',''),(285,0,'ezpublish',262,1,0,0,'','','',''),(286,0,'ezpublish',263,1,0,0,'','','',''),(287,0,'ezpublish',264,1,0,0,'','','',''),(288,0,'ezpublish',256,2,0,0,'','','',''),(289,0,'ezpublish',265,1,0,0,'','','',''),(290,0,'ezpublish',266,1,0,0,'','','',''),(291,0,'ezpublish',267,1,0,0,'','','',''),(292,0,'ezpublish',268,1,0,0,'','','',''),(293,0,'ezpublish',269,1,0,0,'','','',''),(294,0,'ezpublish',260,2,0,0,'','','',''),(295,0,'ezpublish',259,2,0,0,'','','',''),(296,0,'ezpublish',270,1,0,0,'','','',''),(297,0,'ezpublish',271,1,0,0,'','','',''),(298,0,'ezpublish',257,2,0,0,'','','',''),(299,0,'ezpublish',251,2,0,0,'','','',''),(300,0,'ezpublish',272,1,0,0,'','','',''),(301,0,'ezpublish',259,3,0,0,'','','',''),(302,0,'ezpublish',273,1,0,0,'','','',''),(303,0,'ezpublish',270,2,0,0,'','','',''),(304,0,'ezpublish',270,3,0,0,'','','',''),(305,0,'ezpublish',274,1,0,0,'','','',''),(306,0,'ezpublish',56,50,0,0,'','','',''),(307,0,'ezpublish',56,51,0,0,'','','',''),(308,0,'ezpublish',275,1,0,0,'','','',''),(309,0,'ezpublish',115,5,0,0,'','','',''),(310,0,'ezpublish',43,10,0,0,'','','',''),(311,0,'ezpublish',45,11,0,0,'','','',''),(312,0,'ezpublish',276,1,0,0,'','','',''),(313,0,'ezpublish',116,4,0,0,'','','',''),(314,0,'ezpublish',271,2,0,0,'','','',''),(315,0,'ezpublish',277,1,0,0,'','','',''),(316,0,'ezpublish',278,1,0,0,'','','',''),(317,0,'ezpublish',279,1,0,0,'','','',''),(318,0,'ezpublish',280,1,0,0,'','','',''),(319,0,'ezpublish',281,1,0,0,'','','',''),(320,0,'ezpublish',282,1,0,0,'','','',''),(321,0,'ezpublish',283,1,0,0,'','','',''),(322,0,'ezpublish',284,1,0,0,'','','',''),(323,0,'ezpublish',251,3,0,0,'','','',''),(324,0,'ezpublish',251,4,0,0,'','','',''),(325,0,'ezpublish',285,1,0,0,'','','',''),(326,0,'ezpublish',286,1,0,0,'','','',''),(327,0,'ezpublish',287,1,0,0,'','','',''),(328,0,'ezpublish',285,2,0,0,'','','',''),(329,0,'ezpublish',288,1,0,0,'','','',''),(330,0,'ezpublish',251,5,0,0,'','','',''),(331,0,'ezpublish',289,1,0,0,'','','',''),(332,0,'ezpublish',290,1,0,0,'','','',''),(333,0,'ezpublish',291,1,0,0,'','','',''),(334,0,'ezpublish',292,1,0,0,'','','',''),(335,0,'ezpublish',293,1,0,0,'','','',''),(336,0,'ezpublish',294,1,0,0,'','','',''),(337,0,'ezpublish',295,1,0,0,'','','',''),(338,0,'ezpublish',294,2,0,0,'','','',''),(339,0,'ezpublish',296,1,0,0,'','','',''),(340,0,'ezpublish',297,1,0,0,'','','',''),(341,0,'ezpublish',298,1,0,0,'','','',''),(342,0,'ezpublish',56,52,0,0,'','','',''),(343,0,'ezpublish',301,1,0,0,'','','',''),(344,0,'ezpublish',302,1,0,0,'','','',''),(345,0,'ezpublish',303,1,0,0,'','','',''),(346,0,'ezpublish',304,1,0,0,'','','',''),(347,0,'ezpublish',305,1,0,0,'','','',''),(348,0,'ezpublish',306,1,0,0,'','','',''),(349,0,'ezpublish',304,2,0,0,'','','',''),(350,0,'ezpublish',307,1,0,0,'','','',''),(351,0,'ezpublish',308,1,0,0,'','','',''),(352,0,'ezpublish',309,1,0,0,'','','',''),(353,0,'ezpublish',308,2,0,0,'','','',''),(354,0,'ezpublish',310,1,0,0,'','','',''),(355,0,'ezpublish',311,1,0,0,'','','',''),(356,0,'ezpublish',312,1,0,0,'','','',''),(357,0,'ezpublish',314,1,0,0,'','','',''),(358,0,'ezpublish',315,1,0,0,'','','',''),(359,0,'ezpublish',316,1,0,0,'','','',''),(360,0,'ezpublish',317,1,0,0,'','','',''),(361,0,'ezpublish',318,1,0,0,'','','',''),(362,0,'ezpublish',115,6,0,0,'','','',''),(363,0,'ezpublish',43,11,0,0,'','','',''),(364,0,'ezpublish',45,12,0,0,'','','',''),(365,0,'ezpublish',116,5,0,0,'','','',''),(366,0,'ezpublish',310,2,0,0,'','','',''),(367,0,'ezpublish',311,2,0,0,'','','',''),(368,0,'ezpublish',312,2,0,0,'','','',''),(369,0,'ezpublish',310,3,0,0,'','','',''),(370,0,'ezpublish',310,4,0,0,'','','',''),(371,0,'ezpublish',310,5,0,0,'','','',''),(372,0,'ezpublish',310,6,0,0,'','','',''),(373,0,'ezpublish',310,7,0,0,'','','',''),(374,0,'ezpublish',310,8,0,0,'','','',''),(375,0,'ezpublish',311,3,0,0,'','','',''),(376,0,'ezpublish',312,3,0,0,'','','',''),(377,0,'ezpublish',317,2,0,0,'','','',''),(378,0,'ezpublish',315,2,0,0,'','','',''),(379,0,'ezpublish',316,2,0,0,'','','',''),(380,0,'ezpublish',318,2,0,0,'','','',''),(381,0,'ezpublish',306,2,0,0,'','','',''),(382,0,'ezpublish',305,2,0,0,'','','',''),(383,0,'ezpublish',303,2,0,0,'','','',''),(384,0,'ezpublish',304,3,0,0,'','','',''),(385,0,'ezpublish',307,2,0,0,'','','',''),(386,0,'ezpublish',56,53,0,0,'','','',''),(387,0,'ezpublish',56,54,0,0,'','','',''),(388,0,'ezpublish',319,1,0,0,'','','',''),(389,0,'ezpublish',320,1,0,0,'','','',''),(390,0,'ezpublish',321,1,0,0,'','','',''),(391,0,'ezpublish',322,1,0,0,'','','',''),(392,0,'ezpublish',323,1,0,0,'','','',''),(393,0,'ezpublish',324,1,0,0,'','','',''),(394,0,'ezpublish',325,1,0,0,'','','',''),(395,0,'ezpublish',326,1,0,0,'','','',''),(396,0,'ezpublish',327,1,0,0,'','','',''),(397,0,'ezpublish',328,1,0,0,'','','',''),(398,0,'ezpublish',329,1,0,0,'','','',''),(399,0,'ezpublish',330,1,0,0,'','','',''),(400,0,'ezpublish',331,1,0,0,'','','',''),(401,0,'ezpublish',332,1,0,0,'','','',''),(402,0,'ezpublish',333,1,0,0,'','','',''),(403,0,'ezpublish',334,1,0,0,'','','',''),(404,0,'ezpublish',335,1,0,0,'','','',''),(405,0,'ezpublish',330,2,0,0,'','','',''),(406,0,'ezpublish',320,2,0,0,'','','',''),(407,0,'ezpublish',320,3,0,0,'','','',''),(408,0,'ezpublish',324,2,0,0,'','','',''),(409,0,'ezpublish',56,55,0,0,'','','',''),(410,0,'ezpublish',56,56,0,0,'','','',''),(411,0,'ezpublish',56,57,0,0,'','','',''),(412,0,'ezpublish',56,58,0,0,'','','','');
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(341,8,'read','content','*'),(387,1,'login','user','*'),(388,1,'read','content',''),(389,1,'create','content',''),(390,1,'edit','content','');
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
INSERT INTO ezpolicy_limitation VALUES (309,388,'Class',0,'read','content'),(307,389,'Class',0,'create','content'),(308,390,'Class',0,'edit','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (643,309,'28'),(642,309,'27'),(641,309,'26'),(640,309,'12'),(639,309,'10'),(638,309,'5'),(637,309,'2'),(636,309,'1'),(634,307,'26'),(635,308,'26');
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
INSERT INTO ezsearch_object_word_link VALUES (3025,149,1340,0,4,1316,0,4,1068041016,2,199,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(5289,43,2184,0,2,2183,0,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(5287,43,2182,0,0,0,2183,14,1066384365,11,152,'',0),(5288,43,2183,0,1,2182,2184,14,1066384365,11,155,'',0),(5523,320,2302,0,5,2306,0,28,1069317685,1,221,'',3),(5522,320,2306,0,4,2308,2302,28,1069317685,1,218,'',0),(5521,320,2308,0,3,2249,2306,28,1069317685,1,218,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(3495,161,1399,0,383,1438,0,10,1068047603,1,141,'',0),(3494,161,1438,0,382,1466,1399,10,1068047603,1,141,'',0),(3493,161,1466,0,381,1455,1438,10,1068047603,1,141,'',0),(3492,161,1455,0,380,1485,1466,10,1068047603,1,141,'',0),(3491,161,1485,0,379,1484,1455,10,1068047603,1,141,'',0),(3490,161,1484,0,378,1483,1485,10,1068047603,1,141,'',0),(3489,161,1483,0,377,1387,1484,10,1068047603,1,141,'',0),(3488,161,1387,0,376,1406,1483,10,1068047603,1,141,'',0),(3487,161,1406,0,375,1457,1387,10,1068047603,1,141,'',0),(3486,161,1457,0,374,1452,1406,10,1068047603,1,141,'',0),(3485,161,1452,0,373,1482,1457,10,1068047603,1,141,'',0),(3484,161,1482,0,372,1407,1452,10,1068047603,1,141,'',0),(3483,161,1407,0,371,1403,1482,10,1068047603,1,141,'',0),(3482,161,1403,0,370,1448,1407,10,1068047603,1,141,'',0),(3481,161,1448,0,369,1481,1403,10,1068047603,1,141,'',0),(3480,161,1481,0,368,1480,1448,10,1068047603,1,141,'',0),(3479,161,1480,0,367,1479,1481,10,1068047603,1,141,'',0),(3478,161,1479,0,366,1478,1480,10,1068047603,1,141,'',0),(3477,161,1478,0,365,1399,1479,10,1068047603,1,141,'',0),(3476,161,1399,0,364,1440,1478,10,1068047603,1,141,'',0),(3475,161,1440,0,363,1386,1399,10,1068047603,1,141,'',0),(3474,161,1386,0,362,1477,1440,10,1068047603,1,141,'',0),(3473,161,1477,0,361,1412,1386,10,1068047603,1,141,'',0),(3472,161,1412,0,360,1401,1477,10,1068047603,1,141,'',0),(3471,161,1401,0,359,1457,1412,10,1068047603,1,141,'',0),(3470,161,1457,0,358,1399,1401,10,1068047603,1,141,'',0),(3469,161,1399,0,357,1476,1457,10,1068047603,1,141,'',0),(3468,161,1476,0,356,1382,1399,10,1068047603,1,141,'',0),(3467,161,1382,0,355,1448,1476,10,1068047603,1,141,'',0),(3466,161,1448,0,354,1427,1382,10,1068047603,1,141,'',0),(3465,161,1427,0,353,1438,1448,10,1068047603,1,141,'',0),(3464,161,1438,0,352,1396,1427,10,1068047603,1,141,'',0),(3463,161,1396,0,351,1475,1438,10,1068047603,1,141,'',0),(3462,161,1475,0,350,1474,1396,10,1068047603,1,141,'',0),(3461,161,1474,0,349,1448,1475,10,1068047603,1,141,'',0),(3460,161,1448,0,348,1419,1474,10,1068047603,1,141,'',0),(3459,161,1419,0,347,1473,1448,10,1068047603,1,141,'',0),(3458,161,1473,0,346,89,1419,10,1068047603,1,141,'',0),(3457,161,89,0,345,1454,1473,10,1068047603,1,141,'',0),(3456,161,1454,0,344,1460,89,10,1068047603,1,141,'',0),(3455,161,1460,0,343,1392,1454,10,1068047603,1,141,'',0),(3454,161,1392,0,342,1457,1460,10,1068047603,1,141,'',0),(3453,161,1457,0,341,1419,1392,10,1068047603,1,141,'',0),(3452,161,1419,0,340,1445,1457,10,1068047603,1,141,'',0),(3451,161,1445,0,339,1472,1419,10,1068047603,1,141,'',0),(3450,161,1472,0,338,1400,1445,10,1068047603,1,141,'',0),(3449,161,1400,0,337,1438,1472,10,1068047603,1,141,'',0),(3448,161,1438,0,336,1471,1400,10,1068047603,1,141,'',0),(3447,161,1471,0,335,1470,1438,10,1068047603,1,141,'',0),(3446,161,1470,0,334,1402,1471,10,1068047603,1,141,'',0),(3445,161,1402,0,333,1469,1470,10,1068047603,1,141,'',0),(3444,161,1469,0,332,1428,1402,10,1068047603,1,141,'',0),(3443,161,1428,0,331,1432,1469,10,1068047603,1,141,'',0),(3442,161,1432,0,330,1468,1428,10,1068047603,1,141,'',0),(3441,161,1468,0,329,1410,1432,10,1068047603,1,141,'',0),(3440,161,1410,0,328,1416,1468,10,1068047603,1,141,'',0),(3439,161,1416,0,327,1436,1410,10,1068047603,1,141,'',0),(3438,161,1436,0,326,1453,1416,10,1068047603,1,141,'',0),(3437,161,1453,0,325,1420,1436,10,1068047603,1,141,'',0),(3436,161,1420,0,324,1413,1453,10,1068047603,1,141,'',0),(3435,161,1413,0,323,1382,1420,10,1068047603,1,141,'',0),(3434,161,1382,0,322,1467,1413,10,1068047603,1,141,'',0),(3433,161,1467,0,321,1432,1382,10,1068047603,1,141,'',0),(3432,161,1432,0,320,1393,1467,10,1068047603,1,141,'',0),(3431,161,1393,0,319,1466,1432,10,1068047603,1,141,'',0),(3430,161,1466,0,318,1430,1393,10,1068047603,1,141,'',0),(3429,161,1430,0,317,1399,1466,10,1068047603,1,141,'',0),(3428,161,1399,0,316,1465,1430,10,1068047603,1,141,'',0),(3427,161,1465,0,315,1464,1399,10,1068047603,1,141,'',0),(3426,161,1464,0,314,1463,1465,10,1068047603,1,141,'',0),(3425,161,1463,0,313,1462,1464,10,1068047603,1,141,'',0),(3424,161,1462,0,312,1461,1463,10,1068047603,1,141,'',0),(3423,161,1461,0,311,1388,1462,10,1068047603,1,141,'',0),(3422,161,1388,0,310,1460,1461,10,1068047603,1,141,'',0),(3421,161,1460,0,309,1459,1388,10,1068047603,1,141,'',0),(3420,161,1459,0,308,1399,1460,10,1068047603,1,141,'',0),(3419,161,1399,0,307,1428,1459,10,1068047603,1,141,'',0),(3418,161,1428,0,306,1443,1399,10,1068047603,1,141,'',0),(3417,161,1443,0,305,1393,1428,10,1068047603,1,141,'',0),(3416,161,1393,0,304,1393,1443,10,1068047603,1,141,'',0),(3415,161,1393,0,303,1458,1393,10,1068047603,1,141,'',0),(3414,161,1458,0,302,1457,1393,10,1068047603,1,141,'',0),(1106,107,571,0,1,570,0,4,1066916865,2,9,'',0),(1105,107,570,0,0,0,571,4,1066916865,2,8,'',0),(1107,111,572,0,0,0,573,4,1066917523,2,8,'',0),(1108,111,573,0,1,572,0,4,1066917523,2,9,'',0),(3413,161,1457,0,301,943,1458,10,1068047603,1,141,'',0),(3412,161,943,0,300,1408,1457,10,1068047603,1,141,'',0),(3411,161,1408,0,299,1412,943,10,1068047603,1,141,'',0),(3410,161,1412,0,298,1428,1408,10,1068047603,1,141,'',0),(3409,161,1428,0,297,1388,1412,10,1068047603,1,141,'',0),(3408,161,1388,0,296,1418,1428,10,1068047603,1,141,'',0),(3407,161,1418,0,295,1456,1388,10,1068047603,1,141,'',0),(3406,161,1456,0,294,1397,1418,10,1068047603,1,141,'',0),(3405,161,1397,0,293,1445,1456,10,1068047603,1,141,'',0),(3404,161,1445,0,292,1455,1397,10,1068047603,1,141,'',0),(3403,161,1455,0,291,1454,1445,10,1068047603,1,141,'',0),(3402,161,1454,0,290,1453,1455,10,1068047603,1,141,'',0),(3401,161,1453,0,289,1452,1454,10,1068047603,1,141,'',0),(3400,161,1452,0,288,1401,1453,10,1068047603,1,141,'',0),(3399,161,1401,0,287,1451,1452,10,1068047603,1,141,'',0),(3398,161,1451,0,286,1450,1401,10,1068047603,1,141,'',0),(3397,161,1450,0,285,1449,1451,10,1068047603,1,141,'',0),(3396,161,1449,0,284,1448,1450,10,1068047603,1,141,'',0),(3395,161,1448,0,283,1447,1449,10,1068047603,1,141,'',0),(3394,161,1447,0,282,1446,1448,10,1068047603,1,141,'',0),(3393,161,1446,0,281,1411,1447,10,1068047603,1,141,'',0),(3392,161,1411,0,280,1445,1446,10,1068047603,1,141,'',0),(3391,161,1445,0,279,1444,1411,10,1068047603,1,141,'',0),(3390,161,1444,0,278,1413,1445,10,1068047603,1,141,'',0),(3389,161,1413,0,277,1443,1444,10,1068047603,1,141,'',0),(3388,161,1443,0,276,1442,1413,10,1068047603,1,141,'',0),(3387,161,1442,0,275,1441,1443,10,1068047603,1,141,'',0),(3386,161,1441,0,274,1393,1442,10,1068047603,1,141,'',0),(3385,161,1393,0,273,1441,1441,10,1068047603,1,141,'',0),(3384,161,1441,0,272,1440,1393,10,1068047603,1,141,'',0),(3383,161,1440,0,271,1439,1441,10,1068047603,1,141,'',0),(3382,161,1439,0,270,1438,1440,10,1068047603,1,141,'',0),(3381,161,1438,0,269,1437,1439,10,1068047603,1,141,'',0),(3380,161,1437,0,268,1436,1438,10,1068047603,1,141,'',0),(3379,161,1436,0,267,1435,1437,10,1068047603,1,141,'',0),(3378,161,1435,0,266,1406,1436,10,1068047603,1,141,'',0),(3377,161,1406,0,265,1388,1435,10,1068047603,1,141,'',0),(3376,161,1388,0,264,1434,1406,10,1068047603,1,141,'',0),(3375,161,1434,0,263,1433,1388,10,1068047603,1,141,'',0),(3374,161,1433,0,262,1432,1434,10,1068047603,1,141,'',0),(3373,161,1432,0,261,1431,1433,10,1068047603,1,141,'',0),(3372,161,1431,0,260,1430,1432,10,1068047603,1,141,'',0),(3371,161,1430,0,259,943,1431,10,1068047603,1,141,'',0),(3370,161,943,0,258,1393,1430,10,1068047603,1,141,'',0),(3369,161,1393,0,257,1429,943,10,1068047603,1,141,'',0),(3368,161,1429,0,256,1428,1393,10,1068047603,1,141,'',0),(3367,161,1428,0,255,1410,1429,10,1068047603,1,141,'',0),(3366,161,1410,0,254,1388,1428,10,1068047603,1,141,'',0),(3365,161,1388,0,253,1427,1410,10,1068047603,1,141,'',0),(3364,161,1427,0,252,1426,1388,10,1068047603,1,141,'',0),(3363,161,1426,0,251,1425,1427,10,1068047603,1,141,'',0),(3362,161,1425,0,250,1393,1426,10,1068047603,1,141,'',0),(3361,161,1393,0,249,1425,1425,10,1068047603,1,141,'',0),(3360,161,1425,0,248,1424,1393,10,1068047603,1,141,'',0),(3359,161,1424,0,247,1423,1425,10,1068047603,1,141,'',0),(3358,161,1423,0,246,1400,1424,10,1068047603,1,141,'',0),(3357,161,1400,0,245,1422,1423,10,1068047603,1,141,'',0),(3356,161,1422,0,244,1421,1400,10,1068047603,1,141,'',0),(3355,161,1421,0,243,1420,1422,10,1068047603,1,141,'',0),(3354,161,1420,0,242,1419,1421,10,1068047603,1,141,'',0),(3353,161,1419,0,241,1387,1420,10,1068047603,1,141,'',0),(3352,161,1387,0,240,1399,1419,10,1068047603,1,141,'',0),(3351,161,1399,0,239,1418,1387,10,1068047603,1,141,'',0),(3350,161,1418,0,238,1417,1399,10,1068047603,1,141,'',0),(3349,161,1417,0,237,1416,1418,10,1068047603,1,141,'',0),(3348,161,1416,0,236,1415,1417,10,1068047603,1,141,'',0),(3347,161,1415,0,235,1414,1416,10,1068047603,1,141,'',0),(3346,161,1414,0,234,1413,1415,10,1068047603,1,141,'',0),(3345,161,1413,0,233,1412,1414,10,1068047603,1,141,'',0),(3344,161,1412,0,232,1411,1413,10,1068047603,1,141,'',0),(3343,161,1411,0,231,1410,1412,10,1068047603,1,141,'',0),(3342,161,1410,0,230,89,1411,10,1068047603,1,141,'',0),(3341,161,89,0,229,1409,1410,10,1068047603,1,141,'',0),(3340,161,1409,0,228,1408,89,10,1068047603,1,141,'',0),(3339,161,1408,0,227,1407,1409,10,1068047603,1,141,'',0),(3338,161,1407,0,226,1406,1408,10,1068047603,1,141,'',0),(3337,161,1406,0,225,1389,1407,10,1068047603,1,141,'',0),(3336,161,1389,0,224,1405,1406,10,1068047603,1,141,'',0),(3335,161,1405,0,223,1404,1389,10,1068047603,1,141,'',0),(3334,161,1404,0,222,1403,1405,10,1068047603,1,141,'',0),(3333,161,1403,0,221,1402,1404,10,1068047603,1,141,'',0),(3332,161,1402,0,220,1401,1403,10,1068047603,1,141,'',0),(3331,161,1401,0,219,1400,1402,10,1068047603,1,141,'',0),(3330,161,1400,0,218,1399,1401,10,1068047603,1,141,'',0),(3329,161,1399,0,217,1398,1400,10,1068047603,1,141,'',0),(3328,161,1398,0,216,1388,1399,10,1068047603,1,141,'',0),(3327,161,1388,0,215,1389,1398,10,1068047603,1,141,'',0),(3326,161,1389,0,214,1397,1388,10,1068047603,1,141,'',0),(3325,161,1397,0,213,1396,1389,10,1068047603,1,141,'',0),(3324,161,1396,0,212,1395,1397,10,1068047603,1,141,'',0),(3323,161,1395,0,211,1394,1396,10,1068047603,1,141,'',0),(3322,161,1394,0,210,1386,1395,10,1068047603,1,141,'',0),(3321,161,1386,0,209,1393,1394,10,1068047603,1,141,'',0),(3320,161,1393,0,208,1392,1386,10,1068047603,1,141,'',0),(3319,161,1392,0,207,1391,1393,10,1068047603,1,141,'',0),(3318,161,1391,0,206,1390,1392,10,1068047603,1,141,'',0),(3317,161,1390,0,205,1389,1391,10,1068047603,1,141,'',0),(3316,161,1389,0,204,1384,1390,10,1068047603,1,141,'',0),(3315,161,1384,0,203,1383,1389,10,1068047603,1,141,'',0),(3314,161,1383,0,202,1388,1384,10,1068047603,1,141,'',0),(3313,161,1388,0,201,1387,1383,10,1068047603,1,141,'',0),(3312,161,1387,0,200,1386,1388,10,1068047603,1,141,'',0),(3311,161,1386,0,199,1385,1387,10,1068047603,1,141,'',0),(3310,161,1385,0,198,1384,1386,10,1068047603,1,141,'',0),(3309,161,1384,0,197,1383,1385,10,1068047603,1,141,'',0),(3308,161,1383,0,196,1382,1384,10,1068047603,1,141,'',0),(3307,161,1382,0,195,944,1383,10,1068047603,1,141,'',0),(3306,161,944,0,194,943,1382,10,1068047603,1,141,'',0),(3305,161,943,0,193,1399,944,10,1068047603,1,141,'',0),(3304,161,1399,0,192,1438,943,10,1068047603,1,141,'',0),(3303,161,1438,0,191,1466,1399,10,1068047603,1,141,'',0),(3302,161,1466,0,190,1455,1438,10,1068047603,1,141,'',0),(3301,161,1455,0,189,1485,1466,10,1068047603,1,141,'',0),(3300,161,1485,0,188,1484,1455,10,1068047603,1,141,'',0),(3299,161,1484,0,187,1483,1485,10,1068047603,1,141,'',0),(3298,161,1483,0,186,1387,1484,10,1068047603,1,141,'',0),(3297,161,1387,0,185,1406,1483,10,1068047603,1,141,'',0),(3296,161,1406,0,184,1457,1387,10,1068047603,1,141,'',0),(3295,161,1457,0,183,1452,1406,10,1068047603,1,141,'',0),(3294,161,1452,0,182,1482,1457,10,1068047603,1,141,'',0),(3293,161,1482,0,181,1407,1452,10,1068047603,1,141,'',0),(3292,161,1407,0,180,1403,1482,10,1068047603,1,141,'',0),(3291,161,1403,0,179,1448,1407,10,1068047603,1,141,'',0),(3290,161,1448,0,178,1481,1403,10,1068047603,1,141,'',0),(3289,161,1481,0,177,1480,1448,10,1068047603,1,141,'',0),(3288,161,1480,0,176,1479,1481,10,1068047603,1,141,'',0),(3287,161,1479,0,175,1478,1480,10,1068047603,1,141,'',0),(3286,161,1478,0,174,1399,1479,10,1068047603,1,141,'',0),(3285,161,1399,0,173,1440,1478,10,1068047603,1,141,'',0),(3284,161,1440,0,172,1386,1399,10,1068047603,1,141,'',0),(3283,161,1386,0,171,1477,1440,10,1068047603,1,141,'',0),(3282,161,1477,0,170,1412,1386,10,1068047603,1,141,'',0),(3281,161,1412,0,169,1401,1477,10,1068047603,1,141,'',0),(3280,161,1401,0,168,1457,1412,10,1068047603,1,141,'',0),(3279,161,1457,0,167,1399,1401,10,1068047603,1,141,'',0),(3278,161,1399,0,166,1476,1457,10,1068047603,1,141,'',0),(3277,161,1476,0,165,1382,1399,10,1068047603,1,141,'',0),(3276,161,1382,0,164,1448,1476,10,1068047603,1,141,'',0),(3275,161,1448,0,163,1427,1382,10,1068047603,1,141,'',0),(3274,161,1427,0,162,1438,1448,10,1068047603,1,141,'',0),(3273,161,1438,0,161,1396,1427,10,1068047603,1,141,'',0),(3272,161,1396,0,160,1475,1438,10,1068047603,1,141,'',0),(3271,161,1475,0,159,1474,1396,10,1068047603,1,141,'',0),(3270,161,1474,0,158,1448,1475,10,1068047603,1,141,'',0),(3269,161,1448,0,157,1419,1474,10,1068047603,1,141,'',0),(3268,161,1419,0,156,1473,1448,10,1068047603,1,141,'',0),(3267,161,1473,0,155,89,1419,10,1068047603,1,141,'',0),(3266,161,89,0,154,1454,1473,10,1068047603,1,141,'',0),(3265,161,1454,0,153,1460,89,10,1068047603,1,141,'',0),(3264,161,1460,0,152,1392,1454,10,1068047603,1,141,'',0),(3263,161,1392,0,151,1457,1460,10,1068047603,1,141,'',0),(3262,161,1457,0,150,1419,1392,10,1068047603,1,141,'',0),(3261,161,1419,0,149,1445,1457,10,1068047603,1,141,'',0),(3260,161,1445,0,148,1472,1419,10,1068047603,1,141,'',0),(3259,161,1472,0,147,1400,1445,10,1068047603,1,141,'',0),(3258,161,1400,0,146,1438,1472,10,1068047603,1,141,'',0),(3257,161,1438,0,145,1471,1400,10,1068047603,1,141,'',0),(3256,161,1471,0,144,1470,1438,10,1068047603,1,141,'',0),(3255,161,1470,0,143,1402,1471,10,1068047603,1,141,'',0),(3254,161,1402,0,142,1469,1470,10,1068047603,1,141,'',0),(3253,161,1469,0,141,1428,1402,10,1068047603,1,141,'',0),(3252,161,1428,0,140,1432,1469,10,1068047603,1,141,'',0),(3251,161,1432,0,139,1468,1428,10,1068047603,1,141,'',0),(3250,161,1468,0,138,1410,1432,10,1068047603,1,141,'',0),(3249,161,1410,0,137,1416,1468,10,1068047603,1,141,'',0),(3248,161,1416,0,136,1436,1410,10,1068047603,1,141,'',0),(3247,161,1436,0,135,1453,1416,10,1068047603,1,141,'',0),(3246,161,1453,0,134,1420,1436,10,1068047603,1,141,'',0),(3245,161,1420,0,133,1413,1453,10,1068047603,1,141,'',0),(3244,161,1413,0,132,1382,1420,10,1068047603,1,141,'',0),(3243,161,1382,0,131,1467,1413,10,1068047603,1,141,'',0),(3242,161,1467,0,130,1432,1382,10,1068047603,1,141,'',0),(3241,161,1432,0,129,1393,1467,10,1068047603,1,141,'',0),(3240,161,1393,0,128,1466,1432,10,1068047603,1,141,'',0),(3239,161,1466,0,127,1430,1393,10,1068047603,1,141,'',0),(3238,161,1430,0,126,1399,1466,10,1068047603,1,141,'',0),(3237,161,1399,0,125,1465,1430,10,1068047603,1,141,'',0),(3236,161,1465,0,124,1464,1399,10,1068047603,1,141,'',0),(3235,161,1464,0,123,1463,1465,10,1068047603,1,141,'',0),(3234,161,1463,0,122,1462,1464,10,1068047603,1,141,'',0),(3233,161,1462,0,121,1461,1463,10,1068047603,1,141,'',0),(3232,161,1461,0,120,1388,1462,10,1068047603,1,141,'',0),(3231,161,1388,0,119,1460,1461,10,1068047603,1,141,'',0),(3230,161,1460,0,118,1459,1388,10,1068047603,1,141,'',0),(3229,161,1459,0,117,1399,1460,10,1068047603,1,141,'',0),(3228,161,1399,0,116,1428,1459,10,1068047603,1,141,'',0),(3227,161,1428,0,115,1443,1399,10,1068047603,1,141,'',0),(3226,161,1443,0,114,1393,1428,10,1068047603,1,141,'',0),(3225,161,1393,0,113,1393,1443,10,1068047603,1,141,'',0),(3224,161,1393,0,112,1458,1393,10,1068047603,1,141,'',0),(3223,161,1458,0,111,1457,1393,10,1068047603,1,141,'',0),(5458,326,2268,0,3,2266,2269,5,1069317947,1,117,'',0),(5286,115,2181,0,2,7,0,14,1066991725,11,155,'',0),(5285,115,7,0,1,2181,2181,14,1066991725,11,155,'',0),(5284,115,2181,0,0,0,7,14,1066991725,11,152,'',0),(5299,116,2189,0,3,25,0,14,1066992054,11,155,'',0),(5298,116,25,0,2,2188,2189,14,1066992054,11,155,'',0),(5297,116,2188,0,1,2187,25,14,1066992054,11,152,'',0),(5296,116,2187,0,0,0,2188,14,1066992054,11,152,'',0),(5295,45,2186,0,5,2185,0,14,1066388816,11,155,'',0),(5294,45,2185,0,4,25,2186,14,1066388816,11,155,'',0),(5293,45,25,0,3,34,2185,14,1066388816,11,155,'',0),(5292,45,34,0,2,33,25,14,1066388816,11,152,'',0),(5290,45,32,0,0,0,33,14,1066388816,11,152,'',0),(5291,45,33,0,1,32,34,14,1066388816,11,152,'',0),(3068,14,1362,0,5,1316,0,4,1033920830,2,199,'',0),(3067,14,1316,0,4,1361,1362,4,1033920830,2,198,'',0),(3024,149,1316,0,3,1339,1340,4,1068041016,2,198,'',0),(3222,161,1457,0,110,943,1458,10,1068047603,1,141,'',0),(3221,161,943,0,109,1408,1457,10,1068047603,1,141,'',0),(3220,161,1408,0,108,1412,943,10,1068047603,1,141,'',0),(3219,161,1412,0,107,1428,1408,10,1068047603,1,141,'',0),(3218,161,1428,0,106,1388,1412,10,1068047603,1,141,'',0),(3217,161,1388,0,105,1418,1428,10,1068047603,1,141,'',0),(3216,161,1418,0,104,1456,1388,10,1068047603,1,141,'',0),(3215,161,1456,0,103,1397,1418,10,1068047603,1,141,'',0),(3214,161,1397,0,102,1445,1456,10,1068047603,1,141,'',0),(3213,161,1445,0,101,1455,1397,10,1068047603,1,141,'',0),(3212,161,1455,0,100,1454,1445,10,1068047603,1,141,'',0),(3211,161,1454,0,99,1453,1455,10,1068047603,1,141,'',0),(3210,161,1453,0,98,1452,1454,10,1068047603,1,141,'',0),(3209,161,1452,0,97,1401,1453,10,1068047603,1,141,'',0),(3208,161,1401,0,96,1451,1452,10,1068047603,1,141,'',0),(3207,161,1451,0,95,1450,1401,10,1068047603,1,141,'',0),(3206,161,1450,0,94,1449,1451,10,1068047603,1,141,'',0),(3205,161,1449,0,93,1448,1450,10,1068047603,1,141,'',0),(3204,161,1448,0,92,1447,1449,10,1068047603,1,141,'',0),(3203,161,1447,0,91,1446,1448,10,1068047603,1,141,'',0),(3202,161,1446,0,90,1411,1447,10,1068047603,1,141,'',0),(3201,161,1411,0,89,1445,1446,10,1068047603,1,141,'',0),(3200,161,1445,0,88,1444,1411,10,1068047603,1,141,'',0),(3199,161,1444,0,87,1413,1445,10,1068047603,1,141,'',0),(3198,161,1413,0,86,1443,1444,10,1068047603,1,141,'',0),(3197,161,1443,0,85,1442,1413,10,1068047603,1,141,'',0),(3196,161,1442,0,84,1441,1443,10,1068047603,1,141,'',0),(3195,161,1441,0,83,1393,1442,10,1068047603,1,141,'',0),(3194,161,1393,0,82,1441,1441,10,1068047603,1,141,'',0),(3193,161,1441,0,81,1440,1393,10,1068047603,1,141,'',0),(3192,161,1440,0,80,1439,1441,10,1068047603,1,141,'',0),(3191,161,1439,0,79,1438,1440,10,1068047603,1,141,'',0),(3190,161,1438,0,78,1437,1439,10,1068047603,1,141,'',0),(3189,161,1437,0,77,1436,1438,10,1068047603,1,141,'',0),(3188,161,1436,0,76,1435,1437,10,1068047603,1,141,'',0),(3187,161,1435,0,75,1406,1436,10,1068047603,1,141,'',0),(3186,161,1406,0,74,1388,1435,10,1068047603,1,141,'',0),(3185,161,1388,0,73,1434,1406,10,1068047603,1,141,'',0),(3184,161,1434,0,72,1433,1388,10,1068047603,1,141,'',0),(3183,161,1433,0,71,1432,1434,10,1068047603,1,141,'',0),(3182,161,1432,0,70,1431,1433,10,1068047603,1,141,'',0),(3181,161,1431,0,69,1430,1432,10,1068047603,1,141,'',0),(3180,161,1430,0,68,943,1431,10,1068047603,1,141,'',0),(3179,161,943,0,67,1393,1430,10,1068047603,1,141,'',0),(3178,161,1393,0,66,1429,943,10,1068047603,1,141,'',0),(3177,161,1429,0,65,1428,1393,10,1068047603,1,141,'',0),(3176,161,1428,0,64,1410,1429,10,1068047603,1,141,'',0),(3175,161,1410,0,63,1388,1428,10,1068047603,1,141,'',0),(3174,161,1388,0,62,1427,1410,10,1068047603,1,141,'',0),(3173,161,1427,0,61,1426,1388,10,1068047603,1,141,'',0),(3172,161,1426,0,60,1425,1427,10,1068047603,1,141,'',0),(3171,161,1425,0,59,1393,1426,10,1068047603,1,141,'',0),(3170,161,1393,0,58,1425,1425,10,1068047603,1,141,'',0),(3169,161,1425,0,57,1424,1393,10,1068047603,1,141,'',0),(3168,161,1424,0,56,1423,1425,10,1068047603,1,141,'',0),(3167,161,1423,0,55,1400,1424,10,1068047603,1,141,'',0),(3166,161,1400,0,54,1422,1423,10,1068047603,1,141,'',0),(3165,161,1422,0,53,1421,1400,10,1068047603,1,141,'',0),(3164,161,1421,0,52,1420,1422,10,1068047603,1,141,'',0),(3163,161,1420,0,51,1419,1421,10,1068047603,1,141,'',0),(3162,161,1419,0,50,1387,1420,10,1068047603,1,141,'',0),(3161,161,1387,0,49,1399,1419,10,1068047603,1,141,'',0),(3160,161,1399,0,48,1418,1387,10,1068047603,1,141,'',0),(3159,161,1418,0,47,1417,1399,10,1068047603,1,141,'',0),(3158,161,1417,0,46,1416,1418,10,1068047603,1,141,'',0),(3157,161,1416,0,45,1415,1417,10,1068047603,1,141,'',0),(3156,161,1415,0,44,1414,1416,10,1068047603,1,141,'',0),(3155,161,1414,0,43,1413,1415,10,1068047603,1,141,'',0),(3154,161,1413,0,42,1412,1414,10,1068047603,1,141,'',0),(3153,161,1412,0,41,1411,1413,10,1068047603,1,141,'',0),(3152,161,1411,0,40,1410,1412,10,1068047603,1,141,'',0),(3151,161,1410,0,39,89,1411,10,1068047603,1,141,'',0),(3150,161,89,0,38,1409,1410,10,1068047603,1,141,'',0),(3149,161,1409,0,37,1408,89,10,1068047603,1,141,'',0),(3148,161,1408,0,36,1407,1409,10,1068047603,1,141,'',0),(3147,161,1407,0,35,1406,1408,10,1068047603,1,141,'',0),(3146,161,1406,0,34,1389,1407,10,1068047603,1,141,'',0),(3145,161,1389,0,33,1405,1406,10,1068047603,1,141,'',0),(3144,161,1405,0,32,1404,1389,10,1068047603,1,141,'',0),(3143,161,1404,0,31,1403,1405,10,1068047603,1,141,'',0),(3142,161,1403,0,30,1402,1404,10,1068047603,1,141,'',0),(3141,161,1402,0,29,1401,1403,10,1068047603,1,141,'',0),(3140,161,1401,0,28,1400,1402,10,1068047603,1,141,'',0),(3139,161,1400,0,27,1399,1401,10,1068047603,1,141,'',0),(3138,161,1399,0,26,1398,1400,10,1068047603,1,141,'',0),(3137,161,1398,0,25,1388,1399,10,1068047603,1,141,'',0),(3136,161,1388,0,24,1389,1398,10,1068047603,1,141,'',0),(3135,161,1389,0,23,1397,1388,10,1068047603,1,141,'',0),(3134,161,1397,0,22,1396,1389,10,1068047603,1,141,'',0),(3133,161,1396,0,21,1395,1397,10,1068047603,1,141,'',0),(3132,161,1395,0,20,1394,1396,10,1068047603,1,141,'',0),(3131,161,1394,0,19,1386,1395,10,1068047603,1,141,'',0),(3130,161,1386,0,18,1393,1394,10,1068047603,1,141,'',0),(3129,161,1393,0,17,1392,1386,10,1068047603,1,141,'',0),(3128,161,1392,0,16,1391,1393,10,1068047603,1,141,'',0),(3127,161,1391,0,15,1390,1392,10,1068047603,1,141,'',0),(3126,161,1390,0,14,1389,1391,10,1068047603,1,141,'',0),(3125,161,1389,0,13,1384,1390,10,1068047603,1,141,'',0),(3124,161,1384,0,12,1383,1389,10,1068047603,1,141,'',0),(3123,161,1383,0,11,1388,1384,10,1068047603,1,141,'',0),(3122,161,1388,0,10,1387,1383,10,1068047603,1,141,'',0),(3121,161,1387,0,9,1386,1388,10,1068047603,1,141,'',0),(3120,161,1386,0,8,1385,1387,10,1068047603,1,141,'',0),(3119,161,1385,0,7,1384,1386,10,1068047603,1,141,'',0),(3118,161,1384,0,6,1383,1385,10,1068047603,1,141,'',0),(3117,161,1383,0,5,1382,1384,10,1068047603,1,141,'',0),(3116,161,1382,0,4,944,1383,10,1068047603,1,141,'',0),(3115,161,944,0,3,943,1382,10,1068047603,1,141,'',0),(3114,161,943,0,2,1381,944,10,1068047603,1,141,'',0),(3113,161,1381,0,1,934,943,10,1068047603,1,140,'',0),(3112,161,934,0,0,0,1381,10,1068047603,1,140,'',0),(3066,14,1361,0,3,1360,1316,4,1033920830,2,198,'',0),(3065,14,1360,0,2,1359,1361,4,1033920830,2,197,'',0),(3064,14,1359,0,1,1358,1360,4,1033920830,2,9,'',0),(3063,14,1358,0,0,0,1359,4,1033920830,2,8,'',0),(2993,206,1140,0,0,0,1318,4,1068123599,2,8,'',0),(2994,206,1318,0,1,1140,1094,4,1068123599,2,9,'',0),(2995,206,1094,0,2,1318,1319,4,1068123599,2,197,'',0),(2996,206,1319,0,3,1094,1320,4,1068123599,2,197,'',0),(2997,206,1320,0,4,1319,1316,4,1068123599,2,198,'',0),(2998,206,1316,0,5,1320,1321,4,1068123599,2,198,'',0),(2999,206,1321,0,6,1316,0,4,1068123599,2,199,'',0),(3023,149,1339,0,2,1338,1316,4,1068041016,2,197,'',0),(3022,149,1338,0,1,1337,1339,4,1068041016,2,9,'',0),(3021,149,1337,0,0,0,1338,4,1068041016,2,8,'',0),(4839,1,1925,0,0,0,0,1,1033917596,1,4,'',0),(5479,331,2280,0,2,2279,2281,5,1069318446,1,117,'',0),(5480,331,2281,0,3,2280,2249,5,1069318446,1,117,'',0),(5481,331,2249,0,4,2281,89,5,1069318446,1,117,'',0),(5482,331,89,0,5,2249,2278,5,1069318446,1,117,'',0),(5483,331,2278,0,6,89,2282,5,1069318446,1,117,'',0),(5484,331,2282,0,7,2278,0,5,1069318446,1,117,'',0),(5485,332,2283,0,0,0,2284,5,1069318482,1,116,'',0),(5486,332,2284,0,1,2283,2283,5,1069318482,1,116,'',0),(5487,332,2283,0,2,2284,2284,5,1069318482,1,117,'',0),(5488,332,2284,0,3,2283,2285,5,1069318482,1,117,'',0),(5489,332,2285,0,4,2284,2273,5,1069318482,1,117,'',0),(5490,332,2273,0,5,2285,1361,5,1069318482,1,117,'',0),(5491,332,1361,0,6,2273,0,5,1069318482,1,117,'',0),(5492,333,2286,0,0,0,2287,5,1069318517,1,116,'',0),(5493,333,2287,0,1,2286,2259,5,1069318517,1,116,'',0),(5494,333,2259,0,2,2287,2288,5,1069318517,1,117,'',0),(5495,333,2288,0,3,2259,2289,5,1069318517,1,117,'',0),(5496,333,2289,0,4,2288,2290,5,1069318517,1,117,'',0),(5497,333,2290,0,5,2289,2291,5,1069318517,1,117,'',0),(5498,333,2291,0,6,2290,0,5,1069318517,1,117,'',0),(5499,334,2292,0,0,0,2292,5,1069318560,1,116,'',0),(5500,334,2292,0,1,2292,2293,5,1069318560,1,117,'',0),(5501,334,2293,0,2,2292,2294,5,1069318560,1,117,'',0),(5502,334,2294,0,3,2293,0,5,1069318560,1,117,'',0),(5503,335,2295,0,0,0,2296,5,1069318590,1,116,'',0),(5504,335,2296,0,1,2295,2297,5,1069318590,1,117,'',0),(5505,335,2297,0,2,2296,2298,5,1069318590,1,117,'',0),(5506,335,2298,0,3,2297,2299,5,1069318590,1,117,'',0),(5507,335,2299,0,4,2298,2249,5,1069318590,1,117,'',0),(5508,335,2249,0,5,2299,2300,5,1069318590,1,117,'',0),(5509,335,2300,0,6,2249,0,5,1069318590,1,117,'',0),(5538,56,2320,0,0,0,2321,15,1066643397,11,161,'',0),(5539,56,2321,0,1,2320,2322,15,1066643397,11,224,'',0),(5540,56,2322,0,2,2321,2323,15,1066643397,11,224,'',0),(5541,56,2323,0,3,2322,2324,15,1066643397,11,224,'',0),(5542,56,2324,0,4,2323,2325,15,1066643397,11,224,'',0),(5543,56,2325,0,5,2324,2326,15,1066643397,11,224,'',0),(5544,56,2326,0,6,2325,2327,15,1066643397,11,224,'',0),(5545,56,2327,0,7,2326,0,15,1066643397,11,224,'',0),(5520,320,2249,0,2,2307,2308,28,1069317685,1,218,'',0),(5519,320,2307,0,1,2306,2249,28,1069317685,1,218,'',0),(5417,319,2245,0,0,0,2245,27,1069317649,1,215,'',0),(5418,319,2245,0,1,2245,2246,27,1069317649,1,216,'',0),(5419,319,2246,0,2,2245,0,27,1069317649,1,216,'',0),(5462,327,2270,0,2,2271,2271,5,1069317978,1,117,'',0),(5463,327,2271,0,3,2270,0,5,1069317978,1,117,'',0),(5464,328,2272,0,0,0,2265,5,1069318020,1,116,'',0),(5465,328,2265,0,1,2272,2265,5,1069318020,1,116,'',0),(5466,328,2265,0,2,2265,2273,5,1069318020,1,117,'',0),(5467,328,2273,0,3,2265,89,5,1069318020,1,117,'',0),(5468,328,89,0,4,2273,2274,5,1069318020,1,117,'',0),(5469,328,2274,0,5,89,1388,5,1069318020,1,117,'',0),(5470,328,1388,0,6,2274,2275,5,1069318020,1,117,'',0),(5471,328,2275,0,7,1388,0,5,1069318020,1,117,'',0),(5472,329,2276,0,0,0,2276,27,1069318331,1,215,'',0),(5473,329,2276,0,1,2276,2263,27,1069318331,1,216,'',0),(5474,329,2263,0,2,2276,0,27,1069318331,1,216,'',0),(5511,330,2302,0,1,2301,0,28,1069318374,1,221,'',3),(5510,330,2301,0,0,0,2302,28,1069318374,1,217,'',0),(5477,331,2278,0,0,0,2279,5,1069318446,1,116,'',0),(5478,331,2279,0,1,2278,2280,5,1069318446,1,116,'',0),(5459,326,2269,0,4,2268,0,5,1069317947,1,117,'',0),(5460,327,2270,0,0,0,2271,5,1069317978,1,116,'',0),(5461,327,2271,0,1,2270,2270,5,1069317978,1,116,'',0),(5457,326,2266,0,2,2267,2268,5,1069317947,1,117,'',0),(5456,326,2267,0,1,2266,2266,5,1069317947,1,116,'',0),(5455,326,2266,0,0,0,2267,5,1069317947,1,116,'',0),(5454,325,2264,0,6,2254,0,5,1069317907,1,117,'',0),(5518,320,2306,0,0,0,2307,28,1069317685,1,217,'',0),(5426,321,2252,0,0,0,2253,5,1069317728,1,116,'',0),(5427,321,2253,0,1,2252,89,5,1069317728,1,116,'',0),(5428,321,89,0,2,2253,2254,5,1069317728,1,117,'',0),(5429,321,2254,0,3,89,2255,5,1069317728,1,117,'',0),(5430,321,2255,0,4,2254,2252,5,1069317728,1,117,'',0),(5431,321,2252,0,5,2255,2253,5,1069317728,1,117,'',0),(5432,321,2253,0,6,2252,0,5,1069317728,1,117,'',0),(5433,322,2256,0,0,0,2257,5,1069317767,1,116,'',0),(5434,322,2257,0,1,2256,89,5,1069317767,1,116,'',0),(5435,322,89,0,2,2257,2256,5,1069317767,1,117,'',0),(5436,322,2256,0,3,89,2258,5,1069317767,1,117,'',0),(5437,322,2258,0,4,2256,2259,5,1069317767,1,117,'',0),(5438,322,2259,0,5,2258,2260,5,1069317767,1,117,'',0),(5439,322,2260,0,6,2259,0,5,1069317767,1,117,'',0),(5440,323,2261,0,0,0,2253,5,1069317797,1,116,'',0),(5441,323,2253,0,1,2261,2261,5,1069317797,1,116,'',0),(5442,323,2261,0,2,2253,2253,5,1069317797,1,117,'',0),(5443,323,2253,0,3,2261,0,5,1069317797,1,117,'',0),(5527,324,2302,0,3,2263,0,28,1069317869,1,221,'',3),(5526,324,2263,0,2,2309,2302,28,1069317869,1,218,'',0),(5525,324,2309,0,1,2309,2263,28,1069317869,1,218,'',0),(5524,324,2309,0,0,0,2309,28,1069317869,1,217,'',0),(5448,325,2264,0,0,0,2265,5,1069317907,1,116,'',0),(5449,325,2265,0,1,2264,2265,5,1069317907,1,116,'',0),(5450,325,2265,0,2,2265,1388,5,1069317907,1,117,'',0),(5451,325,1388,0,3,2265,89,5,1069317907,1,117,'',0),(5452,325,89,0,4,1388,2254,5,1069317907,1,117,'',0),(5453,325,2254,0,5,89,2264,5,1069317907,1,117,'',0),(4992,268,1987,0,15,1986,0,2,1068814752,1,121,'',0),(4991,268,1986,0,14,1986,1987,2,1068814752,1,121,'',0),(4990,268,1986,0,13,1986,1986,2,1068814752,1,121,'',0),(4989,268,1986,0,12,2014,1986,2,1068814752,1,121,'',0),(4988,268,2014,0,11,2005,1986,2,1068814752,1,121,'',0),(4987,268,2005,0,10,2004,2014,2,1068814752,1,121,'',0),(4986,268,2004,0,9,1987,2005,2,1068814752,1,121,'',0),(4985,268,1987,0,8,2013,2004,2,1068814752,1,121,'',0),(4984,268,2013,0,7,2012,1987,2,1068814752,1,121,'',0),(4983,268,2012,0,6,2011,2013,2,1068814752,1,120,'',0),(4982,268,2011,0,5,1990,2012,2,1068814752,1,120,'',0),(4981,268,1990,0,4,2010,2011,2,1068814752,1,120,'',0),(4980,268,2010,0,3,2009,1990,2,1068814752,1,120,'',0),(4979,268,2009,0,2,2008,2010,2,1068814752,1,120,'',0),(4978,268,2008,0,1,2007,2009,2,1068814752,1,1,'',0),(4977,268,2007,0,0,0,2008,2,1068814752,1,1,'',0),(4976,267,2007,0,1,2006,0,1,1068814364,1,119,'',0),(4975,267,2006,0,0,0,2007,1,1068814364,1,4,'',0);
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
INSERT INTO ezsearch_return_count VALUES (1,1,1066398569,1),(2,2,1066909621,1),(3,3,1066910511,1),(4,4,1066912239,1),(5,5,1066982534,1),(6,6,1066991890,4),(7,6,1066992837,4),(8,6,1066992963,4),(9,6,1066992972,0),(10,6,1066993049,0),(11,6,1066993056,4),(12,6,1066993091,4),(13,6,1066993127,4),(14,6,1066993135,4),(15,6,1066993895,4),(16,6,1066993946,4),(17,6,1066993995,4),(18,6,1066994001,4),(19,6,1066994050,4),(20,6,1066994057,4),(21,6,1066994067,4),(22,7,1066996820,0),(23,5,1066997190,1),(24,5,1066997194,1),(25,8,1066998830,1),(26,8,1066998836,1),(27,8,1066998870,1),(28,9,1066998915,1),(29,10,1067003146,0),(30,11,1067003155,2),(31,6,1067005771,4),(32,6,1067005777,4),(33,6,1067005801,4),(34,12,1067006770,1),(35,12,1067006774,1),(36,12,1067006777,1),(37,12,1067006787,1),(38,12,1067006803,1),(39,12,1067006996,1),(40,12,1067008585,1),(41,12,1067008597,1),(42,12,1067008602,0),(43,12,1067008608,1),(44,12,1067008613,0),(45,12,1067008620,0),(46,12,1067008625,0),(47,12,1067008629,1),(48,12,1067008655,1),(49,12,1067008659,0),(50,12,1067008663,0),(51,12,1067008667,0),(52,12,1067008711,0),(53,12,1067008717,0),(54,12,1067008720,1),(55,12,1067008725,0),(56,12,1067008920,1),(57,12,1067008925,1),(58,12,1067008929,0),(59,12,1067008934,1),(60,12,1067009005,1),(61,12,1067009023,1),(62,12,1067009042,1),(63,12,1067009051,0),(64,13,1067009056,1),(65,14,1067009067,0),(66,14,1067009073,0),(67,13,1067009594,1),(68,13,1067009816,1),(69,13,1067009953,1),(70,13,1067010181,1),(71,13,1067010352,1),(72,13,1067010359,1),(73,13,1067010370,1),(74,13,1067010509,1),(75,6,1067241668,5),(76,6,1067241727,5),(77,6,1067241742,5),(78,6,1067241760,5),(79,6,1067241810,5),(80,6,1067241892,5),(81,6,1067241928,5),(82,6,1067241953,5),(83,14,1067252984,0),(84,14,1067252987,0),(85,14,1067253026,0),(86,14,1067253160,0),(87,14,1067253218,0),(88,14,1067253285,0),(89,5,1067520640,1),(90,5,1067520646,1),(91,5,1067520658,1),(92,5,1067520704,0),(93,5,1067520753,0),(94,5,1067520761,1),(95,5,1067520769,1),(96,5,1067521324,1),(97,5,1067521402,1),(98,5,1067521453,1),(99,5,1067521532,1),(100,5,1067521615,1),(101,5,1067521674,1),(102,5,1067521990,1),(103,5,1067522592,1),(104,5,1067522620,1),(105,5,1067522888,1),(106,5,1067522987,1),(107,5,1067523012,1),(108,5,1067523144,1),(109,5,1067523213,1),(110,5,1067523261,1),(111,5,1067523798,1),(112,5,1067523805,1),(113,5,1067523820,1),(114,5,1067523858,1),(115,5,1067524474,1),(116,5,1067524629,1),(117,5,1067524696,1),(118,15,1067526426,0),(119,15,1067526433,0),(120,15,1067526701,0),(121,15,1067527009,0),(122,5,1067527022,1),(123,5,1067527033,1),(124,5,1067527051,1),(125,5,1067527069,1),(126,5,1067527076,0),(127,5,1067527124,1),(128,5,1067527176,1),(129,16,1067527188,0),(130,16,1067527227,0),(131,16,1067527244,0),(132,16,1067527301,0),(133,5,1067527315,0),(134,5,1067527349,0),(135,5,1067527412,0),(136,5,1067527472,1),(137,5,1067527502,1),(138,5,1067527508,0),(139,17,1067527848,0),(140,5,1067527863,1),(141,5,1067527890,1),(142,5,1067527906,1),(143,5,1067527947,1),(144,5,1067527968,0),(145,5,1067527993,0),(146,5,1067528010,1),(147,5,1067528029,0),(148,5,1067528045,0),(149,5,1067528050,0),(150,5,1067528056,0),(151,5,1067528061,0),(152,5,1067528063,0),(153,18,1067528100,1),(154,18,1067528113,0),(155,18,1067528190,1),(156,18,1067528236,1),(157,18,1067528270,1),(158,18,1067528309,1),(159,5,1067528323,0),(160,18,1067528334,1),(161,18,1067528355,1),(162,5,1067528368,0),(163,5,1067528377,1),(164,19,1067528402,0),(165,19,1067528770,0),(166,19,1067528924,0),(167,19,1067528963,0),(168,19,1067529028,0),(169,19,1067529054,0),(170,19,1067529119,0),(171,19,1067529169,0),(172,19,1067529211,0),(173,19,1067529263,0),(174,20,1067943156,3),(175,4,1067943454,1),(176,4,1067943503,1),(177,4,1067943525,1),(178,21,1067943559,1),(179,21,1067945657,1),(180,21,1067945693,1),(181,21,1067945697,1),(182,21,1067945707,1),(183,22,1067945890,0),(184,20,1067945898,3),(185,23,1067946301,6),(186,24,1067946325,1),(187,24,1067946432,1),(188,25,1067946484,4),(189,26,1067946492,1),(190,27,1067946577,1),(191,25,1067946691,4),(192,4,1067946702,1),(193,4,1067947201,1),(194,4,1067947228,1),(195,4,1067948201,1),(196,5,1068028867,0),(197,12,1068028883,0),(198,28,1068028898,2),(199,5,1068040205,0),(200,29,1068048420,0),(201,29,1068048455,1),(202,30,1068048466,0),(203,29,1068048480,0),(204,30,1068048487,2),(205,29,1068048592,0),(206,30,1068048615,2),(207,30,1068048653,2),(208,30,1068048698,2),(209,30,1068048707,2),(210,30,1068048799,2),(211,30,1068048825,2),(212,30,1068048830,2),(213,30,1068048852,2),(214,30,1068048874,2),(215,30,1068048890,2),(216,30,1068048918,2),(217,30,1068048928,2),(218,31,1068048940,2),(219,31,1068048964,2),(220,20,1068049003,0),(221,20,1068049007,2),(222,25,1068049014,3),(223,25,1068049043,3),(224,25,1068049062,3),(225,25,1068049082,3),(226,32,1068112266,5),(227,30,1068468248,3),(228,33,1068714725,2),(229,34,1068719240,1),(230,34,1068719687,1),(231,34,1068719760,1),(232,34,1068719777,1),(233,35,1068719791,1),(234,35,1068723985,1),(235,34,1068727787,1),(236,36,1068728624,1),(237,36,1068728739,1),(238,36,1068728770,1),(239,36,1068728776,1),(240,36,1068728947,1),(241,36,1068728959,1),(242,36,1068728966,1),(243,36,1068728973,1),(244,36,1068728982,1),(245,36,1068729022,1),(246,36,1068729042,1),(247,35,1068731397,1),(248,35,1068734726,1),(249,35,1068734805,1),(250,35,1068734826,1),(251,37,1068734855,0),(252,35,1068734862,1),(253,35,1068735020,1),(254,38,1068735027,0),(255,35,1068735649,1),(256,35,1068735665,1),(257,35,1068735532,1),(258,36,1068735742,1),(259,39,1068735756,0),(260,35,1068735761,1),(261,35,1068738591,1),(262,35,1068796351,1),(263,40,1068796826,1),(264,41,1068804100,1),(265,42,1068806711,1),(266,42,1068806856,1),(267,42,1068806950,1),(268,42,1068806958,1),(269,42,1068806981,1),(270,41,1068807050,1),(271,43,1068810116,1),(272,43,1068812890,1),(273,41,1068812933,2),(274,41,1068813046,2),(275,41,1068813057,2),(276,41,1068813256,3),(277,41,1068813302,3),(278,41,1068814045,3),(279,41,1068814063,3),(280,41,1068814205,3),(281,44,1068816081,0),(282,45,1068816092,2),(283,46,1068816105,0),(284,43,1068816698,1),(285,41,1068817211,3),(286,47,1068817228,3),(287,48,1068820591,1),(288,30,1068823181,2),(289,30,1069066282,2),(290,49,1069162332,1),(291,49,1069162386,1),(292,49,1069162400,1),(293,50,1069162414,0),(294,50,1069162534,0),(295,50,1069162544,0),(296,50,1069162583,0),(297,50,1069162599,0),(298,50,1069162619,0),(299,50,1069162658,0),(300,50,1069162663,0),(301,50,1069162673,0),(302,50,1069162686,0),(303,51,1069162695,1),(304,51,1069162757,1),(305,51,1069162853,1),(306,52,1069162867,1),(307,53,1069162874,1),(308,54,1069162903,1),(309,54,1069162941,1),(310,54,1069162992,1),(311,54,1069163198,1),(312,54,1069163208,1),(313,54,1069163271,1),(314,54,1069163329,1),(315,54,1069165505,2),(316,54,1069165526,2),(317,54,1069165545,2),(318,54,1069165577,2),(319,54,1069165581,2),(320,54,1069165595,2),(321,54,1069165682,2),(322,54,1069165688,2),(323,54,1069165789,2),(324,54,1069165802,2),(325,54,1069165812,2),(326,54,1069165828,2),(327,54,1069165861,2),(328,54,1069165917,2),(329,54,1069165935,2),(330,54,1069165984,2),(331,54,1069166159,2),(332,54,1069166165,2),(333,54,1069166197,2),(334,54,1069166204,2),(335,54,1069166231,2),(336,54,1069166247,2),(337,54,1069166304,2),(338,54,1069166317,2),(339,54,1069166329,2),(340,54,1069166335,2),(341,54,1069166385,2),(342,54,1069166434,2),(343,54,1069166439,2),(344,54,1069166458,2),(345,54,1069166482,2),(346,53,1069166508,1),(347,55,1069166514,2),(348,56,1069166539,1),(349,57,1069166545,1),(350,57,1069166553,1),(351,56,1069166574,1),(352,31,1069166585,1),(353,31,1069166881,1),(354,54,1069169039,2),(355,54,1069232921,2),(356,54,1069233985,2),(357,54,1069234000,2),(358,54,1069234003,2),(359,54,1069234029,2),(360,54,1069234124,2),(361,54,1069234194,2),(362,54,1069234212,2),(363,54,1069234212,2),(364,54,1069234221,2),(365,54,1069234244,2),(366,54,1069234258,2),(367,54,1069234274,2),(368,54,1069234284,2),(369,54,1069234288,2),(370,54,1069234311,2),(371,5,1069237902,0),(372,54,1069238817,4),(373,54,1069238888,4),(374,54,1069238892,4),(375,54,1069239264,5),(376,54,1069239462,5),(377,54,1069239518,5),(378,54,1069239553,5),(379,54,1069239595,5),(380,54,1069239622,5),(381,54,1069239637,5),(382,54,1069239647,5),(383,54,1069239656,5),(384,54,1069239664,5),(385,58,1069239674,1),(386,54,1069242677,5),(387,58,1069250301,1),(388,58,1069250353,1),(389,59,1069254083,1),(390,60,1069321322,0),(391,61,1069321326,1);
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
INSERT INTO ezsearch_search_phrase VALUES (1,'documents'),(2,'wenyue'),(3,'xxx'),(4,'release'),(5,'test'),(6,'ez'),(7,'f1'),(8,'bjørn'),(9,'abb'),(10,'2-2'),(11,'3.2'),(12,'bård'),(13,'Vidar'),(14,'tewtet'),(15,'dcv'),(16,'gr'),(17,'tewt'),(18,'members'),(19,'regte'),(20,'news'),(21,'german'),(22,'info'),(23,'information'),(24,'folder'),(25,'about'),(26,'2'),(27,'systems'),(28,'the'),(29,'football'),(30,'foo'),(31,'my'),(32,'reply'),(33,'today'),(34,'no comments'),(35,'car'),(36,'pirate'),(37,'carre'),(38,'cards'),(39,'cat'),(40,'overslept'),(41,'egg'),(42,'marygold'),(43,'green'),(44,'gold'),(45,'a'),(46,'flower'),(47,'nice'),(48,'lucky'),(49,'crash'),(50,'creep*'),(51,'creepers'),(52,'blå'),(53,'cars'),(54,'games'),(55,'nature'),(56,'animals'),(57,'flowers'),(58,'outcast'),(59,'need'),(60,'speed'),(61,'speeding');
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
INSERT INTO ezsearch_word VALUES (6,'media',1),(7,'setup',3),(2184,'grouplist',1),(2183,'class',1),(2182,'classes',1),(11,'links',1),(25,'content',2),(34,'feel',2),(33,'and',2),(32,'look',2),(1463,'platea',1),(1462,'habitasse',1),(1461,'hac',1),(934,'about',1),(1460,'fringilla',1),(89,'a',6),(2252,'blue',1),(1459,'nonummy',1),(1094,'music',1),(1458,'fermentum',1),(1457,'vestibulum',1),(1456,'erat',1),(1455,'vitae',1),(1454,'mi',1),(1453,'magna',1),(1465,'duis',1),(1452,'tempor',1),(1451,'lectus',1),(1464,'dictumst',1),(1340,'kghjohtkæ',1),(1339,'director',1),(2181,'cache',1),(2293,'the',1),(1450,'rhoncus',1),(1449,'nunc',1),(1448,'lacus',1),(1447,'accumsan',1),(1446,'vehicula',1),(1445,'velit',1),(1444,'elementum',1),(1443,'tellus',1),(1442,'suscipit',1),(1441,'commodo',1),(1440,'sagittis',1),(1439,'enim',1),(1438,'vel',1),(1925,'gallery',1),(1321,'sig',1),(1320,'oslo',1),(1319,'guru',1),(1318,'farstad',1),(1362,'developer',1),(1316,'norway',3),(1361,'skien',2),(1437,'felis',1),(1360,'uberguru',1),(1359,'user',1),(571,'doe',1),(570,'john',1),(572,'vid',1),(573,'la',1),(1436,'ullamcorper',1),(1435,'pellentesque',1),(1434,'fusce',1),(1433,'tortor',1),(1432,'scelerisque',1),(1431,'pharetra',1),(1430,'aenean',1),(1429,'facilisis',1),(1428,'ut',1),(1427,'tristique',1),(1426,'eros',1),(1425,'turpis',1),(1424,'eu',1),(1423,'metus',1),(1422,'blandit',1),(1421,'ac',1),(1420,'neque',1),(1419,'dapibus',1),(1418,'volutpat',1),(1417,'iaculis',1),(1416,'id',1),(1415,'purus',1),(1414,'imperdiet',1),(1413,'phasellus',1),(1412,'libero',1),(1411,'at',1),(1410,'tincidunt',1),(1409,'molestie',1),(1408,'eget',1),(1407,'dignissim',1),(1406,'est',1),(1405,'proin',1),(1404,'odio',1),(1403,'morbi',1),(1402,'nulla',1),(1401,'et',1),(1400,'wisi',1),(1399,'diam',1),(1398,'gravida',1),(1397,'aliquam',1),(1396,'quam',1),(1395,'nisl',1),(1394,'eleifend',1),(1393,'sed',1),(1392,'mauris',1),(1391,'egestas',1),(1390,'maecenas',1),(1389,'massa',1),(1388,'in',3),(1387,'elit',1),(1386,'adipiscing',1),(1385,'consectetuer',1),(1384,'amet',1),(1383,'sit',1),(944,'ipsum',1),(943,'lorem',1),(1382,'dolor',1),(1381,'me',1),(2186,'56',1),(2185,'edit',1),(2294,'cat',1),(2187,'url',1),(2188,'translator',1),(2189,'urltranslator',1),(1338,'yu',1),(1337,'wenyue',1),(1358,'administrator',1),(1140,'bård',1),(1466,'interdum',1),(1467,'ornare',1),(1468,'non',1),(1469,'sapien',1),(1470,'facilisi',1),(1471,'suspendisse',1),(1472,'nec',1),(1473,'congue',1),(1474,'sem',1),(1475,'viverra',1),(1476,'consequat',1),(1477,'donec',1),(1478,'nam',1),(1479,'bibendum',1),(1480,'dui',1),(1481,'porttitor',1),(1482,'integer',1),(1483,'cursus',1),(1484,'quis',1),(1485,'laoreet',1),(2284,'wheel',1),(2285,'statue',1),(2286,'green',1),(2287,'clover',1),(2288,'it',1),(2289,'s',1),(2290,'called',1),(2254,'small',2),(2255,'nice',1),(2256,'purple',1),(2257,'haze',1),(2258,'one',1),(2259,'actually',2),(2245,'nature',1),(2246,'images',1),(2308,'various',1),(2307,'pictures',1),(2249,'of',3),(2306,'flowers',1),(2298,'legal',1),(2297,'withing',1),(2296,'all',1),(2302,'3',3),(2292,'mjaurits',1),(2291,'gaukesyre',1),(2300,'course',1),(2299,'limits',1),(2295,'speeding',1),(2267,'skyline',1),(2268,'by',1),(2269,'night',1),(2270,'foggy',1),(2271,'trees',1),(2272,'water',1),(2273,'from',2),(2274,'lake',1),(2275,'kongsberg',1),(2276,'abstract',1),(2301,'misc',1),(2278,'cvs',1),(2279,'branching',1),(2280,'visual',1),(2281,'representation',1),(2282,'branch',1),(2265,'reflection',2),(2264,'pond',1),(2260,'two',1),(2261,'yellow',1),(2309,'landscape',1),(2263,'photography',2),(2283,'gear',1),(2266,'ormevika',1),(2253,'flower',2),(2327,'2003',1),(2326,'1999',1),(2325,'as',1),(2324,'systems',1),(2320,'gallery_package',1),(2321,'copyright',1),(2322,'&copy',1),(2323,'ez',1),(2014,'fd',1),(2013,'dfg',1),(2012,'sgf',1),(2011,'gd',1),(2010,'sdfgsdf',1),(2009,'dfsdfg',1),(2008,'sdfgsdgf',1),(2007,'latest',2),(2006,'news',1),(2005,'gh',1),(2004,'ghdf',1),(1990,'gsdf',1),(1987,'df',1),(1986,'dfgh',1);
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
INSERT INTO ezsession VALUES ('e2e8d02c8370177806cb6c47d91a2012',1069510456,'eZUserInfoCache_Timestamp|i:1069144919;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069165365;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069251254;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}eZUserDiscountRulesTimestamp|i:1069151302;eZUserDiscountRules10|a:0:{}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}}userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/209\";eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069149307;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:7:\"Comment\";}i:10;a:2:{s:2:\"id\";s:2:\"27\";s:4:\"name\";s:7:\"Gallery\";}i:11;a:2:{s:2:\"id\";s:2:\"28\";s:4:\"name\";s:5:\"Album\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserDiscountRules14|a:0:{}eZUserLoggedInID|s:2:\"14\";UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}'),('84c7a7721fa8abbf12d281c35c254aa3',1069501896,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069143585;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069143585;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069242674;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}}userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/228\";eZUserDiscountRulesTimestamp|i:1069152093;eZUserDiscountRules10|a:0:{}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}'),('04de756533b1e3b45f47e2aa4e28304f',1069683852,''),('4fa270fd68c3515c7bc0e18f8478e58a',1069683852,''),('83d1d0b9bdb4cee51afc42c0c17fab4a',1069683965,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069424692;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069424692;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069424692;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:2:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}}eZUserDiscountRulesTimestamp|i:1069424692;eZUserDiscountRules10|a:0:{}userLimitationValues|a:2:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}'),('eb5f781412fa257b5bee025a0a5ef194',1069687193,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069425526;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425526;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069425526;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}}eZUserDiscountRulesTimestamp|i:1069425526;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/264\";userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}}'),('6b757a80dcd2886681c0a2dc420526f6',1069687585,'LastAccessesURI|s:22:\"/content/view/full/263\";eZUserInfoCache_Timestamp|i:1068468222;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069329307;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069426460;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}userLimitations|a:3:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:379;a:0:{}i:380;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"299\";s:9:\"policy_id\";s:3:\"380\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:2:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}i:299;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"585\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"586\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"587\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"588\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"589\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"590\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"591\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"592\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1068804650;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:7:\"Comment\";}i:10;a:2:{s:2:\"id\";s:2:\"27\";s:4:\"name\";s:7:\"Gallery\";}i:11;a:2:{s:2:\"id\";s:2:\"28\";s:4:\"name\";s:5:\"Album\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|s:2:\"on\";FromGroupID|s:1:\"3\";CurrentViewMode|s:4:\"full\";ContentNodeID|s:1:\"2\";ContentObjectID|s:1:\"1\";DeleteIDArray|a:2:{i:0;s:3:\"231\";i:1;s:3:\"238\";}DeleteClassIDArray|a:3:{i:0;s:2:\"23\";i:1;s:2:\"24\";i:2;s:2:\"25\";}DisableRoleCache|i:1;BrowseParameters|a:14:{s:11:\"action_name\";s:26:\"NewObjectAddNodeAssignment\";s:20:\"description_template\";s:41:\"design:content/browse_first_placement.tpl\";s:4:\"keys\";a:2:{s:5:\"class\";s:2:\"26\";s:10:\"classgroup\";a:1:{i:0;s:1:\"1\";}}s:15:\"persistent_data\";a:1:{s:7:\"ClassID\";s:2:\"26\";}s:7:\"content\";a:1:{s:8:\"class_id\";s:2:\"26\";}s:9:\"from_page\";s:15:\"/content/action\";s:4:\"type\";s:26:\"NewObjectAddNodeAssignment\";s:9:\"selection\";s:6:\"single\";s:11:\"return_type\";s:6:\"NodeID\";s:20:\"browse_custom_action\";b:0;s:18:\"custom_action_data\";b:0;s:10:\"start_node\";s:1:\"2\";s:12:\"ignore_nodes\";a:0:{}s:15:\"top_level_nodes\";a:4:{i:0;s:1:\"2\";i:1;s:1:\"5\";i:2;s:2:\"43\";i:3;b:0;}}InformationCollectionMap|a:1:{i:227;i:25;}DiscardObjectID|s:3:\"313\";DiscardObjectVersion|s:1:\"1\";DiscardObjectLanguage|b:0;DiscardConfirm|b:1;eZUserDiscountRulesTimestamp|i:1069318627;eZUserDiscountRules14|a:0:{}eZUserDiscountRules10|a:0:{}eZUserLoggedInID|s:2:\"14\";UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}'),('3ce082d1475a953c88befcf96a33a300',1069581365,'LastAccessesURI|s:22:\"/content/view/full/259\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069321950;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069321950;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069322118;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069321950;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}}userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}'),('e22e7d2e4dad027d148e6f9575f511cf',1069579903,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069319780;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069319780;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069319780;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069319780;eZUserDiscountRules10|a:0:{}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}}userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/259\";'),('d271f6d685ca1201f3ed151127255ff3',1069593392,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069320995;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069320995;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069331691;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069320995;eZUserDiscountRules10|a:0:{}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}}userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/264\";UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}'),('120beff7ff85218a2ae586f44d863601',1069589537,'LastAccessesURI|s:22:\"/content/view/full/250\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069325613;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069325613;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069330299;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069325613;eZUserDiscountRules10|a:0:{}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}}userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}'),('f3902aa19871fbab578e2c56c58301e1',1069601905,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069342476;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069342476;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069342476;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069342476;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}}LastAccessesURI|s:22:\"/content/view/full/257\";'),('3ea970eef16ec8060c93c619d6cd7fa3',1069947124,'eZUserGroupsCache_Timestamp|i:1069429957;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069429957;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069429957;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069429959;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:7:\"Comment\";}i:10;a:2:{s:2:\"id\";s:2:\"27\";s:4:\"name\";s:7:\"Gallery\";}i:11;a:2:{s:2:\"id\";s:2:\"28\";s:4:\"name\";s:5:\"Album\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|s:2:\"on\";'),('d3a52c5fd461f5c9a3b72363143e30b3',1069922229,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069663023;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069663023;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069663023;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069663023;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}}LastAccessesURI|s:22:\"/content/view/full/248\";'),('2e08341747316bf3f8fae49c4f659e39',1069674257,'eZUserGroupsCache_Timestamp|i:1068803831;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1068803831;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069415024;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069414678;canInstantiateClasses|i:1;canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:7:\"Comment\";}i:10;a:2:{s:2:\"id\";s:2:\"27\";s:4:\"name\";s:7:\"Gallery\";}i:11;a:2:{s:2:\"id\";s:2:\"28\";s:4:\"name\";s:5:\"Album\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|s:2:\"on\";LastAccessesURI|s:22:\"/content/view/full/232\";eZUserDiscountRulesTimestamp|i:1068803918;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}FromGroupID|b:0;classesCachedForUser|s:2:\"14\";userLimitations|a:7:{i:384;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"303\";s:9:\"policy_id\";s:3:\"384\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:385;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"304\";s:9:\"policy_id\";s:3:\"385\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}i:386;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"305\";s:9:\"policy_id\";s:3:\"386\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}i:387;a:0:{}i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"306\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}}userLimitationValues|a:6:{i:303;a:9:{i:0;a:3:{s:2:\"id\";s:3:\"614\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"615\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"616\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"617\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"618\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"619\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"620\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"621\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"26\";}i:8;a:3:{s:2:\"id\";s:3:\"622\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:1:\"5\";}}i:304;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"623\";s:13:\"limitation_id\";s:3:\"304\";s:5:\"value\";s:2:\"26\";}}i:305;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"624\";s:13:\"limitation_id\";s:3:\"305\";s:5:\"value\";s:2:\"26\";}}i:306;a:9:{i:0;a:3:{s:2:\"id\";s:3:\"625\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"626\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"627\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"628\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"629\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"630\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"631\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"632\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"26\";}i:8;a:3:{s:2:\"id\";s:3:\"633\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"5\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}}DisableRoleCache|i:1;'),('d1c2f971d9c03c2494e9273598058c38',1069683225,'eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069238044;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069238044;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069415071;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:6:{i:384;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"303\";s:9:\"policy_id\";s:3:\"384\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:386;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"305\";s:9:\"policy_id\";s:3:\"386\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}i:385;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"304\";s:9:\"policy_id\";s:3:\"385\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}}eZUserDiscountRulesTimestamp|i:1069238044;eZUserDiscountRules10|a:0:{}userLimitationValues|a:6:{i:303;a:9:{i:0;a:3:{s:2:\"id\";s:3:\"614\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"615\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"616\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"617\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"618\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"619\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"620\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"621\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:2:\"26\";}i:8;a:3:{s:2:\"id\";s:3:\"622\";s:13:\"limitation_id\";s:3:\"303\";s:5:\"value\";s:1:\"5\";}}i:305;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"624\";s:13:\"limitation_id\";s:3:\"305\";s:5:\"value\";s:2:\"26\";}}i:304;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"623\";s:13:\"limitation_id\";s:3:\"304\";s:5:\"value\";s:2:\"26\";}}i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/264\";eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRules14|a:0:{}'),('f01cd9ad6c05527b5a586df7a6e97468',1069684944,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069425611;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425611;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069425742;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069425611;eZUserDiscountRules10|a:0:{}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}}userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/248\";UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}'),('92f9ac3a9ada7ce1ab7428360ef806de',1069503014,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069243028;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069243029;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069243029;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:4:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:3:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}}userLimitationValues|a:3:{i:309;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:5;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"27\";}i:6;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"28\";}i:7;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/181\";'),('2ec27e55b6b0b6ad19c1676c4e83cef2',1069577478,'eZUserInfoCache_Timestamp|i:1068818305;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1068818305;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069251671;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069155434;canInstantiateClasses|i:1;userLimitations|a:1:{i:389;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"307\";s:9:\"policy_id\";s:3:\"389\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";}}}userLimitationValues|a:1:{i:307;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"634\";s:13:\"limitation_id\";s:3:\"307\";s:5:\"value\";s:2:\"26\";}}}canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:7:\"Comment\";}i:10;a:2:{s:2:\"id\";s:2:\"27\";s:4:\"name\";s:7:\"Gallery\";}i:11;a:2:{s:2:\"id\";s:2:\"28\";s:4:\"name\";s:5:\"Album\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|s:2:\"on\";eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}Preferences-advanced_menu|s:2:\"on\";LastAccessesURI|s:22:\"/content/view/full/209\";eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}BrowseParameters|a:14:{s:11:\"action_name\";s:18:\"MoveNodeAssignment\";s:20:\"description_template\";s:40:\"design:content/browse_move_placement.tpl\";s:4:\"keys\";a:4:{s:5:\"class\";s:1:\"5\";s:8:\"class_id\";s:5:\"image\";s:10:\"classgroup\";a:1:{i:0;s:1:\"3\";}s:7:\"section\";s:1:\"1\";}s:10:\"start_node\";s:3:\"181\";s:15:\"persistent_data\";a:2:{s:10:\"FromNodeID\";s:3:\"181\";s:21:\"OldAssignmentParentID\";s:3:\"181\";}s:7:\"content\";a:4:{s:9:\"object_id\";s:3:\"271\";s:16:\"previous_node_id\";s:3:\"181\";s:14:\"object_version\";s:1:\"2\";s:15:\"object_language\";b:0;}s:9:\"from_page\";s:20:\"/content/edit/271/2/\";s:4:\"type\";s:18:\"MoveNodeAssignment\";s:9:\"selection\";s:6:\"single\";s:11:\"return_type\";s:6:\"NodeID\";s:20:\"browse_custom_action\";b:0;s:18:\"custom_action_data\";b:0;s:12:\"ignore_nodes\";a:0:{}s:15:\"top_level_nodes\";a:4:{i:0;s:1:\"2\";i:1;s:1:\"5\";i:2;s:2:\"43\";i:3;b:0;}}FromGroupID|s:1:\"1\";classesCachedForUser|s:2:\"14\";UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}');
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
INSERT INTO ezurl VALUES (1,'http://ez.no',1068713677,1068713677,1,0,'dfcdb471b240d964dc3f57b998eb0533');
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
INSERT INTO ezurl_object_link VALUES (1,768,1);
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,NULL),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,NULL),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,NULL),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,NULL),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,NULL),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,NULL),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,NULL),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,NULL),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,NULL),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(193,'nature/flowers_in_june/marygold/good','63667a5fd9f62f7128802afcbfc3eaa5','content/view/full/187',1,0,0),(125,'discussions/forum_main_group/music_discussion/latest_msg_not_sticky','70cf693961dcdd67766bf941c3ed2202','content/view/full/130',1,0,0),(126,'discussions/forum_main_group/music_discussion/not_sticky_2','969f470c93e2131a0884648b91691d0b','content/view/full/131',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,213,0),(124,'discussions/forum_main_group/music_discussion/new_topic_sticky/reply','f3dd8b6512a0b04b426ef7d7307b7229','content/view/full/129',1,0,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,153,0),(123,'discussions/forum_main_group/music_discussion/new_topic_sticky','bf37b4a370ddb3935d0625a5b348dd20','content/view/full/128',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,213,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,213,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(127,'discussions/forum_main_group/music_discussion/important_sticky','2f16cf3039c97025a43f23182b4b6d60','content/view/full/132',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(74,'users/editors/john_doe','470ba5117b9390b819f7c2519c0a6092','content/view/full/91',1,0,0),(75,'users/editors/vid_la','73f7efbac10f9f69aa4f7b19c97cfb16','content/view/full/92',1,0,0),(102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0),(206,'news/latest_sdfgsdgf','decc79834f40f5a98a8694852ea55bf2','content/view/full/200',1,0,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(104,'discussions/music_discussion','09533dfccc8477debe545d31bccf391f','content/view/full/114',1,149,0),(105,'discussions/forum_main_group/music_discussion/this_is_a_new_topic','cec6b1593bf03079990a89a3fdc60c56','content/view/full/112',1,0,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(107,'discussions/sports_discussion','c551943f4df3c58a693f8ba55e9b6aeb','content/view/full/115',1,151,0),(117,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/foo_bar','741cdf9f1ee1fa974ea7ec755f538271','content/view/full/122',1,0,0),(109,'users/administrator_users/wenyue_yu','823d93f67a2868cf64fecf47ea766bce','content/view/full/117',1,0,0),(111,'discussions/forum_main_group/sports_discussion/football','6e9c09d390322aa44bb5108b93f5f17c','content/view/full/119',1,0,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,213,0),(114,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/my_reply','1e03a7609698aa8a98dccf1178df0e6f','content/view/full/120',1,0,0),(118,'discussions/forum_main_group/music_discussion/what_about_pop','c4ebc99b2ed9792d1aee0e5fe210b852','content/view/full/123',1,0,0),(119,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic','6c20d2df5a828dcdb6a4fcb4897bb643','content/view/full/124',1,0,0),(120,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','de98a1bb645ea84919a5e34688ff84e2','content/view/full/125',1,0,0),(128,'discussions/forum_main_group/sports_discussion/football/reply_2','13a443b7e046bb36831640f1d19e33d9','content/view/full/133',1,0,0),(130,'discussions/forum_main_group/music_discussion/lkj_ssssstick','75ee87c770e4e8be9d44200cdb71d071','content/view/full/135',1,0,0),(131,'discussions/forum_main_group/music_discussion/foo','12c58f35c1114deeb172aba728c50ca8','content/view/full/136',1,0,0),(132,'discussions/forum_main_group/music_discussion/lkj_ssssstick/reply','6040856b4ec5bcc1c699d95020005be5','content/view/full/137',1,0,0),(135,'discussions/forum_main_group/music_discussion/lkj_ssssstick/uyuiyui','4c48104ea6e5ec2a78067374d9561fcb','content/view/full/140',1,0,0),(136,'discussions/forum_main_group/music_discussion/test2','53f71d4ff69ffb3bf8c8ccfb525eabd3','content/view/full/141',1,0,0),(137,'discussions/forum_main_group/music_discussion/t4','5da27cda0fbcd5290338b7d22cfd730c','content/view/full/142',1,0,0),(138,'discussions/forum_main_group/music_discussion/lkj_ssssstick/klj_jkl_klj','9ae60fa076882d6807506c2232143d27','content/view/full/143',1,0,0),(139,'discussions/forum_main_group/music_discussion/test2/retest2','a17d07fbbd2d1b6d0fbbf8ca1509cd01','content/view/full/144',1,0,0),(140,'users/administrator_users/brd_farstad','875930f56fad1a5cc6fbcac4ed6d3f8d','content/view/full/145',1,0,0),(141,'discussions/forum_main_group/music_discussion/lkj_ssssstick/my_reply','1f95000d1f993ffa16a0cf83b78515bf','content/view/full/146',1,0,0),(142,'discussions/forum_main_group/music_discussion/lkj_ssssstick/retest','0686f14064a420e6ee95aabf89c4a4f2','content/view/full/147',1,0,0),(144,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf','21f0ee2122dd5264192adc15c1e69c03','content/view/full/149',1,0,0),(146,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/dfghd_fghklj','460d30ba47855079ac8605e1c8085993','content/view/full/151',1,0,0),(159,'blogs/computers/special_things_happened_today','4427c3eda2e43a04f639ef1d5f1bb71e','content/view/full/156',1,0,0),(158,'blogs/personal/today_i_got_my_new_car','ce9118c9b6c16328082445f6d8098a0d','content/view/full/155',1,0,0),(149,'discussions/forum_main_group/music_discussion','a1a79985f113d5b05b22c9686b46b175','content/view/full/114',1,0,0),(150,'discussions/music_discussion/*','2ec2a3bfcf01ad3f1323390ab26dfeac','discussions/forum_main_group/music_discussion/{1}',1,0,1),(151,'discussions/forum_main_group/sports_discussion','b68c5a82b8b2035eeee5788cb223bb7e','content/view/full/115',1,0,0),(152,'discussions/sports_discussion/*','7acbf48218ca6e1d80c267911860d34f','discussions/forum_main_group/sports_discussion/{1}',1,0,1),(153,'about_me','50793f253d2dc015e93a2f75163b0894','content/view/full/127',1,0,0),(160,'blogs/computers/special_things_happened_today/brd_farstad','4d1dddb2000bdf69e822fb41d4000919','content/view/full/157',1,0,0),(161,'blogs/computers/special_things_happened_today/bbb','afc9fd5431105082994247c0ae0992b3','content/view/full/158',1,0,0),(162,'blogs/personal/for_posteritys_sake','c6c14fe1f69ebc2a9db76192fcb204f5','content/view/full/159',1,0,0),(251,'nature/flowers/purple_haze','86cafbea1918587028e945ef4b683370','content/view/full/251',1,0,0),(190,'nature/flowers_in_june/marygold','4426134a10c2a51fe5474a277d425ca3','content/view/full/185',1,0,0),(191,'nature/flowers_in_june/marygold/brd','1fc258a3660094f111baddb66f526142','content/view/full/186',1,192,0),(192,'nature/flowers_in_june/marygold/nice_image','cb01bf081117199266b52b99e3ccfd70','content/view/full/186',1,0,0),(168,'blogs/computers/special_things_happened_today/brd','40f4dda88233928fac915274a90476b5','content/view/full/165',1,0,0),(169,'links/news/vg','ae1126bc66ec164212018a497469e3b5','content/view/full/166',1,0,0),(170,'blogs/computers/special_things_happened_today/kjh','0cca438ee3d1d3b2cdfaa9d45dbac2a7','content/view/full/167',1,0,0),(171,'links/news/sina_','68e911c6f20934bdc959741837d8d092','content/view/full/168',1,0,0),(172,'blogs/computers/new_big_discovery_today','d174bf1f78f8c3cbf985909a26880d88','content/view/full/169',1,0,0),(173,'links/software/soft_house','aa5de9806ca77bb313e748c9bcf5def8','content/view/full/170',1,0,0),(174,'blogs/computers/no_comments_on_this_one','0df10f829cc6d968d74ece063eaee683','content/view/full/171',1,0,0),(175,'blogs/computers/new_big_discovery_today/brd','2aee5cbd251dbc484e78fba61e5bb7cf','content/view/full/172',1,0,0),(261,'nature/landscape','c414de967eedae8262a7354d5e3e866a','content/view/full/253',1,0,0),(179,'blogs/computers/new_big_discovery_today/ghghj','cd10884873caf4a20621b35199f331c4','content/view/full/175',1,0,0),(194,'nature/flowers_in_june/green','9da501e5531da587ec568f73eb5c00a3','content/view/full/188',1,0,0),(181,'blogs/entertainment/a_pirates_life','bb23fe0ca4a2afc405c4a70d5ff0abd0','content/view/full/177',1,0,0),(182,'setup/look_and_feel/blog','a0aa455a1c24b5d1d0448546c83836cf','content/view/full/54',1,213,0),(183,'blogs/entertainment/a_pirates_life/kjlh','dbf2c1455eff8c6100181582298d197f','content/view/full/178',1,0,0),(184,'blogs/entertainment/a_pirates_life/kjhkjh','e73acc89936bc771971a97eb45d51c66','content/view/full/179',1,0,0),(185,'blogs/computers/i_overslept_today','9497b5cd127ce3f9f04e3d74c8fc4da5','content/view/full/180',1,0,0),(196,'people/asia_people/suchi','2b6ceb88b365cbf425b48a000442a654','content/view/full/190',1,0,0),(197,'people/asia_people/maid','4dc59141caa2b7a1cb9ec01ca94ebfc3','content/view/full/191',1,0,0),(198,'people/asia_people/ellen','f52e1d82b911e65778e70f3cc75916df','content/view/full/192',1,0,0),(199,'nature/flowers_in_june/green/nice_image','7545f6989baf13ac6bedeab474e3de9c','content/view/full/193',1,0,0),(200,'nature/flowers_in_june/green/ool','83d2ae1be41ce0d5fc0875bd94b556a1','content/view/full/194',1,0,0),(201,'nature/flowers_in_june/green/ooh','40b0363eb8880262642a4e0c42594f5c','content/view/full/195',1,0,0),(202,'nature/flowers_in_june/marygold/dsfgsdgf','03f4289cc8b98a14acc4ae78c3649025','content/view/full/196',1,0,0),(203,'nature/flowers_in_june/foo','8e80ad1e11fa10ea3fd00771c45d2a2d','content/view/full/197',1,0,0),(204,'nature/flowers_in_june/marygold/jkhjkhk','aab1582af8e975338c9221189b17d6cb','content/view/full/198',1,0,0),(205,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/199',1,0,0),(207,'nature/flowers_in_june/foo/nice_feel','3b31350a2dd3df6615cb7a36410328c5','content/view/full/201',1,0,0),(208,'people/asia_people/xiake','8281574139ed78c6ea3396616e2dfb20','content/view/full/202',1,0,0),(209,'nature/lucky','a38b40db7afdadf093ab684ed97a9bb8','content/view/full/203',1,216,0),(210,'nature/flowers_in_june/limestone','4840af0c64a8f374728205ee032f41c9','content/view/full/204',1,0,0),(211,'nature/flowers_in_june/bombwall_boz','e773dfe30e5575d1ccf6ac40f2748626','content/view/full/205',1,0,0),(212,'nature/flowers_in_june/hedgehog','f66f727f82b616a90673e04f2dc3cfff','content/view/full/206',1,0,0),(213,'setup/look_and_feel/my_gallery','da1e93305d8b5181634ebdb1319569bd','content/view/full/54',1,0,0),(215,'nature/games/cgwloading1600','2b5a3fdfd44ebecbb82164584eb7c81b','content/view/full/208',1,0,0),(216,'nature/flowers_in_june/lucky','81aac1ed0b07b6bf549ddf4a82288135','content/view/full/203',1,0,0),(250,'nature/flowers/blue_flower','5b763d7e491af63d009ac03b80239aba','content/view/full/250',1,0,0),(249,'nature__1/*','07ead2373ee62cfa2b9ab3251c499c97','nature/{1}',1,0,1),(219,'nature/games/champ01','2d1a70f41c99db78ed6f8923c5979c23','content/view/full/211',1,0,0),(220,'nature/games/cover','5bd1a87c7d91e0e069b5b324f33a2229','content/view/full/212',1,0,0),(221,'nature/games/cgimage06','869eb2ff6c14ddbf9414611a63c44b96','content/view/full/213',1,0,0),(222,'nature/animals/sky_scraper','0a6f23861db026d58d0600c36a583ec0','content/view/full/214',1,0,0),(223,'nature/animals/creepybox','37f15623b438e349897a577cba4d441b','content/view/full/215',1,0,0),(224,'nature/animals/cow','31353785d3488a6f94248b5df1c461fe','content/view/full/216',1,0,0),(258,'nature','405aaff66082ffe7231d7c1f79926c17','content/view/full/248',1,0,0),(226,'nature/my_pictures/lw0000039','70e9761265394b58ecec8d8797a402ab','content/view/full/218',1,0,0),(227,'nature/my_pictures/blomst','d070278f420a9e5b0f6c2f9a24622ad4','content/view/full/219',1,0,0),(228,'nature/flowers_in_june/lucky/nice_one','f470c036c048cba377e314209f72bd59','content/view/full/220',1,0,0),(230,'cars/broom_broom/crash','8c562c66f14b575e70041f6014a68a1e','content/view/full/222',1,0,0),(231,'nature/games/games_for_you','f23dc9c43404a57731461d243990484d','content/view/full/223',1,0,0),(233,'people/games/blurry_people','aa9a56f79db1f52b2f9333ddeca5f3fc','content/view/full/225',1,0,0),(234,'people/games/outcast','9ed4a0ecaf24cfa841e766e9c57a65fc','content/view/full/226',1,0,0),(235,'people/games/blacksmith','d185b17e748c5ae0ea463da861c3e6b8','content/view/full/227',1,0,0),(238,'games/jedi_knight/logo','9bb045dbefa56497c80667fe6589f521','content/view/full/230',1,0,0),(262,'nature/landscape/ormevika_skyline','160b1c14354a6dd8c474dfa25cc1bc2b','content/view/full/255',1,0,0),(241,'abstract/misc/green_branch','31027a374a0ab73a2f50e3b462b9d6a0','content/view/full/233',1,0,0),(242,'abstract/misc/mjaurits','e466c08f08d86491f0cdcd25f6fdec89','content/view/full/263',1,0,0),(243,'abstract/misc/gear_wheel','a9e42e6e94ea7f05fb85e81304f6c9d2','content/view/full/261',1,0,0),(244,'abstract/misc/clover','5ee1e0f9265330fb866ff5033e5566e7','content/view/full/236',1,0,0),(245,'abstract/misc/the_need_for_speed','28a175b764b7d4fe0e15823b1406ec8f','content/view/full/237',1,0,0),(259,'nature/flowers','fcf4f3ad05704e53c28c28dd615dfed1','content/view/full/249',1,0,0),(252,'nature/flowers/yellow','1794c04296bdb61ef2a829fbb2b43dbd','content/view/full/242',1,0,0),(260,'nature/flowers/yellow_flower','b4eda4f4f56369fa2335c7926bd80e7e','content/view/full/252',1,0,0),(254,'nature/landscape/pond_reflection','a44de60ea29ebe58a0daf6032835ff13','content/view/full/254',1,0,0),(255,'nature/landscape/skyline','bab64e01e92bb8021b4142b8d1175fcf','content/view/full/245',1,0,0),(256,'nature/landscape/foggy_trees','0f1800c387d13296b66c1a9dbdbeb3cd','content/view/full/256',1,0,0),(257,'nature/landscape/water','d6ecab04885623ee57b21e1be8175667','content/view/full/247',1,0,0),(263,'nature/landscape/water_reflection','a0780ff9569ed64f0ea0975190b0ec0b','content/view/full/257',1,0,0),(264,'abstract','ce28071e1a0424ba1b7956dd3853c7fb','content/view/full/258',1,0,0),(265,'abstract/misc','514b370e8de983586f80a3069b026ed0','content/view/full/259',1,0,0),(266,'abstract/misc/cvs_branching','e78f8f18386ca133abf85de1fdb99a9f','content/view/full/260',1,0,0),(267,'abstract/misc/green_clover','720c8416fde86772a895e172a36cc80d','content/view/full/262',1,0,0),(268,'abstract/misc/speeding','dccac6ce16572084195113b46fa28036','content/view/full/264',1,0,0);
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
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','bf@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(206,'bfbf','bf@piranha.no',2,'78be1382dd64e987845778e68cf04968'),(107,'john','doe@ez.no',2,'e82dc887aa749d7bc91b9bc489e61968'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(111,'vidla','vl@ez.no',2,'5289e8d223b023d527c47d58da538068'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d'),(149,'wy','wy@ez.no',2,'123a284182a7d594ba92ce7cd334dc2b'),(187,'','',1,''),(189,'','',1,'');
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

