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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentbrowserecent'
--

/*!40000 ALTER TABLE ezcontentbrowserecent DISABLE KEYS */;
LOCK TABLES ezcontentbrowserecent WRITE;
INSERT INTO ezcontentbrowserecent VALUES (35,111,99,1067006746,'foo bar corp'),(65,149,135,1068126974,'lkj ssssstick'),(49,10,12,1068112852,'Guest accounts'),(64,206,135,1068123651,'lkj ssssstick'),(142,14,12,1070976556,'Gallery editor');
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentclass'
--

/*!40000 ALTER TABLE ezcontentclass DISABLE KEYS */;
LOCK TABLES ezcontentclass WRITE;
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1069757113),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1070976505),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069414675),(12,1,'File','file','<name>',14,14,1052385472,1067353799),(27,0,'Gallery','gallery','<name>',14,14,1068803512,1069086251),(28,0,'Album','album','<name>',14,14,1068803560,1069150091),(26,0,'Comment','comment','<subject>',14,14,1068716787,1069155431),(1,1,'Folder','folder','<name>',14,14,1024392098,1068803282);
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentclass_attribute'
--

/*!40000 ALTER TABLE ezcontentclass_attribute DISABLE KEYS */;
LOCK TABLES ezcontentclass_attribute WRITE;
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(212,0,26,'comment','Comment','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',0,1),(211,0,26,'url','Homepage URL','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(221,0,28,'column','Column','ezinteger',1,1,3,0,0,4,0,0,0,0,0,'','','','','',0,1),(222,0,28,'image','Image','ezimage',0,0,4,5,0,0,0,0,0,0,0,'','','','','',0,1),(4,1,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','','',0,1),(119,1,1,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1),(210,0,26,'email','Your E-mail','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(217,0,28,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(220,0,26,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(209,0,26,'name','Your name','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(214,1,1,'','new attribute3','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(218,0,28,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(223,0,27,'column','Album columns','ezinteger',0,1,3,0,0,2,0,0,0,0,0,'','','','','',0,1),(216,0,27,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(215,0,27,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(224,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject'
--

/*!40000 ALTER TABLE ezcontentobject DISABLE KEYS */;
LOCK TABLES ezcontentobject WRITE;
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Gallery',7,0,1033917596,1068803301,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Gallery editor',2,0,1033920746,1070976447,1,''),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',5,0,1033920830,1068468219,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',11,0,1066384365,1069254603,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',12,0,1066388816,1069254903,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'My gallery',61,0,1066643397,1069842020,1,''),(268,14,1,2,'Added new gallery',2,0,1068814752,1069757082,1,''),(161,14,1,10,'About my gallery',3,0,1068047603,1069757035,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(267,14,1,1,'News',1,0,1068814364,1068814364,1,''),(115,14,11,14,'Cache',6,0,1066991725,1069254540,1,''),(116,14,11,14,'URL translator',5,0,1066992054,1069254931,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(187,14,1,4,'New User',1,0,0,0,0,''),(189,14,1,4,'New User',1,0,0,0,0,''),(320,14,1,28,'Flowers',3,0,1069317685,1069321701,1,''),(321,14,1,5,'Blue flower',1,0,1069317728,1069317728,1,''),(322,14,1,5,'Purple haze',1,0,1069317767,1069317767,1,''),(323,14,1,5,'Yellow flower',1,0,1069317797,1069317797,1,''),(324,14,1,28,'Landscape',2,0,1069317869,1069321720,1,''),(325,14,1,5,'Pond reflection',1,0,1069317907,1069317907,1,''),(326,14,1,5,'Ormevika skyline',1,0,1069317947,1069317947,1,''),(327,14,1,5,'Foggy trees',1,0,1069317978,1069317978,1,''),(328,14,1,5,'Water reflection',1,0,1069318020,1069318020,1,''),(329,14,1,27,'Abstract',1,0,1069318331,1069318331,1,''),(330,14,1,28,'Misc',2,0,1069318374,1069321636,1,''),(331,14,1,5,'CVS branching?',1,0,1069318446,1069318446,1,''),(332,14,1,5,'Gear wheel',1,0,1069318482,1069318482,1,''),(333,14,1,5,'Green clover',1,0,1069318517,1069318517,1,''),(334,14,1,5,'Mjaurits',1,0,1069318560,1069318560,1,''),(335,14,1,5,'Speeding',1,0,1069318590,1069318590,1,''),(299,14,1,5,'New Image',1,0,0,0,0,''),(300,14,1,5,'New Image',1,0,0,0,0,''),(319,14,1,27,'Nature',1,0,1069317649,1069317649,1,''),(337,14,2,4,'Gallery Editor',1,0,1070976556,1070976556,1,'');
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_attribute'
--

/*!40000 ALTER TABLE ezcontentobject_attribute DISABLE KEYS */;
LOCK TABLES ezcontentobject_attribute WRITE;
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Blog',0,0,0,0,'blog','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(103,'eng-GB',11,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069254602\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414615\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069687923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',11,43,152,'Classes',0,0,0,0,'classes','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',11,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"109\"\n            attribute_version=\"10\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',11,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(437,'eng-GB',54,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(522,'eng-GB',3,161,140,'About my gallery',0,0,0,0,'about my gallery','ezstring'),(523,'eng-GB',3,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(153,'eng-GB',61,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',61,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',61,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',61,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(1161,'eng-GB',61,56,224,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',53,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',58,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',59,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',60,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',61,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(1157,'eng-GB',52,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1158,'eng-GB',53,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1159,'eng-GB',54,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1160,'eng-GB',55,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1161,'eng-GB',56,56,224,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1,'eng-GB',7,1,4,'Gallery',0,0,0,0,'gallery','ezstring'),(2,'eng-GB',7,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',57,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069328524\">\n  <original attribute_id=\"152\"\n            attribute_version=\"56\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414734\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069414734\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069414911\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',54,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',54,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',53,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.png\"\n         suffix=\"png\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB/my_gallery.png\"\n         original_filename=\"gallery.png\"\n         mime_type=\"original\"\n         width=\"208\"\n         height=\"59\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069257589\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB/my_gallery_reference.png\"\n         mime_type=\"image/png\"\n         width=\"208\"\n         height=\"59\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069257590\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB/my_gallery_medium.png\"\n         mime_type=\"image/png\"\n         width=\"200\"\n         height=\"56\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069257590\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-53-eng-GB/my_gallery_logo.png\"\n         mime_type=\"image/png\"\n         width=\"204\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069257607\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',12,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel.png\"\n         original_filename=\"look_and_feel.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069254902\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414615\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069687923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',12,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(151,'eng-GB',52,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',52,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.png\"\n         suffix=\"png\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB/my_gallery.png\"\n         original_filename=\"gallery.png\"\n         mime_type=\"original\"\n         width=\"208\"\n         height=\"59\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069252381\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB/my_gallery_reference.png\"\n         mime_type=\"image/png\"\n         width=\"208\"\n         height=\"59\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069252383\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB/my_gallery_medium.png\"\n         mime_type=\"image/png\"\n         width=\"200\"\n         height=\"56\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069252383\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-52-eng-GB/my_gallery_logo.png\"\n         mime_type=\"image/png\"\n         width=\"204\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069252397\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(152,'eng-GB',54,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.png\"\n         suffix=\"png\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery.png\"\n         original_filename=\"gallery.png\"\n         mime_type=\"original\"\n         width=\"208\"\n         height=\"59\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069257589\">\n  <original attribute_id=\"152\"\n            attribute_version=\"53\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery_reference.png\"\n         mime_type=\"image/png\"\n         width=\"208\"\n         height=\"59\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069257590\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery_medium.png\"\n         mime_type=\"image/png\"\n         width=\"200\"\n         height=\"56\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069257590\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery_logo.png\"\n         mime_type=\"image/png\"\n         width=\"204\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069320005\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',54,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(150,'eng-GB',57,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(151,'eng-GB',57,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(153,'eng-GB',53,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',53,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',53,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',53,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(437,'eng-GB',55,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',55,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(150,'eng-GB',52,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',53,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',54,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(150,'eng-GB',55,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(151,'eng-GB',55,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(153,'eng-GB',60,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',60,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',60,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',60,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(1161,'eng-GB',60,56,224,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(152,'eng-GB',58,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069328524\">\n  <original attribute_id=\"152\"\n            attribute_version=\"57\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414734\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069414734\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069415078\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"my_gallery_small_h.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_small_h.gif\"\n         mime_type=\"image/gif\"\n         width=\"413\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069841499\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',58,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',56,56,157,'My gallery',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069328524\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414692\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069414692\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069328580\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(671,'eng-GB',54,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',55,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"160\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069325654\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_gallery_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069325655\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_gallery_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069325655\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069325675\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',55,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',55,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(1,'eng-GB',3,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',3,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',57,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',57,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',57,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',57,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1161,'eng-GB',57,56,224,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(153,'eng-GB',58,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',58,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',58,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',58,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1161,'eng-GB',58,56,224,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(1169,'eng-GB',1,337,8,'Gallery',0,0,0,0,'gallery','ezstring'),(1170,'eng-GB',1,337,9,'Editor',0,0,0,0,'editor','ezstring'),(1171,'eng-GB',1,337,12,'',0,0,0,0,'','ezuser'),(522,'eng-GB',2,161,140,'About me',0,0,0,0,'about me','ezstring'),(523,'eng-GB',2,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',2,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_me.\"\n         suffix=\"\"\n         basename=\"about_me\"\n         dirpath=\"var/blog/storage/images/about_me/524-2-eng-GB\"\n         url=\"var/blog/storage/images/about_me/524-2-eng-GB/about_me.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(151,'eng-GB',59,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',59,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-59-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-59-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069841540\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"my_gallery_small_h.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-59-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-59-eng-GB/my_gallery_small_h.gif\"\n         mime_type=\"image/gif\"\n         width=\"447\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069841542\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',59,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',59,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',59,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',59,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(1161,'eng-GB',59,56,224,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(921,'eng-GB',1,267,4,'News',0,0,0,0,'news','ezstring'),(922,'eng-GB',1,267,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Latest.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(923,'eng-GB',1,268,1,'Latest sdfgsdgf',0,0,0,0,'latest sdfgsdgf','ezstring'),(924,'eng-GB',1,268,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfsdfg sdfgsdf</line>\n    <line>gsdf</line>\n    <line>gd</line>\n    <line>sgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(925,'eng-GB',1,268,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfg</line>\n    <line>df</line>\n    <line>ghdf</line>\n    <line>gh</line>\n    <line>fd</line>\n  </paragraph>\n  <paragraph>\n    <line>dfgh</line>\n    <line>dfgh</line>\n    <line>dfgh</line>\n    <line>df</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(926,'eng-GB',1,268,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"latest_sdfgsdgf.jpg\"\n         suffix=\"jpg\"\n         basename=\"latest_sdfgsdgf\"\n         dirpath=\"var/galler/storage/images/news/latest_sdfgsdgf/926-1-eng-GB\"\n         url=\"var/galler/storage/images/news/latest_sdfgsdgf/926-1-eng-GB/latest_sdfgsdgf.jpg\"\n         original_filename=\"dscn1631.jpg\"\n         mime_type=\"original\"\n         width=\"1024\"\n         height=\"768\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(927,'eng-GB',1,268,123,'',0,0,0,0,'','ezboolean'),(1112,'eng-GB',1,323,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Yellow flower</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1113,'eng-GB',1,323,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"yellow_flower.jpg\"\n         suffix=\"jpg\"\n         basename=\"yellow_flower\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower.jpg\"\n         original_filename=\"yellow_flower.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"A yellow flower\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317797\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"yellow_flower_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069317809\"\n         is_valid=\"1\" />\n  <alias name=\"small_v\"\n         filename=\"yellow_flower_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069317825\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"yellow_flower_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318079\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"yellow_flower_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069318084\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1114,'eng-GB',1,324,217,'Landscape',0,0,0,0,'landscape','ezstring'),(1115,'eng-GB',1,324,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Landscape photography.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1116,'eng-GB',1,324,221,'',4,0,0,4,'','ezinteger'),(1117,'eng-GB',1,324,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"landscape.\"\n         suffix=\"\"\n         basename=\"landscape\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/1117-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/1117-1-eng-GB/landscape.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317845\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1118,'eng-GB',1,325,116,'Pond reflection',0,0,0,0,'pond reflection','ezstring'),(1119,'eng-GB',1,325,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Reflection in a small pond.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1120,'eng-GB',1,325,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"pond_reflection.jpg\"\n         suffix=\"jpg\"\n         basename=\"pond_reflection\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection.jpg\"\n         original_filename=\"pond_reflection.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Pond reflection\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317907\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"pond_reflection_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069318078\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"pond_reflection_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069321901\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"pond_reflection_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069321912\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1121,'eng-GB',1,326,116,'Ormevika skyline',0,0,0,0,'ormevika skyline','ezstring'),(1122,'eng-GB',1,326,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Ormevika by night</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1123,'eng-GB',1,326,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"ormevika_skyline.jpg\"\n         suffix=\"jpg\"\n         basename=\"ormevika_skyline\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline.jpg\"\n         original_filename=\"skyline.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Nice nightshot from ormevika\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317947\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"ormevika_skyline_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069318074\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"ormevika_skyline_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069318078\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"ormevika_skyline_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069321895\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"ormevika_skyline_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069321901\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1124,'eng-GB',1,327,116,'Foggy trees',0,0,0,0,'foggy trees','ezstring'),(1125,'eng-GB',1,327,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Foggy trees</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1126,'eng-GB',1,327,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"foggy_trees.jpg\"\n         suffix=\"jpg\"\n         basename=\"foggy_trees\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees.jpg\"\n         original_filename=\"trees.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Foggy trees\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317978\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"foggy_trees_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069318074\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"foggy_trees_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069318078\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"foggy_trees_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318286\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"foggy_trees_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069321894\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1127,'eng-GB',1,328,116,'Water reflection',0,0,0,0,'water reflection','ezstring'),(1128,'eng-GB',1,328,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Reflection from a lake in Kongsberg</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1129,'eng-GB',1,328,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"water_reflection.jpg\"\n         suffix=\"jpg\"\n         basename=\"water_reflection\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection.jpg\"\n         original_filename=\"water.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Water reflection\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318019\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"water_reflection_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069318073\"\n         is_valid=\"1\" />\n  <alias name=\"small_v\"\n         filename=\"water_reflection_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069318074\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"water_reflection_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318085\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"water_reflection_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069318285\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',60,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',60,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-60-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-60-eng-GB/my_gallery.gif\"\n         original_filename=\"forum.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069841692\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"my_gallery_small_h.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-60-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-60-eng-GB/my_gallery_small_h.gif\"\n         mime_type=\"image/gif\"\n         width=\"447\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069841693\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',61,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',61,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_gallery.gif\"\n         suffix=\"gif\"\n         basename=\"my_gallery\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB/my_gallery.gif\"\n         original_filename=\"gallery.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069842020\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"my_gallery_small_h.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB/my_gallery_small_h.gif\"\n         mime_type=\"image/gif\"\n         width=\"447\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069842022\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_gallery_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB\"\n         url=\"var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB/my_gallery_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1220513676\"\n         timestamp=\"1069842044\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',5,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',5,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"324\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',5,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',5,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',10,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',10,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"103\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',10,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',10,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(110,'eng-GB',11,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',11,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',4,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',4,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"328\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',4,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',4,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(153,'eng-GB',56,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',56,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',56,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',56,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1130,'eng-GB',1,329,215,'Abstract',0,0,0,0,'abstract','ezstring'),(1131,'eng-GB',1,329,216,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Abstract photography</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1132,'eng-GB',1,329,223,'',2,0,0,2,'','ezinteger'),(1133,'eng-GB',1,330,217,'Misc',0,0,0,0,'misc','ezstring'),(1134,'eng-GB',1,330,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(1135,'eng-GB',1,330,221,'',4,0,0,4,'','ezinteger'),(1136,'eng-GB',1,330,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"misc.\"\n         suffix=\"\"\n         basename=\"misc\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/1136-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/1136-1-eng-GB/misc.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318368\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1137,'eng-GB',1,331,116,'CVS branching?',0,0,0,0,'cvs branching?','ezstring'),(1138,'eng-GB',1,331,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Visual representation of a CVS branch.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1139,'eng-GB',1,331,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cvs_branching.jpg\"\n         suffix=\"jpg\"\n         basename=\"cvs_branching\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching.jpg\"\n         original_filename=\"branch.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"CVS branch\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318446\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"cvs_branching_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069842041\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"cvs_branching_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069322030\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cvs_branching_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069322302\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1140,'eng-GB',1,332,116,'Gear wheel',0,0,0,0,'gear wheel','ezstring'),(1141,'eng-GB',1,332,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Gear wheel statue from Skien</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1142,'eng-GB',1,332,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"gear_wheel.jpg\"\n         suffix=\"jpg\"\n         basename=\"gear_wheel\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel.jpg\"\n         original_filename=\"gear_wheel.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Gear wheel\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318481\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"gear_wheel_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069426469\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"gear_wheel_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318980\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"gear_wheel_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069322029\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1143,'eng-GB',1,333,116,'Green clover',0,0,0,0,'green clover','ezstring'),(1144,'eng-GB',1,333,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Actually it&apos;s called gaukesyre</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1145,'eng-GB',1,333,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"green_clover.jpg\"\n         suffix=\"jpg\"\n         basename=\"green_clover\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover.jpg\"\n         original_filename=\"green_clover.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Gren clover\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318517\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"green_clover_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069426472\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"green_clover_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069842043\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"green_clover_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318965\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"green_clover_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069318979\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1146,'eng-GB',1,334,116,'Mjaurits',0,0,0,0,'mjaurits','ezstring'),(1147,'eng-GB',1,334,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Mjaurits the cat.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1148,'eng-GB',1,334,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"mjaurits.jpg\"\n         suffix=\"jpg\"\n         basename=\"mjaurits\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits.jpg\"\n         original_filename=\"cat.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"A closeup of the cat Mjaurits\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318560\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"mjaurits_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069426471\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"mjaurits_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069842043\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"mjaurits_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069427992\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"mjaurits_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069318965\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1149,'eng-GB',1,335,116,'Speeding',0,0,0,0,'speeding','ezstring'),(1150,'eng-GB',1,335,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>All withing legal limits, of course.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1151,'eng-GB',1,335,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"speeding.jpg\"\n         suffix=\"jpg\"\n         basename=\"speeding\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding.jpg\"\n         original_filename=\"speed.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Speed\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318589\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_v\"\n         filename=\"speeding_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069426471\"\n         is_valid=\"1\" />\n  <alias name=\"small_h\"\n         filename=\"speeding_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069842042\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"speeding_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069427991\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"speeding_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318965\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1133,'eng-GB',2,330,217,'Misc',0,0,0,0,'misc','ezstring'),(1134,'eng-GB',2,330,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(1135,'eng-GB',2,330,221,'',3,0,0,3,'','ezinteger'),(1136,'eng-GB',2,330,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"misc.\"\n         suffix=\"\"\n         basename=\"misc\"\n         dirpath=\"var/gallery/storage/images/abstract/misc/1136-2-eng-GB\"\n         url=\"var/gallery/storage/images/abstract/misc/1136-2-eng-GB/misc.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069318368\">\n  <original attribute_id=\"1136\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1101,'eng-GB',2,320,217,'Flowers',0,0,0,0,'flowers','ezstring'),(1102,'eng-GB',2,320,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Pictures of various flowers.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1103,'eng-GB',2,320,221,'',3,0,0,3,'','ezinteger'),(1104,'eng-GB',2,320,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"flowers.\"\n         suffix=\"\"\n         basename=\"flowers\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/1104-2-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/1104-2-eng-GB/flowers.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317669\">\n  <original attribute_id=\"1104\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1101,'eng-GB',3,320,217,'Flowers',0,0,0,0,'flowers','ezstring'),(1102,'eng-GB',3,320,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Pictures of various flowers.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1103,'eng-GB',3,320,221,'',3,0,0,3,'','ezinteger'),(1104,'eng-GB',3,320,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"flowers.\"\n         suffix=\"\"\n         basename=\"flowers\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/1104-3-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/1104-3-eng-GB/flowers.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317669\">\n  <original attribute_id=\"1104\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1114,'eng-GB',2,324,217,'Landscape',0,0,0,0,'landscape','ezstring'),(1115,'eng-GB',2,324,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Landscape photography.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1116,'eng-GB',2,324,221,'',3,0,0,3,'','ezinteger'),(1117,'eng-GB',2,324,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"landscape.\"\n         suffix=\"\"\n         basename=\"landscape\"\n         dirpath=\"var/gallery/storage/images/nature/landscape/1117-2-eng-GB\"\n         url=\"var/gallery/storage/images/nature/landscape/1117-2-eng-GB/landscape.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317845\">\n  <original attribute_id=\"1117\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(923,'eng-GB',2,268,1,'Added new gallery',0,0,0,0,'added new gallery','ezstring'),(924,'eng-GB',2,268,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(925,'eng-GB',2,268,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>dfghLorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam </line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(926,'eng-GB',2,268,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"added_new_gallery.jpg\"\n         suffix=\"jpg\"\n         basename=\"added_new_gallery\"\n         dirpath=\"var/gallery/storage/images/news/added_new_gallery/926-2-eng-GB\"\n         url=\"var/gallery/storage/images/news/added_new_gallery/926-2-eng-GB/added_new_gallery.jpg\"\n         original_filename=\"dscn1631.jpg\"\n         mime_type=\"original\"\n         width=\"1024\"\n         height=\"768\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"926\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(927,'eng-GB',2,268,123,'',0,0,0,0,'','ezboolean'),(1109,'eng-GB',1,322,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A purple one, actually two.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1108,'eng-GB',1,322,116,'Purple haze',0,0,0,0,'purple haze','ezstring'),(1098,'eng-GB',1,319,215,'Nature',0,0,0,0,'nature','ezstring'),(1099,'eng-GB',1,319,216,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Nature images</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1100,'eng-GB',1,319,223,'',2,0,0,2,'','ezinteger'),(1101,'eng-GB',1,320,217,'Flowers',0,0,0,0,'flowers','ezstring'),(1102,'eng-GB',1,320,218,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Pictures of various flowers.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1103,'eng-GB',1,320,221,'',4,0,0,4,'','ezinteger'),(1104,'eng-GB',1,320,222,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"flowers.\"\n         suffix=\"\"\n         basename=\"flowers\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/1104-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/1104-1-eng-GB/flowers.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317669\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1105,'eng-GB',1,321,116,'Blue flower',0,0,0,0,'blue flower','ezstring'),(1106,'eng-GB',1,321,117,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A small nice blue flower.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1107,'eng-GB',1,321,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blue_flower.jpg\"\n         suffix=\"jpg\"\n         basename=\"blue_flower\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower.jpg\"\n         original_filename=\"blue_flower.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Blue flower\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317728\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"blue_flower_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069842042\"\n         is_valid=\"1\" />\n  <alias name=\"small_v\"\n         filename=\"blue_flower_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069317826\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"blue_flower_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069320924\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"blue_flower_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069326352\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',5,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',5,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(524,'eng-GB',3,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_my_gallery.\"\n         suffix=\"\"\n         basename=\"about_my_gallery\"\n         dirpath=\"var/gallery/storage/images/about_my_gallery/524-3-eng-GB\"\n         url=\"var/gallery/storage/images/about_my_gallery/524-3-eng-GB/about_my_gallery.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(327,'eng-GB',5,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',5,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator.png\"\n         original_filename=\"url_translator.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069254930\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414616\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069687924\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',12,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',12,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(104,'eng-GB',11,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',11,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(325,'eng-GB',6,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',6,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(323,'eng-GB',6,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',6,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache.png\"\n         original_filename=\"cache.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069254539\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069414616\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB\"\n         url=\"var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069687923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',52,56,160,'gallery_blue',0,0,0,0,'gallery_blue','ezpackage'),(154,'eng-GB',52,56,161,'gallery_package',0,0,0,0,'gallery_package','ezstring'),(437,'eng-GB',52,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',52,56,196,'mygallery.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1111,'eng-GB',1,323,116,'Yellow flower',0,0,0,0,'yellow flower','ezstring'),(1110,'eng-GB',1,322,118,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"purple_haze.jpg\"\n         suffix=\"jpg\"\n         basename=\"purple_haze\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze.jpg\"\n         original_filename=\"purple_haze.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"Purple haze\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069317767\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"small_h\"\n         filename=\"purple_haze_small_h.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_small_h.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"173\"\n         height=\"130\"\n         alias_key=\"-1426914878\"\n         timestamp=\"1069317809\"\n         is_valid=\"1\" />\n  <alias name=\"small_v\"\n         filename=\"purple_haze_small_v.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_small_v.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"150\"\n         height=\"113\"\n         alias_key=\"78134807\"\n         timestamp=\"1069317826\"\n         is_valid=\"1\" />\n  <alias name=\"navigator\"\n         filename=\"purple_haze_navigator.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_navigator.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"133\"\n         height=\"100\"\n         alias_key=\"347197093\"\n         timestamp=\"1069318084\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"purple_haze_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB\"\n         url=\"var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"400\"\n         height=\"300\"\n         alias_key=\"-1750183455\"\n         timestamp=\"1069320923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(22,'eng-GB',2,11,6,'Gallery editor',0,0,0,0,'gallery editor','ezstring'),(23,'eng-GB',2,11,7,'',0,0,0,0,'','ezstring');
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_name'
--

/*!40000 ALTER TABLE ezcontentobject_name DISABLE KEYS */;
LOCK TABLES ezcontentobject_name WRITE;
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(268,'Latest sdfgsdgf',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(267,'News',1,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(56,'My gallery',55,'eng-GB','eng-GB'),(320,'Flowers',3,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(56,'My gallery',58,'eng-GB','eng-GB'),(56,'My gallery',57,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(320,'Flowers',2,'eng-GB','eng-GB'),(56,'My gallery',54,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(56,'My gallery',59,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(268,'Added new gallery',2,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(56,'My gallery',60,'eng-GB','eng-GB'),(328,'Water reflection',1,'eng-GB','eng-GB'),(56,'My gallery',61,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(1,'Blog',6,'eng-GB','eng-GB'),(161,'About me',2,'eng-GB','eng-GB'),(115,'Cache',6,'eng-GB','eng-GB'),(43,'Classes',11,'eng-GB','eng-GB'),(45,'Look and feel',12,'eng-GB','eng-GB'),(56,'Blog',43,'eng-GB','eng-GB'),(56,'Blog',47,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(56,'Blog',48,'eng-GB','eng-GB'),(56,'Blog',49,'eng-GB','eng-GB'),(1,'Gallery',7,'eng-GB','eng-GB'),(56,'My gallery',53,'eng-GB','eng-GB'),(334,'Mjaurits',1,'eng-GB','eng-GB'),(335,'Speeding',1,'eng-GB','eng-GB'),(330,'Misc',2,'eng-GB','eng-GB'),(324,'Landscape',2,'eng-GB','eng-GB'),(56,'My gallery',50,'eng-GB','eng-GB'),(56,'My gallery',51,'eng-GB','eng-GB'),(115,'Cache',5,'eng-GB','eng-GB'),(43,'Classes',10,'eng-GB','eng-GB'),(45,'Look and feel',11,'eng-GB','eng-GB'),(116,'URL translator',4,'eng-GB','eng-GB'),(329,'Abstract',1,'eng-GB','eng-GB'),(330,'Misc',1,'eng-GB','eng-GB'),(331,'CVS branching?',1,'eng-GB','eng-GB'),(332,'Gear wheel',1,'eng-GB','eng-GB'),(333,'Green clover',1,'eng-GB','eng-GB'),(319,'Nature',1,'eng-GB','eng-GB'),(320,'Flowers',1,'eng-GB','eng-GB'),(321,'Blue flower',1,'eng-GB','eng-GB'),(322,'Purple haze',1,'eng-GB','eng-GB'),(323,'Yellow flower',1,'eng-GB','eng-GB'),(324,'Landscape',1,'eng-GB','eng-GB'),(325,'Pond reflection',1,'eng-GB','eng-GB'),(326,'Ormevika skyline',1,'eng-GB','eng-GB'),(116,'URL translator',5,'eng-GB','eng-GB'),(161,'About my gallery',3,'eng-GB','eng-GB'),(56,'My gallery',56,'eng-GB','eng-GB'),(299,'afunction_1280',1,'eng-GB','eng-GB'),(300,'nin',1,'eng-GB','eng-GB'),(56,'My gallery',52,'eng-GB','eng-GB'),(327,'Foggy trees',1,'eng-GB','eng-GB'),(11,'Gallery editor',2,'eng-GB','eng-GB'),(337,'Gallery Editor',1,'eng-GB','eng-GB');
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_tree'
--

/*!40000 ALTER TABLE ezcontentobject_tree DISABLE KEYS */;
LOCK TABLES ezcontentobject_tree WRITE;
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,7,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,2,1,2,'/1/5/12/',9,1,0,'users/gallery_editor',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,5,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,11,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,12,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(54,48,56,61,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/my_gallery',54),(127,2,161,3,1,2,'/1/2/127/',9,1,0,'about_my_gallery',127),(95,46,115,6,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,5,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(200,199,268,2,1,3,'/1/2/199/200/',9,1,0,'news/added_new_gallery',200),(199,2,267,1,1,2,'/1/2/199/',9,1,0,'news',199),(250,249,321,1,1,4,'/1/2/248/249/250/',9,1,0,'nature/flowers/blue_flower',250),(251,249,322,1,1,4,'/1/2/248/249/251/',9,1,0,'nature/flowers/purple_haze',251),(252,249,323,1,1,4,'/1/2/248/249/252/',9,1,0,'nature/flowers/yellow_flower',252),(253,248,324,2,1,3,'/1/2/248/253/',9,1,0,'nature/landscape',253),(254,253,325,1,1,4,'/1/2/248/253/254/',9,1,0,'nature/landscape/pond_reflection',254),(255,253,326,1,1,4,'/1/2/248/253/255/',9,1,0,'nature/landscape/ormevika_skyline',255),(256,253,327,1,1,4,'/1/2/248/253/256/',9,1,0,'nature/landscape/foggy_trees',256),(257,253,328,1,1,4,'/1/2/248/253/257/',9,1,0,'nature/landscape/water_reflection',257),(258,2,329,1,1,2,'/1/2/258/',9,1,0,'abstract',258),(259,258,330,2,1,3,'/1/2/258/259/',9,1,0,'abstract/misc',259),(260,259,331,1,1,4,'/1/2/258/259/260/',9,1,0,'abstract/misc/cvs_branching',260),(261,259,332,1,1,4,'/1/2/258/259/261/',9,1,0,'abstract/misc/gear_wheel',261),(262,259,333,1,1,4,'/1/2/258/259/262/',9,1,0,'abstract/misc/green_clover',262),(263,259,334,1,1,4,'/1/2/258/259/263/',9,1,0,'abstract/misc/mjaurits',263),(264,259,335,1,1,4,'/1/2/258/259/264/',9,1,0,'abstract/misc/speeding',264),(248,2,319,1,1,2,'/1/2/248/',9,1,0,'nature',248),(249,248,320,3,1,3,'/1/2/248/249/',9,1,0,'nature/flowers',249),(265,12,337,1,1,3,'/1/5/12/265/',9,1,0,'users/gallery_editor/gallery_editor',265);
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_version'
--

/*!40000 ALTER TABLE ezcontentobject_version DISABLE KEYS */;
LOCK TABLES ezcontentobject_version WRITE;
INSERT INTO ezcontentobject_version VALUES (804,1,14,6,1068710443,1068710484,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,3,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,3,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(950,43,14,11,1069254550,1069254602,1,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(897,45,14,11,1069074388,1069074395,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(1000,56,14,58,1069415035,1069415051,3,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(998,56,14,56,1069328499,1069328524,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(982,324,14,1,1069317843,1069317869,3,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(974,56,14,53,1069257560,1069257588,3,0,0),(725,161,14,1,1068047518,1068047603,3,0,0),(976,56,14,55,1069259417,1069325654,3,0,0),(1003,56,14,59,1069841477,1069841539,3,0,0),(975,56,14,54,1069259362,1069259378,3,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(1005,56,14,61,1069841702,1069842020,1,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(845,43,14,9,1068729346,1068729356,3,0,0),(984,326,14,1,1069317915,1069317947,1,0,0),(1004,56,14,60,1069841550,1069841691,3,0,0),(990,332,14,1,1069318454,1069318481,1,0,0),(928,56,14,52,1069252360,1069252381,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(876,268,14,1,1068814400,1068814751,3,0,0),(853,1,14,7,1068803287,1068803301,1,1,0),(999,56,14,57,1069414689,1069414733,3,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(951,45,14,12,1069254878,1069254902,1,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(695,1,14,3,1068035768,1068035779,3,1,0),(844,115,14,4,1068729296,1068729308,3,0,0),(875,267,14,1,1068814351,1068814364,1,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(949,115,14,6,1069254471,1069254539,1,0,0),(993,335,14,1,1069318568,1069318589,1,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(986,328,14,1,1069317987,1069318019,1,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(796,14,14,5,1068468183,1068468218,1,0,0),(997,324,14,2,1069321711,1069321719,1,0,0),(847,116,14,3,1068729385,1068729395,3,0,0),(846,45,14,10,1068729368,1068729376,3,0,0),(805,161,14,2,1068710499,1068710511,3,0,0),(996,320,14,3,1069321690,1069321700,1,0,0),(894,115,14,5,1069074351,1069074361,3,0,0),(896,43,14,10,1069074370,1069074377,3,0,0),(898,116,14,4,1069074407,1069074415,3,0,0),(980,322,14,1,1069317740,1069317767,1,0,0),(981,323,14,1,1069317776,1069317797,1,0,0),(983,325,14,1,1069317882,1069317906,1,0,0),(985,327,14,1,1069317955,1069317978,1,0,0),(987,329,14,1,1069318314,1069318331,1,0,0),(989,331,14,1,1069318390,1069318446,1,0,0),(995,320,14,2,1069321651,1069321658,3,0,0),(978,320,14,1,1069317667,1069317685,3,0,0),(992,334,14,1,1069318526,1069318560,1,0,0),(988,330,14,1,1069318366,1069318374,3,0,0),(991,333,14,1,1069318491,1069318517,1,0,0),(1001,161,14,3,1069757022,1069757035,1,0,0),(952,116,14,5,1069254913,1069254930,1,0,0),(994,330,14,2,1069321615,1069321635,1,0,0),(979,321,14,1,1069317697,1069317728,1,0,0),(1002,268,14,2,1069757046,1069757082,1,0,0),(977,319,14,1,1069317638,1069317649,1,0,0),(1006,11,14,2,1070976433,1070976447,1,0,0),(1008,337,14,1,1070976511,1070976556,1,0,0);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezgeneral_digest_user_settings'
--

/*!40000 ALTER TABLE ezgeneral_digest_user_settings DISABLE KEYS */;
LOCK TABLES ezgeneral_digest_user_settings WRITE;
INSERT INTO ezgeneral_digest_user_settings VALUES (1,'nospam@ez.no',0,0,'',''),(2,'wy@ez.no',0,0,'','');
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezimagefile'
--

/*!40000 ALTER TABLE ezimagefile DISABLE KEYS */;
LOCK TABLES ezimagefile WRITE;
INSERT INTO ezimagefile VALUES (1,1104,'var/gallery/storage/images/nature/flowers/1104-1-eng-GB/flowers.'),(3,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower.jpg'),(5,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze.jpg'),(7,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower.jpg'),(8,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_h.jpg'),(9,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_small_h.jpg'),(10,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_small_h.jpg'),(11,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_small_v.jpg'),(12,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_small_v.jpg'),(13,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_v.jpg'),(14,1117,'var/gallery/storage/images/nature/landscape/1117-1-eng-GB/landscape.'),(16,1120,'var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection.jpg'),(18,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline.jpg'),(20,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees.jpg'),(22,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection.jpg'),(23,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_small_h.jpg'),(24,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_small_v.jpg'),(25,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_small_v.jpg'),(26,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_small_v.jpg'),(27,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_small_h.jpg'),(28,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_small_h.jpg'),(29,1120,'var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_small_h.jpg'),(30,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_navigator.jpg'),(31,1113,'var/gallery/storage/images/nature/flowers/yellow_flower/1113-1-eng-GB/yellow_flower_large.jpg'),(32,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_navigator.jpg'),(33,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_navigator.jpg'),(34,1129,'var/gallery/storage/images/nature/landscape/water_reflection/1129-1-eng-GB/water_reflection_large.jpg'),(35,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_navigator.jpg'),(36,1136,'var/gallery/storage/images/abstract/misc/1136-1-eng-GB/misc.'),(38,1139,'var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching.jpg'),(40,1142,'var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel.jpg'),(42,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover.jpg'),(44,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits.jpg'),(46,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding.jpg'),(47,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_v.jpg'),(48,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_v.jpg'),(49,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_v.jpg'),(50,1139,'var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_small_h.jpg'),(51,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_h.jpg'),(52,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_h.jpg'),(53,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_h.jpg'),(54,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_large.jpg'),(55,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_navigator.jpg'),(56,1142,'var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_small_h.jpg'),(57,1148,'var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_large.jpg'),(58,1151,'var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_navigator.jpg'),(59,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_navigator.jpg'),(60,1145,'var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_large.jpg'),(61,1142,'var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_navigator.jpg'),(62,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-54-eng-GB/my_gallery_logo.png'),(63,1110,'var/gallery/storage/images/nature/flowers/purple_haze/1110-1-eng-GB/purple_haze_large.jpg'),(64,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_navigator.jpg'),(65,1136,'var/gallery/storage/images/abstract/misc/1136-2-eng-GB/misc.'),(66,1104,'var/gallery/storage/images/nature/flowers/1104-2-eng-GB/flowers.'),(67,1104,'var/gallery/storage/images/nature/flowers/1104-3-eng-GB/flowers.'),(68,1117,'var/gallery/storage/images/nature/landscape/1117-2-eng-GB/landscape.'),(69,1126,'var/gallery/storage/images/nature/landscape/foggy_trees/1126-1-eng-GB/foggy_trees_large.jpg'),(70,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_navigator.jpg'),(71,1123,'var/gallery/storage/images/nature/landscape/ormevika_skyline/1123-1-eng-GB/ormevika_skyline_large.jpg'),(72,1120,'var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_navigator.jpg'),(73,1120,'var/gallery/storage/images/nature/landscape/pond_reflection/1120-1-eng-GB/pond_reflection_large.jpg'),(74,1142,'var/gallery/storage/images/abstract/misc/gear_wheel/1142-1-eng-GB/gear_wheel_large.jpg'),(75,1139,'var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_navigator.jpg'),(76,1139,'var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_large.jpg'),(78,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery.gif'),(79,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_reference.gif'),(80,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_medium.gif'),(81,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-55-eng-GB/my_gallery_logo.gif'),(82,1107,'var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_large.jpg'),(84,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery.gif'),(85,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_reference.gif'),(86,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_medium.gif'),(87,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-56-eng-GB/my_gallery_logo.gif'),(88,103,'var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_reference.png'),(89,103,'var/gallery/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_large.png'),(90,109,'var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel_reference.png'),(91,109,'var/gallery/storage/images/setup/setup_links/look_and_feel/109-12-eng-GB/look_and_feel_large.png'),(92,324,'var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache_reference.png'),(93,324,'var/gallery/storage/images/setup/setup_links/cache/324-6-eng-GB/cache_large.png'),(94,328,'var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator_reference.png'),(95,328,'var/gallery/storage/images/setup/setup_links/url_translator/328-5-eng-GB/url_translator_large.png'),(96,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery.gif'),(97,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_reference.gif'),(98,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_medium.gif'),(99,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-57-eng-GB/my_gallery_logo.gif'),(100,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery.gif'),(101,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_reference.gif'),(102,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_medium.gif'),(103,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_logo.gif'),(104,524,'var/gallery/storage/images/about_my_gallery/524-3-eng-GB/about_my_gallery.'),(105,926,'var/gallery/storage/images/news/added_new_gallery/926-2-eng-GB/added_new_gallery.jpg'),(106,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-58-eng-GB/my_gallery_small_h.gif'),(108,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-59-eng-GB/my_gallery.gif'),(109,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-59-eng-GB/my_gallery_small_h.gif'),(111,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-60-eng-GB/my_gallery.gif'),(112,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-60-eng-GB/my_gallery_small_h.gif'),(114,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB/my_gallery.gif'),(115,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB/my_gallery_small_h.gif'),(116,152,'var/gallery/storage/images/setup/look_and_feel/my_gallery/152-61-eng-GB/my_gallery_logo.gif');
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezinfocollection_attribute'
--

/*!40000 ALTER TABLE ezinfocollection_attribute DISABLE KEYS */;
LOCK TABLES ezinfocollection_attribute WRITE;
INSERT INTO ezinfocollection_attribute VALUES (1,1,'',0,0,183,443,137),(2,1,'',0,0,185,445,137),(3,1,'',0,0,184,444,137),(4,2,'FOo bar ',0,0,183,443,137),(5,2,'nospam@ez.no',0,0,185,445,137),(6,2,'This is my feedback.',0,0,184,444,137),(7,3,'',0,0,208,789,227),(8,4,'',2,0,208,789,227),(9,5,'',2,0,208,789,227),(10,6,'',3,0,208,789,227),(11,7,'',4,0,208,789,227),(12,8,'',1,0,208,789,227),(13,9,'',1,0,208,789,227),(14,10,'',1,0,208,789,227),(15,11,'',3,0,208,789,227),(16,12,'',3,0,208,789,227),(17,13,'',3,0,208,789,227),(18,14,'',0,0,208,789,227),(19,15,'',1,0,208,789,227),(20,16,'',2,0,208,789,227),(21,17,'',2,0,208,789,227),(22,18,'',0,0,208,789,227),(23,19,'',0,0,208,789,227),(24,20,'',0,0,208,789,227),(25,21,'',0,0,208,789,227),(26,22,'',0,0,208,789,227),(27,23,'',1,0,208,789,227),(28,24,'',1,0,208,789,227),(29,25,'',2,0,208,789,227);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'eznode_assignment'
--

/*!40000 ALTER TABLE eznode_assignment DISABLE KEYS */;
LOCK TABLES eznode_assignment WRITE;
INSERT INTO eznode_assignment VALUES (510,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(691,332,1,259,9,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(575,267,1,2,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(651,43,11,46,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(597,45,11,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(701,56,58,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(699,56,56,48,9,1,1,0,0),(677,56,55,48,9,1,1,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(675,56,53,48,9,1,1,0,0),(704,56,59,48,9,1,1,0,0),(676,56,54,48,9,1,1,0,0),(321,45,6,46,9,1,1,0,0),(706,56,61,48,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(544,115,4,46,9,1,1,0,0),(681,322,1,249,9,1,1,0,0),(705,56,60,48,9,1,1,0,0),(545,43,9,46,9,1,1,0,0),(335,45,8,46,9,1,1,0,0),(629,56,52,48,9,1,1,0,0),(546,45,10,46,9,1,1,0,0),(553,1,7,1,9,1,1,0,0),(700,56,57,48,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(652,45,12,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(398,1,3,1,9,1,1,0,0),(694,335,1,259,9,1,1,0,0),(424,14,2,13,9,1,1,0,0),(547,116,3,46,9,1,1,0,0),(650,115,6,46,9,1,1,0,0),(481,14,3,13,9,1,1,0,0),(685,326,1,253,9,1,1,0,0),(687,328,1,253,9,1,1,0,0),(576,268,1,199,9,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(500,14,5,13,9,1,1,0,0),(680,321,1,249,9,1,1,0,0),(698,324,2,248,9,1,1,0,0),(511,161,2,2,9,1,1,0,0),(697,320,3,248,9,1,1,0,0),(594,115,5,46,9,1,1,0,0),(596,43,10,46,9,1,1,0,0),(598,116,4,46,9,1,1,0,0),(683,324,1,248,9,1,1,0,0),(684,325,1,253,9,1,1,0,0),(686,327,1,253,9,1,1,0,0),(688,329,1,2,9,1,1,0,0),(693,334,1,259,9,1,1,0,0),(696,320,2,248,9,1,1,0,0),(679,320,1,248,9,1,1,0,0),(690,331,1,259,9,1,1,0,0),(689,330,1,258,9,1,1,0,0),(692,333,1,259,9,1,1,0,0),(702,161,3,2,9,1,1,0,0),(653,116,5,46,9,1,1,0,0),(695,330,2,258,9,1,1,0,0),(703,268,2,199,9,1,1,0,0),(682,323,1,249,9,1,1,0,0),(678,319,1,2,9,1,1,0,0),(707,11,2,5,9,1,1,0,0),(709,337,1,12,9,1,1,0,0);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'eznotificationevent'
--

/*!40000 ALTER TABLE eznotificationevent DISABLE KEYS */;
LOCK TABLES eznotificationevent WRITE;
INSERT INTO eznotificationevent VALUES (227,0,'ezpublish',142,3,0,0,'','','',''),(226,0,'ezpublish',141,3,0,0,'','','',''),(225,0,'ezpublish',211,2,0,0,'','','',''),(224,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',142,2,0,0,'','','',''),(222,0,'ezpublish',141,2,0,0,'','','',''),(221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','',''),(228,0,'ezpublish',1,6,0,0,'','','',''),(229,0,'ezpublish',161,2,0,0,'','','',''),(230,0,'ezpublish',49,2,0,0,'','','',''),(231,0,'ezpublish',212,1,0,0,'','','',''),(232,0,'ezpublish',213,1,0,0,'','','',''),(233,0,'ezpublish',214,1,0,0,'','','',''),(234,0,'ezpublish',215,1,0,0,'','','',''),(235,0,'ezpublish',219,1,0,0,'','','',''),(236,0,'ezpublish',220,1,0,0,'','','',''),(237,0,'ezpublish',212,2,0,0,'','','',''),(238,0,'ezpublish',213,2,0,0,'','','',''),(239,0,'ezpublish',226,1,0,0,'','','',''),(240,0,'ezpublish',227,1,0,0,'','','',''),(241,0,'ezpublish',228,1,0,0,'','','',''),(242,0,'ezpublish',229,1,0,0,'','','',''),(243,0,'ezpublish',230,1,0,0,'','','',''),(244,0,'ezpublish',231,1,0,0,'','','',''),(245,0,'ezpublish',233,1,0,0,'','','',''),(246,0,'ezpublish',232,1,0,0,'','','',''),(247,0,'ezpublish',235,1,0,0,'','','',''),(248,0,'ezpublish',234,1,0,0,'','','',''),(249,0,'ezpublish',237,1,0,0,'','','',''),(250,0,'ezpublish',236,1,0,0,'','','',''),(251,0,'ezpublish',238,1,0,0,'','','',''),(252,0,'ezpublish',239,1,0,0,'','','',''),(253,0,'ezpublish',240,1,0,0,'','','',''),(254,0,'ezpublish',227,2,0,0,'','','',''),(255,0,'ezpublish',240,2,0,0,'','','',''),(256,0,'ezpublish',241,1,0,0,'','','',''),(257,0,'ezpublish',242,1,0,0,'','','',''),(258,0,'ezpublish',243,1,0,0,'','','',''),(259,0,'ezpublish',244,1,0,0,'','','',''),(260,0,'ezpublish',56,47,0,0,'','','',''),(261,0,'ezpublish',115,4,0,0,'','','',''),(262,0,'ezpublish',43,9,0,0,'','','',''),(263,0,'ezpublish',45,10,0,0,'','','',''),(264,0,'ezpublish',116,3,0,0,'','','',''),(265,0,'ezpublish',245,1,0,0,'','','',''),(266,0,'ezpublish',56,48,0,0,'','','',''),(267,0,'ezpublish',246,1,0,0,'','','',''),(268,0,'ezpublish',56,49,0,0,'','','',''),(269,0,'ezpublish',247,1,0,0,'','','',''),(270,0,'ezpublish',1,7,0,0,'','','',''),(271,0,'ezpublish',248,1,0,0,'','','',''),(272,0,'ezpublish',249,1,0,0,'','','',''),(273,0,'ezpublish',250,1,0,0,'','','',''),(274,0,'ezpublish',251,1,0,0,'','','',''),(275,0,'ezpublish',252,1,0,0,'','','',''),(276,0,'ezpublish',254,1,0,0,'','','',''),(277,0,'ezpublish',254,2,0,0,'','','',''),(278,0,'ezpublish',255,1,0,0,'','','',''),(279,0,'ezpublish',256,1,0,0,'','','',''),(280,0,'ezpublish',257,1,0,0,'','','',''),(281,0,'ezpublish',258,1,0,0,'','','',''),(282,0,'ezpublish',259,1,0,0,'','','',''),(283,0,'ezpublish',260,1,0,0,'','','',''),(284,0,'ezpublish',261,1,0,0,'','','',''),(285,0,'ezpublish',262,1,0,0,'','','',''),(286,0,'ezpublish',263,1,0,0,'','','',''),(287,0,'ezpublish',264,1,0,0,'','','',''),(288,0,'ezpublish',256,2,0,0,'','','',''),(289,0,'ezpublish',265,1,0,0,'','','',''),(290,0,'ezpublish',266,1,0,0,'','','',''),(291,0,'ezpublish',267,1,0,0,'','','',''),(292,0,'ezpublish',268,1,0,0,'','','',''),(293,0,'ezpublish',269,1,0,0,'','','',''),(294,0,'ezpublish',260,2,0,0,'','','',''),(295,0,'ezpublish',259,2,0,0,'','','',''),(296,0,'ezpublish',270,1,0,0,'','','',''),(297,0,'ezpublish',271,1,0,0,'','','',''),(298,0,'ezpublish',257,2,0,0,'','','',''),(299,0,'ezpublish',251,2,0,0,'','','',''),(300,0,'ezpublish',272,1,0,0,'','','',''),(301,0,'ezpublish',259,3,0,0,'','','',''),(302,0,'ezpublish',273,1,0,0,'','','',''),(303,0,'ezpublish',270,2,0,0,'','','',''),(304,0,'ezpublish',270,3,0,0,'','','',''),(305,0,'ezpublish',274,1,0,0,'','','',''),(306,0,'ezpublish',56,50,0,0,'','','',''),(307,0,'ezpublish',56,51,0,0,'','','',''),(308,0,'ezpublish',275,1,0,0,'','','',''),(309,0,'ezpublish',115,5,0,0,'','','',''),(310,0,'ezpublish',43,10,0,0,'','','',''),(311,0,'ezpublish',45,11,0,0,'','','',''),(312,0,'ezpublish',276,1,0,0,'','','',''),(313,0,'ezpublish',116,4,0,0,'','','',''),(314,0,'ezpublish',271,2,0,0,'','','',''),(315,0,'ezpublish',277,1,0,0,'','','',''),(316,0,'ezpublish',278,1,0,0,'','','',''),(317,0,'ezpublish',279,1,0,0,'','','',''),(318,0,'ezpublish',280,1,0,0,'','','',''),(319,0,'ezpublish',281,1,0,0,'','','',''),(320,0,'ezpublish',282,1,0,0,'','','',''),(321,0,'ezpublish',283,1,0,0,'','','',''),(322,0,'ezpublish',284,1,0,0,'','','',''),(323,0,'ezpublish',251,3,0,0,'','','',''),(324,0,'ezpublish',251,4,0,0,'','','',''),(325,0,'ezpublish',285,1,0,0,'','','',''),(326,0,'ezpublish',286,1,0,0,'','','',''),(327,0,'ezpublish',287,1,0,0,'','','',''),(328,0,'ezpublish',285,2,0,0,'','','',''),(329,0,'ezpublish',288,1,0,0,'','','',''),(330,0,'ezpublish',251,5,0,0,'','','',''),(331,0,'ezpublish',289,1,0,0,'','','',''),(332,0,'ezpublish',290,1,0,0,'','','',''),(333,0,'ezpublish',291,1,0,0,'','','',''),(334,0,'ezpublish',292,1,0,0,'','','',''),(335,0,'ezpublish',293,1,0,0,'','','',''),(336,0,'ezpublish',294,1,0,0,'','','',''),(337,0,'ezpublish',295,1,0,0,'','','',''),(338,0,'ezpublish',294,2,0,0,'','','',''),(339,0,'ezpublish',296,1,0,0,'','','',''),(340,0,'ezpublish',297,1,0,0,'','','',''),(341,0,'ezpublish',298,1,0,0,'','','',''),(342,0,'ezpublish',56,52,0,0,'','','',''),(343,0,'ezpublish',301,1,0,0,'','','',''),(344,0,'ezpublish',302,1,0,0,'','','',''),(345,0,'ezpublish',303,1,0,0,'','','',''),(346,0,'ezpublish',304,1,0,0,'','','',''),(347,0,'ezpublish',305,1,0,0,'','','',''),(348,0,'ezpublish',306,1,0,0,'','','',''),(349,0,'ezpublish',304,2,0,0,'','','',''),(350,0,'ezpublish',307,1,0,0,'','','',''),(351,0,'ezpublish',308,1,0,0,'','','',''),(352,0,'ezpublish',309,1,0,0,'','','',''),(353,0,'ezpublish',308,2,0,0,'','','',''),(354,0,'ezpublish',310,1,0,0,'','','',''),(355,0,'ezpublish',311,1,0,0,'','','',''),(356,0,'ezpublish',312,1,0,0,'','','',''),(357,0,'ezpublish',314,1,0,0,'','','',''),(358,0,'ezpublish',315,1,0,0,'','','',''),(359,0,'ezpublish',316,1,0,0,'','','',''),(360,0,'ezpublish',317,1,0,0,'','','',''),(361,0,'ezpublish',318,1,0,0,'','','',''),(362,0,'ezpublish',115,6,0,0,'','','',''),(363,0,'ezpublish',43,11,0,0,'','','',''),(364,0,'ezpublish',45,12,0,0,'','','',''),(365,0,'ezpublish',116,5,0,0,'','','',''),(366,0,'ezpublish',310,2,0,0,'','','',''),(367,0,'ezpublish',311,2,0,0,'','','',''),(368,0,'ezpublish',312,2,0,0,'','','',''),(369,0,'ezpublish',310,3,0,0,'','','',''),(370,0,'ezpublish',310,4,0,0,'','','',''),(371,0,'ezpublish',310,5,0,0,'','','',''),(372,0,'ezpublish',310,6,0,0,'','','',''),(373,0,'ezpublish',310,7,0,0,'','','',''),(374,0,'ezpublish',310,8,0,0,'','','',''),(375,0,'ezpublish',311,3,0,0,'','','',''),(376,0,'ezpublish',312,3,0,0,'','','',''),(377,0,'ezpublish',317,2,0,0,'','','',''),(378,0,'ezpublish',315,2,0,0,'','','',''),(379,0,'ezpublish',316,2,0,0,'','','',''),(380,0,'ezpublish',318,2,0,0,'','','',''),(381,0,'ezpublish',306,2,0,0,'','','',''),(382,0,'ezpublish',305,2,0,0,'','','',''),(383,0,'ezpublish',303,2,0,0,'','','',''),(384,0,'ezpublish',304,3,0,0,'','','',''),(385,0,'ezpublish',307,2,0,0,'','','',''),(386,0,'ezpublish',56,53,0,0,'','','',''),(387,0,'ezpublish',56,54,0,0,'','','',''),(388,0,'ezpublish',319,1,0,0,'','','',''),(389,0,'ezpublish',320,1,0,0,'','','',''),(390,0,'ezpublish',321,1,0,0,'','','',''),(391,0,'ezpublish',322,1,0,0,'','','',''),(392,0,'ezpublish',323,1,0,0,'','','',''),(393,0,'ezpublish',324,1,0,0,'','','',''),(394,0,'ezpublish',325,1,0,0,'','','',''),(395,0,'ezpublish',326,1,0,0,'','','',''),(396,0,'ezpublish',327,1,0,0,'','','',''),(397,0,'ezpublish',328,1,0,0,'','','',''),(398,0,'ezpublish',329,1,0,0,'','','',''),(399,0,'ezpublish',330,1,0,0,'','','',''),(400,0,'ezpublish',331,1,0,0,'','','',''),(401,0,'ezpublish',332,1,0,0,'','','',''),(402,0,'ezpublish',333,1,0,0,'','','',''),(403,0,'ezpublish',334,1,0,0,'','','',''),(404,0,'ezpublish',335,1,0,0,'','','',''),(405,0,'ezpublish',330,2,0,0,'','','',''),(406,0,'ezpublish',320,2,0,0,'','','',''),(407,0,'ezpublish',320,3,0,0,'','','',''),(408,0,'ezpublish',324,2,0,0,'','','',''),(409,0,'ezpublish',56,55,0,0,'','','',''),(410,0,'ezpublish',56,56,0,0,'','','',''),(411,0,'ezpublish',56,57,0,0,'','','',''),(412,0,'ezpublish',56,58,0,0,'','','',''),(413,0,'ezpublish',161,3,0,0,'','','',''),(414,0,'ezpublish',268,2,0,0,'','','',''),(415,0,'ezpublish',56,59,0,0,'','','',''),(416,0,'ezpublish',56,60,0,0,'','','',''),(417,0,'ezpublish',56,61,0,0,'','','',''),(418,0,'ezpublish',11,2,0,0,'','','',''),(419,0,'ezpublish',337,1,0,0,'','','','');
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy'
--

/*!40000 ALTER TABLE ezpolicy DISABLE KEYS */;
LOCK TABLES ezpolicy WRITE;
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(396,1,'login','user','*'),(392,8,'read','content',''),(393,8,'create','content',''),(394,8,'edit','content',''),(395,8,'create','content',''),(397,1,'read','content',''),(398,1,'create','content',''),(399,1,'edit','content','');
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy_limitation'
--

/*!40000 ALTER TABLE ezpolicy_limitation DISABLE KEYS */;
LOCK TABLES ezpolicy_limitation WRITE;
INSERT INTO ezpolicy_limitation VALUES (310,392,'Class',0,'read','content'),(311,393,'Class',0,'create','content'),(312,393,'ParentClass',0,'create','content'),(313,394,'Owner',0,'edit','content'),(314,395,'Class',0,'create','content'),(315,395,'ParentClass',0,'create','content'),(316,397,'Class',0,'read','content'),(317,398,'Class',0,'create','content'),(318,399,'Class',0,'edit','content');
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy_limitation_value'
--

/*!40000 ALTER TABLE ezpolicy_limitation_value DISABLE KEYS */;
LOCK TABLES ezpolicy_limitation_value WRITE;
INSERT INTO ezpolicy_limitation_value VALUES (644,310,'1'),(645,310,'2'),(646,310,'5'),(647,310,'10'),(648,310,'26'),(649,310,'27'),(650,310,'28'),(651,311,'27'),(652,312,'28'),(653,313,'1'),(654,314,'5'),(655,315,'27'),(656,316,'1'),(657,316,'10'),(658,316,'12'),(659,316,'2'),(660,316,'26'),(661,316,'27'),(662,316,'28'),(663,316,'5'),(664,317,'26'),(665,318,'26');
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezrole'
--

/*!40000 ALTER TABLE ezrole DISABLE KEYS */;
LOCK TABLES ezrole WRITE;
INSERT INTO ezrole VALUES (1,0,'Anonymous',''),(2,0,'Administrator','*'),(8,0,'Gallery editor',NULL);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_object_word_link'
--

/*!40000 ALTER TABLE ezsearch_object_word_link DISABLE KEYS */;
LOCK TABLES ezsearch_object_word_link WRITE;
INSERT INTO ezsearch_object_word_link VALUES (28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(5289,43,2184,0,2,2183,0,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(5287,43,2182,0,0,0,2183,14,1066384365,11,152,'',0),(5288,43,2183,0,1,2182,2184,14,1066384365,11,155,'',0),(5523,320,2302,0,5,2306,0,28,1069317685,1,221,'',3),(5522,320,2306,0,4,2308,2302,28,1069317685,1,218,'',0),(5521,320,2308,0,3,2249,2306,28,1069317685,1,218,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(5963,268,2353,0,32,2352,2354,2,1068814752,1,120,'',0),(5962,268,2352,0,31,2351,2353,2,1068814752,1,120,'',0),(5961,268,2351,0,30,2350,2352,2,1068814752,1,120,'',0),(5948,268,2341,0,17,2340,2342,2,1068814752,1,120,'',0),(5947,268,2340,0,16,2339,2341,2,1068814752,1,120,'',0),(5930,161,2348,0,384,2387,0,10,1068047603,1,141,'',0),(5929,161,2387,0,383,2415,2348,10,1068047603,1,141,'',0),(5928,161,2415,0,382,2404,2387,10,1068047603,1,141,'',0),(5927,161,2404,0,381,2434,2415,10,1068047603,1,141,'',0),(5926,161,2434,0,380,2433,2404,10,1068047603,1,141,'',0),(5925,161,2433,0,379,2432,2434,10,1068047603,1,141,'',0),(5924,161,2432,0,378,2337,2433,10,1068047603,1,141,'',0),(5923,161,2337,0,377,2355,2432,10,1068047603,1,141,'',0),(5922,161,2355,0,376,2406,2337,10,1068047603,1,141,'',0),(5921,161,2406,0,375,2401,2355,10,1068047603,1,141,'',0),(5920,161,2401,0,374,2431,2406,10,1068047603,1,141,'',0),(5919,161,2431,0,373,2356,2401,10,1068047603,1,141,'',0),(5918,161,2356,0,372,2352,2431,10,1068047603,1,141,'',0),(5917,161,2352,0,371,2397,2356,10,1068047603,1,141,'',0),(5916,161,2397,0,370,2430,2352,10,1068047603,1,141,'',0),(5915,161,2430,0,369,2429,2397,10,1068047603,1,141,'',0),(5914,161,2429,0,368,2428,2430,10,1068047603,1,141,'',0),(5913,161,2428,0,367,2427,2429,10,1068047603,1,141,'',0),(5912,161,2427,0,366,2348,2428,10,1068047603,1,141,'',0),(5911,161,2348,0,365,2389,2427,10,1068047603,1,141,'',0),(5910,161,2389,0,364,2336,2348,10,1068047603,1,141,'',0),(5909,161,2336,0,363,2426,2389,10,1068047603,1,141,'',0),(5908,161,2426,0,362,2361,2336,10,1068047603,1,141,'',0),(5907,161,2361,0,361,2350,2426,10,1068047603,1,141,'',0),(5906,161,2350,0,360,2406,2361,10,1068047603,1,141,'',0),(5905,161,2406,0,359,2348,2350,10,1068047603,1,141,'',0),(5904,161,2348,0,358,2425,2406,10,1068047603,1,141,'',0),(5903,161,2425,0,357,2332,2348,10,1068047603,1,141,'',0),(5902,161,2332,0,356,2397,2425,10,1068047603,1,141,'',0),(5901,161,2397,0,355,2376,2332,10,1068047603,1,141,'',0),(5900,161,2376,0,354,2387,2397,10,1068047603,1,141,'',0),(5899,161,2387,0,353,2345,2376,10,1068047603,1,141,'',0),(5898,161,2345,0,352,2424,2387,10,1068047603,1,141,'',0),(5897,161,2424,0,351,2423,2345,10,1068047603,1,141,'',0),(5896,161,2423,0,350,2397,2424,10,1068047603,1,141,'',0),(5895,161,2397,0,349,2368,2423,10,1068047603,1,141,'',0),(5894,161,2368,0,348,2422,2397,10,1068047603,1,141,'',0),(5893,161,2422,0,347,89,2368,10,1068047603,1,141,'',0),(5892,161,89,0,346,2403,2422,10,1068047603,1,141,'',0),(5891,161,2403,0,345,2409,89,10,1068047603,1,141,'',0),(5890,161,2409,0,344,2341,2403,10,1068047603,1,141,'',0),(5889,161,2341,0,343,2406,2409,10,1068047603,1,141,'',0),(5888,161,2406,0,342,2368,2341,10,1068047603,1,141,'',0),(5887,161,2368,0,341,2394,2406,10,1068047603,1,141,'',0),(5886,161,2394,0,340,2421,2368,10,1068047603,1,141,'',0),(5885,161,2421,0,339,2349,2394,10,1068047603,1,141,'',0),(5884,161,2349,0,338,2387,2421,10,1068047603,1,141,'',0),(5883,161,2387,0,337,2420,2349,10,1068047603,1,141,'',0),(5882,161,2420,0,336,2419,2387,10,1068047603,1,141,'',0),(5881,161,2419,0,335,2351,2420,10,1068047603,1,141,'',0),(5880,161,2351,0,334,2418,2419,10,1068047603,1,141,'',0),(5879,161,2418,0,333,2377,2351,10,1068047603,1,141,'',0),(5878,161,2377,0,332,2381,2418,10,1068047603,1,141,'',0),(5877,161,2381,0,331,2417,2377,10,1068047603,1,141,'',0),(5876,161,2417,0,330,2359,2381,10,1068047603,1,141,'',0),(5875,161,2359,0,329,2365,2417,10,1068047603,1,141,'',0),(5874,161,2365,0,328,2385,2359,10,1068047603,1,141,'',0),(5873,161,2385,0,327,2402,2365,10,1068047603,1,141,'',0),(5872,161,2402,0,326,2369,2385,10,1068047603,1,141,'',0),(5871,161,2369,0,325,2362,2402,10,1068047603,1,141,'',0),(5870,161,2362,0,324,2332,2369,10,1068047603,1,141,'',0),(5869,161,2332,0,323,2416,2362,10,1068047603,1,141,'',0),(5868,161,2416,0,322,2381,2332,10,1068047603,1,141,'',0),(5867,161,2381,0,321,2342,2416,10,1068047603,1,141,'',0),(5866,161,2342,0,320,2415,2381,10,1068047603,1,141,'',0),(5865,161,2415,0,319,2379,2342,10,1068047603,1,141,'',0),(5864,161,2379,0,318,2348,2415,10,1068047603,1,141,'',0),(5863,161,2348,0,317,2414,2379,10,1068047603,1,141,'',0),(5862,161,2414,0,316,2413,2348,10,1068047603,1,141,'',0),(5861,161,2413,0,315,2412,2414,10,1068047603,1,141,'',0),(5860,161,2412,0,314,2411,2413,10,1068047603,1,141,'',0),(5859,161,2411,0,313,2410,2412,10,1068047603,1,141,'',0),(5858,161,2410,0,312,1388,2411,10,1068047603,1,141,'',0),(5857,161,1388,0,311,2409,2410,10,1068047603,1,141,'',0),(5856,161,2409,0,310,2408,1388,10,1068047603,1,141,'',0),(5855,161,2408,0,309,2348,2409,10,1068047603,1,141,'',0),(5854,161,2348,0,308,2377,2408,10,1068047603,1,141,'',0),(5853,161,2377,0,307,2392,2348,10,1068047603,1,141,'',0),(5852,161,2392,0,306,2342,2377,10,1068047603,1,141,'',0),(5850,161,2342,0,304,2407,2342,10,1068047603,1,141,'',0),(5851,161,2342,0,305,2342,2392,10,1068047603,1,141,'',0),(5849,161,2407,0,303,2406,2342,10,1068047603,1,141,'',0),(5848,161,2406,0,302,2330,2407,10,1068047603,1,141,'',0),(5847,161,2330,0,301,2357,2406,10,1068047603,1,141,'',0),(5846,161,2357,0,300,2361,2330,10,1068047603,1,141,'',0),(5845,161,2361,0,299,2377,2357,10,1068047603,1,141,'',0),(5844,161,2377,0,298,1388,2361,10,1068047603,1,141,'',0),(5843,161,1388,0,297,2367,2377,10,1068047603,1,141,'',0),(5842,161,2367,0,296,2405,1388,10,1068047603,1,141,'',0),(5841,161,2405,0,295,2346,2367,10,1068047603,1,141,'',0),(5840,161,2346,0,294,2394,2405,10,1068047603,1,141,'',0),(5839,161,2394,0,293,2404,2346,10,1068047603,1,141,'',0),(5838,161,2404,0,292,2403,2394,10,1068047603,1,141,'',0),(5837,161,2403,0,291,2402,2404,10,1068047603,1,141,'',0),(5836,161,2402,0,290,2401,2403,10,1068047603,1,141,'',0),(5835,161,2401,0,289,2350,2402,10,1068047603,1,141,'',0),(5834,161,2350,0,288,2400,2401,10,1068047603,1,141,'',0),(5833,161,2400,0,287,2399,2350,10,1068047603,1,141,'',0),(5832,161,2399,0,286,2398,2400,10,1068047603,1,141,'',0),(5831,161,2398,0,285,2397,2399,10,1068047603,1,141,'',0),(5830,161,2397,0,284,2396,2398,10,1068047603,1,141,'',0),(5829,161,2396,0,283,2395,2397,10,1068047603,1,141,'',0),(5828,161,2395,0,282,2360,2396,10,1068047603,1,141,'',0),(5827,161,2360,0,281,2394,2395,10,1068047603,1,141,'',0),(5826,161,2394,0,280,2393,2360,10,1068047603,1,141,'',0),(5825,161,2393,0,279,2362,2394,10,1068047603,1,141,'',0),(5824,161,2362,0,278,2392,2393,10,1068047603,1,141,'',0),(5823,161,2392,0,277,2391,2362,10,1068047603,1,141,'',0),(5822,161,2391,0,276,2390,2392,10,1068047603,1,141,'',0),(5821,161,2390,0,275,2342,2391,10,1068047603,1,141,'',0),(5820,161,2342,0,274,2390,2390,10,1068047603,1,141,'',0),(5819,161,2390,0,273,2389,2342,10,1068047603,1,141,'',0),(5818,161,2389,0,272,2388,2390,10,1068047603,1,141,'',0),(5817,161,2388,0,271,2387,2389,10,1068047603,1,141,'',0),(5816,161,2387,0,270,2386,2388,10,1068047603,1,141,'',0),(5815,161,2386,0,269,2385,2387,10,1068047603,1,141,'',0),(5814,161,2385,0,268,2384,2386,10,1068047603,1,141,'',0),(5813,161,2384,0,267,2355,2385,10,1068047603,1,141,'',0),(5812,161,2355,0,266,1388,2384,10,1068047603,1,141,'',0),(5811,161,1388,0,265,2383,2355,10,1068047603,1,141,'',0),(5810,161,2383,0,264,2382,1388,10,1068047603,1,141,'',0),(5809,161,2382,0,263,2381,2383,10,1068047603,1,141,'',0),(5808,161,2381,0,262,2380,2382,10,1068047603,1,141,'',0),(5807,161,2380,0,261,2379,2381,10,1068047603,1,141,'',0),(5806,161,2379,0,260,2330,2380,10,1068047603,1,141,'',0),(5805,161,2330,0,259,2342,2379,10,1068047603,1,141,'',0),(5804,161,2342,0,258,2378,2330,10,1068047603,1,141,'',0),(5803,161,2378,0,257,2377,2342,10,1068047603,1,141,'',0),(5802,161,2377,0,256,2359,2378,10,1068047603,1,141,'',0),(5801,161,2359,0,255,1388,2377,10,1068047603,1,141,'',0),(5800,161,1388,0,254,2376,2359,10,1068047603,1,141,'',0),(5799,161,2376,0,253,2375,1388,10,1068047603,1,141,'',0),(5798,161,2375,0,252,2374,2376,10,1068047603,1,141,'',0),(5797,161,2374,0,251,2342,2375,10,1068047603,1,141,'',0),(5796,161,2342,0,250,2374,2374,10,1068047603,1,141,'',0),(5795,161,2374,0,249,2373,2342,10,1068047603,1,141,'',0),(5794,161,2373,0,248,2372,2374,10,1068047603,1,141,'',0),(5793,161,2372,0,247,2349,2373,10,1068047603,1,141,'',0),(5792,161,2349,0,246,2371,2372,10,1068047603,1,141,'',0),(5791,161,2371,0,245,2370,2349,10,1068047603,1,141,'',0),(5790,161,2370,0,244,2369,2371,10,1068047603,1,141,'',0),(5789,161,2369,0,243,2368,2370,10,1068047603,1,141,'',0),(5788,161,2368,0,242,2337,2369,10,1068047603,1,141,'',0),(5787,161,2337,0,241,2348,2368,10,1068047603,1,141,'',0),(5786,161,2348,0,240,2367,2337,10,1068047603,1,141,'',0),(5785,161,2367,0,239,2366,2348,10,1068047603,1,141,'',0),(5784,161,2366,0,238,2365,2367,10,1068047603,1,141,'',0),(5783,161,2365,0,237,2364,2366,10,1068047603,1,141,'',0),(5782,161,2364,0,236,2363,2365,10,1068047603,1,141,'',0),(5781,161,2363,0,235,2362,2364,10,1068047603,1,141,'',0),(5780,161,2362,0,234,2361,2363,10,1068047603,1,141,'',0),(5779,161,2361,0,233,2360,2362,10,1068047603,1,141,'',0),(5778,161,2360,0,232,2359,2361,10,1068047603,1,141,'',0),(5777,161,2359,0,231,89,2360,10,1068047603,1,141,'',0),(5776,161,89,0,230,2358,2359,10,1068047603,1,141,'',0),(5775,161,2358,0,229,2357,89,10,1068047603,1,141,'',0),(5774,161,2357,0,228,2356,2358,10,1068047603,1,141,'',0),(5773,161,2356,0,227,2355,2357,10,1068047603,1,141,'',0),(5772,161,2355,0,226,2338,2356,10,1068047603,1,141,'',0),(5771,161,2338,0,225,2354,2355,10,1068047603,1,141,'',0),(5770,161,2354,0,224,2353,2338,10,1068047603,1,141,'',0),(5769,161,2353,0,223,2352,2354,10,1068047603,1,141,'',0),(5768,161,2352,0,222,2351,2353,10,1068047603,1,141,'',0),(5767,161,2351,0,221,2350,2352,10,1068047603,1,141,'',0),(5766,161,2350,0,220,2349,2351,10,1068047603,1,141,'',0),(5765,161,2349,0,219,2348,2350,10,1068047603,1,141,'',0),(5764,161,2348,0,218,2347,2349,10,1068047603,1,141,'',0),(5763,161,2347,0,217,1388,2348,10,1068047603,1,141,'',0),(5762,161,1388,0,216,2338,2347,10,1068047603,1,141,'',0),(5761,161,2338,0,215,2346,1388,10,1068047603,1,141,'',0),(5760,161,2346,0,214,2345,2338,10,1068047603,1,141,'',0),(5759,161,2345,0,213,2344,2346,10,1068047603,1,141,'',0),(5758,161,2344,0,212,2343,2345,10,1068047603,1,141,'',0),(5757,161,2343,0,211,2336,2344,10,1068047603,1,141,'',0),(5756,161,2336,0,210,2342,2343,10,1068047603,1,141,'',0),(5755,161,2342,0,209,2341,2336,10,1068047603,1,141,'',0),(5754,161,2341,0,208,2340,2342,10,1068047603,1,141,'',0),(5753,161,2340,0,207,2339,2341,10,1068047603,1,141,'',0),(5752,161,2339,0,206,2338,2340,10,1068047603,1,141,'',0),(5751,161,2338,0,205,2334,2339,10,1068047603,1,141,'',0),(5750,161,2334,0,204,2333,2338,10,1068047603,1,141,'',0),(5749,161,2333,0,203,1388,2334,10,1068047603,1,141,'',0),(5748,161,1388,0,202,2337,2333,10,1068047603,1,141,'',0),(5747,161,2337,0,201,2336,1388,10,1068047603,1,141,'',0),(5746,161,2336,0,200,2335,2337,10,1068047603,1,141,'',0),(5745,161,2335,0,199,2334,2336,10,1068047603,1,141,'',0),(5744,161,2334,0,198,2333,2335,10,1068047603,1,141,'',0),(5743,161,2333,0,197,2332,2334,10,1068047603,1,141,'',0),(5742,161,2332,0,196,2331,2333,10,1068047603,1,141,'',0),(5741,161,2331,0,195,2330,2332,10,1068047603,1,141,'',0),(5740,161,2330,0,194,2348,2331,10,1068047603,1,141,'',0),(5739,161,2348,0,193,2387,2330,10,1068047603,1,141,'',0),(5738,161,2387,0,192,2415,2348,10,1068047603,1,141,'',0),(5737,161,2415,0,191,2404,2387,10,1068047603,1,141,'',0),(5736,161,2404,0,190,2434,2415,10,1068047603,1,141,'',0),(5735,161,2434,0,189,2433,2404,10,1068047603,1,141,'',0),(5734,161,2433,0,188,2432,2434,10,1068047603,1,141,'',0),(5733,161,2432,0,187,2337,2433,10,1068047603,1,141,'',0),(5732,161,2337,0,186,2355,2432,10,1068047603,1,141,'',0),(5731,161,2355,0,185,2406,2337,10,1068047603,1,141,'',0),(5730,161,2406,0,184,2401,2355,10,1068047603,1,141,'',0),(5729,161,2401,0,183,2431,2406,10,1068047603,1,141,'',0),(5728,161,2431,0,182,2356,2401,10,1068047603,1,141,'',0),(5727,161,2356,0,181,2352,2431,10,1068047603,1,141,'',0),(5726,161,2352,0,180,2397,2356,10,1068047603,1,141,'',0),(5725,161,2397,0,179,2430,2352,10,1068047603,1,141,'',0),(5724,161,2430,0,178,2429,2397,10,1068047603,1,141,'',0),(5723,161,2429,0,177,2428,2430,10,1068047603,1,141,'',0),(5722,161,2428,0,176,2427,2429,10,1068047603,1,141,'',0),(5721,161,2427,0,175,2348,2428,10,1068047603,1,141,'',0),(5720,161,2348,0,174,2389,2427,10,1068047603,1,141,'',0),(5719,161,2389,0,173,2336,2348,10,1068047603,1,141,'',0),(5718,161,2336,0,172,2426,2389,10,1068047603,1,141,'',0),(5717,161,2426,0,171,2361,2336,10,1068047603,1,141,'',0),(5716,161,2361,0,170,2350,2426,10,1068047603,1,141,'',0),(5715,161,2350,0,169,2406,2361,10,1068047603,1,141,'',0),(5714,161,2406,0,168,2348,2350,10,1068047603,1,141,'',0),(5713,161,2348,0,167,2425,2406,10,1068047603,1,141,'',0),(5712,161,2425,0,166,2332,2348,10,1068047603,1,141,'',0),(5711,161,2332,0,165,2397,2425,10,1068047603,1,141,'',0),(5710,161,2397,0,164,2376,2332,10,1068047603,1,141,'',0),(5709,161,2376,0,163,2387,2397,10,1068047603,1,141,'',0),(5708,161,2387,0,162,2345,2376,10,1068047603,1,141,'',0),(5707,161,2345,0,161,2424,2387,10,1068047603,1,141,'',0),(5706,161,2424,0,160,2423,2345,10,1068047603,1,141,'',0),(5705,161,2423,0,159,2397,2424,10,1068047603,1,141,'',0),(5704,161,2397,0,158,2368,2423,10,1068047603,1,141,'',0),(5703,161,2368,0,157,2422,2397,10,1068047603,1,141,'',0),(5702,161,2422,0,156,89,2368,10,1068047603,1,141,'',0),(5701,161,89,0,155,2403,2422,10,1068047603,1,141,'',0),(5700,161,2403,0,154,2409,89,10,1068047603,1,141,'',0),(5699,161,2409,0,153,2341,2403,10,1068047603,1,141,'',0),(5698,161,2341,0,152,2406,2409,10,1068047603,1,141,'',0),(5697,161,2406,0,151,2368,2341,10,1068047603,1,141,'',0),(5696,161,2368,0,150,2394,2406,10,1068047603,1,141,'',0),(5695,161,2394,0,149,2421,2368,10,1068047603,1,141,'',0),(5694,161,2421,0,148,2349,2394,10,1068047603,1,141,'',0),(5693,161,2349,0,147,2387,2421,10,1068047603,1,141,'',0),(5692,161,2387,0,146,2420,2349,10,1068047603,1,141,'',0),(5691,161,2420,0,145,2419,2387,10,1068047603,1,141,'',0),(5690,161,2419,0,144,2351,2420,10,1068047603,1,141,'',0),(5689,161,2351,0,143,2418,2419,10,1068047603,1,141,'',0),(5688,161,2418,0,142,2377,2351,10,1068047603,1,141,'',0),(5687,161,2377,0,141,2381,2418,10,1068047603,1,141,'',0),(5686,161,2381,0,140,2417,2377,10,1068047603,1,141,'',0),(5685,161,2417,0,139,2359,2381,10,1068047603,1,141,'',0),(5684,161,2359,0,138,2365,2417,10,1068047603,1,141,'',0),(5683,161,2365,0,137,2385,2359,10,1068047603,1,141,'',0),(5682,161,2385,0,136,2402,2365,10,1068047603,1,141,'',0),(5681,161,2402,0,135,2369,2385,10,1068047603,1,141,'',0),(5680,161,2369,0,134,2362,2402,10,1068047603,1,141,'',0),(5679,161,2362,0,133,2332,2369,10,1068047603,1,141,'',0),(5678,161,2332,0,132,2416,2362,10,1068047603,1,141,'',0),(5677,161,2416,0,131,2381,2332,10,1068047603,1,141,'',0),(5676,161,2381,0,130,2342,2416,10,1068047603,1,141,'',0),(5675,161,2342,0,129,2415,2381,10,1068047603,1,141,'',0),(5674,161,2415,0,128,2379,2342,10,1068047603,1,141,'',0),(5673,161,2379,0,127,2348,2415,10,1068047603,1,141,'',0),(5672,161,2348,0,126,2414,2379,10,1068047603,1,141,'',0),(5671,161,2414,0,125,2413,2348,10,1068047603,1,141,'',0),(5670,161,2413,0,124,2412,2414,10,1068047603,1,141,'',0),(5669,161,2412,0,123,2411,2413,10,1068047603,1,141,'',0),(5668,161,2411,0,122,2410,2412,10,1068047603,1,141,'',0),(5667,161,2410,0,121,1388,2411,10,1068047603,1,141,'',0),(5666,161,1388,0,120,2409,2410,10,1068047603,1,141,'',0),(5665,161,2409,0,119,2408,1388,10,1068047603,1,141,'',0),(5664,161,2408,0,118,2348,2409,10,1068047603,1,141,'',0),(5663,161,2348,0,117,2377,2408,10,1068047603,1,141,'',0),(5662,161,2377,0,116,2392,2348,10,1068047603,1,141,'',0),(5661,161,2392,0,115,2342,2377,10,1068047603,1,141,'',0),(5660,161,2342,0,114,2342,2392,10,1068047603,1,141,'',0),(5658,161,2407,0,112,2406,2342,10,1068047603,1,141,'',0),(5659,161,2342,0,113,2407,2342,10,1068047603,1,141,'',0),(5458,326,2268,0,3,2266,2269,5,1069317947,1,117,'',0),(5286,115,2181,0,2,7,0,14,1066991725,11,155,'',0),(5285,115,7,0,1,2181,2181,14,1066991725,11,155,'',0),(5284,115,2181,0,0,0,7,14,1066991725,11,152,'',0),(5299,116,2189,0,3,25,0,14,1066992054,11,155,'',0),(5298,116,25,0,2,2188,2189,14,1066992054,11,155,'',0),(5297,116,2188,0,1,2187,25,14,1066992054,11,152,'',0),(5296,116,2187,0,0,0,2188,14,1066992054,11,152,'',0),(5295,45,2186,0,5,2185,0,14,1066388816,11,155,'',0),(5294,45,2185,0,4,25,2186,14,1066388816,11,155,'',0),(5293,45,25,0,3,34,2185,14,1066388816,11,155,'',0),(5292,45,34,0,2,33,25,14,1066388816,11,152,'',0),(5290,45,32,0,0,0,33,14,1066388816,11,152,'',0),(5291,45,33,0,1,32,34,14,1066388816,11,152,'',0),(3068,14,1362,0,5,1316,0,4,1033920830,2,199,'',0),(3067,14,1316,0,4,1361,1362,4,1033920830,2,198,'',0),(5657,161,2406,0,111,2330,2407,10,1068047603,1,141,'',0),(5656,161,2330,0,110,2357,2406,10,1068047603,1,141,'',0),(5655,161,2357,0,109,2361,2330,10,1068047603,1,141,'',0),(5654,161,2361,0,108,2377,2357,10,1068047603,1,141,'',0),(5653,161,2377,0,107,1388,2361,10,1068047603,1,141,'',0),(5652,161,1388,0,106,2367,2377,10,1068047603,1,141,'',0),(5651,161,2367,0,105,2405,1388,10,1068047603,1,141,'',0),(5650,161,2405,0,104,2346,2367,10,1068047603,1,141,'',0),(5649,161,2346,0,103,2394,2405,10,1068047603,1,141,'',0),(5648,161,2394,0,102,2404,2346,10,1068047603,1,141,'',0),(5647,161,2404,0,101,2403,2394,10,1068047603,1,141,'',0),(5646,161,2403,0,100,2402,2404,10,1068047603,1,141,'',0),(5645,161,2402,0,99,2401,2403,10,1068047603,1,141,'',0),(5644,161,2401,0,98,2350,2402,10,1068047603,1,141,'',0),(5643,161,2350,0,97,2400,2401,10,1068047603,1,141,'',0),(5642,161,2400,0,96,2399,2350,10,1068047603,1,141,'',0),(5641,161,2399,0,95,2398,2400,10,1068047603,1,141,'',0),(5640,161,2398,0,94,2397,2399,10,1068047603,1,141,'',0),(5639,161,2397,0,93,2396,2398,10,1068047603,1,141,'',0),(5638,161,2396,0,92,2395,2397,10,1068047603,1,141,'',0),(5637,161,2395,0,91,2360,2396,10,1068047603,1,141,'',0),(5636,161,2360,0,90,2394,2395,10,1068047603,1,141,'',0),(5635,161,2394,0,89,2393,2360,10,1068047603,1,141,'',0),(5634,161,2393,0,88,2362,2394,10,1068047603,1,141,'',0),(5633,161,2362,0,87,2392,2393,10,1068047603,1,141,'',0),(5632,161,2392,0,86,2391,2362,10,1068047603,1,141,'',0),(5631,161,2391,0,85,2390,2392,10,1068047603,1,141,'',0),(5630,161,2390,0,84,2342,2391,10,1068047603,1,141,'',0),(5629,161,2342,0,83,2390,2390,10,1068047603,1,141,'',0),(5628,161,2390,0,82,2389,2342,10,1068047603,1,141,'',0),(5627,161,2389,0,81,2388,2390,10,1068047603,1,141,'',0),(5626,161,2388,0,80,2387,2389,10,1068047603,1,141,'',0),(5625,161,2387,0,79,2386,2388,10,1068047603,1,141,'',0),(5624,161,2386,0,78,2385,2387,10,1068047603,1,141,'',0),(5623,161,2385,0,77,2384,2386,10,1068047603,1,141,'',0),(5622,161,2384,0,76,2355,2385,10,1068047603,1,141,'',0),(5621,161,2355,0,75,1388,2384,10,1068047603,1,141,'',0),(5620,161,1388,0,74,2383,2355,10,1068047603,1,141,'',0),(5619,161,2383,0,73,2382,1388,10,1068047603,1,141,'',0),(5618,161,2382,0,72,2381,2383,10,1068047603,1,141,'',0),(5617,161,2381,0,71,2380,2382,10,1068047603,1,141,'',0),(5616,161,2380,0,70,2379,2381,10,1068047603,1,141,'',0),(5615,161,2379,0,69,2330,2380,10,1068047603,1,141,'',0),(5614,161,2330,0,68,2342,2379,10,1068047603,1,141,'',0),(5613,161,2342,0,67,2378,2330,10,1068047603,1,141,'',0),(5612,161,2378,0,66,2377,2342,10,1068047603,1,141,'',0),(5611,161,2377,0,65,2359,2378,10,1068047603,1,141,'',0),(5610,161,2359,0,64,1388,2377,10,1068047603,1,141,'',0),(5609,161,1388,0,63,2376,2359,10,1068047603,1,141,'',0),(5608,161,2376,0,62,2375,1388,10,1068047603,1,141,'',0),(5607,161,2375,0,61,2374,2376,10,1068047603,1,141,'',0),(5606,161,2374,0,60,2342,2375,10,1068047603,1,141,'',0),(5605,161,2342,0,59,2374,2374,10,1068047603,1,141,'',0),(5604,161,2374,0,58,2373,2342,10,1068047603,1,141,'',0),(5603,161,2373,0,57,2372,2374,10,1068047603,1,141,'',0),(5602,161,2372,0,56,2349,2373,10,1068047603,1,141,'',0),(5601,161,2349,0,55,2371,2372,10,1068047603,1,141,'',0),(5600,161,2371,0,54,2370,2349,10,1068047603,1,141,'',0),(5599,161,2370,0,53,2369,2371,10,1068047603,1,141,'',0),(5598,161,2369,0,52,2368,2370,10,1068047603,1,141,'',0),(5597,161,2368,0,51,2337,2369,10,1068047603,1,141,'',0),(5596,161,2337,0,50,2348,2368,10,1068047603,1,141,'',0),(5595,161,2348,0,49,2367,2337,10,1068047603,1,141,'',0),(5594,161,2367,0,48,2366,2348,10,1068047603,1,141,'',0),(5593,161,2366,0,47,2365,2367,10,1068047603,1,141,'',0),(5592,161,2365,0,46,2364,2366,10,1068047603,1,141,'',0),(5591,161,2364,0,45,2363,2365,10,1068047603,1,141,'',0),(5590,161,2363,0,44,2362,2364,10,1068047603,1,141,'',0),(5589,161,2362,0,43,2361,2363,10,1068047603,1,141,'',0),(5588,161,2361,0,42,2360,2362,10,1068047603,1,141,'',0),(5587,161,2360,0,41,2359,2361,10,1068047603,1,141,'',0),(5586,161,2359,0,40,89,2360,10,1068047603,1,141,'',0),(5585,161,89,0,39,2358,2359,10,1068047603,1,141,'',0),(5584,161,2358,0,38,2357,89,10,1068047603,1,141,'',0),(5583,161,2357,0,37,2356,2358,10,1068047603,1,141,'',0),(5582,161,2356,0,36,2355,2357,10,1068047603,1,141,'',0),(5581,161,2355,0,35,2338,2356,10,1068047603,1,141,'',0),(5580,161,2338,0,34,2354,2355,10,1068047603,1,141,'',0),(5579,161,2354,0,33,2353,2338,10,1068047603,1,141,'',0),(5578,161,2353,0,32,2352,2354,10,1068047603,1,141,'',0),(5577,161,2352,0,31,2351,2353,10,1068047603,1,141,'',0),(5576,161,2351,0,30,2350,2352,10,1068047603,1,141,'',0),(5575,161,2350,0,29,2349,2351,10,1068047603,1,141,'',0),(5574,161,2349,0,28,2348,2350,10,1068047603,1,141,'',0),(5573,161,2348,0,27,2347,2349,10,1068047603,1,141,'',0),(5572,161,2347,0,26,1388,2348,10,1068047603,1,141,'',0),(5571,161,1388,0,25,2338,2347,10,1068047603,1,141,'',0),(5570,161,2338,0,24,2346,1388,10,1068047603,1,141,'',0),(5569,161,2346,0,23,2345,2338,10,1068047603,1,141,'',0),(5568,161,2345,0,22,2344,2346,10,1068047603,1,141,'',0),(5567,161,2344,0,21,2343,2345,10,1068047603,1,141,'',0),(5566,161,2343,0,20,2336,2344,10,1068047603,1,141,'',0),(5565,161,2336,0,19,2342,2343,10,1068047603,1,141,'',0),(5564,161,2342,0,18,2341,2336,10,1068047603,1,141,'',0),(5563,161,2341,0,17,2340,2342,10,1068047603,1,141,'',0),(5562,161,2340,0,16,2339,2341,10,1068047603,1,141,'',0),(5561,161,2339,0,15,2338,2340,10,1068047603,1,141,'',0),(5560,161,2338,0,14,2334,2339,10,1068047603,1,141,'',0),(5559,161,2334,0,13,2333,2338,10,1068047603,1,141,'',0),(5558,161,2333,0,12,1388,2334,10,1068047603,1,141,'',0),(5557,161,1388,0,11,2337,2333,10,1068047603,1,141,'',0),(5556,161,2337,0,10,2336,1388,10,1068047603,1,141,'',0),(5555,161,2336,0,9,2335,2337,10,1068047603,1,141,'',0),(5554,161,2335,0,8,2334,2336,10,1068047603,1,141,'',0),(5553,161,2334,0,7,2333,2335,10,1068047603,1,141,'',0),(5552,161,2333,0,6,2332,2334,10,1068047603,1,141,'',0),(5551,161,2332,0,5,2331,2333,10,1068047603,1,141,'',0),(5550,161,2331,0,4,2330,2332,10,1068047603,1,141,'',0),(5549,161,2330,0,3,1925,2331,10,1068047603,1,141,'',0),(5548,161,1925,0,2,2329,2330,10,1068047603,1,140,'',0),(5546,161,2328,0,0,0,2329,10,1068047603,1,140,'',0),(5547,161,2329,0,1,2328,1925,10,1068047603,1,140,'',0),(3066,14,1361,0,3,1360,1316,4,1033920830,2,198,'',0),(3065,14,1360,0,2,1359,1361,4,1033920830,2,197,'',0),(3064,14,1359,0,1,1358,1360,4,1033920830,2,9,'',0),(3063,14,1358,0,0,0,1359,4,1033920830,2,8,'',0),(4839,1,1925,0,0,0,0,1,1033917596,1,4,'',0),(6156,268,2338,0,225,2354,2355,2,1068814752,1,121,'',0),(6155,268,2354,0,224,2353,2338,2,1068814752,1,121,'',0),(6154,268,2353,0,223,2352,2354,2,1068814752,1,121,'',0),(6153,268,2352,0,222,2351,2353,2,1068814752,1,121,'',0),(6040,268,2357,0,109,2361,2330,2,1068814752,1,120,'',0),(6041,268,2330,0,110,2357,2406,2,1068814752,1,120,'',0),(6042,268,2406,0,111,2330,2407,2,1068814752,1,120,'',0),(6043,268,2407,0,112,2406,2342,2,1068814752,1,120,'',0),(6044,268,2342,0,113,2407,2342,2,1068814752,1,120,'',0),(6045,268,2342,0,114,2342,2392,2,1068814752,1,120,'',0),(6046,268,2392,0,115,2342,2377,2,1068814752,1,120,'',0),(6047,268,2377,0,116,2392,2348,2,1068814752,1,120,'',0),(6048,268,2348,0,117,2377,2408,2,1068814752,1,120,'',0),(6049,268,2408,0,118,2348,2409,2,1068814752,1,120,'',0),(6050,268,2409,0,119,2408,1388,2,1068814752,1,120,'',0),(6051,268,1388,0,120,2409,2410,2,1068814752,1,120,'',0),(6052,268,2410,0,121,1388,2411,2,1068814752,1,120,'',0),(6053,268,2411,0,122,2410,2412,2,1068814752,1,120,'',0),(6054,268,2412,0,123,2411,2413,2,1068814752,1,120,'',0),(6055,268,2413,0,124,2412,2414,2,1068814752,1,120,'',0),(6056,268,2414,0,125,2413,2348,2,1068814752,1,120,'',0),(6057,268,2348,0,126,2414,2379,2,1068814752,1,120,'',0),(6058,268,2379,0,127,2348,2415,2,1068814752,1,120,'',0),(6059,268,2415,0,128,2379,2342,2,1068814752,1,120,'',0),(6060,268,2342,0,129,2415,2381,2,1068814752,1,120,'',0),(6061,268,2381,0,130,2342,2416,2,1068814752,1,120,'',0),(6062,268,2416,0,131,2381,2332,2,1068814752,1,120,'',0),(6063,268,2332,0,132,2416,2362,2,1068814752,1,120,'',0),(6064,268,2362,0,133,2332,2369,2,1068814752,1,120,'',0),(6065,268,2369,0,134,2362,2402,2,1068814752,1,120,'',0),(6066,268,2402,0,135,2369,2385,2,1068814752,1,120,'',0),(6067,268,2385,0,136,2402,2365,2,1068814752,1,120,'',0),(6068,268,2365,0,137,2385,2359,2,1068814752,1,120,'',0),(6069,268,2359,0,138,2365,2417,2,1068814752,1,120,'',0),(6070,268,2417,0,139,2359,2381,2,1068814752,1,120,'',0),(6071,268,2381,0,140,2417,2377,2,1068814752,1,120,'',0),(6072,268,2377,0,141,2381,2418,2,1068814752,1,120,'',0),(6073,268,2418,0,142,2377,2351,2,1068814752,1,120,'',0),(6074,268,2351,0,143,2418,2419,2,1068814752,1,120,'',0),(6075,268,2419,0,144,2351,2420,2,1068814752,1,120,'',0),(6076,268,2420,0,145,2419,2387,2,1068814752,1,120,'',0),(6077,268,2387,0,146,2420,2349,2,1068814752,1,120,'',0),(6078,268,2349,0,147,2387,2421,2,1068814752,1,120,'',0),(6079,268,2421,0,148,2349,2394,2,1068814752,1,120,'',0),(6080,268,2394,0,149,2421,2368,2,1068814752,1,120,'',0),(6081,268,2368,0,150,2394,2406,2,1068814752,1,120,'',0),(6082,268,2406,0,151,2368,2341,2,1068814752,1,120,'',0),(6083,268,2341,0,152,2406,2409,2,1068814752,1,120,'',0),(6084,268,2409,0,153,2341,2403,2,1068814752,1,120,'',0),(6085,268,2403,0,154,2409,89,2,1068814752,1,120,'',0),(6086,268,89,0,155,2403,2422,2,1068814752,1,120,'',0),(6087,268,2422,0,156,89,2368,2,1068814752,1,120,'',0),(6088,268,2368,0,157,2422,2397,2,1068814752,1,120,'',0),(6089,268,2397,0,158,2368,2423,2,1068814752,1,120,'',0),(6090,268,2423,0,159,2397,2424,2,1068814752,1,120,'',0),(6091,268,2424,0,160,2423,2345,2,1068814752,1,120,'',0),(6092,268,2345,0,161,2424,2387,2,1068814752,1,120,'',0),(6093,268,2387,0,162,2345,2376,2,1068814752,1,120,'',0),(6039,268,2361,0,108,2377,2357,2,1068814752,1,120,'',0),(6035,268,2405,0,104,2346,2367,2,1068814752,1,120,'',0),(6036,268,2367,0,105,2405,1388,2,1068814752,1,120,'',0),(6037,268,1388,0,106,2367,2377,2,1068814752,1,120,'',0),(6038,268,2377,0,107,1388,2361,2,1068814752,1,120,'',0),(6030,268,2402,0,99,2401,2403,2,1068814752,1,120,'',0),(6029,268,2401,0,98,2350,2402,2,1068814752,1,120,'',0),(6028,268,2350,0,97,2400,2401,2,1068814752,1,120,'',0),(6027,268,2400,0,96,2399,2350,2,1068814752,1,120,'',0),(6026,268,2399,0,95,2398,2400,2,1068814752,1,120,'',0),(6025,268,2398,0,94,2397,2399,2,1068814752,1,120,'',0),(6020,268,2394,0,89,2393,2360,2,1068814752,1,120,'',0),(6021,268,2360,0,90,2394,2395,2,1068814752,1,120,'',0),(6022,268,2395,0,91,2360,2396,2,1068814752,1,120,'',0),(6023,268,2396,0,92,2395,2397,2,1068814752,1,120,'',0),(6024,268,2397,0,93,2396,2398,2,1068814752,1,120,'',0),(6314,268,2387,0,383,2415,2348,2,1068814752,1,121,'',0),(6313,268,2415,0,382,2404,2387,2,1068814752,1,121,'',0),(6312,268,2404,0,381,2434,2415,2,1068814752,1,121,'',0),(6311,268,2434,0,380,2433,2404,2,1068814752,1,121,'',0),(6310,268,2433,0,379,2432,2434,2,1068814752,1,121,'',0),(6309,268,2432,0,378,2337,2433,2,1068814752,1,121,'',0),(6308,268,2337,0,377,2355,2432,2,1068814752,1,121,'',0),(6307,268,2355,0,376,2406,2337,2,1068814752,1,121,'',0),(6306,268,2406,0,375,2401,2355,2,1068814752,1,121,'',0),(6305,268,2401,0,374,2431,2406,2,1068814752,1,121,'',0),(6304,268,2431,0,373,2356,2401,2,1068814752,1,121,'',0),(6303,268,2356,0,372,2352,2431,2,1068814752,1,121,'',0),(6302,268,2352,0,371,2397,2356,2,1068814752,1,121,'',0),(6301,268,2397,0,370,2430,2352,2,1068814752,1,121,'',0),(6300,268,2430,0,369,2429,2397,2,1068814752,1,121,'',0),(6299,268,2429,0,368,2428,2430,2,1068814752,1,121,'',0),(6298,268,2428,0,367,2427,2429,2,1068814752,1,121,'',0),(6297,268,2427,0,366,2348,2428,2,1068814752,1,121,'',0),(6296,268,2348,0,365,2389,2427,2,1068814752,1,121,'',0),(6295,268,2389,0,364,2336,2348,2,1068814752,1,121,'',0),(6294,268,2336,0,363,2426,2389,2,1068814752,1,121,'',0),(6293,268,2426,0,362,2361,2336,2,1068814752,1,121,'',0),(6292,268,2361,0,361,2350,2426,2,1068814752,1,121,'',0),(6291,268,2350,0,360,2406,2361,2,1068814752,1,121,'',0),(6290,268,2406,0,359,2348,2350,2,1068814752,1,121,'',0),(6289,268,2348,0,358,2425,2406,2,1068814752,1,121,'',0),(6288,268,2425,0,357,2332,2348,2,1068814752,1,121,'',0),(6287,268,2332,0,356,2397,2425,2,1068814752,1,121,'',0),(6286,268,2397,0,355,2376,2332,2,1068814752,1,121,'',0),(6285,268,2376,0,354,2387,2397,2,1068814752,1,121,'',0),(6284,268,2387,0,353,2345,2376,2,1068814752,1,121,'',0),(6283,268,2345,0,352,2424,2387,2,1068814752,1,121,'',0),(6282,268,2424,0,351,2423,2345,2,1068814752,1,121,'',0),(6281,268,2423,0,350,2397,2424,2,1068814752,1,121,'',0),(6280,268,2397,0,349,2368,2423,2,1068814752,1,121,'',0),(6279,268,2368,0,348,2422,2397,2,1068814752,1,121,'',0),(6278,268,2422,0,347,89,2368,2,1068814752,1,121,'',0),(6277,268,89,0,346,2403,2422,2,1068814752,1,121,'',0),(6276,268,2403,0,345,2409,89,2,1068814752,1,121,'',0),(6275,268,2409,0,344,2341,2403,2,1068814752,1,121,'',0),(6274,268,2341,0,343,2406,2409,2,1068814752,1,121,'',0),(6273,268,2406,0,342,2368,2341,2,1068814752,1,121,'',0),(6272,268,2368,0,341,2394,2406,2,1068814752,1,121,'',0),(6271,268,2394,0,340,2421,2368,2,1068814752,1,121,'',0),(6270,268,2421,0,339,2349,2394,2,1068814752,1,121,'',0),(6269,268,2349,0,338,2387,2421,2,1068814752,1,121,'',0),(6268,268,2387,0,337,2420,2349,2,1068814752,1,121,'',0),(6267,268,2420,0,336,2419,2387,2,1068814752,1,121,'',0),(6266,268,2419,0,335,2351,2420,2,1068814752,1,121,'',0),(6265,268,2351,0,334,2418,2419,2,1068814752,1,121,'',0),(6264,268,2418,0,333,2377,2351,2,1068814752,1,121,'',0),(6263,268,2377,0,332,2381,2418,2,1068814752,1,121,'',0),(6262,268,2381,0,331,2417,2377,2,1068814752,1,121,'',0),(6261,268,2417,0,330,2359,2381,2,1068814752,1,121,'',0),(6260,268,2359,0,329,2365,2417,2,1068814752,1,121,'',0),(6259,268,2365,0,328,2385,2359,2,1068814752,1,121,'',0),(6258,268,2385,0,327,2402,2365,2,1068814752,1,121,'',0),(6257,268,2402,0,326,2369,2385,2,1068814752,1,121,'',0),(6256,268,2369,0,325,2362,2402,2,1068814752,1,121,'',0),(6255,268,2362,0,324,2332,2369,2,1068814752,1,121,'',0),(6254,268,2332,0,323,2416,2362,2,1068814752,1,121,'',0),(6253,268,2416,0,322,2381,2332,2,1068814752,1,121,'',0),(6252,268,2381,0,321,2342,2416,2,1068814752,1,121,'',0),(6251,268,2342,0,320,2415,2381,2,1068814752,1,121,'',0),(6250,268,2415,0,319,2379,2342,2,1068814752,1,121,'',0),(6249,268,2379,0,318,2348,2415,2,1068814752,1,121,'',0),(6248,268,2348,0,317,2414,2379,2,1068814752,1,121,'',0),(6247,268,2414,0,316,2413,2348,2,1068814752,1,121,'',0),(6246,268,2413,0,315,2412,2414,2,1068814752,1,121,'',0),(6245,268,2412,0,314,2411,2413,2,1068814752,1,121,'',0),(6244,268,2411,0,313,2410,2412,2,1068814752,1,121,'',0),(6243,268,2410,0,312,1388,2411,2,1068814752,1,121,'',0),(6315,268,2348,0,384,2387,0,2,1068814752,1,121,'',0),(5978,268,2366,0,47,2365,2367,2,1068814752,1,120,'',0),(5972,268,2360,0,41,2359,2361,2,1068814752,1,120,'',0),(5973,268,2361,0,42,2360,2362,2,1068814752,1,120,'',0),(5974,268,2362,0,43,2361,2363,2,1068814752,1,120,'',0),(5975,268,2363,0,44,2362,2364,2,1068814752,1,120,'',0),(5976,268,2364,0,45,2363,2365,2,1068814752,1,120,'',0),(5977,268,2365,0,46,2364,2366,2,1068814752,1,120,'',0),(5971,268,2359,0,40,89,2360,2,1068814752,1,120,'',0),(5970,268,89,0,39,2358,2359,2,1068814752,1,120,'',0),(5969,268,2358,0,38,2357,89,2,1068814752,1,120,'',0),(5968,268,2357,0,37,2356,2358,2,1068814752,1,120,'',0),(5964,268,2354,0,33,2353,2338,2,1068814752,1,120,'',0),(5965,268,2338,0,34,2354,2355,2,1068814752,1,120,'',0),(5966,268,2355,0,35,2338,2356,2,1068814752,1,120,'',0),(5967,268,2356,0,36,2355,2357,2,1068814752,1,120,'',0),(5479,331,2280,0,2,2279,2281,5,1069318446,1,117,'',0),(5480,331,2281,0,3,2280,2249,5,1069318446,1,117,'',0),(5481,331,2249,0,4,2281,89,5,1069318446,1,117,'',0),(5482,331,89,0,5,2249,2278,5,1069318446,1,117,'',0),(5483,331,2278,0,6,89,2282,5,1069318446,1,117,'',0),(5484,331,2282,0,7,2278,0,5,1069318446,1,117,'',0),(5485,332,2283,0,0,0,2284,5,1069318482,1,116,'',0),(5486,332,2284,0,1,2283,2283,5,1069318482,1,116,'',0),(5487,332,2283,0,2,2284,2284,5,1069318482,1,117,'',0),(5488,332,2284,0,3,2283,2285,5,1069318482,1,117,'',0),(5489,332,2285,0,4,2284,2273,5,1069318482,1,117,'',0),(5490,332,2273,0,5,2285,1361,5,1069318482,1,117,'',0),(5491,332,1361,0,6,2273,0,5,1069318482,1,117,'',0),(5492,333,2286,0,0,0,2287,5,1069318517,1,116,'',0),(5493,333,2287,0,1,2286,2259,5,1069318517,1,116,'',0),(5494,333,2259,0,2,2287,2288,5,1069318517,1,117,'',0),(5495,333,2288,0,3,2259,2289,5,1069318517,1,117,'',0),(5496,333,2289,0,4,2288,2290,5,1069318517,1,117,'',0),(5497,333,2290,0,5,2289,2291,5,1069318517,1,117,'',0),(5498,333,2291,0,6,2290,0,5,1069318517,1,117,'',0),(5499,334,2292,0,0,0,2292,5,1069318560,1,116,'',0),(5500,334,2292,0,1,2292,2293,5,1069318560,1,117,'',0),(5501,334,2293,0,2,2292,2294,5,1069318560,1,117,'',0),(5502,334,2294,0,3,2293,0,5,1069318560,1,117,'',0),(5503,335,2295,0,0,0,2296,5,1069318590,1,116,'',0),(5504,335,2296,0,1,2295,2297,5,1069318590,1,117,'',0),(5505,335,2297,0,2,2296,2298,5,1069318590,1,117,'',0),(5506,335,2298,0,3,2297,2299,5,1069318590,1,117,'',0),(5507,335,2299,0,4,2298,2249,5,1069318590,1,117,'',0),(5508,335,2249,0,5,2299,2300,5,1069318590,1,117,'',0),(5509,335,2300,0,6,2249,0,5,1069318590,1,117,'',0),(5960,268,2350,0,29,2349,2351,2,1068814752,1,120,'',0),(5959,268,2349,0,28,2348,2350,2,1068814752,1,120,'',0),(5958,268,2348,0,27,2347,2349,2,1068814752,1,120,'',0),(5957,268,2347,0,26,1388,2348,2,1068814752,1,120,'',0),(6339,56,2461,0,7,2460,0,15,1066643397,11,224,'',0),(6338,56,2460,0,6,2459,2461,15,1066643397,11,224,'',0),(6337,56,2459,0,5,2458,2460,15,1066643397,11,224,'',0),(6336,56,2458,0,4,2457,2459,15,1066643397,11,224,'',0),(6335,56,2457,0,3,2456,2458,15,1066643397,11,224,'',0),(6334,56,2456,0,2,2455,2457,15,1066643397,11,224,'',0),(6333,56,2455,0,1,2454,2456,15,1066643397,11,224,'',0),(6332,56,2454,0,0,0,2455,15,1066643397,11,161,'',0),(5949,268,2342,0,18,2341,2336,2,1068814752,1,120,'',0),(5950,268,2336,0,19,2342,2343,2,1068814752,1,120,'',0),(5951,268,2343,0,20,2336,2344,2,1068814752,1,120,'',0),(5952,268,2344,0,21,2343,2345,2,1068814752,1,120,'',0),(5953,268,2345,0,22,2344,2346,2,1068814752,1,120,'',0),(5954,268,2346,0,23,2345,2338,2,1068814752,1,120,'',0),(5955,268,2338,0,24,2346,1388,2,1068814752,1,120,'',0),(5956,268,1388,0,25,2338,2347,2,1068814752,1,120,'',0),(6230,268,2361,0,299,2377,2357,2,1068814752,1,121,'',0),(6229,268,2377,0,298,1388,2361,2,1068814752,1,121,'',0),(6228,268,1388,0,297,2367,2377,2,1068814752,1,121,'',0),(6227,268,2367,0,296,2405,1388,2,1068814752,1,121,'',0),(6226,268,2405,0,295,2346,2367,2,1068814752,1,121,'',0),(6225,268,2346,0,294,2394,2405,2,1068814752,1,121,'',0),(6224,268,2394,0,293,2404,2346,2,1068814752,1,121,'',0),(6223,268,2404,0,292,2403,2394,2,1068814752,1,121,'',0),(6222,268,2403,0,291,2402,2404,2,1068814752,1,121,'',0),(6221,268,2402,0,290,2401,2403,2,1068814752,1,121,'',0),(6220,268,2401,0,289,2350,2402,2,1068814752,1,121,'',0),(6219,268,2350,0,288,2400,2401,2,1068814752,1,121,'',0),(6218,268,2400,0,287,2399,2350,2,1068814752,1,121,'',0),(6217,268,2399,0,286,2398,2400,2,1068814752,1,121,'',0),(6216,268,2398,0,285,2397,2399,2,1068814752,1,121,'',0),(6215,268,2397,0,284,2396,2398,2,1068814752,1,121,'',0),(6214,268,2396,0,283,2395,2397,2,1068814752,1,121,'',0),(6213,268,2395,0,282,2360,2396,2,1068814752,1,121,'',0),(6212,268,2360,0,281,2394,2395,2,1068814752,1,121,'',0),(6211,268,2394,0,280,2393,2360,2,1068814752,1,121,'',0),(6210,268,2393,0,279,2362,2394,2,1068814752,1,121,'',0),(6209,268,2362,0,278,2392,2393,2,1068814752,1,121,'',0),(6208,268,2392,0,277,2391,2362,2,1068814752,1,121,'',0),(6207,268,2391,0,276,2390,2392,2,1068814752,1,121,'',0),(6206,268,2390,0,275,2342,2391,2,1068814752,1,121,'',0),(6205,268,2342,0,274,2390,2390,2,1068814752,1,121,'',0),(6204,268,2390,0,273,2389,2342,2,1068814752,1,121,'',0),(6203,268,2389,0,272,2388,2390,2,1068814752,1,121,'',0),(6202,268,2388,0,271,2387,2389,2,1068814752,1,121,'',0),(6201,268,2387,0,270,2386,2388,2,1068814752,1,121,'',0),(6200,268,2386,0,269,2385,2387,2,1068814752,1,121,'',0),(6199,268,2385,0,268,2384,2386,2,1068814752,1,121,'',0),(6198,268,2384,0,267,2355,2385,2,1068814752,1,121,'',0),(6197,268,2355,0,266,1388,2384,2,1068814752,1,121,'',0),(6196,268,1388,0,265,2383,2355,2,1068814752,1,121,'',0),(6195,268,2383,0,264,2382,1388,2,1068814752,1,121,'',0),(6194,268,2382,0,263,2381,2383,2,1068814752,1,121,'',0),(6193,268,2381,0,262,2380,2382,2,1068814752,1,121,'',0),(6192,268,2380,0,261,2379,2381,2,1068814752,1,121,'',0),(6191,268,2379,0,260,2330,2380,2,1068814752,1,121,'',0),(6190,268,2330,0,259,2342,2379,2,1068814752,1,121,'',0),(6189,268,2342,0,258,2378,2330,2,1068814752,1,121,'',0),(6188,268,2378,0,257,2377,2342,2,1068814752,1,121,'',0),(6187,268,2377,0,256,2359,2378,2,1068814752,1,121,'',0),(6186,268,2359,0,255,1388,2377,2,1068814752,1,121,'',0),(6185,268,1388,0,254,2376,2359,2,1068814752,1,121,'',0),(6184,268,2376,0,253,2375,1388,2,1068814752,1,121,'',0),(6183,268,2375,0,252,2374,2376,2,1068814752,1,121,'',0),(6182,268,2374,0,251,2342,2375,2,1068814752,1,121,'',0),(6181,268,2342,0,250,2374,2374,2,1068814752,1,121,'',0),(6180,268,2374,0,249,2373,2342,2,1068814752,1,121,'',0),(6179,268,2373,0,248,2372,2374,2,1068814752,1,121,'',0),(6178,268,2372,0,247,2349,2373,2,1068814752,1,121,'',0),(6177,268,2349,0,246,2371,2372,2,1068814752,1,121,'',0),(6176,268,2371,0,245,2370,2349,2,1068814752,1,121,'',0),(6175,268,2370,0,244,2369,2371,2,1068814752,1,121,'',0),(6174,268,2369,0,243,2368,2370,2,1068814752,1,121,'',0),(6173,268,2368,0,242,2337,2369,2,1068814752,1,121,'',0),(6172,268,2337,0,241,2348,2368,2,1068814752,1,121,'',0),(6171,268,2348,0,240,2367,2337,2,1068814752,1,121,'',0),(6170,268,2367,0,239,2366,2348,2,1068814752,1,121,'',0),(6169,268,2366,0,238,2365,2367,2,1068814752,1,121,'',0),(6168,268,2365,0,237,2364,2366,2,1068814752,1,121,'',0),(6167,268,2364,0,236,2363,2365,2,1068814752,1,121,'',0),(6166,268,2363,0,235,2362,2364,2,1068814752,1,121,'',0),(6165,268,2362,0,234,2361,2363,2,1068814752,1,121,'',0),(6164,268,2361,0,233,2360,2362,2,1068814752,1,121,'',0),(6163,268,2360,0,232,2359,2361,2,1068814752,1,121,'',0),(6162,268,2359,0,231,89,2360,2,1068814752,1,121,'',0),(6161,268,89,0,230,2358,2359,2,1068814752,1,121,'',0),(6160,268,2358,0,229,2357,89,2,1068814752,1,121,'',0),(6159,268,2357,0,228,2356,2358,2,1068814752,1,121,'',0),(6158,268,2356,0,227,2355,2357,2,1068814752,1,121,'',0),(6157,268,2355,0,226,2338,2356,2,1068814752,1,121,'',0),(5520,320,2249,0,2,2307,2308,28,1069317685,1,218,'',0),(5519,320,2307,0,1,2306,2249,28,1069317685,1,218,'',0),(5417,319,2245,0,0,0,2245,27,1069317649,1,215,'',0),(5418,319,2245,0,1,2245,2246,27,1069317649,1,216,'',0),(5419,319,2246,0,2,2245,0,27,1069317649,1,216,'',0),(5987,268,2372,0,56,2349,2373,2,1068814752,1,120,'',0),(5986,268,2349,0,55,2371,2372,2,1068814752,1,120,'',0),(5985,268,2371,0,54,2370,2349,2,1068814752,1,120,'',0),(5984,268,2370,0,53,2369,2371,2,1068814752,1,120,'',0),(5983,268,2369,0,52,2368,2370,2,1068814752,1,120,'',0),(5981,268,2337,0,50,2348,2368,2,1068814752,1,120,'',0),(5982,268,2368,0,51,2337,2369,2,1068814752,1,120,'',0),(5992,268,2375,0,61,2374,2376,2,1068814752,1,120,'',0),(5991,268,2374,0,60,2342,2375,2,1068814752,1,120,'',0),(5990,268,2342,0,59,2374,2374,2,1068814752,1,120,'',0),(5988,268,2373,0,57,2372,2374,2,1068814752,1,120,'',0),(5989,268,2374,0,58,2373,2342,2,1068814752,1,120,'',0),(5996,268,2377,0,65,2359,2378,2,1068814752,1,120,'',0),(5995,268,2359,0,64,1388,2377,2,1068814752,1,120,'',0),(5993,268,2376,0,62,2375,1388,2,1068814752,1,120,'',0),(5994,268,1388,0,63,2376,2359,2,1068814752,1,120,'',0),(6001,268,2380,0,70,2379,2381,2,1068814752,1,120,'',0),(6000,268,2379,0,69,2330,2380,2,1068814752,1,120,'',0),(5999,268,2330,0,68,2342,2379,2,1068814752,1,120,'',0),(5997,268,2378,0,66,2377,2342,2,1068814752,1,120,'',0),(5998,268,2342,0,67,2378,2330,2,1068814752,1,120,'',0),(6002,268,2381,0,71,2380,2382,2,1068814752,1,120,'',0),(6003,268,2382,0,72,2381,2383,2,1068814752,1,120,'',0),(5979,268,2367,0,48,2366,2348,2,1068814752,1,120,'',0),(5980,268,2348,0,49,2367,2337,2,1068814752,1,120,'',0),(6032,268,2404,0,101,2403,2394,2,1068814752,1,120,'',0),(6033,268,2394,0,102,2404,2346,2,1068814752,1,120,'',0),(6034,268,2346,0,103,2394,2405,2,1068814752,1,120,'',0),(6031,268,2403,0,100,2402,2404,2,1068814752,1,120,'',0),(6019,268,2393,0,88,2362,2394,2,1068814752,1,120,'',0),(6018,268,2362,0,87,2392,2393,2,1068814752,1,120,'',0),(6017,268,2392,0,86,2391,2362,2,1068814752,1,120,'',0),(6006,268,2355,0,75,1388,2384,2,1068814752,1,120,'',0),(6004,268,2383,0,73,2382,1388,2,1068814752,1,120,'',0),(6005,268,1388,0,74,2383,2355,2,1068814752,1,120,'',0),(6016,268,2391,0,85,2390,2392,2,1068814752,1,120,'',0),(6015,268,2390,0,84,2342,2391,2,1068814752,1,120,'',0),(6014,268,2342,0,83,2390,2390,2,1068814752,1,120,'',0),(6013,268,2390,0,82,2389,2342,2,1068814752,1,120,'',0),(6007,268,2384,0,76,2355,2385,2,1068814752,1,120,'',0),(6008,268,2385,0,77,2384,2386,2,1068814752,1,120,'',0),(6009,268,2386,0,78,2385,2387,2,1068814752,1,120,'',0),(6010,268,2387,0,79,2386,2388,2,1068814752,1,120,'',0),(6011,268,2388,0,80,2387,2389,2,1068814752,1,120,'',0),(6012,268,2389,0,81,2388,2390,2,1068814752,1,120,'',0),(5462,327,2270,0,2,2271,2271,5,1069317978,1,117,'',0),(5463,327,2271,0,3,2270,0,5,1069317978,1,117,'',0),(5464,328,2272,0,0,0,2265,5,1069318020,1,116,'',0),(5465,328,2265,0,1,2272,2265,5,1069318020,1,116,'',0),(5466,328,2265,0,2,2265,2273,5,1069318020,1,117,'',0),(5467,328,2273,0,3,2265,89,5,1069318020,1,117,'',0),(5468,328,89,0,4,2273,2274,5,1069318020,1,117,'',0),(5469,328,2274,0,5,89,1388,5,1069318020,1,117,'',0),(5470,328,1388,0,6,2274,2275,5,1069318020,1,117,'',0),(5471,328,2275,0,7,1388,0,5,1069318020,1,117,'',0),(5472,329,2276,0,0,0,2276,27,1069318331,1,215,'',0),(5473,329,2276,0,1,2276,2263,27,1069318331,1,216,'',0),(5474,329,2263,0,2,2276,0,27,1069318331,1,216,'',0),(5511,330,2302,0,1,2301,0,28,1069318374,1,221,'',3),(5510,330,2301,0,0,0,2302,28,1069318374,1,217,'',0),(5477,331,2278,0,0,0,2279,5,1069318446,1,116,'',0),(5478,331,2279,0,1,2278,2280,5,1069318446,1,116,'',0),(5459,326,2269,0,4,2268,0,5,1069317947,1,117,'',0),(5460,327,2270,0,0,0,2271,5,1069317978,1,116,'',0),(5461,327,2271,0,1,2270,2270,5,1069317978,1,116,'',0),(5457,326,2266,0,2,2267,2268,5,1069317947,1,117,'',0),(5456,326,2267,0,1,2266,2266,5,1069317947,1,116,'',0),(5455,326,2266,0,0,0,2267,5,1069317947,1,116,'',0),(5454,325,2264,0,6,2254,0,5,1069317907,1,117,'',0),(5518,320,2306,0,0,0,2307,28,1069317685,1,217,'',0),(5426,321,2252,0,0,0,2253,5,1069317728,1,116,'',0),(5427,321,2253,0,1,2252,89,5,1069317728,1,116,'',0),(5428,321,89,0,2,2253,2254,5,1069317728,1,117,'',0),(5429,321,2254,0,3,89,2255,5,1069317728,1,117,'',0),(5430,321,2255,0,4,2254,2252,5,1069317728,1,117,'',0),(5431,321,2252,0,5,2255,2253,5,1069317728,1,117,'',0),(5432,321,2253,0,6,2252,0,5,1069317728,1,117,'',0),(5433,322,2256,0,0,0,2257,5,1069317767,1,116,'',0),(5434,322,2257,0,1,2256,89,5,1069317767,1,116,'',0),(5435,322,89,0,2,2257,2256,5,1069317767,1,117,'',0),(5436,322,2256,0,3,89,2258,5,1069317767,1,117,'',0),(5437,322,2258,0,4,2256,2259,5,1069317767,1,117,'',0),(5438,322,2259,0,5,2258,2260,5,1069317767,1,117,'',0),(5439,322,2260,0,6,2259,0,5,1069317767,1,117,'',0),(5440,323,2261,0,0,0,2253,5,1069317797,1,116,'',0),(5441,323,2253,0,1,2261,2261,5,1069317797,1,116,'',0),(5442,323,2261,0,2,2253,2253,5,1069317797,1,117,'',0),(5443,323,2253,0,3,2261,0,5,1069317797,1,117,'',0),(5527,324,2302,0,3,2263,0,28,1069317869,1,221,'',3),(5526,324,2263,0,2,2309,2302,28,1069317869,1,218,'',0),(5525,324,2309,0,1,2309,2263,28,1069317869,1,218,'',0),(5524,324,2309,0,0,0,2309,28,1069317869,1,217,'',0),(5448,325,2264,0,0,0,2265,5,1069317907,1,116,'',0),(5449,325,2265,0,1,2264,2265,5,1069317907,1,116,'',0),(5450,325,2265,0,2,2265,1388,5,1069317907,1,117,'',0),(5451,325,1388,0,3,2265,89,5,1069317907,1,117,'',0),(5452,325,89,0,4,1388,2254,5,1069317907,1,117,'',0),(5453,325,2254,0,5,89,2264,5,1069317907,1,117,'',0),(5946,268,2339,0,15,2338,2340,2,1068814752,1,120,'',0),(5945,268,2338,0,14,2334,2339,2,1068814752,1,120,'',0),(5944,268,2334,0,13,2333,2338,2,1068814752,1,120,'',0),(5943,268,2333,0,12,1388,2334,2,1068814752,1,120,'',0),(5942,268,1388,0,11,2337,2333,2,1068814752,1,120,'',0),(5941,268,2337,0,10,2336,1388,2,1068814752,1,120,'',0),(5940,268,2336,0,9,2335,2337,2,1068814752,1,120,'',0),(5939,268,2335,0,8,2334,2336,2,1068814752,1,120,'',0),(5938,268,2334,0,7,2333,2335,2,1068814752,1,120,'',0),(5937,268,2333,0,6,2332,2334,2,1068814752,1,120,'',0),(5936,268,2332,0,5,2331,2333,2,1068814752,1,120,'',0),(5935,268,2331,0,4,2330,2332,2,1068814752,1,120,'',0),(5934,268,2330,0,3,1925,2331,2,1068814752,1,120,'',0),(5933,268,1925,0,2,2436,2330,2,1068814752,1,1,'',0),(5932,268,2436,0,1,2435,1925,2,1068814752,1,1,'',0),(5931,268,2435,0,0,0,2436,2,1068814752,1,1,'',0),(4976,267,2007,0,1,2006,0,1,1068814364,1,119,'',0),(4975,267,2006,0,0,0,2007,1,1068814364,1,4,'',0),(6148,268,2347,0,217,1388,2348,2,1068814752,1,121,'',0),(6147,268,1388,0,216,2338,2347,2,1068814752,1,121,'',0),(6146,268,2338,0,215,2346,1388,2,1068814752,1,121,'',0),(6145,268,2346,0,214,2345,2338,2,1068814752,1,121,'',0),(6144,268,2345,0,213,2344,2346,2,1068814752,1,121,'',0),(6143,268,2344,0,212,2343,2345,2,1068814752,1,121,'',0),(6142,268,2343,0,211,2336,2344,2,1068814752,1,121,'',0),(6141,268,2336,0,210,2342,2343,2,1068814752,1,121,'',0),(6140,268,2342,0,209,2341,2336,2,1068814752,1,121,'',0),(6104,268,2389,0,173,2336,2348,2,1068814752,1,120,'',0),(6103,268,2336,0,172,2426,2389,2,1068814752,1,120,'',0),(6102,268,2426,0,171,2361,2336,2,1068814752,1,120,'',0),(6101,268,2361,0,170,2350,2426,2,1068814752,1,120,'',0),(6100,268,2350,0,169,2406,2361,2,1068814752,1,120,'',0),(6094,268,2376,0,163,2387,2397,2,1068814752,1,120,'',0),(6095,268,2397,0,164,2376,2332,2,1068814752,1,120,'',0),(6096,268,2332,0,165,2397,2425,2,1068814752,1,120,'',0),(6097,268,2425,0,166,2332,2348,2,1068814752,1,120,'',0),(6098,268,2348,0,167,2425,2406,2,1068814752,1,120,'',0),(6099,268,2406,0,168,2348,2350,2,1068814752,1,120,'',0),(6152,268,2351,0,221,2350,2352,2,1068814752,1,121,'',0),(6151,268,2350,0,220,2349,2351,2,1068814752,1,121,'',0),(6150,268,2349,0,219,2348,2350,2,1068814752,1,121,'',0),(6149,268,2348,0,218,2347,2349,2,1068814752,1,121,'',0),(6116,268,2355,0,185,2406,2337,2,1068814752,1,120,'',0),(6115,268,2406,0,184,2401,2355,2,1068814752,1,120,'',0),(6114,268,2401,0,183,2431,2406,2,1068814752,1,120,'',0),(6113,268,2431,0,182,2356,2401,2,1068814752,1,120,'',0),(6112,268,2356,0,181,2352,2431,2,1068814752,1,120,'',0),(6111,268,2352,0,180,2397,2356,2,1068814752,1,120,'',0),(6110,268,2397,0,179,2430,2352,2,1068814752,1,120,'',0),(6109,268,2430,0,178,2429,2397,2,1068814752,1,120,'',0),(6108,268,2429,0,177,2428,2430,2,1068814752,1,120,'',0),(6107,268,2428,0,176,2427,2429,2,1068814752,1,120,'',0),(6105,268,2348,0,174,2389,2427,2,1068814752,1,120,'',0),(6106,268,2427,0,175,2348,2428,2,1068814752,1,120,'',0),(6126,268,2331,0,195,2437,2332,2,1068814752,1,121,'',0),(6125,268,2437,0,194,2348,2331,2,1068814752,1,121,'',0),(6124,268,2348,0,193,2387,2437,2,1068814752,1,120,'',0),(6123,268,2387,0,192,2415,2348,2,1068814752,1,120,'',0),(6122,268,2415,0,191,2404,2387,2,1068814752,1,120,'',0),(6121,268,2404,0,190,2434,2415,2,1068814752,1,120,'',0),(6120,268,2434,0,189,2433,2404,2,1068814752,1,120,'',0),(6119,268,2433,0,188,2432,2434,2,1068814752,1,120,'',0),(6118,268,2432,0,187,2337,2433,2,1068814752,1,120,'',0),(6117,268,2337,0,186,2355,2432,2,1068814752,1,120,'',0),(6139,268,2341,0,208,2340,2342,2,1068814752,1,121,'',0),(6138,268,2340,0,207,2339,2341,2,1068814752,1,121,'',0),(6137,268,2339,0,206,2338,2340,2,1068814752,1,121,'',0),(6136,268,2338,0,205,2334,2339,2,1068814752,1,121,'',0),(6135,268,2334,0,204,2333,2338,2,1068814752,1,121,'',0),(6134,268,2333,0,203,1388,2334,2,1068814752,1,121,'',0),(6133,268,1388,0,202,2337,2333,2,1068814752,1,121,'',0),(6132,268,2337,0,201,2336,1388,2,1068814752,1,121,'',0),(6127,268,2332,0,196,2331,2333,2,1068814752,1,121,'',0),(6128,268,2333,0,197,2332,2334,2,1068814752,1,121,'',0),(6129,268,2334,0,198,2333,2335,2,1068814752,1,121,'',0),(6130,268,2335,0,199,2334,2336,2,1068814752,1,121,'',0),(6131,268,2336,0,200,2335,2337,2,1068814752,1,121,'',0),(6242,268,1388,0,311,2409,2410,2,1068814752,1,121,'',0),(6241,268,2409,0,310,2408,1388,2,1068814752,1,121,'',0),(6240,268,2408,0,309,2348,2409,2,1068814752,1,121,'',0),(6239,268,2348,0,308,2377,2408,2,1068814752,1,121,'',0),(6238,268,2377,0,307,2392,2348,2,1068814752,1,121,'',0),(6237,268,2392,0,306,2342,2377,2,1068814752,1,121,'',0),(6236,268,2342,0,305,2342,2392,2,1068814752,1,121,'',0),(6235,268,2342,0,304,2407,2342,2,1068814752,1,121,'',0),(6234,268,2407,0,303,2406,2342,2,1068814752,1,121,'',0),(6233,268,2406,0,302,2330,2407,2,1068814752,1,121,'',0),(6232,268,2330,0,301,2357,2406,2,1068814752,1,121,'',0),(6231,268,2357,0,300,2361,2330,2,1068814752,1,121,'',0),(6340,11,1925,0,0,0,2462,3,1033920746,2,6,'',0),(6341,11,2462,0,1,1925,0,3,1033920746,2,6,'',0),(6342,337,1925,0,0,0,2462,4,1070976556,2,8,'',0),(6343,337,2462,0,1,1925,0,4,1070976556,2,9,'',0);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_word'
--

/*!40000 ALTER TABLE ezsearch_word DISABLE KEYS */;
LOCK TABLES ezsearch_word WRITE;
INSERT INTO ezsearch_word VALUES (6,'media',1),(7,'setup',3),(2184,'grouplist',1),(2183,'class',1),(2182,'classes',1),(11,'links',1),(25,'content',2),(34,'feel',2),(33,'and',2),(32,'look',2),(2425,'consequat',2),(2424,'viverra',2),(2423,'sem',2),(2422,'congue',2),(2421,'nec',2),(89,'a',7),(2252,'blue',1),(2420,'suspendisse',2),(2419,'facilisi',2),(2418,'sapien',2),(2417,'non',2),(2416,'ornare',2),(2415,'interdum',2),(2414,'duis',2),(2413,'dictumst',2),(2412,'platea',2),(2411,'habitasse',2),(2181,'cache',1),(2293,'the',1),(2410,'hac',2),(2409,'fringilla',2),(2408,'nonummy',2),(2407,'fermentum',2),(2406,'vestibulum',2),(2405,'erat',2),(2404,'vitae',2),(2403,'mi',2),(2402,'magna',2),(2399,'rhoncus',2),(2400,'lectus',2),(2401,'tempor',2),(1925,'gallery',5),(1362,'developer',1),(1316,'norway',1),(1361,'skien',2),(2398,'nunc',2),(1360,'uberguru',1),(1359,'user',1),(2397,'lacus',2),(2396,'accumsan',2),(2395,'vehicula',2),(2394,'velit',2),(2393,'elementum',2),(2392,'tellus',2),(2391,'suscipit',2),(2390,'commodo',2),(2389,'sagittis',2),(2388,'enim',2),(2387,'vel',2),(2386,'felis',2),(2385,'ullamcorper',2),(2384,'pellentesque',2),(2383,'fusce',2),(2382,'tortor',2),(2381,'scelerisque',2),(2380,'pharetra',2),(2379,'aenean',2),(2378,'facilisis',2),(2377,'ut',2),(2376,'tristique',2),(2375,'eros',2),(2374,'turpis',2),(2373,'eu',2),(2372,'metus',2),(2371,'blandit',2),(2370,'ac',2),(2369,'neque',2),(2368,'dapibus',2),(2367,'volutpat',2),(2366,'iaculis',2),(2365,'id',2),(2364,'purus',2),(2363,'imperdiet',2),(2362,'phasellus',2),(2361,'libero',2),(1388,'in',4),(2360,'at',2),(2359,'tincidunt',2),(2358,'molestie',2),(2357,'eget',2),(2356,'dignissim',2),(2355,'est',2),(2354,'proin',2),(2353,'odio',2),(2186,'56',1),(2185,'edit',1),(2294,'cat',1),(2187,'url',1),(2188,'translator',1),(2189,'urltranslator',1),(1358,'administrator',1),(2352,'morbi',2),(2351,'nulla',2),(2350,'et',2),(2349,'wisi',2),(2348,'diam',2),(2347,'gravida',2),(2346,'aliquam',2),(2345,'quam',2),(2344,'nisl',2),(2343,'eleifend',2),(2342,'sed',2),(2341,'mauris',2),(2340,'egestas',2),(2339,'maecenas',2),(2338,'massa',2),(2337,'elit',2),(2328,'about',1),(2329,'my',1),(2330,'lorem',2),(2331,'ipsum',2),(2332,'dolor',2),(2333,'sit',2),(2334,'amet',2),(2335,'consectetuer',2),(2336,'adipiscing',2),(2284,'wheel',1),(2285,'statue',1),(2286,'green',1),(2287,'clover',1),(2288,'it',1),(2289,'s',1),(2290,'called',1),(2254,'small',2),(2255,'nice',1),(2256,'purple',1),(2257,'haze',1),(2258,'one',1),(2259,'actually',2),(2245,'nature',1),(2246,'images',1),(2308,'various',1),(2307,'pictures',1),(2249,'of',3),(2306,'flowers',1),(2429,'dui',2),(2430,'porttitor',2),(2431,'integer',2),(2298,'legal',1),(2297,'withing',1),(2296,'all',1),(2302,'3',3),(2292,'mjaurits',1),(2291,'gaukesyre',1),(2300,'course',1),(2299,'limits',1),(2432,'cursus',2),(2433,'quis',2),(2434,'laoreet',2),(2295,'speeding',1),(2267,'skyline',1),(2268,'by',1),(2269,'night',1),(2270,'foggy',1),(2271,'trees',1),(2272,'water',1),(2273,'from',2),(2274,'lake',1),(2275,'kongsberg',1),(2276,'abstract',1),(2301,'misc',1),(2278,'cvs',1),(2279,'branching',1),(2280,'visual',1),(2281,'representation',1),(2282,'branch',1),(2265,'reflection',2),(2264,'pond',1),(2260,'two',1),(2261,'yellow',1),(2309,'landscape',1),(2263,'photography',2),(2283,'gear',1),(2266,'ormevika',1),(2253,'flower',2),(2428,'bibendum',2),(2427,'nam',2),(2426,'donec',2),(2461,'2003',1),(2460,'1999',1),(2459,'as',1),(2458,'systems',1),(2457,'ez',1),(2454,'gallery_package',1),(2455,'copyright',1),(2456,'&copy',1),(2007,'latest',1),(2006,'news',1),(2437,'dfghlorem',1),(2436,'new',1),(2435,'added',1),(2462,'editor',2);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsession'
--

/*!40000 ALTER TABLE ezsession DISABLE KEYS */;
LOCK TABLES ezsession WRITE;
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsite_data'
--

/*!40000 ALTER TABLE ezsite_data DISABLE KEYS */;
LOCK TABLES ezsite_data WRITE;
INSERT INTO ezsite_data VALUES ('ezpublish-version','3.3.0'),('ezpublish-release','2');
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsubtree_notification_rule'
--

/*!40000 ALTER TABLE ezsubtree_notification_rule DISABLE KEYS */;
LOCK TABLES ezsubtree_notification_rule WRITE;
INSERT INTO ezsubtree_notification_rule VALUES (1,'nospam@ez.no',0,112),(2,'wy@ez.no',0,112),(3,'nospam@ez.no',0,123),(4,'nospam@ez.no',0,124),(5,'nospam@ez.no',0,135),(6,'wy@ez.no',0,114);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezurlalias'
--

/*!40000 ALTER TABLE ezurlalias DISABLE KEYS */;
LOCK TABLES ezurlalias WRITE;
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,271,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0),(19,'users/gallery_editor/test_test','3fa1d4782f0ab793d0cbb9635e4e9c0d','content/view/full/42',1,0,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(193,'nature/flowers_in_june/marygold/good','63667a5fd9f62f7128802afcbfc3eaa5','content/view/full/187',1,0,0),(125,'discussions/forum_main_group/music_discussion/latest_msg_not_sticky','70cf693961dcdd67766bf941c3ed2202','content/view/full/130',1,0,0),(126,'discussions/forum_main_group/music_discussion/not_sticky_2','969f470c93e2131a0884648b91691d0b','content/view/full/131',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,213,0),(124,'discussions/forum_main_group/music_discussion/new_topic_sticky/reply','f3dd8b6512a0b04b426ef7d7307b7229','content/view/full/129',1,0,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,269,0),(123,'discussions/forum_main_group/music_discussion/new_topic_sticky','bf37b4a370ddb3935d0625a5b348dd20','content/view/full/128',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,213,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,213,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(127,'discussions/forum_main_group/music_discussion/important_sticky','2f16cf3039c97025a43f23182b4b6d60','content/view/full/132',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0),(206,'news/latest_sdfgsdgf','decc79834f40f5a98a8694852ea55bf2','content/view/full/200',1,270,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(104,'discussions/music_discussion','09533dfccc8477debe545d31bccf391f','content/view/full/114',1,149,0),(105,'discussions/forum_main_group/music_discussion/this_is_a_new_topic','cec6b1593bf03079990a89a3fdc60c56','content/view/full/112',1,0,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(107,'discussions/sports_discussion','c551943f4df3c58a693f8ba55e9b6aeb','content/view/full/115',1,151,0),(117,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/foo_bar','741cdf9f1ee1fa974ea7ec755f538271','content/view/full/122',1,0,0),(111,'discussions/forum_main_group/sports_discussion/football','6e9c09d390322aa44bb5108b93f5f17c','content/view/full/119',1,0,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,213,0),(114,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/my_reply','1e03a7609698aa8a98dccf1178df0e6f','content/view/full/120',1,0,0),(118,'discussions/forum_main_group/music_discussion/what_about_pop','c4ebc99b2ed9792d1aee0e5fe210b852','content/view/full/123',1,0,0),(119,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic','6c20d2df5a828dcdb6a4fcb4897bb643','content/view/full/124',1,0,0),(120,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','de98a1bb645ea84919a5e34688ff84e2','content/view/full/125',1,0,0),(128,'discussions/forum_main_group/sports_discussion/football/reply_2','13a443b7e046bb36831640f1d19e33d9','content/view/full/133',1,0,0),(130,'discussions/forum_main_group/music_discussion/lkj_ssssstick','75ee87c770e4e8be9d44200cdb71d071','content/view/full/135',1,0,0),(131,'discussions/forum_main_group/music_discussion/foo','12c58f35c1114deeb172aba728c50ca8','content/view/full/136',1,0,0),(132,'discussions/forum_main_group/music_discussion/lkj_ssssstick/reply','6040856b4ec5bcc1c699d95020005be5','content/view/full/137',1,0,0),(135,'discussions/forum_main_group/music_discussion/lkj_ssssstick/uyuiyui','4c48104ea6e5ec2a78067374d9561fcb','content/view/full/140',1,0,0),(136,'discussions/forum_main_group/music_discussion/test2','53f71d4ff69ffb3bf8c8ccfb525eabd3','content/view/full/141',1,0,0),(137,'discussions/forum_main_group/music_discussion/t4','5da27cda0fbcd5290338b7d22cfd730c','content/view/full/142',1,0,0),(138,'discussions/forum_main_group/music_discussion/lkj_ssssstick/klj_jkl_klj','9ae60fa076882d6807506c2232143d27','content/view/full/143',1,0,0),(139,'discussions/forum_main_group/music_discussion/test2/retest2','a17d07fbbd2d1b6d0fbbf8ca1509cd01','content/view/full/144',1,0,0),(141,'discussions/forum_main_group/music_discussion/lkj_ssssstick/my_reply','1f95000d1f993ffa16a0cf83b78515bf','content/view/full/146',1,0,0),(142,'discussions/forum_main_group/music_discussion/lkj_ssssstick/retest','0686f14064a420e6ee95aabf89c4a4f2','content/view/full/147',1,0,0),(144,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf','21f0ee2122dd5264192adc15c1e69c03','content/view/full/149',1,0,0),(146,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/dfghd_fghklj','460d30ba47855079ac8605e1c8085993','content/view/full/151',1,0,0),(159,'blogs/computers/special_things_happened_today','4427c3eda2e43a04f639ef1d5f1bb71e','content/view/full/156',1,0,0),(158,'blogs/personal/today_i_got_my_new_car','ce9118c9b6c16328082445f6d8098a0d','content/view/full/155',1,0,0),(149,'discussions/forum_main_group/music_discussion','a1a79985f113d5b05b22c9686b46b175','content/view/full/114',1,0,0),(150,'discussions/music_discussion/*','2ec2a3bfcf01ad3f1323390ab26dfeac','discussions/forum_main_group/music_discussion/{1}',1,0,1),(151,'discussions/forum_main_group/sports_discussion','b68c5a82b8b2035eeee5788cb223bb7e','content/view/full/115',1,0,0),(152,'discussions/sports_discussion/*','7acbf48218ca6e1d80c267911860d34f','discussions/forum_main_group/sports_discussion/{1}',1,0,1),(153,'about_me','50793f253d2dc015e93a2f75163b0894','content/view/full/127',1,269,0),(160,'blogs/computers/special_things_happened_today/brd_farstad','4d1dddb2000bdf69e822fb41d4000919','content/view/full/157',1,0,0),(161,'blogs/computers/special_things_happened_today/bbb','afc9fd5431105082994247c0ae0992b3','content/view/full/158',1,0,0),(162,'blogs/personal/for_posteritys_sake','c6c14fe1f69ebc2a9db76192fcb204f5','content/view/full/159',1,0,0),(251,'nature/flowers/purple_haze','86cafbea1918587028e945ef4b683370','content/view/full/251',1,0,0),(190,'nature/flowers_in_june/marygold','4426134a10c2a51fe5474a277d425ca3','content/view/full/185',1,0,0),(191,'nature/flowers_in_june/marygold/brd','1fc258a3660094f111baddb66f526142','content/view/full/186',1,192,0),(192,'nature/flowers_in_june/marygold/nice_image','cb01bf081117199266b52b99e3ccfd70','content/view/full/186',1,0,0),(168,'blogs/computers/special_things_happened_today/brd','40f4dda88233928fac915274a90476b5','content/view/full/165',1,0,0),(169,'links/news/vg','ae1126bc66ec164212018a497469e3b5','content/view/full/166',1,0,0),(170,'blogs/computers/special_things_happened_today/kjh','0cca438ee3d1d3b2cdfaa9d45dbac2a7','content/view/full/167',1,0,0),(171,'links/news/sina_','68e911c6f20934bdc959741837d8d092','content/view/full/168',1,0,0),(172,'blogs/computers/new_big_discovery_today','d174bf1f78f8c3cbf985909a26880d88','content/view/full/169',1,0,0),(173,'links/software/soft_house','aa5de9806ca77bb313e748c9bcf5def8','content/view/full/170',1,0,0),(174,'blogs/computers/no_comments_on_this_one','0df10f829cc6d968d74ece063eaee683','content/view/full/171',1,0,0),(175,'blogs/computers/new_big_discovery_today/brd','2aee5cbd251dbc484e78fba61e5bb7cf','content/view/full/172',1,0,0),(261,'nature/landscape','c414de967eedae8262a7354d5e3e866a','content/view/full/253',1,0,0),(179,'blogs/computers/new_big_discovery_today/ghghj','cd10884873caf4a20621b35199f331c4','content/view/full/175',1,0,0),(194,'nature/flowers_in_june/green','9da501e5531da587ec568f73eb5c00a3','content/view/full/188',1,0,0),(181,'blogs/entertainment/a_pirates_life','bb23fe0ca4a2afc405c4a70d5ff0abd0','content/view/full/177',1,0,0),(182,'setup/look_and_feel/blog','a0aa455a1c24b5d1d0448546c83836cf','content/view/full/54',1,213,0),(183,'blogs/entertainment/a_pirates_life/kjlh','dbf2c1455eff8c6100181582298d197f','content/view/full/178',1,0,0),(184,'blogs/entertainment/a_pirates_life/kjhkjh','e73acc89936bc771971a97eb45d51c66','content/view/full/179',1,0,0),(185,'blogs/computers/i_overslept_today','9497b5cd127ce3f9f04e3d74c8fc4da5','content/view/full/180',1,0,0),(196,'people/asia_people/suchi','2b6ceb88b365cbf425b48a000442a654','content/view/full/190',1,0,0),(197,'people/asia_people/maid','4dc59141caa2b7a1cb9ec01ca94ebfc3','content/view/full/191',1,0,0),(198,'people/asia_people/ellen','f52e1d82b911e65778e70f3cc75916df','content/view/full/192',1,0,0),(199,'nature/flowers_in_june/green/nice_image','7545f6989baf13ac6bedeab474e3de9c','content/view/full/193',1,0,0),(200,'nature/flowers_in_june/green/ool','83d2ae1be41ce0d5fc0875bd94b556a1','content/view/full/194',1,0,0),(201,'nature/flowers_in_june/green/ooh','40b0363eb8880262642a4e0c42594f5c','content/view/full/195',1,0,0),(202,'nature/flowers_in_june/marygold/dsfgsdgf','03f4289cc8b98a14acc4ae78c3649025','content/view/full/196',1,0,0),(203,'nature/flowers_in_june/foo','8e80ad1e11fa10ea3fd00771c45d2a2d','content/view/full/197',1,0,0),(204,'nature/flowers_in_june/marygold/jkhjkhk','aab1582af8e975338c9221189b17d6cb','content/view/full/198',1,0,0),(205,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/199',1,0,0),(207,'nature/flowers_in_june/foo/nice_feel','3b31350a2dd3df6615cb7a36410328c5','content/view/full/201',1,0,0),(208,'people/asia_people/xiake','8281574139ed78c6ea3396616e2dfb20','content/view/full/202',1,0,0),(209,'nature/lucky','a38b40db7afdadf093ab684ed97a9bb8','content/view/full/203',1,216,0),(210,'nature/flowers_in_june/limestone','4840af0c64a8f374728205ee032f41c9','content/view/full/204',1,0,0),(211,'nature/flowers_in_june/bombwall_boz','e773dfe30e5575d1ccf6ac40f2748626','content/view/full/205',1,0,0),(212,'nature/flowers_in_june/hedgehog','f66f727f82b616a90673e04f2dc3cfff','content/view/full/206',1,0,0),(213,'setup/look_and_feel/my_gallery','da1e93305d8b5181634ebdb1319569bd','content/view/full/54',1,0,0),(215,'nature/games/cgwloading1600','2b5a3fdfd44ebecbb82164584eb7c81b','content/view/full/208',1,0,0),(216,'nature/flowers_in_june/lucky','81aac1ed0b07b6bf549ddf4a82288135','content/view/full/203',1,0,0),(250,'nature/flowers/blue_flower','5b763d7e491af63d009ac03b80239aba','content/view/full/250',1,0,0),(249,'nature__1/*','07ead2373ee62cfa2b9ab3251c499c97','nature/{1}',1,0,1),(219,'nature/games/champ01','2d1a70f41c99db78ed6f8923c5979c23','content/view/full/211',1,0,0),(220,'nature/games/cover','5bd1a87c7d91e0e069b5b324f33a2229','content/view/full/212',1,0,0),(221,'nature/games/cgimage06','869eb2ff6c14ddbf9414611a63c44b96','content/view/full/213',1,0,0),(222,'nature/animals/sky_scraper','0a6f23861db026d58d0600c36a583ec0','content/view/full/214',1,0,0),(223,'nature/animals/creepybox','37f15623b438e349897a577cba4d441b','content/view/full/215',1,0,0),(224,'nature/animals/cow','31353785d3488a6f94248b5df1c461fe','content/view/full/216',1,0,0),(258,'nature','405aaff66082ffe7231d7c1f79926c17','content/view/full/248',1,0,0),(226,'nature/my_pictures/lw0000039','70e9761265394b58ecec8d8797a402ab','content/view/full/218',1,0,0),(227,'nature/my_pictures/blomst','d070278f420a9e5b0f6c2f9a24622ad4','content/view/full/219',1,0,0),(228,'nature/flowers_in_june/lucky/nice_one','f470c036c048cba377e314209f72bd59','content/view/full/220',1,0,0),(230,'cars/broom_broom/crash','8c562c66f14b575e70041f6014a68a1e','content/view/full/222',1,0,0),(231,'nature/games/games_for_you','f23dc9c43404a57731461d243990484d','content/view/full/223',1,0,0),(233,'people/games/blurry_people','aa9a56f79db1f52b2f9333ddeca5f3fc','content/view/full/225',1,0,0),(234,'people/games/outcast','9ed4a0ecaf24cfa841e766e9c57a65fc','content/view/full/226',1,0,0),(235,'people/games/blacksmith','d185b17e748c5ae0ea463da861c3e6b8','content/view/full/227',1,0,0),(238,'games/jedi_knight/logo','9bb045dbefa56497c80667fe6589f521','content/view/full/230',1,0,0),(262,'nature/landscape/ormevika_skyline','160b1c14354a6dd8c474dfa25cc1bc2b','content/view/full/255',1,0,0),(241,'abstract/misc/green_branch','31027a374a0ab73a2f50e3b462b9d6a0','content/view/full/233',1,0,0),(242,'abstract/misc/mjaurits','e466c08f08d86491f0cdcd25f6fdec89','content/view/full/263',1,0,0),(243,'abstract/misc/gear_wheel','a9e42e6e94ea7f05fb85e81304f6c9d2','content/view/full/261',1,0,0),(244,'abstract/misc/clover','5ee1e0f9265330fb866ff5033e5566e7','content/view/full/236',1,0,0),(245,'abstract/misc/the_need_for_speed','28a175b764b7d4fe0e15823b1406ec8f','content/view/full/237',1,0,0),(259,'nature/flowers','fcf4f3ad05704e53c28c28dd615dfed1','content/view/full/249',1,0,0),(252,'nature/flowers/yellow','1794c04296bdb61ef2a829fbb2b43dbd','content/view/full/242',1,0,0),(260,'nature/flowers/yellow_flower','b4eda4f4f56369fa2335c7926bd80e7e','content/view/full/252',1,0,0),(254,'nature/landscape/pond_reflection','a44de60ea29ebe58a0daf6032835ff13','content/view/full/254',1,0,0),(255,'nature/landscape/skyline','bab64e01e92bb8021b4142b8d1175fcf','content/view/full/245',1,0,0),(256,'nature/landscape/foggy_trees','0f1800c387d13296b66c1a9dbdbeb3cd','content/view/full/256',1,0,0),(257,'nature/landscape/water','d6ecab04885623ee57b21e1be8175667','content/view/full/247',1,0,0),(263,'nature/landscape/water_reflection','a0780ff9569ed64f0ea0975190b0ec0b','content/view/full/257',1,0,0),(264,'abstract','ce28071e1a0424ba1b7956dd3853c7fb','content/view/full/258',1,0,0),(265,'abstract/misc','514b370e8de983586f80a3069b026ed0','content/view/full/259',1,0,0),(266,'abstract/misc/cvs_branching','e78f8f18386ca133abf85de1fdb99a9f','content/view/full/260',1,0,0),(267,'abstract/misc/green_clover','720c8416fde86772a895e172a36cc80d','content/view/full/262',1,0,0),(268,'abstract/misc/speeding','dccac6ce16572084195113b46fa28036','content/view/full/264',1,0,0),(269,'about_my_gallery','f9546701fd4ce1c2d4fa393c2d9ab4ac','content/view/full/127',1,0,0),(270,'news/added_new_gallery','b0d34695c66ecec9d3cad7d15626fbe9','content/view/full/200',1,0,0),(271,'users/gallery_editor','062c92c946f51a1b5f59c690884d4bdb','content/view/full/12',1,0,0),(272,'users/gallery_editor/gallery_editor','ce0cc39926cb1f9b4ad37d79f923d30e','content/view/full/265',1,0,0);
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser'
--

/*!40000 ALTER TABLE ezuser DISABLE KEYS */;
LOCK TABLES ezuser WRITE;
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d'),(187,'','',1,''),(189,'','',1,''),(336,'','',2,'cb2f6b2b2c106a1d0aa0c30f1829b40a'),(337,'gallery','galleryeditor@example.com',2,'4bef7c4a5ea969ec7204e5357e1ff565');
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser_role'
--

/*!40000 ALTER TABLE ezuser_role DISABLE KEYS */;
LOCK TABLES ezuser_role WRITE;
INSERT INTO ezuser_role VALUES (29,1,10),(25,2,12),(28,1,11),(34,1,13),(35,8,11);
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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser_setting'
--

/*!40000 ALTER TABLE ezuser_setting DISABLE KEYS */;
LOCK TABLES ezuser_setting WRITE;
INSERT INTO ezuser_setting VALUES (10,1,1000),(14,1,10),(23,1,0),(40,1,0),(107,1,0),(108,1,0),(109,1,0),(111,1,0),(130,1,0),(149,1,0),(154,0,0),(187,1,0),(188,0,0),(189,1,0),(197,0,0),(198,1,0),(206,1,0),(336,1,0),(337,1,0);
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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_process'
--

/*!40000 ALTER TABLE ezworkflow_process DISABLE KEYS */;
LOCK TABLES ezworkflow_process WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_process ENABLE KEYS */;

alter table ezrss_export add rss_version varchar(255) default null;
