-- MySQL dump 10.2
--
-- Host: localhost    Database: news
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
INSERT INTO ezcontentbrowserecent VALUES (35,111,99,1067006746,'foo bar corp'),(65,149,135,1068126974,'lkj ssssstick'),(100,10,182,1069245323,'Breaking news'),(64,206,135,1068123651,'lkj ssssstick'),(109,14,187,1069685704,'Technology');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1069069785),(2,0,'Article','article','<title>',14,14,1024392098,1069239395),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1068123024),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069416212),(12,1,'File','file','<name>',14,14,1052385472,1067353799),(25,0,'Poll','poll','<name>',14,14,1068716373,1068717758),(26,0,'Comment','comment','<subject>',14,14,1068716787,1069074842);
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
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(220,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(219,0,2,'show_on_frontpage','Show on frontpage','ezboolean',1,0,7,0,0,1,0,0,0,0,0,'','','','','',0,1),(198,0,4,'location','Location','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(199,0,4,'signature','Signature','eztext',1,0,6,2,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(207,0,25,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(208,0,25,'option','Option','ezoption',0,1,2,0,0,0,0,0,0,0,0,'','','','','',1,1),(120,0,2,'intro','Intro','ezxmltext',1,1,3,10,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,4,25,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,5,2,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(200,0,4,'user_image','User image','ezimage',0,0,7,1,0,0,0,0,0,0,0,'','','','','',0,1),(197,0,4,'title','Title','ezstring',1,0,4,25,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(217,0,26,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(209,0,26,'name','Name','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(210,0,26,'email','E-mail','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(211,0,26,'url','URL','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(212,0,26,'comment','Comment','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','','',0,1),(119,0,1,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1),(218,0,2,'author','Author','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1);
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
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(21,1,1,'Content'),(20,0,1,'Content'),(21,0,1,'Content'),(23,0,1,'Content'),(26,0,1,'Content'),(25,0,1,'Content');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'News',7,0,1033917596,1069069747,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',5,0,1033920830,1068468219,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',10,0,1066384365,1069159004,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',11,0,1066388816,1069159022,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(252,14,1,26,'Kewl news',1,0,1069074891,1069074891,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'News',65,0,1066643397,1069841357,1,''),(161,14,1,10,'About this website',4,0,1068047603,1069683989,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(254,14,1,2,'Google indexes',3,0,1069077452,1069682589,1,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(220,14,13,26,'båbåb',1,0,1068716967,1068716967,1,''),(115,14,11,14,'Cache',5,0,1066991725,1069158933,1,''),(116,14,11,14,'URL translator',4,0,1066992054,1069159040,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(218,14,1,25,'New Poll',1,0,0,0,0,''),(219,14,13,26,'Bård Farstad',1,0,1068716920,1068716920,1,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(216,14,1,25,'New Poll',1,0,0,0,0,''),(217,14,1,25,'New Poll',1,0,0,0,0,''),(187,14,1,4,'New User',1,0,0,0,0,''),(189,14,1,4,'New User',1,0,0,0,0,''),(221,14,1,25,'New Poll',1,0,0,0,0,''),(222,14,1,25,'New Poll',1,0,0,0,0,''),(224,14,1,25,'New Poll',1,0,0,0,0,''),(225,14,1,25,'New Poll',1,0,0,0,0,''),(278,14,1,2,'Entertainment weekly',1,0,1069680733,1069680733,1,''),(248,14,1,1,'News',1,0,1069070990,1069070990,1,''),(249,14,1,2,'Breaking news',5,0,1069071380,1069158606,1,''),(250,14,1,2,'Leauge champion',3,0,1069072401,1069683166,1,''),(233,14,13,26,'bård',1,0,1068718705,1068718705,1,''),(235,14,13,26,'kjh',1,0,1068718760,1068718760,1,''),(239,14,13,26,'Bård',1,0,1068719374,1068719374,1,''),(240,14,1,1,'Polls',2,0,1068719643,1068720665,1,''),(241,14,1,25,'Which one is the best of matrix movies?',1,0,1068720802,1068720802,1,''),(242,14,13,26,'ghghj',1,0,1068720915,1068720915,1,''),(245,14,13,26,'kjlh',1,0,1068730476,1068730476,1,''),(246,14,13,26,'kjhkjh',1,0,1068737197,1068737197,1,''),(255,14,1,1,'Technology',1,0,1069145298,1069145298,1,''),(256,14,1,1,'Politics',1,0,1069145327,1069145327,1,''),(257,14,1,1,'Sports',1,0,1069145751,1069145751,1,''),(258,14,1,1,'Business',1,0,1069146661,1069146661,1,''),(259,14,1,1,'Entertainment',1,0,1069146733,1069146733,1,''),(260,14,1,2,'Latest business update',4,0,1069147811,1069681784,1,''),(261,14,1,2,'Arnold for governor',2,0,1069147950,1069682497,1,''),(263,14,1,10,'Contact information',2,0,1069236942,1069684196,1,''),(264,14,1,10,'Help',1,0,1069237025,1069237025,1,''),(265,10,1,26,'New Comment',1,0,0,0,0,''),(266,10,1,0,'New',1,0,0,0,0,''),(267,10,1,0,'New',1,0,0,0,0,''),(268,10,1,26,'New Comment',1,0,0,0,0,''),(269,10,1,26,'Amazing',1,0,1069239748,1069239748,1,''),(270,10,1,26,'New Comment',1,0,0,0,0,''),(271,14,1,2,'Business as usual',2,0,1069243021,1069682292,1,''),(272,10,1,26,'fgdfg',1,0,1069244422,1069244422,1,''),(273,10,1,26,'New Comment',1,0,0,0,0,''),(274,10,1,26,'New Comment',1,0,0,0,0,''),(275,10,1,26,'sdsdd',1,0,1069245323,1069245323,1,''),(277,14,1,25,'New Poll',1,0,0,0,0,''),(279,14,1,2,'Will he become President?',1,0,1069680908,1069680908,1,''),(280,14,1,2,'New Article',1,0,0,0,0,''),(281,14,1,2,'Final release of ABC',1,0,1069681297,1069681297,1,''),(283,14,1,2,'Dons Jonas goes down',1,0,1069681443,1069681443,1,''),(284,14,1,2,'Rider wins dart competition',2,0,1069683484,1069685384,1,''),(285,14,1,25,'New Poll',1,0,0,0,0,''),(286,14,1,25,'New Poll',1,0,0,0,0,''),(287,14,1,25,'New Poll',1,0,0,0,0,''),(290,14,1,2,'New top fair',2,0,1069685704,1069757298,1,''),(289,14,1,25,'What season is the best?',1,0,1069684665,1069684665,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Blog',0,0,0,0,'blog','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',11,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"109\"\n            attribute_version=\"10\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',11,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(810,'eng-GB',1,235,211,'kljh',0,0,0,0,'kljh','ezstring'),(811,'eng-GB',1,235,212,'< >\n\n:)\n\nhttp://ez.no',0,0,0,0,'','eztext'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(809,'eng-GB',1,235,210,'kjlh',0,0,0,0,'kjlh','ezstring'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(957,'eng-GB',1,245,217,'',0,0,0,0,'','ezstring'),(958,'eng-GB',1,246,217,'',0,0,0,0,'','ezstring'),(959,'eng-GB',1,252,217,'Kewl news',0,0,0,0,'kewl news','ezstring'),(949,'eng-GB',1,252,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring'),(950,'eng-GB',1,252,212,'sdfg sdfgsdfg\nsdfg\nsdfg\nsdf\ngsdfg',0,0,0,0,'','eztext'),(951,'eng-GB',1,219,217,'',0,0,0,0,'','ezstring'),(952,'eng-GB',1,220,217,'',0,0,0,0,'','ezstring'),(953,'eng-GB',1,233,217,'',0,0,0,0,'','ezstring'),(954,'eng-GB',1,235,217,'',0,0,0,0,'','ezstring'),(929,'eng-GB',5,249,1,'Breaking news',0,0,0,0,'breaking news','ezstring'),(996,'eng-GB',5,249,218,'',0,0,0,0,'','ezstring'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(152,'eng-GB',61,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.gif\"\n         suffix=\"gif\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB/news.gif\"\n         original_filename=\"news6.gif\"\n         mime_type=\"original\"\n         width=\"160\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069319197\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB/news_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069319199\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB/news_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069319199\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"news_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB/news_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069319704\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1018,'eng-GB',5,249,219,'',1,0,0,1,'','ezboolean'),(1021,'eng-GB',1,254,219,'',1,0,0,1,'','ezboolean'),(730,'eng-GB',2,14,200,'',0,0,0,0,'','ezimage'),(731,'eng-GB',3,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"administrator_user.\"\n         suffix=\"\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB/administrator_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1020,'eng-GB',2,250,219,'',1,0,0,1,'','ezboolean'),(729,'eng-GB',1,14,200,'',0,0,0,0,'','ezimage'),(728,'eng-GB',1,10,200,'',0,0,0,0,'','ezimage'),(1000,'eng-GB',2,254,218,'',0,0,0,0,'','ezstring'),(980,'eng-GB',3,260,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf</paragraph>\n  <paragraph>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf</paragraph>\n  <paragraph>\n    <line>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(716,'eng-GB',1,10,199,'',0,0,0,0,'','eztext'),(717,'eng-GB',1,14,199,'',0,0,0,0,'','eztext'),(718,'eng-GB',2,14,199,'',0,0,0,0,'','eztext'),(719,'eng-GB',3,14,199,'developer... ;)',0,0,0,0,'','eztext'),(1023,'eng-GB',1,260,219,'',1,0,0,1,'','ezboolean'),(692,'eng-GB',1,10,197,'',0,0,0,0,'','ezstring'),(693,'eng-GB',1,14,197,'',0,0,0,0,'','ezstring'),(694,'eng-GB',2,14,197,'',0,0,0,0,'','ezstring'),(695,'eng-GB',3,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(1022,'eng-GB',2,254,219,'',1,0,0,1,'','ezboolean'),(1019,'eng-GB',1,250,219,'',1,0,0,1,'','ezboolean'),(704,'eng-GB',1,10,198,'',0,0,0,0,'','ezstring'),(705,'eng-GB',1,14,198,'',0,0,0,0,'','ezstring'),(706,'eng-GB',2,14,198,'',0,0,0,0,'','ezstring'),(707,'eng-GB',3,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(808,'eng-GB',1,235,209,'kjh',0,0,0,0,'kjh','ezstring'),(150,'eng-GB',57,56,157,'News',0,0,0,0,'','ezinisetting'),(151,'eng-GB',57,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',57,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.png\"\n         suffix=\"png\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-57-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-57-eng-GB/news.png\"\n         original_filename=\"mlogo.png\"\n         mime_type=\"original\"\n         width=\"99\"\n         height=\"60\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069243739\">\n  <original attribute_id=\"152\"\n            attribute_version=\"56\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-57-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-57-eng-GB/news_reference.png\"\n         mime_type=\"image/png\"\n         width=\"99\"\n         height=\"60\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069243741\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-57-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-57-eng-GB/news_medium.png\"\n         mime_type=\"image/png\"\n         width=\"99\"\n         height=\"60\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069243742\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"news_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-57-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-57-eng-GB/news_logo.png\"\n         mime_type=\"image/png\"\n         width=\"95\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069243775\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(772,'eng-GB',1,219,209,'Bård Farstad',0,0,0,0,'bård farstad','ezstring'),(773,'eng-GB',1,219,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(774,'eng-GB',1,219,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring'),(775,'eng-GB',1,219,212,'I\'ve seen more speacial things.. dsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gf\n\ndsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gf',0,0,0,0,'','eztext'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1090,'eng-GB',1,279,1,'Will he become President?',0,0,0,0,'will he become president?','ezstring'),(1094,'eng-GB',1,279,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"will_he_become_president.\"\n         suffix=\"\"\n         basename=\"will_he_become_president\"\n         dirpath=\"var/news/storage/images/news/entertainment/will_he_become_president/1094-1-eng-GB\"\n         url=\"var/news/storage/images/news/entertainment/will_he_become_president/1094-1-eng-GB/will_he_become_president.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069680749\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1095,'eng-GB',1,279,123,'',0,0,0,0,'','ezboolean'),(1096,'eng-GB',1,279,219,'',1,0,0,1,'','ezboolean'),(1010,'eng-GB',1,265,209,'',0,0,0,0,'','ezstring'),(1011,'eng-GB',1,265,210,'',0,0,0,0,'','ezstring'),(1012,'eng-GB',1,265,211,'',0,0,0,0,'','ezstring'),(1013,'eng-GB',1,265,212,'',0,0,0,0,'','eztext'),(1091,'eng-GB',1,279,218,'',0,0,0,0,'','ezstring'),(1092,'eng-GB',1,279,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The word is out there but it is not possible to say if it is a fact or not. Will he be the first to become president from his state?</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(150,'eng-GB',62,56,157,'News',0,0,0,0,'','ezinisetting'),(151,'eng-GB',62,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',62,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.gif\"\n         suffix=\"gif\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB/news.gif\"\n         original_filename=\"news.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069330025\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB/news_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069330027\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB/news_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069330027\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"news_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB/news_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069330044\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(1001,'eng-GB',3,260,218,'Bård Farstad',0,0,0,0,'bård farstad','ezstring'),(979,'eng-GB',3,260,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1002,'eng-GB',1,261,218,'',0,0,0,0,'','ezstring'),(978,'eng-GB',3,260,1,'Latest business update',0,0,0,0,'latest business update','ezstring'),(1001,'eng-GB',1,260,218,'',0,0,0,0,'','ezstring'),(1059,'eng-GB',1,274,209,'sfs',0,0,0,0,'sfs','ezstring'),(1060,'eng-GB',1,274,210,'fsfs',0,0,0,0,'fsfs','ezstring'),(1061,'eng-GB',1,274,211,'sf',0,0,0,0,'sf','ezstring'),(1062,'eng-GB',1,274,212,'sfsfs',0,0,0,0,'','eztext'),(1063,'eng-GB',1,275,217,'sdsdd',0,0,0,0,'sdsdd','ezstring'),(1064,'eng-GB',1,275,209,'ddd',0,0,0,0,'ddd','ezstring'),(1065,'eng-GB',1,275,210,'sdfsdf@yahoo.com',0,0,0,0,'sdfsdf@yahoo.com','ezstring'),(1066,'eng-GB',1,275,211,'fsf',0,0,0,0,'fsf','ezstring'),(1067,'eng-GB',1,275,212,'sdfsdfsdf\nsd\nfsdf',0,0,0,0,'','eztext'),(154,'eng-GB',58,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(437,'eng-GB',58,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',58,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(151,'eng-GB',55,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(824,'eng-GB',1,239,209,'Bård',0,0,0,0,'bård','ezstring'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1056,'eng-GB',1,273,211,'fsfs',0,0,0,0,'fsfs','ezstring'),(1057,'eng-GB',1,273,212,'sfs',0,0,0,0,'','eztext'),(153,'eng-GB',58,56,160,'news_blue',0,0,0,0,'news_blue','ezpackage'),(153,'eng-GB',61,56,160,'news_blue',0,0,0,0,'news_blue','ezpackage'),(153,'eng-GB',63,56,160,'news_blue',0,0,0,0,'news_blue','ezpackage'),(933,'eng-GB',5,249,123,'',1,0,0,1,'','ezboolean'),(150,'eng-GB',55,56,157,'News',0,0,0,0,'','ezinisetting'),(151,'eng-GB',61,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',61,56,157,'News',0,0,0,0,'','ezinisetting'),(671,'eng-GB',62,56,196,'mynews.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(150,'eng-GB',65,56,157,'News',0,0,0,0,'','ezinisetting'),(1070,'eng-GB',55,56,220,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1071,'eng-GB',56,56,220,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1072,'eng-GB',57,56,220,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1073,'eng-GB',58,56,220,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1074,'eng-GB',59,56,220,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1075,'eng-GB',61,56,220,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(1076,'eng-GB',62,56,220,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(150,'eng-GB',63,56,157,'News',0,0,0,0,'','ezinisetting'),(151,'eng-GB',63,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',63,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.gif\"\n         suffix=\"gif\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB/news.gif\"\n         original_filename=\"news.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069330025\">\n  <original attribute_id=\"152\"\n            attribute_version=\"62\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB/news_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069330027\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB/news_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069330027\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"news_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB/news_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069431879\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(930,'eng-GB',5,249,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',58,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.gif\"\n         suffix=\"gif\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-58-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-58-eng-GB/news.gif\"\n         original_filename=\"news.gif\"\n         mime_type=\"original\"\n         width=\"217\"\n         height=\"71\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069245892\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-58-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-58-eng-GB/news_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"217\"\n         height=\"71\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069245897\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-58-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-58-eng-GB/news_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"65\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069245897\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"news_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-58-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-58-eng-GB/news_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"177\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069245923\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',58,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',58,56,157,'News',0,0,0,0,'','ezinisetting'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',56,56,157,'News',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(1058,'eng-GB',1,274,217,'sfs',0,0,0,0,'sfs','ezstring'),(437,'eng-GB',57,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',57,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',56,56,160,'news_blue',0,0,0,0,'news_blue','ezpackage'),(154,'eng-GB',56,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(437,'eng-GB',56,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',56,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(154,'eng-GB',57,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.png\"\n         suffix=\"png\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-56-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-56-eng-GB/news.png\"\n         original_filename=\"mlogo.png\"\n         mime_type=\"original\"\n         width=\"99\"\n         height=\"60\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069243739\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-56-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-56-eng-GB/news_reference.png\"\n         mime_type=\"image/png\"\n         width=\"99\"\n         height=\"60\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069243741\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-56-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-56-eng-GB/news_medium.png\"\n         mime_type=\"image/png\"\n         width=\"99\"\n         height=\"60\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069243742\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',64,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.gif\"\n         suffix=\"gif\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB/news.gif\"\n         original_filename=\"news.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069841035\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB/news_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069841037\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB/news_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069841037\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"news_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB/news_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069841071\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',64,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',55,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.png\"\n         suffix=\"png\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-55-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-55-eng-GB/news.png\"\n         original_filename=\"mlogo.png\"\n         mime_type=\"original\"\n         width=\"99\"\n         height=\"60\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069243625\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',55,56,160,'news_blue',0,0,0,0,'news_blue','ezpackage'),(154,'eng-GB',55,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(437,'eng-GB',55,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',55,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1048,'eng-GB',1,272,217,'fgdfg',0,0,0,0,'fgdfg','ezstring'),(1049,'eng-GB',1,272,209,'dgdf',0,0,0,0,'dgdf','ezstring'),(1050,'eng-GB',1,272,210,'dg',0,0,0,0,'dg','ezstring'),(1051,'eng-GB',1,272,211,'dfgd',0,0,0,0,'dfgd','ezstring'),(1052,'eng-GB',1,272,212,'dfgfd',0,0,0,0,'','eztext'),(1053,'eng-GB',1,273,217,'sfs',0,0,0,0,'sfs','ezstring'),(1054,'eng-GB',1,273,209,'sf',0,0,0,0,'sf','ezstring'),(1055,'eng-GB',1,273,210,'fsfs',0,0,0,0,'fsfs','ezstring'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',59,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.gif\"\n         suffix=\"gif\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-59-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-59-eng-GB/news.gif\"\n         original_filename=\"news.gif\"\n         mime_type=\"original\"\n         width=\"160\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069253804\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-59-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-59-eng-GB/news_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069253807\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-59-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-59-eng-GB/news_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069253807\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"news_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-59-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-59-eng-GB/news_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"160\"\n         height=\"40\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069253826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',59,56,160,'news_blue',0,0,0,0,'news_blue','ezpackage'),(154,'eng-GB',59,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(437,'eng-GB',59,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',59,56,196,'mynews.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(1,'eng-GB',3,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',3,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',57,56,160,'news_blue',0,0,0,0,'news_blue','ezpackage'),(825,'eng-GB',1,239,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(802,'eng-GB',1,233,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(803,'eng-GB',1,233,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring'),(804,'eng-GB',1,233,212,'dfgl sdflg sdiofg usdoigfu osdigu iosdgf sdgfsd\nfg\nsdfg\nsdfg\nsdg',0,0,0,0,'','eztext'),(1034,'eng-GB',1,269,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring'),(1035,'eng-GB',1,269,212,'jgslfjs\r\nsdfklsfløsk\r\n',0,0,0,0,'','eztext'),(1033,'eng-GB',1,269,210,'wy@ez.no',0,0,0,0,'wy@ez.no','ezstring'),(1032,'eng-GB',1,269,209,'wenyue',0,0,0,0,'wenyue','ezstring'),(1025,'eng-GB',1,261,219,'',1,0,0,1,'','ezboolean'),(1026,'eng-GB',1,268,217,'',0,0,0,0,'','ezstring'),(1027,'eng-GB',1,268,209,'',0,0,0,0,'','ezstring'),(1028,'eng-GB',1,268,210,'',0,0,0,0,'','ezstring'),(1029,'eng-GB',1,268,211,'',0,0,0,0,'','ezstring'),(1030,'eng-GB',1,268,212,'',0,0,0,0,'','eztext'),(1031,'eng-GB',1,269,217,'Amazing',0,0,0,0,'amazing','ezstring'),(1024,'eng-GB',2,260,219,'',1,0,0,1,'','ezboolean'),(1014,'eng-GB',1,249,219,'',1,0,0,1,'','ezboolean'),(1015,'eng-GB',2,249,219,'',1,0,0,1,'','ezboolean'),(1016,'eng-GB',3,249,219,'',1,0,0,1,'','ezboolean'),(1017,'eng-GB',4,249,219,'',1,0,0,1,'','ezboolean'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',4,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',4,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',4,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',4,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',5,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',5,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',5,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',5,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"731\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"administrator_user_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(937,'eng-GB',1,250,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. </paragraph>\n  <paragraph>It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. </paragraph>\n  <paragraph>It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(938,'eng-GB',1,250,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_ez_publish_package.\"\n         suffix=\"\"\n         basename=\"new_ez_publish_package\"\n         dirpath=\"var/news/storage/images/news/new_ez_publish_package/938-1-eng-GB\"\n         url=\"var/news/storage/images/news/new_ez_publish_package/938-1-eng-GB/new_ez_publish_package.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069072375\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(939,'eng-GB',1,250,123,'',0,0,0,0,'','ezboolean'),(995,'eng-GB',3,249,218,'',0,0,0,0,'','ezstring'),(936,'eng-GB',1,250,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(935,'eng-GB',1,250,1,'New eZ publish package',0,0,0,0,'new ez publish package','ezstring'),(801,'eng-GB',1,233,209,'bård',0,0,0,0,'bård','ezstring'),(1087,'eng-GB',1,278,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"entertainment_weekly.\"\n         suffix=\"\"\n         basename=\"entertainment_weekly\"\n         dirpath=\"var/news/storage/images/news/entertainment/entertainment_weekly/1087-1-eng-GB\"\n         url=\"var/news/storage/images/news/entertainment/entertainment_weekly/1087-1-eng-GB/entertainment_weekly.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069680587\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1088,'eng-GB',1,278,123,'',1,0,0,1,'','ezboolean'),(1083,'eng-GB',1,278,1,'Entertainment weekly',0,0,0,0,'entertainment weekly','ezstring'),(1084,'eng-GB',1,278,218,'',0,0,0,0,'','ezstring'),(1085,'eng-GB',1,278,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Every week we will have an update of the news around in the entertainment world. For the latest gossip this is the place to be.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1086,'eng-GB',1,278,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The latest releases, the most beautiful faces, the best contracts, the best payed persons, the latest divorce etc. You will find it all here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',64,56,160,'news_brown',0,0,0,0,'news_brown','ezpackage'),(154,'eng-GB',64,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(437,'eng-GB',64,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',64,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(1076,'eng-GB',64,56,220,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(834,'eng-GB',1,242,211,'fghvmbnmbvnm',0,0,0,0,'fghvmbnmbvnm','ezstring'),(835,'eng-GB',1,242,212,'fgn fdgh fdgh fdgh\nkløæ\nølæ\nløæ\nløæ\nløæ\nhjlh\nhj',0,0,0,0,'','eztext'),(826,'eng-GB',1,239,211,'sdfgsd',0,0,0,0,'sdfgsd','ezstring'),(827,'eng-GB',1,239,212,'sdfgsdgsd\nsdg\nsdf',0,0,0,0,'','eztext'),(828,'eng-GB',1,240,4,'Polls',0,0,0,0,'polls','ezstring'),(829,'eng-GB',1,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(19,'eng-GB',2,10,8,'Anonymous',0,0,0,0,'anonymous','ezstring'),(20,'eng-GB',2,10,9,'User',0,0,0,0,'user','ezstring'),(21,'eng-GB',2,10,12,'',0,0,0,0,'','ezuser'),(692,'eng-GB',2,10,197,'',0,0,0,0,'','ezstring'),(704,'eng-GB',2,10,198,'',0,0,0,0,'','ezstring'),(716,'eng-GB',2,10,199,'',0,0,0,0,'','eztext'),(728,'eng-GB',2,10,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069677828\" />',0,0,0,0,'','ezimage'),(828,'eng-GB',2,240,4,'Polls',0,0,0,0,'polls','ezstring'),(829,'eng-GB',2,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(830,'eng-GB',1,241,207,'Which one is the best of matrix movies?',0,0,0,0,'which one is the best of matrix movies?','ezstring'),(831,'eng-GB',1,241,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Matrix</option>\n    <option id=\"1\"\n            additional_price=\"\">Matrix reloaded</option>\n    <option id=\"2\"\n            additional_price=\"\">Matrix revoluaton</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(832,'eng-GB',1,242,209,'ghghj',0,0,0,0,'ghghj','ezstring'),(833,'eng-GB',1,242,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(776,'eng-GB',1,220,209,'båbåb',0,0,0,0,'båbåb','ezstring'),(777,'eng-GB',1,220,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(778,'eng-GB',1,220,211,'http://piranha.no',0,0,0,0,'http://piranha.no','ezstring'),(779,'eng-GB',1,220,212,'sdfgsd fgsdgsd\ngf\nsdfg\nsdfgdsg\nsdgf\n',0,0,0,0,'','eztext'),(929,'eng-GB',2,249,1,'Breaking news',0,0,0,0,'breaking news','ezstring'),(930,'eng-GB',2,249,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(955,'eng-GB',1,239,217,'',0,0,0,0,'','ezstring'),(956,'eng-GB',1,242,217,'',0,0,0,0,'','ezstring'),(948,'eng-GB',1,252,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(947,'eng-GB',1,252,209,'Bård',0,0,0,0,'bård','ezstring'),(522,'eng-GB',2,161,140,'About me',0,0,0,0,'about me','ezstring'),(523,'eng-GB',2,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',2,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_me.\"\n         suffix=\"\"\n         basename=\"about_me\"\n         dirpath=\"var/blog/storage/images/about_me/524-2-eng-GB\"\n         url=\"var/blog/storage/images/about_me/524-2-eng-GB/about_me.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(841,'eng-GB',1,245,209,'kjlh',0,0,0,0,'kjlh','ezstring'),(842,'eng-GB',1,245,210,'kjh',0,0,0,0,'kjh','ezstring'),(843,'eng-GB',1,245,211,'kjh',0,0,0,0,'kjh','ezstring'),(844,'eng-GB',1,245,212,'kjlhkhkjhklhj',0,0,0,0,'','eztext'),(845,'eng-GB',1,246,209,'kjhkjh',0,0,0,0,'kjhkjh','ezstring'),(846,'eng-GB',1,246,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(847,'eng-GB',1,246,211,'sdfgsdfg',0,0,0,0,'sdfgsdfg','ezstring'),(848,'eng-GB',1,246,212,'sdfgsdfgsdfgds\nfgsd\nfg\nsdfg',0,0,0,0,'','eztext'),(150,'eng-GB',64,56,157,'News',0,0,0,0,'','ezinisetting'),(151,'eng-GB',59,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',59,56,157,'News',0,0,0,0,'','ezinisetting'),(2,'eng-GB',7,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(964,'eng-GB',1,254,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf sdgf sdgf sdgf</paragraph>\n  <paragraph>All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cAll the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf sdgf sdgf sdgf</paragraph>\n  <paragraph>d breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf sdgf sdgf sdgfsdgf sdgf sdgf</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(965,'eng-GB',1,254,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"google_indexes.\"\n         suffix=\"\"\n         basename=\"google_indexes\"\n         dirpath=\"var/news/storage/images/news/google_indexes/965-1-eng-GB\"\n         url=\"var/news/storage/images/news/google_indexes/965-1-eng-GB/google_indexes.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069077395\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(966,'eng-GB',1,254,123,'',0,0,0,0,'','ezboolean'),(993,'eng-GB',1,249,218,'',0,0,0,0,'','ezstring'),(962,'eng-GB',1,254,1,'Google indexes',0,0,0,0,'google indexes','ezstring'),(963,'eng-GB',1,254,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf sdgf sdgf sdgf</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(998,'eng-GB',2,250,218,'',0,0,0,0,'','ezstring'),(933,'eng-GB',2,249,123,'',0,0,0,0,'','ezboolean'),(932,'eng-GB',2,249,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"breaking_news.\"\n         suffix=\"\"\n         basename=\"breaking_news\"\n         dirpath=\"var/news/storage/images/news/breaking_news/932-2-eng-GB\"\n         url=\"var/news/storage/images/news/breaking_news/932-2-eng-GB/breaking_news.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069071356\">\n  <original attribute_id=\"932\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1,'eng-GB',7,1,4,'News',0,0,0,0,'news','ezstring'),(931,'eng-GB',2,249,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</paragraph>\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</paragraph>\n  <paragraph>\n    <line>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(927,'eng-GB',1,248,4,'News',0,0,0,0,'news','ezstring'),(928,'eng-GB',1,248,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(929,'eng-GB',1,249,1,'Breaking news',0,0,0,0,'breaking news','ezstring'),(930,'eng-GB',1,249,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(931,'eng-GB',1,249,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf </paragraph>\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf </paragraph>\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(932,'eng-GB',1,249,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"breaking_news.\"\n         suffix=\"\"\n         basename=\"breaking_news\"\n         dirpath=\"var/news/storage/images/news/breaking_news/932-1-eng-GB\"\n         url=\"var/news/storage/images/news/breaking_news/932-1-eng-GB/breaking_news.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069071356\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(933,'eng-GB',1,249,123,'',0,0,0,0,'','ezboolean'),(999,'eng-GB',1,254,218,'',0,0,0,0,'','ezstring'),(929,'eng-GB',3,249,1,'Breaking news',0,0,0,0,'breaking news','ezstring'),(930,'eng-GB',3,249,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(931,'eng-GB',3,249,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</paragraph>\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</paragraph>\n  <paragraph>\n    <line>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(932,'eng-GB',3,249,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"breaking_news.\"\n         suffix=\"\"\n         basename=\"breaking_news\"\n         dirpath=\"var/news/storage/images/news/breaking_news/932-3-eng-GB\"\n         url=\"var/news/storage/images/news/breaking_news/932-3-eng-GB/breaking_news.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069071356\">\n  <original attribute_id=\"932\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(933,'eng-GB',3,249,123,'',1,0,0,1,'','ezboolean'),(997,'eng-GB',1,250,218,'',0,0,0,0,'','ezstring'),(968,'eng-GB',1,255,4,'Technology',0,0,0,0,'technology','ezstring'),(969,'eng-GB',1,255,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(970,'eng-GB',1,256,4,'Politics',0,0,0,0,'politics','ezstring'),(971,'eng-GB',1,256,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(929,'eng-GB',4,249,1,'Breaking news',0,0,0,0,'breaking news','ezstring'),(930,'eng-GB',4,249,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(931,'eng-GB',4,249,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</paragraph>\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</paragraph>\n  <paragraph>\n    <line>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(932,'eng-GB',4,249,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"breaking_news.\"\n         suffix=\"\"\n         basename=\"breaking_news\"\n         dirpath=\"var/news/storage/images/news/politics/breaking_news/932-4-eng-GB\"\n         url=\"var/news/storage/images/news/politics/breaking_news/932-4-eng-GB/breaking_news.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069071356\">\n  <original attribute_id=\"932\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(933,'eng-GB',4,249,123,'',1,0,0,1,'','ezboolean'),(996,'eng-GB',4,249,218,'',0,0,0,0,'','ezstring'),(962,'eng-GB',2,254,1,'Google indexes',0,0,0,0,'google indexes','ezstring'),(963,'eng-GB',2,254,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf sdgf sdgf sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(964,'eng-GB',2,254,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf sdgf sdgf sdgf</paragraph>\n  <paragraph>All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cAll the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf sdgf sdgf sdgf</paragraph>\n  <paragraph>\n    <line>d breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? dsfg sdfg sdfg dsfg sdfg sdfg.sdgf sdgf sdgf sdgfsdgf sdgf sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(965,'eng-GB',2,254,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"google_indexes.\"\n         suffix=\"\"\n         basename=\"google_indexes\"\n         dirpath=\"var/news/storage/images/news/technology/google_indexes/965-2-eng-GB\"\n         url=\"var/news/storage/images/news/technology/google_indexes/965-2-eng-GB/google_indexes.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069077395\">\n  <original attribute_id=\"965\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(966,'eng-GB',2,254,123,'',0,0,0,0,'','ezboolean'),(972,'eng-GB',1,257,4,'Sports',0,0,0,0,'sports','ezstring'),(973,'eng-GB',1,257,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(935,'eng-GB',2,250,1,'New eZ publish package',0,0,0,0,'new ez publish package','ezstring'),(936,'eng-GB',2,250,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(937,'eng-GB',2,250,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf.</paragraph>\n  <paragraph>It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf.</paragraph>\n  <paragraph>\n    <line>It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf. It was just released. dfg dskfjgh ksdjfh gkjsdhf sdgf.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(938,'eng-GB',2,250,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_ez_publish_package.\"\n         suffix=\"\"\n         basename=\"new_ez_publish_package\"\n         dirpath=\"var/news/storage/images/news/sports/new_ez_publish_package/938-2-eng-GB\"\n         url=\"var/news/storage/images/news/sports/new_ez_publish_package/938-2-eng-GB/new_ez_publish_package.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069072375\">\n  <original attribute_id=\"938\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(939,'eng-GB',2,250,123,'',0,0,0,0,'','ezboolean'),(994,'eng-GB',2,249,218,'',0,0,0,0,'','ezstring'),(974,'eng-GB',1,258,4,'Business',0,0,0,0,'business','ezstring'),(975,'eng-GB',1,258,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(976,'eng-GB',1,259,4,'Entertainment',0,0,0,0,'entertainment','ezstring'),(977,'eng-GB',1,259,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(978,'eng-GB',1,260,1,'Latest business update',0,0,0,0,'latest business update','ezstring'),(979,'eng-GB',1,260,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(980,'eng-GB',1,260,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf </paragraph>\n  <paragraph>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf </paragraph>\n  <paragraph>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(981,'eng-GB',1,260,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"latest_business_update.\"\n         suffix=\"\"\n         basename=\"latest_business_update\"\n         dirpath=\"var/news/storage/images/news/business/latest_business_update/981-1-eng-GB\"\n         url=\"var/news/storage/images/news/business/latest_business_update/981-1-eng-GB/latest_business_update.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069147445\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(982,'eng-GB',1,260,123,'',0,0,0,0,'','ezboolean'),(984,'eng-GB',1,261,1,'Arnold for governor',0,0,0,0,'arnold for governor','ezstring'),(985,'eng-GB',1,261,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The latest movie from hollywood. d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(986,'eng-GB',1,261,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The latest movie from hollywood. d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg </paragraph>\n  <paragraph>The latest movie from hollywood. d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg </paragraph>\n  <paragraph>The latest movie from hollywood. d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg </paragraph>\n  <paragraph>The latest movie from hollywood. d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg d fgs dgf sdfgsdfg sdfg sdfg sdfg dsfg </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(987,'eng-GB',1,261,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"arnold_for_governor.\"\n         suffix=\"\"\n         basename=\"arnold_for_governor\"\n         dirpath=\"var/news/storage/images/news/entertainment/arnold_for_governor/987-1-eng-GB\"\n         url=\"var/news/storage/images/news/entertainment/arnold_for_governor/987-1-eng-GB/arnold_for_governor.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069147875\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(988,'eng-GB',1,261,123,'',0,0,0,0,'','ezboolean'),(978,'eng-GB',2,260,1,'Latest business update',0,0,0,0,'latest business update','ezstring'),(1001,'eng-GB',2,260,218,'Bård Farstad',0,0,0,0,'bård farstad','ezstring'),(979,'eng-GB',2,260,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(980,'eng-GB',2,260,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf</paragraph>\n  <paragraph>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf</paragraph>\n  <paragraph>\n    <line>sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf sdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(981,'eng-GB',2,260,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"latest_business_update.\"\n         suffix=\"\"\n         basename=\"latest_business_update\"\n         dirpath=\"var/news/storage/images/news/business/latest_business_update/981-2-eng-GB\"\n         url=\"var/news/storage/images/news/business/latest_business_update/981-2-eng-GB/latest_business_update.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069147445\">\n  <original attribute_id=\"981\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(982,'eng-GB',2,260,123,'',0,0,0,0,'','ezboolean'),(931,'eng-GB',5,249,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</paragraph>\n  <paragraph>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</paragraph>\n  <paragraph>\n    <line>latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf latest dfgds fgsdfg sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(932,'eng-GB',5,249,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"breaking_news.\"\n         suffix=\"\"\n         basename=\"breaking_news\"\n         dirpath=\"var/news/storage/images/news/politics/breaking_news/932-5-eng-GB\"\n         url=\"var/news/storage/images/news/politics/breaking_news/932-5-eng-GB/breaking_news.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069071356\">\n  <original attribute_id=\"932\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1009,'eng-GB',1,265,217,'',0,0,0,0,'','ezstring'),(1093,'eng-GB',1,279,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>It is way to early to say but he is the name in all newspapers this week. Will he be able to do what nobody else has? </paragraph>\n  <paragraph>Stay tuned for more updates.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1082,'eng-GB',1,277,207,'',0,0,0,0,'','ezstring'),(1089,'eng-GB',1,278,219,'',1,0,0,1,'','ezboolean'),(323,'eng-GB',5,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',5,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/news/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/cache/324-5-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"324\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',5,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',5,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',10,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',10,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/news/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/classes/103-10-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"103\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',10,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',10,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(110,'eng-GB',11,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',11,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',4,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',4,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/news/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"328\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/news/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/news/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',4,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',4,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(522,'eng-GB',3,161,140,'About this service',0,0,0,0,'about this service','ezstring'),(523,'eng-GB',3,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',3,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_service.\"\n         suffix=\"\"\n         basename=\"about_this_service\"\n         dirpath=\"var/news/storage/images/about_this_service/524-3-eng-GB\"\n         url=\"var/news/storage/images/about_this_service/524-3-eng-GB/about_this_service.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1003,'eng-GB',1,263,140,'Contact information',0,0,0,0,'contact information','ezstring'),(1004,'eng-GB',1,263,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information how to contact us.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1005,'eng-GB',1,263,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"contact_information.\"\n         suffix=\"\"\n         basename=\"contact_information\"\n         dirpath=\"var/news/storage/images/contact_information/1005-1-eng-GB\"\n         url=\"var/news/storage/images/contact_information/1005-1-eng-GB/contact_information.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069236863\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1006,'eng-GB',1,264,140,'Help',0,0,0,0,'help','ezstring'),(1007,'eng-GB',1,264,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information on how to use this service.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1008,'eng-GB',1,264,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"help.\"\n         suffix=\"\"\n         basename=\"help\"\n         dirpath=\"var/news/storage/images/help/1008-1-eng-GB\"\n         url=\"var/news/storage/images/help/1008-1-eng-GB/help.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069236964\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(981,'eng-GB',3,260,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"latest_business_update.\"\n         suffix=\"\"\n         basename=\"latest_business_update\"\n         dirpath=\"var/news/storage/images/news/business/latest_business_update/981-3-eng-GB\"\n         url=\"var/news/storage/images/news/business/latest_business_update/981-3-eng-GB/latest_business_update.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069147445\">\n  <original attribute_id=\"981\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(982,'eng-GB',3,260,123,'',0,0,0,0,'','ezboolean'),(1024,'eng-GB',3,260,219,'',1,0,0,1,'','ezboolean'),(1036,'eng-GB',1,270,217,'test',0,0,0,0,'test','ezstring'),(1037,'eng-GB',1,270,209,'sefes',0,0,0,0,'sefes','ezstring'),(1038,'eng-GB',1,270,210,'sefw',0,0,0,0,'sefw','ezstring'),(1039,'eng-GB',1,270,211,'sefse',0,0,0,0,'sefse','ezstring'),(1040,'eng-GB',1,270,212,'sefsf',0,0,0,0,'','eztext'),(1041,'eng-GB',1,271,1,'Business is',0,0,0,0,'business is','ezstring'),(1042,'eng-GB',1,271,218,'Kjell Mann',0,0,0,0,'kjell mann','ezstring'),(1043,'eng-GB',1,271,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>dsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfg</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1044,'eng-GB',1,271,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>dsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfg</paragraph>\n  <paragraph>fg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgdsfg sdfg sdfg sdfgg sdfg sdfg sdfg</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1045,'eng-GB',1,271,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"business_is.\"\n         suffix=\"\"\n         basename=\"business_is\"\n         dirpath=\"var/news/storage/images/news/business/business_is/1045-1-eng-GB\"\n         url=\"var/news/storage/images/news/business/business_is/1045-1-eng-GB/business_is.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069242999\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1046,'eng-GB',1,271,123,'',0,0,0,0,'','ezboolean'),(1047,'eng-GB',1,271,219,'',0,0,0,0,'','ezboolean'),(152,'eng-GB',65,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"news.gif\"\n         suffix=\"gif\"\n         basename=\"news\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB/news.gif\"\n         original_filename=\"news.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069841357\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"news_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB/news_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069841359\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"news_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB/news_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069841360\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"news_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB\"\n         url=\"var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB/news_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069841394\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',65,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(437,'eng-GB',62,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(154,'eng-GB',62,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(153,'eng-GB',62,56,160,'news_blue',0,0,0,0,'news_blue','ezpackage'),(154,'eng-GB',61,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(437,'eng-GB',61,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',61,56,196,'mynews.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(154,'eng-GB',63,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(437,'eng-GB',63,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',63,56,196,'mynews.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(1076,'eng-GB',63,56,220,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(1097,'eng-GB',1,280,1,'New article',0,0,0,0,'new article','ezstring'),(1098,'eng-GB',1,280,218,'',0,0,0,0,'','ezstring'),(1099,'eng-GB',1,280,120,'',1045487555,0,0,0,'','ezxmltext'),(1100,'eng-GB',1,280,121,'',1045487555,0,0,0,'','ezxmltext'),(1101,'eng-GB',1,280,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069680961\" />',0,0,0,0,'','ezimage'),(1102,'eng-GB',1,280,123,'',0,0,0,0,'','ezboolean'),(1103,'eng-GB',1,280,219,'',1,0,0,1,'','ezboolean'),(1104,'eng-GB',1,281,1,'Final release of ABC',0,0,0,0,'final release of abc','ezstring'),(1105,'eng-GB',1,281,218,'',0,0,0,0,'','ezstring'),(1106,'eng-GB',1,281,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Publish ABC finally released their long awaited software last friday. But was it all worth waiting for? We will let you know right now.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1107,'eng-GB',1,281,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We have been waiting for Publish ABC for more than five years and they have told us over and over again that this will be the best software ever. But how often have we heard this before? Publish ABC has nothing new to tell.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1108,'eng-GB',1,281,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"final_release_of_abc.\"\n         suffix=\"\"\n         basename=\"final_release_of_abc\"\n         dirpath=\"var/news/storage/images/news/technology/final_release_of_abc/1108-1-eng-GB\"\n         url=\"var/news/storage/images/news/technology/final_release_of_abc/1108-1-eng-GB/final_release_of_abc.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069681154\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1109,'eng-GB',1,281,123,'',1,0,0,1,'','ezboolean'),(1110,'eng-GB',1,281,219,'',1,0,0,1,'','ezboolean'),(1118,'eng-GB',1,283,1,'Dons Jonas goes down',0,0,0,0,'dons jonas goes down','ezstring'),(1119,'eng-GB',1,283,218,'',0,0,0,0,'','ezstring'),(1120,'eng-GB',1,283,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>One of the largest companies in the country today went out of business. It is the result of to many good diners says the CEO Jonas.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1121,'eng-GB',1,283,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(1122,'eng-GB',1,283,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"dons_jonas_goes_down.\"\n         suffix=\"\"\n         basename=\"dons_jonas_goes_down\"\n         dirpath=\"var/news/storage/images/news/business/dons_jonas_goes_down/1122-1-eng-GB\"\n         url=\"var/news/storage/images/news/business/dons_jonas_goes_down/1122-1-eng-GB/dons_jonas_goes_down.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069681346\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1123,'eng-GB',1,283,123,'',1,0,0,1,'','ezboolean'),(1124,'eng-GB',1,283,219,'',1,0,0,1,'','ezboolean'),(978,'eng-GB',4,260,1,'Latest business update',0,0,0,0,'latest business update','ezstring'),(1001,'eng-GB',4,260,218,'Bård Farstad',0,0,0,0,'bård farstad','ezstring'),(979,'eng-GB',4,260,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The news department has created a new service for all local readers. Let us know what happens in your neighborhood and you will serve others.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(980,'eng-GB',4,260,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is a service that will be benefitial to all your friends and locals. It will also be a good service for the people that have moved away but wants to know what is going on in their old neighborhood.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1041,'eng-GB',2,271,1,'Business as usual',0,0,0,0,'business as usual','ezstring'),(1042,'eng-GB',2,271,218,'Kjell Mann',0,0,0,0,'kjell mann','ezstring'),(1043,'eng-GB',2,271,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Where have we heard this before? Business as usual is becoming a slang for we have problems but will not close down.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(981,'eng-GB',4,260,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"latest_business_update.\"\n         suffix=\"\"\n         basename=\"latest_business_update\"\n         dirpath=\"var/news/storage/images/news/business/latest_business_update/981-4-eng-GB\"\n         url=\"var/news/storage/images/news/business/latest_business_update/981-4-eng-GB/latest_business_update.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069147445\">\n  <original attribute_id=\"981\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(982,'eng-GB',4,260,123,'',1,0,0,1,'','ezboolean'),(1024,'eng-GB',4,260,219,'',1,0,0,1,'','ezboolean'),(1044,'eng-GB',2,271,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Write in the rest of the text here</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(984,'eng-GB',2,261,1,'Arnold for governor',0,0,0,0,'arnold for governor','ezstring'),(1002,'eng-GB',2,261,218,'',0,0,0,0,'','ezstring'),(985,'eng-GB',2,261,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The latest movie from hollywood. As we all know Arnold was selected for governor. But what will happen to T4?</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1045,'eng-GB',2,271,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"business_as_usual.\"\n         suffix=\"\"\n         basename=\"business_as_usual\"\n         dirpath=\"var/news/storage/images/news/business/business_as_usual/1045-2-eng-GB\"\n         url=\"var/news/storage/images/news/business/business_as_usual/1045-2-eng-GB/business_as_usual.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069242999\">\n  <original attribute_id=\"1045\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1046,'eng-GB',2,271,123,'',1,0,0,1,'','ezboolean'),(1047,'eng-GB',2,271,219,'',1,0,0,1,'','ezboolean'),(986,'eng-GB',2,261,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Many fans are asking this question. Will there be no more movies with the man himself? No more strange quotes? No more entertaining movies? This is not good, not good at all. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(962,'eng-GB',3,254,1,'Google indexes',0,0,0,0,'google indexes','ezstring'),(1000,'eng-GB',3,254,218,'',0,0,0,0,'','ezstring'),(963,'eng-GB',3,254,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? </line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(964,'eng-GB',3,254,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>All the content on the web and stores it on one singe cd-rom!! Pretty nasty stuff. What if this cd breakes? </paragraph>\n  <paragraph>It is just a chanse you have to take. That is more or less it.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(987,'eng-GB',2,261,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"arnold_for_governor.\"\n         suffix=\"\"\n         basename=\"arnold_for_governor\"\n         dirpath=\"var/news/storage/images/news/politics/arnold_for_governor/987-2-eng-GB\"\n         url=\"var/news/storage/images/news/politics/arnold_for_governor/987-2-eng-GB/arnold_for_governor.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069147875\">\n  <original attribute_id=\"987\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(988,'eng-GB',2,261,123,'',0,0,0,0,'','ezboolean'),(1025,'eng-GB',2,261,219,'',1,0,0,1,'','ezboolean'),(935,'eng-GB',3,250,1,'Leauge champion',0,0,0,0,'leauge champion','ezstring'),(998,'eng-GB',3,250,218,'',0,0,0,0,'','ezstring'),(936,'eng-GB',3,250,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>eZpool is the internal champion and was never close to loosing to anybody else. Undefeated through the whole year it was never any doubt about it.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(965,'eng-GB',3,254,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"google_indexes.\"\n         suffix=\"\"\n         basename=\"google_indexes\"\n         dirpath=\"var/news/storage/images/news/technology/google_indexes/965-3-eng-GB\"\n         url=\"var/news/storage/images/news/technology/google_indexes/965-3-eng-GB/google_indexes.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069077395\">\n  <original attribute_id=\"965\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(966,'eng-GB',3,254,123,'',1,0,0,1,'','ezboolean'),(1022,'eng-GB',3,254,219,'',1,0,0,1,'','ezboolean'),(937,'eng-GB',3,250,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>The final standings:</line>\n    <line>1. eZpool</line>\n    <line>2. Dudes</line>\n    <line>3. Giverns</line>\n    <line>4. Stakes</line>\n    <line>5. Heads before brains</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1125,'eng-GB',1,284,1,'Rider wins dart competition',0,0,0,0,'rider wins dart competition','ezstring'),(1126,'eng-GB',1,284,218,'Terje Gunrell',0,0,0,0,'terje gunrell','ezstring'),(1127,'eng-GB',1,284,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The very close competition was finished late last night after 3 am. Rider the underdog beat the favourite and let the celebrations start with his familiy and friends as soon as it was decided.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1128,'eng-GB',1,284,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The very close competition was finished late last night after 3 am. Rider the underdog beat the favourite and let the celebrations start with his familiy and friends as soon as it was decided.</paragraph>\n  <paragraph>This was fun was all he had to say when it was over. I will just have a small dring and then go to bed and sleep like a champion. It will be a nice feeling.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1129,'eng-GB',1,284,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"rider_wins_dart_competition.\"\n         suffix=\"\"\n         basename=\"rider_wins_dart_competition\"\n         dirpath=\"var/news/storage/images/news/sports/rider_wins_dart_competition/1129-1-eng-GB\"\n         url=\"var/news/storage/images/news/sports/rider_wins_dart_competition/1129-1-eng-GB/rider_wins_dart_competition.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069683256\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1130,'eng-GB',1,284,123,'',1,0,0,1,'','ezboolean'),(1131,'eng-GB',1,284,219,'',1,0,0,1,'','ezboolean'),(938,'eng-GB',3,250,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"leauge_champion.\"\n         suffix=\"\"\n         basename=\"leauge_champion\"\n         dirpath=\"var/news/storage/images/news/sports/leauge_champion/938-3-eng-GB\"\n         url=\"var/news/storage/images/news/sports/leauge_champion/938-3-eng-GB/leauge_champion.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069072375\">\n  <original attribute_id=\"938\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(939,'eng-GB',3,250,123,'',0,0,0,0,'','ezboolean'),(1020,'eng-GB',3,250,219,'',1,0,0,1,'','ezboolean'),(1132,'eng-GB',1,285,207,'',0,0,0,0,'','ezstring'),(522,'eng-GB',4,161,140,'About this website',0,0,0,0,'about this website','ezstring'),(523,'eng-GB',4,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is where you let people know what your site is about and why it is there. You can also use these information pages all kinds of general information like contact forms etc.</paragraph>\n  <paragraph>Like this: This website was created to help share information about whats going on in the world. We will keep you updated in several areas.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1003,'eng-GB',2,263,140,'Contact information',0,0,0,0,'contact information','ezstring'),(1004,'eng-GB',2,263,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A contact page is where you let your readers, customers, partners etc find information on how to get in touch with you.</paragraph>\n  <paragraph>Normal info to have here is: telephone numbers, fax numbers, e-mail addresses, visitors address and snail mail address. </paragraph>\n  <paragraph>This site is also often used for people that wants to tip the site on news, updates etc. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1005,'eng-GB',2,263,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"contact_information.\"\n         suffix=\"\"\n         basename=\"contact_information\"\n         dirpath=\"var/news/storage/images/contact_information/1005-2-eng-GB\"\n         url=\"var/news/storage/images/contact_information/1005-2-eng-GB/contact_information.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069236863\">\n  <original attribute_id=\"1005\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1133,'eng-GB',1,286,207,'',0,0,0,0,'','ezstring'),(524,'eng-GB',4,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_website.\"\n         suffix=\"\"\n         basename=\"about_this_website\"\n         dirpath=\"var/news/storage/images/about_this_website/524-4-eng-GB\"\n         url=\"var/news/storage/images/about_this_website/524-4-eng-GB/about_this_website.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1134,'eng-GB',1,287,207,'',0,0,0,0,'','ezstring'),(1126,'eng-GB',2,284,218,'Terje Gunrell',0,0,0,0,'terje gunrell','ezstring'),(1127,'eng-GB',2,284,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The very close competition was finished late last night after 3 am. Rider the underdog beat the favourite and let the celebrations start with his familiy and friends as soon as it was decided.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1125,'eng-GB',2,284,1,'Rider wins dart competition',0,0,0,0,'rider wins dart competition','ezstring'),(1137,'eng-GB',1,289,207,'What season is the best?',0,0,0,0,'what season is the best?','ezstring'),(1138,'eng-GB',1,289,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Spring</option>\n    <option id=\"1\"\n            additional_price=\"\">Summer</option>\n    <option id=\"2\"\n            additional_price=\"\">Fall</option>\n    <option id=\"3\"\n            additional_price=\"\">Winter</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(1128,'eng-GB',2,284,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The very close competition was finished late last night after 3 am. Rider the underdog beat the favourite and let the celebrations start with his familiy and friends as soon as it was decided.</paragraph>\n  <paragraph>This was fun was all he had to say when it was over. I will just have a small dring and then go to bed and sleep like a champion. It will be a nice feeling.</paragraph>\n  <paragraph>Rider today insisted he will face a &quot;big summer&quot; as he will attempt to piece together a season which can make a stronger challenge for next season&apos;s Premiership crown. </paragraph>\n  <paragraph>Rider was disappointed with his finish in the league last season and he is working hard, like everyone else, to bid for the title next year as well. </paragraph>\n  <paragraph>&quot;It&apos;s been the greatest of seasons on the league front, and in Europe,&quot; he said. </paragraph>\n  <paragraph>&quot;I won the league now and also won the Open Cup but set my sights much higher than that and, overall, it&apos;s been an ok season. </paragraph>\n  <paragraph>&quot;Hopefully I will get even better next year. It&apos;s a really important summer for me and my mates. But it&apos;s certainly a big summer.&quot;</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1129,'eng-GB',2,284,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"rider_wins_dart_competition.\"\n         suffix=\"\"\n         basename=\"rider_wins_dart_competition\"\n         dirpath=\"var/news/storage/images/news/sports/rider_wins_dart_competition/1129-2-eng-GB\"\n         url=\"var/news/storage/images/news/sports/rider_wins_dart_competition/1129-2-eng-GB/rider_wins_dart_competition.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069683256\">\n  <original attribute_id=\"1129\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1130,'eng-GB',2,284,123,'',1,0,0,1,'','ezboolean'),(1131,'eng-GB',2,284,219,'',1,0,0,1,'','ezboolean'),(1139,'eng-GB',1,290,1,'New top fair',0,0,0,0,'new top fair','ezstring'),(1140,'eng-GB',1,290,218,'Tim Gunny',0,0,0,0,'tim gunny','ezstring'),(1141,'eng-GB',1,290,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This week, some members of the crew are reporting live from Hall A, attending &quot;Top fair 2003&quot;. Top fair 2003 is an international trade fair for Information Technology and Telecommunications. The trade fair is held for the 5th time.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1142,'eng-GB',1,290,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Four crew members are on-site from the 20th to the 24th reporting live from the hall. The following text contains a live report from the fair.</paragraph>\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small. </paragraph>\n  <paragraph>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things. </paragraph>\n  <paragraph>Speaking of community, we&apos;re happy that the community show up. It is always interesting and inspiring to meet face to face and to discuss various topics. We certainly hope that more community people will show up during the rest of the week. </paragraph>\n  <paragraph>Anyway, we were talking about the benefits of open and free software. As mentioned, some people still don&apos;t get it; however, when explained, we&apos;re met by replies such as: </paragraph>\n  <paragraph>&quot;Amazing!&quot; - big smile... </paragraph>\n  <paragraph>&quot;I would have to pay a lot of money for this feature from company...&quot; </paragraph>\n  <paragraph>- from a guy who came to us from one of the neighboring stands (right after watching a presentation there). </paragraph>\n  <paragraph>Some companies are just interested in talking to potential customers who are willing to spend millions on rigid solutions. This is not our policy. We&apos;re very flexible and eager to talk to a wide range of people. If you have the chance visit the fair, feel free to stop by. Our stand is open and prepared for everyone. Anybody can sit down together with our representatives and receive an on-site, hands-on demonstration of the system.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1143,'eng-GB',1,290,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_top_fair.\"\n         suffix=\"\"\n         basename=\"new_top_fair\"\n         dirpath=\"var/news/storage/images/news/technology/new_top_fair/1143-1-eng-GB\"\n         url=\"var/news/storage/images/news/technology/new_top_fair/1143-1-eng-GB/new_top_fair.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069685462\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1144,'eng-GB',1,290,123,'',1,0,0,1,'','ezboolean'),(1145,'eng-GB',1,290,219,'',1,0,0,1,'','ezboolean'),(1144,'eng-GB',2,290,123,'',1,0,0,1,'','ezboolean'),(1143,'eng-GB',2,290,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"new_top_fair.jpg\"\n         suffix=\"jpg\"\n         basename=\"new_top_fair\"\n         dirpath=\"var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB\"\n         url=\"var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB/new_top_fair.jpg\"\n         original_filename=\"dscn0001.jpg\"\n         mime_type=\"original\"\n         width=\"350\"\n         height=\"263\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757298\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"new_top_fair_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB\"\n         url=\"var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB/new_top_fair_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"350\"\n         height=\"263\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069841064\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"new_top_fair_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB\"\n         url=\"var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB/new_top_fair_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         timestamp=\"1069757307\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"new_top_fair_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB\"\n         url=\"var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB/new_top_fair_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-1588460780\"\n         timestamp=\"1069841064\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1141,'eng-GB',2,290,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the crew are reporting live from Hall A, attending &quot;Top fair 2003&quot;. Top fair 2003 is an international trade fair for Information Technology and Telecommunications. The trade fair is held for the 5th time.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1142,'eng-GB',2,290,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Four crew members are on-site from the 20th to the 24th reporting live from the hall. The following text contains a live report from the fair.</paragraph>\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</paragraph>\n  <paragraph>Speaking of community, we&apos;re happy that the community show up. It is always interesting and inspiring to meet face to face and to discuss various topics. We certainly hope that more community people will show up during the rest of the week.</paragraph>\n  <paragraph>Anyway, we were talking about the benefits of open and free software. As mentioned, some people still don&apos;t get it; however, when explained, we&apos;re met by replies such as:</paragraph>\n  <paragraph>&quot;Amazing!&quot; - big smile...</paragraph>\n  <paragraph>&quot;I would have to pay a lot of money for this feature from company...&quot;</paragraph>\n  <paragraph>- from a guy who came to us from one of the neighboring stands (right after watching a presentation there).</paragraph>\n  <paragraph>\n    <line>Some companies are just interested in talking to potential customers who are willing to spend millions on rigid solutions. This is not our policy. We&apos;re very flexible and eager to talk to a wide range of people. If you have the chance visit the fair, feel free to stop by. Our stand is open and prepared for everyone. Anybody can sit down together with our representatives and receive an on-site, hands-on demonstration of the system.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1140,'eng-GB',2,290,218,'Tim Gunny',0,0,0,0,'tim gunny','ezstring'),(1139,'eng-GB',2,290,1,'New top fair',0,0,0,0,'new top fair','ezstring'),(1145,'eng-GB',2,290,219,'',1,0,0,1,'','ezboolean'),(153,'eng-GB',65,56,160,'news_brown',0,0,0,0,'news_brown','ezpackage'),(154,'eng-GB',65,56,161,'news_package',0,0,0,0,'news_package','ezstring'),(437,'eng-GB',65,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',65,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(1076,'eng-GB',65,56,220,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring');
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
INSERT INTO ezcontentobject_link VALUES (7,249,5,250),(6,249,5,261);
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(233,'bård',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(254,'Google indexes',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(235,'kjh',1,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(256,'Politics',1,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(249,'Breaking news',4,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(240,'Polls',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(240,'Polls',2,'eng-GB','eng-GB'),(278,'Entertainment weekly',1,'eng-GB','eng-GB'),(221,'New Poll',1,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(239,'Bård',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(216,'New Poll',1,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(252,'Kewl news',1,'eng-GB','eng-GB'),(279,'Will he become President?',1,'eng-GB','eng-GB'),(255,'Technology',1,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(249,'Breaking news',2,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(224,'New Poll',1,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(222,'New Poll',1,'eng-GB','eng-GB'),(225,'New Poll',1,'eng-GB','eng-GB'),(218,'New Poll',1,'eng-GB','eng-GB'),(219,'Bård Farstad',1,'eng-GB','eng-GB'),(220,'båbåb',1,'eng-GB','eng-GB'),(217,'New Poll',1,'eng-GB','eng-GB'),(1,'Blog',6,'eng-GB','eng-GB'),(161,'About me',2,'eng-GB','eng-GB'),(241,'Which one is the best of matrix movies?',1,'eng-GB','eng-GB'),(242,'ghghj',1,'eng-GB','eng-GB'),(249,'Breaking news',3,'eng-GB','eng-GB'),(56,'News',65,'eng-GB','eng-GB'),(56,'Blog',43,'eng-GB','eng-GB'),(56,'Blog',47,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(245,'kjlh',1,'eng-GB','eng-GB'),(56,'Blog',48,'eng-GB','eng-GB'),(246,'kjhkjh',1,'eng-GB','eng-GB'),(56,'Blog',49,'eng-GB','eng-GB'),(56,'News',64,'eng-GB','eng-GB'),(250,'New eZ publish package',1,'eng-GB','eng-GB'),(249,'Breaking news',1,'eng-GB','eng-GB'),(248,'News',1,'eng-GB','eng-GB'),(56,'Blog',50,'eng-GB','eng-GB'),(56,'Blog',51,'eng-GB','eng-GB'),(1,'News',7,'eng-GB','eng-GB'),(254,'Google indexes',2,'eng-GB','eng-GB'),(257,'Sports',1,'eng-GB','eng-GB'),(250,'New eZ publish package',2,'eng-GB','eng-GB'),(258,'Business',1,'eng-GB','eng-GB'),(259,'Entertainment',1,'eng-GB','eng-GB'),(260,'Latest business update',1,'eng-GB','eng-GB'),(261,'Arnold for governor',1,'eng-GB','eng-GB'),(240,'Polls',3,'eng-GB','eng-GB'),(260,'Latest business update',2,'eng-GB','eng-GB'),(56,'News',52,'eng-GB','eng-GB'),(249,'Breaking news',5,'eng-GB','eng-GB'),(115,'Cache',5,'eng-GB','eng-GB'),(43,'Classes',10,'eng-GB','eng-GB'),(45,'Look and feel',11,'eng-GB','eng-GB'),(116,'URL translator',4,'eng-GB','eng-GB'),(161,'About this service',3,'eng-GB','eng-GB'),(263,'Contact information',1,'eng-GB','eng-GB'),(264,'Help',1,'eng-GB','eng-GB'),(56,'News',53,'eng-GB','eng-GB'),(265,'New Comment',1,'eng-GB','eng-GB'),(266,'New',1,'eng-GB','eng-GB'),(267,'New',1,'eng-GB','eng-GB'),(267,'',2,'eng-GB','eng-GB'),(268,'New Comment',1,'eng-GB','eng-GB'),(269,'Amazing',1,'eng-GB','eng-GB'),(260,'Latest business update',3,'eng-GB','eng-GB'),(270,'test',1,'eng-GB','eng-GB'),(271,'Business is',1,'eng-GB','eng-GB'),(56,'News',54,'eng-GB','eng-GB'),(56,'News',55,'eng-GB','eng-GB'),(56,'News',56,'eng-GB','eng-GB'),(56,'News',57,'eng-GB','eng-GB'),(272,'fgdfg',1,'eng-GB','eng-GB'),(273,'sfs',1,'eng-GB','eng-GB'),(274,'sfs',1,'eng-GB','eng-GB'),(275,'sdsdd',1,'eng-GB','eng-GB'),(56,'News',58,'eng-GB','eng-GB'),(56,'News',59,'eng-GB','eng-GB'),(56,'News',61,'eng-GB','eng-GB'),(56,'News',62,'eng-GB','eng-GB'),(56,'News',63,'eng-GB','eng-GB'),(277,'New Poll',1,'eng-GB','eng-GB'),(280,'New Article',1,'eng-GB','eng-GB'),(281,'Final release of ABC',1,'eng-GB','eng-GB'),(283,'Dons Jonas goes down',1,'eng-GB','eng-GB'),(260,'Latest business update',4,'eng-GB','eng-GB'),(271,'Business as usual',2,'eng-GB','eng-GB'),(261,'Arnold for governor',2,'eng-GB','eng-GB'),(254,'Google indexes',3,'eng-GB','eng-GB'),(250,'Leauge champion',3,'eng-GB','eng-GB'),(284,'Rider wins dart competition',1,'eng-GB','eng-GB'),(285,'New Poll',1,'eng-GB','eng-GB'),(161,'About this website',4,'eng-GB','eng-GB'),(263,'Contact information',2,'eng-GB','eng-GB'),(286,'New Poll',1,'eng-GB','eng-GB'),(287,'New Poll',1,'eng-GB','eng-GB'),(284,'Rider wins dart competition',2,'eng-GB','eng-GB'),(289,'What season is the best?',1,'eng-GB','eng-GB'),(290,'New top fair',1,'eng-GB','eng-GB'),(290,'New top fair',2,'eng-GB','eng-GB'),(290,'New top fair',3,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,7,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,5,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,10,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,11,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(184,182,252,1,1,5,'/1/2/181/188/182/184/',9,1,0,'news/politics/breaking_news/kewl_news',184),(54,48,56,65,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/news',54),(189,181,257,1,1,3,'/1/2/181/189/',9,1,0,'news/sports',189),(127,2,161,4,1,2,'/1/2/127/',9,1,0,'about_this_website',127),(95,46,115,5,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,4,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(195,2,264,1,1,2,'/1/2/195/',9,1,0,'help',195),(196,182,269,1,1,5,'/1/2/181/188/182/196/',1,1,0,'news/politics/breaking_news/amazing',196),(197,190,271,2,1,4,'/1/2/181/190/197/',9,1,0,'news/business/business_as_usual',197),(186,187,254,3,1,4,'/1/2/181/187/186/',9,1,0,'news/technology/google_indexes',186),(187,181,255,1,1,3,'/1/2/181/187/',9,1,0,'news/technology',187),(188,181,256,1,1,3,'/1/2/181/188/',9,1,0,'news/politics',188),(174,173,241,1,1,3,'/1/2/173/174/',9,1,0,'polls/which_one_is_the_best_of_matrix_movies',174),(173,2,240,2,1,2,'/1/2/173/',8,1,0,'polls',173),(199,182,275,1,1,5,'/1/2/181/188/182/199/',1,1,0,'news/politics/breaking_news/sdsdd',199),(198,182,272,1,1,5,'/1/2/181/188/182/198/',1,1,0,'news/politics/breaking_news/fgdfg',198),(190,181,258,1,1,3,'/1/2/181/190/',9,1,0,'news/business',190),(191,181,259,1,1,3,'/1/2/181/191/',9,1,0,'news/entertainment',191),(192,190,260,4,1,4,'/1/2/181/190/192/',9,1,0,'news/business/latest_business_update',192),(193,191,261,2,1,4,'/1/2/181/191/193/',9,1,0,'news/entertainment/arnold_for_governor',193),(200,191,278,1,1,4,'/1/2/181/191/200/',9,1,0,'news/entertainment/entertainment_weekly',200),(194,2,263,2,1,2,'/1/2/194/',9,1,0,'contact_information',194),(182,188,249,5,1,4,'/1/2/181/188/182/',9,1,0,'news/politics/breaking_news',182),(183,189,250,3,1,4,'/1/2/181/189/183/',9,1,0,'news/sports/leauge_champion',183),(181,2,248,1,1,2,'/1/2/181/',9,1,0,'news',181),(201,191,279,1,1,4,'/1/2/181/191/201/',9,1,0,'news/entertainment/will_he_become_president',201),(202,187,281,1,1,4,'/1/2/181/187/202/',9,1,0,'news/technology/final_release_of_abc',202),(203,190,283,1,1,4,'/1/2/181/190/203/',9,1,0,'news/business/dons_jonas_goes_down',203),(204,188,261,2,1,4,'/1/2/181/188/204/',9,1,0,'news/politics/arnold_for_governor',193),(205,189,284,2,1,4,'/1/2/181/189/205/',9,1,0,'news/sports/rider_wins_dart_competition',205),(208,187,290,2,1,4,'/1/2/181/187/208/',9,1,0,'news/technology/new_top_fair',208),(207,173,289,1,1,3,'/1/2/173/207/',9,1,0,'polls/what_season_is_the_best',207);
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
INSERT INTO ezcontentobject_version VALUES (804,1,14,6,1068710443,1068710484,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,3,0,0),(831,235,14,1,1068718746,1068718760,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(888,45,14,11,1069159015,1069159022,1,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(927,281,14,1,1069681152,1069681297,1,0,0),(913,56,14,58,1069245875,1069245892,3,0,0),(875,257,14,1,1069145734,1069145751,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(926,280,14,1,1069680959,1069680959,0,0,0),(907,56,14,56,1069243637,1069243739,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(880,261,14,1,1069147872,1069147950,3,0,0),(836,240,14,1,1068719631,1068719643,3,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(884,249,14,5,1069158567,1069158606,1,0,0),(725,161,14,1,1068047518,1068047603,3,0,0),(906,56,14,55,1069243470,1069243625,3,0,0),(914,56,14,59,1069245905,1069253804,3,0,0),(950,56,14,65,1069841102,1069841356,1,0,0),(912,275,10,1,1069245275,1069245295,1,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(916,56,14,61,1069319172,1069319197,3,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(845,43,14,9,1068729346,1068729356,3,0,0),(838,240,14,2,1068720650,1068720665,1,0,0),(919,56,14,63,1069416450,1069416472,3,0,0),(918,56,14,62,1069329997,1069330025,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(835,239,14,1,1068719292,1068719374,1,0,0),(861,1,14,7,1069069717,1069069747,1,1,0),(908,56,14,57,1069243755,1069243766,3,0,0),(874,254,14,2,1069145684,1069145714,3,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(695,1,14,3,1068035768,1068035779,3,1,0),(848,245,14,1,1068730464,1068730475,1,0,0),(844,115,14,4,1068729296,1068729308,3,0,0),(813,219,14,1,1068716892,1068716920,1,0,0),(911,274,10,1,1069244621,1069244633,0,0,0),(910,273,10,1,1069244442,1069244456,0,0,0),(909,272,10,1,1069244370,1069244422,1,0,0),(840,242,14,1,1068720892,1068720915,1,0,0),(876,250,14,2,1069145933,1069145962,3,0,0),(904,271,14,1,1069242995,1069243021,3,0,0),(878,259,14,1,1069146722,1069146733,1,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(839,241,14,1,1068720708,1068720802,1,0,0),(924,278,14,1,1069680582,1069680732,1,0,0),(903,270,10,1,1069239916,1069239927,0,0,0),(902,260,14,3,1069239712,1069239723,3,0,0),(870,249,14,3,1069077544,1069077554,3,0,0),(829,233,14,1,1068718686,1068718705,1,0,0),(866,252,14,1,1069074559,1069074891,1,0,0),(925,279,14,1,1069680746,1069680907,1,0,0),(877,258,14,1,1069146650,1069146661,1,0,0),(879,260,14,1,1069147441,1069147811,3,0,0),(900,269,10,1,1069239679,1069239748,1,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(899,268,10,1,1069239656,1069239656,0,0,0),(923,10,14,2,1069677828,1069677828,0,0,0),(867,249,14,2,1069076434,1069076500,3,0,0),(869,254,14,1,1069077391,1069077452,3,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(897,267,10,1,1069239288,1069239288,0,0,0),(796,14,14,5,1068468183,1068468218,1,0,0),(814,220,14,1,1068716938,1068716967,1,0,0),(847,116,14,3,1068729385,1068729395,3,0,0),(896,266,10,1,1069239136,1069239136,0,0,0),(949,56,14,64,1069840988,1069841035,3,0,0),(850,246,14,1,1068737185,1068737197,1,0,0),(846,45,14,10,1068729368,1068729376,3,0,0),(895,265,10,1,1069239075,1069239075,0,0,0),(805,161,14,2,1068710499,1068710511,3,0,0),(864,250,14,1,1069072371,1069072401,3,0,0),(873,249,14,4,1069145636,1069145665,3,0,0),(863,249,14,1,1069071352,1069071380,3,0,0),(872,256,14,1,1069145315,1069145327,1,0,0),(862,248,14,1,1069070970,1069070990,1,0,0),(871,255,14,1,1069145180,1069145297,1,0,0),(882,260,14,2,1069157446,1069157467,3,0,0),(922,277,14,1,1069676048,1069676048,0,0,0),(886,115,14,5,1069158923,1069158933,1,0,0),(887,43,14,10,1069158943,1069159003,1,0,0),(889,116,14,4,1069159032,1069159040,1,0,0),(891,161,14,3,1069236718,1069236739,3,0,0),(892,263,14,1,1069236860,1069236942,3,0,0),(893,264,14,1,1069236962,1069237025,1,0,0),(929,283,14,1,1069681344,1069681442,1,0,0),(930,260,14,4,1069681530,1069681784,1,0,0),(931,271,14,2,1069682097,1069682291,1,0,0),(932,261,14,2,1069682326,1069682497,1,0,0),(933,254,14,3,1069682530,1069682589,1,0,0),(934,250,14,3,1069682627,1069683166,1,0,0),(935,284,14,1,1069683250,1069683484,3,0,0),(936,285,14,1,1069683532,1069683532,0,0,0),(937,161,14,4,1069683751,1069683989,1,0,0),(938,263,14,2,1069684001,1069684195,1,0,0),(939,286,14,1,1069684266,1069684266,0,0,0),(940,287,14,1,1069684434,1069684434,0,0,0),(943,284,14,2,1069685130,1069685383,1,0,0),(942,289,14,1,1069684552,1069684665,1,0,0),(944,290,14,1,1069685460,1069685704,3,0,0),(947,290,14,2,1069757185,1069757297,1,0,0);
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
INSERT INTO ezimagefile VALUES (2,152,'var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB/news.gif'),(3,152,'var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB/news_reference.gif'),(4,152,'var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB/news_medium.gif'),(5,152,'var/news/storage/images/setup/look_and_feel/news/152-61-eng-GB/news_logo.gif'),(7,152,'var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB/news.gif'),(8,152,'var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB/news_reference.gif'),(9,152,'var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB/news_medium.gif'),(10,152,'var/news/storage/images/setup/look_and_feel/news/152-62-eng-GB/news_logo.gif'),(11,152,'var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB/news.gif'),(12,152,'var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB/news_reference.gif'),(13,152,'var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB/news_medium.gif'),(14,152,'var/news/storage/images/setup/look_and_feel/news/152-63-eng-GB/news_logo.gif'),(15,1087,'var/news/storage/images/news/entertainment/entertainment_weekly/1087-1-eng-GB/entertainment_weekly.'),(16,1094,'var/news/storage/images/news/entertainment/will_he_become_president/1094-1-eng-GB/will_he_become_president.'),(17,1108,'var/news/storage/images/news/technology/final_release_of_abc/1108-1-eng-GB/final_release_of_abc.'),(18,1122,'var/news/storage/images/news/business/dons_jonas_goes_down/1122-1-eng-GB/dons_jonas_goes_down.'),(19,981,'var/news/storage/images/news/business/latest_business_update/981-4-eng-GB/latest_business_update.'),(20,1045,'var/news/storage/images/news/business/business_as_usual/1045-2-eng-GB/business_as_usual.'),(21,987,'var/news/storage/images/news/politics/arnold_for_governor/987-2-eng-GB/arnold_for_governor.'),(22,965,'var/news/storage/images/news/technology/google_indexes/965-3-eng-GB/google_indexes.'),(23,938,'var/news/storage/images/news/sports/leauge_champion/938-3-eng-GB/leauge_champion.'),(24,1129,'var/news/storage/images/news/sports/rider_wins_dart_competition/1129-1-eng-GB/rider_wins_dart_competition.'),(25,524,'var/news/storage/images/about_this_website/524-4-eng-GB/about_this_website.'),(26,1005,'var/news/storage/images/contact_information/1005-2-eng-GB/contact_information.'),(27,1129,'var/news/storage/images/news/sports/rider_wins_dart_competition/1129-2-eng-GB/rider_wins_dart_competition.'),(28,1143,'var/news/storage/images/news/technology/new_top_fair/1143-1-eng-GB/new_top_fair.'),(30,1143,'var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB/new_top_fair.jpg'),(31,1143,'var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB/new_top_fair_reference.jpg'),(32,1143,'var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB/new_top_fair_medium.jpg'),(33,1143,'var/news/storage/images/news/technology/new_top_fair/1143-2-eng-GB/new_top_fair_small.jpg'),(35,152,'var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB/news.gif'),(36,152,'var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB/news_reference.gif'),(37,152,'var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB/news_medium.gif'),(38,152,'var/news/storage/images/setup/look_and_feel/news/152-64-eng-GB/news_logo.gif'),(40,152,'var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB/news.gif'),(41,152,'var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB/news_reference.gif'),(42,152,'var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB/news_medium.gif'),(43,152,'var/news/storage/images/setup/look_and_feel/news/152-65-eng-GB/news_logo.gif');
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
INSERT INTO ezinfocollection VALUES (1,137,1068027503,'c6194244e6057c2ed46e92ac8c59be21',1068027503),(2,137,1068028058,'c6194244e6057c2ed46e92ac8c59be21',1068028058),(3,227,1068718291,'c6194244e6057c2ed46e92ac8c59be21',1068718291),(4,227,1068718359,'c6194244e6057c2ed46e92ac8c59be21',1068718359),(5,227,1068721732,'c6194244e6057c2ed46e92ac8c59be21',1068721732),(6,227,1068723204,'c6194244e6057c2ed46e92ac8c59be21',1068723204),(7,227,1068723216,'c6194244e6057c2ed46e92ac8c59be21',1068723216),(8,227,1068723236,'c6194244e6057c2ed46e92ac8c59be21',1068723236),(9,227,1068723826,'c6194244e6057c2ed46e92ac8c59be21',1068723826),(10,227,1068723856,'c6194244e6057c2ed46e92ac8c59be21',1068723856),(11,227,1068724005,'c6194244e6057c2ed46e92ac8c59be21',1068724005),(12,227,1068724227,'c6194244e6057c2ed46e92ac8c59be21',1068724227),(13,227,1068726335,'c6194244e6057c2ed46e92ac8c59be21',1068726335),(14,227,1068726772,'c6194244e6057c2ed46e92ac8c59be21',1068726772),(15,227,1068727910,'c6194244e6057c2ed46e92ac8c59be21',1068727910),(16,227,1068729189,'9d6d05ca28ed8f65e38e0e7f01741744',1068729189),(17,227,1068729968,'cf64399b65e473dd59293d990f30bfbf',1068729968),(18,227,1068731428,'c6194244e6057c2ed46e92ac8c59be21',1068731428),(19,227,1068731436,'c6194244e6057c2ed46e92ac8c59be21',1068731436),(20,227,1068731442,'c6194244e6057c2ed46e92ac8c59be21',1068731442),(21,227,1068732540,'c6194244e6057c2ed46e92ac8c59be21',1068732540),(22,227,1068736388,'c6194244e6057c2ed46e92ac8c59be21',1068736388),(23,227,1068736850,'c6194244e6057c2ed46e92ac8c59be21',1068736850),(24,227,1068737071,'c6194244e6057c2ed46e92ac8c59be21',1068737071),(25,227,1068796372,'c6194244e6057c2ed46e92ac8c59be21',1068796372),(26,227,1068823513,'246f16e4f01d95e37296d2c33eff57d7',1068823513),(27,227,1068823514,'246f16e4f01d95e37296d2c33eff57d7',1068823514),(28,227,1069081028,'246f16e4f01d95e37296d2c33eff57d7',1069081028),(29,227,1069081483,'246f16e4f01d95e37296d2c33eff57d7',1069081483),(30,241,1069081500,'246f16e4f01d95e37296d2c33eff57d7',1069081500),(31,241,1069082057,'246f16e4f01d95e37296d2c33eff57d7',1069082057),(32,241,1069082069,'246f16e4f01d95e37296d2c33eff57d7',1069082069),(33,241,1069082502,'246f16e4f01d95e37296d2c33eff57d7',1069082502),(34,227,1069142011,'c6194244e6057c2ed46e92ac8c59be21',1069142011),(35,227,1069142051,'c6194244e6057c2ed46e92ac8c59be21',1069142051),(36,241,1069154052,'246f16e4f01d95e37296d2c33eff57d7',1069154052),(37,241,1069164627,'246f16e4f01d95e37296d2c33eff57d7',1069164627),(38,241,1069169830,'c6194244e6057c2ed46e92ac8c59be21',1069169830),(39,241,1069235027,'c6194244e6057c2ed46e92ac8c59be21',1069235027),(40,241,1069235815,'c6194244e6057c2ed46e92ac8c59be21',1069235815),(41,241,1069240710,'246f16e4f01d95e37296d2c33eff57d7',1069240710),(42,241,1069241320,'246f16e4f01d95e37296d2c33eff57d7',1069241320),(43,288,1069684531,'c6194244e6057c2ed46e92ac8c59be21',1069684531),(44,289,1069684793,'c6194244e6057c2ed46e92ac8c59be21',1069684793),(45,289,1069684799,'5b5270557d219bf1e9501f082d09e959',1069684799),(46,289,1069684810,'5b5270557d219bf1e9501f082d09e959',1069684810);
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
INSERT INTO ezinfocollection_attribute VALUES (1,1,'',0,0,183,443,137),(2,1,'',0,0,185,445,137),(3,1,'',0,0,184,444,137),(4,2,'FOo bar ',0,0,183,443,137),(5,2,'bf@ez.no',0,0,185,445,137),(6,2,'This is my feedback.',0,0,184,444,137),(7,3,'',0,0,208,789,227),(8,4,'',2,0,208,789,227),(9,5,'',2,0,208,789,227),(10,6,'',3,0,208,789,227),(11,7,'',4,0,208,789,227),(12,8,'',1,0,208,789,227),(13,9,'',1,0,208,789,227),(14,10,'',1,0,208,789,227),(15,11,'',3,0,208,789,227),(16,12,'',3,0,208,789,227),(17,13,'',3,0,208,789,227),(18,14,'',0,0,208,789,227),(19,15,'',1,0,208,789,227),(20,16,'',2,0,208,789,227),(21,17,'',2,0,208,789,227),(22,18,'',0,0,208,789,227),(23,19,'',0,0,208,789,227),(24,20,'',0,0,208,789,227),(25,21,'',0,0,208,789,227),(26,22,'',0,0,208,789,227),(27,23,'',1,0,208,789,227),(28,24,'',1,0,208,789,227),(29,25,'',2,0,208,789,227),(30,26,'',0,0,208,789,227),(31,27,'',0,0,208,789,227),(32,28,'',0,0,208,789,227),(33,29,'',3,0,208,789,227),(34,30,'',1,0,208,831,241),(35,31,'',1,0,208,831,241),(36,32,'',0,0,208,831,241),(37,33,'',0,0,208,831,241),(38,34,'',1,0,208,789,227),(39,35,'',2,0,208,789,227),(40,36,'',1,0,208,831,241),(41,37,'',0,0,208,831,241),(42,38,'',1,0,208,831,241),(43,39,'',1,0,208,831,241),(44,40,'',1,0,208,831,241),(45,41,'',0,0,208,831,241),(46,42,'',0,0,208,831,241),(47,43,'',0,0,208,1136,288),(48,44,'',2,0,208,1138,289),(49,45,'',1,0,208,1138,289),(50,46,'',1,0,208,1138,289);
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
INSERT INTO eznode_assignment VALUES (510,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(626,10,2,5,1,1,1,0,0),(570,254,1,181,9,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(540,242,1,169,1,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(592,45,11,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(617,56,58,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(611,56,56,48,9,1,1,0,0),(575,249,4,188,9,1,1,181,0),(606,260,3,190,9,1,1,0,0),(610,56,55,48,9,1,1,0,0),(581,258,1,181,9,1,1,0,0),(593,116,4,46,9,1,1,0,0),(583,260,1,190,9,1,1,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(588,249,5,188,9,1,1,0,0),(580,250,2,189,9,1,1,181,0),(618,56,59,48,9,1,1,0,0),(651,56,65,48,9,1,1,0,0),(629,280,1,187,1,1,1,0,0),(607,270,1,182,1,1,1,0,0),(321,45,6,46,9,1,1,0,0),(620,56,61,48,9,1,1,0,0),(578,257,1,181,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(573,256,1,181,9,1,1,0,0),(544,115,4,46,9,1,1,0,0),(608,271,1,190,9,1,1,0,0),(623,56,63,48,9,1,1,0,0),(545,43,9,46,9,1,1,0,0),(335,45,8,46,9,1,1,0,0),(622,56,62,48,9,1,1,0,0),(546,45,10,46,9,1,1,0,0),(548,245,1,177,1,1,1,0,0),(561,1,7,1,9,1,1,0,0),(612,56,57,48,9,1,1,0,0),(572,255,1,181,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(398,1,3,1,9,1,1,0,0),(584,261,1,191,9,1,1,0,0),(564,250,1,181,9,1,1,0,0),(604,269,1,182,1,1,1,0,0),(603,268,1,2,1,1,1,0,0),(601,267,1,182,1,1,1,0,0),(591,43,10,46,9,1,1,0,0),(563,249,1,181,9,1,1,0,0),(516,219,1,156,9,1,1,0,0),(613,272,1,182,1,1,1,0,0),(424,14,2,13,9,1,1,0,0),(550,246,1,177,1,1,1,0,0),(547,116,3,46,9,1,1,0,0),(600,266,1,182,1,1,1,0,0),(599,265,1,181,1,1,1,0,0),(595,161,3,2,9,1,1,0,0),(539,241,1,173,9,1,1,0,0),(627,278,1,191,9,1,1,0,0),(650,56,64,48,9,1,1,0,0),(596,263,1,2,9,1,1,0,0),(628,279,1,191,9,1,1,0,0),(528,233,1,156,9,1,1,0,0),(590,115,5,46,9,1,1,0,0),(538,240,2,2,8,1,1,0,0),(567,249,2,181,9,1,1,0,0),(481,14,3,13,9,1,1,0,0),(571,249,3,181,9,1,1,0,0),(586,260,2,190,9,1,1,0,0),(530,235,1,156,9,1,1,0,0),(535,240,1,2,9,1,1,0,0),(534,239,1,169,1,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(562,248,1,2,9,1,1,0,0),(500,14,5,13,9,1,1,0,0),(582,259,1,181,9,1,1,0,0),(577,254,2,187,9,1,1,181,0),(597,264,1,2,9,1,1,0,0),(566,252,1,182,9,1,1,0,0),(517,220,1,156,9,1,1,0,0),(511,161,2,2,9,1,1,0,0),(614,273,1,182,1,1,1,0,0),(615,274,1,182,1,1,1,0,0),(616,275,1,182,1,1,1,0,0),(630,281,1,187,9,1,1,0,0),(632,283,1,190,9,1,1,0,0),(633,260,4,190,9,1,1,0,0),(634,271,2,190,9,1,1,0,0),(635,261,2,191,9,1,1,0,0),(636,261,2,188,9,1,0,0,0),(637,254,3,187,9,1,1,0,0),(638,250,3,189,9,1,1,0,0),(639,284,1,189,9,1,1,0,0),(640,161,4,2,9,1,1,0,0),(641,263,2,2,9,1,1,0,0),(644,284,2,189,9,1,1,0,0),(643,289,1,173,9,1,1,0,0),(645,290,1,187,9,1,1,0,0),(648,290,2,187,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (227,0,'ezpublish',142,3,0,0,'','','',''),(226,0,'ezpublish',141,3,0,0,'','','',''),(225,0,'ezpublish',211,2,0,0,'','','',''),(224,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',142,2,0,0,'','','',''),(222,0,'ezpublish',141,2,0,0,'','','',''),(221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','',''),(228,0,'ezpublish',1,6,0,0,'','','',''),(229,0,'ezpublish',161,2,0,0,'','','',''),(230,0,'ezpublish',49,2,0,0,'','','',''),(231,0,'ezpublish',212,1,0,0,'','','',''),(232,0,'ezpublish',213,1,0,0,'','','',''),(233,0,'ezpublish',214,1,0,0,'','','',''),(234,0,'ezpublish',215,1,0,0,'','','',''),(235,0,'ezpublish',219,1,0,0,'','','',''),(236,0,'ezpublish',220,1,0,0,'','','',''),(237,0,'ezpublish',212,2,0,0,'','','',''),(238,0,'ezpublish',213,2,0,0,'','','',''),(239,0,'ezpublish',226,1,0,0,'','','',''),(240,0,'ezpublish',227,1,0,0,'','','',''),(241,0,'ezpublish',228,1,0,0,'','','',''),(242,0,'ezpublish',229,1,0,0,'','','',''),(243,0,'ezpublish',230,1,0,0,'','','',''),(244,0,'ezpublish',231,1,0,0,'','','',''),(245,0,'ezpublish',233,1,0,0,'','','',''),(246,0,'ezpublish',232,1,0,0,'','','',''),(247,0,'ezpublish',235,1,0,0,'','','',''),(248,0,'ezpublish',234,1,0,0,'','','',''),(249,0,'ezpublish',237,1,0,0,'','','',''),(250,0,'ezpublish',236,1,0,0,'','','',''),(251,0,'ezpublish',238,1,0,0,'','','',''),(252,0,'ezpublish',239,1,0,0,'','','',''),(253,0,'ezpublish',240,1,0,0,'','','',''),(254,0,'ezpublish',227,2,0,0,'','','',''),(255,0,'ezpublish',240,2,0,0,'','','',''),(256,0,'ezpublish',241,1,0,0,'','','',''),(257,0,'ezpublish',242,1,0,0,'','','',''),(258,0,'ezpublish',243,1,0,0,'','','',''),(259,0,'ezpublish',244,1,0,0,'','','',''),(260,0,'ezpublish',56,47,0,0,'','','',''),(261,0,'ezpublish',115,4,0,0,'','','',''),(262,0,'ezpublish',43,9,0,0,'','','',''),(263,0,'ezpublish',45,10,0,0,'','','',''),(264,0,'ezpublish',116,3,0,0,'','','',''),(265,0,'ezpublish',245,1,0,0,'','','',''),(266,0,'ezpublish',56,48,0,0,'','','',''),(267,0,'ezpublish',246,1,0,0,'','','',''),(268,0,'ezpublish',56,49,0,0,'','','',''),(269,0,'ezpublish',247,1,0,0,'','','',''),(270,0,'ezpublish',228,2,0,0,'','','',''),(271,0,'ezpublish',49,3,0,0,'','','',''),(272,0,'ezpublish',228,3,0,0,'','','',''),(273,0,'ezpublish',49,4,0,0,'','','',''),(274,0,'ezpublish',228,4,0,0,'','','',''),(275,0,'ezpublish',49,5,0,0,'','','',''),(276,0,'ezpublish',56,50,0,0,'','','',''),(277,0,'ezpublish',56,51,0,0,'','','',''),(278,0,'ezpublish',1,7,0,0,'','','',''),(279,0,'ezpublish',248,1,0,0,'','','',''),(280,0,'ezpublish',249,1,0,0,'','','',''),(281,0,'ezpublish',250,1,0,0,'','','',''),(282,0,'ezpublish',252,1,0,0,'','','',''),(283,0,'ezpublish',253,1,0,0,'','','',''),(284,0,'ezpublish',249,2,0,0,'','','',''),(285,0,'ezpublish',254,1,0,0,'','','',''),(286,0,'ezpublish',249,3,0,0,'','','',''),(287,0,'ezpublish',255,1,0,0,'','','',''),(288,0,'ezpublish',256,1,0,0,'','','',''),(289,0,'ezpublish',249,4,0,0,'','','',''),(290,0,'ezpublish',254,2,0,0,'','','',''),(291,0,'ezpublish',257,1,0,0,'','','',''),(292,0,'ezpublish',250,2,0,0,'','','',''),(293,0,'ezpublish',258,1,0,0,'','','',''),(294,0,'ezpublish',259,1,0,0,'','','',''),(295,0,'ezpublish',260,1,0,0,'','','',''),(296,0,'ezpublish',261,1,0,0,'','','',''),(297,0,'ezpublish',260,2,0,0,'','','',''),(298,0,'ezpublish',56,52,0,0,'','','',''),(299,0,'ezpublish',249,5,0,0,'','','',''),(300,0,'ezpublish',115,5,0,0,'','','',''),(301,0,'ezpublish',43,10,0,0,'','','',''),(302,0,'ezpublish',45,11,0,0,'','','',''),(303,0,'ezpublish',116,4,0,0,'','','',''),(304,0,'ezpublish',161,3,0,0,'','','',''),(305,0,'ezpublish',263,1,0,0,'','','',''),(306,0,'ezpublish',264,1,0,0,'','','',''),(307,0,'ezpublish',56,53,0,0,'','','',''),(308,0,'ezpublish',269,1,0,0,'','','',''),(309,0,'ezpublish',260,3,0,0,'','','',''),(310,0,'ezpublish',271,1,0,0,'','','',''),(311,0,'ezpublish',56,54,0,0,'','','',''),(312,0,'ezpublish',56,55,0,0,'','','',''),(313,0,'ezpublish',56,56,0,0,'','','',''),(314,0,'ezpublish',56,57,0,0,'','','',''),(315,0,'ezpublish',272,1,0,0,'','','',''),(316,0,'ezpublish',275,1,0,0,'','','',''),(317,0,'ezpublish',56,58,0,0,'','','',''),(318,0,'ezpublish',56,59,0,0,'','','',''),(319,0,'ezpublish',56,61,0,0,'','','',''),(320,0,'ezpublish',56,62,0,0,'','','',''),(321,0,'ezpublish',56,63,0,0,'','','',''),(322,0,'ezpublish',278,1,0,0,'','','',''),(323,0,'ezpublish',279,1,0,0,'','','',''),(324,0,'ezpublish',281,1,0,0,'','','',''),(325,0,'ezpublish',283,1,0,0,'','','',''),(326,0,'ezpublish',260,4,0,0,'','','',''),(327,0,'ezpublish',271,2,0,0,'','','',''),(328,0,'ezpublish',261,2,0,0,'','','',''),(329,0,'ezpublish',254,3,0,0,'','','',''),(330,0,'ezpublish',250,3,0,0,'','','',''),(331,0,'ezpublish',284,1,0,0,'','','',''),(332,0,'ezpublish',161,4,0,0,'','','',''),(333,0,'ezpublish',263,2,0,0,'','','',''),(334,0,'ezpublish',288,1,0,0,'','','',''),(335,0,'ezpublish',289,1,0,0,'','','',''),(336,0,'ezpublish',284,2,0,0,'','','',''),(337,0,'ezpublish',290,1,0,0,'','','',''),(338,0,'ezpublish',290,2,0,0,'','','',''),(339,0,'ezpublish',56,64,0,0,'','','',''),(340,0,'ezpublish',56,65,0,0,'','','','');
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(341,8,'read','content','*'),(393,1,'create','content',''),(392,1,'read','content',''),(391,1,'login','user','*'),(394,1,'edit','content',''),(395,1,'versionread','content','');
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
INSERT INTO ezpolicy_limitation VALUES (311,394,'Class',0,'edit','content'),(310,393,'Class',0,'create','content'),(309,392,'Class',0,'read','content'),(312,395,'Class',0,'versionread','content'),(313,395,'Owner',0,'versionread','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (646,311,'26'),(645,310,'26'),(644,309,'5'),(643,309,'26'),(642,309,'25'),(641,309,'24'),(640,309,'23'),(639,309,'2'),(638,309,'12'),(637,309,'10'),(636,309,'1'),(647,312,'26'),(648,313,'1');
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
INSERT INTO ezsearch_object_word_link VALUES (28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(7267,43,2063,0,2,2062,0,14,1066384365,11,155,'',0),(7266,43,2062,0,1,2061,2063,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(7265,43,2061,0,0,0,2062,14,1066384365,11,152,'',0),(7273,45,2065,0,5,2064,0,14,1066388816,11,155,'',0),(7272,45,2064,0,4,25,2065,14,1066388816,11,155,'',0),(7271,45,25,0,3,34,2064,14,1066388816,11,155,'',0),(7270,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(9836,56,2224,0,7,2918,0,15,1066643397,11,220,'',0),(3754,219,1524,0,70,1523,1503,26,1068716920,13,212,'',0),(3753,219,1523,0,69,1522,1524,26,1068716920,13,212,'',0),(3752,219,1522,0,68,1509,1523,26,1068716920,13,212,'',0),(9819,290,2811,0,391,1535,2191,2,1069685704,1,121,'',0),(9818,290,1535,0,390,1537,2811,2,1069685704,1,121,'',0),(9817,290,1537,0,389,2906,1535,2,1069685704,1,121,'',0),(9816,290,2906,0,388,2014,1537,2,1069685704,1,121,'',0),(9815,290,2014,0,387,2905,2906,2,1069685704,1,121,'',0),(9814,290,2905,0,386,2440,2014,2,1069685704,1,121,'',0),(9813,290,2440,0,385,2014,2905,2,1069685704,1,121,'',0),(9812,290,2014,0,384,2231,2440,2,1069685704,1,121,'',0),(9811,290,2231,0,383,2904,2014,2,1069685704,1,121,'',0),(9810,290,2904,0,382,33,2231,2,1069685704,1,121,'',0),(9809,290,33,0,381,2903,2904,2,1069685704,1,121,'',0),(9808,290,2903,0,380,2794,33,2,1069685704,1,121,'',0),(9807,290,2794,0,379,2364,2903,2,1069685704,1,121,'',0),(9806,290,2364,0,378,2585,2794,2,1069685704,1,121,'',0),(9805,290,2585,0,377,2310,2364,2,1069685704,1,121,'',0),(9804,290,2310,0,376,2902,2585,2,1069685704,1,121,'',0),(9803,290,2902,0,375,2442,2310,2,1069685704,1,121,'',0),(9802,290,2442,0,374,2390,2902,2,1069685704,1,121,'',0),(9801,290,2390,0,373,2598,2442,2,1069685704,1,121,'',0),(9800,290,2598,0,372,2048,2390,2,1069685704,1,121,'',0),(9799,290,2048,0,371,2901,2598,2,1069685704,1,121,'',0),(9798,290,2901,0,370,33,2048,2,1069685704,1,121,'',0),(9797,290,33,0,369,2611,2901,2,1069685704,1,121,'',0),(9796,290,2611,0,368,1725,33,2,1069685704,1,121,'',0),(9795,290,1725,0,367,2900,2611,2,1069685704,1,121,'',0),(9794,290,2900,0,366,2794,1725,2,1069685704,1,121,'',0),(9793,290,2794,0,365,2868,2900,2,1069685704,1,121,'',0),(9792,290,2868,0,364,2899,2794,2,1069685704,1,121,'',0),(9791,290,2899,0,363,2181,2868,2,1069685704,1,121,'',0),(9790,290,2181,0,362,2862,2899,2,1069685704,1,121,'',0),(9789,290,2862,0,361,34,2181,2,1069685704,1,121,'',0),(9788,290,34,0,360,2770,2862,2,1069685704,1,121,'',0),(9787,290,2770,0,359,1535,34,2,1069685704,1,121,'',0),(9786,290,1535,0,358,2898,2770,2,1069685704,1,121,'',0),(9785,290,2898,0,357,2897,1535,2,1069685704,1,121,'',0),(9784,290,2897,0,356,1535,2898,2,1069685704,1,121,'',0),(9783,290,1535,0,355,2230,2897,2,1069685704,1,121,'',0),(9782,290,2230,0,354,2246,1535,2,1069685704,1,121,'',0),(9781,290,2246,0,353,2024,2230,2,1069685704,1,121,'',0),(9780,290,2024,0,352,2335,2246,2,1069685704,1,121,'',0),(9034,284,2191,0,238,2630,0,2,1069683484,1,219,'',1),(9033,284,2630,0,237,2629,2191,2,1069683484,1,121,'',0),(9032,284,2629,0,236,2101,2630,2,1069683484,1,121,'',0),(9031,284,2101,0,235,2628,2629,2,1069683484,1,121,'',0),(9030,284,2628,0,234,2590,2101,2,1069683484,1,121,'',0),(9029,284,2590,0,233,1965,2628,2,1069683484,1,121,'',0),(9028,284,1965,0,232,2255,2590,2,1069683484,1,121,'',0),(9027,284,2255,0,231,2627,1965,2,1069683484,1,121,'',0),(9026,284,2627,0,230,2614,2255,2,1069683484,1,121,'',0),(9025,284,2614,0,229,33,2627,2,1069683484,1,121,'',0),(9024,284,33,0,228,2626,2614,2,1069683484,1,121,'',0),(9023,284,2626,0,227,2048,33,2,1069683484,1,121,'',0),(9022,284,2048,0,226,2625,2626,2,1069683484,1,121,'',0),(9021,284,2625,0,225,2624,2048,2,1069683484,1,121,'',0),(9020,284,2624,0,224,2623,2625,2,1069683484,1,121,'',0),(9019,284,2623,0,223,2101,2624,2,1069683484,1,121,'',0),(9018,284,2101,0,222,2590,2623,2,1069683484,1,121,'',0),(9017,284,2590,0,221,1965,2101,2,1069683484,1,121,'',0),(9016,284,1965,0,220,2394,2590,2,1069683484,1,121,'',0),(9015,284,2394,0,219,2589,1965,2,1069683484,1,121,'',0),(9014,284,2589,0,218,2622,2394,2,1069683484,1,121,'',0),(9013,284,2622,0,217,2621,2589,2,1069683484,1,121,'',0),(9012,284,2621,0,216,2530,2622,2,1069683484,1,121,'',0),(9011,284,2530,0,215,2229,2621,2,1069683484,1,121,'',0),(9010,284,2229,0,214,1490,2530,2,1069683484,1,121,'',0),(9009,284,1490,0,213,2620,2229,2,1069683484,1,121,'',0),(9008,284,2620,0,212,2547,1490,2,1069683484,1,121,'',0),(9007,284,2547,0,211,2619,2620,2,1069683484,1,121,'',0),(9006,284,2619,0,210,2231,2547,2,1069683484,1,121,'',0),(9005,284,2231,0,209,2292,2619,2,1069683484,1,121,'',0),(9004,284,2292,0,208,2590,2231,2,1069683484,1,121,'',0),(9003,284,2590,0,207,1965,2292,2,1069683484,1,121,'',0),(9002,284,1965,0,206,2618,2590,2,1069683484,1,121,'',0),(9001,284,2618,0,205,33,1965,2,1069683484,1,121,'',0),(9000,284,33,0,204,2300,2618,2,1069683484,1,121,'',0),(8999,284,2300,0,203,2293,33,2,1069683484,1,121,'',0),(8998,284,2293,0,202,2617,2300,2,1069683484,1,121,'',0),(8997,284,2617,0,201,2616,2293,2,1069683484,1,121,'',0),(8996,284,2616,0,200,2615,2617,2,1069683484,1,121,'',0),(8995,284,2615,0,199,2614,2616,2,1069683484,1,121,'',0),(8994,284,2614,0,198,2613,2615,2,1069683484,1,121,'',0),(8993,284,2613,0,197,2255,2614,2,1069683484,1,121,'',0),(3751,219,1509,0,67,1521,1522,26,1068716920,13,212,'',0),(3750,219,1521,0,66,1526,1509,26,1068716920,13,212,'',0),(3749,219,1526,0,65,1503,1521,26,1068716920,13,212,'',0),(3748,219,1503,0,64,1524,1526,26,1068716920,13,212,'',0),(3747,219,1524,0,63,1523,1503,26,1068716920,13,212,'',0),(3746,219,1523,0,62,1522,1524,26,1068716920,13,212,'',0),(3745,219,1522,0,61,1509,1523,26,1068716920,13,212,'',0),(3744,219,1509,0,60,1525,1522,26,1068716920,13,212,'',0),(3743,219,1525,0,59,1503,1509,26,1068716920,13,212,'',0),(3742,219,1503,0,58,1524,1525,26,1068716920,13,212,'',0),(3741,219,1524,0,57,1523,1503,26,1068716920,13,212,'',0),(3740,219,1523,0,56,1522,1524,26,1068716920,13,212,'',0),(3739,219,1522,0,55,1509,1523,26,1068716920,13,212,'',0),(3738,219,1509,0,54,1525,1522,26,1068716920,13,212,'',0),(3737,219,1525,0,53,1503,1509,26,1068716920,13,212,'',0),(3736,219,1503,0,52,1524,1525,26,1068716920,13,212,'',0),(3735,219,1524,0,51,1523,1503,26,1068716920,13,212,'',0),(3734,219,1523,0,50,1522,1524,26,1068716920,13,212,'',0),(3733,219,1522,0,49,1509,1523,26,1068716920,13,212,'',0),(3732,219,1509,0,48,1525,1522,26,1068716920,13,212,'',0),(3731,219,1525,0,47,1503,1509,26,1068716920,13,212,'',0),(3730,219,1503,0,46,1524,1525,26,1068716920,13,212,'',0),(3729,219,1524,0,45,1523,1503,26,1068716920,13,212,'',0),(3728,219,1523,0,44,1522,1524,26,1068716920,13,212,'',0),(3727,219,1522,0,43,1509,1523,26,1068716920,13,212,'',0),(3726,219,1509,0,42,1525,1522,26,1068716920,13,212,'',0),(3725,219,1525,0,41,1503,1509,26,1068716920,13,212,'',0),(3724,219,1503,0,40,1524,1525,26,1068716920,13,212,'',0),(3723,219,1524,0,39,1523,1503,26,1068716920,13,212,'',0),(3722,219,1523,0,38,1522,1524,26,1068716920,13,212,'',0),(3721,219,1522,0,37,1509,1523,26,1068716920,13,212,'',0),(3720,219,1509,0,36,1525,1522,26,1068716920,13,212,'',0),(3719,219,1525,0,35,1503,1509,26,1068716920,13,212,'',0),(3718,219,1503,0,34,1524,1525,26,1068716920,13,212,'',0),(3717,219,1524,0,33,1523,1503,26,1068716920,13,212,'',0),(3716,219,1523,0,32,1522,1524,26,1068716920,13,212,'',0),(3715,219,1522,0,31,1509,1523,26,1068716920,13,212,'',0),(3714,219,1509,0,30,1525,1522,26,1068716920,13,212,'',0),(3713,219,1525,0,29,1503,1509,26,1068716920,13,212,'',0),(3712,219,1503,0,28,1524,1525,26,1068716920,13,212,'',0),(3711,219,1524,0,27,1523,1503,26,1068716920,13,212,'',0),(3710,219,1523,0,26,1522,1524,26,1068716920,13,212,'',0),(3709,219,1522,0,25,1509,1523,26,1068716920,13,212,'',0),(3708,219,1509,0,24,1525,1522,26,1068716920,13,212,'',0),(3707,219,1525,0,23,1503,1509,26,1068716920,13,212,'',0),(3706,219,1503,0,22,1524,1525,26,1068716920,13,212,'',0),(3705,219,1524,0,21,1523,1503,26,1068716920,13,212,'',0),(3704,219,1523,0,20,1522,1524,26,1068716920,13,212,'',0),(3703,219,1522,0,19,1509,1523,26,1068716920,13,212,'',0),(3702,219,1509,0,18,1525,1522,26,1068716920,13,212,'',0),(3701,219,1525,0,17,1503,1509,26,1068716920,13,212,'',0),(3700,219,1503,0,16,1524,1525,26,1068716920,13,212,'',0),(3699,219,1524,0,15,1523,1503,26,1068716920,13,212,'',0),(3698,219,1523,0,14,1522,1524,26,1068716920,13,212,'',0),(3697,219,1522,0,13,1509,1523,26,1068716920,13,212,'',0),(3696,219,1509,0,12,1521,1522,26,1068716920,13,212,'',0),(3695,219,1521,0,11,1501,1509,26,1068716920,13,212,'',0),(3694,219,1501,0,10,1520,1521,26,1068716920,13,212,'',0),(3693,219,1520,0,9,1519,1501,26,1068716920,13,212,'',0),(3692,219,1519,0,8,1518,1520,26,1068716920,13,212,'',0),(3691,219,1518,0,7,1517,1519,26,1068716920,13,212,'',0),(3690,219,1517,0,6,1490,1518,26,1068716920,13,212,'',0),(3689,219,1490,0,5,1516,1517,26,1068716920,13,212,'',0),(3688,219,1516,0,4,1515,1490,26,1068716920,13,211,'',0),(3687,219,1515,0,3,1514,1516,26,1068716920,13,211,'',0),(3686,219,1514,0,2,1318,1515,26,1068716920,13,210,'',0),(3685,219,1318,0,1,1140,1514,26,1068716920,13,209,'',0),(3684,219,1140,0,0,0,1318,26,1068716920,13,209,'',0),(7685,269,2189,0,6,2188,0,26,1069239748,1,212,'',0),(7684,269,2188,0,5,1516,2189,26,1069239748,1,212,'',0),(7683,269,1516,0,4,1515,2188,26,1069239748,1,211,'',0),(7682,269,1515,0,3,2187,1516,26,1069239748,1,211,'',0),(7681,269,2187,0,2,1337,1515,26,1069239748,1,210,'',0),(7680,269,1337,0,1,2186,2187,26,1069239748,1,209,'',0),(7679,269,2186,0,0,0,1337,26,1069239748,1,217,'',0),(7677,264,2070,0,7,2025,0,10,1069237025,1,141,'',0),(7676,264,2025,0,6,2184,2070,10,1069237025,1,141,'',0),(7675,264,2184,0,5,2181,2025,10,1069237025,1,141,'',0),(7674,264,2181,0,4,2180,2184,10,1069237025,1,141,'',0),(7673,264,2180,0,3,2014,2181,10,1069237025,1,141,'',0),(7672,264,2014,0,2,2179,2180,10,1069237025,1,141,'',0),(7671,264,2179,0,1,2183,2014,10,1069237025,1,141,'',0),(7670,264,2183,0,0,0,2179,10,1069237025,1,140,'',0),(8744,263,2014,0,16,2179,2180,10,1069236942,1,141,'',0),(8743,263,2179,0,15,2247,2014,10,1069236942,1,141,'',0),(8742,263,2247,0,14,2245,2179,10,1069236942,1,141,'',0),(8741,263,2245,0,13,2529,2247,10,1069236942,1,141,'',0),(8740,263,2529,0,12,2528,2245,10,1069236942,1,141,'',0),(8739,263,2528,0,11,2325,2529,10,1069236942,1,141,'',0),(8728,263,2178,0,0,0,2179,10,1069236942,1,140,'',0),(8729,263,2179,0,1,2178,2101,10,1069236942,1,140,'',0),(8730,263,2101,0,2,2179,2178,10,1069236942,1,141,'',0),(8731,263,2178,0,3,2101,2527,10,1069236942,1,141,'',0),(8732,263,2527,0,4,2178,1725,10,1069236942,1,141,'',0),(8733,263,1725,0,5,2527,2344,10,1069236942,1,141,'',0),(8734,263,2344,0,6,1725,2246,10,1069236942,1,141,'',0),(8735,263,2246,0,7,2344,2288,10,1069236942,1,141,'',0),(8736,263,2288,0,8,2246,2327,10,1069236942,1,141,'',0),(8737,263,2327,0,9,2288,2325,10,1069236942,1,141,'',0),(8738,263,2325,0,10,2327,2528,10,1069236942,1,141,'',0),(9779,290,2335,0,351,1537,2024,2,1069685704,1,121,'',0),(9778,290,1537,0,350,2896,2335,2,1069685704,1,121,'',0),(9777,290,2896,0,349,2895,1537,2,1069685704,1,121,'',0),(9776,290,2895,0,348,2101,2896,2,1069685704,1,121,'',0),(9775,290,2101,0,347,2181,2895,2,1069685704,1,121,'',0),(9774,290,2181,0,346,2894,2101,2,1069685704,1,121,'',0),(9773,290,2894,0,345,2181,2181,2,1069685704,1,121,'',0),(9772,290,2181,0,344,2893,2894,2,1069685704,1,121,'',0),(9771,290,2893,0,343,33,2181,2,1069685704,1,121,'',0),(9770,290,33,0,342,2892,2893,2,1069685704,1,121,'',0),(9769,290,2892,0,341,2554,33,2,1069685704,1,121,'',0),(9768,290,2554,0,340,2847,2892,2,1069685704,1,121,'',0),(9767,290,2847,0,339,2228,2554,2,1069685704,1,121,'',0),(9766,290,2228,0,338,2891,2847,2,1069685704,1,121,'',0),(9765,290,2891,0,337,2794,2228,2,1069685704,1,121,'',0),(9764,290,2794,0,336,2256,2891,2,1069685704,1,121,'',0),(9763,290,2256,0,335,1725,2794,2,1069685704,1,121,'',0),(9762,290,1725,0,334,2025,2256,2,1069685704,1,121,'',0),(9761,290,2025,0,333,2890,1725,2,1069685704,1,121,'',0),(9760,290,2890,0,332,2889,2025,2,1069685704,1,121,'',0),(9759,290,2889,0,331,2014,2890,2,1069685704,1,121,'',0),(9758,290,2014,0,330,2888,2889,2,1069685704,1,121,'',0),(9757,290,2888,0,329,2887,2014,2,1069685704,1,121,'',0),(9756,290,2887,0,328,2181,2888,2,1069685704,1,121,'',0),(9755,290,2181,0,327,2886,2887,2,1069685704,1,121,'',0),(9754,290,2886,0,326,2360,2181,2,1069685704,1,121,'',0),(9753,290,2360,0,325,2805,2886,2,1069685704,1,121,'',0),(9752,290,2805,0,324,2528,2360,2,1069685704,1,121,'',0),(9751,290,2528,0,323,2885,2805,2,1069685704,1,121,'',0),(9750,290,2885,0,322,2181,2528,2,1069685704,1,121,'',0),(7936,272,2206,0,3,2205,2207,26,1069244422,1,211,'',0),(7935,272,2205,0,2,2204,2206,26,1069244422,1,210,'',0),(7934,272,2204,0,1,2203,2205,26,1069244422,1,209,'',0),(7933,272,2203,0,0,0,2204,26,1069244422,1,217,'',0),(9820,290,2191,0,392,2811,0,2,1069685704,1,219,'',1),(9749,290,2181,0,321,2861,2885,2,1069685704,1,121,'',0),(9748,290,2861,0,320,2079,2181,2,1069685704,1,121,'',0),(9747,290,2079,0,319,2884,2861,2,1069685704,1,121,'',0),(9746,290,2884,0,318,2038,2079,2,1069685704,1,121,'',0),(9745,290,2038,0,317,2360,2884,2,1069685704,1,121,'',0),(9744,290,2360,0,316,2312,2038,2,1069685704,1,121,'',0),(9743,290,2312,0,315,2773,2360,2,1069685704,1,121,'',0),(9742,290,2773,0,314,2254,2312,2,1069685704,1,121,'',0),(9741,290,2254,0,313,2883,2773,2,1069685704,1,121,'',0),(9740,290,2883,0,312,2101,2254,2,1069685704,1,121,'',0),(9739,290,2101,0,311,2882,2883,2,1069685704,1,121,'',0),(9738,290,2882,0,310,2558,2101,2,1069685704,1,121,'',0),(9737,290,2558,0,309,2290,2882,2,1069685704,1,121,'',0),(9736,290,2290,0,308,2881,2558,2,1069685704,1,121,'',0),(9735,290,2881,0,307,2880,2290,2,1069685704,1,121,'',0),(9734,290,2880,0,306,1535,2881,2,1069685704,1,121,'',0),(9733,290,1535,0,305,1537,2880,2,1069685704,1,121,'',0),(9732,290,1537,0,304,1558,1535,2,1069685704,1,121,'',0),(8286,271,2191,0,35,2248,0,2,1069243021,1,219,'',1),(8285,271,2248,0,34,2351,2191,2,1069243021,1,121,'',0),(8284,271,2351,0,33,1535,2248,2,1069243021,1,121,'',0),(8283,271,1535,0,32,1537,2351,2,1069243021,1,121,'',0),(8282,271,1537,0,31,2350,1535,2,1069243021,1,121,'',0),(8281,271,2350,0,30,1535,1537,2,1069243021,1,121,'',0),(8280,271,1535,0,29,2079,2350,2,1069243021,1,121,'',0),(8279,271,2079,0,28,2349,1535,2,1069243021,1,121,'',0),(8278,271,2349,0,27,2310,2079,2,1069243021,1,121,'',0),(8277,271,2310,0,26,2348,2349,2,1069243021,1,120,'',0),(8276,271,2348,0,25,2256,2310,2,1069243021,1,120,'',0),(8275,271,2256,0,24,2229,2348,2,1069243021,1,120,'',0),(8274,271,2229,0,23,2255,2256,2,1069243021,1,120,'',0),(8273,271,2255,0,22,2347,2229,2,1069243021,1,120,'',0),(8272,271,2347,0,21,2230,2255,2,1069243021,1,120,'',0),(8271,271,2230,0,20,2228,2347,2,1069243021,1,120,'',0),(8270,271,2228,0,19,2048,2230,2,1069243021,1,120,'',0),(8269,271,2048,0,18,2346,2228,2,1069243021,1,120,'',0),(8268,271,2346,0,17,2101,2048,2,1069243021,1,120,'',0),(8267,271,2101,0,16,2345,2346,2,1069243021,1,120,'',0),(8266,271,2345,0,15,1725,2101,2,1069243021,1,120,'',0),(8265,271,1725,0,14,2341,2345,2,1069243021,1,120,'',0),(8264,271,2341,0,13,2222,1725,2,1069243021,1,120,'',0),(8263,271,2222,0,12,2044,2341,2,1069243021,1,120,'',0),(8262,271,2044,0,11,2304,2222,2,1069243021,1,120,'',0),(8261,271,2304,0,10,2025,2044,2,1069243021,1,120,'',0),(8260,271,2025,0,9,2303,2304,2,1069243021,1,120,'',0),(8259,271,2303,0,8,2228,2025,2,1069243021,1,120,'',0),(8258,271,2228,0,7,2230,2303,2,1069243021,1,120,'',0),(8257,271,2230,0,6,2344,2228,2,1069243021,1,120,'',0),(8256,271,2344,0,5,2343,2230,2,1069243021,1,120,'',0),(8255,271,2343,0,4,2342,2344,2,1069243021,1,218,'',0),(8254,271,2342,0,3,2341,2343,2,1069243021,1,218,'',0),(8251,271,2044,0,0,0,2222,2,1069243021,1,1,'',0),(8252,271,2222,0,1,2044,2341,2,1069243021,1,1,'',0),(8253,271,2341,0,2,2222,2342,2,1069243021,1,1,'',0),(9731,290,1558,0,303,2051,1537,2,1069685704,1,121,'',0),(9730,290,2051,0,302,2182,1558,2,1069685704,1,121,'',0),(9729,290,2182,0,301,2181,2051,2,1069685704,1,121,'',0),(9728,290,2181,0,300,2879,2182,2,1069685704,1,121,'',0),(9727,290,2879,0,299,2805,2181,2,1069685704,1,121,'',0),(9726,290,2805,0,298,2878,2879,2,1069685704,1,121,'',0),(9725,290,2878,0,297,2101,2805,2,1069685704,1,121,'',0),(9724,290,2101,0,296,2051,2878,2,1069685704,1,121,'',0),(9723,290,2051,0,295,2607,2101,2,1069685704,1,121,'',0),(9722,290,2607,0,294,2877,2051,2,1069685704,1,121,'',0),(9721,290,2877,0,293,2051,2607,2,1069685704,1,121,'',0),(9720,290,2051,0,292,2876,2877,2,1069685704,1,121,'',0),(9719,290,2876,0,291,2025,2051,2,1069685704,1,121,'',0),(9718,290,2025,0,290,2048,2876,2,1069685704,1,121,'',0),(9717,290,2048,0,289,2875,2025,2,1069685704,1,121,'',0),(9716,290,2875,0,288,1537,2048,2,1069685704,1,121,'',0),(9715,290,1537,0,287,2801,2875,2,1069685704,1,121,'',0),(9714,290,2801,0,286,2101,1537,2,1069685704,1,121,'',0),(9713,290,2101,0,285,2874,2801,2,1069685704,1,121,'',0),(9712,290,2874,0,284,2181,2101,2,1069685704,1,121,'',0),(9711,290,2181,0,283,2230,2874,2,1069685704,1,121,'',0),(9710,290,2230,0,282,2873,2181,2,1069685704,1,121,'',0),(8992,284,2255,0,196,2612,2613,2,1069683484,1,121,'',0),(8991,284,2612,0,195,2611,2255,2,1069683484,1,121,'',0),(8990,284,2611,0,194,1535,2612,2,1069683484,1,121,'',0),(8989,284,1535,0,193,2610,2611,2,1069683484,1,121,'',0),(8988,284,2610,0,192,2334,1535,2,1069683484,1,121,'',0),(8987,284,2334,0,191,33,2610,2,1069683484,1,121,'',0),(8986,284,33,0,190,2291,2334,2,1069683484,1,121,'',0),(8985,284,2291,0,189,2595,33,2,1069683484,1,121,'',0),(8984,284,2595,0,188,1535,2291,2,1069683484,1,121,'',0),(8983,284,1535,0,187,2610,2595,2,1069683484,1,121,'',0),(8982,284,2610,0,186,2609,1535,2,1069683484,1,121,'',0),(8981,284,2609,0,185,2608,2610,2,1069683484,1,121,'',0),(8980,284,2608,0,184,2249,2609,2,1069683484,1,121,'',0),(8979,284,2249,0,183,2607,2608,2,1069683484,1,121,'',0),(8978,284,2607,0,182,2606,2249,2,1069683484,1,121,'',0),(8977,284,2606,0,181,2079,2607,2,1069683484,1,121,'',0),(8976,284,2079,0,180,33,2606,2,1069683484,1,121,'',0),(8975,284,33,0,179,2605,2079,2,1069683484,1,121,'',0),(8974,284,2605,0,178,2595,33,2,1069683484,1,121,'',0),(8973,284,2595,0,177,1535,2605,2,1069683484,1,121,'',0),(8972,284,1535,0,176,2014,2595,2,1069683484,1,121,'',0),(8971,284,2014,0,175,2604,1535,2,1069683484,1,121,'',0),(8970,284,2604,0,174,1537,2014,2,1069683484,1,121,'',0),(8969,284,1537,0,173,2603,2604,2,1069683484,1,121,'',0),(8968,284,2603,0,172,1535,1537,2,1069683484,1,121,'',0),(8967,284,1535,0,171,2292,2603,2,1069683484,1,121,'',0),(8966,284,2292,0,170,2590,1535,2,1069683484,1,121,'',0),(8965,284,2590,0,169,2602,2292,2,1069683484,1,121,'',0),(8964,284,2602,0,168,2601,2590,2,1069683484,1,121,'',0),(8963,284,2601,0,167,2222,2602,2,1069683484,1,121,'',0),(8962,284,2222,0,166,2394,2601,2,1069683484,1,121,'',0),(8961,284,2394,0,165,2589,2222,2,1069683484,1,121,'',0),(8960,284,2589,0,164,2600,2394,2,1069683484,1,121,'',0),(8959,284,2600,0,163,1535,2589,2,1069683484,1,121,'',0),(8958,284,1535,0,162,2048,2600,2,1069683484,1,121,'',0),(8957,284,2048,0,161,2599,1535,2,1069683484,1,121,'',0),(8956,284,2599,0,160,2181,2048,2,1069683484,1,121,'',0),(8955,284,2181,0,159,2271,2599,2,1069683484,1,121,'',0),(8954,284,2271,0,158,2598,2181,2,1069683484,1,121,'',0),(8953,284,2598,0,157,2436,2271,2,1069683484,1,121,'',0),(8952,284,2436,0,156,2597,2598,2,1069683484,1,121,'',0),(8951,284,2597,0,155,2596,2436,2,1069683484,1,121,'',0),(8950,284,2596,0,154,1725,2597,2,1069683484,1,121,'',0),(8949,284,1725,0,153,2249,2596,2,1069683484,1,121,'',0),(8948,284,2249,0,152,33,1725,2,1069683484,1,121,'',0),(8947,284,33,0,151,2547,2249,2,1069683484,1,121,'',0),(8946,284,2547,0,150,2284,33,2,1069683484,1,121,'',0),(8945,284,2284,0,149,2595,2547,2,1069683484,1,121,'',0),(8944,284,2595,0,148,1535,2284,2,1069683484,1,121,'',0),(8943,284,1535,0,147,2079,2595,2,1069683484,1,121,'',0),(8942,284,2079,0,146,2594,1535,2,1069683484,1,121,'',0),(8941,284,2594,0,145,2262,2079,2,1069683484,1,121,'',0),(8940,284,2262,0,144,2364,2594,2,1069683484,1,121,'',0),(8939,284,2364,0,143,2593,2262,2,1069683484,1,121,'',0),(8938,284,2593,0,142,2037,2364,2,1069683484,1,121,'',0),(8937,284,2037,0,141,2548,2593,2,1069683484,1,121,'',0),(8936,284,2548,0,140,2592,2037,2,1069683484,1,121,'',0),(8935,284,2592,0,139,2591,2548,2,1069683484,1,121,'',0),(8934,284,2591,0,138,2590,2592,2,1069683484,1,121,'',0),(8933,284,2590,0,137,2547,2591,2,1069683484,1,121,'',0),(8932,284,2547,0,136,2589,2590,2,1069683484,1,121,'',0),(8931,284,2589,0,135,2048,2547,2,1069683484,1,121,'',0),(8930,284,2048,0,134,2588,2589,2,1069683484,1,121,'',0),(8929,284,2588,0,133,2587,2048,2,1069683484,1,121,'',0),(8928,284,2587,0,132,2101,2588,2,1069683484,1,121,'',0),(8927,284,2101,0,131,2586,2587,2,1069683484,1,121,'',0),(8926,284,2586,0,130,2442,2101,2,1069683484,1,121,'',0),(8925,284,2442,0,129,1543,2586,2,1069683484,1,121,'',0),(8924,284,1543,0,128,2547,2442,2,1069683484,1,121,'',0),(8923,284,2547,0,127,2101,1543,2,1069683484,1,121,'',0),(8922,284,2101,0,126,2585,2547,2,1069683484,1,121,'',0),(8921,284,2585,0,125,2584,2101,2,1069683484,1,121,'',0),(8920,284,2584,0,124,2181,2585,2,1069683484,1,121,'',0),(8919,284,2181,0,123,2583,2584,2,1069683484,1,121,'',0),(8918,284,2583,0,122,2229,2181,2,1069683484,1,121,'',0),(8917,284,2229,0,121,2249,2583,2,1069683484,1,121,'',0),(8916,284,2249,0,120,2222,2229,2,1069683484,1,121,'',0),(8915,284,2222,0,119,2582,2249,2,1069683484,1,121,'',0),(8914,284,2582,0,118,2581,2222,2,1069683484,1,121,'',0),(8913,284,2581,0,117,2101,2582,2,1069683484,1,121,'',0),(8912,284,2101,0,116,2580,2581,2,1069683484,1,121,'',0),(8911,284,2580,0,115,2229,2101,2,1069683484,1,121,'',0),(8910,284,2229,0,114,2249,2580,2,1069683484,1,121,'',0),(8909,284,2249,0,113,2579,2229,2,1069683484,1,121,'',0),(8795,289,1726,0,4,1535,0,25,1069684665,1,207,'',0),(8794,289,1535,0,3,1725,1726,25,1069684665,1,207,'',0),(8793,289,1725,0,2,2547,1535,25,1069684665,1,207,'',0),(8792,289,2547,0,1,2023,1725,25,1069684665,1,207,'',0),(8791,289,2023,0,0,0,2547,25,1069684665,1,207,'',0),(8908,284,2579,0,112,2314,2249,2,1069683484,1,121,'',0),(8907,284,2314,0,111,2548,2579,2,1069683484,1,121,'',0),(8788,263,2245,0,60,2275,0,10,1069236942,1,141,'',0),(8787,263,2275,0,59,1678,2245,10,1069236942,1,141,'',0),(8786,263,1678,0,58,2014,2275,10,1069236942,1,141,'',0),(8785,263,2014,0,57,2440,1678,10,1069236942,1,141,'',0),(8784,263,2440,0,56,1535,2014,10,1069236942,1,141,'',0),(8783,263,1535,0,55,2544,2440,10,1069236942,1,141,'',0),(8782,263,2544,0,54,2181,1535,10,1069236942,1,141,'',0),(8781,263,2181,0,53,2338,2544,10,1069236942,1,141,'',0),(8780,263,2338,0,52,2300,2181,10,1069236942,1,141,'',0),(8779,263,2300,0,51,2335,2338,10,1069236942,1,141,'',0),(8778,263,2335,0,50,2048,2300,10,1069236942,1,141,'',0),(8777,263,2048,0,49,2543,2335,10,1069236942,1,141,'',0),(8776,263,2543,0,48,2302,2048,10,1069236942,1,141,'',0),(8775,263,2302,0,47,2334,2543,10,1069236942,1,141,'',0),(8774,263,2334,0,46,1725,2302,10,1069236942,1,141,'',0),(8773,263,1725,0,45,2440,2334,10,1069236942,1,141,'',0),(8772,263,2440,0,44,2025,1725,10,1069236942,1,141,'',0),(8771,263,2025,0,43,2541,2440,10,1069236942,1,141,'',0),(8770,263,2541,0,42,2538,2025,10,1069236942,1,141,'',0),(8769,263,2538,0,41,2542,2541,10,1069236942,1,141,'',0),(8768,263,2542,0,40,33,2538,10,1069236942,1,141,'',0),(8767,263,33,0,39,2541,2542,10,1069236942,1,141,'',0),(8766,263,2541,0,38,2540,33,10,1069236942,1,141,'',0),(8765,263,2540,0,37,2539,2541,10,1069236942,1,141,'',0),(8764,263,2539,0,36,2538,2540,10,1069236942,1,141,'',0),(8763,263,2538,0,35,2537,2539,10,1069236942,1,141,'',0),(8762,263,2537,0,34,2535,2538,10,1069236942,1,141,'',0),(8761,263,2535,0,33,2536,2537,10,1069236942,1,141,'',0),(8760,263,2536,0,32,2535,2535,10,1069236942,1,141,'',0),(8759,263,2535,0,31,2534,2536,10,1069236942,1,141,'',0),(8758,263,2534,0,30,1725,2535,10,1069236942,1,141,'',0),(8757,263,1725,0,29,2248,2534,10,1069236942,1,141,'',0),(8756,263,2248,0,28,2230,1725,10,1069236942,1,141,'',0),(8755,263,2230,0,27,2181,2248,10,1069236942,1,141,'',0),(8754,263,2181,0,26,2533,2230,10,1069236942,1,141,'',0),(8753,263,2533,0,25,2532,2181,10,1069236942,1,141,'',0),(8752,263,2532,0,24,2246,2533,10,1069236942,1,141,'',0),(8751,263,2246,0,23,2364,2532,10,1069236942,1,141,'',0),(8750,263,2364,0,22,2531,2246,10,1069236942,1,141,'',0),(8749,263,2531,0,21,2079,2364,10,1069236942,1,141,'',0),(8748,263,2079,0,20,2530,2531,10,1069236942,1,141,'',0),(8747,263,2530,0,19,2181,2079,10,1069236942,1,141,'',0),(8746,263,2181,0,18,2180,2530,10,1069236942,1,141,'',0),(8745,263,2180,0,17,2014,2181,10,1069236942,1,141,'',0),(8727,161,2479,0,167,2501,0,10,1068047603,1,141,'',0),(8726,161,2501,0,166,2488,2479,10,1068047603,1,141,'',0),(8725,161,2488,0,165,2467,2501,10,1068047603,1,141,'',0),(8724,161,2467,0,164,2526,2488,10,1068047603,1,141,'',0),(8723,161,2526,0,163,2525,2467,10,1068047603,1,141,'',0),(8722,161,2525,0,162,2524,2526,10,1068047603,1,141,'',0),(8721,161,2524,0,161,2523,2525,10,1068047603,1,141,'',0),(8720,161,2523,0,160,2522,2524,10,1068047603,1,141,'',0),(8719,161,2522,0,159,2475,2523,10,1068047603,1,141,'',0),(8718,161,2475,0,158,2464,2522,10,1068047603,1,141,'',0),(8717,161,2464,0,157,2521,2475,10,1068047603,1,141,'',0),(8716,161,2521,0,156,2520,2464,10,1068047603,1,141,'',0),(8715,161,2520,0,155,2519,2521,10,1068047603,1,141,'',0),(8714,161,2519,0,154,2459,2520,10,1068047603,1,141,'',0),(8713,161,2459,0,153,2518,2519,10,1068047603,1,141,'',0),(8712,161,2518,0,152,2517,2459,10,1068047603,1,141,'',0),(8711,161,2517,0,151,2516,2518,10,1068047603,1,141,'',0),(8710,161,2516,0,150,2515,2517,10,1068047603,1,141,'',0),(8709,161,2515,0,149,2479,2516,10,1068047603,1,141,'',0),(8708,161,2479,0,148,2514,2515,10,1068047603,1,141,'',0),(8707,161,2514,0,147,2513,2479,10,1068047603,1,141,'',0),(8706,161,2513,0,146,2512,2514,10,1068047603,1,141,'',0),(8705,161,2512,0,145,2472,2513,10,1068047603,1,141,'',0),(8704,161,2472,0,144,2463,2512,10,1068047603,1,141,'',0),(8703,161,2463,0,143,2475,2472,10,1068047603,1,141,'',0),(8702,161,2475,0,142,2479,2463,10,1068047603,1,141,'',0),(8701,161,2479,0,141,2511,2475,10,1068047603,1,141,'',0),(8700,161,2511,0,140,2491,2479,10,1068047603,1,141,'',0),(8699,161,2491,0,139,2459,2511,10,1068047603,1,141,'',0),(8698,161,2459,0,138,2510,2491,10,1068047603,1,141,'',0),(8697,161,2510,0,137,2501,2459,10,1068047603,1,141,'',0),(8696,161,2501,0,136,2509,2510,10,1068047603,1,141,'',0),(8695,161,2509,0,135,2508,2501,10,1068047603,1,141,'',0),(8694,161,2508,0,134,2507,2509,10,1068047603,1,141,'',0),(8693,161,2507,0,133,2459,2508,10,1068047603,1,141,'',0),(8692,161,2459,0,132,2504,2507,10,1068047603,1,141,'',0),(8691,161,2504,0,131,2506,2459,10,1068047603,1,141,'',0),(8690,161,2506,0,130,2101,2504,10,1068047603,1,141,'',0),(8689,161,2101,0,129,2466,2506,10,1068047603,1,141,'',0),(8688,161,2466,0,128,2481,2101,10,1068047603,1,141,'',0),(8687,161,2481,0,127,2505,2466,10,1068047603,1,141,'',0),(8686,161,2505,0,126,2475,2481,10,1068047603,1,141,'',0),(8685,161,2475,0,125,2504,2505,10,1068047603,1,141,'',0),(8684,161,2504,0,124,2456,2475,10,1068047603,1,141,'',0),(8683,161,2456,0,123,2503,2504,10,1068047603,1,141,'',0),(8682,161,2503,0,122,2502,2456,10,1068047603,1,141,'',0),(8681,161,2502,0,121,2501,2503,10,1068047603,1,141,'',0),(8680,161,2501,0,120,2500,2502,10,1068047603,1,141,'',0),(8679,161,2500,0,119,2499,2501,10,1068047603,1,141,'',0),(8678,161,2499,0,118,2498,2500,10,1068047603,1,141,'',0),(8677,161,2498,0,117,2497,2499,10,1068047603,1,141,'',0),(8676,161,2497,0,116,2471,2498,10,1068047603,1,141,'',0),(8675,161,2471,0,115,2489,2497,10,1068047603,1,141,'',0),(8674,161,2489,0,114,2496,2471,10,1068047603,1,141,'',0),(8673,161,2496,0,113,2495,2489,10,1068047603,1,141,'',0),(8672,161,2495,0,112,2494,2496,10,1068047603,1,141,'',0),(3764,219,1522,0,80,1509,1523,26,1068716920,13,212,'',0),(3763,219,1509,0,79,1525,1522,26,1068716920,13,212,'',0),(3762,219,1525,0,78,1503,1509,26,1068716920,13,212,'',0),(3761,219,1503,0,77,1524,1525,26,1068716920,13,212,'',0),(3760,219,1524,0,76,1523,1503,26,1068716920,13,212,'',0),(3757,219,1509,0,73,1525,1522,26,1068716920,13,212,'',0),(3758,219,1522,0,74,1509,1523,26,1068716920,13,212,'',0),(3759,219,1523,0,75,1522,1524,26,1068716920,13,212,'',0),(3756,219,1525,0,72,1503,1509,26,1068716920,13,212,'',0),(3755,219,1503,0,71,1524,1525,26,1068716920,13,212,'',0),(7269,45,33,0,1,32,34,14,1066388816,11,152,'',0),(7264,115,2060,0,2,7,0,14,1066991725,11,155,'',0),(7263,115,7,0,1,2060,2060,14,1066991725,11,155,'',0),(7262,115,2060,0,0,0,7,14,1066991725,11,152,'',0),(7277,116,2068,0,3,25,0,14,1066992054,11,155,'',0),(7276,116,25,0,2,2067,2068,14,1066992054,11,155,'',0),(7275,116,2067,0,1,2066,25,14,1066992054,11,152,'',0),(7274,116,2066,0,0,0,2067,14,1066992054,11,152,'',0),(7268,45,32,0,0,0,33,14,1066388816,11,152,'',0),(9709,290,2873,0,281,2609,2230,2,1069685704,1,121,'',0),(9708,290,2609,0,280,2872,2873,2,1069685704,1,121,'',0),(6510,259,2045,0,0,0,0,1,1069146733,1,4,'',0),(6509,258,2044,0,0,0,0,1,1069146661,1,4,'',0),(6315,257,2032,0,0,0,0,1,1069145751,1,4,'',0),(9707,290,2872,0,279,2629,2609,2,1069685704,1,121,'',0),(3068,14,1362,0,5,1316,0,4,1033920830,2,199,'',0),(3067,14,1316,0,4,1361,1362,4,1033920830,2,198,'',0),(9706,290,2629,0,278,2607,2872,2,1069685704,1,121,'',0),(9705,290,2607,0,277,2871,2629,2,1069685704,1,121,'',0),(9704,290,2871,0,276,2222,2607,2,1069685704,1,121,'',0),(9703,290,2222,0,275,2870,2871,2,1069685704,1,121,'',0),(9702,290,2870,0,274,2869,2222,2,1069685704,1,121,'',0),(9701,290,2869,0,273,2868,2870,2,1069685704,1,121,'',0),(9700,290,2868,0,272,2867,2869,2,1069685704,1,121,'',0),(9699,290,2867,0,271,2847,2868,2,1069685704,1,121,'',0),(9698,290,2847,0,270,2228,2867,2,1069685704,1,121,'',0),(3777,220,1531,0,8,1509,1499,26,1068716967,13,212,'',0),(3776,220,1509,0,7,1526,1531,26,1068716967,13,212,'',0),(3775,220,1526,0,6,1530,1509,26,1068716967,13,212,'',0),(3774,220,1530,0,5,1529,1526,26,1068716967,13,212,'',0),(3771,220,1515,0,2,1514,1528,26,1068716967,13,211,'',0),(3772,220,1528,0,3,1515,1529,26,1068716967,13,211,'',0),(3773,220,1529,0,4,1528,1530,26,1068716967,13,212,'',0),(9697,290,2228,0,269,2866,2847,2,1069685704,1,121,'',0),(9696,290,2866,0,268,2570,2228,2,1069685704,1,121,'',0),(9695,290,2570,0,267,2831,2866,2,1069685704,1,121,'',0),(9694,290,2831,0,266,1965,2570,2,1069685704,1,121,'',0),(9693,290,1965,0,265,2530,2831,2,1069685704,1,121,'',0),(9692,290,2530,0,264,2865,1965,2,1069685704,1,121,'',0),(8671,161,2494,0,111,2493,2495,10,1068047603,1,141,'',0),(8670,161,2493,0,110,2465,2494,10,1068047603,1,141,'',0),(8669,161,2465,0,109,2492,2493,10,1068047603,1,141,'',0),(8668,161,2492,0,108,2454,2465,10,1068047603,1,141,'',0),(8667,161,2454,0,107,2491,2492,10,1068047603,1,141,'',0),(8666,161,2491,0,106,2490,2454,10,1068047603,1,141,'',0),(8665,161,2490,0,105,2489,2491,10,1068047603,1,141,'',0),(8664,161,2489,0,104,2477,2490,10,1068047603,1,141,'',0),(8663,161,2477,0,103,2488,2489,10,1068047603,1,141,'',0),(8662,161,2488,0,102,2487,2477,10,1068047603,1,141,'',0),(8661,161,2487,0,101,2479,2488,10,1068047603,1,141,'',0),(8660,161,2479,0,100,2486,2487,10,1068047603,1,141,'',0),(8659,161,2486,0,99,2485,2479,10,1068047603,1,141,'',0),(8658,161,2485,0,98,2484,2486,10,1068047603,1,141,'',0),(8657,161,2484,0,97,2483,2485,10,1068047603,1,141,'',0),(8656,161,2483,0,96,2482,2484,10,1068047603,1,141,'',0),(8655,161,2482,0,95,2079,2483,10,1068047603,1,141,'',0),(8654,161,2079,0,94,2481,2482,10,1068047603,1,141,'',0),(8653,161,2481,0,93,2480,2079,10,1068047603,1,141,'',0),(8652,161,2480,0,92,2479,2481,10,1068047603,1,141,'',0),(8651,161,2479,0,91,2471,2480,10,1068047603,1,141,'',0),(8650,161,2471,0,90,2478,2479,10,1068047603,1,141,'',0),(8649,161,2478,0,89,2477,2471,10,1068047603,1,141,'',0),(8648,161,2477,0,88,2477,2478,10,1068047603,1,141,'',0),(8647,161,2477,0,87,2476,2477,10,1068047603,1,141,'',0),(8646,161,2476,0,86,2475,2477,10,1068047603,1,141,'',0),(8645,161,2475,0,85,2474,2476,10,1068047603,1,141,'',0),(8644,161,2474,0,84,2473,2475,10,1068047603,1,141,'',0),(8643,161,2473,0,83,2472,2474,10,1068047603,1,141,'',0),(8642,161,2472,0,82,2471,2473,10,1068047603,1,141,'',0),(8641,161,2471,0,81,2079,2472,10,1068047603,1,141,'',0),(8640,161,2079,0,80,2470,2471,10,1068047603,1,141,'',0),(8639,161,2470,0,79,2469,2079,10,1068047603,1,141,'',0),(8638,161,2469,0,78,2468,2470,10,1068047603,1,141,'',0),(8637,161,2468,0,77,2456,2469,10,1068047603,1,141,'',0),(8636,161,2456,0,76,2467,2468,10,1068047603,1,141,'',0),(8635,161,2467,0,75,2466,2456,10,1068047603,1,141,'',0),(8634,161,2466,0,74,2465,2467,10,1068047603,1,141,'',0),(8633,161,2465,0,73,2464,2466,10,1068047603,1,141,'',0),(8632,161,2464,0,72,2463,2465,10,1068047603,1,141,'',0),(8631,161,2463,0,71,2462,2464,10,1068047603,1,141,'',0),(8630,161,2462,0,70,2461,2463,10,1068047603,1,141,'',0),(8629,161,2461,0,69,2460,2462,10,1068047603,1,141,'',0),(8628,161,2460,0,68,2459,2461,10,1068047603,1,141,'',0),(8627,161,2459,0,67,2458,2460,10,1068047603,1,141,'',0),(8626,161,2458,0,66,2457,2459,10,1068047603,1,141,'',0),(8625,161,2457,0,65,2103,2458,10,1068047603,1,141,'',0),(8624,161,2103,0,64,2456,2457,10,1068047603,1,141,'',0),(8623,161,2456,0,63,2455,2103,10,1068047603,1,141,'',0),(8622,161,2455,0,62,2454,2456,10,1068047603,1,141,'',0),(8621,161,2454,0,61,2453,2455,10,1068047603,1,141,'',0),(8620,161,2453,0,60,2452,2454,10,1068047603,1,141,'',0),(8619,161,2452,0,59,2079,2453,10,1068047603,1,141,'',0),(8618,161,2079,0,58,2451,2452,10,1068047603,1,141,'',0),(8617,161,2451,0,57,2246,2079,10,1068047603,1,141,'',0),(8616,161,2246,0,56,2450,2451,10,1068047603,1,141,'',0),(8615,161,2450,0,55,2229,2246,10,1068047603,1,141,'',0),(8614,161,2229,0,54,2228,2450,10,1068047603,1,141,'',0),(8613,161,2228,0,53,2233,2229,10,1068047603,1,141,'',0),(8612,161,2233,0,52,1535,2228,10,1068047603,1,141,'',0),(8611,161,1535,0,51,2079,2233,10,1068047603,1,141,'',0),(8610,161,2079,0,50,2014,1535,10,1068047603,1,141,'',0),(8609,161,2014,0,49,2339,2079,10,1068047603,1,141,'',0),(8608,161,2339,0,48,2449,2014,10,1068047603,1,141,'',0),(8607,161,2449,0,47,2069,2339,10,1068047603,1,141,'',0),(8606,161,2069,0,46,2179,2449,10,1068047603,1,141,'',0),(8605,161,2179,0,45,2448,2069,10,1068047603,1,141,'',0),(8604,161,2448,0,44,2183,2179,10,1068047603,1,141,'',0),(8603,161,2183,0,43,2181,2448,10,1068047603,1,141,'',0),(8602,161,2181,0,42,2323,2183,10,1068047603,1,141,'',0),(8601,161,2323,0,41,2037,2181,10,1068047603,1,141,'',0),(8600,161,2037,0,40,2439,2323,10,1068047603,1,141,'',0),(8599,161,2439,0,39,2025,2037,10,1068047603,1,141,'',0),(8598,161,2025,0,38,2025,2439,10,1068047603,1,141,'',0),(8597,161,2025,0,37,2436,2025,10,1068047603,1,141,'',0),(8596,161,2436,0,36,2245,2025,10,1068047603,1,141,'',0),(8595,161,2245,0,35,2447,2436,10,1068047603,1,141,'',0),(8594,161,2447,0,34,2178,2245,10,1068047603,1,141,'',0),(8593,161,2178,0,33,2436,2447,10,1068047603,1,141,'',0),(8592,161,2436,0,32,2179,2178,10,1068047603,1,141,'',0),(8591,161,2179,0,31,2446,2436,10,1068047603,1,141,'',0),(8590,161,2446,0,30,1537,2179,10,1068047603,1,141,'',0),(8589,161,1537,0,29,2445,2446,10,1068047603,1,141,'',0),(8588,161,2445,0,28,2013,1537,10,1068047603,1,141,'',0),(8587,161,2013,0,27,2444,2445,10,1068047603,1,141,'',0),(8586,161,2444,0,26,2179,2013,10,1068047603,1,141,'',0),(8585,161,2179,0,25,2443,2444,10,1068047603,1,141,'',0),(8584,161,2443,0,24,2184,2179,10,1068047603,1,141,'',0),(8583,161,2184,0,23,2334,2443,10,1068047603,1,141,'',0),(8582,161,2334,0,22,2442,2184,10,1068047603,1,141,'',0),(8581,161,2442,0,21,2246,2334,10,1068047603,1,141,'',0),(8580,161,2246,0,20,2254,2442,10,1068047603,1,141,'',0),(8579,161,2254,0,19,1725,2246,10,1068047603,1,141,'',0),(8578,161,1725,0,18,1965,2254,10,1068047603,1,141,'',0),(8577,161,1965,0,17,2441,1725,10,1068047603,1,141,'',0),(8576,161,2441,0,16,33,1965,10,1068047603,1,141,'',0),(8575,161,33,0,15,2069,2441,10,1068047603,1,141,'',0),(8574,161,2069,0,14,1725,33,10,1068047603,1,141,'',0),(8573,161,1725,0,13,2440,2069,10,1068047603,1,141,'',0),(8572,161,2440,0,12,2327,1725,10,1068047603,1,141,'',0),(8571,161,2327,0,11,2023,2440,10,1068047603,1,141,'',0),(8570,161,2023,0,10,2289,2327,10,1068047603,1,141,'',0),(8569,161,2289,0,9,2335,2023,10,1068047603,1,141,'',0),(8568,161,2335,0,8,2288,2289,10,1068047603,1,141,'',0),(8567,161,2288,0,7,2246,2335,10,1068047603,1,141,'',0),(8566,161,2246,0,6,2344,2288,10,1068047603,1,141,'',0),(8565,161,2344,0,5,1725,2246,10,1068047603,1,141,'',0),(8564,161,1725,0,4,2025,2344,10,1068047603,1,141,'',0),(8563,161,2025,0,3,2439,1725,10,1068047603,1,141,'',0),(8562,161,2439,0,2,2025,2025,10,1068047603,1,140,'',0),(8560,161,2069,0,0,0,2025,10,1068047603,1,140,'',0),(8561,161,2025,0,1,2069,2439,10,1068047603,1,140,'',0),(9691,290,2865,0,263,2864,2530,2,1069685704,1,121,'',0),(9690,290,2864,0,262,2827,2865,2,1069685704,1,121,'',0),(9689,290,2827,0,261,2335,2864,2,1069685704,1,121,'',0),(9688,290,2335,0,260,2773,2827,2,1069685704,1,121,'',0),(9687,290,2773,0,259,2863,2335,2,1069685704,1,121,'',0),(9686,290,2863,0,258,2222,2773,2,1069685704,1,121,'',0),(9685,290,2222,0,257,2283,2863,2,1069685704,1,121,'',0),(9684,290,2283,0,256,2862,2222,2,1069685704,1,121,'',0),(9683,290,2862,0,255,33,2283,2,1069685704,1,121,'',0),(9682,290,33,0,254,2611,2862,2,1069685704,1,121,'',0),(9681,290,2611,0,253,1537,33,2,1069685704,1,121,'',0),(9680,290,1537,0,252,2838,2611,2,1069685704,1,121,'',0),(9679,290,2838,0,251,1535,1537,2,1069685704,1,121,'',0),(9678,290,1535,0,250,2069,2838,2,1069685704,1,121,'',0),(9677,290,2069,0,249,2861,1535,2,1069685704,1,121,'',0),(9676,290,2861,0,248,2860,2069,2,1069685704,1,121,'',0),(9675,290,2860,0,247,2228,2861,2,1069685704,1,121,'',0),(9674,290,2228,0,246,2859,2860,2,1069685704,1,121,'',0),(9673,290,2859,0,245,2227,2228,2,1069685704,1,121,'',0),(9672,290,2227,0,244,1535,2859,2,1069685704,1,121,'',0),(9671,290,1535,0,243,1537,2227,2,1069685704,1,121,'',0),(9670,290,1537,0,242,2350,1535,2,1069685704,1,121,'',0),(9669,290,2350,0,241,1535,1537,2,1069685704,1,121,'',0),(9668,290,1535,0,240,2858,2350,2,1069685704,1,121,'',0),(9667,290,2858,0,239,2850,1535,2,1069685704,1,121,'',0),(9666,290,2850,0,238,2849,2858,2,1069685704,1,121,'',0),(9665,290,2849,0,237,2229,2850,2,1069685704,1,121,'',0),(9664,290,2229,0,236,2335,2849,2,1069685704,1,121,'',0),(3770,220,1514,0,1,1527,1515,26,1068716967,13,210,'',0),(3769,220,1527,0,0,0,1514,26,1068716967,13,209,'',0),(3768,219,1526,0,84,1503,0,26,1068716920,13,212,'',0),(3765,219,1523,0,81,1522,1524,26,1068716920,13,212,'',0),(3766,219,1524,0,82,1523,1503,26,1068716920,13,212,'',0),(3767,219,1503,0,83,1524,1526,26,1068716920,13,212,'',0),(9663,290,2335,0,235,2841,2229,2,1069685704,1,121,'',0),(9662,290,2841,0,234,1519,2335,2,1069685704,1,121,'',0),(9661,290,1519,0,233,2300,2841,2,1069685704,1,121,'',0),(9660,290,2300,0,232,2857,1519,2,1069685704,1,121,'',0),(9659,290,2857,0,231,2628,2300,2,1069685704,1,121,'',0),(9658,290,2628,0,230,2228,2857,2,1069685704,1,121,'',0),(9657,290,2228,0,229,2856,2628,2,1069685704,1,121,'',0),(9656,290,2856,0,228,2819,2228,2,1069685704,1,121,'',0),(9655,290,2819,0,227,2855,2856,2,1069685704,1,121,'',0),(9654,290,2855,0,226,2181,2819,2,1069685704,1,121,'',0),(9653,290,2181,0,225,33,2855,2,1069685704,1,121,'',0),(9652,290,33,0,224,2580,2181,2,1069685704,1,121,'',0),(9651,290,2580,0,223,2181,33,2,1069685704,1,121,'',0),(9650,290,2181,0,222,2580,2580,2,1069685704,1,121,'',0),(9649,290,2580,0,221,2854,2181,2,1069685704,1,121,'',0),(9648,290,2854,0,220,2181,2580,2,1069685704,1,121,'',0),(3066,14,1361,0,3,1360,1316,4,1033920830,2,198,'',0),(3065,14,1360,0,2,1359,1361,4,1033920830,2,197,'',0),(3064,14,1359,0,1,1358,1360,4,1033920830,2,9,'',0),(3063,14,1358,0,0,0,1359,4,1033920830,2,8,'',0),(5932,256,2006,0,0,0,0,1,1069145327,1,4,'',0),(5931,255,2005,0,0,0,0,1,1069145298,1,4,'',0),(9647,290,2181,0,219,2853,2854,2,1069685704,1,121,'',0),(9646,290,2853,0,218,33,2181,2,1069685704,1,121,'',0),(9645,290,33,0,217,2852,2853,2,1069685704,1,121,'',0),(9644,290,2852,0,216,2851,33,2,1069685704,1,121,'',0),(9643,290,2851,0,215,1725,2852,2,1069685704,1,121,'',0),(9642,290,1725,0,214,1965,2851,2,1069685704,1,121,'',0),(9641,290,1965,0,213,2850,1725,2,1069685704,1,121,'',0),(4889,1,1678,0,0,0,0,1,1033917596,1,4,'',0),(7261,249,1499,0,225,2059,0,2,1069071380,1,121,'',0),(3778,220,1499,0,9,1531,0,26,1068716967,13,212,'',0),(7260,249,2059,0,224,2058,1499,2,1069071380,1,121,'',0),(7259,249,2058,0,223,2008,2059,2,1069071380,1,121,'',0),(7258,249,2008,0,222,1499,2058,2,1069071380,1,121,'',0),(7257,249,1499,0,221,2059,2008,2,1069071380,1,121,'',0),(7256,249,2059,0,220,2058,1499,2,1069071380,1,121,'',0),(7255,249,2058,0,219,2008,2059,2,1069071380,1,121,'',0),(7254,249,2008,0,218,1499,2058,2,1069071380,1,121,'',0),(7253,249,1499,0,217,2059,2008,2,1069071380,1,121,'',0),(7252,249,2059,0,216,2058,1499,2,1069071380,1,121,'',0),(7251,249,2058,0,215,2008,2059,2,1069071380,1,121,'',0),(7250,249,2008,0,214,1499,2058,2,1069071380,1,121,'',0),(7249,249,1499,0,213,2059,2008,2,1069071380,1,121,'',0),(7248,249,2059,0,212,2058,1499,2,1069071380,1,121,'',0),(7247,249,2058,0,211,2008,2059,2,1069071380,1,121,'',0),(9640,290,2850,0,212,2849,1965,2,1069685704,1,121,'',0),(9639,290,2849,0,211,2841,2850,2,1069685704,1,121,'',0),(9638,290,2841,0,210,1535,2849,2,1069685704,1,121,'',0),(9637,290,1535,0,209,2300,2841,2,1069685704,1,121,'',0),(9636,290,2300,0,208,2848,1535,2,1069685704,1,121,'',0),(9635,290,2848,0,207,2847,2300,2,1069685704,1,121,'',0),(9634,290,2847,0,206,2228,2848,2,1069685704,1,121,'',0),(9633,290,2228,0,205,2841,2847,2,1069685704,1,121,'',0),(9632,290,2841,0,204,1537,2228,2,1069685704,1,121,'',0),(9631,290,1537,0,203,2846,2841,2,1069685704,1,121,'',0),(9630,290,2846,0,202,1501,1537,2,1069685704,1,121,'',0),(9629,290,1501,0,201,2845,2846,2,1069685704,1,121,'',0),(9628,290,2845,0,200,2844,1501,2,1069685704,1,121,'',0),(9627,290,2844,0,199,2181,2845,2,1069685704,1,121,'',0),(9626,290,2181,0,198,2585,2844,2,1069685704,1,121,'',0),(9625,290,2585,0,197,2596,2181,2,1069685704,1,121,'',0),(9624,290,2596,0,196,2360,2585,2,1069685704,1,121,'',0),(9623,290,2360,0,195,2805,2596,2,1069685704,1,121,'',0),(9622,290,2805,0,194,2843,2360,2,1069685704,1,121,'',0),(9621,290,2843,0,193,2842,2805,2,1069685704,1,121,'',0),(9620,290,2842,0,192,2364,2843,2,1069685704,1,121,'',0),(9619,290,2364,0,191,2841,2842,2,1069685704,1,121,'',0),(9618,290,2841,0,190,2611,2364,2,1069685704,1,121,'',0),(9617,290,2611,0,189,33,2841,2,1069685704,1,121,'',0),(9616,290,33,0,188,2840,2611,2,1069685704,1,121,'',0),(9615,290,2840,0,187,2101,33,2,1069685704,1,121,'',0),(9614,290,2101,0,186,2839,2840,2,1069685704,1,121,'',0),(9613,290,2839,0,185,1537,2101,2,1069685704,1,121,'',0),(9612,290,1537,0,184,2838,2839,2,1069685704,1,121,'',0),(9611,290,2838,0,183,1535,1537,2,1069685704,1,121,'',0),(9610,290,1535,0,182,2837,2838,2,1069685704,1,121,'',0),(9609,290,2837,0,181,2181,1535,2,1069685704,1,121,'',0),(9608,290,2181,0,180,2256,2837,2,1069685704,1,121,'',0),(9607,290,2256,0,179,2236,2181,2,1069685704,1,121,'',0),(9606,290,2236,0,178,2442,2256,2,1069685704,1,121,'',0),(9605,290,2442,0,177,2808,2236,2,1069685704,1,121,'',0),(9604,290,2808,0,176,2809,2442,2,1069685704,1,121,'',0),(9603,290,2809,0,175,2611,2808,2,1069685704,1,121,'',0),(9602,290,2611,0,174,2231,2809,2,1069685704,1,121,'',0),(9601,290,2231,0,173,2836,2611,2,1069685704,1,121,'',0),(9600,290,2836,0,172,2180,2231,2,1069685704,1,121,'',0),(9599,290,2180,0,171,2038,2836,2,1069685704,1,121,'',0),(9598,290,2038,0,170,2835,2180,2,1069685704,1,121,'',0),(9597,290,2835,0,169,2296,2038,2,1069685704,1,121,'',0),(9596,290,2296,0,168,2570,2835,2,1069685704,1,121,'',0),(9595,290,2570,0,167,2834,2296,2,1069685704,1,121,'',0),(9594,290,2834,0,166,2833,2570,2,1069685704,1,121,'',0),(9593,290,2833,0,165,2832,2834,2,1069685704,1,121,'',0),(9592,290,2832,0,164,2296,2833,2,1069685704,1,121,'',0),(9591,290,2296,0,163,2831,2832,2,1069685704,1,121,'',0),(9590,290,2831,0,162,2830,2296,2,1069685704,1,121,'',0),(9589,290,2830,0,161,2829,2831,2,1069685704,1,121,'',0),(9588,290,2829,0,160,33,2830,2,1069685704,1,121,'',0),(9587,290,33,0,159,2283,2829,2,1069685704,1,121,'',0),(9586,290,2283,0,158,2809,33,2,1069685704,1,121,'',0),(9585,290,2809,0,157,2611,2283,2,1069685704,1,121,'',0),(9584,290,2611,0,156,2364,2809,2,1069685704,1,121,'',0),(9583,290,2364,0,155,2828,2611,2,1069685704,1,121,'',0),(9582,290,2828,0,154,2827,2364,2,1069685704,1,121,'',0),(9581,290,2827,0,153,2360,2828,2,1069685704,1,121,'',0),(9580,290,2360,0,152,2335,2827,2,1069685704,1,121,'',0),(9579,290,2335,0,151,2773,2360,2,1069685704,1,121,'',0),(9578,290,2773,0,150,2826,2335,2,1069685704,1,121,'',0),(9577,290,2826,0,149,2825,2773,2,1069685704,1,121,'',0),(9576,290,2825,0,148,1537,2826,2,1069685704,1,121,'',0),(9575,290,1537,0,147,2824,2825,2,1069685704,1,121,'',0),(9574,290,2824,0,146,2823,1537,2,1069685704,1,121,'',0),(9573,290,2823,0,145,1535,2824,2,1069685704,1,121,'',0),(9572,290,1535,0,144,2822,2823,2,1069685704,1,121,'',0),(9571,290,2822,0,143,2571,1535,2,1069685704,1,121,'',0),(9570,290,2571,0,142,33,2822,2,1069685704,1,121,'',0),(9569,290,33,0,141,2821,2571,2,1069685704,1,121,'',0),(9568,290,2821,0,140,2312,33,2,1069685704,1,121,'',0),(9567,290,2312,0,139,33,2821,2,1069685704,1,121,'',0),(9566,290,33,0,138,2820,2312,2,1069685704,1,121,'',0),(9565,290,2820,0,137,2819,33,2,1069685704,1,121,'',0),(9564,290,2819,0,136,2818,2820,2,1069685704,1,121,'',0),(9563,290,2818,0,135,2360,2819,2,1069685704,1,121,'',0),(9562,290,2360,0,134,2817,2818,2,1069685704,1,121,'',0),(9561,290,2817,0,133,1537,2360,2,1069685704,1,121,'',0),(9560,290,1537,0,132,2317,2817,2,1069685704,1,121,'',0),(9559,290,2317,0,131,2816,1537,2,1069685704,1,121,'',0),(9558,290,2816,0,130,33,2317,2,1069685704,1,121,'',0),(9557,290,33,0,129,2815,2816,2,1069685704,1,121,'',0),(9556,290,2815,0,128,2051,33,2,1069685704,1,121,'',0),(9555,290,2051,0,127,2814,2815,2,1069685704,1,121,'',0),(9554,290,2814,0,126,2360,2051,2,1069685704,1,121,'',0),(9553,290,2360,0,125,2540,2814,2,1069685704,1,121,'',0),(9552,290,2540,0,124,1535,2360,2,1069685704,1,121,'',0),(9551,290,1535,0,123,2813,2540,2,1069685704,1,121,'',0),(9550,290,2813,0,122,2812,1535,2,1069685704,1,121,'',0),(9549,290,2812,0,121,33,2813,2,1069685704,1,121,'',0),(9548,290,33,0,120,2811,2812,2,1069685704,1,121,'',0),(9547,290,2811,0,119,2810,33,2,1069685704,1,121,'',0),(9546,290,2810,0,118,25,2811,2,1069685704,1,121,'',0),(9545,290,25,0,117,2809,2810,2,1069685704,1,121,'',0),(9544,290,2809,0,116,2611,25,2,1069685704,1,121,'',0),(9543,290,2611,0,115,2231,2809,2,1069685704,1,121,'',0),(8342,261,2191,0,55,2013,0,2,1069147950,1,219,'',1),(8341,261,2013,0,54,2103,2191,2,1069147950,1,121,'',0),(8340,261,2103,0,53,2318,2013,2,1069147950,1,121,'',0),(8339,261,2318,0,52,2256,2103,2,1069147950,1,121,'',0),(8338,261,2256,0,51,2318,2318,2,1069147950,1,121,'',0),(8337,261,2318,0,50,2256,2256,2,1069147950,1,121,'',0),(8336,261,2256,0,49,1725,2318,2,1069147950,1,121,'',0),(8335,261,1725,0,48,2025,2256,2,1069147950,1,121,'',0),(8334,261,2025,0,47,1728,1725,2,1069147950,1,121,'',0),(8333,261,1728,0,46,2369,2025,2,1069147950,1,121,'',0),(8332,261,2369,0,45,1519,1728,2,1069147950,1,121,'',0),(8331,261,1519,0,44,2363,2369,2,1069147950,1,121,'',0),(8330,261,2363,0,43,2368,1519,2,1069147950,1,121,'',0),(8329,261,2368,0,42,2367,2363,2,1069147950,1,121,'',0),(8328,261,2367,0,41,1519,2368,2,1069147950,1,121,'',0),(8327,261,1519,0,40,2363,2367,2,1069147950,1,121,'',0),(8326,261,2363,0,39,2366,1519,2,1069147950,1,121,'',0),(8325,261,2366,0,38,2365,2363,2,1069147950,1,121,'',0),(8324,261,2365,0,37,1535,2366,2,1069147950,1,121,'',0),(8323,261,1535,0,36,2364,2365,2,1069147950,1,121,'',0),(8322,261,2364,0,35,1728,1535,2,1069147950,1,121,'',0),(8321,261,1728,0,34,1519,2364,2,1069147950,1,121,'',0),(8320,261,1519,0,33,2363,1728,2,1069147950,1,121,'',0),(8319,261,2363,0,32,2236,1519,2,1069147950,1,121,'',0),(8318,261,2236,0,31,2254,2363,2,1069147950,1,121,'',0),(8317,261,2254,0,30,2229,2236,2,1069147950,1,121,'',0),(8315,261,2362,0,28,2025,2229,2,1069147950,1,121,'',0),(8316,261,2229,0,29,2362,2254,2,1069147950,1,121,'',0),(7961,278,2228,0,4,2227,2229,2,1069680733,1,120,'',0),(7960,278,2227,0,3,2226,2228,2,1069680733,1,120,'',0),(7959,278,2226,0,2,2225,2227,2,1069680733,1,120,'',0),(7958,278,2225,0,1,2045,2226,2,1069680733,1,1,'',0),(7957,278,2045,0,0,0,2225,2,1069680733,1,1,'',0),(7246,249,2008,0,210,1499,2058,2,1069071380,1,121,'',0),(7245,249,1499,0,209,2059,2008,2,1069071380,1,121,'',0),(7244,249,2059,0,208,2058,1499,2,1069071380,1,121,'',0),(7243,249,2058,0,207,2008,2059,2,1069071380,1,121,'',0),(7242,249,2008,0,206,1499,2058,2,1069071380,1,121,'',0),(7241,249,1499,0,205,2059,2008,2,1069071380,1,121,'',0),(7240,249,2059,0,204,2058,1499,2,1069071380,1,121,'',0),(7239,249,2058,0,203,2008,2059,2,1069071380,1,121,'',0),(7238,249,2008,0,202,1499,2058,2,1069071380,1,121,'',0),(7237,249,1499,0,201,2059,2008,2,1069071380,1,121,'',0),(7236,249,2059,0,200,2058,1499,2,1069071380,1,121,'',0),(7235,249,2058,0,199,2008,2059,2,1069071380,1,121,'',0),(4066,233,1140,0,0,0,1514,26,1068718705,13,209,'',0),(4067,233,1514,0,1,1140,1515,26,1068718705,13,210,'',0),(4068,233,1515,0,2,1514,1516,26,1068718705,13,211,'',0),(4069,233,1516,0,3,1515,1680,26,1068718705,13,211,'',0),(4070,233,1680,0,4,1516,1681,26,1068718705,13,212,'',0),(4071,233,1681,0,5,1680,1682,26,1068718705,13,212,'',0),(4072,233,1682,0,6,1681,1683,26,1068718705,13,212,'',0),(4073,233,1683,0,7,1682,1684,26,1068718705,13,212,'',0),(4074,233,1684,0,8,1683,1685,26,1068718705,13,212,'',0),(4075,233,1685,0,9,1684,1686,26,1068718705,13,212,'',0),(4076,233,1686,0,10,1685,1687,26,1068718705,13,212,'',0),(4077,233,1687,0,11,1686,1509,26,1068718705,13,212,'',0),(4078,233,1509,0,12,1687,1509,26,1068718705,13,212,'',0),(4079,233,1509,0,13,1509,1688,26,1068718705,13,212,'',0),(4080,233,1688,0,14,1509,0,26,1068718705,13,212,'',0),(7234,249,2008,0,198,1499,2058,2,1069071380,1,121,'',0),(7233,249,1499,0,197,2059,2008,2,1069071380,1,121,'',0),(7232,249,2059,0,196,2058,1499,2,1069071380,1,121,'',0),(7231,249,2058,0,195,2008,2059,2,1069071380,1,121,'',0),(4087,235,1693,0,0,0,1694,26,1068718760,13,209,'',0),(4088,235,1694,0,1,1693,1695,26,1068718760,13,210,'',0),(4089,235,1695,0,2,1694,1515,26,1068718760,13,211,'',0),(4090,235,1515,0,3,1695,1516,26,1068718760,13,212,'',0),(4091,235,1516,0,4,1515,0,26,1068718760,13,212,'',0),(7230,249,2008,0,194,1499,2058,2,1069071380,1,121,'',0),(7203,249,2058,0,167,2008,2059,2,1069071380,1,121,'',0),(7204,249,2059,0,168,2058,1499,2,1069071380,1,121,'',0),(7205,249,1499,0,169,2059,2008,2,1069071380,1,121,'',0),(7206,249,2008,0,170,1499,2058,2,1069071380,1,121,'',0),(7207,249,2058,0,171,2008,2059,2,1069071380,1,121,'',0),(7208,249,2059,0,172,2058,1499,2,1069071380,1,121,'',0),(7209,249,1499,0,173,2059,2008,2,1069071380,1,121,'',0),(7210,249,2008,0,174,1499,2058,2,1069071380,1,121,'',0),(7211,249,2058,0,175,2008,2059,2,1069071380,1,121,'',0),(7212,249,2059,0,176,2058,1499,2,1069071380,1,121,'',0),(7213,249,1499,0,177,2059,2008,2,1069071380,1,121,'',0),(7214,249,2008,0,178,1499,2058,2,1069071380,1,121,'',0),(7215,249,2058,0,179,2008,2059,2,1069071380,1,121,'',0),(7216,249,2059,0,180,2058,1499,2,1069071380,1,121,'',0),(7217,249,1499,0,181,2059,2008,2,1069071380,1,121,'',0),(7218,249,2008,0,182,1499,2058,2,1069071380,1,121,'',0),(7219,249,2058,0,183,2008,2059,2,1069071380,1,121,'',0),(7220,249,2059,0,184,2058,1499,2,1069071380,1,121,'',0),(7221,249,1499,0,185,2059,2008,2,1069071380,1,121,'',0),(7222,249,2008,0,186,1499,2058,2,1069071380,1,121,'',0),(7223,249,2058,0,187,2008,2059,2,1069071380,1,121,'',0),(7224,249,2059,0,188,2058,1499,2,1069071380,1,121,'',0),(7225,249,1499,0,189,2059,2008,2,1069071380,1,121,'',0),(7226,249,2008,0,190,1499,2058,2,1069071380,1,121,'',0),(7227,249,2058,0,191,2008,2059,2,1069071380,1,121,'',0),(7228,249,2059,0,192,2058,1499,2,1069071380,1,121,'',0),(8314,261,2025,0,27,2361,2362,2,1069147950,1,121,'',0),(8313,261,2361,0,26,2360,2025,2,1069147950,1,121,'',0),(8312,261,2360,0,25,2359,2361,2,1069147950,1,121,'',0),(8311,261,2359,0,24,2317,2360,2,1069147950,1,121,'',0),(8310,261,2317,0,23,2358,2359,2,1069147950,1,121,'',0),(8309,261,2358,0,22,2181,2317,2,1069147950,1,120,'',0),(8308,261,2181,0,21,2357,2358,2,1069147950,1,120,'',0),(8307,261,2357,0,20,2229,2181,2,1069147950,1,120,'',0),(8306,261,2229,0,19,2023,2357,2,1069147950,1,120,'',0),(8305,261,2023,0,18,2255,2229,2,1069147950,1,120,'',0),(8304,261,2255,0,17,2353,2023,2,1069147950,1,120,'',0),(8303,261,2353,0,16,2048,2255,2,1069147950,1,120,'',0),(8302,261,2048,0,15,2356,2353,2,1069147950,1,120,'',0),(8301,261,2356,0,14,2037,2048,2,1069147950,1,120,'',0),(8300,261,2037,0,13,2352,2356,2,1069147950,1,120,'',0),(8299,261,2352,0,12,2289,2037,2,1069147950,1,120,'',0),(8298,261,2289,0,11,2013,2352,2,1069147950,1,120,'',0),(8297,261,2013,0,10,2228,2289,2,1069147950,1,120,'',0),(8296,261,2228,0,9,2222,2013,2,1069147950,1,120,'',0),(8295,261,2222,0,8,2355,2228,2,1069147950,1,120,'',0),(8294,261,2355,0,7,2051,2222,2,1069147950,1,120,'',0),(8293,261,2051,0,6,2354,2355,2,1069147950,1,120,'',0),(8292,261,2354,0,5,2008,2051,2,1069147950,1,120,'',0),(8291,261,2008,0,4,1535,2354,2,1069147950,1,120,'',0),(8290,261,1535,0,3,2353,2008,2,1069147950,1,120,'',0),(8287,261,2352,0,0,0,2048,2,1069147950,1,1,'',0),(8288,261,2048,0,1,2352,2353,2,1069147950,1,1,'',0),(8289,261,2353,0,2,2048,1535,2,1069147950,1,1,'',0),(8250,260,2191,0,70,2328,0,2,1069147811,1,219,'',1),(8249,260,2328,0,69,2340,2191,2,1069147811,1,121,'',0),(8248,260,2340,0,68,2280,2328,2,1069147811,1,121,'',0),(8247,260,2280,0,67,2079,2340,2,1069147811,1,121,'',0),(8246,260,2079,0,66,2014,2280,2,1069147811,1,121,'',0),(8245,260,2014,0,65,2339,2079,2,1069147811,1,121,'',0),(8244,260,2339,0,64,1725,2014,2,1069147811,1,121,'',0),(8243,260,1725,0,63,2023,2339,2,1069147811,1,121,'',0),(8242,260,2023,0,62,2289,1725,2,1069147811,1,121,'',0),(8241,260,2289,0,61,2181,2023,2,1069147811,1,121,'',0),(8240,260,2181,0,60,2338,2289,2,1069147811,1,121,'',0),(8239,260,2338,0,59,2255,2181,2,1069147811,1,121,'',0),(8238,260,2255,0,58,2337,2338,2,1069147811,1,121,'',0),(8237,260,2337,0,57,2336,2255,2,1069147811,1,121,'',0),(8236,260,2336,0,56,2230,2337,2,1069147811,1,121,'',0),(8235,260,2230,0,55,2300,2336,2,1069147811,1,121,'',0),(8234,260,2300,0,54,2335,2230,2,1069147811,1,121,'',0),(8233,260,2335,0,53,1535,2300,2,1069147811,1,121,'',0),(8232,260,1535,0,52,2048,2335,2,1069147811,1,121,'',0),(8231,260,2048,0,51,2070,1535,2,1069147811,1,121,'',0),(8230,260,2070,0,50,2318,2048,2,1069147811,1,121,'',0),(8229,260,2318,0,49,2101,2070,2,1069147811,1,121,'',0),(8228,260,2101,0,48,2236,2318,2,1069147811,1,121,'',0),(8227,260,2236,0,47,2334,2101,2,1069147811,1,121,'',0),(8226,260,2334,0,46,2229,2236,2,1069147811,1,121,'',0),(8225,260,2229,0,45,1965,2334,2,1069147811,1,121,'',0),(8224,260,1965,0,44,2333,2229,2,1069147811,1,121,'',0),(8223,260,2333,0,43,33,1965,2,1069147811,1,121,'',0),(8222,260,33,0,42,2332,2333,2,1069147811,1,121,'',0),(8221,260,2332,0,41,2327,33,2,1069147811,1,121,'',0),(8220,260,2327,0,40,2013,2332,2,1069147811,1,121,'',0),(8219,260,2013,0,39,2181,2327,2,1069147811,1,121,'',0),(8218,260,2181,0,38,2331,2013,2,1069147811,1,121,'',0),(8217,260,2331,0,37,2236,2181,2,1069147811,1,121,'',0),(8216,260,2236,0,36,2229,2331,2,1069147811,1,121,'',0),(8215,260,2229,0,35,2300,2236,2,1069147811,1,121,'',0),(8214,260,2300,0,34,2070,2229,2,1069147811,1,121,'',0),(8213,260,2070,0,33,2101,2300,2,1069147811,1,121,'',0),(8212,260,2101,0,32,1725,2070,2,1069147811,1,121,'',0),(8211,260,1725,0,31,2025,2101,2,1069147811,1,121,'',0),(8210,260,2025,0,30,2330,1725,2,1069147811,1,121,'',0),(8209,260,2330,0,29,2329,2025,2,1069147811,1,120,'',0),(8208,260,2329,0,28,2229,2330,2,1069147811,1,120,'',0),(8207,260,2229,0,27,2246,2329,2,1069147811,1,120,'',0),(8206,260,2246,0,26,33,2229,2,1069147811,1,120,'',0),(8205,260,33,0,25,2328,2246,2,1069147811,1,120,'',0),(8204,260,2328,0,24,2327,33,2,1069147811,1,120,'',0),(8203,260,2327,0,23,2079,2328,2,1069147811,1,120,'',0),(8202,260,2079,0,22,2326,2327,2,1069147811,1,120,'',0),(8201,260,2326,0,21,2023,2079,2,1069147811,1,120,'',0),(8200,260,2023,0,20,2289,2326,2,1069147811,1,120,'',0),(8199,260,2289,0,19,2182,2023,2,1069147811,1,120,'',0),(8198,260,2182,0,18,2288,2289,2,1069147811,1,120,'',0),(8197,260,2288,0,17,2325,2182,2,1069147811,1,120,'',0),(8196,260,2325,0,16,2324,2288,2,1069147811,1,120,'',0),(8195,260,2324,0,15,2013,2325,2,1069147811,1,120,'',0),(8194,260,2013,0,14,2048,2324,2,1069147811,1,120,'',0),(8193,260,2048,0,13,2070,2013,2,1069147811,1,120,'',0),(8192,260,2070,0,12,2033,2048,2,1069147811,1,120,'',0),(8191,260,2033,0,11,2101,2070,2,1069147811,1,120,'',0),(8190,260,2101,0,10,2323,2033,2,1069147811,1,120,'',0),(8189,260,2323,0,9,2272,2101,2,1069147811,1,120,'',0),(8188,260,2272,0,8,2322,2323,2,1069147811,1,120,'',0),(8187,260,2322,0,7,1678,2272,2,1069147811,1,120,'',0),(8186,260,1678,0,6,1535,2322,2,1069147811,1,120,'',0),(8185,260,1535,0,5,1318,1678,2,1069147811,1,120,'',0),(8184,260,1318,0,4,1140,1535,2,1069147811,1,218,'',0),(8183,260,1140,0,3,2190,1318,2,1069147811,1,218,'',0),(8182,260,2190,0,2,2044,1140,2,1069147811,1,1,'',0),(8181,260,2044,0,1,2008,2190,2,1069147811,1,1,'',0),(8180,260,2008,0,0,0,2044,2,1069147811,1,1,'',0),(9542,290,2231,0,114,1725,2611,2,1069685704,1,121,'',0),(9541,290,1725,0,113,1543,2231,2,1069685704,1,121,'',0),(9540,290,1543,0,112,2808,1725,2,1069685704,1,121,'',0),(9539,290,2808,0,111,2807,1543,2,1069685704,1,121,'',0),(9538,290,2807,0,110,2794,2808,2,1069685704,1,121,'',0),(9537,290,2794,0,109,1537,2807,2,1069685704,1,121,'',0),(9536,290,1537,0,108,2806,2794,2,1069685704,1,121,'',0),(9535,290,2806,0,107,2773,1537,2,1069685704,1,121,'',0),(9534,290,2773,0,106,2230,2806,2,1069685704,1,121,'',0),(9533,290,2230,0,105,2803,2773,2,1069685704,1,121,'',0),(9532,290,2803,0,104,2805,2230,2,1069685704,1,121,'',0),(9531,290,2805,0,103,2335,2803,2,1069685704,1,121,'',0),(9530,290,2335,0,102,2317,2805,2,1069685704,1,121,'',0),(9529,290,2317,0,101,2360,2335,2,1069685704,1,121,'',0),(9528,290,2360,0,100,2254,2317,2,1069685704,1,121,'',0),(9527,290,2254,0,99,2300,2360,2,1069685704,1,121,'',0),(9526,290,2300,0,98,2804,2254,2,1069685704,1,121,'',0),(9525,290,2804,0,97,1965,2300,2,1069685704,1,121,'',0),(9524,290,1965,0,96,2803,2804,2,1069685704,1,121,'',0),(9523,290,2803,0,95,2182,1965,2,1069685704,1,121,'',0),(9522,290,2182,0,94,2802,2803,2,1069685704,1,121,'',0),(9521,290,2802,0,93,2230,2182,2,1069685704,1,121,'',0),(9520,290,2230,0,92,2335,2802,2,1069685704,1,121,'',0),(9519,290,2335,0,91,1537,2230,2,1069685704,1,121,'',0),(9518,290,1537,0,90,2801,2335,2,1069685704,1,121,'',0),(9517,290,2801,0,89,2101,1537,2,1069685704,1,121,'',0),(9516,290,2101,0,88,33,2801,2,1069685704,1,121,'',0),(9515,290,33,0,87,2800,2101,2,1069685704,1,121,'',0),(9514,290,2800,0,86,2101,33,2,1069685704,1,121,'',0),(9513,290,2101,0,85,2248,2800,2,1069685704,1,121,'',0),(9512,290,2248,0,84,2292,2101,2,1069685704,1,121,'',0),(9511,290,2292,0,83,2799,2248,2,1069685704,1,121,'',0),(9510,290,2799,0,82,2230,2292,2,1069685704,1,121,'',0),(9509,290,2230,0,81,2228,2799,2,1069685704,1,121,'',0),(9508,290,2228,0,80,2798,2230,2,1069685704,1,121,'',0),(9507,290,2798,0,79,2013,2228,2,1069685704,1,121,'',0),(9506,290,2013,0,78,2797,2798,2,1069685704,1,121,'',0),(9505,290,2797,0,77,2796,2013,2,1069685704,1,121,'',0),(9504,290,2796,0,76,2554,2797,2,1069685704,1,121,'',0),(9503,290,2554,0,75,2360,2796,2,1069685704,1,121,'',0),(9502,290,2360,0,74,2795,2554,2,1069685704,1,121,'',0),(9501,290,2795,0,73,2261,2360,2,1069685704,1,121,'',0),(9500,290,2261,0,72,2794,2795,2,1069685704,1,121,'',0),(9499,290,2794,0,71,2770,2261,2,1069685704,1,121,'',0),(9498,290,2770,0,70,1535,2794,2,1069685704,1,121,'',0),(9497,290,1535,0,69,2051,2770,2,1069685704,1,121,'',0),(9496,290,2051,0,68,2793,1535,2,1069685704,1,121,'',0),(9495,290,2793,0,67,2777,2051,2,1069685704,1,121,'',0),(9494,290,2777,0,66,2101,2793,2,1069685704,1,121,'',0),(9493,290,2101,0,65,2792,2777,2,1069685704,1,121,'',0),(9492,290,2792,0,64,2351,2101,2,1069685704,1,121,'',0),(9491,290,2351,0,63,2791,2792,2,1069685704,1,121,'',0),(9490,290,2791,0,62,1535,2351,2,1069685704,1,121,'',0),(9489,290,1535,0,61,2778,2791,2,1069685704,1,121,'',0),(9488,290,2778,0,60,1535,1535,2,1069685704,1,121,'',0),(9487,290,1535,0,59,2051,2778,2,1069685704,1,121,'',0),(9486,290,2051,0,58,2777,1535,2,1069685704,1,121,'',0),(9485,290,2777,0,57,2776,2051,2,1069685704,1,121,'',0),(9484,290,2776,0,56,2790,2777,2,1069685704,1,121,'',0),(9483,290,2790,0,55,1535,2776,2,1069685704,1,121,'',0),(9482,290,1535,0,54,2181,2790,2,1069685704,1,121,'',0),(9481,290,2181,0,53,2789,1535,2,1069685704,1,121,'',0),(9480,290,2789,0,52,1535,2181,2,1069685704,1,121,'',0),(9479,290,1535,0,51,2051,2789,2,1069685704,1,121,'',0),(9478,290,2051,0,50,2440,1535,2,1069685704,1,121,'',0),(9477,290,2440,0,49,2014,2051,2,1069685704,1,121,'',0),(9476,290,2014,0,48,2360,2440,2,1069685704,1,121,'',0),(9475,290,2360,0,47,2774,2014,2,1069685704,1,121,'',0),(9474,290,2774,0,46,2775,2360,2,1069685704,1,121,'',0),(9473,290,2775,0,45,2788,2774,2,1069685704,1,121,'',0),(9472,290,2788,0,44,2787,2775,2,1069685704,1,121,'',0),(9471,290,2787,0,43,2786,2788,2,1069685704,1,120,'',0),(9470,290,2786,0,42,1535,2787,2,1069685704,1,120,'',0),(9469,290,1535,0,41,2048,2786,2,1069685704,1,120,'',0),(7229,249,1499,0,193,2059,2008,2,1069071380,1,121,'',0),(7202,249,2008,0,166,1499,2058,2,1069071380,1,121,'',0),(7193,249,1499,0,157,2059,2008,2,1069071380,1,121,'',0),(7194,249,2008,0,158,1499,2058,2,1069071380,1,121,'',0),(7195,249,2058,0,159,2008,2059,2,1069071380,1,121,'',0),(7196,249,2059,0,160,2058,1499,2,1069071380,1,121,'',0),(7197,249,1499,0,161,2059,2008,2,1069071380,1,121,'',0),(7198,249,2008,0,162,1499,2058,2,1069071380,1,121,'',0),(7199,249,2058,0,163,2008,2059,2,1069071380,1,121,'',0),(7200,249,2059,0,164,2058,1499,2,1069071380,1,121,'',0),(7201,249,1499,0,165,2059,2008,2,1069071380,1,121,'',0),(9468,290,2048,0,40,2785,1535,2,1069685704,1,120,'',0),(9467,290,2785,0,39,1725,2048,2,1069685704,1,120,'',0),(9466,290,1725,0,38,2770,2785,2,1069685704,1,120,'',0),(9465,290,2770,0,37,2783,1725,2,1069685704,1,120,'',0),(8404,254,2191,0,61,1965,0,2,1069077452,1,219,'',1),(8403,254,1965,0,60,2383,2191,2,1069077452,1,121,'',0),(8402,254,2383,0,59,2260,1965,2,1069077452,1,121,'',0),(8401,254,2260,0,58,1519,2383,2,1069077452,1,121,'',0),(8400,254,1519,0,57,1725,2260,2,1069077452,1,121,'',0),(8399,254,1725,0,56,2300,1519,2,1069077452,1,121,'',0),(8398,254,2300,0,55,2382,1725,2,1069077452,1,121,'',0),(8397,254,2382,0,54,2181,2300,2,1069077452,1,121,'',0),(8396,254,2181,0,53,2230,2382,2,1069077452,1,121,'',0),(8395,254,2230,0,52,2246,2181,2,1069077452,1,121,'',0),(8394,254,2246,0,51,2381,2230,2,1069077452,1,121,'',0),(8393,254,2381,0,50,2101,2246,2,1069077452,1,121,'',0),(8392,254,2101,0,49,2038,2381,2,1069077452,1,121,'',0),(8391,254,2038,0,48,1725,2101,2,1069077452,1,121,'',0),(8390,254,1725,0,47,1965,2038,2,1069077452,1,121,'',0),(8389,254,1965,0,46,2380,1725,2,1069077452,1,121,'',0),(8388,254,2380,0,45,2375,1965,2,1069077452,1,121,'',0),(8387,254,2375,0,44,2025,2380,2,1069077452,1,121,'',0),(8386,254,2025,0,43,2024,2375,2,1069077452,1,121,'',0),(8385,254,2024,0,42,2023,2025,2,1069077452,1,121,'',0),(8384,254,2023,0,41,2379,2024,2,1069077452,1,121,'',0),(8383,254,2379,0,40,2378,2023,2,1069077452,1,121,'',0),(8382,254,2378,0,39,2377,2379,2,1069077452,1,121,'',0),(8381,254,2377,0,38,2376,2378,2,1069077452,1,121,'',0),(8380,254,2376,0,37,2375,2377,2,1069077452,1,121,'',0),(8379,254,2375,0,36,2374,2376,2,1069077452,1,121,'',0),(8378,254,2374,0,35,1558,2375,2,1069077452,1,121,'',0),(8377,254,1558,0,34,2014,2374,2,1069077452,1,121,'',0),(8376,254,2014,0,33,1965,1558,2,1069077452,1,121,'',0),(8375,254,1965,0,32,2373,2014,2,1069077452,1,121,'',0),(8374,254,2373,0,31,33,1965,2,1069077452,1,121,'',0),(8373,254,33,0,30,2372,2373,2,1069077452,1,121,'',0),(8372,254,2372,0,29,1535,33,2,1069077452,1,121,'',0),(8371,254,1535,0,28,2014,2372,2,1069077452,1,121,'',0),(8370,254,2014,0,27,25,1535,2,1069077452,1,121,'',0),(8369,254,25,0,26,1535,2014,2,1069077452,1,121,'',0),(8368,254,1535,0,25,2013,25,2,1069077452,1,121,'',0),(8367,254,2013,0,24,2380,1535,2,1069077452,1,121,'',0),(8366,254,2380,0,23,2375,2013,2,1069077452,1,120,'',0),(8365,254,2375,0,22,2025,2380,2,1069077452,1,120,'',0),(8364,254,2025,0,21,2024,2375,2,1069077452,1,120,'',0),(8363,254,2024,0,20,2023,2025,2,1069077452,1,120,'',0),(8362,254,2023,0,19,2379,2024,2,1069077452,1,120,'',0),(8361,254,2379,0,18,2378,2023,2,1069077452,1,120,'',0),(8360,254,2378,0,17,2377,2379,2,1069077452,1,120,'',0),(8359,254,2377,0,16,2376,2378,2,1069077452,1,120,'',0),(8358,254,2376,0,15,2375,2377,2,1069077452,1,120,'',0),(8357,254,2375,0,14,2374,2376,2,1069077452,1,120,'',0),(8356,254,2374,0,13,1558,2375,2,1069077452,1,120,'',0),(8355,254,1558,0,12,2014,2374,2,1069077452,1,120,'',0),(8354,254,2014,0,11,1965,1558,2,1069077452,1,120,'',0),(8353,254,1965,0,10,2373,2014,2,1069077452,1,120,'',0),(8352,254,2373,0,9,33,1965,2,1069077452,1,120,'',0),(8351,254,33,0,8,2372,2373,2,1069077452,1,120,'',0),(8350,254,2372,0,7,1535,33,2,1069077452,1,120,'',0),(8349,254,1535,0,6,2014,2372,2,1069077452,1,120,'',0),(8348,254,2014,0,5,25,1535,2,1069077452,1,120,'',0),(8347,254,25,0,4,1535,2014,2,1069077452,1,120,'',0),(8346,254,1535,0,3,2013,25,2,1069077452,1,120,'',0),(8344,254,2371,0,1,2370,2013,2,1069077452,1,1,'',0),(8345,254,2013,0,2,2371,1535,2,1069077452,1,120,'',0),(8343,254,2370,0,0,0,2371,2,1069077452,1,1,'',0),(5321,252,1974,0,11,1719,0,26,1069074891,1,212,'',0),(5320,252,1719,0,10,1509,1974,26,1069074891,1,212,'',0),(5319,252,1509,0,9,1509,1719,26,1069074891,1,212,'',0),(5318,252,1509,0,8,1915,1509,26,1069074891,1,212,'',0),(5317,252,1915,0,7,1509,1509,26,1069074891,1,212,'',0),(5316,252,1509,0,6,1516,1915,26,1069074891,1,212,'',0),(5315,252,1516,0,5,1515,1509,26,1069074891,1,211,'',0),(5314,252,1515,0,4,1514,1516,26,1069074891,1,211,'',0),(5313,252,1514,0,3,1140,1515,26,1069074891,1,210,'',0),(5312,252,1140,0,2,1678,1514,26,1069074891,1,209,'',0),(5311,252,1678,0,1,1973,1140,26,1069074891,1,217,'',0),(4430,239,1140,0,0,0,1514,26,1068719374,13,209,'',0),(4431,239,1514,0,1,1140,1529,26,1068719374,13,210,'',0),(4432,239,1529,0,2,1514,1718,26,1068719374,13,211,'',0),(4433,239,1718,0,3,1529,1688,26,1068719374,13,212,'',0),(4434,239,1688,0,4,1718,1719,26,1068719374,13,212,'',0),(4435,239,1719,0,5,1688,0,26,1068719374,13,212,'',0),(4442,240,1724,0,0,0,0,1,1068719643,1,4,'',0),(4443,241,1543,0,0,0,1558,25,1068720802,1,207,'',0),(4444,241,1558,0,1,1543,1725,25,1068720802,1,207,'',0),(4445,241,1725,0,2,1558,1535,25,1068720802,1,207,'',0),(4446,241,1535,0,3,1725,1726,25,1068720802,1,207,'',0),(4447,241,1726,0,4,1535,1537,25,1068720802,1,207,'',0),(4448,241,1537,0,5,1726,1727,25,1068720802,1,207,'',0),(4449,241,1727,0,6,1537,1728,25,1068720802,1,207,'',0),(4450,241,1728,0,7,1727,0,25,1068720802,1,207,'',0),(4451,242,1729,0,0,0,1514,26,1068720915,13,209,'',0),(4452,242,1514,0,1,1729,1730,26,1068720915,13,210,'',0),(4453,242,1730,0,2,1514,1731,26,1068720915,13,211,'',0),(4454,242,1731,0,3,1730,1732,26,1068720915,13,212,'',0),(4455,242,1732,0,4,1731,1732,26,1068720915,13,212,'',0),(4456,242,1732,0,5,1732,1732,26,1068720915,13,212,'',0),(4457,242,1732,0,6,1732,1733,26,1068720915,13,212,'',0),(4458,242,1733,0,7,1732,1734,26,1068720915,13,212,'',0),(4459,242,1734,0,8,1733,1735,26,1068720915,13,212,'',0),(4460,242,1735,0,9,1734,1735,26,1068720915,13,212,'',0),(4461,242,1735,0,10,1735,1735,26,1068720915,13,212,'',0),(4462,242,1735,0,11,1735,1736,26,1068720915,13,212,'',0),(4463,242,1736,0,12,1735,1737,26,1068720915,13,212,'',0),(4464,242,1737,0,13,1736,0,26,1068720915,13,212,'',0),(7192,249,2059,0,156,2058,1499,2,1069071380,1,121,'',0),(7191,249,2058,0,155,2008,2059,2,1069071380,1,121,'',0),(7190,249,2008,0,154,1499,2058,2,1069071380,1,121,'',0),(7189,249,1499,0,153,2059,2008,2,1069071380,1,121,'',0),(7188,249,2059,0,152,2058,1499,2,1069071380,1,121,'',0),(7187,249,2058,0,151,2008,2059,2,1069071380,1,121,'',0),(7186,249,2008,0,150,1499,2058,2,1069071380,1,121,'',0),(7185,249,1499,0,149,2059,2008,2,1069071380,1,121,'',0),(7184,249,2059,0,148,2058,1499,2,1069071380,1,121,'',0),(7183,249,2058,0,147,2008,2059,2,1069071380,1,121,'',0),(7182,249,2008,0,146,1499,2058,2,1069071380,1,121,'',0),(7181,249,1499,0,145,2059,2008,2,1069071380,1,121,'',0),(7180,249,2059,0,144,2058,1499,2,1069071380,1,121,'',0),(7179,249,2058,0,143,2008,2059,2,1069071380,1,121,'',0),(7178,249,2008,0,142,1499,2058,2,1069071380,1,121,'',0),(7177,249,1499,0,141,2059,2008,2,1069071380,1,121,'',0),(7176,249,2059,0,140,2058,1499,2,1069071380,1,121,'',0),(7175,249,2058,0,139,2008,2059,2,1069071380,1,121,'',0),(7174,249,2008,0,138,1499,2058,2,1069071380,1,121,'',0),(7173,249,1499,0,137,2059,2008,2,1069071380,1,121,'',0),(7172,249,2059,0,136,2058,1499,2,1069071380,1,121,'',0),(7171,249,2058,0,135,2008,2059,2,1069071380,1,121,'',0),(7170,249,2008,0,134,1499,2058,2,1069071380,1,121,'',0),(5310,252,1973,0,0,0,1678,26,1069074891,1,217,'',0),(9464,290,2783,0,36,1535,2770,2,1069685704,1,120,'',0),(9463,290,1535,0,35,2784,2783,2,1069685704,1,120,'',0),(9462,290,2784,0,34,33,1535,2,1069685704,1,120,'',0),(9461,290,33,0,33,2005,2784,2,1069685704,1,120,'',0),(9460,290,2005,0,32,2179,33,2,1069685704,1,120,'',0),(9459,290,2179,0,31,2048,2005,2,1069685704,1,120,'',0),(9458,290,2048,0,30,2770,2179,2,1069685704,1,120,'',0),(9457,290,2770,0,29,2783,2048,2,1069685704,1,120,'',0),(9456,290,2783,0,28,2782,2770,2,1069685704,1,120,'',0),(9455,290,2782,0,27,2231,2783,2,1069685704,1,120,'',0),(9454,290,2231,0,26,1725,2782,2,1069685704,1,120,'',0),(9453,290,1725,0,25,2224,2231,2,1069685704,1,120,'',0),(9452,290,2224,0,24,2770,1725,2,1069685704,1,120,'',0),(9451,290,2770,0,23,2769,2224,2,1069685704,1,120,'',0),(9450,290,2769,0,22,2781,2770,2,1069685704,1,120,'',0),(9449,290,2781,0,21,2770,2769,2,1069685704,1,120,'',0),(9448,290,2770,0,20,2780,2781,2,1069685704,1,120,'',0),(9447,290,2780,0,19,2779,2770,2,1069685704,1,120,'',0),(9446,290,2779,0,18,2101,2780,2,1069685704,1,120,'',0),(9445,290,2101,0,17,2778,2779,2,1069685704,1,120,'',0),(9444,290,2778,0,16,2051,2101,2,1069685704,1,120,'',0),(9443,290,2051,0,15,2777,2778,2,1069685704,1,120,'',0),(9442,290,2777,0,14,2776,2051,2,1069685704,1,120,'',0),(9441,290,2776,0,13,2360,2777,2,1069685704,1,120,'',0),(9440,290,2360,0,12,2775,2776,2,1069685704,1,120,'',0),(9439,290,2775,0,11,1535,2360,2,1069685704,1,120,'',0),(9438,290,1535,0,10,1537,2775,2,1069685704,1,120,'',0),(9437,290,1537,0,9,2774,1535,2,1069685704,1,120,'',0),(9436,290,2774,0,8,2773,1537,2,1069685704,1,120,'',0),(9435,290,2773,0,7,2227,2774,2,1069685704,1,120,'',0),(9434,290,2227,0,6,2025,2773,2,1069685704,1,120,'',0),(9433,290,2025,0,5,2772,2227,2,1069685704,1,120,'',0),(9432,290,2772,0,4,2771,2025,2,1069685704,1,218,'',0),(9431,290,2771,0,3,2770,2772,2,1069685704,1,218,'',0),(9430,290,2770,0,2,2769,2771,2,1069685704,1,1,'',0),(9428,290,2033,0,0,0,2769,2,1069685704,1,1,'',0),(9429,290,2769,0,1,2033,2770,2,1069685704,1,1,'',0),(8906,284,2548,0,110,2578,2314,2,1069683484,1,121,'',0),(8905,284,2578,0,109,2577,2548,2,1069683484,1,121,'',0),(8904,284,2577,0,108,2101,2578,2,1069683484,1,121,'',0),(8903,284,2101,0,107,2236,2577,2,1069683484,1,121,'',0),(8902,284,2236,0,106,2229,2101,2,1069683484,1,121,'',0),(8901,284,2229,0,105,1965,2236,2,1069683484,1,121,'',0),(8900,284,1965,0,104,2385,2229,2,1069683484,1,121,'',0),(8899,284,2385,0,103,2101,1965,2,1069683484,1,121,'',0),(8898,284,2101,0,102,2436,2385,2,1069683484,1,121,'',0),(8897,284,2436,0,101,2576,2101,2,1069683484,1,121,'',0),(8896,284,2576,0,100,33,2436,2,1069683484,1,121,'',0),(8895,284,33,0,99,2575,2576,2,1069683484,1,121,'',0),(8894,284,2575,0,98,2181,33,2,1069683484,1,121,'',0),(8893,284,2181,0,97,2574,2575,2,1069683484,1,121,'',0),(8892,284,2574,0,96,2573,2181,2,1069683484,1,121,'',0),(8891,284,2573,0,95,33,2574,2,1069683484,1,121,'',0),(8890,284,33,0,94,2572,2573,2,1069683484,1,121,'',0),(8889,284,2572,0,93,2571,33,2,1069683484,1,121,'',0),(8888,284,2571,0,92,2101,2572,2,1069683484,1,121,'',0),(8887,284,2101,0,91,2230,2571,2,1069683484,1,121,'',0),(8886,284,2230,0,90,2038,2101,2,1069683484,1,121,'',0),(8885,284,2038,0,89,2229,2230,2,1069683484,1,121,'',0),(8884,284,2229,0,88,1490,2038,2,1069683484,1,121,'',0),(8883,284,1490,0,87,2298,2229,2,1069683484,1,121,'',0),(8882,284,2298,0,86,2037,1490,2,1069683484,1,121,'',0),(8881,284,2037,0,85,1965,2298,2,1069683484,1,121,'',0),(8880,284,1965,0,84,2570,2037,2,1069683484,1,121,'',0),(8879,284,2570,0,83,2258,1965,2,1069683484,1,121,'',0),(8878,284,2258,0,82,2181,2570,2,1069683484,1,121,'',0),(8877,284,2181,0,81,2569,2258,2,1069683484,1,121,'',0),(8876,284,2569,0,80,2249,2181,2,1069683484,1,121,'',0),(8875,284,2249,0,79,2013,2569,2,1069683484,1,121,'',0),(8874,284,2013,0,78,2037,2249,2,1069683484,1,121,'',0),(8873,284,2037,0,77,2568,2013,2,1069683484,1,121,'',0),(8872,284,2568,0,76,2037,2037,2,1069683484,1,121,'',0),(8871,284,2037,0,75,2025,2568,2,1069683484,1,121,'',0),(8870,284,2025,0,74,2567,2037,2,1069683484,1,121,'',0),(8869,284,2567,0,73,2037,2025,2,1069683484,1,121,'',0),(8868,284,2037,0,72,1965,2567,2,1069683484,1,121,'',0),(8867,284,1965,0,71,2222,2037,2,1069683484,1,121,'',0),(8866,284,2222,0,70,2566,1965,2,1069683484,1,121,'',0),(8865,284,2566,0,69,2222,2222,2,1069683484,1,121,'',0),(8864,284,2222,0,68,2332,2566,2,1069683484,1,121,'',0),(8863,284,2332,0,67,33,2222,2,1069683484,1,121,'',0),(8862,284,33,0,66,2565,2332,2,1069683484,1,121,'',0),(8861,284,2565,0,65,2262,33,2,1069683484,1,121,'',0),(8860,284,2262,0,64,2364,2565,2,1069683484,1,121,'',0),(8859,284,2364,0,63,2564,2262,2,1069683484,1,121,'',0),(8858,284,2564,0,62,2563,2364,2,1069683484,1,121,'',0),(8857,284,2563,0,61,1535,2564,2,1069683484,1,121,'',0),(8856,284,1535,0,60,2288,2563,2,1069683484,1,121,'',0),(8855,284,2288,0,59,33,1535,2,1069683484,1,121,'',0),(8854,284,33,0,58,2562,2288,2,1069683484,1,121,'',0),(8853,284,2562,0,57,1535,33,2,1069683484,1,121,'',0),(8852,284,1535,0,56,2561,2562,2,1069683484,1,121,'',0),(8851,284,2561,0,55,2560,1535,2,1069683484,1,121,'',0),(8850,284,2560,0,54,1535,2561,2,1069683484,1,121,'',0),(8849,284,1535,0,53,2548,2560,2,1069683484,1,121,'',0),(8848,284,2548,0,52,2559,1535,2,1069683484,1,121,'',0),(8847,284,2559,0,51,2400,2548,2,1069683484,1,121,'',0),(8846,284,2400,0,50,2558,2559,2,1069683484,1,121,'',0),(8845,284,2558,0,49,2557,2400,2,1069683484,1,121,'',0),(8844,284,2557,0,48,2284,2558,2,1069683484,1,121,'',0),(8843,284,2284,0,47,2556,2557,2,1069683484,1,121,'',0),(8842,284,2556,0,46,2555,2284,2,1069683484,1,121,'',0),(8841,284,2555,0,45,2037,2556,2,1069683484,1,121,'',0),(8840,284,2037,0,44,2551,2555,2,1069683484,1,121,'',0),(8839,284,2551,0,43,2348,2037,2,1069683484,1,121,'',0),(8838,284,2348,0,42,2554,2551,2,1069683484,1,121,'',0),(8837,284,2554,0,41,1535,2348,2,1069683484,1,121,'',0),(8836,284,1535,0,40,2567,2554,2,1069683484,1,121,'',0),(8835,284,2567,0,39,2037,1535,2,1069683484,1,120,'',0),(8834,284,2037,0,38,1965,2567,2,1069683484,1,120,'',0),(8833,284,1965,0,37,2222,2037,2,1069683484,1,120,'',0),(8832,284,2222,0,36,2566,1965,2,1069683484,1,120,'',0),(8831,284,2566,0,35,2222,2222,2,1069683484,1,120,'',0),(8830,284,2222,0,34,2332,2566,2,1069683484,1,120,'',0),(8829,284,2332,0,33,33,2222,2,1069683484,1,120,'',0),(8828,284,33,0,32,2565,2332,2,1069683484,1,120,'',0),(8827,284,2565,0,31,2262,33,2,1069683484,1,120,'',0),(8826,284,2262,0,30,2364,2565,2,1069683484,1,120,'',0),(8825,284,2364,0,29,2564,2262,2,1069683484,1,120,'',0),(8824,284,2564,0,28,2563,2364,2,1069683484,1,120,'',0),(8823,284,2563,0,27,1535,2564,2,1069683484,1,120,'',0),(8822,284,1535,0,26,2288,2563,2,1069683484,1,120,'',0),(8821,284,2288,0,25,33,1535,2,1069683484,1,120,'',0),(8820,284,33,0,24,2562,2288,2,1069683484,1,120,'',0),(8819,284,2562,0,23,1535,33,2,1069683484,1,120,'',0),(8818,284,1535,0,22,2561,2562,2,1069683484,1,120,'',0),(8817,284,2561,0,21,2560,1535,2,1069683484,1,120,'',0),(8816,284,2560,0,20,1535,2561,2,1069683484,1,120,'',0),(8815,284,1535,0,19,2548,2560,2,1069683484,1,120,'',0),(8814,284,2548,0,18,2559,1535,2,1069683484,1,120,'',0),(8813,284,2559,0,17,2400,2548,2,1069683484,1,120,'',0),(8812,284,2400,0,16,2558,2559,2,1069683484,1,120,'',0),(8811,284,2558,0,15,2557,2400,2,1069683484,1,120,'',0),(8810,284,2557,0,14,2284,2558,2,1069683484,1,120,'',0),(8809,284,2284,0,13,2556,2557,2,1069683484,1,120,'',0),(8808,284,2556,0,12,2555,2284,2,1069683484,1,120,'',0),(8807,284,2555,0,11,2037,2556,2,1069683484,1,120,'',0),(8806,284,2037,0,10,2551,2555,2,1069683484,1,120,'',0),(8805,284,2551,0,9,2348,2037,2,1069683484,1,120,'',0),(8804,284,2348,0,8,2554,2551,2,1069683484,1,120,'',0),(8803,284,2554,0,7,1535,2348,2,1069683484,1,120,'',0),(8802,284,1535,0,6,2553,2554,2,1069683484,1,120,'',0),(8801,284,2553,0,5,2552,1535,2,1069683484,1,218,'',0),(8800,284,2552,0,4,2551,2553,2,1069683484,1,218,'',0),(8799,284,2551,0,3,2550,2552,2,1069683484,1,1,'',0),(8798,284,2550,0,2,2549,2551,2,1069683484,1,1,'',0),(8797,284,2549,0,1,2548,2550,2,1069683484,1,1,'',0),(8796,284,2548,0,0,0,2549,2,1069683484,1,1,'',0),(8448,250,2191,0,43,2406,0,2,1069072401,1,219,'',1),(8447,250,2406,0,42,2304,2191,2,1069072401,1,121,'',0),(8446,250,2304,0,41,2405,2406,2,1069072401,1,121,'',0),(8445,250,2405,0,40,2404,2304,2,1069072401,1,121,'',0),(8444,250,2404,0,39,2403,2405,2,1069072401,1,121,'',0),(8443,250,2403,0,38,2402,2404,2,1069072401,1,121,'',0),(8442,250,2402,0,37,2401,2403,2,1069072401,1,121,'',0),(8441,250,2401,0,36,2400,2402,2,1069072401,1,121,'',0),(8440,250,2400,0,35,2399,2401,2,1069072401,1,121,'',0),(8439,250,2399,0,34,2398,2400,2,1069072401,1,121,'',0),(8438,250,2398,0,33,2386,2399,2,1069072401,1,121,'',0),(8437,250,2386,0,32,2191,2398,2,1069072401,1,121,'',0),(8436,250,2191,0,31,2397,2386,2,1069072401,1,121,'',0),(8435,250,2397,0,30,2276,2191,2,1069072401,1,121,'',0),(8434,250,2276,0,29,1535,2397,2,1069072401,1,121,'',0),(8433,250,1535,0,28,1965,2276,2,1069072401,1,121,'',0),(8432,250,1965,0,27,2069,1535,2,1069072401,1,120,'',0),(8431,250,2069,0,26,2396,1965,2,1069072401,1,120,'',0),(8430,250,2396,0,25,2395,2069,2,1069072401,1,120,'',0),(8429,250,2395,0,24,2388,2396,2,1069072401,1,120,'',0),(8428,250,2388,0,23,2037,2395,2,1069072401,1,120,'',0),(8427,250,2037,0,22,1965,2388,2,1069072401,1,120,'',0),(8426,250,1965,0,21,2394,2037,2,1069072401,1,120,'',0),(8425,250,2394,0,20,2393,1965,2,1069072401,1,120,'',0),(8424,250,2393,0,19,1535,2394,2,1069072401,1,120,'',0),(8423,250,1535,0,18,2392,2393,2,1069072401,1,120,'',0),(8422,250,2392,0,17,2391,1535,2,1069072401,1,120,'',0),(8421,250,2391,0,16,2271,2392,2,1069072401,1,120,'',0),(8420,250,2271,0,15,2390,2391,2,1069072401,1,120,'',0),(8419,250,2390,0,14,2181,2271,2,1069072401,1,120,'',0),(8418,250,2181,0,13,2389,2390,2,1069072401,1,120,'',0),(8417,250,2389,0,12,2181,2181,2,1069072401,1,120,'',0),(8416,250,2181,0,11,2348,2389,2,1069072401,1,120,'',0),(8415,250,2348,0,10,2388,2181,2,1069072401,1,120,'',0),(8414,250,2388,0,9,2037,2348,2,1069072401,1,120,'',0),(8413,250,2037,0,8,33,2388,2,1069072401,1,120,'',0),(8412,250,33,0,7,2385,2037,2,1069072401,1,120,'',0),(8411,250,2385,0,6,2387,33,2,1069072401,1,120,'',0),(8410,250,2387,0,5,1535,2385,2,1069072401,1,120,'',0),(8409,250,1535,0,4,1725,2387,2,1069072401,1,120,'',0),(8408,250,1725,0,3,2386,1535,2,1069072401,1,120,'',0),(8407,250,2386,0,2,2385,1725,2,1069072401,1,120,'',0),(8406,250,2385,0,1,2384,2386,2,1069072401,1,1,'',0),(8405,250,2384,0,0,0,2385,2,1069072401,1,1,'',0),(7169,249,1499,0,133,2059,2008,2,1069071380,1,121,'',0),(7168,249,2059,0,132,2058,1499,2,1069071380,1,121,'',0),(7167,249,2058,0,131,2008,2059,2,1069071380,1,121,'',0),(7166,249,2008,0,130,1499,2058,2,1069071380,1,121,'',0),(7165,249,1499,0,129,2059,2008,2,1069071380,1,121,'',0),(7164,249,2059,0,128,2058,1499,2,1069071380,1,121,'',0),(7163,249,2058,0,127,2008,2059,2,1069071380,1,121,'',0),(7162,249,2008,0,126,1499,2058,2,1069071380,1,121,'',0),(7161,249,1499,0,125,2059,2008,2,1069071380,1,121,'',0),(7160,249,2059,0,124,2058,1499,2,1069071380,1,121,'',0),(7159,249,2058,0,123,2008,2059,2,1069071380,1,121,'',0),(7158,249,2008,0,122,1499,2058,2,1069071380,1,121,'',0),(7157,249,1499,0,121,2059,2008,2,1069071380,1,121,'',0),(7156,249,2059,0,120,2058,1499,2,1069071380,1,121,'',0),(7155,249,2058,0,119,2008,2059,2,1069071380,1,121,'',0),(7154,249,2008,0,118,1499,2058,2,1069071380,1,121,'',0),(7153,249,1499,0,117,2059,2008,2,1069071380,1,121,'',0),(7152,249,2059,0,116,2058,1499,2,1069071380,1,121,'',0),(7151,249,2058,0,115,2008,2059,2,1069071380,1,121,'',0),(7150,249,2008,0,114,1499,2058,2,1069071380,1,121,'',0),(7149,249,1499,0,113,2059,2008,2,1069071380,1,121,'',0),(7148,249,2059,0,112,2058,1499,2,1069071380,1,121,'',0),(7147,249,2058,0,111,2008,2059,2,1069071380,1,121,'',0),(7146,249,2008,0,110,1499,2058,2,1069071380,1,121,'',0),(7145,249,1499,0,109,2059,2008,2,1069071380,1,121,'',0),(7144,249,2059,0,108,2058,1499,2,1069071380,1,121,'',0),(7143,249,2058,0,107,2008,2059,2,1069071380,1,121,'',0),(7142,249,2008,0,106,1499,2058,2,1069071380,1,121,'',0),(7141,249,1499,0,105,2059,2008,2,1069071380,1,121,'',0),(7140,249,2059,0,104,2058,1499,2,1069071380,1,121,'',0),(7139,249,2058,0,103,2008,2059,2,1069071380,1,121,'',0),(7138,249,2008,0,102,1499,2058,2,1069071380,1,121,'',0),(7137,249,1499,0,101,2059,2008,2,1069071380,1,121,'',0),(7136,249,2059,0,100,2058,1499,2,1069071380,1,121,'',0),(7135,249,2058,0,99,2008,2059,2,1069071380,1,121,'',0),(7134,249,2008,0,98,1499,2058,2,1069071380,1,121,'',0),(7133,249,1499,0,97,2059,2008,2,1069071380,1,121,'',0),(7132,249,2059,0,96,2058,1499,2,1069071380,1,121,'',0),(7131,249,2058,0,95,2008,2059,2,1069071380,1,121,'',0),(7130,249,2008,0,94,1499,2058,2,1069071380,1,121,'',0),(7129,249,1499,0,93,2059,2008,2,1069071380,1,121,'',0),(7128,249,2059,0,92,2058,1499,2,1069071380,1,121,'',0),(7127,249,2058,0,91,2008,2059,2,1069071380,1,121,'',0),(7126,249,2008,0,90,1499,2058,2,1069071380,1,121,'',0),(7125,249,1499,0,89,2059,2008,2,1069071380,1,121,'',0),(7124,249,2059,0,88,2058,1499,2,1069071380,1,121,'',0),(7123,249,2058,0,87,2008,2059,2,1069071380,1,121,'',0),(7122,249,2008,0,86,1499,2058,2,1069071380,1,121,'',0),(7121,249,1499,0,85,2059,2008,2,1069071380,1,121,'',0),(7120,249,2059,0,84,2058,1499,2,1069071380,1,121,'',0),(7119,249,2058,0,83,2008,2059,2,1069071380,1,121,'',0),(7118,249,2008,0,82,1499,2058,2,1069071380,1,121,'',0),(7117,249,1499,0,81,2059,2008,2,1069071380,1,121,'',0),(7116,249,2059,0,80,2058,1499,2,1069071380,1,121,'',0),(7115,249,2058,0,79,2008,2059,2,1069071380,1,121,'',0),(7114,249,2008,0,78,1499,2058,2,1069071380,1,121,'',0),(7113,249,1499,0,77,2059,2008,2,1069071380,1,121,'',0),(7112,249,2059,0,76,2058,1499,2,1069071380,1,121,'',0),(7111,249,2058,0,75,2008,2059,2,1069071380,1,121,'',0),(7110,249,2008,0,74,1499,2058,2,1069071380,1,121,'',0),(7109,249,1499,0,73,2059,2008,2,1069071380,1,121,'',0),(7108,249,2059,0,72,2058,1499,2,1069071380,1,121,'',0),(7107,249,2058,0,71,2008,2059,2,1069071380,1,121,'',0),(7106,249,2008,0,70,1499,2058,2,1069071380,1,121,'',0),(7105,249,1499,0,69,2059,2008,2,1069071380,1,121,'',0),(7104,249,2059,0,68,2058,1499,2,1069071380,1,121,'',0),(7103,249,2058,0,67,2008,2059,2,1069071380,1,121,'',0),(7102,249,2008,0,66,1499,2058,2,1069071380,1,121,'',0),(7101,249,1499,0,65,2059,2008,2,1069071380,1,121,'',0),(7100,249,2059,0,64,2058,1499,2,1069071380,1,121,'',0),(7099,249,2058,0,63,2008,2059,2,1069071380,1,121,'',0),(7098,249,2008,0,62,1499,2058,2,1069071380,1,121,'',0),(7097,249,1499,0,61,2059,2008,2,1069071380,1,121,'',0),(7096,249,2059,0,60,2058,1499,2,1069071380,1,121,'',0),(7095,249,2058,0,59,2008,2059,2,1069071380,1,121,'',0),(7094,249,2008,0,58,1499,2058,2,1069071380,1,121,'',0),(7093,249,1499,0,57,2059,2008,2,1069071380,1,120,'',0),(7092,249,2059,0,56,2058,1499,2,1069071380,1,120,'',0),(7091,249,2058,0,55,2008,2059,2,1069071380,1,120,'',0),(7090,249,2008,0,54,1499,2058,2,1069071380,1,120,'',0),(7089,249,1499,0,53,2059,2008,2,1069071380,1,120,'',0),(7088,249,2059,0,52,2058,1499,2,1069071380,1,120,'',0),(7087,249,2058,0,51,2008,2059,2,1069071380,1,120,'',0),(7086,249,2008,0,50,1499,2058,2,1069071380,1,120,'',0),(7085,249,1499,0,49,2059,2008,2,1069071380,1,120,'',0),(7084,249,2059,0,48,2058,1499,2,1069071380,1,120,'',0),(7083,249,2058,0,47,2008,2059,2,1069071380,1,120,'',0),(7082,249,2008,0,46,1499,2058,2,1069071380,1,120,'',0),(7081,249,1499,0,45,2059,2008,2,1069071380,1,120,'',0),(7080,249,2059,0,44,2058,1499,2,1069071380,1,120,'',0),(7079,249,2058,0,43,2008,2059,2,1069071380,1,120,'',0),(7078,249,2008,0,42,1499,2058,2,1069071380,1,120,'',0),(7077,249,1499,0,41,2059,2008,2,1069071380,1,120,'',0),(7076,249,2059,0,40,2058,1499,2,1069071380,1,120,'',0),(7075,249,2058,0,39,2008,2059,2,1069071380,1,120,'',0),(7074,249,2008,0,38,1499,2058,2,1069071380,1,120,'',0),(7073,249,1499,0,37,2059,2008,2,1069071380,1,120,'',0),(7072,249,2059,0,36,2058,1499,2,1069071380,1,120,'',0),(7071,249,2058,0,35,2008,2059,2,1069071380,1,120,'',0),(7070,249,2008,0,34,1499,2058,2,1069071380,1,120,'',0),(7069,249,1499,0,33,2059,2008,2,1069071380,1,120,'',0),(7068,249,2059,0,32,2058,1499,2,1069071380,1,120,'',0),(7067,249,2058,0,31,2008,2059,2,1069071380,1,120,'',0),(7066,249,2008,0,30,1499,2058,2,1069071380,1,120,'',0),(7065,249,1499,0,29,2059,2008,2,1069071380,1,120,'',0),(4807,245,1694,0,0,0,1693,26,1068730476,13,209,'',0),(4808,245,1693,0,1,1694,1693,26,1068730476,13,210,'',0),(4809,245,1693,0,2,1693,1912,26,1068730476,13,211,'',0),(4810,245,1912,0,3,1693,0,26,1068730476,13,212,'',0),(4812,246,1914,0,0,0,1514,26,1068737197,13,209,'',0),(4813,246,1514,0,1,1914,1915,26,1068737197,13,210,'',0),(4814,246,1915,0,2,1514,1916,26,1068737197,13,211,'',0),(4815,246,1916,0,3,1915,1917,26,1068737197,13,212,'',0),(4816,246,1917,0,4,1916,1687,26,1068737197,13,212,'',0),(4817,246,1687,0,5,1917,1509,26,1068737197,13,212,'',0),(4818,246,1509,0,6,1687,0,26,1068737197,13,212,'',0),(7064,249,2059,0,28,2058,1499,2,1069071380,1,120,'',0),(7063,249,2058,0,27,2008,2059,2,1069071380,1,120,'',0),(7062,249,2008,0,26,1499,2058,2,1069071380,1,120,'',0),(7061,249,1499,0,25,2059,2008,2,1069071380,1,120,'',0),(7060,249,2059,0,24,2058,1499,2,1069071380,1,120,'',0),(7059,249,2058,0,23,2008,2059,2,1069071380,1,120,'',0),(7058,249,2008,0,22,1499,2058,2,1069071380,1,120,'',0),(7057,249,1499,0,21,2059,2008,2,1069071380,1,120,'',0),(7056,249,2059,0,20,2058,1499,2,1069071380,1,120,'',0),(7055,249,2058,0,19,2008,2059,2,1069071380,1,120,'',0),(7054,249,2008,0,18,1499,2058,2,1069071380,1,120,'',0),(7053,249,1499,0,17,2059,2008,2,1069071380,1,120,'',0),(7052,249,2059,0,16,2058,1499,2,1069071380,1,120,'',0),(7051,249,2058,0,15,2008,2059,2,1069071380,1,120,'',0),(7050,249,2008,0,14,1499,2058,2,1069071380,1,120,'',0),(7049,249,1499,0,13,2059,2008,2,1069071380,1,120,'',0),(7048,249,2059,0,12,2058,1499,2,1069071380,1,120,'',0),(7047,249,2058,0,11,2008,2059,2,1069071380,1,120,'',0),(7046,249,2008,0,10,1499,2058,2,1069071380,1,120,'',0),(7045,249,1499,0,9,2059,2008,2,1069071380,1,120,'',0),(7044,249,2059,0,8,2058,1499,2,1069071380,1,120,'',0),(7043,249,2058,0,7,2008,2059,2,1069071380,1,120,'',0),(7042,249,2008,0,6,1499,2058,2,1069071380,1,120,'',0),(7041,249,1499,0,5,2059,2008,2,1069071380,1,120,'',0),(7040,249,2059,0,4,2058,1499,2,1069071380,1,120,'',0),(4890,248,1678,0,0,0,0,1,1069070990,1,4,'',0),(7039,249,2058,0,3,2008,2059,2,1069071380,1,120,'',0),(7038,249,2008,0,2,1678,2058,2,1069071380,1,120,'',0),(7037,249,1678,0,1,2057,2008,2,1069071380,1,1,'',0),(7036,249,2057,0,0,0,1678,2,1069071380,1,1,'',0),(7937,272,2207,0,4,2206,0,26,1069244422,1,212,'',0),(7938,275,2208,0,0,0,2209,26,1069245323,1,217,'',0),(7939,275,2209,0,1,2208,2210,26,1069245323,1,209,'',0),(7940,275,2210,0,2,2209,2211,26,1069245323,1,210,'',0),(7941,275,2211,0,3,2210,2212,26,1069245323,1,211,'',0),(7942,275,2212,0,4,2211,1503,26,1069245323,1,212,'',0),(7943,275,1503,0,5,2212,2213,26,1069245323,1,212,'',0),(7944,275,2213,0,6,1503,0,26,1069245323,1,212,'',0),(9835,56,2918,0,6,2222,2224,15,1066643397,11,220,'',0),(9834,56,2222,0,5,2917,2918,15,1066643397,11,220,'',0),(9833,56,2917,0,4,2916,2222,15,1066643397,11,220,'',0),(9832,56,2916,0,3,2915,2917,15,1066643397,11,220,'',0),(9831,56,2915,0,2,2914,2916,15,1066643397,11,220,'',0),(9830,56,2914,0,1,2913,2915,15,1066643397,11,220,'',0),(9829,56,2913,0,0,0,2914,15,1066643397,11,161,'',0),(7962,278,2229,0,5,2228,2230,2,1069680733,1,120,'',0),(7963,278,2230,0,6,2229,2231,2,1069680733,1,120,'',0),(7964,278,2231,0,7,2230,2190,2,1069680733,1,120,'',0),(7965,278,2190,0,8,2231,1537,2,1069680733,1,120,'',0),(7966,278,1537,0,9,2190,1535,2,1069680733,1,120,'',0),(7967,278,1535,0,10,1537,1678,2,1069680733,1,120,'',0),(7968,278,1678,0,11,1535,2232,2,1069680733,1,120,'',0),(7969,278,2232,0,12,1678,2079,2,1069680733,1,120,'',0),(7970,278,2079,0,13,2232,1535,2,1069680733,1,120,'',0),(7971,278,1535,0,14,2079,2045,2,1069680733,1,120,'',0),(7972,278,2045,0,15,1535,2233,2,1069680733,1,120,'',0),(7973,278,2233,0,16,2045,2048,2,1069680733,1,120,'',0),(7974,278,2048,0,17,2233,1535,2,1069680733,1,120,'',0),(7975,278,1535,0,18,2048,2008,2,1069680733,1,120,'',0),(7976,278,2008,0,19,1535,2234,2,1069680733,1,120,'',0),(7977,278,2234,0,20,2008,2025,2,1069680733,1,120,'',0),(7978,278,2025,0,21,2234,1725,2,1069680733,1,120,'',0),(7979,278,1725,0,22,2025,1535,2,1069680733,1,120,'',0),(7980,278,1535,0,23,1725,2235,2,1069680733,1,120,'',0),(7981,278,2235,0,24,1535,2181,2,1069680733,1,120,'',0),(7982,278,2181,0,25,2235,2236,2,1069680733,1,120,'',0),(7983,278,2236,0,26,2181,1535,2,1069680733,1,120,'',0),(7984,278,1535,0,27,2236,2008,2,1069680733,1,121,'',0),(7985,278,2008,0,28,1535,2237,2,1069680733,1,121,'',0),(7986,278,2237,0,29,2008,1535,2,1069680733,1,121,'',0),(7987,278,1535,0,30,2237,2238,2,1069680733,1,121,'',0),(7988,278,2238,0,31,1535,2239,2,1069680733,1,121,'',0),(7989,278,2239,0,32,2238,2240,2,1069680733,1,121,'',0),(7990,278,2240,0,33,2239,1535,2,1069680733,1,121,'',0),(7991,278,1535,0,34,2240,1726,2,1069680733,1,121,'',0),(7992,278,1726,0,35,1535,2241,2,1069680733,1,121,'',0),(7993,278,2241,0,36,1726,1535,2,1069680733,1,121,'',0),(7994,278,1535,0,37,2241,1726,2,1069680733,1,121,'',0),(7995,278,1726,0,38,1535,2242,2,1069680733,1,121,'',0),(7996,278,2242,0,39,1726,2243,2,1069680733,1,121,'',0),(7997,278,2243,0,40,2242,1535,2,1069680733,1,121,'',0),(7998,278,1535,0,41,2243,2008,2,1069680733,1,121,'',0),(7999,278,2008,0,42,1535,2244,2,1069680733,1,121,'',0),(8000,278,2244,0,43,2008,2245,2,1069680733,1,121,'',0),(8001,278,2245,0,44,2244,2246,2,1069680733,1,121,'',0),(8002,278,2246,0,45,2245,2229,2,1069680733,1,121,'',0),(8003,278,2229,0,46,2246,2247,2,1069680733,1,121,'',0),(8004,278,2247,0,47,2229,1965,2,1069680733,1,121,'',0),(8005,278,1965,0,48,2247,2013,2,1069680733,1,121,'',0),(8006,278,2013,0,49,1965,2248,2,1069680733,1,121,'',0),(8007,278,2248,0,50,2013,2191,2,1069680733,1,121,'',0),(8008,278,2191,0,51,2248,0,2,1069680733,1,219,'',1),(8009,279,2229,0,0,0,2249,2,1069680908,1,1,'',0),(8010,279,2249,0,1,2229,2250,2,1069680908,1,1,'',0),(8011,279,2250,0,2,2249,2251,2,1069680908,1,1,'',0),(8012,279,2251,0,3,2250,1535,2,1069680908,1,1,'',0),(8013,279,1535,0,4,2251,2252,2,1069680908,1,120,'',0),(8014,279,2252,0,5,1535,1725,2,1069680908,1,120,'',0),(8015,279,1725,0,6,2252,2253,2,1069680908,1,120,'',0),(8016,279,2253,0,7,1725,2254,2,1069680908,1,120,'',0),(8017,279,2254,0,8,2253,2255,2,1069680908,1,120,'',0),(8018,279,2255,0,9,2254,1965,2,1069680908,1,120,'',0),(8019,279,1965,0,10,2255,1725,2,1069680908,1,120,'',0),(8020,279,1725,0,11,1965,2256,2,1069680908,1,120,'',0),(8021,279,2256,0,12,1725,2257,2,1069680908,1,120,'',0),(8022,279,2257,0,13,2256,2181,2,1069680908,1,120,'',0),(8023,279,2181,0,14,2257,2258,2,1069680908,1,120,'',0),(8024,279,2258,0,15,2181,2024,2,1069680908,1,120,'',0),(8025,279,2024,0,16,2258,1965,2,1069680908,1,120,'',0),(8026,279,1965,0,17,2024,1725,2,1069680908,1,120,'',0),(8027,279,1725,0,18,1965,2101,2,1069680908,1,120,'',0),(8028,279,2101,0,19,1725,2259,2,1069680908,1,120,'',0),(8029,279,2259,0,20,2101,2260,2,1069680908,1,120,'',0),(8030,279,2260,0,21,2259,2256,2,1069680908,1,120,'',0),(8031,279,2256,0,22,2260,2229,2,1069680908,1,120,'',0),(8032,279,2229,0,23,2256,2249,2,1069680908,1,120,'',0),(8033,279,2249,0,24,2229,2236,2,1069680908,1,120,'',0),(8034,279,2236,0,25,2249,1535,2,1069680908,1,120,'',0),(8035,279,1535,0,26,2236,2261,2,1069680908,1,120,'',0),(8036,279,2261,0,27,1535,2181,2,1069680908,1,120,'',0),(8037,279,2181,0,28,2261,2250,2,1069680908,1,120,'',0),(8038,279,2250,0,29,2181,2251,2,1069680908,1,120,'',0),(8039,279,2251,0,30,2250,2051,2,1069680908,1,120,'',0),(8040,279,2051,0,31,2251,2262,2,1069680908,1,120,'',0),(8041,279,2262,0,32,2051,2263,2,1069680908,1,120,'',0),(8042,279,2263,0,33,2262,1965,2,1069680908,1,120,'',0),(8043,279,1965,0,34,2263,1725,2,1069680908,1,121,'',0),(8044,279,1725,0,35,1965,2264,2,1069680908,1,121,'',0),(8045,279,2264,0,36,1725,2181,2,1069680908,1,121,'',0),(8046,279,2181,0,37,2264,2265,2,1069680908,1,121,'',0),(8047,279,2265,0,38,2181,2181,2,1069680908,1,121,'',0),(8048,279,2181,0,39,2265,2258,2,1069680908,1,121,'',0),(8049,279,2258,0,40,2181,2255,2,1069680908,1,121,'',0),(8050,279,2255,0,41,2258,2249,2,1069680908,1,121,'',0),(8051,279,2249,0,42,2255,1725,2,1069680908,1,121,'',0),(8052,279,1725,0,43,2249,1535,2,1069680908,1,121,'',0),(8053,279,1535,0,44,1725,2266,2,1069680908,1,121,'',0),(8054,279,2266,0,45,1535,2079,2,1069680908,1,121,'',0),(8055,279,2079,0,46,2266,2013,2,1069680908,1,121,'',0),(8056,279,2013,0,47,2079,2267,2,1069680908,1,121,'',0),(8057,279,2267,0,48,2013,2025,2,1069680908,1,121,'',0),(8058,279,2025,0,49,2267,2227,2,1069680908,1,121,'',0),(8059,279,2227,0,50,2025,2229,2,1069680908,1,121,'',0),(8060,279,2229,0,51,2227,2249,2,1069680908,1,121,'',0),(8061,279,2249,0,52,2229,2236,2,1069680908,1,121,'',0),(8062,279,2236,0,53,2249,2268,2,1069680908,1,121,'',0),(8063,279,2268,0,54,2236,2181,2,1069680908,1,121,'',0),(8064,279,2181,0,55,2268,2269,2,1069680908,1,121,'',0),(8065,279,2269,0,56,2181,2023,2,1069680908,1,121,'',0),(8066,279,2023,0,57,2269,2270,2,1069680908,1,121,'',0),(8067,279,2270,0,58,2023,2271,2,1069680908,1,121,'',0),(8068,279,2271,0,59,2270,2272,2,1069680908,1,121,'',0),(8069,279,2272,0,60,2271,2273,2,1069680908,1,121,'',0),(8070,279,2273,0,61,2272,2274,2,1069680908,1,121,'',0),(8071,279,2274,0,62,2273,2048,2,1069680908,1,121,'',0),(8072,279,2048,0,63,2274,1519,2,1069680908,1,121,'',0),(8073,279,1519,0,64,2048,2275,2,1069680908,1,121,'',0),(8074,279,2275,0,65,1519,2191,2,1069680908,1,121,'',0),(8075,279,2191,0,66,2275,0,2,1069680908,1,219,'',1),(8076,281,2276,0,0,0,2277,2,1069681297,1,1,'',0),(8077,281,2277,0,1,2276,1537,2,1069681297,1,1,'',0),(8078,281,1537,0,2,2277,2278,2,1069681297,1,1,'',0),(8079,281,2278,0,3,1537,2035,2,1069681297,1,1,'',0),(8080,281,2035,0,4,2278,2278,2,1069681297,1,120,'',0),(8081,281,2278,0,5,2035,2279,2,1069681297,1,120,'',0),(8082,281,2279,0,6,2278,2039,2,1069681297,1,120,'',0),(8083,281,2039,0,7,2279,2280,2,1069681297,1,120,'',0),(8084,281,2280,0,8,2039,2281,2,1069681297,1,120,'',0),(8085,281,2281,0,9,2280,2282,2,1069681297,1,120,'',0),(8086,281,2282,0,10,2281,2283,2,1069681297,1,120,'',0),(8087,281,2283,0,11,2282,2284,2,1069681297,1,120,'',0),(8088,281,2284,0,12,2283,2285,2,1069681297,1,120,'',0),(8089,281,2285,0,13,2284,2255,2,1069681297,1,120,'',0),(8090,281,2255,0,14,2285,2037,2,1069681297,1,120,'',0),(8091,281,2037,0,15,2255,1965,2,1069681297,1,120,'',0),(8092,281,1965,0,16,2037,2013,2,1069681297,1,120,'',0),(8093,281,2013,0,17,1965,2286,2,1069681297,1,120,'',0),(8094,281,2286,0,18,2013,2287,2,1069681297,1,120,'',0),(8095,281,2287,0,19,2286,2048,2,1069681297,1,120,'',0),(8096,281,2048,0,20,2287,2228,2,1069681297,1,120,'',0),(8097,281,2228,0,21,2048,2229,2,1069681297,1,120,'',0),(8098,281,2229,0,22,2228,2288,2,1069681297,1,120,'',0),(8099,281,2288,0,23,2229,2246,2,1069681297,1,120,'',0),(8100,281,2246,0,24,2288,2289,2,1069681297,1,120,'',0),(8101,281,2289,0,25,2246,2290,2,1069681297,1,120,'',0),(8102,281,2290,0,26,2289,2291,2,1069681297,1,120,'',0),(8103,281,2291,0,27,2290,2228,2,1069681297,1,120,'',0),(8104,281,2228,0,28,2291,2230,2,1069681297,1,121,'',0),(8105,281,2230,0,29,2228,2292,2,1069681297,1,121,'',0),(8106,281,2292,0,30,2230,2287,2,1069681297,1,121,'',0),(8107,281,2287,0,31,2292,2048,2,1069681297,1,121,'',0),(8108,281,2048,0,32,2287,2035,2,1069681297,1,121,'',0),(8109,281,2035,0,33,2048,2278,2,1069681297,1,121,'',0),(8110,281,2278,0,34,2035,2048,2,1069681297,1,121,'',0),(8111,281,2048,0,35,2278,1519,2,1069681297,1,121,'',0),(8112,281,1519,0,36,2048,2293,2,1069681297,1,121,'',0),(8113,281,2293,0,37,1519,2294,2,1069681297,1,121,'',0),(8114,281,2294,0,38,2293,2295,2,1069681297,1,121,'',0),(8115,281,2295,0,39,2294,33,2,1069681297,1,121,'',0),(8116,281,33,0,40,2295,2296,2,1069681297,1,121,'',0),(8117,281,2296,0,41,33,2230,2,1069681297,1,121,'',0),(8118,281,2230,0,42,2296,2297,2,1069681297,1,121,'',0),(8119,281,2297,0,43,2230,2182,2,1069681297,1,121,'',0),(8120,281,2182,0,44,2297,2298,2,1069681297,1,121,'',0),(8121,281,2298,0,45,2182,33,2,1069681297,1,121,'',0),(8122,281,33,0,46,2298,2298,2,1069681297,1,121,'',0),(8123,281,2298,0,47,33,2299,2,1069681297,1,121,'',0),(8124,281,2299,0,48,2298,2300,2,1069681297,1,121,'',0),(8125,281,2300,0,49,2299,2025,2,1069681297,1,121,'',0),(8126,281,2025,0,50,2300,2229,2,1069681297,1,121,'',0),(8127,281,2229,0,51,2025,2236,2,1069681297,1,121,'',0),(8128,281,2236,0,52,2229,1535,2,1069681297,1,121,'',0),(8129,281,1535,0,53,2236,1726,2,1069681297,1,121,'',0),(8130,281,1726,0,54,1535,2283,2,1069681297,1,121,'',0),(8131,281,2283,0,55,1726,2301,2,1069681297,1,121,'',0),(8132,281,2301,0,56,2283,2255,2,1069681297,1,121,'',0),(8133,281,2255,0,57,2301,2180,2,1069681297,1,121,'',0),(8134,281,2180,0,58,2255,2302,2,1069681297,1,121,'',0),(8135,281,2302,0,59,2180,2230,2,1069681297,1,121,'',0),(8136,281,2230,0,60,2302,2228,2,1069681297,1,121,'',0),(8137,281,2228,0,61,2230,2303,2,1069681297,1,121,'',0),(8138,281,2303,0,62,2228,2025,2,1069681297,1,121,'',0),(8139,281,2025,0,63,2303,2304,2,1069681297,1,121,'',0),(8140,281,2304,0,64,2025,2035,2,1069681297,1,121,'',0),(8141,281,2035,0,65,2304,2278,2,1069681297,1,121,'',0),(8142,281,2278,0,66,2035,2272,2,1069681297,1,121,'',0),(8143,281,2272,0,67,2278,2305,2,1069681297,1,121,'',0),(8144,281,2305,0,68,2272,2033,2,1069681297,1,121,'',0),(8145,281,2033,0,69,2305,2181,2,1069681297,1,121,'',0),(8146,281,2181,0,70,2033,2306,2,1069681297,1,121,'',0),(8147,281,2306,0,71,2181,2191,2,1069681297,1,121,'',0),(8148,281,2191,0,72,2306,0,2,1069681297,1,219,'',1),(8149,283,2307,0,0,0,2308,2,1069681443,1,1,'',0),(8150,283,2308,0,1,2307,2309,2,1069681443,1,1,'',0),(8151,283,2309,0,2,2308,2310,2,1069681443,1,1,'',0),(8152,283,2310,0,3,2309,1558,2,1069681443,1,1,'',0),(8153,283,1558,0,4,2310,1537,2,1069681443,1,120,'',0),(8154,283,1537,0,5,1558,1535,2,1069681443,1,120,'',0),(8155,283,1535,0,6,1537,2311,2,1069681443,1,120,'',0),(8156,283,2311,0,7,1535,2312,2,1069681443,1,120,'',0),(8157,283,2312,0,8,2311,2079,2,1069681443,1,120,'',0),(8158,283,2079,0,9,2312,1535,2,1069681443,1,120,'',0),(8159,283,1535,0,10,2079,2313,2,1069681443,1,120,'',0),(8160,283,2313,0,11,1535,2314,2,1069681443,1,120,'',0),(8161,283,2314,0,12,2313,2315,2,1069681443,1,120,'',0),(8162,283,2315,0,13,2314,2253,2,1069681443,1,120,'',0),(8163,283,2253,0,14,2315,1537,2,1069681443,1,120,'',0),(8164,283,1537,0,15,2253,2044,2,1069681443,1,120,'',0),(8165,283,2044,0,16,1537,1965,2,1069681443,1,120,'',0),(8166,283,1965,0,17,2044,1725,2,1069681443,1,120,'',0),(8167,283,1725,0,18,1965,1535,2,1069681443,1,120,'',0),(8168,283,1535,0,19,1725,2316,2,1069681443,1,120,'',0),(8169,283,2316,0,20,1535,1537,2,1069681443,1,120,'',0),(8170,283,1537,0,21,2316,2181,2,1069681443,1,120,'',0),(8171,283,2181,0,22,1537,2317,2,1069681443,1,120,'',0),(8172,283,2317,0,23,2181,2318,2,1069681443,1,120,'',0),(8173,283,2318,0,24,2317,2319,2,1069681443,1,120,'',0),(8174,283,2319,0,25,2318,2320,2,1069681443,1,120,'',0),(8175,283,2320,0,26,2319,1535,2,1069681443,1,120,'',0),(8176,283,1535,0,27,2320,2321,2,1069681443,1,120,'',0),(8177,283,2321,0,28,1535,2308,2,1069681443,1,120,'',0),(8178,283,2308,0,29,2321,2191,2,1069681443,1,120,'',0),(8179,283,2191,0,30,2308,0,2,1069681443,1,219,'',1);
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
INSERT INTO ezsearch_word VALUES (1527,'båbåb',1),(6,'media',1),(7,'setup',3),(2063,'grouplist',1),(2062,'class',1),(2061,'classes',1),(11,'links',1),(25,'content',4),(34,'feel',3),(33,'and',10),(32,'look',2),(2573,'then',1),(2524,'cursus',1),(2530,'get',3),(2523,'elit',1),(1519,'more',6),(2522,'est',1),(1521,'dsfgljk',1),(1520,'speacial',1),(2521,'integer',1),(1523,'sdlfgj',1),(1535,'the',15),(2355,'hollywood',1),(2520,'dignissim',1),(2051,'from',3),(2519,'morbi',1),(2518,'porttitor',1),(1525,'gfdsfgljk',1),(2517,'dui',1),(2516,'bibendum',1),(2515,'nam',1),(2354,'movie',1),(2514,'sagittis',1),(1501,'things',2),(2356,'selected',1),(1558,'one',4),(2048,'for',9),(2353,'governor',1),(1516,'ez.no',5),(1515,'http',6),(2574,'go',1),(1514,'bf@ez.no',7),(2572,'dring',1),(1518,'seen',1),(2513,'adipiscing',1),(1503,'sd',2),(2571,'small',2),(2068,'urltranslator',1),(2006,'politics',1),(2005,'technology',2),(1517,'ve',1),(1543,'which',3),(2060,'cache',1),(1965,'it',10),(1537,'of',8),(2512,'donec',1),(2511,'consequat',1),(2510,'tristique',1),(2509,'quam',1),(2508,'viverra',1),(2507,'sem',1),(2506,'congue',1),(2505,'mauris',1),(1490,'i',2),(2526,'laoreet',1),(2032,'sports',1),(1526,'gf',2),(1529,'sdfgsd',2),(1318,'farstad',2),(1362,'developer',1),(1316,'norway',1),(1361,'skien',1),(2504,'dapibus',1),(1360,'uberguru',1),(1359,'user',1),(1522,'jsdklgj',1),(2352,'arnold',1),(1531,'sdfgdsg',1),(1530,'fgsdgsd',1),(2918,'1999',1),(2917,'systems',1),(2190,'update',2),(2503,'nec',1),(2502,'wisi',1),(2501,'vel',1),(2500,'suspendisse',1),(2499,'facilisi',1),(2498,'nulla',1),(2497,'sapien',1),(2496,'non',1),(2495,'tincidunt',1),(2494,'id',1),(2493,'ullamcorper',1),(2492,'neque',1),(2491,'dolor',1),(2490,'ornare',1),(2489,'scelerisque',1),(2488,'interdum',1),(2487,'aenean',1),(2486,'duis',1),(2485,'dictumst',1),(2484,'platea',1),(2483,'habitasse',1),(2482,'hac',1),(2481,'fringilla',1),(2103,'at',2),(2529,'partners',1),(2101,'a',8),(2528,'customers',2),(2480,'nonummy',1),(2479,'diam',1),(2478,'tellus',1),(2477,'sed',1),(2476,'fermentum',1),(2475,'vestibulum',1),(2474,'lorem',1),(2473,'eget',1),(2472,'libero',1),(2471,'ut',1),(2470,'volutpat',1),(2469,'erat',1),(1499,'sdgf',2),(2570,'when',2),(2065,'56',1),(2064,'edit',1),(2067,'translator',1),(2066,'url',1),(2391,'undefeated',1),(1528,'piranha.no',1),(2569,'had',1),(2568,'fun',1),(2567,'decided',1),(2566,'soon',1),(1524,'skldg',1),(1337,'wenyue',1),(2392,'through',1),(2390,'anybody',2),(1509,'sdfg',5),(2575,'bed',1),(2044,'business',4),(2383,'less',1),(2382,'take',1),(1358,'administrator',1),(2393,'whole',1),(2381,'chanse',1),(1140,'bård',5),(2527,'page',1),(2468,'aliquam',1),(2467,'vitae',1),(2466,'mi',1),(2465,'magna',1),(2464,'tempor',1),(2463,'et',1),(2079,'in',9),(2462,'lectus',1),(2461,'rhoncus',1),(2460,'nunc',1),(2459,'lacus',1),(2458,'accumsan',1),(2457,'vehicula',1),(2456,'velit',1),(2070,'service',2),(2069,'about',3),(2388,'never',1),(2025,'this',12),(2565,'familiy',1),(2564,'start',1),(2563,'celebrations',1),(2562,'favourite',1),(2561,'beat',1),(2023,'what',6),(2024,'if',3),(2576,'sleep',1),(2389,'loosing',1),(2386,'ezpool',1),(2387,'internal',1),(2322,'department',1),(2560,'underdog',1),(2559,'am',1),(2316,'result',1),(2558,'after',2),(2557,'night',1),(2556,'late',1),(2315,'went',1),(2555,'finished',1),(2554,'very',2),(2406,'brains',1),(2314,'today',2),(2405,'heads',1),(2404,'5',1),(2403,'stakes',1),(2402,'4',1),(2313,'country',1),(2401,'giverns',1),(2400,'3',2),(2399,'dudes',1),(2380,'breakes',1),(2377,'pretty',1),(2378,'nasty',1),(2379,'stuff',1),(2398,'2',1),(2397,'standings',1),(2396,'doubt',1),(2395,'any',1),(2369,'entertaining',1),(2368,'quotes',1),(2367,'strange',1),(2366,'himself',1),(2365,'man',1),(2364,'with',4),(2363,'no',1),(2362,'question',1),(2361,'asking',1),(2360,'are',2),(2359,'fans',1),(2358,'t4',1),(2357,'happen',1),(2351,'text',2),(2350,'rest',2),(2349,'write',1),(2348,'close',3),(2340,'old',1),(2339,'going',2),(2338,'wants',2),(2337,'away',1),(2336,'moved',1),(2335,'people',4),(2334,'also',4),(2333,'locals',1),(2332,'friends',2),(2331,'benefitial',1),(2376,'rom',1),(2375,'cd',1),(2374,'singe',1),(2330,'others',1),(2312,'companies',2),(2329,'serve',1),(2328,'neighborhood',1),(2311,'largest',1),(2228,'we',6),(2227,'week',3),(2226,'every',1),(2225,'weekly',1),(2045,'entertainment',2),(1678,'news',7),(2385,'champion',2),(1680,'dfgl',1),(1681,'sdflg',1),(1682,'sdiofg',1),(1683,'usdoigfu',1),(1684,'osdigu',1),(1685,'iosdgf',1),(1686,'sdgfsd',1),(1687,'fg',2),(1688,'sdg',2),(2455,'elementum',1),(2454,'phasellus',1),(2373,'stores',1),(1693,'kjh',2),(1694,'kjlh',2),(1695,'kljh',1),(2453,'areas',1),(2452,'several',1),(2327,'your',3),(2326,'happens',1),(2325,'readers',2),(2324,'local',1),(2323,'created',2),(2451,'updated',1),(2450,'keep',1),(2449,'whats',1),(2310,'down',3),(2321,'ceo',1),(2320,'says',1),(2319,'diners',1),(2318,'good',3),(2317,'many',3),(1718,'sdfgsdgsd',1),(1719,'sdf',2),(1724,'polls',1),(1725,'is',14),(1726,'best',4),(1727,'matrix',1),(1728,'movies',2),(1729,'ghghj',1),(1730,'fghvmbnmbvnm',1),(1731,'fgn',1),(1732,'fdgh',1),(1733,'kløæ',1),(1734,'ølæ',1),(1735,'løæ',1),(1736,'hjlh',1),(1737,'hj',1),(2372,'web',1),(2014,'on',7),(2013,'all',9),(2394,'year',2),(2371,'indexes',1),(2370,'google',1),(1974,'gsdfg',1),(1973,'kewl',1),(2039,'released',1),(2038,'just',3),(2037,'was',5),(2035,'publish',1),(2384,'leauge',1),(2309,'goes',1),(2308,'jonas',1),(2307,'dons',1),(2306,'tell',1),(2305,'nothing',1),(2304,'before',3),(2303,'heard',2),(2302,'often',2),(2301,'ever',1),(2300,'that',6),(2299,'again',1),(2298,'over',2),(2297,'told',1),(2296,'they',2),(2295,'years',1),(2294,'five',1),(2293,'than',2),(2292,'been',3),(2291,'now',2),(2290,'right',2),(2289,'know',4),(2288,'let',5),(2287,'waiting',1),(2286,'worth',1),(2285,'friday',1),(2284,'last',2),(2283,'software',2),(2282,'awaited',1),(2281,'long',1),(2280,'their',2),(2279,'finally',1),(2278,'abc',1),(2277,'release',1),(2276,'final',2),(2275,'updates',2),(2274,'tuned',1),(2273,'stay',1),(2272,'has',3),(2271,'else',3),(2270,'nobody',1),(2269,'do',1),(2268,'able',1),(2267,'newspapers',1),(2266,'name',1),(2265,'early',1),(2264,'way',1),(2263,'state',1),(2262,'his',2),(2261,'first',2),(2260,'or',2),(2259,'fact',1),(2258,'say',2),(2257,'possible',1),(2256,'not',4),(2255,'but',6),(2254,'there',4),(2253,'out',2),(2252,'word',1),(2251,'president',1),(2250,'become',1),(2249,'he',2),(2248,'here',4),(2247,'find',2),(2246,'you',7),(2245,'etc',3),(2244,'divorce',1),(2243,'persons',1),(2242,'payed',1),(2241,'contracts',1),(2240,'faces',1),(2239,'beautiful',1),(2238,'most',1),(2237,'releases',1),(2236,'be',7),(2235,'place',1),(2234,'gossip',1),(2233,'world',2),(2232,'around',1),(2231,'an',3),(2230,'have',8),(2229,'will',9),(2224,'2003',2),(2916,'ez',1),(2222,'as',5),(2915,'&copy',1),(2914,'copyright',1),(2213,'fsdf',1),(2212,'sdfsdfsdf',1),(2211,'fsf',1),(2210,'sdfsdf@yahoo.com',1),(2209,'ddd',1),(2208,'sdsdd',1),(2207,'dfgfd',1),(2206,'dfgd',1),(2205,'dg',1),(2204,'dgdf',1),(2203,'fgdfg',1),(2347,'problems',1),(2346,'slang',1),(2345,'becoming',1),(2344,'where',3),(2343,'mann',1),(2342,'kjell',1),(2341,'usual',1),(2191,'1',11),(2189,'sdfklsfløsk',1),(2188,'jgslfjs',1),(2187,'wy@ez.no',1),(2186,'amazing',1),(2184,'use',2),(2183,'help',2),(2182,'us',3),(2181,'to',13),(2180,'how',4),(2179,'information',4),(2178,'contact',2),(2525,'quis',1),(2448,'share',1),(2447,'forms',1),(2446,'general',1),(2445,'kinds',1),(2444,'pages',1),(2443,'these',1),(1912,'kjlhkhkjhklhj',1),(1914,'kjhkjh',1),(1915,'sdfgsdfg',2),(1916,'sdfgsdfgsdfgds',1),(1917,'fgsd',1),(2442,'can',3),(2441,'why',1),(2440,'site',3),(2439,'website',1),(2058,'dfgds',1),(2008,'latest',4),(2059,'fgsdfg',1),(2913,'news_package',1),(2033,'new',3),(2057,'breaking',1),(2553,'gunrell',1),(2552,'terje',1),(2551,'competition',1),(2550,'dart',1),(2436,'like',2),(2549,'wins',1),(2548,'rider',1),(2531,'touch',1),(2532,'normal',1),(2533,'info',1),(2534,'telephone',1),(2535,'numbers',1),(2536,'fax',1),(2537,'e',1),(2538,'mail',1),(2539,'addresses',1),(2540,'visitors',2),(2541,'address',1),(2542,'snail',1),(2543,'used',1),(2544,'tip',1),(2578,'feeling',1),(2577,'nice',1),(2547,'season',2),(2579,'insisted',1),(2580,'face',2),(2581,'\"big',1),(2582,'summer\"',1),(2583,'attempt',1),(2584,'piece',1),(2585,'together',2),(2586,'make',1),(2587,'stronger',1),(2588,'challenge',1),(2589,'next',1),(2590,'s',1),(2591,'premiership',1),(2592,'crown',1),(2593,'disappointed',1),(2594,'finish',1),(2595,'league',1),(2596,'working',2),(2597,'hard',1),(2598,'everyone',2),(2599,'bid',1),(2600,'title',1),(2601,'well',1),(2602,'\"it',1),(2603,'greatest',1),(2604,'seasons',1),(2605,'front',1),(2606,'europe',1),(2607,'\"',2),(2608,'said',1),(2609,'\"i',2),(2610,'won',1),(2611,'open',2),(2612,'cup',1),(2613,'set',1),(2614,'my',1),(2615,'sights',1),(2616,'much',1),(2617,'higher',1),(2618,'overall',1),(2619,'ok',1),(2620,'\"hopefully',1),(2621,'even',1),(2622,'better',1),(2623,'really',1),(2624,'important',1),(2625,'summer',1),(2626,'me',1),(2627,'mates',1),(2628,'certainly',2),(2629,'big',2),(2630,'summer.\"',1),(2877,'company',1),(2876,'feature',1),(2875,'money',1),(2874,'pay',1),(2873,'would',1),(2872,'smile',1),(2871,'\"amazing',1),(2870,'such',1),(2869,'replies',1),(2868,'by',1),(2867,'met',1),(2866,'explained',1),(2865,'t',1),(2864,'don',1),(2863,'mentioned',1),(2862,'free',1),(2861,'talking',1),(2860,'were',1),(2859,'anyway',1),(2858,'during',1),(2857,'hope',1),(2856,'topics',1),(2855,'discuss',1),(2854,'meet',1),(2853,'inspiring',1),(2852,'interesting',1),(2851,'always',1),(2850,'up',1),(2849,'show',1),(2848,'happy',1),(2847,'re',1),(2846,'speaking',1),(2845,'great',1),(2844,'achieve',1),(2843,'minds',1),(2842,'creative',1),(2841,'community',1),(2840,'huge',1),(2839,'having',1),(2838,'benefits',1),(2837,'mention',1),(2836,'powerful',1),(2835,'realize',1),(2834,'impressed',1),(2833,'seem',1),(2832,'sure',1),(2831,'however',1),(2830,'licenses',1),(2829,'public',1),(2828,'unfamiliar',1),(2827,'still',1),(2826,'linux',1),(2825,'gnu',1),(2824,'success',1),(2823,'enormous',1),(2822,'despite',1),(2821,'large',1),(2820,'organizations',1),(2819,'various',1),(2818,'representing',1),(2817,'them',1),(2816,'austria',1),(2815,'germany',1),(2814,'mostly',1),(2813,'framework',1),(2812,'development',1),(2811,'system',1),(2810,'management',1),(2809,'source',1),(2808,'product',1),(2807,'main',1),(2806,'knowledge',1),(2805,'who',1),(2804,'seems',1),(2803,'already',1),(2802,'visited',1),(2801,'lot',1),(2800,'day',1),(2799,'barely',1),(2798,'expectations',1),(2797,'exceeding',1),(2796,'positive',1),(2795,'impressions',1),(2794,'our',1),(2793,'report',1),(2792,'contains',1),(2791,'following',1),(2790,'24th',1),(2789,'20th',1),(2788,'four',1),(2787,'time',1),(2786,'5th',1),(2785,'held',1),(2784,'telecommunications',1),(2783,'trade',1),(2782,'international',1),(2781,'2003\"',1),(2780,'\"top',1),(2779,'attending',1),(2778,'hall',1),(2777,'live',1),(2776,'reporting',1),(2775,'crew',1),(2774,'members',1),(2773,'some',1),(2772,'gunny',1),(2771,'tim',1),(2770,'fair',1),(2769,'top',1),(2878,'guy',1),(2879,'came',1),(2880,'neighboring',1),(2881,'stands',1),(2882,'watching',1),(2883,'presentation',1),(2884,'interested',1),(2885,'potential',1),(2886,'willing',1),(2887,'spend',1),(2888,'millions',1),(2889,'rigid',1),(2890,'solutions',1),(2891,'policy',1),(2892,'flexible',1),(2893,'eager',1),(2894,'talk',1),(2895,'wide',1),(2896,'range',1),(2897,'chance',1),(2898,'visit',1),(2899,'stop',1),(2900,'stand',1),(2901,'prepared',1),(2902,'sit',1),(2903,'representatives',1),(2904,'receive',1),(2905,'hands',1),(2906,'demonstration',1);
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
INSERT INTO ezsession VALUES ('d5c5ffc528b64e5e660b37ccc5640ec4',1070102886,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069843681;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069843681;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069843681;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:5:{i:0;a:5:{s:2:\"id\";s:3:\"391\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"392\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"393\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"394\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}i:4;a:5:{s:2:\"id\";s:3:\"395\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:11:\"versionread\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069843682;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:392;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"309\";s:9:\"policy_id\";s:3:\"392\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:309;a:9:{i:0;a:3:{s:2:\"id\";s:3:\"636\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"637\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"638\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"639\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"640\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"641\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"642\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"643\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:2:\"26\";}i:8;a:3:{s:2:\"id\";s:3:\"644\";s:13:\"limitation_id\";s:3:\"309\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}'),('2f08430f259ff29ceb7c0995839dd5fd',1070114985,'eZUserGroupsCache_Timestamp|i:1069855195;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069855195;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069855195;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069855196;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:11:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"25\";s:4:\"name\";s:4:\"Poll\";}i:10;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:7:\"Comment\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|s:2:\"on\";');
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
INSERT INTO eztipafriend_counter VALUES (182,1),(193,1),(205,4);
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
INSERT INTO ezurl VALUES (1,'http://ez.no',1068713677,1068713677,1,0,'dfcdb471b240d964dc3f57b998eb0533'),(2,'http://www.vg.no',1068718860,1068718860,1,0,'26f1033e463720ae68742157890bc752'),(3,'http://www.sina.com.cn',1068718957,1068718957,1,0,'4f12a25ee6cc3d6123be77df850e343e'),(4,'http://download.hzinfo.com',1068719250,1068719250,1,0,'4c9c884a40d63b7d9555ffb77fe75466');
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
INSERT INTO ezurl_object_link VALUES (1,768,1),(2,800,1),(3,807,1),(4,814,1);
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(193,'news/politics','70683aff56043252ef658ccbfc6afa31','content/view/full/188',1,0,0),(125,'discussions/forum_main_group/music_discussion/latest_msg_not_sticky','70cf693961dcdd67766bf941c3ed2202','content/view/full/130',1,0,0),(126,'discussions/forum_main_group/music_discussion/not_sticky_2','969f470c93e2131a0884648b91691d0b','content/view/full/131',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,203,0),(124,'discussions/forum_main_group/music_discussion/new_topic_sticky/reply','f3dd8b6512a0b04b426ef7d7307b7229','content/view/full/129',1,0,0),(194,'news/politics/breaking_news','d0a69056576563cde94a120a52328d88','content/view/full/182',1,0,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,219,0),(123,'discussions/forum_main_group/music_discussion/new_topic_sticky','bf37b4a370ddb3935d0625a5b348dd20','content/view/full/128',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,203,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,203,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(127,'discussions/forum_main_group/music_discussion/important_sticky','2f16cf3039c97025a43f23182b4b6d60','content/view/full/132',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0),(187,'news/breaking_news','d8509229115663eb7fdf2bbbee3f255f','content/view/full/182',1,194,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(104,'discussions/music_discussion','09533dfccc8477debe545d31bccf391f','content/view/full/114',1,149,0),(105,'discussions/forum_main_group/music_discussion/this_is_a_new_topic','cec6b1593bf03079990a89a3fdc60c56','content/view/full/112',1,0,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(107,'discussions/sports_discussion','c551943f4df3c58a693f8ba55e9b6aeb','content/view/full/115',1,151,0),(117,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/foo_bar','741cdf9f1ee1fa974ea7ec755f538271','content/view/full/122',1,0,0),(111,'discussions/forum_main_group/sports_discussion/football','6e9c09d390322aa44bb5108b93f5f17c','content/view/full/119',1,0,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,203,0),(114,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/my_reply','1e03a7609698aa8a98dccf1178df0e6f','content/view/full/120',1,0,0),(118,'discussions/forum_main_group/music_discussion/what_about_pop','c4ebc99b2ed9792d1aee0e5fe210b852','content/view/full/123',1,0,0),(119,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic','6c20d2df5a828dcdb6a4fcb4897bb643','content/view/full/124',1,0,0),(120,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','de98a1bb645ea84919a5e34688ff84e2','content/view/full/125',1,0,0),(128,'discussions/forum_main_group/sports_discussion/football/reply_2','13a443b7e046bb36831640f1d19e33d9','content/view/full/133',1,0,0),(130,'discussions/forum_main_group/music_discussion/lkj_ssssstick','75ee87c770e4e8be9d44200cdb71d071','content/view/full/135',1,0,0),(131,'discussions/forum_main_group/music_discussion/foo','12c58f35c1114deeb172aba728c50ca8','content/view/full/136',1,0,0),(132,'discussions/forum_main_group/music_discussion/lkj_ssssstick/reply','6040856b4ec5bcc1c699d95020005be5','content/view/full/137',1,0,0),(135,'discussions/forum_main_group/music_discussion/lkj_ssssstick/uyuiyui','4c48104ea6e5ec2a78067374d9561fcb','content/view/full/140',1,0,0),(136,'discussions/forum_main_group/music_discussion/test2','53f71d4ff69ffb3bf8c8ccfb525eabd3','content/view/full/141',1,0,0),(137,'discussions/forum_main_group/music_discussion/t4','5da27cda0fbcd5290338b7d22cfd730c','content/view/full/142',1,0,0),(138,'discussions/forum_main_group/music_discussion/lkj_ssssstick/klj_jkl_klj','9ae60fa076882d6807506c2232143d27','content/view/full/143',1,0,0),(139,'discussions/forum_main_group/music_discussion/test2/retest2','a17d07fbbd2d1b6d0fbbf8ca1509cd01','content/view/full/144',1,0,0),(141,'discussions/forum_main_group/music_discussion/lkj_ssssstick/my_reply','1f95000d1f993ffa16a0cf83b78515bf','content/view/full/146',1,0,0),(142,'discussions/forum_main_group/music_discussion/lkj_ssssstick/retest','0686f14064a420e6ee95aabf89c4a4f2','content/view/full/147',1,0,0),(144,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf','21f0ee2122dd5264192adc15c1e69c03','content/view/full/149',1,0,0),(146,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/dfghd_fghklj','460d30ba47855079ac8605e1c8085993','content/view/full/151',1,0,0),(201,'news/business/latest_business_update','dd5b261d06692ab08f2fcaa55e8e1d53','content/view/full/192',1,0,0),(202,'news/entertainment/arnold_for_governor','bb8a6d9337b49e5432eaa5e3fd2f9e4f','content/view/full/193',1,0,0),(149,'discussions/forum_main_group/music_discussion','a1a79985f113d5b05b22c9686b46b175','content/view/full/114',1,0,0),(150,'discussions/music_discussion/*','2ec2a3bfcf01ad3f1323390ab26dfeac','discussions/forum_main_group/music_discussion/{1}',1,0,1),(151,'discussions/forum_main_group/sports_discussion','b68c5a82b8b2035eeee5788cb223bb7e','content/view/full/115',1,0,0),(152,'discussions/sports_discussion/*','7acbf48218ca6e1d80c267911860d34f','discussions/forum_main_group/sports_discussion/{1}',1,0,1),(153,'about_me','50793f253d2dc015e93a2f75163b0894','content/view/full/127',1,219,0),(160,'blogs/computers/special_things_happened_today/brd_farstad','4d1dddb2000bdf69e822fb41d4000919','content/view/full/157',1,0,0),(161,'blogs/computers/special_things_happened_today/bbb','afc9fd5431105082994247c0ae0992b3','content/view/full/158',1,0,0),(200,'news/entertainment','1c0fefbd843e7961bb8062c96e128683','content/view/full/191',1,0,0),(212,'news/entertainment/will_he_become_president','9e85708a4fa2190e5bbea017f7574938','content/view/full/201',1,0,0),(186,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/181',1,0,0),(188,'news/new_ez_publish_package','21e270dc6369c28ec9eead1ceb474af8','content/view/full/183',1,217,0),(189,'news/politics/breaking_news/kewl_news','ed88dc6a9315d5b9d832ce7282121233','content/view/full/184',1,0,0),(168,'blogs/computers/special_things_happened_today/brd','40f4dda88233928fac915274a90476b5','content/view/full/165',1,0,0),(170,'blogs/computers/special_things_happened_today/kjh','0cca438ee3d1d3b2cdfaa9d45dbac2a7','content/view/full/167',1,0,0),(197,'news/sports','6123ea62d93ed020f31e6e7920b10d0f','content/view/full/189',1,0,0),(198,'news/sports/new_ez_publish_package','7c8632a535a781907dbac14d16d83570','content/view/full/183',1,217,0),(195,'news/breaking_news/*','1c84412822823453849fbfd423dce066','news/politics/breaking_news/{1}',1,0,1),(196,'news/technology/google_indexes','25792456c7959c09a7cafef33e2e49a1','content/view/full/186',1,0,0),(175,'blogs/computers/new_big_discovery_today/brd','2aee5cbd251dbc484e78fba61e5bb7cf','content/view/full/172',1,0,0),(176,'polls','952e8cf2c863b8ddc656bac6ad0b729b','content/view/full/173',1,0,0),(211,'news/entertainment/entertainment_weekly','d4ca4e7aaa28c898b00e05522ed3d1b1','content/view/full/200',1,0,0),(178,'polls/which_one_is_the_best_of_matrix_movies','bb0ff8ca87eb02ff2219a32c5c73b7f4','content/view/full/174',1,0,0),(179,'blogs/computers/new_big_discovery_today/ghghj','cd10884873caf4a20621b35199f331c4','content/view/full/175',1,0,0),(191,'news/google_indexes','50a1329cdeef629af68254e03c9fadd3','content/view/full/186',1,196,0),(192,'news/technology','19fa0ec362258a0c209193afd31d8040','content/view/full/187',1,0,0),(182,'setup/look_and_feel/blog','a0aa455a1c24b5d1d0448546c83836cf','content/view/full/54',1,203,0),(183,'blogs/entertainment/a_pirates_life/kjlh','dbf2c1455eff8c6100181582298d197f','content/view/full/178',1,0,0),(184,'blogs/entertainment/a_pirates_life/kjhkjh','e73acc89936bc771971a97eb45d51c66','content/view/full/179',1,0,0),(199,'news/business','32407e4d99060ebf72baf5f3fcd15130','content/view/full/190',1,0,0),(203,'setup/look_and_feel/news','a00d016e1fa55a6285b2e58580a81591','content/view/full/54',1,0,0),(204,'about_this_service','a05dbbe0742bbd70c3c4af8dd994e805','content/view/full/127',1,219,0),(205,'contact_information','ed8e2b815e2f24e61286fb45b86d3868','content/view/full/194',1,0,0),(206,'help','657f8b8da628ef83cf69101b6817150a','content/view/full/195',1,0,0),(207,'news/politics/breaking_news/amazing','7b29cd3538b23e040fcd73b0aece42b9','content/view/full/196',1,0,0),(208,'news/business/business_is','4026dcfc2998ab67a010c354201a7c03','content/view/full/197',1,215,0),(209,'news/politics/breaking_news/fgdfg','76bbf2611ecc1761cdf7841df039cadd','content/view/full/198',1,0,0),(210,'news/politics/breaking_news/sdsdd','fed2296e54048a4a01247a0e21141c39','content/view/full/199',1,0,0),(213,'news/technology/final_release_of_abc','0f094451a41d9bbbf2ec5e412cbdfdea','content/view/full/202',1,0,0),(214,'news/business/dons_jonas_goes_down','907abedce4248de6040ef2797f580b21','content/view/full/203',1,0,0),(215,'news/business/business_as_usual','b39853864be2a521017ed2ee5c5b3d0e','content/view/full/197',1,0,0),(216,'news/politics/arnold_for_governor','b42b48edf57b3ddca3413eec95765358','content/view/full/204',1,0,0),(217,'news/sports/leauge_champion','846e90f12c3474ef869967621ddb9a3a','content/view/full/183',1,0,0),(218,'news/sports/rider_wins_dart_competition','c07ac268c486dc1df3f140aabe96304a','content/view/full/205',1,0,0),(219,'about_this_website','7962ee24a05f526a5cd231165095edc8','content/view/full/127',1,0,0),(222,'news/technology/new_top_fair','4996cc29e5bc760cfc5ed358d4f8754d','content/view/full/208',1,0,0),(221,'polls/what_season_is_the_best','9c93a6dc8b98dfe90e0d3af17fc62342','content/view/full/207',1,0,0);
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

