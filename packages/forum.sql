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
INSERT INTO ezcontentbrowserecent VALUES (35,111,99,1067006746,'foo bar corp'),(84,14,168,1069768853,'Forum rules'),(65,149,135,1068126974,'lkj ssssstick'),(49,10,12,1068112852,'Guest accounts'),(64,206,135,1068123651,'lkj ssssstick');
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(202,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(186,0,20,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(187,0,20,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(201,0,20,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(198,0,4,'location','Location','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(199,0,4,'signature','Signature','eztext',1,0,6,2,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'sitestyle','Sitestyle','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'sitestyle','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(191,0,22,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(193,0,22,'message','Message','eztext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(194,1,21,'notify_me_about_updates','Notify me about updates','ezsubtreesubscription',0,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(188,1,21,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(190,1,21,'sticky','Sticky','ezboolean',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(190,0,21,'sticky','Sticky','ezboolean',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(194,0,21,'notify_me_about_updates','Notify me about updates','ezsubtreesubscription',0,0,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(189,1,21,'message','Message','eztext',1,1,2,15,0,0,0,0,0,0,0,'','','','','',0,1),(189,0,21,'message','Message','eztext',1,1,2,15,0,0,0,0,0,0,0,'','','','','',0,1),(188,0,21,'subject','Subject','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(200,0,4,'user_image','User image','ezimage',0,0,7,1,0,0,0,0,0,0,0,'','','','','',0,1),(197,0,4,'title','Title','ezstring',1,0,4,25,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1);
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Forum',8,0,1033917596,1068810447,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',7,0,1033920830,1069765740,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',9,0,1066384365,1068825487,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',10,0,1066388816,1068825503,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,4,1,'News',1,0,1066398020,1066398020,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'Forum',59,0,1066643397,1069841894,1,''),(160,14,4,2,'News bulletin',1,0,1068047455,1068047455,1,''),(161,14,1,10,'About this forum',4,0,1068047603,1069763531,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(165,149,1,21,'New Forum topic',1,0,0,0,0,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(226,14,1,20,'Sport forum',1,0,1069766830,1069766830,1,''),(138,14,1,1,'Discussions',2,0,1068036060,1068041936,1,''),(115,14,11,14,'Cache',4,0,1066991725,1068825448,1,''),(116,14,11,14,'URL translator',3,0,1066992054,1068825521,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(141,14,1,20,'Music discussion',4,0,1068036586,1069766561,1,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(228,14,1,22,'Who is Odd?',1,0,1069767050,1069767050,1,''),(227,14,1,21,'What is the best football team in Europe?',1,0,1069766947,1069766947,1,''),(168,149,0,21,'New Forum topic',1,0,0,0,0,''),(169,149,0,21,'New Forum topic',1,0,0,0,0,''),(171,149,1,21,'New Forum topic',1,0,0,0,0,''),(172,149,0,21,'New Forum topic',1,0,0,0,0,''),(173,149,0,21,'New Forum topic',1,0,0,0,0,''),(174,149,0,21,'New Forum topic',1,0,0,0,0,''),(175,149,0,21,'New Forum topic',1,0,0,0,0,''),(176,149,0,21,'New Forum topic',1,0,0,0,0,''),(177,149,0,21,'New Forum topic',1,0,0,0,0,''),(178,149,0,21,'New Forum topic',1,0,0,0,0,''),(179,149,0,21,'New Forum topic',1,0,0,0,0,''),(180,149,0,21,'New Forum topic',1,0,0,0,0,''),(181,149,0,21,'New Forum topic',1,0,0,0,0,''),(182,149,0,21,'New Forum topic',1,0,0,0,0,''),(183,149,0,21,'New Forum topic',1,0,0,0,0,''),(184,149,0,21,'New Forum topic',1,0,0,0,0,''),(185,149,0,21,'New Forum topic',1,0,0,0,0,''),(186,149,0,21,'New Forum topic',1,0,0,0,0,''),(187,14,1,4,'New User',1,0,0,0,0,''),(191,149,1,21,'New Forum topic',1,0,0,0,0,''),(189,14,1,4,'New User',1,0,0,0,0,''),(192,149,0,21,'New Forum topic',1,0,0,0,0,''),(193,149,0,21,'New Forum topic',1,0,0,0,0,''),(194,149,0,21,'New Forum topic',1,0,0,0,0,''),(200,149,1,21,'New Forum topic',1,0,0,0,0,''),(201,149,1,22,'New Forum reply',1,0,0,0,0,''),(232,14,1,21,'Choose the correct forum',2,0,1069768853,1069768887,1,''),(231,14,1,21,'How to Register a New User',2,0,1069768747,1069768899,1,''),(211,14,1,1,'Forum main group',2,0,1068640085,1068640157,1,''),(230,14,1,1,'Forum rules',1,0,1069768639,1069768639,1,''),(229,14,1,22,'Gulset are better than Odd',1,0,1069768552,1069768552,1,''),(217,14,1,22,'New Forum reply',1,0,0,0,0,''),(218,14,4,2,'Choose the correct forum',1,0,1069763601,1069763601,1,''),(219,14,4,2,'Latest forum: Dreamcars',1,0,1069763878,1069763878,1,''),(220,14,1,20,'Dreamcars',2,0,1069763952,1069844000,1,''),(221,14,1,21,'Koenigsegg is the master',1,0,1069765267,1069765267,1,''),(222,14,1,21,'Can\'t leave Ferrari F40 out of it',1,0,1069765545,1069765545,1,''),(223,14,1,22,'Königsegg is the best',2,0,1069765640,1069765670,1,''),(224,14,1,21,'What is wrong with pop?',1,0,1069766105,1069766105,1,''),(225,14,1,22,'Madonna is one of the greats',1,0,1069766473,1069766473,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table border=\"1\"\n           width=\"100%\"\n           class=\"frontpage\">\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',1,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',1,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',1,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',1,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(831,'eng-GB',1,232,188,'Choose the correct forum',0,0,0,0,'choose the correct forum','ezstring'),(832,'eng-GB',1,232,189,'Use a few minutes to consider which forum you should post to. Posting it to a \"General\" forum is not as effective as posting to the \"Install\" forum if you question is about install-related questions. Don\'t post in several different forums. If your question belongs in the \"Install\" forum, posting it in \"General\" or \"Developer\" aren\'t very helpful. \r\nConsider this: If you post to the wrong forum the chances for someone actually answering it is lower than if you post it in the correct forum. Furthermore your off-topic post will just make it harder for those posting correctly to be noticed among those who post in the wrong forums.\r\n',0,0,0,0,'','eztext'),(833,'eng-GB',1,232,190,'',0,0,0,0,'','ezboolean'),(834,'eng-GB',1,232,194,'',0,0,0,0,'','ezsubtreesubscription'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(832,'eng-GB',2,232,189,'Use a few minutes to consider which forum you should post to. Posting it to a \"General\" forum is not as effective as posting to the \"Install\" forum if you question is about install-related questions. Don\'t post in several different forums. If your question belongs in the \"Install\" forum, posting it in \"General\" or \"Developer\" aren\'t very helpful. \r\nConsider this: If you post to the wrong forum the chances for someone actually answering it is lower than if you post it in the correct forum. Furthermore your off-topic post will just make it harder for those posting correctly to be noticed among those who post in the wrong forums.\r\n',0,0,0,0,'','eztext'),(676,'eng-GB',1,200,190,'',0,0,0,0,'','ezboolean'),(677,'eng-GB',1,200,194,'',0,0,0,0,'','ezsubtreesubscription'),(678,'eng-GB',1,201,191,'Re:test',0,0,0,0,'re:test','ezstring'),(679,'eng-GB',1,201,193,'fdsf',0,0,0,0,'','eztext'),(831,'eng-GB',2,232,188,'Choose the correct forum',0,0,0,0,'choose the correct forum','ezstring'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(558,'eng-GB',1,171,189,'',0,0,0,0,'','eztext'),(553,'eng-GB',1,169,190,'',0,0,0,0,'','ezboolean'),(554,'eng-GB',1,169,194,'',0,0,0,0,'','ezsubtreesubscription'),(557,'eng-GB',1,171,188,'',0,0,0,0,'','ezstring'),(552,'eng-GB',1,169,189,'sfsvggs\nsfsf',0,0,0,0,'','eztext'),(547,'eng-GB',1,168,188,'',0,0,0,0,'','ezstring'),(548,'eng-GB',1,168,189,'',0,0,0,0,'','eztext'),(549,'eng-GB',1,168,190,'',0,0,0,0,'','ezboolean'),(550,'eng-GB',1,168,194,'',0,0,0,0,'','ezsubtreesubscription'),(551,'eng-GB',1,169,188,'test',0,0,0,0,'test','ezstring'),(152,'eng-GB',58,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB/forum.gif\"\n         original_filename=\"forum.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069841319\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069841321\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069841321\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB/forum_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069841413\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',58,56,157,'Forum',0,0,0,0,'','ezinisetting'),(521,'eng-GB',1,160,177,'',0,0,0,0,'','ezinteger'),(516,'eng-GB',1,160,1,'News bulletin',0,0,0,0,'news bulletin','ezstring'),(517,'eng-GB',1,160,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(518,'eng-GB',1,160,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(519,'eng-GB',1,160,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin.\"\n         suffix=\"\"\n         basename=\"news_bulletin\"\n         dirpath=\"var/forum/storage/images/news/news_bulletin/519-1-eng-GB\"\n         url=\"var/forum/storage/images/news/news_bulletin/519-1-eng-GB/news_bulletin.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(520,'eng-GB',1,160,123,'',0,0,0,0,'','ezboolean'),(153,'eng-GB',59,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(535,'eng-GB',1,165,188,'',0,0,0,0,'','ezstring'),(536,'eng-GB',1,165,189,'',0,0,0,0,'','eztext'),(537,'eng-GB',1,165,190,'',0,0,0,0,'','ezboolean'),(538,'eng-GB',1,165,194,'',0,0,0,0,'','ezsubtreesubscription'),(151,'eng-GB',58,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(152,'eng-GB',50,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-50-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',53,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',50,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(796,'eng-GB',2,220,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>What is your dreamcar? Ferrari, Diablo or Beetle!</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(830,'eng-GB',1,231,194,'',0,0,0,0,'','ezsubtreesubscription'),(639,'eng-GB',1,192,189,'',0,0,0,0,'','eztext'),(640,'eng-GB',1,192,190,'',0,0,0,0,'','ezboolean'),(151,'eng-GB',54,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(634,'eng-GB',1,191,188,'',0,0,0,0,'','ezstring'),(635,'eng-GB',1,191,189,'',0,0,0,0,'','eztext'),(636,'eng-GB',1,191,190,'',0,0,0,0,'','ezboolean'),(637,'eng-GB',1,191,194,'',0,0,0,0,'','ezsubtreesubscription'),(638,'eng-GB',1,192,188,'',0,0,0,0,'','ezstring'),(609,'eng-GB',1,184,188,'',0,0,0,0,'','ezstring'),(610,'eng-GB',1,184,189,'',0,0,0,0,'','eztext'),(611,'eng-GB',1,184,190,'',0,0,0,0,'','ezboolean'),(612,'eng-GB',1,184,194,'',0,0,0,0,'','ezsubtreesubscription'),(613,'eng-GB',1,185,188,'',0,0,0,0,'','ezstring'),(614,'eng-GB',1,185,189,'',0,0,0,0,'','eztext'),(615,'eng-GB',1,185,190,'',0,0,0,0,'','ezboolean'),(616,'eng-GB',1,185,194,'',0,0,0,0,'','ezsubtreesubscription'),(617,'eng-GB',1,186,188,'',0,0,0,0,'','ezstring'),(618,'eng-GB',1,186,189,'',0,0,0,0,'','eztext'),(619,'eng-GB',1,186,190,'',0,0,0,0,'','ezboolean'),(620,'eng-GB',1,186,194,'',0,0,0,0,'','ezsubtreesubscription'),(829,'eng-GB',2,231,190,'',0,0,0,0,'','ezboolean'),(603,'eng-GB',1,182,190,'',0,0,0,0,'','ezboolean'),(604,'eng-GB',1,182,194,'',0,0,0,0,'','ezsubtreesubscription'),(605,'eng-GB',1,183,188,'',0,0,0,0,'','ezstring'),(606,'eng-GB',1,183,189,'',0,0,0,0,'','eztext'),(607,'eng-GB',1,183,190,'',0,0,0,0,'','ezboolean'),(608,'eng-GB',1,183,194,'',0,0,0,0,'','ezsubtreesubscription'),(602,'eng-GB',1,182,189,'',0,0,0,0,'','eztext'),(797,'eng-GB',2,220,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"dreamcars.\"\n         suffix=\"\"\n         basename=\"dreamcars\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/dreamcars/797-2-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/dreamcars/797-2-eng-GB/dreamcars.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069763923\">\n  <original attribute_id=\"797\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(827,'eng-GB',1,231,188,'How to Register a New User',0,0,0,0,'how to register a new user','ezstring'),(828,'eng-GB',1,231,189,'You can register a new user by pressing the \"Login\" button here.\r\nAs soon as you have filled in the information you have created an account and have the privileges that comes with that.\r\n',0,0,0,0,'','eztext'),(829,'eng-GB',1,231,190,'',0,0,0,0,'','ezboolean'),(730,'eng-GB',2,14,200,'',0,0,0,0,'','ezimage'),(731,'eng-GB',3,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"administrator_user.\"\n         suffix=\"\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB/administrator_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(729,'eng-GB',1,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(728,'eng-GB',1,10,200,'',0,0,0,0,'','ezimage'),(675,'eng-GB',1,200,189,'sefsefsf\nsf\nsf',0,0,0,0,'','eztext'),(153,'eng-GB',57,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(782,'eng-GB',54,56,202,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(151,'eng-GB',52,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(601,'eng-GB',1,182,188,'',0,0,0,0,'','ezstring'),(716,'eng-GB',1,10,199,'',0,0,0,0,'','eztext'),(717,'eng-GB',1,14,199,'',0,0,0,0,'','eztext'),(718,'eng-GB',2,14,199,'',0,0,0,0,'','eztext'),(719,'eng-GB',3,14,199,'developer... ;)',0,0,0,0,'','eztext'),(692,'eng-GB',1,10,197,'',0,0,0,0,'','ezstring'),(693,'eng-GB',1,14,197,'',0,0,0,0,'','ezstring'),(694,'eng-GB',2,14,197,'',0,0,0,0,'','ezstring'),(695,'eng-GB',3,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(152,'eng-GB',54,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"152\"\n            attribute_version=\"53\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         timestamp=\"\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(704,'eng-GB',1,10,198,'',0,0,0,0,'','ezstring'),(705,'eng-GB',1,14,198,'',0,0,0,0,'','ezstring'),(706,'eng-GB',2,14,198,'',0,0,0,0,'','ezstring'),(707,'eng-GB',3,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(818,'eng-GB',1,227,189,'It is Odd Grenland from Skien and Norway:-) Give the team five years and they will rule. They have many good players and are all very young.',0,0,0,0,'','eztext'),(819,'eng-GB',1,227,190,'',0,0,0,0,'','ezboolean'),(820,'eng-GB',1,227,194,'',0,0,0,0,'','ezsubtreesubscription'),(817,'eng-GB',1,227,188,'What is the best football team in Europe?',0,0,0,0,'what is the best football team in europe?','ezstring'),(816,'eng-GB',1,226,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"sport_forum.\"\n         suffix=\"\"\n         basename=\"sport_forum\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/sport_forum/816-1-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/sport_forum/816-1-eng-GB/sport_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069766768\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(815,'eng-GB',1,226,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Everybody has an opinion about sport. I can&apos;t live without sports and can not understand anyone who can while you hate everything about it.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(814,'eng-GB',1,226,186,'Sport forum',0,0,0,0,'sport forum','ezstring'),(1,'eng-GB',7,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',7,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table class=\"frontpage\"\n           border=\"1\"\n           width=\"100%\">\n      <tr>\n        <th>\n          <paragraph>Latest discussions in music</paragraph>\n        </th>\n        <th>\n          <paragraph>Latest discussions in sports</paragraph>\n        </th>\n      </tr>\n      <tr>\n        <td>\n          <paragraph>\n            <object id=\"141\" />\n          </paragraph>\n        </td>\n        <td>\n          <paragraph>\n            <object id=\"142\" />\n          </paragraph>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(446,'eng-GB',1,138,4,'Forum',0,0,0,0,'forum','ezstring'),(447,'eng-GB',1,138,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here you will find different discussion forums.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',52,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"50\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-52-eng-GB/forum_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',52,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(154,'eng-GB',52,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',52,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',52,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(779,'eng-GB',46,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(150,'eng-GB',59,56,157,'Forum',0,0,0,0,'','ezinisetting'),(778,'eng-GB',45,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(151,'eng-GB',53,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(782,'eng-GB',57,56,202,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(152,'eng-GB',53,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.jpg\"\n         suffix=\"jpg\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB/forum.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"52\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB/forum_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB/forum_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-53-eng-GB/forum_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069244503\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(600,'eng-GB',1,181,194,'',0,0,0,0,'','ezsubtreesubscription'),(591,'eng-GB',1,179,190,'',0,0,0,0,'','ezboolean'),(592,'eng-GB',1,179,194,'',0,0,0,0,'','ezsubtreesubscription'),(593,'eng-GB',1,180,188,'',0,0,0,0,'','ezstring'),(594,'eng-GB',1,180,189,'',0,0,0,0,'','eztext'),(595,'eng-GB',1,180,190,'',0,0,0,0,'','ezboolean'),(596,'eng-GB',1,180,194,'',0,0,0,0,'','ezsubtreesubscription'),(597,'eng-GB',1,181,188,'',0,0,0,0,'','ezstring'),(598,'eng-GB',1,181,189,'',0,0,0,0,'','eztext'),(599,'eng-GB',1,181,190,'',0,0,0,0,'','ezboolean'),(573,'eng-GB',1,175,188,'',0,0,0,0,'','ezstring'),(574,'eng-GB',1,175,189,'',0,0,0,0,'','eztext'),(575,'eng-GB',1,175,190,'',0,0,0,0,'','ezboolean'),(576,'eng-GB',1,175,194,'',0,0,0,0,'','ezsubtreesubscription'),(577,'eng-GB',1,176,188,'',0,0,0,0,'','ezstring'),(578,'eng-GB',1,176,189,'',0,0,0,0,'','eztext'),(579,'eng-GB',1,176,190,'',0,0,0,0,'','ezboolean'),(580,'eng-GB',1,176,194,'',0,0,0,0,'','ezsubtreesubscription'),(581,'eng-GB',1,177,188,'',0,0,0,0,'','ezstring'),(582,'eng-GB',1,177,189,'',0,0,0,0,'','eztext'),(583,'eng-GB',1,177,190,'',0,0,0,0,'','ezboolean'),(584,'eng-GB',1,177,194,'',0,0,0,0,'','ezsubtreesubscription'),(585,'eng-GB',1,178,188,'',0,0,0,0,'','ezstring'),(586,'eng-GB',1,178,189,'',0,0,0,0,'','eztext'),(587,'eng-GB',1,178,190,'',0,0,0,0,'','ezboolean'),(588,'eng-GB',1,178,194,'',0,0,0,0,'','ezsubtreesubscription'),(589,'eng-GB',1,179,188,'',0,0,0,0,'','ezstring'),(590,'eng-GB',1,179,189,'',0,0,0,0,'','eztext'),(561,'eng-GB',1,172,188,'',0,0,0,0,'','ezstring'),(562,'eng-GB',1,172,189,'',0,0,0,0,'','eztext'),(563,'eng-GB',1,172,190,'',0,0,0,0,'','ezboolean'),(564,'eng-GB',1,172,194,'',0,0,0,0,'','ezsubtreesubscription'),(565,'eng-GB',1,173,188,'',0,0,0,0,'','ezstring'),(566,'eng-GB',1,173,189,'',0,0,0,0,'','eztext'),(567,'eng-GB',1,173,190,'',0,0,0,0,'','ezboolean'),(568,'eng-GB',1,173,194,'',0,0,0,0,'','ezsubtreesubscription'),(569,'eng-GB',1,174,188,'',0,0,0,0,'','ezstring'),(570,'eng-GB',1,174,189,'',0,0,0,0,'','eztext'),(571,'eng-GB',1,174,190,'',0,0,0,0,'','ezboolean'),(572,'eng-GB',1,174,194,'',0,0,0,0,'','ezsubtreesubscription'),(560,'eng-GB',1,171,194,'',0,0,0,0,'','ezsubtreesubscription'),(559,'eng-GB',1,171,190,'',0,0,0,0,'','ezboolean'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(437,'eng-GB',57,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',57,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',54,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(437,'eng-GB',54,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',54,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(154,'eng-GB',54,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(154,'eng-GB',57,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',58,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',58,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(437,'eng-GB',45,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',59,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB/forum.gif\"\n         original_filename=\"forum.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069841319\">\n  <original attribute_id=\"152\"\n            attribute_version=\"58\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069841321\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069841321\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB/forum_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069843348\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(782,'eng-GB',58,56,202,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(153,'eng-GB',58,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(154,'eng-GB',58,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',45,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(828,'eng-GB',2,231,189,'You can register a new user by pressing the \"Login\" button here.\r\nAs soon as you have filled in the information you have created an account and have the privileges that comes with that.\r\n',0,0,0,0,'','eztext'),(834,'eng-GB',2,232,194,'',0,0,0,0,'','ezsubtreesubscription'),(827,'eng-GB',2,231,188,'How to Register a New User',0,0,0,0,'how to register a new user','ezstring'),(833,'eng-GB',2,232,190,'',1,0,0,1,'','ezboolean'),(150,'eng-GB',46,56,157,'Forum',0,0,0,0,'','ezinisetting'),(150,'eng-GB',50,56,157,'Forum',0,0,0,0,'','ezinisetting'),(150,'eng-GB',52,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',59,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(671,'eng-GB',45,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(674,'eng-GB',1,200,188,'test',0,0,0,0,'test','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(641,'eng-GB',1,192,194,'',0,0,0,0,'','ezsubtreesubscription'),(642,'eng-GB',1,193,188,'',0,0,0,0,'','ezstring'),(643,'eng-GB',1,193,189,'',0,0,0,0,'','eztext'),(644,'eng-GB',1,193,190,'',0,0,0,0,'','ezboolean'),(645,'eng-GB',1,193,194,'',0,0,0,0,'','ezsubtreesubscription'),(646,'eng-GB',1,194,188,'',0,0,0,0,'','ezstring'),(647,'eng-GB',1,194,189,'',0,0,0,0,'','eztext'),(648,'eng-GB',1,194,190,'',0,0,0,0,'','ezboolean'),(649,'eng-GB',1,194,194,'',0,0,0,0,'','ezsubtreesubscription'),(830,'eng-GB',2,231,194,'',1,0,0,0,'','ezsubtreesubscription'),(153,'eng-GB',45,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(154,'eng-GB',45,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(671,'eng-GB',53,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(154,'eng-GB',46,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',46,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',46,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(150,'eng-GB',45,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',46,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',46,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB/forum.gif\"\n         original_filename=\"phpCfM6Z4_600x600_68578.gif\"\n         mime_type=\"original\"\n         width=\"114\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"45\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-46-eng-GB/forum_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-61922323\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',46,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(152,'eng-GB',45,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB/forum.gif\"\n         original_filename=\"phpCfM6Z4_600x600_68578.gif\"\n         mime_type=\"original\"\n         width=\"114\"\n         height=\"40\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"44\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-45-eng-GB/forum_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"114\"\n         height=\"40\"\n         alias_key=\"-61922323\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/forum/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(1,'eng-GB',8,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',8,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table class=\"frontpage\">\n      <tr>\n        <th>\n          <paragraph>Latest discussions in music</paragraph>\n        </th>\n        <th>\n          <paragraph>Latest discussions in sports</paragraph>\n        </th>\n      </tr>\n      <tr>\n        <td>\n          <paragraph>\n            <object id=\"141\" />\n          </paragraph>\n        </td>\n        <td>\n          <paragraph>\n            <object id=\"142\" />\n          </paragraph>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <paragraph>\n    <table class=\"frontpage\">\n      <tr>\n        <th>\n          <paragraph>Latest news</paragraph>\n        </th>\n      </tr>\n      <tr>\n        <td>\n          <paragraph>\n            <object id=\"49\" />\n          </paragraph>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(795,'eng-GB',2,220,186,'Dreamcars',0,0,0,0,'dreamcars','ezstring'),(782,'eng-GB',59,56,202,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(454,'eng-GB',1,141,186,'Music discussion',0,0,0,0,'music discussion','ezstring'),(455,'eng-GB',1,141,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss music here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(671,'eng-GB',59,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(437,'eng-GB',59,56,180,'nospam@ez.no',0,0,0,0,'','ezinisetting'),(154,'eng-GB',59,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(780,'eng-GB',50,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(781,'eng-GB',52,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(782,'eng-GB',53,56,202,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(150,'eng-GB',54,56,157,'Forum',0,0,0,0,'','ezinisetting'),(446,'eng-GB',2,138,4,'Discussions',0,0,0,0,'discussions','ezstring'),(447,'eng-GB',2,138,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Here you will find different discussion forums.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',4,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',4,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',4,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',4,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',5,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',5,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',5,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',5,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"731\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"administrator_user_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(757,'eng-GB',1,141,201,'',0,0,0,0,'','ezimage'),(454,'eng-GB',2,141,186,'Music discussion',0,0,0,0,'music discussion','ezstring'),(455,'eng-GB',2,141,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss music here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(757,'eng-GB',2,141,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"music_discussion.gif\"\n         suffix=\"gif\"\n         basename=\"music_discussion\"\n         dirpath=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB\"\n         url=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB/music_discussion.gif\"\n         original_filename=\"note.gif\"\n         mime_type=\"original\"\n         width=\"80\"\n         height=\"80\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"music_discussion_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB\"\n         url=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB/music_discussion_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"music_discussion_small.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB\"\n         url=\"var/forum/storage/images/discussions/music_discussion/757-2-eng-GB/music_discussion_small.gif\"\n         mime_type=\"image/gif\"\n         width=\"100\"\n         height=\"100\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(759,'eng-GB',1,211,4,'Folder',0,0,0,0,'folder','ezstring'),(760,'eng-GB',1,211,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Diverse temaer</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(759,'eng-GB',2,211,4,'Forum main group',0,0,0,0,'forum main group','ezstring'),(760,'eng-GB',2,211,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>No description. This text may not be shown?</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(454,'eng-GB',3,141,186,'Music discussion',0,0,0,0,'music discussion','ezstring'),(455,'eng-GB',3,141,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss music here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(757,'eng-GB',3,141,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"music_discussion.gif\"\n         suffix=\"gif\"\n         basename=\"music_discussion\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB/music_discussion.gif\"\n         original_filename=\"note.gif\"\n         mime_type=\"original\"\n         width=\"80\"\n         height=\"80\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"757\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"music_discussion_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB/music_discussion_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"music_discussion_small.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB/music_discussion_small.gif\"\n         mime_type=\"image/gif\"\n         width=\"100\"\n         height=\"100\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"music_discussion_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-3-eng-GB/music_discussion_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"200\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(825,'eng-GB',1,230,4,'Forum rules',0,0,0,0,'forum rules','ezstring'),(826,'eng-GB',1,230,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>There are certain rules you must follow in this forum. You must follow these rules or you can be removed.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(824,'eng-GB',1,229,193,'Gulset beat Odd a couple of years ago in the cup and since then they haven\'t played. So Gulset is better than Odd. In fact Gulset is the best team in the world.',0,0,0,0,'','eztext'),(823,'eng-GB',1,229,191,'Gulset are better than Odd',0,0,0,0,'gulset are better than odd','ezstring'),(822,'eng-GB',1,228,193,'What is Odd Grenland? I have never heard of them. But maybe I will in five years then. Tranmere is the kings of Europe.',0,0,0,0,'','eztext'),(821,'eng-GB',1,228,191,'Who is Odd?',0,0,0,0,'who is odd?','ezstring'),(437,'eng-GB',53,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(154,'eng-GB',53,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(153,'eng-GB',53,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(153,'eng-GB',50,56,160,'forum_red',0,0,0,0,'forum_red','ezpackage'),(154,'eng-GB',50,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',50,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',50,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',57,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB/forum.gif\"\n         original_filename=\"forum.gif\"\n         mime_type=\"original\"\n         width=\"165\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069840897\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069840898\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069840899\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"forum_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB/forum_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"165\"\n         height=\"48\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069840919\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',57,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',57,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',56,56,157,'Forum',0,0,0,0,'','ezinisetting'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"forum.gif\"\n         suffix=\"gif\"\n         basename=\"forum\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum.gif\"\n         original_filename=\"forum.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069687512\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"forum_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069687516\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"forum_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB\"\n         url=\"var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069687516\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',56,56,160,'forum_blue',0,0,0,0,'forum_blue','ezpackage'),(154,'eng-GB',56,56,161,'forum_package',0,0,0,0,'forum_package','ezstring'),(437,'eng-GB',56,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',56,56,196,'myuser.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(782,'eng-GB',56,56,202,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(522,'eng-GB',2,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',2,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>These guidelines also apply to any news groups, mailing lists or other web forums you frequent. If you follow the guidelines you will find that your problems get more attention from people and that your problems might actually get solved. </line>\n    <line>How to Log In</line>\n  </paragraph>\n  <paragraph>You can log in by entering your user name and password here </paragraph>\n  <paragraph>\n    <line>How to Register a New User</line>\n    <line>You can register a new user by pressing the &quot;Sign up&quot; button here.</line>\n    <line>As soon as you have filled in the information you have created an account and have the privileges that comes with that.</line>\n  </paragraph>\n  <paragraph>\n    <line>Choose the correct forum</line>\n    <line>Use a few minutes to consider which forum you should post to. Posting it to a &quot;General&quot; forum is not as effective as posting to the &quot;Install&quot; forum if you question is about install-related questions. Don&apos;t post in several different forums. If your question belongs in the &quot;Install&quot; forum, posting it in &quot;General&quot; or &quot;Developer&quot; aren&apos;t very helpful. </line>\n    <line>Consider this: If you post to the wrong forum the chances for someone actually answering it is lower than if you post it in the correct forum. Furthermore your off-topic post will just make it harder for those posting correctly to be noticed among those who post in the wrong forums. </line>\n  </paragraph>\n  <paragraph>\n    <line>Search the forums</line>\n    <line>Before you ask your question try to search the archives of the forum. You&apos;d be surprised to notice how often your exact problem has been solved. You should also read any and all documentation available and search them for relevant info. After all, if it is allready written somewhere, why should someone have to rewrite it into a forum to answer your question? </line>\n  </paragraph>\n  <paragraph>\n    <line>Choose a clear title</line>\n    <line>Choosing a clear and describing title is important, it is the first information someone considering answering your question will read. Saying &quot;I need help&quot;, &quot;Newbie question&quot;, or &quot;Installation question&quot; doesn&apos;t help anyone who reads the title. Everyone needs help, and if you&apos;re posting in the installation forum, don&apos;t you think that people know it&apos;s about installation? </line>\n    <line>Also your title will let a reader decide if the question you are posting is something they can answer. If they from your title can see that the question is outside their knowledge, they can use their time to answer someone whom they might actually be able to help. </line>\n  </paragraph>\n  <paragraph>\n    <line>Describe your goal</line>\n    <line>Sometimes a clear idea of what you&apos;re trying to achieve is relevant to the question asked. Use a few sentences describing what you&apos;re trying to do. Be brief and precise. </line>\n  </paragraph>\n  <paragraph>\n    <line>Describe your problem</line>\n    <line>&quot;Installation don&apos;t work&quot; isn&apos;t very descriptive. More information is needed. How far have you got? What does work? What testing have you done? What system are you running on? You should take at least five minutes to describe your problem. If you get any error messages in your logs, always paste them into you posting. Look in your apache error log, in your php error log and any error messages encountered in your browser. </line>\n    <line>The extra minutes you use to get this information and describing your problem clearly is well worth the time, since it might help someone answer your question. </line>\n    <line>In fact, the few extra minutes you use on your question is well spent after using several hours on your problem. Who knows, you might even see the solution why you take the time to rethink your problem. </line>\n    <line>Remember to include the name of the program, module or plug-in you have a problem with, also remember their version numbers. </line>\n  </paragraph>\n  <paragraph>\n    <line>Don&apos;t use l33t speak</line>\n    <line>U must not use l33t speak or b short. Use plain English. If English isn&apos;t your mother tongue, try to rewrite any sentences where you stumble across words you don&apos;t remember or don&apos;t know. </line>\n    <line>Use words you know and keep the language simple. In fact, you might get help from someone who don&apos;t know English well either, but who is the best expert on the subject you need help in. </line>\n  </paragraph>\n  <paragraph>\n    <line>Reread before you post</line>\n    <line>Use a few minutes reading your posting. Try to see if you have left out important information, or if there are extra information you can supply. </line>\n  </paragraph>\n  <paragraph>\n    <line>Don&apos;t expect an answer right away</line>\n    <line>The forums are supported by the community. People answer at their leisure and level of knowledge. You will also find answers from the crew and together with the community this will be a very valuable part of the documentation. </line>\n  </paragraph>\n  <paragraph>Do not use improper language</paragraph>\n  <paragraph>A post that contains improper language like cursing and swearing will be removed from the forums. It will not be answered in any way and will be removed without warning by the administrators of the forum. </paragraph>\n  <paragraph>Otherwise normal behavior is expected.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',2,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-2-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-2-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(522,'eng-GB',3,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',3,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>These guidelines also apply to any news groups, mailing lists or other web forums you frequent. If you follow the guidelines you will find that your problems get more attention from people and that your problems might actually get solved. </line>\n    <line>How to Log In</line>\n  </paragraph>\n  <paragraph>You can log in by entering your user name and password here</paragraph>\n  <paragraph>\n    <line>\n      <strong>How to Register a New User</strong>\n    </line>\n    <line>You can register a new user by pressing the &quot;Sign up&quot; button here.</line>\n    <line>As soon as you have filled in the information you have created an account and have the privileges that comes with that.</line>\n  </paragraph>\n  <paragraph>\n    <line>Choose the correct forum</line>\n    <line>Use a few minutes to consider which forum you should post to. Posting it to a &quot;General&quot; forum is not as effective as posting to the &quot;Install&quot; forum if you question is about install-related questions. Don&apos;t post in several different forums. If your question belongs in the &quot;Install&quot; forum, posting it in &quot;General&quot; or &quot;Developer&quot; aren&apos;t very helpful. </line>\n    <line>Consider this: If you post to the wrong forum the chances for someone actually answering it is lower than if you post it in the correct forum. Furthermore your off-topic post will just make it harder for those posting correctly to be noticed among those who post in the wrong forums.</line>\n  </paragraph>\n  <paragraph>\n    <line>Search the forums</line>\n    <line>Before you ask your question try to search the archives of the forum. You&apos;d be surprised to notice how often your exact problem has been solved. You should also read any and all documentation available and search them for relevant info. After all, if it is allready written somewhere, why should someone have to rewrite it into a forum to answer your question?</line>\n  </paragraph>\n  <paragraph>\n    <line>Choose a clear title</line>\n    <line>Choosing a clear and describing title is important, it is the first information someone considering answering your question will read. Saying &quot;I need help&quot;, &quot;Newbie question&quot;, or &quot;Installation question&quot; doesn&apos;t help anyone who reads the title. Everyone needs help, and if you&apos;re posting in the installation forum, don&apos;t you think that people know it&apos;s about installation? </line>\n    <line>Also your title will let a reader decide if the question you are posting is something they can answer. If they from your title can see that the question is outside their knowledge, they can use their time to answer someone whom they might actually be able to help.</line>\n  </paragraph>\n  <paragraph>\n    <line>Describe your goal</line>\n    <line>Sometimes a clear idea of what you&apos;re trying to achieve is relevant to the question asked. Use a few sentences describing what you&apos;re trying to do. Be brief and precise.</line>\n  </paragraph>\n  <paragraph>\n    <line>Describe your problem</line>\n    <line>&quot;Installation don&apos;t work&quot; isn&apos;t very descriptive. More information is needed. How far have you got? What does work? What testing have you done? What system are you running on? You should take at least five minutes to describe your problem. If you get any error messages in your logs, always paste them into you posting. Look in your apache error log, in your php error log and any error messages encountered in your browser. </line>\n    <line>The extra minutes you use to get this information and describing your problem clearly is well worth the time, since it might help someone answer your question. </line>\n    <line>In fact, the few extra minutes you use on your question is well spent after using several hours on your problem. Who knows, you might even see the solution why you take the time to rethink your problem. </line>\n    <line>Remember to include the name of the program, module or plug-in you have a problem with, also remember their version numbers.</line>\n  </paragraph>\n  <paragraph>\n    <line>Don&apos;t use l33t speak</line>\n    <line>U must not use l33t speak or b short. Use plain English. If English isn&apos;t your mother tongue, try to rewrite any sentences where you stumble across words you don&apos;t remember or don&apos;t know. </line>\n    <line>Use words you know and keep the language simple. In fact, you might get help from someone who don&apos;t know English well either, but who is the best expert on the subject you need help in.</line>\n  </paragraph>\n  <paragraph>\n    <line>Reread before you post</line>\n    <line>Use a few minutes reading your posting. Try to see if you have left out important information, or if there are extra information you can supply.</line>\n  </paragraph>\n  <paragraph>\n    <line>Don&apos;t expect an answer right away</line>\n    <line>The forums are supported by the community. People answer at their leisure and level of knowledge. You will also find answers from the crew and together with the community this will be a very valuable part of the documentation.</line>\n  </paragraph>\n  <paragraph>Do not use improper language</paragraph>\n  <paragraph>A post that contains improper language like cursing and swearing will be removed from the forums. It will not be answered in any way and will be removed without warning by the administrators of the forum.</paragraph>\n  <paragraph>Otherwise normal behavior is expected.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',3,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-3-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-3-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(522,'eng-GB',4,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',4,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>These guidelines also apply to any news groups, mailing lists or other web forums you frequent. If you follow the guidelines you will find that your problems get more attention from people and that your problems might actually get solved. </line>\n    <line>How to Log In</line>\n  </paragraph>\n  <paragraph>You can log in by entering your user name and password here</paragraph>\n  <paragraph>\n    <line>\n      <strong>How to Register a New User</strong>\n    </line>\n    <line>You can register a new user by pressing the &quot;Sign up&quot; button here.</line>\n    <line>As soon as you have filled in the information you have created an account and have the privileges that comes with that.</line>\n  </paragraph>\n  <paragraph>\n    <line>\n      <strong>Choose the correct forum</strong>\n    </line>\n    <line>Use a few minutes to consider which forum you should post to. Posting it to a &quot;General&quot; forum is not as effective as posting to the &quot;Install&quot; forum if you question is about install-related questions. Don&apos;t post in several different forums. If your question belongs in the &quot;Install&quot; forum, posting it in &quot;General&quot; or &quot;Developer&quot; aren&apos;t very helpful. </line>\n    <line>Consider this: If you post to the wrong forum the chances for someone actually answering it is lower than if you post it in the correct forum. Furthermore your off-topic post will just make it harder for those posting correctly to be noticed among those who post in the wrong forums.</line>\n  </paragraph>\n  <paragraph>\n    <line>\n      <strong>Search the forums</strong>\n    </line>\n    <line>Before you ask your question try to search the archives of the forum. You&apos;d be surprised to notice how often your exact problem has been solved. You should also read any and all documentation available and search them for relevant info. After all, if it is allready written somewhere, why should someone have to rewrite it into a forum to answer your question?</line>\n  </paragraph>\n  <paragraph>\n    <line>\n      <strong>Choose a clear title</strong>\n    </line>\n    <line>Choosing a clear and describing title is important, it is the first information someone considering answering your question will read. Saying &quot;I need help&quot;, &quot;Newbie question&quot;, or &quot;Installation question&quot; doesn&apos;t help anyone who reads the title. Everyone needs help, and if you&apos;re posting in the installation forum, don&apos;t you think that people know it&apos;s about installation? </line>\n    <line>Also your title will let a reader decide if the question you are posting is something they can answer. If they from your title can see that the question is outside their knowledge, they can use their time to answer someone whom they might actually be able to help.</line>\n  </paragraph>\n  <paragraph>\n    <line>\n      <strong>Describe your goal</strong>\n    </line>\n    <line>Sometimes a clear idea of what you&apos;re trying to achieve is relevant to the question asked. Use a few sentences describing what you&apos;re trying to do. Be brief and precise.</line>\n  </paragraph>\n  <paragraph>\n    <line>\n      <strong>Describe your problem</strong>\n    </line>\n    <line>&quot;Installation don&apos;t work&quot; isn&apos;t very descriptive. More information is needed. How far have you got? What does work? What testing have you done? What system are you running on? You should take at least five minutes to describe your problem. If you get any error messages in your logs, always paste them into you posting. Look in your apache error log, in your php error log and any error messages encountered in your browser. </line>\n    <line>The extra minutes you use to get this information and describing your problem clearly is well worth the time, since it might help someone answer your question. In fact, the few extra minutes you use on your question is well spent after using several hours on your problem. Who knows, you might even see the solution why you take the time to rethink your problem. </line>\n    <line>Remember to include the name of the program, module or plug-in you have a problem with, also remember their version numbers.</line>\n  </paragraph>\n  <paragraph>\n    <line>\n      <strong>Reread before you post</strong>\n    </line>\n    <line>Use a few minutes reading your posting. Try to see if you have left out important information, or if there are extra information you can supply.</line>\n  </paragraph>\n  <paragraph>\n    <line>\n      <strong>Don&apos;t expect an answer right away</strong>\n    </line>\n    <line>The forums are supported by the community. People answer at their leisure and level of knowledge. You will also find answers from the crew and together with the community this will be a very valuable part of the documentation.</line>\n  </paragraph>\n  <paragraph>\n    <line>\n      <strong>Do not use improper language</strong>\n    </line>\n    <line>A post that contains improper language like cursing and swearing will be removed from the forums. It will not be answered in any way and will be removed without warning by the administrators of the forum.</line>\n  </paragraph>\n  <paragraph>Otherwise normal behavior is expected.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(783,'eng-GB',1,218,1,'Choose the correct forum',0,0,0,0,'choose the correct forum','ezstring'),(784,'eng-GB',1,218,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Use a few minutes to consider which forum you should post to. Posting it to a &quot;General&quot; forum is not as effective as posting to the &quot;Install&quot; forum if you question is about install-related questions. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(785,'eng-GB',1,218,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Don&apos;t post in several different forums. If your question belongs in the &quot;Install&quot; forum, posting it in &quot;General&quot; or &quot;Developer&quot; aren&apos;t very helpful. </line>\n    <line>Consider this: If you post to the wrong forum the chances for someone actually answering it is lower than if you post it in the correct forum. Furthermore your off-topic post will just make it harder for those posting correctly to be noticed among those who post in the wrong forums.</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(786,'eng-GB',1,218,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"choose_the_correct_forum.\"\n         suffix=\"\"\n         basename=\"choose_the_correct_forum\"\n         dirpath=\"var/forum/storage/images/news/choose_the_correct_forum/786-1-eng-GB\"\n         url=\"var/forum/storage/images/news/choose_the_correct_forum/786-1-eng-GB/choose_the_correct_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069763577\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(524,'eng-GB',4,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-4-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-4-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"524\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(787,'eng-GB',1,218,123,'',0,0,0,0,'','ezboolean'),(788,'eng-GB',1,218,177,'',0,0,0,0,'','ezinteger'),(789,'eng-GB',1,219,1,'Latest forum: Dreamcars',0,0,0,0,'latest forum: dreamcars','ezstring'),(790,'eng-GB',1,219,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>The latest forum to be added is about dreamcars. What&apos;s your favorite dreamcar? </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(791,'eng-GB',1,219,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Many of us always dream about a red Ferrari or an angry Diablo. Let others know about the car of your dreams. Perhaps you have tested it or even own your dreamcar. </paragraph>\n  <paragraph>Perhaps your dreamcar is a Volvo C70, a Jaguar, a 79 Mustang or a Beetle. We all have different choices and dreams. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(792,'eng-GB',1,219,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"latest_forum_dreamcars.\"\n         suffix=\"\"\n         basename=\"latest_forum_dreamcars\"\n         dirpath=\"var/forum/storage/images/news/latest_forum_dreamcars/792-1-eng-GB\"\n         url=\"var/forum/storage/images/news/latest_forum_dreamcars/792-1-eng-GB/latest_forum_dreamcars.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069763611\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(793,'eng-GB',1,219,123,'',0,0,0,0,'','ezboolean'),(794,'eng-GB',1,219,177,'',0,0,0,0,'','ezinteger'),(795,'eng-GB',1,220,186,'Dreamcars',0,0,0,0,'dreamcars','ezstring'),(796,'eng-GB',1,220,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>What is your dreamcar? Ferrari, Diablo or Beetle!</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(797,'eng-GB',1,220,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"dreamcars.\"\n         suffix=\"\"\n         basename=\"dreamcars\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/dreamcars/797-1-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/dreamcars/797-1-eng-GB/dreamcars.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069763923\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(798,'eng-GB',1,221,188,'Koenigsegg is the master',0,0,0,0,'koenigsegg is the master','ezstring'),(799,'eng-GB',1,221,189,'Just imagine to have one if these parked in your garrage. It must be the most awsome car ever.',0,0,0,0,'','eztext'),(800,'eng-GB',1,221,190,'',0,0,0,0,'','ezboolean'),(801,'eng-GB',1,221,194,'',1,0,0,0,'','ezsubtreesubscription'),(28,'eng-GB',6,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',6,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',6,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',6,14,197,'Site boss',0,0,0,0,'site boss','ezstring'),(707,'eng-GB',6,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',6,14,199,'My sig',0,0,0,0,'','eztext'),(731,'eng-GB',6,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"administrator_user.\"\n         suffix=\"\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-6-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-6-eng-GB/administrator_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(802,'eng-GB',1,222,188,'Can\'t leave Ferrari F40 out of it',0,0,0,0,'can\'t leave ferrari f40 out of it','ezstring'),(803,'eng-GB',1,222,189,'This IS the ulitmate dreamcar',0,0,0,0,'','eztext'),(804,'eng-GB',1,222,190,'',0,0,0,0,'','ezboolean'),(805,'eng-GB',1,222,194,'',0,0,0,0,'','ezsubtreesubscription'),(806,'eng-GB',1,223,191,'Königsegg is the best',0,0,0,0,'königsegg is the best','ezstring'),(807,'eng-GB',1,223,193,'I just have to agree again. There are no car even close to this. ',0,0,0,0,'','eztext'),(806,'eng-GB',2,223,191,'Königsegg is the best',0,0,0,0,'königsegg is the best','ezstring'),(807,'eng-GB',2,223,193,'I just have to say it again. There are no car even close to this. ',0,0,0,0,'','eztext'),(28,'eng-GB',7,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',7,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',7,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',7,14,197,'Site boss',0,0,0,0,'site boss','ezstring'),(707,'eng-GB',7,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',7,14,199,'Tim Tim',0,0,0,0,'','eztext'),(731,'eng-GB',7,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"administrator_user.\"\n         suffix=\"\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-7-eng-GB/administrator_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"731\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(808,'eng-GB',1,224,188,'What is wrong with pop?',0,0,0,0,'what is wrong with pop?','ezstring'),(809,'eng-GB',1,224,189,'Pop is no longer misic in my eyes. What does Spears and Madonna ad to music history? Nothing at all I say. Nothing :-(',0,0,0,0,'','eztext'),(810,'eng-GB',1,224,190,'',0,0,0,0,'','ezboolean'),(811,'eng-GB',1,224,194,'',0,0,0,0,'','ezsubtreesubscription'),(812,'eng-GB',1,225,191,'Madonna is one of the greats',0,0,0,0,'madonna is one of the greats','ezstring'),(813,'eng-GB',1,225,193,'And it\'s no doubt about that. Her list of hits are proof enough. ',0,0,0,0,'','eztext'),(454,'eng-GB',4,141,186,'Music discussion',0,0,0,0,'music discussion','ezstring'),(455,'eng-GB',4,141,187,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Discuss music here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(757,'eng-GB',4,141,201,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"music_discussion.\"\n         suffix=\"\"\n         basename=\"music_discussion\"\n         dirpath=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-4-eng-GB\"\n         url=\"var/forum/storage/images/discussions/forum_main_group/music_discussion/757-4-eng-GB/music_discussion.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage');
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
INSERT INTO ezcontentobject_link VALUES (1,1,4,49),(2,1,4,141),(4,1,5,49),(5,1,5,141),(7,1,6,49),(8,1,6,141),(10,1,7,49),(11,1,7,141),(13,1,8,49),(14,1,8,141);
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(165,'',1,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(169,'test',1,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(138,'Forum',1,'eng-GB','eng-GB'),(168,'',1,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(160,'News bulletin',1,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(226,'Sport forum',1,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(141,'Music discussion',1,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(138,'Discussions',2,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(171,'',1,'eng-GB','eng-GB'),(172,'',1,'eng-GB','eng-GB'),(173,'',1,'eng-GB','eng-GB'),(174,'',1,'eng-GB','eng-GB'),(175,'',1,'eng-GB','eng-GB'),(176,'',1,'eng-GB','eng-GB'),(177,'',1,'eng-GB','eng-GB'),(178,'',1,'eng-GB','eng-GB'),(179,'',1,'eng-GB','eng-GB'),(180,'',1,'eng-GB','eng-GB'),(181,'',1,'eng-GB','eng-GB'),(182,'',1,'eng-GB','eng-GB'),(183,'',1,'eng-GB','eng-GB'),(184,'',1,'eng-GB','eng-GB'),(185,'',1,'eng-GB','eng-GB'),(186,'New Forum topic',1,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(191,'',1,'eng-GB','eng-GB'),(192,'',1,'eng-GB','eng-GB'),(193,'',1,'eng-GB','eng-GB'),(194,'New Forum topic',1,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(200,'test',1,'eng-GB','eng-GB'),(201,'Re:test',1,'eng-GB','eng-GB'),(220,'Dreamcars',2,'eng-GB','eng-GB'),(56,'Forum',59,'eng-GB','eng-GB'),(56,'Forum',58,'eng-GB','eng-GB'),(231,'How to Register a New User',2,'eng-GB','eng-GB'),(56,'Forum',57,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(227,'What is the best football team in Europe?',1,'eng-GB','eng-GB'),(228,'Who is Odd?',1,'eng-GB','eng-GB'),(229,'Gulset are better than Odd',1,'eng-GB','eng-GB'),(230,'Forum rules',1,'eng-GB','eng-GB'),(231,'How to Register a New User',1,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(141,'Music discussion',2,'eng-GB','eng-GB'),(211,'Folder',1,'eng-GB','eng-GB'),(211,'Forum main group',2,'eng-GB','eng-GB'),(141,'Music discussion',3,'eng-GB','eng-GB'),(232,'Choose the correct forum',2,'eng-GB','eng-GB'),(232,'Choose the correct forum',1,'eng-GB','eng-GB'),(14,'Administrator User',6,'eng-GB','eng-GB'),(1,'Forum',6,'eng-GB','eng-GB'),(1,'Forum',7,'eng-GB','eng-GB'),(1,'Forum',8,'eng-GB','eng-GB'),(56,'Forum',50,'eng-GB','eng-GB'),(56,'Forum',52,'eng-GB','eng-GB'),(56,'Forum',53,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(217,'fsf',1,'eng-GB','eng-GB'),(56,'Forum',54,'eng-GB','eng-GB'),(56,'Forum',56,'eng-GB','eng-GB'),(161,'About this forum',2,'eng-GB','eng-GB'),(161,'About this forum',3,'eng-GB','eng-GB'),(161,'About this forum',4,'eng-GB','eng-GB'),(218,'Choose the correct forum',1,'eng-GB','eng-GB'),(219,'Latest forum: Dreamcars',1,'eng-GB','eng-GB'),(220,'Dreamcars',1,'eng-GB','eng-GB'),(221,'Koenigsegg is the master',1,'eng-GB','eng-GB'),(222,'Can\'t leave Ferrari F40 out of it',1,'eng-GB','eng-GB'),(223,'Königsegg is the best',1,'eng-GB','eng-GB'),(223,'Königsegg is the best',2,'eng-GB','eng-GB'),(14,'Administrator User',7,'eng-GB','eng-GB'),(224,'What is wrong with pop?',1,'eng-GB','eng-GB'),(225,'Madonna is one of the greats',1,'eng-GB','eng-GB'),(141,'Music discussion',4,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,8,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,7,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,9,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,10,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,1,1,2,'/1/2/50/',9,1,0,'news',50),(54,48,56,59,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/forum',54),(126,50,160,1,1,3,'/1/2/50/126/',9,1,0,'news/news_bulletin',126),(127,2,161,4,1,2,'/1/2/127/',9,1,0,'about_this_forum',127),(169,168,231,2,1,4,'/1/2/111/168/169/',9,1,0,'discussions/forum_rules/how_to_register_a_new_user',169),(111,2,138,2,1,2,'/1/2/111/',9,1,0,'discussions',111),(95,46,115,4,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,3,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(114,152,141,4,1,4,'/1/2/111/152/114/',9,1,0,'discussions/forum_main_group/music_discussion',114),(162,114,224,1,1,5,'/1/2/111/152/114/162/',9,1,0,'discussions/forum_main_group/music_discussion/what_is_wrong_with_pop',162),(168,111,230,1,1,3,'/1/2/111/168/',9,1,0,'discussions/forum_rules',168),(167,165,229,1,1,6,'/1/2/111/152/164/165/167/',9,1,0,'discussions/forum_main_group/sport_forum/what_is_the_best_football_team_in_europe/gulset_are_better_than_odd',167),(163,162,225,1,1,6,'/1/2/111/152/114/162/163/',1,1,0,'discussions/forum_main_group/music_discussion/what_is_wrong_with_pop/madonna_is_one_of_the_greats',163),(166,165,228,1,1,6,'/1/2/111/152/164/165/166/',9,1,0,'discussions/forum_main_group/sport_forum/what_is_the_best_football_team_in_europe/who_is_odd',166),(164,152,226,1,1,4,'/1/2/111/152/164/',9,1,0,'discussions/forum_main_group/sport_forum',164),(165,164,227,1,1,5,'/1/2/111/152/164/165/',9,1,0,'discussions/forum_main_group/sport_forum/what_is_the_best_football_team_in_europe',165),(170,168,232,2,1,4,'/1/2/111/168/170/',9,1,0,'discussions/forum_rules/choose_the_correct_forum',170),(152,111,211,2,1,3,'/1/2/111/152/',9,1,0,'discussions/forum_main_group',152),(156,50,218,1,1,3,'/1/2/50/156/',9,1,0,'news/choose_the_correct_forum',156),(157,50,219,1,1,3,'/1/2/50/157/',9,1,0,'news/latest_forum_dreamcars',157),(158,152,220,2,1,4,'/1/2/111/152/158/',9,1,0,'discussions/forum_main_group/dreamcars',158),(159,158,221,1,1,5,'/1/2/111/152/158/159/',9,1,0,'discussions/forum_main_group/dreamcars/koenigsegg_is_the_master',159),(160,158,222,1,1,5,'/1/2/111/152/158/160/',9,1,0,'discussions/forum_main_group/dreamcars/cant_leave_ferrari_f40_out_of_it',160),(161,159,223,2,1,6,'/1/2/111/152/158/159/161/',1,1,0,'discussions/forum_main_group/dreamcars/koenigsegg_is_the_master/knigsegg_is_the_best',161);
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
INSERT INTO ezcontentobject_version VALUES (810,1,14,6,1068735167,1068736490,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,3,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(480,45,14,1,1066388718,1066388815,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(832,218,14,1,1069763574,1069763601,1,0,0),(822,43,14,9,1068825480,1068825487,1,0,0),(490,49,14,1,1066398007,1066398020,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(741,175,149,1,1068108534,1068108624,0,0,0),(831,161,14,4,1069763471,1069763531,1,0,0),(767,56,14,46,1068113507,1068113546,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(734,168,149,1,1068048359,1068048594,0,0,0),(731,165,149,1,1068048190,1068048359,0,0,0),(724,160,14,1,1068047416,1068047455,1,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(818,56,14,52,1068822388,1068822406,3,0,0),(725,161,14,1,1068047518,1068047603,3,0,0),(717,56,14,45,1068043009,1068043048,3,0,0),(821,115,14,4,1068825440,1068825448,1,0,0),(740,174,149,1,1068050123,1068108534,0,0,0),(859,56,14,59,1069841661,1069841894,1,0,0),(830,161,14,3,1069763435,1069763457,3,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(816,56,14,50,1068821705,1068821744,3,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(739,173,149,1,1068050088,1068050123,0,0,0),(738,172,149,1,1068049706,1068050088,0,0,0),(735,169,149,1,1068048594,1068048622,0,0,0),(858,56,14,58,1069841286,1069841319,3,0,0),(696,138,14,1,1068036042,1068036060,3,0,0),(737,171,149,1,1068049618,1068049706,0,0,0),(819,56,14,53,1068825229,1068825245,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(811,1,14,7,1068736833,1068737860,3,1,0),(848,226,14,1,1069766765,1069766829,1,0,0),(823,45,14,10,1068825496,1068825502,1,0,0),(824,116,14,3,1068825510,1068825521,1,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(812,1,14,8,1068807642,1068810446,1,1,0),(700,141,14,1,1068036570,1068036586,3,0,0),(829,161,14,2,1069763210,1069763310,3,0,0),(828,56,14,56,1069687492,1069687511,3,0,0),(857,56,14,57,1069840841,1069840897,3,0,0),(712,138,14,2,1068041924,1068041936,1,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(742,176,149,1,1068108624,1068108805,0,0,0),(743,177,149,1,1068108805,1068108834,0,0,0),(744,178,149,1,1068108834,1068108898,0,0,0),(745,179,149,1,1068108898,1068109016,0,0,0),(746,180,149,1,1068109016,1068109220,0,0,0),(747,181,149,1,1068109220,1068109255,0,0,0),(748,182,149,1,1068109255,1068109498,0,0,0),(749,183,149,1,1068109498,1068109663,0,0,0),(750,184,149,1,1068109663,1068109781,0,0,0),(751,185,149,1,1068109781,1068109829,0,0,0),(752,186,149,1,1068109829,1068109829,0,0,0),(826,56,14,54,1069416499,1069416519,3,0,0),(860,220,14,2,1069843977,1069844000,1,0,0),(758,191,149,1,1068111317,1068111376,0,0,0),(759,192,149,1,1068111376,1068111870,0,0,0),(760,193,149,1,1068111870,1068111917,0,0,0),(761,194,149,1,1068111917,1068111917,0,0,0),(769,200,149,1,1068120480,1068120496,0,0,0),(770,201,149,1,1068120737,1068120756,0,0,0),(856,231,14,2,1069768893,1069768899,1,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(855,232,14,2,1069768881,1069768887,1,0,0),(849,227,14,1,1069766846,1069766947,1,0,0),(854,232,14,1,1069768799,1069768853,3,0,0),(853,231,14,1,1069768724,1069768747,3,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(796,14,14,5,1068468183,1068468218,3,0,0),(798,141,14,2,1068565517,1068565749,3,0,0),(800,211,14,1,1068640049,1068640085,3,0,0),(801,211,14,2,1068640100,1068640156,1,0,0),(802,141,14,3,1068640282,1068640319,3,0,0),(852,230,14,1,1069768587,1069768639,1,0,0),(851,229,14,1,1069768479,1069768552,1,0,0),(850,228,14,1,1069766960,1069767050,1,0,0),(833,219,14,1,1069763609,1069763878,1,0,0),(834,220,14,1,1069763921,1069763952,3,0,0),(835,221,14,1,1069763985,1069765267,1,0,0),(836,14,14,6,1069764559,1069764589,3,0,0),(837,222,14,1,1069765507,1069765545,1,0,0),(838,223,14,1,1069765602,1069765640,3,0,0),(839,223,14,2,1069765654,1069765670,1,0,0),(840,14,14,7,1069765717,1069765739,1,0,0),(844,224,14,1,1069766009,1069766105,1,0,0),(845,225,14,1,1069766168,1069766473,1,0,0),(846,141,14,4,1069766551,1069766561,1,0,0);
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
INSERT INTO ezgeneral_digest_user_settings VALUES (1,'bf@ez.no',0,0,'',''),(2,'wy@ez.no',0,0,'',''),(3,'nospam@ez.no',0,0,'','');
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
INSERT INTO ezimagefile VALUES (1,152,'var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum.jpg'),(2,152,'var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_reference.jpg'),(3,152,'var/forum/storage/images/setup/look_and_feel/forum/152-54-eng-GB/forum_medium.jpg'),(5,152,'var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum.gif'),(6,152,'var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum_reference.gif'),(7,152,'var/forum/storage/images/setup/look_and_feel/forum/152-56-eng-GB/forum_medium.gif'),(8,524,'var/forum/storage/images/about_this_forum/524-2-eng-GB/about_this_forum.'),(9,524,'var/forum/storage/images/about_this_forum/524-3-eng-GB/about_this_forum.'),(10,524,'var/forum/storage/images/about_this_forum/524-4-eng-GB/about_this_forum.'),(11,786,'var/forum/storage/images/news/choose_the_correct_forum/786-1-eng-GB/choose_the_correct_forum.'),(12,792,'var/forum/storage/images/news/latest_forum_dreamcars/792-1-eng-GB/latest_forum_dreamcars.'),(13,797,'var/forum/storage/images/discussions/forum_main_group/dreamcars/797-1-eng-GB/dreamcars.'),(14,731,'var/forum/storage/images/users/administrator_users/administrator_user/731-6-eng-GB/administrator_user.'),(15,731,'var/forum/storage/images/users/administrator_users/administrator_user/731-7-eng-GB/administrator_user.'),(16,757,'var/forum/storage/images/discussions/forum_main_group/music_discussion/757-4-eng-GB/music_discussion.'),(18,816,'var/forum/storage/images/discussions/forum_main_group/sport_forum/816-1-eng-GB/sport_forum.'),(20,152,'var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB/forum.gif'),(21,152,'var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB/forum_reference.gif'),(22,152,'var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB/forum_medium.gif'),(23,152,'var/forum/storage/images/setup/look_and_feel/forum/152-57-eng-GB/forum_logo.gif'),(25,152,'var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB/forum.gif'),(26,152,'var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB/forum_reference.gif'),(27,152,'var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB/forum_medium.gif'),(28,152,'var/forum/storage/images/setup/look_and_feel/forum/152-58-eng-GB/forum_logo.gif'),(29,152,'var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB/forum.gif'),(30,152,'var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB/forum_reference.gif'),(31,152,'var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB/forum_medium.gif'),(32,152,'var/forum/storage/images/setup/look_and_feel/forum/152-59-eng-GB/forum_logo.gif'),(33,797,'var/forum/storage/images/discussions/forum_main_group/dreamcars/797-2-eng-GB/dreamcars.');
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
INSERT INTO eznode_assignment VALUES (516,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(191,45,1,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(528,43,9,46,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(471,56,46,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(445,175,1,2,1,1,0,0,0),(438,168,1,2,1,1,0,0,0),(537,161,4,2,9,1,1,0,0),(421,56,45,48,9,1,1,0,0),(435,165,1,115,1,1,0,0,0),(428,160,1,50,9,1,1,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(524,56,52,48,9,1,1,0,0),(399,138,1,2,9,1,1,0,0),(527,115,4,46,9,1,1,0,0),(444,174,1,2,1,1,0,0,0),(565,56,59,48,9,1,1,0,0),(536,161,3,2,9,1,1,0,0),(538,218,1,50,9,1,1,0,0),(321,45,6,46,9,1,1,0,0),(522,56,50,48,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(562,231,2,168,9,1,1,0,0),(443,173,1,2,1,1,0,0,0),(439,169,1,2,1,1,1,0,0),(442,172,1,2,1,1,0,0,0),(564,56,58,48,9,1,1,0,0),(441,171,1,115,1,1,0,0,0),(335,45,8,46,9,1,1,0,0),(525,56,53,48,9,1,1,0,0),(561,232,2,168,9,1,1,0,0),(517,1,7,1,9,1,1,0,0),(554,226,1,152,9,1,1,0,0),(529,45,10,46,9,1,1,0,0),(530,116,3,46,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(518,1,8,1,9,1,1,0,0),(403,141,1,111,9,1,1,0,0),(551,225,1,162,1,1,1,0,0),(535,161,2,2,9,1,1,0,0),(534,56,56,48,9,1,1,0,0),(563,56,57,48,9,1,1,0,0),(550,224,1,114,9,1,1,0,0),(552,141,4,152,9,1,1,0,0),(416,138,2,2,9,1,1,0,0),(424,14,2,13,9,1,1,0,0),(446,176,1,2,1,1,0,0,0),(447,177,1,2,1,1,0,0,0),(448,178,1,2,1,1,0,0,0),(449,179,1,2,1,1,0,0,0),(450,180,1,2,1,1,0,0,0),(451,181,1,2,1,1,0,0,0),(452,182,1,2,1,1,0,0,0),(453,183,1,2,1,1,0,0,0),(454,184,1,2,1,1,0,0,0),(455,185,1,2,1,1,0,0,0),(456,186,1,2,1,1,1,0,0),(532,56,54,48,9,1,1,0,0),(566,220,2,152,9,1,1,0,0),(462,191,1,115,1,1,0,0,0),(463,192,1,2,1,1,0,0,0),(464,193,1,2,1,1,0,0,0),(465,194,1,2,1,1,1,0,0),(473,200,1,114,1,1,1,0,0),(474,201,1,135,1,1,1,0,0),(560,232,1,168,9,1,1,0,0),(559,231,1,168,9,1,1,0,0),(558,230,1,111,9,1,1,0,0),(557,229,1,165,9,1,1,0,0),(556,228,1,165,9,1,1,0,0),(481,14,3,13,9,1,1,0,0),(555,227,1,164,9,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(500,14,5,13,9,1,1,0,0),(502,141,2,111,9,1,1,0,0),(504,211,1,111,9,1,1,0,0),(505,211,2,111,9,1,1,0,0),(507,141,3,152,9,1,1,111,0),(539,219,1,50,9,1,1,0,0),(540,220,1,152,9,1,1,0,0),(541,221,1,158,9,1,1,0,0),(542,14,6,13,9,1,1,0,0),(543,222,1,158,9,1,1,0,0),(544,223,1,159,1,1,1,0,0),(545,223,2,159,1,1,1,0,0),(546,14,7,13,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (274,0,'ezpublish',220,2,0,0,'','','',''),(273,0,'ezpublish',56,59,0,0,'','','',''),(272,0,'ezpublish',56,58,0,0,'','','',''),(271,0,'ezpublish',56,57,0,0,'','','',''),(270,0,'ezpublish',231,2,0,0,'','','',''),(269,0,'ezpublish',232,2,0,0,'','','',''),(268,0,'ezpublish',232,1,0,0,'','','',''),(267,0,'ezpublish',231,1,0,0,'','','',''),(266,0,'ezpublish',230,1,0,0,'','','',''),(265,0,'ezpublish',229,1,0,0,'','','',''),(264,0,'ezpublish',228,1,0,0,'','','',''),(263,0,'ezpublish',227,1,0,0,'','','',''),(262,0,'ezpublish',226,1,0,0,'','','',''),(261,0,'ezpublish',142,4,0,0,'','','',''),(260,0,'ezpublish',141,4,0,0,'','','',''),(259,0,'ezpublish',225,1,0,0,'','','',''),(258,0,'ezpublish',224,1,0,0,'','','',''),(257,0,'ezpublish',170,2,0,0,'','','',''),(256,0,'ezpublish',151,3,0,0,'','','',''),(255,0,'ezpublish',151,2,0,0,'','','',''),(254,0,'ezpublish',14,7,0,0,'','','',''),(253,0,'ezpublish',223,2,0,0,'','','',''),(252,0,'ezpublish',223,1,0,0,'','','',''),(251,0,'ezpublish',222,1,0,0,'','','',''),(250,0,'ezpublish',221,1,0,0,'','','',''),(249,0,'ezpublish',14,6,0,0,'','','',''),(248,0,'ezpublish',220,1,0,0,'','','',''),(247,0,'ezpublish',219,1,0,0,'','','',''),(246,0,'ezpublish',218,1,0,0,'','','',''),(245,0,'ezpublish',161,4,0,0,'','','',''),(244,0,'ezpublish',161,3,0,0,'','','',''),(243,0,'ezpublish',161,2,0,0,'','','',''),(242,0,'ezpublish',56,56,0,0,'','','',''),(241,0,'ezpublish',56,54,0,0,'','','',''),(240,0,'ezpublish',116,3,0,0,'','','',''),(239,0,'ezpublish',45,10,0,0,'','','',''),(238,0,'ezpublish',43,9,0,0,'','','',''),(237,0,'ezpublish',115,4,0,0,'','','',''),(236,0,'ezpublish',56,53,0,0,'','','',''),(235,0,'ezpublish',56,52,0,0,'','','',''),(234,0,'ezpublish',56,50,0,0,'','','',''),(233,0,'ezpublish',1,8,0,0,'','','',''),(232,0,'ezpublish',1,7,0,0,'','','',''),(231,0,'ezpublish',1,6,0,0,'','','',''),(230,0,'ezpublish',215,1,0,0,'','','',''),(229,0,'ezpublish',214,1,0,0,'','','',''),(228,0,'ezpublish',212,1,0,0,'','','',''),(227,0,'ezpublish',142,3,0,0,'','','',''),(226,0,'ezpublish',141,3,0,0,'','','',''),(225,0,'ezpublish',211,2,0,0,'','','',''),(224,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',142,2,0,0,'','','',''),(222,0,'ezpublish',141,2,0,0,'','','',''),(221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','','');
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
INSERT INTO ezsearch_object_word_link VALUES (6503,231,1092,0,40,1976,0,21,1069768747,1,190,'',0),(6046,226,2301,0,11,2027,2198,20,1069766830,1,187,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(3285,43,1412,0,2,1411,0,14,1066384365,11,155,'',0),(3284,43,1411,0,1,1410,1412,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(3283,43,1410,0,0,0,1411,14,1066384365,11,152,'',0),(3291,45,1414,0,5,1413,0,14,1066388816,11,155,'',0),(3290,45,1413,0,4,25,1414,14,1066388816,11,155,'',0),(3289,45,25,0,3,34,1413,14,1066388816,11,155,'',0),(3288,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(6527,56,2359,0,7,2358,0,15,1066643397,11,202,'',0),(61,49,37,0,0,0,0,1,1066398020,4,4,'',0),(2264,160,943,0,20,1146,944,2,1068047455,4,120,'',0),(2263,160,1146,0,19,1145,943,2,1068047455,4,120,'',0),(2246,160,73,0,2,1141,74,2,1068047455,4,120,'',0),(2244,160,37,0,0,0,1141,2,1068047455,4,1,'',0),(2245,160,1141,0,1,37,73,2,1068047455,4,1,'',0),(5707,218,2036,0,77,77,1984,2,1069763601,4,121,'',0),(5706,218,77,0,76,2035,2036,2,1069763601,4,121,'',0),(5705,218,2035,0,75,1142,77,2,1069763601,4,121,'',0),(5704,218,1142,0,74,1081,2035,2,1069763601,4,121,'',0),(5703,218,1081,0,73,2034,1142,2,1069763601,4,121,'',0),(5702,218,2034,0,72,1142,1081,2,1069763601,4,121,'',0),(5701,218,1142,0,71,1402,2034,2,1069763601,4,121,'',0),(5700,218,1402,0,70,2016,1142,2,1069763601,4,121,'',0),(5699,218,2016,0,69,1116,1402,2,1069763601,4,121,'',0),(5698,218,1116,0,68,1974,2016,2,1069763601,4,121,'',0),(5697,218,1974,0,67,73,1116,2,1069763601,4,121,'',0),(5696,218,73,0,66,2013,1974,2,1069763601,4,121,'',0),(5695,218,2013,0,65,2033,73,2,1069763601,4,121,'',0),(5694,218,2033,0,64,2032,2013,2,1069763601,4,121,'',0),(5693,218,2032,0,63,2027,2033,2,1069763601,4,121,'',0),(5692,218,2027,0,62,2031,2032,2,1069763601,4,121,'',0),(5691,218,2031,0,61,2030,2027,2,1069763601,4,121,'',0),(5690,218,2030,0,60,1970,2031,2,1069763601,4,121,'',0),(5689,218,1970,0,59,2019,2030,2,1069763601,4,121,'',0),(5688,218,2019,0,58,1153,1970,2,1069763601,4,121,'',0),(5687,218,1153,0,57,2018,2019,2,1069763601,4,121,'',0),(5686,218,2018,0,56,2017,1153,2,1069763601,4,121,'',0),(5685,218,2017,0,55,1081,2018,2,1069763601,4,121,'',0),(5684,218,1081,0,54,2021,2017,2,1069763601,4,121,'',0),(5683,218,2021,0,53,1142,1081,2,1069763601,4,121,'',0),(5682,218,1142,0,52,1153,2021,2,1069763601,4,121,'',0),(5681,218,1153,0,51,2029,1142,2,1069763601,4,121,'',0),(5680,218,2029,0,50,2022,1153,2,1069763601,4,121,'',0),(5679,218,2022,0,49,1977,2029,2,1069763601,4,121,'',0),(5678,218,1977,0,48,1974,2022,2,1069763601,4,121,'',0),(5677,218,1974,0,47,1120,1977,2,1069763601,4,121,'',0),(5676,218,1120,0,46,1119,1974,2,1069763601,4,121,'',0),(5675,218,1119,0,45,2028,1120,2,1069763601,4,121,'',0),(5674,218,2028,0,44,1153,1119,2,1069763601,4,121,'',0),(5673,218,1153,0,43,2016,2028,2,1069763601,4,121,'',0),(5672,218,2016,0,42,2027,1153,2,1069763601,4,121,'',0),(5671,218,2027,0,41,2026,2016,2,1069763601,4,121,'',0),(5670,218,2026,0,40,2025,2027,2,1069763601,4,121,'',0),(5669,218,2025,0,39,2024,2026,2,1069763601,4,120,'',0),(5668,218,2024,0,38,2023,2025,2,1069763601,4,120,'',0),(5667,218,2023,0,37,934,2024,2,1069763601,4,120,'',0),(5666,218,934,0,36,74,2023,2,1069763601,4,120,'',0),(5665,218,74,0,35,2022,934,2,1069763601,4,120,'',0),(5664,218,2022,0,34,1116,74,2,1069763601,4,120,'',0),(5663,218,1116,0,33,1974,2022,2,1069763601,4,120,'',0),(5662,218,1974,0,32,1081,1116,2,1069763601,4,120,'',0),(5661,218,1081,0,31,2021,1974,2,1069763601,4,120,'',0),(5660,218,2021,0,30,1142,1081,2,1069763601,4,120,'',0),(5659,218,1142,0,29,1402,2021,2,1069763601,4,120,'',0),(5658,218,1402,0,28,2017,1142,2,1069763601,4,120,'',0),(5657,218,2017,0,27,1431,1402,2,1069763601,4,120,'',0),(5656,218,1431,0,26,2020,2017,2,1069763601,4,120,'',0),(5655,218,2020,0,25,1431,1431,2,1069763601,4,120,'',0),(5654,218,1431,0,24,1260,2020,2,1069763601,4,120,'',0),(5653,218,1260,0,23,74,1431,2,1069763601,4,120,'',0),(5652,218,74,0,22,1081,1260,2,1069763601,4,120,'',0),(5651,218,1081,0,21,2019,74,2,1069763601,4,120,'',0),(5650,218,2019,0,20,89,1081,2,1069763601,4,120,'',0),(5649,218,89,0,19,1402,2019,2,1069763601,4,120,'',0),(5648,218,1402,0,18,2018,89,2,1069763601,4,120,'',0),(5647,218,2018,0,17,2017,1402,2,1069763601,4,120,'',0),(5646,218,2017,0,16,1402,2018,2,1069763601,4,120,'',0),(5645,218,1402,0,15,2016,2017,2,1069763601,4,120,'',0),(5644,218,2016,0,14,2015,1402,2,1069763601,4,120,'',0),(5643,218,2015,0,13,1116,2016,2,1069763601,4,120,'',0),(5642,218,1116,0,12,1081,2015,2,1069763601,4,120,'',0),(5641,218,1081,0,11,2014,1116,2,1069763601,4,120,'',0),(5640,218,2014,0,10,2013,1081,2,1069763601,4,120,'',0),(5639,218,2013,0,9,1402,2014,2,1069763601,4,120,'',0),(5638,218,1402,0,8,2012,2013,2,1069763601,4,120,'',0),(5637,218,2012,0,7,2011,1402,2,1069763601,4,120,'',0),(5636,218,2011,0,6,89,2012,2,1069763601,4,120,'',0),(5635,218,89,0,5,2010,2011,2,1069763601,4,120,'',0),(5634,218,2010,0,4,1081,89,2,1069763601,4,120,'',0),(5633,218,1081,0,3,2009,2010,2,1069763601,4,1,'',0),(5632,218,2009,0,2,1142,1081,2,1069763601,4,1,'',0),(5631,218,1142,0,1,2008,2009,2,1069763601,4,1,'',0),(5630,218,2008,0,0,0,1142,2,1069763601,4,1,'',0),(5629,161,2204,0,719,74,0,10,1068047603,1,141,'',0),(5628,161,74,0,718,2203,2204,10,1068047603,1,141,'',0),(5627,161,2203,0,717,2202,74,10,1068047603,1,141,'',0),(5626,161,2202,0,716,2201,2203,10,1068047603,1,141,'',0),(2503,160,1146,0,259,1145,0,2,1068047455,4,121,'',0),(2502,160,1145,0,258,944,1146,2,1068047455,4,121,'',0),(2501,160,944,0,257,943,1145,2,1068047455,4,121,'',0),(2500,160,943,0,256,1146,944,2,1068047455,4,121,'',0),(2499,160,1146,0,255,1145,943,2,1068047455,4,121,'',0),(2498,160,1145,0,254,944,1146,2,1068047455,4,121,'',0),(2497,160,944,0,253,943,1145,2,1068047455,4,121,'',0),(2496,160,943,0,252,1146,944,2,1068047455,4,121,'',0),(2495,160,1146,0,251,1145,943,2,1068047455,4,121,'',0),(2494,160,1145,0,250,944,1146,2,1068047455,4,121,'',0),(2493,160,944,0,249,943,1145,2,1068047455,4,121,'',0),(2492,160,943,0,248,1146,944,2,1068047455,4,121,'',0),(2491,160,1146,0,247,1145,943,2,1068047455,4,121,'',0),(2490,160,1145,0,246,944,1146,2,1068047455,4,121,'',0),(2489,160,944,0,245,943,1145,2,1068047455,4,121,'',0),(2488,160,943,0,244,1146,944,2,1068047455,4,121,'',0),(2487,160,1146,0,243,1145,943,2,1068047455,4,121,'',0),(2486,160,1145,0,242,944,1146,2,1068047455,4,121,'',0),(2485,160,944,0,241,943,1145,2,1068047455,4,121,'',0),(2484,160,943,0,240,1146,944,2,1068047455,4,121,'',0),(2483,160,1146,0,239,1145,943,2,1068047455,4,121,'',0),(2482,160,1145,0,238,944,1146,2,1068047455,4,121,'',0),(2481,160,944,0,237,943,1145,2,1068047455,4,121,'',0),(2480,160,943,0,236,1146,944,2,1068047455,4,121,'',0),(2479,160,1146,0,235,1145,943,2,1068047455,4,121,'',0),(2478,160,1145,0,234,944,1146,2,1068047455,4,121,'',0),(2477,160,944,0,233,943,1145,2,1068047455,4,121,'',0),(2476,160,943,0,232,1146,944,2,1068047455,4,121,'',0),(2475,160,1146,0,231,1145,943,2,1068047455,4,121,'',0),(2474,160,1145,0,230,944,1146,2,1068047455,4,121,'',0),(2473,160,944,0,229,943,1145,2,1068047455,4,121,'',0),(2472,160,943,0,228,1146,944,2,1068047455,4,121,'',0),(2471,160,1146,0,227,1145,943,2,1068047455,4,121,'',0),(2470,160,1145,0,226,944,1146,2,1068047455,4,121,'',0),(2469,160,944,0,225,943,1145,2,1068047455,4,121,'',0),(2468,160,943,0,224,1146,944,2,1068047455,4,121,'',0),(2467,160,1146,0,223,1145,943,2,1068047455,4,121,'',0),(2466,160,1145,0,222,944,1146,2,1068047455,4,121,'',0),(2465,160,944,0,221,943,1145,2,1068047455,4,121,'',0),(2464,160,943,0,220,1146,944,2,1068047455,4,121,'',0),(2463,160,1146,0,219,1145,943,2,1068047455,4,121,'',0),(2462,160,1145,0,218,944,1146,2,1068047455,4,121,'',0),(2461,160,944,0,217,943,1145,2,1068047455,4,121,'',0),(2460,160,943,0,216,1146,944,2,1068047455,4,121,'',0),(2459,160,1146,0,215,1145,943,2,1068047455,4,121,'',0),(2458,160,1145,0,214,944,1146,2,1068047455,4,121,'',0),(2457,160,944,0,213,943,1145,2,1068047455,4,121,'',0),(2456,160,943,0,212,1146,944,2,1068047455,4,121,'',0),(2455,160,1146,0,211,1145,943,2,1068047455,4,121,'',0),(2454,160,1145,0,210,944,1146,2,1068047455,4,121,'',0),(2453,160,944,0,209,943,1145,2,1068047455,4,121,'',0),(2452,160,943,0,208,1146,944,2,1068047455,4,121,'',0),(2451,160,1146,0,207,1145,943,2,1068047455,4,121,'',0),(2450,160,1145,0,206,944,1146,2,1068047455,4,121,'',0),(2449,160,944,0,205,943,1145,2,1068047455,4,121,'',0),(2448,160,943,0,204,1146,944,2,1068047455,4,121,'',0),(2447,160,1146,0,203,1145,943,2,1068047455,4,121,'',0),(2446,160,1145,0,202,944,1146,2,1068047455,4,121,'',0),(2445,160,944,0,201,943,1145,2,1068047455,4,121,'',0),(2444,160,943,0,200,1146,944,2,1068047455,4,121,'',0),(2443,160,1146,0,199,1145,943,2,1068047455,4,121,'',0),(2442,160,1145,0,198,944,1146,2,1068047455,4,121,'',0),(2441,160,944,0,197,943,1145,2,1068047455,4,121,'',0),(2440,160,943,0,196,1146,944,2,1068047455,4,121,'',0),(2439,160,1146,0,195,1145,943,2,1068047455,4,121,'',0),(2438,160,1145,0,194,944,1146,2,1068047455,4,121,'',0),(2437,160,944,0,193,943,1145,2,1068047455,4,121,'',0),(2436,160,943,0,192,1146,944,2,1068047455,4,121,'',0),(2435,160,1146,0,191,1145,943,2,1068047455,4,121,'',0),(2434,160,1145,0,190,944,1146,2,1068047455,4,121,'',0),(2433,160,944,0,189,943,1145,2,1068047455,4,121,'',0),(2432,160,943,0,188,1146,944,2,1068047455,4,121,'',0),(2431,160,1146,0,187,1145,943,2,1068047455,4,121,'',0),(2430,160,1145,0,186,944,1146,2,1068047455,4,121,'',0),(2429,160,944,0,185,943,1145,2,1068047455,4,121,'',0),(2428,160,943,0,184,1146,944,2,1068047455,4,121,'',0),(2427,160,1146,0,183,1145,943,2,1068047455,4,121,'',0),(2426,160,1145,0,182,944,1146,2,1068047455,4,121,'',0),(2425,160,944,0,181,943,1145,2,1068047455,4,121,'',0),(2424,160,943,0,180,1144,944,2,1068047455,4,121,'',0),(2423,160,1144,0,179,37,943,2,1068047455,4,121,'',0),(2422,160,37,0,178,1143,1144,2,1068047455,4,121,'',0),(2421,160,1143,0,177,1142,37,2,1068047455,4,121,'',0),(2420,160,1142,0,176,74,1143,2,1068047455,4,121,'',0),(2419,160,74,0,175,73,1142,2,1068047455,4,121,'',0),(2418,160,73,0,174,1146,74,2,1068047455,4,121,'',0),(2417,160,1146,0,173,1145,73,2,1068047455,4,121,'',0),(2416,160,1145,0,172,944,1146,2,1068047455,4,121,'',0),(2415,160,944,0,171,943,1145,2,1068047455,4,121,'',0),(2414,160,943,0,170,1146,944,2,1068047455,4,121,'',0),(2413,160,1146,0,169,1145,943,2,1068047455,4,121,'',0),(2412,160,1145,0,168,944,1146,2,1068047455,4,121,'',0),(2411,160,944,0,167,943,1145,2,1068047455,4,121,'',0),(2410,160,943,0,166,1146,944,2,1068047455,4,121,'',0),(2409,160,1146,0,165,1145,943,2,1068047455,4,121,'',0),(2408,160,1145,0,164,944,1146,2,1068047455,4,121,'',0),(2407,160,944,0,163,943,1145,2,1068047455,4,121,'',0),(2406,160,943,0,162,1146,944,2,1068047455,4,121,'',0),(2405,160,1146,0,161,1145,943,2,1068047455,4,121,'',0),(2404,160,1145,0,160,944,1146,2,1068047455,4,121,'',0),(2403,160,944,0,159,943,1145,2,1068047455,4,121,'',0),(2402,160,943,0,158,1146,944,2,1068047455,4,121,'',0),(2401,160,1146,0,157,1145,943,2,1068047455,4,121,'',0),(2400,160,1145,0,156,944,1146,2,1068047455,4,121,'',0),(2399,160,944,0,155,943,1145,2,1068047455,4,121,'',0),(2398,160,943,0,154,1146,944,2,1068047455,4,121,'',0),(2397,160,1146,0,153,1145,943,2,1068047455,4,121,'',0),(2396,160,1145,0,152,944,1146,2,1068047455,4,121,'',0),(2395,160,944,0,151,943,1145,2,1068047455,4,121,'',0),(2394,160,943,0,150,1146,944,2,1068047455,4,121,'',0),(2393,160,1146,0,149,1145,943,2,1068047455,4,121,'',0),(2392,160,1145,0,148,944,1146,2,1068047455,4,121,'',0),(2391,160,944,0,147,943,1145,2,1068047455,4,121,'',0),(2390,160,943,0,146,1146,944,2,1068047455,4,121,'',0),(2389,160,1146,0,145,1145,943,2,1068047455,4,121,'',0),(2388,160,1145,0,144,944,1146,2,1068047455,4,121,'',0),(2387,160,944,0,143,943,1145,2,1068047455,4,121,'',0),(2386,160,943,0,142,1146,944,2,1068047455,4,121,'',0),(2385,160,1146,0,141,1145,943,2,1068047455,4,121,'',0),(2384,160,1145,0,140,944,1146,2,1068047455,4,121,'',0),(2383,160,944,0,139,943,1145,2,1068047455,4,121,'',0),(2382,160,943,0,138,1146,944,2,1068047455,4,121,'',0),(2381,160,1146,0,137,1145,943,2,1068047455,4,121,'',0),(2380,160,1145,0,136,944,1146,2,1068047455,4,121,'',0),(2379,160,944,0,135,943,1145,2,1068047455,4,121,'',0),(2378,160,943,0,134,1146,944,2,1068047455,4,121,'',0),(2377,160,1146,0,133,1145,943,2,1068047455,4,121,'',0),(2376,160,1145,0,132,944,1146,2,1068047455,4,121,'',0),(2375,160,944,0,131,943,1145,2,1068047455,4,121,'',0),(2374,160,943,0,130,1146,944,2,1068047455,4,121,'',0),(2373,160,1146,0,129,1145,943,2,1068047455,4,121,'',0),(2372,160,1145,0,128,944,1146,2,1068047455,4,121,'',0),(2371,160,944,0,127,943,1145,2,1068047455,4,121,'',0),(2370,160,943,0,126,1146,944,2,1068047455,4,121,'',0),(2369,160,1146,0,125,1145,943,2,1068047455,4,121,'',0),(2368,160,1145,0,124,944,1146,2,1068047455,4,121,'',0),(2367,160,944,0,123,943,1145,2,1068047455,4,121,'',0),(2366,160,943,0,122,1146,944,2,1068047455,4,121,'',0),(2365,160,1146,0,121,1145,943,2,1068047455,4,121,'',0),(2364,160,1145,0,120,944,1146,2,1068047455,4,121,'',0),(2363,160,944,0,119,943,1145,2,1068047455,4,121,'',0),(2362,160,943,0,118,1146,944,2,1068047455,4,121,'',0),(2361,160,1146,0,117,1145,943,2,1068047455,4,121,'',0),(2360,160,1145,0,116,944,1146,2,1068047455,4,121,'',0),(2359,160,944,0,115,943,1145,2,1068047455,4,121,'',0),(2358,160,943,0,114,1146,944,2,1068047455,4,121,'',0),(2357,160,1146,0,113,1145,943,2,1068047455,4,121,'',0),(2356,160,1145,0,112,944,1146,2,1068047455,4,121,'',0),(2355,160,944,0,111,943,1145,2,1068047455,4,121,'',0),(2354,160,943,0,110,1146,944,2,1068047455,4,121,'',0),(2353,160,1146,0,109,1145,943,2,1068047455,4,121,'',0),(2352,160,1145,0,108,944,1146,2,1068047455,4,121,'',0),(2351,160,944,0,107,943,1145,2,1068047455,4,121,'',0),(2350,160,943,0,106,1146,944,2,1068047455,4,121,'',0),(2349,160,1146,0,105,1145,943,2,1068047455,4,121,'',0),(2348,160,1145,0,104,944,1146,2,1068047455,4,121,'',0),(2347,160,944,0,103,943,1145,2,1068047455,4,121,'',0),(2346,160,943,0,102,1146,944,2,1068047455,4,121,'',0),(2345,160,1146,0,101,1145,943,2,1068047455,4,121,'',0),(2344,160,1145,0,100,944,1146,2,1068047455,4,121,'',0),(2343,160,944,0,99,943,1145,2,1068047455,4,121,'',0),(2342,160,943,0,98,1146,944,2,1068047455,4,121,'',0),(2341,160,1146,0,97,1145,943,2,1068047455,4,121,'',0),(2340,160,1145,0,96,944,1146,2,1068047455,4,121,'',0),(2339,160,944,0,95,943,1145,2,1068047455,4,121,'',0),(2338,160,943,0,94,1144,944,2,1068047455,4,121,'',0),(2337,160,1144,0,93,37,943,2,1068047455,4,121,'',0),(2336,160,37,0,92,1143,1144,2,1068047455,4,121,'',0),(2335,160,1143,0,91,1142,37,2,1068047455,4,121,'',0),(2334,160,1142,0,90,74,1143,2,1068047455,4,121,'',0),(2333,160,74,0,89,73,1142,2,1068047455,4,121,'',0),(2332,160,73,0,88,1146,74,2,1068047455,4,121,'',0),(2331,160,1146,0,87,1145,73,2,1068047455,4,120,'',0),(2330,160,1145,0,86,944,1146,2,1068047455,4,120,'',0),(2329,160,944,0,85,943,1145,2,1068047455,4,120,'',0),(2328,160,943,0,84,1146,944,2,1068047455,4,120,'',0),(2327,160,1146,0,83,1145,943,2,1068047455,4,120,'',0),(2326,160,1145,0,82,944,1146,2,1068047455,4,120,'',0),(2325,160,944,0,81,943,1145,2,1068047455,4,120,'',0),(2324,160,943,0,80,1146,944,2,1068047455,4,120,'',0),(2323,160,1146,0,79,1145,943,2,1068047455,4,120,'',0),(2322,160,1145,0,78,944,1146,2,1068047455,4,120,'',0),(2321,160,944,0,77,943,1145,2,1068047455,4,120,'',0),(2320,160,943,0,76,1146,944,2,1068047455,4,120,'',0),(2319,160,1146,0,75,1145,943,2,1068047455,4,120,'',0),(2318,160,1145,0,74,944,1146,2,1068047455,4,120,'',0),(2317,160,944,0,73,943,1145,2,1068047455,4,120,'',0),(2316,160,943,0,72,1146,944,2,1068047455,4,120,'',0),(2315,160,1146,0,71,1145,943,2,1068047455,4,120,'',0),(2314,160,1145,0,70,944,1146,2,1068047455,4,120,'',0),(2313,160,944,0,69,943,1145,2,1068047455,4,120,'',0),(2312,160,943,0,68,1146,944,2,1068047455,4,120,'',0),(2311,160,1146,0,67,1145,943,2,1068047455,4,120,'',0),(2310,160,1145,0,66,944,1146,2,1068047455,4,120,'',0),(2309,160,944,0,65,943,1145,2,1068047455,4,120,'',0),(2308,160,943,0,64,1146,944,2,1068047455,4,120,'',0),(2307,160,1146,0,63,1145,943,2,1068047455,4,120,'',0),(2306,160,1145,0,62,944,1146,2,1068047455,4,120,'',0),(2305,160,944,0,61,943,1145,2,1068047455,4,120,'',0),(2304,160,943,0,60,1146,944,2,1068047455,4,120,'',0),(2303,160,1146,0,59,1145,943,2,1068047455,4,120,'',0),(2302,160,1145,0,58,944,1146,2,1068047455,4,120,'',0),(2301,160,944,0,57,943,1145,2,1068047455,4,120,'',0),(2300,160,943,0,56,1146,944,2,1068047455,4,120,'',0),(2299,160,1146,0,55,1145,943,2,1068047455,4,120,'',0),(2298,160,1145,0,54,944,1146,2,1068047455,4,120,'',0),(2297,160,944,0,53,943,1145,2,1068047455,4,120,'',0),(2296,160,943,0,52,1146,944,2,1068047455,4,120,'',0),(2295,160,1146,0,51,1145,943,2,1068047455,4,120,'',0),(2294,160,1145,0,50,944,1146,2,1068047455,4,120,'',0),(2293,160,944,0,49,943,1145,2,1068047455,4,120,'',0),(2292,160,943,0,48,1146,944,2,1068047455,4,120,'',0),(2291,160,1146,0,47,1145,943,2,1068047455,4,120,'',0),(2290,160,1145,0,46,944,1146,2,1068047455,4,120,'',0),(2289,160,944,0,45,943,1145,2,1068047455,4,120,'',0),(2288,160,943,0,44,1146,944,2,1068047455,4,120,'',0),(2287,160,1146,0,43,1145,943,2,1068047455,4,120,'',0),(2286,160,1145,0,42,944,1146,2,1068047455,4,120,'',0),(2285,160,944,0,41,943,1145,2,1068047455,4,120,'',0),(2284,160,943,0,40,1146,944,2,1068047455,4,120,'',0),(2283,160,1146,0,39,1145,943,2,1068047455,4,120,'',0),(2282,160,1145,0,38,944,1146,2,1068047455,4,120,'',0),(2281,160,944,0,37,943,1145,2,1068047455,4,120,'',0),(2280,160,943,0,36,1146,944,2,1068047455,4,120,'',0),(2279,160,1146,0,35,1145,943,2,1068047455,4,120,'',0),(2278,160,1145,0,34,944,1146,2,1068047455,4,120,'',0),(2277,160,944,0,33,943,1145,2,1068047455,4,120,'',0),(2276,160,943,0,32,1146,944,2,1068047455,4,120,'',0),(2275,160,1146,0,31,1145,943,2,1068047455,4,120,'',0),(2274,160,1145,0,30,944,1146,2,1068047455,4,120,'',0),(2273,160,944,0,29,943,1145,2,1068047455,4,120,'',0),(2272,160,943,0,28,1146,944,2,1068047455,4,120,'',0),(2271,160,1146,0,27,1145,943,2,1068047455,4,120,'',0),(2270,160,1145,0,26,944,1146,2,1068047455,4,120,'',0),(2269,160,944,0,25,943,1145,2,1068047455,4,120,'',0),(2268,160,943,0,24,1146,944,2,1068047455,4,120,'',0),(2267,160,1146,0,23,1145,943,2,1068047455,4,120,'',0),(2266,160,1145,0,22,944,1146,2,1068047455,4,120,'',0),(2265,160,944,0,21,943,1145,2,1068047455,4,120,'',0),(2262,160,1145,0,18,944,1146,2,1068047455,4,120,'',0),(2261,160,944,0,17,943,1145,2,1068047455,4,120,'',0),(2260,160,943,0,16,1146,944,2,1068047455,4,120,'',0),(2259,160,1146,0,15,1145,943,2,1068047455,4,120,'',0),(2258,160,1145,0,14,944,1146,2,1068047455,4,120,'',0),(2257,160,944,0,13,943,1145,2,1068047455,4,120,'',0),(2256,160,943,0,12,1146,944,2,1068047455,4,120,'',0),(2255,160,1146,0,11,1145,943,2,1068047455,4,120,'',0),(2254,160,1145,0,10,944,1146,2,1068047455,4,120,'',0),(2253,160,944,0,9,943,1145,2,1068047455,4,120,'',0),(2252,160,943,0,8,1144,944,2,1068047455,4,120,'',0),(2251,160,1144,0,7,37,943,2,1068047455,4,120,'',0),(2250,160,37,0,6,1143,1144,2,1068047455,4,120,'',0),(2249,160,1143,0,5,1142,37,2,1068047455,4,120,'',0),(2247,160,74,0,3,73,1142,2,1068047455,4,120,'',0),(2248,160,1142,0,4,74,1143,2,1068047455,4,120,'',0),(6048,226,1099,0,13,2198,33,20,1069766830,1,187,'',0),(6047,226,2198,0,12,2301,1099,20,1069766830,1,187,'',0),(3276,1,37,0,15,1143,0,1,1033917596,1,119,'',0),(3275,1,1143,0,14,1099,37,1,1033917596,1,119,'',0),(3274,1,1099,0,13,1153,1143,1,1033917596,1,119,'',0),(3273,1,1153,0,12,1115,1099,1,1033917596,1,119,'',0),(3272,1,1115,0,11,1143,1153,1,1033917596,1,119,'',0),(6029,141,381,0,4,1094,0,20,1068036586,1,187,'',0),(6028,141,1094,0,3,1095,381,20,1068036586,1,187,'',0),(6027,141,1095,0,2,1087,1094,20,1068036586,1,187,'',0),(6026,141,1087,0,1,1094,1095,20,1068036586,1,186,'',0),(6025,141,1094,0,0,0,1087,20,1068036586,1,186,'',0),(6003,224,2290,0,27,2255,1092,21,1069766105,1,189,'',0),(6002,224,2255,0,26,2254,2290,21,1069766105,1,189,'',0),(6001,224,2254,0,25,2065,2255,21,1069766105,1,189,'',0),(6000,224,2065,0,24,2140,2254,21,1069766105,1,189,'',0),(5999,224,2140,0,23,2290,2065,21,1069766105,1,189,'',0),(5998,224,2290,0,22,2289,2140,21,1069766105,1,189,'',0),(5997,224,2289,0,21,1094,2290,21,1069766105,1,189,'',0),(5996,224,1094,0,20,1402,2289,21,1069766105,1,189,'',0),(5995,224,1402,0,19,2288,1094,21,1069766105,1,189,'',0),(5994,224,2288,0,18,2287,1402,21,1069766105,1,189,'',0),(5993,224,2287,0,17,33,2288,21,1069766105,1,189,'',0),(5992,224,33,0,16,2286,2287,21,1069766105,1,189,'',0),(5991,224,2286,0,15,2132,33,21,1069766105,1,189,'',0),(5990,224,2132,0,14,1132,2286,21,1069766105,1,189,'',0),(5989,224,1132,0,13,2285,2132,21,1069766105,1,189,'',0),(5625,161,2201,0,715,1081,2202,10,1068047603,1,141,'',0),(5624,161,1081,0,714,1142,2201,10,1068047603,1,141,'',0),(5623,161,1142,0,713,2055,1081,10,1068047603,1,141,'',0),(5622,161,2055,0,712,2200,1142,10,1068047603,1,141,'',0),(5621,161,2200,0,711,1142,2055,10,1068047603,1,141,'',0),(5620,161,1142,0,710,1989,2200,10,1068047603,1,141,'',0),(5619,161,1989,0,709,2199,1142,10,1068047603,1,141,'',0),(5618,161,2199,0,708,2198,1989,10,1068047603,1,141,'',0),(5617,161,2198,0,707,2195,2199,10,1068047603,1,141,'',0),(5616,161,2195,0,706,1378,2198,10,1068047603,1,141,'',0),(5615,161,1378,0,705,1117,2195,10,1068047603,1,141,'',0),(5614,161,1117,0,704,33,1378,10,1068047603,1,141,'',0),(5613,161,33,0,703,2197,1117,10,1068047603,1,141,'',0),(5612,161,2197,0,702,1966,33,10,1068047603,1,141,'',0),(5611,161,1966,0,701,1153,2197,10,1068047603,1,141,'',0),(5610,161,1153,0,700,2196,1966,10,1068047603,1,141,'',0),(5609,161,2196,0,699,1378,1153,10,1068047603,1,141,'',0),(5608,161,1378,0,698,1260,2196,10,1068047603,1,141,'',0),(5607,161,1260,0,697,1117,1378,10,1068047603,1,141,'',0),(5606,161,1117,0,696,2018,1260,10,1068047603,1,141,'',0),(5605,161,2018,0,695,1120,1117,10,1068047603,1,141,'',0),(5604,161,1120,0,694,1142,2018,10,1068047603,1,141,'',0),(5603,161,1142,0,693,1144,1120,10,1068047603,1,141,'',0),(5602,161,1144,0,692,2195,1142,10,1068047603,1,141,'',0),(5601,161,2195,0,691,1378,1144,10,1068047603,1,141,'',0),(5600,161,1378,0,690,1117,2195,10,1068047603,1,141,'',0),(5599,161,1117,0,689,2194,1378,10,1068047603,1,141,'',0),(5598,161,2194,0,688,33,1117,10,1068047603,1,141,'',0),(5597,161,33,0,687,2193,2194,10,1068047603,1,141,'',0),(5596,161,2193,0,686,2192,33,10,1068047603,1,141,'',0),(5595,161,2192,0,685,2190,2193,10,1068047603,1,141,'',0),(5594,161,2190,0,684,2189,2192,10,1068047603,1,141,'',0),(5593,161,2189,0,683,2191,2190,10,1068047603,1,141,'',0),(5592,161,2191,0,682,1976,2189,10,1068047603,1,141,'',0),(5591,161,1976,0,681,2016,2191,10,1068047603,1,141,'',0),(5590,161,2016,0,680,89,1976,10,1068047603,1,141,'',0),(5589,161,89,0,679,2190,2016,10,1068047603,1,141,'',0),(5588,161,2190,0,678,2189,89,10,1068047603,1,141,'',0),(5587,161,2189,0,677,2010,2190,10,1068047603,1,141,'',0),(5586,161,2010,0,676,1260,2189,10,1068047603,1,141,'',0),(5585,161,1260,0,675,2123,2010,10,1068047603,1,141,'',0),(5584,161,2123,0,674,2066,1260,10,1068047603,1,141,'',0),(5583,161,2066,0,673,1142,2123,10,1068047603,1,141,'',0),(5582,161,1142,0,672,2055,2066,10,1068047603,1,141,'',0),(5581,161,2055,0,671,2188,1142,10,1068047603,1,141,'',0),(5580,161,2188,0,670,2187,2055,10,1068047603,1,141,'',0),(5579,161,2187,0,669,2032,2188,10,1068047603,1,141,'',0),(5578,161,2032,0,668,89,2187,10,1068047603,1,141,'',0),(5577,161,89,0,667,1378,2032,10,1068047603,1,141,'',0),(5576,161,1378,0,666,1117,89,10,1068047603,1,141,'',0),(5575,161,1117,0,665,73,1378,10,1068047603,1,141,'',0),(5574,161,73,0,664,1404,1117,10,1068047603,1,141,'',0),(5573,161,1404,0,663,1142,73,10,1068047603,1,141,'',0),(5572,161,1142,0,662,2007,1404,10,1068047603,1,141,'',0),(5571,161,2007,0,661,2186,1142,10,1068047603,1,141,'',0),(5570,161,2186,0,660,33,2007,10,1068047603,1,141,'',0),(5569,161,33,0,659,2185,2186,10,1068047603,1,141,'',0),(5568,161,2185,0,658,1142,33,10,1068047603,1,141,'',0),(5567,161,1142,0,657,1144,2185,10,1068047603,1,141,'',0),(5566,161,1144,0,656,2184,1142,10,1068047603,1,141,'',0),(5565,161,2184,0,655,1118,1144,10,1068047603,1,141,'',0),(5564,161,1118,0,654,1964,2184,10,1068047603,1,141,'',0),(5563,161,1964,0,653,1117,1118,10,1068047603,1,141,'',0),(5562,161,1117,0,652,1116,1964,10,1068047603,1,141,'',0),(5561,161,1116,0,651,2111,1117,10,1068047603,1,141,'',0),(5560,161,2111,0,650,2055,1116,10,1068047603,1,141,'',0),(5559,161,2055,0,649,2183,2111,10,1068047603,1,141,'',0),(5558,161,2183,0,648,33,2055,10,1068047603,1,141,'',0),(5557,161,33,0,647,2182,2183,10,1068047603,1,141,'',0),(5556,161,2182,0,646,2110,33,10,1068047603,1,141,'',0),(5555,161,2110,0,645,2140,2182,10,1068047603,1,141,'',0),(5554,161,2140,0,644,2078,2110,10,1068047603,1,141,'',0),(5553,161,2078,0,643,1982,2140,10,1068047603,1,141,'',0),(5552,161,1982,0,642,1404,2078,10,1068047603,1,141,'',0),(5551,161,1404,0,641,1142,1982,10,1068047603,1,141,'',0),(5550,161,1142,0,640,1989,1404,10,1068047603,1,141,'',0),(5549,161,1989,0,639,2181,1142,10,1068047603,1,141,'',0),(5548,161,2181,0,638,2105,1989,10,1068047603,1,141,'',0),(5547,161,2105,0,637,1120,2181,10,1068047603,1,141,'',0),(5546,161,1120,0,636,1142,2105,10,1068047603,1,141,'',0),(5545,161,1142,0,635,2180,1120,10,1068047603,1,141,'',0),(5544,161,2180,0,634,2179,1142,10,1068047603,1,141,'',0),(5543,161,2179,0,633,2078,2180,10,1068047603,1,141,'',0),(5542,161,2078,0,632,2003,2179,10,1068047603,1,141,'',0),(5541,161,2003,0,631,2178,2078,10,1068047603,1,141,'',0),(5540,161,2178,0,630,2027,2003,10,1068047603,1,141,'',0),(5539,161,2027,0,629,2026,2178,10,1068047603,1,141,'',0),(5538,161,2026,0,628,2177,2027,10,1068047603,1,141,'',0),(5537,161,2177,0,627,1988,2026,10,1068047603,1,141,'',0),(5536,161,1988,0,626,1116,2177,10,1068047603,1,141,'',0),(5535,161,1116,0,625,2001,1988,10,1068047603,1,141,'',0),(5534,161,2001,0,624,2152,1116,10,1068047603,1,141,'',0),(5533,161,2152,0,623,2105,2001,10,1068047603,1,141,'',0),(5532,161,2105,0,622,2176,2152,10,1068047603,1,141,'',0),(5531,161,2176,0,621,1974,2105,10,1068047603,1,141,'',0),(5530,161,1974,0,620,1970,2176,10,1068047603,1,141,'',0),(5529,161,1970,0,619,2001,1974,10,1068047603,1,141,'',0),(5528,161,2001,0,618,1266,1970,10,1068047603,1,141,'',0),(5527,161,1266,0,617,2175,2001,10,1068047603,1,141,'',0),(5526,161,2175,0,616,2174,1266,10,1068047603,1,141,'',0),(5525,161,2174,0,615,1999,2175,10,1068047603,1,141,'',0),(5524,161,1999,0,614,1116,2174,10,1068047603,1,141,'',0),(5523,161,1116,0,613,1974,1999,10,1068047603,1,141,'',0),(5522,161,1974,0,612,2108,1116,10,1068047603,1,141,'',0),(5521,161,2108,0,611,1402,1974,10,1068047603,1,141,'',0),(5520,161,1402,0,610,2053,2108,10,1068047603,1,141,'',0),(5519,161,2053,0,609,2017,1402,10,1068047603,1,141,'',0),(5518,161,2017,0,608,1977,2053,10,1068047603,1,141,'',0),(5517,161,1977,0,607,2173,2017,10,1068047603,1,141,'',0),(5516,161,2173,0,606,2012,1977,10,1068047603,1,141,'',0),(5515,161,2012,0,605,2011,2173,10,1068047603,1,141,'',0),(5514,161,2011,0,604,89,2012,10,1068047603,1,141,'',0),(5513,161,89,0,603,2010,2011,10,1068047603,1,141,'',0),(5512,161,2010,0,602,2016,89,10,1068047603,1,141,'',0),(5511,161,2016,0,601,1116,2010,10,1068047603,1,141,'',0),(5510,161,1116,0,600,2051,2016,10,1068047603,1,141,'',0),(5509,161,2051,0,599,2172,1116,10,1068047603,1,141,'',0),(5508,161,2172,0,598,2171,2051,10,1068047603,1,141,'',0),(5507,161,2171,0,597,2170,2172,10,1068047603,1,141,'',0),(5506,161,2170,0,596,2110,2171,10,1068047603,1,141,'',0),(5505,161,2110,0,595,2165,2170,10,1068047603,1,141,'',0),(5504,161,2165,0,594,1964,2110,10,1068047603,1,141,'',0),(5503,161,1964,0,593,2007,2165,10,1068047603,1,141,'',0),(5502,161,2007,0,592,2061,1964,10,1068047603,1,141,'',0),(5501,161,2061,0,591,89,2007,10,1068047603,1,141,'',0),(5500,161,89,0,590,1999,2061,10,1068047603,1,141,'',0),(5499,161,1999,0,589,1116,89,10,1068047603,1,141,'',0),(5498,161,1116,0,588,1153,1999,10,1068047603,1,141,'',0),(5497,161,1153,0,587,2169,1116,10,1068047603,1,141,'',0),(5496,161,2169,0,586,1970,1153,10,1068047603,1,141,'',0),(5495,161,1970,0,585,2168,2169,10,1068047603,1,141,'',0),(5494,161,2168,0,584,2167,1970,10,1068047603,1,141,'',0),(5493,161,2167,0,583,1142,2168,10,1068047603,1,141,'',0),(5492,161,1142,0,582,2055,2167,10,1068047603,1,141,'',0),(5491,161,2055,0,581,1991,1142,10,1068047603,1,141,'',0),(5490,161,1991,0,580,1142,2055,10,1068047603,1,141,'',0),(5489,161,1142,0,579,2166,1991,10,1068047603,1,141,'',0),(5488,161,2166,0,578,1402,1142,10,1068047603,1,141,'',0),(5487,161,1402,0,577,2165,2166,10,1068047603,1,141,'',0),(5486,161,2165,0,576,2061,1402,10,1068047603,1,141,'',0),(5485,161,2061,0,575,1977,2165,10,1068047603,1,141,'',0),(5484,161,1977,0,574,2164,2061,10,1068047603,1,141,'',0),(5483,161,2164,0,573,1402,1977,10,1068047603,1,141,'',0),(5482,161,1402,0,572,2112,2164,10,1068047603,1,141,'',0),(5481,161,2112,0,571,1142,1402,10,1068047603,1,141,'',0),(5480,161,1142,0,570,2139,2112,10,1068047603,1,141,'',0),(5479,161,2139,0,569,1116,1142,10,1068047603,1,141,'',0),(5478,161,1116,0,568,2075,2139,10,1068047603,1,141,'',0),(5477,161,2075,0,567,2163,1116,10,1068047603,1,141,'',0),(5476,161,2163,0,566,1142,2075,10,1068047603,1,141,'',0),(5475,161,1142,0,565,2108,2163,10,1068047603,1,141,'',0),(5474,161,2108,0,564,2162,1142,10,1068047603,1,141,'',0),(5473,161,2162,0,563,1983,2108,10,1068047603,1,141,'',0),(5472,161,1983,0,562,1116,2162,10,1068047603,1,141,'',0),(5471,161,1116,0,561,2161,1983,10,1068047603,1,141,'',0),(5470,161,2161,0,560,2049,1116,10,1068047603,1,141,'',0),(5469,161,2049,0,559,2061,2161,10,1068047603,1,141,'',0),(5468,161,2061,0,558,1977,2049,10,1068047603,1,141,'',0),(5467,161,1977,0,557,2138,2061,10,1068047603,1,141,'',0),(5466,161,2138,0,556,2160,1977,10,1068047603,1,141,'',0),(5465,161,2160,0,555,2028,2138,10,1068047603,1,141,'',0),(5464,161,2028,0,554,2159,2160,10,1068047603,1,141,'',0),(5463,161,2159,0,553,2071,2028,10,1068047603,1,141,'',0),(5462,161,2071,0,552,2158,2159,10,1068047603,1,141,'',0),(5461,161,2158,0,551,2154,2071,10,1068047603,1,141,'',0),(5460,161,2154,0,550,74,2158,10,1068047603,1,141,'',0),(5459,161,74,0,549,2022,2154,10,1068047603,1,141,'',0),(5458,161,2022,0,548,1977,74,10,1068047603,1,141,'',0),(5457,161,1977,0,547,2138,2022,10,1068047603,1,141,'',0),(5456,161,2138,0,546,2010,1977,10,1068047603,1,141,'',0),(5455,161,2010,0,545,1116,2138,10,1068047603,1,141,'',0),(5454,161,1116,0,544,2012,2010,10,1068047603,1,141,'',0),(5453,161,2012,0,543,2152,1116,10,1068047603,1,141,'',0),(5452,161,2152,0,542,2011,2012,10,1068047603,1,141,'',0),(5451,161,2011,0,541,1142,2152,10,1068047603,1,141,'',0),(5450,161,1142,0,540,2157,2011,10,1068047603,1,141,'',0),(5449,161,2157,0,539,1153,1142,10,1068047603,1,141,'',0),(5448,161,1153,0,538,2022,2157,10,1068047603,1,141,'',0),(5447,161,2022,0,537,1977,1153,10,1068047603,1,141,'',0),(5446,161,1977,0,536,2078,2022,10,1068047603,1,141,'',0),(5445,161,2078,0,535,2036,1977,10,1068047603,1,141,'',0),(5444,161,2036,0,534,2093,2078,10,1068047603,1,141,'',0),(5443,161,2093,0,533,1983,2036,10,1068047603,1,141,'',0),(5442,161,1983,0,532,2018,2093,10,1068047603,1,141,'',0),(5441,161,2018,0,531,2156,1983,10,1068047603,1,141,'',0),(5440,161,2156,0,530,2112,2018,10,1068047603,1,141,'',0),(5439,161,2112,0,529,1142,2156,10,1068047603,1,141,'',0),(5438,161,1142,0,528,2155,2112,10,1068047603,1,141,'',0),(5437,161,2155,0,527,2154,1142,10,1068047603,1,141,'',0),(5436,161,2154,0,526,74,2155,10,1068047603,1,141,'',0),(5435,161,74,0,525,2153,2154,10,1068047603,1,141,'',0),(2206,138,1120,0,7,1087,0,1,1068036060,1,119,'',0),(2205,138,1087,0,6,1119,1120,1,1068036060,1,119,'',0),(2204,138,1119,0,5,1118,1087,1,1068036060,1,119,'',0),(2203,138,1118,0,4,1117,1119,1,1068036060,1,119,'',0),(2202,138,1117,0,3,1116,1118,1,1068036060,1,119,'',0),(2201,138,1116,0,2,381,1117,1,1068036060,1,119,'',0),(5988,224,2285,0,12,2284,1132,21,1069766105,1,189,'',0),(5987,224,2284,0,11,1153,2285,21,1069766105,1,189,'',0),(5986,224,1153,0,10,2283,2284,21,1069766105,1,189,'',0),(5985,224,2283,0,9,2282,1153,21,1069766105,1,189,'',0),(2199,138,1115,0,0,0,381,1,1068036060,1,4,'',0),(2200,138,381,0,1,1115,1116,1,1068036060,1,119,'',0),(3287,45,33,0,1,32,34,14,1066388816,11,152,'',0),(3282,115,1409,0,2,7,0,14,1066991725,11,155,'',0),(3281,115,7,0,1,1409,1409,14,1066991725,11,155,'',0),(3280,115,1409,0,0,0,7,14,1066991725,11,152,'',0),(3295,116,1417,0,3,25,0,14,1066992054,11,155,'',0),(3294,116,25,0,2,1416,1417,14,1066992054,11,155,'',0),(3293,116,1416,0,1,1415,25,14,1066992054,11,152,'',0),(3292,116,1415,0,0,0,1416,14,1066992054,11,152,'',0),(3286,45,32,0,0,0,33,14,1066388816,11,152,'',0),(3271,1,1143,0,10,1094,1115,1,1033917596,1,119,'',0),(6064,227,1142,0,2,74,2253,21,1069766947,1,188,'',0),(6063,227,74,0,1,1132,1142,21,1069766947,1,188,'',0),(6062,227,1132,0,0,0,74,21,1069766947,1,188,'',0),(6059,226,2305,0,24,2304,934,20,1069766830,1,187,'',0),(6060,226,934,0,25,2305,2018,20,1069766830,1,187,'',0),(5916,14,2261,0,6,1316,2261,4,1033920830,2,199,'',0),(5915,14,1316,0,5,2260,2261,4,1033920830,2,198,'',0),(5983,224,1374,0,7,74,2282,21,1069766105,1,189,'',0),(5984,224,2282,0,8,1374,2283,21,1069766105,1,189,'',0),(5982,224,74,0,6,2281,1374,21,1069766105,1,189,'',0),(5981,224,2281,0,5,2281,74,21,1069766105,1,189,'',0),(5980,224,2281,0,4,2007,2281,21,1069766105,1,188,'',0),(5979,224,2007,0,3,2034,2281,21,1069766105,1,188,'',0),(5976,224,1132,0,0,0,74,21,1069766105,1,188,'',0),(5977,224,74,0,1,1132,2034,21,1069766105,1,188,'',0),(5978,224,2034,0,2,74,2007,21,1069766105,1,188,'',0),(6097,227,1092,0,35,2316,0,21,1069766947,1,190,'',0),(6098,228,2049,0,0,0,74,22,1069767050,1,191,'',0),(6100,228,2309,0,2,74,1132,22,1069767050,1,191,'',0),(6101,228,1132,0,3,2309,74,22,1069767050,1,193,'',0),(6061,226,2018,0,26,934,0,20,1069766830,1,187,'',0),(6024,225,2297,0,19,2296,0,22,1069766473,1,193,'',0),(6023,225,2296,0,18,2105,2297,22,1069766473,1,193,'',0),(6106,228,1999,0,8,2254,2317,22,1069767050,1,193,'',0),(6105,228,2254,0,7,2310,1999,22,1069767050,1,193,'',0),(6104,228,2310,0,6,2309,2254,22,1069767050,1,193,'',0),(6103,228,2309,0,5,74,2310,22,1069767050,1,193,'',0),(6102,228,74,0,4,1132,2309,22,1069767050,1,193,'',0),(5434,161,2153,0,524,2061,74,10,1068047603,1,141,'',0),(5433,161,2061,0,523,1977,2153,10,1068047603,1,141,'',0),(5432,161,1977,0,522,2082,2061,10,1068047603,1,141,'',0),(5431,161,2082,0,521,33,1977,10,1068047603,1,141,'',0),(5430,161,33,0,520,2001,2082,10,1068047603,1,141,'',0),(5429,161,2001,0,519,73,33,10,1068047603,1,141,'',0),(5428,161,73,0,518,1979,2001,10,1068047603,1,141,'',0),(5427,161,1979,0,517,1402,73,10,1068047603,1,141,'',0),(5426,161,1402,0,516,2010,1979,10,1068047603,1,141,'',0),(5425,161,2010,0,515,1116,1402,10,1068047603,1,141,'',0),(5424,161,1116,0,514,2012,2010,10,1068047603,1,141,'',0),(5423,161,2012,0,513,2152,1116,10,1068047603,1,141,'',0),(5422,161,2152,0,512,1142,2012,10,1068047603,1,141,'',0),(5421,161,1142,0,511,2151,2152,10,1068047603,1,141,'',0),(5420,161,2151,0,510,1977,1142,10,1068047603,1,141,'',0),(5419,161,1977,0,509,1153,2151,10,1068047603,1,141,'',0),(5418,161,1153,0,508,2150,1977,10,1068047603,1,141,'',0),(5417,161,2150,0,507,2144,1153,10,1068047603,1,141,'',0),(5416,161,2144,0,506,2143,2150,10,1068047603,1,141,'',0),(5415,161,2143,0,505,1966,2144,10,1068047603,1,141,'',0),(5414,161,1966,0,504,33,2143,10,1068047603,1,141,'',0),(5413,161,33,0,503,1987,1966,10,1068047603,1,141,'',0),(5412,161,1987,0,502,2143,33,10,1068047603,1,141,'',0),(5411,161,2143,0,501,2149,1987,10,1068047603,1,141,'',0),(5410,161,2149,0,500,1977,2143,10,1068047603,1,141,'',0),(5409,161,1977,0,499,1153,2149,10,1068047603,1,141,'',0),(5408,161,1153,0,498,1987,1977,10,1068047603,1,141,'',0),(5407,161,1987,0,497,2143,1153,10,1068047603,1,141,'',0),(5406,161,2143,0,496,2148,1987,10,1068047603,1,141,'',0),(5405,161,2148,0,495,1977,2143,10,1068047603,1,141,'',0),(5404,161,1977,0,494,1153,2148,10,1068047603,1,141,'',0),(5403,161,1153,0,493,32,1977,10,1068047603,1,141,'',0),(5402,161,32,0,492,2017,1153,10,1068047603,1,141,'',0),(5401,161,2017,0,491,1116,32,10,1068047603,1,141,'',0),(5400,161,1116,0,490,2077,2017,10,1068047603,1,141,'',0),(5399,161,2077,0,489,2068,1116,10,1068047603,1,141,'',0),(5398,161,2068,0,488,2147,2077,10,1068047603,1,141,'',0),(5397,161,2147,0,487,2146,2068,10,1068047603,1,141,'',0),(5396,161,2146,0,486,2145,2147,10,1068047603,1,141,'',0),(5395,161,2145,0,485,1977,2146,10,1068047603,1,141,'',0),(5394,161,1977,0,484,1153,2145,10,1068047603,1,141,'',0),(5393,161,1153,0,483,2144,1977,10,1068047603,1,141,'',0),(5392,161,2144,0,482,2143,1153,10,1068047603,1,141,'',0),(5391,161,2143,0,481,1966,2144,10,1068047603,1,141,'',0),(5390,161,1966,0,480,1979,2143,10,1068047603,1,141,'',0),(5389,161,1979,0,479,1116,1966,10,1068047603,1,141,'',0),(5388,161,1116,0,478,1974,1979,10,1068047603,1,141,'',0),(5387,161,1974,0,477,2061,1116,10,1068047603,1,141,'',0),(5386,161,2061,0,476,1977,1974,10,1068047603,1,141,'',0),(5385,161,1977,0,475,2115,2061,10,1068047603,1,141,'',0),(5384,161,2115,0,474,1402,1977,10,1068047603,1,141,'',0),(5383,161,1402,0,473,2012,2115,10,1068047603,1,141,'',0),(5382,161,2012,0,472,2142,1402,10,1068047603,1,141,'',0),(5381,161,2142,0,471,2141,2012,10,1068047603,1,141,'',0),(5380,161,2141,0,470,2140,2142,10,1068047603,1,141,'',0),(5379,161,2140,0,469,2139,2141,10,1068047603,1,141,'',0),(5378,161,2139,0,468,2015,2140,10,1068047603,1,141,'',0),(5377,161,2015,0,467,1116,2139,10,1068047603,1,141,'',0),(5376,161,1116,0,466,2138,2015,10,1068047603,1,141,'',0),(5375,161,2138,0,465,2137,1116,10,1068047603,1,141,'',0),(5374,161,2137,0,464,1116,2138,10,1068047603,1,141,'',0),(5373,161,1116,0,463,2105,2137,10,1068047603,1,141,'',0),(5372,161,2105,0,462,2136,1116,10,1068047603,1,141,'',0),(5371,161,2136,0,461,1132,2105,10,1068047603,1,141,'',0),(5370,161,1132,0,460,2135,2136,10,1068047603,1,141,'',0),(5369,161,2135,0,459,1116,1132,10,1068047603,1,141,'',0),(5368,161,1116,0,458,1999,2135,10,1068047603,1,141,'',0),(5367,161,1999,0,457,2134,1116,10,1068047603,1,141,'',0),(5366,161,2134,0,456,1132,1999,10,1068047603,1,141,'',0),(5365,161,1132,0,455,2133,2134,10,1068047603,1,141,'',0),(5364,161,2133,0,454,2132,1132,10,1068047603,1,141,'',0),(5363,161,2132,0,453,1132,2133,10,1068047603,1,141,'',0),(5362,161,1132,0,452,2131,2132,10,1068047603,1,141,'',0),(5361,161,2131,0,451,1116,1132,10,1068047603,1,141,'',0),(5360,161,1116,0,450,1999,2131,10,1068047603,1,141,'',0),(5359,161,1999,0,449,2130,1116,10,1068047603,1,141,'',0),(5358,161,2130,0,448,1986,1999,10,1068047603,1,141,'',0),(5357,161,1986,0,447,2129,2130,10,1068047603,1,141,'',0),(5356,161,2129,0,446,74,1986,10,1068047603,1,141,'',0),(5355,161,74,0,445,2001,2129,10,1068047603,1,141,'',0),(5354,161,2001,0,444,1980,74,10,1068047603,1,141,'',0),(5353,161,1980,0,443,2128,2001,10,1068047603,1,141,'',0),(5352,161,2128,0,442,2032,1980,10,1068047603,1,141,'',0),(5351,161,2032,0,441,2027,2128,10,1068047603,1,141,'',0),(5350,161,2027,0,440,2127,2032,10,1068047603,1,141,'',0),(5349,161,2127,0,439,2126,2027,10,1068047603,1,141,'',0),(5348,161,2126,0,438,2027,2127,10,1068047603,1,141,'',0),(5347,161,2027,0,437,2026,2126,10,1068047603,1,141,'',0),(5346,161,2026,0,436,2091,2027,10,1068047603,1,141,'',0),(5345,161,2091,0,435,2061,2026,10,1068047603,1,141,'',0),(5344,161,2061,0,434,1977,2091,10,1068047603,1,141,'',0),(5343,161,1977,0,433,2115,2061,10,1068047603,1,141,'',0),(5342,161,2115,0,432,2125,1977,10,1068047603,1,141,'',0),(5341,161,2125,0,431,33,2115,10,1068047603,1,141,'',0),(5340,161,33,0,430,2124,2125,10,1068047603,1,141,'',0),(5339,161,2124,0,429,1378,33,10,1068047603,1,141,'',0),(5338,161,1378,0,428,2123,2124,10,1068047603,1,141,'',0),(5337,161,2123,0,427,1402,1378,10,1068047603,1,141,'',0),(5336,161,1402,0,426,2119,2123,10,1068047603,1,141,'',0),(5335,161,2119,0,425,1310,1402,10,1068047603,1,141,'',0),(5334,161,1310,0,424,1116,2119,10,1068047603,1,141,'',0),(5333,161,1116,0,423,1132,1310,10,1068047603,1,141,'',0),(5332,161,1132,0,422,2082,1116,10,1068047603,1,141,'',0),(5331,161,2082,0,421,2122,1132,10,1068047603,1,141,'',0),(5330,161,2122,0,420,2011,2082,10,1068047603,1,141,'',0),(5329,161,2011,0,419,89,2122,10,1068047603,1,141,'',0),(5328,161,89,0,418,2010,2011,10,1068047603,1,141,'',0),(5327,161,2010,0,417,2121,89,10,1068047603,1,141,'',0),(5325,161,2022,0,415,1142,2121,10,1068047603,1,141,'',0),(5326,161,2121,0,416,2022,2010,10,1068047603,1,141,'',0),(6129,229,2309,0,4,2039,2324,22,1069768552,1,191,'',0),(6128,229,2039,0,3,2325,2309,22,1069768552,1,191,'',0),(6127,229,2325,0,2,2105,2039,22,1069768552,1,191,'',0),(6126,229,2105,0,1,2324,2325,22,1069768552,1,191,'',0),(6125,229,2324,0,0,0,2105,22,1069768552,1,191,'',0),(6124,228,2308,0,26,2055,0,22,1069767050,1,193,'',0),(6123,228,2055,0,25,2323,2308,22,1069767050,1,193,'',0),(6122,228,2323,0,24,1142,2055,22,1069767050,1,193,'',0),(6121,228,1142,0,23,74,2323,22,1069767050,1,193,'',0),(6120,228,74,0,22,2322,1142,22,1069767050,1,193,'',0),(6119,228,2322,0,21,2321,74,22,1069767050,1,193,'',0),(6114,228,1117,0,16,2254,1153,22,1069767050,1,193,'',0),(6115,228,1153,0,17,1117,2142,22,1069767050,1,193,'',0),(6116,228,2142,0,18,1153,2312,22,1069767050,1,193,'',0),(6117,228,2312,0,19,2142,2321,22,1069767050,1,193,'',0),(6118,228,2321,0,20,2312,2322,22,1069767050,1,193,'',0),(6502,231,1976,0,39,2007,1092,21,1069768747,1,189,'',0),(6501,231,2007,0,38,2006,1976,21,1069768747,1,189,'',0),(6500,231,2006,0,37,1976,2007,21,1069768747,1,189,'',0),(6494,231,2004,0,31,2003,33,21,1069768747,1,189,'',0),(6113,228,2254,0,15,2320,1117,22,1069767050,1,193,'',0),(6112,228,2320,0,14,2319,2254,22,1069767050,1,193,'',0),(6111,228,2319,0,13,2068,2320,22,1069767050,1,193,'',0),(6110,228,2068,0,12,2055,2319,22,1069767050,1,193,'',0),(6109,228,2055,0,11,2318,2068,22,1069767050,1,193,'',0),(6108,228,2318,0,10,2317,2055,22,1069767050,1,193,'',0),(6107,228,2317,0,9,1999,2318,22,1069767050,1,193,'',0),(6499,231,1976,0,36,2005,2006,21,1069768747,1,189,'',0),(6498,231,2005,0,35,1142,1976,21,1069768747,1,189,'',0),(6497,231,1142,0,34,1999,2005,21,1069768747,1,189,'',0),(6496,231,1999,0,33,33,1142,21,1069768747,1,189,'',0),(6074,227,1144,0,12,2310,2260,21,1069766947,1,189,'',0),(6073,227,2310,0,11,2309,1144,21,1069766947,1,189,'',0),(6071,227,74,0,9,2018,2309,21,1069766947,1,189,'',0),(6072,227,2309,0,10,74,2310,21,1069766947,1,189,'',0),(6495,231,33,0,32,2004,1999,21,1069768747,1,189,'',0),(6462,232,2337,0,117,1120,0,21,1069768853,1,190,'',1),(6461,232,1120,0,116,2034,2337,21,1069768853,1,189,'',0),(6492,231,2002,0,29,1999,2003,21,1069768747,1,189,'',0),(6493,231,2003,0,30,2002,2004,21,1069768747,1,189,'',0),(6019,225,2294,0,14,2293,2055,22,1069766473,1,193,'',0),(6020,225,2055,0,15,2294,2295,22,1069766473,1,193,'',0),(6017,225,1976,0,12,934,2293,22,1069766473,1,193,'',0),(6016,225,934,0,11,2292,1976,22,1069766473,1,193,'',0),(6004,224,1092,0,28,2290,0,21,1069766105,1,190,'',0),(6005,225,2287,0,0,0,74,22,1069766473,1,191,'',0),(6006,225,74,0,1,2287,2236,22,1069766473,1,191,'',0),(6007,225,2236,0,2,74,2055,22,1069766473,1,191,'',0),(6008,225,2055,0,3,2236,1142,22,1069766473,1,191,'',0),(6009,225,1142,0,4,2055,2291,22,1069766473,1,191,'',0),(6010,225,2291,0,5,1142,33,22,1069766473,1,191,'',0),(6011,225,33,0,6,2291,2018,22,1069766473,1,193,'',0),(6012,225,2018,0,7,33,2101,22,1069766473,1,193,'',0),(6013,225,2101,0,8,2018,1374,22,1069766473,1,193,'',0),(6014,225,1374,0,9,2101,2292,22,1069766473,1,193,'',0),(6015,225,2292,0,10,1374,934,22,1069766473,1,193,'',0),(6022,225,2105,0,17,2295,2296,22,1069766473,1,193,'',0),(6018,225,2293,0,13,1976,2294,22,1069766473,1,193,'',0),(6021,225,2295,0,16,2055,2105,22,1069766473,1,193,'',0),(5914,14,2260,0,4,2259,1316,4,1033920830,2,198,'',0),(5913,14,2259,0,3,1405,2260,4,1033920830,2,197,'',0),(5912,14,1405,0,2,1359,2259,4,1033920830,2,197,'',0),(6042,226,2298,0,7,934,2254,20,1069766830,1,187,'',0),(6041,226,934,0,6,2300,2298,20,1069766830,1,187,'',0),(6040,226,2300,0,5,2003,934,20,1069766830,1,187,'',0),(6039,226,2003,0,4,2062,2300,20,1069766830,1,187,'',0),(6038,226,2062,0,3,2299,2003,20,1069766830,1,187,'',0),(6037,226,2299,0,2,1081,2062,20,1069766830,1,187,'',0),(6035,226,2298,0,0,0,1081,20,1069766830,1,186,'',0),(6036,226,1081,0,1,2298,2299,20,1069766830,1,186,'',0),(6491,231,1999,0,28,1116,2002,21,1069768747,1,189,'',0),(6490,231,1116,0,27,2001,1999,21,1069768747,1,189,'',0),(6489,231,2001,0,26,1142,1116,21,1069768747,1,189,'',0),(6488,231,1142,0,25,1153,2001,21,1069768747,1,189,'',0),(6486,231,2000,0,23,1999,1153,21,1069768747,1,189,'',0),(6487,231,1153,0,24,2000,1142,21,1069768747,1,189,'',0),(6045,226,2027,0,10,1988,2301,20,1069766830,1,187,'',0),(6044,226,1988,0,9,2254,2027,20,1069766830,1,187,'',0),(6043,226,2254,0,8,2298,1988,20,1069766830,1,187,'',0),(3270,1,1094,0,9,1153,1143,1,1033917596,1,119,'',0),(3269,1,1153,0,8,1115,1094,1,1033917596,1,119,'',0),(3268,1,1115,0,7,1143,1153,1,1033917596,1,119,'',0),(3267,1,1143,0,6,1405,1115,1,1033917596,1,119,'',0),(3266,1,1405,0,5,1404,1143,1,1033917596,1,119,'',0),(3265,1,1404,0,4,1403,1405,1,1033917596,1,119,'',0),(3264,1,1403,0,3,1402,1404,1,1033917596,1,119,'',0),(3263,1,1402,0,2,1401,1403,1,1033917596,1,119,'',0),(6058,226,2304,0,23,1116,2305,20,1069766830,1,187,'',0),(6057,226,1116,0,22,2303,2304,20,1069766830,1,187,'',0),(6056,226,2303,0,21,1988,1116,20,1069766830,1,187,'',0),(6055,226,1988,0,20,2049,2303,20,1069766830,1,187,'',0),(6054,226,2049,0,19,2094,1988,20,1069766830,1,187,'',0),(6053,226,2094,0,18,2302,2049,20,1069766830,1,187,'',0),(6052,226,2302,0,17,1260,2094,20,1069766830,1,187,'',0),(6051,226,1260,0,16,1988,2302,20,1069766830,1,187,'',0),(6050,226,1988,0,15,33,1260,20,1069766830,1,187,'',0),(6049,226,33,0,14,1099,1988,20,1069766830,1,187,'',0),(3092,211,1373,0,2,1372,1374,1,1068640085,1,4,'',0),(3091,211,1372,0,1,1081,1373,1,1068640085,1,4,'',0),(3090,211,1081,0,0,0,1372,1,1068640085,1,4,'',0),(3093,211,1374,0,3,1373,1375,1,1068640085,1,119,'',0),(3094,211,1375,0,4,1374,73,1,1068640085,1,119,'',0),(3095,211,73,0,5,1375,1376,1,1068640085,1,119,'',0),(3096,211,1376,0,6,73,1377,1,1068640085,1,119,'',0),(3097,211,1377,0,7,1376,1260,1,1068640085,1,119,'',0),(3098,211,1260,0,8,1377,1378,1,1068640085,1,119,'',0),(3099,211,1378,0,9,1260,1379,1,1068640085,1,119,'',0),(3100,211,1379,0,10,1378,0,1,1068640085,1,119,'',0),(6460,232,2034,0,115,1142,1120,21,1069768853,1,189,'',0),(6459,232,1142,0,114,1153,2034,21,1069768853,1,189,'',0),(6458,232,1153,0,113,2016,1142,21,1069768853,1,189,'',0),(6457,232,2016,0,112,2049,1153,21,1069768853,1,189,'',0),(6456,232,2049,0,111,2045,2016,21,1069768853,1,189,'',0),(6455,232,2045,0,110,2048,2049,21,1069768853,1,189,'',0),(6454,232,2048,0,109,2047,2045,21,1069768853,1,189,'',0),(6453,232,2047,0,108,1378,2048,21,1069768853,1,189,'',0),(6452,232,1378,0,107,1402,2047,21,1069768853,1,189,'',0),(6451,232,1402,0,106,2046,1378,21,1069768853,1,189,'',0),(6450,232,2046,0,105,2017,1402,21,1069768853,1,189,'',0),(6449,232,2017,0,104,2045,2046,21,1069768853,1,189,'',0),(6448,232,2045,0,103,77,2017,21,1069768853,1,189,'',0),(6447,232,77,0,102,2044,2045,21,1069768853,1,189,'',0),(6446,232,2044,0,101,2018,77,21,1069768853,1,189,'',0),(6445,232,2018,0,100,2043,2044,21,1069768853,1,189,'',0),(6444,232,2043,0,99,2042,2018,21,1069768853,1,189,'',0),(6443,232,2042,0,98,1117,2043,21,1069768853,1,189,'',0),(6442,232,1117,0,97,2016,2042,21,1069768853,1,189,'',0),(6441,232,2016,0,96,51,1117,21,1069768853,1,189,'',0),(6440,232,51,0,95,2041,2016,21,1069768853,1,189,'',0),(6439,232,2041,0,94,1977,51,21,1069768853,1,189,'',0),(6438,232,1977,0,93,2040,2041,21,1069768853,1,189,'',0),(6437,232,2040,0,92,1081,1977,21,1069768853,1,189,'',0),(6436,232,1081,0,91,2009,2040,21,1069768853,1,189,'',0),(6435,232,2009,0,90,1142,1081,21,1069768853,1,189,'',0),(6434,232,1142,0,89,1153,2009,21,1069768853,1,189,'',0),(6433,232,1153,0,88,2018,1142,21,1069768853,1,189,'',0),(6432,232,2018,0,87,2016,1153,21,1069768853,1,189,'',0),(6431,232,2016,0,86,1116,2018,21,1069768853,1,189,'',0),(6430,232,1116,0,85,1974,2016,21,1069768853,1,189,'',0),(6429,232,1974,0,84,2039,1116,21,1069768853,1,189,'',0),(6428,232,2039,0,83,2038,1974,21,1069768853,1,189,'',0),(6427,232,2038,0,82,74,2039,21,1069768853,1,189,'',0),(6485,231,1999,0,22,1116,2000,21,1069768747,1,189,'',0),(6484,231,1116,0,21,1431,1999,21,1069768747,1,189,'',0),(6483,231,1431,0,20,1998,1116,21,1069768747,1,189,'',0),(6482,231,1998,0,19,1431,1431,21,1069768747,1,189,'',0),(6481,231,1431,0,18,381,1998,21,1069768747,1,189,'',0),(6480,231,381,0,17,1997,1431,21,1069768747,1,189,'',0),(6479,231,1997,0,16,2338,381,21,1069768747,1,189,'',0),(6478,231,2338,0,15,1142,1997,21,1069768747,1,189,'',0),(6477,231,1142,0,14,1994,2338,21,1069768747,1,189,'',0),(6476,231,1994,0,13,1989,1142,21,1069768747,1,189,'',0),(6475,231,1989,0,12,1359,1994,21,1069768747,1,189,'',0),(6474,231,1359,0,11,195,1989,21,1069768747,1,189,'',0),(6473,231,195,0,10,89,1359,21,1069768747,1,189,'',0),(6472,231,89,0,9,1993,195,21,1069768747,1,189,'',0),(6471,231,1993,0,8,1988,89,21,1069768747,1,189,'',0),(6470,231,1988,0,7,1116,1993,21,1069768747,1,189,'',0),(6469,231,1116,0,6,1359,1988,21,1069768747,1,189,'',0),(6468,231,1359,0,5,195,1116,21,1069768747,1,188,'',0),(6467,231,195,0,4,89,1359,21,1069768747,1,188,'',0),(6466,231,89,0,3,1993,195,21,1069768747,1,188,'',0),(6464,231,1402,0,1,1986,1993,21,1069768747,1,188,'',0),(6465,231,1993,0,2,1402,89,21,1069768747,1,188,'',0),(6173,230,1153,0,9,1975,73,1,1069768639,1,119,'',0),(6172,230,1975,0,8,2239,1153,1,1069768639,1,119,'',0),(6171,230,2239,0,7,1116,1975,1,1069768639,1,119,'',0),(6170,230,1116,0,6,2334,2239,1,1069768639,1,119,'',0),(6169,230,2334,0,5,2335,1116,1,1069768639,1,119,'',0),(6168,230,2335,0,4,2105,2334,1,1069768639,1,119,'',0),(6167,230,2105,0,3,2176,2335,1,1069768639,1,119,'',0),(6166,230,2176,0,2,2334,2105,1,1069768639,1,119,'',0),(6165,230,2334,0,1,1081,2176,1,1069768639,1,4,'',0),(6164,230,1081,0,0,0,2334,1,1069768639,1,4,'',0),(6163,229,2333,0,38,1142,0,22,1069768552,1,193,'',0),(6162,229,1142,0,37,1153,2333,22,1069768552,1,193,'',0),(6161,229,1153,0,36,2307,1142,22,1069768552,1,193,'',0),(6160,229,2307,0,35,2253,1153,22,1069768552,1,193,'',0),(6159,229,2253,0,34,1142,2307,22,1069768552,1,193,'',0),(6158,229,1142,0,33,74,2253,22,1069768552,1,193,'',0),(6157,229,74,0,32,2324,1142,22,1069768552,1,193,'',0),(6156,229,2324,0,31,2157,74,22,1069768552,1,193,'',0),(6155,229,2157,0,30,1153,2324,22,1069768552,1,193,'',0),(6154,229,1153,0,29,2309,2157,22,1069768552,1,193,'',0),(6153,229,2309,0,28,2039,1153,22,1069768552,1,193,'',0),(6152,229,2039,0,27,2325,2309,22,1069768552,1,193,'',0),(6151,229,2325,0,26,74,2039,22,1069768552,1,193,'',0),(6150,229,74,0,25,2324,2325,22,1069768552,1,193,'',0),(6149,229,2324,0,24,2332,74,22,1069768552,1,193,'',0),(6148,229,2332,0,23,2331,2324,22,1069768552,1,193,'',0),(6147,229,2331,0,22,2027,2332,22,1069768552,1,193,'',0),(6146,229,2027,0,21,2330,2331,22,1069768552,1,193,'',0),(6145,229,2330,0,20,2107,2027,22,1069768552,1,193,'',0),(6144,229,2107,0,19,2321,2330,22,1069768552,1,193,'',0),(6143,229,2321,0,18,2156,2107,22,1069768552,1,193,'',0),(6142,229,2156,0,17,33,2321,22,1069768552,1,193,'',0),(6141,229,33,0,16,2329,2156,22,1069768552,1,193,'',0),(6140,229,2329,0,15,1142,33,22,1069768552,1,193,'',0),(6139,229,1142,0,14,1153,2329,22,1069768552,1,193,'',0),(6138,229,1153,0,13,2328,1142,22,1069768552,1,193,'',0),(6137,229,2328,0,12,2312,1153,22,1069768552,1,193,'',0),(6136,229,2312,0,11,2055,2328,22,1069768552,1,193,'',0),(6135,229,2055,0,10,2327,2312,22,1069768552,1,193,'',0),(6134,229,2327,0,9,89,2055,22,1069768552,1,193,'',0),(6133,229,89,0,8,2309,2327,22,1069768552,1,193,'',0),(6132,229,2309,0,7,2326,89,22,1069768552,1,193,'',0),(6130,229,2324,0,5,2309,2326,22,1069768552,1,193,'',0),(6131,229,2326,0,6,2324,2309,22,1069768552,1,193,'',0),(6463,231,1986,0,0,0,1402,21,1069768747,1,188,'',0),(6185,230,2195,0,21,1378,0,1,1069768639,1,119,'',0),(6184,230,1378,0,20,1988,2195,1,1069768639,1,119,'',0),(6183,230,1988,0,19,1116,1378,1,1069768639,1,119,'',0),(6182,230,1116,0,18,1970,1988,1,1069768639,1,119,'',0),(6181,230,1970,0,17,2334,1116,1,1069768639,1,119,'',0),(6180,230,2334,0,16,1962,1970,1,1069768639,1,119,'',0),(6179,230,1962,0,15,1975,2334,1,1069768639,1,119,'',0),(6178,230,1975,0,14,2239,1962,1,1069768639,1,119,'',0),(6177,230,2239,0,13,1116,1975,1,1069768639,1,119,'',0),(6176,230,1116,0,12,1081,2239,1,1069768639,1,119,'',0),(6175,230,1081,0,11,73,1116,1,1069768639,1,119,'',0),(6174,230,73,0,10,1153,1081,1,1069768639,1,119,'',0),(3262,1,1401,0,1,1081,1402,1,1033917596,1,119,'',0),(3261,1,1081,0,0,0,1401,1,1033917596,1,4,'',0),(6526,56,2358,0,6,1431,2359,15,1066643397,11,202,'',0),(6525,56,1431,0,5,2357,2358,15,1066643397,11,202,'',0),(6524,56,2357,0,4,2356,1431,15,1066643397,11,202,'',0),(6523,56,2356,0,3,2355,2357,15,1066643397,11,202,'',0),(6522,56,2355,0,2,2354,2356,15,1066643397,11,202,'',0),(6521,56,2354,0,1,2353,2355,15,1066643397,11,202,'',0),(6520,56,2353,0,0,0,2354,15,1066643397,11,161,'',0),(5324,161,1142,0,414,1402,2022,10,1068047603,1,141,'',0),(5323,161,1402,0,413,2069,1142,10,1068047603,1,141,'',0),(5322,161,2069,0,412,74,1402,10,1068047603,1,141,'',0),(5321,161,74,0,411,2120,2069,10,1068047603,1,141,'',0),(5320,161,2120,0,410,1402,74,10,1068047603,1,141,'',0),(5319,161,1402,0,409,2119,2120,10,1068047603,1,141,'',0),(5318,161,2119,0,408,1310,1402,10,1068047603,1,141,'',0),(5317,161,1310,0,407,1116,2119,10,1068047603,1,141,'',0),(5316,161,1116,0,406,1132,1310,10,1068047603,1,141,'',0),(5315,161,1132,0,405,2055,1116,10,1068047603,1,141,'',0),(5314,161,2055,0,404,2118,1132,10,1068047603,1,141,'',0),(5313,161,2118,0,403,2079,2055,10,1068047603,1,141,'',0),(5312,161,2079,0,402,89,2118,10,1068047603,1,141,'',0),(5311,161,89,0,401,2117,2079,10,1068047603,1,141,'',0),(5310,161,2117,0,400,2116,89,10,1068047603,1,141,'',0),(5309,161,2116,0,399,1977,2117,10,1068047603,1,141,'',0),(5308,161,1977,0,398,2115,2116,10,1068047603,1,141,'',0),(5307,161,2115,0,397,2093,1977,10,1068047603,1,141,'',0),(5306,161,2093,0,396,1402,2115,10,1068047603,1,141,'',0),(5305,161,1402,0,395,2114,2093,10,1068047603,1,141,'',0),(5304,161,2114,0,394,1378,1402,10,1068047603,1,141,'',0),(5303,161,1378,0,393,1984,2114,10,1068047603,1,141,'',0),(5302,161,1984,0,392,1983,1378,10,1068047603,1,141,'',0),(5301,161,1983,0,391,2107,1984,10,1068047603,1,141,'',0),(5300,161,2107,0,390,2113,1983,10,1068047603,1,141,'',0),(5299,161,2113,0,389,2036,2107,10,1068047603,1,141,'',0),(5298,161,2036,0,388,2078,2113,10,1068047603,1,141,'',0),(5297,161,2078,0,387,1402,2036,10,1068047603,1,141,'',0),(5296,161,1402,0,386,2112,2078,10,1068047603,1,141,'',0),(5295,161,2112,0,385,2110,1402,10,1068047603,1,141,'',0),(5294,161,2110,0,384,2010,2112,10,1068047603,1,141,'',0),(5293,161,2010,0,383,1988,2110,10,1068047603,1,141,'',0),(5292,161,1988,0,382,2107,2010,10,1068047603,1,141,'',0),(5291,161,2107,0,381,2111,1988,10,1068047603,1,141,'',0),(5290,161,2111,0,380,2110,2107,10,1068047603,1,141,'',0),(5289,161,2110,0,379,2109,2111,10,1068047603,1,141,'',0),(5288,161,2109,0,378,74,2110,10,1068047603,1,141,'',0),(5287,161,74,0,377,2022,2109,10,1068047603,1,141,'',0),(5286,161,2022,0,376,1142,74,10,1068047603,1,141,'',0),(5285,161,1142,0,375,1976,2022,10,1068047603,1,141,'',0),(5284,161,1976,0,374,2108,1142,10,1068047603,1,141,'',0),(5283,161,2108,0,373,1988,1976,10,1068047603,1,141,'',0),(5282,161,1988,0,372,2080,2108,10,1068047603,1,141,'',0),(5281,161,2080,0,371,1977,1988,10,1068047603,1,141,'',0),(5280,161,1977,0,370,1144,2080,10,1068047603,1,141,'',0),(5279,161,1144,0,369,2107,1977,10,1068047603,1,141,'',0),(5278,161,2107,0,368,1974,1144,10,1068047603,1,141,'',0),(5277,161,1974,0,367,2078,2107,10,1068047603,1,141,'',0),(5276,161,2078,0,366,1988,1974,10,1068047603,1,141,'',0),(5275,161,1988,0,365,2107,2078,10,1068047603,1,141,'',0),(5274,161,2107,0,364,2106,1988,10,1068047603,1,141,'',0),(5273,161,2106,0,363,74,2107,10,1068047603,1,141,'',0),(5272,161,74,0,362,2017,2106,10,1068047603,1,141,'',0),(5271,161,2017,0,361,2105,74,10,1068047603,1,141,'',0),(5270,161,2105,0,360,1116,2017,10,1068047603,1,141,'',0),(5269,161,1116,0,359,2022,2105,10,1068047603,1,141,'',0),(5268,161,2022,0,358,1142,1116,10,1068047603,1,141,'',0),(5267,161,1142,0,357,1974,2022,10,1068047603,1,141,'',0),(5266,161,1974,0,356,2104,1142,10,1068047603,1,141,'',0),(5265,161,2104,0,355,2103,1974,10,1068047603,1,141,'',0),(5264,161,2103,0,354,89,2104,10,1068047603,1,141,'',0),(5263,161,89,0,353,2102,2103,10,1068047603,1,141,'',0),(5262,161,2102,0,352,1117,89,10,1068047603,1,141,'',0),(5261,161,1117,0,351,2080,2102,10,1068047603,1,141,'',0),(5260,161,2080,0,350,1977,1117,10,1068047603,1,141,'',0),(5259,161,1977,0,349,1964,2080,10,1068047603,1,141,'',0),(5258,161,1964,0,348,2098,1977,10,1068047603,1,141,'',0),(5257,161,2098,0,347,934,1964,10,1068047603,1,141,'',0),(5256,161,934,0,346,2101,2098,10,1068047603,1,141,'',0),(5255,161,2101,0,345,2018,934,10,1068047603,1,141,'',0),(5254,161,2018,0,344,2100,2101,10,1068047603,1,141,'',0),(5253,161,2100,0,343,1982,2018,10,1068047603,1,141,'',0),(5252,161,1982,0,342,1976,2100,10,1068047603,1,141,'',0),(5251,161,1976,0,341,2099,1982,10,1068047603,1,141,'',0),(5250,161,2099,0,340,1116,1976,10,1068047603,1,141,'',0),(5249,161,1116,0,339,2027,2099,10,1068047603,1,141,'',0),(5248,161,2027,0,338,2026,1116,10,1068047603,1,141,'',0),(5247,161,2026,0,337,1081,2027,10,1068047603,1,141,'',0),(5246,161,1081,0,336,2098,2026,10,1068047603,1,141,'',0),(5245,161,2098,0,335,1142,1081,10,1068047603,1,141,'',0),(5244,161,1142,0,334,1153,2098,10,1068047603,1,141,'',0),(5243,161,1153,0,333,2017,1142,10,1068047603,1,141,'',0),(5242,161,2017,0,332,1310,1153,10,1068047603,1,141,'',0),(5241,161,1310,0,331,1116,2017,10,1068047603,1,141,'',0),(5240,161,1116,0,330,1974,1310,10,1068047603,1,141,'',0),(5239,161,1974,0,329,33,1116,10,1068047603,1,141,'',0),(5238,161,33,0,328,2093,1974,10,1068047603,1,141,'',0),(5237,161,2093,0,327,2097,33,10,1068047603,1,141,'',0),(5236,161,2097,0,326,2096,2093,10,1068047603,1,141,'',0),(5235,161,2096,0,325,2080,2097,10,1068047603,1,141,'',0),(5234,161,2080,0,324,1142,2096,10,1068047603,1,141,'',0),(5233,161,1142,0,323,2095,2080,10,1068047603,1,141,'',0),(5232,161,2095,0,322,2049,1142,10,1068047603,1,141,'',0),(5231,161,2049,0,321,2094,2095,10,1068047603,1,141,'',0),(5230,161,2094,0,320,2093,2049,10,1068047603,1,141,'',0),(5229,161,2093,0,319,2027,2094,10,1068047603,1,141,'',0),(5228,161,2027,0,318,2092,2093,10,1068047603,1,141,'',0),(5227,161,2092,0,317,2090,2027,10,1068047603,1,141,'',0),(5226,161,2090,0,316,2091,2092,10,1068047603,1,141,'',0),(5225,161,2091,0,315,1970,2090,10,1068047603,1,141,'',0),(5224,161,1970,0,314,2090,2091,10,1068047603,1,141,'',0),(5223,161,2090,0,313,2089,1970,10,1068047603,1,141,'',0),(5222,161,2089,0,312,2088,2090,10,1068047603,1,141,'',0),(5221,161,2088,0,311,2087,2089,10,1068047603,1,141,'',0),(5220,161,2087,0,310,2086,2088,10,1068047603,1,141,'',0),(5219,161,2086,0,309,2085,2087,10,1068047603,1,141,'',0),(5218,161,2085,0,308,2064,2086,10,1068047603,1,141,'',0),(5217,161,2064,0,307,1117,2085,10,1068047603,1,141,'',0),(5216,161,1117,0,306,2022,2064,10,1068047603,1,141,'',0),(5215,161,2022,0,305,1977,1117,10,1068047603,1,141,'',0),(5214,161,1977,0,304,2037,2022,10,1068047603,1,141,'',0),(5213,161,2037,0,303,2084,1977,10,1068047603,1,141,'',0),(5212,161,2084,0,302,2036,2037,10,1068047603,1,141,'',0),(5211,161,2036,0,301,2001,2084,10,1068047603,1,141,'',0),(5210,161,2001,0,300,2083,2036,10,1068047603,1,141,'',0),(5209,161,2083,0,299,1142,2001,10,1068047603,1,141,'',0),(5208,161,1142,0,298,74,2083,10,1068047603,1,141,'',0),(5207,161,74,0,297,2018,1142,10,1068047603,1,141,'',0),(5206,161,2018,0,296,1266,74,10,1068047603,1,141,'',0),(5205,161,1266,0,295,74,2018,10,1068047603,1,141,'',0),(5204,161,74,0,294,2080,1266,10,1068047603,1,141,'',0),(5203,161,2080,0,293,2082,74,10,1068047603,1,141,'',0),(5202,161,2082,0,292,33,2080,10,1068047603,1,141,'',0),(5201,161,33,0,291,2079,2082,10,1068047603,1,141,'',0),(5200,161,2079,0,290,89,33,10,1068047603,1,141,'',0),(5199,161,89,0,289,2081,2079,10,1068047603,1,141,'',0),(5198,161,2081,0,288,2080,89,10,1068047603,1,141,'',0),(5197,161,2080,0,287,2079,2081,10,1068047603,1,141,'',0),(5196,161,2079,0,286,89,2080,10,1068047603,1,141,'',0),(5195,161,89,0,285,2008,2079,10,1068047603,1,141,'',0),(5194,161,2008,0,284,2022,89,10,1068047603,1,141,'',0),(5193,161,2022,0,283,1977,2008,10,1068047603,1,141,'',0),(5192,161,1977,0,282,2078,2022,10,1068047603,1,141,'',0),(5191,161,2078,0,281,1402,1977,10,1068047603,1,141,'',0),(5190,161,1402,0,280,1081,2078,10,1068047603,1,141,'',0),(5189,161,1081,0,279,89,1402,10,1068047603,1,141,'',0),(5188,161,89,0,278,2077,1081,10,1068047603,1,141,'',0),(5187,161,2077,0,277,2018,89,10,1068047603,1,141,'',0),(5186,161,2018,0,276,2076,2077,10,1068047603,1,141,'',0),(5185,161,2076,0,275,1402,2018,10,1068047603,1,141,'',0),(5184,161,1402,0,274,1999,2076,10,1068047603,1,141,'',0),(5183,161,1999,0,273,2036,1402,10,1068047603,1,141,'',0),(5182,161,2036,0,272,2015,1999,10,1068047603,1,141,'',0),(5181,161,2015,0,271,2075,2036,10,1068047603,1,141,'',0),(5180,161,2075,0,270,2074,2015,10,1068047603,1,141,'',0),(5179,161,2074,0,269,2073,2075,10,1068047603,1,141,'',0),(5178,161,2073,0,268,2072,2074,10,1068047603,1,141,'',0),(5177,161,2072,0,267,74,2073,10,1068047603,1,141,'',0),(5176,161,74,0,266,2018,2072,10,1068047603,1,141,'',0),(5175,161,2018,0,265,1974,74,10,1068047603,1,141,'',0),(5174,161,1974,0,264,2065,2018,10,1068047603,1,141,'',0),(5173,161,2065,0,263,2071,1974,10,1068047603,1,141,'',0),(5172,161,2071,0,262,2070,2065,10,1068047603,1,141,'',0),(5171,161,2070,0,261,2069,2071,10,1068047603,1,141,'',0),(5170,161,2069,0,260,77,2070,10,1068047603,1,141,'',0),(5169,161,77,0,259,2068,2069,10,1068047603,1,141,'',0),(5168,161,2068,0,258,2050,77,10,1068047603,1,141,'',0),(5167,161,2050,0,257,33,2068,10,1068047603,1,141,'',0),(5166,161,33,0,256,2067,2050,10,1068047603,1,141,'',0),(5165,161,2067,0,255,2066,33,10,1068047603,1,141,'',0),(5164,161,2066,0,254,2065,2067,10,1068047603,1,141,'',0),(5163,161,2065,0,253,33,2066,10,1068047603,1,141,'',0),(5162,161,33,0,252,1966,2065,10,1068047603,1,141,'',0),(5161,161,1966,0,251,2064,33,10,1068047603,1,141,'',0),(5160,161,2064,0,250,1964,1966,10,1068047603,1,141,'',0),(5159,161,1964,0,249,2015,2064,10,1068047603,1,141,'',0),(5158,161,2015,0,248,1116,1964,10,1068047603,1,141,'',0),(5157,161,1116,0,247,1985,2015,10,1068047603,1,141,'',0),(5156,161,1985,0,246,2063,1116,10,1068047603,1,141,'',0),(5155,161,2063,0,245,2062,1985,10,1068047603,1,141,'',0),(5154,161,2062,0,244,2061,2063,10,1068047603,1,141,'',0),(5153,161,2061,0,243,2060,2062,10,1068047603,1,141,'',0),(5152,161,2060,0,242,1977,2061,10,1068047603,1,141,'',0),(5151,161,1977,0,241,2059,2060,10,1068047603,1,141,'',0),(5150,161,2059,0,240,1986,1977,10,1068047603,1,141,'',0),(5149,161,1986,0,239,2058,2059,10,1068047603,1,141,'',0),(5148,161,2058,0,238,1402,1986,10,1068047603,1,141,'',0),(5147,161,1402,0,237,2057,2058,10,1068047603,1,141,'',0),(5146,161,2057,0,236,1378,1402,10,1068047603,1,141,'',0),(5145,161,1378,0,235,2056,2057,10,1068047603,1,141,'',0),(5144,161,2056,0,234,1116,1378,10,1068047603,1,141,'',0),(5143,161,1116,0,233,1081,2056,10,1068047603,1,141,'',0),(5142,161,1081,0,232,1142,1116,10,1068047603,1,141,'',0),(5141,161,1142,0,231,2055,1081,10,1068047603,1,141,'',0),(5140,161,2055,0,230,2054,1142,10,1068047603,1,141,'',0),(5139,161,2054,0,229,1142,2055,10,1068047603,1,141,'',0),(5138,161,1142,0,228,2050,2054,10,1068047603,1,141,'',0),(5137,161,2050,0,227,1402,1142,10,1068047603,1,141,'',0),(5136,161,1402,0,226,2053,2050,10,1068047603,1,141,'',0),(5135,161,2053,0,225,2022,1402,10,1068047603,1,141,'',0),(5134,161,2022,0,224,1977,2053,10,1068047603,1,141,'',0),(5133,161,1977,0,223,2052,2022,10,1068047603,1,141,'',0),(5132,161,2052,0,222,1116,1977,10,1068047603,1,141,'',0),(5131,161,1116,0,221,2051,2052,10,1068047603,1,141,'',0),(5130,161,2051,0,220,1120,1116,10,1068047603,1,141,'',0),(5129,161,1120,0,219,1142,2051,10,1068047603,1,141,'',0),(5128,161,1142,0,218,2050,1120,10,1068047603,1,141,'',0),(5127,161,2050,0,217,1120,1142,10,1068047603,1,141,'',0),(5126,161,1120,0,216,2034,2050,10,1068047603,1,141,'',0),(5125,161,2034,0,215,1142,1120,10,1068047603,1,141,'',0),(5124,161,1142,0,214,1153,2034,10,1068047603,1,141,'',0),(5123,161,1153,0,213,2016,1142,10,1068047603,1,141,'',0),(5122,161,2016,0,212,2049,1153,10,1068047603,1,141,'',0),(5121,161,2049,0,211,2045,2016,10,1068047603,1,141,'',0),(5120,161,2045,0,210,2048,2049,10,1068047603,1,141,'',0),(5119,161,2048,0,209,2047,2045,10,1068047603,1,141,'',0),(5118,161,2047,0,208,1378,2048,10,1068047603,1,141,'',0),(5117,161,1378,0,207,1402,2047,10,1068047603,1,141,'',0),(5116,161,1402,0,206,2046,1378,10,1068047603,1,141,'',0),(5115,161,2046,0,205,2017,1402,10,1068047603,1,141,'',0),(5114,161,2017,0,204,2045,2046,10,1068047603,1,141,'',0),(5113,161,2045,0,203,77,2017,10,1068047603,1,141,'',0),(5112,161,77,0,202,2044,2045,10,1068047603,1,141,'',0),(5111,161,2044,0,201,2018,77,10,1068047603,1,141,'',0),(5110,161,2018,0,200,2043,2044,10,1068047603,1,141,'',0),(5109,161,2043,0,199,2042,2018,10,1068047603,1,141,'',0),(5108,161,2042,0,198,1117,2043,10,1068047603,1,141,'',0),(5107,161,1117,0,197,2016,2042,10,1068047603,1,141,'',0),(5106,161,2016,0,196,51,1117,10,1068047603,1,141,'',0),(5105,161,51,0,195,2041,2016,10,1068047603,1,141,'',0),(5104,161,2041,0,194,1977,51,10,1068047603,1,141,'',0),(5103,161,1977,0,193,2040,2041,10,1068047603,1,141,'',0),(5102,161,2040,0,192,1081,1977,10,1068047603,1,141,'',0),(5101,161,1081,0,191,2009,2040,10,1068047603,1,141,'',0),(5100,161,2009,0,190,1142,1081,10,1068047603,1,141,'',0),(5099,161,1142,0,189,1153,2009,10,1068047603,1,141,'',0),(5098,161,1153,0,188,2018,1142,10,1068047603,1,141,'',0),(5097,161,2018,0,187,2016,1153,10,1068047603,1,141,'',0),(5096,161,2016,0,186,1116,2018,10,1068047603,1,141,'',0),(5095,161,1116,0,185,1974,2016,10,1068047603,1,141,'',0),(5094,161,1974,0,184,2039,1116,10,1068047603,1,141,'',0),(5093,161,2039,0,183,2038,1974,10,1068047603,1,141,'',0),(5092,161,2038,0,182,74,2039,10,1068047603,1,141,'',0),(5091,161,74,0,181,2018,2038,10,1068047603,1,141,'',0),(5090,161,2018,0,180,2037,74,10,1068047603,1,141,'',0),(5089,161,2037,0,179,1984,2018,10,1068047603,1,141,'',0),(5088,161,1984,0,178,2036,2037,10,1068047603,1,141,'',0),(5087,161,2036,0,177,77,1984,10,1068047603,1,141,'',0),(5086,161,77,0,176,2035,2036,10,1068047603,1,141,'',0),(5085,161,2035,0,175,1142,77,10,1068047603,1,141,'',0),(5084,161,1142,0,174,1081,2035,10,1068047603,1,141,'',0),(5083,161,1081,0,173,2034,1142,10,1068047603,1,141,'',0),(5082,161,2034,0,172,1142,1081,10,1068047603,1,141,'',0),(5081,161,1142,0,171,1402,2034,10,1068047603,1,141,'',0),(5080,161,1402,0,170,2016,1142,10,1068047603,1,141,'',0),(5079,161,2016,0,169,1116,1402,10,1068047603,1,141,'',0),(5078,161,1116,0,168,1974,2016,10,1068047603,1,141,'',0),(5077,161,1974,0,167,73,1116,10,1068047603,1,141,'',0),(5076,161,73,0,166,2013,1974,10,1068047603,1,141,'',0),(5075,161,2013,0,165,2033,73,10,1068047603,1,141,'',0),(5074,161,2033,0,164,2032,2013,10,1068047603,1,141,'',0),(5073,161,2032,0,163,2027,2033,10,1068047603,1,141,'',0),(5072,161,2027,0,162,2031,2032,10,1068047603,1,141,'',0),(5071,161,2031,0,161,2030,2027,10,1068047603,1,141,'',0),(5070,161,2030,0,160,1970,2031,10,1068047603,1,141,'',0),(5069,161,1970,0,159,2019,2030,10,1068047603,1,141,'',0),(5068,161,2019,0,158,1153,1970,10,1068047603,1,141,'',0),(5067,161,1153,0,157,2018,2019,10,1068047603,1,141,'',0),(5066,161,2018,0,156,2017,1153,10,1068047603,1,141,'',0),(5065,161,2017,0,155,1081,2018,10,1068047603,1,141,'',0),(5064,161,1081,0,154,2021,2017,10,1068047603,1,141,'',0),(5063,161,2021,0,153,1142,1081,10,1068047603,1,141,'',0),(5062,161,1142,0,152,1153,2021,10,1068047603,1,141,'',0),(5061,161,1153,0,151,2029,1142,10,1068047603,1,141,'',0),(5060,161,2029,0,150,2022,1153,10,1068047603,1,141,'',0),(5059,161,2022,0,149,1977,2029,10,1068047603,1,141,'',0),(5058,161,1977,0,148,1974,2022,10,1068047603,1,141,'',0),(5057,161,1974,0,147,1120,1977,10,1068047603,1,141,'',0),(5056,161,1120,0,146,1119,1974,10,1068047603,1,141,'',0),(5055,161,1119,0,145,2028,1120,10,1068047603,1,141,'',0),(5054,161,2028,0,144,1153,1119,10,1068047603,1,141,'',0),(5053,161,1153,0,143,2016,2028,10,1068047603,1,141,'',0),(5052,161,2016,0,142,2027,1153,10,1068047603,1,141,'',0),(5051,161,2027,0,141,2026,2016,10,1068047603,1,141,'',0),(5050,161,2026,0,140,2025,2027,10,1068047603,1,141,'',0),(5049,161,2025,0,139,2024,2026,10,1068047603,1,141,'',0),(5048,161,2024,0,138,2023,2025,10,1068047603,1,141,'',0),(5047,161,2023,0,137,934,2024,10,1068047603,1,141,'',0),(5046,161,934,0,136,74,2023,10,1068047603,1,141,'',0),(5045,161,74,0,135,2022,934,10,1068047603,1,141,'',0),(5044,161,2022,0,134,1116,74,10,1068047603,1,141,'',0),(5043,161,1116,0,133,1974,2022,10,1068047603,1,141,'',0),(5042,161,1974,0,132,1081,1116,10,1068047603,1,141,'',0),(5041,161,1081,0,131,2021,1974,10,1068047603,1,141,'',0),(5040,161,2021,0,130,1142,1081,10,1068047603,1,141,'',0),(5039,161,1142,0,129,1402,2021,10,1068047603,1,141,'',0),(5038,161,1402,0,128,2017,1142,10,1068047603,1,141,'',0),(5037,161,2017,0,127,1431,1402,10,1068047603,1,141,'',0),(5036,161,1431,0,126,2020,2017,10,1068047603,1,141,'',0),(5035,161,2020,0,125,1431,1431,10,1068047603,1,141,'',0),(5034,161,1431,0,124,1260,2020,10,1068047603,1,141,'',0),(5033,161,1260,0,123,74,1431,10,1068047603,1,141,'',0),(5032,161,74,0,122,1081,1260,10,1068047603,1,141,'',0),(5031,161,1081,0,121,2019,74,10,1068047603,1,141,'',0),(5030,161,2019,0,120,89,1081,10,1068047603,1,141,'',0),(5029,161,89,0,119,1402,2019,10,1068047603,1,141,'',0),(5028,161,1402,0,118,2018,89,10,1068047603,1,141,'',0),(5027,161,2018,0,117,2017,1402,10,1068047603,1,141,'',0),(5026,161,2017,0,116,1402,2018,10,1068047603,1,141,'',0),(5025,161,1402,0,115,2016,2017,10,1068047603,1,141,'',0),(5024,161,2016,0,114,2015,1402,10,1068047603,1,141,'',0),(5023,161,2015,0,113,1116,2016,10,1068047603,1,141,'',0),(5022,161,1116,0,112,1081,2015,10,1068047603,1,141,'',0),(5021,161,1081,0,111,2014,1116,10,1068047603,1,141,'',0),(5020,161,2014,0,110,2013,1081,10,1068047603,1,141,'',0),(5019,161,2013,0,109,1402,2014,10,1068047603,1,141,'',0),(5018,161,1402,0,108,2012,2013,10,1068047603,1,141,'',0),(5017,161,2012,0,107,2011,1402,10,1068047603,1,141,'',0),(5016,161,2011,0,106,89,2012,10,1068047603,1,141,'',0),(5015,161,89,0,105,2010,2011,10,1068047603,1,141,'',0),(5014,161,2010,0,104,1081,89,10,1068047603,1,141,'',0),(5013,161,1081,0,103,2009,2010,10,1068047603,1,141,'',0),(5012,161,2009,0,102,1142,1081,10,1068047603,1,141,'',0),(5011,161,1142,0,101,2008,2009,10,1068047603,1,141,'',0),(5010,161,2008,0,100,1976,1142,10,1068047603,1,141,'',0),(5009,161,1976,0,99,2007,2008,10,1068047603,1,141,'',0),(5008,161,2007,0,98,2006,1976,10,1068047603,1,141,'',0),(5007,161,2006,0,97,1976,2007,10,1068047603,1,141,'',0),(5006,161,1976,0,96,2005,2006,10,1068047603,1,141,'',0),(5005,161,2005,0,95,1142,1976,10,1068047603,1,141,'',0),(5004,161,1142,0,94,1999,2005,10,1068047603,1,141,'',0),(5003,161,1999,0,93,33,1142,10,1068047603,1,141,'',0),(5002,161,33,0,92,2004,1999,10,1068047603,1,141,'',0),(5001,161,2004,0,91,2003,33,10,1068047603,1,141,'',0),(5000,161,2003,0,90,2002,2004,10,1068047603,1,141,'',0),(4999,161,2002,0,89,1999,2003,10,1068047603,1,141,'',0),(4998,161,1999,0,88,1116,2002,10,1068047603,1,141,'',0),(4997,161,1116,0,87,2001,1999,10,1068047603,1,141,'',0),(4996,161,2001,0,86,1142,1116,10,1068047603,1,141,'',0),(4995,161,1142,0,85,1153,2001,10,1068047603,1,141,'',0),(4994,161,1153,0,84,2000,1142,10,1068047603,1,141,'',0),(4993,161,2000,0,83,1999,1153,10,1068047603,1,141,'',0),(4992,161,1999,0,82,1116,2000,10,1068047603,1,141,'',0),(4991,161,1116,0,81,1431,1999,10,1068047603,1,141,'',0),(4990,161,1431,0,80,1998,1116,10,1068047603,1,141,'',0),(4989,161,1998,0,79,1431,1431,10,1068047603,1,141,'',0),(4988,161,1431,0,78,381,1998,10,1068047603,1,141,'',0),(4987,161,381,0,77,1997,1431,10,1068047603,1,141,'',0),(4986,161,1997,0,76,1996,381,10,1068047603,1,141,'',0),(4985,161,1996,0,75,1995,1997,10,1068047603,1,141,'',0),(4984,161,1995,0,74,1142,1996,10,1068047603,1,141,'',0),(4983,161,1142,0,73,1994,1995,10,1068047603,1,141,'',0),(4982,161,1994,0,72,1989,1142,10,1068047603,1,141,'',0),(4981,161,1989,0,71,1359,1994,10,1068047603,1,141,'',0),(4980,161,1359,0,70,195,1989,10,1068047603,1,141,'',0),(4979,161,195,0,69,89,1359,10,1068047603,1,141,'',0),(4978,161,89,0,68,1993,195,10,1068047603,1,141,'',0),(4977,161,1993,0,67,1988,89,10,1068047603,1,141,'',0),(4976,161,1988,0,66,1116,1993,10,1068047603,1,141,'',0),(4975,161,1116,0,65,1359,1988,10,1068047603,1,141,'',0),(4974,161,1359,0,64,195,1116,10,1068047603,1,141,'',0),(4973,161,195,0,63,89,1359,10,1068047603,1,141,'',0),(4972,161,89,0,62,1993,195,10,1068047603,1,141,'',0),(4971,161,1993,0,61,1402,89,10,1068047603,1,141,'',0),(4970,161,1402,0,60,1986,1993,10,1068047603,1,141,'',0),(4969,161,1986,0,59,381,1402,10,1068047603,1,141,'',0),(4968,161,381,0,58,1992,1986,10,1068047603,1,141,'',0),(4967,161,1992,0,57,33,381,10,1068047603,1,141,'',0),(4966,161,33,0,56,1991,1992,10,1068047603,1,141,'',0),(4965,161,1991,0,55,1359,33,10,1068047603,1,141,'',0),(4964,161,1359,0,54,1977,1991,10,1068047603,1,141,'',0),(4963,161,1977,0,53,1990,1359,10,1068047603,1,141,'',0),(4962,161,1990,0,52,1989,1977,10,1068047603,1,141,'',0),(4961,161,1989,0,51,1153,1990,10,1068047603,1,141,'',0),(4960,161,1153,0,50,1987,1989,10,1068047603,1,141,'',0),(4959,161,1987,0,49,1988,1153,10,1068047603,1,141,'',0),(4958,161,1988,0,48,1116,1987,10,1068047603,1,141,'',0),(4957,161,1116,0,47,1153,1988,10,1068047603,1,141,'',0),(4956,161,1153,0,46,1987,1116,10,1068047603,1,141,'',0),(4955,161,1987,0,45,1402,1153,10,1068047603,1,141,'',0),(4954,161,1402,0,44,1986,1987,10,1068047603,1,141,'',0),(4953,161,1986,0,43,1985,1402,10,1068047603,1,141,'',0),(4952,161,1985,0,42,1979,1986,10,1068047603,1,141,'',0),(4951,161,1979,0,41,1984,1985,10,1068047603,1,141,'',0),(4950,161,1984,0,40,1983,1979,10,1068047603,1,141,'',0),(4949,161,1983,0,39,1978,1984,10,1068047603,1,141,'',0),(4948,161,1978,0,38,1977,1983,10,1068047603,1,141,'',0),(4947,161,1977,0,37,1976,1978,10,1068047603,1,141,'',0),(4946,161,1976,0,36,33,1977,10,1068047603,1,141,'',0),(4945,161,33,0,35,1982,1976,10,1068047603,1,141,'',0),(4944,161,1982,0,34,1144,33,10,1068047603,1,141,'',0),(4943,161,1144,0,33,1981,1982,10,1068047603,1,141,'',0),(4942,161,1981,0,32,1980,1144,10,1068047603,1,141,'',0),(4941,161,1980,0,31,1979,1981,10,1068047603,1,141,'',0),(4940,161,1979,0,30,1978,1980,10,1068047603,1,141,'',0),(4939,161,1978,0,29,1977,1979,10,1068047603,1,141,'',0),(4938,161,1977,0,28,1976,1978,10,1068047603,1,141,'',0),(4937,161,1976,0,27,1118,1977,10,1068047603,1,141,'',0),(4936,161,1118,0,26,1117,1976,10,1068047603,1,141,'',0),(4935,161,1117,0,25,1116,1118,10,1068047603,1,141,'',0),(4934,161,1116,0,24,1963,1117,10,1068047603,1,141,'',0),(4933,161,1963,0,23,1142,1116,10,1068047603,1,141,'',0),(4932,161,1142,0,22,1975,1963,10,1068047603,1,141,'',0),(4931,161,1975,0,21,1116,1142,10,1068047603,1,141,'',0),(4930,161,1116,0,20,1974,1975,10,1068047603,1,141,'',0),(4929,161,1974,0,19,1973,1116,10,1068047603,1,141,'',0),(4928,161,1973,0,18,1116,1974,10,1068047603,1,141,'',0),(4927,161,1116,0,17,1120,1973,10,1068047603,1,141,'',0),(4926,161,1120,0,16,1972,1116,10,1068047603,1,141,'',0),(4925,161,1972,0,15,1971,1120,10,1068047603,1,141,'',0),(4924,161,1971,0,14,1970,1972,10,1068047603,1,141,'',0),(4923,161,1970,0,13,1969,1971,10,1068047603,1,141,'',0),(4922,161,1969,0,12,1968,1970,10,1068047603,1,141,'',0),(4921,161,1968,0,11,1967,1969,10,1068047603,1,141,'',0),(4920,161,1967,0,10,37,1968,10,1068047603,1,141,'',0),(4919,161,37,0,9,1966,1967,10,1068047603,1,141,'',0),(4918,161,1966,0,8,1402,37,10,1068047603,1,141,'',0),(4917,161,1402,0,7,1965,1966,10,1068047603,1,141,'',0),(4916,161,1965,0,6,1964,1402,10,1068047603,1,141,'',0),(4915,161,1964,0,5,1963,1965,10,1068047603,1,141,'',0),(4914,161,1963,0,4,1962,1964,10,1068047603,1,141,'',0),(4913,161,1962,0,3,1081,1963,10,1068047603,1,141,'',0),(4912,161,1081,0,2,73,1962,10,1068047603,1,140,'',0),(4911,161,73,0,1,934,1081,10,1068047603,1,140,'',0),(4910,161,934,0,0,0,73,10,1068047603,1,140,'',0),(5708,218,1984,0,78,2036,2037,2,1069763601,4,121,'',0),(5709,218,2037,0,79,1984,2018,2,1069763601,4,121,'',0),(5710,218,2018,0,80,2037,74,2,1069763601,4,121,'',0),(5711,218,74,0,81,2018,2038,2,1069763601,4,121,'',0),(5712,218,2038,0,82,74,2039,2,1069763601,4,121,'',0),(5713,218,2039,0,83,2038,1974,2,1069763601,4,121,'',0),(5714,218,1974,0,84,2039,1116,2,1069763601,4,121,'',0),(5715,218,1116,0,85,1974,2016,2,1069763601,4,121,'',0),(5716,218,2016,0,86,1116,2018,2,1069763601,4,121,'',0),(5717,218,2018,0,87,2016,1153,2,1069763601,4,121,'',0),(5718,218,1153,0,88,2018,1142,2,1069763601,4,121,'',0),(5719,218,1142,0,89,1153,2009,2,1069763601,4,121,'',0),(5720,218,2009,0,90,1142,1081,2,1069763601,4,121,'',0),(5721,218,1081,0,91,2009,2040,2,1069763601,4,121,'',0),(5722,218,2040,0,92,1081,1977,2,1069763601,4,121,'',0),(5723,218,1977,0,93,2040,2041,2,1069763601,4,121,'',0),(5724,218,2041,0,94,1977,51,2,1069763601,4,121,'',0),(5725,218,51,0,95,2041,2016,2,1069763601,4,121,'',0),(5726,218,2016,0,96,51,1117,2,1069763601,4,121,'',0),(5727,218,1117,0,97,2016,2042,2,1069763601,4,121,'',0),(5728,218,2042,0,98,1117,2043,2,1069763601,4,121,'',0),(5729,218,2043,0,99,2042,2018,2,1069763601,4,121,'',0),(5730,218,2018,0,100,2043,2044,2,1069763601,4,121,'',0),(5731,218,2044,0,101,2018,77,2,1069763601,4,121,'',0),(5732,218,77,0,102,2044,2045,2,1069763601,4,121,'',0),(5733,218,2045,0,103,77,2017,2,1069763601,4,121,'',0),(5734,218,2017,0,104,2045,2046,2,1069763601,4,121,'',0),(5735,218,2046,0,105,2017,1402,2,1069763601,4,121,'',0),(5736,218,1402,0,106,2046,1378,2,1069763601,4,121,'',0),(5737,218,1378,0,107,1402,2047,2,1069763601,4,121,'',0),(5738,218,2047,0,108,1378,2048,2,1069763601,4,121,'',0),(5739,218,2048,0,109,2047,2045,2,1069763601,4,121,'',0),(5740,218,2045,0,110,2048,2049,2,1069763601,4,121,'',0),(5741,218,2049,0,111,2045,2016,2,1069763601,4,121,'',0),(5742,218,2016,0,112,2049,1153,2,1069763601,4,121,'',0),(5743,218,1153,0,113,2016,1142,2,1069763601,4,121,'',0),(5744,218,1142,0,114,1153,2034,2,1069763601,4,121,'',0),(5745,218,2034,0,115,1142,1120,2,1069763601,4,121,'',0),(5746,218,1120,0,116,2034,0,2,1069763601,4,121,'',0),(5747,219,1143,0,0,0,1081,2,1069763878,4,1,'',0),(5748,219,1081,0,1,1143,2205,2,1069763878,4,1,'',0),(5749,219,2205,0,2,1081,1142,2,1069763878,4,1,'',0),(5750,219,1142,0,3,2205,1143,2,1069763878,4,120,'',0),(5751,219,1143,0,4,1142,1081,2,1069763878,4,120,'',0),(5752,219,1081,0,5,1143,1402,2,1069763878,4,120,'',0),(5753,219,1402,0,6,1081,1378,2,1069763878,4,120,'',0),(5754,219,1378,0,7,1402,2206,2,1069763878,4,120,'',0),(5755,219,2206,0,8,1378,74,2,1069763878,4,120,'',0),(5756,219,74,0,9,2206,934,2,1069763878,4,120,'',0),(5757,219,934,0,10,74,2205,2,1069763878,4,120,'',0),(5758,219,2205,0,11,934,1132,2,1069763878,4,120,'',0),(5759,219,1132,0,12,2205,2101,2,1069763878,4,120,'',0),(5760,219,2101,0,13,1132,1977,2,1069763878,4,120,'',0),(5761,219,1977,0,14,2101,2207,2,1069763878,4,120,'',0),(5762,219,2207,0,15,1977,2208,2,1069763878,4,120,'',0),(5763,219,2208,0,16,2207,2209,2,1069763878,4,120,'',0),(5764,219,2209,0,17,2208,2055,2,1069763878,4,121,'',0),(5765,219,2055,0,18,2209,2210,2,1069763878,4,121,'',0),(5766,219,2210,0,19,2055,2146,2,1069763878,4,121,'',0),(5767,219,2146,0,20,2210,2211,2,1069763878,4,121,'',0),(5768,219,2211,0,21,2146,934,2,1069763878,4,121,'',0),(5769,219,934,0,22,2211,89,2,1069763878,4,121,'',0),(5770,219,89,0,23,934,2212,2,1069763878,4,121,'',0),(5771,219,2212,0,24,89,2213,2,1069763878,4,121,'',0),(5772,219,2213,0,25,2212,1970,2,1069763878,4,121,'',0),(5773,219,1970,0,26,2213,2003,2,1069763878,4,121,'',0),(5774,219,2003,0,27,1970,2214,2,1069763878,4,121,'',0),(5775,219,2214,0,28,2003,2215,2,1069763878,4,121,'',0),(5776,219,2215,0,29,2214,2102,2,1069763878,4,121,'',0),(5777,219,2102,0,30,2215,2216,2,1069763878,4,121,'',0),(5778,219,2216,0,31,2102,2100,2,1069763878,4,121,'',0),(5779,219,2100,0,32,2216,934,2,1069763878,4,121,'',0),(5780,219,934,0,33,2100,1142,2,1069763878,4,121,'',0),(5781,219,1142,0,34,934,2217,2,1069763878,4,121,'',0),(5782,219,2217,0,35,1142,2055,2,1069763878,4,121,'',0),(5783,219,2055,0,36,2217,1977,2,1069763878,4,121,'',0),(5784,219,1977,0,37,2055,2218,2,1069763878,4,121,'',0),(5785,219,2218,0,38,1977,2219,2,1069763878,4,121,'',0),(5786,219,2219,0,39,2218,1116,2,1069763878,4,121,'',0),(5787,219,1116,0,40,2219,1999,2,1069763878,4,121,'',0),(5788,219,1999,0,41,1116,2220,2,1069763878,4,121,'',0),(5789,219,2220,0,42,1999,2018,2,1069763878,4,121,'',0),(5790,219,2018,0,43,2220,1970,2,1069763878,4,121,'',0),(5791,219,1970,0,44,2018,2162,2,1069763878,4,121,'',0),(5792,219,2162,0,45,1970,2221,2,1069763878,4,121,'',0),(5793,219,2221,0,46,2162,1977,2,1069763878,4,121,'',0),(5794,219,1977,0,47,2221,2208,2,1069763878,4,121,'',0),(5795,219,2208,0,48,1977,2219,2,1069763878,4,121,'',0),(5796,219,2219,0,49,2208,1977,2,1069763878,4,121,'',0),(5797,219,1977,0,50,2219,2208,2,1069763878,4,121,'',0),(5798,219,2208,0,51,1977,74,2,1069763878,4,121,'',0),(5799,219,74,0,52,2208,89,2,1069763878,4,121,'',0),(5800,219,89,0,53,74,2222,2,1069763878,4,121,'',0),(5801,219,2222,0,54,89,2223,2,1069763878,4,121,'',0),(5802,219,2223,0,55,2222,89,2,1069763878,4,121,'',0),(5803,219,89,0,56,2223,2224,2,1069763878,4,121,'',0),(5804,219,2224,0,57,89,89,2,1069763878,4,121,'',0),(5805,219,89,0,58,2224,2225,2,1069763878,4,121,'',0),(5806,219,2225,0,59,89,2226,2,1069763878,4,121,'',0),(5807,219,2226,0,60,2225,1970,2,1069763878,4,121,'',0),(5808,219,1970,0,61,2226,89,2,1069763878,4,121,'',0),(5809,219,89,0,62,1970,2227,2,1069763878,4,121,'',0),(5810,219,2227,0,63,89,2228,2,1069763878,4,121,'',0),(5811,219,2228,0,64,2227,2065,2,1069763878,4,121,'',0),(5812,219,2065,0,65,2228,1999,2,1069763878,4,121,'',0),(5813,219,1999,0,66,2065,1119,2,1069763878,4,121,'',0),(5814,219,1119,0,67,1999,2229,2,1069763878,4,121,'',0),(5815,219,2229,0,68,1119,33,2,1069763878,4,121,'',0),(5816,219,33,0,69,2229,2218,2,1069763878,4,121,'',0),(5817,219,2218,0,70,33,0,2,1069763878,4,121,'',0),(6536,220,2227,0,8,1970,0,20,1069763952,1,187,'',0),(6535,220,1970,0,7,2215,2227,20,1069763952,1,187,'',0),(6534,220,2215,0,6,2213,1970,20,1069763952,1,187,'',0),(6533,220,2213,0,5,2208,2215,20,1069763952,1,187,'',0),(6532,220,2208,0,4,1977,2213,20,1069763952,1,187,'',0),(6531,220,1977,0,3,74,2208,20,1069763952,1,187,'',0),(6530,220,74,0,2,1132,1977,20,1069763952,1,187,'',0),(6529,220,1132,0,1,2205,74,20,1069763952,1,187,'',0),(6528,220,2205,0,0,0,1132,20,1069763952,1,186,'',0),(5911,14,1359,0,1,2258,1405,4,1033920830,2,9,'',0),(5910,14,2258,0,0,0,1359,4,1033920830,2,8,'',0),(5835,221,2233,0,0,0,74,21,1069765267,1,188,'',0),(5836,221,74,0,1,2233,1142,21,1069765267,1,188,'',0),(5837,221,1142,0,2,74,2234,21,1069765267,1,188,'',0),(5838,221,2234,0,3,1142,2042,21,1069765267,1,188,'',0),(5839,221,2042,0,4,2234,2235,21,1069765267,1,189,'',0),(5840,221,2235,0,5,2042,1402,21,1069765267,1,189,'',0),(5841,221,1402,0,6,2235,1999,21,1069765267,1,189,'',0),(5842,221,1999,0,7,1402,2236,21,1069765267,1,189,'',0),(5843,221,2236,0,8,1999,1974,21,1069765267,1,189,'',0),(5844,221,1974,0,9,2236,1962,21,1069765267,1,189,'',0),(5845,221,1962,0,10,1974,2237,21,1069765267,1,189,'',0),(5846,221,2237,0,11,1962,1153,21,1069765267,1,189,'',0),(5847,221,1153,0,12,2237,1977,21,1069765267,1,189,'',0),(5848,221,1977,0,13,1153,2238,21,1069765267,1,189,'',0),(5849,221,2238,0,14,1977,2018,21,1069765267,1,189,'',0),(5850,221,2018,0,15,2238,2239,21,1069765267,1,189,'',0),(5851,221,2239,0,16,2018,1378,21,1069765267,1,189,'',0),(5852,221,1378,0,17,2239,1142,21,1069765267,1,189,'',0),(5853,221,1142,0,18,1378,2240,21,1069765267,1,189,'',0),(5854,221,2240,0,19,1142,2241,21,1069765267,1,189,'',0),(5855,221,2241,0,20,2240,2217,21,1069765267,1,189,'',0),(5856,221,2217,0,21,2241,2242,21,1069765267,1,189,'',0),(5857,221,2242,0,22,2217,1092,21,1069765267,1,189,'',0),(5858,221,1092,0,23,2242,0,21,1069765267,1,190,'',0),(5859,222,1988,0,0,0,2027,21,1069765545,1,188,'',0),(5860,222,2027,0,1,1988,2243,21,1069765545,1,188,'',0),(5861,222,2243,0,2,2027,2213,21,1069765545,1,188,'',0),(5862,222,2213,0,3,2243,2244,21,1069765545,1,188,'',0),(5863,222,2244,0,4,2213,2175,21,1069765545,1,188,'',0),(5864,222,2175,0,5,2244,2055,21,1069765545,1,188,'',0),(5865,222,2055,0,6,2175,2018,21,1069765545,1,188,'',0),(5866,222,2018,0,7,2055,73,21,1069765545,1,188,'',0),(5867,222,73,0,8,2018,74,21,1069765545,1,189,'',0),(5868,222,74,0,9,73,1142,21,1069765545,1,189,'',0),(5869,222,1142,0,10,74,2245,21,1069765545,1,189,'',0),(5870,222,2245,0,11,1142,2208,21,1069765545,1,189,'',0),(5871,222,2208,0,12,2245,1092,21,1069765545,1,189,'',0),(5872,222,1092,0,13,2208,0,21,1069765545,1,190,'',0),(5908,223,1402,0,17,2257,73,22,1069765640,1,193,'',0),(5907,223,2257,0,16,2162,1402,22,1069765640,1,193,'',0),(5906,223,2162,0,15,2217,2257,22,1069765640,1,193,'',0),(5905,223,2217,0,14,1374,2162,22,1069765640,1,193,'',0),(5904,223,1374,0,13,2105,2217,22,1069765640,1,193,'',0),(5903,223,2105,0,12,2176,1374,22,1069765640,1,193,'',0),(5902,223,2176,0,11,2256,2105,22,1069765640,1,193,'',0),(5901,223,2256,0,10,2018,2176,22,1069765640,1,193,'',0),(5900,223,2018,0,9,2255,2256,22,1069765640,1,193,'',0),(5899,223,2255,0,8,1402,2018,22,1069765640,1,193,'',0),(5898,223,1402,0,7,1999,2255,22,1069765640,1,193,'',0),(5897,223,1999,0,6,2042,1402,22,1069765640,1,193,'',0),(5896,223,2042,0,5,2254,1999,22,1069765640,1,193,'',0),(5895,223,2254,0,4,2253,2042,22,1069765640,1,193,'',0),(5894,223,2253,0,3,1142,2254,22,1069765640,1,191,'',0),(5893,223,1142,0,2,74,2253,22,1069765640,1,191,'',0),(5892,223,74,0,1,2252,1142,22,1069765640,1,191,'',0),(5891,223,2252,0,0,0,74,22,1069765640,1,191,'',0),(5909,223,73,0,18,1402,0,22,1069765640,1,193,'',0),(5917,14,2261,0,7,2261,0,4,1033920830,2,199,'',0),(6099,228,74,0,1,2049,2309,22,1069767050,1,191,'',0),(6096,227,2316,0,34,2032,1092,21,1069766947,1,189,'',0),(6095,227,2032,0,33,2065,2316,21,1069766947,1,189,'',0),(6094,227,2065,0,32,2105,2032,21,1069766947,1,189,'',0),(6093,227,2105,0,31,33,2065,21,1069766947,1,189,'',0),(6092,227,33,0,30,2315,2105,21,1069766947,1,189,'',0),(6091,227,2315,0,29,2314,33,21,1069766947,1,189,'',0),(6090,227,2314,0,28,2209,2315,21,1069766947,1,189,'',0),(6089,227,2209,0,27,1999,2314,21,1069766947,1,189,'',0),(6088,227,1999,0,26,2107,2209,21,1069766947,1,189,'',0),(6087,227,2107,0,25,2313,1999,21,1069766947,1,189,'',0),(6086,227,2313,0,24,1117,2107,21,1069766947,1,189,'',0),(6085,227,1117,0,23,2107,2313,21,1069766947,1,189,'',0),(6084,227,2107,0,22,33,1117,21,1069766947,1,189,'',0),(6083,227,33,0,21,2312,2107,21,1069766947,1,189,'',0),(6082,227,2312,0,20,2142,33,21,1069766947,1,189,'',0),(6081,227,2142,0,19,2307,2312,21,1069766947,1,189,'',0),(6080,227,2307,0,18,1142,2142,21,1069766947,1,189,'',0),(6079,227,1142,0,17,2311,2307,21,1069766947,1,189,'',0),(6078,227,2311,0,16,1316,1142,21,1069766947,1,189,'',0),(6077,227,1316,0,15,33,2311,21,1069766947,1,189,'',0),(6076,227,33,0,14,2260,1316,21,1069766947,1,189,'',0),(6075,227,2260,0,13,1144,33,21,1069766947,1,189,'',0),(6070,227,2018,0,8,2308,74,21,1069766947,1,189,'',0),(6069,227,2308,0,7,1153,2018,21,1069766947,1,188,'',0),(6068,227,1153,0,6,2307,2308,21,1069766947,1,188,'',0),(6067,227,2307,0,5,2306,1153,21,1069766947,1,188,'',0),(6066,227,2306,0,4,2253,2307,21,1069766947,1,188,'',0),(6065,227,2253,0,3,1142,2306,21,1069766947,1,188,'',0),(6426,232,74,0,81,2018,2038,21,1069768853,1,189,'',0),(6425,232,2018,0,80,2037,74,21,1069768853,1,189,'',0),(6424,232,2037,0,79,1984,2018,21,1069768853,1,189,'',0),(6423,232,1984,0,78,2036,2037,21,1069768853,1,189,'',0),(6422,232,2036,0,77,77,1984,21,1069768853,1,189,'',0),(6421,232,77,0,76,2035,2036,21,1069768853,1,189,'',0),(6420,232,2035,0,75,1142,77,21,1069768853,1,189,'',0),(6419,232,1142,0,74,1081,2035,21,1069768853,1,189,'',0),(6418,232,1081,0,73,2034,1142,21,1069768853,1,189,'',0),(6417,232,2034,0,72,1142,1081,21,1069768853,1,189,'',0),(6416,232,1142,0,71,1402,2034,21,1069768853,1,189,'',0),(6415,232,1402,0,70,2016,1142,21,1069768853,1,189,'',0),(6414,232,2016,0,69,1116,1402,21,1069768853,1,189,'',0),(6413,232,1116,0,68,1974,2016,21,1069768853,1,189,'',0),(6412,232,1974,0,67,73,1116,21,1069768853,1,189,'',0),(6411,232,73,0,66,2013,1974,21,1069768853,1,189,'',0),(6410,232,2013,0,65,2033,73,21,1069768853,1,189,'',0),(6409,232,2033,0,64,2032,2013,21,1069768853,1,189,'',0),(6408,232,2032,0,63,2027,2033,21,1069768853,1,189,'',0),(6407,232,2027,0,62,2031,2032,21,1069768853,1,189,'',0),(6406,232,2031,0,61,2030,2027,21,1069768853,1,189,'',0),(6405,232,2030,0,60,1970,2031,21,1069768853,1,189,'',0),(6404,232,1970,0,59,2019,2030,21,1069768853,1,189,'',0),(6403,232,2019,0,58,1153,1970,21,1069768853,1,189,'',0),(6402,232,1153,0,57,2018,2019,21,1069768853,1,189,'',0),(6401,232,2018,0,56,2017,1153,21,1069768853,1,189,'',0),(6400,232,2017,0,55,1081,2018,21,1069768853,1,189,'',0),(6399,232,1081,0,54,2021,2017,21,1069768853,1,189,'',0),(6398,232,2021,0,53,1142,1081,21,1069768853,1,189,'',0),(6397,232,1142,0,52,1153,2021,21,1069768853,1,189,'',0),(6396,232,1153,0,51,2029,1142,21,1069768853,1,189,'',0),(6395,232,2029,0,50,2022,1153,21,1069768853,1,189,'',0),(6394,232,2022,0,49,1977,2029,21,1069768853,1,189,'',0),(6393,232,1977,0,48,1974,2022,21,1069768853,1,189,'',0),(6392,232,1974,0,47,1120,1977,21,1069768853,1,189,'',0),(6391,232,1120,0,46,1119,1974,21,1069768853,1,189,'',0),(6390,232,1119,0,45,2028,1120,21,1069768853,1,189,'',0),(6389,232,2028,0,44,1153,1119,21,1069768853,1,189,'',0),(6388,232,1153,0,43,2016,2028,21,1069768853,1,189,'',0),(6387,232,2016,0,42,2027,1153,21,1069768853,1,189,'',0),(6386,232,2027,0,41,2026,2016,21,1069768853,1,189,'',0),(6385,232,2026,0,40,2025,2027,21,1069768853,1,189,'',0),(6384,232,2025,0,39,2024,2026,21,1069768853,1,189,'',0),(6383,232,2024,0,38,2023,2025,21,1069768853,1,189,'',0),(6382,232,2023,0,37,934,2024,21,1069768853,1,189,'',0),(6381,232,934,0,36,74,2023,21,1069768853,1,189,'',0),(6380,232,74,0,35,2022,934,21,1069768853,1,189,'',0),(6379,232,2022,0,34,1116,74,21,1069768853,1,189,'',0),(6378,232,1116,0,33,1974,2022,21,1069768853,1,189,'',0),(6377,232,1974,0,32,1081,1116,21,1069768853,1,189,'',0),(6376,232,1081,0,31,2021,1974,21,1069768853,1,189,'',0),(6375,232,2021,0,30,1142,1081,21,1069768853,1,189,'',0),(6374,232,1142,0,29,1402,2021,21,1069768853,1,189,'',0),(6373,232,1402,0,28,2017,1142,21,1069768853,1,189,'',0),(6372,232,2017,0,27,1431,1402,21,1069768853,1,189,'',0),(6371,232,1431,0,26,2020,2017,21,1069768853,1,189,'',0),(6370,232,2020,0,25,1431,1431,21,1069768853,1,189,'',0),(6369,232,1431,0,24,1260,2020,21,1069768853,1,189,'',0),(6368,232,1260,0,23,74,1431,21,1069768853,1,189,'',0),(6367,232,74,0,22,1081,1260,21,1069768853,1,189,'',0),(6366,232,1081,0,21,2019,74,21,1069768853,1,189,'',0),(6365,232,2019,0,20,89,1081,21,1069768853,1,189,'',0),(6364,232,89,0,19,1402,2019,21,1069768853,1,189,'',0),(6363,232,1402,0,18,2018,89,21,1069768853,1,189,'',0),(6362,232,2018,0,17,2017,1402,21,1069768853,1,189,'',0),(6361,232,2017,0,16,1402,2018,21,1069768853,1,189,'',0),(6360,232,1402,0,15,2016,2017,21,1069768853,1,189,'',0),(6359,232,2016,0,14,2015,1402,21,1069768853,1,189,'',0),(6358,232,2015,0,13,1116,2016,21,1069768853,1,189,'',0),(6357,232,1116,0,12,1081,2015,21,1069768853,1,189,'',0),(6356,232,1081,0,11,2014,1116,21,1069768853,1,189,'',0),(6355,232,2014,0,10,2013,1081,21,1069768853,1,189,'',0),(6354,232,2013,0,9,1402,2014,21,1069768853,1,189,'',0),(6353,232,1402,0,8,2012,2013,21,1069768853,1,189,'',0),(6352,232,2012,0,7,2011,1402,21,1069768853,1,189,'',0),(6351,232,2011,0,6,89,2012,21,1069768853,1,189,'',0),(6350,232,89,0,5,2010,2011,21,1069768853,1,189,'',0),(6349,232,2010,0,4,1081,89,21,1069768853,1,189,'',0),(6348,232,1081,0,3,2009,2010,21,1069768853,1,188,'',0),(6347,232,2009,0,2,1142,1081,21,1069768853,1,188,'',0),(6346,232,1142,0,1,2008,2009,21,1069768853,1,188,'',0),(6345,232,2008,0,0,0,1142,21,1069768853,1,188,'',0);
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
INSERT INTO ezsearch_word VALUES (2335,'certain',1),(6,'media',1),(7,'setup',3),(1412,'grouplist',1),(1411,'class',1),(1410,'classes',1),(11,'links',1),(25,'content',2),(34,'feel',2),(33,'and',10),(32,'look',3),(37,'news',4),(1431,'as',5),(2190,'language',1),(51,'topic',3),(2189,'improper',1),(1141,'bulletin',1),(2188,'part',1),(2187,'valuable',1),(2321,'then',2),(934,'about',6),(73,'this',8),(74,'is',14),(77,'for',3),(2332,'so',1),(2331,'played',1),(2296,'proof',1),(2186,'together',1),(89,'a',6),(2295,'hits',1),(2293,'her',1),(2294,'list',1),(2185,'crew',1),(2184,'answers',1),(2183,'level',1),(2182,'leisure',1),(2181,'supported',1),(2180,'away',1),(2179,'right',1),(1095,'discuss',1),(1094,'music',3),(2292,'doubt',1),(2291,'greats',1),(1092,'0',5),(2178,'expect',1),(2177,'supply',1),(2176,'there',3),(2175,'out',2),(2174,'left',1),(2173,'reading',1),(2359,'2003',1),(2358,'1999',1),(1405,'site',2),(1404,'community',2),(2172,'reread',1),(2357,'systems',1),(2356,'ez',1),(2355,'&copy',1),(195,'new',2),(2171,'numbers',1),(381,'here',4),(2170,'version',1),(1379,'shown',1),(1378,'be',7),(1377,'may',1),(1376,'text',1),(1375,'description',1),(1374,'no',4),(1373,'group',1),(1372,'main',1),(2317,'never',1),(2316,'young',1),(2314,'good',1),(2315,'players',1),(2313,'rule',1),(2312,'years',3),(2311,'give',1),(2320,'maybe',1),(2319,'but',1),(1403,'our',1),(1402,'to',9),(2306,'football',1),(2305,'everything',1),(2304,'hate',1),(2324,'gulset',1),(1266,'important',1),(2354,'copyright',1),(1417,'urltranslator',1),(2337,'1',1),(2338,'\"login\"',1),(1260,'not',5),(2318,'heard',1),(1409,'cache',1),(2353,'forum_package',1),(2169,'plug',1),(2168,'module',1),(2167,'program',1),(2166,'include',1),(2165,'remember',1),(2164,'rethink',1),(2163,'solution',1),(2162,'even',3),(2161,'knows',1),(2160,'hours',1),(2159,'using',1),(2158,'spent',1),(2157,'fact',2),(2156,'since',2),(1146,'dall',1),(1145,'dill',1),(1144,'from',3),(1143,'latest',3),(1142,'the',13),(1401,'welcome',1),(2302,'understand',1),(2301,'live',1),(2300,'opinion',1),(2299,'everybody',1),(2233,'koenigsegg',1),(1316,'norway',2),(2260,'skien',2),(2155,'worth',1),(2154,'well',1),(2259,'boss',1),(1359,'user',3),(2297,'enough',1),(1310,'re',1),(2310,'grenland',2),(2309,'odd',3),(2308,'europe',2),(2307,'team',2),(1081,'forum',8),(2153,'clearly',1),(2152,'extra',1),(2151,'browser',1),(2150,'encountered',1),(2149,'php',1),(2148,'apache',1),(2147,'paste',1),(2146,'always',2),(2145,'logs',1),(2144,'messages',1),(2143,'error',1),(2142,'five',3),(2141,'least',1),(2140,'at',2),(2139,'take',1),(2138,'on',1),(2137,'running',1),(2136,'system',1),(2135,'done',1),(2134,'testing',1),(2133,'work',1),(2132,'does',2),(2131,'got',1),(2130,'far',1),(2129,'needed',1),(2128,'descriptive',1),(2127,'isn',1),(2126,'work\"',1),(2125,'precise',1),(2124,'brief',1),(2123,'do',1),(2122,'sentences',1),(2121,'asked',1),(2120,'achieve',1),(2119,'trying',1),(2118,'idea',1),(2117,'sometimes',1),(2116,'goal',1),(2115,'describe',1),(2114,'able',1),(2113,'whom',1),(2112,'time',1),(2111,'knowledge',1),(2110,'their',1),(2109,'outside',1),(2108,'see',1),(2107,'they',3),(2106,'something',1),(1153,'in',11),(2105,'are',6),(2104,'decide',1),(2103,'reader',1),(944,'ipsum',1),(943,'lorem',1),(2191,'contains',1),(2102,'let',2),(1118,'find',2),(1117,'will',6),(1116,'you',8),(1414,'56',1),(1413,'edit',1),(1416,'translator',1),(1415,'url',1),(2287,'madonna',2),(2286,'spears',1),(1115,'discussions',2),(1087,'discussion',2),(1119,'different',5),(2285,'eyes',1),(1099,'sports',2),(2289,'history',1),(2288,'ad',1),(2303,'while',1),(2290,'nothing',1),(2284,'my',1),(2329,'cup',1),(2330,'haven',1),(2328,'ago',1),(1120,'forums',4),(1132,'what',6),(2283,'misic',1),(2282,'longer',1),(2281,'pop',1),(2258,'administrator',1),(2334,'rules',1),(2333,'world',1),(2298,'sport',1),(2101,'s',3),(2100,'know',2),(2099,'think',1),(2098,'installation',1),(2097,'needs',1),(2096,'everyone',1),(2095,'reads',1),(2094,'anyone',2),(2093,'help',1),(2092,'doesn',1),(2091,'\"installation',1),(2090,'question\"',1),(2089,'\"newbie',1),(2088,'help\"',1),(2087,'need',1),(2086,'\"i',1),(2085,'saying',1),(2084,'considering',1),(2083,'first',1),(2082,'describing',1),(2081,'choosing',1),(2080,'title',1),(2079,'clear',1),(2078,'answer',1),(2077,'into',1),(2076,'rewrite',1),(2075,'why',1),(2074,'somewhere',1),(2073,'written',1),(2072,'allready',1),(2071,'after',1),(2070,'info',1),(2069,'relevant',1),(2068,'them',2),(2067,'available',1),(2066,'documentation',1),(2065,'all',4),(2064,'read',1),(2063,'been',1),(2062,'has',2),(2061,'problem',1),(2060,'exact',1),(2059,'often',1),(2058,'notice',1),(2057,'surprised',1),(2056,'d',1),(2055,'of',6),(2054,'archives',1),(2053,'try',1),(2052,'ask',1),(2051,'before',1),(2050,'search',1),(2049,'who',5),(2048,'among',3),(2047,'noticed',3),(2046,'correctly',3),(2045,'those',3),(2044,'harder',3),(2043,'make',3),(2042,'just',5),(2041,'off',3),(2040,'furthermore',3),(2039,'than',4),(2038,'lower',3),(2037,'answering',3),(2036,'someone',3),(2035,'chances',3),(2034,'wrong',4),(2033,'helpful',3),(2032,'very',4),(2031,'aren',3),(2030,'\"developer\"',3),(2029,'belongs',3),(2028,'several',3),(2027,'t',6),(2026,'don',3),(2025,'questions',3),(2024,'related',3),(2023,'install',3),(2022,'question',3),(2021,'\"install\"',3),(2020,'effective',3),(2019,'\"general\"',3),(2018,'it',10),(2017,'posting',3),(2016,'post',3),(2015,'should',3),(2014,'which',3),(2013,'consider',3),(2012,'minutes',3),(2011,'few',3),(2010,'use',3),(2009,'correct',3),(2008,'choose',3),(2007,'with',3),(2006,'comes',2),(2005,'privileges',2),(2004,'account',2),(2003,'an',4),(2002,'created',2),(2001,'information',2),(2000,'filled',2),(1999,'have',7),(1998,'soon',2),(1997,'button',2),(1996,'up\"',1),(1995,'\"sign',1),(1994,'pressing',2),(1993,'register',2),(1992,'password',1),(1991,'name',1),(1990,'entering',1),(1989,'by',2),(1988,'can',5),(1987,'log',1),(1986,'how',2),(1985,'solved',1),(1984,'actually',3),(1983,'might',1),(1982,'people',1),(1981,'attention',1),(1980,'more',1),(1979,'get',1),(1978,'problems',1),(1977,'your',6),(1976,'that',3),(1975,'follow',2),(1974,'if',4),(1973,'frequent',1),(1972,'web',1),(1971,'other',1),(1970,'or',6),(1969,'lists',1),(1968,'mailing',1),(1967,'groups',1),(1966,'any',1),(1965,'apply',1),(1964,'also',1),(1963,'guidelines',1),(1962,'these',3),(2192,'like',1),(2193,'cursing',1),(2194,'swearing',1),(2195,'removed',2),(2196,'answered',1),(2197,'way',1),(2198,'without',2),(2199,'warning',1),(2200,'administrators',1),(2201,'otherwise',1),(2202,'normal',1),(2203,'behavior',1),(2204,'expected',1),(2205,'dreamcars',2),(2206,'added',1),(2207,'favorite',1),(2208,'dreamcar',3),(2209,'many',2),(2210,'us',1),(2211,'dream',1),(2212,'red',1),(2213,'ferrari',3),(2214,'angry',1),(2215,'diablo',2),(2216,'others',1),(2217,'car',3),(2218,'dreams',1),(2219,'perhaps',1),(2220,'tested',1),(2221,'own',1),(2222,'volvo',1),(2223,'c70',1),(2224,'jaguar',1),(2225,'79',1),(2226,'mustang',1),(2227,'beetle',2),(2228,'we',1),(2229,'choices',1),(2234,'master',1),(2235,'imagine',1),(2236,'one',2),(2237,'parked',1),(2238,'garrage',1),(2239,'must',2),(2240,'most',1),(2241,'awsome',1),(2242,'ever',1),(2243,'leave',1),(2244,'f40',1),(2245,'ulitmate',1),(2256,'again',1),(2255,'say',2),(2254,'i',4),(2253,'best',3),(2252,'königsegg',1),(2257,'close',1),(2261,'tim',1),(2323,'kings',1),(2327,'couple',1),(2326,'beat',1),(2325,'better',1),(2322,'tranmere',1);
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
INSERT INTO ezsession VALUES ('4ab459d79b889d215fc807e1f9abaac8',1070101099,'eZUserGroupsCache_Timestamp|i:1069841661;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069841661;eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069841661;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069841899;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"20\";s:4:\"name\";s:5:\"Forum\";}i:10;a:2:{s:2:\"id\";s:2:\"21\";s:4:\"name\";s:11:\"Forum topic\";}i:11;a:2:{s:2:\"id\";s:2:\"22\";s:4:\"name\";s:11:\"Forum reply\";}}Preferences-advanced_menu|s:2:\"on\";'),('2260b2820342eff5be6fb233cde9e1ab',1070102743,'LastAccessesURI|s:22:\"/content/view/full/127\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069843393;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069843393;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069843393;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"377\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"378\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}eZUserDiscountRulesTimestamp|i:1069843393;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}'),('1c945923164151668f7b213fe2dd2bab',1070103243,'LastAccessesURI|s:22:\"/content/view/full/163\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069843928;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069843928;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069844006;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069843928;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"377\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"378\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}}}'),('6b757a80dcd2886681c0a2dc420526f6',1070105606,'eZUserInfoCache_Timestamp|i:1069843973;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069843973;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069846405;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}eZUserDiscountRulesTimestamp|i:1069843973;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069844005;canInstantiateClasses|i:1;Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;LastAccessesURI|s:22:\"/content/view/full/152\";eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRules14|a:0:{}classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"20\";s:4:\"name\";s:5:\"Forum\";}i:10;a:2:{s:2:\"id\";s:2:\"21\";s:4:\"name\";s:11:\"Forum topic\";}i:11;a:2:{s:2:\"id\";s:2:\"22\";s:4:\"name\";s:11:\"Forum reply\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}');
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
INSERT INTO ezsubtree_notification_rule VALUES (8,'nospam@ez.no',0,169),(2,'wy@ez.no',0,112),(7,'nospam@ez.no',0,159),(4,'bf@ez.no',0,124),(5,'bf@ez.no',0,135),(6,'wy@ez.no',0,114);
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,115,0),(121,'news/news_bulletin','9365952d8950c12f923a3a48e5e27fa3','content/view/full/126',1,0,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,115,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,115,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(101,'forum','bbdbe444288550204c968fe7002a97a9','content/view/full/111',1,112,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(104,'discussions/music_discussion','09533dfccc8477debe545d31bccf391f','content/view/full/114',1,149,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(173,'discussions/forum_rules/choose_the_correct_forum','a4b18bd7a44b0a85c44d2a24ed6dcf1f','content/view/full/170',1,0,0),(112,'discussions','48ee344d9a540894650ce4af27e169dd','content/view/full/111',1,0,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,0,0),(165,'discussions/forum_main_group/music_discussion/what_is_wrong_with_pop','deef0b69a301d79c660a47b8da45d1bb','content/view/full/162',1,0,0),(166,'discussions/forum_main_group/music_discussion/what_is_wrong_with_pop/madonna_is_one_of_the_greats','ba2e62a42bda005d4c34e5166dcdd49e','content/view/full/163',1,0,0),(128,'discussions/forum_main_group/sports_discussion/what_is_the_best_football_team_in_europe/reply_2','5936e9ca8c6d52c0e7bf656f64cc18ae','content/view/full/133',1,164,0),(167,'discussions/forum_main_group/sport_forum','33630020df40911b02af2a5607be9ea4','content/view/full/164',1,0,0),(168,'discussions/forum_main_group/sport_forum/what_is_the_best_football_team_in_europe','b4f62b44ff2941aef56caf9b37c353a0','content/view/full/165',1,0,0),(169,'discussions/forum_main_group/sport_forum/what_is_the_best_football_team_in_europe/who_is_odd','513d8b7cc7a8e5b393401ed29804166d','content/view/full/166',1,0,0),(170,'discussions/forum_main_group/sport_forum/what_is_the_best_football_team_in_europe/gulset_are_better_than_odd','b6cc0a944079dbad1b927da4897b4fe6','content/view/full/167',1,0,0),(171,'discussions/forum_rules','20d090b2e34d25845cfa3ced99c3c3bd','content/view/full/168',1,0,0),(172,'discussions/forum_rules/how_to_register_a_new_user','9bcddfc3ced0dbf170783a3d5a3d2b90','content/view/full/169',1,0,0),(147,'discussions/folder','fb8d0dc27cea3b666b0e76e4b6805d77','content/view/full/152',1,148,0),(148,'discussions/forum_main_group','cb4217f89d8a4365cfef45f8cb50a1cc','content/view/full/152',1,0,0),(149,'discussions/forum_main_group/music_discussion','a1a79985f113d5b05b22c9686b46b175','content/view/full/114',1,0,0),(150,'discussions/music_discussion/*','2ec2a3bfcf01ad3f1323390ab26dfeac','discussions/forum_main_group/music_discussion/{1}',1,0,1),(152,'discussions/sports_discussion/*','7acbf48218ca6e1d80c267911860d34f','discussions/forum_main_group/sports_discussion/{1}',1,0,1),(156,'news/choose_the_correct_forum','6d6b4946f3fdbdd11431feb374b85a36','content/view/full/156',1,0,0),(157,'news/latest_forum_dreamcars','0ab8369e6bbe6629b18f53c2b7d7231a','content/view/full/157',1,0,0),(158,'discussions/forum_main_group/dreamcars','cf8fa92c99b243b23cdf1bd393b406af','content/view/full/158',1,0,0),(159,'discussions/forum_main_group/dreamcars/koenigsegg_is_the_master','4a018a78c744e72b44334a823e368ab3','content/view/full/159',1,0,0),(160,'discussions/forum_main_group/dreamcars/cant_leave_ferrari_f40_out_of_it','838454f3eaa615da6d3330d50dd89e92','content/view/full/160',1,0,0),(161,'discussions/forum_main_group/dreamcars/koenigsegg_is_the_master/knigsegg_is_the_best','1571e43da0713dbfb79d02074f0fd1bc','content/view/full/161',1,0,0),(163,'discussions/forum_main_group/sports_discussion/football/*','b12d788dbcae0e42a75222e6f87b8a76','discussions/forum_main_group/sports_discussion/what_is_the_best_football_team_in_europe/{1}',1,0,1),(164,'discussions/forum_main_group/sports_discussion/what_is_the_best_football_team_in_europe/who_is_odd','c09a58e324a83f2a9dfa1ce426a23385','content/view/full/133',1,0,0);
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
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d'),(187,'','',1,''),(189,'','',1,'');
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

alter table ezrss_export add rss_version varchar(255) default null;
