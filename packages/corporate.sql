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
INSERT INTO ezcontentbrowserecent VALUES (7,14,46,1061216019,'Services');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL),(4,14,2,3,'Users',1,0,0,0,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(40,14,2,4,'test test',1,0,1053613020,1053613020,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,1,1,'Company',1,0,1061214222,1061214222,1,''),(43,14,1,1,'Products',1,0,1061214257,1061214257,1,''),(44,14,1,1,'Services',1,0,1061214274,1061214274,1,''),(45,14,1,1,'Press releases',1,0,1061214302,1061214302,1,''),(46,14,1,2,'A press release',1,0,1061214366,1061214366,1,''),(47,14,1,10,'About my company',2,0,1061215045,1061215485,1,''),(48,14,1,1,'Software',1,0,1061215659,1061215659,1,''),(49,14,1,1,'Hardware',1,0,1061215680,1061215680,1,''),(51,14,1,2,'A hardware thing',1,0,1061215750,1061215750,1,''),(52,14,1,2,'Our software product',1,0,1061215972,1061215972,1,''),(53,14,1,10,'Consulting',1,0,1061216018,1061216018,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'My folder',NULL,NULL,0,0,''),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,''),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,''),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,''),(1,'eng-GB',2,1,4,'My folder',0,0,0,0,''),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',0,0,0,0,''),(21,'eng-GB',1,10,12,'',0,0,0,0,''),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,''),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,''),(20,'eng-GB',1,10,9,'User',0,0,0,0,''),(23,'eng-GB',1,11,7,'',0,0,0,0,''),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,''),(25,'eng-GB',1,12,7,'',0,0,0,0,''),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,''),(27,'eng-GB',1,13,7,'',0,0,0,0,''),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,''),(29,'eng-GB',1,14,9,'User',0,0,0,0,''),(30,'eng-GB',1,14,12,'',0,0,0,0,''),(95,'eng-GB',1,40,8,'test',0,0,0,0,''),(96,'eng-GB',1,40,9,'test',0,0,0,0,''),(97,'eng-GB',1,40,12,'',0,0,0,0,''),(98,'eng-GB',1,41,4,'Media',0,0,0,0,''),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,''),(100,'eng-GB',1,42,4,'Company',0,0,0,0,'0'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Information about our company.</paragraph>\n</section>',1045487555,0,0,0,'0'),(102,'eng-GB',1,43,4,'Products',0,0,0,0,'0'),(103,'eng-GB',1,43,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our products.</paragraph>\n</section>',1045487555,0,0,0,'0'),(104,'eng-GB',1,44,4,'Services',0,0,0,0,'0'),(105,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our services.</paragraph>\n</section>',1045487555,0,0,0,'0'),(106,'eng-GB',1,45,4,'Press releases',0,0,0,0,'0'),(107,'eng-GB',1,45,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Press releases.</paragraph>\n</section>',1045487555,0,0,0,'0'),(108,'eng-GB',1,46,1,'A press release',0,0,0,0,'0'),(109,'eng-GB',1,46,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <emphasize>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas augue leo, hendrerit vitae, dapibus ut, interdum at, ligula. Sed ut ipsum vitae sem rhoncus pulvinar. Quisque fringilla nibh at odio convallis mollis. </emphasize>\n  </paragraph>\n</section>',1045487555,0,0,0,'0'),(110,'eng-GB',1,46,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n  <section>\n    <header>Nam sodales</header>\n    <paragraph>\n      <line> Nam sodales vestibulum erat. Nam non mauris quis dui porttitor iaculis. Nullam quam. Suspendisse at dolor. Aliquam erat volutpat. Morbi ac justo ut enim gravida gravida. Donec tempor, nunc id elementum nonummy, lorem ligula consectetuer felis, nec rutrum wisi nisl nec augue. Nam feugiat. Etiam iaculis elementum massa. Mauris vitae ante vel lacus eleifend vehicula. Nulla facilisi. Maecenas tincidunt consequat wisi. Curabitur in libero. Nunc interdum pretium urna.</line>\n      <line> Vivamus vel tortor. Cras nonummy facilisis ligula. Aliquam urna dolor, congue eu, fermentum a, faucibus ac, tortor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed varius risus sagittis elit egestas egestas. Aenean elit. Nulla magna. Mauris rutrum. Suspendisse dignissim eros in wisi pretium dignissim. Maecenas ante. Pellentesque mattis augue. Cras a ante. Nam non arcu. Donec cursus lacinia elit.</line>\n      <line> Duis eu nunc. Cras condimentum posuere lectus. Maecenas diam. Vestibulum vitae urna vitae lorem porttitor commodo. Integer vehicula tincidunt odio. Aliquam sit amet elit a nunc accumsan sagittis. Vivamus sollicitudin tempor magna. Suspendisse convallis nibh id ligula. Phasellus at velit sit amet sapien viverra ultricies. Etiam molestie euismod risus. Pellentesque non purus at erat dapibus aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Nullam ornare ullamcorper risus. Nulla nonummy sapien vitae enim. Nam velit mauris, interdum vel, ultricies ut, feugiat at, ligula. Praesent vehicula, arcu sed nonummy tempus, orci mauris fermentum neque, nec sagittis libero lacus et augue. Sed fermentum eleifend sapien. Fusce sem magna, pharetra sed, facilisis eu, interdum ut, neque. Suspendisse facilisis augue eget est. Fusce mollis vehicula felis.</line>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(111,'eng-GB',1,46,122,'',0,0,0,0,'0'),(112,'eng-GB',1,46,123,'',0,0,0,0,'0'),(113,'eng-GB',1,47,140,'About my company',0,0,0,0,'0'),(114,'eng-GB',1,47,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas augue leo, hendrerit vitae, dapibus ut, interdum at, ligula. Sed ut ipsum vitae sem rhoncus pulvinar. Quisque fringilla nibh at odio convallis mollis. </paragraph>\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(115,'eng-GB',1,47,142,'',0,0,0,0,'0'),(113,'eng-GB',2,47,140,'About my company',0,0,0,0,'0'),(114,'eng-GB',2,47,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas augue leo, hendrerit vitae, dapibus ut, interdum at, ligula. Sed ut ipsum vitae sem rhoncus pulvinar. Quisque fringilla nibh at odio convallis mollis.</paragraph>\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(115,'eng-GB',2,47,142,'',0,0,0,0,'0'),(116,'eng-GB',1,48,4,'Software',0,0,0,0,'0'),(117,'eng-GB',1,48,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'0'),(118,'eng-GB',1,49,4,'Hardware',0,0,0,0,'0'),(119,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'0'),(123,'eng-GB',1,51,1,'A hardware thing',0,0,0,0,'0'),(124,'eng-GB',1,51,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas augue leo, hendrerit vitae, dapibus ut, interdum at, ligula. Sed ut ipsum vitae sem rhoncus pulvinar. Quisque fringilla nibh at odio convallis mollis. </paragraph>\n</section>',1045487555,0,0,0,'0'),(125,'eng-GB',1,51,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n  <section>\n    <header>Nam sodales</header>\n    <paragraph>\n      <line> Nam sodales vestibulum erat. Nam non mauris quis dui porttitor iaculis. Nullam quam. Suspendisse at dolor. Aliquam erat volutpat. Morbi ac justo ut enim gravida gravida. Donec tempor, nunc id elementum nonummy, lorem ligula consectetuer felis, nec rutrum wisi nisl nec augue. Nam feugiat. Etiam iaculis elementum massa. Mauris vitae ante vel lacus eleifend vehicula. Nulla facilisi. Maecenas tincidunt consequat wisi. Curabitur in libero. Nunc interdum pretium urna.</line>\n      <line> Vivamus vel tortor. Cras nonummy facilisis ligula. Aliquam urna dolor, congue eu, fermentum a, faucibus ac, tortor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed varius risus sagittis elit egestas egestas. Aenean elit. Nulla magna. Mauris rutrum. Suspendisse dignissim eros in wisi pretium dignissim. Maecenas ante. Pellentesque mattis augue. Cras a ante. Nam non arcu. Donec cursus lacinia elit.</line>\n      <line> Duis eu nunc. Cras condimentum posuere lectus. Maecenas diam. Vestibulum vitae urna vitae lorem porttitor commodo. Integer vehicula tincidunt odio. Aliquam sit amet elit a nunc accumsan sagittis. Vivamus sollicitudin tempor magna. Suspendisse convallis nibh id ligula. Phasellus at velit sit amet sapien viverra ultricies. Etiam molestie euismod risus. Pellentesque non purus at erat dapibus aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Nullam ornare ullamcorper risus. Nulla nonummy sapien vitae enim. Nam velit mauris, interdum vel, ultricies ut, feugiat at, ligula. Praesent vehicula, arcu sed nonummy tempus, orci mauris fermentum neque, nec sagittis libero lacus et augue. Sed fermentum eleifend sapien. Fusce sem magna, pharetra sed, facilisis eu, interdum ut, neque. Suspendisse facilisis augue eget est. Fusce mollis vehicula felis.</line>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(126,'eng-GB',1,51,122,'',0,0,0,0,'0'),(127,'eng-GB',1,51,123,'',0,0,0,0,'0'),(128,'eng-GB',1,52,1,'Our software product',0,0,0,0,'0'),(129,'eng-GB',1,52,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas augue leo, hendrerit vitae, dapibus ut, interdum at, ligula. Sed ut ipsum vitae sem rhoncus pulvinar. Quisque fringilla nibh at odio convallis mollis. </line>\n  </paragraph>\n</section>',1045487555,0,0,0,'0'),(130,'eng-GB',1,52,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n  <section>\n    <header>Nam sodales</header>\n    <paragraph>\n      <line> Nam sodales vestibulum erat. Nam non mauris quis dui porttitor iaculis. Nullam quam. Suspendisse at dolor. Aliquam erat volutpat. Morbi ac justo ut enim gravida gravida. Donec tempor, nunc id elementum nonummy, lorem ligula consectetuer felis, nec rutrum wisi nisl nec augue. Nam feugiat. Etiam iaculis elementum massa. Mauris vitae ante vel lacus eleifend vehicula. Nulla facilisi. Maecenas tincidunt consequat wisi. Curabitur in libero. Nunc interdum pretium urna.</line>\n      <line> Vivamus vel tortor. Cras nonummy facilisis ligula. Aliquam urna dolor, congue eu, fermentum a, faucibus ac, tortor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed varius risus sagittis elit egestas egestas. Aenean elit. Nulla magna. Mauris rutrum. Suspendisse dignissim eros in wisi pretium dignissim. Maecenas ante. Pellentesque mattis augue. Cras a ante. Nam non arcu. Donec cursus lacinia elit.</line>\n      <line> Duis eu nunc. Cras condimentum posuere lectus. Maecenas diam. Vestibulum vitae urna vitae lorem porttitor commodo. Integer vehicula tincidunt odio. Aliquam sit amet elit a nunc accumsan sagittis. Vivamus sollicitudin tempor magna. Suspendisse convallis nibh id ligula. Phasellus at velit sit amet sapien viverra ultricies. Etiam molestie euismod risus. Pellentesque non purus at erat dapibus aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Nullam ornare ullamcorper risus. Nulla nonummy sapien vitae enim. Nam velit mauris, interdum vel, ultricies ut, feugiat at, ligula. Praesent vehicula, arcu sed nonummy tempus, orci mauris fermentum neque, nec sagittis libero lacus et augue. Sed fermentum eleifend sapien. Fusce sem magna, pharetra sed, facilisis eu, interdum ut, neque. Suspendisse facilisis augue eget est. Fusce mollis vehicula felis.</line>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(131,'eng-GB',1,52,122,'',0,0,0,0,'0'),(132,'eng-GB',1,52,123,'',0,0,0,0,'0'),(133,'eng-GB',1,53,140,'Consulting',0,0,0,0,'0'),(134,'eng-GB',1,53,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas augue leo, hendrerit vitae, dapibus ut, interdum at, ligula. Sed ut ipsum vitae sem rhoncus pulvinar. Quisque fringilla nibh at odio convallis mollis. </paragraph>\n  <section>\n    <header>Proin consectetuer lacus</header>\n    <paragraph>\n      <line>Proin consectetuer lacus nec neque. Vivamus volutpat elit id purus. Nulla varius dictum est. Maecenas sapien pede, mattis mattis, mollis in, pulvinar a, mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus arcu. Vestibulum justo. Sed rhoncus. Suspendisse quis turpis nec turpis pretium scelerisque. Fusce dignissim, metus ut vestibulum rutrum, risus purus scelerisque est, eu venenatis velit magna ac quam. Morbi non risus ut arcu eleifend consequat. Ut est augue, malesuada vitae, porttitor pellentesque, egestas vitae, nunc. Curabitur feugiat. Ut sit amet dui. Etiam fermentum. Nulla ornare magna non urna. Cras pulvinar imperdiet turpis.</line>\n      <line> Phasellus eu felis non diam faucibus viverra. Pellentesque sit amet mi. Cras euismod leo vel libero. Sed vel sapien. Mauris aliquam enim ac ante. Nam vestibulum, metus et blandit vulputate, arcu arcu scelerisque ante, at dapibus ipsum turpis vitae felis. Sed aliquet tempus ipsum. Sed facilisis arcu in nulla. Maecenas et orci. Morbi ornare massa. Sed dui metus, scelerisque sed, vestibulum non, dictum a, purus. Proin dignissim semper odio.</line>\n    </paragraph>\n    <paragraph>\n      <ul>\n        <li>Phasellus</li>\n        <li>Dictum</li>\n        <li>Dignissim</li>\n      </ul>\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'0'),(135,'eng-GB',1,53,142,'',0,0,0,0,'0');
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(40,'test test',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Company',1,'eng-GB','eng-GB'),(43,'Products',1,'eng-GB','eng-GB'),(44,'Services',1,'eng-GB','eng-GB'),(45,'Press releases',1,'eng-GB','eng-GB'),(46,'A press release',1,'eng-GB','eng-GB'),(47,'About my company',1,'eng-GB','eng-GB'),(47,'About my company',2,'eng-GB','eng-GB'),(48,'Software',1,'eng-GB','eng-GB'),(49,'Hardware',1,'eng-GB','eng-GB'),(51,'A hardware thing',1,'eng-GB','eng-GB'),(52,'Our software product',1,'eng-GB','eng-GB'),(53,'Consulting',1,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,1,1,1,'/1/2/',1,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15),(42,12,40,1,1,3,'/1/5/12/42/',9,1,0,'users/guest_accounts/test_test',42),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,2,42,1,1,2,'/1/2/44/',9,1,0,'company',44),(45,2,43,1,1,2,'/1/2/45/',9,1,0,'products',45),(46,2,44,1,1,2,'/1/2/46/',9,1,0,'services',46),(47,44,45,1,1,3,'/1/2/44/47/',9,1,0,'company/press_releases',47),(48,47,46,1,1,4,'/1/2/44/47/48/',9,1,0,'company/press_releases/a_press_release',48),(49,44,47,2,1,3,'/1/2/44/49/',9,1,0,'company/about_my_company',49),(50,45,48,1,1,3,'/1/2/45/50/',9,1,0,'products/software',50),(51,45,49,1,1,3,'/1/2/45/51/',9,1,0,'products/hardware',51),(52,51,51,1,1,4,'/1/2/45/51/52/',9,1,0,'products/hardware/a_hardware_thing',52),(53,50,52,1,1,4,'/1/2/45/50/53/',9,1,0,'products/software/our_software_product',53),(54,46,53,1,1,3,'/1/2/46/54/',9,1,0,'services/consulting',54);
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
INSERT INTO ezcontentobject_version VALUES (1,1,14,1,0,0,1,1,0),(4,4,14,1,0,0,1,1,0),(436,1,14,2,1033919080,1033919080,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(471,40,14,1,1053613007,1053613020,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1061214203,1061214222,1,0,0),(474,43,14,1,1061214246,1061214256,1,0,0),(475,44,14,1,1061214261,1061214274,1,0,0),(476,45,14,1,1061214290,1061214302,1,0,0),(477,46,14,1,1061214318,1061214366,1,0,0),(478,47,14,1,1061215014,1061215045,3,0,0),(479,47,14,2,1061215466,1061215484,1,0,0),(480,48,14,1,1061215649,1061215658,1,0,0),(481,49,14,1,1061215665,1061215679,1,0,0),(483,51,14,1,1061215734,1061215750,1,0,0),(484,52,14,1,1061215946,1061215972,1,0,0),(485,53,14,1,1061215996,1061216018,1,0,0);
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
INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(145,1,2,1,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(181,40,1,12,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,2,9,1,1,0,0),(184,43,1,2,9,1,1,0,0),(185,44,1,2,9,1,1,0,0),(186,45,1,44,9,1,1,0,0),(187,46,1,47,9,1,1,0,0),(188,47,1,47,9,1,1,0,0),(191,48,1,45,9,1,1,0,0),(190,47,2,44,9,1,1,47,0),(192,49,1,45,9,1,1,0,0),(194,51,1,51,9,1,1,0,0),(195,52,1,50,9,1,1,0,0),(196,53,1,46,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',41,1,0,0,'','','',''),(2,0,'ezpublish',42,1,0,0,'','','',''),(3,0,'ezpublish',43,1,0,0,'','','',''),(4,0,'ezpublish',44,1,0,0,'','','',''),(5,0,'ezpublish',45,1,0,0,'','','',''),(6,0,'ezpublish',46,1,0,0,'','','',''),(7,0,'ezpublish',47,1,0,0,'','','',''),(8,0,'ezpublish',47,2,0,0,'','','',''),(9,0,'ezpublish',48,1,0,0,'','','',''),(10,0,'ezpublish',49,1,0,0,'','','',''),(11,0,'ezpublish',51,1,0,0,'','','',''),(12,0,'ezpublish',52,1,0,0,'','','',''),(13,0,'ezpublish',53,1,0,0,'','','','');
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
INSERT INTO ezsearch_object_word_link VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0),(27,40,5,0,1,5,0,4,1053613020,2,9,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,8,1,1061214222,1,4,'',0),(30,42,8,0,1,7,9,1,1061214222,1,119,'',0),(31,42,9,0,2,8,10,1,1061214222,1,119,'',0),(32,42,10,0,3,9,7,1,1061214222,1,119,'',0),(33,42,7,0,4,10,0,1,1061214222,1,119,'',0),(34,43,11,0,0,0,10,1,1061214257,1,4,'',0),(35,43,10,0,1,11,11,1,1061214257,1,119,'',0),(36,43,11,0,2,10,0,1,1061214257,1,119,'',0),(37,44,12,0,0,0,10,1,1061214274,1,4,'',0),(38,44,10,0,1,12,12,1,1061214274,1,119,'',0),(39,44,12,0,2,10,0,1,1061214274,1,119,'',0),(40,45,13,0,0,0,14,1,1061214302,1,4,'',0),(41,45,14,0,1,13,13,1,1061214302,1,4,'',0),(42,45,13,0,2,14,14,1,1061214302,1,119,'',0),(43,45,14,0,3,13,0,1,1061214302,1,119,'',0),(44,46,15,0,0,0,13,2,1061214366,1,1,'',0),(45,46,13,0,1,15,16,2,1061214366,1,1,'',0),(46,46,16,0,2,13,17,2,1061214366,1,1,'',0),(47,46,17,0,3,16,18,2,1061214366,1,120,'',0),(48,46,18,0,4,17,19,2,1061214366,1,120,'',0),(49,46,19,0,5,18,20,2,1061214366,1,120,'',0),(50,46,20,0,6,19,21,2,1061214366,1,120,'',0),(51,46,21,0,7,20,22,2,1061214366,1,120,'',0),(52,46,22,0,8,21,23,2,1061214366,1,120,'',0),(53,46,23,0,9,22,24,2,1061214366,1,120,'',0),(54,46,24,0,10,23,25,2,1061214366,1,120,'',0),(55,46,25,0,11,24,26,2,1061214366,1,120,'',0),(56,46,26,0,12,25,27,2,1061214366,1,120,'',0),(57,46,27,0,13,26,28,2,1061214366,1,120,'',0),(58,46,28,0,14,27,29,2,1061214366,1,120,'',0),(59,46,29,0,15,28,30,2,1061214366,1,120,'',0),(60,46,30,0,16,29,31,2,1061214366,1,120,'',0),(61,46,31,0,17,30,32,2,1061214366,1,120,'',0),(62,46,32,0,18,31,33,2,1061214366,1,120,'',0),(63,46,33,0,19,32,34,2,1061214366,1,120,'',0),(64,46,34,0,20,33,35,2,1061214366,1,120,'',0),(65,46,35,0,21,34,31,2,1061214366,1,120,'',0),(66,46,31,0,22,35,18,2,1061214366,1,120,'',0),(67,46,18,0,23,31,29,2,1061214366,1,120,'',0),(68,46,29,0,24,18,36,2,1061214366,1,120,'',0),(69,46,36,0,25,29,37,2,1061214366,1,120,'',0),(70,46,37,0,26,36,38,2,1061214366,1,120,'',0),(71,46,38,0,27,37,39,2,1061214366,1,120,'',0),(72,46,39,0,28,38,40,2,1061214366,1,120,'',0),(73,46,40,0,29,39,41,2,1061214366,1,120,'',0),(74,46,41,0,30,40,33,2,1061214366,1,120,'',0),(75,46,33,0,31,41,42,2,1061214366,1,120,'',0),(76,46,42,0,32,33,43,2,1061214366,1,120,'',0),(77,46,43,0,33,42,44,2,1061214366,1,120,'',0),(78,46,44,0,34,43,45,2,1061214366,1,120,'',0),(79,46,45,0,35,44,22,2,1061214366,1,121,'',0),(80,46,22,0,36,45,46,2,1061214366,1,121,'',0),(81,46,46,0,37,22,45,2,1061214366,1,121,'',0),(82,46,45,0,38,46,22,2,1061214366,1,121,'',0),(83,46,22,0,39,45,46,2,1061214366,1,121,'',0),(84,46,46,0,40,22,47,2,1061214366,1,121,'',0),(85,46,47,0,41,46,48,2,1061214366,1,121,'',0),(86,46,48,0,42,47,49,2,1061214366,1,121,'',0),(87,46,49,0,43,48,50,2,1061214366,1,121,'',0),(88,46,50,0,44,49,24,2,1061214366,1,121,'',0),(89,46,24,0,45,50,51,2,1061214366,1,121,'',0),(90,46,51,0,46,24,52,2,1061214366,1,121,'',0),(91,46,52,0,47,51,53,2,1061214366,1,121,'',0),(92,46,53,0,48,52,54,2,1061214366,1,121,'',0),(93,46,54,0,49,53,55,2,1061214366,1,121,'',0),(94,46,55,0,50,54,56,2,1061214366,1,121,'',0),(95,46,56,0,51,55,25,2,1061214366,1,121,'',0),(96,46,25,0,52,56,57,2,1061214366,1,121,'',0),(97,46,57,0,53,25,58,2,1061214366,1,121,'',0),(98,46,58,0,54,57,59,2,1061214366,1,121,'',0),(99,46,59,0,55,58,59,2,1061214366,1,121,'',0),(100,46,59,0,56,59,44,2,1061214366,1,121,'',0),(101,46,44,0,57,59,60,2,1061214366,1,121,'',0),(102,46,60,0,58,44,38,2,1061214366,1,121,'',0),(103,46,38,0,59,60,15,2,1061214366,1,121,'',0),(104,46,15,0,60,38,61,2,1061214366,1,121,'',0),(105,46,61,0,61,15,62,2,1061214366,1,121,'',0),(106,46,62,0,62,61,63,2,1061214366,1,121,'',0),(107,46,63,0,63,62,18,2,1061214366,1,121,'',0),(108,46,18,0,64,63,64,2,1061214366,1,121,'',0),(109,46,64,0,65,18,60,2,1061214366,1,121,'',0),(110,46,60,0,66,64,65,2,1061214366,1,121,'',0),(111,46,65,0,67,60,66,2,1061214366,1,121,'',0),(112,46,66,0,68,65,67,2,1061214366,1,121,'',0),(113,46,67,0,69,66,68,2,1061214366,1,121,'',0),(114,46,68,0,70,67,69,2,1061214366,1,121,'',0),(115,46,69,0,71,68,70,2,1061214366,1,121,'',0),(116,46,70,0,72,69,71,2,1061214366,1,121,'',0),(117,46,71,0,73,70,72,2,1061214366,1,121,'',0),(118,46,72,0,74,71,73,2,1061214366,1,121,'',0),(119,46,73,0,75,72,74,2,1061214366,1,121,'',0),(120,46,74,0,76,73,62,2,1061214366,1,121,'',0),(121,46,62,0,77,74,75,2,1061214366,1,121,'',0),(122,46,75,0,78,62,35,2,1061214366,1,121,'',0),(123,46,35,0,79,75,37,2,1061214366,1,121,'',0),(124,46,37,0,80,35,76,2,1061214366,1,121,'',0),(125,46,76,0,81,37,77,2,1061214366,1,121,'',0),(126,46,77,0,82,76,78,2,1061214366,1,121,'',0),(127,46,78,0,83,77,47,2,1061214366,1,121,'',0),(128,46,47,0,84,78,78,2,1061214366,1,121,'',0),(129,46,78,0,85,47,79,2,1061214366,1,121,'',0),(130,46,79,0,86,78,80,2,1061214366,1,121,'',0),(131,46,80,0,87,79,81,2,1061214366,1,121,'',0),(132,46,81,0,88,80,82,2,1061214366,1,121,'',0),(133,46,82,0,89,81,83,2,1061214366,1,121,'',0),(134,46,83,0,90,82,31,2,1061214366,1,121,'',0),(135,46,31,0,91,83,62,2,1061214366,1,121,'',0),(136,46,62,0,92,31,84,2,1061214366,1,121,'',0),(137,46,84,0,93,62,85,2,1061214366,1,121,'',0),(138,46,85,0,94,84,52,2,1061214366,1,121,'',0),(139,46,52,0,95,85,80,2,1061214366,1,121,'',0),(140,46,80,0,96,52,56,2,1061214366,1,121,'',0),(141,46,56,0,97,80,86,2,1061214366,1,121,'',0),(142,46,86,0,98,56,87,2,1061214366,1,121,'',0),(143,46,87,0,99,86,88,2,1061214366,1,121,'',0),(144,46,88,0,100,87,89,2,1061214366,1,121,'',0),(145,46,89,0,101,88,90,2,1061214366,1,121,'',0),(146,46,90,0,102,89,91,2,1061214366,1,121,'',0),(147,46,91,0,103,90,92,2,1061214366,1,121,'',0),(148,46,92,0,104,91,93,2,1061214366,1,121,'',0),(149,46,93,0,105,92,85,2,1061214366,1,121,'',0),(150,46,85,0,106,93,31,2,1061214366,1,121,'',0),(151,46,31,0,107,85,74,2,1061214366,1,121,'',0),(152,46,74,0,108,31,94,2,1061214366,1,121,'',0),(153,46,94,0,109,74,95,2,1061214366,1,121,'',0),(154,46,95,0,110,94,31,2,1061214366,1,121,'',0),(155,46,31,0,111,95,56,2,1061214366,1,121,'',0),(156,46,56,0,112,31,26,2,1061214366,1,121,'',0),(157,46,26,0,113,56,96,2,1061214366,1,121,'',0),(158,46,96,0,114,26,29,2,1061214366,1,121,'',0),(159,46,29,0,115,96,97,2,1061214366,1,121,'',0),(160,46,97,0,116,29,98,2,1061214366,1,121,'',0),(161,46,98,0,117,97,99,2,1061214366,1,121,'',0),(162,46,99,0,118,98,29,2,1061214366,1,121,'',0),(163,46,29,0,119,99,100,2,1061214366,1,121,'',0),(164,46,100,0,120,29,101,2,1061214366,1,121,'',0),(165,46,101,0,121,100,102,2,1061214366,1,121,'',0),(166,46,102,0,122,101,31,2,1061214366,1,121,'',0),(167,46,31,0,123,102,20,2,1061214366,1,121,'',0),(168,46,20,0,124,31,21,2,1061214366,1,121,'',0),(169,46,21,0,125,20,103,2,1061214366,1,121,'',0),(170,46,103,0,126,21,104,2,1061214366,1,121,'',0),(171,46,104,0,127,103,105,2,1061214366,1,121,'',0),(172,46,105,0,128,104,53,2,1061214366,1,121,'',0),(173,46,53,0,129,105,106,2,1061214366,1,121,'',0),(174,46,106,0,130,53,89,2,1061214366,1,121,'',0),(175,46,89,0,131,106,93,2,1061214366,1,121,'',0),(176,46,93,0,132,89,107,2,1061214366,1,121,'',0),(177,46,107,0,133,93,108,2,1061214366,1,121,'',0),(178,46,108,0,134,107,38,2,1061214366,1,121,'',0),(179,46,38,0,135,108,109,2,1061214366,1,121,'',0),(180,46,109,0,136,38,78,2,1061214366,1,121,'',0),(181,46,78,0,137,109,73,2,1061214366,1,121,'',0),(182,46,73,0,138,78,86,2,1061214366,1,121,'',0),(183,46,86,0,139,73,110,2,1061214366,1,121,'',0),(184,46,110,0,140,86,93,2,1061214366,1,121,'',0),(185,46,93,0,141,110,111,2,1061214366,1,121,'',0),(186,46,111,0,142,93,65,2,1061214366,1,121,'',0),(187,46,65,0,143,111,112,2,1061214366,1,121,'',0),(188,46,112,0,144,65,98,2,1061214366,1,121,'',0),(189,46,98,0,145,112,20,2,1061214366,1,121,'',0),(190,46,20,0,146,98,21,2,1061214366,1,121,'',0),(191,46,21,0,147,20,61,2,1061214366,1,121,'',0),(192,46,61,0,148,21,108,2,1061214366,1,121,'',0),(193,46,108,0,149,61,113,2,1061214366,1,121,'',0),(194,46,113,0,150,108,27,2,1061214366,1,121,'',0),(195,46,27,0,151,113,114,2,1061214366,1,121,'',0),(196,46,114,0,152,27,115,2,1061214366,1,121,'',0),(197,46,115,0,153,114,35,2,1061214366,1,121,'',0),(198,46,35,0,154,115,114,2,1061214366,1,121,'',0),(199,46,114,0,155,35,57,2,1061214366,1,121,'',0),(200,46,57,0,156,114,116,2,1061214366,1,121,'',0),(201,46,116,0,157,57,117,2,1061214366,1,121,'',0),(202,46,117,0,158,116,118,2,1061214366,1,121,'',0),(203,46,118,0,159,117,90,2,1061214366,1,121,'',0),(204,46,90,0,160,118,63,2,1061214366,1,121,'',0),(205,46,63,0,161,90,119,2,1061214366,1,121,'',0),(206,46,119,0,162,63,62,2,1061214366,1,121,'',0),(207,46,62,0,163,119,83,2,1061214366,1,121,'',0),(208,46,83,0,164,62,68,2,1061214366,1,121,'',0),(209,46,68,0,165,83,120,2,1061214366,1,121,'',0),(210,46,120,0,166,68,121,2,1061214366,1,121,'',0),(211,46,121,0,167,120,74,2,1061214366,1,121,'',0),(212,46,74,0,168,121,74,2,1061214366,1,121,'',0),(213,46,74,0,169,74,80,2,1061214366,1,121,'',0),(214,46,80,0,170,74,63,2,1061214366,1,121,'',0),(215,46,63,0,171,80,33,2,1061214366,1,121,'',0),(216,46,33,0,172,63,30,2,1061214366,1,121,'',0),(217,46,30,0,173,33,18,2,1061214366,1,121,'',0),(218,46,18,0,174,30,78,2,1061214366,1,121,'',0),(219,46,78,0,175,18,29,2,1061214366,1,121,'',0),(220,46,29,0,176,78,110,2,1061214366,1,121,'',0),(221,46,110,0,177,29,35,2,1061214366,1,121,'',0),(222,46,35,0,178,110,122,2,1061214366,1,121,'',0),(223,46,122,0,179,35,123,2,1061214366,1,121,'',0),(224,46,123,0,180,122,18,2,1061214366,1,121,'',0),(225,46,18,0,181,123,35,2,1061214366,1,121,'',0),(226,46,35,0,182,18,124,2,1061214366,1,121,'',0),(227,46,124,0,183,35,74,2,1061214366,1,121,'',0),(228,46,74,0,184,124,60,2,1061214366,1,121,'',0),(229,46,60,0,185,74,53,2,1061214366,1,121,'',0),(230,46,53,0,186,60,25,2,1061214366,1,121,'',0),(231,46,25,0,187,53,68,2,1061214366,1,121,'',0),(232,46,68,0,188,25,66,2,1061214366,1,121,'',0),(233,46,66,0,189,68,92,2,1061214366,1,121,'',0),(234,46,92,0,190,66,106,2,1061214366,1,121,'',0),(235,46,106,0,191,92,125,2,1061214366,1,121,'',0),(236,46,125,0,192,106,35,2,1061214366,1,121,'',0),(237,46,35,0,193,125,103,2,1061214366,1,121,'',0),(238,46,103,0,194,35,83,2,1061214366,1,121,'',0),(239,46,83,0,195,103,80,2,1061214366,1,121,'',0),(240,46,80,0,196,83,35,2,1061214366,1,121,'',0),(241,46,35,0,197,80,62,2,1061214366,1,121,'',0),(242,46,62,0,198,35,93,2,1061214366,1,121,'',0),(243,46,93,0,199,62,55,2,1061214366,1,121,'',0),(244,46,55,0,200,93,15,2,1061214366,1,121,'',0),(245,46,15,0,201,55,52,2,1061214366,1,121,'',0),(246,46,52,0,202,15,45,2,1061214366,1,121,'',0),(247,46,45,0,203,52,82,2,1061214366,1,121,'',0),(248,46,82,0,204,45,126,2,1061214366,1,121,'',0),(249,46,126,0,205,82,42,2,1061214366,1,121,'',0),(250,46,42,0,206,126,73,2,1061214366,1,121,'',0),(251,46,73,0,207,42,55,2,1061214366,1,121,'',0),(252,46,55,0,208,73,82,2,1061214366,1,121,'',0),(253,46,82,0,209,55,119,2,1061214366,1,121,'',0),(254,46,119,0,210,82,127,2,1061214366,1,121,'',0),(255,46,127,0,211,119,119,2,1061214366,1,121,'',0),(256,46,119,0,212,127,127,2,1061214366,1,121,'',0),(257,46,127,0,213,119,62,2,1061214366,1,121,'',0),(258,46,62,0,214,127,128,2,1061214366,1,121,'',0),(259,46,128,0,215,62,119,2,1061214366,1,121,'',0),(260,46,119,0,216,128,93,2,1061214366,1,121,'',0),(261,46,93,0,217,119,116,2,1061214366,1,121,'',0),(262,46,116,0,218,93,77,2,1061214366,1,121,'',0),(263,46,77,0,219,116,103,2,1061214366,1,121,'',0),(264,46,103,0,220,77,97,2,1061214366,1,121,'',0),(265,46,97,0,221,103,129,2,1061214366,1,121,'',0),(266,46,129,0,222,97,130,2,1061214366,1,121,'',0),(267,46,130,0,223,129,91,2,1061214366,1,121,'',0),(268,46,91,0,224,130,76,2,1061214366,1,121,'',0),(269,46,76,0,225,91,33,2,1061214366,1,121,'',0),(270,46,33,0,226,76,19,2,1061214366,1,121,'',0),(271,46,19,0,227,33,117,2,1061214366,1,121,'',0),(272,46,117,0,228,19,128,2,1061214366,1,121,'',0),(273,46,128,0,229,117,50,2,1061214366,1,121,'',0),(274,46,50,0,230,128,92,2,1061214366,1,121,'',0),(275,46,92,0,231,50,90,2,1061214366,1,121,'',0),(276,46,90,0,232,92,75,2,1061214366,1,121,'',0),(277,46,75,0,233,90,31,2,1061214366,1,121,'',0),(278,46,31,0,234,75,118,2,1061214366,1,121,'',0),(279,46,118,0,235,31,131,2,1061214366,1,121,'',0),(280,46,131,0,236,118,131,2,1061214366,1,121,'',0),(281,46,131,0,237,131,132,2,1061214366,1,121,'',0),(282,46,132,0,238,131,133,2,1061214366,1,121,'',0),(283,46,133,0,239,132,100,2,1061214366,1,121,'',0),(284,46,100,0,240,133,51,2,1061214366,1,121,'',0),(285,46,51,0,241,100,134,2,1061214366,1,121,'',0),(286,46,134,0,242,51,135,2,1061214366,1,121,'',0),(287,46,135,0,243,134,17,2,1061214366,1,121,'',0),(288,46,17,0,244,135,34,2,1061214366,1,121,'',0),(289,46,34,0,245,17,22,2,1061214366,1,121,'',0),(290,46,22,0,246,34,110,2,1061214366,1,121,'',0),(291,46,110,0,247,22,47,2,1061214366,1,121,'',0),(292,46,47,0,248,110,84,2,1061214366,1,121,'',0),(293,46,84,0,249,47,136,2,1061214366,1,121,'',0),(294,46,136,0,250,84,137,2,1061214366,1,121,'',0),(295,46,137,0,251,136,47,2,1061214366,1,121,'',0),(296,46,47,0,252,137,26,2,1061214366,1,121,'',0),(297,46,26,0,253,47,119,2,1061214366,1,121,'',0),(298,46,119,0,254,26,102,2,1061214366,1,121,'',0),(299,46,102,0,255,119,104,2,1061214366,1,121,'',0),(300,46,104,0,256,102,129,2,1061214366,1,121,'',0),(301,46,129,0,257,104,134,2,1061214366,1,121,'',0),(302,46,134,0,258,129,125,2,1061214366,1,121,'',0),(303,46,125,0,259,134,116,2,1061214366,1,121,'',0),(304,46,116,0,260,125,29,2,1061214366,1,121,'',0),(305,46,29,0,261,116,63,2,1061214366,1,121,'',0),(306,46,63,0,262,29,114,2,1061214366,1,121,'',0),(307,46,114,0,263,63,46,2,1061214366,1,121,'',0),(308,46,46,0,264,114,94,2,1061214366,1,121,'',0),(309,46,94,0,265,46,138,2,1061214366,1,121,'',0),(310,46,138,0,266,94,53,2,1061214366,1,121,'',0),(311,46,53,0,267,138,139,2,1061214366,1,121,'',0),(312,46,139,0,268,53,25,2,1061214366,1,121,'',0),(313,46,25,0,269,139,140,2,1061214366,1,121,'',0),(314,46,140,0,270,25,95,2,1061214366,1,121,'',0),(315,46,95,0,271,140,136,2,1061214366,1,121,'',0),(316,46,136,0,272,95,101,2,1061214366,1,121,'',0),(317,46,101,0,273,136,60,2,1061214366,1,121,'',0),(318,46,60,0,274,101,115,2,1061214366,1,121,'',0),(319,46,115,0,275,60,100,2,1061214366,1,121,'',0),(320,46,100,0,276,115,32,2,1061214366,1,121,'',0),(321,46,32,0,277,100,79,2,1061214366,1,121,'',0),(322,46,79,0,278,32,107,2,1061214366,1,121,'',0),(323,46,107,0,279,79,49,2,1061214366,1,121,'',0),(324,46,49,0,280,107,114,2,1061214366,1,121,'',0),(325,46,114,0,281,49,141,2,1061214366,1,121,'',0),(326,46,141,0,282,114,108,2,1061214366,1,121,'',0),(327,46,108,0,283,141,135,2,1061214366,1,121,'',0),(328,46,135,0,284,108,124,2,1061214366,1,121,'',0),(329,46,124,0,285,135,34,2,1061214366,1,121,'',0),(330,46,34,0,286,124,117,2,1061214366,1,121,'',0),(331,46,117,0,287,34,107,2,1061214366,1,121,'',0),(332,46,107,0,288,117,19,2,1061214366,1,121,'',0),(333,46,19,0,289,107,142,2,1061214366,1,121,'',0),(334,46,142,0,290,19,86,2,1061214366,1,121,'',0),(335,46,86,0,291,142,105,2,1061214366,1,121,'',0),(336,46,105,0,292,86,15,2,1061214366,1,121,'',0),(337,46,15,0,293,105,65,2,1061214366,1,121,'',0),(338,46,65,0,294,15,90,2,1061214366,1,121,'',0),(339,46,90,0,295,65,141,2,1061214366,1,121,'',0),(340,46,141,0,296,90,143,2,1061214366,1,121,'',0),(341,46,143,0,297,141,144,2,1061214366,1,121,'',0),(342,46,144,0,298,143,145,2,1061214366,1,121,'',0),(343,46,145,0,299,144,146,2,1061214366,1,121,'',0),(344,46,146,0,300,145,68,2,1061214366,1,121,'',0),(345,46,68,0,301,146,147,2,1061214366,1,121,'',0),(346,46,147,0,302,68,148,2,1061214366,1,121,'',0),(347,46,148,0,303,147,149,2,1061214366,1,121,'',0),(348,46,149,0,304,148,150,2,1061214366,1,121,'',0),(349,46,150,0,305,149,151,2,1061214366,1,121,'',0),(350,46,151,0,306,150,152,2,1061214366,1,121,'',0),(351,46,152,0,307,151,153,2,1061214366,1,121,'',0),(352,46,153,0,308,152,35,2,1061214366,1,121,'',0),(353,46,35,0,309,153,54,2,1061214366,1,121,'',0),(354,46,54,0,310,35,85,2,1061214366,1,121,'',0),(355,46,85,0,311,54,154,2,1061214366,1,121,'',0),(356,46,154,0,312,85,24,2,1061214366,1,121,'',0),(357,46,24,0,313,154,99,2,1061214366,1,121,'',0),(358,46,99,0,314,24,99,2,1061214366,1,121,'',0),(359,46,99,0,315,99,155,2,1061214366,1,121,'',0),(360,46,155,0,316,99,24,2,1061214366,1,121,'',0),(361,46,24,0,317,155,53,2,1061214366,1,121,'',0),(362,46,53,0,318,24,89,2,1061214366,1,121,'',0),(363,46,89,0,319,53,116,2,1061214366,1,121,'',0),(364,46,116,0,320,89,84,2,1061214366,1,121,'',0),(365,46,84,0,321,116,76,2,1061214366,1,121,'',0),(366,46,76,0,322,84,82,2,1061214366,1,121,'',0),(367,46,82,0,323,76,156,2,1061214366,1,121,'',0),(368,46,156,0,324,82,60,2,1061214366,1,121,'',0),(369,46,60,0,325,156,136,2,1061214366,1,121,'',0),(370,46,136,0,326,60,79,2,1061214366,1,121,'',0),(371,46,79,0,327,136,82,2,1061214366,1,121,'',0),(372,46,82,0,328,79,25,2,1061214366,1,121,'',0),(373,46,25,0,329,82,63,2,1061214366,1,121,'',0),(374,46,63,0,330,25,98,2,1061214366,1,121,'',0),(375,46,98,0,331,63,59,2,1061214366,1,121,'',0),(376,46,59,0,332,98,26,2,1061214366,1,121,'',0),(377,46,26,0,333,59,108,2,1061214366,1,121,'',0),(378,46,108,0,334,26,15,2,1061214366,1,121,'',0),(379,46,15,0,335,108,63,2,1061214366,1,121,'',0),(380,46,63,0,336,15,119,2,1061214366,1,121,'',0),(381,46,119,0,337,63,93,2,1061214366,1,121,'',0),(382,46,93,0,338,119,74,2,1061214366,1,121,'',0),(383,46,74,0,339,93,132,2,1061214366,1,121,'',0),(384,46,132,0,340,74,157,2,1061214366,1,121,'',0),(385,46,157,0,341,132,158,2,1061214366,1,121,'',0),(386,46,158,0,342,157,24,2,1061214366,1,121,'',0),(387,46,24,0,343,158,159,2,1061214366,1,121,'',0),(388,46,159,0,344,24,86,2,1061214366,1,121,'',0),(389,46,86,0,345,159,100,2,1061214366,1,121,'',0),(390,46,100,0,346,86,108,2,1061214366,1,121,'',0),(391,46,108,0,347,100,160,2,1061214366,1,121,'',0),(392,46,160,0,348,108,70,2,1061214366,1,121,'',0),(393,46,70,0,349,160,161,2,1061214366,1,121,'',0),(394,46,161,0,350,70,25,2,1061214366,1,121,'',0),(395,46,25,0,351,161,111,2,1061214366,1,121,'',0),(396,46,111,0,352,25,62,2,1061214366,1,121,'',0),(397,46,62,0,353,111,29,2,1061214366,1,121,'',0),(398,46,29,0,354,62,107,2,1061214366,1,121,'',0),(399,46,107,0,355,29,29,2,1061214366,1,121,'',0),(400,46,29,0,356,107,17,2,1061214366,1,121,'',0),(401,46,17,0,357,29,97,2,1061214366,1,121,'',0),(402,46,97,0,358,17,162,2,1061214366,1,121,'',0),(403,46,162,0,359,97,163,2,1061214366,1,121,'',0),(404,46,163,0,360,162,138,2,1061214366,1,121,'',0),(405,46,138,0,361,163,140,2,1061214366,1,121,'',0),(406,46,140,0,362,138,42,2,1061214366,1,121,'',0),(407,46,42,0,363,140,117,2,1061214366,1,121,'',0),(408,46,117,0,364,42,20,2,1061214366,1,121,'',0),(409,46,20,0,365,117,21,2,1061214366,1,121,'',0),(410,46,21,0,366,20,24,2,1061214366,1,121,'',0),(411,46,24,0,367,21,15,2,1061214366,1,121,'',0),(412,46,15,0,368,24,100,2,1061214366,1,121,'',0),(413,46,100,0,369,15,164,2,1061214366,1,121,'',0),(414,46,164,0,370,100,154,2,1061214366,1,121,'',0),(415,46,154,0,371,164,49,2,1061214366,1,121,'',0),(416,46,49,0,372,154,165,2,1061214366,1,121,'',0),(417,46,165,0,373,49,133,2,1061214366,1,121,'',0),(418,46,133,0,374,165,89,2,1061214366,1,121,'',0),(419,46,89,0,375,133,76,2,1061214366,1,121,'',0),(420,46,76,0,376,89,43,2,1061214366,1,121,'',0),(421,46,43,0,377,76,41,2,1061214366,1,121,'',0),(422,46,41,0,378,43,51,2,1061214366,1,121,'',0),(423,46,51,0,379,41,34,2,1061214366,1,121,'',0),(424,46,34,0,380,51,73,2,1061214366,1,121,'',0),(425,46,73,0,381,34,33,2,1061214366,1,121,'',0),(426,46,33,0,382,73,88,2,1061214366,1,121,'',0),(427,46,88,0,383,33,20,2,1061214366,1,121,'',0),(428,46,20,0,384,88,21,2,1061214366,1,121,'',0),(429,46,21,0,385,20,57,2,1061214366,1,121,'',0),(430,46,57,0,386,21,112,2,1061214366,1,121,'',0),(431,46,112,0,387,57,166,2,1061214366,1,121,'',0),(432,46,166,0,388,112,104,2,1061214366,1,121,'',0),(433,46,104,0,389,166,167,2,1061214366,1,121,'',0),(434,46,167,0,390,104,113,2,1061214366,1,121,'',0),(435,46,113,0,391,167,85,2,1061214366,1,121,'',0),(436,46,85,0,392,113,98,2,1061214366,1,121,'',0),(437,46,98,0,393,85,93,2,1061214366,1,121,'',0),(438,46,93,0,394,98,52,2,1061214366,1,121,'',0),(439,46,52,0,395,93,33,2,1061214366,1,121,'',0),(440,46,33,0,396,52,128,2,1061214366,1,121,'',0),(441,46,128,0,397,33,30,2,1061214366,1,121,'',0),(442,46,30,0,398,128,117,2,1061214366,1,121,'',0),(443,46,117,0,399,30,168,2,1061214366,1,121,'',0),(444,46,168,0,400,117,169,2,1061214366,1,121,'',0),(445,46,169,0,401,168,170,2,1061214366,1,121,'',0),(446,46,170,0,402,169,171,2,1061214366,1,121,'',0),(447,46,171,0,403,170,172,2,1061214366,1,121,'',0),(448,46,172,0,404,171,173,2,1061214366,1,121,'',0),(449,46,173,0,405,172,174,2,1061214366,1,121,'',0),(450,46,174,0,406,173,175,2,1061214366,1,121,'',0),(451,46,175,0,407,174,176,2,1061214366,1,121,'',0),(452,46,176,0,408,175,177,2,1061214366,1,121,'',0),(453,46,177,0,409,176,175,2,1061214366,1,121,'',0),(454,46,175,0,410,177,178,2,1061214366,1,121,'',0),(455,46,178,0,411,175,179,2,1061214366,1,121,'',0),(456,46,179,0,412,178,130,2,1061214366,1,121,'',0),(457,46,130,0,413,179,106,2,1061214366,1,121,'',0),(458,46,106,0,414,130,180,2,1061214366,1,121,'',0),(459,46,180,0,415,106,85,2,1061214366,1,121,'',0),(460,46,85,0,416,180,53,2,1061214366,1,121,'',0),(461,46,53,0,417,85,135,2,1061214366,1,121,'',0),(462,46,135,0,418,53,57,2,1061214366,1,121,'',0),(463,46,57,0,419,135,29,2,1061214366,1,121,'',0),(464,46,29,0,420,57,118,2,1061214366,1,121,'',0),(465,46,118,0,421,29,119,2,1061214366,1,121,'',0),(466,46,119,0,422,118,88,2,1061214366,1,121,'',0),(467,46,88,0,423,119,116,2,1061214366,1,121,'',0),(468,46,116,0,424,88,32,2,1061214366,1,121,'',0),(469,46,32,0,425,116,114,2,1061214366,1,121,'',0),(470,46,114,0,426,32,166,2,1061214366,1,121,'',0),(471,46,166,0,427,114,31,2,1061214366,1,121,'',0),(472,46,31,0,428,166,102,2,1061214366,1,121,'',0),(473,46,102,0,429,31,33,2,1061214366,1,121,'',0),(474,46,33,0,430,102,34,2,1061214366,1,121,'',0),(475,46,34,0,431,33,181,2,1061214366,1,121,'',0),(476,46,181,0,432,34,138,2,1061214366,1,121,'',0),(477,46,138,0,433,181,74,2,1061214366,1,121,'',0),(478,46,74,0,434,138,35,2,1061214366,1,121,'',0),(479,46,35,0,435,74,135,2,1061214366,1,121,'',0),(480,46,135,0,436,35,123,2,1061214366,1,121,'',0),(481,46,123,0,437,135,66,2,1061214366,1,121,'',0),(482,46,66,0,438,123,116,2,1061214366,1,121,'',0),(483,46,116,0,439,66,105,2,1061214366,1,121,'',0),(484,46,105,0,440,116,48,2,1061214366,1,121,'',0),(485,46,48,0,441,105,47,2,1061214366,1,121,'',0),(486,46,47,0,442,48,154,2,1061214366,1,121,'',0),(487,46,154,0,443,47,115,2,1061214366,1,121,'',0),(488,46,115,0,444,154,46,2,1061214366,1,121,'',0),(489,46,46,0,445,115,68,2,1061214366,1,121,'',0),(490,46,68,0,446,46,26,2,1061214366,1,121,'',0),(491,46,26,0,447,68,35,2,1061214366,1,121,'',0),(492,46,35,0,448,26,105,2,1061214366,1,121,'',0),(493,46,105,0,449,35,94,2,1061214366,1,121,'',0),(494,46,94,0,450,105,57,2,1061214366,1,121,'',0),(495,46,57,0,451,94,81,2,1061214366,1,121,'',0),(496,46,81,0,452,57,36,2,1061214366,1,121,'',0),(497,46,36,0,453,81,89,2,1061214366,1,121,'',0),(498,46,89,0,454,36,182,2,1061214366,1,121,'',0),(499,46,182,0,455,89,35,2,1061214366,1,121,'',0),(500,46,35,0,456,182,124,2,1061214366,1,121,'',0),(501,46,124,0,457,35,86,2,1061214366,1,121,'',0),(502,46,86,0,458,124,32,2,1061214366,1,121,'',0),(503,46,32,0,459,86,31,2,1061214366,1,121,'',0),(504,46,31,0,460,32,48,2,1061214366,1,121,'',0),(505,46,48,0,461,31,76,2,1061214366,1,121,'',0),(506,46,76,0,462,48,124,2,1061214366,1,121,'',0),(507,46,124,0,463,76,26,2,1061214366,1,121,'',0),(508,46,26,0,464,124,183,2,1061214366,1,121,'',0),(509,46,183,0,465,26,56,2,1061214366,1,121,'',0),(510,46,56,0,466,183,81,2,1061214366,1,121,'',0),(511,46,81,0,467,56,44,2,1061214366,1,121,'',0),(512,46,44,0,468,81,138,2,1061214366,1,121,'',0),(513,46,138,0,469,44,110,2,1061214366,1,121,'',0),(514,46,110,0,470,138,0,2,1061214366,1,121,'',0),(934,47,82,0,209,55,0,10,1061215045,1,141,'',0),(933,47,55,0,208,73,82,10,1061215045,1,141,'',0),(932,47,73,0,207,42,55,10,1061215045,1,141,'',0),(931,47,42,0,206,126,73,10,1061215045,1,141,'',0),(930,47,126,0,205,82,42,10,1061215045,1,141,'',0),(929,47,82,0,204,45,126,10,1061215045,1,141,'',0),(928,47,45,0,203,52,82,10,1061215045,1,141,'',0),(927,47,52,0,202,15,45,10,1061215045,1,141,'',0),(926,47,15,0,201,55,52,10,1061215045,1,141,'',0),(925,47,55,0,200,93,15,10,1061215045,1,141,'',0),(924,47,93,0,199,62,55,10,1061215045,1,141,'',0),(923,47,62,0,198,35,93,10,1061215045,1,141,'',0),(922,47,35,0,197,80,62,10,1061215045,1,141,'',0),(921,47,80,0,196,83,35,10,1061215045,1,141,'',0),(920,47,83,0,195,103,80,10,1061215045,1,141,'',0),(919,47,103,0,194,35,83,10,1061215045,1,141,'',0),(918,47,35,0,193,125,103,10,1061215045,1,141,'',0),(917,47,125,0,192,106,35,10,1061215045,1,141,'',0),(916,47,106,0,191,92,125,10,1061215045,1,141,'',0),(915,47,92,0,190,66,106,10,1061215045,1,141,'',0),(914,47,66,0,189,68,92,10,1061215045,1,141,'',0),(913,47,68,0,188,25,66,10,1061215045,1,141,'',0),(912,47,25,0,187,53,68,10,1061215045,1,141,'',0),(911,47,53,0,186,60,25,10,1061215045,1,141,'',0),(910,47,60,0,185,74,53,10,1061215045,1,141,'',0),(909,47,74,0,184,124,60,10,1061215045,1,141,'',0),(908,47,124,0,183,35,74,10,1061215045,1,141,'',0),(907,47,35,0,182,18,124,10,1061215045,1,141,'',0),(906,47,18,0,181,123,35,10,1061215045,1,141,'',0),(905,47,123,0,180,122,18,10,1061215045,1,141,'',0),(904,47,122,0,179,35,123,10,1061215045,1,141,'',0),(903,47,35,0,178,110,122,10,1061215045,1,141,'',0),(902,47,110,0,177,29,35,10,1061215045,1,141,'',0),(901,47,29,0,176,78,110,10,1061215045,1,141,'',0),(900,47,78,0,175,18,29,10,1061215045,1,141,'',0),(899,47,18,0,174,30,78,10,1061215045,1,141,'',0),(898,47,30,0,173,33,18,10,1061215045,1,141,'',0),(897,47,33,0,172,63,30,10,1061215045,1,141,'',0),(896,47,63,0,171,80,33,10,1061215045,1,141,'',0),(895,47,80,0,170,74,63,10,1061215045,1,141,'',0),(894,47,74,0,169,74,80,10,1061215045,1,141,'',0),(893,47,74,0,168,121,74,10,1061215045,1,141,'',0),(892,47,121,0,167,120,74,10,1061215045,1,141,'',0),(891,47,120,0,166,68,121,10,1061215045,1,141,'',0),(890,47,68,0,165,83,120,10,1061215045,1,141,'',0),(889,47,83,0,164,62,68,10,1061215045,1,141,'',0),(888,47,62,0,163,119,83,10,1061215045,1,141,'',0),(887,47,119,0,162,63,62,10,1061215045,1,141,'',0),(886,47,63,0,161,90,119,10,1061215045,1,141,'',0),(885,47,90,0,160,118,63,10,1061215045,1,141,'',0),(884,47,118,0,159,117,90,10,1061215045,1,141,'',0),(883,47,117,0,158,116,118,10,1061215045,1,141,'',0),(882,47,116,0,157,57,117,10,1061215045,1,141,'',0),(881,47,57,0,156,114,116,10,1061215045,1,141,'',0),(880,47,114,0,155,35,57,10,1061215045,1,141,'',0),(879,47,35,0,154,115,114,10,1061215045,1,141,'',0),(878,47,115,0,153,114,35,10,1061215045,1,141,'',0),(877,47,114,0,152,27,115,10,1061215045,1,141,'',0),(876,47,27,0,151,113,114,10,1061215045,1,141,'',0),(875,47,113,0,150,108,27,10,1061215045,1,141,'',0),(874,47,108,0,149,61,113,10,1061215045,1,141,'',0),(873,47,61,0,148,21,108,10,1061215045,1,141,'',0),(872,47,21,0,147,20,61,10,1061215045,1,141,'',0),(871,47,20,0,146,98,21,10,1061215045,1,141,'',0),(870,47,98,0,145,112,20,10,1061215045,1,141,'',0),(869,47,112,0,144,65,98,10,1061215045,1,141,'',0),(868,47,65,0,143,111,112,10,1061215045,1,141,'',0),(867,47,111,0,142,93,65,10,1061215045,1,141,'',0),(866,47,93,0,141,110,111,10,1061215045,1,141,'',0),(865,47,110,0,140,86,93,10,1061215045,1,141,'',0),(864,47,86,0,139,73,110,10,1061215045,1,141,'',0),(863,47,73,0,138,78,86,10,1061215045,1,141,'',0),(862,47,78,0,137,109,73,10,1061215045,1,141,'',0),(861,47,109,0,136,38,78,10,1061215045,1,141,'',0),(860,47,38,0,135,108,109,10,1061215045,1,141,'',0),(859,47,108,0,134,107,38,10,1061215045,1,141,'',0),(858,47,107,0,133,93,108,10,1061215045,1,141,'',0),(857,47,93,0,132,89,107,10,1061215045,1,141,'',0),(856,47,89,0,131,106,93,10,1061215045,1,141,'',0),(855,47,106,0,130,53,89,10,1061215045,1,141,'',0),(854,47,53,0,129,105,106,10,1061215045,1,141,'',0),(853,47,105,0,128,104,53,10,1061215045,1,141,'',0),(852,47,104,0,127,103,105,10,1061215045,1,141,'',0),(851,47,103,0,126,21,104,10,1061215045,1,141,'',0),(850,47,21,0,125,20,103,10,1061215045,1,141,'',0),(849,47,20,0,124,31,21,10,1061215045,1,141,'',0),(848,47,31,0,123,102,20,10,1061215045,1,141,'',0),(847,47,102,0,122,101,31,10,1061215045,1,141,'',0),(846,47,101,0,121,100,102,10,1061215045,1,141,'',0),(845,47,100,0,120,29,101,10,1061215045,1,141,'',0),(844,47,29,0,119,99,100,10,1061215045,1,141,'',0),(843,47,99,0,118,98,29,10,1061215045,1,141,'',0),(842,47,98,0,117,97,99,10,1061215045,1,141,'',0),(841,47,97,0,116,29,98,10,1061215045,1,141,'',0),(840,47,29,0,115,96,97,10,1061215045,1,141,'',0),(839,47,96,0,114,26,29,10,1061215045,1,141,'',0),(838,47,26,0,113,56,96,10,1061215045,1,141,'',0),(837,47,56,0,112,31,26,10,1061215045,1,141,'',0),(836,47,31,0,111,95,56,10,1061215045,1,141,'',0),(835,47,95,0,110,94,31,10,1061215045,1,141,'',0),(834,47,94,0,109,74,95,10,1061215045,1,141,'',0),(833,47,74,0,108,31,94,10,1061215045,1,141,'',0),(832,47,31,0,107,85,74,10,1061215045,1,141,'',0),(831,47,85,0,106,93,31,10,1061215045,1,141,'',0),(830,47,93,0,105,92,85,10,1061215045,1,141,'',0),(829,47,92,0,104,91,93,10,1061215045,1,141,'',0),(828,47,91,0,103,90,92,10,1061215045,1,141,'',0),(827,47,90,0,102,89,91,10,1061215045,1,141,'',0),(826,47,89,0,101,88,90,10,1061215045,1,141,'',0),(825,47,88,0,100,87,89,10,1061215045,1,141,'',0),(824,47,87,0,99,86,88,10,1061215045,1,141,'',0),(823,47,86,0,98,56,87,10,1061215045,1,141,'',0),(822,47,56,0,97,80,86,10,1061215045,1,141,'',0),(821,47,80,0,96,52,56,10,1061215045,1,141,'',0),(820,47,52,0,95,85,80,10,1061215045,1,141,'',0),(819,47,85,0,94,84,52,10,1061215045,1,141,'',0),(818,47,84,0,93,62,85,10,1061215045,1,141,'',0),(817,47,62,0,92,31,84,10,1061215045,1,141,'',0),(816,47,31,0,91,83,62,10,1061215045,1,141,'',0),(815,47,83,0,90,82,31,10,1061215045,1,141,'',0),(814,47,82,0,89,81,83,10,1061215045,1,141,'',0),(813,47,81,0,88,80,82,10,1061215045,1,141,'',0),(812,47,80,0,87,79,81,10,1061215045,1,141,'',0),(811,47,79,0,86,78,80,10,1061215045,1,141,'',0),(810,47,78,0,85,47,79,10,1061215045,1,141,'',0),(809,47,47,0,84,78,78,10,1061215045,1,141,'',0),(808,47,78,0,83,77,47,10,1061215045,1,141,'',0),(807,47,77,0,82,76,78,10,1061215045,1,141,'',0),(806,47,76,0,81,37,77,10,1061215045,1,141,'',0),(805,47,37,0,80,35,76,10,1061215045,1,141,'',0),(804,47,35,0,79,75,37,10,1061215045,1,141,'',0),(803,47,75,0,78,62,35,10,1061215045,1,141,'',0),(802,47,62,0,77,74,75,10,1061215045,1,141,'',0),(801,47,74,0,76,73,62,10,1061215045,1,141,'',0),(800,47,73,0,75,72,74,10,1061215045,1,141,'',0),(799,47,72,0,74,71,73,10,1061215045,1,141,'',0),(798,47,71,0,73,70,72,10,1061215045,1,141,'',0),(797,47,70,0,72,69,71,10,1061215045,1,141,'',0),(796,47,69,0,71,68,70,10,1061215045,1,141,'',0),(795,47,68,0,70,67,69,10,1061215045,1,141,'',0),(794,47,67,0,69,66,68,10,1061215045,1,141,'',0),(793,47,66,0,68,65,67,10,1061215045,1,141,'',0),(792,47,65,0,67,60,66,10,1061215045,1,141,'',0),(791,47,60,0,66,64,65,10,1061215045,1,141,'',0),(790,47,64,0,65,18,60,10,1061215045,1,141,'',0),(789,47,18,0,64,63,64,10,1061215045,1,141,'',0),(788,47,63,0,63,62,18,10,1061215045,1,141,'',0),(787,47,62,0,62,61,63,10,1061215045,1,141,'',0),(786,47,61,0,61,15,62,10,1061215045,1,141,'',0),(785,47,15,0,60,38,61,10,1061215045,1,141,'',0),(784,47,38,0,59,60,15,10,1061215045,1,141,'',0),(783,47,60,0,58,44,38,10,1061215045,1,141,'',0),(782,47,44,0,57,59,60,10,1061215045,1,141,'',0),(781,47,59,0,56,59,44,10,1061215045,1,141,'',0),(780,47,59,0,55,58,59,10,1061215045,1,141,'',0),(779,47,58,0,54,57,59,10,1061215045,1,141,'',0),(778,47,57,0,53,25,58,10,1061215045,1,141,'',0),(777,47,25,0,52,56,57,10,1061215045,1,141,'',0),(776,47,56,0,51,55,25,10,1061215045,1,141,'',0),(775,47,55,0,50,54,56,10,1061215045,1,141,'',0),(774,47,54,0,49,53,55,10,1061215045,1,141,'',0),(773,47,53,0,48,52,54,10,1061215045,1,141,'',0),(772,47,52,0,47,51,53,10,1061215045,1,141,'',0),(771,47,51,0,46,24,52,10,1061215045,1,141,'',0),(770,47,24,0,45,50,51,10,1061215045,1,141,'',0),(769,47,50,0,44,49,24,10,1061215045,1,141,'',0),(768,47,49,0,43,48,50,10,1061215045,1,141,'',0),(767,47,48,0,42,47,49,10,1061215045,1,141,'',0),(766,47,47,0,41,46,48,10,1061215045,1,141,'',0),(765,47,46,0,40,22,47,10,1061215045,1,141,'',0),(764,47,22,0,39,45,46,10,1061215045,1,141,'',0),(763,47,45,0,38,46,22,10,1061215045,1,141,'',0),(762,47,46,0,37,22,45,10,1061215045,1,141,'',0),(761,47,22,0,36,45,46,10,1061215045,1,141,'',0),(760,47,45,0,35,44,22,10,1061215045,1,141,'',0),(759,47,44,0,34,43,45,10,1061215045,1,141,'',0),(758,47,43,0,33,42,44,10,1061215045,1,141,'',0),(757,47,42,0,32,33,43,10,1061215045,1,141,'',0),(756,47,33,0,31,41,42,10,1061215045,1,141,'',0),(755,47,41,0,30,40,33,10,1061215045,1,141,'',0),(754,47,40,0,29,39,41,10,1061215045,1,141,'',0),(753,47,39,0,28,38,40,10,1061215045,1,141,'',0),(752,47,38,0,27,37,39,10,1061215045,1,141,'',0),(751,47,37,0,26,36,38,10,1061215045,1,141,'',0),(750,47,36,0,25,29,37,10,1061215045,1,141,'',0),(749,47,29,0,24,18,36,10,1061215045,1,141,'',0),(748,47,18,0,23,31,29,10,1061215045,1,141,'',0),(747,47,31,0,22,35,18,10,1061215045,1,141,'',0),(746,47,35,0,21,34,31,10,1061215045,1,141,'',0),(745,47,34,0,20,33,35,10,1061215045,1,141,'',0),(744,47,33,0,19,32,34,10,1061215045,1,141,'',0),(743,47,32,0,18,31,33,10,1061215045,1,141,'',0),(742,47,31,0,17,30,32,10,1061215045,1,141,'',0),(741,47,30,0,16,29,31,10,1061215045,1,141,'',0),(740,47,29,0,15,28,30,10,1061215045,1,141,'',0),(739,47,28,0,14,27,29,10,1061215045,1,141,'',0),(738,47,27,0,13,26,28,10,1061215045,1,141,'',0),(737,47,26,0,12,25,27,10,1061215045,1,141,'',0),(736,47,25,0,11,24,26,10,1061215045,1,141,'',0),(735,47,24,0,10,23,25,10,1061215045,1,141,'',0),(734,47,23,0,9,22,24,10,1061215045,1,141,'',0),(733,47,22,0,8,21,23,10,1061215045,1,141,'',0),(732,47,21,0,7,20,22,10,1061215045,1,141,'',0),(731,47,20,0,6,19,21,10,1061215045,1,141,'',0),(730,47,19,0,5,18,20,10,1061215045,1,141,'',0),(729,47,18,0,4,17,19,10,1061215045,1,141,'',0),(728,47,17,0,3,7,18,10,1061215045,1,141,'',0),(727,47,7,0,2,185,17,10,1061215045,1,140,'',0),(726,47,185,0,1,9,7,10,1061215045,1,140,'',0),(725,47,9,0,0,0,185,10,1061215045,1,140,'',0),(935,48,186,0,0,0,0,1,1061215659,1,4,'',0),(936,49,187,0,0,0,0,1,1061215680,1,4,'',0),(937,51,15,0,0,0,187,2,1061215750,1,1,'',0),(938,51,187,0,1,15,188,2,1061215750,1,1,'',0),(939,51,188,0,2,187,17,2,1061215750,1,1,'',0),(940,51,17,0,3,188,18,2,1061215750,1,120,'',0),(941,51,18,0,4,17,19,2,1061215750,1,120,'',0),(942,51,19,0,5,18,20,2,1061215750,1,120,'',0),(943,51,20,0,6,19,21,2,1061215750,1,120,'',0),(944,51,21,0,7,20,22,2,1061215750,1,120,'',0),(945,51,22,0,8,21,23,2,1061215750,1,120,'',0),(946,51,23,0,9,22,24,2,1061215750,1,120,'',0),(947,51,24,0,10,23,25,2,1061215750,1,120,'',0),(948,51,25,0,11,24,26,2,1061215750,1,120,'',0),(949,51,26,0,12,25,27,2,1061215750,1,120,'',0),(950,51,27,0,13,26,28,2,1061215750,1,120,'',0),(951,51,28,0,14,27,29,2,1061215750,1,120,'',0),(952,51,29,0,15,28,30,2,1061215750,1,120,'',0),(953,51,30,0,16,29,31,2,1061215750,1,120,'',0),(954,51,31,0,17,30,32,2,1061215750,1,120,'',0),(955,51,32,0,18,31,33,2,1061215750,1,120,'',0),(956,51,33,0,19,32,34,2,1061215750,1,120,'',0),(957,51,34,0,20,33,35,2,1061215750,1,120,'',0),(958,51,35,0,21,34,31,2,1061215750,1,120,'',0),(959,51,31,0,22,35,18,2,1061215750,1,120,'',0),(960,51,18,0,23,31,29,2,1061215750,1,120,'',0),(961,51,29,0,24,18,36,2,1061215750,1,120,'',0),(962,51,36,0,25,29,37,2,1061215750,1,120,'',0),(963,51,37,0,26,36,38,2,1061215750,1,120,'',0),(964,51,38,0,27,37,39,2,1061215750,1,120,'',0),(965,51,39,0,28,38,40,2,1061215750,1,120,'',0),(966,51,40,0,29,39,41,2,1061215750,1,120,'',0),(967,51,41,0,30,40,33,2,1061215750,1,120,'',0),(968,51,33,0,31,41,42,2,1061215750,1,120,'',0),(969,51,42,0,32,33,43,2,1061215750,1,120,'',0),(970,51,43,0,33,42,44,2,1061215750,1,120,'',0),(971,51,44,0,34,43,45,2,1061215750,1,120,'',0),(972,51,45,0,35,44,22,2,1061215750,1,121,'',0),(973,51,22,0,36,45,46,2,1061215750,1,121,'',0),(974,51,46,0,37,22,45,2,1061215750,1,121,'',0),(975,51,45,0,38,46,22,2,1061215750,1,121,'',0),(976,51,22,0,39,45,46,2,1061215750,1,121,'',0),(977,51,46,0,40,22,47,2,1061215750,1,121,'',0),(978,51,47,0,41,46,48,2,1061215750,1,121,'',0),(979,51,48,0,42,47,49,2,1061215750,1,121,'',0),(980,51,49,0,43,48,50,2,1061215750,1,121,'',0),(981,51,50,0,44,49,24,2,1061215750,1,121,'',0),(982,51,24,0,45,50,51,2,1061215750,1,121,'',0),(983,51,51,0,46,24,52,2,1061215750,1,121,'',0),(984,51,52,0,47,51,53,2,1061215750,1,121,'',0),(985,51,53,0,48,52,54,2,1061215750,1,121,'',0),(986,51,54,0,49,53,55,2,1061215750,1,121,'',0),(987,51,55,0,50,54,56,2,1061215750,1,121,'',0),(988,51,56,0,51,55,25,2,1061215750,1,121,'',0),(989,51,25,0,52,56,57,2,1061215750,1,121,'',0),(990,51,57,0,53,25,58,2,1061215750,1,121,'',0),(991,51,58,0,54,57,59,2,1061215750,1,121,'',0),(992,51,59,0,55,58,59,2,1061215750,1,121,'',0),(993,51,59,0,56,59,44,2,1061215750,1,121,'',0),(994,51,44,0,57,59,60,2,1061215750,1,121,'',0),(995,51,60,0,58,44,38,2,1061215750,1,121,'',0),(996,51,38,0,59,60,15,2,1061215750,1,121,'',0),(997,51,15,0,60,38,61,2,1061215750,1,121,'',0),(998,51,61,0,61,15,62,2,1061215750,1,121,'',0),(999,51,62,0,62,61,63,2,1061215750,1,121,'',0),(1000,51,63,0,63,62,18,2,1061215750,1,121,'',0),(1001,51,18,0,64,63,64,2,1061215750,1,121,'',0),(1002,51,64,0,65,18,60,2,1061215750,1,121,'',0),(1003,51,60,0,66,64,65,2,1061215750,1,121,'',0),(1004,51,65,0,67,60,66,2,1061215750,1,121,'',0),(1005,51,66,0,68,65,67,2,1061215750,1,121,'',0),(1006,51,67,0,69,66,68,2,1061215750,1,121,'',0),(1007,51,68,0,70,67,69,2,1061215750,1,121,'',0),(1008,51,69,0,71,68,70,2,1061215750,1,121,'',0),(1009,51,70,0,72,69,71,2,1061215750,1,121,'',0),(1010,51,71,0,73,70,72,2,1061215750,1,121,'',0),(1011,51,72,0,74,71,73,2,1061215750,1,121,'',0),(1012,51,73,0,75,72,74,2,1061215750,1,121,'',0),(1013,51,74,0,76,73,62,2,1061215750,1,121,'',0),(1014,51,62,0,77,74,75,2,1061215750,1,121,'',0),(1015,51,75,0,78,62,35,2,1061215750,1,121,'',0),(1016,51,35,0,79,75,37,2,1061215750,1,121,'',0),(1017,51,37,0,80,35,76,2,1061215750,1,121,'',0),(1018,51,76,0,81,37,77,2,1061215750,1,121,'',0),(1019,51,77,0,82,76,78,2,1061215750,1,121,'',0),(1020,51,78,0,83,77,47,2,1061215750,1,121,'',0),(1021,51,47,0,84,78,78,2,1061215750,1,121,'',0),(1022,51,78,0,85,47,79,2,1061215750,1,121,'',0),(1023,51,79,0,86,78,80,2,1061215750,1,121,'',0),(1024,51,80,0,87,79,81,2,1061215750,1,121,'',0),(1025,51,81,0,88,80,82,2,1061215750,1,121,'',0),(1026,51,82,0,89,81,83,2,1061215750,1,121,'',0),(1027,51,83,0,90,82,31,2,1061215750,1,121,'',0),(1028,51,31,0,91,83,62,2,1061215750,1,121,'',0),(1029,51,62,0,92,31,84,2,1061215750,1,121,'',0),(1030,51,84,0,93,62,85,2,1061215750,1,121,'',0),(1031,51,85,0,94,84,52,2,1061215750,1,121,'',0),(1032,51,52,0,95,85,80,2,1061215750,1,121,'',0),(1033,51,80,0,96,52,56,2,1061215750,1,121,'',0),(1034,51,56,0,97,80,86,2,1061215750,1,121,'',0),(1035,51,86,0,98,56,87,2,1061215750,1,121,'',0),(1036,51,87,0,99,86,88,2,1061215750,1,121,'',0),(1037,51,88,0,100,87,89,2,1061215750,1,121,'',0),(1038,51,89,0,101,88,90,2,1061215750,1,121,'',0),(1039,51,90,0,102,89,91,2,1061215750,1,121,'',0),(1040,51,91,0,103,90,92,2,1061215750,1,121,'',0),(1041,51,92,0,104,91,93,2,1061215750,1,121,'',0),(1042,51,93,0,105,92,85,2,1061215750,1,121,'',0),(1043,51,85,0,106,93,31,2,1061215750,1,121,'',0),(1044,51,31,0,107,85,74,2,1061215750,1,121,'',0),(1045,51,74,0,108,31,94,2,1061215750,1,121,'',0),(1046,51,94,0,109,74,95,2,1061215750,1,121,'',0),(1047,51,95,0,110,94,31,2,1061215750,1,121,'',0),(1048,51,31,0,111,95,56,2,1061215750,1,121,'',0),(1049,51,56,0,112,31,26,2,1061215750,1,121,'',0),(1050,51,26,0,113,56,96,2,1061215750,1,121,'',0),(1051,51,96,0,114,26,29,2,1061215750,1,121,'',0),(1052,51,29,0,115,96,97,2,1061215750,1,121,'',0),(1053,51,97,0,116,29,98,2,1061215750,1,121,'',0),(1054,51,98,0,117,97,99,2,1061215750,1,121,'',0),(1055,51,99,0,118,98,29,2,1061215750,1,121,'',0),(1056,51,29,0,119,99,100,2,1061215750,1,121,'',0),(1057,51,100,0,120,29,101,2,1061215750,1,121,'',0),(1058,51,101,0,121,100,102,2,1061215750,1,121,'',0),(1059,51,102,0,122,101,31,2,1061215750,1,121,'',0),(1060,51,31,0,123,102,20,2,1061215750,1,121,'',0),(1061,51,20,0,124,31,21,2,1061215750,1,121,'',0),(1062,51,21,0,125,20,103,2,1061215750,1,121,'',0),(1063,51,103,0,126,21,104,2,1061215750,1,121,'',0),(1064,51,104,0,127,103,105,2,1061215750,1,121,'',0),(1065,51,105,0,128,104,53,2,1061215750,1,121,'',0),(1066,51,53,0,129,105,106,2,1061215750,1,121,'',0),(1067,51,106,0,130,53,89,2,1061215750,1,121,'',0),(1068,51,89,0,131,106,93,2,1061215750,1,121,'',0),(1069,51,93,0,132,89,107,2,1061215750,1,121,'',0),(1070,51,107,0,133,93,108,2,1061215750,1,121,'',0),(1071,51,108,0,134,107,38,2,1061215750,1,121,'',0),(1072,51,38,0,135,108,109,2,1061215750,1,121,'',0),(1073,51,109,0,136,38,78,2,1061215750,1,121,'',0),(1074,51,78,0,137,109,73,2,1061215750,1,121,'',0),(1075,51,73,0,138,78,86,2,1061215750,1,121,'',0),(1076,51,86,0,139,73,110,2,1061215750,1,121,'',0),(1077,51,110,0,140,86,93,2,1061215750,1,121,'',0),(1078,51,93,0,141,110,111,2,1061215750,1,121,'',0),(1079,51,111,0,142,93,65,2,1061215750,1,121,'',0),(1080,51,65,0,143,111,112,2,1061215750,1,121,'',0),(1081,51,112,0,144,65,98,2,1061215750,1,121,'',0),(1082,51,98,0,145,112,20,2,1061215750,1,121,'',0),(1083,51,20,0,146,98,21,2,1061215750,1,121,'',0),(1084,51,21,0,147,20,61,2,1061215750,1,121,'',0),(1085,51,61,0,148,21,108,2,1061215750,1,121,'',0),(1086,51,108,0,149,61,113,2,1061215750,1,121,'',0),(1087,51,113,0,150,108,27,2,1061215750,1,121,'',0),(1088,51,27,0,151,113,114,2,1061215750,1,121,'',0),(1089,51,114,0,152,27,115,2,1061215750,1,121,'',0),(1090,51,115,0,153,114,35,2,1061215750,1,121,'',0),(1091,51,35,0,154,115,114,2,1061215750,1,121,'',0),(1092,51,114,0,155,35,57,2,1061215750,1,121,'',0),(1093,51,57,0,156,114,116,2,1061215750,1,121,'',0),(1094,51,116,0,157,57,117,2,1061215750,1,121,'',0),(1095,51,117,0,158,116,118,2,1061215750,1,121,'',0),(1096,51,118,0,159,117,90,2,1061215750,1,121,'',0),(1097,51,90,0,160,118,63,2,1061215750,1,121,'',0),(1098,51,63,0,161,90,119,2,1061215750,1,121,'',0),(1099,51,119,0,162,63,62,2,1061215750,1,121,'',0),(1100,51,62,0,163,119,83,2,1061215750,1,121,'',0),(1101,51,83,0,164,62,68,2,1061215750,1,121,'',0),(1102,51,68,0,165,83,120,2,1061215750,1,121,'',0),(1103,51,120,0,166,68,121,2,1061215750,1,121,'',0),(1104,51,121,0,167,120,74,2,1061215750,1,121,'',0),(1105,51,74,0,168,121,74,2,1061215750,1,121,'',0),(1106,51,74,0,169,74,80,2,1061215750,1,121,'',0),(1107,51,80,0,170,74,63,2,1061215750,1,121,'',0),(1108,51,63,0,171,80,33,2,1061215750,1,121,'',0),(1109,51,33,0,172,63,30,2,1061215750,1,121,'',0),(1110,51,30,0,173,33,18,2,1061215750,1,121,'',0),(1111,51,18,0,174,30,78,2,1061215750,1,121,'',0),(1112,51,78,0,175,18,29,2,1061215750,1,121,'',0),(1113,51,29,0,176,78,110,2,1061215750,1,121,'',0),(1114,51,110,0,177,29,35,2,1061215750,1,121,'',0),(1115,51,35,0,178,110,122,2,1061215750,1,121,'',0),(1116,51,122,0,179,35,123,2,1061215750,1,121,'',0),(1117,51,123,0,180,122,18,2,1061215750,1,121,'',0),(1118,51,18,0,181,123,35,2,1061215750,1,121,'',0),(1119,51,35,0,182,18,124,2,1061215750,1,121,'',0),(1120,51,124,0,183,35,74,2,1061215750,1,121,'',0),(1121,51,74,0,184,124,60,2,1061215750,1,121,'',0),(1122,51,60,0,185,74,53,2,1061215750,1,121,'',0),(1123,51,53,0,186,60,25,2,1061215750,1,121,'',0),(1124,51,25,0,187,53,68,2,1061215750,1,121,'',0),(1125,51,68,0,188,25,66,2,1061215750,1,121,'',0),(1126,51,66,0,189,68,92,2,1061215750,1,121,'',0),(1127,51,92,0,190,66,106,2,1061215750,1,121,'',0),(1128,51,106,0,191,92,125,2,1061215750,1,121,'',0),(1129,51,125,0,192,106,35,2,1061215750,1,121,'',0),(1130,51,35,0,193,125,103,2,1061215750,1,121,'',0),(1131,51,103,0,194,35,83,2,1061215750,1,121,'',0),(1132,51,83,0,195,103,80,2,1061215750,1,121,'',0),(1133,51,80,0,196,83,35,2,1061215750,1,121,'',0),(1134,51,35,0,197,80,62,2,1061215750,1,121,'',0),(1135,51,62,0,198,35,93,2,1061215750,1,121,'',0),(1136,51,93,0,199,62,55,2,1061215750,1,121,'',0),(1137,51,55,0,200,93,15,2,1061215750,1,121,'',0),(1138,51,15,0,201,55,52,2,1061215750,1,121,'',0),(1139,51,52,0,202,15,45,2,1061215750,1,121,'',0),(1140,51,45,0,203,52,82,2,1061215750,1,121,'',0),(1141,51,82,0,204,45,126,2,1061215750,1,121,'',0),(1142,51,126,0,205,82,42,2,1061215750,1,121,'',0),(1143,51,42,0,206,126,73,2,1061215750,1,121,'',0),(1144,51,73,0,207,42,55,2,1061215750,1,121,'',0),(1145,51,55,0,208,73,82,2,1061215750,1,121,'',0),(1146,51,82,0,209,55,119,2,1061215750,1,121,'',0),(1147,51,119,0,210,82,127,2,1061215750,1,121,'',0),(1148,51,127,0,211,119,119,2,1061215750,1,121,'',0),(1149,51,119,0,212,127,127,2,1061215750,1,121,'',0),(1150,51,127,0,213,119,62,2,1061215750,1,121,'',0),(1151,51,62,0,214,127,128,2,1061215750,1,121,'',0),(1152,51,128,0,215,62,119,2,1061215750,1,121,'',0),(1153,51,119,0,216,128,93,2,1061215750,1,121,'',0),(1154,51,93,0,217,119,116,2,1061215750,1,121,'',0),(1155,51,116,0,218,93,77,2,1061215750,1,121,'',0),(1156,51,77,0,219,116,103,2,1061215750,1,121,'',0),(1157,51,103,0,220,77,97,2,1061215750,1,121,'',0),(1158,51,97,0,221,103,129,2,1061215750,1,121,'',0),(1159,51,129,0,222,97,130,2,1061215750,1,121,'',0),(1160,51,130,0,223,129,91,2,1061215750,1,121,'',0),(1161,51,91,0,224,130,76,2,1061215750,1,121,'',0),(1162,51,76,0,225,91,33,2,1061215750,1,121,'',0),(1163,51,33,0,226,76,19,2,1061215750,1,121,'',0),(1164,51,19,0,227,33,117,2,1061215750,1,121,'',0),(1165,51,117,0,228,19,128,2,1061215750,1,121,'',0),(1166,51,128,0,229,117,50,2,1061215750,1,121,'',0),(1167,51,50,0,230,128,92,2,1061215750,1,121,'',0),(1168,51,92,0,231,50,90,2,1061215750,1,121,'',0),(1169,51,90,0,232,92,75,2,1061215750,1,121,'',0),(1170,51,75,0,233,90,31,2,1061215750,1,121,'',0),(1171,51,31,0,234,75,118,2,1061215750,1,121,'',0),(1172,51,118,0,235,31,131,2,1061215750,1,121,'',0),(1173,51,131,0,236,118,131,2,1061215750,1,121,'',0),(1174,51,131,0,237,131,132,2,1061215750,1,121,'',0),(1175,51,132,0,238,131,133,2,1061215750,1,121,'',0),(1176,51,133,0,239,132,100,2,1061215750,1,121,'',0),(1177,51,100,0,240,133,51,2,1061215750,1,121,'',0),(1178,51,51,0,241,100,134,2,1061215750,1,121,'',0),(1179,51,134,0,242,51,135,2,1061215750,1,121,'',0),(1180,51,135,0,243,134,17,2,1061215750,1,121,'',0),(1181,51,17,0,244,135,34,2,1061215750,1,121,'',0),(1182,51,34,0,245,17,22,2,1061215750,1,121,'',0),(1183,51,22,0,246,34,110,2,1061215750,1,121,'',0),(1184,51,110,0,247,22,47,2,1061215750,1,121,'',0),(1185,51,47,0,248,110,84,2,1061215750,1,121,'',0),(1186,51,84,0,249,47,136,2,1061215750,1,121,'',0),(1187,51,136,0,250,84,137,2,1061215750,1,121,'',0),(1188,51,137,0,251,136,47,2,1061215750,1,121,'',0),(1189,51,47,0,252,137,26,2,1061215750,1,121,'',0),(1190,51,26,0,253,47,119,2,1061215750,1,121,'',0),(1191,51,119,0,254,26,102,2,1061215750,1,121,'',0),(1192,51,102,0,255,119,104,2,1061215750,1,121,'',0),(1193,51,104,0,256,102,129,2,1061215750,1,121,'',0),(1194,51,129,0,257,104,134,2,1061215750,1,121,'',0),(1195,51,134,0,258,129,125,2,1061215750,1,121,'',0),(1196,51,125,0,259,134,116,2,1061215750,1,121,'',0),(1197,51,116,0,260,125,29,2,1061215750,1,121,'',0),(1198,51,29,0,261,116,63,2,1061215750,1,121,'',0),(1199,51,63,0,262,29,114,2,1061215750,1,121,'',0),(1200,51,114,0,263,63,46,2,1061215750,1,121,'',0),(1201,51,46,0,264,114,94,2,1061215750,1,121,'',0),(1202,51,94,0,265,46,138,2,1061215750,1,121,'',0),(1203,51,138,0,266,94,53,2,1061215750,1,121,'',0),(1204,51,53,0,267,138,139,2,1061215750,1,121,'',0),(1205,51,139,0,268,53,25,2,1061215750,1,121,'',0),(1206,51,25,0,269,139,140,2,1061215750,1,121,'',0),(1207,51,140,0,270,25,95,2,1061215750,1,121,'',0),(1208,51,95,0,271,140,136,2,1061215750,1,121,'',0),(1209,51,136,0,272,95,101,2,1061215750,1,121,'',0),(1210,51,101,0,273,136,60,2,1061215750,1,121,'',0),(1211,51,60,0,274,101,115,2,1061215750,1,121,'',0),(1212,51,115,0,275,60,100,2,1061215750,1,121,'',0),(1213,51,100,0,276,115,32,2,1061215750,1,121,'',0),(1214,51,32,0,277,100,79,2,1061215750,1,121,'',0),(1215,51,79,0,278,32,107,2,1061215750,1,121,'',0),(1216,51,107,0,279,79,49,2,1061215750,1,121,'',0),(1217,51,49,0,280,107,114,2,1061215750,1,121,'',0),(1218,51,114,0,281,49,141,2,1061215750,1,121,'',0),(1219,51,141,0,282,114,108,2,1061215750,1,121,'',0),(1220,51,108,0,283,141,135,2,1061215750,1,121,'',0),(1221,51,135,0,284,108,124,2,1061215750,1,121,'',0),(1222,51,124,0,285,135,34,2,1061215750,1,121,'',0),(1223,51,34,0,286,124,117,2,1061215750,1,121,'',0),(1224,51,117,0,287,34,107,2,1061215750,1,121,'',0),(1225,51,107,0,288,117,19,2,1061215750,1,121,'',0),(1226,51,19,0,289,107,142,2,1061215750,1,121,'',0),(1227,51,142,0,290,19,86,2,1061215750,1,121,'',0),(1228,51,86,0,291,142,105,2,1061215750,1,121,'',0),(1229,51,105,0,292,86,15,2,1061215750,1,121,'',0),(1230,51,15,0,293,105,65,2,1061215750,1,121,'',0),(1231,51,65,0,294,15,90,2,1061215750,1,121,'',0),(1232,51,90,0,295,65,141,2,1061215750,1,121,'',0),(1233,51,141,0,296,90,143,2,1061215750,1,121,'',0),(1234,51,143,0,297,141,144,2,1061215750,1,121,'',0),(1235,51,144,0,298,143,145,2,1061215750,1,121,'',0),(1236,51,145,0,299,144,146,2,1061215750,1,121,'',0),(1237,51,146,0,300,145,68,2,1061215750,1,121,'',0),(1238,51,68,0,301,146,147,2,1061215750,1,121,'',0),(1239,51,147,0,302,68,148,2,1061215750,1,121,'',0),(1240,51,148,0,303,147,149,2,1061215750,1,121,'',0),(1241,51,149,0,304,148,150,2,1061215750,1,121,'',0),(1242,51,150,0,305,149,151,2,1061215750,1,121,'',0),(1243,51,151,0,306,150,152,2,1061215750,1,121,'',0),(1244,51,152,0,307,151,153,2,1061215750,1,121,'',0),(1245,51,153,0,308,152,35,2,1061215750,1,121,'',0),(1246,51,35,0,309,153,54,2,1061215750,1,121,'',0),(1247,51,54,0,310,35,85,2,1061215750,1,121,'',0),(1248,51,85,0,311,54,154,2,1061215750,1,121,'',0),(1249,51,154,0,312,85,24,2,1061215750,1,121,'',0),(1250,51,24,0,313,154,99,2,1061215750,1,121,'',0),(1251,51,99,0,314,24,99,2,1061215750,1,121,'',0),(1252,51,99,0,315,99,155,2,1061215750,1,121,'',0),(1253,51,155,0,316,99,24,2,1061215750,1,121,'',0),(1254,51,24,0,317,155,53,2,1061215750,1,121,'',0),(1255,51,53,0,318,24,89,2,1061215750,1,121,'',0),(1256,51,89,0,319,53,116,2,1061215750,1,121,'',0),(1257,51,116,0,320,89,84,2,1061215750,1,121,'',0),(1258,51,84,0,321,116,76,2,1061215750,1,121,'',0),(1259,51,76,0,322,84,82,2,1061215750,1,121,'',0),(1260,51,82,0,323,76,156,2,1061215750,1,121,'',0),(1261,51,156,0,324,82,60,2,1061215750,1,121,'',0),(1262,51,60,0,325,156,136,2,1061215750,1,121,'',0),(1263,51,136,0,326,60,79,2,1061215750,1,121,'',0),(1264,51,79,0,327,136,82,2,1061215750,1,121,'',0),(1265,51,82,0,328,79,25,2,1061215750,1,121,'',0),(1266,51,25,0,329,82,63,2,1061215750,1,121,'',0),(1267,51,63,0,330,25,98,2,1061215750,1,121,'',0),(1268,51,98,0,331,63,59,2,1061215750,1,121,'',0),(1269,51,59,0,332,98,26,2,1061215750,1,121,'',0),(1270,51,26,0,333,59,108,2,1061215750,1,121,'',0),(1271,51,108,0,334,26,15,2,1061215750,1,121,'',0),(1272,51,15,0,335,108,63,2,1061215750,1,121,'',0),(1273,51,63,0,336,15,119,2,1061215750,1,121,'',0),(1274,51,119,0,337,63,93,2,1061215750,1,121,'',0),(1275,51,93,0,338,119,74,2,1061215750,1,121,'',0),(1276,51,74,0,339,93,132,2,1061215750,1,121,'',0),(1277,51,132,0,340,74,157,2,1061215750,1,121,'',0),(1278,51,157,0,341,132,158,2,1061215750,1,121,'',0),(1279,51,158,0,342,157,24,2,1061215750,1,121,'',0),(1280,51,24,0,343,158,159,2,1061215750,1,121,'',0),(1281,51,159,0,344,24,86,2,1061215750,1,121,'',0),(1282,51,86,0,345,159,100,2,1061215750,1,121,'',0),(1283,51,100,0,346,86,108,2,1061215750,1,121,'',0),(1284,51,108,0,347,100,160,2,1061215750,1,121,'',0),(1285,51,160,0,348,108,70,2,1061215750,1,121,'',0),(1286,51,70,0,349,160,161,2,1061215750,1,121,'',0),(1287,51,161,0,350,70,25,2,1061215750,1,121,'',0),(1288,51,25,0,351,161,111,2,1061215750,1,121,'',0),(1289,51,111,0,352,25,62,2,1061215750,1,121,'',0),(1290,51,62,0,353,111,29,2,1061215750,1,121,'',0),(1291,51,29,0,354,62,107,2,1061215750,1,121,'',0),(1292,51,107,0,355,29,29,2,1061215750,1,121,'',0),(1293,51,29,0,356,107,17,2,1061215750,1,121,'',0),(1294,51,17,0,357,29,97,2,1061215750,1,121,'',0),(1295,51,97,0,358,17,162,2,1061215750,1,121,'',0),(1296,51,162,0,359,97,163,2,1061215750,1,121,'',0),(1297,51,163,0,360,162,138,2,1061215750,1,121,'',0),(1298,51,138,0,361,163,140,2,1061215750,1,121,'',0),(1299,51,140,0,362,138,42,2,1061215750,1,121,'',0),(1300,51,42,0,363,140,117,2,1061215750,1,121,'',0),(1301,51,117,0,364,42,20,2,1061215750,1,121,'',0),(1302,51,20,0,365,117,21,2,1061215750,1,121,'',0),(1303,51,21,0,366,20,24,2,1061215750,1,121,'',0),(1304,51,24,0,367,21,15,2,1061215750,1,121,'',0),(1305,51,15,0,368,24,100,2,1061215750,1,121,'',0),(1306,51,100,0,369,15,164,2,1061215750,1,121,'',0),(1307,51,164,0,370,100,154,2,1061215750,1,121,'',0),(1308,51,154,0,371,164,49,2,1061215750,1,121,'',0),(1309,51,49,0,372,154,165,2,1061215750,1,121,'',0),(1310,51,165,0,373,49,133,2,1061215750,1,121,'',0),(1311,51,133,0,374,165,89,2,1061215750,1,121,'',0),(1312,51,89,0,375,133,76,2,1061215750,1,121,'',0),(1313,51,76,0,376,89,43,2,1061215750,1,121,'',0),(1314,51,43,0,377,76,41,2,1061215750,1,121,'',0),(1315,51,41,0,378,43,51,2,1061215750,1,121,'',0),(1316,51,51,0,379,41,34,2,1061215750,1,121,'',0),(1317,51,34,0,380,51,73,2,1061215750,1,121,'',0),(1318,51,73,0,381,34,33,2,1061215750,1,121,'',0),(1319,51,33,0,382,73,88,2,1061215750,1,121,'',0),(1320,51,88,0,383,33,20,2,1061215750,1,121,'',0),(1321,51,20,0,384,88,21,2,1061215750,1,121,'',0),(1322,51,21,0,385,20,57,2,1061215750,1,121,'',0),(1323,51,57,0,386,21,112,2,1061215750,1,121,'',0),(1324,51,112,0,387,57,166,2,1061215750,1,121,'',0),(1325,51,166,0,388,112,104,2,1061215750,1,121,'',0),(1326,51,104,0,389,166,167,2,1061215750,1,121,'',0),(1327,51,167,0,390,104,113,2,1061215750,1,121,'',0),(1328,51,113,0,391,167,85,2,1061215750,1,121,'',0),(1329,51,85,0,392,113,98,2,1061215750,1,121,'',0),(1330,51,98,0,393,85,93,2,1061215750,1,121,'',0),(1331,51,93,0,394,98,52,2,1061215750,1,121,'',0),(1332,51,52,0,395,93,33,2,1061215750,1,121,'',0),(1333,51,33,0,396,52,128,2,1061215750,1,121,'',0),(1334,51,128,0,397,33,30,2,1061215750,1,121,'',0),(1335,51,30,0,398,128,117,2,1061215750,1,121,'',0),(1336,51,117,0,399,30,168,2,1061215750,1,121,'',0),(1337,51,168,0,400,117,169,2,1061215750,1,121,'',0),(1338,51,169,0,401,168,170,2,1061215750,1,121,'',0),(1339,51,170,0,402,169,171,2,1061215750,1,121,'',0),(1340,51,171,0,403,170,172,2,1061215750,1,121,'',0),(1341,51,172,0,404,171,173,2,1061215750,1,121,'',0),(1342,51,173,0,405,172,174,2,1061215750,1,121,'',0),(1343,51,174,0,406,173,175,2,1061215750,1,121,'',0),(1344,51,175,0,407,174,176,2,1061215750,1,121,'',0),(1345,51,176,0,408,175,177,2,1061215750,1,121,'',0),(1346,51,177,0,409,176,175,2,1061215750,1,121,'',0),(1347,51,175,0,410,177,178,2,1061215750,1,121,'',0),(1348,51,178,0,411,175,179,2,1061215750,1,121,'',0),(1349,51,179,0,412,178,130,2,1061215750,1,121,'',0),(1350,51,130,0,413,179,106,2,1061215750,1,121,'',0),(1351,51,106,0,414,130,180,2,1061215750,1,121,'',0),(1352,51,180,0,415,106,85,2,1061215750,1,121,'',0),(1353,51,85,0,416,180,53,2,1061215750,1,121,'',0),(1354,51,53,0,417,85,135,2,1061215750,1,121,'',0),(1355,51,135,0,418,53,57,2,1061215750,1,121,'',0),(1356,51,57,0,419,135,29,2,1061215750,1,121,'',0),(1357,51,29,0,420,57,118,2,1061215750,1,121,'',0),(1358,51,118,0,421,29,119,2,1061215750,1,121,'',0),(1359,51,119,0,422,118,88,2,1061215750,1,121,'',0),(1360,51,88,0,423,119,116,2,1061215750,1,121,'',0),(1361,51,116,0,424,88,32,2,1061215750,1,121,'',0),(1362,51,32,0,425,116,114,2,1061215750,1,121,'',0),(1363,51,114,0,426,32,166,2,1061215750,1,121,'',0),(1364,51,166,0,427,114,31,2,1061215750,1,121,'',0),(1365,51,31,0,428,166,102,2,1061215750,1,121,'',0),(1366,51,102,0,429,31,33,2,1061215750,1,121,'',0),(1367,51,33,0,430,102,34,2,1061215750,1,121,'',0),(1368,51,34,0,431,33,181,2,1061215750,1,121,'',0),(1369,51,181,0,432,34,138,2,1061215750,1,121,'',0),(1370,51,138,0,433,181,74,2,1061215750,1,121,'',0),(1371,51,74,0,434,138,35,2,1061215750,1,121,'',0),(1372,51,35,0,435,74,135,2,1061215750,1,121,'',0),(1373,51,135,0,436,35,123,2,1061215750,1,121,'',0),(1374,51,123,0,437,135,66,2,1061215750,1,121,'',0),(1375,51,66,0,438,123,116,2,1061215750,1,121,'',0),(1376,51,116,0,439,66,105,2,1061215750,1,121,'',0),(1377,51,105,0,440,116,48,2,1061215750,1,121,'',0),(1378,51,48,0,441,105,47,2,1061215750,1,121,'',0),(1379,51,47,0,442,48,154,2,1061215750,1,121,'',0),(1380,51,154,0,443,47,115,2,1061215750,1,121,'',0),(1381,51,115,0,444,154,46,2,1061215750,1,121,'',0),(1382,51,46,0,445,115,68,2,1061215750,1,121,'',0),(1383,51,68,0,446,46,26,2,1061215750,1,121,'',0),(1384,51,26,0,447,68,35,2,1061215750,1,121,'',0),(1385,51,35,0,448,26,105,2,1061215750,1,121,'',0),(1386,51,105,0,449,35,94,2,1061215750,1,121,'',0),(1387,51,94,0,450,105,57,2,1061215750,1,121,'',0),(1388,51,57,0,451,94,81,2,1061215750,1,121,'',0),(1389,51,81,0,452,57,36,2,1061215750,1,121,'',0),(1390,51,36,0,453,81,89,2,1061215750,1,121,'',0),(1391,51,89,0,454,36,182,2,1061215750,1,121,'',0),(1392,51,182,0,455,89,35,2,1061215750,1,121,'',0),(1393,51,35,0,456,182,124,2,1061215750,1,121,'',0),(1394,51,124,0,457,35,86,2,1061215750,1,121,'',0),(1395,51,86,0,458,124,32,2,1061215750,1,121,'',0),(1396,51,32,0,459,86,31,2,1061215750,1,121,'',0),(1397,51,31,0,460,32,48,2,1061215750,1,121,'',0),(1398,51,48,0,461,31,76,2,1061215750,1,121,'',0),(1399,51,76,0,462,48,124,2,1061215750,1,121,'',0),(1400,51,124,0,463,76,26,2,1061215750,1,121,'',0),(1401,51,26,0,464,124,183,2,1061215750,1,121,'',0),(1402,51,183,0,465,26,56,2,1061215750,1,121,'',0),(1403,51,56,0,466,183,81,2,1061215750,1,121,'',0),(1404,51,81,0,467,56,44,2,1061215750,1,121,'',0),(1405,51,44,0,468,81,138,2,1061215750,1,121,'',0),(1406,51,138,0,469,44,110,2,1061215750,1,121,'',0),(1407,51,110,0,470,138,0,2,1061215750,1,121,'',0),(1408,52,10,0,0,0,186,2,1061215972,1,1,'',0),(1409,52,186,0,1,10,189,2,1061215972,1,1,'',0),(1410,52,189,0,2,186,17,2,1061215972,1,1,'',0),(1411,52,17,0,3,189,18,2,1061215972,1,120,'',0),(1412,52,18,0,4,17,19,2,1061215972,1,120,'',0),(1413,52,19,0,5,18,20,2,1061215972,1,120,'',0),(1414,52,20,0,6,19,21,2,1061215972,1,120,'',0),(1415,52,21,0,7,20,22,2,1061215972,1,120,'',0),(1416,52,22,0,8,21,23,2,1061215972,1,120,'',0),(1417,52,23,0,9,22,24,2,1061215972,1,120,'',0),(1418,52,24,0,10,23,25,2,1061215972,1,120,'',0),(1419,52,25,0,11,24,26,2,1061215972,1,120,'',0),(1420,52,26,0,12,25,27,2,1061215972,1,120,'',0),(1421,52,27,0,13,26,28,2,1061215972,1,120,'',0),(1422,52,28,0,14,27,29,2,1061215972,1,120,'',0),(1423,52,29,0,15,28,30,2,1061215972,1,120,'',0),(1424,52,30,0,16,29,31,2,1061215972,1,120,'',0),(1425,52,31,0,17,30,32,2,1061215972,1,120,'',0),(1426,52,32,0,18,31,33,2,1061215972,1,120,'',0),(1427,52,33,0,19,32,34,2,1061215972,1,120,'',0),(1428,52,34,0,20,33,35,2,1061215972,1,120,'',0),(1429,52,35,0,21,34,31,2,1061215972,1,120,'',0),(1430,52,31,0,22,35,18,2,1061215972,1,120,'',0),(1431,52,18,0,23,31,29,2,1061215972,1,120,'',0),(1432,52,29,0,24,18,36,2,1061215972,1,120,'',0),(1433,52,36,0,25,29,37,2,1061215972,1,120,'',0),(1434,52,37,0,26,36,38,2,1061215972,1,120,'',0),(1435,52,38,0,27,37,39,2,1061215972,1,120,'',0),(1436,52,39,0,28,38,40,2,1061215972,1,120,'',0),(1437,52,40,0,29,39,41,2,1061215972,1,120,'',0),(1438,52,41,0,30,40,33,2,1061215972,1,120,'',0),(1439,52,33,0,31,41,42,2,1061215972,1,120,'',0),(1440,52,42,0,32,33,43,2,1061215972,1,120,'',0),(1441,52,43,0,33,42,44,2,1061215972,1,120,'',0),(1442,52,44,0,34,43,45,2,1061215972,1,120,'',0),(1443,52,45,0,35,44,22,2,1061215972,1,121,'',0),(1444,52,22,0,36,45,46,2,1061215972,1,121,'',0),(1445,52,46,0,37,22,45,2,1061215972,1,121,'',0),(1446,52,45,0,38,46,22,2,1061215972,1,121,'',0),(1447,52,22,0,39,45,46,2,1061215972,1,121,'',0),(1448,52,46,0,40,22,47,2,1061215972,1,121,'',0),(1449,52,47,0,41,46,48,2,1061215972,1,121,'',0),(1450,52,48,0,42,47,49,2,1061215972,1,121,'',0),(1451,52,49,0,43,48,50,2,1061215972,1,121,'',0),(1452,52,50,0,44,49,24,2,1061215972,1,121,'',0),(1453,52,24,0,45,50,51,2,1061215972,1,121,'',0),(1454,52,51,0,46,24,52,2,1061215972,1,121,'',0),(1455,52,52,0,47,51,53,2,1061215972,1,121,'',0),(1456,52,53,0,48,52,54,2,1061215972,1,121,'',0),(1457,52,54,0,49,53,55,2,1061215972,1,121,'',0),(1458,52,55,0,50,54,56,2,1061215972,1,121,'',0),(1459,52,56,0,51,55,25,2,1061215972,1,121,'',0),(1460,52,25,0,52,56,57,2,1061215972,1,121,'',0),(1461,52,57,0,53,25,58,2,1061215972,1,121,'',0),(1462,52,58,0,54,57,59,2,1061215972,1,121,'',0),(1463,52,59,0,55,58,59,2,1061215972,1,121,'',0),(1464,52,59,0,56,59,44,2,1061215972,1,121,'',0),(1465,52,44,0,57,59,60,2,1061215972,1,121,'',0),(1466,52,60,0,58,44,38,2,1061215972,1,121,'',0),(1467,52,38,0,59,60,15,2,1061215972,1,121,'',0),(1468,52,15,0,60,38,61,2,1061215972,1,121,'',0),(1469,52,61,0,61,15,62,2,1061215972,1,121,'',0),(1470,52,62,0,62,61,63,2,1061215972,1,121,'',0),(1471,52,63,0,63,62,18,2,1061215972,1,121,'',0),(1472,52,18,0,64,63,64,2,1061215972,1,121,'',0),(1473,52,64,0,65,18,60,2,1061215972,1,121,'',0),(1474,52,60,0,66,64,65,2,1061215972,1,121,'',0),(1475,52,65,0,67,60,66,2,1061215972,1,121,'',0),(1476,52,66,0,68,65,67,2,1061215972,1,121,'',0),(1477,52,67,0,69,66,68,2,1061215972,1,121,'',0),(1478,52,68,0,70,67,69,2,1061215972,1,121,'',0),(1479,52,69,0,71,68,70,2,1061215972,1,121,'',0),(1480,52,70,0,72,69,71,2,1061215972,1,121,'',0),(1481,52,71,0,73,70,72,2,1061215972,1,121,'',0),(1482,52,72,0,74,71,73,2,1061215972,1,121,'',0),(1483,52,73,0,75,72,74,2,1061215972,1,121,'',0),(1484,52,74,0,76,73,62,2,1061215972,1,121,'',0),(1485,52,62,0,77,74,75,2,1061215972,1,121,'',0),(1486,52,75,0,78,62,35,2,1061215972,1,121,'',0),(1487,52,35,0,79,75,37,2,1061215972,1,121,'',0),(1488,52,37,0,80,35,76,2,1061215972,1,121,'',0),(1489,52,76,0,81,37,77,2,1061215972,1,121,'',0),(1490,52,77,0,82,76,78,2,1061215972,1,121,'',0),(1491,52,78,0,83,77,47,2,1061215972,1,121,'',0),(1492,52,47,0,84,78,78,2,1061215972,1,121,'',0),(1493,52,78,0,85,47,79,2,1061215972,1,121,'',0),(1494,52,79,0,86,78,80,2,1061215972,1,121,'',0),(1495,52,80,0,87,79,81,2,1061215972,1,121,'',0),(1496,52,81,0,88,80,82,2,1061215972,1,121,'',0),(1497,52,82,0,89,81,83,2,1061215972,1,121,'',0),(1498,52,83,0,90,82,31,2,1061215972,1,121,'',0),(1499,52,31,0,91,83,62,2,1061215972,1,121,'',0),(1500,52,62,0,92,31,84,2,1061215972,1,121,'',0),(1501,52,84,0,93,62,85,2,1061215972,1,121,'',0),(1502,52,85,0,94,84,52,2,1061215972,1,121,'',0),(1503,52,52,0,95,85,80,2,1061215972,1,121,'',0),(1504,52,80,0,96,52,56,2,1061215972,1,121,'',0),(1505,52,56,0,97,80,86,2,1061215972,1,121,'',0),(1506,52,86,0,98,56,87,2,1061215972,1,121,'',0),(1507,52,87,0,99,86,88,2,1061215972,1,121,'',0),(1508,52,88,0,100,87,89,2,1061215972,1,121,'',0),(1509,52,89,0,101,88,90,2,1061215972,1,121,'',0),(1510,52,90,0,102,89,91,2,1061215972,1,121,'',0),(1511,52,91,0,103,90,92,2,1061215972,1,121,'',0),(1512,52,92,0,104,91,93,2,1061215972,1,121,'',0),(1513,52,93,0,105,92,85,2,1061215972,1,121,'',0),(1514,52,85,0,106,93,31,2,1061215972,1,121,'',0),(1515,52,31,0,107,85,74,2,1061215972,1,121,'',0),(1516,52,74,0,108,31,94,2,1061215972,1,121,'',0),(1517,52,94,0,109,74,95,2,1061215972,1,121,'',0),(1518,52,95,0,110,94,31,2,1061215972,1,121,'',0),(1519,52,31,0,111,95,56,2,1061215972,1,121,'',0),(1520,52,56,0,112,31,26,2,1061215972,1,121,'',0),(1521,52,26,0,113,56,96,2,1061215972,1,121,'',0),(1522,52,96,0,114,26,29,2,1061215972,1,121,'',0),(1523,52,29,0,115,96,97,2,1061215972,1,121,'',0),(1524,52,97,0,116,29,98,2,1061215972,1,121,'',0),(1525,52,98,0,117,97,99,2,1061215972,1,121,'',0),(1526,52,99,0,118,98,29,2,1061215972,1,121,'',0),(1527,52,29,0,119,99,100,2,1061215972,1,121,'',0),(1528,52,100,0,120,29,101,2,1061215972,1,121,'',0),(1529,52,101,0,121,100,102,2,1061215972,1,121,'',0),(1530,52,102,0,122,101,31,2,1061215972,1,121,'',0),(1531,52,31,0,123,102,20,2,1061215972,1,121,'',0),(1532,52,20,0,124,31,21,2,1061215972,1,121,'',0),(1533,52,21,0,125,20,103,2,1061215972,1,121,'',0),(1534,52,103,0,126,21,104,2,1061215972,1,121,'',0),(1535,52,104,0,127,103,105,2,1061215972,1,121,'',0),(1536,52,105,0,128,104,53,2,1061215972,1,121,'',0),(1537,52,53,0,129,105,106,2,1061215972,1,121,'',0),(1538,52,106,0,130,53,89,2,1061215972,1,121,'',0),(1539,52,89,0,131,106,93,2,1061215972,1,121,'',0),(1540,52,93,0,132,89,107,2,1061215972,1,121,'',0),(1541,52,107,0,133,93,108,2,1061215972,1,121,'',0),(1542,52,108,0,134,107,38,2,1061215972,1,121,'',0),(1543,52,38,0,135,108,109,2,1061215972,1,121,'',0),(1544,52,109,0,136,38,78,2,1061215972,1,121,'',0),(1545,52,78,0,137,109,73,2,1061215972,1,121,'',0),(1546,52,73,0,138,78,86,2,1061215972,1,121,'',0),(1547,52,86,0,139,73,110,2,1061215972,1,121,'',0),(1548,52,110,0,140,86,93,2,1061215972,1,121,'',0),(1549,52,93,0,141,110,111,2,1061215972,1,121,'',0),(1550,52,111,0,142,93,65,2,1061215972,1,121,'',0),(1551,52,65,0,143,111,112,2,1061215972,1,121,'',0),(1552,52,112,0,144,65,98,2,1061215972,1,121,'',0),(1553,52,98,0,145,112,20,2,1061215972,1,121,'',0),(1554,52,20,0,146,98,21,2,1061215972,1,121,'',0),(1555,52,21,0,147,20,61,2,1061215972,1,121,'',0),(1556,52,61,0,148,21,108,2,1061215972,1,121,'',0),(1557,52,108,0,149,61,113,2,1061215972,1,121,'',0),(1558,52,113,0,150,108,27,2,1061215972,1,121,'',0),(1559,52,27,0,151,113,114,2,1061215972,1,121,'',0),(1560,52,114,0,152,27,115,2,1061215972,1,121,'',0),(1561,52,115,0,153,114,35,2,1061215972,1,121,'',0),(1562,52,35,0,154,115,114,2,1061215972,1,121,'',0),(1563,52,114,0,155,35,57,2,1061215972,1,121,'',0),(1564,52,57,0,156,114,116,2,1061215972,1,121,'',0),(1565,52,116,0,157,57,117,2,1061215972,1,121,'',0),(1566,52,117,0,158,116,118,2,1061215972,1,121,'',0),(1567,52,118,0,159,117,90,2,1061215972,1,121,'',0),(1568,52,90,0,160,118,63,2,1061215972,1,121,'',0),(1569,52,63,0,161,90,119,2,1061215972,1,121,'',0),(1570,52,119,0,162,63,62,2,1061215972,1,121,'',0),(1571,52,62,0,163,119,83,2,1061215972,1,121,'',0),(1572,52,83,0,164,62,68,2,1061215972,1,121,'',0),(1573,52,68,0,165,83,120,2,1061215972,1,121,'',0),(1574,52,120,0,166,68,121,2,1061215972,1,121,'',0),(1575,52,121,0,167,120,74,2,1061215972,1,121,'',0),(1576,52,74,0,168,121,74,2,1061215972,1,121,'',0),(1577,52,74,0,169,74,80,2,1061215972,1,121,'',0),(1578,52,80,0,170,74,63,2,1061215972,1,121,'',0),(1579,52,63,0,171,80,33,2,1061215972,1,121,'',0),(1580,52,33,0,172,63,30,2,1061215972,1,121,'',0),(1581,52,30,0,173,33,18,2,1061215972,1,121,'',0),(1582,52,18,0,174,30,78,2,1061215972,1,121,'',0),(1583,52,78,0,175,18,29,2,1061215972,1,121,'',0),(1584,52,29,0,176,78,110,2,1061215972,1,121,'',0),(1585,52,110,0,177,29,35,2,1061215972,1,121,'',0),(1586,52,35,0,178,110,122,2,1061215972,1,121,'',0),(1587,52,122,0,179,35,123,2,1061215972,1,121,'',0),(1588,52,123,0,180,122,18,2,1061215972,1,121,'',0),(1589,52,18,0,181,123,35,2,1061215972,1,121,'',0),(1590,52,35,0,182,18,124,2,1061215972,1,121,'',0),(1591,52,124,0,183,35,74,2,1061215972,1,121,'',0),(1592,52,74,0,184,124,60,2,1061215972,1,121,'',0),(1593,52,60,0,185,74,53,2,1061215972,1,121,'',0),(1594,52,53,0,186,60,25,2,1061215972,1,121,'',0),(1595,52,25,0,187,53,68,2,1061215972,1,121,'',0),(1596,52,68,0,188,25,66,2,1061215972,1,121,'',0),(1597,52,66,0,189,68,92,2,1061215972,1,121,'',0),(1598,52,92,0,190,66,106,2,1061215972,1,121,'',0),(1599,52,106,0,191,92,125,2,1061215972,1,121,'',0),(1600,52,125,0,192,106,35,2,1061215972,1,121,'',0),(1601,52,35,0,193,125,103,2,1061215972,1,121,'',0),(1602,52,103,0,194,35,83,2,1061215972,1,121,'',0),(1603,52,83,0,195,103,80,2,1061215972,1,121,'',0),(1604,52,80,0,196,83,35,2,1061215972,1,121,'',0),(1605,52,35,0,197,80,62,2,1061215972,1,121,'',0),(1606,52,62,0,198,35,93,2,1061215972,1,121,'',0),(1607,52,93,0,199,62,55,2,1061215972,1,121,'',0),(1608,52,55,0,200,93,15,2,1061215972,1,121,'',0),(1609,52,15,0,201,55,52,2,1061215972,1,121,'',0),(1610,52,52,0,202,15,45,2,1061215972,1,121,'',0),(1611,52,45,0,203,52,82,2,1061215972,1,121,'',0),(1612,52,82,0,204,45,126,2,1061215972,1,121,'',0),(1613,52,126,0,205,82,42,2,1061215972,1,121,'',0),(1614,52,42,0,206,126,73,2,1061215972,1,121,'',0),(1615,52,73,0,207,42,55,2,1061215972,1,121,'',0),(1616,52,55,0,208,73,82,2,1061215972,1,121,'',0),(1617,52,82,0,209,55,119,2,1061215972,1,121,'',0),(1618,52,119,0,210,82,127,2,1061215972,1,121,'',0),(1619,52,127,0,211,119,119,2,1061215972,1,121,'',0),(1620,52,119,0,212,127,127,2,1061215972,1,121,'',0),(1621,52,127,0,213,119,62,2,1061215972,1,121,'',0),(1622,52,62,0,214,127,128,2,1061215972,1,121,'',0),(1623,52,128,0,215,62,119,2,1061215972,1,121,'',0),(1624,52,119,0,216,128,93,2,1061215972,1,121,'',0),(1625,52,93,0,217,119,116,2,1061215972,1,121,'',0),(1626,52,116,0,218,93,77,2,1061215972,1,121,'',0),(1627,52,77,0,219,116,103,2,1061215972,1,121,'',0),(1628,52,103,0,220,77,97,2,1061215972,1,121,'',0),(1629,52,97,0,221,103,129,2,1061215972,1,121,'',0),(1630,52,129,0,222,97,130,2,1061215972,1,121,'',0),(1631,52,130,0,223,129,91,2,1061215972,1,121,'',0),(1632,52,91,0,224,130,76,2,1061215972,1,121,'',0),(1633,52,76,0,225,91,33,2,1061215972,1,121,'',0),(1634,52,33,0,226,76,19,2,1061215972,1,121,'',0),(1635,52,19,0,227,33,117,2,1061215972,1,121,'',0),(1636,52,117,0,228,19,128,2,1061215972,1,121,'',0),(1637,52,128,0,229,117,50,2,1061215972,1,121,'',0),(1638,52,50,0,230,128,92,2,1061215972,1,121,'',0),(1639,52,92,0,231,50,90,2,1061215972,1,121,'',0),(1640,52,90,0,232,92,75,2,1061215972,1,121,'',0),(1641,52,75,0,233,90,31,2,1061215972,1,121,'',0),(1642,52,31,0,234,75,118,2,1061215972,1,121,'',0),(1643,52,118,0,235,31,131,2,1061215972,1,121,'',0),(1644,52,131,0,236,118,131,2,1061215972,1,121,'',0),(1645,52,131,0,237,131,132,2,1061215972,1,121,'',0),(1646,52,132,0,238,131,133,2,1061215972,1,121,'',0),(1647,52,133,0,239,132,100,2,1061215972,1,121,'',0),(1648,52,100,0,240,133,51,2,1061215972,1,121,'',0),(1649,52,51,0,241,100,134,2,1061215972,1,121,'',0),(1650,52,134,0,242,51,135,2,1061215972,1,121,'',0),(1651,52,135,0,243,134,17,2,1061215972,1,121,'',0),(1652,52,17,0,244,135,34,2,1061215972,1,121,'',0),(1653,52,34,0,245,17,22,2,1061215972,1,121,'',0),(1654,52,22,0,246,34,110,2,1061215972,1,121,'',0),(1655,52,110,0,247,22,47,2,1061215972,1,121,'',0),(1656,52,47,0,248,110,84,2,1061215972,1,121,'',0),(1657,52,84,0,249,47,136,2,1061215972,1,121,'',0),(1658,52,136,0,250,84,137,2,1061215972,1,121,'',0),(1659,52,137,0,251,136,47,2,1061215972,1,121,'',0),(1660,52,47,0,252,137,26,2,1061215972,1,121,'',0),(1661,52,26,0,253,47,119,2,1061215972,1,121,'',0),(1662,52,119,0,254,26,102,2,1061215972,1,121,'',0),(1663,52,102,0,255,119,104,2,1061215972,1,121,'',0),(1664,52,104,0,256,102,129,2,1061215972,1,121,'',0),(1665,52,129,0,257,104,134,2,1061215972,1,121,'',0),(1666,52,134,0,258,129,125,2,1061215972,1,121,'',0),(1667,52,125,0,259,134,116,2,1061215972,1,121,'',0),(1668,52,116,0,260,125,29,2,1061215972,1,121,'',0),(1669,52,29,0,261,116,63,2,1061215972,1,121,'',0),(1670,52,63,0,262,29,114,2,1061215972,1,121,'',0),(1671,52,114,0,263,63,46,2,1061215972,1,121,'',0),(1672,52,46,0,264,114,94,2,1061215972,1,121,'',0),(1673,52,94,0,265,46,138,2,1061215972,1,121,'',0),(1674,52,138,0,266,94,53,2,1061215972,1,121,'',0),(1675,52,53,0,267,138,139,2,1061215972,1,121,'',0),(1676,52,139,0,268,53,25,2,1061215972,1,121,'',0),(1677,52,25,0,269,139,140,2,1061215972,1,121,'',0),(1678,52,140,0,270,25,95,2,1061215972,1,121,'',0),(1679,52,95,0,271,140,136,2,1061215972,1,121,'',0),(1680,52,136,0,272,95,101,2,1061215972,1,121,'',0),(1681,52,101,0,273,136,60,2,1061215972,1,121,'',0),(1682,52,60,0,274,101,115,2,1061215972,1,121,'',0),(1683,52,115,0,275,60,100,2,1061215972,1,121,'',0),(1684,52,100,0,276,115,32,2,1061215972,1,121,'',0),(1685,52,32,0,277,100,79,2,1061215972,1,121,'',0),(1686,52,79,0,278,32,107,2,1061215972,1,121,'',0),(1687,52,107,0,279,79,49,2,1061215972,1,121,'',0),(1688,52,49,0,280,107,114,2,1061215972,1,121,'',0),(1689,52,114,0,281,49,141,2,1061215972,1,121,'',0),(1690,52,141,0,282,114,108,2,1061215972,1,121,'',0),(1691,52,108,0,283,141,135,2,1061215972,1,121,'',0),(1692,52,135,0,284,108,124,2,1061215972,1,121,'',0),(1693,52,124,0,285,135,34,2,1061215972,1,121,'',0),(1694,52,34,0,286,124,117,2,1061215972,1,121,'',0),(1695,52,117,0,287,34,107,2,1061215972,1,121,'',0),(1696,52,107,0,288,117,19,2,1061215972,1,121,'',0),(1697,52,19,0,289,107,142,2,1061215972,1,121,'',0),(1698,52,142,0,290,19,86,2,1061215972,1,121,'',0),(1699,52,86,0,291,142,105,2,1061215972,1,121,'',0),(1700,52,105,0,292,86,15,2,1061215972,1,121,'',0),(1701,52,15,0,293,105,65,2,1061215972,1,121,'',0),(1702,52,65,0,294,15,90,2,1061215972,1,121,'',0),(1703,52,90,0,295,65,141,2,1061215972,1,121,'',0),(1704,52,141,0,296,90,143,2,1061215972,1,121,'',0),(1705,52,143,0,297,141,144,2,1061215972,1,121,'',0),(1706,52,144,0,298,143,145,2,1061215972,1,121,'',0),(1707,52,145,0,299,144,146,2,1061215972,1,121,'',0),(1708,52,146,0,300,145,68,2,1061215972,1,121,'',0),(1709,52,68,0,301,146,147,2,1061215972,1,121,'',0),(1710,52,147,0,302,68,148,2,1061215972,1,121,'',0),(1711,52,148,0,303,147,149,2,1061215972,1,121,'',0),(1712,52,149,0,304,148,150,2,1061215972,1,121,'',0),(1713,52,150,0,305,149,151,2,1061215972,1,121,'',0),(1714,52,151,0,306,150,152,2,1061215972,1,121,'',0),(1715,52,152,0,307,151,153,2,1061215972,1,121,'',0),(1716,52,153,0,308,152,35,2,1061215972,1,121,'',0),(1717,52,35,0,309,153,54,2,1061215972,1,121,'',0),(1718,52,54,0,310,35,85,2,1061215972,1,121,'',0),(1719,52,85,0,311,54,154,2,1061215972,1,121,'',0),(1720,52,154,0,312,85,24,2,1061215972,1,121,'',0),(1721,52,24,0,313,154,99,2,1061215972,1,121,'',0),(1722,52,99,0,314,24,99,2,1061215972,1,121,'',0),(1723,52,99,0,315,99,155,2,1061215972,1,121,'',0),(1724,52,155,0,316,99,24,2,1061215972,1,121,'',0),(1725,52,24,0,317,155,53,2,1061215972,1,121,'',0),(1726,52,53,0,318,24,89,2,1061215972,1,121,'',0),(1727,52,89,0,319,53,116,2,1061215972,1,121,'',0),(1728,52,116,0,320,89,84,2,1061215972,1,121,'',0),(1729,52,84,0,321,116,76,2,1061215972,1,121,'',0),(1730,52,76,0,322,84,82,2,1061215972,1,121,'',0),(1731,52,82,0,323,76,156,2,1061215972,1,121,'',0),(1732,52,156,0,324,82,60,2,1061215972,1,121,'',0),(1733,52,60,0,325,156,136,2,1061215972,1,121,'',0),(1734,52,136,0,326,60,79,2,1061215972,1,121,'',0),(1735,52,79,0,327,136,82,2,1061215972,1,121,'',0),(1736,52,82,0,328,79,25,2,1061215972,1,121,'',0),(1737,52,25,0,329,82,63,2,1061215972,1,121,'',0),(1738,52,63,0,330,25,98,2,1061215972,1,121,'',0),(1739,52,98,0,331,63,59,2,1061215972,1,121,'',0),(1740,52,59,0,332,98,26,2,1061215972,1,121,'',0),(1741,52,26,0,333,59,108,2,1061215972,1,121,'',0),(1742,52,108,0,334,26,15,2,1061215972,1,121,'',0),(1743,52,15,0,335,108,63,2,1061215972,1,121,'',0),(1744,52,63,0,336,15,119,2,1061215972,1,121,'',0),(1745,52,119,0,337,63,93,2,1061215972,1,121,'',0),(1746,52,93,0,338,119,74,2,1061215972,1,121,'',0),(1747,52,74,0,339,93,132,2,1061215972,1,121,'',0),(1748,52,132,0,340,74,157,2,1061215972,1,121,'',0),(1749,52,157,0,341,132,158,2,1061215972,1,121,'',0),(1750,52,158,0,342,157,24,2,1061215972,1,121,'',0),(1751,52,24,0,343,158,159,2,1061215972,1,121,'',0),(1752,52,159,0,344,24,86,2,1061215972,1,121,'',0),(1753,52,86,0,345,159,100,2,1061215972,1,121,'',0),(1754,52,100,0,346,86,108,2,1061215972,1,121,'',0),(1755,52,108,0,347,100,160,2,1061215972,1,121,'',0),(1756,52,160,0,348,108,70,2,1061215972,1,121,'',0),(1757,52,70,0,349,160,161,2,1061215972,1,121,'',0),(1758,52,161,0,350,70,25,2,1061215972,1,121,'',0),(1759,52,25,0,351,161,111,2,1061215972,1,121,'',0),(1760,52,111,0,352,25,62,2,1061215972,1,121,'',0),(1761,52,62,0,353,111,29,2,1061215972,1,121,'',0),(1762,52,29,0,354,62,107,2,1061215972,1,121,'',0),(1763,52,107,0,355,29,29,2,1061215972,1,121,'',0),(1764,52,29,0,356,107,17,2,1061215972,1,121,'',0),(1765,52,17,0,357,29,97,2,1061215972,1,121,'',0),(1766,52,97,0,358,17,162,2,1061215972,1,121,'',0),(1767,52,162,0,359,97,163,2,1061215972,1,121,'',0),(1768,52,163,0,360,162,138,2,1061215972,1,121,'',0),(1769,52,138,0,361,163,140,2,1061215972,1,121,'',0),(1770,52,140,0,362,138,42,2,1061215972,1,121,'',0),(1771,52,42,0,363,140,117,2,1061215972,1,121,'',0),(1772,52,117,0,364,42,20,2,1061215972,1,121,'',0),(1773,52,20,0,365,117,21,2,1061215972,1,121,'',0),(1774,52,21,0,366,20,24,2,1061215972,1,121,'',0),(1775,52,24,0,367,21,15,2,1061215972,1,121,'',0),(1776,52,15,0,368,24,100,2,1061215972,1,121,'',0),(1777,52,100,0,369,15,164,2,1061215972,1,121,'',0),(1778,52,164,0,370,100,154,2,1061215972,1,121,'',0),(1779,52,154,0,371,164,49,2,1061215972,1,121,'',0),(1780,52,49,0,372,154,165,2,1061215972,1,121,'',0),(1781,52,165,0,373,49,133,2,1061215972,1,121,'',0),(1782,52,133,0,374,165,89,2,1061215972,1,121,'',0),(1783,52,89,0,375,133,76,2,1061215972,1,121,'',0),(1784,52,76,0,376,89,43,2,1061215972,1,121,'',0),(1785,52,43,0,377,76,41,2,1061215972,1,121,'',0),(1786,52,41,0,378,43,51,2,1061215972,1,121,'',0),(1787,52,51,0,379,41,34,2,1061215972,1,121,'',0),(1788,52,34,0,380,51,73,2,1061215972,1,121,'',0),(1789,52,73,0,381,34,33,2,1061215972,1,121,'',0),(1790,52,33,0,382,73,88,2,1061215972,1,121,'',0),(1791,52,88,0,383,33,20,2,1061215972,1,121,'',0),(1792,52,20,0,384,88,21,2,1061215972,1,121,'',0),(1793,52,21,0,385,20,57,2,1061215972,1,121,'',0),(1794,52,57,0,386,21,112,2,1061215972,1,121,'',0),(1795,52,112,0,387,57,166,2,1061215972,1,121,'',0),(1796,52,166,0,388,112,104,2,1061215972,1,121,'',0),(1797,52,104,0,389,166,167,2,1061215972,1,121,'',0),(1798,52,167,0,390,104,113,2,1061215972,1,121,'',0),(1799,52,113,0,391,167,85,2,1061215972,1,121,'',0),(1800,52,85,0,392,113,98,2,1061215972,1,121,'',0),(1801,52,98,0,393,85,93,2,1061215972,1,121,'',0),(1802,52,93,0,394,98,52,2,1061215972,1,121,'',0),(1803,52,52,0,395,93,33,2,1061215972,1,121,'',0),(1804,52,33,0,396,52,128,2,1061215972,1,121,'',0),(1805,52,128,0,397,33,30,2,1061215972,1,121,'',0),(1806,52,30,0,398,128,117,2,1061215972,1,121,'',0),(1807,52,117,0,399,30,168,2,1061215972,1,121,'',0),(1808,52,168,0,400,117,169,2,1061215972,1,121,'',0),(1809,52,169,0,401,168,170,2,1061215972,1,121,'',0),(1810,52,170,0,402,169,171,2,1061215972,1,121,'',0),(1811,52,171,0,403,170,172,2,1061215972,1,121,'',0),(1812,52,172,0,404,171,173,2,1061215972,1,121,'',0),(1813,52,173,0,405,172,174,2,1061215972,1,121,'',0),(1814,52,174,0,406,173,175,2,1061215972,1,121,'',0),(1815,52,175,0,407,174,176,2,1061215972,1,121,'',0),(1816,52,176,0,408,175,177,2,1061215972,1,121,'',0),(1817,52,177,0,409,176,175,2,1061215972,1,121,'',0),(1818,52,175,0,410,177,178,2,1061215972,1,121,'',0),(1819,52,178,0,411,175,179,2,1061215972,1,121,'',0),(1820,52,179,0,412,178,130,2,1061215972,1,121,'',0),(1821,52,130,0,413,179,106,2,1061215972,1,121,'',0),(1822,52,106,0,414,130,180,2,1061215972,1,121,'',0),(1823,52,180,0,415,106,85,2,1061215972,1,121,'',0),(1824,52,85,0,416,180,53,2,1061215972,1,121,'',0),(1825,52,53,0,417,85,135,2,1061215972,1,121,'',0),(1826,52,135,0,418,53,57,2,1061215972,1,121,'',0),(1827,52,57,0,419,135,29,2,1061215972,1,121,'',0),(1828,52,29,0,420,57,118,2,1061215972,1,121,'',0),(1829,52,118,0,421,29,119,2,1061215972,1,121,'',0),(1830,52,119,0,422,118,88,2,1061215972,1,121,'',0),(1831,52,88,0,423,119,116,2,1061215972,1,121,'',0),(1832,52,116,0,424,88,32,2,1061215972,1,121,'',0),(1833,52,32,0,425,116,114,2,1061215972,1,121,'',0),(1834,52,114,0,426,32,166,2,1061215972,1,121,'',0),(1835,52,166,0,427,114,31,2,1061215972,1,121,'',0),(1836,52,31,0,428,166,102,2,1061215972,1,121,'',0),(1837,52,102,0,429,31,33,2,1061215972,1,121,'',0),(1838,52,33,0,430,102,34,2,1061215972,1,121,'',0),(1839,52,34,0,431,33,181,2,1061215972,1,121,'',0),(1840,52,181,0,432,34,138,2,1061215972,1,121,'',0),(1841,52,138,0,433,181,74,2,1061215972,1,121,'',0),(1842,52,74,0,434,138,35,2,1061215972,1,121,'',0),(1843,52,35,0,435,74,135,2,1061215972,1,121,'',0),(1844,52,135,0,436,35,123,2,1061215972,1,121,'',0),(1845,52,123,0,437,135,66,2,1061215972,1,121,'',0),(1846,52,66,0,438,123,116,2,1061215972,1,121,'',0),(1847,52,116,0,439,66,105,2,1061215972,1,121,'',0),(1848,52,105,0,440,116,48,2,1061215972,1,121,'',0),(1849,52,48,0,441,105,47,2,1061215972,1,121,'',0),(1850,52,47,0,442,48,154,2,1061215972,1,121,'',0),(1851,52,154,0,443,47,115,2,1061215972,1,121,'',0),(1852,52,115,0,444,154,46,2,1061215972,1,121,'',0),(1853,52,46,0,445,115,68,2,1061215972,1,121,'',0),(1854,52,68,0,446,46,26,2,1061215972,1,121,'',0),(1855,52,26,0,447,68,35,2,1061215972,1,121,'',0),(1856,52,35,0,448,26,105,2,1061215972,1,121,'',0),(1857,52,105,0,449,35,94,2,1061215972,1,121,'',0),(1858,52,94,0,450,105,57,2,1061215972,1,121,'',0),(1859,52,57,0,451,94,81,2,1061215972,1,121,'',0),(1860,52,81,0,452,57,36,2,1061215972,1,121,'',0),(1861,52,36,0,453,81,89,2,1061215972,1,121,'',0),(1862,52,89,0,454,36,182,2,1061215972,1,121,'',0),(1863,52,182,0,455,89,35,2,1061215972,1,121,'',0),(1864,52,35,0,456,182,124,2,1061215972,1,121,'',0),(1865,52,124,0,457,35,86,2,1061215972,1,121,'',0),(1866,52,86,0,458,124,32,2,1061215972,1,121,'',0),(1867,52,32,0,459,86,31,2,1061215972,1,121,'',0),(1868,52,31,0,460,32,48,2,1061215972,1,121,'',0),(1869,52,48,0,461,31,76,2,1061215972,1,121,'',0),(1870,52,76,0,462,48,124,2,1061215972,1,121,'',0),(1871,52,124,0,463,76,26,2,1061215972,1,121,'',0),(1872,52,26,0,464,124,183,2,1061215972,1,121,'',0),(1873,52,183,0,465,26,56,2,1061215972,1,121,'',0),(1874,52,56,0,466,183,81,2,1061215972,1,121,'',0),(1875,52,81,0,467,56,44,2,1061215972,1,121,'',0),(1876,52,44,0,468,81,138,2,1061215972,1,121,'',0),(1877,52,138,0,469,44,110,2,1061215972,1,121,'',0),(1878,52,110,0,470,138,0,2,1061215972,1,121,'',0),(1879,53,190,0,0,0,17,10,1061216018,1,140,'',0),(1880,53,17,0,1,190,18,10,1061216018,1,141,'',0),(1881,53,18,0,2,17,19,10,1061216018,1,141,'',0),(1882,53,19,0,3,18,20,10,1061216018,1,141,'',0),(1883,53,20,0,4,19,21,10,1061216018,1,141,'',0),(1884,53,21,0,5,20,22,10,1061216018,1,141,'',0),(1885,53,22,0,6,21,23,10,1061216018,1,141,'',0),(1886,53,23,0,7,22,24,10,1061216018,1,141,'',0),(1887,53,24,0,8,23,25,10,1061216018,1,141,'',0),(1888,53,25,0,9,24,26,10,1061216018,1,141,'',0),(1889,53,26,0,10,25,27,10,1061216018,1,141,'',0),(1890,53,27,0,11,26,28,10,1061216018,1,141,'',0),(1891,53,28,0,12,27,29,10,1061216018,1,141,'',0),(1892,53,29,0,13,28,30,10,1061216018,1,141,'',0),(1893,53,30,0,14,29,31,10,1061216018,1,141,'',0),(1894,53,31,0,15,30,32,10,1061216018,1,141,'',0),(1895,53,32,0,16,31,33,10,1061216018,1,141,'',0),(1896,53,33,0,17,32,34,10,1061216018,1,141,'',0),(1897,53,34,0,18,33,35,10,1061216018,1,141,'',0),(1898,53,35,0,19,34,31,10,1061216018,1,141,'',0),(1899,53,31,0,20,35,18,10,1061216018,1,141,'',0),(1900,53,18,0,21,31,29,10,1061216018,1,141,'',0),(1901,53,29,0,22,18,36,10,1061216018,1,141,'',0),(1902,53,36,0,23,29,37,10,1061216018,1,141,'',0),(1903,53,37,0,24,36,38,10,1061216018,1,141,'',0),(1904,53,38,0,25,37,39,10,1061216018,1,141,'',0),(1905,53,39,0,26,38,40,10,1061216018,1,141,'',0),(1906,53,40,0,27,39,41,10,1061216018,1,141,'',0),(1907,53,41,0,28,40,33,10,1061216018,1,141,'',0),(1908,53,33,0,29,41,42,10,1061216018,1,141,'',0),(1909,53,42,0,30,33,43,10,1061216018,1,141,'',0),(1910,53,43,0,31,42,44,10,1061216018,1,141,'',0),(1911,53,44,0,32,43,45,10,1061216018,1,141,'',0),(1912,53,45,0,33,44,22,10,1061216018,1,141,'',0),(1913,53,22,0,34,45,46,10,1061216018,1,141,'',0),(1914,53,46,0,35,22,45,10,1061216018,1,141,'',0),(1915,53,45,0,36,46,22,10,1061216018,1,141,'',0),(1916,53,22,0,37,45,46,10,1061216018,1,141,'',0),(1917,53,46,0,38,22,47,10,1061216018,1,141,'',0),(1918,53,47,0,39,46,48,10,1061216018,1,141,'',0),(1919,53,48,0,40,47,49,10,1061216018,1,141,'',0),(1920,53,49,0,41,48,50,10,1061216018,1,141,'',0),(1921,53,50,0,42,49,24,10,1061216018,1,141,'',0),(1922,53,24,0,43,50,51,10,1061216018,1,141,'',0),(1923,53,51,0,44,24,52,10,1061216018,1,141,'',0),(1924,53,52,0,45,51,53,10,1061216018,1,141,'',0),(1925,53,53,0,46,52,54,10,1061216018,1,141,'',0),(1926,53,54,0,47,53,55,10,1061216018,1,141,'',0),(1927,53,55,0,48,54,56,10,1061216018,1,141,'',0),(1928,53,56,0,49,55,25,10,1061216018,1,141,'',0),(1929,53,25,0,50,56,57,10,1061216018,1,141,'',0),(1930,53,57,0,51,25,58,10,1061216018,1,141,'',0),(1931,53,58,0,52,57,59,10,1061216018,1,141,'',0),(1932,53,59,0,53,58,59,10,1061216018,1,141,'',0),(1933,53,59,0,54,59,44,10,1061216018,1,141,'',0),(1934,53,44,0,55,59,60,10,1061216018,1,141,'',0),(1935,53,60,0,56,44,38,10,1061216018,1,141,'',0),(1936,53,38,0,57,60,15,10,1061216018,1,141,'',0),(1937,53,15,0,58,38,61,10,1061216018,1,141,'',0),(1938,53,61,0,59,15,62,10,1061216018,1,141,'',0),(1939,53,62,0,60,61,63,10,1061216018,1,141,'',0),(1940,53,63,0,61,62,18,10,1061216018,1,141,'',0),(1941,53,18,0,62,63,64,10,1061216018,1,141,'',0),(1942,53,64,0,63,18,60,10,1061216018,1,141,'',0),(1943,53,60,0,64,64,65,10,1061216018,1,141,'',0),(1944,53,65,0,65,60,66,10,1061216018,1,141,'',0),(1945,53,66,0,66,65,67,10,1061216018,1,141,'',0),(1946,53,67,0,67,66,68,10,1061216018,1,141,'',0),(1947,53,68,0,68,67,69,10,1061216018,1,141,'',0),(1948,53,69,0,69,68,70,10,1061216018,1,141,'',0),(1949,53,70,0,70,69,71,10,1061216018,1,141,'',0),(1950,53,71,0,71,70,72,10,1061216018,1,141,'',0),(1951,53,72,0,72,71,73,10,1061216018,1,141,'',0),(1952,53,73,0,73,72,74,10,1061216018,1,141,'',0),(1953,53,74,0,74,73,62,10,1061216018,1,141,'',0),(1954,53,62,0,75,74,75,10,1061216018,1,141,'',0),(1955,53,75,0,76,62,35,10,1061216018,1,141,'',0),(1956,53,35,0,77,75,37,10,1061216018,1,141,'',0),(1957,53,37,0,78,35,76,10,1061216018,1,141,'',0),(1958,53,76,0,79,37,77,10,1061216018,1,141,'',0),(1959,53,77,0,80,76,78,10,1061216018,1,141,'',0),(1960,53,78,0,81,77,47,10,1061216018,1,141,'',0),(1961,53,47,0,82,78,78,10,1061216018,1,141,'',0),(1962,53,78,0,83,47,79,10,1061216018,1,141,'',0),(1963,53,79,0,84,78,80,10,1061216018,1,141,'',0),(1964,53,80,0,85,79,81,10,1061216018,1,141,'',0),(1965,53,81,0,86,80,82,10,1061216018,1,141,'',0),(1966,53,82,0,87,81,83,10,1061216018,1,141,'',0),(1967,53,83,0,88,82,31,10,1061216018,1,141,'',0),(1968,53,31,0,89,83,62,10,1061216018,1,141,'',0),(1969,53,62,0,90,31,84,10,1061216018,1,141,'',0),(1970,53,84,0,91,62,85,10,1061216018,1,141,'',0),(1971,53,85,0,92,84,52,10,1061216018,1,141,'',0),(1972,53,52,0,93,85,80,10,1061216018,1,141,'',0),(1973,53,80,0,94,52,56,10,1061216018,1,141,'',0),(1974,53,56,0,95,80,86,10,1061216018,1,141,'',0),(1975,53,86,0,96,56,87,10,1061216018,1,141,'',0),(1976,53,87,0,97,86,88,10,1061216018,1,141,'',0),(1977,53,88,0,98,87,89,10,1061216018,1,141,'',0),(1978,53,89,0,99,88,90,10,1061216018,1,141,'',0),(1979,53,90,0,100,89,91,10,1061216018,1,141,'',0),(1980,53,91,0,101,90,92,10,1061216018,1,141,'',0),(1981,53,92,0,102,91,93,10,1061216018,1,141,'',0),(1982,53,93,0,103,92,85,10,1061216018,1,141,'',0),(1983,53,85,0,104,93,31,10,1061216018,1,141,'',0),(1984,53,31,0,105,85,74,10,1061216018,1,141,'',0),(1985,53,74,0,106,31,94,10,1061216018,1,141,'',0),(1986,53,94,0,107,74,95,10,1061216018,1,141,'',0),(1987,53,95,0,108,94,31,10,1061216018,1,141,'',0),(1988,53,31,0,109,95,56,10,1061216018,1,141,'',0),(1989,53,56,0,110,31,26,10,1061216018,1,141,'',0),(1990,53,26,0,111,56,96,10,1061216018,1,141,'',0),(1991,53,96,0,112,26,29,10,1061216018,1,141,'',0),(1992,53,29,0,113,96,97,10,1061216018,1,141,'',0),(1993,53,97,0,114,29,98,10,1061216018,1,141,'',0),(1994,53,98,0,115,97,99,10,1061216018,1,141,'',0),(1995,53,99,0,116,98,29,10,1061216018,1,141,'',0),(1996,53,29,0,117,99,100,10,1061216018,1,141,'',0),(1997,53,100,0,118,29,101,10,1061216018,1,141,'',0),(1998,53,101,0,119,100,102,10,1061216018,1,141,'',0),(1999,53,102,0,120,101,31,10,1061216018,1,141,'',0),(2000,53,31,0,121,102,20,10,1061216018,1,141,'',0),(2001,53,20,0,122,31,21,10,1061216018,1,141,'',0),(2002,53,21,0,123,20,103,10,1061216018,1,141,'',0),(2003,53,103,0,124,21,104,10,1061216018,1,141,'',0),(2004,53,104,0,125,103,105,10,1061216018,1,141,'',0),(2005,53,105,0,126,104,53,10,1061216018,1,141,'',0),(2006,53,53,0,127,105,106,10,1061216018,1,141,'',0),(2007,53,106,0,128,53,89,10,1061216018,1,141,'',0),(2008,53,89,0,129,106,93,10,1061216018,1,141,'',0),(2009,53,93,0,130,89,107,10,1061216018,1,141,'',0),(2010,53,107,0,131,93,108,10,1061216018,1,141,'',0),(2011,53,108,0,132,107,38,10,1061216018,1,141,'',0),(2012,53,38,0,133,108,109,10,1061216018,1,141,'',0),(2013,53,109,0,134,38,78,10,1061216018,1,141,'',0),(2014,53,78,0,135,109,73,10,1061216018,1,141,'',0),(2015,53,73,0,136,78,86,10,1061216018,1,141,'',0),(2016,53,86,0,137,73,110,10,1061216018,1,141,'',0),(2017,53,110,0,138,86,93,10,1061216018,1,141,'',0),(2018,53,93,0,139,110,111,10,1061216018,1,141,'',0),(2019,53,111,0,140,93,65,10,1061216018,1,141,'',0),(2020,53,65,0,141,111,112,10,1061216018,1,141,'',0),(2021,53,112,0,142,65,98,10,1061216018,1,141,'',0),(2022,53,98,0,143,112,20,10,1061216018,1,141,'',0),(2023,53,20,0,144,98,21,10,1061216018,1,141,'',0),(2024,53,21,0,145,20,61,10,1061216018,1,141,'',0),(2025,53,61,0,146,21,108,10,1061216018,1,141,'',0),(2026,53,108,0,147,61,113,10,1061216018,1,141,'',0),(2027,53,113,0,148,108,27,10,1061216018,1,141,'',0),(2028,53,27,0,149,113,114,10,1061216018,1,141,'',0),(2029,53,114,0,150,27,115,10,1061216018,1,141,'',0),(2030,53,115,0,151,114,35,10,1061216018,1,141,'',0),(2031,53,35,0,152,115,114,10,1061216018,1,141,'',0),(2032,53,114,0,153,35,57,10,1061216018,1,141,'',0),(2033,53,57,0,154,114,116,10,1061216018,1,141,'',0),(2034,53,116,0,155,57,117,10,1061216018,1,141,'',0),(2035,53,117,0,156,116,118,10,1061216018,1,141,'',0),(2036,53,118,0,157,117,90,10,1061216018,1,141,'',0),(2037,53,90,0,158,118,63,10,1061216018,1,141,'',0),(2038,53,63,0,159,90,119,10,1061216018,1,141,'',0),(2039,53,119,0,160,63,62,10,1061216018,1,141,'',0),(2040,53,62,0,161,119,83,10,1061216018,1,141,'',0),(2041,53,83,0,162,62,68,10,1061216018,1,141,'',0),(2042,53,68,0,163,83,120,10,1061216018,1,141,'',0),(2043,53,120,0,164,68,121,10,1061216018,1,141,'',0),(2044,53,121,0,165,120,74,10,1061216018,1,141,'',0),(2045,53,74,0,166,121,74,10,1061216018,1,141,'',0),(2046,53,74,0,167,74,80,10,1061216018,1,141,'',0),(2047,53,80,0,168,74,63,10,1061216018,1,141,'',0),(2048,53,63,0,169,80,33,10,1061216018,1,141,'',0),(2049,53,33,0,170,63,30,10,1061216018,1,141,'',0),(2050,53,30,0,171,33,18,10,1061216018,1,141,'',0),(2051,53,18,0,172,30,78,10,1061216018,1,141,'',0),(2052,53,78,0,173,18,29,10,1061216018,1,141,'',0),(2053,53,29,0,174,78,110,10,1061216018,1,141,'',0),(2054,53,110,0,175,29,35,10,1061216018,1,141,'',0),(2055,53,35,0,176,110,122,10,1061216018,1,141,'',0),(2056,53,122,0,177,35,123,10,1061216018,1,141,'',0),(2057,53,123,0,178,122,18,10,1061216018,1,141,'',0),(2058,53,18,0,179,123,35,10,1061216018,1,141,'',0),(2059,53,35,0,180,18,124,10,1061216018,1,141,'',0),(2060,53,124,0,181,35,74,10,1061216018,1,141,'',0),(2061,53,74,0,182,124,60,10,1061216018,1,141,'',0),(2062,53,60,0,183,74,53,10,1061216018,1,141,'',0),(2063,53,53,0,184,60,25,10,1061216018,1,141,'',0),(2064,53,25,0,185,53,68,10,1061216018,1,141,'',0),(2065,53,68,0,186,25,66,10,1061216018,1,141,'',0),(2066,53,66,0,187,68,92,10,1061216018,1,141,'',0),(2067,53,92,0,188,66,106,10,1061216018,1,141,'',0),(2068,53,106,0,189,92,125,10,1061216018,1,141,'',0),(2069,53,125,0,190,106,35,10,1061216018,1,141,'',0),(2070,53,35,0,191,125,103,10,1061216018,1,141,'',0),(2071,53,103,0,192,35,83,10,1061216018,1,141,'',0),(2072,53,83,0,193,103,80,10,1061216018,1,141,'',0),(2073,53,80,0,194,83,35,10,1061216018,1,141,'',0),(2074,53,35,0,195,80,62,10,1061216018,1,141,'',0),(2075,53,62,0,196,35,93,10,1061216018,1,141,'',0),(2076,53,93,0,197,62,55,10,1061216018,1,141,'',0),(2077,53,55,0,198,93,15,10,1061216018,1,141,'',0),(2078,53,15,0,199,55,52,10,1061216018,1,141,'',0),(2079,53,52,0,200,15,45,10,1061216018,1,141,'',0),(2080,53,45,0,201,52,82,10,1061216018,1,141,'',0),(2081,53,82,0,202,45,126,10,1061216018,1,141,'',0),(2082,53,126,0,203,82,42,10,1061216018,1,141,'',0),(2083,53,42,0,204,126,73,10,1061216018,1,141,'',0),(2084,53,73,0,205,42,55,10,1061216018,1,141,'',0),(2085,53,55,0,206,73,82,10,1061216018,1,141,'',0),(2086,53,82,0,207,55,0,10,1061216018,1,141,'',0);
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
INSERT INTO ezsearch_word VALUES (5,'test',1),(6,'media',1),(7,'company',2),(8,'information',1),(9,'about',2),(10,'our',4),(11,'products',1),(12,'services',1),(13,'press',2),(14,'releases',1),(15,'a',5),(16,'release',1),(17,'lorem',5),(18,'ipsum',5),(19,'dolor',5),(20,'sit',5),(21,'amet',5),(22,'consectetuer',5),(23,'adipiscing',5),(24,'elit',5),(25,'maecenas',5),(26,'augue',5),(27,'leo',5),(28,'hendrerit',5),(29,'vitae',5),(30,'dapibus',5),(31,'ut',5),(32,'interdum',5),(33,'at',5),(34,'ligula',5),(35,'sed',5),(36,'sem',5),(37,'rhoncus',5),(38,'pulvinar',5),(39,'quisque',5),(40,'fringilla',5),(41,'nibh',5),(42,'odio',5),(43,'convallis',5),(44,'mollis',5),(45,'proin',5),(46,'lacus',5),(47,'nec',5),(48,'neque',5),(49,'vivamus',5),(50,'volutpat',5),(51,'id',5),(52,'purus',5),(53,'nulla',5),(54,'varius',5),(55,'dictum',5),(56,'est',5),(57,'sapien',5),(58,'pede',5),(59,'mattis',5),(60,'in',5),(61,'mi',5),(62,'vestibulum',5),(63,'ante',5),(64,'primis',5),(65,'faucibus',5),(66,'orci',5),(67,'luctus',5),(68,'et',5),(69,'ultrices',5),(70,'posuere',5),(71,'cubilia',5),(72,'curae',5),(73,'phasellus',5),(74,'arcu',5),(75,'justo',5),(76,'suspendisse',5),(77,'quis',5),(78,'turpis',5),(79,'pretium',5),(80,'scelerisque',5),(81,'fusce',5),(82,'dignissim',5),(83,'metus',5),(84,'rutrum',5),(85,'risus',5),(86,'eu',5),(87,'venenatis',5),(88,'velit',5),(89,'magna',5),(90,'ac',5),(91,'quam',5),(92,'morbi',5),(93,'non',5),(94,'eleifend',5),(95,'consequat',5),(96,'malesuada',5),(97,'porttitor',5),(98,'pellentesque',5),(99,'egestas',5),(100,'nunc',5),(101,'curabitur',5),(102,'feugiat',5),(103,'dui',5),(104,'etiam',5),(105,'fermentum',5),(106,'ornare',5),(107,'urna',5),(108,'cras',5),(109,'imperdiet',5),(110,'felis',5),(111,'diam',5),(112,'viverra',5),(113,'euismod',5),(114,'vel',5),(115,'libero',5),(116,'mauris',5),(117,'aliquam',5),(118,'enim',5),(119,'nam',5),(120,'blandit',5),(121,'vulputate',5),(122,'aliquet',5),(123,'tempus',5),(124,'facilisis',5),(125,'massa',5),(126,'semper',5),(127,'sodales',3),(128,'erat',3),(129,'iaculis',3),(130,'nullam',3),(131,'gravida',3),(132,'donec',3),(133,'tempor',3),(134,'elementum',3),(135,'nonummy',3),(136,'wisi',3),(137,'nisl',3),(138,'vehicula',3),(139,'facilisi',3),(140,'tincidunt',3),(141,'tortor',3),(142,'congue',3),(143,'cum',3),(144,'sociis',3),(145,'natoque',3),(146,'penatibus',3),(147,'magnis',3),(148,'dis',3),(149,'parturient',3),(150,'montes',3),(151,'nascetur',3),(152,'ridiculus',3),(153,'mus',3),(154,'sagittis',3),(155,'aenean',3),(156,'eros',3),(157,'cursus',3),(158,'lacinia',3),(159,'duis',3),(160,'condimentum',3),(161,'lectus',3),(162,'commodo',3),(163,'integer',3),(164,'accumsan',3),(165,'sollicitudin',3),(166,'ultricies',3),(167,'molestie',3),(168,'class',3),(169,'aptent',3),(170,'taciti',3),(171,'sociosqu',3),(172,'ad',3),(173,'litora',3),(174,'torquent',3),(175,'per',3),(176,'conubia',3),(177,'nostra',3),(178,'inceptos',3),(179,'hymenaeos',3),(180,'ullamcorper',3),(181,'praesent',3),(182,'pharetra',3),(183,'eget',3),(185,'my',1),(186,'software',2),(187,'hardware',2),(188,'thing',1),(189,'product',1),(190,'consulting',1);
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0),(21,'company','93c731f1c3a84ef05cd54d044c379eaa','content/view/full/44',1,0),(22,'products','86024cad1e83101d97359d7351051156','content/view/full/45',1,0),(23,'services','10cd395cf71c18328c863c08e78f3fd0','content/view/full/46',1,0),(24,'company/press_releases','e5800232891ecf58d893ae11f3a74e67','content/view/full/47',1,0),(25,'company/press_releases/a_press_release','6912f5a4fe4a4084eed697a63537338c','content/view/full/48',1,0),(26,'company/press_releases/about_my_company','60e63653f5c8fb907ae65bc5620484cc','content/view/full/49',1,27),(27,'company/about_my_company','44d707c8e884d291b26b178de533798c','content/view/full/49',1,0),(28,'products/software','248afd8e918aa9380622f515f11e25c1','content/view/full/50',1,0),(29,'products/hardware','b2337ecb28b6b10a33b246c2ca546b56','content/view/full/51',1,0),(30,'products/hardware/a_hardware_thing','87bba8492402d13f4cbd294286162cbf','content/view/full/52',1,0),(31,'products/software/our_software_product','9ba7da50e2955cbb1c108471c552f8d4','content/view/full/53',1,0),(32,'services/consulting','c760f8839c491854158c2bf58d3376f5','content/view/full/54',1,0);
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

