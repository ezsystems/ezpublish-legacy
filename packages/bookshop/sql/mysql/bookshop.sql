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
INSERT INTO ezcontentbrowserecent VALUES (4,14,47,1061231361,'Wines of the world');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1048494722),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1048494759),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(6,0,'Forum','forum','<name>',14,14,1052384723,1052384870),(7,0,'Forum message','forum_message','<topic>',14,14,1052384877,1052384943),(8,0,'Product','product','<title>',14,14,1052384951,1052385067),(9,0,'Product review','product_review','<title>',14,14,1052385080,1061231279),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(11,0,'Link','link','<title>',14,14,1052385361,1052385453),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(13,0,'Comment','comment','<subject>',14,14,1052385685,1052385756);
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
INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',NULL,0,1),(128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',NULL,0,1),(125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',NULL,0,1),(132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','','',0,1);
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL),(4,14,2,3,'Users',1,0,0,0,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(40,14,2,4,'test test',1,0,1053613020,1053613020,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,1,1,'Comics',1,0,1061230786,1061230786,1,''),(43,14,1,1,'Food & Drink',1,0,1061230870,1061230870,1,''),(44,14,1,8,'Comic book',1,0,1061230921,1061230921,1,''),(45,14,1,8,'Wines of the world',1,0,1061231015,1061231015,1,''),(47,14,1,9,'Good',1,0,1061231361,1061231361,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'My folder',NULL,NULL,0,0,''),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,''),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,''),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,''),(1,'eng-GB',2,1,4,'My folder',0,0,0,0,''),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',0,0,0,0,''),(21,'eng-GB',1,10,12,'',0,0,0,0,''),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,''),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,''),(20,'eng-GB',1,10,9,'User',0,0,0,0,''),(23,'eng-GB',1,11,7,'',0,0,0,0,''),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,''),(25,'eng-GB',1,12,7,'',0,0,0,0,''),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,''),(27,'eng-GB',1,13,7,'',0,0,0,0,''),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,''),(29,'eng-GB',1,14,9,'User',0,0,0,0,''),(30,'eng-GB',1,14,12,'',0,0,0,0,''),(95,'eng-GB',1,40,8,'test',0,0,0,0,''),(96,'eng-GB',1,40,9,'test',0,0,0,0,''),(97,'eng-GB',1,40,12,'',0,0,0,0,''),(98,'eng-GB',1,41,4,'Media',0,0,0,0,''),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,''),(100,'eng-GB',1,42,4,'Comics',0,0,0,0,'0'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Comic books.</paragraph>\n</section>',1045487555,0,0,0,'0'),(102,'eng-GB',1,43,4,'Food & Drink',0,0,0,0,'0'),(103,'eng-GB',1,43,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'0'),(104,'eng-GB',1,44,129,'Comic book',0,0,0,0,'0'),(105,'eng-GB',1,44,130,'B01',0,0,0,0,'0'),(106,'eng-GB',1,44,131,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. </paragraph>\n</section>',1045487555,0,0,0,'0'),(107,'eng-GB',1,44,132,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(108,'eng-GB',1,44,133,'',0,149,0,0,'0'),(109,'eng-GB',1,44,134,'',0,0,0,0,'0'),(110,'eng-GB',1,45,129,'Wines of the world',0,0,0,0,'0'),(111,'eng-GB',1,45,130,'W01',0,0,0,0,'0'),(112,'eng-GB',1,45,131,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'0'),(113,'eng-GB',1,45,132,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(114,'eng-GB',1,45,133,'',0,99,0,0,'0'),(115,'eng-GB',1,45,134,'',0,0,0,0,'0'),(125,'eng-GB',1,47,139,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>I really enjoy this book!</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'0'),(124,'eng-GB',1,47,138,'Anytown, Norway',0,0,0,0,'0'),(121,'eng-GB',1,47,135,'Good',0,0,0,0,'0'),(123,'eng-GB',1,47,137,'John Doe',0,0,0,0,'0'),(122,'eng-GB',1,47,136,'',0,0,0,0,'0');
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(40,'test test',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Comics',1,'eng-GB','eng-GB'),(43,'Food & Drink',1,'eng-GB','eng-GB'),(44,'Comic book',1,'eng-GB','eng-GB'),(45,'Wines of the world',1,'eng-GB','eng-GB'),(47,'Good',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,1,1,1,'/1/2/',1,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15),(42,12,40,1,1,3,'/1/5/12/42/',9,1,0,'users/guest_accounts/test_test',42),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,2,42,1,1,2,'/1/2/44/',9,1,0,'comics',44),(45,2,43,1,1,2,'/1/2/45/',9,1,0,'food_drink',45),(46,44,44,1,1,3,'/1/2/44/46/',9,1,0,'comics/comic_book',46),(47,45,45,1,1,3,'/1/2/45/47/',9,1,0,'food_drink/wines_of_the_world',47),(48,47,47,1,1,4,'/1/2/45/47/48/',9,1,0,'food_drink/wines_of_the_world/good',48);
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
INSERT INTO ezcontentobject_version VALUES (1,1,14,1,0,0,1,1,0),(4,4,14,1,0,0,1,1,0),(436,1,14,2,1033919080,1033919080,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(471,40,14,1,1053613007,1053613020,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1061230772,1061230786,1,0,0),(474,43,14,1,1061230793,1061230870,1,0,0),(475,44,14,1,1061230881,1061230921,1,0,0),(476,45,14,1,1061230984,1061231015,1,0,0),(478,47,14,1,1061231333,1061231361,1,0,0);
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
INSERT INTO ezenumobjectvalue VALUES (122,1,3,'Good','5');
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
INSERT INTO ezenumvalue VALUES (3,136,0,'Good','5',3),(2,136,0,'Ok','3',2),(1,136,0,'Poor','2',1);
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
INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(145,1,2,1,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(181,40,1,12,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,2,9,1,1,0,0),(184,43,1,2,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(186,45,1,45,9,1,1,0,0),(188,47,1,47,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',41,1,0,0,'','','',''),(2,0,'ezpublish',42,1,0,0,'','','',''),(3,0,'ezpublish',43,1,0,0,'','','',''),(4,0,'ezpublish',44,1,0,0,'','','',''),(5,0,'ezpublish',45,1,0,0,'','','',''),(6,0,'ezpublish',47,1,0,0,'','','','');
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
INSERT INTO ezorder VALUES (1,10,1,1061231582,0,1,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Foo</first-name>\n  <last-name>Bar</last-name>\n  <email>bf@ez.no</email>\n  <address>Shop test.</address>\n</shop_account>','simple',0);
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
INSERT INTO ezpolicy VALUES (317,3,'*','content','*'),(308,2,'*','*','*'),(319,3,'login','user','*'),(323,5,'*','content','*'),(324,5,'login','user','*'),(327,1,'login','user','*'),(328,1,'read','content',''),(329,1,'buy','shop','*');
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
INSERT INTO ezpolicy_limitation VALUES (250,328,'Class',0,'read','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (456,250,'1'),(457,250,'10'),(458,250,'10'),(459,250,'11'),(460,250,'11'),(461,250,'12'),(462,250,'12'),(463,250,'13'),(464,250,'13'),(465,250,'2'),(466,250,'2'),(467,250,'5'),(468,250,'5'),(469,250,'6'),(470,250,'6'),(471,250,'7'),(472,250,'7'),(473,250,'8'),(474,250,'8'),(475,250,'9'),(476,250,'9');
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
INSERT INTO ezproductcollection VALUES (1,1061231473);
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
INSERT INTO ezproductcollection_item VALUES (1,1,45,1,99,1,0,0),(2,1,45,1,99,1,0,0),(3,1,45,1,99,1,0,0);
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
INSERT INTO ezsearch_object_word_link VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0),(27,40,5,0,1,5,0,4,1053613020,2,9,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,8,1,1061230786,1,4,'',0),(30,42,8,0,1,7,9,1,1061230786,1,119,'',0),(31,42,9,0,2,8,0,1,1061230786,1,119,'',0),(32,43,10,0,0,0,11,1,1061230870,1,4,'',0),(33,43,11,0,1,10,12,1,1061230870,1,4,'',0),(34,43,12,0,2,11,0,1,1061230870,1,4,'',0),(35,44,8,0,0,0,13,8,1061230921,1,129,'',0),(36,44,13,0,1,8,14,8,1061230921,1,129,'',0),(37,44,14,0,2,13,15,8,1061230921,1,130,'',0),(38,44,15,0,3,14,16,8,1061230921,1,131,'',0),(39,44,16,0,4,15,17,8,1061230921,1,131,'',0),(40,44,17,0,5,16,18,8,1061230921,1,131,'',0),(41,44,18,0,6,17,19,8,1061230921,1,131,'',0),(42,44,19,0,7,18,20,8,1061230921,1,131,'',0),(43,44,20,0,8,19,21,8,1061230921,1,131,'',0),(44,44,21,0,9,20,22,8,1061230921,1,131,'',0),(45,44,22,0,10,21,23,8,1061230921,1,131,'',0),(46,44,23,0,11,22,24,8,1061230921,1,131,'',0),(47,44,24,0,12,23,25,8,1061230921,1,131,'',0),(48,44,25,0,13,24,26,8,1061230921,1,131,'',0),(49,44,26,0,14,25,27,8,1061230921,1,131,'',0),(50,44,27,0,15,26,28,8,1061230921,1,131,'',0),(51,44,28,0,16,27,29,8,1061230921,1,131,'',0),(52,44,29,0,17,28,30,8,1061230921,1,131,'',0),(53,44,30,0,18,29,31,8,1061230921,1,131,'',0),(54,44,31,0,19,30,32,8,1061230921,1,131,'',0),(55,44,32,0,20,31,32,8,1061230921,1,131,'',0),(56,44,32,0,21,32,33,8,1061230921,1,131,'',0),(57,44,33,0,22,32,34,8,1061230921,1,131,'',0),(58,44,34,0,23,33,35,8,1061230921,1,131,'',0),(59,44,35,0,24,34,36,8,1061230921,1,131,'',0),(60,44,36,0,25,35,37,8,1061230921,1,131,'',0),(61,44,37,0,26,36,15,8,1061230921,1,131,'',0),(62,44,15,0,27,37,16,8,1061230921,1,132,'',0),(63,44,16,0,28,15,17,8,1061230921,1,132,'',0),(64,44,17,0,29,16,15,8,1061230921,1,132,'',0),(65,44,15,0,30,17,16,8,1061230921,1,132,'',0),(66,44,16,0,31,15,17,8,1061230921,1,132,'',0),(67,44,17,0,32,16,18,8,1061230921,1,132,'',0),(68,44,18,0,33,17,19,8,1061230921,1,132,'',0),(69,44,19,0,34,18,20,8,1061230921,1,132,'',0),(70,44,20,0,35,19,21,8,1061230921,1,132,'',0),(71,44,21,0,36,20,22,8,1061230921,1,132,'',0),(72,44,22,0,37,21,23,8,1061230921,1,132,'',0),(73,44,23,0,38,22,24,8,1061230921,1,132,'',0),(74,44,24,0,39,23,25,8,1061230921,1,132,'',0),(75,44,25,0,40,24,26,8,1061230921,1,132,'',0),(76,44,26,0,41,25,27,8,1061230921,1,132,'',0),(77,44,27,0,42,26,28,8,1061230921,1,132,'',0),(78,44,28,0,43,27,29,8,1061230921,1,132,'',0),(79,44,29,0,44,28,30,8,1061230921,1,132,'',0),(80,44,30,0,45,29,31,8,1061230921,1,132,'',0),(81,44,31,0,46,30,32,8,1061230921,1,132,'',0),(82,44,32,0,47,31,32,8,1061230921,1,132,'',0),(83,44,32,0,48,32,33,8,1061230921,1,132,'',0),(84,44,33,0,49,32,34,8,1061230921,1,132,'',0),(85,44,34,0,50,33,35,8,1061230921,1,132,'',0),(86,44,35,0,51,34,36,8,1061230921,1,132,'',0),(87,44,36,0,52,35,37,8,1061230921,1,132,'',0),(88,44,37,0,53,36,38,8,1061230921,1,132,'',0),(89,44,38,0,54,37,39,8,1061230921,1,132,'',0),(90,44,39,0,55,38,40,8,1061230921,1,132,'',0),(91,44,40,0,56,39,41,8,1061230921,1,132,'',0),(92,44,41,0,57,40,34,8,1061230921,1,132,'',0),(93,44,34,0,58,41,42,8,1061230921,1,132,'',0),(94,44,42,0,59,34,43,8,1061230921,1,132,'',0),(95,44,43,0,60,42,44,8,1061230921,1,132,'',0),(96,44,44,0,61,43,45,8,1061230921,1,132,'',0),(97,44,45,0,62,44,46,8,1061230921,1,132,'',0),(98,44,46,0,63,45,47,8,1061230921,1,132,'',0),(99,44,47,0,64,46,48,8,1061230921,1,132,'',0),(100,44,48,0,65,47,49,8,1061230921,1,132,'',0),(101,44,49,0,66,48,50,8,1061230921,1,132,'',0),(102,44,50,0,67,49,51,8,1061230921,1,132,'',0),(103,44,51,0,68,50,38,8,1061230921,1,132,'',0),(104,44,38,0,69,51,52,8,1061230921,1,132,'',0),(105,44,52,0,70,38,53,8,1061230921,1,132,'',0),(106,44,53,0,71,52,54,8,1061230921,1,132,'',0),(107,44,54,0,72,53,55,8,1061230921,1,132,'',0),(108,44,55,0,73,54,56,8,1061230921,1,132,'',0),(109,44,56,0,74,55,57,8,1061230921,1,132,'',0),(110,44,57,0,75,56,18,8,1061230921,1,132,'',0),(111,44,18,0,76,57,57,8,1061230921,1,132,'',0),(112,44,57,0,77,18,58,8,1061230921,1,132,'',0),(113,44,58,0,78,57,59,8,1061230921,1,132,'',0),(114,44,59,0,79,58,60,8,1061230921,1,132,'',0),(115,44,60,0,80,59,61,8,1061230921,1,132,'',0),(116,44,61,0,81,60,62,8,1061230921,1,132,'',0),(117,44,62,0,82,61,63,8,1061230921,1,132,'',0),(118,44,63,0,83,62,38,8,1061230921,1,132,'',0),(119,44,38,0,84,63,64,8,1061230921,1,132,'',0),(120,44,64,0,85,38,65,8,1061230921,1,132,'',0),(121,44,65,0,86,64,24,8,1061230921,1,132,'',0),(122,44,24,0,87,65,59,8,1061230921,1,132,'',0),(123,44,59,0,88,24,28,8,1061230921,1,132,'',0),(124,44,28,0,89,59,66,8,1061230921,1,132,'',0),(125,44,66,0,90,28,67,8,1061230921,1,132,'',0),(126,44,67,0,91,66,68,8,1061230921,1,132,'',0),(127,44,68,0,92,67,69,8,1061230921,1,132,'',0),(128,44,69,0,93,68,70,8,1061230921,1,132,'',0),(129,44,70,0,94,69,71,8,1061230921,1,132,'',0),(130,44,71,0,95,70,72,8,1061230921,1,132,'',0),(131,44,72,0,96,71,73,8,1061230921,1,132,'',0),(132,44,73,0,97,72,65,8,1061230921,1,132,'',0),(133,44,65,0,98,73,63,8,1061230921,1,132,'',0),(134,44,63,0,99,65,51,8,1061230921,1,132,'',0),(135,44,51,0,100,63,74,8,1061230921,1,132,'',0),(136,44,74,0,101,51,75,8,1061230921,1,132,'',0),(137,44,75,0,102,74,63,8,1061230921,1,132,'',0),(138,44,63,0,103,75,28,8,1061230921,1,132,'',0),(139,44,28,0,104,63,76,8,1061230921,1,132,'',0),(140,44,76,0,105,28,77,8,1061230921,1,132,'',0),(141,44,77,0,106,76,78,8,1061230921,1,132,'',0),(142,44,78,0,107,77,79,8,1061230921,1,132,'',0),(143,44,79,0,108,78,80,8,1061230921,1,132,'',0),(144,44,80,0,109,79,81,8,1061230921,1,132,'',0),(145,44,81,0,110,80,78,8,1061230921,1,132,'',0),(146,44,78,0,111,81,82,8,1061230921,1,132,'',0),(147,44,82,0,112,78,83,8,1061230921,1,132,'',0),(148,44,83,0,113,82,84,8,1061230921,1,132,'',0),(149,44,84,0,114,83,63,8,1061230921,1,132,'',0),(150,44,63,0,115,84,85,8,1061230921,1,132,'',0),(151,44,85,0,116,63,86,8,1061230921,1,132,'',0),(152,44,86,0,117,85,87,8,1061230921,1,132,'',0),(153,44,87,0,118,86,88,8,1061230921,1,132,'',0),(154,44,88,0,119,87,89,8,1061230921,1,132,'',0),(155,44,89,0,120,88,25,8,1061230921,1,132,'',0),(156,44,25,0,121,89,90,8,1061230921,1,132,'',0),(157,44,90,0,122,25,69,8,1061230921,1,132,'',0),(158,44,69,0,123,90,73,8,1061230921,1,132,'',0),(159,44,73,0,124,69,91,8,1061230921,1,132,'',0),(160,44,91,0,125,73,92,8,1061230921,1,132,'',0),(161,44,92,0,126,91,35,8,1061230921,1,132,'',0),(162,44,35,0,127,92,93,8,1061230921,1,132,'',0),(163,44,93,0,128,35,57,8,1061230921,1,132,'',0),(164,44,57,0,129,93,50,8,1061230921,1,132,'',0),(165,44,50,0,130,57,66,8,1061230921,1,132,'',0),(166,44,66,0,131,50,94,8,1061230921,1,132,'',0),(167,44,94,0,132,66,73,8,1061230921,1,132,'',0),(168,44,73,0,133,94,95,8,1061230921,1,132,'',0),(169,44,95,0,134,73,42,8,1061230921,1,132,'',0),(170,44,42,0,135,95,96,8,1061230921,1,132,'',0),(171,44,96,0,136,42,80,8,1061230921,1,132,'',0),(172,44,80,0,137,96,85,8,1061230921,1,132,'',0),(173,44,85,0,138,80,86,8,1061230921,1,132,'',0),(174,44,86,0,139,85,37,8,1061230921,1,132,'',0),(175,44,37,0,140,86,92,8,1061230921,1,132,'',0),(176,44,92,0,141,37,97,8,1061230921,1,132,'',0),(177,44,97,0,142,92,98,8,1061230921,1,132,'',0),(178,44,98,0,143,97,99,8,1061230921,1,132,'',0),(179,44,99,0,144,98,100,8,1061230921,1,132,'',0),(180,44,100,0,145,99,53,8,1061230921,1,132,'',0),(181,44,53,0,146,100,99,8,1061230921,1,132,'',0),(182,44,99,0,147,53,30,8,1061230921,1,132,'',0),(183,44,30,0,148,99,101,8,1061230921,1,132,'',0),(184,44,101,0,149,30,102,8,1061230921,1,132,'',0),(185,44,102,0,150,101,103,8,1061230921,1,132,'',0),(186,44,103,0,151,102,70,8,1061230921,1,132,'',0),(187,44,70,0,152,103,39,8,1061230921,1,132,'',0),(188,44,39,0,153,70,104,8,1061230921,1,132,'',0),(189,44,104,0,154,39,38,8,1061230921,1,132,'',0),(190,44,38,0,155,104,62,8,1061230921,1,132,'',0),(191,44,62,0,156,38,45,8,1061230921,1,132,'',0),(192,44,45,0,157,62,105,8,1061230921,1,132,'',0),(193,44,105,0,158,45,106,8,1061230921,1,132,'',0),(194,44,106,0,159,105,51,8,1061230921,1,132,'',0),(195,44,51,0,160,106,51,8,1061230921,1,132,'',0),(196,44,51,0,161,51,59,8,1061230921,1,132,'',0),(197,44,59,0,162,51,39,8,1061230921,1,132,'',0),(198,44,39,0,163,59,107,8,1061230921,1,132,'',0),(199,44,107,0,164,39,108,8,1061230921,1,132,'',0),(200,44,108,0,165,107,40,8,1061230921,1,132,'',0),(201,44,40,0,166,108,57,8,1061230921,1,132,'',0),(202,44,57,0,167,40,78,8,1061230921,1,132,'',0),(203,44,78,0,168,57,94,8,1061230921,1,132,'',0),(204,44,94,0,169,78,53,8,1061230921,1,132,'',0),(205,44,53,0,170,94,109,8,1061230921,1,132,'',0),(206,44,109,0,171,53,110,8,1061230921,1,132,'',0),(207,44,110,0,172,109,40,8,1061230921,1,132,'',0),(208,44,40,0,173,110,53,8,1061230921,1,132,'',0),(209,44,53,0,174,40,111,8,1061230921,1,132,'',0),(210,44,111,0,175,53,51,8,1061230921,1,132,'',0),(211,44,51,0,176,111,34,8,1061230921,1,132,'',0),(212,44,34,0,177,51,25,8,1061230921,1,132,'',0),(213,44,25,0,178,34,29,8,1061230921,1,132,'',0),(214,44,29,0,179,25,45,8,1061230921,1,132,'',0),(215,44,45,0,180,29,43,8,1061230921,1,132,'',0),(216,44,43,0,181,45,72,8,1061230921,1,132,'',0),(217,44,72,0,182,43,90,8,1061230921,1,132,'',0),(218,44,90,0,183,72,112,8,1061230921,1,132,'',0),(219,44,112,0,184,90,53,8,1061230921,1,132,'',0),(220,44,53,0,185,112,87,8,1061230921,1,132,'',0),(221,44,87,0,186,53,62,8,1061230921,1,132,'',0),(222,44,62,0,187,87,59,8,1061230921,1,132,'',0),(223,44,59,0,188,62,53,8,1061230921,1,132,'',0),(224,44,53,0,189,59,38,8,1061230921,1,132,'',0),(225,44,38,0,190,53,73,8,1061230921,1,132,'',0),(226,44,73,0,191,38,27,8,1061230921,1,132,'',0),(227,44,27,0,192,73,36,8,1061230921,1,132,'',0),(228,44,36,0,193,27,24,8,1061230921,1,132,'',0),(229,44,24,0,194,36,15,8,1061230921,1,132,'',0),(230,44,15,0,195,24,61,8,1061230921,1,132,'',0),(231,44,61,0,196,15,113,8,1061230921,1,132,'',0),(232,44,113,0,197,61,114,8,1061230921,1,132,'',0),(233,44,114,0,198,113,50,8,1061230921,1,132,'',0),(234,44,50,0,199,114,27,8,1061230921,1,132,'',0),(235,44,27,0,200,50,61,8,1061230921,1,132,'',0),(236,44,61,0,201,27,0,8,1061230921,1,132,'',0),(237,45,115,0,0,0,116,8,1061231015,1,129,'',0),(238,45,116,0,1,115,117,8,1061231015,1,129,'',0),(239,45,117,0,2,116,118,8,1061231015,1,129,'',0),(240,45,118,0,3,117,119,8,1061231015,1,129,'',0),(241,45,119,0,4,118,50,8,1061231015,1,130,'',0),(242,45,50,0,5,119,66,8,1061231015,1,131,'',0),(243,45,66,0,6,50,94,8,1061231015,1,131,'',0),(244,45,94,0,7,66,73,8,1061231015,1,131,'',0),(245,45,73,0,8,94,95,8,1061231015,1,131,'',0),(246,45,95,0,9,73,42,8,1061231015,1,131,'',0),(247,45,42,0,10,95,96,8,1061231015,1,131,'',0),(248,45,96,0,11,42,80,8,1061231015,1,131,'',0),(249,45,80,0,12,96,85,8,1061231015,1,131,'',0),(250,45,85,0,13,80,86,8,1061231015,1,131,'',0),(251,45,86,0,14,85,37,8,1061231015,1,131,'',0),(252,45,37,0,15,86,92,8,1061231015,1,131,'',0),(253,45,92,0,16,37,97,8,1061231015,1,131,'',0),(254,45,97,0,17,92,98,8,1061231015,1,131,'',0),(255,45,98,0,18,97,99,8,1061231015,1,131,'',0),(256,45,99,0,19,98,100,8,1061231015,1,131,'',0),(257,45,100,0,20,99,53,8,1061231015,1,131,'',0),(258,45,53,0,21,100,99,8,1061231015,1,131,'',0),(259,45,99,0,22,53,30,8,1061231015,1,131,'',0),(260,45,30,0,23,99,101,8,1061231015,1,131,'',0),(261,45,101,0,24,30,102,8,1061231015,1,131,'',0),(262,45,102,0,25,101,103,8,1061231015,1,131,'',0),(263,45,103,0,26,102,70,8,1061231015,1,131,'',0),(264,45,70,0,27,103,39,8,1061231015,1,131,'',0),(265,45,39,0,28,70,104,8,1061231015,1,131,'',0),(266,45,104,0,29,39,38,8,1061231015,1,131,'',0),(267,45,38,0,30,104,62,8,1061231015,1,131,'',0),(268,45,62,0,31,38,45,8,1061231015,1,131,'',0),(269,45,45,0,32,62,105,8,1061231015,1,131,'',0),(270,45,105,0,33,45,106,8,1061231015,1,131,'',0),(271,45,106,0,34,105,51,8,1061231015,1,131,'',0),(272,45,51,0,35,106,51,8,1061231015,1,131,'',0),(273,45,51,0,36,51,59,8,1061231015,1,131,'',0),(274,45,59,0,37,51,39,8,1061231015,1,131,'',0),(275,45,39,0,38,59,107,8,1061231015,1,131,'',0),(276,45,107,0,39,39,108,8,1061231015,1,131,'',0),(277,45,108,0,40,107,40,8,1061231015,1,131,'',0),(278,45,40,0,41,108,57,8,1061231015,1,131,'',0),(279,45,57,0,42,40,78,8,1061231015,1,131,'',0),(280,45,78,0,43,57,94,8,1061231015,1,131,'',0),(281,45,94,0,44,78,53,8,1061231015,1,131,'',0),(282,45,53,0,45,94,109,8,1061231015,1,131,'',0),(283,45,109,0,46,53,110,8,1061231015,1,131,'',0),(284,45,110,0,47,109,40,8,1061231015,1,131,'',0),(285,45,40,0,48,110,53,8,1061231015,1,131,'',0),(286,45,53,0,49,40,111,8,1061231015,1,131,'',0),(287,45,111,0,50,53,51,8,1061231015,1,131,'',0),(288,45,51,0,51,111,34,8,1061231015,1,131,'',0),(289,45,34,0,52,51,25,8,1061231015,1,131,'',0),(290,45,25,0,53,34,29,8,1061231015,1,131,'',0),(291,45,29,0,54,25,45,8,1061231015,1,131,'',0),(292,45,45,0,55,29,43,8,1061231015,1,131,'',0),(293,45,43,0,56,45,72,8,1061231015,1,131,'',0),(294,45,72,0,57,43,90,8,1061231015,1,131,'',0),(295,45,90,0,58,72,112,8,1061231015,1,131,'',0),(296,45,112,0,59,90,53,8,1061231015,1,131,'',0),(297,45,53,0,60,112,87,8,1061231015,1,131,'',0),(298,45,87,0,61,53,62,8,1061231015,1,131,'',0),(299,45,62,0,62,87,59,8,1061231015,1,131,'',0),(300,45,59,0,63,62,53,8,1061231015,1,131,'',0),(301,45,53,0,64,59,38,8,1061231015,1,131,'',0),(302,45,38,0,65,53,73,8,1061231015,1,131,'',0),(303,45,73,0,66,38,27,8,1061231015,1,131,'',0),(304,45,27,0,67,73,36,8,1061231015,1,131,'',0),(305,45,36,0,68,27,24,8,1061231015,1,131,'',0),(306,45,24,0,69,36,15,8,1061231015,1,131,'',0),(307,45,15,0,70,24,61,8,1061231015,1,131,'',0),(308,45,61,0,71,15,113,8,1061231015,1,131,'',0),(309,45,113,0,72,61,114,8,1061231015,1,131,'',0),(310,45,114,0,73,113,15,8,1061231015,1,131,'',0),(311,45,15,0,74,114,16,8,1061231015,1,132,'',0),(312,45,16,0,75,15,17,8,1061231015,1,132,'',0),(313,45,17,0,76,16,15,8,1061231015,1,132,'',0),(314,45,15,0,77,17,16,8,1061231015,1,132,'',0),(315,45,16,0,78,15,17,8,1061231015,1,132,'',0),(316,45,17,0,79,16,18,8,1061231015,1,132,'',0),(317,45,18,0,80,17,19,8,1061231015,1,132,'',0),(318,45,19,0,81,18,20,8,1061231015,1,132,'',0),(319,45,20,0,82,19,21,8,1061231015,1,132,'',0),(320,45,21,0,83,20,22,8,1061231015,1,132,'',0),(321,45,22,0,84,21,23,8,1061231015,1,132,'',0),(322,45,23,0,85,22,24,8,1061231015,1,132,'',0),(323,45,24,0,86,23,25,8,1061231015,1,132,'',0),(324,45,25,0,87,24,26,8,1061231015,1,132,'',0),(325,45,26,0,88,25,27,8,1061231015,1,132,'',0),(326,45,27,0,89,26,28,8,1061231015,1,132,'',0),(327,45,28,0,90,27,29,8,1061231015,1,132,'',0),(328,45,29,0,91,28,30,8,1061231015,1,132,'',0),(329,45,30,0,92,29,31,8,1061231015,1,132,'',0),(330,45,31,0,93,30,32,8,1061231015,1,132,'',0),(331,45,32,0,94,31,32,8,1061231015,1,132,'',0),(332,45,32,0,95,32,33,8,1061231015,1,132,'',0),(333,45,33,0,96,32,34,8,1061231015,1,132,'',0),(334,45,34,0,97,33,35,8,1061231015,1,132,'',0),(335,45,35,0,98,34,36,8,1061231015,1,132,'',0),(336,45,36,0,99,35,37,8,1061231015,1,132,'',0),(337,45,37,0,100,36,38,8,1061231015,1,132,'',0),(338,45,38,0,101,37,39,8,1061231015,1,132,'',0),(339,45,39,0,102,38,40,8,1061231015,1,132,'',0),(340,45,40,0,103,39,41,8,1061231015,1,132,'',0),(341,45,41,0,104,40,34,8,1061231015,1,132,'',0),(342,45,34,0,105,41,42,8,1061231015,1,132,'',0),(343,45,42,0,106,34,43,8,1061231015,1,132,'',0),(344,45,43,0,107,42,44,8,1061231015,1,132,'',0),(345,45,44,0,108,43,45,8,1061231015,1,132,'',0),(346,45,45,0,109,44,46,8,1061231015,1,132,'',0),(347,45,46,0,110,45,47,8,1061231015,1,132,'',0),(348,45,47,0,111,46,48,8,1061231015,1,132,'',0),(349,45,48,0,112,47,49,8,1061231015,1,132,'',0),(350,45,49,0,113,48,50,8,1061231015,1,132,'',0),(351,45,50,0,114,49,51,8,1061231015,1,132,'',0),(352,45,51,0,115,50,38,8,1061231015,1,132,'',0),(353,45,38,0,116,51,52,8,1061231015,1,132,'',0),(354,45,52,0,117,38,53,8,1061231015,1,132,'',0),(355,45,53,0,118,52,54,8,1061231015,1,132,'',0),(356,45,54,0,119,53,55,8,1061231015,1,132,'',0),(357,45,55,0,120,54,56,8,1061231015,1,132,'',0),(358,45,56,0,121,55,57,8,1061231015,1,132,'',0),(359,45,57,0,122,56,18,8,1061231015,1,132,'',0),(360,45,18,0,123,57,57,8,1061231015,1,132,'',0),(361,45,57,0,124,18,58,8,1061231015,1,132,'',0),(362,45,58,0,125,57,59,8,1061231015,1,132,'',0),(363,45,59,0,126,58,60,8,1061231015,1,132,'',0),(364,45,60,0,127,59,61,8,1061231015,1,132,'',0),(365,45,61,0,128,60,62,8,1061231015,1,132,'',0),(366,45,62,0,129,61,63,8,1061231015,1,132,'',0),(367,45,63,0,130,62,38,8,1061231015,1,132,'',0),(368,45,38,0,131,63,64,8,1061231015,1,132,'',0),(369,45,64,0,132,38,65,8,1061231015,1,132,'',0),(370,45,65,0,133,64,24,8,1061231015,1,132,'',0),(371,45,24,0,134,65,59,8,1061231015,1,132,'',0),(372,45,59,0,135,24,28,8,1061231015,1,132,'',0),(373,45,28,0,136,59,66,8,1061231015,1,132,'',0),(374,45,66,0,137,28,67,8,1061231015,1,132,'',0),(375,45,67,0,138,66,68,8,1061231015,1,132,'',0),(376,45,68,0,139,67,69,8,1061231015,1,132,'',0),(377,45,69,0,140,68,70,8,1061231015,1,132,'',0),(378,45,70,0,141,69,71,8,1061231015,1,132,'',0),(379,45,71,0,142,70,72,8,1061231015,1,132,'',0),(380,45,72,0,143,71,73,8,1061231015,1,132,'',0),(381,45,73,0,144,72,65,8,1061231015,1,132,'',0),(382,45,65,0,145,73,63,8,1061231015,1,132,'',0),(383,45,63,0,146,65,51,8,1061231015,1,132,'',0),(384,45,51,0,147,63,74,8,1061231015,1,132,'',0),(385,45,74,0,148,51,75,8,1061231015,1,132,'',0),(386,45,75,0,149,74,63,8,1061231015,1,132,'',0),(387,45,63,0,150,75,28,8,1061231015,1,132,'',0),(388,45,28,0,151,63,76,8,1061231015,1,132,'',0),(389,45,76,0,152,28,77,8,1061231015,1,132,'',0),(390,45,77,0,153,76,78,8,1061231015,1,132,'',0),(391,45,78,0,154,77,79,8,1061231015,1,132,'',0),(392,45,79,0,155,78,80,8,1061231015,1,132,'',0),(393,45,80,0,156,79,81,8,1061231015,1,132,'',0),(394,45,81,0,157,80,78,8,1061231015,1,132,'',0),(395,45,78,0,158,81,82,8,1061231015,1,132,'',0),(396,45,82,0,159,78,83,8,1061231015,1,132,'',0),(397,45,83,0,160,82,84,8,1061231015,1,132,'',0),(398,45,84,0,161,83,63,8,1061231015,1,132,'',0),(399,45,63,0,162,84,85,8,1061231015,1,132,'',0),(400,45,85,0,163,63,86,8,1061231015,1,132,'',0),(401,45,86,0,164,85,87,8,1061231015,1,132,'',0),(402,45,87,0,165,86,88,8,1061231015,1,132,'',0),(403,45,88,0,166,87,89,8,1061231015,1,132,'',0),(404,45,89,0,167,88,25,8,1061231015,1,132,'',0),(405,45,25,0,168,89,90,8,1061231015,1,132,'',0),(406,45,90,0,169,25,69,8,1061231015,1,132,'',0),(407,45,69,0,170,90,73,8,1061231015,1,132,'',0),(408,45,73,0,171,69,91,8,1061231015,1,132,'',0),(409,45,91,0,172,73,92,8,1061231015,1,132,'',0),(410,45,92,0,173,91,35,8,1061231015,1,132,'',0),(411,45,35,0,174,92,93,8,1061231015,1,132,'',0),(412,45,93,0,175,35,57,8,1061231015,1,132,'',0),(413,45,57,0,176,93,50,8,1061231015,1,132,'',0),(414,45,50,0,177,57,66,8,1061231015,1,132,'',0),(415,45,66,0,178,50,94,8,1061231015,1,132,'',0),(416,45,94,0,179,66,73,8,1061231015,1,132,'',0),(417,45,73,0,180,94,95,8,1061231015,1,132,'',0),(418,45,95,0,181,73,42,8,1061231015,1,132,'',0),(419,45,42,0,182,95,96,8,1061231015,1,132,'',0),(420,45,96,0,183,42,80,8,1061231015,1,132,'',0),(421,45,80,0,184,96,85,8,1061231015,1,132,'',0),(422,45,85,0,185,80,86,8,1061231015,1,132,'',0),(423,45,86,0,186,85,37,8,1061231015,1,132,'',0),(424,45,37,0,187,86,92,8,1061231015,1,132,'',0),(425,45,92,0,188,37,97,8,1061231015,1,132,'',0),(426,45,97,0,189,92,98,8,1061231015,1,132,'',0),(427,45,98,0,190,97,99,8,1061231015,1,132,'',0),(428,45,99,0,191,98,100,8,1061231015,1,132,'',0),(429,45,100,0,192,99,53,8,1061231015,1,132,'',0),(430,45,53,0,193,100,99,8,1061231015,1,132,'',0),(431,45,99,0,194,53,30,8,1061231015,1,132,'',0),(432,45,30,0,195,99,101,8,1061231015,1,132,'',0),(433,45,101,0,196,30,102,8,1061231015,1,132,'',0),(434,45,102,0,197,101,103,8,1061231015,1,132,'',0),(435,45,103,0,198,102,70,8,1061231015,1,132,'',0),(436,45,70,0,199,103,39,8,1061231015,1,132,'',0),(437,45,39,0,200,70,104,8,1061231015,1,132,'',0),(438,45,104,0,201,39,38,8,1061231015,1,132,'',0),(439,45,38,0,202,104,62,8,1061231015,1,132,'',0),(440,45,62,0,203,38,45,8,1061231015,1,132,'',0),(441,45,45,0,204,62,105,8,1061231015,1,132,'',0),(442,45,105,0,205,45,106,8,1061231015,1,132,'',0),(443,45,106,0,206,105,51,8,1061231015,1,132,'',0),(444,45,51,0,207,106,51,8,1061231015,1,132,'',0),(445,45,51,0,208,51,59,8,1061231015,1,132,'',0),(446,45,59,0,209,51,39,8,1061231015,1,132,'',0),(447,45,39,0,210,59,107,8,1061231015,1,132,'',0),(448,45,107,0,211,39,108,8,1061231015,1,132,'',0),(449,45,108,0,212,107,40,8,1061231015,1,132,'',0),(450,45,40,0,213,108,57,8,1061231015,1,132,'',0),(451,45,57,0,214,40,78,8,1061231015,1,132,'',0),(452,45,78,0,215,57,94,8,1061231015,1,132,'',0),(453,45,94,0,216,78,53,8,1061231015,1,132,'',0),(454,45,53,0,217,94,109,8,1061231015,1,132,'',0),(455,45,109,0,218,53,110,8,1061231015,1,132,'',0),(456,45,110,0,219,109,40,8,1061231015,1,132,'',0),(457,45,40,0,220,110,53,8,1061231015,1,132,'',0),(458,45,53,0,221,40,111,8,1061231015,1,132,'',0),(459,45,111,0,222,53,51,8,1061231015,1,132,'',0),(460,45,51,0,223,111,34,8,1061231015,1,132,'',0),(461,45,34,0,224,51,25,8,1061231015,1,132,'',0),(462,45,25,0,225,34,29,8,1061231015,1,132,'',0),(463,45,29,0,226,25,45,8,1061231015,1,132,'',0),(464,45,45,0,227,29,43,8,1061231015,1,132,'',0),(465,45,43,0,228,45,72,8,1061231015,1,132,'',0),(466,45,72,0,229,43,90,8,1061231015,1,132,'',0),(467,45,90,0,230,72,112,8,1061231015,1,132,'',0),(468,45,112,0,231,90,53,8,1061231015,1,132,'',0),(469,45,53,0,232,112,87,8,1061231015,1,132,'',0),(470,45,87,0,233,53,62,8,1061231015,1,132,'',0),(471,45,62,0,234,87,59,8,1061231015,1,132,'',0),(472,45,59,0,235,62,53,8,1061231015,1,132,'',0),(473,45,53,0,236,59,38,8,1061231015,1,132,'',0),(474,45,38,0,237,53,73,8,1061231015,1,132,'',0),(475,45,73,0,238,38,27,8,1061231015,1,132,'',0),(476,45,27,0,239,73,36,8,1061231015,1,132,'',0),(477,45,36,0,240,27,24,8,1061231015,1,132,'',0),(478,45,24,0,241,36,15,8,1061231015,1,132,'',0),(479,45,15,0,242,24,61,8,1061231015,1,132,'',0),(480,45,61,0,243,15,113,8,1061231015,1,132,'',0),(481,45,113,0,244,61,114,8,1061231015,1,132,'',0),(482,45,114,0,245,113,50,8,1061231015,1,132,'',0),(483,45,50,0,246,114,27,8,1061231015,1,132,'',0),(484,45,27,0,247,50,61,8,1061231015,1,132,'',0),(485,45,61,0,248,27,0,8,1061231015,1,132,'',0),(486,47,120,0,0,0,121,9,1061231361,1,135,'',0),(487,47,121,0,1,120,120,9,1061231361,1,136,'',0),(488,47,120,0,2,121,122,9,1061231361,1,136,'',0),(489,47,122,0,3,120,123,9,1061231361,1,137,'',0),(490,47,123,0,4,122,124,9,1061231361,1,137,'',0),(491,47,124,0,5,123,125,9,1061231361,1,138,'',0),(492,47,125,0,6,124,126,9,1061231361,1,138,'',0),(493,47,126,0,7,125,127,9,1061231361,1,139,'',0),(494,47,127,0,8,126,128,9,1061231361,1,139,'',0),(495,47,128,0,9,127,129,9,1061231361,1,139,'',0),(496,47,129,0,10,128,13,9,1061231361,1,139,'',0),(497,47,13,0,11,129,0,9,1061231361,1,139,'',0);
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
INSERT INTO ezsearch_word VALUES (5,'test',1),(6,'media',1),(7,'comics',1),(8,'comic',2),(9,'books',1),(10,'food',1),(11,'&',1),(12,'drink',1),(13,'book',2),(14,'b01',1),(15,'proin',2),(16,'consectetuer',2),(17,'lacus',2),(18,'nec',2),(19,'neque',2),(20,'vivamus',2),(21,'volutpat',2),(22,'elit',2),(23,'id',2),(24,'purus',2),(25,'nulla',2),(26,'varius',2),(27,'dictum',2),(28,'est',2),(29,'maecenas',2),(30,'sapien',2),(31,'pede',2),(32,'mattis',2),(33,'mollis',2),(34,'in',2),(35,'pulvinar',2),(36,'a',2),(37,'mi',2),(38,'vestibulum',2),(39,'ante',2),(40,'ipsum',2),(41,'primis',2),(42,'faucibus',2),(43,'orci',2),(44,'luctus',2),(45,'et',2),(46,'ultrices',2),(47,'posuere',2),(48,'cubilia',2),(49,'curae',2),(50,'phasellus',2),(51,'arcu',2),(52,'justo',2),(53,'sed',2),(54,'rhoncus',2),(55,'suspendisse',2),(56,'quis',2),(57,'turpis',2),(58,'pretium',2),(59,'scelerisque',2),(60,'fusce',2),(61,'dignissim',2),(62,'metus',2),(63,'ut',2),(64,'rutrum',2),(65,'risus',2),(66,'eu',2),(67,'venenatis',2),(68,'velit',2),(69,'magna',2),(70,'ac',2),(71,'quam',2),(72,'morbi',2),(73,'non',2),(74,'eleifend',2),(75,'consequat',2),(76,'augue',2),(77,'malesuada',2),(78,'vitae',2),(79,'porttitor',2),(80,'pellentesque',2),(81,'egestas',2),(82,'nunc',2),(83,'curabitur',2),(84,'feugiat',2),(85,'sit',2),(86,'amet',2),(87,'dui',2),(88,'etiam',2),(89,'fermentum',2),(90,'ornare',2),(91,'urna',2),(92,'cras',2),(93,'imperdiet',2),(94,'felis',2),(95,'diam',2),(96,'viverra',2),(97,'euismod',2),(98,'leo',2),(99,'vel',2),(100,'libero',2),(101,'mauris',2),(102,'aliquam',2),(103,'enim',2),(104,'nam',2),(105,'blandit',2),(106,'vulputate',2),(107,'at',2),(108,'dapibus',2),(109,'aliquet',2),(110,'tempus',2),(111,'facilisis',2),(112,'massa',2),(113,'semper',2),(114,'odio',2),(115,'wines',1),(116,'of',1),(117,'the',1),(118,'world',1),(119,'w01',1),(120,'good',1),(121,'5',1),(122,'john',1),(123,'doe',1),(124,'anytown',1),(125,'norway',1),(126,'i',1),(127,'really',1),(128,'enjoy',1),(129,'this',1);
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
INSERT INTO ezsession VALUES ('bc038fad359d92172ab11d5b1f1cb461',1061490794,'eZUserInfoCache_Timestamp|i:1061231502;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1061231502;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1061231550;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}FromPage|s:22:\"/content/view/full/47/\";LastAccessesURI|s:22:\"/content/view/full/47/\";eZUserDiscountRulesTimestamp|i:1061231502;eZUserDiscountRules10|a:0:{}userLimitations|a:5:{i:326;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"249\";s:9:\"policy_id\";s:3:\"326\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:325;a:0:{}i:327;a:0:{}i:328;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"250\";s:9:\"policy_id\";s:3:\"328\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:329;a:0:{}}userLimitationValues|a:2:{i:249;a:21:{i:0;a:3:{s:2:\"id\";s:3:\"435\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"436\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"437\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:2:\"10\";}i:3;a:3:{s:2:\"id\";s:3:\"438\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:2:\"11\";}i:4;a:3:{s:2:\"id\";s:3:\"439\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:2:\"11\";}i:5;a:3:{s:2:\"id\";s:3:\"440\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:2:\"12\";}i:6;a:3:{s:2:\"id\";s:3:\"441\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:2:\"12\";}i:7;a:3:{s:2:\"id\";s:3:\"442\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:2:\"13\";}i:8;a:3:{s:2:\"id\";s:3:\"443\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:2:\"13\";}i:9;a:3:{s:2:\"id\";s:3:\"444\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"2\";}i:10;a:3:{s:2:\"id\";s:3:\"445\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"2\";}i:11;a:3:{s:2:\"id\";s:3:\"446\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"5\";}i:12;a:3:{s:2:\"id\";s:3:\"447\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"5\";}i:13;a:3:{s:2:\"id\";s:3:\"448\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"6\";}i:14;a:3:{s:2:\"id\";s:3:\"449\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"6\";}i:15;a:3:{s:2:\"id\";s:3:\"450\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"7\";}i:16;a:3:{s:2:\"id\";s:3:\"451\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"7\";}i:17;a:3:{s:2:\"id\";s:3:\"452\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"8\";}i:18;a:3:{s:2:\"id\";s:3:\"453\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"8\";}i:19;a:3:{s:2:\"id\";s:3:\"454\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"9\";}i:20;a:3:{s:2:\"id\";s:3:\"455\";s:13:\"limitation_id\";s:3:\"249\";s:5:\"value\";s:1:\"9\";}}i:250;a:21:{i:0;a:3:{s:2:\"id\";s:3:\"456\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"457\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"458\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:2:\"10\";}i:3;a:3:{s:2:\"id\";s:3:\"459\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:2:\"11\";}i:4;a:3:{s:2:\"id\";s:3:\"460\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:2:\"11\";}i:5;a:3:{s:2:\"id\";s:3:\"461\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:2:\"12\";}i:6;a:3:{s:2:\"id\";s:3:\"462\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:2:\"12\";}i:7;a:3:{s:2:\"id\";s:3:\"463\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:2:\"13\";}i:8;a:3:{s:2:\"id\";s:3:\"464\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:2:\"13\";}i:9;a:3:{s:2:\"id\";s:3:\"465\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"2\";}i:10;a:3:{s:2:\"id\";s:3:\"466\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"2\";}i:11;a:3:{s:2:\"id\";s:3:\"467\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"5\";}i:12;a:3:{s:2:\"id\";s:3:\"468\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"5\";}i:13;a:3:{s:2:\"id\";s:3:\"469\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"6\";}i:14;a:3:{s:2:\"id\";s:3:\"470\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"6\";}i:15;a:3:{s:2:\"id\";s:3:\"471\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"7\";}i:16;a:3:{s:2:\"id\";s:3:\"472\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"7\";}i:17;a:3:{s:2:\"id\";s:3:\"473\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"8\";}i:18;a:3:{s:2:\"id\";s:3:\"474\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"8\";}i:19;a:3:{s:2:\"id\";s:3:\"475\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"9\";}i:20;a:3:{s:2:\"id\";s:3:\"476\";s:13:\"limitation_id\";s:3:\"250\";s:5:\"value\";s:1:\"9\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserDiscountRules14|a:0:{}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1061231541;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:13:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:1:\"6\";s:4:\"name\";s:5:\"Forum\";}i:6;a:2:{s:2:\"id\";s:1:\"7\";s:4:\"name\";s:13:\"Forum message\";}i:7;a:2:{s:2:\"id\";s:1:\"8\";s:4:\"name\";s:7:\"Product\";}i:8;a:2:{s:2:\"id\";s:1:\"9\";s:4:\"name\";s:14:\"Product review\";}i:9;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:10;a:2:{s:2:\"id\";s:2:\"11\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:12;a:2:{s:2:\"id\";s:2:\"13\";s:4:\"name\";s:7:\"Comment\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserLoggedInID|N;UserPolicies|a:1:{i:1;a:3:{i:0;a:5:{s:2:\"id\";s:3:\"327\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"328\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"329\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"shop\";s:13:\"function_name\";s:3:\"buy\";s:10:\"limitation\";s:1:\"*\";}}}MyTemporaryOrderID|i:1;UserOrderID|s:1:\"1\";');
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0),(21,'comics','51af637aed604c1d5376026ae625111e','content/view/full/44',1,0),(22,'food_drink','5f4233aa8721d1bf592b36c8015387bb','content/view/full/45',1,0),(23,'comics/comic_book','65f62b921d9507302127565c830cd81c','content/view/full/46',1,0),(24,'food_drink/wines_of_the_world','58389bc72c29368ecdcf1801cf879b9f','content/view/full/47',1,0),(25,'food_drink/wines_of_the_world/good','2ec43ec25f6492f6f5bc7129c785daf2','content/view/full/48',1,0);
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

