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
INSERT INTO ezbinaryfile VALUES (247,1,'phpfxvKKl.tar','test.tar','application/x-tar'),(244,2,'phpL40xq8.zip','editor-1.2.zip','application/x-zip'),(564,1,'phpP59jcb.txt','3_4_issues.txt','text/plain'),(587,1,'php1RbEYd.txt','3_4_issues.txt','text/plain');
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
INSERT INTO ezcontentbrowserecent VALUES (76,14,50,1069675837,'News'),(35,111,99,1067006746,'foo bar corp');
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',NULL,0,1),(128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',NULL,0,1),(125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',NULL,0,1),(132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(178,0,17,'picture','picture','ezimage',0,0,5,1,0,0,0,0,0,0,0,'','','','','',0,1),(179,0,17,'comment','Comment','ezxmltext',1,0,6,10,0,0,0,0,0,0,0,'','','','','',0,1),(182,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(167,0,16,'relation','Relation','ezselection',1,0,6,0,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezselection>\n  <options>\n    <option id=\"0\"\n            name=\"Partner\" />\n    <option id=\"2\"\n            name=\"Customer\" />\n    <option id=\"3\"\n            name=\"Supplier\" />\n  </options>\n</ezselection>',0,1),(168,0,16,'company_numbers','Company numbers','ezmatrix',1,0,7,2,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <column-name id=\"contact_type\"\n               idx=\"0\">Contact type</column-name>\n  <column-name id=\"contact_value\"\n               idx=\"1\">Contact value</column-name>\n</ezmatrix>',0,1),(176,0,18,'event_info','Event info','eztext',1,0,3,10,0,0,0,0,0,0,0,'','','','','',0,1),(175,0,18,'event_date','Event date','ezdate',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(173,0,18,'event_name','Event name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(170,0,17,'first_name','First name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(181,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(169,0,17,'last_name','Last name','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(171,0,17,'position','Position/job','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(172,0,17,'person_numbers','Person numbers','ezmatrix',1,0,4,0,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <column-name id=\"contact_type\"\n               idx=\"0\">Contact type</column-name>\n  <column-name id=\"contact_value\"\n               idx=\"1\">Contact value</column-name>\n</ezmatrix>',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(165,0,16,'logo','Logo','ezimage',0,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(166,0,16,'additional_information','Additional information','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'css','CSS','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'css','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(162,0,16,'company_name','Company name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(163,0,16,'company_number','Company number','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(164,0,16,'company_address','Company address','ezmatrix',1,0,3,3,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <column-name id=\"address_type\"\n               idx=\"0\">Address type</column-name>\n  <column-name id=\"address_value\"\n               idx=\"1\">Address value</column-name>\n</ezmatrix>',0,1);
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Intranet',2,0,1033917596,1069416323,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(40,14,2,4,'test test',1,0,1053613020,1053613020,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',8,0,1066384365,1069162841,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',10,0,1066388816,1069164888,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,4,1,'News',1,0,1066398020,1066398020,1,''),(121,111,5,17,'Vidar Langseid',1,0,1067006667,1067006667,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(136,14,11,15,'Intranet',11,0,1069164104,1069687155,1,''),(57,14,5,1,'Contact',1,0,1066729137,1066729137,1,''),(58,14,4,1,'Business news',1,0,1066729196,1066729196,1,''),(59,14,4,1,'Off topic',1,0,1066729211,1066729211,1,''),(60,14,4,1,'Reports',2,0,1066729226,1066729241,1,''),(61,14,4,1,'Staff news',1,0,1066729258,1066729258,1,''),(62,14,5,1,'Persons',1,0,1066729284,1066729284,1,''),(63,14,5,1,'Companies',1,0,1066729298,1066729298,1,''),(64,14,6,1,'Files',3,0,1066729319,1066898100,1,''),(65,14,6,1,'Products',1,0,1066729341,1066729341,1,''),(66,14,6,1,'Handbooks',1,0,1066729356,1066729356,1,''),(67,14,6,1,'Documents',1,0,1066729371,1066729371,1,''),(68,14,6,1,'Company routines',1,0,1066729385,1066729385,1,''),(69,14,6,1,'Logos',1,0,1066729400,1066729400,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(132,14,5,17,'Terje Kaste',2,0,1067417696,1069336241,1,''),(124,111,5,17,'fido barida',1,0,1067006746,1067006746,1,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(148,14,5,17,'jh jh',2,0,1069417643,1069417688,1,''),(123,111,5,17,'Bård Farstad',1,0,1067006701,1067006701,1,''),(82,14,5,16,'abb',1,0,1066740213,1066740213,1,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(87,14,5,16,'New Company',1,0,0,0,0,''),(88,14,2,4,'New User',1,0,0,0,0,''),(89,14,6,12,'Online Editor',2,0,1066746701,1066747596,1,''),(90,14,6,12,'eZ publish 3.2',1,0,1066746790,1066746790,1,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(92,14,4,2,'eZ systems - reporting live from Munich',4,0,1066828821,1066998232,1,''),(93,14,4,2,'eZ publish 3.2-2 release',3,0,1066828903,1069414861,1,''),(94,14,4,2,'Mr xxx joined us',2,0,1066829047,1066910828,1,''),(96,14,2,4,'New User',1,0,0,0,0,''),(97,14,5,17,'Yu Wenyue',4,0,1066898315,1066998783,1,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(119,111,5,16,'eZ sys',1,0,1067006564,1067006564,1,''),(120,111,5,16,'foo bar corp',1,0,1067006610,1067006610,1,''),(102,14,5,17,'Reiten Bjørn',4,0,1066911635,1066998808,1,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(107,14,2,4,'John Doe',2,0,1066916865,1066916941,1,''),(112,14,1,1,'Information',1,0,1066986270,1066986270,1,''),(111,14,2,4,'vid la',1,0,1066917523,1066917523,1,''),(113,14,1,10,'Routines',1,0,1066986541,1066986541,1,''),(115,14,11,14,'Cache',3,0,1066991725,1069162746,1,''),(116,14,11,14,'URL translator',2,0,1066992054,1069162892,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(133,14,11,15,'New Template look',1,0,0,0,0,''),(134,14,11,15,'New Template look',1,0,0,0,0,''),(139,14,5,17,'Bilbo Baggins',3,0,1069336369,1069339387,1,''),(140,14,5,17,'Gandalf Gray',2,0,1069337418,1069337620,1,''),(142,14,6,1,'oe',1,0,1069405553,1069405553,1,''),(143,14,4,2,'New Article',1,0,0,0,0,''),(144,14,1,2,'New article',1,0,1069411408,1069411408,1,''),(145,14,4,2,'Foo bar',1,0,1069411438,1069411438,1,''),(146,14,4,2,'mnb',1,0,1069411461,1069411461,1,''),(147,14,4,2,'fdhjkldfhj',1,0,1069414807,1069414807,1,''),(149,14,4,2,'dfsdfg',1,0,1069417727,1069417727,1,''),(150,14,4,2,'sdifgksdjfgkjgh',1,0,1069417787,1069417787,1,''),(151,14,4,2,'kåre test',1,0,1069417842,1069417842,1,''),(153,14,5,16,'Foo',1,0,1069419202,1069419202,1,''),(154,14,6,12,'jhhhjgjhg',1,0,1069419406,1069419406,1,''),(155,14,4,2,'jkhkjh',1,0,1069419543,1069419543,1,''),(156,14,4,2,'jhkjh',1,0,1069419905,1069419905,1,''),(157,14,4,2,'utuytuy',1,0,1069420019,1069420019,1,''),(158,14,4,2,'jkhjkh',1,0,1069420874,1069420874,1,''),(159,14,6,12,'jkhkjh',1,0,1069420903,1069420903,1,''),(161,14,5,17,'Per Son',1,0,1069421689,1069421689,1,''),(162,14,5,17,'bbmnm mnbnmb',1,0,1069421720,1069421720,1,''),(163,14,4,2,'lkj',1,0,1069422602,1069422602,1,''),(165,14,4,13,'jkhkjh',1,0,1069423490,1069423490,1,''),(166,14,4,13,'kljjkl',1,0,1069423957,1069423957,1,''),(168,14,4,2,'jkljlkj',1,0,1069675837,1069675837,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'Root folder',NULL,NULL,0,0,'','ezstring'),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(95,'eng-GB',1,40,8,'test',0,0,0,0,'','ezstring'),(96,'eng-GB',1,40,9,'test',0,0,0,0,'','ezstring'),(97,'eng-GB',1,40,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',1,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',1,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',1,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',1,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(251,'eng-GB',4,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',4,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',4,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.</paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(155,'eng-GB',1,57,4,'Contact',0,0,0,0,'contact','ezstring'),(156,'eng-GB',1,57,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(157,'eng-GB',1,58,4,'Business news',0,0,0,0,'business news','ezstring'),(158,'eng-GB',1,58,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(159,'eng-GB',1,59,4,'Off topic',0,0,0,0,'off topic','ezstring'),(160,'eng-GB',1,59,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(161,'eng-GB',1,60,4,'Reports ',0,0,0,0,'reports','ezstring'),(162,'eng-GB',1,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(161,'eng-GB',2,60,4,'Reports',0,0,0,0,'reports','ezstring'),(162,'eng-GB',2,60,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(163,'eng-GB',1,61,4,'Staff news',0,0,0,0,'staff news','ezstring'),(164,'eng-GB',1,61,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(165,'eng-GB',1,62,4,'Persons',0,0,0,0,'persons','ezstring'),(166,'eng-GB',1,62,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(167,'eng-GB',1,63,4,'Companies',0,0,0,0,'companies','ezstring'),(168,'eng-GB',1,63,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(169,'eng-GB',1,64,4,'Files',0,0,0,0,'files','ezstring'),(170,'eng-GB',1,64,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(171,'eng-GB',1,65,4,'Products',0,0,0,0,'products','ezstring'),(172,'eng-GB',1,65,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(173,'eng-GB',1,66,4,'Handbooks',0,0,0,0,'handbooks','ezstring'),(174,'eng-GB',1,66,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(175,'eng-GB',1,67,4,'Documents',0,0,0,0,'documents','ezstring'),(176,'eng-GB',1,67,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(177,'eng-GB',1,68,4,'Company routines',0,0,0,0,'company routines','ezstring'),(178,'eng-GB',1,68,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(179,'eng-GB',1,69,4,'Logos',0,0,0,0,'logos','ezstring'),(180,'eng-GB',1,69,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(169,'eng-GB',2,64,4,'Files',0,0,0,0,'files','ezstring'),(170,'eng-GB',2,64,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(467,'eng-GB',4,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(466,'eng-GB',4,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(254,'eng-GB',4,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_systems_reporting_live_from_munich.\"\n         suffix=\"\"\n         basename=\"ez_systems_reporting_live_from_munich\"\n         dirpath=\"var/intranet/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-4-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/ez_systems_reporting_live_from_munich/254-4-eng-GB/ez_systems_reporting_live_from_munich.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(255,'eng-GB',4,92,123,'',0,0,0,0,'','ezboolean'),(461,'eng-GB',2,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',2,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',2,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.jpg\"\n         suffix=\"jpg\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-2-eng-GB/intranet.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069164104\">\n  <original attribute_id=\"463\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-2-eng-GB/intranet_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069164907\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-2-eng-GB/intranet_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069164907\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/136',0,0,0,0,'content/edit/136','ezstring'),(464,'eng-GB',2,136,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(465,'eng-GB',2,136,161,'',0,0,0,0,'','ezstring'),(466,'eng-GB',2,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',2,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(224,'eng-GB',1,82,168,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c></c>\n  <c></c>\n  <c></c>\n  <c></c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(223,'eng-GB',1,82,167,'0',0,0,0,0,'0','ezselection'),(222,'eng-GB',1,82,166,'',0,0,0,0,'','eztext'),(221,'eng-GB',1,82,165,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(218,'eng-GB',1,82,162,'abb',0,0,0,0,'abb','ezstring'),(219,'eng-GB',1,82,163,'',0,0,0,0,'','ezstring'),(220,'eng-GB',1,82,164,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"address_type\">Address type</column>\n    <column num=\"1\"\n            id=\"address_value\">Address value</column>\n  </columns>\n  <rows number=\"0\" />\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(242,'eng-GB',1,89,146,'Online Editor',0,0,0,0,'online editor','ezstring'),(243,'eng-GB',1,89,147,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>The online editor (OE) is an integrated editor for simplified content editing, eliminating the need to use tags. It is only available for Internet Explorer (IE) and is available for both eZ publish 3 and 2.2.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(244,'eng-GB',1,89,148,'',0,0,0,0,'','ezbinaryfile'),(245,'eng-GB',1,90,146,'eZ publish 3.2',0,0,0,0,'ez publish 3.2','ezstring'),(246,'eng-GB',1,90,147,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The latest stable version of eZ publish.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(247,'eng-GB',1,90,148,'',0,0,0,0,'','ezbinaryfile'),(242,'eng-GB',2,89,146,'Online Editor',0,0,0,0,'online editor','ezstring'),(243,'eng-GB',2,89,147,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>The online editor (OE) is an integrated editor for simplified content editing, eliminating the need to use tags. It is only available for Internet Explorer (IE) and is available for both eZ publish 3 and 2.2.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(244,'eng-GB',2,89,148,'',0,0,0,0,'','ezbinaryfile'),(251,'eng-GB',1,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',1,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',1,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small. </paragraph>\n  <paragraph>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',1,92,122,'',0,0,0,0,'','ezimage'),(255,'eng-GB',1,92,123,'',0,0,0,0,'','ezboolean'),(256,'eng-GB',1,93,1,'eZ publish 3.2-2 release',0,0,0,0,'ez publish 3.2-2 release','ezstring'),(257,'eng-GB',1,93,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph> eZ publish 3.2-2 is an upgrade of the stable 3.2 release and it is recommended for all users of eZ publish 3.2. This release fixes some of the problems that were present in the last release. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(258,'eng-GB',1,93,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Release notes for 3.2-2</paragraph>\n  <paragraph>\n    <line>- Improved url alias system with support for wildcards, the updatenicurls scripts were also updated.</line>\n    <line>- Some UI improvements with regards to url alias, cache clearing.</line>\n    <line>- Cc and Bcc support in the SMTP transport.</line>\n    <line>- Fixed problem with the shop regarding the basket and orders.</line>\n    <line>- Fixed bug with sort keys in content object attributes as well as new field for the attribute filter.</line>\n    <line>- New translations for Portuguese and Mozambique.</line>\n    <line>- Updated translations for German, Spanish and Catalan translations.</line>\n    <line>- And general bugfixes and enhancements.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(259,'eng-GB',1,93,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(260,'eng-GB',1,93,123,'',0,0,0,0,'','ezboolean'),(261,'eng-GB',1,94,1,'Mr xxx joined us',0,0,0,0,'mr xxx joined us','ezstring'),(262,'eng-GB',1,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We hired a new employee who is from --</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(263,'eng-GB',1,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>His name is xxx.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(264,'eng-GB',1,94,122,'',0,0,0,0,'','ezimage'),(265,'eng-GB',1,94,123,'',0,0,0,0,'','ezboolean'),(251,'eng-GB',2,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',2,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',2,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.     \n    <object id=\"95\"\n            align=\"right\"\n            size=\"medium\" />\n  </paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things. </line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',2,92,122,'',0,0,0,0,'','ezimage'),(255,'eng-GB',2,92,123,'',0,0,0,0,'','ezboolean'),(169,'eng-GB',3,64,4,'Files',0,0,0,0,'files','ezstring'),(170,'eng-GB',3,64,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you can download all files ...</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(271,'eng-GB',1,97,169,'Wenyue',0,0,0,0,'wenyue','ezstring'),(272,'eng-GB',1,97,170,'Yu',0,0,0,0,'yu','ezstring'),(273,'eng-GB',1,97,171,'Software engineer',0,0,0,0,'software engineer','ezstring'),(274,'eng-GB',1,97,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>Email:</c>\n  <c>wy@ez.no</c>\n  <c>Tele:</c>\n  <c>98802246</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(251,'eng-GB',3,92,1,'eZ systems - reporting live from Munich',0,0,0,0,'ez systems - reporting live from munich','ezstring'),(252,'eng-GB',3,92,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This week, some members of the eZ crew are in Germany, attending &quot;Systems 2003&quot;. Systems 2003 is an international trade fair for Information Technology, Telecommunications and New Media. The trade fair is held for the 22nd time. Four eZ systems representatives are on-site from the 20th to the 24th of October 2003. The following text contains a live report from the fair.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(253,'eng-GB',3,92,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Our first impressions are very positive; exceeding all expectations. We have barely been here a day, and a lot of people have visited us already. It seems that there are many people who already have some knowledge of our main product, eZ publish, which is an open source content management system and development framework. The visitors are mostly from Germany and Austria, many of them are representing various organizations and companies, large and small.     \n    <object id=\"95\"\n            size=\"medium\"\n            align=\"right\" />\n  </paragraph>\n  <paragraph>\n    <line>Despite the enormous success of GNU/Linux, some people are still unfamiliar with open source software and public licenses. However, they sure seem impressed when they realize just how powerful an open source product can be; not to mention the benefits of having a huge and open community with creative minds who are working together to achieve great things.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(254,'eng-GB',3,92,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(255,'eng-GB',3,92,123,'',0,0,0,0,'','ezboolean'),(261,'eng-GB',2,94,1,'Mr xxx joined us',0,0,0,0,'mr xxx joined us','ezstring'),(262,'eng-GB',2,94,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>We hired a new employee who is from --</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(263,'eng-GB',2,94,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>His name is xxx.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(264,'eng-GB',2,94,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(265,'eng-GB',2,94,123,'',0,0,0,0,'','ezboolean'),(288,'eng-GB',1,102,169,'Bjørn',0,0,0,0,'bjørn','ezstring'),(289,'eng-GB',1,102,170,'Reiten',0,0,0,0,'reiten','ezstring'),(290,'eng-GB',1,102,171,'Support manager',0,0,0,0,'support manager','ezstring'),(291,'eng-GB',1,102,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>Email:</c>\n  <c>br@ez.no</c>\n  <c>Tele:</c>\n  <c>66667777</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(292,'eng-GB',1,97,178,'',0,0,0,0,'','ezimage'),(293,'eng-GB',1,102,178,'',0,0,0,0,'','ezimage'),(271,'eng-GB',2,97,169,'Wenyue',0,0,0,0,'wenyue','ezstring'),(272,'eng-GB',2,97,170,'Yu',0,0,0,0,'yu','ezstring'),(273,'eng-GB',2,97,171,'Software engineer',0,0,0,0,'software engineer','ezstring'),(274,'eng-GB',2,97,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>Email:</c>\n  <c>wy@ez.no</c>\n  <c>Tele:</c>\n  <c>98802246</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(292,'eng-GB',2,97,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"phpCKfj8I.png\"\n         suffix=\"png\"\n         basename=\"phpCKfj8I\"\n         dirpath=\"var/intranet/storage/original/image\"\n         url=\"var/intranet/storage/original/image/phpCKfj8I.png\"\n         original_filename=\"phpCG9Rrg_600x600_97870.png\"\n         mime_type=\"image/png\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(288,'eng-GB',2,102,169,'Bjørn',0,0,0,0,'bjørn','ezstring'),(289,'eng-GB',2,102,170,'Reiten',0,0,0,0,'reiten','ezstring'),(290,'eng-GB',2,102,171,'Support manager',0,0,0,0,'support manager','ezstring'),(291,'eng-GB',2,102,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>Email:</c>\n  <c>br@ez.no</c>\n  <c>Tele:</c>\n  <c>66667777</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(293,'eng-GB',2,102,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"php2e1GsG.png\"\n         suffix=\"png\"\n         basename=\"php2e1GsG\"\n         dirpath=\"var/intranet/storage/original/image\"\n         url=\"var/intranet/storage/original/image/php2e1GsG.png\"\n         original_filename=\"bj.png\"\n         mime_type=\"image/png\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(302,'eng-GB',1,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',1,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',1,107,12,'',0,0,0,0,'','ezuser'),(302,'eng-GB',2,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',2,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',2,107,12,'',0,0,0,0,'','ezuser'),(288,'eng-GB',3,102,169,'Bjørn',0,0,0,0,'bjørn','ezstring'),(289,'eng-GB',3,102,170,'Reiten',0,0,0,0,'reiten','ezstring'),(290,'eng-GB',3,102,171,'Support manager',0,0,0,0,'support manager','ezstring'),(291,'eng-GB',3,102,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>Email:</c>\n  <c>br@ez.no</c>\n  <c>Tele:</c>\n  <c>66667777</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(293,'eng-GB',3,102,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"bjrn.png\"\n         suffix=\"png\"\n         basename=\"bjrn\"\n         dirpath=\"var/intranet/storage/images/contact/persons/bjrn/293-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/bjrn/293-3-eng-GB/bjrn.png\"\n         original_filename=\"bj.png\"\n         mime_type=\"image/png\"\n         width=\"186\"\n         height=\"93\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"bjrn_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/contact/persons/bjrn/293-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/bjrn/293-3-eng-GB/bjrn_reference.png\"\n         mime_type=\"image/png\"\n         width=\"186\"\n         height=\"93\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"bjrn_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/contact/persons/bjrn/293-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/bjrn/293-3-eng-GB/bjrn_medium.png\"\n         mime_type=\"image/png\"\n         width=\"186\"\n         height=\"93\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(315,'eng-GB',1,111,12,'',0,0,0,0,'','ezuser'),(313,'eng-GB',1,111,8,'vid',0,0,0,0,'vid','ezstring'),(314,'eng-GB',1,111,9,'la',0,0,0,0,'la','ezstring'),(271,'eng-GB',3,97,169,'Wenyue',0,0,0,0,'wenyue','ezstring'),(272,'eng-GB',3,97,170,'Yu',0,0,0,0,'yu','ezstring'),(273,'eng-GB',3,97,171,'Software engineer',0,0,0,0,'software engineer','ezstring'),(274,'eng-GB',3,97,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>Email:</c>\n  <c>wy@ez.no</c>\n  <c>Tele:</c>\n  <c>98802246</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(292,'eng-GB',3,97,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue.png\"\n         suffix=\"png\"\n         basename=\"wenyue\"\n         dirpath=\"var/intranet/storage/images/contact/persons/wenyue/292-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/wenyue/292-3-eng-GB/wenyue.png\"\n         original_filename=\"phpCG9Rrg_600x600_97870.png\"\n         mime_type=\"original\"\n         width=\"186\"\n         height=\"93\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/contact/persons/wenyue/292-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/wenyue/292-3-eng-GB/wenyue_reference.png\"\n         mime_type=\"image/png\"\n         width=\"186\"\n         height=\"93\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"wenyue_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/contact/persons/wenyue/292-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/wenyue/292-3-eng-GB/wenyue_medium.png\"\n         mime_type=\"image/png\"\n         width=\"186\"\n         height=\"93\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(316,'eng-GB',1,112,4,'Information',0,0,0,0,'information','ezstring'),(317,'eng-GB',1,112,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>General information about the company.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(318,'eng-GB',1,113,140,'Routines',0,0,0,0,'routines','ezstring'),(319,'eng-GB',1,113,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Cras egestas nisl non turpis. Cras sed leo ut dui iaculis pharetra. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Donec felis nulla, aliquet et, aliquam sed, ultricies et, urna. Vivamus risus. Fusce pede. Sed ornare, lectus sed aliquam auctor, erat purus elementum magna, vel luctus augue turpis quis massa. Nullam egestas diam at mi. </paragraph>\n  <paragraph>Vestibulum viverra tristique velit. Vivamus vitae quam. Mauris nibh. Phasellus nec metus. Integer tristique magna eu sem. Praesent rutrum ullamcorper ligula. Fusce et est. Integer at orci. Aliquam lectus ligula, commodo a, rhoncus et, semper eget, magna. Sed vel augue. Pellentesque at est ac massa gravida vehicula. Suspendisse potenti. Aenean ut diam. Nulla purus quam, sodales id, adipiscing eu, dignissim quis, libero. In eu erat. </paragraph>\n  <paragraph>Maecenas vel lorem a nisl auctor semper. Vivamus arcu elit, ultricies in, congue at, commodo at, nulla. Aliquam in nibh. Etiam sapien lectus, mollis vitae, malesuada vel, fermentum vitae, massa. In hac habitasse platea dictumst. Phasellus quis neque ut orci auctor posuere. Donec ac nisl vel nunc porttitor venenatis. Morbi eget enim. Phasellus commodo, neque at sagittis scelerisque, erat nulla elementum orci, vitae consectetuer risus magna sit amet lectus. Curabitur laoreet wisi sed neque. Vestibulum lobortis magna nec nisl. Praesent non enim. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(320,'eng-GB',1,113,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"routines.\"\n         suffix=\"\"\n         basename=\"routines\"\n         dirpath=\"var/intranet/storage/images/information/routines/320-1-eng-GB\"\n         url=\"var/intranet/storage/images/information/routines/320-1-eng-GB/routines.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(418,'eng-GB',1,132,171,'',0,0,0,0,'','ezstring'),(419,'eng-GB',1,132,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>214512345</c>\n  <c>2</c>\n  <c>tg@ez.no</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(417,'eng-GB',1,132,169,'',0,0,0,0,'','ezstring'),(420,'eng-GB',1,132,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"terje.\"\n         suffix=\"\"\n         basename=\"terje\"\n         dirpath=\"var/intranet/storage/images/contact/terje_/420-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/terje_/420-1-eng-GB/terje.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(421,'eng-GB',1,132,179,'',1045487555,0,0,0,'','ezxmltext'),(462,'eng-GB',11,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',11,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet.gif\"\n         original_filename=\"intranet.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069685477\">\n  <original attribute_id=\"463\"\n            attribute_version=\"10\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(416,'eng-GB',1,132,170,'Terje',0,0,0,0,'terje','ezstring'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(272,'eng-GB',4,97,170,'Yu',0,0,0,0,'yu','ezstring'),(271,'eng-GB',4,97,169,'Wenyue',0,0,0,0,'wenyue','ezstring'),(273,'eng-GB',4,97,171,'Software engineer',0,0,0,0,'software engineer','ezstring'),(274,'eng-GB',4,97,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>Email:</c>\n  <c>wy@ez.no</c>\n  <c>Tele:</c>\n  <c>98802246</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(292,'eng-GB',4,97,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"yu_wenyue.png\"\n         suffix=\"png\"\n         basename=\"yu_wenyue\"\n         dirpath=\"var/intranet/storage/images/contact/persons/yu_wenyue/292-4-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/yu_wenyue/292-4-eng-GB/yu_wenyue.png\"\n         original_filename=\"phpCG9Rrg_600x600_97870.png\"\n         mime_type=\"original\"\n         width=\"186\"\n         height=\"93\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"292\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(289,'eng-GB',4,102,170,'Reiten',0,0,0,0,'reiten','ezstring'),(288,'eng-GB',4,102,169,'Bjørn',0,0,0,0,'bjørn','ezstring'),(290,'eng-GB',4,102,171,'Support manager',0,0,0,0,'support manager','ezstring'),(291,'eng-GB',4,102,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>Email:</c>\n  <c>br@ez.no</c>\n  <c>Tele:</c>\n  <c>66667777</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(293,'eng-GB',4,102,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"reiten_bjrn.png\"\n         suffix=\"png\"\n         basename=\"reiten_bjrn\"\n         dirpath=\"var/intranet/storage/images/contact/persons/reiten_bjrn/293-4-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/reiten_bjrn/293-4-eng-GB/reiten_bjrn.png\"\n         original_filename=\"bj.png\"\n         mime_type=\"image/png\"\n         width=\"186\"\n         height=\"93\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"293\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(337,'eng-GB',1,97,179,'',1045487555,0,0,0,'','ezxmltext'),(338,'eng-GB',2,97,179,'',1045487555,0,0,0,'','ezxmltext'),(339,'eng-GB',3,97,179,'',1045487555,0,0,0,'','ezxmltext'),(340,'eng-GB',4,97,179,'',1045487555,0,0,0,'','ezxmltext'),(341,'eng-GB',1,102,179,'',1045487555,0,0,0,'','ezxmltext'),(342,'eng-GB',2,102,179,'',1045487555,0,0,0,'','ezxmltext'),(343,'eng-GB',3,102,179,'',1045487555,0,0,0,'','ezxmltext'),(344,'eng-GB',4,102,179,'',1045487555,0,0,0,'','ezxmltext'),(499,'eng-GB',1,144,1,'New article',0,0,0,0,'new article','ezstring'),(500,'eng-GB',1,144,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>kjhjkhj</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(501,'eng-GB',1,144,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>lkjklj</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(502,'eng-GB',1,144,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"new_article.\"\n         suffix=\"\"\n         basename=\"new_article\"\n         dirpath=\"var/intranet/storage/images/new_article/502-1-eng-GB\"\n         url=\"var/intranet/storage/images/new_article/502-1-eng-GB/new_article.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069411259\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(503,'eng-GB',1,144,123,'',0,0,0,0,'','ezboolean'),(505,'eng-GB',1,145,1,'Foo bar',0,0,0,0,'foo bar','ezstring'),(506,'eng-GB',1,145,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sdfg</line>\n    <line>sdfg</line>\n    <line>sdfg</line>\n    <line>sd</line>\n    <line>fg</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(507,'eng-GB',1,145,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>gh</line>\n    <line>dfghg</line>\n  </paragraph>\n  <paragraph>\n    <line>dfhgh</line>\n    <line>hdfgh</line>\n    <line>dfgh</line>\n    <line>fd</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(508,'eng-GB',1,145,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"foo_bar.\"\n         suffix=\"\"\n         basename=\"foo_bar\"\n         dirpath=\"var/intranet/storage/images/news/off_topic/foo_bar/508-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/off_topic/foo_bar/508-1-eng-GB/foo_bar.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069411422\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(256,'eng-GB',2,93,1,'eZ publish 3.2-2 release',0,0,0,0,'ez publish 3.2-2 release','ezstring'),(257,'eng-GB',2,93,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>eZ publish 3.2-2 is an upgrade of the stable 3.2 release and it is recommended for all users of eZ publish 3.2. This release fixes some of the problems that were present in the last release.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(258,'eng-GB',2,93,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Release notes for 3.2-2</paragraph>\n  <paragraph>\n    <line>- Improved url alias system with support for wildcards, the updatenicurls scripts were also updated.</line>\n    <line>- Some UI improvements with regards to url alias, cache clearing.</line>\n    <line>- Cc and Bcc support in the SMTP transport.</line>\n    <line>- Fixed problem with the shop regarding the basket and orders.</line>\n    <line>- Fixed bug with sort keys in content object attributes as well as new field for the attribute filter.</line>\n    <line>- New translations for Portuguese and Mozambique.</line>\n    <line>- Updated translations for German, Spanish and Catalan translations.</line>\n    <line>- And general bugfixes and enhancements.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(259,'eng-GB',2,93,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_publish_322_release.\"\n         suffix=\"\"\n         basename=\"ez_publish_322_release\"\n         dirpath=\"var/intranet/storage/images/news/business_news/ez_publish_322_release/259-2-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/ez_publish_322_release/259-2-eng-GB/ez_publish_322_release.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(260,'eng-GB',2,93,123,'',0,0,0,0,'','ezboolean'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(483,'eng-GB',1,140,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"gandalf_gray.\"\n         suffix=\"\"\n         basename=\"gandalf_gray\"\n         dirpath=\"var/intranet/storage/images/contact/gandalf_gray/483-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/gandalf_gray/483-1-eng-GB/gandalf_gray.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069337392\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(484,'eng-GB',1,140,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>jkhdfgdf</line>\n    <line>gh</line>\n    <line>dfgh</line>\n    <line>gh</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(464,'eng-GB',8,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',8,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',8,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',8,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(477,'eng-GB',2,139,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"bilbo_baggins.\"\n         suffix=\"\"\n         basename=\"bilbo_baggins\"\n         dirpath=\"var/intranet/storage/images/contact/bilbo_baggins/477-2-eng-GB\"\n         url=\"var/intranet/storage/images/contact/bilbo_baggins/477-2-eng-GB/bilbo_baggins.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069336283\">\n  <original attribute_id=\"477\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(527,'eng-GB',5,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(528,'eng-GB',6,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(529,'eng-GB',7,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(530,'eng-GB',8,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(461,'eng-GB',9,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',9,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(416,'eng-GB',2,132,170,'Terje',0,0,0,0,'terje','ezstring'),(417,'eng-GB',2,132,169,'Kaste',0,0,0,0,'kaste','ezstring'),(418,'eng-GB',2,132,171,'Butikksjef',0,0,0,0,'butikksjef','ezstring'),(419,'eng-GB',2,132,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>214512345</c>\n  <c>2</c>\n  <c>tg@ez.no</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(420,'eng-GB',2,132,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"terje_kaste.\"\n         suffix=\"\"\n         basename=\"terje_kaste\"\n         dirpath=\"var/intranet/storage/images/contact/terje_kaste/420-2-eng-GB\"\n         url=\"var/intranet/storage/images/contact/terje_kaste/420-2-eng-GB/terje_kaste.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"420\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(421,'eng-GB',2,132,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(473,'eng-GB',1,139,170,'Bilbo',0,0,0,0,'bilbo','ezstring'),(474,'eng-GB',1,139,169,'Baggins',0,0,0,0,'baggins','ezstring'),(475,'eng-GB',1,139,171,'boss',0,0,0,0,'boss','ezstring'),(476,'eng-GB',1,139,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"1\" />\n  <c>0</c>\n  <c>9934</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(477,'eng-GB',1,139,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"bilbo_baggins.\"\n         suffix=\"\"\n         basename=\"bilbo_baggins\"\n         dirpath=\"var/intranet/storage/images/contact/bilbo_baggins/477-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/bilbo_baggins/477-1-eng-GB/bilbo_baggins.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069336283\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(478,'eng-GB',1,139,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfjghkjdfgdfdfgh</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(473,'eng-GB',2,139,170,'Bilbo',0,0,0,0,'bilbo','ezstring'),(474,'eng-GB',2,139,169,'Baggins',0,0,0,0,'baggins','ezstring'),(475,'eng-GB',2,139,171,'boss',0,0,0,0,'boss','ezstring'),(476,'eng-GB',2,139,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"1\" />\n  <c>0</c>\n  <c>9934</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(348,'eng-GB',1,119,162,'eZ sys',0,0,0,0,'ez sys','ezstring'),(349,'eng-GB',1,119,163,'',0,0,0,0,'','ezstring'),(350,'eng-GB',1,119,164,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"address_type\">Address type</column>\n    <column num=\"1\"\n            id=\"address_value\">Address value</column>\n  </columns>\n  <rows number=\"3\" />\n  <c>0</c>\n  <c>kvern</c>\n  <c>1</c>\n  <c>telemar</c>\n  <c>2</c>\n  <c>1234</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(351,'eng-GB',1,119,165,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(352,'eng-GB',1,119,166,'asdf',0,0,0,0,'','eztext'),(353,'eng-GB',1,119,167,'0',0,0,0,0,'0','ezselection'),(354,'eng-GB',1,119,168,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>123</c>\n  <c>1</c>\n  <c>123</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(355,'eng-GB',1,120,162,'foo bar corp',0,0,0,0,'foo bar corp','ezstring'),(356,'eng-GB',1,120,163,'123',0,0,0,0,'123','ezstring'),(357,'eng-GB',1,120,164,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"address_type\">Address type</column>\n    <column num=\"1\"\n            id=\"address_value\">Address value</column>\n  </columns>\n  <rows number=\"3\" />\n  <c>0</c>\n  <c>foo</c>\n  <c>1</c>\n  <c>bar</c>\n  <c>2</c>\n  <c>123</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(358,'eng-GB',1,120,165,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069336436\" />',0,0,0,0,'','ezimage'),(359,'eng-GB',1,120,166,'foo bar corp',0,0,0,0,'','eztext'),(360,'eng-GB',1,120,167,'0',0,0,0,0,'0','ezselection'),(361,'eng-GB',1,120,168,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c></c>\n  <c>1</c>\n  <c></c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(362,'eng-GB',1,121,170,'Vidar',0,0,0,0,'vidar','ezstring'),(363,'eng-GB',1,121,169,'Langseid',0,0,0,0,'langseid','ezstring'),(364,'eng-GB',1,121,171,'',0,0,0,0,'','ezstring'),(365,'eng-GB',1,121,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"1\" />\n  <c>0</c>\n  <c>123</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(366,'eng-GB',1,121,178,'',0,0,0,0,'','ezimage'),(367,'eng-GB',1,121,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>adf</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(371,'eng-GB',1,123,170,'Bård',0,0,0,0,'bård','ezstring'),(372,'eng-GB',1,123,169,'Farstad',0,0,0,0,'farstad','ezstring'),(373,'eng-GB',1,123,171,'asd',0,0,0,0,'asd','ezstring'),(374,'eng-GB',1,123,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"0\" />\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(375,'eng-GB',1,123,178,'',0,0,0,0,'','ezimage'),(376,'eng-GB',1,123,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>asdfasdfasdf</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(377,'eng-GB',1,124,170,'fido',0,0,0,0,'fido','ezstring'),(378,'eng-GB',1,124,169,'barida',0,0,0,0,'barida','ezstring'),(379,'eng-GB',1,124,171,'asdf',0,0,0,0,'asdf','ezstring'),(380,'eng-GB',1,124,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"0\" />\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(381,'eng-GB',1,124,178,'',0,0,0,0,'','ezimage'),(382,'eng-GB',1,124,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>fasdf</line>\n    <line>asdfasdfawf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(497,'eng-GB',1,143,123,'',0,0,0,0,'','ezboolean'),(496,'eng-GB',1,143,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069409692\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(491,'eng-GB',1,142,4,'oe',0,0,0,0,'oe','ezstring'),(492,'eng-GB',1,142,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(493,'eng-GB',1,143,1,'fp',0,0,0,0,'fp','ezstring'),(494,'eng-GB',1,143,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfsg</line>\n    <line>sdgf</line>\n    <line>sdfg</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(495,'eng-GB',1,143,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>fgh</line>\n    <line>dfhf</line>\n    <line>dg</line>\n    <line>h</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(509,'eng-GB',1,145,123,'',0,0,0,0,'','ezboolean'),(478,'eng-GB',3,139,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfjghkjdfgdfdfgh sdfg sdgf dsfg</line>\n    <line>dsgf</line>\n    <line>sdfg</line>\n    <line>dsfg</line>\n    <line>sd</line>\n    <line>fgsdfg dsfg</line>\n    <line>sdg</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(461,'eng-GB',3,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',3,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',3,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.jpg\"\n         suffix=\"jpg\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-3-eng-GB/intranet.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069164104\">\n  <original attribute_id=\"463\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-3-eng-GB/intranet_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069164907\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-3-eng-GB/intranet_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069164907\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(465,'eng-GB',4,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(464,'eng-GB',4,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(461,'eng-GB',4,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',4,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',4,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.jpg\"\n         suffix=\"jpg\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-4-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-4-eng-GB/intranet.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069164104\">\n  <original attribute_id=\"463\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-4-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-4-eng-GB/intranet_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069164907\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-4-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-4-eng-GB/intranet_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069164907\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(467,'eng-GB',3,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(464,'eng-GB',3,136,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(465,'eng-GB',3,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',3,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(463,'eng-GB',9,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069168827\">\n  <original attribute_id=\"463\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069168829\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069168829\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069680740\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(526,'eng-GB',4,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(461,'eng-GB',11,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(524,'eng-GB',2,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(525,'eng-GB',3,136,182,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(461,'eng-GB',5,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',5,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',5,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.png\"\n         suffix=\"png\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-5-eng-GB/intranet.png\"\n         original_filename=\"phphWMyJs.png\"\n         mime_type=\"original\"\n         width=\"144\"\n         height=\"134\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069167783\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-5-eng-GB/intranet_reference.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-5-eng-GB/intranet_medium.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',5,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',5,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',5,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',5,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',6,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',6,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',6,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.png\"\n         suffix=\"png\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB/intranet.png\"\n         original_filename=\"phphWMyJs.png\"\n         mime_type=\"original\"\n         width=\"144\"\n         height=\"134\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069167783\">\n  <original attribute_id=\"463\"\n            attribute_version=\"5\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB/intranet_reference.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-6-eng-GB/intranet_medium.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',6,136,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(465,'eng-GB',6,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',6,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',6,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(461,'eng-GB',7,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',7,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',7,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.png\"\n         suffix=\"png\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB/intranet.png\"\n         original_filename=\"phphWMyJs.png\"\n         mime_type=\"original\"\n         width=\"144\"\n         height=\"134\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069167783\">\n  <original attribute_id=\"463\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB/intranet_reference.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB/intranet_medium.png\"\n         mime_type=\"image/png\"\n         width=\"144\"\n         height=\"134\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069167785\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-7-eng-GB/intranet_logo.png\"\n         mime_type=\"image/png\"\n         width=\"62\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069168662\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',7,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',7,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',7,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',7,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(479,'eng-GB',2,140,170,'Gandalf',0,0,0,0,'gandalf','ezstring'),(480,'eng-GB',2,140,169,'Gray',0,0,0,0,'gray','ezstring'),(481,'eng-GB',2,140,171,'Master',0,0,0,0,'master','ezstring'),(482,'eng-GB',2,140,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>3458</c>\n  <c>1</c>\n  <c></c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(483,'eng-GB',2,140,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"gandalf_gray.\"\n         suffix=\"\"\n         basename=\"gandalf_gray\"\n         dirpath=\"var/intranet/storage/images/contact/gandalf_gray/483-2-eng-GB\"\n         url=\"var/intranet/storage/images/contact/gandalf_gray/483-2-eng-GB/gandalf_gray.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069337392\">\n  <original attribute_id=\"483\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(484,'eng-GB',2,140,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>jkhdfgdf</line>\n    <line>gh</line>\n    <line>dfgh</line>\n    <line>gh</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(473,'eng-GB',3,139,170,'Bilbo',0,0,0,0,'bilbo','ezstring'),(474,'eng-GB',3,139,169,'Baggins',0,0,0,0,'baggins','ezstring'),(475,'eng-GB',3,139,171,'boss',0,0,0,0,'boss','ezstring'),(476,'eng-GB',3,139,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"1\" />\n  <c>0</c>\n  <c>9934</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(477,'eng-GB',3,139,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"bilbo_baggins.jpg\"\n         suffix=\"jpg\"\n         basename=\"bilbo_baggins\"\n         dirpath=\"var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB/bilbo_baggins.jpg\"\n         original_filename=\"cat.jpg\"\n         mime_type=\"original\"\n         width=\"400\"\n         height=\"300\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069339386\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"bilbo_baggins_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB/bilbo_baggins_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         timestamp=\"1069339390\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"bilbo_baggins_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB\"\n         url=\"var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB/bilbo_baggins_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         timestamp=\"1069339391\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069162834\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069162835\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069162835\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069162901\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"url_translator.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069162886\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069162886\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069162886\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069162901\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(478,'eng-GB',2,139,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfjghkjdfgdfdfgh</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(479,'eng-GB',1,140,170,'Gandalf',0,0,0,0,'gandalf','ezstring'),(480,'eng-GB',1,140,169,'Gray',0,0,0,0,'gray','ezstring'),(481,'eng-GB',1,140,171,'Master',0,0,0,0,'master','ezstring'),(482,'eng-GB',1,140,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"1\" />\n  <c>0</c>\n  <c>3458</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(461,'eng-GB',8,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',8,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',8,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB/intranet.gif\"\n         original_filename=\"ezlogo.gif\"\n         mime_type=\"original\"\n         width=\"210\"\n         height=\"35\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069168827\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069168829\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"33\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069168829\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-8-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"210\"\n         height=\"35\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069168850\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(511,'eng-GB',1,146,1,'mnb',0,0,0,0,'mnb','ezstring'),(512,'eng-GB',1,146,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>jkh</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(513,'eng-GB',1,146,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kjh</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(514,'eng-GB',1,146,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"mnb.\"\n         suffix=\"\"\n         basename=\"mnb\"\n         dirpath=\"var/intranet/storage/images/news/business_news/mnb/514-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/mnb/514-1-eng-GB/mnb.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069411454\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(515,'eng-GB',1,146,123,'',0,0,0,0,'','ezboolean'),(517,'eng-GB',1,147,1,'fdhjkldfhj',0,0,0,0,'fdhjkldfhj','ezstring'),(518,'eng-GB',1,147,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>jkhhjlk hjcvxbdfgh</line>\n    <line>dfh</line>\n    <line>df</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(519,'eng-GB',1,147,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>jkhhjlk hjcvxbdfgh</line>\n    <line>dfh</line>\n    <line>df</line>\n  </paragraph>\n  <paragraph>\n    <line>jkhhjlk hjcvxbdfgh</line>\n    <line>dfh</line>\n    <line>df</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(520,'eng-GB',1,147,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"fdhjkldfhj.\"\n         suffix=\"\"\n         basename=\"fdhjkldfhj\"\n         dirpath=\"var/intranet/storage/images/news/business_news/fdhjkldfhj/520-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/fdhjkldfhj/520-1-eng-GB/fdhjkldfhj.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069414791\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(521,'eng-GB',1,147,123,'',0,0,0,0,'','ezboolean'),(256,'eng-GB',3,93,1,'eZ publish 3.2-2 release',0,0,0,0,'ez publish 3.2-2 release','ezstring'),(257,'eng-GB',3,93,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>eZ publish 3.2-2 is an upgrade of the stable 3.2 release and it is recommended for all users of eZ publish 3.2. This release fixes some of the problems that were present in the last release.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(258,'eng-GB',3,93,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Release notes for 3.2-2</paragraph>\n  <paragraph>\n    <line>- Improved url alias system with support for wildcards, the updatenicurls scripts were also updated.</line>\n    <line>- Some UI improvements with regards to url alias, cache clearing.</line>\n    <line>- Cc and Bcc support in the SMTP transport.</line>\n    <line>- Fixed problem with the shop regarding the basket and orders.</line>\n    <line>- Fixed bug with sort keys in content object attributes as well as new field for the attribute filter.</line>\n    <line>- New translations for Portuguese and Mozambique.</line>\n    <line>- Updated translations for German, Spanish and Catalan translations.</line>\n    <line>- And general bugfixes and enhancements.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(259,'eng-GB',3,93,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"ez_publish_322_release.\"\n         suffix=\"\"\n         basename=\"ez_publish_322_release\"\n         dirpath=\"var/intranet/storage/images/news/business_news/ez_publish_322_release/259-3-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/ez_publish_322_release/259-3-eng-GB/ez_publish_322_release.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"259\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(260,'eng-GB',3,93,123,'',0,0,0,0,'','ezboolean'),(1,'eng-GB',2,1,4,'Intranet',0,0,0,0,'intranet','ezstring'),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our intranet</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(464,'eng-GB',9,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',9,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',9,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',9,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',9,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(531,'eng-GB',1,148,170,'jh',0,0,0,0,'jh','ezstring'),(532,'eng-GB',1,148,169,'jh',0,0,0,0,'jh','ezstring'),(533,'eng-GB',1,148,171,'foo',0,0,0,0,'foo','ezstring'),(534,'eng-GB',1,148,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>99345</c>\n  <c>1</c>\n  <c>34588</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(535,'eng-GB',1,148,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"jh_jh.\"\n         suffix=\"\"\n         basename=\"jh_jh\"\n         dirpath=\"var/intranet/storage/images/contact/jh_jh/535-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/jh_jh/535-1-eng-GB/jh_jh.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069417285\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(536,'eng-GB',1,148,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>kjhsdfgjkshdgjk sdkfjgh sdjkfgh dskjgf sdkjh sdgf</line>\n    <line>sd</line>\n    <line>gfsd</line>\n    <line>gf</line>\n    <line>sd</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(531,'eng-GB',2,148,170,'jh',0,0,0,0,'jh','ezstring'),(532,'eng-GB',2,148,169,'jh',0,0,0,0,'jh','ezstring'),(533,'eng-GB',2,148,171,'foo',0,0,0,0,'foo','ezstring'),(534,'eng-GB',2,148,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>99345</c>\n  <c>1</c>\n  <c>34588</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(535,'eng-GB',2,148,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"jh_jh.\"\n         suffix=\"\"\n         basename=\"jh_jh\"\n         dirpath=\"var/intranet/storage/images/contact/jh_jh/535-2-eng-GB\"\n         url=\"var/intranet/storage/images/contact/jh_jh/535-2-eng-GB/jh_jh.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069417285\">\n  <original attribute_id=\"535\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(536,'eng-GB',2,148,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>kjhsdfgjkshdgjk sdkfjgh sdjkfgh dskjgf sdkjh sdgf</line>\n    <line>sd</line>\n    <line>gfsd</line>\n    <line>gf</line>\n    <line>sd</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(537,'eng-GB',1,149,1,'dfsdfg',0,0,0,0,'dfsdfg','ezstring'),(538,'eng-GB',1,149,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>dfsgsdfgsdfg</line>\n    <line>dsgf</line>\n    <line>sd</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(539,'eng-GB',1,149,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(540,'eng-GB',1,149,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"dfsdfg.\"\n         suffix=\"\"\n         basename=\"dfsdfg\"\n         dirpath=\"var/intranet/storage/images/news/business_news/dfsdfg/540-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/business_news/dfsdfg/540-1-eng-GB/dfsdfg.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069417718\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(541,'eng-GB',1,149,123,'',0,0,0,0,'','ezboolean'),(542,'eng-GB',1,150,1,'sdifgksdjfgkjgh',0,0,0,0,'sdifgksdjfgkjgh','ezstring'),(543,'eng-GB',1,150,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>kjhjkh kjh</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(544,'eng-GB',1,150,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>kjhjkhghdf</line>\n    <line>dfh</line>\n    <line>dfgh</line>\n    <line>fdg</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(545,'eng-GB',1,150,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"sdifgksdjfgkjgh.\"\n         suffix=\"\"\n         basename=\"sdifgksdjfgkjgh\"\n         dirpath=\"var/intranet/storage/images/news/off_topic/sdifgksdjfgkjgh/545-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/off_topic/sdifgksdjfgkjgh/545-1-eng-GB/sdifgksdjfgkjgh.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069417747\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(546,'eng-GB',1,150,123,'',0,0,0,0,'','ezboolean'),(547,'eng-GB',1,151,1,'kåre test',0,0,0,0,'kåre test','ezstring'),(548,'eng-GB',1,151,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>jhghj gj hjg kgjkgjkg </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(549,'eng-GB',1,151,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kjg jkg jk</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(550,'eng-GB',1,151,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"kre_test.\"\n         suffix=\"\"\n         basename=\"kre_test\"\n         dirpath=\"var/intranet/storage/images/news/off_topic/kre_test/550-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/off_topic/kre_test/550-1-eng-GB/kre_test.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069417822\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(551,'eng-GB',1,151,123,'',0,0,0,0,'','ezboolean'),(557,'eng-GB',1,153,164,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"address_type\">Address type</column>\n    <column num=\"1\"\n            id=\"address_value\">Address value</column>\n  </columns>\n  <rows number=\"3\" />\n  <c>0</c>\n  <c></c>\n  <c>1</c>\n  <c></c>\n  <c>2</c>\n  <c></c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(556,'eng-GB',1,153,163,'dfg',0,0,0,0,'dfg','ezstring'),(555,'eng-GB',1,153,162,'Foo',0,0,0,0,'foo','ezstring'),(558,'eng-GB',1,153,165,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"foo.\"\n         suffix=\"\"\n         basename=\"foo\"\n         dirpath=\"var/intranet/storage/images/contact/companies/foo/558-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/companies/foo/558-1-eng-GB/foo.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069418733\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(559,'eng-GB',1,153,166,'fdhdfgh\ndf\ngh\ndfgh\ndfh',0,0,0,0,'','eztext'),(560,'eng-GB',1,153,167,'0',0,0,0,0,'0','ezselection'),(561,'eng-GB',1,153,168,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"2\" />\n  <c>0</c>\n  <c>876</c>\n  <c>1</c>\n  <c>876786</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(562,'eng-GB',1,154,146,'jhhhjgjhg',0,0,0,0,'jhhhjgjhg','ezstring'),(563,'eng-GB',1,154,147,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>jhg jhgds</line>\n    <line>sd</line>\n    <line>g</line>\n    <line>sdgf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(564,'eng-GB',1,154,148,'',0,0,0,0,'','ezbinaryfile'),(565,'eng-GB',1,155,1,'jkhkjh',0,0,0,0,'jkhkjh','ezstring'),(566,'eng-GB',1,155,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kjhjkh</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(567,'eng-GB',1,155,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kjhkjh</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(568,'eng-GB',1,155,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"jkhkjh.\"\n         suffix=\"\"\n         basename=\"jkhkjh\"\n         dirpath=\"var/intranet/storage/images/news/jkhkjh/568-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/jkhkjh/568-1-eng-GB/jkhkjh.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069419532\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(569,'eng-GB',1,155,123,'',0,0,0,0,'','ezboolean'),(570,'eng-GB',1,156,1,'jhkjh',0,0,0,0,'jhkjh','ezstring'),(571,'eng-GB',1,156,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kjhkjhkj</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(572,'eng-GB',1,156,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kjhjkh</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(573,'eng-GB',1,156,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"jhkjh.\"\n         suffix=\"\"\n         basename=\"jhkjh\"\n         dirpath=\"var/intranet/storage/images/news/jhkjh/573-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/jhkjh/573-1-eng-GB/jhkjh.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069419891\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(574,'eng-GB',1,156,123,'',0,0,0,0,'','ezboolean'),(575,'eng-GB',1,157,1,'utuytuy',0,0,0,0,'utuytuy','ezstring'),(576,'eng-GB',1,157,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>gfhjfghdf</line>\n    <line>gh</line>\n    <line>gh</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(577,'eng-GB',1,157,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(578,'eng-GB',1,157,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"utuytuy.\"\n         suffix=\"\"\n         basename=\"utuytuy\"\n         dirpath=\"var/intranet/storage/images/news/utuytuy/578-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/utuytuy/578-1-eng-GB/utuytuy.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069420005\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(579,'eng-GB',1,157,123,'',0,0,0,0,'','ezboolean'),(580,'eng-GB',1,158,1,'jkhjkh',0,0,0,0,'jkhjkh','ezstring'),(581,'eng-GB',1,158,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kjhjkhjk</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(582,'eng-GB',1,158,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>hjkhjkh</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(583,'eng-GB',1,158,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"jkhjkh.\"\n         suffix=\"\"\n         basename=\"jkhjkh\"\n         dirpath=\"var/intranet/storage/images/news/jkhjkh/583-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/jkhjkh/583-1-eng-GB/jkhjkh.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069420868\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(584,'eng-GB',1,158,123,'',0,0,0,0,'','ezboolean'),(585,'eng-GB',1,159,146,'jkhkjh',0,0,0,0,'jkhkjh','ezstring'),(586,'eng-GB',1,159,147,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kjhkjh</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(587,'eng-GB',1,159,148,'',0,0,0,0,'','ezbinaryfile'),(598,'eng-GB',1,161,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"per_son.\"\n         suffix=\"\"\n         basename=\"per_son\"\n         dirpath=\"var/intranet/storage/images/contact/per_son/598-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/per_son/598-1-eng-GB/per_son.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069421673\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(599,'eng-GB',1,161,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>098ds</line>\n    <line>sdfghfd</line>\n    <line>ghdfgh</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(594,'eng-GB',1,161,170,'Per',0,0,0,0,'per','ezstring'),(595,'eng-GB',1,161,169,'Son',0,0,0,0,'son','ezstring'),(596,'eng-GB',1,161,171,'SJef',0,0,0,0,'sjef','ezstring'),(597,'eng-GB',1,161,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"1\" />\n  <c>0</c>\n  <c>8908</c>\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(600,'eng-GB',1,162,170,'bbmnm',0,0,0,0,'bbmnm','ezstring'),(601,'eng-GB',1,162,169,'mnbnmb',0,0,0,0,'mnbnmb','ezstring'),(602,'eng-GB',1,162,171,'mnbnm',0,0,0,0,'mnbnm','ezstring'),(603,'eng-GB',1,162,172,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezmatrix>\n  <name></name>\n  <columns number=\"2\">\n    <column num=\"0\"\n            id=\"contact_type\">Contact type</column>\n    <column num=\"1\"\n            id=\"contact_value\">Contact value</column>\n  </columns>\n  <rows number=\"0\" />\n</ezmatrix>',0,0,0,0,'','ezmatrix'),(604,'eng-GB',1,162,178,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"bbmnm_mnbnmb.\"\n         suffix=\"\"\n         basename=\"bbmnm_mnbnmb\"\n         dirpath=\"var/intranet/storage/images/contact/persons/bbmnm_mnbnmb/604-1-eng-GB\"\n         url=\"var/intranet/storage/images/contact/persons/bbmnm_mnbnmb/604-1-eng-GB/bbmnm_mnbnmb.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069421705\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(605,'eng-GB',1,162,179,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>nmbnmb</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(606,'eng-GB',1,163,1,'lkj',0,0,0,0,'lkj','ezstring'),(607,'eng-GB',1,163,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>lkjkljkljfd ghdf ghfdghdf</line>\n    <line>ghdf</line>\n    <line>gh</line>\n    <line>fgh</line>\n    <line>dfhdfghdfgh</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(608,'eng-GB',1,163,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>kljlkjl dfghdf gh dfgh fdh fdgh fdgh dfgh dfgh</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(609,'eng-GB',1,163,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"lkj.\"\n         suffix=\"\"\n         basename=\"lkj\"\n         dirpath=\"var/intranet/storage/images/news/lkj/609-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/lkj/609-1-eng-GB/lkj.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069422563\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(610,'eng-GB',1,163,123,'',1,0,0,1,'','ezboolean'),(616,'eng-GB',1,165,151,'jkhjkh',0,0,0,0,'','eztext'),(615,'eng-GB',1,165,150,'kjhjkh',0,0,0,0,'kjhjkh','ezstring'),(614,'eng-GB',1,165,149,'jkhkjh',0,0,0,0,'jkhkjh','ezstring'),(617,'eng-GB',1,166,149,'kljjkl',0,0,0,0,'kljjkl','ezstring'),(618,'eng-GB',1,166,150,'lkjlkj',0,0,0,0,'lkjlkj','ezstring'),(619,'eng-GB',1,166,151,'lkjlkj',0,0,0,0,'','eztext'),(623,'eng-GB',1,168,1,'jkljlkj',0,0,0,0,'jkljlkj','ezstring'),(624,'eng-GB',1,168,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>lkjkljkljlkjklj</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(625,'eng-GB',1,168,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>lkjklj</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(626,'eng-GB',1,168,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"jkljlkj.\"\n         suffix=\"\"\n         basename=\"jkljlkj\"\n         dirpath=\"var/intranet/storage/images/news/jkljlkj/626-1-eng-GB\"\n         url=\"var/intranet/storage/images/news/jkljlkj/626-1-eng-GB/jkljlkj.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069675817\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(627,'eng-GB',1,168,123,'',0,0,0,0,'','ezboolean'),(461,'eng-GB',10,136,157,'Intranet',0,0,0,0,'','ezinisetting'),(462,'eng-GB',10,136,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(463,'eng-GB',10,136,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"intranet.gif\"\n         suffix=\"gif\"\n         basename=\"intranet\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet.gif\"\n         original_filename=\"intranet.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069685477\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"intranet_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"intranet_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069685479\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"intranet_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB\"\n         url=\"var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069685498\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(464,'eng-GB',10,136,160,'right_menu',0,0,0,0,'right_menu','ezpackage'),(465,'eng-GB',10,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',10,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',10,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',10,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(464,'eng-GB',11,136,160,'intranet_gray',0,0,0,0,'intranet_gray','ezpackage'),(465,'eng-GB',11,136,161,'intranet_package',0,0,0,0,'intranet_package','ezstring'),(466,'eng-GB',11,136,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(467,'eng-GB',11,136,181,'myintranet.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(530,'eng-GB',11,136,182,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring');
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(40,'test test',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(120,'foo bar corp',1,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(102,'Reiten Bjørn',4,'eng-GB','eng-GB'),(93,'eZ publish 3.2-2 release',2,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(143,'fp',1,'eng-GB','eng-GB'),(142,'oe',1,'eng-GB','eng-GB'),(139,'Bilbo Baggins',3,'eng-GB','eng-GB'),(57,'Contact',1,'eng-GB','eng-GB'),(58,'Business news',1,'eng-GB','eng-GB'),(59,'Off topic',1,'eng-GB','eng-GB'),(60,'Reports',1,'eng-GB','eng-GB'),(60,'Reports',2,'eng-GB','eng-GB'),(61,'Staff news',1,'eng-GB','eng-GB'),(62,'Persons',1,'eng-GB','eng-GB'),(63,'Companies',1,'eng-GB','eng-GB'),(64,'Files',1,'eng-GB','eng-GB'),(65,'Products',1,'eng-GB','eng-GB'),(66,'Handbooks',1,'eng-GB','eng-GB'),(67,'Documents',1,'eng-GB','eng-GB'),(68,'Company routines',1,'eng-GB','eng-GB'),(69,'Logos',1,'eng-GB','eng-GB'),(64,'Files',2,'eng-GB','eng-GB'),(1,'Intranet',2,'eng-GB','eng-GB'),(140,'Gandalf Gray',2,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(140,'Gandalf Gray',1,'eng-GB','eng-GB'),(121,'Vidar Langseid',1,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(123,'Bård Farstad',1,'eng-GB','eng-GB'),(124,'fido barida',1,'eng-GB','eng-GB'),(119,'eZ sys',1,'eng-GB','eng-GB'),(139,'Bilbo Baggins',2,'eng-GB','eng-GB'),(139,'Bilbo Baggins',1,'eng-GB','eng-GB'),(82,'abb',1,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(136,'Intranet',7,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(87,'New Company',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(89,'Online Editor',1,'eng-GB','eng-GB'),(90,'eZ publish 3.2',1,'eng-GB','eng-GB'),(89,'Online Editor',2,'eng-GB','eng-GB'),(136,'Intranet',6,'eng-GB','eng-GB'),(136,'Intranet',5,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(136,'Intranet',4,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',1,'eng-GB','eng-GB'),(93,'eZ publish 3.2-2 release',1,'eng-GB','eng-GB'),(94,'Mr xxx joined us',1,'eng-GB','eng-GB'),(97,'Yu Wenyue',4,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',2,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(64,'Files',3,'eng-GB','eng-GB'),(97,'Wenyue',1,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',3,'eng-GB','eng-GB'),(92,'eZ systems - reporting live from Munich',4,'eng-GB','eng-GB'),(102,'Bjørn',3,'eng-GB','eng-GB'),(97,'Wenyue',3,'eng-GB','eng-GB'),(136,'Intranet',3,'eng-GB','eng-GB'),(94,'Mr xxx joined us',2,'eng-GB','eng-GB'),(102,'Bjørn',1,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(97,'Wenyue',2,'eng-GB','eng-GB'),(102,'Bjørn',2,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(107,'John Doe',1,'eng-GB','eng-GB'),(107,'John Doe',2,'eng-GB','eng-GB'),(112,'Information',1,'eng-GB','eng-GB'),(111,'vid la',1,'eng-GB','eng-GB'),(113,'Routines',1,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(136,'Intranet',2,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(136,'Intranet',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(136,'Intranet',1,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(136,'Intranet',8,'eng-GB','eng-GB'),(132,'Terje',1,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(133,'New Template look',1,'eng-GB','eng-GB'),(134,'News',1,'eng-GB','eng-GB'),(132,'Terje Kaste',2,'eng-GB','eng-GB'),(144,'New article',1,'eng-GB','eng-GB'),(145,'Foo bar',1,'eng-GB','eng-GB'),(146,'mnb',1,'eng-GB','eng-GB'),(147,'fdhjkldfhj',1,'eng-GB','eng-GB'),(93,'eZ publish 3.2-2 release',3,'eng-GB','eng-GB'),(148,'jh jh',1,'eng-GB','eng-GB'),(148,'jh jh',2,'eng-GB','eng-GB'),(149,'dfsdfg',1,'eng-GB','eng-GB'),(150,'sdifgksdjfgkjgh',1,'eng-GB','eng-GB'),(151,'kåre test',1,'eng-GB','eng-GB'),(153,'Foo',1,'eng-GB','eng-GB'),(154,'jhhhjgjhg',1,'eng-GB','eng-GB'),(155,'jkhkjh',1,'eng-GB','eng-GB'),(156,'jhkjh',1,'eng-GB','eng-GB'),(157,'utuytuy',1,'eng-GB','eng-GB'),(158,'jkhjkh',1,'eng-GB','eng-GB'),(159,'jkhkjh',1,'eng-GB','eng-GB'),(161,'Per Son',1,'eng-GB','eng-GB'),(162,'bbmnm mnbnmb',1,'eng-GB','eng-GB'),(163,'lkj',1,'eng-GB','eng-GB'),(165,'jkhkjh',1,'eng-GB','eng-GB'),(166,'kljjkl',1,'eng-GB','eng-GB'),(168,'jkljlkj',1,'eng-GB','eng-GB'),(136,'Intranet',10,'eng-GB','eng-GB'),(136,'Intranet',11,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,2,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15),(42,12,40,1,1,3,'/1/5/12/42/',9,1,0,'users/guest_accounts/test_test',42),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,8,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,10,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(101,98,123,1,1,5,'/1/2/55/61/98/101/',9,1,0,'contact/companies/ez_sys/brd_farstad',101),(50,2,49,1,1,2,'/1/2/50/',9,1,0,'news',50),(56,50,58,1,1,3,'/1/2/50/56/',9,1,0,'news/business_news',56),(108,55,139,3,1,3,'/1/2/55/108/',9,1,0,'contact/bilbo_baggins',108),(55,2,57,1,1,2,'/1/2/55/',9,1,0,'contact',55),(57,50,59,1,1,3,'/1/2/50/57/',9,1,0,'news/off_topic',57),(58,50,60,2,1,3,'/1/2/50/58/',9,1,0,'news/reports',58),(59,50,61,1,1,3,'/1/2/50/59/',9,1,0,'news/staff_news',59),(60,55,62,1,1,3,'/1/2/55/60/',9,1,0,'contact/persons',60),(61,55,63,1,1,3,'/1/2/55/61/',9,1,0,'contact/companies',61),(62,2,64,3,1,2,'/1/2/62/',8,1,0,'files',62),(63,62,65,1,1,3,'/1/2/62/63/',9,1,1,'files/products',63),(64,62,66,1,1,3,'/1/2/62/64/',9,1,2,'files/handbooks',64),(65,62,67,1,1,3,'/1/2/62/65/',9,1,3,'files/documents',65),(66,62,68,1,1,3,'/1/2/62/66/',9,1,4,'files/company_routines',66),(67,62,69,1,1,3,'/1/2/62/67/',9,1,5,'files/logos',67),(102,99,124,1,1,5,'/1/2/55/61/99/102/',9,1,0,'contact/companies/foo_bar_corp/fido_barida',102),(105,55,132,2,1,3,'/1/2/55/105/',9,1,0,'contact/terje_kaste',105),(78,61,82,1,1,4,'/1/2/55/61/78/',9,1,0,'contact/companies/abb',78),(79,63,89,2,1,4,'/1/2/62/63/79/',9,1,0,'files/products/online_editor',79),(80,63,90,1,1,4,'/1/2/62/63/80/',9,1,0,'files/products/ez_publish_32',80),(81,56,92,4,1,4,'/1/2/50/56/81/',9,1,0,'news/business_news/ez_systems_reporting_live_from_munich',81),(82,56,93,3,1,4,'/1/2/50/56/82/',9,1,0,'news/business_news/ez_publish_322_release',82),(83,59,94,2,1,4,'/1/2/50/59/83/',9,1,0,'news/staff_news/mr_xxx_joined_us',83),(85,60,97,4,1,4,'/1/2/55/60/85/',9,1,0,'contact/persons/yu_wenyue',85),(98,61,119,1,1,4,'/1/2/55/61/98/',9,1,0,'contact/companies/ez_sys',98),(118,60,148,2,1,4,'/1/2/55/60/118/',1,1,0,'contact/persons/jh_jh',118),(100,98,121,1,1,5,'/1/2/55/61/98/100/',9,1,0,'contact/companies/ez_sys/vidar_langseid',100),(99,61,120,1,1,4,'/1/2/55/61/99/',9,1,0,'contact/companies/foo_bar_corp',99),(90,60,102,4,1,4,'/1/2/55/60/90/',9,1,0,'contact/persons/reiten_bjrn',90),(91,14,107,2,1,3,'/1/5/14/91/',9,1,0,'users/editors/john_doe',91),(92,14,111,1,1,3,'/1/5/14/92/',9,1,0,'users/editors/vid_la',92),(93,2,112,1,1,2,'/1/2/93/',9,1,0,'information',93),(94,93,113,1,1,3,'/1/2/93/94/',9,1,0,'information/routines',94),(95,46,115,3,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,2,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(107,48,136,11,1,3,'/1/44/48/107/',9,1,0,'setup/look_and_feel/intranet',107),(109,55,140,2,1,3,'/1/2/55/109/',9,1,0,'contact/gandalf_gray',109),(110,63,142,1,1,4,'/1/2/62/63/110/',9,1,0,'files/products/oe',110),(111,57,144,1,1,4,'/1/2/50/57/111/',1,1,0,'news/off_topic/new_article',111),(112,2,144,1,1,2,'/1/2/112/',1,1,0,'new_article',111),(113,57,145,1,1,4,'/1/2/50/57/113/',1,1,0,'news/off_topic/foo_bar',113),(114,59,146,1,1,4,'/1/2/50/59/114/',1,1,0,'news/staff_news/mnb',114),(115,56,146,1,1,4,'/1/2/50/56/115/',1,1,0,'news/business_news/mnb',114),(116,58,147,1,1,4,'/1/2/50/58/116/',1,1,0,'news/reports/fdhjkldfhj',116),(117,56,147,1,1,4,'/1/2/50/56/117/',1,1,0,'news/business_news/fdhjkldfhj',116),(119,55,148,2,1,3,'/1/2/55/119/',1,1,0,'contact/jh_jh',118),(120,57,149,1,1,4,'/1/2/50/57/120/',1,1,0,'news/off_topic/dfsdfg',120),(121,56,149,1,1,4,'/1/2/50/56/121/',1,1,0,'news/business_news/dfsdfg',120),(122,59,150,1,1,4,'/1/2/50/59/122/',1,1,0,'news/staff_news/sdifgksdjfgkjgh',122),(123,57,150,1,1,4,'/1/2/50/57/123/',1,1,0,'news/off_topic/sdifgksdjfgkjgh',122),(124,58,151,1,1,4,'/1/2/50/58/124/',1,1,0,'news/reports/kre_test',124),(125,57,151,1,1,4,'/1/2/50/57/125/',1,1,0,'news/off_topic/kre_test',124),(126,61,153,1,1,4,'/1/2/55/61/126/',1,1,0,'contact/companies/foo',126),(127,63,154,1,1,4,'/1/2/62/63/127/',1,1,0,'files/products/jhhhjgjhg',127),(128,56,155,1,1,4,'/1/2/50/56/128/',1,1,0,'news/business_news/jkhkjh',128),(129,50,155,1,1,3,'/1/2/50/129/',1,1,0,'news/jkhkjh',128),(130,56,156,1,1,4,'/1/2/50/56/130/',1,1,0,'news/business_news/jhkjh',130),(131,50,156,1,1,3,'/1/2/50/131/',1,1,0,'news/jhkjh',130),(132,58,157,1,1,4,'/1/2/50/58/132/',1,1,0,'news/reports/utuytuy',132),(133,50,157,1,1,3,'/1/2/50/133/',1,1,0,'news/utuytuy',132),(134,56,158,1,1,4,'/1/2/50/56/134/',1,1,0,'news/business_news/jkhjkh',134),(135,50,158,1,1,3,'/1/2/50/135/',1,1,0,'news/jkhjkh',134),(136,63,159,1,1,4,'/1/2/62/63/136/',1,1,0,'files/products/jkhkjh',136),(137,62,159,1,1,3,'/1/2/62/137/',1,1,0,'files/jkhkjh',136),(138,60,161,1,1,4,'/1/2/55/60/138/',1,1,0,'contact/persons/per_son',138),(139,55,161,1,1,3,'/1/2/55/139/',1,1,0,'contact/per_son',138),(140,60,162,1,1,4,'/1/2/55/60/140/',1,1,0,'contact/persons/bbmnm_mnbnmb',140),(141,59,163,1,1,4,'/1/2/50/59/141/',1,1,0,'news/staff_news/lkj',141),(142,50,163,1,1,3,'/1/2/50/142/',1,1,0,'news/lkj',141),(143,141,165,1,1,5,'/1/2/50/59/141/143/',1,1,0,'news/staff_news/lkj/jkhkjh',143),(144,141,166,1,1,5,'/1/2/50/59/141/144/',1,1,0,'news/staff_news/lkj/kljjkl',144),(145,58,168,1,1,4,'/1/2/50/58/145/',1,1,0,'news/reports/jkljlkj',145),(146,50,168,1,1,3,'/1/2/50/146/',1,1,0,'news/jkljlkj',145);
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
INSERT INTO ezcontentobject_version VALUES (1,1,14,1,1033919080,1033919080,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(471,40,14,1,1053613007,1053613020,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(480,45,14,1,1066388718,1066388815,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(636,119,111,1,1067006527,1067006564,1,0,0),(698,139,14,3,1069339365,1069339386,1,0,0),(688,136,14,6,1069168386,1069168404,3,0,0),(490,49,14,1,1066398007,1066398020,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(622,92,14,4,1066998064,1066998231,1,0,0),(697,140,14,2,1069337446,1069337620,1,0,0),(687,136,14,5,1069167616,1069167783,3,0,0),(625,97,14,3,1066998422,1066998491,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(519,57,14,1,1066729088,1066729137,1,0,0),(520,58,14,1,1066729186,1066729195,1,0,0),(521,59,14,1,1066729202,1066729210,1,0,0),(522,60,14,1,1066729218,1066729226,3,0,0),(523,60,14,2,1066729234,1066729241,1,0,0),(524,61,14,1,1066729249,1066729258,1,0,0),(525,62,14,1,1066729275,1066729284,1,0,0),(526,63,14,1,1066729291,1066729298,1,0,0),(527,64,14,1,1066729311,1066729319,3,0,0),(528,65,14,1,1066729334,1066729341,1,0,0),(529,66,14,1,1066729349,1066729356,1,0,0),(530,67,14,1,1066729363,1066729371,1,0,0),(531,68,14,1,1066729377,1066729385,1,0,0),(532,69,14,1,1066729392,1066729400,1,0,0),(533,64,14,2,1066729407,1066729416,3,0,0),(686,136,14,4,1069167541,1069167556,3,0,0),(708,1,14,2,1069416307,1069416322,1,1,0),(685,136,14,3,1069164957,1069164987,3,0,0),(689,136,14,7,1069168565,1069168581,3,0,0),(684,136,14,2,1069164905,1069164915,3,0,0),(693,132,14,2,1069336220,1069336240,1,0,0),(641,124,111,1,1067006729,1067006745,1,0,0),(640,123,111,1,1067006679,1067006700,1,0,0),(637,120,111,1,1067006576,1067006609,1,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(683,45,14,10,1069164834,1069164888,1,0,0),(733,136,14,11,1069687139,1069687155,1,0,0),(550,82,14,1,1066740167,1066740212,1,0,0),(638,121,111,1,1067006632,1067006667,1,0,0),(561,89,14,1,1066746635,1066746701,3,0,0),(562,90,14,1,1066746721,1066746790,1,0,0),(563,89,14,2,1066747534,1066747596,1,0,0),(573,92,14,1,1066828519,1066828821,3,0,0),(574,93,14,1,1066828848,1066828903,3,0,0),(575,94,14,1,1066828947,1066829047,3,0,0),(576,92,14,2,1066832966,1066833112,3,0,0),(630,93,14,2,1067000124,1067000145,3,0,0),(690,136,14,8,1069168808,1069168827,3,0,0),(669,132,14,1,1067417596,1067417696,3,0,0),(581,64,14,3,1066898069,1066898100,1,0,0),(582,97,14,1,1066898192,1066898314,3,0,0),(583,92,14,3,1066907449,1066907519,3,0,0),(707,136,14,9,1069416345,1069416376,3,0,0),(694,139,14,1,1069336281,1069336369,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(628,102,14,4,1066998796,1066998808,1,0,0),(627,97,14,4,1066998771,1066998783,1,0,0),(696,140,14,1,1069337390,1069337418,3,0,0),(590,94,14,2,1066910791,1066910828,1,0,0),(591,102,14,1,1066911522,1066911635,3,0,0),(592,97,14,2,1066914813,1066916644,3,0,0),(595,102,14,2,1066916662,1066916675,3,0,0),(598,107,14,1,1066916843,1066916865,3,0,0),(599,107,14,2,1066916931,1066916941,1,0,0),(605,112,14,1,1066986251,1066986270,1,0,0),(601,102,14,3,1066917426,1066998412,3,0,0),(604,111,14,1,1066917488,1066917523,1,0,0),(606,113,14,1,1066986461,1066986541,1,0,0),(695,139,14,2,1069336466,1069336510,3,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(672,115,14,3,1069162736,1069162746,1,0,0),(673,43,14,8,1069162754,1069162840,1,0,0),(674,45,14,9,1069162851,1069162858,3,0,0),(675,116,14,2,1069162867,1069162891,1,0,0),(700,142,14,1,1069405546,1069405553,1,0,0),(701,143,14,1,1069409689,1069410987,0,0,0),(702,144,14,1,1069411246,1069411408,1,0,0),(703,145,14,1,1069411420,1069411438,1,0,0),(704,146,14,1,1069411452,1069411461,1,0,0),(705,147,14,1,1069414789,1069414806,1,0,0),(706,93,14,3,1069414836,1069414860,1,0,0),(709,148,14,1,1069417281,1069417643,3,0,0),(710,148,14,2,1069417661,1069417688,1,0,0),(711,149,14,1,1069417715,1069417726,1,0,0),(712,150,14,1,1069417743,1069417787,1,0,0),(713,151,14,1,1069417820,1069417842,1,0,0),(715,153,14,1,1069418730,1069419202,1,0,0),(716,154,14,1,1069419286,1069419405,1,0,0),(717,155,14,1,1069419529,1069419542,1,0,0),(718,156,14,1,1069419888,1069419905,1,0,0),(719,157,14,1,1069420002,1069420018,1,0,0),(720,158,14,1,1069420865,1069420873,1,0,0),(721,159,14,1,1069420891,1069420903,1,0,0),(723,161,14,1,1069421670,1069421689,1,0,0),(724,162,14,1,1069421702,1069421719,1,0,0),(725,163,14,1,1069422559,1069422602,1,0,0),(727,165,14,1,1069423480,1069423490,1,0,0),(728,166,14,1,1069423948,1069423957,1,0,0),(730,168,14,1,1069675813,1069675836,1,0,0),(732,136,14,10,1069685452,1069685477,3,0,0);
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
INSERT INTO ezimagefile VALUES (6,477,'var/intranet/storage/images/contact/bilbo_baggins/477-1-eng-GB/bilbo_baggins.'),(5,420,'var/intranet/storage/images/contact/terje_kaste/420-2-eng-GB/terje_kaste.'),(19,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet.gif'),(7,477,'var/intranet/storage/images/contact/bilbo_baggins/477-2-eng-GB/bilbo_baggins.'),(8,483,'var/intranet/storage/images/contact/gandalf_gray/483-1-eng-GB/gandalf_gray.'),(9,483,'var/intranet/storage/images/contact/gandalf_gray/483-2-eng-GB/gandalf_gray.'),(11,477,'var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB/bilbo_baggins.jpg'),(12,477,'var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB/bilbo_baggins_reference.jpg'),(13,477,'var/intranet/storage/images/contact/bilbo_baggins/477-3-eng-GB/bilbo_baggins_medium.jpg'),(14,502,'var/intranet/storage/images/new_article/502-1-eng-GB/new_article.'),(15,508,'var/intranet/storage/images/news/off_topic/foo_bar/508-1-eng-GB/foo_bar.'),(16,514,'var/intranet/storage/images/news/business_news/mnb/514-1-eng-GB/mnb.'),(17,520,'var/intranet/storage/images/news/business_news/fdhjkldfhj/520-1-eng-GB/fdhjkldfhj.'),(18,259,'var/intranet/storage/images/news/business_news/ez_publish_322_release/259-3-eng-GB/ez_publish_322_release.'),(20,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_reference.gif'),(21,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_medium.gif'),(22,535,'var/intranet/storage/images/contact/jh_jh/535-1-eng-GB/jh_jh.'),(23,535,'var/intranet/storage/images/contact/jh_jh/535-2-eng-GB/jh_jh.'),(24,540,'var/intranet/storage/images/news/business_news/dfsdfg/540-1-eng-GB/dfsdfg.'),(25,545,'var/intranet/storage/images/news/off_topic/sdifgksdjfgkjgh/545-1-eng-GB/sdifgksdjfgkjgh.'),(26,550,'var/intranet/storage/images/news/off_topic/kre_test/550-1-eng-GB/kre_test.'),(27,558,'var/intranet/storage/images/contact/companies/foo/558-1-eng-GB/foo.'),(28,568,'var/intranet/storage/images/news/jkhkjh/568-1-eng-GB/jkhkjh.'),(29,573,'var/intranet/storage/images/news/jhkjh/573-1-eng-GB/jhkjh.'),(30,578,'var/intranet/storage/images/news/utuytuy/578-1-eng-GB/utuytuy.'),(31,583,'var/intranet/storage/images/news/jkhjkh/583-1-eng-GB/jkhjkh.'),(32,598,'var/intranet/storage/images/contact/per_son/598-1-eng-GB/per_son.'),(33,604,'var/intranet/storage/images/contact/persons/bbmnm_mnbnmb/604-1-eng-GB/bbmnm_mnbnmb.'),(34,609,'var/intranet/storage/images/news/lkj/609-1-eng-GB/lkj.'),(35,626,'var/intranet/storage/images/news/jkljlkj/626-1-eng-GB/jkljlkj.'),(36,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-9-eng-GB/intranet_logo.gif'),(38,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet.gif'),(39,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_reference.gif'),(40,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_medium.gif'),(41,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-10-eng-GB/intranet_logo.gif'),(42,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet.gif'),(43,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_reference.gif'),(44,463,'var/intranet/storage/images/setup/look_and_feel/intranet/463-11-eng-GB/intranet_medium.gif');
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
INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(181,40,1,12,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(191,45,1,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(390,136,5,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(389,136,4,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(325,92,4,56,9,1,1,0,0),(230,58,1,50,9,1,1,0,0),(400,140,2,55,9,1,1,0,0),(388,136,3,48,9,1,1,0,0),(328,97,3,60,9,1,1,0,0),(416,136,9,48,9,1,1,0,0),(229,57,1,2,9,1,1,0,0),(231,59,1,50,9,1,1,0,0),(232,60,1,50,9,1,1,0,0),(233,60,2,50,9,1,1,0,0),(234,61,1,50,9,1,1,0,0),(235,62,1,55,9,1,1,0,0),(236,63,1,55,9,1,1,0,0),(237,64,1,2,9,1,1,0,0),(238,65,1,62,9,1,1,0,0),(239,66,1,62,9,1,1,0,0),(240,67,1,62,9,1,1,0,0),(241,68,1,62,9,1,1,0,0),(242,69,1,62,9,1,1,0,0),(243,64,2,2,8,1,1,0,0),(344,124,1,99,9,1,1,0,0),(387,136,2,48,9,1,1,0,0),(391,136,6,48,9,1,1,0,0),(386,45,10,46,9,1,1,0,0),(399,140,1,55,9,1,1,0,0),(339,119,1,61,9,1,1,0,0),(343,123,1,98,9,1,1,0,0),(341,121,1,98,9,1,1,0,0),(401,139,3,55,9,1,1,0,0),(321,45,6,46,9,1,1,0,0),(461,136,11,48,9,1,1,0,0),(397,139,1,55,9,1,1,0,0),(260,82,1,61,9,1,1,0,0),(340,120,1,61,9,1,1,0,0),(268,89,1,63,9,1,1,0,0),(269,90,1,63,9,1,1,0,0),(270,89,2,63,9,1,1,0,0),(279,92,1,56,9,1,1,0,0),(280,93,1,56,9,1,1,0,0),(281,94,1,59,9,1,1,0,0),(282,92,2,56,9,1,1,0,0),(333,93,2,56,9,1,1,0,0),(393,136,8,48,9,1,1,0,0),(372,132,1,55,9,1,1,0,0),(286,64,3,2,8,1,1,0,0),(287,97,1,60,9,1,1,0,0),(288,92,3,56,9,1,1,0,0),(417,1,2,1,9,1,1,0,0),(330,97,4,60,9,1,1,0,0),(335,45,8,46,9,1,1,0,0),(392,136,7,48,9,1,1,0,0),(331,102,4,60,9,1,1,0,0),(398,139,2,55,9,1,1,0,0),(296,94,2,59,9,1,1,0,0),(297,102,1,60,9,1,1,0,0),(298,97,2,60,9,1,1,0,0),(299,102,2,60,9,1,1,0,0),(300,107,1,14,9,1,1,0,0),(301,107,2,14,9,1,1,0,0),(307,112,1,2,9,1,1,0,0),(303,102,3,60,9,1,1,0,0),(306,111,1,14,9,1,1,0,0),(308,113,1,93,9,1,1,0,0),(396,132,2,55,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(375,115,3,46,9,1,1,0,0),(376,43,8,46,9,1,1,0,0),(377,45,9,46,9,1,1,0,0),(378,116,2,46,9,1,1,0,0),(403,142,1,63,9,1,1,0,0),(404,143,1,56,1,1,0,0,0),(405,143,1,57,1,1,0,0,1),(406,144,1,2,1,1,0,0,0),(407,144,1,57,1,1,1,0,1),(408,145,1,57,1,1,1,0,0),(409,145,1,57,1,1,1,0,1),(410,146,1,56,1,1,0,0,0),(411,146,1,59,1,1,1,0,1),(412,147,1,56,1,1,0,0,0),(413,147,1,58,1,1,1,0,1),(414,93,3,56,9,1,1,0,0),(415,93,3,56,1,1,1,0,1),(418,148,1,55,1,1,0,0,0),(419,148,1,60,1,1,1,0,1),(420,148,2,55,1,1,0,0,0),(421,148,2,60,1,1,1,0,1),(422,149,1,56,1,1,0,0,0),(423,149,1,57,1,1,1,0,1),(424,150,1,57,1,1,0,0,0),(425,150,1,59,1,1,1,0,1),(426,151,1,57,1,1,0,0,0),(427,151,1,58,1,1,1,0,1),(429,153,1,61,1,1,1,0,0),(430,153,1,61,1,1,1,0,1),(431,154,1,63,1,1,1,0,0),(432,154,1,63,1,1,1,0,1),(433,155,1,50,1,1,0,0,0),(434,155,1,56,1,1,1,0,1),(435,156,1,50,1,1,0,0,0),(436,156,1,56,1,1,1,0,1),(437,157,1,50,1,1,0,0,0),(438,157,1,58,1,1,1,0,1),(439,158,1,50,1,1,0,0,0),(440,158,1,56,1,1,1,0,1),(441,159,1,62,1,1,0,0,0),(442,159,1,63,1,1,1,0,1),(446,161,1,60,1,1,1,0,1),(445,161,1,55,1,1,0,0,0),(447,162,1,60,1,1,1,0,0),(448,162,1,60,1,1,1,0,1),(449,163,1,50,1,1,0,0,0),(450,163,1,59,1,1,1,0,1),(452,165,1,141,1,1,1,0,0),(453,166,1,141,1,1,1,0,0),(457,168,1,58,1,1,1,0,1),(456,168,1,50,1,1,0,0,0),(460,136,10,48,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',41,1,0,0,'','','',''),(2,0,'ezpublish',42,1,0,0,'','','',''),(3,0,'ezpublish',43,1,0,0,'','','',''),(4,0,'ezpublish',44,1,0,0,'','','',''),(5,0,'ezpublish',43,2,0,0,'','','',''),(6,0,'ezpublish',43,3,0,0,'','','',''),(7,0,'ezpublish',43,4,0,0,'','','',''),(8,0,'ezpublish',43,5,0,0,'','','',''),(9,0,'ezpublish',45,1,0,0,'','','',''),(10,0,'ezpublish',46,1,0,0,'','','',''),(11,0,'ezpublish',46,2,0,0,'','','',''),(12,0,'ezpublish',48,1,0,0,'','','',''),(13,0,'ezpublish',48,4,0,0,'','','',''),(14,0,'ezpublish',48,5,0,0,'','','',''),(15,0,'ezpublish',49,1,0,0,'','','',''),(16,0,'ezpublish',50,1,0,0,'','','',''),(17,0,'ezpublish',50,2,0,0,'','','',''),(18,0,'ezpublish',48,6,0,0,'','','',''),(19,0,'ezpublish',48,7,0,0,'','','',''),(20,0,'ezpublish',48,8,0,0,'','','',''),(21,0,'ezpublish',48,9,0,0,'','','',''),(22,0,'ezpublish',48,11,0,0,'','','',''),(23,0,'ezpublish',55,1,0,0,'','','',''),(24,0,'ezpublish',54,1,0,0,'','','',''),(25,0,'ezpublish',56,1,0,0,'','','',''),(26,0,'ezpublish',56,2,0,0,'','','',''),(27,0,'ezpublish',56,3,0,0,'','','',''),(28,0,'ezpublish',56,4,0,0,'','','',''),(29,0,'ezpublish',56,5,0,0,'','','',''),(30,0,'ezpublish',56,6,0,0,'','','',''),(31,0,'ezpublish',57,1,0,0,'','','',''),(32,0,'ezpublish',58,1,0,0,'','','',''),(33,0,'ezpublish',59,1,0,0,'','','',''),(34,0,'ezpublish',60,1,0,0,'','','',''),(35,0,'ezpublish',60,2,0,0,'','','',''),(36,0,'ezpublish',61,1,0,0,'','','',''),(37,0,'ezpublish',62,1,0,0,'','','',''),(38,0,'ezpublish',63,1,0,0,'','','',''),(39,0,'ezpublish',64,1,0,0,'','','',''),(40,0,'ezpublish',65,1,0,0,'','','',''),(41,0,'ezpublish',66,1,0,0,'','','',''),(42,0,'ezpublish',67,1,0,0,'','','',''),(43,0,'ezpublish',68,1,0,0,'','','',''),(44,0,'ezpublish',69,1,0,0,'','','',''),(45,0,'ezpublish',64,2,0,0,'','','',''),(46,0,'ezpublish',70,1,0,0,'','','',''),(47,0,'ezpublish',72,1,0,0,'','','',''),(48,0,'ezpublish',73,1,0,0,'','','',''),(49,0,'ezpublish',74,1,0,0,'','','',''),(50,0,'ezpublish',75,1,0,0,'','','',''),(51,0,'ezpublish',70,2,0,0,'','','',''),(52,0,'ezpublish',76,1,0,0,'','','',''),(53,0,'ezpublish',77,1,0,0,'','','',''),(54,0,'ezpublish',78,1,0,0,'','','',''),(55,0,'ezpublish',79,1,0,0,'','','',''),(56,0,'ezpublish',80,1,0,0,'','','',''),(57,0,'ezpublish',56,7,0,0,'','','',''),(58,0,'ezpublish',56,8,0,0,'','','',''),(59,0,'ezpublish',56,9,0,0,'','','',''),(60,0,'ezpublish',82,1,0,0,'','','',''),(61,0,'ezpublish',56,10,0,0,'','','',''),(62,0,'ezpublish',76,2,0,0,'','','',''),(63,0,'ezpublish',56,11,0,0,'','','',''),(64,0,'ezpublish',89,1,0,0,'','','',''),(65,0,'ezpublish',90,1,0,0,'','','',''),(66,0,'ezpublish',89,2,0,0,'','','',''),(67,0,'ezpublish',56,12,0,0,'','','',''),(68,0,'ezpublish',56,13,0,0,'','','',''),(69,0,'ezpublish',56,18,0,0,'','','',''),(70,0,'ezpublish',92,1,0,0,'','','',''),(71,0,'ezpublish',93,1,0,0,'','','',''),(72,0,'ezpublish',94,1,0,0,'','','',''),(73,0,'ezpublish',95,1,0,0,'','','',''),(74,0,'ezpublish',92,2,0,0,'','','',''),(75,0,'ezpublish',64,3,0,0,'','','',''),(76,0,'ezpublish',97,1,0,0,'','','',''),(77,0,'ezpublish',92,3,0,0,'','','',''),(78,0,'ezpublish',98,1,0,0,'','','',''),(79,0,'ezpublish',99,1,0,0,'','','',''),(80,0,'ezpublish',95,2,0,0,'','','',''),(81,0,'ezpublish',100,1,0,0,'','','',''),(82,0,'ezpublish',100,2,0,0,'','','',''),(83,0,'ezpublish',101,1,0,0,'','','',''),(84,0,'ezpublish',94,2,0,0,'','','',''),(85,0,'ezpublish',102,1,0,0,'','','',''),(86,0,'ezpublish',97,2,0,0,'','','',''),(87,0,'ezpublish',102,2,0,0,'','','',''),(88,0,'ezpublish',107,1,0,0,'','','',''),(89,0,'ezpublish',107,2,0,0,'','','',''),(90,0,'ezpublish',111,1,0,0,'','','',''),(91,0,'ezpublish',112,1,0,0,'','','',''),(92,0,'ezpublish',113,1,0,0,'','','',''),(93,0,'ezpublish',43,6,0,0,'','','',''),(94,0,'ezpublish',45,2,0,0,'','','',''),(95,0,'ezpublish',43,7,0,0,'','','',''),(96,0,'ezpublish',45,3,0,0,'','','',''),(97,0,'ezpublish',115,1,0,0,'','','',''),(98,0,'ezpublish',45,4,0,0,'','','',''),(99,0,'ezpublish',116,1,0,0,'','','',''),(100,0,'ezpublish',45,5,0,0,'','','',''),(101,0,'ezpublish',45,6,0,0,'','','',''),(102,0,'ezpublish',56,19,0,0,'','','',''),(103,0,'ezpublish',115,2,0,0,'','','',''),(104,0,'ezpublish',98,2,0,0,'','','',''),(105,0,'ezpublish',92,4,0,0,'','','',''),(106,0,'ezpublish',102,3,0,0,'','','',''),(107,0,'ezpublish',97,3,0,0,'','','',''),(108,0,'ezpublish',56,20,0,0,'','','',''),(109,0,'ezpublish',97,4,0,0,'','','',''),(110,0,'ezpublish',102,4,0,0,'','','',''),(111,0,'ezpublish',93,2,0,0,'','','',''),(112,0,'ezpublish',45,7,0,0,'','','',''),(113,0,'ezpublish',56,21,0,0,'','','',''),(114,0,'ezpublish',45,8,0,0,'','','',''),(115,0,'ezpublish',118,1,0,0,'','','',''),(116,0,'ezpublish',119,1,0,0,'','','',''),(117,0,'ezpublish',120,1,0,0,'','','',''),(118,0,'ezpublish',121,1,0,0,'','','',''),(119,0,'ezpublish',123,1,0,0,'','','',''),(120,0,'ezpublish',124,1,0,0,'','','',''),(121,0,'ezpublish',125,1,0,0,'','','',''),(122,0,'ezpublish',125,2,0,0,'','','',''),(123,0,'ezpublish',125,3,0,0,'','','',''),(124,0,'ezpublish',125,4,0,0,'','','',''),(125,0,'ezpublish',56,22,0,0,'','','',''),(126,0,'ezpublish',56,23,0,0,'','','',''),(127,0,'ezpublish',56,24,0,0,'','','',''),(128,0,'ezpublish',56,25,0,0,'','','',''),(129,0,'ezpublish',128,1,0,0,'','','',''),(130,0,'ezpublish',128,2,0,0,'','','',''),(131,0,'ezpublish',56,26,0,0,'','','',''),(132,0,'ezpublish',56,27,0,0,'','','',''),(133,0,'ezpublish',56,28,0,0,'','','',''),(134,0,'ezpublish',56,29,0,0,'','','',''),(135,0,'ezpublish',132,1,0,0,'','','',''),(136,0,'ezpublish',115,3,0,0,'','','',''),(137,0,'ezpublish',43,8,0,0,'','','',''),(138,0,'ezpublish',45,9,0,0,'','','',''),(139,0,'ezpublish',116,2,0,0,'','','',''),(140,0,'ezpublish',135,1,0,0,'','','',''),(141,0,'ezpublish',136,1,0,0,'','','',''),(142,0,'ezpublish',45,10,0,0,'','','',''),(143,0,'ezpublish',136,2,0,0,'','','',''),(144,0,'ezpublish',136,3,0,0,'','','',''),(145,0,'ezpublish',136,4,0,0,'','','',''),(146,0,'ezpublish',136,5,0,0,'','','',''),(147,0,'ezpublish',136,6,0,0,'','','',''),(148,0,'ezpublish',136,7,0,0,'','','',''),(149,0,'ezpublish',136,8,0,0,'','','',''),(150,0,'ezpublish',132,2,0,0,'','','',''),(151,0,'ezpublish',139,1,0,0,'','','',''),(152,0,'ezpublish',139,2,0,0,'','','',''),(153,0,'ezpublish',140,1,0,0,'','','',''),(154,0,'ezpublish',140,2,0,0,'','','',''),(155,0,'ezpublish',139,3,0,0,'','','',''),(156,0,'ezpublish',142,1,0,0,'','','',''),(157,0,'ezpublish',144,1,0,0,'','','',''),(158,0,'ezpublish',145,1,0,0,'','','',''),(159,0,'ezpublish',146,1,0,0,'','','',''),(160,0,'ezpublish',147,1,0,0,'','','',''),(161,0,'ezpublish',93,3,0,0,'','','',''),(162,0,'ezpublish',136,9,0,0,'','','',''),(163,0,'ezpublish',1,2,0,0,'','','',''),(164,0,'ezpublish',148,1,0,0,'','','',''),(165,0,'ezpublish',148,2,0,0,'','','',''),(166,0,'ezpublish',149,1,0,0,'','','',''),(167,0,'ezpublish',150,1,0,0,'','','',''),(168,0,'ezpublish',151,1,0,0,'','','',''),(169,0,'ezpublish',153,1,0,0,'','','',''),(170,0,'ezpublish',154,1,0,0,'','','',''),(171,0,'ezpublish',155,1,0,0,'','','',''),(172,0,'ezpublish',156,1,0,0,'','','',''),(173,0,'ezpublish',157,1,0,0,'','','',''),(174,0,'ezpublish',158,1,0,0,'','','',''),(175,0,'ezpublish',159,1,0,0,'','','',''),(176,0,'ezpublish',161,1,0,0,'','','',''),(177,0,'ezpublish',162,1,0,0,'','','',''),(178,0,'ezpublish',163,1,0,0,'','','',''),(179,0,'ezpublish',165,1,0,0,'','','',''),(180,0,'ezpublish',166,1,0,0,'','','',''),(181,0,'ezpublish',168,1,0,0,'','','',''),(182,0,'ezpublish',136,10,0,0,'','','',''),(183,0,'ezpublish',136,11,0,0,'','','','');
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
INSERT INTO ezsearch_object_word_link VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0),(27,40,5,0,1,5,0,4,1053613020,2,9,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(1846,43,933,0,2,22,0,14,1066384365,11,155,'',0),(1845,43,22,0,1,932,933,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(1844,43,932,0,0,0,22,14,1066384365,11,152,'',0),(1863,45,940,0,5,939,0,14,1066388816,11,155,'',0),(1862,45,939,0,4,25,940,14,1066388816,11,155,'',0),(1861,45,25,0,3,34,939,14,1066388816,11,155,'',0),(1860,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(1874,132,842,0,4,123,945,17,1067417696,5,172,'contact_type',2),(61,49,37,0,0,0,0,1,1066398020,4,4,'',0),(74,58,49,0,0,0,37,1,1066729196,4,4,'',0),(73,57,48,0,0,0,0,1,1066729137,5,4,'',0),(75,58,37,0,1,49,0,1,1066729196,4,4,'',0),(76,59,50,0,0,0,51,1,1066729211,4,4,'',0),(77,59,51,0,1,50,0,1,1066729211,4,4,'',0),(79,60,53,0,0,0,0,1,1066729226,4,4,'',0),(80,61,54,0,0,0,37,1,1066729258,4,4,'',0),(81,61,37,0,1,54,0,1,1066729258,4,4,'',0),(82,62,55,0,0,0,0,1,1066729284,5,4,'',0),(83,63,56,0,0,0,0,1,1066729298,5,4,'',0),(821,64,440,0,0,0,381,1,1066729319,6,4,'',0),(85,65,58,0,0,0,0,1,1066729341,6,4,'',0),(86,66,59,0,0,0,0,1,1066729356,6,4,'',0),(87,67,60,0,0,0,0,1,1066729371,6,4,'',0),(88,68,61,0,0,0,62,1,1066729385,6,4,'',0),(89,68,62,0,1,61,0,1,1066729385,6,4,'',0),(90,69,63,0,0,0,0,1,1066729400,6,4,'',0),(1788,119,123,0,10,123,898,16,1067006564,5,168,'contact_type',0),(1873,132,123,0,3,944,842,17,1067417696,5,172,'contact_type',0),(1872,132,944,0,2,943,123,17,1067417696,5,171,'',0),(1871,132,943,0,1,942,944,17,1067417696,5,169,'',0),(1870,132,942,0,0,0,943,17,1067417696,5,170,'',0),(1821,124,918,0,4,917,0,17,1067006746,5,179,'',0),(1820,124,917,0,3,903,918,17,1067006746,5,179,'',0),(1819,124,903,0,2,916,917,17,1067006746,5,171,'',0),(1818,124,916,0,1,915,903,17,1067006746,5,169,'',0),(1817,124,915,0,0,0,916,17,1067006746,5,170,'',0),(1816,123,914,0,3,913,0,17,1067006701,5,179,'',0),(1815,123,913,0,2,912,914,17,1067006701,5,171,'',0),(1814,123,912,0,1,911,913,17,1067006701,5,169,'',0),(1813,123,911,0,0,0,912,17,1067006701,5,170,'',0),(1812,121,910,0,4,904,0,17,1067006667,5,179,'',0),(1811,121,904,0,3,123,910,17,1067006667,5,172,'contact_value',123),(1810,121,123,0,2,909,904,17,1067006667,5,172,'contact_type',0),(1809,121,909,0,1,908,123,17,1067006667,5,169,'',0),(1808,121,908,0,0,0,909,17,1067006667,5,170,'',0),(1807,120,898,0,15,123,0,16,1067006610,5,168,'contact_type',1),(1806,120,123,0,14,123,898,16,1067006610,5,168,'contact_type',0),(1805,120,123,0,13,907,123,16,1067006610,5,167,'0',0),(1804,120,907,0,12,906,123,16,1067006610,5,166,'',0),(1803,120,906,0,11,905,907,16,1067006610,5,166,'',0),(1802,120,905,0,10,904,906,16,1067006610,5,166,'',0),(1801,120,904,0,9,906,905,16,1067006610,5,164,'address_value',123),(1800,120,906,0,8,905,904,16,1067006610,5,164,'address_value',0),(1799,120,905,0,7,842,906,16,1067006610,5,164,'address_value',0),(1798,120,842,0,6,898,905,16,1067006610,5,164,'address_type',2),(1797,120,898,0,5,123,842,16,1067006610,5,164,'address_type',1),(1796,120,123,0,4,904,898,16,1067006610,5,164,'address_type',0),(1795,120,904,0,3,907,123,16,1067006610,5,163,'',123),(1794,120,907,0,2,906,904,16,1067006610,5,162,'',0),(1793,120,906,0,1,905,907,16,1067006610,5,162,'',0),(1792,120,905,0,0,0,906,16,1067006610,5,162,'',0),(1791,119,904,0,13,904,0,16,1067006564,5,168,'contact_value',123),(1790,119,904,0,12,898,904,16,1067006564,5,168,'contact_value',123),(1789,119,898,0,11,123,904,16,1067006564,5,168,'contact_type',1),(1787,119,123,0,9,903,123,16,1067006564,5,167,'0',0),(180,82,122,0,0,0,123,16,1066740213,5,162,'',0),(181,82,123,0,1,122,0,16,1066740213,5,167,'0',0),(270,89,171,0,37,33,0,12,1066746701,6,147,'',0),(269,89,33,0,36,170,171,12,1066746701,6,147,'',0),(268,89,170,0,35,145,33,12,1066746701,6,147,'',0),(267,89,145,0,34,144,170,12,1066746701,6,147,'',0),(266,89,144,0,33,169,145,12,1066746701,6,147,'',0),(265,89,169,0,32,77,144,12,1066746701,6,147,'',0),(264,89,77,0,31,165,169,12,1066746701,6,147,'',0),(263,89,165,0,30,74,77,12,1066746701,6,147,'',0),(262,89,74,0,29,33,165,12,1066746701,6,147,'',0),(261,89,33,0,28,168,74,12,1066746701,6,147,'',0),(260,89,168,0,27,167,33,12,1066746701,6,147,'',0),(259,89,167,0,26,166,168,12,1066746701,6,147,'',0),(258,89,166,0,25,77,167,12,1066746701,6,147,'',0),(257,89,77,0,24,165,166,12,1066746701,6,147,'',0),(256,89,165,0,23,164,77,12,1066746701,6,147,'',0),(255,89,164,0,22,74,165,12,1066746701,6,147,'',0),(254,89,74,0,21,67,164,12,1066746701,6,147,'',0),(253,89,67,0,20,163,74,12,1066746701,6,147,'',0),(252,89,163,0,19,162,67,12,1066746701,6,147,'',0),(251,89,162,0,18,102,163,12,1066746701,6,147,'',0),(250,89,102,0,17,161,162,12,1066746701,6,147,'',0),(249,89,161,0,16,75,102,12,1066746701,6,147,'',0),(248,89,75,0,15,160,161,12,1066746701,6,147,'',0),(247,89,160,0,14,159,75,12,1066746701,6,147,'',0),(246,89,159,0,13,25,160,12,1066746701,6,147,'',0),(245,89,25,0,12,158,159,12,1066746701,6,147,'',0),(244,89,158,0,11,77,25,12,1066746701,6,147,'',0),(243,89,77,0,10,154,158,12,1066746701,6,147,'',0),(242,89,154,0,9,157,77,12,1066746701,6,147,'',0),(241,89,157,0,8,156,154,12,1066746701,6,147,'',0),(240,89,156,0,7,74,157,12,1066746701,6,147,'',0),(239,89,74,0,6,155,156,12,1066746701,6,147,'',0),(238,89,155,0,5,154,74,12,1066746701,6,147,'',0),(237,89,154,0,4,153,155,12,1066746701,6,147,'',0),(236,89,153,0,3,75,154,12,1066746701,6,147,'',0),(235,89,75,0,2,154,153,12,1066746701,6,147,'',0),(234,89,154,0,1,153,75,12,1066746701,6,146,'',0),(233,89,153,0,0,0,154,12,1066746701,6,146,'',0),(223,90,144,0,0,0,145,12,1066746790,6,146,'',0),(224,90,145,0,1,144,148,12,1066746790,6,146,'',0),(225,90,148,0,2,145,75,12,1066746790,6,146,'',0),(226,90,75,0,3,148,149,12,1066746790,6,147,'',0),(227,90,149,0,4,75,150,12,1066746790,6,147,'',0),(228,90,150,0,5,149,151,12,1066746790,6,147,'',0),(229,90,151,0,6,150,152,12,1066746790,6,147,'',0),(230,90,152,0,7,151,144,12,1066746790,6,147,'',0),(231,90,144,0,8,152,145,12,1066746790,6,147,'',0),(232,90,145,0,9,144,0,12,1066746790,6,147,'',0),(1402,92,144,0,0,0,720,2,1066828821,4,1,'',0),(1403,92,720,0,1,144,721,2,1066828821,4,1,'',0),(1602,92,819,0,200,818,820,2,1066828821,4,121,'',0),(1601,92,818,0,199,102,819,2,1066828821,4,121,'',0),(1600,92,102,0,198,817,818,2,1066828821,4,121,'',0),(1599,92,817,0,197,816,102,2,1066828821,4,121,'',0),(1598,92,816,0,196,727,817,2,1066828821,4,121,'',0),(1597,92,727,0,195,227,816,2,1066828821,4,121,'',0),(1596,92,227,0,194,815,727,2,1066828821,4,121,'',0),(1595,92,815,0,193,814,227,2,1066828821,4,121,'',0),(1594,92,814,0,192,254,815,2,1066828821,4,121,'',0),(1593,92,254,0,191,813,814,2,1066828821,4,121,'',0),(1592,92,813,0,190,774,254,2,1066828821,4,121,'',0),(1591,92,774,0,189,33,813,2,1066828821,4,121,'',0),(1590,92,33,0,188,812,774,2,1066828821,4,121,'',0),(1589,92,812,0,187,89,33,2,1066828821,4,121,'',0),(1588,92,89,0,186,811,812,2,1066828821,4,121,'',0),(1587,92,811,0,185,152,89,2,1066828821,4,121,'',0),(1586,92,152,0,184,810,811,2,1066828821,4,121,'',0),(1585,92,810,0,183,75,152,2,1066828821,4,121,'',0),(1584,92,75,0,182,809,810,2,1066828821,4,121,'',0),(1583,92,809,0,181,102,75,2,1066828821,4,121,'',0),(1582,92,102,0,180,808,809,2,1066828821,4,121,'',0),(1581,92,808,0,179,807,102,2,1066828821,4,121,'',0),(1580,92,807,0,178,427,808,2,1066828821,4,121,'',0),(1579,92,427,0,177,772,807,2,1066828821,4,121,'',0),(1578,92,772,0,176,775,427,2,1066828821,4,121,'',0),(1577,92,775,0,175,774,772,2,1066828821,4,121,'',0),(1576,92,774,0,174,156,775,2,1066828821,4,121,'',0),(1575,92,156,0,173,806,774,2,1066828821,4,121,'',0),(1574,92,806,0,172,805,156,2,1066828821,4,121,'',0),(1573,92,805,0,171,804,806,2,1066828821,4,121,'',0),(1572,92,804,0,170,803,805,2,1066828821,4,121,'',0),(1571,92,803,0,169,798,804,2,1066828821,4,121,'',0),(1570,92,798,0,168,802,803,2,1066828821,4,121,'',0),(1569,92,802,0,167,801,798,2,1066828821,4,121,'',0),(1568,92,801,0,166,800,802,2,1066828821,4,121,'',0),(1567,92,800,0,165,799,801,2,1066828821,4,121,'',0),(1566,92,799,0,164,798,800,2,1066828821,4,121,'',0),(1565,92,798,0,163,797,799,2,1066828821,4,121,'',0),(1564,92,797,0,162,796,798,2,1066828821,4,121,'',0),(1563,92,796,0,161,795,797,2,1066828821,4,121,'',0),(1562,92,795,0,160,33,796,2,1066828821,4,121,'',0),(1561,92,33,0,159,414,795,2,1066828821,4,121,'',0),(1560,92,414,0,158,775,33,2,1066828821,4,121,'',0),(1559,92,775,0,157,774,414,2,1066828821,4,121,'',0),(1558,92,774,0,156,254,775,2,1066828821,4,121,'',0),(1557,92,254,0,155,794,774,2,1066828821,4,121,'',0),(1556,92,794,0,154,793,254,2,1066828821,4,121,'',0),(1555,92,793,0,153,727,794,2,1066828821,4,121,'',0),(1554,92,727,0,152,764,793,2,1066828821,4,121,'',0),(1553,92,764,0,151,180,727,2,1066828821,4,121,'',0),(1552,92,180,0,150,792,764,2,1066828821,4,121,'',0),(1551,92,792,0,149,791,180,2,1066828821,4,121,'',0),(1550,92,791,0,148,152,792,2,1066828821,4,121,'',0),(1549,92,152,0,147,790,791,2,1066828821,4,121,'',0),(1548,92,790,0,146,789,152,2,1066828821,4,121,'',0),(1547,92,789,0,145,75,790,2,1066828821,4,121,'',0),(1546,92,75,0,144,788,789,2,1066828821,4,121,'',0),(1545,92,788,0,143,787,75,2,1066828821,4,121,'',0),(1544,92,787,0,142,33,788,2,1066828821,4,121,'',0),(1543,92,33,0,141,786,787,2,1066828821,4,121,'',0),(1542,92,786,0,140,56,33,2,1066828821,4,121,'',0),(1541,92,56,0,139,33,786,2,1066828821,4,121,'',0),(1540,92,33,0,138,785,56,2,1066828821,4,121,'',0),(1539,92,785,0,137,784,33,2,1066828821,4,121,'',0),(1538,92,784,0,136,783,785,2,1066828821,4,121,'',0),(1537,92,783,0,135,727,784,2,1066828821,4,121,'',0),(1536,92,727,0,134,782,783,2,1066828821,4,121,'',0),(1535,92,782,0,133,152,727,2,1066828821,4,121,'',0),(1534,92,152,0,132,769,782,2,1066828821,4,121,'',0),(1533,92,769,0,131,781,152,2,1066828821,4,121,'',0),(1532,92,781,0,130,33,769,2,1066828821,4,121,'',0),(1531,92,33,0,129,728,781,2,1066828821,4,121,'',0),(1530,92,728,0,128,108,33,2,1066828821,4,121,'',0),(1529,92,108,0,127,780,728,2,1066828821,4,121,'',0),(1528,92,780,0,126,727,108,2,1066828821,4,121,'',0),(1527,92,727,0,125,779,780,2,1066828821,4,121,'',0),(1526,92,779,0,124,75,727,2,1066828821,4,121,'',0),(1525,92,75,0,123,778,779,2,1066828821,4,121,'',0),(1524,92,778,0,122,777,75,2,1066828821,4,121,'',0),(1523,92,777,0,121,33,778,2,1066828821,4,121,'',0),(1522,92,33,0,120,235,777,2,1066828821,4,121,'',0),(1521,92,235,0,119,776,33,2,1066828821,4,121,'',0),(1520,92,776,0,118,25,235,2,1066828821,4,121,'',0),(1519,92,25,0,117,775,776,2,1066828821,4,121,'',0),(1518,92,775,0,116,774,25,2,1066828821,4,121,'',0),(1517,92,774,0,115,156,775,2,1066828821,4,121,'',0),(1516,92,156,0,114,74,774,2,1066828821,4,121,'',0),(1515,92,74,0,113,773,156,2,1066828821,4,121,'',0),(1514,92,773,0,112,145,74,2,1066828821,4,121,'',0),(1513,92,145,0,111,144,773,2,1066828821,4,121,'',0),(1512,92,144,0,110,772,145,2,1066828821,4,121,'',0),(1511,92,772,0,109,771,144,2,1066828821,4,121,'',0),(1510,92,771,0,108,752,772,2,1066828821,4,121,'',0),(1509,92,752,0,107,152,771,2,1066828821,4,121,'',0),(1508,92,152,0,106,770,752,2,1066828821,4,121,'',0),(1507,92,770,0,105,180,152,2,1066828821,4,121,'',0),(1506,92,180,0,104,759,770,2,1066828821,4,121,'',0),(1505,92,759,0,103,766,180,2,1066828821,4,121,'',0),(1504,92,766,0,102,227,759,2,1066828821,4,121,'',0),(1503,92,227,0,101,764,766,2,1066828821,4,121,'',0),(1502,92,764,0,100,769,227,2,1066828821,4,121,'',0),(1501,92,769,0,99,727,764,2,1066828821,4,121,'',0),(1500,92,727,0,98,768,769,2,1066828821,4,121,'',0),(1499,92,768,0,97,224,727,2,1066828821,4,121,'',0),(1498,92,224,0,96,767,768,2,1066828821,4,121,'',0),(1497,92,767,0,95,67,224,2,1066828821,4,121,'',0),(1496,92,67,0,94,766,767,2,1066828821,4,121,'',0),(1495,92,766,0,93,221,67,2,1066828821,4,121,'',0),(1494,92,221,0,92,765,766,2,1066828821,4,121,'',0),(1493,92,765,0,91,759,221,2,1066828821,4,121,'',0),(1492,92,759,0,90,764,765,2,1066828821,4,121,'',0),(1491,92,764,0,89,152,759,2,1066828821,4,121,'',0),(1490,92,152,0,88,763,764,2,1066828821,4,121,'',0),(1489,92,763,0,87,89,152,2,1066828821,4,121,'',0),(1488,92,89,0,86,33,763,2,1066828821,4,121,'',0),(1487,92,33,0,85,762,89,2,1066828821,4,121,'',0),(1486,92,762,0,84,89,33,2,1066828821,4,121,'',0),(1485,92,89,0,83,381,762,2,1066828821,4,121,'',0),(1484,92,381,0,82,761,89,2,1066828821,4,121,'',0),(1483,92,761,0,81,760,381,2,1066828821,4,121,'',0),(1482,92,760,0,80,759,761,2,1066828821,4,121,'',0),(1481,92,759,0,79,86,760,2,1066828821,4,121,'',0),(1480,92,86,0,78,758,759,2,1066828821,4,121,'',0),(1479,92,758,0,77,81,86,2,1066828821,4,121,'',0),(1478,92,81,0,76,757,758,2,1066828821,4,121,'',0),(1477,92,757,0,75,756,81,2,1066828821,4,121,'',0),(1476,92,756,0,74,755,757,2,1066828821,4,121,'',0),(1475,92,755,0,73,727,756,2,1066828821,4,121,'',0),(1474,92,727,0,72,754,755,2,1066828821,4,121,'',0),(1473,92,754,0,71,753,727,2,1066828821,4,121,'',0),(1472,92,753,0,70,752,754,2,1066828821,4,121,'',0),(1471,92,752,0,69,735,753,2,1066828821,4,121,'',0),(1470,92,735,0,68,75,752,2,1066828821,4,120,'',0),(1469,92,75,0,67,108,735,2,1066828821,4,120,'',0),(1468,92,108,0,66,751,75,2,1066828821,4,120,'',0),(1467,92,751,0,65,722,108,2,1066828821,4,120,'',0),(1466,92,722,0,64,89,751,2,1066828821,4,120,'',0),(1465,92,89,0,63,750,722,2,1066828821,4,120,'',0),(1464,92,750,0,62,749,89,2,1066828821,4,120,'',0),(1463,92,749,0,61,748,750,2,1066828821,4,120,'',0),(1462,92,748,0,60,75,749,2,1066828821,4,120,'',0),(1461,92,75,0,59,732,748,2,1066828821,4,120,'',0),(1460,92,732,0,58,747,75,2,1066828821,4,120,'',0),(1459,92,747,0,57,152,732,2,1066828821,4,120,'',0),(1458,92,152,0,56,746,747,2,1066828821,4,120,'',0),(1457,92,746,0,55,75,152,2,1066828821,4,120,'',0),(1456,92,75,0,54,102,746,2,1066828821,4,120,'',0),(1455,92,102,0,53,745,75,2,1066828821,4,120,'',0),(1454,92,745,0,52,75,102,2,1066828821,4,120,'',0),(1453,92,75,0,51,108,745,2,1066828821,4,120,'',0),(1452,92,108,0,50,744,75,2,1066828821,4,120,'',0),(1451,92,744,0,49,743,108,2,1066828821,4,120,'',0),(1450,92,743,0,48,727,744,2,1066828821,4,120,'',0),(1449,92,727,0,47,742,743,2,1066828821,4,120,'',0),(1448,92,742,0,46,720,727,2,1066828821,4,120,'',0),(1447,92,720,0,45,144,742,2,1066828821,4,120,'',0),(1446,92,144,0,44,741,720,2,1066828821,4,120,'',0),(1445,92,741,0,43,740,144,2,1066828821,4,120,'',0),(1444,92,740,0,42,739,741,2,1066828821,4,120,'',0),(1443,92,739,0,41,75,740,2,1066828821,4,120,'',0),(1442,92,75,0,40,77,739,2,1066828821,4,120,'',0),(1441,92,77,0,39,738,75,2,1066828821,4,120,'',0),(1440,92,738,0,38,74,77,2,1066828821,4,120,'',0),(1439,92,74,0,37,735,738,2,1066828821,4,120,'',0),(1438,92,735,0,36,734,74,2,1066828821,4,120,'',0),(1437,92,734,0,35,75,735,2,1066828821,4,120,'',0),(1436,92,75,0,34,6,734,2,1066828821,4,120,'',0),(1435,92,6,0,33,195,75,2,1066828821,4,120,'',0),(1434,92,195,0,32,33,6,2,1066828821,4,120,'',0),(1433,92,33,0,31,737,195,2,1066828821,4,120,'',0),(1432,92,737,0,30,736,33,2,1066828821,4,120,'',0),(1431,92,736,0,29,465,737,2,1066828821,4,120,'',0),(1430,92,465,0,28,77,736,2,1066828821,4,120,'',0),(1429,92,77,0,27,735,465,2,1066828821,4,120,'',0),(1428,92,735,0,26,734,77,2,1066828821,4,120,'',0),(1427,92,734,0,25,733,735,2,1066828821,4,120,'',0),(1426,92,733,0,24,156,734,2,1066828821,4,120,'',0),(1425,92,156,0,23,74,733,2,1066828821,4,120,'',0),(1424,92,74,0,22,732,156,2,1066828821,4,120,'',0),(1423,92,732,0,21,720,74,2,1066828821,4,120,'',0),(1422,92,720,0,20,731,732,2,1066828821,4,120,'',0),(1421,92,731,0,19,730,720,2,1066828821,4,120,'',0),(1420,92,730,0,18,729,731,2,1066828821,4,120,'',0),(1419,92,729,0,17,728,730,2,1066828821,4,120,'',0),(1418,92,728,0,16,183,729,2,1066828821,4,120,'',0),(1417,92,183,0,15,727,728,2,1066828821,4,120,'',0),(1416,92,727,0,14,726,183,2,1066828821,4,120,'',0),(1415,92,726,0,13,144,727,2,1066828821,4,120,'',0),(1414,92,144,0,12,75,726,2,1066828821,4,120,'',0),(1413,92,75,0,11,152,144,2,1066828821,4,120,'',0),(1412,92,152,0,10,725,75,2,1066828821,4,120,'',0),(1411,92,725,0,9,180,152,2,1066828821,4,120,'',0),(1410,92,180,0,8,724,725,2,1066828821,4,120,'',0),(1409,92,724,0,7,73,180,2,1066828821,4,120,'',0),(1408,92,73,0,6,723,724,2,1066828821,4,120,'',0),(1407,92,723,0,5,108,73,2,1066828821,4,1,'',0),(1406,92,108,0,4,722,723,2,1066828821,4,1,'',0),(1405,92,722,0,3,721,108,2,1066828821,4,1,'',0),(2083,93,1047,0,125,33,0,2,1066828903,4,121,'',0),(2082,93,33,0,124,1046,1047,2,1066828903,4,121,'',0),(2081,93,1046,0,123,331,33,2,1066828903,4,121,'',0),(2080,93,331,0,122,33,1046,2,1066828903,4,121,'',0),(2079,93,33,0,121,1040,331,2,1066828903,4,121,'',0),(2078,93,1040,0,120,1045,33,2,1066828903,4,121,'',0),(2077,93,1045,0,119,33,1040,2,1066828903,4,121,'',0),(2076,93,33,0,118,1044,1045,2,1066828903,4,121,'',0),(2075,93,1044,0,117,1043,33,2,1066828903,4,121,'',0),(2074,93,1043,0,116,77,1044,2,1066828903,4,121,'',0),(2073,93,77,0,115,1040,1043,2,1066828903,4,121,'',0),(2072,93,1040,0,114,1015,77,2,1066828903,4,121,'',0),(2071,93,1015,0,113,1042,1040,2,1066828903,4,121,'',0),(2070,93,1042,0,112,33,1015,2,1066828903,4,121,'',0),(2069,93,33,0,111,1041,1042,2,1066828903,4,121,'',0),(2068,93,1041,0,110,77,33,2,1066828903,4,121,'',0),(2067,93,77,0,109,1040,1041,2,1066828903,4,121,'',0),(2066,93,1040,0,108,195,77,2,1066828903,4,121,'',0),(2065,93,195,0,107,1039,1040,2,1066828903,4,121,'',0),(2064,93,1039,0,106,1038,195,2,1066828903,4,121,'',0),(2063,93,1038,0,105,75,1039,2,1066828903,4,121,'',0),(2062,93,75,0,104,77,1038,2,1066828903,4,121,'',0),(2061,93,77,0,103,1037,75,2,1066828903,4,121,'',0),(2060,93,1037,0,102,195,77,2,1066828903,4,121,'',0),(2059,93,195,0,101,1035,1037,2,1066828903,4,121,'',0),(2058,93,1035,0,100,1036,195,2,1066828903,4,121,'',0),(2057,93,1036,0,99,1035,1035,2,1066828903,4,121,'',0),(2056,93,1035,0,98,1034,1036,2,1066828903,4,121,'',0),(2055,93,1034,0,97,1033,1035,2,1066828903,4,121,'',0),(2054,93,1033,0,96,25,1034,2,1066828903,4,121,'',0),(2053,93,25,0,95,183,1033,2,1066828903,4,121,'',0),(2052,93,183,0,94,1032,25,2,1066828903,4,121,'',0),(2051,93,1032,0,93,1031,183,2,1066828903,4,121,'',0),(2050,93,1031,0,92,254,1032,2,1066828903,4,121,'',0),(2049,93,254,0,91,1030,1031,2,1066828903,4,121,'',0),(2048,93,1030,0,90,1024,254,2,1066828903,4,121,'',0),(2047,93,1024,0,89,1029,1030,2,1066828903,4,121,'',0),(2046,93,1029,0,88,33,1024,2,1066828903,4,121,'',0),(2045,93,33,0,87,1028,1029,2,1066828903,4,121,'',0),(2044,93,1028,0,86,75,33,2,1066828903,4,121,'',0),(2043,93,75,0,85,1027,1028,2,1066828903,4,121,'',0),(2042,93,1027,0,84,1026,75,2,1066828903,4,121,'',0),(2041,93,1026,0,83,75,1027,2,1066828903,4,121,'',0),(2040,93,75,0,82,254,1026,2,1066828903,4,121,'',0),(2039,93,254,0,81,1025,75,2,1066828903,4,121,'',0),(2038,93,1025,0,80,1024,254,2,1066828903,4,121,'',0),(2037,93,1024,0,79,1023,1025,2,1066828903,4,121,'',0),(2036,93,1023,0,78,1022,1024,2,1066828903,4,121,'',0),(2035,93,1022,0,77,75,1023,2,1066828903,4,121,'',0),(2034,93,75,0,76,183,1022,2,1066828903,4,121,'',0),(2033,93,183,0,75,294,75,2,1066828903,4,121,'',0),(2032,93,294,0,74,1021,183,2,1066828903,4,121,'',0),(2031,93,1021,0,73,33,294,2,1066828903,4,121,'',0),(2030,93,33,0,72,1020,1021,2,1066828903,4,121,'',0),(2029,93,1020,0,71,1019,33,2,1066828903,4,121,'',0),(2028,93,1019,0,70,303,1020,2,1066828903,4,121,'',0),(2027,93,303,0,69,1010,1019,2,1066828903,4,121,'',0),(2026,93,1010,0,68,292,303,2,1066828903,4,121,'',0),(2025,93,292,0,67,102,1010,2,1066828903,4,121,'',0),(2024,93,102,0,66,1018,292,2,1066828903,4,121,'',0),(2023,93,1018,0,65,254,102,2,1066828903,4,121,'',0),(2022,93,254,0,64,1017,1018,2,1066828903,4,121,'',0),(2021,93,1017,0,63,1016,254,2,1066828903,4,121,'',0),(2020,93,1016,0,62,180,1017,2,1066828903,4,121,'',0),(2019,93,180,0,61,1015,1016,2,1066828903,4,121,'',0),(2018,93,1015,0,60,1014,180,2,1066828903,4,121,'',0),(2017,93,1014,0,59,1005,1015,2,1066828903,4,121,'',0),(2016,93,1005,0,58,1013,1014,2,1066828903,4,121,'',0),(2015,93,1013,0,57,1012,1005,2,1066828903,4,121,'',0),(2014,93,1012,0,56,75,1013,2,1066828903,4,121,'',0),(2013,93,75,0,55,1011,1012,2,1066828903,4,121,'',0),(2012,93,1011,0,54,77,75,2,1066828903,4,121,'',0),(2011,93,77,0,53,294,1011,2,1066828903,4,121,'',0),(2010,93,294,0,52,254,77,2,1066828903,4,121,'',0),(2009,93,254,0,51,235,294,2,1066828903,4,121,'',0),(2008,93,235,0,50,1010,254,2,1066828903,4,121,'',0),(2007,93,1010,0,49,292,235,2,1066828903,4,121,'',0),(2006,93,292,0,48,1009,1010,2,1066828903,4,121,'',0),(2005,93,1009,0,47,842,292,2,1066828903,4,121,'',0),(2004,93,842,0,46,148,1009,2,1066828903,4,121,'',0),(2003,93,148,0,45,77,842,2,1066828903,4,121,'',0),(2002,93,77,0,44,1008,148,2,1066828903,4,121,'',0),(2001,93,1008,0,43,999,77,2,1066828903,4,121,'',0),(2000,93,999,0,42,999,1008,2,1066828903,4,121,'',0),(1999,93,999,0,41,1007,999,2,1066828903,4,120,'',0),(1998,93,1007,0,40,75,999,2,1066828903,4,120,'',0),(1997,93,75,0,39,183,1007,2,1066828903,4,120,'',0),(1996,93,183,0,38,1006,75,2,1066828903,4,120,'',0),(1995,93,1006,0,37,1005,183,2,1066828903,4,120,'',0),(1994,93,1005,0,36,224,1006,2,1066828903,4,120,'',0),(1993,93,224,0,35,1004,1005,2,1066828903,4,120,'',0),(1992,93,1004,0,34,75,224,2,1066828903,4,120,'',0),(1991,93,75,0,33,152,1004,2,1066828903,4,120,'',0),(1990,93,152,0,32,180,75,2,1066828903,4,120,'',0),(1989,93,180,0,31,1003,152,2,1066828903,4,120,'',0),(1988,93,1003,0,30,999,180,2,1066828903,4,120,'',0),(1987,93,999,0,29,73,1003,2,1066828903,4,120,'',0),(1986,93,73,0,28,148,999,2,1066828903,4,120,'',0),(1985,93,148,0,27,145,73,2,1066828903,4,120,'',0),(1984,93,145,0,26,144,148,2,1066828903,4,120,'',0),(1983,93,144,0,25,152,145,2,1066828903,4,120,'',0),(1982,93,152,0,24,1002,144,2,1066828903,4,120,'',0),(1981,93,1002,0,23,81,152,2,1066828903,4,120,'',0),(1980,93,81,0,22,77,1002,2,1066828903,4,120,'',0),(1979,93,77,0,21,1001,81,2,1066828903,4,120,'',0),(1978,93,1001,0,20,74,77,2,1066828903,4,120,'',0),(1977,93,74,0,19,67,1001,2,1066828903,4,120,'',0),(1976,93,67,0,18,33,74,2,1066828903,4,120,'',0),(1975,93,33,0,17,999,67,2,1066828903,4,120,'',0),(1974,93,999,0,16,148,33,2,1066828903,4,120,'',0),(1973,93,148,0,15,150,999,2,1066828903,4,120,'',0),(1972,93,150,0,14,75,148,2,1066828903,4,120,'',0),(1971,93,75,0,13,152,150,2,1066828903,4,120,'',0),(1970,93,152,0,12,1000,75,2,1066828903,4,120,'',0),(1969,93,1000,0,11,156,152,2,1066828903,4,120,'',0),(1968,93,156,0,10,74,1000,2,1066828903,4,120,'',0),(1967,93,74,0,9,842,156,2,1066828903,4,120,'',0),(1966,93,842,0,8,148,74,2,1066828903,4,120,'',0),(1965,93,148,0,7,145,842,2,1066828903,4,120,'',0),(1964,93,145,0,6,144,148,2,1066828903,4,120,'',0),(1963,93,144,0,5,999,145,2,1066828903,4,120,'',0),(1962,93,999,0,4,842,144,2,1066828903,4,1,'',0),(1961,93,842,0,3,148,999,2,1066828903,4,1,'',0),(1960,93,148,0,2,145,842,2,1066828903,4,1,'',0),(1959,93,145,0,1,144,148,2,1066828903,4,1,'',0),(1958,93,144,0,0,0,145,2,1066828903,4,1,'',0),(1078,94,335,0,15,74,0,2,1066829047,4,121,'',0),(1077,94,74,0,14,552,335,2,1066829047,4,121,'',0),(1076,94,552,0,13,551,74,2,1066829047,4,121,'',0),(1075,94,551,0,12,108,552,2,1066829047,4,121,'',0),(1074,94,108,0,11,74,551,2,1066829047,4,120,'',0),(1073,94,74,0,10,227,108,2,1066829047,4,120,'',0),(1072,94,227,0,9,550,74,2,1066829047,4,120,'',0),(1071,94,550,0,8,195,227,2,1066829047,4,120,'',0),(1070,94,195,0,7,89,550,2,1066829047,4,120,'',0),(1069,94,89,0,6,549,195,2,1066829047,4,120,'',0),(1068,94,549,0,5,86,89,2,1066829047,4,120,'',0),(1067,94,86,0,4,221,549,2,1066829047,4,120,'',0),(1066,94,221,0,3,548,86,2,1066829047,4,1,'',0),(1065,94,548,0,2,335,221,2,1066829047,4,1,'',0),(1064,94,335,0,1,334,548,2,1066829047,4,1,'',0),(1063,94,334,0,0,0,335,2,1066829047,4,1,'',0),(1404,92,721,0,2,720,722,2,1066828821,4,1,'',0),(822,64,381,0,1,440,441,1,1066729319,6,119,'',0),(823,64,441,0,2,381,427,1,1066729319,6,119,'',0),(824,64,427,0,3,441,442,1,1066729319,6,119,'',0),(825,64,442,0,4,427,81,1,1066729319,6,119,'',0),(826,64,81,0,5,442,440,1,1066729319,6,119,'',0),(827,64,440,0,6,81,0,1,1066729319,6,119,'',0),(1778,119,144,0,0,0,899,16,1067006564,5,162,'',0),(1779,119,899,0,1,144,123,16,1067006564,5,162,'',0),(1628,97,836,0,7,835,0,17,1066898315,5,172,'contact_value',98802246),(1627,97,835,0,6,447,836,17,1066898315,5,172,'contact_value',0),(1626,97,447,0,5,446,835,17,1066898315,5,172,'contact_type',0),(1625,97,446,0,4,834,447,17,1066898315,5,172,'contact_type',0),(1623,97,414,0,2,833,834,17,1066898315,5,171,'',0),(1624,97,834,0,3,414,446,17,1066898315,5,171,'',0),(1603,92,820,0,201,819,0,2,1066828821,4,121,'',0),(1786,119,903,0,8,902,123,16,1067006564,5,166,'',0),(1785,119,902,0,7,901,903,16,1067006564,5,164,'address_value',1234),(1784,119,901,0,6,900,902,16,1067006564,5,164,'address_value',0),(1783,119,900,0,5,842,901,16,1067006564,5,164,'address_value',0),(1782,119,842,0,4,898,900,16,1067006564,5,164,'address_type',2),(1781,119,898,0,3,123,842,16,1067006564,5,164,'address_type',1),(1780,119,123,0,2,899,898,16,1067006564,5,164,'address_type',0),(1636,102,841,0,7,840,0,17,1066911635,5,172,'contact_value',66667777),(1635,102,840,0,6,447,841,17,1066911635,5,172,'contact_value',0),(1634,102,447,0,5,446,840,17,1066911635,5,172,'contact_type',0),(1633,102,446,0,4,839,447,17,1066911635,5,172,'contact_type',0),(1632,102,839,0,3,294,446,17,1066911635,5,171,'',0),(1631,102,294,0,2,838,839,17,1066911635,5,171,'',0),(1622,97,833,0,1,832,414,17,1066898315,5,169,'',0),(1621,97,832,0,0,0,833,17,1066898315,5,170,'',0),(1630,102,838,0,1,837,294,17,1066911635,5,169,'',0),(1629,102,837,0,0,0,838,17,1066911635,5,170,'',0),(1106,107,571,0,1,570,0,4,1066916865,2,9,'',0),(1105,107,570,0,0,0,571,4,1066916865,2,8,'',0),(1107,111,572,0,0,0,573,4,1066917523,2,8,'',0),(1108,111,573,0,1,572,0,4,1066917523,2,9,'',0),(1109,112,465,0,0,0,331,1,1066986270,1,4,'',0),(1110,112,331,0,1,465,465,1,1066986270,1,119,'',0),(1111,112,465,0,2,331,574,1,1066986270,1,119,'',0),(1112,112,574,0,3,465,75,1,1066986270,1,119,'',0),(1113,112,75,0,4,574,61,1,1066986270,1,119,'',0),(1114,112,61,0,5,75,0,1,1066986270,1,119,'',0),(1115,113,62,0,0,0,575,10,1066986541,1,140,'',0),(1116,113,575,0,1,62,576,10,1066986541,1,141,'',0),(1117,113,576,0,2,575,577,10,1066986541,1,141,'',0),(1118,113,577,0,3,576,578,10,1066986541,1,141,'',0),(1119,113,578,0,4,577,579,10,1066986541,1,141,'',0),(1120,113,579,0,5,578,580,10,1066986541,1,141,'',0),(1121,113,580,0,6,579,581,10,1066986541,1,141,'',0),(1122,113,581,0,7,580,580,10,1066986541,1,141,'',0),(1123,113,580,0,8,581,582,10,1066986541,1,141,'',0),(1124,113,582,0,9,580,583,10,1066986541,1,141,'',0),(1125,113,583,0,10,582,584,10,1066986541,1,141,'',0),(1126,113,584,0,11,583,585,10,1066986541,1,141,'',0),(1127,113,585,0,12,584,586,10,1066986541,1,141,'',0),(1128,113,586,0,13,585,22,10,1066986541,1,141,'',0),(1129,113,22,0,14,586,587,10,1066986541,1,141,'',0),(1130,113,587,0,15,22,588,10,1066986541,1,141,'',0),(1131,113,588,0,16,587,589,10,1066986541,1,141,'',0),(1132,113,589,0,17,588,590,10,1066986541,1,141,'',0),(1133,113,590,0,18,589,591,10,1066986541,1,141,'',0),(1134,113,591,0,19,590,592,10,1066986541,1,141,'',0),(1135,113,592,0,20,591,593,10,1066986541,1,141,'',0),(1136,113,593,0,21,592,594,10,1066986541,1,141,'',0),(1137,113,594,0,22,593,595,10,1066986541,1,141,'',0),(1138,113,595,0,23,594,593,10,1066986541,1,141,'',0),(1139,113,593,0,24,595,596,10,1066986541,1,141,'',0),(1140,113,596,0,25,593,597,10,1066986541,1,141,'',0),(1141,113,597,0,26,596,598,10,1066986541,1,141,'',0),(1142,113,598,0,27,597,586,10,1066986541,1,141,'',0),(1143,113,586,0,28,598,599,10,1066986541,1,141,'',0),(1144,113,599,0,29,586,600,10,1066986541,1,141,'',0),(1145,113,600,0,30,599,585,10,1066986541,1,141,'',0),(1146,113,585,0,31,600,598,10,1066986541,1,141,'',0),(1147,113,598,0,32,585,601,10,1066986541,1,141,'',0),(1148,113,601,0,33,598,602,10,1066986541,1,141,'',0),(1149,113,602,0,34,601,603,10,1066986541,1,141,'',0),(1150,113,603,0,35,602,604,10,1066986541,1,141,'',0),(1151,113,604,0,36,603,605,10,1066986541,1,141,'',0),(1152,113,605,0,37,604,606,10,1066986541,1,141,'',0),(1153,113,606,0,38,605,22,10,1066986541,1,141,'',0),(1154,113,22,0,39,606,587,10,1066986541,1,141,'',0),(1155,113,587,0,40,22,588,10,1066986541,1,141,'',0),(1156,113,588,0,41,587,589,10,1066986541,1,141,'',0),(1157,113,589,0,42,588,590,10,1066986541,1,141,'',0),(1158,113,590,0,43,589,591,10,1066986541,1,141,'',0),(1159,113,591,0,44,590,592,10,1066986541,1,141,'',0),(1160,113,592,0,45,591,593,10,1066986541,1,141,'',0),(1161,113,593,0,46,592,594,10,1066986541,1,141,'',0),(1162,113,594,0,47,593,595,10,1066986541,1,141,'',0),(1163,113,595,0,48,594,593,10,1066986541,1,141,'',0),(1164,113,593,0,49,595,596,10,1066986541,1,141,'',0),(1165,113,596,0,50,593,597,10,1066986541,1,141,'',0),(1166,113,597,0,51,596,607,10,1066986541,1,141,'',0),(1167,113,607,0,52,597,608,10,1066986541,1,141,'',0),(1168,113,608,0,53,607,609,10,1066986541,1,141,'',0),(1169,113,609,0,54,608,610,10,1066986541,1,141,'',0),(1170,113,610,0,55,609,580,10,1066986541,1,141,'',0),(1171,113,580,0,56,610,611,10,1066986541,1,141,'',0),(1172,113,611,0,57,580,601,10,1066986541,1,141,'',0),(1173,113,601,0,58,611,612,10,1066986541,1,141,'',0),(1174,113,612,0,59,601,580,10,1066986541,1,141,'',0),(1175,113,580,0,60,612,613,10,1066986541,1,141,'',0),(1176,113,613,0,61,580,614,10,1066986541,1,141,'',0),(1177,113,614,0,62,613,615,10,1066986541,1,141,'',0),(1178,113,615,0,63,614,616,10,1066986541,1,141,'',0),(1179,113,616,0,64,615,617,10,1066986541,1,141,'',0),(1180,113,617,0,65,616,601,10,1066986541,1,141,'',0),(1181,113,601,0,66,617,618,10,1066986541,1,141,'',0),(1182,113,618,0,67,601,619,10,1066986541,1,141,'',0),(1183,113,619,0,68,618,601,10,1066986541,1,141,'',0),(1184,113,601,0,69,619,611,10,1066986541,1,141,'',0),(1185,113,611,0,70,601,620,10,1066986541,1,141,'',0),(1186,113,620,0,71,611,621,10,1066986541,1,141,'',0),(1187,113,621,0,72,620,622,10,1066986541,1,141,'',0),(1188,113,622,0,73,621,623,10,1066986541,1,141,'',0),(1189,113,623,0,74,622,624,10,1066986541,1,141,'',0),(1190,113,624,0,75,623,625,10,1066986541,1,141,'',0),(1191,113,625,0,76,624,626,10,1066986541,1,141,'',0),(1192,113,626,0,77,625,627,10,1066986541,1,141,'',0),(1193,113,627,0,78,626,585,10,1066986541,1,141,'',0),(1194,113,585,0,79,627,628,10,1066986541,1,141,'',0),(1195,113,628,0,80,585,629,10,1066986541,1,141,'',0),(1196,113,629,0,81,628,630,10,1066986541,1,141,'',0),(1197,113,630,0,82,629,586,10,1066986541,1,141,'',0),(1198,113,586,0,83,630,631,10,1066986541,1,141,'',0),(1199,113,631,0,84,586,632,10,1066986541,1,141,'',0),(1200,113,632,0,85,631,633,10,1066986541,1,141,'',0),(1201,113,633,0,86,632,634,10,1066986541,1,141,'',0),(1202,113,634,0,87,633,635,10,1066986541,1,141,'',0),(1203,113,635,0,88,634,578,10,1066986541,1,141,'',0),(1204,113,578,0,89,635,636,10,1066986541,1,141,'',0),(1205,113,636,0,90,578,614,10,1066986541,1,141,'',0),(1206,113,614,0,91,636,637,10,1066986541,1,141,'',0),(1207,113,637,0,92,614,638,10,1066986541,1,141,'',0),(1208,113,638,0,93,637,639,10,1066986541,1,141,'',0),(1209,113,639,0,94,638,640,10,1066986541,1,141,'',0),(1210,113,640,0,95,639,641,10,1066986541,1,141,'',0),(1211,113,641,0,96,640,642,10,1066986541,1,141,'',0),(1212,113,642,0,97,641,643,10,1066986541,1,141,'',0),(1213,113,643,0,98,642,644,10,1066986541,1,141,'',0),(1214,113,644,0,99,643,578,10,1066986541,1,141,'',0),(1215,113,578,0,100,644,624,10,1066986541,1,141,'',0),(1216,113,624,0,101,578,645,10,1066986541,1,141,'',0),(1217,113,645,0,102,624,646,10,1066986541,1,141,'',0),(1218,113,646,0,103,645,647,10,1066986541,1,141,'',0),(1219,113,647,0,104,646,648,10,1066986541,1,141,'',0),(1220,113,648,0,105,647,649,10,1066986541,1,141,'',0),(1221,113,649,0,106,648,650,10,1066986541,1,141,'',0),(1222,113,650,0,107,649,616,10,1066986541,1,141,'',0),(1223,113,616,0,108,650,580,10,1066986541,1,141,'',0),(1224,113,580,0,109,616,651,10,1066986541,1,141,'',0),(1225,113,651,0,110,580,644,10,1066986541,1,141,'',0),(1226,113,644,0,111,651,632,10,1066986541,1,141,'',0),(1227,113,632,0,112,644,652,10,1066986541,1,141,'',0),(1228,113,652,0,113,632,611,10,1066986541,1,141,'',0),(1229,113,611,0,114,652,619,10,1066986541,1,141,'',0),(1230,113,619,0,115,611,650,10,1066986541,1,141,'',0),(1231,113,650,0,116,619,653,10,1066986541,1,141,'',0),(1232,113,653,0,117,650,89,10,1066986541,1,141,'',0),(1233,113,89,0,118,653,654,10,1066986541,1,141,'',0),(1234,113,654,0,119,89,580,10,1066986541,1,141,'',0),(1235,113,580,0,120,654,655,10,1066986541,1,141,'',0),(1236,113,655,0,121,580,656,10,1066986541,1,141,'',0),(1237,113,656,0,122,655,624,10,1066986541,1,141,'',0),(1238,113,624,0,123,656,601,10,1066986541,1,141,'',0),(1239,113,601,0,124,624,625,10,1066986541,1,141,'',0),(1240,113,625,0,125,601,627,10,1066986541,1,141,'',0),(1241,113,627,0,126,625,575,10,1066986541,1,141,'',0),(1242,113,575,0,127,627,632,10,1066986541,1,141,'',0),(1243,113,632,0,128,575,651,10,1066986541,1,141,'',0),(1244,113,651,0,129,632,584,10,1066986541,1,141,'',0),(1245,113,584,0,130,651,629,10,1066986541,1,141,'',0),(1246,113,629,0,131,584,657,10,1066986541,1,141,'',0),(1247,113,657,0,132,629,658,10,1066986541,1,141,'',0),(1248,113,658,0,133,657,659,10,1066986541,1,141,'',0),(1249,113,659,0,134,658,660,10,1066986541,1,141,'',0),(1250,113,660,0,135,659,661,10,1066986541,1,141,'',0),(1251,113,661,0,136,660,603,10,1066986541,1,141,'',0),(1252,113,603,0,137,661,631,10,1066986541,1,141,'',0),(1253,113,631,0,138,603,609,10,1066986541,1,141,'',0),(1254,113,609,0,139,631,622,10,1066986541,1,141,'',0),(1255,113,622,0,140,609,638,10,1066986541,1,141,'',0),(1256,113,638,0,141,622,662,10,1066986541,1,141,'',0),(1257,113,662,0,142,638,663,10,1066986541,1,141,'',0),(1258,113,663,0,143,662,664,10,1066986541,1,141,'',0),(1259,113,664,0,144,663,645,10,1066986541,1,141,'',0),(1260,113,645,0,145,664,665,10,1066986541,1,141,'',0),(1261,113,665,0,146,645,628,10,1066986541,1,141,'',0),(1262,113,628,0,147,665,666,10,1066986541,1,141,'',0),(1263,113,666,0,148,628,183,10,1066986541,1,141,'',0),(1264,113,183,0,149,666,645,10,1066986541,1,141,'',0),(1265,113,645,0,150,183,621,10,1066986541,1,141,'',0),(1266,113,621,0,151,645,667,10,1066986541,1,141,'',0),(1267,113,667,0,152,621,625,10,1066986541,1,141,'',0),(1268,113,625,0,153,667,668,10,1066986541,1,141,'',0),(1269,113,668,0,154,625,89,10,1066986541,1,141,'',0),(1270,113,89,0,155,668,599,10,1066986541,1,141,'',0),(1271,113,599,0,156,89,620,10,1066986541,1,141,'',0),(1272,113,620,0,157,599,655,10,1066986541,1,141,'',0),(1273,113,655,0,158,620,614,10,1066986541,1,141,'',0),(1274,113,614,0,159,655,669,10,1066986541,1,141,'',0),(1275,113,669,0,160,614,670,10,1066986541,1,141,'',0),(1276,113,670,0,161,669,612,10,1066986541,1,141,'',0),(1277,113,612,0,162,670,183,10,1066986541,1,141,'',0),(1278,113,183,0,163,612,671,10,1066986541,1,141,'',0),(1279,113,671,0,164,183,632,10,1066986541,1,141,'',0),(1280,113,632,0,165,671,653,10,1066986541,1,141,'',0),(1281,113,653,0,166,632,632,10,1066986541,1,141,'',0),(1282,113,632,0,167,653,609,10,1066986541,1,141,'',0),(1283,113,609,0,168,632,611,10,1066986541,1,141,'',0),(1284,113,611,0,169,609,183,10,1066986541,1,141,'',0),(1285,113,183,0,170,611,640,10,1066986541,1,141,'',0),(1286,113,640,0,171,183,672,10,1066986541,1,141,'',0),(1287,113,672,0,172,640,673,10,1066986541,1,141,'',0),(1288,113,673,0,173,672,619,10,1066986541,1,141,'',0),(1289,113,619,0,174,673,674,10,1066986541,1,141,'',0),(1290,113,674,0,175,619,637,10,1066986541,1,141,'',0),(1291,113,637,0,176,674,582,10,1066986541,1,141,'',0),(1292,113,582,0,177,637,625,10,1066986541,1,141,'',0),(1293,113,625,0,178,582,675,10,1066986541,1,141,'',0),(1294,113,675,0,179,625,637,10,1066986541,1,141,'',0),(1295,113,637,0,180,675,629,10,1066986541,1,141,'',0),(1296,113,629,0,181,637,183,10,1066986541,1,141,'',0),(1297,113,183,0,182,629,676,10,1066986541,1,141,'',0),(1298,113,676,0,183,183,677,10,1066986541,1,141,'',0),(1299,113,677,0,184,676,678,10,1066986541,1,141,'',0),(1300,113,678,0,185,677,679,10,1066986541,1,141,'',0),(1301,113,679,0,186,678,641,10,1066986541,1,141,'',0),(1302,113,641,0,187,679,628,10,1066986541,1,141,'',0),(1303,113,628,0,188,641,680,10,1066986541,1,141,'',0),(1304,113,680,0,189,628,603,10,1066986541,1,141,'',0),(1305,113,603,0,190,680,652,10,1066986541,1,141,'',0),(1306,113,652,0,191,603,620,10,1066986541,1,141,'',0),(1307,113,620,0,192,652,681,10,1066986541,1,141,'',0),(1308,113,681,0,193,620,607,10,1066986541,1,141,'',0),(1309,113,607,0,194,681,584,10,1066986541,1,141,'',0),(1310,113,584,0,195,607,599,10,1066986541,1,141,'',0),(1311,113,599,0,196,584,625,10,1066986541,1,141,'',0),(1312,113,625,0,197,599,682,10,1066986541,1,141,'',0),(1313,113,682,0,198,625,683,10,1066986541,1,141,'',0),(1314,113,683,0,199,682,684,10,1066986541,1,141,'',0),(1315,113,684,0,200,683,577,10,1066986541,1,141,'',0),(1316,113,577,0,201,684,656,10,1066986541,1,141,'',0),(1317,113,656,0,202,577,685,10,1066986541,1,141,'',0),(1318,113,685,0,203,656,641,10,1066986541,1,141,'',0),(1319,113,641,0,204,685,653,10,1066986541,1,141,'',0),(1320,113,653,0,205,641,680,10,1066986541,1,141,'',0),(1321,113,680,0,206,653,632,10,1066986541,1,141,'',0),(1322,113,632,0,207,680,686,10,1066986541,1,141,'',0),(1323,113,686,0,208,632,687,10,1066986541,1,141,'',0),(1324,113,687,0,209,686,621,10,1066986541,1,141,'',0),(1325,113,621,0,210,687,609,10,1066986541,1,141,'',0),(1326,113,609,0,211,621,623,10,1066986541,1,141,'',0),(1327,113,623,0,212,609,652,10,1066986541,1,141,'',0),(1328,113,652,0,213,623,637,10,1066986541,1,141,'',0),(1329,113,637,0,214,652,688,10,1066986541,1,141,'',0),(1330,113,688,0,215,637,615,10,1066986541,1,141,'',0),(1331,113,615,0,216,688,624,10,1066986541,1,141,'',0),(1332,113,624,0,217,615,689,10,1066986541,1,141,'',0),(1333,113,689,0,218,624,690,10,1066986541,1,141,'',0),(1334,113,690,0,219,689,619,10,1066986541,1,141,'',0),(1335,113,619,0,220,690,691,10,1066986541,1,141,'',0),(1336,113,691,0,221,619,692,10,1066986541,1,141,'',0),(1337,113,692,0,222,691,693,10,1066986541,1,141,'',0),(1338,113,693,0,223,692,601,10,1066986541,1,141,'',0),(1339,113,601,0,224,693,680,10,1066986541,1,141,'',0),(1340,113,680,0,225,601,634,10,1066986541,1,141,'',0),(1341,113,634,0,226,680,694,10,1066986541,1,141,'',0),(1342,113,694,0,227,634,624,10,1066986541,1,141,'',0),(1343,113,624,0,228,694,642,10,1066986541,1,141,'',0),(1344,113,642,0,229,624,599,10,1066986541,1,141,'',0),(1345,113,599,0,230,642,647,10,1066986541,1,141,'',0),(1346,113,647,0,231,599,600,10,1066986541,1,141,'',0),(1347,113,600,0,232,647,685,10,1066986541,1,141,'',0),(1348,113,685,0,233,600,0,10,1066986541,1,141,'',0),(1859,45,33,0,1,32,34,14,1066388816,11,152,'',0),(1843,115,303,0,2,7,0,14,1066991725,11,155,'',0),(1842,115,7,0,1,303,303,14,1066991725,11,155,'',0),(1841,115,303,0,0,0,7,14,1066991725,11,152,'',0),(1856,116,937,0,3,25,0,14,1066992054,11,155,'',0),(1855,116,25,0,2,936,937,14,1066992054,11,155,'',0),(1854,116,936,0,1,292,25,14,1066992054,11,152,'',0),(1853,116,292,0,0,0,936,14,1066992054,11,152,'',0),(1858,45,32,0,0,0,33,14,1066388816,11,152,'',0),(2240,136,732,0,7,1136,0,15,1069164104,11,182,'',0),(1875,132,945,0,5,842,946,17,1067417696,5,172,'contact_value',214512345),(1876,132,946,0,6,945,0,17,1067417696,5,172,'contact_value',0),(1913,139,975,0,5,974,976,17,1069336369,5,179,'',0),(1912,139,974,0,4,123,975,17,1069336369,5,172,'contact_value',9934),(1911,139,123,0,3,973,974,17,1069336369,5,172,'contact_type',0),(1910,139,973,0,2,972,123,17,1069336369,5,171,'',0),(1909,139,972,0,1,971,973,17,1069336369,5,169,'',0),(1908,139,971,0,0,0,972,17,1069336369,5,170,'',0),(1903,140,967,0,5,898,968,17,1069337418,5,172,'contact_value',3458),(1902,140,898,0,4,123,967,17,1069337418,5,172,'contact_type',1),(1901,140,123,0,3,966,898,17,1069337418,5,172,'contact_type',0),(1900,140,966,0,2,965,123,17,1069337418,5,171,'',0),(1899,140,965,0,1,964,966,17,1069337418,5,169,'',0),(1898,140,964,0,0,0,965,17,1069337418,5,170,'',0),(1904,140,968,0,6,967,969,17,1069337418,5,179,'',0),(1905,140,969,0,7,968,970,17,1069337418,5,179,'',0),(1906,140,970,0,8,969,969,17,1069337418,5,179,'',0),(1907,140,969,0,9,970,0,17,1069337418,5,179,'',0),(1914,139,976,0,6,975,977,17,1069336369,5,179,'',0),(1915,139,977,0,7,976,978,17,1069336369,5,179,'',0),(1916,139,978,0,8,977,979,17,1069336369,5,179,'',0),(1917,139,979,0,9,978,976,17,1069336369,5,179,'',0),(1918,139,976,0,10,979,978,17,1069336369,5,179,'',0),(1919,139,978,0,11,976,980,17,1069336369,5,179,'',0),(1920,139,980,0,12,978,981,17,1069336369,5,179,'',0),(1921,139,981,0,13,980,978,17,1069336369,5,179,'',0),(1922,139,978,0,14,981,982,17,1069336369,5,179,'',0),(1923,139,982,0,15,978,0,17,1069336369,5,179,'',0),(1924,142,155,0,0,0,0,1,1069405553,6,4,'',0),(1925,144,195,0,0,0,983,2,1069411408,1,1,'',0),(1926,144,983,0,1,195,984,2,1069411408,1,1,'',0),(1927,144,984,0,2,983,985,2,1069411408,1,120,'',0),(1928,144,985,0,3,984,0,2,1069411408,1,121,'',0),(1929,145,905,0,0,0,906,2,1069411438,4,1,'',0),(1930,145,906,0,1,905,976,2,1069411438,4,1,'',0),(1931,145,976,0,2,906,976,2,1069411438,4,120,'',0),(1932,145,976,0,3,976,976,2,1069411438,4,120,'',0),(1933,145,976,0,4,976,980,2,1069411438,4,120,'',0),(1934,145,980,0,5,976,986,2,1069411438,4,120,'',0),(1935,145,986,0,6,980,969,2,1069411438,4,120,'',0),(1936,145,969,0,7,986,987,2,1069411438,4,121,'',0),(1937,145,987,0,8,969,988,2,1069411438,4,121,'',0),(1938,145,988,0,9,987,989,2,1069411438,4,121,'',0),(1939,145,989,0,10,988,970,2,1069411438,4,121,'',0),(1940,145,970,0,11,989,990,2,1069411438,4,121,'',0),(1941,145,990,0,12,970,0,2,1069411438,4,121,'',0),(1942,146,991,0,0,0,992,2,1069411461,4,1,'',0),(1943,146,992,0,1,991,993,2,1069411461,4,120,'',0),(1944,146,993,0,2,992,0,2,1069411461,4,121,'',0),(1945,147,994,0,0,0,995,2,1069414807,4,1,'',0),(1946,147,995,0,1,994,996,2,1069414807,4,120,'',0),(1947,147,996,0,2,995,997,2,1069414807,4,120,'',0),(1948,147,997,0,3,996,998,2,1069414807,4,120,'',0),(1949,147,998,0,4,997,995,2,1069414807,4,120,'',0),(1950,147,995,0,5,998,996,2,1069414807,4,121,'',0),(1951,147,996,0,6,995,997,2,1069414807,4,121,'',0),(1952,147,997,0,7,996,998,2,1069414807,4,121,'',0),(1953,147,998,0,8,997,995,2,1069414807,4,121,'',0),(1954,147,995,0,9,998,996,2,1069414807,4,121,'',0),(1955,147,996,0,10,995,997,2,1069414807,4,121,'',0),(1956,147,997,0,11,996,998,2,1069414807,4,121,'',0),(1957,147,998,0,12,997,0,2,1069414807,4,121,'',0),(2239,136,1136,0,6,1035,732,15,1069164104,11,182,'',0),(2238,136,1035,0,5,720,1136,15,1069164104,11,182,'',0),(2237,136,720,0,4,144,1035,15,1069164104,11,182,'',0),(2236,136,144,0,3,1135,720,15,1069164104,11,182,'',0),(2235,136,1135,0,2,1134,144,15,1069164104,11,182,'',0),(2234,136,1134,0,1,1133,1135,15,1069164104,11,182,'',0),(2233,136,1133,0,0,0,1134,15,1069164104,11,161,'',0),(2092,1,1052,0,0,0,1053,1,1033917596,1,4,'',0),(2093,1,1053,0,1,1052,102,1,1033917596,1,119,'',0),(2094,1,102,0,2,1053,752,1,1033917596,1,119,'',0),(2095,1,752,0,3,102,1052,1,1033917596,1,119,'',0),(2096,1,1052,0,4,752,0,1,1033917596,1,119,'',0),(2126,148,977,0,12,1071,980,17,1069417643,5,179,'',0),(2125,148,1071,0,11,1070,977,17,1069417643,5,179,'',0),(2124,148,1070,0,10,1069,1071,17,1069417643,5,179,'',0),(2123,148,1069,0,9,1068,1070,17,1069417643,5,179,'',0),(2122,148,1068,0,8,1067,1069,17,1069417643,5,179,'',0),(2121,148,1067,0,7,1066,1068,17,1069417643,5,179,'',0),(2120,148,1066,0,6,1065,1067,17,1069417643,5,172,'contact_value',34588),(2119,148,1065,0,5,898,1066,17,1069417643,5,172,'contact_value',99345),(2118,148,898,0,4,123,1065,17,1069417643,5,172,'contact_type',1),(2117,148,123,0,3,905,898,17,1069417643,5,172,'contact_type',0),(2116,148,905,0,2,1064,123,17,1069417643,5,171,'',0),(2115,148,1064,0,1,1064,905,17,1069417643,5,169,'',0),(2114,148,1064,0,0,0,1064,17,1069417643,5,170,'',0),(2127,148,980,0,13,977,1072,17,1069417643,5,179,'',0),(2128,148,1072,0,14,980,1073,17,1069417643,5,179,'',0),(2129,148,1073,0,15,1072,980,17,1069417643,5,179,'',0),(2130,148,980,0,16,1073,0,17,1069417643,5,179,'',0),(2131,149,1074,0,0,0,1075,2,1069417727,4,1,'',0),(2132,149,1075,0,1,1074,979,2,1069417727,4,120,'',0),(2133,149,979,0,2,1075,980,2,1069417727,4,120,'',0),(2134,149,980,0,3,979,0,2,1069417727,4,120,'',0),(2135,150,1076,0,0,0,1077,2,1069417787,4,1,'',0),(2136,150,1077,0,1,1076,993,2,1069417787,4,120,'',0),(2137,150,993,0,2,1077,1078,2,1069417787,4,120,'',0),(2138,150,1078,0,3,993,997,2,1069417787,4,121,'',0),(2139,150,997,0,4,1078,970,2,1069417787,4,121,'',0),(2140,150,970,0,5,997,1079,2,1069417787,4,121,'',0),(2141,150,1079,0,6,970,0,2,1069417787,4,121,'',0),(2142,151,1080,0,0,0,5,2,1069417842,4,1,'',0),(2143,151,5,0,1,1080,1081,2,1069417842,4,1,'',0),(2144,151,1081,0,2,5,1082,2,1069417842,4,120,'',0),(2145,151,1082,0,3,1081,1083,2,1069417842,4,120,'',0),(2146,151,1083,0,4,1082,1084,2,1069417842,4,120,'',0),(2147,151,1084,0,5,1083,1085,2,1069417842,4,120,'',0),(2148,151,1085,0,6,1084,1086,2,1069417842,4,121,'',0),(2149,151,1086,0,7,1085,1087,2,1069417842,4,121,'',0),(2150,151,1087,0,8,1086,0,2,1069417842,4,121,'',0),(2151,153,905,0,0,0,1088,16,1069419202,5,162,'',0),(2152,153,1088,0,1,905,123,16,1069419202,5,163,'',0),(2153,153,123,0,2,1088,898,16,1069419202,5,164,'address_type',0),(2154,153,898,0,3,123,842,16,1069419202,5,164,'address_type',1),(2155,153,842,0,4,898,1089,16,1069419202,5,164,'address_type',2),(2156,153,1089,0,5,842,998,16,1069419202,5,166,'',0),(2157,153,998,0,6,1089,969,16,1069419202,5,166,'',0),(2158,153,969,0,7,998,970,16,1069419202,5,166,'',0),(2159,153,970,0,8,969,997,16,1069419202,5,166,'',0),(2160,153,997,0,9,970,123,16,1069419202,5,166,'',0),(2161,153,123,0,10,997,123,16,1069419202,5,167,'0',0),(2162,153,123,0,11,123,898,16,1069419202,5,168,'contact_type',0),(2163,153,898,0,12,123,1090,16,1069419202,5,168,'contact_type',1),(2164,153,1090,0,13,898,1091,16,1069419202,5,168,'contact_value',876),(2165,153,1091,0,14,1090,0,16,1069419202,5,168,'contact_value',876786),(2166,154,1092,0,0,0,1093,12,1069419406,6,146,'',0),(2167,154,1093,0,1,1092,1094,12,1069419406,6,147,'',0),(2168,154,1094,0,2,1093,980,12,1069419406,6,147,'',0),(2169,154,980,0,3,1094,1095,12,1069419406,6,147,'',0),(2170,154,1095,0,4,980,977,12,1069419406,6,147,'',0),(2171,154,977,0,5,1095,0,12,1069419406,6,147,'',0),(2172,155,1096,0,0,0,1077,2,1069419543,4,1,'',0),(2173,155,1077,0,1,1096,1097,2,1069419543,4,120,'',0),(2174,155,1097,0,2,1077,0,2,1069419543,4,121,'',0),(2175,156,1098,0,0,0,1099,2,1069419905,4,1,'',0),(2176,156,1099,0,1,1098,1077,2,1069419905,4,120,'',0),(2177,156,1077,0,2,1099,0,2,1069419905,4,121,'',0),(2178,157,1100,0,0,0,1101,2,1069420019,4,1,'',0),(2179,157,1101,0,1,1100,969,2,1069420019,4,120,'',0),(2180,157,969,0,2,1101,969,2,1069420019,4,120,'',0),(2181,157,969,0,3,969,0,2,1069420019,4,120,'',0),(2182,158,1102,0,0,0,1103,2,1069420874,4,1,'',0),(2183,158,1103,0,1,1102,1104,2,1069420874,4,120,'',0),(2184,158,1104,0,2,1103,0,2,1069420874,4,121,'',0),(2185,159,1096,0,0,0,1097,12,1069420903,6,146,'',0),(2186,159,1097,0,1,1096,0,12,1069420903,6,147,'',0),(2187,161,593,0,0,0,1105,17,1069421689,5,170,'',0),(2188,161,1105,0,1,593,1106,17,1069421689,5,169,'',0),(2189,161,1106,0,2,1105,123,17,1069421689,5,171,'',0),(2190,161,123,0,3,1106,1107,17,1069421689,5,172,'contact_type',0),(2191,161,1107,0,4,123,1108,17,1069421689,5,172,'contact_value',8908),(2192,161,1108,0,5,1107,1109,17,1069421689,5,179,'',0),(2193,161,1109,0,6,1108,1110,17,1069421689,5,179,'',0),(2194,161,1110,0,7,1109,0,17,1069421689,5,179,'',0),(2195,162,1111,0,0,0,1112,17,1069421720,5,170,'',0),(2196,162,1112,0,1,1111,1113,17,1069421720,5,169,'',0),(2197,162,1113,0,2,1112,1114,17,1069421720,5,171,'',0),(2198,162,1114,0,3,1113,0,17,1069421720,5,179,'',0),(2199,163,1115,0,0,0,1116,2,1069422602,4,1,'',0),(2200,163,1116,0,1,1115,1117,2,1069422602,4,120,'',0),(2201,163,1117,0,2,1116,1118,2,1069422602,4,120,'',0),(2202,163,1118,0,3,1117,1117,2,1069422602,4,120,'',0),(2203,163,1117,0,4,1118,969,2,1069422602,4,120,'',0),(2204,163,969,0,5,1117,1119,2,1069422602,4,120,'',0),(2205,163,1119,0,6,969,1120,2,1069422602,4,120,'',0),(2206,163,1120,0,7,1119,1121,2,1069422602,4,120,'',0),(2207,163,1121,0,8,1120,1122,2,1069422602,4,121,'',0),(2208,163,1122,0,9,1121,969,2,1069422602,4,121,'',0),(2209,163,969,0,10,1122,970,2,1069422602,4,121,'',0),(2210,163,970,0,11,969,1123,2,1069422602,4,121,'',0),(2211,163,1123,0,12,970,1124,2,1069422602,4,121,'',0),(2212,163,1124,0,13,1123,1124,2,1069422602,4,121,'',0),(2213,163,1124,0,14,1124,970,2,1069422602,4,121,'',0),(2214,163,970,0,15,1124,970,2,1069422602,4,121,'',0),(2215,163,970,0,16,970,0,2,1069422602,4,121,'',0),(2216,165,1096,0,0,0,1077,13,1069423490,4,149,'',0),(2217,165,1077,0,1,1096,1102,13,1069423490,4,150,'',0),(2218,165,1102,0,2,1077,0,13,1069423490,4,151,'',0),(2219,166,1125,0,0,0,1126,13,1069423957,4,149,'',0),(2220,166,1126,0,1,1125,1126,13,1069423957,4,150,'',0),(2221,166,1126,0,2,1126,0,13,1069423957,4,151,'',0),(2222,168,1127,0,0,0,1128,2,1069675837,4,1,'',0),(2223,168,1128,0,1,1127,985,2,1069675837,4,120,'',0),(2224,168,985,0,2,1128,0,2,1069675837,4,121,'',0);
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
INSERT INTO ezsearch_return_count VALUES (1,1,1066398569,1),(2,2,1066909621,1),(3,3,1066910511,1),(4,4,1066912239,1),(5,5,1066982534,1),(6,6,1066991890,4),(7,6,1066992837,4),(8,6,1066992963,4),(9,6,1066992972,0),(10,6,1066993049,0),(11,6,1066993056,4),(12,6,1066993091,4),(13,6,1066993127,4),(14,6,1066993135,4),(15,6,1066993895,4),(16,6,1066993946,4),(17,6,1066993995,4),(18,6,1066994001,4),(19,6,1066994050,4),(20,6,1066994057,4),(21,6,1066994067,4),(22,7,1066996820,0),(23,5,1066997190,1),(24,5,1066997194,1),(25,8,1066998830,1),(26,8,1066998836,1),(27,8,1066998870,1),(28,9,1066998915,1),(29,10,1067003146,0),(30,11,1067003155,2),(31,6,1067005771,4),(32,6,1067005777,4),(33,6,1067005801,4),(34,12,1067006770,1),(35,12,1067006774,1),(36,12,1067006777,1),(37,12,1067006787,1),(38,12,1067006803,1),(39,12,1067006996,1),(40,12,1067008585,1),(41,12,1067008597,1),(42,12,1067008602,0),(43,12,1067008608,1),(44,12,1067008613,0),(45,12,1067008620,0),(46,12,1067008625,0),(47,12,1067008629,1),(48,12,1067008655,1),(49,12,1067008659,0),(50,12,1067008663,0),(51,12,1067008667,0),(52,12,1067008711,0),(53,12,1067008717,0),(54,12,1067008720,1),(55,12,1067008725,0),(56,12,1067008920,1),(57,12,1067008925,1),(58,12,1067008929,0),(59,12,1067008934,1),(60,12,1067009005,1),(61,12,1067009023,1),(62,12,1067009042,1),(63,12,1067009051,0),(64,13,1067009056,1),(65,14,1067009067,0),(66,14,1067009073,0),(67,13,1067009594,1),(68,13,1067009816,1),(69,13,1067009953,1),(70,13,1067010181,1),(71,13,1067010352,1),(72,13,1067010359,1),(73,13,1067010370,1),(74,13,1067010509,1),(75,6,1067241668,5),(76,6,1067241727,5),(77,6,1067241742,5),(78,6,1067241760,5),(79,6,1067241810,5),(80,6,1067241892,5),(81,6,1067241928,5),(82,6,1067241953,5),(83,14,1067252984,0),(84,14,1067252987,0),(85,14,1067253026,0),(86,14,1067253160,0),(87,14,1067253218,0),(88,14,1067253285,0),(89,5,1067520640,1),(90,5,1067520646,1),(91,5,1067520658,1),(92,5,1067520704,0),(93,5,1067520753,0),(94,5,1067520761,1),(95,5,1067520769,1),(96,5,1067521324,1),(97,5,1067521402,1),(98,5,1067521453,1),(99,5,1067521532,1),(100,5,1067521615,1),(101,5,1067521674,1),(102,5,1067521990,1),(103,5,1067522592,1),(104,5,1067522620,1),(105,5,1067522888,1),(106,5,1067522987,1),(107,5,1067523012,1),(108,5,1067523144,1),(109,5,1067523213,1),(110,5,1067523261,1),(111,5,1067523798,1),(112,5,1067523805,1),(113,5,1067523820,1),(114,5,1067523858,1),(115,5,1067524474,1),(116,5,1067524629,1),(117,5,1067524696,1),(118,15,1067526426,0),(119,15,1067526433,0),(120,15,1067526701,0),(121,15,1067527009,0),(122,5,1067527022,1),(123,5,1067527033,1),(124,5,1067527051,1),(125,5,1067527069,1),(126,5,1067527076,0),(127,5,1067527124,1),(128,5,1067527176,1),(129,16,1067527188,0),(130,16,1067527227,0),(131,16,1067527244,0),(132,16,1067527301,0),(133,5,1067527315,0),(134,5,1067527349,0),(135,5,1067527412,0),(136,5,1067527472,1),(137,5,1067527502,1),(138,5,1067527508,0),(139,17,1067527848,0),(140,5,1067527863,1),(141,5,1067527890,1),(142,5,1067527906,1),(143,5,1067527947,1),(144,5,1067527968,0),(145,5,1067527993,0),(146,5,1067528010,1),(147,5,1067528029,0),(148,5,1067528045,0),(149,5,1067528050,0),(150,5,1067528056,0),(151,5,1067528061,0),(152,5,1067528063,0),(153,18,1067528100,1),(154,18,1067528113,0),(155,18,1067528190,1),(156,18,1067528236,1),(157,18,1067528270,1),(158,18,1067528309,1),(159,5,1067528323,0),(160,18,1067528334,1),(161,18,1067528355,1),(162,5,1067528368,0),(163,5,1067528377,1),(164,19,1067528402,0),(165,19,1067528770,0),(166,19,1067528924,0),(167,19,1067528963,0),(168,19,1067529028,0),(169,19,1067529054,0),(170,19,1067529119,0),(171,19,1067529169,0),(172,19,1067529211,0),(173,19,1067529263,0),(174,20,1069331888,1),(175,21,1069339779,0),(176,22,1069339782,1),(177,22,1069340682,1),(178,9,1069343741,1),(179,9,1069343746,0),(180,23,1069402946,1),(181,23,1069403018,1),(182,23,1069403060,1),(183,23,1069403074,1),(184,23,1069403165,1),(185,23,1069403175,1),(186,23,1069403313,1),(187,23,1069403382,1),(188,22,1069403393,1),(189,22,1069405832,0),(190,24,1069415930,1),(191,25,1069417220,1),(192,26,1069429264,1);
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
INSERT INTO ezsearch_search_phrase VALUES (1,'documents'),(2,'wenyue'),(3,'xxx'),(4,'release'),(5,'test'),(6,'ez'),(7,'f1'),(8,'bjørn'),(9,'abb'),(10,'2-2'),(11,'3.2'),(12,'bård'),(13,'Vidar'),(14,'tewtet'),(15,'dcv'),(16,'gr'),(17,'tewt'),(18,'members'),(19,'regte'),(20,'terje'),(21,'bildo'),(22,'bilbo'),(23,'sys'),(24,'vbanner'),(25,'dsfg'),(26,'gandalf');
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
INSERT INTO ezsearch_word VALUES (5,'test',2),(6,'media',2),(7,'setup',3),(933,'grouplist',1),(22,'class',2),(932,'classes',1),(11,'links',1),(900,'kvern',1),(25,'content',5),(34,'feel',2),(33,'and',5),(32,'look',2),(37,'news',3),(49,'business',1),(1135,'&copy',1),(48,'contact',1),(50,'off',1),(51,'topic',1),(53,'reports',1),(54,'staff',1),(55,'persons',1),(56,'companies',2),(440,'files',1),(58,'products',1),(59,'handbooks',1),(60,'documents',1),(61,'company',2),(62,'routines',2),(63,'logos',1),(898,'1',5),(67,'it',3),(899,'sys',1),(813,'community',1),(73,'this',2),(74,'is',4),(75,'the',5),(810,'benefits',1),(77,'for',3),(81,'all',3),(1047,'enhancements',1),(1046,'bugfixes',1),(809,'mention',1),(86,'we',2),(1045,'catalan',1),(89,'a',3),(1044,'spanish',1),(1043,'german',1),(1042,'mozambique',1),(808,'not',1),(807,'be',1),(1041,'portuguese',1),(1040,'translations',1),(1039,'filter',1),(806,'powerful',1),(102,'to',4),(820,'things',1),(805,'how',1),(819,'great',1),(818,'achieve',1),(108,'from',2),(817,'together',1),(816,'working',1),(804,'just',1),(815,'minds',1),(814,'creative',1),(812,'huge',1),(122,'abb',1),(123,'0',10),(169,'both',1),(168,'ie',1),(167,'explorer',1),(166,'internet',1),(165,'available',1),(164,'only',1),(163,'tags',1),(162,'use',1),(161,'need',1),(160,'eliminating',1),(159,'editing',1),(158,'simplified',1),(157,'integrated',1),(156,'an',3),(155,'oe',2),(144,'ez',6),(145,'publish',4),(154,'editor',1),(153,'online',1),(148,'3.2',2),(149,'latest',1),(150,'stable',2),(151,'version',1),(152,'of',3),(170,'3',1),(171,'2.2',1),(803,'realize',1),(802,'when',1),(414,'software',2),(180,'some',2),(801,'impressed',1),(800,'seem',1),(183,'in',3),(799,'sure',1),(798,'they',1),(797,'however',1),(796,'licenses',1),(795,'public',1),(794,'unfamiliar',1),(793,'still',1),(792,'linux',1),(195,'new',4),(791,'gnu',1),(790,'success',1),(789,'enormous',1),(788,'despite',1),(786,'large',1),(787,'small',1),(785,'organizations',1),(784,'various',1),(783,'representing',1),(782,'them',1),(781,'austria',1),(780,'mostly',1),(779,'visitors',1),(221,'us',2),(778,'framework',1),(381,'here',2),(224,'that',2),(777,'development',1),(227,'who',2),(776,'management',1),(775,'source',1),(774,'open',1),(773,'which',1),(772,'product',1),(235,'system',2),(771,'main',1),(770,'knowledge',1),(769,'many',1),(768,'there',1),(767,'seems',1),(766,'already',1),(765,'visited',1),(764,'people',1),(763,'lot',1),(762,'day',1),(761,'been',1),(760,'barely',1),(759,'have',1),(254,'with',2),(758,'expectations',1),(757,'exceeding',1),(756,'positive',1),(755,'very',1),(754,'impressions',1),(753,'first',1),(752,'our',2),(751,'report',1),(749,'text',1),(750,'contains',1),(748,'following',1),(747,'october',1),(746,'24th',1),(744,'site',1),(745,'20th',1),(743,'on',1),(1038,'attribute',1),(1037,'field',1),(1036,'well',1),(1035,'as',2),(1034,'attributes',1),(1033,'object',1),(1032,'keys',1),(1031,'sort',1),(1030,'bug',1),(1029,'orders',1),(292,'url',2),(1028,'basket',1),(294,'support',2),(1027,'regarding',1),(1026,'shop',1),(1025,'problem',1),(1024,'fixed',1),(1023,'transport',1),(1022,'smtp',1),(1021,'bcc',1),(1020,'cc',1),(303,'cache',2),(1019,'clearing',1),(1018,'regards',1),(1017,'improvements',1),(1016,'ui',1),(1015,'updated',1),(1014,'also',1),(1013,'scripts',1),(1012,'updatenicurls',1),(1011,'wildcards',1),(1010,'alias',1),(1009,'improved',1),(1008,'notes',1),(1007,'last',1),(1006,'present',1),(1005,'were',1),(1004,'problems',1),(1003,'fixes',1),(1002,'users',1),(1001,'recommended',1),(1000,'upgrade',1),(331,'general',2),(999,'release',1),(842,'2',5),(334,'mr',1),(335,'xxx',1),(552,'name',1),(551,'his',1),(550,'employee',1),(549,'hired',1),(548,'joined',1),(811,'having',1),(720,'systems',2),(465,'information',2),(742,'representatives',1),(741,'four',1),(740,'time',1),(427,'can',2),(739,'22nd',1),(738,'held',1),(737,'telecommunications',1),(736,'technology',1),(735,'fair',1),(734,'trade',1),(733,'international',1),(732,'2003',2),(441,'you',1),(442,'download',1),(836,'98802246',1),(835,'wy@ez.no',1),(446,'email',2),(447,'tele',2),(834,'engineer',1),(833,'wenyue',1),(731,'2003\"',1),(730,'\"systems',1),(729,'attending',1),(728,'germany',1),(727,'are',1),(726,'crew',1),(725,'members',1),(724,'week',1),(723,'munich',1),(722,'live',1),(721,'reporting',1),(841,'66667777',1),(840,'br@ez.no',1),(839,'manager',1),(838,'bjørn',1),(832,'yu',1),(837,'reiten',1),(571,'doe',1),(570,'john',1),(572,'vid',1),(573,'la',1),(574,'about',1),(575,'pellentesque',1),(576,'habitant',1),(577,'morbi',1),(578,'tristique',1),(579,'senectus',1),(580,'et',1),(581,'netus',1),(582,'malesuada',1),(583,'fames',1),(584,'ac',1),(585,'turpis',1),(586,'egestas',1),(587,'aptent',1),(588,'taciti',1),(589,'sociosqu',1),(590,'ad',1),(591,'litora',1),(592,'torquent',1),(593,'per',2),(594,'conubia',1),(595,'nostra',1),(596,'inceptos',1),(597,'hymenaeos',1),(598,'cras',1),(599,'nisl',1),(600,'non',1),(601,'sed',1),(602,'leo',1),(603,'ut',1),(604,'dui',1),(605,'iaculis',1),(606,'pharetra',1),(607,'donec',1),(608,'felis',1),(609,'nulla',1),(610,'aliquet',1),(611,'aliquam',1),(612,'ultricies',1),(613,'urna',1),(614,'vivamus',1),(615,'risus',1),(616,'fusce',1),(617,'pede',1),(618,'ornare',1),(619,'lectus',1),(620,'auctor',1),(621,'erat',1),(622,'purus',1),(623,'elementum',1),(624,'magna',1),(625,'vel',1),(626,'luctus',1),(627,'augue',1),(628,'quis',1),(629,'massa',1),(630,'nullam',1),(631,'diam',1),(632,'at',1),(633,'mi',1),(634,'vestibulum',1),(635,'viverra',1),(636,'velit',1),(637,'vitae',1),(638,'quam',1),(639,'mauris',1),(640,'nibh',1),(641,'phasellus',1),(642,'nec',1),(643,'metus',1),(644,'integer',1),(645,'eu',1),(646,'sem',1),(647,'praesent',1),(648,'rutrum',1),(649,'ullamcorper',1),(650,'ligula',1),(651,'est',1),(652,'orci',1),(653,'commodo',1),(654,'rhoncus',1),(655,'semper',1),(656,'eget',1),(657,'gravida',1),(658,'vehicula',1),(659,'suspendisse',1),(660,'potenti',1),(661,'aenean',1),(662,'sodales',1),(663,'id',1),(664,'adipiscing',1),(665,'dignissim',1),(666,'libero',1),(667,'maecenas',1),(668,'lorem',1),(669,'arcu',1),(670,'elit',1),(671,'congue',1),(672,'etiam',1),(673,'sapien',1),(674,'mollis',1),(675,'fermentum',1),(676,'hac',1),(677,'habitasse',1),(678,'platea',1),(679,'dictumst',1),(680,'neque',1),(681,'posuere',1),(682,'nunc',1),(683,'porttitor',1),(684,'venenatis',1),(685,'enim',1),(686,'sagittis',1),(687,'scelerisque',1),(688,'consectetuer',1),(689,'sit',1),(690,'amet',1),(691,'curabitur',1),(692,'laoreet',1),(693,'wisi',1),(694,'lobortis',1),(901,'telemar',1),(940,'136',1),(939,'edit',1),(937,'urltranslator',1),(936,'translator',1),(902,'1234',1),(903,'asdf',2),(904,'123',3),(905,'foo',4),(906,'bar',2),(907,'corp',1),(908,'vidar',1),(909,'langseid',1),(910,'adf',1),(911,'bård',1),(912,'farstad',1),(913,'asd',1),(914,'asdfasdfasdf',1),(915,'fido',1),(916,'barida',1),(917,'fasdf',1),(918,'asdfasdfawf',1),(944,'butikksjef',1),(943,'kaste',1),(942,'terje',1),(1134,'copyright',1),(945,'214512345',1),(946,'tg@ez.no',1),(975,'dfjghkjdfgdfdfgh',1),(974,'9934',1),(973,'boss',1),(972,'baggins',1),(971,'bilbo',1),(968,'jkhdfgdf',1),(967,'3458',1),(966,'master',1),(965,'gray',1),(964,'gandalf',1),(969,'gh',5),(970,'dfgh',5),(976,'sdfg',2),(977,'sdgf',3),(978,'dsfg',1),(979,'dsgf',2),(980,'sd',5),(981,'fgsdfg',1),(982,'sdg',1),(983,'article',1),(984,'kjhjkhj',1),(985,'lkjklj',2),(986,'fg',1),(987,'dfghg',1),(988,'dfhgh',1),(989,'hdfgh',1),(990,'fd',1),(991,'mnb',1),(992,'jkh',1),(993,'kjh',2),(994,'fdhjkldfhj',1),(995,'jkhhjlk',1),(996,'hjcvxbdfgh',1),(997,'dfh',3),(998,'df',2),(1052,'intranet',1),(1053,'welcome',1),(1072,'gfsd',1),(1071,'sdkjh',1),(1070,'dskjgf',1),(1069,'sdjkfgh',1),(1068,'sdkfjgh',1),(1067,'kjhsdfgjkshdgjk',1),(1066,'34588',1),(1065,'99345',1),(1064,'jh',1),(1073,'gf',1),(1074,'dfsdfg',1),(1075,'dfsgsdfgsdfg',1),(1076,'sdifgksdjfgkjgh',1),(1077,'kjhjkh',4),(1078,'kjhjkhghdf',1),(1079,'fdg',1),(1080,'kåre',1),(1081,'jhghj',1),(1082,'gj',1),(1083,'hjg',1),(1084,'kgjkgjkg',1),(1085,'kjg',1),(1086,'jkg',1),(1087,'jk',1),(1088,'dfg',1),(1089,'fdhdfgh',1),(1090,'876',1),(1091,'876786',1),(1092,'jhhhjgjhg',1),(1093,'jhg',1),(1094,'jhgds',1),(1095,'g',1),(1096,'jkhkjh',3),(1097,'kjhkjh',2),(1098,'jhkjh',1),(1099,'kjhkjhkj',1),(1100,'utuytuy',1),(1101,'gfhjfghdf',1),(1102,'jkhjkh',2),(1103,'kjhjkhjk',1),(1104,'hjkhjkh',1),(1105,'son',1),(1106,'sjef',1),(1107,'8908',1),(1108,'098ds',1),(1109,'sdfghfd',1),(1110,'ghdfgh',1),(1111,'bbmnm',1),(1112,'mnbnmb',1),(1113,'mnbnm',1),(1114,'nmbnmb',1),(1115,'lkj',1),(1116,'lkjkljkljfd',1),(1117,'ghdf',1),(1118,'ghfdghdf',1),(1119,'fgh',1),(1120,'dfhdfghdfgh',1),(1121,'kljlkjl',1),(1122,'dfghdf',1),(1123,'fdh',1),(1124,'fdgh',1),(1125,'kljjkl',1),(1126,'lkjlkj',1),(1127,'jkljlkj',1),(1128,'lkjkljkljlkjklj',1),(1133,'intranet_package',1),(1136,'1999',1);
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
INSERT INTO ezsession VALUES ('4d2b4416f789250f9013cd293ed8a6c1',1069945224,'eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069686010;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069686010;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069686010;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRulesTimestamp|i:1069686011;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}'),('dc4e4ff636b5445bca24a00c277ddbe4',1069683847,''),('11aa60fe7a56587c7890e113b5c4daae',1069686082,'eZUserInfoCache_Timestamp|i:1069425075;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425075;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069426559;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRulesTimestamp|i:1069425075;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:18:\"/content/view/full\";UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}'),('f01cd9ad6c05527b5a586df7a6e97468',1069685097,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069425810;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425810;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069425897;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}LastAccessesURI|s:21:\"/content/view/full/50\";UserPolicies|a:1:{i:1;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"339\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}}}'),('58edd11b0756bf41cc4cf9d8690902df',1069946359,'eZUserGroupsCache_Timestamp|i:1069685446;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069685446;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069687139;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069685447;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:18:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:1:\"6\";s:4:\"name\";s:5:\"Forum\";}i:6;a:2:{s:2:\"id\";s:1:\"7\";s:4:\"name\";s:13:\"Forum message\";}i:7;a:2:{s:2:\"id\";s:1:\"8\";s:4:\"name\";s:7:\"Product\";}i:8;a:2:{s:2:\"id\";s:1:\"9\";s:4:\"name\";s:14:\"Product review\";}i:9;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:10;a:2:{s:2:\"id\";s:2:\"11\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:12;a:2:{s:2:\"id\";s:2:\"13\";s:4:\"name\";s:7:\"Comment\";}i:13;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:14;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:15;a:2:{s:2:\"id\";s:2:\"16\";s:4:\"name\";s:7:\"Company\";}i:16;a:2:{s:2:\"id\";s:2:\"17\";s:4:\"name\";s:6:\"Person\";}i:17;a:2:{s:2:\"id\";s:2:\"18\";s:4:\"name\";s:5:\"Event\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|b:0;eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}'),('6b757a80dcd2886681c0a2dc420526f6',1069942484,'eZUserInfoCache_Timestamp|i:1069331658;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069681457;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069681457;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069423918;canInstantiateClasses|i:1;Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;LastAccessesURI|s:21:\"/content/view/full/50\";eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRulesTimestamp|i:1069331658;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"4\";}classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:18:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:1:\"6\";s:4:\"name\";s:5:\"Forum\";}i:6;a:2:{s:2:\"id\";s:1:\"7\";s:4:\"name\";s:13:\"Forum message\";}i:7;a:2:{s:2:\"id\";s:1:\"8\";s:4:\"name\";s:7:\"Product\";}i:8;a:2:{s:2:\"id\";s:1:\"9\";s:4:\"name\";s:14:\"Product review\";}i:9;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:10;a:2:{s:2:\"id\";s:2:\"11\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:12;a:2:{s:2:\"id\";s:2:\"13\";s:4:\"name\";s:7:\"Comment\";}i:13;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:14;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:15;a:2:{s:2:\"id\";s:2:\"16\";s:4:\"name\";s:7:\"Company\";}i:16;a:2:{s:2:\"id\";s:2:\"17\";s:4:\"name\";s:6:\"Person\";}i:17;a:2:{s:2:\"id\";s:2:\"18\";s:4:\"name\";s:5:\"Event\";}}DiscardObjectID|s:3:\"169\";DiscardObjectVersion|s:1:\"1\";DiscardObjectLanguage|b:0;DiscardConfirm|b:1;Preferences-advanced_menu|b:0;FromGroupID|b:0;CurrentViewMode|s:4:\"full\";ContentNodeID|s:2:\"62\";ContentObjectID|s:2:\"64\";DeleteIDArray|a:1:{i:0;s:3:\"104\";}eZUserLoggedInID|s:2:\"14\";UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}');
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,NULL),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,NULL),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,NULL),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,NULL),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,NULL),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,NULL),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,NULL),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,NULL),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,NULL),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0),(37,'news/off_topic','c77d3081eac3bee15b0213bcc89b369b','content/view/full/57',1,0,0),(36,'news/business_news','bde42888705c25806fbe02b8570d055d','content/view/full/56',1,0,0),(96,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/107',1,0,0),(35,'contact','2f8a6bf31f3bd67bd2d9720c58b19c9a','content/view/full/55',1,0,0),(38,'news/reports_','ac624940baa3e037e0467bf2db2743cb','content/view/full/58',1,39,0),(39,'news/reports','f3cbeafbd5dbf7477a9a803d47d4dcbb','content/view/full/58',1,0,0),(40,'news/staff_news','c50e4a6eb10a499c098857026282ceb4','content/view/full/59',1,0,0),(41,'contact/persons','8d26f497abc489a9566eab966cbfe3ed','content/view/full/60',1,0,0),(42,'contact/companies','7b84a445a156acf3dd455ea6f585d78f','content/view/full/61',1,0,0),(43,'files','45b963397aa40d4a0063e0d85e4fe7a1','content/view/full/62',1,0,0),(44,'files/products','03be84a2443f2c43565937940a41ed8d','content/view/full/63',1,0,0),(45,'files/handbooks','7b18bc03d154e9c0643a86d3d2b7d68f','content/view/full/64',1,0,0),(46,'files/documents','2d30f25cef1a92db784bc537e8bf128d','content/view/full/65',1,0,0),(47,'files/company_routines','7ffaba1db587b80e9767abd0ceb00df7','content/view/full/66',1,0,0),(48,'files/logos','ab4749ddb9d45855d2431d2341c1c14e','content/view/full/67',1,0,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(94,'contact/terje_','d0d275955aa0aa3b8e72f80a7ded8a61','content/view/full/105',1,97,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(62,'news/business_news/ez_systems_reporting_live_from_munich','ddb9dceff37417877c5a030d5ca3e5b5','content/view/full/81',1,0,0),(63,'news/business_news/ez_publish_322_release','2fd7cd9bd8dc7eaa376187692cf64cdc','content/view/full/82',1,0,0),(64,'news/staff_news/mr_xxx_joined_us','6755615af39b3f3a145fd2a57a37809d','content/view/full/83',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(108,'contact/persons/jh_jh','cf01f10bebd4f9aec52a6778c36c8233','content/view/full/118',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(74,'users/editors/john_doe','470ba5117b9390b819f7c2519c0a6092','content/view/full/91',1,0,0),(75,'users/editors/vid_la','73f7efbac10f9f69aa4f7b19c97cfb16','content/view/full/92',1,0,0),(76,'information','bb3ccd5881d651448ded1dac904054ac','content/view/full/93',1,0,0),(77,'information/routines','ed84b3909be89ec2c730ddc2fa7b7a46','content/view/full/94',1,0,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(97,'contact/terje_kaste','bd2ca9a57d2c13e52e0c34123fd3cec6','content/view/full/105',1,0,0),(98,'contact/bilbo_baggins','4b02b38f887a25756869e9d8bbefca72','content/view/full/108',1,0,0),(99,'contact/gandalf_gray','6f43934dff8d41dce92814b1e5f60c01','content/view/full/109',1,0,0),(100,'files/products/oe','13d46921b179f97be723916d140c191c','content/view/full/110',1,0,0),(101,'news/off_topic/new_article','fbe6d1aa73ea1a6551c24a9aa68a95fa','content/view/full/111',1,0,0),(102,'new_article','876e70b907548cbdffd01696e70911c1','content/view/full/112',1,0,0),(103,'news/off_topic/foo_bar','068727130b8b50d4113bf0e029d44dd8','content/view/full/113',1,0,0),(104,'news/staff_news/mnb','e816c1fe1795536e6896953fa5249ef7','content/view/full/114',1,0,0),(105,'news/business_news/mnb','f335d816c5a32f2c39bca8541e559319','content/view/full/115',1,0,0),(106,'news/reports/fdhjkldfhj','5928e1cce21ba84ed7f29cc0e00136be','content/view/full/116',1,0,0),(107,'news/business_news/fdhjkldfhj','1bfb12ec3fe31773b22c2d49eb6fbba3','content/view/full/117',1,0,0),(109,'contact/jh_jh','68abd7d29119bdc4f6f1a02b4eeecff9','content/view/full/119',1,0,0),(110,'news/off_topic/dfsdfg','3933dd18ca8b2e509b8b45118a8eaad4','content/view/full/120',1,0,0),(111,'news/business_news/dfsdfg','a7388bc5dda435b988e4f9a9aeb14038','content/view/full/121',1,0,0),(112,'news/staff_news/sdifgksdjfgkjgh','524539c61399ed93fc9fa090da54ec9d','content/view/full/122',1,0,0),(113,'news/off_topic/sdifgksdjfgkjgh','54ad548df1e3706b84ed7ba38506a409','content/view/full/123',1,0,0),(114,'news/reports/kre_test','2dc61bac93b13af1aae5a92ac15c55e7','content/view/full/124',1,0,0),(115,'news/off_topic/kre_test','6f11515b7b8b895c94f3ecb7e956e37b','content/view/full/125',1,0,0),(116,'contact/companies/foo','eae1a17bdeef625ac212bbb91e4023d7','content/view/full/126',1,0,0),(117,'files/products/jhhhjgjhg','4ddf0131535a401f138373d21c2688cf','content/view/full/127',1,0,0),(118,'news/business_news/jkhkjh','a07c80b8ac651f94e66590df27da085a','content/view/full/128',1,0,0),(119,'news/jkhkjh','b4e1d3e90c569cf5fa638b524f819897','content/view/full/129',1,0,0),(120,'news/business_news/jhkjh','57d2a64cbaf32e1551d0657a800a332d','content/view/full/130',1,0,0),(121,'news/jhkjh','dd08c60ba513e461333e4a60065d3b41','content/view/full/131',1,0,0),(122,'news/reports/utuytuy','863350595188c49f4925dba8a66d1a18','content/view/full/132',1,0,0),(123,'news/utuytuy','1b452d7c1a13e0445272809214411cd2','content/view/full/133',1,0,0),(124,'news/business_news/jkhjkh','dcde291a69b40f0a76bfa67e3446daf1','content/view/full/134',1,0,0),(125,'news/jkhjkh','5f71e58ee158537ed6e0b55314ee4239','content/view/full/135',1,0,0),(126,'files/products/jkhkjh','09f9658056d40e65ae3521fd02f89fe1','content/view/full/136',1,0,0),(127,'files/jkhkjh','64aae439d4b9b79dd336a115c4944da6','content/view/full/137',1,0,0),(128,'contact/persons/per_son','547056bbe8097664a20631c40f020e22','content/view/full/138',1,0,0),(129,'contact/per_son','21e47ef737be617f0052407b2162d872','content/view/full/139',1,0,0),(130,'contact/persons/bbmnm_mnbnmb','579a17a6451f1ff24b2f27efe6ffd7cd','content/view/full/140',1,0,0),(131,'news/staff_news/lkj','76ca99cb194260eb47ec5d697473b0a2','content/view/full/141',1,0,0),(132,'news/lkj','ef01b10a44406e427433d14c6290ef9a','content/view/full/142',1,0,0),(133,'news/staff_news/lkj/jkhkjh','0ab56a1b16594b117f45bb7797574a9c','content/view/full/143',1,0,0),(134,'news/staff_news/lkj/kljjkl','c3ffa06697cf122974c26a55bb1e64f7','content/view/full/144',1,0,0),(135,'news/reports/jkljlkj','079e3d5ad55d498fae54a21fe49b57b5','content/view/full/145',1,0,0),(136,'news/jkljlkj','f9e313851fcfceea4b079c106f3cb938','content/view/full/146',1,0,0);
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

