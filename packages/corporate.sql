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
INSERT INTO ezcontentbrowserecent VALUES (35,111,99,1067006746,'foo bar corp'),(46,14,107,1069757729,'Services');
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(187,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(188,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(185,1,19,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1),(181,0,19,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(182,0,19,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(183,0,19,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1),(185,0,19,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1),(184,0,19,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1),(182,1,19,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(184,1,19,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1),(181,1,19,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(183,1,19,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1);
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Corporate',4,0,1033917596,1069762950,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',8,0,1066384365,1067950307,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',9,0,1066388816,1067950326,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,4,1,'News',1,0,1066398020,1066398020,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'Corporate',62,0,1066643397,1069840811,1,''),(58,14,4,1,'Business news',1,0,1066729196,1066729196,1,''),(59,14,4,1,'Off topic',1,0,1066729211,1066729211,1,''),(60,14,4,1,'Reports',2,0,1066729226,1066729241,1,''),(61,14,4,1,'Staff news',1,0,1066729258,1066729258,1,''),(135,14,1,1,'General info',2,0,1067936571,1069757266,1,''),(136,14,1,10,'About',5,0,1067937053,1069757111,1,''),(137,14,1,19,'Contact us',4,0,1068027382,1069761690,1,''),(138,14,4,2,'New website',1,0,1069755162,1069755162,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(144,14,1,10,'Support',1,0,1069757581,1069757581,1,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(142,14,1,10,'Career',1,0,1069757199,1069757199,1,''),(143,14,1,10,'Shop info',1,0,1069757424,1069757424,1,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(88,14,2,4,'New User',1,0,0,0,0,''),(140,14,1,10,'PublishABC',1,0,1069756410,1069756410,1,''),(139,14,1,10,'Top 100 set',1,0,1069756326,1069756326,1,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(92,14,4,2,'Live from Top fair 2003',6,0,1066828821,1069755437,1,''),(94,14,4,2,'Mr Smith joined us',3,0,1066829047,1069755309,1,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(145,14,1,10,'Development',1,0,1069757729,1069757729,1,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(133,14,1,1,'Products',1,0,1067872500,1067872500,1,''),(134,14,1,1,'Services',1,0,1067872529,1067872529,1,''),(115,14,11,14,'Cache',3,0,1066991725,1067950265,1,''),(116,14,11,14,'URL translator',2,0,1066992054,1067950343,1,''),(117,14,4,2,'New Article',1,0,0,0,0,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'Root folder',NULL,NULL,0,0,'','ezstring'),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',1,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',1,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',1,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',1,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(251,'eng-GB',4,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',4,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',4,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(157,'eng-GB',1,58,4,'Business news',0,0,0,0,'business news','ezstring'),(158,'eng-GB',1,58,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(159,'eng-GB',1,59,4,'Off topic',0,0,0,0,'off topic','ezstring'),(160,'eng-GB',1,59,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(161,'eng-GB',1,60,4,'Reports ',0,0,0,0,'reports','ezstring'),(162,'eng-GB',1,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(161,'eng-GB',2,60,4,'Reports',0,0,0,0,'reports','ezstring'),(162,'eng-GB',2,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(163,'eng-GB',1,61,4,'Staff news',0,0,0,0,'staff news','ezstring'),(164,'eng-GB',1,61,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(251,'eng-GB',6,92,1,'Live from Top fair 2003',0,0,0,0,'live from top fair 2003','ezstring'),(151,'eng-GB',59,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',59,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(430,'eng-GB',2,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/storage/images/information/about/430-2-eng-GB\"\n         url=\"var/storage/images/information/about/430-2-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"430\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(254,'eng-GB',5,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_systems_reporting_live_from_munich.\"\n         suffix=\"\"\n         basename=\"ez_systems_reporting_live_from_munich\"\n         dirpath=\"var/corporate/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-5-eng-GB\"\n         url=\"var/corporate/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-5-eng-GB/ez_systems_reporting_live_from_munich.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"254\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(254,'eng-GB',4,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_systems_reporting_live_from_munich.\"\n         suffix=\"\"\n         basename=\"ez_systems_reporting_live_from_munich\"\n         dirpath=\"var/intranet/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-4-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-4-eng-GB/ez_systems_reporting_live_from_munich.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(255,'eng-GB',4,92,123,'',0,0,0,0,'','ezboolean'),(276,'eng-GB',4,92,177,'',0,0,0,0,'','ezinteger'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(150,'eng-GB',60,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(153,'eng-GB',59,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',59,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',59,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',59,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(441,'eng-GB',1,137,181,'Contact us',0,0,0,0,'contact us','ezstring'),(442,'eng-GB',1,137,182,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Fill in the form below if you have any</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(443,'eng-GB',1,137,183,'',0,0,0,0,'','ezstring'),(444,'eng-GB',1,137,184,'',0,0,0,0,'','eztext'),(445,'eng-GB',1,137,185,'',0,0,0,0,'','ezstring'),(441,'eng-GB',2,137,181,'Contact us',0,0,0,0,'contact us','ezstring'),(442,'eng-GB',2,137,182,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Fill in the form below if you have any feedback. Please remember to fill in your e-mail address.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(443,'eng-GB',2,137,183,'',0,0,0,0,'','ezstring'),(445,'eng-GB',2,137,185,'',0,0,0,0,'','ezstring'),(444,'eng-GB',2,137,184,'',0,0,0,0,'','eztext'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',53,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"51\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-53-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(251,'eng-GB',1,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',1,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',1,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small. </paragraph>\n  <paragraph>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',1,92,122,'',0,0,0,0,'','ezimage'),(255,'eng-GB',1,92,123,'',0,0,0,0,'','ezboolean'),(261,'eng-GB',1,94,1,'Mr xxx joined us',0,0,0,0,'mr xxx joined us','ezstring'),(262,'eng-GB',1,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We hired a new employee who is from --</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(263,'eng-GB',1,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>His name is xxx.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(264,'eng-GB',1,94,122,'',0,0,0,0,'','ezimage'),(265,'eng-GB',1,94,123,'',0,0,0,0,'','ezboolean'),(251,'eng-GB',2,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',2,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',2,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.     \n    <object id=\"95\"\n            align=\"right\"\n            size=\"medium\" />\n  </paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things. </line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',2,92,122,'',0,0,0,0,'','ezimage'),(255,'eng-GB',2,92,123,'',0,0,0,0,'','ezboolean'),(255,'eng-GB',5,92,123,'',0,0,0,0,'','ezboolean'),(276,'eng-GB',5,92,177,'',0,0,0,0,'','ezinteger'),(153,'eng-GB',58,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',58,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',58,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',58,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(468,'eng-GB',58,56,188,'Copyright &copy; eZ Publish 2000-2003',0,0,0,0,'copyright &copy; ez publish 2000-2003','ezstring'),(275,'eng-GB',1,92,177,'',0,0,0,0,'','ezinteger'),(276,'eng-GB',2,92,177,'',0,0,0,0,'','ezinteger'),(278,'eng-GB',1,94,177,'',0,0,0,0,'','ezinteger'),(251,'eng-GB',3,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',3,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',3,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.     \n    <object id=\"95\"\n            size=\"medium\"\n            align=\"right\" />\n  </paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',3,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(255,'eng-GB',3,92,123,'',0,0,0,0,'','ezboolean'),(276,'eng-GB',3,92,177,'',95,0,0,95,'','ezinteger'),(429,'eng-GB',2,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur eget mi quis mi porta suscipit. Maecenas elit. Etiam congue dictum libero. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras vitae libero ac lorem auctor sodales. Phasellus vitae lorem. Nullam sodales tristique lectus. Vivamus ultrices nunc a augue. Sed dignissim, ligula sed dictum consectetuer, quam leo auctor ipsum, a consequat neque diam eget justo. Ut posuere aliquam lectus. Nulla non massa vel massa ultricies vulputate. Vestibulum neque erat, interdum vel, rutrum ut, pharetra at, urna. Fusce eleifend dictum justo. Curabitur nonummy sodales orci. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. </paragraph>\n  <paragraph>Aliquam tincidunt, sem vitae porta adipiscing, odio mauris egestas augue, sit amet pellentesque velit arcu ut sapien. Suspendisse consequat pellentesque nibh. Nunc sed eros. Integer mi arcu, facilisis non, dictum quis, tincidunt id, metus. Aliquam vestibulum. Donec vitae leo sed nunc lobortis aliquam. Quisque sed elit. Fusce felis. Fusce ullamcorper mauris in ipsum. Nulla lacus mauris, porta id, mattis vitae, vehicula at, augue. Curabitur consequat, urna ut placerat elementum, velit augue aliquet orci, sed sollicitudin tortor quam sit amet neque. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(428,'eng-GB',2,136,140,'About',0,0,0,0,'about','ezstring'),(261,'eng-GB',2,94,1,'Mr xxx joined us',0,0,0,0,'mr xxx joined us','ezstring'),(262,'eng-GB',2,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>We hired a new employee who is from --</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(263,'eng-GB',2,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>His name is xxx.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(264,'eng-GB',2,94,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(265,'eng-GB',2,94,123,'',0,0,0,0,'','ezboolean'),(278,'eng-GB',2,94,177,'',101,0,0,101,'','ezinteger'),(428,'eng-GB',4,136,140,'About',0,0,0,0,'about','ezstring'),(429,'eng-GB',4,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n  <paragraph>My company is located in Skien, Norway with 223 employees. My company was founded in May 1973, in Skien, Norway,</paragraph>\n  <paragraph>Corporate Vision</paragraph>\n  <paragraph>&quot;We shall be an open minded, dedicated team helping people and businesses around the world to share information and knowledge&quot;.</paragraph>\n  <paragraph>\n    <line>Corporate Values</line>\n    <line>Open - We shall always meet the world with an open mind and an open heart, always welcoming other people, ideas and knowledge.</line>\n  </paragraph>\n  <paragraph>Sharing - We shall share our information, ideas and knowledge and pull together as a team, both internally and together with the community. Together we will accomplish great things.</paragraph>\n  <paragraph>Innovative - We shall be innovative people creating innovative solutions.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(443,'eng-GB',3,137,183,'',0,0,0,0,'','ezstring'),(445,'eng-GB',3,137,185,'',0,0,0,0,'','ezstring'),(444,'eng-GB',3,137,184,'',0,0,0,0,'','eztext'),(1,'eng-GB',3,1,4,'Corporate',0,0,0,0,'corporate','ezstring'),(2,'eng-GB',3,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This folder contains some information about...</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(476,'eng-GB',1,139,140,'Top 100 set',0,0,0,0,'top 100 set','ezstring'),(477,'eng-GB',1,139,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A collection of music from the year 2003. The best of the best. All top of the charts from Top 100. </paragraph>\n  <paragraph>Mona will be smarting from the lacklustre chart position of her new album &apos;Up and Go&apos;. It&apos;s come in at No. 234 when surely it should have snagged the top spot. Fellow babe July will be smarting too, with her new CD manages a poor No. 343. But for Tim Tim, whose new album &apos;InOn&apos; doesn&apos;t even manage to scrape into the top 20. The once mighty seem fragile. Meanwhile someone who&apos;s reputation has been seeming really fragile is Joap Jackson, and he romps to No. 1.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(478,'eng-GB',1,139,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"top_100_set.\"\n         suffix=\"\"\n         basename=\"top_100_set\"\n         dirpath=\"var/corporate/storage/images/products/top_100_set/478-1-eng-GB\"\n         url=\"var/corporate/storage/images/products/top_100_set/478-1-eng-GB/top_100_set.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069756112\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(255,'eng-GB',6,92,123,'',0,0,0,0,'','ezboolean'),(276,'eng-GB',6,92,177,'',0,0,0,0,'','ezinteger'),(254,'eng-GB',6,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"live_from_top_fair_2003.\"\n         suffix=\"\"\n         basename=\"live_from_top_fair_2003\"\n         dirpath=\"var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB\"\n         url=\"var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB/live_from_top_fair_2003.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"254\"\n            attribute_version=\"5\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(441,'eng-GB',3,137,181,'Contact us',0,0,0,0,'contact us','ezstring'),(442,'eng-GB',3,137,182,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Fill in the form below if you have any feedback. Please remember to fill in your e-mail address.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(150,'eng-GB',61,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',61,56,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(1,'eng-GB',2,1,4,'Corporate',0,0,0,0,'corporate','ezstring'),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(422,'eng-GB',1,133,4,'Products',0,0,0,0,'products','ezstring'),(423,'eng-GB',1,133,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find information about our products.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(424,'eng-GB',1,134,4,'Services',0,0,0,0,'services','ezstring'),(425,'eng-GB',1,134,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about our services.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(426,'eng-GB',1,135,4,'Information',0,0,0,0,'information','ezstring'),(427,'eng-GB',1,135,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Company information.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(428,'eng-GB',1,136,140,'About',0,0,0,0,'about','ezstring'),(429,'eng-GB',1,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(430,'eng-GB',1,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/storage/images/information/about/430-1-eng-GB\"\n         url=\"var/storage/images/information/about/430-1-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(153,'eng-GB',53,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',53,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',53,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(150,'eng-GB',51,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',60,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',60,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(481,'eng-GB',1,140,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"publishabc.\"\n         suffix=\"\"\n         basename=\"publishabc\"\n         dirpath=\"var/corporate/storage/images/products/publishabc/481-1-eng-GB\"\n         url=\"var/corporate/storage/images/products/publishabc/481-1-eng-GB/publishabc.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069756339\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(479,'eng-GB',1,140,140,'PublishABC',0,0,0,0,'publishabc','ezstring'),(480,'eng-GB',1,140,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>PublishABC is an open source content management system (CMS) and development framework.</paragraph>\n  <paragraph>With advanced functionality for e-commerce sites, news-sites, forums, picture galleries, intranets and much more, you can build your dynamic websites fast and reliable. PublishABC is a very flexible system for everyone that wants to share their information on the web.</paragraph>\n  <paragraph>With PublishABC you can easily create, edit and publish all sorts of content and the day-to-day work can easily be done by non-technical persons.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',6,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Four crew members are on-site from the 20th to the 24th reporting live from the hall. The following text contains a live report from the fair.</paragraph>\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</paragraph>\n  <paragraph>Speaking of community, we&apos;re happy that the community show up. It is always interesting and inspiring to meet face to face and to discuss various topics. We certainly hope that more community people will show up during the rest of the week.</paragraph>\n  <paragraph>Anyway, we were talking about the benefits of open and free software. As mentioned, some people still don&apos;t get it; however, when explained, we&apos;re met by replies such as:</paragraph>\n  <paragraph>&quot;Amazing!&quot; - big smile...</paragraph>\n  <paragraph>&quot;I would have to pay a lot of money for this feature from company...&quot;</paragraph>\n  <paragraph>- from a guy who came to us from one of the neighboring stands (right after watching a presentation there).</paragraph>\n  <paragraph>Some companies are just interested in talking to potential customers who are willing to spend millions on rigid solutions. This is not our policy. We&apos;re very flexible and eager to talk to a wide range of people. If you have the chance visit the fair, feel free to stop by. Our stand is open and prepared for everyone. Anybody can sit down together with our representatives and receive an on-site, hands-on demonstration of the system.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(252,'eng-GB',6,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This week, some members of the crew are reporting live from Hall A, attending &quot;Top fair 2003&quot;. Top fair 2003 is an international trade fair for Information Technology and Telecommunications. The trade fair is held for the 5th time.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',51,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"47\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-51-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',51,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',59,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"58\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069414397\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(461,'eng-GB',60,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(468,'eng-GB',60,56,188,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(437,'eng-GB',60,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(150,'eng-GB',62,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(153,'eng-GB',54,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',54,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',54,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(464,'eng-GB',51,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(465,'eng-GB',53,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(262,'eng-GB',3,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Mr Smith started today at our firm. He will be in charge of the computer matrix. We hired him from his previous workplace at Nemos place.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(261,'eng-GB',3,94,1,'Mr Smith joined us',0,0,0,0,'mr smith joined us','ezstring'),(265,'eng-GB',3,94,123,'',1,0,0,1,'','ezboolean'),(278,'eng-GB',3,94,177,'',0,0,0,0,'','ezinteger'),(264,'eng-GB',3,94,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"mr_smith_joined_us.\"\n         suffix=\"\"\n         basename=\"mr_smith_joined_us\"\n         dirpath=\"var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB\"\n         url=\"var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB/mr_smith_joined_us.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',55,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"54\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-55-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',54,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',54,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',54,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB/corporate.gif\"\n         original_filename=\"db-logo-mag04.gif\"\n         mime_type=\"original\"\n         width=\"224\"\n         height=\"72\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"53\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"224\"\n         height=\"72\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"64\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-54-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"180\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',55,56,160,'corporate_blue',0,0,0,0,'corporate_blue','ezpackage'),(154,'eng-GB',55,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',55,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(466,'eng-GB',54,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(467,'eng-GB',55,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(468,'eng-GB',56,56,188,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(263,'eng-GB',3,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>His name is Mr Smith and I hope you all welcome him into our ranks.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',5,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(468,'eng-GB',59,56,188,'Copyright &copy; eZ Publish 1999-2003',0,0,0,0,'copyright &copy; ez publish 1999-2003','ezstring'),(251,'eng-GB',5,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',5,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(150,'eng-GB',53,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',53,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(151,'eng-GB',60,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',60,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"59\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069676156\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',62,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate.gif\"\n         original_filename=\"corporate.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069839369\">\n  <original attribute_id=\"152\"\n            attribute_version=\"61\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069839370\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069839370\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069844204\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',58,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(151,'eng-GB',58,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',58,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"56\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069412772\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',56,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(437,'eng-GB',56,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(474,'eng-GB',1,138,123,'',1,0,0,1,'','ezboolean'),(154,'eng-GB',56,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(153,'eng-GB',56,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-56-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(151,'eng-GB',55,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',55,56,157,'Corporate',0,0,0,0,'','ezinisetting'),(153,'eng-GB',51,56,160,'corporate_green',0,0,0,0,'corporate_green','ezpackage'),(154,'eng-GB',51,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',51,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',56,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(460,'eng-GB',55,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(470,'eng-GB',1,138,1,'New website',0,0,0,0,'new website','ezstring'),(471,'eng-GB',1,138,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We have now released our new website. I hope that it is easier to find iformation about the company and what we offer from now on.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(472,'eng-GB',1,138,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The feedback we have gotten so far indicates this. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(473,'eng-GB',1,138,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_website.\"\n         suffix=\"\"\n         basename=\"new_website\"\n         dirpath=\"var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB\"\n         url=\"var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB/new_website.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069755091\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(430,'eng-GB',3,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/corporate/storage/images/information/about/430-3-eng-GB\"\n         url=\"var/corporate/storage/images/information/about/430-3-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"430\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',62,56,160,'corporate_blue',0,0,0,0,'corporate_blue','ezpackage'),(154,'eng-GB',62,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(428,'eng-GB',3,136,140,'About',0,0,0,0,'about','ezstring'),(429,'eng-GB',3,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n  <paragraph>My company is located in Skien, Norway with 223 employees. My company was founded in May 1973, in Skien, Norway,</paragraph>\n  <paragraph>Corporate Vision </paragraph>\n  <paragraph>&quot;We shall be an open minded, dedicated team helping people and businesses around the world to share information and knowledge&quot;. </paragraph>\n  <paragraph>\n    <line>Corporate Values</line>\n    <line>Open - We shall always meet the world with an open mind and an open heart, always welcoming other people, ideas and knowledge. </line>\n  </paragraph>\n  <paragraph>Sharing - We shall share our information, ideas and knowledge and pull together as a team, both internally and together with the community. Together we will accomplish great things. </paragraph>\n  <paragraph>Innovative - We shall be innovative people creating innovative solutions.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(475,'eng-GB',1,138,177,'',0,0,0,0,'','ezinteger'),(151,'eng-GB',62,56,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(457,'eng-GB',51,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(458,'eng-GB',53,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(459,'eng-GB',54,56,187,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(430,'eng-GB',4,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/corporate/storage/images/about/430-4-eng-GB\"\n         url=\"var/corporate/storage/images/about/430-4-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"430\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(428,'eng-GB',5,136,140,'About',0,0,0,0,'about','ezstring'),(429,'eng-GB',5,136,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about your company.</paragraph>\n  <paragraph>My company is located in Skien, Norway with 223 employees. My company was founded in May 1973, in Skien, Norway,</paragraph>\n  <paragraph>Corporate Vision</paragraph>\n  <paragraph>&quot;We shall be an open minded, dedicated team helping people and businesses around the world to share information and knowledge&quot;.</paragraph>\n  <paragraph>\n    <line>Corporate Values</line>\n    <line>Open - We shall always meet the world with an open mind and an open heart, always welcoming other people, ideas and knowledge.</line>\n  </paragraph>\n  <paragraph>Sharing - We shall share our information, ideas and knowledge and pull together as a team, both internally and together with the community. Together we will accomplish great things.</paragraph>\n  <paragraph>Innovative - We shall be innovative people creating innovative solutions.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(430,'eng-GB',5,136,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about.\"\n         suffix=\"\"\n         basename=\"about\"\n         dirpath=\"var/corporate/storage/images/information/about/430-5-eng-GB\"\n         url=\"var/corporate/storage/images/information/about/430-5-eng-GB/about.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"430\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(484,'eng-GB',1,142,140,'Career',0,0,0,0,'career','ezstring'),(485,'eng-GB',1,142,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We are now hiring the following</paragraph>\n  <paragraph>System developers </paragraph>\n  <paragraph>You will be part of the eZ development crew, developing our products. You will also be part of customer projects either as a project leader and/or developer. Very good programming skills are required. You must know object oriented programming and design using C++ and PHP. You should be familiar with UML, SQL, XML, XHTML, SOAP/XML-RPC and Linux/Unix. Experience with the Qt Toolkit is a plus. Experience from open source projects is a plus. Fresh graduates may also apply, if you have very good programming knowledge. </paragraph>\n  <paragraph>\n    <line>Applications will be accepted continually.</line>\n    <line>Place of work: Skien, Norway.</line>\n    <line>Conditions: Depending on qualifications.</line>\n    <line>Very good English skills are required. Other languages is a plus.</line>\n  </paragraph>\n  <paragraph>\n    <line>Questions and applications with CV&apos;s are to be sent by e-mail to:</line>\n    <line>The Boss</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(486,'eng-GB',1,142,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"career.\"\n         suffix=\"\"\n         basename=\"career\"\n         dirpath=\"var/corporate/storage/images/information/career/486-1-eng-GB\"\n         url=\"var/corporate/storage/images/information/career/486-1-eng-GB/career.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757141\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(426,'eng-GB',2,135,4,'General info',0,0,0,0,'general info','ezstring'),(427,'eng-GB',2,135,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find information about this company.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',4,1,4,'Corporate',0,0,0,0,'corporate','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to the website of MyCompany. Here you can read about our company, our products and services. Take a tour through our digitised archive, and find out more about the comapny and what we offer. </paragraph>\n  <paragraph>Our mission is to keep our customers in touch with the latest updates, releases and products.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(487,'eng-GB',1,143,140,'Shop info',0,0,0,0,'shop info','ezstring'),(488,'eng-GB',1,143,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We are committed to your satisfaction. We will do everything we can to make a present or potential customer a satisfied customer. </paragraph>\n  <paragraph>On these pages we will outline our terms &amp; conditions and your rights and privacy as a customer.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(489,'eng-GB',1,143,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"shop_info.\"\n         suffix=\"\"\n         basename=\"shop_info\"\n         dirpath=\"var/corporate/storage/images/general_info/shop_info/489-1-eng-GB\"\n         url=\"var/corporate/storage/images/general_info/shop_info/489-1-eng-GB/shop_info.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757397\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(493,'eng-GB',1,145,140,'Development',0,0,0,0,'development','ezstring'),(494,'eng-GB',1,145,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Use the crew for developments and enhancements </paragraph>\n  <paragraph>Hire a guy to help you with your solution. We and our friends have highly skilled developers ready to advice you. Consulting ranges from feature requests, installation help, upgrade help, migration, integration and solutions. </paragraph>\n  <paragraph>Often we help with installation, migration, integration, programming etc. We can also deliver a turn key web solution based on PublishABC. Let us know what we can do for you. Our standard hourly rate is $ 129 and our minimum asking price for projects is $ 2344. </paragraph>\n  <paragraph>Contact us if there is something we can do for you.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(495,'eng-GB',1,145,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"development.\"\n         suffix=\"\"\n         basename=\"development\"\n         dirpath=\"var/corporate/storage/images/services/development/495-1-eng-GB\"\n         url=\"var/corporate/storage/images/services/development/495-1-eng-GB/development.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757596\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(492,'eng-GB',1,144,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"support.\"\n         suffix=\"\"\n         basename=\"support\"\n         dirpath=\"var/corporate/storage/images/services/support/492-1-eng-GB\"\n         url=\"var/corporate/storage/images/services/support/492-1-eng-GB/support.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069757493\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(490,'eng-GB',1,144,140,'Support',0,0,0,0,'support','ezstring'),(491,'eng-GB',1,144,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>&quot; Use the crew for support - first hand information&quot; </paragraph>\n  <paragraph>To guarantee our customers the best possible result we offer a support program. The professionals are ready to help you with your problem. </paragraph>\n  <paragraph>\n    <line>What you will get with support</line>\n    <line>The support will cover answers by email and phone. If you need help to configure or develop features, we can help you doing that directly on your server, or as a new feature to a distribution on PublishABC. We will also be able to give advise on how to solve problems with development in PublishABC, if you want to do most of the work yourself.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(441,'eng-GB',4,137,181,'Contact us',0,0,0,0,'contact us','ezstring'),(442,'eng-GB',4,137,182,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Fill in the form below if you have any feedback. Please remember to fill in your e-mail address.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(443,'eng-GB',4,137,183,'',0,0,0,0,'','ezstring'),(445,'eng-GB',4,137,185,'',0,0,0,0,'','ezstring'),(444,'eng-GB',4,137,184,'',0,0,0,0,'','eztext'),(152,'eng-GB',61,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"corporate.gif\"\n         suffix=\"gif\"\n         basename=\"corporate\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate.gif\"\n         original_filename=\"corporate.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069839369\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"corporate_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069839370\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"corporate_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069839370\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"corporate_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB\"\n         url=\"var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069839392\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',61,56,160,'corporate_blue',0,0,0,0,'corporate_blue','ezpackage'),(154,'eng-GB',61,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',61,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',61,56,187,'ez.no',0,0,0,0,'','ezinisetting'),(468,'eng-GB',61,56,188,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(437,'eng-GB',62,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',62,56,187,'ez.no',0,0,0,0,'','ezinisetting'),(468,'eng-GB',62,56,188,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring');
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(58,'Business news',1,'eng-GB','eng-GB'),(59,'Off topic',1,'eng-GB','eng-GB'),(60,'Reports',1,'eng-GB','eng-GB'),(60,'Reports',2,'eng-GB','eng-GB'),(61,'Staff news',1,'eng-GB','eng-GB'),(137,'Contact us',2,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(135,'Information',1,'eng-GB','eng-GB'),(136,'About',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(136,'About',2,'eng-GB','eng-GB'),(134,'Services',1,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',1,'eng-GB','eng-GB'),(94,'Mr xxx joined us',1,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',2,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(133,'Products',1,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',3,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',4,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(94,'Mr xxx joined us',2,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(137,'Contact us',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(56,'Corporate',43,'eng-GB','eng-GB'),(56,'Corporate',44,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',5,'eng-GB','eng-GB'),(56,'Corporate',45,'eng-GB','eng-GB'),(56,'Corporate',46,'eng-GB','eng-GB'),(56,'Corporate',48,'eng-GB','eng-GB'),(56,'Corporate',47,'eng-GB','eng-GB'),(56,'Corporate',51,'eng-GB','eng-GB'),(56,'Corporate',53,'eng-GB','eng-GB'),(56,'Corporate',54,'eng-GB','eng-GB'),(56,'Corporate',55,'eng-GB','eng-GB'),(56,'Corporate',56,'eng-GB','eng-GB'),(56,'Corporate',58,'eng-GB','eng-GB'),(56,'Corporate',59,'eng-GB','eng-GB'),(56,'Corporate',60,'eng-GB','eng-GB'),(56,'Corporate',57,'eng-GB','eng-GB'),(136,'About',3,'eng-GB','eng-GB'),(138,'New website',1,'eng-GB','eng-GB'),(94,'Mr Smith joined us',3,'eng-GB','eng-GB'),(92,'Live from Top fair 2003',6,'eng-GB','eng-GB'),(139,'Top 100 set',1,'eng-GB','eng-GB'),(140,'PublishABC',1,'eng-GB','eng-GB'),(1,'Corporate',4,'eng-GB','eng-GB'),(137,'Contact us',3,'eng-GB','eng-GB'),(1,'Corporate',3,'eng-GB','eng-GB'),(136,'About',4,'eng-GB','eng-GB'),(136,'About',5,'eng-GB','eng-GB'),(142,'Career',1,'eng-GB','eng-GB'),(135,'General info',2,'eng-GB','eng-GB'),(56,'Corporate',62,'eng-GB','eng-GB'),(143,'Shop info',1,'eng-GB','eng-GB'),(56,'Corporate',61,'eng-GB','eng-GB'),(144,'Support',1,'eng-GB','eng-GB'),(145,'Development',1,'eng-GB','eng-GB'),(137,'Contact us',4,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,4,1,1,'/1/2/',8,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,8,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,9,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,1,1,2,'/1/2/50/',9,1,1,'news',50),(56,50,58,1,1,3,'/1/2/50/56/',9,1,0,'news/business_news',56),(54,48,56,62,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/corporate',54),(57,50,59,1,1,3,'/1/2/50/57/',9,1,0,'news/off_topic',57),(58,50,60,2,1,3,'/1/2/50/58/',9,1,0,'news/reports',58),(59,50,61,1,1,3,'/1/2/50/59/',9,1,0,'news/staff_news',59),(108,2,135,2,1,2,'/1/2/108/',9,1,4,'general_info',108),(109,108,136,5,1,3,'/1/2/108/109/',9,1,0,'general_info/about',109),(110,2,137,4,1,2,'/1/2/110/',9,1,5,'contact_us',110),(111,57,138,1,1,4,'/1/2/50/57/111/',9,1,0,'news/off_topic/new_website',111),(112,58,92,6,1,4,'/1/2/50/58/112/',9,1,0,'news/reports/live_from_top_fair_2003',81),(114,106,140,1,1,3,'/1/2/106/114/',9,1,0,'products/publishabc',114),(116,108,142,1,1,3,'/1/2/108/116/',9,1,0,'general_info/career',116),(117,108,143,1,1,3,'/1/2/108/117/',9,1,0,'general_info/shop_info',117),(81,56,92,6,1,4,'/1/2/50/56/81/',9,1,0,'news/business_news/live_from_top_fair_2003',81),(113,106,139,1,1,3,'/1/2/106/113/',9,1,0,'products/top_100_set',113),(83,59,94,3,1,4,'/1/2/50/59/83/',9,1,0,'news/staff_news/mr_smith_joined_us',83),(118,107,144,1,1,3,'/1/2/107/118/',9,1,0,'services/support',118),(119,107,145,1,1,3,'/1/2/107/119/',9,1,0,'services/development',119),(106,2,133,1,1,2,'/1/2/106/',9,1,2,'products',106),(107,2,134,1,1,2,'/1/2/107/',9,1,3,'services',107),(95,46,115,3,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,2,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96);
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
INSERT INTO ezcontentobject_version VALUES (1,1,14,1,1033919080,1033919080,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(480,45,14,1,1066388718,1066388815,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(734,56,14,61,1069839217,1069839369,3,0,0),(718,139,14,1,1069756109,1069756326,1,0,0),(735,56,14,62,1069839663,1069840810,1,0,0),(490,49,14,1,1066398007,1066398020,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(622,92,14,4,1066998064,1066998231,3,0,0),(717,92,14,6,1069755358,1069755437,1,0,0),(708,56,14,56,1068222495,1068222571,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(722,1,14,3,1069756755,1069756780,3,1,0),(520,58,14,1,1066729186,1066729195,1,0,0),(521,59,14,1,1066729202,1066729210,1,0,0),(522,60,14,1,1066729218,1066729226,3,0,0),(523,60,14,2,1066729234,1066729241,1,0,0),(524,61,14,1,1066729249,1066729258,1,0,0),(730,144,14,1,1069757491,1069757581,1,0,0),(676,136,14,1,1067937002,1067937053,3,0,0),(719,140,14,1,1069756337,1069756410,1,0,0),(683,45,14,9,1067950316,1067950326,1,0,0),(682,43,14,8,1067950294,1067950307,1,0,0),(681,115,14,3,1067950253,1067950265,1,0,0),(711,56,14,58,1069412658,1069412691,3,0,0),(675,135,14,1,1067936556,1067936571,3,0,0),(707,56,14,55,1068221830,1068221860,3,0,0),(721,137,14,3,1069756602,1069756628,3,0,0),(714,136,14,3,1069754717,1069754944,3,0,0),(663,92,14,5,1067343889,1068213282,3,0,0),(706,56,14,54,1068221131,1068221152,3,0,0),(716,94,14,3,1069755194,1069755309,1,0,0),(723,136,14,4,1069757025,1069757056,3,0,0),(725,142,14,1,1069757139,1069757199,1,0,0),(724,136,14,5,1069757094,1069757111,1,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(712,56,14,59,1069414187,1069414256,3,0,0),(705,56,14,53,1068221035,1068221067,3,0,0),(728,143,14,1,1069757395,1069757424,1,0,0),(726,135,14,2,1069757215,1069757265,1,0,0),(691,137,14,2,1068027475,1068027496,3,0,0),(684,116,14,2,1067950335,1067950343,1,0,0),(690,137,14,1,1068027166,1068027382,3,0,0),(573,92,14,1,1066828519,1066828821,3,0,0),(575,94,14,1,1066828947,1066829047,3,0,0),(576,92,14,2,1066832966,1066833112,3,0,0),(713,56,14,60,1069414771,1069414796,3,0,0),(674,134,14,1,1067872510,1067872528,1,0,0),(583,92,14,3,1066907449,1066907519,3,0,0),(733,1,14,4,1069762220,1069762950,1,1,0),(703,56,14,51,1068220879,1068220902,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(677,136,14,2,1067942731,1067942786,3,0,0),(590,94,14,2,1066910791,1066910828,3,0,0),(732,137,14,4,1069761671,1069761690,1,0,0),(672,1,14,2,1067871685,1067871717,3,1,0),(731,145,14,1,1069757594,1069757729,1,0,0),(673,133,14,1,1067872484,1067872500,1,0,0),(715,138,14,1,1069755089,1069755162,1,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0);
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
INSERT INTO ezimagefile VALUES (1,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate.gif'),(2,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_reference.gif'),(3,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_medium.gif'),(4,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-58-eng-GB/corporate_logo.gif'),(5,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate.gif'),(6,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_reference.gif'),(7,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_medium.gif'),(8,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-59-eng-GB/corporate_logo.gif'),(9,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate.gif'),(10,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_reference.gif'),(11,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_medium.gif'),(12,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-60-eng-GB/corporate_logo.gif'),(13,430,'var/corporate/storage/images/information/about/430-3-eng-GB/about.'),(14,473,'var/corporate/storage/images/news/off_topic/new_website/473-1-eng-GB/new_website.'),(15,264,'var/corporate/storage/images/news/staff_news/mr_smith_joined_us/264-3-eng-GB/mr_smith_joined_us.'),(16,254,'var/corporate/storage/images/news/business_news/live_from_top_fair_2003/254-6-eng-GB/live_from_top_fair_2003.'),(17,478,'var/corporate/storage/images/products/top_100_set/478-1-eng-GB/top_100_set.'),(18,481,'var/corporate/storage/images/products/publishabc/481-1-eng-GB/publishabc.'),(19,430,'var/corporate/storage/images/about/430-4-eng-GB/about.'),(20,430,'var/corporate/storage/images/information/about/430-5-eng-GB/about.'),(21,486,'var/corporate/storage/images/information/career/486-1-eng-GB/career.'),(22,489,'var/corporate/storage/images/general_info/shop_info/489-1-eng-GB/shop_info.'),(23,492,'var/corporate/storage/images/services/support/492-1-eng-GB/support.'),(24,495,'var/corporate/storage/images/services/development/495-1-eng-GB/development.'),(26,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate.gif'),(27,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_reference.gif'),(28,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_medium.gif'),(29,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-61-eng-GB/corporate_logo.gif'),(30,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate.gif'),(31,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_reference.gif'),(32,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_medium.gif'),(33,152,'var/corporate/storage/images/setup/look_and_feel/corporate/152-62-eng-GB/corporate_logo.gif');
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
INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(191,45,1,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(443,56,62,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(411,56,56,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(325,92,4,56,9,1,1,0,0),(230,58,1,50,9,1,1,0,0),(420,92,6,56,9,1,1,0,0),(410,56,55,48,9,1,1,0,0),(441,1,4,1,8,1,1,0,0),(426,137,3,115,9,1,1,2,0),(231,59,1,50,9,1,1,0,0),(232,60,1,50,9,1,1,0,0),(233,60,2,50,9,1,1,0,0),(234,61,1,50,9,1,1,0,0),(437,144,1,107,9,1,1,0,0),(378,135,1,2,9,1,1,0,0),(423,140,1,106,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(414,56,58,48,9,1,1,0,0),(377,134,1,2,9,1,1,0,0),(432,142,1,108,9,1,1,0,0),(417,136,3,108,9,1,1,0,0),(366,92,5,56,9,1,1,0,0),(409,56,54,48,9,1,1,0,0),(419,94,3,59,9,1,1,0,0),(442,56,61,48,9,1,1,0,0),(431,136,5,108,9,1,1,2,0),(433,135,2,2,9,1,1,0,0),(421,92,6,58,9,1,0,0,0),(321,45,6,46,9,1,1,0,0),(415,56,59,48,9,1,1,0,0),(408,56,53,48,9,1,1,0,0),(435,143,1,108,9,1,1,0,0),(429,136,4,2,9,1,1,108,0),(394,137,2,2,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(393,137,1,2,9,1,1,0,0),(279,92,1,56,9,1,1,0,0),(281,94,1,59,9,1,1,0,0),(282,92,2,56,9,1,1,0,0),(422,139,1,106,9,1,1,0,0),(416,56,60,48,9,1,1,0,0),(379,136,1,108,9,1,1,0,0),(288,92,3,56,9,1,1,0,0),(427,1,3,1,8,1,1,0,0),(335,45,8,46,9,1,1,0,0),(406,56,51,48,9,1,1,0,0),(380,136,2,108,9,1,1,0,0),(296,94,2,59,9,1,1,0,0),(440,137,4,2,9,1,1,115,0),(375,1,2,1,9,1,1,0,0),(438,145,1,107,9,1,1,0,0),(376,133,1,2,9,1,1,0,0),(418,138,1,57,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (194,0,'ezpublish',56,62,0,0,'','','',''),(193,0,'ezpublish',56,61,0,0,'','','',''),(192,0,'ezpublish',1,4,0,0,'','','',''),(191,0,'ezpublish',137,4,0,0,'','','',''),(190,0,'ezpublish',145,1,0,0,'','','',''),(189,0,'ezpublish',144,1,0,0,'','','',''),(188,0,'ezpublish',143,1,0,0,'','','',''),(187,0,'ezpublish',141,2,0,0,'','','',''),(186,0,'ezpublish',135,2,0,0,'','','',''),(185,0,'ezpublish',142,1,0,0,'','','',''),(184,0,'ezpublish',136,5,0,0,'','','',''),(183,0,'ezpublish',136,4,0,0,'','','',''),(182,0,'ezpublish',1,3,0,0,'','','',''),(181,0,'ezpublish',137,3,0,0,'','','',''),(180,0,'ezpublish',141,1,0,0,'','','',''),(179,0,'ezpublish',140,1,0,0,'','','',''),(178,0,'ezpublish',139,1,0,0,'','','',''),(177,0,'ezpublish',92,6,0,0,'','','',''),(176,0,'ezpublish',94,3,0,0,'','','',''),(175,0,'ezpublish',138,1,0,0,'','','',''),(174,0,'ezpublish',136,3,0,0,'','','',''),(173,0,'ezpublish',56,60,0,0,'','','',''),(172,0,'ezpublish',56,59,0,0,'','','',''),(171,0,'ezpublish',56,58,0,0,'','','',''),(170,0,'ezpublish',56,56,0,0,'','','',''),(169,0,'ezpublish',56,55,0,0,'','','',''),(168,0,'ezpublish',56,54,0,0,'','','',''),(167,0,'ezpublish',56,53,0,0,'','','',''),(166,0,'ezpublish',56,51,0,0,'','','',''),(165,0,'ezpublish',56,47,0,0,'','','',''),(164,0,'ezpublish',56,48,0,0,'','','',''),(163,0,'ezpublish',56,46,0,0,'','','',''),(162,0,'ezpublish',56,45,0,0,'','','',''),(161,0,'ezpublish',92,5,0,0,'','','',''),(160,0,'ezpublish',56,44,0,0,'','','',''),(159,0,'ezpublish',56,43,0,0,'','','','');
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(370,24,'create','content',''),(371,24,'create','content',''),(372,24,'create','content',''),(341,8,'read','content','*'),(369,24,'read','content','*'),(373,24,'create','content',''),(374,24,'edit','content',''),(380,1,'read','content',''),(379,1,'login','user','*');
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
INSERT INTO ezpolicy_limitation VALUES (289,371,'Class',0,'create','content'),(290,371,'Section',0,'create','content'),(288,370,'Section',0,'create','content'),(287,370,'Class',0,'create','content'),(291,372,'Class',0,'create','content'),(292,372,'Section',0,'create','content'),(293,373,'Class',0,'create','content'),(294,373,'Section',0,'create','content'),(295,374,'Class',0,'edit','content'),(300,380,'Class',0,'read','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (555,291,'12'),(554,291,'1'),(551,289,'16'),(550,288,'4'),(548,287,'13'),(549,287,'2'),(553,290,'5'),(552,289,'17'),(547,287,'1'),(556,292,'6'),(557,293,'6'),(558,293,'7'),(559,294,'7'),(560,295,'1'),(561,295,'2'),(562,295,'6'),(563,295,'7'),(564,295,'12'),(565,295,'13'),(566,295,'16'),(567,295,'17'),(568,295,'18'),(591,300,'12'),(590,300,'10'),(589,300,'5'),(588,300,'2'),(587,300,'1'),(592,300,'19');
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
INSERT INTO ezsearch_object_word_link VALUES (28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(2068,43,1050,0,2,1049,0,14,1066384365,11,155,'',0),(2067,43,1049,0,1,1048,1050,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(2066,43,1048,0,0,0,1049,14,1066384365,11,152,'',0),(2074,45,1052,0,5,1051,0,14,1066388816,11,155,'',0),(2073,45,1051,0,4,25,1052,14,1066388816,11,155,'',0),(2072,45,25,0,3,34,1051,14,1066388816,11,155,'',0),(2071,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(61,49,37,0,0,0,0,1,1066398020,4,4,'',0),(74,58,49,0,0,0,37,1,1066729196,4,4,'',0),(75,58,37,0,1,49,0,1,1066729196,4,4,'',0),(76,59,50,0,0,0,51,1,1066729211,4,4,'',0),(77,59,51,0,1,50,0,1,1066729211,4,4,'',0),(79,60,53,0,0,0,0,1,1066729226,4,4,'',0),(80,61,54,0,0,0,37,1,1066729258,4,4,'',0),(81,61,37,0,1,54,0,1,1066729258,4,4,'',0),(2866,92,1413,0,322,102,1414,2,1066828821,4,121,'',0),(2780,92,937,0,236,1124,1371,2,1066828821,4,121,'',0),(2802,92,1385,0,258,879,180,2,1066828821,4,121,'',0),(2801,92,879,0,257,1345,1385,2,1066828821,4,121,'',0),(2781,92,1371,0,237,937,1372,2,1066828821,4,121,'',0),(2782,92,1372,0,238,1371,1380,2,1066828821,4,121,'',0),(2783,92,1380,0,239,1372,75,2,1066828821,4,121,'',0),(2784,92,75,0,240,1380,1381,2,1066828821,4,121,'',0),(2785,92,1381,0,241,75,152,2,1066828821,4,121,'',0),(2786,92,152,0,242,1381,75,2,1066828821,4,121,'',0),(2787,92,75,0,243,152,1281,2,1066828821,4,121,'',0),(2788,92,1281,0,244,75,1382,2,1066828821,4,121,'',0),(2789,92,1382,0,245,1281,86,2,1066828821,4,121,'',0),(2790,92,86,0,246,1382,849,2,1066828821,4,121,'',0),(2791,92,849,0,247,86,1383,2,1066828821,4,121,'',0),(2792,92,1383,0,248,849,934,2,1066828821,4,121,'',0),(2793,92,934,0,249,1383,75,2,1066828821,4,121,'',0),(2794,92,75,0,250,934,1361,2,1066828821,4,121,'',0),(2795,92,1361,0,251,75,152,2,1066828821,4,121,'',0),(2796,92,152,0,252,1361,1134,2,1066828821,4,121,'',0),(2797,92,1134,0,253,152,33,2,1066828821,4,121,'',0),(2798,92,33,0,254,1134,1384,2,1066828821,4,121,'',0),(2799,92,1384,0,255,33,1345,2,1066828821,4,121,'',0),(2800,92,1345,0,256,1384,879,2,1066828821,4,121,'',0),(2865,92,102,0,321,1383,1413,2,1066828821,4,121,'',0),(2864,92,1383,0,320,183,102,2,1066828821,4,121,'',0),(2863,92,183,0,319,1412,1383,2,1066828821,4,121,'',0),(2862,92,1412,0,318,1355,183,2,1066828821,4,121,'',0),(2861,92,1355,0,317,1284,1412,2,1066828821,4,121,'',0),(2860,92,1284,0,316,1335,1355,2,1066828821,4,121,'',0),(2859,92,1335,0,315,180,1284,2,1066828821,4,121,'',0),(2858,92,180,0,314,1317,1335,2,1066828821,4,121,'',0),(2857,92,1317,0,313,1411,180,2,1066828821,4,121,'',0),(2856,92,1411,0,312,89,1317,2,1066828821,4,121,'',0),(2855,92,89,0,311,1410,1411,2,1066828821,4,121,'',0),(2849,92,75,0,305,152,1406,2,1066828821,4,121,'',0),(2850,92,1406,0,306,75,1407,2,1066828821,4,121,'',0),(2851,92,1407,0,307,1406,1408,2,1066828821,4,121,'',0),(2852,92,1408,0,308,1407,1409,2,1066828821,4,121,'',0),(2853,92,1409,0,309,1408,1410,2,1066828821,4,121,'',0),(2854,92,1410,0,310,1409,89,2,1066828821,4,121,'',0),(2848,92,152,0,304,1405,75,2,1066828821,4,121,'',0),(2847,92,1405,0,303,108,152,2,1066828821,4,121,'',0),(2846,92,108,0,302,221,1405,2,1066828821,4,121,'',0),(2845,92,221,0,301,102,108,2,1066828821,4,121,'',0),(2844,92,102,0,300,1404,221,2,1066828821,4,121,'',0),(2843,92,1404,0,299,1319,102,2,1066828821,4,121,'',0),(2842,92,1319,0,298,1403,1404,2,1066828821,4,121,'',0),(2841,92,1403,0,297,89,1319,2,1066828821,4,121,'',0),(2840,92,89,0,296,108,1403,2,1066828821,4,121,'',0),(2839,92,108,0,295,1395,89,2,1066828821,4,121,'',0),(2838,92,1395,0,294,940,108,2,1066828821,4,121,'',0),(2837,92,940,0,293,108,1395,2,1066828821,4,121,'',0),(2836,92,108,0,292,1402,940,2,1066828821,4,121,'',0),(2835,92,1402,0,291,73,108,2,1066828821,4,121,'',0),(2834,92,73,0,290,77,1402,2,1066828821,4,121,'',0),(2833,92,77,0,289,1401,73,2,1066828821,4,121,'',0),(2832,92,1401,0,288,152,77,2,1066828821,4,121,'',0),(2831,92,152,0,287,1313,1401,2,1066828821,4,121,'',0),(2830,92,1313,0,286,89,152,2,1066828821,4,121,'',0),(2829,92,89,0,285,1400,1313,2,1066828821,4,121,'',0),(2828,92,1400,0,284,102,89,2,1066828821,4,121,'',0),(2827,92,102,0,283,759,1400,2,1066828821,4,121,'',0),(2826,92,759,0,282,1399,102,2,1066828821,4,121,'',0),(2825,92,1399,0,281,1398,759,2,1066828821,4,121,'',0),(2824,92,1398,0,280,1397,1399,2,1066828821,4,121,'',0),(2823,92,1397,0,279,1396,1398,2,1066828821,4,121,'',0),(2822,92,1396,0,278,1395,1397,2,1066828821,4,121,'',0),(2821,92,1395,0,277,1394,1396,2,1066828821,4,121,'',0),(2820,92,1394,0,276,879,1395,2,1066828821,4,121,'',0),(2819,92,879,0,275,1393,1394,2,1066828821,4,121,'',0),(2818,92,1393,0,274,1392,879,2,1066828821,4,121,'',0),(2817,92,1392,0,273,1391,1393,2,1066828821,4,121,'',0),(2816,92,1391,0,272,1390,1392,2,1066828821,4,121,'',0),(2815,92,1390,0,271,1369,1391,2,1066828821,4,121,'',0),(2814,92,1369,0,270,86,1390,2,1066828821,4,121,'',0),(2813,92,86,0,269,1389,1369,2,1066828821,4,121,'',0),(2812,92,1389,0,268,1353,86,2,1066828821,4,121,'',0),(2811,92,1353,0,267,1348,1389,2,1066828821,4,121,'',0),(2810,92,1348,0,266,67,1353,2,1066828821,4,121,'',0),(2809,92,67,0,265,1388,1348,2,1066828821,4,121,'',0),(2808,92,1388,0,264,1387,67,2,1066828821,4,121,'',0),(2807,92,1387,0,263,1386,1388,2,1066828821,4,121,'',0),(2806,92,1386,0,262,1343,1387,2,1066828821,4,121,'',0),(2805,92,1343,0,261,1124,1386,2,1066828821,4,121,'',0),(2804,92,1124,0,260,180,1343,2,1066828821,4,121,'',0),(2803,92,180,0,259,1385,1124,2,1066828821,4,121,'',0),(2778,92,1176,0,234,1379,1124,2,1066828821,4,121,'',0),(2777,92,1379,0,233,224,1176,2,1066828821,4,121,'',0),(2776,92,224,0,232,1247,1379,2,1066828821,4,121,'',0),(2775,92,1247,0,231,1378,224,2,1066828821,4,121,'',0),(2774,92,1378,0,230,86,1247,2,1066828821,4,121,'',0),(2773,92,86,0,229,1377,1378,2,1066828821,4,121,'',0),(2772,92,1377,0,228,1333,86,2,1066828821,4,121,'',0),(3893,56,1095,0,7,1763,0,15,1066643397,11,188,'',0),(3892,56,1763,0,6,879,1095,15,1066643397,11,188,'',0),(3891,56,879,0,5,1762,1763,15,1066643397,11,188,'',0),(3890,56,1762,0,4,144,879,15,1066643397,11,188,'',0),(3889,56,144,0,3,1761,1762,15,1066643397,11,188,'',0),(3888,56,1761,0,2,1760,144,15,1066643397,11,188,'',0),(3887,56,1760,0,1,1759,1761,15,1066643397,11,188,'',0),(3886,56,1759,0,0,0,1760,15,1066643397,11,161,'',0),(2756,92,1372,0,212,1371,67,2,1066828821,4,121,'',0),(2755,92,1371,0,211,1176,1372,2,1066828821,4,121,'',0),(2754,92,1176,0,210,75,1371,2,1066828821,4,121,'',0),(2753,92,75,0,209,224,1176,2,1066828821,4,121,'',0),(2752,92,224,0,208,1370,75,2,1066828821,4,121,'',0),(2751,92,1370,0,207,1369,224,2,1066828821,4,121,'',0),(2750,92,1369,0,206,86,1370,2,1066828821,4,121,'',0),(2749,92,86,0,205,1176,1369,2,1066828821,4,121,'',0),(2748,92,1176,0,204,152,86,2,1066828821,4,121,'',0),(2747,92,152,0,203,1368,1176,2,1066828821,4,121,'',0),(2746,92,1368,0,202,1183,152,2,1066828821,4,121,'',0),(2745,92,1183,0,201,1182,1368,2,1066828821,4,121,'',0),(2744,92,1182,0,200,1367,1183,2,1066828821,4,121,'',0),(2743,92,1367,0,199,102,1182,2,1066828821,4,121,'',0),(2742,92,102,0,198,1180,1367,2,1066828821,4,121,'',0),(2741,92,1180,0,197,1366,102,2,1066828821,4,121,'',0),(2740,92,1366,0,196,1284,1180,2,1066828821,4,121,'',0),(2739,92,1284,0,195,1319,1366,2,1066828821,4,121,'',0),(2738,92,1319,0,194,1365,1284,2,1066828821,4,121,'',0),(2737,92,1365,0,193,1364,1319,2,1066828821,4,121,'',0),(2736,92,1364,0,192,254,1365,2,1066828821,4,121,'',0),(2735,92,254,0,191,1176,1364,2,1066828821,4,121,'',0),(2734,92,1176,0,190,1134,254,2,1066828821,4,121,'',0),(2733,92,1134,0,189,33,1176,2,1066828821,4,121,'',0),(2732,92,33,0,188,1363,1134,2,1066828821,4,121,'',0),(2731,92,1363,0,187,89,33,2,1066828821,4,121,'',0),(2730,92,89,0,186,1362,1363,2,1066828821,4,121,'',0),(2729,92,1362,0,185,152,89,2,1066828821,4,121,'',0),(2728,92,152,0,184,1361,1362,2,1066828821,4,121,'',0),(2727,92,1361,0,183,75,152,2,1066828821,4,121,'',0),(2726,92,75,0,182,1360,1361,2,1066828821,4,121,'',0),(2725,92,1360,0,181,102,75,2,1066828821,4,121,'',0),(2724,92,102,0,180,1359,1360,2,1066828821,4,121,'',0),(2723,92,1359,0,179,1170,102,2,1066828821,4,121,'',0),(2722,92,1170,0,178,1358,1359,2,1066828821,4,121,'',0),(2721,92,1358,0,177,1321,1170,2,1066828821,4,121,'',0),(2720,92,1321,0,176,1323,1358,2,1066828821,4,121,'',0),(2719,92,1323,0,175,1134,1321,2,1066828821,4,121,'',0),(2718,92,1134,0,174,156,1323,2,1066828821,4,121,'',0),(2717,92,156,0,173,1357,1134,2,1066828821,4,121,'',0),(2716,92,1357,0,172,1356,156,2,1066828821,4,121,'',0),(2715,92,1356,0,171,1355,1357,2,1066828821,4,121,'',0),(2714,92,1355,0,170,1354,1356,2,1066828821,4,121,'',0),(2713,92,1354,0,169,1349,1355,2,1066828821,4,121,'',0),(2712,92,1349,0,168,1353,1354,2,1066828821,4,121,'',0),(2711,92,1353,0,167,1352,1349,2,1066828821,4,121,'',0),(2710,92,1352,0,166,1351,1353,2,1066828821,4,121,'',0),(2709,92,1351,0,165,1350,1352,2,1066828821,4,121,'',0),(2708,92,1350,0,164,1349,1351,2,1066828821,4,121,'',0),(2707,92,1349,0,163,1348,1350,2,1066828821,4,121,'',0),(2706,92,1348,0,162,1347,1349,2,1066828821,4,121,'',0),(2705,92,1347,0,161,1346,1348,2,1066828821,4,121,'',0),(2704,92,1346,0,160,33,1347,2,1066828821,4,121,'',0),(2703,92,33,0,159,1345,1346,2,1066828821,4,121,'',0),(2702,92,1345,0,158,1323,33,2,1066828821,4,121,'',0),(2701,92,1323,0,157,1134,1345,2,1066828821,4,121,'',0),(2700,92,1134,0,156,254,1323,2,1066828821,4,121,'',0),(2699,92,254,0,155,1344,1134,2,1066828821,4,121,'',0),(2698,92,1344,0,154,1343,254,2,1066828821,4,121,'',0),(2697,92,1343,0,153,1284,1344,2,1066828821,4,121,'',0),(2696,92,1284,0,152,1124,1343,2,1066828821,4,121,'',0),(2695,92,1124,0,151,180,1284,2,1066828821,4,121,'',0),(2694,92,180,0,150,1342,1124,2,1066828821,4,121,'',0),(2693,92,1342,0,149,1341,180,2,1066828821,4,121,'',0),(2692,92,1341,0,148,152,1342,2,1066828821,4,121,'',0),(2691,92,152,0,147,1340,1341,2,1066828821,4,121,'',0),(2690,92,1340,0,146,1339,152,2,1066828821,4,121,'',0),(2689,92,1339,0,145,75,1340,2,1066828821,4,121,'',0),(2688,92,75,0,144,1338,1339,2,1066828821,4,121,'',0),(2687,92,1338,0,143,1337,75,2,1066828821,4,121,'',0),(2686,92,1337,0,142,33,1338,2,1066828821,4,121,'',0),(2685,92,33,0,141,1336,1337,2,1066828821,4,121,'',0),(2684,92,1336,0,140,1335,33,2,1066828821,4,121,'',0),(2683,92,1335,0,139,33,1336,2,1066828821,4,121,'',0),(2682,92,33,0,138,1334,1335,2,1066828821,4,121,'',0),(2681,92,1334,0,137,1333,33,2,1066828821,4,121,'',0),(2680,92,1333,0,136,1332,1334,2,1066828821,4,121,'',0),(2679,92,1332,0,135,1284,1333,2,1066828821,4,121,'',0),(2678,92,1284,0,134,1331,1332,2,1066828821,4,121,'',0),(2677,92,1331,0,133,152,1284,2,1066828821,4,121,'',0),(2676,92,152,0,132,1318,1331,2,1066828821,4,121,'',0),(2675,92,1318,0,131,1330,152,2,1066828821,4,121,'',0),(2674,92,1330,0,130,33,1318,2,1066828821,4,121,'',0),(2673,92,33,0,129,1329,1330,2,1066828821,4,121,'',0),(2672,92,1329,0,128,108,33,2,1066828821,4,121,'',0),(2671,92,108,0,127,1328,1329,2,1066828821,4,121,'',0),(2670,92,1328,0,126,1284,108,2,1066828821,4,121,'',0),(2669,92,1284,0,125,1327,1328,2,1066828821,4,121,'',0),(2668,92,1327,0,124,75,1284,2,1066828821,4,121,'',0),(2667,92,75,0,123,1326,1327,2,1066828821,4,121,'',0),(2666,92,1326,0,122,1325,75,2,1066828821,4,121,'',0),(2665,92,1325,0,121,33,1326,2,1066828821,4,121,'',0),(2664,92,33,0,120,235,1325,2,1066828821,4,121,'',0),(2663,92,235,0,119,1324,33,2,1066828821,4,121,'',0),(2662,92,1324,0,118,25,235,2,1066828821,4,121,'',0),(2661,92,25,0,117,1323,1324,2,1066828821,4,121,'',0),(2660,92,1323,0,116,1134,25,2,1066828821,4,121,'',0),(2659,92,1134,0,115,156,1323,2,1066828821,4,121,'',0),(2658,92,156,0,114,74,1134,2,1066828821,4,121,'',0),(2657,92,74,0,113,1322,156,2,1066828821,4,121,'',0),(2656,92,1322,0,112,1321,74,2,1066828821,4,121,'',0),(2655,92,1321,0,111,1320,1322,2,1066828821,4,121,'',0),(2654,92,1320,0,110,752,1321,2,1066828821,4,121,'',0),(2653,92,752,0,109,152,1320,2,1066828821,4,121,'',0),(2652,92,152,0,108,1130,752,2,1066828821,4,121,'',0),(2651,92,1130,0,107,180,152,2,1066828821,4,121,'',0),(2650,92,180,0,106,759,1130,2,1066828821,4,121,'',0),(2649,92,759,0,105,1315,180,2,1066828821,4,121,'',0),(2648,92,1315,0,104,1319,759,2,1066828821,4,121,'',0),(2647,92,1319,0,103,1124,1315,2,1066828821,4,121,'',0),(2646,92,1124,0,102,1318,1319,2,1066828821,4,121,'',0),(2645,92,1318,0,101,1284,1124,2,1066828821,4,121,'',0),(2644,92,1284,0,100,1317,1318,2,1066828821,4,121,'',0),(2643,92,1317,0,99,224,1284,2,1066828821,4,121,'',0),(2642,92,224,0,98,1316,1317,2,1066828821,4,121,'',0),(2641,92,1316,0,97,67,224,2,1066828821,4,121,'',0),(2640,92,67,0,96,1315,1316,2,1066828821,4,121,'',0),(2639,92,1315,0,95,221,67,2,1066828821,4,121,'',0),(2638,92,221,0,94,1314,1315,2,1066828821,4,121,'',0),(2637,92,1314,0,93,759,221,2,1066828821,4,121,'',0),(2636,92,759,0,92,1124,1314,2,1066828821,4,121,'',0),(2635,92,1124,0,91,152,759,2,1066828821,4,121,'',0),(2634,92,152,0,90,1313,1124,2,1066828821,4,121,'',0),(2633,92,1313,0,89,89,152,2,1066828821,4,121,'',0),(2632,92,89,0,88,33,1313,2,1066828821,4,121,'',0),(2631,92,33,0,87,1312,89,2,1066828821,4,121,'',0),(2630,92,1312,0,86,89,33,2,1066828821,4,121,'',0),(2629,92,89,0,85,381,1312,2,1066828821,4,121,'',0),(2628,92,381,0,84,1311,89,2,1066828821,4,121,'',0),(2627,92,1311,0,83,1310,381,2,1066828821,4,121,'',0),(2626,92,1310,0,82,759,1311,2,1066828821,4,121,'',0),(2625,92,759,0,81,86,1310,2,1066828821,4,121,'',0),(2624,92,86,0,80,1309,759,2,1066828821,4,121,'',0),(2623,92,1309,0,79,81,86,2,1066828821,4,121,'',0),(2622,92,81,0,78,1308,1309,2,1066828821,4,121,'',0),(2621,92,1308,0,77,1307,81,2,1066828821,4,121,'',0),(2620,92,1307,0,76,1306,1308,2,1066828821,4,121,'',0),(2619,92,1306,0,75,1284,1307,2,1066828821,4,121,'',0),(2618,92,1284,0,74,1305,1306,2,1066828821,4,121,'',0),(2617,92,1305,0,73,1304,1284,2,1066828821,4,121,'',0),(2616,92,1304,0,72,752,1305,2,1066828821,4,121,'',0),(2615,92,752,0,71,1280,1304,2,1066828821,4,121,'',0),(2614,92,1280,0,70,75,752,2,1066828821,4,121,'',0),(2613,92,75,0,69,108,1280,2,1066828821,4,121,'',0),(2612,92,108,0,68,1303,75,2,1066828821,4,121,'',0),(2611,92,1303,0,67,1278,108,2,1066828821,4,121,'',0),(2610,92,1278,0,66,89,1303,2,1066828821,4,121,'',0),(2609,92,89,0,65,750,1278,2,1066828821,4,121,'',0),(2608,92,750,0,64,1302,89,2,1066828821,4,121,'',0),(2607,92,1302,0,63,1301,750,2,1066828821,4,121,'',0),(2606,92,1301,0,62,75,1302,2,1066828821,4,121,'',0),(2605,92,75,0,61,1286,1301,2,1066828821,4,121,'',0),(2604,92,1286,0,60,75,75,2,1066828821,4,121,'',0),(2603,92,75,0,59,108,1286,2,1066828821,4,121,'',0),(2602,92,108,0,58,1278,75,2,1066828821,4,121,'',0),(2601,92,1278,0,57,1285,108,2,1066828821,4,121,'',0),(2600,92,1285,0,56,1300,1278,2,1066828821,4,121,'',0),(2599,92,1300,0,55,75,1285,2,1066828821,4,121,'',0),(2598,92,75,0,54,102,1300,2,1066828821,4,121,'',0),(2597,92,102,0,53,1299,75,2,1066828821,4,121,'',0),(2596,92,1299,0,52,75,102,2,1066828821,4,121,'',0),(2595,92,75,0,51,108,1299,2,1066828821,4,121,'',0),(2594,92,108,0,50,1298,75,2,1066828821,4,121,'',0),(2593,92,1298,0,49,1106,108,2,1066828821,4,121,'',0),(2592,92,1106,0,48,1284,1298,2,1066828821,4,121,'',0),(2591,92,1284,0,47,1282,1106,2,1066828821,4,121,'',0),(2590,92,1282,0,46,1283,1284,2,1066828821,4,121,'',0),(2589,92,1283,0,45,1297,1282,2,1066828821,4,121,'',0),(2588,92,1297,0,44,1296,1283,2,1066828821,4,121,'',0),(2587,92,1296,0,43,1295,1297,2,1066828821,4,120,'',0),(2586,92,1295,0,42,75,1296,2,1066828821,4,120,'',0),(2585,92,75,0,41,77,1295,2,1066828821,4,120,'',0),(2584,92,77,0,40,1294,75,2,1066828821,4,120,'',0),(2583,92,1294,0,39,74,77,2,1066828821,4,120,'',0),(2582,92,74,0,38,1280,1294,2,1066828821,4,120,'',0),(2581,92,1280,0,37,1291,74,2,1066828821,4,120,'',0),(2580,92,1291,0,36,75,1280,2,1066828821,4,120,'',0),(2579,92,75,0,35,1293,1291,2,1066828821,4,120,'',0),(2578,92,1293,0,34,33,75,2,1066828821,4,120,'',0),(2577,92,33,0,33,1292,1293,2,1066828821,4,120,'',0),(2576,92,1292,0,32,465,33,2,1066828821,4,120,'',0),(2575,92,465,0,31,77,1292,2,1066828821,4,120,'',0),(2574,92,77,0,30,1280,465,2,1066828821,4,120,'',0),(2573,92,1280,0,29,1291,77,2,1066828821,4,120,'',0),(3061,140,1325,0,11,33,1326,10,1069756410,1,141,'',0),(3060,140,33,0,10,1492,1325,10,1069756410,1,141,'',0),(3059,140,1492,0,9,235,33,10,1069756410,1,141,'',0),(3058,140,235,0,8,1324,1492,10,1069756410,1,141,'',0),(3057,140,1324,0,7,25,235,10,1069756410,1,141,'',0),(3056,140,25,0,6,1323,1324,10,1069756410,1,141,'',0),(3055,140,1323,0,5,1134,25,10,1069756410,1,141,'',0),(3054,140,1134,0,4,156,1323,10,1069756410,1,141,'',0),(3053,140,156,0,3,74,1134,10,1069756410,1,141,'',0),(3052,140,74,0,2,1491,156,10,1069756410,1,141,'',0),(3051,140,1491,0,1,1491,74,10,1069756410,1,141,'',0),(3050,140,1491,0,0,0,1491,10,1069756410,1,140,'',0),(3049,139,1490,0,113,1455,0,10,1069756326,1,141,'',0),(3048,139,1455,0,112,102,1490,10,1069756326,1,141,'',0),(3047,139,102,0,111,1489,1455,10,1069756326,1,141,'',0),(3046,139,1489,0,110,1263,102,10,1069756326,1,141,'',0),(3045,139,1263,0,109,33,1489,10,1069756326,1,141,'',0),(3044,139,33,0,108,1488,1263,10,1069756326,1,141,'',0),(3043,139,1488,0,107,1487,33,10,1069756326,1,141,'',0),(3042,139,1487,0,106,74,1488,10,1069756326,1,141,'',0),(3041,139,74,0,105,1480,1487,10,1069756326,1,141,'',0),(3040,139,1480,0,104,1486,74,10,1069756326,1,141,'',0),(3039,139,1486,0,103,1485,1480,10,1069756326,1,141,'',0),(3038,139,1485,0,102,1311,1486,10,1069756326,1,141,'',0),(3037,139,1311,0,101,1484,1485,10,1069756326,1,141,'',0),(3036,139,1484,0,100,1483,1311,10,1069756326,1,141,'',0),(3035,139,1483,0,99,1453,1484,10,1069756326,1,141,'',0),(3034,139,1453,0,98,1319,1483,10,1069756326,1,141,'',0),(3033,139,1319,0,97,1482,1453,10,1069756326,1,141,'',0),(3032,139,1482,0,96,1481,1319,10,1069756326,1,141,'',0),(3031,139,1481,0,95,1480,1482,10,1069756326,1,141,'',0),(3030,139,1480,0,94,1351,1481,10,1069756326,1,141,'',0),(3029,139,1351,0,93,1479,1480,10,1069756326,1,141,'',0),(3028,139,1479,0,92,1478,1351,10,1069756326,1,141,'',0),(3027,139,1478,0,91,75,1479,10,1069756326,1,141,'',0),(3026,139,75,0,90,1477,1478,10,1069756326,1,141,'',0),(3025,139,1477,0,89,1279,75,10,1069756326,1,141,'',0),(3024,139,1279,0,88,75,1477,10,1069756326,1,141,'',0),(3023,139,75,0,87,1276,1279,10,1069756326,1,141,'',0),(3022,139,1276,0,86,1476,75,10,1069756326,1,141,'',0),(3021,139,1476,0,85,102,1276,10,1069756326,1,141,'',0),(3020,139,102,0,84,1475,1476,10,1069756326,1,141,'',0),(3019,139,1475,0,83,1474,102,10,1069756326,1,141,'',0),(3018,139,1474,0,82,1387,1475,10,1069756326,1,141,'',0),(3017,139,1387,0,81,1473,1474,10,1069756326,1,141,'',0),(3016,139,1473,0,80,1472,1387,10,1069756326,1,141,'',0),(3015,139,1472,0,79,1451,1473,10,1069756326,1,141,'',0),(3014,139,1451,0,78,195,1472,10,1069756326,1,141,'',0),(3013,139,195,0,77,1471,1451,10,1069756326,1,141,'',0),(3012,139,1471,0,76,1470,195,10,1069756326,1,141,'',0),(3011,139,1470,0,75,1470,1471,10,1069756326,1,141,'',0),(3010,139,1470,0,74,77,1470,10,1069756326,1,141,'',0),(3009,139,77,0,73,1469,1470,10,1069756326,1,141,'',0),(3008,139,1469,0,72,1468,77,10,1069756326,1,141,'',0),(3007,139,1468,0,71,1455,1469,10,1069756326,1,141,'',0),(3006,139,1455,0,70,1467,1468,10,1069756326,1,141,'',0),(3005,139,1467,0,69,89,1455,10,1069756326,1,141,'',0),(3004,139,89,0,68,1466,1467,10,1069756326,1,141,'',0),(3003,139,1466,0,67,1465,89,10,1069756326,1,141,'',0),(3002,139,1465,0,66,195,1466,10,1069756326,1,141,'',0),(3001,139,195,0,65,1450,1465,10,1069756326,1,141,'',0),(3000,139,1450,0,64,254,195,10,1069756326,1,141,'',0),(2999,139,254,0,63,1464,1450,10,1069756326,1,141,'',0),(2998,139,1464,0,62,1446,254,10,1069756326,1,141,'',0),(2997,139,1446,0,61,1170,1464,10,1069756326,1,141,'',0),(2996,139,1170,0,60,937,1446,10,1069756326,1,141,'',0),(2995,139,937,0,59,1463,1170,10,1069756326,1,141,'',0),(2994,139,1463,0,58,1462,937,10,1069756326,1,141,'',0),(2993,139,1462,0,57,1461,1463,10,1069756326,1,141,'',0),(2992,139,1461,0,56,1460,1462,10,1069756326,1,141,'',0),(2991,139,1460,0,55,1279,1461,10,1069756326,1,141,'',0),(2990,139,1279,0,54,75,1460,10,1069756326,1,141,'',0),(2989,139,75,0,53,1459,1279,10,1069756326,1,141,'',0),(2988,139,1459,0,52,759,75,10,1069756326,1,141,'',0),(2987,139,759,0,51,1458,1459,10,1069756326,1,141,'',0),(2986,139,1458,0,50,67,759,10,1069756326,1,141,'',0),(2985,139,67,0,49,1457,1458,10,1069756326,1,141,'',0),(2984,139,1457,0,48,1353,67,10,1069756326,1,141,'',0),(2983,139,1353,0,47,1456,1457,10,1069756326,1,141,'',0),(2982,139,1456,0,46,1455,1353,10,1069756326,1,141,'',0),(2981,139,1455,0,45,1261,1456,10,1069756326,1,141,'',0),(2980,139,1261,0,44,183,1455,10,1069756326,1,141,'',0),(2979,139,183,0,43,1454,1261,10,1069756326,1,141,'',0),(2978,139,1454,0,42,1453,183,10,1069756326,1,141,'',0),(2977,139,1453,0,41,67,1454,10,1069756326,1,141,'',0),(2976,139,67,0,40,1452,1453,10,1069756326,1,141,'',0),(2975,139,1452,0,39,33,67,10,1069756326,1,141,'',0),(2974,139,33,0,38,1372,1452,10,1069756326,1,141,'',0),(2973,139,1372,0,37,1451,33,10,1069756326,1,141,'',0),(2972,139,1451,0,36,195,1372,10,1069756326,1,141,'',0),(2971,139,195,0,35,1450,1451,10,1069756326,1,141,'',0),(2970,139,1450,0,34,152,195,10,1069756326,1,141,'',0),(2969,139,152,0,33,1449,1450,10,1069756326,1,141,'',0),(2968,139,1449,0,32,1448,152,10,1069756326,1,141,'',0),(2967,139,1448,0,31,1447,1449,10,1069756326,1,141,'',0),(2966,139,1447,0,30,75,1448,10,1069756326,1,141,'',0),(2965,139,75,0,29,108,1447,10,1069756326,1,141,'',0),(2964,139,108,0,28,1446,75,10,1069756326,1,141,'',0),(2963,139,1446,0,27,1170,108,10,1069756326,1,141,'',0),(2962,139,1170,0,26,937,1446,10,1069756326,1,141,'',0),(2961,139,937,0,25,1445,1170,10,1069756326,1,141,'',0),(2960,139,1445,0,24,1438,937,10,1069756326,1,141,'',0),(2959,139,1438,0,23,1279,1445,10,1069756326,1,141,'',0),(2958,139,1279,0,22,108,1438,10,1069756326,1,141,'',0),(2957,139,108,0,21,1444,1279,10,1069756326,1,141,'',0),(2956,139,1444,0,20,75,108,10,1069756326,1,141,'',0),(2955,139,75,0,19,152,1444,10,1069756326,1,141,'',0),(2954,139,152,0,18,1279,75,10,1069756326,1,141,'',0),(2953,139,1279,0,17,81,152,10,1069756326,1,141,'',0),(2952,139,81,0,16,1443,1279,10,1069756326,1,141,'',0),(2951,139,1443,0,15,75,81,10,1069756326,1,141,'',0),(2950,139,75,0,14,152,1443,10,1069756326,1,141,'',0),(2949,139,152,0,13,1443,75,10,1069756326,1,141,'',0),(2948,139,1443,0,12,75,152,10,1069756326,1,141,'',0),(2947,139,75,0,11,1095,1443,10,1069756326,1,141,'',0),(2946,139,1095,0,10,1442,75,10,1069756326,1,141,'',0),(2945,139,1442,0,9,75,1095,10,1069756326,1,141,'',0),(2944,139,75,0,8,108,1442,10,1069756326,1,141,'',0),(2943,139,108,0,7,1441,75,10,1069756326,1,141,'',0),(2942,139,1441,0,6,152,108,10,1069756326,1,141,'',0),(2941,139,152,0,5,1440,1441,10,1069756326,1,141,'',0),(2940,139,1440,0,4,89,152,10,1069756326,1,141,'',0),(2939,139,89,0,3,1439,1440,10,1069756326,1,141,'',0),(2938,139,1439,0,2,1438,89,10,1069756326,1,140,'',0),(2937,139,1438,0,1,1279,1439,10,1069756326,1,140,'',0),(2936,139,1279,0,0,0,1438,10,1069756326,1,140,'',0),(2514,94,1264,0,15,183,152,2,1066829047,4,120,'',0),(2513,94,183,0,14,1170,1264,2,1066829047,4,120,'',0),(2512,94,1170,0,13,937,183,2,1066829047,4,120,'',0),(2511,94,937,0,12,1263,1170,2,1066829047,4,120,'',0),(2510,94,1263,0,11,1262,937,2,1066829047,4,120,'',0),(2509,94,1262,0,10,752,1263,2,1066829047,4,120,'',0),(2508,94,752,0,9,1261,1262,2,1066829047,4,120,'',0),(2507,94,1261,0,8,1260,752,2,1066829047,4,120,'',0),(2506,94,1260,0,7,1259,1261,2,1066829047,4,120,'',0),(2505,94,1259,0,6,1257,1260,2,1066829047,4,120,'',0),(2504,94,1257,0,5,1256,1259,2,1066829047,4,120,'',0),(2503,94,1256,0,4,221,1257,2,1066829047,4,120,'',0),(2502,94,221,0,3,1258,1256,2,1066829047,4,1,'',0),(2501,94,1258,0,2,1257,221,2,1066829047,4,1,'',0),(2500,94,1257,0,1,1256,1258,2,1066829047,4,1,'',0),(2499,94,1256,0,0,0,1257,2,1066829047,4,1,'',0),(2572,92,1291,0,28,1290,1280,2,1066828821,4,120,'',0),(2560,92,1286,0,16,108,89,2,1066828821,4,120,'',0),(2561,92,89,0,17,1286,1287,2,1066828821,4,120,'',0),(2562,92,1287,0,18,89,1288,2,1066828821,4,120,'',0),(2563,92,1288,0,19,1287,1280,2,1066828821,4,120,'',0),(2564,92,1280,0,20,1288,1289,2,1066828821,4,120,'',0),(2565,92,1289,0,21,1280,1279,2,1066828821,4,120,'',0),(2566,92,1279,0,22,1289,1280,2,1066828821,4,120,'',0),(2567,92,1280,0,23,1279,1095,2,1066828821,4,120,'',0),(2568,92,1095,0,24,1280,74,2,1066828821,4,120,'',0),(2569,92,74,0,25,1095,156,2,1066828821,4,120,'',0),(2570,92,156,0,26,74,1290,2,1066828821,4,120,'',0),(2571,92,1290,0,27,156,1291,2,1066828821,4,120,'',0),(2559,92,108,0,15,1278,1286,2,1066828821,4,120,'',0),(2558,92,1278,0,14,1285,108,2,1066828821,4,120,'',0),(2557,92,1285,0,13,1284,1278,2,1066828821,4,120,'',0),(2556,92,1284,0,12,1283,1285,2,1066828821,4,120,'',0),(2555,92,1283,0,11,75,1284,2,1066828821,4,120,'',0),(2554,92,75,0,10,152,1283,2,1066828821,4,120,'',0),(2553,92,152,0,9,1282,75,2,1066828821,4,120,'',0),(2552,92,1282,0,8,180,152,2,1066828821,4,120,'',0),(2551,92,180,0,7,1281,1282,2,1066828821,4,120,'',0),(2550,92,1281,0,6,73,180,2,1066828821,4,120,'',0),(2549,92,73,0,5,1095,1281,2,1066828821,4,120,'',0),(2548,92,1095,0,4,1280,73,2,1066828821,4,1,'',0),(2547,92,1280,0,3,1279,1095,2,1066828821,4,1,'',0),(2546,92,1279,0,2,108,1280,2,1066828821,4,1,'',0),(2544,92,1278,0,0,0,108,2,1066828821,4,1,'',0),(2545,92,108,0,1,1278,1279,2,1066828821,4,1,'',0),(3377,142,1596,0,0,0,86,10,1069757199,1,140,'',0),(3832,1,1739,0,6,152,381,1,1033917596,1,119,'',0),(3831,1,152,0,5,1243,1739,1,1033917596,1,119,'',0),(3830,1,1243,0,4,75,152,1,1033917596,1,119,'',0),(3829,1,75,0,3,102,1243,1,1033917596,1,119,'',0),(3828,1,102,0,2,1275,75,1,1033917596,1,119,'',0),(3827,1,1275,0,1,932,102,1,1033917596,1,119,'',0),(2779,92,1124,0,235,1176,937,2,1066828821,4,121,'',0),(3825,137,1738,0,20,1525,0,19,1068027382,1,182,'',0),(3824,137,1525,0,19,1076,1738,19,1068027382,1,182,'',0),(3823,137,1076,0,18,942,1525,19,1068027382,1,182,'',0),(3822,137,942,0,17,183,1076,19,1068027382,1,182,'',0),(3821,137,183,0,16,1733,942,19,1068027382,1,182,'',0),(3820,137,1733,0,15,102,183,19,1068027382,1,182,'',0),(3819,137,102,0,14,1737,1733,19,1068027382,1,182,'',0),(3818,137,1737,0,13,1736,102,19,1068027382,1,182,'',0),(3817,137,1736,0,12,1073,1737,19,1068027382,1,182,'',0),(3816,137,1073,0,11,1735,1736,19,1068027382,1,182,'',0),(3815,137,1735,0,10,759,1073,19,1068027382,1,182,'',0),(3814,137,759,0,9,936,1735,19,1068027382,1,182,'',0),(3813,137,936,0,8,1071,759,19,1068027382,1,182,'',0),(3812,137,1071,0,7,1734,936,19,1068027382,1,182,'',0),(3811,137,1734,0,6,1520,1071,19,1068027382,1,182,'',0),(3810,137,1520,0,5,75,1734,19,1068027382,1,182,'',0),(3809,137,75,0,4,183,1520,19,1068027382,1,182,'',0),(3808,137,183,0,3,1733,75,19,1068027382,1,182,'',0),(3807,137,1733,0,2,221,183,19,1068027382,1,182,'',0),(3806,137,221,0,1,1067,1733,19,1068027382,1,181,'',0),(3805,137,1067,0,0,0,221,19,1068027382,1,181,'',0),(2771,92,1333,0,227,1376,1377,2,1066828821,4,121,'',0),(2770,92,1376,0,226,102,1333,2,1066828821,4,121,'',0),(2769,92,102,0,225,33,1376,2,1066828821,4,121,'',0),(2768,92,33,0,224,1375,102,2,1066828821,4,121,'',0),(2767,92,1375,0,223,102,33,2,1066828821,4,121,'',0),(2766,92,102,0,222,1375,1375,2,1066828821,4,121,'',0),(2765,92,1375,0,221,1229,102,2,1066828821,4,121,'',0),(2764,92,1229,0,220,102,1375,2,1066828821,4,121,'',0),(2763,92,102,0,219,1374,1229,2,1066828821,4,121,'',0),(2762,92,1374,0,218,33,102,2,1066828821,4,121,'',0),(2761,92,33,0,217,1373,1374,2,1066828821,4,121,'',0),(2760,92,1373,0,216,1228,33,2,1066828821,4,121,'',0),(2759,92,1228,0,215,74,1373,2,1066828821,4,121,'',0),(2758,92,74,0,214,67,1228,2,1066828821,4,121,'',0),(2757,92,67,0,213,1372,74,2,1066828821,4,121,'',0),(2543,94,1277,0,44,752,0,2,1066829047,4,121,'',0),(2542,94,752,0,43,1276,1277,2,1066829047,4,121,'',0),(2541,94,1276,0,42,1268,752,2,1066829047,4,121,'',0),(2540,94,1268,0,41,1275,1276,2,1066829047,4,121,'',0),(2539,94,1275,0,40,81,1268,2,1066829047,4,121,'',0),(2538,94,81,0,39,936,1275,2,1066829047,4,121,'',0),(2537,94,936,0,38,1247,81,2,1066829047,4,121,'',0),(2536,94,1247,0,37,1246,936,2,1066829047,4,121,'',0),(2535,94,1246,0,36,33,1247,2,1066829047,4,121,'',0),(2534,94,33,0,35,1257,1246,2,1066829047,4,121,'',0),(2533,94,1257,0,34,1256,33,2,1066829047,4,121,'',0),(2532,94,1256,0,33,74,1257,2,1066829047,4,121,'',0),(2531,94,74,0,32,1274,1256,2,1066829047,4,121,'',0),(2530,94,1274,0,31,1269,74,2,1066829047,4,121,'',0),(2529,94,1269,0,30,1273,1274,2,1066829047,4,121,'',0),(2528,94,1273,0,29,1272,1269,2,1066829047,4,120,'',0),(2527,94,1272,0,28,1261,1273,2,1066829047,4,120,'',0),(2526,94,1261,0,27,1271,1272,2,1066829047,4,120,'',0),(2525,94,1271,0,26,1270,1261,2,1066829047,4,120,'',0),(2524,94,1270,0,25,1269,1271,2,1066829047,4,120,'',0),(2523,94,1269,0,24,108,1270,2,1066829047,4,120,'',0),(2522,94,108,0,23,1268,1269,2,1066829047,4,120,'',0),(2521,94,1268,0,22,1267,108,2,1066829047,4,120,'',0),(2520,94,1267,0,21,86,1268,2,1066829047,4,120,'',0),(2519,94,86,0,20,1266,1267,2,1066829047,4,120,'',0),(2518,94,1266,0,19,1265,86,2,1066829047,4,120,'',0),(2517,94,1265,0,18,75,1266,2,1066829047,4,120,'',0),(2516,94,75,0,17,152,1265,2,1066829047,4,120,'',0),(2515,94,152,0,16,1264,75,2,1066829047,4,120,'',0),(2498,138,73,0,36,1255,0,2,1069755162,4,121,'',0),(2497,138,1255,0,35,1254,73,2,1069755162,4,121,'',0),(2496,138,1254,0,34,1253,1255,2,1069755162,4,121,'',0),(2495,138,1253,0,33,1252,1254,2,1069755162,4,121,'',0),(2494,138,1252,0,32,759,1253,2,1069755162,4,121,'',0),(2493,138,759,0,31,86,1252,2,1069755162,4,121,'',0),(2492,138,86,0,30,1073,759,2,1069755162,4,121,'',0),(2491,138,1073,0,29,75,86,2,1069755162,4,121,'',0),(2490,138,75,0,28,1106,1073,2,1069755162,4,121,'',0),(2489,138,1106,0,27,1244,75,2,1069755162,4,120,'',0),(2488,138,1244,0,26,108,1106,2,1069755162,4,120,'',0),(2487,138,108,0,25,1251,1244,2,1069755162,4,120,'',0),(2486,138,1251,0,24,86,108,2,1069755162,4,120,'',0),(2485,138,86,0,23,1250,1251,2,1069755162,4,120,'',0),(2484,138,1250,0,22,33,86,2,1069755162,4,120,'',0),(2483,138,33,0,21,940,1250,2,1069755162,4,120,'',0),(2482,138,940,0,20,75,33,2,1069755162,4,120,'',0),(2481,138,75,0,19,934,940,2,1069755162,4,120,'',0),(2480,138,934,0,18,1249,75,2,1069755162,4,120,'',0),(2479,138,1249,0,17,938,934,2,1069755162,4,120,'',0),(2478,138,938,0,16,102,1249,2,1069755162,4,120,'',0),(2477,138,102,0,15,1248,938,2,1069755162,4,120,'',0),(2476,138,1248,0,14,74,102,2,1069755162,4,120,'',0),(2475,138,74,0,13,67,1248,2,1069755162,4,120,'',0),(2474,138,67,0,12,224,74,2,1069755162,4,120,'',0),(2473,138,224,0,11,1247,67,2,1069755162,4,120,'',0),(2472,138,1247,0,10,1246,224,2,1069755162,4,120,'',0),(2471,138,1246,0,9,1243,1247,2,1069755162,4,120,'',0),(2470,138,1243,0,8,195,1246,2,1069755162,4,120,'',0),(2469,138,195,0,7,752,1243,2,1069755162,4,120,'',0),(2468,138,752,0,6,1245,195,2,1069755162,4,120,'',0),(2467,138,1245,0,5,1244,752,2,1069755162,4,120,'',0),(2466,138,1244,0,4,759,1245,2,1069755162,4,120,'',0),(2465,138,759,0,3,86,1244,2,1069755162,4,120,'',0),(2464,138,86,0,2,1243,759,2,1069755162,4,120,'',0),(2463,138,1243,0,1,195,86,2,1069755162,4,1,'',0),(2462,138,195,0,0,0,1243,2,1069755162,4,1,'',0),(3376,136,1242,0,107,1594,0,10,1067937053,1,141,'',0),(3375,136,1594,0,106,1595,1242,10,1067937053,1,141,'',0),(3374,136,1595,0,105,1124,1594,10,1067937053,1,141,'',0),(3373,136,1124,0,104,1594,1595,10,1067937053,1,141,'',0),(3372,136,1594,0,103,1170,1124,10,1067937053,1,141,'',0),(3371,136,1170,0,102,1574,1594,10,1067937053,1,141,'',0),(3370,136,1574,0,101,86,1170,10,1067937053,1,141,'',0),(3369,136,86,0,100,1594,1574,10,1067937053,1,141,'',0),(3368,136,1594,0,99,1183,86,10,1067937053,1,141,'',0),(3367,136,1183,0,98,1182,1594,10,1067937053,1,141,'',0),(3366,136,1182,0,97,1593,1183,10,1067937053,1,141,'',0),(3365,136,1593,0,96,937,1182,10,1067937053,1,141,'',0),(3364,136,937,0,95,86,1593,10,1067937053,1,141,'',0),(3363,136,86,0,94,1180,937,10,1067937053,1,141,'',0),(3362,136,1180,0,93,1176,86,10,1067937053,1,141,'',0),(3361,136,1176,0,92,75,1180,10,1067937053,1,141,'',0),(3360,136,75,0,91,254,1176,10,1067937053,1,141,'',0),(3359,136,254,0,90,1180,75,10,1067937053,1,141,'',0),(3358,136,1180,0,89,33,254,10,1067937053,1,141,'',0),(3357,136,33,0,88,1592,1180,10,1067937053,1,141,'',0),(3356,136,1592,0,87,1591,33,10,1067937053,1,141,'',0),(3355,136,1591,0,86,1577,1592,10,1067937053,1,141,'',0),(3354,136,1577,0,85,89,1591,10,1067937053,1,141,'',0),(3353,136,89,0,84,879,1577,10,1067937053,1,141,'',0),(3352,136,879,0,83,1180,89,10,1067937053,1,141,'',0),(3351,136,1180,0,82,1590,879,10,1067937053,1,141,'',0),(3350,136,1590,0,81,33,1180,10,1067937053,1,141,'',0),(3349,136,33,0,80,1130,1590,10,1067937053,1,141,'',0),(3348,136,1130,0,79,33,33,10,1067937053,1,141,'',0),(3347,136,33,0,78,1588,1130,10,1067937053,1,141,'',0),(3346,136,1588,0,77,465,33,10,1067937053,1,141,'',0),(3345,136,465,0,76,752,1588,10,1067937053,1,141,'',0),(3344,136,752,0,75,1225,465,10,1067937053,1,141,'',0),(3343,136,1225,0,74,1574,752,10,1067937053,1,141,'',0),(3342,136,1574,0,73,86,1225,10,1067937053,1,141,'',0),(3341,136,86,0,72,1589,1574,10,1067937053,1,141,'',0),(3340,136,1589,0,71,1130,86,10,1067937053,1,141,'',0),(3339,136,1130,0,70,33,1589,10,1067937053,1,141,'',0),(3338,136,33,0,69,1588,1130,10,1067937053,1,141,'',0),(3337,136,1588,0,68,1124,33,10,1067937053,1,141,'',0),(3336,136,1124,0,67,1587,1588,10,1067937053,1,141,'',0),(3335,136,1587,0,66,1586,1124,10,1067937053,1,141,'',0),(3334,136,1586,0,65,1228,1587,10,1067937053,1,141,'',0),(3333,136,1228,0,64,1585,1586,10,1067937053,1,141,'',0),(3332,136,1585,0,63,1134,1228,10,1067937053,1,141,'',0),(3331,136,1134,0,62,156,1585,10,1067937053,1,141,'',0),(3330,136,156,0,61,33,1134,10,1067937053,1,141,'',0),(3329,136,33,0,60,1584,156,10,1067937053,1,141,'',0),(3328,136,1584,0,59,1134,33,10,1067937053,1,141,'',0),(3327,136,1134,0,58,156,1584,10,1067937053,1,141,'',0),(3326,136,156,0,57,254,1134,10,1067937053,1,141,'',0),(3325,136,254,0,56,1581,156,10,1067937053,1,141,'',0),(3324,136,1581,0,55,75,254,10,1067937053,1,141,'',0),(3323,136,75,0,54,1229,1581,10,1067937053,1,141,'',0),(3322,136,1229,0,53,1228,75,10,1067937053,1,141,'',0),(3321,136,1228,0,52,1574,1229,10,1067937053,1,141,'',0),(3320,136,1574,0,51,86,1228,10,1067937053,1,141,'',0),(3319,136,86,0,50,1134,1574,10,1067937053,1,141,'',0),(3318,136,1134,0,49,1583,86,10,1067937053,1,141,'',0),(3317,136,1583,0,48,932,1134,10,1067937053,1,141,'',0),(3316,136,932,0,47,1582,1583,10,1067937053,1,141,'',0),(3315,136,1582,0,46,33,932,10,1067937053,1,141,'',0),(3314,136,33,0,45,465,1582,10,1067937053,1,141,'',0),(3313,136,465,0,44,1225,33,10,1067937053,1,141,'',0),(3312,136,1225,0,43,102,465,10,1067937053,1,141,'',0),(3311,136,102,0,42,1581,1225,10,1067937053,1,141,'',0),(3310,136,1581,0,41,75,102,10,1067937053,1,141,'',0),(3309,136,75,0,40,1580,1581,10,1067937053,1,141,'',0),(3308,136,1580,0,39,1579,75,10,1067937053,1,141,'',0),(3307,136,1579,0,38,33,1580,10,1067937053,1,141,'',0),(3306,136,33,0,37,1124,1579,10,1067937053,1,141,'',0),(3305,136,1124,0,36,1578,33,10,1067937053,1,141,'',0),(3304,136,1578,0,35,1577,1124,10,1067937053,1,141,'',0),(3303,136,1577,0,34,1576,1578,10,1067937053,1,141,'',0),(3302,136,1576,0,33,1575,1577,10,1067937053,1,141,'',0),(3301,136,1575,0,32,1134,1576,10,1067937053,1,141,'',0),(3300,136,1134,0,31,156,1575,10,1067937053,1,141,'',0),(3299,136,156,0,30,1170,1134,10,1067937053,1,141,'',0),(3298,136,1170,0,29,1574,156,10,1067937053,1,141,'',0),(3297,136,1574,0,28,1573,1170,10,1067937053,1,141,'',0),(3296,136,1573,0,27,1572,1574,10,1067937053,1,141,'',0),(3295,136,1572,0,26,932,1573,10,1067937053,1,141,'',0),(3294,136,932,0,25,1565,1572,10,1067937053,1,141,'',0),(3293,136,1565,0,24,1564,932,10,1067937053,1,141,'',0),(3292,136,1564,0,23,183,1565,10,1067937053,1,141,'',0),(3291,136,183,0,22,1571,1564,10,1067937053,1,141,'',0),(3290,136,1571,0,21,1570,183,10,1067937053,1,141,'',0),(3289,136,1570,0,20,183,1571,10,1067937053,1,141,'',0),(3288,136,183,0,19,1569,1570,10,1067937053,1,141,'',0),(3287,136,1569,0,18,1568,183,10,1067937053,1,141,'',0),(3286,136,1568,0,17,940,1569,10,1067937053,1,141,'',0),(3285,136,940,0,16,1562,1568,10,1067937053,1,141,'',0),(3284,136,1562,0,15,1567,940,10,1067937053,1,141,'',0),(3283,136,1567,0,14,1566,1562,10,1067937053,1,141,'',0),(3282,136,1566,0,13,254,1567,10,1067937053,1,141,'',0),(3281,136,254,0,12,1565,1566,10,1067937053,1,141,'',0),(3280,136,1565,0,11,1564,254,10,1067937053,1,141,'',0),(3279,136,1564,0,10,183,1565,10,1067937053,1,141,'',0),(3278,136,183,0,9,1563,1564,10,1067937053,1,141,'',0),(3277,136,1563,0,8,74,183,10,1067937053,1,141,'',0),(3276,136,74,0,7,940,1563,10,1067937053,1,141,'',0),(3275,136,940,0,6,1562,74,10,1067937053,1,141,'',0),(3274,136,1562,0,5,940,940,10,1067937053,1,141,'',0),(3273,136,940,0,4,942,1562,10,1067937053,1,141,'',0),(3272,136,942,0,3,934,940,10,1067937053,1,141,'',0),(3271,136,934,0,2,465,942,10,1067937053,1,141,'',0),(3270,136,465,0,1,934,934,10,1067937053,1,141,'',0),(3269,136,934,0,0,0,465,10,1067937053,1,140,'',0),(3518,135,381,0,2,1649,936,1,1067936571,1,119,'',0),(3517,135,1649,0,1,1648,381,1,1067936571,1,4,'',0),(3516,135,1648,0,0,0,1649,1,1067936571,1,4,'',0),(1861,134,939,0,4,752,0,1,1067872529,1,119,'',0),(1860,134,752,0,3,934,939,1,1067872529,1,119,'',0),(1859,134,934,0,2,465,752,1,1067872529,1,119,'',0),(1858,134,465,0,1,939,934,1,1067872529,1,119,'',0),(1857,134,939,0,0,0,465,1,1067872529,1,4,'',0),(1856,133,935,0,8,752,0,1,1067872500,1,119,'',0),(1855,133,752,0,7,934,935,1,1067872500,1,119,'',0),(1854,133,934,0,6,465,752,1,1067872500,1,119,'',0),(1853,133,465,0,5,938,934,1,1067872500,1,119,'',0),(1852,133,938,0,4,937,465,1,1067872500,1,119,'',0),(1851,133,937,0,3,936,938,1,1067872500,1,119,'',0),(1850,133,936,0,2,381,937,1,1067872500,1,119,'',0),(1849,133,381,0,1,935,936,1,1067872500,1,119,'',0),(1848,133,935,0,0,0,381,1,1067872500,1,4,'',0),(3826,1,932,0,0,0,1275,1,1033917596,1,4,'',0),(2070,45,33,0,1,32,34,14,1066388816,11,152,'',0),(2065,115,303,0,2,7,0,14,1066991725,11,155,'',0),(2064,115,7,0,1,303,303,14,1066991725,11,155,'',0),(2063,115,303,0,0,0,7,14,1066991725,11,152,'',0),(2078,116,1054,0,3,25,0,14,1066992054,11,155,'',0),(2077,116,25,0,2,1053,1054,14,1066992054,11,155,'',0),(2076,116,1053,0,1,292,25,14,1066992054,11,152,'',0),(2075,116,292,0,0,0,1053,14,1066992054,11,152,'',0),(2069,45,32,0,0,0,33,14,1066388816,11,152,'',0),(2867,92,1414,0,323,1413,1319,2,1066828821,4,121,'',0),(2868,92,1319,0,324,1414,1284,2,1066828821,4,121,'',0),(2869,92,1284,0,325,1319,1415,2,1066828821,4,121,'',0),(2870,92,1415,0,326,1284,102,2,1066828821,4,121,'',0),(2871,92,102,0,327,1415,1416,2,1066828821,4,121,'',0),(2872,92,1416,0,328,102,1417,2,1066828821,4,121,'',0),(2873,92,1417,0,329,1416,1106,2,1066828821,4,121,'',0),(2874,92,1106,0,330,1417,1418,2,1066828821,4,121,'',0),(2875,92,1418,0,331,1106,1242,2,1066828821,4,121,'',0),(2876,92,1242,0,332,1418,73,2,1066828821,4,121,'',0),(2877,92,73,0,333,1242,74,2,1066828821,4,121,'',0),(2878,92,74,0,334,73,1359,2,1066828821,4,121,'',0),(2879,92,1359,0,335,74,752,2,1066828821,4,121,'',0),(2880,92,752,0,336,1359,1419,2,1066828821,4,121,'',0),(2881,92,1419,0,337,752,86,2,1066828821,4,121,'',0),(2882,92,86,0,338,1419,1369,2,1066828821,4,121,'',0),(2883,92,1369,0,339,86,1306,2,1066828821,4,121,'',0),(2884,92,1306,0,340,1369,1420,2,1066828821,4,121,'',0),(2885,92,1420,0,341,1306,33,2,1066828821,4,121,'',0),(2886,92,33,0,342,1420,1421,2,1066828821,4,121,'',0),(2887,92,1421,0,343,33,102,2,1066828821,4,121,'',0),(2888,92,102,0,344,1421,1422,2,1066828821,4,121,'',0),(2889,92,1422,0,345,102,102,2,1066828821,4,121,'',0),(2890,92,102,0,346,1422,89,2,1066828821,4,121,'',0),(2891,92,89,0,347,102,1423,2,1066828821,4,121,'',0),(2892,92,1423,0,348,89,1424,2,1066828821,4,121,'',0),(2893,92,1424,0,349,1423,152,2,1066828821,4,121,'',0),(2894,92,152,0,350,1424,1124,2,1066828821,4,121,'',0),(2895,92,1124,0,351,152,1071,2,1066828821,4,121,'',0),(2896,92,1071,0,352,1124,936,2,1066828821,4,121,'',0),(2897,92,936,0,353,1071,759,2,1066828821,4,121,'',0),(2898,92,759,0,354,936,75,2,1066828821,4,121,'',0),(2899,92,75,0,355,759,1425,2,1066828821,4,121,'',0),(2900,92,1425,0,356,75,1426,2,1066828821,4,121,'',0),(2901,92,1426,0,357,1425,75,2,1066828821,4,121,'',0),(2902,92,75,0,358,1426,1280,2,1066828821,4,121,'',0),(2903,92,1280,0,359,75,34,2,1066828821,4,121,'',0),(2904,92,34,0,360,1280,1384,2,1066828821,4,121,'',0),(2905,92,1384,0,361,34,102,2,1066828821,4,121,'',0),(2906,92,102,0,362,1384,1427,2,1066828821,4,121,'',0),(2907,92,1427,0,363,102,1391,2,1066828821,4,121,'',0),(2908,92,1391,0,364,1427,752,2,1066828821,4,121,'',0),(2909,92,752,0,365,1391,1428,2,1066828821,4,121,'',0),(2910,92,1428,0,366,752,74,2,1066828821,4,121,'',0),(2911,92,74,0,367,1428,1134,2,1066828821,4,121,'',0),(2912,92,1134,0,368,74,33,2,1066828821,4,121,'',0),(2913,92,33,0,369,1134,1429,2,1066828821,4,121,'',0),(2914,92,1429,0,370,33,77,2,1066828821,4,121,'',0),(2915,92,77,0,371,1429,1430,2,1066828821,4,121,'',0),(2916,92,1430,0,372,77,1431,2,1066828821,4,121,'',0),(2917,92,1431,0,373,1430,1358,2,1066828821,4,121,'',0),(2918,92,1358,0,374,1431,1432,2,1066828821,4,121,'',0),(2919,92,1432,0,375,1358,1433,2,1066828821,4,121,'',0),(2920,92,1433,0,376,1432,1180,2,1066828821,4,121,'',0),(2921,92,1180,0,377,1433,254,2,1066828821,4,121,'',0),(2922,92,254,0,378,1180,752,2,1066828821,4,121,'',0),(2923,92,752,0,379,254,1434,2,1066828821,4,121,'',0),(2924,92,1434,0,380,752,33,2,1066828821,4,121,'',0),(2925,92,33,0,381,1434,1435,2,1066828821,4,121,'',0),(2926,92,1435,0,382,33,156,2,1066828821,4,121,'',0),(2927,92,156,0,383,1435,1106,2,1066828821,4,121,'',0),(2928,92,1106,0,384,156,1298,2,1066828821,4,121,'',0),(2929,92,1298,0,385,1106,1436,2,1066828821,4,121,'',0),(2930,92,1436,0,386,1298,1106,2,1066828821,4,121,'',0),(2931,92,1106,0,387,1436,1437,2,1066828821,4,121,'',0),(2932,92,1437,0,388,1106,152,2,1066828821,4,121,'',0),(2933,92,152,0,389,1437,75,2,1066828821,4,121,'',0),(2934,92,75,0,390,152,235,2,1066828821,4,121,'',0),(2935,92,235,0,391,75,0,2,1066828821,4,121,'',0),(3062,140,1326,0,12,1325,254,10,1069756410,1,141,'',0),(3063,140,254,0,13,1326,1493,10,1069756410,1,141,'',0),(3064,140,1493,0,14,254,1494,10,1069756410,1,141,'',0),(3065,140,1494,0,15,1493,77,10,1069756410,1,141,'',0),(3066,140,77,0,16,1494,1076,10,1069756410,1,141,'',0),(3067,140,1076,0,17,77,1495,10,1069756410,1,141,'',0),(3068,140,1495,0,18,1076,1496,10,1069756410,1,141,'',0),(3069,140,1496,0,19,1495,37,10,1069756410,1,141,'',0),(3070,140,37,0,20,1496,1496,10,1069756410,1,141,'',0),(3071,140,1496,0,21,37,1497,10,1069756410,1,141,'',0),(3072,140,1497,0,22,1496,1498,10,1069756410,1,141,'',0),(3073,140,1498,0,23,1497,1499,10,1069756410,1,141,'',0),(3074,140,1499,0,24,1498,1500,10,1069756410,1,141,'',0),(3075,140,1500,0,25,1499,33,10,1069756410,1,141,'',0),(3076,140,33,0,26,1500,1501,10,1069756410,1,141,'',0),(3077,140,1501,0,27,33,1379,10,1069756410,1,141,'',0),(3078,140,1379,0,28,1501,936,10,1069756410,1,141,'',0),(3079,140,936,0,29,1379,1358,10,1069756410,1,141,'',0),(3080,140,1358,0,30,936,1502,10,1069756410,1,141,'',0),(3081,140,1502,0,31,1358,942,10,1069756410,1,141,'',0),(3082,140,942,0,32,1502,1503,10,1069756410,1,141,'',0),(3083,140,1503,0,33,942,1504,10,1069756410,1,141,'',0),(3084,140,1504,0,34,1503,1505,10,1069756410,1,141,'',0),(3085,140,1505,0,35,1504,33,10,1069756410,1,141,'',0),(3086,140,33,0,36,1505,1506,10,1069756410,1,141,'',0),(3087,140,1506,0,37,33,1491,10,1069756410,1,141,'',0),(3088,140,1491,0,38,1506,74,10,1069756410,1,141,'',0),(3089,140,74,0,39,1491,89,10,1069756410,1,141,'',0),(3090,140,89,0,40,74,1306,10,1069756410,1,141,'',0),(3091,140,1306,0,41,89,1420,10,1069756410,1,141,'',0),(3092,140,1420,0,42,1306,235,10,1069756410,1,141,'',0),(3093,140,235,0,43,1420,77,10,1069756410,1,141,'',0),(3094,140,77,0,44,235,1430,10,1069756410,1,141,'',0),(3095,140,1430,0,45,77,224,10,1069756410,1,141,'',0),(3096,140,224,0,46,1430,1507,10,1069756410,1,141,'',0),(3097,140,1507,0,47,224,102,10,1069756410,1,141,'',0),(3098,140,102,0,48,1507,1225,10,1069756410,1,141,'',0),(3099,140,1225,0,49,102,1508,10,1069756410,1,141,'',0),(3100,140,1508,0,50,1225,465,10,1069756410,1,141,'',0),(3101,140,465,0,51,1508,1106,10,1069756410,1,141,'',0),(3102,140,1106,0,52,465,75,10,1069756410,1,141,'',0),(3103,140,75,0,53,1106,1509,10,1069756410,1,141,'',0),(3104,140,1509,0,54,75,254,10,1069756410,1,141,'',0),(3105,140,254,0,55,1509,1491,10,1069756410,1,141,'',0),(3106,140,1491,0,56,254,936,10,1069756410,1,141,'',0),(3107,140,936,0,57,1491,1358,10,1069756410,1,141,'',0),(3108,140,1358,0,58,936,1510,10,1069756410,1,141,'',0),(3109,140,1510,0,59,1358,1511,10,1069756410,1,141,'',0),(3110,140,1511,0,60,1510,1051,10,1069756410,1,141,'',0),(3111,140,1051,0,61,1511,33,10,1069756410,1,141,'',0),(3112,140,33,0,62,1051,1512,10,1069756410,1,141,'',0),(3113,140,1512,0,63,33,81,10,1069756410,1,141,'',0),(3114,140,81,0,64,1512,1513,10,1069756410,1,141,'',0),(3115,140,1513,0,65,81,152,10,1069756410,1,141,'',0),(3116,140,152,0,66,1513,25,10,1069756410,1,141,'',0),(3117,140,25,0,67,152,33,10,1069756410,1,141,'',0),(3118,140,33,0,68,25,75,10,1069756410,1,141,'',0),(3119,140,75,0,69,33,1312,10,1069756410,1,141,'',0),(3120,140,1312,0,70,75,102,10,1069756410,1,141,'',0),(3121,140,102,0,71,1312,1312,10,1069756410,1,141,'',0),(3122,140,1312,0,72,102,1514,10,1069756410,1,141,'',0),(3123,140,1514,0,73,1312,1358,10,1069756410,1,141,'',0),(3124,140,1358,0,74,1514,1510,10,1069756410,1,141,'',0),(3125,140,1510,0,75,1358,1170,10,1069756410,1,141,'',0),(3126,140,1170,0,76,1510,1515,10,1069756410,1,141,'',0),(3127,140,1515,0,77,1170,1391,10,1069756410,1,141,'',0),(3128,140,1391,0,78,1515,1516,10,1069756410,1,141,'',0),(3129,140,1516,0,79,1391,1517,10,1069756410,1,141,'',0),(3130,140,1517,0,80,1516,1518,10,1069756410,1,141,'',0),(3131,140,1518,0,81,1517,0,10,1069756410,1,141,'',0),(3378,142,86,0,1,1596,1284,10,1069757199,1,141,'',0),(3379,142,1284,0,2,86,1244,10,1069757199,1,141,'',0),(3380,142,1244,0,3,1284,1597,10,1069757199,1,141,'',0),(3381,142,1597,0,4,1244,75,10,1069757199,1,141,'',0),(3382,142,75,0,5,1597,1301,10,1069757199,1,141,'',0),(3383,142,1301,0,6,75,235,10,1069757199,1,141,'',0),(3384,142,235,0,7,1301,1598,10,1069757199,1,141,'',0),(3385,142,1598,0,8,235,936,10,1069757199,1,141,'',0),(3386,142,936,0,9,1598,937,10,1069757199,1,141,'',0),(3387,142,937,0,10,936,1170,10,1069757199,1,141,'',0),(3388,142,1170,0,11,937,1599,10,1069757199,1,141,'',0),(3389,142,1599,0,12,1170,152,10,1069757199,1,141,'',0),(3390,142,152,0,13,1599,75,10,1069757199,1,141,'',0),(3391,142,75,0,14,152,144,10,1069757199,1,141,'',0),(3392,142,144,0,15,75,1325,10,1069757199,1,141,'',0),(3393,142,1325,0,16,144,1283,10,1069757199,1,141,'',0),(3394,142,1283,0,17,1325,1600,10,1069757199,1,141,'',0),(3395,142,1600,0,18,1283,752,10,1069757199,1,141,'',0),(3396,142,752,0,19,1600,935,10,1069757199,1,141,'',0),(3397,142,935,0,20,752,936,10,1069757199,1,141,'',0),(3398,142,936,0,21,935,937,10,1069757199,1,141,'',0),(3399,142,937,0,22,936,1601,10,1069757199,1,141,'',0),(3400,142,1601,0,23,937,1170,10,1069757199,1,141,'',0),(3401,142,1170,0,24,1601,1599,10,1069757199,1,141,'',0),(3402,142,1599,0,25,1170,152,10,1069757199,1,141,'',0),(3403,142,152,0,26,1599,1602,10,1069757199,1,141,'',0),(3404,142,1602,0,27,152,1603,10,1069757199,1,141,'',0),(3405,142,1603,0,28,1602,1604,10,1069757199,1,141,'',0),(3406,142,1604,0,29,1603,879,10,1069757199,1,141,'',0),(3407,142,879,0,30,1604,89,10,1069757199,1,141,'',0),(3408,142,89,0,31,879,1605,10,1069757199,1,141,'',0),(3409,142,1605,0,32,89,1606,10,1069757199,1,141,'',0),(3410,142,1606,0,33,1605,33,10,1069757199,1,141,'',0),(3411,142,33,0,34,1606,1607,10,1069757199,1,141,'',0),(3412,142,1607,0,35,33,1608,10,1069757199,1,141,'',0),(3413,142,1608,0,36,1607,1306,10,1069757199,1,141,'',0),(3414,142,1306,0,37,1608,1609,10,1069757199,1,141,'',0),(3415,142,1609,0,38,1306,1610,10,1069757199,1,141,'',0),(3416,142,1610,0,39,1609,1611,10,1069757199,1,141,'',0),(3417,142,1611,0,40,1610,1284,10,1069757199,1,141,'',0),(3418,142,1284,0,41,1611,1612,10,1069757199,1,141,'',0),(3419,142,1612,0,42,1284,936,10,1069757199,1,141,'',0),(3420,142,936,0,43,1612,1613,10,1069757199,1,141,'',0),(3421,142,1613,0,44,936,1614,10,1069757199,1,141,'',0),(3422,142,1614,0,45,1613,1615,10,1069757199,1,141,'',0),(3423,142,1615,0,46,1614,1616,10,1069757199,1,141,'',0),(3424,142,1616,0,47,1615,1610,10,1069757199,1,141,'',0),(3425,142,1610,0,48,1616,33,10,1069757199,1,141,'',0),(3426,142,33,0,49,1610,1617,10,1069757199,1,141,'',0),(3427,142,1617,0,50,33,1618,10,1069757199,1,141,'',0),(3428,142,1618,0,51,1617,1619,10,1069757199,1,141,'',0),(3429,142,1619,0,52,1618,33,10,1069757199,1,141,'',0),(3430,142,33,0,53,1619,1620,10,1069757199,1,141,'',0),(3431,142,1620,0,54,33,936,10,1069757199,1,141,'',0),(3432,142,936,0,55,1620,1458,10,1069757199,1,141,'',0),(3433,142,1458,0,56,936,1170,10,1069757199,1,141,'',0),(3434,142,1170,0,57,1458,1621,10,1069757199,1,141,'',0),(3435,142,1621,0,58,1170,254,10,1069757199,1,141,'',0),(3436,142,254,0,59,1621,1622,10,1069757199,1,141,'',0),(3437,142,1622,0,60,254,1623,10,1069757199,1,141,'',0),(3438,142,1623,0,61,1622,1624,10,1069757199,1,141,'',0),(3439,142,1624,0,62,1623,1625,10,1069757199,1,141,'',0),(3440,142,1625,0,63,1624,1626,10,1069757199,1,141,'',0),(3441,142,1626,0,64,1625,1624,10,1069757199,1,141,'',0),(3442,142,1624,0,65,1626,1627,10,1069757199,1,141,'',0),(3443,142,1627,0,66,1624,33,10,1069757199,1,141,'',0),(3444,142,33,0,67,1627,1342,10,1069757199,1,141,'',0),(3445,142,1342,0,68,33,1628,10,1069757199,1,141,'',0),(3446,142,1628,0,69,1342,1629,10,1069757199,1,141,'',0),(3447,142,1629,0,70,1628,254,10,1069757199,1,141,'',0),(3448,142,254,0,71,1629,75,10,1069757199,1,141,'',0),(3449,142,75,0,72,254,1630,10,1069757199,1,141,'',0),(3450,142,1630,0,73,75,1631,10,1069757199,1,141,'',0),(3451,142,1631,0,74,1630,74,10,1069757199,1,141,'',0),(3452,142,74,0,75,1631,89,10,1069757199,1,141,'',0),(3453,142,89,0,76,74,1632,10,1069757199,1,141,'',0),(3454,142,1632,0,77,89,1629,10,1069757199,1,141,'',0),(3455,142,1629,0,78,1632,108,10,1069757199,1,141,'',0),(3456,142,108,0,79,1629,1134,10,1069757199,1,141,'',0),(3457,142,1134,0,80,108,1323,10,1069757199,1,141,'',0),(3458,142,1323,0,81,1134,1603,10,1069757199,1,141,'',0),(3459,142,1603,0,82,1323,74,10,1069757199,1,141,'',0),(3460,142,74,0,83,1603,89,10,1069757199,1,141,'',0),(3461,142,89,0,84,74,1632,10,1069757199,1,141,'',0),(3462,142,1632,0,85,89,1633,10,1069757199,1,141,'',0),(3463,142,1633,0,86,1632,1634,10,1069757199,1,141,'',0),(3464,142,1634,0,87,1633,1570,10,1069757199,1,141,'',0),(3465,142,1570,0,88,1634,1601,10,1069757199,1,141,'',0),(3466,142,1601,0,89,1570,1635,10,1069757199,1,141,'',0),(3467,142,1635,0,90,1601,1071,10,1069757199,1,141,'',0),(3468,142,1071,0,91,1635,936,10,1069757199,1,141,'',0),(3469,142,936,0,92,1071,759,10,1069757199,1,141,'',0),(3470,142,759,0,93,936,1306,10,1069757199,1,141,'',0),(3471,142,1306,0,94,759,1609,10,1069757199,1,141,'',0),(3472,142,1609,0,95,1306,1610,10,1069757199,1,141,'',0),(3473,142,1610,0,96,1609,1130,10,1069757199,1,141,'',0),(3474,142,1130,0,97,1610,1636,10,1069757199,1,141,'',0),(3475,142,1636,0,98,1130,937,10,1069757199,1,141,'',0),(3476,142,937,0,99,1636,1170,10,1069757199,1,141,'',0),(3477,142,1170,0,100,937,1637,10,1069757199,1,141,'',0),(3478,142,1637,0,101,1170,1638,10,1069757199,1,141,'',0),(3479,142,1638,0,102,1637,1273,10,1069757199,1,141,'',0),(3480,142,1273,0,103,1638,152,10,1069757199,1,141,'',0),(3481,142,152,0,104,1273,1514,10,1069757199,1,141,'',0),(3482,142,1514,0,105,152,1564,10,1069757199,1,141,'',0),(3483,142,1564,0,106,1514,1565,10,1069757199,1,141,'',0),(3484,142,1565,0,107,1564,1639,10,1069757199,1,141,'',0),(3485,142,1639,0,108,1565,1640,10,1069757199,1,141,'',0),(3486,142,1640,0,109,1639,1106,10,1069757199,1,141,'',0),(3487,142,1106,0,110,1640,1641,10,1069757199,1,141,'',0),(3488,142,1641,0,111,1106,1306,10,1069757199,1,141,'',0),(3489,142,1306,0,112,1641,1609,10,1069757199,1,141,'',0),(3490,142,1609,0,113,1306,1642,10,1069757199,1,141,'',0),(3491,142,1642,0,114,1609,1611,10,1069757199,1,141,'',0),(3492,142,1611,0,115,1642,1284,10,1069757199,1,141,'',0),(3493,142,1284,0,116,1611,1612,10,1069757199,1,141,'',0),(3494,142,1612,0,117,1284,1587,10,1069757199,1,141,'',0),(3495,142,1587,0,118,1612,1643,10,1069757199,1,141,'',0),(3496,142,1643,0,119,1587,74,10,1069757199,1,141,'',0),(3497,142,74,0,120,1643,89,10,1069757199,1,141,'',0),(3498,142,89,0,121,74,1632,10,1069757199,1,141,'',0),(3499,142,1632,0,122,89,1644,10,1069757199,1,141,'',0),(3500,142,1644,0,123,1632,33,10,1069757199,1,141,'',0),(3501,142,33,0,124,1644,1636,10,1069757199,1,141,'',0),(3502,142,1636,0,125,33,254,10,1069757199,1,141,'',0),(3503,142,254,0,126,1636,1645,10,1069757199,1,141,'',0),(3504,142,1645,0,127,254,1453,10,1069757199,1,141,'',0),(3505,142,1453,0,128,1645,1284,10,1069757199,1,141,'',0),(3506,142,1284,0,129,1453,102,10,1069757199,1,141,'',0),(3507,142,102,0,130,1284,1170,10,1069757199,1,141,'',0),(3508,142,1170,0,131,102,1646,10,1069757199,1,141,'',0),(3509,142,1646,0,132,1170,1391,10,1069757199,1,141,'',0),(3510,142,1391,0,133,1646,1076,10,1069757199,1,141,'',0),(3511,142,1076,0,134,1391,1525,10,1069757199,1,141,'',0),(3512,142,1525,0,135,1076,102,10,1069757199,1,141,'',0),(3513,142,102,0,136,1525,75,10,1069757199,1,141,'',0),(3514,142,75,0,137,102,1647,10,1069757199,1,141,'',0),(3515,142,1647,0,138,75,0,10,1069757199,1,141,'',0),(3519,135,936,0,3,381,937,1,1067936571,1,119,'',0),(3520,135,937,0,4,936,938,1,1067936571,1,119,'',0),(3521,135,938,0,5,937,465,1,1067936571,1,119,'',0),(3522,135,465,0,6,938,934,1,1067936571,1,119,'',0),(3523,135,934,0,7,465,73,1,1067936571,1,119,'',0),(3524,135,73,0,8,934,940,1,1067936571,1,119,'',0),(3525,135,940,0,9,73,0,1,1067936571,1,119,'',0),(3866,1,1749,0,40,102,752,1,1033917596,1,119,'',0),(3865,1,102,0,39,74,1749,1,1033917596,1,119,'',0),(3864,1,74,0,38,1748,102,1,1033917596,1,119,'',0),(3863,1,1748,0,37,752,74,1,1033917596,1,119,'',0),(3862,1,752,0,36,1251,1748,1,1033917596,1,119,'',0),(3861,1,1251,0,35,86,752,1,1033917596,1,119,'',0),(3860,1,86,0,34,1250,1251,1,1033917596,1,119,'',0),(3859,1,1250,0,33,33,86,1,1033917596,1,119,'',0),(3858,1,33,0,32,1747,1250,1,1033917596,1,119,'',0),(3857,1,1747,0,31,75,33,1,1033917596,1,119,'',0),(3856,1,75,0,30,934,1747,1,1033917596,1,119,'',0),(3855,1,934,0,29,1379,75,1,1033917596,1,119,'',0),(3854,1,1379,0,28,1746,934,1,1033917596,1,119,'',0),(3853,1,1746,0,27,938,1379,1,1033917596,1,119,'',0),(3852,1,938,0,26,33,1746,1,1033917596,1,119,'',0),(3851,1,33,0,25,1745,938,1,1033917596,1,119,'',0),(3850,1,1745,0,24,1744,33,1,1033917596,1,119,'',0),(3849,1,1744,0,23,752,1745,1,1033917596,1,119,'',0),(3848,1,752,0,22,1743,1744,1,1033917596,1,119,'',0),(3847,1,1743,0,21,1742,752,1,1033917596,1,119,'',0),(3846,1,1742,0,20,89,1743,1,1033917596,1,119,'',0),(3845,1,89,0,19,1741,1742,1,1033917596,1,119,'',0),(3844,1,1741,0,18,939,89,1,1033917596,1,119,'',0),(3843,1,939,0,17,33,1741,1,1033917596,1,119,'',0),(3842,1,33,0,16,935,939,1,1033917596,1,119,'',0),(3841,1,935,0,15,752,33,1,1033917596,1,119,'',0),(3840,1,752,0,14,940,935,1,1033917596,1,119,'',0),(3839,1,940,0,13,752,752,1,1033917596,1,119,'',0),(3838,1,752,0,12,934,940,1,1033917596,1,119,'',0),(3837,1,934,0,11,1740,752,1,1033917596,1,119,'',0),(3836,1,1740,0,10,1358,934,1,1033917596,1,119,'',0),(3835,1,1358,0,9,936,1740,1,1033917596,1,119,'',0),(3834,1,936,0,8,381,1358,1,1033917596,1,119,'',0),(3833,1,381,0,7,1739,936,1,1033917596,1,119,'',0),(3561,143,1657,0,0,0,1649,10,1069757424,1,140,'',0),(3562,143,1649,0,1,1657,86,10,1069757424,1,140,'',0),(3563,143,86,0,2,1649,1284,10,1069757424,1,141,'',0),(3564,143,1284,0,3,86,1658,10,1069757424,1,141,'',0),(3565,143,1658,0,4,1284,102,10,1069757424,1,141,'',0),(3566,143,102,0,5,1658,942,10,1069757424,1,141,'',0),(3567,143,942,0,6,102,1659,10,1069757424,1,141,'',0),(3568,143,1659,0,7,942,86,10,1069757424,1,141,'',0),(3569,143,86,0,8,1659,937,10,1069757424,1,141,'',0),(3570,143,937,0,9,86,1656,10,1069757424,1,141,'',0),(3571,143,1656,0,10,937,1660,10,1069757424,1,141,'',0),(3572,143,1660,0,11,1656,86,10,1069757424,1,141,'',0),(3573,143,86,0,12,1660,1358,10,1069757424,1,141,'',0),(3574,143,1358,0,13,86,102,10,1069757424,1,141,'',0),(3575,143,102,0,14,1358,1661,10,1069757424,1,141,'',0),(3576,143,1661,0,15,102,89,10,1069757424,1,141,'',0),(3577,143,89,0,16,1661,1662,10,1069757424,1,141,'',0),(3578,143,1662,0,17,89,1607,10,1069757424,1,141,'',0),(3579,143,1607,0,18,1662,1413,10,1069757424,1,141,'',0),(3580,143,1413,0,19,1607,1602,10,1069757424,1,141,'',0),(3581,143,1602,0,20,1413,89,10,1069757424,1,141,'',0),(3582,143,89,0,21,1602,1663,10,1069757424,1,141,'',0),(3583,143,1663,0,22,89,1602,10,1069757424,1,141,'',0),(3584,143,1602,0,23,1663,1106,10,1069757424,1,141,'',0),(3585,143,1106,0,24,1602,1664,10,1069757424,1,141,'',0),(3586,143,1664,0,25,1106,1665,10,1069757424,1,141,'',0),(3587,143,1665,0,26,1664,86,10,1069757424,1,141,'',0),(3588,143,86,0,27,1665,937,10,1069757424,1,141,'',0),(3589,143,937,0,28,86,1666,10,1069757424,1,141,'',0),(3590,143,1666,0,29,937,752,10,1069757424,1,141,'',0),(3591,143,752,0,30,1666,1667,10,1069757424,1,141,'',0),(3592,143,1667,0,31,752,1668,10,1069757424,1,141,'',0),(3593,143,1668,0,32,1667,1639,10,1069757424,1,141,'',0),(3594,143,1639,0,33,1668,33,10,1069757424,1,141,'',0),(3595,143,33,0,34,1639,942,10,1069757424,1,141,'',0),(3596,143,942,0,35,33,1669,10,1069757424,1,141,'',0),(3597,143,1669,0,36,942,33,10,1069757424,1,141,'',0),(3598,143,33,0,37,1669,1670,10,1069757424,1,141,'',0),(3599,143,1670,0,38,33,879,10,1069757424,1,141,'',0),(3600,143,879,0,39,1670,89,10,1069757424,1,141,'',0),(3601,143,89,0,40,879,1602,10,1069757424,1,141,'',0),(3602,143,1602,0,41,89,0,10,1069757424,1,141,'',0),(3603,144,1671,0,0,0,1395,10,1069757581,1,140,'',0),(3604,144,1395,0,1,1671,1651,10,1069757581,1,141,'',0),(3605,144,1651,0,2,1395,75,10,1069757581,1,141,'',0),(3606,144,75,0,3,1651,1283,10,1069757581,1,141,'',0),(3607,144,1283,0,4,75,77,10,1069757581,1,141,'',0),(3608,144,77,0,5,1283,1671,10,1069757581,1,141,'',0),(3609,144,1671,0,6,77,1304,10,1069757581,1,141,'',0),(3610,144,1304,0,7,1671,1672,10,1069757581,1,141,'',0),(3611,144,1672,0,8,1304,1673,10,1069757581,1,141,'',0),(3612,144,1673,0,9,1672,102,10,1069757581,1,141,'',0),(3613,144,102,0,10,1673,1674,10,1069757581,1,141,'',0),(3614,144,1674,0,11,102,752,10,1069757581,1,141,'',0),(3615,144,752,0,12,1674,1414,10,1069757581,1,141,'',0),(3616,144,1414,0,13,752,75,10,1069757581,1,141,'',0),(3617,144,75,0,14,1414,1443,10,1069757581,1,141,'',0),(3618,144,1443,0,15,75,1675,10,1069757581,1,141,'',0),(3619,144,1675,0,16,1443,1676,10,1069757581,1,141,'',0),(3620,144,1676,0,17,1675,86,10,1069757581,1,141,'',0),(3621,144,86,0,18,1676,1251,10,1069757581,1,141,'',0),(3622,144,1251,0,19,86,89,10,1069757581,1,141,'',0),(3623,144,89,0,20,1251,1671,10,1069757581,1,141,'',0),(3624,144,1671,0,21,89,1677,10,1069757581,1,141,'',0),(3625,144,1677,0,22,1671,75,10,1069757581,1,141,'',0),(3626,144,75,0,23,1677,1678,10,1069757581,1,141,'',0),(3627,144,1678,0,24,75,1284,10,1069757581,1,141,'',0),(3628,144,1284,0,25,1678,1679,10,1069757581,1,141,'',0),(3629,144,1679,0,26,1284,102,10,1069757581,1,141,'',0),(3630,144,102,0,27,1679,1680,10,1069757581,1,141,'',0),(3631,144,1680,0,28,102,936,10,1069757581,1,141,'',0),(3632,144,936,0,29,1680,254,10,1069757581,1,141,'',0),(3633,144,254,0,30,936,942,10,1069757581,1,141,'',0),(3634,144,942,0,31,254,1681,10,1069757581,1,141,'',0),(3635,144,1681,0,32,942,1250,10,1069757581,1,141,'',0),(3636,144,1250,0,33,1681,936,10,1069757581,1,141,'',0),(3637,144,936,0,34,1250,937,10,1069757581,1,141,'',0),(3638,144,937,0,35,936,1388,10,1069757581,1,141,'',0),(3639,144,1388,0,36,937,254,10,1069757581,1,141,'',0),(3640,144,254,0,37,1388,1671,10,1069757581,1,141,'',0),(3641,144,1671,0,38,254,75,10,1069757581,1,141,'',0),(3642,144,75,0,39,1671,1671,10,1069757581,1,141,'',0),(3643,144,1671,0,40,75,937,10,1069757581,1,141,'',0),(3644,144,937,0,41,1671,1682,10,1069757581,1,141,'',0),(3645,144,1682,0,42,937,1683,10,1069757581,1,141,'',0),(3646,144,1683,0,43,1682,1391,10,1069757581,1,141,'',0),(3647,144,1391,0,44,1683,1684,10,1069757581,1,141,'',0),(3648,144,1684,0,45,1391,33,10,1069757581,1,141,'',0),(3649,144,33,0,46,1684,1685,10,1069757581,1,141,'',0),(3650,144,1685,0,47,33,1071,10,1069757581,1,141,'',0),(3651,144,1071,0,48,1685,936,10,1069757581,1,141,'',0),(3652,144,936,0,49,1071,1686,10,1069757581,1,141,'',0),(3653,144,1686,0,50,936,1680,10,1069757581,1,141,'',0),(3654,144,1680,0,51,1686,102,10,1069757581,1,141,'',0),(3655,144,102,0,52,1680,1687,10,1069757581,1,141,'',0),(3656,144,1687,0,53,102,1607,10,1069757581,1,141,'',0),(3657,144,1607,0,54,1687,1688,10,1069757581,1,141,'',0),(3658,144,1688,0,55,1607,1689,10,1069757581,1,141,'',0),(3659,144,1689,0,56,1688,86,10,1069757581,1,141,'',0),(3660,144,86,0,57,1689,1358,10,1069757581,1,141,'',0),(3661,144,1358,0,58,86,1680,10,1069757581,1,141,'',0),(3662,144,1680,0,59,1358,936,10,1069757581,1,141,'',0),(3663,144,936,0,60,1680,1690,10,1069757581,1,141,'',0),(3664,144,1690,0,61,936,224,10,1069757581,1,141,'',0),(3665,144,224,0,62,1690,1691,10,1069757581,1,141,'',0),(3666,144,1691,0,63,224,1106,10,1069757581,1,141,'',0),(3667,144,1106,0,64,1691,942,10,1069757581,1,141,'',0),(3668,144,942,0,65,1106,1692,10,1069757581,1,141,'',0),(3669,144,1692,0,66,942,1607,10,1069757581,1,141,'',0),(3670,144,1607,0,67,1692,879,10,1069757581,1,141,'',0),(3671,144,879,0,68,1607,89,10,1069757581,1,141,'',0),(3672,144,89,0,69,879,195,10,1069757581,1,141,'',0),(3673,144,195,0,70,89,1402,10,1069757581,1,141,'',0),(3674,144,1402,0,71,195,102,10,1069757581,1,141,'',0),(3675,144,102,0,72,1402,89,10,1069757581,1,141,'',0),(3676,144,89,0,73,102,1693,10,1069757581,1,141,'',0),(3677,144,1693,0,74,89,1106,10,1069757581,1,141,'',0),(3678,144,1106,0,75,1693,1491,10,1069757581,1,141,'',0),(3679,144,1491,0,76,1106,86,10,1069757581,1,141,'',0),(3680,144,86,0,77,1491,937,10,1069757581,1,141,'',0),(3681,144,937,0,78,86,1601,10,1069757581,1,141,'',0),(3682,144,1601,0,79,937,1170,10,1069757581,1,141,'',0),(3683,144,1170,0,80,1601,1694,10,1069757581,1,141,'',0),(3684,144,1694,0,81,1170,102,10,1069757581,1,141,'',0),(3685,144,102,0,82,1694,1695,10,1069757581,1,141,'',0),(3686,144,1695,0,83,102,1696,10,1069757581,1,141,'',0),(3687,144,1696,0,84,1695,1106,10,1069757581,1,141,'',0),(3688,144,1106,0,85,1696,1356,10,1069757581,1,141,'',0),(3689,144,1356,0,86,1106,102,10,1069757581,1,141,'',0),(3690,144,102,0,87,1356,1697,10,1069757581,1,141,'',0),(3691,144,1697,0,88,102,1698,10,1069757581,1,141,'',0),(3692,144,1698,0,89,1697,254,10,1069757581,1,141,'',0),(3693,144,254,0,90,1698,1325,10,1069757581,1,141,'',0),(3694,144,1325,0,91,254,183,10,1069757581,1,141,'',0),(3695,144,183,0,92,1325,1491,10,1069757581,1,141,'',0),(3696,144,1491,0,93,183,1071,10,1069757581,1,141,'',0),(3697,144,1071,0,94,1491,936,10,1069757581,1,141,'',0),(3698,144,936,0,95,1071,1699,10,1069757581,1,141,'',0),(3699,144,1699,0,96,936,102,10,1069757581,1,141,'',0),(3700,144,102,0,97,1699,1656,10,1069757581,1,141,'',0),(3701,144,1656,0,98,102,1700,10,1069757581,1,141,'',0),(3702,144,1700,0,99,1656,152,10,1069757581,1,141,'',0),(3703,144,152,0,100,1700,75,10,1069757581,1,141,'',0),(3704,144,75,0,101,152,1514,10,1069757581,1,141,'',0),(3705,144,1514,0,102,75,1701,10,1069757581,1,141,'',0),(3706,144,1701,0,103,1514,0,10,1069757581,1,141,'',0),(3707,145,1325,0,0,0,1651,10,1069757729,1,140,'',0),(3708,145,1651,0,1,1325,75,10,1069757729,1,141,'',0),(3709,145,75,0,2,1651,1283,10,1069757729,1,141,'',0),(3710,145,1283,0,3,75,77,10,1069757729,1,141,'',0),(3711,145,77,0,4,1283,1702,10,1069757729,1,141,'',0),(3712,145,1702,0,5,77,33,10,1069757729,1,141,'',0),(3713,145,33,0,6,1702,1703,10,1069757729,1,141,'',0),(3714,145,1703,0,7,33,1704,10,1069757729,1,141,'',0),(3715,145,1704,0,8,1703,89,10,1069757729,1,141,'',0),(3716,145,89,0,9,1704,1403,10,1069757729,1,141,'',0),(3717,145,1403,0,10,89,102,10,1069757729,1,141,'',0),(3718,145,102,0,11,1403,1680,10,1069757729,1,141,'',0),(3719,145,1680,0,12,102,936,10,1069757729,1,141,'',0),(3720,145,936,0,13,1680,254,10,1069757729,1,141,'',0),(3721,145,254,0,14,936,942,10,1069757729,1,141,'',0),(3722,145,942,0,15,254,1705,10,1069757729,1,141,'',0),(3723,145,1705,0,16,942,86,10,1069757729,1,141,'',0),(3724,145,86,0,17,1705,33,10,1069757729,1,141,'',0),(3725,145,33,0,18,86,752,10,1069757729,1,141,'',0),(3726,145,752,0,19,33,1706,10,1069757729,1,141,'',0),(3727,145,1706,0,20,752,759,10,1069757729,1,141,'',0),(3728,145,759,0,21,1706,1707,10,1069757729,1,141,'',0),(3729,145,1707,0,22,759,1708,10,1069757729,1,141,'',0),(3730,145,1708,0,23,1707,1598,10,1069757729,1,141,'',0),(3731,145,1598,0,24,1708,1679,10,1069757729,1,141,'',0),(3732,145,1679,0,25,1598,102,10,1069757729,1,141,'',0),(3733,145,102,0,26,1679,1709,10,1069757729,1,141,'',0),(3734,145,1709,0,27,102,936,10,1069757729,1,141,'',0),(3735,145,936,0,28,1709,1710,10,1069757729,1,141,'',0),(3736,145,1710,0,29,936,1711,10,1069757729,1,141,'',0),(3737,145,1711,0,30,1710,108,10,1069757729,1,141,'',0),(3738,145,108,0,31,1711,1402,10,1069757729,1,141,'',0),(3739,145,1402,0,32,108,1712,10,1069757729,1,141,'',0),(3740,145,1712,0,33,1402,1713,10,1069757729,1,141,'',0),(3741,145,1713,0,34,1712,1680,10,1069757729,1,141,'',0),(3742,145,1680,0,35,1713,1714,10,1069757729,1,141,'',0),(3743,145,1714,0,36,1680,1680,10,1069757729,1,141,'',0),(3744,145,1680,0,37,1714,1715,10,1069757729,1,141,'',0),(3745,145,1715,0,38,1680,1716,10,1069757729,1,141,'',0),(3746,145,1716,0,39,1715,33,10,1069757729,1,141,'',0),(3747,145,33,0,40,1716,1242,10,1069757729,1,141,'',0),(3748,145,1242,0,41,33,1717,10,1069757729,1,141,'',0),(3749,145,1717,0,42,1242,86,10,1069757729,1,141,'',0),(3750,145,86,0,43,1717,1680,10,1069757729,1,141,'',0),(3751,145,1680,0,44,86,254,10,1069757729,1,141,'',0),(3752,145,254,0,45,1680,1713,10,1069757729,1,141,'',0),(3753,145,1713,0,46,254,1715,10,1069757729,1,141,'',0),(3754,145,1715,0,47,1713,1716,10,1069757729,1,141,'',0),(3755,145,1716,0,48,1715,1610,10,1069757729,1,141,'',0),(3756,145,1610,0,49,1716,1718,10,1069757729,1,141,'',0),(3757,145,1718,0,50,1610,86,10,1069757729,1,141,'',0),(3758,145,86,0,51,1718,1358,10,1069757729,1,141,'',0),(3759,145,1358,0,52,86,1601,10,1069757729,1,141,'',0),(3760,145,1601,0,53,1358,1719,10,1069757729,1,141,'',0),(3761,145,1719,0,54,1601,89,10,1069757729,1,141,'',0),(3762,145,89,0,55,1719,1720,10,1069757729,1,141,'',0),(3763,145,1720,0,56,89,1721,10,1069757729,1,141,'',0),(3764,145,1721,0,57,1720,1509,10,1069757729,1,141,'',0),(3765,145,1509,0,58,1721,1705,10,1069757729,1,141,'',0),(3766,145,1705,0,59,1509,1722,10,1069757729,1,141,'',0),(3767,145,1722,0,60,1705,1106,10,1069757729,1,141,'',0),(3768,145,1106,0,61,1722,1491,10,1069757729,1,141,'',0),(3769,145,1491,0,62,1106,1723,10,1069757729,1,141,'',0),(3770,145,1723,0,63,1491,221,10,1069757729,1,141,'',0),(3771,145,221,0,64,1723,1614,10,1069757729,1,141,'',0),(3772,145,1614,0,65,221,1250,10,1069757729,1,141,'',0),(3773,145,1250,0,66,1614,86,10,1069757729,1,141,'',0),(3774,145,86,0,67,1250,1358,10,1069757729,1,141,'',0),(3775,145,1358,0,68,86,1656,10,1069757729,1,141,'',0),(3776,145,1656,0,69,1358,77,10,1069757729,1,141,'',0),(3777,145,77,0,70,1656,936,10,1069757729,1,141,'',0),(3778,145,936,0,71,77,752,10,1069757729,1,141,'',0),(3779,145,752,0,72,936,1724,10,1069757729,1,141,'',0),(3780,145,1724,0,73,752,1725,10,1069757729,1,141,'',0),(3781,145,1725,0,74,1724,1726,10,1069757729,1,141,'',0),(3782,145,1726,0,75,1725,74,10,1069757729,1,141,'',0),(3783,145,74,0,76,1726,1727,10,1069757729,1,141,'',0),(3784,145,1727,0,77,74,33,10,1069757729,1,141,'',0),(3785,145,33,0,78,1727,752,10,1069757729,1,141,'',0),(3786,145,752,0,79,33,1728,10,1069757729,1,141,'',0),(3787,145,1728,0,80,752,1729,10,1069757729,1,141,'',0),(3788,145,1729,0,81,1728,1730,10,1069757729,1,141,'',0),(3789,145,1730,0,82,1729,77,10,1069757729,1,141,'',0),(3790,145,77,0,83,1730,1603,10,1069757729,1,141,'',0),(3791,145,1603,0,84,77,74,10,1069757729,1,141,'',0),(3792,145,74,0,85,1603,1731,10,1069757729,1,141,'',0),(3793,145,1731,0,86,74,1067,10,1069757729,1,141,'',0),(3794,145,1067,0,87,1731,221,10,1069757729,1,141,'',0),(3795,145,221,0,88,1067,1071,10,1069757729,1,141,'',0),(3796,145,1071,0,89,221,1317,10,1069757729,1,141,'',0),(3797,145,1317,0,90,1071,74,10,1069757729,1,141,'',0),(3798,145,74,0,91,1317,1732,10,1069757729,1,141,'',0),(3799,145,1732,0,92,74,86,10,1069757729,1,141,'',0),(3800,145,86,0,93,1732,1358,10,1069757729,1,141,'',0),(3801,145,1358,0,94,86,1656,10,1069757729,1,141,'',0),(3802,145,1656,0,95,1358,77,10,1069757729,1,141,'',0),(3803,145,77,0,96,1656,936,10,1069757729,1,141,'',0),(3804,145,936,0,97,77,0,10,1069757729,1,141,'',0),(3867,1,752,0,41,1749,1414,1,1033917596,1,119,'',0),(3868,1,1414,0,42,752,183,1,1033917596,1,119,'',0),(3869,1,183,0,43,1414,1750,1,1033917596,1,119,'',0),(3870,1,1750,0,44,183,254,1,1033917596,1,119,'',0),(3871,1,254,0,45,1750,75,1,1033917596,1,119,'',0),(3872,1,75,0,46,254,1751,1,1033917596,1,119,'',0),(3873,1,1751,0,47,75,1752,1,1033917596,1,119,'',0),(3874,1,1752,0,48,1751,1753,1,1033917596,1,119,'',0),(3875,1,1753,0,49,1752,33,1,1033917596,1,119,'',0),(3876,1,33,0,50,1753,935,1,1033917596,1,119,'',0),(3877,1,935,0,51,33,0,1,1033917596,1,119,'',0);
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
INSERT INTO ezsearch_word VALUES (6,'media',1),(7,'setup',3),(1050,'grouplist',1),(1049,'class',1),(1048,'classes',1),(11,'links',1),(1170,'be',7),(25,'content',4),(34,'feel',3),(33,'and',13),(32,'look',2),(37,'news',4),(49,'business',1),(1762,'systems',1),(50,'off',1),(51,'topic',1),(53,'reports',1),(54,'staff',1),(1357,'powerful',1),(1355,'just',1),(1356,'how',2),(1739,'mycompany',1),(934,'about',7),(67,'it',3),(1354,'realize',1),(1353,'when',2),(73,'this',3),(74,'is',9),(75,'the',11),(1352,'impressed',1),(77,'for',5),(81,'all',4),(1485,'seeming',1),(1484,'has',1),(1351,'seem',2),(86,'we',9),(1483,'reputation',1),(89,'a',9),(1482,'someone',1),(1481,'meanwhile',1),(1361,'benefits',1),(1350,'sure',1),(1480,'fragile',1),(1479,'mighty',1),(1478,'once',1),(1360,'mention',1),(102,'to',11),(1349,'they',1),(1348,'however',1),(1347,'licenses',1),(108,'from',6),(1346,'public',1),(1345,'software',1),(1344,'unfamiliar',1),(1343,'still',1),(1342,'linux',2),(1073,'feedback',2),(1738,'address',1),(1071,'if',5),(1525,'mail',2),(1737,'remember',1),(1736,'please',1),(1067,'contact',2),(1382,'anyway',1),(1381,'rest',1),(156,'an',3),(1383,'talking',1),(144,'ez',2),(1477,'20',1),(1380,'during',1),(1476,'scrape',1),(1379,'more',3),(1475,'manage',1),(1378,'certainly',1),(152,'of',7),(1377,'topics',1),(1341,'gnu',1),(180,'some',1),(1359,'not',1),(1340,'success',1),(183,'in',7),(1339,'enormous',1),(1338,'despite',1),(1337,'small',1),(1336,'large',1),(1335,'companies',1),(195,'new',3),(1334,'organizations',1),(1333,'various',1),(1332,'representing',1),(1134,'open',4),(1331,'them',1),(1330,'austria',1),(1130,'knowledge',3),(1329,'germany',1),(221,'us',4),(1328,'mostly',1),(381,'here',4),(224,'that',4),(1327,'visitors',1),(1326,'framework',2),(1124,'people',2),(235,'system',3),(1325,'development',5),(1324,'management',2),(1323,'source',3),(1322,'which',1),(1321,'product',1),(1320,'main',1),(1319,'who',2),(1318,'many',1),(1317,'there',2),(1316,'seems',1),(759,'have',6),(254,'with',8),(1315,'already',1),(1314,'visited',1),(1313,'lot',1),(1312,'day',2),(1311,'been',2),(1310,'barely',1),(752,'our',11),(1106,'on',7),(1309,'expectations',1),(750,'contains',1),(1308,'exceeding',1),(1307,'positive',1),(1306,'very',3),(1474,'even',1),(1473,'doesn',1),(1472,'inon',1),(879,'as',6),(1471,'whose',1),(1470,'tim',1),(1469,'but',1),(1468,'343',1),(1467,'poor',1),(292,'url',1),(1466,'manages',1),(1465,'cd',1),(1464,'too',1),(1463,'july',1),(1462,'babe',1),(1461,'fellow',1),(1460,'spot',1),(1459,'snagged',1),(1458,'should',2),(303,'cache',1),(1457,'surely',1),(1456,'234',1),(1455,'no',1),(1454,'come',1),(1453,'s',2),(1452,'go',1),(1451,'album',1),(1450,'her',1),(1449,'position',1),(1448,'chart',1),(1447,'lacklustre',1),(1446,'smarting',1),(849,'were',1),(1445,'mona',1),(1444,'charts',1),(1443,'best',2),(1442,'year',1),(1441,'music',1),(1440,'collection',1),(1439,'set',1),(1438,'100',1),(1261,'at',2),(1260,'today',1),(1259,'started',1),(1258,'joined',1),(1257,'smith',1),(1256,'mr',1),(465,'information',6),(1358,'can',6),(1305,'impressions',1),(1304,'first',2),(1303,'report',1),(1302,'text',1),(1095,'2003',3),(1301,'following',2),(1298,'site',1),(1299,'20th',1),(1300,'24th',1),(1297,'four',1),(1176,'community',2),(1296,'time',1),(1295,'5th',1),(1294,'held',1),(1293,'telecommunications',1),(1292,'technology',1),(1291,'trade',1),(1290,'international',1),(1289,'2003\"',1),(1761,'&copy',1),(1288,'\"top',1),(1287,'attending',1),(1286,'hall',1),(932,'corporate',2),(1376,'discuss',1),(1375,'face',1),(1374,'inspiring',1),(1373,'interesting',1),(1372,'up',2),(1371,'show',1),(1370,'happy',1),(1369,'re',1),(1368,'speaking',1),(1367,'achieve',1),(1366,'working',1),(1365,'minds',1),(1364,'creative',1),(1363,'huge',1),(1362,'having',1),(1277,'ranks',1),(1276,'into',2),(1275,'welcome',2),(1274,'name',1),(1273,'place',2),(1272,'nemos',1),(1271,'workplace',1),(1270,'previous',1),(1269,'his',1),(1268,'him',1),(1267,'hired',1),(1266,'matrix',1),(1265,'computer',1),(1264,'charge',1),(1263,'he',2),(1262,'firm',1),(1255,'indicates',1),(1254,'far',1),(1253,'so',1),(1252,'gotten',1),(1251,'offer',3),(1250,'what',4),(1249,'iformation',1),(1248,'easier',1),(1247,'hope',3),(1246,'i',2),(1245,'released',1),(1244,'now',2),(1243,'website',2),(1242,'solutions',3),(1594,'innovative',1),(1593,'accomplish',1),(1592,'internally',1),(1591,'both',1),(1590,'pull',1),(1589,'sharing',1),(1588,'ideas',1),(1587,'other',2),(1586,'welcoming',1),(1585,'heart',1),(1584,'mind',1),(1229,'meet',2),(1228,'always',2),(1583,'values',1),(1582,'knowledge\"',1),(1225,'share',2),(1581,'world',1),(1580,'around',1),(1579,'businesses',1),(1578,'helping',1),(1577,'team',1),(1576,'dedicated',1),(1575,'minded',1),(1574,'shall',1),(1573,'\"we',1),(1572,'vision',1),(1571,'1973',1),(1570,'may',2),(1569,'founded',1),(1568,'was',1),(1566,'223',1),(1567,'employees',1),(1565,'norway',2),(1564,'skien',2),(942,'your',6),(940,'company',5),(939,'services',2),(938,'find',4),(937,'will',9),(936,'you',10),(935,'products',3),(1052,'56',1),(1051,'edit',2),(1054,'urltranslator',1),(1053,'translator',1),(1735,'any',1),(1285,'reporting',1),(1284,'are',4),(1734,'below',1),(1283,'crew',4),(1282,'members',1),(1281,'week',1),(1280,'fair',1),(1076,'e',3),(1520,'form',1),(1733,'fill',1),(1180,'together',2),(1279,'top',2),(1278,'live',1),(1182,'great',2),(1183,'things',2),(1760,'copyright',1),(1384,'free',1),(1385,'mentioned',1),(1386,'don',1),(1387,'t',2),(1388,'get',2),(1389,'explained',1),(1390,'met',1),(1391,'by',4),(1392,'replies',1),(1393,'such',1),(1394,'\"amazing',1),(1395,'\"',2),(1396,'big',1),(1397,'smile',1),(1398,'\"i',1),(1399,'would',1),(1400,'pay',1),(1401,'money',1),(1402,'feature',3),(1403,'guy',2),(1404,'came',1),(1405,'one',1),(1406,'neighboring',1),(1407,'stands',1),(1408,'right',1),(1409,'after',1),(1410,'watching',1),(1411,'presentation',1),(1412,'interested',1),(1413,'potential',2),(1414,'customers',3),(1415,'willing',1),(1416,'spend',1),(1417,'millions',1),(1418,'rigid',1),(1419,'policy',1),(1420,'flexible',2),(1421,'eager',1),(1422,'talk',1),(1423,'wide',1),(1424,'range',1),(1425,'chance',1),(1426,'visit',1),(1427,'stop',1),(1428,'stand',1),(1429,'prepared',1),(1430,'everyone',2),(1431,'anybody',1),(1432,'sit',1),(1433,'down',1),(1434,'representatives',1),(1435,'receive',1),(1436,'hands',1),(1437,'demonstration',1),(1486,'really',1),(1487,'joap',1),(1488,'jackson',1),(1489,'romps',1),(1490,'1',1),(1491,'publishabc',3),(1492,'cms',1),(1493,'advanced',1),(1494,'functionality',1),(1495,'commerce',1),(1496,'sites',1),(1497,'forums',1),(1498,'picture',1),(1499,'galleries',1),(1500,'intranets',1),(1501,'much',1),(1502,'build',1),(1503,'dynamic',1),(1504,'websites',1),(1505,'fast',1),(1506,'reliable',1),(1507,'wants',1),(1508,'their',1),(1509,'web',2),(1510,'easily',1),(1511,'create',1),(1512,'publish',1),(1513,'sorts',1),(1514,'work',3),(1515,'done',1),(1516,'non',1),(1517,'technical',1),(1518,'persons',1),(1563,'located',1),(1562,'my',1),(1595,'creating',1),(1596,'career',1),(1597,'hiring',1),(1598,'developers',2),(1599,'part',1),(1600,'developing',1),(1601,'also',3),(1602,'customer',2),(1603,'projects',2),(1604,'either',1),(1605,'project',1),(1606,'leader',1),(1607,'or',3),(1608,'developer',1),(1609,'good',1),(1610,'programming',2),(1611,'skills',1),(1612,'required',1),(1613,'must',1),(1614,'know',2),(1615,'object',1),(1616,'oriented',1),(1617,'design',1),(1618,'using',1),(1619,'c',1),(1620,'php',1),(1621,'familiar',1),(1622,'uml',1),(1623,'sql',1),(1624,'xml',1),(1625,'xhtml',1),(1626,'soap',1),(1627,'rpc',1),(1628,'unix',1),(1629,'experience',1),(1630,'qt',1),(1631,'toolkit',1),(1632,'plus',1),(1633,'fresh',1),(1634,'graduates',1),(1635,'apply',1),(1636,'applications',1),(1637,'accepted',1),(1638,'continually',1),(1639,'conditions',2),(1640,'depending',1),(1641,'qualifications',1),(1642,'english',1),(1643,'languages',1),(1644,'questions',1),(1645,'cv',1),(1646,'sent',1),(1647,'boss',1),(1648,'general',1),(1649,'info',2),(1744,'digitised',1),(1651,'use',2),(1743,'through',1),(1742,'tour',1),(1741,'take',1),(1740,'read',1),(1656,'do',3),(1657,'shop',1),(1658,'committed',1),(1659,'satisfaction',1),(1660,'everything',1),(1661,'make',1),(1662,'present',1),(1663,'satisfied',1),(1664,'these',1),(1665,'pages',1),(1666,'outline',1),(1667,'terms',1),(1668,'&',1),(1669,'rights',1),(1670,'privacy',1),(1671,'support',1),(1672,'hand',1),(1673,'information\"',1),(1674,'guarantee',1),(1675,'possible',1),(1676,'result',1),(1677,'program',1),(1678,'professionals',1),(1679,'ready',2),(1680,'help',2),(1681,'problem',1),(1682,'cover',1),(1683,'answers',1),(1684,'email',1),(1685,'phone',1),(1686,'need',1),(1687,'configure',1),(1688,'develop',1),(1689,'features',1),(1690,'doing',1),(1691,'directly',1),(1692,'server',1),(1693,'distribution',1),(1694,'able',1),(1695,'give',1),(1696,'advise',1),(1697,'solve',1),(1698,'problems',1),(1699,'want',1),(1700,'most',1),(1701,'yourself',1),(1702,'developments',1),(1703,'enhancements',1),(1704,'hire',1),(1705,'solution',1),(1706,'friends',1),(1707,'highly',1),(1708,'skilled',1),(1709,'advice',1),(1710,'consulting',1),(1711,'ranges',1),(1712,'requests',1),(1713,'installation',1),(1714,'upgrade',1),(1715,'migration',1),(1716,'integration',1),(1717,'often',1),(1718,'etc',1),(1719,'deliver',1),(1720,'turn',1),(1721,'key',1),(1722,'based',1),(1723,'let',1),(1724,'standard',1),(1725,'hourly',1),(1726,'rate',1),(1727,'129',1),(1728,'minimum',1),(1729,'asking',1),(1730,'price',1),(1731,'2344',1),(1732,'something',1),(1745,'archive',1),(1746,'out',1),(1747,'comapny',1),(1748,'mission',1),(1749,'keep',1),(1750,'touch',1),(1751,'latest',1),(1752,'updates',1),(1753,'releases',1),(1759,'corporate_package',1),(1763,'1999',1);
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
INSERT INTO ezsession VALUES ('2f9ef2cdd661df1f4c266c242648f3ed',1070101457,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069842257;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('6b757a80dcd2886681c0a2dc420526f6',1070101565,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069842365;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('2b4c9f0ff56c9034bc3fa12d6f69089f',1070103513,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069844283;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069844283;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069844283;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"379\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"380\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069844283;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:380;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"300\";s:9:\"policy_id\";s:3:\"380\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:300;a:6:{i:0;a:3:{s:2:\"id\";s:3:\"587\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"590\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"591\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"592\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"19\";}i:4;a:3:{s:2:\"id\";s:3:\"588\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"2\";}i:5;a:3:{s:2:\"id\";s:3:\"589\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}');
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0),(37,'news/off_topic','c77d3081eac3bee15b0213bcc89b369b','content/view/full/57',1,0,0),(36,'news/business_news','bde42888705c25806fbe02b8570d055d','content/view/full/56',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,99,0),(38,'news/reports_','ac624940baa3e037e0467bf2db2743cb','content/view/full/58',1,39,0),(39,'news/reports','f3cbeafbd5dbf7477a9a803d47d4dcbb','content/view/full/58',1,0,0),(40,'news/staff_news','c50e4a6eb10a499c098857026282ceb4','content/view/full/59',1,0,0),(97,'information','bb3ccd5881d651448ded1dac904054ac','content/view/full/108',1,112,0),(111,'general_info/career','ea1c177fd7c868dc277cf107f26f668c','content/view/full/116',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,0,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,99,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(62,'news/business_news/ez_systems_reporting_live_from_munich','ddb9dceff37417877c5a030d5ca3e5b5','content/view/full/81',1,103,0),(105,'products/top_100_set','ef50df42a7d2fe7cff26830b3de58283','content/view/full/113',1,0,0),(64,'news/staff_news/mr_xxx_joined_us','6755615af39b3f3a145fd2a57a37809d','content/view/full/83',1,102,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(95,'products','86024cad1e83101d97359d7351051156','content/view/full/106',1,0,0),(96,'services','10cd395cf71c18328c863c08e78f3fd0','content/view/full/107',1,0,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(101,'news/off_topic/new_website','0c0589f38af62cd21f20d37e906bb5de','content/view/full/111',1,0,0),(102,'news/staff_news/mr_smith_joined_us','5f9ddd15b000a10b585cb57647e9f387','content/view/full/83',1,0,0),(103,'news/business_news/live_from_top_fair_2003','50fb0286625a02fd09c01b984cd985a9','content/view/full/81',1,0,0),(104,'news/reports/live_from_top_fair_2003','4577e798f398f1d9437338be5c9a83d5','content/view/full/112',1,0,0),(106,'products/publishabc','68eaddddc60054eef37a76b0fb429952','content/view/full/114',1,0,0),(108,'contact/contact_us','9f8a82d9487a7189ffee59fabbaceb89','content/view/full/110',1,117,0),(109,'about','46b3931b9959c927df4fc65fdee94b07','content/view/full/109',1,110,0),(110,'general_info/about','136f5f5c96ca444bbf07042b0597864d','content/view/full/109',1,0,0),(112,'general_info','fb56bf0921bfd932e96f9e2167884487','content/view/full/108',1,0,0),(113,'information/*','109c37699c2b15b48493419b460eb7c6','general_info/{1}',1,0,1),(114,'general_info/shop_info','2b3337b223f81931c8addf43fff88f69','content/view/full/117',1,0,0),(115,'services/support','5fb758997f086bac4a01a3058174bd1c','content/view/full/118',1,0,0),(116,'services/development','119490e5cab36746526adbd2432cfe75','content/view/full/119',1,0,0),(117,'contact_us','53a2c328fefc1efd85d75137a9d833ab','content/view/full/110',1,0,0);
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
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d');
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
INSERT INTO ezuser_role VALUES (29,1,10),(25,2,12),(32,24,13),(28,1,11),(34,1,13);
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

