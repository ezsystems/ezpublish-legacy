-- MySQL dump 10.2
--
-- Host: localhost    Database: corporate
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
INSERT INTO ezcontentbrowserecent VALUES (40,14,2,1068027383,'Corporate'),(35,111,99,1067006746,'foo bar corp');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1066907423),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1066916721),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(20,1,'','','',0,14,0,1069412379),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069412609),(19,1,'Feedback form','feedback_form','<name>',14,14,1068027045,1068554767),(19,0,'Feedback form','feedback_form','<name>',14,14,1068027045,1068027439),(12,1,'File','file','<name>',14,14,1052385472,1067353799);
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'css','CSS','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'css','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(187,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(188,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(185,1,19,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1),(181,0,19,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(182,0,19,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(183,0,19,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1),(185,0,19,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1),(184,0,19,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1),(182,1,19,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(184,1,19,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1),(181,1,19,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(183,1,19,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
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
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(19,1,1,'Content'),(19,0,1,'Content');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Corporate',2,0,1033917596,1067871717,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(40,14,2,4,'test test',1,0,1053613020,1053613020,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',8,0,1066384365,1067950307,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',9,0,1066388816,1067950326,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,4,1,'News',1,0,1066398020,1066398020,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'Corporate',60,0,1066643397,1069414797,1,''),(58,14,4,1,'Business news',1,0,1066729196,1066729196,1,''),(59,14,4,1,'Off topic',1,0,1066729211,1066729211,1,''),(60,14,4,1,'Reports',2,0,1066729226,1066729241,1,''),(61,14,4,1,'Staff news',1,0,1066729258,1066729258,1,''),(135,14,1,1,'Information',1,0,1067936571,1067936571,1,''),(136,14,1,10,'About',2,0,1067937053,1067942786,1,''),(137,14,1,19,'Contact us',2,0,1068027382,1068027496,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(87,14,5,16,'New Company',1,0,0,0,2,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(92,14,4,2,'eZ systems - reporting live from Munich',5,0,1066828821,1068213283,1,''),(93,14,4,2,'eZ publish 3.2-2 release',2,0,1066828903,1067000145,1,''),(94,14,4,2,'Mr xxx joined us',2,0,1066829047,1066910828,1,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(107,14,2,4,'John Doe',2,0,1066916865,1066916941,1,''),(133,14,1,1,'Products',1,0,1067872500,1067872500,1,''),(111,14,2,4,'vid la',1,0,1066917523,1066917523,1,''),(134,14,1,1,'Services',1,0,1067872529,1067872529,1,''),(115,14,11,14,'Cache',3,0,1066991725,1067950265,1,''),(116,14,11,14,'URL translator',2,0,1066992054,1067950343,1,''),(117,14,4,2,'New Article',1,0,0,0,0,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'Root folder',NULL,NULL,0,0,'','ezstring'),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(95,'eng-GB',1,40,8,'test',0,0,0,0,'','ezstring'),(96,'eng-GB',1,40,9,'test',0,0,0,0,'','ezstring'),(97,'eng-GB',1,40,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',1,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',1,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',1,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',1,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(251,'eng-GB',4,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',4,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',4,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(157,'eng-GB',1,58,4,'Business news',0,0,0,0,'business news','ezstring'),(158,'eng-GB',1,58,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(159,'eng-GB',1,59,4,'Off topic',0,0,0,0,'off topic','ezstring'),(160,'eng-GB',1,59,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(161,'eng-GB',1,60,4,'Reports ',0,0,0,0,'reports','ezstring'),(162,'eng-GB',1,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(161,'eng-GB',2,60,4,'Reports',0,0,0,0,'reports','ezstring'),(162,'eng-GB',2,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(163,'eng-GB',1,61,4,'Staff news',0,0,0,0,'staff news','ezstring'),(164,'eng-GB',1,61,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(151,'eng-GB',59,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',59,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(430,'eng-GB',2,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/storage/images/information/about/430-2-eng-GB\"\n         url=\"var/storage/images/information/about/430-2-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"430\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(254,'eng-GB',5,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_systems_reporting_live_from_munich.\"\n         suffix=\"\"\n         basename=\"ez_systems_reporting_live_from_munich\"\n         dirpath=\"var/corporate/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-5-eng-GB\"\n         url=\"var/corporate/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-5-eng-GB/ez_systems_reporting_live_from_munich.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"254\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(254,'eng-GB',4,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_systems_reporting_live_from_munich.\"\n         suffix=\"\"\n         basename=\"ez_systems_reporting_live_from_munich\"\n         dirpath=\"var/intranet/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-4-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-4-eng-GB/ez_systems_reporting_live_from_munich.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(255,'eng-GB',4,92,123,'',0,0,0,0,'','ezboolean'),(276,'eng-GB',4,92,177,'',0,0,0,0,'','ezinteger'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(150,'eng-GB',60,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(153,'eng-GB',59,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',59,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',59,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',59,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(441,'eng-GB',1,137,181,'Contact us',0,0,0,0,'contact us','ezstring'),(442,'eng-GB',1,137,182,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Fill in the form below if you have any</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(443,'eng-GB',1,137,183,'',0,0,0,0,'','ezstring'),(444,'eng-GB',1,137,184,'',0,0,0,0,'','eztext'),(445,'eng-GB',1,137,185,'',0,0,0,0,'','ezstring'),(441,'eng-GB',2,137,181,'Contact us',0,0,0,0,'contact us','ezstring'),(442,'eng-GB',2,137,182,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Fill in the form below if you have any feedback. Please remember to fill in your e-mail address.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(443,'eng-GB',2,137,183,'',0,0,0,0,'','ezstring'),(445,'eng-GB',2,137,185,'',0,0,0,0,'','ezstring'),(444,'eng-GB',2,137,184,'',0,0,0,0,'','eztext'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',53,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"51\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(251,'eng-GB',1,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',1,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',1,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small. </paragraph>\n  <paragraph>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',1,92,122,'',0,0,0,0,'','ezimage'),(255,'eng-GB',1,92,123,'',0,0,0,0,'','ezboolean'),(256,'eng-GB',1,93,1,'eZ publish 3.2-2 release',0,0,0,0,'ez publish 3.2-2 release','ezstring'),(257,'eng-GB',1,93,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph> eZ publish 3.2-2 is an upgrade of the stable 3.2 release and it is recommended for all users of eZ publish 3.2. This release fixes some of the problems that were present in the last release. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(258,'eng-GB',1,93,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Release notes for 3.2-2</paragraph>\n  <paragraph>\n    <line>- Improved url alias system with support for wildcards, the updatenicurls scripts were also updated.</line>\n    <line>- Some UI improvements with regards to url alias, cache clearing.</line>\n    <line>- Cc and Bcc support in the SMTP transport.</line>\n    <line>- Fixed problem with the shop regarding the basket and orders.</line>\n    <line>- Fixed bug with sort keys in content object attributes as well as new field for the attribute filter.</line>\n    <line>- New translations for Portuguese and Mozambique.</line>\n    <line>- Updated translations for German, Spanish and Catalan translations.</line>\n    <line>- And general bugfixes and enhancements.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(259,'eng-GB',1,93,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(260,'eng-GB',1,93,123,'',0,0,0,0,'','ezboolean'),(261,'eng-GB',1,94,1,'Mr xxx joined us',0,0,0,0,'mr xxx joined us','ezstring'),(262,'eng-GB',1,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We hired a new employee who is from --</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(263,'eng-GB',1,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>His name is xxx.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(264,'eng-GB',1,94,122,'',0,0,0,0,'','ezimage'),(265,'eng-GB',1,94,123,'',0,0,0,0,'','ezboolean'),(251,'eng-GB',2,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',2,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',2,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.     \n    <object id=\"95\"\n            align=\"right\"\n            size=\"medium\" />\n  </paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things. </line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',2,92,122,'',0,0,0,0,'','ezimage'),(255,'eng-GB',2,92,123,'',0,0,0,0,'','ezboolean'),(255,'eng-GB',5,92,123,'',0,0,0,0,'','ezboolean'),(276,'eng-GB',5,92,177,'',0,0,0,0,'','ezinteger'),(153,'eng-GB',58,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',58,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',58,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',58,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(468,'eng-GB',58,56,188,'Copyright &copy; eZ Publish 2000-2003',0,0,0,0,'copyright &copy; ez publish 2000-2003','ezstring'),(275,'eng-GB',1,92,177,'',0,0,0,0,'','ezinteger'),(276,'eng-GB',2,92,177,'',0,0,0,0,'','ezinteger'),(277,'eng-GB',1,93,177,'',0,0,0,0,'','ezinteger'),(278,'eng-GB',1,94,177,'',0,0,0,0,'','ezinteger'),(251,'eng-GB',3,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',3,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',3,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.     \n    <object id=\"95\"\n            size=\"medium\"\n            align=\"right\" />\n  </paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',3,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(255,'eng-GB',3,92,123,'',0,0,0,0,'','ezboolean'),(276,'eng-GB',3,92,177,'',95,0,0,95,'','ezinteger'),(429,'eng-GB',2,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur eget mi quis mi porta suscipit. Maecenas elit. Etiam congue dictum libero. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras vitae libero ac lorem auctor sodales. Phasellus vitae lorem. Nullam sodales tristique lectus. Vivamus ultrices nunc a augue. Sed dignissim, ligula sed dictum consectetuer, quam leo auctor ipsum, a consequat neque diam eget justo. Ut posuere aliquam lectus. Nulla non massa vel massa ultricies vulputate. Vestibulum neque erat, interdum vel, rutrum ut, pharetra at, urna. Fusce eleifend dictum justo. Curabitur nonummy sodales orci. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. </paragraph>\n  <paragraph>Aliquam tincidunt, sem vitae porta adipiscing, odio mauris egestas augue, sit amet pellentesque velit arcu ut sapien. Suspendisse consequat pellentesque nibh. Nunc sed eros. Integer mi arcu, facilisis non, dictum quis, tincidunt id, metus. Aliquam vestibulum. Donec vitae leo sed nunc lobortis aliquam. Quisque sed elit. Fusce felis. Fusce ullamcorper mauris in ipsum. Nulla lacus mauris, porta id, mattis vitae, vehicula at, augue. Curabitur consequat, urna ut placerat elementum, velit augue aliquet orci, sed sollicitudin tortor quam sit amet neque. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(428,'eng-GB',2,136,140,'About',0,0,0,0,'about','ezstring'),(261,'eng-GB',2,94,1,'Mr xxx joined us',0,0,0,0,'mr xxx joined us','ezstring'),(262,'eng-GB',2,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>We hired a new employee who is from --</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(263,'eng-GB',2,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>His name is xxx.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(264,'eng-GB',2,94,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(265,'eng-GB',2,94,123,'',0,0,0,0,'','ezboolean'),(278,'eng-GB',2,94,177,'',101,0,0,101,'','ezinteger'),(302,'eng-GB',1,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',1,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',1,107,12,'',0,0,0,0,'','ezuser'),(302,'eng-GB',2,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',2,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',2,107,12,'',0,0,0,0,'','ezuser'),(315,'eng-GB',1,111,12,'',0,0,0,0,'','ezuser'),(313,'eng-GB',1,111,8,'vid',0,0,0,0,'vid','ezstring'),(314,'eng-GB',1,111,9,'la',0,0,0,0,'la','ezstring'),(1,'eng-GB',2,1,4,'Corporate',0,0,0,0,'corporate','ezstring'),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(422,'eng-GB',1,133,4,'Products',0,0,0,0,'products','ezstring'),(423,'eng-GB',1,133,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find information about our products.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(424,'eng-GB',1,134,4,'Services',0,0,0,0,'services','ezstring'),(425,'eng-GB',1,134,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about our services.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(426,'eng-GB',1,135,4,'Information',0,0,0,0,'information','ezstring'),(427,'eng-GB',1,135,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Company information.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(428,'eng-GB',1,136,140,'About',0,0,0,0,'about','ezstring'),(429,'eng-GB',1,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(430,'eng-GB',1,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/storage/images/information/about/430-1-eng-GB\"\n         url=\"var/storage/images/information/about/430-1-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(153,'eng-GB',53,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',53,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',53,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(150,'eng-GB',51,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',60,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',60,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(152,'eng-GB',51,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"47\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',51,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',59,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"58\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069414397\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(256,'eng-GB',2,93,1,'eZ publish 3.2-2 release',0,0,0,0,'ez publish 3.2-2 release','ezstring'),(257,'eng-GB',2,93,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>eZ publish 3.2-2 is an upgrade of the stable 3.2 release and it is recommended for all users of eZ publish 3.2. This release fixes some of the problems that were present in the last release.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(258,'eng-GB',2,93,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Release notes for 3.2-2</paragraph>\n  <paragraph>\n    <line>- Improved url alias system with support for wildcards, the updatenicurls scripts were also updated.</line>\n    <line>- Some UI improvements with regards to url alias, cache clearing.</line>\n    <line>- Cc and Bcc support in the SMTP transport.</line>\n    <line>- Fixed problem with the shop regarding the basket and orders.</line>\n    <line>- Fixed bug with sort keys in content object attributes as well as new field for the attribute filter.</line>\n    <line>- New translations for Portuguese and Mozambique.</line>\n    <line>- Updated translations for German, Spanish and Catalan translations.</line>\n    <line>- And general bugfixes and enhancements.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(259,'eng-GB',2,93,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_publish_322_release.\"\n         suffix=\"\"\n         basename=\"ez_publish_322_release\"\n         dirpath=\"var/intranet/storage/images/news/business_news/ez_publish_322_release/259-2-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/ez_publish_322_release/259-2-eng-GB/ez_publish_322_release.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(260,'eng-GB',2,93,123,'',0,0,0,0,'','ezboolean'),(277,'eng-GB',2,93,177,'',0,0,0,0,'','ezinteger'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(461,'eng-GB',60,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(468,'eng-GB',60,56,188,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(437,'eng-GB',60,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',54,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',54,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',54,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(464,'eng-GB',51,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(465,'eng-GB',53,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',55,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"54\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',54,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',54,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',54,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"53\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',55,56,160,'corporate_blue',0,0,0,0,'corporate_blue','ezpackage'),(154,'eng-GB',55,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',55,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(466,'eng-GB',54,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(467,'eng-GB',55,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(468,'eng-GB',56,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(253,'eng-GB',5,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(468,'eng-GB',59,56,188,'Copyright &copy; eZ Publish 1999-2003',0,0,0,0,'copyright &copy; ez publish 1999-2003','ezstring'),(251,'eng-GB',5,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',5,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(150,'eng-GB',53,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',53,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(151,'eng-GB',60,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',60,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"59\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069676156\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',47,56,160,'corporate_blue',0,0,0,0,'corporate_blue','ezpackage'),(154,'eng-GB',47,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',47,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(150,'eng-GB',58,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',58,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',58,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"56\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069412772\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(463,'eng-GB',47,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(150,'eng-GB',56,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(437,'eng-GB',56,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(154,'eng-GB',56,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(153,'eng-GB',56,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(151,'eng-GB',55,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',55,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(153,'eng-GB',51,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',51,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',51,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(462,'eng-GB',57,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',56,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(460,'eng-GB',55,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(150,'eng-GB',57,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',57,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',57,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"56\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',47,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',47,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',47,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-47-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-47-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-47-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-47-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-47-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-47-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-47-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-47-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(469,'eng-GB',57,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(153,'eng-GB',57,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',57,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',57,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(456,'eng-GB',47,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(457,'eng-GB',51,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(458,'eng-GB',53,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(459,'eng-GB',54,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring');
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(40,'test test',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(93,'eZ publish 3.2-2 release',2,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(58,'Business news',1,'eng-GB','eng-GB'),(59,'Off topic',1,'eng-GB','eng-GB'),(60,'Reports',1,'eng-GB','eng-GB'),(60,'Reports',2,'eng-GB','eng-GB'),(61,'Staff news',1,'eng-GB','eng-GB'),(137,'Contact us',2,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(135,'Information',1,'eng-GB','eng-GB'),(136,'About',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(136,'About',2,'eng-GB','eng-GB'),(134,'Services',1,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(87,'New Company',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',1,'eng-GB','eng-GB'),(93,'eZ publish 3.2-2 release',1,'eng-GB','eng-GB'),(94,'Mr xxx joined us',1,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',2,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(133,'Products',1,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',3,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',4,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(94,'Mr xxx joined us',2,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(137,'Contact us',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(107,'John Doe',1,'eng-GB','eng-GB'),(107,'John Doe',2,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(111,'vid la',1,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(56,'Corporate',43,'eng-GB','eng-GB'),(56,'Corporate',44,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',5,'eng-GB','eng-GB'),(56,'Corporate',45,'eng-GB','eng-GB'),(56,'Corporate',46,'eng-GB','eng-GB'),(56,'Corporate',48,'eng-GB','eng-GB'),(56,'Corporate',47,'eng-GB','eng-GB'),(56,'Corporate',51,'eng-GB','eng-GB'),(56,'Corporate',53,'eng-GB','eng-GB'),(56,'Corporate',54,'eng-GB','eng-GB'),(56,'Corporate',55,'eng-GB','eng-GB'),(56,'Corporate',56,'eng-GB','eng-GB'),(56,'Corporate',58,'eng-GB','eng-GB'),(56,'Corporate',59,'eng-GB','eng-GB'),(56,'Corporate',60,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,2,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15),(42,12,40,1,1,3,'/1/5/12/42/',9,1,0,'users/guest_accounts/test_test',42),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,8,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,9,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,1,1,2,'/1/2/50/',9,1,0,'news',50),(56,50,58,1,1,3,'/1/2/50/56/',9,1,0,'news/business_news',56),(54,48,56,60,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/corporate',54),(57,50,59,1,1,3,'/1/2/50/57/',9,1,0,'news/off_topic',57),(58,50,60,2,1,3,'/1/2/50/58/',9,1,0,'news/reports',58),(59,50,61,1,1,3,'/1/2/50/59/',9,1,0,'news/staff_news',59),(108,2,135,1,1,2,'/1/2/108/',9,1,0,'information',108),(109,108,136,2,1,3,'/1/2/108/109/',9,1,0,'information/about',109),(110,2,137,2,1,2,'/1/2/110/',9,1,0,'contact_us',110),(81,56,92,5,1,4,'/1/2/50/56/81/',9,1,0,'news/business_news/ez_systems_reporting_live_from_munich',81),(82,56,93,2,1,4,'/1/2/50/56/82/',9,1,0,'news/business_news/ez_publish_322_release',82),(83,59,94,2,1,4,'/1/2/50/59/83/',9,1,0,'news/staff_news/mr_xxx_joined_us',83),(91,14,107,2,1,3,'/1/5/14/91/',9,1,0,'users/editors/john_doe',91),(92,14,111,1,1,3,'/1/5/14/92/',9,1,0,'users/editors/vid_la',92),(106,2,133,1,1,2,'/1/2/106/',9,1,0,'products',106),(107,2,134,1,1,2,'/1/2/107/',9,1,0,'services',107),(95,46,115,3,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,2,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96);
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
INSERT INTO ezcontentobject_version VALUES (1,1,14,1,1033919080,1033919080,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(471,40,14,1,1053613007,1053613020,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(480,45,14,1,1066388718,1066388815,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(699,56,14,47,1068216929,1068217049,3,0,0),(490,49,14,1,1066398007,1066398020,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(622,92,14,4,1066998064,1066998231,3,0,0),(708,56,14,56,1068222495,1068222571,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(520,58,14,1,1066729186,1066729195,1,0,0),(521,59,14,1,1066729202,1066729210,1,0,0),(522,60,14,1,1066729218,1066729226,3,0,0),(523,60,14,2,1066729234,1066729241,1,0,0),(524,61,14,1,1066729249,1066729258,1,0,0),(676,136,14,1,1067937002,1067937053,3,0,0),(683,45,14,9,1067950316,1067950326,1,0,0),(682,43,14,8,1067950294,1067950307,1,0,0),(681,115,14,3,1067950253,1067950265,1,0,0),(711,56,14,58,1069412658,1069412691,3,0,0),(675,135,14,1,1067936556,1067936571,1,0,0),(707,56,14,55,1068221830,1068221860,3,0,0),(710,56,14,57,1069411962,1069411962,0,0,0),(663,92,14,5,1067343889,1068213282,1,0,0),(706,56,14,54,1068221131,1068221152,3,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(712,56,14,59,1069414187,1069414256,3,0,0),(705,56,14,53,1068221035,1068221067,3,0,0),(691,137,14,2,1068027475,1068027496,1,0,0),(684,116,14,2,1067950335,1067950343,1,0,0),(690,137,14,1,1068027166,1068027382,3,0,0),(573,92,14,1,1066828519,1066828821,3,0,0),(574,93,14,1,1066828848,1066828903,3,0,0),(575,94,14,1,1066828947,1066829047,3,0,0),(576,92,14,2,1066832966,1066833112,3,0,0),(630,93,14,2,1067000124,1067000145,1,0,0),(713,56,14,60,1069414771,1069414796,1,0,0),(674,134,14,1,1067872510,1067872528,1,0,0),(583,92,14,3,1066907449,1066907519,3,0,0),(703,56,14,51,1068220879,1068220902,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(677,136,14,2,1067942731,1067942786,1,0,0),(590,94,14,2,1066910791,1066910828,1,0,0),(598,107,14,1,1066916843,1066916865,3,0,0),(599,107,14,2,1066916931,1066916941,1,0,0),(672,1,14,2,1067871685,1067871717,1,1,0),(604,111,14,1,1066917488,1066917523,1,0,0),(673,133,14,1,1067872484,1067872500,1,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0);
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
INSERT INTO ezgeneral_digest_user_settings VALUES (1,'nospam@ez.no',0,0,'','');
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
INSERT INTO ezimagefile VALUES (1,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate.gif'),(2,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_reference.gif'),(3,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_medium.gif'),(4,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_logo.gif'),(5,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate.gif'),(6,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_reference.gif'),(7,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_medium.gif'),(8,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_logo.gif'),(9,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate.gif'),(10,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_reference.gif'),(11,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_medium.gif'),(12,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_logo.gif');
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
INSERT INTO ezinfocollection VALUES (1,137,1068027503,'c6194244e6057c2ed46e92ac8c59be21',1068027503),(2,137,1068028058,'c6194244e6057c2ed46e92ac8c59be21',1068028058);
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
INSERT INTO ezinfocollection_attribute VALUES (1,1,'',0,0,183,443,137),(2,1,'',0,0,185,445,137),(3,1,'',0,0,184,444,137),(4,2,'FOo bar ',0,0,183,443,137),(5,2,'bf@ez.no',0,0,185,445,137),(6,2,'This is my feedback.',0,0,184,444,137);
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
INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(181,40,1,12,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(191,45,1,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(402,56,47,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(411,56,56,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(325,92,4,56,9,1,1,0,0),(230,58,1,50,9,1,1,0,0),(410,56,55,48,9,1,1,0,0),(231,59,1,50,9,1,1,0,0),(232,60,1,50,9,1,1,0,0),(233,60,2,50,9,1,1,0,0),(234,61,1,50,9,1,1,0,0),(378,135,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(414,56,58,48,9,1,1,0,0),(377,134,1,2,9,1,1,0,0),(413,56,57,48,9,1,1,0,0),(366,92,5,56,9,1,1,0,0),(409,56,54,48,9,1,1,0,0),(321,45,6,46,9,1,1,0,0),(415,56,59,48,9,1,1,0,0),(408,56,53,48,9,1,1,0,0),(394,137,2,2,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(393,137,1,2,9,1,1,0,0),(279,92,1,56,9,1,1,0,0),(280,93,1,56,9,1,1,0,0),(281,94,1,59,9,1,1,0,0),(282,92,2,56,9,1,1,0,0),(333,93,2,56,9,1,1,0,0),(416,56,60,48,9,1,1,0,0),(379,136,1,108,9,1,1,0,0),(288,92,3,56,9,1,1,0,0),(335,45,8,46,9,1,1,0,0),(406,56,51,48,9,1,1,0,0),(380,136,2,108,9,1,1,0,0),(296,94,2,59,9,1,1,0,0),(300,107,1,14,9,1,1,0,0),(301,107,2,14,9,1,1,0,0),(375,1,2,1,9,1,1,0,0),(306,111,1,14,9,1,1,0,0),(376,133,1,2,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (173,0,'ezpublish',56,60,0,0,'','','',''),(172,0,'ezpublish',56,59,0,0,'','','',''),(171,0,'ezpublish',56,58,0,0,'','','',''),(170,0,'ezpublish',56,56,0,0,'','','',''),(169,0,'ezpublish',56,55,0,0,'','','',''),(168,0,'ezpublish',56,54,0,0,'','','',''),(167,0,'ezpublish',56,53,0,0,'','','',''),(166,0,'ezpublish',56,51,0,0,'','','',''),(165,0,'ezpublish',56,47,0,0,'','','',''),(164,0,'ezpublish',56,48,0,0,'','','',''),(163,0,'ezpublish',56,46,0,0,'','','',''),(162,0,'ezpublish',56,45,0,0,'','','',''),(161,0,'ezpublish',92,5,0,0,'','','',''),(160,0,'ezpublish',56,44,0,0,'','','',''),(159,0,'ezpublish',56,43,0,0,'','','','');
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(370,24,'create','content',''),(371,24,'create','content',''),(372,24,'create','content',''),(341,8,'read','content','*'),(369,24,'read','content','*'),(373,24,'create','content',''),(374,24,'edit','content',''),(375,1,'login','user','*'),(376,1,'read','content','');
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
INSERT INTO ezpolicy_limitation VALUES (289,371,'Class',0,'create','content'),(290,371,'Section',0,'create','content'),(288,370,'Section',0,'create','content'),(287,370,'Class',0,'create','content'),(291,372,'Class',0,'create','content'),(292,372,'Section',0,'create','content'),(293,373,'Class',0,'create','content'),(294,373,'Section',0,'create','content'),(295,374,'Class',0,'edit','content'),(296,376,'Class',0,'read','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (555,291,'12'),(554,291,'1'),(551,289,'16'),(550,288,'4'),(548,287,'13'),(549,287,'2'),(553,290,'5'),(552,289,'17'),(547,287,'1'),(556,292,'6'),(557,293,'6'),(558,293,'7'),(559,294,'7'),(560,295,'1'),(561,295,'2'),(562,295,'6'),(563,295,'7'),(564,295,'12'),(565,295,'13'),(566,295,'16'),(567,295,'17'),(568,295,'18'),(569,296,'1'),(570,296,'2'),(571,296,'5'),(572,296,'12');
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
INSERT INTO ezrole VALUES (1,0,'Anonymous',''),(2,0,'Administrator','*'),(8,0,'Guest',NULL),(24,0,'Intranet',NULL);
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
INSERT INTO ezsearch_object_word_link VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0),(27,40,5,0,1,5,0,4,1053613020,2,9,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(2068,43,1050,0,2,1049,0,14,1066384365,11,155,'',0),(2067,43,1049,0,1,1048,1050,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(2066,43,1048,0,0,0,1049,14,1066384365,11,152,'',0),(2074,45,1052,0,5,1051,0,14,1066388816,11,155,'',0),(2073,45,1051,0,4,25,1052,14,1066388816,11,155,'',0),(2072,45,25,0,3,34,1051,14,1066388816,11,155,'',0),(2071,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(2353,56,1095,0,7,1204,0,15,1066643397,11,188,'',0),(61,49,37,0,0,0,0,1,1066398020,4,4,'',0),(74,58,49,0,0,0,37,1,1066729196,4,4,'',0),(75,58,37,0,1,49,0,1,1066729196,4,4,'',0),(76,59,50,0,0,0,51,1,1066729211,4,4,'',0),(77,59,51,0,1,50,0,1,1066729211,4,4,'',0),(79,60,53,0,0,0,0,1,1066729226,4,4,'',0),(80,61,54,0,0,0,37,1,1066729258,4,4,'',0),(81,61,37,0,1,54,0,1,1066729258,4,4,'',0),(2352,56,1204,0,6,879,1095,15,1066643397,11,188,'',0),(2351,56,879,0,5,1083,1204,15,1066643397,11,188,'',0),(2350,56,1083,0,4,144,879,15,1066643397,11,188,'',0),(2349,56,144,0,3,1203,1083,15,1066643397,11,188,'',0),(2346,56,1201,0,0,0,1202,15,1066643397,11,161,'',0),(2347,56,1202,0,1,1201,1203,15,1066643397,11,188,'',0),(2348,56,1203,0,2,1202,144,15,1066643397,11,188,'',0),(2322,92,1183,0,201,1182,0,2,1066828821,4,121,'',0),(2321,92,1182,0,200,1181,1183,2,1066828821,4,121,'',0),(2320,92,1181,0,199,102,1182,2,1066828821,4,121,'',0),(2319,92,102,0,198,1180,1181,2,1066828821,4,121,'',0),(2318,92,1180,0,197,1179,102,2,1066828821,4,121,'',0),(2317,92,1179,0,196,1090,1180,2,1066828821,4,121,'',0),(2316,92,1090,0,195,227,1179,2,1066828821,4,121,'',0),(2315,92,227,0,194,1178,1090,2,1066828821,4,121,'',0),(2314,92,1178,0,193,1177,227,2,1066828821,4,121,'',0),(2313,92,1177,0,192,254,1178,2,1066828821,4,121,'',0),(2312,92,254,0,191,1176,1177,2,1066828821,4,121,'',0),(2311,92,1176,0,190,1134,254,2,1066828821,4,121,'',0),(2310,92,1134,0,189,33,1176,2,1066828821,4,121,'',0),(2309,92,33,0,188,1175,1134,2,1066828821,4,121,'',0),(2308,92,1175,0,187,89,33,2,1066828821,4,121,'',0),(2307,92,89,0,186,1174,1175,2,1066828821,4,121,'',0),(2306,92,1174,0,185,152,89,2,1066828821,4,121,'',0),(2305,92,152,0,184,1173,1174,2,1066828821,4,121,'',0),(2304,92,1173,0,183,75,152,2,1066828821,4,121,'',0),(2303,92,75,0,182,1172,1173,2,1066828821,4,121,'',0),(2302,92,1172,0,181,102,75,2,1066828821,4,121,'',0),(2301,92,102,0,180,1171,1172,2,1066828821,4,121,'',0),(2300,92,1171,0,179,1170,102,2,1066828821,4,121,'',0),(2299,92,1170,0,178,1169,1171,2,1066828821,4,121,'',0),(2298,92,1169,0,177,1132,1170,2,1066828821,4,121,'',0),(2297,92,1132,0,176,1135,1169,2,1066828821,4,121,'',0),(2296,92,1135,0,175,1134,1132,2,1066828821,4,121,'',0),(2295,92,1134,0,174,156,1135,2,1066828821,4,121,'',0),(2294,92,156,0,173,1168,1134,2,1066828821,4,121,'',0),(2293,92,1168,0,172,1167,156,2,1066828821,4,121,'',0),(2292,92,1167,0,171,1166,1168,2,1066828821,4,121,'',0),(2291,92,1166,0,170,1165,1167,2,1066828821,4,121,'',0),(2290,92,1165,0,169,1160,1166,2,1066828821,4,121,'',0),(2289,92,1160,0,168,1164,1165,2,1066828821,4,121,'',0),(2288,92,1164,0,167,1163,1160,2,1066828821,4,121,'',0),(2287,92,1163,0,166,1162,1164,2,1066828821,4,121,'',0),(2286,92,1162,0,165,1161,1163,2,1066828821,4,121,'',0),(2285,92,1161,0,164,1160,1162,2,1066828821,4,121,'',0),(2284,92,1160,0,163,1159,1161,2,1066828821,4,121,'',0),(2283,92,1159,0,162,1158,1160,2,1066828821,4,121,'',0),(2282,92,1158,0,161,1157,1159,2,1066828821,4,121,'',0),(2281,92,1157,0,160,33,1158,2,1066828821,4,121,'',0),(2280,92,33,0,159,1156,1157,2,1066828821,4,121,'',0),(2279,92,1156,0,158,1135,33,2,1066828821,4,121,'',0),(2278,92,1135,0,157,1134,1156,2,1066828821,4,121,'',0),(2277,92,1134,0,156,254,1135,2,1066828821,4,121,'',0),(2276,92,254,0,155,1155,1134,2,1066828821,4,121,'',0),(2275,92,1155,0,154,1154,254,2,1066828821,4,121,'',0),(2274,92,1154,0,153,1090,1155,2,1066828821,4,121,'',0),(2273,92,1090,0,152,1124,1154,2,1066828821,4,121,'',0),(2272,92,1124,0,151,180,1090,2,1066828821,4,121,'',0),(2271,92,180,0,150,1153,1124,2,1066828821,4,121,'',0),(2270,92,1153,0,149,1152,180,2,1066828821,4,121,'',0),(2269,92,1152,0,148,152,1153,2,1066828821,4,121,'',0),(2268,92,152,0,147,1151,1152,2,1066828821,4,121,'',0),(2267,92,1151,0,146,1150,152,2,1066828821,4,121,'',0),(2266,92,1150,0,145,75,1151,2,1066828821,4,121,'',0),(2265,92,75,0,144,1149,1150,2,1066828821,4,121,'',0),(2264,92,1149,0,143,1148,75,2,1066828821,4,121,'',0),(2263,92,1148,0,142,33,1149,2,1066828821,4,121,'',0),(2262,92,33,0,141,1147,1148,2,1066828821,4,121,'',0),(2261,92,1147,0,140,1146,33,2,1066828821,4,121,'',0),(2260,92,1146,0,139,33,1147,2,1066828821,4,121,'',0),(2259,92,33,0,138,1145,1146,2,1066828821,4,121,'',0),(2258,92,1145,0,137,1144,33,2,1066828821,4,121,'',0),(2257,92,1144,0,136,1143,1145,2,1066828821,4,121,'',0),(2256,92,1143,0,135,1090,1144,2,1066828821,4,121,'',0),(2255,92,1090,0,134,1142,1143,2,1066828821,4,121,'',0),(2254,92,1142,0,133,152,1090,2,1066828821,4,121,'',0),(2253,92,152,0,132,1129,1142,2,1066828821,4,121,'',0),(2252,92,1129,0,131,1141,152,2,1066828821,4,121,'',0),(2251,92,1141,0,130,33,1129,2,1066828821,4,121,'',0),(2250,92,33,0,129,1091,1141,2,1066828821,4,121,'',0),(2249,92,1091,0,128,108,33,2,1066828821,4,121,'',0),(2248,92,108,0,127,1140,1091,2,1066828821,4,121,'',0),(2247,92,1140,0,126,1090,108,2,1066828821,4,121,'',0),(2246,92,1090,0,125,1139,1140,2,1066828821,4,121,'',0),(2245,92,1139,0,124,75,1090,2,1066828821,4,121,'',0),(2244,92,75,0,123,1138,1139,2,1066828821,4,121,'',0),(2243,92,1138,0,122,1137,75,2,1066828821,4,121,'',0),(2242,92,1137,0,121,33,1138,2,1066828821,4,121,'',0),(2241,92,33,0,120,235,1137,2,1066828821,4,121,'',0),(2240,92,235,0,119,1136,33,2,1066828821,4,121,'',0),(2239,92,1136,0,118,25,235,2,1066828821,4,121,'',0),(2238,92,25,0,117,1135,1136,2,1066828821,4,121,'',0),(2237,92,1135,0,116,1134,25,2,1066828821,4,121,'',0),(2236,92,1134,0,115,156,1135,2,1066828821,4,121,'',0),(2235,92,156,0,114,74,1134,2,1066828821,4,121,'',0),(2234,92,74,0,113,1133,156,2,1066828821,4,121,'',0),(2233,92,1133,0,112,145,74,2,1066828821,4,121,'',0),(2232,92,145,0,111,144,1133,2,1066828821,4,121,'',0),(2231,92,144,0,110,1132,145,2,1066828821,4,121,'',0),(2230,92,1132,0,109,1131,144,2,1066828821,4,121,'',0),(2229,92,1131,0,108,752,1132,2,1066828821,4,121,'',0),(2228,92,752,0,107,152,1131,2,1066828821,4,121,'',0),(2227,92,152,0,106,1130,752,2,1066828821,4,121,'',0),(2226,92,1130,0,105,180,152,2,1066828821,4,121,'',0),(2225,92,180,0,104,759,1130,2,1066828821,4,121,'',0),(2224,92,759,0,103,1126,180,2,1066828821,4,121,'',0),(2223,92,1126,0,102,227,759,2,1066828821,4,121,'',0),(2222,92,227,0,101,1124,1126,2,1066828821,4,121,'',0),(2221,92,1124,0,100,1129,227,2,1066828821,4,121,'',0),(2220,92,1129,0,99,1090,1124,2,1066828821,4,121,'',0),(2219,92,1090,0,98,1128,1129,2,1066828821,4,121,'',0),(2218,92,1128,0,97,224,1090,2,1066828821,4,121,'',0),(2217,92,224,0,96,1127,1128,2,1066828821,4,121,'',0),(2216,92,1127,0,95,67,224,2,1066828821,4,121,'',0),(2215,92,67,0,94,1126,1127,2,1066828821,4,121,'',0),(2214,92,1126,0,93,221,67,2,1066828821,4,121,'',0),(2213,92,221,0,92,1125,1126,2,1066828821,4,121,'',0),(2212,92,1125,0,91,759,221,2,1066828821,4,121,'',0),(2211,92,759,0,90,1124,1125,2,1066828821,4,121,'',0),(2210,92,1124,0,89,152,759,2,1066828821,4,121,'',0),(2209,92,152,0,88,1123,1124,2,1066828821,4,121,'',0),(2208,92,1123,0,87,89,152,2,1066828821,4,121,'',0),(2207,92,89,0,86,33,1123,2,1066828821,4,121,'',0),(2206,92,33,0,85,1122,89,2,1066828821,4,121,'',0),(2205,92,1122,0,84,89,33,2,1066828821,4,121,'',0),(2204,92,89,0,83,381,1122,2,1066828821,4,121,'',0),(2203,92,381,0,82,1121,89,2,1066828821,4,121,'',0),(2202,92,1121,0,81,1120,381,2,1066828821,4,121,'',0),(2201,92,1120,0,80,759,1121,2,1066828821,4,121,'',0),(2200,92,759,0,79,86,1120,2,1066828821,4,121,'',0),(2199,92,86,0,78,1119,759,2,1066828821,4,121,'',0),(2198,92,1119,0,77,81,86,2,1066828821,4,121,'',0),(2197,92,81,0,76,1118,1119,2,1066828821,4,121,'',0),(2196,92,1118,0,75,1117,81,2,1066828821,4,121,'',0),(2195,92,1117,0,74,1116,1118,2,1066828821,4,121,'',0),(2194,92,1116,0,73,1090,1117,2,1066828821,4,121,'',0),(2193,92,1090,0,72,1115,1116,2,1066828821,4,121,'',0),(2192,92,1115,0,71,1114,1090,2,1066828821,4,121,'',0),(2191,92,1114,0,70,752,1115,2,1066828821,4,121,'',0),(2190,92,752,0,69,1098,1114,2,1066828821,4,121,'',0),(2189,92,1098,0,68,75,752,2,1066828821,4,120,'',0),(2188,92,75,0,67,108,1098,2,1066828821,4,120,'',0),(2187,92,108,0,66,1113,75,2,1066828821,4,120,'',0),(2186,92,1113,0,65,1085,108,2,1066828821,4,120,'',0),(2185,92,1085,0,64,89,1113,2,1066828821,4,120,'',0),(2184,92,89,0,63,750,1085,2,1066828821,4,120,'',0),(2183,92,750,0,62,1112,89,2,1066828821,4,120,'',0),(2182,92,1112,0,61,1111,750,2,1066828821,4,120,'',0),(2181,92,1111,0,60,75,1112,2,1066828821,4,120,'',0),(2180,92,75,0,59,1095,1111,2,1066828821,4,120,'',0),(2179,92,1095,0,58,1110,75,2,1066828821,4,120,'',0),(2178,92,1110,0,57,152,1095,2,1066828821,4,120,'',0),(2177,92,152,0,56,1109,1110,2,1066828821,4,120,'',0),(2176,92,1109,0,55,75,152,2,1066828821,4,120,'',0),(2175,92,75,0,54,102,1109,2,1066828821,4,120,'',0),(2174,92,102,0,53,1108,75,2,1066828821,4,120,'',0),(2173,92,1108,0,52,75,102,2,1066828821,4,120,'',0),(2172,92,75,0,51,108,1108,2,1066828821,4,120,'',0),(2171,92,108,0,50,1107,75,2,1066828821,4,120,'',0),(2170,92,1107,0,49,1106,108,2,1066828821,4,120,'',0),(2169,92,1106,0,48,1090,1107,2,1066828821,4,120,'',0),(2168,92,1090,0,47,1105,1106,2,1066828821,4,120,'',0),(2167,92,1105,0,46,1083,1090,2,1066828821,4,120,'',0),(2166,92,1083,0,45,144,1105,2,1066828821,4,120,'',0),(2165,92,144,0,44,1104,1083,2,1066828821,4,120,'',0),(2164,92,1104,0,43,1103,144,2,1066828821,4,120,'',0),(2163,92,1103,0,42,1102,1104,2,1066828821,4,120,'',0),(2162,92,1102,0,41,75,1103,2,1066828821,4,120,'',0),(2161,92,75,0,40,77,1102,2,1066828821,4,120,'',0),(2160,92,77,0,39,1101,75,2,1066828821,4,120,'',0),(2159,92,1101,0,38,74,77,2,1066828821,4,120,'',0),(2158,92,74,0,37,1098,1101,2,1066828821,4,120,'',0),(2157,92,1098,0,36,1097,74,2,1066828821,4,120,'',0),(2156,92,1097,0,35,75,1098,2,1066828821,4,120,'',0),(2155,92,75,0,34,6,1097,2,1066828821,4,120,'',0),(2154,92,6,0,33,195,75,2,1066828821,4,120,'',0),(2153,92,195,0,32,33,6,2,1066828821,4,120,'',0),(2152,92,33,0,31,1100,195,2,1066828821,4,120,'',0),(2151,92,1100,0,30,1099,33,2,1066828821,4,120,'',0),(2150,92,1099,0,29,465,1100,2,1066828821,4,120,'',0),(2149,92,465,0,28,77,1099,2,1066828821,4,120,'',0),(2148,92,77,0,27,1098,465,2,1066828821,4,120,'',0),(2147,92,1098,0,26,1097,77,2,1066828821,4,120,'',0),(2146,92,1097,0,25,1096,1098,2,1066828821,4,120,'',0),(2145,92,1096,0,24,156,1097,2,1066828821,4,120,'',0),(2144,92,156,0,23,74,1096,2,1066828821,4,120,'',0),(2143,92,74,0,22,1095,156,2,1066828821,4,120,'',0),(2142,92,1095,0,21,1083,74,2,1066828821,4,120,'',0),(2141,92,1083,0,20,1094,1095,2,1066828821,4,120,'',0),(2140,92,1094,0,19,1093,1083,2,1066828821,4,120,'',0),(2139,92,1093,0,18,1092,1094,2,1066828821,4,120,'',0),(1762,93,891,0,125,33,0,2,1066828903,4,121,'',0),(1761,93,33,0,124,890,891,2,1066828903,4,121,'',0),(1760,93,890,0,123,331,33,2,1066828903,4,121,'',0),(1759,93,331,0,122,33,890,2,1066828903,4,121,'',0),(1758,93,33,0,121,884,331,2,1066828903,4,121,'',0),(1757,93,884,0,120,889,33,2,1066828903,4,121,'',0),(1756,93,889,0,119,33,884,2,1066828903,4,121,'',0),(1755,93,33,0,118,888,889,2,1066828903,4,121,'',0),(1754,93,888,0,117,887,33,2,1066828903,4,121,'',0),(1753,93,887,0,116,77,888,2,1066828903,4,121,'',0),(1752,93,77,0,115,884,887,2,1066828903,4,121,'',0),(1751,93,884,0,114,859,77,2,1066828903,4,121,'',0),(1750,93,859,0,113,886,884,2,1066828903,4,121,'',0),(1749,93,886,0,112,33,859,2,1066828903,4,121,'',0),(1748,93,33,0,111,885,886,2,1066828903,4,121,'',0),(1747,93,885,0,110,77,33,2,1066828903,4,121,'',0),(1746,93,77,0,109,884,885,2,1066828903,4,121,'',0),(1745,93,884,0,108,195,77,2,1066828903,4,121,'',0),(1744,93,195,0,107,883,884,2,1066828903,4,121,'',0),(1743,93,883,0,106,882,195,2,1066828903,4,121,'',0),(1742,93,882,0,105,75,883,2,1066828903,4,121,'',0),(1741,93,75,0,104,77,882,2,1066828903,4,121,'',0),(1740,93,77,0,103,881,75,2,1066828903,4,121,'',0),(1739,93,881,0,102,195,77,2,1066828903,4,121,'',0),(1738,93,195,0,101,879,881,2,1066828903,4,121,'',0),(1737,93,879,0,100,880,195,2,1066828903,4,121,'',0),(1736,93,880,0,99,879,879,2,1066828903,4,121,'',0),(1735,93,879,0,98,878,880,2,1066828903,4,121,'',0),(1734,93,878,0,97,877,879,2,1066828903,4,121,'',0),(1733,93,877,0,96,25,878,2,1066828903,4,121,'',0),(1732,93,25,0,95,183,877,2,1066828903,4,121,'',0),(1731,93,183,0,94,876,25,2,1066828903,4,121,'',0),(1730,93,876,0,93,875,183,2,1066828903,4,121,'',0),(1729,93,875,0,92,254,876,2,1066828903,4,121,'',0),(1728,93,254,0,91,874,875,2,1066828903,4,121,'',0),(1727,93,874,0,90,868,254,2,1066828903,4,121,'',0),(1726,93,868,0,89,873,874,2,1066828903,4,121,'',0),(1725,93,873,0,88,33,868,2,1066828903,4,121,'',0),(1724,93,33,0,87,872,873,2,1066828903,4,121,'',0),(1723,93,872,0,86,75,33,2,1066828903,4,121,'',0),(1722,93,75,0,85,871,872,2,1066828903,4,121,'',0),(1721,93,871,0,84,870,75,2,1066828903,4,121,'',0),(1720,93,870,0,83,75,871,2,1066828903,4,121,'',0),(1719,93,75,0,82,254,870,2,1066828903,4,121,'',0),(1718,93,254,0,81,869,75,2,1066828903,4,121,'',0),(1717,93,869,0,80,868,254,2,1066828903,4,121,'',0),(1716,93,868,0,79,867,869,2,1066828903,4,121,'',0),(1715,93,867,0,78,866,868,2,1066828903,4,121,'',0),(1714,93,866,0,77,75,867,2,1066828903,4,121,'',0),(1713,93,75,0,76,183,866,2,1066828903,4,121,'',0),(1712,93,183,0,75,294,75,2,1066828903,4,121,'',0),(1711,93,294,0,74,865,183,2,1066828903,4,121,'',0),(1710,93,865,0,73,33,294,2,1066828903,4,121,'',0),(1709,93,33,0,72,864,865,2,1066828903,4,121,'',0),(1708,93,864,0,71,863,33,2,1066828903,4,121,'',0),(1707,93,863,0,70,303,864,2,1066828903,4,121,'',0),(1706,93,303,0,69,854,863,2,1066828903,4,121,'',0),(1705,93,854,0,68,292,303,2,1066828903,4,121,'',0),(1704,93,292,0,67,102,854,2,1066828903,4,121,'',0),(1703,93,102,0,66,862,292,2,1066828903,4,121,'',0),(1702,93,862,0,65,254,102,2,1066828903,4,121,'',0),(1701,93,254,0,64,861,862,2,1066828903,4,121,'',0),(1700,93,861,0,63,860,254,2,1066828903,4,121,'',0),(1699,93,860,0,62,180,861,2,1066828903,4,121,'',0),(1698,93,180,0,61,859,860,2,1066828903,4,121,'',0),(1697,93,859,0,60,858,180,2,1066828903,4,121,'',0),(1696,93,858,0,59,849,859,2,1066828903,4,121,'',0),(1695,93,849,0,58,857,858,2,1066828903,4,121,'',0),(1694,93,857,0,57,856,849,2,1066828903,4,121,'',0),(1693,93,856,0,56,75,857,2,1066828903,4,121,'',0),(1692,93,75,0,55,855,856,2,1066828903,4,121,'',0),(1691,93,855,0,54,77,75,2,1066828903,4,121,'',0),(1690,93,77,0,53,294,855,2,1066828903,4,121,'',0),(1689,93,294,0,52,254,77,2,1066828903,4,121,'',0),(1688,93,254,0,51,235,294,2,1066828903,4,121,'',0),(1687,93,235,0,50,854,254,2,1066828903,4,121,'',0),(1686,93,854,0,49,292,235,2,1066828903,4,121,'',0),(1685,93,292,0,48,853,854,2,1066828903,4,121,'',0),(1684,93,853,0,47,842,292,2,1066828903,4,121,'',0),(1683,93,842,0,46,148,853,2,1066828903,4,121,'',0),(1682,93,148,0,45,77,842,2,1066828903,4,121,'',0),(1681,93,77,0,44,852,148,2,1066828903,4,121,'',0),(1680,93,852,0,43,843,77,2,1066828903,4,121,'',0),(1679,93,843,0,42,843,852,2,1066828903,4,121,'',0),(1678,93,843,0,41,851,843,2,1066828903,4,120,'',0),(1677,93,851,0,40,75,843,2,1066828903,4,120,'',0),(1676,93,75,0,39,183,851,2,1066828903,4,120,'',0),(1675,93,183,0,38,850,75,2,1066828903,4,120,'',0),(1674,93,850,0,37,849,183,2,1066828903,4,120,'',0),(1673,93,849,0,36,224,850,2,1066828903,4,120,'',0),(1672,93,224,0,35,848,849,2,1066828903,4,120,'',0),(1671,93,848,0,34,75,224,2,1066828903,4,120,'',0),(1670,93,75,0,33,152,848,2,1066828903,4,120,'',0),(1669,93,152,0,32,180,75,2,1066828903,4,120,'',0),(1668,93,180,0,31,847,152,2,1066828903,4,120,'',0),(1667,93,847,0,30,843,180,2,1066828903,4,120,'',0),(1666,93,843,0,29,73,847,2,1066828903,4,120,'',0),(1665,93,73,0,28,148,843,2,1066828903,4,120,'',0),(1664,93,148,0,27,145,73,2,1066828903,4,120,'',0),(1663,93,145,0,26,144,148,2,1066828903,4,120,'',0),(1662,93,144,0,25,152,145,2,1066828903,4,120,'',0),(1661,93,152,0,24,846,144,2,1066828903,4,120,'',0),(1660,93,846,0,23,81,152,2,1066828903,4,120,'',0),(1659,93,81,0,22,77,846,2,1066828903,4,120,'',0),(1658,93,77,0,21,845,81,2,1066828903,4,120,'',0),(1657,93,845,0,20,74,77,2,1066828903,4,120,'',0),(1656,93,74,0,19,67,845,2,1066828903,4,120,'',0),(1655,93,67,0,18,33,74,2,1066828903,4,120,'',0),(1654,93,33,0,17,843,67,2,1066828903,4,120,'',0),(1653,93,843,0,16,148,33,2,1066828903,4,120,'',0),(1652,93,148,0,15,150,843,2,1066828903,4,120,'',0),(1651,93,150,0,14,75,148,2,1066828903,4,120,'',0),(1650,93,75,0,13,152,150,2,1066828903,4,120,'',0),(1649,93,152,0,12,844,75,2,1066828903,4,120,'',0),(1648,93,844,0,11,156,152,2,1066828903,4,120,'',0),(1647,93,156,0,10,74,844,2,1066828903,4,120,'',0),(1646,93,74,0,9,842,156,2,1066828903,4,120,'',0),(1645,93,842,0,8,148,74,2,1066828903,4,120,'',0),(1644,93,148,0,7,145,842,2,1066828903,4,120,'',0),(1643,93,145,0,6,144,148,2,1066828903,4,120,'',0),(1642,93,144,0,5,843,145,2,1066828903,4,120,'',0),(1641,93,843,0,4,842,144,2,1066828903,4,1,'',0),(1640,93,842,0,3,148,843,2,1066828903,4,1,'',0),(1639,93,148,0,2,145,842,2,1066828903,4,1,'',0),(1638,93,145,0,1,144,148,2,1066828903,4,1,'',0),(1637,93,144,0,0,0,145,2,1066828903,4,1,'',0),(1078,94,335,0,15,74,0,2,1066829047,4,121,'',0),(1077,94,74,0,14,552,335,2,1066829047,4,121,'',0),(1076,94,552,0,13,551,74,2,1066829047,4,121,'',0),(1075,94,551,0,12,108,552,2,1066829047,4,121,'',0),(1074,94,108,0,11,74,551,2,1066829047,4,120,'',0),(1073,94,74,0,10,227,108,2,1066829047,4,120,'',0),(1072,94,227,0,9,550,74,2,1066829047,4,120,'',0),(1071,94,550,0,8,195,227,2,1066829047,4,120,'',0),(1070,94,195,0,7,89,550,2,1066829047,4,120,'',0),(1069,94,89,0,6,549,195,2,1066829047,4,120,'',0),(1068,94,549,0,5,86,89,2,1066829047,4,120,'',0),(1067,94,86,0,4,221,549,2,1066829047,4,120,'',0),(1066,94,221,0,3,548,86,2,1066829047,4,1,'',0),(1065,94,548,0,2,335,221,2,1066829047,4,1,'',0),(1064,94,335,0,1,334,548,2,1066829047,4,1,'',0),(1063,94,334,0,0,0,335,2,1066829047,4,1,'',0),(2137,92,1091,0,16,183,1092,2,1066828821,4,120,'',0),(2138,92,1092,0,17,1091,1093,2,1066828821,4,120,'',0),(2121,92,144,0,0,0,1083,2,1066828821,4,1,'',0),(2122,92,1083,0,1,144,1084,2,1066828821,4,1,'',0),(2123,92,1084,0,2,1083,1085,2,1066828821,4,1,'',0),(2124,92,1085,0,3,1084,108,2,1066828821,4,1,'',0),(2125,92,108,0,4,1085,1086,2,1066828821,4,1,'',0),(2126,92,1086,0,5,108,73,2,1066828821,4,1,'',0),(2127,92,73,0,6,1086,1087,2,1066828821,4,120,'',0),(2128,92,1087,0,7,73,180,2,1066828821,4,120,'',0),(2129,92,180,0,8,1087,1088,2,1066828821,4,120,'',0),(2130,92,1088,0,9,180,152,2,1066828821,4,120,'',0),(2131,92,152,0,10,1088,75,2,1066828821,4,120,'',0),(2132,92,75,0,11,152,144,2,1066828821,4,120,'',0),(2133,92,144,0,12,75,1089,2,1066828821,4,120,'',0),(2134,92,1089,0,13,144,1090,2,1066828821,4,120,'',0),(2135,92,1090,0,14,1089,183,2,1066828821,4,120,'',0),(2136,92,183,0,15,1090,1091,2,1066828821,4,120,'',0),(1106,107,571,0,1,570,0,4,1066916865,2,9,'',0),(1105,107,570,0,0,0,571,4,1066916865,2,8,'',0),(1107,111,572,0,0,0,573,4,1066917523,2,8,'',0),(1108,111,573,0,1,572,0,4,1066917523,2,9,'',0),(1847,1,934,0,6,465,0,1,1033917596,1,119,'',0),(1846,1,465,0,5,180,934,1,1033917596,1,119,'',0),(1845,1,180,0,4,750,465,1,1033917596,1,119,'',0),(1844,1,750,0,3,933,180,1,1033917596,1,119,'',0),(1843,1,933,0,2,73,750,1,1033917596,1,119,'',0),(1841,1,932,0,0,0,73,1,1033917596,1,4,'',0),(1842,1,73,0,1,932,933,1,1033917596,1,119,'',0),(2116,137,1078,0,20,1077,0,19,1068027382,1,182,'',0),(2115,137,1077,0,19,1076,1078,19,1068027382,1,182,'',0),(2114,137,1076,0,18,942,1077,19,1068027382,1,182,'',0),(2113,137,942,0,17,183,1076,19,1068027382,1,182,'',0),(2112,137,183,0,16,1068,942,19,1068027382,1,182,'',0),(2111,137,1068,0,15,102,183,19,1068027382,1,182,'',0),(2110,137,102,0,14,1075,1068,19,1068027382,1,182,'',0),(2109,137,1075,0,13,1074,102,19,1068027382,1,182,'',0),(2108,137,1074,0,12,1073,1075,19,1068027382,1,182,'',0),(2107,137,1073,0,11,1072,1074,19,1068027382,1,182,'',0),(2106,137,1072,0,10,759,1073,19,1068027382,1,182,'',0),(2105,137,759,0,9,936,1072,19,1068027382,1,182,'',0),(2104,137,936,0,8,1071,759,19,1068027382,1,182,'',0),(2103,137,1071,0,7,1070,936,19,1068027382,1,182,'',0),(2102,137,1070,0,6,1069,1071,19,1068027382,1,182,'',0),(2101,137,1069,0,5,75,1070,19,1068027382,1,182,'',0),(2100,137,75,0,4,183,1069,19,1068027382,1,182,'',0),(2099,137,183,0,3,1068,75,19,1068027382,1,182,'',0),(2098,137,1068,0,2,221,183,19,1068027382,1,182,'',0),(2097,137,221,0,1,1067,1068,19,1068027382,1,181,'',0),(2096,137,1067,0,0,0,221,19,1068027382,1,181,'',0),(2059,136,993,0,189,947,0,10,1067937053,1,141,'',0),(2058,136,947,0,188,946,993,10,1067937053,1,141,'',0),(2057,136,946,0,187,990,947,10,1067937053,1,141,'',0),(2056,136,990,0,186,1044,946,10,1067937053,1,141,'',0),(2055,136,1044,0,185,1043,990,10,1067937053,1,141,'',0),(2054,136,1043,0,184,987,1044,10,1067937053,1,141,'',0),(2053,136,987,0,183,1015,1043,10,1067937053,1,141,'',0),(2052,136,1015,0,182,1042,987,10,1067937053,1,141,'',0),(2051,136,1042,0,181,986,1015,10,1067937053,1,141,'',0),(2050,136,986,0,180,1022,1042,10,1067937053,1,141,'',0),(2049,136,1022,0,179,1041,986,10,1067937053,1,141,'',0),(2048,136,1041,0,178,1040,1022,10,1067937053,1,141,'',0),(2047,136,1040,0,177,996,1041,10,1067937053,1,141,'',0),(2046,136,996,0,176,1011,1040,10,1067937053,1,141,'',0),(2045,136,1011,0,175,992,996,10,1067937053,1,141,'',0),(2044,136,992,0,174,951,1011,10,1067937053,1,141,'',0),(2043,136,951,0,173,986,992,10,1067937053,1,141,'',0),(2042,136,986,0,172,1010,951,10,1067937053,1,141,'',0),(2041,136,1010,0,171,1039,986,10,1067937053,1,141,'',0),(2040,136,1039,0,170,975,1010,10,1067937053,1,141,'',0),(2039,136,975,0,169,1038,1039,10,1067937053,1,141,'',0),(2038,136,1038,0,168,1030,975,10,1067937053,1,141,'',0),(2037,136,1030,0,167,955,1038,10,1067937053,1,141,'',0),(2036,136,955,0,166,1019,1030,10,1067937053,1,141,'',0),(2035,136,1019,0,165,1037,955,10,1067937053,1,141,'',0),(2034,136,1037,0,164,999,1019,10,1067937053,1,141,'',0),(2033,136,999,0,163,944,1037,10,1067937053,1,141,'',0),(2032,136,944,0,162,183,999,10,1067937053,1,141,'',0),(2031,136,183,0,161,1019,944,10,1067937053,1,141,'',0),(2030,136,1019,0,160,1036,183,10,1067937053,1,141,'',0),(2029,136,1036,0,159,1012,1019,10,1067937053,1,141,'',0),(2028,136,1012,0,158,1035,1036,10,1067937053,1,141,'',0),(2027,136,1035,0,157,1012,1012,10,1067937053,1,141,'',0),(2026,136,1012,0,156,950,1035,10,1067937053,1,141,'',0),(2025,136,950,0,155,987,1012,10,1067937053,1,141,'',0),(2024,136,987,0,154,1034,950,10,1067937053,1,141,'',0),(2023,136,1034,0,153,998,987,10,1067937053,1,141,'',0),(2022,136,998,0,152,1033,1034,10,1067937053,1,141,'',0),(2021,136,1033,0,151,985,998,10,1067937053,1,141,'',0),(2020,136,985,0,150,987,1033,10,1067937053,1,141,'',0),(2019,136,987,0,149,991,985,10,1067937053,1,141,'',0),(2018,136,991,0,148,975,987,10,1067937053,1,141,'',0),(2017,136,975,0,147,1032,991,10,1067937053,1,141,'',0),(2016,136,1032,0,146,1005,975,10,1067937053,1,141,'',0),(2015,136,1005,0,145,998,1032,10,1067937053,1,141,'',0),(2014,136,998,0,144,1031,1005,10,1067937053,1,141,'',0),(2013,136,1031,0,143,1030,998,10,1067937053,1,141,'',0),(2012,136,1030,0,142,1016,1031,10,1067937053,1,141,'',0),(2011,136,1016,0,141,954,1030,10,1067937053,1,141,'',0),(2010,136,954,0,140,960,1016,10,1067937053,1,141,'',0),(2009,136,960,0,139,1000,954,10,1067937053,1,141,'',0),(2008,136,1000,0,138,1029,960,10,1067937053,1,141,'',0),(2007,136,1029,0,137,1023,1000,10,1067937053,1,141,'',0),(2006,136,1023,0,136,953,1029,10,1067937053,1,141,'',0),(2005,136,953,0,135,1028,1023,10,1067937053,1,141,'',0),(2004,136,1028,0,134,1027,953,10,1067937053,1,141,'',0),(2003,136,1027,0,133,987,1028,10,1067937053,1,141,'',0),(2002,136,987,0,132,985,1027,10,1067937053,1,141,'',0),(2001,136,985,0,131,1026,987,10,1067937053,1,141,'',0),(2000,136,1026,0,130,1021,985,10,1067937053,1,141,'',0),(1999,136,1021,0,129,992,1026,10,1067937053,1,141,'',0),(1998,136,992,0,128,1025,1021,10,1067937053,1,141,'',0),(1997,136,1025,0,127,1024,992,10,1067937053,1,141,'',0),(1996,136,1024,0,126,996,1025,10,1067937053,1,141,'',0),(1995,136,996,0,125,1023,1024,10,1067937053,1,141,'',0),(1994,136,1023,0,124,1022,996,10,1067937053,1,141,'',0),(1993,136,1022,0,123,1021,1023,10,1067937053,1,141,'',0),(1992,136,1021,0,122,947,1022,10,1067937053,1,141,'',0),(1991,136,947,0,121,946,1021,10,1067937053,1,141,'',0),(1990,136,946,0,120,986,947,10,1067937053,1,141,'',0),(1989,136,986,0,119,1020,946,10,1067937053,1,141,'',0),(1988,136,1020,0,118,1019,986,10,1067937053,1,141,'',0),(1987,136,1019,0,117,1018,1020,10,1067937053,1,141,'',0),(1986,136,1018,0,116,949,1019,10,1067937053,1,141,'',0),(1985,136,949,0,115,955,1018,10,1067937053,1,141,'',0),(1984,136,955,0,114,975,949,10,1067937053,1,141,'',0),(1983,136,975,0,113,1017,955,10,1067937053,1,141,'',0),(1982,136,1017,0,112,1016,975,10,1067937053,1,141,'',0),(1981,136,1016,0,111,998,1017,10,1067937053,1,141,'',0),(1980,136,998,0,110,950,1016,10,1067937053,1,141,'',0),(1979,136,950,0,109,949,998,10,1067937053,1,141,'',0),(1978,136,949,0,108,948,950,10,1067937053,1,141,'',0),(1977,136,948,0,107,947,949,10,1067937053,1,141,'',0),(1976,136,947,0,106,946,948,10,1067937053,1,141,'',0),(1975,136,946,0,105,945,947,10,1067937053,1,141,'',0),(1974,136,945,0,104,944,946,10,1067937053,1,141,'',0),(1973,136,944,0,103,943,945,10,1067937053,1,141,'',0),(1972,136,943,0,102,1015,944,10,1067937053,1,141,'',0),(1971,136,1015,0,101,978,943,10,1067937053,1,141,'',0),(1970,136,978,0,100,1014,1015,10,1067937053,1,141,'',0),(1969,136,1014,0,99,951,978,10,1067937053,1,141,'',0),(1968,136,951,0,98,995,1014,10,1067937053,1,141,'',0),(1967,136,995,0,97,960,951,10,1067937053,1,141,'',0),(1966,136,960,0,96,1013,995,10,1067937053,1,141,'',0),(1965,136,1013,0,95,1012,960,10,1067937053,1,141,'',0),(1964,136,1012,0,94,1011,1013,10,1067937053,1,141,'',0),(1963,136,1011,0,93,1010,1012,10,1067937053,1,141,'',0),(1962,136,1010,0,92,1009,1011,10,1067937053,1,141,'',0),(1961,136,1009,0,91,996,1010,10,1067937053,1,141,'',0),(1960,136,996,0,90,1008,1009,10,1067937053,1,141,'',0),(1959,136,1008,0,89,1002,996,10,1067937053,1,141,'',0),(1958,136,1002,0,88,1007,1008,10,1067937053,1,141,'',0),(1957,136,1007,0,87,1006,1002,10,1067937053,1,141,'',0),(1956,136,1006,0,86,993,1007,10,1067937053,1,141,'',0),(1955,136,993,0,85,1005,1006,10,1067937053,1,141,'',0),(1954,136,1005,0,84,1004,993,10,1067937053,1,141,'',0),(1953,136,1004,0,83,1003,1005,10,1067937053,1,141,'',0),(1952,136,1003,0,82,1001,1004,10,1067937053,1,141,'',0),(1951,136,1001,0,81,1002,1003,10,1067937053,1,141,'',0),(1950,136,1002,0,80,1001,1001,10,1067937053,1,141,'',0),(1949,136,1001,0,79,1000,1002,10,1067937053,1,141,'',0),(1948,136,1000,0,78,999,1001,10,1067937053,1,141,'',0),(1947,136,999,0,77,982,1000,10,1067937053,1,141,'',0),(1946,136,982,0,76,998,999,10,1067937053,1,141,'',0),(1945,136,998,0,75,997,982,10,1067937053,1,141,'',0),(1944,136,997,0,74,996,998,10,1067937053,1,141,'',0),(1943,136,996,0,73,995,997,10,1067937053,1,141,'',0),(1942,136,995,0,72,952,996,10,1067937053,1,141,'',0),(1941,136,952,0,71,994,995,10,1067937053,1,141,'',0),(1940,136,994,0,70,993,952,10,1067937053,1,141,'',0),(1939,136,993,0,69,992,994,10,1067937053,1,141,'',0),(1938,136,992,0,68,89,993,10,1067937053,1,141,'',0),(1937,136,89,0,67,944,992,10,1067937053,1,141,'',0),(1936,136,944,0,66,977,89,10,1067937053,1,141,'',0),(1935,136,977,0,65,991,944,10,1067937053,1,141,'',0),(1934,136,991,0,64,990,977,10,1067937053,1,141,'',0),(1933,136,990,0,63,948,991,10,1067937053,1,141,'',0),(1932,136,948,0,62,960,990,10,1067937053,1,141,'',0),(1931,136,960,0,61,987,948,10,1067937053,1,141,'',0),(1930,136,987,0,60,989,960,10,1067937053,1,141,'',0),(1929,136,989,0,59,988,987,10,1067937053,1,141,'',0),(1928,136,988,0,58,987,989,10,1067937053,1,141,'',0),(1927,136,987,0,57,986,988,10,1067937053,1,141,'',0),(1926,136,986,0,56,89,987,10,1067937053,1,141,'',0),(1925,136,89,0,55,985,986,10,1067937053,1,141,'',0),(1924,136,985,0,54,984,89,10,1067937053,1,141,'',0),(1923,136,984,0,53,983,985,10,1067937053,1,141,'',0),(1922,136,983,0,52,982,984,10,1067937053,1,141,'',0),(1921,136,982,0,51,981,983,10,1067937053,1,141,'',0),(1920,136,981,0,50,978,982,10,1067937053,1,141,'',0),(1919,136,978,0,49,980,981,10,1067937053,1,141,'',0),(1918,136,980,0,48,943,978,10,1067937053,1,141,'',0),(1917,136,943,0,47,975,980,10,1067937053,1,141,'',0),(1916,136,975,0,46,979,943,10,1067937053,1,141,'',0),(1915,136,979,0,45,978,975,10,1067937053,1,141,'',0),(1914,136,978,0,44,977,979,10,1067937053,1,141,'',0),(1913,136,977,0,43,943,978,10,1067937053,1,141,'',0),(1912,136,943,0,42,976,977,10,1067937053,1,141,'',0),(1911,136,976,0,41,961,943,10,1067937053,1,141,'',0),(1910,136,961,0,40,975,976,10,1067937053,1,141,'',0),(1909,136,975,0,39,974,961,10,1067937053,1,141,'',0),(1908,136,974,0,38,973,975,10,1067937053,1,141,'',0),(1907,136,973,0,37,972,974,10,1067937053,1,141,'',0),(1906,136,972,0,36,971,973,10,1067937053,1,141,'',0),(1905,136,971,0,35,970,972,10,1067937053,1,141,'',0),(1904,136,970,0,34,969,971,10,1067937053,1,141,'',0),(1903,136,969,0,33,968,970,10,1067937053,1,141,'',0),(1902,136,968,0,32,967,969,10,1067937053,1,141,'',0),(1901,136,967,0,31,966,968,10,1067937053,1,141,'',0),(1900,136,966,0,30,965,967,10,1067937053,1,141,'',0),(1899,136,965,0,29,964,966,10,1067937053,1,141,'',0),(1898,136,964,0,28,963,965,10,1067937053,1,141,'',0),(1897,136,963,0,27,962,964,10,1067937053,1,141,'',0),(1896,136,962,0,26,961,963,10,1067937053,1,141,'',0),(1895,136,961,0,25,960,962,10,1067937053,1,141,'',0),(1894,136,960,0,24,959,961,10,1067937053,1,141,'',0),(1893,136,959,0,23,958,960,10,1067937053,1,141,'',0),(1892,136,958,0,22,950,959,10,1067937053,1,141,'',0),(1891,136,950,0,21,957,958,10,1067937053,1,141,'',0),(1890,136,957,0,20,956,950,10,1067937053,1,141,'',0),(1889,136,956,0,19,955,957,10,1067937053,1,141,'',0),(1888,136,955,0,18,953,956,10,1067937053,1,141,'',0),(1887,136,953,0,17,954,955,10,1067937053,1,141,'',0),(1886,136,954,0,16,953,953,10,1067937053,1,141,'',0),(1885,136,953,0,15,952,954,10,1067937053,1,141,'',0),(1884,136,952,0,14,951,953,10,1067937053,1,141,'',0),(1883,136,951,0,13,950,952,10,1067937053,1,141,'',0),(1882,136,950,0,12,949,951,10,1067937053,1,141,'',0),(1881,136,949,0,11,948,950,10,1067937053,1,141,'',0),(1880,136,948,0,10,947,949,10,1067937053,1,141,'',0),(1879,136,947,0,9,946,948,10,1067937053,1,141,'',0),(1878,136,946,0,8,945,947,10,1067937053,1,141,'',0),(1877,136,945,0,7,944,946,10,1067937053,1,141,'',0),(1876,136,944,0,6,943,945,10,1067937053,1,141,'',0),(1875,136,943,0,5,940,944,10,1067937053,1,141,'',0),(1874,136,940,0,4,942,943,10,1067937053,1,141,'',0),(1873,136,942,0,3,934,940,10,1067937053,1,141,'',0),(1872,136,934,0,2,465,942,10,1067937053,1,141,'',0),(1871,136,465,0,1,934,934,10,1067937053,1,141,'',0),(1870,136,934,0,0,0,465,10,1067937053,1,140,'',0),(1864,135,465,0,2,940,0,1,1067936571,1,119,'',0),(1863,135,940,0,1,465,465,1,1067936571,1,119,'',0),(1862,135,465,0,0,0,940,1,1067936571,1,4,'',0),(1861,134,939,0,4,752,0,1,1067872529,1,119,'',0),(1860,134,752,0,3,934,939,1,1067872529,1,119,'',0),(1859,134,934,0,2,465,752,1,1067872529,1,119,'',0),(1858,134,465,0,1,939,934,1,1067872529,1,119,'',0),(1857,134,939,0,0,0,465,1,1067872529,1,4,'',0),(1856,133,935,0,8,752,0,1,1067872500,1,119,'',0),(1855,133,752,0,7,934,935,1,1067872500,1,119,'',0),(1854,133,934,0,6,465,752,1,1067872500,1,119,'',0),(1853,133,465,0,5,938,934,1,1067872500,1,119,'',0),(1852,133,938,0,4,937,465,1,1067872500,1,119,'',0),(1851,133,937,0,3,936,938,1,1067872500,1,119,'',0),(1850,133,936,0,2,381,937,1,1067872500,1,119,'',0),(1849,133,381,0,1,935,936,1,1067872500,1,119,'',0),(1848,133,935,0,0,0,381,1,1067872500,1,4,'',0),(2070,45,33,0,1,32,34,14,1066388816,11,152,'',0),(2065,115,303,0,2,7,0,14,1066991725,11,155,'',0),(2064,115,7,0,1,303,303,14,1066991725,11,155,'',0),(2063,115,303,0,0,0,7,14,1066991725,11,152,'',0),(2078,116,1054,0,3,25,0,14,1066992054,11,155,'',0),(2077,116,25,0,2,1053,1054,14,1066992054,11,155,'',0),(2076,116,1053,0,1,292,25,14,1066992054,11,152,'',0),(2075,116,292,0,0,0,1053,14,1066992054,11,152,'',0),(2069,45,32,0,0,0,33,14,1066388816,11,152,'',0);
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
INSERT INTO ezsearch_return_count VALUES (1,1,1066398569,1),(2,2,1066909621,1),(3,3,1066910511,1),(4,4,1066912239,1),(5,5,1066982534,1),(6,6,1066991890,4),(7,6,1066992837,4),(8,6,1066992963,4),(9,6,1066992972,0),(10,6,1066993049,0),(11,6,1066993056,4),(12,6,1066993091,4),(13,6,1066993127,4),(14,6,1066993135,4),(15,6,1066993895,4),(16,6,1066993946,4),(17,6,1066993995,4),(18,6,1066994001,4),(19,6,1066994050,4),(20,6,1066994057,4),(21,6,1066994067,4),(22,7,1066996820,0),(23,5,1066997190,1),(24,5,1066997194,1),(25,8,1066998830,1),(26,8,1066998836,1),(27,8,1066998870,1),(28,9,1066998915,1),(29,10,1067003146,0),(30,11,1067003155,2),(31,6,1067005771,4),(32,6,1067005777,4),(33,6,1067005801,4),(34,12,1067006770,1),(35,12,1067006774,1),(36,12,1067006777,1),(37,12,1067006787,1),(38,12,1067006803,1),(39,12,1067006996,1),(40,12,1067008585,1),(41,12,1067008597,1),(42,12,1067008602,0),(43,12,1067008608,1),(44,12,1067008613,0),(45,12,1067008620,0),(46,12,1067008625,0),(47,12,1067008629,1),(48,12,1067008655,1),(49,12,1067008659,0),(50,12,1067008663,0),(51,12,1067008667,0),(52,12,1067008711,0),(53,12,1067008717,0),(54,12,1067008720,1),(55,12,1067008725,0),(56,12,1067008920,1),(57,12,1067008925,1),(58,12,1067008929,0),(59,12,1067008934,1),(60,12,1067009005,1),(61,12,1067009023,1),(62,12,1067009042,1),(63,12,1067009051,0),(64,13,1067009056,1),(65,14,1067009067,0),(66,14,1067009073,0),(67,13,1067009594,1),(68,13,1067009816,1),(69,13,1067009953,1),(70,13,1067010181,1),(71,13,1067010352,1),(72,13,1067010359,1),(73,13,1067010370,1),(74,13,1067010509,1),(75,6,1067241668,5),(76,6,1067241727,5),(77,6,1067241742,5),(78,6,1067241760,5),(79,6,1067241810,5),(80,6,1067241892,5),(81,6,1067241928,5),(82,6,1067241953,5),(83,14,1067252984,0),(84,14,1067252987,0),(85,14,1067253026,0),(86,14,1067253160,0),(87,14,1067253218,0),(88,14,1067253285,0),(89,5,1067520640,1),(90,5,1067520646,1),(91,5,1067520658,1),(92,5,1067520704,0),(93,5,1067520753,0),(94,5,1067520761,1),(95,5,1067520769,1),(96,5,1067521324,1),(97,5,1067521402,1),(98,5,1067521453,1),(99,5,1067521532,1),(100,5,1067521615,1),(101,5,1067521674,1),(102,5,1067521990,1),(103,5,1067522592,1),(104,5,1067522620,1),(105,5,1067522888,1),(106,5,1067522987,1),(107,5,1067523012,1),(108,5,1067523144,1),(109,5,1067523213,1),(110,5,1067523261,1),(111,5,1067523798,1),(112,5,1067523805,1),(113,5,1067523820,1),(114,5,1067523858,1),(115,5,1067524474,1),(116,5,1067524629,1),(117,5,1067524696,1),(118,15,1067526426,0),(119,15,1067526433,0),(120,15,1067526701,0),(121,15,1067527009,0),(122,5,1067527022,1),(123,5,1067527033,1),(124,5,1067527051,1),(125,5,1067527069,1),(126,5,1067527076,0),(127,5,1067527124,1),(128,5,1067527176,1),(129,16,1067527188,0),(130,16,1067527227,0),(131,16,1067527244,0),(132,16,1067527301,0),(133,5,1067527315,0),(134,5,1067527349,0),(135,5,1067527412,0),(136,5,1067527472,1),(137,5,1067527502,1),(138,5,1067527508,0),(139,17,1067527848,0),(140,5,1067527863,1),(141,5,1067527890,1),(142,5,1067527906,1),(143,5,1067527947,1),(144,5,1067527968,0),(145,5,1067527993,0),(146,5,1067528010,1),(147,5,1067528029,0),(148,5,1067528045,0),(149,5,1067528050,0),(150,5,1067528056,0),(151,5,1067528061,0),(152,5,1067528063,0),(153,18,1067528100,1),(154,18,1067528113,0),(155,18,1067528190,1),(156,18,1067528236,1),(157,18,1067528270,1),(158,18,1067528309,1),(159,5,1067528323,0),(160,18,1067528334,1),(161,18,1067528355,1),(162,5,1067528368,0),(163,5,1067528377,1),(164,19,1067528402,0),(165,19,1067528770,0),(166,19,1067528924,0),(167,19,1067528963,0),(168,19,1067529028,0),(169,19,1067529054,0),(170,19,1067529119,0),(171,19,1067529169,0),(172,19,1067529211,0),(173,19,1067529263,0),(174,20,1067943156,3),(175,4,1067943454,1),(176,4,1067943503,1),(177,4,1067943525,1),(178,21,1067943559,1),(179,21,1067945657,1),(180,21,1067945693,1),(181,21,1067945697,1),(182,21,1067945707,1),(183,22,1067945890,0),(184,20,1067945898,3),(185,23,1067946301,6),(186,24,1067946325,1),(187,24,1067946432,1),(188,25,1067946484,4),(189,26,1067946492,1),(190,27,1067946577,1),(191,25,1067946691,4),(192,4,1067946702,1),(193,4,1067947201,1),(194,4,1067947228,1),(195,4,1067948201,1),(196,5,1068028867,0),(197,12,1068028883,0),(198,28,1068028898,2),(199,29,1068039182,0),(200,30,1068042355,0),(201,31,1068048393,0),(202,31,1068048440,0),(203,32,1068217893,1);
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
INSERT INTO ezsearch_search_phrase VALUES (1,'documents'),(2,'wenyue'),(3,'xxx'),(4,'release'),(5,'test'),(6,'ez'),(7,'f1'),(8,'bjørn'),(9,'abb'),(10,'2-2'),(11,'3.2'),(12,'bård'),(13,'Vidar'),(14,'tewtet'),(15,'dcv'),(16,'gr'),(17,'tewt'),(18,'members'),(19,'regte'),(20,'news'),(21,'german'),(22,'info'),(23,'information'),(24,'folder'),(25,'about'),(26,'2'),(27,'systems'),(28,'the'),(29,'this'),(30,'message'),(31,'football'),(32,'mr');
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
INSERT INTO ezsearch_word VALUES (5,'test',1),(6,'media',2),(7,'setup',3),(1050,'grouplist',1),(1049,'class',1),(1048,'classes',1),(11,'links',1),(1170,'be',1),(25,'content',4),(34,'feel',2),(33,'and',4),(32,'look',2),(37,'news',3),(49,'business',1),(1202,'copyright',1),(50,'off',1),(51,'topic',1),(53,'reports',1),(54,'staff',1),(1178,'minds',1),(1160,'they',1),(1036,'ullamcorper',1),(1038,'mattis',1),(1037,'lacus',1),(933,'folder',1),(934,'about',4),(67,'it',2),(1169,'can',1),(1159,'however',1),(73,'this',3),(74,'is',3),(75,'the',3),(1158,'licenses',1),(77,'for',2),(81,'all',2),(891,'enhancements',1),(890,'bugfixes',1),(1157,'public',1),(86,'we',2),(888,'spanish',1),(89,'a',3),(889,'catalan',1),(887,'german',1),(886,'mozambique',1),(1156,'software',1),(885,'portuguese',1),(884,'translations',1),(883,'filter',1),(102,'to',3),(1155,'unfamiliar',1),(1154,'still',1),(1153,'linux',1),(1152,'gnu',1),(108,'from',2),(1151,'success',1),(1150,'enormous',1),(1149,'despite',1),(1148,'small',1),(1147,'large',1),(1163,'impressed',1),(1073,'feedback',1),(1072,'any',1),(1071,'if',1),(1070,'below',1),(1069,'form',1),(1068,'fill',1),(1067,'contact',1),(1044,'tortor',1),(1043,'sollicitudin',1),(156,'an',2),(144,'ez',3),(145,'publish',2),(1042,'aliquet',1),(148,'3.2',1),(1040,'placerat',1),(150,'stable',1),(1039,'vehicula',1),(152,'of',2),(1041,'elementum',1),(1146,'companies',1),(1145,'organizations',1),(180,'some',3),(1144,'various',1),(183,'in',4),(1143,'representing',1),(1142,'them',1),(1141,'austria',1),(1140,'mostly',1),(1139,'visitors',1),(195,'new',3),(1138,'framework',1),(1137,'development',1),(1136,'management',1),(1135,'source',1),(1134,'open',1),(1133,'which',1),(1132,'product',1),(1131,'main',1),(1130,'knowledge',1),(1129,'many',1),(221,'us',3),(1128,'there',1),(381,'here',2),(224,'that',2),(1127,'seems',1),(227,'who',2),(1126,'already',1),(1125,'visited',1),(1124,'people',1),(1123,'lot',1),(235,'system',2),(1122,'day',1),(1121,'been',1),(1120,'barely',1),(1119,'expectations',1),(1118,'exceeding',1),(1117,'positive',1),(1116,'very',1),(1115,'impressions',1),(1114,'first',1),(1113,'report',1),(759,'have',2),(254,'with',2),(1112,'text',1),(1111,'following',1),(1110,'october',1),(1109,'24th',1),(1108,'20th',1),(1107,'site',1),(752,'our',3),(1106,'on',1),(750,'contains',2),(1105,'representatives',1),(1104,'four',1),(1103,'time',1),(1102,'22nd',1),(1101,'held',1),(882,'attribute',1),(881,'field',1),(880,'well',1),(879,'as',2),(878,'attributes',1),(877,'object',1),(876,'keys',1),(875,'sort',1),(874,'bug',1),(873,'orders',1),(292,'url',2),(872,'basket',1),(294,'support',1),(871,'regarding',1),(870,'shop',1),(869,'problem',1),(868,'fixed',1),(867,'transport',1),(866,'smtp',1),(865,'bcc',1),(864,'cc',1),(303,'cache',2),(863,'clearing',1),(862,'regards',1),(861,'improvements',1),(860,'ui',1),(859,'updated',1),(858,'also',1),(857,'scripts',1),(856,'updatenicurls',1),(855,'wildcards',1),(854,'alias',1),(853,'improved',1),(852,'notes',1),(851,'last',1),(850,'present',1),(849,'were',1),(848,'problems',1),(847,'fixes',1),(846,'users',1),(845,'recommended',1),(844,'upgrade',1),(331,'general',1),(843,'release',1),(842,'2',1),(334,'mr',1),(335,'xxx',1),(552,'name',1),(551,'his',1),(550,'employee',1),(549,'hired',1),(548,'joined',1),(1100,'telecommunications',1),(465,'information',6),(1099,'technology',1),(1098,'fair',1),(1097,'trade',1),(1096,'international',1),(1095,'2003',2),(1094,'2003\"',1),(1093,'\"systems',1),(1092,'attending',1),(1035,'felis',1),(1034,'quisque',1),(1177,'creative',1),(1176,'community',1),(1175,'huge',1),(1091,'germany',1),(1090,'are',1),(1089,'crew',1),(1088,'members',1),(1087,'week',1),(1086,'munich',1),(1085,'live',1),(1084,'reporting',1),(1083,'systems',2),(1174,'having',1),(1173,'benefits',1),(1172,'mention',1),(1171,'not',1),(571,'doe',1),(570,'john',1),(572,'vid',1),(573,'la',1),(932,'corporate',1),(1033,'lobortis',1),(1032,'donec',1),(1031,'metus',1),(1030,'id',1),(1029,'facilisis',1),(1028,'integer',1),(1027,'eros',1),(1026,'nibh',1),(1025,'suspendisse',1),(1024,'sapien',1),(1023,'arcu',1),(1022,'velit',1),(1021,'pellentesque',1),(1020,'egestas',1),(1019,'mauris',1),(1018,'odio',1),(1017,'sem',1),(1016,'tincidunt',1),(1015,'orci',1),(1014,'nonummy',1),(1013,'eleifend',1),(1012,'fusce',1),(1011,'urna',1),(1010,'at',1),(1009,'pharetra',1),(1008,'rutrum',1),(1007,'interdum',1),(1006,'erat',1),(1005,'vestibulum',1),(1004,'vulputate',1),(1003,'ultricies',1),(1002,'vel',1),(1001,'massa',1),(1000,'non',1),(999,'nulla',1),(998,'aliquam',1),(997,'posuere',1),(996,'ut',1),(995,'justo',1),(994,'diam',1),(993,'neque',1),(992,'consequat',1),(991,'leo',1),(990,'quam',1),(989,'ligula',1),(988,'dignissim',1),(987,'sed',1),(986,'augue',1),(985,'nunc',1),(984,'ultrices',1),(983,'vivamus',1),(982,'lectus',1),(981,'tristique',1),(980,'nullam',1),(979,'phasellus',1),(978,'sodales',1),(977,'auctor',1),(976,'ac',1),(975,'vitae',1),(974,'cras',1),(973,'mus',1),(972,'ridiculus',1),(971,'nascetur',1),(970,'montes',1),(969,'parturient',1),(968,'dis',1),(967,'magnis',1),(966,'et',1),(965,'penatibus',1),(964,'natoque',1),(963,'sociis',1),(962,'cum',1),(961,'libero',1),(960,'dictum',1),(959,'congue',1),(958,'etiam',1),(957,'maecenas',1),(956,'suscipit',1),(955,'porta',1),(954,'quis',1),(953,'mi',1),(952,'eget',1),(951,'curabitur',1),(950,'elit',1),(949,'adipiscing',1),(948,'consectetuer',1),(947,'amet',1),(946,'sit',1),(945,'dolor',1),(944,'ipsum',1),(943,'lorem',1),(942,'your',2),(940,'company',2),(939,'services',1),(938,'find',1),(937,'will',1),(936,'you',2),(935,'products',1),(1052,'56',1),(1051,'edit',1),(1054,'urltranslator',1),(1053,'translator',1),(1077,'mail',1),(1162,'seem',1),(1161,'sure',1),(1078,'address',1),(1168,'powerful',1),(1167,'how',1),(1166,'just',1),(1165,'realize',1),(1164,'when',1),(1076,'e',1),(1075,'remember',1),(1074,'please',1),(1180,'together',1),(1179,'working',1),(1181,'achieve',1),(1182,'great',1),(1183,'things',1),(1203,'&copy',1),(1201,'corporate_package',1),(1204,'1999',1);
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
INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart'),(2,'Users','','ezusernavigationpart'),(3,'Media','','ezmedianavigationpart'),(4,'News','','ezcontentnavigationpart'),(5,'Contact','','ezcontentnavigationpart'),(6,'Files','','ezcontentnavigationpart'),(11,'Set up object','','ezsetupnavigationpart');
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
INSERT INTO ezsession VALUES ('237c45bc0d93196aea4652196dcb1ca1',1069939252,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069427179;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069427179;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069680052;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069427179;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}'),('9c519e09c2d6a05911210e7cf7485eba',1069689794,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069430594;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('dfbc2fec6626aa5ee49cf08839b75264',1069689795,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069430595;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('31ebec39a2de8809566008c9be160fd0',1069689903,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069430703;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('4d5312b30aaa74529ae2370c8e1b1da0',1069945210,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069686010;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('9787a97769a7e761785e3f8c99a97284',1069945213,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069686013;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('e9dfb7981bccd905ef01a27f66aabee6',1069946711,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069687429;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069687429;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069687429;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069687429;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}'),('4594987f93f84e5587fd75441b437cb0',1069945199,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069685999;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069685999;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069685999;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}'),('61681817f87fc11f7c60bef4c46779d5',1069940338,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069681137;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069681137;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069681137;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069681137;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}'),('f358ca34536a332178e0d3fff9d03cef',1069935309,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069676108;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('6504c19a10041a915e9c9d7e234c8702',1069935634,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069676434;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069676434;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069676434;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069676434;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}'),('310c286311d138e22ca04681dfacc2a7',1069946534,'LastAccessesURI|s:21:\"/content/view/full/50\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069676454;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069676454;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069687333;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069676454;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}'),('89d37b44cfabf1fc009f64d4b23421ed',1069935308,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069676108;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('c9d0107eebc313f74a72dea0e80f07e2',1069935059,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069675859;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069675859;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069675859;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}'),('b18c6a55bdc0a5b9b937e47bb84f8f61',1069935358,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069676157;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069676157;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069676157;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069676158;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}'),('38fa3a07af80789f2c3b638565f15e46',1069935058,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069675857;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('1fd50dec152b40e4e6ec06eb6d9673fb',1069933303,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069674102;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069674102;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069674103;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}'),('ac85f313b8d8e909977f6c96db8ef0b3',1069669451,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069410250;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069410251;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069410251;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}'),('6e9f2aa4bc7c154b8eec66de5fe22868',1069674001,'eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069411811;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069411811;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069414755;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069411788;eZUserDiscountRules10|a:0:{}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069412613;canInstantiateClasses|i:1;canInstantiateClassList|a:10:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"19\";s:4:\"name\";s:13:\"Feedback form\";}}Preferences-advanced_menu|s:2:\"on\";FromGroupID|b:0;classesCachedForUser|s:2:\"14\";'),('c5a8c48486b1c47005dff74b0ae55460',1069673781,'LastAccessesURI|s:21:\"/content/view/full/50\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069414164;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069414164;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069414395;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069414164;eZUserDiscountRules10|a:0:{}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"4\";}'),('11aa60fe7a56587c7890e113b5c4daae',1069684263,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069425063;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425063;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069425063;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069425063;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}'),('dd978bd72748cac349b73d151d3feb0b',1069684815,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069425614;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425614;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069425614;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}'),('f01cd9ad6c05527b5a586df7a6e97468',1069685140,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069425622;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425622;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069425622;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069425622;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}LastAccessesURI|s:22:\"/content/view/full/106\";'),('6b757a80dcd2886681c0a2dc420526f6',1069946612,'eZUserInfoCache_Timestamp|i:1068554749;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1068554749;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069687395;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:21:\"/content/view/full/82\";canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1068554770;canInstantiateClasses|i:1;Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassList|a:10:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"19\";s:4:\"name\";s:13:\"Feedback form\";}}Preferences-advanced_menu|s:2:\"on\";FromGroupID|s:1:\"1\";classesCachedForUser|s:2:\"14\";eZUserDiscountRulesTimestamp|i:1068818589;eZUserDiscountRules14|a:0:{}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}'),('466365afedb7f98da7bedc935dd48b34',1069685790,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069426589;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('04ff4e634654cbd7bf5a22271859ac38',1069686378,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069427178;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('13df25a8ff5079b14cb4a02a9f3f59a7',1069686379,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069427178;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069427179;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069427179;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"375\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"376\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:1:{i:376;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"296\";s:9:\"policy_id\";s:3:\"376\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:296;a:4:{i:0;a:3:{s:2:\"id\";s:3:\"569\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"572\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:2:\"12\";}i:2;a:3:{s:2:\"id\";s:3:\"570\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"2\";}i:3;a:3:{s:2:\"id\";s:3:\"571\";s:13:\"limitation_id\";s:3:\"296\";s:5:\"value\";s:1:\"5\";}}}');
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,NULL),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,NULL),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,NULL),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,NULL),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,NULL),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,NULL),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,NULL),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,NULL),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,NULL),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0),(37,'news/off_topic','c77d3081eac3bee15b0213bcc89b369b','content/view/full/57',1,0,0),(36,'news/business_news','bde42888705c25806fbe02b8570d055d','content/view/full/56',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,99,0),(38,'news/reports_','ac624940baa3e037e0467bf2db2743cb','content/view/full/58',1,39,0),(39,'news/reports','f3cbeafbd5dbf7477a9a803d47d4dcbb','content/view/full/58',1,0,0),(40,'news/staff_news','c50e4a6eb10a499c098857026282ceb4','content/view/full/59',1,0,0),(97,'information','bb3ccd5881d651448ded1dac904054ac','content/view/full/108',1,0,0),(98,'information/about','7da2d46d560bcac31f00b99089e5f17e','content/view/full/109',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,0,0),(100,'contact_us','53a2c328fefc1efd85d75137a9d833ab','content/view/full/110',1,0,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,99,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(62,'news/business_news/ez_systems_reporting_live_from_munich','ddb9dceff37417877c5a030d5ca3e5b5','content/view/full/81',1,0,0),(63,'news/business_news/ez_publish_322_release','2fd7cd9bd8dc7eaa376187692cf64cdc','content/view/full/82',1,0,0),(64,'news/staff_news/mr_xxx_joined_us','6755615af39b3f3a145fd2a57a37809d','content/view/full/83',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(74,'users/editors/john_doe','470ba5117b9390b819f7c2519c0a6092','content/view/full/91',1,0,0),(75,'users/editors/vid_la','73f7efbac10f9f69aa4f7b19c97cfb16','content/view/full/92',1,0,0),(95,'products','86024cad1e83101d97359d7351051156','content/view/full/106',1,0,0),(96,'services','10cd395cf71c18328c863c08e78f3fd0','content/view/full/107',1,0,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0);
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
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(40,'test','test@test.com',2,'be778b473235e210cc577056226536a4'),(107,'john','doe@ez.no',2,'e82dc887aa749d7bc91b9bc489e61968'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(111,'vidla','vl@ez.no',2,'5289e8d223b023d527c47d58da538068'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d');
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
INSERT INTO ezuser_role VALUES (29,1,10),(25,2,12),(32,24,13),(28,1,11),(33,24,111),(34,1,13);
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
INSERT INTO ezuser_setting VALUES (10,1,1000),(14,1,10),(23,1,0),(40,1,0),(107,1,0),(108,1,0),(109,1,0),(111,1,0),(130,1,0);
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

