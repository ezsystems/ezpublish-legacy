-- MySQL dump 10.2
--
-- Host: localhost    Database: intranet
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
INSERT INTO ezcontentbrowserecent VALUES (35,111,99,1067006746,'foo bar corp'),(83,14,59,1069774043,'Staff news');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1069414895),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1066916721),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(6,0,'Forum','forum','<name>',14,14,1052384723,1052384870),(7,0,'Forum message','forum_message','<topic>',14,14,1052384877,1052384943),(8,0,'Product','product','<title>',14,14,1052384951,1052385067),(9,0,'Product review','product_review','<title>',14,14,1052385080,1052385252),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(11,0,'Link','link','<title>',14,14,1052385361,1052385453),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(13,0,'Comment','comment','<subject>',14,14,1052385685,1052385756),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069416328),(16,0,'Company','company','<company_name>',14,14,1066660266,1069419131),(17,0,'Person','person','<first_name> <last_name>',14,14,1066660986,1066999049),(18,0,'Event','event','<event_name>',14,14,1066661135,1066661324),(12,1,'File','file','<name>',14,14,1052385472,1067353799);
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',NULL,0,1),(128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',NULL,0,1),(125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',NULL,0,1),(132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(178,0,17,'picture','picture','ezimage',0,0,5,1,0,0,0,0,0,0,0,'','','','','',0,1),(179,0,17,'comment','Comment','ezxmltext',1,0,6,10,0,0,0,0,0,0,0,'','','','','',0,1),(182,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(167,0,16,'relation','Relation','ezselection',1,0,6,0,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezselection>\n  <options>\n    <option id=\"0\"\n            name=\"Partner\" />\n    <option id=\"2\"\n            name=\"Customer\" />\n    <option id=\"3\"\n            name=\"Supplier\" />\n  </options>\n</ezselection>',0,1),(168,0,16,'company_numbers','Company numbers','ezmatrix',1,0,7,2,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <column-name id=\"contact_type\"\n               idx=\"0\">Contact type</column-name>\n  <column-name id=\"contact_value\"\n               idx=\"1\">Contact value</column-name>\n</ezmatrix>',0,1),(176,0,18,'event_info','Event info','eztext',1,0,3,10,0,0,0,0,0,0,0,'','','','','',0,1),(175,0,18,'event_date','Event date','ezdate',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(173,0,18,'event_name','Event name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(170,0,17,'first_name','First name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(181,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(169,0,17,'last_name','Last name','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(171,0,17,'position','Position/job','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(172,0,17,'person_numbers','Person numbers','ezmatrix',1,0,4,0,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <column-name id=\"contact_type\"\n               idx=\"0\">Contact type</column-name>\n  <column-name id=\"contact_value\"\n               idx=\"1\">Contact value</column-name>\n</ezmatrix>',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(165,0,16,'logo','Logo','ezimage',0,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(166,0,16,'additional_information','Additional information','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(162,0,16,'company_name','Company name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(163,0,16,'company_number','Company number','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(164,0,16,'company_address','Company address','ezmatrix',1,0,3,3,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <column-name id=\"address_type\"\n               idx=\"0\">Address type</column-name>\n  <column-name id=\"address_value\"\n               idx=\"1\">Address value</column-name>\n</ezmatrix>',0,1);
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
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(18,0,1,'Content');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Intranet',2,0,1033917596,1069416323,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',8,0,1066384365,1069162841,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',10,0,1066388816,1069164888,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,4,1,'News',1,0,1066398020,1066398020,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(136,14,11,15,'Intranet',16,0,1069164104,1069841972,1,''),(57,14,5,1,'Contact',1,0,1066729137,1066729137,1,''),(59,14,4,1,'Off topic',1,0,1066729211,1066729211,1,''),(60,14,4,1,'Reports',2,0,1066729226,1066729241,1,''),(61,14,4,1,'Staff news',1,0,1066729258,1066729258,1,''),(62,14,5,1,'Persons',1,0,1066729284,1066729284,1,''),(63,14,5,1,'Companies',1,0,1066729298,1066729298,1,''),(64,14,6,1,'Files',3,0,1066729319,1066898100,1,''),(66,14,6,1,'Handbooks',1,0,1066729356,1066729356,1,''),(67,14,6,1,'Documents',1,0,1066729371,1066729371,1,''),(68,14,6,1,'Company routines',1,0,1066729385,1066729385,1,''),(69,14,6,1,'Logos',1,0,1066729400,1066729400,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(173,14,5,16,'My Company',1,0,1069770749,1069770749,1,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(176,14,4,2,'New employee',1,0,1069774043,1069774043,1,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(87,14,5,16,'New Company',1,0,0,0,0,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(170,14,1,10,'Vacation routines',1,0,1069769718,1069769718,1,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(171,14,5,17,'John Doe',1,0,1069769945,1069769945,1,''),(172,14,5,17,'Per Son',1,0,1069770002,1069770002,1,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(112,14,1,1,'Information',2,0,1066986270,1069769638,1,''),(113,14,1,10,'Routines',1,0,1066986541,1066986541,1,''),(115,14,11,14,'Cache',3,0,1066991725,1069162746,1,''),(116,14,11,14,'URL translator',2,0,1066992054,1069162892,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(133,14,11,15,'New Template look',1,0,0,0,0,''),(134,14,11,15,'New Template look',1,0,0,0,0,''),(143,14,4,2,'New Article',1,0,0,0,0,''),(175,14,4,2,'Annual report',1,0,1069773968,1069773968,1,''),(174,14,4,2,'New business cards',2,0,1069773925,1069774003,1,''),(165,14,4,13,'jkhkjh',1,0,1069423490,1069423490,1,''),(166,14,4,13,'kljjkl',1,0,1069423957,1069423957,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'Root folder',NULL,NULL,0,0,'','ezstring'),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(467,'eng-GB',16,136,181,'ez.no',0,0,0,0,'','ezinisetting'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',1,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',1,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',1,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',1,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(155,'eng-GB',1,57,4,'Contact',0,0,0,0,'contact','ezstring'),(156,'eng-GB',1,57,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(159,'eng-GB',1,59,4,'Off topic',0,0,0,0,'off topic','ezstring'),(160,'eng-GB',1,59,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(161,'eng-GB',1,60,4,'Reports ',0,0,0,0,'reports','ezstring'),(162,'eng-GB',1,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(161,'eng-GB',2,60,4,'Reports',0,0,0,0,'reports','ezstring'),(162,'eng-GB',2,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(163,'eng-GB',1,61,4,'Staff news',0,0,0,0,'staff news','ezstring'),(164,'eng-GB',1,61,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(165,'eng-GB',1,62,4,'Persons',0,0,0,0,'persons','ezstring'),(166,'eng-GB',1,62,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(167,'eng-GB',1,63,4,'Companies',0,0,0,0,'companies','ezstring'),(168,'eng-GB',1,63,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(169,'eng-GB',1,64,4,'Files',0,0,0,0,'files','ezstring'),(170,'eng-GB',1,64,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(173,'eng-GB',1,66,4,'Handbooks',0,0,0,0,'handbooks','ezstring'),(174,'eng-GB',1,66,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(175,'eng-GB',1,67,4,'Documents',0,0,0,0,'documents','ezstring'),(176,'eng-GB',1,67,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(177,'eng-GB',1,68,4,'Company routines',0,0,0,0,'company routines','ezstring'),(178,'eng-GB',1,68,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(179,'eng-GB',1,69,4,'Logos',0,0,0,0,'logos','ezstring'),(180,'eng-GB',1,69,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(169,'eng-GB',2,64,4,'Files',0,0,0,0,'files','ezstring'),(170,'eng-GB',2,64,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(463,'eng-GB',14,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB/intranet.gif\"\n         original_filename=\"intranet.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069685477\">\n  <original attribute_id=\"463\"\n            attribute_version=\"13\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069780222\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',13,136,160,'intranet_red',0,0,0,0,'intranet_red','ezpackage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/136',0,0,0,0,'content/edit/136','ezstring'),(653,'eng-GB',2,174,1,'New business cards',0,0,0,0,'new business cards','ezstring'),(654,'eng-GB',2,174,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>The new business cards just arrived.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(658,'eng-GB',1,175,1,'Annual report',0,0,0,0,'annual report','ezstring'),(659,'eng-GB',1,175,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We&apos;ve just released our annual report.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(660,'eng-GB',1,175,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(661,'eng-GB',1,175,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"annual_report.\"\n         suffix=\"\"\n         basename=\"annual_report\"\n         dirpath=\"var/intranet/storage/images/news/reports/annual_report/661-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/reports/annual_report/661-1-eng-GB/annual_report.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069773935\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(662,'eng-GB',1,175,123,'',0,0,0,0,'','ezboolean'),(169,'eng-GB',3,64,4,'Files',0,0,0,0,'files','ezstring'),(170,'eng-GB',3,64,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you can download all files ...</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(317,'eng-GB',2,112,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>General information about our company.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(316,'eng-GB',2,112,4,'Information',0,0,0,0,'information','ezstring'),(466,'eng-GB',16,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(465,'eng-GB',16,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(464,'eng-GB',16,136,160,'intranet_red',0,0,0,0,'intranet_red','ezpackage'),(656,'eng-GB',2,174,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_business_cards.\"\n         suffix=\"\"\n         basename=\"new_business_cards\"\n         dirpath=\"var/intranet/storage/images/news/off_topic/new_business_cards/656-2-eng-GB\"\n         url=\"var/intranet/storage/images/news/off_topic/new_business_cards/656-2-eng-GB/new_business_cards.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069773844\">\n  <original attribute_id=\"656\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(657,'eng-GB',2,174,123,'',0,0,0,0,'','ezboolean'),(664,'eng-GB',1,176,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We&apos;ve just got a new member of our team.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(665,'eng-GB',1,176,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(666,'eng-GB',1,176,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_employee.\"\n         suffix=\"\"\n         basename=\"new_employee\"\n         dirpath=\"var/intranet/storage/images/news/staff_news/new_employee/666-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/staff_news/new_employee/666-1-eng-GB/new_employee.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069774020\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(667,'eng-GB',1,176,123,'',0,0,0,0,'','ezboolean'),(316,'eng-GB',1,112,4,'Information',0,0,0,0,'information','ezstring'),(317,'eng-GB',1,112,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>General information about the company.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(318,'eng-GB',1,113,140,'Routines',0,0,0,0,'routines','ezstring'),(319,'eng-GB',1,113,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Cras egestas nisl non turpis. Cras sed leo ut dui iaculis pharetra. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Donec felis nulla, aliquet et, aliquam sed, ultricies et, urna. Vivamus risus. Fusce pede. Sed ornare, lectus sed aliquam auctor, erat purus elementum magna, vel luctus augue turpis quis massa. Nullam egestas diam at mi. </paragraph>\n  <paragraph>Vestibulum viverra tristique velit. Vivamus vitae quam. Mauris nibh. Phasellus nec metus. Integer tristique magna eu sem. Praesent rutrum ullamcorper ligula. Fusce et est. Integer at orci. Aliquam lectus ligula, commodo a, rhoncus et, semper eget, magna. Sed vel augue. Pellentesque at est ac massa gravida vehicula. Suspendisse potenti. Aenean ut diam. Nulla purus quam, sodales id, adipiscing eu, dignissim quis, libero. In eu erat. </paragraph>\n  <paragraph>Maecenas vel lorem a nisl auctor semper. Vivamus arcu elit, ultricies in, congue at, commodo at, nulla. Aliquam in nibh. Etiam sapien lectus, mollis vitae, malesuada vel, fermentum vitae, massa. In hac habitasse platea dictumst. Phasellus quis neque ut orci auctor posuere. Donec ac nisl vel nunc porttitor venenatis. Morbi eget enim. Phasellus commodo, neque at sagittis scelerisque, erat nulla elementum orci, vitae consectetuer risus magna sit amet lectus. Curabitur laoreet wisi sed neque. Vestibulum lobortis magna nec nisl. Praesent non enim. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(320,'eng-GB',1,113,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"routines.\"\n         suffix=\"\"\n         basename=\"routines\"\n         dirpath=\"var/intranet/storage/images/information/routines/320-1-eng-GB\"\n         url=\"var/intranet/storage/images/information/routines/320-1-eng-GB/routines.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(462,'eng-GB',11,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',11,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet.gif\"\n         original_filename=\"intranet.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069685477\">\n  <original attribute_id=\"463\"\n            attribute_version=\"10\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069780121\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(663,'eng-GB',1,176,1,'New employee',0,0,0,0,'new employee','ezstring'),(655,'eng-GB',2,174,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</paragraph>\n  <paragraph>\n    <line>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(464,'eng-GB',8,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',8,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',8,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',8,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',14,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(461,'eng-GB',16,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(528,'eng-GB',6,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(529,'eng-GB',7,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(530,'eng-GB',8,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(461,'eng-GB',9,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',9,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(646,'eng-GB',1,173,162,'My Company',0,0,0,0,'my company','ezstring'),(647,'eng-GB',1,173,163,'C100',0,0,0,0,'c100','ezstring'),(648,'eng-GB',1,173,164,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"address_type\">Address type</column>\n    <column num=\"1\"\n            id=\"address_value\">Address value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>Mystreet 2</c>\n  <c>1</c>\n  <c>Mystreet 2</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(649,'eng-GB',1,173,165,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"my_company.\"\n         suffix=\"\"\n         basename=\"my_company\"\n         dirpath=\"var/intranet/storage/images/contact/companies/my_company/649-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/companies/my_company/649-1-eng-GB/my_company.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069770020\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(650,'eng-GB',1,173,166,'A small company we work with.',0,0,0,0,'','eztext'),(651,'eng-GB',1,173,167,'0',0,0,0,0,'0','ezselection'),(652,'eng-GB',1,173,168,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>555 2345</c>\n  <c>1</c>\n  <c>555 2344</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(653,'eng-GB',1,174,1,'New business cards',0,0,0,0,'new business cards','ezstring'),(654,'eng-GB',1,174,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The new business cards just arrived.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(655,'eng-GB',1,174,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</paragraph>\n  <paragraph>\n    <line>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(656,'eng-GB',1,174,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_business_cards.\"\n         suffix=\"\"\n         basename=\"new_business_cards\"\n         dirpath=\"var/intranet/storage/images/news/off_topic/new_business_cards/656-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/off_topic/new_business_cards/656-1-eng-GB/new_business_cards.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069773844\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(657,'eng-GB',1,174,123,'',0,0,0,0,'','ezboolean'),(634,'eng-GB',1,171,170,'John',0,0,0,0,'john','ezstring'),(635,'eng-GB',1,171,169,'Doe',0,0,0,0,'doe','ezstring'),(636,'eng-GB',1,171,171,'Developer',0,0,0,0,'developer','ezstring'),(637,'eng-GB',1,171,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"3\" />\n  <c>0</c>\n  <c>555 1234</c>\n  <c>2</c>\n  <c>doe@example.com</c>\n  <c>3</c>\n  <c>http://www.example.com</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(638,'eng-GB',1,171,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"john_doe.\"\n         suffix=\"\"\n         basename=\"john_doe\"\n         dirpath=\"var/intranet/storage/images/contact/persons/john_doe/638-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/john_doe/638-1-eng-GB/john_doe.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069769896\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(639,'eng-GB',1,171,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A nice guy.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(640,'eng-GB',1,172,170,'Per',0,0,0,0,'per','ezstring'),(641,'eng-GB',1,172,169,'Son',0,0,0,0,'son','ezstring'),(642,'eng-GB',1,172,171,'Administrator',0,0,0,0,'administrator','ezstring'),(643,'eng-GB',1,172,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>555 1236</c>\n  <c>2</c>\n  <c>per.son@example.com</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(644,'eng-GB',1,172,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"per_son.\"\n         suffix=\"\"\n         basename=\"per_son\"\n         dirpath=\"var/intranet/storage/images/contact/persons/per_son__1/644-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/per_son__1/644-1-eng-GB/per_son.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069769956\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(645,'eng-GB',1,172,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Per Son is a very active person.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(467,'eng-GB',15,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',15,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(530,'eng-GB',16,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(465,'eng-GB',13,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',13,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',13,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',13,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(464,'eng-GB',15,136,160,'intranet_red',0,0,0,0,'intranet_red','ezpackage'),(465,'eng-GB',15,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(467,'eng-GB',14,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',14,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(464,'eng-GB',14,136,160,'intranet_red',0,0,0,0,'intranet_red','ezpackage'),(465,'eng-GB',14,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',14,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(463,'eng-GB',9,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069168827\">\n  <original attribute_id=\"463\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069168829\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069168829\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069680740\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(461,'eng-GB',11,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(466,'eng-GB',15,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',13,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',13,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',13,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB/intranet.gif\"\n         original_filename=\"intranet.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069685477\">\n  <original attribute_id=\"463\"\n            attribute_version=\"11\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(462,'eng-GB',14,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',16,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB/intranet.gif\"\n         original_filename=\"intranet.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069838485\">\n  <original attribute_id=\"463\"\n            attribute_version=\"15\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069838488\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069838488\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069843872\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(462,'eng-GB',16,136,158,'author=eZ systems package team\ncopyright=eZ systems as\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(461,'eng-GB',6,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',6,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',6,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.png\"\n         suffix=\"png\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB/intranet.png\"\n         original_filename=\"phphWMyJs.png\"\n         mime_type=\"original\"\n         width=\"144\"\n         height=\"134\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069167783\">\n  <original attribute_id=\"463\"\n            attribute_version=\"5\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB/intranet_reference.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB/intranet_medium.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',6,136,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(465,'eng-GB',6,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',6,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',6,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',7,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',7,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',7,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.png\"\n         suffix=\"png\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB/intranet.png\"\n         original_filename=\"phphWMyJs.png\"\n         mime_type=\"original\"\n         width=\"144\"\n         height=\"134\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069167783\">\n  <original attribute_id=\"463\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB/intranet_reference.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB/intranet_medium.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB/intranet_logo.png\"\n         mime_type=\"image/png\"\n         width=\"62\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069168662\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',7,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',7,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',7,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',7,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069162834\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069162835\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069162835\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069162901\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"url_translator.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069162886\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069162886\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069162886\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069162901\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(461,'eng-GB',8,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',8,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',8,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB/intranet.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069168827\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069168829\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069168829\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069168850\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1,'eng-GB',2,1,4,'Intranet',0,0,0,0,'intranet','ezstring'),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our intranet</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(464,'eng-GB',9,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',9,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',9,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',9,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',9,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(631,'eng-GB',1,170,140,'Vacation routines',0,0,0,0,'vacation routines','ezstring'),(632,'eng-GB',1,170,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Vestibulum viverra tristique velit. Vivamus vitae quam. Mauris nibh. Phasellus nec metus. Integer tristique magna eu sem. Praesent rutrum ullamcorper ligula. Fusce et est. Integer at orci. Aliquam lectus ligula, commodo a, rhoncus et, semper eget, magna. Sed vel augue. Pellentesque at est ac massa gravida vehicula. Suspendisse potenti. Aenean ut diam. Nulla purus quam, sodales id, adipiscing eu, dignissim quis, libero. In eu erat.</paragraph>\n  <paragraph>Vestibulum viverra tristique velit. Vivamus vitae quam. Mauris nibh. Phasellus nec metus. Integer tristique magna eu sem. Praesent rutrum ullamcorper ligula. Fusce et est. Integer at orci. Aliquam lectus ligula, commodo a, rhoncus et, semper eget, magna. Sed vel augue. Pellentesque at est ac massa gravida vehicula. Suspendisse potenti. Aenean ut diam. Nulla purus quam, sodales id, adipiscing eu, dignissim quis, libero. In eu erat.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(633,'eng-GB',1,170,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"vacation_routines.\"\n         suffix=\"\"\n         basename=\"vacation_routines\"\n         dirpath=\"var/intranet/storage/images/information/vacation_routines/633-1-eng-GB\"\n         url=\"var/intranet/storage/images/information/vacation_routines/633-1-eng-GB/vacation_routines.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069769679\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(616,'eng-GB',1,165,151,'jkhjkh',0,0,0,0,'','eztext'),(615,'eng-GB',1,165,150,'kjhjkh',0,0,0,0,'kjhjkh','ezstring'),(614,'eng-GB',1,165,149,'jkhkjh',0,0,0,0,'jkhkjh','ezstring'),(617,'eng-GB',1,166,149,'kljjkl',0,0,0,0,'kljjkl','ezstring'),(618,'eng-GB',1,166,150,'lkjlkj',0,0,0,0,'lkjlkj','ezstring'),(619,'eng-GB',1,166,151,'lkjlkj',0,0,0,0,'','eztext'),(461,'eng-GB',10,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',10,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',10,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet.gif\"\n         original_filename=\"intranet.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069685477\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069685498\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',10,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',10,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',10,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',10,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',10,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(461,'eng-GB',15,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',15,136,158,'author=eZ systems package team\ncopyright=Copyright &copy; 1999-2003 eZ systems as\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',15,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB/intranet.gif\"\n         original_filename=\"intranet.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069838485\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069838488\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069838488\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069838505\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',11,136,160,'intranet_gray',0,0,0,0,'intranet_gray','ezpackage'),(465,'eng-GB',11,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',11,136,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',11,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',11,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring');
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(171,'John Doe',1,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(143,'fp',1,'eng-GB','eng-GB'),(57,'Contact',1,'eng-GB','eng-GB'),(59,'Off topic',1,'eng-GB','eng-GB'),(60,'Reports',1,'eng-GB','eng-GB'),(60,'Reports',2,'eng-GB','eng-GB'),(61,'Staff news',1,'eng-GB','eng-GB'),(62,'Persons',1,'eng-GB','eng-GB'),(63,'Companies',1,'eng-GB','eng-GB'),(64,'Files',1,'eng-GB','eng-GB'),(66,'Handbooks',1,'eng-GB','eng-GB'),(67,'Documents',1,'eng-GB','eng-GB'),(68,'Company routines',1,'eng-GB','eng-GB'),(69,'Logos',1,'eng-GB','eng-GB'),(64,'Files',2,'eng-GB','eng-GB'),(1,'Intranet',2,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(172,'Per Son',1,'eng-GB','eng-GB'),(173,'My Company',1,'eng-GB','eng-GB'),(174,'New business cards',1,'eng-GB','eng-GB'),(175,'Annual report',1,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(136,'Intranet',7,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(87,'New Company',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(136,'Intranet',6,'eng-GB','eng-GB'),(136,'Intranet',5,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(136,'Intranet',4,'eng-GB','eng-GB'),(113,'Routines',2,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(64,'Files',3,'eng-GB','eng-GB'),(136,'Intranet',3,'eng-GB','eng-GB'),(112,'Information',2,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(112,'Information',1,'eng-GB','eng-GB'),(136,'Intranet',16,'eng-GB','eng-GB'),(113,'Routines',1,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(136,'Intranet',2,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(136,'Intranet',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(136,'Intranet',1,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(136,'Intranet',8,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(133,'New Template look',1,'eng-GB','eng-GB'),(134,'News',1,'eng-GB','eng-GB'),(170,'Vacation routines',1,'eng-GB','eng-GB'),(174,'New business cards',2,'eng-GB','eng-GB'),(176,'New employee',1,'eng-GB','eng-GB'),(136,'Intranet',13,'eng-GB','eng-GB'),(136,'Intranet',14,'eng-GB','eng-GB'),(136,'Intranet',15,'eng-GB','eng-GB'),(165,'jkhkjh',1,'eng-GB','eng-GB'),(166,'kljjkl',1,'eng-GB','eng-GB'),(136,'Intranet',10,'eng-GB','eng-GB'),(136,'Intranet',11,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,2,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,8,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,10,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,1,1,2,'/1/2/50/',9,1,0,'news',50),(55,2,57,1,1,2,'/1/2/55/',9,1,0,'contact',55),(57,50,59,1,1,3,'/1/2/50/57/',9,1,0,'news/off_topic',57),(58,50,60,2,1,3,'/1/2/50/58/',9,1,0,'news/reports',58),(59,50,61,1,1,3,'/1/2/50/59/',9,1,0,'news/staff_news',59),(60,55,62,1,1,3,'/1/2/55/60/',9,1,0,'contact/persons',60),(61,55,63,1,1,3,'/1/2/55/61/',9,1,0,'contact/companies',61),(62,2,64,3,1,2,'/1/2/62/',8,1,0,'files',62),(64,62,66,1,1,3,'/1/2/62/64/',9,1,2,'files/handbooks',64),(65,62,67,1,1,3,'/1/2/62/65/',9,1,3,'files/documents',65),(66,62,68,1,1,3,'/1/2/62/66/',9,1,4,'files/company_routines',66),(67,62,69,1,1,3,'/1/2/62/67/',9,1,5,'files/logos',67),(150,61,173,1,1,4,'/1/2/55/61/150/',1,1,0,'contact/companies/my_company',150),(151,58,174,2,1,4,'/1/2/50/58/151/',1,1,0,'news/reports/new_business_cards',151),(152,57,174,2,1,4,'/1/2/50/57/152/',1,1,0,'news/off_topic/new_business_cards',151),(153,58,175,1,1,4,'/1/2/50/58/153/',1,1,0,'news/reports/annual_report',153),(154,59,176,1,1,4,'/1/2/50/59/154/',1,1,0,'news/staff_news/new_employee',154),(147,93,170,1,1,3,'/1/2/93/147/',9,1,0,'information/vacation_routines',147),(118,60,148,2,1,4,'/1/2/55/60/118/',1,1,0,'contact/persons/jh_jh',118),(148,60,171,1,1,4,'/1/2/55/60/148/',1,1,0,'contact/persons/john_doe',148),(149,60,172,1,1,4,'/1/2/55/60/149/',1,1,0,'contact/persons/per_son__1',149),(93,2,112,2,1,2,'/1/2/93/',9,1,0,'information',93),(94,93,113,1,1,3,'/1/2/93/94/',9,1,0,'information/routines',94),(95,46,115,3,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,2,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(107,48,136,16,1,3,'/1/44/48/107/',9,1,0,'setup/look_and_feel/intranet',107),(114,59,146,1,1,4,'/1/2/50/59/114/',1,1,0,'news/staff_news/mnb',114),(116,58,147,1,1,4,'/1/2/50/58/116/',1,1,0,'news/reports/fdhjkldfhj',116),(120,57,149,1,1,4,'/1/2/50/57/120/',1,1,0,'news/off_topic/dfsdfg',120),(122,59,150,1,1,4,'/1/2/50/59/122/',1,1,0,'news/staff_news/sdifgksdjfgkjgh',122),(124,58,151,1,1,4,'/1/2/50/58/124/',1,1,0,'news/reports/kre_test',124),(132,58,157,1,1,4,'/1/2/50/58/132/',1,1,0,'news/reports/utuytuy',132),(138,60,161,1,1,4,'/1/2/55/60/138/',1,1,0,'contact/persons/per_son',138),(141,59,163,1,1,4,'/1/2/50/59/141/',1,1,0,'news/staff_news/lkj',141),(143,141,165,1,1,5,'/1/2/50/59/141/143/',1,1,0,'news/staff_news/lkj/jkhkjh',143),(144,141,166,1,1,5,'/1/2/50/59/141/144/',1,1,0,'news/staff_news/lkj/kljjkl',144),(145,58,168,1,1,4,'/1/2/50/58/145/',1,1,0,'news/reports/jkljlkj',145);
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
INSERT INTO ezcontentobject_version VALUES (1,1,14,1,1033919080,1033919080,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(480,45,14,1,1066388718,1066388815,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(741,174,14,1,1069773840,1069773925,3,0,0),(688,136,14,6,1069168386,1069168404,3,0,0),(490,49,14,1,1066398007,1066398020,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(748,136,14,16,1069841925,1069841972,1,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(519,57,14,1,1066729088,1066729137,1,0,0),(521,59,14,1,1066729202,1066729210,1,0,0),(522,60,14,1,1066729218,1066729226,3,0,0),(523,60,14,2,1066729234,1066729241,1,0,0),(524,61,14,1,1066729249,1066729258,1,0,0),(525,62,14,1,1066729275,1066729284,1,0,0),(526,63,14,1,1066729291,1066729298,1,0,0),(527,64,14,1,1066729311,1066729319,3,0,0),(529,66,14,1,1066729349,1066729356,1,0,0),(530,67,14,1,1066729363,1066729371,1,0,0),(531,68,14,1,1066729377,1066729385,1,0,0),(532,69,14,1,1066729392,1066729400,1,0,0),(533,64,14,2,1066729407,1066729416,3,0,0),(746,136,14,14,1069780198,1069780210,3,0,0),(708,1,14,2,1069416307,1069416322,1,1,0),(745,136,14,13,1069780167,1069780184,3,0,0),(689,136,14,7,1069168565,1069168581,3,0,0),(747,136,14,15,1069838320,1069838485,3,0,0),(739,172,14,1,1069769954,1069770002,1,0,0),(742,175,14,1,1069773933,1069773968,1,0,0),(738,171,14,1,1069769892,1069769945,1,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(683,45,14,10,1069164834,1069164888,1,0,0),(733,136,14,11,1069687139,1069687155,3,0,0),(744,176,14,1,1069774018,1069774043,1,0,0),(743,174,14,2,1069773990,1069774003,1,0,0),(737,170,14,1,1069769677,1069769717,1,0,0),(690,136,14,8,1069168808,1069168827,3,0,0),(581,64,14,3,1066898069,1066898100,1,0,0),(707,136,14,9,1069416345,1069416376,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(735,112,14,2,1069769622,1069769638,1,0,0),(605,112,14,1,1066986251,1066986270,3,0,0),(606,113,14,1,1066986461,1066986541,1,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(672,115,14,3,1069162736,1069162746,1,0,0),(673,43,14,8,1069162754,1069162840,1,0,0),(674,45,14,9,1069162851,1069162858,3,0,0),(675,116,14,2,1069162867,1069162891,1,0,0),(740,173,14,1,1069770018,1069770749,1,0,0),(727,165,14,1,1069423480,1069423490,1,0,0),(728,166,14,1,1069423948,1069423957,1,0,0),(732,136,14,10,1069685452,1069685477,3,0,0);
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
INSERT INTO ezimagefile VALUES (50,661,'var/intranet/storage/images/news/reports/annual_report/661-1-eng-GB/annual_report.'),(51,656,'var/intranet/storage/images/news/off_topic/new_business_cards/656-2-eng-GB/new_business_cards.'),(19,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet.gif'),(63,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB/intranet_reference.gif'),(62,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB/intranet.gif'),(56,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB/intranet_medium.gif'),(54,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB/intranet.gif'),(55,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-13-eng-GB/intranet_reference.gif'),(57,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB/intranet.gif'),(58,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB/intranet_reference.gif'),(59,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB/intranet_medium.gif'),(60,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-14-eng-GB/intranet_logo.gif'),(20,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_reference.gif'),(21,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_medium.gif'),(53,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_logo.gif'),(52,666,'var/intranet/storage/images/news/staff_news/new_employee/666-1-eng-GB/new_employee.'),(45,633,'var/intranet/storage/images/information/vacation_routines/633-1-eng-GB/vacation_routines.'),(46,638,'var/intranet/storage/images/contact/persons/john_doe/638-1-eng-GB/john_doe.'),(47,644,'var/intranet/storage/images/contact/persons/per_son__1/644-1-eng-GB/per_son.'),(64,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB/intranet_medium.gif'),(65,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-15-eng-GB/intranet_logo.gif'),(48,649,'var/intranet/storage/images/contact/companies/my_company/649-1-eng-GB/my_company.'),(49,656,'var/intranet/storage/images/news/off_topic/new_business_cards/656-1-eng-GB/new_business_cards.'),(36,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_logo.gif'),(38,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet.gif'),(39,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_reference.gif'),(40,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_medium.gif'),(41,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_logo.gif'),(42,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet.gif'),(43,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_reference.gif'),(44,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_medium.gif'),(66,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB/intranet.gif'),(67,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB/intranet_reference.gif'),(68,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB/intranet_medium.gif'),(69,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-16-eng-GB/intranet_logo.gif');
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
INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(191,45,1,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(483,136,16,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(481,136,14,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(480,136,13,48,9,1,1,0,0),(476,174,2,57,1,1,0,0,0),(416,136,9,48,9,1,1,0,0),(229,57,1,2,9,1,1,0,0),(231,59,1,50,9,1,1,0,0),(232,60,1,50,9,1,1,0,0),(233,60,2,50,9,1,1,0,0),(234,61,1,50,9,1,1,0,0),(235,62,1,55,9,1,1,0,0),(236,63,1,55,9,1,1,0,0),(237,64,1,2,9,1,1,0,0),(239,66,1,62,9,1,1,0,0),(240,67,1,62,9,1,1,0,0),(241,68,1,62,9,1,1,0,0),(242,69,1,62,9,1,1,0,0),(243,64,2,2,8,1,1,0,0),(467,171,1,60,1,1,1,0,1),(482,136,15,48,9,1,1,0,0),(391,136,6,48,9,1,1,0,0),(386,45,10,46,9,1,1,0,0),(470,173,1,61,1,1,1,0,0),(471,173,1,61,1,1,1,0,1),(472,174,1,57,1,1,0,0,0),(321,45,6,46,9,1,1,0,0),(461,136,11,48,9,1,1,0,0),(473,174,1,58,1,1,1,0,1),(466,171,1,60,1,1,1,0,0),(463,112,2,2,9,1,1,0,0),(393,136,8,48,9,1,1,0,0),(286,64,3,2,8,1,1,0,0),(475,175,1,58,1,1,1,0,1),(417,1,2,1,9,1,1,0,0),(477,174,2,58,1,1,1,0,1),(335,45,8,46,9,1,1,0,0),(392,136,7,48,9,1,1,0,0),(465,170,1,93,9,1,1,0,0),(474,175,1,58,1,1,1,0,0),(479,176,1,59,1,1,1,0,1),(307,112,1,2,9,1,1,0,0),(478,176,1,59,1,1,1,0,0),(308,113,1,93,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(375,115,3,46,9,1,1,0,0),(376,43,8,46,9,1,1,0,0),(377,45,9,46,9,1,1,0,0),(378,116,2,46,9,1,1,0,0),(469,172,1,60,1,1,1,0,1),(468,172,1,60,1,1,1,0,0),(452,165,1,141,1,1,1,0,0),(453,166,1,141,1,1,1,0,0),(460,136,10,48,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',41,1,0,0,'','','',''),(2,0,'ezpublish',42,1,0,0,'','','',''),(3,0,'ezpublish',43,1,0,0,'','','',''),(4,0,'ezpublish',44,1,0,0,'','','',''),(5,0,'ezpublish',43,2,0,0,'','','',''),(6,0,'ezpublish',43,3,0,0,'','','',''),(7,0,'ezpublish',43,4,0,0,'','','',''),(8,0,'ezpublish',43,5,0,0,'','','',''),(9,0,'ezpublish',45,1,0,0,'','','',''),(10,0,'ezpublish',46,1,0,0,'','','',''),(11,0,'ezpublish',46,2,0,0,'','','',''),(12,0,'ezpublish',48,1,0,0,'','','',''),(13,0,'ezpublish',48,4,0,0,'','','',''),(14,0,'ezpublish',48,5,0,0,'','','',''),(15,0,'ezpublish',49,1,0,0,'','','',''),(16,0,'ezpublish',50,1,0,0,'','','',''),(17,0,'ezpublish',50,2,0,0,'','','',''),(18,0,'ezpublish',48,6,0,0,'','','',''),(19,0,'ezpublish',48,7,0,0,'','','',''),(20,0,'ezpublish',48,8,0,0,'','','',''),(21,0,'ezpublish',48,9,0,0,'','','',''),(22,0,'ezpublish',48,11,0,0,'','','',''),(23,0,'ezpublish',55,1,0,0,'','','',''),(24,0,'ezpublish',54,1,0,0,'','','',''),(25,0,'ezpublish',56,1,0,0,'','','',''),(26,0,'ezpublish',56,2,0,0,'','','',''),(27,0,'ezpublish',56,3,0,0,'','','',''),(28,0,'ezpublish',56,4,0,0,'','','',''),(29,0,'ezpublish',56,5,0,0,'','','',''),(30,0,'ezpublish',56,6,0,0,'','','',''),(31,0,'ezpublish',57,1,0,0,'','','',''),(32,0,'ezpublish',58,1,0,0,'','','',''),(33,0,'ezpublish',59,1,0,0,'','','',''),(34,0,'ezpublish',60,1,0,0,'','','',''),(35,0,'ezpublish',60,2,0,0,'','','',''),(36,0,'ezpublish',61,1,0,0,'','','',''),(37,0,'ezpublish',62,1,0,0,'','','',''),(38,0,'ezpublish',63,1,0,0,'','','',''),(39,0,'ezpublish',64,1,0,0,'','','',''),(40,0,'ezpublish',65,1,0,0,'','','',''),(41,0,'ezpublish',66,1,0,0,'','','',''),(42,0,'ezpublish',67,1,0,0,'','','',''),(43,0,'ezpublish',68,1,0,0,'','','',''),(44,0,'ezpublish',69,1,0,0,'','','',''),(45,0,'ezpublish',64,2,0,0,'','','',''),(46,0,'ezpublish',70,1,0,0,'','','',''),(47,0,'ezpublish',72,1,0,0,'','','',''),(48,0,'ezpublish',73,1,0,0,'','','',''),(49,0,'ezpublish',74,1,0,0,'','','',''),(50,0,'ezpublish',75,1,0,0,'','','',''),(51,0,'ezpublish',70,2,0,0,'','','',''),(52,0,'ezpublish',76,1,0,0,'','','',''),(53,0,'ezpublish',77,1,0,0,'','','',''),(54,0,'ezpublish',78,1,0,0,'','','',''),(55,0,'ezpublish',79,1,0,0,'','','',''),(56,0,'ezpublish',80,1,0,0,'','','',''),(57,0,'ezpublish',56,7,0,0,'','','',''),(58,0,'ezpublish',56,8,0,0,'','','',''),(59,0,'ezpublish',56,9,0,0,'','','',''),(60,0,'ezpublish',82,1,0,0,'','','',''),(61,0,'ezpublish',56,10,0,0,'','','',''),(62,0,'ezpublish',76,2,0,0,'','','',''),(63,0,'ezpublish',56,11,0,0,'','','',''),(64,0,'ezpublish',89,1,0,0,'','','',''),(65,0,'ezpublish',90,1,0,0,'','','',''),(66,0,'ezpublish',89,2,0,0,'','','',''),(67,0,'ezpublish',56,12,0,0,'','','',''),(68,0,'ezpublish',56,13,0,0,'','','',''),(69,0,'ezpublish',56,18,0,0,'','','',''),(70,0,'ezpublish',92,1,0,0,'','','',''),(71,0,'ezpublish',93,1,0,0,'','','',''),(72,0,'ezpublish',94,1,0,0,'','','',''),(73,0,'ezpublish',95,1,0,0,'','','',''),(74,0,'ezpublish',92,2,0,0,'','','',''),(75,0,'ezpublish',64,3,0,0,'','','',''),(76,0,'ezpublish',97,1,0,0,'','','',''),(77,0,'ezpublish',92,3,0,0,'','','',''),(78,0,'ezpublish',98,1,0,0,'','','',''),(79,0,'ezpublish',99,1,0,0,'','','',''),(80,0,'ezpublish',95,2,0,0,'','','',''),(81,0,'ezpublish',100,1,0,0,'','','',''),(82,0,'ezpublish',100,2,0,0,'','','',''),(83,0,'ezpublish',101,1,0,0,'','','',''),(84,0,'ezpublish',94,2,0,0,'','','',''),(85,0,'ezpublish',102,1,0,0,'','','',''),(86,0,'ezpublish',97,2,0,0,'','','',''),(87,0,'ezpublish',102,2,0,0,'','','',''),(88,0,'ezpublish',107,1,0,0,'','','',''),(89,0,'ezpublish',107,2,0,0,'','','',''),(90,0,'ezpublish',111,1,0,0,'','','',''),(91,0,'ezpublish',112,1,0,0,'','','',''),(92,0,'ezpublish',113,1,0,0,'','','',''),(93,0,'ezpublish',43,6,0,0,'','','',''),(94,0,'ezpublish',45,2,0,0,'','','',''),(95,0,'ezpublish',43,7,0,0,'','','',''),(96,0,'ezpublish',45,3,0,0,'','','',''),(97,0,'ezpublish',115,1,0,0,'','','',''),(98,0,'ezpublish',45,4,0,0,'','','',''),(99,0,'ezpublish',116,1,0,0,'','','',''),(100,0,'ezpublish',45,5,0,0,'','','',''),(101,0,'ezpublish',45,6,0,0,'','','',''),(102,0,'ezpublish',56,19,0,0,'','','',''),(103,0,'ezpublish',115,2,0,0,'','','',''),(104,0,'ezpublish',98,2,0,0,'','','',''),(105,0,'ezpublish',92,4,0,0,'','','',''),(106,0,'ezpublish',102,3,0,0,'','','',''),(107,0,'ezpublish',97,3,0,0,'','','',''),(108,0,'ezpublish',56,20,0,0,'','','',''),(109,0,'ezpublish',97,4,0,0,'','','',''),(110,0,'ezpublish',102,4,0,0,'','','',''),(111,0,'ezpublish',93,2,0,0,'','','',''),(112,0,'ezpublish',45,7,0,0,'','','',''),(113,0,'ezpublish',56,21,0,0,'','','',''),(114,0,'ezpublish',45,8,0,0,'','','',''),(115,0,'ezpublish',118,1,0,0,'','','',''),(116,0,'ezpublish',119,1,0,0,'','','',''),(117,0,'ezpublish',120,1,0,0,'','','',''),(118,0,'ezpublish',121,1,0,0,'','','',''),(119,0,'ezpublish',123,1,0,0,'','','',''),(120,0,'ezpublish',124,1,0,0,'','','',''),(121,0,'ezpublish',125,1,0,0,'','','',''),(122,0,'ezpublish',125,2,0,0,'','','',''),(123,0,'ezpublish',125,3,0,0,'','','',''),(124,0,'ezpublish',125,4,0,0,'','','',''),(125,0,'ezpublish',56,22,0,0,'','','',''),(126,0,'ezpublish',56,23,0,0,'','','',''),(127,0,'ezpublish',56,24,0,0,'','','',''),(128,0,'ezpublish',56,25,0,0,'','','',''),(129,0,'ezpublish',128,1,0,0,'','','',''),(130,0,'ezpublish',128,2,0,0,'','','',''),(131,0,'ezpublish',56,26,0,0,'','','',''),(132,0,'ezpublish',56,27,0,0,'','','',''),(133,0,'ezpublish',56,28,0,0,'','','',''),(134,0,'ezpublish',56,29,0,0,'','','',''),(135,0,'ezpublish',132,1,0,0,'','','',''),(136,0,'ezpublish',115,3,0,0,'','','',''),(137,0,'ezpublish',43,8,0,0,'','','',''),(138,0,'ezpublish',45,9,0,0,'','','',''),(139,0,'ezpublish',116,2,0,0,'','','',''),(140,0,'ezpublish',135,1,0,0,'','','',''),(141,0,'ezpublish',136,1,0,0,'','','',''),(142,0,'ezpublish',45,10,0,0,'','','',''),(143,0,'ezpublish',136,2,0,0,'','','',''),(144,0,'ezpublish',136,3,0,0,'','','',''),(145,0,'ezpublish',136,4,0,0,'','','',''),(146,0,'ezpublish',136,5,0,0,'','','',''),(147,0,'ezpublish',136,6,0,0,'','','',''),(148,0,'ezpublish',136,7,0,0,'','','',''),(149,0,'ezpublish',136,8,0,0,'','','',''),(150,0,'ezpublish',132,2,0,0,'','','',''),(151,0,'ezpublish',139,1,0,0,'','','',''),(152,0,'ezpublish',139,2,0,0,'','','',''),(153,0,'ezpublish',140,1,0,0,'','','',''),(154,0,'ezpublish',140,2,0,0,'','','',''),(155,0,'ezpublish',139,3,0,0,'','','',''),(156,0,'ezpublish',142,1,0,0,'','','',''),(157,0,'ezpublish',144,1,0,0,'','','',''),(158,0,'ezpublish',145,1,0,0,'','','',''),(159,0,'ezpublish',146,1,0,0,'','','',''),(160,0,'ezpublish',147,1,0,0,'','','',''),(161,0,'ezpublish',93,3,0,0,'','','',''),(162,0,'ezpublish',136,9,0,0,'','','',''),(163,0,'ezpublish',1,2,0,0,'','','',''),(164,0,'ezpublish',148,1,0,0,'','','',''),(165,0,'ezpublish',148,2,0,0,'','','',''),(166,0,'ezpublish',149,1,0,0,'','','',''),(167,0,'ezpublish',150,1,0,0,'','','',''),(168,0,'ezpublish',151,1,0,0,'','','',''),(169,0,'ezpublish',153,1,0,0,'','','',''),(170,0,'ezpublish',154,1,0,0,'','','',''),(171,0,'ezpublish',155,1,0,0,'','','',''),(172,0,'ezpublish',156,1,0,0,'','','',''),(173,0,'ezpublish',157,1,0,0,'','','',''),(174,0,'ezpublish',158,1,0,0,'','','',''),(175,0,'ezpublish',159,1,0,0,'','','',''),(176,0,'ezpublish',161,1,0,0,'','','',''),(177,0,'ezpublish',162,1,0,0,'','','',''),(178,0,'ezpublish',163,1,0,0,'','','',''),(179,0,'ezpublish',165,1,0,0,'','','',''),(180,0,'ezpublish',166,1,0,0,'','','',''),(181,0,'ezpublish',168,1,0,0,'','','',''),(182,0,'ezpublish',136,10,0,0,'','','',''),(183,0,'ezpublish',136,11,0,0,'','','',''),(184,0,'ezpublish',112,2,0,0,'','','',''),(185,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',171,1,0,0,'','','',''),(187,0,'ezpublish',172,1,0,0,'','','',''),(188,0,'ezpublish',173,1,0,0,'','','',''),(189,0,'ezpublish',174,1,0,0,'','','',''),(190,0,'ezpublish',175,1,0,0,'','','',''),(191,0,'ezpublish',174,2,0,0,'','','',''),(192,0,'ezpublish',176,1,0,0,'','','',''),(193,0,'ezpublish',136,13,0,0,'','','',''),(194,0,'ezpublish',136,14,0,0,'','','',''),(195,0,'ezpublish',136,15,0,0,'','','',''),(196,0,'ezpublish',136,16,0,0,'','','','');
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(370,24,'create','content',''),(371,24,'create','content',''),(372,24,'create','content',''),(341,8,'read','content','*'),(369,24,'read','content','*'),(339,1,'login','user','*'),(375,30,'login','user','*'),(373,24,'create','content',''),(374,24,'edit','content','');
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
INSERT INTO ezpolicy_limitation VALUES (289,371,'Class',0,'create','content'),(290,371,'Section',0,'create','content'),(288,370,'Section',0,'create','content'),(287,370,'Class',0,'create','content'),(291,372,'Class',0,'create','content'),(292,372,'Section',0,'create','content'),(293,373,'Class',0,'create','content'),(294,373,'Section',0,'create','content'),(295,374,'Class',0,'edit','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (555,291,'12'),(554,291,'1'),(551,289,'16'),(550,288,'4'),(548,287,'13'),(549,287,'2'),(553,290,'5'),(552,289,'17'),(547,287,'1'),(556,292,'6'),(557,293,'6'),(558,293,'7'),(559,294,'7'),(560,295,'1'),(561,295,'2'),(562,295,'6'),(563,295,'7'),(564,295,'12'),(565,295,'13'),(566,295,'16'),(567,295,'17'),(568,295,'18');
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
INSERT INTO ezrole VALUES (1,0,'Anonymous',''),(2,0,'Administrator','*'),(30,1,'Anonymous',NULL),(8,0,'Guest',NULL),(24,0,'Intranet',NULL);
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
INSERT INTO ezsearch_object_word_link VALUES (28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(1846,43,933,0,2,22,0,14,1066384365,11,155,'',0),(1845,43,22,0,1,932,933,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(1844,43,932,0,0,0,22,14,1066384365,11,152,'',0),(2962,176,1223,0,1,1218,1162,2,1069774043,4,1,'',0),(1863,45,940,0,5,939,0,14,1066388816,11,155,'',0),(1862,45,939,0,4,25,940,14,1066388816,11,155,'',0),(1861,45,25,0,3,34,939,14,1066388816,11,155,'',0),(1860,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(61,49,37,0,0,0,0,1,1066398020,4,4,'',0),(73,57,48,0,0,0,0,1,1066729137,5,4,'',0),(2277,170,619,0,30,611,650,10,1069769718,1,141,'',0),(76,59,50,0,0,0,51,1,1066729211,4,4,'',0),(77,59,51,0,1,50,0,1,1066729211,4,4,'',0),(79,60,53,0,0,0,0,1,1066729226,4,4,'',0),(80,61,54,0,0,0,37,1,1066729258,4,4,'',0),(81,61,37,0,1,54,0,1,1066729258,4,4,'',0),(82,62,55,0,0,0,0,1,1066729284,5,4,'',0),(83,63,56,0,0,0,0,1,1066729298,5,4,'',0),(821,64,440,0,0,0,381,1,1066729319,6,4,'',0),(2960,174,1213,0,176,609,0,2,1069773925,4,121,'',0),(86,66,59,0,0,0,0,1,1066729356,6,4,'',0),(87,67,60,0,0,0,0,1,1066729371,6,4,'',0),(88,68,61,0,0,0,62,1,1066729385,6,4,'',0),(89,68,62,0,1,61,0,1,1066729385,6,4,'',0),(90,69,63,0,0,0,0,1,1066729400,6,4,'',0),(2425,173,898,0,17,123,1144,16,1069770749,5,168,'contact_type',1),(2426,173,1144,0,18,898,1165,16,1069770749,5,168,'contact_value',0),(2427,173,1165,0,19,1144,1144,16,1069770749,5,168,'contact_value',0),(2400,172,1153,0,7,1152,593,17,1069770002,5,172,'contact_value',0),(2399,172,1152,0,6,1144,1153,17,1069770002,5,172,'contact_value',0),(2398,172,1144,0,5,1142,1152,17,1069770002,5,172,'contact_value',0),(2429,173,1166,0,21,1144,0,16,1069770749,5,168,'contact_value',0),(2428,173,1144,0,20,1165,1166,16,1069770749,5,168,'contact_value',0),(2959,174,609,0,175,1212,1213,2,1069773925,4,121,'',0),(2958,174,1212,0,174,1211,609,2,1069773925,4,121,'',0),(2957,174,1211,0,173,1178,1212,2,1069773925,4,121,'',0),(2397,172,1142,0,4,123,1144,17,1069770002,5,172,'contact_type',2),(2396,172,123,0,3,1151,1142,17,1069770002,5,172,'contact_type',0),(2395,172,1151,0,2,1105,123,17,1069770002,5,171,'',0),(2394,172,1105,0,1,593,1151,17,1069770002,5,169,'',0),(2393,172,593,0,0,0,1105,17,1069770002,5,170,'',0),(2392,171,1150,0,13,1149,0,17,1069769945,5,179,'',0),(2391,171,1149,0,12,89,1150,17,1069769945,5,179,'',0),(2390,171,89,0,11,1148,1149,17,1069769945,5,179,'',0),(2389,171,1148,0,10,1147,89,17,1069769945,5,172,'contact_value',0),(2388,171,1147,0,9,1146,1148,17,1069769945,5,172,'contact_value',0),(2387,171,1146,0,8,1145,1147,17,1069769945,5,172,'contact_value',0),(2386,171,1145,0,7,1144,1146,17,1069769945,5,172,'contact_value',0),(2379,171,570,0,0,0,571,17,1069769945,5,170,'',0),(2380,171,571,0,1,570,1141,17,1069769945,5,169,'',0),(2381,171,1141,0,2,571,123,17,1069769945,5,171,'',0),(2382,171,123,0,3,1141,1142,17,1069769945,5,172,'contact_type',0),(2383,171,1142,0,4,123,1143,17,1069769945,5,172,'contact_type',2),(2384,171,1143,0,5,1142,1144,17,1069769945,5,172,'contact_type',3),(2385,171,1144,0,6,1143,1145,17,1069769945,5,172,'contact_value',0),(2956,174,1178,0,172,1190,1211,2,1069773925,4,121,'',0),(2955,174,1190,0,171,627,1178,2,1069773925,4,121,'',0),(2954,174,627,0,170,1210,1190,2,1069773925,4,121,'',0),(2953,174,1210,0,169,1209,627,2,1069773925,4,121,'',0),(2952,174,1209,0,168,1208,1210,2,1069773925,4,121,'',0),(2951,174,1208,0,167,647,1209,2,1069773925,4,121,'',0),(2950,174,647,0,166,1207,1208,2,1069773925,4,121,'',0),(2949,174,1207,0,165,1206,647,2,1069773925,4,121,'',0),(2948,174,1206,0,164,665,1207,2,1069773925,4,121,'',0),(2947,174,665,0,163,1205,1206,2,1069773925,4,121,'',0),(2946,174,1205,0,162,1204,665,2,1069773925,4,121,'',0),(2945,174,1204,0,161,580,1205,2,1069773925,4,121,'',0),(2944,174,580,0,160,1203,1204,2,1069773925,4,121,'',0),(2943,174,1203,0,159,580,580,2,1069773925,4,121,'',0),(2942,174,580,0,158,1202,1203,2,1069773925,4,121,'',0),(2941,174,1202,0,157,1201,580,2,1069773925,4,121,'',0),(2940,174,1201,0,156,632,1202,2,1069773925,4,121,'',0),(2939,174,632,0,155,1200,1201,2,1069773925,4,121,'',0),(2938,174,1200,0,154,609,632,2,1069773925,4,121,'',0),(2937,174,609,0,153,1199,1200,2,1069773925,4,121,'',0),(2936,174,1199,0,152,645,609,2,1069773925,4,121,'',0),(2935,174,645,0,151,1178,1199,2,1069773925,4,121,'',0),(2934,174,1178,0,150,1198,645,2,1069773925,4,121,'',0),(2933,174,1198,0,149,625,1178,2,1069773925,4,121,'',0),(2932,174,625,0,148,1189,1198,2,1069773925,4,121,'',0),(2931,174,1189,0,147,1197,625,2,1069773925,4,121,'',0),(2930,174,1197,0,146,1196,1189,2,1069773925,4,121,'',0),(2929,174,1196,0,145,636,1197,2,1069773925,4,121,'',0),(2928,174,636,0,144,1195,1196,2,1069773925,4,121,'',0),(2927,174,1195,0,143,183,636,2,1069773925,4,121,'',0),(2926,174,183,0,142,1194,1195,2,1069773925,4,121,'',0),(2925,174,1194,0,141,183,183,2,1069773925,4,121,'',0),(2924,174,183,0,140,1174,1194,2,1069773925,4,121,'',0),(2923,174,1174,0,139,1193,183,2,1069773925,4,121,'',0),(2922,174,1193,0,138,1192,1174,2,1069773925,4,121,'',0),(2921,174,1192,0,137,625,1193,2,1069773925,4,121,'',0),(2920,174,625,0,136,1191,1192,2,1069773925,4,121,'',0),(2919,174,1191,0,135,1190,625,2,1069773925,4,121,'',0),(2918,174,1190,0,134,1189,1191,2,1069773925,4,121,'',0),(2917,174,1189,0,133,653,1190,2,1069773925,4,121,'',0),(2916,174,653,0,132,1188,1189,2,1069773925,4,121,'',0),(2915,174,1188,0,131,1187,653,2,1069773925,4,121,'',0),(2914,174,1187,0,130,1186,1188,2,1069773925,4,121,'',0),(2913,174,1186,0,129,603,1187,2,1069773925,4,121,'',0),(2746,175,1174,0,139,1193,183,2,1069773968,4,121,'',0),(2745,175,1193,0,138,1192,1174,2,1069773968,4,121,'',0),(2744,175,1192,0,137,625,1193,2,1069773968,4,121,'',0),(2743,175,625,0,136,1191,1192,2,1069773968,4,121,'',0),(2742,175,1191,0,135,1190,625,2,1069773968,4,121,'',0),(2741,175,1190,0,134,1189,1191,2,1069773968,4,121,'',0),(2740,175,1189,0,133,653,1190,2,1069773968,4,121,'',0),(2739,175,653,0,132,1188,1189,2,1069773968,4,121,'',0),(2738,175,1188,0,131,1187,653,2,1069773968,4,121,'',0),(2737,175,1187,0,130,1186,1188,2,1069773968,4,121,'',0),(2736,175,1186,0,129,603,1187,2,1069773968,4,121,'',0),(2735,175,603,0,128,599,1186,2,1069773968,4,121,'',0),(2734,175,599,0,127,694,603,2,1069773968,4,121,'',0),(2733,175,694,0,126,1185,599,2,1069773968,4,121,'',0),(2732,175,1185,0,125,649,694,2,1069773968,4,121,'',0),(2731,175,649,0,124,1184,1185,2,1069773968,4,121,'',0),(2730,175,1184,0,123,1183,649,2,1069773968,4,121,'',0),(2729,175,1183,0,122,1182,1184,2,1069773968,4,121,'',0),(2728,175,1182,0,121,628,1183,2,1069773968,4,121,'',0),(2727,175,628,0,120,1181,1182,2,1069773968,4,121,'',0),(2726,175,1181,0,119,1180,628,2,1069773968,4,121,'',0),(2725,175,1180,0,118,590,1181,2,1069773968,4,121,'',0),(2724,175,590,0,117,685,1180,2,1069773968,4,121,'',0),(2723,175,685,0,116,693,590,2,1069773968,4,121,'',0),(2722,175,693,0,115,603,685,2,1069773968,4,121,'',0),(2721,175,603,0,114,1179,693,2,1069773968,4,121,'',0),(2720,175,1179,0,113,621,603,2,1069773968,4,121,'',0),(2719,175,621,0,112,611,1179,2,1069773968,4,121,'',0),(2718,175,611,0,111,624,621,2,1069773968,4,121,'',0),(2717,175,624,0,110,1178,611,2,1069773968,4,121,'',0),(2716,175,1178,0,109,692,624,2,1069773968,4,121,'',0),(2715,175,692,0,108,603,1178,2,1069773968,4,121,'',0),(2714,175,603,0,107,1177,692,2,1069773968,4,121,'',0),(2713,175,1177,0,106,1176,603,2,1069773968,4,121,'',0),(2712,175,1176,0,105,640,1177,2,1069773968,4,121,'',0),(2711,175,640,0,104,1175,1176,2,1069773968,4,121,'',0),(2710,175,1175,0,103,631,640,2,1069773968,4,121,'',0),(2709,175,631,0,102,601,1175,2,1069773968,4,121,'',0),(2708,175,601,0,101,670,631,2,1069773968,4,121,'',0),(2707,175,670,0,100,664,601,2,1069773968,4,121,'',0),(2706,175,664,0,99,688,670,2,1069773968,4,121,'',0),(2705,175,688,0,98,690,664,2,1069773968,4,121,'',0),(2704,175,690,0,97,689,688,2,1069773968,4,121,'',0),(2703,175,689,0,96,1174,690,2,1069773968,4,121,'',0),(2702,175,1174,0,95,1173,689,2,1069773968,4,121,'',0),(2701,175,1173,0,94,668,1174,2,1069773968,4,121,'',0),(2700,175,668,0,93,1213,1173,2,1069773968,4,121,'',0),(2699,175,1213,0,92,609,668,2,1069773968,4,121,'',0),(2698,175,609,0,91,1212,1213,2,1069773968,4,121,'',0),(2697,175,1212,0,90,1211,609,2,1069773968,4,121,'',0),(2696,175,1211,0,89,1178,1212,2,1069773968,4,121,'',0),(2695,175,1178,0,88,1190,1211,2,1069773968,4,121,'',0),(2694,175,1190,0,87,627,1178,2,1069773968,4,121,'',0),(2693,175,627,0,86,1210,1190,2,1069773968,4,121,'',0),(2692,175,1210,0,85,1209,627,2,1069773968,4,121,'',0),(2691,175,1209,0,84,1208,1210,2,1069773968,4,121,'',0),(2690,175,1208,0,83,647,1209,2,1069773968,4,121,'',0),(2689,175,647,0,82,1207,1208,2,1069773968,4,121,'',0),(2688,175,1207,0,81,1206,647,2,1069773968,4,121,'',0),(2687,175,1206,0,80,665,1207,2,1069773968,4,121,'',0),(2686,175,665,0,79,1205,1206,2,1069773968,4,121,'',0),(2685,175,1205,0,78,1204,665,2,1069773968,4,121,'',0),(2684,175,1204,0,77,580,1205,2,1069773968,4,121,'',0),(2683,175,580,0,76,1203,1204,2,1069773968,4,121,'',0),(2682,175,1203,0,75,580,580,2,1069773968,4,121,'',0),(2681,175,580,0,74,1202,1203,2,1069773968,4,121,'',0),(2680,175,1202,0,73,1201,580,2,1069773968,4,121,'',0),(2679,175,1201,0,72,632,1202,2,1069773968,4,121,'',0),(2678,175,632,0,71,1200,1201,2,1069773968,4,121,'',0),(2677,175,1200,0,70,609,632,2,1069773968,4,121,'',0),(2676,175,609,0,69,1199,1200,2,1069773968,4,121,'',0),(2675,175,1199,0,68,645,609,2,1069773968,4,121,'',0),(2674,175,645,0,67,1178,1199,2,1069773968,4,121,'',0),(2673,175,1178,0,66,1198,645,2,1069773968,4,121,'',0),(2672,175,1198,0,65,625,1178,2,1069773968,4,121,'',0),(2671,175,625,0,64,1189,1198,2,1069773968,4,121,'',0),(2670,175,1189,0,63,1197,625,2,1069773968,4,121,'',0),(2669,175,1197,0,62,1196,1189,2,1069773968,4,121,'',0),(2668,175,1196,0,61,636,1197,2,1069773968,4,121,'',0),(2667,175,636,0,60,1195,1196,2,1069773968,4,121,'',0),(2666,175,1195,0,59,183,636,2,1069773968,4,121,'',0),(2665,175,183,0,58,1194,1195,2,1069773968,4,121,'',0),(2664,175,1194,0,57,183,183,2,1069773968,4,121,'',0),(2663,175,183,0,56,1174,1194,2,1069773968,4,121,'',0),(2662,175,1174,0,55,1193,183,2,1069773968,4,121,'',0),(2661,175,1193,0,54,1192,1174,2,1069773968,4,121,'',0),(2660,175,1192,0,53,625,1193,2,1069773968,4,121,'',0),(2659,175,625,0,52,1191,1192,2,1069773968,4,121,'',0),(2658,175,1191,0,51,1190,625,2,1069773968,4,121,'',0),(2657,175,1190,0,50,1189,1191,2,1069773968,4,121,'',0),(2656,175,1189,0,49,653,1190,2,1069773968,4,121,'',0),(2655,175,653,0,48,1188,1189,2,1069773968,4,121,'',0),(2654,175,1188,0,47,1187,653,2,1069773968,4,121,'',0),(2653,175,1187,0,46,1186,1188,2,1069773968,4,121,'',0),(2652,175,1186,0,45,603,1187,2,1069773968,4,121,'',0),(2651,175,603,0,44,599,1186,2,1069773968,4,121,'',0),(2650,175,599,0,43,694,603,2,1069773968,4,121,'',0),(2649,175,694,0,42,1185,599,2,1069773968,4,121,'',0),(2648,175,1185,0,41,649,694,2,1069773968,4,121,'',0),(2647,175,649,0,40,1184,1185,2,1069773968,4,121,'',0),(2646,175,1184,0,39,1183,649,2,1069773968,4,121,'',0),(2645,175,1183,0,38,1182,1184,2,1069773968,4,121,'',0),(2644,175,1182,0,37,628,1183,2,1069773968,4,121,'',0),(2643,175,628,0,36,1181,1182,2,1069773968,4,121,'',0),(2642,175,1181,0,35,1180,628,2,1069773968,4,121,'',0),(2641,175,1180,0,34,590,1181,2,1069773968,4,121,'',0),(2640,175,590,0,33,685,1180,2,1069773968,4,121,'',0),(2639,175,685,0,32,693,590,2,1069773968,4,121,'',0),(2638,175,693,0,31,603,685,2,1069773968,4,121,'',0),(2637,175,603,0,30,1179,693,2,1069773968,4,121,'',0),(2636,175,1179,0,29,621,603,2,1069773968,4,121,'',0),(2635,175,621,0,28,611,1179,2,1069773968,4,121,'',0),(2634,175,611,0,27,624,621,2,1069773968,4,121,'',0),(2633,175,624,0,26,1178,611,2,1069773968,4,121,'',0),(2632,175,1178,0,25,692,624,2,1069773968,4,121,'',0),(2631,175,692,0,24,603,1178,2,1069773968,4,121,'',0),(2630,175,603,0,23,1177,692,2,1069773968,4,121,'',0),(2629,175,1177,0,22,1176,603,2,1069773968,4,121,'',0),(2628,175,1176,0,21,640,1177,2,1069773968,4,121,'',0),(2627,175,640,0,20,1175,1176,2,1069773968,4,121,'',0),(2626,175,1175,0,19,631,640,2,1069773968,4,121,'',0),(2625,175,631,0,18,601,1175,2,1069773968,4,121,'',0),(2624,175,601,0,17,670,631,2,1069773968,4,121,'',0),(2623,175,670,0,16,664,601,2,1069773968,4,121,'',0),(2622,175,664,0,15,688,670,2,1069773968,4,121,'',0),(2621,175,688,0,14,690,664,2,1069773968,4,121,'',0),(2620,175,690,0,13,689,688,2,1069773968,4,121,'',0),(2619,175,689,0,12,1174,690,2,1069773968,4,121,'',0),(2618,175,1174,0,11,1173,689,2,1069773968,4,121,'',0),(2617,175,1173,0,10,668,1174,2,1069773968,4,121,'',0),(2616,175,668,0,9,1215,1173,2,1069773968,4,121,'',0),(2615,175,1215,0,8,1214,668,2,1069773968,4,120,'',0),(2614,175,1214,0,7,752,1215,2,1069773968,4,120,'',0),(2613,175,752,0,6,1217,1214,2,1069773968,4,120,'',0),(2612,175,1217,0,5,1171,752,2,1069773968,4,120,'',0),(2611,175,1171,0,4,1216,1217,2,1069773968,4,120,'',0),(2610,175,1216,0,3,1162,1171,2,1069773968,4,120,'',0),(2609,175,1162,0,2,1215,1216,2,1069773968,4,120,'',0),(2608,175,1215,0,1,1214,1162,2,1069773968,4,1,'',0),(2607,175,1214,0,0,0,1215,2,1069773968,4,1,'',0),(2912,174,603,0,128,599,1186,2,1069773925,4,121,'',0),(2911,174,599,0,127,694,603,2,1069773925,4,121,'',0),(2910,174,694,0,126,1185,599,2,1069773925,4,121,'',0),(2909,174,1185,0,125,649,694,2,1069773925,4,121,'',0),(2908,174,649,0,124,1184,1185,2,1069773925,4,121,'',0),(2907,174,1184,0,123,1183,649,2,1069773925,4,121,'',0),(2906,174,1183,0,122,1182,1184,2,1069773925,4,121,'',0),(2905,174,1182,0,121,628,1183,2,1069773925,4,121,'',0),(2904,174,628,0,120,1181,1182,2,1069773925,4,121,'',0),(2903,174,1181,0,119,1180,628,2,1069773925,4,121,'',0),(2902,174,1180,0,118,590,1181,2,1069773925,4,121,'',0),(2901,174,590,0,117,685,1180,2,1069773925,4,121,'',0),(2900,174,685,0,116,693,590,2,1069773925,4,121,'',0),(2899,174,693,0,115,603,685,2,1069773925,4,121,'',0),(2898,174,603,0,114,1179,693,2,1069773925,4,121,'',0),(2897,174,1179,0,113,621,603,2,1069773925,4,121,'',0),(2896,174,621,0,112,611,1179,2,1069773925,4,121,'',0),(2895,174,611,0,111,624,621,2,1069773925,4,121,'',0),(2894,174,624,0,110,1178,611,2,1069773925,4,121,'',0),(2893,174,1178,0,109,692,624,2,1069773925,4,121,'',0),(2892,174,692,0,108,603,1178,2,1069773925,4,121,'',0),(2891,174,603,0,107,1177,692,2,1069773925,4,121,'',0),(2890,174,1177,0,106,1176,603,2,1069773925,4,121,'',0),(2889,174,1176,0,105,640,1177,2,1069773925,4,121,'',0),(2888,174,640,0,104,1175,1176,2,1069773925,4,121,'',0),(2887,174,1175,0,103,631,640,2,1069773925,4,121,'',0),(2886,174,631,0,102,601,1175,2,1069773925,4,121,'',0),(2885,174,601,0,101,670,631,2,1069773925,4,121,'',0),(2884,174,670,0,100,664,601,2,1069773925,4,121,'',0),(2883,174,664,0,99,688,670,2,1069773925,4,121,'',0),(2882,174,688,0,98,690,664,2,1069773925,4,121,'',0),(2881,174,690,0,97,689,688,2,1069773925,4,121,'',0),(2880,174,689,0,96,1174,690,2,1069773925,4,121,'',0),(2879,174,1174,0,95,1173,689,2,1069773925,4,121,'',0),(2878,174,1173,0,94,668,1174,2,1069773925,4,121,'',0),(2877,174,668,0,93,1213,1173,2,1069773925,4,121,'',0),(2876,174,1213,0,92,609,668,2,1069773925,4,121,'',0),(2875,174,609,0,91,1212,1213,2,1069773925,4,121,'',0),(2874,174,1212,0,90,1211,609,2,1069773925,4,121,'',0),(2873,174,1211,0,89,1178,1212,2,1069773925,4,121,'',0),(2872,174,1178,0,88,1190,1211,2,1069773925,4,121,'',0),(2871,174,1190,0,87,627,1178,2,1069773925,4,121,'',0),(2870,174,627,0,86,1210,1190,2,1069773925,4,121,'',0),(2869,174,1210,0,85,1209,627,2,1069773925,4,121,'',0),(2868,174,1209,0,84,1208,1210,2,1069773925,4,121,'',0),(2867,174,1208,0,83,647,1209,2,1069773925,4,121,'',0),(2866,174,647,0,82,1207,1208,2,1069773925,4,121,'',0),(2865,174,1207,0,81,1206,647,2,1069773925,4,121,'',0),(2864,174,1206,0,80,665,1207,2,1069773925,4,121,'',0),(2863,174,665,0,79,1205,1206,2,1069773925,4,121,'',0),(2862,174,1205,0,78,1204,665,2,1069773925,4,121,'',0),(2861,174,1204,0,77,580,1205,2,1069773925,4,121,'',0),(2860,174,580,0,76,1203,1204,2,1069773925,4,121,'',0),(2859,174,1203,0,75,580,580,2,1069773925,4,121,'',0),(2858,174,580,0,74,1202,1203,2,1069773925,4,121,'',0),(2857,174,1202,0,73,1201,580,2,1069773925,4,121,'',0),(2856,174,1201,0,72,632,1202,2,1069773925,4,121,'',0),(2854,174,1200,0,70,609,632,2,1069773925,4,121,'',0),(2855,174,632,0,71,1200,1201,2,1069773925,4,121,'',0),(2853,174,609,0,69,1199,1200,2,1069773925,4,121,'',0),(2852,174,1199,0,68,645,609,2,1069773925,4,121,'',0),(2851,174,645,0,67,1178,1199,2,1069773925,4,121,'',0),(2850,174,1178,0,66,1198,645,2,1069773925,4,121,'',0),(2849,174,1198,0,65,625,1178,2,1069773925,4,121,'',0),(2848,174,625,0,64,1189,1198,2,1069773925,4,121,'',0),(2847,174,1189,0,63,1197,625,2,1069773925,4,121,'',0),(2846,174,1197,0,62,1196,1189,2,1069773925,4,121,'',0),(2845,174,1196,0,61,636,1197,2,1069773925,4,121,'',0),(2844,174,636,0,60,1195,1196,2,1069773925,4,121,'',0),(2843,174,1195,0,59,183,636,2,1069773925,4,121,'',0),(2842,174,183,0,58,1194,1195,2,1069773925,4,121,'',0),(2841,174,1194,0,57,183,183,2,1069773925,4,121,'',0),(2840,174,183,0,56,1174,1194,2,1069773925,4,121,'',0),(2839,174,1174,0,55,1193,183,2,1069773925,4,121,'',0),(2838,174,1193,0,54,1192,1174,2,1069773925,4,121,'',0),(2837,174,1192,0,53,625,1193,2,1069773925,4,121,'',0),(2836,174,625,0,52,1191,1192,2,1069773925,4,121,'',0),(2835,174,1191,0,51,1190,625,2,1069773925,4,121,'',0),(2834,174,1190,0,50,1189,1191,2,1069773925,4,121,'',0),(2833,174,1189,0,49,653,1190,2,1069773925,4,121,'',0),(2832,174,653,0,48,1188,1189,2,1069773925,4,121,'',0),(2831,174,1188,0,47,1187,653,2,1069773925,4,121,'',0),(2378,170,621,0,131,645,0,10,1069769718,1,141,'',0),(2377,170,645,0,130,183,621,10,1069769718,1,141,'',0),(2376,170,183,0,129,666,645,10,1069769718,1,141,'',0),(2375,170,666,0,128,628,183,10,1069769718,1,141,'',0),(2374,170,628,0,127,665,666,10,1069769718,1,141,'',0),(2373,170,665,0,126,645,628,10,1069769718,1,141,'',0),(2372,170,645,0,125,664,665,10,1069769718,1,141,'',0),(2371,170,664,0,124,663,645,10,1069769718,1,141,'',0),(2370,170,663,0,123,662,664,10,1069769718,1,141,'',0),(2369,170,662,0,122,638,663,10,1069769718,1,141,'',0),(2368,170,638,0,121,622,662,10,1069769718,1,141,'',0),(2367,170,622,0,120,609,638,10,1069769718,1,141,'',0),(2366,170,609,0,119,631,622,10,1069769718,1,141,'',0),(2365,170,631,0,118,603,609,10,1069769718,1,141,'',0),(2364,170,603,0,117,661,631,10,1069769718,1,141,'',0),(2363,170,661,0,116,660,603,10,1069769718,1,141,'',0),(2362,170,660,0,115,659,661,10,1069769718,1,141,'',0),(2361,170,659,0,114,658,660,10,1069769718,1,141,'',0),(2360,170,658,0,113,657,659,10,1069769718,1,141,'',0),(2359,170,657,0,112,629,658,10,1069769718,1,141,'',0),(2358,170,629,0,111,584,657,10,1069769718,1,141,'',0),(2357,170,584,0,110,651,629,10,1069769718,1,141,'',0),(2356,170,651,0,109,632,584,10,1069769718,1,141,'',0),(2355,170,632,0,108,575,651,10,1069769718,1,141,'',0),(2354,170,575,0,107,627,632,10,1069769718,1,141,'',0),(2353,170,627,0,106,625,575,10,1069769718,1,141,'',0),(2352,170,625,0,105,601,627,10,1069769718,1,141,'',0),(2351,170,601,0,104,624,625,10,1069769718,1,141,'',0),(2350,170,624,0,103,656,601,10,1069769718,1,141,'',0),(2349,170,656,0,102,655,624,10,1069769718,1,141,'',0),(2348,170,655,0,101,580,656,10,1069769718,1,141,'',0),(2347,170,580,0,100,654,655,10,1069769718,1,141,'',0),(2346,170,654,0,99,89,580,10,1069769718,1,141,'',0),(2345,170,89,0,98,653,654,10,1069769718,1,141,'',0),(2344,170,653,0,97,650,89,10,1069769718,1,141,'',0),(2343,170,650,0,96,619,653,10,1069769718,1,141,'',0),(2342,170,619,0,95,611,650,10,1069769718,1,141,'',0),(2341,170,611,0,94,652,619,10,1069769718,1,141,'',0),(2340,170,652,0,93,632,611,10,1069769718,1,141,'',0),(2339,170,632,0,92,644,652,10,1069769718,1,141,'',0),(2338,170,644,0,91,651,632,10,1069769718,1,141,'',0),(2337,170,651,0,90,580,644,10,1069769718,1,141,'',0),(2336,170,580,0,89,616,651,10,1069769718,1,141,'',0),(2335,170,616,0,88,650,580,10,1069769718,1,141,'',0),(2334,170,650,0,87,649,616,10,1069769718,1,141,'',0),(2333,170,649,0,86,648,650,10,1069769718,1,141,'',0),(2332,170,648,0,85,647,649,10,1069769718,1,141,'',0),(2331,170,647,0,84,646,648,10,1069769718,1,141,'',0),(2330,170,646,0,83,645,647,10,1069769718,1,141,'',0),(2329,170,645,0,82,624,646,10,1069769718,1,141,'',0),(2328,170,624,0,81,578,645,10,1069769718,1,141,'',0),(2327,170,578,0,80,644,624,10,1069769718,1,141,'',0),(2326,170,644,0,79,643,578,10,1069769718,1,141,'',0),(2325,170,643,0,78,642,644,10,1069769718,1,141,'',0),(2324,170,642,0,77,641,643,10,1069769718,1,141,'',0),(2323,170,641,0,76,640,642,10,1069769718,1,141,'',0),(2322,170,640,0,75,639,641,10,1069769718,1,141,'',0),(2321,170,639,0,74,638,640,10,1069769718,1,141,'',0),(2320,170,638,0,73,637,639,10,1069769718,1,141,'',0),(2319,170,637,0,72,614,638,10,1069769718,1,141,'',0),(2318,170,614,0,71,636,637,10,1069769718,1,141,'',0),(2317,170,636,0,70,578,614,10,1069769718,1,141,'',0),(2316,170,578,0,69,635,636,10,1069769718,1,141,'',0),(2315,170,635,0,68,634,578,10,1069769718,1,141,'',0),(2314,170,634,0,67,621,635,10,1069769718,1,141,'',0),(2313,170,621,0,66,645,634,10,1069769718,1,141,'',0),(2312,170,645,0,65,183,621,10,1069769718,1,141,'',0),(2311,170,183,0,64,666,645,10,1069769718,1,141,'',0),(2310,170,666,0,63,628,183,10,1069769718,1,141,'',0),(2309,170,628,0,62,665,666,10,1069769718,1,141,'',0),(2308,170,665,0,61,645,628,10,1069769718,1,141,'',0),(2307,170,645,0,60,664,665,10,1069769718,1,141,'',0),(2306,170,664,0,59,663,645,10,1069769718,1,141,'',0),(2305,170,663,0,58,662,664,10,1069769718,1,141,'',0),(2304,170,662,0,57,638,663,10,1069769718,1,141,'',0),(2303,170,638,0,56,622,662,10,1069769718,1,141,'',0),(2302,170,622,0,55,609,638,10,1069769718,1,141,'',0),(2301,170,609,0,54,631,622,10,1069769718,1,141,'',0),(2300,170,631,0,53,603,609,10,1069769718,1,141,'',0),(2299,170,603,0,52,661,631,10,1069769718,1,141,'',0),(2298,170,661,0,51,660,603,10,1069769718,1,141,'',0),(2297,170,660,0,50,659,661,10,1069769718,1,141,'',0),(2296,170,659,0,49,658,660,10,1069769718,1,141,'',0),(2295,170,658,0,48,657,659,10,1069769718,1,141,'',0),(2294,170,657,0,47,629,658,10,1069769718,1,141,'',0),(2293,170,629,0,46,584,657,10,1069769718,1,141,'',0),(2292,170,584,0,45,651,629,10,1069769718,1,141,'',0),(2291,170,651,0,44,632,584,10,1069769718,1,141,'',0),(2290,170,632,0,43,575,651,10,1069769718,1,141,'',0),(2289,170,575,0,42,627,632,10,1069769718,1,141,'',0),(2288,170,627,0,41,625,575,10,1069769718,1,141,'',0),(2287,170,625,0,40,601,627,10,1069769718,1,141,'',0),(2286,170,601,0,39,624,625,10,1069769718,1,141,'',0),(2285,170,624,0,38,656,601,10,1069769718,1,141,'',0),(2284,170,656,0,37,655,624,10,1069769718,1,141,'',0),(2283,170,655,0,36,580,656,10,1069769718,1,141,'',0),(2282,170,580,0,35,654,655,10,1069769718,1,141,'',0),(2281,170,654,0,34,89,580,10,1069769718,1,141,'',0),(2280,170,89,0,33,653,654,10,1069769718,1,141,'',0),(2279,170,653,0,32,650,89,10,1069769718,1,141,'',0),(2278,170,650,0,31,619,653,10,1069769718,1,141,'',0),(2262,170,578,0,15,644,624,10,1069769718,1,141,'',0),(2261,170,644,0,14,643,578,10,1069769718,1,141,'',0),(2260,170,643,0,13,642,644,10,1069769718,1,141,'',0),(2259,170,642,0,12,641,643,10,1069769718,1,141,'',0),(2258,170,641,0,11,640,642,10,1069769718,1,141,'',0),(2257,170,640,0,10,639,641,10,1069769718,1,141,'',0),(2256,170,639,0,9,638,640,10,1069769718,1,141,'',0),(2255,170,638,0,8,637,639,10,1069769718,1,141,'',0),(2254,170,637,0,7,614,638,10,1069769718,1,141,'',0),(2253,170,614,0,6,636,637,10,1069769718,1,141,'',0),(2252,170,636,0,5,578,614,10,1069769718,1,141,'',0),(2251,170,578,0,4,635,636,10,1069769718,1,141,'',0),(2250,170,635,0,3,634,578,10,1069769718,1,141,'',0),(2249,170,634,0,2,62,635,10,1069769718,1,141,'',0),(2248,170,62,0,1,1140,634,10,1069769718,1,140,'',0),(2247,170,1140,0,0,0,62,10,1069769718,1,140,'',0),(822,64,381,0,1,440,441,1,1066729319,6,119,'',0),(823,64,441,0,2,381,427,1,1066729319,6,119,'',0),(824,64,427,0,3,441,442,1,1066729319,6,119,'',0),(825,64,442,0,4,427,81,1,1066729319,6,119,'',0),(826,64,81,0,5,442,440,1,1066729319,6,119,'',0),(827,64,440,0,6,81,0,1,1066729319,6,119,'',0),(2424,173,123,0,16,123,898,16,1069770749,5,168,'contact_type',0),(2423,173,123,0,15,1164,123,16,1069770749,5,167,'0',0),(2830,174,1187,0,46,1186,1188,2,1069773925,4,121,'',0),(2829,174,1186,0,45,603,1187,2,1069773925,4,121,'',0),(2828,174,603,0,44,599,1186,2,1069773925,4,121,'',0),(2827,174,599,0,43,694,603,2,1069773925,4,121,'',0),(2825,174,1185,0,41,649,694,2,1069773925,4,121,'',0),(2826,174,694,0,42,1185,599,2,1069773925,4,121,'',0),(2422,173,1164,0,14,1163,123,16,1069770749,5,166,'',0),(2421,173,1163,0,13,1162,1164,16,1069770749,5,166,'',0),(2420,173,1162,0,12,61,1163,16,1069770749,5,166,'',0),(2419,173,61,0,11,1161,1162,16,1069770749,5,166,'',0),(2418,173,1161,0,10,89,61,16,1069770749,5,166,'',0),(2417,173,89,0,9,1142,1161,16,1069770749,5,166,'',0),(2415,173,1160,0,7,1142,1142,16,1069770749,5,164,'address_value',0),(2416,173,1142,0,8,1160,89,16,1069770749,5,164,'address_value',0),(2824,174,649,0,40,1184,1185,2,1069773925,4,121,'',0),(2823,174,1184,0,39,1183,649,2,1069773925,4,121,'',0),(2819,174,1181,0,35,1180,628,2,1069773925,4,121,'',0),(2820,174,628,0,36,1181,1182,2,1069773925,4,121,'',0),(2821,174,1182,0,37,628,1183,2,1069773925,4,121,'',0),(2822,174,1183,0,38,1182,1184,2,1069773925,4,121,'',0),(2246,112,61,0,5,752,0,1,1066986270,1,119,'',0),(2245,112,752,0,4,1139,61,1,1066986270,1,119,'',0),(2244,112,1139,0,3,1137,752,1,1066986270,1,119,'',0),(2243,112,1137,0,2,1138,1139,1,1066986270,1,119,'',0),(2242,112,1138,0,1,1137,1137,1,1066986270,1,119,'',0),(2241,112,1137,0,0,0,1138,1,1066986270,1,4,'',0),(1115,113,62,0,0,0,575,10,1066986541,1,140,'',0),(1116,113,575,0,1,62,576,10,1066986541,1,141,'',0),(1117,113,576,0,2,575,577,10,1066986541,1,141,'',0),(1118,113,577,0,3,576,578,10,1066986541,1,141,'',0),(1119,113,578,0,4,577,579,10,1066986541,1,141,'',0),(1120,113,579,0,5,578,580,10,1066986541,1,141,'',0),(1121,113,580,0,6,579,581,10,1066986541,1,141,'',0),(1122,113,581,0,7,580,580,10,1066986541,1,141,'',0),(1123,113,580,0,8,581,582,10,1066986541,1,141,'',0),(1124,113,582,0,9,580,583,10,1066986541,1,141,'',0),(1125,113,583,0,10,582,584,10,1066986541,1,141,'',0),(1126,113,584,0,11,583,585,10,1066986541,1,141,'',0),(1127,113,585,0,12,584,586,10,1066986541,1,141,'',0),(1128,113,586,0,13,585,22,10,1066986541,1,141,'',0),(1129,113,22,0,14,586,587,10,1066986541,1,141,'',0),(1130,113,587,0,15,22,588,10,1066986541,1,141,'',0),(1131,113,588,0,16,587,589,10,1066986541,1,141,'',0),(1132,113,589,0,17,588,590,10,1066986541,1,141,'',0),(1133,113,590,0,18,589,591,10,1066986541,1,141,'',0),(1134,113,591,0,19,590,592,10,1066986541,1,141,'',0),(1135,113,592,0,20,591,593,10,1066986541,1,141,'',0),(1136,113,593,0,21,592,594,10,1066986541,1,141,'',0),(1137,113,594,0,22,593,595,10,1066986541,1,141,'',0),(1138,113,595,0,23,594,593,10,1066986541,1,141,'',0),(1139,113,593,0,24,595,596,10,1066986541,1,141,'',0),(1140,113,596,0,25,593,597,10,1066986541,1,141,'',0),(1141,113,597,0,26,596,598,10,1066986541,1,141,'',0),(1142,113,598,0,27,597,586,10,1066986541,1,141,'',0),(1143,113,586,0,28,598,599,10,1066986541,1,141,'',0),(1144,113,599,0,29,586,600,10,1066986541,1,141,'',0),(1145,113,600,0,30,599,585,10,1066986541,1,141,'',0),(1146,113,585,0,31,600,598,10,1066986541,1,141,'',0),(1147,113,598,0,32,585,601,10,1066986541,1,141,'',0),(1148,113,601,0,33,598,602,10,1066986541,1,141,'',0),(1149,113,602,0,34,601,603,10,1066986541,1,141,'',0),(1150,113,603,0,35,602,604,10,1066986541,1,141,'',0),(1151,113,604,0,36,603,605,10,1066986541,1,141,'',0),(1152,113,605,0,37,604,606,10,1066986541,1,141,'',0),(1153,113,606,0,38,605,22,10,1066986541,1,141,'',0),(1154,113,22,0,39,606,587,10,1066986541,1,141,'',0),(1155,113,587,0,40,22,588,10,1066986541,1,141,'',0),(1156,113,588,0,41,587,589,10,1066986541,1,141,'',0),(1157,113,589,0,42,588,590,10,1066986541,1,141,'',0),(1158,113,590,0,43,589,591,10,1066986541,1,141,'',0),(1159,113,591,0,44,590,592,10,1066986541,1,141,'',0),(1160,113,592,0,45,591,593,10,1066986541,1,141,'',0),(1161,113,593,0,46,592,594,10,1066986541,1,141,'',0),(1162,113,594,0,47,593,595,10,1066986541,1,141,'',0),(1163,113,595,0,48,594,593,10,1066986541,1,141,'',0),(1164,113,593,0,49,595,596,10,1066986541,1,141,'',0),(1165,113,596,0,50,593,597,10,1066986541,1,141,'',0),(1166,113,597,0,51,596,607,10,1066986541,1,141,'',0),(1167,113,607,0,52,597,608,10,1066986541,1,141,'',0),(1168,113,608,0,53,607,609,10,1066986541,1,141,'',0),(1169,113,609,0,54,608,610,10,1066986541,1,141,'',0),(1170,113,610,0,55,609,580,10,1066986541,1,141,'',0),(1171,113,580,0,56,610,611,10,1066986541,1,141,'',0),(1172,113,611,0,57,580,601,10,1066986541,1,141,'',0),(1173,113,601,0,58,611,612,10,1066986541,1,141,'',0),(1174,113,612,0,59,601,580,10,1066986541,1,141,'',0),(1175,113,580,0,60,612,613,10,1066986541,1,141,'',0),(1176,113,613,0,61,580,614,10,1066986541,1,141,'',0),(1177,113,614,0,62,613,615,10,1066986541,1,141,'',0),(1178,113,615,0,63,614,616,10,1066986541,1,141,'',0),(1179,113,616,0,64,615,617,10,1066986541,1,141,'',0),(1180,113,617,0,65,616,601,10,1066986541,1,141,'',0),(1181,113,601,0,66,617,618,10,1066986541,1,141,'',0),(1182,113,618,0,67,601,619,10,1066986541,1,141,'',0),(1183,113,619,0,68,618,601,10,1066986541,1,141,'',0),(1184,113,601,0,69,619,611,10,1066986541,1,141,'',0),(1185,113,611,0,70,601,620,10,1066986541,1,141,'',0),(1186,113,620,0,71,611,621,10,1066986541,1,141,'',0),(1187,113,621,0,72,620,622,10,1066986541,1,141,'',0),(1188,113,622,0,73,621,623,10,1066986541,1,141,'',0),(1189,113,623,0,74,622,624,10,1066986541,1,141,'',0),(1190,113,624,0,75,623,625,10,1066986541,1,141,'',0),(1191,113,625,0,76,624,626,10,1066986541,1,141,'',0),(1192,113,626,0,77,625,627,10,1066986541,1,141,'',0),(1193,113,627,0,78,626,585,10,1066986541,1,141,'',0),(1194,113,585,0,79,627,628,10,1066986541,1,141,'',0),(1195,113,628,0,80,585,629,10,1066986541,1,141,'',0),(1196,113,629,0,81,628,630,10,1066986541,1,141,'',0),(1197,113,630,0,82,629,586,10,1066986541,1,141,'',0),(1198,113,586,0,83,630,631,10,1066986541,1,141,'',0),(1199,113,631,0,84,586,632,10,1066986541,1,141,'',0),(1200,113,632,0,85,631,633,10,1066986541,1,141,'',0),(1201,113,633,0,86,632,634,10,1066986541,1,141,'',0),(1202,113,634,0,87,633,635,10,1066986541,1,141,'',0),(1203,113,635,0,88,634,578,10,1066986541,1,141,'',0),(1204,113,578,0,89,635,636,10,1066986541,1,141,'',0),(1205,113,636,0,90,578,614,10,1066986541,1,141,'',0),(1206,113,614,0,91,636,637,10,1066986541,1,141,'',0),(1207,113,637,0,92,614,638,10,1066986541,1,141,'',0),(1208,113,638,0,93,637,639,10,1066986541,1,141,'',0),(1209,113,639,0,94,638,640,10,1066986541,1,141,'',0),(1210,113,640,0,95,639,641,10,1066986541,1,141,'',0),(1211,113,641,0,96,640,642,10,1066986541,1,141,'',0),(1212,113,642,0,97,641,643,10,1066986541,1,141,'',0),(1213,113,643,0,98,642,644,10,1066986541,1,141,'',0),(1214,113,644,0,99,643,578,10,1066986541,1,141,'',0),(1215,113,578,0,100,644,624,10,1066986541,1,141,'',0),(1216,113,624,0,101,578,645,10,1066986541,1,141,'',0),(1217,113,645,0,102,624,646,10,1066986541,1,141,'',0),(1218,113,646,0,103,645,647,10,1066986541,1,141,'',0),(1219,113,647,0,104,646,648,10,1066986541,1,141,'',0),(1220,113,648,0,105,647,649,10,1066986541,1,141,'',0),(1221,113,649,0,106,648,650,10,1066986541,1,141,'',0),(1222,113,650,0,107,649,616,10,1066986541,1,141,'',0),(1223,113,616,0,108,650,580,10,1066986541,1,141,'',0),(1224,113,580,0,109,616,651,10,1066986541,1,141,'',0),(1225,113,651,0,110,580,644,10,1066986541,1,141,'',0),(1226,113,644,0,111,651,632,10,1066986541,1,141,'',0),(1227,113,632,0,112,644,652,10,1066986541,1,141,'',0),(1228,113,652,0,113,632,611,10,1066986541,1,141,'',0),(1229,113,611,0,114,652,619,10,1066986541,1,141,'',0),(1230,113,619,0,115,611,650,10,1066986541,1,141,'',0),(1231,113,650,0,116,619,653,10,1066986541,1,141,'',0),(1232,113,653,0,117,650,89,10,1066986541,1,141,'',0),(1233,113,89,0,118,653,654,10,1066986541,1,141,'',0),(1234,113,654,0,119,89,580,10,1066986541,1,141,'',0),(1235,113,580,0,120,654,655,10,1066986541,1,141,'',0),(1236,113,655,0,121,580,656,10,1066986541,1,141,'',0),(1237,113,656,0,122,655,624,10,1066986541,1,141,'',0),(1238,113,624,0,123,656,601,10,1066986541,1,141,'',0),(1239,113,601,0,124,624,625,10,1066986541,1,141,'',0),(1240,113,625,0,125,601,627,10,1066986541,1,141,'',0),(1241,113,627,0,126,625,575,10,1066986541,1,141,'',0),(1242,113,575,0,127,627,632,10,1066986541,1,141,'',0),(1243,113,632,0,128,575,651,10,1066986541,1,141,'',0),(1244,113,651,0,129,632,584,10,1066986541,1,141,'',0),(1245,113,584,0,130,651,629,10,1066986541,1,141,'',0),(1246,113,629,0,131,584,657,10,1066986541,1,141,'',0),(1247,113,657,0,132,629,658,10,1066986541,1,141,'',0),(1248,113,658,0,133,657,659,10,1066986541,1,141,'',0),(1249,113,659,0,134,658,660,10,1066986541,1,141,'',0),(1250,113,660,0,135,659,661,10,1066986541,1,141,'',0),(1251,113,661,0,136,660,603,10,1066986541,1,141,'',0),(1252,113,603,0,137,661,631,10,1066986541,1,141,'',0),(1253,113,631,0,138,603,609,10,1066986541,1,141,'',0),(1254,113,609,0,139,631,622,10,1066986541,1,141,'',0),(1255,113,622,0,140,609,638,10,1066986541,1,141,'',0),(1256,113,638,0,141,622,662,10,1066986541,1,141,'',0),(1257,113,662,0,142,638,663,10,1066986541,1,141,'',0),(1258,113,663,0,143,662,664,10,1066986541,1,141,'',0),(1259,113,664,0,144,663,645,10,1066986541,1,141,'',0),(1260,113,645,0,145,664,665,10,1066986541,1,141,'',0),(1261,113,665,0,146,645,628,10,1066986541,1,141,'',0),(1262,113,628,0,147,665,666,10,1066986541,1,141,'',0),(1263,113,666,0,148,628,183,10,1066986541,1,141,'',0),(1264,113,183,0,149,666,645,10,1066986541,1,141,'',0),(1265,113,645,0,150,183,621,10,1066986541,1,141,'',0),(1266,113,621,0,151,645,667,10,1066986541,1,141,'',0),(1267,113,667,0,152,621,625,10,1066986541,1,141,'',0),(1268,113,625,0,153,667,668,10,1066986541,1,141,'',0),(1269,113,668,0,154,625,89,10,1066986541,1,141,'',0),(1270,113,89,0,155,668,599,10,1066986541,1,141,'',0),(1271,113,599,0,156,89,620,10,1066986541,1,141,'',0),(1272,113,620,0,157,599,655,10,1066986541,1,141,'',0),(1273,113,655,0,158,620,614,10,1066986541,1,141,'',0),(1274,113,614,0,159,655,669,10,1066986541,1,141,'',0),(1275,113,669,0,160,614,670,10,1066986541,1,141,'',0),(1276,113,670,0,161,669,612,10,1066986541,1,141,'',0),(1277,113,612,0,162,670,183,10,1066986541,1,141,'',0),(1278,113,183,0,163,612,671,10,1066986541,1,141,'',0),(1279,113,671,0,164,183,632,10,1066986541,1,141,'',0),(1280,113,632,0,165,671,653,10,1066986541,1,141,'',0),(1281,113,653,0,166,632,632,10,1066986541,1,141,'',0),(1282,113,632,0,167,653,609,10,1066986541,1,141,'',0),(1283,113,609,0,168,632,611,10,1066986541,1,141,'',0),(1284,113,611,0,169,609,183,10,1066986541,1,141,'',0),(1285,113,183,0,170,611,640,10,1066986541,1,141,'',0),(1286,113,640,0,171,183,672,10,1066986541,1,141,'',0),(1287,113,672,0,172,640,673,10,1066986541,1,141,'',0),(1288,113,673,0,173,672,619,10,1066986541,1,141,'',0),(1289,113,619,0,174,673,674,10,1066986541,1,141,'',0),(1290,113,674,0,175,619,637,10,1066986541,1,141,'',0),(1291,113,637,0,176,674,582,10,1066986541,1,141,'',0),(1292,113,582,0,177,637,625,10,1066986541,1,141,'',0),(1293,113,625,0,178,582,675,10,1066986541,1,141,'',0),(1294,113,675,0,179,625,637,10,1066986541,1,141,'',0),(1295,113,637,0,180,675,629,10,1066986541,1,141,'',0),(1296,113,629,0,181,637,183,10,1066986541,1,141,'',0),(1297,113,183,0,182,629,676,10,1066986541,1,141,'',0),(1298,113,676,0,183,183,677,10,1066986541,1,141,'',0),(1299,113,677,0,184,676,678,10,1066986541,1,141,'',0),(1300,113,678,0,185,677,679,10,1066986541,1,141,'',0),(1301,113,679,0,186,678,641,10,1066986541,1,141,'',0),(1302,113,641,0,187,679,628,10,1066986541,1,141,'',0),(1303,113,628,0,188,641,680,10,1066986541,1,141,'',0),(1304,113,680,0,189,628,603,10,1066986541,1,141,'',0),(1305,113,603,0,190,680,652,10,1066986541,1,141,'',0),(1306,113,652,0,191,603,620,10,1066986541,1,141,'',0),(1307,113,620,0,192,652,681,10,1066986541,1,141,'',0),(1308,113,681,0,193,620,607,10,1066986541,1,141,'',0),(1309,113,607,0,194,681,584,10,1066986541,1,141,'',0),(1310,113,584,0,195,607,599,10,1066986541,1,141,'',0),(1311,113,599,0,196,584,625,10,1066986541,1,141,'',0),(1312,113,625,0,197,599,682,10,1066986541,1,141,'',0),(1313,113,682,0,198,625,683,10,1066986541,1,141,'',0),(1314,113,683,0,199,682,684,10,1066986541,1,141,'',0),(1315,113,684,0,200,683,577,10,1066986541,1,141,'',0),(1316,113,577,0,201,684,656,10,1066986541,1,141,'',0),(1317,113,656,0,202,577,685,10,1066986541,1,141,'',0),(1318,113,685,0,203,656,641,10,1066986541,1,141,'',0),(1319,113,641,0,204,685,653,10,1066986541,1,141,'',0),(1320,113,653,0,205,641,680,10,1066986541,1,141,'',0),(1321,113,680,0,206,653,632,10,1066986541,1,141,'',0),(1322,113,632,0,207,680,686,10,1066986541,1,141,'',0),(1323,113,686,0,208,632,687,10,1066986541,1,141,'',0),(1324,113,687,0,209,686,621,10,1066986541,1,141,'',0),(1325,113,621,0,210,687,609,10,1066986541,1,141,'',0),(1326,113,609,0,211,621,623,10,1066986541,1,141,'',0),(1327,113,623,0,212,609,652,10,1066986541,1,141,'',0),(1328,113,652,0,213,623,637,10,1066986541,1,141,'',0),(1329,113,637,0,214,652,688,10,1066986541,1,141,'',0),(1330,113,688,0,215,637,615,10,1066986541,1,141,'',0),(1331,113,615,0,216,688,624,10,1066986541,1,141,'',0),(1332,113,624,0,217,615,689,10,1066986541,1,141,'',0),(1333,113,689,0,218,624,690,10,1066986541,1,141,'',0),(1334,113,690,0,219,689,619,10,1066986541,1,141,'',0),(1335,113,619,0,220,690,691,10,1066986541,1,141,'',0),(1336,113,691,0,221,619,692,10,1066986541,1,141,'',0),(1337,113,692,0,222,691,693,10,1066986541,1,141,'',0),(1338,113,693,0,223,692,601,10,1066986541,1,141,'',0),(1339,113,601,0,224,693,680,10,1066986541,1,141,'',0),(1340,113,680,0,225,601,634,10,1066986541,1,141,'',0),(1341,113,634,0,226,680,694,10,1066986541,1,141,'',0),(1342,113,694,0,227,634,624,10,1066986541,1,141,'',0),(1343,113,624,0,228,694,642,10,1066986541,1,141,'',0),(1344,113,642,0,229,624,599,10,1066986541,1,141,'',0),(1345,113,599,0,230,642,647,10,1066986541,1,141,'',0),(1346,113,647,0,231,599,600,10,1066986541,1,141,'',0),(1347,113,600,0,232,647,685,10,1066986541,1,141,'',0),(1348,113,685,0,233,600,0,10,1066986541,1,141,'',0),(1859,45,33,0,1,32,34,14,1066388816,11,152,'',0),(1843,115,303,0,2,7,0,14,1066991725,11,155,'',0),(1842,115,7,0,1,303,303,14,1066991725,11,155,'',0),(1841,115,303,0,0,0,7,14,1066991725,11,152,'',0),(1856,116,937,0,3,25,0,14,1066992054,11,155,'',0),(1855,116,25,0,2,936,937,14,1066992054,11,155,'',0),(1854,116,936,0,1,292,25,14,1066992054,11,152,'',0),(1853,116,292,0,0,0,936,14,1066992054,11,152,'',0),(1858,45,32,0,0,0,33,14,1066388816,11,152,'',0),(3065,136,1259,0,7,1258,0,15,1069164104,11,182,'',0),(2961,176,1218,0,0,0,1223,2,1069774043,4,1,'',0),(2818,174,1180,0,34,590,1181,2,1069773925,4,121,'',0),(2786,174,1220,0,2,1219,1221,2,1069773925,4,1,'',0),(2787,174,1221,0,3,1220,1218,2,1069773925,4,120,'',0),(2788,174,1218,0,4,1221,1219,2,1069773925,4,120,'',0),(2789,174,1219,0,5,1218,1220,2,1069773925,4,120,'',0),(2790,174,1220,0,6,1219,1171,2,1069773925,4,120,'',0),(2791,174,1171,0,7,1220,1222,2,1069773925,4,120,'',0),(2792,174,1222,0,8,1171,668,2,1069773925,4,120,'',0),(2793,174,668,0,9,1222,1173,2,1069773925,4,121,'',0),(2794,174,1173,0,10,668,1174,2,1069773925,4,121,'',0),(2795,174,1174,0,11,1173,689,2,1069773925,4,121,'',0),(2796,174,689,0,12,1174,690,2,1069773925,4,121,'',0),(2797,174,690,0,13,689,688,2,1069773925,4,121,'',0),(2798,174,688,0,14,690,664,2,1069773925,4,121,'',0),(2799,174,664,0,15,688,670,2,1069773925,4,121,'',0),(2800,174,670,0,16,664,601,2,1069773925,4,121,'',0),(2801,174,601,0,17,670,631,2,1069773925,4,121,'',0),(2802,174,631,0,18,601,1175,2,1069773925,4,121,'',0),(2803,174,1175,0,19,631,640,2,1069773925,4,121,'',0),(2804,174,640,0,20,1175,1176,2,1069773925,4,121,'',0),(2805,174,1176,0,21,640,1177,2,1069773925,4,121,'',0),(2806,174,1177,0,22,1176,603,2,1069773925,4,121,'',0),(2807,174,603,0,23,1177,692,2,1069773925,4,121,'',0),(2808,174,692,0,24,603,1178,2,1069773925,4,121,'',0),(2809,174,1178,0,25,692,624,2,1069773925,4,121,'',0),(2810,174,624,0,26,1178,611,2,1069773925,4,121,'',0),(2811,174,611,0,27,624,621,2,1069773925,4,121,'',0),(2812,174,621,0,28,611,1179,2,1069773925,4,121,'',0),(2267,170,648,0,20,647,649,10,1069769718,1,141,'',0),(2266,170,647,0,19,646,648,10,1069769718,1,141,'',0),(2265,170,646,0,18,645,647,10,1069769718,1,141,'',0),(2263,170,624,0,16,578,645,10,1069769718,1,141,'',0),(2264,170,645,0,17,624,646,10,1069769718,1,141,'',0),(2276,170,611,0,29,652,619,10,1069769718,1,141,'',0),(2275,170,652,0,28,632,611,10,1069769718,1,141,'',0),(2274,170,632,0,27,644,652,10,1069769718,1,141,'',0),(2273,170,644,0,26,651,632,10,1069769718,1,141,'',0),(2272,170,651,0,25,580,644,10,1069769718,1,141,'',0),(2271,170,580,0,24,616,651,10,1069769718,1,141,'',0),(2270,170,616,0,23,650,580,10,1069769718,1,141,'',0),(2269,170,650,0,22,649,616,10,1069769718,1,141,'',0),(2268,170,649,0,21,648,650,10,1069769718,1,141,'',0),(1942,146,991,0,0,0,992,2,1069411461,4,1,'',0),(1943,146,992,0,1,991,993,2,1069411461,4,120,'',0),(1944,146,993,0,2,992,0,2,1069411461,4,121,'',0),(1945,147,994,0,0,0,995,2,1069414807,4,1,'',0),(1946,147,995,0,1,994,996,2,1069414807,4,120,'',0),(1947,147,996,0,2,995,997,2,1069414807,4,120,'',0),(1948,147,997,0,3,996,998,2,1069414807,4,120,'',0),(1949,147,998,0,4,997,995,2,1069414807,4,120,'',0),(1950,147,995,0,5,998,996,2,1069414807,4,121,'',0),(1951,147,996,0,6,995,997,2,1069414807,4,121,'',0),(1952,147,997,0,7,996,998,2,1069414807,4,121,'',0),(1953,147,998,0,8,997,995,2,1069414807,4,121,'',0),(1954,147,995,0,9,998,996,2,1069414807,4,121,'',0),(1955,147,996,0,10,995,997,2,1069414807,4,121,'',0),(1956,147,997,0,11,996,998,2,1069414807,4,121,'',0),(1957,147,998,0,12,997,0,2,1069414807,4,121,'',0),(3064,136,1258,0,6,1257,1259,15,1069164104,11,182,'',0),(3063,136,1257,0,5,1256,1258,15,1069164104,11,182,'',0),(3062,136,1256,0,4,1255,1257,15,1069164104,11,182,'',0),(3061,136,1255,0,3,1254,1256,15,1069164104,11,182,'',0),(3060,136,1254,0,2,1253,1255,15,1069164104,11,182,'',0),(3059,136,1253,0,1,1252,1254,15,1069164104,11,182,'',0),(3058,136,1252,0,0,0,1253,15,1069164104,11,161,'',0),(2092,1,1052,0,0,0,1053,1,1033917596,1,4,'',0),(2093,1,1053,0,1,1052,102,1,1033917596,1,119,'',0),(2094,1,102,0,2,1053,752,1,1033917596,1,119,'',0),(2095,1,752,0,3,102,1052,1,1033917596,1,119,'',0),(2096,1,1052,0,4,752,0,1,1033917596,1,119,'',0),(2126,148,977,0,12,1071,980,17,1069417643,5,179,'',0),(2125,148,1071,0,11,1070,977,17,1069417643,5,179,'',0),(2124,148,1070,0,10,1069,1071,17,1069417643,5,179,'',0),(2123,148,1069,0,9,1068,1070,17,1069417643,5,179,'',0),(2122,148,1068,0,8,1067,1069,17,1069417643,5,179,'',0),(2121,148,1067,0,7,1066,1068,17,1069417643,5,179,'',0),(2120,148,1066,0,6,1065,1067,17,1069417643,5,172,'contact_value',34588),(2119,148,1065,0,5,898,1066,17,1069417643,5,172,'contact_value',99345),(2118,148,898,0,4,123,1065,17,1069417643,5,172,'contact_type',1),(2117,148,123,0,3,905,898,17,1069417643,5,172,'contact_type',0),(2116,148,905,0,2,1064,123,17,1069417643,5,171,'',0),(2115,148,1064,0,1,1064,905,17,1069417643,5,169,'',0),(2114,148,1064,0,0,0,1064,17,1069417643,5,170,'',0),(2127,148,980,0,13,977,1072,17,1069417643,5,179,'',0),(2128,148,1072,0,14,980,1073,17,1069417643,5,179,'',0),(2129,148,1073,0,15,1072,980,17,1069417643,5,179,'',0),(2130,148,980,0,16,1073,0,17,1069417643,5,179,'',0),(2131,149,1074,0,0,0,1075,2,1069417727,4,1,'',0),(2132,149,1075,0,1,1074,979,2,1069417727,4,120,'',0),(2133,149,979,0,2,1075,980,2,1069417727,4,120,'',0),(2134,149,980,0,3,979,0,2,1069417727,4,120,'',0),(2135,150,1076,0,0,0,1077,2,1069417787,4,1,'',0),(2136,150,1077,0,1,1076,993,2,1069417787,4,120,'',0),(2137,150,993,0,2,1077,1078,2,1069417787,4,120,'',0),(2138,150,1078,0,3,993,997,2,1069417787,4,121,'',0),(2139,150,997,0,4,1078,970,2,1069417787,4,121,'',0),(2140,150,970,0,5,997,1079,2,1069417787,4,121,'',0),(2141,150,1079,0,6,970,0,2,1069417787,4,121,'',0),(2142,151,1080,0,0,0,5,2,1069417842,4,1,'',0),(2143,151,5,0,1,1080,1081,2,1069417842,4,1,'',0),(2144,151,1081,0,2,5,1082,2,1069417842,4,120,'',0),(2145,151,1082,0,3,1081,1083,2,1069417842,4,120,'',0),(2146,151,1083,0,4,1082,1084,2,1069417842,4,120,'',0),(2147,151,1084,0,5,1083,1085,2,1069417842,4,120,'',0),(2148,151,1085,0,6,1084,1086,2,1069417842,4,121,'',0),(2149,151,1086,0,7,1085,1087,2,1069417842,4,121,'',0),(2150,151,1087,0,8,1086,0,2,1069417842,4,121,'',0),(2414,173,1142,0,6,1160,1160,16,1069770749,5,164,'address_value',0),(2413,173,1160,0,5,898,1142,16,1069770749,5,164,'address_value',0),(2412,173,898,0,4,123,1160,16,1069770749,5,164,'address_type',1),(2411,173,123,0,3,1159,898,16,1069770749,5,164,'address_type',0),(2410,173,1159,0,2,61,123,16,1069770749,5,163,'',0),(2409,173,61,0,1,1158,1159,16,1069770749,5,162,'',0),(2408,173,1158,0,0,0,61,16,1069770749,5,162,'',0),(2407,172,1157,0,14,1156,0,17,1069770002,5,179,'',0),(2406,172,1156,0,13,1155,1157,17,1069770002,5,179,'',0),(2405,172,1155,0,12,89,1156,17,1069770002,5,179,'',0),(2404,172,89,0,11,1154,1155,17,1069770002,5,179,'',0),(2403,172,1154,0,10,1105,89,17,1069770002,5,179,'',0),(2401,172,593,0,8,1153,1105,17,1069770002,5,179,'',0),(2402,172,1105,0,9,593,1154,17,1069770002,5,179,'',0),(2817,174,590,0,33,685,1180,2,1069773925,4,121,'',0),(2816,174,685,0,32,693,590,2,1069773925,4,121,'',0),(2815,174,693,0,31,603,685,2,1069773925,4,121,'',0),(2814,174,603,0,30,1179,693,2,1069773925,4,121,'',0),(2751,175,636,0,144,1195,1196,2,1069773968,4,121,'',0),(2750,175,1195,0,143,183,636,2,1069773968,4,121,'',0),(2749,175,183,0,142,1194,1195,2,1069773968,4,121,'',0),(2178,157,1100,0,0,0,1101,2,1069420019,4,1,'',0),(2179,157,1101,0,1,1100,969,2,1069420019,4,120,'',0),(2180,157,969,0,2,1101,969,2,1069420019,4,120,'',0),(2181,157,969,0,3,969,0,2,1069420019,4,120,'',0),(2748,175,1194,0,141,183,183,2,1069773968,4,121,'',0),(2747,175,183,0,140,1174,1194,2,1069773968,4,121,'',0),(2185,159,1096,0,0,0,1097,12,1069420903,6,146,'',0),(2186,159,1097,0,1,1096,0,12,1069420903,6,147,'',0),(2187,161,593,0,0,0,1105,17,1069421689,5,170,'',0),(2188,161,1105,0,1,593,1106,17,1069421689,5,169,'',0),(2189,161,1106,0,2,1105,123,17,1069421689,5,171,'',0),(2190,161,123,0,3,1106,1107,17,1069421689,5,172,'contact_type',0),(2191,161,1107,0,4,123,1108,17,1069421689,5,172,'contact_value',8908),(2192,161,1108,0,5,1107,1109,17,1069421689,5,179,'',0),(2193,161,1109,0,6,1108,1110,17,1069421689,5,179,'',0),(2194,161,1110,0,7,1109,0,17,1069421689,5,179,'',0),(2813,174,1179,0,29,621,603,2,1069773925,4,121,'',0),(2784,174,1218,0,0,0,1219,2,1069773925,4,1,'',0),(2785,174,1219,0,1,1218,1220,2,1069773925,4,1,'',0),(2199,163,1115,0,0,0,1116,2,1069422602,4,1,'',0),(2200,163,1116,0,1,1115,1117,2,1069422602,4,120,'',0),(2201,163,1117,0,2,1116,1118,2,1069422602,4,120,'',0),(2202,163,1118,0,3,1117,1117,2,1069422602,4,120,'',0),(2203,163,1117,0,4,1118,969,2,1069422602,4,120,'',0),(2204,163,969,0,5,1117,1119,2,1069422602,4,120,'',0),(2205,163,1119,0,6,969,1120,2,1069422602,4,120,'',0),(2206,163,1120,0,7,1119,1121,2,1069422602,4,120,'',0),(2207,163,1121,0,8,1120,1122,2,1069422602,4,121,'',0),(2208,163,1122,0,9,1121,969,2,1069422602,4,121,'',0),(2209,163,969,0,10,1122,970,2,1069422602,4,121,'',0),(2210,163,970,0,11,969,1123,2,1069422602,4,121,'',0),(2211,163,1123,0,12,970,1124,2,1069422602,4,121,'',0),(2212,163,1124,0,13,1123,1124,2,1069422602,4,121,'',0),(2213,163,1124,0,14,1124,970,2,1069422602,4,121,'',0),(2214,163,970,0,15,1124,970,2,1069422602,4,121,'',0),(2215,163,970,0,16,970,0,2,1069422602,4,121,'',0),(2216,165,1096,0,0,0,1077,13,1069423490,4,149,'',0),(2217,165,1077,0,1,1096,1102,13,1069423490,4,150,'',0),(2218,165,1102,0,2,1077,0,13,1069423490,4,151,'',0),(2219,166,1125,0,0,0,1126,13,1069423957,4,149,'',0),(2220,166,1126,0,1,1125,1126,13,1069423957,4,150,'',0),(2221,166,1126,0,2,1126,0,13,1069423957,4,151,'',0),(2222,168,1127,0,0,0,1128,2,1069675837,4,1,'',0),(2223,168,1128,0,1,1127,985,2,1069675837,4,120,'',0),(2224,168,985,0,2,1128,0,2,1069675837,4,121,'',0),(2752,175,1196,0,145,636,1197,2,1069773968,4,121,'',0),(2753,175,1197,0,146,1196,1189,2,1069773968,4,121,'',0),(2754,175,1189,0,147,1197,625,2,1069773968,4,121,'',0),(2755,175,625,0,148,1189,1198,2,1069773968,4,121,'',0),(2756,175,1198,0,149,625,1178,2,1069773968,4,121,'',0),(2757,175,1178,0,150,1198,645,2,1069773968,4,121,'',0),(2758,175,645,0,151,1178,1199,2,1069773968,4,121,'',0),(2759,175,1199,0,152,645,609,2,1069773968,4,121,'',0),(2760,175,609,0,153,1199,1200,2,1069773968,4,121,'',0),(2761,175,1200,0,154,609,632,2,1069773968,4,121,'',0),(2762,175,632,0,155,1200,1201,2,1069773968,4,121,'',0),(2763,175,1201,0,156,632,1202,2,1069773968,4,121,'',0),(2764,175,1202,0,157,1201,580,2,1069773968,4,121,'',0),(2765,175,580,0,158,1202,1203,2,1069773968,4,121,'',0),(2766,175,1203,0,159,580,580,2,1069773968,4,121,'',0),(2767,175,580,0,160,1203,1204,2,1069773968,4,121,'',0),(2768,175,1204,0,161,580,1205,2,1069773968,4,121,'',0),(2769,175,1205,0,162,1204,665,2,1069773968,4,121,'',0),(2770,175,665,0,163,1205,1206,2,1069773968,4,121,'',0),(2771,175,1206,0,164,665,1207,2,1069773968,4,121,'',0),(2772,175,1207,0,165,1206,647,2,1069773968,4,121,'',0),(2773,175,647,0,166,1207,1208,2,1069773968,4,121,'',0),(2774,175,1208,0,167,647,1209,2,1069773968,4,121,'',0),(2775,175,1209,0,168,1208,1210,2,1069773968,4,121,'',0),(2776,175,1210,0,169,1209,627,2,1069773968,4,121,'',0),(2777,175,627,0,170,1210,1190,2,1069773968,4,121,'',0),(2778,175,1190,0,171,627,1178,2,1069773968,4,121,'',0),(2779,175,1178,0,172,1190,1211,2,1069773968,4,121,'',0),(2780,175,1211,0,173,1178,1212,2,1069773968,4,121,'',0),(2781,175,1212,0,174,1211,609,2,1069773968,4,121,'',0),(2782,175,609,0,175,1212,1213,2,1069773968,4,121,'',0),(2783,175,1213,0,176,609,0,2,1069773968,4,121,'',0),(2963,176,1162,0,2,1223,1216,2,1069774043,4,120,'',0),(2964,176,1216,0,3,1162,1171,2,1069774043,4,120,'',0),(2965,176,1171,0,4,1216,1224,2,1069774043,4,120,'',0),(2966,176,1224,0,5,1171,89,2,1069774043,4,120,'',0),(2967,176,89,0,6,1224,1218,2,1069774043,4,120,'',0),(2968,176,1218,0,7,89,1225,2,1069774043,4,120,'',0),(2969,176,1225,0,8,1218,1226,2,1069774043,4,120,'',0),(2970,176,1226,0,9,1225,752,2,1069774043,4,120,'',0),(2971,176,752,0,10,1226,1227,2,1069774043,4,120,'',0),(2972,176,1227,0,11,752,668,2,1069774043,4,120,'',0),(2973,176,668,0,12,1227,1173,2,1069774043,4,121,'',0),(2974,176,1173,0,13,668,1174,2,1069774043,4,121,'',0),(2975,176,1174,0,14,1173,689,2,1069774043,4,121,'',0),(2976,176,689,0,15,1174,690,2,1069774043,4,121,'',0),(2977,176,690,0,16,689,688,2,1069774043,4,121,'',0),(2978,176,688,0,17,690,664,2,1069774043,4,121,'',0),(2979,176,664,0,18,688,670,2,1069774043,4,121,'',0),(2980,176,670,0,19,664,601,2,1069774043,4,121,'',0),(2981,176,601,0,20,670,631,2,1069774043,4,121,'',0),(2982,176,631,0,21,601,1175,2,1069774043,4,121,'',0),(2983,176,1175,0,22,631,640,2,1069774043,4,121,'',0),(2984,176,640,0,23,1175,1176,2,1069774043,4,121,'',0),(2985,176,1176,0,24,640,1177,2,1069774043,4,121,'',0),(2986,176,1177,0,25,1176,603,2,1069774043,4,121,'',0),(2987,176,603,0,26,1177,692,2,1069774043,4,121,'',0),(2988,176,692,0,27,603,1178,2,1069774043,4,121,'',0),(2989,176,1178,0,28,692,624,2,1069774043,4,121,'',0),(2990,176,624,0,29,1178,611,2,1069774043,4,121,'',0),(2991,176,611,0,30,624,621,2,1069774043,4,121,'',0),(2992,176,621,0,31,611,1179,2,1069774043,4,121,'',0),(2993,176,1179,0,32,621,603,2,1069774043,4,121,'',0),(2994,176,603,0,33,1179,693,2,1069774043,4,121,'',0),(2995,176,693,0,34,603,685,2,1069774043,4,121,'',0),(2996,176,685,0,35,693,590,2,1069774043,4,121,'',0),(2997,176,590,0,36,685,1180,2,1069774043,4,121,'',0),(2998,176,1180,0,37,590,1181,2,1069774043,4,121,'',0),(2999,176,1181,0,38,1180,628,2,1069774043,4,121,'',0),(3000,176,628,0,39,1181,1182,2,1069774043,4,121,'',0),(3001,176,1182,0,40,628,1183,2,1069774043,4,121,'',0),(3002,176,1183,0,41,1182,1184,2,1069774043,4,121,'',0),(3003,176,1184,0,42,1183,649,2,1069774043,4,121,'',0),(3004,176,649,0,43,1184,1185,2,1069774043,4,121,'',0),(3005,176,1185,0,44,649,694,2,1069774043,4,121,'',0),(3006,176,694,0,45,1185,599,2,1069774043,4,121,'',0),(3007,176,599,0,46,694,603,2,1069774043,4,121,'',0),(3008,176,603,0,47,599,1186,2,1069774043,4,121,'',0),(3009,176,1186,0,48,603,1187,2,1069774043,4,121,'',0),(3010,176,1187,0,49,1186,1188,2,1069774043,4,121,'',0),(3011,176,1188,0,50,1187,653,2,1069774043,4,121,'',0),(3012,176,653,0,51,1188,1189,2,1069774043,4,121,'',0),(3013,176,1189,0,52,653,1190,2,1069774043,4,121,'',0),(3014,176,1190,0,53,1189,1191,2,1069774043,4,121,'',0),(3015,176,1191,0,54,1190,625,2,1069774043,4,121,'',0),(3016,176,625,0,55,1191,1192,2,1069774043,4,121,'',0),(3017,176,1192,0,56,625,1193,2,1069774043,4,121,'',0),(3018,176,1193,0,57,1192,1174,2,1069774043,4,121,'',0),(3019,176,1174,0,58,1193,183,2,1069774043,4,121,'',0),(3020,176,183,0,59,1174,1194,2,1069774043,4,121,'',0),(3021,176,1194,0,60,183,183,2,1069774043,4,121,'',0),(3022,176,183,0,61,1194,1195,2,1069774043,4,121,'',0),(3023,176,1195,0,62,183,636,2,1069774043,4,121,'',0),(3024,176,636,0,63,1195,1196,2,1069774043,4,121,'',0),(3025,176,1196,0,64,636,1197,2,1069774043,4,121,'',0),(3026,176,1197,0,65,1196,1189,2,1069774043,4,121,'',0),(3027,176,1189,0,66,1197,625,2,1069774043,4,121,'',0),(3028,176,625,0,67,1189,1198,2,1069774043,4,121,'',0),(3029,176,1198,0,68,625,1178,2,1069774043,4,121,'',0),(3030,176,1178,0,69,1198,645,2,1069774043,4,121,'',0),(3031,176,645,0,70,1178,1199,2,1069774043,4,121,'',0),(3032,176,1199,0,71,645,609,2,1069774043,4,121,'',0),(3033,176,609,0,72,1199,0,2,1069774043,4,121,'',0);
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
INSERT INTO ezsearch_word VALUES (5,'test',1),(6,'media',1),(7,'setup',3),(933,'grouplist',1),(22,'class',2),(932,'classes',1),(11,'links',1),(1154,'is',1),(25,'content',2),(1259,'2003',1),(34,'feel',2),(33,'and',2),(32,'look',2),(37,'news',2),(1258,'1999',1),(48,'contact',1),(50,'off',1),(51,'topic',1),(53,'reports',1),(54,'staff',1),(55,'persons',1),(56,'companies',1),(440,'files',1),(1195,'vulputate',3),(59,'handbooks',1),(60,'documents',1),(61,'company',3),(62,'routines',3),(63,'logos',1),(898,'1',2),(1223,'employee',1),(1153,'per.son@example.com',1),(1201,'vero',2),(1217,'released',1),(81,'all',1),(89,'a',6),(102,'to',1),(1161,'small',1),(123,'0',5),(1216,'ve',2),(1215,'report',1),(1214,'annual',1),(1213,'facilisi',2),(1212,'feugait',2),(1211,'te',2),(1210,'delenit',2),(1209,'zzril',2),(1208,'luptatum',2),(1207,'blandit',2),(1206,'qui',2),(1205,'odio',2),(1204,'iusto',2),(1257,'as',1),(1200,'facilisis',2),(1203,'accumsan',2),(1199,'feugiat',3),(1198,'illum',3),(1197,'molestie',3),(1196,'esse',3),(1202,'eros',2),(1222,'arrived',1),(1221,'the',1),(183,'in',5),(381,'here',1),(752,'our',4),(1256,'systems',1),(1255,'ez',1),(1253,'copyright',1),(1254,'&copy',1),(292,'url',1),(1175,'nonummy',3),(303,'cache',1),(1138,'general',1),(1144,'555',3),(1227,'team',1),(1226,'of',1),(1140,'vacation',1),(1139,'about',1),(427,'can',1),(441,'you',1),(442,'download',1),(1220,'cards',1),(1219,'business',1),(1166,'2344',1),(1165,'2345',1),(1164,'with',1),(1163,'work',1),(1174,'dolor',3),(1173,'ipsum',3),(1218,'new',2),(1171,'just',3),(1162,'we',3),(571,'doe',1),(570,'john',1),(1137,'information',1),(575,'pellentesque',2),(576,'habitant',1),(577,'morbi',1),(578,'tristique',2),(579,'senectus',1),(580,'et',4),(581,'netus',1),(582,'malesuada',1),(583,'fames',1),(584,'ac',2),(585,'turpis',1),(586,'egestas',1),(587,'aptent',1),(588,'taciti',1),(589,'sociosqu',1),(590,'ad',4),(591,'litora',1),(592,'torquent',1),(593,'per',3),(594,'conubia',1),(595,'nostra',1),(596,'inceptos',1),(597,'hymenaeos',1),(598,'cras',1),(599,'nisl',4),(600,'non',1),(601,'sed',5),(602,'leo',1),(603,'ut',5),(604,'dui',1),(605,'iaculis',1),(606,'pharetra',1),(607,'donec',1),(608,'felis',1),(609,'nulla',5),(610,'aliquet',1),(611,'aliquam',5),(612,'ultricies',1),(613,'urna',1),(614,'vivamus',2),(615,'risus',1),(616,'fusce',2),(617,'pede',1),(618,'ornare',1),(619,'lectus',2),(620,'auctor',1),(621,'erat',5),(622,'purus',2),(623,'elementum',1),(624,'magna',5),(625,'vel',5),(626,'luctus',1),(627,'augue',4),(628,'quis',5),(629,'massa',2),(630,'nullam',1),(631,'diam',5),(632,'at',4),(633,'mi',1),(634,'vestibulum',2),(635,'viverra',2),(636,'velit',5),(637,'vitae',2),(638,'quam',2),(639,'mauris',2),(640,'nibh',5),(641,'phasellus',2),(642,'nec',2),(643,'metus',2),(644,'integer',2),(645,'eu',5),(646,'sem',2),(647,'praesent',4),(648,'rutrum',2),(649,'ullamcorper',5),(650,'ligula',2),(651,'est',2),(652,'orci',2),(653,'commodo',5),(654,'rhoncus',2),(655,'semper',2),(656,'eget',2),(657,'gravida',2),(658,'vehicula',2),(659,'suspendisse',2),(660,'potenti',2),(661,'aenean',2),(662,'sodales',2),(663,'id',2),(664,'adipiscing',5),(665,'dignissim',4),(666,'libero',2),(667,'maecenas',1),(668,'lorem',4),(669,'arcu',1),(670,'elit',4),(671,'congue',1),(672,'etiam',1),(673,'sapien',1),(674,'mollis',1),(675,'fermentum',1),(676,'hac',1),(677,'habitasse',1),(678,'platea',1),(679,'dictumst',1),(680,'neque',1),(681,'posuere',1),(682,'nunc',1),(683,'porttitor',1),(684,'venenatis',1),(685,'enim',4),(686,'sagittis',1),(687,'scelerisque',1),(688,'consectetuer',4),(689,'sit',4),(690,'amet',4),(691,'curabitur',1),(692,'laoreet',4),(693,'wisi',4),(694,'lobortis',4),(1152,'1236',1),(940,'136',1),(939,'edit',1),(937,'urltranslator',1),(936,'translator',1),(1147,'http',1),(1143,'3',1),(905,'foo',1),(1142,'2',3),(1141,'developer',1),(1160,'mystreet',1),(1159,'c100',1),(1158,'my',1),(1157,'person',1),(1156,'active',1),(1155,'very',1),(1146,'doe@example.com',1),(1145,'1234',1),(1181,'veniam',3),(1180,'minim',3),(1182,'nostrud',3),(1179,'volutpat',3),(1178,'dolore',3),(1193,'iriure',3),(1192,'eum',3),(1191,'autem',3),(1190,'duis',3),(1186,'aliquip',3),(1185,'suscipit',3),(1184,'tation',3),(1183,'exerci',3),(969,'gh',2),(970,'dfgh',2),(1194,'hendrerit',3),(977,'sdgf',1),(1189,'consequat',3),(979,'dsgf',1),(980,'sd',2),(1188,'ea',3),(1187,'ex',3),(985,'lkjklj',1),(991,'mnb',1),(992,'jkh',1),(993,'kjh',2),(994,'fdhjkldfhj',1),(995,'jkhhjlk',1),(996,'hjcvxbdfgh',1),(997,'dfh',2),(998,'df',1),(1052,'intranet',1),(1053,'welcome',1),(1072,'gfsd',1),(1071,'sdkjh',1),(1070,'dskjgf',1),(1069,'sdjkfgh',1),(1068,'sdkfjgh',1),(1067,'kjhsdfgjkshdgjk',1),(1066,'34588',1),(1065,'99345',1),(1064,'jh',1),(1073,'gf',1),(1074,'dfsdfg',1),(1075,'dfsgsdfgsdfg',1),(1076,'sdifgksdjfgkjgh',1),(1077,'kjhjkh',2),(1078,'kjhjkhghdf',1),(1079,'fdg',1),(1080,'kåre',1),(1081,'jhghj',1),(1082,'gj',1),(1083,'hjg',1),(1084,'kgjkgjkg',1),(1085,'kjg',1),(1086,'jkg',1),(1087,'jk',1),(1151,'administrator',1),(1150,'guy',1),(1149,'nice',1),(1148,'www.example.com',1),(1225,'member',1),(1224,'got',1),(1096,'jkhkjh',2),(1097,'kjhkjh',1),(1100,'utuytuy',1),(1101,'gfhjfghdf',1),(1102,'jkhjkh',1),(1105,'son',2),(1106,'sjef',1),(1107,'8908',1),(1108,'098ds',1),(1109,'sdfghfd',1),(1110,'ghdfgh',1),(1177,'tincidunt',3),(1176,'euismod',3),(1115,'lkj',1),(1116,'lkjkljkljfd',1),(1117,'ghdf',1),(1118,'ghfdghdf',1),(1119,'fgh',1),(1120,'dfhdfghdfgh',1),(1121,'kljlkjl',1),(1122,'dfghdf',1),(1123,'fdh',1),(1124,'fdgh',1),(1125,'kljjkl',1),(1126,'lkjlkj',1),(1127,'jkljlkj',1),(1128,'lkjkljkljlkjklj',1),(1252,'intranet_package',1);
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
INSERT INTO ezsession VALUES ('58edd11b0756bf41cc4cf9d8690902df',1070101178,'eZUserGroupsCache_Timestamp|i:1069841919;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069841919;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069841919;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069841977;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:18:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:1:\"6\";s:4:\"name\";s:5:\"Forum\";}i:6;a:2:{s:2:\"id\";s:1:\"7\";s:4:\"name\";s:13:\"Forum message\";}i:7;a:2:{s:2:\"id\";s:1:\"8\";s:4:\"name\";s:7:\"Product\";}i:8;a:2:{s:2:\"id\";s:1:\"9\";s:4:\"name\";s:14:\"Product review\";}i:9;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:10;a:2:{s:2:\"id\";s:2:\"11\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:12;a:2:{s:2:\"id\";s:2:\"13\";s:4:\"name\";s:7:\"Comment\";}i:13;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:14;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:15;a:2:{s:2:\"id\";s:2:\"16\";s:4:\"name\";s:7:\"Company\";}i:16;a:2:{s:2:\"id\";s:2:\"17\";s:4:\"name\";s:6:\"Person\";}i:17;a:2:{s:2:\"id\";s:2:\"18\";s:4:\"name\";s:5:\"Event\";}}Preferences-advanced_menu|b:0;'),('6b757a80dcd2886681c0a2dc420526f6',1070101554,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069842352;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069842352;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069842352;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"339\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}}}canInstantiateClassesCachedForUser|s:2:\"10\";classesCachedTimestamp|N;canInstantiateClasses|i:0;Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;'),('4d2b4416f789250f9013cd293ed8a6c1',1070103326,'eZUserGroupsCache_Timestamp|i:1069843884;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069843884;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069843884;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}eZUserDiscountRulesTimestamp|i:1069843884;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}');
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0),(37,'news/off_topic','c77d3081eac3bee15b0213bcc89b369b','content/view/full/57',1,0,0),(96,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/107',1,0,0),(35,'contact','2f8a6bf31f3bd67bd2d9720c58b19c9a','content/view/full/55',1,0,0),(38,'news/reports_','ac624940baa3e037e0467bf2db2743cb','content/view/full/58',1,39,0),(39,'news/reports','f3cbeafbd5dbf7477a9a803d47d4dcbb','content/view/full/58',1,0,0),(40,'news/staff_news','c50e4a6eb10a499c098857026282ceb4','content/view/full/59',1,0,0),(41,'contact/persons','8d26f497abc489a9566eab966cbfe3ed','content/view/full/60',1,0,0),(42,'contact/companies','7b84a445a156acf3dd455ea6f585d78f','content/view/full/61',1,0,0),(43,'files','45b963397aa40d4a0063e0d85e4fe7a1','content/view/full/62',1,0,0),(45,'files/handbooks','7b18bc03d154e9c0643a86d3d2b7d68f','content/view/full/64',1,0,0),(46,'files/documents','2d30f25cef1a92db784bc537e8bf128d','content/view/full/65',1,0,0),(47,'files/company_routines','7ffaba1db587b80e9767abd0ceb00df7','content/view/full/66',1,0,0),(48,'files/logos','ab4749ddb9d45855d2431d2341c1c14e','content/view/full/67',1,0,0),(138,'contact/persons/john_doe','7945048c2293246f22831f5df43ea531','content/view/full/148',1,0,0),(139,'contact/persons/per_son__1','929374712dd2595e1423fd3e5a1fabb6','content/view/full/149',1,0,0),(140,'contact/companies/my_company','01fb086b461ff9993a7494aacdb4e1d0','content/view/full/150',1,0,0),(141,'news/reports/new_business_cards','9f643832f26db5a0599cab139e7729d6','content/view/full/151',1,0,0),(142,'news/off_topic/new_business_cards','101bdc76d2a9e0fcc0dff8ca415709c4','content/view/full/152',1,0,0),(143,'news/reports/annual_report','7d71ac68f1f9c9f03c530add2b18f8d7','content/view/full/153',1,0,0),(144,'news/staff_news/new_employee','9e18db101f2d057531652aba88e141a3','content/view/full/154',1,0,0),(137,'information/vacation_routines','59e579c66a21dc175d202e5ceafc9068','content/view/full/147',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(108,'contact/persons/jh_jh','cf01f10bebd4f9aec52a6778c36c8233','content/view/full/118',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(76,'information','bb3ccd5881d651448ded1dac904054ac','content/view/full/93',1,0,0),(77,'information/routines','ed84b3909be89ec2c730ddc2fa7b7a46','content/view/full/94',1,0,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(104,'news/staff_news/mnb','e816c1fe1795536e6896953fa5249ef7','content/view/full/114',1,0,0),(106,'news/reports/fdhjkldfhj','5928e1cce21ba84ed7f29cc0e00136be','content/view/full/116',1,0,0),(110,'news/off_topic/dfsdfg','3933dd18ca8b2e509b8b45118a8eaad4','content/view/full/120',1,0,0),(112,'news/staff_news/sdifgksdjfgkjgh','524539c61399ed93fc9fa090da54ec9d','content/view/full/122',1,0,0),(114,'news/reports/kre_test','2dc61bac93b13af1aae5a92ac15c55e7','content/view/full/124',1,0,0),(122,'news/reports/utuytuy','863350595188c49f4925dba8a66d1a18','content/view/full/132',1,0,0),(126,'files/products/jkhkjh','09f9658056d40e65ae3521fd02f89fe1','content/view/full/136',1,0,0),(128,'contact/persons/per_son','547056bbe8097664a20631c40f020e22','content/view/full/138',1,0,0),(131,'news/staff_news/lkj','76ca99cb194260eb47ec5d697473b0a2','content/view/full/141',1,0,0),(133,'news/staff_news/lkj/jkhkjh','0ab56a1b16594b117f45bb7797574a9c','content/view/full/143',1,0,0),(134,'news/staff_news/lkj/kljjkl','c3ffa06697cf122974c26a55bb1e64f7','content/view/full/144',1,0,0),(135,'news/reports/jkljlkj','079e3d5ad55d498fae54a21fe49b57b5','content/view/full/145',1,0,0);
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

alter table ezrss_export add rss_version varchar(255) default null;