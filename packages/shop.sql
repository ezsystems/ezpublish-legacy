-- MySQL dump 10.2
--
-- Host: localhost    Database: shop
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
INSERT INTO ezcontentbrowserecent VALUES (90,14,185,1069752921,'DVD'),(35,111,99,1067006746,'foo bar corp'),(65,149,135,1068126974,'lkj ssssstick'),(49,10,12,1069246037,'Guest accounts'),(64,206,135,1068123651,'lkj ssssstick');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1066907423),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1069678307),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069416268),(12,1,'File','file','<name>',14,14,1052385472,1067353799),(23,0,'Product','product','<name>',14,14,1068472452,1068557806),(24,0,'Feedback form','feedback_form','<name>',14,14,1068554718,1068555117),(25,0,'Review','review','<topic>',14,14,1068565707,1068648892);
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(218,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(215,0,25,'topic','Topic','ezstring',0,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(216,0,25,'description','Description','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(217,0,25,'rating','Rating','ezinteger',1,1,3,0,5,0,3,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(201,0,23,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(202,0,23,'product_number','Product number','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(203,0,23,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','','',0,1),(204,0,23,'image','Image','ezimage',0,0,4,1,0,0,0,0,0,0,0,'','','','','',0,1),(205,0,23,'price','Price','ezprice',0,0,5,1,0,0,0,1,0,0,0,'','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(210,0,24,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1),(209,0,24,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1),(208,0,24,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1),(207,0,24,'description','Description','ezxmltext',1,0,2,15,0,0,0,0,0,0,0,'','','','','',0,1),(206,0,24,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
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
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(21,1,1,'Content'),(20,0,1,'Content'),(21,0,1,'Content'),(23,0,1,'Content'),(24,0,1,'Content'),(25,0,1,'Content');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Shop',8,0,1033917596,1069686123,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',7,0,1033920830,1068556425,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',11,0,1066384365,1068640429,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',11,0,1066388816,1068640502,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,1,1,'News',1,0,1066398020,1066398020,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'Shop',59,0,1066643397,1069839572,1,''),(160,14,1,2,'News bulletin October',2,0,1068047455,1069686818,1,''),(161,14,1,10,'Shipping and returns',4,0,1068047603,1069688507,1,''),(219,14,1,10,'Privacy notice',2,0,1068542692,1069688136,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(213,14,1,1,'Products',2,0,1068473231,1068556203,1,''),(115,14,11,14,'Cache',5,0,1066991725,1068640475,1,''),(116,14,11,14,'URL translator',4,0,1066992054,1068640525,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(246,14,1,4,'New User',1,0,0,0,0,''),(263,14,1,23,'eZ publish basics',1,0,1069752520,1069752520,1,''),(260,14,1,23,'Troll',1,0,1069752252,1069752252,1,''),(257,14,1,1,'Books',1,0,1069751025,1069751025,1,''),(187,14,1,4,'New User',1,0,0,0,0,''),(189,14,1,4,'New User',1,0,0,0,0,''),(225,14,1,23,'New Product',1,0,0,0,0,''),(226,14,1,23,'New Product',1,0,0,0,0,''),(224,14,1,23,'New Product',1,0,0,0,0,''),(223,14,1,23,'New Product',1,0,0,0,0,''),(222,14,1,24,'Contact us',2,0,1068554919,1069688573,1,''),(258,14,1,1,'Cars',2,0,1069751059,1069751108,1,''),(259,14,1,1,'DVD',1,0,1069751462,1069751462,1,''),(220,14,1,10,'Conditions of use',2,0,1068542738,1069688214,1,''),(265,14,1,23,'Action DVD',1,0,1069752921,1069752921,1,''),(264,14,1,23,'Music DVD',1,0,1069752759,1069752759,1,''),(240,14,1,25,'New Review',1,0,0,0,0,''),(245,14,1,4,'New User',1,0,0,0,0,''),(247,14,1,4,'New User',1,0,0,0,0,''),(250,14,1,2,'News bulletin November',1,0,1069687269,1069687269,1,''),(251,14,1,1,'Cords',1,0,1069687877,1069687877,1,''),(252,14,1,23,'1 meter cord',1,0,1069687927,1069687927,1,''),(253,14,1,23,'5 meter cord',1,0,1069687961,1069687961,1,''),(254,14,1,2,'A new cord',1,0,1069688677,1069688677,1,''),(262,14,1,23,'Summer book',1,0,1069752445,1069752445,1,''),(261,14,1,23,'Ferrari',1,0,1069752332,1069752332,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(103,'eng-GB',11,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',11,43,152,'Classes',0,0,0,0,'classes','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',11,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel.png\"\n         original_filename=\"look_and_feel.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',11,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(767,'eng-GB',1,213,4,'Hardware',0,0,0,0,'hardware','ezstring'),(521,'eng-GB',1,160,177,'',0,0,0,0,'','ezinteger'),(516,'eng-GB',1,160,1,'News bulletin',0,0,0,0,'news bulletin','ezstring'),(517,'eng-GB',1,160,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(518,'eng-GB',1,160,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(519,'eng-GB',1,160,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin.\"\n         suffix=\"\"\n         basename=\"news_bulletin\"\n         dirpath=\"var/forum/storage/images/news/news_bulletin/519-1-eng-GB\"\n         url=\"var/forum/storage/images/news/news_bulletin/519-1-eng-GB/news_bulletin.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(520,'eng-GB',1,160,123,'',0,0,0,0,'','ezboolean'),(154,'eng-GB',52,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',52,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(152,'eng-GB',50,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.jpg\"\n         suffix=\"jpg\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB/my_shop.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB/my_shop_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB/my_shop_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB/my_shop_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',50,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(940,'eng-GB',1,250,1,'News bulletin November',0,0,0,0,'news bulletin november','ezstring'),(519,'eng-GB',2,160,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin_october.\"\n         suffix=\"\"\n         basename=\"news_bulletin_october\"\n         dirpath=\"var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB\"\n         url=\"var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB/news_bulletin_october.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"519\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(520,'eng-GB',2,160,123,'',1,0,0,1,'','ezboolean'),(521,'eng-GB',2,160,177,'',0,0,0,0,'','ezinteger'),(627,'eng-GB',1,189,8,'',0,0,0,0,'','ezstring'),(628,'eng-GB',1,189,9,'',0,0,0,0,'','ezstring'),(629,'eng-GB',1,189,12,'',0,0,0,0,'','ezuser'),(621,'eng-GB',1,187,8,'',0,0,0,0,'','ezstring'),(622,'eng-GB',1,187,9,'',0,0,0,0,'','ezstring'),(623,'eng-GB',1,187,12,'',0,0,0,0,'','ezuser'),(785,'eng-GB',2,220,140,'Conditions of use',0,0,0,0,'conditions of use','ezstring'),(786,'eng-GB',2,220,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The conditions of use is where you state how people shall act and behave in your webshop. </paragraph>\n  <paragraph>It also states the policy you have towards the customer.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(951,'eng-GB',1,252,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"1_meter_cord.\"\n         suffix=\"\"\n         basename=\"1_meter_cord\"\n         dirpath=\"var/shop/storage/images/products/cords/1_meter_cord/951-1-eng-GB\"\n         url=\"var/shop/storage/images/products/cords/1_meter_cord/951-1-eng-GB/1_meter_cord.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069687893\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(406,'eng-GB',1,129,177,'',0,0,0,0,'','ezinteger'),(401,'eng-GB',1,129,1,'New article',0,0,0,0,'new article','ezstring'),(402,'eng-GB',1,129,120,'',1045487555,0,0,0,'','ezxmltext'),(403,'eng-GB',1,129,121,'',1045487555,0,0,0,'','ezxmltext'),(404,'eng-GB',1,129,122,'',0,0,0,0,'','ezimage'),(405,'eng-GB',1,129,123,'',0,0,0,0,'','ezboolean'),(151,'eng-GB',52,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(524,'eng-GB',4,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"shipping_and_returns.\"\n         suffix=\"\"\n         basename=\"shipping_and_returns\"\n         dirpath=\"var/shop/storage/images/shipping_and_returns/524-4-eng-GB\"\n         url=\"var/shop/storage/images/shipping_and_returns/524-4-eng-GB/shipping_and_returns.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(522,'eng-GB',4,161,140,'Shipping and returns',0,0,0,0,'shipping and returns','ezstring'),(523,'eng-GB',4,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Shipping and returns are always one of the most important pages in your webshop. Even if people are not returning their products to you they want to know is this is possible. It is kind of a guarantee on their bahalf. It is also a way for you to show that you are professional.</paragraph>\n  <paragraph>Normally a page like this contains information about:</paragraph>\n  <paragraph>\n    <line>Delivery Time</line>\n    <line>Cooling-off Period/Return Rights</line>\n    <line>Faulty or Defective Goods</line>\n    <line>Order Cancellation by the Customer</line>\n    <line>Order Cancellation by Us</line>\n    <line>Replacement Goods</line>\n    <line>Exceptions</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',3,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"shipping_and_returns.\"\n         suffix=\"\"\n         basename=\"shipping_and_returns\"\n         dirpath=\"var/shop/storage/images/shipping_and_returns/524-3-eng-GB\"\n         url=\"var/shop/storage/images/shipping_and_returns/524-3-eng-GB/shipping_and_returns.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(522,'eng-GB',2,161,140,'Shipping and returns',0,0,0,0,'shipping and returns','ezstring'),(1,'eng-GB',7,1,4,'Shop',0,0,0,0,'shop','ezstring'),(2,'eng-GB',7,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(768,'eng-GB',1,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Hardware products</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',52,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.jpg\"\n         suffix=\"jpg\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB/my_shop.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"51\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB/my_shop_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB/my_shop_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB/my_shop_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',52,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(151,'eng-GB',51,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(125,'eng-GB',2,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',2,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(152,'eng-GB',51,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.jpg\"\n         suffix=\"jpg\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB/my_shop.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"50\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB/my_shop_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB/my_shop_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB/my_shop_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(389,'eng-GB',1,126,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(390,'eng-GB',1,126,123,'',0,0,0,0,'','ezboolean'),(391,'eng-GB',1,126,177,'',0,0,0,0,'','ezinteger'),(392,'eng-GB',1,127,1,'New article',0,0,0,0,'new article','ezstring'),(393,'eng-GB',1,127,120,'',1045487555,0,0,0,'','ezxmltext'),(394,'eng-GB',1,127,121,'',1045487555,0,0,0,'','ezxmltext'),(395,'eng-GB',1,127,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(396,'eng-GB',1,127,123,'',0,0,0,0,'','ezboolean'),(397,'eng-GB',1,127,177,'',0,0,0,0,'','ezinteger'),(152,'eng-GB',53,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop.gif\"\n         original_filename=\"qxllogo.gif\"\n         mime_type=\"original\"\n         width=\"196\"\n         height=\"67\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069165845\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069165849\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069165849\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"169\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069322201\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',53,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(386,'eng-GB',1,126,1,'tt',0,0,0,0,'tt','ezstring'),(387,'eng-GB',1,126,120,'',1045487555,0,0,0,'','ezxmltext'),(388,'eng-GB',1,126,121,'',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',54,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(437,'eng-GB',59,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(150,'eng-GB',50,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',51,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',52,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',53,56,157,'My shop',0,0,0,0,'','ezinisetting'),(152,'eng-GB',54,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop.gif\"\n         original_filename=\"qxllogo.gif\"\n         mime_type=\"original\"\n         width=\"196\"\n         height=\"67\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069165845\">\n  <original attribute_id=\"152\"\n            attribute_version=\"53\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069165849\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069165849\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"170\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069416749\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',59,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"shop.gif\"\n         suffix=\"gif\"\n         basename=\"shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop.gif\"\n         original_filename=\"webshop.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069839028\">\n  <original attribute_id=\"152\"\n            attribute_version=\"58\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069839029\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069839029\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069843091\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(669,'eng-GB',52,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',57,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"shop.gif\"\n         suffix=\"gif\"\n         basename=\"shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop.gif\"\n         original_filename=\"shop.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069421350\">\n  <original attribute_id=\"152\"\n            attribute_version=\"56\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069750500\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',56,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',56,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',56,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',56,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(911,'eng-GB',56,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(153,'eng-GB',57,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',57,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',57,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',57,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(153,'eng-GB',55,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(152,'eng-GB',55,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop.gif\"\n         original_filename=\"shop.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069421350\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069421433\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',58,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"shop.gif\"\n         suffix=\"gif\"\n         basename=\"shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop.gif\"\n         original_filename=\"webshop.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069839028\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069839029\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069839029\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069839057\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(911,'eng-GB',57,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(516,'eng-GB',2,160,1,'News bulletin October',0,0,0,0,'news bulletin october','ezstring'),(1,'eng-GB',8,1,4,'Shop',0,0,0,0,'shop','ezstring'),(2,'eng-GB',8,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"shop.gif\"\n         suffix=\"gif\"\n         basename=\"shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop.gif\"\n         original_filename=\"shop.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069421350\">\n  <original attribute_id=\"152\"\n            attribute_version=\"55\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(458,'eng-GB',1,143,152,'',0,0,0,0,'','ezstring'),(459,'eng-GB',1,143,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(460,'eng-GB',1,143,154,'',0,0,0,0,'','eztext'),(461,'eng-GB',1,143,155,'',0,0,0,0,'','ezstring'),(462,'eng-GB',1,144,152,'',0,0,0,0,'','ezstring'),(463,'eng-GB',1,144,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(464,'eng-GB',1,144,154,'',0,0,0,0,'','eztext'),(465,'eng-GB',1,144,155,'',0,0,0,0,'','ezstring'),(466,'eng-GB',1,145,152,'',0,0,0,0,'','ezstring'),(467,'eng-GB',1,145,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(468,'eng-GB',1,145,154,'',0,0,0,0,'','eztext'),(469,'eng-GB',1,145,155,'',0,0,0,0,'','ezstring'),(1006,'eng-GB',1,265,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"action_dvd.\"\n         suffix=\"\"\n         basename=\"action_dvd\"\n         dirpath=\"var/shop/storage/images/products/dvd/action_dvd/1006-1-eng-GB\"\n         url=\"var/shop/storage/images/products/dvd/action_dvd/1006-1-eng-GB/action_dvd.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752769\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1007,'eng-GB',1,265,205,'',0,12,0,0,'','ezprice'),(972,'eng-GB',1,257,4,'Books',0,0,0,0,'books','ezstring'),(977,'eng-GB',1,259,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(976,'eng-GB',1,259,4,'DVD',0,0,0,0,'dvd','ezstring'),(974,'eng-GB',2,258,4,'Cars',0,0,0,0,'cars','ezstring'),(975,'eng-GB',2,258,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(973,'eng-GB',1,257,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(974,'eng-GB',1,258,4,'Flowers',0,0,0,0,'flowers','ezstring'),(975,'eng-GB',1,258,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(912,'eng-GB',1,246,8,'',0,0,0,0,'','ezstring'),(913,'eng-GB',1,246,9,'',0,0,0,0,'','ezstring'),(914,'eng-GB',1,246,12,'',0,0,0,0,'','ezuser'),(518,'eng-GB',2,160,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We release a new website. As you all can see it is a great step forward from the old site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(154,'eng-GB',55,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',55,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',55,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(911,'eng-GB',55,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(919,'eng-GB',1,247,8,'',0,0,0,0,'','ezstring'),(920,'eng-GB',1,247,9,'',0,0,0,0,'','ezstring'),(921,'eng-GB',1,247,12,'',0,0,0,0,'','ezuser'),(963,'eng-GB',1,254,177,'',0,0,0,0,'','ezinteger'),(517,'eng-GB',2,160,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here are the latest news from this webshop. We will publish these news as soon as we have new products, new releases and important information to tell. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(787,'eng-GB',2,220,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"conditions_of_use.\"\n         suffix=\"\"\n         basename=\"conditions_of_use\"\n         dirpath=\"var/shop/storage/images/conditions_of_use/787-2-eng-GB\"\n         url=\"var/shop/storage/images/conditions_of_use/787-2-eng-GB/conditions_of_use.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"787\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(522,'eng-GB',3,161,140,'Shipping and returns',0,0,0,0,'shipping and returns','ezstring'),(523,'eng-GB',3,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Delivery Time</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(784,'eng-GB',2,219,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"privacy_notice.\"\n         suffix=\"\"\n         basename=\"privacy_notice\"\n         dirpath=\"var/shop/storage/images/privacy_notice/784-2-eng-GB\"\n         url=\"var/shop/storage/images/privacy_notice/784-2-eng-GB/privacy_notice.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"784\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(782,'eng-GB',2,219,140,'Privacy notice',0,0,0,0,'privacy notice','ezstring'),(783,'eng-GB',2,219,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>In the privacy notice you should write about how secure you handle information you collect from your customers. What do you do with it and what do you not use it for?</paragraph>\n  <paragraph>Normally people are very interested in knowing about this and it is therefore very important that you state this as clear as possible. It can be the make or breake of your webshop.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(792,'eng-GB',2,222,207,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A contact page is where you let your readers, customers, partners etc find information on how to get in touch with you. </paragraph>\n  <paragraph>Normal info to have here is: telephone numbers, fax numbers, e-mail addresses, visitors address and snail mail address. </paragraph>\n  <paragraph>This site is also often used for people that wants to tip the site on news, updates etc. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(793,'eng-GB',2,222,208,'',0,0,0,0,'','ezstring'),(794,'eng-GB',2,222,209,'',0,0,0,0,'','ezstring'),(795,'eng-GB',2,222,210,'',0,0,0,0,'','eztext'),(958,'eng-GB',1,254,1,'A new cord',0,0,0,0,'a new cord','ezstring'),(959,'eng-GB',1,254,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The have finally received some 5 meter cords from our supplier</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(960,'eng-GB',1,254,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>They are available from our shop for as low as £13. Get i while you can.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(961,'eng-GB',1,254,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"a_new_cord.\"\n         suffix=\"\"\n         basename=\"a_new_cord\"\n         dirpath=\"var/shop/storage/images/news/a_new_cord/961-1-eng-GB\"\n         url=\"var/shop/storage/images/news/a_new_cord/961-1-eng-GB/a_new_cord.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069688601\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(962,'eng-GB',1,254,123,'',0,0,0,0,'','ezboolean'),(1005,'eng-GB',1,265,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Clips from the best action movies from the leading actors from Hollywood. 3 hours of non-stop action from back to back.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1004,'eng-GB',1,265,202,'',0,0,0,0,'','ezstring'),(1003,'eng-GB',1,265,201,'Action DVD',0,0,0,0,'action dvd','ezstring'),(1002,'eng-GB',1,264,205,'',0,6,0,0,'','ezprice'),(1001,'eng-GB',1,264,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"music_dvd.\"\n         suffix=\"\"\n         basename=\"music_dvd\"\n         dirpath=\"var/shop/storage/images/products/dvd/music_dvd/1001-1-eng-GB\"\n         url=\"var/shop/storage/images/products/dvd/music_dvd/1001-1-eng-GB/music_dvd.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752535\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1000,'eng-GB',1,264,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A collection of music from the year 2003. The best of the best. All top of the charts from Top 100.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(998,'eng-GB',1,264,201,'Music DVD',0,0,0,0,'music dvd','ezstring'),(999,'eng-GB',1,264,202,'60897',0,0,0,0,'60897','ezstring'),(988,'eng-GB',1,262,201,'Summer book',0,0,0,0,'summer book','ezstring'),(989,'eng-GB',1,262,202,'1324',0,0,0,0,'1324','ezstring'),(990,'eng-GB',1,262,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The book is about all the colors and smells of summer. The book is packed with picures of the beautiful landscape in Norway.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(991,'eng-GB',1,262,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"summer_book.\"\n         suffix=\"\"\n         basename=\"summer_book\"\n         dirpath=\"var/shop/storage/images/products/books/summer_book/991-1-eng-GB\"\n         url=\"var/shop/storage/images/products/books/summer_book/991-1-eng-GB/summer_book.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752350\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(992,'eng-GB',1,262,205,'',0,79,0,0,'','ezprice'),(993,'eng-GB',1,263,201,'eZ publish basics',0,0,0,0,'ez publish basics','ezstring'),(994,'eng-GB',1,263,202,'123414',0,0,0,0,'123414','ezstring'),(995,'eng-GB',1,263,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Everything you need to know about eZ publish. All steps from download to the finished site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(996,'eng-GB',1,263,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_publish_basics.\"\n         suffix=\"\"\n         basename=\"ez_publish_basics\"\n         dirpath=\"var/shop/storage/images/products/books/ez_publish_basics/996-1-eng-GB\"\n         url=\"var/shop/storage/images/products/books/ez_publish_basics/996-1-eng-GB/ez_publish_basics.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752456\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(523,'eng-GB',2,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',2,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"shipping_and_returns.\"\n         suffix=\"\"\n         basename=\"shipping_and_returns\"\n         dirpath=\"var/shop/storage/images/shipping_and_returns/524-2-eng-GB\"\n         url=\"var/shop/storage/images/shipping_and_returns/524-2-eng-GB/shipping_and_returns.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(782,'eng-GB',1,219,140,'Privacy notice',0,0,0,0,'privacy notice','ezstring'),(783,'eng-GB',1,219,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(784,'eng-GB',1,219,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"privacy_notice.\"\n         suffix=\"\"\n         basename=\"privacy_notice\"\n         dirpath=\"var/shop/storage/images/privacy_notice/784-1-eng-GB\"\n         url=\"var/shop/storage/images/privacy_notice/784-1-eng-GB/privacy_notice.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(785,'eng-GB',1,220,140,'Conditions of use',0,0,0,0,'conditions of use','ezstring'),(786,'eng-GB',1,220,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(787,'eng-GB',1,220,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"conditions_of_use.\"\n         suffix=\"\"\n         basename=\"conditions_of_use\"\n         dirpath=\"var/shop/storage/images/conditions_of_use/787-1-eng-GB\"\n         url=\"var/shop/storage/images/conditions_of_use/787-1-eng-GB/conditions_of_use.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(767,'eng-GB',2,213,4,'Products',0,0,0,0,'products','ezstring'),(768,'eng-GB',2,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Our products</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',6,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',6,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',6,14,12,'',0,0,0,0,'','ezuser'),(952,'eng-GB',1,252,205,'',0,9,0,0,'','ezprice'),(953,'eng-GB',1,253,201,'5 meter cord',0,0,0,0,'5 meter cord','ezstring'),(954,'eng-GB',1,253,202,'34555',0,0,0,0,'34555','ezstring'),(955,'eng-GB',1,253,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This cord is five meters long and works for all machines</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(956,'eng-GB',1,253,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"5_meter_cord.\"\n         suffix=\"\"\n         basename=\"5_meter_cord\"\n         dirpath=\"var/shop/storage/images/products/cords/5_meter_cord/956-1-eng-GB\"\n         url=\"var/shop/storage/images/products/cords/5_meter_cord/956-1-eng-GB/5_meter_cord.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069687936\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(957,'eng-GB',1,253,205,'',0,13,0,0,'','ezprice'),(28,'eng-GB',7,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',7,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',7,14,12,'',0,0,0,0,'','ezuser'),(978,'eng-GB',1,260,201,'Troll',0,0,0,0,'troll','ezstring'),(979,'eng-GB',1,260,202,'',0,0,0,0,'','ezstring'),(980,'eng-GB',1,260,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Troll was the first - and so far the only - car made in Norway. Only five cars left the factory in total. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(981,'eng-GB',1,260,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"troll.\"\n         suffix=\"\"\n         basename=\"troll\"\n         dirpath=\"var/shop/storage/images/products/cars/troll/981-1-eng-GB\"\n         url=\"var/shop/storage/images/products/cars/troll/981-1-eng-GB/troll.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752061\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(982,'eng-GB',1,260,205,'',0,980,0,0,'','ezprice'),(791,'eng-GB',1,222,206,'Contact us',0,0,0,0,'contact us','ezstring'),(792,'eng-GB',1,222,207,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Please contact us.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(793,'eng-GB',1,222,208,'',0,0,0,0,'','ezstring'),(794,'eng-GB',1,222,209,'',0,0,0,0,'','ezstring'),(795,'eng-GB',1,222,210,'',0,0,0,0,'','eztext'),(796,'eng-GB',1,223,201,'',0,0,0,0,'','ezstring'),(797,'eng-GB',1,223,202,'',0,0,0,0,'','ezstring'),(798,'eng-GB',1,223,203,'',1045487555,0,0,0,'','ezxmltext'),(799,'eng-GB',1,223,204,'',0,0,0,0,'','ezimage'),(800,'eng-GB',1,223,205,'',0,0,0,0,'','ezprice'),(801,'eng-GB',1,224,201,'',0,0,0,0,'','ezstring'),(802,'eng-GB',1,224,202,'',0,0,0,0,'','ezstring'),(803,'eng-GB',1,224,203,'',1045487555,0,0,0,'','ezxmltext'),(804,'eng-GB',1,224,204,'',0,0,0,0,'','ezimage'),(805,'eng-GB',1,224,205,'',0,0,0,0,'','ezprice'),(806,'eng-GB',1,225,201,'',0,0,0,0,'','ezstring'),(807,'eng-GB',1,225,202,'',0,0,0,0,'','ezstring'),(808,'eng-GB',1,225,203,'',1045487555,0,0,0,'','ezxmltext'),(809,'eng-GB',1,225,204,'',0,0,0,0,'','ezimage'),(810,'eng-GB',1,225,205,'',0,0,0,0,'','ezprice'),(811,'eng-GB',1,226,201,'',0,0,0,0,'','ezstring'),(812,'eng-GB',1,226,202,'',0,0,0,0,'','ezstring'),(813,'eng-GB',1,226,203,'',1045487555,0,0,0,'','ezxmltext'),(814,'eng-GB',1,226,204,'',0,0,0,0,'','ezimage'),(815,'eng-GB',1,226,205,'',0,0,0,0,'','ezprice'),(29,'eng-GB',8,14,9,'User',0,0,0,0,'user','ezstring'),(997,'eng-GB',1,263,205,'',0,9,0,0,'','ezprice'),(30,'eng-GB',8,14,12,'',0,0,0,0,'','ezuser'),(983,'eng-GB',1,261,201,'Ferrari',0,0,0,0,'ferrari','ezstring'),(984,'eng-GB',1,261,202,'',0,0,0,0,'','ezstring'),(985,'eng-GB',1,261,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Enjoy the feeling. It&apos;s nothing more to say. If you have ever tried one you never want to leave and you</line>\n    <line>re a fan forever.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(986,'eng-GB',1,261,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ferrari.\"\n         suffix=\"\"\n         basename=\"ferrari\"\n         dirpath=\"var/shop/storage/images/products/cars/ferrari/986-1-eng-GB\"\n         url=\"var/shop/storage/images/products/cars/ferrari/986-1-eng-GB/ferrari.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069752264\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(987,'eng-GB',1,261,205,'',0,200000,0,0,'','ezprice'),(28,'eng-GB',8,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(153,'eng-GB',58,56,160,'shop_red',0,0,0,0,'shop_red','ezpackage'),(154,'eng-GB',58,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',58,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',58,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(911,'eng-GB',58,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(669,'eng-GB',59,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(911,'eng-GB',59,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(153,'eng-GB',59,56,160,'shop_red',0,0,0,0,'shop_red','ezpackage'),(154,'eng-GB',59,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(151,'eng-GB',59,56,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(153,'eng-GB',50,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',50,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',50,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',50,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(767,'eng-GB',3,213,4,'Products',0,0,0,0,'products','ezstring'),(768,'eng-GB',3,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Our products</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',51,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',51,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',51,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',51,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(103,'eng-GB',10,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',10,43,152,'Classes',0,0,0,0,'classes','ezstring'),(104,'eng-GB',10,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',10,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(104,'eng-GB',11,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',11,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(323,'eng-GB',5,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',5,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache.png\"\n         original_filename=\"cache.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(327,'eng-GB',4,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',4,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator.png\"\n         original_filename=\"url_translator.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',5,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',5,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(110,'eng-GB',11,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',11,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(329,'eng-GB',4,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',4,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(153,'eng-GB',53,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',53,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',53,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',53,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(941,'eng-GB',1,250,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This month started off with the release of two new products. Product A and Product B.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(942,'eng-GB',1,250,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>They are both part of a new product portfolio that will be the basis of this shop. There will be examples on products like this in many different categories. </paragraph>\n  <paragraph>In these categories you can add as many products you like, set prices and write product texts. You should also always add pictures of the product so that the users can see the product they are reading about.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(943,'eng-GB',1,250,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin_november.\"\n         suffix=\"\"\n         basename=\"news_bulletin_november\"\n         dirpath=\"var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB\"\n         url=\"var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB/news_bulletin_november.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069686831\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(944,'eng-GB',1,250,123,'',0,0,0,0,'','ezboolean'),(945,'eng-GB',1,250,177,'',0,0,0,0,'','ezinteger'),(872,'eng-GB',1,240,215,'Ttttiille',0,0,0,0,'ttttiille','ezstring'),(873,'eng-GB',1,240,216,'this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... ',0,0,0,0,'','eztext'),(874,'eng-GB',1,240,217,'',0,0,0,0,'','ezinteger'),(946,'eng-GB',1,251,4,'Cords',0,0,0,0,'cords','ezstring'),(947,'eng-GB',1,251,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(948,'eng-GB',1,252,201,'1 meter cord',0,0,0,0,'1 meter cord','ezstring'),(949,'eng-GB',1,252,202,'13444',0,0,0,0,'13444','ezstring'),(950,'eng-GB',1,252,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This cord is one meter long and works for all machines</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(150,'eng-GB',58,56,157,'Shop',0,0,0,0,'','ezinisetting'),(151,'eng-GB',58,56,158,'author=eZ systems package team\ncopyright=Copyright &copy; 1999-2003 eZ systems as\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',57,56,157,'Shop',0,0,0,0,'','ezinisetting'),(151,'eng-GB',57,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',55,56,157,'My shop',0,0,0,0,'','ezinisetting'),(151,'eng-GB',55,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',54,56,157,'My shop',0,0,0,0,'','ezinisetting'),(151,'eng-GB',54,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',56,56,157,'Shop',0,0,0,0,'','ezinisetting'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(895,'eng-GB',1,245,8,'',0,0,0,0,'','ezstring'),(896,'eng-GB',1,245,9,'',0,0,0,0,'','ezstring'),(897,'eng-GB',1,245,12,'',0,0,0,0,'','ezuser'),(791,'eng-GB',2,222,206,'Contact us',0,0,0,0,'contact us','ezstring'),(150,'eng-GB',59,56,157,'Shop',0,0,0,0,'','ezinisetting'),(908,'eng-GB',50,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(909,'eng-GB',51,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(910,'eng-GB',52,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(911,'eng-GB',53,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(154,'eng-GB',54,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',54,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',54,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(911,'eng-GB',54,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring');
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
INSERT INTO ezcontentobject_link VALUES (1,1,4,49),(10,1,8,49),(8,1,7,49),(4,1,5,49),(7,1,6,49);
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(223,'New Product',1,'eng-GB','eng-GB'),(224,'New Product',1,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(56,'Shop',57,'eng-GB','eng-GB'),(161,'Shipping and returns',2,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(213,'Hardware',1,'eng-GB','eng-GB'),(222,'Contact us',1,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(160,'News bulletin',1,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(260,'Troll',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(263,'eZ publish basics',1,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(264,'Music DVD',1,'eng-GB','eng-GB'),(1,'Forum',6,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(226,'New Product',1,'eng-GB','eng-GB'),(225,'New Product',1,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(160,'News bulletin October',2,'eng-GB','eng-GB'),(220,'Conditions of use',1,'eng-GB','eng-GB'),(1,'Shop',8,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(213,'Products',2,'eng-GB','eng-GB'),(219,'Privacy notice',1,'eng-GB','eng-GB'),(219,'Shipping and returns',0,'eng-GB','eng-GB'),(261,'Ferrari',1,'eng-GB','eng-GB'),(1,'Shop',7,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(14,'Administrator User',6,'eng-GB','eng-GB'),(14,'Administrator User',7,'eng-GB','eng-GB'),(247,'New User',1,'eng-GB','eng-GB'),(56,'My shop',55,'eng-GB','eng-GB'),(246,'New User',1,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(262,'Summer book',1,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(56,'My shop',43,'eng-GB','eng-GB'),(56,'My shop',47,'eng-GB','eng-GB'),(56,'My shop',48,'eng-GB','eng-GB'),(56,'My shop',49,'eng-GB','eng-GB'),(56,'My shop',50,'eng-GB','eng-GB'),(56,'My shop',51,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(115,'Cache',5,'eng-GB','eng-GB'),(43,'Classes',10,'eng-GB','eng-GB'),(43,'Classes',11,'eng-GB','eng-GB'),(45,'Look and feel',11,'eng-GB','eng-GB'),(116,'URL translator',4,'eng-GB','eng-GB'),(56,'My shop',52,'eng-GB','eng-GB'),(56,'Shop',59,'eng-GB','eng-GB'),(56,'Shop',58,'eng-GB','eng-GB'),(265,'Action DVD',1,'eng-GB','eng-GB'),(56,'My shop',53,'eng-GB','eng-GB'),(240,'Ttttiille',1,'eng-GB','eng-GB'),(14,'Administrator User',8,'eng-GB','eng-GB'),(245,'New User',1,'eng-GB','eng-GB'),(56,'My shop',54,'eng-GB','eng-GB'),(250,'News bulletin November',1,'eng-GB','eng-GB'),(56,'Shop',56,'eng-GB','eng-GB'),(259,'DVD',1,'eng-GB','eng-GB'),(251,'Cords',1,'eng-GB','eng-GB'),(252,'1 meter cord',1,'eng-GB','eng-GB'),(253,'5 meter cord',1,'eng-GB','eng-GB'),(219,'Privacy notice',2,'eng-GB','eng-GB'),(220,'Conditions of use',2,'eng-GB','eng-GB'),(161,'Shipping and returns',3,'eng-GB','eng-GB'),(161,'Shipping and returns',4,'eng-GB','eng-GB'),(222,'Contact us',2,'eng-GB','eng-GB'),(254,'A new cord',1,'eng-GB','eng-GB'),(257,'Books',1,'eng-GB','eng-GB'),(258,'Flowers',1,'eng-GB','eng-GB'),(258,'Cars',2,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,8,1,1,'/1/2/',8,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,7,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,11,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,11,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,1,1,2,'/1/2/50/',9,1,1,'news',50),(54,48,56,59,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/shop',54),(126,50,160,2,1,3,'/1/2/50/126/',9,1,0,'news/news_bulletin_october',126),(127,2,161,4,1,2,'/1/2/127/',9,1,5,'shipping_and_returns',127),(176,50,250,1,1,3,'/1/2/50/176/',9,1,0,'news/news_bulletin_november',176),(178,177,252,1,1,4,'/1/2/154/177/178/',9,1,0,'products/cords/1_meter_cord',178),(154,2,213,2,1,2,'/1/2/154/',9,1,2,'products',154),(95,46,115,5,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,4,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(179,177,253,1,1,4,'/1/2/154/177/179/',9,1,0,'products/cords/5_meter_cord',179),(177,154,251,1,1,3,'/1/2/154/177/',9,1,0,'products/cords',177),(190,185,264,1,1,4,'/1/2/154/185/190/',9,1,0,'products/dvd/music_dvd',190),(191,185,265,1,1,4,'/1/2/154/185/191/',9,1,0,'products/dvd/action_dvd',191),(188,183,262,1,1,4,'/1/2/154/183/188/',9,1,0,'products/books/summer_book',188),(183,154,257,1,1,3,'/1/2/154/183/',9,1,0,'products/books',183),(184,154,258,2,1,3,'/1/2/154/184/',9,1,0,'products/cars',184),(185,154,259,1,1,3,'/1/2/154/185/',9,1,0,'products/dvd',185),(186,184,260,1,1,4,'/1/2/154/184/186/',9,1,0,'products/cars/troll',186),(187,184,261,1,1,4,'/1/2/154/184/187/',9,1,0,'products/cars/ferrari',187),(160,2,219,2,1,2,'/1/2/160/',9,1,3,'privacy_notice',160),(161,2,220,2,1,2,'/1/2/161/',9,1,4,'conditions_of_use',161),(163,2,222,2,1,2,'/1/2/163/',9,1,6,'contact_us',163),(189,183,263,1,1,4,'/1/2/154/183/189/',9,1,0,'products/books/ez_publish_basics',189),(180,50,254,1,1,3,'/1/2/50/180/',9,1,0,'news/a_new_cord',180);
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
INSERT INTO ezcontentobject_version VALUES (800,1,14,6,1068473139,1068473148,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(880,219,14,2,1069688002,1069688136,1,0,0),(894,259,14,1,1069751455,1069751461,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(847,43,14,11,1068640411,1068640429,1,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(849,45,14,11,1068640482,1068640502,1,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(650,126,14,1,1067008555,1067008788,0,0,0),(890,56,14,57,1069750315,1069750337,3,0,0),(490,49,14,1,1066398007,1066398020,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(664,129,14,1,1067344356,1067344356,0,0,0),(867,56,14,55,1069420922,1069421350,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(724,160,14,1,1068047416,1068047455,3,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(851,56,14,52,1068640667,1068640675,3,0,0),(725,161,14,1,1068047518,1068047603,3,0,0),(865,56,14,54,1069416400,1069416424,3,0,0),(901,56,14,58,1069838953,1069839028,3,0,0),(856,56,14,53,1069165818,1069165845,3,0,0),(651,127,14,1,1067243907,1067245036,0,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(835,56,14,50,1068628662,1068628685,3,0,0),(842,43,14,9,1068639982,1068639989,3,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(844,116,14,3,1068640009,1068640016,3,0,0),(836,213,14,3,1068629484,1068629484,0,0,0),(902,56,14,59,1069839554,1069839571,1,0,0),(838,56,14,51,1068634614,1068634626,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(810,1,14,7,1068542616,1068542626,3,1,0),(873,56,14,56,1069686954,1069687079,3,0,0),(668,49,14,2,1067357193,1067357193,0,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(871,1,14,8,1069686109,1069686122,1,1,0),(846,43,14,10,1068640261,1068640329,3,0,0),(843,45,14,10,1068639995,1068640002,3,0,0),(899,264,14,1,1069752534,1069752759,1,0,0),(703,143,14,1,1068040391,1068040391,0,0,0),(704,144,14,1,1068040434,1068040434,0,0,0),(705,145,14,1,1068040688,1068040688,0,0,0),(879,253,14,1,1069687934,1069687961,1,0,0),(841,115,14,4,1068639963,1068639974,3,0,0),(891,257,14,1,1069751013,1069751025,1,0,0),(801,213,14,1,1068473196,1068473231,3,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(753,187,14,1,1068110619,1068110619,0,0,0),(878,252,14,1,1069687890,1069687927,1,0,0),(755,189,14,1,1068110880,1068110880,0,0,0),(817,222,14,1,1068554893,1068554919,3,0,0),(823,226,14,1,1068557670,1068557670,0,0,0),(877,251,14,1,1069687868,1069687877,1,0,0),(822,225,14,1,1068557668,1068557668,0,0,0),(816,14,14,7,1068546819,1068556425,1,0,0),(813,220,14,1,1068542707,1068542738,3,0,0),(812,219,14,1,1068542674,1068542692,3,0,0),(811,161,14,2,1068542639,1068542655,3,0,0),(821,224,14,1,1068557633,1068557633,0,0,0),(896,261,14,1,1069752262,1069752332,1,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(895,260,14,1,1069752059,1069752252,1,0,0),(815,14,14,6,1068545948,1068545957,3,0,0),(818,213,14,2,1068556190,1068556203,1,0,0),(820,223,14,1,1068557207,1068557207,0,0,0),(893,258,14,2,1069751102,1069751108,1,0,0),(874,250,14,1,1069686828,1069687269,1,0,0),(872,160,14,2,1069686675,1069686817,1,0,0),(868,247,14,1,1069676471,1069676471,0,0,0),(866,246,14,1,1069418372,1069418372,0,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(796,14,14,5,1068468183,1068468218,3,0,0),(898,263,14,1,1069752453,1069752520,1,0,0),(848,115,14,5,1068640455,1068640475,1,0,0),(850,116,14,4,1068640509,1068640525,1,0,0),(858,240,14,1,1069249251,1069251941,0,0,0),(861,14,14,8,1069329522,1069329522,0,0,0),(864,245,14,1,1069407224,1069407224,0,0,0),(881,220,14,2,1069688144,1069688213,1,0,0),(882,161,14,3,1069688221,1069688299,3,0,0),(883,161,14,4,1069688307,1069688507,1,0,0),(884,222,14,2,1069688558,1069688572,1,0,0),(885,254,14,1,1069688599,1069688677,1,0,0),(892,258,14,1,1069751035,1069751059,3,0,0),(897,262,14,1,1069752348,1069752444,1,0,0),(900,265,14,1,1069752767,1069752921,1,0,0);
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
INSERT INTO ezgeneral_digest_user_settings VALUES (1,'nospam@ez.no',0,0,'',''),(2,'nospam@ez.no',0,0,'','');
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
INSERT INTO ezimagefile VALUES (1,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_logo.gif'),(2,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop.gif'),(3,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_reference.gif'),(4,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_medium.gif'),(5,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_logo.gif'),(7,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop.gif'),(8,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_reference.gif'),(9,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_medium.gif'),(10,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_logo.gif'),(11,519,'var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB/news_bulletin_october.'),(12,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop.gif'),(13,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_reference.gif'),(14,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_medium.gif'),(15,943,'var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB/news_bulletin_november.'),(31,152,'var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_logo.gif'),(18,951,'var/shop/storage/images/products/cords/1_meter_cord/951-1-eng-GB/1_meter_cord.'),(19,956,'var/shop/storage/images/products/cords/5_meter_cord/956-1-eng-GB/5_meter_cord.'),(20,784,'var/shop/storage/images/privacy_notice/784-2-eng-GB/privacy_notice.'),(21,787,'var/shop/storage/images/conditions_of_use/787-2-eng-GB/conditions_of_use.'),(22,524,'var/shop/storage/images/shipping_and_returns/524-3-eng-GB/shipping_and_returns.'),(23,524,'var/shop/storage/images/shipping_and_returns/524-4-eng-GB/shipping_and_returns.'),(24,961,'var/shop/storage/images/news/a_new_cord/961-1-eng-GB/a_new_cord.'),(28,152,'var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop.gif'),(29,152,'var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_reference.gif'),(30,152,'var/shop/storage/images/setup/look_and_feel/shop/152-57-eng-GB/shop_medium.gif'),(32,981,'var/shop/storage/images/products/cars/troll/981-1-eng-GB/troll.'),(33,986,'var/shop/storage/images/products/cars/ferrari/986-1-eng-GB/ferrari.'),(34,991,'var/shop/storage/images/products/books/summer_book/991-1-eng-GB/summer_book.'),(35,996,'var/shop/storage/images/products/books/ez_publish_basics/996-1-eng-GB/ez_publish_basics.'),(36,1001,'var/shop/storage/images/products/dvd/music_dvd/1001-1-eng-GB/music_dvd.'),(37,1006,'var/shop/storage/images/products/dvd/action_dvd/1006-1-eng-GB/action_dvd.'),(39,152,'var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop.gif'),(40,152,'var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop_reference.gif'),(41,152,'var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop_medium.gif'),(42,152,'var/shop/storage/images/setup/look_and_feel/shop/152-58-eng-GB/shop_logo.gif'),(43,152,'var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop.gif'),(44,152,'var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_reference.gif'),(45,152,'var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_medium.gif'),(46,152,'var/shop/storage/images/setup/look_and_feel/shop/152-59-eng-GB/shop_logo.gif');
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
INSERT INTO ezinfocollection VALUES (1,137,1068027503,'c6194244e6057c2ed46e92ac8c59be21',1068027503),(2,137,1068028058,'c6194244e6057c2ed46e92ac8c59be21',1068028058),(3,222,1068630829,'c6194244e6057c2ed46e92ac8c59be21',1068630829),(4,222,1068634393,'c6194244e6057c2ed46e92ac8c59be21',1068634393),(5,222,1068642711,'c6194244e6057c2ed46e92ac8c59be21',1068642711);
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
INSERT INTO ezinfocollection_attribute VALUES (1,1,'',0,0,183,443,137),(2,1,'',0,0,185,445,137),(3,1,'',0,0,184,444,137),(4,2,'FOo bar ',0,0,183,443,137),(5,2,'nospam@ez.no',0,0,185,445,137),(6,2,'This is my feedback.',0,0,184,444,137),(7,3,'test',0,0,208,793,222),(8,3,'nospam@ez.no',0,0,209,794,222),(9,3,'sfsfsf',0,0,210,795,222),(10,4,'test',0,0,208,793,222),(11,4,'nospam@ez.no',0,0,209,794,222),(12,4,'fwsfwsf',0,0,210,795,222),(13,5,'wer',0,0,208,793,222),(14,5,'nospam@ez.no',0,0,209,794,222),(15,5,'ewrw',0,0,210,795,222);
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
INSERT INTO eznode_assignment VALUES (504,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(602,262,1,183,9,1,1,0,0),(601,261,1,184,9,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(583,219,2,2,9,1,1,0,0),(539,213,3,2,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(550,43,11,46,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(552,45,11,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(595,56,57,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(570,56,55,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(367,129,1,2,1,1,1,0,0),(568,56,54,48,9,1,1,0,0),(428,160,1,50,9,1,1,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(554,56,52,48,9,1,1,0,0),(505,213,1,2,9,1,1,0,0),(606,56,58,48,9,1,1,0,0),(559,56,53,48,9,1,1,0,0),(354,127,1,50,1,1,0,0,0),(353,126,1,50,1,1,0,0,0),(321,45,6,46,9,1,1,0,0),(538,56,50,48,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(607,56,59,48,9,1,1,0,0),(544,115,4,46,9,1,1,0,0),(335,45,8,46,9,1,1,0,0),(541,56,51,48,9,1,1,0,0),(545,43,9,46,9,1,1,0,0),(547,116,3,46,9,1,1,0,0),(516,1,7,1,9,1,1,0,0),(576,56,56,48,9,1,1,0,0),(371,49,2,2,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(574,1,8,1,8,1,1,0,0),(604,264,1,185,9,1,1,0,0),(407,143,1,112,1,1,1,0,0),(408,144,1,112,1,1,1,0,0),(409,145,1,112,1,1,1,0,0),(551,115,5,46,9,1,1,0,0),(581,252,1,177,9,1,1,0,0),(553,116,4,46,9,1,1,0,0),(596,257,1,154,9,1,1,0,0),(424,14,2,13,9,1,1,0,0),(549,43,10,46,9,1,1,0,0),(546,45,10,46,9,1,1,0,0),(457,187,1,12,1,1,1,0,0),(580,251,1,154,9,1,1,0,0),(459,189,1,12,1,1,1,0,0),(598,258,2,154,9,1,1,0,0),(597,258,1,154,9,1,1,0,0),(605,265,1,185,9,1,1,0,0),(521,14,6,13,9,1,1,0,0),(524,213,2,2,9,1,1,0,0),(519,220,1,2,9,1,1,0,0),(518,219,1,2,9,1,1,0,0),(481,14,3,13,9,1,1,0,0),(517,161,2,2,9,1,1,0,0),(523,222,1,2,9,1,1,0,0),(522,14,7,13,9,1,1,0,0),(577,250,1,50,9,1,1,0,0),(575,160,2,50,9,1,1,0,0),(571,247,1,12,1,1,1,0,0),(582,253,1,177,9,1,1,0,0),(569,246,1,12,1,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(600,260,1,184,9,1,1,0,0),(500,14,5,13,9,1,1,0,0),(603,263,1,183,9,1,1,0,0),(561,240,1,165,1,1,1,0,0),(564,14,8,13,9,1,1,0,0),(567,245,1,12,1,1,1,0,0),(584,220,2,2,9,1,1,0,0),(585,161,3,2,9,1,1,0,0),(586,161,4,2,9,1,1,0,0),(587,222,2,2,9,1,1,0,0),(588,254,1,50,9,1,1,0,0),(599,259,1,154,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','',''),(222,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',212,1,0,0,'','','',''),(224,0,'ezpublish',1,6,0,0,'','','',''),(225,0,'ezpublish',213,1,0,0,'','','',''),(226,0,'ezpublish',211,2,0,0,'','','',''),(227,0,'ezpublish',212,2,0,0,'','','',''),(228,0,'ezpublish',214,1,0,0,'','','',''),(229,0,'ezpublish',215,1,0,0,'','','',''),(230,0,'ezpublish',216,1,0,0,'','','',''),(231,0,'ezpublish',217,1,0,0,'','','',''),(232,0,'ezpublish',218,1,0,0,'','','',''),(233,0,'ezpublish',218,2,0,0,'','','',''),(234,0,'ezpublish',1,7,0,0,'','','',''),(235,0,'ezpublish',161,2,0,0,'','','',''),(236,0,'ezpublish',219,1,0,0,'','','',''),(237,0,'ezpublish',220,1,0,0,'','','',''),(238,0,'ezpublish',221,1,0,0,'','','',''),(239,0,'ezpublish',14,6,0,0,'','','',''),(240,0,'ezpublish',222,1,0,0,'','','',''),(241,0,'ezpublish',213,2,0,0,'','','',''),(242,0,'ezpublish',14,7,0,0,'','','',''),(243,0,'ezpublish',211,3,0,0,'','','',''),(244,0,'ezpublish',227,1,0,0,'','','',''),(245,0,'ezpublish',228,1,0,0,'','','',''),(246,0,'ezpublish',229,1,0,0,'','','',''),(247,0,'ezpublish',230,1,0,0,'','','',''),(248,0,'ezpublish',56,43,0,0,'','','',''),(249,0,'ezpublish',56,47,0,0,'','','',''),(250,0,'ezpublish',56,48,0,0,'','','',''),(251,0,'ezpublish',232,1,0,0,'','','',''),(252,0,'ezpublish',232,2,0,0,'','','',''),(253,0,'ezpublish',56,49,0,0,'','','',''),(254,0,'ezpublish',56,50,0,0,'','','',''),(255,0,'ezpublish',232,3,0,0,'','','',''),(256,0,'ezpublish',56,51,0,0,'','','',''),(257,0,'ezpublish',115,4,0,0,'','','',''),(258,0,'ezpublish',43,9,0,0,'','','',''),(259,0,'ezpublish',45,10,0,0,'','','',''),(260,0,'ezpublish',116,3,0,0,'','','',''),(261,0,'ezpublish',43,10,0,0,'','','',''),(262,0,'ezpublish',43,11,0,0,'','','',''),(263,0,'ezpublish',115,5,0,0,'','','',''),(264,0,'ezpublish',45,11,0,0,'','','',''),(265,0,'ezpublish',116,4,0,0,'','','',''),(266,0,'ezpublish',56,52,0,0,'','','',''),(267,0,'ezpublish',235,1,0,0,'','','',''),(268,0,'ezpublish',236,1,0,0,'','','',''),(269,0,'ezpublish',237,1,0,0,'','','',''),(270,0,'ezpublish',238,1,0,0,'','','',''),(271,0,'ezpublish',56,53,0,0,'','','',''),(272,0,'ezpublish',239,1,0,0,'','','',''),(273,0,'ezpublish',241,1,0,0,'','','',''),(274,0,'ezpublish',242,1,0,0,'','','',''),(275,0,'ezpublish',56,54,0,0,'','','',''),(276,0,'ezpublish',56,55,0,0,'','','',''),(277,0,'ezpublish',1,8,0,0,'','','',''),(278,0,'ezpublish',160,2,0,0,'','','',''),(279,0,'ezpublish',56,56,0,0,'','','',''),(280,0,'ezpublish',250,1,0,0,'','','',''),(281,0,'ezpublish',212,3,0,0,'','','',''),(282,0,'ezpublish',227,2,0,0,'','','',''),(283,0,'ezpublish',251,1,0,0,'','','',''),(284,0,'ezpublish',252,1,0,0,'','','',''),(285,0,'ezpublish',253,1,0,0,'','','',''),(286,0,'ezpublish',219,2,0,0,'','','',''),(287,0,'ezpublish',220,2,0,0,'','','',''),(288,0,'ezpublish',161,3,0,0,'','','',''),(289,0,'ezpublish',161,4,0,0,'','','',''),(290,0,'ezpublish',222,2,0,0,'','','',''),(291,0,'ezpublish',254,1,0,0,'','','',''),(292,0,'ezpublish',227,3,0,0,'','','',''),(293,0,'ezpublish',212,4,0,0,'','','',''),(294,0,'ezpublish',255,1,0,0,'','','',''),(295,0,'ezpublish',256,1,0,0,'','','',''),(296,0,'ezpublish',56,57,0,0,'','','',''),(297,0,'ezpublish',257,1,0,0,'','','',''),(298,0,'ezpublish',258,1,0,0,'','','',''),(299,0,'ezpublish',258,2,0,0,'','','',''),(300,0,'ezpublish',259,1,0,0,'','','',''),(301,0,'ezpublish',260,1,0,0,'','','',''),(302,0,'ezpublish',261,1,0,0,'','','',''),(303,0,'ezpublish',262,1,0,0,'','','',''),(304,0,'ezpublish',263,1,0,0,'','','',''),(305,0,'ezpublish',264,1,0,0,'','','',''),(306,0,'ezpublish',265,1,0,0,'','','',''),(307,0,'ezpublish',56,58,0,0,'','','',''),(308,0,'ezpublish',56,59,0,0,'','','','');
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(341,8,'read','content','*'),(381,1,'login','user','*'),(382,1,'read','content',''),(383,1,'buy','shop','*');
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
INSERT INTO ezpolicy_limitation VALUES (301,382,'Class',0,'read','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (608,301,'5'),(607,301,'25'),(606,301,'24'),(605,301,'23'),(604,301,'2'),(603,301,'12'),(602,301,'10'),(601,301,'1');
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
INSERT INTO ezsearch_object_word_link VALUES (28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(4292,43,1639,0,2,1638,0,14,1066384365,11,155,'',0),(4291,43,1638,0,1,1637,1639,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(4290,43,1637,0,0,0,1638,14,1066384365,11,152,'',0),(4301,45,1642,0,5,1641,0,14,1066388816,11,155,'',0),(4300,45,1641,0,4,25,1642,14,1066388816,11,155,'',0),(4299,45,25,0,3,34,1641,14,1066388816,11,155,'',0),(4298,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(61,49,37,0,0,0,0,1,1066398020,1,4,'',0),(5085,253,2017,0,14,1890,0,23,1069687961,1,203,'',0),(5084,253,1890,0,13,1663,2017,23,1069687961,1,203,'',0),(5083,253,1663,0,12,2016,1890,23,1069687961,1,203,'',0),(5082,253,2016,0,11,33,1663,23,1069687961,1,203,'',0),(5081,253,33,0,10,1688,2016,23,1069687961,1,203,'',0),(5080,253,1688,0,9,2020,33,23,1069687961,1,203,'',0),(5079,253,2020,0,8,2019,1688,23,1069687961,1,203,'',0),(5078,253,2019,0,7,74,2020,23,1069687961,1,203,'',0),(5077,253,74,0,6,2014,2019,23,1069687961,1,203,'',0),(5076,253,2014,0,5,73,74,23,1069687961,1,203,'',0),(5075,253,73,0,4,2018,2014,23,1069687961,1,203,'',0),(5074,253,2018,0,3,2014,73,23,1069687961,1,202,'',34555),(5073,253,2014,0,2,2013,2018,23,1069687961,1,201,'',0),(5072,253,2013,0,1,1790,2014,23,1069687961,1,201,'',0),(5071,253,1790,0,0,0,2013,23,1069687961,1,201,'',0),(5070,252,2017,0,14,1890,0,23,1069687927,1,203,'',0),(5069,252,1890,0,13,1663,2017,23,1069687927,1,203,'',0),(5068,252,1663,0,12,2016,1890,23,1069687927,1,203,'',0),(5067,252,2016,0,11,33,1663,23,1069687927,1,203,'',0),(5066,252,33,0,10,1688,2016,23,1069687927,1,203,'',0),(5065,252,1688,0,9,2013,33,23,1069687927,1,203,'',0),(5064,252,2013,0,8,1787,1688,23,1069687927,1,203,'',0),(5063,252,1787,0,7,74,2013,23,1069687927,1,203,'',0),(5062,252,74,0,6,2014,1787,23,1069687927,1,203,'',0),(5061,252,2014,0,5,73,74,23,1069687927,1,203,'',0),(5060,252,73,0,4,2015,2014,23,1069687927,1,203,'',0),(5059,252,2015,0,3,2014,73,23,1069687927,1,202,'',13444),(5058,252,2014,0,2,2013,2015,23,1069687927,1,201,'',0),(5057,252,2013,0,1,1918,2014,23,1069687927,1,201,'',0),(5056,252,1918,0,0,0,2013,23,1069687927,1,201,'',0),(5055,251,2012,0,0,0,0,1,1069687877,1,4,'',0),(5514,261,2162,0,17,1797,1717,23,1069752332,1,203,'',0),(5513,261,1797,0,16,1787,2162,23,1069752332,1,203,'',0),(5512,261,1787,0,15,2161,1797,23,1069752332,1,203,'',0),(5511,261,2161,0,14,2160,1787,23,1069752332,1,203,'',0),(5510,261,2160,0,13,1750,2161,23,1069752332,1,203,'',0),(5509,261,1750,0,12,1797,2160,23,1069752332,1,203,'',0),(5508,261,1797,0,11,1883,1750,23,1069752332,1,203,'',0),(5507,261,1883,0,10,2159,1797,23,1069752332,1,203,'',0),(5506,261,2159,0,9,1614,1883,23,1069752332,1,203,'',0),(5505,261,1614,0,8,2158,2159,23,1069752332,1,203,'',0),(5504,261,2158,0,7,2157,1614,23,1069752332,1,203,'',0),(5503,261,2157,0,6,2156,2158,23,1069752332,1,203,'',0),(5502,261,2156,0,5,1607,2157,23,1069752332,1,203,'',0),(5501,261,1607,0,4,2155,2156,23,1069752332,1,203,'',0),(5500,261,2155,0,3,1142,1607,23,1069752332,1,203,'',0),(5499,261,1142,0,2,2154,2155,23,1069752332,1,203,'',0),(5498,261,2154,0,1,2153,1142,23,1069752332,1,203,'',0),(5497,261,2153,0,0,0,2154,23,1069752332,1,201,'',0),(5496,260,2152,0,21,1417,0,23,1069752252,1,203,'',0),(5495,260,1417,0,20,2151,2152,23,1069752252,1,203,'',0),(5494,260,2151,0,19,1142,1417,23,1069752252,1,203,'',0),(5493,260,1142,0,18,2150,2151,23,1069752252,1,203,'',0),(5492,260,2150,0,17,2141,1142,23,1069752252,1,203,'',0),(5491,260,2141,0,16,2019,2150,23,1069752252,1,203,'',0),(5490,260,2019,0,15,2147,2141,23,1069752252,1,203,'',0),(5489,260,2147,0,14,1316,2019,23,1069752252,1,203,'',0),(5488,260,1316,0,13,1417,2147,23,1069752252,1,203,'',0),(5486,260,2149,0,11,2148,1417,23,1069752252,1,203,'',0),(5487,260,1417,0,12,2149,1316,23,1069752252,1,203,'',0),(4989,250,1768,0,85,1989,0,2,1069687269,1,121,'',0),(4988,250,1989,0,84,1660,1768,2,1069687269,1,121,'',0),(4987,250,1660,0,83,1732,1989,2,1069687269,1,121,'',0),(4986,250,1732,0,82,1969,1660,2,1069687269,1,121,'',0),(4985,250,1969,0,81,1142,1732,2,1069687269,1,121,'',0),(4984,250,1142,0,80,1954,1969,2,1069687269,1,121,'',0),(4983,250,1954,0,79,1612,1142,2,1069687269,1,121,'',0),(4982,250,1612,0,78,1988,1954,2,1069687269,1,121,'',0),(4981,250,1988,0,77,1142,1612,2,1069687269,1,121,'',0),(4980,250,1142,0,76,1672,1988,2,1069687269,1,121,'',0),(4979,250,1672,0,75,1668,1142,2,1069687269,1,121,'',0),(4978,250,1668,0,74,1969,1672,2,1069687269,1,121,'',0),(4977,250,1969,0,73,1142,1668,2,1069687269,1,121,'',0),(4976,250,1142,0,72,1519,1969,2,1069687269,1,121,'',0),(4975,250,1519,0,71,1987,1142,2,1069687269,1,121,'',0),(4974,250,1987,0,70,1979,1519,2,1069687269,1,121,'',0),(4973,250,1979,0,69,1986,1987,2,1069687269,1,121,'',0),(4972,250,1986,0,68,1985,1979,2,1069687269,1,121,'',0),(4971,250,1985,0,67,1984,1986,2,1069687269,1,121,'',0),(4970,250,1984,0,66,1797,1985,2,1069687269,1,121,'',0),(4969,250,1797,0,65,1983,1984,2,1069687269,1,121,'',0),(4968,250,1983,0,64,1969,1797,2,1069687269,1,121,'',0),(4967,250,1969,0,63,1982,1983,2,1069687269,1,121,'',0),(4966,250,1982,0,62,33,1969,2,1069687269,1,121,'',0),(4965,250,33,0,61,1981,1982,2,1069687269,1,121,'',0),(4964,250,1981,0,60,1980,33,2,1069687269,1,121,'',0),(4963,250,1980,0,59,1804,1981,2,1069687269,1,121,'',0),(4962,250,1804,0,58,1797,1980,2,1069687269,1,121,'',0),(4961,250,1797,0,57,1534,1804,2,1069687269,1,121,'',0),(4960,250,1534,0,56,1783,1797,2,1069687269,1,121,'',0),(4959,250,1783,0,55,1670,1534,2,1069687269,1,121,'',0),(4958,250,1670,0,54,1979,1783,2,1069687269,1,121,'',0),(4957,250,1979,0,53,1612,1670,2,1069687269,1,121,'',0),(4956,250,1612,0,52,1797,1979,2,1069687269,1,121,'',0),(4955,250,1797,0,51,1978,1612,2,1069687269,1,121,'',0),(4954,250,1978,0,50,1947,1797,2,1069687269,1,121,'',0),(4953,250,1947,0,49,1417,1978,2,1069687269,1,121,'',0),(4952,250,1417,0,48,1978,1947,2,1069687269,1,121,'',0),(4951,250,1978,0,47,1977,1417,2,1069687269,1,121,'',0),(4950,250,1977,0,46,1783,1978,2,1069687269,1,121,'',0),(4949,250,1783,0,45,1417,1977,2,1069687269,1,121,'',0),(4948,250,1417,0,44,73,1783,2,1069687269,1,121,'',0),(4947,250,73,0,43,1804,1417,2,1069687269,1,121,'',0),(4946,250,1804,0,42,1534,73,2,1069687269,1,121,'',0),(4945,250,1534,0,41,1620,1804,2,1069687269,1,121,'',0),(4944,250,1620,0,40,1976,1534,2,1069687269,1,121,'',0),(4943,250,1976,0,39,1782,1620,2,1069687269,1,121,'',0),(4942,250,1782,0,38,1666,1976,2,1069687269,1,121,'',0),(4941,250,1666,0,37,1975,1782,2,1069687269,1,121,'',0),(4940,250,1975,0,36,1940,1666,2,1069687269,1,121,'',0),(4939,250,1940,0,35,73,1975,2,1069687269,1,121,'',0),(4938,250,73,0,34,1519,1940,2,1069687269,1,121,'',0),(4937,250,1519,0,33,1974,73,2,1069687269,1,121,'',0),(4936,250,1974,0,32,1142,1519,2,1069687269,1,121,'',0),(4935,250,1142,0,31,1782,1974,2,1069687269,1,121,'',0),(4934,250,1782,0,30,1666,1142,2,1069687269,1,121,'',0),(4933,250,1666,0,29,1672,1782,2,1069687269,1,121,'',0),(4932,250,1672,0,28,1973,1666,2,1069687269,1,121,'',0),(4931,250,1973,0,27,1969,1672,2,1069687269,1,121,'',0),(4930,250,1969,0,26,1761,1973,2,1069687269,1,121,'',0),(4929,250,1761,0,25,1439,1969,2,1069687269,1,121,'',0),(4928,250,1439,0,24,1519,1761,2,1069687269,1,121,'',0),(4927,250,1519,0,23,1972,1439,2,1069687269,1,121,'',0),(4926,250,1972,0,22,1971,1519,2,1069687269,1,121,'',0),(4925,250,1971,0,21,1660,1972,2,1069687269,1,121,'',0),(4924,250,1660,0,20,1732,1971,2,1069687269,1,121,'',0),(4923,250,1732,0,19,1970,1660,2,1069687269,1,121,'',0),(4922,250,1970,0,18,1969,1732,2,1069687269,1,120,'',0),(4921,250,1969,0,17,33,1970,2,1069687269,1,120,'',0),(4920,250,33,0,16,1439,1969,2,1069687269,1,120,'',0),(4919,250,1439,0,15,1969,33,2,1069687269,1,120,'',0),(4918,250,1969,0,14,1534,1439,2,1069687269,1,120,'',0),(4917,250,1534,0,13,1761,1969,2,1069687269,1,120,'',0),(4916,250,1761,0,12,1693,1534,2,1069687269,1,120,'',0),(4915,250,1693,0,11,1519,1761,2,1069687269,1,120,'',0),(4914,250,1519,0,10,1953,1693,2,1069687269,1,120,'',0),(4913,250,1953,0,9,1142,1519,2,1069687269,1,120,'',0),(4912,250,1142,0,8,1765,1953,2,1069687269,1,120,'',0),(4911,250,1765,0,7,1738,1142,2,1069687269,1,120,'',0),(4910,250,1738,0,6,1968,1765,2,1069687269,1,120,'',0),(4909,250,1968,0,5,1967,1738,2,1069687269,1,120,'',0),(4908,250,1967,0,4,73,1968,2,1069687269,1,120,'',0),(4907,250,73,0,3,1966,1967,2,1069687269,1,120,'',0),(4906,250,1966,0,2,1941,73,2,1069687269,1,1,'',0),(4905,250,1941,0,1,37,1966,2,1069687269,1,1,'',0),(4904,250,37,0,0,0,1941,2,1069687269,1,1,'',0),(4895,160,1958,0,49,1957,0,2,1068047455,1,121,'',0),(4894,160,1957,0,48,1142,1958,2,1068047455,1,121,'',0),(4893,160,1142,0,47,1144,1957,2,1068047455,1,121,'',0),(4892,160,1144,0,46,1956,1142,2,1068047455,1,121,'',0),(4891,160,1956,0,45,1955,1144,2,1068047455,1,121,'',0),(4890,160,1955,0,44,1917,1956,2,1068047455,1,121,'',0),(4889,160,1917,0,43,1439,1955,2,1068047455,1,121,'',0),(4888,160,1439,0,42,74,1917,2,1068047455,1,121,'',0),(4887,160,74,0,41,1607,1439,2,1068047455,1,121,'',0),(4886,160,1607,0,40,1954,74,2,1068047455,1,121,'',0),(4885,160,1954,0,39,1612,1607,2,1068047455,1,121,'',0),(4884,160,1612,0,38,1890,1954,2,1068047455,1,121,'',0),(4883,160,1890,0,37,1797,1612,2,1068047455,1,121,'',0),(4882,160,1797,0,36,1670,1890,2,1068047455,1,121,'',0),(4881,160,1670,0,35,1622,1797,2,1068047455,1,121,'',0),(4880,160,1622,0,34,1761,1670,2,1068047455,1,121,'',0),(4879,160,1761,0,33,1439,1622,2,1068047455,1,121,'',0),(4878,160,1439,0,32,1953,1761,2,1068047455,1,121,'',0),(4877,160,1953,0,31,1747,1439,2,1068047455,1,121,'',0),(4876,160,1747,0,30,1952,1953,2,1068047455,1,121,'',0),(4875,160,1952,0,29,1614,1747,2,1068047455,1,120,'',0),(4874,160,1614,0,28,1951,1952,2,1068047455,1,120,'',0),(4873,160,1951,0,27,1950,1614,2,1068047455,1,120,'',0),(4872,160,1950,0,26,33,1951,2,1068047455,1,120,'',0),(4871,160,33,0,25,1949,1950,2,1068047455,1,120,'',0),(4870,160,1949,0,24,1761,33,2,1068047455,1,120,'',0),(4869,160,1761,0,23,1534,1949,2,1068047455,1,120,'',0),(4868,160,1534,0,22,1761,1761,2,1068047455,1,120,'',0),(4867,160,1761,0,21,1750,1534,2,1068047455,1,120,'',0),(4866,160,1750,0,20,1747,1761,2,1068047455,1,120,'',0),(4865,160,1747,0,19,1670,1750,2,1068047455,1,120,'',0),(4864,160,1670,0,18,1948,1747,2,1068047455,1,120,'',0),(4863,160,1948,0,17,1670,1670,2,1068047455,1,120,'',0),(4862,160,1670,0,16,37,1948,2,1068047455,1,120,'',0),(4861,160,37,0,15,1947,1670,2,1068047455,1,120,'',0),(4860,160,1947,0,14,1946,37,2,1068047455,1,120,'',0),(4859,160,1946,0,13,1666,1947,2,1068047455,1,120,'',0),(4858,160,1666,0,12,1747,1946,2,1068047455,1,120,'',0),(4857,160,1747,0,11,1945,1666,2,1068047455,1,120,'',0),(4856,160,1945,0,10,73,1747,2,1068047455,1,120,'',0),(4855,160,73,0,9,1144,1945,2,1068047455,1,120,'',0),(4854,160,1144,0,8,37,73,2,1068047455,1,120,'',0),(4853,160,37,0,7,1944,1144,2,1068047455,1,120,'',0),(4852,160,1944,0,6,1142,37,2,1068047455,1,120,'',0),(4851,160,1142,0,5,1660,1944,2,1068047455,1,120,'',0),(4850,160,1660,0,4,1943,1142,2,1068047455,1,120,'',0),(4849,160,1943,0,3,1942,1660,2,1068047455,1,120,'',0),(4848,160,1942,0,2,1941,1943,2,1068047455,1,1,'',0),(4847,160,1941,0,1,37,1942,2,1068047455,1,1,'',0),(4846,160,37,0,0,0,1941,2,1068047455,1,1,'',0),(5366,254,1612,0,29,1797,0,2,1069688677,1,121,'',0),(5365,254,1797,0,28,2103,1612,2,1069688677,1,121,'',0),(5364,254,2103,0,27,1611,1797,2,1069688677,1,121,'',0),(5363,254,1611,0,26,1824,2103,2,1069688677,1,121,'',0),(5362,254,1824,0,25,2102,1611,2,1069688677,1,121,'',0),(5361,254,2102,0,24,1670,1824,2,1069688677,1,121,'',0),(5360,254,1670,0,23,2101,2102,2,1069688677,1,121,'',0),(5359,254,2101,0,22,1670,1670,2,1069688677,1,121,'',0),(5358,254,1670,0,21,1663,2101,2,1069688677,1,121,'',0),(5357,254,1663,0,20,1940,1670,2,1069688677,1,121,'',0),(5356,254,1940,0,19,1535,1663,2,1069688677,1,121,'',0),(5355,254,1535,0,18,1144,1940,2,1069688677,1,121,'',0),(5354,254,1144,0,17,1995,1535,2,1069688677,1,121,'',0),(5353,254,1995,0,16,1660,1144,2,1069688677,1,121,'',0),(5352,254,1660,0,15,1732,1995,2,1069688677,1,121,'',0),(5351,254,1732,0,14,2100,1660,2,1069688677,1,121,'',0),(5350,254,2100,0,13,1535,1732,2,1069688677,1,120,'',0),(5349,254,1535,0,12,1144,2100,2,1069688677,1,120,'',0),(5348,254,1144,0,11,2012,1535,2,1069688677,1,120,'',0),(5347,254,2012,0,10,2013,1144,2,1069688677,1,120,'',0),(5346,254,2013,0,9,1790,2012,2,1069688677,1,120,'',0),(5345,254,1790,0,8,2099,2013,2,1069688677,1,120,'',0),(5344,254,2099,0,7,2098,1790,2,1069688677,1,120,'',0),(5343,254,2098,0,6,1728,2099,2,1069688677,1,120,'',0),(5342,254,1728,0,5,1750,2098,2,1069688677,1,120,'',0),(5341,254,1750,0,4,1142,1728,2,1069688677,1,120,'',0),(5340,254,1142,0,3,2014,1750,2,1069688677,1,120,'',0),(5339,254,2014,0,2,1761,1142,2,1069688677,1,1,'',0),(5338,254,1761,0,1,1439,2014,2,1069688677,1,1,'',0),(5337,254,1439,0,0,0,1761,2,1069688677,1,1,'',0),(5336,222,2079,0,60,2097,0,24,1068554919,1,207,'',0),(5335,222,2097,0,59,37,2079,24,1068554919,1,207,'',0),(5334,222,37,0,58,1620,2097,24,1068554919,1,207,'',0),(5333,222,1620,0,57,1958,37,24,1068554919,1,207,'',0),(5332,222,1958,0,56,1142,1620,24,1068554919,1,207,'',0),(5331,222,1142,0,55,2096,1958,24,1068554919,1,207,'',0),(5330,222,2096,0,54,1614,1142,24,1068554919,1,207,'',0),(5329,222,1614,0,53,2095,2096,24,1068554919,1,207,'',0),(5319,222,73,0,43,2091,1958,24,1068554919,1,207,'',0),(5320,222,1958,0,44,73,74,24,1068554919,1,207,'',0),(5321,222,74,0,45,1958,1985,24,1068554919,1,207,'',0),(5322,222,1985,0,46,74,2093,24,1068554919,1,207,'',0),(5323,222,2093,0,47,1985,2094,24,1068554919,1,207,'',0),(5324,222,2094,0,48,2093,1663,24,1068554919,1,207,'',0),(5325,222,1663,0,49,2094,2030,24,1068554919,1,207,'',0),(5326,222,2030,0,50,1663,1672,24,1068554919,1,207,'',0),(5327,222,1672,0,51,2030,2095,24,1068554919,1,207,'',0),(5328,222,2095,0,52,1672,1614,24,1068554919,1,207,'',0),(4297,45,33,0,1,32,34,14,1066388816,11,152,'',0),(4295,115,1640,0,2,7,0,14,1066991725,11,155,'',0),(4294,115,7,0,1,1640,1640,14,1066991725,11,155,'',0),(4293,115,1640,0,0,0,7,14,1066991725,11,152,'',0),(4305,116,1645,0,3,25,0,14,1066992054,11,155,'',0),(4304,116,25,0,2,1644,1645,14,1066992054,11,155,'',0),(4303,116,1644,0,1,1643,25,14,1066992054,11,152,'',0),(4302,116,1643,0,0,0,1644,14,1066992054,11,152,'',0),(4296,45,32,0,0,0,33,14,1066388816,11,152,'',0),(5524,262,2167,0,0,0,2168,23,1069752445,1,201,'',0),(5523,261,2166,0,26,2165,0,23,1069752332,1,203,'',0),(5518,261,33,0,21,2163,1797,23,1069752332,1,203,'',0),(5519,261,1797,0,22,33,2164,23,1069752332,1,203,'',0),(5520,261,2164,0,23,1797,1439,23,1069752332,1,203,'',0),(5521,261,1439,0,24,2164,2165,23,1069752332,1,203,'',0),(5522,261,2165,0,25,1439,2166,23,1069752332,1,203,'',0),(3967,14,1540,0,5,1316,0,4,1033920830,2,199,'',0),(3966,14,1316,0,4,1539,1540,4,1033920830,2,198,'',0),(5318,222,2091,0,42,2088,73,24,1068554919,1,207,'',0),(5317,222,2088,0,41,2092,2091,24,1068554919,1,207,'',0),(5316,222,2092,0,40,33,2088,24,1068554919,1,207,'',0),(5315,222,33,0,39,2091,2092,24,1068554919,1,207,'',0),(5314,222,2091,0,38,2090,33,24,1068554919,1,207,'',0),(5313,222,2090,0,37,2089,2091,24,1068554919,1,207,'',0),(5312,222,2089,0,36,2088,2090,24,1068554919,1,207,'',0),(5311,222,2088,0,35,2087,2089,24,1068554919,1,207,'',0),(5310,222,2087,0,34,2085,2088,24,1068554919,1,207,'',0),(5309,222,2085,0,33,2086,2087,24,1068554919,1,207,'',0),(5308,222,2086,0,32,2085,2085,24,1068554919,1,207,'',0),(5307,222,2085,0,31,2084,2086,24,1068554919,1,207,'',0),(5306,222,2084,0,30,74,2085,24,1068554919,1,207,'',0),(5305,222,74,0,29,1943,2084,24,1068554919,1,207,'',0),(5304,222,1943,0,28,1750,74,24,1068554919,1,207,'',0),(5303,222,1750,0,27,1614,1943,24,1068554919,1,207,'',0),(5302,222,1614,0,26,2083,1750,24,1068554919,1,207,'',0),(5301,222,2083,0,25,2082,1614,24,1068554919,1,207,'',0),(5300,222,2082,0,24,1797,2083,24,1068554919,1,207,'',0),(5299,222,1797,0,23,1765,2082,24,1068554919,1,207,'',0),(5298,222,1765,0,22,2081,1797,24,1068554919,1,207,'',0),(5297,222,2081,0,21,1417,1765,24,1068554919,1,207,'',0),(5296,222,1417,0,20,1824,2081,24,1068554919,1,207,'',0),(5295,222,1824,0,19,1614,1417,24,1068554919,1,207,'',0),(5294,222,1614,0,18,2023,1824,24,1068554919,1,207,'',0),(5293,222,2023,0,17,1620,1614,24,1068554919,1,207,'',0),(5292,222,1620,0,16,1951,2023,24,1068554919,1,207,'',0),(5291,222,1951,0,15,2080,1620,24,1068554919,1,207,'',0),(5290,222,2080,0,14,2079,1951,24,1068554919,1,207,'',0),(5289,222,2079,0,13,2078,2080,24,1068554919,1,207,'',0),(5288,222,2078,0,12,2027,2079,24,1068554919,1,207,'',0),(5287,222,2027,0,11,2077,2078,24,1068554919,1,207,'',0),(5286,222,2077,0,10,1862,2027,24,1068554919,1,207,'',0),(5285,222,1862,0,9,2076,2077,24,1068554919,1,207,'',0),(5284,222,2076,0,8,1797,1862,24,1068554919,1,207,'',0),(5283,222,1797,0,7,1869,2076,24,1068554919,1,207,'',0),(5282,222,1869,0,6,74,1797,24,1068554919,1,207,'',0),(5281,222,74,0,5,2060,1869,24,1068554919,1,207,'',0),(5275,161,2074,0,87,2070,0,10,1068047603,1,141,'',0),(5274,161,2070,0,86,2073,2074,10,1068047603,1,141,'',0),(5273,161,2073,0,85,1522,2070,10,1068047603,1,141,'',0),(5272,161,1522,0,84,1723,2073,10,1068047603,1,141,'',0),(5271,161,1723,0,83,2072,1522,10,1068047603,1,141,'',0),(5270,161,2072,0,82,2071,1723,10,1068047603,1,141,'',0),(5269,161,2071,0,81,2045,2072,10,1068047603,1,141,'',0),(5268,161,2045,0,80,1142,2071,10,1068047603,1,141,'',0),(5267,161,1142,0,79,1723,2045,10,1068047603,1,141,'',0),(5266,161,1723,0,78,2072,1142,10,1068047603,1,141,'',0),(5265,161,2072,0,77,2071,1723,10,1068047603,1,141,'',0),(5264,161,2071,0,76,2070,2072,10,1068047603,1,141,'',0),(5263,161,2070,0,75,2069,2071,10,1068047603,1,141,'',0),(5262,161,2069,0,74,1912,2070,10,1068047603,1,141,'',0),(5261,161,1912,0,73,2068,2069,10,1068047603,1,141,'',0),(5260,161,2068,0,72,2067,1912,10,1068047603,1,141,'',0),(5259,161,2067,0,71,2066,2068,10,1068047603,1,141,'',0),(5258,161,2066,0,70,2065,2067,10,1068047603,1,141,'',0),(5257,161,2065,0,69,1738,2066,10,1068047603,1,141,'',0),(5256,161,1738,0,68,2064,2065,10,1068047603,1,141,'',0),(5255,161,2064,0,67,2063,1738,10,1068047603,1,141,'',0),(5254,161,2063,0,66,2062,2064,10,1068047603,1,141,'',0),(5253,161,2062,0,65,1768,2063,10,1068047603,1,141,'',0),(5252,161,1768,0,64,1951,2062,10,1068047603,1,141,'',0),(5251,161,1951,0,63,2061,1768,10,1068047603,1,141,'',0),(5250,161,2061,0,62,73,1951,10,1068047603,1,141,'',0),(5249,161,73,0,61,1804,2061,10,1068047603,1,141,'',0),(5248,161,1804,0,60,2060,73,10,1068047603,1,141,'',0),(5247,161,2060,0,59,1439,1804,10,1068047603,1,141,'',0),(5246,161,1439,0,58,2029,2060,10,1068047603,1,141,'',0),(5245,161,2029,0,57,1808,1439,10,1068047603,1,141,'',0),(5244,161,1808,0,56,1660,2029,10,1068047603,1,141,'',0),(5243,161,1660,0,55,1797,1808,10,1068047603,1,141,'',0),(5242,161,1797,0,54,1672,1660,10,1068047603,1,141,'',0),(5241,161,1672,0,53,2059,1797,10,1068047603,1,141,'',0),(5240,161,2059,0,52,1614,1672,10,1068047603,1,141,'',0),(5239,161,1614,0,51,1797,2059,10,1068047603,1,141,'',0),(5238,161,1797,0,50,1663,1614,10,1068047603,1,141,'',0),(5237,161,1663,0,49,1689,1797,10,1068047603,1,141,'',0),(5236,161,1689,0,48,1439,1663,10,1068047603,1,141,'',0),(5235,161,1439,0,47,1985,1689,10,1068047603,1,141,'',0),(5234,161,1985,0,46,74,1439,10,1068047603,1,141,'',0),(5233,161,74,0,45,1607,1985,10,1068047603,1,141,'',0),(5232,161,1607,0,44,2058,74,10,1068047603,1,141,'',0),(5231,161,2058,0,43,1700,1607,10,1068047603,1,141,'',0),(5230,161,1700,0,42,1620,2058,10,1068047603,1,141,'',0),(5229,161,1620,0,41,2057,1700,10,1068047603,1,141,'',0),(5228,161,2057,0,40,1439,1620,10,1068047603,1,141,'',0),(5227,161,1439,0,39,1519,2057,10,1068047603,1,141,'',0),(5226,161,1519,0,38,1894,1439,10,1068047603,1,141,'',0),(5225,161,1894,0,37,74,1519,10,1068047603,1,141,'',0),(5224,161,74,0,36,1607,1894,10,1068047603,1,141,'',0),(5223,161,1607,0,35,2036,74,10,1068047603,1,141,'',0),(5222,161,2036,0,34,74,1607,10,1068047603,1,141,'',0),(5221,161,74,0,33,73,2036,10,1068047603,1,141,'',0),(5220,161,73,0,32,74,74,10,1068047603,1,141,'',0),(5219,161,74,0,31,2056,73,10,1068047603,1,141,'',0),(5218,161,2056,0,30,1614,74,10,1068047603,1,141,'',0),(5217,161,1614,0,29,1717,2056,10,1068047603,1,141,'',0),(5216,161,1717,0,28,1732,1614,10,1068047603,1,141,'',0),(5215,161,1732,0,27,1797,1717,10,1068047603,1,141,'',0),(5214,161,1797,0,26,1614,1732,10,1068047603,1,141,'',0),(5213,161,1614,0,25,1534,1797,10,1068047603,1,141,'',0),(5212,161,1534,0,24,1700,1614,10,1068047603,1,141,'',0),(5211,161,1700,0,23,2055,1534,10,1068047603,1,141,'',0),(5210,161,2055,0,22,1807,1700,10,1068047603,1,141,'',0),(5209,161,1807,0,21,1660,2055,10,1068047603,1,141,'',0),(5208,161,1660,0,20,2030,1807,10,1068047603,1,141,'',0),(5207,161,2030,0,19,1883,1660,10,1068047603,1,141,'',0),(5206,161,1883,0,18,2054,2030,10,1068047603,1,141,'',0),(5205,161,2054,0,17,1945,1883,10,1068047603,1,141,'',0),(5204,161,1945,0,16,1862,2054,10,1068047603,1,141,'',0),(5203,161,1862,0,15,1417,1945,10,1068047603,1,141,'',0),(5202,161,1417,0,14,2053,1862,10,1068047603,1,141,'',0),(5201,161,2053,0,13,1950,1417,10,1068047603,1,141,'',0),(5200,161,1950,0,12,2052,2053,10,1068047603,1,141,'',0),(5188,161,2050,0,0,0,33,10,1068047603,1,140,'',0),(5189,161,33,0,1,2050,2051,10,1068047603,1,140,'',0),(5190,161,2051,0,2,33,2050,10,1068047603,1,140,'',0),(5191,161,2050,0,3,2051,33,10,1068047603,1,141,'',0),(5192,161,33,0,4,2050,2051,10,1068047603,1,141,'',0),(5193,161,2051,0,5,33,1660,10,1068047603,1,141,'',0),(5194,161,1660,0,6,2051,1986,10,1068047603,1,141,'',0),(5195,161,1986,0,7,1660,1787,10,1068047603,1,141,'',0),(5196,161,1787,0,8,1986,1519,10,1068047603,1,141,'',0),(5197,161,1519,0,9,1787,1142,10,1068047603,1,141,'',0),(5198,161,1142,0,10,1519,2052,10,1068047603,1,141,'',0),(5199,161,2052,0,11,1142,1950,10,1068047603,1,141,'',0),(3965,14,1539,0,3,1538,1316,4,1033920830,2,198,'',0),(3964,14,1538,0,2,1537,1539,4,1033920830,2,197,'',0),(3963,14,1537,0,1,1536,1538,4,1033920830,2,9,'',0),(3962,14,1536,0,0,0,1537,4,1033920830,2,8,'',0),(5515,261,1717,0,18,2162,1614,23,1069752332,1,203,'',0),(5516,261,1614,0,19,1717,2163,23,1069752332,1,203,'',0),(5517,261,2163,0,20,1614,33,23,1069752332,1,203,'',0),(5471,257,2139,0,0,0,0,1,1069751025,1,4,'',0),(5473,258,2141,0,0,0,0,1,1069751059,1,4,'',0),(5474,259,2142,0,0,0,0,1,1069751462,1,4,'',0),(3961,213,1534,0,2,1535,0,1,1068473231,1,119,'',0),(4845,1,1940,0,0,0,0,1,1033917596,1,4,'',0),(3960,213,1535,0,1,1534,1534,1,1068473231,1,119,'',0),(3959,213,1534,0,0,0,1535,1,1068473231,1,4,'',0),(5482,260,2146,0,7,1668,1142,23,1069752252,1,203,'',0),(5483,260,1142,0,8,2146,2147,23,1069752252,1,203,'',0),(5484,260,2147,0,9,1142,2148,23,1069752252,1,203,'',0),(5485,260,2148,0,10,2147,2149,23,1069752252,1,203,'',0),(5152,219,1945,0,66,1862,0,10,1068542692,1,141,'',0),(5151,219,1862,0,65,1519,1945,10,1068542692,1,141,'',0),(5150,219,1519,0,64,2037,1862,10,1068542692,1,141,'',0),(5149,219,2037,0,63,1912,1519,10,1068542692,1,141,'',0),(5148,219,1912,0,62,1729,2037,10,1068542692,1,141,'',0),(5147,219,1729,0,61,1142,1912,10,1068542692,1,141,'',0),(5146,219,1142,0,60,1782,1729,10,1068542692,1,141,'',0),(5145,219,1782,0,59,1612,1142,10,1068542692,1,141,'',0),(5144,219,1612,0,58,1607,1782,10,1068542692,1,141,'',0),(5143,219,1607,0,57,2036,1612,10,1068542692,1,141,'',0),(5142,219,2036,0,56,1670,1607,10,1068542692,1,141,'',0),(5141,219,1670,0,55,2035,2036,10,1068542692,1,141,'',0),(5140,219,2035,0,54,1670,1670,10,1068542692,1,141,'',0),(5139,219,1670,0,53,73,2035,10,1068542692,1,141,'',0),(5138,219,73,0,52,2034,1670,10,1068542692,1,141,'',0),(5137,219,2034,0,51,1797,73,10,1068542692,1,141,'',0),(5136,219,1797,0,50,1672,2034,10,1068542692,1,141,'',0),(5135,219,1672,0,49,1950,1797,10,1068542692,1,141,'',0),(5134,219,1950,0,48,2007,1672,10,1068542692,1,141,'',0),(5133,219,2007,0,47,2033,1950,10,1068542692,1,141,'',0),(5132,219,2033,0,46,74,2007,10,1068542692,1,141,'',0),(5131,219,74,0,45,1607,2033,10,1068542692,1,141,'',0),(5130,219,1607,0,44,33,74,10,1068542692,1,141,'',0),(5129,219,33,0,43,73,1607,10,1068542692,1,141,'',0),(5128,219,73,0,42,1768,33,10,1068542692,1,141,'',0),(5127,219,1768,0,41,2032,73,10,1068542692,1,141,'',0),(5126,219,2032,0,40,1417,1768,10,1068542692,1,141,'',0),(5125,219,1417,0,39,2031,2032,10,1068542692,1,141,'',0),(5124,219,2031,0,38,2007,1417,10,1068542692,1,141,'',0),(5123,219,2007,0,37,1660,2031,10,1068542692,1,141,'',0),(5122,219,1660,0,36,2030,2007,10,1068542692,1,141,'',0),(5121,219,2030,0,35,2029,1660,10,1068542692,1,141,'',0),(5120,219,2029,0,34,1663,2030,10,1068542692,1,141,'',0),(5119,219,1663,0,33,1607,2029,10,1068542692,1,141,'',0),(5118,219,1607,0,32,1520,1663,10,1068542692,1,141,'',0),(5117,219,1520,0,31,1807,1607,10,1068542692,1,141,'',0),(5116,219,1807,0,30,1797,1520,10,1068542692,1,141,'',0),(5115,219,1797,0,29,2028,1807,10,1068542692,1,141,'',0),(5114,219,2028,0,28,1842,1797,10,1068542692,1,141,'',0),(5113,219,1842,0,27,33,2028,10,1068542692,1,141,'',0),(5112,219,33,0,26,1607,1842,10,1068542692,1,141,'',0),(5111,219,1607,0,25,1765,33,10,1068542692,1,141,'',0),(5110,219,1765,0,24,2028,1607,10,1068542692,1,141,'',0),(5109,219,2028,0,23,1797,1765,10,1068542692,1,141,'',0),(5108,219,1797,0,22,2028,2028,10,1068542692,1,141,'',0),(5107,219,2028,0,21,1842,1797,10,1068542692,1,141,'',0),(5106,219,1842,0,20,2027,2028,10,1068542692,1,141,'',0),(5105,219,2027,0,19,1862,1842,10,1068542692,1,141,'',0),(5104,219,1862,0,18,1144,2027,10,1068542692,1,141,'',0),(5103,219,1144,0,17,2026,1862,10,1068542692,1,141,'',0),(5102,219,2026,0,16,1797,1144,10,1068542692,1,141,'',0),(5101,219,1797,0,15,1951,2026,10,1068542692,1,141,'',0),(5100,219,1951,0,14,2025,1797,10,1068542692,1,141,'',0),(5099,219,2025,0,13,1797,1951,10,1068542692,1,141,'',0),(5098,219,1797,0,12,2024,2025,10,1068542692,1,141,'',0),(5097,219,2024,0,11,2023,1797,10,1068542692,1,141,'',0),(5096,219,2023,0,10,1768,2024,10,1068542692,1,141,'',0),(5095,219,1768,0,9,1982,2023,10,1068542692,1,141,'',0),(5094,219,1982,0,8,1984,1768,10,1068542692,1,141,'',0),(5093,219,1984,0,7,1797,1982,10,1068542692,1,141,'',0),(5092,219,1797,0,6,2022,1984,10,1068542692,1,141,'',0),(5091,219,2022,0,5,2021,1797,10,1068542692,1,141,'',0),(5090,219,2021,0,4,1142,2022,10,1068542692,1,141,'',0),(5089,219,1142,0,3,1417,2021,10,1068542692,1,141,'',0),(5088,219,1417,0,2,2022,1142,10,1068542692,1,141,'',0),(5087,219,2022,0,1,2021,1417,10,1068542692,1,140,'',0),(5086,219,2021,0,0,0,2022,10,1068542692,1,140,'',0),(5182,220,2045,0,29,1142,0,10,1068542738,1,141,'',0),(5181,220,1142,0,28,2044,2045,10,1068542738,1,141,'',0),(5180,220,2044,0,27,1750,1142,10,1068542738,1,141,'',0),(5179,220,1750,0,26,1797,2044,10,1068542738,1,141,'',0),(5178,220,1797,0,25,2043,1750,10,1068542738,1,141,'',0),(5177,220,2043,0,24,1142,1797,10,1068542738,1,141,'',0),(5176,220,1142,0,23,2042,2043,10,1068542738,1,141,'',0),(5175,220,2042,0,22,1985,1142,10,1068542738,1,141,'',0),(5174,220,1985,0,21,1607,2042,10,1068542738,1,141,'',0),(5173,220,1607,0,20,1945,1985,10,1068542738,1,141,'',0),(5172,220,1945,0,19,1862,1607,10,1068542738,1,141,'',0),(5171,220,1862,0,18,1417,1945,10,1068542738,1,141,'',0),(5170,220,1417,0,17,2041,1862,10,1068542738,1,141,'',0),(5169,220,2041,0,16,33,1417,10,1068542738,1,141,'',0),(5168,220,33,0,15,2040,2041,10,1068542738,1,141,'',0),(5167,220,2040,0,14,2039,33,10,1068542738,1,141,'',0),(5166,220,2039,0,13,2030,2040,10,1068542738,1,141,'',0),(5165,220,2030,0,12,2023,2039,10,1068542738,1,141,'',0),(5164,220,2023,0,11,2034,2030,10,1068542738,1,141,'',0),(5163,220,2034,0,10,1797,2023,10,1068542738,1,141,'',0),(5162,220,1797,0,9,1869,2034,10,1068542738,1,141,'',0),(5161,220,1869,0,8,74,1797,10,1068542738,1,141,'',0),(5160,220,74,0,7,1520,1869,10,1068542738,1,141,'',0),(5159,220,1520,0,6,1519,74,10,1068542738,1,141,'',0),(5158,220,1519,0,5,2038,1520,10,1068542738,1,141,'',0),(5157,220,2038,0,4,1142,1519,10,1068542738,1,141,'',0),(5156,220,1142,0,3,1520,2038,10,1068542738,1,141,'',0),(5155,220,1520,0,2,1519,1142,10,1068542738,1,140,'',0),(5154,220,1519,0,1,2038,1520,10,1068542738,1,140,'',0),(5153,220,2038,0,0,0,1519,10,1068542738,1,140,'',0),(5481,260,1668,0,6,33,2146,23,1069752252,1,203,'',0),(5480,260,33,0,5,2145,1668,23,1069752252,1,203,'',0),(5479,260,2145,0,4,1142,33,23,1069752252,1,203,'',0),(5478,260,1142,0,3,2144,2145,23,1069752252,1,203,'',0),(5477,260,2144,0,2,2143,1142,23,1069752252,1,203,'',0),(5476,260,2143,0,1,2143,2144,23,1069752252,1,203,'',0),(5475,260,2143,0,0,0,2143,23,1069752252,1,201,'',0),(5280,222,2060,0,4,2075,74,24,1068554919,1,207,'',0),(5279,222,2075,0,3,1439,2060,24,1068554919,1,207,'',0),(5278,222,1439,0,2,1522,2075,24,1068554919,1,207,'',0),(5277,222,1522,0,1,2075,1439,24,1068554919,1,206,'',0),(5276,222,2075,0,0,0,1522,24,1068554919,1,206,'',0),(5557,263,1614,0,7,2179,2056,23,1069752520,1,203,'',0),(5556,263,2179,0,6,1797,1614,23,1069752520,1,203,'',0),(5555,263,1797,0,5,2178,2179,23,1069752520,1,203,'',0),(5554,263,2178,0,4,2177,1797,23,1069752520,1,203,'',0),(5553,263,2177,0,3,2176,2178,23,1069752520,1,202,'',123414),(5552,263,2176,0,2,1946,2177,23,1069752520,1,201,'',0),(5551,263,1946,0,1,2135,2176,23,1069752520,1,201,'',0),(5550,263,2135,0,0,0,1946,23,1069752520,1,201,'',0),(5549,262,1316,0,25,1417,0,23,1069752445,1,203,'',0),(5548,262,1417,0,24,2175,1316,23,1069752445,1,203,'',0),(5547,262,2175,0,23,2174,1417,23,1069752445,1,203,'',0),(5546,262,2174,0,22,1142,2175,23,1069752445,1,203,'',0),(5545,262,1142,0,21,1519,2174,23,1069752445,1,203,'',0),(5544,262,1519,0,20,2173,1142,23,1069752445,1,203,'',0),(5543,262,2173,0,19,1765,1519,23,1069752445,1,203,'',0),(5542,262,1765,0,18,2172,2173,23,1069752445,1,203,'',0),(5541,262,2172,0,17,74,1765,23,1069752445,1,203,'',0),(5540,262,74,0,16,2168,2172,23,1069752445,1,203,'',0),(5539,262,2168,0,15,1142,74,23,1069752445,1,203,'',0),(5538,262,1142,0,14,2167,2168,23,1069752445,1,203,'',0),(5537,262,2167,0,13,1519,1142,23,1069752445,1,203,'',0),(5536,262,1519,0,12,2171,2167,23,1069752445,1,203,'',0),(5535,262,2171,0,11,33,1519,23,1069752445,1,203,'',0),(5534,262,33,0,10,2170,2171,23,1069752445,1,203,'',0),(5533,262,2170,0,9,1142,33,23,1069752445,1,203,'',0),(5532,262,1142,0,8,1890,2170,23,1069752445,1,203,'',0),(5531,262,1890,0,7,1768,1142,23,1069752445,1,203,'',0),(5530,262,1768,0,6,74,1890,23,1069752445,1,203,'',0),(5525,262,2168,0,1,2167,2169,23,1069752445,1,201,'',0),(5526,262,2169,0,2,2168,1142,23,1069752445,1,202,'',1324),(5527,262,1142,0,3,2169,2168,23,1069752445,1,203,'',0),(5528,262,2168,0,4,1142,74,23,1069752445,1,203,'',0),(5529,262,74,0,5,2168,1768,23,1069752445,1,203,'',0),(5617,265,2200,0,23,1614,0,23,1069752921,1,203,'',0),(5616,265,1614,0,22,2200,2200,23,1069752921,1,203,'',0),(5615,265,2200,0,21,1144,1614,23,1069752921,1,203,'',0),(5614,265,1144,0,20,2190,2200,23,1069752921,1,203,'',0),(5613,265,2190,0,19,2199,1144,23,1069752921,1,203,'',0),(5612,265,2199,0,18,2198,2190,23,1069752921,1,203,'',0),(5611,265,2198,0,17,1519,2199,23,1069752921,1,203,'',0),(5610,265,1519,0,16,2197,2198,23,1069752921,1,203,'',0),(5609,265,2197,0,15,2196,1519,23,1069752921,1,203,'',0),(5608,265,2196,0,14,2195,2197,23,1069752921,1,203,'',0),(5607,265,2195,0,13,1144,2196,23,1069752921,1,203,'',0),(5606,265,1144,0,12,2194,2195,23,1069752921,1,203,'',0),(5605,265,2194,0,11,2193,1144,23,1069752921,1,203,'',0),(5604,265,2193,0,10,1142,2194,23,1069752921,1,203,'',0),(5603,265,1142,0,9,1144,2193,23,1069752921,1,203,'',0),(5602,265,1144,0,8,2192,1142,23,1069752921,1,203,'',0),(5601,265,2192,0,7,2190,1144,23,1069752921,1,203,'',0),(5600,265,2190,0,6,2186,2192,23,1069752921,1,203,'',0),(5599,265,2186,0,5,1142,2190,23,1069752921,1,203,'',0),(5598,265,1142,0,4,1144,2186,23,1069752921,1,203,'',0),(5597,265,1144,0,3,2191,1142,23,1069752921,1,203,'',0),(5596,265,2191,0,2,2142,1144,23,1069752921,1,203,'',0),(5595,265,2142,0,1,2190,2191,23,1069752921,1,201,'',0),(5594,265,2190,0,0,0,2142,23,1069752921,1,201,'',0),(5593,264,2189,0,23,2187,0,23,1069752759,1,203,'',0),(5592,264,2187,0,22,1144,2189,23,1069752759,1,203,'',0),(5591,264,1144,0,21,2188,2187,23,1069752759,1,203,'',0),(5590,264,2188,0,20,1142,1144,23,1069752759,1,203,'',0),(5589,264,1142,0,19,1519,2188,23,1069752759,1,203,'',0),(5588,264,1519,0,18,2187,1142,23,1069752759,1,203,'',0),(5587,264,2187,0,17,1890,1519,23,1069752759,1,203,'',0),(5586,264,1890,0,16,2186,2187,23,1069752759,1,203,'',0),(5585,264,2186,0,15,1142,1890,23,1069752759,1,203,'',0),(5584,264,1142,0,14,1519,2186,23,1069752759,1,203,'',0),(5583,264,1519,0,13,2186,1142,23,1069752759,1,203,'',0),(5582,264,2186,0,12,1142,1519,23,1069752759,1,203,'',0),(5581,264,1142,0,11,2138,2186,23,1069752759,1,203,'',0),(5580,264,2138,0,10,2185,1142,23,1069752759,1,203,'',0),(5579,264,2185,0,9,1142,2138,23,1069752759,1,203,'',0),(5578,264,1142,0,8,1144,2185,23,1069752759,1,203,'',0),(5577,264,1144,0,7,1094,1142,23,1069752759,1,203,'',0),(5576,264,1094,0,6,1519,1144,23,1069752759,1,203,'',0),(5575,264,1519,0,5,2184,1094,23,1069752759,1,203,'',0),(5574,264,2184,0,4,1439,1519,23,1069752759,1,203,'',0),(5573,264,1439,0,3,2183,2184,23,1069752759,1,203,'',0),(5572,264,2183,0,2,2142,1439,23,1069752759,1,202,'',60897),(5571,264,2142,0,1,1094,2183,23,1069752759,1,201,'',0),(5570,264,1094,0,0,0,2142,23,1069752759,1,201,'',0),(5569,263,1958,0,19,2182,0,23,1069752520,1,203,'',0),(5568,263,2182,0,18,1142,1958,23,1069752520,1,203,'',0),(5567,263,1142,0,17,1614,2182,23,1069752520,1,203,'',0),(5566,263,1614,0,16,2181,1142,23,1069752520,1,203,'',0),(5565,263,2181,0,15,1144,1614,23,1069752520,1,203,'',0),(5564,263,1144,0,14,2180,2181,23,1069752520,1,203,'',0),(5563,263,2180,0,13,1890,1144,23,1069752520,1,203,'',0),(5562,263,1890,0,12,1946,2180,23,1069752520,1,203,'',0),(5561,263,1946,0,11,2135,1890,23,1069752520,1,203,'',0),(5560,263,2135,0,10,1768,1946,23,1069752520,1,203,'',0),(5559,263,1768,0,9,2056,2135,23,1069752520,1,203,'',0),(5558,263,2056,0,8,1614,1768,23,1069752520,1,203,'',0),(5633,56,2138,0,7,2210,0,15,1066643397,11,218,'',0),(5632,56,2210,0,6,1670,2138,15,1066643397,11,218,'',0),(5631,56,1670,0,5,2209,2210,15,1066643397,11,218,'',0),(5630,56,2209,0,4,2135,1670,15,1066643397,11,218,'',0),(5629,56,2135,0,3,2208,2209,15,1066643397,11,218,'',0),(5628,56,2208,0,2,2207,2135,15,1066643397,11,218,'',0),(5627,56,2207,0,1,2206,2208,15,1066643397,11,218,'',0),(5626,56,2206,0,0,0,2207,15,1066643397,11,161,'',0);
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
INSERT INTO ezsearch_word VALUES (6,'media',1),(7,'setup',3),(1639,'grouplist',1),(1638,'class',1),(1637,'classes',1),(11,'links',1),(25,'content',2),(34,'feel',2),(33,'and',13),(32,'look',2),(37,'news',4),(2139,'books',1),(2141,'cars',2),(73,'this',7),(74,'is',8),(1519,'of',7),(1520,'use',2),(1094,'music',1),(2154,'enjoy',1),(2155,'feeling',1),(2156,'s',1),(2157,'nothing',1),(2158,'more',1),(2159,'say',1),(2160,'ever',1),(2161,'tried',1),(2162,'never',1),(2163,'leave',1),(2038,'conditions',1),(2178,'everything',1),(1945,'webshop',4),(1944,'latest',1),(1522,'us',2),(2078,'partners',1),(2076,'let',1),(2077,'readers',1),(2164,'re',1),(1640,'cache',1),(2153,'ferrari',1),(2152,'total',1),(2075,'contact',1),(1942,'october',1),(1144,'from',6),(1941,'bulletin',2),(1142,'the',13),(2142,'dvd',3),(1540,'developer',1),(1316,'norway',3),(1539,'skien',1),(1538,'uberguru',1),(2103,'while',1),(2021,'privacy',1),(2102,'£13',1),(2101,'low',1),(2100,'supplier',1),(2099,'some',1),(2098,'received',1),(2097,'updates',1),(2096,'tip',1),(2095,'wants',1),(2094,'used',1),(2093,'often',1),(2092,'snail',1),(2091,'address',1),(2090,'visitors',1),(2089,'addresses',1),(2088,'mail',1),(2087,'e',1),(2086,'fax',1),(2085,'numbers',1),(2084,'telephone',1),(1439,'a',7),(2083,'info',1),(2082,'normal',1),(2081,'touch',1),(2080,'find',1),(2079,'etc',1),(2074,'exceptions',1),(2073,'replacement',1),(2072,'cancellation',1),(2071,'order',1),(2070,'goods',1),(2069,'defective',1),(2068,'faulty',1),(2067,'rights',1),(2066,'return',1),(2065,'period',1),(2064,'cooling',1),(2063,'time',1),(1417,'in',7),(2062,'delivery',1),(2061,'contains',1),(2060,'page',2),(2059,'show',1),(2058,'bahalf',1),(2057,'guarantee',1),(2056,'know',2),(2055,'returning',1),(2053,'pages',1),(1940,'shop',3),(2168,'book',1),(2167,'summer',1),(1642,'56',1),(1641,'edit',1),(1644,'translator',1),(1643,'url',1),(2054,'even',1),(1535,'our',2),(2166,'forever',1),(1534,'products',4),(2052,'most',1),(1943,'here',2),(2210,'1999',1),(2138,'2003',2),(2051,'returns',1),(2050,'shipping',1),(2165,'fan',1),(2151,'factory',1),(1536,'administrator',1),(1537,'user',1),(2147,'only',1),(1995,'available',1),(2180,'steps',1),(2177,'123414',1),(2176,'basics',1),(2175,'landscape',1),(2174,'beautiful',1),(1622,'website',1),(1620,'on',3),(1614,'to',6),(1612,'can',4),(1611,'i',1),(2173,'picures',1),(1607,'it',5),(1645,'urltranslator',1),(2172,'packed',1),(1660,'are',5),(1663,'for',6),(2171,'smells',1),(1666,'will',2),(1668,'so',2),(1670,'as',5),(1672,'that',4),(1688,'long',2),(1689,'way',1),(1693,'two',1),(1700,'their',1),(1717,'want',2),(1723,'by',1),(1728,'finally',1),(1729,'make',1),(1732,'they',3),(1738,'off',2),(1747,'we',1),(2170,'colors',1),(1750,'have',5),(1761,'new',3),(1765,'with',4),(1768,'about',5),(2150,'left',1),(1782,'be',2),(1783,'many',1),(1787,'one',3),(1790,'5',2),(1797,'you',9),(1804,'like',2),(1807,'not',2),(1808,'professional',1),(2169,'1324',1),(1824,'get',2),(2182,'finished',1),(1842,'what',1),(1862,'your',4),(1869,'where',2),(1883,'if',2),(1890,'all',6),(1894,'kind',1),(2200,'back',1),(2199,'stop',1),(2198,'non',1),(2197,'hours',1),(2196,'3',1),(2195,'hollywood',1),(2194,'actors',1),(2193,'leading',1),(2192,'movies',1),(2191,'clips',1),(2190,'action',1),(2189,'100',1),(2188,'charts',1),(2187,'top',1),(1912,'or',2),(2186,'best',2),(2185,'year',1),(2184,'collection',1),(2183,'60897',1),(1917,'great',1),(1918,'1',1),(2181,'download',1),(2179,'need',1),(2209,'systems',1),(2135,'ez',2),(2208,'&copy',1),(2207,'copyright',1),(2206,'shop_package',1),(1946,'publish',2),(1947,'these',2),(1948,'soon',1),(1949,'releases',1),(1950,'important',3),(1951,'information',4),(1952,'tell',1),(1953,'release',2),(1954,'see',2),(1955,'step',1),(1956,'forward',1),(1957,'old',1),(1958,'site',3),(1966,'november',1),(1967,'month',1),(1968,'started',1),(1969,'product',1),(1970,'b',1),(1971,'both',1),(1972,'part',1),(1973,'portfolio',1),(1974,'basis',1),(1975,'there',1),(1976,'examples',1),(1977,'different',1),(1978,'categories',1),(1979,'add',1),(1980,'set',1),(1981,'prices',1),(1982,'write',2),(1983,'texts',1),(1984,'should',2),(1985,'also',4),(1986,'always',2),(1987,'pictures',1),(1988,'users',1),(1989,'reading',1),(2149,'made',1),(2148,'car',1),(2007,'very',1),(2146,'far',1),(2145,'first',1),(2144,'was',1),(2143,'troll',1),(2012,'cords',2),(2013,'meter',3),(2014,'cord',3),(2015,'13444',1),(2016,'works',2),(2017,'machines',2),(2018,'34555',1),(2019,'five',2),(2020,'meters',1),(2022,'notice',1),(2023,'how',3),(2024,'secure',1),(2025,'handle',1),(2026,'collect',1),(2027,'customers',2),(2028,'do',1),(2029,'normally',2),(2030,'people',4),(2031,'interested',1),(2032,'knowing',1),(2033,'therefore',1),(2034,'state',2),(2035,'clear',1),(2036,'possible',2),(2037,'breake',1),(2039,'shall',1),(2040,'act',1),(2041,'behave',1),(2042,'states',1),(2043,'policy',1),(2044,'towards',1),(2045,'customer',2);
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
INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart'),(2,'Users','','ezusernavigationpart'),(3,'Media','','ezmedianavigationpart'),(11,'Set up object','','ezsetupnavigationpart');
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
) TYPE=MyISAM ;

--
-- Dumping data for table 'ezsubtree_notification_rule'
--

/*!40000 ALTER TABLE ezsubtree_notification_rule DISABLE KEYS */;
LOCK TABLES ezsubtree_notification_rule WRITE;
INSERT INTO ezsubtree_notification_rule VALUES (1,'nospam@ez.no',0,112),(2,'nospam@ez.no',0,112),(3,'nospam@ez.no',0,123),(9,'nospam@ez.no',0,153),(8,'nospam@ez.no',0,165),(6,'nospam@ez.no',0,114),(7,'nospam@ez.no',0,152),(10,'nospam@ez.no',0,165);
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0),(125,'discussions/music_discussion/latest_msg_not_sticky','1980b453976fed108ef2874bac0f8477','content/view/full/130',1,0,0),(126,'discussions/music_discussion/not_sticky_2','06916ca78017a7482957aa4997f66664','content/view/full/131',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,179,0),(124,'discussions/music_discussion/new_topic_sticky/reply','ae271e634c8d9cb077913b222e4b9d17','content/view/full/129',1,0,0),(121,'news/news_bulletin','9365952d8950c12f923a3a48e5e27fa3','content/view/full/126',1,178,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,157,0),(123,'discussions/music_discussion/new_topic_sticky','493ae5ad7ceb46af67edfdaf244d047a','content/view/full/128',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,179,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,179,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(127,'discussions/music_discussion/important_sticky','5b25f18de9f5bafe8050dafdaa759fca','content/view/full/132',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(202,'products/dvd/music_dvd','d0cc19fd0f214acc42b83c7a8aefc0ca','content/view/full/190',1,0,0),(105,'discussions/music_discussion/this_is_a_new_topic','2344619129cdcf0b057b66b259d43a86','content/view/full/112',1,0,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(117,'discussions/music_discussion/this_is_a_new_topic/foo_bar','8ccf76d178398a5021594b8dcc111ef3','content/view/full/122',1,0,0),(178,'news/news_bulletin_october','4bb330d0024e02fb3954cbd69fca08c8','content/view/full/126',1,0,0),(111,'discussions/sports_discussion/football','687ae615eecb9131ce8600e02f087921','content/view/full/119',1,0,0),(149,'hardware','3ca14c518d1bf901acc339e7c9cd6d7f','content/view/full/154',1,162,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,179,0),(114,'discussions/music_discussion/this_is_a_new_topic/my_reply','295c0cf1dfb0786654b87ae7879269ce','content/view/full/120',1,0,0),(118,'discussions/music_discussion/what_about_pop','29e6fdc68db2a2820a4198ccf9606316','content/view/full/123',1,0,0),(119,'discussions/music_discussion/reply_wanted_for_this_topic','659797091633ef0b16807a67d6594e12','content/view/full/124',1,0,0),(120,'discussions/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','cd75b5016b43b7dec4c22e911c98b00f','content/view/full/125',1,0,0),(128,'discussions/sports_discussion/football/reply_2','b99ca3fa56d5010fd9e2edb25c6c723c','content/view/full/133',1,0,0),(130,'discussions/music_discussion/lkj_ssssstick','515b0805b631e2e60f5a01a62078aafd','content/view/full/135',1,0,0),(131,'discussions/music_discussion/foo','c30b12e11f43e38e5007e437eb28f7fc','content/view/full/136',1,0,0),(132,'discussions/music_discussion/lkj_ssssstick/reply','b81320d415f41d95b962b73d36e2c248','content/view/full/137',1,0,0),(135,'discussions/music_discussion/lkj_ssssstick/uyuiyui','c560e70f61e30defc917cf5fd1824831','content/view/full/140',1,0,0),(136,'discussions/music_discussion/test2','79a4b87fad6297c89e32fcda0fdeadef','content/view/full/141',1,0,0),(137,'discussions/music_discussion/t4','a411ba84550a8808aa017d46d7f61899','content/view/full/142',1,0,0),(138,'discussions/music_discussion/lkj_ssssstick/klj_jkl_klj','ad8b440b5c57fce9f5ae28d271b2b629','content/view/full/143',1,0,0),(139,'discussions/music_discussion/test2/retest2','8e0e854c6f944f7b1fd9676c37258634','content/view/full/144',1,0,0),(141,'discussions/music_discussion/lkj_ssssstick/my_reply','d0d8e13f8fc3f4d24ff7223c02bcd26d','content/view/full/146',1,0,0),(142,'discussions/music_discussion/lkj_ssssstick/retest','b9924edb42d7cb24b5b7ff0b3ae8d1f4','content/view/full/147',1,0,0),(194,'products/books','9958a314b65536ff546916425fa22a11','content/view/full/183',1,0,0),(144,'discussions/music_discussion/hjg_dghsdjgf','c9b3ef4c7c4cca6eacfc0d2e0a88747d','content/view/full/149',1,0,0),(146,'discussions/music_discussion/hjg_dghsdjgf/dfghd_fghklj','3353f2cdd52889bdf18d0071c9b3c85b','content/view/full/151',1,0,0),(195,'products/flowers','cc38cceec70ad9af57cced5d4c58fa5b','content/view/full/184',1,196,0),(196,'products/cars','fc536e4f1775ed51adf2031587023b4d','content/view/full/184',1,0,0),(197,'products/dvd','a16e9023bb6d9e8aa8c7d11f62e253a6','content/view/full/185',1,0,0),(192,'products/pc/monitor/lcd_15','b16aa924d05760f61f84112e426b38b2','content/view/full/181',1,0,0),(157,'shipping_and_returns','b6e6c30236fd41d3623ad5cb6ac2bf7d','content/view/full/127',1,0,0),(158,'privacy_notice','8c8c68c20b331d0f4781cc125b98e700','content/view/full/160',1,0,0),(159,'conditions_of_use','53214a466568707294398ecd56b4f788','content/view/full/161',1,0,0),(162,'products','86024cad1e83101d97359d7351051156','content/view/full/154',1,0,0),(161,'contact_us__1','54f33014a45dc127271f59d0ff3e01f7','content/view/full/163',1,187,0),(163,'hardware/*','24c7f0cd68f9143e5c13f759ea1b90bd','products/{1}',1,0,1),(199,'products/cars/ferrari','c6c1a725181a4780250d7fa0985544b3','content/view/full/187',1,0,0),(200,'products/books/summer_book','def473e58410352709df09917f5a1e63','content/view/full/188',1,0,0),(201,'products/books/ez_publish_basics','446ea3e82e8051d9f20746649e5362cd','content/view/full/189',1,0,0),(193,'products/mac/g101_power/jkhjkhjk','1465cf924b4a2dd75c5c3776b06c76a7','content/view/full/182',1,0,0),(168,'setup/look_and_feel/my_shop','dcc2fb6a7ef4778e4058de4a202ab95b','content/view/full/54',1,179,0),(169,'products/good','82f4dd56a317e99f2eda1145cb607304','content/view/full/168',1,170,0),(170,'products/mac/g101_power/good','1e4239d6d550a1c88cd4dc0fae5b7166','content/view/full/168',1,0,0),(171,'products/mac/g101_power/the_best_expansion_pack','552f8240650ade100313e3aa98a2b59c','content/view/full/169',1,0,0),(172,'products/mac/g101_power/whimper','be70879cfc363dc87db87dc9566c94a2','content/view/full/170',1,0,0),(173,'products/mac/g101_power/an_utter_disappointment','43fb8f8283cb725684b087662dccc6ce','content/view/full/171',1,0,0),(174,'products/mac/g101_power/asdfasdf','fe58e7ded6f19d069ca9abb1328aae66','content/view/full/172',1,0,0),(179,'setup/look_and_feel/shop','10a4300fdb27b6b751340b62b885d81c','content/view/full/54',1,0,0),(180,'news/news_bulletin_november','ffa2ee44cb666a55101adbeaeeb6ad9f','content/view/full/176',1,0,0),(203,'products/dvd/action_dvd','5d10b5e5c295c9fdeef3b8e19249fe2a','content/view/full/191',1,0,0),(182,'products/nokia_g101/*','fa18fd0bc938fd540fb4fe1d4c61fe3e','products/g101_power/{1}',1,0,1),(198,'products/cars/troll','f3cfa9f08cc14cd729e5ebfc72266f3e','content/view/full/186',1,0,0),(184,'products/cords','fc6cde66dac794b6b8cb58a48d2b77e4','content/view/full/177',1,0,0),(185,'products/cords/1_meter_cord','4c1c1e669e3bbadd5512da3955bcd24c','content/view/full/178',1,0,0),(186,'products/cords/5_meter_cord','3a3c9627da21e9894a91e36776cd268a','content/view/full/179',1,0,0),(187,'contact_us','53a2c328fefc1efd85d75137a9d833ab','content/view/full/163',1,0,0),(188,'news/a_new_cord','d18e23d7b8ca3336b29913b22e8eb076','content/view/full/180',1,0,0),(191,'products/g101_power/*','4f3ac3a16a32af7773e43e879f7562f0','products/mac/g101_power/{1}',1,0,1);
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
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d'),(246,'','',1,''),(187,'','',1,''),(189,'','',1,''),(243,'','',1,''),(244,'','',1,''),(245,'','',1,''),(247,'','',1,''),(248,'','',1,''),(249,'','',1,'');
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
INSERT INTO ezuser_setting VALUES (10,1,1000),(14,1,10),(23,1,0),(40,1,0),(107,1,0),(108,1,0),(109,1,0),(111,1,0),(130,1,0),(149,1,0),(154,0,0),(187,1,0),(188,0,0),(189,1,0),(197,0,0),(198,1,0),(206,1,0),(239,1,0),(243,1,0),(244,1,0),(245,1,0),(246,1,0),(247,1,0),(248,1,0),(249,1,0);
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