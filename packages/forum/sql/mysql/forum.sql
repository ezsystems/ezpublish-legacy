-- MySQL dump 10.2
--
-- Host: localhost    Database: forum
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
INSERT INTO ezcontentbrowserecent VALUES (73,14,154,1068728070,'New topic'),(35,111,99,1067006746,'foo bar corp'),(65,149,135,1068126974,'lkj ssssstick'),(49,10,12,1068112852,'Guest accounts'),(64,206,135,1068123651,'lkj ssssstick');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1066907423),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1068123024),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069416134),(12,1,'File','file','<name>',14,14,1052385472,1067353799),(20,0,'Forum','forum','<name>',14,14,1068036085,1068564509),(21,0,'Forum topic','forum_topic','<subject>',14,14,1068036127,1068048102),(22,0,'Forum reply','forum_reply','<subject>',14,14,1068041812,1068043948),(21,1,'Forum topic','forum_topic','<subject>',14,14,1068036127,1069675975);
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(202,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(186,0,20,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(187,0,20,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(201,0,20,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(198,0,4,'location','Location','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(199,0,4,'signature','Signature','eztext',1,0,6,2,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'css','CSS','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'css','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(191,0,22,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(193,0,22,'message','Message','eztext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(194,1,21,'notify_me_about_updates','Notify me about updates','ezsubtreesubscription',0,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(188,1,21,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(190,1,21,'sticky','Sticky','ezboolean',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(190,0,21,'sticky','Sticky','ezboolean',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(194,0,21,'notify_me_about_updates','Notify me about updates','ezsubtreesubscription',0,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(189,1,21,'message','Message','eztext',1,1,2,15,0,0,0,0,0,0,0,'','','','','',0,1),(189,0,21,'message','Message','eztext',1,1,2,15,0,0,0,0,0,0,0,'','','','','',0,1),(188,0,21,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(200,0,4,'user_image','User image','ezimage',0,0,7,1,0,0,0,0,0,0,0,'','','','','',0,1),(197,0,4,'title','Title','ezstring',1,0,4,25,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1);
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
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(21,1,1,'Content'),(20,0,1,'Content'),(21,0,1,'Content'),(22,0,1,'Content');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Forum',8,0,1033917596,1068810447,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',5,0,1033920830,1068468219,1,''),(190,14,1,21,'lkj ssssstick',1,0,1068111804,1068111804,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',9,0,1066384365,1068825487,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',10,0,1066388816,1068825503,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,4,1,'News',1,0,1066398020,1066398020,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'Forum',56,0,1066643397,1069687512,1,''),(164,14,1,21,'latest msg (not sticky)',1,0,1068047893,1068047893,1,''),(163,14,1,22,'reply',1,0,1068047867,1068047867,1,''),(160,14,4,2,'News bulletin',1,0,1068047455,1068047455,1,''),(161,14,1,10,'About this forum',1,0,1068047603,1068047603,1,''),(162,14,1,21,'New topic (sticky)',1,0,1068047842,1068047842,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(87,14,5,16,'New Company',1,0,0,0,2,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(166,14,1,21,'Not sticky 2',1,0,1068048145,1068048145,1,''),(165,149,1,21,'New Forum topic',1,0,0,0,0,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(107,14,2,4,'John Doe',2,0,1066916865,1066916941,1,''),(139,14,1,21,'This is a new topic',2,0,1068036445,1068036624,1,''),(111,14,2,4,'vid la',1,0,1066917523,1066917523,1,''),(138,14,1,1,'Discussions',2,0,1068036060,1068041936,1,''),(115,14,11,14,'Cache',4,0,1066991725,1068825448,1,''),(116,14,11,14,'URL translator',3,0,1066992054,1068825521,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(153,14,1,22,'My reply',1,0,1068042105,1068042105,1,''),(141,14,1,20,'Music discussion',3,0,1068036586,1068640320,1,''),(142,14,1,20,'Sports discussion',3,0,1068036660,1068640370,1,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(156,14,1,21,'What about pop?',1,0,1068044289,1068044289,1,''),(149,14,2,4,'wenyue yu',8,0,1068041016,1068130543,1,''),(155,14,1,22,'Foo bar',1,0,1068043907,1068043907,1,''),(151,149,1,21,'Football',1,0,1068041849,1068041849,1,''),(157,14,1,21,'Reply wanted for this topic',1,0,1068044407,1068044407,1,''),(158,14,1,22,'This is a reply',1,0,1068044532,1068044532,1,''),(167,14,1,21,'Important [sticky]',1,0,1068048180,1068048180,1,''),(168,149,0,21,'New Forum topic',1,0,0,0,0,''),(169,149,0,21,'New Forum topic',1,0,0,0,0,''),(170,149,1,22,'Reply 2',1,0,1068048760,1068048760,1,''),(171,149,1,21,'New Forum topic',1,0,0,0,0,''),(172,149,0,21,'New Forum topic',1,0,0,0,0,''),(173,149,0,21,'New Forum topic',1,0,0,0,0,''),(174,149,0,21,'New Forum topic',1,0,0,0,0,''),(175,149,0,21,'New Forum topic',1,0,0,0,0,''),(176,149,0,21,'New Forum topic',1,0,0,0,0,''),(177,149,0,21,'New Forum topic',1,0,0,0,0,''),(178,149,0,21,'New Forum topic',1,0,0,0,0,''),(179,149,0,21,'New Forum topic',1,0,0,0,0,''),(180,149,0,21,'New Forum topic',1,0,0,0,0,''),(181,149,0,21,'New Forum topic',1,0,0,0,0,''),(182,149,0,21,'New Forum topic',1,0,0,0,0,''),(183,149,0,21,'New Forum topic',1,0,0,0,0,''),(184,149,0,21,'New Forum topic',1,0,0,0,0,''),(185,149,0,21,'New Forum topic',1,0,0,0,0,''),(186,149,0,21,'New Forum topic',1,0,0,0,0,''),(187,14,1,4,'New User',1,0,0,0,0,''),(191,149,1,21,'New Forum topic',1,0,0,0,0,''),(189,14,1,4,'New User',1,0,0,0,0,''),(192,149,0,21,'New Forum topic',1,0,0,0,0,''),(193,149,0,21,'New Forum topic',1,0,0,0,0,''),(194,149,0,21,'New Forum topic',1,0,0,0,0,''),(195,14,1,21,'Foo',1,0,1068112063,1068112063,1,''),(196,14,1,22,'REply',1,0,1068112186,1068112186,1,''),(199,14,1,22,'uyuiyui',1,0,1068114951,1068114951,1,''),(200,149,1,21,'New Forum topic',1,0,0,0,0,''),(201,149,1,22,'New Forum reply',1,0,0,0,0,''),(202,149,1,21,'test2',2,0,1068121170,1068122329,1,''),(203,14,1,21,'t4',5,0,1068121394,1068122261,1,''),(204,14,1,22,'klj jkl klj',1,0,1068121576,1068121576,1,''),(205,149,1,22,'re:test2',1,0,1068122396,1068122396,1,''),(206,14,2,4,'Bård Farstad',1,0,1068123599,1068123599,1,''),(207,206,1,22,'My reply',1,0,1068123651,1068123651,1,''),(208,149,1,22,'re:test',1,0,1068126974,1068126974,1,''),(209,14,1,21,'hjg dghsdjgf',1,0,1068468136,1068468136,1,''),(210,14,1,22,'dfghd fghklj',1,0,1068468573,1068468573,1,''),(211,14,1,1,'Forum main group',2,0,1068640085,1068640157,1,''),(212,14,1,22,'Annen melding',1,0,1068726592,1068726592,1,''),(214,14,1,21,'New topic',1,0,1068727316,1068727316,1,''),(215,14,1,22,'fowehfowhi',1,0,1068728070,1068728070,1,''),(217,14,1,22,'New Forum reply',1,0,0,0,0,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table border=\"1\"\n           width=\"100%\"\n           class=\"frontpage\">\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(632,'eng-GB',1,190,190,'',1,0,0,1,'','ezboolean'),(631,'eng-GB',1,190,189,'kljlkj',0,0,0,0,'','eztext'),(630,'eng-GB',1,190,188,'lkj ssssstick',0,0,0,0,'lkj ssssstick','ezstring'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',1,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',1,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',1,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',1,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(685,'eng-GB',5,203,189,'sfsf',0,0,0,0,'','eztext'),(686,'eng-GB',5,203,190,'',0,0,0,0,'','ezboolean'),(688,'eng-GB',1,204,191,'klj jkl klj',0,0,0,0,'klj jkl klj','ezstring'),(689,'eng-GB',1,204,193,'lkj klj dgf\nfd\ngh\ndfhdf\nhdf\nh',0,0,0,0,'','eztext'),(684,'eng-GB',4,203,188,'t4',0,0,0,0,'t4','ezstring'),(685,'eng-GB',4,203,189,'sfsf',0,0,0,0,'','eztext'),(686,'eng-GB',4,203,190,'',0,0,0,0,'','ezboolean'),(687,'eng-GB',4,203,194,'',0,0,0,0,'','ezsubtreesubscription'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(684,'eng-GB',5,203,188,'t4',0,0,0,0,'t4','ezstring'),(676,'eng-GB',1,200,190,'',0,0,0,0,'','ezboolean'),(677,'eng-GB',1,200,194,'',0,0,0,0,'','ezsubtreesubscription'),(678,'eng-GB',1,201,191,'Re:test',0,0,0,0,'re:test','ezstring'),(679,'eng-GB',1,201,193,'fdsf',0,0,0,0,'','eztext'),(680,'eng-GB',1,202,188,'test2',0,0,0,0,'test2','ezstring'),(681,'eng-GB',1,202,189,'sefsefg\nsfes',0,0,0,0,'','eztext'),(682,'eng-GB',1,202,190,'',0,0,0,0,'','ezboolean'),(683,'eng-GB',1,202,194,'',0,0,0,0,'','ezsubtreesubscription'),(684,'eng-GB',1,203,188,'t4',0,0,0,0,'t4','ezstring'),(685,'eng-GB',1,203,189,'sfsf',0,0,0,0,'','eztext'),(686,'eng-GB',1,203,190,'',0,0,0,0,'','ezboolean'),(687,'eng-GB',1,203,194,'',0,0,0,0,'','ezsubtreesubscription'),(684,'eng-GB',2,203,188,'t4',0,0,0,0,'t4','ezstring'),(685,'eng-GB',2,203,189,'sfsf',0,0,0,0,'','eztext'),(686,'eng-GB',2,203,190,'',0,0,0,0,'','ezboolean'),(687,'eng-GB',2,203,194,'',0,0,0,0,'','ezsubtreesubscription'),(684,'eng-GB',3,203,188,'t4',0,0,0,0,'t4','ezstring'),(685,'eng-GB',3,203,189,'sfsf',0,0,0,0,'','eztext'),(686,'eng-GB',3,203,190,'',0,0,0,0,'','ezboolean'),(687,'eng-GB',3,203,194,'',0,0,0,0,'','ezsubtreesubscription'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(558,'eng-GB',1,171,189,'',0,0,0,0,'','eztext'),(553,'eng-GB',1,169,190,'',0,0,0,0,'','ezboolean'),(554,'eng-GB',1,169,194,'',0,0,0,0,'','ezsubtreesubscription'),(555,'eng-GB',1,170,191,'Reply 2',0,0,0,0,'reply 2','ezstring'),(556,'eng-GB',1,170,193,'trwebhr\nhbe\n',0,0,0,0,'','eztext'),(557,'eng-GB',1,171,188,'',0,0,0,0,'','ezstring'),(552,'eng-GB',1,169,189,'sfsvggs\nsfsf',0,0,0,0,'','eztext'),(547,'eng-GB',1,168,188,'',0,0,0,0,'','ezstring'),(548,'eng-GB',1,168,189,'',0,0,0,0,'','eztext'),(549,'eng-GB',1,168,190,'',0,0,0,0,'','ezboolean'),(550,'eng-GB',1,168,194,'',0,0,0,0,'','ezsubtreesubscription'),(551,'eng-GB',1,169,188,'test',0,0,0,0,'test','ezstring'),(544,'eng-GB',1,167,189,'whacky\n\ndsfgsdfg',0,0,0,0,'','eztext'),(545,'eng-GB',1,167,190,'',1,0,0,1,'','ezboolean'),(546,'eng-GB',1,167,194,'',0,0,0,0,'','ezsubtreesubscription'),(521,'eng-GB',1,160,177,'',0,0,0,0,'','ezinteger'),(516,'eng-GB',1,160,1,'News bulletin',0,0,0,0,'news bulletin','ezstring'),(517,'eng-GB',1,160,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(518,'eng-GB',1,160,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(519,'eng-GB',1,160,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin.\"\n         suffix=\"\"\n         basename=\"news_bulletin\"\n         dirpath=\"var/forum/storage/images/news/news_bulletin/519-1-eng-GB\"\n         url=\"var/forum/storage/images/news/news_bulletin/519-1-eng-GB/news_bulletin.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(520,'eng-GB',1,160,123,'',0,0,0,0,'','ezboolean'),(530,'eng-GB',1,163,193,'jkh',0,0,0,0,'','eztext'),(531,'eng-GB',1,164,188,'latest msg (not sticky)',0,0,0,0,'latest msg (not sticky)','ezstring'),(529,'eng-GB',1,163,191,'reply',0,0,0,0,'reply','ezstring'),(525,'eng-GB',1,162,188,'New topic (sticky)',0,0,0,0,'new topic (sticky)','ezstring'),(526,'eng-GB',1,162,189,'This is a teset lskdj gklsdg klsd gklsdgf',0,0,0,0,'','eztext'),(527,'eng-GB',1,162,190,'',1,0,0,1,'','ezboolean'),(528,'eng-GB',1,162,194,'',0,0,0,0,'','ezsubtreesubscription'),(532,'eng-GB',1,164,189,'kjh jkh kjh jkh ',0,0,0,0,'','eztext'),(533,'eng-GB',1,164,190,'',0,0,0,0,'','ezboolean'),(534,'eng-GB',1,164,194,'',0,0,0,0,'','ezsubtreesubscription'),(512,'eng-GB',2,158,191,'This is a reply',0,0,0,0,'this is a reply','ezstring'),(513,'eng-GB',2,158,193,'Test reply..\n\n-bård',0,0,0,0,'','eztext'),(535,'eng-GB',1,165,188,'',0,0,0,0,'','ezstring'),(536,'eng-GB',1,165,189,'',0,0,0,0,'','eztext'),(537,'eng-GB',1,165,190,'',0,0,0,0,'','ezboolean'),(538,'eng-GB',1,165,194,'',0,0,0,0,'','ezsubtreesubscription'),(539,'eng-GB',1,166,188,'Not sticky 2',0,0,0,0,'not sticky 2','ezstring'),(540,'eng-GB',1,166,189,'dsfsdfg\nsdfg\ndsfg\n',0,0,0,0,'','eztext'),(541,'eng-GB',1,166,190,'',0,0,0,0,'','ezboolean'),(542,'eng-GB',1,166,194,'',0,0,0,0,'','ezsubtreesubscription'),(543,'eng-GB',1,167,188,'Important [sticky]',0,0,0,0,'important [sticky]','ezstring'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(152,'eng-GB',50,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',53,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',50,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(749,'eng-GB',1,208,191,'re:test',0,0,0,0,'re:test','ezstring'),(750,'eng-GB',1,208,193,'sdfsdf\nsdfs',0,0,0,0,'','eztext'),(740,'eng-GB',1,206,8,'Bård',0,0,0,0,'bård','ezstring'),(741,'eng-GB',1,206,9,'Farstad',0,0,0,0,'farstad','ezstring'),(742,'eng-GB',1,206,12,'',0,0,0,0,'','ezuser'),(743,'eng-GB',1,206,197,'music guru',0,0,0,0,'music guru','ezstring'),(744,'eng-GB',1,206,198,'Oslo/Norway',0,0,0,0,'oslo/norway','ezstring'),(745,'eng-GB',1,206,199,'sig..',0,0,0,0,'','eztext'),(746,'eng-GB',1,206,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"brd_farstad.jpg\"\n         suffix=\"jpg\"\n         basename=\"brd_farstad\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad.jpg\"\n         original_filename=\"dscn9284.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"2cv\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"brd_farstad_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"brd_farstad_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"225\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"brd_farstad_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(747,'eng-GB',1,207,191,'My reply',0,0,0,0,'my reply','ezstring'),(748,'eng-GB',1,207,193,'dsfgsdfg\nsdf\ngsdf\ng',0,0,0,0,'','eztext'),(639,'eng-GB',1,192,189,'',0,0,0,0,'','eztext'),(640,'eng-GB',1,192,190,'',0,0,0,0,'','ezboolean'),(151,'eng-GB',54,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(483,'eng-GB',2,149,12,'',0,0,0,0,'','ezuser'),(634,'eng-GB',1,191,188,'',0,0,0,0,'','ezstring'),(635,'eng-GB',1,191,189,'',0,0,0,0,'','eztext'),(636,'eng-GB',1,191,190,'',0,0,0,0,'','ezboolean'),(637,'eng-GB',1,191,194,'',0,0,0,0,'','ezsubtreesubscription'),(638,'eng-GB',1,192,188,'',0,0,0,0,'','ezstring'),(609,'eng-GB',1,184,188,'',0,0,0,0,'','ezstring'),(610,'eng-GB',1,184,189,'',0,0,0,0,'','eztext'),(611,'eng-GB',1,184,190,'',0,0,0,0,'','ezboolean'),(612,'eng-GB',1,184,194,'',0,0,0,0,'','ezsubtreesubscription'),(613,'eng-GB',1,185,188,'',0,0,0,0,'','ezstring'),(614,'eng-GB',1,185,189,'',0,0,0,0,'','eztext'),(615,'eng-GB',1,185,190,'',0,0,0,0,'','ezboolean'),(616,'eng-GB',1,185,194,'',0,0,0,0,'','ezsubtreesubscription'),(617,'eng-GB',1,186,188,'',0,0,0,0,'','ezstring'),(618,'eng-GB',1,186,189,'',0,0,0,0,'','eztext'),(619,'eng-GB',1,186,190,'',0,0,0,0,'','ezboolean'),(620,'eng-GB',1,186,194,'',0,0,0,0,'','ezsubtreesubscription'),(482,'eng-GB',2,149,9,'yu',0,0,0,0,'yu','ezstring'),(481,'eng-GB',2,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(633,'eng-GB',1,190,194,'',1,0,0,0,'','ezsubtreesubscription'),(603,'eng-GB',1,182,190,'',0,0,0,0,'','ezboolean'),(604,'eng-GB',1,182,194,'',0,0,0,0,'','ezsubtreesubscription'),(605,'eng-GB',1,183,188,'',0,0,0,0,'','ezstring'),(606,'eng-GB',1,183,189,'',0,0,0,0,'','eztext'),(607,'eng-GB',1,183,190,'',0,0,0,0,'','ezboolean'),(608,'eng-GB',1,183,194,'',0,0,0,0,'','ezsubtreesubscription'),(602,'eng-GB',1,182,189,'',0,0,0,0,'','eztext'),(730,'eng-GB',2,14,200,'',0,0,0,0,'','ezimage'),(731,'eng-GB',3,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"administrator_user.\"\n         suffix=\"\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB/administrator_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(732,'eng-GB',1,107,200,'',0,0,0,0,'','ezimage'),(733,'eng-GB',2,107,200,'',0,0,0,0,'','ezimage'),(734,'eng-GB',1,111,200,'',0,0,0,0,'','ezimage'),(735,'eng-GB',1,149,200,'',0,0,0,0,'','ezimage'),(736,'eng-GB',2,149,200,'',0,0,0,0,'','ezimage'),(737,'eng-GB',3,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(729,'eng-GB',1,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(728,'eng-GB',1,10,200,'',0,0,0,0,'','ezimage'),(675,'eng-GB',1,200,189,'sefsefsf\nsf\nsf',0,0,0,0,'','eztext'),(782,'eng-GB',54,56,202,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(151,'eng-GB',52,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(601,'eng-GB',1,182,188,'',0,0,0,0,'','ezstring'),(716,'eng-GB',1,10,199,'',0,0,0,0,'','eztext'),(717,'eng-GB',1,14,199,'',0,0,0,0,'','eztext'),(718,'eng-GB',2,14,199,'',0,0,0,0,'','eztext'),(719,'eng-GB',3,14,199,'developer... ;)',0,0,0,0,'','eztext'),(720,'eng-GB',1,107,199,'',0,0,0,0,'','eztext'),(721,'eng-GB',2,107,199,'',0,0,0,0,'','eztext'),(722,'eng-GB',1,111,199,'',0,0,0,0,'','eztext'),(723,'eng-GB',1,149,199,'',0,0,0,0,'','eztext'),(724,'eng-GB',2,149,199,'',0,0,0,0,'','eztext'),(725,'eng-GB',3,149,199,'',0,0,0,0,'','eztext'),(692,'eng-GB',1,10,197,'',0,0,0,0,'','ezstring'),(693,'eng-GB',1,14,197,'',0,0,0,0,'','ezstring'),(694,'eng-GB',2,14,197,'',0,0,0,0,'','ezstring'),(695,'eng-GB',3,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(696,'eng-GB',1,107,197,'',0,0,0,0,'','ezstring'),(697,'eng-GB',2,107,197,'',0,0,0,0,'','ezstring'),(698,'eng-GB',1,111,197,'',0,0,0,0,'','ezstring'),(699,'eng-GB',1,149,197,'',0,0,0,0,'','ezstring'),(700,'eng-GB',2,149,197,'',0,0,0,0,'','ezstring'),(701,'eng-GB',3,149,197,'',0,0,0,0,'','ezstring'),(152,'eng-GB',54,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"53\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(704,'eng-GB',1,10,198,'',0,0,0,0,'','ezstring'),(705,'eng-GB',1,14,198,'',0,0,0,0,'','ezstring'),(706,'eng-GB',2,14,198,'',0,0,0,0,'','ezstring'),(707,'eng-GB',3,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(708,'eng-GB',1,107,198,'',0,0,0,0,'','ezstring'),(709,'eng-GB',2,107,198,'',0,0,0,0,'','ezstring'),(710,'eng-GB',1,111,198,'',0,0,0,0,'','ezstring'),(711,'eng-GB',1,149,198,'',0,0,0,0,'','ezstring'),(712,'eng-GB',2,149,198,'',0,0,0,0,'','ezstring'),(713,'eng-GB',3,149,198,'',0,0,0,0,'','ezstring'),(680,'eng-GB',2,202,188,'test2',0,0,0,0,'test2','ezstring'),(681,'eng-GB',2,202,189,'sefsefg\nsfes',0,0,0,0,'','eztext'),(682,'eng-GB',2,202,190,'',0,0,0,0,'','ezboolean'),(683,'eng-GB',2,202,194,'',0,0,0,0,'','ezsubtreesubscription'),(690,'eng-GB',1,205,191,'re:test2',0,0,0,0,'re:test2','ezstring'),(691,'eng-GB',1,205,193,'sefw',0,0,0,0,'','eztext'),(687,'eng-GB',5,203,194,'',0,0,0,0,'','ezsubtreesubscription'),(302,'eng-GB',1,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',1,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',1,107,12,'',0,0,0,0,'','ezuser'),(302,'eng-GB',2,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',2,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',2,107,12,'',0,0,0,0,'','ezuser'),(315,'eng-GB',1,111,12,'',0,0,0,0,'','ezuser'),(313,'eng-GB',1,111,8,'vid',0,0,0,0,'vid','ezstring'),(314,'eng-GB',1,111,9,'la',0,0,0,0,'la','ezstring'),(1,'eng-GB',7,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',7,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table class=\"frontpage\"\n           border=\"1\"\n           width=\"100%\">\n      <tr>\n        <th>\n          <paragraph>Latest discussions in music</paragraph>\n        </th>\n        <th>\n          <paragraph>Latest discussions in sports</paragraph>\n        </th>\n      </tr>\n      <tr>\n        <td>\n          <paragraph>\n            <object id=\"141\" />\n          </paragraph>\n        </td>\n        <td>\n          <paragraph>\n            <object id=\"142\" />\n          </paragraph>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(775,'eng-GB',1,217,191,'fsf',0,0,0,0,'fsf','ezstring'),(446,'eng-GB',1,138,4,'Forum',0,0,0,0,'forum','ezstring'),(447,'eng-GB',1,138,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find different discussion forums.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',52,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"50\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB/forum_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',52,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(154,'eng-GB',52,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',52,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',52,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(779,'eng-GB',46,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(776,'eng-GB',1,217,193,'sfsf',0,0,0,0,'','eztext'),(777,'eng-GB',44,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(778,'eng-GB',45,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(151,'eng-GB',53,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',53,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"52\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB/forum_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069244503\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(600,'eng-GB',1,181,194,'',0,0,0,0,'','ezsubtreesubscription'),(591,'eng-GB',1,179,190,'',0,0,0,0,'','ezboolean'),(592,'eng-GB',1,179,194,'',0,0,0,0,'','ezsubtreesubscription'),(593,'eng-GB',1,180,188,'',0,0,0,0,'','ezstring'),(594,'eng-GB',1,180,189,'',0,0,0,0,'','eztext'),(595,'eng-GB',1,180,190,'',0,0,0,0,'','ezboolean'),(596,'eng-GB',1,180,194,'',0,0,0,0,'','ezsubtreesubscription'),(597,'eng-GB',1,181,188,'',0,0,0,0,'','ezstring'),(598,'eng-GB',1,181,189,'',0,0,0,0,'','eztext'),(599,'eng-GB',1,181,190,'',0,0,0,0,'','ezboolean'),(573,'eng-GB',1,175,188,'',0,0,0,0,'','ezstring'),(574,'eng-GB',1,175,189,'',0,0,0,0,'','eztext'),(575,'eng-GB',1,175,190,'',0,0,0,0,'','ezboolean'),(576,'eng-GB',1,175,194,'',0,0,0,0,'','ezsubtreesubscription'),(577,'eng-GB',1,176,188,'',0,0,0,0,'','ezstring'),(578,'eng-GB',1,176,189,'',0,0,0,0,'','eztext'),(579,'eng-GB',1,176,190,'',0,0,0,0,'','ezboolean'),(580,'eng-GB',1,176,194,'',0,0,0,0,'','ezsubtreesubscription'),(581,'eng-GB',1,177,188,'',0,0,0,0,'','ezstring'),(582,'eng-GB',1,177,189,'',0,0,0,0,'','eztext'),(583,'eng-GB',1,177,190,'',0,0,0,0,'','ezboolean'),(584,'eng-GB',1,177,194,'',0,0,0,0,'','ezsubtreesubscription'),(585,'eng-GB',1,178,188,'',0,0,0,0,'','ezstring'),(586,'eng-GB',1,178,189,'',0,0,0,0,'','eztext'),(587,'eng-GB',1,178,190,'',0,0,0,0,'','ezboolean'),(588,'eng-GB',1,178,194,'',0,0,0,0,'','ezsubtreesubscription'),(589,'eng-GB',1,179,188,'',0,0,0,0,'','ezstring'),(590,'eng-GB',1,179,189,'',0,0,0,0,'','eztext'),(561,'eng-GB',1,172,188,'',0,0,0,0,'','ezstring'),(562,'eng-GB',1,172,189,'',0,0,0,0,'','eztext'),(563,'eng-GB',1,172,190,'',0,0,0,0,'','ezboolean'),(564,'eng-GB',1,172,194,'',0,0,0,0,'','ezsubtreesubscription'),(565,'eng-GB',1,173,188,'',0,0,0,0,'','ezstring'),(566,'eng-GB',1,173,189,'',0,0,0,0,'','eztext'),(567,'eng-GB',1,173,190,'',0,0,0,0,'','ezboolean'),(568,'eng-GB',1,173,194,'',0,0,0,0,'','ezsubtreesubscription'),(569,'eng-GB',1,174,188,'',0,0,0,0,'','ezstring'),(570,'eng-GB',1,174,189,'',0,0,0,0,'','eztext'),(571,'eng-GB',1,174,190,'',0,0,0,0,'','ezboolean'),(572,'eng-GB',1,174,194,'',0,0,0,0,'','ezsubtreesubscription'),(560,'eng-GB',1,171,194,'',0,0,0,0,'','ezsubtreesubscription'),(559,'eng-GB',1,171,190,'',0,0,0,0,'','ezboolean'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(150,'eng-GB',55,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',55,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',55,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"54\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(671,'eng-GB',54,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(437,'eng-GB',54,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',54,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(154,'eng-GB',54,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(152,'eng-GB',44,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-44-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-44-eng-GB/forum.gif\"\n         original_filename=\"phpCfM6Z4_600x600_68578.gif\"\n         mime_type=\"original\"\n         width=\"114\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"42\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-44-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-44-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-44-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-44-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(504,'eng-GB',1,156,188,'What about pop?',0,0,0,0,'what about pop?','ezstring'),(502,'eng-GB',1,155,193,'tesetset',0,0,0,0,'','eztext'),(501,'eng-GB',1,155,191,'Foo bar',0,0,0,0,'foo bar','ezstring'),(437,'eng-GB',45,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',44,56,160,'corporate_red',0,0,0,0,'corporate_red','ezpackage'),(154,'eng-GB',44,56,161,'corporate_package',0,0,0,0,'corporate_package','ezstring'),(437,'eng-GB',44,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(506,'eng-GB',1,156,190,'',0,0,0,0,'','ezboolean'),(507,'eng-GB',1,156,194,'',1,0,0,0,'','ezsubtreesubscription'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(508,'eng-GB',1,157,188,'Reply wanted for this topic',0,0,0,0,'reply wanted for this topic','ezstring'),(509,'eng-GB',1,157,189,';) http://wz.nozw.zno',0,0,0,0,'','eztext'),(510,'eng-GB',1,157,190,'',0,0,0,0,'','ezboolean'),(511,'eng-GB',1,157,194,'',1,0,0,0,'','ezsubtreesubscription'),(512,'eng-GB',1,158,191,'This is a reply',0,0,0,0,'this is a reply','ezstring'),(513,'eng-GB',1,158,193,'Test reply..\n\n-bård',0,0,0,0,'','eztext'),(151,'eng-GB',44,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',45,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(654,'eng-GB',1,196,191,'REply',0,0,0,0,'reply','ezstring'),(655,'eng-GB',1,196,193,'sdgflksdjfg sdfg\nsdgfsdfgsdfg ;)',0,0,0,0,'','eztext'),(482,'eng-GB',3,149,9,'yu',0,0,0,0,'yu','ezstring'),(481,'eng-GB',3,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(483,'eng-GB',3,149,12,'',0,0,0,0,'','ezuser'),(150,'eng-GB',46,56,157,'Forum',0,0,0,0,'','ezinisetting'),(150,'eng-GB',50,56,157,'Forum',0,0,0,0,'','ezinisetting'),(150,'eng-GB',52,56,157,'Forum',0,0,0,0,'','ezinisetting'),(670,'eng-GB',44,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',45,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(672,'eng-GB',1,199,191,'uyuiyui',0,0,0,0,'uyuiyui','ezstring'),(673,'eng-GB',1,199,193,'uiyuiyuiyuiy',0,0,0,0,'','eztext'),(674,'eng-GB',1,200,188,'test',0,0,0,0,'test','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(653,'eng-GB',1,195,194,'',0,0,0,0,'','ezsubtreesubscription'),(641,'eng-GB',1,192,194,'',0,0,0,0,'','ezsubtreesubscription'),(642,'eng-GB',1,193,188,'',0,0,0,0,'','ezstring'),(643,'eng-GB',1,193,189,'',0,0,0,0,'','eztext'),(644,'eng-GB',1,193,190,'',0,0,0,0,'','ezboolean'),(645,'eng-GB',1,193,194,'',0,0,0,0,'','ezsubtreesubscription'),(646,'eng-GB',1,194,188,'',0,0,0,0,'','ezstring'),(647,'eng-GB',1,194,189,'',0,0,0,0,'','eztext'),(648,'eng-GB',1,194,190,'',0,0,0,0,'','ezboolean'),(649,'eng-GB',1,194,194,'',0,0,0,0,'','ezsubtreesubscription'),(650,'eng-GB',1,195,188,'Foo',0,0,0,0,'foo','ezstring'),(651,'eng-GB',1,195,189,'sdfgsdfg',0,0,0,0,'','eztext'),(652,'eng-GB',1,195,190,'',0,0,0,0,'','ezboolean'),(153,'eng-GB',45,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(154,'eng-GB',45,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(671,'eng-GB',53,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(154,'eng-GB',46,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',46,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',46,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(150,'eng-GB',45,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',46,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',46,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB/forum.gif\"\n         original_filename=\"phpCfM6Z4_600x600_68578.gif\"\n         mime_type=\"original\"\n         width=\"114\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"45\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB/forum_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-61922323\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',46,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(150,'eng-GB',44,56,157,'Forum',0,0,0,0,'','ezinisetting'),(152,'eng-GB',45,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB/forum.gif\"\n         original_filename=\"phpCfM6Z4_600x600_68578.gif\"\n         mime_type=\"original\"\n         width=\"114\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"44\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB/forum_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-61922323\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(1,'eng-GB',8,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',8,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table class=\"frontpage\">\n      <tr>\n        <th>\n          <paragraph>Latest discussions in music</paragraph>\n        </th>\n        <th>\n          <paragraph>Latest discussions in sports</paragraph>\n        </th>\n      </tr>\n      <tr>\n        <td>\n          <paragraph>\n            <object id=\"141\" />\n          </paragraph>\n        </td>\n        <td>\n          <paragraph>\n            <object id=\"142\" />\n          </paragraph>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <paragraph>\n    <table class=\"frontpage\">\n      <tr>\n        <th>\n          <paragraph>Latest news</paragraph>\n        </th>\n      </tr>\n      <tr>\n        <td>\n          <paragraph>\n            <object id=\"49\" />\n          </paragraph>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(448,'eng-GB',1,139,188,'This is a new topic',0,0,0,0,'this is a new topic','ezstring'),(449,'eng-GB',1,139,189,'My message body for this new topic.',0,0,0,0,'','eztext'),(450,'eng-GB',1,139,190,'',0,0,0,0,'','ezboolean'),(492,'eng-GB',1,153,191,'My reply',0,0,0,0,'my reply','ezstring'),(493,'eng-GB',1,153,193,'cool\n\n;) :) \n\nhttp://www.exampleurl.com/foo',0,0,0,0,'','eztext'),(454,'eng-GB',1,141,186,'Music discussion',0,0,0,0,'music discussion','ezstring'),(455,'eng-GB',1,141,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss music here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(448,'eng-GB',2,139,188,'This is a new topic',0,0,0,0,'this is a new topic','ezstring'),(449,'eng-GB',2,139,189,'My message body for this new topic.',0,0,0,0,'','eztext'),(450,'eng-GB',2,139,190,'',0,0,0,0,'','ezboolean'),(456,'eng-GB',1,142,186,'Sports discussion',0,0,0,0,'sports discussion','ezstring'),(457,'eng-GB',1,142,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss sports here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(780,'eng-GB',50,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(781,'eng-GB',52,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(782,'eng-GB',53,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(150,'eng-GB',54,56,157,'Forum',0,0,0,0,'','ezinisetting'),(488,'eng-GB',1,151,189,'ghfn\ngnf\nnfnfn\n',0,0,0,0,'','eztext'),(489,'eng-GB',1,151,190,'',0,0,0,0,'','ezboolean'),(483,'eng-GB',4,149,12,'',0,0,0,0,'','ezuser'),(481,'eng-GB',4,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',4,149,9,'yu',0,0,0,0,'yu','ezstring'),(487,'eng-GB',1,151,188,'Football',0,0,0,0,'football','ezstring'),(481,'eng-GB',1,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',1,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',1,149,12,'',0,0,0,0,'','ezuser'),(505,'eng-GB',1,156,189,';) :) http://www.foo.bar.com\n\nehh.',0,0,0,0,'','eztext'),(496,'eng-GB',1,151,194,'',0,0,0,0,'','ezsubtreesubscription'),(494,'eng-GB',1,139,194,'',0,0,0,0,'','ezsubtreesubscription'),(495,'eng-GB',2,139,194,'',0,0,0,0,'','ezsubtreesubscription'),(446,'eng-GB',2,138,4,'Discussions',0,0,0,0,'discussions','ezstring'),(447,'eng-GB',2,138,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Here you will find different discussion forums.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(701,'eng-GB',4,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',4,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',4,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',4,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-4-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',5,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',5,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',5,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',5,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',5,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',5,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',5,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-5-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',6,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',6,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',6,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',6,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',6,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',6,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',6,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-6-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-6-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"5\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',7,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',7,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',7,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',7,149,197,'Derector',0,0,0,0,'derector','ezstring'),(713,'eng-GB',7,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',7,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',7,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',8,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',8,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',8,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',8,149,197,'Director',0,0,0,0,'director','ezstring'),(713,'eng-GB',8,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',8,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',8,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"wenyue_yu_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"139\"\n         height=\"200\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',9,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',9,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',9,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',9,149,197,'Director',0,0,0,0,'director','ezstring'),(713,'eng-GB',9,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',9,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',9,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"wenyue_yu_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"139\"\n         height=\"200\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',4,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',4,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',4,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',4,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(751,'eng-GB',1,209,188,'hjg dghsdjgf',0,0,0,0,'hjg dghsdjgf','ezstring'),(752,'eng-GB',1,209,189,'sdjgfsdjgh',0,0,0,0,'','eztext'),(753,'eng-GB',1,209,190,'',1,0,0,1,'','ezboolean'),(754,'eng-GB',1,209,194,'',0,0,0,0,'','ezsubtreesubscription'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',5,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',5,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',5,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',5,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"731\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"administrator_user_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(755,'eng-GB',1,210,191,'dfghd fghklj',0,0,0,0,'dfghd fghklj','ezstring'),(756,'eng-GB',1,210,193,'sdfgsd fgs\n sdfg sdfg\n   sdfgsdgfsdfg\n       sdfgsdgfsdgf',0,0,0,0,'','eztext'),(757,'eng-GB',1,141,201,'',0,0,0,0,'','ezimage'),(758,'eng-GB',1,142,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(454,'eng-GB',2,141,186,'Music discussion',0,0,0,0,'music discussion','ezstring'),(455,'eng-GB',2,141,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss music here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(757,'eng-GB',2,141,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"music_discussion.gif\"\n         suffix=\"gif\"\n         basename=\"music_discussion\"\n         dirpath=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB\"\n         url=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB/music_discussion.gif\"\n         original_filename=\"note.gif\"\n         mime_type=\"original\"\n         width=\"80\"\n         height=\"80\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"music_discussion_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB\"\n         url=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB/music_discussion_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"music_discussion_small.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB\"\n         url=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB/music_discussion_small.gif\"\n         mime_type=\"image/gif\"\n         width=\"100\"\n         height=\"100\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(456,'eng-GB',2,142,186,'Sports discussion',0,0,0,0,'sports discussion','ezstring'),(457,'eng-GB',2,142,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss sports here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(758,'eng-GB',2,142,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"sports_discussion.gif\"\n         suffix=\"gif\"\n         basename=\"sports_discussion\"\n         dirpath=\"var/forum/storage/images/discussions/sports_discussion/758-2-eng-GB\"\n         url=\"var/forum/storage/images/discussions/sports_discussion/758-2-eng-GB/sports_discussion.gif\"\n         original_filename=\"ball.gif\"\n         mime_type=\"original\"\n         width=\"80\"\n         height=\"80\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(759,'eng-GB',1,211,4,'Folder',0,0,0,0,'folder','ezstring'),(760,'eng-GB',1,211,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Diverse temaer</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(759,'eng-GB',2,211,4,'Forum main group',0,0,0,0,'forum main group','ezstring'),(760,'eng-GB',2,211,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>No description. This text may not be shown?</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(454,'eng-GB',3,141,186,'Music discussion',0,0,0,0,'music discussion','ezstring'),(455,'eng-GB',3,141,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss music here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(757,'eng-GB',3,141,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"music_discussion.gif\"\n         suffix=\"gif\"\n         basename=\"music_discussion\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB/music_discussion.gif\"\n         original_filename=\"note.gif\"\n         mime_type=\"original\"\n         width=\"80\"\n         height=\"80\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"757\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"music_discussion_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB/music_discussion_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"music_discussion_small.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB/music_discussion_small.gif\"\n         mime_type=\"image/gif\"\n         width=\"100\"\n         height=\"100\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"music_discussion_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB/music_discussion_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"200\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(456,'eng-GB',3,142,186,'Sports discussion',0,0,0,0,'sports discussion','ezstring'),(457,'eng-GB',3,142,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss sports here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(758,'eng-GB',3,142,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"sports_discussion.gif\"\n         suffix=\"gif\"\n         basename=\"sports_discussion\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/sports_discussion/758-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/sports_discussion/758-3-eng-GB/sports_discussion.gif\"\n         original_filename=\"ball.gif\"\n         mime_type=\"original\"\n         width=\"80\"\n         height=\"80\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"758\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"sports_discussion_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/sports_discussion/758-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/sports_discussion/758-3-eng-GB/sports_discussion_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"sports_discussion_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/sports_discussion/758-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/sports_discussion/758-3-eng-GB/sports_discussion_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"200\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(761,'eng-GB',1,212,191,'Annen melding',0,0,0,0,'annen melding','ezstring'),(762,'eng-GB',1,212,193,'The quick brown fox jumps over lazy dogs. The quick brown fox jumps over lazy dogs. The quick brown fox jumps over lazy dogs. The quick brown fox jumps over lazy dogs. The quick brown fox jumps over lazy dogs. The quick brown fox jumps over lazy dogs. The quick brown fox jumps over lazy dogs. ',0,0,0,0,'','eztext'),(767,'eng-GB',1,214,188,'New topic',0,0,0,0,'new topic','ezstring'),(768,'eng-GB',1,214,189,'Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. Test. ',0,0,0,0,'','eztext'),(769,'eng-GB',1,214,190,'',0,0,0,0,'','ezboolean'),(770,'eng-GB',1,214,194,'',0,0,0,0,'','ezsubtreesubscription'),(771,'eng-GB',1,215,191,'fowehfowhi',0,0,0,0,'fowehfowhi','ezstring'),(772,'eng-GB',1,215,193,'ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ljdfowihogho ',0,0,0,0,'','eztext'),(437,'eng-GB',53,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(154,'eng-GB',53,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(112,'eng-GB',3,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',3,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',53,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(153,'eng-GB',50,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(154,'eng-GB',50,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',50,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',50,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',55,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(154,'eng-GB',55,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',55,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',55,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(782,'eng-GB',55,56,202,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(150,'eng-GB',56,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum.gif\"\n         original_filename=\"forum.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069687512\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069687516\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069687516\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',56,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(154,'eng-GB',56,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',56,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',56,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(782,'eng-GB',56,56,202,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring');
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
INSERT INTO ezcontentobject_link VALUES (1,1,4,49),(2,1,4,141),(3,1,4,142),(4,1,5,49),(5,1,5,141),(6,1,5,142),(7,1,6,49),(8,1,6,141),(9,1,6,142),(10,1,7,49),(11,1,7,141),(12,1,7,142),(13,1,8,49),(14,1,8,141),(15,1,8,142);
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(190,'lkj ssssstick',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(164,'latest msg (not sticky)',1,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(165,'',1,'eng-GB','eng-GB'),(163,'reply',1,'eng-GB','eng-GB'),(162,'New topic (sticky)',1,'eng-GB','eng-GB'),(139,'This is a new topic',1,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(87,'New Company',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(170,'Reply 2',1,'eng-GB','eng-GB'),(166,'Not sticky 2',1,'eng-GB','eng-GB'),(157,'Reply wanted for this topic',2,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(169,'test',1,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(138,'Forum',1,'eng-GB','eng-GB'),(168,'',1,'eng-GB','eng-GB'),(167,'Important [sticky]',1,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(160,'News bulletin',1,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(153,'My reply',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(107,'John Doe',1,'eng-GB','eng-GB'),(107,'John Doe',2,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(111,'vid la',1,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(141,'Music discussion',1,'eng-GB','eng-GB'),(139,'This is a new topic',2,'eng-GB','eng-GB'),(142,'Sports discussion',1,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(155,'Foo bar',1,'eng-GB','eng-GB'),(149,'wenyue yu',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(151,'Football',1,'eng-GB','eng-GB'),(138,'Discussions',2,'eng-GB','eng-GB'),(156,'What about pop?',1,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(157,'Reply wanted for this topic',1,'eng-GB','eng-GB'),(158,'This is a reply',1,'eng-GB','eng-GB'),(171,'',1,'eng-GB','eng-GB'),(172,'',1,'eng-GB','eng-GB'),(173,'',1,'eng-GB','eng-GB'),(174,'',1,'eng-GB','eng-GB'),(175,'',1,'eng-GB','eng-GB'),(176,'',1,'eng-GB','eng-GB'),(177,'',1,'eng-GB','eng-GB'),(178,'',1,'eng-GB','eng-GB'),(179,'',1,'eng-GB','eng-GB'),(180,'',1,'eng-GB','eng-GB'),(181,'',1,'eng-GB','eng-GB'),(182,'',1,'eng-GB','eng-GB'),(183,'',1,'eng-GB','eng-GB'),(184,'',1,'eng-GB','eng-GB'),(185,'',1,'eng-GB','eng-GB'),(186,'New Forum topic',1,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(149,'wenyue yu',2,'eng-GB','eng-GB'),(191,'',1,'eng-GB','eng-GB'),(192,'',1,'eng-GB','eng-GB'),(193,'',1,'eng-GB','eng-GB'),(194,'New Forum topic',1,'eng-GB','eng-GB'),(195,'Foo',1,'eng-GB','eng-GB'),(196,'REply',1,'eng-GB','eng-GB'),(149,'wenyue yu',3,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(199,'uyuiyui',1,'eng-GB','eng-GB'),(200,'test',1,'eng-GB','eng-GB'),(201,'Re:test',1,'eng-GB','eng-GB'),(202,'test2',1,'eng-GB','eng-GB'),(203,'t4',1,'eng-GB','eng-GB'),(203,'t4',2,'eng-GB','eng-GB'),(203,'t4',3,'eng-GB','eng-GB'),(204,'klj jkl klj',1,'eng-GB','eng-GB'),(203,'t4',4,'eng-GB','eng-GB'),(203,'t4',5,'eng-GB','eng-GB'),(202,'test2',2,'eng-GB','eng-GB'),(205,'re:test2',1,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(206,'Bård Farstad',1,'eng-GB','eng-GB'),(207,'My reply',1,'eng-GB','eng-GB'),(208,'re:test',1,'eng-GB','eng-GB'),(149,'wenyue yu',4,'eng-GB','eng-GB'),(149,'wenyue yu',5,'eng-GB','eng-GB'),(149,'wenyue yu',6,'eng-GB','eng-GB'),(149,'wenyue yu',7,'eng-GB','eng-GB'),(149,'wenyue yu',8,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(209,'hjg dghsdjgf',1,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(210,'dfghd fghklj',1,'eng-GB','eng-GB'),(141,'Music discussion',2,'eng-GB','eng-GB'),(142,'Sports discussion',2,'eng-GB','eng-GB'),(211,'Folder',1,'eng-GB','eng-GB'),(211,'Forum main group',2,'eng-GB','eng-GB'),(141,'Music discussion',3,'eng-GB','eng-GB'),(142,'Sports discussion',3,'eng-GB','eng-GB'),(212,'Annen melding',1,'eng-GB','eng-GB'),(214,'New topic',1,'eng-GB','eng-GB'),(215,'fowehfowhi',1,'eng-GB','eng-GB'),(14,'Administrator User',6,'eng-GB','eng-GB'),(1,'Forum',6,'eng-GB','eng-GB'),(1,'Forum',7,'eng-GB','eng-GB'),(1,'Forum',8,'eng-GB','eng-GB'),(56,'Forum',50,'eng-GB','eng-GB'),(56,'Forum',52,'eng-GB','eng-GB'),(56,'Forum',53,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(217,'fsf',1,'eng-GB','eng-GB'),(56,'Forum',54,'eng-GB','eng-GB'),(56,'Forum',56,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,8,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,5,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(135,114,190,1,1,5,'/1/2/111/152/114/135/',1,1,0,'discussions/forum_main_group/music_discussion/lkj_ssssstick',135),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,9,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,10,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,1,1,2,'/1/2/50/',9,1,0,'news',50),(130,114,164,1,1,5,'/1/2/111/152/114/130/',9,1,0,'discussions/forum_main_group/music_discussion/latest_msg_not_sticky',130),(54,48,56,56,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/forum',54),(129,128,163,1,1,6,'/1/2/111/152/114/128/129/',9,1,0,'discussions/forum_main_group/music_discussion/new_topic_sticky/reply',129),(126,50,160,1,1,3,'/1/2/50/126/',9,1,0,'news/news_bulletin',126),(127,2,161,1,1,2,'/1/2/127/',9,1,0,'about_this_forum',127),(128,114,162,1,1,5,'/1/2/111/152/114/128/',9,1,0,'discussions/forum_main_group/music_discussion/new_topic_sticky',128),(131,114,166,1,1,5,'/1/2/111/152/114/131/',9,1,0,'discussions/forum_main_group/music_discussion/not_sticky_2',131),(91,14,107,2,1,3,'/1/5/14/91/',9,1,0,'users/editors/john_doe',91),(92,14,111,1,1,3,'/1/5/14/92/',9,1,0,'users/editors/vid_la',92),(112,114,139,2,1,5,'/1/2/111/152/114/112/',9,1,0,'discussions/forum_main_group/music_discussion/this_is_a_new_topic',112),(111,2,138,2,1,2,'/1/2/111/',9,1,0,'discussions',111),(95,46,115,4,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,3,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(114,152,141,3,1,4,'/1/2/111/152/114/',9,1,0,'discussions/forum_main_group/music_discussion',114),(115,152,142,3,1,4,'/1/2/111/152/115/',9,1,0,'discussions/forum_main_group/sports_discussion',115),(117,13,149,8,1,3,'/1/5/13/117/',9,1,0,'users/administrator_users/wenyue_yu',117),(122,112,155,1,1,6,'/1/2/111/152/114/112/122/',9,1,0,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/foo_bar',122),(119,115,151,1,1,5,'/1/2/111/152/115/119/',9,1,0,'discussions/forum_main_group/sports_discussion/football',119),(120,112,153,1,1,6,'/1/2/111/152/114/112/120/',9,1,0,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/my_reply',120),(123,114,156,1,1,5,'/1/2/111/152/114/123/',9,1,0,'discussions/forum_main_group/music_discussion/what_about_pop',123),(124,114,157,1,1,5,'/1/2/111/152/114/124/',9,1,0,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic',124),(125,124,158,1,1,6,'/1/2/111/152/114/124/125/',9,1,0,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic/this_is_a_reply',125),(132,114,167,1,1,5,'/1/2/111/152/114/132/',9,1,0,'discussions/forum_main_group/music_discussion/important_sticky',132),(133,119,170,1,1,6,'/1/2/111/152/115/119/133/',1,1,0,'discussions/forum_main_group/sports_discussion/football/reply_2',133),(136,114,195,1,1,5,'/1/2/111/152/114/136/',1,1,0,'discussions/forum_main_group/music_discussion/foo',136),(137,135,196,1,1,6,'/1/2/111/152/114/135/137/',1,1,0,'discussions/forum_main_group/music_discussion/lkj_ssssstick/reply',137),(141,114,202,2,1,5,'/1/2/111/152/114/141/',1,1,0,'discussions/forum_main_group/music_discussion/test2',141),(140,135,199,1,1,6,'/1/2/111/152/114/135/140/',1,1,0,'discussions/forum_main_group/music_discussion/lkj_ssssstick/uyuiyui',140),(142,114,203,5,1,5,'/1/2/111/152/114/142/',9,1,0,'discussions/forum_main_group/music_discussion/t4',142),(143,135,204,1,1,6,'/1/2/111/152/114/135/143/',1,1,0,'discussions/forum_main_group/music_discussion/lkj_ssssstick/klj_jkl_klj',143),(144,141,205,1,1,6,'/1/2/111/152/114/141/144/',1,1,0,'discussions/forum_main_group/music_discussion/test2/retest2',144),(145,13,206,1,1,3,'/1/5/13/145/',9,1,0,'users/administrator_users/brd_farstad',145),(146,135,207,1,1,6,'/1/2/111/152/114/135/146/',1,1,0,'discussions/forum_main_group/music_discussion/lkj_ssssstick/my_reply',146),(147,135,208,1,1,6,'/1/2/111/152/114/135/147/',1,1,0,'discussions/forum_main_group/music_discussion/lkj_ssssstick/retest',147),(148,5,209,1,1,2,'/1/5/148/',1,1,0,'users/hjg_dghsdjgf',148),(149,114,209,1,1,5,'/1/2/111/152/114/149/',1,1,0,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf',148),(150,5,210,1,1,2,'/1/5/150/',1,1,0,'users/dfghd_fghklj',150),(151,149,210,1,1,6,'/1/2/111/152/114/149/151/',1,1,0,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/dfghd_fghklj',150),(152,111,211,2,1,3,'/1/2/111/152/',9,1,0,'discussions/forum_main_group',152),(153,149,212,1,1,6,'/1/2/111/152/114/149/153/',1,1,0,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/annen_melding',153),(154,114,214,1,1,5,'/1/2/111/152/114/154/',1,1,0,'discussions/forum_main_group/music_discussion/new_topic',154),(155,154,215,1,1,6,'/1/2/111/152/114/154/155/',1,1,0,'discussions/forum_main_group/music_discussion/new_topic/fowehfowhi',155);
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
INSERT INTO ezcontentobject_version VALUES (810,1,14,6,1068735167,1068736490,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,3,0,0),(756,190,14,1,1068110957,1068111803,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(480,45,14,1,1066388718,1066388815,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(822,43,14,9,1068825480,1068825487,1,0,0),(490,49,14,1,1066398007,1066398020,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(741,175,149,1,1068108534,1068108624,0,0,0),(767,56,14,46,1068113507,1068113546,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(734,168,149,1,1068048359,1068048594,0,0,0),(733,167,14,1,1068048161,1068048180,1,0,0),(732,166,14,1,1068048129,1068048145,1,0,0),(731,165,149,1,1068048190,1068048359,0,0,0),(724,160,14,1,1068047416,1068047455,1,0,0),(727,162,14,1,1068047815,1068047841,1,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(818,56,14,52,1068822388,1068822406,3,0,0),(725,161,14,1,1068047518,1068047603,1,0,0),(717,56,14,45,1068043009,1068043048,3,0,0),(821,115,14,4,1068825440,1068825448,1,0,0),(740,174,149,1,1068050123,1068108534,0,0,0),(715,56,14,44,1068042890,1068042919,3,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(816,56,14,50,1068821705,1068821744,3,0,0),(714,153,14,1,1068042077,1068042105,1,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(698,139,14,1,1068036426,1068036445,3,0,0),(739,173,149,1,1068050088,1068050123,0,0,0),(736,170,149,1,1068048741,1068048760,1,0,0),(730,158,149,2,1068048071,1068048071,0,0,0),(738,172,149,1,1068049706,1068050088,0,0,0),(735,169,149,1,1068048594,1068048622,0,0,0),(820,46,14,3,1068825403,1068825403,0,0,0),(696,138,14,1,1068036042,1068036060,3,0,0),(737,171,149,1,1068049618,1068049706,0,0,0),(819,56,14,53,1068825229,1068825245,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(728,163,14,1,1068047854,1068047867,1,0,0),(729,164,14,1,1068047881,1068047893,1,0,0),(598,107,14,1,1066916843,1066916865,3,0,0),(599,107,14,2,1066916931,1066916941,1,0,0),(811,1,14,7,1068736833,1068737860,3,1,0),(604,111,14,1,1066917488,1066917523,1,0,0),(823,45,14,10,1068825496,1068825502,1,0,0),(824,116,14,3,1068825510,1068825521,1,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(812,1,14,8,1068807642,1068810446,1,1,0),(700,141,14,1,1068036570,1068036586,3,0,0),(701,139,14,2,1068036596,1068036624,1,0,0),(702,142,14,1,1068036643,1068036660,3,0,0),(828,56,14,56,1069687492,1069687511,1,0,0),(827,56,14,55,1069687484,1069687484,0,0,0),(719,156,14,1,1068044264,1068044289,1,0,0),(709,149,14,1,1068040987,1068041016,3,0,0),(718,155,14,1,1068043894,1068043907,1,0,0),(711,151,149,1,1068041814,1068041848,1,0,0),(712,138,14,2,1068041924,1068041936,1,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(721,157,14,1,1068044381,1068044407,1,0,0),(722,158,14,1,1068044455,1068044532,1,0,0),(742,176,149,1,1068108624,1068108805,0,0,0),(743,177,149,1,1068108805,1068108834,0,0,0),(744,178,149,1,1068108834,1068108898,0,0,0),(745,179,149,1,1068108898,1068109016,0,0,0),(746,180,149,1,1068109016,1068109220,0,0,0),(747,181,149,1,1068109220,1068109255,0,0,0),(748,182,149,1,1068109255,1068109498,0,0,0),(749,183,149,1,1068109498,1068109663,0,0,0),(750,184,149,1,1068109663,1068109781,0,0,0),(751,185,149,1,1068109781,1068109829,0,0,0),(752,186,149,1,1068109829,1068109829,0,0,0),(826,56,14,54,1069416499,1069416519,3,0,0),(757,149,14,2,1068111093,1068111116,3,0,0),(825,217,14,1,1069244541,1069244548,0,0,0),(758,191,149,1,1068111317,1068111376,0,0,0),(759,192,149,1,1068111376,1068111870,0,0,0),(760,193,149,1,1068111870,1068111917,0,0,0),(761,194,149,1,1068111917,1068111917,0,0,0),(762,195,14,1,1068111926,1068111948,1,0,0),(763,196,14,1,1068112173,1068112186,1,0,0),(766,149,14,3,1068112999,1068113012,3,0,0),(768,199,14,1,1068114943,1068114951,1,0,0),(769,200,149,1,1068120480,1068120496,0,0,0),(770,201,149,1,1068120737,1068120756,0,0,0),(771,202,149,1,1068120964,1068120979,3,0,0),(772,203,14,1,1068121361,1068121378,3,0,0),(773,203,14,2,1068121490,1068121501,3,0,0),(774,203,14,3,1068121619,1068121626,3,0,0),(775,204,14,1,1068121565,1068121576,1,0,0),(776,203,14,4,1068121910,1068121921,3,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(778,203,14,5,1068122243,1068122252,1,0,0),(779,202,149,2,1068122309,1068122319,1,0,0),(780,205,149,1,1068122369,1068122383,1,0,0),(782,206,14,1,1068123519,1068123599,1,0,0),(783,207,206,1,1068123637,1068123651,1,0,0),(784,208,149,1,1068126580,1068126596,1,0,0),(785,149,149,4,1068129024,1068129067,3,0,0),(786,149,149,5,1068129453,1068129479,3,0,0),(787,149,149,6,1068129554,1068129569,3,0,0),(789,149,149,7,1068130370,1068130443,3,0,0),(790,149,149,8,1068130529,1068130543,1,0,0),(791,149,149,9,1068132647,1068132647,0,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(795,209,14,1,1068468116,1068468127,1,0,0),(796,14,14,5,1068468183,1068468218,1,0,0),(797,210,14,1,1068468561,1068468573,1,0,0),(798,141,14,2,1068565517,1068565749,3,0,0),(799,142,14,2,1068567602,1068567618,3,0,0),(800,211,14,1,1068640049,1068640085,3,0,0),(801,211,14,2,1068640100,1068640156,1,0,0),(802,141,14,3,1068640282,1068640319,1,0,0),(803,142,14,3,1068640339,1068640370,1,0,0),(804,212,14,1,1068722039,1068726575,1,0,0),(806,214,14,1,1068727150,1068727165,1,0,0),(807,215,14,1,1068727934,1068728049,1,0,0);
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
INSERT INTO ezimagefile VALUES (1,152,'var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum.jpg'),(2,152,'var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_reference.jpg'),(3,152,'var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_medium.jpg'),(5,152,'var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum.gif'),(6,152,'var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum_reference.gif'),(7,152,'var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum_medium.gif');
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
INSERT INTO eznode_assignment VALUES (516,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(460,190,1,114,1,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(191,45,1,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(528,43,9,46,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(471,56,46,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(445,175,1,2,1,1,0,0,0),(438,168,1,2,1,1,0,0,0),(421,56,45,48,9,1,1,0,0),(437,167,1,114,9,1,1,0,0),(435,165,1,115,1,1,0,0,0),(436,166,1,114,9,1,1,0,0),(428,160,1,50,9,1,1,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(524,56,52,48,9,1,1,0,0),(399,138,1,2,9,1,1,0,0),(527,115,4,46,9,1,1,0,0),(444,174,1,2,1,1,0,0,0),(419,56,44,48,9,1,1,0,0),(321,45,6,46,9,1,1,0,0),(522,56,50,48,9,1,1,0,0),(418,153,1,112,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(401,139,1,111,9,1,1,0,0),(443,173,1,2,1,1,0,0,0),(439,169,1,2,1,1,1,0,0),(433,164,1,114,9,1,1,0,0),(442,172,1,2,1,1,0,0,0),(440,170,1,119,1,1,1,0,0),(526,46,3,44,9,1,1,0,0),(432,163,1,128,9,1,1,0,0),(441,171,1,115,1,1,0,0,0),(335,45,8,46,9,1,1,0,0),(525,56,53,48,9,1,1,0,0),(431,162,1,114,9,1,1,0,0),(434,158,2,124,9,1,1,0,0),(300,107,1,14,9,1,1,0,0),(301,107,2,14,9,1,1,0,0),(517,1,7,1,9,1,1,0,0),(306,111,1,14,9,1,1,0,0),(529,45,10,46,9,1,1,0,0),(530,116,3,46,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(518,1,8,1,9,1,1,0,0),(403,141,1,111,9,1,1,0,0),(406,142,1,111,9,1,1,0,0),(405,139,2,114,9,1,1,111,0),(534,56,56,48,9,1,1,0,0),(533,56,55,48,9,1,1,0,0),(423,156,1,114,9,1,1,0,0),(413,149,1,13,9,1,1,0,0),(422,155,1,112,9,1,1,0,0),(415,151,1,115,9,1,1,0,0),(416,138,2,2,9,1,1,0,0),(424,14,2,13,9,1,1,0,0),(425,157,1,114,9,1,1,0,0),(426,158,1,124,9,1,1,0,0),(446,176,1,2,1,1,0,0,0),(447,177,1,2,1,1,0,0,0),(448,178,1,2,1,1,0,0,0),(449,179,1,2,1,1,0,0,0),(450,180,1,2,1,1,0,0,0),(451,181,1,2,1,1,0,0,0),(452,182,1,2,1,1,0,0,0),(453,183,1,2,1,1,0,0,0),(454,184,1,2,1,1,0,0,0),(455,185,1,2,1,1,0,0,0),(456,186,1,2,1,1,1,0,0),(532,56,54,48,9,1,1,0,0),(461,149,2,13,9,1,1,0,0),(531,217,1,154,1,1,1,0,0),(462,191,1,115,1,1,0,0,0),(463,192,1,2,1,1,0,0,0),(464,193,1,2,1,1,0,0,0),(465,194,1,2,1,1,1,0,0),(466,195,1,114,1,1,1,0,0),(467,196,1,135,1,1,1,0,0),(470,149,3,13,9,1,1,0,0),(472,199,1,135,1,1,1,0,0),(473,200,1,114,1,1,1,0,0),(474,201,1,135,1,1,1,0,0),(475,202,1,114,1,1,1,0,0),(476,203,1,114,9,1,1,0,0),(477,203,2,114,9,1,1,0,0),(478,203,3,114,9,1,1,0,0),(479,204,1,135,1,1,1,0,0),(480,203,4,114,9,1,1,0,0),(481,14,3,13,9,1,1,0,0),(482,203,5,114,9,1,1,0,0),(483,202,2,114,1,1,1,0,0),(484,205,1,141,1,1,1,0,0),(486,206,1,13,9,1,1,0,0),(487,207,1,135,1,1,1,0,0),(488,208,1,135,1,1,1,0,0),(489,149,4,13,9,1,1,0,0),(490,149,5,13,9,1,1,0,0),(491,149,6,13,9,1,1,0,0),(493,149,7,13,9,1,1,0,0),(494,149,8,13,9,1,1,0,0),(495,149,9,13,9,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(499,209,1,114,1,1,0,0,0),(500,14,5,13,9,1,1,0,0),(501,210,1,149,1,1,0,0,0),(502,141,2,111,9,1,1,0,0),(503,142,2,111,9,1,1,0,0),(504,211,1,111,9,1,1,0,0),(505,211,2,111,9,1,1,0,0),(510,212,1,149,1,1,1,0,0),(507,141,3,152,9,1,1,111,0),(509,142,3,152,9,1,1,111,0),(512,214,1,114,1,1,1,0,0),(513,215,1,154,1,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (242,0,'ezpublish',56,56,0,0,'','','',''),(241,0,'ezpublish',56,54,0,0,'','','',''),(240,0,'ezpublish',116,3,0,0,'','','',''),(239,0,'ezpublish',45,10,0,0,'','','',''),(238,0,'ezpublish',43,9,0,0,'','','',''),(237,0,'ezpublish',115,4,0,0,'','','',''),(236,0,'ezpublish',56,53,0,0,'','','',''),(235,0,'ezpublish',56,52,0,0,'','','',''),(234,0,'ezpublish',56,50,0,0,'','','',''),(233,0,'ezpublish',1,8,0,0,'','','',''),(232,0,'ezpublish',1,7,0,0,'','','',''),(231,0,'ezpublish',1,6,0,0,'','','',''),(230,0,'ezpublish',215,1,0,0,'','','',''),(229,0,'ezpublish',214,1,0,0,'','','',''),(228,0,'ezpublish',212,1,0,0,'','','',''),(227,0,'ezpublish',142,3,0,0,'','','',''),(226,0,'ezpublish',141,3,0,0,'','','',''),(225,0,'ezpublish',211,2,0,0,'','','',''),(224,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',142,2,0,0,'','','',''),(222,0,'ezpublish',141,2,0,0,'','','',''),(221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','','');
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(341,8,'read','content','*'),(378,1,'read','content',''),(377,1,'login','user','*');
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
INSERT INTO ezpolicy_limitation VALUES (298,378,'Class',0,'read','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (584,298,'22'),(583,298,'21'),(582,298,'20'),(581,298,'12'),(580,298,'10'),(579,298,'5'),(578,298,'2'),(577,298,'1');
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
INSERT INTO ezsearch_object_word_link VALUES (2930,190,1273,0,0,0,1274,21,1068111804,1,188,'',0),(3025,149,1340,0,4,1316,0,4,1068041016,2,199,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(3285,43,1412,0,2,1411,0,14,1066384365,11,155,'',0),(3284,43,1411,0,1,1410,1412,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(3283,43,1410,0,0,0,1411,14,1066384365,11,152,'',0),(3291,45,1414,0,5,1413,0,14,1066388816,11,155,'',0),(3290,45,1413,0,4,25,1414,14,1066388816,11,155,'',0),(3289,45,25,0,3,34,1413,14,1066388816,11,155,'',0),(3288,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(61,49,37,0,0,0,0,1,1066398020,4,4,'',0),(2264,160,943,0,20,1146,944,2,1068047455,4,120,'',0),(2263,160,1146,0,19,1145,943,2,1068047455,4,120,'',0),(2246,160,73,0,2,1141,74,2,1068047455,4,120,'',0),(2244,160,37,0,0,0,1141,2,1068047455,4,1,'',0),(2245,160,1141,0,1,37,73,2,1068047455,4,1,'',0),(2777,161,1206,0,273,1205,1158,10,1068047603,1,141,'',0),(2776,161,1205,0,272,1204,1206,10,1068047603,1,141,'',0),(2775,161,1204,0,271,1203,1205,10,1068047603,1,141,'',0),(2774,161,1203,0,270,1202,1204,10,1068047603,1,141,'',0),(2773,161,1202,0,269,1201,1203,10,1068047603,1,141,'',0),(2772,161,1201,0,268,1200,1202,10,1068047603,1,141,'',0),(2771,161,1200,0,267,1171,1201,10,1068047603,1,141,'',0),(2770,161,1171,0,266,1153,1200,10,1068047603,1,141,'',0),(2769,161,1153,0,265,1199,1171,10,1068047603,1,141,'',0),(2768,161,1199,0,264,1198,1153,10,1068047603,1,141,'',0),(2767,161,1198,0,263,1197,1199,10,1068047603,1,141,'',0),(2766,161,1197,0,262,1196,1198,10,1068047603,1,141,'',0),(2765,161,1196,0,261,1195,1197,10,1068047603,1,141,'',0),(2764,161,1195,0,260,943,1196,10,1068047603,1,141,'',0),(2763,161,943,0,259,1158,1195,10,1068047603,1,141,'',0),(2762,161,1158,0,258,1194,943,10,1068047603,1,141,'',0),(2761,161,1194,0,257,1193,1158,10,1068047603,1,141,'',0),(2760,161,1193,0,256,1175,1194,10,1068047603,1,141,'',0),(2759,161,1175,0,255,1153,1193,10,1068047603,1,141,'',0),(2758,161,1153,0,254,1192,1175,10,1068047603,1,141,'',0),(2757,161,1192,0,253,1191,1153,10,1068047603,1,141,'',0),(2756,161,1191,0,252,1190,1192,10,1068047603,1,141,'',0),(2755,161,1190,0,251,1158,1191,10,1068047603,1,141,'',0),(2754,161,1158,0,250,1190,1190,10,1068047603,1,141,'',0),(2753,161,1190,0,249,1189,1158,10,1068047603,1,141,'',0),(2752,161,1189,0,248,1188,1190,10,1068047603,1,141,'',0),(2751,161,1188,0,247,1165,1189,10,1068047603,1,141,'',0),(2750,161,1165,0,246,1187,1188,10,1068047603,1,141,'',0),(2749,161,1187,0,245,1186,1165,10,1068047603,1,141,'',0),(2748,161,1186,0,244,1185,1187,10,1068047603,1,141,'',0),(2747,161,1185,0,243,1184,1186,10,1068047603,1,141,'',0),(2746,161,1184,0,242,1152,1185,10,1068047603,1,141,'',0),(2745,161,1152,0,241,1164,1184,10,1068047603,1,141,'',0),(2744,161,1164,0,240,1183,1152,10,1068047603,1,141,'',0),(2743,161,1183,0,239,1182,1164,10,1068047603,1,141,'',0),(2742,161,1182,0,238,1181,1183,10,1068047603,1,141,'',0),(2741,161,1181,0,237,1180,1182,10,1068047603,1,141,'',0),(2740,161,1180,0,236,1179,1181,10,1068047603,1,141,'',0),(2739,161,1179,0,235,1178,1180,10,1068047603,1,141,'',0),(2738,161,1178,0,234,1177,1179,10,1068047603,1,141,'',0),(2737,161,1177,0,233,1176,1178,10,1068047603,1,141,'',0),(2736,161,1176,0,232,1175,1177,10,1068047603,1,141,'',0),(2735,161,1175,0,231,89,1176,10,1068047603,1,141,'',0),(2734,161,89,0,230,1174,1175,10,1068047603,1,141,'',0),(2733,161,1174,0,229,1173,89,10,1068047603,1,141,'',0),(2732,161,1173,0,228,1172,1174,10,1068047603,1,141,'',0),(2731,161,1172,0,227,1171,1173,10,1068047603,1,141,'',0),(2730,161,1171,0,226,1154,1172,10,1068047603,1,141,'',0),(2729,161,1154,0,225,1170,1171,10,1068047603,1,141,'',0),(2728,161,1170,0,224,1169,1154,10,1068047603,1,141,'',0),(2727,161,1169,0,223,1168,1170,10,1068047603,1,141,'',0),(2726,161,1168,0,222,1167,1169,10,1068047603,1,141,'',0),(2725,161,1167,0,221,1166,1168,10,1068047603,1,141,'',0),(2724,161,1166,0,220,1165,1167,10,1068047603,1,141,'',0),(2723,161,1165,0,219,1164,1166,10,1068047603,1,141,'',0),(2722,161,1164,0,218,1163,1165,10,1068047603,1,141,'',0),(2721,161,1163,0,217,1153,1164,10,1068047603,1,141,'',0),(2720,161,1153,0,216,1154,1163,10,1068047603,1,141,'',0),(2719,161,1154,0,215,1162,1153,10,1068047603,1,141,'',0),(2718,161,1162,0,214,1161,1154,10,1068047603,1,141,'',0),(2717,161,1161,0,213,1160,1162,10,1068047603,1,141,'',0),(2716,161,1160,0,212,1159,1161,10,1068047603,1,141,'',0),(2715,161,1159,0,211,1151,1160,10,1068047603,1,141,'',0),(2714,161,1151,0,210,1158,1159,10,1068047603,1,141,'',0),(2713,161,1158,0,209,1157,1151,10,1068047603,1,141,'',0),(2712,161,1157,0,208,1156,1158,10,1068047603,1,141,'',0),(2711,161,1156,0,207,1155,1157,10,1068047603,1,141,'',0),(2710,161,1155,0,206,1154,1156,10,1068047603,1,141,'',0),(2709,161,1154,0,205,1149,1155,10,1068047603,1,141,'',0),(2708,161,1149,0,204,1148,1154,10,1068047603,1,141,'',0),(2707,161,1148,0,203,1153,1149,10,1068047603,1,141,'',0),(2706,161,1153,0,202,1152,1148,10,1068047603,1,141,'',0),(2705,161,1152,0,201,1151,1153,10,1068047603,1,141,'',0),(2704,161,1151,0,200,1150,1152,10,1068047603,1,141,'',0),(2703,161,1150,0,199,1149,1151,10,1068047603,1,141,'',0),(2702,161,1149,0,198,1148,1150,10,1068047603,1,141,'',0),(2701,161,1148,0,197,1147,1149,10,1068047603,1,141,'',0),(2700,161,1147,0,196,944,1148,10,1068047603,1,141,'',0),(2699,161,944,0,195,943,1147,10,1068047603,1,141,'',0),(2698,161,943,0,194,1164,944,10,1068047603,1,141,'',0),(2697,161,1164,0,193,1203,943,10,1068047603,1,141,'',0),(2696,161,1203,0,192,1231,1164,10,1068047603,1,141,'',0),(2503,160,1146,0,259,1145,0,2,1068047455,4,121,'',0),(2502,160,1145,0,258,944,1146,2,1068047455,4,121,'',0),(2501,160,944,0,257,943,1145,2,1068047455,4,121,'',0),(2500,160,943,0,256,1146,944,2,1068047455,4,121,'',0),(2499,160,1146,0,255,1145,943,2,1068047455,4,121,'',0),(2498,160,1145,0,254,944,1146,2,1068047455,4,121,'',0),(2497,160,944,0,253,943,1145,2,1068047455,4,121,'',0),(2496,160,943,0,252,1146,944,2,1068047455,4,121,'',0),(2495,160,1146,0,251,1145,943,2,1068047455,4,121,'',0),(2494,160,1145,0,250,944,1146,2,1068047455,4,121,'',0),(2493,160,944,0,249,943,1145,2,1068047455,4,121,'',0),(2492,160,943,0,248,1146,944,2,1068047455,4,121,'',0),(2491,160,1146,0,247,1145,943,2,1068047455,4,121,'',0),(2490,160,1145,0,246,944,1146,2,1068047455,4,121,'',0),(2489,160,944,0,245,943,1145,2,1068047455,4,121,'',0),(2488,160,943,0,244,1146,944,2,1068047455,4,121,'',0),(2487,160,1146,0,243,1145,943,2,1068047455,4,121,'',0),(2486,160,1145,0,242,944,1146,2,1068047455,4,121,'',0),(2485,160,944,0,241,943,1145,2,1068047455,4,121,'',0),(2484,160,943,0,240,1146,944,2,1068047455,4,121,'',0),(2483,160,1146,0,239,1145,943,2,1068047455,4,121,'',0),(2482,160,1145,0,238,944,1146,2,1068047455,4,121,'',0),(2481,160,944,0,237,943,1145,2,1068047455,4,121,'',0),(2480,160,943,0,236,1146,944,2,1068047455,4,121,'',0),(2479,160,1146,0,235,1145,943,2,1068047455,4,121,'',0),(2478,160,1145,0,234,944,1146,2,1068047455,4,121,'',0),(2477,160,944,0,233,943,1145,2,1068047455,4,121,'',0),(2476,160,943,0,232,1146,944,2,1068047455,4,121,'',0),(2475,160,1146,0,231,1145,943,2,1068047455,4,121,'',0),(2474,160,1145,0,230,944,1146,2,1068047455,4,121,'',0),(2473,160,944,0,229,943,1145,2,1068047455,4,121,'',0),(2472,160,943,0,228,1146,944,2,1068047455,4,121,'',0),(2471,160,1146,0,227,1145,943,2,1068047455,4,121,'',0),(2470,160,1145,0,226,944,1146,2,1068047455,4,121,'',0),(2469,160,944,0,225,943,1145,2,1068047455,4,121,'',0),(2468,160,943,0,224,1146,944,2,1068047455,4,121,'',0),(2467,160,1146,0,223,1145,943,2,1068047455,4,121,'',0),(2466,160,1145,0,222,944,1146,2,1068047455,4,121,'',0),(2465,160,944,0,221,943,1145,2,1068047455,4,121,'',0),(2464,160,943,0,220,1146,944,2,1068047455,4,121,'',0),(2463,160,1146,0,219,1145,943,2,1068047455,4,121,'',0),(2462,160,1145,0,218,944,1146,2,1068047455,4,121,'',0),(2461,160,944,0,217,943,1145,2,1068047455,4,121,'',0),(2460,160,943,0,216,1146,944,2,1068047455,4,121,'',0),(2459,160,1146,0,215,1145,943,2,1068047455,4,121,'',0),(2458,160,1145,0,214,944,1146,2,1068047455,4,121,'',0),(2457,160,944,0,213,943,1145,2,1068047455,4,121,'',0),(2456,160,943,0,212,1146,944,2,1068047455,4,121,'',0),(2455,160,1146,0,211,1145,943,2,1068047455,4,121,'',0),(2454,160,1145,0,210,944,1146,2,1068047455,4,121,'',0),(2453,160,944,0,209,943,1145,2,1068047455,4,121,'',0),(2452,160,943,0,208,1146,944,2,1068047455,4,121,'',0),(2451,160,1146,0,207,1145,943,2,1068047455,4,121,'',0),(2450,160,1145,0,206,944,1146,2,1068047455,4,121,'',0),(2449,160,944,0,205,943,1145,2,1068047455,4,121,'',0),(2448,160,943,0,204,1146,944,2,1068047455,4,121,'',0),(2447,160,1146,0,203,1145,943,2,1068047455,4,121,'',0),(2446,160,1145,0,202,944,1146,2,1068047455,4,121,'',0),(2445,160,944,0,201,943,1145,2,1068047455,4,121,'',0),(2444,160,943,0,200,1146,944,2,1068047455,4,121,'',0),(2443,160,1146,0,199,1145,943,2,1068047455,4,121,'',0),(2442,160,1145,0,198,944,1146,2,1068047455,4,121,'',0),(2441,160,944,0,197,943,1145,2,1068047455,4,121,'',0),(2440,160,943,0,196,1146,944,2,1068047455,4,121,'',0),(2439,160,1146,0,195,1145,943,2,1068047455,4,121,'',0),(2438,160,1145,0,194,944,1146,2,1068047455,4,121,'',0),(2437,160,944,0,193,943,1145,2,1068047455,4,121,'',0),(2436,160,943,0,192,1146,944,2,1068047455,4,121,'',0),(2435,160,1146,0,191,1145,943,2,1068047455,4,121,'',0),(2434,160,1145,0,190,944,1146,2,1068047455,4,121,'',0),(2433,160,944,0,189,943,1145,2,1068047455,4,121,'',0),(2432,160,943,0,188,1146,944,2,1068047455,4,121,'',0),(2431,160,1146,0,187,1145,943,2,1068047455,4,121,'',0),(2430,160,1145,0,186,944,1146,2,1068047455,4,121,'',0),(2429,160,944,0,185,943,1145,2,1068047455,4,121,'',0),(2428,160,943,0,184,1146,944,2,1068047455,4,121,'',0),(2427,160,1146,0,183,1145,943,2,1068047455,4,121,'',0),(2426,160,1145,0,182,944,1146,2,1068047455,4,121,'',0),(2425,160,944,0,181,943,1145,2,1068047455,4,121,'',0),(2424,160,943,0,180,1144,944,2,1068047455,4,121,'',0),(2423,160,1144,0,179,37,943,2,1068047455,4,121,'',0),(2422,160,37,0,178,1143,1144,2,1068047455,4,121,'',0),(2421,160,1143,0,177,1142,37,2,1068047455,4,121,'',0),(2420,160,1142,0,176,74,1143,2,1068047455,4,121,'',0),(2419,160,74,0,175,73,1142,2,1068047455,4,121,'',0),(2418,160,73,0,174,1146,74,2,1068047455,4,121,'',0),(2417,160,1146,0,173,1145,73,2,1068047455,4,121,'',0),(2416,160,1145,0,172,944,1146,2,1068047455,4,121,'',0),(2415,160,944,0,171,943,1145,2,1068047455,4,121,'',0),(2414,160,943,0,170,1146,944,2,1068047455,4,121,'',0),(2413,160,1146,0,169,1145,943,2,1068047455,4,121,'',0),(2412,160,1145,0,168,944,1146,2,1068047455,4,121,'',0),(2411,160,944,0,167,943,1145,2,1068047455,4,121,'',0),(2410,160,943,0,166,1146,944,2,1068047455,4,121,'',0),(2409,160,1146,0,165,1145,943,2,1068047455,4,121,'',0),(2408,160,1145,0,164,944,1146,2,1068047455,4,121,'',0),(2407,160,944,0,163,943,1145,2,1068047455,4,121,'',0),(2406,160,943,0,162,1146,944,2,1068047455,4,121,'',0),(2405,160,1146,0,161,1145,943,2,1068047455,4,121,'',0),(2404,160,1145,0,160,944,1146,2,1068047455,4,121,'',0),(2403,160,944,0,159,943,1145,2,1068047455,4,121,'',0),(2402,160,943,0,158,1146,944,2,1068047455,4,121,'',0),(2401,160,1146,0,157,1145,943,2,1068047455,4,121,'',0),(2400,160,1145,0,156,944,1146,2,1068047455,4,121,'',0),(2399,160,944,0,155,943,1145,2,1068047455,4,121,'',0),(2398,160,943,0,154,1146,944,2,1068047455,4,121,'',0),(2397,160,1146,0,153,1145,943,2,1068047455,4,121,'',0),(2396,160,1145,0,152,944,1146,2,1068047455,4,121,'',0),(2395,160,944,0,151,943,1145,2,1068047455,4,121,'',0),(2394,160,943,0,150,1146,944,2,1068047455,4,121,'',0),(2393,160,1146,0,149,1145,943,2,1068047455,4,121,'',0),(2392,160,1145,0,148,944,1146,2,1068047455,4,121,'',0),(2391,160,944,0,147,943,1145,2,1068047455,4,121,'',0),(2390,160,943,0,146,1146,944,2,1068047455,4,121,'',0),(2389,160,1146,0,145,1145,943,2,1068047455,4,121,'',0),(2388,160,1145,0,144,944,1146,2,1068047455,4,121,'',0),(2387,160,944,0,143,943,1145,2,1068047455,4,121,'',0),(2386,160,943,0,142,1146,944,2,1068047455,4,121,'',0),(2385,160,1146,0,141,1145,943,2,1068047455,4,121,'',0),(2384,160,1145,0,140,944,1146,2,1068047455,4,121,'',0),(2383,160,944,0,139,943,1145,2,1068047455,4,121,'',0),(2382,160,943,0,138,1146,944,2,1068047455,4,121,'',0),(2381,160,1146,0,137,1145,943,2,1068047455,4,121,'',0),(2380,160,1145,0,136,944,1146,2,1068047455,4,121,'',0),(2379,160,944,0,135,943,1145,2,1068047455,4,121,'',0),(2378,160,943,0,134,1146,944,2,1068047455,4,121,'',0),(2377,160,1146,0,133,1145,943,2,1068047455,4,121,'',0),(2376,160,1145,0,132,944,1146,2,1068047455,4,121,'',0),(2375,160,944,0,131,943,1145,2,1068047455,4,121,'',0),(2374,160,943,0,130,1146,944,2,1068047455,4,121,'',0),(2373,160,1146,0,129,1145,943,2,1068047455,4,121,'',0),(2372,160,1145,0,128,944,1146,2,1068047455,4,121,'',0),(2371,160,944,0,127,943,1145,2,1068047455,4,121,'',0),(2370,160,943,0,126,1146,944,2,1068047455,4,121,'',0),(2369,160,1146,0,125,1145,943,2,1068047455,4,121,'',0),(2368,160,1145,0,124,944,1146,2,1068047455,4,121,'',0),(2367,160,944,0,123,943,1145,2,1068047455,4,121,'',0),(2366,160,943,0,122,1146,944,2,1068047455,4,121,'',0),(2365,160,1146,0,121,1145,943,2,1068047455,4,121,'',0),(2364,160,1145,0,120,944,1146,2,1068047455,4,121,'',0),(2363,160,944,0,119,943,1145,2,1068047455,4,121,'',0),(2362,160,943,0,118,1146,944,2,1068047455,4,121,'',0),(2361,160,1146,0,117,1145,943,2,1068047455,4,121,'',0),(2360,160,1145,0,116,944,1146,2,1068047455,4,121,'',0),(2359,160,944,0,115,943,1145,2,1068047455,4,121,'',0),(2358,160,943,0,114,1146,944,2,1068047455,4,121,'',0),(2357,160,1146,0,113,1145,943,2,1068047455,4,121,'',0),(2356,160,1145,0,112,944,1146,2,1068047455,4,121,'',0),(2355,160,944,0,111,943,1145,2,1068047455,4,121,'',0),(2354,160,943,0,110,1146,944,2,1068047455,4,121,'',0),(2353,160,1146,0,109,1145,943,2,1068047455,4,121,'',0),(2352,160,1145,0,108,944,1146,2,1068047455,4,121,'',0),(2351,160,944,0,107,943,1145,2,1068047455,4,121,'',0),(2350,160,943,0,106,1146,944,2,1068047455,4,121,'',0),(2349,160,1146,0,105,1145,943,2,1068047455,4,121,'',0),(2348,160,1145,0,104,944,1146,2,1068047455,4,121,'',0),(2347,160,944,0,103,943,1145,2,1068047455,4,121,'',0),(2346,160,943,0,102,1146,944,2,1068047455,4,121,'',0),(2345,160,1146,0,101,1145,943,2,1068047455,4,121,'',0),(2344,160,1145,0,100,944,1146,2,1068047455,4,121,'',0),(2343,160,944,0,99,943,1145,2,1068047455,4,121,'',0),(2342,160,943,0,98,1146,944,2,1068047455,4,121,'',0),(2341,160,1146,0,97,1145,943,2,1068047455,4,121,'',0),(2340,160,1145,0,96,944,1146,2,1068047455,4,121,'',0),(2339,160,944,0,95,943,1145,2,1068047455,4,121,'',0),(2338,160,943,0,94,1144,944,2,1068047455,4,121,'',0),(2337,160,1144,0,93,37,943,2,1068047455,4,121,'',0),(2336,160,37,0,92,1143,1144,2,1068047455,4,121,'',0),(2335,160,1143,0,91,1142,37,2,1068047455,4,121,'',0),(2334,160,1142,0,90,74,1143,2,1068047455,4,121,'',0),(2333,160,74,0,89,73,1142,2,1068047455,4,121,'',0),(2332,160,73,0,88,1146,74,2,1068047455,4,121,'',0),(2331,160,1146,0,87,1145,73,2,1068047455,4,120,'',0),(2330,160,1145,0,86,944,1146,2,1068047455,4,120,'',0),(2329,160,944,0,85,943,1145,2,1068047455,4,120,'',0),(2328,160,943,0,84,1146,944,2,1068047455,4,120,'',0),(2327,160,1146,0,83,1145,943,2,1068047455,4,120,'',0),(2326,160,1145,0,82,944,1146,2,1068047455,4,120,'',0),(2325,160,944,0,81,943,1145,2,1068047455,4,120,'',0),(2324,160,943,0,80,1146,944,2,1068047455,4,120,'',0),(2323,160,1146,0,79,1145,943,2,1068047455,4,120,'',0),(2322,160,1145,0,78,944,1146,2,1068047455,4,120,'',0),(2321,160,944,0,77,943,1145,2,1068047455,4,120,'',0),(2320,160,943,0,76,1146,944,2,1068047455,4,120,'',0),(2319,160,1146,0,75,1145,943,2,1068047455,4,120,'',0),(2318,160,1145,0,74,944,1146,2,1068047455,4,120,'',0),(2317,160,944,0,73,943,1145,2,1068047455,4,120,'',0),(2316,160,943,0,72,1146,944,2,1068047455,4,120,'',0),(2315,160,1146,0,71,1145,943,2,1068047455,4,120,'',0),(2314,160,1145,0,70,944,1146,2,1068047455,4,120,'',0),(2313,160,944,0,69,943,1145,2,1068047455,4,120,'',0),(2312,160,943,0,68,1146,944,2,1068047455,4,120,'',0),(2311,160,1146,0,67,1145,943,2,1068047455,4,120,'',0),(2310,160,1145,0,66,944,1146,2,1068047455,4,120,'',0),(2309,160,944,0,65,943,1145,2,1068047455,4,120,'',0),(2308,160,943,0,64,1146,944,2,1068047455,4,120,'',0),(2307,160,1146,0,63,1145,943,2,1068047455,4,120,'',0),(2306,160,1145,0,62,944,1146,2,1068047455,4,120,'',0),(2305,160,944,0,61,943,1145,2,1068047455,4,120,'',0),(2304,160,943,0,60,1146,944,2,1068047455,4,120,'',0),(2303,160,1146,0,59,1145,943,2,1068047455,4,120,'',0),(2302,160,1145,0,58,944,1146,2,1068047455,4,120,'',0),(2301,160,944,0,57,943,1145,2,1068047455,4,120,'',0),(2300,160,943,0,56,1146,944,2,1068047455,4,120,'',0),(2299,160,1146,0,55,1145,943,2,1068047455,4,120,'',0),(2298,160,1145,0,54,944,1146,2,1068047455,4,120,'',0),(2297,160,944,0,53,943,1145,2,1068047455,4,120,'',0),(2296,160,943,0,52,1146,944,2,1068047455,4,120,'',0),(2295,160,1146,0,51,1145,943,2,1068047455,4,120,'',0),(2294,160,1145,0,50,944,1146,2,1068047455,4,120,'',0),(2293,160,944,0,49,943,1145,2,1068047455,4,120,'',0),(2292,160,943,0,48,1146,944,2,1068047455,4,120,'',0),(2291,160,1146,0,47,1145,943,2,1068047455,4,120,'',0),(2290,160,1145,0,46,944,1146,2,1068047455,4,120,'',0),(2289,160,944,0,45,943,1145,2,1068047455,4,120,'',0),(2288,160,943,0,44,1146,944,2,1068047455,4,120,'',0),(2287,160,1146,0,43,1145,943,2,1068047455,4,120,'',0),(2286,160,1145,0,42,944,1146,2,1068047455,4,120,'',0),(2285,160,944,0,41,943,1145,2,1068047455,4,120,'',0),(2284,160,943,0,40,1146,944,2,1068047455,4,120,'',0),(2283,160,1146,0,39,1145,943,2,1068047455,4,120,'',0),(2282,160,1145,0,38,944,1146,2,1068047455,4,120,'',0),(2281,160,944,0,37,943,1145,2,1068047455,4,120,'',0),(2280,160,943,0,36,1146,944,2,1068047455,4,120,'',0),(2279,160,1146,0,35,1145,943,2,1068047455,4,120,'',0),(2278,160,1145,0,34,944,1146,2,1068047455,4,120,'',0),(2277,160,944,0,33,943,1145,2,1068047455,4,120,'',0),(2276,160,943,0,32,1146,944,2,1068047455,4,120,'',0),(2275,160,1146,0,31,1145,943,2,1068047455,4,120,'',0),(2274,160,1145,0,30,944,1146,2,1068047455,4,120,'',0),(2273,160,944,0,29,943,1145,2,1068047455,4,120,'',0),(2272,160,943,0,28,1146,944,2,1068047455,4,120,'',0),(2271,160,1146,0,27,1145,943,2,1068047455,4,120,'',0),(2270,160,1145,0,26,944,1146,2,1068047455,4,120,'',0),(2269,160,944,0,25,943,1145,2,1068047455,4,120,'',0),(2268,160,943,0,24,1146,944,2,1068047455,4,120,'',0),(2267,160,1146,0,23,1145,943,2,1068047455,4,120,'',0),(2266,160,1145,0,22,944,1146,2,1068047455,4,120,'',0),(2265,160,944,0,21,943,1145,2,1068047455,4,120,'',0),(2262,160,1145,0,18,944,1146,2,1068047455,4,120,'',0),(2261,160,944,0,17,943,1145,2,1068047455,4,120,'',0),(2260,160,943,0,16,1146,944,2,1068047455,4,120,'',0),(2259,160,1146,0,15,1145,943,2,1068047455,4,120,'',0),(2258,160,1145,0,14,944,1146,2,1068047455,4,120,'',0),(2257,160,944,0,13,943,1145,2,1068047455,4,120,'',0),(2256,160,943,0,12,1146,944,2,1068047455,4,120,'',0),(2255,160,1146,0,11,1145,943,2,1068047455,4,120,'',0),(2254,160,1145,0,10,944,1146,2,1068047455,4,120,'',0),(2253,160,944,0,9,943,1145,2,1068047455,4,120,'',0),(2252,160,943,0,8,1144,944,2,1068047455,4,120,'',0),(2251,160,1144,0,7,37,943,2,1068047455,4,120,'',0),(2250,160,37,0,6,1143,1144,2,1068047455,4,120,'',0),(2249,160,1143,0,5,1142,37,2,1068047455,4,120,'',0),(2247,160,74,0,3,73,1142,2,1068047455,4,120,'',0),(2248,160,1142,0,4,74,1143,2,1068047455,4,120,'',0),(1106,107,571,0,1,570,0,4,1066916865,2,9,'',0),(1105,107,570,0,0,0,571,4,1066916865,2,8,'',0),(1107,111,572,0,0,0,573,4,1066917523,2,8,'',0),(1108,111,573,0,1,572,0,4,1066917523,2,9,'',0),(3276,1,37,0,15,1143,0,1,1033917596,1,119,'',0),(3275,1,1143,0,14,1099,37,1,1033917596,1,119,'',0),(3274,1,1099,0,13,1153,1143,1,1033917596,1,119,'',0),(3273,1,1153,0,12,1115,1099,1,1033917596,1,119,'',0),(3272,1,1115,0,11,1143,1153,1,1033917596,1,119,'',0),(3105,141,381,0,4,1094,0,20,1068036586,1,187,'',0),(3104,141,1094,0,3,1095,381,20,1068036586,1,187,'',0),(3103,141,1095,0,2,1087,1094,20,1068036586,1,187,'',0),(3102,141,1087,0,1,1094,1095,20,1068036586,1,186,'',0),(3101,141,1094,0,0,0,1087,20,1068036586,1,186,'',0),(2212,153,1125,0,5,1124,0,22,1068042105,1,193,'',0),(2211,153,1124,0,4,1123,1125,22,1068042105,1,193,'',0),(2210,153,1123,0,3,1122,1124,22,1068042105,1,193,'',0),(2209,153,1122,0,2,1121,1123,22,1068042105,1,193,'',0),(2208,153,1121,0,1,1096,1122,22,1068042105,1,191,'',0),(2207,153,1096,0,0,0,1121,22,1068042105,1,191,'',0),(2170,139,1092,0,12,51,0,21,1068036445,1,190,'',0),(2169,139,51,0,11,195,1092,21,1068036445,1,189,'',0),(2168,139,195,0,10,73,51,21,1068036445,1,189,'',0),(2167,139,73,0,9,77,195,21,1068036445,1,189,'',0),(2166,139,77,0,8,1098,73,21,1068036445,1,189,'',0),(2165,139,1098,0,7,1097,77,21,1068036445,1,189,'',0),(2164,139,1097,0,6,1096,1098,21,1068036445,1,189,'',0),(2163,139,1096,0,5,51,1097,21,1068036445,1,189,'',0),(2162,139,51,0,4,195,1096,21,1068036445,1,188,'',0),(2695,161,1231,0,191,1220,1203,10,1068047603,1,141,'',0),(2694,161,1220,0,190,1250,1231,10,1068047603,1,141,'',0),(2693,161,1250,0,189,1249,1220,10,1068047603,1,141,'',0),(2692,161,1249,0,188,1248,1250,10,1068047603,1,141,'',0),(2691,161,1248,0,187,1152,1249,10,1068047603,1,141,'',0),(2690,161,1152,0,186,1171,1248,10,1068047603,1,141,'',0),(2689,161,1171,0,185,1222,1152,10,1068047603,1,141,'',0),(2688,161,1222,0,184,1217,1171,10,1068047603,1,141,'',0),(2687,161,1217,0,183,1247,1222,10,1068047603,1,141,'',0),(2686,161,1247,0,182,1172,1217,10,1068047603,1,141,'',0),(2685,161,1172,0,181,1168,1247,10,1068047603,1,141,'',0),(2684,161,1168,0,180,1213,1172,10,1068047603,1,141,'',0),(2683,161,1213,0,179,1246,1168,10,1068047603,1,141,'',0),(2682,161,1246,0,178,1245,1213,10,1068047603,1,141,'',0),(2681,161,1245,0,177,1244,1246,10,1068047603,1,141,'',0),(2680,161,1244,0,176,1243,1245,10,1068047603,1,141,'',0),(2679,161,1243,0,175,1164,1244,10,1068047603,1,141,'',0),(2678,161,1164,0,174,1205,1243,10,1068047603,1,141,'',0),(2677,161,1205,0,173,1151,1164,10,1068047603,1,141,'',0),(2676,161,1151,0,172,1242,1205,10,1068047603,1,141,'',0),(2675,161,1242,0,171,1177,1151,10,1068047603,1,141,'',0),(2674,161,1177,0,170,1166,1242,10,1068047603,1,141,'',0),(2673,161,1166,0,169,1222,1177,10,1068047603,1,141,'',0),(2672,161,1222,0,168,1164,1166,10,1068047603,1,141,'',0),(2671,161,1164,0,167,1241,1222,10,1068047603,1,141,'',0),(2670,161,1241,0,166,1147,1164,10,1068047603,1,141,'',0),(2669,161,1147,0,165,1213,1241,10,1068047603,1,141,'',0),(2668,161,1213,0,164,1192,1147,10,1068047603,1,141,'',0),(2667,161,1192,0,163,1203,1213,10,1068047603,1,141,'',0),(2666,161,1203,0,162,1161,1192,10,1068047603,1,141,'',0),(2665,161,1161,0,161,1240,1203,10,1068047603,1,141,'',0),(2664,161,1240,0,160,1239,1161,10,1068047603,1,141,'',0),(2663,161,1239,0,159,1213,1240,10,1068047603,1,141,'',0),(2662,161,1213,0,158,1184,1239,10,1068047603,1,141,'',0),(2661,161,1184,0,157,1238,1213,10,1068047603,1,141,'',0),(2660,161,1238,0,156,89,1184,10,1068047603,1,141,'',0),(2659,161,89,0,155,1219,1238,10,1068047603,1,141,'',0),(2658,161,1219,0,154,1225,89,10,1068047603,1,141,'',0),(2657,161,1225,0,153,1157,1219,10,1068047603,1,141,'',0),(2656,161,1157,0,152,1222,1225,10,1068047603,1,141,'',0),(2655,161,1222,0,151,1184,1157,10,1068047603,1,141,'',0),(2654,161,1184,0,150,1210,1222,10,1068047603,1,141,'',0),(2653,161,1210,0,149,1237,1184,10,1068047603,1,141,'',0),(2652,161,1237,0,148,1165,1210,10,1068047603,1,141,'',0),(2651,161,1165,0,147,1203,1237,10,1068047603,1,141,'',0),(2650,161,1203,0,146,1236,1165,10,1068047603,1,141,'',0),(2649,161,1236,0,145,1235,1203,10,1068047603,1,141,'',0),(2648,161,1235,0,144,1167,1236,10,1068047603,1,141,'',0),(2647,161,1167,0,143,1234,1235,10,1068047603,1,141,'',0),(2646,161,1234,0,142,1193,1167,10,1068047603,1,141,'',0),(2645,161,1193,0,141,1197,1234,10,1068047603,1,141,'',0),(2644,161,1197,0,140,1233,1193,10,1068047603,1,141,'',0),(2643,161,1233,0,139,1175,1197,10,1068047603,1,141,'',0),(2642,161,1175,0,138,1181,1233,10,1068047603,1,141,'',0),(2641,161,1181,0,137,1201,1175,10,1068047603,1,141,'',0),(2640,161,1201,0,136,1218,1181,10,1068047603,1,141,'',0),(2639,161,1218,0,135,1185,1201,10,1068047603,1,141,'',0),(2638,161,1185,0,134,1178,1218,10,1068047603,1,141,'',0),(2637,161,1178,0,133,1147,1185,10,1068047603,1,141,'',0),(2636,161,1147,0,132,1232,1178,10,1068047603,1,141,'',0),(2635,161,1232,0,131,1197,1147,10,1068047603,1,141,'',0),(2634,161,1197,0,130,1158,1232,10,1068047603,1,141,'',0),(2633,161,1158,0,129,1231,1197,10,1068047603,1,141,'',0),(2632,161,1231,0,128,1195,1158,10,1068047603,1,141,'',0),(2631,161,1195,0,127,1164,1231,10,1068047603,1,141,'',0),(2630,161,1164,0,126,1230,1195,10,1068047603,1,141,'',0),(2629,161,1230,0,125,1229,1164,10,1068047603,1,141,'',0),(2628,161,1229,0,124,1228,1230,10,1068047603,1,141,'',0),(2627,161,1228,0,123,1227,1229,10,1068047603,1,141,'',0),(2626,161,1227,0,122,1226,1228,10,1068047603,1,141,'',0),(2625,161,1226,0,121,1153,1227,10,1068047603,1,141,'',0),(2624,161,1153,0,120,1225,1226,10,1068047603,1,141,'',0),(2623,161,1225,0,119,1224,1153,10,1068047603,1,141,'',0),(2622,161,1224,0,118,1164,1225,10,1068047603,1,141,'',0),(2621,161,1164,0,117,1193,1224,10,1068047603,1,141,'',0),(2620,161,1193,0,116,1208,1164,10,1068047603,1,141,'',0),(2619,161,1208,0,115,1158,1193,10,1068047603,1,141,'',0),(2618,161,1158,0,114,1158,1208,10,1068047603,1,141,'',0),(2617,161,1158,0,113,1223,1158,10,1068047603,1,141,'',0),(2616,161,1223,0,112,1222,1158,10,1068047603,1,141,'',0),(2615,161,1222,0,111,943,1223,10,1068047603,1,141,'',0),(2614,161,943,0,110,1173,1222,10,1068047603,1,141,'',0),(2613,161,1173,0,109,1177,943,10,1068047603,1,141,'',0),(2612,161,1177,0,108,1193,1173,10,1068047603,1,141,'',0),(2611,161,1193,0,107,1153,1177,10,1068047603,1,141,'',0),(2610,161,1153,0,106,1183,1193,10,1068047603,1,141,'',0),(2609,161,1183,0,105,1221,1153,10,1068047603,1,141,'',0),(2608,161,1221,0,104,1162,1183,10,1068047603,1,141,'',0),(2607,161,1162,0,103,1210,1221,10,1068047603,1,141,'',0),(2606,161,1210,0,102,1220,1162,10,1068047603,1,141,'',0),(2605,161,1220,0,101,1219,1210,10,1068047603,1,141,'',0),(2604,161,1219,0,100,1218,1220,10,1068047603,1,141,'',0),(2603,161,1218,0,99,1217,1219,10,1068047603,1,141,'',0),(2602,161,1217,0,98,1166,1218,10,1068047603,1,141,'',0),(2601,161,1166,0,97,1216,1217,10,1068047603,1,141,'',0),(2600,161,1216,0,96,1215,1166,10,1068047603,1,141,'',0),(2599,161,1215,0,95,1214,1216,10,1068047603,1,141,'',0),(2598,161,1214,0,94,1213,1215,10,1068047603,1,141,'',0),(2597,161,1213,0,93,1212,1214,10,1068047603,1,141,'',0),(2596,161,1212,0,92,1211,1213,10,1068047603,1,141,'',0),(2595,161,1211,0,91,1176,1212,10,1068047603,1,141,'',0),(2594,161,1176,0,90,1210,1211,10,1068047603,1,141,'',0),(2593,161,1210,0,89,1209,1176,10,1068047603,1,141,'',0),(2592,161,1209,0,88,1178,1210,10,1068047603,1,141,'',0),(2591,161,1178,0,87,1208,1209,10,1068047603,1,141,'',0),(2590,161,1208,0,86,1207,1178,10,1068047603,1,141,'',0),(2589,161,1207,0,85,1206,1208,10,1068047603,1,141,'',0),(2588,161,1206,0,84,1158,1207,10,1068047603,1,141,'',0),(2587,161,1158,0,83,1206,1206,10,1068047603,1,141,'',0),(2586,161,1206,0,82,1205,1158,10,1068047603,1,141,'',0),(2585,161,1205,0,81,1204,1206,10,1068047603,1,141,'',0),(2584,161,1204,0,80,1203,1205,10,1068047603,1,141,'',0),(2583,161,1203,0,79,1202,1204,10,1068047603,1,141,'',0),(2582,161,1202,0,78,1201,1203,10,1068047603,1,141,'',0),(2581,161,1201,0,77,1200,1202,10,1068047603,1,141,'',0),(2580,161,1200,0,76,1171,1201,10,1068047603,1,141,'',0),(2579,161,1171,0,75,1153,1200,10,1068047603,1,141,'',0),(2578,161,1153,0,74,1199,1171,10,1068047603,1,141,'',0),(2577,161,1199,0,73,1198,1153,10,1068047603,1,141,'',0),(2576,161,1198,0,72,1197,1199,10,1068047603,1,141,'',0),(2575,161,1197,0,71,1196,1198,10,1068047603,1,141,'',0),(2574,161,1196,0,70,1195,1197,10,1068047603,1,141,'',0),(2573,161,1195,0,69,943,1196,10,1068047603,1,141,'',0),(2572,161,943,0,68,1158,1195,10,1068047603,1,141,'',0),(2571,161,1158,0,67,1194,943,10,1068047603,1,141,'',0),(2570,161,1194,0,66,1193,1158,10,1068047603,1,141,'',0),(2569,161,1193,0,65,1175,1194,10,1068047603,1,141,'',0),(2568,161,1175,0,64,1153,1193,10,1068047603,1,141,'',0),(2567,161,1153,0,63,1192,1175,10,1068047603,1,141,'',0),(2566,161,1192,0,62,1191,1153,10,1068047603,1,141,'',0),(2565,161,1191,0,61,1190,1192,10,1068047603,1,141,'',0),(2564,161,1190,0,60,1158,1191,10,1068047603,1,141,'',0),(2563,161,1158,0,59,1190,1190,10,1068047603,1,141,'',0),(2562,161,1190,0,58,1189,1158,10,1068047603,1,141,'',0),(2561,161,1189,0,57,1188,1190,10,1068047603,1,141,'',0),(2560,161,1188,0,56,1165,1189,10,1068047603,1,141,'',0),(2559,161,1165,0,55,1187,1188,10,1068047603,1,141,'',0),(2558,161,1187,0,54,1186,1165,10,1068047603,1,141,'',0),(2557,161,1186,0,53,1185,1187,10,1068047603,1,141,'',0),(2556,161,1185,0,52,1184,1186,10,1068047603,1,141,'',0),(2555,161,1184,0,51,1152,1185,10,1068047603,1,141,'',0),(2554,161,1152,0,50,1164,1184,10,1068047603,1,141,'',0),(2553,161,1164,0,49,1183,1152,10,1068047603,1,141,'',0),(2552,161,1183,0,48,1182,1164,10,1068047603,1,141,'',0),(2551,161,1182,0,47,1181,1183,10,1068047603,1,141,'',0),(2550,161,1181,0,46,1180,1182,10,1068047603,1,141,'',0),(2549,161,1180,0,45,1179,1181,10,1068047603,1,141,'',0),(2548,161,1179,0,44,1178,1180,10,1068047603,1,141,'',0),(2547,161,1178,0,43,1177,1179,10,1068047603,1,141,'',0),(2546,161,1177,0,42,1176,1178,10,1068047603,1,141,'',0),(2545,161,1176,0,41,1175,1177,10,1068047603,1,141,'',0),(2544,161,1175,0,40,89,1176,10,1068047603,1,141,'',0),(2543,161,89,0,39,1174,1175,10,1068047603,1,141,'',0),(2542,161,1174,0,38,1173,89,10,1068047603,1,141,'',0),(2541,161,1173,0,37,1172,1174,10,1068047603,1,141,'',0),(2540,161,1172,0,36,1171,1173,10,1068047603,1,141,'',0),(2539,161,1171,0,35,1154,1172,10,1068047603,1,141,'',0),(2538,161,1154,0,34,1170,1171,10,1068047603,1,141,'',0),(2537,161,1170,0,33,1169,1154,10,1068047603,1,141,'',0),(2536,161,1169,0,32,1168,1170,10,1068047603,1,141,'',0),(2535,161,1168,0,31,1167,1169,10,1068047603,1,141,'',0),(2534,161,1167,0,30,1166,1168,10,1068047603,1,141,'',0),(2533,161,1166,0,29,1165,1167,10,1068047603,1,141,'',0),(2532,161,1165,0,28,1164,1166,10,1068047603,1,141,'',0),(2531,161,1164,0,27,1163,1165,10,1068047603,1,141,'',0),(2530,161,1163,0,26,1153,1164,10,1068047603,1,141,'',0),(2529,161,1153,0,25,1154,1163,10,1068047603,1,141,'',0),(2528,161,1154,0,24,1162,1153,10,1068047603,1,141,'',0),(2527,161,1162,0,23,1161,1154,10,1068047603,1,141,'',0),(2526,161,1161,0,22,1160,1162,10,1068047603,1,141,'',0),(2525,161,1160,0,21,1159,1161,10,1068047603,1,141,'',0),(2524,161,1159,0,20,1151,1160,10,1068047603,1,141,'',0),(2523,161,1151,0,19,1158,1159,10,1068047603,1,141,'',0),(2522,161,1158,0,18,1157,1151,10,1068047603,1,141,'',0),(2521,161,1157,0,17,1156,1158,10,1068047603,1,141,'',0),(2520,161,1156,0,16,1155,1157,10,1068047603,1,141,'',0),(2519,161,1155,0,15,1154,1156,10,1068047603,1,141,'',0),(2518,161,1154,0,14,1149,1155,10,1068047603,1,141,'',0),(2517,161,1149,0,13,1148,1154,10,1068047603,1,141,'',0),(2516,161,1148,0,12,1153,1149,10,1068047603,1,141,'',0),(2515,161,1153,0,11,1152,1148,10,1068047603,1,141,'',0),(2514,161,1152,0,10,1151,1153,10,1068047603,1,141,'',0),(2513,161,1151,0,9,1150,1152,10,1068047603,1,141,'',0),(2512,161,1150,0,8,1149,1151,10,1068047603,1,141,'',0),(2511,161,1149,0,7,1148,1150,10,1068047603,1,141,'',0),(2510,161,1148,0,6,1147,1149,10,1068047603,1,141,'',0),(2509,161,1147,0,5,944,1148,10,1068047603,1,141,'',0),(2508,161,944,0,4,943,1147,10,1068047603,1,141,'',0),(2507,161,943,0,3,1081,944,10,1068047603,1,141,'',0),(2506,161,1081,0,2,73,943,10,1068047603,1,140,'',0),(2505,161,73,0,1,934,1081,10,1068047603,1,140,'',0),(2504,161,934,0,0,0,73,10,1068047603,1,140,'',0),(2206,138,1120,0,7,1087,0,1,1068036060,1,119,'',0),(2205,138,1087,0,6,1119,1120,1,1068036060,1,119,'',0),(2204,138,1119,0,5,1118,1087,1,1068036060,1,119,'',0),(2203,138,1118,0,4,1117,1119,1,1068036060,1,119,'',0),(2202,138,1117,0,3,1116,1118,1,1068036060,1,119,'',0),(2201,138,1116,0,2,381,1117,1,1068036060,1,119,'',0),(2161,139,195,0,3,89,51,21,1068036445,1,188,'',0),(2160,139,89,0,2,74,195,21,1068036445,1,188,'',0),(2159,139,74,0,1,73,89,21,1068036445,1,188,'',0),(2158,139,73,0,0,0,74,21,1068036445,1,188,'',0),(2199,138,1115,0,0,0,381,1,1068036060,1,4,'',0),(2200,138,381,0,1,1115,1116,1,1068036060,1,119,'',0),(3287,45,33,0,1,32,34,14,1066388816,11,152,'',0),(3282,115,1409,0,2,7,0,14,1066991725,11,155,'',0),(3281,115,7,0,1,1409,1409,14,1066991725,11,155,'',0),(3280,115,1409,0,0,0,7,14,1066991725,11,152,'',0),(3295,116,1417,0,3,25,0,14,1066992054,11,155,'',0),(3294,116,25,0,2,1416,1417,14,1066992054,11,155,'',0),(3293,116,1416,0,1,1415,25,14,1066992054,11,152,'',0),(3292,116,1415,0,0,0,1416,14,1066992054,11,152,'',0),(3286,45,32,0,0,0,33,14,1066388816,11,152,'',0),(3271,1,1143,0,10,1094,1115,1,1033917596,1,119,'',0),(3109,142,1099,0,3,1095,381,20,1068036660,1,187,'',0),(3108,142,1095,0,2,1087,1099,20,1068036660,1,187,'',0),(3107,142,1087,0,1,1099,1095,20,1068036660,1,186,'',0),(3106,142,1099,0,0,0,1087,20,1068036660,1,186,'',0),(2231,157,77,0,2,1138,73,21,1068044407,1,188,'',0),(2230,157,1138,0,1,1121,77,21,1068044407,1,188,'',0),(2229,157,1121,0,0,0,1138,21,1068044407,1,188,'',0),(3068,14,1362,0,5,1316,0,4,1033920830,2,199,'',0),(3067,14,1316,0,4,1361,1362,4,1033920830,2,198,'',0),(2226,156,1092,0,6,1135,0,21,1068044289,1,190,'',0),(2948,199,1284,0,0,0,1285,22,1068114951,1,191,'',0),(3024,149,1316,0,3,1339,1340,4,1068041016,2,198,'',0),(2225,156,1135,0,5,1134,1092,21,1068044289,1,189,'',0),(2224,156,1134,0,4,1123,1135,21,1068044289,1,189,'',0),(2223,156,1123,0,3,1133,1134,21,1068044289,1,189,'',0),(2222,156,1133,0,2,934,1123,21,1068044289,1,188,'',0),(2221,156,934,0,1,1132,1133,21,1068044289,1,188,'',0),(2220,156,1132,0,0,0,934,21,1068044289,1,188,'',0),(2219,155,1131,0,2,1130,0,22,1068043907,1,193,'',0),(2218,155,1130,0,1,1125,1131,22,1068043907,1,191,'',0),(2217,155,1125,0,0,0,1130,22,1068043907,1,191,'',0),(2194,151,1111,0,0,0,1112,21,1068041849,1,188,'',0),(2195,151,1112,0,1,1111,1113,21,1068041849,1,189,'',0),(2196,151,1113,0,2,1112,1114,21,1068041849,1,189,'',0),(2197,151,1114,0,3,1113,1092,21,1068041849,1,189,'',0),(2198,151,1092,0,4,1114,0,21,1068041849,1,190,'',0),(2232,157,73,0,3,77,51,21,1068044407,1,188,'',0),(2233,157,51,0,4,73,1123,21,1068044407,1,188,'',0),(2234,157,1123,0,5,51,1139,21,1068044407,1,189,'',0),(2235,157,1139,0,6,1123,1092,21,1068044407,1,189,'',0),(2236,157,1092,0,7,1139,0,21,1068044407,1,190,'',0),(2237,158,73,0,0,0,74,22,1068044532,1,191,'',0),(2238,158,74,0,1,73,89,22,1068044532,1,191,'',0),(2239,158,89,0,2,74,1121,22,1068044532,1,191,'',0),(2240,158,1121,0,3,89,5,22,1068044532,1,191,'',0),(2241,158,5,0,4,1121,1121,22,1068044532,1,193,'',0),(2242,158,1121,0,5,5,1140,22,1068044532,1,193,'',0),(2243,158,1140,0,6,1121,0,22,1068044532,1,193,'',0),(2778,161,1158,0,274,1206,1206,10,1068047603,1,141,'',0),(2779,161,1206,0,275,1158,1207,10,1068047603,1,141,'',0),(2780,161,1207,0,276,1206,1208,10,1068047603,1,141,'',0),(2781,161,1208,0,277,1207,1178,10,1068047603,1,141,'',0),(2782,161,1178,0,278,1208,1209,10,1068047603,1,141,'',0),(2783,161,1209,0,279,1178,1210,10,1068047603,1,141,'',0),(2784,161,1210,0,280,1209,1176,10,1068047603,1,141,'',0),(2785,161,1176,0,281,1210,1211,10,1068047603,1,141,'',0),(2786,161,1211,0,282,1176,1212,10,1068047603,1,141,'',0),(2787,161,1212,0,283,1211,1213,10,1068047603,1,141,'',0),(2788,161,1213,0,284,1212,1214,10,1068047603,1,141,'',0),(2789,161,1214,0,285,1213,1215,10,1068047603,1,141,'',0),(2790,161,1215,0,286,1214,1216,10,1068047603,1,141,'',0),(2791,161,1216,0,287,1215,1166,10,1068047603,1,141,'',0),(2792,161,1166,0,288,1216,1217,10,1068047603,1,141,'',0),(2793,161,1217,0,289,1166,1218,10,1068047603,1,141,'',0),(2794,161,1218,0,290,1217,1219,10,1068047603,1,141,'',0),(2795,161,1219,0,291,1218,1220,10,1068047603,1,141,'',0),(2796,161,1220,0,292,1219,1210,10,1068047603,1,141,'',0),(2797,161,1210,0,293,1220,1162,10,1068047603,1,141,'',0),(2798,161,1162,0,294,1210,1221,10,1068047603,1,141,'',0),(2799,161,1221,0,295,1162,1183,10,1068047603,1,141,'',0),(2800,161,1183,0,296,1221,1153,10,1068047603,1,141,'',0),(2801,161,1153,0,297,1183,1193,10,1068047603,1,141,'',0),(2802,161,1193,0,298,1153,1177,10,1068047603,1,141,'',0),(2803,161,1177,0,299,1193,1173,10,1068047603,1,141,'',0),(2804,161,1173,0,300,1177,943,10,1068047603,1,141,'',0),(2805,161,943,0,301,1173,1222,10,1068047603,1,141,'',0),(2806,161,1222,0,302,943,1223,10,1068047603,1,141,'',0),(2807,161,1223,0,303,1222,1158,10,1068047603,1,141,'',0),(2808,161,1158,0,304,1223,1158,10,1068047603,1,141,'',0),(2809,161,1158,0,305,1158,1208,10,1068047603,1,141,'',0),(2810,161,1208,0,306,1158,1193,10,1068047603,1,141,'',0),(2811,161,1193,0,307,1208,1164,10,1068047603,1,141,'',0),(2812,161,1164,0,308,1193,1224,10,1068047603,1,141,'',0),(2813,161,1224,0,309,1164,1225,10,1068047603,1,141,'',0),(2814,161,1225,0,310,1224,1153,10,1068047603,1,141,'',0),(2815,161,1153,0,311,1225,1226,10,1068047603,1,141,'',0),(2816,161,1226,0,312,1153,1227,10,1068047603,1,141,'',0),(2817,161,1227,0,313,1226,1228,10,1068047603,1,141,'',0),(2818,161,1228,0,314,1227,1229,10,1068047603,1,141,'',0),(2819,161,1229,0,315,1228,1230,10,1068047603,1,141,'',0),(2820,161,1230,0,316,1229,1164,10,1068047603,1,141,'',0),(2821,161,1164,0,317,1230,1195,10,1068047603,1,141,'',0),(2822,161,1195,0,318,1164,1231,10,1068047603,1,141,'',0),(2823,161,1231,0,319,1195,1158,10,1068047603,1,141,'',0),(2824,161,1158,0,320,1231,1197,10,1068047603,1,141,'',0),(2825,161,1197,0,321,1158,1232,10,1068047603,1,141,'',0),(2826,161,1232,0,322,1197,1147,10,1068047603,1,141,'',0),(2827,161,1147,0,323,1232,1178,10,1068047603,1,141,'',0),(2828,161,1178,0,324,1147,1185,10,1068047603,1,141,'',0),(2829,161,1185,0,325,1178,1218,10,1068047603,1,141,'',0),(2830,161,1218,0,326,1185,1201,10,1068047603,1,141,'',0),(2831,161,1201,0,327,1218,1181,10,1068047603,1,141,'',0),(2832,161,1181,0,328,1201,1175,10,1068047603,1,141,'',0),(2833,161,1175,0,329,1181,1233,10,1068047603,1,141,'',0),(2834,161,1233,0,330,1175,1197,10,1068047603,1,141,'',0),(2835,161,1197,0,331,1233,1193,10,1068047603,1,141,'',0),(2836,161,1193,0,332,1197,1234,10,1068047603,1,141,'',0),(2837,161,1234,0,333,1193,1167,10,1068047603,1,141,'',0),(2838,161,1167,0,334,1234,1235,10,1068047603,1,141,'',0),(2839,161,1235,0,335,1167,1236,10,1068047603,1,141,'',0),(2840,161,1236,0,336,1235,1203,10,1068047603,1,141,'',0),(2841,161,1203,0,337,1236,1165,10,1068047603,1,141,'',0),(2842,161,1165,0,338,1203,1237,10,1068047603,1,141,'',0),(2843,161,1237,0,339,1165,1210,10,1068047603,1,141,'',0),(2844,161,1210,0,340,1237,1184,10,1068047603,1,141,'',0),(2845,161,1184,0,341,1210,1222,10,1068047603,1,141,'',0),(2846,161,1222,0,342,1184,1157,10,1068047603,1,141,'',0),(2847,161,1157,0,343,1222,1225,10,1068047603,1,141,'',0),(2848,161,1225,0,344,1157,1219,10,1068047603,1,141,'',0),(2849,161,1219,0,345,1225,89,10,1068047603,1,141,'',0),(2850,161,89,0,346,1219,1238,10,1068047603,1,141,'',0),(2851,161,1238,0,347,89,1184,10,1068047603,1,141,'',0),(2852,161,1184,0,348,1238,1213,10,1068047603,1,141,'',0),(2853,161,1213,0,349,1184,1239,10,1068047603,1,141,'',0),(2854,161,1239,0,350,1213,1240,10,1068047603,1,141,'',0),(2855,161,1240,0,351,1239,1161,10,1068047603,1,141,'',0),(2856,161,1161,0,352,1240,1203,10,1068047603,1,141,'',0),(2857,161,1203,0,353,1161,1192,10,1068047603,1,141,'',0),(2858,161,1192,0,354,1203,1213,10,1068047603,1,141,'',0),(2859,161,1213,0,355,1192,1147,10,1068047603,1,141,'',0),(2860,161,1147,0,356,1213,1241,10,1068047603,1,141,'',0),(2861,161,1241,0,357,1147,1164,10,1068047603,1,141,'',0),(2862,161,1164,0,358,1241,1222,10,1068047603,1,141,'',0),(2863,161,1222,0,359,1164,1166,10,1068047603,1,141,'',0),(2864,161,1166,0,360,1222,1177,10,1068047603,1,141,'',0),(2865,161,1177,0,361,1166,1242,10,1068047603,1,141,'',0),(2866,161,1242,0,362,1177,1151,10,1068047603,1,141,'',0),(2867,161,1151,0,363,1242,1205,10,1068047603,1,141,'',0),(2868,161,1205,0,364,1151,1164,10,1068047603,1,141,'',0),(2869,161,1164,0,365,1205,1243,10,1068047603,1,141,'',0),(2870,161,1243,0,366,1164,1244,10,1068047603,1,141,'',0),(2871,161,1244,0,367,1243,1245,10,1068047603,1,141,'',0),(2872,161,1245,0,368,1244,1246,10,1068047603,1,141,'',0),(2873,161,1246,0,369,1245,1213,10,1068047603,1,141,'',0),(2874,161,1213,0,370,1246,1168,10,1068047603,1,141,'',0),(2875,161,1168,0,371,1213,1172,10,1068047603,1,141,'',0),(2876,161,1172,0,372,1168,1247,10,1068047603,1,141,'',0),(2877,161,1247,0,373,1172,1217,10,1068047603,1,141,'',0),(2878,161,1217,0,374,1247,1222,10,1068047603,1,141,'',0),(2879,161,1222,0,375,1217,1171,10,1068047603,1,141,'',0),(2880,161,1171,0,376,1222,1152,10,1068047603,1,141,'',0),(2881,161,1152,0,377,1171,1248,10,1068047603,1,141,'',0),(2882,161,1248,0,378,1152,1249,10,1068047603,1,141,'',0),(2883,161,1249,0,379,1248,1250,10,1068047603,1,141,'',0),(2884,161,1250,0,380,1249,1220,10,1068047603,1,141,'',0),(2885,161,1220,0,381,1250,1231,10,1068047603,1,141,'',0),(2886,161,1231,0,382,1220,1203,10,1068047603,1,141,'',0),(2887,161,1203,0,383,1231,1164,10,1068047603,1,141,'',0),(2888,161,1164,0,384,1203,0,10,1068047603,1,141,'',0),(2889,162,195,0,0,0,51,21,1068047842,1,188,'',0),(2890,162,51,0,1,195,1251,21,1068047842,1,188,'',0),(2891,162,1251,0,2,51,73,21,1068047842,1,188,'',0),(2892,162,73,0,3,1251,74,21,1068047842,1,189,'',0),(2893,162,74,0,4,73,89,21,1068047842,1,189,'',0),(2894,162,89,0,5,74,1252,21,1068047842,1,189,'',0),(2895,162,1252,0,6,89,1253,21,1068047842,1,189,'',0),(2896,162,1253,0,7,1252,1254,21,1068047842,1,189,'',0),(2897,162,1254,0,8,1253,1255,21,1068047842,1,189,'',0),(2898,162,1255,0,9,1254,1256,21,1068047842,1,189,'',0),(2899,162,1256,0,10,1255,1257,21,1068047842,1,189,'',0),(2900,162,1257,0,11,1256,0,21,1068047842,1,190,'',1),(2901,163,1121,0,0,0,1258,22,1068047867,1,191,'',0),(2902,163,1258,0,1,1121,0,22,1068047867,1,193,'',0),(2903,164,1143,0,0,0,1259,21,1068047893,1,188,'',0),(2904,164,1259,0,1,1143,1260,21,1068047893,1,188,'',0),(2905,164,1260,0,2,1259,1251,21,1068047893,1,188,'',0),(2906,164,1251,0,3,1260,1261,21,1068047893,1,188,'',0),(2907,164,1261,0,4,1251,1258,21,1068047893,1,189,'',0),(2908,164,1258,0,5,1261,1261,21,1068047893,1,189,'',0),(2909,164,1261,0,6,1258,1258,21,1068047893,1,189,'',0),(2910,164,1258,0,7,1261,1092,21,1068047893,1,189,'',0),(2911,164,1092,0,8,1258,0,21,1068047893,1,190,'',0),(2912,166,1260,0,0,0,1251,21,1068048145,1,188,'',0),(2913,166,1251,0,1,1260,1262,21,1068048145,1,188,'',0),(2914,166,1262,0,2,1251,1263,21,1068048145,1,188,'',0),(2915,166,1263,0,3,1262,1264,21,1068048145,1,189,'',0),(2916,166,1264,0,4,1263,1265,21,1068048145,1,189,'',0),(2917,166,1265,0,5,1264,1092,21,1068048145,1,189,'',0),(2918,166,1092,0,6,1265,0,21,1068048145,1,190,'',0),(2919,167,1266,0,0,0,1251,21,1068048180,1,188,'',0),(2920,167,1251,0,1,1266,1267,21,1068048180,1,188,'',0),(2921,167,1267,0,2,1251,1268,21,1068048180,1,189,'',0),(2922,167,1268,0,3,1267,1257,21,1068048180,1,189,'',0),(2923,167,1257,0,4,1268,0,21,1068048180,1,190,'',1),(2924,170,1121,0,0,0,1262,22,1068048760,1,191,'',0),(2925,170,1262,0,1,1121,1269,22,1068048760,1,191,'',0),(2926,170,1269,0,2,1262,1270,22,1068048760,1,193,'',0),(2927,170,1270,0,3,1269,0,22,1068048760,1,193,'',0),(2931,190,1274,0,1,1273,1275,21,1068111804,1,188,'',0),(2932,190,1275,0,2,1274,1257,21,1068111804,1,189,'',0),(2933,190,1257,0,3,1275,0,21,1068111804,1,190,'',1),(2934,195,1125,0,0,0,1276,21,1068112063,1,188,'',0),(2935,195,1276,0,1,1125,1092,21,1068112063,1,189,'',0),(2936,195,1092,0,2,1276,0,21,1068112063,1,190,'',0),(2937,196,1121,0,0,0,1277,22,1068112186,1,191,'',0),(2938,196,1277,0,1,1121,1264,22,1068112186,1,193,'',0),(2939,196,1264,0,2,1277,1278,22,1068112186,1,193,'',0),(2940,196,1278,0,3,1264,0,22,1068112186,1,193,'',0),(2983,202,1092,0,3,1309,0,21,1068121170,1,190,'',0),(2949,199,1285,0,1,1284,0,22,1068114951,1,193,'',0),(2982,202,1309,0,2,1308,1092,21,1068121170,1,189,'',0),(2981,202,1308,0,1,1307,1309,21,1068121170,1,189,'',0),(2980,202,1307,0,0,0,1308,21,1068121170,1,188,'',0),(2977,203,1305,0,0,0,1306,21,1068121394,1,188,'',0),(2978,203,1306,0,1,1305,1092,21,1068121394,1,189,'',0),(2963,204,1295,0,0,0,1296,22,1068121576,1,191,'',0),(2964,204,1296,0,1,1295,1295,22,1068121576,1,191,'',0),(2965,204,1295,0,2,1296,1273,22,1068121576,1,191,'',0),(2966,204,1273,0,3,1295,1295,22,1068121576,1,193,'',0),(2967,204,1295,0,4,1273,1297,22,1068121576,1,193,'',0),(2968,204,1297,0,5,1295,1298,22,1068121576,1,193,'',0),(2969,204,1298,0,6,1297,1299,22,1068121576,1,193,'',0),(2970,204,1299,0,7,1298,1300,22,1068121576,1,193,'',0),(2971,204,1300,0,8,1299,1301,22,1068121576,1,193,'',0),(2972,204,1301,0,9,1300,1302,22,1068121576,1,193,'',0),(2973,204,1302,0,10,1301,0,22,1068121576,1,193,'',0),(2979,203,1092,0,2,1306,0,21,1068121394,1,190,'',0),(2984,205,1310,0,0,0,1307,22,1068122396,1,191,'',0),(2985,205,1307,0,1,1310,1311,22,1068122396,1,191,'',0),(2986,205,1311,0,2,1307,0,22,1068122396,1,193,'',0),(3066,14,1361,0,3,1360,1316,4,1033920830,2,198,'',0),(3065,14,1360,0,2,1359,1361,4,1033920830,2,197,'',0),(3064,14,1359,0,1,1358,1360,4,1033920830,2,9,'',0),(3063,14,1358,0,0,0,1359,4,1033920830,2,8,'',0),(2993,206,1140,0,0,0,1318,4,1068123599,2,8,'',0),(2994,206,1318,0,1,1140,1094,4,1068123599,2,9,'',0),(2995,206,1094,0,2,1318,1319,4,1068123599,2,197,'',0),(2996,206,1319,0,3,1094,1320,4,1068123599,2,197,'',0),(2997,206,1320,0,4,1319,1316,4,1068123599,2,198,'',0),(2998,206,1316,0,5,1320,1321,4,1068123599,2,198,'',0),(2999,206,1321,0,6,1316,0,4,1068123599,2,199,'',0),(3000,207,1096,0,0,0,1121,22,1068123651,1,191,'',0),(3001,207,1121,0,1,1096,1268,22,1068123651,1,191,'',0),(3002,207,1268,0,2,1121,1322,22,1068123651,1,193,'',0),(3003,207,1322,0,3,1268,1323,22,1068123651,1,193,'',0),(3004,207,1323,0,4,1322,1324,22,1068123651,1,193,'',0),(3005,207,1324,0,5,1323,0,22,1068123651,1,193,'',0),(3006,208,1310,0,0,0,5,22,1068126974,1,191,'',0),(3007,208,5,0,1,1310,1325,22,1068126974,1,191,'',0),(3008,208,1325,0,2,5,1326,22,1068126974,1,193,'',0),(3009,208,1326,0,3,1325,0,22,1068126974,1,193,'',0),(3023,149,1339,0,2,1338,1316,4,1068041016,2,197,'',0),(3022,149,1338,0,1,1337,1339,4,1068041016,2,9,'',0),(3021,149,1337,0,0,0,1338,4,1068041016,2,8,'',0),(3270,1,1094,0,9,1153,1143,1,1033917596,1,119,'',0),(3269,1,1153,0,8,1115,1094,1,1033917596,1,119,'',0),(3268,1,1115,0,7,1143,1153,1,1033917596,1,119,'',0),(3267,1,1143,0,6,1405,1115,1,1033917596,1,119,'',0),(3266,1,1405,0,5,1404,1143,1,1033917596,1,119,'',0),(3265,1,1404,0,4,1403,1405,1,1033917596,1,119,'',0),(3264,1,1403,0,3,1402,1404,1,1033917596,1,119,'',0),(3263,1,1402,0,2,1401,1403,1,1033917596,1,119,'',0),(3059,209,1355,0,0,0,1356,21,1068468136,1,188,'',0),(3060,209,1356,0,1,1355,1357,21,1068468136,1,188,'',0),(3061,209,1357,0,2,1356,1257,21,1068468136,1,189,'',0),(3062,209,1257,0,3,1357,0,21,1068468136,1,190,'',1),(3069,210,1363,0,0,0,1364,22,1068468573,1,191,'',0),(3070,210,1364,0,1,1363,1365,22,1068468573,1,191,'',0),(3071,210,1365,0,2,1364,1366,22,1068468573,1,193,'',0),(3072,210,1366,0,3,1365,1264,22,1068468573,1,193,'',0),(3073,210,1264,0,4,1366,1264,22,1068468573,1,193,'',0),(3074,210,1264,0,5,1264,1367,22,1068468573,1,193,'',0),(3075,210,1367,0,6,1264,1368,22,1068468573,1,193,'',0),(3076,210,1368,0,7,1367,0,22,1068468573,1,193,'',0),(3110,142,381,0,4,1099,0,20,1068036660,1,187,'',0),(3092,211,1373,0,2,1372,1374,1,1068640085,1,4,'',0),(3091,211,1372,0,1,1081,1373,1,1068640085,1,4,'',0),(3090,211,1081,0,0,0,1372,1,1068640085,1,4,'',0),(3093,211,1374,0,3,1373,1375,1,1068640085,1,119,'',0),(3094,211,1375,0,4,1374,73,1,1068640085,1,119,'',0),(3095,211,73,0,5,1375,1376,1,1068640085,1,119,'',0),(3096,211,1376,0,6,73,1377,1,1068640085,1,119,'',0),(3097,211,1377,0,7,1376,1260,1,1068640085,1,119,'',0),(3098,211,1260,0,8,1377,1378,1,1068640085,1,119,'',0),(3099,211,1378,0,9,1260,1379,1,1068640085,1,119,'',0),(3100,211,1379,0,10,1378,0,1,1068640085,1,119,'',0),(3111,212,1380,0,0,0,1381,22,1068726592,1,191,'',0),(3112,212,1381,0,1,1380,1142,22,1068726592,1,191,'',0),(3113,212,1142,0,2,1381,1382,22,1068726592,1,193,'',0),(3114,212,1382,0,3,1142,1383,22,1068726592,1,193,'',0),(3115,212,1383,0,4,1382,1384,22,1068726592,1,193,'',0),(3116,212,1384,0,5,1383,1385,22,1068726592,1,193,'',0),(3117,212,1385,0,6,1384,1386,22,1068726592,1,193,'',0),(3118,212,1386,0,7,1385,1387,22,1068726592,1,193,'',0),(3119,212,1387,0,8,1386,1388,22,1068726592,1,193,'',0),(3120,212,1388,0,9,1387,1142,22,1068726592,1,193,'',0),(3121,212,1142,0,10,1388,1382,22,1068726592,1,193,'',0),(3122,212,1382,0,11,1142,1383,22,1068726592,1,193,'',0),(3123,212,1383,0,12,1382,1384,22,1068726592,1,193,'',0),(3124,212,1384,0,13,1383,1385,22,1068726592,1,193,'',0),(3125,212,1385,0,14,1384,1386,22,1068726592,1,193,'',0),(3126,212,1386,0,15,1385,1387,22,1068726592,1,193,'',0),(3127,212,1387,0,16,1386,1388,22,1068726592,1,193,'',0),(3128,212,1388,0,17,1387,1142,22,1068726592,1,193,'',0),(3129,212,1142,0,18,1388,1382,22,1068726592,1,193,'',0),(3130,212,1382,0,19,1142,1383,22,1068726592,1,193,'',0),(3131,212,1383,0,20,1382,1384,22,1068726592,1,193,'',0),(3132,212,1384,0,21,1383,1385,22,1068726592,1,193,'',0),(3133,212,1385,0,22,1384,1386,22,1068726592,1,193,'',0),(3134,212,1386,0,23,1385,1387,22,1068726592,1,193,'',0),(3135,212,1387,0,24,1386,1388,22,1068726592,1,193,'',0),(3136,212,1388,0,25,1387,1142,22,1068726592,1,193,'',0),(3137,212,1142,0,26,1388,1382,22,1068726592,1,193,'',0),(3138,212,1382,0,27,1142,1383,22,1068726592,1,193,'',0),(3139,212,1383,0,28,1382,1384,22,1068726592,1,193,'',0),(3140,212,1384,0,29,1383,1385,22,1068726592,1,193,'',0),(3141,212,1385,0,30,1384,1386,22,1068726592,1,193,'',0),(3142,212,1386,0,31,1385,1387,22,1068726592,1,193,'',0),(3143,212,1387,0,32,1386,1388,22,1068726592,1,193,'',0),(3144,212,1388,0,33,1387,1142,22,1068726592,1,193,'',0),(3145,212,1142,0,34,1388,1382,22,1068726592,1,193,'',0),(3146,212,1382,0,35,1142,1383,22,1068726592,1,193,'',0),(3147,212,1383,0,36,1382,1384,22,1068726592,1,193,'',0),(3148,212,1384,0,37,1383,1385,22,1068726592,1,193,'',0),(3149,212,1385,0,38,1384,1386,22,1068726592,1,193,'',0),(3150,212,1386,0,39,1385,1387,22,1068726592,1,193,'',0),(3151,212,1387,0,40,1386,1388,22,1068726592,1,193,'',0),(3152,212,1388,0,41,1387,1142,22,1068726592,1,193,'',0),(3153,212,1142,0,42,1388,1382,22,1068726592,1,193,'',0),(3154,212,1382,0,43,1142,1383,22,1068726592,1,193,'',0),(3155,212,1383,0,44,1382,1384,22,1068726592,1,193,'',0),(3156,212,1384,0,45,1383,1385,22,1068726592,1,193,'',0),(3157,212,1385,0,46,1384,1386,22,1068726592,1,193,'',0),(3158,212,1386,0,47,1385,1387,22,1068726592,1,193,'',0),(3159,212,1387,0,48,1386,1388,22,1068726592,1,193,'',0),(3160,212,1388,0,49,1387,1142,22,1068726592,1,193,'',0),(3161,212,1142,0,50,1388,1382,22,1068726592,1,193,'',0),(3162,212,1382,0,51,1142,1383,22,1068726592,1,193,'',0),(3163,212,1383,0,52,1382,1384,22,1068726592,1,193,'',0),(3164,212,1384,0,53,1383,1385,22,1068726592,1,193,'',0),(3165,212,1385,0,54,1384,1386,22,1068726592,1,193,'',0),(3166,212,1386,0,55,1385,1387,22,1068726592,1,193,'',0),(3167,212,1387,0,56,1386,1388,22,1068726592,1,193,'',0),(3168,212,1388,0,57,1387,0,22,1068726592,1,193,'',0),(3169,214,195,0,0,0,51,21,1068727316,1,188,'',0),(3170,214,51,0,1,195,5,21,1068727316,1,188,'',0),(3171,214,5,0,2,51,5,21,1068727316,1,189,'',0),(3172,214,5,0,3,5,5,21,1068727316,1,189,'',0),(3173,214,5,0,4,5,5,21,1068727316,1,189,'',0),(3174,214,5,0,5,5,5,21,1068727316,1,189,'',0),(3175,214,5,0,6,5,5,21,1068727316,1,189,'',0),(3176,214,5,0,7,5,5,21,1068727316,1,189,'',0),(3177,214,5,0,8,5,5,21,1068727316,1,189,'',0),(3178,214,5,0,9,5,5,21,1068727316,1,189,'',0),(3179,214,5,0,10,5,5,21,1068727316,1,189,'',0),(3180,214,5,0,11,5,5,21,1068727316,1,189,'',0),(3181,214,5,0,12,5,5,21,1068727316,1,189,'',0),(3182,214,5,0,13,5,5,21,1068727316,1,189,'',0),(3183,214,5,0,14,5,5,21,1068727316,1,189,'',0),(3184,214,5,0,15,5,5,21,1068727316,1,189,'',0),(3185,214,5,0,16,5,5,21,1068727316,1,189,'',0),(3186,214,5,0,17,5,5,21,1068727316,1,189,'',0),(3187,214,5,0,18,5,5,21,1068727316,1,189,'',0),(3188,214,5,0,19,5,5,21,1068727316,1,189,'',0),(3189,214,5,0,20,5,5,21,1068727316,1,189,'',0),(3190,214,5,0,21,5,5,21,1068727316,1,189,'',0),(3191,214,5,0,22,5,5,21,1068727316,1,189,'',0),(3192,214,5,0,23,5,5,21,1068727316,1,189,'',0),(3193,214,5,0,24,5,5,21,1068727316,1,189,'',0),(3194,214,5,0,25,5,5,21,1068727316,1,189,'',0),(3195,214,5,0,26,5,5,21,1068727316,1,189,'',0),(3196,214,5,0,27,5,5,21,1068727316,1,189,'',0),(3197,214,5,0,28,5,5,21,1068727316,1,189,'',0),(3198,214,5,0,29,5,5,21,1068727316,1,189,'',0),(3199,214,5,0,30,5,5,21,1068727316,1,189,'',0),(3200,214,5,0,31,5,5,21,1068727316,1,189,'',0),(3201,214,5,0,32,5,5,21,1068727316,1,189,'',0),(3202,214,5,0,33,5,5,21,1068727316,1,189,'',0),(3203,214,5,0,34,5,5,21,1068727316,1,189,'',0),(3204,214,5,0,35,5,5,21,1068727316,1,189,'',0),(3205,214,5,0,36,5,5,21,1068727316,1,189,'',0),(3206,214,5,0,37,5,5,21,1068727316,1,189,'',0),(3207,214,5,0,38,5,5,21,1068727316,1,189,'',0),(3208,214,5,0,39,5,5,21,1068727316,1,189,'',0),(3209,214,5,0,40,5,5,21,1068727316,1,189,'',0),(3210,214,5,0,41,5,1092,21,1068727316,1,189,'',0),(3211,214,1092,0,42,5,0,21,1068727316,1,190,'',0),(3212,215,1389,0,0,0,1390,22,1068728070,1,191,'',0),(3213,215,1390,0,1,1389,1390,22,1068728070,1,193,'',0),(3214,215,1390,0,2,1390,1390,22,1068728070,1,193,'',0),(3215,215,1390,0,3,1390,1390,22,1068728070,1,193,'',0),(3216,215,1390,0,4,1390,1390,22,1068728070,1,193,'',0),(3217,215,1390,0,5,1390,1390,22,1068728070,1,193,'',0),(3218,215,1390,0,6,1390,1390,22,1068728070,1,193,'',0),(3219,215,1390,0,7,1390,1390,22,1068728070,1,193,'',0),(3220,215,1390,0,8,1390,1390,22,1068728070,1,193,'',0),(3221,215,1390,0,9,1390,1390,22,1068728070,1,193,'',0),(3222,215,1390,0,10,1390,1390,22,1068728070,1,193,'',0),(3223,215,1390,0,11,1390,1390,22,1068728070,1,193,'',0),(3224,215,1390,0,12,1390,1390,22,1068728070,1,193,'',0),(3225,215,1390,0,13,1390,1390,22,1068728070,1,193,'',0),(3226,215,1390,0,14,1390,1390,22,1068728070,1,193,'',0),(3227,215,1390,0,15,1390,1390,22,1068728070,1,193,'',0),(3228,215,1390,0,16,1390,0,22,1068728070,1,193,'',0),(3262,1,1401,0,1,1081,1402,1,1033917596,1,119,'',0),(3261,1,1081,0,0,0,1401,1,1033917596,1,4,'',0),(3311,56,1433,0,7,1432,0,15,1066643397,11,202,'',0),(3310,56,1432,0,6,1431,1433,15,1066643397,11,202,'',0),(3309,56,1431,0,5,1430,1432,15,1066643397,11,202,'',0),(3308,56,1430,0,4,1429,1431,15,1066643397,11,202,'',0),(3307,56,1429,0,3,1428,1430,15,1066643397,11,202,'',0),(3306,56,1428,0,2,1427,1429,15,1066643397,11,202,'',0),(3305,56,1427,0,1,1426,1428,15,1066643397,11,202,'',0),(3304,56,1426,0,0,0,1427,15,1066643397,11,161,'',0);
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
INSERT INTO ezsearch_return_count VALUES (1,1,1066398569,1),(2,2,1066909621,1),(3,3,1066910511,1),(4,4,1066912239,1),(5,5,1066982534,1),(6,6,1066991890,4),(7,6,1066992837,4),(8,6,1066992963,4),(9,6,1066992972,0),(10,6,1066993049,0),(11,6,1066993056,4),(12,6,1066993091,4),(13,6,1066993127,4),(14,6,1066993135,4),(15,6,1066993895,4),(16,6,1066993946,4),(17,6,1066993995,4),(18,6,1066994001,4),(19,6,1066994050,4),(20,6,1066994057,4),(21,6,1066994067,4),(22,7,1066996820,0),(23,5,1066997190,1),(24,5,1066997194,1),(25,8,1066998830,1),(26,8,1066998836,1),(27,8,1066998870,1),(28,9,1066998915,1),(29,10,1067003146,0),(30,11,1067003155,2),(31,6,1067005771,4),(32,6,1067005777,4),(33,6,1067005801,4),(34,12,1067006770,1),(35,12,1067006774,1),(36,12,1067006777,1),(37,12,1067006787,1),(38,12,1067006803,1),(39,12,1067006996,1),(40,12,1067008585,1),(41,12,1067008597,1),(42,12,1067008602,0),(43,12,1067008608,1),(44,12,1067008613,0),(45,12,1067008620,0),(46,12,1067008625,0),(47,12,1067008629,1),(48,12,1067008655,1),(49,12,1067008659,0),(50,12,1067008663,0),(51,12,1067008667,0),(52,12,1067008711,0),(53,12,1067008717,0),(54,12,1067008720,1),(55,12,1067008725,0),(56,12,1067008920,1),(57,12,1067008925,1),(58,12,1067008929,0),(59,12,1067008934,1),(60,12,1067009005,1),(61,12,1067009023,1),(62,12,1067009042,1),(63,12,1067009051,0),(64,13,1067009056,1),(65,14,1067009067,0),(66,14,1067009073,0),(67,13,1067009594,1),(68,13,1067009816,1),(69,13,1067009953,1),(70,13,1067010181,1),(71,13,1067010352,1),(72,13,1067010359,1),(73,13,1067010370,1),(74,13,1067010509,1),(75,6,1067241668,5),(76,6,1067241727,5),(77,6,1067241742,5),(78,6,1067241760,5),(79,6,1067241810,5),(80,6,1067241892,5),(81,6,1067241928,5),(82,6,1067241953,5),(83,14,1067252984,0),(84,14,1067252987,0),(85,14,1067253026,0),(86,14,1067253160,0),(87,14,1067253218,0),(88,14,1067253285,0),(89,5,1067520640,1),(90,5,1067520646,1),(91,5,1067520658,1),(92,5,1067520704,0),(93,5,1067520753,0),(94,5,1067520761,1),(95,5,1067520769,1),(96,5,1067521324,1),(97,5,1067521402,1),(98,5,1067521453,1),(99,5,1067521532,1),(100,5,1067521615,1),(101,5,1067521674,1),(102,5,1067521990,1),(103,5,1067522592,1),(104,5,1067522620,1),(105,5,1067522888,1),(106,5,1067522987,1),(107,5,1067523012,1),(108,5,1067523144,1),(109,5,1067523213,1),(110,5,1067523261,1),(111,5,1067523798,1),(112,5,1067523805,1),(113,5,1067523820,1),(114,5,1067523858,1),(115,5,1067524474,1),(116,5,1067524629,1),(117,5,1067524696,1),(118,15,1067526426,0),(119,15,1067526433,0),(120,15,1067526701,0),(121,15,1067527009,0),(122,5,1067527022,1),(123,5,1067527033,1),(124,5,1067527051,1),(125,5,1067527069,1),(126,5,1067527076,0),(127,5,1067527124,1),(128,5,1067527176,1),(129,16,1067527188,0),(130,16,1067527227,0),(131,16,1067527244,0),(132,16,1067527301,0),(133,5,1067527315,0),(134,5,1067527349,0),(135,5,1067527412,0),(136,5,1067527472,1),(137,5,1067527502,1),(138,5,1067527508,0),(139,17,1067527848,0),(140,5,1067527863,1),(141,5,1067527890,1),(142,5,1067527906,1),(143,5,1067527947,1),(144,5,1067527968,0),(145,5,1067527993,0),(146,5,1067528010,1),(147,5,1067528029,0),(148,5,1067528045,0),(149,5,1067528050,0),(150,5,1067528056,0),(151,5,1067528061,0),(152,5,1067528063,0),(153,18,1067528100,1),(154,18,1067528113,0),(155,18,1067528190,1),(156,18,1067528236,1),(157,18,1067528270,1),(158,18,1067528309,1),(159,5,1067528323,0),(160,18,1067528334,1),(161,18,1067528355,1),(162,5,1067528368,0),(163,5,1067528377,1),(164,19,1067528402,0),(165,19,1067528770,0),(166,19,1067528924,0),(167,19,1067528963,0),(168,19,1067529028,0),(169,19,1067529054,0),(170,19,1067529119,0),(171,19,1067529169,0),(172,19,1067529211,0),(173,19,1067529263,0),(174,20,1067943156,3),(175,4,1067943454,1),(176,4,1067943503,1),(177,4,1067943525,1),(178,21,1067943559,1),(179,21,1067945657,1),(180,21,1067945693,1),(181,21,1067945697,1),(182,21,1067945707,1),(183,22,1067945890,0),(184,20,1067945898,3),(185,23,1067946301,6),(186,24,1067946325,1),(187,24,1067946432,1),(188,25,1067946484,4),(189,26,1067946492,1),(190,27,1067946577,1),(191,25,1067946691,4),(192,4,1067946702,1),(193,4,1067947201,1),(194,4,1067947228,1),(195,4,1067948201,1),(196,5,1068028867,0),(197,12,1068028883,0),(198,28,1068028898,2),(199,5,1068040205,0),(200,29,1068048420,0),(201,29,1068048455,1),(202,30,1068048466,0),(203,29,1068048480,0),(204,30,1068048487,2),(205,29,1068048592,0),(206,30,1068048615,2),(207,30,1068048653,2),(208,30,1068048698,2),(209,30,1068048707,2),(210,30,1068048799,2),(211,30,1068048825,2),(212,30,1068048830,2),(213,30,1068048852,2),(214,30,1068048874,2),(215,30,1068048890,2),(216,30,1068048918,2),(217,30,1068048928,2),(218,31,1068048940,2),(219,31,1068048964,2),(220,20,1068049003,0),(221,20,1068049007,2),(222,25,1068049014,3),(223,25,1068049043,3),(224,25,1068049062,3),(225,25,1068049082,3),(226,32,1068112266,5),(227,30,1068468248,3);
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
INSERT INTO ezsearch_search_phrase VALUES (1,'documents'),(2,'wenyue'),(3,'xxx'),(4,'release'),(5,'test'),(6,'ez'),(7,'f1'),(8,'bjørn'),(9,'abb'),(10,'2-2'),(11,'3.2'),(12,'bård'),(13,'Vidar'),(14,'tewtet'),(15,'dcv'),(16,'gr'),(17,'tewt'),(18,'members'),(19,'regte'),(20,'news'),(21,'german'),(22,'info'),(23,'information'),(24,'folder'),(25,'about'),(26,'2'),(27,'systems'),(28,'the'),(29,'football'),(30,'foo'),(31,'my'),(32,'reply');
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
INSERT INTO ezsearch_word VALUES (5,'test',3),(6,'media',1),(7,'setup',3),(1412,'grouplist',1),(1411,'class',1),(1410,'classes',1),(11,'links',1),(25,'content',2),(34,'feel',2),(33,'and',2),(32,'look',2),(37,'news',3),(1235,'facilisi',1),(1431,'as',1),(1234,'sapien',1),(51,'topic',4),(1233,'non',1),(1141,'bulletin',1),(1227,'habitasse',1),(1226,'hac',1),(1355,'hjg',1),(934,'about',2),(1296,'jkl',1),(73,'this',7),(74,'is',4),(1295,'klj',1),(77,'for',2),(1306,'sfsf',1),(1305,'t4',1),(1309,'sfes',1),(1232,'ornare',1),(89,'a',4),(1308,'sefsefg',1),(1307,'test2',2),(1285,'uiyuiyuiyuiy',1),(1284,'uyuiyui',1),(1278,'sdgfsdfgsdfg',1),(1277,'sdgflksdjfg',1),(1231,'interdum',1),(1433,'2003',1),(1095,'discuss',2),(1094,'music',3),(1122,'cool',1),(1121,'reply',7),(1092,'0',10),(1225,'fringilla',1),(1224,'nonummy',1),(1276,'sdfgsdfg',1),(1275,'kljlkj',1),(1223,'fermentum',1),(1222,'vestibulum',1),(1221,'erat',1),(1220,'vitae',1),(1432,'1999',1),(1430,'systems',1),(1405,'site',1),(1404,'community',1),(1219,'mi',1),(1429,'ez',1),(1428,'&copy',1),(1427,'copyright',1),(1426,'forum_package',1),(195,'new',3),(1390,'ljdfowihogho',1),(1389,'fowehfowhi',1),(1388,'dogs',1),(1387,'lazy',1),(1386,'over',1),(1385,'jumps',1),(1384,'fox',1),(1383,'brown',1),(1382,'quick',1),(1230,'duis',1),(381,'here',3),(1274,'ssssstick',1),(1381,'melding',1),(1380,'annen',1),(1379,'shown',1),(1378,'be',1),(1273,'lkj',2),(1377,'may',1),(1376,'text',1),(1375,'description',1),(1374,'no',1),(1373,'group',1),(1372,'main',1),(1368,'sdfgsdgfsdgf',1),(1367,'sdfgsdgfsdfg',1),(1366,'fgs',1),(1270,'hbe',1),(1365,'sdfgsd',1),(1364,'fghklj',1),(1363,'dfghd',1),(1357,'sdjgfsdjgh',1),(1356,'dghsdjgf',1),(1403,'our',1),(1402,'to',1),(1340,'kghjohtkæ',1),(1339,'director',1),(1269,'trwebhr',1),(1268,'dsfgsdfg',2),(1267,'whacky',1),(1266,'important',1),(1265,'dsfg',1),(1264,'sdfg',3),(1417,'urltranslator',1),(1263,'dsfsdfg',1),(1262,'2',2),(1261,'kjh',1),(1260,'not',3),(1259,'msg',1),(1258,'jkh',2),(1257,'1',4),(1256,'gklsdgf',1),(1255,'klsd',1),(1409,'cache',1),(1254,'gklsdg',1),(1253,'lskdj',1),(1252,'teset',1),(1251,'sticky',4),(1250,'laoreet',1),(1249,'quis',1),(1248,'cursus',1),(1247,'integer',1),(1246,'porttitor',1),(1245,'dui',1),(1244,'bibendum',1),(1243,'nam',1),(1242,'donec',1),(1241,'consequat',1),(1240,'viverra',1),(1239,'sem',1),(1238,'congue',1),(1237,'nec',1),(1236,'suspendisse',1),(1229,'dictumst',1),(1228,'platea',1),(1146,'dall',1),(1145,'dill',1),(1144,'from',1),(1143,'latest',3),(1142,'the',2),(1326,'sdfs',1),(1401,'welcome',1),(1325,'sdfsdf',1),(1324,'g',1),(1323,'gsdf',1),(1322,'sdf',1),(1321,'sig',1),(1320,'oslo',1),(1319,'guru',1),(1318,'farstad',1),(1362,'developer',1),(1316,'norway',3),(1361,'skien',1),(1218,'magna',1),(1217,'tempor',1),(1360,'uberguru',1),(1359,'user',1),(1311,'sefw',1),(1310,'re',2),(1302,'h',1),(1301,'hdf',1),(1300,'dfhdf',1),(1299,'gh',1),(1298,'fd',1),(1297,'dgf',1),(571,'doe',1),(570,'john',1),(572,'vid',1),(573,'la',1),(1081,'forum',3),(1216,'lectus',1),(1215,'rhoncus',1),(1214,'nunc',1),(1213,'lacus',1),(1212,'accumsan',1),(1211,'vehicula',1),(1210,'velit',1),(1209,'elementum',1),(1208,'tellus',1),(1207,'suscipit',1),(1206,'commodo',1),(1205,'sagittis',1),(1204,'enim',1),(1203,'vel',1),(1202,'felis',1),(1201,'ullamcorper',1),(1200,'pellentesque',1),(1199,'fusce',1),(1198,'tortor',1),(1197,'scelerisque',1),(1196,'pharetra',1),(1195,'aenean',1),(1194,'facilisis',1),(1193,'ut',1),(1192,'tristique',1),(1191,'eros',1),(1190,'turpis',1),(1189,'eu',1),(1188,'metus',1),(1187,'blandit',1),(1186,'ac',1),(1185,'neque',1),(1184,'dapibus',1),(1183,'volutpat',1),(1182,'iaculis',1),(1181,'id',1),(1180,'purus',1),(1179,'imperdiet',1),(1178,'phasellus',1),(1177,'libero',1),(1176,'at',1),(1175,'tincidunt',1),(1174,'molestie',1),(1173,'eget',1),(1172,'dignissim',1),(1171,'est',1),(1170,'proin',1),(1169,'odio',1),(1168,'morbi',1),(1167,'nulla',1),(1166,'et',1),(1165,'wisi',1),(1164,'diam',1),(1163,'gravida',1),(1162,'aliquam',1),(1161,'quam',1),(1160,'nisl',1),(1159,'eleifend',1),(1158,'sed',1),(1157,'mauris',1),(1156,'egestas',1),(1155,'maecenas',1),(1154,'massa',1),(1153,'in',2),(1152,'elit',1),(1151,'adipiscing',1),(1150,'consectetuer',1),(1149,'amet',1),(944,'ipsum',2),(943,'lorem',2),(1148,'sit',1),(1147,'dolor',1),(1118,'find',1),(1117,'will',1),(1116,'you',1),(1414,'56',1),(1413,'edit',1),(1416,'translator',1),(1415,'url',1),(1097,'message',1),(1096,'my',3),(1115,'discussions',2),(1087,'discussion',3),(1119,'different',1),(1098,'body',1),(1099,'sports',2),(1131,'tesetset',1),(1130,'bar',1),(1125,'foo',3),(1338,'yu',1),(1337,'wenyue',1),(1124,'www.exampleurl.com',1),(1123,'http',3),(1111,'football',1),(1112,'ghfn',1),(1113,'gnf',1),(1114,'nfnfn',1),(1120,'forums',1),(1132,'what',1),(1133,'pop',1),(1134,'www.foo.bar.com',1),(1135,'ehh',1),(1358,'administrator',1),(1138,'wanted',1),(1139,'wz.nozw.zno',1),(1140,'bård',2);
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
INSERT INTO ezsession VALUES ('b9fec7acd95e8e2153126a4dcf387441',1069675723,'eZUserGroupsCache_Timestamp|i:1068821461;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1068821461;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069416057;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069416138;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"20\";s:4:\"name\";s:5:\"Forum\";}i:10;a:2:{s:2:\"id\";s:2:\"21\";s:4:\"name\";s:11:\"Forum topic\";}i:11;a:2:{s:2:\"id\";s:2:\"22\";s:4:\"name\";s:11:\"Forum reply\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|s:2:\"on\";eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}LastAccessesURI|s:21:\"/content/view/full/46\";eZUserDiscountRulesTimestamp|i:1068825416;eZUserDiscountRules14|a:0:{}FromGroupID|b:0;'),('f118346bdcfaf167022f0bfcce5d0b6c',1069503751,'LastAccessesURI|s:22:\"/content/view/full/154\";eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069244535;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069244535;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069244535;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:1:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069244535;eZUserDiscountRules10|a:0:{}userLimitationValues|a:1:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRules14|a:0:{}'),('f01cd9ad6c05527b5a586df7a6e97468',1069684821,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069425619;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425619;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069425619;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"377\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"378\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069425619;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}'),('237c45bc0d93196aea4652196dcb1ca1',1069939263,'eZUserInfoCache_Timestamp|i:1069427803;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069427803;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069427804;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}eZUserDiscountRulesTimestamp|i:1069427804;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/149\";canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069675856;canInstantiateClasses|i:1;Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}eZUserDiscountRules14|a:0:{}classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"20\";s:4:\"name\";s:5:\"Forum\";}i:10;a:2:{s:2:\"id\";s:2:\"21\";s:4:\"name\";s:11:\"Forum topic\";}i:11;a:2:{s:2:\"id\";s:2:\"22\";s:4:\"name\";s:11:\"Forum reply\";}}Preferences-advanced_menu|s:2:\"on\";FromGroupID|s:1:\"2\";'),('4ab459d79b889d215fc807e1f9abaac8',1069946723,'eZUserGroupsCache_Timestamp|i:1069687476;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069687476;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069687476;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069687477;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"20\";s:4:\"name\";s:5:\"Forum\";}i:10;a:2:{s:2:\"id\";s:2:\"21\";s:4:\"name\";s:11:\"Forum topic\";}i:11;a:2:{s:2:\"id\";s:2:\"22\";s:4:\"name\";s:11:\"Forum reply\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|s:2:\"on\";eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}'),('09ecf1bc7b25aa000603d92f5117e28f',1069510532,'eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1068554682;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1068653983;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069251319;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:1:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}LastAccessesURI|s:22:\"/content/view/full/127\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRulesTimestamp|i:1068554682;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}'),('6b757a80dcd2886681c0a2dc420526f6',1069686094,'LastAccessesURI|s:22:\"/content/view/full/114\";eZUserInfoCache_Timestamp|i:1068468222;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069065855;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069426614;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}userLimitations|a:1:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1068565925;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"20\";s:4:\"name\";s:5:\"Forum\";}i:10;a:2:{s:2:\"id\";s:2:\"21\";s:4:\"name\";s:11:\"Forum topic\";}i:11;a:2:{s:2:\"id\";s:2:\"22\";s:4:\"name\";s:11:\"Forum reply\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|s:2:\"on\";FromGroupID|s:1:\"1\";eZUserDiscountRulesTimestamp|i:1068818586;eZUserDiscountRules14|a:0:{}eZUserLoggedInID|s:2:\"14\";UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}');
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,NULL),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,NULL),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,NULL),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,NULL),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,NULL),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,NULL),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,NULL),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,NULL),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,NULL),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0),(125,'discussions/forum_main_group/music_discussion/latest_msg_not_sticky','70cf693961dcdd67766bf941c3ed2202','content/view/full/130',1,0,0),(126,'discussions/forum_main_group/music_discussion/not_sticky_2','969f470c93e2131a0884648b91691d0b','content/view/full/131',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,115,0),(124,'discussions/forum_main_group/music_discussion/new_topic_sticky/reply','f3dd8b6512a0b04b426ef7d7307b7229','content/view/full/129',1,0,0),(121,'news/news_bulletin','9365952d8950c12f923a3a48e5e27fa3','content/view/full/126',1,0,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,0,0),(123,'discussions/forum_main_group/music_discussion/new_topic_sticky','bf37b4a370ddb3935d0625a5b348dd20','content/view/full/128',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,115,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,115,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(127,'discussions/forum_main_group/music_discussion/important_sticky','2f16cf3039c97025a43f23182b4b6d60','content/view/full/132',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(74,'users/editors/john_doe','470ba5117b9390b819f7c2519c0a6092','content/view/full/91',1,0,0),(75,'users/editors/vid_la','73f7efbac10f9f69aa4f7b19c97cfb16','content/view/full/92',1,0,0),(102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0),(101,'forum','bbdbe444288550204c968fe7002a97a9','content/view/full/111',1,112,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(104,'discussions/music_discussion','09533dfccc8477debe545d31bccf391f','content/view/full/114',1,149,0),(105,'discussions/forum_main_group/music_discussion/this_is_a_new_topic','cec6b1593bf03079990a89a3fdc60c56','content/view/full/112',1,0,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(107,'discussions/sports_discussion','c551943f4df3c58a693f8ba55e9b6aeb','content/view/full/115',1,151,0),(117,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/foo_bar','741cdf9f1ee1fa974ea7ec755f538271','content/view/full/122',1,0,0),(109,'users/administrator_users/wenyue_yu','823d93f67a2868cf64fecf47ea766bce','content/view/full/117',1,0,0),(111,'discussions/forum_main_group/sports_discussion/football','6e9c09d390322aa44bb5108b93f5f17c','content/view/full/119',1,0,0),(112,'discussions','48ee344d9a540894650ce4af27e169dd','content/view/full/111',1,0,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,0,0),(114,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/my_reply','1e03a7609698aa8a98dccf1178df0e6f','content/view/full/120',1,0,0),(118,'discussions/forum_main_group/music_discussion/what_about_pop','c4ebc99b2ed9792d1aee0e5fe210b852','content/view/full/123',1,0,0),(119,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic','6c20d2df5a828dcdb6a4fcb4897bb643','content/view/full/124',1,0,0),(120,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','de98a1bb645ea84919a5e34688ff84e2','content/view/full/125',1,0,0),(128,'discussions/forum_main_group/sports_discussion/football/reply_2','13a443b7e046bb36831640f1d19e33d9','content/view/full/133',1,0,0),(130,'discussions/forum_main_group/music_discussion/lkj_ssssstick','75ee87c770e4e8be9d44200cdb71d071','content/view/full/135',1,0,0),(131,'discussions/forum_main_group/music_discussion/foo','12c58f35c1114deeb172aba728c50ca8','content/view/full/136',1,0,0),(132,'discussions/forum_main_group/music_discussion/lkj_ssssstick/reply','6040856b4ec5bcc1c699d95020005be5','content/view/full/137',1,0,0),(135,'discussions/forum_main_group/music_discussion/lkj_ssssstick/uyuiyui','4c48104ea6e5ec2a78067374d9561fcb','content/view/full/140',1,0,0),(136,'discussions/forum_main_group/music_discussion/test2','53f71d4ff69ffb3bf8c8ccfb525eabd3','content/view/full/141',1,0,0),(137,'discussions/forum_main_group/music_discussion/t4','5da27cda0fbcd5290338b7d22cfd730c','content/view/full/142',1,0,0),(138,'discussions/forum_main_group/music_discussion/lkj_ssssstick/klj_jkl_klj','9ae60fa076882d6807506c2232143d27','content/view/full/143',1,0,0),(139,'discussions/forum_main_group/music_discussion/test2/retest2','a17d07fbbd2d1b6d0fbbf8ca1509cd01','content/view/full/144',1,0,0),(140,'users/administrator_users/brd_farstad','875930f56fad1a5cc6fbcac4ed6d3f8d','content/view/full/145',1,0,0),(141,'discussions/forum_main_group/music_discussion/lkj_ssssstick/my_reply','1f95000d1f993ffa16a0cf83b78515bf','content/view/full/146',1,0,0),(142,'discussions/forum_main_group/music_discussion/lkj_ssssstick/retest','0686f14064a420e6ee95aabf89c4a4f2','content/view/full/147',1,0,0),(143,'users/hjg_dghsdjgf','57c9df2797a65a8ca64a400534e60ae5','content/view/full/148',1,0,0),(144,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf','21f0ee2122dd5264192adc15c1e69c03','content/view/full/149',1,0,0),(145,'users/dfghd_fghklj','38a8641a7d7614751346384fbe37163b','content/view/full/150',1,0,0),(146,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/dfghd_fghklj','460d30ba47855079ac8605e1c8085993','content/view/full/151',1,0,0),(147,'discussions/folder','fb8d0dc27cea3b666b0e76e4b6805d77','content/view/full/152',1,148,0),(148,'discussions/forum_main_group','cb4217f89d8a4365cfef45f8cb50a1cc','content/view/full/152',1,0,0),(149,'discussions/forum_main_group/music_discussion','a1a79985f113d5b05b22c9686b46b175','content/view/full/114',1,0,0),(150,'discussions/music_discussion/*','2ec2a3bfcf01ad3f1323390ab26dfeac','discussions/forum_main_group/music_discussion/{1}',1,0,1),(151,'discussions/forum_main_group/sports_discussion','b68c5a82b8b2035eeee5788cb223bb7e','content/view/full/115',1,0,0),(152,'discussions/sports_discussion/*','7acbf48218ca6e1d80c267911860d34f','discussions/forum_main_group/sports_discussion/{1}',1,0,1),(153,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/annen_melding','08f9d18ed0339d4e55b65f95c364bcab','content/view/full/153',1,0,0),(154,'discussions/forum_main_group/music_discussion/new_topic','d0737d0b4fd90b449ca485e39838afa5','content/view/full/154',1,0,0),(155,'discussions/forum_main_group/music_discussion/new_topic/fowehfowhi','023b81479c508b02f5ac0b88de6931c4','content/view/full/155',1,0,0);
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
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','bf@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(206,'bfbf','bf@piranha.no',2,'78be1382dd64e987845778e68cf04968'),(107,'john','doe@ez.no',2,'e82dc887aa749d7bc91b9bc489e61968'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(111,'vidla','vl@ez.no',2,'5289e8d223b023d527c47d58da538068'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d'),(149,'wy','wy@ez.no',2,'123a284182a7d594ba92ce7cd334dc2b'),(187,'','',1,''),(189,'','',1,'');
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

