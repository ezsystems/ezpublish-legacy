-- MySQL dump 10.2
--
-- Host: localhost    Database: nextgen
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
) TYPE=MyISAM CHARSET=latin1;

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
  PRIMARY KEY  (id)
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontent_translation'
--

/*!40000 ALTER TABLE ezcontent_translation DISABLE KEYS */;
LOCK TABLES ezcontent_translation WRITE;
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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentbrowserecent'
--

/*!40000 ALTER TABLE ezcontentbrowserecent DISABLE KEYS */;
LOCK TABLES ezcontentbrowserecent WRITE;
INSERT INTO ezcontentbrowserecent VALUES (3,14,44,1061223634,'News');
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentclass'
--

/*!40000 ALTER TABLE ezcontentclass DISABLE KEYS */;
LOCK TABLES ezcontentclass WRITE;
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1048494722),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1048494759),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(6,0,'Forum','forum','<name>',14,14,1052384723,1052384870),(7,0,'Forum message','forum_message','<topic>',14,14,1052384877,1052384943),(8,0,'Product','product','<title>',14,14,1052384951,1052385067),(9,0,'Product review','product_review','<title>',14,14,1052385080,1052385252),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(11,0,'Link','link','<title>',14,14,1052385361,1052385453),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(13,0,'Comment','comment','<subject>',14,14,1052385685,1052385756);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentclass_attribute'
--

/*!40000 ALTER TABLE ezcontentclass_attribute DISABLE KEYS */;
LOCK TABLES ezcontentclass_attribute WRITE;
INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',NULL,0,1),(128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',NULL,0,1),(125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',NULL,0,1),(132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',NULL,0,1);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentclass_classgroup'
--

/*!40000 ALTER TABLE ezcontentclass_classgroup DISABLE KEYS */;
LOCK TABLES ezcontentclass_classgroup WRITE;
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content');
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentclassgroup'
--

/*!40000 ALTER TABLE ezcontentclassgroup DISABLE KEYS */;
LOCK TABLES ezcontentclassgroup WRITE;
INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,14,1031216928,1033922106),(2,'Users',1,14,1031216941,1033922113),(3,'Media',8,14,1032009743,1033922120);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentobject'
--

/*!40000 ALTER TABLE ezcontentobject DISABLE KEYS */;
LOCK TABLES ezcontentobject WRITE;
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL),(4,14,2,3,'Users',1,0,0,0,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(40,14,2,4,'test test',1,0,1053613020,1053613020,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,1,1,'News',1,0,1061223553,1061223553,1,''),(43,14,1,1,'Files',1,0,1061223565,1061223565,1,''),(44,14,1,1,'Documents',1,0,1061223583,1061223583,1,''),(45,14,1,2,'Intranet news',1,0,1061223634,1061223634,1,'');
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
  PRIMARY KEY  (id,version),
  KEY ezcontentobject_attribute_contentobject_id (contentobject_id),
  KEY ezcontentobject_attribute_language_code (language_code),
  KEY sort_key_int (sort_key_int),
  KEY sort_key_string (sort_key_string)
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentobject_attribute'
--

/*!40000 ALTER TABLE ezcontentobject_attribute DISABLE KEYS */;
LOCK TABLES ezcontentobject_attribute WRITE;
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'My folder',NULL,NULL,0,0,''),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,''),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,''),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,''),(1,'eng-GB',2,1,4,'My folder',0,0,0,0,''),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',0,0,0,0,''),(21,'eng-GB',1,10,12,'',0,0,0,0,''),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,''),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,''),(20,'eng-GB',1,10,9,'User',0,0,0,0,''),(23,'eng-GB',1,11,7,'',0,0,0,0,''),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,''),(25,'eng-GB',1,12,7,'',0,0,0,0,''),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,''),(27,'eng-GB',1,13,7,'',0,0,0,0,''),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,''),(29,'eng-GB',1,14,9,'User',0,0,0,0,''),(30,'eng-GB',1,14,12,'',0,0,0,0,''),(95,'eng-GB',1,40,8,'test',0,0,0,0,''),(96,'eng-GB',1,40,9,'test',0,0,0,0,''),(97,'eng-GB',1,40,12,'',0,0,0,0,''),(98,'eng-GB',1,41,4,'Media',0,0,0,0,''),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,''),(100,'eng-GB',1,42,4,'News',0,0,0,0,'0'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Latest news on our intranet.</paragraph>\n</section>',1045487555,0,0,0,'0'),(102,'eng-GB',1,43,4,'Files',0,0,0,0,'0'),(103,'eng-GB',1,43,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'0'),(104,'eng-GB',1,44,4,'Documents',0,0,0,0,'0'),(105,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'0'),(106,'eng-GB',1,45,1,'Intranet news',0,0,0,0,'0'),(107,'eng-GB',1,45,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas augue leo, hendrerit vitae, dapibus ut, interdum at, ligula. Sed ut ipsum vitae sem rhoncus pulvinar. Quisque fringilla nibh at odio convallis mollis. </paragraph>\n</section>',1045487555,0,0,0,'0'),(108,'eng-GB',1,45,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n  <section>\n    <header>Nam sodales</header>\n    <paragraph>\n      <line> Nam sodales vestibulum erat. Nam non mauris quis dui porttitor iaculis. Nullam quam. Suspendisse at dolor. Aliquam erat volutpat. Morbi ac justo ut enim gravida gravida. Donec tempor, nunc id elementum nonummy, lorem ligula consectetuer felis, nec rutrum wisi nisl nec augue. Nam feugiat. Etiam iaculis elementum massa. Mauris vitae ante vel lacus eleifend vehicula. Nulla facilisi. Maecenas tincidunt consequat wisi. Curabitur in libero. Nunc interdum pretium urna.</line>\n      <line> Vivamus vel tortor. Cras nonummy facilisis ligula. Aliquam urna dolor, congue eu, fermentum a, faucibus ac, tortor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed varius risus sagittis elit egestas egestas. Aenean elit. Nulla magna. Mauris rutrum. Suspendisse dignissim eros in wisi pretium dignissim. Maecenas ante. Pellentesque mattis augue. Cras a ante. Nam non arcu. Donec cursus lacinia elit.</line>\n      <line> Duis eu nunc. Cras condimentum posuere lectus. Maecenas diam. Vestibulum vitae urna vitae lorem porttitor commodo. Integer vehicula tincidunt odio. Aliquam sit amet elit a nunc accumsan sagittis. Vivamus sollicitudin tempor magna. Suspendisse convallis nibh id ligula. Phasellus at velit sit amet sapien viverra ultricies. Etiam molestie euismod risus. Pellentesque non purus at erat dapibus aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Nullam ornare ullamcorper risus. Nulla nonummy sapien vitae enim. Nam velit mauris, interdum vel, ultricies ut, feugiat at, ligula. Praesent vehicula, arcu sed nonummy tempus, orci mauris fermentum neque, nec sagittis libero lacus et augue. Sed fermentum eleifend sapien. Fusce sem magna, pharetra sed, facilisis eu, interdum ut, neque. Suspendisse facilisis augue eget est. Fusce mollis vehicula felis. </line>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(109,'eng-GB',1,45,122,'',0,0,0,0,'0'),(110,'eng-GB',1,45,123,'',0,0,0,0,'0');
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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentobject_name'
--

/*!40000 ALTER TABLE ezcontentobject_name DISABLE KEYS */;
LOCK TABLES ezcontentobject_name WRITE;
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(40,'test test',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'News',1,'eng-GB','eng-GB'),(43,'Files',1,'eng-GB','eng-GB'),(44,'Documents',1,'eng-GB','eng-GB'),(45,'Intranet news',1,'eng-GB','eng-GB');
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentobject_tree'
--

/*!40000 ALTER TABLE ezcontentobject_tree DISABLE KEYS */;
LOCK TABLES ezcontentobject_tree WRITE;
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,1,1,1,'/1/2/',1,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15),(42,12,40,1,1,3,'/1/5/12/42/',9,1,0,'users/guest_accounts/test_test',42),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,2,42,1,1,2,'/1/2/44/',9,1,0,'news',44),(45,2,43,1,1,2,'/1/2/45/',9,1,0,'files',45),(46,45,44,1,1,3,'/1/2/45/46/',9,1,0,'files/documents',46),(47,44,45,1,1,3,'/1/2/44/47/',9,1,0,'news/intranet_news',47);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezcontentobject_version'
--

/*!40000 ALTER TABLE ezcontentobject_version DISABLE KEYS */;
LOCK TABLES ezcontentobject_version WRITE;
INSERT INTO ezcontentobject_version VALUES (1,1,14,1,0,0,1,1,0),(4,4,14,1,0,0,1,1,0),(436,1,14,2,1033919080,1033919080,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(471,40,14,1,1053613007,1053613020,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1061223534,1061223553,1,0,0),(474,43,14,1,1061223559,1061223565,1,0,0),(475,44,14,1,1061223573,1061223583,1,0,0),(476,45,14,1,1061223601,1061223634,1,0,0);
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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezenumvalue'
--

/*!40000 ALTER TABLE ezenumvalue DISABLE KEYS */;
LOCK TABLES ezenumvalue WRITE;
INSERT INTO ezenumvalue VALUES (2,136,0,'Ok','3',2),(1,136,0,'Poor','2',1),(3,136,0,'Good','5',3);
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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezgeneral_digest_user_settings'
--

/*!40000 ALTER TABLE ezgeneral_digest_user_settings DISABLE KEYS */;
LOCK TABLES ezgeneral_digest_user_settings WRITE;
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezimage'
--

/*!40000 ALTER TABLE ezimage DISABLE KEYS */;
LOCK TABLES ezimage WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezimage ENABLE KEYS */;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezimagevariation'
--

/*!40000 ALTER TABLE ezimagevariation DISABLE KEYS */;
LOCK TABLES ezimagevariation WRITE;
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
  PRIMARY KEY  (id)
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezinfocollection'
--

/*!40000 ALTER TABLE ezinfocollection DISABLE KEYS */;
LOCK TABLES ezinfocollection WRITE;
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
  PRIMARY KEY  (id)
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezinfocollection_attribute'
--

/*!40000 ALTER TABLE ezinfocollection_attribute DISABLE KEYS */;
LOCK TABLES ezinfocollection_attribute WRITE;
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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'eznode_assignment'
--

/*!40000 ALTER TABLE eznode_assignment DISABLE KEYS */;
LOCK TABLES eznode_assignment WRITE;
INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(145,1,2,1,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(181,40,1,12,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,2,9,1,1,0,0),(184,43,1,2,9,1,1,0,0),(185,44,1,45,9,1,1,0,0),(186,45,1,44,9,1,1,0,0);
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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'eznotificationevent'
--

/*!40000 ALTER TABLE eznotificationevent DISABLE KEYS */;
LOCK TABLES eznotificationevent WRITE;
INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',41,1,0,0,'','','',''),(2,0,'ezpublish',42,1,0,0,'','','',''),(3,0,'ezpublish',43,1,0,0,'','','',''),(4,0,'ezpublish',44,1,0,0,'','','',''),(5,0,'ezpublish',45,1,0,0,'','','','');
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
  PRIMARY KEY  (id,memento_key)
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
  PRIMARY KEY  (id)
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezpolicy'
--

/*!40000 ALTER TABLE ezpolicy DISABLE KEYS */;
LOCK TABLES ezpolicy WRITE;
INSERT INTO ezpolicy VALUES (317,3,'*','content','*'),(308,2,'*','*','*'),(326,1,'read','content',''),(325,1,'login','user','*'),(319,3,'login','user','*'),(323,5,'*','content','*'),(324,5,'login','user','*');
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezpolicy_limitation'
--

/*!40000 ALTER TABLE ezpolicy_limitation DISABLE KEYS */;
LOCK TABLES ezpolicy_limitation WRITE;
INSERT INTO ezpolicy_limitation VALUES (249,326,'Class',0,'read','content');
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezpolicy_limitation_value'
--

/*!40000 ALTER TABLE ezpolicy_limitation_value DISABLE KEYS */;
LOCK TABLES ezpolicy_limitation_value WRITE;
INSERT INTO ezpolicy_limitation_value VALUES (435,249,'1'),(436,249,'10'),(437,249,'10'),(438,249,'11'),(439,249,'11'),(440,249,'12'),(441,249,'12'),(442,249,'13'),(443,249,'13'),(444,249,'2'),(445,249,'2'),(446,249,'5'),(447,249,'5'),(448,249,'6'),(449,249,'6'),(450,249,'7'),(451,249,'7'),(452,249,'8'),(453,249,'8'),(454,249,'9'),(455,249,'9');
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezpreferences'
--

/*!40000 ALTER TABLE ezpreferences DISABLE KEYS */;
LOCK TABLES ezpreferences WRITE;
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
) TYPE=MyISAM CHARSET=latin1;

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
  PRIMARY KEY  (id)
) TYPE=MyISAM CHARSET=latin1;

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
  PRIMARY KEY  (id)
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezrole'
--

/*!40000 ALTER TABLE ezrole DISABLE KEYS */;
LOCK TABLES ezrole WRITE;
INSERT INTO ezrole VALUES (1,0,'Anonymous',''),(2,0,'Administrator','*'),(3,0,'Editor',''),(5,3,'Editor',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezrole ENABLE KEYS */;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezsearch_object_word_link'
--

/*!40000 ALTER TABLE ezsearch_object_word_link DISABLE KEYS */;
LOCK TABLES ezsearch_object_word_link WRITE;
INSERT INTO ezsearch_object_word_link VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0),(27,40,5,0,1,5,0,4,1053613020,2,9,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,8,1,1061223553,1,4,'',0),(30,42,8,0,1,7,7,1,1061223553,1,119,'',0),(31,42,7,0,2,8,9,1,1061223553,1,119,'',0),(32,42,9,0,3,7,10,1,1061223553,1,119,'',0),(33,42,10,0,4,9,11,1,1061223553,1,119,'',0),(34,42,11,0,5,10,0,1,1061223553,1,119,'',0),(35,43,12,0,0,0,0,1,1061223565,1,4,'',0),(36,44,13,0,0,0,0,1,1061223583,1,4,'',0),(37,45,11,0,0,0,7,2,1061223634,1,1,'',0),(38,45,7,0,1,11,14,2,1061223634,1,1,'',0),(39,45,14,0,2,7,15,2,1061223634,1,120,'',0),(40,45,15,0,3,14,16,2,1061223634,1,120,'',0),(41,45,16,0,4,15,17,2,1061223634,1,120,'',0),(42,45,17,0,5,16,18,2,1061223634,1,120,'',0),(43,45,18,0,6,17,19,2,1061223634,1,120,'',0),(44,45,19,0,7,18,20,2,1061223634,1,120,'',0),(45,45,20,0,8,19,21,2,1061223634,1,120,'',0),(46,45,21,0,9,20,22,2,1061223634,1,120,'',0),(47,45,22,0,10,21,23,2,1061223634,1,120,'',0),(48,45,23,0,11,22,24,2,1061223634,1,120,'',0),(49,45,24,0,12,23,25,2,1061223634,1,120,'',0),(50,45,25,0,13,24,26,2,1061223634,1,120,'',0),(51,45,26,0,14,25,27,2,1061223634,1,120,'',0),(52,45,27,0,15,26,28,2,1061223634,1,120,'',0),(53,45,28,0,16,27,29,2,1061223634,1,120,'',0),(54,45,29,0,17,28,30,2,1061223634,1,120,'',0),(55,45,30,0,18,29,31,2,1061223634,1,120,'',0),(56,45,31,0,19,30,32,2,1061223634,1,120,'',0),(57,45,32,0,20,31,28,2,1061223634,1,120,'',0),(58,45,28,0,21,32,15,2,1061223634,1,120,'',0),(59,45,15,0,22,28,26,2,1061223634,1,120,'',0),(60,45,26,0,23,15,33,2,1061223634,1,120,'',0),(61,45,33,0,24,26,34,2,1061223634,1,120,'',0),(62,45,34,0,25,33,35,2,1061223634,1,120,'',0),(63,45,35,0,26,34,36,2,1061223634,1,120,'',0),(64,45,36,0,27,35,37,2,1061223634,1,120,'',0),(65,45,37,0,28,36,38,2,1061223634,1,120,'',0),(66,45,38,0,29,37,30,2,1061223634,1,120,'',0),(67,45,30,0,30,38,39,2,1061223634,1,120,'',0),(68,45,39,0,31,30,40,2,1061223634,1,120,'',0),(69,45,40,0,32,39,41,2,1061223634,1,120,'',0),(70,45,41,0,33,40,42,2,1061223634,1,120,'',0),(71,45,42,0,34,41,19,2,1061223634,1,121,'',0),(72,45,19,0,35,42,43,2,1061223634,1,121,'',0),(73,45,43,0,36,19,42,2,1061223634,1,121,'',0),(74,45,42,0,37,43,19,2,1061223634,1,121,'',0),(75,45,19,0,38,42,43,2,1061223634,1,121,'',0),(76,45,43,0,39,19,44,2,1061223634,1,121,'',0),(77,45,44,0,40,43,45,2,1061223634,1,121,'',0),(78,45,45,0,41,44,46,2,1061223634,1,121,'',0),(79,45,46,0,42,45,47,2,1061223634,1,121,'',0),(80,45,47,0,43,46,21,2,1061223634,1,121,'',0),(81,45,21,0,44,47,48,2,1061223634,1,121,'',0),(82,45,48,0,45,21,49,2,1061223634,1,121,'',0),(83,45,49,0,46,48,50,2,1061223634,1,121,'',0),(84,45,50,0,47,49,51,2,1061223634,1,121,'',0),(85,45,51,0,48,50,52,2,1061223634,1,121,'',0),(86,45,52,0,49,51,53,2,1061223634,1,121,'',0),(87,45,53,0,50,52,22,2,1061223634,1,121,'',0),(88,45,22,0,51,53,54,2,1061223634,1,121,'',0),(89,45,54,0,52,22,55,2,1061223634,1,121,'',0),(90,45,55,0,53,54,56,2,1061223634,1,121,'',0),(91,45,56,0,54,55,56,2,1061223634,1,121,'',0),(92,45,56,0,55,56,41,2,1061223634,1,121,'',0),(93,45,41,0,56,56,57,2,1061223634,1,121,'',0),(94,45,57,0,57,41,35,2,1061223634,1,121,'',0),(95,45,35,0,58,57,58,2,1061223634,1,121,'',0),(96,45,58,0,59,35,59,2,1061223634,1,121,'',0),(97,45,59,0,60,58,60,2,1061223634,1,121,'',0),(98,45,60,0,61,59,61,2,1061223634,1,121,'',0),(99,45,61,0,62,60,15,2,1061223634,1,121,'',0),(100,45,15,0,63,61,62,2,1061223634,1,121,'',0),(101,45,62,0,64,15,57,2,1061223634,1,121,'',0),(102,45,57,0,65,62,63,2,1061223634,1,121,'',0),(103,45,63,0,66,57,64,2,1061223634,1,121,'',0),(104,45,64,0,67,63,65,2,1061223634,1,121,'',0),(105,45,65,0,68,64,66,2,1061223634,1,121,'',0),(106,45,66,0,69,65,67,2,1061223634,1,121,'',0),(107,45,67,0,70,66,68,2,1061223634,1,121,'',0),(108,45,68,0,71,67,69,2,1061223634,1,121,'',0),(109,45,69,0,72,68,70,2,1061223634,1,121,'',0),(110,45,70,0,73,69,71,2,1061223634,1,121,'',0),(111,45,71,0,74,70,72,2,1061223634,1,121,'',0),(112,45,72,0,75,71,60,2,1061223634,1,121,'',0),(113,45,60,0,76,72,73,2,1061223634,1,121,'',0),(114,45,73,0,77,60,32,2,1061223634,1,121,'',0),(115,45,32,0,78,73,34,2,1061223634,1,121,'',0),(116,45,34,0,79,32,74,2,1061223634,1,121,'',0),(117,45,74,0,80,34,75,2,1061223634,1,121,'',0),(118,45,75,0,81,74,76,2,1061223634,1,121,'',0),(119,45,76,0,82,75,44,2,1061223634,1,121,'',0),(120,45,44,0,83,76,76,2,1061223634,1,121,'',0),(121,45,76,0,84,44,77,2,1061223634,1,121,'',0),(122,45,77,0,85,76,78,2,1061223634,1,121,'',0),(123,45,78,0,86,77,79,2,1061223634,1,121,'',0),(124,45,79,0,87,78,80,2,1061223634,1,121,'',0),(125,45,80,0,88,79,81,2,1061223634,1,121,'',0),(126,45,81,0,89,80,28,2,1061223634,1,121,'',0),(127,45,28,0,90,81,60,2,1061223634,1,121,'',0),(128,45,60,0,91,28,82,2,1061223634,1,121,'',0),(129,45,82,0,92,60,83,2,1061223634,1,121,'',0),(130,45,83,0,93,82,49,2,1061223634,1,121,'',0),(131,45,49,0,94,83,78,2,1061223634,1,121,'',0),(132,45,78,0,95,49,53,2,1061223634,1,121,'',0),(133,45,53,0,96,78,84,2,1061223634,1,121,'',0),(134,45,84,0,97,53,85,2,1061223634,1,121,'',0),(135,45,85,0,98,84,86,2,1061223634,1,121,'',0),(136,45,86,0,99,85,87,2,1061223634,1,121,'',0),(137,45,87,0,100,86,88,2,1061223634,1,121,'',0),(138,45,88,0,101,87,89,2,1061223634,1,121,'',0),(139,45,89,0,102,88,90,2,1061223634,1,121,'',0),(140,45,90,0,103,89,91,2,1061223634,1,121,'',0),(141,45,91,0,104,90,83,2,1061223634,1,121,'',0),(142,45,83,0,105,91,28,2,1061223634,1,121,'',0),(143,45,28,0,106,83,72,2,1061223634,1,121,'',0),(144,45,72,0,107,28,92,2,1061223634,1,121,'',0),(145,45,92,0,108,72,93,2,1061223634,1,121,'',0),(146,45,93,0,109,92,28,2,1061223634,1,121,'',0),(147,45,28,0,110,93,53,2,1061223634,1,121,'',0),(148,45,53,0,111,28,23,2,1061223634,1,121,'',0),(149,45,23,0,112,53,94,2,1061223634,1,121,'',0),(150,45,94,0,113,23,26,2,1061223634,1,121,'',0),(151,45,26,0,114,94,95,2,1061223634,1,121,'',0),(152,45,95,0,115,26,96,2,1061223634,1,121,'',0),(153,45,96,0,116,95,97,2,1061223634,1,121,'',0),(154,45,97,0,117,96,26,2,1061223634,1,121,'',0),(155,45,26,0,118,97,98,2,1061223634,1,121,'',0),(156,45,98,0,119,26,99,2,1061223634,1,121,'',0),(157,45,99,0,120,98,100,2,1061223634,1,121,'',0),(158,45,100,0,121,99,28,2,1061223634,1,121,'',0),(159,45,28,0,122,100,17,2,1061223634,1,121,'',0),(160,45,17,0,123,28,18,2,1061223634,1,121,'',0),(161,45,18,0,124,17,101,2,1061223634,1,121,'',0),(162,45,101,0,125,18,102,2,1061223634,1,121,'',0),(163,45,102,0,126,101,103,2,1061223634,1,121,'',0),(164,45,103,0,127,102,50,2,1061223634,1,121,'',0),(165,45,50,0,128,103,104,2,1061223634,1,121,'',0),(166,45,104,0,129,50,87,2,1061223634,1,121,'',0),(167,45,87,0,130,104,91,2,1061223634,1,121,'',0),(168,45,91,0,131,87,105,2,1061223634,1,121,'',0),(169,45,105,0,132,91,106,2,1061223634,1,121,'',0),(170,45,106,0,133,105,35,2,1061223634,1,121,'',0),(171,45,35,0,134,106,107,2,1061223634,1,121,'',0),(172,45,107,0,135,35,76,2,1061223634,1,121,'',0),(173,45,76,0,136,107,71,2,1061223634,1,121,'',0),(174,45,71,0,137,76,84,2,1061223634,1,121,'',0),(175,45,84,0,138,71,108,2,1061223634,1,121,'',0),(176,45,108,0,139,84,91,2,1061223634,1,121,'',0),(177,45,91,0,140,108,109,2,1061223634,1,121,'',0),(178,45,109,0,141,91,63,2,1061223634,1,121,'',0),(179,45,63,0,142,109,110,2,1061223634,1,121,'',0),(180,45,110,0,143,63,96,2,1061223634,1,121,'',0),(181,45,96,0,144,110,17,2,1061223634,1,121,'',0),(182,45,17,0,145,96,18,2,1061223634,1,121,'',0),(183,45,18,0,146,17,59,2,1061223634,1,121,'',0),(184,45,59,0,147,18,106,2,1061223634,1,121,'',0),(185,45,106,0,148,59,111,2,1061223634,1,121,'',0),(186,45,111,0,149,106,24,2,1061223634,1,121,'',0),(187,45,24,0,150,111,112,2,1061223634,1,121,'',0),(188,45,112,0,151,24,113,2,1061223634,1,121,'',0),(189,45,113,0,152,112,32,2,1061223634,1,121,'',0),(190,45,32,0,153,113,112,2,1061223634,1,121,'',0),(191,45,112,0,154,32,54,2,1061223634,1,121,'',0),(192,45,54,0,155,112,114,2,1061223634,1,121,'',0),(193,45,114,0,156,54,115,2,1061223634,1,121,'',0),(194,45,115,0,157,114,116,2,1061223634,1,121,'',0),(195,45,116,0,158,115,88,2,1061223634,1,121,'',0),(196,45,88,0,159,116,61,2,1061223634,1,121,'',0),(197,45,61,0,160,88,117,2,1061223634,1,121,'',0),(198,45,117,0,161,61,60,2,1061223634,1,121,'',0),(199,45,60,0,162,117,81,2,1061223634,1,121,'',0),(200,45,81,0,163,60,66,2,1061223634,1,121,'',0),(201,45,66,0,164,81,118,2,1061223634,1,121,'',0),(202,45,118,0,165,66,119,2,1061223634,1,121,'',0),(203,45,119,0,166,118,72,2,1061223634,1,121,'',0),(204,45,72,0,167,119,72,2,1061223634,1,121,'',0),(205,45,72,0,168,72,78,2,1061223634,1,121,'',0),(206,45,78,0,169,72,61,2,1061223634,1,121,'',0),(207,45,61,0,170,78,30,2,1061223634,1,121,'',0),(208,45,30,0,171,61,27,2,1061223634,1,121,'',0),(209,45,27,0,172,30,15,2,1061223634,1,121,'',0),(210,45,15,0,173,27,76,2,1061223634,1,121,'',0),(211,45,76,0,174,15,26,2,1061223634,1,121,'',0),(212,45,26,0,175,76,108,2,1061223634,1,121,'',0),(213,45,108,0,176,26,32,2,1061223634,1,121,'',0),(214,45,32,0,177,108,120,2,1061223634,1,121,'',0),(215,45,120,0,178,32,121,2,1061223634,1,121,'',0),(216,45,121,0,179,120,15,2,1061223634,1,121,'',0),(217,45,15,0,180,121,32,2,1061223634,1,121,'',0),(218,45,32,0,181,15,122,2,1061223634,1,121,'',0),(219,45,122,0,182,32,72,2,1061223634,1,121,'',0),(220,45,72,0,183,122,57,2,1061223634,1,121,'',0),(221,45,57,0,184,72,50,2,1061223634,1,121,'',0),(222,45,50,0,185,57,22,2,1061223634,1,121,'',0),(223,45,22,0,186,50,66,2,1061223634,1,121,'',0),(224,45,66,0,187,22,64,2,1061223634,1,121,'',0),(225,45,64,0,188,66,90,2,1061223634,1,121,'',0),(226,45,90,0,189,64,104,2,1061223634,1,121,'',0),(227,45,104,0,190,90,123,2,1061223634,1,121,'',0),(228,45,123,0,191,104,32,2,1061223634,1,121,'',0),(229,45,32,0,192,123,101,2,1061223634,1,121,'',0),(230,45,101,0,193,32,81,2,1061223634,1,121,'',0),(231,45,81,0,194,101,78,2,1061223634,1,121,'',0),(232,45,78,0,195,81,32,2,1061223634,1,121,'',0),(233,45,32,0,196,78,60,2,1061223634,1,121,'',0),(234,45,60,0,197,32,91,2,1061223634,1,121,'',0),(235,45,91,0,198,60,52,2,1061223634,1,121,'',0),(236,45,52,0,199,91,58,2,1061223634,1,121,'',0),(237,45,58,0,200,52,49,2,1061223634,1,121,'',0),(238,45,49,0,201,58,42,2,1061223634,1,121,'',0),(239,45,42,0,202,49,80,2,1061223634,1,121,'',0),(240,45,80,0,203,42,124,2,1061223634,1,121,'',0),(241,45,124,0,204,80,39,2,1061223634,1,121,'',0),(242,45,39,0,205,124,71,2,1061223634,1,121,'',0),(243,45,71,0,206,39,52,2,1061223634,1,121,'',0),(244,45,52,0,207,71,80,2,1061223634,1,121,'',0),(245,45,80,0,208,52,117,2,1061223634,1,121,'',0),(246,45,117,0,209,80,125,2,1061223634,1,121,'',0),(247,45,125,0,210,117,117,2,1061223634,1,121,'',0),(248,45,117,0,211,125,125,2,1061223634,1,121,'',0),(249,45,125,0,212,117,60,2,1061223634,1,121,'',0),(250,45,60,0,213,125,126,2,1061223634,1,121,'',0),(251,45,126,0,214,60,117,2,1061223634,1,121,'',0),(252,45,117,0,215,126,91,2,1061223634,1,121,'',0),(253,45,91,0,216,117,114,2,1061223634,1,121,'',0),(254,45,114,0,217,91,75,2,1061223634,1,121,'',0),(255,45,75,0,218,114,101,2,1061223634,1,121,'',0),(256,45,101,0,219,75,95,2,1061223634,1,121,'',0),(257,45,95,0,220,101,127,2,1061223634,1,121,'',0),(258,45,127,0,221,95,128,2,1061223634,1,121,'',0),(259,45,128,0,222,127,89,2,1061223634,1,121,'',0),(260,45,89,0,223,128,74,2,1061223634,1,121,'',0),(261,45,74,0,224,89,30,2,1061223634,1,121,'',0),(262,45,30,0,225,74,16,2,1061223634,1,121,'',0),(263,45,16,0,226,30,115,2,1061223634,1,121,'',0),(264,45,115,0,227,16,126,2,1061223634,1,121,'',0),(265,45,126,0,228,115,47,2,1061223634,1,121,'',0),(266,45,47,0,229,126,90,2,1061223634,1,121,'',0),(267,45,90,0,230,47,88,2,1061223634,1,121,'',0),(268,45,88,0,231,90,73,2,1061223634,1,121,'',0),(269,45,73,0,232,88,28,2,1061223634,1,121,'',0),(270,45,28,0,233,73,116,2,1061223634,1,121,'',0),(271,45,116,0,234,28,129,2,1061223634,1,121,'',0),(272,45,129,0,235,116,129,2,1061223634,1,121,'',0),(273,45,129,0,236,129,130,2,1061223634,1,121,'',0),(274,45,130,0,237,129,131,2,1061223634,1,121,'',0),(275,45,131,0,238,130,98,2,1061223634,1,121,'',0),(276,45,98,0,239,131,48,2,1061223634,1,121,'',0),(277,45,48,0,240,98,132,2,1061223634,1,121,'',0),(278,45,132,0,241,48,133,2,1061223634,1,121,'',0),(279,45,133,0,242,132,14,2,1061223634,1,121,'',0),(280,45,14,0,243,133,31,2,1061223634,1,121,'',0),(281,45,31,0,244,14,19,2,1061223634,1,121,'',0),(282,45,19,0,245,31,108,2,1061223634,1,121,'',0),(283,45,108,0,246,19,44,2,1061223634,1,121,'',0),(284,45,44,0,247,108,82,2,1061223634,1,121,'',0),(285,45,82,0,248,44,134,2,1061223634,1,121,'',0),(286,45,134,0,249,82,135,2,1061223634,1,121,'',0),(287,45,135,0,250,134,44,2,1061223634,1,121,'',0),(288,45,44,0,251,135,23,2,1061223634,1,121,'',0),(289,45,23,0,252,44,117,2,1061223634,1,121,'',0),(290,45,117,0,253,23,100,2,1061223634,1,121,'',0),(291,45,100,0,254,117,102,2,1061223634,1,121,'',0),(292,45,102,0,255,100,127,2,1061223634,1,121,'',0),(293,45,127,0,256,102,132,2,1061223634,1,121,'',0),(294,45,132,0,257,127,123,2,1061223634,1,121,'',0),(295,45,123,0,258,132,114,2,1061223634,1,121,'',0),(296,45,114,0,259,123,26,2,1061223634,1,121,'',0),(297,45,26,0,260,114,61,2,1061223634,1,121,'',0),(298,45,61,0,261,26,112,2,1061223634,1,121,'',0),(299,45,112,0,262,61,43,2,1061223634,1,121,'',0),(300,45,43,0,263,112,92,2,1061223634,1,121,'',0),(301,45,92,0,264,43,136,2,1061223634,1,121,'',0),(302,45,136,0,265,92,50,2,1061223634,1,121,'',0),(303,45,50,0,266,136,137,2,1061223634,1,121,'',0),(304,45,137,0,267,50,22,2,1061223634,1,121,'',0),(305,45,22,0,268,137,138,2,1061223634,1,121,'',0),(306,45,138,0,269,22,93,2,1061223634,1,121,'',0),(307,45,93,0,270,138,134,2,1061223634,1,121,'',0),(308,45,134,0,271,93,99,2,1061223634,1,121,'',0),(309,45,99,0,272,134,57,2,1061223634,1,121,'',0),(310,45,57,0,273,99,113,2,1061223634,1,121,'',0),(311,45,113,0,274,57,98,2,1061223634,1,121,'',0),(312,45,98,0,275,113,29,2,1061223634,1,121,'',0),(313,45,29,0,276,98,77,2,1061223634,1,121,'',0),(314,45,77,0,277,29,105,2,1061223634,1,121,'',0),(315,45,105,0,278,77,46,2,1061223634,1,121,'',0),(316,45,46,0,279,105,112,2,1061223634,1,121,'',0),(317,45,112,0,280,46,139,2,1061223634,1,121,'',0),(318,45,139,0,281,112,106,2,1061223634,1,121,'',0),(319,45,106,0,282,139,133,2,1061223634,1,121,'',0),(320,45,133,0,283,106,122,2,1061223634,1,121,'',0),(321,45,122,0,284,133,31,2,1061223634,1,121,'',0),(322,45,31,0,285,122,115,2,1061223634,1,121,'',0),(323,45,115,0,286,31,105,2,1061223634,1,121,'',0),(324,45,105,0,287,115,16,2,1061223634,1,121,'',0),(325,45,16,0,288,105,140,2,1061223634,1,121,'',0),(326,45,140,0,289,16,84,2,1061223634,1,121,'',0),(327,45,84,0,290,140,103,2,1061223634,1,121,'',0),(328,45,103,0,291,84,58,2,1061223634,1,121,'',0),(329,45,58,0,292,103,63,2,1061223634,1,121,'',0),(330,45,63,0,293,58,88,2,1061223634,1,121,'',0),(331,45,88,0,294,63,139,2,1061223634,1,121,'',0),(332,45,139,0,295,88,141,2,1061223634,1,121,'',0),(333,45,141,0,296,139,142,2,1061223634,1,121,'',0),(334,45,142,0,297,141,143,2,1061223634,1,121,'',0),(335,45,143,0,298,142,144,2,1061223634,1,121,'',0),(336,45,144,0,299,143,66,2,1061223634,1,121,'',0),(337,45,66,0,300,144,145,2,1061223634,1,121,'',0),(338,45,145,0,301,66,146,2,1061223634,1,121,'',0),(339,45,146,0,302,145,147,2,1061223634,1,121,'',0),(340,45,147,0,303,146,148,2,1061223634,1,121,'',0),(341,45,148,0,304,147,149,2,1061223634,1,121,'',0),(342,45,149,0,305,148,150,2,1061223634,1,121,'',0),(343,45,150,0,306,149,151,2,1061223634,1,121,'',0),(344,45,151,0,307,150,32,2,1061223634,1,121,'',0),(345,45,32,0,308,151,51,2,1061223634,1,121,'',0),(346,45,51,0,309,32,83,2,1061223634,1,121,'',0),(347,45,83,0,310,51,152,2,1061223634,1,121,'',0),(348,45,152,0,311,83,21,2,1061223634,1,121,'',0),(349,45,21,0,312,152,97,2,1061223634,1,121,'',0),(350,45,97,0,313,21,97,2,1061223634,1,121,'',0),(351,45,97,0,314,97,153,2,1061223634,1,121,'',0),(352,45,153,0,315,97,21,2,1061223634,1,121,'',0),(353,45,21,0,316,153,50,2,1061223634,1,121,'',0),(354,45,50,0,317,21,87,2,1061223634,1,121,'',0),(355,45,87,0,318,50,114,2,1061223634,1,121,'',0),(356,45,114,0,319,87,82,2,1061223634,1,121,'',0),(357,45,82,0,320,114,74,2,1061223634,1,121,'',0),(358,45,74,0,321,82,80,2,1061223634,1,121,'',0),(359,45,80,0,322,74,154,2,1061223634,1,121,'',0),(360,45,154,0,323,80,57,2,1061223634,1,121,'',0),(361,45,57,0,324,154,134,2,1061223634,1,121,'',0),(362,45,134,0,325,57,77,2,1061223634,1,121,'',0),(363,45,77,0,326,134,80,2,1061223634,1,121,'',0),(364,45,80,0,327,77,22,2,1061223634,1,121,'',0),(365,45,22,0,328,80,61,2,1061223634,1,121,'',0),(366,45,61,0,329,22,96,2,1061223634,1,121,'',0),(367,45,96,0,330,61,56,2,1061223634,1,121,'',0),(368,45,56,0,331,96,23,2,1061223634,1,121,'',0),(369,45,23,0,332,56,106,2,1061223634,1,121,'',0),(370,45,106,0,333,23,58,2,1061223634,1,121,'',0),(371,45,58,0,334,106,61,2,1061223634,1,121,'',0),(372,45,61,0,335,58,117,2,1061223634,1,121,'',0),(373,45,117,0,336,61,91,2,1061223634,1,121,'',0),(374,45,91,0,337,117,72,2,1061223634,1,121,'',0),(375,45,72,0,338,91,130,2,1061223634,1,121,'',0),(376,45,130,0,339,72,155,2,1061223634,1,121,'',0),(377,45,155,0,340,130,156,2,1061223634,1,121,'',0),(378,45,156,0,341,155,21,2,1061223634,1,121,'',0),(379,45,21,0,342,156,157,2,1061223634,1,121,'',0),(380,45,157,0,343,21,84,2,1061223634,1,121,'',0),(381,45,84,0,344,157,98,2,1061223634,1,121,'',0),(382,45,98,0,345,84,106,2,1061223634,1,121,'',0),(383,45,106,0,346,98,158,2,1061223634,1,121,'',0),(384,45,158,0,347,106,68,2,1061223634,1,121,'',0),(385,45,68,0,348,158,159,2,1061223634,1,121,'',0),(386,45,159,0,349,68,22,2,1061223634,1,121,'',0),(387,45,22,0,350,159,109,2,1061223634,1,121,'',0),(388,45,109,0,351,22,60,2,1061223634,1,121,'',0),(389,45,60,0,352,109,26,2,1061223634,1,121,'',0),(390,45,26,0,353,60,105,2,1061223634,1,121,'',0),(391,45,105,0,354,26,26,2,1061223634,1,121,'',0),(392,45,26,0,355,105,14,2,1061223634,1,121,'',0),(393,45,14,0,356,26,95,2,1061223634,1,121,'',0),(394,45,95,0,357,14,160,2,1061223634,1,121,'',0),(395,45,160,0,358,95,161,2,1061223634,1,121,'',0),(396,45,161,0,359,160,136,2,1061223634,1,121,'',0),(397,45,136,0,360,161,138,2,1061223634,1,121,'',0),(398,45,138,0,361,136,39,2,1061223634,1,121,'',0),(399,45,39,0,362,138,115,2,1061223634,1,121,'',0),(400,45,115,0,363,39,17,2,1061223634,1,121,'',0),(401,45,17,0,364,115,18,2,1061223634,1,121,'',0),(402,45,18,0,365,17,21,2,1061223634,1,121,'',0),(403,45,21,0,366,18,58,2,1061223634,1,121,'',0),(404,45,58,0,367,21,98,2,1061223634,1,121,'',0),(405,45,98,0,368,58,162,2,1061223634,1,121,'',0),(406,45,162,0,369,98,152,2,1061223634,1,121,'',0),(407,45,152,0,370,162,46,2,1061223634,1,121,'',0),(408,45,46,0,371,152,163,2,1061223634,1,121,'',0),(409,45,163,0,372,46,131,2,1061223634,1,121,'',0),(410,45,131,0,373,163,87,2,1061223634,1,121,'',0),(411,45,87,0,374,131,74,2,1061223634,1,121,'',0),(412,45,74,0,375,87,40,2,1061223634,1,121,'',0),(413,45,40,0,376,74,38,2,1061223634,1,121,'',0),(414,45,38,0,377,40,48,2,1061223634,1,121,'',0),(415,45,48,0,378,38,31,2,1061223634,1,121,'',0),(416,45,31,0,379,48,71,2,1061223634,1,121,'',0),(417,45,71,0,380,31,30,2,1061223634,1,121,'',0),(418,45,30,0,381,71,86,2,1061223634,1,121,'',0),(419,45,86,0,382,30,17,2,1061223634,1,121,'',0),(420,45,17,0,383,86,18,2,1061223634,1,121,'',0),(421,45,18,0,384,17,54,2,1061223634,1,121,'',0),(422,45,54,0,385,18,110,2,1061223634,1,121,'',0),(423,45,110,0,386,54,164,2,1061223634,1,121,'',0),(424,45,164,0,387,110,102,2,1061223634,1,121,'',0),(425,45,102,0,388,164,165,2,1061223634,1,121,'',0),(426,45,165,0,389,102,111,2,1061223634,1,121,'',0),(427,45,111,0,390,165,83,2,1061223634,1,121,'',0),(428,45,83,0,391,111,96,2,1061223634,1,121,'',0),(429,45,96,0,392,83,91,2,1061223634,1,121,'',0),(430,45,91,0,393,96,49,2,1061223634,1,121,'',0),(431,45,49,0,394,91,30,2,1061223634,1,121,'',0),(432,45,30,0,395,49,126,2,1061223634,1,121,'',0),(433,45,126,0,396,30,27,2,1061223634,1,121,'',0),(434,45,27,0,397,126,115,2,1061223634,1,121,'',0),(435,45,115,0,398,27,166,2,1061223634,1,121,'',0),(436,45,166,0,399,115,167,2,1061223634,1,121,'',0),(437,45,167,0,400,166,168,2,1061223634,1,121,'',0),(438,45,168,0,401,167,169,2,1061223634,1,121,'',0),(439,45,169,0,402,168,170,2,1061223634,1,121,'',0),(440,45,170,0,403,169,171,2,1061223634,1,121,'',0),(441,45,171,0,404,170,172,2,1061223634,1,121,'',0),(442,45,172,0,405,171,173,2,1061223634,1,121,'',0),(443,45,173,0,406,172,174,2,1061223634,1,121,'',0),(444,45,174,0,407,173,175,2,1061223634,1,121,'',0),(445,45,175,0,408,174,173,2,1061223634,1,121,'',0),(446,45,173,0,409,175,176,2,1061223634,1,121,'',0),(447,45,176,0,410,173,177,2,1061223634,1,121,'',0),(448,45,177,0,411,176,128,2,1061223634,1,121,'',0),(449,45,128,0,412,177,104,2,1061223634,1,121,'',0),(450,45,104,0,413,128,178,2,1061223634,1,121,'',0),(451,45,178,0,414,104,83,2,1061223634,1,121,'',0),(452,45,83,0,415,178,50,2,1061223634,1,121,'',0),(453,45,50,0,416,83,133,2,1061223634,1,121,'',0),(454,45,133,0,417,50,54,2,1061223634,1,121,'',0),(455,45,54,0,418,133,26,2,1061223634,1,121,'',0),(456,45,26,0,419,54,116,2,1061223634,1,121,'',0),(457,45,116,0,420,26,117,2,1061223634,1,121,'',0),(458,45,117,0,421,116,86,2,1061223634,1,121,'',0),(459,45,86,0,422,117,114,2,1061223634,1,121,'',0),(460,45,114,0,423,86,29,2,1061223634,1,121,'',0),(461,45,29,0,424,114,112,2,1061223634,1,121,'',0),(462,45,112,0,425,29,164,2,1061223634,1,121,'',0),(463,45,164,0,426,112,28,2,1061223634,1,121,'',0),(464,45,28,0,427,164,100,2,1061223634,1,121,'',0),(465,45,100,0,428,28,30,2,1061223634,1,121,'',0),(466,45,30,0,429,100,31,2,1061223634,1,121,'',0),(467,45,31,0,430,30,179,2,1061223634,1,121,'',0),(468,45,179,0,431,31,136,2,1061223634,1,121,'',0),(469,45,136,0,432,179,72,2,1061223634,1,121,'',0),(470,45,72,0,433,136,32,2,1061223634,1,121,'',0),(471,45,32,0,434,72,133,2,1061223634,1,121,'',0),(472,45,133,0,435,32,121,2,1061223634,1,121,'',0),(473,45,121,0,436,133,64,2,1061223634,1,121,'',0),(474,45,64,0,437,121,114,2,1061223634,1,121,'',0),(475,45,114,0,438,64,103,2,1061223634,1,121,'',0),(476,45,103,0,439,114,45,2,1061223634,1,121,'',0),(477,45,45,0,440,103,44,2,1061223634,1,121,'',0),(478,45,44,0,441,45,152,2,1061223634,1,121,'',0),(479,45,152,0,442,44,113,2,1061223634,1,121,'',0),(480,45,113,0,443,152,43,2,1061223634,1,121,'',0),(481,45,43,0,444,113,66,2,1061223634,1,121,'',0),(482,45,66,0,445,43,23,2,1061223634,1,121,'',0),(483,45,23,0,446,66,32,2,1061223634,1,121,'',0),(484,45,32,0,447,23,103,2,1061223634,1,121,'',0),(485,45,103,0,448,32,92,2,1061223634,1,121,'',0),(486,45,92,0,449,103,54,2,1061223634,1,121,'',0),(487,45,54,0,450,92,79,2,1061223634,1,121,'',0),(488,45,79,0,451,54,33,2,1061223634,1,121,'',0),(489,45,33,0,452,79,87,2,1061223634,1,121,'',0),(490,45,87,0,453,33,180,2,1061223634,1,121,'',0),(491,45,180,0,454,87,32,2,1061223634,1,121,'',0),(492,45,32,0,455,180,122,2,1061223634,1,121,'',0),(493,45,122,0,456,32,84,2,1061223634,1,121,'',0),(494,45,84,0,457,122,29,2,1061223634,1,121,'',0),(495,45,29,0,458,84,28,2,1061223634,1,121,'',0),(496,45,28,0,459,29,45,2,1061223634,1,121,'',0),(497,45,45,0,460,28,74,2,1061223634,1,121,'',0),(498,45,74,0,461,45,122,2,1061223634,1,121,'',0),(499,45,122,0,462,74,23,2,1061223634,1,121,'',0),(500,45,23,0,463,122,181,2,1061223634,1,121,'',0),(501,45,181,0,464,23,53,2,1061223634,1,121,'',0),(502,45,53,0,465,181,79,2,1061223634,1,121,'',0),(503,45,79,0,466,53,41,2,1061223634,1,121,'',0),(504,45,41,0,467,79,136,2,1061223634,1,121,'',0),(505,45,136,0,468,41,108,2,1061223634,1,121,'',0),(506,45,108,0,469,136,0,2,1061223634,1,121,'',0);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezsearch_return_count'
--

/*!40000 ALTER TABLE ezsearch_return_count DISABLE KEYS */;
LOCK TABLES ezsearch_return_count WRITE;
INSERT INTO ezsearch_return_count VALUES (1,1,1061223659,2);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezsearch_search_phrase'
--

/*!40000 ALTER TABLE ezsearch_search_phrase DISABLE KEYS */;
LOCK TABLES ezsearch_search_phrase WRITE;
INSERT INTO ezsearch_search_phrase VALUES (1,'news');
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezsearch_word'
--

/*!40000 ALTER TABLE ezsearch_word DISABLE KEYS */;
LOCK TABLES ezsearch_word WRITE;
INSERT INTO ezsearch_word VALUES (5,'test',1),(6,'media',1),(7,'news',2),(8,'latest',1),(9,'on',1),(10,'our',1),(11,'intranet',2),(12,'files',1),(13,'documents',1),(14,'lorem',1),(15,'ipsum',1),(16,'dolor',1),(17,'sit',1),(18,'amet',1),(19,'consectetuer',1),(20,'adipiscing',1),(21,'elit',1),(22,'maecenas',1),(23,'augue',1),(24,'leo',1),(25,'hendrerit',1),(26,'vitae',1),(27,'dapibus',1),(28,'ut',1),(29,'interdum',1),(30,'at',1),(31,'ligula',1),(32,'sed',1),(33,'sem',1),(34,'rhoncus',1),(35,'pulvinar',1),(36,'quisque',1),(37,'fringilla',1),(38,'nibh',1),(39,'odio',1),(40,'convallis',1),(41,'mollis',1),(42,'proin',1),(43,'lacus',1),(44,'nec',1),(45,'neque',1),(46,'vivamus',1),(47,'volutpat',1),(48,'id',1),(49,'purus',1),(50,'nulla',1),(51,'varius',1),(52,'dictum',1),(53,'est',1),(54,'sapien',1),(55,'pede',1),(56,'mattis',1),(57,'in',1),(58,'a',1),(59,'mi',1),(60,'vestibulum',1),(61,'ante',1),(62,'primis',1),(63,'faucibus',1),(64,'orci',1),(65,'luctus',1),(66,'et',1),(67,'ultrices',1),(68,'posuere',1),(69,'cubilia',1),(70,'curae',1),(71,'phasellus',1),(72,'arcu',1),(73,'justo',1),(74,'suspendisse',1),(75,'quis',1),(76,'turpis',1),(77,'pretium',1),(78,'scelerisque',1),(79,'fusce',1),(80,'dignissim',1),(81,'metus',1),(82,'rutrum',1),(83,'risus',1),(84,'eu',1),(85,'venenatis',1),(86,'velit',1),(87,'magna',1),(88,'ac',1),(89,'quam',1),(90,'morbi',1),(91,'non',1),(92,'eleifend',1),(93,'consequat',1),(94,'malesuada',1),(95,'porttitor',1),(96,'pellentesque',1),(97,'egestas',1),(98,'nunc',1),(99,'curabitur',1),(100,'feugiat',1),(101,'dui',1),(102,'etiam',1),(103,'fermentum',1),(104,'ornare',1),(105,'urna',1),(106,'cras',1),(107,'imperdiet',1),(108,'felis',1),(109,'diam',1),(110,'viverra',1),(111,'euismod',1),(112,'vel',1),(113,'libero',1),(114,'mauris',1),(115,'aliquam',1),(116,'enim',1),(117,'nam',1),(118,'blandit',1),(119,'vulputate',1),(120,'aliquet',1),(121,'tempus',1),(122,'facilisis',1),(123,'massa',1),(124,'semper',1),(125,'sodales',1),(126,'erat',1),(127,'iaculis',1),(128,'nullam',1),(129,'gravida',1),(130,'donec',1),(131,'tempor',1),(132,'elementum',1),(133,'nonummy',1),(134,'wisi',1),(135,'nisl',1),(136,'vehicula',1),(137,'facilisi',1),(138,'tincidunt',1),(139,'tortor',1),(140,'congue',1),(141,'cum',1),(142,'sociis',1),(143,'natoque',1),(144,'penatibus',1),(145,'magnis',1),(146,'dis',1),(147,'parturient',1),(148,'montes',1),(149,'nascetur',1),(150,'ridiculus',1),(151,'mus',1),(152,'sagittis',1),(153,'aenean',1),(154,'eros',1),(155,'cursus',1),(156,'lacinia',1),(157,'duis',1),(158,'condimentum',1),(159,'lectus',1),(160,'commodo',1),(161,'integer',1),(162,'accumsan',1),(163,'sollicitudin',1),(164,'ultricies',1),(165,'molestie',1),(166,'class',1),(167,'aptent',1),(168,'taciti',1),(169,'sociosqu',1),(170,'ad',1),(171,'litora',1),(172,'torquent',1),(173,'per',1),(174,'conubia',1),(175,'nostra',1),(176,'inceptos',1),(177,'hymenaeos',1),(178,'ullamcorper',1),(179,'praesent',1),(180,'pharetra',1),(181,'eget',1);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezsection'
--

/*!40000 ALTER TABLE ezsection DISABLE KEYS */;
LOCK TABLES ezsection WRITE;
INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart'),(2,'Users','','ezusernavigationpart'),(3,'Media','','ezmedianavigationpart');
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezsession'
--

/*!40000 ALTER TABLE ezsession DISABLE KEYS */;
LOCK TABLES ezsession WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsession ENABLE KEYS */;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezsubtree_notification_rule'
--

/*!40000 ALTER TABLE ezsubtree_notification_rule DISABLE KEYS */;
LOCK TABLES ezsubtree_notification_rule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsubtree_notification_rule ENABLE KEYS */;

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
  UNIQUE KEY eztrigger_def_id (module_name,function_name,connect_type)
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
  PRIMARY KEY  (id),
  KEY ezurlalias_source_md5 (source_md5)
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezurlalias'
--

/*!40000 ALTER TABLE ezurlalias DISABLE KEYS */;
LOCK TABLES ezurlalias WRITE;
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0),(21,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/44',1,0),(22,'files','45b963397aa40d4a0063e0d85e4fe7a1','content/view/full/45',1,0),(23,'files/documents','2d30f25cef1a92db784bc537e8bf128d','content/view/full/46',1,0),(24,'news/intranet_news','86e1d5cd2c5eb047bdef7588e99889a7','content/view/full/47',1,0);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezuser'
--

/*!40000 ALTER TABLE ezuser DISABLE KEYS */;
LOCK TABLES ezuser WRITE;
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(40,'test','test@test.com',2,'be778b473235e210cc577056226536a4');
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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezuser_role'
--

/*!40000 ALTER TABLE ezuser_role DISABLE KEYS */;
LOCK TABLES ezuser_role WRITE;
INSERT INTO ezuser_role VALUES (29,1,10),(25,2,12),(30,3,13),(28,1,11);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezuser_setting'
--

/*!40000 ALTER TABLE ezuser_setting DISABLE KEYS */;
LOCK TABLES ezuser_setting WRITE;
INSERT INTO ezuser_setting VALUES (10,1,1000),(14,1,10),(23,1,0),(40,1,0);
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
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezvattype'
--

/*!40000 ALTER TABLE ezvattype DISABLE KEYS */;
LOCK TABLES ezvattype WRITE;
INSERT INTO ezvattype VALUES (1,'Std',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezvattype ENABLE KEYS */;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
) TYPE=MyISAM CHARSET=latin1;

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
  PRIMARY KEY  (id)
) TYPE=MyISAM CHARSET=latin1;

--
-- Dumping data for table 'ezworkflow_process'
--

/*!40000 ALTER TABLE ezworkflow_process DISABLE KEYS */;
LOCK TABLES ezworkflow_process WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_process ENABLE KEYS */;

